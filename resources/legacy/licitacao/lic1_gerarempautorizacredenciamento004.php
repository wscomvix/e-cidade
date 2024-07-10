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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_empautpresta_classe.php");
require_once("classes/db_empprestatip_classe.php");
require_once("classes/db_empautoriza_classe.php");
require_once("classes/db_empauthist_classe.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_empautidot_classe.php");
require_once("classes/db_emphist_classe.php");
require_once("classes/db_emptipo_classe.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_concarpeculiar_classe.php");
require_once("classes/db_credenciamentotermo_classe.php");

require_once("model/CgmFactory.model.php");
require_once("model/fornecedor.model.php");

require_once("classes/db_empautorizaprocesso_classe.php");

db_postmemory($HTTP_POST_VARS);

$clempautpresta                   = new cl_empautpresta;
$clempprestatip                   = new cl_empprestatip;
$clempautoriza                    = new cl_empautoriza;
$clempauthist                     = new cl_empauthist;
$clemphist                        = new cl_emphist;
$clempautitem                     = new cl_empautitem;
$clempautidot                     = new cl_empautidot;
$clemptipo                        = new cl_emptipo;
$clcflicita                       = new cl_cflicita;
$clpctipocompra                   = new cl_pctipocompra;
$clempparametro                   = new cl_empparametro;
$clpcparam                           = new cl_pcparam;
$clconcarpeculiar                 = new cl_concarpeculiar;
$oDaoEmpenhoProcessoAdminitrativo = new cl_empautorizaprocesso;
$clcredenciamentotermo            = new cl_credenciamentotermo;

$db_opcao    = 1;
$db_botao    = true;
$sUrlEmpenho = "emp1_empempenho001.php";
$iAnoUsu     = db_getsession("DB_anousu");
$iAnoData    = date("Y", db_getsession("DB_datausu"));
$rsEmpParam  = $clempparametro->sql_record($clempparametro->sql_query($iAnoUsu));
if ($clempparametro->numrows > 0) {
    db_fieldsmemory($rsEmpParam, 0);
    if ($e30_notaliquidacao != '') {
        $sUrlEmpenho = "emp4_empempenho001.php";
    }
}
if (isset($incluir)) {

    try {

        $oFornecedor = new fornecedor($e54_numcgm);
        $oFornecedor->verificaBloqueioAutorizacaoEmpenho(null);
        $iStatusBloqueio = $oFornecedor->getStatusBloqueio();
    } catch (Exception $eException) {

        $sqlerro  = true;
        $erro_msg = $eException->getMessage();
    }

    if ($iStatusBloqueio == 2) {

        $erro_msg  = "\\nusu�rio:\\n\\n Fornecedor com d�bito na prefeitura !\\n\\n\\n\\n";
        db_msgbox($erro_msg);
    }
}

if (isset($incluir)) {
    $sqlerro = false;

    if ($sqlerro == false) {

        $res_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_fornecdeb"));
        if ($clpcparam->numrows > 0) {
            db_fieldsmemory($res_pcparam, 0);

            $sql_fornec     = "select * from fc_tipocertidao($e54_numcgm,'c','" . date("Y-m-d", db_getsession("DB_datausu")) . "','') as retorno";
            $res_fornec     = @db_query($sql_fornec);
            $numrows_fornec = @pg_numrows($res_fornec);

            if ($numrows_fornec > 0) {
                db_fieldsmemory($res_fornec, 0);
            }

            if (isset($retorno) && $retorno == "positiva") {

                if ($pc30_fornecdeb == 3 && $iStatusBloqueio != 1) {
                    $clempautoriza->erro_campo = "e54_numcgm";
                    $erro_msg = "Fornecedor com debito";
                    $sqlerro  = true;
                }
            }
        }
    }

    if ($sqlerro == false) {
        $resultVigencia = $clcredenciamentotermo->sql_record($clcredenciamentotermo->sql_query(null, "l212_dtinicio,l212_dtfim,l212_numerotermo", null, "l212_sequencial = $l212_sequencial"));
        db_fieldsmemory($resultVigencia, 0);
        $dataini = (implode("/", (array_reverse(explode("-", $l212_dtinicio)))));
        $datafim = (implode("/", (array_reverse(explode("-", $l212_dtfim)))));
        $datesistema = date("d/m/Y", db_getsession("DB_datausu"));

        $dataInicio = DateTime::createFromFormat('d/m/Y', $dataini);
        $dataFim = DateTime::createFromFormat('d/m/Y', $datafim);
        $dataGen = DateTime::createFromFormat('d/m/Y', $datesistema);

        if ($dataGen < $dataInicio) {
            $sqlerro = true;
            $erro_msg = "Usu�rio: N�o � poss�vel gerar autoriza��o de empenho, Termo $l212_numerotermo ainda n�o est� vigente";
        }

        if ($dataGen > $dataFim) {
            $sqlerro = true;
            $erro_msg = "Usu�rio: N�o � poss�vel gerar autoriza��o de empenho, Termo $l212_numerotermo n�o est� vigente.";
        }
    }

    // Valida Ano da Sessao e da Data da Sessao... devem ser a mesma
    if ($sqlerro == false) {

        if ((int)$iAnoUsu <> (int)$iAnoData) {

            $sDataUsu = date("d/m/Y", db_getsession("DB_datausu"));
            $erro_msg = "ERRO: Ano da Sess�o ($iAnoUsu) diferente do Ano da Data Atual ($sDataUsu)!";
            $sqlerro  = true;
        }
    }

    db_inicio_transacao();

    if (!$sqlerro) {

        $clempautoriza->e54_autori  = $e54_autori;
        $clempautoriza->e54_numcgm  = $e54_numcgm;
        $clempautoriza->e54_login   = db_getsession("DB_id_usuario");
        $clempautoriza->e54_resumo  = $e54_resumo;
        $clempautoriza->e54_anousu  = $iAnoUsu;
        $clempautoriza->e54_codlicitacao = $e54_codlicitacao;
        $clempautoriza->e54_codcom  = $e54_codcom;
        $clempautoriza->e54_destin  = $e54_destin;
        $clempautoriza->e54_tipol   = $e54_tipol;
        $clempautoriza->e54_numerl  = $e54_numerll;
        $clempautoriza->e54_nummodalidade  = $e54_nummodalidade;
        $clempautoriza->e54_tipoautorizacao = 3;
        $clempautoriza->e54_tipoorigem = 2;
        $clempautoriza->e54_emiss   = date("Y-m-d", db_getsession("DB_datausu"));
        $clempautoriza->e54_codtipo = $e54_codtipo;
        $clempautoriza->e54_instit  = db_getsession("DB_instit");
        $clempautoriza->e54_depto   = db_getsession("DB_coddepto");

        $clempautoriza->e54_valor  = '';
        $clempautoriza->e54_praent = '';
        $clempautoriza->e54_entpar = '';
        $clempautoriza->e54_conpag = '';
        $clempautoriza->e54_codout = '';
        $clempautoriza->e54_contat = '';
        $clempautoriza->e54_telef  = '';
        $clempautoriza->e54_numsol = '';
        $clempautoriza->e54_anulad = null;
        $clempautoriza->e54_concarpeculiar = $e54_concarpeculiar;
        $clempautoriza->e54_tipodespesa = $e54_tipodespesa;
        $clempautoriza->e54_numerotermo = $e54_numerotermo;


        $clempautoriza->incluir(null);
        if ($clempautoriza->erro_status == 0) {

            $sqlerro = true;
            $erro_msg = $clempautoriza->erro_msg;
        } else {
            $ok_msg   = $clempautoriza->erro_msg;
        }
        $e54_autori = $clempautoriza->e54_autori;
    }

    if (!$sqlerro) {
        $clempauthist->e57_autori  = $e54_autori;
        $clempauthist->e57_codhist = $e57_codhist;
        $clempauthist->incluir($e54_autori);
        $erro_msg = $clempauthist->erro_msg;
        if ($clempauthist->erro_status == 0) {
            $sqlerro = true;
        }
    }

    if (isset($e44_tipo) && $e44_tipo != "") {
        $result = $clempprestatip->sql_record($clempprestatip->sql_query_file($e44_tipo, "e44_obriga"));
        db_fieldsmemory($result, 0);
        if ($e44_obriga != 0) {
            $clempautpresta->e58_autori = $e54_autori;
            $clempautpresta->e58_tipo   = $e44_tipo;
            $clempautpresta->incluir();
            if ($clempautpresta->erro_status == 0) {
                $sqlerro = true;
            }
        }
    }

    /**
     * Inclui processo administrativo em empautorizaprocesso
     */
    if (!$sqlerro && isset($e150_numeroprocesso) && !empty($e150_numeroprocesso)) {

        $oDaoEmpenhoProcessoAdminitrativo->e150_numeroprocesso = $e150_numeroprocesso;
        $oDaoEmpenhoProcessoAdminitrativo->e150_empautoriza    = $e54_autori;
        $oDaoEmpenhoProcessoAdminitrativo->incluir(null);

        if ($oDaoEmpenhoProcessoAdminitrativo->erro_status == 0) {

            $sqlerro  = true;
            $erro_msg = $oDaoEmpenhoProcessoAdminitrativo->erro_msg;
        }
    }


    db_fim_transacao($sqlerro);
} else if (isset($pesq_ult) && $pesq_ult == true) {
    $result_ultalt = $clempautoriza->sql_record($clempautoriza->sql_query(null, "e54_codlicitacao ,e54_numcgm  ,z01_nome  ,e54_login   ,e54_codcom  ,e54_destin  ,e54_valor   ,e54_anousu  ,e54_tipol   ,e54_numerl  ,e54_praent  ,e54_entpar  ,e54_conpag  ,e54_codout  ,e54_contat  ,e54_telef   ,e54_numsol  ,e54_anulad  ,e54_emiss   ,e54_resumo  ,e54_codtipo ,e54_instit  ,e54_depto", "e54_autori desc limit 1", "e54_instit =" . db_getsession("DB_instit") . " and e54_login=" . db_getsession("DB_id_usuario")));
    if ($clempautoriza->numrows > 0) {
        db_fieldsmemory($result_ultalt, 0);
    }
}
$pctipocompras  = db_query("
                      select pc50_codcom, pc50_descr from pctipocompra order by pc50_codcom
                  ");
$aPctipocompras = db_utils::getCollectionByRecord($pctipocompras);

$emptipos  = db_query("
                      select e41_codtipo, e41_descr from emptipo order by e41_codtipo
                  ");
$aEmptipos = db_utils::getCollectionByRecord($emptipos);

$cflicitas  = db_query("
                    select l03_codigo, l03_descr, l03_tipo from cflicita order by l03_codigo
                  ");
$aCflicitas = db_utils::getCollectionByRecord($cflicitas);
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js,
                prototype.js,
                widgets/windowAux.widget.js,
                strings.js,
                widgets/dbtextField.widget.js,
                dbViewNotificaFornecedor.js,
                dbmessageBoard.widget.js,
                dbautocomplete.widget.js,
                dbcomboBox.widget.js,
                datagrid.widget.js,
                widgets/dbtextFieldData.widget.js,
                DBFormCache.js,
                estilos.css,
                grid.style.css
  ");
    ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

    <center>
        <div style="margin-top: 20px; width: 700px; ">
            <?
            include("forms/db_frmempautorizacredenciamento.php");
            ?>
        </div>
    </center>
</body>

</html>
<?
if (isset($incluir)) {
    if ($sqlerro == true) {
        db_msgbox($erro_msg);
        echo "<script>
        var codlicitacao = document.form1.e54_codlicitacao.value;
        js_pesquisa_fornecedor(codlicitacao);
    </script>";
        $db_botao = true;
        if (isset($incluir) && $clempautoriza->erro_campo != "") {
            echo "<script> document.form1." . $clempautoriza->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clempautoriza->erro_campo . ".focus();</script>";
        }
    } else {
        //db_msgbox($ok_msg);
        echo "
      <script>
        parent.mo_camada('empautitem');
	    </script>
         ";
        db_redireciona("lic1_gerarempautorizacedrenciamento005.php?chavepesquisa=$e54_autori&fornecedor=$e54_numcgm&licitacao=$e54_codlicitacao");
    }
}
?>