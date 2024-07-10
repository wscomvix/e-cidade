<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhlota_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhlota = new cl_rhlota();
$clrhlota->rotulo->label("r70_codigo"); 
$clrhlota->rotulo->label("r70_estrut");
$clrhlota->rotulo->label("r70_descr");

if (isset($chave_r70_codigo) && !DBNumber::isInteger($chave_r70_codigo)) {
  $chave_r70_codigo = '';
}

$chave_r70_estrut = isset($chave_r70_estrut) ? stripslashes($chave_r70_estrut) : '';

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
            <td width="4%" align="right" nowrap title="<?php echo $Tr70_codigo; ?>">
              <?php echo $Lr70_codigo; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("r70_codigo", 4, $Ir70_codigo, true, "text", 4, "", "chave_r70_codigo");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Tr70_estrut; ?>">
              <?php echo $Lr70_estrut; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("r70_estrut", 20, $Ir70_estrut, true, "text", 4, "", "chave_r70_estrut");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tr70_descr?>">
              <?=$Lr70_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r70_descr",40,$Ir70_descr,true,"text",4,"","chave_r70_descr");
		       ?>
            </td>
          </tr>
          <tr> 
             <td width="4%" align="right" nowrap title="Selecionar todos, ativos ou inativos"><b>Sele��o por:</b></td>
             <td width="96%" align="left" nowrap>
             <?
             if(!isset($opcao)){
	           $opcao = "t";
             }
             if(!isset($opcao_bloq)){
             	$opcao_bloq = 1;
             }
             $arr_opcao = array("i"=>"Todos","t"=>"Ativos","f"=>"Inativos");
             db_select('opcao',$arr_opcao,true,$opcao_bloq,"onchange='js_reload();'"); 
             ?>
             </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onClick="return js_valida(arguments[0])"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.fechardb_iframerhlota.click();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top"> 
      <?
      $where_ativo = "";
      if(isset($opcao) && trim($opcao)!="i"){
        $where_ativo = " and r70_ativo='$opcao' ";
      }

      $dbwhere = "";
      if(isset($instit)){
        $dbwhere = " and r70_instit = $instit ";
      }
      $chave_r70_estrut = addslashes($chave_r70_estrut);

      if (!isset($pesquisa_chave)) {

        if (!isset($campos)) {

          if (file_exists("funcoes/db_func_rhlota.php")) {
            include("funcoes/db_func_rhlota.php");
          } else {
            $campos = "rhlota.*,case when rh43_recurso is null then rh25_recurso else rh43_recurso end as recurso";
          }
        }

        if (isset($chave_r70_codigo) && (trim($chave_r70_codigo) != "")) {
	         $sql = $clrhlota->sql_rhlota_vinc_rec(null,$campos,"r70_codigo"," r70_codigo = $chave_r70_codigo $dbwhere $where_ativo ");
        }else if(isset($chave_r70_descr) && (trim($chave_r70_descr)!="") ){
	         $sql = $clrhlota->sql_rhlota_vinc_rec(null,$campos,"r70_descr"," r70_descr like '$chave_r70_descr%' $dbwhere $where_ativo ");
        } else if (isset($chave_r70_estrut) && (trim($chave_r70_estrut)!="") ){
	         $sql = $clrhlota->sql_rhlota_vinc_rec(null,$campos,"r70_estrut"," r70_estrut like '$chave_r70_estrut%' $dbwhere $where_ativo ");
        } else {
           $sql = $clrhlota->sql_rhlota_vinc_rec(null,$campos,"r70_codigo"," 1=1 $dbwhere $where_ativo ");
        }

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe");
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $clrhlota->sql_record($clrhlota->sql_rhlota_vinc_rec(null,"rhlota.*,case when rh43_recurso is null then rh25_recurso else rh43_recurso end as recurso","r70_codigo"," r70_codigo = $pesquisa_chave $dbwhere "));
          if ($clrhlota->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$r70_descr',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }

        } else {
	        echo "<script>".$funcao_js."('',false);</script>";
        }
      }

      ?>
    </td>
  </tr>
</table>
</body>
</html>
<?php if (!isset($pesquisa_chave)) { ?>
  <script>

    function js_valida(event) {
      document.getElementById('chave_r70_codigo').onkeyup(event);
      return true;
    }

  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_r70_estrut", true, 1, "chave_r70_estrut", true);
</script>
