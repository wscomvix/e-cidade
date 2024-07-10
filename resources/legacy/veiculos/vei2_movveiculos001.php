<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veiccaddestino_classe.php");
include("classes/db_veicretirada_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clveicretirada = new cl_veicretirada;
$clrotulo->label('ve01_codigo');
$clrotulo->label('ve01_placa');
$clrotulo->label('ve40_veiccadcentral');
$clrotulo->label('descrdepto');
$db_opcao = 1;
$clveicretirada->rotulo->label();
$clrotulo->label("z01_nome");
?>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script>
        function js_emite() {

            var iVeiculo = $F('ve01_codigo');
            var sPlaca = $F('ve01_placa');
            var iCentral = $F('ve40_veiccadcentral');
            var sDataIni = $F('data_inicial_ano') + '-' + $F('data_inicial_mes') + '-' + $F('data_inicial_dia');
            var sDatafim = $F('data_final_ano') + '-' + $F('data_final_mes') + '-' + $F('data_final_dia');
            var sDestino = $F('destino');
            var iMotorista = $F('ve60_veicmotoristas');
            var sDescDes = document.getElementById('destionodescr');
            var sUrl = 'iVeiculo=' + iVeiculo + '&iMotorista=' + iMotorista +'&sPlaca=' + sPlaca + '&iCentral=' + iCentral + '&sDataIni=' + sDataIni + '&sDataFim=' + sDatafim + '&sDestino=' + sDestino + '&sDescDes=' + sDescDes;
            
            jan = window.open('vei2_movveiculos002.php?' + sUrl, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
            jan.moveTo(0, 0);

        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCCC" style="margin-top: 25px">
    <form name="form1" method="post">
        <center>
            <table>
                <tr>
                    <td>
                        <fieldset>
                            <legend>
                                <b>Movimenta��o de Ve�culos</b>
                            </legend>
                            <table>
                                <tr>
                                    <td nowrap title="<?= @$Tve01_codigo ?>">
                                        <?
                                        db_ancora(@$Lve01_codigo, "js_pesquisave01_codigo(true);", 4);
                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('ve01_codigo', 10, $Ive01_codigo, true, 'text', 4, " onchange='js_pesquisave01_codigo(false);'")
                                        ?>
                                        <?
                                        db_input('ve01_placadescr', 10, '0', true, 'text', 3, '')
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                <td nowrap title="<?= @$Tve60_veicmotoristas ?>">
                  <?
                  db_ancora(@$Lve60_veicmotoristas, "js_pesquisave60_veicmotoristas(true);", $db_opcao);
                  ?>
                </td>
                <td>
                  <?
                  db_input(
                    've60_veicmotoristas',
                    10,
                    $Ive60_veicmotoristas,
                    true,
                    'text',
                    $db_opcao,
                    " onchange='js_pesquisave60_veicmotoristas(false);'"
                  );
                  if (isset($ve50_integrapessoal) && trim(@$ve50_integrapessoal) != "") {
                    if ($ve50_integrapessoal == 1) {
                      $pessoal = "true";
                    }

                    if ($ve50_integrapessoal == 2) {
                      $pessoal = "false";
                    }
                  }
                  db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '')
                  ?>
                </td>
              </tr>
                                <tr>
                                    <td>
                                        <?= @$Lve01_placa ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('ve01_placa', 10, $Ive01_placa, true, 'text', 4, '');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Tve40_veiccadcentral ?>">
                                        <?
                                        db_ancora(@$Lve40_veiccadcentral, "js_pesquisacentral(true);", $db_opcao);
                                        ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input(
                                            've40_veiccadcentral',
                                            10,
                                            $Ive40_veiccadcentral,
                                            true,
                                            'text',
                                            $db_opcao,
                                            " onchange='js_pesquisacentral(false);'"
                                        )
                                        ?>
                                        <?
                                        db_input('descrdepto', 40, $Idescrdepto, true, 'text', 3, '')
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap><b>Per�odo:</b></td>
                                    <td>
                                        <?
                                        db_inputdata("data_inicial", "", "", "", true, "text", 4);
                                        ?>&nbsp;<b> at�</b>&nbsp;
                                        <?
                                        db_inputdata("data_final", "", "", "", true, "text", 4);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap><b>Destino:</b></td>
                                    <td>
                                        <?
                                        $clveiccaddestino = new cl_veiccaddestino;
                                        $result_destinos = $clveiccaddestino->sql_record($clveiccaddestino->sql_query_file(null, "*"));
                                        db_selectrecord("destino", $result_destinos, true, 1, "", "", "", "0", "");
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
                    </td>
                </tr>
            </table>
    </form>
    </center>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<script type="text/javascript">
    /**
     * Valida codigo do veiculo antes de abrir tela de consulta, funcao js_pesquisa()
     *
     * @return {void}
     */
    function js_validarVeiculo() {

        var iVeiculo = document.getElementById('ve01_codigo').value;
        var sIframe = 'db_iframe_validar_veiculos';
        var sArquivo = 'func_veiculosconsulta.php?pesquisa_chave=' + iVeiculo + '&funcao_js=parent.js_validarVeiculo.retorno';
        js_OpenJanelaIframe('CurrentWindow.corpo', sIframe, sArquivo, '', false);
    }

    /**
     * Retorno da funcao validar veiculo
     *
     * @return {void}
     */
    js_validarVeiculo.retorno = function(sDescricaoVeiculo, lErro) {

        /**
         * Veiculo nao encontrado
         */
        if (lErro) {

            document.getElementById('ve01_codigo').focus();
            document.getElementById('ve01_codigo').value = '';
            document.getElementById('ve01_placadescr').value = sDescricaoVeiculo;
            return;
        }

        js_pesquisa();
    }

    function js_pesquisave01_codigo(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_veiculos', 'func_veiculosconsulta.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_placa', 'Pesquisa', true);
        } else {
            if (document.form1.ve01_codigo.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_veiculos', 'func_veiculosconsulta.php?pesquisa_chave=' + document.form1.ve01_codigo.value + '&funcao_js=parent.js_mostraveiculos', 'Pesquisa', false);
            } else {
                document.form1.ve01_placadescr.value = '';
            }
        }
    }

    function js_mostraveiculos(chave, erro) {

        document.form1.ve01_placadescr.value = chave;
        if (erro == true) {
            document.form1.ve01_codigo.focus();
            document.form1.ve01_codigo.value = '';
        }
    }

    function js_mostraveiculos1(chave1, chave2) {

        document.form1.ve01_codigo.value = chave1;
        document.form1.ve01_placadescr.value = chave2;
        db_iframe_veiculos.hide();
    }


    function js_pesquisacentral(mostra) {

        if (mostra) {

            js_OpenJanelaIframe('CurrentWindow.corpo',
                'db_iframe_central',
                'func_veiccadcentral.php?funcao_js=parent.js_mostracentral1|ve36_sequencial|descrdepto&dbdepart=false',
                'Pesquisa de Central',
                true);
        } else {

            if (document.form1.ve40_veiccadcentral.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo',
                    'db_iframe_central',
                    'func_veiccadcentral.php?pesquisa_chave=' + document.form1.ve40_veiccadcentral.value +
                    '&funcao_js=parent.js_mostracentral&dbdepart=false',
                    'Pesquisa de Central',
                    false);
            } else {
                document.form1.descrdepto.value = '';
            }
        }
    }

    function js_mostracentral(chave, erro, descrdepto) {

        document.form1.descrdepto.value = descrdepto;
        if (erro == true) {

            document.form1.ve40_veiccadcentral.focus();
            document.form1.descrdepto.value = '';
        }
    }

    function js_mostracentral1(chave1, chave2) {

        document.form1.ve40_veiccadcentral.value = chave1;
        document.form1.descrdepto.value = chave2;
        db_iframe_central.hide();
    }
    js_tabulacaoforms("form1", "ve01_codigo", true, 0, "ve01_codigo", true);


    function js_pesquisave60_veicmotoristas(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_veicmotoristas', 'func_veicmotoristasalt.php?pessoal=<?= $pessoal ?>&funcao_js=parent.js_mostraveicmotoristas1|ve05_codigo|z01_nome', 'Pesquisa', true);
    } else {
      if (document.form1.ve60_veicmotoristas.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_veicmotoristas', 'func_veicmotoristasalt.php?pessoal=<?= $pessoal ?>&pesquisa_chave=' + document.form1.ve60_veicmotoristas.value + '&funcao_js=parent.js_mostraveicmotoristas', 'Pesquisa', false);
      } else {
        document.form1.z01_nome.value = '';
      }
    }
  }

  function js_mostraveicmotoristas(chave, erro) {
    document.form1.z01_nome.value = chave;
    if (erro == true) {
      document.form1.ve60_veicmotoristas.focus();
      document.form1.ve60_veicmotoristas.value = '';
    }
  }

  function js_mostraveicmotoristas1(chave1, chave2) {
    document.form1.ve60_veicmotoristas.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_veicmotoristas.hide();
  }


</script>