<?
//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_emprestimolocal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clemprestimolocal = new cl_emprestimolocal;
$clemprestimolocal->rotulo->label("bi20_codigo");
$clemprestimolocal->rotulo->label("bi20_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tbi20_codigo?>">
              <?=$Lbi20_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("bi20_codigo",10,$Ibi20_codigo,true,"text",4,"","chave_bi20_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tbi20_descr?>">
              <?=$Lbi20_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("bi20_descr",30,$Ibi20_descr,true,"text",4,"","chave_bi20_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_emprestimolocal.hide();">
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
           if(file_exists("funcoes/db_func_emprestimolocal.php")==true){
             include("funcoes/db_func_emprestimolocal.php");
           }else{
           $campos = "emprestimolocal.*";
           }
        }
        if(isset($chave_bi20_codigo) && (trim($chave_bi20_codigo)!="") ){
	         $sql = $clemprestimolocal->sql_query($chave_bi20_codigo,$campos,"bi20_codigo");
        }else if(isset($chave_bi20_descr) && (trim($chave_bi20_descr)!="") ){
	         $sql = $clemprestimolocal->sql_query("",$campos,"bi20_descr"," bi20_descr like '$chave_bi20_descr%' ");
        }else{
           $sql = $clemprestimolocal->sql_query("",$campos,"bi20_codigo","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clemprestimolocal->sql_record($clemprestimolocal->sql_query($pesquisa_chave));
          if($clemprestimolocal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$bi20_descr',false);</script>";
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
