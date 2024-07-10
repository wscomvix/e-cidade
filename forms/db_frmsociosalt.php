<?php

//MODULO: issqn
use App\Models\Socio;

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir();
$clsocios->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("z01_nome");
if (empty($excluir) && empty($alterar) && isset($opcao) && $opcao != "") {
    $result24 = $clsocios->sql_record($clsocios->sql_query($q95_cgmpri, $q95_numcgm, 'z01_nome,q95_perc, q95_tipo'));
    db_fieldsmemory($result24, 0);
    $result25 = $clcgm->sql_record($clcgm->sql_query_file($q95_numcgm, 'z01_nome as z01_nome_socio'));
    db_fieldsmemory($result25, 0);
}
if (isset($opcao) && $opcao == "alterar") {
    $db_opcao = 2;
} elseif ((isset($opcao) && $opcao == "excluir") || (isset($db_opcao) && $db_opcao == 3)) {
    $db_opcao = 3;
} else {
    $db_opcao = 1;
}
$sql = $clsocios->sql_query_socios($q95_cgmpri, "", "sum(q95_perc) as somaval ");
$result_testaval = pg_exec($sql);
if (pg_numrows($result_testaval) != 0) {
    db_fieldsmemory($result_testaval, 0);

} else $somaval = 0;
?>
<form name="form1" method="post" action="iss1_socios004.php">
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="140" align="center" valign="top">
                <center>
                    <fieldset style="margin-top: 20px;">
                        <legend><b>Cadastro de Socios</b></legend>

                        <table border="0">
                            <tr>
                                <td nowrap title="<?= @$Tq95_cgmpri ?>">
                                    <?= $Lq95_cgmpri ?>
                                </td>
                                <td>
                                    <?php
                                    db_input('somaval', 20, "", true, 'hidden', 3);
                                    db_input('q95_cgmpri', 6, $Iq95_cgmpri, true, 'text', 3);
                                    ?>
                                    <?php
                                    $z01_nome = stripslashes($z01_nome);
                                    db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tq95_numcgm ?>">
                                    <?php
                                    if ($db_opcao == 2) {
                                        $str_01 = 3;
                                    } else {
                                        $str_01 = $db_opcao;
                                    }
                                    db_ancora(@$Lq95_numcgm, "js_pesquisaq95_numcgm(true);", $str_01);
                                    ?>
                                    <input type='hidden' id='fisico_juridico' style="width: 50px;"/>
                                </td>
                                <td>
                                    <?php
                                    db_input('q95_numcgm', 6, $Iq95_numcgm, true, 'text', $str_01, " onchange='js_pesquisaq95_numcgm(false);'")
                                    ?>
                                    <?php
                                    db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '', 'z01_nome_socio');
                                    ?>
                                </td>

                            <tr>
                                <td nowrap title="<?= @$Tq95_tipo ?>">
                                    <?= @$Lq95_tipo ?>
                                </td>
                                <td>
                                    <?php
                                    $aTipo = ['0' => "Selecione...", ...Socio::ASSOCIABLES_WITH_LABEL];
                                    db_select('q95_tipo', $aTipo, true, $db_opcao);
                                    ?>
                                </td>
                            </tr>


                            <tr id='valor_capital' style="display: none;">
                                <td nowrap title="<?= @$Tq95_perc ?>">
                                    <?= @$Lq95_perc ?>
                                </td>
                                <td>
                                    <?php
                                    db_input('q95_perc', 15, $Iq95_perc, true, 'text', $db_opcao, "");
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $sAcaoClick = "";
                            if ($db_opcao == 33 || $db_opcao == 3) {

                                $sAcaoClick = "";
                            } else {
                                $sAcaoClick = " onclick='return js_verificatipo();'";
                            }
                            ?>


                            <tr>
                                <td colspan="2" align="center">
                                    <input
                                        name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>"
                                        type="submit" id="db_opcao"
                                        value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>"
                                        <?= ($db_botao == false ? "disabled" : "") ?> <?= $sAcaoClick ?> >
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </center>
            </td>
        </tr>

        <tr>
            <td colspan="2"> &nbsp;</td>
        </tr>

        <tr>
            <td valign="top">
                <?php
                $chavepri = array("q95_cgmpri" => $q95_cgmpri, "q95_numcgm" => @$q95_numcgm, "q95_tipo" => @$q95_cgmpri);
                $cliframe_alterar_excluir->chavepri = $chavepri;
                $sWhereSocios = "     q95_cgmpri = $q95_cgmpri ";
                $sCampoQ95Tipo = ' ' . Socio::getCaseAssociateLabel() . ' ';

                $cliframe_alterar_excluir->sql = $clsocios->sql_query_socios(null, null, "q95_numcgm,q95_tipo,soc.z01_nome,q95_perc,q95_cgmpri,$sCampoQ95Tipo", null, $sWhereSocios);
                $cliframe_alterar_excluir->campos = "q95_numcgm,z01_nome,q95_perc, tipo ";
                $cliframe_alterar_excluir->legenda = "S�CIOS CADASTRADOS";
                $cliframe_alterar_excluir->msg_vazio = "N�o foi encontrado nenhum registro.";
                $cliframe_alterar_excluir->textocabec = "darkblue";
                $cliframe_alterar_excluir->textocorpo = "black";
                $cliframe_alterar_excluir->fundocabec = "#aacccc";
                $cliframe_alterar_excluir->fundocorpo = "#ccddcc";
                $cliframe_alterar_excluir->formulario = false;
                $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
                ?>
            </td>
        </tr>
        <tr>
            <td align='right'>
                <?php
                $somaval = db_formatar(@$somaval, 'f');
                ?>
                <b>Valor total do capital:
                    <?= @$somaval ?>
                </b>
            </td>
        </tr>
    </table>
</form>
<script>

    // fun��o verifica se q95_cgmpri e q95_numcgm s�o diferentes
    function jc_VerificaCgmCpfIgual() {

        var iEmpresa = $F('q95_cgmpri');
        var iSocio = $F('q95_numcgm');
        if (iEmpresa == iSocio) {
            alert('N�o ser� poss�vel fazer a inclus�o do cgm da pr�pria inscri��o como s�cio');
            $('q95_numcgm').value = '';
            $('q95_numcgm').focus();
            return false;
        } else {
            return true;
        }

    }

    // fun��o que valida o tipo de pessoa, fisica ou juridica, se for fisica, n�o habilitara a op��o s�cio no select q95_tipo
    function js_tipoPessoa() {

        var iTipoPessoa = CurrentWindow.corpo.iframe_issbase.document.form1.z01_cgccpf.value;
        iTipoPessoa = iTipoPessoa.length;

        if (iTipoPessoa <= 11 || iTipoPessoa == "" || iTipoPessoa == null) {

            $('valor_capital').hide();
        }

        $("q95_tipo").options.length = 0;
        $("q95_tipo").options[0] = new Option('Selecione...', '');
        $("q95_tipo").options[1] = new Option('S�cio', '1');
        $("q95_tipo").options[2] = new Option('Respons�vel MEI', '2');
        $("q95_tipo").options[3] = new Option('Respons�vel', '3');
        $("q95_tipo").options[4] = new Option('Socio Administrador', '4');
        $("q95_tipo").options[5] = new Option('Socio Cotista', '5');
        $("q95_tipo").options[5] = new Option('Administrador', '6');
    }


    // fun��o que disponibiliza o campo q95_tipo se o tipo de socio for 1 : socio
    function js_mostraValr_capital() {

        var iTipo = $F('q95_tipo');
        if (iTipo == 1 || iTipo == '1') {
            $('valor_capital').show();
            jc_VerificaCgmCpfIgual();
        } else {
            $('valor_capital').hide();
            $('q95_perc').value = '';
        }
    }

    function js_verificatipo() {

        var iTipo = $F('q95_tipo');
        if (iTipo != 1) {
            $('q95_perc').value = 0;
        }
        if (iTipo == 0 || iTipo == '0') {
            alert('Selecione o tipo de s�cio.');

            return false;
        } else {
            return true;
        }

    }


    function js_cancelar() {

        <?php
        if (isset($q95_cgmpri)) {
            echo "location.href=\"iss1_socios004.php?q95_cgmpri={$q95_cgmpri}&z01_nome={$z01_nome}\";\n";
        }
        ?>
    }

    function js_pesquisaq95_numcgm(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_socios', 'db_iframe_cgm', 'func_nome.php?filtro=3&testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|z01_ender|z01_cgccpf', 'Pesquisa', true, 0);
        } else {
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_socios', 'db_iframe_cgm', 'func_nome.php?filtro=3&testanome=true&pesquisa_chave=' + document.form1.q95_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false, 0);
        }
    }

    function js_mostracgm(erro, chave, chave2) {

        if (chave2 == '') {
            alert('Contribuinte com o CGM desatualizado');
            document.form1.fisico_juridico.value = '';
            document.form1.q95_numcgm.value = '';
            document.form1.z01_nome_socio.value = 'Contribuinte com o CGM desatualizado';
            js_tipoPessoa();
            return false;
        }

        document.form1.z01_nome_socio.value = chave;
        document.form1.fisico_juridico.value = chave2;
        js_tipoPessoa();
        if (erro == true) {
            document.form1.q95_numcgm.focus();
            document.form1.q95_numcgm.value = '';
        }
    }

    function js_mostracgm1(chave1, chave2, chave3, chave4) {
        if (chave3 == '' || chave4 == '') {
            alert('Contribuinte com o CGM desatualizado');
            document.form1.fisico_juridico.value = '';
            document.form1.q95_numcgm.value = '';
            document.form1.z01_nome_socio.value = 'Contribuinte com o CGM desatualizado';

        } else {
            document.form1.fisico_juridico.value = chave4;
            document.form1.q95_numcgm.value = chave1;
            document.form1.z01_nome_socio.value = chave2;
        }
        js_tipoPessoa();
        db_iframe_cgm.hide();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_socios', 'db_iframe_socios', 'func_socios.php?funcao_js=parent.js_preenchepesquisa|q95_numcgm|1', 'Pesquisa', true, 0);
    }

    function js_preenchepesquisa(chave, chave1) {
        db_iframe_socios.hide();
        <?php
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave;";
        }
        ?>
    }

    js_mostraValr_capital();
</script>
