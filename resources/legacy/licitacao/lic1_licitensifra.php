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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_pcproc_classe.php");
require_once("classes/db_pcprocitem_classe.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_liclancedital_classe.php");
require_once("classes/db_pcorcamitemlic_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_liclicitemlote_classe.php");
require_once("classes/db_pcorcamitem_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;
$clpcproc           = new cl_pcproc;
$clpcprocitem       = new cl_pcprocitem;
$clliclicitem       = new cl_liclicitem;
$clpcorcamitemlic   = new cl_pcorcamitemlic;
$clpcparam          = new cl_pcparam;
$clliclicitemlote   = new cl_liclicitemlote;
$clpcorcamitem      = new cl_pcorcamitem;
$clliclancedital   = new cl_liclancedital;

$db_opcao = 1;
$db_botao = true;

$rsCodtribunal = db_query("select l03_pctipocompratribunal from liclicita
inner join cflicita on l20_codtipocom =  l03_codigo
where l20_codigo = $licitacao;");
$codtribunal = db_utils::fieldsMemory($rsCodtribunal, 0);
$codtribunal = $codtribunal->l03_pctipocompratribunal;

$rsDispensaporvalor = db_query("SELECT l20_dispensaporvalor FROM liclicita WHERE l20_codigo = $licitacao");
$l20_dispensaporvalor = db_utils::fieldsMemory($rsDispensaporvalor);
$l20_dispensaporvalor = $l20_dispensaporvalor->l20_dispensaporvalor;


?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/dbItensLicitacaoView.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextFieldData.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script>
        function js_submit_form() {
            document.form1.codprocant.value = document.form1.codproc.value;
            // js_gera_chaves();
        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

    <style>
        .linha__marcada {
            color: #5e5e5e;
        }
    </style>

</head>

<body class="body-default" bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <form name="form1" method="post">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td align="left" valign="top" bgcolor="#CCCCCC">
                    <center>
                        <?
                        db_input("tipojulg", 1, '', true, "hidden", 3);
                        db_input('licitacao', 10, '', true, 'hidden', 3);
                        db_input('incluir', 10, '', true, 'hidden', 3);
                        db_input('codproc', 10, '', true, 'hidden', 3);
                        db_input('codprocant', 10, '', true, 'hidden', 3);
                        db_input('cods', 10, '', true, 'hidden', 3);
                        db_input('codprocanu', 10, '', true, 'hidden', 3);
                        ?>

                        <div class="container">
                            <div id="itens"></div>
                        </div>

                        <?php

                        $destinacaoExclusiva = db_query("select l20_destexclusiva from liclicita where l20_codigo = $licitacao;");
                        $destinacaoExclusiva = pg_result($destinacaoExclusiva, 0, 'l20_destexclusiva');

                        ?>

                        <script>
                            if (document.form1.codproc.value) {
                                let licitacao = document.form1.licitacao.value;
                                var oItensLicitacao = new dbViewItensLicitacao('oItensLicitacao', document.getElementById('itens'), <?php echo $destinacaoExclusiva; ?>);
                                let objeto = new Object();
                                objeto.iLicitacao = document.form1.licitacao.value;
                                objeto.iProcCompra = document.form1.codproc.value;
                                oItensLicitacao.show(objeto);
                            }
                        </script>
                    </center>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>
<script>
    function js_insereItens() {

        let aItens = oItensLicitacao.oGridItens.aRows;

        let aSelecionados = aItens.filter(e => e.isSelected);
        let aItensFormatados = [];

        let erro = false;

        let index = 0;

        if (!aSelecionados.length) {
            alert('Informe ao menos um item!');
            return;
        }

        aSelecionados.forEach(elemento => {

            let item = new Object();
            item.codigo = elemento.aCells[1].getContent();
            item.sequencial = elemento.aCells[2].getContent();
            item.codprocitem = elemento.aCells[10].getContent();
            item.codproc = document.form1.codproc.value;


            idMeepp = "meEpp" + index;
            valorMeepp = document.forms["form1"][idMeepp].value;

            item.sigilo = document.getElementById(elemento.aCells[9].sId).children[0].selectedIndex;

            if (document.getElementById(elemento.aCells[7].sId).children[0].selectedIndex) {
                item.qtdexclusiva = document.getElementById(elemento.aCells[8].sId).children[0].value;
            } else {
                item.qtdexclusiva = 0;
            }

            if (item.qtdexclusiva == "" && valorMeepp == 1) {
                alert('Qtde Exclusiva do item ' + item.codigo + ' precisa ser preenchida!');
                erro = true;
                return;
            }

            aItensFormatados.push(item);
            index++;

        });

        if (erro == true) {
            return;
        }
        js_divCarregando('Aguarde, processando.....', 'msgbox');
        let oParam = new Object();
        if (parent.procs.document.form1.l20_orcsigiloso.style.display == 'none') {
            oParam.l20_orcsigiloso = 'null';
        } else {
            oParam.l20_orcsigiloso = parent.procs.document.form1.l20_orcsigiloso.value;
        }
        oParam.licitacao = document.form1.licitacao.value;
        oParam.aItens = aItensFormatados;
        oParam.codprocant = document.form1.codprocant.value;
        oParam.tipojulg = document.form1.tipojulg.value;
        oParam.exec = 'insereItens';

        var oAjax = new Ajax.Request('lic4_licitacao.RPC.php', {
            method: 'POST',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornoItens
        });

    }

    function js_retornoItens(oAjax) {


        let oRetorno = eval("(" + oAjax.responseText + ")");
        let tipoJulgamento = "<?= $tipojulg ?>";
        let licitacao = "<?= $licitacao ?>";
        let naturezaObj = "<?= $naturezaobj ?>";
        let codtribunal = "<?= $codtribunal ?>";
        let l20_dispensaporvalor = "<?= $l20_dispensaporvalor?>"
        


        if (oRetorno.status == 1) {
            js_removeObj('msgbox');
            alert('Item inclu�do com sucesso!');

            parent.parent.iframe_liclicita.bloquearRegistroPreco;
            
            // Regras para redirecionamento edital
            // Se (l20_tipojulg == 1) AND
            // Modalidade in (1 => "Dispensa", 2 => "Dispensa por Chamada P�blica", 3 => "Inexigibilidade por credenciamento" ou 4 =>"Inexigibilidade") AND
            // Codigo do Tribunal in (100,101,102 e 103)
            let codigosTribunal = [100,101,102,103];
            if (parseInt(tipoJulgamento, 10) === 1 && l20_dispensaporvalor != 't' && codigosTribunal.includes(parseInt(codtribunal, 10))) {
                console.log(l20_dispensaporvalor);
                parent.parent.window.location.href='lic4_editalabas.php?licitacao=<?= $licitacao ?>';
            }

            // Redireciona para o caso onde todos os itens j� tem lote
            if (parseInt(tipoJulgamento, 10) === 3 && codigosTribunal.includes(parseInt(codtribunal, 10))) {
                var oAjax = new Ajax.Request('lic4_licitacao.RPC.php', {
                    method: 'POST',
                    parameters: `json={"licitacao": ${licitacao}, "exec": "itensSemLoteCount"}`,
                    onComplete: (res) => {
                        const retorno = eval("(" + res.responseText + ")");
                        if (retorno.status === 1 && retorno.itenssemlotcount === "0") {
                            parent.parent.window.location.href = 'lic4_editalabas.php?licitacao=<?= $licitacao ?>';
                        } else {
                            redirectTo(`lic1_liclicitemalt001.php?licitacao=${licitacao}&tipojulg=${tipoJulgamento}`);
                        }
                    },
                    onError: () => redirectTo(`lic1_liclicitemalt001.php?licitacao=${licitacao}&tipojulg=${tipoJulgamento}`)
                });
            } else {
                redirectTo(`lic1_liclicitemalt001.php?licitacao=${licitacao}&tipojulg=${tipoJulgamento}`);
            }
        }
        
        if (oRetorno.status == 2) {
            js_removeObj('msgbox');
            alert('Inclus�o abortada, processo de compra por lote!');
        } else if (oRetorno.status == 3) {
            alert('Erro ao lan�ar valor estimado sigiloso!');

        } else {
            //db_msgbox(@$erro_msg);
            db_msgbox("Opera��o Cancelada!!Contate Suporte!!");
        }
        
        parent.procs.document.form1.submit();
        oItensLicitacao.oGridItens.clearAll(true);
        oItensLicitacao.hide();

    }

    function redirectTo(url) {
        parent.location.href = url;
    }
</script>