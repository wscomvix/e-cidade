<?
//MODULO: agua
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claguabasecorresp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x02_codcorresp");
$clrotulo->label("x01_numcgm");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $x32_codcorresp = "";
   }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx32_codcorresp?>">
       <?
       db_ancora(@$Lx32_codcorresp,"js_pesquisax32_codcorresp(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('x32_codcorresp',10,$Ix32_codcorresp,true,'text',$db_opcao," onchange='js_pesquisax32_codcorresp(false);'")
?>
       <?
db_input('x02_codcorresp',10,$Ix02_codcorresp,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx32_matric?>">
       <?
       db_ancora(@$Lx32_matric,"js_pesquisax32_matric(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('x32_matric',10,$Ix32_matric,true,'text',$db_opcao," onchange='js_pesquisax32_matric(false);'")
?>
       <?
db_input('z01_nome',10,$Ix01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">
    <?
	 $chavepri= array("x32_matric"=>@$x32_matric);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 //$cliframe_alterar_excluir->sql     = $claguabasecorresp->sql_query_file($x32_matric);
	 $cliframe_alterar_excluir->sql     = $claguabasecorresp->sql_query($x32_matric);
	 $cliframe_alterar_excluir->campos  ="x32_codcorresp,x32_matric,z01_nome";
	 $cliframe_alterar_excluir->legenda="ITENS LAN�ADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisax32_codcorresp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_aguabasecorresp','db_iframe_aguacorresp','func_aguacorresp.php?funcao_js=parent.js_mostraaguacorresp1|x02_codcorresp|x02_codcorresp','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x32_codcorresp.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_aguabasecorresp','db_iframe_aguacorresp','func_aguacorresp.php?pesquisa_chave='+document.form1.x32_codcorresp.value+'&funcao_js=parent.js_mostraaguacorresp','Pesquisa',false);
     }else{
       document.form1.x02_codcorresp.value = '';
     }
  }
}
function js_mostraaguacorresp(chave,erro){
  document.form1.x02_codcorresp.value = chave;
  if(erro==true){
    document.form1.x32_codcorresp.focus();
    document.form1.x32_codcorresp.value = '';
  }
}
function js_mostraaguacorresp1(chave1,chave2){
  document.form1.x32_codcorresp.value = chave1;
  document.form1.x02_codcorresp.value = chave2;
  db_iframe_aguacorresp.hide();
}
function js_pesquisax32_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_aguabasecorresp','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x32_matric.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_aguabasecorresp','db_iframe_aguabase','func_aguabase.php?pesquisa_chave='+document.form1.x32_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false);
     }else{
       document.form1.x01_numcgm.value = '';
     }
  }
}
function js_mostraaguabase(chave,erro){
  document.form1.x01_numcgm.value = chave;
  if(erro==true){
    document.form1.x32_matric.focus();
    document.form1.x32_matric.value = '';
  }
}
function js_mostraaguabase1(chave1,chave2){
  document.form1.x32_matric.value = chave1;
  document.form1.x01_numcgm.value = chave2;
  db_iframe_aguabase.hide();
}
</script>
