<?php

/**
 *
 * @author I
 * @revision $Author: dbrobson $
 * @version $Revision: 1.10 $
 */
require "libs/db_stdlib.php";
require "libs/db_utils.php";
require "libs/db_conecta.php";
include "libs/db_sessoes.php";
include "libs/db_usuariosonline.php";
include "dbforms/db_funcoes.php";

$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
try {
    $anofolha = DBPessoal::getAnoFolha();
    $mesfolha = DBPessoal::getMesFolha();
} catch (Exception $error) {
    $anofolha = db_getsession("DB_anousu");
    $mesfolha = date("m", db_getsession("DB_datausu"));
}
?>
<html>

<head>
    <title>DBSeller Inform�tica Ltda - P�gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#cccccc" style="margin-top: 25px;">
    <center>


        <form name="form1" method="post" action="">
            <div style="display: table">
                <fieldset>
                    <legend>
                        <b>Exporta��o - eSocial</b>
                    </legend>
                    <table style='empty-cells: show; border-collapse: collapse;' class='form-container'>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend>
                                        <b>Dados Envio</b>
                                    </legend>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td align="left"><label>In�cio de Validade:</label>
                                                <?php
                                                db_input('anofolha', 4, 1, true, 'text', 2, "class='field-size1'");
                                                db_input('mesfolha', 2, 1, true, 'text', 2, "class='field-size1'");
                                                ?>
                                            </td>
                                            <td align="left"><label for="cboEmpregador">Empregador:</label>
                                                <select name="empregador" id="cboEmpregador" style="width: 78%;">
                                                    <option value="">selecione</option>
                                                </select>
                                            </td>
                                            <td align="right"><label for="tpAmb">Ambiente:</label>
                                                <select name="tpAmb" id="tpAmb" style="width: 76%;">
                                                    <option value="1">Produ��o</option>
                                                    <option value="2">Produ��o restrita - dados reais</option>
                                                    <option value="3">Produ��o restrita - dados fict�cios</option>
                                                </select>
                                            </td>
                                            <td align="right"><label for="modo">Tipo:</label>
                                                <select name="modo" id="modo" style="width: 76%;">
                                                    <option value="INC">Inclus�o</option>
                                                    <option value="ALT">Altera��o</option>
                                                    <option value="EXC">Exclus�o</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td align="left" id="dtalteracao" style="display:none" style="width: 25%;"><label>Data Altera��o:</label>
                                                <?php
                                                db_inputdata(
                                                    'dt_alteracao',
                                                    '',
                                                    '',
                                                    '',
                                                    true,
                                                    'text',
                                                    1,
                                                    ""
                                                );
                                                ?>
                                            </td>
                                            <td align="left" id="indapuracao_col" style="display:none"><label>Apura��o:</label>
                                                <select name="indapuracao" id="indapuracao" style="width: 25%;">
                                                    <option value="1">Mensal</option>
                                                    <option value="2">Anual</option>
                                                </select>
                                            </td>
                                            <td align="right" id="tppgto_col" style="display:none"><label>Tipo de Pagamento:</label>
                                                <select name="tppgto" id="tppgto" style="width: 50%;">
                                                    <option value="1">Pagamento de remunera��o, conforme apurado em
                                                        ideDmDev do S-1200
                                                    </option>
                                                    <option value="2">Pagamento de verbas rescis�rias conforme apurado
                                                        em ideDmDev do S-2299
                                                    </option>
                                                    <option value="3">Pagamento de verbas rescis�rias conforme apurado
                                                        em ideDmDev do S-2399
                                                    </option>
                                                    <option value="4">Pagamento de remunera��o conforme apurado em
                                                        ideDmDev do S-1202
                                                    </option>
                                                    <option value="5">Pagamento de benef�cios previdenci�rios, conforme
                                                        apurado em ideDmDev do S-1207
                                                    </option>
                                                </select>
                                            </td>
                                            <td align="left" id="tipo_col" style="display:none"><label>Tipo de evento:</label>
                                                <select name="tpevento" id="tpevento" style="width: 25%;">
                                                    <option value="1">RH</option>
                                                    <option value="2">Contabilidade</option>
                                                </select>
                                            </td>
                                            <td align="left" id="transDCTFWeb_col" style="display:none"><label>transDCTFWeb:</label>
                                                <select name="transDCTFWeb" id="transDCTFWeb" style="width: 25%;">
                                                    <option value="S">Sim</option>
                                                    <option value="N">N�o</option>
                                                </select>
                                            </td>
                                            <td align="left" id="evtpgtos_col" style="display:none"><label>evtpgtos:</label>
                                                <select name="evtpgtos" id="evtpgtos" style="width: 25%;">
                                                    <option value="S">Sim</option>
                                                    <option value="N">N�o</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <fieldset>
                                    <legend>
                                        <b>Fase de Gera��o</b>
                                    </legend>
                                    <table>
                                        <tr>
                                            <td>
                                                <input type="radio" value="EvtIniciaisTabelas" name="fase" onclick="checkFase(this.value);" />
                                                <label for="Fase1">1� Fase - Eventos Iniciais e de Tabelas</label><br>
                                            </td>
                                            <td>
                                                <input type="radio" value="EvtNaoPeriodicos" name="fase" onclick="checkFase(this.value);" />
                                                <label for="Fase2">2� Fase - Eventos n�o Peri�dicos</label><br>
                                            </td>
                                            <td>
                                                <input type="radio" value="EvtPeriodicos" name="fase" onclick="checkFase(this.value);" />
                                                <label for="Fase3">3� Fase - Eventos Peri�dicos</label><br>
                                            </td>
                                            <td>
                                                <input type="radio" value="" name="fase" onclick="checkFase(this.value);" />
                                                <label for="Fase4">Limpeza Ambiente de Teste</label><br>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                        <?php
                        include('forms/db_frmesocialevtinitabelas.php');
                        include('forms/db_frmesocialevtnaoperiodicos.php');
                        include('forms/db_frmesocialevtperiodicos.php');
                        ?>
                    </table>
                </fieldset>
                <div style="text-align: center;">
                    <input type="button" id="btnMarcarTodos" value="Marcar Todos" onclick="js_marcaTodos();" />
                    <input type="button" id="btnLimparTodos" value="Limpar Todos" onclick="js_limpa();" />
                    <input type="button" id="btnEnviar" value="Enviar para eSocial" onclick="js_processar();" />
                    <input type="button" id="btnConsultar" value="Consultar Envio" onclick="js_consultar();" />
                </div>
            </div>
        </form>

    </center>
</body>

</html>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
<script type="text/javascript">
    var arrEvts = ['EvtIniciaisTabelas', 'EvtNaoPeriodicos', 'EvtPeriodicos'];
    var empregador = Object();
    (function() {

        new AjaxRequest('eso4_esocialapi.RPC.php', {
            exec: 'getEmpregadores'
        }, function(retorno, lErro) {

            if (lErro) {
                alert(retorno.sMessage);
                return false;
            }
            empregador = retorno.empregador;

            $('cboEmpregador').length = 0;
            $('cboEmpregador').add(new Option(empregador.nome, empregador.cgm));
        }).setMessage('Buscando servidores.').execute();
    })();


    function checkFase(fase) {

        arrEvts.each(function(evt) {
            if (fase == evt) {
                $(evt).show();
            } else {
                $(evt).hide();
            }
        });
        js_limpa();
    }

    function js_processar() {

        //let result = confirm('Aten��o: Confirmar envio das informa��es do m�s ' + parent.bstatus.document.getElementById('dtatual').innerHTML.substr(3, 7) + ' para o eSocial?');

        // if (!result) {
        //     return false;
        // }

        if ($F('anofolha').length < 4 || parseInt($("mesfolha").value) < 1 || parseInt($("mesfolha").value) > 12) {

            alert("In�cio Validade inv�lido.");
            return false;
        }

        if ($("cboEmpregador").value == '') {

            alert("Selecione um empregador");
            return false;
        }

        if ($("tpAmb").value == '') {

            alert("Selecione um Ambiente de envio");
            return false;
        }

        let sSelectedFase = getRadioOption();
        let aArquivosSelecionados = new Array();
        let aArquivos = $$(`#${sSelectedFase} :input[type='checkbox']`);
        aArquivos.each(function(oElemento, iIndice) {

            if (oElemento.checked) {
                aArquivosSelecionados.push(oElemento.value.toUpperCase());
            }
        });
        if (aArquivosSelecionados.length == 0) {

            alert("Nenhum arquivo foi selecionado para ser gerado");
            return false;
        }

        js_divCarregando('Aguarde, processando arquivos', 'msgBox');
        var oParam = new Object();
        oParam.exec = "transmitir";
        oParam.arquivos = aArquivosSelecionados;
        oParam.empregador = $("cboEmpregador").value;
        oParam.iAnoValidade = $("anofolha").value;
        oParam.iMesValidade = $("mesfolha").value;
        oParam.tpAmb = $("tpAmb").value;
        oParam.modo = $("modo").value;
        oParam.dtalteracao = $("dt_alteracao").value;
        oParam.indapuracao = $("indapuracao").value;
        oParam.tppgto       = $("tppgto").value;
        oParam.tpevento     = $("tpevento").value;
        oParam.transDCTFWeb = $("transDCTFWeb").value;
        oParam.evtpgtos     = $("evtpgtos").value;
        var oAjax = new Ajax.Request("eso4_esocialapi.RPC.php", {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoProcessamento
        });

    }

    function js_retornoProcessamento(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.iStatus == 1) {
            alert(oRetorno.sMessage.urlDecode());
        } else {
            alert("Houve um erro no processamento! " + oRetorno.sMessage.urlDecode());
            return false;
        }
    }

    function js_marcaTodos() {

        var aCheckboxes = $$('input[type=checkbox]');
        aCheckboxes.each(function(oCheckbox) {
            oCheckbox.checked = true;
        });
    }

    function js_limpa() {

        var aCheckboxes = $$('input[type=checkbox]');
        aCheckboxes.each(function(oCheckbox) {
            oCheckbox.checked = false;
        });
    }

    function getRadioOption() {

        let aRadioOption = $$('input[type=radio]');
        let iRadioOption = 'EvtIniciaisTabelas';
        aRadioOption.each(function(oRadioOption) {
            if (oRadioOption.checked) {
                iRadioOption = oRadioOption.value;
            }
        });
        return iRadioOption;
    }

    function js_consultar() {

        js_OpenJanelaIframe('top.corpo', 'iframe_consulta_envio', 'func_consultaenvioesocial.php', 'Pesquisa', true);
    }

    function js_alt() {
        const indapuracaoCol = document.getElementById('indapuracao_col');
        const tppgtoCol = document.getElementById('tppgto_col');
        const tipoCol = document.getElementById('tipo_col');
        const transDCTFWeb = document.getElementById('transDCTFWeb_col');
        const evtpgtos     = document.getElementById('evtpgtos_col');

        if (document.getElementById('S1200').checked) {
            indapuracaoCol.style.display = 'inline';
            tppgtoCol.style.display = 'none';
            transDCTFWeb.style.display = 'none';
            tipoCol.style.display = 'inline';
        } else if (document.getElementById('S1202').checked ||
                document.getElementById('S1207').checked) {
            indapuracaoCol.style.display = 'inline';
            tppgtoCol.style.display = 'none';
            transDCTFWeb.style.display = 'none';
            tipoCol.style.display = 'inline';
        } else if (document.getElementById('S1210').checked) {
            indapuracaoCol.style.display = 'inline';
            tppgtoCol.style.display = 'inline';
            transDCTFWeb.style.display = 'none';
            tipoCol.style.display = 'inline';
        } else if (document.getElementById('S1299').checked) {
            indapuracaoCol.style.display = 'inline';
            tppgtoCol.style.display = 'none';
            transDCTFWeb.style.display = 'inline';
            tipoCol.style.display = 'none';
            evtpgtos.style.display = 'inline';
        } else {
            indapuracaoCol.style.display = 'none';
            tppgtoCol.style.display = 'none';
            transDCTFWeb.style.display = 'none';
            tipoCol.style.display = 'none';
            evtpgtos.style.display = 'none';
        }
    }


    function js_dataalt() {
        if (document.getElementById('S1210').checked) {
            document.getElementById('dtalteracao').style.display = 'inline';
        } else {
            document.getElementById('dtalteracao').style.display = 'none';
        }
    }

    function js_S3000(e){
        var button = document.getElementById("btnEnviar");
        if (e.checked) {
            document.getElementById('indapuracao_col').style.display = 'inline';
            console.log("Checkbox est�o marcados");
            js_OpenJanelaIframe('top.corpo', 'iframe_envio_s3000', 'func_envioesocialS3000.php', 'Pesquisa', true);
            button.disabled = true;
        } else {
            document.getElementById('indapuracao_col').style.display = 'none';
            console.log("Checkbox n�o est�o marcados");
            button.disabled = false;
        }

    }

    

    checkFase('');
</script>
<div id='debug'>
</div>