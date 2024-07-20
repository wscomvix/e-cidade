<?
//MODULO: escola_ecidade
$clalunos->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Talu01_codigo?>">
       <?=@$Lalu01_codigo?>
    </td>
    <td> 
<?
db_input('alu01_codigo',10,$Ialu01_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Talu01_nome?>">
       <?=@$Lalu01_nome?>
    </td>
    <td> 
<?
db_input('alu01_nome',30,$Ialu01_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Talu01_email?>">
       <?=@$Lalu01_email?>
    </td>
    <td> 
<?
db_input('alu01_email',50,$Ialu01_email,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_alunos','func_alunos.php?funcao_js=parent.js_preenchepesquisa|alu01_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_alunos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
