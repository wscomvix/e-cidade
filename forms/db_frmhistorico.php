<script>
parent.disciplina.location.href = "edu1_historicodisciplina.php?ed65_i_historicomps=0";
</script>
<?
if((isset($ed61_i_escola) && $ed61_i_escola!=db_getsession("DB_coddepto")) || (isset($situacao) && $situacao=="CONCLU�DO")){
 $db_opcao = 3;
 $db_opcao1 = 3;
 $db_opcao2 = 3;
 $db_botao = false;
}
//MODULO: educa��o
$clhistorico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed29_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted61_i_codigo?>">
   <?=@$Led61_i_codigo?>
  </td>
  <td>
   <?db_input('ed61_i_codigo',10,$Ied61_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted61_i_curso?>">
   <?db_ancora(@$Led61_i_curso,"js_pesquisaed61_i_curso(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed61_i_curso',10,$Ied61_i_curso,true,'text',$db_opcao1," onchange='js_pesquisaed61_i_curso(false);'")?>
   <?db_input('ed29_c_descr',40,@$Ied29_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Situa��o:</b>
  </td>
  <td>
   <?db_input('situacao',15,@$situacao,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted61_i_aluno?>">
   <?db_ancora(@$Led61_i_aluno,"js_pesquisaed61_i_aluno(true);",3);?>
  </td>
  <td>
   <?db_input('ed61_i_aluno',10,$Ied61_i_aluno,true,'text',3," onchange='js_pesquisaed61_i_aluno(false);'")?>
   <?db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted61_i_escola?>">
   <?db_ancora(@$Led61_i_escola,"js_pesquisaed61_i_escola(true);",$db_opcao2);?>
  </td>
  <td>
   <?db_input('ed61_i_escola',10,$Ied61_i_escola,true,'text',$db_opcao2," onchange='js_pesquisaed61_i_escola(false);'")?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted61_t_obs?>">
   <?=@$Led61_t_obs?>
  </td>
  <td>
   <?db_textarea('ed61_t_obs',6,50,$Ied61_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted61_i_anoconc?>">
   <?=@$Led61_i_anoconc?>
  </td>
  <td>
   <?db_input('ed61_i_anoconc',4,$Ied61_i_anoconc,true,'text',3,"")?>
   <?=@$Led61_i_periodoconc?>
   <?db_input('ed61_i_periodoconc',4,$Ied61_i_periodoconc,true,'text',3,"")?>
  </td>
 </tr>
</table>
<input type="hidden" name="ed61_i_aluno1" value="<?=$ed61_i_aluno?>">
<input type="hidden" name="ed47_v_nome1" value="<?=$ed47_v_nome?>">
</center>
<?if($db_opcao==1){?>
 <input name="incluir" type="submit" id="db_opcao" value="Incluir" <?=($db_botao==false?"disabled":"")?> >
<?}else{?>
 <input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?> >
 <input name="excluir" type="submit" id="db_opcao" value="Excluir" <?=($db_botao==false?"disabled":"")?> >
 <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='edu1_historico001.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>'" <?=($db_opcao==1||$db_opcao==3?"disabled":"")?>>
<?}?>
</form>
<script>
function js_pesquisaed61_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('parent','db_iframe_escola','func_escola.php?funcao_js=parent.dados.js_mostraescola1|ed18_i_codigo|ed18_c_nome','Pesquisa de Escolas',true);
 }else{
  if(document.form1.ed61_i_escola.value != ''){
   js_OpenJanelaIframe('parent','db_iframe_escola','func_escola.php?pesquisa_chave='+document.form1.ed61_i_escola.value+'&funcao_js=parent.dados.js_mostraescola','Pesquisa',false);
  }else{
   document.form1.ed18_c_nome.value = '';
  }
 }
}
function js_mostraescola(chave,erro){
 document.form1.ed18_c_nome.value = chave;
 if(erro==true){
  document.form1.ed61_i_escola.focus();
  document.form1.ed61_i_escola.value = '';
 }
}
function js_mostraescola1(chave1,chave2){
 document.form1.ed61_i_escola.value = chave1;
 document.form1.ed18_c_nome.value = chave2;
 parent.db_iframe_escola.hide();
}
function js_pesquisaed61_i_curso(mostra,aluno){
 if(mostra==true){
  js_OpenJanelaIframe('parent','db_iframe_cursoedu','func_cursoeduhist.php?aluno='+document.form1.ed61_i_aluno.value+'&funcao_js=parent.dados.js_mostracursoedu1|ed29_i_codigo|ed29_c_descr','Pesquisa de Cursos',true);
 }else{
  if(document.form1.ed61_i_curso.value != ''){
   js_OpenJanelaIframe('parent','db_iframe_cursoedu','func_cursoeduhist.php?aluno='+document.form1.ed61_i_aluno.value+'&pesquisa_chave='+document.form1.ed61_i_curso.value+'&funcao_js=parent.dados.js_mostracursoedu','Pesquisa',false);
  }else{
   document.form1.ed29_c_descr.value = '';
  }
 }
}
function js_mostracursoedu(chave,erro){
 document.form1.ed29_c_descr.value = chave;
 if(erro==true){
  document.form1.ed61_i_curso.focus();
  document.form1.ed61_i_curso.value = '';
 }
}
function js_mostracursoedu1(chave1,chave2){
 document.form1.ed61_i_curso.value = chave1;
 document.form1.ed29_c_descr.value = chave2;
 parent.db_iframe_cursoedu.hide();
}
</script>
