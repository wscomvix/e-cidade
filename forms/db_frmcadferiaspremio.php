<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *  junto com este programa; se n�o, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: pessoal
$clcadferiaspremio->rotulo->label();
$clselecao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="pes4_cadferiaspremio004.php"class="container">
  <fieldset >
    <legend>Cadastro de F�rias Pr�mio</legend>

    <table cellpadding="0" cellspacing="0" class="form-container">
      <tr>
        <td align="right" nowrap title="Tipo de Filtro">
          <strong>Tipo de Filtro:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
          $aTipoFiltro = array(1 => "Matr�cula", 2 => "Sele��o");
          db_select('tipo_filtro', $aTipoFiltro, true, $db_opcao, "onchange='js_tipoFiltro()'");
          ?>
        </td>
      </tr>
      <tr id="matricula">
        <td align="right" nowrap title="<?=@$Tr95_regist?>">
          <?
          db_ancora(@$Lr95_regist, "js_pesquisar95_regist(true);", $db_opcao);
          ?>
          &nbsp;&nbsp;
        </td>
        <td>
          <?
          db_input('r95_regist', 8, $Ir95_regist, true, 'text', $db_opcao, " onchange='js_pesquisar95_regist(false);'")
          ?>
          <?
          db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3);
          ?>
        </td>
      </tr>

      <tr id="selecao">
        <td align="right" nowrap title="<?=@$Tr44_selec?>">
          <?
          db_ancora(@$Lr44_selec, "js_pesquisar44_selec(true);", $db_opcao);
          ?>
          &nbsp;&nbsp;
        </td>
        <td>
          <?
          db_input('r44_selec', 8, $Ir44_selec, true, 'text', $db_opcao, " onchange='js_pesquisar44_selec(false);'")
          ?>
          <?
          db_input('r44_descr', 60, $Ir44_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>

  </fieldset>
  <input name="enviar" value="Enviar" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.r95_regist.focus();" >
</form>

<script type="text/javascript">

function js_pesquisar95_regist(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoalafasta.php?lativos=true&testarescisao=raf&afasta=true&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome|r95_dtafas|r95_dtreto&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.r95_regist.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoalafasta.php?lativos=true&testarescisao=raf&afasta=true&pesquisa_chave='+document.form1.r95_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostrapessoal(chave,chave2,chave3,erro) {

	mostrar = false;
	if(erro == false){
		mostrar = true;
	}

  document.form1.z01_nome.value = chave;
  if(mostrar == false){
	  if(erro != true){
	    document.form1.z01_nome.value   = '';
	  }
    document.form1.r95_regist.focus();
    document.form1.r95_regist.value = '';
  }
}

function js_mostrapessoal1(chave1,chave2,chave3,chave4) {

  db_iframe_rhpessoal.hide();
	mostrar = true;

  if(mostrar == true){
	  document.form1.r95_regist.value = chave1;
	  document.form1.z01_nome.value   = chave2;
  }else{
    document.form1.r95_regist.focus();
    document.form1.r95_regist.value = '';
    document.form1.z01_nome.value   = '';
  }
}

function js_compara_datas(dataafast,dataretor) {
  dataatual = "<?=date("Y-m-d",db_getsession("DB_datausu"))?>";

  afast = new Date(dataafast.substring(0,4),(dataafast.substring(5,7) - 1),dataafast.substring(8,10));
  retor = new Date(dataretor.substring(0,4),(dataretor.substring(5,7) - 1),dataretor.substring(8,10));
  atual = new Date(dataatual.substring(0,4),(dataatual.substring(5,7) - 1),dataatual.substring(8,10));

  if(atual > afast && atual < retor) {
  	alert("Funcion�rio afastado.");
  	return false;
  }
  return true;
}

function js_pesquisar44_selec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostraselecao1|r44_selec|r44_descr','Pesquisa',true);
  }else{
    if(document.form1.r44_selec.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostraselecao','Pesquisa',false);
    }else{
      document.form1.r44_descr.value = '';
    }
  }
}
function js_mostraselecao(chave,erro){
  document.form1.r44_descr.value = chave;
  if(erro == true){
    document.form1.r44_selec.focus();
    document.form1.r44_selec.value = '';
  }
}
function js_mostraselecao1(chave1,chave2){
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value = chave2;
  db_iframe_selecao.hide();
}

function js_tipoFiltro() {
  if($("tipo_filtro").value == 2) {
    $("matricula").hide();
    $("selecao").show();
    $("r95_regist").value = "";
    $("z01_nome").value = "";
  } else {
    $("matricula").show();
    $("selecao").hide();
    $("r44_selec").value = "";
    $("r44_descr").value = "";
  }
}
js_tipoFiltro();
</script>
