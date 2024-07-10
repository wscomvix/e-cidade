<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_credenciamentotermo_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcredenciamentotermo = new cl_credenciamentotermo;
$db_botao = false;
$db_opcao = 33;
if (isset($excluir)) {
  db_inicio_transacao();
  $db_opcao = 3;
  $clcredenciamentotermo->excluir($l212_sequencial);
  db_fim_transacao();
} else if (isset($chavepesquisa)) {
  $db_opcao = 3;
  $result = $clcredenciamentotermo->sql_record($clcredenciamentotermo->sql_query($chavepesquisa));
  db_fieldsmemory($result, 0);
  $db_botao = true;
}
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css" />
  <script type="text/javascript" src="scripts/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?
  db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js, DBHint.widget.js");
  db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js,dbtextFieldData.widget.js");
  db_app::load("time.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <?
  include("forms/db_frmcredenciamentotermo.php");
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<?
if (isset($excluir)) {
  if ($clcredenciamentotermo->erro_status == "0") {
    $clcredenciamentotermo->erro(true, false);
  } else {
    $clcredenciamentotermo->erro(true, true);
  }
}
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>