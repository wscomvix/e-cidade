<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaproc_classe.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_pctipocompranumero_classe.php");
require_once("classes/db_pccfeditalnum_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_liclicitemlote_classe.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_pcorcamitemlic_classe.php");
require_once("classes/db_pcorcamdescla_classe.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_homologacaoadjudica_classe.php");
require_once("classes/db_liccomissaocgm_classe.php");
require_once("classes/db_condataconf_classe.php");
require_once("classes/db_manutencaolicitacao_classe.php");
require_once("classes/db_liclicitasituacao_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("l20_codigo");
$clrotulo->label("pc80_codproc");
$clrotulo->label("l20_licsituacao");
$clrotulo->label("l03_codigo");
$clrotulo->label("l03_descr");
$clrotulo->label("pc50_descr");
$clrotulo->label("l34_protprocesso");
$clrotulo->label("p58_numero");
$clrotulo->label("l20_nroedital");
$clrotulo->label("l20_tipoprocesso");

$clliclicita          = new cl_liclicita;
$clliclicitaproc      = new cl_liclicitaproc;
$clpctipocompra       = new cl_pctipocompra;
$clpctipocompranumero = new cl_pctipocompranumero;
$cldb_usuarios        = new cl_db_usuarios;
$clliclicitemlote     = new cl_liclicitemlote;
$clliclicitem         = new cl_liclicitem;
$clpcorcamitemlic     = new cl_pcorcamitemlic;
$clpcorcamdescla      = new cl_pcorcamdescla;
$clcflicita           = new cl_cflicita;
$oDaoLicitaPar        = new cl_pccflicitapar;
$clhomologacao        = new cl_homologacaoadjudica;
$clliccomissaocgm     = new cl_liccomissaocgm;
$clpccfeditalnum      = new cl_pccfeditalnum;
$clmanutencaolicitacao = new cl_manutencaolicitacao;
$clliclicitasituacao  = new cl_liclicitasituacao;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cl_scripts = new cl_scripts;

$anousu = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");
$db_opcao = 3;
if (isset($alterar)) {
  $bmsg = false;
  $result_geral = $clliclicita->sql_record($clliclicita->sql_query_file(null, "max(l20_edital) as edital", null, "l20_instit=$instit and l20_instit = " . db_getsession('DB_instit') . "and l20_anousu = " . db_getsession("DB_anousu")));
  if ($l20_edital != $l20_edital_old) {
    $change = true;
    if ($clliclicita->numrows > 0) {
      db_fieldsmemory($result_geral, 0, 1);
      //verifica se existe edital maior ou igual.
      if ($l20_edital >= $l20_edital_old) {
        $numero = $l20_edital;
        $numero_geral = $clliclicita->sql_record($clliclicita->sql_query_file(null, "l20_edital", null, "l20_edital=$numero and l20_instit = " . db_getsession('DB_instit') . "and l20_anousu = " . db_getsession("DB_anousu")));
        if ($clliclicita->numrows > 0) {
          db_msgbox("J� existe licita��o com o processo licitat�rio $numero");
          $erro = true;
          $bmsg = true;
        }
      }

      //verifica se existe edital menor
      if ($l20_edital < $l20_edital_old) {
        $numero = $l20_edital;
        $numero_geral = $clliclicita->sql_record($clliclicita->sql_query_file(null, "l20_edital", null, "l20_edital=$numero and l20_instit = " . db_getsession('DB_instit') . "and l20_anousu = " . db_getsession("DB_anousu")));
        if ($clliclicita->numrows == 0) {
          $l20_edital = $l20_edital;
        } else {
          db_msgbox("J� existe licita��o com o processo licitat�rio $numero");
          $erro = true;
          $bmsg = true;
        }
      }
    }
  }

  if ($l20_numero != $l20_numero_old) {
    $change = true;
    /* Tratamento do campo l20_numero. */
    $clliclicita_edital = new cl_liclicita;

    $sSqlModalidadeAtual    = $clliclicita_edital->sql_query_edital(null, "distinct pc50_pctipocompratribunal as codtribunalatual", null, "l20_instit = $instit and l20_codigo = $l20_codigo and l20_anousu = " . db_getsession("DB_anousu"));
    $result_modalidadeAtual = $clliclicita_edital->sql_record($sSqlModalidadeAtual);
    //        echo $sSqlModalidadeAtual;
    //        db_criatabela($result_modalidadeAtual);exit;
    db_fieldsmemory($result_modalidadeAtual, 0, 1);

    $sSqlModalidade    = $clliclicita_edital->sql_query_edital(null, "distinct pc50_pctipocompratribunal as searchcodtribunal,l20_numero", null, "l20_instit = $instit and l20_numero = $l20_numero and pc50_pctipocompratribunal = $codtribunalatual and l20_anousu = " . db_getsession("DB_anousu"));
    $result_modalidade = $clliclicita_edital->sql_record($sSqlModalidade);
    //        echo $sSqlModalidade;
    //        db_criatabela($result_modalidade);exit;
    if ($clliclicita_edital->numrows > 0) {
      db_msgbox("J� existe licita��o com a modalidade $l20_numero");
      $erro_edital = true;
      $bmsg = true;
    }
  }

  if ($l20_nroedital != $l20_nroedital_old) {
    $change = true;
    /* Tratamento do campo l20_nroedital. */
    $clliclicita_edital = new cl_liclicita;
    $sSqlMaxEdital = $clliclicita_edital->sql_query_file(null, "max(l20_nroedital) as nroedital", null, "l20_instit = $instit and l20_anousu = " . db_getsession("DB_anousu"));
    $result_geral_edital = $clliclicita_edital->sql_record($sSqlMaxEdital);

    if ($clliclicita_edital->numrows > 0) {

      db_fieldsmemory($result_geral_edital, 0, 1);
      //verifica se existe edital maior ou igual.

      $numero = $l20_nroedital;

      $sWhere = "l20_nroedital = $numero and l20_instit = $instit and l20_anousu = $anousu";
      $numero_geral = $clliclicita_edital->sql_record($clliclicita_edital->sql_query_file(null, "l20_nroedital", null, $sWhere));

      if ($clliclicita_edital->numrows > 0) {
        db_msgbox("J� existe licita��o com o edital n�mero $numero");
        $erro_edital = true;
        $bmsg = true;
      }
    }
  }

  if ($manutlic_codunidsubanterior != $manutlic_codunidsubanterior_old || $manutlic_codunidsubanterior == '') {
    $change = true;

    $sSqlMaxManutLic = $clmanutencaolicitacao->sql_query_file(null, "max(manutlic_sequencial)", null, "manutlic_licitacao = $l20_codigo");
    $clmanutencaolicitacao->sql_record($sSqlMaxManutLic);

    if ($clmanutencaolicitacao->numrows > 0) {
      $clmanutencaolicitacao->excluir('', "manutlic_licitacao = $l20_codigo");
    }
  }

  if ($l11_data != $l11_data_old) {
    $change = true;
  }

  if ($l20_dtpubratificacao != $l20_dtpubratificacao_old) {
    $change = true;
  }

  if ($l20_objeto != $l20_objeto_old) {
    $change = true;
  }

  if ($l20_datacria != $l20_datacria_old) {
    $change = true;
  }

  if ($l20_dataaber != $l20_dataaber_old) {
    $change = true;
  }

  if ($l20_dataaberproposta != $l20_dataaberproposta_old) {
    $change = true;
  }

  if ($l20_dataencproposta != $l20_dataencproposta_old) {
    $change = true;
  }

  if ($l20_datadiario != $l20_datadiario_old) {
    $change = true;
  }

  if ($l20_dtpulicacaopncp != $l20_dtpulicacaopncp_old) {
    $change = true;
  }

  if ($l20_datapublicacao1 != $l20_datapublicacao1_old) {
    $change = true;
  }

  if ($l20_datapublicacao2 != $l20_datapublicacao2_old) {
    $change = true;
  }

  if ($l20_dtpulicacaoedital != $l20_dtpulicacaoedital_old) {
    $change = true;
  }


  if (!isset($erro)) {
    //altera data homologa��o
    if ($sqlerro == false) {

      $result_dataHomologacao = $clliclicita->sql_record($clliclicita->sql_query(null, "distinct homologacaoadjudica.*", null, "l20_codigo = $l20_codigo"));


      for ($iCont = 0; $iCont < pg_num_rows($result_dataHomologacao); $iCont++) {

        $oPosicao = db_utils::fieldsMemory($result_dataHomologacao, $iCont);

        $datahomologacao = 'l202_datahomologacao_' . $oPosicao->l202_sequencial;
        $dataadjudicacao = 'l202_dataadjudicacao_' . $oPosicao->l202_sequencial;

        $datah = '';
        $dataa = '';

        $datah = implode('-', array_reverse(explode('/', $$datahomologacao)));
        $dataa = implode('-', array_reverse(explode('/', $$dataadjudicacao)));
        if($datah != null || $datah != ''){
          if($dataa != null || $dataa != ''){
            $resultado = db_query("update homologacaoadjudica set l202_datahomologacao = '$datah', l202_dataadjudicacao  = '$dataa' where l202_sequencial  = '$oPosicao->l202_sequencial'");
          }else{
            $resultado = db_query("update homologacaoadjudica set l202_datahomologacao = '$datah' where l202_sequencial  = '$oPosicao->l202_sequencial'");
          }
        }elseif($dataa != null || $dataa != ''){
          $resultado = db_query("update homologacaoadjudica set l202_dataadjudicacao  = '$dataa' where l202_sequencial  = '$oPosicao->l202_sequencial'");
        }
        $sqlerro = false;
      }
    }

    if (!isset($erro) && !$erro_edital && $change == true) {
      //print_r($_POST);exit;
      $clliclicita->l20_numero = $l20_numero;
      $clliclicita->l20_nroedital = $l20_nroedital;
      $clliclicita->l20_codtipocom = $l20_codtipocom;
      $clliclicita->l20_veicdivulgacao = $l20_veicdivulgacao;
      $clliclicita->l20_tipojulg = $l20_tipojulg;
      $clliclicita->l20_objeto = $l20_objeto;
      $clliclicita->l20_datacria = !empty($l20_datacria)
        ? "$l20_datacria_ano-$l20_datacria_mes-$l20_datacria_dia"
        : null;
      $clliclicita->l20_dataaber = !empty($l20_dataaber)
        ? "$l20_dataaber_ano-$l20_dataaber_mes-$l20_dataaber_dia"
        : null;
      $clliclicita->l20_dataaberproposta = !empty($l20_dataaberproposta)
        ? "$l20_dataaberproposta_ano-$l20_dataaberproposta_mes-$l20_dataaberproposta_dia"
        : null;
      $clliclicita->l20_dataencproposta = !empty($l20_dataencproposta)
        ? "$l20_dataencproposta_ano-$l20_dataencproposta_mes-$l20_dataencproposta_dia"
        : null;
      $clliclicita->l20_datadiario = !empty($l20_datadiario)
          ? "$l20_datadiario_ano-$l20_datadiario_mes-$l20_datadiario_dia"
          : null;
      $clliclicita->l20_dtpulicacaopncp = !empty($l20_dtpulicacaopncp)
        ? "$l20_dtpulicacaopncp_ano-$l20_dtpulicacaopncp_mes-$l20_dtpulicacaopncp_dia"
        : null;
      $clliclicita->l20_datapublicacao1 = !empty($l20_datapublicacao1)
        ? "$l20_datapublicacao1_ano-$l20_datapublicacao1_mes-$l20_datapublicacao1_dia"
        : null;
      $clliclicita->l20_datapublicacao2 = !empty($l20_datapublicacao2)
        ? "$l20_datapublicacao2_ano-$l20_datapublicacao2_mes-$l20_datapublicacao2_dia"
        : null;
      $clliclicita->l20_dtpulicacaoedital = !empty($l20_dtpulicacaoedital)
        ? "$l20_dtpulicacaoedital_ano-$l20_dtpulicacaoedital_mes-$l20_dtpulicacaoedital_dia"
        : null;
      $clliclicita->l20_recdocumentacao = !empty($l20_recdocumentacao)
        ? "$l20_recdocumentacao_ano-$l20_recdocumentacao_mes-$l20_recdocumentacao_dia"
        : null;

      $clliclicita->alterar($l20_codigo, $descricao);

      $db_opcao = 2;

      if ($clliclicita->erro_status == "0") {
        $erro_msg = $clliclicita->erro_msg;
        $erro_msg_formatada = str_replace(array("Usu�rio:", "Administrador:"), "", $erro_msg);
        $sqlerro = true;
        echo "<script>alert(`$erro_msg_formatada`);</script>";
      }

      //altera data julgamento

      if ($sqlerro == false) {
        $result = $clliclicitasituacao->sql_record($clliclicitasituacao->sql_query(null, "l11_sequencial", "l11_sequencial desc", "l20_codigo = $l20_codigo and l11_licsituacao = 1"));
        $clliclicitasituacao->l11_data = "$l11_data_ano-$l11_data_mes-$l11_data_dia";
        $l11_sequencial = db_utils::fieldsMemory($result, 0)->l11_sequencial;
        $clliclicitasituacao->l11_sequencial = $l11_sequencial;
        $clliclicitasituacao->alterar($l11_sequencial);

        if ($clliclicitasituacao->erro_status == "0") {
          $erro_msg = $clliclicitasituacao->erro_msg;
          $sqlerro = false;
        }
      }

      //altera data publica��o ratifica��o

      if ($sqlerro == false) {
        $result_dtrat = $clliclicita->sql_record($clliclicita->sql_query(null, "l20_codigo", null, "l20_codigo = $l20_codigo"));
        $clliclicita->l20_dtpubratificacao = "$l20_dtpubratificacao_ano-$l20_dtpubratificacao_mes-$l20_dtpubratificacao_dia";
        $l20_codigo = db_utils::fieldsMemory($result_dtrat, 0)->l20_codigo;
        $clliclicita->l20_codigo = $l20_codigo;
        $clliclicita->alterar_dtpubratificacao($l20_codigo);

        if ($clliclicita->erro_status == "0") {
          $erro_msg = $clliclicita->erro_msg;
          $sqlerro = false;
        }
      }
    }

    $clmanutencaolicitacao->manutlic_licitacao = $l20_codigo;
    $clmanutencaolicitacao->manutlic_codunidsubanterior = $manutlic_codunidsubanterior;
    $clmanutencaolicitacao->manutlic_editalant = $manutlic_editalant;

    $clmanutencaolicitacao->incluir();

    if ($sqlerro == false) {
      $resmanut = db_query("select nextval('db_manut_log_manut_sequencial_seq') as seq");
      $seq = pg_result($resmanut, 0, 0);
      $result = db_query("insert into db_manut_log values($seq,'Vigencia anterior: " . $oPosicao->ac16_datainicio . " - " . $oPosicao->ac16_datafim . " atual: " . $ac16_datainicio . " - " . $ac16_datafim . "  '," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

      echo "<script>alert('Altera��o efetuada');</script>";
      $db_opcao = 3;
      $l20_codigo = '';
    }
  } else {
    if ($bmsg == false) {
      echo "<script>alert('Voc� n�o fez nenhuma mudan�a');</script>";
    }
  }
} else if (isset($chavepesquisa)) {

  $db_opcao = 2;
  $sCampos = '*, ';
  $sCampos .= "(select count(*) from liclicitem where l21_codliclicita = l20_codigo) as itens_lancados";
  $result = $clliclicita->sql_record($clliclicita->sql_query($chavepesquisa, $sCampos));


  if ($clliclicita->numrows > 0) {

    db_fieldsmemory($result, 0);

    $result = $clmanutencaolicitacao->sql_record($clmanutencaolicitacao->sql_query('', '*', '', "manutlic_licitacao = $chavepesquisa"));

    db_fieldsmemory($result, 0);

    if (isset($l34_protprocesso) && trim($l34_protprocesso) != '') {
      $l34_protprocessodescr = $p58_requer;
    }

    $tipojulg = $l20_tipojulg;

    if (!empty($p58_numero)) {

      $p58_numero = "{$p58_numero}/{$p58_ano}";
      $l34_protprocesso = $p58_codproc;
    }

    $l20_edital_old    = $l20_edital;
    $l20_numero_old    = $l20_numero;
    $l20_nroedital_old = $l20_nroedital;
    $manutlic_codunidsubanterior_old = $manutlic_codunidsubanterior_old;

    $result = $clliclicitasituacao->sql_record($clliclicitasituacao->sql_query(null, 'l11_data', 'l11_sequencial desc', "l20_codigo = $chavepesquisa and l11_licsituacao = 1"));

    db_fieldsmemory($result, 0);

    $result_dtrat = $clliclicita->sql_record($clliclicita->sql_query(null, 'l20_dtpubratificacao', null, "l20_codigo = $chavepesquisa"));

    db_fieldsmemory($resdtrat, 0);
  }
}

?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
  db_app::load("scripts.js, prototype.js, datagrid.widget.js, messageboard.widget.js, dbtextField.widget.js");
  db_app::load("windowAux.widget.js, strings.js,dbtextFieldData.widget.js");
  db_app::load("grid.style.css, estilos.css");
  ?>
  <style>
    .temdesconto {
      background-color: #D6EDFF
    }
  </style>
</head>

<body bgcolor="#CCCCCC">
  <?php
  $sContass = explode(".", db_getsession("DB_login"));

  if ($sContass[1] != 'contass') {

    echo "<br><center><br><H2>Essa rotina apenas pode ser usada por usu�rios da contass</h2></center>";
  } else {
  ?>

    <form name='form1' method="post" action="" onsubmit="return confirm('Deseja realmente alterar?');">
      <div class="container">
        <fieldset>
          <legend><b></b></legend>
          <table>
            <tr>
              <td nowrap="nowrap" title="<?= $Tl20_codigo ?>">
                <b><?php db_ancora('Licita��o:', "js_pesquisa_liclicita(true);", 1); ?></b>
              </td>
              <td align="left" nowrap="nowrap">
                  <?php
                db_input("l20_codigo", 10, $Il20_codigo, true, "text", 3, "onchange='js_pesquisa_liclicita(false);'");
                ?>
              </td>
            </tr>
            <?php if ($db_opcao == 2) : ?>
            <tr>
                <td nowrap title="Objeto">
                    <strong>Objeto:</strong>
                </td>
                <td>
                    <?php
                    db_textarea('l20_objeto', 0, 50, $Il20_objeto, true, 'text', $db_opcao, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="Exerc�cio da Licita��o">
                    <strong>Exerc�cio:</strong>
                </td>
                <td>
                    <?php
                        db_input('l20_anousu', 10, 1, true, 'text', 2, "");
                    ?>
                </td>
            </tr>
                <tr>
                    <td nowrap title="Processo Licitat�rio">
                        <strong>Processo Licitat�rio:</strong>
                    </td>
                    <td>
                        <?php
                        db_input('l20_edital', 10, $Il20_edital, true, 'text', 2, "");
                        ?>
                    </td>
                </tr>
                <?php
                db_input('l20_edital_old', 10, $Il20_edital, true, 'hidden', 2, "");
                ?>
                <tr>
                    <td nowrap title="Numera��o">
                        <strong>Modalidade:</strong>
                    </td>
                    <td>
                        <?php
                        db_input('l20_numero', 10, $Il20_numero, true, 'text', 2, "");
                        ?>
                    </td>
                </tr>
                <?php
                db_input('l20_numero_old', 10, $Il20_numero, true, 'hidden', 2, "");
                ?>
                <tr id="linha_nroedital">
                    <td nowrap title="<?= @$Tl20_nroedital ?>">
                        <?= @$Ll20_nroedital ?>
                    </td>
                    <td>
                        <?php
                        $mostra = $l20_nroedital && $db_opcao == 2 || !$l20_nroedital && $db_opcao == 1
                        || db_getsession('DB_anousu') >= 2021 ? 3 : 1;
                        db_input('l20_nroedital', 10, $Il20_nroedital, true, 'text', 2, "");
                        ?>
                    </td>
                </tr>
                <?php
                db_input('l20_nroedital_old', 10, $Il20_nroedital, true, 'hidden', 2, "");
                ?>
            <tr>
                <td nowrap title="Data abertura Proc. Adm.">
                    <strong>Data abertura Proc. Adm.: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_datacria', @$l20_datacria_dia, @$l20_datacria_mes, @$l20_datacria_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_datacria_old', @$l20_datacria_dia, @$l20_datacria_mes, @$l20_datacria_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Data Emis./Alt. Edital / Convite">
                    <strong>Data Emis./Alt. Edital / Convite: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_dataaber', @$l20_dataaber_dia, @$l20_dataaber_mes, @$l20_dataaber_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_dataaber_old', @$l20_dataaber_dia, @$l20_dataaber_mes, @$l20_dataaber_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Abertura Proposta">
                    <strong>Abertura Proposta: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_dataaberproposta', @$l20_dataaberproposta_dia, @$l20_dataaberproposta_mes, @$l20_dataaberproposta_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_dataaberproposta_old', @$l20_dataaberproposta_dia, @$l20_dataaberproposta_mes, @$l20_dataaberproposta_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Encerramento Proposta">
                    <strong>Encerramento Proposta: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_dataencproposta', @$l20_dataencproposta_dia, @$l20_dataencproposta_mes, @$l20_dataencproposta_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_dataencproposta_old', @$l20_dataencproposta_dia, @$l20_dataencproposta_mes, @$l20_dataencproposta_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Publica��o DO">
                    <strong>Publica��o DO: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_datadiario', @$l20_datadiario_dia, @$l20_datadiario_mes, @$l20_datadiario_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_datadiario_old', @$l20_datadiario_dia, @$l20_datadiario_mes, @$l20_datadiario_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Publica��o PNCP">
                    <strong>Publica��o PNCP: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_dtpulicacaopncp', @$l20_dtpulicacaopncp_dia, @$l20_dtpulicacaopncp_mes, @$l20_dtpulicacaopncp_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_dtpulicacaopncp_old', @$l20_dtpulicacaopncp_dia, @$l20_dtpulicacaopncp_mes, @$l20_dtpulicacaopncp_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Ve�culo 1">
                    <strong>Ve�culo 1: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_datapublicacao1', @$l20_datapublicacao1_dia, @$l20_datapublicacao1_mes, @$l20_datapublicacao1_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_datapublicacao1_old', @$l20_datapublicacao1_dia, @$l20_datapublicacao1_mes, @$l20_datapublicacao1_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Ve�culo 2">
                    <strong>Ve�culo 2: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_datapublicacao2', @$l20_datapublicacao2_dia, @$l20_datapublicacao2_mes, @$l20_datapublicacao2_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_datapublicacao2_old', @$l20_datapublicacao2_dia, @$l20_datapublicacao2_mes, @$l20_datapublicacao2_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Publica��o Edital">
                    <strong>Publica��o Edital: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_dtpulicacaoedital', @$l20_dtpulicacaoedital_dia, @$l20_dtpulicacaoedital_mes, @$l20_dtpulicacaoedital_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
            db_inputdata('l20_dtpulicacaoedital_old', @$l20_dtpulicacaoedital_dia, @$l20_dtpulicacaoedital_mes, @$l20_dtpulicacaoedital_ano, true, 'hidden', $iCampo);
            ?>
            <tr>
                <td nowrap title="Recebimento de documentos">
                    <strong>Recebimento de documentos: </strong>
                </td>
                <td>
                    <?php
                    db_inputdata('l20_recdocumentacao', @$l20_recdocumentacao_dia, @$l20_recdocumentacao_mes, @$l20_recdocumentacao_ano, true, 'text', $iCampo);
                    ?>
                </td>
            </tr>
            <?php
                db_inputdata('l20_recdocumentacao_old', @$l20_recdocumentacao_dia, @$l20_recdocumentacao_mes, @$l20_recdocumentacao_ano, true, 'hidden', $iCampo);
            ?>

              <tr>
                <td nowrap title="Data Julgamento">
                  <strong>Data Julgamento: </strong>
                </td>
                <td>
                    <?php
                  db_inputdata('l11_data', @$l11_data_dia, @$l11_data_mes, @$l11_data_ano, true, 'text', $iCampo);
                  ?>
                </td>
              </tr>
              <?php
              db_inputdata('l11_data_old', @$l11_data_dia, @$l11_data_mes, @$l11_data_ano, true, 'hidden', $iCampo);
              ?>

              <?php
              $dtpub = array(100, 101, 102, 103);
              if (in_array($chavepesquisa2, $dtpub)) :
              ?>
                <tr>
                  <td nowrap title="Data Pub. Ratifica��o">
                    <strong>Data Pub. Ratifica��o: </strong>
                  </td>
                  <td>
                      <?php
                    db_inputdata('l20_dtpubratificacao', @$l20_dtpubratificacao_dia, @$l20_dtpubratificacao_mes, @$l20_dtpubratificacao_ano, true, 'text', $iCampo);
                    ?>
                  </td>
                </tr>
                  <?php
                db_inputdata('l20_dtpubratificacao_old', @$l20_dtpubratificacao_dia, @$l20_dtpubratificacao_mes, @$l20_dtpubratificacao_ano, true, 'hidden', $iCampo);
              endif;
              ?>
              <?php
              if ($l20_codtipocom == 9 || $l20_codtipocom == 11 || $l20_codtipocom == 10 ||$l20_codtipocom == 8 ) :
              ?>
                  <tr>
                    <td nowrap title="Ve�culo de Publica��o">
                      <strong>Ve�culo de Publica��o:</strong>
                    </td>
                    <td>
                        <?php
                      db_textarea('l20_veicdivulgacao', 0, 50, $Il20_veicdivulgacao, true, 'text', $db_opcao, "");
                      ?>
                    </td>
                  </tr>
              <?php
              endif;
              ?>
                  <tr>
                    <td nowrap title="<?= @$Tl20_tipoprocesso ?>">
                        <?= @$Ll20_tipoprocesso ?>
                    </td>
                    <td>
                        <?php
                        $arr_tipo = array("1" => "Por item", "3" => "Por lote");
                        db_select("l20_tipojulg", $arr_tipo, true, 1);
                        db_input("l20_codtipocom",0, $l20_codtipocom, true,'text',$db_opcao,'style="display:none;"');
                        ?>
                    </td>
                  </tr>

                  <tr>
                    <td nowrap title="Codunidsubanterior">
                      <strong>Codunidsubanterior:</strong>
                    </td>
                    <td>
                        <?php
                      db_input('manutlic_codunidsubanterior', 10, $Imanutlic_codunidsubanterior, true, 'text', 2, "");
                      db_input('manutlic_codunidsubanterior_old', 10, $Imanutlic_codunidsubanterior, true, 'hidden', 2, "");
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="">
                      <strong>N� Processo Anterior:</strong>
                    </td>
                    <td>
                        <?php
                      db_input('manutlic_editalant', 10, $Il20_editalant, true, 'text', 2, "");
                      ?>
                    </td>
                  </tr>

                  <?php

                  $result_dha = $clliclicita->sql_record($clliclicita->sql_query(null, "distinct homologacaoadjudica.*", null, "l20_codigo = $l20_codigo"));


                  for ($i = 0; $i < pg_numrows($result_dha); $i++) :
                    db_fieldsmemory($result_dha, $i);  ?>

                    <tr>
                      <td colspan="2">
                        <fieldset class='fieldsetinterno'>
                          <legend>
                            <b>C�digo: <?php echo $l202_sequencial ?></b>
                          </legend>

                          <table cellpadding="0" border="0" width="100%" class="">



                            <tr>
                              <td nowrap title="Data Homologa��o">
                                <strong>Data Homologa��o: </strong>
                              </td>
                              <td>
                                  <?php
                                db_inputdata("l202_datahomologacao_$l202_sequencial", @$l202_datahomologacao_dia, @$l202_datahomologacao_mes, @$l202_datahomologacao_ano, true, '', $iCampo);
                                ?>
                              </td>
                            </tr>

                            <tr>
                              <td nowrap title="Data Adjudica��o">
                                <strong>Data Adjudica��o: </strong>
                              </td>
                              <td>
                                  <?php
                                db_inputdata("l202_dataadjudicacao_$l202_sequencial", @$l202_dataadjudicacao_dia, @$l202_dataadjudicacao_mes, @$l202_dataadjudicacao_ano, true, '', $iCampo);
                                ?>
                              </td>
                            </tr>

                          </table>
                        </fieldset>
                      </td>
                    </tr>



                  <?php endfor; ?>

            <?php endif; ?>
          </table>
        </fieldset>
        <input name="alterar" type="submit" value="Alterar" <?= ($db_opcao == 2 ? "" : "disabled") ?>>
      </div>
    </form>
    </div>

  <?php
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:400px;
            text-align: left;
            padding:3px;
            z-index:10000;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<script>
  function js_pesquisa_liclicita(mostra) {

    if (mostra) {
      js_OpenJanelaIframe('', 'db_iframe_liclicita', 'func_liclicitamanutencao.php?funcao_js=parent.js_mostraliclicita1|l20_codigo|pc50_pctipocompratribunal', 'Pesquisa', true);
    } else {

      if (document.form1.l20_codigo.value != '') {
        js_OpenJanelaIframe('', 'db_iframe_liclicita', 'func_liclicitamanutencao.php?pesquisa_chave=' + document.form1.l20_codigo.value + '&funcao_js=parent.js_mostraliclicita', 'Pesquisa', false);
      } else {
        document.form1.l20_codigo.value = '';
      }
    }
  }

  function js_mostraliclicita(chave, erro) {

    document.form1.l20_codigo.value = chave;
    if (erro) {
      document.form1.l20_codigo.value = '';
      document.form1.l20_codigo.focus();
    }

      <?php
    echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave;";
    ?>

  }

  function js_mostraliclicita1(chave1, chave2) {

    document.form1.l20_codigo.value = chave1;
    db_iframe_liclicita.hide();

      <?php
    echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave1+'&chavepesquisa2='+chave2";
    ?>

  }
</script>
<?php
  }
?>
