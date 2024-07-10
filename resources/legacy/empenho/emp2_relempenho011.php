<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$clselorcdotacao = new cl_selorcdotacao;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if (!isset($testdt)){
    $testdt='sem';
}

if (!isset($desdobramento)){
    $desdobramento = "true";
}

$anousu = db_getsession("DB_anousu");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        select {
            width: 250px;
        }
    </style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC">
<br/>
<center>
    <form name="form1" method="post" action="emp2_relempenho002.php">
        <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
        <fieldset style="width: 800px">
            <legend><b>Movimenta��o de Empenho</b></legend>
            <?php
            /*
             * Campos HIDDEN
             */
            db_input('testdt',10,"",true,"hidden",1);
            db_input('listacredor',10,"",true,"hidden",1);
            db_input('listagestor',10,"",true,"hidden",1);
            db_input('listahist',10,"",true,"hidden",1);
            db_input('listatipoanulacao',10,"",true,"hidden",1);
            db_input('listaevento',10,"",true,"hidden",1);
            db_input('listaeventodesc',10,"",true,"hidden",1);
            db_input('listaitem',10,"",true,"hidden",1);
            db_input('ver',10,"",true,"hidden",1);
            db_input('verhist',10,"",true,"hidden",1);
            db_input('veritem',10,"",true,"hidden",1);
            db_input('vercom',10,"",true,"hidden",1);
            db_input('listacom',10,"",true,"hidden",1);
            db_input('listalicita',10,"",true,"hidden",1);
            db_input('listaacordo',10,"",true,"hidden",1);
            db_input('datacredor',10,"",true,"hidden",1);
            db_input('datacredor1',10,"",true,"hidden",1);
            db_input('dataesp11',10,"",true,"hidden",1);
            db_input('dataesp22',10,"",true,"hidden",1);
            db_input('hist',10,"",true,"hidden",1);
            db_input('mostraritem',10,"",true,"hidden",1);
            db_input('mostrarobs',10,"",true,"hidden",1);
            db_input('mostralan',10,"",true,"hidden",1);
            db_input('agrupar',10,"",true,"hidden",1);
            db_input('listasub',10,"",true,"hidden",1);
            db_input("desdobramento",10,0,true,"hidden",3);
            db_input("listaconvconvenios",10,0,true,"hidden",3);
            db_input("verconvenio",  10,0,true,"hidden",3);
            db_input("orgaos",  10,0,true,"hidden",3);
            db_input("vernivel",  10,0,true,"hidden",3);
            ?>
            <table style="width: 100%" border='0'>
                <tr>
                    <td colspan="4" align="center">
                        <fieldset style="width: 98%; border-left: none; border-bottom: none; border-right: none;">
                            <legend><b>Institui��es</b></legend>
                            <?php
                            db_selinstit('', 500, 130);
                            ?>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td width="140">
                        <b>Data de Emiss�o:</b>
                    </td>
                    <td colspan="3">
                        <?php
                        $resultmin = pg_exec("select e60_emiss from empempenho order by e60_emiss limit 1");
                        db_fieldsmemory($resultmin,0);
                        $dia=substr($e60_emiss,8,2);
                        $dia="01";
                        $mes=substr($e60_emiss,5,2);
                        $mes="01";
                        $ano=substr($e60_emiss,0,4);
                        $ano= db_getsession("DB_anousu");
                        $dia2=date("d",db_getsession("DB_datausu"));
                        $mes2=date("m",db_getsession("DB_datausu"));
                        $ano2= db_getsession("DB_anousu");
                        db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
                        echo " a ";
                        db_inputdata('data11',@$dia2,@$mes2,@$ano2,true,'text',1,"");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Processar:</b></td>
                    <td colspan="3">
                        <?php
                        $aProcessar = array("a"=>"Posi��o atual","e"=>"Per�odo de Lan�amentos");
                        db_select('processar', $aProcessar, true, 1, "onchange='js_testadata(this.value);'");
                        ?>
                        <span id="dataespec">
            &nbsp;&nbsp;
            <?php
            $diaesp1 = "01";
            $mesesp1 = "01";
            $anoesp1 = db_getsession("DB_anousu");
            db_inputdata('dataesp1',@$diaesp1,@$mesesp1,@$anoesp1,true,'text',1,"");
            echo " a ";
            $diaesp2 = date('d',db_getsession("DB_datausu"));
            $mesesp2 = date('m',db_getsession("DB_datausu"));
            $anoesp2 = db_getsession("DB_anousu");
            db_inputdata('dataesp2',@$diaesp2,@$mesesp2,@$anoesp2,true,'text',1,"");
            ?>
          </span>
                    </td>
                </tr>
                <tr>
                    <td><b>Filtro de Listagem:</b></td>
                    <td colspan="3">
                        <?php
                        $aFiltroListagem = array("todos"       => "Todos",
                            "somemp"      => "Somente Empenhado",
                            "saldo"       => "Com Saldo a Pagar Geral",
                            "saldoliq"    => "Com Saldo a Pagar Liquidados",
                            "saldonaoliq" => "Com Saldo a Pagar n�o Liquidados",
                            "anul"        => "Com Anula��o Lan�ada",
                            "anultot"     => "Totalmente Anulados",
                            "anulparc"    => "Parcialmente Anulados",
                            "anulsem"     => "Sem Anula��o",
                            "liqtot"      => "Totalmente Liquidados",
                            "liqparc"     => "Parcialmente Liquidados",
                            "liqparc"     => "Sem Liquida��o",
                            "pagtot"      => "Totalmente Pagos",
                            "pagparc"     => "Parcialmente Pagos");
                        db_select("tipoemp", $aFiltroListagem, true, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Agrupar Por:</b></td>
                    <td>
                        <?php
                        $aAgruparPor = array("oo"    => "N�o Agrupar",
                            "c"     => "Contrato",
                            "a"     => "Fornecedor",
                            "orgao" => "Org�o",
                            "r"     => "Recurso",
                            "gest"  => "Gestor",
                            "d"     => "Desdobramento",
                            "ta"    => "Tipo de Anula��o",
                            "do"    => "Dota��o Or�ament�ria");
                        db_select("agrupar", $aAgruparPor, true, 1);
                        ?>
                    </td>
                    <td><b>Mostrar:</b></td>
                    <td>
                        <?php
                        $aMostrar = array("r" => "Recurso",
                            "t" => "Tipo de Compra");
                        db_select("mostrar", $aMostrar, true, 1);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Trazer Valor Em Ordem:</b></td>
                    <td>
                        <?php
                        $aValorEmOrdem = array("0" => "Selecione", "E" => "Empenhado", "L" => "Liquidado", "P" => "Pago");
                        db_select("chk_ordem",$aValorEmOrdem,true,2);
                        ?>
                    </td>
                    <td><b>Mostrar Empenho:</b></td>
                    <td>
                        <?php
                        $aMostrarEmpenho = array("n" => "Sim", "s" => "N�o");
                        db_select("sememp",$aMostrarEmpenho,true,2);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Valor Empenho:</b></td>
                    <td >
                        <?php
                        db_input("nValorEmpenhoInicial", 10, false, true, 'text', 1, "onkeypress='return js_mask(event,\"0-9|,|-\");'");
                        echo " <b>at�</b> ";
                        db_input("nValorEmpenhoFinal", 10, false,   true, 'text', 1, "onkeypress='return js_mask(event,\"0-9|,|-\");'");
                        ?>
                    </td>
                    <td><b>Saldo a Pagar:</b></td>
                    <td>
                        <?php
                        $aSaldoPagar = array("s" => "Sim", "n" => "N�o");
                        db_select("saldopagar",$aSaldoPagar,true,2);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Tipo Empenho:</b></td>
                    <td>
                        <?php
                        $aTipoEmpenho = array("0"=>"TODOS", "1" => "ORDINARIO", "2" => "GLOBAL", "3" => "ESTIMATIVA");
                        db_select("emptipo",$aTipoEmpenho,true,1);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Op��es:</b></td>
                    <td colspan="3">
                        <input type="checkbox" name="hist"   	    value="h">Mostrar totaliza��es
                        <input type="checkbox" name="mostraritem" value="m">Mostrar itens
                        <input type="checkbox" name="mostrarobs" 	value="m">Mostrar resumo
                        <input type="checkbox" name="mostralan" 	value="m">Mostrar lan�amentos
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="hidden" name="banco"	value="<?php echo db_getsession("DB_NBASE")?>">
        <br>
        <input type="button" value="Relat�rio" onClick="js_emite(<?=$anousu?>,0)">
        <input type="button" value="Relat�rio .csv" onClick="js_emite(<?=$anousu?>,1)">
</center>
</form>
<script>

    var agrupaSelect = document.querySelector('select#agrupar');
    agrupaSelect.addEventListener('change', function(e) {
        if (this.value === 'gest') {
            parent.iframe_g2.document.getElementById('filtro_gestores').style.display = 'block';
        } else {
            parent.iframe_g2.document.getElementById('filtro_gestores').style.display = 'none';
            parent.iframe_g2.document.form1.e54_gestaut.innerHTML = '';
        }
    });


    tr = document.getElementById("dataespec");
    tr.style.display = "none";
    document.form1.dataesp1_dia.style.visibility="hidden";
    document.form1.dataesp1_mes.style.visibility="hidden";
    document.form1.dataesp1_ano.style.visibility="hidden";
    document.form1.dataesp1.style.visibility="hidden";
    document.form1.dataesp2_dia.style.visibility="hidden";
    document.form1.dataesp2_mes.style.visibility="hidden";
    document.form1.dataesp2_ano.style.visibility="hidden";
    document.form1.dataesp2.style.visibility="hidden";


    function js_testadata(valor){
        tr = document.getElementById("dataespec");
        if (valor=='e'){
            tr.style.display = "";
            document.form1.dataesp1_dia.style.visibility="visible";
            document.form1.dataesp1_mes.style.visibility="visible";
            document.form1.dataesp1_ano.style.visibility="visible";
            document.form1.dataesp1.style.visibility="visible";
            document.form1.dataesp2_dia.style.visibility="visible";
            document.form1.dataesp2_mes.style.visibility="visible";
            document.form1.dataesp2_ano.style.visibility="visible";
            document.form1.dataesp2.style.visibility="visible";
        }else if (valor=='a'){
            tr.style.display = "none";
            document.form1.dataesp1_dia.style.visibility="hidden";
            document.form1.dataesp1_mes.style.visibility="hidden";
            document.form1.dataesp1_ano.style.visibility="hidden";
            document.form1.dataesp1.style.visibility="hidden";
            document.form1.dataesp2_dia.style.visibility="hidden";
            document.form1.dataesp2_mes.style.visibility="hidden";
            document.form1.dataesp2_ano.style.visibility="hidden";
            document.form1.dataesp2.style.visibility="hidden";
        }
    }

    variavel = 1;

    function js_emite(anousu,tipo){


        vir="";
        listacredor="";
        for(x=0;x<parent.iframe_g2.document.form1.credor.length;x++){
            listacredor+=vir+parent.iframe_g2.document.form1.credor.options[x].value;
            vir=",";
        }

        // gera um array com os valores dos <option>s e depois join()
        var listaGestores = parent.iframe_g2.document.form1.e54_gestaut.options;
        listaGestores = Array.prototype.map.call(listaGestores, function(o) {
            return o.value;
        }).join(',');

        vir="";
        listahist="";
        for(x=0;x<parent.iframe_g3.document.form1.historico.length;x++){
            listahist+=vir+parent.iframe_g3.document.form1.historico.options[x].value;
            vir=",";
        }

        vir="";
        listaevento="";
        listaeventodesc="";
        for(x=0;x<parent.iframe_g3.document.form1.evento.length;x++){
            listaevento+=vir+parent.iframe_g3.document.form1.evento.options[x].value;
            listaeventodesc+=vir+parent.iframe_g3.document.form1.evento.options[x].innerText;
            vir=",";
        }

        vir="";
        listatipoanulacao="";
        for(x=0;x<parent.iframe_g3.document.form1.tipoanulacao.length;x++){
            listatipoanulacao+=vir+parent.iframe_g3.document.form1.tipoanulacao.options[x].value;
            vir=",";
        }

        vir="";
        listacom="";
        for(x=0;x< parent.iframe_g4.document.form1.tipocom.length;x++){
            listacom+=vir+ parent.iframe_g4.document.form1.tipocom.options[x].value;
            vir=",";
        }

        vir="";
        listalicita="";
        for(x=0;x< parent.iframe_g4.document.form1.liclicita.length;x++){
            listalicita+=vir+ parent.iframe_g4.document.form1.liclicita.options[x].value;
            vir=",";
        }

        vir="";
        listaacordo="";
        for(x=0;x< parent.iframe_g4.document.form1.acordo.length;x++){
          listaacordo+=vir+ parent.iframe_g4.document.form1.acordo.options[x].value;
          vir=",";
        }

        vir="";
        listaitem="";
        listasub ="";
        for(x=0;x<parent.iframe_g5.document.form1.item.length;x++){
            listaitem+=vir+parent.iframe_g5.document.form1.item.options[x].value;
            vir=",";
        }

        if (listaitem==""){
            for(x=0;x<parent.iframe_g5.document.form1.sub.length;x++){
                listasub+=vir+parent.iframe_g5.document.form1.sub.options[x].value;
                vir=",";
            }
        }

        if (anousu > 2007){
            vir="";
            listaconvconvenios="";
            for(x=0;x< parent.iframe_g6.document.form1.convconvenios.length;x++){
                listaconvconvenios+=vir+ parent.iframe_g6.document.form1.convconvenios.options[x].value;
                vir=",";
            }

            document.form1.listaconvconvenios.value = listaconvconvenios;
            document.form1.verconvenio.value   = parent.iframe_g6.document.form1.verconvenio.value;
        }

        document.form1.listacom.value = listacom;
        document.form1.listalicita.value = listalicita;
        document.form1.listaacordo.value = listaacordo;

        var sPeriodoInicial = document.form1.data1.value.split("/");
        sPeriodoInicial     = sPeriodoInicial[2]+'-'+sPeriodoInicial[1]+'-'+sPeriodoInicial[0];
        var sPeriodoFinal   = document.form1.data11.value.split("/");
        sPeriodoFinal       = sPeriodoFinal[2]+'-'+sPeriodoFinal[1]+'-'+sPeriodoFinal[0];     

        document.form1.datacredor.value=sPeriodoInicial;
        document.form1.datacredor1.value=sPeriodoFinal;

        document.form1.dataesp11.value=document.form1.dataesp1_ano.value+'-'+document.form1.dataesp1_mes.value+'-'+document.form1.dataesp1_dia.value;
        document.form1.dataesp22.value=document.form1.dataesp2_ano.value+'-'+document.form1.dataesp2_mes.value+'-'+document.form1.dataesp2_dia.value;

        document.form1.hist=document.form1.hist.checked;

        document.form1.listacredor.value = listacredor;
        document.form1.listagestor.value = listaGestores;
        document.form1.listahist.value   = listahist;
        document.form1.listaevento.value = listaevento;
        document.form1.listaeventodesc.value = listaeventodesc;
        document.form1.listatipoanulacao.value = listatipoanulacao;

        document.form1.listaitem.value=listaitem;
        document.form1.listasub.value=listasub;

        document.form1.ver.value         = parent.iframe_g2.document.form1.ver.value;
        document.form1.verhist.value     = parent.iframe_g3.document.form1.ver.value;
        document.form1.vercom.value      = parent.iframe_g4.document.form1.ver.value;
        document.form1.veritem.value     = parent.iframe_g5.document.form1.veritem.value;

        document.form1.mostraritem=document.form1.mostraritem.checked;
        document.form1.mostrarobs=document.form1.mostrarobs.checked;
        document.form1.mostralan=document.form1.mostralan.checked;
        document.form1.agrupar=document.form1.agrupar.value;

        // pega dados da func_selorcdotacao_aba.php
        document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();

        // procura pela string orgao, se nao localizar, eh porque nao foi selecionado orgao na aba de filtro
        /**
         * Removido validacao para nao obrigar o usuario acessar a aba Filtro
         */
        /* if (document.form1.filtra_despesa.value.search('orgao') !=-1) {
            // alert('achamos');
         } else {
            alert('Na aba de Filtro selecione ao menos um Org�o para processar a pesquisa');
            return false;
         }*/

        if(tipo == 1){
            document.form1.action = 'emp2_relempenho003.php';
        }else{
            jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            document.form1.target = 'safo' + variavel++;
            document.form1.action = 'emp2_relempenho002.php';
        }

        setTimeout("document.form1.submit()",1000);
        return true;
    }


    function js_mandadados(){

        tipoemp=document.form1.tipoemp.value;
        <?
        if ($testdt=='com'){
        ?>
        var sPeriodoInicial = document.form1.data1.value.split("/");
        sPeriodoInicial     = sPeriodoInicial[2]+'-'+sPeriodoInicial[1]+'-'+sPeriodoInicial[0];
        var sPeriodoFinal   = document.form1.data11.value.split("/");
        sPeriodoFinal       = sPeriodoFinal[2]+'-'+sPeriodoFinal[1]+'-'+sPeriodoFinal[0];      
        jan = window.open('emp2_relempenho002.php?tipoemp='+tipoemp
            +'&listacredor='+listacredor
            +'&listagestor='+listagestor
            +'&emptipo='+emptipo
            +'&listahist='+listahist
            +'&listaevento='+listaevento
            +'&listaeventodesc='+listaeventodesc
            +'&listatipoanulacao='+listatipoanulacao
            +'&listacom='+listacom
            +'&listalicita='+listalicita
            +'&listaacordo='+listaacordo
            +'&datacredor='+sPeriodoInicial
            +'&datacredor1='+sPeriodoFinal
            +'&dataesp1='+document.form1.dataesp1_ano.value+'-'+document.form1.dataesp1_mes.value+'-'+document.form1.dataesp1_dia.value
            +'&dataesp2='+document.form1.dataesp2_ano.value+'-'+document.form1.dataesp2_mes.value+'-'+document.form1.dataesp2_dia.value
            +'&processar='+document.form1.processar.value
            +'&vercredor='+parent.iframe_g2.document.form1.ver.value
            +'&verhist='+parent.iframe_g3.document.form1.ver.value
            +'&vercom='+parent.iframe_g4.document.form1.ver.value
            +'&mostrar='+document.form1.mostrar.value
            +'&tipo='+document.form1.tipo.value
            +'&hist='+document.form1.hist.checked
            +'&banco='+document.form1.banco.value
            +'&mostraritem='+document.form1.mostraritem.checked,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        <?
        }else{
        ?>
         var sPeriodoInicial = document.form1.data1.value.split("/");
         sPeriodoInicial     = sPeriodoInicial[2]+'-'+sPeriodoInicial[1]+'-'+sPeriodoInicial[0];
         var sPeriodoFinal   = document.form1.data11.value.split("/");
         sPeriodoFinal       = sPeriodoFinal[2]+'-'+sPeriodoFinal[1]+'-'+sPeriodoFinal[0];    
        jan = window.open('emp2_relempenho002.php?tipoemp='+tipoemp
            +'&listacredor='+listacredor
            +'&listagestor='+listagestor
            +'&emptipo='+emptipo
            +'&listahist='+listahist
            +'&listaevento='+listaevento
            +'&listaeventodesc='+listaeventodesc
            +'&listatipoanulacao='+listatipoanulacao
            +'&listacom='+listacom
            +'&listalicita='+listalicita
            +'&listaacordo='+listaacordo
            +'&datacredor='+sPeriodoInicial
            +'&datacredor1='+sPeriodoInicial
            +'&processar='+document.form1.processar.value
            +'&vercredor='+parent.iframe_g2.document.form1.ver.value
            +'&verhist='+parent.iframe_g3.document.form1.ver.value
            +'&vercom='+parent.iframe_g4.document.form1.ver.value
            +'&mostrar='+document.form1.mostrar.value
            +'&tipo='+document.form1.tipo.value
            +'&hist='+document.form1.hist.checked
            +'&banco='+document.form1.banco.value
            +'&mostraritem='+document.form1.mostraritem.checked,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        <?
        }
        ?>
        jan.moveTo(0,0);

    }
</script>

</center>
</body>
</html>
