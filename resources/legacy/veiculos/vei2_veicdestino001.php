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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veiccadcentral_classe.php");

$clveiccadcentral   = new cl_veiccadcentral();
$clveiculos         = new cl_veiculos;
$aux                = new cl_arquivo_auxiliar;

$clrotulo           = new rotulocampo;
$clrotulo->label("ve20_descr");
$clrotulo->label("ve21_descr");
$clrotulo->label("ve22_descr");
$clrotulo->label("ve26_descr");
$clrotulo->label("ve75_destino");

$clveiculos->rotulo->label("ve01_placa");
$clveiculos->rotulo->label("ve01_veiccadmarca");
$clveiculos->rotulo->label("ve01_veiccadmodelo");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

    <script>
        function js_emite(){
            var query = "";
            var obj   = document.form1;
            var lista_veic = "";
            var virgula    = "";

//            if (obj.ve70_dataini.value == "" || obj.ve70_datafin.value == "") {
//                alert("Periodo inv�lido. Verifique");
//                obj.ve70_dataini.focus();
//                obj.ve70_dataini.select();
//                return false;
//            }

            if (obj.ve70_dataini.value != ""){
                query += "ve70_dataini=" +obj.ve70_dataini_ano.value+"-"+obj.ve70_dataini_mes.value+"-"+obj.ve70_dataini_dia.value;
            }

            if (obj.ve70_datafin.value != ""){
                query += "&ve70_datafin="+obj.ve70_datafin_ano.value+"-"+obj.ve70_datafin_mes.value+"-"+obj.ve70_datafin_dia.value;
            }

            // Fun��es nativas do JavaScript
            // slice:   retorna um novo Array a partir de elementos de um Array ou de um Array-like
            // call:    aplica um contexto para o `slice` ser executado, no caso, o contexto � `obj.veiculos`
            // map:     retorna um novo Array a partir do retorno da fun��o aplicada em cada elemento do retorno de `.slice.call`
            // join:    retorna a transforma��o de um Array em String, juntando os elementos por `,`
            lista_veic = Array.prototype.slice
                .call(obj.veiculos)
                .map(function retornaValor(veiculo) {
                    return veiculo.value;
                })
                .join(',');

            if (query != ""){
                query += "&";
            }

            query += "ve01_codigo="+lista_veic;

            // ve75_sequencial
            if (query != ""){
                query += "&";
            }
            query += "ve75_sequencial="+obj.ve75_sequencial.value;

            // ve75_destino
            if (query != ""){
                query += "&";
            }
            query += "ve75_destino="+obj.ve75_destino.value;

            var jan = window.open('vei2_veicdestino002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            jan.moveTo(0,0);
        }


        function js_pesquisave01_veiccadmarca(mostra){
            if(mostra==true){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccadmarca','func_veiccadmarca.php?funcao_js=parent.js_mostraveiccadmarca1|ve21_codigo|ve21_descr','Pesquisa',true);
            }else{
                if(document.form1.ve01_veiccadmarca.value != ''){
                    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccadmarca','func_veiccadmarca.php?pesquisa_chave='+document.form1.ve01_veiccadmarca.value+'&funcao_js=parent.js_mostraveiccadmarca','Pesquisa',false);
                }else{
                    document.form1.ve21_descr.value = '';
                }
            }
        }
        function js_mostraveiccadmarca(chave,erro){
            document.form1.ve21_descr.value = chave;
            if(erro==true){
                document.form1.ve01_veiccadmarca.focus();
                document.form1.ve01_veiccadmarca.value = '';
            }
        }
        function js_mostraveiccadmarca1(chave1,chave2){
            document.form1.ve01_veiccadmarca.value = chave1;
            document.form1.ve21_descr.value        = chave2;
            db_iframe_veiccadmarca.hide();
        }
        function js_pesquisave01_veiccadmodelo(mostra){
            if(mostra==true){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccadmodelo','func_veiccadmodelo.php?funcao_js=parent.js_mostraveiccadmodelo1|ve22_codigo|ve22_descr','Pesquisa',true);
            }else{
                if(document.form1.ve01_veiccadmodelo.value != ''){
                    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccadmodelo','func_veiccadmodelo.php?pesquisa_chave='+document.form1.ve01_veiccadmodelo.value+'&funcao_js=parent.js_mostraveiccadmodelo','Pesquisa',false);
                }else{
                    document.form1.ve22_descr.value = '';
                }
            }
        }
        function js_mostraveiccadmodelo(chave,erro){
            document.form1.ve22_descr.value = chave;
            if(erro==true){
                document.form1.ve01_veiccadmodelo.focus();
                document.form1.ve01_veiccadmodelo.value = '';
            }
        }
        function js_mostraveiccadmodelo1(chave1,chave2){
            document.form1.ve01_veiccadmodelo.value = chave1;
            document.form1.ve22_descr.value         = chave2;
            db_iframe_veiccadmodelo.hide();
        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
    </tr>
</table>

<table  align="center" border="0">
    <form name="form1" method="post" action="">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td nowrap align="right" title="Periodo"><b>Periodo:</b></td>
            <td>
                <?
                db_inputdata("ve70_dataini",@$ve70_dataini_dia,@$ve70_dataini_mes,@$ve70_dataini_ano,true,"text",4)
                ?>
                <b>&nbsp;a&nbsp;</b><?
                db_inputdata("ve70_datafin",@$ve70_datafin_dia,@$ve70_datafin_mes,@$ve70_datafin_ano,true,"text",4)
                ?>
            </td>
        </tr>

        <tr>
            <td nowrap title="<?=@$Tve75_destino?>">
                <?
                db_ancora(@$Lve75_destino,"js_pesquisave75_sequencial(true);",$db_opcao);
                ?>
            </td>
            <td>
                <?
                db_input('ve75_sequencial',10,$Ive75_sequencial,true,'text',$db_opcao,
                    " onchange='js_pesquisave75_sequencial(false);'");
                db_input('ve75_destino',40,$Ive75_destino,true,'text',3,'')
                ?>
            </td>
        </tr>

        <tr>
            <td colspan=2 ><?
                $aux->cabecalho      = "<strong>Veiculos</strong>";
                $aux->codigo         = "ve01_codigo";  //chave de retorno da func
                $aux->descr          = "ve01_placa";   //chave de retorno
                $aux->nomeobjeto     = 'veiculos';
                $aux->funcao_js      = 'js_mostraveiculos';//fun��o javascript que ser� utilizada quando clicar na �ncora
                $aux->funcao_js_hide = 'js_mostraveiculos1';//fun��o javascript que ser� utilizada quando colocar um c�digo e sair do campo
                $aux->sql_exec       = "";
                $aux->func_arquivo   = "func_veiculos.php";  //func a executar
                $aux->nomeiframe     = "db_iframe_veiculos";
                $aux->localjan       = "";
                $aux->onclick        = "";
                $aux->db_opcao       = 4;
                $aux->tipo           = 2;
                $aux->top            = 0;
                $aux->linhas         = 10;
                $aux->vwhidth        = 400;
                $aux->passar_query_string_para_func = "&tipoabast=1&central=";
                $aux->funcao_gera_formulario();
                ?>
            </td>
        </tr>
        <tr>
            <td height="50" colspan="2" align = "center">
                <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
            </td>
        </tr>
    </form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>


<script>
    function js_mostraveiculos1(chave,chave1,chave2){
        if (chave==false){
            document.form1.ve01_placa.value  = "";
            document.form1.ve01_codigo.value = "";
            document.form1.ve01_placa.value  = chave2;
            document.form1.ve01_codigo.value = chave1;
            document.form1.db_lanca.onclick = js_insSelectveiculos;
        } else{
            document.form1.ve01_codigo.value = "";
            document.form1.ve01_placa.value  = "";
            alert("C�digo inexistente");
        }
    }

    function js_BuscaDadosArquivoveiculos(chave){
        document.form1.db_lanca.onclick = '';
        if(chave){
            js_OpenJanelaIframe('','db_iframe_veiculos','func_veiculos.php?funcao_js=parent.js_mostraveiculos|ve01_codigo|ve01_placa&tipoabast=1','Pesquisa',true);
        }else{
            js_OpenJanelaIframe('','db_iframe_veiculos','func_veiculos.php?pesquisa_chave='+document.form1.ve01_codigo.value+'&funcao_js=parent.js_mostraveiculos1&tipoabast=1','Pesquisa',false);
        }
    }

function js_pesquisave75_sequencial ( mostra ) {
    if ( mostra == true ) {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccaddestino','func_veiccaddestino.php?funcao_js=parent.js_mostradestino1|ve75_sequencial|ve75_destino','Pesquisa',true);
    } else {
        if (document.form1.ve75_sequencial.value != '') {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_veiccaddestino','func_veiccaddestino.php?pesquisa_chave='+document.form1.ve75_sequencial.value+'&funcao_js=parent.js_mostradestino','Pesquisa',false);
        } else {
            document.form1.ve75_sequencial.value = '';
        }
    }

    if (document.form1.ve75_sequencial.value == null || document.form1.ve75_sequencial.value == '') {
        document.form1.ve75_destino.value = '';
    }

}
function js_mostradestino ( chave1, chave2, erro ) {
    document.form1.ve75_sequencial.value = chave1;
    document.form1.ve75_destino.value = chave2;
    if ( erro == true ) {
        document.form1.ve75_sequencial.value = '';
        document.form1.ve75_destino.value = '';
    }
}
function js_mostradestino1 ( chave1, chave2 ) {
    document.form1.ve75_sequencial.value = chave1;
    document.form1.ve75_destino.value = chave2;
    db_iframe_veiccaddestino.hide();
}

</script>