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

//MODULO: compras
include("dbforms/db_classesgenericas.php");
include("classes/db_habilitacaoforn_classe.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clhabilitacaoforn        = new cl_habilitacaoforn;
$clpcfornereprlegal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if ((isset($incluir) || isset($alterar) || isset($excluir)) && $clpcfornereprlegal->erro_status != 0) {
  unset($pc81_sequencia, $pc81_cgmresp, $z01_nome1, $pc81_datini_dia, $pc81_datini_mes, $pc81_datini_ano, $pc81_datfin_dia, $pc81_datfin_mes, $pc81_datfin_ano, $pc81_obs);
}
?>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?= @$Tpc81_sequencia ?>">
          <?= @$Lpc81_sequencia ?>
        </td>
        <td>
          <?
          db_input('pc81_sequencia', 8, $Ipc81_sequencia, true, 'text', 3, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tpc81_cgmforn ?>">
          <?
          db_ancora(@$Lpc81_cgmforn, "", 3);
          ?>
        </td>
        <td>
          <?
          db_input('pc81_cgmforn', 10, $Ipc81_cgmforn, true, 'text', 3)
          ?>
          <?
          db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tpc81_cgmresp ?>">
          <?
          db_ancora(@$Lpc81_cgmresp, "js_pesquisapc81_cgmresp(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?
          db_input('pc81_cgmresp', 10, $Ipc81_cgmresp, true, 'text', $db_opcao, " onchange='js_pesquisapc81_cgmresp(false);'")
          ?>
          <?
          db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '', "z01_nome1")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tpc81_datini ?>">
          <?= @$Lpc81_datini ?>
        </td>
        <td>
          <?
          db_inputdata('pc81_datini', @$pc81_datini_dia, @$pc81_datini_mes, @$pc81_datini_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tpc81_datfin ?>">
          <?= @$Lpc81_datfin ?>
        </td>
        <td>
          <?
          db_inputdata('pc81_datfin', @$pc81_datfin_dia, @$pc81_datfin_mes, @$pc81_datfin_ano, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?= @$Tpc81_obs ?>">
          <?= @$Lpc81_obs ?>
        </td>
        <td>
          <?
          db_textarea('pc81_obs', 3, 51, $Ipc81_obs, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?= @$Tpc81_tipopart ?>">
          <?= @$Lpc81_tipopart ?>
        </td>
        <td>
          <?
          $rsOrgaoReg = db_query("SELECT pc60_orgaoreg FROM pcforne WHERE pc60_numcgm = $pc81_cgmforn");
          $iOrgaoReg = db_utils::fieldsMemory($rsOrgaoReg, 0)->pc60_orgaoreg;

          if (intval($iOrgaoReg) == 4) {
            $aParticipacao = array(
              "0" => "Selecione",
              "3" => "MicroEmpreendedor Individual (MEI)"
            );
          } else {
            $aParticipacao = array(
              "0" => "Selecione",
              "1" => "Representante Legal",
              "2" => "Demais Membros",
              "3" => "MicroEmpreendedor Individual (MEI)",
              "4" => "Empres�rio Individual (EI)",
              "5" => "Empresa Individual de Responsabilidade Limitada (EIRELI)",
              "6" => "Sociedade LTDA Unipessoal (Lei 13.874/2019)"
            );
          }
          db_select("pc81_tipopart", $aParticipacao, true, $db_opcao);
          ?>
        </td>
      </tr>

    </table>
    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
    <?
    if ($db_opcao != 1) {
    ?>
      <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='com1_pcfornereprlegal001.php?pc81_cgmforn=<?= $pc81_cgmforn ?>'">
    <?
    }
    ?>
    <table>
      <tr>
        <td valign="top" align="center">
          <?
          $dbwhere = "";
          if (isset($pc81_cgmforn) && trim($pc81_cgmforn) != "") {
            $dbwhere .= " pc81_cgmforn = " . $pc81_cgmforn;
            $result = $clhabilitacaoforn->sql_record($clhabilitacaoforn->sql_query("", "*", "", "l206_fornecedor = $pc81_cgmforn"));
          }
          if (isset($pc81_sequencia) && trim($pc81_sequencia) != "") {
            $dbwhere .= " and pc81_sequencia <> " . $pc81_sequencia;
          }

          $chavepri = array("pc81_sequencia" => @$pc81_sequencia);
          $cliframe_alterar_excluir->chavepri = $chavepri;
          $cliframe_alterar_excluir->sql     = $clpcfornereprlegal->sql_query(null, "pc81_sequencia, pc81_datini, pc81_datfin, pc81_obs,b.z01_nome as pc81_cgmresp,
      case when pc81_tipopart = 1 then 'Representante Legal' when pc81_tipopart = 2 then 'Demais Membros' when pc81_tipopart = 3 then 'MicroEmpreendedor Individual(MEI)'
       when pc81_tipopart = 4 then 'Empres�rio Individual (EI)' when pc81_tipopart = 5 then 'Empresa Individual de Responsabilidade Limitada (EIRELI)' end as pc81_tipopart", "b.z01_nome", $dbwhere);
          $cliframe_alterar_excluir->campos  = "pc81_sequencia, pc81_cgmresp, pc81_datini, pc81_datfin,pc81_tipopart,pc81_obs";
          $cliframe_alterar_excluir->legenda = "REPRESENTANTES CADASTRADOS";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width = "700";
          if ($clhabilitacaoforn->numrows > 0) {
            $cliframe_alterar_excluir->opcoes  = (isset($db_opcaoal) ? 4 : 2);
          } else {
            $cliframe_alterar_excluir->opcoes  = (isset($db_opcaoal) ? 4 : 1);
          }

          $cliframe_alterar_excluir->iframe_alterar_excluir(1);
          ?>
        </td>
      </tr>
    </table>
  </center>
</form>
<script>
  function js_pesquisapc81_cgmresp(mostra) {
    let anousu = "<?= db_getsession('DB_anousu') ?>";

    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_pcfornereprlegal', 'db_iframe_cgm', 'func_nome.php?funcao_js=CurrentWindow.corpo.iframe_pcfornereprlegal.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true, 0);
    } else {
      if (document.form1.pc81_cgmresp.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_pcfornereprlegal', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + document.form1.pc81_cgmresp.value + '&funcao_js=CurrentWindow.corpo.iframe_pcfornereprlegal.js_mostracgm', 'Pesquisa', false);
      } else {
        document.form1.z01_nome1.value = '';
      }
    }
  }

  function js_mostracgm(erro, chave) {
    document.form1.z01_nome1.value = chave;
    if (erro == true) {
      document.form1.pc81_cgmresp.focus();
      document.form1.pc81_cgmresp.value = '';
    }
  }

  function js_mostracgm1(chave1, chave2) {
    document.form1.pc81_cgmresp.value = chave1;
    document.form1.z01_nome1.value = chave2;
    db_iframe_cgm.hide();
  }

  function js_pesquisa() {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_pcfornereprlegal', 'func_pcfornereprlegal.php?funcao_js=parent.js_preenchepesquisa|pc81_sequencia', 'Pesquisa', true);
  }

  function js_preenchepesquisa(chave) {
    db_iframe_pcfornereprlegal.hide();
    <?
    if ($db_opcao != 1) {
      echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
    }
    ?>
  }
</script>