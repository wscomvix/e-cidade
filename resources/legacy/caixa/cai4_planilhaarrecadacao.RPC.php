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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once 'libs/db_liborcamento.php';
require_once 'model/contabilidade/planoconta/ContaPlano.model.php';
require_once 'model/contabilidade/planoconta/ContaOrcamento.model.php';
require_once 'model/contabilidade/planoconta/ClassificacaoConta.model.php';
require_once 'model/contabilidade/planoconta/SistemaConta.model.php';
require_once 'model/contabilidade/planoconta/SubSistemaConta.model.php';
require_once 'model/CgmFactory.model.php';
require_once 'model/configuracao/InstituicaoRepository.model.php';
require_once 'model/caixa/PlanilhaArrecadacao.model.php';
require_once 'model/caixa/ReceitaPlanilha.model.php';
require_once 'model/caixa/AutenticacaoPlanilha.model.php';

db_app::import("exceptions.*");
db_app::import("configuracao.Instituicao");
db_app::import("configuracao.DBEstrutura");
db_app::import("CgmFactory");
db_app::import("contaTesouraria");
db_app::import("contabilidade.*");
db_app::import("orcamento.*");
db_app::import("configuracao.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("exceptions.*");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;

switch ($oParam->exec) {


	case "excluirPlanilha" :


		$iPlanilha       = $oParam->iPlanilha;

		db_inicio_transacao();

		try {

      $oPlanilha = new PlanilhaArrecadacao($iPlanilha);
      $oPlanilha->excluir();

			db_fim_transacao(false);

		} catch (Exception $oExceptionErro) {

    	db_fim_transacao(true);
    	$oRetorno->status  = 2;
    	$oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }


	break;

  case 'salvarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao();
      $dtArrecadacao = formateDateReverse($oParam->novaDtRecebimento);
      $oPlanilhaArrecadacao->setDataCriacao($dtArrecadacao);
      $oPlanilhaArrecadacao->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
      $oPlanilhaArrecadacao->setProcessoAdministrativo(db_stdClass::normalizeStringJsonEscapeString($oParam->k144_numeroprocesso));

      foreach ($oParam->aReceitas as $oReceitas) {

        $iNumeroCgm = buscaCgmOrigem($oReceitas);
        $iInscricao = empty($oReceitas->iInscricao) ? '' : $oReceitas->iInscricao;
        $iMatricula = empty($oReceitas->iMatricula) ? '' : $oReceitas->iMatricula;
        $iConvenio  = empty($oReceitas->iConvenio)  ? '' : $oReceitas->iConvenio;

        $oReceitaPlanilha = new ReceitaPlanilha();
        $oReceitaPlanilha->setCaracteristicaPeculiar(new CaracteristicaPeculiar($oReceitas->iCaracteriscaPeculiar));
        $oReceitaPlanilha->setCGM(CgmFactory::getInstanceByCgm($iNumeroCgm));
        $oContaTesouraria = new contaTesouraria($oReceitas->iContaTesouraria);
        $oContaTesouraria->validaContaPorDataMovimento($dtArrecadacao);
        $oReceitaPlanilha->setContaTesouraria($oContaTesouraria);
        $oReceitaPlanilha->setDataRecebimento(new DBDate($oReceitas->dtRecebimento));
        $oReceitaPlanilha->setInscricao($iInscricao);
        $oReceitaPlanilha->setMatricula($iMatricula);
        $oReceitaPlanilha->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oReceitas->sObservacao));
        $oReceitaPlanilha->setOperacaoBancaria($oReceitas->sOperacaoBancaria);
        $oReceitaPlanilha->setOrigem($oReceitas->iOrigem);
        $oReceitaPlanilha->setRecurso(new Recurso($oReceitas->iRecurso));
        $oReceitaPlanilha->setRegularizacaoRepasse($oReceitas->iRegRepasse);
        $oReceitaPlanilha->setRegExercicio($oReceitas->iExerc);
        $oReceitaPlanilha->setEmendaParlamentar($oReceitas->iEmParlamentar);
        $oReceitaPlanilha->setTipoReceita($oReceitas->iReceita);
        $oReceitaPlanilha->setValor($oReceitas->nValor);
        $oReceitaPlanilha->setConvenio($iConvenio);
        $oPlanilhaArrecadacao->adicionarReceitaPlanilha($oReceitaPlanilha);
      }

      $oPlanilhaArrecadacao->salvar();
      db_fim_transacao(false);

      $sMensagemRetorno  = "Planilha {$oPlanilhaArrecadacao->getCodigo()} inclusa com sucesso.\n\n";
      $sMensagemRetorno .= "Deseja imprimir o documento gerado?";
      $oRetorno->message         = urlencode($sMensagemRetorno);
      $oRetorno->iCodigoPlanilha = $oPlanilhaArrecadacao->getCodigo();


    } catch (Exception $oExceptionErro) {

    	db_fim_transacao(true);
    	$oRetorno->status  = 2;
    	$oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }
    break;

  case 'estornarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao($oParam->iPlanilha);
      $oPlanilhaArrecadacao->setDataAutenticacao(formateDateReverse($oParam->dataestorno));
      $oPlanilhaArrecadacao->estornar();
      db_fim_transacao(false);
      $oRetorno->message = urlencode("Planilha {$oPlanilhaArrecadacao->getCodigo()} estornada com sucesso.");

    } catch (Exception $oExceptionErro) {

    	db_fim_transacao(true);
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($oExceptionErro->getMessage());
    }

    break;

  case 'importarPlanilha':
  case 'getDadosPlanilhaArrecadacao':

    $oPlanilhaArrecadacao     = new PlanilhaArrecadacao($oParam->iPlanilha);
    $aReceitasPlanilha        = $oPlanilhaArrecadacao->getReceitasPlanilha();

    $oPlanilha                = new stdClass();
    $oPlanilha->aReceitas     = array();
    $oPlanilha->iPlanilha     = $oPlanilhaArrecadacao->getCodigo();
    $oData                    = $oPlanilhaArrecadacao->getDataCriacao();
    $oPlanilha->dtDataCriacao = $oData->getDate(DBDate::DATA_PTBR);
    $oPlanilha->k144_numeroprocesso = $oPlanilhaArrecadacao->getProcessoAdministrativo();

    if (count($aReceitasPlanilha) > 0) {

      foreach ($aReceitasPlanilha as $oReceitaPlanilha) {

        $oReceita                        = new stdClass();
        $oReceita->iCodigo               = $oReceitaPlanilha->getCodigo();
        $oReceita->iReceita              = $oReceitaPlanilha->getTipoReceita();
        $oReceita->sDescricaoReceita     = urlencode($oReceitaPlanilha->getDescricaoReceita());

        $oReceita->iOrigem               = $oReceitaPlanilha->getOrigem();
        $oReceita->iCgm                  = $oReceitaPlanilha->getCGM()->getCodigo();
        $oReceita->iInscricao            = $oReceitaPlanilha->getInscricao();
        $oReceita->iMatricula            = $oReceitaPlanilha->getMatricula();
        $oReceita->iCaracteriscaPeculiar = $oReceitaPlanilha->getCaracteristicaPeculiar()->getSequencial();

        $oContaTesouraria                = $oReceitaPlanilha->getContaTesouraria();
        $oReceita->iContaTesouraria      = $oContaTesouraria->getCodigoConta();
        $oReceita->sDescricaoConta       = urlencode($oContaTesouraria->getDescricao());

        $oReceita->dtRecebimento         = $oReceitaPlanilha->getDataRecebimento()->convertTo(DBDate::DATA_PTBR);
        $oReceita->sObservacao           = urlencode($oReceitaPlanilha->getObservacao());
        $oReceita->sOperacaoBancaria     = $oReceitaPlanilha->getOperacaoBancaria();
        $oReceita->iRecurso              = $oReceitaPlanilha->getRecurso()->getCodigoRecurso();
        $oReceita->iRegRepasse           = $oReceitaPlanilha->getRegularizacaoRepasse();
        $oReceita->iExerc                = $oReceitaPlanilha->getRegExercicio();
        $oReceita->iEmParlamentar        = $oReceitaPlanilha->getEmendaParlamentar();
        $oReceita->nValor                = $oReceitaPlanilha->getValor();
        $oReceita->iConvenio             = $oReceitaPlanilha->getConvenio();

        $oPlanilha->aReceitas[]          = $oReceita;
        unset($oReceita);
      }
    }

    $oRetorno->oPlanilha = $oPlanilha;
    break;

  case 'autenticarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao($oParam->iPlanilha);
      $oPlanilhaArrecadacao->setDataAutenticacao(formateDateReverse($oParam->novaDtRecebimento));
      $oPlanilhaArrecadacao->getReceitasPlanilha();
      $oPlanilhaArrecadacao->autenticar();

      $sNumeroProcesso = addslashes(db_stdClass::normalizeStringJson($oParam->k144_numeroprocesso));
      if ( !empty($sNumeroProcesso) ) {

        $oDaoPlacaixaProcesso = new cl_placaixaprocesso();

        $oDaoPlacaixaProcesso->k144_placaixa       = $oParam->iPlanilha;
        $oDaoPlacaixaProcesso->k144_numeroprocesso = $sNumeroProcesso;

        $sSqlProcesso         = $oDaoPlacaixaProcesso->sql_query_file(null, "*", null, "k144_placaixa = {$oParam->iPlanilha}" );
        $rsProcesso           = $oDaoPlacaixaProcesso->sql_record($sSqlProcesso);
        if ($oDaoPlacaixaProcesso->numrows > 0 ) {

          $oDadosProcesso = db_utils::fieldsMemory($rsProcesso, 0);
          $oDaoPlacaixaProcesso->k144_sequencial = $oDadosProcesso->k144_sequencial;
          $oDaoPlacaixaProcesso->alterar($oDaoPlacaixaProcesso->k144_sequencial);
        } else {
          $oDaoPlacaixaProcesso->incluir(null);
        }

        if ($oDaoPlacaixaProcesso->erro_status == 0) {
          throw new Exception($oDaoPlacaixaProcesso->erro_msg);
        }

      }

      db_fim_transacao(false);

      $sMensagemRetorno    = "Planilha {$oPlanilhaArrecadacao->getCodigo()} autenticada com sucesso.\n\n";
      $sMensagemRetorno   .= "Deseja imprimir o documento gerado?";
      $oRetorno->message   = urlencode($sMensagemRetorno);
      $oRetorno->iPlanilha = $oPlanilhaArrecadacao->getCodigo();

    } catch (Exception $oExceptionErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }
    break;

  case 'alterarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao($oParam->iCodigoPlanilha);
      $oPlanilhaArrecadacao->excluirReceitas();

      $oPlanilhaArrecadacao->setProcessoAdministrativo(db_stdClass::normalizeStringJsonEscapeString($oParam->k144_numeroprocesso));

      foreach ($oParam->aReceitas as $oReceitas) {

        $iNumeroCgm       = buscaCgmOrigem($oReceitas);
        $iInscricao       = empty($oReceitas->iInscricao) ? '' : $oReceitas->iInscricao;
        $iMatricula       = empty($oReceitas->iMatricula) ? '' : $oReceitas->iMatricula;
        $iConvenio        = empty($oReceitas->iConvenio)  ? '' : $oReceitas->iConvenio;

        $oReceitaPlanilha = new ReceitaPlanilha(null);
        $oReceitaPlanilha->setCaracteristicaPeculiar(new CaracteristicaPeculiar($oReceitas->iCaracteriscaPeculiar));
        $oReceitaPlanilha->setCGM(CgmFactory::getInstanceByCgm($iNumeroCgm));
        $oReceitaPlanilha->setContaTesouraria(new contaTesouraria($oReceitas->iContaTesouraria));
        $oReceitaPlanilha->setDataRecebimento(new DBDate($oReceitas->dtRecebimento));
        $oReceitaPlanilha->setInscricao($iInscricao);
        $oReceitaPlanilha->setMatricula($iMatricula);
        $oReceitaPlanilha->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oReceitas->sObservacao));
        $oReceitaPlanilha->setOperacaoBancaria($oReceitas->sOperacaoBancaria);
        $oReceitaPlanilha->setOrigem($oReceitas->iOrigem);
        $oReceitaPlanilha->setRecurso(new Recurso($oReceitas->iRecurso));
        $oReceitaPlanilha->setRegularizacaoRepasse($oReceitas->iRegRepasse);
        $oReceitaPlanilha->setRegExercicio($oReceitas->iExerc);
        $oReceitaPlanilha->setEmendaParlamentar($oReceitas->iEmParlamentar);
        $oReceitaPlanilha->setTipoReceita($oReceitas->iReceita);
        $oReceitaPlanilha->setValor($oReceitas->nValor);
        $oReceitaPlanilha->setConvenio($iConvenio);

        $oPlanilhaArrecadacao->adicionarReceitaPlanilha($oReceitaPlanilha);
      }
      $oPlanilhaArrecadacao->salvar();

      db_fim_transacao(false);

      $sMensagemRetorno          = "Planilha {$oPlanilhaArrecadacao->getCodigo()} salva com sucesso.\n\n";
      $sMensagemRetorno         .= "Deseja imprimir o documento gerado?";
      $oRetorno->message         = urlencode($sMensagemRetorno);
      $oRetorno->iCodigoPlanilha = $oPlanilhaArrecadacao->getCodigo();

    } catch (Exception $oExceptionErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }
  break;

  case 'buscarDeducao':

    try{ 
        $oRetorno->oReceita = buscarDeducao($oParam->k81_receita); 
        $oRetorno->oReceita->k02_descr = urlencode($oRetorno->oReceita->k02_descr);

    }catch (Exception $oExceptionErro) {
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }

   break;

   case 'excluirAutenticacaoPlanilha':

    $iPlanilha       = $oParam->iPlanilha;


    try {

      db_inicio_transacao();

      $oPlanilha = new PlanilhaArrecadacao($iPlanilha);
      $oPlanilha->estornar();
      $oPlanilha->excluirAutenticacao();

      db_fim_transacao(false);

    } catch (Exception $oExceptionErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }

   break;

   	case 'buscaReceitaFundep':

		try {

			$oRetorno->oReceita = buscaReceitaFundep($oParam->k81_receita);
			$oRetorno->oReceita->k02_descr = urlencode($oRetorno->oReceita->k02_descr);

		} catch (Exception $oExceptionErro) {

			$oRetorno->status  = 2;
			$oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
		
		}

	break;

    case 'verificaReceitaAutoComplete':

      $descricao = strtoupper($oParam->iDescricao);
      $cltabrec = new cl_tabrec;
      $sSqlCampos   = " * ";
      $sSqlWhere    = " k02_drecei ILIKE '%$descricao%' or k02_estorc ILIKE '%$descricao%' or o70_codigo ::text ILIKE '%$descricao%' ";
      $sSqlMonta    = $cltabrec->sql_query_inst(null, $sSqlCampos, "k02_codigo", $sSqlWhere);
     
      $rsQuery = $cltabrec->sql_record($sSqlMonta);

      if ($cltabrec->numrows > 0) {
        for($i=0;$i<$cltabrec->numrows;$i++){
          $oDadosRetorno = db_utils::fieldsMemory($rsQuery, $i);

          $objRenomeado = new stdClass();

          $objRenomeado->campo1 = $oDadosRetorno->k02_codigo;
          $objRenomeado->campo2 = utf8_encode($oDadosRetorno->k02_drecei);
          $objRenomeado->campo3 = $oDadosRetorno->k02_estorc ." - ".$oDadosRetorno->o70_codigo;
          $oRetorno->oDados[] = $objRenomeado;
        } 
        $oRetorno->inputField  = $oParam->inputField;
        $oRetorno->inputCodigo = $oParam->inputCodigo;
        $oRetorno->ulField     = $oParam->ulField;
        $oRetorno->status      = 2;
        $oRetorno->tipo        = 1; // Tipo 2 AutoComplete Historicos
      }

  break;  

  case 'verificaContaAutoComplete':

    $descricao = strtoupper($oParam->iDescricao);
    $clsaltes = new cl_saltes;
    $sSqlCampos   = " * ";
    $sSqlWhere    = " k13_descr ILIKE '%$descricao%'";
    $sSqlMonta    = $clsaltes->sql_query_anousu(null, $sSqlCampos, "c61_codigo", $sSqlWhere);

    $rsQuery = $clsaltes->sql_record($sSqlMonta);
  
    if ($clsaltes->numrows > 0) {
      for($i=0;$i<$clsaltes->numrows;$i++){
        $oDadosRetorno = db_utils::fieldsMemory($rsQuery, $i);

        $objRenomeado = new stdClass();

        $objRenomeado->campo1 = $oDadosRetorno->k13_reduz;
        $objRenomeado->campo2 = utf8_encode($oDadosRetorno->k13_descr);
        $objRenomeado->campo3 = $oDadosRetorno->c61_codigo;
        $oRetorno->oDados[] = $objRenomeado;
      } 
      $oRetorno->inputField  = $oParam->inputField;
      $oRetorno->inputCodigo = $oParam->inputCodigo;
      $oRetorno->ulField     = $oParam->ulField;
      $oRetorno->status      = 2;
      $oRetorno->tipo        = 1; // Tipo 2 AutoComplete Historicos
    }
    
break;  


}
/**
 * Busca o dedutora da receita caso exista
 * @param integer $iInscricao
 * @throws BusinessException
 */
function buscarDeducao($iReceita) {

  if (empty($iReceita)) {

    $sMsgErro = "C�digo da Receita vazio ou n�o informado.";
    throw new BusinessException($sMsgErro);
  }

  $oDaoReceita   = db_utils::getDao('tabrec');
  $sSqlReceita = $oDaoReceita->sql_query_rec_deducao($iReceita);
  $rsReceita   = $oDaoReceita->sql_record($sSqlReceita);

  if ($rsReceita && $oDaoReceita->numrows == 1) {
    return db_utils::fieldsMemory($rsReceita,0);
  }

}

/**
 * "Factory method" para retornar o cgm de acordo com a origem
 * @param stdClass $oParam
 * @throws BusinessException
 * @return integer
 */
function buscaCgmOrigem($oParam) {

  $iNumeroCmg = "";
  switch ($oParam->iOrigem) {

    case 1:

      $iNumeroCmg = $oParam->iCgm;
      break;

    case 2:

      $iNumeroCmg = buscaCgmInscricao($oParam->iInscricao);
      break;

    case 3:

      $iNumeroCmg = buscaCgmMatricula($oParam->iMatricula);
      break;
    default:
      throw new BusinessException("Origem n�o identificada.");
  }

  return $iNumeroCmg;
}

/**
 * Busca o cgm da inscricao
 * @param integer $iInscricao
 * @throws BusinessException
 */
function buscaCgmInscricao($iInscricao) {

  if (empty($iInscricao)) {

    $sMsgErro = "N�mero da inscricao vazio ou n�o informado.";
    throw new BusinessException($sMsgErro);
  }

  $oDaoIssBase   = db_utils::getDao('issbase');
  $sSqlInscricao = $oDaoIssBase->sql_query_file($iInscricao);
  $rsInscricao   = $oDaoIssBase->sql_record($sSqlInscricao);

  if ($rsInscricao && $oDaoIssBase->numrows == 1) {

    return db_utils::fieldsMemory($rsInscricao, 0)->q02_numcgm;
  }

  $sMsgErro  = "Erro ao buscar cgm da inscricao.\n";
  $sMsgErro .= "Inscri��o: {$iInscricao}";
  $sMsgErro .= $oDaoIssBase->erro_msg;

  throw new BusinessException($sMsgErro);

}




/**
 * Busca o cgm da matricula
 * @param integer $iMatricula
 * @throws BusinessException
 */
function buscaCgmMatricula($iMatricula) {

  if (empty($iMatricula)) {

    $sMsgErro = "N�mero da matricula vazio ou n�o informado.";
    throw new BusinessException($sMsgErro);
  }

  $oDaoIptuBase   = db_utils::getDao('iptubase');
  $sSqlMatricula  = $oDaoIptuBase->sql_query_file($iMatricula);
  $rsMatricula    = $oDaoIptuBase->sql_record($sSqlMatricula);

  if ($rsMatricula && $oDaoIptuBase->numrows == 1) {

    return db_utils::fieldsMemory($rsMatricula, 0)->j01_numcgm;
  }

  $sMsgErro  = "Erro ao buscar cgm da matricula.\n";
  $sMsgErro .= "Matricula: {$iMatricula}.\n";
  $sMsgErro .= $oDaoIptuBase->erro_msg;

  throw new BusinessException($sMsgErro);

}

function formateDateReverse(string $date): string
{
    $data_objeto = DateTime::createFromFormat('d/m/Y', $date);
    $data_formatada = $data_objeto->format('Y-m-d');
    return date('Y-m-d', strtotime($data_formatada));
}

function buscaReceitaFundep($iReceita) {
  
	$oDaoTabRec = db_utils::getDao('tabrec');
	$sCampos 	= "tabrec.k02_codigo, k02_descr, o70_codigo";
  
  if($iReceita =='118'){
	if (db_getsession("DB_anousu") > 2021) 
        $sWhere 	= "k02_estorc like '417515001%' and o70_codigo = '119' limit 1";
      else
        $sWhere 	= "k02_estorc like '417580111%' and o70_codigo = '119' limit 1";
        
      $sSqlTabRec = $oDaoTabRec->sql_query_concarpeculiar(null, $sCampos, null, $sWhere);
      $rsTabRec 	= $oDaoTabRec->sql_record($sSqlTabRec);
      
  }if($iReceita =='166'){
     if (db_getsession("DB_anousu") > 2021) 
            $sWhere 	= "k02_estorc like '417155001%' and o70_codigo = '167' limit 1";
        else
            $sWhere 	= "k02_estorc like '417580111%' and o70_codigo = '119' limit 1";
        
        $sSqlTabRec = $oDaoTabRec->sql_query_concarpeculiar(null, $sCampos, null, $sWhere);
        $rsTabRec 	= $oDaoTabRec->sql_record($sSqlTabRec);
  }
  if($iReceita =='15400007'){
    if (db_getsession("DB_anousu") > 2022) 
          $sWhere 	= "k02_estorc like '417515001%' and o70_codigo = '15400000' limit 1";
        else
          $sWhere 	= "k02_estorc like '417580111%' and o70_codigo = '15400000' limit 1";
          
        $sSqlTabRec = $oDaoTabRec->sql_query_concarpeculiar(null, $sCampos, null, $sWhere);
        $rsTabRec 	= $oDaoTabRec->sql_record($sSqlTabRec);
        
  }if($iReceita =='15420007'){
       if (db_getsession("DB_anousu") > 2022) 
              $sWhere 	= "k02_estorc like '417155001%' and o70_codigo = '15420000' limit 1";
          else
              $sWhere 	= "k02_estorc like '417580111%' and o70_codigo = '15400000' limit 1";
          
          $sSqlTabRec = $oDaoTabRec->sql_query_concarpeculiar(null, $sCampos, null, $sWhere);
          $rsTabRec 	= $oDaoTabRec->sql_record($sSqlTabRec);
    }  
  
  if ($rsTabRec && $oDaoTabRec->numrows == 1) {
		return db_utils::fieldsMemory($rsTabRec,0);
	}
  else {
		if($iReceita == '118')
		  $sMsgErro = "Para realizar arrecada��o da receita do FUNDEB � necess�rio que a receita de fonte 119 esteja cadastrada na tesouraria.";
    if($iReceita == '166')
		  $sMsgErro = "Para realizar arrecada��o da receita do FUNDEB � necess�rio que a receita de fonte 167 esteja cadastrada na tesouraria.";
    if($iReceita == '15400007')
		  $sMsgErro = "Para realizar arrecada��o da receita do FUNDEB � necess�rio que a receita de fonte 15400000 esteja cadastrada na tesouraria.";
    if($iReceita == '15420007')
		  $sMsgErro = "Para realizar arrecada��o da receita do FUNDEB � necess�rio que a receita de fonte 15420000 esteja cadastrada na tesouraria.";
		
      throw new BusinessException($sMsgErro);

	}
 

} 

echo $oJson->encode($oRetorno);
