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
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$clselorcdotacao = new cl_selorcdotacao;
$oFiltroCredores = new cl_arquivo_auxiliar;
$oFiltroGestores = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if (!isset($testdt)){
  $testdt='sem';
}

if (!isset($desdobramento)){
  $desdobramento = "true";
}

$anousu = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">

.table {
  padding: 30px 0;
  width: 100%;
  border: 0;
}

.table table {
  margin-left: auto;
  margin-right: auto;
}

#filtro_gestores {
  display: none;
}

</style>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC">

<form name="form1" method="post" action="">
<table cellspacing="0" cellpadding="0" class="table">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
      <table border="0">
        <tr>
          <td align="center">
            <strong>Op��es:</strong>
            <select name="ver">
              <option name="condicao1" value="com">Com os credores selecionados</option>
              <option name="condicao1" value="sem">Sem os credores selecionadas</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <?
            // $oFiltroCredores = new cl_arquivo_auxiliar;
            $oFiltroCredores->cabecalho = "<strong>Credores</strong>";
            $oFiltroCredores->codigo = "e60_numcgm"; //chave de retorno da func
            $oFiltroCredores->descr  = "z01_nome";   //chave de retorno
            $oFiltroCredores->nomeobjeto = 'credor';
            $oFiltroCredores->funcao_js = 'js_mostra';
            $oFiltroCredores->funcao_js_hide = 'js_mostra1';
            $oFiltroCredores->sql_exec  = "";
            $oFiltroCredores->func_arquivo = "func_cgm_empenho.php";  //func a executar
            $oFiltroCredores->nomeiframe = "db_iframe_cgm";
            $oFiltroCredores->localjan = "";
            $oFiltroCredores->onclick = "";
            $oFiltroCredores->nome_botao = "db_lanca_credor";
            $oFiltroCredores->db_opcao = 2;
            $oFiltroCredores->tipo = 2;
            $oFiltroCredores->top = 1;
            $oFiltroCredores->linhas = 4;
            $oFiltroCredores->vwhidth = 400;
            $oFiltroCredores->funcao_gera_formulario();
            ?>
          </td>
        </tr>
      </table>
    </td>

    <td>
      <div id="filtro_gestores">
        <table>
          <tr>
            <td>
              <?php
                // $oFiltroGestores = new cl_arquivo_auxiliar;
                $oFiltroGestores->cabecalho = "<strong>Gestores Empenhos</strong>";
                $oFiltroGestores->codigo = "coddepto"; //chave de retorno da func
                $oFiltroGestores->descr  = "descrdepto";   //chave de retorno
                $oFiltroGestores->nomeobjeto = 'e54_gestaut';
                $oFiltroGestores->funcao_js = 'js_mostraDpt';
                $oFiltroGestores->funcao_js_hide = 'js_mostraDpt1';
                $oFiltroGestores->sql_exec  = "";
                $oFiltroGestores->func_arquivo = "func_departamento.php";  //func a executar
                $oFiltroGestores->nomeiframe = "db_iframe_cgm";
                $oFiltroGestores->localjan = "";
                $oFiltroGestores->onclick = "";
                $oFiltroCredores->nome_botao = "db_lanca_gestor";
                $oFiltroGestores->db_opcao = 2;
                $oFiltroGestores->tipo = 2;
                $oFiltroGestores->top = 1;
                $oFiltroGestores->linhas = 4;
                $oFiltroGestores->vwhidth = 400;
                $oFiltroGestores->funcao_gera_formulario();
                ?>
            </td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>

<script>
</script>

</body>
</html>
