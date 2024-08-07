<?php
  global $db61_texto, $db02_texto;

  $this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;

	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(126,$xlin-13,'NOTA DE ANULA��O N'.CHR(176).': ');
	$this->objpdf->text(175,$xlin-13,db_formatar($this->notaanulacao,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISS�O : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);

	$this->objpdf->text(134,$xlin-3,'        EMPENHO : ');
	$this->objpdf->text(175,$xlin-3,trim($this->codemp)."/".$this->anousu);

  $this->objpdf->Image('imagens/files/'.$this->logo ,15,$xlin-17,12);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);
    $this->objpdf->text(40, $xlin + 1, db_formatar($this->cgcpref, 'cnpj'));

        /// retangulo dos dados da dota��o
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+2,$xlin+7,'�rg�o');
	$this->objpdf->text($xcol+2,$xlin+11,'Unidade');
	$this->objpdf->text($xcol+2,$xlin+15,'Fun��o');
	$this->objpdf->text($xcol+2,$xlin+19,'Subfun��o');
	$this->objpdf->text($xcol+2,$xlin+23,'Programa');
	$this->objpdf->text($xcol+2,$xlin+27,'Proj/Ativ');
	$this->objpdf->text($xcol+2,$xlin+31,'Elemento');
	$this->objpdf->text($xcol+2,$xlin+40,'Recurso');
	$this->objpdf->text($xcol+2,$xlin+44,'Reduzido');

	$this->objpdf->text($xcol+2,$xlin+48,'Licita��o');

	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	$this->objpdf->text($xcol+17,$xlin+19,':  '.db_formatar($this->subfuncao,'subfuncao').' - '.$this->descr_subfuncao);
	$this->objpdf->text($xcol+17,$xlin+23,':  '.db_formatar($this->programa,'programa').' - '.$this->descr_programa);
	$this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	$this->objpdf->text($xcol+17,$xlin+31,':  '.db_formatar($this->sintetico,'elemento'));
	$this->objpdf->setxy($xcol+18,$xlin+32);
	$this->objpdf->multicell(90,3,$this->descr_sintetico,0,"L");
	$this->objpdf->text($xcol+17,$xlin+40,':  '.$this->recurso.' - '.$this->descr_recurso);
	$this->objpdf->text($xcol+17,$xlin+44,':  '.$this->coddot);
	$this->objpdf->text($xcol+17,$xlin+48,':  '.$this->descr_licitacao);

        //// retangulo dos dados do credor
	$this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->text($xcol+107,$xlin+7,'N� Credor');
	$this->objpdf->text($xcol +150, $xlin +7, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
	$this->objpdf->text($xcol+107,$xlin+10,'Nome');
	$this->objpdf->text($xcol+107,$xlin+13,'Endere�o');
	$this->objpdf->text($xcol+107,$xlin+16,'Munic�pio');
	$this->objpdf->text($xcol +107, $xlin +19, 'Banco/Ag./Conta');
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
	$this->objpdf->text($xcol +158, $xlin +7,  ': '. (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')));
	$this->objpdf->text($xcol+124,$xlin+10,': '.$this->nome);
	$this->objpdf->text($xcol+124,$xlin+13,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+16,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);

	$this->objpdf->text($xcol +131, $xlin +19, ': '.$this->iBancoFornecedor.' / '.$this->iAgenciaForncedor.' / '.$this->iContaForncedor);

	///// retangulo dos valores
	$this->objpdf->rect($xcol+106,$xlin+21.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+21.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+157,$xlin+23.5,'Valor Empenhado');
	$this->objpdf->text($xcol+108,$xlin+34.0,'Valor Or�ado');
	$this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
	$this->objpdf->text($xcol+108,$xlin+44.5,'Valor Anulado');
	$this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
  $this->objpdf->text($xcol+108,$xlin+27,'SEQ. EMP. N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
	$this->objpdf->Setfont('Arial','',8);
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	$this->objpdf->text($xcol+180,$xlin+27.5,db_formatar($this->empenhado,'f'));
	$this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
	$this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
	$this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->anulado,'f'));
	$this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_atu,'f'));

        /// retangulo do corpo do empenho
	$this->objpdf->rect($xcol,$xlin+60,10,130,2,'DF','');
	$this->objpdf->rect($xcol+10,$xlin+60,11,130,2,'DF','');
	$this->objpdf->rect($xcol+21,$xlin+60,14,130,2,'DF','');
	$this->objpdf->rect($xcol+35,$xlin+60,105,130,2,'DF','');
	$this->objpdf->rect($xcol+140,$xlin+60,15,130,2,'DF','');
	$this->objpdf->rect($xcol+155,$xlin+60,23,163,2,'DF','');
	$this->objpdf->rect($xcol+178,$xlin+60,24,163,2,'DF','');	
	$this->objpdf->rect($xcol,$xlin+190,155,33,2,'DF','');

	//// retangulos do titulo do corpo do empenho
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->rect($xcol,$xlin+54,10,6,2,'DF','12');
	$this->objpdf->rect($xcol+10,$xlin+54,11,6,2,'DF','12');
	$this->objpdf->rect($xcol+21,$xlin+54,14,6,2,'DF','12');
	$this->objpdf->rect($xcol+35,$xlin+54,105,6,2,'DF','12');
	$this->objpdf->rect($xcol+140,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+155,$xlin+54,23,6,2,'DF','12');
	$this->objpdf->rect($xcol+178,$xlin+54,24,6,2,'DF','12');

	//// t�tulo do corpo do empenho
	$this->objpdf->text($xcol+2,$xlin+58,'SEQ.');
	$this->objpdf->text($xcol+11,$xlin+58,'QUANT');
	$this->objpdf->text($xcol+25,$xlin+58,'ITEM');
	$this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVI�O');
	$this->objpdf->text($xcol+142,$xlin+58,'UNIDADE');
	$this->objpdf->text($xcol+156,$xlin+58,'VALOR UNIT�RIO');
	$this->objpdf->text($xcol+181,$xlin+58,'VALOR TOTAL');
  $maiscol = 0;

	/// monta os dados para itens do empenho
	$this->objpdf->SetWidths(array(10,11,14,105,15,23,24));
	$this->objpdf->SetAligns(array('C','C','C','L','C','R','R'));  

  $this->objpdf->setleftmargin(4);
  $this->objpdf->sety($xlin+62);

  $this->objpdf->Setfont('Arial', 'B', 7);

  $this->objpdf->cell(35, 4, '', 0, 0, "C", 0);

  $sElemento   = pg_result($this->recorddositens, $ii, $this->analitico);
  $sElemento   = db_formatar($sElemento, 'elemento');
  $sResumoItem = pg_result($this->recorddositens, $ii, $this->descr_analitico);

  $this->objpdf->cell(105, 4, "{$sElemento} - {$sResumoItem}", 0, 1, "L", 0);

  $ele                    = 0;
  $xtotal                 = 0;
  $retorna_obs            = 0;
  $nValorItemTotalAnulado = 0;

  $iTotalLinhas = $this->linhasdositens;
	for($ii = 0;$ii < $iTotalLinhas ;$ii++) {

	  $oItens = db_utils::getCollectionByRecord($this->recorddositens);

	  $this->objpdf->SetWidths(array(10,11,14,105,15,23,24));
	  $this->objpdf->SetAligns(array('C','C','C','L','C','R','R'));
	  db_fieldsmemory($this->recorddositens, $ii);

	  if ($retorna_obs == 0) {

	    $xtotal        += pg_result($this->recorddositens, $ii, $this->valoritem);
		$sequencialitem = pg_result($this->recorddositens, $ii, $this->sequencialitem);
		$item 	   	    = pg_result($this->recorddositens, $ii, $this->item);
	    $quantitem      = pg_result($this->recorddositens, $ii, $this->quantitem);
		$unidadeitem    = pg_result($this->recorddositens, $ii, $this->unidadeitem);
	    $descricaoitem  = pg_result($this->recorddositens, $ii, $this->descricaoitem);

	    // $obsitem       = "OBS.: " . pg_result($this->recorddositens, $ii, 'e60_resumo');
	    // $obsitem      .= "\n".'SOLICITA��O: '.pg_result($this->recorddositens,$ii,$this->Snumero) . "\n\n";

	    $valoritemuni            = db_formatar(pg_result($this->recorddositens, $ii, 'e62_vlrun'), 'v', " ", $this->casadec);
	    $valoritemtot            = pg_result($this->recorddositens, $ii, 'e37_vlranu');
	    $nValorItemTotalAnulado += $valoritemtot;
        $valoritemtot            = db_formatar($valoritemtot, 'f');
        if(pg_result($this->recorddositens, $ii, 'pc01_servico') == 't'
            && pg_result($this->recorddositens, $ii, 'e62_servicoquantidade') == 'f'){
            $valoritemuni = $valoritemtot;
        }

	  } else {
		
	    $descricaoitem  = $descricaoitemimprime;
	    $retorna_obs    = 0;
		$sequencialitem	= ""; 
		$item	   	    = "";   
	    $quantitem      = "";
		$unidadeitem    = ""; 
	    $valoritemuni   = "";
	    $valoritemtot   = "";
	  }

	  $set_altura_row = $this->objpdf->h - 125;
	  if ($pagina != 1) {
	    $set_altura_row = $this->objpdf->h - 30;
	  }

	  /**
	   *
	   * Verifica os casos em que o resumo n�o tem quebra e � maior que o tamanho restante da p�gina
	   *
	   * - � feito a corre��o inserindo uma quebra no ponto limite para a impress�o do resumo
	   *
	   */
	  // Largura total do multicell
	  $iWidthMulticell  = $this->objpdf->widths[3];
	  // Consulta o total de linhas restantes
	  $iLinhasRestantes = ((( $this->objpdf->h - 25 ) - $this->objpdf->GetY()) / 3 );
	  // Consulta o total de linhas que ser� utilizado no multicelll
	  $iLinhasMulticell = $this->objpdf->NbLines($iWidthMulticell,$descricaoitem);
	  // Verifica se o total de linhas utilizadas no multicell � maior que as linhas restantes
	  if ( $iLinhasMulticell > $iLinhasRestantes ) {

	    // Total de carateres necess�rios para a impress�o at� o fim da p�gina
	    $iTotalCaract = ( $iWidthMulticell * $iLinhasRestantes );
	    $iLimitString = $iTotalCaract;

	    // Percorre o resumo do limite de caraceters at� um ponto que haja espa�o em branco para n�o quebre alguma palavra
	    for ($iInd = $iTotalCaract; $iInd < strlen($descricaoitem); $iInd++) {

	      if ( $descricaoitem{$iInd} == ' ') {

	        $iLimitString = $iInd;
	        break;
	      }
	    }
	    // Insere quebra no ponto informado
	    //$descricaoitem = substr($descricaoitem,0,$iLimitString)."".substr($descricaoitem,$iLimitString,strlen($descricaoitem));
	  }
	  $this->objpdf->Setfont('Arial', '', 6);
	  $descricaoitemimprime = $this->objpdf->Row_multicell(array($sequencialitem,
																 $quantitem,
	  															 $item,
	                                                             $descricaoitem,
																 $unidadeitem,
	                                                             $valoritemuni,
	                                                             $valoritemtot),
                                                      	  4.5,
                                                      	  false,
                                                      	  5,
                                                      	  0,
                                                      	  true,
                                                      	  true,
                                                      	  1,
                                                      	  $set_altura_row
	                                                       );

	  //$descricaoitemimprime = str_replace('\\n', '\n', $descricaoitemimprime);

	  if ( trim($descricaoitemimprime) != "" && $iTotalLinhas > 1 ) {

	    $retorna_obs = 1;
	    $ii--;
	  }

	  if (($this->objpdf->gety() > $this->objpdf->h - 125 && $pagina == 1) ||
	  ($this->objpdf->gety() > $this->objpdf->h - 30 && $pagina != 1)) {
	    $proxima_pagina = $pagina +1;
	    $this->objpdf->Row(array('','','', "Continua na pgina $proxima_pagina",'','',''), 3, false, 4);
	    if ($pagina == 1) {

			$this->objpdf->rect($xcol,$xlin+223,155,6,2,'DF','34');
			$this->objpdf->rect($xcol+155,$xlin+223,23,6,2,'DF','34');
			$this->objpdf->rect($xcol+178,$xlin+223,24,6,2,'DF','34');	

    //	$this->objpdf->setfillcolor(0,0,0);
    	  $this->objpdf->SetFont('Arial','',7);
    	  $this->objpdf->text($xcol+2,$xlin+227,'DESTINO : ',0,1,'L',0);
    	  $this->objpdf->text($xcol+30,$xlin+227,$this->destino,0,1,'L',0);

    	  $this->objpdf->setxy($xcol+1,$xlin+195);
    	  $this->objpdf->text($xcol+2,$xlin+194,'MOTIVO : ',0,1,'L',0);
    	  $this->objpdf->multicell(147,3.5,$this->resumo);

    	  $this->objpdf->text($xcol+159,$xlin+227,'T O T A L',0,1,'L',0);
    	  $this->objpdf->setxy($xcol+185,$xlin+222);
    	  $this->objpdf->cell(30,10,db_formatar( $this->anulado , 'f' ),0,0,'f');

	      $sqlparag  = "select db02_texto ";
	      $sqlparag .= "  from db_documento ";
	      $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
	      $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
	      $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
	      $sqlparag .= " where db03_tipodoc = 1506 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

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
	        $sqlparagpadrao .= " where db60_tipodoc = 1506 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";

	        $resparagpadrao = @db_query($sqlparagpadrao);
	        if (@pg_numrows($resparagpadrao) > 0) {
	          db_fieldsmemory($resparagpadrao,0);
            /**[extensao ordenadordespesa] doc_padrao*/

	          eval($db61_texto);
	        }
	      }

	      $this->objpdf->SetFont('Arial', '', 4);
	      $this->objpdf->Text(2, 296, $this->texto);
	      $this->objpdf->setfont('Arial', '', 11);

	      $xlin = 169;
	    }

	    $this->objpdf->addpage();
	    $pagina += 1;

	    $this->objpdf->settopmargin(1);
	    $xlin = 20;
	    $xcol = 4;

	    //Inserindo usuario e data no rodape
	    $this->objpdf->Setfont('Arial', 'I', 6);
	    $this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y", db_getsession("DB_datausu"))."");

	    $this->objpdf->setfillcolor(245);
	    $this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
	    $this->objpdf->setfillcolor(255, 255, 255);
	    $this->objpdf->Setfont('Arial', 'B', 10);

    	$this->objpdf->setfillcolor(245);
    	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
    	$this->objpdf->setfillcolor(255,255,255);
    	$this->objpdf->Setfont('Arial','B',10);
    	$this->objpdf->text(126,$xlin-13,'NOTA DE ANULA��O N'.CHR(176).': ');
    	$this->objpdf->text(175,$xlin-13,db_formatar($this->notaanulacao,'s','0',6,'e'));
    	$this->objpdf->text(134,$xlin-8,'DATA DE EMISS�O : ');
    	$this->objpdf->text(175,$xlin-8,$this->emissao);

    	$this->objpdf->text(134,$xlin-3,'        EMPENHO : ');
    	$this->objpdf->text(175,$xlin-3,trim($this->codemp)."/".$this->anousu);

      $this->objpdf->Image('imagens/files/'.$this->logo ,15,$xlin-17,12);
    	$this->objpdf->Setfont('Arial','B',9);
    	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
    	$this->objpdf->Setfont('Arial','',9);
    	$this->objpdf->text(40,$xlin-11,$this->enderpref);
    	$this->objpdf->text(40,$xlin-8,$this->municpref);
    	$this->objpdf->text(40,$xlin-5,$this->telefpref);
    	$this->objpdf->text(40,$xlin-2,$this->emailpref);

    	$xlin = -30;
	     $this->objpdf->Setfont('Arial', 'B', 7);

		$this->objpdf->rect($xcol, $xlin +54, 10, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol+10, $xlin +54, 11, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +21, $xlin +54, 14, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +35, $xlin +54, 105, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +140, $xlin +54, 15, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +155, $xlin +54, 23, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +178, $xlin +54, 24, 6, 2, 'DF', '12');

		$this->objpdf->rect($xcol, $xlin +54, 10, 262, 2, 'DF', '34');
		$this->objpdf->rect($xcol+10, $xlin +54, 11, 262, 2, 'DF', '34');
		$this->objpdf->rect($xcol +21, $xlin +54, 14, 262, 2, 'DF', '34');
		$this->objpdf->rect($xcol +35, $xlin +54, 105, 262, 2, 'DF', '34');
		$this->objpdf->rect($xcol +140, $xlin +54, 15, 262, 2, 'DF', '34');
		$this->objpdf->rect($xcol +155, $xlin +54, 23, 262, 2, 'DF', '34');
		$this->objpdf->rect($xcol +178, $xlin +54, 24, 262, 2, 'DF', '34'); 

	    $this->objpdf->sety($xlin +66);
	    $alt = 4;

	    $this->objpdf->text($xcol+2,$xlin+58,'SEQ.');
		$this->objpdf->text($xcol+11,$xlin+58,'QUANT');
		$this->objpdf->text($xcol+25,$xlin+58,'ITEM');
		$this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIO');
		$this->objpdf->text($xcol+142,$xlin+58,'UNIDADE');
		$this->objpdf->text($xcol+156,$xlin+58,'VALOR UNITRIO');
		$this->objpdf->text($xcol+181,$xlin+58,'VALOR TOTAL');
	    $this->objpdf->text($xcol +38, $xlin +63, 'Continua��o da P�gina '. ($this->objpdf->PageNo() - 1));

	    $maiscol = 0;
	  }
	}

  if ($pagina == 1) {

    $this->objpdf->rect($xcol,$xlin+223,155,6,2,'DF','34');
    $this->objpdf->rect($xcol+155,$xlin+223,23,6,2,'DF','34');
    $this->objpdf->rect($xcol+178,$xlin+223,24,6,2,'DF','34');


//	$this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->SetFont('Arial','',7);
	  $this->objpdf->text($xcol+2,$xlin+227,'DESTINO : ',0,1,'L',0);
	  $this->objpdf->text($xcol+30,$xlin+227,$this->destino,0,1,'L',0);

	  $this->objpdf->setxy($xcol+1,$xlin+195);
	  $this->objpdf->text($xcol+2,$xlin+194,'MOTIVO : ',0,1,'L',0);
	  $this->objpdf->multicell(147,3.5,$this->resumo);

	  $this->objpdf->text($xcol+159,$xlin+227,'T O T A L',0,1,'L',0);
	  $this->objpdf->setxy($xcol+185,$xlin+222);
	  $this->objpdf->cell(30,10,db_formatar($this->anulado,'f'),0,0,'f');

    $sqlparag  = "select db02_texto   ";
    $sqlparag .= "  from db_documento ";
    $sqlparag .= "       inner join db_docparag on db03_docum    = db04_docum   ";
    $sqlparag .= "       inner join db_tipodoc on db08_codigo    = db03_tipodoc ";
    $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
    $sqlparag .= " where db03_tipodoc = 1506 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

    $resparag = db_query($sqlparag);

    if ( $resparag && pg_numrows($resparag) > 0) {

      db_fieldsmemory($resparag,0);
      /**[extensao ordenadordespesa] doc_usuario*/

      eval($db02_texto);
    } else {

      $sqlparagpadrao  = "select db61_texto ";
      $sqlparagpadrao .= "  from db_documentopadrao ";
      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc   ";
      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc  ";
      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
      $sqlparagpadrao .= " where db60_tipodoc = 1506 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";
      $resparagpadrao = db_query($sqlparagpadrao);
      if ( $resparagpadrao && pg_numrows($resparagpadrao) > 0) {

        db_fieldsmemory($resparagpadrao,0);
        /**[extensao ordenadordespesa] doc_padrao*/

        eval($db61_texto);
      }
    }
	//Inserindo usuario e data no rodape
	$this->objpdf->Setfont('Arial', 'I', 6);
	$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y", db_getsession("DB_datausu"))."");

    $xlin = 169;
 }
?>
