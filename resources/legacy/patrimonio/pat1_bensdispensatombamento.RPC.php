<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("libs/JSON.php");
require_once("model/contabilidade/ParametroIntegracaoPatrimonial.model.php");

$oJson       = new services_json();
$oParametros = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->sMensagem = "";

$oDaoEmpnotaitembenspendente = db_utils::getDao("empnotaitembenspendente");
$oDaoBensDispensaTombamento  = db_utils::getDao("bensdispensatombamento");

/**
 * Caminho das mensagens do programa
 */
define('MENSAGENS', 'patrimonial.patrimonio.pat1_bensdispensatombamento.');

try {

  db_inicio_transacao();

  switch ( $oParametros->sExecucao ) {

    /**
     * Estonar
     * - documento 208
     */
    case 'processar':

      $oParametros->sJustificativa = db_stdClass::normalizeStringJsonEscapeString($oParametros->sJustificativa);
      $oParametros->sDescricaoItem = db_stdClass::normalizeStringJsonEscapeString($oParametros->sDescricaoItem);

      $sWhere               = "e137_empnotaitem = {$oParametros->iCodigoEmpNotaItem}";
      $sSqlBensNotaPendente = $oDaoEmpnotaitembenspendente->sql_query_file(null,"*",null,$sWhere);
      $rsBensNotaPendente   = db_query($sSqlBensNotaPendente);

      if (!$rsBensNotaPendente || pg_num_rows($rsBensNotaPendente) == 0 ) {
        throw new Exception (_M(MENSAGENS."nota_item_nao_encontrado"));
      }

      $oDadosEmpnotaitem = db_utils::fieldsMemory($rsBensNotaPendente,0);
      $oDaoEmpnotaitembenspendente->excluir($oDadosEmpnotaitem->e137_sequencial);

      if ($oDaoEmpnotaitembenspendente->erro_status == "0") {
        throw new Exception (_M(MENSAGENS."nota_item_nao_excluindo"));
      }

      $oDaoBensDispensaTombamento->e139_empnotaitem    = $oDadosEmpnotaitem->e137_empnotaitem;
      $oDaoBensDispensaTombamento->e139_matestoqueitem = $oDadosEmpnotaitem->e137_matestoqueitem;
      $oDaoBensDispensaTombamento->e139_justificativa  = $oParametros->sJustificativa;
      $oDaoBensDispensaTombamento->e139_codcla         = $oParametros->iClassificacao;
      $oDaoBensDispensaTombamento->e139_codordem       = $oParametros->iCodigoNota;
      $dataaquisicao = explode('/',$oParametros->dataaquisicao);
      $oDaoBensDispensaTombamento->e139_datadispensa   = $dataaquisicao[2].'-'.$dataaquisicao[1].'-'.$dataaquisicao[0];
      $oDaoBensDispensaTombamento->incluir(null);

      if ($oDaoBensDispensaTombamento->erro_status == "0") {

        $oDadosErro = (object) array('sErroBanco' => $oDaoBensDispensaTombamento->erro_banco);
        throw new Exception (_M(MENSAGENS."dispensa_tombamento_nao_incluido", $oDadosErro));
      }

      processarLancamento(208, $oDadosEmpnotaitem->e137_matestoqueitem, $oDadosEmpnotaitem->e137_empnotaitem, $oParametros);

      $oRetorno->sMensagem = _M(MENSAGENS . 'processamento_efetuado_sucesso');
    break;

    /**
     * Estonar
     * - documento 209
     */
    case 'estornar':

      $oParametros->sJustificativa = db_stdClass::normalizeStringJsonEscapeString($oParametros->sJustificativa);
      $oParametros->sDescricaoItem = db_stdClass::normalizeStringJsonEscapeString($oParametros->sDescricaoItem);
      $sWhere = "e139_empnotaitem = {$oParametros->iCodigoEmpNotaItem}";
      $sSqlBensDispensaTombamento = $oDaoBensDispensaTombamento->sql_query_file(null,"*",null,$sWhere);
      $rsBensDispensaTombamento   = db_query($sSqlBensDispensaTombamento);

      if (!$rsBensDispensaTombamento || pg_num_rows($rsBensDispensaTombamento) == 0 ){

        $oDadosErro = (object) array('sErroBanco' => pg_last_error());
        throw new Exception (_M(MENSAGENS."dispensa_tombamento_nao_encontrado", $oDadosErro));
      }

      $oDadosTombamento = db_utils::fieldsMemory($rsBensDispensaTombamento,0);
      $oDaoBensDispensaTombamento->excluir($oDadosTombamento->e139_sequencial);

      if ($oDaoBensDispensaTombamento->erro_status == 0) {

        $oDadosErro = (object) array('sErroBanco' => $oDaoBensDispensaTombamento->erro_banco);
        throw new Exception (_M(MENSAGENS."dispensa_tombamento_nao_excluido", $oDadosErro));
      }

      $oDaoEmpnotaitembenspendente->e137_empnotaitem    = $oDadosTombamento->e139_empnotaitem;
      $oDaoEmpnotaitembenspendente->e137_matestoqueitem = $oDadosTombamento->e139_matestoqueitem;
      $oDaoEmpnotaitembenspendente->incluir(null);

      if ( $oDaoEmpnotaitembenspendente->erro_status == "0" ) {

        $oDadosErro = (object) array('sErroBanco' => pg_last_error());
        throw new Exception (_M(MENSAGENS . "nota_item_nao_incluido", $oDadosErro));
      }

      processarLancamento(209, $oDadosTombamento->e139_matestoqueitem, $oDadosTombamento->e139_empnotaitem, $oParametros);
      $oRetorno->sMensagem = _M(MENSAGENS . 'estorno_efetuado_sucesso');

      break;

    default :
      throw new Exception('Par�metro inv�lido');
      break;
  }

  db_fim_transacao(false);

} catch ( Exception $eErro ) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $eErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);

/**
 * Processa lancamento
 *
 * @param integer $iCodigoDocumento
 * @param integer $iCodigoItemEstoque
 * @param integer $iCodigoItemNota
 * @param stdClass $oParametros
 * @access public
 * @throws Exception|DBException
 * @return true
 */
function processarLancamento($iCodigoDocumento, $iCodigoItemEstoque, $iCodigoItemNota, $oParametros) {
 
  $oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
 
  $oInstituicao      = new Instituicao(db_getsession('DB_instit'));

  /**
   * Verifica se para data atual existe integracao com financeiro para efetuar lancamento
   */
  if ( !ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataImplantacao, $oInstituicao) ) {
    return false;
  }

  if ( empty($oParametros->iNumeroEmpenho) ) {
    throw new Exception('N�mero de empenho n�o informado.');
  }

  /**
   * Busca codigo da nota de liquidacao pelo codigo do item
   */
  $oDaoEmpnotaitem  = db_utils::getDao('empnotaitem');
  $sSqlNotaLiquidacao = $oDaoEmpnotaitem->sql_query_empenho_item($iCodigoItemNota);
  $rsNotaLiquidacao = $oDaoEmpnotaitem->sql_record($sSqlNotaLiquidacao);

  if ( $oDaoEmpnotaitem->erro_status == "0" ) {
    throw new DBException("Erro ao buscar item da nota, item n�o encontrado: $iCodigoItemNota.");
  }

  $iCodigoNotaLiquidacao = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e72_codnota;
  $iNumeroNotaLiquidacao = db_utils::fieldsMemory($rsNotaLiquidacao, 0)->e69_numero;

  $oDaoMaterialEstoqueGrupo = db_utils::getDao('materialestoquegrupo');
  $sWhere                   = "m71_codlanc = {$iCodigoItemEstoque}";
  $sSqlMaterialGrupo        = $oDaoMaterialEstoqueGrupo->sql_query_grupoitem(null, 'm65_sequencial', null, $sWhere);
  $rsMaterialGrupo          = $oDaoMaterialEstoqueGrupo->sql_record($sSqlMaterialGrupo);

  if ($oDaoMaterialEstoqueGrupo->erro_status == "0") {

    $oDadosErro = (object) array('sErroBanco' => $oDaoMaterialEstoqueGrupo->erro_banco);
    throw new Exception (_M(MENSAGENS . "grupo_material_nao_encontrado", $oDadosErro));
  }

  $oDadosEstoqueGrupo = db_utils::fieldsMemory($rsMaterialGrupo, 0);
  $iCodigoGrupo       = $oDadosEstoqueGrupo->m65_sequencial;

  $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oParametros->iNumeroEmpenho);

  /**
   * OC12129
   * Verificacoes para documento de controle de liquidacao de materiais permanentes com entrada do bem realizada
   * em ano diferente do ano da emissao do empenho.
   */

  if ( $oEmpenhoFinanceiro->isMaterialPermanente() && $oEmpenhoFinanceiro->getAnoUso() < db_getsession("DB_anousu") ) {
    if ($iCodigoDocumento == 208) {
      $iCodigoDocumento = 214;
    } elseif($iCodigoDocumento == 209) {
      $iCodigoDocumento = 215;
    }
  }
  $aItensEmpenho = $oEmpenhoFinanceiro->getItens();
  $dataaquisicao = explode('/',$oParametros->dataaquisicao);
  $oDataEstorno  = $dataaquisicao[2].'-'.$dataaquisicao[1].'-'.$dataaquisicao[0];
  
  $oEventoContabil = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));

  $nValorNota = $oParametros->nValorNota;
  $nValorNota = str_replace('.', '', $nValorNota);
  $nValorNota = str_replace(',', '.', $nValorNota);

  $sObservacao = "{$oParametros->sJustificativa} | ITEM: {$oParametros->sDescricaoItem}, ";
  $sObservacao .= " QUANTIDADE ADQUIRIDA: {$oParametros->iQuantidadeItem}, NOTA FISCAL: {$iNumeroNotaLiquidacao}.";

  $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
  $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
  $oContaCorrenteDetalhe->setDotacao($oEmpenhoFinanceiro->getDotacao());
  $oRecurso = new Recurso($oEmpenhoFinanceiro->getDotacao()->getRecurso());
  $oContaCorrenteDetalhe->setRecurso($oRecurso);
  $oLancamentoAuxiliarEmLiquidacao = new LancamentoAuxiliarEmLiquidacaoMaterialPermanente();
  $oLancamentoAuxiliarEmLiquidacao->setClassificacao(new BemClassificacao($oParametros->iClassificacao));
  $oLancamentoAuxiliarEmLiquidacao->setObservacaoHistorico($sObservacao);
  $oLancamentoAuxiliarEmLiquidacao->setFavorecido($oEmpenhoFinanceiro->getFornecedor()->getCodigo());
  $oLancamentoAuxiliarEmLiquidacao->setCodigoElemento($aItensEmpenho[0]->getCodigoElemento());
  $oLancamentoAuxiliarEmLiquidacao->setNumeroEmpenho($oParametros->iNumeroEmpenho);
  $oLancamentoAuxiliarEmLiquidacao->setCodigoDotacao($oEmpenhoFinanceiro->getDotacao()->getCodigo());
  $oLancamentoAuxiliarEmLiquidacao->setCodigoNotaLiquidacao($iCodigoNotaLiquidacao);
  $oLancamentoAuxiliarEmLiquidacao->setValorTotal($nValorNota);
  $oLancamentoAuxiliarEmLiquidacao->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

  $oEventoContabil->executaLancamento($oLancamentoAuxiliarEmLiquidacao,$oDataEstorno);
  
  return true;
}
