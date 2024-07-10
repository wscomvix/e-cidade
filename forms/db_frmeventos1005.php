<?
//MODULO: esocial
$cleventos1005->rotulo->label();
?>
<form name="form1" method="post" action="">
    <fieldset id = "fieldset1" style="margin-top: 30px">
        <legend>Identifica��o do estabelecimento, obra de constru��o civil ou unidade de �rg�o p�blico</legend>
        <table border="0">
            <tr style="display: none">
                <td nowrap title="<?=@$Teso06_sequencial?>">
                    <input name="oid" type="hidden" value="<?=@$eso06_sequencial?>">
                    <?=@$Leso06_sequencial?>
                </td>
                <td>
                    <?
                    db_input('eso06_sequencial',10,$Ieso06_sequencial,true,'text',3,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_tipoinscricao?>">
                    <strong>Preencher com o c�digo correspondente ao tipo de inscri��o:</strong>
                </td>
                <td>
                    <?
                    $x = array("0"=>"Selecione","1"=>"CNPJ","3"=>"CAEPF","4"=>"CNO");
                    db_select('eso06_tipoinscricao',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_nroinscricaoobra?>">
                    <strong>Informar o n�mero de inscri��o do estabelecimento, obra de constru��o civil ou �rg�o p�blico:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_nroinscricaoobra',14,$Ieso06_nroinscricaoobra,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset2">
        <legend>Detalhamento das informa��es do estabelecimento, obra de constru��o civil ou unidade de �rg�o p�blico:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_codcnaf?>">
                    <strong>Preencher com o c�digo CNAE conforme legisla��o vigente, referente � atividade econ�mica preponderante do estabelecimento:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_codcnaf',7,$Ieso06_codcnaf,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset3">
        <legend>Informa��es de apura��o da al�quota GILRAT do estabelecimento:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_aliquotarat?>">
                    <strong>Informar a al�quota RAT, quando divergente da legisla��o vigente para a atividade (CNAE) preponderante:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_aliquotarat',1,$Ieso06_aliquotarat,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_fatoracidentario?>">
                    <strong>Fator Acident�rio de Preven��o - FAP</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_fatoracidentario',5,$Ieso06_fatoracidentario,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset4">
        <legend>Processo administrativo ou judicial em que houve decis�o/senten�a favor�vel ao contribuinte modificando a al�quota RAT da empresa:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_codtipoprocessorat?>">
                    <strong>Preencher com o c�digo correspondente ao tipo de processo:</strong>
                </td>
                <td>
                    <?
                    $x = array("0"=>"Selecione","1"=>"Administrativo","2"=>"Judicial");
                    db_select('eso06_codtipoprocessorat',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_nroprocessos1070rat?>">
                    <strong>Informar um n�mero de processo cadastrado atrav�s do evento S-1070:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_nroprocessos1070rat',21,$Ieso06_nroprocessos1070rat,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_codindicativosuspensaos1070rat?>">
                    <strong>C�digo do indicativo da suspens�o, atribu�do pelo empregador em S-1070:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_codindicativosuspensaos1070rat',14,$Ieso06_codindicativosuspensaos1070rat,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset5">
        <legend>Processo administrativo/judicial em que houve decis�o ou senten�a favor�vel ao contribuinte suspendendo ou alterando a al�quota FAP aplic�vel ao contribuinte:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_codtipoprocessofap?>">
                    <strong>Preencher com o c�digo correspondente ao tipo de processo</strong>
                </td>
                <td>
                    <?
                    $x = array("0"=>"Selecione","1"=>"Administrativo","2"=>"Judicial","3"=>"Processo FAP de exerc�cio anterior a 2019");
                    db_select('eso06_codtipoprocessofap',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_nroprocessos1070fap?>">
                    <strong>Informar um n�mero de processo cadastrado atrav�s do evento S-1070:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_nroprocessos1070fap',21,$Ieso06_nroprocessos1070fap,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso06_codindicativosuspensaos1070fap?>">
                    <strong>C�digo do indicativo da suspens�o, atribu�do pelo empregador em S-1070:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_codindicativosuspensaos1070fap',14,$Ieso06_codindicativosuspensaos1070fap,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset6">
        <legend>Informa��es relativas ao Cadastro de Atividade Econ�mica da Pessoa F�sica - CAEPF:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_tipocaepf?>">
                    <strong>Tipo de CAEPF:</strong>
                </td>
                <td>
                    <?
                    $x = array("0"=>"Selecione","1"=>"Contribuinte individual","3"=>"Produtor rural","4"=>"Segurado especial");
                    db_select('eso06_tipocaepf',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset7">
        <legend>Cadastro Nacional de Obras:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_subscontribuicaoobra?>">
                    <strong>Indicativo de substitui��o da contribui��o patronal de obra de constru��o civil:</strong>
                </td>
                <td>
                    <?
                    $x = array("0"=>"Selecione","3003747"=>"Contribui��o Patronal Substitu�da","3003748"=>"Contribui��o Patronal N�o Substitu�da");
                    db_select('eso06_subscontribuicaoobra',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset8">
        <legend>Informa��es relacionadas � contrata��o de aprendiz:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_nroprocessojudicia?>">
                    <strong>Preencher com o n�mero do processo judicia:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_nroprocessojudicia',20,$Ieso06_nroprocessojudicia,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset9">
        <legend>Identifica��o da(s) entidade(s) educativa(s) ou de pr�tica desportiva:</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_nroinscricaoenteducativa?>">
                    <strong>Informar o n�mero de inscri��o da entidade educativa ou de pr�tica desportiva:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_nroinscricaoenteducativa',14,$Ieso06_nroinscricaoenteducativa,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id = "fieldset10">
        <legend>Informa��es sobre a contrata��o de pessoa com defici�ncia (PCD):</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso06_nroprocessocontratacaodeficiencia?>">
                    <strong>Informar o n�mero de inscri��o da entidade educativa ou de pr�tica desportiva:</strong>
                </td>
                <td>
                    <?
                    db_input('eso06_nroprocessocontratacaodeficiencia',20,$Ieso06_nroprocessocontratacaodeficiencia,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <div style="margin-left: 40%; margin-top: 10px">
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </div>
</form>
<script>
    function js_pesquisa(){
        js_OpenJanelaIframe('top.corpo','db_iframe_eventos1005','func_eventos1005.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
    }
    function js_preenchepesquisa(chave){
        db_iframe_eventos1005.hide();
        <?
        if($db_opcao!=1){
            echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
        }
        ?>
    }
    var ofieldset3 = new DBToogle('fieldset3', false);
    var ofieldset4 = new DBToogle('fieldset4', false);
    var ofieldset5 = new DBToogle('fieldset5', false);
    var ofieldset6 = new DBToogle('fieldset6', false);
    var ofieldset7 = new DBToogle('fieldset7', false);
    var ofieldset8 = new DBToogle('fieldset8', false);
    var ofieldset9 = new DBToogle('fieldset9', false);
    var ofieldset10 = new DBToogle('fieldset10', false);
</script>
