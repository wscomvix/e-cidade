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
include("libs/db_utils.php");
include("classes/db_agentearrecadador_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
*/
$clagentearrecadador = new cl_agentearrecadador;

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {
    $lerro    = false;
    $erro_msg = "";

    db_inicio_transacao();

    $clagentearrecadador->k174_codigobanco = $k174_codigobanco;
    $clagentearrecadador->k174_descricao = $k174_descricao;
    $clagentearrecadador->k174_idcontabancaria = $k174_idcontabancaria;
    $clagentearrecadador->k174_numcgm = $k174_numcgm;
    $clagentearrecadador->k174_instit = db_getsession("DB_instit");
    $clagentearrecadador->incluir();

    $erro_msg = $clagentearrecadador->erro_msg;
    if ($clagentearrecadador->erro_status == "0") {
        $lerro  = true;
    }

    db_fim_transacao($lerro);
}

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="680" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td valign="top">
                <?
                include("forms/db_frmagentearrecadador.php");
                ?>
            </td>
        </tr>
    </table>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<?

if (isset($incluir)) {
    if (!$lerro) {
        echo "<script>  </script>";
        db_msgbox($erro_msg);
    } else {
        $db_botao = true;
        db_msgbox($erro_msg);
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clagentearrecadador->erro_campo != "") {
            echo "<script> document.form1." . $clagentearrecadador->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clagentearrecadador->erro_campo . ".focus();</script>";
        }
    }
}
?>