<?php
//MODULO: licitacao
include("dbforms/db_classesgenericas.php");
$clhomologacaoadjudica->rotulo->label();

$cliframe_seleciona = new cl_iframe_seleciona;
$clpcprocitem       = new cl_pcprocitem;
$clrotulo           = new rotulocampo;


$clrotulo->label("l20_codigo");

if (isset($_GET['lic'])) {
    $valor = $_GET['lic'];
    $l202_licitacao = $_GET['lic'];
    $l202_sequencial = $_GET['seq'];
    $pc50_descr = $_GET['descricao'];
    $respHomologcodigo = $_GET['codigo'];
    $respHomolognome = $_GET['nome'];
    $l202_datahomologacao = $_GET['data'];

    $valordata = explode('/', $l202_datahomologacao);

    $l202_datahomologacao_dia = $valordata[0];
    $l202_datahomologacao_mes =  $valordata[1];
    $l202_datahomologacao_ano =  $valordata[2];

?>
    <input type="hidden" id="teste2" value="<? echo $valor; ?>">
<?php
} else { ?>
    <input type="hidden" id="teste2" value="2">
<?php
}

db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<form name="form1" method="post" action="">
    <table border="0">
        <tr>
            <td nowrap title="<?= @$Tl202_sequencial ?>">
                <?= @$Ll202_sequencial ?>
            </td>
            <td>
                <?
                db_input('l202_sequencial', 10, $Il202_sequencial, true, 'text', 3, "")
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap title="<?= @$Tl202_licitacao ?>">
                <?
                db_ancora(@$Ll202_licitacao, "js_pesquisal202_licitacao(true);", $db_opcao);
                ?>
            </td>
            <td>
                <?
                db_input('l202_licitacao', 10, $Il202_licitacao, true, 'text', 3, " onchange='js_pesquisal202_licitacao(false);'")
                ?>
                <?
                $pc50_descr = $pc50_descr . " " . $l20_numero;
                db_input('pc50_descr', 40, $Ipc50_descr, true, 'text', 3, '')
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap title="respHomologcodigo">
                <?
                db_ancora("Resp. pela Homologa��o:", "js_pesquisal31_numcgm(true,'respHomologcodigo','respHomolognome');", $db_opcao)

                ?>
            </td>
            <td>
                <?
                db_input('respHomologcodigo', 10, $IrespHomologcodigo, true, 'text', $db_opcao, "onchange='js_pesquisal31_numcgm(false);';");
                db_input('respHomolognome', 45, $IrespHomolognome, true, 'text', 3, "");
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap title="<?= @$Tl202_datahomologacao ?>">
                <?= @$Ll202_datahomologacao ?>
            </td>
            <td>
                <?
                db_inputdata('l202_datahomologacao', @$l202_datahomologacao_dia, @$l202_datahomologacao_mes, @$l202_datahomologacao_ano, true, 'text', $db_opcao, "")
                ?>
                <input type="hidden" id="valor">
            </td>
        </tr>
        <tr  id="trdatareferencia" style="display:none;">
            <td nowrap title="<?= @$Tl202_datareferencia ?>">
                <?= @$Ll202_datareferencia ?>
            </td>
            <td>
                <?
                db_inputdata('l202_datareferencia', @$l202_datareferencia_dia, @$l202_datareferencia_mes, @$l202_datareferencia_ano, true, 'text', $db_opcao, "")
                ?>
            </td>
        </tr>
    </table>
    <br>
    <div>
        <?php
        if ($db_opcao == "1") {
            echo " <input type='button' value='Incluir' onclick='js_salvarHomologacao();'>";
        } elseif ($db_opcao == "2") {
            echo " <input type='button' value='Alterar' onclick='js_alterarHomologacao();'>";
        } else {
            echo " <input type='button' value='Excluir' onclick='js_excluirHomologacao();'>";
        }
        ?>
        <input type="button" value="Pesquisar" onclick="js_pesquisal202_licitacao(true);">
        <input type="button" value="Gerar Relat�rio" onclick="js_gerarRelatorio(true);">
    </div>
    <br>
    <fieldset>
        <legend><b>Itens</b></legend>
        <div id='cntgriditens'></div>
    </fieldset>
</form>
<script>
    <?php
    /**
     * ValidaFornecedor:
     * Quando for passado por URL o parametro validafornecedor, s� ir� retornar licita��es que possuem fornecedores habilitados.
     * @see ocorr�ncia 2278
     */
    ?>

    function js_showGrid() {
        let opcao = "<?= $db_opcao ?>";
        oGridItens = new DBGrid('gridItens');
        oGridItens.nameInstance = 'oGridItens';
        if (opcao != 2) {
            oGridItens.setCheckbox(0);
        }
        oGridItens.setCellAlign(new Array("center", "center", "center", "center", "center", 'center', 'center', 'center', 'center'));
        oGridItens.setCellWidth(new Array("5%", "5%", "35%", '15%', '5%', '25%', '15%', '8%', '8%'));
        oGridItens.setHeader(new Array("C�digo", "Ordem", "Material", "Lote", "CGM", "Fornecedores", "Unidade", "Qtde Licitada", "Valor Licitado"));
        oGridItens.hasTotalValue = true;
        oGridItens.show($('cntgriditens'));

        var width = $('cntgriditens').scrollWidth - 30;
        $("table" + oGridItens.sName + "header").style.width = width;
        $(oGridItens.sName + "body").style.width = width;
        $("table" + oGridItens.sName + "footer").style.width = width;
    }
    js_pesquisal202_licitacao(true);

    function js_pesquisal202_licitacao(mostra) {
        let opcao = "<?= $db_opcao ?>";
        var situacao = 0;
        var homologacao = 0;
        if (opcao == 1) {
            situacao = 1;
            homologacao = 1;
        } else {
            situacao = 10;
            homologacao = 2;
        }
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_liclicita', 'func_lichomologa.php?situacao=' + situacao +
                '&funcao_js=parent.js_mostraliclicita1|l20_codigo|l20_objeto|l20_numero|l202_datahomologacao|l202_sequencial&validafornecedor=1&homologacao=' + homologacao, 'Pesquisa', true);
        } else {
            if (document.form1.l202_licitacao.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_liclicita', 'func_lichomologa.php?situacao=' + (opcao == '1' ? '1' : '10') +
                    '&pesquisa_chave=' + document.form1.l202_licitacao.value + '&funcao_js=parent.js_mostraliclicita&validafornecedor=1', 'Pesquisa', false);
            } else {
                document.form1.l202_licitacao.value = '';
                document.form1.pc50_descr.value = '';
                js_init()
            }

        }
    }

    function js_mostraliclicita(chave, erro) {

        document.form1.pc50_descr.value = chave;
        if (erro == true) {
            iLicitacao = '';
            document.form1.l202_licitacao.focus();
            document.form1.l202_licitacao.value = '';
        } else {
            iLicitacao = document.form1.l202_licitacao.value;
            js_init()
        }
    }
    /**
     * Fun��o alterada para receber o parametro da numera��o da modalidade.
     * Acrescentado o parametro chave3 que recebe o l20_numero vindo da linha 263.
     * Solicitado por danilo@contass e deborah@contass
     */
    function js_mostraliclicita1(chave1, chave2, chave3, chave4, chave5) {
        iLicitacao = chave1;
        document.form1.l202_licitacao.value = chave1;
        document.form1.pc50_descr.value = chave2;
        let opcao = "<?= $db_opcao ?>";
        js_getReponsavel();
        if (opcao != 1) {
            aData = chave4.split('-');
            let dataHomo = aData[2] + '/' + aData[1] + '/' + aData[0];
            document.form1.l202_datahomologacao.value = dataHomo;
            document.form1.l202_sequencial.value = chave5;
        }
        db_iframe_liclicita.hide();

        js_init()
    }

    function js_init() {
        js_showGrid();
        js_getItens();
    }

    function js_getItens() {
        oGridItens.clearAll(true);
        var oParam = new Object();
        oParam.iLicitacao = $F('l202_licitacao');
        oParam.iHomologacao = $F('l202_sequencial');
        oParam.dbopcao = "<?= $db_opcao ?>";
        oParam.exec = "getItens";
        js_divCarregando('Aguarde, pesquisando Itens', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_homologacaoadjudica.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoGetItens
            }
        );
    }

    function js_pesquisa(homologacao = false) {
        if (!homologacao) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_homologacaoadjudica', 'func_homologacaoadjudica.php?validadispensa=true&situacao=1&funcao_js=parent.js_preenchepesquisa|l202_sequencial', 'Pesquisa', true);
        } else {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_homologacaoadjudica', 'func_homologacaoadjudica.php?validadispensa=true&situacao=10&funcao_js=parent.js_preenchepesquisa|l202_sequencial', 'Pesquisa', true);
        }
    }

    function js_retornoGetItens(oAjax) {

        js_removeObj('msgBox');
        oGridItens.clearAll(true);
        var aEventsIn = ["onmouseover"];
        var aEventsOut = ["onmouseout"];
        aDadosHintGrid = new Array();
        aDadosHintGridlote = new Array();

        var oRetornoitens = JSON.parse(oAjax.responseText);

        var nTotal = new Number(0);

        if (oRetornoitens.status == 1) {

            var seq = 0;
            oRetornoitens.itens.each(function(oLinha, iLinha) {
                seq++;
                var aLinha = new Array();
                aLinha[0] = oLinha.pc81_codprocitem;
                aLinha[1] = oLinha.pc11_seq;
                aLinha[2] = oLinha.pc01_descrmater.urlDecode();
                aLinha[3] = oLinha.l04_descricao.urlDecode();
                aLinha[4] = oLinha.z01_numcgm;
                aLinha[5] = oLinha.z01_nome.urlDecode();
                aLinha[6] = oLinha.m61_descr.urlDecode();
                aLinha[7] = oLinha.pc11_quant;
                aLinha[8] = oLinha.pc23_valor;
                oGridItens.addRow(aLinha);
                nTotal = nTotal + Number(oLinha.pc23_valor);

                var sTextEvent = " ";

                if (aLinha[2] !== '') {
                    sTextEvent += "<b>Material: </b>" + aLinha[2];
                } else {
                    sTextEvent += "<b>Nenhum dado � mostrar</b>";
                }

                var oDadosHint = new Object();
                oDadosHint.idLinha = `gridItensrowgridItens${iLinha}`;
                oDadosHint.sText = sTextEvent;
                aDadosHintGrid.push(oDadosHint);

                /*LOTE*/
                var sTextEventlote = " ";

                if (aLinha[3] !== '') {
                    sTextEventlote += "<b>Lote: </b>" + aLinha[3];
                } else {
                    sTextEventlote += "<b>Nenhum dado � mostrar</b>";
                }

                var oDadosHintlote = new Object();
                oDadosHintlote.idLinha = `gridItensrowgridItens${iLinha}`;
                oDadosHintlote.sTextlote = sTextEventlote;
                aDadosHintGridlote.push(oDadosHintlote);
            });
            document.getElementById('gridItenstotalValue').innerText = js_formatar(nTotal, 'f');
            document.getElementById('valor').value = js_formatar(nTotal, 'f');

            oGridItens.renderRows();

            aDadosHintGrid.each(function(oHint, id) {
                var oDBHint = eval("oDBHint_" + id + " = new DBHint('oDBHint_" + id + "')");
                oDBHint.setText(oHint.sText);
                oDBHint.setShowEvents(aEventsIn);
                oDBHint.setHideEvents(aEventsOut);
                oDBHint.setPosition('B', 'L');
                oDBHint.setUseMouse(true);
                oDBHint.make($(oHint.idLinha), 3);
            });

            aDadosHintGridlote.each(function(oHintlote, id) {
                var oDBHintlote = eval("oDBHintlote_" + id + " = new DBHint('oDBHintlote_" + id + "')");
                oDBHintlote.setText(oHintlote.sTextlote);
                oDBHintlote.setShowEvents(aEventsIn);
                oDBHintlote.setHideEvents(aEventsOut);
                oDBHintlote.setPosition('B', 'L');
                oDBHintlote.setUseMouse(true);
                oDBHintlote.make($(oHintlote.idLinha), 4);
            });

        }
    }

    function js_gerarRelatorio() {

        var iHeight = 200;
        var iWidth = 300;
        windowDotacaoItem = new windowAux('wndDotacoesItem',
            'Gerar Relag�rio ',
            iWidth,
            iHeight
        );

        var sContent = "<div style='margin-top:30px;'>";
        sContent += "<fieldset>";
        sContent += "<legend>Gerar Relat�rio de Homologa��o em:</legend>";
        sContent += "  <div id=''>";
        sContent += "  <input type='checkbox' id='pdf' name='PDF'>";
        sContent += "  <label>PDF</label>";
        sContent += "  </div>";
        sContent += "  <div id=''>";
        sContent += "  <input type='checkbox' id='word' name='WORD'>";
        sContent += "  <label>WORD</label>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "<center>";
        sContent += "<input type='button' id='btnGerar' value='Confirmar' onclick='gerar()'>";
        sContent += "</center>";
        sContent += "</div>";
        windowDotacaoItem.setContent(sContent);
        windowDotacaoItem.show();

    }

    function gerar() {
        var pdf = document.getElementById("pdf");
        var word = document.getElementById("word");
        var ilicita = document.getElementById("l202_licitacao").value;
        var sequencial = document.getElementById("l202_sequencial").value;
        var nome = document.getElementById("respHomolognome").value;
        var data = document.getElementById("l202_datahomologacao").value;
        var valor = document.getElementById('valor').value;

        if (pdf.checked) {
            jan = window.open('lic1_homologacaoadjudica004.php?impjust=$impjustificativa&codigo_preco=' + ilicita + '&nome=' + nome + '&sequencial=' + sequencial + '&data=' + data + '&valor=' + valor + '&quant_casas=2&tipoprecoreferencia=',
                'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
            jan.moveTo(0, 0);
        } else if (word.checked) {
            jan = window.open('lic1_homologacaoadjudica005.php?impjust=$impjustificativa&codigo_preco=' + ilicita + '&nome=' + nome + '&sequencial=' + sequencial + '&data=' + data + '&valor=' + valor + '&quant_casas=2&tipoprecoreferencia=',
                'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
            jan.moveTo(0, 0);
        }
        windowDotacaoItem.destroy();
    }

    function js_getReponsavel() {

        var oParam = new Object();
        oParam.iLicitacao = $F('l202_licitacao');
        oParam.exec = "getResponsavel";
        //js_divCarregando('Aguarde, pesquisando Itens', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_homologacaoadjudica.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoGetResponsavel
            }
        );
    }

    function js_retornoGetResponsavel(oAjax) {

        //js_removeObj('msgBox');


        var oRetornoitens = JSON.parse(oAjax.responseText);
        oRetornoitens.itens.each(function(oLinha, iLinha) {
            document.getElementById("respHomologcodigo").value = oLinha.codigo;
            document.getElementById("respHomolognome").value = oLinha.nome;

        });

    }

    function js_salvarHomologacao() {

        var aItens = oGridItens.getSelection("object");
        if (aItens.length == 0) {
            alert('Nenhum item Selecionado');
            return false;
        }

        var oParam = new Object();
        oParam.iLicitacao = $F('l202_licitacao');
        oParam.dtHomologacao = $F('l202_datahomologacao');
        oParam.iHomologacao = $F('l202_sequencial');
        oParam.respHomologcodigo = $F('respHomologcodigo');
        oParam.dataReferencia = $F('l202_datareferencia');

        oParam.possuiDataReferencia = document.getElementById('trdatareferencia').style.display == "none" ? false : true;

        if (oParam.respHomologcodigo == "") {
            alert('Campo Respons�vel pela Homologa��o n�o informado');
            return false;
        }
        if (oParam.dtHomologacao == "") {
            alert('Campo Data da Homologa��o n�o informado');
            return false;
        }

        oParam.aItens = new Array();
        oParam.exec = "homologarLicitacao";
        oParam.sCodigoItens = "";
        for (var i = 0; i < aItens.length; i++) {

            with(aItens[i]) {
                var oItem = new Object();
                oItem.codigo = aCells[0].getValue();
                oItem.fornecedor = aCells[5].getValue();
                oParam.sCodigoItens += aCells[0].getValue() + ",";
                oParam.aItens.push(oItem);
            }
        }

        oParam.sCodigoItens = oParam.sCodigoItens.substring(0, oParam.sCodigoItens.length - 1);

        js_divCarregando('Aguarde, Homologando Licitacao', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_homologacaoadjudica.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoHomologacao
            }
        );
    }

    function js_retornoHomologacao(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.status == '1') {
            alert(oRetorno.message.urlDecode());
            oGridItens.clearAll(true);
            document.getElementById('l202_sequencial').value = oRetorno.sequencial;
            document.getElementById('pc50_descr').value = '';
            document.getElementById('l202_datahomologacao').value = '';
            document.getElementById('respHomologcodigo').value = '';
            document.getElementById('respHomolognome').value = '';
            if (oRetorno.regpreco == 2) {
                alert('Confira a vig�ncia do registro de pre�o');
                window.location.replace('com4_vigenciaregistropreco001.php?pc54_solicita=' + oRetorno.pc10_numero);
                return;
            } 

            licitacao = $F('l202_licitacao');
            descricao = $F('pc50_descr');
            data = $F('l202_datahomologacao');
            codigo = $F('respHomologcodigo');
            nome = $F('respHomolognome');
            sequencial = oRetorno.sequencial;
            window.location.replace("lic1_homologacaoadjudica002.php?lic=" + licitacao + "&seq=" + sequencial + "&nome=" + nome + "&codigo=" + codigo + "&descricao=" + descricao + "&data=" + data);
            return;
            
        } 

        if (oRetorno.periodosicomencerrado) {
            document.getElementById('trdatareferencia').style.display = '';
        }

        alert(oRetorno.message.urlDecode());
        return;
        
    }

    function js_alterarHomologacao() {
        var oParam = new Object();
        oParam.iLicitacao = $F('l202_licitacao');
        oParam.dtHomologacao = $F('l202_datahomologacao');
        oParam.iHomologacao = $F('l202_sequencial');
        oParam.respHomologcodigo = $F('respHomologcodigo');
        oParam.dataReferencia = $F('l202_datareferencia');
        oParam.possuiDataReferencia = document.getElementById('trdatareferencia').style.display == "none" ? false : true;
        
        if (oParam.respHomologcodigo == "") {
            alert('Campo Respons�vel pela Homologa��o n�o informado');
            return false;
        }
        oParam.exec = "alterarHomologacao";
        js_divCarregando('Aguarde, alterando Homologando', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_homologacaoadjudica.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoAlterarHomologacao
            }
        );
    }

    function js_retornoAlterarHomologacao(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.status == '1') {
            alert(oRetorno.message.urlDecode());
            oGridItens.clearAll(true);
            document.getElementById('l202_licitacao').value = '';
            document.getElementById('pc50_descr').value = '';
            document.getElementById('l202_datahomologacao').value = '';
            document.getElementById('l202_sequencial').value = '';
            document.getElementById('respHomologcodigo').value = '';
            document.getElementById('respHomolognome').value = '';
            return;
        } 


        if (oRetorno.periodosicomencerrado) {
            document.getElementById('trdatareferencia').style.display = '';
        }

        alert(oRetorno.message.urlDecode());
        
    }

    function js_excluirHomologacao() {

        var aItens = oGridItens.getSelection("object");
        if (aItens.length == 0) {
            alert('Nenhum item Selecionado');
            return false;
        }

        var oParam = new Object();
        oParam.iLicitacao = $F('l202_licitacao');
        oParam.dtAdjudicacao = $F('l202_datahomologacao');
        oParam.iHomologacao = $F('l202_sequencial');
        oParam.aItens = new Array();
        oParam.exec = "excluirhomologacao";

        for (var i = 0; i < aItens.length; i++) {

            with(aItens[i]) {
                var oItem = new Object();
                oItem.codigo = aCells[0].getValue();
                oParam.aItens.push(oItem);
            }
        }
        js_divCarregando('Aguarde, Excluindo homologacao', 'msgBox');
        var oAjax = new Ajax.Request(
            'lic1_homologacaoadjudica.RPC.php', {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoExcluirHomologacao
            }
        );
    }

    function js_retornoExcluirHomologacao(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.status == '1') {
            alert(oRetorno.message.urlDecode());
            oGridItens.clearAll(true);
            document.getElementById('l202_licitacao').value = '';
            document.getElementById('pc50_descr').value = '';
            document.getElementById('l202_datahomologacao').value = '';
            document.getElementById('l202_sequencial').value = '';
            document.getElementById("respHomolognome").value = "";
            document.getElementById("respHomologcodigo").value = "";
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_somaItens() {}

    var varNumCampo;
    var varNomeCampo;

    function js_pesquisal31_numcgm(mostra, numCampo, nomeCampo) {
        varNumCampo = numCampo;
        varNomeCampo = nomeCampo;

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&filtro=1', 'Pesquisa', true, '0', '1');
        } else {

            numcgm = document.getElementById("respHomologcodigo").value;
            if (numcgm != '') {
                js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.js_mostracgm&filtro=1', 'Pesquisa', false);
            } else {
                document.getElementById(numCampo).value = "";
            }
        }
    }

    function js_mostracgm(erro, chave) {
        document.getElementById("respHomolognome").value = chave;
        if (erro == true) {
            //  document.form1.l31_numcgm.focus();
            document.getElementById("respHomolognome").value = "";
            document.getElementById("respHomologcodigo").value = "";
            alert("Respons�vel n�o encontrado");
        }
    }

    function js_mostracgm1(chave1, chave2) {

        document.getElementById(varNumCampo).value = chave1;
        document.getElementById(varNomeCampo).value = chave2;
        db_iframe_cgm.hide();
    }

    js_showGrid();
</script>