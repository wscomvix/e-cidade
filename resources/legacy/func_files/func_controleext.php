<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_controleext_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcontroleext = new cl_controleext;
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_controleext.hide();">
  </form>
      <?php
      if (isset($pesquisa_chave)) {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $sSQL = $clcontroleext->sql_query($pesquisa_chave);

          $result = $clcontroleext->sql_record($sSQL);

          if ($clcontroleext->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$oid',false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }

        } else {
          echo "<script>".$funcao_js."('',false);</script>";
        }

      } else {

        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_controleext.php")==true) {
            include("funcoes/db_func_controleext.php");
          } else {
            $campos = "controleext.oid,controleext.*";
          }
        }

        if (isset($codcon) && !empty($codcon)) {

          $sSQL = $clcontroleext->sql_query(null, '*', null, "k167_codcon = {$codcon}");

          $result = $clcontroleext->sql_record($sSQL);

          if ($clcontroleext->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('{$c60_descr}', '{$k167_anousu}',false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado', '', true);</script>";
          }

        } else {

          $sSQL = $clcontroleext->sql_query();

          $repassa = array();
          echo '<div class="container">';
          echo '  <fieldset>';
          echo '    <legend>Resultado da Pesquisa</legend>';
            db_lovrot($sSQL,15,"()","",$funcao_js,"","NoMe",$repassa);
          echo '  </fieldset>';
          echo '</div>';

        }
      }
      ?>
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
