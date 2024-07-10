<?php
/*
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

require("classes/db_pcorcam_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clpcorcam = new cl_pcorcam;
$clpcorcam->rotulo->label("pc20_codorc");
$clpcorcam->rotulo->label("pc20_dtate");
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
            <td width="4%" align="right" nowrap title="<?=$Tpc20_codorc?>">
              <?=$Lpc20_codorc?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("pc20_codorc",10,$Ipc20_codorc,true,"text",4,"","chave_pc20_codorc");
		          ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc20_dtate?>">
              <strong>Data do Prazo Limite De:</strong>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_inputdata('pc20_data_inicial', "", "", '', true, "text", 1, "", "chave_pc20_data_inicial");
                echo "&nbsp;<strong>At�:</strong>&nbsp;";
                db_inputdata('pc20_data_final', "", "", '', true, "text", 1, "", "chave_pc20_data_final");
                db_input("param",10,"",false,"hidden",3);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcorcam.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
        <?php
      if (!isset($camposControleData)) {
          if (file_exists("funcoes/db_func_pcorcam.php")) {
              include("funcoes/db_func_pcorcam.php");
          } else {
              $camposControleData = "pcorcam.*";
          }
      }

      $camposControleData = " distinct ". $camposControleData;

      if (isset($chave_pc20_codorc) && (trim($chave_pc20_codorc) !== "") ) {
          $sWhere = " pc20_codorc = {$chave_pc20_codorc}";
          $sql = $clpcorcam->sql_query(null, $camposControleData, "pc20_codorc desc", $sWhere);
      } else if (!empty($chave_pc20_data_inicial)) {
          $chave_pc20_data_inicial = implode('-', array_reverse(explode('/', $chave_pc20_data_inicial)));
          $sql = $clpcorcam->sql_query(null, $camposControleData, "pc20_codorc desc", " pc20_dtate >= '{$chave_pc20_data_inicial}' ");

          if ( !empty($chave_pc20_data_final) ) {
              $chave_pc80_data_final = implode('-', array_reverse(explode('/', $chave_pc20_data_final)));
              $sWhere = " pc20_dtate >= '$chave_pc20_data_inicial' AND pc20_dtate <= '$chave_pc20_data_final' ";
              $sql = $clpcorcam->sql_query(null, $camposControleData, "pc20_codorc desc", $sWhere);
          }
      } else {
          $sql = $clpcorcam->sql_query(null, $camposControleData, "pc20_codorc desc");
      }

      db_lovrot($sql ,15,"()","",$funcao_js,"","NoMe",array(),false);
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?php
if(!isset($pesquisa_chave)){
  ?>
  <script>
      const botaoLimpar = document.getElementById('limpar');
      const camposInput = document.getElementsByTagName('input');
      const listaComNomesInputs = ['chave_pc20_codorc', 'chave_pc20_data_inicial', 'chave_pc20_data_final'];

      function limpaCamposDeInput() {
          document.getElementById('chave_pc20_codorc').setAttribute('value', '');
          for (const input of camposInput) {
              if (listaComNomesInputs.includes(input.name)) {
                  input.value = '';
              }
          }
      }

      botaoLimpar.addEventListener('click', () => {
          limpaCamposDeInput();
          document.form2.submit();
      });
  </script>
    <?php
}
?>
