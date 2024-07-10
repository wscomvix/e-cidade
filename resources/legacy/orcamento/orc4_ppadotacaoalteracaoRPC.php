<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->itens   = array();
$oRetorno->message = "";

$aParametros         = db_stdClass::getParametro("orcparametro", array(db_getsession("DB_anousu")));
$oParametroOrcamento = $aParametros[0];
if ($oParam->exec == "getElementosFromAcao") {

	/**
	 * @todo - remover
	 * Ativa PCASP quando arquivo de configura��o com ano do PCASP existir
	 */
	if ( !USE_PCASP && file_exists("legacy_config/pcasp.txt") ) {
		$_SESSION["DB_use_pcasp"] = "t";
	}

  $oRetorno->leivalida = false;
  $oDaoLei             = db_utils::getDao("ppaversao");
  $sSqlPPALei          = $oDaoLei->sql_query($oParam->o08_ppaversao);
  $rsPPALei            = $oDaoLei->sql_record($sSqlPPALei);
  if ($oDaoLei->numrows > 0) {

    $oDadosLei = db_utils::fieldsMemory($rsPPALei, 0);
    /**
     * Verifica se o ano base da lei � o ano da sessao.
     */
    if ($oDadosLei->o01_anoinicio -1 == db_getsession("DB_anousu")) {
      $oRetorno->leivalida = true;
    }
  }
  $oDaoPPaDotacao = db_utils::getDao("ppaestimativadespesa");
  $sCampos        = "distinct o08_orgao,
                     orcorgao.o40_descr,
                     o08_unidade,
                     o41_descr,
                     o08_funcao,
                     o52_descr,
                     o08_subfuncao,
                     o53_descr,
                     o08_programa,
                     o54_descr,
                     o08_projativ,
                     o55_descr,
                     o05_ppaversao,
                     o01_anoinicio,
                     o01_anofinal,
                     o08_concarpeculiar,
                     'P'||o119_versao||' - '||to_char(o119_datainicio, 'dd/mm/YYYY') as o01_descricao";

  $sWhere       =  " o08_instit=".db_getsession("DB_instit");
  $sWhere      .=  " and o08_orgao     = {$oParam->o40_orgao}";
  $sWhere      .=  " and o08_unidade   = {$oParam->o41_unidade}";
  $sWhere      .=  " and o08_funcao    = {$oParam->o52_funcao}";
  $sWhere      .=  " and o08_subfuncao = {$oParam->o53_subfuncao}";
  $sWhere      .=  " and o08_programa  = {$oParam->o54_programa}";
  $sWhere      .=  " and o08_projativ  = {$oParam->o55_projativ}";
  $sWhere      .=  " and o08_ppaversao = {$oParam->o08_ppaversao}";
  //$sWhere      .=  " and o08_ano       = ".db_getsession("DB_anousu");
  $sSqlDotacao  = $oDaoPPaDotacao->sql_query_conplano(
                                                      null,
                                                      $sCampos,
                                                      null,
                                                      $sWhere
                                                      );
  $rsDotacao  = $oDaoPPaDotacao->sql_record(analiseQueryPlanoOrcamento($sSqlDotacao));
//   db_criatabela($rsDotacao);die;
  $oDotacao   = db_utils::fieldsMemory($rsDotacao, 0, false, false, true);
  $oRetorno->o08_orgao              = $oDotacao->o08_orgao;
  $oRetorno->o08_orgao_original     = $oDotacao->o08_orgao;
  $oRetorno->o40_descr              = $oDotacao->o40_descr;
  $oRetorno->o08_unidade            = $oDotacao->o08_unidade;
  $oRetorno->o08_unidade_original   = $oDotacao->o08_unidade;
  $oRetorno->o41_descr              = $oDotacao->o41_descr;
  $oRetorno->o08_funcao             = $oDotacao->o08_funcao;
  $oRetorno->o08_funcao_original    = $oDotacao->o08_funcao;
  $oRetorno->o52_descr              = $oDotacao->o52_descr;
  $oRetorno->o08_subfuncao          = $oDotacao->o08_subfuncao;
  $oRetorno->o08_subfuncao_original = $oDotacao->o08_subfuncao;
  $oRetorno->o53_descr              = $oDotacao->o53_descr;
  $oRetorno->o08_programa           = $oDotacao->o08_programa;
  $oRetorno->o08_programa_original  = $oDotacao->o08_programa;
  $oRetorno->o54_descr              = $oDotacao->o54_descr;
  $oRetorno->o08_projativ           = $oDotacao->o08_projativ;
  $oRetorno->o08_projativ_original  = $oDotacao->o08_projativ;
  $oRetorno->o55_descr              = $oDotacao->o55_descr;
  $oRetorno->o05_ppaversao          = $oDotacao->o05_ppaversao;
  $oRetorno->o01_descricao          = $oDotacao->o01_descricao;
  $oRetorno->o01_anoinicio          = $oDotacao->o01_anoinicio;
  $oRetorno->o01_anofinal           = $oDotacao->o01_anofinal;
  $oRetorno->o08_concarpeculiar     = $oDotacao->o08_concarpeculiar;
  /**
   * Retornamos todos os elementos dessa dotacao;s
   *
   */
  $sCampos  = "o05_sequencial,";
  $sCampos .= "o08_sequencial,";
  $sCampos .= "o05_anoreferencia,";
  $sCampos .= "o08_elemento,";
  $sCampos .= "o56_descr,";
  $sCampos .= "o56_elemento,";
  $sCampos .= "o08_concarpeculiar,";
  $sCampos .= "o08_recurso,";
  $sCampos .= "o15_descr,";
  $sCampos .= "o08_localizadorgastos,";
  $sCampos .= "o11_descricao,";
  $sCampos .= "o05_valor,";
  $sCampos .= "o19_coddot";
  $sWhere  .= " and o05_base is false";
  $sSqlDotacaoItens  = $oDaoPPaDotacao->sql_query_conplano(
                                                             null,
                                                             $sCampos,
                                                             "
                                                             o08_elemento,
                                                             o08_recurso,
                                                             o08_localizadorgastos,
                                                             o05_anoreferencia",
                                                             $sWhere
                                                           );
  $rsDotacaoItens  = $oDaoPPaDotacao->sql_record(analiseQueryPlanoOrcamento($sSqlDotacaoItens));
  /**
   * @todo - remover
   * Ativa PCASP quando arquivo de configura��o com ano do PCASP existir
   */
  if ( !USE_PCASP && file_exists("legacy_config/pcasp.txt") ) {
  	$_SESSION["DB_use_pcasp"] = "f";
  }


  $oRetorno->itens = db_utils::getColectionByRecord($rsDotacaoItens,false, false, true);

} else if ($oParam->exec == "getInformacaoEstivativa") {

  $sCampos           = "o05_sequencial,";
  $sCampos          .= "o08_sequencial,";
  $sCampos          .= "o05_anoreferencia,";
  $sCampos          .= "o08_elemento,";
  $sCampos          .= "o56_descr,";
  $sCampos          .= "o56_elemento,";
  $sCampos          .= "o08_recurso,";
  $sCampos          .= "o15_descr,";
  $sCampos          .= "o08_localizadorgastos,";
  $sCampos          .= "o08_concarpeculiar,";
  $sCampos          .= "o11_descricao,";
  $sCampos          .= "round(o05_valor,2) as o05_valor, ";
  $sCampos          .= "o19_coddot";
  $oDaoPPaDotacao    = db_utils::getDao("ppaestimativadespesa");
  $sWhere            = "o05_sequencial = {$oParam->o05_sequencial}";
  $sSqlDotacaoItens  = $oDaoPPaDotacao->sql_query_conplano(
                                                             null,
                                                             $sCampos,
                                                             null,
                                                             $sWhere
                                                           );

  $rsDotacaoItens  = $oDaoPPaDotacao->sql_record(analiseQueryPlanoOrcamento($sSqlDotacaoItens));
  $oRetorno->itens = db_utils::fieldsMemory($rsDotacaoItens, 0, false, false, true);
} else if ($oParam->exec == "salvarElemento") {

    $sSql = "   SELECT p2.o08_sequencial,
                       o05_sequencial,
                       o05_valor,
                       p2.o08_ano
                    FROM ppadotacao p1
                    INNER JOIN ppadotacao p2
                        ON p1.o08_orgao         = p2.o08_orgao
                        AND p1.o08_unidade      = p2.o08_unidade
                        AND p1.o08_funcao       = p2.o08_funcao
                        AND p1.o08_subfuncao    = p2.o08_subfuncao
                        AND p1.o08_programa     = p2.o08_programa
                        AND p1.o08_projativ     = p2.o08_projativ
                        AND p1.o08_ppaversao    = p2.o08_ppaversao
                        AND p1.o08_elemento     = p2.o08_elemento
                        AND p1.o08_recurso      = p2.o08_recurso
                    INNER JOIN ppaestimativadespesa on o07_coddot = p2.o08_sequencial
                    INNER JOIN ppaestimativa ON ppaestimativa.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa
                WHERE p1.o08_sequencial = {$oParam->o08_sequencial} ";

    if ($oParam->atualiza_anos_seguintes) {
        $sSql .= " AND p2.o08_ano >= p1.o08_ano";
    } else {
        $sSql .= " AND p2.o08_ano = {$oParam->o05_anoreferencia}";
    }
    $sSql .= " ORDER BY o05_sequencial";

    $rsPpaAnos = db_query($sSql);

    for($iCont = 0; $iCont < pg_num_rows($rsPpaAnos); $iCont++) {

        $lSqlErro = false;
        db_inicio_transacao();

        $oPPAano = db_utils::fieldsMemory($rsPpaAnos, $iCont);

        if ($iCont == 0) {
            $iAnoInicio = $oPPAano->o08_ano;
        }

        /**
         * Apenas alteramos a cadastro da dotacao do ppa caso nao seje estimativa gerada pelo sistema
         */

        $oDaoPPaDotacao = db_utils::getDao("ppadotacao");
        if ($oParam->o19_coddot == "") {

            $oDaoPPaDotacao->o08_localizadorgastos = $oParam->o08_localizadorgastos;
            $oDaoPPaDotacao->o08_elemento          = $oParam->o08_elemento;
            $oDaoPPaDotacao->o08_recurso           = $oParam->o08_recurso;
            $oDaoPPaDotacao->o08_sequencial        = $oPPAano->o08_sequencial;
            $oDaoPPaDotacao->o08_concarpeculiar    = $oParam->o08_concarpeculiar;
            $oDaoPPaDotacao->alterar($oPPAano->o08_sequencial);

        } else {

            $oDaoPPaDotacao->o08_recurso           = $oParam->o08_recurso;
            $oDaoPPaDotacao->o08_localizadorgastos = $oParam->o08_localizadorgastos;
            $oDaoPPaDotacao->o08_sequencial        = $oPPAano->o08_sequencial;
            $oDaoPPaDotacao->o08_concarpeculiar    = $oParam->o08_concarpeculiar;
            $oDaoPPaDotacao->alterar($oPPAano->o08_sequencial);

            /**
             *
            * excluimos o vinculo da dotacao com a estimativa, caso houve modificacao no localizador de gastos da estimativa
            * do ppa.
            */
            $sSqlDadosEstimativa = $oDaoPPaDotacao->sql_query_file($oPPAano->o08_sequencial);
            $rsDadosEstimativa   = $oDaoPPaDotacao->sql_record($sSqlDadosEstimativa);
            if ($oDaoPPaDotacao->numrows == 1) {

                $oDadosEstimativa = db_utils::fieldsMemory($rsDadosEstimativa, 0);
                if ($oParam->o08_localizadorgastos != $oDadosEstimativa->o08_localizadorgastos) {

                    $oDaoPPaOrcDotacao= db_utils::getDao("ppadotacaoorcdotacao");
                    $oDaoPPaOrcDotacao->excluir(null, "o19_ppadotacao={$oPPAano->o08_sequencial}");
                    if ($oDaoPPaOrcDotacao->erro_status == 0) {

                        $oRetorno->status = 2;
                        $oRetorno->message = urlencode("N�o foi poss�vel alterar a dota��o.\n{$oDaoPPaOrcDotacao->erro_msg}");
                    }
                }
            }
        }

        if ($oDaoPPaDotacao->erro_status == 0) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode( "N�o foi poss�vel alterar a dota��o.\n{$oDaoPPaDotacao->erro_msg}");
        }
        /**
         * Alteramos  valor da estimativa
         */
        $oDaoPPaestimativa = db_utils::getDao("ppaestimativa");
        $oDaoPPaestimativa->o05_sequencial = $oPPAano->o05_sequencial;

        $nValorParam   = ppa::getAcrescimosEstimativa($oParam->o08_elemento, $oPPAano->o08_ano);

    	$nValor = $oParam->o05_valor;
    	if ($oPPAano->o08_ano > $iAnoInicio) {

            if ($nValorParam > 0) {
                $nValor *= $nValorParam;
            }
            $oParam->o05_valor = $nValor;

        }

        $nValorSalvar = round($oParam->o05_valor);
        if ($oParametroOrcamento->o50_liberadecimalppa == "t") {
            $nValorSalvar = round($oParam->o05_valor, 2);
        }
        $oDaoPPaestimativa->o05_valor      = "{$nValorSalvar}";
        $oDaoPPaestimativa->alterar($oPPAano->o05_sequencial);
        if ($oDaoPPaestimativa->erro_status == 0) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode("N�o foi poss�vel alterar a dota��o.\n{$oDaoPPaestimativa->erro_msg}");

        }
        if ($oRetorno->status == 2) {
            db_fim_transacao(true);
        } else {
            db_fim_transacao(false);
        }

    }

} else if ($oParam->exec == "excluirElemento") {

   db_inicio_transacao();
  /**
   * Verificamos se existe uma dotacao do ppa vinculada a
   * uma dotacao do or�amento
   */
   $lSqlErro         = false;
  $clppadotacao      = db_utils::getDao('ppadotacao');
  $oDaoPPaOrcDotacao = db_utils::getDao("ppadotacaoorcdotacao");
  $sSqlOrcDotacao    = $oDaoPPaOrcDotacao->sql_query_file(null,"*", null,"o19_ppadotacao= {$oParam->o08_sequencial}");
  $rsOrcDotacao      = $oDaoPPaOrcDotacao->sql_record($sSqlOrcDotacao);
  if ($oDaoPPaOrcDotacao->numrows > 0) {

    $oOrcDotacao = db_utils::fieldsMemory($rsOrcDotacao, 0);
    $oDaoPPaOrcDotacao->excluir($oOrcDotacao->o19_sequencial);
    if ($oDaoPPaOrcDotacao->erro_status == 0) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oDaoPPaOrcDotacao->erro_msg);
      $lSqlErro = true;

    }
  }
  if (!$lSqlErro) {

    /**
     * deletamos a estimativa da dotaca�ao
     */
    $oDaoPPaEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $oDaoPPaEstimativaDespesa->excluir(null, "o07_ppaestimativa = {$oParam->o05_sequencial}");
    if ($oDaoPPaEstimativaDespesa->erro_status == 0) {

      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode($oDaoPPaEstimativaDespesa->erro_msg);
      $lSqlErro = true;

    }
  }
  if (!$lSqlErro){

    $oDaoPPaEstimativa = db_utils::getDao("ppaestimativa");
    $oDaoPPaEstimativa->excluir($oParam->o05_sequencial);
    if ($oDaoPPaEstimativa->erro_status == 0) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oDaoPPaEstimativa->erro_msg);
      $lSqlErro = true;

    }
  }
  if (!$lSqlErro) {

    $clppadotacao->excluir($oParam->o08_sequencial);
    if ($clppadotacao->erro_status == 0) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($clppadotacao->erro_msg);
      $lSqlErro = true;

    }
  }
  db_fim_transacao($lSqlErro);

} else if ($oParam->exec == "alterarAcoesGrupo") {

  /**
   * Selecionamos todos as dota��es que pertencem ao grupo
   */
  $sCampos           = "o05_sequencial,";
  $sCampos          .= "o08_sequencial,";
  $sCampos          .= "o05_anoreferencia,";
  $sCampos          .= "o08_elemento,";
  $sCampos          .= "o56_descr,";
  $sCampos          .= "o08_concarpeculiar,";
  $sCampos          .= "o08_recurso,";
  $sCampos          .= "o15_descr,";
  $sCampos          .= "o08_localizadorgastos,";
  $sCampos          .= "o11_descricao,";
  $sCampos          .= "round(o05_valor,2) as o05_valor, ";
  $sCampos          .= "o19_sequencial,";
  $sCampos          .= "o19_coddot,";
  $sCampos          .= "o19_anousu";
  $oDaoPPaDotacao    = db_utils::getDao("ppaestimativadespesa");
  $sWhere       =  " o08_instit=".db_getsession("DB_instit");
  $sWhere      .=  " and o08_orgao          = {$oParam->o40_orgao}";
  $sWhere      .=  " and o08_unidade        = {$oParam->o41_unidade}";
  $sWhere      .=  " and o08_funcao         = {$oParam->o52_funcao}";
  $sWhere      .=  " and o08_subfuncao      = {$oParam->o53_subfuncao}";
  $sWhere      .=  " and o08_programa       = {$oParam->o54_programa}";
  $sWhere      .=  " and o08_projativ       = {$oParam->o55_projativ}";
  $sWhere      .=  " and o08_ppaversao      = {$oParam->o08_ppaversao}";

  $sSqlDotacaoItens  = $oDaoPPaDotacao->sql_query_conplano(
                                                             null,
                                                             $sCampos,
                                                             'o05_sequencial',
                                                             $sWhere
                                                           );
  $rsDotacaoItens  = $oDaoPPaDotacao->sql_record($sSqlDotacaoItens);
  $oRetorno->itens = db_utils::getColectionByRecord($rsDotacaoItens);

  /**
   * percorremos as dota��es, e verificamos se com a modifica��o feita pelo usu�rio j� existe uma
   * outra dotacao con as mesmas informa��es.
   * nesse caso, Devemso cancelar o procedimento e informar ao us�rio
   */

  db_inicio_transacao();
  $oDaoPPaDotacao = db_utils::getDao("ppadotacao");
  foreach ($oRetorno->itens  as $oDotacao) {

    $oDaoPPaDotacao->o08_sequencial       = $oDotacao->o08_sequencial;
    $oDaoPPaDotacao->o08_funcao           = $oParam->oAlterar->o08_funcao;
    $oDaoPPaDotacao->o08_subfuncao        = $oParam->oAlterar->o08_subfuncao;
    $oDaoPPaDotacao->o08_unidade          = $oParam->oAlterar->o08_unidade;
    $oDaoPPaDotacao->o08_orgao            = $oParam->oAlterar->o08_orgao;
    $oDaoPPaDotacao->o08_projativ         = $oParam->oAlterar->o08_projativ;
    $oDaoPPaDotacao->o08_programa         = $oParam->oAlterar->o08_programa;
    //$oDaoPPaDotacao->o08_concarpeculiar   = $oParam->oAlterar->o08_concarpeculiar;
    $oDaoPPaDotacao->alterar($oDotacao->o08_sequencial);

    if ($oDaoPPaDotacao->erro_status == 0) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oDaoPPaDotacao->erro_msg);
      break;
    }

    //Verifica se existe a nova dota��o
    $oDaoDotacao    = db_utils::getDao("orcdotacao");
    $sWhere         = " o58_orgao = {$oParam->oAlterar->o08_orgao} ";
    $sWhere        .= " and o58_unidade = {$oParam->oAlterar->o08_unidade} ";
    $sWhere        .= " and o58_funcao = {$oParam->oAlterar->o08_funcao} ";
    $sWhere        .= " and o58_subfuncao = {$oParam->oAlterar->o08_subfuncao} ";
    $sWhere        .= " and o58_programa = {$oParam->oAlterar->o08_programa} ";
    $sWhere        .= " and o58_projativ = {$oParam->oAlterar->o08_projativ} ";
    $sWhere        .= " and o58_codele = {$oDotacao->o08_elemento} ";
    if ($oDotacao->o19_anousu != '') {
        $sWhere    .= " and o58_anousu = {$oDotacao->o19_anousu} ";
    } else {
        $sWhere    .= " and o58_anousu = ";
        $sWhere    .= $oDotacao->o05_anoreferencia > db_getsession("DB_anousu") ? db_getsession("DB_anousu") : $oDotacao->o05_anoreferencia;
    }

    $sSqlDotacao    = $oDaoDotacao->sql_query_file(null, null, "o58_coddot", null, $sWhere);
    $rsDotacao      = $oDaoDotacao->sql_record($sSqlDotacao);

    if ($oDaoDotacao->numrows > 0) {

        $oOrcDotacao = db_utils::fieldsMemory($rsDotacao, 0);

        /**
         * Caso n�o exista vinculo na ppadotacaoorcdotacao, cria
         */
        if ($oDotacao->o19_coddot == '') {

            $oDaoPPaOrcDotacao = db_utils::getDao("ppadotacaoorcdotacao");
            $oDaoPPaOrcDotacao->o19_ppadotacao = $oDotacao->o08_sequencial;
            $oDaoPPaOrcDotacao->o19_coddot = $oOrcDotacao->o58_coddot;
            $oDaoPPaOrcDotacao->o19_anousu = $oDotacao->o05_anoreferencia < db_getsession("DB_anousu") ? $oDotacao->o05_anoreferencia : db_getsession("DB_anousu");
            $oDaoPPaOrcDotacao->incluir();

            if ($oDaoPPaOrcDotacao->erro_status == 0) {
                $oRetorno->status = 2;echo $oDaoPPaOrcDotacao->erro_msg;die;
                $oRetorno->message = urlencode("N�o foi poss�vel alterar a dota��o.\n{$oDaoPPaOrcDotacao->erro_msg}");
                break;
            }

        } elseif ($oOrcDotacao->o58_coddot != $oDotacao->o19_coddot) {

            /**
            * Caso exista e o cod dota��o seja diferente do existente na ppadotacaoorcdotacao,
            * atualiza a ppadotacaoorcdotacao
            */

            $oDaoPPaOrcDotacao = db_utils::getDao("ppadotacaoorcdotacao");
            $oDaoPPaOrcDotacao->o19_sequencial = $oDotacao->o19_sequencial;
            $oDaoPPaOrcDotacao->o19_coddot = $oOrcDotacao->o58_coddot;
            $oDaoPPaOrcDotacao->alterar($oDotacao->o19_sequencial);
            if ($oDaoPPaOrcDotacao->erro_status == 0) {
                $oRetorno->status = 2;
                $oRetorno->message = urlencode("N�o foi poss�vel alterar a dota��o.\n{$oDaoPPaOrcDotacao->erro_msg}");
                break;
            }

        }

    } else {

        //Exclui o registro da ppadotacaoorcdotacao
        if ($oDotacao->o19_sequencial != '') {
            $oDaoPPaOrcDotacao = db_utils::getDao("ppadotacaoorcdotacao");
            $oDaoPPaOrcDotacao->excluir(null, "o19_sequencial={$oDotacao->o19_sequencial}");
            if ($oDaoPPaOrcDotacao->erro_status == 0) {

                $oRetorno->status = 2;
                $oRetorno->message = urlencode("N�o foi poss�vel alterar a dota��o.\n{$oDaoPPaOrcDotacao->erro_msg}");
            }
        }
    }

  }

  if ($oRetorno->status == 1) {
    db_fim_transacao(false);
  } else {
    db_fim_transacao(true);
  }
} else if ($oParam->exec == "incluirAcao") {


  	require("model/ppadespesa.model.php");
    $oPPADespesa = new ppaDespesa($oParam->o08_ppaversao);
    try {

        $oDaoppaVersao = db_utils::getDao("ppaversao");
        $sSqlVersao    = $oDaoppaVersao->sql_query($oParam->o08_ppaversao);
        $rsPPaVersao   = $oDaoppaVersao->sql_record($sSqlVersao);
        $oVersao       = db_utils::fieldsMemory($rsPPaVersao,0);

        db_inicio_transacao();
        for ($iAno = $oParam->oDotacao->iAno; $iAno <= $oVersao->o01_anofinal; $iAno++) {

            $nValorParam   = ppa::getAcrescimosEstimativa($oParam->oDotacao->o08_elemento, $iAno);
            $nValor        = $oParam->oDotacao->nValor;
    	    if ($iAno >= $oVersao->o01_anoinicio) {

                if ($nValorParam > 0 && $iAno > $oVersao->o01_anoinicio) {
                    $nValor *= $nValorParam;
                }

                $oParam->oDotacao->nValor = $nValor;
                $oParam->oDotacao->iAno   = $iAno;
                $oPPADespesa->adicionarEstimativa($oParam->oDotacao);

            }
        }
        db_fim_transacao(false);

    } catch (Exception $eErroDotacao) {

        $oRetorno->status = 2;
        $oRetorno->message = urlencode($eErroDotacao->getMessage());
        db_fim_transacao(true);

    }
}
echo $oJson->encode($oRetorno);
?>
