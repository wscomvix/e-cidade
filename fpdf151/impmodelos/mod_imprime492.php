<?php

use App\Models\IssNotaAvulsaTomadorCgmRetencao;

$oInstit                            = new Instituicao(db_getsession('DB_instit'));
$IssNotaAvulsaTomadorCgmRetencao  = IssNotaAvulsaTomadorCgmRetencao::query()->where('numcgm',$this->dadosTomador->z01_numcgm)->first();

##Modelo de nota Fiscal
$confNumRows = pg_num_rows($this->rsConfig);
for ($j = 0; $j < $confNumRows; $j++) {

    $oConf = db_utils::fieldsmemory($this->rsConfig, $j);
    $xlin = 20;
    $xcol = 4;
    $this->fTotaliUni = 0;
    $this->fTotal = 0;
    $this->fvlrIssqn = 0;
    $this->fvlrInss = 0;
    $this->fvlrIrrf = 0;
    $this->objpdf->AliasNbPages();
    $this->objpdf->AddPage();
    $this->objpdf->settopmargin(1);
    $this->objpdf->SetFillColor(192, 192, 192);
    $this->objpdf->Setfont('Arial', 'B', 10);
    $this->objpdf->rect(10, 2, 29, 28);
    $this->objpdf->Image('imagens/files/' . $this->logo, 19, $xlin - 12, 12);
    $this->objpdf->Setfont('Arial', 'B', 9);
    $this->objpdf->sety(2);
    $this->objpdf->setx($xlin + 19);
    $this->objpdf->cell(121, 5, "$this->prefeitura", "T", 1, "C");
    $this->objpdf->Setfont('Arial', '', 8);
    $this->objpdf->setx($xlin + 19);
    $this->objpdf->cell(121, 5, $this->enderpref . ' - ' . $this->municpref, 0, 1, "C");
    $this->objpdf->setx($xlin + 19);
    $this->objpdf->cell(121, 5, db_formatar($this->cgcpref, 'cnpj'), 0, 1, "C");
    $this->objpdf->setx($xlin + 19);
    $this->objpdf->cell(121, 5, $this->telefpref . (empty($this->emailpref) ? "" : " - " . $this->emailpref), 0, 1, "C");

    if ($this->notaCancelada) {
        $this->setWaterMark(40, 190, "Cancelada", 45, 130);
        $this->printWaterMark();
    }
    // Titulo
    $this->objpdf->sety($xlin + 3);
    $this->objpdf->setx($xlin + 19);
    $this->objpdf->Setfont('Arial', 'B', 10);
    if ($oInstit->getCodigoCliente() == Instituicao::COD_CLI_PMMONTALVANIA){
        $this->objpdf->cell(121, 5, "NOTA FISCAL DE SERVI�OS - AVULSA", 0, 1, "C");
    }else{
        $this->objpdf->cell(121, 5, "NOTA FISCAL DE SERVI�OS ELETR�NICA - AVULSA", 0, 1, "C");
    }
    if ($confNumRows > 1){
        //Descricao VIA
        $this->objpdf->Setfont('Arial', '', 7);
        $this->objpdf->sety(2);
        $this->objpdf->setx(160);
        $this->objpdf->cell(40, 28, $oConf->q67_via . "� via - " . $oConf->q67_descr, 1, 1, "C");

        //Numero da Nota
        $this->objpdf->Setfont('Arial', 'B', 10);
        $this->objpdf->sety($xlin + 5);
        $this->objpdf->setx(160);
        $this->objpdf->cell(40, 5, "N� " . $this->dadosPrestador->q51_numnota, 0, 1, "C");
    }else{
        //Numero da nota
        $this->objpdf->sety(2);
        $this->objpdf->setx(160);
        $this->objpdf->cell(40, 28, "N� " . $this->dadosPrestador->q51_numnota, 1, 1, "C");
    }

    //Data / Hora Emiss�o
    $this->objpdf->Setfont('Arial', 'B', 7);
    $this->objpdf->sety($xlin + 10);
    $this->objpdf->cell(29, 5, "Data / Hora Emiss�o", 1, 0, "C", true);
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->cell(29, 5, db_formatar($this->dadosPrestador->q51_dtemiss, 'd') . ' - ' . $this->dadosPrestador->q51_hora, "TB", 0, "C");

    //Compet�ncia
    $this->objpdf->Setfont('Arial', 'B', 7);
    $this->objpdf->cell(29, 5, "Compet�ncia", 1, 0, "C", true);
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->cell(29, 5, substr(db_formatar($this->dadosTomador->q53_dtservico, 'd'), 3), "TB", 0, "C");

    //C�d. Verifica��o
    $this->objpdf->Setfont('Arial', 'B', 7);
    $this->objpdf->cell(34, 5, "C�d. Verifica��o", 1, 0, "C", true);
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->cell(40, 5, $this->dadosPrestador->q51_codautent, "BR", 1, "C");

    $xlin = 33;
    $this->objpdf->sety($xlin + 4);
    //Dados do Prestador
    $this->objpdf->Setfont('Arial', 'B', 8);
    $this->objpdf->cell(0, 6, "PRESTADOR DO SERVI�O", 1, 0, "C", true);
    $this->objpdf->rect(10, $xlin + 10, 190, 20);
    //Nome
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $xlin + 12, 'NOME/RAZ�O SOCIAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14, $xlin + 14.3, $this->dadosPrestador->z01_nome);
    $this->objpdf->line(10, $xlin + 15, 200, $xlin + 15);
    //Endereco
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $xlin + 17, 'ENDERE�O');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->dadosPrestador->z01_ender = trim($this->dadosPrestador->z01_ender);
    $this->objpdf->text(14, $xlin + 19.3, "{$this->dadosPrestador->z01_ender}, {$this->dadosPrestador->z01_numero}");
    $this->objpdf->line(10, $xlin + 20, 200, $xlin + 20);
    //BAIRRO
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 132, $xlin + 17, 'BAIRRO');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 134, $xlin + 19.3, $this->dadosPrestador->z01_bairro);
    $this->objpdf->line($xcol + 130, $xlin + 15, $xcol + 130, $xlin + 25);
    //Municipio
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $xlin + 22, 'MUNIC�PIO');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14, $xlin + 24.3, $this->dadosPrestador->z01_munic);
    $this->objpdf->line(10, $xlin + 20, 200, $xlin + 20);
    //UF
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 122, $xlin + 22, 'UF');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 124, $xlin + 24.3, $this->dadosPrestador->z01_uf);
    $this->objpdf->line($xcol + 120, $xlin + 20, $xcol + 120, $xlin + 25);
    //CEP
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 132, $xlin + 22, 'CEP');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 134, $xlin + 24.3, $this->dadosPrestador->z01_cep);
    //Fone
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol + 162, $xlin + 22, 'TELEFONE');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 164, $xlin + 24.3, $this->dadosPrestador->z01_telef);
    $this->objpdf->line($xcol + 160, $xlin + 20, $xcol + 160, $xlin + 25);
    //CPF/CNPJ
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text(12, $xlin + 27, 'CPF/CNPJ');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14, $xlin + 29, $this->dadosPrestador->z01_cgccpf);
    $this->objpdf->line(10, $xlin + 30, 200, $xlin + 30);
    //INSCRICAO
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol + 82, $xlin + 27, 'INSCRI��O MUNICIPAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 84, $xlin + 29.3, $this->dadosPrestador->q02_inscr);
    $this->objpdf->line($xcol + 80, $xlin + 25, $xcol + 80, $xlin + 30);
    //Email
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 132, $xlin + 27, 'EMAIL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 134, $xlin + 29.3, $this->dadosPrestador->z01_email);
    $this->objpdf->line($xcol + 130, $xlin + 25, $xcol + 130, $xlin + 30);

    $this->objpdf->line(10, $xlin + 25, 200, $xlin + 25);
    $xlin = 60;
    $this->objpdf->sety($xlin + 5);
    //Dados do TOMADOR
    $this->objpdf->Setfont('Arial', 'B', 8);
    $this->objpdf->cell(0, 5, "TOMADOR DO SERVI�O", 1, 0, "C", true);
    $this->objpdf->rect(10, $xlin + 10, 190, 20);
    //Nome
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $xlin + 12, 'NOME/RAZ�O SOCIAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->dadosTomador->z01_nomecomple = trim($this->dadosTomador->z01_nomecomple);
    $nometomador = empty($this->dadosTomador->z01_nomecomple) ? $this->dadosTomador->z01_nome : $this->dadosTomador->z01_nomecomple;
    $this->objpdf->text(14, $xlin + 14.3, $nometomador);
    $this->objpdf->line(10, $xlin + 15, 200, $xlin + 15);
    //Endereco
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $xlin + 17, 'ENDERE�O');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->dadosTomador->z01_ender = trim($this->dadosTomador->z01_ender);
    $this->objpdf->text(14, $xlin + 19.3, "{$this->dadosTomador->z01_ender}, {$this->dadosTomador->z01_numero}");
    $this->objpdf->line(10, $xlin + 20, 200, $xlin + 20);
    //BAIRRO
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 132, $xlin + 17, 'BAIRRO');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 134, $xlin + 19.3, $this->dadosTomador->z01_bairro);
    $this->objpdf->line($xcol + 130, $xlin + 15, $xcol + 130, $xlin + 25);
    //Municipio
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $xlin + 22, 'MUNIC�PIO');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14, $xlin + 24.3, $this->dadosTomador->z01_munic);
    $this->objpdf->line(10, $xlin + 25, 200, $xlin + 25);
    //UF
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 122, $xlin + 22, 'UF');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 124, $xlin + 24.3, $this->dadosTomador->z01_uf);
    $this->objpdf->line($xcol + 120, $xlin + 25, $xcol + 120, $xlin + 25);
    //CEP
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 132, $xlin + 22, 'CEP');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 134, $xlin + 24.3, $this->dadosTomador->z01_cep);
    $this->objpdf->line($xcol + 120, $xlin + 20, $xcol + 120, $xlin + 25);
    //Fone
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol + 162, $xlin + 22, 'TELEFONE');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 164, $xlin + 24.3, $this->dadosTomador->z01_telef);
    $this->objpdf->line($xcol + 160,$xlin + 20,$xcol + 160,$xlin + 25);
    //CPF/CNPJ
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text(12, $xlin + 27, 'CPF/CNPJ');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text(14, $xlin + 29.3, $this->dadosTomador->z01_cgccpf);
    //INSCRICAO
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol + 52, $xlin + 27, 'INSCRI��O MUNICIPAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 54, $xlin + 29.3, $this->dadosTomador->q02_inscr);
    $this->objpdf->line($xcol + 50, $xlin + 25, $xcol + 50, $xlin + 30);
    //INSCRICAO ESTADUAL
    $this->objpdf->Setfont('Arial', 'b', 5);
    $this->objpdf->text($xcol + 92, $xlin + 27, 'INSCRI��O ESTADUAL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 94, $xlin + 29.3, $this->dadosTomador->z01_incest);
    $this->objpdf->line($xcol + 90, $xlin + 25, $xcol + 90, $xlin + 30);
    //Email
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text($xcol + 132, $xlin + 27, 'EMAIL');
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->text($xcol + 134, $xlin + 29.3, $this->dadosTomador->z01_email);
    $this->objpdf->line($xcol + 130, $xlin + 25, $xcol + 130, $xlin + 30);

    $this->objpdf->sety($xlin + 33);

    $this->objpdf->Setfont('Arial', 'B', 7);
    $this->objpdf->cell(190, 5, "DISCRIMINACAO DOS SERVICOS", 1, 1, "C", true);
    $this->objpdf->Setfont('Arial', '', 7);

    /*
    ** Dados do Servico;
    */
    $iYinicio = $this->objpdf->getY();
    $cellYnew = $this->objpdf->getY();
    $this->objpdf->line(10, $iYinicio, 200, $iYinicio);
    $totlinha = 0;
    $sDescitemservico = "";
    $fAliquota = 0;
    for ($i = 0; $i < pg_num_rows($this->rsServico); $i++) {

        $this->objpdf->sety($cellYnew);
        $oItensServico = db_utils::fieldsmemory($this->rsServico, $i);
        $totalLinha = ($oItensServico->q62_qtd * $oItensServico->q62_vlruni);
        $this->fTotaliUni += $totalLinha;
        $this->fTotal += $this->fTotaliUni;
        $this->fvlrIssqn += $oItensServico->q62_vlrissqn;
        $this->fvlrInss += $oItensServico->q62_vlrinss;
        $this->fvlrIrrf += $oItensServico->q62_vlrirrf;
        $sDescitemservico = $oItensServico->db121_estrutural . " - " . $oItensServico->db121_descricao;
        $fAliquota = $oItensServico->q62_aliquota;

        $this->objpdf->multiCell(190, 5, $oItensServico->q62_discriminacao, "", "L");
        $this->objpdf->rect(10, $iYinicio, 190, 80);

        break;
    }

    $this->objpdf->sety(220);
    $iYFinal = $this->objpdf->getY() - 10;

    $this->objpdf->sety(178);
    $this->objpdf->Setfont('Arial', 'B', 7);
    $this->objpdf->cell(190, 5, "C�DIGO DO SERVI�O - ATIVIDADE", 1, 1, "C", true);
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->sety(183);
    $this->objpdf->rect(10, $iYinicio+80, 190, 32);
    $this->objpdf->multiCell(190, 5, $sDescitemservico, "", "J");
    $this->objpdf->sety(220);

    //box com o total devido de imposto
    $this->yOld = $this->objpdf->getY() - 10;
    $this->objpdf->rect(10, $this->yOld, 115, 30);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->sety($this->yOld + 2);
    $this->objpdf->Setfont('Arial', 'B', 5);
    $this->objpdf->text(12, $this->yOld + 2, 'OBSERVA��ES');
    $this->objpdf->Setfont('Arial', '', 8);
    // aqui tem que colocar a obs
    $this->objpdf->MultiCell(110, 3, $this->dadosPrestador->q51_obs, 0, "J");
    $this->objpdf->Setfont('Arial', '', 10);

    //valores totais da nota
    $this->objpdf->sety($this->yOld);
    $this->objpdf->setX(125);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->cell(40, 5, "Base de C�lculo", 1, 0);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->cell(35, 5, "R$ " . number_format($this->fTotaliUni, 2, ",", "."), 1, 1, "R");

    $this->objpdf->setX(125);
    $this->objpdf->cell(40, 5, "Al�quota", 1, 0);
    $this->objpdf->cell(35, 5, number_format($fAliquota, 2, ",", ".") . "%", 1, 1, "R");

    $this->objpdf->setX(125);
    $this->objpdf->cell(40, 5, "Valor ISSQN", 1, 0);
    $this->objpdf->cell(35, 5, "R$ " . number_format($this->fvlrIssqn, 2, ",", "."), 1, 1, "R");

    $this->objpdf->setX(125);
    $this->objpdf->cell(40, 5, "Valor INSS", 1, 0);
    $this->objpdf->cell(35, 5, "R$ " . number_format($this->fvlrInss, 2, ",", "."), 1, 1, "R");

    $this->objpdf->setX(125);
    $this->objpdf->cell(40, 5, "Valor IRRF", 1, 0);
    $this->objpdf->cell(35, 5, "R$ " . number_format($this->fvlrIrrf, 2, ",", "."), 1, 1, "R");

    $fTotalNota = $this->fTotaliUni; 

    // valor total da nota
    if ((!empty($IssNotaAvulsaTomadorCgmRetencao)) && ($IssNotaAvulsaTomadorCgmRetencao->prefeitura)){
        $fTotalNota = $this->fTotaliUni - $this->fvlrIssqn - $this->fvlrInss - $this->fvlrIrrf;
    }

    if ((!empty($IssNotaAvulsaTomadorCgmRetencao)) && (!$IssNotaAvulsaTomadorCgmRetencao->prefeitura)){
            $fTotalNota = $this->fTotaliUni - $this->fvlrInss - $this->fvlrIrrf;
    }

    $this->objpdf->setX(125);
    $this->objpdf->cell(40, 5, "Valor L�quido da Nota", 1, 0);
    $this->objpdf->Setfont('Arial', '', 10);
    $this->objpdf->cell(35, 5, "R$ " . number_format(($fTotalNota), 2, ",", "."), 1, 1, "R");

    $this->objpdf->sety(250);
    $this->objpdf->Setfont('Arial', 'B', 10);
    $this->objpdf->cell(30, 20,"Avisos", "LTB", 0, "C");
    $this->objpdf->Setfont('Arial', '', 8);

    $codInstituicao = array(Instituicao::COD_CLI_PMMONTALVANIA, Instituicao::COD_CLI_PMJURAMENTO);
    if (in_array($oInstit->getCodigoCliente(), $codInstituicao))    {
        $this->objpdf->rect(40, $this->yOld + 40, 100, 20);
        $this->objpdf->MultiCell(100, 5,$this->texto_aviso, 0, "L");
        $this->objpdf->rect(140, $this->yOld + 40, 60, 20);
        $this->objpdf->setX(160);
        $this->objpdf->cell(40, 20,"Servidor(a) Emitente", 0, "C");
    }else{
        $this->objpdf->rect(40, $this->yOld + 40, 160, 20);
        $this->objpdf->MultiCell(160, 5,$this->texto_aviso, 0, "L");
    }
}