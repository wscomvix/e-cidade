<?
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$sSql  = "SELECT * FROM db_config ";
$sSql .= "	WHERE prefeitura = 't'";

$rsInst = db_query($sSql);
$sCnpj  = db_utils::fieldsMemory($rsInst, 0)->cgc;
$sArquivo = "legacy_config/sicom/".db_getsession("DB_anousu")."/{$sCnpj}_sicomcadveiculos.xml";

/*
 * inserir ou atualizar registro do xml
 */
if (isset($_POST['btnSalvar'])){

	if (!file_exists($sArquivo)) {

    $oDOMDocument = new DOMDocument('1.0','ISO-8859-1');
    $oRoot  = $oDOMDocument->createElement('cadveiculos');

  }else{

  	$oDOMDocument = new DOMDocument();
  	$sTextoXml    = file_get_contents($sArquivo);
    $oDOMDocument->loadXML($sTextoXml);
    $oRoot  = $oDOMDocument->documentElement;

  }

  $oDOMDocument->formatOutput = true;

  $oDados      = $oDOMDocument->getElementsByTagName('cadveiculo');

  /**
   * caso o codigo j� exista no xml ir� atualizar o registro
   */


  $aCaracteres = array('\"', "\'");
  $_POST['nomeEstabelecimento'] = str_replace($aCaracteres, "", $_POST['nomeEstabelecimento']);
  $_POST['nomeEstabelecimento'] = utf8_encode($_POST['nomeEstabelecimento']);

  $_POST['localidade'] = str_replace($aCaracteres, "", $_POST['localidade']);
  $_POST['localidade'] = utf8_encode($_POST['localidade']);

  foreach ($oDados as $oRow) {

  		$iUltimoCodigo = $oRow->getAttribute("codigo");
		if ($oRow->getAttribute("codigo") == $_POST['codigo']) {

			$oDado = new stdClass();
			$oDado = $oRow;
			unset($_POST['btnSalvar']);
			$oDado->setAttribute("instituicao", db_getsession("DB_instit"));

  	  /**
  	   * passar os valores para o objeto para ser salvo no xml
  	   */

		  $aCaracteres = array('\"', "\'");
  		  $_POST['nomRespParecer'] = str_replace($aCaracteres, "", $_POST['nomRespParecer']);
  		  $_POST['nomRespParecer'] = utf8_encode($_POST['nomRespParecer']);

  		  $_POST['logradouro'] = str_replace($aCaracteres, "", $_POST['logradouro']);
  		  $_POST['logradouro'] = utf8_encode($_POST['logradouro']);

		  foreach ($_POST as $coll => $value) {
			  $oDado->setAttribute($coll, $value);
		  }
		  $oDOMDocument->save($sArquivo);
		  if (filesize($sArquivo) > filesize("legacy_config/sicom/".db_getsession("DB_anousu")."/backup_{$sCnpj}_sicomcadveiculos.xml")/2) {
		    system("cp $sArquivo legacy_config/sicom/".db_getsession("DB_anousu")."/backup_{$sCnpj}_sicomcadveiculos.xml");
		  }
			echo"
			<script LANGUAGE=\"Javascript\">
			alert(\"Seu cadastro foi realizado com sucesso.\");
			</SCRIPT>";
			break;
    }

  }
  if (!$oDado) {

  	unset($_POST['btnSalvar']);

  	$oDado  = $oDOMDocument->createElement('cadveiculo');

  	$oDado->setAttribute("instituicao", db_getsession("DB_instit"));


  	/**
  	 * passar os valores para o objeto para ser salvo no xml
  	 */
	  foreach ($_POST as $coll => $value) {
		  $oDado->setAttribute($coll, $value);
	  }
	  $oDado->setAttribute("codigo", $iUltimoCodigo+1);

	  if (!file_exists($sArquivo)) {

	  	$oRoot->appendChild($oDado);
	    $oDOMDocument->appendChild($oRoot);

	  } else {
	  	$oDado = $oRoot->appendChild($oDado);
	  }

	  $oDOMDocument->save($sArquivo);
    if (filesize($sArquivo) > filesize("legacy_config/sicom/".db_getsession("DB_anousu")."/backup_{$sCnpj}_sicomcadveiculos.xml")/2) {
		  system("cp $sArquivo legacy_config/sicom/".db_getsession("DB_anousu")."/backup_{$sCnpj}_sicomcadveiculos.xml");
		}
		echo"
		<script LANGUAGE=\"Javascript\">
		alert(\"Seu cadastro foi realizado com sucesso.\");
		</SCRIPT>";

  }

}

/*
 * remover um registro do xml
 */
if (isset($_POST['btnExcluir'])) {

	if (!file_exists($sArquivo)) {
    $oDOMDocument = new DOMDocument('1.0','ISO-8859-1');
  }else{
  	$oDOMDocument = new DOMDocument();
  }

 	$sTextoXml    = file_get_contents($sArquivo);
  $oDOMDocument->loadXML($sTextoXml);
  $oDOMDocument->formatOutput = true;
	$oDocument = $oDOMDocument->documentElement;
  $oDados      = $oDOMDocument->getElementsByTagName('cadveiculo');

  /**
   * encontrar o codigo selecionado para excluir o registro no xml
   */
  foreach ($oDados as $oRow) {

		if ($oRow->getAttribute("codigo") == $_POST['codigo']) {

		  $oDocument->removeChild($oRow);
		  $oDOMDocument->save($sArquivo);
		  system("cp $sArquivo legacy_config/sicom/".db_getsession("DB_anousu")."/backup_{$sCnpj}_sicomcadveiculos.xml");
			echo"
			<script LANGUAGE=\"Javascript\">
			alert(\"Registro removido com sucesso.\");
			</SCRIPT>";
			break;

    }

  }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
    <center>
	<?
	include("forms/frmsicomcadveiculos.php");
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
