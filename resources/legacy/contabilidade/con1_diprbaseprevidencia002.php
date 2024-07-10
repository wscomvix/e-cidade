<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
include("libs/db_stdlib.php");
include("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_diprbaseprevidencia_classe.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldipr = new cl_diprbaseprevidencia;
$cldb_config = new cl_db_config;
$db_opcao = 22;
$db_botao = true;

if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar") {
    $sqlerro == false;
    if ($c238_datasicom != "") {
        $resultado = db_query("select c99_data from condataconf where c99_instit = " . db_getsession("DB_instit") . " and c99_anousu = " . db_getsession("DB_anousu"));
        db_fieldsmemory($resultado, 0)->c99_data;
        $c99_data = (implode("/", (array_reverse(explode("-", $c99_data)))));
        if ($sqlerro == false) {
            $dataEncerramentoContabil = DateTime::createFromFormat('d/m/Y', $c99_data);
            $dataReferenciaSICOM = DateTime::createFromFormat('d/m/Y', $c238_datasicom);

            if ($dataReferenciaSICOM < $dataEncerramentoContabil) {
                db_msgbox("Existe encerramento de per�odo cont�bil para a Data de Refer�ncia informada, o procedimento n�o poder� ser executado");
                $sqlerro = true;
            }
        }
    }
    if ($sqlerro == false) {
        db_inicio_transacao();
        $db_opcao = 2;
        $cldipr->alterar($c238_sequencial);
        db_fim_transacao();
    }
} else if (isset($chavepesquisa)) {
    $db_opcao = 2;
    $result = $cldipr->sql_record($cldipr->sql_query($chavepesquisa));
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
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        select {
            width: 280px;
        }
    </style>
</head>

<body style="background-color: #CCCCCC; margin-top: 30px;">
    <div class="container">
        <?php
        include("forms/db_diprbaseprevidencia.php");
        ?>
    </div>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {
    if ($cldipr->erro_status == "0") {
        $cldipr->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($cldipr->erro_campo != "") {
            echo "<script> document.form1." . $cldipr->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $cldipr->erro_campo . ".focus();</script>";
        };
    } else {
        $cldipr->erro(true, true);
    };
};
if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>