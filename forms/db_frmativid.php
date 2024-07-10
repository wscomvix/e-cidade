<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

$clativid->rotulo->label();
$cl_classe->rotulo->label();
$clcnaeanalitica->rotulo->label();
$clcnae->rotulo->label();
$clrhcbo->rotulo->label();
?>

<script type="text/javascript">

  function js_valida(db_opcao) {

    if (db_opcao != 3) {

      classe = document.getElementById('q12_classe');

      if (document.form1.q03_descr.value.length == "") {

        alert('Campo Descri��o � obrigat�rio!');
        document.form1.q03_descr.focus();
        return false;
      }

      if (classe.value == '') {

        alert('Campo C�digo da Classe deve ser preenchido!');
        classe.focus();
        return false;
      }

      if (document.form1.q03_descr.value.length > 200) {

        alert('Campo Descri��o ultrapassou o tamanho m�ximo de caracteres permitido.\nTamanho M�ximo 200 caracteres!');
        document.form1.q03_descr.focus();
        return false;
      }
    }

    js_processaDados(db_opcao);
  }

  function js_calcula() {

    if (document.form1.q03_ativ.value == "") {

      alert("Preencha o C�digo da atividade!");
      return false;
    }

    return true;
  }

  function js_verifica() {

    if ($F('q03_ativ') == '') {
      $('db_opcao').disabled = true;
    }
  }

  function js_getCodigo() {

    iAtiv = $F('q03_ativ');

    $('calcular').disabled = true;
    $('db_opcao').disabled = true;

    if (iAtiv == '') {
      iAtiv = 1;
    }

    url = 'iss1_ativid004.php';
    oAjax = new Ajax.Request(url, {
      method    : 'get',
      parameters: 'q03_ativ=' + iAtiv,
      onComplete: js_setCodigo
    });
  }

  function js_setCodigo(oAjax) {

    iCodigo = eval("(" + oAjax.responseText + ")");

    $('q03_ativ').value = iCodigo.q03_ativ;
    $('calcular').disabled = false;
    $('db_opcao').disabled = false;
  }
</script>

<form name="form1" method="post" action="">
  <center>
    <fieldset style="width: 500px;">
      <legend><strong><?php echo $sAcao; ?> Cadastro de Atividades&nbsp;</strong></legend>
      <table border="0" align="center">
        <tr>
          <td nowrap title="<?php echo @$Tq03_ativ ?>" width="150">
            <strong>C�digo da Atividade:</strong>
          </td>
          <td>
            <?php
            db_input('q03_ativ', 10, $Iq03_ativ, TRUE, 'text', $db_opcaoc, "'onblur='js_verifica();js_getCodigo()'");

            if ($db_opcaoc != 3) {
              echo '<input name="calcular" type="button" id="calcular" value="Calcular" onclick="js_getCodigo()">';
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Pessoa:</strong>
          </td>
          <td>
            <?php
            $arrayvalores = array("S" => "Selecione", "F" => "F�sica", "J" => "Juridica");
            db_select("pessoa", $arrayvalores, 1, $db_opcaoselect, "onchange='js_limpaCampos()'");
            ?>
          </td>
        </tr>

        <tr id="oculta" style="display: ;">
          <td colspan="2">
            <div id='PJ' style='visibility:<?php echo $hiddenPJ ?>; position:<?php echo $positionPJ ?>;'>
              <table>
                <tr>
                  <td width="249"><? db_ancora(@$Lq71_estrutural, "js_pesquisa_cnae(true);", $db_opcaoselect); ?></td>
                  <td nowrap="nowrap">
                    <?php
                    db_input("q71_estrutural", 10, $Iq71_estrutural, TRUE, "text", $db_opcaoselect, "onchange='js_pesquisa_cnae(false);'");
                    db_input("q71_descr", 61, $Iq71_descr, TRUE, "text", 3);
                    db_input("q72_sequencial", 5, $Iq72_sequencial, TRUE, "hidden", 3);
                    ?>
                  </td>
                </tr>
              </table>
            </div>
            <div id='PF' style="visibility:<?php echo $hiddenPF ?>; position:<?php echo $positionPF ?>;">
              <table>
                <tr>
                  <td width="249">
                    <? db_ancora(@$Lrh70_estrutural, "js_pesquisa_cbo(true);", $db_opcaoselect); ?>
                  </td>
                  <td nowrap="nowrap">
                    <?php
                    db_input("rh70_estrutural", 10, $Irh70_estrutural, TRUE, "text", $db_opcaoselect, "onchange='js_pesquisa_cbo(false);'");
                    db_input("rh70_descr", 61, $Irh70_sequencial, TRUE, "text", 3);
                    db_input("rh70_sequencial", 5, $Irh70_sequencial, TRUE, "hidden", 3);
                    ?>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Tq03_descr ?>">
            <strong>Descri��o:</strong>
          </td>
          <td>
            <?php
            db_input('q03_descr', 75, $Iq03_descr, TRUE, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap title="<?php echo $Tq12_classe ?>">
            <strong><? db_ancora("C�digo da Classe:", "js_pesquisaq12_classe(true);", $db_opcao); ?></strong>
          </td>
          <td align="left" nowrap>
            <?php
            db_input("q12_classe", 10, $Iq12_classe, TRUE, "text", 3, "onchange='js_pesquisaq12_classe(false);'");
            db_input("q12_descr", 61, $Iq03_descr, TRUE, "text", 3);
            ?>
          </td>
        </tr>
        <tr id='servico' style="display: <?php echo $sServicoStyle ?> ">
          <td colspan="2">
            <fieldset style="margin-top: 10px;">
              <legend><strong>Lista de Servi�os </strong></legend>
              <div id="gridServicos">

              </div>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Tq03_atmemo ?>">
            <strong>Observa��o:</strong>
          </td>
          <td>
            <?php
            db_textarea('q03_atmemo', 0, 73, $Iq03_atmemo, TRUE, 'text', $db_opcao, "")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Tq03_limite ?>">
            <?php echo @$Lq03_limite ?>
          </td>
          <td>
            <?php
            db_inputdata('q03_limite', @$q03_limite_dia, @$q03_limite_mes, @$q03_limite_ano, TRUE, 'text', $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Tq03_horaini ?>">
            <?php echo @$Lq03_horaini ?>
          </td>
          <td colspan="2">
            <?
            db_input('q03_horaini', 10, $Iq03_horaini, TRUE, 'text', $db_opcao,
                "onchange='js_verifica_hora(this.value,this.name)';");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo @$Tq03_horafim ?>">
            <?php echo @$Lq03_horafim ?>
          </td>
          <td colspan="2">
            <?php
            db_input('q03_horafim', 10, $Iq03_horafim, TRUE, 'text', $db_opcao, "onchange='js_verifica_hora(this.value,this.name)';");
            ?>
          </td>
        </tr>
        <tr id='deducao' style="display: <?php echo $sServicoStyle ?> ">
          <td nowrap title="<?php echo @$Tq03_deducao ?>">
            <?php echo @$Lq03_deducao ?>
          </td>
          <td colspan="2">
            <? db_input('q03_deducao', 10, $Iq03_deducao, TRUE, 'checkbox', $db_opcao); ?>
          </td>
        </tr>
        <tr id='tributacao_municipio' style="display: <?php echo $sServicoStyle ?> ">
          <td nowrap title="<?php echo $Tq03_tributacao_municipio ?>">
            <?php echo $Lq03_tributacao_municipio ?>
          </td>
          <td colspan="2">
            <? db_input('q03_tributacao_municipio', 10, $Iq03_tributacao_municipio, TRUE, 'checkbox', $db_opcao); ?>
          </td>
        </tr>
      </table>
      <fieldset style="margin-top: 10px;">
        <legend><strong>Lista de Documentos </strong></legend>
        <div id="gridDocumentos">

        </div>
      </fieldset>
    </fieldset>
    <input name="db_opcao" onclick='return js_valida(<?php echo $db_opcao ?>)' type="button"
           id="db_opcao" <?php echo ($db_botao == FALSE ? "disabled" : "") ?>
           value="<?php echo ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>">
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    <? db_input("alteracbocnae", 5, '', TRUE, "hidden", 3); ?>
  </center>
</form>

<script type="text/javascript">
  oGrid = new DBGrid("gridDoc");
  oGridServ = new DBGrid("gridServ");
  js_pesquisaDocumentos();
  var lCalCiss;
  var db_opcao = <?php echo $db_opcao?>;
  var servico  = '<?php echo $servico ?>';
  if (db_opcao == 22 || db_opcao == 2) {
    lCalCiss = '<?php echo isset($q12_calciss);?>';
  } else {
    lCalCiss = false;
  }

  /**
   * Cria a Grid com os Documentos
   */
  function js_pesquisaDocumentos() {

    oGrid.nameInstance = 'oGrid';
    oGrid.setCellAlign(new Array('center', 'left'));
    oGrid.setCheckbox(0);
    oGrid.setHeader(new Array('C�digo', 'Tipo'));
    oGrid.show($('gridDocumentos'));
    oGrid.clearAll(true);

    var url = 'iss1_ativid.RPC.php';
    var oObject = new Object();

    oObject.exec = "buscaDocs";
    oObject.iCodAtividade = $F("q03_ativ");
    oObject.db_opcao = db_opcao;

    var objAjax = new Ajax.Request(url, {
          method    : 'post',
          parameters: 'json=' + Object.toJSON(oObject),
          onComplete: js_retornoBusca
        }
    );
  }

  function js_retornoBusca(oJson) {

    var oRetorno = eval("(" + oJson.responseText + ")");

    if (oRetorno.status == 2) {
      alert('Sem Documentos Retornados.');
    } else {

      for (var i = 0; i < oRetorno.aDocs.length; i++) {

        var bCheked = false;
        var aLinha = new Array();
        aLinha[0] = oRetorno.aDocs[i].db44_sequencial;
        aLinha[1] = oRetorno.aDocs[i].db44_descricao.urlDecode();

        if (oRetorno.aDocs[i].checked == 1) {
          bCheked = true;
        }

        oGrid.addRow(aLinha, false, false, bCheked);
      }

      oGrid.renderRows();
    }
    /**
     * Adicionado aqui para gerar a grid de servi�os depois de carregar o outro grid pra n�o dar conflito.
     */
    if(servico != 'SERVI�O'){
      oGridServ.clearAll(true);
    } else {
      js_pesquisaServicos();
    }
  }

  /**
   * Realiza processo de dos Dados do Formul�rio
   */
  function js_processaDados(db_opcao) {

    var sMsg = "Carregando...";

    js_divCarregando(sMsg, 'msgBox');

    var url     = 'iss1_ativid.RPC.php';
    var aDocs   = new Array();
    var aServs  = new Array();
    var oObject = new Object();

    oObject.iCodAtividade = $F("q03_ativ")
    oObject.sTipoPessoa   = $F("pessoa");
    oObject.sDescricao    = encodeURIComponent(tagString($F("q03_descr")));
    oObject.iClasse       = $F("q12_classe");
    oObject.sObservacao   = encodeURIComponent(tagString($F("q03_atmemo")));
    oObject.dtDataLimit   = $F("q03_limite");
    oObject.sHoraIni      = $F("q03_horaini");
    oObject.sHoraFim      = $F("q03_horafim");
    oObject.lDeducao      = $("q03_deducao").checked;
    oObject.lTributacaoMunicipio = $("q03_tributacao_municipio").checked;
    oObject.lCalCiss      = lCalCiss;
    oObject.db_opcao      = db_opcao;


    if (oObject.db_opcao != 3) {
      oObject.exec = "processaDados";
    } else {
      oObject.exec = "processaExclusao";
    }

    // Tipo de Pessoa N�o � Obrigat�rio
    if (oObject.sTipoPessoa == "F") {
      oObject.iCBO = $F("rh70_sequencial");
    } else if (oObject.sTipoPessoa == "J") {
      oObject.iCNAE = $F("q72_sequencial");
    }
    console.log(oGrid);
    console.log(oGridServ);
    aDocsGrid = oGrid.getSelection();
    aServsGrid = oGridServ.getSelection();

    for (var i = 0; i < aDocsGrid.length; i++) {

      var oDocs = new Object();

      oDocs.db44_sequencial = aDocsGrid[i][1];
      oDocs.db44_descricao = aDocsGrid[i][2];
      aDocs[i] = oDocs;
    }

    oObject.docs = aDocs;

    for (var i = 0; i < aServsGrid.length; i++) {

      var oServs = new Object();

      oServs.q126_sequencial = aServsGrid[i][1];
      oServs.db121_estrutural = aServsGrid[i][2];
      oServs.db121_descricao = aServsGrid[i][3];

      aServs[i] = oServs;
    }

    oObject.servs = aServs;

    var objAjax = new Ajax.Request(url, {
      method    : 'post',
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoProcessaDados
    });
  }

  function js_retornoProcessaDados(objAjax) {

    js_removeObj('msgBox');

    var oRetorno = eval("(" + objAjax.responseText + ")");
    oRetorno.message = oRetorno.message.urlDecode().replace(/\\n/g, "\n");

    if (oRetorno.status == 2) {
      alert(oRetorno.message);
    } else {

      alert(oRetorno.message);

      if (oRetorno.altera == 1) {
        window.location = "iss1_ativid002.php";
      } else {
        window.location = "iss1_ativid003.php";
      }
    }
  }

  function js_verifica_hora(valor, campo) {

    erro = 0;
    ms = "";
    hs = "";
    tam = "";
    pos = "";
    tam = valor.length;
    pos = valor.indexOf(":");

    if (pos != -1) {

      if (pos == 0 || pos > 2) {
        erro++;
      } else {

        if (pos == 1) {

          hs = "0" + valor.substr(0, 1);
          ms = valor.substr(pos + 1, 2);
        } else if (pos == 2) {

          hs = valor.substr(0, 2);
          ms = valor.substr(pos + 1, 2);
        }

        if (ms == "") {
          ms = "00";
        }
      }
    } else {

      if (tam >= 4) {

        hs = valor.substr(0, 2);
        ms = valor.substr(2, 2);
      } else if (tam == 3) {

        hs = "0" + valor.substr(0, 1);
        ms = valor.substr(1, 2);
      } else if (tam == 2) {

        hs = valor;
        ms = "00";
      } else if (tam == 1) {

        hs = "0" + valor;
        ms = "00";
      }
    }

    if (ms != "" && hs != "") {

      if (hs > 24 || hs < 0 || ms > 60 || ms < 0) {
        erro++
      } else {

        if (ms == 60) {
          ms = "59";
        }

        if (hs == 24) {
          hs = "00";
        }

        hora = hs;
        minu = ms;
      }
    }

    if (document.form1.q03_horafim.value != "" && erro == 0) {

      var botao = document.getElementById("db_opcao");
      var val_ini = document.form1.q03_horaini.value;
      var pos_ini = val_ini.indexOf(":");
      var hs_ini = "";

      if (pos_ini == 1) {
        hs_ini = "0" + val_ini.substr(0, 1);
      } else if (pos_ini == 2) {
        hs_ini = val_ini.substr(0, 2);
      }

      if (valor != "") {
        eval("document.form1." + campo + ".value='" + hora + ":" + minu + "';");
      }

      var val_fin = document.form1.q03_horafim.value;
      var pos_fin = val_fin.indexOf(":");
      var ms_fin = "";

      if (pos_fin == 1) {
        hs_fin = "0" + val_fin.substr(0, 1);
      } else if (pos_fin == 2) {
        hs_fin = val_fin.substr(0, 2);
      }
    }

    if (erro > 0) {

      if (erro < 99) {
        alert("Informe uma hora v�lida.");
      }
    }

    if (valor != "") {

      eval("document.form1." + campo + ".focus();");
      eval("document.form1." + campo + ".value='" + hora + ":" + minu + "';");
    }
  }

  function js_pesquisa() {

    <?php if (($db_opcao == 2 )  or ($db_opcao == 22) or ($db_opcao == 3) or ($db_opcao == 33)) { ?>
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_ativid', 'func_atividalt.php?funcao_js=parent.js_preenchepesquisa|q03_ativ|q12_descr', 'Pesquisa', true);
    <?php } else { ?>
    window.location = "iss1_ativid002.php";
    <?php } ?>
  }

  function js_preenchepesquisa(chave,chave2) {

    db_iframe_ativid.hide();
    <?php
    if($db_opcao!=1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&servico='+chave2;";
    }
    ?>
  }

  function js_pesquisa_cbo(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cbo', 'func_rhcboalt.php?funcao_js=parent.js_mostracbo|rh70_estrutural|rh70_descr|rh70_sequencial|tipo', 'Pesquisa', true);
    } else {

      if (document.form1.rh70_estrutural.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cbo', 'func_rhcboalt.php?pesquisa_chave=' + document.form1.rh70_estrutural.value + '&funcao_js=parent.js_mostracbo2', 'Pesquisa', false);
      } else {

        document.form1.rh70_estrutural.value = '';
        document.form1.rh70_sequencial.value = '';
        document.form1.rh70_descr.value      = '';
      }
    }
  }

  function js_mostracbo(estrutural, descr, sequencial, tipo) {

    if (tipo == 'Sintetico') {
      alert('Selecione uma atividade do tipo "Analitico".');
    } else {

      db_iframe_cbo.hide();
      document.form1.rh70_estrutural.value = estrutural;
      document.form1.rh70_descr.value      = descr;
      document.form1.rh70_sequencial.value = sequencial;

      <?php if ($db_opcao == 1) { ?>
      document.form1.q03_descr.value = descr;
      <?php } ?>
    }
  }

  function js_mostracbo2(estrutural, descr, sequencial, tipo, erro) {

    if (tipo == 'Sintetico') {

      alert('Selecione uma atividade do tipo "Analitico".');
      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_sequencial.value = '';
      document.form1.rh70_descr.value = '';
    } else {

      document.form1.rh70_estrutural.value = estrutural;
      document.form1.rh70_descr.value = descr;
      document.form1.rh70_sequencial.value = sequencial;

      <?php if ($db_opcao == 1) { ?>
      document.form1.q03_descr.value = descr;
      <?php } ?>
    }

    if (erro == true) {

      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_sequencial.value = '';
    }
  }

  function js_pesquisa_cnae(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cnae', 'func_cnae.php?funcao_js=parent.js_mostracnae|q71_estrutural|q71_descr|q72_sequencial', 'Pesquisa', true);
    } else {

      if (document.form1.q71_estrutural.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cnae', 'func_cnae.php?pesquisa_chave=' + document.form1.q71_estrutural.value + '&funcao_js=parent.js_mostracnae2', 'Pesquisa', false);
      } else {

        document.form1.q71_estrutural.value = '';
        document.form1.q72_sequencial.value = '';
        document.form1.q71_descr.value = '';
      }
    }
  }

  function js_mostracnae(estrutural, descr, sequencial) {

    if (sequencial == '') {
      alert('Selecione uma atividade do tipo "Analitico".');
    } else {

      db_iframe_cnae.hide();

      document.form1.q71_estrutural.value = estrutural;
      document.form1.q71_descr.value = descr;
      document.form1.q72_sequencial.value = sequencial;

      <?php if ($db_opcao == 1) { ?>
      document.form1.q03_descr.value = descr;
      <?php } ?>
    }
  }

  function js_mostracnae2(estrutural, descr, sequencial, erro) {

    if (erro == false) {

      if (sequencial == '') {
        alert('Selecione uma atividade do tipo "Analitico".');
      } else {

        document.form1.q71_estrutural.value = estrutural;
        document.form1.q71_descr.value = descr;
        document.form1.q72_sequencial.value = sequencial;

        <?php if ($db_opcao == 1) { ?>
        document.form1.q03_descr.value = descr;
        <?php } ?>
      }
    } else {

      document.form1.q71_estrutural.value = '';
      document.form1.q71_descr.value      = descr;
      document.form1.q03_descr.value      = descr;
      document.form1.q72_sequencial.value = '';
    }
  }

  function js_pesquisaq12_classe(mostra) {

    tipo_pessoa = document.getElementById('pessoa');
    filtrapessoa = '';

    if (tipo_pessoa.value == 'J') {
      filtrapessoa = 'filtrapessoa=J&'
    } else if (tipo_pessoa.value == 'F') {
      filtrapessoa = 'filtrapessoa=F&'
    }

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_ativid', 'func_classealt.php?' + filtrapessoa + 'funcao_js=parent.js_mostraclasse|q12_classe|q12_descr|q12_fisica|q12_calciss', 'Pesquisa', true);
    } else {

      if (document.form1.q12_classe.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_ativid', 'func_classealt.php?' + filtrapessoa + 'pesquisa_chave=' + document.form1.q12_classe.value + '&funcao_js=parent.js_mostraclasse2', 'Pesquisa', false);
      } else {
        document.form1.q12_classe.value = '';
      }
    }
  }

  function js_mostraclasse(chave_classe, chave1_classe, juridica, calciss) {

    document.form1.q12_classe.value = chave_classe;
    document.form1.q12_descr.value = chave1_classe;
    db_iframe_ativid.hide();
    lCalCiss = calciss;

    if (calciss == 't') {

      $("servico").style.display              = "";
      js_gridServicos();
      $("deducao").style.display              = "";
      $("tributacao_municipio").style.display = "";
    } else {

      $("servico").style.display              = "none";
      $("deducao").style.display              = "none";
      $("tributacao_municipio").style.display = "none";
      $("q127_sequencial").value              = "";
      $("db121_descricao").value              = "";
    }
  }

  function js_mostraclasse2(chave_classe, erro_classe, juridica) {

    document.form1.q12_descr.value = chave_classe;

    if (erro_classe == true) {
      document.form1.q12_classe.value = '';
    }

    if (juridica == 't') {
      document.form1.pessoa.value = 'J';
    } else if (juridica == 'f') {
      document.form1.pessoa.value = 'F';
    }
  }

  function js_pesquisa_tipcalc(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_issportetipo', 'func_tipcalciss.php?funcao_js=parent.js_mostraportetipo1|q81_codigo|q81_descr', 'Pesquisa', true);
    } else {

      if (document.form1.q80_tipcal.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_issportetipo', 'func_tipcalciss.php?pesquisa_chave=' + document.form1.q80_tipcal.value + '&funcao_js=parent.js_mostraportetipo', 'Pesquisa', false);
      } else {
        document.form1.q81_descr.value = '';
      }
    }
  }

  function js_mostraportetipo(chave, erro) {

    document.form1.q81_descr.value = chave;

    if (erro == true) {

      document.form1.q80_tipcal.focus();
      document.form1.q80_tipcal.value = '';
    }
  }

  function js_mostraportetipo1(chave1, chave2) {

    document.form1.q80_tipcal.value = chave1;
    document.form1.q81_descr.value = chave2;

    db_iframe_issportetipo.hide();
  }

  /**
   * Pesquisa Servi�o
   *
   */
  function js_pesquisa_servico(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_issgruposervico', 'func_issgruposervico.php?funcao_js=parent.js_mostraServico1|q126_sequencial|db121_descricao', 'Pesquisa', true);
    } else {

      if (document.form1.q127_sequencial.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_issgruposervico', 'func_issgruposervico.php?pesquisa_chave=' + document.form1.q127_sequencial.value + '&funcao_js=parent.js_mostraServico', 'Pesquisa', false);
      } else {
        document.form1.db121_descricao.value = '';
      }
    }
  }

  function js_mostraServico(chave, erro) {

    document.form1.db121_descricao.value = chave;

    if (erro == true) {

      document.form1.q127_sequencial.focus();
      document.form1.q127_sequencial.value = '';
    }
  }

  function js_mostraServico1(chave1, chave2) {

    document.form1.q127_sequencial.value = chave1;
    document.form1.db121_descricao.value = chave2;

    db_iframe_issgruposervico.hide();
  }

  function js_pessoa() {

    var tr = $("oculta");
    var pessoa = $F("pessoa");

    if (pessoa == 'J') {

      tr.style.display = "";

      document.getElementById('PJ').style.visibility = 'visible';
      document.getElementById('PJ').style.position = 'relative';
      document.getElementById('PF').style.visibility = 'hidden';
      document.getElementById('PF').style.position = 'absolute';

      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';
      document.form1.rh70_sequencial.value = '';
    } else if (pessoa == 'F') {

      tr.style.display = "";

      document.getElementById('PF').style.visibility = 'visible';
      document.getElementById('PF').style.position = 'relative';
      document.getElementById('PJ').style.visibility = 'hidden';
      document.getElementById('PJ').style.position = 'absolute';

      document.form1.q71_estrutural.value = '';
      document.form1.q71_descr.value = '';
      document.form1.q72_sequencial.value = '';
    } else {

      tr.style.display = "none";

      document.getElementById('PF').style.visibility = 'hidden';
      document.getElementById('PJ').style.visibility = 'hidden';
      document.getElementById('PF').style.position = 'absolute';
      document.getElementById('PJ').style.position = 'absolute';

      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';
      document.form1.rh70_sequencial.value = '';
      document.form1.q71_estrutural.value = '';
      document.form1.q71_descr.value = '';
      document.form1.q72_sequencial.value = '';
    }
  }

  /**
   * Limpa os campos dependendo do tipo de pessoa selecionado
   */
  function js_limpaCampos() {

    var tr = $("oculta");
    var pessoa = $F("pessoa");

    if (pessoa == 'J') {

      tr.style.display = "";

      document.getElementById('PJ').style.visibility = 'visible';
      document.getElementById('PJ').style.position = 'relative';
      document.getElementById('PF').style.visibility = 'hidden';
      document.getElementById('PF').style.position = 'absolute';

      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';
      document.form1.rh70_sequencial.value = '';
      document.form1.q12_classe.value = '';
      document.form1.q12_descr.value = '';
      document.form1.q127_sequencial.value = '';
      document.form1.db121_descricao.value = '';
    } else if (pessoa == 'F') {

      tr.style.display = "";

      document.getElementById('PF').style.visibility = 'visible';
      document.getElementById('PF').style.position = 'relative';
      document.getElementById('PJ').style.visibility = 'hidden';
      document.getElementById('PJ').style.position = 'absolute';

      document.form1.q71_estrutural.value = '';
      document.form1.q71_descr.value = '';
      document.form1.q72_sequencial.value = '';
      document.form1.q12_classe.value = '';
      document.form1.q12_descr.value = '';
      document.form1.q127_sequencial.value = '';
      document.form1.db121_descricao.value = '';
    } else {

      tr.style.display = "none";

      document.getElementById('PF').style.visibility = 'hidden';
      document.getElementById('PJ').style.visibility = 'hidden';
      document.getElementById('PF').style.position = 'absolute';
      document.getElementById('PJ').style.position = 'absolute';

      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value = '';
      document.form1.rh70_sequencial.value = '';
      document.form1.q71_estrutural.value = '';
      document.form1.q71_descr.value = '';
      document.form1.q72_sequencial.value = '';
      document.form1.q12_classe.value = '';
      document.form1.q12_descr.value = '';
      document.form1.q127_sequencial.value = '';
      document.form1.db121_descricao.value = '';

      $("servico").style.display              = "none";
      $("deducao").style.display              = "none";
      $("tributacao_municipio").style.display = "none";
    }
  }

  if (db_opcao == 1) {
    $("servico").style.display              = "none";
    $("deducao").style.display              = "none";
    $("tributacao_municipio").style.display = "none";
  }

  function js_gridServicos(){
    js_pesquisaServicos();

  }

  /**
   * Cria a Grid com os Servicos da atividade
   */
  function js_pesquisaServicos() {
    js_divCarregando('Carregando lista de servi�os', 'msgBox');
    oGridServ.nameInstance = 'oGridServ';
    oGridServ.setCellAlign(new Array('left', 'left','left','center'));
    oGridServ.setCheckbox(0);
    oGridServ.setHeader(new Array('C�digo', 'Estrutural', 'Descricao', 'Valor'));
    oGridServ.setCellWidth(new Array('10%','15%','60%','15%'));
    oGridServ.show($('gridServicos'));
    oGridServ.clearAll(true);

    var url = 'iss1_ativid.RPC.php';
    var oObject = new Object();

    oObject.exec = "buscaServicos";
    oObject.iCodAtividade = $F("q03_ativ");
    oObject.db_opcao = db_opcao;

    var objAjax = new Ajax.Request(url, {
          method    : 'post',
          parameters: 'json=' + Object.toJSON(oObject),
          onComplete: js_retornoBuscaServicos
        }
    );
  }

  function js_retornoBuscaServicos(oJson){i

    js_removeObj('msgBox');
    oGridServ.show($('gridServicos'));

    var oRetorno = eval("(" + oJson.responseText + ")");

    for (var i = 0; i < oRetorno.aServs.length; i++) {

      var bCheked = false;
      var aLinha = new Array();
      aLinha[0] = oRetorno.aServs[i].q126_sequencial;
      aLinha[1] = oRetorno.aServs[i].db121_estrutural;
      aLinha[2] = oRetorno.aServs[i].db121_descricao.urlDecode();
      aLinha[3] = oRetorno.aServs[i].q136_valor.urlDecode();

      if (oRetorno.aServs[i].checked == 1) {
        bCheked = true;
      }
      oGridServ.iRowCount = 0;
      oGridServ.addRow(aLinha, false, false, bCheked);
    }

    oGridServ.renderRows();



  }
</script>
