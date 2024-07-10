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
$clrotulo = new rotulocampo;
$clrotulo->label("k13_dtvlr");
$clrotulo->label("k13_conta");

$dados = "ordem";
require_once("std/db_stdClass.php");
$iTipoControleRetencaoMesAnterior = 0;
$lUsaData = true;
// Carrega parametros
$sSQL = "SELECT c99_data FROM condataconf WHERE c99_instit = " . db_getsession('DB_instit') . " AND c99_anousu = " . db_getsession("DB_anousu");
$rsResult = db_query($sSQL);
$encerramentoContabil = db_utils::fieldsMemory($rsResult, 0)->c99_data ? date("d/m/Y", strtotime(db_utils::fieldsMemory($rsResult, 0)->c99_data)) : "";
// Carrega os scripts
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("AjaxRequest.js");
db_app::load("widgets/windowAux.widget.js");
?>
<style type="text/css">
    #tabela-lancamentos {
        border-collapse: collapse;
        width: 98%;
        margin: 10px;
        border: 1px solid black;
    }
    #tabela-lancamentos tr {
        background-color: #fff;
    }
    #tabela-lancamentos td, #tabela-lancamentos th {
        padding: 5px;
        border: 1px solid #ddd;
}

#tabela-lancamentos tr:hover {background-color: #6a6a6a;}

#tabela-lancamentos th {

  text-align: left;
  background-color: #D3D3D3;
  font-weight: bold;
    color: #000;
}
    .pesquisaConta {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: none;
        overflow-y:auto;
        overflow-x: hidden;
        position: absolute;
        max-height: 200px;
    }

    .pesquisaConta li {
        border: 1px solid #ddd;
        margin-top: -1px;  /*Prevent double borders */
        background-color: #f6f6f6;
        padding: 10px;
        text-decoration: none;
        color: black;
        display: block
    }

    .pesquisaConta li:hover:not(.header) {
        background-color: #eee;
    }

    .codtipo {
        display: none;
    }

    .ctapag {
        width: 100%;
    }
</style>
<script>
    function js_mascara(evt){
        var evt = (evt) ? evt : (window.event) ? window.event : "";

        if((evt.charCode >46 && evt.charCode <58) || evt.charCode ==0){//8:backspace|46:delete|190:.
            return true;
        }else{
            return false;
        }
    }
</script>
<BR><BR>

<form name="form1" method="post" action="">
    <center>
        <table  border =0 style='width:90%'>
            <tr>
                <td>
                    <fieldset>
                        <legend><b>Concilia��o Banc�ria</b></legend>
                        <table width="100%">
                            <tr>
                                <td width="50%" valign="top">
                                    <fieldset class='filtros'>
                                        <legend>
                                            <b>Filtros</b>
                                        </legend>
                                        <table border="0" align="left" >
                                            <tr>
                                                <td><b>Data Inicial:</b></td>
                                                <td nowrap>
                                                    <? db_inputdata("data_inicial", null, null, null, true, "text", 1, "onchange='js_verifica_campos();'"); ?>
                                                </td>
                                                <td><b>Data Final:</b></td>
                                                <td nowrap align="">
                                                    <? db_inputdata("data_final", 10, null, null, true, "text", 1, "onchange='js_verifica_campos();'"); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?=@$Tk13_conta?>">
                                                    <? db_ancora(@$Lk13_conta, "js_pesquisak13_conta(true);", $db_opcao); ?>
                                                </td>
                                                <td  colspan='4' nowrap>
                                                    <?
                                                    db_input('k13_conta', 10, $Ik13_conta, true, 'text', $db_opcao, "onchange='js_pesquisak13_conta(false);'");
                                                    db_input('k13_descr', 42, $Ik13_descr, true, 'text', 3, '');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Tipo de Movimento:</b></td>
                                                <td align="left" colspan="4">
                                                    <?
                                                    $tipo_movimento = array("0" => "Todos", "E" => "Entrada", "S" => "Sa�da");
                                                    db_select("tipo_movimento", $tipo_movimento, true, 1, "style='width:100%'");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Tipo de Lan�amento:</b></td>
                                                <td align="left" colspan="4">
                                                    <?
                                                    $tipo_lancamento = array("Todos", "PGTO. EMPENHO", "EST. PGTO EMPENHO", "REC. ORCAMENTARIA",
                                                    "EST. REC. ORCAMENTARIA", "PGTO EXTRA ORCAMENTARIA", "EST. PGTO EXTRA ORCAMENTARIA",
                                                    "REC. EXTRA ORCAMENTARIA", "EST. REC. EXTRA ORCAMENTARIA", "PERDAS", "ESTORNO PERDAS",
                                                    "TRANSFERENCIA", "EST. TRANSFERENCIA", "PENDENCIA", "IMPLANTACAO");
                                                    db_select("tipo_lancamento", $tipo_lancamento, true, 1, "style='width:100%'");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Data da Concilia��o:</b></td>
                                                <td align="left">
                                                    <? db_inputdata("data_conciliacao", null, null, null, true, "text", 1) ?>
                                                </td>
                                                <td><b>Saldo Final Extrato:</b></td>
                                                <td align="left">
                                                    <? db_input("saldo_final_extrato", 16, 0, true, "text", 1, "onkeyup=\"js_ValidaCampos(this, 4, 'valor', false, null, event)\""); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Valor Total Selecionado:</b></td>
                                                <td align="left">
                                                    <? db_input("total_selecionado", 12, 0, true, "text", 1, "disabled"); ?>
                                                </td>
                                                <td><input type="checkbox" name="fechar_conciliacao" id="fechar_conciliacao"/><b>Fechar Concilia��o</b></td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>

                                <td rowspan="1" valign="top" height="100%">
                                    <fieldset class='filtros'>
                                        <legend><b>Saldos da Conta</b></legend>
                                        <table>
                                            <tr>
                                                <td style='color:blue' id='descrConta' colspan='4'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Saldo Final Tesouraria:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("saldo_inicial_tesouraria", 15, null, true, "text", 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Valores � D�bito:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("total_entradas", 15, null, true, "text", 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Valores � Cr�dito:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("total_saidas", 15, null, true, "text", 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Saldo Conciliado:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("saldo_conciliado", 15, null, true, "text", 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Diferen�a:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("diferenca", 15, null, true, "text", 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div style="height: 16.5px"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                                <td width='20%'>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan='4' style='text-align: center'>
                    <fieldset>
                        <input name="pesquisar" id='pesquisar' type="button"  value="Pesquisar" onclick='js_pesquisar_lancamentos_conciliacao();' />
                        <input name="atualizar" id='atualizar' type="button"  value="Conciliar" onclick="js_processar()" />
                        <input name="desprocessar" id='desprocessar' type="button" value='Desprocessar' onclick='js_desprocessar()' />
                        <input name="emitir_capa" id='emitir_capa' type="button" value='Emitir Concilia��o'/>
                        <input name="incluir_pendencias" id='incluir_pendencias' type="button" value='Incluir Pend�ncias' onclick='js_janelaPendencia()' />
                        <input name="nova_conciliacao" id='nova_conciliacao' type="button" value='Nova Concilia��o' onclick="js_reset()" />
                    </fieldset>
                </td>
            <tr>
                <td colspan='3'>
                    <fieldset>
                        <div id='gridLancamentos' style="width: 100%">
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan='5' align='left'>
                    <b><span >**</span>Exibir Lan�amentos</b>
                    <br />
                    <span>
                        <fieldset>
                            <legend><b>Exibir</b></legend>
                            <input type="checkbox" id='configuradas' checked onclick='js_showFiltro("conciliado", this.checked)' />
                            <label for="configuradas" style='padding:1px;border: 1px solid black; background-color:#d1f07c'>
                                <b>Conciliados</b>
                            </label>
                            <input type="checkbox" id='normais' checked onclick='js_showFiltro("normal", this.checked)' />
                            <label for="normais" style='padding:1px;border: 1px solid black;background-color:white'>
                                <b>N�o Conciliados</b>
                            </label>
                            <input type="checkbox" id='comMovs' checked onclick='js_showFiltro("pendente", this.checked)' />
                            <label for="comMovs" style='padding:1px;border: 1px solid black;background-color:rgb(222, 184, 135)'>
                                <b>Pend�ncia/Implanta��o</b>
                            </label>
                        </fieldset>
                    </span>
                </td>
            </tr>
        </table>
    </form>
</center>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<div id='teste'></div>
<script>
    var encerramento_contabil = "<?=$encerramentoContabil?>";
    var dados_complementares_numcgm = new Array();
    var dados_complementares_coddoc = new Array();
    var dados_complementares_oprecslip = new Array();
    var dados_complementares_documento = new Array();
    var dados_complementares_valor_individual = new Array();
    var iZerarSaldoFinalExtrato = 0;

    // ----------------------------------------
    // Fun��o verificadas
    js_reset();
    js_verifica_campos();
    js_init();

    function js_pesquisak13_conta(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_saltes', 'func_saltes.php?funcao_js=parent.js_preenche_k13_conta|k13_conta|k13_descr','Pesquisa', true);
        } else {
            if(document.form1.k13_conta.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_saltes', 'func_saltes.php?pesquisa_chave=' + document.form1.k13_conta.value + '&funcao_js=parent.js_preenche_k13_descr', 'Pesquisa', false);
            } else {
                document.form1.k13_conta.value = '';
            }
        }
    }

    function js_preenche_k13_descr(k13_descr, erro) {
        document.form1.k13_descr.value = k13_descr;
        if (erro == true) {
            document.form1.k13_conta.focus();
            document.form1.k13_conta = '';
        } else {
            js_verifica_campos();
        }
    }

    function js_preenche_k13_conta(k13_conta, k13_descr) {
        document.form1.k13_conta.value = k13_conta;
        document.form1.k13_descr.value = k13_descr;
        js_verifica_campos();
        db_iframe_saltes.hide();
    }

    document.form1.saldo_final_extrato.addEventListener('change', js_registraSaldoExtrato);
    document.form1.fechar_conciliacao.addEventListener('change', js_registraSaldoExtrato);

    function js_registraSaldoExtrato() {
        oParam                      = new Object();
        oParam.conta                = document.form1.k13_conta.value;
        oParam.data_final           = document.form1.data_final.value;
        oParam.saldo_final_extrato  = document.form1.saldo_final_extrato.value;
        if (document.form1.fechar_conciliacao.checked == true) {
            oParam.fechar_conciliacao = document.form1.data_final.value;
        } else {
            oParam.fechar_conciliacao = null;
        }

        var sParam  = js_objectToJson(oParam);
        var sJson   = '{"exec": "RegistrarSaldoExtrato", "params": ['+ sParam + ']}';
        var oAjax   = new Ajax.Request('cai4_conciliacaoBancariaNovo.RPC.php',
            {
                method    : 'post',
                parameters: 'json=' + sJson
            }
        );
        js_atualizar_diferenca();
    }

    function js_verifica_campos() {
        if (document.form1.data_inicial.value == "" || document.form1.data_final.value == "") {
            js_libera_botoes(false);
            return;
        }

        if (!js_verifica_datas())
            return false;

        if (!js_verifica_conta())
            return false;
    }

    function js_verifica_datas() {
        var dataInicial = js_formatar_data_reversa(document.form1.data_inicial.value);
        var dataFinal   = js_formatar_data_reversa(document.form1.data_final.value);

        if (dataInicial > dataFinal) {
            alert("Data Final inferior a Data Inicial!");
            document.form1.data_final.value = '';
            document.form1.data_final.focus();
            return false;
        }
        return true;
    }

    function js_formatar_data_reversa(data) {
        return new Date(data.split('/').reverse().join('/'));
    }

    function js_verifica_conta() {
        if (document.form1.k13_conta.value == "" || document.form1.k13_descr.value == "") {
            js_libera_botoes(false);
            return false;
        }

        js_busca_extrato();
        gridLancamentos.clearAll(true);
        document.form1.data_conciliacao.value = document.form1.data_final.value;
        js_libera_botoes(true, 'pesquisar');
        return true;
    }

    function js_libera_botoes(liberar, botao = null) {
        if (botao) {
            if (liberar) {
                $(botao).disabled = false;
            } else {
                $(botao).disabled = true;
            }
        }
        if (liberar) {
            $('pesquisar').disabled = false;
            $('atualizar').disabled = false;
            $('tipo_movimento').disabled = false;
            $('tipo_lancamento').disabled = false;
            $('data_conciliacao').disabled = false;
            $('saldo_final_extrato').disabled = false;
            $('desprocessar').disabled = false;
            $('emitir_capa').disabled = false;
            $('incluir_pendencias').disabled = false;
            $('nova_conciliacao').disabled = false;
        } else {
            $('pesquisar').disabled = true;
            $('atualizar').disabled = true;
            $('tipo_movimento').disabled = true;
            $('tipo_lancamento').disabled = true;
            $('data_conciliacao').disabled = true;
            $('saldo_final_extrato').disabled = true;
            $('desprocessar').disabled = true;
            $('emitir_capa').disabled = true;
            $('incluir_pendencias').disabled = true;
            $('nova_conciliacao').disabled = true;
        }
    }

    function js_busca_extrato() {
        oParam                = new Object();
        oParam.conta          = document.form1.k13_conta.value;
        oParam.data_inicial   = document.form1.data_inicial.value;
        oParam.data_final     = document.form1.data_final.value;
        var sParam = js_objectToJson(oParam);
        var url = 'cai4_conciliacaoBancariaNovo.RPC.php';
        var sJson = '{"exec": "getFechamento", "params": ['+ sParam + ']}';
        var oAjax = new Ajax.Request(url,
            {
                method    : 'post',
                parameters: 'json=' + sJson,
                onComplete: js_retorno_fechamento
            }
        );
        var sJson = '{"exec": "getDadosExtrato", "params": ['+ sParam + ']}';
        var oAjax = new Ajax.Request(url,
            {
                method    : 'post',
                parameters: 'json=' + sJson,
                onComplete: js_retorno_dados_extrato
            }
        );
    }

    function js_retorno_fechamento(oAjax) {
        // console.log(oAjax);
        var oResponse = eval("(" + oAjax.responseText + ")");

        if (oResponse.status == 1) {
            // console.log(oResponse.aLinhasExtrato);
            for (var i = 0; i < oResponse.aLinhasExtrato.length; i++) {
                console.log(oResponse.aLinhasExtrato[i]);
                with (oResponse.aLinhasExtrato[i]) {
                    if (fechar_conciliacao == 1)
                        document.getElementById("fechar_conciliacao").checked = true;
                    else 
                        document.getElementById("fechar_conciliacao").checked = false;
                }
            }
        }
    }

    function js_retorno_dados_extrato(oAjax) {
        // console.log(oAjax);
        var oResponse = eval("(" + oAjax.responseText + ")");

        if (oResponse.status == 1) {
            // console.log(oResponse.aLinhasExtrato);
            for (var i = 0; i < oResponse.aLinhasExtrato.length; i++) {
                // console.log(oResponse.aLinhasExtrato[i]);
                with (oResponse.aLinhasExtrato[i]) {
                    if (fechar_conciliacao == 1)
                        document.getElementById("fechar_conciliacao").checked = true;
                    else 
                        document.getElementById("fechar_conciliacao").checked = false;
                    var conciliado = parseFloat(saldo_anterior) - parseFloat(total_entradas) + parseFloat(total_saidas);
                    // console.log("Query da Entrada: " + total_entradas);
                    document.form1.saldo_inicial_tesouraria.value = js_formatar(saldo_anterior, "f");
                    document.form1.total_entradas.value           = js_formatar(total_entradas, "f");
                    document.form1.total_saidas.value             = js_formatar(total_saidas, "f");
                    document.form1.saldo_conciliado.value         = js_formatar(conciliado, "f");
                    if (iZerarSaldoFinalExtrato == 0) {
                        document.form1.saldo_final_extrato.value = valor_conciliado;
                    }
                    iZerarSaldoFinalExtrato = 0;
                    js_atualizar_diferenca();
                }
            }
        }
    }

    function js_atualizar_diferenca() {
        var conciliado  = js_valor_reverso(document.form1.saldo_conciliado.value);
        var saldo_final = js_valor_reverso(document.form1.saldo_final_extrato.value);
        document.form1.diferenca.value = js_formatar(conciliado - saldo_final, "f");
    }

    function js_valor_reverso(valor) {
        if (valor.indexOf(",") >= 0) {
            valor = valor.replaceAll(".", "").replace(",", ".");
        }
        return parseFloat(valor);
    }

    function js_pesquisar_lancamentos() {
        js_divCarregando("Aguarde, pesquisando Lan�amentos.", "msgBox");
        js_libera_botoes(false);
        js_busca_extrato();

        oParam = new Object();
        oParam.conta = document.form1.k13_conta.value;
        oParam.data_inicial = document.form1.data_inicial.value;
        oParam.data_final = document.form1.data_final.value;
        oParam.tipo_lancamento = document.form1.tipo_lancamento.value;
        oParam.tipo_movimento  = document.form1.tipo_movimento.value;
        document.form1.total_selecionado.value = "0.00";

        var sParam = js_objectToJson(oParam);
        url = 'cai4_conciliacaoBancariaNovo.RPC.php';
        var sJson = '{"exec": "getLancamentos", "params": ['+ sParam + ']}';
        var oAjax = new Ajax.Request(url,
            {
                method    : 'post',
                parameters: 'json=' + sJson,
                onComplete: js_retorno_consulta_lancamentos
            }
        );
    }

    function js_retorno_consulta_lancamentos(oAjax) {
        js_removeObj("msgBox");
        js_libera_botoes(true);
        // console.log(oAjax);
        var oResponse = eval("(" + oAjax.responseText + ")");
        var iRowAtiva     = 0;
        var iTotalizador  = 0;
        console.log(oResponse);
        gridLancamentos.clearAll(true);
        gridLancamentos.setStatus("");

        if (oResponse.status == 1) {
            for (var iNotas = 0; iNotas < oResponse.aLinhasExtrato.length; iNotas++) {
                // console.log(oResponse.aLinhasExtrato[iNotas]);
                with (oResponse.aLinhasExtrato[iNotas]) {
                    var nValor = valor;
                    var lDisabled = false;
                    var lDisabledContaFornecedor = false;
                    var sDisabled = "";

                    if (nValor == 0) {
                        continue;
                    }

                    iTotalizador++;

                    var aLinha  = new Array();
                    aLinha[0] = iNotas + 1;
                    aLinha[1] = data_lancamento;
                    aLinha[2] = data_conciliacao;
                    aLinha[3] = credor;
                    aLinha[4] = tipo;
                    if (agrupado) {
                        aLinha[5] = ''
                        if (documento != 0) {
                            aLinha[6] = numdocumento != '' ? numdocumento:documento[0];
                        } else {
                            aLinha[6] = numdocumento != '' ? numdocumento:'';
                        }
                    } else {
                        aLinha[5] = op_rec_slip;
                        if (documento != 0) {
                            aLinha[6] = numdocumento != '' ? numdocumento:documento;
                        } else {
                            aLinha[6] = numdocumento != '' ? numdocumento:'';
                        }
                    }
                    var color = 'red';
                    if (movimento == 'E')
                        color = 'blue';

                    if (tipo_lancamento == 2)
                        movimento = movimento + 'P';

                    aLinha[7] = "<span style='color:" + color + "'>" + movimento + '</span>';
                    aLinha[8] = "<span style='color:" + color + "'>" + js_formatar(nValor, "f") + '</span>';
                    aLinha[9] = historico;

                    dados_complementares_numcgm[iNotas + 1]           = numcgm;
                    dados_complementares_coddoc[iNotas + 1]           = cod_doc;
                    dados_complementares_oprecslip[iNotas + 1]        = op_rec_slip;
                    dados_complementares_documento[iNotas + 1]        = documento;
                    dados_complementares_valor_individual[iNotas + 1] = valor_individual;

                    gridLancamentos.addRow(aLinha, false, lDisabled);

                    /*
                    document.getElementById("configuradas").checked = true;
                    document.getElementById("normais").checked = true;
                    document.getElementById("comMovs").checked = true;
                    */

                    js_linha_pendencia(iNotas, tipo_lancamento, movimento);
                    js_linha_conciliada(iNotas, data_conciliacao);

                    iRowAtiva++;
                }
            }
            gridLancamentos.renderRows();
            gridLancamentos.setNumRows(iTotalizador);
            // Bloco de conferencia do filtro
            js_showFiltro("conciliado", document.getElementById("configuradas").checked);
            js_showFiltro("normal", document.getElementById("normais").checked);
            js_showFiltro("pendente", document.getElementById("comMovs").checked);
            $('gridLancamentosstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
        } else if (oResponse.status == 2) {
            gridLancamentos.setStatus("<b>N�o foram encontrados movimentos.</b>");
        }
    }

    function js_linha_conciliada(row, data_conciliacao) {
        if (data_conciliacao != '')
            gridLancamentos.aRows[row].setClassName('conciliado');
    }

    function js_linha_pendencia(row, lancamento, movimento) {
    	if (lancamento == 1) {
    		gridLancamentos.aRows[row].setClassName('pendente');
            return;
        }

        if (lancamento == 2) {
            if (movimento == "EP") {
    		    gridLancamentos.aRows[row].setClassName('pendente-entrada');
                return;
            }
            if (movimento == "SP") {
                gridLancamentos.aRows[row].setClassName('pendente-saida');
                return
            }
            return;
        }
        return;
    }

   function js_showFiltro(sQualFiltro, lMostrar) {
        var aMatched = gridLancamentos.getElementsByClass(sQualFiltro);
        aMatched = aMatched.concat(gridLancamentos.getElementsByClass(sQualFiltro + "marcado"));
        var iTotalizador = 0;
        for (var i = 0; i < aMatched.length; i++) {
            if (lMostrar) {
                aMatched[i].style.display = '';
                iTotalizador++;
            } else {
                aMatched[i].style.display = 'none';
                iTotalizador--;
            }
        }
        var iTotal  = gridLancamentos.getNumRows();
        gridLancamentos.setNumRows(iTotal +iTotalizador);
    }

    function js_init() {
        document.form1.total_selecionado.value = "0.00";
        gridLancamentos = new DBGrid("gridLancamentos");
        gridLancamentos.nameInstance = "gridLancamentos";
        var alertError = false;

        gridLancamentos.selectSingle = function(oCheckbox, sRow, oRow, lVerificaSaldo, bSelectAll) {
            var limpar = true;
            var valorCampo = document.form1.total_selecionado.value;
            // Remove os pontos do n�mero (apenas os pontos, sem remover a v�rgula)
            var valorSemPontos = valorCampo.replace(/\./g, '');
            // Remove a v�rgula decimal do n�mero
            var valor = valorSemPontos.replace(',', '.');
            valor = parseFloat(valor);

            if (js_comparadata($F("data_conciliacao"), oRow.aCells[2].getValue(), "<")) {
                if (!alertError) {
                    var alertMensagem = "Lan�amento com data superior a data da concilia��o!";
                    if (bSelectAll)
                        alertMensagem = "Um ou mais Lan�amentos com data superior a data da concilia��o!";
                    alert(alertMensagem);
                    alertError = bSelectAll;
                }
                oCheckbox.checked = false;
                limpar = false;
                return false;
            }

            if (oCheckbox.checked) {
                oRow.isSelected = true;
                $(sRow).className += ' marcado';
                $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML) + 1;

                // if (oRow.aCells[3].getValue().length == 1) {
                    valorCampo = oRow.aCells[9].getValue();
                    // Remove os pontos do n�mero (apenas os pontos, sem remover a v�rgula)
                    var valorSemPontos = valorCampo.replace(/\./g, ''); 
                    // Remove a v�rgula decimal do n�mero
                    var valorSemVirgula = valorSemPontos.replace(',', '.');
                    if (oRow.aCells[8].getValue() == 'E' || oRow.aCells[8].getValue() == 'EP')
                        valor += parseFloat(valorSemVirgula);
                    else
                        valor -= parseFloat(valorSemVirgula);

                // }
            } else {
                if (limpar) {
                    $(sRow).className = oRow.getClassName();
                    oRow.isSelected = false;
                    $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML) - 1;

                    // f (oRow.aCells[3].getValue().length == 1) {
                        valorCampo = oRow.aCells[9].getValue();
                        // Remove os pontos do n�mero (apenas os pontos, sem remover a v�rgula)
                        var valorSemPontos = valorCampo.replace(/\./g, ''); 
                        // Remove a v�rgula decimal do n�mero
                        var valorSemVirgula = valorSemPontos.replace(',', '.');
                        if (oRow.aCells[8].getValue() == 'S' || oRow.aCells[8].getValue() == 'SP')
                            valor = parseFloat(valor) + parseFloat(valorSemVirgula);
                        else
                            valor = parseFloat(valor) - parseFloat(valorSemVirgula);
                    // }
                }
            }
            document.form1.total_selecionado.value = js_formatar(valor.toFixed(2), "f");
        }

        gridLancamentos.selectAll = function(idObjeto, sClasse, sLinha) {
            var obj = document.getElementById(idObjeto);

            if (obj.checked){
                obj.checked = false;
            } else{
                obj.checked = true;
            }

            itens = this.getElementsByClass(sClasse);
            for (var i = 0;i < itens.length; i++) {
                if (itens[i].disabled == false) {
                    if (obj.checked == true) {
                        if ($(this.aRows[i].sId).style.display != 'none') {
                            if (!itens[i].checked) {
                                itens[i].checked=true;
                                this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false, true);
                            }
                        }
                    } else {
                        if (itens[i].checked) {
                            itens[i].checked=false;
                            this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false, true);
                        }
                    }
                }
            }
            alertError = false;
        }

        gridLancamentos.setCheckbox(0);
        gridLancamentos.hasTotalizador = true;
        gridLancamentos.allowSelectColumns(true);
        gridLancamentos.setCellWidth(new Array("5%", "5%", "5%", "26%", "15%", "8%", "7%", "3%", "10%", "15%"));
        gridLancamentos.setCellAlign(new Array("center", "center", "center", "left", "left", "center", "center", "center", "right", "left"));
        gridLancamentos.setHeader(new Array("M", "Data", "Conciliado", "Credor", "Tipo", "OP/REC/SLIP", "Documento", "Mov", "Valor", "Hist�rico"));
        gridLancamentos.aHeaders[1].lDisplayed = false;
        gridLancamentos.show(document.getElementById('gridLancamentos'));
        $('gridLancamentosstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
        document.form1.data_inicial.focus();
    }

    function js_janelaPlanilhaDetalhada(lancamentos) {
        var dtBase = 1;
        var iCheque = 2;
        var total = 0;
        windowLancamentoItem = new windowAux('wndReceitaItem', 'Informa��es Detalhadas', 1000, 250);

        var sContent = "<div class='grid_planilha' id='grid_planilha' style='margin: 0 auto; width: 100%; text-align: center'>";
        sContent += "       <table id='tabela-lancamentos'>";
        sContent += "           <thead>";
        sContent += "               <tr>";
        sContent += "                   <th>Planilha</th>";
        sContent += "                   <th>C�digo</th>";
        sContent += "                   <th>Data</th>";
        sContent += "                   <th width='80%'>Nomenclatura</th>";
        sContent += "                   <th>Valor</th>";
        sContent += "               </tr>";
        sContent += "           </thead>";
        sContent += "           <tbody>";

        lancamentos.forEach(function (data, index) {
            sContent += "           <tr>";
            sContent += "                   <td>" + data.planilha + "</td>";
            sContent += "                   <td>" + data.codigo + "</td>";
            sContent += "                   <td>" + data.data + "</td>";
            sContent += "                   <td>" + data.descricao + "</td>";
            sContent += "                   <td style='text-align: right'>" + js_formatar(data.valor, "f"); + "</td>";
            sContent += "           </tr>";
            total += parseFloat(data.valor);
        });

        sContent += "           </tbody>";
        sContent += "           <tfoot>";
        sContent += "           <tr>";
        sContent += "                   <td colspan='4'><b>Total:</b></td>";
        sContent += "                   <td style='text-align: right'><b>" + js_formatar(total, "f"); + "</b></td>";
        sContent += "           </tr>";
        sContent += "           </tfoot>";
        sContent += "       </table>";
        sContent += "</div>";

        windowLancamentoItem.setContent(sContent);

        windowLancamentoItem.setShutDownFunction(function () {
            windowLancamentoItem.destroy();
        });

        var w = ((screen.width - 1000) / 2);
        var h = ((screen.height / 2) - 300);
        windowLancamentoItem.setIndex(5);
        windowLancamentoItem.allowDrag(false);
        windowLancamentoItem.show(h, w);
    }

    function js_janelaAgrupados(lancamentos) {
        var dtBase = 1;
        var iCheque = 2;
        var total = 0;

        windowLancamentoItem = new windowAux('wndChequeItem', 'Informa��es Detalhadas', 1000, 250);
        var sContent = "<div class='grid_detalhamentos' id='grid_detalhamentos' style='margin: 0 auto; width: 100%; text-align: center'>";
        sContent += "       <table id='tabela-lancamentos'>";
        sContent += "           <thead>";
        sContent += "               <tr>";
        sContent += "                   <th>D. Lan�amento</th>";
        sContent += "                   <th>Credor</th>";
        sContent += "                   <th>Tipo</th>";
        sContent += "                   <th>OP/REC/SLIP</th>";
        sContent += "                   <th>Documento</th>";
        sContent += "                   <th>Mov</th>";
        sContent += "                   <th>Valor</th>";
        sContent += "                   <th>Hist�rico</th>";
        sContent += "               </tr>";
        sContent += "           </thead>";
        sContent += "           <tbody>";

        lancamentos.forEach(function (data, index) {
            sContent += "           <tr>";
            sContent += "                   <td>" + data.data_lancamento + "</td>";
            sContent += "                   <td>" + data.credor + "</td>";
            sContent += "                   <td>" + data.tipo + "</td>";
            sContent += "                   <td>" + data.op_rec_slip + "</td>";
            sContent += "                   <td>" + data.documento + "</td>";
            sContent += "                   <td>" + data.movimento + "</td>";
            sContent += "                   <td style='text-align: right'>" + js_formatar(data.valor, "f"); + "</td>";
            sContent += "                   <td>" + data.historico + "</td>";
            sContent += "           </tr>";
            total += parseFloat(data.valor);
        });

        sContent += "           </tbody>";
        sContent += "           <tfoot>";
        sContent += "           <tr>";
        sContent += "                   <td colspan='6'><b>Total:</b></td>";
        sContent += "                   <td style='text-align: right'><b>" + js_formatar(total, "f"); + "</b></td>";
        sContent += "                   <td> </td>";
        sContent += "           </tr>";
        sContent += "           </tfoot>";
        sContent += "       </table>";
        sContent += "</div>";

        windowLancamentoItem.setContent(sContent);

        windowLancamentoItem.setShutDownFunction(function () {
            windowLancamentoItem.destroy();
        });

        var w = ((screen.width - 1000) / 2);
        var h = ((screen.height / 2) - 300);
        windowLancamentoItem.setIndex(5);
        windowLancamentoItem.allowDrag(false);
        windowLancamentoItem.show(h, w);
    }

    function js_janelaPendencia() {
        js_OpenJanelaIframe('top.corpo','db_iframe_extratobancariapendencia',
            'cai4_concbancpendencia001.php?funcao_js=parent.js_pesquisar_lancamentos&novo=true&reload=true&conta=' + $F("k13_conta") + "&data_inicial=" + $F("data_inicial"),
            'Cadastro de Pend�ncias', true);
    }

    function js_janelaPendenciaAlterarPendencia(id, conciliacao) {
        var url = 'cai4_concbancpendencia001.php?funcao_js=parent.js_pesquisar_lancamentos&novo=false&reload=true&conta=' + $F("k13_conta") + "&data_inicial=" + $F("data_inicial") + "&sequencial=" + id + "&conciliacao=" + conciliacao;
        js_OpenJanelaIframe('top.corpo','db_iframe_extratobancariapendencia', url, 'Cadastro de Pend�ncias', true);
    }

    function js_processar() {
        var movimentos = gridLancamentos.getSelection();
        var sem_erro = true;
        var aviso    = "";

        if (!js_validar_campos_processar(movimentos))
            return false;

        js_divCarregando("Aguarde, processando Lan�amentos.", "msgBox");

        oParam = new Object();
        oParam.conta = document.form1.k13_conta.value;
        oParam.data_final = document.form1.data_final.value;
        oParam.data_conciliacao = document.form1.data_conciliacao.value;
        oParam.saldo_final_extrato = document.form1.saldo_final_extrato.value;
        oParam.movimentos = new Array();

        for (var iMov = 0; iMov < movimentos.length; iMov++) {
            // console.log("Data da Concilia��o: " + movimentos[iMov][3].trim());
            // console.log("Encerramento: " + encerramento_contabil);
            if (encerramento_contabil != "" && movimentos[iMov][3].trim() != "") {
                if (js_comparadata(movimentos[iMov][3], encerramento_contabil, "<=")) {
                    alert("N�o foi poss�vel processar a concilia��o pois j� existe encerramento de per�odo cont�bil para um ou mais lan�amentos selecionados.");
                    js_removeObj("msgBox");
                    return false;
                }
            }
            var lancamento = js_preenche_lancamento(movimentos[iMov]);
            oParam.movimentos.push(lancamento);
        }
        console.log(oParam);
        // Final dos movimentos
        var sParam = js_objectToJson(oParam);
        url = 'cai4_conciliacaoBancariaNovo.RPC.php';
        var sJson = '{"exec": "Processar", "params": ['+ sParam + ']}';
        var oAjax = new Ajax.Request(url,
            {
                method    : 'post',
                parameters: 'json=' + sJson,
                onComplete: js_retorno_processar_lancamentos
            }
        );
    }

    function js_retorno_processar_lancamentos(oAjax) {
        js_removeObj("msgBox");
        // console.log("Antes da resposta");
        // console.log(oAjax.responseText);

        var oResponse = eval("(" + oAjax.responseText + ")");
        if (oResponse.error)
            alert(oResponse.error);
        js_pesquisar_lancamentos();
    }

    function js_pesquisar_lancamentos_conciliacao() {
        iZerarSaldoFinalExtrato = 1;
        js_pesquisar_lancamentos();
    }

    function js_desprocessar() {
        var movimentos = gridLancamentos.getSelection();
        var sem_erro = true;
        var aviso    = "";

        if (!js_validar_campos_processar(movimentos))
            return false;

        js_divCarregando("Aguarde, processando Lan�amentos.", "msgBox");

        oParam = new Object();
        oParam.conta = document.form1.k13_conta.value;
        oParam.data_final = document.form1.data_final.value;
        oParam.data_conciliacao = document.form1.data_conciliacao.value;
        oParam.saldo_final_extrato = document.form1.saldo_final_extrato.value;
        oParam.movimentos = new Array();

        for (var iMov = 0; iMov < movimentos.length; iMov++) {
            if (encerramento_contabil == 10 && js_comparadata(movimentos[iMov][3], encerramento_contabil, "<=") && movimentos[iMov][3].length == 10) {
                alert("N�o foi poss�vel desprocessar a concilia��o pois j� existe encerramento de per�odo cont�bil para um ou mais lan�amentos selecionados.");
                js_removeObj("msgBox");
                return false;
            }

            var lancamento = js_preenche_lancamento(movimentos[iMov]);
            oParam.movimentos.push(lancamento);
        }
        // console.log(oParam);
        // Final dos movimentos
        var sParam = js_objectToJson(oParam);
        url = 'cai4_conciliacaoBancariaNovo.RPC.php';
        var saldo_final_extrato = $F("saldo_final_extrato");
        var sJson = '{"exec": "Desprocessar", "params": ['+ sParam + ']}';
        iZerarSaldoFinalExtrato = 1;
        var oAjax = new Ajax.Request(url,
            {
                method    : 'post',
                parameters: 'json=' + sJson,
                onComplete: js_retorno_processar_lancamentos
            }
        );
        document.form1.saldo_final_extrato.value = saldo_final_extrato;
    }

    function js_preenche_lancamento(movimento) {
        lancamento = new Object();
        lancamento.data_lancamento  = movimento[2];
        lancamento.data_conciliacao = movimento[3];
        lancamento.cgm              = dados_complementares_numcgm[movimento[1]];
        lancamento.tipo             = dados_complementares_coddoc[movimento[1]];
        lancamento.codigo           = dados_complementares_oprecslip[movimento[1]];
        lancamento.documento        = dados_complementares_documento[movimento[1]];
        lancamento.movimentacao     = movimento[8];
        if (movimento[8] == "EP") 
            lancamento.movimentacao = "E";
        if (movimento[8] == "SP") 
            lancamento.movimentacao = "S";
        lancamento.valor            = dados_complementares_valor_individual[movimento[1]];
        console.log(lancamento);
        return lancamento;
    }

    // Fun��o que valida os campos para o processamento da concilia��o
    function js_validar_campos_processar(movimentos) {
        if (movimentos.length == 0) {
            alert("N�o h� nenhum lan�amento selecionado.");
            return false;
        }

        if ($F("data_conciliacao") == "") {
            alert("Informe a Data da Concilia��o!");
            $F("data_conciliacao") = "";
            return false;
        }

        if (encerramento_contabil != "") {
            if (js_comparadata($F("data_conciliacao"), encerramento_contabil, "<=")) {
                alert("N�o foi poss�vel processar a concilia��o pois j� existe encerramento de per�odo cont�bil para esta data.");
                return false;
            }
        }

        if (js_comparadata($F("data_conciliacao"), $F("data_inicial"), "<") || js_comparadata($F("data_conciliacao"), $F("data_final"), ">")) {
            alert("Data de Concilia��o fora do per�odo informado!");
            $F("data_conciliacao") = "";
            return false;
        }

        if ($F("saldo_final_extrato") == "") {
            alert("Informe o saldo final do extrato banc?rio!");
            return false;
        }

        return true;
    }

    function js_reset() {
        document.form1.k13_conta.value = "";
        document.form1.k13_descr.value = "";
        document.form1.data_inicial.value = "";
        document.form1.data_final.value = "";
        document.form1.tipo_lancamento.selectedIndex = 0;
        document.form1.tipo_movimento.selectedIndex = 0;
        document.form1.data_conciliacao.value = "";

        document.form1.saldo_inicial_tesouraria.value = "0,00";
        document.form1.total_entradas.value = "0,00";
        document.form1.total_saidas.value = "0,00";
        document.form1.saldo_conciliado.value = "0,00";
        document.form1.saldo_final_extrato.value = "0.00";
        document.form1.diferenca.value = "0,00";

        js_verifica_campos();
        js_init();
    }

    function js_data(data) {
        var data = data.split("/");
        return data[2] + "-" + data[1] + "-" + data[0];
    }

    document.form1.emitir_capa.onclick = function() {
        if (confirm("Deseja emitir tamb�m o extrato banc�rio?")) {
            var parametros = "conta=(" + $F("k13_conta") + ")&imprime_historico=s&imprime_analitico=s&totalizador_diario=n&somente_contas_com_movimento=s&datai=" + js_data($F("data_inicial")) + "&dataf=" + js_data($F("data_final")) + "&agrupapor=1&receitaspor=1&pagempenhos=1&imprime_pdf=p&somente_contas_bancarias=s&exibir_retencoes=n";
            jan = window.open('cai2_extratobancario002.php?'+parametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
            jan.moveTo(0,0);
        }

        sUrl = "cai4_concbancnovo002.php?conta_nova=(" + $F("k13_conta") + ")&data_inicial=" + js_data($F("data_inicial")) + "&data_final=" + js_data($F("data_final")) + "&saldo_extrato=" + $F("saldo_conciliado");
        window.open(sUrl, '', 'location=0');
    }
    // Final das fun��es verificadas
</script>
