<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empord_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempord = new cl_empord;
$clempord->rotulo->label("e82_codmov");
$clempord->rotulo->label("e82_codord");
$clempord->rotulo->label("e82_codmov");
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
            <td width="4%" align="right" nowrap title="<?=$Te82_codmov?>">
              <?=$Le82_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e82_codmov",6,$Ie82_codmov,true,"text",4,"","chave_e82_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te82_codord?>">
              <?=$Le82_codord?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e82_codord",6,$Ie82_codord,true,"text",4,"","chave_e82_codord");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te82_codmov?>">
              <?=$Le82_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e82_codmov",6,$Ie82_codmov,true,"text",4,"","chave_e82_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empord.hide();">
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
           if(file_exists("funcoes/db_func_empord.php")==true){
             include("funcoes/db_func_empord.php");
           }else{
           $campos = "empord.*";
           }
        }
        if(isset($chave_e82_codmov) && (trim($chave_e82_codmov)!="") ){
	         $sql = $clempord->sql_query($chave_e82_codmov,$chave_e82_codord,$campos,"e82_codmov");
        }else if(isset($chave_e82_codmov) && (trim($chave_e82_codmov)!="") ){
	         $sql = $clempord->sql_query("","",$campos,"e82_codmov"," e82_codmov like '$chave_e82_codmov%' ");
        }else{
           $sql = $clempord->sql_query("","",$campos,"e82_codmov#e82_codord","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clempord->sql_record($clempord->sql_query($pesquisa_chave));
          if($clempord->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e82_codmov',false);</script>";
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
