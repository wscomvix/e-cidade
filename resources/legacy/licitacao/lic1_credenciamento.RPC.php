<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitasituacao_classe.php");
include("classes/db_homologacaoadjudica_classe.php");
include("classes/db_credenciamento_classe.php");
include("classes/db_habilitacaoforn_classe.php");
include("classes/db_parecerlicitacao_classe.php");
include("classes/db_licitemobra_classe.php");
include("classes/db_acordo_classe.php");
include("classes/db_liccomissaocgm_classe.php");

$cllicitemobra         = new cl_licitemobra;
$clcredenciamento      = new cl_credenciamento;
$clitenshomologacao    = new cl_itenshomologacao;
$clhomologacaoadjudica = new cl_homologacaoadjudica;
$clliclicitasituacao   = new cl_liclicitasituacao;
$clliclicita           = new cl_liclicita;
$clliclicitasituacao   = new cl_liclicitasituacao;
$clhabilitacaoforn     = new cl_habilitacaoforn();
$clparecerlicitacao    = new cl_parecerlicitacao();
$clacordo              = new cl_acordo();
$clliccomissaocgm     = new cl_liccomissaocgm();

$oJson    = new services_json();
$oRetorno = new stdClass();
$oParam   = json_decode(str_replace('\\', '', $_POST["json"]));

$oRetorno->status   = 1;
$oRetorno->erro     = false;
$oRetorno->message  = '';

try {
    db_fim_transacao();

    switch ($oParam->exec) {

        case 'SalvarCred':

            db_inicio_transacao();

            $rsLimiteCred = $clliclicita->sql_record($clliclicita->sql_query_file($oParam->licitacao, "l20_dtlimitecredenciamento,l20_datacria,l20_dtpubratificacao", null, null));
            db_fieldsmemory($rsLimiteCred, 0);

            foreach ($oParam->itens as $item) {

                $rsHabilitacao = $clhabilitacaoforn->sql_record($clhabilitacaoforn->sql_query_file(null, "l206_datahab", null, "l206_fornecedor = $item->l205_fornecedor and l206_licitacao = $item->l205_licitacao"));
                db_fieldsmemory($rsHabilitacao, 0);

                $clcredenciamento->l205_fornecedor = $item->l205_fornecedor;
                $clcredenciamento->l205_datacred = $item->l205_datacreditem;
                $clcredenciamento->l205_item = $item->l205_item;
                $clcredenciamento->l205_licitacao = $item->l205_licitacao;
                $clcredenciamento->l205_datacreditem = $item->l205_datacreditem == "" || $item->l205_datacreditem == null ? $item->l205_datacred : $item->l205_datacreditem;

                $rsItem = $clcredenciamento->sql_record($clcredenciamento->sql_query(null, "*", null, "l205_item = {$item->l205_item} and l205_fornecedor={$item->l205_fornecedor}"));

                $dtcred = join('-', array_reverse(explode('/', $item->l205_datacreditem)));
                $l205_dtcred = join('-', array_reverse(explode('/', $item->l205_datacred)));

                if (!$rsItem) {
                    if ($dtcred < $l206_datahab) {
                        throw new Exception("Usu�rio: Campo Data de Credenciamento menor que Data de Habilita��o do Fornecedor. Item: $item->l205_item");
                    }

                    if ($l20_dtlimitecredenciamento != "") {
                        if (strtotime($l205_dtcred) > strtotime($l20_dtlimitecredenciamento)) {
                            throw new Exception("Usu�rio: Campo Data Credenciamento maior que data Limite de Credenciamento");
                        }
                    }

                    if ($dtcred < $l20_datacria) {
                        throw new Exception("Erro: Data de credenciamento menor que a data de abertura do procedimento adm.");
                    }

                    if ($l20_dtpubratificacao != "") {
                        if ($dtcred < $l20_dtpubratificacao) {
                            throw new Exception("Erro: A data de credenciamento deve ser maior ou igual a data de publica��o do Termo de Ratifica��o");
                        }
                    }

                    $clcredenciamento->incluir(null);

                    if ($clcredenciamento->erro_status == 0) {
                        $sqlerro = true;
                        $erro_msg = $clcredenciamento->erro_msg;
                        break;
                    }
                } else {

                    $oCredenciamento = db_utils::fieldsMemory($rsItem, 0);

                    if ($oCredenciamento->l205_datacreditem != $dtcred) {

                        if ($dtcred < $l206_datahab) {
                            throw new Exception("Usu�rio: Campo Data de Credenciamento menor que Data de Habilita��o do Fornecedor. Item: $item->l205_item");
                        }

                        if ($l20_dtlimitecredenciamento != "") {
                            if ($item->l205_datacred > $l20_dtlimitecredenciamento) {
                                throw new Exception("Usu�rio: Campo Data Credenciamento maior que data Limite de Credenciamento");
                            }
                        }

                        if ($dtcred < $l20_datacria) {
                            throw new Exception("Erro: Data de credenciamento menor que a data de abertura do procedimento adm.");
                        }

                        if ($l20_dtpubratificacao != "") {
                            if ($dtcred < $l20_dtpubratificacao) {
                                throw new Exception("Erro: A data de credenciamento deve ser maior ou igual a data de publica��o do Termo de Ratifica��o");
                            }
                        }

                        $clcredenciamento->l205_sequencial = $oCredenciamento->l205_sequencial;

                        $clcredenciamento->alterar();

                        if ($clcredenciamento->erro_status == 0) {
                            $sqlerro = true;
                            $erro_msg = $clcredenciamento->erro_msg;
                            break;
                        }
                    }
                }

                $oRetorno->sequecialforne = $item->sequenciaforne;
            }

            db_fim_transacao($sqlerro);

            break;

        case 'getCredforne':
            $aItens = array();

            $resultcredforne = $clcredenciamento->sql_record($clcredenciamento->sql_query(null, "*", null, "l205_fornecedor = {$oParam->forne} and l205_licitacao = {$oParam->licitacao}"));
            if (pg_num_rows($resultcredforne) != 0) {
                $oRetorno->result = $nenhumresultado;
                for ($iContItens = 0; $iContItens < pg_num_rows($resultcredforne); $iContItens++) {
                    $oItens = db_utils::fieldsMemory($resultcredforne, $iContItens);
                    $aItens[] = $oItens;
                }
                $oRetorno->itens = $aItens;
            } else {
                $oRetorno->itens = null;
            }

            break;

        case 'excluirCred':

            $clcredenciamento->excluir(null, $oParam->forne, $oParam->licitacao);

            if ($clcredenciamento->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clcredenciamento->erro_msg;
                break;
            }
            break;

        case 'julgarLic':

            /*altero a situa��o da licitacao para julgada*/
            $clliclicita->alterarSituacaoCredenciamento($oParam->licitacao, 1);

            /*salvo os dados da situa��o na tabela licsituacao*/
            $l11_sequencial                       = '';
            $clliclicitasituacao->l11_id_usuario  = DB_getSession("DB_id_usuario");
            $clliclicitasituacao->l11_licsituacao = 1;
            $clliclicitasituacao->l11_liclicita   = $oParam->licitacao;
            $clliclicitasituacao->l11_obs         = "Licita��o Julgada";
            $clliclicitasituacao->l11_data        = date("Y-m-d", DB_getSession("DB_datausu"));
            $clliclicitasituacao->l11_hora        = DB_hora();
            $clliclicitasituacao->incluir($l11_sequencial);

            if ($clliclicitasituacao->erro_status == 0) {
                $sqlerro = true;
            }

            break;

        case 'salvarHomo':

            $sSql = 'SELECT c99_datapat FROM condataconf WHERE c99_anousu = ' . db_getsession('DB_anousu') . ' and c99_instit = ' . db_getsession('DB_instit');
            $rsSql = db_query($sSql);
            $datapat = db_utils::fieldsMemory($rsSql, 0)->c99_datapat;

            if ($datapat >= join('-', array_reverse(explode('/', $oParam->l20_dtpubratificacao)))) {
                throw new Exception('O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.');
            };

            /**
             * busco o codtipocom
             */

            $result = $clliclicita->sql_record($clliclicita->sql_query_file(null, "l20_codtipocom,l20_dispensaporvalor,l20_cadinicial,l20_tipoprocesso", null, "l20_codigo = $oParam->licitacao"));
            $oresultlicitacao = db_utils::fieldsMemory($result, 0);

            /**
             * valida��o do parecer
             *
             */
            $rsParecer = $clparecerlicitacao->sql_record($clparecerlicitacao->sql_query_file(null, "*", null, "l200_licitacao = $oParam->licitacao"));

            if (pg_num_rows($rsParecer) == 0) {
                throw new Exception("Usu�rio: Licita��o sem Parecer cadastrado.");
            }

            /**
             * valida��o items obra
             */
            $resultLicitacao = $clliclicita->sql_record($clliclicita->sql_query(null, "l20_naturezaobjeto", null, "l20_codigo = $oParam->licitacao"));

            if (pg_num_rows($resultLicitacao) > 0) {
                db_fieldsmemory($resultLicitacao, 0);

                if ($l20_naturezaobjeto == "1") {
                    //Verifica itens obra
                    $aPcmater = $clliclicita->getPcmaterObras($oParam->licitacao);
                    foreach ($aPcmater as $item) {

                        $rsverifica = $cllicitemobra->sql_record($cllicitemobra->sql_query(null, "*", null, "obr06_pcmater = $item->pc16_codmater"));
                        if (pg_num_rows($rsverifica) == 0) {
                            throw new Exception("Itens $item->pc16_codmater obras n�o cadastrados");
                        }
                    }
                }
            }
            /**
             * realiza as altera��es na licita�ao
             */

            db_inicio_transacao();
            $result = $clliclicita->sql_record($clliclicita->sql_query_file(null, "l20_codtipocom,l20_datacria,l20_tipoprocesso", null, "l20_codigo = $oParam->licitacao"));
            $rsCredenciamento = $clcredenciamento->sql_record($clcredenciamento->sql_query(null, "max(l205_datacred) as l205_datacred", null, "l205_licitacao = $oParam->licitacao"));

            $l20_datacria = implode("/", (array_reverse(explode("-", db_utils::fieldsMemory($result, 0)->l20_datacria))));
            $l205_datacred  = strtotime(implode("/", (array_reverse(explode("-", db_utils::fieldsMemory($rsCredenciamento, 0)->l205_datacred)))));
            $l20_dtpubratificacao = strtotime(implode("/", (array_reverse(explode("-", $oParam->l20_dtpubratificacao)))));
            $l20_dtlimitecredenciamento = strtotime(implode("/", (array_reverse(explode("-", $oParam->l20_dtlimitecredenciamento)))));

            $datapublicacao = DateTime::createFromFormat('d/m/Y', $oParam->l20_dtpubratificacao);
            $datacriacao = DateTime::createFromFormat('d/m/Y', $l20_datacria);

            if ($datapublicacao < $datacriacao) {
                throw new Exception("Usu�rio: Campo Data Publica��o Termo Ratifica��o menor que data de cria��o.");
            }

            if ($oParam->l20_dtpubratificacao == null || $oParam->l20_dtpubratificacao == null) {
                throw new Exception("Usu�rio: Campo Data Publica��o Termo Ratifica��o n�o Informado.");
            }

            if ($oParam->l20_tipoprocesso == "103" ||  $oParam->l20_tipoprocesso == "102") {

                if ($oParam->l20_dtlimitecredenciamento == "") {
                    throw new Exception("Usu�rio: Campo Data Limite Credenciamento n�o Informado.");
                }
            }

            if ($oParam->l20_veicdivulgacao == null || $oParam->l20_veicdivulgacao == "") {
                throw new Exception("Usu�rio: Campo ve�culo de divulga��o n�o Informado.");
            }

            if (strlen($oParam->l20_veicdivulgacao) < 10 || strlen($oParam->l20_veicdivulgacao) > 80) {
                throw new Exception("O campo Veiculo deve ter no m�nimo 10 caracteres e no m�ximo 80");
            }

            if($oresultlicitacao->l20_dispensaporvalor =='f' || $oresultlicitacao->l20_tipoprocesso == '2' || $oresultlicitacao->l20_tipoprocesso == '3'){

                if ($oresultlicitacao->l20_cadinicial == '1' || $oresultlicitacao->l20_cadinicial == '2') {
                    throw new Exception("Usu�rio: O envio do edital deste processo se encontra com o status de PENDENTE/AGUARDANDO ENVIO. Gentileza verificar se o envio foi realizado e alterar o status para ENVIADO para que seja poss�vel inserir a publica��o do Termo de Ratifica��o.");
                }
            }

            $clliclicita->l20_codtipocom = $oresultlicitacao->l20_codtipocom;
            $clliclicita->l20_datacria = implode("-", (array_reverse(explode("/", db_utils::fieldsMemory($result, 0)->l20_datacria))));
            $clliclicita->l205_datacred = implode("-", (array_reverse(explode("/", $l205_datacred))));
            $clliclicita->l20_codigo = $oParam->licitacao;
            $clliclicita->l20_tipoprocesso = $oParam->l20_tipoprocesso;
            $clliclicita->l20_dtpubratificacao = implode("-", (array_reverse(explode("/", $oParam->l20_dtpubratificacao))));
            $clliclicita->l20_dtlimitecredenciamento = $oParam->l20_dtlimitecredenciamento;
            $clliclicita->l20_veicdivulgacao = $oParam->l20_veicdivulgacao;
            $clliclicita->alterar($oParam->licitacao, null, null);

            if ($clliclicita->erro_status == "0") {
                $erro_msg = $clliclicita->erro_msg;
                $sqlerro = true;
                $oRetorno->message = $erro_msg;
            }

            /**
             * incluir a situa�ao homologada
             */
            $oresultlicitacao->l20_cadinicial     = $oresultlicitacao->l20_cadinicial;
            $clliclicitasituacao->l11_data        = date("Y-m-d", db_getsession("DB_datausu"));
            $clliclicitasituacao->l11_hora        = db_hora();
            $clliclicitasituacao->l11_licsituacao = 10;
            $clliclicitasituacao->l11_id_usuario  = db_getsession("DB_id_usuario");
            $clliclicitasituacao->l11_liclicita   = $oParam->licitacao;
            $clliclicitasituacao->l11_obs         = "Homologa��o";
            $clliclicitasituacao->incluir(null);

            if ($clliclicitasituacao->erro_status == "0") {
                $erro_msg = $clliclicitasituacao->erro_msg;
                $sqlerro = true;
                $oRetorno->message = $erro_msg;
            }

            /**
             * alterando a situa��o da licita��o para homologada
             */

            $clliclicita->alterarSituacaoCredenciamento($oParam->licitacao, 10);

            /**
             * incluir a homologa��o
             */
            $clhomologacaoadjudica->l202_licitacao = $oParam->licitacao;
            $clhomologacaoadjudica->l202_datahomologacao = $oParam->l20_dtpubratificacao;
            $clhomologacaoadjudica->l202_dataadjudicacao = $oParam->l20_dtpubratificacao;
            $clhomologacaoadjudica->incluir(null);

            if ($clhomologacaoadjudica->erro_status == "0") {
                $erro_msg = $clhomologacaoadjudica->erro_msg;
                $sqlerro = true;
                $oRetorno->message = $erro_msg;
            }
            /**
             * incluir itens na homologa��o
             */

            //getTipoCompra
            $sqlTipoCompra = "SELECT l44_sequencial
                                FROM liclicita
                                INNER JOIN cflicita ON l03_codigo = l20_codtipocom
                                INNER JOIN pctipocompratribunal ON l44_sequencial = l03_pctipocompratribunal
                                WHERE l20_codigo = $oParam->licitacao";
            $rsSqlTipoCompra = db_query($sqlTipoCompra);
            $l44_sequencial = db_utils::fieldsMemory($rsSqlTipoCompra, 0)->l44_sequencial;

            if ($l44_sequencial == "100" || $l44_sequencial == "101") {
                $sqlVencedor = "select z01_numcgm
                                    from liclicita
                                    inner join liclicitem on l21_codliclicita=l20_codigo
                                    inner join pcorcamitemlic on pc26_liclicitem=l21_codigo
                                    inner join pcorcamitem on pc22_orcamitem=pc26_orcamitem
                                    inner join pcorcamjulg on pc24_orcamitem=pc22_orcamitem
                                    inner join pcorcamforne on pc21_orcamforne=pc24_orcamforne
                                    inner join pcorcamval on pc23_orcamitem=pc22_orcamitem
                                    inner join cgm on z01_numcgm=pc21_numcgm
                                    where l20_codigo=$oParam->licitacao and pc24_pontuacao=1";
                $rsSqlVencedor = db_query($sqlVencedor);
                $fornecedor = db_utils::fieldsMemory($rsSqlVencedor, 0)->z01_numcgm;

                foreach ($oParam->itens as $iten) {
                    $clitenshomologacao->l203_item                  = $iten->l205_item;
                    $clitenshomologacao->l203_homologaadjudicacao   = $clhomologacaoadjudica->l202_sequencial;
                    $clitenshomologacao->l203_fornecedor            = $fornecedor;
                    $clitenshomologacao->incluir(null);
                }
            } else {
                foreach ($oParam->itens as $iten) {
                    $clitenshomologacao->l203_item                  = $iten->l205_item;
                    $clitenshomologacao->l203_homologaadjudicacao   = $clhomologacaoadjudica->l202_sequencial;
                    $clitenshomologacao->incluir(null);
                }
            }

            if ($clitenshomologacao->erro_status == "0") {
                $erro_msg = $clitenshomologacao->erro_msg;
                $sqlerro = true;
                $oRetorno->message = $erro_msg;
            }

            if ($oParam->respRaticodigo != "") {

                $clliccomissaocgm->l31_numcgm = $oParam->respRaticodigo;
                $clliccomissaocgm->l31_tipo = 2;
                $clliccomissaocgm->l31_licitacao = $oParam->licitacao;
                $clliccomissaocgm->incluir(null);
            }

            if ($oParam->respPubliccodigo != "") {

                $clliccomissaocgm->l31_numcgm = $oParam->respPubliccodigo;
                $clliccomissaocgm->l31_tipo = 8;
                $clliccomissaocgm->l31_licitacao = $oParam->licitacao;
                $clliccomissaocgm->incluir(null);
            }

            db_fim_transacao();
            break;

        case 'alterarHomo':

            $sSql = 'SELECT c99_datapat FROM condataconf WHERE c99_anousu = ' . db_getsession('DB_anousu') . ' and c99_instit = ' . db_getsession('DB_instit');
            $rsSql = db_query($sSql);

            $datapat = db_utils::fieldsMemory($rsSql, 0)->c99_datapat;

            if ($datapat >= join('-', array_reverse(explode('/', $oParam->l20_dtpubratificacao)))) {
                throw new Exception('O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.');
            };

            /**
             * verifico tipo de compra da licitacao
             */
            $rsTipoCompra = $clliclicita->sql_record($clliclicita->getTipocomTribunal($oParam->licitacao));
            db_fieldsmemory($rsTipoCompra, 0)->l03_pctipocompratribunal;

            if ($l03_pctipocompratribunal == "100" || $l03_pctipocompratribunal == "101") {

                /**
                 * verifica se existe acordo para bloquear altera��o
                 */
                $rsAcordos = $clacordo->sql_record($clacordo->sql_queryLicitacoesVinculadas(null, "*", null, "and l20_codigo= $oParam->licitacao"));
                if (pg_num_rows($rsAcordos) >= 1) {
                    throw new Exception('Existe contratos cadastrados para essa Licita��o n�o e poss�vel Alterar.');
                }
            }
            /**
             * busco sequencial da homologa��o
             */
            $result = $clhomologacaoadjudica->sql_record($clhomologacaoadjudica->sql_query(null, "l203_homologaadjudicacao", null, "l202_licitacao = $oParam->licitacao"));

            $l203_homologaadjudicacao = pg_result($result, 0, 0);

            /**
             * busco o codtipocom
             */

            $rsResult = $clliclicita->sql_record($clliclicita->sql_query_file(null, "l20_codtipocom,l20_datacria,l20_tipoprocesso", null, "l20_codigo = $oParam->licitacao"));
            db_fieldsmemory($rsResult, 0);

            $l20_datacria = implode("/", (array_reverse(explode("-", $l20_datacria))));
            $datapublicacao = DateTime::createFromFormat('d/m/Y', $oParam->l20_dtpubratificacao);
            $datacriacao = DateTime::createFromFormat('d/m/Y', $l20_datacria);

            if ($datapublicacao < $datacriacao) {
                throw new Exception('Usu�rio: Campo Data Publica��o Termo Ratifica��o menor que data de cria��o da licita��o.');
                $sqlerro = true;
            }

            /**
             * alterando a data de homologa��o e adjundica��o
             */

            if ($sqlerro == false) {
                $clhomologacaoadjudica->l202_dataadjudicacao = $oParam->l20_dtpubratificacao;
                $clhomologacaoadjudica->l202_datahomologacao = $oParam->l20_dtpubratificacao;
                $clhomologacaoadjudica->alterar($l203_homologaadjudicacao);
            }

            if ($clhomologacaoadjudica->erro_status == "0") {
                $erro_msg = $clhomologacaoadjudica->erro_msg;
                $sqlerro = true;
                $oRetorno = $erro_msg;
            }


            /**
             * excluindo itens anteriores da homologa��o
             */
            $clitenshomologacao->excluir(null, "l203_homologaadjudicacao = {$l203_homologaadjudicacao}");

            /**
             * incluindo novos itens
             */
            foreach ($oParam->itens as $iten) {
                $clitenshomologacao->l203_item = $iten->l205_item;
                $clitenshomologacao->l203_homologaadjudicacao = $l203_homologaadjudicacao;
                $clitenshomologacao->incluir(null);
            }

            if ($clitenshomologacao->erro_status == "0") {
                $erro_msg = $clitenshomologacao->erro_msg;
                $sqlerro = true;
                $oRetorno = $erro_msg;
            }

            /**
             * altero a licita��o
             */

            $clliclicita->l20_codtipocom = $l20_codtipocom;
            $clliclicita->l20_codigo = $oParam->licitacao;
            $clliclicita->l20_tipoprocesso = $l20_tipoprocesso;
            $clliclicita->l20_dtpubratificacao = $oParam->l20_dtpubratificacao;
            $clliclicita->l20_dtlimitecredenciamento = $oParam->l20_dtlimitecredenciamento;
            $clliclicita->l20_veicdivulgacao = $oParam->l20_veicdivulgacao;
            //            $clliclicita->l20_justificativa = $oParam->l20_justificativa;
            //            $clliclicita->l20_razao = $oParam->l20_razao;
            $clliclicita->alterar($oParam->licitacao, null, null);

            if ($clliclicita->erro_status == "0") {
                $erro_msg = $clliclicita->erro_msg;
                $sqlerro = true;
                $oRetorno = $erro_msg;
            }

            if ($oParam->respRaticodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '2' and l31_licitacao = $oParam->licitacao";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $oParam->respRaticodigo;
                $clliccomissaocgm->l31_tipo = 2;
                $clliccomissaocgm->l31_licitacao = $oParam->licitacao;
                $clliccomissaocgm->incluir(null);
            }

            if ($oParam->respPubliccodigo != "") {
                //excluir o reponsavel
                $dbquery = "l31_tipo = '5' and l31_licitacao = $oParam->licitacao";
                $clliccomissaocgm->excluir(null, $dbquery);

                $clliccomissaocgm->l31_numcgm = $oParam->respPubliccodigo;
                $clliccomissaocgm->l31_tipo = 5;
                $clliccomissaocgm->l31_licitacao = $oParam->licitacao;
                $clliccomissaocgm->incluir(null);
            }


            break;

        case 'excluirHomo':
            db_inicio_transacao();

            /*
             * Verifica o per�odo de encerramento patrimonial
             * */

            $sSql = 'SELECT c99_datapat FROM condataconf WHERE c99_anousu = ' . db_getsession('DB_anousu') . ' and c99_instit = ' . db_getsession('DB_instit');
            $rsSql = db_query($sSql);
            $datapat = db_utils::fieldsMemory($rsSql, 0)->c99_datapat;

            if ($datapat >= join('-', array_reverse(explode('/', $oParam->ratificacao)))) {
                throw new Exception('O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.');
            }

            /**
             * verifico tipo de compra da licitacao
             */
            $rsTipoCompra = $clliclicita->sql_record($clliclicita->getTipocomTribunal($oParam->licitacao));
            db_fieldsmemory($rsTipoCompra, 0)->l03_pctipocompratribunal;

            if ($l03_pctipocompratribunal == "100" || $l03_pctipocompratribunal == "101" || $l03_pctipocompratribunal == "102" || $l03_pctipocompratribunal == "103") {
                /**
                 * verifica se existe acordo para bloquear exclus�o
                 */
                $rsAcordos = $clacordo->sql_record($clacordo->sql_queryLicitacoesVinculadas(null, "*", null, "and l20_codigo= $oParam->licitacao"));
                if (pg_num_rows($rsAcordos) >= 1) {
                    throw new Exception('Existe contratos cadastrados para essa Licita��o n�o e poss�vel excluir.');
                }
            }

            $clliccomissaocgm->excluir(null, "l31_licitacao = $oParam->licitacao and l31_tipo = '8'");
            $clliccomissaocgm->excluir(null, "l31_licitacao = $oParam->licitacao and l31_tipo = '2'");

            /**
             * busco sequencial da homologa��o
             */
            $result = $clhomologacaoadjudica->sql_record($clhomologacaoadjudica->sql_query(null, "l203_homologaadjudicacao", null, "l202_licitacao = $oParam->licitacao"));

            $l203_homologaadjudicacao = pg_result($result, 0, 0);

            /**
             * excluindo itens da homologa��o
             */
            $clitenshomologacao->excluir(null, "l203_homologaadjudicacao = {$l203_homologaadjudicacao}");

            /**
             * excluindo homologa��o
             */
            $clhomologacaoadjudica->excluir(null, "l202_sequencial = {$l203_homologaadjudicacao}");

            /**
             * inseriondo cancelamento de homologacao
             */

            $clliclicitasituacao->l11_data        = date("Y-m-d", db_getsession("DB_datausu"));
            $clliclicitasituacao->l11_hora        = db_hora();
            $clliclicitasituacao->l11_licsituacao = 1;
            $clliclicitasituacao->l11_id_usuario  = db_getsession("DB_id_usuario");
            $clliclicitasituacao->l11_liclicita   = $oParam->licitacao;
            $clliclicitasituacao->l11_obs         = "Cancelamento da Homologa��o";
            $clliclicitasituacao->incluir(null);

            if ($clliclicitasituacao->erro_status == "0") {
                $erro_msg = $clliclicitasituacao->erro_msg;
                $sqlerro = true;
                $oRetorno = $erro_msg;
            }

            /**
             * alterando a situa��o da licitacao para julgada
             */

            $clliclicita->alterarSituacaoCredenciamento($oParam->licitacao, 1);

            /**
             * alterando a liclicita
             */

            $clliclicita->excluirpublicacaocredenciamento($oParam->licitacao);
            db_fim_transacao();

            break;

        case 'getItensHomo':
            $aItens = array();

            $result = $clhomologacaoadjudica->sql_record($clhomologacaoadjudica->sql_query(null, "l203_item,l202_datahomologacao,l20_dtlimitecredenciamento", null, "l202_licitacao = {$oParam->licitacao}"));

            for ($iContItens = 0; $iContItens < pg_num_rows($result); $iContItens++) {
                $oItens = db_utils::fieldsMemory($result, $iContItens);
                $aItens[] = $oItens;
            }
            $oRetorno->itens = $aItens;
            $oRetorno->dtpublicacao = $oItens->l202_datahomologacao;
            $oRetorno->dtlimitecredenciamento = $oItens->l20_dtlimitecredenciamento;

            break;

        case 'getCredenciamento':
            $aItensCred = array();

            $result = $clcredenciamento->sql_record($clcredenciamento->sql_query_file(null, "l205_sequencial,l20_licsituacao", null, "l205_licitacao = {$oParam->licitacao} limit 1"));

            for ($iContItens = 0; $iContItens < pg_num_rows($result); $iContItens++) {
                $oItens = db_utils::fieldsMemory($result, $iContItens);
                $aItensCred[] = $oItens;
            }
            $oRetorno->itens = $aItensCred;

            break;
    }

    db_fim_transacao(true);
} catch (Exception $eErro) {
    $oRetorno->erro  = true;
    $oRetorno->status = 2;
    $oRetorno->message = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
