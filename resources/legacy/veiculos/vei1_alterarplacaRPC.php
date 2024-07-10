<?php
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/DBTime.php");
require_once("std/DBDate.php");
include("classes/db_veiculos_classe.php");
include("classes/db_condataconf_classe.php");

db_app::import("configuracao.DBDepartamento");


// Fun��o para buscar um veiculo a partir do c�digo
function buscarVeiculo($codigo)
{

  $clveiculos = new cl_veiculos;

  if (!isset($codigo)) {
    return buildResponse(2, "Para buscar ve�culo � necess�rio informar o c�digo.");
  }

  $sql = $clveiculos->sql_query($codigo, "ve01_codigo,ve01_placa,si04_descricao");
  $result = $clveiculos->sql_record($sql);
  if (!$result) {
    return buildResponse(2, "Veiculo c�digo $codigo n�o encontrado.");
  }

  $veiculo = db_utils::getCollectionByRecord($result)[0];
  
  if (isset($veiculo) || $veiculo !== null) {
    $veiculo->si04_descricao = mb_convert_encoding($veiculo->si04_descricao, 'UTF-8', 'ISO-8859-1');
  }
  
  return buildResponse(1, "", ['veiculo' => $veiculo]);
}

// Fun��o para altera��o da placa do ve�culo
function alterarPlaca($dados)
{

  if (!isset($dados->ve01_codigo) || empty($dados->ve01_codigo)) {
    return buildResponse(2, "� necess�rio informar o c�digo do veiculo.");
  }

  if (!isset($dados->ve76_data) || empty($dados->ve76_data)) {
    return buildResponse(2, "� necess�rio informar a data da altera��o.");
  }

  if (!isset($dados->ve76_placa) || empty($dados->ve76_placa)) {
    return buildResponse(2, "� necess�rio informar a nova placa.");
  }

  $clveiculos = new cl_veiculos;

  $sql = $clveiculos->sql_query($dados->ve01_codigo, "ve01_codigo,ve01_placa,si04_descricao,ve01_dtaquis");
  $result = $clveiculos->sql_record($sql);

  if (!$result) {
    return buildResponse(2, "Ocorreu um erro ao buscar o veculo $dados->ve01_codigo.");
  }

  $veiculo = db_utils::getCollectionByRecord($result)[0];

  // Validar se a placa est� sendo alterada para o mesmo c�digo
  if (strcasecmp($veiculo->ve01_placa, $dados->ve76_placa) == 0) {
    return buildResponse(2, "A placa informada � igual a placa j� cadastrada para o ve�culo.");
  }

  // Verificar se j� existe placa cadastrada
  $sqlPlaca = $clveiculos->sql_query(null, "ve01_codigo", "", "ve01_placa = '$dados->ve76_placa'");
  $result = $clveiculos->sql_record($sqlPlaca);
  if ($result !== false) {
    return buildResponse(2, "A placa informada j� est� cadastrada para outro ve�culo.");
  }

  $dataAlteracaoPlaca = convertToDate($dados->ve76_data);

  // Verifica a data de encerramento do per�odo patrimonial
  $clcondataconf = new cl_condataconf;
  $sqlConf = $clcondataconf->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"));
  $result = $clcondataconf->sql_record($sqlConf);

  if ($result != false) {
    $config = db_utils::getCollectionByRecord($result)[0];

    $dataEncerramentoPatrimonial = convertToDate($config->c99_datapat);
    if ($dataAlteracaoPlaca <= $dataEncerramentoPatrimonial) {
      return buildResponse(2, "O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
    }
  }

  // Valida a data de altera��o com a data de aquisi��o do ve�culo
  $dataAquisicao = convertToDate($veiculo->ve01_dtaquis);
  if ($dataAlteracaoPlaca < $dataAquisicao) {
    return buildResponse(2, "A data da altera��o deve ser posterior � data de aquisi��o do ve�culo.");
  }

  db_inicio_transacao();

  try {
    // Adicionar altera��o na tabela veiculosplaca
    $ve76_placaanterior = $veiculo->ve01_placa;

    // Altera a placa do veiculo
    $clveiculos->ve01_codigo = $dados->ve01_codigo;
    $clveiculos->ve01_placa = $dados->ve76_placa;
    if (!$clveiculos->alterar($veiculo->ve01_codigo)) {
      throw new Exception($clveiculos->erro_msg);
    }

    $clveiculosplaca = new cl_veiculosplaca();
    $clveiculosplaca->ve76_veiculo = $dados->ve01_codigo;
    $clveiculosplaca->ve76_placa = $dados->ve76_placa;
    $clveiculosplaca->ve76_placaanterior = $ve76_placaanterior;
    $clveiculosplaca->ve76_obs = mb_convert_encoding($dados->ve76_obs, 'ISO-8859-1', 'UTF-8');
    $clveiculosplaca->ve76_data = $dados->ve76_data;
    $clveiculosplaca->ve76_usuario = db_getsession("DB_id_usuario");
    $clveiculosplaca->ve76_criadoem = (new DateTime())->format('Y-m-d H:i:s');

    if(!$clveiculosplaca->incluir()) {
      throw new Exception($clveiculosplaca->erro_msg);
    }

    db_fim_transacao();

    $result = $clveiculos->sql_record($sql);
    $veiculo = db_utils::getCollectionByRecord($result)[0];

    if (isset($veiculo) || $veiculo !== null) {
      $veiculo->si04_descricao = mb_convert_encoding($veiculo->si04_descricao, 'UTF-8', 'ISO-8859-1');
    }

    return buildResponse(1, "Placa alterada com sucesso!", ["veiculo" =>  $veiculo]);

  } catch (Exception $erro) {
    db_fim_transacao(true);
    return buildResponse(2, $erro->getMessage());
  }
}

// Fun��o que busca altera��o de placa
function buscarAlteracao($ve76_sequencial) {
  $clveiculosplaca = new cl_veiculosplaca();
  $clveiculosplaca->ve76_sequencial = $ve76_sequencial;
  
  $campos = "ve76_sequencial, ve76_veiculo, ve76_placa, ve76_placaanterior, ve76_obs, ve76_data, ve76_usuario";
  $sql = $clveiculosplaca->sql_query($ve76_sequencial, $campos, "", "ve76_sequencial = '$ve76_sequencial'");
  $result = $clveiculosplaca->sql_record($sql);

  if ($result == false) {
    return buildResponse(2, "Altera��o n�o encontrada.", ["altera��o" =>  $ve76_sequencial]);
  }

  $alterarplaca = db_utils::getCollectionByRecord($result)[0];

  if (isset($alterarplaca) || $alterarplaca !== null) {
    $alterarplaca->ve76_obs = mb_convert_encoding($alterarplaca->ve76_obs, 'UTF-8', 'ISO-8859-1');
  }

  return buildResponse(1, "", ["alterarplaca" =>  $alterarplaca]);
}

// Fun��o para excluir a altera��o de placa
function excluirAlteracao($ve76_sequencial)
{
  // Busca a altera��o que ser� exclu�da
  $clveiculosplaca = new cl_veiculosplaca();
  $clveiculosplaca->ve76_sequencial = $ve76_sequencial;
  
  $sql = $clveiculosplaca->sql_query($ve76_sequencial, "*", "", "ve76_sequencial = '$ve76_sequencial'");
  $result = $clveiculosplaca->sql_record($sql);

  if ($result == false) {
    return buildResponse(2, "Altera��o n�o encontrada.", ["altera��o" =>  $ve76_sequencial]);
  }

  $alterarplaca = db_utils::getCollectionByRecord($result)[0];
  
  // Verifica a data de encerramento do per�odo patrimonial
  $clcondataconf = new cl_condataconf;
  $sqlConf = $clcondataconf->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"));
  $result = $clcondataconf->sql_record($sqlConf);

  if ($result != false) {
    $config = db_utils::getCollectionByRecord($result)[0];

    $dataEncerramentoPatrimonial = convertToDate($config->c99_datapat);
    $dataAlteracaoPlaca = convertToDate($alterarplaca->ve76_data);

    if ($dataAlteracaoPlaca <= $dataEncerramentoPatrimonial) {
      return buildResponse(2, "O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
    }
  }

  // Inicia transa��o de exclus�o de placa
  db_inicio_transacao();

  try {
    
    // Altera a placa do veiculo
    $clveiculos = new cl_veiculos;

    $clveiculos->ve01_codigo = $alterarplaca->ve76_veiculo;
    $clveiculos->ve01_placa = $alterarplaca->ve76_placaanterior;
    
    if (!$clveiculos->alterar($alterarplaca->ve76_veiculo)) {
      throw new Exception($clveiculos->erro_msg);
    }

    $clveiculosplaca = new cl_veiculosplaca();
    
    if(!$clveiculosplaca->excluir($alterarplaca->ve76_sequencial)) {
      throw new Exception($clveiculosplaca->erro_msg);
    }

    db_fim_transacao();

    return buildResponse(1, "Altera��o de placa exclu�da sucesso!", []);

  } catch (Exception $erro) {
    db_fim_transacao(true);
    return buildResponse(2, $erro->getMessage());
  }


  return buildResponse(1, "Altera��o de placa exclu�da com sucesso!", []);
}

// Fun��o respons�vel por preparar a resposta da requisi��o
function buildResponse($status, $message, $data = [])
{
  $oJson    = new services_json();
  $oRetorno = new stdClass();

  $oRetorno->status = $status;
  $oRetorno->message = mb_convert_encoding($message, 'UTF-8', 'ISO-8859-1');

  foreach ($data as $key => $value) {
    $oRetorno->$key = $value;
  }
  
  return $oJson->encode($oRetorno);
}

// Converter a data para datetime
function convertToDate($dateString)
{
  if (strpos($dateString, '/') !== false) {
    return DateTime::createFromFormat('d/m/Y', $dateString);
  } else if (strpos($dateString, '-') !== false) {
    return DateTime::createFromFormat('Y-m-d', $dateString);
  } else {
    return false;
  }
}

// Executa as fun��es dispon�veis via Ajax
function executar($oParam)
{
  switch ($oParam->exec) {
    case 'buscarVeiculo':
      return buscarVeiculo($oParam->codigo);
      break;
    case 'buscarAlteracao':
      return buscarAlteracao($oParam->codigo);
      break;
    case 'alterarPlaca':
      return alterarPlaca($oParam->dados);
      break;
    case 'excluirAlteracao':
      return excluirAlteracao($oParam->codigo);
      break;
    default:
      return buildResponse(2, "Opera��o inv�lida para op��o ($oParam->exec.)");
      break;
  }
}

// Recebe os parametros e executa a opera��o
$oParam = (new services_json())->decode(str_replace("\\", "", $_POST["json"]));
echo executar($oParam);
