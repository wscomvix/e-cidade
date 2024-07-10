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
$clconhist->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc50_codhist?>">
       <?=@$Lc50_codhist?>
    </td>
    <td>
<?
db_input('c50_codhist',4,$Ic50_codhist,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc50_descr?>">
       <?=@$Lc50_descr?>
    </td>
    <td>
<?
db_input('c50_descr',40,$Ic50_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc50_compl?>">
       <?=@$Lc50_compl?>
    </td>
    <td>
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('c50_compl',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc50_ativo?>">
       <?=@$Lc50_ativo?>
    </td>
    <td>
<?
$x = array("t"=>"SIM","f"=>"NAO");
db_select('c50_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc50_descrcompl?>">
       <?=@$Lc50_descrcompl?>
    </td>
    <td>
    <?
    db_textarea('c50_descrcompl',7,50,$Ic50_descrcompl,true,'text',$db_opcao,"","","",500)
    ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conhist','func_conhist.php?funcao_js=parent.js_preenchepesquisa|c50_codhist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conhist.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>