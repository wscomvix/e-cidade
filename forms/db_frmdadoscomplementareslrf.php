<?
//MODULO: contabilidade
$cldadoscomplementareslrf->rotulo->label();
?>
<form name="form1" method="post" action="">

    <table border="0" align="left">
        <tr>
            <td nowrap title="<?=@$Tc218_sequencial?>">

                <?=@$Lc218_sequencial?>
            </td>
            <td align="right">
                <?
                db_input('c218_sequencial',10,$Ic218_sequencial,true,'text',3,"")
                ?>
            </td>
        </tr>

        <tr >
            <td nowrap title="<?=@$Tc218_mesusu?>">
                <b>M�s de refer�ncia</b>
            </td>
            <td align="right">
                <?

                db_select('c218_mesusu',array('Selecione',
                    1 => "Janeiro",
                    2 => "Fevereiro",
                    3 => "Mar�o",
                    4 => "Abril",
                    5 => "Maio",
                    6 => "Junho",
                    7 => "Julho",
                    8 => "Agosto",
                    9 => "Setembro",
                    10 => "Outubro",
                    11 => "Novembro",
                    12 => "Dezembro"),
                    true,1,"onchange='js_getSaldo()'");

                ?>
            </td>
        </tr>
        <? if(db_getsession('DB_anousu') < 2023) {?>
        <tr  <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_passivosreconhecidos?>">
                <b>Valores dos passivos reconhecidos:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_passivosreconhecidos',10,$Ic218_passivosreconhecidos,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <? } ?>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlsaldoatualconcgarantiainterna?>">
                <b>Saldo atual das concess�es com garantia interna:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlsaldoatualconcgarantiainterna',10,$Ic218_vlsaldoatualconcgarantiainterna,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlsaldoatualconcgarantia?>">
                <b>Saldo atual das concess�es com garantia externa:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlsaldoatualconcgarantia',10,$Ic218_vlsaldoatualconcgarantia,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlsaldoatualcontragarantiainterna?>">
                <b>Saldo atual das contragarantias  interna recebidas:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlsaldoatualcontragarantiainterna',10,$Ic218_vlsaldoatualcontragarantiainterna,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlsaldoatualcontragarantiaexterna?>">
                <b>Saldo atual das contragarantias externas recebidas:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlsaldoatualcontragarantiaexterna',10,$Ic218_vlsaldoatualcontragarantiaexterna,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap >
                <b>O limite de concess�o de Garantia foi ultrapassado:</b>
            </td>
            <td align="right">
                <?

                db_select('medidasCorretivas',array('Selecione',1=>'Sim', 2=>'N�o'),true,$db_opcao,"onchange='js_medidasCorretivas();'");
                ?>
            </td>
        </tr>
        <tr style="display:none;" id="medidasCorretivasTextarea">
            <td nowrap colspan="2" title="<?=@$Tc218_medidascorretivas?>">
                <b></b>
                <fieldset>

                    <legend>Medidas corretivas adotadas:</legend>
                    <?

                    db_textarea('c218_medidascorretivas',8,70,0,true,'text',$db_opcao,"","","",4000)
                    ?>
                </fieldset>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_recalieninvpermanente?>">
                <b>Receita de Aliena��o de Investimentos Permanentes:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_recalieninvpermanente',10,$Ic218_recalieninvpermanente,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vldotatualizadaincentcontrib?>">
                <b>Valor da dota��o atualizada de Incentivo a Contribuinte:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vldotatualizadaincentcontrib',10,$Ic218_vldotatualizadaincentcontrib,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlempenhadoicentcontrib?>">
                <b>Valor empenhado de Incentivo a Contribuinte:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlempenhadoicentcontrib',10,$Ic218_vlempenhadoicentcontrib,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vldotatualizadaincentinstfinanc?>">
                <b>Valor da dota��o atualizada de Incentivo concedido por Institui��o Financeira:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vldotatualizadaincentinstfinanc',10,$Ic218_vldotatualizadaincentinstfinanc,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlempenhadoincentinstfinanc?>">
                <b>Valor empenhado de Incentivo concedido por Institui��o Financeira:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlempenhadoincentinstfinanc',10,$Ic218_vlempenhadoincentinstfinanc,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlliqincentcontrib?>">
                <b>Valor Liquidado de Incentivo:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlliqincentcontrib',10,$Ic218_vlliqincentcontrib,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlliqincentinstfinanc?>">
                <b>Valor concedido por Institui��o</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlliqincentinstfinanc',10,$Ic218_vlliqincentinstfinanc,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlirpnpincentcontrib?>">
                <b>Valor Inscrito em RP N�o Processados:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlirpnpincentcontrib',10,$Ic218_vlirpnpincentcontrib,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlirpnpincentinstfinanc?>">
                <b>Valor Inscrito em RP N�o Processados IF:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlirpnpincentinstfinanc',10,$Ic218_vlirpnpincentinstfinanc,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr style="display:none;">
            <td nowrap title="<?=@$Tc218_vlrecursosnaoaplicados?>">
                <b>Recursos do FUNDEB n�o aplicados:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlrecursosnaoaplicados',10,$Ic218_vlrecursosnaoaplicados,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vlapropiacaodepositosjudiciais?>">
                <b>Saldo apurado da apropria��o de dep�sitos judiciais:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlapropiacaodepositosjudiciais',10,$Ic218_vlapropiacaodepositosjudiciais,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr <?php if($tipoInstint == 1): ?> style="display:none;" <?php endif;?>>
            <td nowrap title="<?=@$Tc218_vloutrosajustes?>">
                <b>Valor de outros ajustes n�o considerados:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vloutrosajustes',10,$Ic218_vloutrosajustes,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr id="metaArrecadada" style="display:none;">
            <td nowrap title="<?=@$Tc218_metarrecada?>">
                <b>A meta bimestral de arrecada��o foi cumprida:</b>
            </td>
            <td align="right">
                <?
                db_select('c218_metarrecada',array('Selecione',1=>'Sim', 2=>'N�o'),true,1,"onchange='js_metaBimestral();'");
                ?>
            </td>
        </tr>
        <?php if(db_getsession("DB_anousu") > 2018):?>
        <tr id="ancoramedidasadotadas" style="display:none;">
            <td nowrap colspan="2">
                <?
                $aux->cabecalho      = "<strong>Medidas Adotadas e a Adotar:</strong>";
                $aux->codigo         = "c224_medida";  //chave de retorno da func
                $aux->descr          = "c224_descricao";   //chave de retorno
                $aux->nomeobjeto     = 'metasadotadas';
                $aux->funcao_js      = 'js_mostrarmedidasdotadas';//fun��o javascript que ser� utilizada quando clicar na �ncora
                $aux->funcao_js_hide = 'js_mostrarmedidasdotadas1';//fun��o javascript que ser� utilizada quando colocar um c�digo e sair do campo
                $aux->sql_exec       = "";
                $aux->func_arquivo   = "func_metasadotadas.php";  //func a executar
                $aux->nomeiframe     = "db_iframe_medidasadotadas";
                $aux->localjan       = "";
                $aux->onclick        = "";
                $aux->db_opcao       = 4;
                $aux->tipo           = 2;
                $aux->top            = 0;
                $aux->linhas         = 10;
                $aux->vwhidth        = 400;
                $aux->funcao_gera_formulario();
                ?>
            </td>
        </tr>
        <?php endif;?>
        <tr id="medidasAdotadas" style="display:none;">
            <td nowrap colspan="2" title="<?=@$Tc218_dscmedidasadotadas?>">
                <b></b>
                <fieldset>

                    <legend>Descri��o das Outras Medidas:</legend>
                    <?

                    db_textarea('c218_dscmedidasadotadas',8,70,0,true,'text',$db_opcao,"","","",4000)
                    ?>
                </fieldset>
            </td>

        </tr>
        <tr>
            <td nowrap title="<?=@$Tc218_vldotinicialincentivocontrib?>">
                <b>Valor dota��o inicial de incentivo  a contribuinte:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vldotinicialincentivocontrib',10,$Ic218_vldotinicialincentivocontrib,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap title="<?=@$Tc218_vldotincentconcedinstfinanc?>">
                <b>Valor dota��o de incentivo concedido por institui��o financeira:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vldotincentconcedinstfinanc',10,$Ic218_vldotincentconcedinstfinanc,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap title="<?=@$Tc218_vlajustesrelativosrpps?>">
                <b>Valor de ajustes relativos ao rpps:</b>
            </td>
            <td align="right">
                <?
                db_input('c218_vlajustesrelativosrpps',10,$Ic218_vlajustesrelativosrpps,true,'text',$db_opcao,"")
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <center>
                    <input name="db_opcao" type="button" id="db_opcao" value="<?=($db_opcao==1?"Pr�ximo":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="js_incluirDados();">

                    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
                </center>
            </td>
        </tr>
    </table>

</form>
<script>
    document.getElementById("medidasCorretivas").style.width = "100%";
    document.getElementById("c218_mesusu").style.width = "100%";

    function ajustarSelecao() {
        document.getElementById("tr_inicio_metasadotadas").style.display = "none";
        document.getElementById("metasadotadas").style.width = "512px";
        document.getElementById("c224_descricao").style.width = "321px";
    }
    ajustarSelecao();

    function js_medidasCorretivas(){
        if(document.form1.medidasCorretivas.value == '2'){
            document.getElementById("medidasCorretivasTextarea").style.display = 'none';
        }else{
            document.getElementById("medidasCorretivasTextarea").style.display = '';
        }
    }

    function js_incluirDados(){
        /*VALIDA��ES*/
        if(document.form1.c218_mesusu.value == "0"){
            alert('O campo "M�s de Refer�ncia" n�o foi preenchido.');
            return false;
        }
        <?php if($tipoInstint == 2): ?>
        if(document.form1.c218_metarrecada.value == "0" && ((parseInt(document.form1.c218_mesusu.value)%2) == 0)){
            alert('O campo "A meta bimestral de arrecada��o foi cumprida" n�o foi preenchido.');
            return false;
        }
        if(document.form1.medidasCorretivas.value == 0){
            alert('O campo limite de concess�o de Garantia foi ultrapassado" � obrigat�rio.');
            return false;
        }
        <?php endif; ?>

        <?php if(db_getsession("DB_anousu") > 2018):?>
        let todasmedidas = CurrentWindow.corpo.iframe_dadoscomplementares.document.form1.metasadotadas;
        let medidas = Array.from(todasmedidas.options);
        let valida = true;

        if(CurrentWindow.corpo.iframe_dadoscomplementares.document.form1.c218_metarrecada.value == "2" && (CurrentWindow.corpo.iframe_dadoscomplementares.document.form1.c218_mesusu.value % 2) == 0 && medidas == ""){
            alert('Dados Complementares LRF - Nenhuma Medida Selecionada.');
            return false;
        }

        medidas.forEach(function (itens, key) {
            if(itens.value == 99 && document.form1.c218_dscmedidasadotadas.value == ""){
                valida = false;
                alert('O campo "Descri��o das Outras Medidas" n�o foi preenchido.');
            }
        });

        if(valida == false){
            return;
        }
        <?php endif;?>
        CurrentWindow.corpo.dadoscomplementares.c218_sequencial = document.form1.c218_sequencial.value;
        CurrentWindow.corpo.dadoscomplementares.c218_mesusu = document.form1.c218_mesusu.value;
        <?php if(db_getsession('DB_anousu') < 2023){ ?>
            CurrentWindow.corpo.dadoscomplementares.c218_passivosreconhecidos = document.form1.c218_passivosreconhecidos.value;
        <?php } ?>    
        CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualconcgarantiainterna = document.form1.c218_vlsaldoatualconcgarantiainterna.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualconcgarantia = document.form1.c218_vlsaldoatualconcgarantia.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualcontragarantiainterna = document.form1.c218_vlsaldoatualcontragarantiainterna.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualcontragarantiaexterna = document.form1.c218_vlsaldoatualcontragarantiaexterna.value;
        CurrentWindow.corpo.dadoscomplementares.c218_medidascorretivas = document.form1.c218_medidascorretivas.value;
        CurrentWindow.corpo.dadoscomplementares.c218_recalieninvpermanente = document.form1.c218_recalieninvpermanente.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vldotatualizadaincentcontrib = document.form1.c218_vldotatualizadaincentcontrib.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlempenhadoicentcontrib = document.form1.c218_vlempenhadoicentcontrib.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vldotatualizadaincentinstfinanc = document.form1.c218_vldotatualizadaincentinstfinanc.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlempenhadoincentinstfinanc = document.form1.c218_vlempenhadoincentinstfinanc.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlliqincentcontrib = document.form1.c218_vlliqincentcontrib.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlliqincentinstfinanc = document.form1.c218_vlliqincentinstfinanc.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlirpnpincentcontrib = document.form1.c218_vlirpnpincentcontrib.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlirpnpincentinstfinanc = document.form1.c218_vlirpnpincentinstfinanc.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlrecursosnaoaplicados = document.form1.c218_vlrecursosnaoaplicados.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlapropiacaodepositosjudiciais = document.form1.c218_vlapropiacaodepositosjudiciais.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vloutrosajustes = document.form1.c218_vloutrosajustes.value;
        CurrentWindow.corpo.dadoscomplementares.c218_metarrecada = document.form1.c218_metarrecada.value;
        CurrentWindow.corpo.dadoscomplementares.c218_dscmedidasadotadas = document.form1.c218_dscmedidasadotadas.value;

        CurrentWindow.corpo.dadoscomplementares.c218_vldotinicialincentivocontrib = document.form1.c218_vldotinicialincentivocontrib.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vldotincentconcedinstfinanc = document.form1.c218_vldotincentconcedinstfinanc.value;
        CurrentWindow.corpo.dadoscomplementares.c218_vlajustesrelativosrpps = document.form1.c218_vlajustesrelativosrpps.value;


        <?php if($tipoInstint == 1): ?>
        parent.mo_camada('publicacaoeperiodicidadergf');
        <?php else: ?>
        if(CurrentWindow.corpo.dadoscomplementares.c218_mesusu == 12){
            parent.mo_camada('operacoesdecredito');
        }else{
            parent.mo_camada('publicacaoeperiodicidaderreo');
        }
        <?php endif; ?>

        return false;

    }

    function js_pesquisa(){
        js_OpenJanelaIframe('','db_iframe_dadoscomplementareslrf','func_dadoscomplementareslrf.php?funcao_js=parent.js_preenchepesquisa|c218_sequencial','Pesquisa',true);
    }

    function js_preenchepesquisa(chave){
        db_iframe_dadoscomplementareslrf.hide();
        var oParametros = new Object();

        oParametros.exec = 'getDados';
        oParametros.c218_sequencial = chave;
        js_divCarregando('Carregando...', 'msgBox');

        var oAjaxLista = new Ajax.Request("sic1_dadoscomplementareslrf.RPC.php",
            {
                method: "post",
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: (function(oAjax){
                    <?php if(db_getsession("DB_anousu") > 2022){?>
                        js_removeObj('msgBox');
                    <?php } ?>     
                    oRetorno = eval("(" + oAjax.responseText + ")");
                    oRetornoMedidas = oRetorno.medidasadotadas;
                    oRetorno = oRetorno.dadoscomplementares;
                    // console.log(oRetorno);
                    /* PREENCHE OS FORMUL�RIOS */
                    document.form1.c218_sequencial.value = oRetorno.c218_sequencial;
                    document.form1.c218_mesusu.value = oRetorno.c218_mesusu;
                    document.form1.c218_mesusu.disabled = "true";
                    document.form1.c218_mesusu.style.background = "#DEB887";
                    <?php if(db_getsession('DB_anousu') < 2023) {?>
                        document.form1.c218_passivosreconhecidos.value = oRetorno.c218_passivosreconhecidos;
                    <?php } ?>      
                    document.form1.c218_vlsaldoatualconcgarantiainterna.value = oRetorno.c218_vlsaldoatualconcgarantiainterna;
                    document.form1.c218_vlsaldoatualconcgarantia.value = oRetorno.c218_vlsaldoatualconcgarantia;
                    document.form1.c218_vlsaldoatualcontragarantiainterna.value = oRetorno.c218_vlsaldoatualcontragarantiainterna;
                    document.form1.c218_vlsaldoatualcontragarantiaexterna.value = oRetorno.c218_vlsaldoatualcontragarantiaexterna;
                    document.form1.c218_medidascorretivas.value = oRetorno.c218_medidascorretivas;
                    if(oRetorno.c218_medidascorretivas == ""){
                        CurrentWindow.corpo.iframe_dadoscomplementares.document.form1.medidasCorretivas.value = 2;
                    }
                    document.form1.c218_recalieninvpermanente.value = oRetorno.c218_recalieninvpermanente;
                    document.form1.c218_vldotatualizadaincentcontrib.value = oRetorno.c218_vldotatualizadaincentcontrib;
                    document.form1.c218_vlempenhadoicentcontrib.value = oRetorno.c218_vlempenhadoicentcontrib;
                    document.form1.c218_vldotatualizadaincentinstfinanc.value = oRetorno.c218_vldotatualizadaincentinstfinanc;
                    document.form1.c218_vlempenhadoincentinstfinanc.value = oRetorno.c218_vlempenhadoincentinstfinanc;
                    document.form1.c218_vlliqincentcontrib.value = oRetorno.c218_vlliqincentcontrib;
                    document.form1.c218_vlliqincentinstfinanc.value = oRetorno.c218_vlliqincentinstfinanc;
                    document.form1.c218_vlirpnpincentcontrib.value = oRetorno.c218_vlirpnpincentcontrib;
                    document.form1.c218_vlirpnpincentinstfinanc.value = oRetorno.c218_vlirpnpincentinstfinanc;
                    document.form1.c218_vlrecursosnaoaplicados.value = oRetorno.c218_vlrecursosnaoaplicados;
                    document.form1.c218_vlapropiacaodepositosjudiciais.value = oRetorno.c218_vlapropiacaodepositosjudiciais;
                    document.form1.c218_vloutrosajustes.value = oRetorno.c218_vloutrosajustes;
                    document.form1.c218_metarrecada.value = oRetorno.c218_metarrecada;
                    document.form1.c218_metarrecada.disabled = "true";
                    document.form1.c218_metarrecada.style.background = "#DEB887";
                    document.form1.c218_dscmedidasadotadas.value = oRetorno.c218_dscmedidasadotadas;

                    document.form1.c218_vldotinicialincentivocontrib.value = oRetorno.c218_vldotinicialincentivocontrib;
                    document.form1.c218_vldotincentconcedinstfinanc.value = oRetorno.c218_vldotincentconcedinstfinanc;
                    document.form1.c218_vlajustesrelativosrpps.value = oRetorno.c218_vlajustesrelativosrpps;
                    js_buscarMedidas(oRetornoMedidas);

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_contopcredito.value = oRetorno.c219_contopcredito;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_dscnumeroinst.value = oRetorno.c219_dscnumeroinst;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_dsccontopcredito.value = oRetorno.c219_dsccontopcredito;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_realizopcredito.value = oRetorno.c219_realizopcredito;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditocapta.value = oRetorno.c219_tiporealizopcreditocapta;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoreceb.value = oRetorno.c219_tiporealizopcreditoreceb;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoassundir.value = oRetorno.c219_tiporealizopcreditoassundir;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoassunobg.value = oRetorno.c219_tiporealizopcreditoassunobg;

                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_publiclrf.value = oRetorno.c220_publiclrf;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_dtpublicacaorelatoriolrf.value = oRetorno.c220_dtpublicacaorelatoriolrf.split("-").reverse().join("/");;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_localpublicacao.value = oRetorno.c220_localpublicacao;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_tpbimestre.value = oRetorno.c220_tpbimestre;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_exerciciotpbimestre.value = oRetorno.c220_exerciciotpbimestre;

                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_publicrgf.value = oRetorno.c221_publicrgf;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_dtpublicacaorelatoriorgf.value = oRetorno.c221_dtpublicacaorelatoriorgf.split("-").reverse().join("/");
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_localpublicacaorgf.value = oRetorno.c221_localpublicacaorgf;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_tpperiodo.value = oRetorno.c221_tpperiodo;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_exerciciotpperiodo.value = oRetorno.c221_exerciciotpperiodo;
                    /* PREENCHE AS VARIAVEIS GLOBAIS */
                    CurrentWindow.corpo.dadoscomplementares.c218_sequencial = oRetorno.c218_sequencial;
                    CurrentWindow.corpo.dadoscomplementares.c218_mesusu = oRetorno.c218_mesusu;
                    <?php if(db_getsession('DB_anousu') < 2023){ ?>
                        CurrentWindow.corpo.dadoscomplementares.c218_passivosreconhecidos = oRetorno.c218_passivosreconhecidos;
                    <?php } ?>      
                    CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualconcgarantiainterna = oRetorno.c218_vlsaldoatualconcgarantiainterna;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualconcgarantia = oRetorno.c218_vlsaldoatualconcgarantia;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualcontragarantiainterna = oRetorno.c218_vlsaldoatualcontragarantiainterna;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlsaldoatualcontragarantiaexterna = oRetorno.c218_vlsaldoatualcontragarantiaexterna;
                    CurrentWindow.corpo.dadoscomplementares.c218_medidascorretivas = oRetorno.c218_medidascorretivas;
                    CurrentWindow.corpo.dadoscomplementares.c218_recalieninvpermanente = oRetorno.c218_recalieninvpermanente;
                    CurrentWindow.corpo.dadoscomplementares.c218_vldotatualizadaincentcontrib = oRetorno.c218_vldotatualizadaincentcontrib;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlempenhadoicentcontrib = oRetorno.c218_vlempenhadoicentcontrib;
                    CurrentWindow.corpo.dadoscomplementares.c218_vldotatualizadaincentinstfinanc = oRetorno.c218_vldotatualizadaincentinstfinanc;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlempenhadoincentinstfinanc = oRetorno.c218_vlempenhadoincentinstfinanc;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlliqincentcontrib = oRetorno.c218_vlliqincentcontrib;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlliqincentinstfinanc = oRetorno.c218_vlliqincentinstfinanc;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlirpnpincentcontrib = oRetorno.c218_vlirpnpincentcontrib;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlirpnpincentinstfinanc = oRetorno.c218_vlirpnpincentinstfinanc;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlrecursosnaoaplicados = oRetorno.c218_vlrecursosnaoaplicados;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlapropiacaodepositosjudiciais = oRetorno.c218_vlapropiacaodepositosjudiciais;
                    CurrentWindow.corpo.dadoscomplementares.c218_vloutrosajustes = oRetorno.c218_vloutrosajustes;
                    CurrentWindow.corpo.dadoscomplementares.c218_metarrecada = oRetorno.c218_metarrecada;
                    CurrentWindow.corpo.dadoscomplementares.c218_dscmedidasadotadas = oRetorno.c218_dscmedidasadotadas;

                    CurrentWindow.corpo.dadoscomplementares.c218_vldotinicialincentivocontrib = oRetorno.c218_vldotinicialincentivocontrib;
                    CurrentWindow.corpo.dadoscomplementares.c218_vldotincentconcedinstfinanc = oRetorno.c218_vldotincentconcedinstfinanc;
                    CurrentWindow.corpo.dadoscomplementares.c218_vlajustesrelativosrpps = oRetorno.c218_vlajustesrelativosrpps;



                    CurrentWindow.corpo.operacoesdecredito.c219_contopcredito = oRetorno.c219_contopcredito;
                    CurrentWindow.corpo.operacoesdecredito.c219_dscnumeroinst = oRetorno.c219_dscnumeroinst;
                    CurrentWindow.corpo.operacoesdecredito.c219_dsccontopcredito = oRetorno.c219_dsccontopcredito;
                    CurrentWindow.corpo.operacoesdecredito.c219_realizopcredito = oRetorno.c219_realizopcredito;
                    CurrentWindow.corpo.operacoesdecredito.c219_tiporealizopcreditocapta = oRetorno.c219_tiporealizopcreditocapta;
                    CurrentWindow.corpo.operacoesdecredito.c219_tiporealizopcreditoreceb = oRetorno.c219_tiporealizopcreditoreceb;
                    CurrentWindow.corpo.operacoesdecredito.c219_tiporealizopcreditoassundir = oRetorno.c219_tiporealizopcreditoassundir;
                    CurrentWindow.corpo.operacoesdecredito.c219_tiporealizopcreditoassunobg = oRetorno.c219_tiporealizopcreditoassunobg;

                    CurrentWindow.corpo.publicacaoeperiodicidaderreo.c220_publiclrf = oRetorno.c220_publiclrf;
                    CurrentWindow.corpo.publicacaoeperiodicidaderreo.c220_dtpublicacaorelatoriolrf = oRetorno.c220_dtpublicacaorelatoriolrf;
                    CurrentWindow.corpo.publicacaoeperiodicidaderreo.c220_localpublicacao = oRetorno.c220_localpublicacao;
                    CurrentWindow.corpo.publicacaoeperiodicidaderreo.c220_tpbimestre = oRetorno.c220_tpbimestre;
                    CurrentWindow.corpo.publicacaoeperiodicidaderreo.c220_exerciciotpbimestre = oRetorno.c220_exerciciotpbimestre;

                    CurrentWindow.corpo.publicacaoeperiodicidadergf.c221_publicrgf = oRetorno.c221_publicrgf;
                    CurrentWindow.corpo.publicacaoeperiodicidadergf.c221_dtpublicacaorelatoriorgf = oRetorno.c221_dtpublicacaorelatoriorgf;
                    CurrentWindow.corpo.publicacaoeperiodicidadergf.c221_localpublicacaorgf = oRetorno.c221_localpublicacaorgf;
                    CurrentWindow.corpo.publicacaoeperiodicidadergf.c221_tpperiodo = oRetorno.c221_tpperiodo;
                    CurrentWindow.corpo.publicacaoeperiodicidadergf.c221_exerciciotpperiodo = oRetorno.c221_exerciciotpperiodo;

                    <?php if($db_opcao == 3): ?>

                    // DISABILITA��O DOS CAMPOS SELECT QUANDO A OPCAO � EXCLUSAO
                    document.form1.c218_metarrecada.disabled = false;

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_contopcredito.disabled = true;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_contopcredito.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_realizopcredito.disabled = true;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_realizopcredito.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditocapta.disabled = true;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditocapta.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoreceb.disabled = true;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoreceb.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoassundir.disabled = true;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoassundir.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoassunobg.disabled = true;
                    CurrentWindow.corpo.iframe_operacoesdecredito.document.form1.c219_tiporealizopcreditoassunobg.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_publiclrf.disabled = true;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_publiclrf.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_tpbimestre.disabled = true;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidaderreo.document.form1.c220_tpbimestre.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_publicrgf.disabled = true;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_publicrgf.style.background = "#DEB887";

                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_tpperiodo.disabled = true;
                    CurrentWindow.corpo.iframe_publicacaoeperiodicidadergf.document.form1.c221_tpperiodo.style.background = "#DEB887";

                    <?php endif; ?>
                    <?php if($tipoInstint == 2): ?>
                    if(document.form1.c218_mesusu.value == 12){
                        CurrentWindow.corpo.formaba[1].setAttribute("onclick", "mo_camada('operacoesdecredito')");
                    }else{
                        CurrentWindow.corpo.formaba[1].setAttribute("onclick", "");
                    }
                    if((document.form1.c218_mesusu.value % 2) == 0){
                        document.getElementById("metaArrecadada").style.display = "";
                        document.getElementById("c218_metarrecada").style.width = "100%";
                    }else{
                        document.getElementById("metaArrecadada").style.display = "none";
                    }
                    <?php endif; ?>

                    <?php if(db_getsession("DB_anousu") > 2018):?>
                    js_removeObj('msgBox');
                    if(oRetorno.c218_metarrecada == "2"){
                        document.getElementById("medidasAdotadas").style.display = "";
                        document.getElementById("tr_inicio_metasadotadas").style.display = "";

                    }else{
                        document.getElementById("medidasAdotadas").style.display = "none";
                        document.getElementById("tr_inicio_metasadotadas").style.display = "none";
                    }

                    if(oRetorno.c218_medidascorretivas!=""){
                        document.form1.medidasCorretivas.value = 1;
                        document.getElementById("medidasCorretivasTextarea").style.display = "";
                    }
                    <?php endif;?>

                    js_removeObj('msgBox');
                    if(oRetorno.c218_metarrecada == "2"){
                        document.getElementById("medidasAdotadas").style.display = "";

                    }else{
                        document.getElementById("medidasAdotadas").style.display = "none";
                    }

                    if(oRetorno.c218_medidascorretivas!=""){
                        document.form1.medidasCorretivas.value = 1;
                        document.getElementById("medidasCorretivasTextarea").style.display = "";
                    }
                })
            });
//js_getSaldo();
    }

    function js_getSaldo(){
        <?php if($tipoInstint == 2): ?>
        if((document.form1.c218_mesusu.value % 2) == 0){
            document.getElementById("metaArrecadada").style.display = "";
            document.getElementById("c218_metarrecada").style.width = "100%";
        }else{
            document.getElementById("metaArrecadada").style.display = "none";
        }
        <?php endif;?>
        var oParametros = new Object();
        oParametros.exec = 'getSaldo';
        oParametros.mesReferencia = document.form1.c218_mesusu.value;
        js_divCarregando('Carregando...', 'msgBox');

        var oAjaxLista = new Ajax.Request("sic1_dadoscomplementareslrf.RPC.php",
            {
                method: "post",
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: (function(oAjax){
                    oRetorno = eval("(" + oAjax.responseText + ")");
                    <?php if(db_getsession('DB_anousu') < 2023){ ?>
                        document.form1.c218_passivosreconhecidos.value = oRetorno.si167_vlsaldoatual;
                    <?php } ?>          
                    js_removeObj('msgBox');
                })
            });
        <?php if($tipoInstint == 2): ?>
        if(document.form1.c218_mesusu.value == 12){
            CurrentWindow.corpo.formaba[1].setAttribute("onclick", "mo_camada('operacoesdecredito')");
        }else{
            CurrentWindow.corpo.formaba[1].setAttribute("onclick", "");
        }
        <?php endif; ?>

    }

    function js_metaBimestral(){

        if(document.form1.c218_metarrecada.value == "2"){
            document.getElementById("medidasAdotadas").style.display = "";
            document.getElementById("tr_inicio_metasadotadas").style.display = "";

        }else{
            document.getElementById("medidasAdotadas").style.display = "none";
            document.getElementById("tr_inicio_metasadotadas").style.display = "none";
        }
    }
    <?php if($db_opcao == 2 || $db_opcao == 3): ?>
    js_pesquisa();
    <?php endif; ?>

    function js_mostrarmedidasdotadas1(medida,descricao,chave){
        if (chave==false){

            document.form1.c224_medida.value = medida;
            document.form1.c224_descricao.value = descricao.slice(0,85);
            document.form1.db_lanca.onclick = js_insSelectmetasadotadas;
        } else{
            document.form1.c224_medida.value  = "";
            document.form1.c224_descricao.value = "";
            alert("C�digo inexistente");
        }
    }

    function js_BuscaDadosArquivometasadotadas(chave){
        document.form1.db_lanca.onclick = '';
        if(chave){
            js_OpenJanelaIframe('','db_iframe_medidasadotadas','func_metasadotadas.php?funcao_js=parent.js_mostrarmedidasdotadas|c224_medida|c224_descricao','Pesquisa',true);
        }else{
            js_OpenJanelaIframe('','db_iframe_medidasadotadas','func_metasadotadas.php?pesquisa_chave='+document.form1.c224_medida.value+'&funcao_js=parent.js_mostrarmedidasdotadas1','Pesquisa',false);
        }
    }

    function js_buscarMedidas(codigosmedidas) {
 
        var oParametros = new Object();
        oParametros.exec = 'getMedidas';
        oParametros.codmedidas = codigosmedidas;

        var oAjaxLista = new Ajax.Request("sic1_dadoscomplementareslrf.RPC.php",
            {
                method: "post",
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: (function(oAjax){
                    oRetorno = eval("(" + oAjax.responseText + ")");
                    // console.log(oRetorno);
                    var select = document.getElementById("metasadotadas");

                    oRetorno.medidasadotadas.forEach(function (medida) {
                        var medidaadot = medida.c224_medida;
                        var descri = medida.c224_descricao;
                        var option = new Option(descri.slice(0,85),medidaadot);
                        option.style = "background-color: rgb(248, 236, 7);";
                        // console.log(select);
                        select.add(option);
                    });
                    js_removeObj('msgBox');
                })
            });
    }

</script>
