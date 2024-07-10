<?
//MODULO: agua
$clagualeitura->rotulo->label();
$clrotulo = new rotulocampo;
// REFER�NCIA
$clrotulo->label("x04_matric");
// DADOS DA MATR�CULA
$clrotulo->label("x01_numcgm");
$clrotulo->label("x01_codrua");
$clrotulo->label("x01_numero");
$clrotulo->label("x01_letra");
$clrotulo->label("x01_zona");
$clrotulo->label("x01_qtdeconomia");
$clrotulo->label("x01_multiplicador");
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
// DADOS DO HIDR�METRO
$clrotulo->label("x04_nrohidro");
$clrotulo->label("x04_qtddigito");
$clrotulo->label("x15_diametro");
$clrotulo->label("x03_nomemarca");
// LEITURA ANTERIOR e ATUAL
$clrotulo->label("x17_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>Refer�ncia</b></legend>
	<table width="100%">
          <tr>
            <td nowrap title="<?=@$Tx21_exerc?>" align="right">
              <b><?=@$RLx21_exerc?>&nbsp;/&nbsp;<?=@$RLx21_mes?>:</b>
            </td>
            <td>
              <?
              if(!isset($x21_exerc) || (isset($x21_exerc) && trim($x21_exerc) == "")){
                $x21_exerc = db_getsession("DB_anousu");
              }
              db_input('x21_exerc',4,$Ix21_exerc,true,'text',$db_opcao==1?1:3,"");
              ?>
              <b>&nbsp;/&nbsp;</b>
              <?
              if(!isset($x21_mes) || (isset($x21_mes) && trim($x21_mes) == "")){
                $x21_mes = date("m",db_getsession("DB_datausu"));
              }
              db_input('x21_mes',2,$Ix21_mes,true,'text',$db_opcao==1?1:3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Tx04_matric?>" align="right">
              <?
              db_ancora(@$Lx04_matric,"js_pesquisax04_matric(true);",$db_opcao==1?1:3);
              ?>
            </td>
            <td>
              <?
              db_input('x04_matric',10,$Ix04_matric,true,'text',$db_opcao==1?1:3," onchange='js_pesquisax04_matric(false);'");
              ?>
            </td>
          </tr>
	</table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>Dados da matr�cula</b></legend>
	<table width="100%">
          <tr>
            <td nowrap title="<?=@$Tx01_numcgm?>" align="right">
              <?=@$Lx01_numcgm?>
            </td>
            <td colspan="5">
              <?
              db_input('x01_numcgm',8,$Ix01_numcgm,true,'text',3,"");
              ?>
              <?
              db_input('z01_nome',45,$Iz01_nome,true,'text',3,"","z01_nomedad");
              ?>
            </td>
	  </tr>
	  <tr>
            <td nowrap title="<?=@$Tx01_codrua?>" align="right">
              <?
              db_ancora(@$Lx01_codrua,"",3);
              ?>
            </td>
            <td colspan="5">
              <?
              db_input('x01_codrua',8,$Ix01_codrua,true,'text',3,"");
              ?>
              <?
              db_input('j14_nome',45,$Ij14_nome,true,'text',3,"");
              ?>
            </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Tx01_numero?>" align="right">
              <?
              db_ancora(@$Lx01_numero,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x01_numero',8,$Ix01_numero,true,'text',3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Tx01_letra?>" align="right">
              <?
              db_ancora(@$Lx01_letra,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x01_letra',8,$Ix01_letra,true,'text',3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Tx01_zona?>" align="right">
              <?
              db_ancora(@$Lx01_zona,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x01_zona',8,$Ix01_zona,true,'text',3,"");
              ?>
            </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Tx01_qtdeconomia?>" align="right">
              <?
              db_ancora(@$Lx01_qtdeconomia,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x01_qtdeconomia',8,$Ix01_qtdeconomia,true,'text',3,"");
              ?>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td nowrap title="<?=@$Tx01_multiplicador?>" align="right">
	      <b>
              <?
              db_ancora(@$RLx01_multiplicador.":","",3);
              ?>
	      </b>
            </td>
            <td>
              <?
              db_input('x01_multiplicador',8,0,true,'text',3,"");
              ?>
            </td>
          </tr>
	</table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>Dados do hidr�metro</b></legend>
	<table width="100%">
          <tr>
            <td nowrap title="<?=@$Tx04_nrohidro?>" align="right">
              <?=@$Lx04_nrohidro?>
            </td>
            <td>
              <?
              db_input('x04_nrohidro',30,$Ix04_nrohidro,true,'text',3,"");
              db_input('x21_codhidrometro',6,$Ix21_codhidrometro,true,'hidden',3,"");
              db_input('x21_codleitura',6,$Ix21_codleitura,true,'hidden',3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Tx04_qtddigito?>" align="right">
              <?
              db_ancora(@$Lx04_qtddigito,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x04_qtddigito',10,$Ix04_qtddigito,true,'text',3,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tx03_nomemarca?>" align="right">
              <?
              db_ancora(@$Lx03_nomemarca,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x03_nomemarca',30,$Ix03_nomemarca,true,'text',3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Tx15_diametro?>" align="right">
              <?=@$Lx15_diametro?>
            </td>
            <td>
              <?
              db_input('x15_diametro',10,$Ix15_diametro,true,'text',3,"");
              ?>
            </td>
          </tr>
	</table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>Leitura anterior</b></legend>
	<table width="100%">
          <tr>
            <td nowrap title="<?=@$Tx21_situacao?>" align="right">
              <?
	      db_ancora(@$Lx21_situacao,"",3);
	      ?>
            </td>
            <td colspan="5">
              <?
              db_input('x21_situacao',8,$Ix21_situacao,true,'text',3,"","x21_situacant");
              ?>
              <?
              db_input('x17_descr',44,$Ix17_descr,true,'text',3,"","x17_descrant");
              ?>
            </td>
	  </tr>
	  <tr>
            <td nowrap title="<?=@$Tx21_numcgm?>" align="right">
              <?
              db_ancora(@$Lx21_numcgm,"",3);
              ?>
            </td>
            <td colspan="5">
              <?
              db_input('x21_numcgm',8,$Ix21_numcgm,true,'text',3,"","x21_numcgmant");
              ?>
              <?
              db_input('z01_nome',44,$Iz01_nome,true,'text',3,"","z01_nomeant");
              ?>
            </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Tx21_dtleitura?>" align="right">
              <?
              db_ancora(@$Lx21_dtleitura,"",3);
              ?>
            </td>
            <td colspan="5">
              <?
              db_inputdata('x21_dtleitura',@$x21_dtleituraant_dia,@$x21_dtleituraant_mes,@$x21_dtleituraant_ano,true,'text',3,"","x21_dtleituraant");
              ?>
            </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Tx21_leitura?>" align="right">
              <?
              db_ancora(@$Lx21_leitura,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x21_leitura',8,$Ix21_leitura,true,'text',3,"","x21_leituraant");
              ?>
            </td>
            <td nowrap title="<?=@$Tx21_consumo?>" align="right">
              <?
              db_ancora(@$Lx21_consumo,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x21_consumo',8,$Iz01_nome,true,'text',3,"","x21_consumoant");
              ?>
            </td>
            <td nowrap title="<?=@$Tx21_excesso?>" align="right">
              <?
              db_ancora(@$Lx21_excesso,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x21_excesso',8,$Ix21_excesso,true,'text',3,"","x21_excessoant");
              ?>
            </td>
          </tr>
	</table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><b>Dados leitura</b></legend>
	<table width="100%">
          <tr>
            <td nowrap title="<?=@$Tx21_situacao?>" align="right">
              <?
	      db_ancora(@$Lx21_situacao,"js_pesquisax21_situacao(true);",$db_opcao);
	      ?>
            </td>
            <td colspan="5">
              <?
              db_input('x21_situacao',8,$Ix21_situacao,true,'text',$db_opcao,"onchange='js_pesquisax21_situacao(false);'","");
              ?>
              <?
              db_input('x17_descr',44,$Ix17_descr,true,'text',3,"","");
              ?>
            </td>
	  </tr>
	  <tr>
            <td nowrap title="<?=@$Tx21_numcgm?>" align="right">
              <?
              db_ancora(@$Lx21_numcgm,"js_pesquisax21_numcgm(true);",$db_opcao);
              ?>
            </td>
            <td colspan="5">
              <?
              db_input('x21_numcgm',8,$Ix21_numcgm,true,'text',$db_opcao,"onchange='js_pesquisax21_numcgm(false);'","");
              ?>
              <?
              db_input('z01_nome',44,$Iz01_nome,true,'text',3,"","");
              ?>
            </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Tx21_dtleitura?>" align="right">
              <?
              db_ancora(@$Lx21_dtleitura,"",3);
              ?>
            </td>
            <td colspan="5">
              <?
              db_inputdata('x21_dtleitura',@$x21_dtleitura_dia,@$x21_dtleitura_mes,@$x21_dtleitura_ano,true,'text',$db_opcao,"","");
              ?>
            </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Tx21_leitura?>" align="right">
              <?
              db_ancora(@$Lx21_leitura,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x21_leitura',8,$Ix21_leitura,true,'text',$db_opcao,"onchange='js_ver_leitura_anterior();'","");
              ?>
            </td>
            <td nowrap title="<?=@$Tx21_consumo?>" align="right">
              <?
              db_ancora(@$Lx21_consumo,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x21_consumo',8,$Iz01_nome,true,'text',3,"","");
              ?>
            </td>
            <td nowrap title="<?=@$Tx21_excesso?>" align="right">
              <?
              db_ancora(@$Lx21_excesso,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('x21_excesso',8,$Ix21_excesso,true,'text',3,"","");
              ?>
            </td>
          </tr>
	</table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verifica_campos();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" onblur='js_tabulacaoforms("form1","x21_exerc",true,1,"x21_exerc",true);'>
</form>
<script>
function js_verifica_campos(){
  if(document.form1.x21_exerc.value == ""){
    alert("Informe o exerc�cio.");
    document.form1.x21_exerc.focus()
  }else if(document.form1.x21_mes.value == ""){
    alert("Informe o m�s.");
    document.form1.x21_mes.focus()
  }else if(document.form1.x04_matric.value == ""){
    alert("Informe a matr�cula.");
    document.form1.x04_matric.focus()
  }else if(document.form1.x21_situacao.value == ""){
    alert("Informe a situa��o.");
    document.form1.x21_situacao.focus()
  }else if(document.form1.x21_numcgm.value == ""){
    alert("Informe o leiturista.");
    document.form1.x21_numcgm.focus()
  }else if(document.form1.x21_dtleitura_dia.value == "" || document.form1.x21_dtleitura_mes.value == "" || document.form1.x21_dtleitura_ano.value == ""){
    alert("Informe a data da leitura.");
    document.form1.x21_dtleitura_dia.focus()
    document.form1.x21_dtleitura_dia.select()
  }else if(document.form1.x21_leitura.value == ""){
    alert("Informe o valor da leitura.");
    document.form1.x21_leitura.focus()
  }else{
    return true;
  }
  return false;
}
function js_ver_leitura_anterior(){
  leitura_atu = new Number(document.form1.x21_leitura.value);
  leitura_ant = new Number(document.form1.x21_leituraant.value);
  if(leitura_atu < leitura_ant){
    alert("Leitura atual menor que a leitura anterior. Verifique");
  }
}
function js_repete_leitura_anterior(repete){
  if(repete == true){
    document.form1.x21_leitura.value = document.form1.x21_leituraant.value;
    document.form1.x21_consumo.value = 0;
    document.form1.x21_excesso.value = 0;
    document.form1.x21_leitura.readOnly=true;
    document.form1.x21_leitura.style.backgroundColor="#DEB887";
  }else{
    document.form1.x21_leitura.readOnly=false;
    document.form1.x21_leitura.style.backgroundColor="";
  }
  js_tabulacaoforms("form1","x21_exerc",false,1,"x21_exerc",false);
}
function js_retorna_dados_hidro(matricula){
  qry = "";
  if(document.form1.x21_exerc.value != ""){
    qry+= "&exerc="+document.form1.x21_exerc.value;
  }else{
    qry+= "&exerc=<?=db_getsession("DB_anousu")?>";
  }
  if(document.form1.x21_mes.value != ""){
    qry+= "&mes="+document.form1.x21_mes.value;
  }else{
    qry+= "&mes=<?=date("m",db_getsession("DB_datausu"))?>";
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_retorna_dados_hidro','func_retorna_dados_hidro.php?matric='+matricula+qry,'Pesquisa',false);
}
function js_pesquisax04_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguabase','func_aguabasealt.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm|z01_nome|j14_codigo|j14_nome|x01_numero|x01_letra|x01_zona|x01_qtdeconomia|x01_multiplicador|x04_matric','Pesquisa',true);
  }else{
     if(document.form1.x04_matric.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_agualeiturista','func_aguabasealt.php?pesquisa_chave='+document.form1.x04_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false);
     }else{
       document.form1.x04_matric.value = '';
       document.form1.z01_nomedad.value   = '';
       document.form1.x01_numcgm.value = '';
       document.form1.x01_codrua.value = '';
       document.form1.j14_nome.value   = '';
       document.form1.x01_numero.value = '';
       document.form1.x01_letra.value  = '';
       document.form1.x01_zona.value   = '';
       document.form1.x01_qtdeconomia.value   = '';
       document.form1.x01_multiplicador.value = '';
       document.form1.x21_codhidrometro.value = '';
       document.form1.x04_nrohidro.value  = '';
       document.form1.x04_qtddigito.value = '';
       document.form1.x03_nomemarca.value = '';
       document.form1.x15_diametro.value  = '';
       document.form1.x21_situacant.value = '';
       document.form1.x17_descrant.value  = '';
       document.form1.x21_numcgmant.value = '';
       document.form1.z01_nomeant.value   = '';

       document.form1.x21_dtleituraant_dia.value = '';
       document.form1.x21_dtleituraant_mes.value = '';
       document.form1.x21_dtleituraant_ano.value = '';
       document.form1.x21_dtleituraant.value = '';

       document.form1.x21_leituraant.value = '';
       document.form1.x21_consumoant.value = '';
       document.form1.x21_excessoant.value = '';
       document.form1.x21_situacao.value = '';
       document.form1.x17_descr.value  = '';
       document.form1.x21_numcgm.value = '';
       document.form1.z01_nome.value   = '';
       document.form1.x21_dtleitura_dia.value = '';
       document.form1.x21_dtleitura_mes.value = '';
       document.form1.x21_dtleitura_ano.value = '';
       document.form1.x21_dtleitura.value = '';
       document.form1.x21_leitura.value = '';
       document.form1.x21_consumo.value = '';
       document.form1.x21_excesso.value = '';
     }
  }
}
function js_mostraaguabase(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,erro){
  document.form1.z01_nomedad.value   = chave2;
  if(erro==true || chave10 == ""){
    if(erro == false && chave10 == ""){
      document.form1.z01_nomedad.value   = "";
      alert("Matr�cula sem hidr�metro cadastrado.");
    }
    document.form1.x04_matric.value = '';
    js_pesquisax04_matric(false);
    document.form1.x04_matric.focus();
  }else{
    document.form1.x01_numcgm.value = chave1;
    document.form1.x01_codrua.value = chave3;
    document.form1.j14_nome.value   = chave4;
    document.form1.x01_numero.value = chave5;
    document.form1.x01_letra.value  = chave6;
    document.form1.x01_zona.value   = chave7;
    document.form1.x01_qtdeconomia.value   = chave8;
    document.form1.x01_multiplicador.value = chave9;
    js_retorna_dados_hidro(document.form1.x04_matric.value);
  }
}
function js_mostraaguabase1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11){
  if(chave11 != ""){
    document.form1.x04_matric.value = chave1;
    document.form1.x01_numcgm.value = chave2;
    document.form1.z01_nomedad.value   = chave3;
    document.form1.x01_codrua.value = chave4;
    document.form1.j14_nome.value   = chave5;
    document.form1.x01_numero.value = chave6;
    document.form1.x01_letra.value  = chave7;
    document.form1.x01_zona.value   = chave8;
    document.form1.x01_qtdeconomia.value   = chave9;
    document.form1.x01_multiplicador.value = chave10;
    js_retorna_dados_hidro(chave1);
    db_iframe_aguabase.hide();
  }else{
    alert("Matr�cula sem hidr�metro cadastrado.");
    document.form1.x04_matric.value = '';
    js_pesquisax04_matric(false);
    document.form1.x04_matric.focus();
  }
}
function js_pesquisax21_situacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguasitleitura','func_aguasitleituraalt.php?funcao_js=parent.js_mostraaguasitleitura1|x17_codigo|x17_descr|x17_regra','Pesquisa',true);
  }else{
     if(document.form1.x21_situacao.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguasitleitura','func_aguasitleituraalt.php?pesquisa_chave='+document.form1.x21_situacao.value+'&funcao_js=parent.js_mostraaguasitleitura','Pesquisa',false);
     }else{
       document.form1.x17_descr.value = '';
     }
  }
}
function js_mostraaguasitleitura(chave,chave2,erro){
  document.form1.x17_descr.value = chave;
  if(erro==true){
    document.form1.x21_situacao.focus();
    document.form1.x21_situacao.value = '';
  }else{
    if(chave2 == "0"){
      js_repete_leitura_anterior(false);
    }else{
      js_repete_leitura_anterior(true);
    }
  }
}
function js_mostraaguasitleitura1(chave1,chave2,chave3){
  document.form1.x21_situacao.value = chave1;
  document.form1.x17_descr.value = chave2;
  if(chave3 == "0"){
    js_repete_leitura_anterior(false);
  }else{
    js_repete_leitura_anterior(true);
  }
  db_iframe_aguasitleitura.hide();
}
function js_pesquisax21_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_agualeiturista','func_agualeituristaalt.php?funcao_js=parent.js_mostraagualeiturista1|x16_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.x21_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_agualeiturista','func_agualeituristaalt.php?pesquisa_chave='+document.form1.x21_numcgm.value+'&funcao_js=parent.js_mostraagualeiturista','Pesquisa',false);
     }else{
       document.form1.x16_numcgm.value = '';
     }
  }
}
function js_mostraagualeiturista(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.x21_numcgm.focus();
    document.form1.x21_numcgm.value = '';
  }
}
function js_mostraagualeiturista1(chave1,chave2){
  document.form1.x21_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_agualeiturista.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_agualeitura','func_agualeitura.php?funcao_js=parent.js_preenchepesquisa|x21_codleitura&chave_x21_exerc=<?=$x21_exerc?>&chave_x21_mes=<?=$x21_mes?>','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_agualeitura.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
