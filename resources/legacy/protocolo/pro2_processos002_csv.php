<?
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_tipoproc_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_procandam_classe.php");
include("classes/db_db_usuarios_classe.php");

//estancia as classes
$cl_processos = new cl_protprocesso;
$cl_tipoproc = new cl_tipoproc;
$cl_usuarios = new cl_db_usuarios;
//label dos campos
$cl_processos->rotulo->label();
$cl_tipoproc->rotulo->label();
$cl_usuarios->rotulo->label();


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//filtro das listas
$db_where = "";
if ($_GET["listacgm"] != "") {
  if ($_GET["Condicao1"] == "com") {
    $db_where .= " p58_numcgm IN ( $_GET[listacgm])";
  } else {
    $db_where .= " p58_numcgm NOT IN ( $_GET[listacgm])";
  }
}
//
if ($_GET["listadept"] != "") {
  if ($_GET["listacgm"] != "") {
    $db_where .= " AND";
  }
  if ($_GET["Condicao2"] == "com") {
    $db_where .= " p58_coddepto IN ( $_GET[listadept])";
  } else {
    $db_where .= " p58_coddepto NOT IN ( $_GET[listadept])";
  }
}
//
if ($_GET["listatipo"] != "") {
  if (($_GET["listadept"] != "") || ($_GET["listacgm"] != "")) {
    $db_where .= " AND";
  }
  if ($_GET["Condicao3"] == "com") {
    $db_where .= " p51_codigo IN ( $_GET[listatipo])";
  } else {
    $db_where .= " p51_codigo NOT IN ( $_GET[listatipo])";
  }
}
//
if ($_GET["listaprocand"] != "") {
  if (($_GET["listatipo"] != "") || ($_GET["listacgm"] != "") || ($_GET["listadept"] != "")) {
    $db_where .= " AND";
  }
  if ($_GET["Condicao3"] == "com") {
    $db_where .= " p61_coddepto IN ( $_GET[listaprocand])";
  } else {
    $db_where .= " p61_coddepto NOT IN ( $_GET[listaprocand])";
  }
}

if (($_GET["listacgm"] != "") ||
  ($_GET["listatipo"] != "") ||
  ($_GET["listaprocand"] != "") ||
  ($_GET["listadept"] != "")
) {
  $db_where .= " AND";
}
$sSqlUsuInsti = "select id_instit from db_userinst where id_usuario = " . db_getsession("DB_id_usuario");
$rsUsuInsti = pg_query($sSqlUsuInsti);
$strWhereinsti = '';
$strV = '';

if (pg_num_rows($rsUsuInsti) > 0) {

  for ($i = 0; $i < pg_num_rows($rsUsuInsti); $i++) {

    $strWhereinsti .= $strV . pg_result($rsUsuInsti, $i, 0);
    $strV = ", ";
  }
}

if ($strWhereinsti != null) {

  $strWhereinsti = "  and p58_instit in ($strWhereinsti)";

}
//filtro das data
/*
if( ($_GET["data1"] != "//") and ($_GET["data1"] == $_GET["data2"]) ) {
  $db_where .= " p58_dtproc = '$_GET[data1]'";
	$head5 = "PERIODO: " . $_GET["data1"];
}
*/

/**
 * Organiza as datas para apresent�-las ao usu�rio
 */
list($iAno1, $iMes1, $iDia1) = explode('/', $_GET['data1']);
list($iAno2, $iMes2, $iDia2) = explode('/', $_GET['data2']);
$dData1 = "{$iDia1}/{$iMes1}/{$iAno1}";
$dData2 = "{$iDia2}/{$iMes2}/{$iAno2}";

if (($_GET["data1"] != "//") and ($_GET["data2"] != "//")) {
  $db_where .= " p58_dtproc BETWEEN '$_GET[data1]' AND '$_GET[data2]'";
  $head5 = "PERIODO: de $dData1 � $dData2";
} elseif (($_GET["data1"] != "//") and ($_GET["data2"] == "//")) {
  $db_where .= " p58_dtproc = '$_GET[data1]'";
  $head5 = "PERIODO: $dData1";
} elseif (($_GET["data1"] == "//") and ($_GET["data2"] != "//")) {
  $db_where .= " p58_dtproc = '$_GET[data2]'";
  $head5 = "PERIODO: $dData2";
}

if (isset($tipo)) {

  if ($db_where == "") {
    $db_where = " 1 = 1";
  }

  if ($tipo == "n") {

    $db_where .= " and p68_codproc is null ";
    $head4 = "SOMENTE OS EM ANDAMENTO";
  } elseif ($tipo == "a") {

    $db_where .= " and p68_codproc is not null ";
    $head4 = "SOMENTE OS ARQUIVADOS";
  } else {
    $head4 = "TODOS (ARQUIVADOS E EM ANDAMENTO)";
  }

}

$ordem = $Ordem;

$ordenacao = " p58_codproc";

if (isset($ordem)) {
  if ($ordem == "1") {
    $ordenacao = " p58_codproc";
  } elseif ($ordem == "2") {
    $ordenacao = " p51_descr";
  } elseif ($ordem == "3") {
    $ordenacao = " p58_dtproc";
  } elseif ($ordem == "4") {
    $ordenacao = " login";
  } elseif ($ordem == "5") {
    $ordenacao = " p58_requer";
  } elseif ($ordem == "6") {
    $ordenacao = " a.descrdepto";
  } elseif ($ordem == "7") {
    $ordenacao = " b.descrdepto";
  }
}
$db_where .= $strWhereinsti;

/**
 * Verifica o campo que dever� ser buscado no banco
 * $sTituloCampo = titulo do campo a ser impresso no PDF
 */
if ($_GET["tipoCGMProcesso"] == 1) {
  // Requerente
  $sCampoMostrar = "p58_requer";
  $sTituloCampo = "Requerente";
} else if ($_GET["tipoCGMProcesso"] == 2) {
  // Titular
  $sCampoMostrar = "z01_nome";
  $sTituloCampo = "Titular";
}


$head3 = "RELAT�RIO DE PROCESSOS ";
//die($cl_processos->sql_query_andam("","p58_codproc,p58_codigo,p51_descr,to_char(p58_dtproc,'dd/mm/yyyy'),login,p58_numcgm,p58_requer,p58_coddepto,p58_codandam,p58_hora,a.descrdepto as deptoproc,p61_coddepto,b.descrdepto as deptoandam",$ordenacao,$db_where));

$sSqlProcessos = "p58_codproc,
                  p58_numero,
                  p58_numeracao||'/'||p58_ano as p58_numeracao,
                  p58_ano,
                  p58_codigo,
                  p51_descr,
                  to_char(p58_dtproc,'dd/mm/yyyy'),
                  login,
                  p58_obs,
                  p58_numcgm,
                  {$sCampoMostrar} AS titular,
                  p58_coddepto,
                  p58_codandam,
                  p58_hora,
                  a.descrdepto as deptoproc,
                  p61_coddepto,
                  b.descrdepto as deptoandam";

$sProcessaSql = $cl_processos->sql_query_deptarq("", $sSqlProcessos, $ordenacao, $db_where);

$result = $cl_processos->sql_record($sProcessaSql);
//db_criatabela($result);

/**
 * Caso n�o seja localizada nenhum registro, direciona para a p�gina abaixo
 */
if ($cl_processos->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=N�o h� registros.');
}



/// come�a escrever relat�rio
$sNomeArquivo = 'processos' . date("d-m-Y") . '.csv';

header("Content-type: text/csv; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=\"{$sNomeArquivo}\"");


function escreveLinha($valores = array()) {

  fputs($fp, implode(';', $valores) . "\n");
  echo implode(';', $valores) . "\n";

}

escreveLinha(array(
  "Protocolo",
  "Processo",
  "Data",
  $RLp51_descr,
  "CGM",
  $sTituloCampo,
  "Dept. Ini.",
  "Dept. Atual",
  "Observa��o"
));

for ($x = 0; $x < $cl_processos->numrows; $x++) {

  db_fieldsmemory($result, $x);

  $aLinha = array();

  $sDeptoOrin   = $p58_coddepto . " - " . $deptoproc;
  $sDeptoAtual  = $p61_coddepto . " - " . $deptoandam;

  $oProcPrincipal = $cl_processos->getPrincipal($p58_codproc);

  if ($oProcPrincipal) {
    $sDeptoAtual  = $oProcPrincipal->coddepto . " - " . $oProcPrincipal->descrdepto;
  }

  $sNumeroProcesso = $p58_numero . "/" . $p58_ano;
  if (empty($p58_numero)) {
    $sNumeroProcesso = "";
  }


  $aLinha[] = $sNumeroProcesso;
  $aLinha[] = $p58_numeracao;
  $aLinha[] = $to_char;
  $aLinha[] = $p51_descr;
  $aLinha[] = $p58_numcgm;
  $aLinha[] = $titular;
  $aLinha[] = $sDeptoOrin;
  $aLinha[] = $sDeptoAtual;

  $aLinha[] = ($Observacao == '1') ? str_replace(array("\n", "\r"), ' :: ', $p58_obs) : '';

  escreveLinha($aLinha);


  /**
   * listar apensados
   */
  $aProcApensados = $cl_processos->getProcessosApensados($p58_codproc, $sCampos);

  if ($aProcApensados && $listaApensados == 's') {

    escreveLinha(array("APENSADOS (" . count($aProcApensados) . ")"));

    foreach ($aProcApensados as $oEsseApensado) {

      $aLinhaApensados = array();

      $sDeptoOrin = $oEsseApensado->p58_coddepto . " - " . $oEsseApensado->descrdepto;

      $sNumeroProcesso = $oEsseApensado->p58_numero . "/" . $oEsseApensado->p58_ano;
      if (empty($oEsseApensado->p58_numero)) {
        $sNumeroProcesso = "";
      }

      $aLinhaApensados[] = $sNumeroProcesso;
      $aLinhaApensados[] = $oEsseApensado->p58_numeracao;
      $aLinhaApensados[] = date("d/m/Y", strtotime($oEsseApensado->p58_dtproc));
      $aLinhaApensados[] = $oEsseApensado->p51_descr;
      $aLinhaApensados[] = $oEsseApensado->p58_numcgm;
      $aLinhaApensados[] = $oEsseApensado->p58_requer;
      $aLinhaApensados[] = $sDeptoOrin;
      $aLinhaApensados[] = $sDeptoAtual;

      escreveLinha($aLinhaApensados);

    }

  }

  escreveLinha();

}

escreveLinha(array("TOTAL DE REGISTROS: " . $cl_processos->numrows));

?>
