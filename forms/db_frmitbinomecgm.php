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

//MODULO: itbi
$clitbinomecgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it03_seq");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit21_sequencial?>">
       <?=@$Lit21_sequencial?>
    </td>
    <td>
<?
db_input('it21_sequencial',10,$Iit21_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit21_itbinome?>">
       <?
       db_ancora(@$Lit21_itbinome,"js_pesquisait21_itbinome(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('it21_itbinome',10,$Iit21_itbinome,true,'text',$db_opcao," onchange='js_pesquisait21_itbinome(false);'")
?>
       <?
db_input('it03_seq',10,$Iit03_seq,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit21_numcgm?>">
       <?
       db_ancora(@$Lit21_numcgm,"js_pesquisait21_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('it21_numcgm',10,$Iit21_numcgm,true,'text',$db_opcao," onchange='js_pesquisait21_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisait21_itbinome(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbinome','func_itbinome.php?funcao_js=parent.js_mostraitbinome1|it03_seq|it03_seq','Pesquisa',true);
  }else{
     if(document.form1.it21_itbinome.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbinome','func_itbinome.php?pesquisa_chave='+document.form1.it21_itbinome.value+'&funcao_js=parent.js_mostraitbinome','Pesquisa',false);
     }else{
       document.form1.it03_seq.value = '';
     }
  }
}
function js_mostraitbinome(chave,erro){
  document.form1.it03_seq.value = chave;
  if(erro==true){
    document.form1.it21_itbinome.focus();
    document.form1.it21_itbinome.value = '';
  }
}
function js_mostraitbinome1(chave1,chave2){
  document.form1.it21_itbinome.value = chave1;
  document.form1.it03_seq.value = chave2;
  db_iframe_itbinome.hide();
}
function js_pesquisait21_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.it21_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.it21_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.it21_numcgm.focus();
    document.form1.it21_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.it21_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbinomecgm','func_itbinomecgm.php?funcao_js=parent.js_preenchepesquisa|it21_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbinomecgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
