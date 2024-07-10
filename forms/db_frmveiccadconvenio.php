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

//MODULO: veiculos
$clveiccadconvenio->rotulo->label();

$clrotulo = new rotulocampo();

$clrotulo->label("ve18_numcgm");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap align="right" title="<?=@$Tve17_sequencial?>">
       <?=@$Lve17_sequencial?>
    </td>
    <td colspan="2">
<?
db_input('ve17_sequencial',10,$Ive17_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="<?=@$Tve17_descr?>">
       <?=@$Lve17_descr?>
    </td>
    <td colspan="2">
<?
db_input('ve17_descr',40,$Ive17_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="<?=@$Tve18_numcgm?>">
    <?
      db_ancora($Lve18_numcgm,"js_pesquisave18_numcgm(true);",$db_opcao);
    ?>
    </td>
    <td nowrap width="10%">
    <?
      db_input("ve18_numcgm",10,$Ive18_numcgm,true,"text",$db_opcao,"onChange='js_pesquisave18_numcgm(false);'");
    ?>
    </td>
    <td nowrap>
    <?
      db_input("z01_nome",40,0,true,"text",3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="<?=@$Tve17_obs?>">
       <?=@$Lve17_obs?>
    </td>
    <td colspan="2">
<?
db_textarea('ve17_obs',10,80,$Ive17_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisave18_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.ve18_numcgm.value != ""){
        js_OpenJanelaIframe('CurrentWindow.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.ve18_numcgm.value+'&funcao_js=parent.js_mostranumcgm','Pesquisa',false);
    } else {
      document.form1.z01_nome.value = "";
    }
  }
}

function js_mostranumcgm1(chave1,chave2){
  document.form1.ve18_numcgm.value = chave1;
  document.form1.z01_nome.value    = chave2;

  func_nome.hide();
}
function js_mostranumcgm(erro,chave){
  if (erro==false){
    document.form1.z01_nome.value = chave;
  }

  if(erro==true){
    document.form1.ve18_numcgm.focus();
    document.form1.ve18_numcgm.value = "";
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccadconvenio','func_veiccadconvenio.php?funcao_js=parent.js_preenchepesquisa|ve17_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veiccadconvenio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
