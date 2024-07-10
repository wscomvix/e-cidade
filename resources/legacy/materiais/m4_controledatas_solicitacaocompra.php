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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  dbtextFieldData.widget.js,
                  DBInputHora.widget.js,
                  datagrid/plugins/DBOrderRows.plugin.js,
                  datagrid/plugins/DBHint.plugin.js");

    db_app::load(
        "estilos.css,
    grid.style.css"
    );
    ?>
</head>

<body style='margin-top: 25px' bgcolor="#cccccc">
<form name="form1" id='frmSolicitacaoCompra' method="post">
    <center>
        <div style='display:table;'>
            <fieldset>
                <legend style="font-weight: bold">Solicita��o de Compra </legend>
                <table border='0'>
                    <tr>
                        <td nowrap title="� partir de qual data">
                            <?php db_ancora("C�digo de: ", "pesquisaCodigoSolicitacao(true, `inicial`);",1); ?>
                        </td>
                        <td>
                            <?php
                            db_input('iCodigoSolicitacaoInicial',4, true, 1, 'text', 1, "onchange='pesquisaCodigoSolicitacao(false, `inicial`)'");
                            ?>
                            <b><?php db_ancora('a', "pesquisaCodigoSolicitacao(true, `final`);",1); ?></b>
                            <?php
                            db_input('iCodigoSolicitacaoFinal', 4, true, 1, 'text', 1, "onchange='pesquisaCodigoSolicitacao(false, `final`)'");
                            ?>
                        </td>
                        <td>
                            <input name="btnProcessar" id="btnProcessar" type="button" value="Processar"  onclick='consultaCodigoSolicitacao();' >
                        </td>
                    </tr>
                    <tr>
                        <td nowrap title="Atualizar para">
                            <strong>Atualizar para:</strong>
                        </td>
                        <td>
                            <?php
                            db_inputdata('iAtualizarDataPara', "", "", "", true, 'text', 1)
                            ?>
                            <b></b>
                        </td>
                        <td>
                            <input name="btnAplicar" id="btnAplicar" type="button" value="Aplicar"  onclick='atualizarDataPara();' >
                        </td>
                    </tr>
                </table>
                <fieldset style='width:600px;'>
                    <div id='ctnGridSolicitacaoCompra'></div>
                </fieldset>
            </fieldset>
        </div>
        <input name="btnAtualizar" id="btnAtualizar" type="button" value="Atualizar"  onclick='atualizarSolicitacoesSelecionadas();' >
    </center>
</form>
</body>
<script>
    const sUrlRpc = 'm4_controledatas.RPC.php';
    const oGridSolicitacaoCompra          = new DBGrid('gridSolicitacaoCompra');
    oGridSolicitacaoCompra.nameInstance = 'oGridSolicitacaoCompra';
    oGridSolicitacaoCompra.setCheckbox(0);
    oGridSolicitacaoCompra.setCellWidth( [ '0%', '10%', '70%', '20%'] );
    oGridSolicitacaoCompra.setHeader( [ 'codigo', 'C�digo', 'Resumo', 'Data'] );
    oGridSolicitacaoCompra.setCellAlign( [ 'left', 'left', 'left', 'center'] );
    oGridSolicitacaoCompra.setHeight(130);
    oGridSolicitacaoCompra.aHeaders[1].lDisplayed = false;
    oGridSolicitacaoCompra.show($('ctnGridSolicitacaoCompra'));
    let aSolictacoesCompra = [];
    let sInicialFinal = '';

    function consultaCodigoSolicitacao() {
        const oParametros = {};
        oParametros.exec = 'consultaCodigoSolicitacaoCompra';
        oParametros.codigoSolicitacaoInicial = $F('iCodigoSolicitacaoInicial');
        oParametros.codigoSolicitacaoFinal = $F('iCodigoSolicitacaoFinal');

        if (validaCodigoSolicitacaoCompra(oParametros.codigoSolicitacaoInicial)) {
            js_divCarregando('Aguarde, Atualizando leituras...<br>Esse procedimento pode levar algum tempo.', 'msgBox');
            new Ajax.Request(sUrlRpc, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: function(oResponse) {
                    const oRetorno = eval("(" + oResponse.responseText + ")");
                    aSolictacoesCompra = oRetorno.solicitacoesCompra;
                    js_removeObj('msgBox');
                    renderizaSolicitacaoCompra();
                    if (oRetorno.status === 2) {
                        alert(oRetorno.message.urlDecode());
                    }
                }
            });
        }
    }

    function validaCodigoSolicitacaoCompra(codigo) {
        if (typeof codigo === 'string' && codigo.trim() === '') {
           alert('O intervalo c�digo da solicita��o de compra n�o pode ser vazio!');
           oGridSolicitacaoCompra.clearAll(true);
           return;
        }

        return true;
    }

    function pesquisaCodigoSolicitacao(mostra, inicial_final) {
        sInicialFinal = inicial_final;
        const sAbreUrl = 'func_solicita.php?funcao_js=parent.preencheEscondeCodigoSolicitacao|pc10_numero';
        const deveAparecer = !!mostra;

        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_solicita', sAbreUrl, 'Pesquisa', deveAparecer);
    }

    function preencheEscondeCodigoSolicitacao(codigoSolicitacao) {
        if (codigoSolicitacao === '') {
            return;
        }

        const codigoSolicitacaoCompara = $F('iCodigoSolicitacaoInicial');

        if ((typeof sInicialFinal === 'string' && sInicialFinal === 'final')
            && Number(codigoSolicitacaoCompara) > Number(codigoSolicitacao)
        ) {
            alert('Informe uma solicita��o final maior que uma solicita��o inicial ' + codigoSolicitacaoCompara);
            return;
        }

        if (typeof sInicialFinal === 'string' && sInicialFinal === 'inicial') {
            document.querySelector(
                'form[name="form1"] input[name="iCodigoSolicitacaoInicial"]'
            ).value = codigoSolicitacao;
        }

        if (typeof sInicialFinal === 'string' && sInicialFinal === 'final') {
            document.querySelector(
                'form[name="form1"] input[name="iCodigoSolicitacaoFinal"]'
            ).value = codigoSolicitacao;
        }

        db_iframe_solicita.hide();
    }

    function renderizaSolicitacaoCompra() {
        oGridSolicitacaoCompra.clearAll(true);
        aSolictacoesCompra.each(function (oSolicitacaoCompra, iSolicitacaoCompra) {
            let aLinha = [];
            aLinha.push(oSolicitacaoCompra.codigo);
            aLinha.push(oSolicitacaoCompra.codigo);
            aLinha.push(oSolicitacaoCompra.resumo.urlDecode());
            const sDBDataFormatada = oSolicitacaoCompra.data
                .split('-')
                .reverse()
                .join('/');
            const sDBData = oSolicitacaoCompra.data.length ? sDBDataFormatada : '';
            const iData = new DBTextFieldData(
                'oDBTextFieldData' + iSolicitacaoCompra,
                'oDBTextFieldData' + iSolicitacaoCompra,
                sDBData,
                10
            ).toInnerHtml();
            aLinha.push(iData);
            oGridSolicitacaoCompra.addRow(aLinha);
        });
        oGridSolicitacaoCompra.renderRows();
    }

    function atualizarDataPara() {
        if (Array.isArray(aSolictacoesCompra) && !aSolictacoesCompra.length) {
            alert('Nenhuma solicita��o de compra listada para atualizar data!');
            return;
        }

        const iAtualizarDataPara = $F('iAtualizarDataPara');

        if (iAtualizarDataPara.length === 0) {
            alert('Insira uma data para atualizar a(s) solicita��o(�es) listada(s)!');
            return;
        }

        if (!oGridSolicitacaoCompra.getSelection('array').length) {
            alert('Nenhuma solicita��o selecionada para atualizar data!');
            return;
        }

        const iLinhas = oGridSolicitacaoCompra.aRows.length;

        for (let i = 0; i < iLinhas; i++) {
            if (oGridSolicitacaoCompra.aRows[i].isSelected) {
                let oCheckGrid = document.getElementById(
                    oGridSolicitacaoCompra.aRows[i].aCells[4].getId()
                ).firstChild;

                oCheckGrid.value = iAtualizarDataPara;
            }
        }
    }

    function atualizarSolicitacoesSelecionadas() {
        const aSolicitacoesSelecionadas = oGridSolicitacaoCompra.getSelection('array');

        if (Array.isArray(aSolicitacoesSelecionadas) && !aSolicitacoesSelecionadas.length) {
            alert('Selecione uma solicita��o de compra para atualizar!');
            return;
        }

        let aSolicitacoesParaAtualizacao = new Array(aSolicitacoesSelecionadas.length)
            .fill(null)
            .map(() => ({
                codigo: 0,
                data: '',
            }));

        aSolicitacoesSelecionadas.each(
            function (oSolicitacaoSelecionada, iSolicitacacaoSelecionada) {
                aSolicitacoesParaAtualizacao[iSolicitacacaoSelecionada].codigo =
                    oSolicitacaoSelecionada[1];
                aSolicitacoesParaAtualizacao[iSolicitacacaoSelecionada].data =
                    oSolicitacaoSelecionada[4];
            }
        );

        const verificaDatasVaziasNoGrid = aSolicitacoesParaAtualizacao.some(
            (item) => typeof item === 'object' && item.data === ''
        );

        if (verificaDatasVaziasNoGrid) {
            alert('Insira uma data para atualizar a(s) solicita��o(�es)!');
            return;
        }

        js_divCarregando('Aguarde, atualizando os dados!', 'msgBox');
        const oParametros = {};
        oParametros.exec = 'atualizarDatasSolictacoesCompra';
        oParametros.solicitacoesParaAtualizacao  =  aSolicitacoesParaAtualizacao;
        new Ajax.Request(sUrlRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function (oResponse) {
                js_removeObj('msgBox');
                const oRetorno = eval("(" + oResponse.responseText + ")");
                if (oRetorno.status === 1) {
                    alert('Atualizado(s) com sucesso.');
                } else {
                    alert(oRetorno.message.urlDecode());
                }
            }
        });
    }
</script>

</html>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
