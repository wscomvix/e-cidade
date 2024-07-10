<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("model/Acordo.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/CgmFisico.model.php");
require_once("model/CgmJuridico.model.php");
require_once("model/AcordoHomologacao.model.php");
require_once("model/AcordoComissao.model.php");
require_once("model/AcordoComissaoMembro.model.php");
require_once("model/AcordoAssinatura.model.php");
require_once("model/AcordoRescisao.model.php");
require_once("model/AcordoAnulacao.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/AcordoItem.model.php");
require_once("model/contrato/AcordoLancamentoContabil.model.php");
require_once("model/licitacao.model.php");
require_once("model/Dotacao.model.php");
require_once("model/MaterialCompras.model.php");
require_once("std/DBDate.php");
require_once("model/AcordoMovimentacao.model.php");

$oJson    = new services_json();
$oRetorno = new stdClass();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));

$oRetorno->status  = 1;

if (isset($oParam->observacao)) {
    $sObservacao = utf8_decode($oParam->observacao);
}

switch ($oParam->exec) {

        /*
   * Pesquisa homologa��o para o contrato
   */
    case "getDadosHomologacao":

        try {

            $oHomologacao        = new AcordoHomologacao($oParam->codigo);
            $oAcordo             = new Acordo($oHomologacao->getAcordo());
            $oRetorno->codigo    = $oHomologacao->getCodigo();
            $oRetorno->acordo    = $oAcordo->getCodigoAcordo();
            $oRetorno->descricao = urlencode($oAcordo->getResumoObjeto());
        } catch (Exception $eExeption) {

            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Incluir homologa��o para o contrato
   */
    case "homologarContrato":

        try {

            db_inicio_transacao();
            $dataReferencia = db_query("select ac16_datareferencia from acordo where ac16_sequencial = $oParam->acordo");
            $dataReferencia = pg_result($dataReferencia, 0, 'ac16_datareferencia');
            $datoDataLancamentoLancamento = $dataReferencia;
            $oHomologacao = new AcordoHomologacao();
            $oHomologacao->setAcordo($oParam->acordo);
            $oHomologacao->setObservacao($sObservacao);
            $oHomologacao->save();
            $oAcordo                   = new Acordo($oParam->acordo);
            $oAcordoLancamentoContabil = new AcordoLancamentoContabil();
            $sHistorico = "Valor referente a homologa��o do contrato de c�digo: {$oParam->acordo}.";
            $oAcordoLancamentoContabil->registraControleContrato($oParam->acordo, $oAcordo->getValorContrato(), $sHistorico, $datoDataLancamentoLancamento);

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Cancelar homologa��o para o contrato
   */
    case "cancelarHomologacao":
        
        try {

            db_inicio_transacao();

            $oHomologacao = new AcordoHomologacao($oParam->codigo);
            $oHomologacao->setObservacao($sObservacao);
            $oHomologacao->cancelar();
            $oAcordo                   = new Acordo($oHomologacao->getAcordo());
            $oAcordoLancamentoContabil = new AcordoLancamentoContabil();
            $sHistorico = "Valor referente a cancelamento da homologa��o do contrato de c�digo: {$oAcordo->getCodigoAcordo()}.";
            $oAcordoLancamentoContabil->anulaRegistroControleContrato($oAcordo->getCodigoAcordo(), $oAcordo->getValorContrato(), $sHistorico, $oHomologacao->getDataMovimento());

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;
    case "getleilicitacao":
            $sSQL = "select l20_leidalicitacao  from liclicita
            inner join acordo on
                acordo.ac16_licitacao = liclicita.l20_codigo
            where
            acordo.ac16_origem = 2
            and acordo.ac16_sequencial = $oParam->licitacao";


            $rsResult       = db_query($sSQL);
            $leilicitacao = db_utils::fieldsMemory($rsResult, 0);

            $oRetorno->lei = $leilicitacao->l20_leidalicitacao;

            break;

        /*
   * Pesquisa dados da assinatura
   */
    case "getDadosAssinatura":

        try {

            $oAssinatura             = new AcordoAssinatura($oParam->codigo);
            $oAcordo                 = new Acordo($oAssinatura->getAcordo());
            $oRetorno->codigo        = $oAssinatura->getCodigo();
            $oRetorno->acordo        = $oAcordo->getCodigoAcordo();
            $oRetorno->datamovimento = date("Y-m-d", db_getsession("DB_datausu"));
            $oRetorno->descricao     = urlencode($oAcordo->getResumoObjeto());
            $dataReferencia = db_query("select ac16_datareferencia from acordo where ac16_sequencial = $oRetorno->acordo");
            $dataReferencia = pg_result($dataReferencia, 0, 'ac16_datareferencia');
            $oRetorno->datareferencia = $dataReferencia;
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Incluir assinatura para o contrato
   */
    case "assinarContrato":

        try {

            $clAcordoItem = new cl_acordoitem;
            $sSqlSomaItens = $clAcordoItem->sql_query('', 'sum(ac20_valortotal) as soma', '', 'ac16_sequencial = ' . $oParam->acordo);
            $rsSomaItens = $clAcordoItem->sql_record($sSqlSomaItens);
            $iSoma = db_utils::fieldsMemory($rsSomaItens, 0)->soma;

            $clAcordo = new cl_acordo;
            $clAcordo->ac16_valor = $iSoma;
            $clAcordo->alterar($oParam->acordo);

            $oDataMovimentacao = new DBDate($oParam->dtmovimentacao);
            $oDataPublicacao = new DBDate($oParam->dtpublicacao);
            $oDataReferencia = null;

            db_inicio_transacao();
            $oAssinatura = new AcordoAssinatura();
            $oAssinatura->setAcordo($oParam->acordo);
            $oAssinatura->setDataMovimento($oDataMovimentacao->getDate());
            $oAssinatura->setDataPublicacao($oDataPublicacao->getDate());
            $oAssinatura->setVeiculoDivulgacao($oParam->veiculodivulgacao);
            $oAssinatura->setObservacao($sObservacao);
            if ($oParam->dtreferencia != null) {
                $oDataReferencia = new DBDate($oParam->dtreferencia);
                $oAssinatura->setDataReferencia($oDataReferencia->getDate());
            } else {
                $oAssinatura->setDataReferencia($oDataMovimentacao->getDate());
            }
            $oAcordo = new Acordo($oParam->acordo);
            /*
            if (!$oAssinatura->verificaPeriodoPatrimonial()) {
                $lAcordoValido = false;
            }
            */

            if ($oAcordo->getNaturezaAcordo($oParam->acordo) == "1") {
                if ($oAcordo->getObras($oParam->acordo) == null) {
                    $iLicitacao = $oAcordo->getLicitacao();
                    $oLicitacao = new licitacao($iLicitacao);
                    $iAnousu     = $oLicitacao->getAno();
                    $iModalidade = $oLicitacao->getNumeroLicitacao();
                    $iProcesso   = $oLicitacao->getEdital();
                    $oModalidade = $oLicitacao->getModalidade();
                    $sDescricaoMod = $oModalidade->getDescricao();
                    throw new Exception("Contrato de Natureza OBRAS E SERVI�OS DE ENGENHARIA, sem Obra informada. Solicitar cadastro no m�dulo Obras para o processo N� $iProcesso/$iAnousu $sDescricaoMod N� $iModalidade/$iAnousu");
                }
            }

            if ($oDataPublicacao->getTimeStamp() < $oDataMovimentacao->getTimeStamp()) {
                throw new Exception("A data de assinatura do contrato n�o pode ser menor que a data de publica��o.");
            }


            /*
       * Valida��es caso a origem do contrato seja Licita��o
       * O sistema n�o deve permitir a inclus�o de acordos quando a data de assinatura do acordo for anterior a data de homologa��o da licita��o.
       * Para dispensa/inexigibilidade deve se validar a data de ratifica��o presente no cadastro de licita��o
       */
            if ($oAcordo->getOrigem() == 2) {
                foreach ($oAcordo->getLicitacoes() as $oLicitacao) {
                    $bValidaDispensa = in_array($oLicitacao->getModalidade()->getCodigo(), array(9, 10)) ? true : false;
                    if (!$oAcordo->validaDataAssinatura($oLicitacao->getCodigo(), $oParam->dtmovimentacao, $bValidaDispensa)) {
                        $lAcordoValido = false;
                        throw new Exception("A data de assinatura do acordo n�o pode ser anterior a data de homologa��o da licita��o.");
                    }
                }
            }

            if (strtotime($dtMovimento) > strtotime(str_replace("/", "-", $oAcordo->getDataFinal()))) {
                $lAcordoValido = false;
                throw new Exception("A data de assinatura do acordo {$oParam->dtmovimentacao} n�o pode ser posterior ao per�odo de vig�ncia do contrato {$oAcordo->getDataFinal()}.");
            }

            /**
             * Valida��o solicitada: n�o seja poss�vel incluir assinatura de acordos que n�o tenha as penalidades e garantias cadastradas.?
             * @see OC 3495, 4408
             */

            if (count($oAcordo->getPenalidades()) < 2 || count($oAcordo->getGarantias()) == 0) {
                $lAcordoValido = false;
                throw new Exception("N�o � permitido assinar um acordos que n�o tenha as penalidades e garantias cadastradas.");
            }

            /**
             * Valida��o soliciatada: Validar o sistema para que n�o seja poss�vel assinar acordos de origem Manual que n�o tenha itens vinculados.
             * @see OC 3499
             */

            if ($oAcordo->getOrigem() == Acordo::ORIGEM_MANUAL && count($oAcordo->getItens()) == 0) {
                $lAcordoValido = false;
                throw new Exception("Acordo sem itens Cadastrados.");
            } else {
                $itens = $oAcordo->getItens();
                foreach ($itens as $item) {
                    $dotacaoItem = $item->getDotacoes();

                    if ($dotacaoItem == null) {
                        $lAcordoValido = false;
                        throw new Exception("Usu�rio: Assinatura do contrato n�o inclu�da. Item " . $item->getMaterial()->getCodigo() . " sem dota��o.");
                    }

                    if ($item->getPeriodosItem() == null) {
                        $lAcordoValido = false;
                        throw new Exception("Preencha as datas de previs�o de execu��o dos �tens.");
                    }
                }
            }

            if ($oAcordo->getOrigem() == Acordo::ORIGEM_LICITACAO && count($oAcordo->getItens()) == 0) {
                $lAcordoValido = false;
                throw new Exception("Acordo sem itens Cadastrados.");
            } else {
                $itens = $oAcordo->getItens();
                foreach ($itens as $item) {
                    $dotacaoItem = $item->getDotacoes();

                    if ($dotacaoItem == null) {
                        $lAcordoValido = false;
                        throw new Exception("Usu�rio: Assinatura do contrato n�o inclu�da. Item " . $item->getMaterial()->getCodigo() . " sem dota��o.");
                    }

                    if ($item->getPeriodosItem() == null) {
                        $lAcordoValido = false;
                        throw new Exception("Preencha as datas de previs�o de execu��o dos �tens.");
                    }
                }
            }

            if ($oAcordo->getOrigem() == Acordo::ORIGEM_PROCESSO_COMPRAS && count($oAcordo->getItens()) == 0) {
                $lAcordoValido = false;
                throw new Exception("Acordo sem itens Cadastrados.");
            } else {
                $itens = $oAcordo->getItens();
                foreach ($itens as $item) {
                    $dotacaoItem = $item->getDotacoes();

                    if ($dotacaoItem == null) {
                        $lAcordoValido = false;
                        throw new Exception("Usu�rio: Assinatura do contrato n�o inclu�da. Item " . $item->getMaterial()->getCodigo() . " sem dota��o.");
                    }

                    if ($item->getPeriodosItem() == null) {
                        $lAcordoValido = false;
                        throw new Exception("Preencha as datas de previs�o de execu��o dos �tens.");
                    }
                }
            }

            $oAssinatura->save();

            $dataReferencia = db_query("select ac16_datareferencia from acordo where ac16_sequencial = $oParam->acordo");
            $dataReferencia = pg_result($dataReferencia, 0, 'ac16_datareferencia');
            $datoDataLancamentoLancamento = $dataReferencia;
            $oHomologacao = new AcordoHomologacao();
            $oHomologacao->setAcordo($oParam->acordo);
            $oHomologacao->setObservacao($sObservacao);
            $oHomologacao->save();
            $oAcordo                   = new Acordo($oParam->acordo);
            $oAcordoLancamentoContabil = new AcordoLancamentoContabil();
            $sHistorico = "Valor referente a homologa��o do contrato de c�digo: {$oParam->acordo}.";
            $oAcordoLancamentoContabil->registraControleContrato($oParam->acordo, $oAcordo->getValorContrato(), $sHistorico, $datoDataLancamentoLancamento);

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Cancelamento da assinatura para o contrato
   */
    case "cancelarAssinatura":

        try {

            db_inicio_transacao();
            $ac10Sequencial = $oParam->codigo;
            if(!empty($oParam->acordoMovimentacaoTipo) && $oParam->acordoMovimentacaoTipo == '11') {
                $oHomologacao = new AcordoHomologacao($oParam->codigo);
                $oHomologacao->setObservacao($sObservacao);
                $oHomologacao->cancelar();
                $oAcordo                   = new Acordo($oHomologacao->getAcordo());
                $oAcordoLancamentoContabil = new AcordoLancamentoContabil();
                $sHistorico = "Valor referente a cancelamento da homologa��o do contrato de c�digo: {$oAcordo->getCodigoAcordo()}.";
                $oAcordoLancamentoContabil->anulaRegistroControleContrato($oAcordo->getCodigoAcordo(), $oAcordo->getValorContrato(), $sHistorico, $oHomologacao->getDataMovimento());
                $ac10Sequencial = $oHomologacao->getUltimaAssinatura($oParam->ac16Sequencial);
            }

            $oAssinatura = new AcordoAssinatura($ac10Sequencial);

            /*
            if (!$oAssinatura->verificaPeriodoPatrimonial()) {
                $lAcordoValido = false;
            }
            */
            $oAssinatura->setDataMovimento();
            $oAssinatura->setObservacao($sObservacao);
            $oAssinatura->cancelar();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Pesquisa recis�o para o contrato
   */
    case "getDadosRescisao":

        try {

            $oRecisao                = new AcordoRescisao($oParam->codigo);
            $oAcordo                 = new Acordo($oRecisao->getAcordo());
            $oRetorno->codigo        = $oRecisao->getCodigo();
            $oRetorno->valorrescisao = $oAcordo->getValorRescisao();
            $oRetorno->acordo        = $oAcordo->getCodigoAcordo();

            $oRetorno->datamovimento = date("Y-m-d", db_getsession("DB_datausu"));
            $oRetorno->datamovimentoantiga = $oRecisao->getDataMovimento();
            $oRetorno->descricao     = urlencode($oAcordo->getResumoObjeto());

            $dataReferencia = db_query("select ac16_datareferenciarescisao from acordo where ac16_sequencial = $oRetorno->acordo ");
            $dataReferencia = pg_result($dataReferencia, 0, 'ac16_datareferenciarescisao');
            $oRetorno->datarescisao = $dataReferencia;
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Incluir recis�o para o contrato
   */
    case "rescindirContrato":

        try {
            db_inicio_transacao();
            $oAcordo = new Acordo($oParam->acordo);

            $oRecisao = new AcordoRescisao();
            $oRecisao->setAcordo($oParam->acordo);
            $nValorRescisao = str_replace(',', '.', $oParam->valorrescisao);
            $dtMovimento = implode("-", array_reverse(explode("/", $oParam->dtmovimentacao)));
            $dataReferencia = implode("-", array_reverse(explode("/", $oParam->datareferencia)));
            $oRecisao->setDataMovimento($dtMovimento);
            $oRecisao->setObservacao($sObservacao);
            $oRecisao->setValorRescisao($nValorRescisao);

            if ($dataReferencia == "") {
                $dataReferencia =  $dtMovimento;
            }

            /*
            if (!$oRecisao->verificaPeriodoPatrimonial()) {
                $lAcordoValido = false;
            } */

            if ($oRecisao->getValorRescisao() > $oAcordo->getValorContrato()) {
                throw new Exception("O valor rescindido n�o pode ser maior que o valor do acordo.");
            }

            $oRecisao->save();

            db_query("UPDATE acordo SET ac16_datareferenciarescisao = '$dataReferencia'  WHERE ac16_sequencial = $oParam->acordo");

            $oAcordoLancamentoContabil = new AcordoLancamentoContabil();
            $sHistorico = "Valor referente a rescis�o do contrato com o c�digo: {$oAcordo->getCodigoAcordo()}.";
            $oAcordoLancamentoContabil->anulaRegistroControleContrato($oAcordo->getCodigoAcordo(), $nValorRescisao, $sHistorico, $dataReferencia);

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   =  urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Cancelamento de recis�o para o contrato
   */
    case "cancelarRescisao":

        try {

            db_inicio_transacao();
            $dtMovimento = implode("-", array_reverse(explode("/", $oParam->sData)));
            $oRecisao = new AcordoRescisao($oParam->codigo);
            $nValorRescisao = floatval(str_replace(',', '.', $oParam->valorrescisao));

            /*
            if (!$oRecisao->verificaPeriodoPatrimonial()) {
                $lAcordoValido = false;
            }
            */
            $oRecisao->setDataMovimento();
            $oRecisao->setValorRescisao(0);
            $oRecisao->setObservacao($sObservacao);
            $oRecisao->cancelar();

            $oAcordo = new Acordo($oRecisao->getAcordo());
            $oAcordoLancamentoContabil = new AcordoLancamentoContabil();
            $sHistorico = "Valor referente ao cancelamento da rescis�o do contrato de c�digo: {$oAcordo->getCodigoAcordo()}.";
            $oAcordoLancamentoContabil->registraControleContrato($oAcordo->getCodigoAcordo(), $nValorRescisao, $sHistorico, $dtMovimento);

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Cancela cancelamento de recis�o para o contrato
   */
    case "desfazerCancelarRecisao":

        try {

            db_inicio_transacao();

            $oRecisao = new AcordoRescisao($oParam->codigo);
            if (!$oRecisao->verificaPeriodoPatrimonial()) {
                $lAcordoValido = false;
            }
            $oRecisao->setObservacao($sObservacao);
            $oRecisao->setDataMovimento();
            $oRecisao->desfazerCancelamento();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Pesquisa anula��o de contrato
   */
    case "getDadosAnulacao":

        try {

            $oAnulacao = new AcordoAnulacao($oParam->codigo);
            $oAcordo   = new Acordo($oAnulacao->getAcordo());
            $oRetorno->codigo        = $oAnulacao->getCodigo();
            $oRetorno->acordo        = $oAcordo->getCodigoAcordo();
            $oRetorno->datamovimento = date("Y-m-d", db_getsession("DB_datausu"));
            $oRetorno->descricao     = urlencode($oAcordo->getResumoObjeto());
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Incluir anula��o de contrato
   */
    case "anularContrato":

        try {

            db_inicio_transacao();

            $oAnulacao = new AcordoAnulacao();
            $oAnulacao->setAcordo($oParam->acordo);
            $dtMovimento = implode("-", array_reverse(explode("/", $oParam->dtmovimentacao)));
            $oAnulacao->setDataMovimento($dtMovimento);
            $oAnulacao->setObservacao($sObservacao);
            $oAnulacao->save();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Cancelamento de anula��o de contrato
   */
    case "cancelarAnulacao":

        try {

            db_inicio_transacao();

            $oAnulacao = new AcordoAnulacao($oParam->codigo);
            $oAnulacao->setDataMovimento();
            $oAnulacao->setObservacao($sObservacao);
            $oAnulacao->cancelar();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
   * Cancela cancelamento de anula��o de contrato
   */
    case "desfazerCancelarAnulacao":

        try {

            db_inicio_transacao();

            $oAnulacao = new AcordoAnulacao($oParam->codigo);
            $oAnulacao->setObservacao($sObservacao);
            $oAnulacao->setDataMovimento();
            $oAnulacao->desfazerCancelamento();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;
}

echo $oJson->encode($oRetorno);
