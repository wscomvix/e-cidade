<?php
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
include("classes/db_db_contatos_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empelemento_classe.php");
include("classes/db_empanuladotipo_classe.php");
require_once("classes/db_empparametro_classe.php");

$clempempenho      = new cl_empempenho;
$clempelemento     = new cl_empelemento;
$clorcdotacao      = new cl_orcdotacao;
$clorctiporec      = new cl_orctiporec;
$clempparametro 	 = new cl_empparametro;

$rsEmpParametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));
$sTipoDeAnulacaoPadrao = db_utils::fieldsMemory($rsEmpParametro, 0)->e30_tipoanulacaopadrao;

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/notaliquidacao.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0">
    <tr height='25'>
        <td>&nbsp;</td>
        <center>
        </center>
        </td>
    </tr>
</table>
<center>
    <?php
    include_once "forms/db_frmliquidarRPproc.php";
    ?>
</center>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>