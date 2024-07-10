<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_atendimento_top_classe.php");
include("classes/db_atendimentoorigem_classe.php");
include("classes/db_atendimentomod_classe.php");
include("classes/db_tecnico_classe.php");
include("classes/db_atendimentolanc_classe.php");
include("classes/db_clientes_classe.php");
include("classes/db_db_usuclientes_classe.php");
include("classes/db_atendimento_classe.php");
include("classes/db_atendimentousu_classe.php");
include("classes/db_atendimentocadsituacao_classe.php");
include("classes/db_atendimentosituacao_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_atendarea_classe.php");
include("classes/db_atendcadarea_classe.php");
include("classes/db_atendareatec_classe.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_tipoatend_classe.php");
db_postmemory($HTTP_POST_VARS);

$cltipoatend              = new cl_tipoatend;
$clatendimento_top        = new cl_atendimento_top;
$clatendimentoorigem      = new cl_atendimentoorigem;
$clatendimentomod         = new cl_atendimentomod;
$cltecnico                = new cl_tecnico;
$clatendimentolanc        = new cl_atendimentolanc;
$clclientes               = new cl_clientes;
$cldb_usuclientes         = new cl_db_usuclientes;
$clatendimento            = new cl_atendimento;
$clatendimentousu         = new cl_atendimentousu;
$clatendimentocadsituacao = new cl_atendimentocadsituacao;
$clatendimentosituacao    = new cl_atendimentosituacao;
$clatendarea              = new cl_atendarea;
$clatendcadarea           = new cl_atendcadarea;
$clatendareatec           = new cl_atendareatec;
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("at10_usuario");
$clrotulo->label("nome_tecnico");
$clrotulo->label("nome_modulo");
$clrotulo->label("at02_codatend");
$clrotulo->label("at11_origematend");
$clrotulo->label("at04_codtipo");
$clrotulo->label("at05_seq");
$clrotulo->label("at40_sequencial");
$clrotulo->label("at02_observacao");
$clrotulo->label("at16_situacao");

$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  //
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
      
   $campos="at01_codcli as cliente, at10_usuario as usuario, at10_nome as nome, at03_id_usuario as tecnico, nome as nome_tecnico, at04_codtipo, at16_situacao, at02_observacao,at20_sequencial,at16_sequencial,at28_sequencial";
   if (!isset($area)||(isset($area)&&$area=="")){
   		$campos .= " ,at28_atendcadarea as area ";
   }
   
   
   $result = $clatendimento ->sql_record($clatendimento ->sql_query_inc($chavepesquisa,$campos)); 
  
  //die($clatendimento ->sql_query_inc($chavepesquisa,$campos));
   db_fieldsmemory($result,0);
   $db_botao = true;
}
  
if (isset($alterar)&&$alterar!=""){
	$sqlerro=false;
	if($usuario==""){
		db_msgbox("Informe o Usu�rio.");
	}else{
		db_inicio_transacao();
		$clatendimento->at02_codatend   = $chavepesquisa;
		$clatendimento->at02_codcli     = $cliente;
		$clatendimento->at02_codtipo    = $at04_codtipo;
		$clatendimento->at02_observacao = $at02_observacao;
		$clatendimento->alterar($chavepesquisa);
		$erro_msg = $clatendimento->erro_msg;
		if ($clatendimento->erro_status=="0"){
			$sqlerro=true;				
		}
		if ($sqlerro==false){
			$clatendimentousu->at20_sequencial = $at20_sequencial;
			$clatendimentousu->at20_codatend   = $clatendimento->at02_codatend;
			$clatendimentousu->at20_usuario    = $usuario;
			$clatendimentousu->alterar($at20_sequencial);
			if ($clatendimentousu->erro_status=="0"){
				$sqlerro=true;	
				$erro_msg = $clatendimentousu->erro_msg;	
			}
			if ($sqlerro==false){
				$cltecnico->at03_codatend   = $chavepesquisa;
				$cltecnico->excluir($chavepesquisa,null);
				if($tecnico>0){
					$cltecnico->at03_codatend   = $chavepesquisa;
					$cltecnico->at03_id_usuario = $tecnico;
					$cltecnico->incluir($chavepesquisa,$tecnico);
					$erro_msg = $cltecnico->erro_msg;
					if ($cltecnico->erro_status=="0"){
						$sqlerro=true;				
					}
				}

					if ($sqlerro==false){
						$clatendimentosituacao->at16_sequencial = $at16_sequencial;
						$clatendimentosituacao->at16_atendimento = $chavepesquisa;
						$clatendimentosituacao->at16_situacao = $at16_situacao;
						$clatendimentosituacao->alterar($at16_sequencial);
						$erro_msg = $clatendimentosituacao->erro_msg;
						if ($clatendimentosituacao->erro_status=="0"){
							$sqlerro=true;				
						}
					}
					if ($sqlerro==false){
						if($at28_sequencial!=""){
							$clatendarea->excluir($at28_sequencial);
						}
							if($area>0){
								$clatendarea->at28_atendcadarea = $area;
								$clatendarea->at28_atendimento= $clatendimento->at02_codatend;
								$clatendarea->incluir(null);
								$erro_msg = $clatendarea->erro_msg;
								if ($clatendarea->erro_status=="0"){
									$sqlerro=true;				
								}	
								
							}
						
						/*
						if ($area>0){
							db_msgbox("area > 0...$at28_sequencial");
							$clatendarea->at28_sequencial = $at28_sequencial;
							$clatendarea->at28_atendcadarea = $area;
							$clatendarea->alterar($at28_sequencial);
							$erro_msg = $clatendarea->erro_msg;
							if ($clatendarea->erro_status=="0"){
								$sqlerro=true;				
							}
						}elseif($area==0){
							db_msgbox("area = 0");
							$clatendarea->excluir($at28_sequencial);
						}*/
					}
						
			}
		}
		
		
		db_fim_transacao($sqlerro);
		if ($sqlerro==true){
			db_msgbox($erro_msg);
			echo "<script>location.href='ate4_atendcli002.php';</script>";
		}else{
		    $certo=true;
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
<script>
function js_submit(){
	document.form1.usuario.value = "";
	document.form1.nome.value = "";
	document.form1.submit();
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
<br><br>
  <table  align="center">
   	<? include("forms/db_frmatendcli.php"); ?>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
