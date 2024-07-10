<?


require_once("fpdf151/pdf.php");

$where = "where obr01_instit =" . db_getsession("DB_instit");

if ($obr02_seqobra != "") $where = "where  obr01_sequencial = $obr02_seqobra";

$sSqlObras = db_query("select distinct on (obr01_numeroobra) * from licobras
left join liclicita on l20_codigo = obr01_licitacao
left join licobraslicitacao on obr07_sequencial = obr01_licitacao
left join acordo on l20_codigo = ac16_licitacao
left join cflicita on l20_codtipocom = l03_codigo
left join pctipocompratribunal on l44_sequencial = obr07_tipoprocesso
left join licobrasmedicao on obr01_sequencial = obr03_seqobra $where order by obr01_numeroobra;");

if (pg_numrows($sSqlObras) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$head2 = "Obras";
$oPDF = new PDF('Landscape', 'mm', 'A4');
$oPDF->Open();
$oPDF->AliasNbPages();
$oPDF->setfillcolor(235);

$iAlt = 6;
$oPDF->addpage();

$aSituacao = array(
    1 => '1 - N�o Iniciada',
    2 => '2 - Iniciada',
    3 => '3 - Paralisada por rescis�o contratual',
    4 => '4 - Paralisada',
    5 => '5 - Conclu�da e n�o recebida',
    6 => '6 - Conclu�da e recebida provisoriamente',
    7 => '7 - Conclu�da e recebida definitivamente',
    8 => '8 - Reiniciada'
);

$aMedicao = array(
    1 => '1 - Medi��o a pre�os iniciais',
    2 => '2 - Medi��o de reajuste',
    3 => '3 - Medi��o complementar',
    4 => '4 - Medi��o final',
    5 => '5 - Medi��o de termo aditivo',
    9 => '9 - Outro documento de medi��o.'
);

for ($i = 0; $i < pg_num_rows($sSqlObras); $i++) {

    // Quantidade de celulas a serem saltadas
    $iQtdCells = 0;

    $oDadosObra = db_utils::fieldsMemory($sSqlObras, $i);



    $oPDF->setfont('arial', 'b', 8);
    $oPDF->cell(280, $iAlt, "Obra: $oDadosObra->obr01_numeroobra", 1, 0, "L", 1);
    $oPDF->ln();
    $oPDF->cell(70, $iAlt, 'Licita��o', 1, 0, "C", 3);
    $oPDF->cell(70, $iAlt, 'Modalidade ', 1, 0, "C", 3);
    $oPDF->cell(70, $iAlt, 'Objeto ', 1, 0, "C", 2);
    $oPDF->cell(35, $iAlt, 'Contrato ', 1, 0, "C", 3);
    $oPDF->cell(35, $iAlt, 'Data de Assinatura', 1, 0, "C", 3);

    $oPDF->ln();
    $oPDF->setfont('arial', '', 8);

    if ($oDadosObra->obr01_licitacaosistema == 1) {
        $altura = $oPDF->NbLines(70, $oDadosObra->l20_objeto);
        $oPDF->cell(70, $iAlt * $altura, empty($oDadosObra->l20_edital) == true ? "-" :  "$oDadosObra->l20_edital/$oDadosObra->l20_anousu", 1, 0, "C", 2);
        $oPDF->cell(70, $iAlt * $altura, empty($oDadosObra->l20_numero) == true ? "-" : "$oDadosObra->l03_descr - $oDadosObra->l20_numero", 1, 0, "C", 2);
        $y =  $oPDF->GetY();
        $x =  $oPDF->GetX();
        $oPDF->MultiCell(70, $iAlt, $oDadosObra->l20_objeto, 1, "L", 2);
    }

    if ($oDadosObra->obr01_licitacaosistema == 2) {
        $altura = $oPDF->NbLines(70, $oDadosObra->obr07_objeto);
        $oPDF->cell(70, $iAlt * $altura, empty($oDadosObra->obr07_processo) == true ? "-" :  "$oDadosObra->obr07_processo/$oDadosObra->obr07_exercicio", 1, 0, "C", 2);
        $oPDF->cell(70, $iAlt * $altura, empty($oDadosObra->l44_descricao) == true ? "-" : "$oDadosObra->l44_descricao", 1, 0, "C", 2);
        $y =  $oPDF->GetY();
        $x =  $oPDF->GetX();
        $oPDF->MultiCell(70, $iAlt, $oDadosObra->obr07_objeto, 1, "L", 2);
    }

    $oPDF->SetY($y);
    $oPDF->SetX(220);

    $oPDF->cell(35, $iAlt * $altura, empty($oDadosObra->ac16_numeroacordo) == true ?   "-" : "$oDadosObra->ac16_numeroacordo/$oDadosObra->ac16_anousu", 1, 0, "C", 2);
    $oPDF->cell(35, $iAlt * $altura, empty($oDadosObra->ac16_dataassinatura) == true ? "-" : implode("/", array_reverse(explode("-", $oDadosObra->ac16_dataassinatura))), 1, 0, "C", 2);

    $oPDF->ln();

    $rsSituacaoObra = db_query("select * from licobrasituacao where obr02_seqobra = $oDadosObra->obr01_sequencial;");
    for ($j = 0; $j < pg_num_rows($rsSituacaoObra); $j++) {

        if ($j == 0) {
            $oPDF->setfont('arial', 'b', 8);
            $oPDF->cell(280, $iAlt, 'Situa��o da Obra', 1, 0, "L", 1);
            $oPDF->ln();
            $oPDF->setfont('arial', 'b', 8);
            $oPDF->cell(70, $iAlt, 'Data ', 1, 0, "C", 2);
            $oPDF->cell(210, $iAlt, 'Tipo ', 1, 0, "C", 2);
        }

        $oSituacaoObra = db_utils::fieldsMemory($rsSituacaoObra, $j);


        $oPDF->ln();
        $oPDF->setfont('arial', '', 8);
        $oPDF->cell(70, $iAlt, empty($oSituacaoObra->obr02_dtsituacao) == true ? "-" : implode("/", array_reverse(explode("-", $oSituacaoObra->obr02_dtsituacao))), 1, 0, "C", 2);
        $oPDF->cell(210, $iAlt, empty($oSituacaoObra->obr02_situacao) == true ? "-" : $aSituacao[$oSituacaoObra->obr02_situacao], 1, 0, "C", 2);
    }

    if (pg_num_rows($rsSituacaoObra) > 0) {
        $iQtdCells .= pg_num_rows($rsSituacaoObra) + 1;
        $oPDF->ln();
    }

    $rsMedicaoObra = db_query("select * from licobrasmedicao where obr03_seqobra = $oDadosObra->obr01_sequencial;");
    for ($k = 0; $k < pg_num_rows($rsMedicaoObra); $k++) {

        if ($k == 0) {
            $oPDF->setfont('arial', 'b', 8);
            $oPDF->cell(280, $iAlt, 'Medi��o da Obra', 1, 0, "L", 1);
            $oPDF->ln();
            $oPDF->setfont('arial', 'b', 8);
            $oPDF->cell(70, $iAlt, 'N�mero ', 1, 0, "C", 3);
            $oPDF->cell(70, $iAlt, 'Data da Entrega ', 1, 0, "C", 3);
            $oPDF->cell(70, $iAlt, 'Tipo ', 1, 0, "C", 2);
            $oPDF->cell(70, $iAlt, 'Valor ', 1, 0, "C", 3);
        }

        $oMedicaoObra = db_utils::fieldsMemory($rsMedicaoObra, $k);

        $oPDF->ln();
        $oPDF->setfont('arial', '', 8);
        $oPDF->cell(70, $iAlt, empty($oMedicaoObra->obr03_nummedicao) == true ? "-" : "$oMedicaoObra->obr03_nummedicao", 1, 0, "C", 2);
        $oPDF->cell(70, $iAlt, empty($oMedicaoObra->obr03_dtentregamedicao) == true ? "-" : implode("/", array_reverse(explode("-", $oMedicaoObra->obr03_dtentregamedicao))), 1, 0, "C", 2);
        $oPDF->cell(70, $iAlt, empty($oMedicaoObra->obr03_tipomedicao) == true ? "-" : $aMedicao[$oMedicaoObra->obr03_tipomedicao], 1, 0, "C", 2);
        $oPDF->cell(70, $iAlt, empty($oMedicaoObra->obr03_vlrmedicao) == true ? "-" : "R$" . db_formatar($oMedicaoObra->obr03_vlrmedicao, 'f'), 1, 0, "C", 2);
    }

    if (pg_num_rows($rsMedicaoObra) > 0) {
        $iQtdCells .= pg_num_rows($rsMedicaoObra) + 1;
        $oPDF->ln();
    }

    if ($iQtdCells == 0) {
        $iQtdCells = 5;
    }

    $oPDF->ln($iQtdCells);
}

$oPDF->Output();
