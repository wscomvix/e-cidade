<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empageconfgera_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempageconfgera = new cl_empageconfgera;
$clempageconfgera->rotulo->label("e90_codmov");
$clempageconfgera->rotulo->label("e90_codgera");
$clempageconfgera->rotulo->label("e90_codmov");
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
            <td width="4%" align="right" nowrap title="<?=$Te90_codmov?>">
              <?=$Le90_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e90_codmov",6,$Ie90_codmov,true,"text",4,"","chave_e90_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te90_codgera?>">
              <?=$Le90_codgera?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e90_codgera",6,$Ie90_codgera,true,"text",4,"","chave_e90_codgera");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te90_codmov?>">
              <?=$Le90_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e90_codmov",6,$Ie90_codmov,true,"text",4,"","chave_e90_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empageconfgera.hide();">
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
           if(file_exists("funcoes/db_func_empageconfgera.php")==true){
             include("funcoes/db_func_empageconfgera.php");
           }else{
           $campos = "empageconfgera.*";
           }
        }
        if(isset($chave_e90_codmov) && (trim($chave_e90_codmov)!="") ){
	         $sql = $clempageconfgera->sql_query($chave_e90_codmov,$chave_e90_codgera,$campos,"e90_codmov");
        }else if(isset($chave_e90_codmov) && (trim($chave_e90_codmov)!="") ){
	         $sql = $clempageconfgera->sql_query("","",$campos,"e90_codmov"," e90_codmov like '$chave_e90_codmov%' ");
        }else{
           $sql = $clempageconfgera->sql_query("","",$campos,"e90_codmov#e90_codgera","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clempageconfgera->sql_record($clempageconfgera->sql_query($pesquisa_chave));
          if($clempageconfgera->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e90_codmov',false);</script>";
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
