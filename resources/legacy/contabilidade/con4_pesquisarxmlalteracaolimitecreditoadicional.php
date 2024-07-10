<?php
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

$sArquivo = "legacy_config/sicom/".db_getsession("DB_anousu")."/{$sCnpj}_sicomalteracaolimitecreditoadicional.xml";

$oDOMDocument = new DOMDocument();

$sTextoXml    = file_get_contents($sArquivo);
$oDOMDocument->loadXML($sTextoXml);
$oDOMDocument->formatOutput = true;

$oDados = $oDOMDocument->getElementsByTagName('alteracaolimitecreditoadicional');

/**
 * caso tenha passado um dos dois codigos para ser pesquisado
 */
if ($_POST['codigo1'] || $_POST['codigo2']) {

	/**
	 * percorrer dados do xml para passar para o objeto para ser adicionado ao array
	 */
  foreach ($oDados as $oRow) {

  	/**
     * selecionando linha conforme o codigo passado
     */
  	if ($oRow->getAttribute("codigo") == $_POST['codigo1'] || $oRow->getAttribute("nroLeiAlterOrcam") == $_POST['codigo2']) {

  	  $oValores = new stdClass();
		  $oValores->codigo                  = $oRow->getAttribute("codigo");
		  $oValores->nroLeiAlterOrcam      	 = $oRow->getAttribute("nroLeiAlterOrcam");
		  $oValores->dataLeiAlterOrcam       = $oRow->getAttribute("dataLeiAlterOrcam");
		  $oValores->artigoLeiAlterOrcamento = $oRow->getAttribute("artigoLeiAlterOrcamento");
		  $oValores->descricaoArtigo         = $oRow->getAttribute("descricaoArtigo");
		  $oValores->NovoPercentual          = $oRow->getAttribute("NovoPercentual");
		  $aValores[] = $oValores;

  	}

  }

} else {

  /**
   * percorrer os dados do xml para passar para o objeto e ser adicionado ao array
   */
  foreach ($oDados as $oRow) {

    $oValores = new stdClass();
		$oValores->codigo                  = $oRow->getAttribute("codigo");
		$oValores->nroLeiAlterOrcam      	 = $oRow->getAttribute("nroLeiAlterOrcam");
		$oValores->dataLeiAlterOrcam       = $oRow->getAttribute("dataLeiAlterOrcam");
		$oValores->artigoLeiAlterOrcamento = $oRow->getAttribute("artigoLeiAlterOrcamento");
		$oValores->descricaoArtigo         = $oRow->getAttribute("descricaoArtigo");
		$oValores->NovoPercentual          = $oRow->getAttribute("NovoPercentual");
		$aValores[] = $oValores;

  }

}
echo json_encode($aValores);