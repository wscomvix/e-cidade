<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_saltes_classe.php");

// Carrega parametros
$sSQL = "SELECT k29_conciliacaobancaria FROM caiparametro WHERE k29_instit = " . db_getsession('DB_instit');
$rsResult = db_query($sSQL);
$conciliacaoBancaria = db_utils::fieldsMemory($rsResult, 0)->k29_conciliacaobancaria ? date("d/m/Y", strtotime(db_utils::fieldsMemory($rsResult, 0)->k29_conciliacaobancaria)) : "";
$sSQL = "SELECT c99_data FROM condataconf WHERE c99_instit = " . db_getsession('DB_instit') . " AND c99_anousu = " . db_getsession("DB_anousu");
$rsResult = db_query($sSQL);
$encerramentoContabil = db_utils::fieldsMemory($rsResult, 0)->c99_data ? date("d/m/Y", strtotime(db_utils::fieldsMemory($rsResult, 0)->c99_data)) : "";

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

if (isset($incluir)) {
    db_inicio_transacao();
    $pendencia = new cl_conciliacaobancariapendencia;
    $pendencia->k173_conta = $conta;
    $pendencia->k173_tipolancamento = $tipo_lancamento;
    $pendencia->k173_mov = $movimento == "E" ? 1 : 2;
    $pendencia->k173_tipomovimento = $tipo_movimento ? tipoDocumentoLancamento($tipo_movimento) : "";
    $pendencia->k173_numcgm = $z01_numcgm;
    $pendencia->k173_codigo = $codigo;
    $pendencia->k173_documento = $documento;
    $pendencia->k173_data = data($data_lancamento);
    $pendencia->k173_valor = $valor;
    $pendencia->k173_historico = $observacao;
    if ($pendencia->Incluir()) {
        echo "<script>alert('Pend�ncia inserida com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao inserir a pend�ncia!')</script>";
    }
    db_fim_transacao();
    echo "<script>{$funcao_js}();</script>";
}

if (isset($alterar)) {
    if ($alterar == 'alterar') {
        db_inicio_transacao();
        $pendencia = new cl_conciliacaobancariapendencia;
        $pendencia->k173_sequencial = $sequencial;
        $pendencia->k173_conta = $conta;
        $pendencia->k173_tipolancamento = $tipo_lancamento;
        $pendencia->k173_mov = $movimento == "E" ? 1 : 2;
        $pendencia->k173_tipomovimento = $tipo_movimento ? tipoDocumentoLancamento($tipo_movimento) : "";
        $pendencia->k173_numcgm = $z01_numcgm;
        $pendencia->k173_codigo = $codigo;
        $pendencia->k173_documento = $documento;
        $pendencia->k173_data = data($data_lancamento);
        $pendencia->k173_valor = $valor;
        $pendencia->k173_historico = $observacao;
        if ($pendencia->Alterar()) {
            echo "<script>alert('Pend�ncia alterada com sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao alterar a pend�ncia!')</script>";
        }
        db_fim_transacao();
        echo "<script>{$funcao_js}();</script>";
    } else {
        $pendencia = new cl_conciliacaobancariapendencia;
        $pendencia->k173_sequencial = $sequencial;
        if ($pendencia->excluir($sequencial)) {
            echo "<script>alert('Pend�ncia exlu�da com sucesso!')</script>";
        } else {
            echo "<script>alert('Erro ao excluir a pend�ncia!')</script>";
        }
        echo "<script>{$funcao_js}();</script>";
    }
}

$DaoPendencia = new cl_conciliacaobancariapendencia;
$sqlPendencia = db_query($DaoPendencia->sql_query($sequencial, "*", NULL, ""));
db_fieldsmemory($sqlPendencia, 0);
$k173_historico = preg_replace('/\s\s+/', '', $k173_historico);
if ($k173_numcgm) {
    $sSQL = "SELECT z01_nome FROM cgm WHERE z01_numcgm = {$k173_numcgm}";
    $rsResult = db_query($sSQL);
    $nomeCgm = db_utils::fieldsMemory($rsResult, 0)->z01_nome;
}

function data($data)
{
    $data = explode("/", $data);
    return $data[2] . "-" . $data[1] . "-" . $data[0];
}

function descricaoTipoDocumentoLancamento($tipo_lancamento)
{
    switch ($tipo_lancamento) {
        case "30":
            return "3";
            break;
        case "31":
            return "3";
            break;
        case "100":
            return "1";
            break;
        case "101":
            return "1";
            break;
        case "120":
            return "4";
            break;
        case "121":
            return "4";
            break;
        case "130":
            return "2";
            break;
        case "131":
            return "2";
            break;
        case "2":
            return "2";
            break;
        case "164":
            return "5";
            break;
        case "165":
            return "5";
            break;
        case "140":
            return "6";
            break;
        case "141":
            return "7";
            break;
    }
}

function tipoDocumentoLancamento($tipo_lancamento)
{
    switch ($tipo_lancamento) {
        case "PGTO EMPENHO":
            return "30";
            break;
        case "EST. PGTO EMPENHO":
            return "31";
            break;
        case "REC. ORCAMENTARIA":
            return "100";
            break;
        case "EST. REC. ORCAMENTARIA":
            return "101";
            break;
        case "PGTO EXTRA ORCAMENTARIA":
            return "120";
            break;
        case "EST. PGTO EXTRA ORCAMENTARIA":
            return "121";
            break;
        case "EST. REC. EXTRA ORCAMENTARIA":
            return "131";
            break;
        case "REC. EXTRA ORCAMENTARIA":
            return "130";
            break;
        case "PERDAS":
            return "164";
            break;
        case "ESTORNO PERDAS":
            return "165";
            break;
        case "TRANSFERENCIA":
            return "140";
            break;
        case "EST. TRANSFERENCIA":
            return "141";
            break;
    }
}
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body style="background-color: #CCCCCC;" >
        <div class="container">
            <form name="form1" method="post" action="" onsubmit="return js_valida_dados()">
                <input type="hidden" value="<?php echo $conta; ?>" name="conta" />
                <?php if ($novo != 'false') { ?>
                    <input type="hidden" value="incluir" name="incluir" />
                <?php } else { ?>
                    <input type="hidden" value="alterar" name="alterar" />
                <?php } ?>
                <fieldset style="margin-top: 20px; width: 850px;">
                    <legend><b>Lan�amento de pend�ncia</b></legend>
                    <table width="100%">
                        <tr>
                            <td  valign="top">
                                <fieldset >
                                    <table border="0" width="100%">
                                        <tr>
                                            <td><b>Tipo de Lan�amento:</b></td>
                                            <td align="left" colspan="4">
                                            <?
                                                $tipo_lancamento = array("Selecione", "IMPLANTA��O", "PEND�NCIA");
                                                db_select("tipo_lancamento", $tipo_lancamento, true, 1, "onchange='js_seleciona_tipo_lancamento()' style='width:100%'");
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="320px"><b>Movimento:</b></td>
                                            <td align="left"  width="160px">
                                            <?
                                                $tipo_movimento = array("0" => "Selecione", "E" => "Entrada", "S" => "Sa�da");
                                                db_select("movimento", $tipo_movimento, true, 1, "onchange='js_seleciona_tipo_movimento()' style='width:100%'");
                                            ?>
                                            </td>
                                            <td  width="160px"><b>Tipo de Movimento:</b></td>
                                            <td align="left"  width="160px">
                                            <?
                                                $tipo_lancamento = array("Selecione");
                                                db_select("tipo_movimento", $tipo_lancamento, true, 1, "style='width:100%'");
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td nowrap title="<?=@$Tz01_numcgm?>">
                                            <?
                                                db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
                                            ?>
                                            </td>
                                            <td  nowrap>
                                            <?
                                                db_input('z01_numcgm',27,$k173_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
                                            ?>
                                            </td>
                                            <td colspan="2">
                                                <?
                                                db_input('z01_nome',72,$Iz01_nome,true,'text',3,'');
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>OP/REC/SLIP:</b></td>
                                            <td nowrap>
                                                <? db_input('codigo', 27, null, true, 'text', 1, ''); ?>
                                            </td>
                                            <td><b>Doc. Extrato:</b></td>
                                            <td nowrap align="">
                                                <? db_input('documento', 39, null, true, 'text', 1, ''); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Data Lan�amento:</b></td>
                                            <td nowrap>
                                                <? db_inputdata("data_lancamento", 25, null, null, true, "text", 1, "style='width:100%'"); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Valor:</b></td>
                                            <td align="left">
                                                <? db_input("valor", 27, 0, true, "text", 1, "onkeyup=\"js_ValidaCampos(this, 4, 'valor', false, null, event); js_validaNegativo(this)\""); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <fieldset>
                                                    <legend><strong>Hist�rico</strong></legend>
                                                    <textarea title="Hist�rico" name="observacao" type="text" id="observacao" rows="1" cols="40" onblur="js_ValidaMaiusculo(this,'f',event);" onkeyup="js_ValidaCampos(this, 0,'Observa��o','t','f',event);" oninput=""  style="background-color:#E6E4F1;width:100%" autocomplete="off"></textarea>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4' style='text-align: center'>
                                <? if (!$conciliacao) { ?>
                                  <input name="salvar" id='salvar' type="submit"  value="<? if ($novo != 'false') { echo "Salvar"; } else { echo "Alterar"; }  ?>"/>
                                <? } ?>
                                <? if ($novo == 'false' AND !$conciliacao) { ?>
                                    <input name="cancelar" id='cancelar' type="button" value="Excluir" onclick="js_cancelar()" />
                                <? } ?>
                                <input name="voltar" id='voltar' type="button"  value="Voltar" onclick="parent.db_iframe_extratobancariapendencia.hide();"/>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </div>
    </body>
</html>
<script>
    var dataConciliacaoBancaria = "<?= $conciliacaoBancaria ?>";
    var dataInicial = "<?= $data_inicial ?>";
    var encerramentoContabil = "<?=$encerramentoContabil?>";
    var novo = <?=$novo?>;

    if (!novo)
        js_atualizarCampos();
    else {
        js_reset();
        js_bloquear_campos(true);
    }

    function js_validaNegativo(campo)
    {
        campo.value = campo.value.replace('-', '');
    }

    function js_atualizarCampos()
    {
        document.form1.tipo_lancamento.selectedIndex = "<?=$k173_tipolancamento?>";
        document.form1.movimento.selectedIndex = "<?=$k173_mov?>";
        if (document.form1.tipo_lancamento.selectedIndex == 1) {
            js_seleciona_tipo_movimento();
            document.form1.tipo_movimento.selectedIndex = "<?=descricaoTipoDocumentoLancamento($k173_tipomovimento)?>";
        } else {
            document.form1.tipo_movimento.selectedIndex = 0;
        }
        document.form1.z01_numcgm.value = "<?=$k173_numcgm?>";
        document.form1.z01_nome.value = "<?=$nomeCgm?>";
        document.form1.codigo.value = "<?=$k173_codigo?>";
        document.form1.documento.value = "<?=$k173_documento?>";
        document.form1.data_lancamento.value = "<?=$k173_data ? date("d/m/Y", strtotime($k173_data)) : ""?>";
        document.form1.valor.value = "<?=$k173_valor?>";
        document.form1.observacao.value = "<?=$k173_historico?>";
        if (document.form1.valor.value == "") {
            js_reset();
            js_bloquear_campos(true);
        }
    }

    function js_reset() {
        document.form1.tipo_lancamento.selectedIndex = 0;
        document.form1.movimento.selectedIndex = 0;
        document.form1.tipo_movimento.selectedIndex = 0;
        document.form1.z01_numcgm.value = "";
        document.form1.z01_nome.value = "";
        document.form1.codigo.value = "";
        document.form1.documento.value = "";
        document.form1.data_lancamento.value = "";
        document.form1.valor.value = "";
        document.form1.observacao.value = "";
    }

    function js_bloquear_campos(liberar)
    {
        $('movimento').disabled = liberar;
        $('z01_numcgm').disabled = liberar;
        $('codigo').disabled = liberar;
        $('documento').disabled = liberar;
        $('data_lancamento').disabled = liberar;
        $('valor').disabled = liberar;
        js_bloquear_tipo_movimento();
    }

    function js_seleciona_tipo_lancamento()
    {
        if ($F('tipo_lancamento') != "0")
            js_bloquear_campos(false);
        else
            js_bloquear_campos(true);
    }

    function js_seleciona_tipo_movimento()
    {
        js_tipo_movimento_options();
    }

    function js_bloquear_tipo_movimento()
    {
        if ($F('tipo_lancamento') == "1")
            $('tipo_movimento').disabled = false;
        else
            $('tipo_movimento').disabled = true;
    }

    function js_tipo_movimento_options()
    {
        var entrada = ["Selecione", "REC. ORCAMENTARIA", "REC. EXTRA ORCAMENTARIA", "EST. PGTO EMPENHO", "EST. PGTO EXTRA ORCAMENTARIA", "ESTORNO PERDAS", "TRANSFERENCIA", "EST. TRANSFERENCIA."];
        var saida = ["Selecione", "EST. REC. ORCAMENTARIA", "EST. REC. EXTRA ORCAMENTARIA", "PGTO EMPENHO", "PGTO EXTRA ORCAMENTARIA", "PERDAS", "TRANSFERENCIA", "EST. TRANSFERENCIA"];

        if ($F("movimento") == "E") {
            js_preenche_option(document.form1.tipo_movimento, entrada);
        } else if ($F("movimento") == "S") {
            js_preenche_option(document.form1.tipo_movimento, saida);
        }
    }

    function js_preenche_option(select, options)
    {
        var i, option;
        js_remove_all_select(select);
        for (i = 0; i < options.length; i++) {
            option = document.createElement("option");
            option.value = option.text = options[i];
            select.add(option);
        }
    }

    function js_remove_all_select(select) {
        while (select.options.length > 0) {
            select.remove(0);
        }
    }

    function js_pesquisaz01_numcgm(mostra){
        if (mostra == true) {
            js_OpenJanelaIframe('', 'func_nome', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisar CGM', true, 22, 0, document.body.getWidth() - 12, document.body.scrollHeight - 30);
        } else {
            if (document.form1.z01_numcgm.value != '') {
                js_OpenJanelaIframe('', 'func_nome', 'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisar CGM', false, 22, 0, document.width-12, document.body.scrollHeight-30);
            } else {
                document.form1.z01_nome.value = '';
            }
        }
    }

    function js_mostracgm(erro,chave) {
        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.z01_numcgm.focus();
            document.form1.z01_numcgm.value = '';
        }
    }

    function js_mostracgm1(chave1,chave2) {
        document.form1.z01_numcgm.value = chave1;
        document.form1.z01_nome.value   = chave2;
        func_nome.hide();
    }

    function js_cancelar()
    {
        document.form1.alterar.value = 'excluir';
        if (js_valida_dados()) {
            if (confirm("Tem certeza que deseja excluir esse lan�amento?"))
                document.form1.submit();
        }
    }

    function js_valida_dados()
    {
        if ($F("tipo_lancamento") == "0") {
            alert("Tipo de Lan�amento n�o informado.");
            return false;
        }

        if ($F("movimento") == "0") {
            alert("Movimento n�o informado.");
            return false;
        }

        if ($F("tipo_lancamento") == "1" && $F("tipo_movimento") == "Selecione") {
            alert("Tipo de Movimento n�o informado.");
            return false;
        }

        if ($F("data_lancamento") == "") {
            alert("Data de Lan�amento n�o informada.");
            return false;
        }

        if ($F("valor") == "") {
            alert("Valor n�o informado.");
            return false;
        }

        if (document.form1.observacao.value.trim() == "") {
            alert("Hist�rico n�o informado.");
            return false;
        }

        if ($F("tipo_lancamento") == "1" && js_comparadata($F("data_lancamento"), dataConciliacaoBancaria, ">=")) {
            alert("N�o � poss�vel incluir movimento de implanta��o ap�s a data de implanta��o da concilia��o banc�ria.");
            return false;
        }

        if ($F("tipo_lancamento") == "2" && js_comparadata($F("data_lancamento"), dataInicial, "<")) {
            if (!confirm("A inclus�o da pend�ncia fora do per�odo de concilia��o afetar� as concilia��es realizadas anteriormente, tem certeza que deseja incluir a pend�ncia nesta data?"))
                return false;
        }

        if ($F("tipo_lancamento") == "2") {
            if (js_comparadata($F("data_lancamento"), dataConciliacaoBancaria, "<=")) {

            } else {
                if (encerramentoContabil != "") {
                    if (js_comparadata($F("data_lancamento"), encerramentoContabil, "<")) {
                        alert("N�o foi poss�vel processar a concilia��o pois j� existe encerramento de per�odo cont�bil para esta data.");
                        return false;
                    }
                }
            }
        }
        return true;
    }
</script>
