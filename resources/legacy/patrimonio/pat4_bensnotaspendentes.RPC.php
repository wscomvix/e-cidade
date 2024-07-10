<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("model/patrimonio/depreciacao/BemDepreciacao.model.php");

$oDaoNotaItemBensPendentes = db_utils::getDao('empnotaitembenspendente');
$oDaoBensEmpNotaItem       = db_utils::getDao('bensempnotaitem');

$oPost = db_utils::postMemory($_POST);

$oJson            = new services_json();
$oParam           = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno         = new stdClass();
$oRetorno->status = 1;

switch ($oParam->exec) {

  case "getNotasPendentes":

    $dtImplantacaoDepreciacao = BemDepreciacao::retornaDataImplantacaoDepreciacao(db_getsession("DB_instit"));

    $oRetorno->aNotasPendentes = array();
    $sCamposBensPendentes  = "distinct ";
    $sCamposBensPendentes .= "m52_codordem as codigonota,";
    $sCamposBensPendentes .= "e69_numemp as numeroempenho,";
    $sCamposBensPendentes .= "e69_numero as notafiscal,";
    $sCamposBensPendentes .= "o56_elemento as desdobramento,";
    $sCamposBensPendentes .= "e72_qtd as quantidade,";
    $sCamposBensPendentes .= "pc01_descrmater as descricao,";
    $sCamposBensPendentes .= "e137_sequencial,";
    $sCamposBensPendentes .= "e72_sequencial as codigoitemnota,";
    $sCamposBensPendentes .= "e60_codemp as codigoempenho,";
    $sCamposBensPendentes .= "e72_valor as valornota,";
    $sCamposBensPendentes .= "e60_anousu as anoempenho";

    $sWhereBensPendentes   = "     bensempnotaitem.e136_empnotaitem is null";

    /**
     * Conforme acertado com Henrique, nao mostrar notas liquidadas
     */
    $sWhereBensPendentes  .= " and empnotaitem.e72_vlrliq = 0 ";
    /**
     *  Trecho comentado segundo conversa com o colaborador Danilo, para permitir a inclus�o de Bens ap�s a data da Implanta��o
     *  HOTFIX_inclusaobens
     */
    // $sWhereBensPendentes  .= " and empempenho.e60_anousu >= ".date('Y',strtotime($dtImplantacaoDepreciacao));
    if (!empty($dtImplantacaoDepreciacao)) {

      // $sWhereBensPendentes  .= " and empempenho.e60_emiss >= '{$dtImplantacaoDepreciacao}' ";

      if (!USE_PCASP) {
        $sWhereBensPendentes  .= "  and o56_elemento in (select distinct e135_desdobramento from configuracaodesdobramentopatrimonio)";
      }
    }

    $iInstituicaoSessao = db_getsession("DB_instit");
    if (USE_PCASP) {

      $sNomeTabelaPlanoConta      = "conplanoorcamento";
      $sNomeTabelaGrupoPlanoConta = "conplanoorcamentogrupo";
      $sWhereBensPendentes  .= " and exists ( select 1
                                                from orcelemento oe
                                                     inner join {$sNomeTabelaPlanoConta}      on {$sNomeTabelaPlanoConta}.c60_codcon = oe.o56_codele
                                                                                             and {$sNomeTabelaPlanoConta}.c60_anousu = oe.o56_anousu
                                                     inner join {$sNomeTabelaGrupoPlanoConta} on {$sNomeTabelaGrupoPlanoConta}.c21_codcon = {$sNomeTabelaPlanoConta}.c60_codcon
                                                                                             and {$sNomeTabelaGrupoPlanoConta}.c21_anousu = {$sNomeTabelaPlanoConta}.c60_anousu
                                                                                             and {$sNomeTabelaGrupoPlanoConta}.c21_instit = {$iInstituicaoSessao}
                                               where {$sNomeTabelaGrupoPlanoConta}.c21_congrupo = 9
                                                 and oe.o56_codele = orcelemento.o56_codele
                                                 and oe.o56_anousu = orcelemento.o56_anousu )";
    }

    $sWhereBensPendentes .= " and e60_instit = {$iInstituicaoSessao} ";
    $sWhereBensPendentes .= " and m53_codordem is null ";
    $sWhereBensPendentes .= " and e70_vlranu = 0 ";

    $sSqlDadosBensPendentes = $oDaoNotaItemBensPendentes->sql_query_bens_nota(null, $sCamposBensPendentes, null, $sWhereBensPendentes);
    $sSqlBensPendentes      = "select *                                                ";
    $sSqlBensPendentes     .= "  from ($sSqlDadosBensPendentes) as xx                  ";
    $sSqlBensPendentes     .= " order by anoempenho, codigoempenho::int                ";
    $rsBensPendentes        = $oDaoNotaItemBensPendentes->sql_record($sSqlBensPendentes);
    if ($oDaoNotaItemBensPendentes->numrows > 0) {
      $oRetorno->aNotasPendentes = db_utils::getCollectionByRecord($rsBensPendentes, false, false, true);
    }

    break;

  case "getBensPorCodigoNota":

    $oRetorno->aNotasPendentes = array();
    $sCamposBensNotaPendentes  = "distinct e69_codnota as codigonota,";
    $sCamposBensNotaPendentes .= "         e69_numemp as numeroempenho,";
    $sCamposBensNotaPendentes .= "e69_numero as notafiscal,";
    $sCamposBensNotaPendentes .= "         o56_elemento as desdobramento,";
    $sCamposBensNotaPendentes .= "         e72_qtd as quantidade,";
    $sCamposBensNotaPendentes .= "         pc01_descrmater as descricao,";
    $sCamposBensNotaPendentes .= "         0 as e137_sequencial,";
    $sCamposBensNotaPendentes .= "         e72_valor as valornota,";
    $sCamposBensNotaPendentes .= "         e60_codemp as codigoempenho,";
    $sCamposBensNotaPendentes .= "         e60_anousu as anoempenho,";
    $sCamposBensNotaPendentes .= "         e72_sequencial as codigoitemnota";
    $sWhereBensNotaPendente    = "e69_codnota in (" . implode(",", $oParam->aCodigoNota) . ")";
    $sSqlBensNotaPendente      = $oDaoNotaItemBensPendentes->sql_query_bens(null, $sCamposBensNotaPendentes, null, $sWhereBensNotaPendente);
    $rsBensNotaPendente        = $oDaoNotaItemBensPendentes->sql_record($sSqlBensNotaPendente);
    if ($oDaoNotaItemBensPendentes->numrows > 0) {
      $oRetorno->aNotasPendentes = db_utils::getCollectionByRecord($rsBensNotaPendente, false, false, true);
    }
    break;
}
echo $oJson->encode($oRetorno);
