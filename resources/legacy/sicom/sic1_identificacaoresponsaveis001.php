<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_identificacaoresponsaveis_classe.php");
include("dbforms/db_funcoes.php");
if (db_getsession("DB_anousu") < 2014 && db_getsession("DB_modulo") != 952) {
echo "<script>
                                   CurrentWindow.corpo.location.href='con4_sicomidentresponsavel.php';
                                </script>";
                          exit;
}
db_postmemory($HTTP_POST_VARS);
$clidentificacaoresponsaveis = new cl_identificacaoresponsaveis;
$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;
if(isset($incluir)){
    db_inicio_transacao();
    $result_dtcadcgm = db_query("select z09_datacadastro from historicocgm where z09_numcgm = {$si166_numcgm} and z09_tipo = 1");
    db_fieldsmemory($result_dtcadcgm, 0);
    $z09_datacadastro = (implode("/",(array_reverse(explode("-",$z09_datacadastro)))));
    if($sqlerro==false){
        $dtinicial = DateTime::createFromFormat('d/m/Y', $si166_dataini);
        $dtcadastrocgm =   DateTime::createFromFormat('d/m/Y', $z09_datacadastro);

        if($dtinicial < $dtcadastrocgm && db_getsession("DB_modulo") != 952) {
            db_msgbox("Usu�rio: A data de cadastro do CGM informado � superior a data do procedimento que est� sendo realizado. Corrija a data de cadastro do CGM e tente novamente!");
            $sqlerro = true;
        }
    }
    if($sqlerro==false) {
        $clidentificacaoresponsaveis->incluir($si166_sequencial);
    }
    db_fim_transacao();
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
	<?
	include("forms/db_frmidentificacaoresponsaveis.php");
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
<script>
js_tabulacaoforms("form1","si166_numcgm",true,1,"si166_numcgm",true);
</script>
<?
if(isset($incluir)){
  if($clidentificacaoresponsaveis->erro_status=="0"){
    $clidentificacaoresponsaveis->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clidentificacaoresponsaveis->erro_campo!=""){
      echo "<script> document.form1.".$clidentificacaoresponsaveis->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clidentificacaoresponsaveis->erro_campo.".focus();</script>";
    }
  }else{
    $clidentificacaoresponsaveis->erro(true,true);
  }
}
?>
