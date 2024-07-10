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
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_parametroscontratos_classe.php");
$lAtivo      = '';
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clacordo = new cl_acordo;
$clparametroscontratos = new cl_parametroscontratos;
$clacordo->rotulo->label();
$iInstituicaoSessao = db_getsession('DB_instit');

$rsParametros = $clparametroscontratos->sql_record($clparametroscontratos->sql_query_file('', '*'));

$pc01_liberaautorizacao = db_utils::fieldsMemory($rsParametros, 0)->pc01_liberaautorizacao;
$pc01_libcontratodepart = db_utils::fieldsMemory($rsParametros, 0)->pc01_libcontratodepart;

function verificaCamposVazios(
    $coddeptoinc,
    $coddeptoresp,
    $ac16_numeroacordo,
    $ac16_acordogrupo,
    $chave_ac16_sequencial
) {
    return empty($coddeptoinc) &&
        empty($coddeptoresp) &&
        empty($ac16_numeroacordo) &&
        empty($coddeptoinc) &&
        empty($ac16_acordogrupo) &&
        empty($chave_ac16_sequencial);
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAncora.widget.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body style="background-color: #CCCCCC">

    <div class="container">
        <form name="form2" method="post" action="">
            <fieldset>
                <legend class="bold">Filtros</legend>

                <table border="0" align="center" cellspacing="0">
                    <tr>
                        <td width="4%" align="left" nowrap title="<?php echo $Tac16_sequencial; ?>">
                            <?php echo $Lac16_sequencial; ?>
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?php
                            db_input("ac16_sequencial", 10, $Iac16_sequencial, true, "text", 4, "", "chave_ac16_sequencial");
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="4%" align="left" nowrap title="<?php echo $Tac16_numeroacordo; ?>" class="bold">
                            N�mero do Acordo:
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?php db_input("ac16_numeroacordo", 10, 0, true, "text", 4); ?>
                        </td>
                    </tr>

                    <tr>
                        <td nowrap title="<?php echo @$Tac16_acordogrupo; ?>" class="bold" align="">
                            <?php
                            db_ancora("Grupo:", "js_pesquisaac16_acordogrupo(true);", 1);
                            ?>
                        </td>
                        <td>
                            <?php
                            db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', 1, "onchange='js_pesquisaac16_acordogrupo(false);'");
                            db_input('ac02_descricao', 30, "", true, 'text', 3);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php db_ancora('Depto. de Inclus�o:', "js_pesquisa_depart(true);", 1); ?>
                        </td>
                        <td>
                            <?php
                            db_input("coddeptoinc", 10, $Icoddepto, true, "text", 4, "style='width: 90px;'onchange='js_pesquisa_depart(false);'");
                            ?>

                            <?php db_input("descrdeptoinc", 50, $Idescrdepto, true, "text", 3); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php db_ancora('Depto. Respons�vel', "js_pesquisa_departamento(true);", 1); ?>
                        </td>
                        <td>
                            <?php
                            db_input('coddeptoresp', 10, '', true, 'text', 4, " style='width: 90px;'onchange='js_pesquisa_departamento(false);'");
                            ?>

                            <?php db_input('descrdeptoresp', 50, '', true, 'text', 3, "", ""); ?>
                        </td>
                    </tr>
                </table>

            </fieldset>
            <p align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acordo.hide();">
            </p>

        </form>
    </div>

    <fieldset style="width: 98%">
        <legend class="bold">Registros</legend>



        <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
            <tr>
                <td align="center" valign="top">
                    <?php

                    $sWhere  = " 1 = 1 ";

                    $sWhere .= " and ac16_instit = {$iInstituicaoSessao} ";


                    if (!isset($lNovoDetalhe)) {

                        if ($pc01_liberaautorizacao <> 't') {
                            if (isset($iTipoFiltro)) {
                                $sWhere .= " and ac16_acordosituacao in ({$iTipoFiltro})";
                            }
                        }
                        if (isset($ac16_acordosituacao)) {
                            $sWhere .= " and ac16_acordosituacao = $ac16_acordosituacao";
                        }

                        if (isset($lParaExcluir) && $lParaExcluir == true) {
                            $sWhere .= "
              AND NOT EXISTS
              (SELECT
              acordo_sub.ac16_sequencial,
              empautoriza.e54_autori,
              empautoriza.e54_anulad
              FROM acordo acordo_sub
              INNER JOIN acordoposicao                  ON acordoposicao.ac26_acordo = acordo_sub.ac16_sequencial
              INNER JOIN acordoitem                     ON acordoitem.ac20_acordoposicao = acordoposicao.ac26_sequencial
              INNER JOIN acordoitemexecutado            ON acordoitemexecutado.ac29_acordoitem = acordoitem.ac20_sequencial
              INNER JOIN acordoitemexecutadoempautitem  ON acordoitemexecutadoempautitem.ac19_acordoitemexecutado = acordoitemexecutado.ac29_sequencial
              INNER JOIN empautitem                     ON empautitem.e55_autori = acordoitemexecutadoempautitem.ac19_autori AND empautitem.e55_sequen = acordoitemexecutadoempautitem.ac19_sequen
              INNER JOIN empautoriza                    ON empautoriza.e54_autori = empautitem.e55_autori
              WHERE
              ac16_sequencial = acordo.ac16_sequencial
              AND e54_anulad IS NULL
              )
              ";
                        }

                        if (!empty($lLancamento)) {
                            $sWhere .= " and exists (select 1 from conlancamacordo where c87_acordo = ac16_sequencial limit 1) ";
                        }
                        //verifica se foi o aco4_homologacaoinclusao001 que chamou
                        if (isset($frame) && $frame == "homologacao") {


                            if (!empty($assinatura) && $assinatura == true) {

                                $sWhere .= " and (acordo.ac16_dataassinatura IS NOT NULL) ";
                            }
                        } else if (!empty($assinatura) && $assinatura == true) {
                            $sWhere .= " and (acordo.ac16_dataassinatura IS NULL) ";
                        }

                        /**
                         * Caso tenha sido setado $lComExecucao como false, buscamos os acordos que nao tiveram item executado
                         */
                        if (isset($lComExecucao) && $lComExecucao == 'false') {

                            $sWhere .= " and not exists (select 1 from acordoitemexecutadoperiodo     aitemexecutadoperiodo
            inner join acordoitemexecutado aitemexecutado on aitemexecutado.ac29_sequencial = aitemexecutadoperiodo.ac38_acordoitemexecutado
            inner join acordoitemprevisao  aitemprevisao  on aitemprevisao.ac37_sequencial  = aitemexecutadoperiodo.ac38_acordoitemprevisao
            inner join acordoitem          aitem          on aitem.ac20_sequencial          = aitemprevisao.ac37_acordoitem
            where aitemprevisao.ac37_acordoitem = acordoitem.ac20_sequencial
            )";
                        }

                        if (isset($lAtivo)) {

                            if ($lAtivo == 1) {
                                $sWhere .= " and (ac16_acordosituacao = 1 OR ac16_origem = 6)";
                            } else if ($lAtivo == 2) {
                                $sWhere .= " and (ac16_acordosituacao != 1 OR ac16_origem = 6)";
                            }
                        }

                        if (isset($Homologados)) {
                            $sWhere .= " and ac16_acordosituacao = 4";
                        }

                        if (isset($lGeraAutorizacao) && $lGeraAutorizacao == "true") {
                            $sWhere .= " and ac16_origem in(1, 2, 3, 6) ";
                        }

                        if (isset($sListaOrigens) && !empty($sListaOrigens)) {
                            $sWhere .= " and ac16_origem in({$sListaOrigens}) ";
                        }

                        /**
                         * Pesquisa por tipo de movimento
                         */
                        if (isset($iTipo) && !empty($iTipo)) {
                            $sWhere .= " and ac10_acordomovimentacaotipo = {$iTipo} ";
                        }

                        if (!empty($aditamentos)) {
                            $sWhere .= " AND ac26_acordoposicaotipo in (2, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14) ";
                        }

                        if (!empty($apostilamento)) {
                            $sWhere .= " AND ac26_acordoposicaotipo NOT IN (2, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14) ";
                        }
                    }

                    if (!isset($pesquisa_chave)) {

                        if (isset($campos) == false) {

                            if (file_exists("funcoes/db_func_acordo.php") == true) {

                                $campos  = "distinct acordo.ac16_sequencial, ";
                                $campos .= "CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ";
                                $campos .= "ac17_descricao as dl_Situa��o, ";
                                $campos .= "acordo.ac16_contratado, ";
                                $campos .= "cgm.z01_nome, ";
                                $campos .= "acordo.ac16_resumoobjeto::text, ";
                                $campos .= "acordo.ac16_valor, ";
                                $campos .= "acordo.ac16_dataassinatura, ";
                                $campos .= "CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, ";
                                $campos .= "CASE WHEN ac16_vigenciaindeterminada='t' THEN null WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, ";
                                $campos .= "CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, ";
                                $campos .= "db_depart.descrdepto as dl_Dpto_de_Inclusao, ";
                                $campos .= "responsavel.descrdepto as dl_Dpto_Responsavel";
                            } else {
                                $campos = "acordo.*";
                            }
                        }

                        /**
                         * Numero e ano do acordo - separados por '/', caso nao for informado ano, pega da sessao
                         */
                        if (isset($semvigencia) && $semvigencia == false) {
                            $sWhere .= " and ac16_semvigencia = 'f' ";
                        }

                        if (!empty($frame) && $frame == "homologacao") {


                            if (!empty($assinatura) && $assinatura == true) {

                                $sWhere .= " and (acordo.ac16_dataassinatura IS NOT NULL) ";
                            }
                        }
                        if (!empty($assinatura) && $assinatura == false) {

                            $sWhere .= " and (acordo.ac16_dataassinatura IS NULL) ";
                        }
                        if (!empty($ac16_numeroacordo)) {

                            $aNumeroAcordo = explode('/', $ac16_numeroacordo);
                            $iNumero = $aNumeroAcordo[0];
                            $iAno    = !empty($aNumeroAcordo[1]) ? $aNumeroAcordo[1] : db_getsession("DB_anousu");
                            $sWhere  = "ac16_numeroacordo = '{$iNumero}' and ac16_anousu = '{$iAno}' and ac16_instit = {$iInstituicaoSessao}";

                            if (!empty($sDepartamentos)) {
                                $sWhere .= "and {$sDepartamentos}";
                            }
                        }

                        $sql_departamentos = "";

                        if (isset($coddeptoinc) && (trim($coddeptoinc) != "")) {
                            $sql_departamentos = "and ac16_coddepto = {$coddeptoinc}";
                        }

                        if (isset($coddeptoresp) && (trim($coddeptoresp) != "")) {
                            $sql_departamentos .= "and ac16_deptoresponsavel = {$coddeptoresp}";
                        }

                        if (isset($chave_ac16_sequencial) && (trim($chave_ac16_sequencial) != "")) {

                            $sql = $clacordo->sql_query_acordoitemexecutado(
                                null,
                                $campos,
                                "ac16_sequencial desc",
                                "ac16_sequencial = {$chave_ac16_sequencial} and $sWhere and ac16_instit = {$iInstituicaoSessao} {$sql_departamentos}",
                                $apostilamento
                            );
                        } else if (isset($ac16_acordogrupo) && (trim($ac16_acordogrupo) != "")) {

                            $sql = $clacordo->sql_query_acordoitemexecutado(
                                "",
                                $campos,
                                "ac16_sequencial desc",
                                "ac16_acordogrupo = '{$ac16_acordogrupo}' and {$sWhere} and ac16_instit = {$iInstituicaoSessao} {$sql_departamentos}",
                                $apostilamento
                            );
                        } else {
                            $sql = $clacordo->sql_query_acordoitemexecutado("", $campos, "ac16_sequencial desc", $sWhere . " and ac16_instit = {$iInstituicaoSessao} {$sql_departamentos} ", $apostilamento);
                        }

                        if (
                            $geraAutorizacao == true &&
                            verificaCamposVazios(
                                $coddeptoinc,
                                $coddeptoresp,
                                $ac16_numeroacordo,
                                $ac16_acordogrupo,
                                $chave_ac16_sequencial
                            )
                        ) {
                            $sql = "select distinct acordo.ac16_sequencial, CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ac17_descricao as dl_Situa��o, acordo.ac16_contratado, cgm.z01_nome, acordo.ac16_resumoobjeto::text, acordo.ac16_valor, acordo.ac16_dataassinatura, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, db_depart.descrdepto as dl_Dpto_de_Inclusao, responsavel.descrdepto as dl_Dpto_Responsavel from acordo inner join cgm on cgm.z01_numcgm = acordo.ac16_contratado inner join db_depart on db_depart.coddepto = acordo.ac16_coddepto inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel  inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao  where 1 = 1 and (select ac18_datafim from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial desc limit 1)) >= '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' and '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' >= (select ac18_datainicio from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial limit 1)) and ac16_instit = $iInstituicaoSessao and ac16_acordosituacao in (4) and ac16_origem in(1, 2, 3, 6)  order by ac16_sequencial desc";
                            $rsParametros = db_query("select * from parametroscontratos;");
                            db_fieldsmemory($rsParametros, 0);
                            if ($pc01_liberaautorizacao == 't') {
                                $sql = "select distinct acordo.ac16_sequencial, CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ac17_descricao as dl_Situa��o, acordo.ac16_contratado, cgm.z01_nome, acordo.ac16_resumoobjeto::text, acordo.ac16_valor, acordo.ac16_dataassinatura, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, db_depart.descrdepto as dl_Dpto_de_Inclusao, responsavel.descrdepto as dl_Dpto_Responsavel from acordo inner join cgm on cgm.z01_numcgm = acordo.ac16_contratado inner join db_depart on db_depart.coddepto = acordo.ac16_coddepto inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel  inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao  where 1 = 1 and ac16_instit = $iInstituicaoSessao and ac16_acordosituacao in (1) and ac16_origem in(1, 2, 3, 6)  ";
                                $sql .= "union select distinct acordo.ac16_sequencial, CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ac17_descricao as dl_Situa��o, acordo.ac16_contratado, cgm.z01_nome, acordo.ac16_resumoobjeto::text, acordo.ac16_valor, acordo.ac16_dataassinatura, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, db_depart.descrdepto as dl_Dpto_de_Inclusao, responsavel.descrdepto as dl_Dpto_Responsavel from acordo inner join cgm on cgm.z01_numcgm = acordo.ac16_contratado inner join db_depart on db_depart.coddepto = acordo.ac16_coddepto inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel  inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao  where 1 = 1 and (select ac18_datafim from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial desc limit 1)) >= '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' and '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' >= (select ac18_datainicio from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial limit 1)) and ac16_instit = $iInstituicaoSessao and ac16_acordosituacao in (4) and ac16_origem in(1, 2, 3, 6)  order by ac16_sequencial desc";
                            }
                        }

                        $repassa = array();

                        if (isset($chave_ac16_sequencial)) {
                            $repassa = array("chave_ac16_sequencial" => $chave_ac16_sequencial, "chave_ac16_sequencial" => $chave_ac16_sequencial);
                        }
                        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
                    } else {

                        if (isset($semvigencia) && $semvigencia == false) {
                            $sWhere .= " and ac16_semvigencia = 'f' ";
                        }
                        if ($pesquisa_chave != null && $pesquisa_chave != "") {

                            $sSqlBuscaAcordo = $clacordo->sql_query_acordoitemexecutado(
                                null,
                                "*",
                                null,
                                "ac16_sequencial = {$pesquisa_chave}
              and {$sWhere}",
                                $apostilamento
                            );

                            if ($geraAutorizacao == true) {
                                $sSqlBuscaAcordo = "select distinct acordo.ac16_sequencial, CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ac17_descricao as dl_Situa��o, acordo.ac16_contratado, cgm.z01_nome, acordo.ac16_resumoobjeto::text, acordo.ac16_valor, acordo.ac16_dataassinatura, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, db_depart.descrdepto as dl_Dpto_de_Inclusao, responsavel.descrdepto as dl_Dpto_Responsavel from acordo inner join cgm on cgm.z01_numcgm = acordo.ac16_contratado inner join db_depart on db_depart.coddepto = acordo.ac16_coddepto inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao where 1 = 1 and (select ac18_datafim from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial desc limit 1)) >= '"  .  date("d-m-Y", db_getsession('DB_datausu')) . "' and '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' >= (select ac18_datainicio from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial limit 1)) and ac16_sequencial = $pesquisa_chave and ac16_instit = $iInstituicaoSessao and ac16_acordosituacao in (4) and ac16_origem in(1, 2, 3, 6) order by ac16_sequencial desc";
                                $rsParametros = db_query("select * from parametroscontratos;");
                                db_fieldsmemory($rsParametros, 0);
                                if ($pc01_liberaautorizacao == 't') {
                                    $sSqlBuscaAcordo = "select distinct acordo.ac16_sequencial, CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ac17_descricao as dl_Situa��o, acordo.ac16_contratado, cgm.z01_nome, acordo.ac16_resumoobjeto::text, acordo.ac16_valor, acordo.ac16_dataassinatura, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, db_depart.descrdepto as dl_Dpto_de_Inclusao, responsavel.descrdepto as dl_Dpto_Responsavel from acordo inner join cgm on cgm.z01_numcgm = acordo.ac16_contratado inner join db_depart on db_depart.coddepto = acordo.ac16_coddepto inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel  inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao  where 1 = 1 and ac16_instit = $iInstituicaoSessao and ac16_acordosituacao in (1) and ac16_origem in(1, 2, 3, 6) and  ac16_sequencial = $pesquisa_chave ";
                                    $sSqlBuscaAcordo .= "union select distinct acordo.ac16_sequencial, CASE WHEN ac16_semvigencia='t' THEN ('-')::varchar ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar END dl_N�_Acordo, ac17_descricao as dl_Situa��o, acordo.ac16_contratado, cgm.z01_nome, acordo.ac16_resumoobjeto::text, acordo.ac16_valor, acordo.ac16_dataassinatura, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datainicio END ac16_datainicio, CASE WHEN ac16_semvigencia='t' THEN null ELSE ac16_datafim END ac16_datafim, CASE WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras' WHEN acordo.ac16_origem = 2 THEN 'Licita��o' ELSE 'Manual' END ac16_origem, db_depart.descrdepto as dl_Dpto_de_Inclusao, responsavel.descrdepto as dl_Dpto_Responsavel from acordo inner join cgm on cgm.z01_numcgm = acordo.ac16_contratado inner join db_depart on db_depart.coddepto = acordo.ac16_coddepto inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel  inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao  where 1 = 1 and (select ac18_datafim from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial desc limit 1)) >= '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' and '" .  date("d-m-Y", db_getsession('DB_datausu')) . "' >= (select ac18_datainicio from acordovigencia where ac18_acordoposicao = (select ac26_sequencial from acordoposicao where ac26_acordo = ac16_sequencial order by ac26_sequencial limit 1)) and ac16_instit = $iInstituicaoSessao and ac16_acordosituacao in (4) and ac16_origem in(1, 2, 3, 6) and ac16_sequencial = $pesquisa_chave  order by ac16_sequencial desc";
                                }
                            }

                            $result = $clacordo->sql_record($sSqlBuscaAcordo);

                            if ($clacordo->numrows != 0) {

                                db_fieldsmemory($result, 0);
                                if (isset($descricao) && $descricao == 'true') {
                                    if (isset($lGeraAutorizacao)) {
                                        echo "<script>" . $funcao_js . "('$ac16_sequencial','$ac16_resumoobjeto','$z01_nome','$ac16_contratado');</script>";
                                        exit;
                                    }
                                    echo "<script>" . $funcao_js . "('$ac16_sequencial','$ac16_resumoobjeto','$ac16_origem',false,'$ac16_licitacao');</script>";
                                } else {

                                    echo "<script>" . $funcao_js . "('$ac16_sequencial',false);</script>";
                                }
                            } else {

                                if (isset($descricao) && $descricao == 'true') {

                                    if (!empty($frame) && $frame == "homologacao") {
                                        echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") n�o Encontrado','','',true);</script>";
                                    } else {
                                        echo "<script>" . $funcao_js . "('','Chave(" . $pesquisa_chave . ") n�o Encontrado','',true);</script>";
                                    }
                                } else {
                                    echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") n�o Encontrado',true);</script>";
                                }
                            }
                        } else {

                            if (isset($descricao) && $descricao == 'true') {
                                echo "<script>" . $funcao_js . "('','',false);</script>";
                            } else {
                                echo "<script>" . $funcao_js . "('',false);</script>";
                            }
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
</body>

</html>
<?php
if (!isset($pesquisa_chave)) {
?>
    <script>
    </script>
<?
}
?>
<script>
    /**
     * Formata numero / ano de um elemento html
     *
     * @param {object} elemento
     * @returns {void}
     */
    function js_formatarNumeroAno(elemento) {

        /**
         * Formata a cada tecla digitada
         *
         * @param {object} elemento
         * @returns {boolean}
         */
        elemento.onkeypress = function(event) {

            var iTecla = event.keyCode ? event.keyCode : event.charCode;
            var sTecla = String.fromCharCode(iTecla);

            /**
             * Nao permite por 2x '/' ou como primeiro caracter
             */
            if (sTecla == '/') {

                if (this.value.indexOf('/') !== -1 || this.value == '') {
                    return false;
                }
            }

            return js_mask(event, "0-9|/");
        };
    }

    js_formatarNumeroAno(document.getElementById('ac16_numeroacordo'));

    js_tabulacaoforms("form2", "chave_ac16_sequencial", true, 1, "chave_ac16_sequencial", true);

    function js_pesquisaac16_acordogrupo(mostra) {

        if (mostra == true) {

            var sUrl = 'func_acordogrupo.php?funcao_js=parent.js_mostraacordogrupo1|ac02_sequencial|ac02_descricao';
            js_OpenJanelaIframe('',
                'db_iframe_acordogrupo',
                sUrl,
                'Pesquisa de Grupo de Acordo',
                true,
                '0');

        } else {

            if ($('ac16_acordogrupo').value != '') {

                js_OpenJanelaIframe('',
                    'db_iframe_acordogrupo',
                    'func_acordogrupo.php?pesquisa_chave=' + $('ac16_acordogrupo').value +
                    '&funcao_js=parent.js_mostraacordogrupo',
                    'Pesquisa de Grupo de Acordo',
                    false,
                    '0');
            } else {
                $('ac02_sequencial').value = '';
            }
        }
    }

    function js_mostraacordogrupo(chave, erro) {

        $('ac02_descricao').value = chave;
        if (erro == true) {

            $('ac16_acordogrupo').focus();
            $('ac16_acordogrupo').value = '';
        }
    }

    function js_mostraacordogrupo1(chave1, chave2) {

        $('ac16_acordogrupo').value = chave1;
        $('ac02_descricao').value = chave2;
        $('ac16_acordogrupo').focus();

        db_iframe_acordogrupo.hide();
    }

    function js_pesquisa_depart(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_db_depart', 'func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto', 'Pesquisa', true);
        } else {
            if (document.form1.coddeptoinc.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_db_depart', 'func_db_depart.php?pesquisa_chave=' + document.form1.coddeptoinc.value + '&funcao_js=parent.js_mostradepart', 'Pesquisa', false);
            } else {
                $('descrdeptoinc').value = '';
            }
        }
    }

    function js_mostradepart(chave, erro) {
        $('descrdeptoinc').value = chave;
        if (erro == true) {
            $('coddeptoinc').focus();
            $('coddeptoinc').value = '';
        }
    }

    function js_mostradepart1(chave1, chave2) {
        $('coddeptoinc').value = chave1;
        $('descrdeptoinc').value = chave2;
        db_iframe_db_depart.hide();
    }

    function js_pesquisa_departamento(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_departamento', 'func_departamento.php?funcao_js=parent.js_mostradepartamento1|coddepto|descrdepto', 'Pesquisa', true);
        } else {
            if ($('coddeptoresp').value != '') {
                js_OpenJanelaIframe('', 'db_iframe_departamento', 'func_departamento.php?pesquisa_chave=' + $('coddeptoresp').value + '&funcao_js=parent.js_mostradepartamento', 'Pesquisa', false);
            } else {
                $('descrdeptoresp').value = '';
            }
        }
    }

    function js_mostradepartamento1(chave1, chave2, erro) {
        $('coddeptoresp').value = chave1;
        $('descrdeptoresp').value = chave2;
        db_iframe_departamento.hide();
    }

    function js_mostradepartamento(chave1, erro) {
        if (!erro) {
            $('descrdeptoresp').value = chave1;
        }
        db_iframe_departamento.hide();
    }
</script>