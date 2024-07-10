<?
//MODULO: esocial
$cleventos1020->rotulo->label();
?>
<style>
    #eso08_codempregadorlotacao {
        width: 160px;
        height: 17px;
    }

    #eso08_codtipolotacao {
        width: 120px;
    }

    #eso08_codtipoinscricao {
        width: 120px;
    }
</style>
<form name="form1" method="post" action="">
    <fieldset id="fieldset1" style="margin-top: 30px">
        <legend>Detalhamento das informa��es da Lota��o</legend>
        <table border="0">
            <tr style="display: none">
                <td nowrap title="<?= @$Teso08_sequencial ?>">
                    <input name="oid" type="hidden" value="<?= @$oid ?>">
                    <strong>Sequencial:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_sequencial', 20, $Ieso08_sequencial, true, 'text', 3, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_codempregadorlotacao ?>">
                    <strong>Informar o c�digo atribu�do pelo empregador para a lota��o tribut�ria:</strong>
                </td>
                <td>
                    <?
                    db_textarea('eso08_codempregadorlotacao', 0, 0, $Ieso08_codempregadorlotacao, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_codtipolotacao ?>">
                    <strong>Preencher com o c�digo correspondente ao tipo de lota��o:</strong>
                </td>
                <td>
                    <?
                    //db_input('eso08_codtipolotacao',2,$Ieso08_codtipolotacao,true,'text',$db_opcao,"")
                    $x = array(
                        "0" => "Selecione",
                        "1" => "Classifica��o da atividade econ�mica exercida pela Pessoa Jur�dica para fins de atribui��o de c�digo FPAS",
                        "2" => "Obra de Constru��o Civil - Empreitada Parcial ou Subempreitada",
                        "3" => "Pessoa F�sica tomadora de servi�os prestados mediante cess�o de m�o de obra, exceto contratante de cooperativa",
                        "4" => "Pessoa Jur�dica tomadora de servi�os prestados mediante cess�o de m�o de obra, exceto contratante de cooperativa, nos termos da Lei 8.212/1991",
                        "5" => "Pessoa Jur�dica tomadora de servi�os prestados por cooperados por interm�dio de cooperativa de trabalho, exceto aqueles prestados a entidade beneficente/isenta",
                        "6" => "Entidade beneficente/isenta tomadora de servi�os prestados por cooperados por interm�dio de cooperativa de trabalho",
                        "7" => "Pessoa F�sica tomadora de servi�os prestados por cooperados por interm�dio de cooperativa de trabalho",
                        "8" => "Operador portu�rio tomador de servi�os de trabalhadores avulsos",
                        "9" => "Contratante de trabalhadores avulsos n�o portu�rios por interm�dio de sindicato",
                        "10" => "Embarca��o inscrita no Registro Especial Brasileiro - REB",
                        "21" => "Classifica��o da atividade econ�mica ou obra pr�pria de constru��o civil da Pessoa F�sica",
                        "24" => "Empregador dom�stico",
                        "90" => "Atividades desenvolvidas no exterior por trabalhador vinculado ao Regime Geral de Previd�ncia Social (expatriados)",
                        "91" => "Atividades desenvolvidas por trabalhador estrangeiro vinculado a Regime de Previd�ncia Social no exterior"
                    );
                    db_select('eso08_codtipolotacao', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_codtipoinscricao ?>">
                    <strong>Preencher com o c�digo correspondente ao tipo de inscri��o:</strong>
                </td>
                <td>
                    <?
                    $x = array(
                        "0" => "Selecione",
                        "1" => "CNPJ",
                        "2" => "CPF",
                        "3" => "CAEPF (Cadastro de Atividade Econ�mica de Pessoa F�sica)",
                        "4" => "CNO (Cadastro Nacional de Obra)",
                        "5" => "CGC",
                        "6" => "CEI"
                    );
                    db_select('eso08_codtipoinscricao', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_numeroinscricao ?>">
                    <strong>Preencher com o n�mero de inscri��o (CNPJ, CPF, CNO) ao qual pertence a lota��o tribut�ria:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_numeroinscricao', 14, $Ieso08_numeroinscricao, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset2" style="margin-top: 30px">
        <legend>Informa��es de FPAS e Terceiros relativos � lota��o tribut�ria</legend>
        <table>
            <tr>
                <td nowrap title="<?= @$Teso08_codfpas ?>">
                    <strong>Preencher com o c�digo relativo ao FPAS:</strong>
                </td>
                <td>
                    <?
                    $x = array(
                        "11" => "Selecione",
                        "3003576" => "Ind�stria, Escrit�rio e Dep�sito de Empresa Industrial, Ind�stria de carnes e derivados entre outros",
                        "3003577" => "Com�rcio atacadista, Varejista, Estabelecimento de servi�o de sa�de, Com�rcio transportador entre outros",
                        "3003578" => "Sindicado e associa��o, trabalhador avulso ou empregador",
                        "3003579" => "Ind�stria de cana-de-a��car e latic�nios, extra��o de madeira, matadouro e abatedouro entre outros",
                        "3003580" => "Empresa de navega��o mar�tima, fluvial e lacustre, Empresa de administra��o e explora��o de portos entre outros",
                        "3003581" => "Empresa aerovi�ria",
                        "3003582" => "Empresa de comunica��o, publicidade, josrnalista.",
                        "3003583" => "Estabelecimento de ensino - Sociedade cooperativa",
                        "3003584" => "�rg�o de poder p�blico",
                        "3003585" => "Cart�rio e tabelionato",
                        "3003586" => "Produtor Rural pessoa f�sica, jur�dica, cons�rcio simplificado de produtores rurais, agroind�stria",
                        "3003587" => "Empresa optante pelo simples nacional, transporte rodovi�rio, transporte simples entre outros",
                        "3003588" => "Tomador de servi�o de transportador rodovi�rio aut�nomo",
                        "3003589" => "Sociedade beneficente de assist�ncia social",
                        "3003590" => "Associa��o desportiva que mant�m equipe de futebol profissional",
                        "3003591" => "Empresa de trabalho tempor�rio",
                        "3003592" => "�rg�o gestor de m�o-de-obra",
                        "3003593" => "Banco comercial e de investimento, Banco de desenvolvimento - caixa eletr�nico entre outros",
                        "3003594" => "Empresa adquirente, consumidora, consignat�ria ou cooperativa, produtor rural de pessoa f�sica e jur�dica",
                        "3003595" => "Associa��o desportiva que mant�m equipe de futebol profissional",
                        "3003596" => "Sindicato federa��o e confedera��o patronal rural, Atividade cooperativista rural entre outros",
                        "3003597" => "Estabelecimento Rural e industrial de sociedade cooperatival",
                        "3003598" => "Tomador de servi�o de trabalhador avulso",
                        "3003599" => "Setor indutrial de agroind�stria e tomador de servi�o trabalhador avulso",
                        "3003600" => "Empregador Dom�stico",
                        "3003601" => "Miss�es diplom�ticas e outros organismos a elas equiparados",
                    );
                    db_select('eso08_codfpas', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_codterceiros ?>">
                    <strong>Preencher com o c�digo de Terceiros:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_codterceiros', 4, $Ieso08_codterceiros, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_codterceiroscombinado ?>">
                    <strong>Informar o c�digo combinado dos Terceiros para os quais o recolhimento est� suspenso em virtude de processos judiciais:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_codterceiroscombinado', 4, $Ieso08_codterceiroscombinado, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset3" style="margin-top: 30px">
        <legend>Identifica��o do processo judicial</legend>
        <table>
            <tr>
                <td nowrap title="<?= @$Teso08_codterceirosprocjudicial ?>">
                    <strong>Informar o c�digo de Terceiro:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_codterceirosprocjudicial', 20, $Ieso08_codterceirosprocjudicial, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_nroprocessojudicial ?>">
                    <strong>Informar um n�mero de processo judicial cadastrado atrav�s do evento S-1070:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_nroprocessojudicial', 20, $Ieso08_nroprocessojudicial, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_codindicasuspensao ?>">
                    <strong>C�digo do indicativo da suspens�o, atribu�do pelo empregador em S-1070:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_codindicasuspensao', 20, $Ieso08_codindicasuspensao, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset4" style="margin-top: 30px">
        <legend>Informa��o complementar que apresenta identifica��o do contratante de obra de constru��o civil sob regime de empreitada parcial ou subempreitada</legend>
        <table>
            <tr>
                <td nowrap title="<?= @$Teso08_tipoinscricaocontratante ?>">
                    <strong>Tipo de inscri��o do contratante:</strong>
                </td>
                <td>
                    <?
                    $x = array("0" => "Selecione", "1" => "CNPJ", "2" => "CPF");
                    db_select('eso08_tipoinscricaocontratante', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_numeroinscricaocontratante ?>">
                    <strong>N�mero de inscri��o (CNPJ/CPF) do contratante:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_numeroinscricaocontratante', 14, $Ieso08_numeroinscricaocontratante, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_tipoinscricaoproprietario ?>">
                    <strong>Tipo de inscri��o do propriet�rio do CNO:</strong>
                </td>
                <td>
                    <?
                    $x = array("0" => "Selecione", "1" => "CNPJ", "2" => "CPF");
                    db_select('eso08_tipoinscricaoproprietario', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_nroinscricaoproprietario ?>">
                    <strong>Preencher com o n�mero de inscri��o (CNPJ/CPF) do propriet�rio do CNO:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_nroinscricaoproprietario', 14, $Ieso08_nroinscricaoproprietario, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset5" style="margin-top: 30px">
        <legend>Informa��es do operador portu�rio:</legend>
        <table>
            <tr>
                <td nowrap title="<?= @$Teso08_aliquotarat ?>">
                    <strong>Preencher com a al�quota RAT definida na legisla��o vigente para a atividade (CNAE) preponderante:</strong>
                </td>
                <td>
                    <?
                    $x = array("0" => "Selecione", "1" => "1", "2" => "2", "3" => "3");
                    db_select('eso08_aliquotarat', $x, true, $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Teso08_fatoracidentario ?>">
                    <strong>Fator Acident�rio de Preven��o FAP:</strong>
                </td>
                <td>
                    <?
                    db_input('eso08_fatoracidentario', 5, $Ieso08_fatoracidentario, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    </center>
    <div style="margin-left: 40%; margin-top: 10px">
        <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </div>
</form>
<script>
    function js_pesquisa() {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_eventos1020', 'func_eventos1020.php?funcao_js=parent.js_preenchepesquisa|0', 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave) {
        db_iframe_eventos1020.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }
    var ofieldset3 = new DBToogle('fieldset3', false);
    var ofieldset4 = new DBToogle('fieldset4', false);
    var ofieldset5 = new DBToogle('fieldset5', false);
</script>
