<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

$head3 = "CONTAS AGRUPADAS SICOM";

$aFontesEncerradas = array('148', '149', '150', '151', '152', '248', '249', '250', '251', '252');

$sSqlGeral = "SELECT 10 AS tiporegistro,
                     k13_reduz AS codctb,
                     c61_codtce AS codtce,
                     si09_codorgaotce,
                     si09_tipoinstit,
                     c63_banco,
                     c63_agencia,
                     c63_conta,
                     c63_dvconta,
                     c63_dvagencia,
                     CASE
                         WHEN db83_tipoconta IN (2, 3) THEN 2
                         ELSE 1
                     END AS tipoconta,
                     CASE
                         WHEN (SELECT si09_tipoinstit FROM infocomplementaresinstit WHERE si09_instit = " . db_getsession("DB_instit") . " ) = 5 AND db83_tipoconta IN (2, 3) THEN db83_tipoaplicacao::varchar
                         ELSE ' '
                     END AS tipoaplicacao,
                     CASE
                         WHEN (SELECT si09_tipoinstit FROM infocomplementaresinstit WHERE si09_instit = " . db_getsession("DB_instit") . " ) = 5 AND db83_tipoconta IN (2, 3) THEN db83_nroseqaplicacao::varchar
                         ELSE ' '
                     END AS nroseqaplicacao,
                     db83_descricao AS desccontabancaria,
                     CASE
                         WHEN (db83_convenio IS NULL
                             OR db83_convenio = 2) THEN 2
                         ELSE 1
                     END AS contaconvenio,
                     CASE
                         WHEN db83_convenio = 1 THEN db83_numconvenio
                         ELSE NULL
                     END AS nroconvenio,
                     CASE
                         WHEN db83_convenio = 1 THEN db83_dataconvenio
                         ELSE NULL
                     END AS dataassinaturaconvenio,
                     o15_codtri AS recurso
              FROM saltes
              JOIN conplanoreduz ON k13_reduz = c61_reduz AND c61_anousu = ".db_getsession("DB_anousu")."
              JOIN conplanoconta ON c63_codcon = c61_codcon AND c63_anousu = c61_anousu
              JOIN orctiporec ON c61_codigo = o15_codigo
              LEFT JOIN conplanocontabancaria ON c56_codcon = c61_codcon AND c56_anousu = c61_anousu
              LEFT JOIN contabancaria ON c56_contabancaria = db83_sequencial
              LEFT JOIN infocomplementaresinstit ON si09_instit = c61_instit
              WHERE c61_instit = ".db_getsession("DB_instit")."
              ORDER BY k13_reduz";
//echo $sSqlGeral;
$rsContas = db_query($sSqlGeral);//db_criatabela($rsContas);

$aBancosAgrupados = array();

$rsContas = db_query($sSqlGeral);

for ($iCont = 0;$iCont < pg_num_rows($rsContas); $iCont++) {

    $oRegistro10 = db_utils::fieldsMemory($rsContas,$iCont);


    $aHash  = $oRegistro10->si09_codorgaotce;
    $aHash .= intval($oRegistro10->c63_banco);
    $aHash .= intval($oRegistro10->c63_agencia);
    $aHash .= $oRegistro10->c63_dvagencia;
    $aHash .= intval($oRegistro10->c63_conta);
    $aHash .= $oRegistro10->c63_dvconta;
    $aHash .= $oRegistro10->tipoconta;
    
    if ($oRegistro10->si09_tipoinstit == 5) {
        $aHash .= $oRegistro10->tipoaplicacao;
        $aHash .= $oRegistro10->nroseqaplicacao;
    }

    if($oRegistro10->si09_tipoinstit != 5){

        if(!isset($aBancosAgrupados[$aHash])){

            $cCtb10    =  new stdClass();

            $cCtb10->codctb =	$oRegistro10->codctb;
            $cCtb10->codtce =	$oRegistro10->codtce;
            $cCtb10->recurso =	in_array($oRegistro10->recurso, $aFontesEncerradas) ? substr($oRegistro10->recurso, 0, 1).'59' : $oRegistro10->recurso;
            $cCtb10->contas	= array();

            $aBancosAgrupados[$aHash] = $cCtb10;

        }else{
            $oConta = new stdClass();
            $oConta->codctb = $oRegistro10->codctb;
            $oConta->recurso = in_array($oRegistro10->recurso, $aFontesEncerradas) ? substr($oRegistro10->recurso, 0, 1).'59' : $oRegistro10->recurso;

            $aBancosAgrupados[$aHash]->contas[] = $oConta;
        }


    }else{

        if(!isset($aBancosAgrupados[$aHash])){

            $cCtb10    =  new stdClass();

            $cCtb10->codctb =	$oRegistro10->codctb;
            $cCtb10->codtce =	$oRegistro10->codtce;
            $cCtb10->recurso =	in_array($oRegistro10->recurso, $aFontesEncerradas) ? substr($oRegistro10->recurso, 0, 1).'59' : $oRegistro10->recurso;
            $cCtb10->contas	= array();

            $aBancosAgrupados[$aHash] = $cCtb10;

        }else{
            $oConta = new stdClass();
            $oConta->codctb = $oRegistro10->codctb;
            $oConta->recurso = in_array($oRegistro10->recurso, $aFontesEncerradas) ? substr($oRegistro10->recurso, 0, 1).'59' : $oRegistro10->recurso;

            $aBancosAgrupados[$aHash]->contas[] = $oConta;
        }
    }

}
//echo "<pre>";print_r($aBancosAgrupados);exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
foreach($aBancosAgrupados as $oBancos)
{
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
    {
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(35,$alt,"Reduzido",1,0,"C",1);
        $pdf->cell(35,$alt,"C�digo TCE",1,0,"C",1);
        $pdf->cell(110,$alt,"Reduzidos Agrupados",1,1,"C",1);
        $troca = 0;
    }
    $pdf->setfont('arial','',7);
    $pdf->cell(35,$alt,$oBancos->codctb.' ('.$oBancos->recurso.')',1,0,"C",0);
    $pdf->cell(35,$alt,$oBancos->codtce,1,0,"C",0);    
    
    $sContaAgrup = '';
    $iCount = count($oBancos->contas);
    
    foreach($oBancos->contas as $index => $oConta) {

        $sContaAgrup .= $oConta->codctb.' ('.$oConta->recurso.')';
        
        if ($index < ($iCount-1)) {
            $sContaAgrup .= ', ';
        }
        
    }

    $pdf->cell(110,$alt,$sContaAgrup,1,1,"C",0);
    $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>