<?php
/*
 * E-cidade Software P�blico para Gest�o Municipal
 * Copyright (C) 2014 DBSeller Servi�os de Inform�tica
 * www.dbseller.com.br
 * e-cidade@dbseller.com.br
 *
 * Este programa � software livre; voc� pode redistribu�-lo e/ou
 * modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 * publicada pela Free Software Foundation; tanto a vers�o 2 da
 * Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *
 * Este programa � distribu�do na expectativa de ser �til, mas SEM
 * QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 * COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 * PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 * detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 * junto com este programa; se n�o, escreva para a Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307, USA.
 *
 * C�pia da licen�a no diret�rio licenca/licenca_en.txt
 * licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
?>
<html>

<head>
    <title>DBSeller Inform�tica Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("windowAux.widget.js");
    db_app::load("dbtextField.widget.js");
    db_app::load("dbViewAvaliacoes.classe.js");
    db_app::load("dbmessageBoard.widget.js");
    db_app::load("dbautocomplete.widget.js");
    db_app::load("dbcomboBox.widget.js");
    db_app::load("datagrid.widget.js");
    db_app::load("AjaxRequest.js");
    db_app::load("widgets/DBLookUp.widget.js");
    db_app::load("estilos.css,grid.style.css");
    db_app::load('scripts.js, prototype.js, strings.js,DBLookUp.widget.js,EmissaoRelatorio.js');
    ?>
</head>
<style>
     input {
            border-radius: 5px;
        }
</style>
<body class="body-default">
    <div class="container">
        <fieldset>
            <legend>Envio Fechamento/reabertura dos eventos da s�rie R-4000</legend>
            <table>
                <tr>
                    <td width="4%" align="right" nowrap title="Selecionar a Competencia"><b>Mes competencia:</b></td>
                    <td width="96%" align="left" nowrap>
                        <?php
                        $meses = array(
                            "" => "Selecione",
                            "01" => "Janeiro",
                            "02" => "Fevereiro",
                            "03" => "Mar�o",
                            "04" => "Abril",
                            "05" => "Maio",
                            "06" => "Junho",
                            "07" => "Julho",
                            "08" => "Agosto",
                            "09" => "Setembro",
                            "10" => "Outubro",
                            "11" => "Novembro",
                            "12" => "Dezembro"
                        );
                        db_select('mescompetencia', $meses, true, $opcao, "onchange='js_reload();'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="bold" for="anocompetencia">Ano competencia:</label>
                    </td>
                    <td>
                        <?php
                        $anocompetencia = date("Y", db_getsession("DB_datausu"));
                        db_input('anocompetencia', 10, $Ianocompetencia, true, 'number', $opcao, "");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Tipo:</b></td>
                    <td>
                        <?php
                        $tipos = array(
                            "0" => "Fechamento",
                            "1" => "Abertura"
                        );
                        db_select('tipo', $tipos, true, $opcao, "onchange='js_reload();'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Ambiente:</b></td>
                    <td>
                        <?php
                        $ambiente = array(
                            "1" => "Produ��o",
                            "2" => "Produ��o Restrita"
                        );
                        db_select('ambiente', $ambiente, true, $opcao, "onchange='js_reload();'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="z01_numcgm" class="for">
                            <a id="cgm_ancora">CGM do Respons�vel:</a>
                        </label>
                    </td>
                    <td>
                        <input type="text" name="z01_numcgm" id="z01_numcgm">
                        <input type="text" name="z01_nome" id="z01_nome">
                    </td>
                </tr>
            </table>
        </fieldset>

        <input type="button" name="transmitir" id="transmitir" value="Transmitir" />
    </div>


    <?php
    db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
    ?>
    <script type="text/javascript">

        var oCgm = $("cgm_ancora");
        var oNumCgm = $("z01_numcgm");
        var oNomeCgm = $("z01_nome");
        var oCgmLookup = new DBLookUp(oCgm, oNumCgm, oNomeCgm, {
            "sArquivo": "func_reinfcgmcpf.php",
            "sObjetoLookUp": "db_iframe_numcgm",
            "sLabel": "Pesquisar"
        });

        $('transmitir').addEventListener('click', function () 
        {

            if ($F('mescompetencia') == '') {
                alert('Selecione o M�s compet�ncia.');
                return;
            }

            if ($F('anocompetencia') == '') {
                alert('Selecione o  Ano competencia .');
                return;
            }

            if ($F('tipo') == '') {
                alert('Selecione o Tipo.');
                return;
            }

            if ($F('ambiente') == '') {
                alert('Selecione o Ambiente.');
                return;
            }

            if ($F('z01_numcgm') == '') {
                alert('Selecione o CGM do Respons�vel.');
                return;
            }

            if ($F('z01_nome') == '') {
                alert('Selecione CGM do Respons�vel.');
                return;
            }

            var parametros = {
                'exec'           : 'transmitirreinf4099',
                'sMescompetencia': $F('mescompetencia'),
                'sAnocompetencia': $F("anocompetencia"),
                'sCgm'           : $F('z01_numcgm'),
                'sTipo'          : $F('tipo'),
                'sAmbiente'      : $F('ambiente') 
            };

            new AjaxRequest('efd1_reinf.RPC.php', parametros, function (retorno) {
                alert(retorno.sMessage);
                if (retorno.erro) {
                    return false;
                }
            }).setMessage('Agendando envio para o reinf').execute();
        });

        document.getElementById('mescompetencia').style.width = '130px';
        document.getElementById('anocompetencia').style.width = '130px';
        document.getElementById('tipo').style.width = '130px';
        document.getElementById('z01_numcgm').style.width = '130px';
        document.getElementById('ambiente').style.width = '130px';
    </script>

</body>

</html>
