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
require_once("std/db_stdClass.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/financeiro/ContaBancaria.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");
require_once("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once 'classes/db_historicocgm_classe.php';

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->status = 1;
$oRetorno->message = '';


switch ($oParam->exec) {

    case "salvarPlanoConta":

        /**
         * No momento em que � salvo devemos verificar o estrutural da
         * conta e localizar a qual conta corrente o estrutural pertence.
         */


        try {

            $cl_historicocgm = new cl_historicocgm();
            $result_dtcadcgm = $cl_historicocgm->sql_record($cl_historicocgm->sql_query_file(null,'z09_datacadastro','z09_sequencial desc',"z09_numcgm = $oParam->iCgmPessoa"));

            db_fieldsmemory($result_dtcadcgm, 0)->z09_datacadastro;
            $dtlancamento = date("Y-m-d",db_getsession("DB_datausu"));

            if($dtlancamento < $z09_datacadastro){
                throw new Exception("Usu�rio: A data de cadastro do CGM informado � superior a data do procedimento que est� sendo realizado. Corrija a data de cadastro do CGM e tente novamente!");
            }

            db_inicio_transacao();
            $iAno = null;
            if ($oParam->iCodigoConta != "") {
                $iAno = db_getsession("DB_anousu");
            }
            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, $iAno);
            $oPlanoPCASP->setAno(db_getsession("DB_anousu"));
            $oPlanoPCASP->setNRegObrig($oParam->iNRegObrig);
            $oPlanoPCASP->setInfCompMSC($oParam->infCompMSC);
            $oPlanoPCASP->setFuncao(db_stdClass::normalizeStringJsonEscapeString($oParam->sFuncao));
            $oPlanoPCASP->setFinalidade(db_stdClass::normalizeStringJsonEscapeString($oParam->sFuncionamento));
            $oPlanoPCASP->setContraPartida("0");
            $oPlanoPCASP->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->sTitulo));
            $oPlanoPCASP->setEstrutural($oParam->sEstrutural);
            $oPlanoPCASP->setIdentificadorFinanceiro($oParam->sIndicadorSuperavit);
            $oPlanoPCASP->setNaturezaSaldo($oParam->iNaturezaSaldo);
            $oPlanoPCASP->setClassificacaoConta(new ClassificacaoConta($oParam->iClassificacao));
            $oPlanoPCASP->setSistemaConta(new SistemaConta($oParam->iDetalhamentoSistema));
            $oPlanoPCASP->setSubSistema(new SubSistemaConta($oParam->iSistemaConta));
            $oPlanoPCASP->setTipoLancamento($oParam->iTipoLancamento);
            $oPlanoPCASP->setSubTipo(is_numeric($oParam->iSubTipo) == true && $oParam->iSubTipo != 0 ? $oParam->iSubTipo : 0);
            $oPlanoPCASP->setDesdobramento(is_numeric($oParam->iDesdobramento) == true && $oParam->iDesdobramento != 0 ? $oParam->iDesdobramento : 0);
            if (!empty($oParam->iContaBancaria)) {
            $oPlanoPCASP->setContaBancaria(new ContaBancaria($oParam->iContaBancaria));
            }
            $oPlanoPCASP->setCgmPessoa($oParam->iCgmPessoa);
            $oPlanoPCASP->setNaturezadaReceita($oParam->iNaturezaReceita);


            /**
             * quando o iTipoConta for 1 = analitica, habilita o campo contacorrente
             * porem ele ainda nao � passado nesse case
             * madar junto para validar se deveremos buscar a conta corrente caso ele seja = 1
             *
             * buscar conta corrente a partir do estrutural na contacorrenteregravinculo
             *
             */
            if ($oParam->iTipoConta == 1) {

                $oDaoContaCorrenteRegraVinculo = db_utils::getDao("contacorrenteregravinculo");
                $sEstrutural = $oParam->sEstrutural;

                /*
                 * construimos a arvore do estrutural desejado para
                 * come�ar a busca
                 */
                $aNiveisEstruturais = ContaPlano::getNiveisEstruturais(str_replace(".", "", $sEstrutural));


                foreach ($aNiveisEstruturais as $oNiveisEstruturais) {

                    $sEstrutural = str_replace(".", "", $oNiveisEstruturais);
                    $sWhere = "c27_estrutural = '$sEstrutural' ";
                    $sSqlContaCorrenteRegraVinculo = $oDaoContaCorrenteRegraVinculo->sql_query_file(null, "*", null, $sWhere);
                    $rsContaCorrenteRegraVinculo = $oDaoContaCorrenteRegraVinculo->sql_record($sSqlContaCorrenteRegraVinculo);

                    if ($oDaoContaCorrenteRegraVinculo->numrows > 0) {

                        $iContaCorrente = db_utils::fieldsMemory($rsContaCorrenteRegraVinculo, 0)->c27_contacorrente;
                        $oPlanoPCASP->setContaCorrente(new ContaCorrente($iContaCorrente));
                        break;
                    }

                }

            }

            $oPlanoPCASP->salvar($oParam->iTipoConta);

            $oRetorno->message = urlencode("Plano de contas salvo com sucesso.");
            $oRetorno->iAno = $oPlanoPCASP->getAno();
            $oRetorno->iNRegObrig = $oPlanoPCASP->getNRegObrig();
            $oRetorno->sDescricao = urlencode($oPlanoPCASP->getDescricao());
            $oRetorno->sEstrutural = $oParam->sEstrutural;
            $oRetorno->sFinalidade = urlencode($oPlanoPCASP->getFinalidade());
            $oRetorno->sFuncao = urlencode($oPlanoPCASP->getFuncao());
            $oRetorno->sIdentificadorFinanceiro = $oPlanoPCASP->getIdentificadorFinanceiro();
            $oRetorno->iNaturezaSaldo = $oPlanoPCASP->getNaturezaSaldo();
            $oRetorno->iClassificacao = $oParam->iClassificacao;
            $oRetorno->iDetalhamentoSistema = $oParam->iDetalhamentoSistema;
            $oRetorno->iSubSistemaConta = $oParam->iSistemaConta;
            $oRetorno->iCodigoConta = $oPlanoPCASP->getCodigoConta();
            $oRetorno->iTipoLancamento = $oPlanoPCASP->getTipoLancamento();
            $oRetorno->iSubTipo = $oPlanoPCASP->getSubTipo();
            $oRetorno->iDesdobramento = $oPlanoPCASP->getDesdobramento();
            $oRetorno->iCgmPessoa = $oPlanoPCASP->getCgmPessoa();
            $oRetorno->iNaturezaReceita = $oPlanoPCASP->getNaturezadaReceita();
            $oRetorno->iContaCorrente = $oParam->iContaCorrente;

            db_fim_transacao(false);
        } catch (Exception $eErro) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
            db_fim_transacao(true);
        }
        break;

    case "salvarReduzido":

        try {

            db_inicio_transacao();

            /*
             * Valida se o par�metro iCodigoReduzido est� setado. Caso esteja, � realizado a ALTERA��O dentro do model
             */
            $iReduzidoParametro = null;
            if (isset($oParam->iCodigoReduzido)) {
                $iReduzidoParametro = $oParam->iCodigoReduzido;
            }

            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoPlanoConta, db_getsession("DB_anousu"), $iReduzidoParametro, $oParam->iCodigoInstituicao);
            $oPlanoPCASP->setInstituicao($oParam->iCodigoInstituicao)
                ->setRecurso($oParam->iCodigoRecurso)
                ->setCodigoTce($oParam->iCodigoTce)
                ->persistirReduzido();
            db_fim_transacao(false);
            $oRetorno->message = "Reduzidos salvos com sucesso!";
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case "getReduzidos":

        /*
         * Busca as contas reduzidas de um plano de contas do PCASP
         */
        $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, db_getsession("DB_anousu"));
        $oRetorno->aContasReduzidas = $oPlanoPCASP->getContasReduzidas();
        break;

    case "excluirReduzido":

        try {

            db_inicio_transacao();

            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoPlanoConta, db_getsession("DB_anousu"), $oParam->iCodigoReduzido, $oParam->iCodigoInstituicao);
            $oPlanoPCASP->removerReduzido($oParam->iCodigoReduzido);
            db_fim_transacao(false);
            $oRetorno->message = urlencode("Reduzido exclu�do com sucesso.");

        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case "vinculaPlanoOrcamentario":

        try {

            db_inicio_transacao();
            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoPlanoPCASP, db_getsession("DB_anousu"));
            $oPlanoPCASP->vinculaPlanoContasOrcamento($oParam->iCodigoPlanoOrcamento);
            db_fim_transacao(false);
            $oRetorno->message = urlencode("Contas vinculadas com sucesso!");

        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->message = urlencode($eErro->getMessage());
        }

        break;

    case "getVinculoPlanoOrcamento":

        $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, db_getsession("DB_anousu"));
        $aContas = $oPlanoPCASP->getVinculoContaOrcamento();
        $oRetorno->aContasOrcamento = $aContas;
        break;

    case "excluiVinculoPlanoOrcamento":

        try {

            db_inicio_transacao();
            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, db_getsession("DB_anousu"));
            $oPlanoPCASP->excluiVinculoContaOrcamento($oParam->iCodigoPlanoOrcamento);
            $oRetorno->message = urlencode("V�nculo exclu�do com sucesso.");
            db_fim_transacao(false);

        } catch (Exception $eErro) {
            db_fim_transacao(true);
            $oRetorno->message = urlencode($eErro->getMessage());
        }

        break;


    case "getPlanoContasPCASP":

        $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, db_getsession("DB_anousu"));
        $oRetorno->dados->iCodigoConta = $oPlanoPCASP->getCodigoConta();
        $oRetorno->dados->c60_nregobrig = $oPlanoPCASP->getNRegObrig();
        $oRetorno->dados->c90_estrutcontabil = db_formatar($oPlanoPCASP->getEstrutural(), 'receita');
        $oRetorno->dados->sTitulo = urlencode($oPlanoPCASP->getDescricao());
        $oRetorno->dados->iNaturezaSaldo = $oPlanoPCASP->getNaturezaSaldo();
        $oRetorno->dados->sFuncionamento = urlencode($oPlanoPCASP->getFinalidade());
        $oRetorno->dados->sFuncao = urlencode($oPlanoPCASP->getFuncao());
        $oRetorno->dados->iSistemaConta = $oPlanoPCASP->getSubSistema()->getCodigo();
        $oRetorno->dados->iDetalhamentoSistema = $oPlanoPCASP->getSistemaConta()->getCodigoSistemaConta();
        $oRetorno->dados->sDescricaoDetalhamentoSistema = urlencode($oPlanoPCASP->getSistemaConta()->getDescricao());
        $oRetorno->dados->iClassificacao = $oPlanoPCASP->getClassificacaoConta()->getCodigoClasse();
        $oRetorno->dados->sIndicadorSuperavit = $oPlanoPCASP->getIdentificadorFinanceiro();
        $oRetorno->dados->c60_naturezadareceita = $oPlanoPCASP->getNaturezadaReceita();
        $oRetorno->dados->c60_descr = $oPlanoPCASP->getDescrNaturezareceita($oPlanoPCASP->getNaturezadaReceita());
        $oRetorno->dados->iTipoLancamento = $oPlanoPCASP->getTipoLancamento();
        $oRetorno->dados->isubtipo = $oPlanoPCASP->getSubTipo();
        $oRetorno->dados->idesdobramento = $oPlanoPCASP->getDesdobramento();
        $oRetorno->dados->c60_infcompmsc = $oPlanoPCASP->getInfCompMSC();
        $oContaCorrente = $oPlanoPCASP->getContaCorrente();
        if (isset($oContaCorrente)) {

            $oRetorno->dados->iCodigoContaCorrente = $oPlanoPCASP->getContaCorrente()->getCodigo();
            $oRetorno->dados->sDescricaoContaCorrente = $oPlanoPCASP->getContaCorrente()->getDescricao();
        }

        $oRetorno->dados->iContaBancaria = "";
        if ($oPlanoPCASP->getContaBancaria() != null) {

            $oRetorno->dados->iContaBancaria = $oPlanoPCASP->getContaBancaria()->getSequencialContaBancaria();
            $oRetorno->dados->sDescricaoContaBancaria = urlencode($oPlanoPCASP->getContaBancaria()->getDadosConta());
        }
        $oRetorno->dados->iTipoConta = 0;
        if ($oPlanoPCASP->getContasReduzidas()) {
            $oRetorno->dados->iTipoConta = 1;
        }
        $oRetorno->dados->iCgmPessoa = $oPlanoPCASP->getCgmPessoa();
        //echo "<pre>";
        //print_r($oRetorno);exit;
        break;

    case 'removerConta':

        try {

            db_inicio_transacao();
            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, db_getsession("DB_anousu"));
            $oPlanoPCASP->excluir();
            db_fim_transacao(false);

        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    case "removerIndicadorSuperavit":

        try {

            db_inicio_transacao();

            $oPlanoPCASP = new ContaPlanoPCASP($oParam->iCodigoConta, db_getsession("DB_anousu"));
            $oPlanoPCASP->setIdentificadorFinanceiro('N');
            $oPlanoPCASP->salvar();
            db_fim_transacao(false);

        } catch (Exception $e) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($eErro->getMessage());
        }

    /*
     * adicionado para salvar os subtipos novos conforme exigido pelo sicom
     */
    case "salvarSubTipo":

        require_once("classes/db_subtipo_classe.php");
        try {

            db_inicio_transacao();

            $oSubTipo = new cl_subtipo();
            $oSubTipo->c200_tipo        = $oParam->c200_tipo;
            $oSubTipo->c200_subtipo     = $oParam->c200_subtipo;
            $oSubTipo->c200_descsubtipo = str_pad($oParam->c200_subtipo, 4, "0", STR_PAD_LEFT)."-".strtoupper(urldecode($oParam->c200_descsubtipo));
            $oSubTipo->incluir();
            $oRetorno->status = 1;
            $oRetorno->erro = false;
            $oRetorno->message = urlencode(str_replace("\n", '\n',$oSubTipo->erro_msg));
            db_fim_transacao(false);

        } catch (Exception $e) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;
    /*
     * adicionado para buscar os subtipos conforme tipo selecionado
     * altera��o sicom
     */
    case "getSubTipo":

        require_once("classes/db_subtipo_classe.php");
        try {

            $oSubTipo = new cl_subtipo();
            $rsSubtipo = db_query($oSubTipo->sql_query(null,"*","c200_subtipo","c200_tipo = {$oParam->c200_tipo}"));
            $oRetorno->aSubTipos = array();
            for ($iCont = 0; $iCont < pg_num_rows($rsSubtipo); $iCont++) {
                $oRetorno->aSubTipos[] = db_utils::fieldsMemory($rsSubtipo, $iCont);
            }
            $oRetorno->status = 1;
            $oRetorno->erro = false;

        } catch (Exception $e) {

            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;
    /*
     * adicionado para salvar os desdobrasubtipos novos conforme exigido pelo sicom
     */
    case "salvarDesdobraSubTipo":

        require_once("classes/db_desdobrasubtipo_classe.php");
        try {

            db_inicio_transacao();

            $oDesdobraSubTipo = new cl_desdobrasubtipo();
            $oDesdobraSubTipo->c201_tipo        = $oParam->c201_tipo;
            $oDesdobraSubTipo->c201_subtipo     = $oParam->c201_subtipo;
            $oDesdobraSubTipo->c201_desdobrasubtipo     = $oParam->c201_desdobrasubtipo;
            $oDesdobraSubTipo->c201_descdesdobrasubtipo = str_pad($oParam->c201_desdobrasubtipo, 4, "0", STR_PAD_LEFT)."-".strtoupper(urldecode($oParam->c201_descdesdobrasubtipo));
            $oDesdobraSubTipo->incluir();
            $oRetorno->status = 1;
            $oRetorno->erro = false;
            $oRetorno->message = urlencode(str_replace("\n", '\n',$oDesdobraSubTipo->erro_msg));
            db_fim_transacao(false);

        } catch (Exception $e) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
        break;

    /*
     * adicionado para buscar os desdobrasubtipos conforme tipo e subtipo selecionado
     * altera��o sicom
     */
    case "getDesdobraSubTipo":

        require_once("classes/db_desdobrasubtipo_classe.php");
        try {

            $oDesdobraSubTipo = new cl_desdobrasubtipo();
            $rsDesdobraSubtipo = db_query($oDesdobraSubTipo->sql_query(null,"*","c201_desdobrasubtipo","c201_tipo = {$oParam->c201_tipo} and c201_subtipo = {$oParam->c201_subtipo}"));
            $oRetorno->aDesdobraSubTipos = array();
            for ($iCont = 0; $iCont < pg_num_rows($rsDesdobraSubtipo); $iCont++) {
                $oRetorno->aDesdobraSubTipos[] = db_utils::fieldsMemory($rsDesdobraSubtipo, $iCont);
            }
            $oRetorno->status = 1;
            $oRetorno->erro = false;

        } catch (Exception $e) {

            $oRetorno->status = 2;
            $oRetorno->erro = true;
            $oRetorno->message = urlencode($eErro->getMessage());
        }
}
echo $oJson->encode($oRetorno);
