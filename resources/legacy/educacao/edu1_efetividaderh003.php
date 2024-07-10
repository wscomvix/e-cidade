<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoEfetividadeRh = db_utils::getdao("efetividaderh");
$oDaoEfetividade   = db_utils::getdao("efetividade");
$db_botao          = false;
$db_opcao          = 33;
$db_opcao1         = 3;

if (isset($excluir)) {
  db_inicio_transacao();
  $db_opcao  = 3;
  $db_opcao1 = 3;
  $oDaoEfetividade->excluir(""," ed97_i_efetividaderh = $ed98_i_codigo");
  $oDaoEfetividadeRh->excluir($ed98_i_codigo);
  db_fim_transacao();
} elseif (isset($chavepesquisa)) {
	
  $db_opcao          = 3;
  $db_opcao1         = 3;
  $sSqlefetividadeRh = $oDaoEfetividadeRh->sql_query($chavepesquisa);
  $rsEfetividadeRh   = $oDaoEfetividadeRh->sql_record($sSqlefetividadeRh);
  db_fieldsmemory($rsEfetividadeRh, 0);
  $db_botao = true;
 
}

?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Informe a competÍncia da efetividade:</b></legend>
       <?include("forms/db_frmefetividaderh.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?
if (isset($excluir)) {
  if ($oDaoEfetividadeRh->erro_status == "0") {
    $oDaoEfetividadeRh->erro(true,false);
  } else {
    $oDaoEfetividadeRh->erro(true,true);
  }
}

if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>