<?php

/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_bancos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_bancos = new cl_db_bancos;
$cldb_bancos->rotulo->label("db90_codban");
$cldb_bancos->rotulo->label("db90_descr");

if ( isset($chave_db90_codban) && !DBNumber::isInteger($chave_db90_codban) ) {
  $chave_db90_codban = '';
}

$chave_db90_descr = isset($chave_db90_descr) ? stripslashes($chave_db90_descr) : '';

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
      <form name="form2" method="post" action="" >
        <fieldset style="width: 35%">
          <legend>Pesquisa de Banco</legend>
          <table width="35%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tdb90_codban?>">
                <?=$Ldb90_codban?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
             db_input("db90_codban",10,$Idb90_codban,true,"text",4,"","chave_db90_codban");
             ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tdb90_descr?>">
                <?=$Ldb90_descr?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                  db_input("db90_descr",40,$Idb90_descr,true,"text",4,"","chave_db90_descr");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_bancos.hide();">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
      <?php

      $chave_db90_descr = addslashes($chave_db90_descr);

      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false) {
           if(file_exists("funcoes/db_func_db_bancos.php")==true) {
             include("funcoes/db_func_db_bancos.php");
           }else{
           $campos = "db90_codban,db90_descr,db90_digban,db90_abrev";
           }
        }

        if( isset($chave_db90_codban) ) {
          if (  !DBNumber::isInteger($chave_db90_codban) ) {
            $chave_db90_codban = '';
          }
        }

        if(isset($chave_db90_codban) && (trim($chave_db90_codban)!="" && DBNumber::isInteger($chave_db90_codban))) {
	         $sql = $cldb_bancos->sql_query($chave_db90_codban,$campos,"db90_codban");
        }else if(isset($chave_db90_descr) && (trim($chave_db90_descr)!="") ){
	         $sql = $cldb_bancos->sql_query("",$campos,"db90_descr"," db90_descr like '$chave_db90_descr%' ");
        }else{
           $sql = $cldb_bancos->sql_query("",$campos,"db90_codban","");
        }
        $repassa = array();
        
        if( isset($chave_db90_descr) ){
          $chave_db90_descr = str_replace("\\", "", $chave_db90_descr);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe");
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_bancos->sql_record($cldb_bancos->sql_query($pesquisa_chave));
          if($cldb_bancos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db90_descr',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
      </fieldset>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
   (function(){
      
      if( document.getElementById('$chave_db90_codban').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('$chave_db90_codban').value ) ) {
          alert('C�digo do banco FEBRABAN deve ser preenchido somente com n�meros!');
          document.getElementById('$chave_db90_codban').value = '';
          return false;  
        }
      }
      
    })();
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_db90_descr",true,1,"chave_db90_descr",true);
</script>