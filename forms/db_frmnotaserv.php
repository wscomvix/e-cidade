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

//MODULO: cemiterio
$clnotaserv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm09_i_codigo?>">
       <?=@$Lcm09_i_codigo?>
    </td>
    <td>
<?
db_input('cm09_i_codigo',10,$Icm09_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm09_i_sepultamento?>">
       <?
       db_ancora(@$Lcm09_i_sepultamento,"js_pesquisacm09_i_sepultamento(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm09_i_sepultamento',10,$Icm09_i_sepultamento,true,'text',$db_opcao," onchange='js_pesquisacm09_i_sepultamento(false);'")
?>
       <?
db_input('z01_nome',40,$Icm01_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm09_d_emissao?>">
       <?=@$Lcm09_d_emissao?>
    </td>
    <td>
<?
db_inputdata('cm09_d_emissao',@$cm09_d_emissao_dia,@$cm09_d_emissao_mes,@$cm09_d_emissao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacm09_i_sepultamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm09_i_sepultamento.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_sepultamentos','func_sepultamentos.php?pesquisa_chave='+document.form1.cm09_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostrasepultamentos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.cm09_i_sepultamento.focus();
    document.form1.cm09_i_sepultamento.value = '';
  }
}
function js_mostrasepultamentos1(chave1,chave2){
  document.form1.cm09_i_sepultamento.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_sepultamentos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_notaserv','func_notaserv.php?funcao_js=parent.js_preenchepesquisa|cm09_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_notaserv.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
