<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/FileException.php");
require_once("libs/exceptions/ParameterException.php");
require_once("classes/db_conciliacaobancaria_classe.php");
require_once("classes/db_conciliacaobancarialancamento_classe.php");
require_once("classes/db_caiparametro_classe.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';
$sCaminhoMensagens = 'financeiro.caixa.cai4_concbancnovo';

try {
    switch ($oParam->exec) {
        case "getLancamentos":
            $oRetorno->aLinhasExtrato = array();
            $data_inicial = data($oParam->params[0]->data_inicial);
            $data_final = data($oParam->params[0]->data_final);
            $condicao_lancamento = condicao_lancamento($oParam->params[0]->tipo_lancamento);
            $sql = query_lancamentos($oParam->params[0]->conta, $data_inicial, $data_final, $condicao_lancamento, $oParam->params[0]->tipo_lancamento);
            // $oRetorno->aLinhasExtrato[] = $sql;
            $resultado   = db_query($sql);
            $rows        = pg_numrows($resultado);
            $lancamentos = array();
            $agrupado    = array();
            $movimentacao_permitida = $oParam->params[0]->tipo_movimento ? array($oParam->params[0]->tipo_movimento) : array("E", "S");
            $i = 0;
            $diferenciador = 1;
            for ($linha = 0; $linha < $rows; $linha++) {
                db_fieldsmemory($resultado, $linha);
                $movimento = ($valor_debito > 0 or $valor_credito < 0) ? "E" : "S";
                $movimento = ($cod_doc == 116 or $cod_doc == 418 or $cod_doc == 122) ? "E" : $movimento;

                if (in_array($movimento, $movimentacao_permitida)) {
                    $cod_doc = ($cod_doc == 116 or $cod_doc == 418 or $cod_doc == 122) ? 100 : $cod_doc;

                    $documento = numero_documento_lancamento($tipo, $ordem, $codigo);
                    if ($tipo_lancamento == 0) {
                        if (in_array($tipo, array("OP")))
                            $chave = $credor . $data . $data_conciliacao . $movimento . trim($cheque) . $cod_doc;
                        else if ($tipo == "SLIP") {
                            if (in_array($cod_doc, array("140", "141"))) {
                                $chave = $credor . $data . $data_conciliacao . $codigo . $movimento . trim($cheque) . $cod_doc;
                            } else {
                                $chave = $credor . $data . $data_conciliacao . $movimento . trim($cheque) . $cod_doc;
                            }
                        } else if ($tipo == "SLIP") {
                            $chave = $credor . $data . $data_conciliacao . $documento . $movimento . $cod_doc;
                        } else
                            $chave = $credor . $data . $data_conciliacao . $documento . $movimento . $cod_doc;
                    } else {
                        $chave = $credor . $data . $data_conciliacao . $documento . $movimento . $cod_doc . $diferenciador;
                        $diferenciador++;
                    }
                    $valor = $valor_debito <> 0 ? abs($valor_debito) : abs($valor_credito);
                    // $chave = $i++;
                    if ($codigo){
                        
                        $sql = "select k17_numdocumento from slip
                        where k17_codigo = $codigo";
                        $numdocumento = db_query($sql);
                        if (pg_numrows($numdocumento)>0){
                            db_fieldsmemory($numdocumento,0);
                        }
                    }
                   
                    $agrupado[$chave][] = array(
                        "identificador" => $caixa,
                        "data_lancamento" => date("d/m/Y", strtotime($data)),
                        "data_conciliacao" => $data_conciliacao ? date("d/m/Y", strtotime($data_conciliacao)) : "",
                        "credor" => utf8_encode($credor),
                        "numcgm" => $numcgm,
                        "tipo" => descricaoTipoLancamento($cod_doc),
                        "op_rec_slip" => !$documento ? "" : $documento,
                        "documento" => trim($cheque),
                        "numdocumento" => $k17_numdocumento,
                        "movimento" => $movimento,
                        "valor_individual" => $valor,
                        "valor" => $valor,
                        "historico" => descricaoHistorico($tipo, $codigo, $historico),
                        "cod_doc" => $cod_doc
                    );

                    if (!array_key_exists($chave, $lancamentos)) {
                        $lancamentos[$chave]["identificador"] = $caixa;
                        $lancamentos[$chave]["tipo_lancamento"] = $tipo_lancamento;
                        $lancamentos[$chave]["data_lancamento"] = date("d/m/Y", strtotime($data));
                        $lancamentos[$chave]["data_conciliacao"] = $data_conciliacao ? date("d/m/Y", strtotime($data_conciliacao)) : "";
                        $lancamentos[$chave]["credor"] = $credor;
                        $lancamentos[$chave]["tipo"] = $tipo_lancamento == 2 ? utf8_encode("PEND�NCIA") : descricaoTipoLancamento($cod_doc);
                        $lancamentos[$chave]["op_rec_slip"][] = !$documento ? "" : $documento;
                        $lancamentos[$chave]["numdocumento"][] = $k17_numdocumento;
                        $lancamentos[$chave]["documento"][] = trim($cheque);
                        $lancamentos[$chave]["movimento"] = $movimento;
                        $lancamentos[$chave]["valor"] = $valor_debito <> 0 ? $valor_debito : $valor_credito;
                        $lancamentos[$chave]["valor_individual"][] = $valor_debito <> 0 ? $valor_debito : $valor_credito;
                        if ($tipo_lancamento == 0) {
                            if ($tipo == "REC") {
                                $lancamentos[$chave]["historico"] = "<a href='#' onclick='js_janelaPlanilhaDetalhada(" . json_encode(dados_planilha($oParam->params[0]->conta, $data, $codigo, $cod_doc)) . ")'>" . descricaoHistorico($tipo, $codigo, $historico) . "</a>";
                            } else {
                                $lancamentos[$chave]["historico"] = descricaoHistorico($tipo, $codigo, $historico);
                            }
                        } else {
                            $conciliacao = $data_conciliacao ? 1 : 0;
                            $lancamentos[$chave]["historico"] = "<a href='#' onclick='js_janelaPendenciaAlterarPendencia({$historico}, {$conciliacao})'>(+) Mais Detalhes</a>";
                        }
                        $lancamentos[$chave]["cod_doc"][] = $cod_doc;
                        $lancamentos[$chave]["numcgm"] = $numcgm;
                        $lancamentos[$chave]["agrupado"] = false;
                    } else {
                        $lancamentos[$chave]["valor"] += $valor_debito <> 0 ? $valor_debito : $valor_credito;
                        $lancamentos[$chave]["valor_individual"][] = $valor_debito <> 0 ? $valor_debito : $valor_credito;
                        $lancamentos[$chave]["cod_doc"][] = $cod_doc;
                        $lancamentos[$chave]["op_rec_slip"][] = !$documento ? "" : $documento;
                        $lancamentos[$chave]["documento"][] = trim($cheque);
                        $lancamentos[$chave]["agrupado"] = true;

                        if ($tipo == "REC") {
                            $lancamentos[$chave]["historico"] = "<a href='#' onclick='js_janelaPlanilhaDetalhada(" . json_encode(dados_planilha($oParam->params[0]->conta, $data, $codigo, $cod_doc)) . ")'>" . descricaoHistorico($tipo, $codigo, $historico) . "</a>";
                        } else {
                            $lancamentos[$chave]["historico"] = "<a href='#' onclick='js_janelaAgrupados(" .  json_encode($agrupado[$chave]) . ")'>(+) Mais Detalhes</a>";
                        }
                    }
                }
            }

            foreach ($lancamentos as $chave => $lancamento) {
                if ($lancamento["valor"] <> 0) {
                    $oDadosLinha = new StdClass();
                    $oDadosLinha->identificador    = $lancamento["caixa"];
                    $oDadosLinha->data_lancamento  = $lancamento["data_lancamento"];
                    $oDadosLinha->data_conciliacao = $lancamento["data_conciliacao"];
                    $oDadosLinha->credor           = utf8_encode($lancamento["credor"]);
                    $oDadosLinha->tipo             = $lancamento["tipo"];
                    $oDadosLinha->op_rec_slip      = $lancamento["op_rec_slip"];
                    $oDadosLinha->documento        = $lancamento["documento"];
                    $oDadosLinha->numdocumento     = $lancamento["numdocumento"];
                    $oDadosLinha->movimento        = $lancamento["movimento"];
                    $oDadosLinha->valor_individual = $lancamento["valor_individual"];
                    $oDadosLinha->valor            = abs($lancamento["valor"]);
                    $oDadosLinha->historico        = $lancamento["historico"];
                    $oDadosLinha->numcgm           = $lancamento["numcgm"];
                    $oDadosLinha->cod_doc          = $lancamento["cod_doc"];
                    $oDadosLinha->tipo_lancamento  = $lancamento["tipo_lancamento"];
                    $oDadosLinha->agrupado         = $lancamento["agrupado"];
                    $oRetorno->aLinhasExtrato[]    = $oDadosLinha;
                }
            }
            break;
        case 'RegistrarSaldoExtrato':
            db_inicio_transacao();
            $oDaoConciliacaoBancaria  = new cl_conciliacaobancaria();
            // Recebe os parametros
            $conta = $oParam->params[0]->conta;
            $data_final = data($oParam->params[0]->data_final);
            $data_conciliacao = data($oParam->params[0]->data_final);
            $saldo_final_extrato = $oParam->params[0]->saldo_final_extrato;
            $fechar_conciliacao = $oParam->params[0]->fechar_conciliacao ? data($oParam->params[0]->fechar_conciliacao) : "";
            // busca concilia��o
            $oSql = $oDaoConciliacaoBancaria->sql_query_file(null, "*", null, "k171_conta = {$conta} AND k171_data = '{$data_final}' ");
            $oDaoConciliacaoBancaria->sql_record($oSql);
            $oRetorno->aLinhasExtrato = array();
            $oRetorno->aLinhasExtrato[] = $oParam->params[0];
            // Tratativas
            if ($oDaoConciliacaoBancaria->numrows > 0) {
                $oDaoConciliacaoBancaria->k171_conta = $conta;
                $oDaoConciliacaoBancaria->k171_data = $data_final;
                $oDaoConciliacaoBancaria->k171_dataconciliacao = $data_conciliacao;
                $oDaoConciliacaoBancaria->k171_saldo = $saldo_final_extrato;
                $oDaoConciliacaoBancaria->k171_datafechamento = $fechar_conciliacao;
                $oDaoConciliacaoBancaria->alterar();
                // $oRetorno->aLinhasExtrato[] = $oDaoConciliacaoBancaria;
            } else {
                $oDaoConciliacaoBancaria->k171_conta = $conta;
                $oDaoConciliacaoBancaria->k171_data = $data_final;
                $oDaoConciliacaoBancaria->k171_dataconciliacao = $data_conciliacao;
                $oDaoConciliacaoBancaria->k171_saldo = $saldo_final_extrato;
                $oDaoConciliacaoBancaria->k171_datafechamento = $fechar_conciliacao;
                $oDaoConciliacaoBancaria->incluir();
                // $oRetorno->aLinhasExtrato[] = $oDaoConciliacaoBancaria;
            }
            db_fim_transacao(false);
            break;
        case 'Processar':
            db_inicio_transacao();
            // Recebe os parametros
            $conta = $oParam->params[0]->conta;
            $data_final = data($oParam->params[0]->data_final);
            $data_conciliacao = data($oParam->params[0]->data_conciliacao);
            // Preenche a movimenta��o
            $oRetorno->aLinhasExtrato   = array();
            $retorno = lancamentos_conciliados($oParam->params[0]->movimentos, $conta, $data_conciliacao);
            $oRetorno->aLinhasExtrato[] = $retorno;
            $oRetorno->error = false;
            if (array_key_exists("error", $retorno))
                $oRetorno->error = $retorno["error"];

            db_fim_transacao(false);
            break;
        case 'Desprocessar':
            db_inicio_transacao();
            // Recebe os parametros
            $conta = $oParam->params[0]->conta;
            $data_final = data($oParam->params[0]->data_final);
            $data_conciliacao = data($oParam->params[0]->data_conciliacao);
            // Preenche a movimenta��o
            $oRetorno->aLinhasExtrato   = array();
            $oRetorno->aLinhasExtrato[] = excluir_lancamentos_conciliados($oParam->params[0]->movimentos, $conta, $data_conciliacao);
            db_fim_transacao(false);
            break;

        case 'getFechamento':
            $oRetorno->aLinhasExtrato = array();
            // $oRetorno->aLinhasExtrato[] = "Uso para debug";
            $data_inicial = data($oParam->params[0]->data_inicial);
            $data_final = data($oParam->params[0]->data_final);
            $conta = $oParam->params[0]->conta;
            $oDadosLinha->fechar_conciliacao = fechar_conciliacao($conta, $data_final);
            // Retorna os dados
            $oRetorno->aLinhasExtrato[] = $oDadosLinha;
            break;

        case 'getDadosExtrato':
            $oRetorno->aLinhasExtrato = array();
            // $oRetorno->aLinhasExtrato[] = "Uso para debug";
            $data_inicial = data($oParam->params[0]->data_inicial);
            $data_final = data($oParam->params[0]->data_final);
            $conta = $oParam->params[0]->conta;
            // Preenche os dados para retorno
            $oDadosLinha = new StdClass();
            $oDadosLinha->saldo_anterior     = saldo_anterior_extrato($conta, $data_inicial, $data_final);
            $oDadosLinha->total_entradas     = movimentacao_extrato($conta, $data_inicial, $data_final, 1);
            $oDadosLinha->total_saidas       = movimentacao_extrato($conta, $data_inicial, $data_final, 2);
            $oDadosLinha->saldo_final        = saldo_final_extrato($conta, $data_final);
            $oDadosLinha->valor_conciliado   = valor_conciliado($conta, $data_final);
            $oDadosLinha->fechar_conciliacao = fechar_conciliacao($conta, $data_final);
            // Retorna os dados
            $oRetorno->aLinhasExtrato[] = $oDadosLinha;
            break;
    }
} catch (ParameterException $oErro) {
    db_fim_transacao(true);
    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {
    db_fim_transacao(true);
    $oRetorno->status  = 2;
    $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);

function condicao_lancamento($tipo_lancamento)
{
    return $tipo_lancamento > 0 ? " AND conhistdoc.c53_tipo IN (" . tipo_documento_lancamento($tipo_lancamento) . ") " : "";
}

function valor_conciliado($conta, $data)
{
    $sql = "SELECT k171_saldo FROM conciliacaobancaria WHERE k171_dataconciliacao = '{$data}' AND k171_conta = {$conta}";
    $resultado = pg_query($sql);
    while ($row = pg_fetch_object($resultado)) {
        return $row->k171_saldo;
    }
    return 0;
}

function saldo_anterior_extrato($conta, $inicio, $data)
{
    $sql = "select
            substr(fc_saltessaldo,41,13)::float8 as saldo_anterior
            from
                (
                    select
                        fc_saltessaldo(k13_reduz, '{$inicio}', '{$data}', null, " . db_getsession("DB_instit") . ")
                    from
                        saltes
                        inner join conplanoexe on k13_reduz = c62_reduz
                            and c62_anousu = " . db_getsession('DB_anousu') . "
                        inner join conplanoreduz on c61_anousu = c62_anousu
                            and c61_reduz = c62_reduz
                            and c61_instit = " . db_getsession("DB_instit") . "
                        inner join conplano on c60_codcon = c61_codcon
                            and c60_anousu = c61_anousu
                    where
                        c61_reduz = {$conta}
                        and c60_codsis = 6
                ) as x";
    $resultado = pg_query($sql);
    while ($row = pg_fetch_object($resultado)) {
        return $row->saldo_anterior;
    }
}

function saldo_final_extrato($conta, $data)
{
    $sql   = "select k171_saldo from conciliacaobancaria WHERE k171_conta = {$conta} AND k171_data = '{$data}'";
    $query = db_query($sql);
    if (pg_numrows($query) > 0) {
        return number_format(db_utils::fieldsMemory($query, 0)->k171_saldo, 2, ".", "");
    }
    return 0;
}

function fechar_conciliacao($conta, $data)
{
    $sql   = "select k171_datafechamento from conciliacaobancaria WHERE k171_conta = {$conta} AND k171_data = '{$data}'";
    $query = db_query($sql);
    if (pg_numrows($query) > 0) {
        return 1;
    }
    return 0;
}

function numero_documento_lancamento($tipo, $ordem, $codigo)
{
    switch ($tipo) {
        case "OP":
            return $ordem;
            break;
        case "REC":
            return $codigo;
            break;
        case "BAIXA":
            return $codigo;
            break;
        case "SLIP":
            return $codigo;
            break;
        case 1:
            return $codigo;
            break;
        case 2:
            return $codigo;
            break;
    }
}

function tipo_lancamento($id_tipo_lancamento)
{
    $tipo_lancamento = array(
        "Selecione", "PGTO. EMPENHO", "EST. PGTO EMPENHO", "REC. ORCAMENTARIA",
        "EST. REC. ORCAMENTARIA", "PGTO EXTRA ORCAMENTARIA", "EST. PGTO EXTRA ORCAMENTARIA",
        "REC. EXTRA ORCAMENTARIA", "EST. REC. EXTRA ORCAMENTARIA", "PERDAS", "ESTORNO PERDAS",
        "TRANSFERENCIA", "EST. TRANSFERENCIA", "PENDENCIA", "IMPLANTACAO"
    );
    return $tipo_lancamento[$id_tipo_lancamento];
}

function tipo_documento_lancamento($tipo_lancamento)
{
    switch (tipo_lancamento($tipo_lancamento)) {
        case "PGTO. EMPENHO":
            return "30, 35, 5, 37";
            break;
        case "EST. PGTO EMPENHO":
            return "31, 6";
            break;
        case "REC. ORCAMENTARIA":
            return "100, 101 ) AND conlancamdoc.c71_coddoc IN (100, 122, 116, 418 ";
            break;
        case "EST. REC. ORCAMENTARIA":
            return "101 ) AND ( conlancamdoc.c71_coddoc <> 116 OR conlancamdoc.c71_coddoc <> 418 OR conlancamdoc.c71_coddoc <> 122 ";
            break;
        case "PGTO EXTRA ORCAMENTARIA":
            return "120, 161";
            break;
        case "EST. PGTO EXTRA ORCAMENTARIA":
            return "121, 163";
            break;
        case "EST. REC. EXTRA ORCAMENTARIA":
            return "131, 151, 153, 162, 167";
            break;
        case "REC. EXTRA ORCAMENTARIA":
            return "130, 150, 152, 160, 166";
            break;
        case "PERDAS":
            return "164";
            break;
        case "ESTORNO PERDAS":
            return "165";
            break;
        case "TRANSFERENCIA":
            return "140";
            break;
        case "EST. TRANSFERENCIA":
            return "141";
            break;
    }
}

function descricaoTipoLancamento($cod_doc)
{
    switch ($cod_doc) {
        case in_array($cod_doc, array("5", "30", "35", "37")):
            return "PGTO. EMPENHO";
            break;
        case in_array($cod_doc, array("6", "31")):
            return "EST. PGTO EMPENHO";
            break;
        case in_array($cod_doc, array("100")):
            return "REC. ORCAMENTARIA";
            break;
        case "101":
            return "EST. REC. ORCAMENTARIA";
            break;
        case in_array($cod_doc, array("120", "161")):
            return "PGTO EXTRA ORCAMENTARIA";
            break;
        case in_array($cod_doc, array("121", "163")):
            return "EST. PGTO EXTRA ORCAMENTARIA";
            break;
        case in_array($cod_doc, array("131", "151", "153", "162", "167")):
            return "EST. REC. EXTRA ORCAMENTARIA";
            break;
        case in_array($cod_doc, array("130", "150", "152", "160", "166")):
            return "REC. EXTRA ORCAMENTARIA";
            break;
        case "164":
            return "PERDAS";
            break;
        case "165":
            return "ESTORNO PERDAS";
            break;
        case "140":
            return "TRANSFERENCIA";
            break;
        case "141":
            return "EST. TRANSFERENCIA";
            break;
        default:
            return "";
    }
}

function descricaoHistorico($tipo, $codigo, $historico)
{
    switch ($tipo) {
        case "OP":
            return utf8_encode("Empenho N� {$codigo}");
            break;
        case "SLIP":
            return utf8_encode("Slip N� {$codigo}");
            break;
        case "REC":
            return utf8_encode("Planilha N� {$codigo}");
            break;
        default:
            return $historico;
    }
}

function lancamentos_conciliados($movimentos, $conta, $data_conciliacao)
{
    $retorno = array();
    // $retorno[] = $movimentos;
    // $retorno[] = $movimentos;
    foreach ($movimentos as $id => $movimento) {
        // $retorno[] = $movimento;
        $i = 0;
        foreach ($movimento->tipo as $tipo) {
            $valor = $movimento->valor[$i];
            $numcgm = trim($movimento->cgm);
            $documento = trim($movimento->codigo[$i] . $movimento->documento[$i]);
            $data = data($movimento->data_lancamento);
            if ($data <= $data_conciliacao) {
                $mov = ($movimento->movimentacao == "E" OR $movimento->movimentacao == "EP") ? 1 : 2;
                $where = where_conciliados($conta, $data, $tipo, $valor, NULL, $numcgm, $documento, $mov);

                $conciliacao = new cl_conciliacaobancarialancamento();

                $oSql = $conciliacao->sql_query_file(null, "*", null, $where);
                $conciliacao->sql_record($oSql);
                $retorno["retorno"][] = $oSql;
                // Tratativas
                if ($conciliacao->numrows > 0) {
                    $conciliacao->k172_conta = $conta;
                    $conciliacao->k172_data = data($movimento->data_lancamento);
                    $conciliacao->k172_numcgm = trim($movimento->cgm);
                    $conciliacao->k172_coddoc = $tipo;
                    $conciliacao->k172_mov = ($movimento->movimentacao == "E" OR $movimento->movimentacao == "EP") ? 1 : 2;
                    $conciliacao->k172_codigo = trim($documento);
                    $conciliacao->k172_valor = $valor;
                    $conciliacao->k172_dataconciliacao = $data_conciliacao;
                    $conciliacao->alterar();
                    $retorno["retorno"][] = $conciliacao;
                } else {
                    $conciliacao->k172_conta = $conta;
                    $conciliacao->k172_data = data($movimento->data_lancamento);
                    $conciliacao->k172_numcgm = trim($movimento->cgm);
                    $conciliacao->k172_coddoc = $tipo;
                    $conciliacao->k172_mov = ($movimento->movimentacao == "E" OR $movimento->movimentacao == "EP") ? 1 : 2;
                    $conciliacao->k172_codigo = trim($documento);
                    $conciliacao->k172_valor = $valor;
                    $conciliacao->k172_dataconciliacao = $data_conciliacao;
                    $conciliacao->incluir();
                    $retorno["retorno"][] = $conciliacao;
                }
                $i++;
            } else {
                $retorno["error"] = utf8_encode("Um ou mais lan�amentos n�o processados porque a data do lan�amento � superior a data da concilia��o!");
            }
        }
    }
    return $retorno;
}

function excluir_lancamentos_conciliados($movimentos, $conta, $data_conciliacao)
{
    $retorno = array();
    // $retorno[] = $movimentos;
    foreach ($movimentos as $id => $movimento) {
        if (strlen($movimento->data_conciliacao) == 10) {
            $i = 0;
            foreach ($movimento->tipo as $tipo) {
                $valor = $movimento->valor[$i];
                $numcgm = trim($movimento->cgm);
                $documento = trim($movimento->codigo[$i] . $movimento->documento[$i]);
                $data = data($movimento->data_lancamento);
                $mov = ($movimento->movimentacao == "E" OR $movimento->movimentacao == "EP") ? 1 : 2;
                $where = where_conciliados($conta, $data, $tipo, $valor, data($movimento->data_conciliacao), $numcgm, $documento, $mov);
                $conciliacao = new cl_conciliacaobancarialancamento();
                // $retorno[] = $where;
                $retorno[] = $conciliacao->excluir(null, $where);
                $i++;
            }
        }
    }
    return $retorno;
}

function where_conciliados($conta, $data, $tipo, $valor, $data_conciliacao, $numcgm, $documento, $mov)
{
    $where = "k172_conta = {$conta} AND k172_data = '{$data}' AND round(k172_valor,2) = round({$valor},2) ";
    $where .= $data_conciliacao ? " AND k172_dataconciliacao = '{$data_conciliacao}' " : " ";
    $where .= $tipo ? " AND k172_coddoc = {$tipo} " : " AND k172_coddoc IS NULL ";
    $where .= $numcgm ? " AND k172_numcgm = {$numcgm} " :  " AND k172_numcgm IS NULL ";
    $where .= $mov ? " AND k172_mov = {$mov} " : " AND k172_mov IS NULL ";
    // $documento = preg_replace( "~\x{00a0}~siu", "", $documento);
    $where .= $documento ? " AND k172_codigo = '{$documento}' " : " AND (k172_codigo IS NULL OR k172_codigo = '') ";

    return $where;
}

function query_lancamentos($conta, $data_inicial, $data_final, $condicao_lancamento, $tipo)
{
    $dataImplantacao = data_implantacao();
    $sql = "SELECT * FROM (";
    if (in_array($tipo, array(0, 13, 14))) {
        $sql .= query_pendencias($conta, $data_inicial, $data_final, $tipo);
    }
    if (!in_array($tipo, array(13, 14))) {
        if ($tipo == 0)
            $sql .= " union all ";
        $sql .= query_empenhos($conta, $data_inicial, $data_final, $condicao_lancamento, data($dataImplantacao));
        $sql .= " UNION ALL ";
        $sql .= query_baixa($conta, $data_inicial, $data_final, $condicao_lancamento, data($dataImplantacao));
        $sql .= " UNION ALL ";
        $sql .= query_planilhas($conta, $data_inicial, $data_final, $condicao_lancamento, data($dataImplantacao));
        $sql .= " UNION ALL ";
        $sql .= query_transferencias_debito($conta, $data_inicial, $data_final, $condicao_lancamento, data($dataImplantacao));
        $sql .= " UNION ALL ";
        $sql .= query_transferencias_credito($conta, $data_inicial, $data_final, $condicao_lancamento, data($dataImplantacao));
    }
    $sql .= ") as w " . where_retencoes() . " ORDER BY w.data";
    return $sql;
}

function where_retencoes() {
    return " WHERE (
                (valor_credito <> 0 AND (valor_credito - COALESCE(retencao, 0)) <> 0) 
            OR 
                (valor_debito <> 0 AND (valor_debito - COALESCE(retencao, 0)) <> 0)
            ) ";
}

function query_empenhos($conta, $data_inicial, $data_final, $condicao_lancamento, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data BETWEEN '{$data_implantacao}' AND '{$data_final}')  ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data < '{$data_inicial}') ";
    }

    $sql  = query_padrao_op();
    $sql .= " corrente.k12_conta = {$conta} ";
    $sql .= " AND ((corrente.k12_data between '{$data_inicial}' AND '{$data_final}') ";
    $sql .= "     {$condicao_implantacao} ";
    $sql .= "     OR (k172_dataconciliacao > '{$data_final}' AND corrente.k12_data <= '{$data_final}' ) ";
    $sql .= "     OR (k172_dataconciliacao BETWEEN '{$data_inicial}' AND '{$data_final}')) ";
    $sql .= " {$condicao_lancamento} ";
    $sql .= " AND c69_sequen IS NOT NULL ";
    $sql .= " AND k105_corgrupotipo != 2 ";
    $sql .= " AND corrente.k12_instit = " . db_getsession("DB_instit");
    $sql .= " AND " . condicao_retencao();

    return $sql;
}

function query_baixa($conta, $inicio, $fim, $condicao, $implantacao)
{
    $inicio = $inicio < $implantacao ? $implantacao : $inicio;
    $condicao_implantacao = query_baixa_implantacao($inicio, $fim, $implantacao);

    $sql  = query_baixa_padrao();
    $sql .= "       WHERE corrente.k12_conta = {$conta} ";
    $sql .= "           AND corrente.k12_instit = " . db_getsession('DB_instit');
    $sql .= "           AND corplacaixa.k82_id IS NULL ";
    $sql .= "           AND corplacaixa.k82_data IS NULL ";
    $sql .= "           AND corplacaixa.k82_autent IS NULL ";
    $sql .= "           {$condicao} ";
    // $sql .= "       GROUP BY corrente.k12_conta, corrente.k12_data, discla.codret, c53_tipo, c71_coddoc, z01_numcgm ";
    $sql .= "    ) as x ";

    $sql .= "
    group by
    data,
    cod_doc,
    valor_credito,
    codigo,
    tipo,
    k12_conta,
    tipo_doc,
    numcgm,
    ordem,
    credor
    ";
    $sql .= " ) as xx ";
    $sql .= "    LEFT JOIN conciliacaobancarialancamento conc ON conc.k172_conta = conta ";
    $sql .= "        AND conc.k172_data = data ";
    $sql .= "        AND conc.k172_coddoc = cod_doc ";
    $sql .= "        AND conc.k172_codigo = codigo::text ";
    $sql .= "        AND round(conc.k172_valor, 2) = round(valor_debito, 2) ";
    $sql .= "    WHERE ";
    $sql .= "        ((data between '{$inicio}' AND '{$fim}' AND k172_dataconciliacao IS NULL) ";
    $sql .= "            {$condicao_implantacao} ";
    $sql .= "            OR (k172_dataconciliacao > '{$fim}' AND data <= '{$fim}') ";
    $sql .= "            OR (k172_dataconciliacao between '{$inicio}' AND '{$fim}')) ";

    return $sql;
}

function query_planilhas($conta, $inicio, $fim, $condicao_lancamento, $data_implantacao)
{
    $inicio = $inicio < $data_implantacao ? $data_implantacao : $inicio;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND data >= '{$data_implantacao}' AND data <= '{$fim}') ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND data < '{$inicio}') ";
    }

    $sql  = query_padrao_rec($conta, $condicao_lancamento);
    $sql .= " ((data between '{$inicio}' AND '{$fim}') ";
    $sql .= " {$condicao_implantacao} ";
    $sql .= " OR (k172_dataconciliacao > '{$fim}' AND data <= '{$fim}') ";
    $sql .= " OR (k172_dataconciliacao BETWEEN '{$inicio}' AND '{$fim}')) ";
    return $sql;
}

function query_transferencias_debito($conta, $data_inicial, $data_final, $condicao_lancamento, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corlanc.k12_data  >= '{$data_implantacao}' AND corlanc.k12_data <= '{$data_final}')  ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corlanc.k12_data < '{$data_inicial}') ";
    }

    $sql  = query_padrao_slip_debito();
    $sql .= " corlanc.k12_conta = {$conta} ";
    $sql .= "     AND ((corlanc.k12_data between '{$data_inicial}' AND '{$data_final}') ";
    $sql .= "     {$condicao_implantacao} ";
    $sql .= "     OR (k172_dataconciliacao > '{$data_final}' AND corlanc.k12_data <= '{$data_final}' ) ";
    $sql .= "      OR (k172_dataconciliacao BETWEEN '{$data_inicial}' AND '{$data_final}')) ";
    $sql .= " {$condicao_lancamento} ";
    $sql .= " AND e81_cancelado IS NULL ";
    return $sql;
}

function query_transferencias_credito($conta, $data_inicial, $data_final, $condicao_lancamento, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data  >= '{$data_implantacao}' AND corrente.k12_data <= '{$data_final}')  ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data < '{$data_inicial}') ";
    }

    $sql  = query_padrao_slip_credito();
    $sql .= " corrente.k12_conta = {$conta} ";
    $sql .= " AND ((corrente.k12_data between '{$data_inicial}' AND '{$data_final}') ";
    $sql .= "     {$condicao_implantacao} ";
    $sql .= "     OR (k172_dataconciliacao > '{$data_final}' AND corrente.k12_data <= '{$data_final}' ) ";
    $sql .= "     OR (k172_dataconciliacao BETWEEN '{$data_inicial}' AND '{$data_final}')) ";
    $sql .= " {$condicao_lancamento} ";
    $sql .= " AND e81_cancelado IS NULL ";
    $sql .= " ORDER BY data, codigo ";
    return $sql;
}

function movimentacao_extrato($conta, $dataInicial, $dataFinal, $movimentacao)
{
    $implantacao = data(data_implantacao());
    $sql = " SELECT * FROM ( ";
    $sql .= query_empenhos_total($conta, $dataInicial, $dataFinal, $implantacao);
    $sql .= " UNION ALL ";
    $sql .= query_baixa_total($conta, $dataInicial, $dataFinal, $implantacao);
    $sql .= " union all ";
    $sql .= query_planilha_total($conta, $dataInicial, $dataFinal, $implantacao);
    $sql .= " union all ";
    $sql .= query_transferencias_debito_total($conta, $dataInicial, $dataFinal, $implantacao);
    $sql .= " union all ";
    $sql .= query_transferencias_credito_total($conta, $dataInicial, $dataFinal, $implantacao);
    $sql .= ") as w " . where_retencoes() . " ORDER BY w.data";

    $query = pg_query($sql);

    $valor = 0;
    while ($row = pg_fetch_object($query)) {
        // $movimento = $row->valor_debito > 0 ? 1 : 2;
        if ($movimentacao == 1) {
            $valor += $row->valor_debito  > 0 ? abs($row->valor_debito) : 0;
            $valor += $row->valor_credito < 0 ? abs($row->valor_credito) : 0;
        } else {
            $valor += $row->valor_debito  < 0 ? abs($row->valor_debito) : 0;
            $valor += $row->valor_credito > 0 ? abs($row->valor_credito) : 0;
        }
    }

    $sqlPendencias = "SELECT
              *
          FROM
              conciliacaobancariapendencia
          LEFT JOIN cgm ON z01_numcgm = k173_numcgm
          LEFT JOIN conciliacaobancarialancamento ON k172_data = k173_data
              AND ((k172_numcgm IS NULL AND k173_numcgm IS NULL) OR (k172_numcgm = k173_numcgm))
              AND ((k172_coddoc is null AND k173_tipomovimento = '') OR (k172_coddoc::text = k173_tipomovimento))
              AND ((k173_documento is null AND k172_codigo is null) OR

              (k172_codigo::text =  concat_ws(
                '',
                k173_codigo :: text,
                k173_documento :: text
            )))
              AND k172_valor = k173_valor
              AND k172_mov = k173_mov
          WHERE
              ((k173_data BETWEEN '{$dataInicial}'
              AND '{$dataFinal}' AND k172_dataconciliacao IS NULL)
              OR (k172_dataconciliacao > '{$dataFinal}' AND  k173_data <= '{$dataFinal}')
              OR (k172_dataconciliacao IS NULL AND k173_data <= '{$dataInicial}'))
              AND k173_conta = {$conta} ";
    // return $sqlPendencias;
    $query = pg_query($sqlPendencias);

    while ($row = pg_fetch_object($query)) {
        if ($movimentacao == 1) {
            if ($row->k173_tipolancamento == 1 and $row->k173_mov == 1)
                $valor += $row->k173_valor;
            if ($row->k173_tipolancamento == 2 and $row->k173_mov == 2)
                $valor += $row->k173_valor;
        }
        if ($movimentacao == 2) {
            if ($row->k173_tipolancamento == 1 and $row->k173_mov == 2)
                $valor += $row->k173_valor;
            if ($row->k173_tipolancamento == 2 and $row->k173_mov == 1)
                $valor += $row->k173_valor;
        }
    }

    return $valor;
}

function query_empenhos_total($conta, $data_inicial, $data_final, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data BETWEEN '{$data_implantacao}' AND '{$data_final}')   ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data < '{$data_inicial}') ";
    }

    $sql = query_padrao_op();
    $sql .= " corrente.k12_conta = {$conta} ";
    $sql .= " AND ((corrente.k12_data between '{$data_inicial}' AND '{$data_final}' AND k172_dataconciliacao IS NULL) ";
    $sql .= "     {$condicao_implantacao} ";
    $sql .= "     OR (k172_dataconciliacao > '{$data_final}' AND corrente.k12_data <= '{$data_final}' )) ";
    $sql .= " AND c69_sequen IS NOT NULL ";
    $sql .= " AND k105_corgrupotipo != 2 ";
    $sql .= " AND corrente.k12_instit = " . db_getsession("DB_instit");
    $sql .= " AND " . condicao_retencao();

    return $sql;
}

function query_baixa_total($conta, $inicio, $fim, $implantacao)
{
    $inicio = $inicio < $implantacao ? $implantacao : $inicio;
    $condicao_implantacao = query_baixa_implantacao($inicio, $fim, $implantacao);

    $sql  = query_baixa_padrao();
    $sql .= "       WHERE corrente.k12_conta = {$conta} ";
    $sql .= "           AND corrente.k12_instit = " . db_getsession('DB_instit');
    $sql .= "           AND corplacaixa.k82_id IS NULL ";
    $sql .= "           AND corplacaixa.k82_data IS NULL ";
    $sql .= "           AND corplacaixa.k82_autent IS NULL ";
    // $sql .= "       GROUP BY corrente.k12_conta, corrente.k12_data, discla.codret, c53_tipo, c71_coddoc, z01_numcgm ";
    $sql .= "    ) as x ";

    $sql .= "
    group by
    data,
    cod_doc,
    valor_credito,
    codigo,
    tipo,
    conta,
    tipo_doc,
    numcgm,
    ordem,
    credor
    ";
    $sql .= " ) as xx ";
    $sql .= "    LEFT JOIN conciliacaobancarialancamento conc ON conc.k172_conta = conta ";
    $sql .= "        AND conc.k172_data = data ";
    $sql .= "        AND conc.k172_coddoc = cod_doc ";
    $sql .= "        AND conc.k172_codigo = codigo::text ";
    $sql .= "        AND round(conc.k172_valor, 2) = round(valor_debito, 2) ";
    $sql .= "    WHERE ";
    $sql .= "        ((data between '{$inicio}' AND '{$fim}' AND k172_dataconciliacao IS NULL) ";
    $sql .= "            {$condicao_implantacao} ";
    $sql .= "            OR (k172_dataconciliacao > '{$fim}' AND data <= '{$fim}')) ";

    return $sql;
}

function query_planilha_total($conta, $data_inicial, $data_final, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND data >= '{$data_implantacao}' AND data <= '{$data_final}')   ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND data < '{$data_inicial}') ";
    }

    $sql  = query_padrao_rec($conta, "");
    $sql .= " ((data between '{$data_inicial}' AND '{$data_final}' AND k172_dataconciliacao IS NULL) ";
    $sql .= " {$condicao_implantacao} ";
    $sql .= " OR (k172_dataconciliacao > '{$data_final}' AND data <= '{$data_final}')) ";

    return $sql;
}

function query_transferencias_debito_total($conta, $data_inicial, $data_final, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corlanc.k12_data  >= '{$data_implantacao}' AND corlanc.k12_data <= '{$data_final}')   ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corlanc.k12_data < '{$data_inicial}') ";
    }

    $sql = query_padrao_slip_debito() . "
                corlanc.k12_conta = {$conta}
                AND ((corlanc.k12_data between '{$data_inicial}' AND '{$data_final}' AND k172_dataconciliacao IS NULL)
                {$condicao_implantacao} OR (k172_dataconciliacao > '{$data_final}' AND corlanc.k12_data <= '{$data_final}'))
                AND e81_cancelado IS NULL
                ";

    return $sql;
}

function query_transferencias_credito_total($conta, $data_inicial, $data_final, $data_implantacao)
{
    $data_inicial = $data_inicial < $data_implantacao ? $data_implantacao : $data_inicial;
    if ($data_implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data  >= '{$data_implantacao}' AND corrente.k12_data <= '{$data_final}')   ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND corrente.k12_data < '{$data_inicial}') ";
    }

    $sql = query_padrao_slip_credito() . "
            corrente.k12_conta = {$conta}
            AND ((corrente.k12_data between '{$data_inicial}' AND '{$data_final}' AND k172_dataconciliacao IS NULL)
            {$condicao_implantacao} OR (k172_dataconciliacao > '{$data_final}'
            AND corrente.k12_data <= '{$data_final}'))
            AND e81_cancelado IS NULL
        order by
            data,
            codigo";
    return $sql;
}

function query_padrao_op()
{
    $sql  = " SELECT ";
    $sql .= "     0 as tipo_lancamento, ";
    $sql .= "     corrente.k12_data as data, ";
    $sql .= "     k172_dataconciliacao data_conciliacao, ";
    $sql .= "     conhistdoc.c53_tipo::text cod_doc, ";
    $sql .= "     0 as valor_debito, ";
    $sql .= "     corrente.k12_valor as valor_credito, ";
    $sql .= "     e60_codemp || '/' || e60_anousu::text as codigo, ";
    $sql .= "     'OP'::text as tipo, ";
    $sql .= "     CASE ";
    $sql .= "         WHEN e86_cheque IS NOT NULL AND e86_cheque <> '0' ";
    $sql .= "         THEN 'CHE ' || e86_cheque :: text ";
    $sql .= "         WHEN coremp.k12_cheque = 0 ";
    $sql .= "         THEN e81_numdoc::text ";
    $sql .= "         ELSE 'CHE ' || coremp.k12_cheque::text ";
    $sql .= "     END AS cheque, ";
    $sql .= "     coremp.k12_codord::text AS ordem, ";
    $sql .= "     z01_nome::text AS credor, ";
    $sql .= "     z01_numcgm::text AS numcgm, ";
    $sql .= "     '' AS historico, ";
    $sql .= " CASE
    WHEN conhistdoc.c53_tipo = 31 THEN (
        SELECT valor_retencao FROM (
            SELECT
                round(SUM(numpre.k12_valor), 2) valor_retencao
            FROM
                retencaoreceitas
                INNER JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec
                INNER JOIN retencaopagordem ON retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem
                INNER JOIN tabrec ON tabrec.k02_codigo = retencaotiporec.e21_receita
                INNER JOIN retencaotipocalc ON retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc
                INNER JOIN pagordem ON pagordem.e50_codord = retencaopagordem.e20_pagordem
                INNER JOIN pagordemnota ON pagordem.e50_codord = pagordemnota.e71_codord
                INNER JOIN empnota ON pagordemnota.e71_codnota = empnota.e69_codnota
                INNER JOIN retencaoempagemov ON e23_sequencial = e27_retencaoreceitas
                LEFT JOIN empagemovslips ON e27_empagemov = k107_empagemov
                LEFT JOIN slipempagemovslips ON k107_sequencial = k108_empagemovslips
                LEFT JOIN retencaocorgrupocorrente ON e23_sequencial = e47_retencaoreceita
                LEFT JOIN corgrupocorrente ON e47_corgrupocorrente = k105_sequencial
                LEFT JOIN cornump as numpre ON numpre.k12_data = k105_data
                AND numpre.k12_autent = k105_autent
                AND numpre.k12_id = k105_id
                LEFT JOIN issplannumpre ON numpre.k12_numpre = q32_numpre
            WHERE
                e20_pagordem = coremp.k12_codord
                AND e27_principal IS true
                -- AND e23_ativo IS FALSE
                
                AND corgrupocorrente.k105_corgrupotipo = 6
            ) as x  WHERE round(valor_retencao, 2) = corrente.k12_valor
    )
    ELSE (
        SELECT valor_retencao FROM (
            SELECT
                round(SUM(numpre.k12_valor), 2) valor_retencao
            FROM
                retencaoreceitas
                INNER JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec
                INNER JOIN retencaopagordem ON retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem
                INNER JOIN tabrec ON tabrec.k02_codigo = retencaotiporec.e21_receita
                INNER JOIN retencaotipocalc ON retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc
                INNER JOIN pagordem ON pagordem.e50_codord = retencaopagordem.e20_pagordem
                INNER JOIN pagordemnota ON pagordem.e50_codord = pagordemnota.e71_codord
                INNER JOIN empnota ON pagordemnota.e71_codnota = empnota.e69_codnota
                INNER JOIN retencaoempagemov ON e23_sequencial = e27_retencaoreceitas
                LEFT JOIN empagemovslips ON e27_empagemov = k107_empagemov
                LEFT JOIN slipempagemovslips ON k107_sequencial = k108_empagemovslips
                LEFT JOIN retencaocorgrupocorrente ON e23_sequencial = e47_retencaoreceita
                LEFT JOIN corgrupocorrente ON e47_corgrupocorrente = k105_sequencial
                LEFT JOIN cornump as numpre ON numpre.k12_data = k105_data
                AND numpre.k12_autent = k105_autent
                AND numpre.k12_id = k105_id
                LEFT JOIN issplannumpre ON numpre.k12_numpre = q32_numpre
            WHERE
                e20_pagordem = coremp.k12_codord
                AND e27_principal IS true
                -- AND e23_ativo IS FALSE
                AND corgrupocorrente.k105_corgrupotipo = 3
            ) as x  WHERE  round(valor_retencao, 2) = corrente.k12_valor
    )
END retencao ";
    $sql .= " FROM corrente ";
    $sql .= " INNER JOIN coremp ON coremp.k12_id = corrente.k12_id ";
    $sql .= "     AND coremp.k12_data = corrente.k12_data ";
    $sql .= "     AND coremp.k12_autent = corrente.k12_autent ";
    $sql .= " INNER JOIN empempenho ON e60_numemp = coremp.k12_empen ";
    $sql .= " INNER JOIN cgm ON z01_numcgm = e60_numcgm ";
    $sql .= " LEFT JOIN corhist ON corhist.k12_id = corrente.k12_id ";
    $sql .= "     AND corhist.k12_data = corrente.k12_data ";
    $sql .= "     AND corhist.k12_autent = corrente.k12_autent ";
    $sql .= " LEFT JOIN corautent ON corautent.k12_id = corrente.k12_id ";
    $sql .= "     AND corautent.k12_data = corrente.k12_data ";
    $sql .= "     AND corautent.k12_autent = corrente.k12_autent ";
    $sql .= " LEFT JOIN corgrupocorrente ON corrente.k12_data = k105_data ";
    $sql .= "     AND corrente.k12_id = k105_id ";
    $sql .= "     AND corrente.k12_autent = k105_autent ";
    $sql .= " LEFT JOIN conlancamcorgrupocorrente ON c23_corgrupocorrente = k105_sequencial ";
    $sql .= " LEFT JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamcorgrupocorrente.c23_conlancam ";
    $sql .= " LEFT JOIN conlancamval ON conlancamval.c69_codlan = conlancamcorgrupocorrente.c23_conlancam ";
    $sql .= "     AND (";
    $sql .= "         ( c69_credito = corrente.k12_conta";
    $sql .= "             AND corrente.k12_valor > 0 )";
    $sql .= "         OR ( c69_debito = corrente.k12_conta ";
    $sql .= "             AND corrente.k12_valor < 0 )";
    $sql .= "     )";
    $sql .= " LEFT JOIN corempagemov ON corempagemov.k12_id = coremp.k12_id";
    $sql .= "     AND corempagemov.k12_autent = coremp.k12_autent";
    $sql .= "     AND corempagemov.k12_data = coremp.k12_data";
    $sql .= " LEFT JOIN empagemov ON e60_numemp = empagemov.e81_numemp";
    $sql .= "      AND k12_codmov = e81_codmov";
    $sql .= " LEFT JOIN conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc";
    $sql .= " LEFT JOIN empageconf ON empageconf.e86_codmov = empagemov.e81_codmov";
    $sql .= " LEFT JOIN conciliacaobancarialancamento conc ON conc.k172_conta = corrente.k12_conta";
    $sql .= "     AND conc.k172_data = corrente.k12_data";
    $sql .= "     AND conc.k172_coddoc = conhistdoc.c53_tipo";
    $sql .= "     AND conc.k172_codigo = concat_ws('', coremp.k12_codord::text, ( ";
    $sql .= "         CASE ";
    $sql .= "             WHEN e86_cheque IS NOT NULL AND e86_cheque <> '0' ";
    $sql .= "             THEN 'CHE ' || e86_cheque::text ";
    $sql .= "             WHEN coremp.k12_cheque = 0 ";
    $sql .= "             THEN e81_numdoc::text ";
    $sql .= "             ELSE 'CHE ' || coremp.k12_cheque::text ";
    $sql .= "         END ";
    $sql .= "         ) ";
    $sql .= "     ) ";
    // $sql .= " AND conc.k172_valor = corrente.k12_valor ";
    $sql .= " WHERE ";

    return $sql;
}

function query_baixa_padrao()
{
    $sql = " SELECT ";
    $sql .= "     0 as tipo_lancamento, ";
    $sql .= "     data, ";
    $sql .= "        k172_dataconciliacao data_conciliacao, ";
    $sql .= "     cod_doc::text cod_doc, ";
    $sql .= "     valor_debito, ";
    $sql .= "     valor_credito, ";
    $sql .= "     codigo, ";
    $sql .= "     'BAIXA'::text as tipo, ";
    $sql .= "     NULL as cheque, ";
    $sql .= "     ordem::text ordem, ";
    $sql .= "     credor, ";
    $sql .= "     numcgm::text numcgm, ";
    $sql .= utf8_encode(" 'Arrecada��o de Receita' historico, ");
    $sql .= "     0 as retencao ";
    $sql .= " FROM ( ";
    $sql .= "    SELECT ";
    $sql .= "        data, ";
    $sql .= "        SUM(valor_debito) valor_debito, ";
    $sql .= "        k12_conta conta, ";
    $sql .= "        valor_credito, ";
    $sql .= "        codigo :: text, ";
    $sql .= "        tipo :: text, ";
    $sql .= "        ordem, ";
    $sql .= "        credor :: text, ";
    $sql .= "        tipo_doc, ";
    $sql .= "        cod_doc, ";
    $sql .= "        numcgm ";
    $sql .= "    FROM ( ";
    $sql .= "       SELECT ";
    $sql .= "            corrente.k12_conta, ";
    $sql .= "            corrente.k12_data as data, ";
    $sql .= "            CASE ";
    $sql .= "               WHEN conlancamdoc.c71_coddoc IN (418, 122) THEN -1 * c70_valor ";
    $sql .= "               ELSE c70_valor ";
    $sql .= "            END as valor_debito, ";
    $sql .= "            0 as valor_credito, ";
    $sql .= "            discla.codret as codigo, ";
    $sql .= "            'baixa' :: text as tipo, ";
    $sql .= "            0 as ordem, ";
    $sql .= "            z01_nome credor, ";
    $sql .= "             CASE
    WHEN conlancamdoc.c71_coddoc IN (418, 122)
    THEN 100
    ELSE conhistdoc.c53_tipo END as tipo_doc, ";
    $sql .= "             CASE
    WHEN conlancamdoc.c71_coddoc IN (418, 122)
    THEN 100
    ELSE c71_coddoc END as cod_doc, ";
    $sql .= "            z01_numcgm numcgm ";
    $sql .= "       FROM corrente ";
    $sql .= "       LEFT JOIN corhist on corhist.k12_id = corrente.k12_id ";
    $sql .= "           AND corhist.k12_data = corrente.k12_data ";
    $sql .= "           AND corhist.k12_autent = corrente.k12_autent ";
    $sql .= "       LEFT JOIN corautent on corautent.k12_id = corrente.k12_id ";
    $sql .= "           AND corautent.k12_data = corrente.k12_data ";
    $sql .= "           AND corautent.k12_autent = corrente.k12_autent ";
    $sql .= "       INNER JOIN corcla on corcla.k12_id = corrente.k12_id ";
    $sql .= "           AND corcla.k12_data = corrente.k12_data ";
    $sql .= "           AND corcla.k12_autent = corrente.k12_autent ";
    $sql .= "       INNER JOIN discla on discla.codcla = corcla.k12_codcla ";
    $sql .= "           AND discla.instit = " . db_getsession('DB_instit');
    $sql .= "       INNER JOIN disarq on disarq.codret = discla.codret ";
    $sql .= "           AND disarq.instit = discla.instit ";
    $sql .= "       LEFT JOIN corplacaixa ON corplacaixa.k82_id = corrente.k12_id ";
    $sql .= "           AND corplacaixa.k82_data = corrente.k12_data ";
    $sql .= "           AND corplacaixa.k82_autent = corrente.k12_autent ";
    $sql .= "       LEFT JOIN conlancamcorrente ON conlancamcorrente.c86_id = corrente.k12_id ";
    $sql .= "           AND conlancamcorrente.c86_data = corrente.k12_data ";
    $sql .= "           AND conlancamcorrente.c86_autent = corrente.k12_autent ";
    $sql .= "       LEFT JOIN conlancam ON conlancam.c70_codlan = conlancamcorrente.c86_conlancam ";
    $sql .= "       LEFT JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan ";
    $sql .= "       LEFT JOIN conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc ";
    $sql .= "       LEFT JOIN placaixarec on k81_seqpla = k82_seqpla ";
    $sql .= "       LEFT JOIN cgm on cgm.z01_numcgm = placaixarec.k81_numcgm ";
    return $sql;
}

function query_pendencias($conta, $data_inicial, $data_final, $tipo)
{
    $sql  = " SELECT ";
    $sql .= "     k173_tipolancamento tipo_lancamento, ";
    $sql .= "     k173_data as data, ";
    $sql .= "     k172_dataconciliacao data_conciliacao, ";
    $sql .= "     k173_tipomovimento cod_doc, ";
    $sql .= "     CASE WHEN k173_mov = 1 THEN k173_valor ELSE 0 END as valor_debito, ";
    $sql .= "     CASE WHEN k173_mov = 2 THEN k173_valor ELSE 0 END as valor_credito, ";
    $sql .= "     k173_codigo codigo, ";
    $sql .= "     k173_tipolancamento::text tipo, ";
    $sql .= "     k173_documento as cheque, ";
    $sql .= "     '' as ordem, ";
    $sql .= "     z01_nome credor, ";
    $sql .= "     k173_numcgm::text numcgm, ";
    $sql .= "     k173_sequencial::text as historico, ";
    $sql .= "     0 as retencao ";
    $sql .= " FROM conciliacaobancariapendencia ";
    $sql .= " LEFT JOIN cgm ON z01_numcgm = k173_numcgm ";
    $sql .= " LEFT JOIN conciliacaobancarialancamento ON k172_data = k173_data ";
    $sql .= "     AND ((k172_numcgm IS NULL AND k173_numcgm IS NULL) OR (k172_numcgm = k173_numcgm)) ";
    $sql .= "     AND ((k172_coddoc is null AND k173_tipomovimento = '') OR (k172_coddoc::text = k173_tipomovimento)) ";
    $sql .= "     AND ((k173_documento is null AND k172_codigo is null)  ";
    $sql .= "         OR (k172_codigo::text = concat_ws('', k173_codigo::text, k173_documento::text))) ";
    $sql .= "     AND k172_valor = k173_valor ";
    $sql .= "     AND k172_mov = k173_mov ";
    $sql .= "     AND k172_conta = k173_conta ";
    $sql .= " WHERE ((k173_data BETWEEN '{$data_inicial}' AND '{$data_final}') ";
    $sql .= "         OR (k172_dataconciliacao > '{$data_final}' AND k173_data <= '{$data_final}') ";
    $sql .= "         OR (k172_dataconciliacao IS NULL AND k173_data < '{$data_inicial}') ";
    $sql .= "         OR (k172_dataconciliacao BETWEEN '{$data_inicial}' AND '{$data_final}')) ";
    $sql .= "     AND k173_conta = {$conta} ";

    if ($tipo == 13)
        $sql .= " AND k173_tipolancamento = 2 ";
    if ($tipo == 14)
        $sql .= " AND k173_tipolancamento = 1 ";

    return $sql;
}

function query_padrao_rec($conta, $condicao)
{
    return "select
  0 as tipo_lancamento,
  data,

  conc.k172_dataconciliacao as data_conciliacao,
  cod_doc :: text,
  valor_debito,
  valor_credito,
  codigo,
  tipo,
  cheque,
  ordem :: text,
  credor,
  numcgm :: text as numcgm,
  '' as historico,
  0 as retencao
 from
  (
      select
          data,
          conta,
          cod_doc,
          sum(valor_debito) as valor_debito,
          0 as valor_credito,
          tipo_movimentacao :: text,
          codigo :: text,
          tipo :: text,
          cheque :: text,
          ordem,
          credor :: text,
          numcgm
      from
          (
              select
                  corrente.k12_id as caixa,
                  corrente.k12_conta as conta,
                  corrente.k12_data as data,
                  CASE
                      WHEN conlancamdoc.c71_coddoc = 100 OR conlancamdoc.c71_coddoc = 115 THEN c70_valor
                      ELSE -1 * c70_valor
                  END as valor_debito,
                  0 valor_credito,
                  CASE
                      WHEN conlancamdoc.c71_coddoc = 116
                      THEN 100
                      WHEN conlancamdoc.c71_coddoc = 115 
                      THEN 101
                      ELSE conhistdoc.c53_tipo
                  END as cod_doc,
                  ('planilha :' || k81_codpla) :: text as tipo_movimentacao,
                  k81_codpla :: text as codigo,
                  'REC' :: text as tipo,
                  (coalesce(placaixarec.k81_obs, '.')) :: text as historico,
                  null :: text as cheque,
                  0 as ordem,
                  z01_nome as credor,
                  z01_numcgm as numcgm
              from
                  corrente
                  inner join corplacaixa on k12_id = k82_id
                  and k12_data = k82_data
                  and k12_autent = k82_autent
                  inner join placaixarec on k81_seqpla = k82_seqpla
                  inner join tabrec on tabrec.k02_codigo = k81_receita
                  left join corhist on corhist.k12_id = corrente.k12_id
                  and corhist.k12_data = corrente.k12_data
                  and corhist.k12_autent = corrente.k12_autent
                  inner join corautent on corautent.k12_id = corrente.k12_id
                  and corautent.k12_data = corrente.k12_data
                  and corautent.k12_autent = corrente.k12_autent
                  LEFT JOIN conlancamcorrente ON conlancamcorrente.c86_id = corrente.k12_id
                  AND conlancamcorrente.c86_data = corrente.k12_data
                  AND conlancamcorrente.c86_autent = corrente.k12_autent
                  LEFT JOIN conlancam ON conlancam.c70_codlan = conlancamcorrente.c86_conlancam
                  LEFT JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                  inner join cgm on cgm.z01_numcgm = placaixarec.k81_numcgm
                  LEFT JOIN conhistdoc ON conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
              where
                              corrente.k12_conta = {$conta}
                              and corrente.k12_instit = " . db_getsession("DB_instit") . " {$condicao}
                      ) as x

                      group by
                          data,
                          cod_doc,
                          conta,
                          valor_credito,
                          tipo_movimentacao,
                          codigo,
                          tipo,
                          historico,
                          cheque,
                          numcgm,
                          ordem,
                          credor
                  ) as xx

                  LEFT JOIN conciliacaobancarialancamento conc ON conc.k172_conta = conta
                          AND conc.k172_data = data
                          AND conc.k172_coddoc = cod_doc
                          AND conc.k172_codigo = codigo
                          AND round(conc.k172_valor, 2) = round(valor_debito, 2)
              WHERE ";
}

function query_padrao_slip_debito()
{
    return "select
  0 as tipo_lancamento,
  corlanc.k12_data as data,
  k172_dataconciliacao data_conciliacao,
  conhistdoc.c53_tipo::text cod_doc,
  corrente.k12_valor as valor_debito,
  0 as valor_credito,
  k12_codigo::text as codigo,
  'SLIP'::text as tipo,
  case
                when e91_cheque is null then e81_numdoc :: text
                else 'CHE ' || e91_cheque :: text
            end as cheque,
  '' as ordem,
  z01_nome::text as credor,
  z01_numcgm::text as numcgm,
  '' as historico,
  0 as retencao
 from
  corlanc
  inner join corrente on corrente.k12_id = corlanc.k12_id
  and corrente.k12_data = corlanc.k12_data
  and corrente.k12_autent = corlanc.k12_autent
  inner join slip on slip.k17_codigo = corlanc.k12_codigo
  inner join conplanoreduz on c61_reduz = slip.k17_credito
  and c61_anousu =  " . db_getsession('DB_anousu') . "
  inner join conplano on c60_codcon = c61_codcon
  and c60_anousu = c61_anousu
  left join slipnum on slipnum.k17_codigo = slip.k17_codigo
  left join cgm on slipnum.k17_numcgm = z01_numcgm
  left join sliptipooperacaovinculo on sliptipooperacaovinculo.k153_slip = slip.k17_codigo
  left join corconf on corconf.k12_id = corlanc.k12_id
  and corconf.k12_data = corlanc.k12_data
  and corconf.k12_autent = corlanc.k12_autent
  and corconf.k12_ativo is true
  left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
  and corconf.k12_ativo is true
  and empageconfche.e91_ativo is true
  left join corhist on corhist.k12_id = corrente.k12_id
  and corhist.k12_data = corrente.k12_data
  and corhist.k12_autent = corrente.k12_autent
  left join corautent on corautent.k12_id = corrente.k12_id
  and corautent.k12_data = corrente.k12_data
  and corautent.k12_autent = corrente.k12_autent
  LEFT JOIN conlancamcorrente ON conlancamcorrente.c86_id = corrente.k12_id
  AND conlancamcorrente.c86_data = corrente.k12_data
  AND conlancamcorrente.c86_autent = corrente.k12_autent
  LEFT JOIN conlancam ON conlancam.c70_codlan = conlancamcorrente.c86_conlancam
  LEFT JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
  LEFT JOIN conhistdoc ON
  conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
  left join corempagemov on corempagemov.k12_data = corautent.k12_data
										   and corempagemov.k12_id = corautent.k12_id
										   and corempagemov.k12_autent = corautent.k12_autent
  left join empagemov on corempagemov.k12_codmov = e81_codmov
  LEFT JOIN conciliacaobancarialancamento conc ON conc.k172_conta = corlanc.k12_conta
  AND conc.k172_data = corrente.k12_data
  AND conc.k172_coddoc = conhistdoc.c53_tipo
  AND round(conc.k172_valor, 2) = round(corrente.k12_valor, 2)
  AND conc.k172_codigo = concat_ws('', k12_codigo::text, case
  when e91_cheque is null then e81_numdoc :: text
  else 'CHE ' || e91_cheque :: text
 end)
 where";
}

function query_padrao_slip_credito()
{
    return "
  select
      0 as tipo_lancamento,
      corlanc.k12_data as data,
      k172_dataconciliacao data_conciliacao,
      conhistdoc.c53_tipo::text cod_doc,
      0 as valor_debito,
      corrente.k12_valor as valor_credito,
      k12_codigo::text as codigo,
      'SLIP'::text as tipo,
      case
                when e91_cheque is null then e81_numdoc :: text
                else 'CHE ' || e91_cheque :: text
            end as cheque,
      '' as ordem,
      z01_nome::text as credor,
      z01_numcgm::text as numcgm,
      '' as historico, 
      0 as retencao
  from
      corrente
      inner join corlanc on corrente.k12_id = corlanc.k12_id
      and corrente.k12_data = corlanc.k12_data
      and corrente.k12_autent = corlanc.k12_autent
      inner join slip on slip.k17_codigo = corlanc.k12_codigo
      inner join conplanoreduz on c61_reduz = slip.k17_debito
      and c61_anousu =  " . db_getsession('DB_anousu') . "
      inner join conplano on c60_codcon = c61_codcon
      and c60_anousu = c61_anousu
      left join slipnum on slipnum.k17_codigo = slip.k17_codigo
      left join cgm on slipnum.k17_numcgm = z01_numcgm
      left join corconf on corconf.k12_id = corlanc.k12_id
      and corconf.k12_data = corlanc.k12_data
      and corconf.k12_autent = corlanc.k12_autent
      and corconf.k12_ativo is true
      left join sliptipooperacaovinculo on sliptipooperacaovinculo.k153_slip = slip.k17_codigo
      left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
      and corconf.k12_ativo is true
      and empageconfche.e91_ativo is true
      left join corhist on corhist.k12_id = corrente.k12_id
      and corhist.k12_data = corrente.k12_data
      and corhist.k12_autent = corrente.k12_autent
      left join corautent on corautent.k12_id = corrente.k12_id
      and corautent.k12_data = corrente.k12_data
      and corautent.k12_autent = corrente.k12_autent
      LEFT JOIN conlancamcorrente ON conlancamcorrente.c86_id = corrente.k12_id
      AND conlancamcorrente.c86_data = corrente.k12_data
      AND conlancamcorrente.c86_autent = corrente.k12_autent
      LEFT JOIN conlancam ON conlancam.c70_codlan = conlancamcorrente.c86_conlancam
      LEFT JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
      LEFT JOIN conhistdoc ON
      conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
      left join corempagemov on corempagemov.k12_data = corautent.k12_data
      and corempagemov.k12_id = corautent.k12_id
      and corempagemov.k12_autent = corautent.k12_autent
      left join empageslip  on empageslip.e89_codigo = slip.k17_codigo
      left join empagemov   on e89_codmov=e81_codmov
      LEFT JOIN conciliacaobancarialancamento conc ON conc.k172_conta = corrente.k12_conta
      AND conc.k172_data = corrente.k12_data
      AND conc.k172_coddoc = conhistdoc.c53_tipo
      AND round(conc.k172_valor, 2) = round(corrente.k12_valor, 2)
      AND conc.k172_codigo = concat_ws('', k12_codigo::text, case
      when e91_cheque is null then e81_numdoc :: text
      else 'CHE ' || e91_cheque :: text
  end)
  where";
}

function dados_planilha($conta, $data_lancamento, $planilha)
{
    $sql = "SELECT DISTINCT
            k81_codpla as planilha, k81_receita as codigo, k02_drecei as descricao, SUM(k12_valor) as valor
        from
            corrente
            inner join corplacaixa on k12_id = k82_id
                AND k12_data = k82_data
                AND k12_autent = k82_autent
            inner join placaixarec on k81_seqpla = k82_seqpla
            inner join tabrec on tabrec.k02_codigo = k81_receita
        where
            corrente.k12_conta = {$conta}
            and corrente.k12_instit = " . db_getsession('DB_instit') . "
            AND corrente.k12_data = '{$data_lancamento}'
            AND k81_codpla = {$planilha}
        GROUP BY k81_codpla, k81_receita, k02_drecei
        ORDER BY k81_receita";

    $resultado = pg_query($sql);
    $receita = array();
    while ($row = pg_fetch_object($resultado)) {
        $receita[] = array(
            "planilha"  => $row->planilha,
            "codigo"    => $row->codigo,
            "data"      => date("d/m/Y", strtotime($data_lancamento)),
            "descricao" => utf8_encode($row->descricao),
            "valor"     => $row->valor
        );
    }
    return $receita;
}

function data_implantacao()
{
    $sSQL = "SELECT k29_conciliacaobancaria FROM caiparametro WHERE k29_instit = " . db_getsession('DB_instit');
    $rsResult = db_query($sSQL);
    return db_utils::fieldsMemory($rsResult, 0)->k29_conciliacaobancaria ? date("d/m/Y", strtotime(db_utils::fieldsMemory($rsResult, 0)->k29_conciliacaobancaria)) : "";
}

function condicao_retencao()
{
    $sql  = " ( ";
    $sql .= " SELECT * ";
    $sql .= " FROM ( ";
    $sql .= "     SELECT SUM(e23_valorretencao) retencao ";
    $sql .= "     FROM retencaoreceitas ";
    $sql .= "     INNER JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec ";
    $sql .= "     INNER JOIN retencaopagordem ON retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem ";
    $sql .= "     INNER JOIN tabrec ON tabrec.k02_codigo = retencaotiporec.e21_receita ";
    $sql .= "     INNER JOIN retencaotipocalc ON retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc ";
    $sql .= "     INNER JOIN pagordem ON pagordem.e50_codord = retencaopagordem.e20_pagordem ";
    $sql .= "     INNER JOIN pagordemnota ON pagordem.e50_codord = pagordemnota.e71_codord ";
    $sql .= "     INNER JOIN empnota ON pagordemnota.e71_codnota = empnota.e69_codnota ";
    $sql .= "     INNER JOIN retencaoempagemov ON e23_sequencial = e27_retencaoreceitas ";
    $sql .= "     LEFT JOIN empagemovslips ON e27_empagemov = k107_empagemov ";
    $sql .= "     LEFT JOIN slipempagemovslips ON k107_sequencial = k108_empagemovslips ";
    $sql .= "     LEFT JOIN retencaocorgrupocorrente ON e23_sequencial = e47_retencaoreceita ";
    $sql .= "     LEFT JOIN corgrupocorrente ON e47_corgrupocorrente = k105_sequencial ";
    $sql .= "    AND k105_data = e20_data ";
    $sql .= "     LEFT JOIN cornump as numpre ON numpre.k12_data = k105_data ";
    $sql .= "         AND numpre.k12_autent = k105_autent ";
    $sql .= "         AND numpre.k12_id = k105_id ";
    $sql .= "     LEFT JOIN issplannumpre ON numpre.k12_numpre = q32_numpre ";
    $sql .= "     WHERE e20_pagordem = coremp.k12_codord ";
    $sql .= "         AND e27_principal IS true ";
    $sql .= "        AND k105_sequencial IS NOT NULL ";
    $sql .= "         AND e23_ativo IS true ";
    $sql .= "     ) as w ";
    $sql .= " WHERE round(retencao,2) = round(corrente.k12_valor,2) ";
    $sql .= " ) IS NULL ";

    return $sql;
}

function query_baixa_implantacao($inicio, $fim, $implantacao)
{
    if ($implantacao) {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND data >= '{$implantacao}' AND data <= '{$fim}') ";
    } else {
        $condicao_implantacao = " OR (k172_dataconciliacao IS NULL AND data < '{$inicio}') ";
    }
    return $condicao_implantacao;
}

function data($data)
{
    $data = explode("/", $data);
    if (count($data) > 1) {
        return $data[2] . "-" . $data[1] . "-" . $data[0];
    } else {
        return $data[0];
    }
}
