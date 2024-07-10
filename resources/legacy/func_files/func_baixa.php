<?
//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_baixa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbaixa = new cl_baixa;
$clbaixa->rotulo->label("bi08_codigo");
$clbaixa->rotulo->label("bi08_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tbi08_codigo?>">
              <?=$Lbi08_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("bi08_codigo",10,$Ibi08_codigo,true,"text",4,"","chave_bi08_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tbi08_descr?>">
              <?=$Lbi08_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("bi08_descr",100,$Ibi08_descr,true,"text",4,"","chave_bi08_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_baixa.hide();">
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
           if(file_exists("funcoes/db_func_baixa.php")==true){
             include("funcoes/db_func_baixa.php");
           }else{
           $campos = "baixa.*";
           }
        }
        if(isset($chave_bi08_codigo) && (trim($chave_bi08_codigo)!="") ){
	         $sql = $clbaixa->sql_query($chave_bi08_codigo,$campos,"bi08_codigo");
        }else if(isset($chave_bi08_descr) && (trim($chave_bi08_descr)!="") ){
	         $sql = $clbaixa->sql_query("",$campos,"bi08_descr"," bi08_descr like '$chave_bi08_descr%' ");
        }else{
           $sql = $clbaixa->sql_query("",$campos,"bi08_codigo","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clbaixa->sql_record($clbaixa->sql_query($pesquisa_chave));
          if($clbaixa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$bi08_descr',false);</script>";
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
