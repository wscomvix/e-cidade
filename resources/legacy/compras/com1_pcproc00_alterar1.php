<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcprocitem_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamtroca_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
//db_postmemory($HTTP_POST_VARS,2);db_postmemory($HTTP_GET_VARS,2);
$clsolicita = new cl_solicita;
$clsolicitem = new cl_solicitem;
$clpcprocitem = new cl_pcprocitem;
$clpcproc = new cl_pcproc;
$clpcparam = new cl_pcparam;
$clpcorcam = new cl_pcorcam;
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamforne = new cl_pcorcamforne;
$clpcorcamforne2 = new cl_pcorcamforne;
$clpcorcamval = new cl_pcorcamval;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamtroca = new cl_pcorcamtroca;
$db_opcao=1;
$db_botao=true;
$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(null,"pc30_horas,pc30_dias"));
db_fieldsmemory($result_pcparam,0);
if(isset($incluir) || isset($juntar)){ 
  $gerouorc = false;
  $sqlerro=false;
  if(isset($valores) && $valores!=""){
    db_inicio_transacao();
    if(isset($incluir)){
      $clpcproc->pc80_codproc = @$pc80_codproc;
      $clpcproc->pc80_data    = date("Y-m-d",db_getsession("DB_datausu"));
      $clpcproc->pc80_usuario = db_getsession("DB_id_usuario");
      $clpcproc->pc80_depto   = db_getsession("DB_coddepto");
      $clpcproc->pc80_resumo  = '';
      $clpcproc->incluir(@$pc80_codproc);
      $erro_msg   = $clpcproc->erro_msg; 
      $pc80_codproc= $clpcproc->pc80_codproc;
      if($clpcproc->erro_status==0){
	$sqlerro=true;
      } 
    }
    $arr_valores = explode(",",$valores);
    if(isset($juntar)){
      $pc80_codproc = $juntar;
    }
    $arr_numero = Array();
    $arr_solici = Array();
    for($i=0;$i<sizeof($arr_valores);$i++){      
      $arr_item  = explode("_",$arr_valores[$i]);
      if(in_array($arr_item[1],$arr_numero)==false){
	array_push($arr_numero,$arr_item[1]);
      }      
      $pc11_codigo = $arr_item[2];
      $clpcprocitem->pc81_codproc   = $pc80_codproc;
      $clpcprocitem->pc81_solicitem = $pc11_codigo;
      $clpcprocitem->incluir(@$pc81_codprocitem);
      if(!isset($arr_solici[$pc11_codigo])){
	$arr_solici[$pc11_codigo] = $clpcprocitem->pc81_codprocitem;
      }
      if($clpcprocitem->erro_status==0){
        $erro_msg   = $clpcprocitem->erro_msg; 
	$sqlerro=true;
	break;
      } 
    }
    $arr_importar = explode(",",$importa);
    $arr_orcam = Array();
    $rowssizeof = sizeof($arr_importar);
    $arr_orcamfornexist  = Array();

    for($i=0;$i<$rowssizeof;$i++){
      if(trim($arr_importar[$i])!=""){
	$arr_importaritem = explode("_",$arr_importar[$i]);
	$orcamento = $arr_importaritem[1];
	$item      = $arr_importaritem[2];
	$orcamitem = $arr_importaritem[3];
	if(isset($arr_solici[$item]) && $sqlerro==false){
	  if($gerouorc == false){
	    $clpcorcam->pc20_dtate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$pc30_dias,date("Y"))); 
	    $clpcorcam->pc20_hrate = $pc30_horas; 
	    if(isset($juntar)){
	      $result_pc22_codorc = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query(null,null,"max(pc20_codorc) as pc20_codorc",""," pc80_codproc=$pc80_codproc"));
	      if($clpcorcamitemproc->numrows>0){
		db_fieldsmemory($result_pc22_codorc,0);
	      }else{
		$clpcorcam->incluir(null);
		$pc20_codorc = $clpcorcam->pc20_codorc;
	      }
	    }else{
	      $clpcorcam->incluir(null);
	      $pc20_codorc = $clpcorcam->pc20_codorc;
	    }
	    if($clpcorcam->erro_status==0){
	      $erro_msg   = $clpcorcam->erro_msg; 
	      $sqlerro=true;
	      break;
	    }else{
	      $erro_msg .= "\\n\\nOBS.: Foi gerado o or�amento n�mero $pc20_codorc para este processo de compras.";
	    }
	    $gerouorc = true;
	  }
	  $clpcorcamitem->pc22_codorc = $pc20_codorc;
	  $clpcorcamitem->incluir(null);
	  $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;
	  if($clpcorcamitem->erro_status==0){
	    $erro_msg   = $clpcorcamitem->erro_msg; 
	    $sqlerro=true;
	    break;
	  }
	  $clpcorcamitemproc->incluir($pc22_orcamitem,$arr_solici[$item]);
	  if($clpcorcamitemproc->erro_status==0){
	    $erro_msg   = $clpcorcamitemproc->erro_msg; 
	    $sqlerro=true;
	    break;
	  }
	  if($sqlerro==false && isset($pc20_codorc)){
	    $result_fornecedores = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_fornec(null,"pc21_numcgm,pc21_orcamforne as selforne",""," pc22_orcamitem=$orcamitem "));
	    $numrows_contafornec = $clpcorcamforne->numrows;
	    for($contaforne=0;$contaforne<$numrows_contafornec;$contaforne++){
	      db_fieldsmemory($result_fornecedores,$contaforne);
              if(!isset($arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc])){
		$clpcorcamforne->pc21_numcgm = $pc21_numcgm;
		$clpcorcamforne->pc21_codorc = $pc20_codorc;
		$clpcorcamforne->incluir(null);
		$pc21_orcamforne = $clpcorcamforne->pc21_orcamforne;
                $arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc] = $pc21_orcamforne;
//	        db_msgbox($arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc]);
		if($clpcorcamforne->erro_status==0){
		  $erro_msg   = $clpcorcamforne->erro_msg; 
		  $sqlerro=true;
		  break;
		}
              }
              $pc21_orcamforne = $arr_orcamfornexist[$pc21_numcgm."_".$pc20_codorc];
//	      db_msgbox($pc21_orcamforne);
	      $result_pcorcamval = $clpcorcamval->sql_record($clpcorcamval->sql_query_file($selforne,$orcamitem,"pc23_valor,pc23_quant,pc23_obs"));
	      $numrows_pcorcamval = $clpcorcamval->numrows;
	      if($numrows_pcorcamval>0){
		db_fieldsmemory($result_pcorcamval,0);
		$clpcorcamval->pc23_valor = $pc23_valor;
		$clpcorcamval->pc23_quant = $pc23_quant;
		$clpcorcamval->pc23_obs   = $pc23_obs;
		$clpcorcamval->incluir($pc21_orcamforne,$pc22_orcamitem);
		if($clpcorcamval->erro_status==0){
		  $erro_msg   = $clpcorcamval->erro_msg; 
		  $sqlerro=true;
		  break;
		}
		if($sqlerro==false && isset($pc21_orcamforne) && isset($pc22_orcamitem)){
		  $result_itemjulg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_file($orcamitem,$selforne,"pc24_pontuacao as pontuacao"));
		  $numrows_itemjulg= $clpcorcamjulg->numrows;
		  for($ii=0;$ii<$numrows_itemjulg;$ii++){
		    db_fieldsmemory($result_itemjulg,$ii);
		    $clpcorcamjulg->pc24_pontuacao = $pontuacao;
		    $clpcorcamjulg->incluir($pc22_orcamitem,$pc21_orcamforne);
		    if($clpcorcamjulg->erro_status==0){
		      $erro_msg = $clpcorcamjulg->erro_msg;
		      $sqlerro=true;
		      break;
		    }
		  }
		  if($sqlerro==true){
		    break;
		  }
		}
		if($sqlerro==false && isset($pc22_orcamitem)){
		  $result_itemtroca = $clpcorcamtroca->sql_record($clpcorcamtroca->sql_query_file(null,"pc25_motivo,pc25_forneant,pc25_forneatu","","pc25_orcamitem=$orcamitem"));
		  $numrows_itemtroca= $clpcorcamtroca->numrows;
		  for($ii=0;$ii<$numrows_itemtroca;$ii++){
		    db_fieldsmemory($result_itemtroca,$ii);
		    $clpcorcamtroca->pc25_orcamitem = $pc22_orcamitem;
		    $clpcorcamtroca->pc25_motivo    = $pc25_motivo;

        if (trim(@$pc25_forneant)==""){
             $clpcorcamtroca->pc25_forneant = $clpcorcamforne->pc21_orcamforne;
        } else {
             $clpcorcamtroca->pc25_forneant = $pc25_forneant;
        }
                
        if (trim(@$pc25_forneatu)==""){
             $clpcorcamtroca->pc25_forneatu = $clpcorcamforne->pc21_orcamforne;
        } else {
             $clpcorcamtroca->pc25_forneatu = $pc25_forneatu;
        }

		    $clpcorcamtroca->incluir(null);
		    if($clpcorcamtroca->erro_status==0){
		      $erro_msg = $clpcorcamtroca->erro_msg;
		      $sqlerro=true;
		      break;
		    }
		  }
		}
	      }
	    }
	    if($sqlerro==true){
	      break;
	    }
	  }
	}
      }
    }
    if(sizeof($arr_numero)==1 && !isset($juntar)){
      $result_resum = $clsolicita->sql_record($clsolicita->sql_query_file($arr_numero[0],"pc10_resumo"));
      if($clsolicita->numrows>0){
	db_fieldsmemory($result_resum,0);
        $resumao = str_replace("\n",'\\n',$pc10_resumo);
        $resumao = str_replace("\r",'',$resumao);
	$clpcproc->pc80_resumo  = $resumao;
	$clpcproc->alterar($pc80_codproc);
	$pc80_codproc= $clpcproc->pc80_codproc;
	if($clpcproc->erro_status==0){
	  $erro_msg= str_replace("Altera��o","Inclusao",$clpcproc->erro_msg);
	  $sqlerro=true;
	} 
      }
    }
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      unset($valores,$importa);
    }
  }else{
    $sqlerro=true;
    $erro_msg = "Usu�rio: \\n\\nInclus�o n�o efetuada. \\n\\nERRO: Nenhum item informado. \\n\\nAdministrador:";
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmpcproc.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
arr_dados = new Array();
arr_impor = new Array();
<?
/*
$result_solicitem = $clpcprocitem->sql_record($clpcprocitem->sql_query(null,"pc11_numero,pc11_codigo,pc81_codprocitem,pc81_codproc"," pc11_numero desc,pc11_codigo desc "));
for($i=0;$i<$clpcprocitem->numrows;$i++){
  db_fieldsmemory($result_solicitem,$i,true);
  echo "arr_dados.unshift('item".$pc11_numero."_".$pc11_codigo."')";
}
*/
?>
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clpcproc->erro_campo!=""){
      echo "<script> document.form1.".$clpcproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcproc->erro_campo.".focus();</script>";
    };
  }
}
?>
