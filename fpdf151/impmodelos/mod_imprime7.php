<?
global $db61_texto, $db02_texto;
$nTotalRetencaoRecolhido = 0;
$iInstituicao = db_getsession("DB_instit");
for ($xxx = 0; $xxx < $this->nvias; $xxx++) {

  $this->objpdf->AliasNbPages();
  $this->objpdf->AddPage();
  $this->objpdf->settopmargin(1);
  $pagina = 1;
  $xlin = 20;
  $xcol = 12;
  $ano = $this->ano;

  $this->objpdf->setfillcolor(245);
  $this->objpdf->rect($xcol-2,$xlin-18,198,292,2,'DF','1234');
  $this->objpdf->setfillcolor(255,255,255);
  $this->objpdf->Setfont('Arial','B',10);
  $this->objpdf->text(128,$xlin-10,'ORDEM DE PAGAMENTO N'.CHR(176).': ');
  $this->objpdf->Setfont('Arial','B',12);
  $this->objpdf->text(180,$xlin-10,$this->ordpag);
  $this->objpdf->Setfont('Arial','B',10);
  $this->objpdf->text(134,$xlin-5,'DATA DE EMISS�O : ');
  $this->objpdf->text(175,$xlin-5,$this->emissao);
  $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
  $this->objpdf->Setfont('Arial','B',9);
  $this->objpdf->text(40,$xlin-15,$this->prefeitura);
  $this->objpdf->Setfont('Arial','',9);
  $this->objpdf->text(40,$xlin-11,$this->enderpref);
  $this->objpdf->text(40,$xlin-8,$this->municpref);
  $this->objpdf->text(40,$xlin-5,$this->telefpref);
  $this->objpdf->text(40,$xlin-2,$this->emailpref);
  $this->objpdf->text(40, $xlin +1, db_formatar($this->cgcpref, 'cnpj'));

  // retangulo dos dados da dota��o
  $this->objpdf->rect($xcol,$xlin+2,$xcol+90,41,2,'DF','1234');
  $this->objpdf->Setfont('Arial','B',8);

  $this->objpdf->text($xcol+2,$xlin+7,'�rg�o');
  $this->objpdf->text($xcol+2,$xlin+11,'Unidade');
  $this->objpdf->text($xcol+2,$xlin+15,'Fun��o');

  $this->objpdf->text($xcol+2,$xlin+19,'Proj/Ativ');
  $this->objpdf->text($xcol+2,$xlin+23,'Dota��o');
  $this->objpdf->text($xcol+2,$xlin+27,'Elemento');
  $this->objpdf->text($xcol+2,$xlin+34,'Recurso');
  $this->objpdf->text($xcol+2,$xlin+38,'Processo');
  $this->objpdf->text($xcol+2,$xlin+42,'Tipo Compra');
  $this->objpdf->text($xcol+70,$xlin+38,'Reduzido:');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.substr($this->descr_orgao,0,46));
  $this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
  $this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);

  $this->objpdf->text($xcol+17,$xlin+19,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
  $this->objpdf->text($xcol+17,$xlin+23,':  '.$this->dotacao);
  $this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->elemento,'elemento'));
  $this->objpdf->text($xcol+17,$xlin+30,'   '.$this->descr_elemento);
  $this->objpdf->text($xcol+17,$xlin+34,':  '.substr($this->recurso.' - '.$this->descr_recurso,0,50));
  $this->objpdf->text($xcol+17,$xlin+38,':  '.$this->processo);
  $this->objpdf->text($xcol+21,$xlin+42,':  '.$this->descr_tipocompra);
  $this->objpdf->text($xcol+85,$xlin+38,$this->coddot);

  // if ($ano < db_getsession("DB_anousu")) {
  //   // $this->objpdf->text($xcol+19,$xlin+38,'RESTOS A PAGAR ');
  // }

  // retangulo dos dados do credor
  $this->objpdf->rect($xcol+106,$xlin+2,88,32,2,'DF','1234');
  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->text($xcol+107,$xlin+4,'Dados do Credor:');
  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text($xcol+107,$xlin+7,'N� Credor');
  $this->objpdf->text($xcol+150,$xlin+7,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
  $this->objpdf->text($xcol+107,$xlin+15,'Banco/Ag./Conta');
  $this->objpdf->text($xcol+107,$xlin+11,'Nome');
  $this->objpdf->text($xcol+107,$xlin+19,'Munic�pio');
  $this->objpdf->text($xcol+107,$xlin+23,'Endere�o');
  $this->objpdf->text($xcol+107,$xlin+27,'Bairro');
  $this->objpdf->text($xcol+150,$xlin+31,'Telefone');
  $this->objpdf->text($xcol+107,$xlin+31,'N�mero');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+122,$xlin+7,': '.$this->numcgm);
  $this->objpdf->text($xcol+157,$xlin+7,' :  '.(strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')));
  $this->objpdf->text($xcol+122,$xlin+11,': '.$this->nome);
  $this->objpdf->text($xcol+122,$xlin+23,': '.$this->ender.' '.$this->compl);
  $this->objpdf->text($xcol+122,$xlin+27,': '.$this->bairro);
  $this->objpdf->text($xcol+122,$xlin+19,': '.$this->munic.'-'.$this->ufFornecedor.'    CEP : '.$this->cep);
  if ($this->banco != null) {
    $agenciadv = "";
    $contadv = "";
    if (trim($this->agenciadv)!="") {
      $agenciadv = "-".$this->agenciadv;
    }
    if (trim($this->contadv)!="") {
      $contadv = "-".$this->contadv;
    }
    if($this->tipoconta == 1){
        $abreviatura = "C/C";
    }else{
        $abreviatura = "C/I";
    }
    $this->objpdf->text($xcol+131,$xlin+15,': '.$this->banco.' / '.$this->agencia.$agenciadv.' / '.$this->conta.$contadv .' - '.$abreviatura);
  }
  $this->objpdf->text($xcol+163,$xlin+31,': '.$this->telef);
  $this->objpdf->text($xcol+122,$xlin+31,': '.$this->fax);

  // retangulo do empenho
  $this->objpdf->rect($xcol+106,$xlin+36,43,5,2,'DF','1234');
  $this->objpdf->rect($xcol+151,$xlin+36,43,5,2,'DF','1234');

  // retangulo dos itens
	$this->objpdf->rect($xcol+97, $xlin+ 55,  25,  4, 2, 'DF', '');
	$this->objpdf->rect($xcol+122, $xlin+ 55,  23,  4, 2, 'DF', '');
	$this->objpdf->rect($xcol+145, $xlin+ 55,  24,  4, 2, 'DF', '');
  $this->objpdf->rect($xcol+169, $xlin+ 55,  25,  4, 2, 'DF', '');
  $this->objpdf->rect($xcol,     $xlin+ 55, 97, 24, 2, 'DF', '');
  $this->objpdf->rect($xcol+000,$xlin+ 48,194, 75, 2, 'DF', '12');
  $this->objpdf->rect($xcol+97,$xlin+ 48, 25, 31, 2, 'DF', '12');
  $this->objpdf->rect($xcol+122,$xlin+ 48, 23, 31, 2, 'DF', '12');
  $this->objpdf->rect($xcol+145,$xlin+ 48, 24, 31, 2, 'DF', '12');
  $this->objpdf->rect($xcol+169,$xlin+ 48, 25, 31, 2, 'DF', '12');

  // retangulo das reten��es
  $this->objpdf->rect($xcol+169,$xlin+176, 25, 8,2,'DF','34');      //ret�ngulo total l�quido da ordem
  $this->objpdf->rect($xcol+169,$xlin+170, 25, 6,2,'DF','');        //ret�ngulo total reten��es
  $this->objpdf->rect($xcol+000,$xlin+130, 75,54,2,'DF','12');      //ret�ngulo repasses
  $this->objpdf->rect($xcol+75 ,$xlin+130, 19,54,2,'DF','12');      //ret�ngulo valores repasses

  $this->objpdf->rect($xcol+94,$xlin+130, 75,40,2,'DF','12');       //ret�ngulo reten��es
  $this->objpdf->rect($xcol+94,$xlin+170, 75, 6,2,'DF','');         //ret�ngulo label total reten��es
  $this->objpdf->rect($xcol+94,$xlin+176, 75, 8,2,'DF','34');       //ret�ngulo label l�quido da ordem
  $this->objpdf->rect($xcol+169,$xlin+130, 25,40,2,'DF','12');      //ret�ngulo valores reten��es

  $this->objpdf->Setfont('Arial','',7);
  $this->objpdf->text($xcol+108,$xlin+40,'Empenho N'.chr(176));
  $this->objpdf->text($xcol+153,$xlin+40,'Valor do Empenho');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+124,$xlin+40,db_formatar($this->numemp,'s','0',6,'e'));
  $this->objpdf->text($xcol+170,$xlin+40,db_formatar($this->empenhado,'f'));

  $this->objpdf->Setfont('Arial','B',10);
  $this->objpdf->text($xcol+2,$xlin+47,'Dados da Ordem de Pagamento');
  $this->objpdf->Setfont('Arial','B',6);

  // t�tulo do corpo do empenho
  $maiscol = 0;

  // monta os dados dos elementos da ordem de compra
  $this->objpdf->SetWidths(array(20,80,20,23,23,25));
  $this->objpdf->SetAligns(array('L','L','R','R','R','R'));
  $this->objpdf->setleftmargin(12);
  $this->objpdf->sety($xlin+48);
  $this->objpdf->cell(20,4,'ELEMENTO',0,0,"L");
  $this->objpdf->cell(80,4,'DESCRI��O',0,0,"L");
  $this->objpdf->cell(20,4,'VALOR',0,0,"R");
  $this->objpdf->cell(23,4,'ANULADO',0,0,"R");
  $this->objpdf->cell(23,4,'PAGO',0,0,"R");
  $this->objpdf->cell(25,4,'SALDO',0,1,"R");
  $this->objpdf->Setfont('Arial','',7);

	$total_pag = 0;
  $total_emp = 0;
  $total_anu = 0;
  $total_sal = 0;
  $total_saldo_final = 0;
  for ($ii = 0; $ii < $this->linhasdositens ; $ii++) {
    db_fieldsmemory($this->recorddositens,$ii);

    $this->objpdf->Setfont('Arial','',7);
    $this->objpdf->Row(array((substr(pg_result($this->recorddositens,$ii,$this->elementoitem),1)),
	                            (substr(pg_result($this->recorddositens,$ii,$this->descr_elementoitem),0,50)),
                              db_formatar(pg_result($this->recorddositens,$ii,$this->vlremp),'f'),
                              db_formatar(pg_result($this->recorddositens,$ii,$this->vlranu),'f'),
                              db_formatar(pg_result($this->recorddositens,$ii,$this->vlrpag),'f'),
                              db_formatar(pg_result($this->recorddositens,$ii,$this->saldo_final),'f')),3,false,3);
    $total_emp          += pg_result($this->recorddositens,$ii,$this->vlremp);
    $total_anu          += pg_result($this->recorddositens,$ii,$this->vlranu);
    $total_pag          += pg_result($this->recorddositens,$ii,$this->vlrpag);
    $total_sal          += pg_result($this->recorddositens,$ii,$this->vlrsaldo);
    $total_saldo_final  += pg_result($this->recorddositens,$ii,$this->saldo_final);

  }

  $rsNotas = db_query("select e69_numero,
                                                 e69_dtnota
    	     						    from empnota
 									    inner join pagordemnota on e71_codnota = e69_codnota and e71_anulado = 'f'
									  where e71_codord = ".$this->ordpag);
  if( pg_numrows($rsNotas) > 0 ){
   $this->numeronota = pg_result($rsNotas,0,0);
   $this->datanota  = pg_result($rsNotas,0,1);
   $this->objpdf->cell(102,4,"Ref. Nota Fiscal n�: ".$this->numeronota.", de ".db_formatar($this->datanota,'d'),0,1,"L");
  }

  if (isset($this->conta_pagadora_reduz) && $this->conta_pagadora_reduz != '') {
    $this->objpdf->Setfont('Arial','B',7);
    $this->objpdf->text($xcol+1,$xlin+66,'CONTA PAGADORA');
    $this->objpdf->text($xcol+1,$xlin+70,'Reduzido: ');
    $this->objpdf->text($xcol+1,$xlin+74,'Ag�ncia: ');
    $this->objpdf->text($xcol+1,$xlin+78,'Conta: ');
    $this->objpdf->Setfont('Arial','',7);
    $this->objpdf->text($xcol+14,$xlin+70,$this->conta_pagadora_reduz);
    $this->objpdf->text($xcol+12,$xlin+74,$this->conta_pagadora_agencia);
    $this->objpdf->text($xcol+10,$xlin+78,substr($this->conta_pagadora_conta,0,60));
  }

  // monta os dados das reten��es da ordem de compra
  $this->objpdf->SetWidths(array(12,58,23));
  $this->objpdf->SetAligns(array('C','L','R'));
  $this->objpdf->setxy($xcol+10,$xlin+134);
  $this->objpdf->Setfont('Arial','B',10);

  $this->objpdf->text($xcol+2,$xlin+128,'Dados das Reten��es');

  $this->objpdf->Setfont('Arial','b',7);
  $this->objpdf->SetX($this->objpdf->GetX() -8);
  $this->objpdf->cell(10,0,'COD.',0,0,"L");
  $this->objpdf->cell(55,0,'DESCRI��O',0,0,"L");//Cabe�alho da primeira coluna de reten��es
  $this->objpdf->cell(26,0,'VALOR',0,1,"R");

  $this->objpdf->Setfont('Arial','',7);
  $total_ret                = 0;
  $total_ret_outras         = 0;
  $iTotalRetencaoRecolhido  = 0;
//   echo '<pre>';print_r($this->aRetencoes);echo '</pre>';die;
  if (is_array($this->aRetencoes) && count($this->aRetencoes) > 0 ) {

    for ($ii = 0; $ii < count($this->aRetencoes); $ii++) {

        if ($ii == 15) {

            $this->objpdf->SetWidths(array(12,63,23));
            $this->objpdf->Setfont('Arial','b',7);
            $this->objpdf->setxy($xcol+97,$xlin+134);
            $this->objpdf->cell(10,0,'COD.',0,0,"L");
            $this->objpdf->cell(60,0,'DESCRI��O',0,0,"L");//Cabe�alho da segunda coluna de reten��es
            $this->objpdf->cell(26,0,'VALOR',0,1,"R");

            $this->objpdf->sety($xlin+134);

        }

        if ($ii >= 15) {
            $this->objpdf->setx($xcol+95);
        }


      if ($ii < 25) {

         $this->objpdf->Setfont('Arial','',7);
         $this->objpdf->Row(array($this->aRetencoes[$ii]->e21_sequencial,
                            substr($this->aRetencoes[$ii]->e21_descricao,0,40),
                            db_formatar($this->aRetencoes[$ii]->e23_valorretencao,'f'))
         ,6,
         false,
         3);
      } else {
        $total_ret_outras += $this->aRetencoes[$ii]->e23_valorretencao;
      }
      $total_ret += $this->aRetencoes[$ii]->e23_valorretencao;
    }

   if ($ii > 25) {

      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->Row(array("",
                            substr("OUTRAS RETEN��ES",0,40),
                            db_formatar($total_ret_outras,'f')),6,
         false,
         3);
   }

  }

  $this->objpdf->Setfont('Arial','B',7);
	// define propriedades para os totais
  $this->objpdf->setxy($xcol+100,$xlin+55);
  $this->objpdf->Setfont('Arial','',7);

  // totais
  $this->objpdf->cell(20,4,db_formatar($total_emp,'f'),0,0,"R");
  $this->objpdf->cell(23,4,db_formatar($total_anu,'f'),0,0,"R");
  $this->objpdf->cell(23,4,db_formatar($total_pag,'f'),0,0,"R");
  $this->objpdf->cell(25,4,db_formatar($total_saldo_final,'f'),0,1,"R");

  $nTotalOrdem = $total_emp - $total_anu;

  $this->objpdf->setxy($xcol+127,$xlin+61);

  $this->objpdf->Setfont('Arial','B',7);
  $this->objpdf->cell(42,4,'TOTAL DA ORDEM',0,0,"R");
  $this->objpdf->cell(22,4,db_formatar($nTotalOrdem,'f'),0,1,"R");
  $this->objpdf->setx($xcol+127);

  $this->objpdf->cell(42,4,'SALDO ANTERIOR',0,0,"R");
  $this->objpdf->cell(22,4,db_formatar($this->empenhado - $this->outrasordens,'f'),0,1,"R");
  $this->objpdf->setx($xcol+127);

  $this->objpdf->cell(42,4,'OUTRAS ORDENS',0,0,"R");
  $this->objpdf->cell(22,4,db_formatar($this->outrasordens,'f'),0,1,"R");
  $this->objpdf->setx($xcol+127);

  $this->objpdf->cell(42,4,'VALOR RESTANTE',0,0,"R");
  $this->objpdf->cell(22,4,db_formatar($this->empenhado - $this->outrasordens - $total_emp + ($total_anu - $this->empenho_anulado),'f'),0,1,"R");

  $this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+2,$xlin+84,'OBSERVA��ES :');
  $this->objpdf->Setfont('Arial','',7);

	// descri��o da observa��o
	$this->objpdf->setxy($xcol,$xlin+85);
  $this->objpdf->Setfont('Arial','',7);
  $this->objpdf->multicell(190,4,substr($this->obs,0,1318));


  // total das reten��es
  $this->objpdf->setxy($xcol+127,$xlin+172);
  $this->objpdf->Setfont('Arial','B',7);
  $this->objpdf->cell(42,5,'VALOR TOTAL DAS RETEN��ES: ',0,0,"R");
  $this->objpdf->cell(23,5,db_formatar($total_ret,'f'),0,1,"R");

  // total dos repasses
  $this->objpdf->setxy($xcol,$xlin+181);
  $this->objpdf->Setfont('Arial','B',7);

  // liquido da ordem de pagamento
  $this->objpdf->setxy($xcol+127,$xlin+181);
  $this->objpdf->Setfont('Arial','B',7);
  $this->objpdf->cell(43,1,'VALOR L�QUIDO DA ORDEM DE PAGAMENTO: ',0,0,"R");
  $this->objpdf->Setfont('Arial','B',9);

  /**
   * Verifica se o valor da Ordem est� vazio e faz o c�lculo {(Total do Saldo - (Valor da Reten��o - Valor Recolhido))}
   */
  if ($total_sal == 0) {
    $nValorSaldo = 0;
  } else {
    $nValorSaldo = ($this->valor_ordem != "") ? $this->valor_ordem : ($total_sal - ($total_ret));
  }
  $this->objpdf->cell(23,1,db_formatar($nValorSaldo, 'f'),0,1,"R");//total rente��es
  $this->objpdf->Setfont('Arial','B',7);

  // Assinaturas e Canhoto
  $sqlparag  = "select db02_texto                                             ";
  $sqlparag .= "  from db_documento                                           ";
  $sqlparag .= "       inner join db_docparag  on db03_docum   = db04_docum   ";
  $sqlparag .= "       inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
  $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sqlparag .= " where db03_tipodoc = 1500 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
//   die($sqlparag);
  $resparag = @db_query($sqlparag);
  if (@pg_numrows($resparag) > 0) {
    db_fieldsmemory($resparag,0);
    /**[extensao ordenadordespesa] doc_usuario*/
    eval($db02_texto);
  } else {
    $sqlparagpadrao  = "select db61_texto ";
    $sqlparagpadrao .= "  from db_documentopadrao ";
    $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
    $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
    $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
    $sqlparagpadrao .= " where db60_tipodoc = 1500 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";

    $resparagpadrao = @db_query($sqlparagpadrao);
    if (@pg_numrows($resparagpadrao) > 0) {
      db_fieldsmemory($resparagpadrao,0);

      /**[extensao ordenadordespesa] doc_padrao*/
      eval($db61_texto);
    }
  }

  $this->objpdf->SetFont('Arial','',4);
  $this->objpdf->Text(2,296,$this->texto);
  $this->objpdf->SetFont('Arial','',6);
  $this->objpdf->Text(200,296,($xxx+1).'� via');
  $this->objpdf->setfont('Arial','',11);
  $xlin = 169;
}

?>