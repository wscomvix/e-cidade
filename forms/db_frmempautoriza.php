<?
/*
* E-cidade Software Publico para Gestao Municipal
* Copyright (C) 2014 DBselller Servicos de Informatica
* www.dbseller.com.br
* e-cidade@dbseller.com.br
*
* Este programa e software livre; voce pode redistribui-lo e/ou
* modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
* publicada pela Free Software Foundation; tanto a versao 2 da
* Licenca como (a seu criterio) qualquer versao mais nova.
*
* Este programa e distribuido na expectativa de ser util, mas SEM
* QUALQUER GARANTIA; sem mesmo a garantia implicita de
* COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
* PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
* detalhes.
*
* Voce deve ter recebido uma copia da Licenca Publica Geral GNU
* junto com este programa; se nao, escreva para a Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
* 02111-1307, USA.
*
* Copia da licenca no diretorio licenca/licenca_en.txt
* licenca/licenca_pt.txt
*/

//MODULO: empenho
$clempautoriza->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("e44_tipo");
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("pc50_descr");
$clrotulo->label("e57_codhist");
$clrotulo->label("e40_codhist");
$clrotulo->label("e40_historico");
$clrotulo->label("c58_descr");
$clrotulo->label("e150_numeroprocesso");
if ($db_opcao == 1) {
    $ac = "emp1_empautoriza004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
    $ac = "emp1_empautoriza005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
    $ac = "emp1_empautoriza006.php";
}

if (isset($e57_codhist)) {
    $query = "select e40_descr from emphist where e40_codhist = $e57_codhist";
    $resultado = db_query($query);
    $resultado = db_utils::fieldsMemory($resultado, 0);
    $e40_descr = $resultado->e40_descr;
}
if (!$e57_codhist) {
    $query = "select distinct e57_codhist from empauthist where e57_codhist = 0";
    $resultado = db_query($query);
    $resultado = db_utils::fieldsMemory($resultado, 0);
    $e57_codhist = $resultado->e57_codhist;
    $query = "select e40_descr from emphist where e40_codhist = $e57_codhist";
    $resultado = db_query($query);
    $resultado = db_utils::fieldsMemory($resultado, 0);
    $e40_descr = $resultado->e40_descr;
}

db_app::load("DBFormCache.js");

?>

<style>
    #e54_numcgm,
    #e54_autori,
    #e54_codcom,
    #e54_codtipo,
    #e57_codhist {
        width: 20%;
    }

    #e54_numcgm,
    #e54_concarpeculiar {
        width: 20%;
        float: left;
    }

    #z01_nome,
    #e54_codcomdescr,
    #e54_codtipodescr,
    #e57_codhistdescr,
    #c58_descr {
        width: 79%;
        float: right;
    }

    #e54_tipoautorizacao,
    #e44_tipo,
    #e54_destin,
    #e150_numeroprocesso,
    #e54_resumo {
        width: 100%;
    }
</style>

<form name="form1" method="post" onsubmit="<?php ($db_opcao == 1) ? 'return js_salvaCache();' : ''; ?>" action="<?= $ac ?>">
    <fieldset>
        <legend><strong>Autoriza��o de Empenho </strong></legend>
        <table border="0">
            <tr>
                <td nowrap title="<?= @$Te54_autori ?>">
                    <?= @$Le54_autori ?>
                </td>
                <td>
                    <?
                    db_input('e54_autori', 10, $Ie54_autori, true, 'text', 3);
                    db_input('o58_codele', 10, $Ie54_autori, true, 'hidden', 3);
                    ?>

                    <!-- Inclui data de emiss�o de autoriza��o -->
                    <b> Data da Autoriza��o: </b>
                    <?
                    if ($db_opcao == 1 && !$e54_emiss) {
                        $e54_emiss_dia = date("d", db_getsession("DB_datausu"));
                        $e54_emiss_mes = date("m", db_getsession("DB_datausu"));
                        $e54_emiss_ano = date("Y", db_getsession("DB_datausu"));
                    }
                    db_inputData('e54_emiss', @$e54_emiss_dia, @$e54_emiss_mes, @$e54_emiss_ano, true, 'text', $db_opcao);
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Te54_numcgm ?>">
                    <?
                    db_ancora(@$Lz01_nome, "js_pesquisae54_numcgm(true);", isset($emprocesso) && $emprocesso == true ? "3" : $db_opcao);
                    ?>
                </td>
                <td nowrap="nowrap">
                    <?
                    db_input('e54_numcgm', 10, $Ie54_numcgm, true, 'text', isset($emprocesso) && $emprocesso == true ? "3" : $db_opcao, " onchange='js_pesquisae54_numcgm(false);'");
                    db_input('z01_nome', 48, $Iz01_nome, true, 'text', 3, '');
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Tipo de Autoriza��o:</strong>
                </td>
                <td>
                    <?php
                    $valores = array(
                        0 => 'Selecione',
                        1 => 'Direta',
                        3 => 'Decorrente de Licita��o',
                        2 => 'Decorrente de Licita��o de Outro �rg�o',
                        4 => 'Ades�o � ata de Registro de Pre�os'
                    );
                    db_select('e54_tipoautorizacao', $valores, true, $db_opcao, "onchange='js_handleChangeTipoAutorizacao()'");
                    ?>
                </td>
            </tr>

            <tr id="trlicitacao" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                <td>
                    <?php
                    db_ancora('<b>Licita��o:</b>', "js_pesquisa_liclicita(true);", 1);
                    ?>
                </td>
                <td>
                    <?php
                    db_input('e54_codlicitacao', 7, "", true, "text", 1, "onchange='js_pesquisa_liclicita(false);'");
                    db_input('l20_objeto', 40, "", true, 'text', 3);
                    ?>
                </td>
            </tr>
            <tr id="trlicoutrosorgaos" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                <td nowrap title="Tipo de Autoriza��o">
                    <?=
                    db_ancora("Licita��o Outro �rg�o:", "js_pesquisaac16_licoutroorgao(true)", $db_opcao);
                    ?>
                </td>
                <td>
                    <?
                    db_input('e54_licoutrosorgaos', 7, "", true, 'text', $db_opcao, "onchange='js_pesquisaac16_licoutroorgao(false)';");
                    db_input('e54_fornelicoutrosorgaos', 40, "", true, 'text', 3);
                    ?>
                </td>
            </tr>
            <tr id="tradesao" style="display: <?= $db_opcao == 2 ? 'table-row' : 'none' ?> ;">
                <td>
                    <?=
                    db_ancora("Ades�o de Registro Pre�o:", "js_pesquisaaadesaoregpreco(true)", $db_opcao);
                    ?>
                </td>
                <td>
                    <?
                    db_input('e54_adesaoregpreco', 7, "", true, 'text', $db_opcao, "onchange='js_pesquisaaadesaoregpreco(false)';");
                    db_input('si06_objetoadesao', 40, "", true, 'text', 3);
                    ?>
                </td>
            </tr>

            <tr id="trtipoorigem" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                <td>
                    <strong>Tipo Origem:</strong>
                </td>
                <td>
                    <?
                    $aValores = array(
                        0 => 'Selecione',
                        1 => '1 - N�o ou dispensa por valor',
                        2 => '2 - Licita��o',
                        3 => '3 - Dispensa ou Inexigibilidade',
                        4 => '4 - Ades�o � ata de registro de pre�os',
                        5 => '5 - Licita��o realizada por outro �rg�o ou entidade',
                        6 => '6 - Dispensa ou Inexigibilidade realizada por outro �rg�o ou entidade',
                        7 => '7 - Licita��o - Regime Diferenciado de Contrata��es P�blicas - RDC',
                        8 => '8 - Licita��o realizada por consorcio p�blico',
                        9 => '9 - Licita��o realizada por outro ente da federa��o',
                    );
                    db_select('e54_tipoorigem', $aValores, true, $db_opcao, "onchange=''", "style='width:100%;'");
                    ?>
                </td>
            </tr>
            <tr id="trdadoslicitacao" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                <td>
                    <strong>Dados da Licita��o:</strong>
                </td>
                <td>
                    <strong>N� do Processo:</strong>
                    <? db_input('e54_numerl', 10, "", true, 'text', 3, "", "", "", "", 16); ?>

                    <strong> Modalidade:</strong>
                    <?
                    db_input('e54_nummodalidade', 10, "", true, 'text', 3, "onkeyup='somenteNumeros(this)';", "", "", "", 10);
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Te54_codcom ?>">
                    <strong>Tipo de Compra:</strong>
                </td>
                <td>
                    <?php
                    if (isset($e54_codcom) && $e54_codcom == '') {
                        $pc50_descr = '';
                    }
                    if (empty($e54_codcom)) {

                        $somadata = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_tipcom as e54_codcom"));
                        if ($clpcparam->numrows > 0) {
                            db_fieldsmemory($somadata, 0);
                        } else {
                            $e54_codcom = 5;
                        }
                    }

                    $result = $clpctipocompra->sql_record($clpctipocompra->sql_query_file(null, "pc50_codcom as e54_codcom,pc50_descr"));
                    db_selectrecord("e54_codcom", $result, true, isset($emprocesso) && $emprocesso == true ? "1" : $db_opcao, "", "", "", "", "js_verificatipocompratribunal(this.value)");
                    ?>
                </td>
            </tr>
            <tr style="display:none;">
                <td nowrap title="<?= @$Te54_tipol ?>">
                    <strong>Tipo de Licita��o:</strong>
                </td>
                <td>
                    <?
                    if (isset($tipocompra) || isset($e54_codcom)) {

                        if (isset($e54_codcom) && empty($tipocompra)) {
                            $tipocompra = $e54_codcom;
                        }

                        $result = $clcflicita->sql_record($clcflicita->sql_query_file(null, "l03_tipo,l03_descr", '', "l03_codcom=$tipocompra and l03_instit = " . db_getsession('DB_instit')));
                        if ($clcflicita->numrows > 0) {
                            /*
* alterado para liberar o campo tipo licitacao para alteracao
*/
                            db_selectrecord("e54_tipol", $result, true, isset($emprocesso) && $emprocesso == true ? "1" : "1", "", "", "");
                            $dop = $db_opcao;
                        } else {

                            $e54_tipol = '';
                            $e54_numerl = '';
                            $dop = '3';
                            db_input('e54_tipol', 8, $Ie54_tipol, true, 'text', 3);
                        }
                    } else {

                        $dop = '3';
                        $e54_tipol = '';
                        $e54_numerl = '';
                        db_input('e54_tipol', 8, $Ie54_tipol, true, 'text', 3);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Te54_codtipo ?>">
                    <strong>Tipo de Empenho:</strong>
                </td>
                <td>
                    <?

                    /*
* alterado para liberar o campo tipo de empenho para alteracao
*/
                    $result = $clemptipo->sql_record($clemptipo->sql_query_file(null, "e41_codtipo,e41_descr"));
                    db_selectrecord("e54_codtipo", $result, true, isset($emprocesso) && $emprocesso == true ? "1" : $db_opcao, "", "", "", "0-Selecione");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Te44_tipo ?>">
                    <?= $Le44_tipo ?>
                </td>
                <td>
                    <?
                    $result = $clempprestatip->sql_record($clempprestatip->sql_query_file(null, "e44_tipo as tipo,e44_descr,e44_obriga", "e44_obriga "));
                    $numrows = $clempprestatip->numrows;
                    $arr = array();
                    for ($i = 0; $i < $numrows; $i++) {

                        db_fieldsmemory($result, $i);
                        if ($e44_obriga == 0 && empty($e44_tipo)) {
                            $e44_tipo = $tipo;
                        }
                        $arr[$tipo] = $e44_descr;
                    }
                    db_select("e44_tipo", $arr, true, 1);
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Te54_destin ?>">
                    <?= @$Le54_destin ?>
                </td>
                <td>
                    <?
                    /*
* alterado para liberar o campo destino para alteracao
*/
                    db_input('e54_destin', 61, $Ie54_destin, true, 'text', isset($emprocesso) && $emprocesso == true ? "1" : $db_opcao, "")
                    ?>
                </td>
            </tr>

            <tr title="N�mero do processo administrativo (PA). M�ximo 15 caract�res.">
                <td nowrap="nowrap">
                    <strong>Processo Administrativo (PA):</strong>
                </td>
                <td colspan="3">
                    <?php
                    db_input('e150_numeroprocesso', 61, $Ie150_numeroprocesso, true, 'text', $db_opcao);
                    ?>
                </td>
            </tr>
            <?
            $anousu = db_getsession("DB_anousu");
            if ($anousu > 2007) {
            ?>
                <tr>
                    <td nowrap title="<?= @$Te54_concarpeculiar ?>">
                        <?
                        db_ancora(@$Le54_concarpeculiar, "js_pesquisae54_concarpeculiar(true);", isset($emprocesso) && $emprocesso == true ? "3" : $db_opcao);
                        ?>
                    </td>
                    <td nowrap="nowrap">
                        <?
                        db_input("e54_concarpeculiar", 10, $Ie54_concarpeculiar, true, "text", isset($emprocesso) && $emprocesso == true ? "3" : $db_opcao, "onChange='js_pesquisae54_concarpeculiar(false);'");
                        db_input("c58_descr", 47, 0, true, "text", 3);
                        ?>
                    </td>
                </tr>
            <?
            } else {
                $e54_concarpeculiar = 0;
                db_input("e54_concarpeculiar", 10, 0, true, "hidden", 3, "");
            }
            ?>
            <tr>
                <td nowrap title="<?= @$Te57_codhist ?>">
                    <?= db_ancora(substr(@$Le40_codhist, 12, 50), "js_pesquisahistorico(true);", isset($emprocesso) && $emprocesso == true ? "1" : "1"); ?>
                </td>
                <td nowrap="nowrap">
                    <?
                    db_input('e57_codhist', 11, $Ie57_codhist, true, '', 1, " onchange='js_pesquisahistorico(false);'");
                    if ($db_opcao == 1) {
                        db_input('e40_descr', 48, $Ie40_descr, true, 'text', 3);
                    } else {
                        db_input('e40_descr', 40, $Ie40_descr, true, 'text', 3);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Te54_resumo ?>" colspan="2">
                    <fieldset>
                        <legend>
                            <strong><?= @$Le54_resumo ?></strong>
                        </legend>
                        <?
                        db_textarea('e54_resumo', 3, 510, $Ie54_resumo, true, 'text', $db_opcao, "", "", "#FFFFFF")
                        ?>

                    </fieldset>
                </td>
            </tr>
        </table>
    </fieldset>

    <div style="margin-top: 10px;">

        <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> onclick="return js_validaLicitacao();<?php ($db_opcao == 1) ? 'return js_salvaCache();' : ' '; ?>">
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

        <? if ($db_opcao == 2) { ?>
            <input name="novo" type="button" id="novo" value="Nova autoriza��o" onclick="js_nova();">

            <?
            $permissao_lancar = db_permissaomenu(db_getsession("DB_anousu"), 398, 3489);
            if ($permissao_lancar == "true") {
            ?>
                <input name="lancemp" type="button" id="lancemp" value="Lan�ar Empenho" onclick="js_lanc_empenho();">
            <?
            }
        }

        if ($db_opcao == 1) { ?>
            <input name="importar" type="button" id="importar" value="Importar autoriza��o" onclick="js_importar();">
        <? } ?>

    </div>

    <? if (isset($emprocesso) && $emprocesso == true) { ?>
        <br>
        <font color="red"><b>Autoriza��o gerada por solicita��o de compras.</b></font>
    <? } ?>

</form>
<script>
    var opcao = <?php echo $db_opcao ?>;
    if (opcao == 1 && document.getElementById('e54_autori').value == "") {
        js_verificatipocompratribunal(document.getElementById('e54_codcom').value);
    }

    if (opcao == 2) {
        if ($('e54_tipoautorizacao').value == '1') {
            document.getElementById('trdadoslicitacao').style.display = "none";
        }
    }

    function js_pesquisahistorico(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_emphist', 'func_emphist.php?funcao_js=parent.js_mostrahistorico1|e40_codhist|e40_descr|e40_historico|e54_resumo', 'Pesquisa', true);
        } else {
            if (document.form1.e57_codhist.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_emphist', 'func_emphist.php?pesquisa_chave=' + document.form1.e57_codhist.value + '&funcao_js=parent.js_mostrahistorico', 'Pesquisa', false);

            } else {
                document.form1.e57_codhist.value = '';
                document.form1.e40_descr.value = '';
                document.form1.e54_resumo.value = '';
            }
        }
    }

    function js_mostrahistorico1(chave, chave1, chave2, chave3) {

        document.form1.e57_codhist.value = chave;
        document.form1.e40_descr.value = chave1;
        document.form1.e54_resumo.value = chave2;
        db_iframe_emphist.hide();
    }

    function js_mostrahistorico(chave, chave1, erro) {
        document.form1.e40_descr.value = chave;
        if (erro == false)
            document.form1.e54_resumo.value = chave1;
        else
            document.form1.e54_resumo.value = '';

        db_iframe_emphist.hide();
    }

    function js_validaLicitacao() {

        if ($('e54_codcomdescr').value === '' || $('e54_codcomdescr').value === '') {
            alert("Usu�rio:\nO Tipo de Compra deve ser informado");
            return false;
        }

        if (codigotribunal != 13 && $('e54_tipoautorizacao').value == '1') {
            alert("Usu�rio:\nO tipo de compra selecionado n�o pode ser utilizado para autoriza��o direta. Gentileza alterar para o tipo de autoriza��o adequado");
            return false;
        }

        if ($('e54_tipoautorizacao').value == '3' && $('e54_codlicitacao').value == '') {
            alert("Usu�rio:\nA licita��o deve ser informada.");
            return false;
        }

        if ($('e54_tipoautorizacao').value == '2' && $('e54_licoutrosorgaos').value == '') {
            alert("Usu�rio:\nA licita��o deve ser informada.");
            return false;
        }

        if ($('e54_tipoautorizacao').value == '4' && $('e54_adesaoregpreco').value == '') {
            alert("Usu�rio:\nA Ades�o de registro de pre�o deve ser informada.");
            return false;
        }

        if ($('e54_tipoautorizacao').value == '0') {
            alert("Usu�rio:\nO tipo de autoriza��o deve ser informado.");
            return false;
        }

        if ($('dop').value != 3 && ($('e54_numerl').value == '' || $('e54_numerl').value == 0)) {
            alert("Para esse Tipo de Compra � necess�rio informar o campo N�mero da Licita��o.");
            return false;
        } else {
            return true;
        }
    }
    var db_opcao = <?php echo $db_opcao; ?>;

    js_mostrarancora();

    var oDBFormCache = new DBFormCache('oDBFormCache', 'db_frmempautoriza.php');

    oDBFormCache.setElements(new Array(
        $('e54_codtipo'),
        $('e54_codtipodescr'),
        $('e57_codhist'),
        $('e57_codhistdescr'),
        $('e54_concarpeculiar'),
        $('c58_descr'),
        $('e44_tipo'),
        $('e54_tipoautorizacao')
    ));

    if (db_opcao == 1) {
        oDBFormCache.load();
        oDBFormCache.save();
    }

    function js_salvaCache() {

        oDBFormCache.save();
        return true;
    }

    function js_pesquisae54_concarpeculiar(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_concarpeculiar',
                'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|' +
                'c58_sequencial|c58_descr', 'Pesquisa', true, '0', '1');
        } else {
            if (document.form1.e54_concarpeculiar.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_empautoriza',
                    'db_iframe_concarpeculiar',
                    'func_concarpeculiar.php?pesquisa_chave=' + document.form1.e54_concarpeculiar.value +
                    '&funcao_js=parent.js_mostraconcarpeculiar', 'Pesquisa', false);
            } else {
                document.form1.c58_descr.value = '';
            }
        }
    }

    function js_mostraconcarpeculiar(chave, erro) {
        document.form1.c58_descr.value = chave;
        if (erro == true) {
            document.form1.e54_concarpeculiar.focus();
            document.form1.e54_concarpeculiar.value = '';
        }
    }

    function js_mostraconcarpeculiar1(chave1, chave2) {
        document.form1.e54_concarpeculiar.value = chave1;
        document.form1.c58_descr.value = chave2;
        db_iframe_concarpeculiar.hide();
    }

    function js_nova() {
        // e40_historico
        destin = document.form1.e54_destin.value;
        resumo = document.form1.e54_resumo.value;
        numcgm = document.form1.e54_numcgm.value;
        nome = document.form1.z01_nome.value;
        parent.location.href = "emp1_empautoriza001.php?z01_nome=" + nome + "&e54_numcgm=" + numcgm + "&e54_destin=" + destin + "&e54_resumo=" + resumo;
    }
    // lan�ar empenho
    function js_lanc_empenho() {

        autori = document.form1.e54_autori.value;
        var iElemento = $F("o58_codele");

        parent.location.href = "<?= $sUrlEmpenho ?>?iElemento=" + iElemento + "&chavepesquisa=" + autori + "&lanc_emp=true";
    }

    function completaElemento(iElemento) {

        //alert(iElemento);
        $("o58_codele").value = iElemento;
    }

    function js_reload(valor) {
        obj = document.createElement('input');
        obj.setAttribute('name', 'tipocompra');
        obj.setAttribute('type', 'hidden');
        obj.setAttribute('value', valor);
        document.form1.appendChild(obj);
        // document.form1.submit();
    }

    function js_pesquisae54_numcgm(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_cgm', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true, '0', '1');
        } else {
            if (document.form1.e54_numcgm.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + document.form1.e54_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false);
            } else {
                document.form1.z01_nome.value = '';
            }
        }
    }

    function js_mostracgm(erro, chave) {

        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.e54_numcgm.focus();
            document.form1.e54_numcgm.value = '';
        } else {
            js_debitosemaberto();
        }
    }

    function js_mostracgm1(chave1, chave2) {

        document.form1.e54_numcgm.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_cgm.hide();

        js_debitosemaberto();
    }

    function js_pesquisae54_login(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_db_usuarios', 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome', 'Pesquisa', true, '0', '1');
        } else {
            if (document.form1.e54_login.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_db_usuarios', 'func_db_usuarios.php?pesquisa_chave=' + document.form1.e54_login.value + '&funcao_js=parent.js_mostradb_usuarios', 'Pesquisa', false);
            } else {
                document.form1.nome.value = '';
            }
        }
    }

    function js_mostradb_usuarios(chave, erro) {
        document.form1.nome.value = chave;
        if (erro == true) {
            document.form1.e54_login.focus();
            document.form1.e54_login.value = '';
        }
    }

    function js_mostradb_usuarios1(chave1, chave2) {
        document.form1.e54_login.value = chave1;
        document.form1.nome.value = chave2;
        db_iframe_db_usuarios.hide();
    }

    function js_importar() {
        js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_empautoriza', 'func_empautoriza.php?funcao_js=parent.js_importar02|e54_autori', 'Pesquisa', true, '0', '1');
    }

    function js_importar02(chave) {
        db_iframe_empautoriza.hide();
        if (confirm("Deseja realmente importar a autoriza��o " + chave + "?")) {
            <?
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?autori_importa='+chave";
            ?>
        }
    }

    function js_pesquisa() {
        <?
        if ($db_opcao == 2 || $db_opcao == 22) {
            $iframe = "selempautoriza";
        } else {
            $iframe = "selempautoriza";
        }
        ?>
        js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_<?= $iframe ?>', 'func_<?= $iframe ?>.php?funcao_js=parent.js_preenchepesquisa|e54_autori', 'Pesquisa', true, '0', '1');
    }

    function js_preenchepesquisa(chave) {
        db_iframe_<?= $iframe ?>.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }

    /**
     * Procura se o fornecedor possui d�bitos em aberto
     */
    function js_debitosemaberto() {

        var sUrlRPC = 'com4_notificafornecedor.RPC.php';
        var iCgm = $('e54_numcgm').value;

        if ($('pesquisar')) {
            $('pesquisar').disabled = true;
        }

        if ($('novo')) {
            $('novo').disabled = true;
        }

        if ($('lancemp')) {
            $('lancemp').disabled = true;
        }

        if ($('importar')) {
            $('importar').disabled = true;
        }

        $('db_opcao').disabled = true;

        js_divCarregando('Aguarde, verificando d�bitos em aberto...', "msgBoxDebitosEmAberto");

        var oParam = new Object();
        oParam.sExecucao = 'debitosEmAberto';
        oParam.iNumCgm = iCgm;
        oParam.sLiberacao = "A";

        var oAjax = new Ajax.Request(sUrlRPC, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornodebitosemaberto
        });
    }

    /**
     * Retorno com os d�bitos em aberto e informa��es de configura��o
     */
    function js_retornodebitosemaberto(oAjax) {

        js_removeObj("msgBoxDebitosEmAberto");

        var oRetorno = eval("(" + oAjax.responseText + ")");
        var iNumCgm = new Number(oRetorno.iNumCgm);
        var iParamFornecDeb = new Number(oRetorno.iParamFornecDeb);
        var iDebitosEmAberto = new Number(oRetorno.iDebitosEmAberto);
        var lParamGerarNotifDebitos = oRetorno.lParamGerarNotifDebitos;

        if (iParamFornecDeb == 1) {

            if ($('pesquisar')) {
                $('pesquisar').disabled = false;
            }

            if ($('novo')) {
                $('novo').disabled = false;
            }

            if ($('lancemp')) {
                $('lancemp').disabled = false;
            }

            if ($('importar')) {
                $('importar').disabled = false;
            }

            $('db_opcao').disabled = false;
        } else if (iParamFornecDeb == 2) {

            if (iDebitosEmAberto > 0) {

                var sMensagem = 'O fornecedor ' + iNumCgm + ' possui d�bitos em aberto.';
                sMensagem += '\n Deseja Notifica-lo?';
                if (confirm(sMensagem)) {
                    js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);
                } else {
                    js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, false);
                }
            } else {

                if ($('pesquisar')) {
                    $('pesquisar').disabled = false;
                }

                if ($('novo')) {
                    $('novo').disabled = false;
                }

                if ($('lancemp')) {
                    $('lancemp').disabled = false;
                }

                if ($('importar')) {
                    $('importar').disabled = false;
                }

                $('db_opcao').disabled = false;
            }
        } else if (iParamFornecDeb == 3) {

            if (iDebitosEmAberto > 0) {

                alert('O fornecedor ' + iNumCgm + ' possui d�bitos em aberto.');

                js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);

            } else {
                if ($('pesquisar')) {
                    $('pesquisar').disabled = false;
                }

                if ($('novo')) {
                    $('novo').disabled = false;
                }

                if ($('lancemp')) {
                    $('lancemp').disabled = false;
                }

                if ($('importar')) {
                    $('importar').disabled = false;
                }

                $('db_opcao').disabled = false;
            }
        }
    }

    /**
     * Executa a notifica��o de d�bitos ao fornecedor
     */
    function js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, aFormaNotificacao, lGerarNotificacaoDebito, lMostrarJanela) {

        var iOrigem = 3;
        var iCodigoOrigem = $('e54_autori').value;

        oNotificarDebitos = new dbViewNotificaFornecedor(iNumCgm, iOrigem);
        oNotificarDebitos.setCodigoOrigem(iCodigoOrigem);
        oNotificarDebitos.setGerarNotificacaoDebito(lGerarNotificacaoDebito);
        if (lMostrarJanela) {

            oNotificarDebitos.setFormaNotificacao(aFormaNotificacao, true);
            if (aFormaNotificacao.length > 0) {
                oNotificarDebitos.show();
            } else {
                oNotificarDebitos.setFormaNotificacao(aFormaNotificacao, false);
            }
        } else {

            oNotificarDebitos.setGerarNotificacaoDebito(false);
            oNotificarDebitos.setFormaNotificacao(0, false);
        }

        /**
         * Retorno do processo de notifica��o de debitos
         */
        oNotificarDebitos.setCallBack(function(oRetorno) {

            if (oRetorno.lFormaNotifEmail) {
                alert(oRetorno.sMessage.urlDecode());
            }

            if (oRetorno.lFormaNotifCarta) {
                js_emitircartanotificacao(oRetorno.iCodigoNotificaBloqueioFornecedor);
            }

            if ($('pesquisar')) {
                $('pesquisar').disabled = false;
            }

            if ($('novo')) {
                $('novo').disabled = false;
            }

            if ($('lancemp')) {
                $('lancemp').disabled = false;
            }

            if ($('importar')) {
                $('importar').disabled = false;
            }

            $('db_opcao').disabled = false;
            if (iParamFornecDeb == 3) {
                $('e54_numcgm').value = '';
                $('z01_nome').value = '';
            }
        });
    }

    function js_emitircartanotificacao(iCodigoNotificaBloqueioFornecedor) {

        var jan = window.open('com2_emitircartanotificacao002.php?iCodigoNotificaBloqueioFornecedor=' + iCodigoNotificaBloqueioFornecedor,
            '',
            'width=' + (screen.availWidth - 5) +
            ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
    }

    /**
     * funcao para retornar licitacao
     */
    function js_pesquisa_liclicita(mostra) {
        if (mostra == true) {

            js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_liclicita', 'func_liclicita.php?funcao_js=parent.js_preencheLicitacao|l20_codigo|l20_numero|l20_edital|l20_objeto|l20_anousu',
                'Pesquisa Licita��es', true);
        } else {

            if (document.form1.e54_codlicitacao.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_empautoriza', 'db_iframe_liclicita', 'func_liclicita.php?&autoriza=true&pesquisa_chave=' +
                    document.form1.e54_codlicitacao.value + '&funcao_js=parent.js_preencheLicitacao1',
                    'Pesquisa', false);
            } else {
                document.form1.e54_codlicitacao.value = '';
            }
        }
    }

    /**
     * funcao para preencher licitacao da ancora
     */
    function js_preencheLicitacao(codigo, modalidade, processo, l20_objeto, ano) {
        js_buscaTipos({
            codigo,
            modalidade,
            processo,
            l20_objeto,
            ano
        });
    }

    function js_retornoTipos(oTipos, dadosLicitacao) {
        let tipcom = oTipos.l03_codcom;
        let tipocomtribunal = oTipos.l03_pctipocompratribunal;
        document.form1.e54_nummodalidade.value = dadosLicitacao.modalidade;
        document.form1.e54_numerl.value = dadosLicitacao.processo + '/' + dadosLicitacao.ano;
        document.form1.e54_codcom.value = tipcom;
        document.form1.e54_codcomdescr.value = tipcom;
        document.form1.e54_codlicitacao.value = dadosLicitacao.codigo;
        document.form1.l20_objeto.value = dadosLicitacao.l20_objeto;
        if (tipocomtribunal == 101 || tipocomtribunal == 102 || tipocomtribunal == 103 || tipocomtribunal == 104) {
            document.form1.e54_tipoorigem.value = 3;
        } else {
            document.form1.e54_tipoorigem.value = 2;
        }
        js_verificatipocompratribunal(tipcom);
        db_iframe_liclicita.hide();
    }

    function js_preencheLicitacao1(l20_objeto, modalidade, processo, ano, tipcom, tipocomtribunal, erro) {
        document.form1.l20_objeto.value = l20_objeto;
        document.form1.e54_nummodalidade.value = modalidade;
        document.form1.e54_numerl.value = processo + '/' + ano;
        document.form1.e54_codcom.value = tipcom;
        if (tipocomtribunal == 101 || tipocomtribunal == 102 || tipocomtribunal == 103 || tipocomtribunal == 104) {
            document.form1.e54_tipoorigem.value = 3;
        } else {
            document.form1.e54_tipoorigem.value = 2;
        }
        js_verificatipocompratribunal(tipcom);
    }

    /**
     * fun��o para retornar licita��es de outros orgaos
     */

    function js_pesquisaac16_licoutroorgao(mostra) {
        if (mostra == true) {
            var sUrl = 'func_liclicitaoutrosorgaos.php?funcao_js=parent.js_buscalicoutrosorgaos|lic211_sequencial|z01_nome|lic211_processo|lic211_numero|lic211_anousu|lic211_tipo';
            js_OpenJanelaIframe('', 'db_iframe_liclicitaoutrosorgaos', sUrl, 'Pesquisar', true, '0');
        } else {
            if (document.form1.e54_licoutrosorgaos.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_liclicitaoutrosorgaos', 'func_liclicitaoutrosorgaos.php?poo=true&pesquisa_chave=' + document.form1.e54_licoutrosorgaos.value + '&funcao_js=parent.js_mostrarlicoutroorgao',
                    'Pesquisar licita��o Outro �rg�o',
                    false,
                    '0');
            } else {
                $('z01_nome').value = '';
            }
        }
    }

    /**
     * fun��o para carregar os dados da licita��o selecionada no campo
     */
    function js_buscalicoutrosorgaos(codigo, fornecedor, processo, modalidade, ano, tipo) {
        document.form1.e54_licoutrosorgaos.value = codigo;
        document.form1.e54_fornelicoutrosorgaos.value = fornecedor;
        document.form1.e54_nummodalidade.value = modalidade;
        document.form1.e54_numerl.value = processo + '/' + ano;
        document.form1.e54_tipoorigem.value = tipo;
        if (tipo == 5) {
            js_tipocompra(105);
        } else if (tipo == 6) {
            js_tipocompra(106);
        } else if (tipo == 7) {
            js_tipocompra(107);
        } else if (tipo == 8) {
            js_tipocompra(108);
        } else if (tipo == 9) {
            js_tipocompra(109);
        }
        db_iframe_liclicitaoutrosorgaos.hide();
    }

    function js_mostrarlicoutroorgao(chave, tipo, processo, modalidade, ano, erro) {
        document.form1.e54_fornelicoutrosorgaos.value = chave;
        document.form1.e54_tipoorigem.value = tipo;
        document.form1.e54_nummodalidade.value = modalidade;
        document.form1.e54_numerl.value = processo + '/' + ano;
        if (tipo == 5) {
            js_tipocompra(105);
        } else if (tipo == 6) {
            js_tipocompra(106);
        } else if (tipo == 7) {
            js_tipocompra(107);
        } else if (tipo == 8) {
            js_tipocompra(108);
        } else if (tipo == 9) {
            js_tipocompra(109);
        }
        if (erro == true) {
            document.form1.z01_nome.focus();
        }
    }

    /**
     * funcao para buscar as ades�es de registro de preco
     * */

    function js_pesquisaaadesaoregpreco(mostra) {
        if (mostra == true) {
            var sUrl = 'func_adesaoregprecos.php?funcao_js=parent.js_buscaadesaoregpreco|si06_sequencial|si06_objetoadesao|si06_numeroadm|si06_nummodadm|si06_anomodadm';
            js_OpenJanelaIframe('', 'db_iframe_adesaoregprecos', sUrl, 'Pesquisar', true, '0');
        } else {
            if (document.form1.e54_adesaoregpreco.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_adesaoregprecos', 'func_adesaoregprecos.php?par=true&pesquisa_chave=' + document.form1.e54_adesaoregpreco.value + '&funcao_js=parent.js_mostraradesao',
                    'Pesquisar', false, '0');
            } else {
                document.getElementById('si06_objetoadesao').value = '';
            }
        }
    }

    /**
     * funcao para carregar adesao de registro de preco escolhida no campo
     * */
    function js_buscaadesaoregpreco(sequencial, objeto, processo, modalidade, ano) {
        document.form1.e54_adesaoregpreco.value = sequencial;
        document.form1.si06_objetoadesao.value = objeto;
        document.form1.e54_numerl.value = processo + '/' + ano;
        document.form1.e54_nummodalidade.value = modalidade;
        document.form1.e54_tipoorigem.value = 4;
        js_tipocompra(104);
        db_iframe_adesaoregprecos.hide();
    }

    function js_mostraradesao(objeto, processo, modalidade, ano, erro) {
        document.form1.si06_objetoadesao.value = objeto;
        document.form1.e54_numerl.value = processo + '/' + ano;
        document.form1.e54_nummodalidade.value = modalidade;
        document.form1.e54_tipoorigem.value = 4;
        js_tipocompra(104);
        if (erro == true) {
            document.form1.si06_objetoadesao.focus();
        }
    }

    function js_handleChangeTipoAutorizacao() {
        js_mostrarancora();
        js_desabilitaTipoCompra();

        const e54_tipoautorizacao = document.querySelector('#e54_tipoautorizacao');
        if (e54_tipoautorizacao.value == '1') {
            js_habilitaTipoCompra();

        }
    }

    function js_habilitaTipoCompra() {
        const e54_codcom = document.querySelector('#e54_codcom');
        const e54_codcomdescr = document.querySelector('#e54_codcomdescr');
        e54_codcom.options[e54_codcom.options.length] = new Option('0', '0');
        e54_codcomdescr.options[e54_codcomdescr.options.length] = new Option('Selecione', '0');

        let atributos = "background-color:#FFF; pointer-events: auto; touch-action: auto;";

        e54_codcom.style.cssText = atributos;
        e54_codcom.removeAttribute('readonly');
        e54_codcom.value = '0';

        e54_codcomdescr.style.cssText = atributos;
        e54_codcomdescr.removeAttribute('readonly');
        e54_codcomdescr.value = '0';
    }

    function js_desabilitaTipoCompra() {

        const e54_codcom = document.querySelector('#e54_codcom');
        const e54_codcomdescr = document.querySelector('#e54_codcomdescr');

        let atributos = "background-color:#DEB887; pointer-events: none; touch-action: none;";

        e54_codcom.style.cssText = atributos;
        e54_codcom.setAttribute('readonly', 'true');

        e54_codcomdescr.style.cssText = atributos;
        e54_codcomdescr.setAttribute('readonly', 'true');
    }

    function js_mostrarancora() {
        let itipolic = document.form1.e54_tipoautorizacao.value;

        if (itipolic == 2) {
            document.getElementById('trlicitacao').style.display = 'none';
            document.getElementById('trlicoutrosorgaos').style.display = '';
            document.getElementById('tradesao').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('e54_licoutrosorgaos').value = '';
            document.getElementById('e54_fornelicoutrosorgaos').value = '';
            document.getElementById('trdadoslicitacao').value = '';
            //document.getElementById('e54_nummodalidade').value = '';
            //document.getElementById('e54_numerl').value = '';
            document.getElementById('e54_adesaoregpreco').value = '';
            document.getElementById('si06_objetoadesao').value = '';
            document.form1.e54_nummodalidade.readOnly = true;
            document.form1.e54_numerl.readOnly = true;
            document.getElementById('e54_concarpeculiar').value = '000';
            document.getElementById('c58_descr').value = 'N�O SE APLICA';

        } else if (itipolic == 3) {
            document.getElementById('trlicitacao').style.display = '';
            document.getElementById('trlicoutrosorgaos').style.display = 'none';
            document.getElementById('tradesao').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('trdadoslicitacao').value = '';
            //document.getElementById('e54_codlicitacao').value = '';
            //document.getElementById('l20_objeto').value = '';
            //document.getElementById('e54_nummodalidade').value = '';
            //document.getElementById('e54_numerl').value = '';
            document.getElementById('e54_adesaoregpreco').value = '';
            document.getElementById('si06_objetoadesao').value = '';
            document.form1.e54_nummodalidade.readOnly = true;
            document.form1.e54_numerl.readOnly = true;
            document.getElementById('e54_concarpeculiar').value = '000';
            document.getElementById('c58_descr').value = 'N�O SE APLICA';

        } else if (itipolic == 4) {
            document.getElementById('trlicitacao').style.display = 'none';
            document.getElementById('trlicoutrosorgaos').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('trdadoslicitacao').value = '';
            document.getElementById('tradesao').style.display = '';
            document.getElementById('e54_codlicitacao').value = '';
            //document.getElementById('e54_nummodalidade').value = '';
            //document.getElementById('e54_numerl').value = '';
            document.getElementById('e54_concarpeculiar').value = '000';
            document.getElementById('c58_descr').value = 'N�O SE APLICA';
            document.form1.e54_nummodalidade.readOnly = true;
            document.form1.e54_numerl.readOnly = true;

        } else if (itipolic == 1) {
            document.getElementById('trlicitacao').style.display = 'none';
            document.getElementById('trlicoutrosorgaos').style.display = 'none';
            document.getElementById('tradesao').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('e54_codlicitacao').value = '';
            document.getElementById('l20_objeto').value = '';
            document.getElementById('e54_nummodalidade').value = '';
            document.getElementById('e54_numerl').value = '';
            document.getElementById('e54_fornelicoutrosorgaos').value = '';
            document.getElementById('e54_adesaoregpreco').value = '';
            document.getElementById('si06_objetoadesao').value = '';
            document.getElementById('e54_tipoorigem').value = 1;
            document.getElementById('e54_concarpeculiar').value = '000';
            document.getElementById('c58_descr').value = 'N�O SE APLICA';
            document.form1.e54_nummodalidade.readOnly = false;
            document.form1.e54_numerl.readOnly = false;
            document.getElementById('trdadoslicitacao').style.display = "none";

        }
    }

    function js_reloadautoriza() {
        let itipolic = document.form1.e54_tipoautorizacao.value;

        if (itipolic == 2) {
            document.getElementById('trlicitacao').style.display = 'none';
            document.getElementById('trlicoutrosorgaos').style.display = '';
            document.getElementById('tradesao').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = '';
            document.getElementById('e54_tipoorigem').value = 5;
            document.getElementById('e54_fornelicoutrosorgaos').style.display = 'none';
            document.form1.e54_nummodalidade.readOnly = true;
            document.form1.e54_numerl.readOnly = true;

        } else if (itipolic == 3) {
            document.getElementById('trlicitacao').style.display = '';
            document.getElementById('trlicoutrosorgaos').style.display = 'none';
            document.getElementById('tradesao').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('e54_tipoorigem').value = 2;
            document.getElementById('l20_objeto').style.display = 'none';
            document.form1.e54_nummodalidade.readOnly = true;
            document.form1.e54_numerl.readOnly = true;

        } else if (itipolic == 4) {
            document.getElementById('trlicitacao').style.display = 'none';
            document.getElementById('trlicoutrosorgaos').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('tradesao').style.display = '';
            document.getElementById('e54_tipoorigem').value = 4;
            document.form1.e54_nummodalidade.readOnly = true;
            document.form1.e54_numerl.readOnly = true;

        } else if (itipolic == 1) {
            document.getElementById('trlicitacao').style.display = 'none';
            document.getElementById('trlicoutrosorgaos').style.display = 'none';
            document.getElementById('tradesao').style.display = 'none';
            document.getElementById('trtipoorigem').style.display = 'none';
            document.getElementById('si06_objetoadesao').style.display = 'none';
            document.form1.e54_nummodalidade.readOnly = false;
            document.form1.e54_numerl.readOnly = false;
            if (document.form1.e54_numerl.value == '') {
                document.getElementById('trdadoslicitacao').style.display = 'none';
            } else {
                document.getElementById('trdadoslicitacao').style.display = ' ';
            }
        }
    }

    function js_verificatipocompratribunal(value) {

        var sUrlRPC = 'com4_tipocompra.RPC.php';
        var pc50_codcom = value;
        js_divCarregando('Aguarde, carregando informa��es...', 'msgbox');
        var oParam = new Object();
        oParam.sExecucao = 'getTipocompratribunal';
        oParam.Codtipocom = pc50_codcom;

        var oAjax = new Ajax.Request(sUrlRPC, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_validacampos
        });

    }

    function js_validacampos(oAjax) {

        oRetorno = eval("(" + oAjax.responseText + ")");
        codigotribunal = oRetorno.tipocompratribunal;

        if (oRetorno.tipocompratribunal == 13) {
            document.getElementById('trdadoslicitacao').style.display = 'none';
            document.form1.e54_nummodalidade.value = '';
            document.form1.e54_numerl.value = '';
        } else if (oRetorno.tipocompratribunal != 13) {
            validaTipoAutorizacao(oRetorno);
        }
        js_removeObj('msgbox');
    }

    function validaTipoAutorizacao(oRetorno) {
        let tipoAutorizacao = document.getElementById('e54_tipoautorizacao').value;

        if (tipoAutorizacao === '1') {
            alert('Tipo de compra inv�lido para este tipo de autoriza��o');
            document.getElementById('e54_codcom').value = '0';
            document.getElementById('e54_codcomdescr').value = '0';
            return;
        }

        document.getElementById('trdadoslicitacao').style.display = '';
        document.getElementById('e54_codcom').value = oRetorno.tipocompra;
        document.getElementById('e54_codcomdescr').value = oRetorno.tipocompra;
    }

    function js_tipocompra(codigotipocompratribunal) {
        var sUrlRPC = 'com4_tipocompra.RPC.php';
        var oParam = new Object();
        oParam.sExecucao = 'getTipocompra';
        oParam.oTipocompratribunal = codigotipocompratribunal;
        var oAjax = new Ajax.Request(sUrlRPC, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_preenchetipocompra
        });

    }

    function js_preenchetipocompra(oAjax) {
        oRetorno = eval("(" + oAjax.responseText + ")");
        document.getElementById('trdadoslicitacao').style.display = '';
        document.getElementById('e54_codcom').value = oRetorno.tipocompra;
        document.getElementById('e54_codcomdescr').value = oRetorno.tipocompra;
    }

    function somenteNumeros(num) {
        var er = /[^0-9.]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
            campo.value = "";
        }
    }

    function js_buscaTipos(licitacao) {
        let oParam = new Object();
        oParam.exec = 'findTipos';
        oParam.iLicitacao = licitacao.codigo;
        let oAjax = new Ajax.Request('lic4_licitacao.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: (res) => {
                onCompleteTipos(res, licitacao)
            }
        });
    }

    function onCompleteTipos(response, aDadosLicitacao) {
        let oRetorno = JSON.parse(response.responseText);
        let oTiposRetornados = oRetorno.dadosLicitacao;
        js_retornoTipos(oTiposRetornados, aDadosLicitacao);
    }

    js_reloadautoriza();
</script>
