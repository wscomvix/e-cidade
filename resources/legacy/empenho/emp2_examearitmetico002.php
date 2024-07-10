<?
include("fpdf151/pdf.php");
include("classes/db_cgm_classe.php");
require_once("libs/db_utils.php");
include("libs/db_liborcamento.php");

$clselorcdotacao = new cl_selorcdotacao();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

/*
 * Definindo o per�odo em que ser�o selecionado os dados
 */
$iUltimoDiaMes = date("d", mktime(0, 0, 0, $MesReferencia + 1, 0, db_getsession("DB_anousu")));
$sDataInicial = db_getsession("DB_anousu") . "-{$MesReferencia}-01";
$sDataFinal = db_getsession("DB_anousu") . "-{$MesReferencia}-{$iUltimoDiaMes}";

$sWhere = "";

if (isset($filtros) && $filtros != '') {
    
    $clselorcdotacao->setDados($filtros);
    $sql_filtro = $clselorcdotacao->getDados(false);
    $sWhere .= $sql_filtro;

}

switch ($MesReferencia) {
    case "01":
        $sMes = "Janeiro";
        break;
    case "02":
        $sMes = "Fevereiro";
        break;
    case "03":
        $sMes = "Mar�o";
        break;
    case "04":
        $sMes = "Abril";
        break;
    case "05":
        $sMes = "Maio";
        break;
    case "06":
        $sMes = "Junho";
        break;
    case "07":
        $sMes = "Julho";
        break;
    case "08":
        $sMes = "Agosto";
        break;
    case "09":
        $sMes = "Setembro";
        break;
    case "10":
        $sMes = "Outubro";
        break;
    case "11":
        $sMes = "Novembro";
        break;
    case "12":
        $sMes = "Dezembro";
        break;
}

if ($recursos != '') {
    $sWhere .= "and o15_codigo in ({$recursos})";

    $sTipoPasta = $recursos;
} else {
    $sWhere .= '';
    $sTipoPasta = "Todos";
}

if (isset($iTipo) && $iTipo != '') {
    
    if ($iTipo == 1) {
        
        $sWhere .= " and o15_codigo in (102,202,15000002,25000002,148,149,150,151,152) ";
        $sTipoPasta = "Sa�de 15% ";

    } elseif ($iTipo == 2) {

        $sWhere .= " and o15_codigo in (101, 201, 15000001,25000001) ";
        $sTipoPasta = "Educa��o 25% ";

    } elseif ($iTipo == 3) {
        
        $sWhere .= " and o15_codigo in (118,119, 218, 219,166,167,266,267,15400007,25400007,15400000,25400000,15420007,25420007,15420000,25420000) ";
        $sTipoPasta = "Fundeb (70% e 30%) ";

    } elseif ($iTipo == 4) {
        $sWhere .= " and ( o15_codigo not in (101, 102, 118, 119, 202, 201, 218, 219,166,167,266,267,157, 17520000,257, 27520000,116, 17500000,216,27500000,15000002,25000002,15000001,25000001,15400007,25400007,15400000,25400000,15420007,25420007,15420000,25420000, 159, 153, 132, 155, 121, 123, 176, 177, 179, 180, 178, 112, 259, 253, 232, 255, 221, 223, 276, 277, 279, 280, 278, 212,148,149,150,151,152,129, 156, 142, 229, 256, 242,107, 147, 143, 144, 145, 146, 122, 171, 172, 175, 174, 173, 120, 106, 113, 207, 247, 243, 244, 245, 246, 222, 271, 272 ,275 ,274 ,273 ,220 ,206 ,213)) ";
        $sWhere .= " and ( SUBSTRING(o15_codtri,1, 4) not in ('1600', '1601', '1602', '1603', '1604' ,'1605', '1621', '1622', '1631', '1632', '1633', '1634', '1635', '1636', '1659', '2600', '2601', '2602', '2603', '2604', '2605', '2621', '2622', '2631', '2632', '2633', '2634', '2635', '2636', '2659','1660', '1661', '1662', '1665', '1669', '2660', '2661', '2662', '2665', '2669','1541', '1543', '1544', '1550', '1551', '1552', '1553', '1569', '1570', '1571', '1572', '1573', '1574', '1575', '1576', '1599', '2541', '2543', '2544', '2550', '2551', '2552', '2553', '2569', '2570', '2571', '2572', '2573', '2574', '2575', '2576', '2599')) ";
        $sTipoPasta = "Geral ";
    } elseif ($iTipo == 5) {
        
        $sWhere .= " and o15_codigo in (118, 218,166,266,15400007,25400007,15420007,25420007) ";
        $sTipoPasta = "Fundeb 70% ";

    } elseif ($iTipo == 6) {
        
        $sWhere .= " and o15_codigo in (119, 219,167,267, 15400000,25400000,15420000,25420000) ";
        $sTipoPasta = "Fundeb 30% ";

    } elseif ($iTipo == 7) {
        
        $sWhere .= " and o15_codigo in (157, 17520000,257, 27520000) ";
        $sTipoPasta = "Multas de Transito ";

    } elseif ($iTipo == 8) {
        
        $sWhere .= " and o15_codigo in (116, 17500000,216,27500000) ";
        $sTipoPasta = "CIDE ";

    } elseif ($iTipo == 9) {
        
        $sWhere .= " and (( SUBSTRING(o15_codtri,1, 4) in ('1600', '1601', '1602', '1603', '1604', '1605', '1621', '1622', '1631', '1632', '1633', '1634', '1635', '1636', '1659', '2600', '2601', '2602', '2603', '2604', '2605', '2621', '2622', '2631', '2632', '2633', '2634', '2635', '2636', '2659'))  or o15_codigo in ('159', '153', '132', '155', '121', '123', '176', '177', '179', '180', '178', '112', '259', '253', '232', '255', '221', '223', '276', '277', '279', '280', '278', '212 ')) ";
        $sTipoPasta = "Vinculados � Sa�de ";

    }elseif ($iTipo == 10) {
        
        $sWhere .= " and (( SUBSTRING(o15_codtri,1, 4) in ('1660', '1661', '1662', '1665', '1669', '2660', '2661', '2662', '2665', '2669'))  or o15_codigo in ('129', '156', '142', '229', '256', '242')) ";
        $sTipoPasta = "Vinculados � Assistencia ";

    }elseif ($iTipo == 11) {
        
        $sWhere .= " and (( SUBSTRING(o15_codtri,1, 4) in ('1541', '1543', '1544', '1550', '1551', '1552', '1553', '1569', '1570', '1571', '1572', '1573', '1574', '1575', '1576', '1599', '2541', '2543', '2544', '2550', '2551', '2552', '2553', '2569', '2570', '2571', '2572', '2573', '2574', '2575', '2576', '2599'))  or o15_codigo in ('107', '147', '143', '144', '145', '146', '122', '171', '172', '175', '174', '173', '120', '106', '113', '207', '247', '243', '244', '245', '246', '222', '271', '272 ','275 ','274 ','273 ','220 ','206 ','213 ')) ";
        $sTipoPasta = "Vinculados � Educa��o ";

    }


}

$head3 = "Exame Aritm�tico";

$head5 = "M�s de Refer�ncia: $sMes";

$head7 = "Tipo Recursos: $sTipoPasta";

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
//$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial', 'B', 8);
$tam = '05'; 

if ($iRestosPagar != 2) {

    if($ordenar == 01) {
        $sSql = "select * from
        ( select coremp.k12_empen, e60_numemp, e60_codemp, e60_emiss, e60_numerol,e60_tipol,
            case when e49_numcgm is null then e60_numcgm
                else e49_numcgm end as
            e60_numcgm,
            k12_codord as e50_codord,
            case when e49_numcgm is null then cgm.z01_nome
                else cgmordem.z01_nome end as z01_nome,
            k12_valor,
            k12_cheque,
            e60_anousu,
            coremp.k12_autent,
            coremp.k12_data,
            k13_conta,
            k13_descr,
            o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,
            o58_funcao,o58_programa,
            o55_descr,
            o15_codtri,o15_codigo,o15_descr,o15_tipo,
            o56_elemento,
                            o56_descr,
                            e50_data,
            case when e60_anousu < " . db_getsession("DB_anousu") . " then 'RP'
                else 'Emp' end as tipo,
                e50_obs
        from coremp
            inner join empempenho on e60_numemp = k12_empen
                        and e60_instit = " . db_getsession("DB_instit") . "
            inner join orcdotacao on e60_anousu = o58_anousu
                        and e60_coddot = o58_coddot
            inner join orcprojativ on o58_anousu = o55_anousu
                        and o58_projativ = o55_projativ
            inner join orctiporec on o58_codigo = o15_codigo
            inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
            inner join pagordem on e50_codord = k12_codord
            left join pagordemconta on e50_codord = e49_codord
            inner join corrente on corrente.k12_id = coremp.k12_id
                        and corrente.k12_data=coremp.k12_data
                        and corrente.k12_autent= coremp.k12_autent
            inner join cgm on cgm.z01_numcgm = e60_numcgm
            left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
            inner join saltes on saltes.k13_conta = corrente.k12_conta
        where " . $sWhere . " and coremp.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' 
        order by o58_orgao, o58_unidade, o58_subfuncao, o58_funcao, o58_programa, o58_projativ, o56_elemento,e60_codemp) as xxxxx where 1 = 1";
    }else if($ordenar == 02) {
        $sSql = "SELECT *
    FROM
        (SELECT coremp.k12_empen,e60_numemp,e60_codemp,e60_emiss,e60_numerol,e60_tipol,
                CASE
                    WHEN e49_numcgm IS NULL THEN e60_numcgm
                    ELSE e49_numcgm
                END AS e60_numcgm,
                k12_codord AS e50_codord,
                CASE
                    WHEN e49_numcgm IS NULL THEN cgm.z01_nome
                    ELSE cgmordem.z01_nome
                END AS z01_nome,
                k12_valor,k12_cheque,e60_anousu,coremp.k12_autent,coremp.k12_data,k13_conta,
                k13_descr,o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,o58_funcao,
                o58_programa,o55_descr,o15_codtri,o15_codigo,o15_descr,o15_tipo,o56_elemento,o56_descr,e50_data,
                CASE
                    WHEN e60_anousu < " . db_getsession("DB_anousu") . " THEN 'RP'
                    ELSE 'Emp'
                END AS tipo,
                e50_obs
        FROM coremp
        INNER JOIN empempenho ON e60_numemp = k12_empen AND e60_instit = " . db_getsession("DB_instit") . "
        INNER JOIN orcdotacao ON e60_anousu = o58_anousu AND e60_coddot = o58_coddot
        INNER JOIN orcprojativ ON o58_anousu = o55_anousu AND o58_projativ = o55_projativ
        INNER JOIN orctiporec ON o58_codigo = o15_codigo
        INNER JOIN orcelemento ON o58_codele = o56_codele AND o58_anousu = o56_anousu
        INNER JOIN pagordem ON e50_codord = k12_codord
        LEFT JOIN pagordemconta ON e50_codord = e49_codord
        INNER JOIN corrente ON corrente.k12_id = coremp.k12_id AND corrente.k12_data=coremp.k12_data
        AND corrente.k12_autent = coremp.k12_autent
        INNER JOIN cgm ON cgm.z01_numcgm = e60_numcgm
        LEFT JOIN cgm cgmordem ON cgmordem.z01_numcgm = e49_numcgm
        INNER JOIN saltes ON saltes.k13_conta = corrente.k12_conta
        WHERE " . $sWhere . " and coremp.k12_data BETWEEN '" . $sDataInicial . "' and '" . $sDataFinal . "'
        ORDER BY o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_funcao,o58_programa,o58_projativ,o56_elemento,e60_codemp) AS xxxxx
    WHERE 1 = 1";
    }else if($ordenar == 03) {
        $sSql = "select * from
        ( select coremp.k12_empen, e60_numemp, e60_codemp, e60_emiss, e60_numerol,e60_tipol,
            case when e49_numcgm is null then e60_numcgm
                else e49_numcgm end as
            e60_numcgm,
            k12_codord as e50_codord,
            case when e49_numcgm is null then cgm.z01_nome
                else cgmordem.z01_nome end as z01_nome,
            k12_valor,
            k12_cheque,
            e60_anousu,
            coremp.k12_autent,
            coremp.k12_data,
            k13_conta,
            k13_descr,
            o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,
            o58_funcao,o58_programa,
            o55_descr,
            o15_codtri,o15_codigo,o15_descr,o15_tipo,
            o56_elemento,
                            o56_descr,
                            e50_data,
            case when e60_anousu < " . db_getsession("DB_anousu") . " then 'RP'
                else 'Emp' end as tipo,
                e50_obs
        from coremp
            inner join empempenho on e60_numemp = k12_empen
                        and e60_instit = " . db_getsession("DB_instit") . "
            inner join orcdotacao on e60_anousu = o58_anousu
                        and e60_coddot = o58_coddot
            inner join orcprojativ on o58_anousu = o55_anousu
                        and o58_projativ = o55_projativ
            inner join orctiporec on o58_codigo = o15_codigo
            inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
            inner join pagordem on e50_codord = k12_codord
            left join pagordemconta on e50_codord = e49_codord
            inner join corrente on corrente.k12_id = coremp.k12_id
                        and corrente.k12_data=coremp.k12_data
                        and corrente.k12_autent= coremp.k12_autent
            inner join cgm on cgm.z01_numcgm = e60_numcgm
            left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
            inner join saltes on saltes.k13_conta = corrente.k12_conta
        where " . $sWhere . " and coremp.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' 
        order by o58_coddot,o58_orgao, o58_unidade, o58_subfuncao, o58_funcao, o58_programa, o58_projativ, o56_elemento,e60_codemp) as xxxxx where 1 = 1";
        
    }
    
    $rsResult = db_query($sSql);
    $aDadosAgrupados = array();
    for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {

        $oResult = db_utils::fieldsMemory($rsResult, $iCont);

        $sHash = $oResult->e50_codord;
        if (!isset($aDadosAgrupados[$sHash])) {

            if ($oResult->tipo == 'Emp') {
                $aDadosAgrupados[$sHash] = $oResult;
            }

        } else {
            $aDadosAgrupados[$sHash]->k12_valor += $oResult->k12_valor;
        }

    }

    $pdf->SetFont("", "B", "");
    $pdf->Cell(282, $tam, "EMPENHOS DO M�S", 1, 1, "C", 1);
    $nTotalizador = 0;
    foreach ($aDadosAgrupados as $oResult) {

        if ($oResult->k12_valor > 0) {

            if ($oResult->o58_coddot != $sReduzido || $iCont == 0) {

                $pdf->SetFont("", "B", "");
                $pdf->Cell(25, $tam, "DOTA��O:", 1, 0, "C", 1);
                $pdf->Cell(25, $tam, "REDUZIDO: " . $oResult->o58_coddot, 1, 0, "C", 1);
                $pdf->Cell(135, $tam, str_pad($oResult->o58_orgao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_unidade, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_funcao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_subfuncao, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_programa, 4, "0", STR_PAD_LEFT) . str_pad($oResult->o58_projativ, 4, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o56_elemento, 6, "0", STR_PAD_LEFT) . " - " . $oResult->o56_descr, 1, 0, "C", 1);
                $pdf->Cell(97, $tam, substr($oResult->o15_codigo.' - '.$oResult->o15_descr,0,55), 1, 1, "C", 1);

                $pdf->Cell(20, $tam, "Empenho", 1, 0, "C", 1);
                $pdf->Cell(17, $tam, "Data Emp", 1, 0, "C", 1);
                $pdf->Cell(15, $tam, "OP", 1, 0, "C", 1);
                $pdf->Cell(17, $tam, "Data Ordem", 1, 0, "C", 1);
                $pdf->Cell(90, $tam, "Fornecedor", 1, 0, "C", 1);
                $pdf->Cell(17, $tam, "Data Pag", 1, 0, "C", 1);
                $pdf->Cell(35, $tam, "Valor", 1, 0, "C", 1);
                $pdf->Cell(15, $tam, "Num Lic", 1, 0, "C", 1);
                $pdf->Cell(15, $tam, "Mod Lic", 1, 0, "C", 1);
                $pdf->Cell(41, $tam, "N� DOC", 1, 1, "C", 1);
                $sReduzido = $oResult->o58_coddot;
                $pdf->SetFont("", "", "");

            }
            $pdf->Cell(20, $tam, $oResult->e60_codemp, 0, 0, "C", 0);
            $pdf->Cell(17, $tam, implode("/", array_reverse(explode("-", $oResult->e60_emiss))), 0, 0, "C", 0);
            $pdf->Cell(15, $tam, $oResult->e50_codord, 0, 0, "C", 0);
            $pdf->Cell(17, $tam, implode("/", array_reverse(explode("-", $oResult->e50_data))), 0, 0, "C", 0);
            $pdf->Cell(90, $tam, substr($oResult->z01_nome, 0, 50), 0, 0, "L", 0);
            $pdf->Cell(17, $tam, implode("/", array_reverse(explode("-", $oResult->k12_data))), 0, 0, "C", 0);
            $pdf->Cell(35, $tam, number_format($oResult->k12_valor, "2", ",", "."), 0, 0, "R", 0);
            $pdf->Cell(15, $tam, $oResult->e60_numerol, 0, 0, "C", 0);
            $pdf->Cell(15, $tam, $oResult->e60_tipol, 0, 0, "C", 0);
            $pdf->Cell(41, $tam, "", 0, 1, "C", 0);
            $pdf->Cell(282, "0.1", "", 1, 1, "C", 0);
            $pos_y = $pdf->y;
            $pos_x = $pdf->x;

            if($ExibirHistoricoDoEmpenho == 01){

            $pdf->multicell(282, $tam, "Hist�rico: " . $oResult->e50_obs, 0,"L");
            $pos_y = $pdf->y + 282;
            $pos_x = $pdf->x;
            $pdf->Cell(282, "0.1", "", 1, 1, "C", 0);

            }

            $nTotalizador += $oResult->k12_valor;

        }

    }
    $pdf->SetFont("", "B", "");
    $pdf->Cell(70, $tam, "Valor Total:", 1, 0, "C", 1);
    $pdf->Cell(212, $tam, number_format($nTotalizador, "2", ",", "."), 1, 1, "L", 1);
    $pdf->Ln();

}

if ($tipoExame == 2) {

    $sSqlSlips = "select h.c60_descr,ff.o15_descr,j.c61_codigo,k12_id, k12_autent, k12_data, k12_valor,
		case when (h.c60_codsis = 6 and f.c60_codsis = 6)
			then 'tran' when (h.c60_codsis = 6 and f.c60_codsis = 5)
			then 'tran' when (h.c60_codsis = 5 and f.c60_codsis = 6)
			then 'tran' else 'desp' end as tipo,
		k12_empen, k12_codord, k12_cheque, entrou as debito,
		f.c60_descr as descr_debito, f.c60_codsis as sis_debito,
		saiu as credito, h.c60_descr as descr_credito,
		h.c60_codsis as sis_credito, sl as k17_codigo,
		corhi as k12_histcor, sl_txt as k17_texto, dta as k17_data
		from
		(select k12_id, k12_autent, k12_data, k12_valor, tipo, k12_empen, k12_codord,
			k12_cheque, corlanc as entrou, corrente as saiu, slp as sl,
			corh as corhi, slp_txt as sl_txt, data as dta
				from (select *, case when coalesce(corl_saltes,0) = 0
					then 'desp' else 'tran' end as tipo
					from (select corrente.k12_id, corrente.k12_autent, corrente.k12_data,
						corrente.k12_valor, corrente.k12_conta as corrente,
						c.k13_conta as corr_saltes, b.k12_conta as corlanc,
						d.k13_conta as corl_saltes, p.k12_empen, p.k12_codord,
						p.k12_cheque, slip.k17_codigo as slp,
						corhist.k12_histcor as corh, slip.k17_texto as slp_txt, slip.k17_data as data, slip.k17_hist
							from corrente
								inner join corlanc b on corrente.k12_id = b.k12_id
									and corrente.k12_autent = b.k12_autent
									and corrente.k12_data = b.k12_data
								inner join slip on slip.k17_codigo = b.k12_codigo
								left join corhist on corhist.k12_id = b.k12_id
									and corhist.k12_data = b.k12_data
									and corhist.k12_autent = b.k12_autent
								left join coremp p on corrente.k12_id =
								p.k12_id
									and corrente.k12_autent=p.k12_autent
									and corrente.k12_data = p.k12_data
								left join saltes c on c.k13_conta = corrente.k12_conta
								left join saltes d on d.k13_conta = b.k12_conta
							where corrente.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "'
								and corrente.k12_instit = " . db_getsession("DB_instit") . " )
								as x )
							as xx )
						as xxx inner join conplanoexe e on entrou = e.c62_reduz
							and e.c62_anousu = " . db_getsession("DB_anousu") . "
					inner join conplanoreduz i on e.c62_reduz = i.c61_reduz
						and i.c61_anousu=" . db_getsession("DB_anousu") . " and i.c61_instit = " . db_getsession("DB_instit") . "
					inner join conplano f on i.c61_codcon = f.c60_codcon
						and i.c61_anousu = f.c60_anousu
					inner join conplanoexe g on saiu = g.c62_reduz
						and g.c62_anousu = " . db_getsession("DB_anousu") . "
					inner join conplanoreduz j on g.c62_reduz = j.c61_reduz
						and j.c61_anousu=" . db_getsession("DB_anousu") . "
					inner join conplano h on j.c61_codcon = h.c60_codcon
						and j.c61_anousu = h.c60_anousu
					inner join orctiporec ff on j.c61_codigo = ff.o15_codigo
					where tipo = 'desp'
					order by tipo, credito, k12_data, k12_autent";

    $rsResultSlips = db_query($sSqlSlips);
    $aDadosAgrupados = array();
    for ($iCont = 0; $iCont < pg_num_rows($rsResultSlips); $iCont++) {

        $oResultSlips = db_utils::fieldsMemory($rsResultSlips, $iCont);
        $sHash = $oResultSlips->k17_codigo;
        if (!isset($aDadosAgrupados[$sHash])) {
            $aDadosAgrupados[$sHash] = $oResultSlips;
        } else {
            $aDadosAgrupados[$sHash]->k17_valor += $oResultSlips->k17_valor;
        }

    }

    $pdf->SetFont("", "B", "");
    $pdf->Cell(282, $tam, "SLIPS DO M�S", 1, 1, "C", 1);
    $pdf->Cell(20, $tam, "C�digo SLIP", 1, 0, "C", 1);
    $pdf->Cell(91, $tam, "Descri��o", 1, 0, "C", 1);
    $pdf->Cell(18, $tam, "Data Slip", 1, 0, "C", 1);
    $pdf->Cell(21, $tam, "Dt Pagamento", 1, 0, "C", 1);
    $pdf->Cell(40, $tam, "Valor", 1, 0, "C", 1);
    $pdf->Cell(60, $tam, "Observa��o: ", 1, 0, "C", 1);
    $pdf->Cell(32, $tam, "N� DOC", 1, 1, "C", 1);
    $pdf->SetFont("", "", "");
    $nTotalizador = 0;
    foreach ($aDadosAgrupados as $oResultSlips) {

        if ($oResultSlips->k12_valor > 0) {

            $pdf->Cell(20, $tam, $oResultSlips->k17_codigo, 0, 0, "C", 0);
            $pdf->Cell(91, $tam, substr($oResultSlips->descr_debito, 0, 45), 0, 0, "L", 0);
            $pdf->Cell(18, $tam, implode("/", array_reverse(explode("-", $oResultSlips->k17_data))), 0, 0, "C", 0);
            $pdf->Cell(21, $tam, implode("/", array_reverse(explode("-", $oResultSlips->k12_data))), 0, 0, "C", 0);
            $pdf->Cell(40, $tam, number_format($oResultSlips->k12_valor, "2", ",", "."), 0, 0, "R", 0);
            $pdf->Cell(60, $tam, substr($oResultSlips->k17_hist, 0, 35), 0, 0, "L", 0);
            $pdf->Cell(32, $tam, "", 0, 1, "C", 0);
            $pdf->Cell(282, "0.1", "", 1, 1, "C", 0);
            $nTotalizador += $oResultSlips->k12_valor;

        }

    }
    $pdf->SetFont("", "B", "");
    $pdf->Cell(70, $tam, "Valor Total:", 1, 0, "C", 1);
    $pdf->Cell(212, $tam, number_format($nTotalizador, "2", ",", "."), 1, 1, "L", 1);

}

if ($iRestosPagar != 1) {
    if($ordenar == '3')
        $ordenarcampos = " o58_coddot ";
    else
        $ordenarcampos = " z01_nome ";    
    $sSql = "select * from
        ( select coremp.k12_empen, e60_numemp, e60_codemp, e60_emiss, e60_numerol,e60_tipol,
            case when e49_numcgm is null then e60_numcgm
                else e49_numcgm end as
            e60_numcgm,
            k12_codord as e50_codord,
            case when e49_numcgm is null then cgm.z01_nome
                else cgmordem.z01_nome end as z01_nome,
            k12_valor,
            k12_cheque,
            e60_anousu,
            coremp.k12_autent,
            coremp.k12_data,
            k13_conta,
            k13_descr,
            o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,
            o58_funcao,o58_programa,
            o55_descr,
            o15_codtri,o15_descr,o15_tipo,
            o56_elemento,
                            o56_descr,
                            e50_data,
            case when e60_anousu < " . db_getsession("DB_anousu") . " then 'RP'
                else 'Emp' end as tipo,
                e50_obs
        from coremp
            inner join empempenho on e60_numemp = k12_empen
                        and e60_instit = " . db_getsession("DB_instit") . "
            inner join orcdotacao on e60_anousu = o58_anousu
                        and e60_coddot = o58_coddot
            inner join orcprojativ on o58_anousu = o55_anousu
                        and o58_projativ = o55_projativ
            inner join orctiporec on o58_codigo = o15_codigo
            inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
            inner join pagordem on e50_codord = k12_codord
            left join pagordemconta on e50_codord = e49_codord
            inner join corrente on corrente.k12_id = coremp.k12_id
                        and corrente.k12_data=coremp.k12_data
                        and corrente.k12_autent= coremp.k12_autent
            inner join cgm on cgm.z01_numcgm = e60_numcgm
            left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
            inner join saltes on saltes.k13_conta = corrente.k12_conta
        where " . $sWhere . " and coremp.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' 
        order by o58_orgao, o58_unidade, o58_subfuncao, o58_funcao, o58_programa, o58_projativ, o56_elemento) as xxxxx
        where 1 = 1 and tipo = 'RP' order by ". $ordenarcampos ."";
   
    $rsResult = db_query($sSql);
    $aDadosAgrupados = array();
    for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {

        $oResult = db_utils::fieldsMemory($rsResult, $iCont);
        $sHash = $oResult->e50_codord;
        if (!isset($aDadosAgrupados[$sHash])) {

            if ($oResult->tipo == 'RP') {
                $aDadosAgrupados[$sHash] = $oResult;
            }

        } else {
            $aDadosAgrupados[$sHash]->k12_valor += $oResult->k12_valor;
        }

    }
    $pdf->SetFont("", "B", "");
    $pdf->Ln();
    $pdf->Cell(282, $tam, "RESTOS A PAGAR", 1, 1, "C", 1);
    $nTotalizador = 0;
    foreach ($aDadosAgrupados as $oResult) {

        if ($oResult->k12_valor > 0) {

            if ($oResult->o58_coddot != $sReduzido || $iCont == 0) {

                $pdf->SetFont("", "B", "");
                $pdf->Cell(25, $tam, "DOTA��O:", 1, 0, "C", 1);
                $pdf->Cell(25, $tam, "REDUZIDO: " . $oResult->o58_coddot, 1, 0, "C", 1);
                $pdf->Cell(135, $tam, str_pad($oResult->o58_orgao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_unidade, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_funcao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_subfuncao, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_programa, 4, "0", STR_PAD_LEFT) . str_pad($oResult->o58_projativ, 4, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o56_elemento, 6, "0", STR_PAD_LEFT) . " - " . $oResult->o56_descr, 1, 0, "C", 1);
                $pdf->Cell(97, $tam, substr($oResult->o15_codtri.' - '.$oResult->o15_descr,0,55), 1, 1, "C", 1);

                $pdf->Cell(20, $tam, "Empenho", 1, 0, "C", 1);
                $pdf->Cell(17, $tam, "Data Emp", 1, 0, "C", 1);
                $pdf->Cell(15, $tam, "OP", 1, 0, "C", 1);
                $pdf->Cell(17, $tam, "Data Ordem", 1, 0, "C", 1);
                $pdf->Cell(90, $tam, "Fornecedor", 1, 0, "C", 1);
                $pdf->Cell(17, $tam, "Data Pag", 1, 0, "C", 1);
                $pdf->Cell(35, $tam, "Valor", 1, 0, "C", 1);
                $pdf->Cell(15, $tam, "Num Lic", 1, 0, "C", 1);
                $pdf->Cell(15, $tam, "Mod Lic", 1, 0, "C", 1);
                $pdf->Cell(41, $tam, "N� DOC", 1, 1, "C", 1);
                $sReduzido = $oResult->o58_coddot;
                $pdf->SetFont("", "", "");

            }
            $pdf->Cell(20, $tam, $oResult->e60_codemp, 0, 0, "C", 0);
            $pdf->Cell(17, $tam, implode("/", array_reverse(explode("-", $oResult->e60_emiss))), 0, 0, "C", 0);
            $pdf->Cell(15, $tam, $oResult->e50_codord, 0, 0, "C", 0);
            $pdf->Cell(17, $tam, implode("/", array_reverse(explode("-", $oResult->e50_data))), 0, 0, "C", 0);
            $pdf->Cell(90, $tam, substr($oResult->z01_nome, 0, 35), 0, 0, "L", 0);
            $pdf->Cell(17, $tam, implode("/", array_reverse(explode("-", $oResult->k12_data))), 0, 0, "C", 0);
            $pdf->Cell(35, $tam, number_format($oResult->k12_valor, "2", ",", "."), 0, 0, "R", 0);
            $pdf->Cell(15, $tam, $oResult->e60_numerol, 0, 0, "C", 0);
            $pdf->Cell(15, $tam, $oResult->e60_tipol, 0, 0, "C", 0);
            $pdf->Cell(41, $tam, "", 0, 1, "C", 0);
            $pdf->Cell(282, "0.1", "", 1, 1, "C", 0);

            if($ExibirHistoricoDoEmpenho == 01){

            $pdf->Cell(282, $tam, "Hist�rico: " . substr($oResult->e50_obs, 0, 160), 0, 1, "L", 0);
            $pdf->Cell(282, "0.1", "", 1, 1, "C", 0);

            }

            $nTotalizador += $oResult->k12_valor;

        }

    }

    $pdf->SetFont("", "B", "");
    $pdf->Cell(70, $tam, "Valor Total:", 1, 0, "C", 1);
    $pdf->Cell(212, $tam, number_format($nTotalizador, "2", ",", "."), 1, 1, "L", 1);

}

$pdf->output();


?>
