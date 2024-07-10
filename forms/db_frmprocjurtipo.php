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

//MODULO: arrecadacao
$clprocjurtipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v61_descricao");
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<legend align="center" >
  <b>Cadastro Tipo de Processo</b>
</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv66_procjurtiporegra?>">
      <?=@$Lv66_procjurtiporegra?>
    </td>
    <td>
      <?
		db_input('v66_sequencial',10,$Iv66_sequencial,true,'hidden',$db_opcao,"");
      	$rsTipoRegra = $clprocjurtiporegra->sql_record($clprocjurtiporegra->sql_query_file());
      	db_selectrecord("v66_procjurtiporegra",$rsTipoRegra,true,$db_opcao,"style='width:260px;'","","","","",1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv66_descr?>">
      <?=@$Lv66_descr?>
    </td>
    <td>
	  <?
		db_input('v66_descr',40,$Iv66_descr,true,'text',$db_opcao,"");
	  ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>


function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procjurtipo','func_procjurtipo.php?funcao_js=parent.js_preenchepesquisa|v66_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procjurtipo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
