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
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_sql.php");

db_postmemory($HTTP_POST_VARS);
$dtini = implode("-", array_reverse(explode("/", $DBtxt21)));
$dtfim = implode("-", array_reverse(explode("/", $DBtxt22)));
$instits = str_replace('-', ', ', $db_selinstit);
$aInstits = explode(",", $instits);

$RPPS = 0;

foreach ($aInstits as $iInstit) {
    $oInstit = new Instituicao($iInstit);
    if ($oInstit->getTipoInstit() == 5)
        $RPPS = 1;
}


if (count($aInstits) > 1) {
    $oInstit = new Instituicao();
    $oInstit = $oInstit->getDadosPrefeitura();
} else {
    foreach ($aInstits as $iInstit) {
        $oInstit = new Instituicao($iInstit);
    }
}

$sWhereReceita      = "o70_instit in ({$instits})";


$oReceitas = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $anousu, $dtini, $dtfim, false, ' * ', true, 0);
$aReceitas = db_utils::getColectionByRecord($oReceitas);

$fTC1 = 0;
$fTC2 = 0;
$fTC3 = 0;
$fTC4 = 0;
$fTCons = 0;
$fPFM = 0;
$fFEP = 0;
$fICMS = 0;
$fITR = 0;
$fCFM = 0;
$fCIDE = 0;
$fCFH = 0;
$fFEX = 0;
$fIPM = 0;
$fSTN = 0;
$fTEUEDM = 0;
$fTRCI = 0;
$fRIRPPS = 0;

// Filtrando a conta cont�bil 362110300000000
$where = " p.c60_estrut LIKE '362110300000000%' AND c61_instit IN ({$instits}) ";
$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtini, $dtfim, false, $where, '', false, 'false');
for ($x = 0; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result, $x);

    if ($c61_reduz != 0) {
        $fRIRPPS += $saldo_anterior_debito - $saldo_anterior_credito;
    }
}

foreach ($aReceitas as $Receitas) {
    if (db_getsession("DB_anousu") < 2022) {
        if (strstr($Receitas->o57_fonte, '17181000000000')) $fTC1 += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17281000000000')) $fTC2 += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '24181000000000')) $fTC3 += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '24281000000000')) $fTC4 += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17380211000000')) $fTCons += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17180121000000') || strstr($Receitas->o57_fonte, '17180131000000') || strstr($Receitas->o57_fonte, '17180141000000')) $fPFM += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17180261000000')) $fFEP += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17180611000000')) $fICMS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17180151000000')) $fITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17280221000000') || strstr($Receitas->o57_fonte, '17180221000000')) $fCFM += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17280141000000')) $fCIDE += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17180211000000')) $fCFH += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17189911000000')) $fFEX += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '17280131000000')) $fIPM += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '49500000000000')) $fTEUEDM += abs($Receitas->saldo_arrecadado);
        if (strstr($Receitas->o57_fonte, '47000000000000')) $fRCI += abs($Receitas->saldo_arrecadado);
        // if (strstr($Receitas->o57_fonte, '49813210041000') || strstr($Receitas->o57_fonte, '49813210401000')) $fRIRPPS += $Receitas->saldo_arrecadado;
    }
    // Oc16641 - Atualiza��o de Receitas para 2022
    if (db_getsession("DB_anousu") >= 2022) {
        $fTC1 = atualizaSaldo($fTC1, $Receitas, array(417170000000000, 417240000000000, 417320000000000, 424140000000000, 424220000000000, 424320000000000));
        $fTC2 = 0; // Removido da soma individual
        $fTC3 = 0; // Removido da soma individual
        $fTC4 = 0; // Removido da soma individual
        $fTCons = atualizaSaldo($fTCons, $Receitas, array(417395000000000));
        $fPFM = atualizaSaldo($fPFM, $Receitas, array(417115111000000, 417115121000000, 417115131000000));
        $fFEP = atualizaSaldo($fFEP, $Receitas, array(417125241000000));
        $fICMS = atualizaSaldo($fICMS, $Receitas, array(417195101000000, 417195801000000, 417196101000000));
        $fITR = atualizaSaldo($fITR, $Receitas, array(417115201000000));
        $fCFM = atualizaSaldo($fCFM, $Receitas, array(417225101000000, 417125101000000));
        $fCIDE = atualizaSaldo($fCIDE, $Receitas, array(417215301000000));
        $fCFH = atualizaSaldo($fCFH, $Receitas, array(417125001000000));
        $fFEX = atualizaSaldo($fFEX, $Receitas, array(417199901000000));
        $fIPM = atualizaSaldo($fIPM, $Receitas, array(417215201000000));
        $fTEUEDM = atualizaSaldo($fTEUEDM, $Receitas, array(495000000000000));
        $fRCI = atualizaSaldo($fRCI, $Receitas, array(470000000000000));
    }
}

function atualizaSaldo($valor, $receitas, $naturezas)
{
    // echo "<pre>";
    foreach ($naturezas as $natureza) {
        if ($receitas->o57_fonte == $natureza) {
            $valor += abs($receitas->saldo_arrecadado);
            // echo "Natureza Encontrada: $natureza Valor: $receitas->saldo_arrecadado <br/>";
        }
    }
    // echo "</pre>";
    return $valor;
}

db_query("drop table if exists work_receita");

criarWorkReceita($sWhereReceita, array($anousu), $dtini, $dtfim);
/**
 * mPDF
 * @param string $mode              | padr�o: BLANK
 * @param mixed $format             | padr�o: A4
 * @param float $default_font_size  | padr�o: 0
 * @param string $default_font      | padr�o: ''
 * @param float $margin_left        | padr�o: 15
 * @param float $margin_right       | padr�o: 15
 * @param float $margin_top         | padr�o: 16
 * @param float $margin_bottom      | padr�o: 16
 * @param float $margin_header      | padr�o: 9
 * @param float $margin_footer      | padr�o: 9
 *
 * Nenhum dos par�metros � obrigat�rio
 */

$mPDF = new \Mpdf\Mpdf([
    'mode' => '',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 20,
    'margin_bottom' => 15,
    'margin_header' => 8,
    'margin_footer' => 11,
]);

$header = <<<HEADER
<header>
  <table style="width:100%;text-align:center;font-family:sans-serif;padding-bottom:6px;">
    <tr>
      <td>{$oInstit->getDescricao()}</td>
    </tr>
    <tr>
      <th></th>
    </tr>
    <tr>
      <td style="text-align:right;font-size:10px;font-style:oblique;">Per�odo: De {$DBtxt21} a {$DBtxt22}</td>
    </tr>
  </table>
</header>
HEADER;

$footer = <<<FOOTER
<div style='border-top:1px solid #000;width:100%;text-align:right;font-family:sans-serif;font-size:10px;height:10px;'>
  {PAGENO}
</div>
FOOTER;


$mPDF->WriteHTML(file_get_contents('estilos/tab_relatorio.css'), 1);
$mPDF->setHTMLHeader(utf8_encode($header), 'O', true);
$mPDF->setHTMLFooter(utf8_encode($footer), 'O', true);

ob_start();

?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <style type="text/css">
        .ritz .waffle a {
            color: inherit;
        }

        .ritz .waffle .s3 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
        }

        .ritz .waffle .s4 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s6 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s1 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s9 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s10 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s8 {
            background-color: #bfbfbf;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s7 {
            background-color: #bfbfbf;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s5 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s0 {
            background-color: #bfbfbf;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s2 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }
    </style>

</head>

<body>
    <?php if (db_getsession('DB_anousu') < 2018) : ?>
        <div class="ritz grid-container" dir="ltr">
            <table class="waffle" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr style=''>
                        <td class="s0 bdtop bdleft" colspan="2">C�LCULO PARA CONTRIBUI��O DO PASEP</td>
                    </tr>
                    <tr style=''>
                        <td class="s0 bdleft" colspan="2">I - RECEITAS</td>
                    </tr>
                    <tr style=''>
                        <td class="s0 bdleft" colspan="2">Inc. III, do art. 2�, da Lei n.� 9.715/98</td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft" style="width:700px">Receitas Correntes</td>
                        <td class="s2" style="width:172px">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1100.00.00.00 - Receitas Tribut�rias</td>
                        <td class="s4">
                            <?php
                            $aDadosRT = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '411%'");
                            $fRT = count($aDadosRT) > 0 ? $aDadosRT[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRT, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1200.00.00.00 - Receita de Contribui��es </td>
                        <td class="s4">
                            <?php
                            $aDadosRC = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '412%'");
                            $fRC = count($aDadosRC) > 0 ? $aDadosRC[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRC, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1300.00.00.00 - Receita Patrimonial </td>
                        <td class="s4">
                            <?php
                            $aDadosRP = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '413%'");
                            $fRP = count($aDadosRP) > 0 ? $aDadosRP[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRP, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1400.00.00.00 - Receita Agropecu�ria </td>
                        <td class="s4">
                            <?php
                            $aDadosRA = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '414%'");
                            $fRA = count($aDadosRA) > 0 ? $aDadosRA[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1500.00.00.00 - Receita Industrial </td>
                        <td class="s4">
                            <?php
                            $aDadosRI = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '415%'");
                            $fRI = count($aDadosRI) > 0 ? $aDadosRI[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1600.00.00.00 - Receita de Servi�os </td>
                        <td class="s4">
                            <?php
                            $aDadosRS = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '416%'");
                            $fRS = count($aDadosRS) > 0 ? $aDadosRS[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1700.00.00.00 - Transfer�ncias Correntes</td>
                        <td class="s4">
                            <?php
                            $aDadosTC = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417%'");
                            $fTC = count($aDadosTC) > 0 ? $aDadosTC[0]->saldo_arrecadado : 0;
                            echo db_formatar($fTC, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1900.00.00.00 - Outras receitas correntes </td>
                        <td class="s4">
                            <?php
                            $aDadosORC = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '419%'");
                            $fORC = count($aDadosORC) > 0 ? $aDadosORC[0]->saldo_arrecadado : 0;
                            echo db_formatar($fORC, "f");
                            ?>
                        </td>
                    </tr>
                    <?php if ($RPPS) { ?>
                        <tr style=''>
                            <td class="s3 bdleft">7000.00.00.00 - Receitas Correntes Intraor�ament�rias </td>
                            <td class="s4">
                                <?php
                                $aDadosRCI = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '47%'");
                                $fRCI = count($aDadosRCI) > 0 ? $aDadosRCI[0]->saldo_arrecadado : 0;
                                echo db_formatar($fRCI, "f");
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr style=''>
                        <td class="s1 bdleft">Sub-Total I </td>
                        <td class="s5">
                            <?php
                            $fSubTutalI = array_sum(array($fRT, $fRC, $fRP, $fRA, $fRI, $fRS, $fTC, $fORC, $fRCI));
                            echo db_formatar($fSubTutalI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">2400.00.00.00 - Transfer�ncias de Capital</td>
                        <td class="s4">
                            <?php
                            $aDadosTCA = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '424%'");
                            $fTCA = count($aDadosTCA) > 0 ? $aDadosTCA[0]->saldo_arrecadado : 0;
                            echo db_formatar($fTCA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">Sub-Total II </td>
                        <td class="s5">
                            <?php
                            $fSubTutalII = $fTCA;
                            echo db_formatar($fSubTutalII, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">Total das Receitas (I) </td>
                        <td class="s5">
                            <?php
                            $fTotalReceitasI = $fSubTutalI + $fSubTutalII;
                            echo db_formatar($fTotalReceitasI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s0 bdleft" colspan="2">II - EXCLUS�ES DA RECEITA</td>
                    </tr>
                    <tr style=''>
                        <td class="s6 bdleft">Base Legal </td>
                        <td class="s6">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">Transfer�ncias de Conv�nios (� 7�, do art. 2�, da Lei n.� 9.715/98)</td>
                        <td class="s4">
                            <?php
                            $aDadosTC1 = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '41761%'");
                            $fTC1 = count($aDadosTC1) > 0 ? $aDadosTC1[0]->saldo_arrecadado : 0;
                            $aDadosTC2 = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '41762%'");
                            $fTC2 = count($aDadosTC2) > 0 ? $aDadosTC2[0]->saldo_arrecadado : 0;
                            $aDadosTC3 = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '42471%'");
                            $fTC3 = count($aDadosTC3) > 0 ? $aDadosTC3[0]->saldo_arrecadado : 0;
                            $aDadosTC4 = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '42472%'");
                            $fTC4 = count($aDadosTC4) > 0 ? $aDadosTC4[0]->saldo_arrecadado : 0;
                            $fTotalTC = array_sum(array($fTC1, $fTC2, $fTC3, $fTC4));
                            echo db_formatar($fTotalTC, "f");
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td class="s3 bdleft" dir="ltr">
                            Contrato de repasse ou instrumento cong�nere com objeto definido (� 7�, do art. 2�, da Lei n.� 9.715/98)
                        </td>
                        <td class="s4">
                            <?= db_formatar($fCRICOD = 0, "f") ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">Transfer�ncias a outras Entidades de Direito P�blico Interno (art. 7�, da Lei n.� 9.715/98)</td>
                        <td class="s4"><?= db_formatar($fTOEDPI = 0, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">
                            Transfer�ncias efetuadas � Uni�o, aos Estados, ao Distrito Federal e a outros Munic�pios, bem como �s autarquias dessas entidades (Solu��o de Consulta RFB n.� 278 - de 01 de junho 2017 - D.O.U: 06.06.2017)
                        </td>
                        <td class="s4"><?= db_formatar($fTEUEDM, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">
                            Transfer�ncias efetuadas � Institui��es Multigovernamentais Nacionais (criadas e mantidas por dois ou mais entes da Federa��o) de car�ter p�blico, criadas por lei. (Solu��o de Consulta RFB n.� 31, de 28 de fevereiro de 2013 - 6� Regi�o Fiscal - D.O.U.: 05.03.2013)
                        </td>
                        <td class="s4"><?= db_formatar($fTEIMN = 0, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">
                            Transfer�ncias de Munic�pios a Cons�rcios P�blicos
                        </td>

                        <td class="s4">
                            <?php
                            $aDadosCons = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '4173802%'");
                            $fTCons = count($aDadosCons) > 0 ? $aDadosCons[0]->saldo_arrecadado : 0;
                            echo db_formatar($fTCons, "f");
                            ?>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">Total das exclus�es da Receita (II) </td>
                        <td class="s5">
                            <?php
                            $fTotalExclusaoReceitaII = array_sum(array($fTotalTC, $fCRICOD, $fTOEDPI, $fTEUEDM, $fTEIMN, $fTCons));
                            echo db_formatar($fTotalExclusaoReceitaII, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s7 bdleft">III - TOTAL RECEITA L�QUIDA (BASE DE C�LCULO) (I-II) </td>
                        <td class="s8">
                            <?php
                            $fTotalRecLiqIII = $fTotalReceitasI - $fTotalExclusaoReceitaII;
                            echo db_formatar($fTotalRecLiqIII, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s9 bdleft">IV - RECEITAS COM RETEN��ES DO PASEP NA FONTE</td>
                        <td class="s6">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">FPM - Fundo de Participa��o dos Munic�pios</td>
                        <td class="s4">
                            <?php
                            $aDadosFPM = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '4172101%' AND o57_fonte not in ('417210105010000','417210105020000','417210105030000')");
                            $fPFM = count($aDadosFPM) > 0 ? $aDadosFPM[0]->saldo_arrecadado : 0;
                            echo db_formatar($fPFM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">F.E.P. - Fundo Especial Petr�leo</td>
                        <td class="s4">
                            <?php
                            $aDadosFEP = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417212270%'");
                            $fFEP = count($aDadosFEP) > 0 ? $aDadosFEP[0]->saldo_arrecadado : 0;
                            echo db_formatar($fFEP, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">ICMS - Desonera��o das Exporta��es - LC n.� 87/96</td>
                        <td class="s4">
                            <?php
                            $aDadosICMS = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '4172136%'");
                            $fICMS = count($aDadosICMS) > 0 ? $aDadosICMS[0]->saldo_arrecadado : 0;
                            echo db_formatar($fICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">ITR - Imposto Territorial Rural</td>
                        <td class="s4">
                            <?php
                            $aDadosITR = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417210105%'");
                            $fITR = count($aDadosITR) > 0 ? $aDadosITR[0]->saldo_arrecadado : 0;
                            echo db_formatar($fITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">CFM - Depto Nacional de Produ��o Mineral</td>
                        <td class="s4">
                            <?php
                            $aDadosCFM = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417222220%'");
                            $fCFM = count($aDadosCFM) > 0 ? $aDadosCFM[0]->saldo_arrecadado : 0;
                            echo db_formatar($fCFM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">CIDE - Contribui��es de Interven��o no Dom�nio Econ�mico</td>
                        <td class="s4">
                            <?php
                            $aDadosCIDE = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417220113%'");
                            $fCIDE = count($aDadosCIDE) > 0 ? $aDadosCIDE[0]->saldo_arrecadado : 0;
                            echo db_formatar($fCIDE, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">CFH - Cota parte Compensa��o Finan. Rec. H�dricos</td>
                        <td class="s4">
                            <?php
                            $aDadosCFH = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417212211%'");
                            $fCFH = count($aDadosCFH) > 0 ? $aDadosCFH[0]->saldo_arrecadado : 0;
                            echo db_formatar($fCFH, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">FEX - Aux�lio Financeiro para Fomento Exporta��es / AFM - Apoio Financeiro aos Munic�pios</td>
                        <td class="s4">
                            <?php
                            $aDadosFEX = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '4172199%'");
                            $fFEX = count($aDadosFEX) > 0 ? $aDadosFEX[0]->saldo_arrecadado : 0;
                            echo db_formatar($fFEX, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft" dir="ltr">
                            Outras transfer�ncias correntes e de capital recebidas, se comprovada a reten��o na fonte, pela Secretaria do Tesouro Nacional - STN, da contribui��o incidente sobre tais valores.
                        </td>
                        <td class="s4">
                            <?php
                            $aDadosSTN = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417220104%'");
                            $fSTN = count($aDadosSTN) > 0 ? $aDadosSTN[0]->saldo_arrecadado : 0;
                            echo db_formatar($fSTN, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">TOTAL DAS RECEITAS COM RETEN��ES DO PASEP NA FONTE (IV)</td>
                        <td class="s5">
                            <?php
                            $fTotalRetidosIV = array_sum(array($fPFM, $fFEP, $fICMS, $fITR, $fCFM, $fCIDE, $fCFH, $fFEX, $fSTN));
                            echo db_formatar($fTotalRetidosIV, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s6 bdleft">RESUMO</td>
                        <td class="s6">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">a) Total da Receita L�quida (III) </td>
                        <td class="s5"><?= db_formatar($fTotalRecLiqIII, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">b) 1% sobre total das Receitas (a*1%) </td>
                        <td class="s5"><?= db_formatar($fTotalRecLiqIII * 0.01, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">c) PASEP retido na Fonte (IV*1%)</td>
                        <td class="s5"><?= db_formatar($fTotalRetidosIV * 0.01, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s7 bdleft">RESULTADO DO C�LCULO (b-c)</td>
                        <td class="s8"><?= db_formatar(($fTotalRecLiqIII * 0.01) - ($fTotalRetidosIV * 0.01), "f") ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="ritz grid-container" dir="ltr">
            <table class="waffle" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr style=''>
                        <td class="s0 bdtop bdleft" colspan="2">C�LCULO PARA CONTRIBUI��O DO PASEP</td>
                    </tr>
                    <tr style=''>
                        <td class="s0 bdleft" colspan="2">I - RECEITAS</td>
                    </tr>
                    <tr style=''>
                        <td class="s0 bdleft" colspan="2">Inc. III, do art. 2�, da Lei n.� 9.715/98</td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft" style="width:700px">Receitas Correntes</td>
                        <td class="s2" style="width:172px">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1100.00.00.00 - Impostos, Taxas e Contribui��es de Melhoria</td>
                        <td class="s4">
                            <?php
                            $aDadosRT = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '411%'");
                            $fRT = count($aDadosRT) > 0 ? $aDadosRT[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRT, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1200.00.00.00 - Receita de Contribui��es </td>
                        <td class="s4">
                            <?php
                            $aDadosRC = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '412%'");
                            $fRC = count($aDadosRC) > 0 ? $aDadosRC[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRC, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1300.00.00.00 - Receita Patrimonial </td>
                        <td class="s4">
                            <?php
                            $aDadosRP = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '413%'");
                            $fRP = count($aDadosRP) > 0 ? $aDadosRP[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRP, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1400.00.00.00 - Receita Agropecu�ria </td>
                        <td class="s4">
                            <?php
                            $aDadosRA = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '414%'");
                            $fRA = count($aDadosRA) > 0 ? $aDadosRA[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1500.00.00.00 - Receita Industrial </td>
                        <td class="s4">
                            <?php
                            $aDadosRI = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '415%'");
                            $fRI = count($aDadosRI) > 0 ? $aDadosRI[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1600.00.00.00 - Receita de Servi�os </td>
                        <td class="s4">
                            <?php
                            $aDadosRS = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '416%'");
                            $fRS = count($aDadosRS) > 0 ? $aDadosRS[0]->saldo_arrecadado : 0;
                            echo db_formatar($fRS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1700.00.00.00 - Transfer�ncias Correntes</td>
                        <td class="s4">
                            <?php
                            $aDadosTC = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '417%'");
                            $fTC = count($aDadosTC) > 0 ? $aDadosTC[0]->saldo_arrecadado : 0;
                            echo db_formatar($fTC, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">1900.00.00.00 - Outras receitas correntes </td>
                        <td class="s4">
                            <?php
                            $aDadosORC = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '419%'");
                            $fORC = count($aDadosORC) > 0 ? $aDadosORC[0]->saldo_arrecadado : 0;
                            echo db_formatar($fORC, "f");
                            ?>
                        </td>
                    </tr>
                    <?php if ($RPPS) { ?>
                        <tr style=''>
                            <td class="s3 bdleft">7000.00.00.00 - Receitas Correntes Intraor�ament�rias </td>
                            <td class="s4">
                                <?php
                                $aDadosRCI = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '47%'");
                                $fRCI = count($aDadosRCI) > 0 ? $aDadosRCI[0]->saldo_arrecadado : 0;
                                echo db_formatar($fRCI, "f");
                                ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr style=''>
                        <td class="s1 bdleft">Sub-Total I </td>
                        <td class="s5">
                            <?php
                            $fSubTutalI = array_sum(array($fRT, $fRC, $fRP, $fRA, $fRI, $fRS, $fTC, $fORC, $fRCI));
                            echo db_formatar($fSubTutalI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">2400.00.00.00 - Transfer�ncias de Capital</td>
                        <td class="s4">
                            <?php
                            $aDadosTCA = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '424%'");
                            $fTCA = count($aDadosTCA) > 0 ? $aDadosTCA[0]->saldo_arrecadado : 0;
                            echo db_formatar($fTCA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">Sub-Total II </td>
                        <td class="s5">
                            <?php
                            $fSubTutalII = $fTCA;
                            echo db_formatar($fSubTutalII, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">Total das Receitas (I) </td>
                        <td class="s5">
                            <?php
                            $fTotalReceitasI = $fSubTutalI + $fSubTutalII;
                            echo db_formatar($fTotalReceitasI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s0 bdleft" colspan="2">II - EXCLUS�ES DA RECEITA</td>
                    </tr>
                    <tr style=''>
                        <td class="s6 bdleft">Base Legal </td>
                        <td class="s6">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">Transfer�ncias de Conv�nios (� 7�, do art. 2�, da Lei n.� 9.715/98)</td>
                        <td class="s4">
                            <?php

                            $fTotalTC = array_sum(array($fTC1, $fTC2, $fTC3, $fTC4));
                            echo db_formatar($fTotalTC, "f");
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td class="s3 bdleft" dir="ltr">
                            Contrato de repasse ou instrumento cong�nere com objeto definido (� 7�, do art. 2�, da Lei n.� 9.715/98)
                        </td>
                        <td class="s4">
                            <?= db_formatar($fCRICOD = 0, "f") ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">Transfer�ncias a outras Entidades de Direito P�blico Interno (art. 7�, da Lei n.� 9.715/98)</td>
                        <td class="s4"><?= db_formatar($fTOEDPI = 0, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">
                            Transfer�ncias efetuadas � Uni�o, aos Estados, ao Distrito Federal e a outros Munic�pios, bem como �s autarquias dessas entidades (Solu��o de Consulta RFB n.� 278 - de 01 de junho 2017 - D.O.U: 06.06.2017)
                        </td>
                        <td class="s4"><?= db_formatar($fTEUEDM, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">
                            Transfer�ncias efetuadas � Institui��es Multigovernamentais Nacionais (criadas e mantidas por dois ou mais entes da Federa��o) de car�ter p�blico, criadas por lei. (Solu��o de Consulta RFB n.� 31, de 28 de fevereiro de 2013 - 6� Regi�o Fiscal - D.O.U.: 05.03.2013)
                        </td>
                        <td class="s4"><?= db_formatar($fTEIMN = 0, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">
                            Transfer�ncias de Munic�pios a Cons�rcios P�blicos
                        </td>

                        <td class="s4">
                            <?php
                            echo db_formatar($fTCons, "f");
                            ?>
                    </tr>

                    <?php if ($RPPS) { ?>
                        <tr style=''>
                            <td class="s3 bdleft">
                                Receitas Correntes Intraor�ament�ria
                            </td>

                            <td class="s4">
                                <?php
                                echo db_formatar($fRCI, "f");
                                ?>
                        </tr>

                        <tr style=''>
                            <td class="s3 bdleft">
                                Perdas com Investimentos do RPPS
                            </td>

                            <td class="s4">
                                <?php
                                echo db_formatar($fRIRPPS, "f");
                                ?>
                        </tr>
                    <?php } ?>
                    <tr style=''>
                        <td class="s1 bdleft">Total das exclus�es da Receita (II) </td>
                        <td class="s5">
                            <?php
                            $fTotalExclusaoReceitaII = array_sum(array($fTotalTC, $fCRICOD, $fTOEDPI, $fTEUEDM, $fTEIMN, $fTCons, $fRIRPPS, $fRCI));
                            echo db_formatar($fTotalExclusaoReceitaII, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s7 bdleft">III - TOTAL RECEITA L�QUIDA (BASE DE C�LCULO) (I-II) </td>
                        <td class="s8">
                            <?php
                            $fTotalRecLiqIII = $fTotalReceitasI - $fTotalExclusaoReceitaII;
                            echo db_formatar($fTotalRecLiqIII, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s9 bdleft">IV - RECEITAS COM RETEN��ES DO PASEP NA FONTE</td>
                        <td class="s6">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">FPM - Fundo de Participa��o dos Munic�pios</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fPFM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">F.E.P. - Fundo Especial Petr�leo</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fFEP, "f");
                            ?>
                        </td>
                    </tr>
                    <?php
                        $sDescricaoICMS = "ICMS - Desonera��o das Exporta��es - LC n.� 87/96 / Transfer�ncia  LC n.� 176/2020";
                        if (db_getsession('DB_anousu') >= 2022)
                            $sDescricaoICMS .= " / EC n� 123/2022 ";
                    ?>
                    <tr style=''>
                        <td class="s3 bdleft"><?=$sDescricaoICMS?></td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">ITR - Imposto Territorial Rural</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">CFM - Depto Nacional de Produ��o Mineral</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fCFM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">CIDE - Contribui��es de Interven��o no Dom�nio Econ�mico</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fCIDE, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">CFH - Cota parte Compensa��o Finan. Rec. H�dricos</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fCFH, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">FEX - Aux�lio Financeiro para Fomento Exporta��es / AFM - Apoio Financeiro aos Munic�pios</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fFEX, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">IPM - IPI Exporta��o</td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fIPM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft" dir="ltr">
                            Outras transfer�ncias correntes e de capital recebidas, se comprovada a reten��o na fonte, pela Secretaria do Tesouro Nacional - STN, da contribui��o incidente sobre tais valores.
                        </td>
                        <td class="s4">
                            <?php
                            echo db_formatar($fSTN, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s1 bdleft">TOTAL DAS RECEITAS COM RETEN��ES DO PASEP NA FONTE (IV)</td>
                        <td class="s5">
                            <?php
                            $fTotalRetidosIV = array_sum(array($fPFM, $fFEP, $fICMS, $fITR, $fCFM, $fCIDE, $fCFH, $fFEX, $fIPM, $fSTN));
                            echo db_formatar($fTotalRetidosIV, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style=''>
                        <td class="s6 bdleft">RESUMO</td>
                        <td class="s6">VALOR</td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">a) Total da Receita L�quida (III) </td>
                        <td class="s5"><?= db_formatar($fTotalRecLiqIII, "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">b) 1% sobre total das Receitas (a*1%) </td>
                        <td class="s5"><?= db_formatar(floatval(number_format(($fTotalRecLiqIII * 0.01), 2, '.', '')), "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s3 bdleft">c) PASEP retido na Fonte</td>
                        <?php $fPASEPRETIDO = (($fPFM + $fFEP + $fICMS +  $fCFM + $fCIDE + $fCFH + $fFEX + $fSTN) * 0.01) + (($fITR + $fIPM) * 0.008) ?>
                        <td class="s5"><?= db_formatar(floatval(number_format($fPASEPRETIDO, 2, '.', '')), "f") ?></td>
                    </tr>
                    <tr style=''>
                        <td class="s7 bdleft">RESULTADO DO C�LCULO (b-c)</td>
                        <td class="s8"><?= db_formatar(floatval(number_format(($fTotalRecLiqIII * 0.01), 2, '.', '')) - floatval(number_format($fPASEPRETIDO, 2, '.', '')), 'f') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>

</html>

<?php

$html = ob_get_contents();
ob_end_clean();
$mPDF->WriteHTML(utf8_encode($html));
$mPDF->Output();
//echo $html;

?>
