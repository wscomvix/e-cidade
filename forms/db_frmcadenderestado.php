<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

//MODULO: Configuracoes
$clcadenderestado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db70_descricao");
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top: 15px">
<tr><td>

<fieldset>
<legend><b>Estados</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb71_sequencial?>">
       <?=@$Ldb71_sequencial?>
    </td>
    <td>
<?
db_input('db71_sequencial',10,$Idb71_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb71_cadenderpais?>">
       <?
       db_ancora(@$Ldb71_cadenderpais,"js_pesquisadb71_cadenderpais(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('db71_cadenderpais',10,$Idb71_cadenderpais,true,'text',$db_opcao," onchange='js_pesquisadb71_cadenderpais(false);'")
?>
       <?
db_input('db70_descricao',26,$Idb70_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb71_descricao?>">
       <?=@$Ldb71_descricao?>
    </td>
    <td>
<?
db_input('db71_descricao',40,$Idb71_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tdb71_sigla?>">
       <?=@$Ldb71_sigla?>
    </td>
    <td>
<?
db_input('db71_sigla',2,$Idb71_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  </table>

</fieldset>

</td></tr>
</table>

  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb71_cadenderpais(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadenderpais','func_cadenderpais.php?funcao_js=parent.js_mostracadenderpais1|db70_sequencial|db70_descricao','Pesquisa',true);
  }else{
     if(document.form1.db71_cadenderpais.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadenderpais','func_cadenderpais.php?pesquisa_chave='+document.form1.db71_cadenderpais.value+'&funcao_js=parent.js_mostracadenderpais','Pesquisa',false);
     }else{
       document.form1.db70_descricao.value = '';
     }
  }
}
function js_mostracadenderpais(chave,erro){
  document.form1.db70_descricao.value = chave;
  if(erro==true){
    document.form1.db71_cadenderpais.focus();
    document.form1.db71_cadenderpais.value = '';
  }
}
function js_mostracadenderpais1(chave1,chave2){
  document.form1.db71_cadenderpais.value = chave1;
  document.form1.db70_descricao.value = chave2;
  db_iframe_cadenderpais.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadenderestado','func_cadenderestado.php?funcao_js=parent.js_preenchepesquisa|db71_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadenderestado.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
