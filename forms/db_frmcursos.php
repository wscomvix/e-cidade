<?
//MODULO: escola_ecidade
$clcursos->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcur01_codigo?>">
       <?=@$Lcur01_codigo?>
    </td>
    <td> 
<?
db_input('cur01_codigo',10,$Icur01_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcur01_nome?>">
       <?=@$Lcur01_nome?>
    </td>
    <td> 
<?
db_input('cur01_nome',50,$Icur01_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcur01_data_inicio?>">
       <?=@$Lcur01_data_inicio?>
    </td>
    <td> 
<?
db_inputdata('cur01_data_inicio',@$cur01_data_inicio_dia,@$cur01_data_inicio_mes,@$cur01_data_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcur01_carga_horaria?>">
       <?=@$Lcur01_carga_horaria?>
    </td>
    <td> 
<?
db_input('cur01_carga_horaria',10,$Icur01_carga_horaria,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_cursos.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
