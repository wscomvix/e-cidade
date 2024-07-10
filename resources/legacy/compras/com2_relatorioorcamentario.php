<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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

$clrotulo = new rotulocampo();
$clrotulo->label("pc80_codproc");
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
<body>
<center>
    <form name="form1" method="post" action="">

        <fieldset style="width: 480px;margin-top: 30px">
            <legend><strong>Relat�rio de Recursos Or�amentarios</strong></legend>

            <table>
                <tr>
                    <td>
                        <strong>Tipo de Relat�rio</strong>
                    </td>
                    <td>
                        <?$iTipoRelatorio = array(
                            1=>"Solicita��o de Disponibiliza��o Financeira",
                            2 =>"Declara��o de Recursos Or�ament�rios");
                        db_select("tiporelatorio",$iTipoRelatorio,true,1,"onchange='js_trocarelatoria(this.value);'");
                        ?>
                    </td>
                </tr>
                <tr id='trsolicitacao'>
                    <td style="font-weight: bolder;" >
                        <? db_ancora("Solicita��o : ","js_pesquisapc10_numero(true);",1);?>
                    </td>
                    <td>
                        <?
                        db_input('pc10_numero',8,$Ipc10_numero,true,"text",1,"onchange='js_pesquisapc10_numero(false);'");
                        ?>
                    </td>
                </tr>
                <tr style="display: none;" id='trprocessocompra'>
                    <td style="font-weight: bolder;" >
                        <? db_ancora("Processos de Compra : ","js_pesquisapc80_codproc(true);",1);?>
                    </td>
                    <td>
                        <?
                        db_input("pc80_codproc", 10, $Ipc80_codproc,true,"text",4,"onchange='js_pesquisapc80_codproc(false);'");
                        ?>
                    </td>
                </tr>
                <tr style="display: none;" id="trimprimevalor">
                    <td>
                        <b>Imprimir valor:</b>
                    </td>
                    <td>
                        <?
                        $aImprimeValor = array("f" => "N�o", "t" => "Sim");
                        db_select("imprimeValor", $aImprimeValor, true, 1);
                        ?>
                    </td>
                </tr>
                
            </table>
        </fieldset>
    </form>
    <br>
    <input type="button" name="btnEmitir" id="btnEmitir" value="Emitir PDF" onclick="js_emitirProcessoPDF();">
    <input type="button" name="btnEmitir" id="btnEmitir" value="Emitir WORD" onclick="js_emitirProcessoWORD();">
</center>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
    db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

    function js_pesquisapc80_codproc(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcprocrelorc.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc','Pesquisa',true);
        }else{
            if(document.form1.pc80_codproc.value != ''){
                js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcprocrelorc.php?pesquisa_chave='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_mostrapcproc','Pesquisa',false);
            }else{
                document.form1.pc80_codproc.value = '';
            }
        }
    }
    function js_mostrapcproc(chave,erro){
        if(erro==true){
            document.form1.pc80_codproc.focus();
            document.form1.pc80_codproc.value = '';
        }
    }
    function js_mostrapcproc1(chave1,x){
        document.getElementById('pc80_codproc').value= chave1;
        db_iframe_pcproc.hide();
    }
    function js_pesquisapc10_numero(mostra){
        qry = "&nada=true";
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem1|pc10_numero'+qry,'Pesquisa',true);
        }else{
            if(document.form1.pc10_numero.value!=""){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem&pesquisa_chave='+document.form1.pc10_numero.value+qry,'Pesquisa',false);
            }else{
            document.form1.pc10_numero.value = "";
            }
        }
    }
    function js_mostrapcorcamitem1(chave1,chave2){
        document.form1.pc10_numero.value = chave1;
        db_iframe_solicita.hide();
    }
    function js_mostrapcorcamitem(chave1,erro){
        if(erro==true){
            document.form1.pc10_numero.value = "";
        }
    }

    /**
     * Validamos as informa��es do formul�rio e reemitimos o documento do processo de compra
     */
    function js_emitirProcessoPDF() {
        let tiporelatorio = $F('tiporelatorio');

        if(tiporelatorio == 1){
            solicitacaocompras = $F('pc10_numero');

            if(solicitacaocompras == ""){
                alert("Solicita��o de Compras n�o informado.");
                return;
            }

            Filtros = "";
            Filtros += "solicitacaocompras="+solicitacaocompras;
            Filtros += "&tipo=1";

            var jan = window.open('com2_relatorioorcamentario002.php?'+Filtros, '', 'location=0, width='+(screen.availWidth - 5)+
                'width='+(screen.availWidth - 5)+', scrollbars=1');
            jan.moveTo(0, 0);
        }

        if(tiporelatorio == 2){
            processodecompras = $F('pc80_codproc');
            imprimevalor = $F('imprimeValor');

            if(processodecompras == ""){
                alert("Processo de Compras n�o informado.");
                return;
            }

            Filtros = "";
            Filtros += "processodecompras="+processodecompras;
            Filtros += "&tipo=1";
            Filtros += "&imprimevalor="+imprimevalor;

            var jan = window.open('com2_relatorioorcamentario003.php?'+Filtros, '', 'location=0, width='+(screen.availWidth - 5)+
                'width='+(screen.availWidth - 5)+', scrollbars=1');
            jan.moveTo(0, 0);
        }


    }
    function js_emitirProcessoWORD() {
        let tiporelatorio = $F('tiporelatorio');

        if(tiporelatorio == 1){
            solicitacaocompras = $F('pc10_numero');

            if(solicitacaocompras == ""){
                alert("Solicita��o de Compras n�o informado.");
                return;
            }

            Filtros = "";
            Filtros += "solicitacaocompras="+solicitacaocompras;
            Filtros += "&tipo=2";

            var jan = window.open('com2_relatorioorcamentario004.php?'+Filtros, '', 'location=0, width='+(screen.availWidth - 5)+
                'width='+(screen.availWidth - 5)+', scrollbars=1');
            jan.moveTo(0, 0);
        }

        if(tiporelatorio == 2){
            processodecompras = $F('pc80_codproc');
            imprimevalor = $F('imprimeValor');

            if(processodecompras == ""){
                alert("Processo de Compras n�o informado.");
                return;
            }

            Filtros = "";
            Filtros += "processodecompras="+processodecompras;
            Filtros += "&tipo=2";
            Filtros += "&imprimevalor="+imprimevalor;


            var jan = window.open('com2_relatorioorcamentario005.php?'+Filtros, '', 'location=0, width='+(screen.availWidth - 5)+
                'width='+(screen.availWidth - 5)+', scrollbars=1');
            jan.moveTo(0, 0);
        }

    }

    function js_trocarelatoria(iTipo){

        if(iTipo == 1){
            document.getElementById('trsolicitacao').style.display= "";
            document.getElementById('trprocessocompra').style.display= "none";
            document.getElementById('trimprimevalor').style.display= "none";
            document.getElementById('pc80_codproc').value= "";
            return;
        }

        document.getElementById('trprocessocompra').style.display= "";
        document.getElementById('trsolicitacao').style.display= "none";
        document.getElementById('trimprimevalor').style.display= "";
        document.getElementById('pc10_numero').value= "";
        
    }

</script>