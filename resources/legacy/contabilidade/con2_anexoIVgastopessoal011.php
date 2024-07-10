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
include("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);
$anousu = db_getsession("DB_anousu");
$codigoRelatorio = 4000003;

$oRelatorio = new relatorioContabil($codigoRelatorio);

$aPeriodos = $oRelatorio->getPeriodos();
$aListaPeriodos = array();
$aListaPeriodos[0] = "Selecione";
foreach ($aPeriodos as $oPeriodo) {
    $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
}
?>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

    <script>
        variavel = 1;

        function js_emite(tipo) {
            sel_instit = new Number(document.form1.db_selinstit.value);
            if (sel_instit == 0) {
                alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
                return false;
            }
            if (tipo == 2) {
                document.form1.action = "orc2_despitemdesdobramento002_csv.php";
            } else {
                document.form1.action = "con2_anexoIVgastopessoal002.php";
            }

            if (document.form1.o116_periodo.value == 0) {
                alert("Voc� n�o escolheu um per�odo. Verifique!");
                return false;
            }

            if (document.form1.tipoEmissao.value == 0) {
                alert("Voc� n�o escolheu um tipo de emiss�o. Verifique!");
                return false;
            }

            if (document.form1.tipoCalculo.value == 0) {
                alert("Voc� n�o escolheu um c�lculo com base nos valores. Verifique!");
                return false;
            }
            // pega dados da func_selorcdotacao_aba.php
            jan = window.open('', 'safo' + variavel, 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
            document.form1.target = 'safo' + variavel++;
            setTimeout("document.form1.submit()", 1000);
            return true;
        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        #o116_periodo,
        #tipoCalculo {
            width: 130px;
        }
        #tipoEmissao {
            width: 130px;
        }
    </style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <table align="center">
        <form name="form1" method="post" action="con2_anexoIVgastopessoal002.php">
            <input type=hidden name="filtra_despesa" value="">
            <input type=hidden name="anousu" value="<?= $anousu ?>">
            <input type=hidden name="codigorelatorio" value="<?= $codigoRelatorio ?>">
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan=3 class='table_header'>
                    Anexo IV - Gasto com Pessoal
                </td>
            </tr>
            <tr>
                <td align="center" colspan="3">
                    <?
                    db_selinstit('parent.js_limpa', 300, 100);
                    // ja tem institui��o na aba filtros
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td><label for="o116_periodo"><b>Per�odo:</b></label></td>
                <td><?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?></td>
            </tr>

            <tr>
                <td><label><b>Emiss�o:</b></label></td>
                <?
                $aEmissao = array(
                    '0' => 'Selecione',
                    '1' => 'Publica��o Oficial',
                    '2' => 'Detalhamento Mensal'
                );
                ?>
                <td><?php db_select("tipoEmissao", $aEmissao, true, 1); ?></td>
            </tr>

            <input type="hidden" name="tipoCalculo" value="2" />

            <tr>
                <td>&nbsp; </td>
            </tr>


            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input name="emiterel" value="Gera Relat�rio" type="button" onclick="js_emite(1);">
                    <!--<input name="emiterelcsv" value="Gera CSV" type="button" onclick="js_emite(2);">-->
                </td>
            </tr>

        </form>
    </table>
</body>

</html>
