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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclancedital_classe.php");
require_once("classes/db_editaldocumento_classe.php");
require_once("classes/db_cflicita_classe.php");
include("dbforms/db_classesgenericas.php");

$clliclicita = new cl_liclicita;
$clliclancedital = new cl_liclancedital;
$clcflicita  = new cl_cflicita;
$cleditaldocumento = new cl_editaldocumento;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$sqlerro = false;
$db_opcao = 2;

if (!isset($alterar)) {
  $sWhere = "
    AND (CASE WHEN pc50_pctipocompratribunal IN (48, 49, 50, 52, 53, 54)
                                    AND liclicita.l20_dtpublic IS NOT NULL THEN EXTRACT(YEAR FROM liclicita.l20_dtpublic)
                                    WHEN pc50_pctipocompratribunal IN (100, 101, 102, 103, 106)
                                    AND liclicita.l20_datacria IS NOT NULL THEN EXTRACT(YEAR FROM liclicita.l20_datacria)
                              END) >= 2020
  ";
  $sqlEdital = $clliclicita->sql_query_edital('', 'DISTINCT *', 'l47_sequencial DESC', 'l20_codigo = ' . $licitacao . $sWhere);
  $rsEdital = $clliclicita->sql_record($sqlEdital);
  $oDados = db_utils::fieldsMemory($rsEdital, 0);

  $numero_edital = $oDados->l20_nroedital;
  $objeto = $oDados->l20_objeto;
  $edital = $oDados->l20_edital;
  $codigoModalidade = $oDados->l20_numero;
  $tipo_tribunal = $oDados->l44_sequencial;
  $descr_tribunal = strtoupper($oDados->l44_descricao);
  $origem_recurso = $oDados->l47_origemrecurso;
  $descricao_recurso = $oDados->l47_descrecurso;
  $email = $oDados->l47_email;
  $links = $oDados->l47_linkpub;
  $data_referencia = join('/', array_reverse(explode('-', $oDados->l47_dataenvio)));
  $natureza_objeto = $oDados->l20_naturezaobjeto;
  $sequencial = $oDados->l47_sequencial;
  $codigolicitacao = $oDados->l20_codigo;
  $anoLicitacao = $oDados->l20_anousu;
  $iTipoJulgamento = $oDados->l20_tipojulg;

  $tabela_base = $anoLicitacao >= 2021 ? 'obrasdadoscomplementareslote' : 'obrasdadoscomplementares';
  $clobrasdadoscomplementares = db_utils::getDao($tabela_base);
}

if (isset($alterar)) {
  $sqlEdital = $clliclancedital->sql_query_completo('', 'l20_codigo, l47_sequencial, l20_naturezaobjeto, l48_tipo', '', 'l20_codigo = ' . $licitacao);
  $rsEdital = $clliclancedital->sql_record($sqlEdital);
  $oDadosEdital = db_utils::fieldsMemory($rsEdital, 0);
  $natureza_objeto = $oDadosEdital->l20_naturezaobjeto;
  $codigolicitacao = $oDadosEdital->l20_codigo;

  $sSqlDocumentos = $cleditaldocumento->sql_query(null, 'l48_tipo', null, ' l48_liclicita = ' . $licitacao);
  $rsDocumentos = $cleditaldocumento->sql_record($sSqlDocumentos);

  $sqlLicita = $clliclicita->sql_query_edital('', 'DISTINCT l20_codigo, l20_edital, l20_nroedital, l20_objeto,l20_numero, pctipocompratribunal.l44_sequencial as tipo_tribunal,
  UPPER(pctipocompratribunal.l44_descricao) as descr_tribunal, l20_naturezaobjeto as natureza_objeto,
  l47_dataenvio, l20_anousu, l20_tipojulg', '', 'l20_codigo = ' . $licitacao . $sWhere, '', 1);
  $rsLicita = $clliclicita->sql_record($sqlLicita);
  $oDadosLicitacao = db_utils::fieldsMemory($rsLicita, 0);
  $tipo_tribunal = $oDadosLicitacao->tipo_tribunal;

  if ($natureza_objeto == 1) {

    $aTipos = db_utils::getCollectionByRecord($rsDocumentos);
    $aSelecionados = array();

    foreach ($aTipos as $tipo) {
      $aSelecionados[] = $tipo->l48_tipo;
    }

    if (in_array($tipo_tribunal, array(100, 101, 102, 103, 106))) {
      $tiposCadastrados = array_intersect($aSelecionados, array('mc', 'po', 'cr', 'cb', 'td', 'fl'));
    } elseif (in_array($tipo_tribunal, array(48, 49, 50, 52, 53, 54))) {
      $tiposCadastrados = array_intersect($aSelecionados, array('mc', 'po', 'cr', 'cb', 'ed', 'fl'));
    }

    if ($cleditaldocumento->numrows == 0) {
      $sqlerro = true;
      $erro_msg = 'Nenhum documento anexo � licita��o';
    } else {
      if (count($tiposCadastrados) < 6) {
        $sqlerro = true;
        $erro_msg = 'Existem documentes anexos faltantes, verifique o cadastro na aba de Documentos!';
      }
    }

    /* Verifica se tem dados complementares vinculados � licita��o */
    if (!$sqlerro) {
      if (!trim($anoLicitacao)) {
        $anoLicitacao = explode('/', $data_referencia);
        $tabela_base = $anoLicitacao >= 2021 ? 'obrasdadoscomplementareslote' : 'obrasdadoscomplementares';
        $clobrasdadoscomplementares = db_utils::getDao($tabela_base);
      }
      $sSqlObras = $clobrasdadoscomplementares->sql_query_completo(null, '*', null, 'db151_liclicita = ' . $licitacao);
      $rsObras = $clobrasdadoscomplementares->sql_record($sSqlObras);

      if ($clobrasdadoscomplementares->numrows == 0) {
        $sqlerro = true;
        $erro_msg = 'Nenhum dado complementar cadastrado, verifique!';
      }
    }
  } else {
    if (!$cleditaldocumento->numrows && !$sqlerro) {
      $sqlerro = true;
      $erro_msg = 'Existem documentes anexos faltantes, verifique o cadastro na aba de Documentos!';
    }
  }


  if (!$sqlerro) {
    if (isset($oDadosEdital->l47_sequencial)) {
      $data_formatada = str_replace('/', '-', db_formatar($data_referencia, 'd'));
      $clliclancedital->l47_linkpub = $links;
      $clliclancedital->l47_origemrecurso = $origem_recurso;
      $clliclancedital->l47_descrecurso = $descricao_recurso;
      $clliclancedital->l47_dataenvio = $data_formatada;
      $clliclancedital->l47_liclicita = $oDadosEdital->l20_codigo;
      $clliclancedital->l47_email = $email;

      $clliclancedital->alterar($oDadosEdital->l47_sequencial);
      $erro_msg = $clliclancedital->erro_sql;
      if ($clliclancedital->erro_status == '0') {
        $sqlerro = true;
      }
    }
  }
}


?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.maskedinput.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/dbViewCadDadosComplementares.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewLotesPendentesLicitacao.classe.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
  #msgBoardEnderecodadosCompl_title {
    padding-top: 15px;
  }
</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
        <center>
          <?
          include("forms/db_frmlicedital.php");
          ?>
        </center>
      </td>
    </tr>
  </table>
  <script>
  </script>
</body>

</html>
<?
echo "<script>";
echo "parent.document.formaba.documentos.disabled=false;";
echo "parent.iframe_editais.js_buscaDadosComplementares();";
echo "parent.iframe_documentos.location.href='lic4_editaldocumentos.php?l20_codigo=$codigolicitacao&l47_sequencial=$sequencial&natureza_objeto=$natureza_objeto&cod_tribunal=$tipo_tribunal';";
echo "</script>";

if (isset($alterar)) {
  echo "<script>";
  echo "alert('" . $erro_msg . "');";
  echo "</script>";
}
echo "<script>document.form1.data_referencia.value = '" . $data_referencia . "';</script>";
?>
