<?
//MODULO: esocial
$cleventos1070->rotulo->label();
?>
<style>
    #eso09_obsproc {
        width: 50%;
    }

    #eso09_indmateriaproc {
        width: 40%;
    }
</style>
<form name="form1" method="post" action="">
    <fieldset id="fieldset1" style="margin-top: 30px">
        <legend>Identifica��o do processo e per�odo de validade das informa��es (ideProcesso)</legend>
        <table border="0">
            <tr style="display: none">
                <td nowrap title="<?= @$Teso09_sequencial ?>">
                    <strong>Sequencial:</strong>
                </td>
                <td>
                    <?
                    db_input('eso09_sequencial', 19, $Ieso09_sequencial, true, 'text', 3, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_tipoprocesso ?>">
                    <strong>Preencher com o c�digo correspondente ao tipo de processo:</strong>
                </td>
                <td>
                    <?
                    $x = array("0" => "Selecione", "1" => "Administrativo", "2" => "Judicial", "4" => "Processo FAP de exerc�cio anterior a 2019");
                    db_select('eso09_tipoprocesso', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_nroprocessoadm ?>">
                    <strong>Informar o n�mero do processo administrativo/judicial de acordo com o tipo informado em tpProc (nrProc):</strong>
                </td>
                <td>
                    <?
                    db_input('eso09_nroprocessoadm', 21, $Ieso09_nroprocessoadm, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset1">
        <legend>Dados do processo (dadosProc)</legend>
        <table>
            <tr>
                <td nowrap title="<?= @$Teso09_indautoria ?>">
                    <strong>Indicativo da autoria da a��o judicial:</strong>
                </td>
                <td>
                    <?
                    $x = array("0" => "Selecione", "1" => "Pr�prio contribuinte", "2" => "Outra entidade, empresa ou empregado");
                    db_select('eso09_indautoria', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_indmateriaproc ?>">
                    <strong>Indicativo da mat�ria do processo:</strong>
                </td>
                <td>
                    <?
                    $x = array("0" => "Selecione", "1" => "Exclusivamente tribut�ria ou tribut�ria e FGTS", "7" => "Exclusivamente FGTS e/ou Contribui��o Social Rescis�ria Exclusivamente FGTS e/ou Contribui��o Social Rescis�ria (Lei Complementar 110/2001)");
                    db_select('eso09_indmateriaproc', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_obsproc ?>">
                    <strong>Observa��es relacionadas ao processo:</strong>
                </td>
                <td>
                    <?
                    db_input('eso09_obsproc', 255, $Ieso09_obsproc, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset1">
        <legend>Informa��es complementares do processo judicial (dadosProcJud)</legend>
        <table>
            <tr>
                <td nowrap title="<?= @$Teso09_indundfederacao ?>">
                    <strong>Identifica��o da Unidade da Federa��o - UF da Se��o Judici�ria:</strong>
                </td>
                <td>
                    <?
                    $x = array(
                        "0" => "Selecione",
                        "AC" => "AC",
                        "AL" => "AL",
                        "AP" => "AP",
                        "AM" => "AM",
                        "BA" => "BA",
                        "CE" => "CE",
                        "DF" => "DF",
                        "ES" => "ES",
                        "GO" => "GO",
                        "MA" => "MA",
                        "MT" => "MT",
                        "MS" => "MS",
                        "MG" => "MG",
                        "PA" => "PA",
                        "PB" => "PB",
                        "PR" => "PR",
                        "PE" => "PE",
                        "PI" => "PI",
                        "RJ" => "RJ",
                        "RN" => "RN",
                        "RS" => "RS",
                        "RO" => "RO",
                        "RR" => "RR",
                        "SC" => "SC",
                        "SP" => "SP",
                        "SE" => "SE",
                        "TO" => "TO"
                    );
                    db_select('eso09_indundfederacao', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_codmuniIBGE ?>">
                    <strong>Preencher com o c�digo do munic�pio, conforme tabela do IBGE:</strong>
                </td>
                <td>
                    <?
                    db_input('eso09_codmuniibge', 7, $Ieso09_codmuniIBGE, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_idvara ?>">
                    <strong>C�digo de identifica��o da Vara:</strong>
                </td>
                <td>
                    <?
                    db_input('eso09_idvara', 4, $Ieso09_idvara, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_codsusp ?>">
                    <strong>C�digo do indicativo da suspens�o, atribu�do pelo empregador:</strong>
                </td>
                <td>
                    <?
                    db_input('eso09_codsusp', 14, $Ieso09_codsusp, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_codsuspexigi ?>">
                    <strong>Indicativo de suspens�o da exigibilidade:</strong>
                </td>
                <td>
                    <?
                    $x = array(
                        "0" => "Selecione",
                        "01" => "Liminar em mandado de seguran�a", "02" => "Dep�sito judicial do montante integral", "03" => "Dep�sito administrativo do montante integral", "04" => "Antecipa��o de tutela", "05" => "Liminar em medida cautelar", "08" => "Senten�a em mandado de seguran�a favor�vel ao contribuinte", "09" => "Senten�a em a��o ordin�ria favor�vel ao contribuinte e confirmada pelo TRF", "10" => "Ac�rd�o do TRF favor�vel ao contribuinte", "11" => "Ac�rd�o do STJ em recurso especial favor�vel ao contribuinte", "12" => "Ac�rd�o do STF em recurso extraordin�rio favor�vel ao contribuinte", "13" => "Senten�a 1� inst�ncia n�o transitada em julgado com efeito suspensivo", "14" => "Contesta��o administrativa FAP", "90" => "Decis�o definitiva a favor do contribuinte", "92" => "Sem suspens�o da exigibilidade"
                    );
                    db_select('eso09_codsuspexigi', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_dtdecisao ?>">
                    <strong>Data da decis�o, senten�a ou despacho administrativo:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('eso09_dtdecisao', @$eso09_dtdecisao_dia, @$eso09_dtdecisao_mes, @$eso09_dtdecisao_ano, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso09_inddeposito ?>">
                    <strong>Indicativo de dep�sito do montante integral:</strong>
                </td>
                <td>
                    <?
                    $x = array("N" => "NAO", "S" => "SIM");
                    db_select('eso09_inddeposito', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <div style="margin-left: 40%; margin-top: 10px">
        <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </div>
</form>
<script>
    function js_pesquisa() {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_eventos1070', 'func_eventos1070.php?funcao_js=parent.js_preenchepesquisa|0', 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave) {
        db_iframe_eventos1070.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }
</script>
