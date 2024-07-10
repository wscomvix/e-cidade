<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
?>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
    db_app::load("widgets/dbmessageBoard.widget.js, widgets/windowAux.widget.js, datagrid.widget.js");
    db_app::load("classes/DBViewAcordoDotacaoItens.classe.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
</head>

<body style="background-color: #cccccc; margin-top: 35px">
    <center>
        <div style="display: table;" id='pesquisa-solicitacoes'>
            <fieldset>
                <legend><b>Altera��o/Inclus�o de Dota��o</legend>
                <table>
                    <tr>
                        <td nowrap title="<?php echo $Tac16_sequencial; ?>" width="130">
                            <?php db_ancora($Lac16_sequencial, "js_acordo(true);", 1); ?>
                        </td>
                        <td colspan="2">
                            <?php
                            db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1, "onchange='js_acordo(false);'");
                            db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <input style='margin-top: 10px;' type="button" name='Alterar' value='Alterar' onclick="alteraAcordo();">
    </center>
</body>

</html>

<script type="text/javascript">
    function js_acordo(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_acordo',
                'func_acordoinstit.php?funcao_js=parent.js_mostraAcordo1|ac16_sequencial|z01_nome',
                'Pesquisa', true);
        } else {
            if ($F('ac16_sequencial').trim() != '') {
                js_OpenJanelaIframe('', 'db_iframe_depart',
                    'func_acordoinstit.php?pesquisa_chave=' + $F('ac16_sequencial') + '&funcao_js=parent.js_mostraAcordo' +
                    '&descricao=true',
                    'Pesquisa', false);
            } else {
                $('ac16_resumoobjeto').value = '';
            }
        }
    }

    function js_mostraAcordo(chave, descricao, erro) {

        $('ac16_resumoobjeto').value = descricao;
        if (erro == true) {
            $('ac16_sequencial').focus();
            $('ac16_sequencial').value = '';
        }
    }

    function js_mostraAcordo1(chave1, chave2) {
        $('ac16_sequencial').value = chave1;
        $('ac16_resumoobjeto').value = chave2;
        db_iframe_acordo.hide();
    }

    function alteraAcordo() {
        oViewSolicitacaoDotacao = new DBViewAcordoDotacaoItens(document.getElementById('ac16_sequencial').value, "oViewSolicitacaoDotacao");
        oViewSolicitacaoDotacao.getDotacoes();
        oViewSolicitacaoDotacao.onBeforeSave();
    }
</script>