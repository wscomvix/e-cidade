<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_ppaversao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clppaversao = new cl_ppaversao;
$clppaversao->rotulo->label("o119_sequencial");
$clppaversao->rotulo->label("o119_versao");
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
            <td width="4%" align="right" nowrap title="<?=$To119_sequencial?>">
              <?=$Lo119_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o119_sequencial",10,$Io119_sequencial,true,"text",4,"","chave_o119_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To119_versao?>">
              <?=$Lo119_versao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o119_versao",10,$Io119_versao,true,"text",4,"","chave_o119_versao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ppaversao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = "1=1 and o119_versaofinal is true";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_ppaversao.php")==true){
             include("funcoes/db_func_ppaversao.php");
           }else{
           $campos = "ppaversao.*";
           }
        }
        $campos = "o119_sequencial, o119_ppalei,o119_versao,o119_versaofinal,o01_anoinicio, o01_anofinal,o01_descricao";
        if(isset($chave_o119_sequencial) && (trim($chave_o119_sequencial)!="") ){
	         $sql = $clppaversao->sql_query(null,"*" ,"o119_versao",
	                                        "o119_sequencial = {$chave_o119_sequencial}");
        }else if(isset($chave_o119_versao) && (trim($chave_o119_versao)!="") ){
	         $sql = $clppaversao->sql_query("",$campos,"o119_versao"," o119_versao like '$chave_o119_versao%' and {$sWhere} ");
        }else{
           $sql = $clppaversao->sql_query("",$campos,"o119_sequencial","{$sWhere}");
        }
        $repassa = array();
        if(isset($chave_o119_versao)){
          $repassa = array("chave_o119_sequencial"=>$chave_o119_sequencial,"chave_o119_versao"=>$chave_o119_versao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clppaversao->sql_record($clppaversao->sql_query($pesquisa_chave));
          if($clppaversao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o01_descricao','$o01_anoinicio',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_o119_versao",true,1,"chave_o119_versao",true);
</script>
