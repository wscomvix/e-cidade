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
$clcondataconf->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc99_anousu?>">
        <br>
       <?=@$Lc99_anousu?>
        <?
        $c99_anousu = db_getsession('DB_anousu');
        db_input('c99_anousu',4,$Ic99_anousu,true,'text',3,"")
        ?>
    </td>
    <td>

    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc99_instit?>">
       <?
       db_ancora(@$Lc99_instit,"js_pesquisac99_instit(true);",3);
       ?>

       <?
       $c99_instit = db_getsession('DB_instit');
       db_input('c99_instit',2,$Ic99_instit,true,'text',3," onchange='js_pesquisac99_instit(false);'")
       ?>
    </td>
    <td>

    </td>
  </tr>

    <!--    Label Encerramento do per�odo cont�bil  -->
    <tr>
        <td nowrap title="<?=@$Tc99_encpercont?>">
            <br>
            <strong><?=@$Lc99_encpercont?></strong>
        </td>
    </tr>

    <!--    Input encerramento do per�odo cont�bil  -->
  <tr>
    <td nowrap title="<?=@$Tc99_data?>">
       <?=@$Lc99_data?>
       <?
       db_inputdata('c99_data',@$c99_data_dia,@$c99_data_mes,@$c99_data_ano,true,'text',$db_opcao,"")
       ?>

        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" onClick='js_validac99_data();' type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
    <td>

      <td style="padding-left:6px;"></td>
    </td>
  </tr>

<!--    Label Encerramento do per�odo patrimonial   -->
    <tr>
        <td nowrap title="<?=@$Tc99_encperpat?>">
            <br>
            <strong><?=@$Lc99_encperpat?></strong>
        </td>
    </tr>

<!--    Input Encerramento do per�odo patrimonial   -->
    <tr>
        <td nowrap title="<?=@$Tc99_data?>">
            <?=@$Lc99_data?>
            <?
            db_inputdata('c99_datapat',@$c99_datapat_dia,@$c99_datapat_mes,@$c99_datapat_ano,true,'text',$db_opcao,"","","style='background-color:#E6E4F1'")
            ?>
            <input id="c99_alterar2" name="c99_alterar2" type="submit" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
        </td>
        <td>

        <td style="padding-left:6px;"></td>
        </td>
    </tr>


  </table>
  </center>

<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >-->
</form>
<script>
function js_pesquisac99_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.c99_instit.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.c99_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = '';
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave;
  if(erro==true){
    document.form1.c99_instit.focus();
    document.form1.c99_instit.value = '';
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.c99_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_condataconf','func_condataconf.php?funcao_js=parent.js_preenchepesquisa|c99_anousu|c99_instit','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_condataconf.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
function js_datapat(){
    document.getElementById("c99_alterar2").setAttribute("name", 2);
    alert(document.getElementById("c99_alterar2").getAttribute("name"));
    document.form1.submit();
}
function js_validac99_data()
{
  var sData = document.getElementById("c99_data").value.split("/");
  document.getElementById("c99_data_dia").value = sData[0];
  document.getElementById("c99_data_mes").value = sData[1];
  document.getElementById("c99_data_ano").value = sData[2];
}
</script>
