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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tipoquestaoaudit_classe.php");
require_once("classes/db_questaoaudit_classe.php");
require_once("classes/db_lancamverifaudit_classe.php");
require_once("classes/db_matrizachadosaudit_classe.php");

$cltipoquestaoaudit = new cl_tipoquestaoaudit;
$clquestaoaudit     = new cl_questaoaudit;
$clmatrizachadosaudit = new cl_matrizachadosaudit;

include("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$sqlerro           = false;
$oRetorno->aQuestoes = array();

$iInstit = db_getsession('DB_instit');

if (isset($oParam->sAchados) && !empty($oParam->sAchados)) {
    $oParam->sAchados = str_replace("<contrabarra>", "\\", $oParam->sAchados);            
}

try {

    switch ($oParam->exec) {

        case "buscaQuestoes":

            db_inicio_transacao();

            if ($oParam->iOpcao == 1) {
                $sWhere = "ci03_codproc = {$oParam->iCodProc} AND ci02_instit = {$iInstit} AND ci05_codlan IS NULL";
            } elseif ($oParam->iOpcao == 2) {
                $sWhere = "ci03_codproc = {$oParam->iCodProc} AND ci02_instit = {$iInstit} AND ci05_codlan IS NOT NULL";
            } elseif ($oParam->iOpcao == 3) {
                $sWhere = "ci03_codproc = {$oParam->iCodProc} AND ci02_instit = {$iInstit}";
            }

            $sSqlQuestoes = $clquestaoaudit->sql_questao_processo(null, "*", "ci02_numquestao", $sWhere);
            $rsQuestoes = db_query($sSqlQuestoes);
            
            if(pg_num_rows($rsQuestoes) > 0) {

                for ($iCont = 0; $iCont < pg_num_rows($rsQuestoes); $iCont++ ){

                    $oQuestaoBusca = db_utils::fieldsMemory($rsQuestoes, $iCont);
                    
                    $oQuestao = new stdClass();
                    $oQuestao->ci05_codlan            = $oQuestaoBusca->ci05_codlan;
                    $oQuestao->ci02_codquestao        = $oQuestaoBusca->ci02_codquestao;
                    $oQuestao->ci02_numquestao        = $oQuestaoBusca->ci02_numquestao;
                    $oQuestao->ci03_codproc           = $oQuestaoBusca->ci03_codproc;
                    $oQuestao->ci02_questao           = urlencode($oQuestaoBusca->ci02_questao);
                    $oQuestao->ci02_inforeq           = urlencode($oQuestaoBusca->ci02_inforeq);
                    $oQuestao->ci02_fonteinfo         = urlencode($oQuestaoBusca->ci02_fonteinfo);
                    $oQuestao->ci02_procdetal         = urlencode($oQuestaoBusca->ci02_procdetal);
                    $oQuestao->ci02_objeto            = urlencode($oQuestaoBusca->ci02_objeto);
                    $oQuestao->ci02_possivachadneg    = urlencode($oQuestaoBusca->ci02_possivachadneg);
                    $oQuestao->ci05_inianalise        = $oQuestaoBusca->ci05_inianalise;
                    $oQuestao->ci05_atendquestaudit   = $oQuestaoBusca->ci05_atendquestaudit;
                    $oQuestao->ci05_achados           = urlencode($oQuestaoBusca->ci05_achados);

                    $oRetorno->aQuestoes[] = $oQuestao;

                }
                
            }

        break;

        case "salvaLancamento":

            db_inicio_transacao();

            $cllancamverifaudit = new cl_lancamverifaudit;
            $cllancamverifaudit->ci05_codproc           = $oParam->iCodProc;
            $cllancamverifaudit->ci05_codquestao        = $oParam->iCodQuestao;
            $cllancamverifaudit->ci05_inianalise_dia    = $oParam->dtDataIniDia;
            $cllancamverifaudit->ci05_inianalise_mes    = $oParam->dtDataIniMes;
            $cllancamverifaudit->ci05_inianalise_ano    = $oParam->dtDataIniAno;
            $cllancamverifaudit->ci05_atendquestaudit   = $oParam->bAtendeQuest;
            $cllancamverifaudit->ci05_achados           = addslashes(db_stdClass::normalizeStringJson($oParam->sAchados));
            $cllancamverifaudit->ci05_instit            = $iInstit;

            $cllancamverifaudit->incluir();

            if ($cllancamverifaudit->erro_status == "0") {
                throw new Exception("Erro ao adicionar achado ao lan�amento (1). \n".$cllancamverifaudit->erro_sql, null);
            }

            $oRetorno->iLinha       = $oParam->iLinha;
            $oRetorno->iCodLan      = $cllancamverifaudit->ci05_codlan;
            $oRetorno->sMensagem    = "Achado adicionado ao lan�amento com sucesso!";   

        break;

        case "atualizaLancamento":
            
            db_inicio_transacao();

            $cllancamverifaudit = new cl_lancamverifaudit;
            $sSql = $cllancamverifaudit->sql_query(null, "*", null, "ci05_codproc = {$oParam->iCodLan}");
            $cllancamverifaudit->sql_record($sSql);

            $cllancamverifaudit->ci05_codproc           = $oParam->iCodProc;
            $cllancamverifaudit->ci05_codquestao        = $oParam->iCodQuestao;
            $cllancamverifaudit->ci05_inianalise_dia    = $oParam->dtDataIniDia;
            $cllancamverifaudit->ci05_inianalise_mes    = $oParam->dtDataIniMes;
            $cllancamverifaudit->ci05_inianalise_ano    = $oParam->dtDataIniAno;
            $cllancamverifaudit->ci05_atendquestaudit   = $oParam->bAtendeQuest;
            $cllancamverifaudit->ci05_achados           = addslashes(db_stdClass::normalizeStringJson($oParam->sAchados));

            $cllancamverifaudit->alterar($oParam->iCodLan);

            if ($cllancamverifaudit->erro_status == "0") {
                throw new Exception("Erro ao adicionar achado ao lan�amento (2). \n".$cllancamverifaudit->erro_sql, null);
            }

            $oRetorno->iLinha       = $oParam->iLinha;
            $oRetorno->iCodLan      = $oParam->iCodLan;
            $oRetorno->sMensagem    = "Achado adicionado ao lan�amento com sucesso!"; 

        break;

        case "salvaGeral":
            
            db_inicio_transacao();

            foreach ($oParam->questoesEnviar as $oQuestao) {

                $cllancamverifaudit = new cl_lancamverifaudit;

                $iNumLancamentos = 0;

                if (isset($oQuestao->iCodLan) && $oQuestao->iCodLan != "") {
                    
                    $sSql = $cllancamverifaudit->sql_query(null, "*", null, "ci05_codlan = {$oQuestao->iCodLan}");
                    $rsLancam = $cllancamverifaudit->sql_record($sSql);
                    $iNumLancamentos = $cllancamverifaudit->numrows;

                }

                if ($iNumLancamentos > 0) {

                    $cllancamverifaudit->ci05_inianalise_dia    = $oQuestao->dtDataIniDia;
                    $cllancamverifaudit->ci05_inianalise_mes    = $oQuestao->dtDataIniMes;
                    $cllancamverifaudit->ci05_inianalise_ano    = $oQuestao->dtDataIniAno;
                    $cllancamverifaudit->ci05_atendquestaudit   = $oQuestao->bAtendeQuest;
                    $cllancamverifaudit->ci05_achados           = $oQuestao->bAtendeQuest == "t" ? 'null' : addslashes(db_stdClass::normalizeStringJson($oParam->sAchados));

                    $cllancamverifaudit->alterar($oQuestao->iCodLan);

                    if ($cllancamverifaudit->erro_status == "0") {
                        throw new Exception("Erro ao alterar o lan�amento. \n".$cllancamverifaudit->erro_sql, null);
                    }

                } else {

                    $cllancamverifaudit = new cl_lancamverifaudit;
                    $cllancamverifaudit->ci05_codproc           = $oQuestao->iCodProc;
                    $cllancamverifaudit->ci05_codquestao        = $oQuestao->iCodQuestao;
                    $cllancamverifaudit->ci05_inianalise_dia    = $oQuestao->dtDataIniDia;
                    $cllancamverifaudit->ci05_inianalise_mes    = $oQuestao->dtDataIniMes;
                    $cllancamverifaudit->ci05_inianalise_ano    = $oQuestao->dtDataIniAno;
                    $cllancamverifaudit->ci05_atendquestaudit   = $oQuestao->bAtendeQuest;
                    $cllancamverifaudit->ci05_achados           = $oQuestao->sAchado;
                    $cllancamverifaudit->ci05_instit            = $iInstit;

                    $cllancamverifaudit->incluir();

                    if ($cllancamverifaudit->erro_status == "0") {
                        throw new Exception("Erro ao salvar lan�amento. \n".$cllancamverifaudit->erro_sql, null);
                    }

                }

                if ($oParam->bExcluiMatriz) {
                    
                    $aCodLanExcluir = explode(",", $oParam->sCodLanExcluir);

                    foreach ($aCodLanExcluir as $iCodLan) {

                        $sSqlExcluiMatriz = $clmatrizachadosaudit->sql_query(null, "*", null, "ci06_codlan = {$iCodLan}");
                        $clmatrizachadosaudit->sql_record($sSqlExcluiMatriz);
                        
                        if ($clmatrizachadosaudit->numrows > 0) {
                            
                            $clmatrizachadosaudit->excluir(null, "ci06_codlan = {$iCodLan}");

                            if ($clmatrizachadosaudit->erro_status == "0") {
                                throw new Exception("Erro ao excluir matriz de achados. \n".$clmatrizachadosaudit->erro_msg, null);
                            }

                        }                        

                    }

                }

            }
            
            $oRetorno->iFiltroQuestoes = $oParam->iFiltroQuestoes;
            $oRetorno->sMensagem = "Lan�amentos salvos com sucesso!"; 

        break;

        case "excluiLancamento":

            db_inicio_transacao();

            $aItens = explode(",", $oParam->sItens);

            foreach ($aItens as $iItem) {

                $cllancamverifaudit = new cl_lancamverifaudit;
                    
                $sSql = $cllancamverifaudit->sql_query(null, "*", null, "ci05_codlan = {$iItem}");
                $cllancamverifaudit->sql_record($sSql);

                if ($cllancamverifaudit->numrows > 0) {
                    
                    if ($oParam->bExcluiMatriz) {
                        
                        $clmatrizachadosaudit->excluir(null, "ci06_codlan = {$iItem}");

                        if ($clmatrizachadosaudit->erro_status == "0") {
                            throw new Exception("Erro ao excluir matriz de achados. \n".$clmatrizachadosaudit->erro_msg, null);
                        }

                    }

                    $cllancamverifaudit->excluir($iItem);

                    if ($cllancamverifaudit->erro_status == "0") {
                        throw new Exception("Erro ao limpar lan�amentos. \n".$cllancamverifaudit->erro_sql, null);
                    }

                }

                $oRetorno->iFiltroQuestoes = $oParam->iFiltroQuestoes;
                $oRetorno->sMensagem = "Lan�amentos exclu�dos com sucesso!"; 

            }

        break;

        case "verificaQuestaoMatriz":

            $sCodsLan = implode(",", $oParam->aItens);

            $sWhere = " ci05_codlan in ({$sCodsLan}) AND ci06_seq IS NOT NULL";

            $sSql = $clquestaoaudit->sql_questao_matriz(null, "*", "ci02_numquestao", $sWhere);
            $clquestaoaudit->sql_record($sSql);
            
            if ($clquestaoaudit->numrows > 0) {
                $oRetorno->bExisteMatriz = true;
            } else {
                $oRetorno->bExisteMatriz = false;
            }

            $oRetorno->sCodsLan = $sCodsLan;

        break;

    }

    db_fim_transacao($sqlerro);
    $oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
    echo $oJson->encode($oRetorno);


} catch (Exception $e) {

    db_fim_transacao($sqlerro);
    $oRetorno->sMensagem    = urlencode($e->getMessage());
    $oRetorno->status       = 2;
    echo $oJson->encode($oRetorno);

}
    