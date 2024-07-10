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
?>
<script>
  // --------------------
  function valida_dados() {

    if (document.getElementById('o47_coddot').value == '') {

      alert('Campo Reduzido da dota��o � obrigat�rio!');
      return false;
    }
    var tiposuplem = "<?php echo $tiposup ?>"; 
    <?php if (db_getsession("DB_anousu")>2021){ ?>
    
    if( tiposuplem == 1002 || tiposuplem == 1007) {
      if (document.getElementById('o47_codigoopcredito').value == '') {
          alert('Campo Opera��o de Cr�dito n�o Informado!');
          return false;
      }
    }
    <?php } ?>  

  


    if (document.getElementById('o58_concarpeculiar').value == '') {

      alert('Voc� deve selecionar uma C.Peculiar/Cod de Aplica��o antes de incluir a Suplementa��o!');
      return false;
    }

    if (document.getElementById('o50_motivosuplementacao').value == 't') {
      if (document.getElementById('o47_motivo').value == '') {

        alert('Campo motivo � obrigat�rio!');
        return false;
      }
    }

    var op = document.createElement("input");
    op.setAttribute("type", "hidden");
    op.setAttribute("name", "<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>");
    op.setAttribute("value", "");
    document.form1.appendChild(op);
    document.form1.submit();

  }

  // --------------------
</script>
<form name="form1" method="post" action="">
  <center>
    <table border=0>
      <tr>
        <td valign=top>
          <fieldset>
            <legend><b>Dota��o</b></legend>
            <table>
              <tr>
                <td nowrap title="<?= @$To47_coddot ?>"> <? db_ancora(@$Lo47_coddot, "js_pesquisao47_coddot(true);", $op); ?> </td>
                <td><? db_input('o47_coddot', 10, $Io47_coddot, true, 'text', $op, "onchange='js_pesquisao47_coddot(false);'"); ?> </td>
                <td><input type="submit" name="pesquisa_dot" value="pesquisar"> </td>
              </tr>
              <tr>
                <?
                if ($oDadosProjeto->o138_sequencial != "") {
                ?>
              <tr>
                <td nowrap><? db_ancora("<b>Proje��o Despesa</b>", "js_pesquisa_estimativa(true);", $op); ?> </td>
                <td><? db_input('o07_sequencial', 8, $Io47_coddot, true, 'text', 3, "onchange='js_pesquisa_estimativa(false);'"); ?> </td>
              </tr>
            <?
                }
            ?>
            <tr>
              <td nowrap title="<?= @$To58_orgao ?>">Org�o : </td>
              <td><? db_input('o58_orgao', 10, "$Io58_orgao", true, 'text', 3, "");  ?> </td>
              <td colspan=3><? db_input('o40_descr', 40, "", true, 'text', 3, "");  ?> </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$To58_elemento ?>">Elemento : </td>
              <td> <? db_input('o56_elemento', 10, "", true, 'text', 3, "");  ?> </td>
              <td> <? db_input('o56_descr', 40, "", true, 'text', 3, "");  ?> </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$To58_codigo ?>">Recurso : </td>
              <td> <? db_input('o58_codigo', 10, "", true, 'text', 3, "");  ?> </td>
              <td> <? db_input('o15_descr', 40, "", true, 'text', 3, "");  ?> </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$To58_concarpeculiar ?>">
                <?
                db_ancora(@$Lo58_concarpeculiar, "js_pesquisao58_concarpeculiar(true);", $db_opcao, "", "o58_concarpeculiarancora");
                ?>
              </td>
              <td><? db_input('o58_concarpeculiar', 10, @$Io58_concarpeculiar, true, 'text', $db_opcao, "onchange='js_pesquisao58_concarpeculiar(false);'");  ?></td>
              <td><? db_input('c58_descr', 40, @$Ic58_descr, true, 'text', 3, ''); ?></td>
            </tr>
            <!-- oc16314 -->
            <?php if (db_getsession("DB_anousu")>2021){ ?>
            <?php if (($tiposup == 1002 || $tiposup == 1007)){ ?>
            <tr>
              <td nowrap title="<?= substr(@$Top01_numerocontratoopc, 18, 50) ?>">
                <?= db_ancora(substr(@$Lop01_numerocontratoopc, 26, 50), "js_pesquisaop01_db_operacaodecredito(true);", $db_opcao); ?>
              </td>
              <td nowrap title="<?= @$To47_dataassinaturacop ?>">
              <? db_input('o47_codigoopcredito', 10, $o47_codigoopcredito, true, '', 1, " onchange='js_pesquisaop01_db_operacaodecredito(false);'");?></td>
              <td><? 
                  db_input('o47_numerocontratooc', 40, $Io47_numerocontratooc, true, '', 3);
                  $data = explode("-", $o47_dataassinaturacop);
                  $o47_dataassinaturacop_dias = $data[2];
                  $o47_dataassinaturacop_mes = $data[1];
                  $o47_dataassinaturacop_ano = $data[0];
                  db_input('o47_dataassinaturacop', 8, $Io47_dataassinaturacop, true, 'hidden', 3);
                  ?>
              </td>
				    </tr>
            <?php } ?>
            <?php } ?>
            
            <!-- oc16314 -->
            <tr>
              <td>Saldo: </td>
              <td><? db_input('atual_menos_reservado', 10, '', true, 'text', 3, '', '', '', 'text-align:right');  ?> </td>
            </tr>
            <tr>
              <td><b>Valor a Suplementar:</b></td>
              <td><? db_input('o47_valor', 10, $Io47_valor, true, 'text', 1, '', '', '', 'text-align:right');  ?> </td>
            </tr>
            <?php
            //OC2785
            $motivo  = db_query("
                    select o50_motivosuplementacao from orcparametro where o50_anousu = " . db_getsession("DB_anousu"));
            $aMotivo = db_utils::getCollectionByRecord($motivo);
            ?>
            <?php if ($aMotivo[0]->o50_motivosuplementacao == 't') : ?>
              <tr>
                <td><b>Motivo:</b></td>
                <td colspan="2"><? db_input('o47_motivo', 52, "", true, 'text', 1, '', '', '', '');  ?> </td>
                <input type="hidden" id="o50_motivosuplementacao" value="<?php echo $aMotivo[0]->o50_motivosuplementacao ?>">
              </tr>
            <?php else : ?>
              <input type="hidden" id="o50_motivosuplementacao" value="">
            <?php endif; ?>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2" align="center">
                <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="button" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>" onclick='valida_dados();'" >
    </td>
  </tr>
 </table>
 </legend>
</td>

<!-- segunda coluna -->
<td valign=top>

 <fieldset><legend><b>Projeto</b></legend>
 <table width=200px>
   <tr><td><b>Projeto</b></td><td><? db_input("o39_codproj", 6, '', true, 'text', 3); ?></td></tr>
   <tr><td><b>Suplementa��o</b> </td><td><? db_input("o46_codsup", 6, '', true, 'text', 3); ?></td></tr>
 </table>
 </fieldset>

 <fieldset><legend><b>Saldos</b></legend>
 <table width=200px>
    <tr><td><b>Total Suplementado</b></td><td><? db_input("soma_suplem", 10, '', true, 'text', 3, '', '', '', 'text-align:right'); ?></td></tr>
 </table>
 </fieldset>

</td>
</tr>
</table>

<center>
<?  ///////// total reduzido

/*OC2785*/
$motivo  = db_query("
                    select o50_motivosuplementacao from orcparametro where o50_anousu = " . db_getsession("DB_anousu"));
$aMotivo = db_utils::getCollectionByRecord($motivo);
$o47_motivo = "";
$o47_motivo_union = "";
if ($aMotivo[0]->o50_motivosuplementacao == 't') {
  $o47_motivo = ",o47_motivo";
  $o47_motivo_union = ", 'A' as o47_motivo";
}

if (db_getsession("DB_anousu")>2021){
  if (($tiposup == 1002 || $tiposup == 1007)){
          $sSqlTotalSuplementacoes = $clorcsuplemval->sql_query_file(
            "",
            "",
            "",
            "fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                                                        1 as tipo,o47_anousu,o47_coddot,o47_valor{$o47_motivo},o47_numerocontratooc",
            "",
            "o47_codsup=$o46_codsup
                                                        and o47_valor >= 0"
          );
          $sSqlTotalSuplementacoes .= " union all ";
          $sSqlTotalSuplementacoes .= " select  fc_estruturaldotacaoppa(o08_ano,o08_sequencial) as o50_estrutdespesa , ";
          $sSqlTotalSuplementacoes .= "         2 as tipo,o08_ano, o136_sequencial, o136_valor{$o47_motivo_union},o47_numerocontratooc";
          $sSqlTotalSuplementacoes .= "  from orcsuplemdespesappa ";
          $sSqlTotalSuplementacoes .= "       inner join ppaestimativadespesa on o07_sequencial = o136_ppaestimativadespesa";
          $sSqlTotalSuplementacoes .= "       inner join ppadotacao           on o07_coddot     = o08_sequencial";
          $sSqlTotalSuplementacoes .= "       inner join orcsuplemval         on o47_codsup = o136_orcsuplem";	 
          $sSqlTotalSuplementacoes .= " where o136_orcsuplem={$o46_codsup}";


            //   "select
            //   distinct fc_estruturaldotacao(o47_anousu,
            //   o47_coddot) as o50_estrutdespesa,
            //   1 as tipo,
            //   o47_anousu,
            //   o47_coddot,
            //   o47_valor,
            //   fc_estruturaldotacaoppa(o08_ano,
            //   o08_sequencial) as o50_estrutdespesa ,
            //   2 as tipo,
            //   o08_ano,
            //   o136_sequencial,
            //   o136_valor,
            //   o47_numerocontratooc
            // from
            //   orcsuplemval
            // inner join ppaestimativadespesa on
            //   o07_coddot = o47_coddot
            // left join orcsuplemdespesappa on
            //   o136_ppaestimativadespesa = o07_sequencial
            // inner join ppadotacao on
            //   o07_coddot = o08_sequencial
            // where
            //   o136_orcsuplem = $o46_codsup or
            //   o47_codsup =  $o46_codsup
            //   and o47_valor >= 0
            // order by o47_anousu  "
              ;
  } else{
      $sSqlTotalSuplementacoes = $clorcsuplemval->sql_query_file(
                "",
                "",
                "",
                "fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                                                            1 as tipo,o47_anousu,o47_coddot,o47_valor{$o47_motivo}",
                "",
                "o47_codsup=$o46_codsup
                                                            and o47_valor >= 0"
              );
              $sSqlTotalSuplementacoes .= " union all ";
              $sSqlTotalSuplementacoes .= " select  fc_estruturaldotacaoppa(o08_ano,o08_sequencial) as o50_estrutdespesa , ";
              $sSqlTotalSuplementacoes .= "         2 as tipo,o08_ano, o136_sequencial, o136_valor{$o47_motivo_union}";
              $sSqlTotalSuplementacoes .= "  from orcsuplemdespesappa ";
              $sSqlTotalSuplementacoes .= "       inner join ppaestimativadespesa on o07_sequencial = o136_ppaestimativadespesa";
              $sSqlTotalSuplementacoes .= "       inner join ppadotacao           on o07_coddot     = o08_sequencial";
              $sSqlTotalSuplementacoes .= " where o136_orcsuplem={$o46_codsup}";
} 
}else{
    $sSqlTotalSuplementacoes = $clorcsuplemval->sql_query_file(
              "",
              "",
              "",
              "fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,
                                                          1 as tipo,o47_anousu,o47_coddot,o47_valor{$o47_motivo}",
              "",
              "o47_codsup=$o46_codsup
                                                          and o47_valor >= 0"
            );
            $sSqlTotalSuplementacoes .= " union all ";
            $sSqlTotalSuplementacoes .= " select  fc_estruturaldotacaoppa(o08_ano,o08_sequencial) as o50_estrutdespesa , ";
            $sSqlTotalSuplementacoes .= "         2 as tipo,o08_ano, o136_sequencial, o136_valor{$o47_motivo_union}";
            $sSqlTotalSuplementacoes .= "  from orcsuplemdespesappa ";
            $sSqlTotalSuplementacoes .= "       inner join ppaestimativadespesa on o07_sequencial = o136_ppaestimativadespesa";
            $sSqlTotalSuplementacoes .= "       inner join ppadotacao           on o07_coddot     = o08_sequencial";
            $sSqlTotalSuplementacoes .= " where o136_orcsuplem={$o46_codsup}";
}

// echo $sSqlTotalSuplementacoes;
$clorcsuplemval = new cl_orcsuplemval;

$chavepri = "";
if ($o47_motivo != "") {
  $chavepri = array("o47_anousu" => $anousu, "o47_coddot" => @$o47_coddot, "tipo" => @$tipo, "o47_motivo" => @$o47_motivo);
} else {
  $chavepri = array("o47_anousu" => $anousu, "o47_coddot" => @$o47_coddot, "tipo" => @$tipo);
}
//print_r($sSqlTotalSuplementacoes);die;
$cliframe_alterar_excluir->chavepri = $chavepri;
$cliframe_alterar_excluir->sql   =  $sSqlTotalSuplementacoes;

if (db_getsession("DB_anousu")>2021){
if (($tiposup == 1002 || $tiposup == 1007)){
  $cliframe_alterar_excluir->campos  = "o47_anousu,o50_estrutdespesa,o47_coddot,o47_valor,tipo{$o47_motivo},o47_numerocontratooc";
}else{
  $cliframe_alterar_excluir->campos  = "o47_anousu,o50_estrutdespesa,o47_coddot,o47_valor,tipo{$o47_motivo}";  
}
}else{
  $cliframe_alterar_excluir->campos  = "o47_anousu,o50_estrutdespesa,o47_coddot,o47_valor,tipo{$o47_motivo}";  
}
$cliframe_alterar_excluir->legenda = "Lista";
$cliframe_alterar_excluir->iframe_height = "200"; 
$cliframe_alterar_excluir->opcoes = 3;
$cliframe_alterar_excluir->iframe_alterar_excluir(1);

?>
</form>
</center>




<script>
    if (document.form1.o47_coddot.value != "")
        document.form1.o47_valor.focus();

function js_pesquisao47_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o47_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  if(erro==true){
    document.form1.o47_coddot.focus();
    document.form1.o47_coddot.value = '';
  } else {
    if ($('o07_sequencial')) {
      $('o07_sequencial').value = '';
    }
    document.form1.pesquisa_dot.click();
    document.form1.o47_valor.focus();
  }
}
 
function js_pesquisaop01_db_operacaodecredito(mostra) {
      if (mostra == true) {
        js_OpenJanelaIframe('', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?funcao_js=parent.js_mostraoperacaodecredito1|op01_sequencial|op01_numerocontratoopc|op01_dataassinaturacop|o47_numerocontratooc|o47_dataassinaturacop|o47_codigoopcredito', 'Pesquisa', true);
      } else {
        if (document.form1.o47_codigoopcredito.value != '') {
          js_OpenJanelaIframe('', 'db_iframe_db_operacaodecredito', 'func_db_operacaodecredito.php?pesquisa_chave=' + document.form1.o47_codigoopcredito.value +'&funcao_js=parent.js_mostraoperacaodecredito', 'Pesquisa', false);
        } else {
          document.form1.o47_codigoopcredito.value = '';
          document.form1.o47_numerocontratooc.value = '';
          document.form1.o47_dataassinaturacop.value = '';
        }
      }	
	}
  function js_mostraoperacaodecredito1(chave,chave1,chave2,chave3,chave4) {
    
		document.form1.o47_codigoopcredito.value = chave;
		document.form1.o47_numerocontratooc .value = chave1;
		var data = chave2.split("-", 3);
		document.form1.o47_dataassinaturacop.value = data[2] + "-" + data[1] + "-" + data[0];

		db_iframe_db_operacaodecredito.hide();
	}
  	
	function js_mostraoperacaodecredito(chave,chave1,erro) {
		document.form1.o47_numerocontratooc .value = chave;
		var data = chave1.split("-", 3);
		document.form1.o47_dataassinaturacop.value = data[2] + "-" + data[1] + "-" + data[0];
  
	}

function js_mostraorcdotacao1(chave1) {  
    if ($('o07_sequencial')) {
        $('o07_sequencial').value = '';
    }
    document.form1.o47_coddot.value = chave1;
    document.form1.o47_valor.focus();
    db_iframe_orcdotacao.hide();
    document.form1.pesquisa_dot.click();
}

function js_mostracontratoop(chave1) {

if ($('o07_sequencial')) {
  $('o07_sequencial').value = '';
}
document.form1.o47_coddot.value = chave1;
db_iframe_orcdotacao.hide();
document.form1.pesquisa_dot.click();
}

// --------------------------------------

function js_pesquisao47_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_suplem','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_suplem','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.o47_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false);
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave;
  if(erro==true){
    document.form1.o47_coddoc.focus();
    document.form1.o47_coddoc.value = '';
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.o47_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_suplem','db_iframe_orcsuplemval','func_orcsuplemval.php?funcao_js=parent.js_preenchepesquisa|o45_anousu|1|2','Pesquisa',true,0);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_orcsuplemval.hide();
}

function js_importa_suplementacao(){
   js_OpenJanelaIframe('CurrentWindow.corpo.iframe_suplem','db_iframe_orcsuplem_imp','func_orcsuplem_importa.php?funcao_js=parent.js_importa_suplementacao_01|o46_codsup','Pesquisa',true);
}
function js_importa_suplementacao_01(chave1,chave2){
  document.form1.codsup_imp.value = chave1;
  db_iframe_orcsuplem_imp.hide();
}

function js_pesquisa_estimativa(mostra) {
  if (mostra) {
    js_OpenJanelaIframe('','db_iframe_ppadotacao','func_ppadotacaosuplementacao.php?funcao_js=parent.js_mostraestimativa1|o07_sequencial|dotacao','Pesquisar Estimativas de Despesa',true,0);
  }
}
function js_mostraestimativa1(chave1,chave2) {

  document.form1.o07_sequencial.value = chave1;
  db_iframe_ppadotacao.hide();
  getDadosDotacaoPPA(chave1);
}
function getDadosDotacaoPPA(iDotacao) {

  var oParam         = new Object();
  oParam.iEstimativa = iDotacao;
  oParam.exec        = 'getDadosDotacaoPPA';
  js_divCarregando('Aguarde, pesquisando dados...', 'msgBox');
  var oAjax          = new Ajax.Request('orc4_suplementacoes.RPC.php',{method:'post',parameters:'json='+Object.toJSON(oParam),onComplete: preencheDadosDotacao});
}
function preencheDadosDotacao(oAjax) {

js_removeObj('msgBox');
oRetorno = eval(" ("+oAjax.responseText+")"); 
if (oRetorno.status==1) { 
  $('o47_coddot').value='' ; 
  $('o58_orgao').value=oRetorno.dadosdotacao.o08_orgao; 
  $('o40_descr').value=oRetorno.dadosdotacao.o40_descr.urlDecode(); 
  $('o56_elemento').value=oRetorno.dadosdotacao.o56_elemento;
  $('o56_descr').value=oRetorno.dadosdotacao.o56_descr.urlDecode(); 
  $('o58_codigo').value=oRetorno.dadosdotacao.o08_recurso; 
  $('o15_descr').value=oRetorno.dadosdotacao.o15_descr; 
} else { 
  alert('Estimativa informada n�o possui estrutura v�lida.') ;
  $('o07_sequencial').value='' ; 
} } 
function js_pesquisao58_concarpeculiar(mostra){ 
  if(mostra==true){ 
    js_OpenJanelaIframe('', 'db_iframe_concarpeculiar' , 'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr' , 'Pesquisa' , true); 
  }else{ 
    if(document.form1.o58_concarpeculiar.value !='' ){ 
      js_OpenJanelaIframe('', 'db_iframe_concarpeculiar' , 'func_concarpeculiar.php?pesquisa_chave=' +document.form1.o58_concarpeculiar.value.trim()+'&funcao_js=parent.js_mostraconcarpeculiar', 'Pesquisa' , false); 
      }else{ 
        document.form1.c58_descr.value='' ; 
        } 
     } 
  } 
function js_mostraconcarpeculiar(chave,erro){ 
  document.form1.c58_descr.value=chave; 
  if(erro==true){ 
    document.form1.o58_concarpeculiar.focus(); 
    document.form1.o58_concarpeculiar.value='' ; 
  } 
} 
function js_mostraconcarpeculiar1(chave1,chave2){ 
    document.form1.o58_concarpeculiar.value=chave1; 
    document.form1.c58_descr.value=chave2; db_iframe_concarpeculiar.hide(); 
  } 
</script>