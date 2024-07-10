<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once("std/db_stdClass.php");
require_once("classes/db_pcorcam_classe.php");
require_once("classes/db_pcorcamitem_classe.php");
include("libs/PHPExcel/Classes/PHPExcel.php");
$oGet            = db_utils::postMemory($_GET);
$clpcorcam       = new cl_pcorcam();
$clpcorcamitem   = new cl_pcorcamitem();
$objPHPExcel = new PHPExcel;

/**
 * matriz de entrada
 */
$what = array(
    '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
    '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
    '�', '�', '�', '�', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', '�', '�', "�", chr(13), chr(10), "'"
);

/**
 * matriz de saida
 */
$by   = array(
    'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
    'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U',
    'n', 'N', 'c', 'C', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', " ", " ", " ", " "
);

$result_fornecedores = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "DISTINCT pc22_codorc,pc81_codproc,pc80_criterioadjudicacao,l20_objeto", null, "pc20_codorc = $pc20_codorc"));
db_fieldsmemory($result_fornecedores, 0);

//Inicio
$sheet = $objPHPExcel->getActiveSheet();

$styleTitulo = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFA0A0A0',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
    'font' => array(
        'size' => 12,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);
$styleTitulo1 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);
$styleResTitulo1 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);
$styleItens2 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFA0A0A0',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);
$styleItens = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);

$styleItensCenterAlign = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

//Iniciando planilha
$sheet->setCellValue('A1', 'Orcamento de Processo de Compra Numero: ' . $pc81_codproc . ' do Processo de Compra');
$sheet->setCellValue('A2', "Codigo do Orcamento: " . $pc22_codorc);
$sheet->setCellValue('A3', "Objeto: " . iconv('UTF-8', 'ISO-8859-1//IGNORE', str_replace($what, $by, $pc80_resumo)));
$sheet->setCellValue('A4', 'Codigo do Orcamento do Fornecedor:');
$sheet->setCellValue('A5', 'CPF / CNPJ:');
$sheet->setCellValue('A6', 'Nome / Razao Social:');

//merge das cells
$sheet->mergeCells('A1:S1');
$sheet->mergeCells('A2:S2');
$sheet->mergeCells('A3:S3');
$sheet->mergeCells('A4:S4');
$sheet->mergeCells('A5:B5');
$sheet->mergeCells('A6:S6');
$sheet->mergeCells('M5:S5');
//cabe�alho
$sheet->getStyle('A1:S1')->applyFromArray($styleTitulo);
$sheet->getStyle('A2:A6')->applyFromArray($styleTitulo1);
$sheet->getStyle('B2:B6')->applyFromArray($styleTitulo1);
$sheet->getStyle('C2:C6')->applyFromArray($styleTitulo1);
$sheet->getStyle('D2:D6')->applyFromArray($styleTitulo1);
//resposta cabe�alho
$sheet->getStyle('E2:S6')->applyFromArray($styleResTitulo1);
//itens
$sheet->setCellValue('A7', 'Cod. Item');
$sheet->setCellValue('B7', 'Seq. Item');
$sheet->setCellValue('C7', 'Servico Material');
$sheet->setCellValue('M7', 'UN');
$sheet->setCellValue('N7', 'Qtde');
if ($pc80_criterioadjudicacao == 3) {
    $sheet->setCellValue('O7', 'Valor Unit.');
} else {
    $sheet->setCellValue('O7', 'Taxa/Tabela %');
}
$sheet->setCellValue('P7', 'Valor Total');
$sheet->setCellValue('R7', 'Marca');
$sheet->mergeCells('P7:Q7');
$sheet->mergeCells('R7:S7');
$sheet->getStyle('A7:S7')->applyFromArray($styleItens2);
//cria protecao na planilha
//senha para altera��o
$sheet->getProtection()->setPassword('PHPExcel');
$sheet->getProtection()->setSheet(false);
$sheet->getProtection()->setSort(false);
$sheet->getProtection()->setInsertRows(false);
$sheet->getProtection()->setFormatCells(false);
$sheet->getStyle('E4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$sheet->getStyle('E5')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$sheet->getStyle('E6')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
//itens orcamento
$result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "distinct pc22_codorc,pc01_codmater,pc11_seq,pc01_descrmater,pc01_complmater,m61_abrev,pc11_quant", "pc11_seq", "pc20_codorc = $pc20_codorc"));
$numrows_itens = $clpcorcamitem->numrows;

for ($i = 0; $i < $numrows_itens; $i++) {
    db_fieldsmemory($result_itens, $i);
    $numrow = $i + 8;
    $collA = 'A' . $numrow;
    $collB = 'B' . $numrow;
    $collC = 'C' . $numrow;
    $collF = 'F' . $numrow;
    $collG = 'G' . $numrow;
    $collH = 'H' . $numrow;
    $collI = 'I' . $numrow;
    $collJ = 'J' . $numrow;
    $collK = 'K' . $numrow;
    $collL = 'L' . $numrow;
    $collM = 'M' . $numrow;
    $collN = 'N' . $numrow;
    $collO = 'O' . $numrow;
    $collP = 'P' . $numrow;
    $collQ = 'Q' . $numrow;
    $collR = 'R' . $numrow;
    $collS = 'S' . $numrow;
    $pc01_descrmater = $pc01_descrmater . '-' . $pc01_complmater;
    $sheet->setCellValue($collA, $pc01_codmater);
    $sheet->setCellValue($collB, $pc11_seq);
    $sheet->setCellValue($collC, iconv('UTF-8', 'ISO-8859-1//IGNORE', str_replace($what, $by, $pc01_descrmater)));
    $sheet->getStyle($collC)->getAlignment()->setWrapText(false);
    $sheet->setCellValue($collM, $m61_abrev);
    $sheet->setCellValue($collN, $pc11_quant);

    //merge das cells
    $sheet->mergeCells($collP . ':' . $collQ);
    $sheet->mergeCells($collR . ':' . $collS);

    if ($pc80_criterioadjudicacao == 3) {
        //formatacao na cell valor unitario
        $sheet->getStyle($collO)->getNumberFormat()->setFormatCode('[$R$ ]#,####0.0000_-');
        //formula multiplicacao
        $sheet->setCellValue($collP, '=' . $collN . '*' . $collO);
        $sheet->getStyle($collP)->getNumberFormat()->setFormatCode('[$R$ ]#,##0.00_-');
        //formatando os itens
        $sheet->getStyle($collA . ':' . $collS)->applyFromArray($styleItensCenterAlign);
        //libera celulas para alteracao
        $sheet->getStyle($collO)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle($collR)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    } else {
        //formatando os itens
        $sheet->getStyle($collA . ':' . $collS)->applyFromArray($styleItensCenterAlign);
        //libera celulas para alteracao
        $sheet->getStyle($collO)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle($collP)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle($collR)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    }
    $sheet->getStyle($collA . ':' . $collS)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}
$styleTotal = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);

//ultima linha itens
$lastCell = 'P' . ($numrows_itens + 7);

//linha texto valor total
$totalCellN = 'N' . ($numrows_itens + 8);
$totalCellO = 'O' . ($numrows_itens + 8);
$totalCellG = 'G' . ($numrows_itens + 8);

//linha valor total
$totalCellP = 'P' . ($numrows_itens + 8);
$totalCellQ = 'Q' . ($numrows_itens + 8);
$sheet->mergeCells($totalCellP . ':' . $totalCellQ);
$sheet->mergeCells($totalCellN . ':' . $totalCellO);

$sheet->getStyle($totalCellN . ':' . $totalCellQ)->applyFromArray($styleTotal);

$sheet->setCellValue($totalCellN, iconv('UTF-8', 'ISO-8859-1//IGNORE', str_replace($what, $by, 'Valor Or�amento') . ':'));

$formula = '=(SUM(P7:' . $lastCell . '))';
//valor Total
$sheet->setCellValue($totalCellP, $formula);
$sheet->getStyle($totalCellP)->getNumberFormat()->setFormatCode('[$R$ ]#,##0.00_-');

$objPHPExcel->getActiveSheet()->protectCells('');
$sheet->getStyle('A6:E6')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$sheet->getStyle('C5')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$sheet->getStyle('A8:A1000')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$sheet->getStyle('C8:C1000')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

$sheet->getDefaultRowDimension()->setRowHeight(-1);

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setVisible(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setVisible(false);




$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);

foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
    $rd->setRowHeight(-1);
}

$sheet->getStyle('C8:C1000')->getAlignment()->setWrapText(true);

$nomefile = "licprc_" . $pc81_codproc . "_" . db_getsession('DB_instit') . "." . "xlsx";

header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=$nomefile");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
