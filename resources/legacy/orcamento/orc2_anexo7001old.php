<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  jan = window.open('orc2_anexo7002.php?&tipo_nivel='+document.form1.tipo_nivel.value+'&tipo_agrupa='+document.form1.tipo_agrupa.value+'&tipo_impressao='+document.form1.origem.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" title="Origem dos dados a serem gerados no relat�rio."><strong>Origem dos dados :</strong></td>
        <td align="left">
          <?
            $x = array("1"=>"Or�amento","2"=>"Balan�o");
            db_select('origem',$x,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td align="right" ><strong>Desdobrar :</strong></td>
        <td align="left">
          <?
            $y = array("6"=>"Recurso","1"=>"Fun��o","2"=>"Subfun��o","3"=>"Programa","4"=>"Proj/Ativ","5"=>"Elemento");
            db_select('tipo_nivel',$y,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td align="right" ><strong>Agrupar Por :</strong></td>
        <td align="left">
          <?
            $z = array("1"=>"Geral","2"=>"�rg�o","3"=>"Unidade");
            db_select('tipo_agrupa',$z,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>