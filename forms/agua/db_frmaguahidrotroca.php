<?
//MODULO: agua
$claguahidrotroca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x04_codmarca");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx28_codhidrometro?>">
       <?
       db_ancora(@$Lx28_codhidrometro,"js_pesquisax28_codhidrometro(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('x28_codhidrometro',6,$Ix28_codhidrometro,true,'text',$db_opcao," onchange='js_pesquisax28_codhidrometro(false);'")
?>
       <?
db_input('x04_codmarca',6,$Ix04_codmarca,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx28_codigo?>">
       <?=@$Lx28_codigo?>
    </td>
    <td>
<?
db_input('x28_codigo',10,$Ix28_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx28_dttroca?>">
       <?=@$Lx28_dttroca?>
    </td>
    <td>
<?
db_inputdata('x28_dttroca',@$x28_dttroca_dia,@$x28_dttroca_mes,@$x28_dttroca_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx28_obs?>">
       <?=@$Lx28_obs?>
    </td>
    <td>
<?
db_textarea('x28_obs',5, 60,$Ix28_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisax28_codhidrometro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguahidromatric','func_aguahidromatric.php?funcao_js=parent.js_mostraaguahidromatric1|x04_codhidrometro|x04_codmarca','Pesquisa',true);
  }else{
     if(document.form1.x28_codhidrometro.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguahidromatric','func_aguahidromatric.php?pesquisa_chave='+document.form1.x28_codhidrometro.value+'&funcao_js=parent.js_mostraaguahidromatric','Pesquisa',false);
     }else{
       document.form1.x04_codmarca.value = '';
     }
  }
}
function js_mostraaguahidromatric(chave,erro){
  document.form1.x04_codmarca.value = chave;
  if(erro==true){
    document.form1.x28_codhidrometro.focus();
    document.form1.x28_codhidrometro.value = '';
  }
}
function js_mostraaguahidromatric1(chave1,chave2){
  document.form1.x28_codhidrometro.value = chave1;
  document.form1.x04_codmarca.value = chave2;
  db_iframe_aguahidromatric.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguahidrotroca','func_aguahidrotroca.php?funcao_js=parent.js_preenchepesquisa|x28_codhidrometro','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_aguahidrotroca.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
