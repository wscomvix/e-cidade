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

//MODULO: orcamento
$clorcprojeto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o45_numlei");
$superaviloa = $suplementacaoparametro->o134_superavitloa;
$excessoarrecad= $suplementacaoparametro->o134_excessoarrecad;

?>
<fieldset>
<form name="form1" method="post" action="" onsubmit="js_validaSubmit();">
  <center>
    <table border=0 style="border:0px solid #999999; width:100%;margin:auto;">
      <tr>
        <td valign=top>

          <table border=0 style="border:0px solid #999999; width:60%;margin:auto;">
            <tr>
              <td nowrap title="<?= @$To39_anousu ?>"><?= @$Lo39_anousu ?></td>
              <td><? $o39_anousu = db_getsession('DB_anousu');
                  db_input('o39_anousu', 8, $Io39_anousu, true, 'text', 3, "") ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?= @$To39_codproj ?>"><?= @$Lo39_codproj ?></td>
              <td><? db_input('o39_codproj', 8, $Io39_codproj, true, 'text', 3, "") ?></td>
            </tr>
            <tr>
              <td nowrap title="<?= @$To39_numero ?>"><?= @$Lo39_numero ?></td>
              <td><? db_input('o39_numero', 10, $Io39_numero, true, 'text', $db_opcao, "") ?> </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$To39_data ?>"><?= @$Lo39_data ?> </td>
              <td><? db_inputdata('o39_data', @$o39_data_dia, @$o39_data_mes, @$o39_data_ano, true, 'text', $db_opcao, "") ?> </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$To39_descr ?>"><?= @$Lo39_descr ?></td>
              <td><? db_textarea('o39_descr', 0, 72, $Io39_descr, true, 'text', $db_opcao, "") ?></td>
            </tr>
            <?php if (db_getsession('DB_anousu') > 2021) { ?>
            <tr>
              <td  nowrap title="<?= @$To39_justi ?>"><?= @$Lo39_justi ?></td>
              <td><? db_textarea('o39_justi', 0, 72, $Io39_justi, true, 'text', $db_opcao, "onKeydown='js_validacaracter(this,event)' onKeyUp='js_validacaracter(this.value.length)' onchange='js_validacaracter(this.value.length)')", "","") ?></td>
            </tr>
            <tr>
              <td align="right" colspan = "5">
              <b>  Caracteres Digitados : </b>
              <input type="text" name="obsdig" id="obsdig" size="3" value="0" disabled>
              <b> - Limite 1000  </b>
            </td>
            </tr>
            <tr style="display: none;">
              <td nowrap title="<?= @$To39_texto ?>"><?= @$Lo39_texto ?></td>
              <td><? db_textarea('o39_texto', 0, 72, $Io39_texto, true, 'text', $db_opcao, "", "","",1000) ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td nowrap title="<?= @$To39_codlei ?>"><? db_ancora(@$Lo39_codlei, "js_pesquisao39_codlei(true);", $db_opcao); ?></td>
              <td>
                <? db_input('o39_codlei', 14, $Io39_codlei, true, 'text', $db_opcao, " onchange='js_pesquisao39_codlei(false);'") ?>
                <? db_input('o45_numlei', 40, $Io45_numlei, true, 'text', 3, '', '', '', 'width: 100%')     ?>
                <input type="hidden" id="iTipoLei" value="" name="iTipoLei">
                <input type="hidden" id="bModalidadeAplic" value="<?= $bModalidadeAplic ?>" name="bModalidadeAplic">
                <input type="hidden" id="superaviloa" value="<?= $superaviloa ?>" name="superaviloa">
                <input type="hidden" id="excessoarrecad" value="<?= $excessoarrecad ?>" name="excessoarrecad">
              </td>
            </tr>

            <?php
            //echo $clorcsuplem->sql_query(null,"distinct o48_tiposup","o46_codsup","orcprojeto.o39_codproj = {$o39_codproj}" );
            $res = $clorcsuplem->sql_record($clorcsuplem->sql_query(null, "distinct o48_tiposup", "", "orcprojeto.o39_codproj = {$o39_codproj}"));
            if ($clorcsuplem->numrows == 1) {
              $edita = 3;
            } else {
              $edita = 1;
            }
            ?>
            <tr>
              <td><b>Tipo :</b></td>
              <td> <?

                    //$aWhere=array();
                    //array_push($aWhere, "1001 ","1002","1003","1004","1017","1016","1014","1015");
                    $sSqlTipoSuplem = $clorcsuplemtipo->sql_query("", "o48_tiposup as o46_tiposup,o48_descr", "o48_tiposup"/*,"o48_tiposup in (".implode(",", $aWhere).")"*/);
                    $rtipo          = $clorcsuplemtipo->sql_record($sSqlTipoSuplem);                   
                    db_fieldsmemory($rtipo, 0);

                    $sSqlTipoprojeto = $clorcprojeto->sql_query($o39_codproj, "o39_tipoproj"); 
                    $ptipo          = $clorcprojeto->sql_record($sSqlTipoprojeto);                   
                    db_fieldsmemory($ptipo, 0);
                   
                    if ($o39_tiposuplementacao == "") {
                      $o39_tiposuplementacao = $o46_tiposup;
                    }
                    db_selectrecord("o39_tiposuplementacao", $rtipo, false, $db_opcao != 3 ? $edita : $db_opcao, "", "", "", "Selecione", "js_validaTipoSup();");
                    
                    ?>
                    
              </td>
            </tr>


            <tr>
              <td nowrap title="<?= @$To39_usalimite ?>">
                <?= @$Lo39_usalimite ?>
              </td>
              <td>
                <?
                $x = array('f' => 'N�o', 't' => 'Sim');
                db_select('o39_usalimite', $x, true, 3, ""); ?>
                <script>
                  document.getElementById('o39_usalimite_select_descr').setAttribute("style", "width: 15%; background-color:#DEB887;");
                </script>
              </td>
            </tr>
          </table>
        </td>

      </tr>
      <tr valign=botton>
        <td colspan=1 align=center>
          <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
          <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
        </td>
      </tr>


    </table>

  </center>
</form>
</fieldset>
<script>
var superaviloa = document.getElementById('superaviloa').value;
var excessoarrecad = document.getElementById('excessoarrecad').value;
function js_incrementaCaracDig(contcaracteres){
  document.getElementById('obsdig').value = contcaracteres;
  return contcaracteres;
}
function js_validacaracter() {
    let texto = document.getElementById("o39_justi").value;
    if(texto){
      var er = /[^a-z0-9]/gi;
      texto = texto.replace(er, '')
      contcaracteres = texto.length;
      return js_incrementaCaracDig(contcaracteres);
    }
    return js_incrementaCaracDig(0);    
  }

  document.getElementById('o39_tiposuplementacao').style.width = "15%";
  document.getElementById('o45_numlei').style.width = "84%";
  document.getElementById('o39_codlei').style.width = "15%";

  function js_validaTipoSup() {

    let iTipoLei = document.getElementById('iTipoLei').value;
    let iTipoSup = document.getElementById('o39_tiposuplementacao').value;

    if(!iTipoLei){
      iTipoLei = "<?php print $o39_tipoproj; ?>";
    }
       
    if (iTipoLei == 1) {

      let aTipoSupPermitidosLOA = ['Selecione', '1001', '1002', '1003', '1004', '1011', '1017','1018', '1019', '1020', '1021', '1022', '2026'];
      js_validaTipoSupArray(aTipoSupPermitidosLOA, iTipoSup, iTipoLei);

    }

    if (iTipoLei == 2) {

      let aTipoSupPermitidosLDO = ['Selecione', '1017', '1014', '1015', '1016', '1020', '1021', '1022', '2026'];
      js_validaTipoSupArray(aTipoSupPermitidosLDO, iTipoSup, iTipoLei);

    }

    if (iTipoLei == 3) {
      
      let aTipoSupPermitidosLAO = ['Selecione', '1006', '1007', '1008', '1009', '1010', '1012', '1013', '1023', '1024', '1025', '1014', '1015', '1016', '1026', '1027', '1028', '1029', '2026'];
      js_validaTipoSupArray(aTipoSupPermitidosLAO, iTipoSup, iTipoLei);

    }

    if (iTipoLei == 3){
        document.getElementById('o39_usalimite_select_descr').value = 'N�o';
    }else{
        if ((iTipoSup == 1003 && superaviloa == 'f') || iTipoSup == 1004 && excessoarrecad == 'f'){
          document.getElementById('o39_usalimite').value = 'f';
          document.getElementById('o39_usalimite_select_descr').value = 'N�o';
        } else{
            if (iTipoSup == 1001  || iTipoSup == 1002 || iTipoSup == 1003  || iTipoSup == 1004  ){
              document.getElementById('o39_usalimite').value = 't';
              document.getElementById('o39_usalimite_select_descr').value = 'Sim';
            } else {
              document.getElementById('o39_usalimite').value = 'f';
              document.getElementById('o39_usalimite_select_descr').value = 'N�o';
            }
        }    
    }

    let aTiposModalidade = ['1020', '1021', '1022'];
    let bModalidadeAplic = document.getElementById('bModalidadeAplic').value;

    if ((aTiposModalidade.indexOf(iTipoSup) > -1) && bModalidadeAplic == 'f') {

      alert("Esse tipo de suplementa��o somente pode ser utilizado quando or�amento � aprovado por modalidade de aplica��o!");
      document.getElementById('o39_tiposuplementacao').options[0].selected = true;
      document.getElementById('o39_tiposuplementacao').onchange();
      return false;

    }

    if ((aTiposModalidade.indexOf(iTipoSup) > -1) && bModalidadeAplic == '' || bModalidadeAplic == null) {

        alert("Para utilizar esse tipo de suplementa��o � necess�rio definir no cadastro das leis se o or�amento foi aprovado por modalidade de aplica��o!");
        document.getElementById('o39_tiposuplementacao').options[0].selected = true;
        document.getElementById('o39_tiposuplementacao').onchange();
        return false;

    }
       

  }

  function js_validaTipoSupArray(aTipoSup = [], iTipoSup = 0, iTipoLei = 0) {

    let aDescTipoSup = ['1 - LOA', '2 - LDO', '3 - LAO'];

    if (aTipoSup && iTipoSup) {

      if (aTipoSup.indexOf(iTipoSup) < 0) {

        let eSelect = document.getElementById("o39_tiposuplementacaodescr");
        let sTipoSup = iTipoSup + ' ' + eSelect.options[eSelect.selectedIndex].text;
        alert('Tipo de suplementa��o ' + sTipoSup + ' n�o permitido para o Tipo da Lei: ' + aDescTipoSup[(iTipoLei - 1)]);

        document.getElementById('o39_tiposuplementacao').options[0].selected = true;
        document.getElementById('o39_tiposuplementacao').onchange();
        return false;

      }

    }

  }

  function js_validaSubmit() {

    let contcaracteres = js_validacaracter();

    if ( contcaracteres < 100) {
      alert("O campo Justificativa deve ter no m�nimo 100 caracteres");
      event.preventDefault();
    }

     
    if (document.form1.o39_tiposuplementacao.value == 'Selecione') {
      alert("Informe o Tipo de Suplementa��o.");
      event.preventDefault();
    }


  }

  function js_pesquisao39_codlei(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_projeto',
        'db_iframe_orclei',
        'func_orclei.php?funcao_js=parent.js_mostraorclei1|o45_codlei|o45_numlei|o45_tipolei&leimanual=1',
        'Pesquisa', true);
    } else {
      if (document.form1.o39_codlei.value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_projeto',
          'db_iframe_orclei',
          'func_orclei.php?pesquisa_chave=' +
          document.form1.o39_codlei.value +
          '&funcao_js=parent.js_mostraorclei&bTipoLei=true', 'Pesquisa', false);
      } else {
        document.form1.o45_numlei.value = '';
        document.form1.iTipoLei.value = '';
      }
    }
  }

  function js_mostraorclei(chave, chave1, erro) {
    document.form1.o45_numlei.value = chave;
    document.form1.iTipoLei.value = chave1.substr(0, 1);
    js_validaTipoSup();
    if (erro == true) {
      document.form1.o39_codlei.focus();
      document.form1.o39_codlei.value = '';
    }
  }

  function js_mostraorclei1(chave1, chave2, chave3) {
    document.form1.o39_codlei.value = chave1;
    document.form1.o45_numlei.value = chave2;
    document.form1.iTipoLei.value = chave3.substr(0, 1);
    js_validaTipoSup();
    db_iframe_orclei.hide();
  }

  function js_pesquisa() {
    <?
    //  if($db_opcao==22){
    echo "js_OpenJanelaIframe('top.corpo.iframe_projeto','db_iframe_orcprojeto','func_orcprojeto001.php?funcao_js=parent.js_preenchepesquisa|o39_codproj','Pesquisa',true);";
    // }else {
    //    echo "js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto.php?funcao_js=parent.js_preenchepesquisa|o39_codproj','Pesquisa',true);";
    // }
    ?>
  }

  function js_preenchepesquisa(chave) {
    db_iframe_orcprojeto.hide();
    <?
    if ($db_opcao != 1) {
      echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
    }
    ?>
  }
  <? if ($db_opcao == 1) {
    echo "	document.getElementById('o39_tiposuplementacao').options[0].selected = true;
			document.getElementById('o39_tiposuplementacao').onchange();
			document.getElementById('o39_usalimite_select_descr').value = '';";
  }
  ?>
</script>
