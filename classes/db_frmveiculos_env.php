<?
//MODULO: Tr�nsito
$clveiculos_env->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tr05_descr");
$clrotulo->label("db10_munic");
$clrotulo->label("tr07_id");
$clrotulo->label("tr09_tipo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<input type="hidden" name="tr08_idacidente" value="<?=db_getsession("id_acidente")?>" size=5>
  <tr>
    <td nowrap title="<?=@$Ttr08_id?>">
       <?=@$Ltr08_id?>
    </td>
    <td>
<?
db_input('tr08_id',5,$Itr08_id,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_idveiculo?>">
       <?
       db_ancora(@$Ltr08_idveiculo,"js_pesquisatr08_idveiculo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('tr08_idveiculo',5,$Itr08_idveiculo,true,'text',$db_opcao," onchange='js_pesquisatr08_idveiculo(false);'")
?>
       <?
db_input('tr05_descr',35,$Itr05_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_municipio?>">
       <?
       db_ancora(@$Ltr08_municipio,"js_pesquisatr08_municipio(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('tr08_municipio',5,$Itr08_municipio,true,'text',$db_opcao," onchange='js_pesquisatr08_municipio(false);'")
?>
       <?
db_input('db10_munic',60,$Idb10_munic,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_placa?>">
       <?=@$Ltr08_placa?>
    </td>
    <td>
<?
db_input('tr08_placa',7,$Itr08_placa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_condnome?>">
       <?=@$Ltr08_condnome?>
    </td>
    <td>
<?
db_input('tr08_condnome',50,$Itr08_condnome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_idade?>">
       <?=@$Ltr08_idade?>
    </td>
    <td>
<?
db_input('tr08_idade',0,$Itr08_idade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_idhabilitacao?>">
       <?
       db_ancora(@$Ltr08_idhabilitacao,"js_pesquisatr08_idhabilitacao(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('tr08_idhabilitacao',5,$Itr08_idhabilitacao,true,'text',$db_opcao," onchange='js_pesquisatr08_idhabilitacao(false);'")
?>
       <?
db_input('tr09_tipo',3,$Itr09_tipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr08_sexo?>">
       <?=@$Ltr08_sexo?>
    </td>
    <td>
<?
$x = array('Feminino'=>'Feminino','Masculino'=>'Masculino','NI'=>'NI');
db_select('tr08_sexo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>

  </table>
  </center>
  <input name="db_opcao" type="submit" id="db_opcao" value="Incluir">
  <input name="db_opcao2" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >-->
</form>
<iframe src="tra4_listaveiculos.php?acidente=<?=db_getsession("id_acidente");?>" height="40%" width="80%"></iframe>
<script>
function js_pesquisatr08_idveiculo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipo_veiculos','func_tipo_veiculos.php?funcao_js=parent.js_mostratipo_veiculos1|tr05_id|tr05_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipo_veiculos','func_tipo_veiculos.php?pesquisa_chave='+document.form1.tr08_idveiculo.value+'&funcao_js=parent.js_mostratipo_veiculos','Pesquisa',false);
  }
}
function js_mostratipo_veiculos(chave,erro){
  document.form1.tr05_descr.value = chave;
  if(erro==true){
    document.form1.tr08_idveiculo.focus();
    document.form1.tr08_idveiculo.value = '';
  }
}
function js_mostratipo_veiculos1(chave1,chave2){
  document.form1.tr08_idveiculo.value = chave1;
  document.form1.tr05_descr.value = chave2;
  db_iframe_tipo_veiculos.hide();
}
function js_pesquisatr08_municipio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_cepmunic','func_db_cepmunic.php?funcao_js=parent.js_mostradb_cepmunic1|db10_codigo|db10_munic','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_cepmunic','func_db_cepmunic.php?pesquisa_chave='+document.form1.tr08_municipio.value+'&funcao_js=parent.js_mostradb_cepmunic','Pesquisa',false);
  }
}
function js_mostradb_cepmunic(chave,erro){
  document.form1.db10_munic.value = chave;
  if(erro==true){
    document.form1.tr08_municipio.focus();
    document.form1.tr08_municipio.value = '';
  }
}
function js_mostradb_cepmunic1(chave1,chave2){
  document.form1.tr08_municipio.value = chave1;
  document.form1.db10_munic.value = chave2;
  db_iframe_db_cepmunic.hide();
}
function js_pesquisatr08_idacidente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_acidentes','func_acidentes.php?funcao_js=parent.js_mostraacidentes1|tr07_id|tr07_id','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_acidentes','func_acidentes.php?pesquisa_chave='+document.form1.tr08_idacidente.value+'&funcao_js=parent.js_mostraacidentes','Pesquisa',false);
  }
}
function js_mostraacidentes(chave,erro){
  document.form1.tr07_id.value = chave;
  if(erro==true){
    document.form1.tr08_idacidente.focus();
    document.form1.tr08_idacidente.value = '';
  }
}
function js_mostraacidentes1(chave1,chave2){
  document.form1.tr08_idacidente.value = chave1;
  document.form1.tr07_id.value = chave2;
  db_iframe_acidentes.hide();
}
function js_pesquisatr08_idhabilitacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipo_habilitacao','func_tipo_habilitacao.php?funcao_js=parent.js_mostratipo_habilitacao1|tr09_id|tr09_tipo','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipo_habilitacao','func_tipo_habilitacao.php?pesquisa_chave='+document.form1.tr08_idhabilitacao.value+'&funcao_js=parent.js_mostratipo_habilitacao','Pesquisa',false);
  }
}
function js_mostratipo_habilitacao(chave,erro){
  document.form1.tr09_tipo.value = chave;
  if(erro==true){
    document.form1.tr08_idhabilitacao.focus();
    document.form1.tr08_idhabilitacao.value = '';
  }
}
function js_mostratipo_habilitacao1(chave1,chave2){
  document.form1.tr08_idhabilitacao.value = chave1;
  document.form1.tr09_tipo.value = chave2;
  db_iframe_tipo_habilitacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_veiculos_env','func_veiculos_env.php?funcao_js=CurrentWindow.js_preenchepesquisa|tr08_id','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veiculos_env.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>