<?
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

require_once("fpdf151/impcarne.php");
require_once("fpdf151/scpdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_empempitem_classe.php");
require_once("classes/db_empparametro_classe.php");
require_once("classes/db_cgmalt_classe.php");
require_once("classes/db_pcforneconpad_classe.php");
require_once("classes/db_emite_nota_empenho.php");
require_once("model/orcamento/ControleOrcamentario.model.php");

/*
 * Configura��es GED
*/
require_once("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php");
require_once("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php");
require_once("libs/exceptions/BusinessException.php");

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

    if (!empty($oGet->dtInicial) || !empty($oGet->dtFinal)) {

        $sMsgErro  = "O par�metro para utiliza��o do GED (Gerenciador Eletr�nico de Documentos) est� ativado.<br><br>";
        $sMsgErro .= "Neste n�o � poss�vel informar interv�los de c�digos ou datas.<br><br>";
        db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
        exit;
    }
}


$clempparametro        = new cl_empparametro;
$clempautitem       = new cl_empautitem;
$clcgmalt           = new cl_cgmalt;
$cldb_pcforneconpad = new cl_pcforneconpad;
$clempempitem       = new cl_empempitem;
$clemite_nota_emp   = new cl_emite_nota_empenho;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$head3 = "CADASTRO DE C�DIGOS";
//$head5 = "PER�ODO : ".$mes." / ".$ano;

$sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao ";
$sqlpref .= "  from db_config                                                     ";
$sqlpref .= " inner join cgm on cgm.z01_numcgm = db_config.numcgm                 ";
$sqlpref .=    "	where codigo = " . db_getsession("DB_instit");

$resultpref = db_query($sqlpref);

db_fieldsmemory($resultpref, 0);

$anousu = db_getsession("DB_anousu");
$dbwhere = '1=1';
if (isset($e60_numemp) && $e60_numemp != '') {
    $dbwhere     = " e60_numemp = $e60_numemp ";
    $sql         = "select e60_anousu as anousu from empempenho where $dbwhere";
    $res_empenho = @db_query($sql);
    $numrows_empenho = @pg_numrows($res_empenho);
    if ($numrows_empenho != 0) {
        db_fieldsmemory($res_empenho, 0);
    }
} else if ((isset($e60_codemp) && $e60_codemp != '') && (isset($e60_codemp_fim) && $e60_codemp_fim != '')) {
    $arr = split("/", $e60_codemp);
    $arr2 = split("/", $e60_codemp_fim);
    if (count($arr) == 2  && isset($arr[1]) && $arr[1] != '') {
        $dbwhere_ano = " and e60_anousu = " . $arr[1];
        $anousu = $arr[1];
    } else {
        $dbwhere_ano = " and e60_anousu = " . db_getsession("DB_anousu");
    }
    $dbwhere = "e60_codemp::integer >=" . $arr[0] . " and e60_codemp::integer <=" . $arr2[0] . "$dbwhere_ano";
} else if (isset($e60_codemp) && $e60_codemp != '') {
    $arr = split("/", $e60_codemp);
    if (count($arr) == 2  && isset($arr[1]) && $arr[1] != '') {
        $dbwhere_ano = " and e60_anousu = " . $arr[1];
        $anousu = $arr[1];
    } else {
        $dbwhere_ano = " and e60_anousu = " . db_getsession("DB_anousu");
    }
    $dbwhere = "e60_codemp='" . $arr[0] . "'$dbwhere_ano";
} else {
    if (isset($dtini_dia)) {
        $dbwhere = " e60_emiss >= '$dtini_ano-$dtini_mes-$dtini_dia'";

        if (isset($dtfim_dia)) {
            $dbwhere .= " and e60_emiss <= '$dtfim_ano-$dtfim_mes-$dtfim_dia'";
        }
    }
}

if (isset($listacgm) && $listacgm != '') {
    if ($ver == 'com')
        $dbwhere .= "and cgm.z01_numcgm in ($listacgm)";
    elseif ($ver == 'sem')
        $dbwhere .= "and cgm.z01_numcgm not in ($listacgm)";
}

$sqlemp = $clemite_nota_emp->get_sql_empenho(db_getsession("DB_anousu"), db_getsession("DB_instit"), $dbwhere);

$result = db_query($sqlemp);
 //db_criatabela($result);exit;

if (pg_numrows($result) == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado !  ");
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf, '6');
$pdf1->objpdf->SetTextColor(0, 0, 0);
//$pdf1->objpdf->Output();

//rotina que pega o numero de vias
//add campo e30_impobslicempenho
$sCampos      = "e30_nroviaemp,e30_numdec,e30_impobslicempenho,e30_dadosbancoempenho";
$sSqlEmpParam = $clempparametro->sql_query_file(db_getsession("DB_anousu"), $sCampos);
$result02     = $clempparametro->sql_record($sSqlEmpParam);
if ($clempparametro->numrows == 0) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado na empparametro!");
}

db_fieldsmemory($result02, 0);

//recebido variavel
$pdf1->nvias              = $e30_nroviaemp;
$pdf1->casadec            = $e30_numdec;
$pdf1->dadosbancoemprenho = $e30_dadosbancoempenho;

for ($i = 0; $i < pg_numrows($result); $i++) {

    db_fieldsmemory($result, $i);

    $pdf1->evento             = $evento;
    $sSqlPcFornecOnPad  = $cldb_pcforneconpad->sql_query(null, "*", null, "pc63_numcgm = {$e60_numcgm}");
    $rsSqlPcFornecOnPad = $cldb_pcforneconpad->sql_record($sSqlPcFornecOnPad);

    if (!$rsSqlPcFornecOnPad == false && $cldb_pcforneconpad->numrows > 0) {
        $oPcFornecOnPad     = db_utils::fieldsMemory($rsSqlPcFornecOnPad, 0);
    } else {

        $oPcFornecOnPad = new stdClass();
        $oPcFornecOnPad->pc63_banco       = '';
        $oPcFornecOnPad->pc63_agencia     = '';
        $oPcFornecOnPad->pc63_agencia_dig = '';
        $oPcFornecOnPad->pc63_conta       = '';
        $oPcFornecOnPad->pc63_conta_dig   = '';
    }

    $sSqlPacto  = $clemite_nota_emp->get_sql_pacto($e61_autori);
    $rsPacto    = db_query($sSqlPacto);

    $o74_descricao       = null;
    $o78_pactoplano      = null;
    if (@pg_num_rows($rsPacto) > 0) {

        $oPacto              = db_utils::fieldsMemory($rsPacto, 0);
        $o74_descricao       = $oPacto->o74_descricao;
        $o78_pactoplano      = $oPacto->o74_sequencial;
    }

    $sProcessoAdministrativo = $clemite_nota_emp->get_sql_processo_administrativo($e61_autori);

    $sCondtipos = "";
    if (isset($tipos) && !empty($tipos)) {
        $sCondtipos = " $tipos as tipos, ";
    }

    $sqlitem    = $clemite_nota_emp->get_sql_item($sCondtipos, $e60_numemp);
    $resultitem = db_query($sqlitem);

    db_fieldsmemory($resultitem);


    $result_cgmalt = $clcgmalt->sql_record($clcgmalt->sql_query_file(null, "z05_numcgm as z01_numcgm,z05_nome as z01_nome,z05_telef as z01_telef,z05_ender as z01_ender,z05_numero as z01_numero,z05_munic as z01_munic,z05_cgccpf as z01_cgccpf,z05_cep as z01_cep", " abs(z05_data_alt - date '$e60_emiss') asc, z05_sequencia desc limit 1", "z05_numcgm = $z01_numcgm and z05_data_alt > '$e60_emiss' "));

    if ($clcgmalt->numrows > 0) {
        db_fieldsmemory($result_cgmalt, 0);
    }

    /**
     * Verificamos o cnpj da unidade. caso diferente de null, e diferente do xcnpj da institui��o,
     * mostramso a descri��o e o cnpj da unidade
     */
    if ($o41_cnpj != "" && $o41_cnpj != $cgc) {
        $nomeinst = $o41_descr;
        $cgc      = $o41_cnpj;
    }

    $sSqlFuncaoOrdenaPagamento = $clemite_nota_emp->get_sql_funcao_ordena_pagamento($cgmpaga,
                                                                                    date( 'Y',strtotime($e60_emiss)),
                                                                                    date('m',strtotime($e60_emiss)));


    $pdf1->cargoordenapagamento = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenaPagamento),0)->cargoordenapagamento;

    $sSqlFuncaoOrdenadespesa = $clemite_nota_emp->get_sql_funcao_ordena_despesa(
        $cgmordenadespesa,
        date('Y', strtotime($e60_emiss)),
        date('m', strtotime($e60_emiss))
    );

    $pdf1->cargoordenadespesa = db_utils::fieldsMemory(db_query($sSqlFuncaoOrdenadespesa), 0)->cargoordenadespesa;

    //assinaturas
    $pdf1->ordenadespesa   =  $ordenadesp;
    $pdf1->liquida         =  $liquida;
    $pdf1->ordenapagamento =  $ordenapaga;
    $pdf1->contador        =  $contador;
    $pdf1->crccontador     =  $crc;
    $pdf1->controleinterno =  $controleinterno;
    $pdf1->emptipo              = $e41_descr;
    $pdf1->prefeitura           = $nomeinst;
    $pdf1->enderpref            = $ender . ", " . $numero;
    $pdf1->cgcpref              = $cgc;
    $pdf1->municpref            = $munic;
    $pdf1->telefpref            = $telef;
    $pdf1->emailpref            = $email;

    $pdf1->inscricaoestadualinstituicao    = '';
    if ($db21_usasisagua == 't') {
        $pdf1->inscricaoestadualinstituicao    = "- Inscri��o Estadual: " . $inscricaoestadualinstituicao;
    }

    $pdf1->numcgm               = $z01_numcgm;
    $pdf1->nome                 = $z01_nome;
    $pdf1->telefone             = $z01_telef;
    $pdf1->ender                = $z01_ender;
    $pdf1->bairro               = $z01_bairro;
    $pdf1->munic                = $z01_munic;
    $pdf1->cnpj                 = $z01_cgccpf;
    $pdf1->cep                  = $z01_cep;
    $pdf1->ufFornecedor         = $z01_uf;
    $pdf1->prazo_entrega        = $e54_praent;
    $pdf1->condicao_pagamento   = $e54_conpag;
    $pdf1->outras_condicoes     = $e54_codout;
    $pdf1->iBancoFornecedor     = $oPcFornecOnPad->pc63_banco;
    $pdf1->iAgenciaForncedor    = $oPcFornecOnPad->pc63_agencia . "-" . $oPcFornecOnPad->pc63_agencia_dig;
    $pdf1->iContaForncedor      = $oPcFornecOnPad->pc63_conta . "-" . $oPcFornecOnPad->pc63_conta_dig;
    $pdf1->dotacao              = $estrutural;
    $pdf1->solicitacao          = $pc10_numero;
    $pdf1->num_licitacao        = $e60_numerol;
    $pdf1->cod_concarpeculiar   = $e60_concarpeculiar;
    $pdf1->descr_concarpeculiar = substr($c58_descr, 0, 34);
    $pdf1->logo                 = $logo;
    $pdf1->SdescrPacto          = $o74_descricao;
    $pdf1->iPlanoPacto          = $o78_pactoplano;
    $pdf1->contrapartida        = $e56_orctiporec;
    $pdf1->observacaoitem       = "pc23_obs";
    $pdf1->Snumeroproc          = "pc81_codproc";
    $pdf1->Snumero              = "pc11_numero";
    $pdf1->marca                = "e55_marca";
    $pdf1->processo_administrativo = $sProcessoAdministrativo;
    $pdf1->coddot           = $o58_coddot;
    $pdf1->destino          = $e60_destin;
    $pdf1->licitacao        = $e60_codtipo;
    $pdf1->recorddositens   = $resultitem;
    $pdf1->linhasdositens   = pg_numrows($resultitem);
    //Zera as vari�veis
    $pdf1->resumo = "";
    $resumo_lic   = "";

    $pdf1->edital_licitacao = $e60_numerol;
    $pdf1->modalidade = $e54_nummodalidade;
    $pdf1->descr_tipocompra = $pc50_descr;

    $result_licita = $clempautitem->sql_record($clempautitem->sql_query_lic(null, null, "distinct l20_edital, l20_numero, l20_anousu, l20_objeto,l03_descr", null, "e55_autori = $e54_autori "));

    if ($clempautitem->numrows > 0) {
        db_fieldsmemory($result_licita, 0);
        $pdf1->edital_licitacao = $l20_edital . '/' . $l20_anousu;
        $pdf1->ano_licitacao = $l20_anousu;
        $pdf1->modalidade = $l20_numero . '/' . $l20_anousu;
        $resumo_lic = $l20_objeto;
        $pdf1->observacaoitem = "pc23_obs";
    }


    if (isset($resumo_lic) && $resumo_lic != "") {
        if ($e30_impobslicempenho == 't') {
            $pdf1->resumo = $resumo_lic . "\n" . $e60_resumo;
        } else {
            $pdf1->resumo = $e60_resumo;
        }
    } else {
        $pdf1->resumo = $e60_resumo;
    }


    $Sresumo = $pdf1->resumo;
    $vresumo = split("\n", $Sresumo);

    if (count($vresumo) > 1) {
        $Sresumo   = "";
        $separador = "";
        for ($x = 0; $x < count($vresumo); $x++) {
            if (trim($vresumo[$x]) != "") {
                $separador = ". ";
                $Sresumo  .= $vresumo[$x] . $separador;
            }
        }
    }

    if (count($vresumo) == 0) {
        $Sresumo = str_replace("\n", ". ", $Sresumo);
    }

    $Sresumo = str_replace("\r", "", $Sresumo);

    $pdf1->resumo = substr($Sresumo, 0, 730);

    if (in_array($e54_tipoautorizacao, array('0', '1', '2', '3', '4'))) {

        $oAutoriza = $clemite_nota_emp->get_dados_licitacao($e54_tipoautorizacao, $e54_autori, $pc50_descr);

        $pdf1->edital_licitacao = $oAutoriza->edital_licitacao;
        $pdf1->modalidade       = $oAutoriza->modalidade;
        $pdf1->resumo           = $e60_resumo;
        $pdf1->descr_tipocompra = $oAutoriza->descr_tipocompra;
        $pdf1->descr_modalidade = $oAutoriza->descr_modalidade;
    }

    $oAcordo = $clemite_nota_emp->get_acordo($e60_numemp);

    $pdf1->resumo  = substr($pdf1->resumo, 0, 730);

    if (!empty($e54_praent)) {
        $pdf1->prazo_ent              = $e54_praent;
    } else {
        $pdf1->prazo_ent              = db_utils::fieldsMemory($resultitem, 0)->l20_prazoentrega;
    }

    $pdf1->quantitem        = "e62_quant";
    $pdf1->valoritem        = "e62_vltot";
    $pdf1->valor            = "e62_vlrun";
    $pdf1->descricaoitem    = "pc01_descrmater";
    $pdf1->complmater       = "pc01_complmater";
    $pdf1->sequenitem       = "e62_sequen";
    $pdf1->m61_descr       = "m61_descr";

    $pdf1->orcado            = $e60_vlrorc;
    $pdf1->saldo_ant        = $e60_salant;
    $pdf1->empenhado        = $e60_vlremp;
    $pdf1->numemp           = $e60_numemp;
    /*OC4401*/
    $pdf1->usuario          = $nome;
    /*FIM - OC4401*/
    $pdf1->codemp           = $e60_codemp;
    $pdf1->numaut           = $e61_autori;
    $pdf1->orgao            = $o58_orgao;
    $pdf1->descr_orgao      = $o40_descr;
    $pdf1->unidade          = $o58_unidade;
    $pdf1->descr_unidade    = $o41_descr;
    $pdf1->funcao           = $o58_funcao;
    $pdf1->descr_funcao     = $o52_descr;
    $pdf1->subfuncao        = $o58_subfuncao;
    $pdf1->descr_subfuncao  = $o53_descr;
    $pdf1->programa         = $o58_programa;
    $pdf1->descr_programa   = $o54_descr;
    $pdf1->projativ         = $o58_projativ;
    $pdf1->descr_projativ   = $o55_descr;
    $pdf1->analitico        = "o56_elemento";
    $pdf1->descr_analitico  = "o56_descr";
    $pdf1->sintetico        = $sintetico;
    $pdf1->descr_sintetico  = $descr_sintetico;
    $pdf1->recurso          = $o58_codigo;
    $pdf1->descr_recurso    = $o15_descr;
    $pdf1->banco            = null;
    $pdf1->agencia          = null;
    $pdf1->conta            = null;
    $pdf1->tipos            = $tipos;
    $pdf1->numero           = $z01_numero;
    $pdf1->marca            = 'e55_marca';
    $pdf1->acordo           = $oAcordo->ac16_numeroacordo;
    $pdf1->anoacordo        = $oAcordo->ac16_anousu;
    $pdf1->seqacordo        = $oAcordo->ac16_sequencial;


    $clControleOrc = new ControleOrcamentario;
    $e60_codco = $e60_codco == null ? '0000' : $e60_codco;
    $clControleOrc->setCodCO($e60_codco);
    
    $pdf1->codco  = $e60_codco.' - '.$clControleOrc->getDescricaoResumoCO();

    $sql  = "select c61_codcon
              from conplanoreduz
                   inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu
                   inner join consistema on c52_codsis = c60_codsis
             where c61_instit   = " . db_getsession("DB_instit") . "
               and c61_anousu   =" . db_getsession("DB_anousu") . "
               and c61_codigo   = $o58_codigo
               and c52_descrred = 'F' ";
    $result_conta = db_query($sql);

    if ($result_conta != false && (pg_numrows($result_conta) == 1)) {

        db_fieldsmemory($result_conta, 0);
        $sqlconta     = "select * from conplanoconta where c63_codcon = $c61_codcon and c63_anousu = " . db_getsession("DB_anousu");
        $result_conta = db_query($sqlconta);

        if (pg_result($result_conta, 0) == 1) {

            db_fieldsmemory($result_conta, 0);
            $pdf1->banco            = $c63_banco;
            $pdf1->agencia          = $c63_agencia;
            $pdf1->conta            = $c63_conta;
        }
    }

    $pdf1->emissao          = db_formatar($e60_emiss, 'd');
    $pdf1->texto            = "";
    $pdf1->imprime();
}
//include("fpdf151/geraarquivo.php");

if ($oConfiguracaoGed->utilizaGED()) {

    try {

        $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::EMPENHO;

        $oGerenciador = new GerenciadorEletronicoDocumento();
        $oGerenciador->setLocalizacaoOrigem("tmp/");
        $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$e60_numemp}.pdf");

        $oStdDadosGED        = new stdClass();
        $oStdDadosGED->nome  = $sTipoDocumento;
        $oStdDadosGED->tipo  = "NUMERO";
        $oStdDadosGED->valor = $e60_numemp;
        $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$e60_numemp}.pdf");
        $oGerenciador->moverArquivo(array($oStdDadosGED));

    } catch (Exception $eErro) {

        db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
    }
} else {
    $pdf1->objpdf->Output();
}
