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

//MODULO: contabilidade
$clcontcearquivooid->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c11_id_usuario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc14_sequencial?>">
       <?=@$Lc14_sequencial?>
    </td>
    <td>
<?
db_input('c14_sequencial',10,$Ic14_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc14_contcearquivo?>">
       <?
       db_ancora(@$Lc14_contcearquivo,"js_pesquisac14_contcearquivo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('c14_contcearquivo',10,$Ic14_contcearquivo,true,'text',$db_opcao," onchange='js_pesquisac14_contcearquivo(false);'")
?>
       <?
db_input('c11_id_usuario',10,$Ic11_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc14_arquivo?>">
       <?=@$Lc14_arquivo?>
    </td>
    <td>
<?
db_input('c14_arquivo',1,$Ic14_arquivo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac14_contcearquivo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_contcearquivo','func_contcearquivo.php?funcao_js=parent.js_mostracontcearquivo1|c11_sequencial|c11_id_usuario','Pesquisa',true);
  }else{
     if(document.form1.c14_contcearquivo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_contcearquivo','func_contcearquivo.php?pesquisa_chave='+document.form1.c14_contcearquivo.value+'&funcao_js=parent.js_mostracontcearquivo','Pesquisa',false);
     }else{
       document.form1.c11_id_usuario.value = '';
     }
  }
}
function js_mostracontcearquivo(chave,erro){
  document.form1.c11_id_usuario.value = chave;
  if(erro==true){
    document.form1.c14_contcearquivo.focus();
    document.form1.c14_contcearquivo.value = '';
  }
}
function js_mostracontcearquivo1(chave1,chave2){
  document.form1.c14_contcearquivo.value = chave1;
  document.form1.c11_id_usuario.value = chave2;
  db_iframe_contcearquivo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_contcearquivooid','func_contcearquivooid.php?funcao_js=parent.js_preenchepesquisa|c14_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_contcearquivooid.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
