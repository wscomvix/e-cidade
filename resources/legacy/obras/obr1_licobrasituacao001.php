<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_licobrasituacao_classe.php");
include("classes/db_licobras_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_condataconf_classe.php");

db_postmemory($HTTP_POST_VARS);
$cllicobrasituacao = new cl_licobrasituacao;
$cllicobras = new cl_licobras;
$clcondataconf = new cl_condataconf;
$db_opcao = 1;
$db_botao = true;
$sqlerro = false;

if (isset($incluir)) {

  $resultObras = $cllicobras->sql_record($cllicobras->sql_query($obr02_seqobra, "obr01_dtlancamento", null, null));
  db_fieldsmemory($resultObras, 0);
  $dataobra = (implode("/", (array_reverse(explode("-", $obr01_dtlancamento)))));

  $datalancamentobra = DateTime::createFromFormat('d/m/Y', $dataobra);
  $dtalancamento = DateTime::createFromFormat('d/m/Y', $obr02_dtlancamento);
  $dtpublicacao = DateTime::createFromFormat('d/m/Y', $obr02_dtpublicacao);
  $dtsituacao = DateTime::createFromFormat('d/m/Y', $obr02_dtsituacao);

  try {
    /**
     * valida��o com sicom
     */
    if (!empty($dtalancamento)) {
      $anousu = db_getsession('DB_anousu');
      $instituicao = db_getsession('DB_instit');
      $result = $clcondataconf->sql_record($clcondataconf->sql_query_file($anousu, $instituicao, "c99_datapat", null, null));
      db_fieldsmemory($result);
      $data = (implode("/", (array_reverse(explode("-", $c99_datapat)))));
      $dtencerramento = DateTime::createFromFormat('d/m/Y', $data);

      if ($dtalancamento <= $dtencerramento) {
        throw new Exception("O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
      }
    }

    /**
     * Valida��es para o tipo de situa��o da obra
     * @
     */
    if ($obr02_situacao == "0") {
      throw new Exception("Selecione uma Situa��o");
      $sqlerro = true;
    }

    if ($obr02_situacao == "2") {
      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "*", null, "obr02_seqobra = $obr02_seqobra and obr02_situacao = 2"));
      if (pg_num_rows($resultSituacao) > 0) {
        throw new Exception("Obra j� iniciada!");
        $sqlerro = true;
      }
    }

    if ($obr02_situacao == "1") {

      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "obr02_dtsituacao as dtsituacao1", null, "obr02_seqobra = $obr02_seqobra and obr02_situacao = 2"));

      db_fieldsmemory($resultSituacao, 0);
      $datasituacao1 = (implode("/", (array_reverse(explode("-", $dtsituacao1)))));

      /* Verificando se a situa��o iniciada foi cadastrada no mesmo m�s da situa��o a ser inclusa. */
      if (substr($obr02_dtsituacao, 3, 2) == substr($datasituacao1, 3, 2) && substr($obr02_dtsituacao, 6, 4) == substr($datasituacao1, 6, 4)) {
        throw new Exception('A obra n�o poder� estar "N�O INICIADA" e "INICIADA" no mesmo m�s de cadastro.');
      }

      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "obr02_situacao as situacao", "obr02_situacao", "obr02_seqobra = $obr02_seqobra"));
      db_fieldsmemory($resultSituacao, 0);

      if (pg_num_rows($resultSituacao) > 2 || pg_num_rows($resultSituacao) == 1 && $situacao == "1") {
        throw new Exception("Obra j� iniciada!");
        $sqlerro = true;
      }
    }

    if ($obr02_situacao == "2") {
      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "obr02_dtsituacao as dtsituacao1", null, "obr02_seqobra = $obr02_seqobra and obr02_situacao = 1"));

      db_fieldsmemory($resultSituacao, 0);
      $data1 = (implode("/", (array_reverse(explode("-", $dtsituacao1)))));

      /* Verificando se a situa��o iniciada foi cadastrada no mesmo m�s da situa��o a ser inclusa. */
      if (substr($obr02_dtsituacao, 3, 2) == substr($data1, 3, 2) && substr($obr02_dtsituacao, 6, 4) == substr($data1, 6, 4)) {
        throw new Exception('A obra n�o poder� estar "INICIADA" e "N�O INICIADA" no mesmo m�s de cadastro.');
      }


      $datasituacao1 = DateTime::createFromFormat('d/m/Y', $data1);

      if ($dtsituacao <= $datasituacao1) {
        throw new Exception("Data da Inicio deve ser maior que data de n�o iniciada.");
        $sqlerro = true;
      }
    }

    if ($obr02_situacao == "3" || $obr02_situacao == "4") {
      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "*", null, "obr02_seqobra = $obr02_seqobra and obr02_situacao = 2"));
      if (pg_num_rows($resultSituacao) <= 0) {
        throw new Exception("Para que uma obra seja paralisada deve existir o registro de inicio da obra!");
        $sqlerro = true;
      }
    }

    if ($obr02_situacao == "5" || $obr02_situacao == "6" || $obr02_situacao == "7") {
      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "*", null, "obr02_seqobra = $obr02_seqobra and obr02_situacao = 2"));
      if (pg_num_rows($resultSituacao) <= 0) {
        throw new Exception("Para que uma obra seja conclu�da ela deve ter sido iniciada!");
        $sqlerro = true;
      }
    }

    if ($obr02_situacao == "8") {
      $resultSituacao = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "*", null, "obr02_seqobra = $obr02_seqobra and obr02_situacao in (3,4)"));
      if (pg_num_rows($resultSituacao) <= 0) {
        throw new Exception("Para que uma obra seja Reiniciada ela deve ter sido Paralisada!");
        $sqlerro = true;
      }
    }

    if ($obr02_situacao == "7") {
      $resultsituacao7 = $cllicobrasituacao->sql_record($cllicobrasituacao->sql_query(null, "*", null, "obr01_sequencial = $obr02_seqobra and obr02_situacao = 7"));
      if (pg_num_rows($resultsituacao7) > 0) {
        db_fieldsmemory($resultsituacao7, 0);
        $data = (implode("/", (array_reverse(explode("-", $obr02_dtsituacao)))));
        $datasituacao7 = DateTime::createFromFormat('d/m/Y', $data);
        if ($dtsituacao > $datasituacao7) {
          throw new Exception("Obra j� conclu�da definitivamente!");
          $sqlerro = true;
        }
      }
    }

    if ($datalancamentobra != null) {
      if ($dtalancamento < $datalancamentobra) {
        throw new Exception("Usu�rio: Data de Lan�amento deve ser maior ou igual a data de lan�amento da Obra.");
        $sqlerro = true;
      }
    }

    if ($dtsituacao < $dtalancamento) {
      throw new Exception("Usu�rio: Data de Situa��o deve ser maior ou igual a data de lan�amento da Obra.");
      $sqlerro = true;
    }

    if ($dtpublicacao < $dtsituacao) {
      throw new Exception("Usu�rio: Data de Publica��o deve ser maior ou igual a data de Situa��o da Obra.");
      $sqlerro = true;
    }

    db_inicio_transacao();
    if ($sqlerro == false) {
      $cllicobrasituacao->obr02_seqobra                  = $obr02_seqobra;
      $cllicobrasituacao->obr02_dtlancamento             = $obr02_dtlancamento;
      $cllicobrasituacao->obr02_situacao                 = $obr02_situacao;
      $cllicobrasituacao->obr02_dtsituacao               = $obr02_dtsituacao;
      $cllicobrasituacao->obr02_veiculopublicacao        = $obr02_veiculopublicacao;
      $cllicobrasituacao->obr02_dtpublicacao             = $obr02_dtpublicacao;
      $cllicobrasituacao->obr02_descrisituacao           = $obr02_descrisituacao;
      $cllicobrasituacao->obr02_motivoparalisacao        = $obr02_motivoparalisacao;
      $cllicobrasituacao->obr02_dtparalisacao            = $obr02_dtparalisacao;
      $cllicobrasituacao->obr02_outrosmotivos            = $obr02_outrosmotivos;
      $cllicobrasituacao->obr02_dtretomada               = $obr02_dtretomada;
      $cllicobrasituacao->obr02_instit                   = db_getsession('DB_instit');
      $cllicobrasituacao->incluir();

      if ($cllicobrasituacao->erro_status == 0) {
        $erro = $cllicobrasituacao->erro_msg;
        $sqlerro = true;
      }
    }

    db_fim_transacao();
  } catch (Exception $eErro) {
    db_msgbox($eErro->getMessage());
  }
}
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
  #obr02_descrisituacao {
    width: 710px;
    height: 43px;
    background-color: #E6E4F1;
  }

  #obr02_outrosmotivos {
    width: 756px;
    height: 34px;
  }

  #tipocompra {
    width: 263px;
  }
</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="790" border="0" cellspacing="0" cellpadding="0" style="margin-left: 16%; margin-top: 2%;">
    <tr>
      <td height="500" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <?
          include("forms/db_frmlicobrasituacao.php");
          ?>
        </center>
      </td>
    </tr>
  </table>
  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<script>
  js_tabulacaoforms("form1", "obr02_seqobra", true, 1, "obr02_seqobra", true);
</script>
<?
if (isset($incluir)) {
  if ($sqlerro == false) {
    if ($cllicobrasituacao->erro_status == "0") {
      $cllicobrasituacao->erro(true, false);
      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if ($cllicobrasituacao->erro_campo != "") {
        echo "<script> document.form1." . $cllicobrasituacao->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $cllicobrasituacao->erro_campo . ".focus();</script>";
      }
    } else {
      $cllicobrasituacao->erro(true, true);
    }
  }
}
?>