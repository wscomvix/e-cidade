<?php

/**
 *
 * @author I
 * @revision $Author: dbrobson $
 * @version $Revision: 1.10 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#cccccc" style="margin-top: 25px;">
    <center>


        <form name="form1" method="post" action="">
            <div style="display: table">
                <fieldset>
                    <legend>
                        <b>Gerar Balancete</b>
                    </legend>
                    <table style='empty-cells: show; border-collapse: collapse;'>
                        <tr>
                            <td colspan="4">
                                <fieldset>
                                    <table>
                                        <tr>
                                            <td>M�s Refer�ncia: </td>
                                            <td>
                                                <select id="MesReferencia" class="MesReferencia" onchange="isEncerramento();">
                                                    <option value="00">SELECIONE</option>
                                                    <option value="01">Janeiro</option>
                                                    <option value="02">Fevereiro</option>
                                                    <option value="03">Mar�o</option>
                                                    <option value="04">Abril</option>
                                                    <option value="05">Maio</option>
                                                    <option value="06">Junho</option>
                                                    <option value="07">Julho</option>
                                                    <option value="08">Agosto</option>
                                                    <option value="09">Setembro</option>
                                                    <option value="10">Outubro</option>
                                                    <option value="11">Novembro</option>
                                                    <option value="12">Dezembro</option>
                                                    <option value="13">Encerramento</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>De Para Natureza Despesa: </td>
                                            <td>
                                                <select id="DeParaNatureza" class="DeParaNatureza" onchange="isEncerramento();">
                                                    <option value="1">Sim</option>
                                                    <option value="0">N�o</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php if (db_getsession('DB_anousu') > 2022) { ?>
                                        <tr>
                                            <td>N�mero Registro: </td>
                                            <td>
                                                <select id="numeroRegistro" class="numeroRegistro" onchange="isEncerramento();">
                                                    <option value="0">Todos</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                    <option value="26">26</option>
                                                    <option value="27">27</option>
                                                    <option value="28">28</option>
                                                    <option value="29">29</option>
                                                    <option value="30">30</option>
                                                    <option value="31">31</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">Dados Mensais</td>
                            <td>Arquivos Gerados</td>
                        </tr>
                        <tr>
                            <td style="border: 2px groove white;" valign="top">
                                <input type="checkbox" value="IdentificacaoRemessa" id="IdenficacaoRemessa" />
                                <label for="IdenficacaoRemessa">Identifica��o da Remessa</label><br>
                                <input type="checkbox" value="Balancete" id="Balancete" />
                                <label for="Balancete">Balancete Cont�bil</label><br>
                                <input type="checkbox" value="Consideracoes" id="Consideracoes" />
                                <label for="Consideracoes">Considera��es</label><br>
                                <?php if (db_getsession('DB_anousu') > 2016) { ?>
                                    <input type="checkbox" value="Fundos" id="Fundos" />
                                    <label for="Fundos">Fundos</label><br>
                                <?php } ?>
                            </td>
                            <td style="border: 2px groove white;" valign="top">


                            </td>

                            <td style="border: 2px groove white;" valign="top">
                                <div id='retorno' style="width: 200px; height: 250px; overflow: scroll;">
                                </div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <div style="text-align: center;">
                    <input type="button" id="btnMarcarTodos" value="Marcar Todos" onclick="js_marcaTodos();" />
                    <input type="button" id="btnLimparTodos" value="Limpar Todos" onclick="js_limpa();" />
                    <input type="button" id="btnProcessar" value="Processar" onclick="js_processar();" />
                </div>
            </div>
        </form>

    </center>
</body>

</html>
<? db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
<script type="text/javascript">
    function isEncerramento() {
        $("Balancete").value = 'Balancete';
        if ($("MesReferencia").value == 13)
            $("Balancete").value = 'BalanceteEncerramento';
    }

    function js_processar() {

        var aArquivosSelecionados = new Array();
        var aArquivos = $$("input[type='checkbox']");
        var iMesReferencia = $("MesReferencia");
        var iDeParaNatureza = $("DeParaNatureza");
        var iNumeroRegistro = $("numeroRegistro");

        /*
         * iterando sobre o array de arquivos com uma fun��o an�nima para pegar os arquivos selecionados pelo usu�rio
         */
        aArquivos.each(function(oElemento, iIndice) {

            if (oElemento.checked) {
                aArquivosSelecionados.push(oElemento.value);
            }
        });
        if (aArquivosSelecionados.length == 0) {

            alert("Nenhum arquivo foi selecionado para ser gerado");
            return false;
        }

        if (iMesReferencia.value == 0) {


            alert("Selecione um M�s Refer�ncia para gera��o do(s) arquivo(s)!");

            return false;
        }
        js_divCarregando('Aguarde, processando arquivos', 'msgBox');
        var oParam = new Object();
        oParam.exec = "processarBalancete";
        oParam.arquivos = aArquivosSelecionados;
        oParam.mesReferencia = iMesReferencia.value;
        oParam.deParaNatureza = iDeParaNatureza.value;
        oParam.iNumeroRegistro = iNumeroRegistro.value;
        var oAjax = new Ajax.Request("con4_processarpad.RPC.php", {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoProcessamento
        });

    }

    function js_retornoProcessamento(oAjax) {

        js_removeObj('msgBox');
        $('debug').innerHTML = oAjax.responseText;
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            alert("Processo conclu�do com sucesso!");
            var sRetorno = "<b>Arquivos Gerados:</b><br>";
            for (var i = 0; i < oRetorno.itens.length; i++) {

                with(oRetorno.itens[i]) {

                    sRetorno += "<a target='_blank' href='db_download.php?arquivo=" + caminho + "'>" + nome + "</a><br>";
                }
            }

            $('retorno').innerHTML = sRetorno;
            var sCalculos = "<hr>";
            for (var i = 0; i < oRetorno.calculos.length; i++) {

                with(oRetorno.calculos[i]) {
                    sCalculos += "<h3>Resultado dos c�lculos do encerramento</h3><br>" + i + 1 + ". " + mensagem + "<br> <strong>Detalhes:</strong> " + calculo + "<br><h3>Regras:</h3><br>" + regras + "<br>";
                }

            }
            $('debug').innerHTML = sCalculos;
        } else {

            $('retorno').innerHTML = '';
            alert("Houve um erro no processamento!" + oRetorno.message.urlDecode());
            return false;
        }
    }

    function js_pesquisao125_cronogramaperspectiva(mostra) {

        if (mostra == true) {
            /*
             *passa o nome dos campos do banco para pesquisar pela fun��o js_mostracronogramaperspectiva1
             *a variavel funcao_js � uma vari�vel global
             *db_lovrot recebe par�metros separados por |
             */
            js_OpenJanelaIframe('CurrentWindow.corpo',
                'db_iframe_cronogramaperspectiva',
                'func_cronogramaperspectiva.php?funcao_js=' +
                'parent.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano',
                'Perspectivas do Cronograma', true);
        } else {
            if ($F('o124_sequencial') != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo',
                    'db_iframe_cronogramaperspectiva',
                    'func_cronogramaperspectiva.php?pesquisa_chave=' +
                    $F('o124_sequencial') +
                    '&funcao_js=parent.js_mostracronogramaperspectiva',
                    'Perspectivas do Cronograma',
                    false);
            } else {
                $('o124_sequencial').value = '';
            }
        }
    }
    //para retornar sem mostrar a tela de pesquisa. ao digitar o codigo retorna direto para o campo
    function js_mostracronogramaperspectiva(chave, erro, ano) {
        $('o124_descricao').value = chave;
        if (erro == true) {

            $('o124_sequencial').focus();
            $('o124_sequencial').value = '';

        }
    }
    //preenche os campos do frame onde foi chamada com os valores do banco
    function js_mostracronogramaperspectiva1(chave1, chave2, chave3) {

        $('o124_sequencial').value = chave1;
        $('o124_descricao').value = chave2;
        db_iframe_cronogramaperspectiva.hide();
    }

    function js_pesquisa_ppa(mostra) {

        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo',
                'db_iframe_ppa',
                'func_ppaversaosigap.php?funcao_js=' +
                'parent.js_mostrappa1|o119_sequencial|o01_descricao',
                'Perspectivas do Cronograma', true);
        } else {
            if ($F('o119_sequencial') != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo',
                    'db_iframe_ppa',
                    'func_ppaversaosigap.php?pesquisa_chave=' +
                    $F('o119_sequencial') +
                    '&funcao_js=parent.js_mostrappa',
                    'Perspectivas do Cronograma',
                    false);
            } else {

                document.form1.o124_descricao.value = '';
                document.form1.ano.value = ''

            }
        }
    }

    function js_mostrappa(chave, erro, ano) {
        $('o119_descricao').value = chave;
        if (erro == true) {

            $('o119_sequencial').focus();
            $('o119_sequencial').value = '';

        }
    }

    function js_mostrappa1(chave1, chave2, chave3) {

        $('o119_sequencial').value = chave1;
        $('o119_descricao').value = chave2;
        db_iframe_ppa.hide();
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
</script>
<div id='debug'>
</div>