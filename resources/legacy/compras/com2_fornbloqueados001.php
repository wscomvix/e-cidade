<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>

<html>
<style>
    #tipo_fornecedor{
        width: 100%;
    }
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
  <fieldset style="width: 43%;margin-top: 5%">
    <legend>Fornecedores Bloqueados</legend>
    <table  align="center">
      <form name="form1" method="post" action="">
        <tr>
          <td >&nbsp;</td>
          <td >&nbsp;</td>
        </tr>
        <tr >
          <td align="left" nowrap title="Per�odo" >
            <strong>Per�odo :&nbsp;&nbsp;</strong>
          </td>
          <td>

            <?php db_inputdata("data_ini",'', '', '', true, "text", 4, "", "data_ini"); ?> a
            <?php db_inputdata("data_fim",'', '', '', true, "text", 4, "", "data_fim"); ?>

          </td>
        </tr>
          <tr >
              <td align="left" nowrap title="Per�odo" >
                  <strong>Filtrar por:&nbsp;&nbsp;</strong>
              </td>
              <td>
                  <?php
                  $aTiposFornecedor = array("t" => "Todos", "a" => "Impedimentos n�o vigentes", "i" => "Impedimentos vigentes");
                  db_select("tipo_fornecedor", $aTiposFornecedor, true, $db_opcao);
                  ?>
			  </td>
          </tr>
        <tr>
          <td >&nbsp;</td>
          <td >&nbsp;</td>
        </tr>

      </form>
    </table>
  </fieldset>
  <table>
    <tr>
      <td>
        <input  name="emite2" id="emite2" type="button" value="Emitir Relat�rio" onclick="js_emite();" >
      </td>
    </tr>
  </table>
</center>



<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>


  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
</html>
<script>
    function js_emite(){
        let error_msg = '';

        if(!document.form1.data_fim.value){
            error_msg = 'Informe uma Data Final!';
        }

        if(!document.form1.data_ini.value){
            error_msg = 'Informe uma Data de In�cio!';
        }

        if(!document.form1.data_fim.value && !document.form1.data_ini.value){
            error_msg = 'Nenhum per�odo informado. Verifique!';
        }

        if(document.form1.data_fim.value < document.form1.data_ini.value){
            error_msg = 'Data Final � menor que a Data Inicial. Verifique!';
        }

        if(error_msg){
           alert(error_msg);
           return false;
        }

        jan = window.open('com2_fornbloqueados002.php?data_ini='+document.form1.data_ini.value+'&data_fim='+document.form1.data_fim.value+
            '&tipo_fornecedor='+document.form1.tipo_fornecedor.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
  }
</script>
