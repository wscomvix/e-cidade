<?php

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");

$oGet = db_utils::postMemory($_GET);
/*
* clausula FROM para o SQL do relat�rio. O select est� contido no arquivo .agt,
* pois ele � fixo na consulta e o FROM pode ser v�riavel dependendo o tipo de
* configura��o
*/
$sFrom  = "   issbase                                          											";
$sFrom .= "   inner join cgm                   on cgm.z01_numcgm                   = issbase.q02_numcgm ";
$sFrom .= "   inner join tabativ               on tabativ.q07_inscr                = issbase.q02_inscr  ";
$sFrom .= "   inner join ativid                on tabativ.q07_ativ                 = ativid.q03_ativ    ";
$sFrom .= "   inner join ativprinc             on ativprinc.q88_inscr              = tabativ.q07_inscr  ";
$sFrom .= "   left join issbasecaracteristica  on issbasecaracteristica.q138_inscr = issbase.q02_inscr  ";
// declara��o das v�riaveis
$sWhere = null;

// Tratamento de dados para pessoa
switch ($oGet->pessoa) {

  case "f":
	  $sPessoa = "F�sica";
		$sWhere  = "length(z01_cgccpf) = 11 ";
	break;

  case "j":
	  $sPessoa = "Jur�dica";
		$sWhere  = "length(z01_cgccpf) = 14 ";
	break;

	case "t":
	  $sPessoa = "Todas";
	break;

}

// adiciona o AND no SQL quando necess�rio
$and = null;
if ($sWhere !== null) {
  $and = "and";
}

// Tratamento de dados para Baixa
switch ($oGet->baixa) {

  case "n":
	  $sBaixa = "N�o";
		$sWhere .= "{$and} (q02_dtbaix is null or q02_dtbaix >= now())";
	break;

  case "s":
	  $sBaixa = "Sim";
		$sWhere .= " {$and} (q02_dtbaix is not null and q02_dtbaix < now())";
	break;

	case "t":
	  $sBaixa = "Todas";
	break;

}

$and = null;
if ($sWhere !== null) {
  $and = "and";
}

// Tratamento de dados para Atividade
switch ($oGet->atividade) {

  case "p":
	  $sAtividade = "Somente Principal";
	  $sWhere .= " {$and} q07_seq = q88_seq";
	break;

	case "t":
	  $sAtividade = "Todas";
	break;

}

$and = null;
if ($sWhere !== null) {
  $and = "and";
}

if ($oGet->datainicioatividade != "--") {
  $sWhere .= " {$and} tabativ.q07_datain > '{$oGet->datainicioatividade}'";
}

$and = null;
if ($sWhere !== null) {
  $and = "and";
}

if ($oGet->datafinalatividade != "--") {
	$sWhere .= " {$and} tabativ.q07_datafi < '{$oGet->datafinalatividade}'";
}

$and = null;
if ($sWhere !== null) {
  $and = "and";
}

$descricaoregime = "Todos";
if ($oGet->regime !== "0") {
  $sWhere .= " {$and} q138_caracteristica = {$oGet->regime}";
}

// Tratamento de dados para Ordem
switch ($oGet->ordem) {

  case "i":
	  $sOrdem   = "Inscri��o";
    $sOrderBy = "q02_inscr asc";
	break;

	case "n":
	  $sOrdem   = "Nome";
		$sOrderBy = '"Nome / Raz�o Social" asc';
	break;

  case "a":
	  $sOrdem   = "Atividade";
		$sOrderBy = '"Descri��o da Atividade" asc';
	break;
}

# Include AgataAPI class
include_once('dbagata/classes/core/AgataAPI.class');

# Instantiate AgataAPI
$clagata = new cl_dbagata("issqn/iss1_inscr002.agt");

$api = $clagata->api;
$api->setParameter('$head1', "Relat�rio de Inscri��es");
$api->setParameter('$head2', "Pessoa: {$sPessoa}");
$api->setParameter('$head3', "Baixadas: {$sBaixa}");
$api->setParameter('$head4', "Atividades: {$sAtividade}");
$api->setParameter('$head5', "Data de In�cio da Atividade: ".db_formatar($oGet->datainicioatividade,"d"));
$api->setParameter('$head6', "Data de Fim da Atividade: ".db_formatar($oGet->datafinalatividade,"d"));
$api->setParameter('$head7', "Regime: {$descricaoregime}");
$api->setParameter('$head8', "Ordem: {$sOrdem}");

$api->setParameter('$instit', db_getsession('DB_instit'));

// Modifica o SQL do arquivo XML (iss1_inscr002.agt) gravado pelo agata
$xml = $api->getReport();
$xml["Report"]["DataSet"]["Query"]["From"]    = $sFrom;
$xml["Report"]["DataSet"]["Query"]["Where"]   = $sWhere;
$xml["Report"]["DataSet"]["Query"]["OrderBy"] = $sOrderBy;
$api->setReport($xml);

$ok = $api->generateReport();

if (!$ok)
{
    echo $api->getError();
}
else
{
    db_redireciona($clagata->arquivo);
}
