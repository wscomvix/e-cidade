<?
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoHistorico = db_utils::getdao("historico");
$oDaoAlunoCurso = db_utils::getdao("alunocurso");
$db_botao      = false;
$db_opcao      = 33;

if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoHistorico->excluir($ed61_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao      = 3;
  $sSqlHistorico = $oDaoHistorico->sql_query($chavepesquisa);
  $rsHistorico   = $oDaoHistorico->sql_record($sSqlHistorico); 
  db_fieldsmemory($rsHistorico,0);
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
 </head>
 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
 </table>
 <table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
   <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
     <?include("forms/db_frmhistorico.php");?>
    </center>
   </td>
  </tr>
 </table>
 <?
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
           db_getsession("DB_anousu"),db_getsession("DB_instit")
          );
 ?>
</body>
</html>
<?
if (isset($excluir)) {
	
  if ($oDaoHistorico->erro_status == "0") {
    $oDaoHistorico->erro(true,false);
  } else {
    $oDaoHistorico->erro(true,true);
  }
  
}

if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>