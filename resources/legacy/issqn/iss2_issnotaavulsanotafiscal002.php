<?
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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_issnotaavulsaservico_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_parissqnviasnotaavulsavias_classe.php");
include("classes/db_issnotaavulsacanc_classe.php");

$clissnotaavulsaservico       = new cl_issnotaavulsaservico();
$clissnotaavulsa              = new cl_issnotaavulsa();
$clissnotaavulsatomador       = new cl_issnotaavulsatomador();
$clparissqnviasnotaavulsavias = new cl_parissqnviasnotaavulsavias();
$clissnotaavulsacanc = new cl_issnotaavulsacanc();
$oDaoParIssqn                 = db_utils::getDao("parissqn");

$oInstit = new Instituicao(db_getsession('DB_instit'));

$pdf   = new scpdf();
$pdf->open();

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
$oInst = db_utils::fieldsmemory($resultpref,0);
$get   = db_utils::postmemory($_GET);

//dados do servico
$rsServico = $clissnotaavulsaservico->sql_record($clissnotaavulsaservico->sql_query(null,"*","q62_sequencial",
    "q62_issnotaavulsa = ".$get->q51_sequencial));
$hasItemServico = empty(db_utils::fieldsMemory($rsServico,0)->q62_issgruposervico);

if($hasItemServico) {
    $pdf1 = new db_impcarne($pdf, '49');
} else {
    $pdf1 = new db_impcarne($pdf, '492');
}
$rsParIssqn = $oDaoParIssqn->sql_record($oDaoParIssqn->sql_query(null,"q60_notaavulsalinkautenticacao"));
$pdf1->urlautenticacao = db_utils::fieldsMemory($rsParIssqn,0)->q60_notaavulsalinkautenticacao;
$pdf1->rsConfig = $clparissqnviasnotaavulsavias->sql_record($clparissqnviasnotaavulsavias->sql_query());
$pdf1->prefeitura     = $oInst->nomeinst;
$pdf1->enderpref      = $oInst->ender.", ".$oInst->numero;
$pdf1->cgcpref        = $oInst->cgc;
$pdf1->municpref      = $oInst->munic;
$pdf1->telefpref      = $oInst->telef;
$pdf1->emailpref      = $oInst->email;
$pdf1->logo			  = $oInst->logo;

//dados do prestador
$rsPrest = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query($get->q51_sequencial));
$oPrest = db_utils::fieldsMemory($rsPrest,0);
$pdf1->dadosPrestador = $oPrest;
//dados do tomador
$sSQLTomador = $clissnotaavulsatomador->sql_query_tomador($get->q51_sequencial);
$rsTom     = $clissnotaavulsatomador->sql_record($sSQLTomador);
//dados do servico
$rsServico = $clissnotaavulsaservico->sql_record($clissnotaavulsaservico->sql_query(null,"*","q62_sequencial",
                                              "q62_issnotaavulsa = ".$get->q51_sequencial));
$pdf1->qteServicos = $clissnotaavulsaservico->numrows;
$pdf1->rsServico   = $rsServico;
$pdf1->dadosTomador = db_utils::fieldsMemory($rsTom,0);
$rsNotaCancelada = $clissnotaavulsacanc->sql_record($clissnotaavulsacanc->sql_query(null,"q63_sequencial",null,"q63_issnotaavulsa = ".$get->q51_sequencial));
$pdf1->notaCancelada = empty(db_utils::fieldsMemory($rsNotaCancelada,0)->q63_sequencial) ? false : true;

$codInstituicao = array(Instituicao::COD_CLI_PMMONTALVANIA, Instituicao::COD_CLI_PMJURAMENTO);
if (in_array($oInstit->getCodigoCliente(), $codInstituicao)){
    $pdf1->texto_aviso = "";
}else{    
    $pdf1->texto_aviso = "Acesse {$pdf1->urlautenticacao} e utilize o c�digo de verifica��o {$pdf1->dadosPrestador->q51_codautent} para confirmar a autenticidade.";
}

$pdf1->imprime();
$pdf1->objpdf->Output();
?>
