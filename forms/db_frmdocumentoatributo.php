<?
//MODU</center></center>LO: Configuracoes
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldocumentoatributo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db44_sequencial");
$clrotulo->label("nomecam");
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
     $db45_codcam = "";
     $db45_descricao = "";
     $db45_valordefault = "";
     $db45_tipo = "";
     $nomecam = "";
   }
}
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin_top:15px;">
<tr><td width=700 align=center>

<fieldset>
<legend><strong>Cadastro de Atributos</strong></legend>

<table border="0">

	<?
	db_input('db45_sequencial',10,$Idb45_sequencial,true,'hidden',3,"")
	?>

  <tr>
    <td nowrap title="<?=@$Tdb45_documento?>">
       <?
       db_ancora(@$Ldb45_documento,"js_pesquisadb45_documento(true);",3);
       ?>
    </td>
    <td>
		<?
		db_input('db45_documento',10,$db45_documento,true,'text',3,"")
		?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tdb45_descricao?>">
       <?=@$Ldb45_descricao?>
    </td>
    <td>
			<?
			db_input('db45_descricao',54,$Idb45_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>

    <tr>
    <td nowrap title="<?=@$Tdb45_tipo?>">
       <?=@$Ldb45_tipo?>
    </td>
    <td>
			<?
			$x = array('1'=>'Varchar','2'=>'Integer','3'=>'Date','4'=>'Float','5'=>'Boolean');
			db_select('db45_tipo',$x,true,$db_opcao,"style='width:200px;'");
			?>
    </td>
  </tr>

    <tr>
    <td nowrap title="<?=@$Tdb45_valordefault?>">
       <?=@$Ldb45_valordefault?>
    </td>
    <td>
			<?
			db_input('db45_valordefault',26,$Idb45_valordefault,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tdb45_codcam?>">
       <?
       db_ancora(@$Ldb45_codcam,"js_pesquisadb45_codcam(true);",$db_opcao);
       ?>
    </td>
    <td>
				<?
				db_input('db45_codcam',10,$Idb45_codcam,true,'text',$db_opcao," onchange='js_pesquisadb45_codcam(false);'")
				?>
       <?
        db_input('nomecam',40,$Inomecam,true,'text',3,'')
       ?></fieldset>
    </td>
  </tr>

 </table>

</fieldset>

</td></tr>
</table>

 <table>
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

   $sCampos  = " db45_sequencial, ";
   $sCampos .= " db45_descricao, ";
   $sCampos .= "             case ";
   $sCampos .= "   when db45_tipo = 1 then 'Varchar'
                   when db45_tipo = 2 then 'Integer'
                   when db45_tipo = 3 then 'Date'
                   when db45_tipo = 4 then 'Float'
                   when db45_tipo = 5 then 'Boolean'
                end as db45_tipo,
                db45_valordefault ";

	 $chavepri= array("db45_sequencial"=>@$db45_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldocumentoatributo->sql_query_file(null,$sCampos,null,"db45_documento=".$db45_documento);
	 $cliframe_alterar_excluir->campos  = "db45_sequencial, db45_descricao, db45_tipo, db45_valordefault"; //"db45_sequencial,db45_documento,db45_codcam,db45_descricao,db45_valordefault,db45_tipo";
	 $cliframe_alterar_excluir->legenda = "ATRIBUTOS CADASTRADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="680";
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
function js_pesquisadb45_documento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_documentoatributo','db_iframe_documento','func_documento.php?funcao_js=parent.js_mostradocumento1|db44_sequencial|db44_sequencial','Pesquisa',true,'0','1');
  }else{
     if(document.form1.db45_documento.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_documentoatributo','db_iframe_documento','func_documento.php?pesquisa_chave='+document.form1.db45_documento.value+'&funcao_js=parent.js_mostradocumento','Pesquisa',false);
     }else{
       document.form1.db44_sequencial.value = '';
     }
  }
}
function js_mostradocumento(chave,erro){
  document.form1.db44_sequencial.value = chave;
  if(erro==true){
    document.form1.db45_documento.focus();
    document.form1.db45_documento.value = '';
  }
}
function js_mostradocumento1(chave1,chave2){
  document.form1.db45_documento.value = chave1;
  document.form1.db44_sequencial.value = chave2;
  db_iframe_documento.hide();
}
function js_pesquisadb45_codcam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_documentoatributo','db_iframe_db_syscampo','func_db_syscampo.php?funcao_js=parent.js_mostradb_syscampo1|codcam|nomecam','Pesquisa',true,'0','1');
  }else{
     if(document.form1.db45_codcam.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_documentoatributo','db_iframe_db_syscampo','func_db_syscampo.php?pesquisa_chave='+document.form1.db45_codcam.value+'&funcao_js=parent.js_mostradb_syscampo','Pesquisa',false);
     }else{
       document.form1.nomecam.value = '';
     }
  }
}
function js_mostradb_syscampo(chave,erro){
  document.form1.nomecam.value = chave;
  if(erro==true){
    document.form1.db45_codcam.focus();
    document.form1.db45_codcam.value = '';
  }
}
function js_mostradb_syscampo1(chave1,chave2){
  document.form1.db45_codcam.value = chave1;
  document.form1.nomecam.value = chave2;
  db_iframe_db_syscampo.hide();
}
</script>
