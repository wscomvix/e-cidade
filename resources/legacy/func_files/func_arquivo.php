<?
//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_arquivo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clarquivo = new cl_arquivo;
$clarquivo->rotulo->label("bi12_codigo");
$clarquivo->rotulo->label("bi12_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Tbi12_codigo?>">
              <?=$Lbi12_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("bi12_codigo",8,$Ibi12_codigo,true,"text",4,"","chave_bi12_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tbi12_nome?>">
              <?=$Lbi12_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("bi12_nome",100,$Ibi12_nome,true,"text",4,"","chave_bi12_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_arquivo.hide();">
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
           if(file_exists("funcoes/db_func_arquivo.php")==true){
             include("funcoes/db_func_arquivo.php");
           }else{
           $campos = "arquivo.*";
           }
        }
        if(isset($chave_bi12_codigo) && (trim($chave_bi12_codigo)!="") ){
	         $sql = $clarquivo->sql_query($chave_bi12_codigo,$campos,"bi12_codigo");
        }else if(isset($chave_bi12_nome) && (trim($chave_bi12_nome)!="") ){
	         $sql = $clarquivo->sql_query("",$campos,"bi12_nome"," bi12_nome like '$chave_bi12_nome%' ");
        }else{
           $sql = $clarquivo->sql_query("",$campos,"bi12_codigo","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clarquivo->sql_record($clarquivo->sql_query($pesquisa_chave));
          if($clarquivo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$bi12_nome',false);</script>";
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
