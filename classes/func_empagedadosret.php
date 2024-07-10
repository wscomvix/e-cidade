<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empagedadosret_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempagedadosret = new cl_empagedadosret;
$clempagedadosret->rotulo->label("e75_codret");
$clempagedadosret->rotulo->label("e75_codgera");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te75_codret?>">
              <?=$Le75_codret?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e75_codret",10,$Ie75_codret,true,"text",4,"","chave_e75_codret");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te75_codgera?>">
              <?=$Le75_codgera?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e75_codgera",6,$Ie75_codgera,true,"text",4,"","chave_e75_codgera");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empagedadosret.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_empagedadosret.php")==true){
             include("funcoes/db_func_empagedadosret.php");
           }else{
           $campos = "empagedadosret.*";
           }
        }
        if(isset($chave_e75_codret) && (trim($chave_e75_codret)!="") ){
	         $sql = $clempagedadosret->sql_query($chave_e75_codret,$campos,"e75_codret");
        }else if(isset($chave_e75_codgera) && (trim($chave_e75_codgera)!="") ){
	         $sql = $clempagedadosret->sql_query("",$campos,"e75_codgera"," e75_codgera like '$chave_e75_codgera%' ");
        }else{
           $sql = $clempagedadosret->sql_query("",$campos,"e75_codret","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clempagedadosret->sql_record($clempagedadosret->sql_query($pesquisa_chave));
          if($clempagedadosret->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e75_codgera',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
