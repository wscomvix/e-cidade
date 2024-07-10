<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$oGet = db_utils::postMemory($_GET);

$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("l20_nroedital");
$clrotulo->label("l20_numero");
$clrotulo->label("l20_codtipocom");
$db_botao = true;

?>
<style type="text/css">
  .fieldsetinterno {
    border: 0px;
    border-top: 2px groove white;
    margin-top: 10px;

  }

  fieldset table tr>td {
    width: 180px;
    white-space: nowrap
  }

  .label-textarea {
    vertical-align: top;
  }

  #tr_inicio_depart table {
    width: 100%;
  }

  select#depart {
    width: 90%;
  }

  #obras,
  #origem_recurso {
    width: 100%;
  }

  div.header-container table#tablectnGridLotesPendentesheader tr td#col1.table_header.cell {
    width: 150px;
  }

  div.header-container table#tablectnGridLotesPendentesheader tr td#col2.table_header.cell {
    width: 400px;
  }
</style>
<form name="form1" method="post" action="" onsubmit="">
  <center>

    <table align=center style="margin-top:25px;">
      <tr>
        <td>

          <fieldset>
            <legend><strong>Editais</strong></legend>

            <fieldset style="border:0px;">

              <table border="0">
                <tr>
                    <td>
                        <b>Licita��o:</b>
                    </td>
                    <td>
                        <div style="display: flex;">
                            <div style="width: 50%;">
                                <?php db_input('codigolicitacao', 10, '', true, 'text', 3, ""); ?>
                            </div>

                            <?php if (!in_array($tipo_tribunal, array(100, 101, 102, 103, 104, 106))) : ?>

                            <div style="text-align: end; width: 50%;">

                            <strong>Edital:</strong>
                                <?php
                                db_input('numero_edital', 10, '', true, 'text', 3, "");
                                db_input('codigolicitacao', 10, '', true, 'hidden', 3);
                                db_input('naturezaobjeto', 10, '', true, 'hidden', 3);
                                ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                  <td nowrap title="Processo">
                    <b>Processo:</b>
                  </td>
                  <td>
                    <?php
                    db_input('edital', 10, '', true, 'text', 3, "");
                    db_input('objeto', 45, '', true, 'text', 3, "");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td title="Modalidade">
                    <b>Modalidade:</b>
                  </td>
                  <td>
                    <?php
                    db_input('codigoModalidade', 10, '', true, 'text', 3, "");
                    db_input('descr_tribunal', 45, '', true, 'text', 3, "");
                    ?>
                  </td>
                </tr>

                <?php if (!in_array($tipo_tribunal, array(100, 101, 102, 103, 104))) : ?>
                  <tr>
                    <td nowrap title="Origem do recurso">
                      <b>Origem do recurso:</b>
                    </td>
                    <td>
                      <?
                      $arr_tipo = array("0" => "Selecione", "1" => "1- Pr�prio", "2" => "2- Estadual", "3" => "3- Federal", "4" => "4- Pr�prio e Estadual", "5" => "5- Pr�prio e Federal", "9" => "9- Outros");
                      db_select("origem_recurso", $arr_tipo, true, 1);
                      ?>
                    </td>
                  </tr>
                <?php endif; ?>

                <tr id="tr_desc_recurso">
                  <td class="label-textarea" nowrap title="Descri��o do recurso">
                    <b>Descri��o do Recurso:</b>
                  </td>
                  <td>
                    <?
                    db_textarea('descricao_recurso', 4, 56, '', true, 'text', 1, "", '', '', 150);
                    ?>
                  </td>
                </tr>
                <tr id="email">
                  <td class="label-email" nowrap title="Descri��o do recurso">
                    <b>Email:</b>
                  </td>
                  <td>
                    <?php
                        db_input('email', 58, '', true, 'text', 1, "oninput = limparInput(this);","","","",200);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="label-textarea" nowrap title="Links da publica��o">
                    <b>Links da publica��o:</b>
                  </td>
                  <td>
                    <?
                    db_textarea('links', 4, 58, '', true, 'text', 1, "", '', '', 200);
                    ?>
                  </td>
                </tr>

                <tr id="td_obras" style="display: <?= $natureza_objeto == 1 ? '' : 'none' ?>;">
                  <td colspan="3">
                    <fieldset>
                      <legend>Obras e Servi�os</legend>
                      <table id="obras">
                        <tr>
                          <td>
                            <?
                            db_ancora('Dados Complementares:', 'js_liberaAncoraDados()', '', '', 'ancora_dados');
                            ?>
                          </td>
                          <td>
                            <?php
                            db_input('dados_complementares', 45, '', true, 'text', 3, "");
                            db_input('idObra', 10, '', true, 'hidden', $db_opcao);
                            ?>
                            <input type="button" value="Lan�ar" id="btnLancarDados" onclick="js_lancaDadosObra();" />
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3">
                            <div id="cntDBGrid">
                            </div>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>

                <tr>
                  <?php if (!$dataenviosicom) : ?>
                    <td nowrap title="Data de Envio">
                      <b>Data de Refer�ncia/Envio:</b>
                    </td>
                    <td>
                      <?= db_inputdata("data_referencia", '', '', '', true, 'text', 1); ?>
                    </td>
                  <?php endif; ?>
                </tr>
              </table>
            </fieldset>
          </fieldset>
        </td>
      </tr>
    </table>
  </center>
  <input name="<?= ($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir')) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'Alterar' : 'Excluir')) ?>" <?= ($db_botao == false ? 'disabled' : '') ?> onClick="js_salvarEdital();">
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa(<?= $dataenviosicom ?>);">
</form>

<script>
  iSequencial = '<?= $sequencial; ?>';
  codigoLicitacao = "<?= $codigolicitacao; ?>";
  origem_rec = "<?= $origem_recurso ?>";
  anoLicitacao = "<?= $anoLicitacao ?>";
  tipoJulgamento = "<?= $iTipoJulgamento ?>"

  function limparInput(input) {
    input.value = input.value.replace(/[;\*\\\:\"\']/gm, '');
  }

  function js_mostraDescricao(valor) {
    if (valor != 9) {
      document.getElementById('tr_desc_recurso').style.display = 'none';
    } else document.getElementById('tr_desc_recurso').style.display = '';
  }

  js_mostraDescricao(origem_rec);

  if (document.getElementById('origem_recurso')) {
    document.getElementById('origem_recurso').addEventListener('change', (e) => {
      js_mostraDescricao(e.target.value);
    });
  }

  function js_pesquisa(dataenviosicom = false) {
    if (!dataenviosicom) {
      js_OpenJanelaIframe('', 'db_iframe_liclicita', 'func_edital.php?funcao_js=parent.js_preenchepesquisa|l20_nroedital|l20_codigo|Status', 'Pesquisa', true, "0");
    }
  }

  function js_preenchepesquisa(nroedital, codigo, status) {
      js_buscaDadosLicitacao(codigo);
      db_iframe_liclicita.hide();
  }

  function js_buscaDadosLicitacao(valor) {
    let oParam = new Object();
    oParam.exec = 'findDadosLicitacao';
    oParam.iCodigoLicitacao = parseInt(valor);
    let oAjax = new Ajax.Request(
      'lic4_licitacao.RPC.php', {
        parameters: 'json=' + Object.toJSON(oParam),
        method: 'post',
        onComplete: js_retornoDadosLicitacao
      }

    );
  }

  function js_retornoDadosLicitacao(oAjax) {
    let oRetorno = eval('(' + oAjax.responseText + ')');
    let dadoslicitacao = oRetorno.dadosLicitacao;
    
    switch (dadoslicitacao.l20_cadinicial) {
      case '1':
        document.location.href = "lic4_editalinclusao.php?licitacao=" + dadoslicitacao.l20_codigo;
        break;
      case '2':
      case '3':
        document.location.href = "lic4_editalalteracao.php?licitacao=" + dadoslicitacao.l20_codigo;
        break;
    }
  }

  function js_salvarEdital() {
    let descricao = document.getElementById('descricao_recurso').value;
    let origem_recurso = document.getElementById('origem_recurso').value;
    let email = document.getElementById('email').value;

  }

  function js_exibeDadosCompl(iSequencial = null, incluir = true) {

    if (anoLicitacao >= 2021 && iSequencial == null && tipoJulgamento == '3') {
      oDadosLotesPendentes = new DBViewLotesPendentes('oDadosLotesPendentes');
      oDadosLotesPendentes.setLicitacao(codigoLicitacao);

      oDadosLotesPendentes.setCallBackDoubleClick((oDadosLinha) => {
        oDadosComplementares = new DBViewCadDadosComplementares('pri', 'oDadosComplementares', '', incluir,
          codigoLicitacao, "<?= $natureza_objeto ?>", oDadosLinha.sLote, oDadosLinha.sDescricao);
        oDadosComplementares.setObjetoRetorno($('idObra'));
        oDadosComplementares.setLicitacao(codigoLicitacao);
        oDadosComplementares.setCallBackFunction(() => {
          oDadosLotesPendentes.getLotesPendentes();
          js_buscaDadosComplementares();
        });
        oDadosComplementares.show();
      });

      oDadosLotesPendentes.show();

    } else {
      oDadosComplementares = new DBViewCadDadosComplementares('pri', 'oDadosComplementares', '', incluir,
        codigoLicitacao, "<?= $natureza_objeto ?>", iSequencial, '');
      oDadosComplementares.setObjetoRetorno($('idObra'));
      oDadosComplementares.setLicitacao(codigoLicitacao);

      if (iSequencial) {
        oDadosComplementares.preencheCampos(iSequencial);
      } else {
        oDadosComplementares.setCallBackFunction(() => {
          js_lancaDadosCompCallBack();
        });
      }

      oDadosComplementares.show();
    }

  }

  function js_lancaDadosCompCallBack() {
    let oEndereco = new Object();
    oEndereco.exec = 'findDadosObra';
    oEndereco.licitacao = codigoLicitacao;
    js_AjaxCgm(oEndereco, js_retornoDadosObra);

    function js_retornoDadosObra(oAjax) {
      js_removeObj('msgBox');
      let oRetorno = eval('(' + oAjax.responseText + ')');

      let sExpReg = new RegExp('\\\\n', 'g');

      if (oRetorno.dadoscomplementares == false) {

        let strMessageUsuario = "Falha ao ler os dados complementares cadastrados! ";
        js_messageBox(strMessageUsuario, '');
        return false;
      } else {
        js_PreencheObra(oRetorno.dadoscomplementares);
      }
    }
  }

  function js_AjaxCgm(oSend, jsRetorno) {
    let msgDiv = "Aguarde ...";
    js_divCarregando(msgDiv, 'msgBox');

    let sUrlRpc = "con4_endereco.RPC.php";

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oSend),
        method: 'post',
        onComplete: jsRetorno
      }

    );
  }

  function js_PreencheObra(aDados) {

    let iNumDados = aDados.length;
    for (let iInd = 0; iInd < iNumDados; iInd++) {
      let sEndereco = "";
      sEndereco += "Sequencial: " + aDados[iInd].db150_sequencial.urlDecode() + ", ";
      sEndereco += "Obra: " + aDados[iInd].db150_codobra.urlDecode() + ", ";
      sEndereco += aDados[iInd].descrmunicipio.urlDecode() + ", ";
      sEndereco += aDados[iInd].db150_bairro != '' ? aDados[iInd].db150_bairro.urlDecode() : '';

      $('dados_complementares').value = sEndereco;
    }
  }

  function js_init() {
    oDBGrid = new DBGrid("gridDocumentos");
    oDBGrid.nameInstance = "oDBGrid";
    oDBGrid.aWidths = new Array("10%", "15%", "59%", "16%");
    oDBGrid.setCellAlign(new Array("center", "center", "left", "center"));
    oDBGrid.setHeader(new Array("C�digo", "Sequencial", "Descri��o", "Op��es"));
    oDBGrid.show($('cntDBGrid'));
    oDBGrid.clearAll(true);
  }

  function js_lancaDadosObra() {

    let dadoscomplementares = $('dados_complementares').value;

    if (dadoscomplementares != '') {
      js_buscaDadosComplementares();
    } else {
      alert('Informe algum endere�o');
    }

  }

  js_init();

  function js_buscaDadosComplementares() {
    oDBGrid.clearAll(true);
    let sUrlRpc = "con4_endereco.RPC.php";
    let oParam = new Object();
    oParam.exec = 'findDadosObraLicitacao';
    oParam.codLicitacao = codigoLicitacao;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParam),
        asynchronous: false,
        method: 'post',
        onComplete: js_retornoDados
      }
    );
  }

  function js_retornoDados(oAjax) {

    let oRetorno = eval("(" + oAjax.responseText + ")");

    oRetorno.dadoscomplementares.forEach((dado) => {

      let descMunicipio = dado.descrmunicipio.urlDecode();
      let bairro = dado.db150_bairro.urlDecode();
      let linhas = oDBGrid.aRows.length;
      let descricaoLinha = `Obra: ${dado.db150_codobra}, `;

      /**
       * Se existir valor para esse atributo, os dados s�o de lote
       */
      if (dado.l04_descricao) {
        descricaoLinha += `${dado.l04_descricao.urlDecode()}, `;
      }

      descricaoLinha += `${descMunicipio.urlDecode()},`;
      descricaoLinha += bairro ? ` ${bairro}` : '';

      let aLinha = new Array();

      aLinha[0] = linhas + 1;
      aLinha[1] = dado.l04_descricao ? dado.db150_codobra : dado.db150_sequencial;
      aLinha[2] = descricaoLinha;
      aLinha[3] = "<input type='button' value='A' onclick='js_lancaDadosAlt(" + '"' + dado.db150_sequencial + '"' + ");'>" +
        "<input type='button' value='E' onclick='js_excluiDados(" + '"' + dado.db150_sequencial + '",' + '"' + dado.l04_descricao + '"' + ");'>";
      oDBGrid.addRow(aLinha);
    });

    oDBGrid.renderRows();
    $('dados_complementares').value = '';
    $('idObra').value = '';

  }

  function js_liberaAncoraDados() {
    let oParam = new Object();
    oParam.exec = 'getLotesPendentes';
    oParam.iLicitacao = codigoLicitacao;

    let oAjax = new Ajax.Request(
      "lic4_licitacao.RPC.php", {
        parameters: 'json=' + Object.toJSON(oParam),
        asynchronous: false,
        method: 'post',
        onComplete: (oAjax) => {
          let response = eval('(' + oAjax.responseText + ')');
          let aLotes = [];

          response.itens.map(item => {

            if (!aLotes.includes(item.descricao)) {
              aLotes.push(item.descricao);
            }

          });

          js_exibeDadosCompl();

        }
      }
    )

  }

  function js_lancaDadosAlt(valor) {
    $('idObra').value = valor;
    js_exibeDadosCompl(valor, false);
  }

  function js_excluiDados(valor, lote) {
    let mensagem = '';

    if (lote != 'undefined' && lote) {
      mensagem = `Deseja excluir o endere�o do ${lote.urlDecode()}?`;
    } else {
      mensagem = `Deseja excluir o endere�o do c�digo da obra ${valor}?`;
    }

    let resposta = window.confirm(mensagem);

    if (resposta) {
      let sUrlRpc = "con4_endereco.RPC.php";
      let oParam = new Object();
      oParam.exec = 'excluiDadosObra';
      oParam.sequencial = valor;
      oParam.licitacao = codigoLicitacao;

      let oAjax = new Ajax.Request(
        sUrlRpc, {
          parameters: 'json=' + Object.toJSON(oParam),
          method: 'post',
          onComplete: js_retornoExclusao
        }
      );
    }
  }

  function js_retornoExclusao(oAjax) {
    let obra = JSON.parse(oAjax.request.parameters.json);
    let resposta = eval("(" + oAjax.responseText + ")");

    alert(resposta.message.urlDecode());

    if (resposta.status == 1) {
      if (!resposta.lote) {

        for (let cont = 0; cont < oDBGrid.aRows.length; cont++) {
          let codigoobra = oDBGrid.aRows[cont].aCells[1].content;

          if (codigoobra == obra.sequencial) {
            let valores = [];
            valores.push(cont);
            oDBGrid.removeRow(valores);
          }
        }
        oDBGrid.renderRows();

      } else {
        js_buscaDadosComplementares();
      }
    }

  }

  let elemento = document.getElementById('links');
  elemento.addEventListener('keyup', (e) => {
    let valor = e.target.value;

    if (valor.includes(';')) {
      document.getElementById('links').value = valor.replace(/;/g, ',');
      alert('Caractere ponto e v�rgula n�o � permitido e ser� substitu�do por v�rgula.');
    }
    document.getElementById('links').value = valor.replace(/\r|\n/, ' ');
  });

</script>
