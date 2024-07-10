<?
//MODULO: Obras
$cllicobrasituacao->rotulo->label();
?>
<form name="form1" method="post" action="">
  <center>
    <fieldset>
      <legend>Situa��o da Obra</legend>
      <table border="0" style="margin-left: -1%;">
        <tr style="display: none">
          <td nowrap title="<?=@$Tobr02_sequencial?>">
            <input name="oid" type="hidden" value="<?=@$oid?>">
            <strong>Cod. Sequencial:</strong>
          </td>
          <td>
            <?
            db_input('obr02_sequencial',11,$Iobr02_sequencial,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>
              N�mero da Obra:
            </strong>
          </td>
          <td>
            <?
            db_input('obr01_numeroobra',11,$Iobr01_numeroobra,true,'text',3,"");
            ?>
          </td>
        </tr>

        <tr>
          <td>
            <?
            db_ancora('Sequencial da Obra: ',"js_pesquisa_obra(true)",$db_opcao);
            ?>
          </td>
          <td>
            <?
            db_input('obr02_seqobra',11,$Iobr02_seqobra,true,'text',$db_opcao,"onchange='js_pesquisa_obra(false)'");
            ?>
          </td>
          <td>
            <strong>Processo Licitat�rio: </strong>
          </td>
          <td>
            <?
            db_input('l20_edital',6,$Il20_edital,true,'text',3,"");
            ?>
          </td>
          <td>
            <strong>Modalidade: </strong>
            <?
            db_input('tipocompra',25,'',true,'text',3,"");
            ?>
          </td>
          <td>
            <strong>N�: </strong>
            <?
            db_input('l20_numero',6,$Il20_numero,true,'text',3,"");
            ?>
          </td>

        </tr>
      </table>
      <hr>
      <table>
        <tr>
          <td nowrap title="<?=@$Tobr02_dtlancamento?>">
            <?=@$Lobr02_dtlancamento?>
          </td>
          <td>
            <?
            if(!isset($obr02_dtlancamento)) {
              $obr02_dtlancamento_dia=date('d',db_getsession("DB_datausu"));
              $obr02_dtlancamento_mes=date('m',db_getsession("DB_datausu"));
              $obr02_dtlancamento_ano=date('Y',db_getsession("DB_datausu"));
            }
            db_inputdata('obr02_dtlancamento',@$obr02_dtlancamento_dia,@$obr02_dtlancamento_mes,@$obr02_dtlancamento_ano,true,'text',$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tobr02_situacao?>">
            <?=@$Lobr02_situacao?>
          </td>
          <td>
            <?
            $aValores = array(0 => 'Selecione',
              1 => '1 - N�o Iniciada',
              2 => '2 - Iniciada',
              3 => '3 - Paralisada por rescis�o contratual',
              4 => '4 - Paralisada',
              5 => '5 - Conclu�da e n�o recebida',
              6 => '6 - Conclu�da e recebida provisoriamente',
              7 => '7 - Conclu�da e recebida definitivamente',
              8 => '8 - Reiniciada');
            db_select('obr02_situacao', $aValores, true, $db_opcao," onchange='js_verificasituacao(this.value)'");
            ?>
          </td>
          <td>
            <?=@$Lobr02_dtsituacao?>
          </td>
          <td>
            <?
            db_inputdata('obr02_dtsituacao',@$obr02_dtsituacao_dia,@$obr02_dtsituacao_mes,@$obr02_dtsituacao_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tobr02_veiculopublicacao?>">
            <?=@$Lobr02_veiculopublicacao?>
          </td>
          <td>
            <?
            db_input('obr02_veiculopublicacao',50,$Iobr02_veiculopublicacao,true,'text',$db_opcao,"");
            ?>
          </td>
          <td>
            <strong>Data Public. Veic.:</strong>
          </td>
          <td>
            <?
            db_inputdata('obr02_dtpublicacao',@$obr02_dtpublicacao_dia,@$obr02_dtpublicacao_mes,@$obr02_dtpublicacao_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tobr02_descrisituacao?>">
            <?=@$Lobr02_descrisituacao?>
          </td>
          <td colspan="3">
            <?
            db_textarea('obr02_descrisituacao',0,0,$Iobr02_descrisituacao,true,'text',$db_opcao,"","","",'500')
            ?>
          </td>
        </tr>
      </table>
      <fieldset id="paralisaobra" style="display: none">
        <legend>Paralisa��o da Obra</legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Tobr02_motivoparalisacao?>">
              <?=@$Lobr02_motivoparalisacao?>
            </td>
            <td>
              <?
              $aValoresmotivo = array(
                0 => 'Selecione',
                1 => '1 - Atrasos do repasse de conv�nios',
                2 => '2 - Suspens�o do repasse de conv�nios',
                3 => '3 - Bloqueio do repasse de conv�nios',
                4 => '4 - Repasses de conv�nios em valor inferior ao programado',
                5 => '5 - Contingenciamento de recursos pr�prios',
                6 => '6 - Inadequa��o ao plano de trabalho da nova gest�o',
                7 => '7 - Irregularidades/problemas afetos ao meio ambiente',
                8 => '8 - Pend�ncias com desapropria��es',
                9 => '9 - Quest�es t�cnicas que vieram a ser conhecidas somente ap�s a licita��o',
                10 => '10 - Riscos decorrentes de erros e v�cios construtivos',
                11 => '11 - Descumprimento de especifica��es t�cnicas e prazos',
                12 => '12 - Irregularidades nos pre�os e servi�os contratados',
                13 => '13 - Problemas relacionados � contratada (exemplos: recupera��o judicial, dissolu��o, etc.)',
                14 => '14 - Caso Fortuito ou For�a Maior',
                15 => '15 - Ordem Judicial',
                99 => '99 - Outros tipos de paralisa��o');
              db_select('obr02_motivoparalisacao', $aValoresmotivo, true, $db_opcao," onchange='js_motivoParalisacao(this.value)'");
              ?>
            </td>
            <td>
              <?=@$Lobr02_dtparalisacao?>
              <?
              db_inputdata('obr02_dtparalisacao',@$obr02_dtparalisacao_dia,@$obr02_dtparalisacao_mes,@$obr02_dtparalisacao_ano,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr id="trOutrosMotivos" style="display: none">
            <td nowrap title="<?=@$Tobr02_outrosmotivos?>">
              <?=@$Lobr02_outrosmotivos?>
            </td>
            <td colspan="2">
              <?
              db_textarea('obr02_outrosmotivos',0,0,$Iobr02_outrosmotivos,true,'text',$db_opcao,"","","",'150');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tobr02_dtretomada?>">
              <?=@$Lobr02_dtretomada?>
            </td>
            <td>
              <?
              db_inputdata('obr02_dtretomada',@$obr02_dtretomada_dia,@$obr02_dtretomada_mes,@$obr02_dtretomada_ano,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </fieldset>
  </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <input name="Nova Situa��o" type="button" id="Nova Situa��o" value="Nova Situa��o" onclick="js_novasituacao();" >
</form>
<script>
  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_licobrasituacao2','func_licobrasituacao.php?funcao_js=parent.js_preenchepesquisa|obr02_sequencial','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_licobrasituacao2.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }

  js_carregar();
  function js_novasituacao() {
    document.location.href = 'obr1_licobrasituacao001.php'
  }
  /**
   * funcao para retornar obras
   */
  function js_pesquisa_obra(mostra){
    if(mostra==true){

      js_OpenJanelaIframe('CurrentWindow.corpo',
        'db_iframe_licobrasituacao',
        'func_licobras.php?pesquisa=true&funcao_js=parent.js_preencheObra|obr01_sequencial|l20_edital|l20_numero|l03_descr|obr01_numeroobra',
        'Pesquisa Obras',true);
    }else{

      if(document.form1.obr02_seqobra.value != ''){

        js_OpenJanelaIframe('CurrentWindow.corpo',
          'db_iframe_licobrasituacao',
          'func_licobras.php?pesquisa=true&pesquisa_chave='+
          document.form1.obr02_seqobra.value+'&funcao_js=parent.js_preencheObra2',
          'Pesquisa',false);
      }else{
        document.form1.obr02_seqobra.value = '';
      }
    }
  }
  /**
   * funcao para preencher licitacao  da ancora
   */
  function js_preencheObra(codigo,edital,numero,descrcompra,numeroobra)
  {
    document.form1.obr02_seqobra.value = codigo;
    document.form1.tipocompra.value = descrcompra;
    document.form1.l20_edital.value = edital;
    document.form1.l20_numero.value = numero;
    document.form1.obr01_numeroobra.value = numeroobra;

    db_iframe_licobrasituacao.hide();
  }

  function js_preencheObra2(edital,descrcompra,numero,numeroobra,erro) {
    document.form1.tipocompra.value = descrcompra;
    document.form1.l20_numero.value = numero;
    document.form1.l20_edital.value = edital;
    document.form1.obr01_numeroobra.value = numeroobra;

    if(erro==true){
      alert('Nenhuma obra encontrada.');
      document.form1.obr02_seqobra.focus();
      document.form1.obr01_numeroobra.value = "";
      document.form1.tipocompra.value = "";
      document.form1.l20_numero.value = "";
      document.form1.l20_edital.value = "";
    }
  }

  function js_verificasituacao(value) {
    if(value == '3'){
      document.getElementById('paralisaobra').style.display = '';
    }else if (value == '4'){
      document.getElementById('paralisaobra').style.display = '';
    }else{
      document.getElementById('paralisaobra').style.display = 'none';
    }
  }

  function js_carregar() {
    let db_opcao = <?=$db_opcao?>;
    let situacao = document.form1.obr02_situacao.value;
    let motivoParalisacao = document.form1.obr02_motivoparalisacao.value;

    if(db_opcao != 1){
      js_pesquisa_obra(false);
      motivoParalisacao == '99' ? document.getElementById('trOutrosMotivos').style.display = '' : document.getElementById('trOutrosMotivos').style.display = 'none';
    }

    if(situacao == '3' || situacao == '4'){
      document.getElementById('paralisaobra').style.display = '';
      return;
    }
      
    document.getElementById('paralisaobra').style.display = 'none';
  }

  function js_motivoParalisacao(motivoParalisacao) {
    if(motivoParalisacao == '99'){
      document.getElementById('trOutrosMotivos').style.display = '';
      return true;
    }
    document.getElementById('trOutrosMotivos').style.display = 'none';
  }

</script>
