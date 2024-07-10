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
include("classes/db_caracter_classe.php");
include("classes/db_carpadrao_classe.php");
include("dbforms/db_funcoes.php");
$clcaracter = new cl_caracter;
$clcarpadrao = new cl_carpadrao;
$db_opcao = 2;
$db_botao = false;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
   db_inicio_transacao();
   $clcaracter->alterar($j31_codigo);
   if($j33_codcaracter != $padrao){
     if($j33_codcaracter == "S"){
       $resu = $clcarpadrao->sql_record($clcarpadrao->sql_query($j31_grupo)); 
       if($clcarpadrao->numrows==0){
         $clcarpadrao->j33_codgrupo = $j31_grupo;
         $clcarpadrao->j33_codcaracter = $clcaracter->j31_codigo;
         $clcarpadrao->incluir($j31_grupo);
       }else{
         $clcarpadrao->j33_codgrupo = $j31_grupo;
         $clcarpadrao->j33_codcaracter = $clcaracter->j31_codigo;
         $clcarpadrao->alterar($j31_grupo);
       }	
     }else{
         $clcarpadrao->j33_codgrupo = $j31_grupo;
         $clcarpadrao->excluir($j31_grupo);
     }
   }
   db_fim_transacao();
   $padrao = $j33_codcaracter;
}else if(isset($chavepesquisa)){
   $result = $clcaracter->sql_record($clcaracter->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $clcarpadrao->sql_record($clcarpadrao->sql_query($j31_grupo,"j33_codcaracter","","j33_codcaracter=$j31_codigo")); 
   if($clcarpadrao->numrows==0){
     $j33_codcaracter="N";
   }else{
     $j33_codcaracter="S";
   }
   $padrao = $j33_codcaracter;
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_load_caracter(){
  <?
    if(!isset($chavepesquisa)){
       echo "js_pesquisa()";

    }


  ?>
}
</script>



</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_load_caracter();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
	<?
	include("forms/db_frmcaracter.php");
	?>
	</center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clcaracter->erro_status=="0"){
    $clcaracter->erro(true,false);
    if($clcaracter->erro_campo!=""){
	echo "<script> document.form1.".$clcaracter->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaracter->erro_campo.".focus();</script>";
    }
  }else{
     $clcaracter->erro(true,true);
  }
}
?>