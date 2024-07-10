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
include("classes/db_orcparamrel_classe.php");
include("classes/db_orcparamelemento_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post">
<table border="0" cellspacing="0" cellpadding="0">
  <? 
  $clorcparamelemento = new cl_orcparamelemento;
  //echo ($clorcparamelemento->sql_query_elemento(db_getsession("DB_anousu"),$o42_codparrel));
  $result = $clorcparamelemento->sql_record($clorcparamelemento->sql_query_elemento(db_getsession("DB_anousu"),$o42_codparrel));
  if($clorcparamelemento->numrows>0){
    for($i=0;$i<$clorcparamelemento->numrows;$i++){
      db_fieldsmemory($result,$i);
      ?>
      <tr> 
        <td align="left" nowrap><strong><?=$o56_elemento?></strong></td>
        <td align="left" nowrap><input type="checkbox" value="<?$o56_codele?>" name="<?=$o56_codele?>" <?=($o44_codparrel!=null?"checked":"")?>></td>
        <td align="left" nowrap><?=$o56_descr?></td>
      </tr>
      <?
    }
  }
  ?>
</table>
</form>
</body>
</html>