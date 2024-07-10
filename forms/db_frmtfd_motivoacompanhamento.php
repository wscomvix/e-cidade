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

//MODULO: TFD
$cltfd_motivoacompanhamento->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ttf08_i_codigo?>">
       <?=@$Ltf08_i_codigo?>
    </td>
    <td>
      <?
      db_input('tf08_i_codigo',10,$Itf08_i_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf08_c_descr?>">
      <?=@$Ltf08_c_descr?>
    </td>
    <td>
      <?
      db_input('tf08_c_descr',50,$Itf08_c_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf08_d_validade?>">
      <?=@$Ltf08_d_validade?>
    </td>
    <td>
      <?
      db_inputdata('tf08_d_validade',@$tf08_d_validade_dia,@$tf08_d_validade_mes,@$tf08_d_validade_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao == 1 ? 'disabled' : '')?>>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_tfd_motivoacompanhamento','func_tfd_motivoacompanhamento.php?funcao_js=parent.js_preenchepesquisa|tf08_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_motivoacompanhamento.hide();
  <?
  if($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>