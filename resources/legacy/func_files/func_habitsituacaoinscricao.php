<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_habitsituacaoinscricao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clhabitsituacaoinscricao = new cl_habitsituacaoinscricao;
$clhabitsituacaoinscricao->rotulo->label("ht13_sequencial");
$clhabitsituacaoinscricao->rotulo->label("ht13_descricao");
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
            <td width="4%" align="right" nowrap title="<?=$Tht13_sequencial?>">
              <?=$Lht13_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht13_sequencial",10,$Iht13_sequencial,true,"text",4,"","chave_ht13_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht13_descricao?>">
              <?=$Lht13_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht13_descricao",50,$Iht13_descricao,true,"text",4,"","chave_ht13_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_habitsituacaoinscricao.hide();">
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
           if(file_exists("funcoes/db_func_habitsituacaoinscricao.php")==true){
             include("funcoes/db_func_habitsituacaoinscricao.php");
           }else{
           $campos = "habitsituacaoinscricao.*";
           }
        }
        if(isset($chave_ht13_sequencial) && (trim($chave_ht13_sequencial)!="") ){
	         $sql = $clhabitsituacaoinscricao->sql_query($chave_ht13_sequencial,$campos,"ht13_sequencial");
        }else if(isset($chave_ht13_descricao) && (trim($chave_ht13_descricao)!="") ){
	         $sql = $clhabitsituacaoinscricao->sql_query("",$campos,"ht13_descricao"," ht13_descricao like '$chave_ht13_descricao%' ");
        }else{
           $sql = $clhabitsituacaoinscricao->sql_query("",$campos,"ht13_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ht13_descricao)){
          $repassa = array("chave_ht13_sequencial"=>$chave_ht13_sequencial,"chave_ht13_descricao"=>$chave_ht13_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clhabitsituacaoinscricao->sql_record($clhabitsituacaoinscricao->sql_query($pesquisa_chave));
          if($clhabitsituacaoinscricao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ht13_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_ht13_descricao",true,1,"chave_ht13_descricao",true);
</script>
