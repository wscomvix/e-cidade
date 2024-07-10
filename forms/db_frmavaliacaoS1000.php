<?
//MODULO: esocial
$clavaliacaoS1000->rotulo->label();
?>
<form name="form1" method="post" action="">
    <fieldset>
        <legend>Detalhamento das Informa��es do Empregador</legend>
        <table border="0">
            <tr style="display: none">
                <td nowrap title="<?=@$Teso05_sequencial?>">
                    <input name="oid" type="hidden" value="<?=@$eso05_sequencial?>">
                    <?=@$Leso05_sequencial?>
                </td>
                <td>
                    <?
                    db_input('eso05_sequencial',10,$Ieso05_sequencial,true,'text',3,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso05_codclassificacaotributaria?>">
                    <strong>Preencher com o c�digo correspondente � classifica��o tribut�ria do contribuinte:</strong>
                </td>
                <td>
                    <?
                    db_input('eso05_codclassificacaotributaria',2,$Ieso05_codclassificacaotributaria,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso05_indicativocooperativa?>">
                    <strong>Indicativo de cooperativa:</strong>
                </td>
                <td>
                    <?
                    $x = array("3003669"=>"N�o e Cooperatva","3003670"=>"Cooperativa de Trabalho","3003671"=>"Cooperativa de Produ��o","3003672"=>"Outras Cooperativas");
                    db_select('eso05_indicativocooperativa',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso05_indicativodeconstrutora?>">
                    <strong>Indicativo de Construtora:</strong>
                </td>
                <td>
                    <?
                    $x = array("f"=>"N�o � Construtora","t"=>"Empresa Construtora");
                    db_select('eso05_indicativodeconstrutora',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso05_indicativodesoneracao?>">
                    <strong>Indicativo de desonera��o da folha:</strong>
                </td>
                <td>
                    <?
                    $x = array("f"=>"N�o aplic�vel","t"=>"Empresa enquadrada nos arts. 7� a 9� da Lei 12.546/2011");
                    db_select('eso05_indicativodesoneracao',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso05_registroeletronicodeempregados?>">
                    <strong>Indica se houve op��o pelo registro eletr�nico de empregados:</strong>
                </td>
                <td>
                    <?
                    $x = array("" => "Selecione","1"=>"N�o optou pelo registro eletr�nico de empregados","2"=>"Optou pelo registro eletr�nico de empregados");
                    db_select('eso05_registroeletronicodeempregados',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Teso05_cnpjdoentefederativoresp?>">
                    <strong>CNPJ do Ente Federativo Respons�vel - EFR:</strong>
                </td>
                <td>
                    <?
                    db_input('eso05_cnpjdoentefederativoresp',14,$Ieso05_cnpjdoentefederativoresp,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset0">
        <legend>Detalhamento das Informa��es do Empregador</legend>
        <table>
            <tr>
                <td>
                    <strong>Indicativo da op��o pelo produtor rural pela forma de tributa��o da contribui��o previdenci�ria:</strong>
                </td>
                <td>
                    <?
                    $x = array("0"=>"Selecione","1"=>"Sobre a comercializa��o da sua produ��o","2"=>"Sobre a folha de pagamento");
                    db_select('eso05_indicativoprodutorrural',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset1">
        <legend>Detalhamento das Informa��es do Empregador</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso05_microempresa?>">
                    <strong>Indicativo de microempresa - ME ou empresa de pequeno porte - EPP para permiss�o de acesso ao m�dulo simplificado:</strong>
                </td>
                <td>
                    <?
                    $x = array("f"=>"Selecione","t"=>"SIM");
                    db_select('eso05_microempresa',$x,true,$db_opcao,"");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="fieldset2">
        <legend>Informa��es Complementares - Empresas Isentas - Dados da Isen��o</legend>
        <table>
            <tr>
                <td>
                    <strong>Sigla e nome do Minist�rio ou Lei que concedeu o Certificado:</strong>
                </td>
                <td>
                    <?
                    db_input('eso05_ideminlei',14,$Ieso05_ideminlei,true,'text',$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>N�mero do Certificado de Entidade Beneficente de Assist�ncia Social, n�mero da portaria de concess�o do Certificado:</strong>
                </td>
                <td>
                    <?
                    db_input('eso05_nrocertificado',14,$Ieso05_nrocertificado,true,'text',$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Data de Emiss�o do Certificado/publica��o da Lei:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('eso05_dtemitcertificado',@$eso05_dtemitcertificado_dia,@$eso05_dtemitcertificado_mes,@$eso05_dtemitcertificado_ano,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Data de Vencimento do Certificado:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('eso05_dtvalcertificado',@$eso05_dtvalcertificado_dia,@$eso05_dtvalcertificado_mes,@$eso05_dtvalcertificado_ano,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Protocolo pedido renova��o:</strong>
                </td>
                <td>
                    <?
                    db_input('eso05_protocolorenov',14,$Ieso05_protocolorenov,true,'text',$db_opcao,"");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Data do protocolo de renova��o:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('eso05_dtprotocolo',@$eso05_dtprotocolo_dia,@$eso05_dtprotocolo_mes,@$eso05_dtprotocolo_ano,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Preencher com a data de publica��o no Di�rio Oficial da Uni�o:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('eso05_dtpublicacao',@$eso05_dtpublicacao_dia,@$eso05_dtpublicacao_mes,@$eso05_dtpublicacao_ano,true,'text',$db_opcao,"")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Preencher com o n�mero da p�gina no DOU referente � publica��o do documento de concess�o do certificado:</strong>
                </td>
                <td>
                    <?
                    db_input('eso05_nropaginadou',14,$eso05_nropaginadou,true,'text',$db_opcao,"");
                    ?>
                </td>
            </tr>

        </table>
    </fieldset>
    <fieldset id="fieldset3">
        <legend>Informa��es exclusivas de organismos internacionais e outras institui��es extraterritoriais</legend>
        <table>
            <tr>
                <td nowrap title="<?=@$Teso05_registroeletronicodeempregados?>">
                    <strong>Indicativo da exist�ncia de acordo internacional para insen��o de multa:</strong>
                </td>
                <td>
                    <?
                    $x = array("" => "Selecione","1"=>"Sem Acordo","2"=>"Com Acordo");
                    db_select('eso05_indicativoacordo',$x,true,$db_opcao,"");
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
        js_OpenJanelaIframe('top.corpo','db_iframe_avaliacaoS1000','func_avaliacaoS1000.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
    }
    function js_preenchepesquisa(chave){
        db_iframe_avaliacaoS1000.hide();
        <?
        if($db_opcao!=1){
            echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
        }
        ?>
    }

    var ofieldset1 = new DBToogle('fieldset0', false);
    var ofieldset1 = new DBToogle('fieldset1', false);
    var ofieldset2 = new DBToogle('fieldset2', false);
    var ofieldset3 = new DBToogle('fieldset3', false);

</script>
