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
require_once("libs/JSON.php");
require_once(Modification::getFile('model/agendaPagamento.model.php'));
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("model/CgmFactory.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmJuridico.model.php");
require_once("model/CgmFisico.model.php");
require_once("model/Dotacao.model.php");
require_once('model/empenho/EmpenhoFinanceiro.model.php');
require_once('model/empenho/EmpenhoFinanceiroItem.model.php');
require_once('model/MaterialCompras.model.php');
require_once("classes/ordemPagamento.model.php");
require_once("libs/db_app.utils.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("std/DBDate.php");
db_app::import("exceptions.*");
db_app::import("configuracao.*");
db_app::import("caixa.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("financeiro.*");
db_app::import("Dotacao");
db_app::import("contabilidade.contacorrente.*");
db_app::import("orcamento.*");

include("classes/db_slip_classe.php");

$oGet     = db_utils::postMemory($_GET);
$oJson    = new services_json();
$sStringToParse = str_replace("<aspa>",'\"',str_replace("\\","",$_POST["json"]));
$oParam   = $oJson->decode($sStringToParse);

switch ($oParam->exec) {

  case "getNotas" :

  $oAgenda = new agendaPagamento();
  $oAgenda->setUrlEncode(true);
  $sWhere  = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
  $sWhere .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";
  $sWhere .= " and e80_data  <= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
  $sWhere .= " and e97_codforma = {$oParam->params[0]->iForma} and e81_cancelado is null";

  if ($oParam->params[0]->iForma == 2) {

    $sWhere .= " and e91_codcheque is not null";
    if ($oParam->params[0]->dtChequeIni != "" && $oParam->params[0]-> dtChequeFim== "") {
      $sWhere .= " and e86_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtChequeIni)))."'";
    } else if ($oParam->params[0]->dtChequeIni != "" && $oParam->params[0]->dtChequeFim != "") {

      $dtChequeIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtChequeIni)));
      $dtChequeFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtChequeFim)));
      $sWhere .= " and e86_data between '{$dtChequeIni}' and '{$dtChequeFim}'";

    } else if ($oParam->params[0]->dtChequeIni == "" && $oParam->params[0]->dtChequeFim != "") {

      $dtChequeFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtChequeFim)));
      $sWhere    .= " and e86_data <= '{$dtChequeFim}'";
    }

    if ($oParam->params[0]->sNumeroCheque != "") {
      $sWhere    .= " and e91_cheque = '{$oParam->params[0]->sNumeroCheque}'";
    }

  }

  $sWhere .= " and k12_data is null";
  $sWhere .= " and e60_instit = ".db_getsession("DB_instit");
  if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
    $sWhere .= " and e50_codord = {$oParam->params[0]->iOrdemIni}";
  } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {
    $sWhere .= " and e50_codord between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";
  }

  if (isset($oParam->params[0]->iOrdemBanc) && $oParam->params[0]->iOrdemBanc != '') {
    $sSqlOrdem = "select k00_codord from ordembancariapagamento where k00_codordembancaria = {$oParam->params[0]->iOrdemBanc}";
    $rsResultOrdem = db_query($sSqlOrdem);
    $aOrdem = array();
    for ($iCont = 0; $iCont < pg_num_rows($rsResultOrdem); $iCont++) {
      $aOrdem[] = db_utils::fieldsMemory($rsResultOrdem, $iCont)->k00_codord;
    }
    $sWhere .= " and e50_codord in (".implode(",", $aOrdem).") ";
  }

  if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {
    $sWhere .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
  } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {

    $dtDataIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
    $dtDataFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
    $sWhere .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";

  } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {

    $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
    $sWhere    .= " and e50_data <= '{$dtDataFim}'";
  }

    //Filtro para Empenho
  if ($oParam->params[0]->iCodEmp != '' and $oParam->params[0]->iCodEmp2 == "") {

    if (strpos($oParam->params[0]->iCodEmp,"/")) {

      $aEmpenho = explode("/",$oParam->params[0]->iCodEmp);
      $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";

    } else {
      $sWhere .= " and e60_codemp = '{$oParam->params[0]->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
    }

  } else if ($oParam->params[0]->iCodEmp != "" and $oParam->params[0]->iCodEmp2 != "") {

    $sWhere .= "  and (";
    if (strpos($oParam->params[0]->iCodEmp,"/")) {

      $aEmpenho = explode("/",$oParam->params[0]->iCodEmp);
      $sWhere .= " (cast(e60_codemp as integer) >= {$aEmpenho[0]} and e60_anousu={$aEmpenho[1]} )";

    } else {
      $sWhere .= " (cast(e60_codemp as integer) >= {$oParam->params[0]->iCodEmp} and e60_anousu=".db_getsession("DB_anousu").")";
    }
    if (strpos($oParam->params[0]->iCodEmp2,"/")) {

      $aEmpenho = explode("/",$oParam->params[0]->iCodEmp2);
      $sWhere .= " or  (cast(e60_codemp as integer) <= {$aEmpenho[0]} and e60_anousu={$aEmpenho[1]} )";

    } else {
      $sWhere .= "  or (cast(e60_codemp as integer) <= {$oParam->params[0]->iCodEmp2} and e60_anousu=".db_getsession("DB_anousu").")";
    }
    $sWhere .= ") ";

  }
    //echo "<br>".$sWhere;
    //filtro para filtrar por credor
  if ($oParam->params[0]->iNumCgm != '') {
    $sWhere .= " and (e60_numcgm = {$oParam->params[0]->iNumCgm})";
  }

    /*
     * Conta pagadora
     */
    if ($oParam->params[0]->iCtaPagadora != '0') {
      $sWhere .= " and e83_codtipo = {$oParam->params[0]->iCtaPagadora}";
    }
    if ($oParam->params[0]->iRecurso != '') {
      $sWhere .= " and o15_codigo = {$oParam->params[0]->iRecurso}";
    }

    if ($oParam->params[0]->sDtAut != '') {

      $oParam->params[0]->sDtAut = implode("-", array_reverse(explode("/", $oParam->params[0]->sDtAut)));
      $sWhere .= " and e42_dtpagamento = '{$oParam->params[0]->sDtAut}'";

    }

    if ($oParam->params[0]->iOPauxiliar != '') {
      $sWhere .= " and e42_sequencial = {$oParam->params[0]->iOPauxiliar}";
    }
    $sJoin  = " left join empagenotasordem on e81_codmov  = e43_empagemov     ";
    $sJoin .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
    $aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere, $sJoin, false, false, ",e83_conta, e83_descr,e91_codcheque, e91_valor");


    $oRetorno->status           = 2;
    $oRetorno->mensagem         = "";
    if (count($aOrdensAgenda) > 0) {

      $oRetorno->status           = 1;
      $oRetorno->mensagem         = 1;
      $oRetorno->aNotasLiquidacao = $aOrdensAgenda;
      echo $oJson->encode($oRetorno);

    } else {

      $oRetorno->status           = 2;
      $oRetorno->mensagem         = "";
      echo $oJson->encode($oRetorno);

    }
    break;

    case "getSlipSemCheque":
        $clslip = new cl_slip;

        // Montando Condi��es da Busca
        $dbwhere = '(e97_codforma = 1 or  e97_codforma = 4) and k17_instit = ' . db_getsession("DB_instit");
        $dbwhere .= ' and k17_dtaut is null and e81_cancelado IS NULL and c60_anousu = ' . db_getsession("DB_anousu");
        $dbwhere .= ' and k17_situacao = 1 ';

        if (isset($oParam->params[0]->iOrdemIni) && $oParam->params[0]->iOrdemIni != '' && isset($oParam->params[0]->iOrdemFim) && $oParam->params[0]->iOrdemFim != '') {
            $dbwhere .= " and s.k17_codigo >= " . $oParam->params[0]->iOrdemIni . " and s.k17_codigo <= " . $oParam->params[0]->iOrdemFim;
        } else if ((empty($oParam->params[0]->iOrdemIni) || (isset($oParam->params[0]->iOrdemIni) && $oParam->params[0]->iOrdemIni == ''))  && isset($oParam->params[0]->iOrdemFim) && $oParam->params[0]->iOrdemFim != '') {
            $dbwhere .= " and s.k17_codigo <= " . $oParam->params[0]->iOrdemFim;
        } else if (isset($oParam->params[0]->iOrdemIni) && $oParam->params[0]->iOrdemIni != '') {
            $dbwhere .= " and s.k17_codigo = " . $oParam->params[0]->iOrdemIni;
        }

        if (isset($oParam->params[0]->iOrdemBanc) && $oParam->params[0]->iOrdemBanc != '') {
            $sSqlOrdemSlip = "select k00_slip from ordembancariapagamento where k00_codordembancaria = {$oParam->params[0]->iOrdemBanc}";
            $rsResultOrdemSlip = db_query($sSqlOrdemSlip);
            $aOrdemSlip = array();
            for ($iCont = 0; $iCont < pg_num_rows($rsResultOrdemSlip); $iCont++) {
                $aOrdemSlip[] = db_utils::fieldsMemory($rsResultOrdemSlip, $iCont)->k00_slip;
            }
            $dbwhere .= " and s.k17_codigo in (" . implode(",", $aOrdemSlip) . ") ";
        }

        if (isset($oParam->params[0]->z01_numcgm) && $oParam->params[0]->z01_numcgm != '') {
            $dbwhere .= " and z01_numcgm = " . $oParam->params[0]->z01_numcgm;
        }

        if (isset($oParam->params[0]->dtDataIni) && !isset($oParam->params[0]->dtDataFim)) {
            $oParam->params[0]->dtDataIni = implode("-", array_reverse(explode("/", $oParam->params[0]->dtDataIni)));
            if ($oParam->params[0]->dtDataIni != '') {
                $dbwhere .= " and k17_data = '{$oParam->params[0]->dtDataIni}'";
            }
        } else if (isset($oParam->params[0]->dtDataIni) && isset($oParam->params[0]->dtDataFim)) {
            $oParam->params[0]->dtDataFim = implode("-", array_reverse(explode("/", $oParam->params[0]->dtDataFim)));
            $oParam->params[0]->dtDataIni = implode("-", array_reverse(explode("/", $oParam->params[0]->dtDataIni)));
            if ($oParam->params[0]->dtDataFim != '' && $oParam->params[0]->dtDataIni != '') {
                $dbwhere .= " and k17_data between '{$oParam->params[0]->dtDataFim}'";
                $dbwhere .= " and '{$oParam->params[0]->dtDataFim}'";
            }
        }

        $sql_disabled = $clslip->sql_query_tipo(null, "s.k17_codigo, k17_valor", "", $dbwhere . " and k17_autent > 0");
        $sql = $clslip->sql_query_tipo(null, "s.k17_codigo, k17_situacao, k17_valor, k17_credito, k17_debito, k17_data, c50_descr, c60_descr, z01_nome, k17_instit,e81_numdoc, e81_codmov, e97_codforma", 's.k17_codigo', $dbwhere);
        
        $query = pg_query($sql);
        while ($oOrdem = pg_fetch_object($query)) {
            $oOrdem->e81_numdoc = $oOrdem->e81_numdoc ? $oOrdem->e81_numdoc : '';
            $aOrdensAgenda[] = $oOrdem;
        }

        // Condi��o do Slip Sem Cheque
        $oRetorno->status = 2;
        $oRetorno->mensagem = "";

        if (count($aOrdensAgenda) > 0) {
            $oRetorno->status = 1;
            $oRetorno->mensagem = 1;
            $oRetorno->aNotasLiquidacao = $aOrdensAgenda;
            echo $oJson->encode($oRetorno);
        } else {
            $oRetorno->status = 2;
            $oRetorno->mensagem = $oParam->params[0];
            echo $oJson->encode($oRetorno);
        }

        break;

    case "pagarSlip" :
        // Condi��o para slip sem cheque, retirada do arquivo emp4_empagepagamento001.php
        $oRetorno = new stdClass();
        $oRetorno->status = 1;
        $oRetorno->message = "";
        if (is_array($oParam->aMovimentos)) {
            try {
                db_inicio_transacao();
                foreach ($oParam->aMovimentos as $oMovimento) {
                    $codigo = $oMovimento->iCodSlip;
                    $numslip = $oMovimento->iCodSlip;
                    $e91_codcheque = '0';
                    $codigomovimento = '0';

                    if (!empty($oParam->dtPagamento) && $oParam->dtPagamento != '//') {
                        $dtAuxData = new DBDate($oParam->dtPagamento);
                        $dtAuxData = $dtAuxData->getDate();
                        $data = $dtAuxData; // aqui ele atribui a data_para_pagamento enviada pelo usu�rio
                        unset($dtAuxData);
                    } else {
                        $data = date("Y-m-d", db_getsession("DB_datausu"));
                    }

                    $ip = db_getsession("DB_ip");
                    $instit = db_getsession("DB_instit");

                    $sql = "select fc_auttransf($codigo, '" . $data . "', '" . $ip . "', true, $e91_codcheque, " . $instit . ") as verautenticacao";
                    $result03 = db_query($sql);
                    
                    if (pg_numrows($result03) == 0) {
                        $oRetorno->status = 1;
                        $oRetorno->mensagem = "Erro ao Autenticar SLIP $numslip!";
                        break;
                    } else {
                      
                        db_fieldsmemory($result03, 0);
                        if (substr($verautenticacao, 0, 1) != "1") {
                            throw new Exception($verautenticacao);
                            break;
                        }
                        

                        if (USE_PCASP) {
                            try {
                              
                                $oDaocfautent = db_utils::getDao('cfautent');
                                $oDaoSlipTipoOperacao  = db_utils::getDao('sliptipooperacaovinculo');
                                $sSqlBuscaTipoOperacao = $oDaoSlipTipoOperacao->sql_query_file($codigo);
                                $rsBuscaTipoOperacao   = $oDaoSlipTipoOperacao->sql_record($sSqlBuscaTipoOperacao);
                                if ($oDaoSlipTipoOperacao->numrows == 0) {
                                    throw new Exception("N�o foi poss�vel localizar o tipo de opera��o do slip {$codigo}.");
                                }
                                $sSqlAutenticadora = $oDaocfautent->sql_query_file(null, "k11_id, k11_tipautent", '', "k11_ipterm = '{$ip}' and k11_instit = " . db_getsession("DB_instit"));
                                $rsAutenticador = $oDaocfautent->sql_record($sSqlAutenticadora);

                                if ($oDaocfautent->numrows == '0') {
                                    throw new Exception("Cadastre o ip {$iIp} como um caixa.");
                                }

                                $iCodigoTerminal = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;
                                $iTipoOperacao = db_utils::fieldsMemory($rsBuscaTipoOperacao, 0)->k153_slipoperacaotipo;
                                $oTransferencia = TransferenciaFactory::getInstance($iTipoOperacao, $codigo);
                                $oTransferencia->setDataAutenticacao($data);
                                $oTransferencia->setIDTerminal($iCodigoTerminal);
                                $oTransferencia->setNumeroAutenticacao(substr($verautenticacao, 1, 7));
                                $oTransferencia->executarLancamentoContabil(null, false, $e91_codcheque);
                                
                                $oDaoEmpageMov = db_utils::getDao("empagemov");
                                $oDaoEmpageMov->e81_numdoc = (isset($oMovimento->sNumDoc) && $oMovimento->sNumDoc != '') ? $oMovimento->sNumDoc : '';
                                $oDaoEmpageMov->e81_codmov = $oMovimento->iCodMov;
                                $oDaoEmpageMov->alterar($oMovimento->iCodMov);

                                if ($oDaoEmpageMov->erro_status == 0) {
                                    $sErroMsg = "Erro [8] - N�o foi possivel incluir o n�mero do documento ao movimento.";
                                    throw new Exception($sErroMsg);
                                }

                                $oRetorno->message = urlencode("Pagamento(s) Efetuado(s) com sucesso!");
                            } catch (Exception $eErro) {
                                db_fim_transacao(true);
                                $oRetorno->status = 2;
                                $oRetorno->message = urlencode($eErro->getMessage());
                            }
                        }
                    }
                } 
            } catch (Exception $eErro) {
                db_fim_transacao(true);
                $oRetorno->status = 2;
                $oRetorno->message = urlencode($eErro->getMessage());
            }
        }
        db_fim_transacao(false);
        echo $oJson->encode($oRetorno);
        // Final da slip sem cheque
        break;

    case "pagarMovimento" :

    $oRetorno = new stdClass();
    $oRetorno->status       = 1;
    $oRetorno->message      = "";
    $oRetorno->iItipoAutent = null;
    if (is_array($oParam->aMovimentos)) {

      try {
        $oRetorno->aAutenticacoes = array();
        $oRetorno->iItipoAutent   = 0;
        $oRetorno->aAutenticacoes = array();
        db_inicio_transacao();
        foreach ($oParam->aMovimentos as $oMovimento) {

          if (!empty($oParam->dtPagamento) && $oParam->dtPagamento != '//') {
            $dtAuxData = new DBDate($oParam->dtPagamento);
            $dtAuxData = $dtAuxData->getDate();
            $data = $dtAuxData; // aqui ele atribui a data_para_pagamento enviada pelo usu�rio
            unset($dtAuxData);
          } else {
              $data = date("Y-m-d", db_getsession("DB_datausu"));
          }

          $oOrdemPagamento = new ordemPagamento($oMovimento->iNotaLiq, $data);

          $oOrdemPagamento->setCheque($oMovimento->iCheque);
          $oOrdemPagamento->setChequeAgenda($oMovimento->iCodCheque);
          $oOrdemPagamento->setConta($oMovimento->iConta);
          $oOrdemPagamento->setValorPago($oMovimento->nValorPagar);
          $oOrdemPagamento->setMovimentoAgenda($oMovimento->iCodMov);
          $oOrdemPagamento->setHistorico($oMovimento->sHistorico);
          $oOrdemPagamento->pagarOrdem();
          $oRetorno->iItipoAutent     = $oOrdemPagamento->oAutentica->k11_tipautent;
          $c70_codlan                 = $oOrdemPagamento->iCodLanc;
          $oAutentica                 = new stdClass();
          $oAutentica->iNota          = $oMovimento->iNotaLiq;
          $oAutentica->sAutentica     = $oOrdemPagamento->getRetornoautenticacao();
          $oRetorno->aAutenticacoes[] = $oAutentica;

          $oDaoEmpageMov = db_utils::getDao("empagemov");
          $oDaoEmpageMov->e81_numdoc = (isset($oMovimento->sNumDoc) && $oMovimento->sNumDoc != '') ? $oMovimento->sNumDoc : '';
          $oDaoEmpageMov->e81_codmov = $oMovimento->iCodMov;
          $oDaoEmpageMov->alterar($oMovimento->iCodMov);
  
          if ($oDaoEmpageMov->erro_status == 0) {
              $sErroMsg = "Erro [8] - N�o foi possivel incluir o n�mero do documento ao movimento.";
              throw new Exception($sErroMsg);
          }
        }

        db_fim_transacao(false);


        $oRetorno->message = urlencode("Pagamento(s) Efetuado(s) com sucesso!");
      }
      catch (Exception $eErro) {

        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }

    echo $oJson->encode($oRetorno);
    break;

    case "estornarPagamento":

    $oRetorno = new stdClass();
    $oRetorno->status         = 1;
    $oRetorno->message        = "";
    $oRetorno->iTipoAutentica = 0;
    $oRetorno->sAutenticacao  = null;
    $oRetorno->sCodLanc       = null;
    try {


      db_inicio_transacao();
      $dtDataLancamento = new DBDate($oParam->dataLancamento);
      $iAnoLancamento   = $dtDataLancamento->getAno();
      $sDataLancamento  = $dtDataLancamento->getDate();
      $oOrdemPagamento = new ordemPagamento($oParam->iNota, $sDataLancamento);
      $oOrdemPagamento->setCheque($oParam->iCheque);
      $oOrdemPagamento->setChequeAgenda($oParam->iCodCheque);
      $oOrdemPagamento->setConta($oParam->iConta);
      $oOrdemPagamento->setValorPago($oParam->nValorEstornar);
      $oOrdemPagamento->setMovimentoAgenda($oParam->iCodMov);
      $oOrdemPagamento->setHistorico($oParam->sHistorico);
      $nEmpenho = $oOrdemPagamento->getDadosOrdem()->e50_numemp;

      $sSqlConsultaFimPeriodoContabil   = "SELECT * FROM condataconf WHERE c99_anousu = ".db_getsession('DB_anousu')." and c99_instit = ".db_getsession('DB_instit');
      $rsConsultaFimPeriodoContabil     = db_query($sSqlConsultaFimPeriodoContabil);

      if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {

        $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);

        if ($oFimPeriodoContabil->c99_data != '' && $dtDataLancamento->getTimeStamp() < db_strtotime($oFimPeriodoContabil->c99_data)) {
            throw new Exception("Data informada inferior � data do fim do per�odo cont�bil.");
        }

      }

      if ($iAnoLancamento != $oOrdemPagamento->getDataPagamento()->c70_anousu) {
        throw new Exception("N�o � poss�vel estornar pagamentos de anos anteriores.");
      }

      if ($dtDataLancamento->getTimeStamp() < db_strtotime($oOrdemPagamento->getDataPagamento()->c70_data)) {
        throw new Exception("N�o � poss�vel estornar com data anterior ao pagamento.");
      }

      if ($oParam->lEstornarPgto) {

        $iMovimento              = $oOrdemPagamento->getMovimentoAgenda();
        $iCaracteristicaPeculiar = null;

        if (!empty($iMovimento)) {

          $oDaoEmpAgeConcarPeculiar = db_utils::getDao("empageconcarpeculiar");
          $sSqlConCarPeculiar = $oDaoEmpAgeConcarPeculiar->sql_query_file(null, "e79_concarpeculiar", null, "e79_empagemov = {$iMovimento}");
          $rsConcarPeculiar = $oDaoEmpAgeConcarPeculiar->sql_record($sSqlConCarPeculiar);
          if ($oDaoEmpAgeConcarPeculiar->numrows > 0) {

            $iCaracteristicaPeculiar = db_utils::fieldsMemory($rsConcarPeculiar, 0)->e79_concarpeculiar;
          }
        }

        $oOrdemPagamento->estornarOrdem($iCaracteristicaPeculiar, $sDataLancamento);

        $oRetorno->iTipoAutentica = $oOrdemPagamento->getTipoAutenticacao();
        $oRetorno->sAutenticacao  = $oOrdemPagamento->getRetornoautenticacao();
        $oRetorno->sCodLanc       = $oOrdemPagamento->iCodLanc;

      }
      if (count($oParam->aRetencoes) > 0) {

        $oOrdemPagamento->setRetencoes($oParam->aRetencoes);
        $oOrdemPagamento->estornarRetencoes($oParam->dataLancamento ?? null);
        if ($oRetorno->iTipoAutentica == 0) {

          $oRetorno->iTipoAutentica = $oOrdemPagamento->getTipoAutenticacao();
          $oRetorno->sAutenticacao  = urlEncode($oOrdemPagamento->getRetornoautenticacao());
        }
        if ($oRetorno->sCodLanc != "") {
          $oRetorno->sCodLanc       += ",{$oOrdemPagamento->iCodLanc}";
        } else {
          $oRetorno->sCodLanc       = "{$oOrdemPagamento->iCodLanc}";
        }
      }

      /*
       * Verificamos se o usuario solicitou a anula��o do cheque
       */
      if ($oParam->iCodCheque != ""  && $oParam->lEstornaCheque) {

        $oAgendaPagamento  = new agendaPagamento();
        $oAgendaPagamento->cancelarCheque($oParam->iCodMov);

      }

      /**
       * Verifica se � uma devolu��o e pega os itens da nota vinculada a ordem de pagamento e aplica um desconto
       */
      if ($oParam->lDevolucao) {
        $rsExecutaQuery = db_query(  " select e69_codnota, empnotaitem.* from empnota              "
          . "        inner join empnotaitem on e72_codnota = e69_codnota  "
          . "        inner join pagordemnota on e71_codnota = e69_codnota "
          . "  where e71_codord = {$oParam->iNota}                        " );

        $iCodigoNota = db_utils::fieldsMemory($rsExecutaQuery, 0)->e69_codnota;

        $oNota = new stdClass;
        $oNota->e69_codnota = $iCodigoNota;
        $oNota->aItens = array();

        $nDescontoSaldo = $oParam->nValorEstornar;

        for ($i =0 ; $i< pg_num_rows($rsExecutaQuery); $i++) {

          $oStdDadosNota = db_utils::fieldsMemory($rsExecutaQuery, $i);

          if ($nDescontoSaldo != 0) {

            if ($nDescontoSaldo >= $oStdDadosNota->e72_vlrliq) {

              $oStdDadosNota->nTotalDesconto = $oStdDadosNota->e72_vlrliq;
              $nDescontoSaldo               -= $oStdDadosNota->e72_vlrliq;
            } else {
              $oStdDadosNota->nTotalDesconto = $nDescontoSaldo;
              $nDescontoSaldo                = 0;
            }

            $oNota->aItens[] = $oStdDadosNota;
          }
        }

        $oOrdemPagamento->desconto($oNota, $oParam->nValorEstornar, $oParam->sHistorico);
      }


      //$oOrdemPagamento->estornarOrdem();
      //$oRetorno->aAutenticacoes[] = $oAutentica;
      db_fim_transacao(false);

    }
    catch (Exception $eErro) {

      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
      db_fim_transacao(true);

    }
    echo $oJson->encode($oRetorno);
    break;

    case "Autenticar" :

    $oRetorno = new stdClass();
    $oRetorno->status         = 1;
    $oRetorno->message        = "";

    require_once 'model/impressaoAutenticacao.php';
    try {
      $oImpressao = new impressaoAutenticacao($oParam->sString);
      $oModelo = $oImpressao->getModelo();
      $oModelo->imprimir();
    }catch (Exception $eErroAutenticacao){
      $oRetorno->status  = 2;
      $oRetorno->message = urlEncode($eErroAutenticacao->getMessage());
    }

    $oRetorno->sAutenticacao = urlEncode($oParam->sString);
    echo $oJson->encode($oRetorno);

    /*
    $fd = @fsockopen(db_getsession('DB_ip'),4444);
    if ($fd) {
     fputs($fd, chr(15)."{$oParam->sString}".chr(18).chr(10).chr(13));
     $oRetorno->sAutenticacao = urlEncode($oParam->sString);
   } else {
      $oRetorno->status         = 2;
      $oRetorno->message        = urlencode("N�o foi poss�vel encontrar Impressora");
      $oRetorno->sAutenticacao = urlEncode($oParam->sString);
   }
   echo $oJson->encode($oRetorno);
   if ($fd) {
    fclose($fd);
  } */

  break;
}
