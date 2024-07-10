<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$db_opcao = 1;
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="top">
            <form name='form1'>
                <fieldset>
                    <legend><b>Confer�ncia de Empenhos</b></legend>
                    <table>
                        <tr>
                            <td>
                                <strong>Exerc�cio:</strong>
                                <select name="anousu" id="anousu">
                                    <option value="2017">2017</option>
                                    <option value="2016">2016</option>
                                    <option value="2015" selected>2015</option>
                                    <option value="2014">2014</option>
                                    <option value="2013">2013</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>M�s:</strong>
                                <input type="text" id="mesIni" maxlength="2" size="5" oninput="js_ValidaCampos(this,1,'M�s In�cio','t','f',event);"/>
                                <b>at�</b>
                                <input type="text" id="mesFim" maxlength="2" size="5" oninput="js_ValidaCampos(this,1,'M�s Fim','t','f',event);"/>
                            </td>
                        </tr>

                    </table>
                </fieldset>
            </form>
        </td>
    </tr>
    <tr>
        <td align='center'>
            <input name='pesquisar' type='button' value='Emitir' onclick='js_abre();'>
        </td>
    </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
    function js_abre() {
        obj = document.form1;
        query = '';

        query += "anousu=" + obj.anousu.value + "&mesini=" + obj.mesIni.value + "&mesfim=" + obj.mesFim.value;

        jan = window.open('con4_conferenciaempenhos002.php?' + query,
            '',
            'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);

    }
</script>

