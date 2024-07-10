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
include("classes/db_orcprojeto_classe.php");
include("classes/db_orcprojlan_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_orcsuplemtipo_classe.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_ppaleidadocomplementar_classe.php");

include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clppaleidadocomplementar 	= new cl_ppaleidadocomplementar;
$sSqlModalidadeAplic 		= $clppaleidadocomplementar->sql_query_file(null, "o142_orcmodalidadeaplic", "o142_sequencial DESC LIMIT 1");
$bModalidadeAplic 			= db_utils::fieldsmemory(db_query($sSqlModalidadeAplic))->o142_orcmodalidadeaplic;

$clorcprojeto = new cl_orcprojeto;
$clorcprojlan = new cl_orcprojlan;
$cldbusuarios = new cl_db_usuarios;
$clorcsuplem = new cl_orcsuplem;
$clorcsuplemtipo = new cl_orcsuplemtipo;


$clorcprojeto->rotulo->label();


$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){

  db_inicio_transacao();
  $db_opcao = 2;
  $db_botao = true;
  
  if(isset($o39_tiposuplementacao)){
    $clorcprojeto->o39_tiposuplementacao = $o39_tiposuplementacao;
         
  }
  if(isset($o39_texto)){
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
    $clorcprojeto->o39_texto = $o39_texto;
         
  }

  $clorcprojeto->alterar($o39_codproj);
  db_fim_transacao();

  echo "<script>
  parent.iframe_emissao.location.href='orc1_orcprojeto012.php?o39_codproj=$o39_codproj&db_opcao=$db_opcao';
  </script>
  ";

}else if(isset($chavepesquisa) && $chavepesquisa!=""){
   $db_opcao = 2;
   $result = $clorcprojeto->sql_record($clorcprojeto->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $rr = $clorcprojlan->sql_record($clorcprojlan->sql_query_file($chavepesquisa));
   if ($clorcprojlan->numrows > 0){
     db_fieldsmemory($rr,0);
     $rr = $cldbusuarios->sql_record($cldbusuarios->sql_query_file($o51_id_usuario));
     if ($cldbusuarios->numrows > 0){
        db_fieldsmemory($rr,0);
     }
   }
   $db_botao = true;
   echo "<script>
         parent.iframe_emissao.location.href='orc1_orcprojeto012.php?o39_codproj=$o39_codproj&db_opcao=$db_opcao';
         </script>
         ";

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

<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin:auto;">
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
if(isset($alterar)){
  if($clorcprojeto->erro_status=="0"){
    $clorcprojeto->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcprojeto->erro_campo!=""){
      echo "<script> document.form1.".$clorcprojeto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcprojeto->erro_campo.".focus();</script>";
    };
  }else{
    $clorcprojeto->erro(true,false);

  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
