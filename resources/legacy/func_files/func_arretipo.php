<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arretipo_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clarretipo = new cl_arretipo();
$clarretipo->rotulo->label("k00_tipo"); 
$clarretipo->rotulo->label("k00_descr");

if (isset($chave_k00_tipo) && !DBNumber::isInteger($chave_k00_tipo)) {
  $chave_k00_tipo = '';
}

if(isset($oGet->k03_tipo)){
	$wheretipo = " and arretipo.k03_tipo in($oGet->k03_tipo) ";
}else{
	$wheretipo = "";
}
$chave_k00_descr = isset($chave_k00_descr) ? stripslashes($chave_k00_descr) : '';

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
            <td width="4%" align="right" nowrap title="<?php echo $Tk00_tipo; ?>">
              <?php echo $Lk00_tipo; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
		            db_input("k00_tipo", 4, $Ik00_tipo, true, "text", 4, "", "chave_k00_tipo");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Tk00_descr; ?>">
              <?php echo $Lk00_descr; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("k00_descr", 40, $Ik00_descr, true, "text", 4, "", "chave_k00_descr");
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_arretipo.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top"> 
      <?php

      $chave_k00_descr = addslashes($chave_k00_descr);

      if (!isset($pesquisa_chave)) {

        if (!isset($campos)) {

          if (file_exists("funcoes/db_func_arretipo.php")) {
            include("funcoes/db_func_arretipo.php");
          } else {
            $campos = "arretipo.*";
          }
        }

        if (isset($chave_k00_tipo) && (trim($chave_k00_tipo) != "")) {
	         $sql = $clarretipo->sql_query($chave_k00_tipo,$campos, "k00_tipo");
        } else if (isset($chave_k00_descr) && (trim($chave_k00_descr)!="") ){
	         $sql = $clarretipo->sql_query("", $campos, "k00_descr"," k00_descr like '$chave_k00_descr%' ");
        } else {
           $sql = $clarretipo->sql_query("", $campos, "k00_tipo","");
        }

        $repassa = array();
        if (isset($chave_k00_descr) && isset($chave_k00_tipo)) {
          $repassa = array("chave_k00_tipo" => $chave_k00_tipo, "chave_k00_descr" => $chave_k00_descr);
        }

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $result = $clarretipo->sql_record($clarretipo->sql_query($pesquisa_chave));
          if ($clarretipo->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$k00_descr', false);</script>";
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
  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_k00_descr", true, 1, "chave_k00_descr", true);
</script>