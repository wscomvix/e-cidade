<?
include("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
$tipo_mesini = 1;
$tipo_mesfim = 1;

//$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
//$tipo_agrupa = 1;
// 1 = geral
// 2 = orgao
// 3 = unidade
//$tipo_nivel = 6;
// 1 = funcao
// 2 = subfuncao
// 3 = programa
// 4 = projeto/atividade
// 5 = elemento
// 6 = recurso
$tipo_agrupa = 3;
$tipo_nivel = 6;

$qorgao = 0;
$qunidade = 0;


include("fpdf151/pdf.php");
include("libs/db_sql.php");

//db_postmemory($HTTP_POST_VARS,2);exit;
db_postmemory($HTTP_POST_VARS);

$anousu  = db_getsession("DB_anousu");
$diainicio = 01;
$mesinicio = 01;

$diafim = 31;
$mesfim = 12;


$dataini = $anousu . '-' . $mesinicio . '-' . $diainicio;
$datafin = $anousu . '-' . $mesfim . '-' . $diafim;

$data_ini_exibida = $diainicio . '/' . $mesinicio . '/' . $anousu;
$data_fin_exibida = $diafim . '/' . $mesfim . '/' . $anousu;

//---------------------------------------------------------------
$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits = $clselorcdotacao->getInstit();

if (trim(@$instits) == "") {
    $instits = db_getsession("DB_instit");
}

/*
 echo "<br>instit : $instits";
 echo "<br>parametros : $parametros";
 echo "<br>dados : ".$selorcdotacao->getDados();
 exit;
*/

//@ recupera as informa��es fornecidas para gerar os dados
//---------------------------------------------------------------
switch ($nivel) {

    case '1A':
        $mensagem = '�rg�o At� o N�vel';
        break;

    case '1B':
        $mensagem = '�rg�o s� o N�vel';
        break;

    case '2A':
        $mensagem = 'Unidade At� o N�vel';
        break;

    case '2B':
        $mensagem = 'Unidade s� o N�vel';
        break;

    case '3A':
        $mensagem = 'Fun��o At� o N�vel';
        break;

    case '3B':
        $mensagem = 'Fun��o s� o N�vel';
        break;

    case '4A':
        $mensagem = 'Subfun��o At� o N�vel';
        break;

    case '4B':
        $mensagem = 'Subfun��o s� o N�vel';
        break;

    case '5A':
        $mensagem = 'Programa At� o N�vel';
        break;

    case '5B':
        $mensagem = 'Programa s� o N�vel';
        break;

    case '6A':
        $mensagem = 'Proj/Ativ At� o N�vel';
        break;
    case '6B':
        $mensagem = 'Proj/Ativ s� o N�vel';
        break;
    case '10A':
        $mensagem = 'Modalidade de Aplica��o at� o N�vel';
        break;
    case '10B':
        $mensagem = 'Modalidade de Aplica��o s� o N�vel';
        break;

    case '7A':
        $mensagem = 'Elemento At� o N�vel';
        break;

    case '7B':
        $mensagem = 'Elemento s� o N�vel';
        break;

    case '8A':
        $mensagem = 'Recurso At� o N�vel';
        break;

    case '8B':
        $mensagem = 'Recurso s� o N�vel ';
        break;

    case '9A':
        $mensagem = 'Recurso At� o N�vel - Completo';
        break;
}




$head1 = "QUADRO DE DETALHAMENTO DAS DESPESAS-QDD";
$head2 = "EXERC�CIO: " . db_getsession("DB_anousu");
$head6 = "N�VEL:   " . $mensagem;
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (" . str_ireplace('-', ',', $db_selinstit) . ")");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {

    db_fieldsmemory($resultinst, $xins);

    $descr_inst .= $xvirg . $nomeinst;
    $xvirg = ', ';
}
$head3 = "INSTITUI��ES : " . $descr_inst;
$head5 = "Per�odo : " . $data_ini_exibida . "   �  " . $data_fin_exibida;
/////////////////////////////////////////////////////////

$sele_work = $clselorcdotacao->getDados() . " and w.o58_instit in (" . str_ireplace('-', ',', $db_selinstit) . ")";

//Filtro abaixo � incluido com o sql dinamico para as colunas
//comprometido e automatico.
$filtro = str_replace("1=1", "", $sele_work);
$filtro = " " . str_replace("w.", "", $filtro);
$modalidade_aplicacao = false;
if (substr($nivel, -1) == 'A') {
    $completo = false;
    $nivela = substr($nivel, 0, 1);
    if ($nivela == "9") {
        $completo = true;
        $nivela = "8";
    } elseif (substr($nivel, 0, 2) == "10") {
        $modalidade_aplicacao = true;
        $nivela = "8";
    }
    //db_criatabela(pg_exec("select * from t"));

    if ($modalidade_aplicacao) {
        $result = getDotacaoSaldoModalidadeAplic($nivela, 1, 2, true, $sele_work, $anousu, $dataini, $datafin);
    } else {
        $result = db_dotacaosaldo($nivela, 1, 2, true, $sele_work, $anousu, $dataini, $datafin);
    }
    pg_exec("commit");

    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();

    $total = 0;
    $pdf->setfillcolor(235);
    $pdf->setfont('arial', 'b', 7);
    $troca         = 1;
    $alt           = 4;
    $qualou        = 0;
    $totproj       = 0;
    $totativ       = 0;
    $pagina        = 1;
    $xorgao        = 0;
    $xunidade      = 0;
    $xfuncao       = 0;
    $xsubfuncao    = 0;
    $xprograma     = 0;
    $xprojativ     = 0;
    $xelemento     = 0;

    $totorgaoini   = 0;
    $totorgaosup   = 0;
    $totorgaoesp   = 0;
    $totorgaored   = 0;
    $totorgaoemp   = 0;
    $totorgaoliq   = 0;
    $totorgaopag   = 0;

    $totorgaoanter   = 0;
    $totorgaoreser   = 0;
    $totorgaoatual   = 0;
    $totorgaocomp    = 0;
    $totorgaoresauto = 0;

    $totunidaini   = 0;
    $totunidasup   = 0;
    $totunidaesp   = 0;
    $totunidared   = 0;
    $totunidaemp   = 0;
    $totunidaliq   = 0;
    $totunidapag   = 0;

    $totunidaanter   = 0;
    $totunidareser   = 0;
    $totunidaatual   = 0;
    $totunidacomp    = 0;
    $totunidaresauto = 0;

    $nGeralTotOrgaoini     = 0;
    $nGeralTotOrgaosup     = 0;
    $nGeralTotOrgaoesp     = 0;
    $nGeralTotOrgaored     = 0;
    $nGeralTotOrgaoemp     = 0;
    $nGeralTotOrgaoliq     = 0;
    $nGeralTotOrgaopag     = 0;
    $nGeralTotOrgaoanter   = 0;
    $nGeralTotOrgaoreser   = 0;
    $nGeralTotOrgaocomp    = 0;
    $nGeralTotOrgaoresauto = 0;
    $nGeralTotOrgaoatual   = 0;

    $pagina        = 1;

    for ($i = 0; $i < pg_numrows($result); $i++) {
        $automatico = 0;

        db_fieldsmemory($result, $i);

        $oRelatorio = db_utils::fieldsMemory($result, $i);

        $sQuery = retornaSqlReservado($oRelatorio, $nivela, $anousu, $dataini, $datafin);

        $rsQuery = db_query($sQuery);

        if ($rsQuery !== false) {

            $oQuery = db_utils::fieldsMemory($rsQuery, 0);
        }

        $nResevaAutomatica = $oQuery->total;

        if ($xorgao . $xunidade != $o58_orgao . $o58_unidade && $quebra_unidade == 'S' && $pagina != 1 && $totunidaanter != 0) {
            $pdf->setfont('arial', 'b', 7);
            $pagina = 1;
            $pdf->ln(3);

            if ($completo == false) {
                $pdf->setfont('arial', 'b', 7);
                $pdf->ln(3);
                $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
                $pdf->cell(85, $alt, 'TOTAL DA UNIDADE ', "TB", 0, "L", 1);
                $pdf->cell(25, $alt, db_formatar($totunidaini, 'f'), "TBL", 1, "R", 1);
                /*$pdf->cell(25,$alt,db_formatar($totunidaanter,'f')  ,"TBL",0,"R",1);
				$pdf->cell(25,$alt,db_formatar($totunidacomp,'f')   ,"TBL",0,"R",1);
				$pdf->cell(25,$alt,db_formatar($totunidaresauto,'f'),"TBL",0,"R",1);
				$pdf->cell(25,$alt,db_formatar($totunidareser,'f')  ,"TBL",0,"R",1);
				$pdf->cell(20,$alt,db_formatar($totunidaatual,'f')  ,"TBL",1,"R",1);*/
            } else {

                $pdf->setfont('arial', 'b', 7);
                $pdf->cell(105, $alt, 'TOTAL DA UNIDADE - SALDOS', "T", 0, "C", 1);
                $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
                $pdf->cell(25, $alt, db_formatar($totunidaini, 'f'), 1, 0, "R", 1);
                /* $pdf->cell(25,$alt,db_formatar($totunidaini+$totunidasup+$totunidaesp-$totunidared,'f'),1,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidacomp    ,'f'),1  ,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidaresauto ,'f'),1  ,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidareser   ,'f'),1  ,0,"R",1);
	      $pdf->cell(20,2*$alt,db_formatar($totunidaatual ,'f'),"TLB",1,"R",1);*/
                $y = $pdf->GetY();
                $pdf->SetY($y - $alt);
                $pdf->cell(105, $alt, 'TOTAIS DA UNIDADE EXECU��O', "TB", 0, "C", 1);
                $pdf->cell(30, $alt, db_formatar($totunidasup, 'f'), 1, 1, "R", 1);
                /* $pdf->cell(25,$alt,db_formatar($totunidaesp,'f'),1   ,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidared,'f'),1   ,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidaemp,'f'),1   ,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidaliq,'f'),1   ,0,"R",1);
	      $pdf->cell(25,$alt,db_formatar($totunidapag,'f'),1   ,0,"R",1);*/
                $pdf->ln(2);
            }
            $pdf->setfont('arial', '', 7);
            $totunidaini   = 0;
            $totunidaanter = 0;
            $totunidareser = 0;
            $totunidaatual = 0;
            $totunidasup   = 0;
            $totunidaesp   = 0;
            $totunidared   = 0;
            $totunidaemp   = 0;
            $totunidaliq   = 0;
            $totunidapag   = 0;
        }

        if ($xorgao != $o58_orgao && $quebra_orgao == 'S') {
            $pdf->setfont('arial', 'b', 7);
            $pagina = 1;
            $pdf->ln(3);

            if ($completo == false) {
                $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
                $pdf->cell(85, $alt, 'TOTAL DO ORG�O ', "TB", 0, "L", 1);
                $pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), "TBL", 1, "R", 1);
                /*$pdf->cell(25,$alt,db_formatar($totorgaoanter,'f')   ,"TBL",0,"R",1);
      	$pdf->cell(25,$alt,db_formatar($totorgaocomp,'f')    ,"TBL",0,"R",1);
      	$pdf->cell(25,$alt,db_formatar($totorgaoresauto,'f') ,"TBL",0,"R",1);
      	$pdf->cell(25,$alt,db_formatar($totorgaoreser,'f')   ,"TBL",0,"R",1);
      	$pdf->cell(20,$alt,db_formatar($totorgaoatual,'f')   ,"TBL",1,"R",1);*/
            } else {
                if ($pdf->gety() > $pdf->h - 30) {
                    $pdf->addpage("L");
                }
                $pdf->setfont('arial', 'b', 7);
                $pdf->cell(105, $alt, 'TOTAL DO ORG�O - SALDOS', "T", 0, "C", 1);
                $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
                $pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), 1, 0, "R", 1);
                /*$pdf->cell(25,$alt  ,db_formatar($totorgaoini+$totorgaosup+$totorgaoesp-$totorgaored,'f'),1,0,"R",1);
        $pdf->cell(25,$alt  ,db_formatar($totorgaocomp    ,'f'),1    ,0,"R",1);
        $pdf->cell(25,$alt  ,db_formatar($totorgaoresauto ,'f'),1    ,0,"R",1);
        $pdf->cell(25,$alt  ,db_formatar($totorgaoreser   ,'f'),1    ,0,"R",1);
        $pdf->cell(20,2*$alt,db_formatar($totorgaoatual ,'f'),"TLB",1,"R",1);*/
                $y = $pdf->GetY();
                $pdf->SetY($y - $alt);
                $pdf->cell(105, $alt, 'TOTAIS DO ORG�O EXECU��O', "TB", 0, "C", 1);
                $pdf->cell(30, $alt, db_formatar($totorgaosup, 'f'), 1, 1, "R", 1);
                /* $pdf->cell(25,$alt,db_formatar($totorgaoesp,'f'),1   ,0,"R",1);
        $pdf->cell(25,$alt,db_formatar($totorgaored,'f'),1   ,0,"R",1);
        $pdf->cell(25,$alt,db_formatar($totorgaoemp,'f'),1   ,0,"R",1);
        $pdf->cell(25,$alt,db_formatar($totorgaoliq,'f'),1   ,0,"R",1);
        $pdf->cell(25,$alt,db_formatar($totorgaopag,'f'),1   ,0,"R",1);*/
            }


            $pdf->setfont('arial', '', 7);

            $nGeralTotOrgaoini   += $totorgaoini;
            $nGeralTotOrgaosup   += $totorgaosup;
            $nGeralTotOrgaoesp   += $totorgaoesp;
            $nGeralTotOrgaored   += $totorgaored;
            $nGeralTotOrgaoemp   += $totorgaoemp;
            $nGeralTotOrgaoliq   += $totorgaoliq;
            $nGeralTotOrgaopag   += $totorgaopag;
            $nGeralTotOrgaoanter += $totorgaoanter;
            $nGeralTotOrgaoreser += $totorgaoreser;
            $nGeralTotOrgaoatual += $totorgaoatual;


            $totorgaoini   = 0;
            $totorgaoanter = 0;
            $totorgaoreser = 0;
            $totorgaoatual = 0;
            $totorgaosup   = 0;
            $totorgaoesp   = 0;
            $totorgaored   = 0;
            $totorgaoemp   = 0;
            $totorgaoliq   = 0;
            $totorgaopag   = 0;
        }

        if ($pdf->gety() > $pdf->h - 30 || $pagina == 1) {
            //Novo cabe�alho
            $pagina = 0;
            $qualou = $o58_orgao . $o58_unidade;
            $pdf->addpage("L");
            $pdf->setfont('arial', 'b', 7);
            $pdf->ln(3);


            if ($completo == false) {
                $pdf->cell(160, 5, "DADOS DA DESPESA", "TBR", 0, "C", 1);
                //$pdf->cell(60,$alt,"RECURSO",0,0,"L",0);
                $pdf->cell(50, 5, "REDUZIDO", "TLBR", 0, "C", 1);
                $pdf->cell(70, 5, "VALOR OR�ADO", "TLBR", 1, "C", 1);
                $x = $pdf->GetX();
                $y = $pdf->GetY();

                $pdf->SetXY($x, $y + 5);
            } else {

                $pdf->cell(160, 5, "DADOS DA DESPESA", "TBR", 0, "C", 1);
                $pdf->cell(50, 5, "REDUZIDO", "TLBR", 0, "C", 1);
                $pdf->cell(70, 5, "VALOR OR�ADO", "TLBR", 1, "C", 1);

                $x = $pdf->GetX();
                $y = $pdf->GetY();


                /*$pdf->SetXY($x,$y+5);



        $pdf->SetXY($x-135,$y+10);

        $pdf->cell(90,5,"DETALHAMENTO DA EXECU��O DA DESPESA","BTR",0,"C",1);
        $pdf->SetX($pdf->GetX()+15);*/
            }
            //Fim do novo cabe�alho

            $pdf->cell(0, $alt, '', "T", 1, "C", 0);
            $pdf->setfont('arial', '', 7);
        }
        //echo $o58_orgao;exit; 2
        //echo $o40_descr;exit; gabinete do prefeito dados da despesa
        //echo $dot_ini;exit; 417000 valor or�ado
        if ($xorgao != $o58_orgao && $o58_orgao != 0) {

            $xorgao = $o58_orgao;
            if ($nivela == 1) {   // primeiro
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($o40_descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);

                $atual_menos_reservado = $atual - $reservado;
                //   $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0); // quinta coluna
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                //echo "deus";exit; // aqui	echo "pass";exit;
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($o40_descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, '', 'B', 1, "L", 0);
                $xunidade = 0;
            }
        }
        if ("$o58_orgao.$o58_unidade" != "$xorgao.$xunidade" && $o58_unidade != 0) {
            $xunidade = "$o58_unidade";
            if ($nivela == 2) {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($o41_descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);

                $atual_menos_reservado = $atual - $reservado;
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($o41_descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, '', 'B', 1, "L", 0);
            }
        }

        if ("$o58_orgao.$o58_unidade.$o58_funcao" != "$xfuncao" && $o58_funcao != 0) {
            $xfuncao = "$o58_orgao.$o58_unidade.$o58_funcao";
            $descr = $o52_descr;
            if ($nivela == 3) {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 33), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);

                $nComprometido = $reservado - $nResevaAutomatica;
                $atual_menos_reservado = $atual - $reservado;

                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, '', 'B', 1, "L", 0);
            }
        }
        if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao" != "$xsubfuncao" && $o58_subfuncao != 0) {
            $xsubfuncao = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao";
            $descr = $o53_descr;
            if ($nivela == 4) {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 'subfuncao'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);

                $atual_menos_reservado = $atual - $reservado;

                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'orgao') . db_formatar($o58_funcao, 'unidade') . "." . db_formatar($o58_subfuncao, 'subfuncao'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, '', 'B', 1, "L", 0);
            }
        }
        if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa" != "$xprograma" && (($nivela == 8 && $o54_descr != "") || $o58_programa != 0)) {
            $xprograma = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa";
            $descr = $o54_descr;
            if ($nivela == 5) {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);

                $nComprometido = $reservado - $nResevaAutomatica;



                $atual_menos_reservado = $atual - $reservado;
                //($atual_menos_reservado,'f'),0,1,"R",0);
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 'subfuncao') . "." . db_formatar($o58_programa, 'programa'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, '', 'B', 1, "L", 0);
            }
        }
        if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ" != "$xprojativ" && $o58_projativ != 0) {
            $xprojativ = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ";
            $descr = $o55_descr;
            if ($nivela == 6) {
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa') . "." . db_formatar($o58_projativ, 'projativ'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 0, 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
                //				$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
                $nComprometido = $reservado - $nResevaAutomatica;
                //				$pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
                //      $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
                //    $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
                $atual_menos_reservado = $atual - $reservado;
                //  $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
                if ($nivela != 7) {
                    //Totalizador do orgao
                    $totorgaoini      += $dot_ini;
                    $totorgaoanter    += $atual;
                    $totorgaocomp     += $nComprometido;
                    $totorgaoresauto  += $nResevaAutomatica;
                    $totorgaoreser    += $reservado;
                    $totorgaoatual    += $atual_menos_reservado;
                    //Totalizador da unidade
                    $totunidaini     += $dot_ini;
                    $totunidaanter   += $atual;
                    $totunidacomp    += $nComprometido;
                    $totunidaresauto += $nResevaAutomatica;
                    $totunidareser   += $reservado;
                    $totunidaatual   += $atual_menos_reservado;
                }
            } else {

                $pdf->setfont('arial', 'b', 7);
                $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 'subfuncao') . "." . db_formatar($o58_programa, 'programa') . "." . db_formatar($o58_projativ, 'projativ'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                if ($completo == false) {
                    $totProj   = getTotalNivel($o58_orgao, $o58_unidade, $o58_funcao, $o58_subfuncao,$o58_programa, $o58_projativ, $anousu, $dataini, $datafin);
                    $pdf->cell(130, $alt, db_formatar($totProj, 'f'), 'B', 1, "R", 0);
                    /*$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
				  $comprometido = $reservado - $nResevaAutomatica;
				  $pdf->cell(25,$alt,db_formatar($comprometido,'f'),0,0,"R",0);
				  $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
				  $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
				  $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);*/
                } else {
                    $pdf->cell(130, $alt, '', 'B', 1, "R", 0);
                }
                $pdf->setfont('arial', '', 7);
            }
        }
        if ("$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento" != "$xelemento" && $o58_elemento  != 0) {
            $xelemento = "$o58_orgao.$o58_unidade.$o58_funcao.$o58_subfuncao.$o58_programa.$o58_projativ.$o58_elemento";
            $descr = $o56_descr;

            if ($nivela == 7) {

                $pdf->cell(27, $alt, db_formatar($o58_elemento, 'elemento'), 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
                //				$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
                $nComprometido = $reservado - $nResevaAutomatica;
                /*				$pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
        $atual_menos_reservado = $atual - $reservado;
        $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);*/
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                /*$pdf->cell(27,$alt,db_formatar($o58_orgao,'orgao').db_formatar($o58_unidade,'orgao').db_formatar($o58_funcao,'orgao').".".db_formatar($o58_subfuncao,'s','0',3,'e').".".db_formatar($o58_programa,'orgao').".".$o58_projativ,'B',0,"L",0);
        $pdf->cell(80,$alt,"",'B',0,"L",0); ////verificar 4 campos
        $pdf->cell(30,$alt,"",'B',0,"L",0);
        $pdf->cell(130,$alt,"",'B',1,"L",0);*/
            }
        }
        if ($o58_codigo > 0) {
            $descr = $o56_descr;

            if ($completo == false) {
                $pdf->cell(27, $alt, $o58_elemento, 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(10, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 'B', 0, "C", 0);
                //$pdf->cell(15,$alt,$o58_coddot."-".db_CalculaDV($o58_coddot),0,0,"R",0);
                if ($nivela == 8) {
                    //$pdf->cell(27,$alt,"",'B',0,"L",0);
                    $pdf->cell(80, $alt, substr($o15_descr, 0, 30), 'B', 0, "L", 0);
                    /*$pdf->cell(30,$alt,"",'B',0,"L",0);
	       $pdf->cell(130,$alt,"",'B',1,"L",0);*/
                } else {
                    //$pdf->cell(27,$alt,"",'B',0,"L",0);
                    $pdf->cell(80, $alt, substr($o15_descr, 0, 30), 'B', 0, "L", 0);
                    /*$pdf->cell(30,$alt,"",'B',0,"L",0);
	      	 $pdf->cell(130,$alt,"",'B',1,"L",0);*/
                }
                $o58_coddot = $o58_coddot != '' ? $o58_coddot : '';
                $pdf->cell(-10, $alt, $o58_coddot, 'B', 0, "R", 0);
                $pdf->cell(80, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            }

            if ($completo == false) {
                //	$pdf->cell(25,$alt,db_formatar($dot_ini,'f'),'B',1,"R",0); // VERIFICAR
                /*	$pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
				$nComprometido = $reservado - $nResevaAutomatica;
				$pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);
        $atual_menos_reservado = $atual - $reservado;
        $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);*/
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } else {
                $pdf->setfont('arial', 'b', 7);
                $pdf->cell(27, $alt, $o58_elemento, 'B', 0, "L", 0);
                $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
                $pdf->cell(30, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 'B', 0, "C", 0);
                //        $pdf->cell(30,$alt,$o58_coddot."-".db_CalculaDV($o58_coddot),0,0,"C",0);
                //$pdf->cell(25,$alt,'',0,1,"R",0);
                //Inicial
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
                //Disponivel
                //      $pdf->cell(25,$alt,db_formatar($dot_ini+$suplemen_acumulado+$especial_acumulado-$reduzido_acumulado,'f'),0,0,"R",0);
                //Comprometido
                $nComprometido = $reservado - $nResevaAutomatica;
                /*    $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
                $atual_menos_reservado = $atual - $reservado;
                //        $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);

                /*$pdf->setfont('arial','',6);
        $pdf->SetX($pdf->GetX()+110);
        //cred suplemetar
  /*      $pdf->cell(25,$alt,db_formatar($suplemen_acumulado,'f'),0,0,"R",0);

        $pdf->cell(25,$alt,db_formatar($especial_acumulado,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reduzido_acumulado,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($empenhado-$anulado,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($pago,'f'),0,1,"R",0);
*/
                $totorgaoini     += $dot_ini;
                $totorgaocomp    += $nComprometido;
                $totorgaoresauto += $nResevaAutomatica;
                $totorgaosup     += $suplemen_acumulado;
                $totorgaoesp     += $especial_acumulado;
                $totorgaored     += $reduzido_acumulado;
                $totorgaoemp     += $empenhado - $anulado;
                $totorgaoliq     += $liquidado;
                $totorgaopag     += $pago;
                $totorgaoanter   += $atual;
                $totorgaoreser   += $reservado;
                $totorgaoatual   += $atual_menos_reservado;

                $totunidaini     += $dot_ini;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidasup     += $suplemen_acumulado;
                $totunidaesp     += $especial_acumulado;
                $totunidared     += $reduzido_acumulado;
                $totunidaemp     += $empenhado - $anulado;
                $totunidaliq     += $liquidado;
                $totunidapag     += $pago;
                $totunidaanter   += $atual;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            }


            if ($lista_subeleme == 'S') {
                $sql = "select *
					from orcelemento
					where substr(o56_elemento,1,7) = '" . str_replace('.', '', substr($o58_elemento, 0, 7)) . "' and
					      substr(o56_elemento,8,5) != '00000' and o56_anousu = " . db_getsession("DB_anousu") . " and
					      o56_orcado is true";
                $res = pg_exec($sql);
                for ($ne = 0; $ne < pg_numrows($res); $ne++) {
                    db_fieldsmemory($res, $ne);
                    $pdf->cell(20, $alt, $o56_elemento, 'B', 0, "L", 0);
                    $pdf->cell(80, $alt, $o56_descr, 'B', 0, "L", 0);
                    $pdf->cell(125, $alt, $o56_finali, 'B', 1, "L", 0);
                }
            }
        }
    }

    $nGeralTotOrgaoini     += $totorgaoini;
    $nGeralTotOrgaosup     += $totorgaosup;
    $nGeralTotOrgaoesp     += $totorgaoesp;
    $nGeralTotOrgaored     += $totorgaored;
    $nGeralTotOrgaoemp     += $totorgaoemp;
    $nGeralTotOrgaoliq     += $totorgaoliq;
    $nGeralTotOrgaopag     += $totorgaopag;
    $nGeralTotOrgaoanter   += $totorgaoanter;
    $nGeralTotOrgaocomp    += $totorgaocomp;
    $nGeralTotOrgaoresauto += $totorgaoresauto;
    $nGeralTotOrgaoreser   += $totorgaoreser;
    $nGeralTotOrgaoatual   += $totorgaoatual;

    if ($quebra_unidade == 'S') {
        if ($completo == false) {
            $pdf->setfont('arial', 'b', 7);
            $pdf->ln(3);
            $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
            $pdf->cell(85, $alt, 'TOTAL DA UNIDADE ', "TB", 0, "L", 1);
            $pdf->cell(25, $alt, db_formatar($totunidaini,  'f'), "TBL", 0, "R", 1);
            /*     $pdf->cell(25,$alt,db_formatar($totunidaanter,'f')  ,"TBL",0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidacomp, 'f')  ,"TBL",0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidaresauto,'f'),"TBL",0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidareser,'f')  ,"TBL",0,"R",1);
      $pdf->cell(20,$alt,db_formatar($totunidaatual,'f')  ,"TBL",1,"R",1);
   */
        } else {
            $pdf->setfont('arial', 'b', 7);
            $pdf->cell(105, $alt, 'TOTAL DA UNIDADE - SALDOS', "T", 0, "C", 1);
            $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
            $pdf->cell(25, $alt, db_formatar($totunidaini, 'f'), 1, 0, "R", 1);
            /* $pdf->cell(25,$alt,db_formatar($totunidaini+$totunidasup+$totunidaesp-$totunidared,'f'),1,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidacomp    ,'f'),1    ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidaresauto ,'f'),1    ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidareser   ,'f'),1    ,0,"R",1);
      $pdf->cell(20,2*$alt,db_formatar($totunidaatual ,'f'),"TLB",1,"R",1);*/
            $y = $pdf->GetY();
            $pdf->SetY($y - $alt);
            $pdf->cell(105, $alt, 'TOTAIS DA UNIDADE EXECU��O', "TB", 0, "C", 1);
            /*      $pdf->cell(30,$alt,db_formatar($totunidasup,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidaesp,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidared,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidaemp,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidaliq,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totunidapag,'f'),1   ,0,"R",1);*/
            $pdf->ln(2);
        }
    }
    $pdf->ln(3);
    if ($completo == false) {

        if ($quebra_orgao == "S" || $quebra_unidade == "S") {
            $pdf->setfont('arial', 'b', 7);
            $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
            $pdf->cell(85, $alt, 'TOTAL DO ORG�O ', "TB", 0, "L", 1);
            $pdf->cell(25, $alt, db_formatar($totorgaoini,  'f'), "TBL", 1, "R", 1);
            /*      $pdf->cell(25, $alt, db_formatar($totorgaoanter,  'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaocomp,   'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoresauto,'f'), "TBL", 0, "R", 1);
      $pdf->cell(25, $alt, db_formatar($totorgaoreser,  'f'), "TBL", 0, "R", 1);
      $pdf->cell(20, $alt, db_formatar($totorgaoatual,  'f'), "TBL", 1, "R", 1);
  */
        }
        $pdf->setfont('arial', 'b', 7);
        $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
        $pdf->cell(205, $alt, 'TOTAL GERAL ', "TB", 0, "L", 1);

        $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoini, 'f'), "TBL", 1, "R", 1);
        /* $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoanter  , 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaocomp   , 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoresauto, 'f'), "TBL", 0, "R", 1);
    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoreser  , 'f'), "TBL", 0, "R", 1);
    $pdf->cell(20, $alt, db_formatar($nGeralTotOrgaoatual  , 'f'), "TBL", 1, "R", 1);
*/
    } else {

        if ($quebra_orgao == "S" || $quebra_unidade == "S") {
            $pdf->setfont('arial', 'b', 7);
            $pdf->cell(105, $alt, 'TOTAL DO ORG�O - SALDOS', "T", 0, "C", 1);
            $pdf->cell(30, $alt, '', "TL", 0, "C", 1);
            $pdf->cell(25, $alt, db_formatar($totorgaoini, 'f'), 1, 0, "R", 1);
            //      $pdf->cell(25,$alt  ,db_formatar($totorgaoini+$totorgaosup+$totorgaoesp-$totorgaored,'f'),1,0,"R",1);
            /*     $pdf->cell(25,$alt  ,db_formatar($totorgaoanter,'f'),1,0,"R",1);
      $pdf->cell(25,$alt  ,db_formatar($totorgaocomp    ,'f'),1    ,0,"R",1);
      $pdf->cell(25,$alt  ,db_formatar($totorgaoresauto ,'f'),1    ,0,"R",1);
      $pdf->cell(25,$alt  ,db_formatar($totorgaoreser   ,'f'),1    ,0,"R",1);
      $pdf->cell(20,2*$alt,db_formatar($totorgaoatual ,'f'),"TLB",1,"R",1);*/
            $y = $pdf->GetY();
            $pdf->SetY($y - $alt);
            $pdf->cell(105, $alt, 'TOTAIS DO ORG�O EXECU��O', "TB", 0, "C", 1);
            $pdf->cell(30, $alt, db_formatar($totorgaosup, 'f'), 1, 1, "R", 1);
            /*      $pdf->cell(25,$alt,db_formatar($totorgaoesp,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totorgaored,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totorgaoemp,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totorgaoliq,'f'),1   ,0,"R",1);
      $pdf->cell(25,$alt,db_formatar($totorgaopag,'f'),1   ,1,"R",1);
  */
        }
        $pdf->setfont('arial', 'b', 7);
        $pdf->cell(105, $alt, 'TOTAL GERAL', "", 0, "C", 1);
        //$pdf->cell(30,$alt,'',"TL",0,"C",1);
        $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoini, 'f'), "", 0, "R", 1);

        //    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoini+$nGeralTotOrgaosup+$nGeralTotOrgaoesp-$nGeralTotOrgaored,'f'),"TL",0,"R",1);
        /* $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoanter  ,'f'),"TL",0,"R",1);

    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaocomp   ,'f')  ,"TL",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoresauto,'f')  ,"TL",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoreser  ,'f')  ,"TL",0,"R",1);
    $pdf->cell(20,2*$alt,db_formatar($nGeralTotOrgaoatual,'f'),"TLB",1,"R",1);*/

        /*$y = $pdf->GetY();
    $pdf->SetY($y-$alt);
    $pdf->cell(105,$alt,'TOTAIS DA EXECU��O',"",0,"C",1);

    $pdf->cell(30,$alt,db_formatar($nGeralTotOrgaosup,'f')  ,"TBL",1,"R",1);*/

        /*    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoesp,'f')  ,"TBL",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaored,'f')  ,"TBL",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoemp,'f')  ,"TBL",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaoliq,'f')  ,"TBL",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($nGeralTotOrgaopag,'f')  ,"TBRL",0,"R",1);
  */
    }
} else {

    if (substr($nivel, 0, 2) == "10") {
        $modalidade_aplicacao = true;
        $nivela = "8";
    } else {
        $nivela = substr($nivel, 0, 1);
    }

    $anousu  = db_getsession("DB_anousu");
    //$dataini = db_getsession("DB_anousu")."-01-01";
    //$datafin = date("Y-m-d",db_getsession("DB_datausu"));
    //db_criatabela(pg_exec("select * from temporario"));
    if ($modalidade_aplicacao) {
        $result = getDotacaoSaldoModalidadeAplic($nivela, 1, 2, true, $sele_work, $anousu, $dataini, $datafin);
    } else {
        $result = db_dotacaosaldo($nivela, 3, 2, true, $sele_work, $anousu, $dataini, $datafin);
    }
    //db_criatabela($result);exit;
    // funcao para gerar work
    //db_criatabela(pg_exec("select * from work w inner join temporario t on $sele_work "));exit;


    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $total = 0;
    $pdf->setfillcolor(235);
    $pdf->setfont('arial', 'b', 7);
    $troca           = 1;
    $alt             = 4;
    $qualou          = 0;
    $totproj         = 0;
    $totativ         = 0;
    $pagina          = 1;
    $xorgao          = 0;
    $xunidade        = 0;
    $xfuncao         = 0;
    $xsubfuncao      = 0;
    $xprograma       = 0;
    $xprojativ       = 0;
    $xelemento       = 0;
    $totorgaoanter   = 0;
    $totorgaoreser   = 0;
    $totorgaocomp    = 0;
    $totorgaoresauto = 0;
    $totorgaoini     = 0;
    $totorgaoatual   = 0;
    $totunidaanter   = 0;
    $totunidareser   = 0;
    $totunidacomp    = 0;
    $totunidaresauto = 0;
    $totunidaini     = 0;
    $totunidaatual   = 0;
    $nGeralTotOrgaoanter   = 0;
    $nGeralTotOrgaoatual   = 0;
    $nGeralTotOrgaocomp    = 0;
    $nGeralTotOrgaoresauto = 0;
    $nGeralTotOrgaoreser   = 0;
    $nGeralTotOrgaoini     = 0;
    $pagina = 1;

    for ($iLinha = 0; $iLinha < pg_numrows($result); $iLinha++) {

        $oRelatorio = db_utils::fieldsMemory($result, $iLinha);
        $sQuery = retornaSqlReservadoSoNivel($oRelatorio, $nivela, $anousu, $dataini, $datafin);
        //    echo $sQuery."<br><br>";
        $rsQuery = db_query($sQuery . $filtro);
        if ($rsQuery !== false) {

            $oQuery = db_utils::fieldsMemory($rsQuery, 0);
        }

        $nResevaAutomatica = $oQuery->total;
        //    echo "<pre>";
        //    var_dump($oRelatorio);
        //    echo "</pre>";
        //    echo "ReservaAutomatica = ".$nResevaAutomatica."<br>";
        db_fieldsmemory($result, $iLinha);
        $k = $iLinha;
        if ($pdf->gety() > $pdf->h - 30 || $pagina == 1) {

            $pagina = 0;
            $qualou = $o58_orgao . $o58_unidade;
            $pdf->addpage("L");
            $pdf->setfont('arial', 'b', 7);
            $pdf->ln(2);
            $pdf->cell(160, 5, "DADOS DA DESPESA", "TBR", 0, "C", 1);
            //$pdf->cell(60,$alt,"RECURSO",0,0,"L",0);
            $pdf->cell(50, 5, "REDUZIDO", "TLBR", 0, "C", 1);
            $pdf->cell(70, 5, "VALOR OR�ADO", "TLBR", 1, "C", 1);
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            $pdf->SetXY($x, $y + 5);

            // $pdf->cell(25,5,"DISPON�VEL"        ,"TLBR" ,0,"C",1);

            $pdf->setfont('arial', '', 7);
        }
        if ($nivela == 1) {
            $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($o40_descr, 0, 80), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //    $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*  $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            //      $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }

        if ($nivela == 2) {
            $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($o41_descr, 0, 80), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //    $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*  $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            //        $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }
        $descr = $o52_descr;

        if ($nivela == 3) { //Fun��o s� o N�vel
            $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //      $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*    $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            //        $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }

        $descr = $o53_descr;
        if ($nivela == 4) {
            $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 'subfuncao'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($descr, 0, 80), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //      $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*    $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            //        $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }

        $descr = $o54_descr;
        if ($nivela == 5) {
            $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'funcao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($descr, 0, 33), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //      $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*    $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            // $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }
        $descr = $o55_descr;

        if ($nivela == 6) {
            $pdf->cell(27, $alt, db_formatar($o58_orgao, 'orgao') . db_formatar($o58_unidade, 'unidade') . db_formatar($o58_funcao, 'orgao') . "." . db_formatar($o58_subfuncao, 's', '0', 3, 'e') . "." . db_formatar($o58_programa, 'programa') . "." . db_formatar($o58_projativ, 'projativ'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($descr, 0, 33), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //        $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*      $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            // $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);

            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }
        $descr = $o56_descr;

        if ($nivela == 7) {
            $pdf->cell(27, $alt, db_formatar($o58_elemento, 'elemento'), 'B', 0, "L", 0);
            $pdf->cell(80, $alt, substr($descr, 0, 33), 'B', 0, "L", 0);
            $pdf->cell(30, $alt, '', 'B', 0, "L", 0);
            $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
            //        $pdf->cell(25,$alt,db_formatar($atual,'f'),0,0,"R",0);
            $nComprometido = $reservado - $nResevaAutomatica;
            /*      $pdf->cell(25,$alt,db_formatar($nComprometido,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($nResevaAutomatica,'f'),0,0,"R",0);
        $pdf->cell(25,$alt,db_formatar($reservado,'f'),0,0,"R",0);*/
            $atual_menos_reservado = $atual - $reservado;
            // $pdf->cell(20,$alt,db_formatar($atual_menos_reservado,'f'),0,1,"R",0);
            //Totalizador do orgao
            $totorgaoini      += $dot_ini;
            $totorgaoanter    += $atual;
            $totorgaocomp     += $nComprometido;
            $totorgaoresauto  += $nResevaAutomatica;
            $totorgaoreser    += $reservado;
            $totorgaoatual    += $atual_menos_reservado;
            //Totalizador da unidade
            $totunidaini     += $dot_ini;
            $totunidaanter   += $atual;
            $totunidacomp    += $nComprometido;
            $totunidaresauto += $nResevaAutomatica;
            $totunidareser   += $reservado;
            $totunidaatual   += $atual_menos_reservado;
        }

        if ($nivela == 8) {

            if (substr($nivel, 0, 2) == "10" && $o58_codigo > 0) {

                $pdf->cell(27, $alt, $o58_elemento, 'B', 0, "L", 0);
                $pdf->cell(53, $alt, substr($descr, 0, 30), 'B', 0, "L", 0);

                $pdf->cell(27, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 'B', 0, "C", 0);
                $pdf->cell(80, $alt, substr($o15_descr, 0, 80), 'B', 0, "L", 0);
                $o58_coddot = $o58_coddot != '' ? $o58_coddot : '';
                $pdf->cell(67, $alt, $o58_coddot, "B", 0, "R", 0);
                $pdf->cell(25, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
                $nComprometido = $reservado - $nResevaAutomatica;
                $atual_menos_reservado = $atual - $reservado;
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            } elseif (substr($nivel, 0, 2) != "10") {
                $descr = $o56_descr;

                $pdf->cell(27, $alt, db_formatar($o58_codigo, 's', '0', 4, 'e'), 'B', 0, "C", 0);
                $pdf->cell(80, $alt, substr($o15_descr, 0, 80), 'B', 0, "L", 0);
                $o58_coddot = $o58_coddot != '' ? $o58_coddot : '';
                $pdf->cell(30, $alt, $o58_coddot, "B", 0, "R", 0);
                $pdf->cell(130, $alt, db_formatar($dot_ini, 'f'), 'B', 1, "R", 0);
                $nComprometido = $reservado - $nResevaAutomatica;
                $atual_menos_reservado = $atual - $reservado;
                //Totalizador do orgao
                $totorgaoini      += $dot_ini;
                $totorgaoanter    += $atual;
                $totorgaocomp     += $nComprometido;
                $totorgaoresauto  += $nResevaAutomatica;
                $totorgaoreser    += $reservado;
                $totorgaoatual    += $atual_menos_reservado;
                //Totalizador da unidade
                $totunidaini     += $dot_ini;
                $totunidaanter   += $atual;
                $totunidacomp    += $nComprometido;
                $totunidaresauto += $nResevaAutomatica;
                $totunidareser   += $reservado;
                $totunidaatual   += $atual_menos_reservado;
            }
        }
    }

    $nGeralTotOrgaoanter   += $totorgaoanter;
    $nGeralTotOrgaoatual   += $totorgaoatual;
    $nGeralTotOrgaocomp    += $totorgaocomp;
    $nGeralTotOrgaoresauto += $totorgaoresauto;
    $nGeralTotOrgaoreser   += $totorgaoreser;
    $nGeralTotOrgaoini     += $totorgaoini;


    $pdf->ln(3);
    $pdf->setfont('arial', 'b', 7);
    $pdf->cell(50, $alt, '', "TB", 0, "L", 1);
    $pdf->cell(205, $alt, 'TOTAL GERAL ', "TB", 0, "L", 1);

    $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoini, 'f'), "TBL", 1, "R", 1);
    /*$pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoanter  , 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaocomp   , 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoresauto, 'f'), "TBL", 0, "R", 1);
  $pdf->cell(25, $alt, db_formatar($nGeralTotOrgaoreser  , 'f'), "TBL", 0, "R", 1);
  $pdf->cell(20, $alt, db_formatar($nGeralTotOrgaoatual  , 'f'), "TBL", 1, "R", 1);
  */
}
$pdf->Output();

pg_exec("commit");
/**
 * Fun��o para retornar um sql dinamico
 * conforme os nivel em execu��o
 *
 * @param object $oData
 * @param integer $iNivel
 * @param integer $iAnousu
 */
function retornaSqlReservado($oData, $iNivel, $iAnousu, $dataini, $datafin)
{

    $sSqlWhere  = " and o58_orgao     = " . $oData->o58_orgao;

    if (!empty($oData->o58_unidade)) {
        $sSqlWhere .= " and o58_unidade   = " . $oData->o58_unidade;
    }
    if (!empty($oData->o58_funcao)) {
        $sSqlWhere .= " and o58_funcao    = " . $oData->o58_funcao;
    }
    if (!empty($oData->o58_subfuncao)) {
        $sSqlWhere .= " and o58_subfuncao = " . $oData->o58_subfuncao;
    }
    if (!empty($oData->o58_programa)) {
        $sSqlWhere .= " and o58_programa  = " . $oData->o58_programa;
    }
    if (!empty($oData->o58_projativ)) {
        $sSqlWhere .= " and o58_projativ  = " . $oData->o58_projativ;
    }
    if (!empty($oData->o58_elemento)) {
        $sSqlWhere .= " and o56_elemento  = '" . $oData->o58_elemento . "'";
    }
    if (!empty($oData->o58_codigo)) {
        $sSqlWhere .= " and o58_codigo    = " . $oData->o58_codigo;
    }
    $sSql = "select coalesce(sum(o80_valor),0) as total
	           from orcreservager
	                inner join orcreserva on o84_codres = o80_codres
	                inner join orcdotacao on o58_coddot = o80_coddot
	                                     and o80_anousu = o58_anousu
	                inner join orcelemento on o58_codele = o56_codele
	                                      and o58_anousu = o56_anousu

	          where o80_anousu = $iAnousu
	            and o84_data between '$dataini' and '$datafin'
	             ";

    $sSql .= $sSqlWhere;

    return $sSql;
}

/**
 * Fun��o para retornar um sql dinamico
 * conforme os nivel em execu��o
 *
 * @param object $oData
 * @param integer $iNivel
 * @param integer $iAnousu
 */
function retornaSqlReservadoSoNivel($oData, $iNivel, $iAnousu, $dataini, $datafin)
{

    switch ($iNivel) {
        case 1:
            $sSqlWhere  = " and o58_orgao = " . $oData->o58_orgao;
            break;
        case 2:
            $sSqlWhere  = " and o58_orgao   = " . $oData->o58_orgao;
            $sSqlWhere .= " and o58_unidade = " . $oData->o58_unidade;
            break;
        case 3:
            $sSqlWhere  = " and o58_funcao  = " . $oData->o58_funcao;
            break;
        case 4:
            $sSqlWhere  = " and o58_subfuncao = " . $oData->o58_subfuncao;
            break;
        case 5:
            $sSqlWhere = " and o58_programa  = " . $oData->o58_programa;
            break;
        case 6:
            $sSqlWhere = " and o58_projativ  = " . $oData->o58_projativ;
            break;
        case 7:
            $sSqlWhere = " and o56_elemento  = '" . $oData->o58_elemento . "'";
            break;
        case 8:
            $sSqlWhere = " and o58_codigo    = " . $oData->o58_codigo;
            break;
        default:
            $sSqlWhere = "";
    }

    $sSql = "select coalesce(sum(o80_valor),0) as total
             from orcreservager
                  inner join orcreserva on o84_codres = o80_codres
                  inner join orcdotacao on o58_coddot = o80_coddot
                                       and o80_anousu = o58_anousu
                  inner join orcelemento on o58_codele = o56_codele
                                        and o58_anousu = o56_anousu

            where o80_anousu = $iAnousu
              and o84_data between '$dataini' and '$datafin'
               ";

    $sSql .= $sSqlWhere;

    return $sSql;
}

/**
 * Fun��o para agrupar os elementos na modalidade de aplica��o
 */
function getDotacaoSaldoModalidadeAplic($nivela, $tipo_nivel, $tipo_saldo, $descr, $sele_work, $anousu, $dataini, $datafin)
{

    $sql    = db_dotacaosaldo($nivela, $tipo_nivel, $tipo_saldo, $descr, $sele_work, $anousu, $dataini, $datafin, 8, 0, true);
    $sql2   = " SELECT o58_orgao,
                        o40_descr,
                        o58_unidade,
                        o41_descr,
                        o58_funcao,
                        o52_descr,
                        o58_subfuncao,
                        o53_descr,
                        o58_programa,
                        o54_descr,
                        o58_projativ,
                        o55_descr,
                        o55_finali,
                        o55_tipopasta,
                        o55_tipoensino,
                        orcelementoanalitico.o56_elemento AS o58_elemento,
                        orcelementoanalitico.o56_descr,
                        o58_codigo,
                        o15_descr,
                        sum(dot_ini) AS dot_ini,
                        sum(saldo_anterior) AS saldo_anterior,
                        sum(empenhado) AS empenhado,
                        sum(anulado) AS anulado,
                        sum(liquidado) AS liquidado,
                        sum(pago) AS pago,
                        sum(suplementado) AS suplementado,
                        sum(reduzido) AS reduzido,
                        sum(atual) AS atual,
                        sum(reservado) AS reservado,
                        sum(atual_menos_reservado) AS atual_menos_reservado,
                        sum(atual_a_pagar) AS atual_a_pagar,
                        sum(atual_a_pagar_liquidado) AS atual_a_pagar_liquidado,
                        sum(empenhado_acumulado) AS empenhado_acumulado,
                        sum(anulado_acumulado) AS anulado_acumulado,
                        sum(liquidado_acumulado) AS liquidado_acumulado,
                        sum(pago_acumulado) AS pago_acumulado,
                        sum(suplementado_acumulado) AS suplementado_acumulado,
                        sum(reduzido_acumulado) AS reduzido_acumulado,
                        sum(proj) AS proj,
                        sum(ativ) AS ativ,
                        sum(oper) AS oper,
                        sum(ordinario) AS ordinario,
                        sum(vinculado) AS vinculado,
                        sum(suplemen) AS suplemen,
                        sum(suplemen_acumulado) AS suplemen_acumulado,
                        sum(especial) AS especial,
                        sum(especial_acumulado) AS especial_acumulado,
                        sum(reservado_manual_ate_data) AS reservado_manual_ate_data,
                        sum(reservado_automatico_ate_data) AS reservado_automatico_ate_data,
                        sum(reservado_ate_data) AS reservado_ate_data
                FROM ($sql) as xxxx

                        LEFT JOIN orcelemento AS orcelementoanalitico ON substr(o58_elemento,1,5)||'00000000' = o56_elemento
                            AND o56_anousu = $anousu
                GROUP by o58_orgao,
                            o40_descr,
                            o58_unidade,
                            o41_descr,
                            o58_funcao,
                            o52_descr,
                            o58_subfuncao,
                            o53_descr,
                            o58_programa,
                            o54_descr,
                            o58_projativ,
                            o55_descr,
                            o55_finali,
                            o55_tipopasta,
                            o55_tipoensino,
                            orcelementoanalitico.o56_elemento,
                            orcelementoanalitico.o56_descr,
                            o58_codigo,
                            o15_descr
                ORDER BY o58_orgao,
                            o40_descr,
                            o58_unidade,
                            o41_descr,
                            o58_funcao,
                            o52_descr,
                            o58_subfuncao,
                            o53_descr,
                            o58_programa,
                            o54_descr,
                            o58_projativ,
                            o55_descr,
                            o55_finali,
                            o55_tipopasta,
                            o55_tipoensino,
                            o58_elemento,
                            o56_descr,
                            o58_codigo,
                            o15_descr";
    return db_query($sql2);
}

function getTotalNivel($o58_orgao, $o58_unidade, $o58_funcao, $o58_subfuncao,$o58_programa, $o58_projativ,  $anousu, $dataini, $datafin){

    $sQuery = "select sum(o58_valor) as o58_valor from orcdotacao where o58_anousu ={$anousu} ";
    if($o58_orgao !== null) {
        $sQuery .= " and o58_orgao = ".$o58_orgao;
    }
    if($o58_unidade !== null) {
        $sQuery .= " and o58_unidade = ".$o58_unidade;
    }
    if($o58_funcao !== null) {
        $sQuery .= " and o58_funcao = ".$o58_funcao;
    }
    if($o58_subfuncao !== null) {
        $sQuery .= " and o58_subfuncao = ".$o58_subfuncao;
    }
    if($o58_programa !== null) {
        $sQuery .= " and o58_programa = ".$o58_programa;
    }
    if($o58_projativ !== null) {
        $sQuery .= " and o58_projativ = ".$o58_projativ;
    }
    $rsQuery = db_query($sQuery);

    if ($rsQuery !== false) {
        $oQuery = db_utils::fieldsMemory($rsQuery, 0);
    }

    return $oQuery->o58_valor;
}
