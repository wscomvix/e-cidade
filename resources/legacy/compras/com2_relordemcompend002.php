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
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("classes/db_matordem_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatordem = new cl_matordem;
$iInstit    = db_getsession('DB_instit');
$iAnoUsu    = db_getsession("DB_anousu");
$dtDataUsu  = date("Y-m-d", db_getsession("DB_datausu"));
$sWhere     = "";
$sOrder     = "";
$agrupaForne = false;

if (isset($lFiltro) && $lFiltro == "t") {
    $sWhere .= " WHERE m51_valortotal::numeric > (valorlancado + m36_vrlanu)::numeric AND (m51_data+{$iDiasPrazo}) < '{$dtDataUsu}' ";
} else {
    $sWhere .= " WHERE m51_valortotal::numeric > (valorlancado + m36_vrlanu)::numeric ";
}

if (isset($iCgm) && $iCgm != null) {
    $sWhere .= " AND z01_numcgm = {$iCgm}";
}

if (isset($iCodDepto) && $iCodDepto != null) {
    $sWhere .= " AND deptodestino = {$iCodDepto} ";
}

if (isset($lQuebraForne) && isset($lQuebraDepart) && $lQuebraForne == "t" && $lQuebraDepart == "t") {
    $sOrder .= " ORDER BY fornecedor, deptodestino, m51_data";
    $agrupaForne = true;
    $agrupaDepto = true;
} elseif (isset($lQuebraForne) && $lQuebraForne == "t") {
    $sOrder .= " ORDER BY fornecedor, m51_data";
    $agrupaForne = true;
} elseif (isset($lQuebraDepart) && $lQuebraDepart == "t") {
    $sOrder .= " ORDER BY deptodestino, m51_data";
    $agrupaDepto = true;
} else {
    $sOrder .= " ORDER BY m51_data ";
}

$sSqlOrdemPendente = "  SELECT  m51_codordem as cod_ordem,
                                z01_numcgm as cgm,
                                fornecedor,
                                empenho,
                                m51_data as data_emissao,
                                deptodestino,
                                descdeptodestino,  
                                ('{$dtDataUsu}'::date - m51_data::date)||' DIAS' as dias_atraso,
                                m51_valortotal,
                                (valorlancado + m36_vrlanu) AS valorlancadoanulado
                        FROM
                            (SELECT m51_codordem,
                                    z01_numcgm,
                                    z01_nome as fornecedor,
                                    empenho,
                                    m51_data,
                                    m51_valortotal,
                                    deptodestino,
                                    descdeptodestino,
                                    coalesce((SELECT sum(m71_valor)
                                                FROM matestoqueitemoc
                                                    INNER JOIN matordemitem ON matordemitem.m52_codlanc = matestoqueitemoc.m73_codmatordemitem
                                                    INNER JOIN matestoqueitem ON matestoqueitem.m71_codlanc = matestoqueitemoc.m73_codmatestoqueitem
                                                    INNER JOIN matordem ON matordem.m51_codordem = matordemitem.m52_codordem
                                                    INNER JOIN matestoque AS a ON a.m70_codigo = matestoqueitem.m71_codmatestoque
                                                WHERE m52_codordem = x.m51_codordem
                                                        AND m73_cancelado IS FALSE),0) AS valorlancado,
									sum(coalesce(m36_vrlanu,0)) AS m36_vrlanu
                                FROM 
                                    (SELECT DISTINCT
                                        m51_codordem,
                                        m52_codlanc,
                                        z01_numcgm,
                                        z01_nome,
                                        (e60_codemp||'/'||e60_anousu)::varchar AS empenho,
                                        m51_data,
                                        m51_valortotal,
                                        m51_depto as deptodestino,     
                                        descrdepto as descdeptodestino,
										m36_vrlanu
                                    FROM matordem
                                        INNER JOIN matordemitem ON matordemitem.m52_codordem = matordem.m51_codordem
                                        LEFT JOIN empnotaord ON empnotaord.m72_codordem = matordem.m51_codordem
                                        LEFT JOIN empnota ON empnota.e69_codnota = empnotaord.m72_codnota
                                        LEFT JOIN empempenho ON empempenho.e60_numemp = matordemitem.m52_numemp
                                        INNER JOIN cgm ON cgm.z01_numcgm = matordem.m51_numcgm
                                        LEFT JOIN matordemanu ON matordemanu.m53_codordem = matordem.m51_codordem
										LEFT JOIN matordemitemanu ON m52_codlanc = m36_matordemitem
                                        LEFT JOIN db_depart ON matordem.m51_depto = coddepto
                                        WHERE e60_anousu = {$iAnoUsu}
                                            AND (m51_obs != 'Ordem de Compra Automatica' OR m51_obs IS NULL)
                                            AND m53_codordem IS NULL
                                            AND e60_instit = {$iInstit}
                                    ) as x
                                GROUP BY 1, 2, 3, 4, 5, 6, 7, 8
                            ) AS xx
                        {$sWhere}
                        {$sOrder}";

$rsResultOrdemPendente = $clmatordem->sql_record($sSqlOrdemPendente);

if ($clmatordem->numrows == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=N�o h� ordens de compra pendentes de entrada');
} 

$head3 = "Ordens de Compra Pendentes de Entrada";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

$troca = 1;
$alt   = 4;
$prenc = 1;
$sep   = false;

for ($i = 0; $i < $clmatordem->numrows; $i++) {

    db_fieldsmemory($rsResultOrdemPendente,$i);

    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

        $pdf->addpage("L");
        
        if (!$agrupaForne && !$agrupaDepto) {        
            imprimeCabecalho($pdf, $alt);
        }

        $troca = 0;
        $prenc = 1;

    }

    if ($prenc == 0){
        $prenc = 1;
    } else $prenc = 0;

    if ($agrupaForne && $agrupaDepto) {

        if ($repeteCgm != $cgm) {
            
            $pdf->setfont('arial','b',9);
            if ($sep) { 
                $pdf->ln();
                $pdf->cell(280,1,'',"T",1,"L",0);
                $sep = true;
            }
            $pdf->cell(280,$alt+4,$cgm.' - '.$fornecedor,0,0,"L",0);
            $pdf->ln();

        }

        if ($repeteDepto != $deptodestino) {
            
            $pdf->setfont('arial','',9);
            $pdf->cell(280,$alt+4,$deptodestino.' - '.$descdeptodestino,0,0,"L",0);
            $pdf->ln();

            imprimeCabecalho($pdf, $alt);

        }

        $sep = true;

    } elseif ($agrupaForne && $repeteCgm != $cgm) {
        
        $pdf->setfont('arial','',9);
        $pdf->cell(280,$alt+4,$cgm.' - '.$fornecedor,0,0,"L",0);
        $pdf->ln();
            
        imprimeCabecalho($pdf, $alt);
    
    } elseif ($agrupaDepto && $repeteDepto != $deptodestino) {
        
        $pdf->setfont('arial','',9);
        $pdf->cell(280,$alt+4,$deptodestino.' - '.$descdeptodestino,0,0,"L",0);
        $pdf->ln();

        imprimeCabecalho($pdf, $alt);

    }
    
    $pdf->setfont('arial','',8);
    $pdf->cell(15,$alt,$cod_ordem,0,0,"C",$prenc);
    $pdf->cell(28,$alt,db_formatar($data_emissao,'d'),0,0,"C",$prenc);
    $pdf->cell(20,$alt,$empenho,0,0,"C",$prenc);
    $pdf->cell(83,$alt,$cgm.' - '.$fornecedor,0,0,"L",$prenc);
    $pdf->cell(49,$alt,substr($deptodestino.' - '.$descdeptodestino,0,33),0,0,"L",$prenc);
    $pdf->cell(35,$alt,$dias_atraso,0,0,"C",$prenc);
    $pdf->cell(25,$alt,db_formatar($m51_valortotal,'f'),0,0,"C",$prenc);
    $pdf->cell(25,$alt,db_formatar(($m51_valortotal-$valorlancadoanulado),'f'),0,0,"C",$prenc);
    $pdf->Ln(4);
    
    if ($agrupaForne) {
        $repeteCgm = $cgm;
    }

    if ($agrupaDepto) {
        $repeteDepto = $deptodestino;
    }

}

$pdf->Output();

function imprimeCabecalho($pdf, $alt) {
    
    $pdf->setfont('arial','b',9);
    $pdf->cell(15,$alt,"Ordem",      0,0,"C",1);
    $pdf->cell(28,$alt,"Data de Emiss�o",      0,0,"C",1);
    $pdf->cell(20,$alt,"Empenho",              0,0,"C",1);
    $pdf->cell(83,$alt,"Fornecedor",           0,0,"C",1);
    $pdf->cell(49,$alt,"Departamento",         0,0,"C",1);
    $pdf->cell(35,$alt,"Entrada pendente h�:", 0,0,"C",1);
    $pdf->cell(25,$alt,"Valor da OC:",         0,0,"C",1);
    $pdf->cell(25,$alt,"Valor Pendente:",      0,0,"C",1);
    $pdf->Ln();
    
}
?>