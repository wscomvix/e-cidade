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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_utils.php");
include("classes/db_orcprojeto_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcsuplemtipo_classe.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_ppaleidadocomplementar_classe.php");
include("classes/db_orcsuplementacaoparametro_classe.php");
$clorcsuplemtipo = new cl_orcsuplemtipo;
$clorcsuplem = new cl_orcsuplem;

db_postmemory($HTTP_POST_VARS);
$clorcprojeto = new cl_orcprojeto;

$clppaleidadocomplementar 	= new cl_ppaleidadocomplementar;
$sSqlAnoInicio 		= $clppaleidadocomplementar->sql_query_file(null, "o142_anoinicialppa", "o142_sequencial DESC LIMIT 1");
$anoInicio = db_utils::fieldsmemory(db_query($sSqlAnoInicio))->o142_anoinicialppa;
$anoSessao =  db_getsession('DB_anousu');
$anoReferencia = $anoSessao - intval($anoInicio);
$sCampos =  returnFieldNameModalidadeAplic($anoReferencia);
$sSqlModalidadeAplic 		= $clppaleidadocomplementar->sql_query_file(null,$sCampos, "o142_sequencial DESC LIMIT 1");
$bModalidadeAplic 			= db_utils::fieldsmemory(db_query($sSqlModalidadeAplic))->$sCampos;

$clorcsuplementacaoparametro = new cl_orcsuplementacaoparametro;
$sqlsuplementacaoparametro  = $clorcsuplementacaoparametro->sql_query(db_getsession('DB_anousu'),"*","");
$suplementacaoparametro  			= db_utils::fieldsmemory(db_query($sqlsuplementacaoparametro));

$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $lErro = false;
  // inclui texto padr�o
  if($o39_tiposuplementacao == 1004 || $o39_tiposuplementacao == 1009 ||$o39_tiposuplementacao == 1025 || $o39_tiposuplementacao == 1027 ||$o39_tiposuplementacao == 1029){
      $o39_texto = "Art 2. -  Para cobertura do Cr�dito aberto de acordo com o Art 1.,";
      $o39_texto.= " ser� usado como recurso o excesso de arrecada��o na fonte:   ";
  }
  elseif($o39_tiposuplementacao == 1003 || $o39_tiposuplementacao == 1008 ||$o39_tiposuplementacao == 1028 || $o39_tiposuplementacao == 2026 ){
    $o39_texto = "Art 2. -  Para cobertura do Cr�dito aberto de acordo com o Art 1.,";
    $o39_texto.= "  ser� usado como recurso o Super�vit Financeiro apurado no Balan�o Patrimonial anterior:   ";
  }
  elseif($o39_tiposuplementacao == 1011){
    $o39_texto = "Art. 2� - Fica o servi�o de contabilidade autorizado a promover as adequa��es necess�rias na Lei Or�ament�ria Municipal";
    $o39_texto.= "  e no Plano Plurianual vigente, com a respectiva a��o.   ";
  }
  else{
    $o39_texto = "Art 2. -  Para cobertura do Cr�dito aberto de acordo com o Art 1.,"; 
    $o39_texto.= " ser� usado como recurso as seguintes redu��es or�ament�rias: ";
  }
  if ($o39_usalimite == '0') {

    $clorcprojeto->erro_status = 0;
    $clorcprojeto->erro_campo  = 'o39_usalimite';
    $clorcprojeto->erro_msg    = "Usu�rio:\\nDecreto n�o incluido.\\nInforme se o projeto deve usar o limite da LOA";
    $lErro  = true;

  } else {

    $clorcprojeto->o39_tipoproj = '1';
    $clorcprojeto->o39_texto = $o39_texto;
    $clorcprojeto->o39_tiposuplementacao = $o39_tiposuplementacao;
    $clorcprojeto->incluir($o39_codproj);

  }

  db_fim_transacao($lErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<table width="1000" border="0" style="margin:auto;" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmorcprojeto.php");
	?>
    </center>
	</td>
  </tr>
</table>

</body>
</html>
<?
if(isset($incluir)){
  if($clorcprojeto->erro_status=="0"){
    $clorcprojeto->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcprojeto->erro_campo!=""){
      echo "<script> document.form1.".$clorcprojeto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcprojeto->erro_campo.".focus();</script>";
      echo "<script> document.getElementById('o39_tiposuplementacao').value = $o39_tiposuplementacao;</script>";
      echo "<script> document.getElementById('o39_tiposuplementacaodescr').value = $o39_tiposuplementacaodescr;</script>";
      if ($o39_tiposuplementacao == 1001 || $o39_tiposuplementacao == 1002 || $o39_tiposuplementacao == 1003 || $o39_tiposuplementacao == 1004) {
        
        echo "<script> document.getElementById('o39_usalimite').value = 't';</script>";
        echo "<script> document.getElementById('o39_usalimite_select_descr').value = 'Sim';</script>";
  
      } else {
  
        echo "<script> document.getElementById('o39_usalimite').value = 'f';</script>";
        echo "<script> document.getElementById('o39_usalimite_select_descr').value = 'N�o';</script>";
  
      }
      echo "<script> document.getElementById('o39_usalimite_select_descr').value = $o39_usalimite_select_descr;</script>";
      echo "<script>alert($o39_usalimite); </script>";
      
    };
  }else{
    $clorcprojeto->erro(true,false);
    echo "<script>
           // parent.mo_camada('emissao');
           parent.location.href = 'orc1_orcsuplem001.php?chavepesquisa={$clorcprojeto->o39_codproj}';\n
          </script>
         ";

  };
};
function returnFieldNameModalidadeAplic($anoReferencia)
{
  switch($anoReferencia){
    case 0:
      return  "o142_orcmodalidadeaplic";
    case 1:
      return  "o142_orcmodalidadeaplicano2";
    case 2:
      return  "o142_orcmodalidadeaplicano3"; 
    case 3:
      return  "o142_orcmodalidadeaplicano4";             
  }
}
?>
