<?
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsainscr_classe.php");
include("dbforms/db_funcoes.php");

$clissnotaavulsa      = new cl_issnotaavulsa;
$clissnotaavulsainscr = new cl_issnotaavulsainscr;
$post                 = db_utils::postmemory($_POST);
(integer)$db_opcao    = 1;
(integer)$db_botao    = true;
(boolean)$lSqlErro    = false;
(string) $erro_msg    = null;
if (isset($post->incluir)){

   db_inicio_transacao();
   $clissnotaavulsa->q51_usuario = db_getsession("DB_id_usuario");
	 $clissnotaavulsa->q51_hora    = date("h:i");
	 $clissnotaavulsa->q51_data    = date("Y-m-d",db_getsession("DB_datausu"));
	 $dtparte                          = explode("/",$post->q51_dtemiss);
	 $clissnotaavulsa->q51_dtemiss_dia = $dtparte[0];
	 $clissnotaavulsa->q51_dtemiss_mes = $dtparte[1];
	 $clissnotaavulsa->q51_dtemiss_ano = $dtparte[2];
	 $clissnotaavulsa->incluir(null);
   if ($clissnotaavulsa->erro_status == 0){
       
			 $lSqlErro = true;
			 $erro_msg = $clissnotaavulsa->erro_msg;

	 }
	 if (!$lSqlErro){

			$clissnotaavulsainscr->q52_issnotaavulsa = $clissnotaavulsa->q51_numnota;
      $clissnotaavulsainscr->q52_inscr         = $post->q52_inscr;
			$clissnotaavulsainscr->incluir(null);
			if ($clissnotaavulsainscr->erro_status == 0){

         $lSqlErro = true;
				 $erro_msg = $clissnotaavulsainscr->erro_status;
 
			}

	 }
   db_fim_transacao($lSqlErro);
	 if (!$lSqlErro){
		  
			db_redireciona("iss1_issnotaavulsa004.php?q51_numnota=".$clissnotaavulsa->q51_numnota); 

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
	include("forms/db_frmissnotaavulsaalt.php");
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","q51_dtemiss",true,1,"q51_dtemiss",true);
</script>
<?
if(isset($post->incluir)){
  if($clissnotaavulsa->erro_status=="0"){
    $clissnotaavulsa->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clissnotaavulsa->erro_campo!=""){
      echo "<script> document.form1.".$clissnotaavulsa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clissnotaavulsa->erro_campo.".focus();</script>";
    }
  }else{
    $clissnotaavulsa->erro(true,true);
  }
}
?>
