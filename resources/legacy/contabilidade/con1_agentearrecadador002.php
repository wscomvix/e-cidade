<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_agentearrecadador_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clagentearrecadador    = new cl_agentearrecadador;

$db_opcao        = 22;
$db_botao        = false;

if (isset($alterar)) {

    $db_opcao = 2;
    $lErro    = false;

    db_inicio_transacao();

    $clagentearrecadador->k174_codigobanco = $k174_codigobanco;
    $clagentearrecadador->k174_descricao = $k174_descricao;
    $clagentearrecadador->k174_idcontabancaria = $k174_idcontabancaria;
    $clagentearrecadador->k174_numcgm = $k174_numcgm;
    $clagentearrecadador->alterar($k174_sequencial);
    $sMsgErro = $clagentearrecadador->erro_msg;
    if ($clagentearrecadador->erro_status == "0") {
        $lErro = true;
    }

    db_fim_transacao($lErro);

} else if (isset($chavepesquisa)) {
    $db_opcao = 2;
    $result = $clagentearrecadador->sql_record($clagentearrecadador->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
    $db_botao = true;
}
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
if (isset($alterar)) {

    if (!$lErro) {
        db_msgbox($sMsgErro);
    } else {

        $db_botao = true;
        db_msgbox($sMsgErro);
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clagentearrecadador->erro_campo != "") {

            echo "<script> document.form1." . $clagentearrecadador->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clagentearrecadador->erro_campo . ".focus();</script>";
        }
    }
}

if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>