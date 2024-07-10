<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_questaoaudit_classe.php");
include("classes/db_processoaudit_classe.php");
include("classes/db_lancamverifaudit_classe.php");
include("classes/db_matrizachadosaudit_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clquestaoaudit = new cl_questaoaudit;
$db_opcao = 22;
$db_botao = false;
$sqlerro = false;
$ci02_codtipo = $ci01_codtipo;

if (isset($incluir)) {

	$sSqlVerifica = $clquestaoaudit->sql_query(null, "*", null, "ci02_numquestao = {$ci02_numquestao} AND ci02_codtipo = {$ci02_codtipo} AND ci02_instit = ".db_getsession('DB_instit'));
	$clquestaoaudit->sql_record($sSqlVerifica);

	if ($clquestaoaudit->numrows > 0) {

		$sqlerro = true;
		$clquestaoaudit->erro_msg 	= "N�mero de quest�o j� existe.";		
		$clquestaoaudit->erro_campo = "ci02_numquestao";

	}
	  
	if($sqlerro==false){
	
		db_inicio_transacao();
    	$clquestaoaudit->ci02_instit = db_getsession('DB_instit');
    	$clquestaoaudit->ci02_codtipo = $ci02_codtipo;
    	$clquestaoaudit->incluir($ci02_codquestao);
		
		if ($clquestaoaudit->erro_status == 0) {
      		$sqlerro = true;
		}
		
		db_fim_transacao($sqlerro);
		
  	} else {
		$clquestaoaudit->erro_status = 0;
	}

} else if (isset($alterar)) {
	  
	if($sqlerro==false){
	
		db_inicio_transacao();
    	$clquestaoaudit->alterar($ci02_codquestao);
		
		if ($clquestaoaudit->erro_status == 0){
      		$sqlerro = true;
		}
		
		db_fim_transacao($sqlerro);
		
  }
  
} else if (isset($excluir)) {
  
	if ($sqlerro==false) {
		
		db_inicio_transacao();

		$cllancamverifaudit = new cl_lancamverifaudit;
		$sSqlLancanVerif 	= $cllancamverifaudit->sql_query(null, "*", null, "ci05_codquestao = {$ci02_codquestao}");
		$rsLancanVerif 		= $cllancamverifaudit->sql_record($sSqlLancanVerif);

		if ($cllancamverifaudit->numrows > 0) {

			$clmatrizachadosaudit = new cl_matrizachadosaudit;

			for ($i = 0; $i < $cllancamverifaudit->numrows; $i++) {

				$iCodLan = db_utils::fieldsMemory($rsLancanVerif, $i)->ci05_codlan;
				
				$clmatrizachadosaudit->excluir(null, "ci06_codlan = {$iCodLan}");

				if ($clmatrizachadosaudit->erro_status == 0) {
					$sqlerro = true;
					$clquestaoaudit->erro_msg .= $clmatrizachadosaudit->erro_msg;
				}

				if (!$sqlerro) {

					$cllancamverifaudit->excluir($iCodLan);
					
					if ($cllancamverifaudit->erro_status == 0) {
						$sqlerro = true;
						$clquestaoaudit->erro_msg .= $cllancamverifaudit->erro_msg;
					}

				}				
				
			}
	
		}

    	$clquestaoaudit->excluir($ci02_codquestao);
		
		if ($clquestaoaudit->erro_status == 0) {
      		$sqlerro=true;
	    }
	
		db_fim_transacao($sqlerro);
  	}

} else if(isset($opcao)) {
	   
	$result = $clquestaoaudit->sql_record($clquestaoaudit->sql_query($ci02_codquestao));
   	if( $result != false && $clquestaoaudit->numrows>0) {
		
		db_fieldsmemory($result,0);
		
		$clprocessoaudit 	= new cl_processoaudit;
		$sSqlProcesso 		= $clprocessoaudit->sql_query(null, "ci03_numproc, ci03_anoproc", null, "ci03_codtipoquest = {$ci02_codtipo}");
		$rsProcesso			= $clprocessoaudit->sql_record($sSqlProcesso);
		
		if ($clprocessoaudit->numrows > 0) {
		
			include("libs/JSON.php");
			$oJson = new services_json();
			$aProcesso = $oJson->encode(db_utils::getCollectionByRecord($rsProcesso));

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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="390" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmquestaoaudit.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if (isset($alterar) || isset($excluir) || isset($incluir)) {
	
	if ($clquestaoaudit->erro_status == "0") {
		
		$clquestaoaudit->erro(true,false);
	    $db_botao=true;
    	echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    	if ($clquestaoaudit->erro_campo != "") {
		  
			echo "<script> document.form1.".$clquestaoaudit->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      		echo "<script> document.form1.".$clquestaoaudit->erro_campo.".focus();</script>";
    	}
  	} else {
    	db_msgbox($clquestaoaudit->erro_msg);
  	}
}
?>
