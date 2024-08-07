<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
$clrotulo = new rotulocampo;
$clrotulo->label("k13_dtvlr");
$clrotulo->label("k13_conta");

$dados = "ordem";
require_once("std/db_stdClass.php");
$iTipoControleRetencaoMesAnterior = 0;
$lUsaData    = true;
/** Verificar utilidade
$aParamentrosCaixa = db_stdClass::getParametro("caiparametro", array(db_getsession("DB_anousu")));
if (count($aParamentrosCaixa) > 0) {
    $iTipoControleRetencaoMesAnterior = $aParamentrosCaixa[0]->e30_retencaomesanterior;
    $lUsaData = $aParamentrosCaixa[0]->e30_usadataagenda=="t"?true:false;
}
*/
?>
<style type="text/css">
    .pesquisaConta {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: none;
        overflow-y:auto;
        overflow-x: hidden;
        position: absolute;
        max-height: 200px;
    }

    .pesquisaConta li {
        border: 1px solid #ddd;
        margin-top: -1px;  /*Prevent double borders */
        background-color: #f6f6f6;
        padding: 10px;
        text-decoration: none;
        color: black;
        display: block
    }

    .pesquisaConta li:hover:not(.header) {
        background-color: #eee;
    }

    .codtipo {
        display: none;
    }

    .ctapag {
        width: 100%;
    }
</style>
<script>
    function js_mascara(evt){
        var evt = (evt) ? evt : (window.event) ? window.event : "";

        if((evt.charCode >46 && evt.charCode <58) || evt.charCode ==0){//8:backspace|46:delete|190:.
            return true;
        }else{
            return false;
        }
    }
</script>
<BR><BR>

<form name="form1" method="post" action="">
    <center>
        <table  border =0 style='width:90%'>
            <tr>
                <td>
                    <fieldset>
                        <legend><b>Concilia��o Banc�ria</b></legend>
                        <table width="100%">
                            <tr>
                                <td width="50%" valign="top">
                                    <fieldset class='filtros'>
                                        <legend>
                                            <b>Filtros</b>
                                        </legend>
                                        <table border="0" align="left" >
                                            <tr>
                                                <td>
                                                    <b>Data Inicial:</b>
                                                </td>
                                                <td nowrap>
                                                    <?
                                                    db_inputdata("k13_dtvlr_inicial", null, null, null, true, "text", 1);
                                                    ?>
                                                </td>
                                                <td>
                                                    <b>Data Final:</b>
                                                </td>
                                                <td nowrap align="">
                                                    <?
                                                    db_inputdata("k13_dtvlr_final", null, null, null, true, "text", 1);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?=@$Tk13_conta?>">
                                                    <? db_ancora(@$Lk13_conta, "js_pesquisak13_conta(true);", $db_opcao); ?>
                                                </td>
                                                <td  colspan='4' nowrap>
                                                    <?
                                                    db_input('k13_conta', 10, $Ik13_conta, true, 'text', $db_opcao, " onchange='js_pesquisaz01_numcgm(false);'");
                                                    db_input('k13_descr', 46, $Ik13_descr, true, 'text', 3, '');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Tipo de Movimento:</b></td>
                                                <td align="left" colspan="4">
                                                    <?
                                                    $ar = array("false" => "Selecione");
                                                    db_select("tipomovimento", $ar, true, 1, "style='width:100%'");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Tipo de Lan�amento:</b></td>
                                                <td align="left" colspan="4">
                                                    <?
                                                    $ar = array("false" => "Selecione");
                                                    db_select("tipolancamento", $ar, true, 1, "style='width:100%'");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Data da Concilia��o:</b></td>
                                                <td align="left">
                                                    <? db_inputdata("data_conciliacao", null, null, null, true, "text", 1) ?>
                                                </td>
                                                <td><b>Saldo Final Extrato:</b></td>
                                                <td align="left">
                                                    <? db_input("saldo_final_extrato", 16, null, null, true, "text", 1); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>

                                <td rowspan="1" valign="top" height="100%">
                                    <fieldset class='filtros'>
                                        <legend><b>Saldos da Conta</b></legend>
                                        <table>
                                            <tr>
                                                <td style='color:blue' id='descrConta' colspan='4'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Saldo Inicial Tesouraria:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("saldotesouraria",15,null,true,"text",3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Total de Entradas:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("totalcheques",15,null,true,"text",3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Total de Sa�das:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("saldoatual",15,null,true,"text",3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Saldo Conciliado:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("saldoatual",15,null,true,"text",3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign='top'>
                                                    <b>Diferen�a:</b>
                                                </td>
                                                <td valign='top'>
                                                    <?
                                                    db_input("saldoatual",15,null,true,"text",3);
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                                <td width='20%'>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan='4' style='text-align: center'>
                        <input name="pesquisar" id='pesquisar' type="button"  value="Pesquisar" onclick='js_pesquisarOrdens();' />
                        <input name="atualizar" id='atualizar' type="button"  value="Atualizar" onclick='js_configurar()' />
                        <input name="desprocessar" id='desprocessar' type="button" value='Desprocessar' onclick='#' />
                        <input name="emitir_capa" id='emitir_capa' type="button" value='Emitir Capa' onclick='#' />
                        <input name="incluir_pendencias" id='incluir_pendencias' type="button" value='Incluir Pend�ncias' onclick='#' />
                        <input name="nova_conciliacao" id='nova_conciliacao' type="button" value='Nova Concilia��o' onclick='#' />
                    </fieldset>
                </td>
            <tr>
                <td colspan='3'>
                    <fieldset>
                        <div id='gridNotas' style="width: 100%">
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan='5' align='left'>
                    <b><span >**</span>Exibir Lan�amentos</b>
                    <br />
                    <span>
          <fieldset>
            <legend><b>Exibir</b></legend>
            <input type="checkbox" id='configuradas' checked onclick='js_showFiltro("configurada",this.checked)' />
            <label for="configuradas" style='padding:1px;border: 1px solid black; background-color:#d1f07c'>
              <b>Conciliados</b>
            </label>
            <input type="checkbox" id='normais' checked onclick='js_showFiltro("normal",this.checked)' />
            <label for="normais" style='padding:1px;border: 1px solid black;background-color:white'>
              <b>N�o Conciliados</b>
            </label>
            <input type="checkbox" id='comMovs' checked onclick='js_showFiltro("comMov",this.checked)' />
            <label for="comMovs" style='padding:1px;border: 1px solid black;background-color:rgb(222, 184, 135)'>
              <b>Pend�ncia/Implanta��o</b>
            </label>
          </fieldset>
      </span>
                </td>
            </tr>
        </table>
</form>
</center>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<div id='teste'></div>
<script>
    sDataDia = "<?=date("d/m/Y",db_getsession("DB_datausu"))?>";
    iTipoControleRetencaoMesAnterior = <?=$iTipoControleRetencaoMesAnterior?>;
    var aAutenticacoesGlobal = new Array();
    function js_reload(){
        document.form1.submit();
    }
    //-----------------------------------------------------------
    //---ordem 01
    function js_pesquisak13_conta(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('top.corpo',
                'db_iframe_pagordem',
                'func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord|z01_cgccpf',
                'Pesquisa Ordens de Pagamento',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight - 30
            );
        }else{
            ord01 = new Number(document.form1.e82_codord.value);
            ord02 = new Number(document.form1.e82_codord02.value);
            if(ord01 > ord02 && ord01 != "" && ord02 != ""){
                alert("Selecione uma ordem menor que a segunda!");
                document.form1.e82_codord.focus();
                document.form1.e82_codord.value = '';
            }
        }
    }
    function js_mostrapagordem1(chave1,z01_cgccpf){
        if(z01_cgccpf.length == 11){
            if(z01_cgccpf == '00000000000'){
                alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }else{
            if(z01_cgccpf == '' || z01_cgccpf == null ){
                alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }

        if(z01_cgccpf.length == 14){
            if(z01_cgccpf == '00000000000000'){
                alert("ERRO: N�mero do CNPJ est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }else{
            if(z01_cgccpf == '' || z01_cgccpf == null ){
                alert("ERRO: N�mero do CNPJ est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }
        document.form1.e82_codord.value = chave1;
        db_iframe_pagordem.hide();
    }
    //-----------------------------------------------------------
    //---ordem 02
    function js_pesquisae82_codord02(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('top.corpo',
                'db_iframe_pagordem',
                'func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord|z01_cgccpf',
                'Pesquisa Ordens de Pagamento',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight - 30
            );
        }else{
            ord01 = new Number(document.form1.e82_codord.value);
            ord02 = new Number(document.form1.e82_codord02.value);
            if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
                alert("Selecione uma ordem maior que a primeira");
                document.form1.e82_codord02.focus();
                document.form1.e82_codord02.value = '';
            }
        }
    }
    function js_mostrapagordem102(chave1,z01_cgccpf){
        if(z01_cgccpf.length = 11){
            if(z01_cgccpf == '00000000000'){
                alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }

        if(z01_cgccpf.length = 14){
            if(z01_cgccpf == '00000000000000'){
                alert("ERRO: N�mero do CNPJ est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }
        document.form1.e82_codord02.value = chave1;
        db_iframe_pagordem.hide();
    }
    function js_pesquisae60_codemp(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('top.corpo',
                'db_iframe_empempenho',
                'func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp|e60_anousu|z01_cgccpf',
                'Pesquisar Empenhos',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight - 30);
        }else{
            // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
        }
    }
    function js_mostraempempenho2(chave1, iAnoEmepenho, z01_cgccpf){
        if(z01_cgccpf.length == 11){
            if(z01_cgccpf == '00000000000'){
                alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }else{
            if(z01_cgccpf == '' || z01_cgccpf == null ){
                alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }

        if(z01_cgccpf.length == 14){
            if(z01_cgccpf == '00000000000000'){
                alert("ERRO: N�mero do CNPJ est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }else{
            if(z01_cgccpf == '' || z01_cgccpf == null ){
                alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                return false
            }
        }
        document.form1.e60_codemp.value = chave1+'/'+iAnoEmepenho;
        db_iframe_empempenho.hide();
    }

    function js_pesquisaz01_numcgm(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('',
                'func_nome',
                'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                'Pesquisar CGM',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight - 30);
        }else{
            if(document.form1.z01_numcgm.value != ''){

                js_OpenJanelaIframe('',
                    'func_nome',
                    'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+
                    '&funcao_js=parent.js_mostracgm',
                    'Pesquisar CGM',
                    false,
                    22,
                    0,
                    document.width-12,
                    document.body.scrollHeight-30);
            }else{
                document.form1.z01_nome.value = '';
            }
        }
    }
    function js_mostracgm(erro,chave){
        document.form1.z01_nome.value = chave;
        if(erro==true){
            document.form1.z01_numcgm.focus();
            document.form1.z01_numcgm.value = '';
        }
    }

    function js_mostracgm1(chave1,chave2){

        document.form1.z01_numcgm.value = chave1;
        document.form1.z01_nome.value   = chave2;
        func_nome.hide();

    }
    function js_pesquisae42_sequencial(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('',
                'func_nome',
                'func_empageordem.php?funcao_js=parent.js_mostraordem1|e42_sequencial|e42_dtpagamento',
                'Pesquisar OP Auxiliar ',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight-30
            );
        } else {
            if ($F('e42_sequencial') != "") {
                js_OpenJanelaIframe('',
                    'func_nome',
                    'func_empageordem.php?pesquisa_chave='+$F('e42_sequencial')+
                    '&funcao_js=parent.js_mostraordemagenda',
                    'Pesquisar OP Auxiliar',
                    false,
                    22,
                    0,
                    document.width-12,
                    document.body.scrollHeight-30);
            } else {
                $('e42_sequencial').value = '';
            }
        }
    }

    function js_mostraordem1(chave1,chave2){

        document.form1.e42_sequencial.value = chave1;
        document.form1.e42_dtpagamento.value = js_formatar(chave2,"d");
        func_nome.hide();

    }

    function js_mostraordemagenda(chave,erro){

        if(!erro) {
            document.form1.e42_dtpagamento.value = chave;
        } else {

            document.form1.e42_sequencial.value  = '';
            document.form1.e42_dtpagamento.value = '';

        }
    }

    function js_pesquisae42_sequencialmanutencao(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('',
                'func_nome',
                'func_empageordem.php?funcao_js=parent.js_mostraordem3|e42_sequencial|e42_dtpagamento',
                'Pesquisa OP Auxiliar',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight - 30);
        } else {
            if ($F('e42_sequencialmanutencao') != "") {
                js_OpenJanelaIframe('',
                    'func_nome','func_empageordem.php?pesquisa_chave='+$F('e42_sequencialmanutencao')+
                    '&funcao_js=parent.js_mostraordemagenda4',
                    'Pesquisa OP Auxiliar',
                    false,
                    22,
                    0,
                    document.width-12,
                    document.body.scrollHeight-30);
            } else {
                $('e42_dtpagamentomanutencao').value = '';
            }
        }
    }

    function js_mostraordem3(chave1,chave2){

        document.form1.e42_sequencialmanutencao.value = chave1;
        document.form1.e42_dtpagamentomanutencao.value = js_formatar(chave2,"d");
        func_nome.hide();

    }

    function js_mostraordemagenda4(chave,erro){

        if(!erro) {
            document.form1.e42_dtpagamentomanutencao.value = chave;
        } else {

            document.form1.e42_sequencialmanutencao.value  = '';
            document.form1.e42_dtpagamentomanutencao.value = '';

        }
    }

    function js_pesquisac62_codrec(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('top.corpo',
                'db_iframe_orctiporec',
                'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                'Pesquisar Recursos',
                true,
                22,
                0,
                document.body.getWidth() - 12,
                document.body.scrollHeight - 30);
        }else{
            if(document.form1.o15_codigo.value != ''){
                js_OpenJanelaIframe('top.corpo',
                    'db_iframe_orctiporec',
                    'func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+
                    '&funcao_js=parent.js_mostraorctiporec',
                    'Pesquisar Recursos',
                    false,
                    22,
                    0,
                    document.body.getWidth() - 12,
                    document.body.scrollHeight - 30);
            }else{
                document.form1.o15_descr.value = '';
            }
        }
    }
    function js_mostraorctiporec(chave,erro){
        document.form1.o15_descr.value = chave;
        if(erro==true){
            document.form1.o15_codigo.focus();
            document.form1.o15_codigo.value = '';
        }
    }

    function js_mostraorctiporec1(chave1,chave2){
        document.form1.o15_codigo.value = chave1;
        document.form1.o15_descr.value = chave2;
        db_iframe_orctiporec.hide();
    }
    function js_pesquisarOrdens() {

        js_divCarregando("Aguarde, pesquisando Movimentos.","msgBox");
        js_liberaBotoes(false);
        js_reset();
        $('normais').checked = true;
        $('TotalForCol14').innerHTML = js_formatar(0,'f');
        $('TotalForCol13').innerHTML = js_formatar(0,'f');
        $('TotalForCol12').innerHTML = js_formatar(0,'f');
        $('TotalForCol11').innerHTML  = js_formatar(0,'f');
        //Criamos um objeto que tera a requisicao
        var oParam                = new Object();
        oParam.iOrdemIni          = $F('e82_codord');
        oParam.iOrdemFim          = $F('e82_codord02');
        oParam.iCodEmp            = $F('e60_codemp');
        oParam.dtDataIni          = $F('dataordeminicial');
        oParam.dtDataFim          = $F('dataordemfinal');
        oParam.iNumCgm            = $F('z01_numcgm');
        //oParam.sRecursos          = $F('recursos');
        oParam.iRecurso           = $F('o15_codigo');
        oParam.sDtAut             = $F('e42_dtpagamento');
        oParam.iOPauxiliar        = $F('e42_sequencial');
        oParam.iAutorizadas       = $F('ordensautorizadas');
        oParam.iOPManutencao      = $F('e42_sequencialmanutencao');
        oParam.orderBy            = $F('orderby');
        oParam.lVinculadas        = false;
        oParam.e03_numeroprocesso = encodeURIComponent(tagString($F("e03_numeroprocesso")));

        if ($F("recursosvinculados") == "true") {
            oParam.lVinculadas   = true;
        }
        var sParam           = js_objectToJson(oParam);
        url       = 'emp4_manutencaoPagamentoRPC.php';
        var sJson = '{"exec":"getMovimentos","params":['+sParam+']}';
        var oAjax   = new Ajax.Request(
            url,
            {
                method    : 'post',
                parameters: 'json='+sJson,
                onComplete: js_retornoConsultaMovimentos
            }
        );

    }

    function js_retornoConsultaMovimentos(oAjax) {

        js_removeObj("msgBox");
        js_liberaBotoes(true);
        var oResponse = eval("("+oAjax.responseText+")");
        gridNotas.clearAll(true);
        gridNotas.setStatus("");
        var iRowAtiva     = 0;
        var iTotalizador  = 0;
        if (oResponse.status == 1) {

            for (var iNotas = 0; iNotas < oResponse.aNotasLiquidacao.length; iNotas++) {

                with (oResponse.aNotasLiquidacao[iNotas]) {
                    let CNPJ = oResponse.aNotasLiquidacao[iNotas].z01_cgccpf;

                    if(CNPJ.length = 11){
                        if(CNPJ == '00000000000'){
                            alert("ERRO: N�mero do CPF est� zerado. Corrija o CGM do fornecedor e tente novamente");
                            return false
                        }
                    }

                    if(CNPJ.length = 14){
                        if(CNPJ == '00000000000000'){
                            alert("ERRO: N�mero do CNPJ est� zerado. Corrija o CGM do fornecedor e tente novamente");
                            return false
                        }
                    }
                    var nValor =  e81_valor;
                    if (e43_valor > 0 && e43_valor >= e81_valor) {
                        nValor = e43_valor;
                    }
                    nValorTotal  = new Number(nValor - valorretencao).toFixed(2);
                    var lDisabled = false;
                    var lDisabledContaFornecedor = false;
                    var sDisabled = "";
                    if (e91_codmov != '' || e90_codmov != '') {

                        lDisabled                = true;
                        lDisabledContaFornecedor = true;
                        sDisabled                = " disabled ";
                    }

                    if (e97_codforma != 3) {
                        lDisabledContaFornecedor = true;
                    }

                    if (nValor == 0) {
                        continue;
                    }
                    iTotalizador++;
                    var aLinha  = new Array();
                    aLinha[0]   = e81_codmov;
                    aLinha[1]   = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
                    aLinha[1]  += e60_codemp+"/"+e60_anousu+"</a>";
                    aLinha[2]   = o15_codigo;

                    /**
                     * Cria a express�o regular para efetuar a altera��o dos pontos por vazio.
                     */

                    var sRegExpressao = /\./g;
                    var sConCarPeculiar       = e60_concarpeculiar.replace(sRegExpressao, "");
                    /**
                     *  Caso seja vazio, permite o usu�rio selecionar um id. Do contr�rio mostra a concarpeculiar selecionada
                     */
                    if ( sConCarPeculiar == '' || new Number(sConCarPeculiar) == 0 ) {
                        if (e79_concarpeculiar == '') {
                            e79_concarpeculiar = 'Selecione';
                        }
                        aLinha[3]  = "<a href='#' id='ccp_"+e81_codmov+"'";
                        aLinha[3] += "onclick='js_lookupConCarPeculiar("+e81_codmov+");' >"+e79_concarpeculiar+"</a>";
                    } else {
                        aLinha[3] = e60_concarpeculiar;
                    }
                    aLinha[4]   = e50_codord;
                    aLinha[5]   = js_createComboContasPag(e81_codmov, aContasVinculadas, e85_codtipo, lDisabled);
                    aLinha[6]   = z01_nome.urlDecode().substring(0,20);
                    aLinha[7]   = js_createComboContasForne(aContasFornecedor, e98_contabanco, e81_codmov, z01_numcgm);
                    aLinha[8]   = js_createComboForma(e97_codforma,e81_codmov, lDisabled);
                    if (e43_sequencial != "") {
                        aLinha[9]   = "("+e42_sequencial+") - "+js_formatar(e42_dtpagamento,'d');
                    } else {
                        aLinha[9]   = "";
                    }
                    aLinha[10]   = "<span id='valor_com_desconto"+e81_codmov+"'>"+js_formatar( (e53_valor),"f")+"</span>";
                    aLinha[11]   = "<span id='valoraut"+e81_codmov+"'>"+js_formatar(nValor, "f")+"</span>";

                    if (lDisabled) {
                        aLinha[12]  = "<a id='retencao"+e81_codmov+"'>"+js_formatar(valorretencao,"f")+"</a>";
                    } else {

                        aLinha[12]  = "<a href='#'  id='retencao"+e81_codmov+"'";
                        aLinha[12] += " onclick='js_lancarRetencao("+e71_codnota+","+e50_codord+","+e60_numemp+","+e81_codmov+");'>";
                        aLinha[12] += js_formatar(valorretencao,"f")+"</a>";
                        aLinha[12] += "<span style='display:none' id='validarretencao"+e81_codmov+"'>"+validaretencao+"</span>";

                    }
                    var sReadOnly = '';
                    if (valorretencao > 0) {
                        sReadOnly  = ' readonly ';
                    }

//          nValorTotal = (nValorTotal - e53_vlranu).toFixed(2);

                    aLinha[13]  = "<input type = 'text' id='valorrow"+e81_codmov+"' size='9' style='width:100%;height:100%;text-align:right;border:1px inset'";
                    aLinha[13] += " class='valores' onchange='js_calculaValor(this,"+e81_codmov+")'"+sReadOnly;
                    aLinha[13] += "                 onkeypress='return js_teclas(event,this)'";
                    aLinha[13] += "       value = '"+nValorTotal+"' id='valor"+e50_codord+"' "+sDisabled+">";

                    gridNotas.addRow(aLinha, false, lDisabled);

                    if (e91_codmov != '' || e90_codmov != '') {

                        if (!$('comMovs').checked) {

                            iTotalizador--;
                            gridNotas.aRows[iRowAtiva].lDisplayed = false;

                        }
                        gridNotas.aRows[iRowAtiva].aCells[0].lDisabled  = true;
                        gridNotas.aRows[iRowAtiva].setClassName('comMov');

                    } else if (e86_codmov != '' || e97_codmov != '') {

                        if (!$('configuradas').checked) {

                            iTotalizador--;
                            gridNotas.aRows[iRowAtiva].lDisplayed = false;

                        }
                        gridNotas.aRows[iRowAtiva].aCells[0].lDisabled  = true;
                        gridNotas.aRows[iRowAtiva].setClassName('configurada');

                    } else if ($F('e42_sequencialmanutencao') != "" && e42_sequencial == $F('e42_sequencialmanutencao')) {
                        gridNotas.aRows[iRowAtiva].setClassName('naOPAuxiliar');
                    }
                    gridNotas.aRows[iRowAtiva].aCells[7].sEvents  = "onmouseover='js_setAjuda(\""+z01_nome.urlDecode()+"\",true)'";
                    gridNotas.aRows[iRowAtiva].aCells[7].sEvents += "onmouseOut='js_setAjuda(null,false)'";
                    gridNotas.aRows[iRowAtiva].sValue  = e81_codmov;
                    iRowAtiva++;

                }
            }
            gridNotas.renderRows();
            gridNotas.setNumRows(iTotalizador);
            $('gridNotasstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
            if (oResponse.totais.length > 0) {
                var sTotais = "";
                for (var i = 0; i < oResponse.totais.length;i++) {

                    with (oResponse.totais[i]) {

                        sTotais += "<tr>";
                        sTotais += "<td class='linhagrid' style='text-align:laeft'>"+tipo+"</td>";
                        var nValor = 0;
                        if (tipo != "NDA") {
                            nValor  = valor;
                        }
                        sTotais += "<td class='linhagrid' style='text-align:right'>"+js_formatar(nValor,'f')+"</td>";
                        var sValorVinculado = 0;
                        if (tipo == "CHE" ) {
                            sValorVinculado = cheques;
                        }
                        if (tipo == "TRA") {
                            sValorVinculado = transmissao;
                        }
                        sTotais += "<td class='linhagrid' style='text-align:right'>"+js_formatar(sValorVinculado,'f')+"</td>";
                        var nConfigurado = 0;
                        if (tipo == "NDA") {
                            nConfigurado = valor;
                        }
                        sTotais += "<td class='linhagrid' style='text-align:right'>"+js_formatar(nConfigurado,'f')+"</td></tr>";

                    }
                }
                $('totalizadores').innerHTML = sTotais;
            }
        } else if (oResponse.status == 2) {
            gridNotas.setStatus("<b>N�o foram encontrados movimentos.</b>");
        }
    }

    function js_getContaSaltes(objInput){
        var iContaSaltes = objInput.value.split('-')[0].trim();
        return iContaSaltes;
    }

    function js_init() {

        gridNotas              = new DBGrid("gridNotas");
        gridNotas.nameInstance = "gridNotas";
        gridNotas.selectSingle = function (oCheckbox, sRow, oRow,lVerificaSaldo) {

            if (lVerificaSaldo == null) {
                var lVerificaSaldo = true;
            }

            if (oCheckbox.checked) {

                oRow.isSelected    = true;
                $(sRow).className += 'marcado';
                if (oRow.aCells[8].getValue() != "" && lVerificaSaldo) {
                    if ($('ctapag' + oRow.aCells[1].getValue())) {
                        js_getSaldos(oRow.aCells[1].getValue());
                    }
                }
                if (lVerificaSaldo) {

                    $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
                    $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');
                    $('TotalForCol12').innerHTML = js_formatar(gridNotas.sum(12).toFixed(2),'f');
                    $('TotalForCol11').innerHTML  = js_formatar(gridNotas.sum(11).toFixed(2),'f');

                }
                $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)+1;
            } else {

                $(sRow).className = oRow.getClassName();
                oRow.isSelected   = false;
                if (lVerificaSaldo) {

                    $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
                    $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');
                    $('TotalForCol12').innerHTML = js_formatar(gridNotas.sum(12).toFixed(2),'f');
                    $('TotalForCol11').innerHTML  = js_formatar(gridNotas.sum(11).toFixed(2),'f');

                }
                $('total_selecionados').innerHTML = new Number($('total_selecionados').innerHTML)-1;
            }
        }
        gridNotas.selectAll = function(idObjeto, sClasse, sLinha) {

            var obj = document.getElementById(idObjeto);
            if (obj.checked){
                obj.checked = false;
            } else{
                obj.checked = true;
            }

            itens = this.getElementsByClass(sClasse);
            for (var i = 0;i < itens.length ;i++){

                if (itens[i].disabled == false){
                    if (obj.checked == true){

                        if ($(this.aRows[i].sId).style.display != 'none') {
                            if (!itens[i].checked) {

                                itens[i].checked=true;
                                this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);

                            }

                        }
                    } else {

                        if (itens[i].checked) {

                            itens[i].checked=false;
                            this.selectSingle($(itens[i].id), (sLinha+i), this.aRows[i], false);
                        }
                    }
                }
            }

            $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
            $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');
            $('TotalForCol12').innerHTML = js_formatar(gridNotas.sum(12).toFixed(2),'f');
            $('TotalForCol11').innerHTML  = js_formatar(gridNotas.sum(11).toFixed(2),'f');
        }
        gridNotas.setCheckbox(0);
        gridNotas.hasTotalizador = true;
        gridNotas.allowSelectColumns(true);
        gridNotas.setCellWidth(new Array("5%","7%", "5%", "5%","5%","15%","10%", "10%", "10%", "10%", "5%", "5%", "5%", "5%"));
        gridNotas.setCellAlign(new Array("right", "center","right", "center", "right", "left", "left", "center", "center", "center","right","right","right"));
        gridNotas.setHeader(new Array("M",
            "D. Lan�amento",
            "D. Concilia��o",
            "Credor",
            "Tipo",
            "OP/REC/SLIP",
            "Documento",
            "Mov",
            "Valor",
            "Hist�rico"
            )
        );
        gridNotas.aHeaders[1].lDisplayed = false;
        gridNotas.show(document.getElementById('gridNotas'));
        $('gridNotasstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
        // Tarefa 24652
        alert("Estou aqui dentro");
        document.form1.k13_conta.focus();
    }

    function js_createComboContasPag(iCodMov, aContas, iContaConfig, lDisabled) {

        var sDisabled = "";
        if (lDisabled == null) {
            lDisabled = false;
        }
        if (lDisabled) {
            sDisabled = " disabled ";
        }

        var sComboInputHidden  = "<input type='hidden' id='tipoconta"+iCodMov+"' ";
        var sComboInputText = "<input type='text' id='ctapag"+iCodMov+"' class='ctapag' onfocus='this.select();mostrarPesquisa("+iCodMov+")' onkeyup='pesquisaConta("+iCodMov+",event)' onkeydown='pesquisaConta("+iCodMov+",event)' onclick='this.select();' onblur='fecharPesquisa("+iCodMov+");js_getSaldos("+iCodMov+")' placeholder='Selecione' title='' "+sDisabled;

        var sComboUL = "<ul id='pesquisaConta"+iCodMov+"' class='pesquisaConta'>";
        if (aContas != null) {

            for (var i = 0; i < aContas.length; i++) {
                var sDescrConta =  aContas[i].e83_conta+" - "+aContas[i].e83_descr.urlDecode()+" - "+aContas[i].c61_codigo;
                sComboUL += "<li onclick='selecionarConta(this,"+iCodMov+")'><div class='codtipo'>"+aContas[i].e83_codtipo+"</div><span>"+sDescrConta+"</span></li>";
                if (iContaConfig == aContas[i].e83_codtipo) {
                    sComboInputHidden += " value='"+aContas[i].e83_codtipo+"' ";
                    sComboInputText += " value='"+sDescrConta+"' ";
                }
            }
        }
        sComboUL += "</ul>";
        sComboInputHidden += " /> ";
        sComboInputText += " /> ";

        return sComboInputHidden+sComboInputText+sComboUL;
    }

    function js_objectToJson(oObject) {

        var sJson = oObject.toSource();
        sJson     = sJson.replace("(","");
        sJson     = sJson.replace(")","");
        return sJson;

    }

    function js_showFiltro(sQualFiltro,lMostrar) {

        var aMatched     = gridNotas.getElementsByClass(sQualFiltro);
        aMatched     = aMatched.concat(gridNotas.getElementsByClass(sQualFiltro+"marcado"));
        var iTotalizador = 0;
        for (var i = 0; i < aMatched.length; i++) {
            if (lMostrar) {

                aMatched[i].style.display = '';
                iTotalizador++;

            } else {

                aMatched[i].style.display = 'none';
                iTotalizador--;

            }
        }
        var iTotal  = gridNotas.getNumRows();
        gridNotas.setNumRows(iTotal +iTotalizador);
    }

    function js_createComboForma(iTipoForma, iCodMov, lDisabled) {

        var sDisabled = "";
        if (lDisabled == null) {
            lDisabled = false;
        }
        if (lDisabled) {
            sDisabled = " disabled ";
        }
        var sCombo  = "<select style='width:100%' class='formapag' id='forma"+iCodMov+"' "+sDisabled+">";
        sCombo     += "  <option "+(iTipoForma == 0?" selected ":" ")+" value='0'>NDA</option>";
        sCombo     += "  <option "+(iTipoForma == 1?" selected ":" ")+" value='1'>DIN</option>";
        sCombo     += "  <option "+(iTipoForma == 2?" selected ":" ")+" value='2'>CHE</option>";
        sCombo     += "  <option "+(iTipoForma == 3?" selected ":" ")+" value='3'>TRA</option>";
        sCombo     += "  <option "+(iTipoForma == 4?" selected ":" ")+" value='4'>DEB</option>";
        sCombo     += "</select>";
        return sCombo
    }

    function js_createComboContasForne(aContasForne, iContaForne, iCodMov, iNumCgm, lDisabled) {

        var sDisabled = "";
        if (lDisabled == null) {
            lDisabled = false;
        }
        if (lDisabled) {
            sDisabled = " disabled ";
        }

        var sRetorno  = "<select id='ctapagfor"+iCodMov+"' "+sDisabled+" class='cgm' style='width:100%'";
        sRetorno     += " onchange='js_novaConta("+iCodMov+", "+iNumCgm+",this.value)'>";
        sRetorno     += "<option value=''>Selecione</option>";
        sRetorno     += "<option value='n'>Nova Conta</option>";
        if (aContasForne != null) {


            aContasForne.each(
                function (oConta, iLinha) {

                    var sSelecionado = "";
                    /*comentado por solicita��o de barbara OC 6184*/
                    // if (oConta.pc63_contabanco == oConta.conta_historico_fornecedor && iContaForne == "") {
                    //   sSelecionado = " selected ";
                    // } else if (iContaForne != "" && iContaForne == oConta.pc63_contabanco) {
                    //   sSelecionado = " selected ";
                    // } else
                    if (oConta.padrao == "t") {
                        sSelecionado = " selected ";
                    }

                    var sConferido = "";
                    var sOption = "<option value='"+oConta.pc63_contabanco+"' "+sSelecionado+">";
                    if (oConta.pc63_agencia_dig.trim() != ""){
                        oConta.pc63_agencia_dig = "/"+oConta.pc63_agencia_dig;
                    }
                    if (oConta.pc63_conta_dig.trim() != ""){
                        oConta.pc63_conta_dig = "/"+oConta.pc63_conta_dig;
                    }

                    if (oConta.pc63_dataconf.trim() != "" ){
                        sConferido = "** - ";
                    }
                    sOption += sConferido+oConta.pc63_banco+' - '+oConta.pc63_agencia+""+oConta.pc63_agencia_dig+' - '+oConta.pc63_conta+""+oConta.pc63_conta_dig;
                    sOption += "</option>";
                    sRetorno += sOption;
                }
            );


        }
        sRetorno += "</select>";
        return sRetorno;
    }

    function js_dbInputData(sName, value, lDisabled){

        var sDisabled = "";
        if (lDisabled == null) {
            lDisabled = false;
        }
        if (lDisabled) {
            sDisabled = " disabled ";
        }
        var sSaida  = '<input readonly name="'+sName+'" type="text" '+sDisabled+' style="height:100%;width:100%"  id="'+sName+'"';
        sSaida += '   value="'+value+'" size="10"  maxlength="10" autocomplete="off"';
        sSaida += '   onBlur="js_validaDbData(this);" onKeyUp="return js_mascaraData(this,event)"';
        sSaida += '   onSelect="return js_bloqueiaSelecionar(this);" onFocus="js_validaEntrada(this);">';
        sSaida += '<input name="'+sName+'_dia" type="hidden" title="" id="'+sName+'_dia" value=""  maxlength="2" >';
        sSaida += '<input name="'+sName+'_mes" type="hidden" title="" id="'+sName+'_mes" value=""  maxlength="2" >';
        sSaida += '<input name="'+sName+'_ano" type="hidden" title="" id="'+sName+'_ano" value=""  maxlength="4" >';

        return sSaida;
    }

    function js_novaConta(Movimento,iNumCgm, sOpcao ){
        erro = 0;
        if(sOpcao == 'n' || sOpcao == 'button'){
            js_OpenJanelaIframe('top.corpo','db_iframe_pcfornecon',
                'com1_pcfornecon001.php?novo=true&reload=true&z01_numcgm='+iNumCgm,
                'Cadastro de Contas de Fornecedores',true);
        }
    }
    function js_setAjuda(sTexto,lShow) {

        if (lShow) {

            el =  $('gridNotas');
            var x = 0;
            var y = el.offsetHeight;
            while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

                x += el.offsetLeft;
                y += el.offsetTop;
                el = el.offsetParent;

            }
            x += el.offsetLeft;
            y += el.offsetTop;
            $('ajudaItem').innerHTML     = sTexto;
            $('ajudaItem').style.display = '';
            $('ajudaItem').style.top     = y+10;
            $('ajudaItem').style.left    = x;

        } else {
            $('ajudaItem').style.display = 'none';
        }
    }

    function js_configurar() {

        var aMovimentos = gridNotas.getSelection();
        /*
         * Validamos o movimento configurado, conforme a forma de pagamento escolhido.
         * - cheque, � obrigatorio ter informado a conta pagadora, e o valor;
         * - Transmissao � obrigatorio ter informado a conta pagadora, e a conta do fornecedor
         * - Dinheiro , apenas obrigatorio informar  valor.
         * - NDA, ignoramos o registro.
         */

        var lEfetuarPagamento = $('efetuarpagamento').checked;
        var lSemErro          = true;
        var sAviso            = '';

        if (aMovimentos.length == 0) {

            alert('N�o h� nenhum movimento selecionado.');
            return false;

        }

        if ($F('e42_dtpagamento') == "") {

            alert('Data de pagamento nao informado.');
            return false;

        }
        if (js_comparadata(sDataDia,$F('e42_dtpagamento'),">")) {

            alert("Data Informada Inv�lida.\nData menor que a data do sistema");
            return false;

        }
        var oEnvio                   = new Object();
        oEnvio.exec                  = "configurarPagamento";
        oEnvio.lEfetuarPagamento     = lEfetuarPagamento;
        oEnvio.dtPagamento           = $F('e42_dtpagamento');
        oEnvio.aMovimentos           = new Array();
        oEnvio.lEmitirOrdeAuxiliar   = false;
        oEnvio.iOPAuxiliarManutencao = "";
        if ($('emitirordemauxiliar').checked) {
            oEnvio.lEmitirOrdeAuxiliar = true;
        }
        if ($F('e42_sequencialmanutencao') != "" && !$('opmanutencaonda').checked) {

            if (($('opmanutencaoincluir').checked && !$('opmanutencaoexcluir').checked) && oEnvio.lEmitirOrdeAuxiliar) {

                alert('Configura��es escolhidas est�o em conflito: emitir OrdemAuxiliar e incluir movimentos na ordem auxlilar selecionada.');
                return false;

            } else if ($('opmanutencaoincluir').checked && !$('opmanutencaoexcluir').checked) {
                oEnvio.iOPAuxiliarManutencao = $F('e42_sequencialmanutencao');
            }

            if (($('opmanutencaoexcluir').checked && !$('opmanutencaoincluir').checked) && oEnvio.lEmitirOrdeAuxiliar) {

                alert('Configura��es escolhidas est�o em conflito: emitir Ordem Auxiliar e Excluir movimentos na ordem auxlilar selecionada.');
                return false;

            } else if ($('opmanutencaoexcluir').checked && !$('opmanutencaoincluir').checked) {

                oEnvio.iOPAuxiliarManutencao = $F('e42_sequencialmanutencao');
                oEnvio.exec =  'cancelaMovimentoOrdemAuxiliar';

            }
        }

        var aFormasSelecionadas     = new Array();
        var lMostraMsgErroRetencao  = false;
        var sMsgRetencaoMesAnterior = "Aten��o:\n";
        var sVirgula                = "";
        for (var iMov = 0; iMov < aMovimentos.length; iMov++) {

            var iForma               = aMovimentos[iMov][9];
            var iCodMov              = aMovimentos[iMov][0];
            var nValor               = new Number(aMovimentos[iMov][14]);
            var sConCarPeculiar      = aMovimentos[iMov][4];
            var iNota                = aMovimentos[iMov][5];
            var iContaFornecedor     = aMovimentos[iMov][8];
            var iContaPagadora       = aMovimentos[iMov][6];
            var iContaSaltes         = js_getContaSaltes( $('ctapag'+aMovimentos[iMov][0]) );
            var dtAutoriza           = $F('e42_dtpagamento');
            var nValorRetencao       = js_strToFloat(aMovimentos[iMov][13]);
            var lRetencaoMesAnterior = $('validarretencao'+iCodMov).innerHTML;

            /*
             * Fazemos a verificacao para Cheque;
             */
            aFormasSelecionadas.push(iForma);

            if (iForma != 0) {

                if (iContaPagadora == '') {

                    lSemErro = false;
                    sAviso   = "Movimento ("+iCodMov+") da Nota "+iNota+" Sem conta pagadora Informada.";

                }
            }
            /*
             if (iForma == 3 ) {

             if (iContaFornecedor == 'n' || iContaFornecedor == '') {

             lSemErro = false;
             sAviso   = "Movimento ("+iCodMov+") da Nota "+iNota+" sem Conta do fornecedor Configurada.";

             }
             }
             */
            if (lRetencaoMesAnterior == "true") {

                lMostraMsgErroRetencao   = true;
                sMsgRetencaoMesAnterior += sVirgula+"Movimento "+iCodMov+" da OP "+iNota+" possui reten��es ";
                sMsgRetencaoMesAnterior += " configuradas em m�s  diferente do m�s atual\n";
                sVirgula = ", ";

            }
            if (!lSemErro) {

                alert(sAviso);
                return false;
                break;

            }
            if (sConCarPeculiar == "Selecione") {
                sConCarPeculiar = '';
            }
            oMovimento                   = new Object();
            oMovimento.iCodForma         = iForma;
            oMovimento.iCodMov           = iCodMov;
            oMovimento.nValor            = nValor.valueOf();
            oMovimento.iContaFornecedor  = iContaFornecedor;
            oMovimento.iContaPagadora    = iContaPagadora;
            oMovimento.iContaSaltes      = iContaSaltes;
            oMovimento.iCodNota          = iNota;
            oMovimento.nValorRetencao    = nValorRetencao.valueOf();
            oMovimento.sConCarPeculiar   = sConCarPeculiar;
            if (dtAutoriza == "") {
                dtAutoriza = oEnvio.dtPagamento;
            }

            oMovimento.dtPagamento = dtAutoriza;
            oEnvio.aMovimentos.push(oMovimento);
        }

        /*
         * For verificando se todas as formas de pagamento para os movimentos selecionados
         *   sao dinheiro(DIN) ou debito(DEB) caso nao for obrigamos o usuario a corrigir a
         *   forma de pagamento ou desmarcar a opcao de "Efetuar pagamento"
         *
         */
        if (lEfetuarPagamento) {
            for (var iInd = 0; iInd < aFormasSelecionadas.length; iInd++ ) {
                if (aFormasSelecionadas[iInd] != "1" && aFormasSelecionadas[iInd] != "4" ) {
                    alert("Para efetuar pagamento autom�tico somente s�o permitidas as forma de pagamento : Dinheiro (DIN) e D�bito (DEB). Verifique.");
                    return false;
                }
            }

            /**
             * verificamos o parametro para controle de retenc�es em meses anteriores.
             * caso seje 0 - n�o faz nenhuma critica ao usu�rio. apenas realiza o pagamento.
             *           1 - Avisa ao usu�rio e pede uma confirma��o para realizar o pagamento.
             *           2 - Avisa ao usu�rio e cancela o pagamento do movimento
             */
            var sMsgConfirmaPagamento = "Deseja realmente efetuar pagamento para os movimentos selecionados?";
            if (iTipoControleRetencaoMesAnterior == 1) {

                if (lMostraMsgErroRetencao) {

                    sMsgConfirmaPagamento  =  sMsgRetencaoMesAnterior;
                    sMsgConfirmaPagamento += "� Recomend�vel recalcular as reten��es.\n";
                    sMsgConfirmaPagamento += "Deseja realmente efetuar pagamento para os movimentos selecionados?";

                }
            } else if (iTipoControleRetencaoMesAnterior == 2) {

                if (lMostraMsgErroRetencao) {

                    sMsgConfirmaPagamento    =  sMsgRetencaoMesAnterior;
                    sMsgRetencaoMesAnterior += "Recalcule as Reten��es do movimento.";
                    alert(sMsgRetencaoMesAnterior);
                    return false;

                }
            }

            var lConfirmacao          = confirm(sMsgConfirmaPagamento);
            if (!lConfirmacao) {
                return false;
            }
        }

        js_divCarregando("Aguarde, Configurando Movimentos.","msgBox");
        js_liberaBotoes(false);
        var sJson = js_objectToJson(oEnvio);
        var oAjax = new Ajax.Request(
            url,
            {
                method    : 'post',
                parameters: 'json='+sJson,
                onComplete: js_retornoConfigurarPagamentos
            }
        );
    }

    function js_retornoConfigurarPagamentos(oAjax) {

        js_removeObj("msgBox");
        js_liberaBotoes(true);
        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.iItipoAutent != 3 && oRetorno.status == 1) {

            iCodigoOrdemAuxiliar = oRetorno.iCodigoOrdemAuxiliar;
            if ($('autenticar').checked) {

                aAutenticacoes       = oRetorno.aAutenticacoes;
                iIndice              = 0;
                js_autenticar(oRetorno.aAutenticacoes[0],false);
                if ($('reemisaoop').checked) {
                    js_emiteOrdens(oRetorno.aAutenticacoes);
                }
            } else {

                alert("Movimentos atualizados com sucesso!");
                if ($('emitirordemauxiliar').checked || ($('opmanutencaoincluir').checked || $('opmanutencaoexcluir').checked)) {
                    js_emitirOrdemAuxiliar($F('e42_dtpagamento'),oRetorno.iCodigoOrdemAuxiliar);
                }

                aAutenticacoesGlobal = oRetorno.aAutenticacoes
                if ($('reemisaoop').checked) {

                    var aMovimentosSelecionados = gridNotas.getSelection();
                    var aMovimentosImprimir = new Array();
                    aMovimentosSelecionados.each(function(aMovimento, iSeq) {
                        aMovimentosImprimir.push(aMovimento[0]);
                    });
                    js_emiteOrdens(aAutenticacoesGlobal, aMovimentosImprimir);
                }
                $('opmanutencaonda').checked = true;
                js_pesquisarOrdens();
            }
        } else {
            alert(oRetorno.message.urlDecode());
        }

    }

    function js_calculaValor(oTextObj, iCodMov) {

        var nValorAut = js_strToFloat($('valoraut'+iCodMov).innerHTML);
        var nRetencao = js_strToFloat($('retencao'+iCodMov).innerHTML);
        var nValorMaximo = nValorAut  - nRetencao;
        if (new Number(oTextObj.value) > nValorMaximo.toFixed(2) || new Number(oTextObj.value) <= 0) {
            oTextObj.value  = nValorMaximo;
        }
    }

    function js_liberaBotoes(lLiberar) {


        if (lLiberar) {

            $('pesquisar').disabled = false;
            $('atualizar').disabled   = false;

        } else {

            $('pesquisar').disabled = true;
            $('atualizar').disabled   = true;

        }
    }

    function js_getSaldos(iCodMov) {
        objTipoConta = document.getElementById("tipoconta"+iCodMov);
        objCtaPag = document.getElementById("ctapag"+iCodMov);
        if (objTipoConta.value != 0) {

            var dtBase = $F('e42_dtpagamento');
            if ($F('e42_dtpagamento') == '') {
                dtBase = sDataDia;
                $('e42_dtpagamento').focus();
            }
            if ($('descrConta').textContent == objCtaPag.value) {
                return false;
            }
            js_divCarregando("Aguarde, Verificando saldo da conta.","msgBox");
            $('descrConta').innerHTML = objCtaPag.value;
            var url       = 'emp4_agendaPagamentoRPC.php';
            var sJson = '{"exec":"getSaldos","params":[{"iCodTipo":"'+objTipoConta.value+'","dtBase":"'+dtBase+'"}]}';
            var oAjax   = new Ajax.Request(
                url,
                {
                    method    : 'post',
                    parameters: 'json='+sJson,
                    onComplete: js_retornoGetSaldos
                }
            );
        }

    }
    function js_retornoGetSaldos(oAjax) {

        js_removeObj("msgBox");
        var oRetorno               = eval("("+oAjax.responseText+")");
        $('saldotesouraria').value = new Number(oRetorno.oSaldoTes.rnvalortesouraria);
        $('totalcheques').value    = new Number(oRetorno.oSaldoTes.rnvalorreservado);
        $('saldoatual').value      = new Number(oRetorno.oSaldoTes.rnsaldofinal).toFixed(2);
    }

    function js_lancarRetencao(iCodNota, iCodOrd, iNumEmp, iCodMov){

        var lSession     = "false";
        var dtPagamento  = $F('e42_dtpagamento');
        var nValor       = new Number($('valorrow'+iCodMov).value);
        var nValorRetido = js_strToFloat($('retencao'+iCodMov).innerHTML);
        if (dtPagamento == '') {

            alert('Antes de recalcular as retencoes, informe a data de pagamento');
            return false;

        }
        js_OpenJanelaIframe('top.corpo', 'db_iframe_retencao',
            'emp4_lancaretencoes.php?iNumNota='+iCodNota+'&nValorBase='+(nValor+nValorRetido)+
            '&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+"&lSession="+lSession
            +'&dtPagamento='+dtPagamento+'&iCodMov='+iCodMov+'&callback=true',
            'Lancar Reten��es',
            true,
            22,
            0,
            document.body.clientWidth,
            document.body.clientHeight);

    }

    function js_atualizaValorRetencao(iCodMov, nValor, iNota, iCodOrdem, lValidar) {

        $('valorrow'+iCodMov).value     = new Number(js_strToFloat($('valoraut'+iCodMov).innerHTML) - new Number(nValor)).toFixed(2);
        $('retencao'+iCodMov).innerHTML = js_formatar(nValor,'f');
        if (new Number(nValor).valueOf() > 0) {
            $('valorrow'+iCodMov).readOnly = true;
        } else {
            $('valorrow'+iCodMov).readOnly = false;
        }
        if (lValidar != null) {
            $('validarretencao'+iCodMov).innerHTML = lValidar;
        }
        db_iframe_retencao.hide();
        $('TotalForCol14').innerHTML = js_formatar(gridNotas.sum(14).toFixed(2),'f');
        $('TotalForCol13').innerHTML = js_formatar(gridNotas.sum(13).toFixed(2),'f');

    }

    function js_setContaPadrao(iCodigoConta) {

        var aItens = gridNotas.getElementsByClass('ctapag');
        var oUltimoCampo = null;
        var contaPadrao = document.getElementById("e83_codtipo");
        for (var i = 0; i < aItens.length; i++) {

            if ($F('e83_codtipo') == "0") {
                aItens[i].value = "";
            }else{
                aItens[i].value = contaPadrao.options[contaPadrao.selectedIndex].text;
                document.getElementById("tipoconta"+aItens[i].id.replace("ctapag","")).value = iCodigoConta;
            }

            oUltimoCampo = aItens[i];
        }


        if (aItens.length > 0) {
            js_getSaldos(oUltimoCampo.id.replace("ctapag",""));
        }

    }


    function js_setFormaPadrao(iForma) {


        var aItens = gridNotas.getElementsByClass('formapag');
        for (var i = 0; i < aItens.length; i++) {
            if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {
                aItens[i].value = $F('e96_codigo');
            }
        }
    }

    function js_reset() {

        $('descrConta').innerHTML      = '';
        $('saldotesouraria').value     = '';
        $('totalcheques').value        = '';
        $('saldoatual').value          = '';

    }

    function js_autenticar(oAutentica, lReautentica) {

        var sPalavra = 'Autenticar';
        if (lReautentica) {
            var sPalavra = "Autenticar novamente";
        }
        if (confirm(sPalavra+' a Nota '+oAutentica.iNota+'?')) {

            var oRequisicao      = new Object();
            oRequisicao.exec     = "Autenticar";
            oRequisicao.sString  = oAutentica.sAutentica;
            var sJson            = js_objectToJson(oRequisicao);
            var oAjax = new Ajax.Request(
                'emp4_pagarpagamentoRPC.php',
                {
                    method    : 'post',
                    parameters: 'json='+sJson,
                    onComplete: js_retornoAutenticacao
                }
            );

        } else {

            iIndice++;
            if (aAutenticacoes[iIndice]) {
                js_autenticar(aAutenticacoes[iIndice],false);
            } else {


                if ($('emitirordemauxiliar').checked || ($('opmanutencaoincluir').checked || $('opmanutencaoexcluir').checked)) {
                    js_emitirOrdemAuxiliar($F('e42_dtpagamento'), iCodigoOrdemAuxiliar);
                }

                $('opmanutencaonda').checked = true;
                js_pesquisarOrdens();

            }
        }
    }

    function js_showAutenticar(obj) {
        if (obj.checked) {

            $('showautenticar').style.visibility = 'visible';
            $('autenticar').checked               = false;
            $('showreemissao').style.visibility = 'visible';

        } else {

            $('showautenticar').style.visibility = 'hidden';
            $('showreemissao').style.visibility  = 'hidden';
            $('autenticar').checked              = false;
            $('reemisaoop').checked              = false;

        }
    }


    function js_reemissaoOP(oObjeto) {

        if (oObjeto.checked) {
            $('autenticar').checked = false;
            $('autenticar').setAttribute("disabled", "disabled");
        } else {
            $('autenticar').removeAttribute("disabled");
        }
    }


    function js_retornoAutenticacao(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");
        if (oRetorno.status == 1) {

            js_autenticar(aAutenticacoes[iIndice], true);

        } else {

            if ($('emitirordemauxiliar').checked || ($('opmanutencaoincluir').checked || $('opmanutencaoexcluir').checked)) {
                js_emitirOrdemAuxiliar($F('e42_dtpagamento'), iCodigoOrdemAuxiliar);
            }
            $('opmanutencaonda').checked = true;
            js_pesquisarOrdens();

        }
    }

    function js_emitirOrdemAuxiliar(dtAutoriza, iOrdemAuxiliar) {

        window.open('emp2_ordempagamentoauxiliar002.php?dtAutorizacao=&iAgenda='+iOrdemAuxiliar,'','location=0');
    }

    $('esconderfiltros').onclick = function () {

        var aFiltros = gridNotas.getElementsByClass('filtros');
        aFiltros.each(function (oNode, id) {

            if (oNode.style.display == '') {

                oNode.style.display = 'none';
                $('togglefiltros').src='imagens/seta.gif';

            } else if (oNode.style.display == 'none') {

                oNode.style.display = '';
                $('togglefiltros').src='imagens/setabaixo.gif'

            }
        });
    }
    $('esconderTotais').onclick = function () {

        var aFiltros = gridNotas.getElementsByClass('tabelatotais');
        aFiltros.each(function (oNode, id) {

            if (oNode.style.display == '') {

                oNode.style.display = 'none';
                $('toggletotais').src='imagens/seta.gif';

            } else if (oNode.style.display == 'none') {

                oNode.style.display = '';
                $('toggletotais').src='imagens/setabaixo.gif'

            }
        });
    }

    /**
     * Agrupa as os movimentos selecionados
     */
    function js_agruparMovimentos() {

        /**
         * - O movimento nao pode estar configurado.
         * - N�o pode haver retencoes lan�adas para o movimento
         */
        var oParam                = new Object();
        oParam.exec               = "agruparMovimentos";
        oParam.aMovimentosAgrupar =  new Array();

        var aMovimentos           = gridNotas.getSelection("object");
        var iOPAnterior = 0;
        for (var i = 0; i < aMovimentos.length; i++) {

            var oMovimento      = new Object();
            var iMovimento      = aMovimentos[i].aCells[1].getValue();
            var iOP             = aMovimentos[i].aCells[5].getValue();
            var nValor          = aMovimentos[i].aCells[14].getValue();
            var sConCarPeculiar = aMovimentos[i].aCells[4].getValue();
            var nValorRetencao = js_strToFloat(aMovimentos[i].aCells[13].getValue()).valueOf();
            if (i > 0 && iOPAnterior !=  iOP ) {

                alert('Foram Selecionados Movimentos de OP diferentes!\nProcedimento Cancelado');
                return false;

            }
            if (aMovimentos[i].getClassName() != "normal") {

                alert('Movimento '+iMovimento+' da OP '+iOP+' Est� Configurada.');
                return false;

            }

            if (nValorRetencao != 0) {

                alert('Movimento '+iMovimento+' da OP '+iOP+' possui reten��es lancadas.');
                return false;

            }
            iOPAnterior                = iOP;
            oMovimento.e81_codmov      = iMovimento;
            oMovimento.e82_codord      = iOP;
            oMovimento.nValor          = nValor;
            oMovimento.sConCarPeculiar = sConCarPeculiar
            oParam.aMovimentosAgrupar.push(oMovimento);
        }

        var iTotalString =new String(aMovimentos.length).extenso(false).ucFirst();
        if (!confirm('Confirma o agrupamento de '+iTotalString+' movimentos?')){
            return false;
        }
        js_divCarregando("Aguarde, Agrupando Movimentos.","msgBox");
        var oAjax = new Ajax.Request(
            'emp4_manutencaoPagamentoRPC.php',
            {
                method    : 'post',
                parameters: 'json='+Object.toJSON(oParam),
                onComplete: js_retornoAgruparMovimentos
            }
        );

    }

    function js_retornoAgruparMovimentos(oResponse) {

        js_removeObj("msgBox");
        var oRetorno = eval("("+oResponse.responseText+")");
        if (oRetorno.status == 1) {

            alert(oRetorno.totalagrupados.extenso(false).ucFirst()+' movimentos foram agrupados com sucesso.');
            js_pesquisarOrdens();

        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_visualizarRelatorio() {

        var oParam           = new Object();
        oParam.iOrdemIni     = $F('e82_codord');
        oParam.iOrdemFim     = $F('e82_codord02');
        oParam.iCodEmp       = $F('e60_codemp');
        oParam.dtDataIni     = $F('dataordeminicial');
        oParam.dtDataFim     = $F('dataordemfinal');
        oParam.iNumCgm       = $F('z01_numcgm');
        oParam.iRecurso      = $F('o15_codigo');
        oParam.sDtAut        = $F('e42_dtpagamento');
        oParam.iOPauxiliar   = $F('e42_sequencial');
        oParam.iAutorizadas  = $F('ordensautorizadas');
        oParam.lAtualizadas  = $('configuradas').checked;
        oParam.lNormais      = $('normais').checked;
        oParam.lChequeArq    = $('comMovs').checked;
        oParam.orderBy       = $F('orderby');
        sUrl = "emp2_manutencaopagamentos002.php?json="+Object.toJSON(oParam);
        window.open(sUrl, '', 'location=0');

    }
    $('agruparmovimentos').observe("click",js_agruparMovimentos);
    js_init();



    /**
     *  Abre lookup para pesquisar na tabela concarpeculiar
     */
    function js_lookupConCarPeculiar(iCodigoMovimento) {
        idLinhaSelecionada = $('ccp_'+iCodigoMovimento);
        js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_completaConCarPeculiar|c58_sequencial','Pesquisa',true);
    }
    /**
     *  Preenche a linha com o ID da concarpeculiar selecionada
     */
    function js_completaConCarPeculiar(s_c58_concarpeculiar) {
        idLinhaSelecionada.innerHTML = s_c58_concarpeculiar;
        db_iframe_concarpeculiar.hide();
    }

    function js_emiteOrdens(aOrdens, aMovimentos) {

        var sListaOrdem = '';
        var sVirgula    = '';
        aOrdens.each(function (oOrdem, iSeq) {

            sListaOrdem += sVirgula+""+oOrdem.iNota;
            sVirgula   = ",";
        });
        sVirgula        = '';
        sListaMovimento = '';

        aMovimentos.each(function (aMovimento, iSeq) {

            sListaMovimento += sVirgula+""+aMovimento;
            sVirgula         = ",";
        });
        window.open('emp2_emitenotaliqpormovimento002.php?e50_codord='+sListaOrdem+'&e81_codmov='+sListaMovimento,
            '',
            'location=0'
        );
    }

    function verificaCadastroAutenticadora() {

        new AjaxRequest(
            'cai4_autenticadora.RPC.php',
            {exec : 'possuiCadastro'},
            function (oRetorno, lErro) {

                if (!oRetorno.possuiCadastro) {
                    alert ("IP "+oRetorno.ip_usuario.urlDecode()+" n�o cadastrado como autenticadora.");
                }
            }
        ).setMessage('Aguarde, verificando cadastro de autenticadora...').execute();
    }

    verificaCadastroAutenticadora();
    $('col1').style.width = "10px";

    function pesquisaConta(conta,event) {
        var input, filter, ul, li, a, i;
        input = document.getElementById("ctapag"+conta);
        filter = input.value.toUpperCase();
        ul = document.getElementById("pesquisaConta"+conta);
        li = ul.getElementsByTagName("li");

        for (i = 0; i < li.length; i++) {
            descricao = li[i].getElementsByTagName("span")[0];
            if (descricao.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";

            }
        }

        if (event.keyCode == 9) {
            for (i = 0; i < li.length; i++) {
                if (li[i].style.display == "") {
                    selecionarConta(li[i],conta);
                    break;
                }
            }
        }

    }

    function mostrarPesquisa(conta) {
        document.getElementById("pesquisaConta"+conta).style.display = "block";
    }

    function fecharPesquisa(conta) {
        setTimeout(function(){
            document.getElementById("pesquisaConta"+conta).style.display = "none";
        }, 100);
    }

    function selecionarConta(elemento,conta) {
        document.getElementById("tipoconta"+conta).value = elemento.getElementsByTagName("div")[0].textContent;
        document.getElementById("ctapag"+conta).value = elemento.getElementsByTagName("span")[0].textContent;
    }
</script>
