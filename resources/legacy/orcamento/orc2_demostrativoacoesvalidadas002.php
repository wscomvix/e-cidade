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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_sql.php"));
require_once(modification("classes/db_ppadotacao_classe.php"));
require_once(modification("classes/db_ppaestimativa_classe.php"));
require_once(modification("model/ppaVersao.model.php"));

$oGet = db_utils::postMemory($_GET);
$iModelo = $oGet->iModelo;
$clppadotacao    = new cl_ppadotacao();
$clppaestimativa = new cl_ppaestimativa();
$oDaoPPALei = db_utils::getDao("ppalei");
$oPPAVersao      = new ppaVersao($oGet->ppaversao);
$sListaInstit    = str_replace("-", ",", $oGet->sListaInstit);
$sWhere  = "      o05_ppaversao = {$oGet->ppaversao}	 						";
$sWhere .= "  AND o05_anoreferencia BETWEEN {$oGet->anoini} AND {$oGet->anofin} ";
$sWhereAcao = $sWhere;
$controle   = $oGet->lQuebra == "true"?true:false;
$sWherePrograma  = "";
$sWhereOrgao     = "";
if (isset($oGet->programa) && $oGet->programa != "") {
  $sWherePrograma = " AND o08_programa IN ({$oGet->programa})";
}

if (isset($oGet->orgao) && $oGet->orgao != "") {
  $sWhereOrgao = " AND o08_orgao IN({$oGet->orgao}) ";
}

if ($iModelo == 1) {
    $sSqlEstimativa = " SELECT DISTINCT o08_programa, /* Programa do PPA */
                                        o54_descr /* Descri��o do Programa */
                        FROM ppadotacao
                        INNER JOIN ppaestimativadespesa ON o08_sequencial = o07_coddot
                        INNER JOIN ppaestimativa ON o07_ppaestimativa = o05_sequencial
                        INNER JOIN orcprograma ON orcprograma.o54_anousu = {$oGet->iAno} AND orcprograma.o54_programa = ppadotacao.o08_programa
                        LEFT JOIN orcindicaprograma ON orcindicaprograma.o18_orcprograma = orcprograma.o54_programa AND orcindicaprograma.o18_anousu = " . db_getsession('DB_anousu') . "
                        WHERE $sWhere
                          AND o08_instit IN ({$sListaInstit}) {$sWherePrograma} {$sWhereOrgao}
                        ORDER BY o08_programa";

} else {
    $sSqlEstimativa = "SELECT o54_programa AS o08_programa,
                              o54_descr
                       FROM orcprograma
                       WHERE o54_anousu = {$oGet->iAno}";

}
$rsEstimativa = pg_query($sSqlEstimativa);
$iLinhasEstimativa = pg_num_rows($rsEstimativa);

$aEstimativa	   = array();

$valor = 0;
$aTotalAcoes  = array();
for ( $iInd=0; $iInd < $iLinhasEstimativa; $iInd++ ) {

  $oDadosEstimativa = db_utils::fieldsMemory($rsEstimativa,$iInd);

  $oDados = new stdClass();

  $oDados->iPrograma     	 = $oDadosEstimativa->o08_programa;
  $oDados->sPrograma     	 = $oDadosEstimativa->o54_descr;

  if ($iModelo == 2) {

    $sWhere = " o55_anousu = {$oGet->iAno} ";

    $sSqlAcoes = " SELECT DISTINCT o55_projativ AS o08_projativ,
                          o55_descr,
                          o55_anousu o05_anoreferencia,
                          (SELECT sum(o28_valor) FROM orcprojativprogramfisica
                           WHERE o28_orcprojativ = ppadotacao.o08_projativ
                             AND o28_anoref = o05_anoreferencia) AS o28_valor,
                          upper(o55_especproduto) o22_descrprod,
                          upper(o55_descrunidade) o55_descrunidade,
                          o55_valorunidade,
                          o20_descricao,
                          CASE
                              WHEN o55_tipo = 1 THEN 'Projeto'
                              WHEN o55_tipo = 2 THEN 'Atividade'
                              WHEN o55_tipo = 3 THEN 'Opera��es Especiais'
                              WHEN o55_tipo = 9 THEN 'Reserva de Contig�ncia'
                          END AS tipo,
                          round(CASE
                                    WHEN ppadotacao.o08_projativ IS NULL THEN sum(o58_valor)
                                    ELSE (SELECT DISTINCT sum(o58_valor) FROM orcprojativ
                                          JOIN orcdotacao ON (o58_anousu, o58_projativ) = (o55_anousu, o55_projativ)
                                          WHERE o55_projativ = ppadotacao.o08_projativ
                                              AND (o58_programa, o55_anousu) = ({$oDados->iPrograma}, {$oGet->iAno}))
                                END,2) AS valor
                   FROM orcprojativ
                   LEFT JOIN ppadotacao ON orcprojativ.o55_projativ = ppadotacao.o08_projativ AND orcprojativ.o55_anousu = o08_ano
                   LEFT JOIN ppaestimativadespesa ON o08_sequencial = o07_coddot
                   LEFT JOIN ppaestimativa ON o07_ppaestimativa = o05_sequencial
                   LEFT JOIN orcproduto ON orcproduto.o22_codproduto = orcprojativ.o55_orcproduto
                   LEFT JOIN orcprojativunidaderesp ON o13_orcprojativ = o55_projativ AND o13_anousu = o55_anousu
                   LEFT JOIN unidaderesp ON o13_unidaderesp = o20_sequencial
                   INNER JOIN orcdotacao ON (o58_anousu, o58_projativ) = (o55_anousu, o55_projativ)
                   INNER JOIN orctiporec ON orctiporec.o15_codigo = o58_codigo
                   WHERE $sWhere
                     AND o58_programa = {$oDados->iPrograma}
                     AND o58_instit IN ({$sListaInstit})
                   GROUP BY 1, 3, ppadotacao.o08_projativ, 4, 8, o05_anoreferencia
                   ORDER BY o08_projativ, o05_anoreferencia";

  } elseif ($iModelo == 1) {

    $sSqlAcoes = " SELECT o08_projativ,
                          o55_descr,
                          o05_anoreferencia,
                          (SELECT sum(o28_valor) FROM orcprojativprogramfisica
                           WHERE o28_orcprojativ = ppadotacao.o08_projativ
                             AND o28_anoref = o05_anoreferencia) AS o28_valor,
                          o22_descrprod,
                          o55_descrunidade,
                          o55_valorunidade,
                          o20_descricao,
                          CASE
                              WHEN o55_tipo = 1 THEN 'Projeto'
                              WHEN o55_tipo = 2 THEN 'Atividade'
                              WHEN o55_tipo = 3 THEN 'Opera��es Especiais'
                              WHEN o55_tipo = 9 THEN 'Reserva de Contig�ncia'
                          END AS tipo,
                          round(sum(o05_valor),2) AS valor
                   FROM ppadotacao
                   INNER JOIN ppaestimativadespesa ON o08_sequencial = o07_coddot
                   INNER JOIN ppaestimativa ON o07_ppaestimativa = o05_sequencial
                   INNER JOIN orcprojativ ON orcprojativ.o55_projativ = ppadotacao.o08_projativ AND orcprojativ.o55_anousu = o08_ano
                   LEFT JOIN orcproduto ON orcproduto.o22_codproduto = orcprojativ.o55_orcproduto
                   INNER JOIN orctiporec ON orctiporec.o15_codigo = ppadotacao.o08_recurso
                   LEFT JOIN orcprojativunidaderesp ON o13_orcprojativ = o55_projativ AND o13_anousu = o55_anousu
                   LEFT JOIN unidaderesp ON o13_unidaderesp = o20_sequencial
                   WHERE $sWhere
                     AND o08_programa = {$oDados->iPrograma}
                     AND o08_instit IN ({$sListaInstit})
                   GROUP BY o08_projativ, o15_tipo, o55_tipo, o55_descr, o22_descrprod, o20_descricao, o55_descrunidade, o55_valorunidade, o05_anoreferencia
                   ORDER BY o08_projativ, o05_anoreferencia";

  }

  $rsConsultaAcoes 	= pg_query($sSqlAcoes);

  $iLinhasAcoes    	= pg_num_rows($rsConsultaAcoes);
  $aAcoes 			= array();


  if ( $iLinhasAcoes > 0 ) {

  	for ( $iIndAcao=0; $iIndAcao < $iLinhasAcoes; $iIndAcao++ ) {

  	  $oDadosAcao = db_utils::fieldsMemory($rsConsultaAcoes,$iIndAcao);

  	  $oAcao = new stdClass();
  	  $aAcoes[$oDadosAcao->o08_projativ]['iAcao']        = str_pad($oDadosAcao->o08_projativ, 4, '0', STR_PAD_LEFT);
  	  $aAcoes[$oDadosAcao->o08_projativ]['sDescricao']   = $oDadosAcao->o55_descr;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sProduto']     = $oDadosAcao->o22_descrprod;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sUnidade']     = $oDadosAcao->o20_descricao;
  		if ($iModelo == 2) {

  	    if ($oDadosAcao->o05_anoreferencia != $oGet->iAno) {

  	      $oDadosAcao->vinculado = "";
  	      $oDadosAcao->valor     = "";
  	      $oDadosAcao->o28_valor = "";

  	    }
  	  }
  	  $aAcoes[$oDadosAcao->o08_projativ]['sUnidadeMed']  = $oDadosAcao->o55_descrunidade;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sTipo']     = $oDadosAcao->tipo;
  	  $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nQuantFisica'] = $oDadosAcao->o28_valor;
  	  $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nValor']       += $oDadosAcao->valor;
  	  @$aTotalAcoes[$oDados->iPrograma][$oDadosAcao->o05_anoreferencia] += $oDadosAcao->valor;
  	}

  }


  $oDados->iLinhasAcoes = $iLinhasAcoes;
  $oDados->aAcoes 		= $aAcoes;


  $aEstimativa[] = $oDados;


}

$mostra = 0;



/*echo "<pre>";
var_dump($aEstimativa);
echo "</pre>";
exit;*/
$sSqlPPALei  = $oDaoPPALei->sql_query($oGet->ppalei);
$rsPPALei    = $oDaoPPALei->sql_record($sSqlPPALei);
$oLeiPPA     = db_utils::fieldsMemory($rsPPALei, 0);

//$head2  = "ANEXO DE OBJETIVOS , DIRETRIZES E METAS";
//$head3  = "PPA - {$oGet->anoini} - {$oGet->anofin}";
$head4  = "Lei {$oLeiPPA->o01_numerolei} - {$oLeiPPA->o01_descricao}";
if ( $oGet->selforma == "s" ) {
	$head4 = "Forma de Emiss�o: Sint�tico";
}elseif ($oGet->selforma == "a"){
	$head4 = "Forma de Emiss�o: Anal�tico";
}
if ($iModelo == 1) {

  $head2  = "ANEXO DE OBJETIVOS , DIRETRIZES E METAS";
  $head3  = "PPA - {$oGet->anoini} - {$oGet->anofin}";

  //Modifica��o T25780
//  $head4  = "Vers�o: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
  //

} else {

  $head2  = "LEI DE DIRETRIZES OR�AMENT�RIAS - EXERC�CIO DE $oGet->iAno";
}
$head5 = "Perspectiva: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
//$pdf->AddPage("L");
$pdf->setfillcolor(244);

if ( $oGet->imprimerodape == "n" ) {
  $pdf->imprime_rodape = false;
}

$alt = "4";
/*
 * Este if teste se o relat�rio vai ser impresso na forma sint�tica
 * como estava padr�o no sistema
 */
$iPrograma = null;
if ( $oGet->selforma == "s" ) {
	//$head5 = "Orgao: $oDados->sOrgao";
	if (!$controle) {
	  $pdf->AddPage("L");
	}

  foreach ( $aEstimativa as $oEstimativa ) {

    $mostra = 0;
    foreach ( $oEstimativa->aAcoes as $aDadosAcoes ) {

      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
        $mostra += $aDadosExerc['nValor'];
      }
    }

    if ($mostra == 0) {
      continue;
    }
    if ($controle && $iPrograma != $oEstimativa->iPrograma) {
     $pdf->addpage("L");
    }
		validaNovaPagina($pdf, 40);
	  $pdf->setfont('arial','B', 8);
	  $pdf->cell(18,$alt, "Programa:",0,0,"L");
	  $pdf->cell(10,$alt,str_pad($oEstimativa->iPrograma, 4, '0', STR_PAD_LEFT) , 0, 0, "R");
	  $pdf->cell(100,$alt, $oEstimativa->sPrograma, 0, 1, "L");
	  $iPosYDepois = $pdf->GetY();
	  $iPosXIndicador = 162;
	  $pdf->ln();
	  if ( $oEstimativa->iLinhasAcoes > 0 ) {

	  	foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) {

	  		$nTotalGeral     = 0;
	  	  foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){
	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	   += $nTotalRecurso;
	  	  }

	  	  if ($nTotalGeral == 0) {
	  	  	continue;
	  	  }

	  	  validaNovaPagina($pdf, 35);
	  	  $pdf->setfont('arial','B', 8);
	  	  $pdf->Cell(62,$alt,"A��o"	  							,"TBR",0,"C",1);
	      $pdf->Cell(26,$alt,"Unidade"		 					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Classifica��o"					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Produto"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Unidade Medida"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Ano"    					,"TRBL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Metas"    					,"TRL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Valor R$"    					,"TL" ,1,"C",1);
	      $iPosYAntes  = $pdf->GetY();

	      $pdf->setfont('arial','', 8);
	      $iAltDescr = ($alt);

	  	  $pdf->setfont('arial','', 8);
	  	  //$pdf->multicell(62,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),90," ",STR_PAD_RIGHT),"TR","L",0);
	  	  $pdf->multicell(62,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),4," ",STR_PAD_LEFT),"TR","L",0);
	  	  $pdf->setfont('arial','', 8);
	  	  $pdf->SetXY(72,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidade'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(98,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sTipo'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(124,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sProduto'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(150,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidadeMed'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	   	  $nTotalLivre     = 0;
	  	  $nTotalVinculado = 0;
	  	  $nTotalMetas     = 0;
	  	  $nTotalGeral     = 0;
	  	  $iLinhasExerc	 = 0;
	  	  $pdf->SetY($iPosYAntes);
	      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){

	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	   += $nTotalRecurso;
	   	    $nTotalMetas     += $aDadosExerc['nQuantFisica'];
	     		$tipo = "f";
	        if ($iModelo == 2) {

			 	    if ($iExercicio != $oGet->iAno) {
			 	      $iExercicio = "";
			 	      $tipo = "";
			 	    }
			 	  }
	  	    $pdf->SetX(176);
	  	    $pdf->Cell(26,$alt,$iExercicio  			     			,"BR" ,0,"C",0);
	  	    $pdf->Cell(40,$alt,$aDadosExerc['nQuantFisica']	,"TBR",0,"R",0);
	  	    $pdf->Cell(40,$alt,db_formatar($aDadosExerc['nValor'],$tipo)	,"TBL",1,"R",0);
	  	    $iLinhasExerc++;

	      }
	  	  $pdf->SetY($iPosYAntes);
	  	  for ($iSeq=0; $iSeq < 4; $iSeq++ ) {

	  	    $sBorda = "R";
	  	    if ($iSeq == 3) {
	  	      $sBorda = "RB";
	  	    }
	  	    $pdf->Cell(62,$alt,"","{$sBorda}" ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	        $pdf->Cell(26,$alt,"",$sBorda ,1);
	      }
	  	  $pdf->setfont('arial','B', 8);
	  	  $pdf->Cell(192,$alt,"Total da a��o para os exerc�cios" ,"TBR",0,"R",0);
	  	  $pdf->setfont('arial','' , 8);
	  	  $pdf->Cell(40 ,$alt, $nTotalMetas ,"TBR",0,"R",0);
	  	  $pdf->Cell(40 ,$alt,db_formatar($nTotalGeral,"f")	   ,"TBL",1,"R",0);
	      $pdf->setfont('arial','B', 8);
	      $pdf->ln();
	  	}
	  }
	  $pdf->ln();
    /**
     * Escrevemos o Totalizador do Programa
     */
    validaNovaPagina($pdf, 40);
    $pdf->Cell(193,$alt,""          ,"TBR",0,"C",0);
    $pdf->Cell(40 ,$alt,"Ano"       ,"TBR",0,"C",0);
    $pdf->Cell(40 ,$alt,"Valor"       ,"TBR",1,"C",0);

    $iY = $pdf->getY();
    $pdf->Cell(193,$alt*3," TOTAL PROGRAMA","TBR",1,"C",0);
    $pdf->setY($iY);
    $nTotalGeral     = 0;
    foreach ( $aTotalAcoes[$oEstimativa->iPrograma] as $iExercicio => $aDados ) {

      $nTotalValor     = $aDados;
      $nTotalGeral     += $nTotalValor;
      $pdf->SetX(203);
      $pdf->Cell(40,$alt,$iExercicio                           ,"BR" ,0,"C",0);
      $pdf->Cell(40,$alt,db_formatar($nTotalValor,"f")        ,"TB" ,1,"R",0);

    }
    $pdf->Cell(193,$alt*-1,"Total dos Exerc�cios","TBR",0,"C",0);
    $pdf->Cell(40,$alt,""                               ,"BR" ,0,"C",0);
    $pdf->Cell(40,$alt,db_formatar($nTotalGeral,"f")  ,"TB" ,1,"R",0);
    $pdf->ln();
    $iPrograma = $oEstimativa->iPrograma;
	}

/*
 * Este parte do if tem a parte nova onde imprimi o relatorio analitico
 * detalhando o Programa e a A��o
 */
} else if ( $oGet->selforma == "a" ) {


  if (!$controle) {
    $pdf->AddPage("L");
  };
  $pdf->setfont('arial','B', 8);
  foreach ( $aEstimativa as $iInd => $oEstimativa ) {

   /*
    *Faz a verifica��o dos registros que poder�o ser mostrados
    * - Dever�  ter valor
    *  - Dever�o ter a��es
    */
   foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) {

   $mostra = 0;
   foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
     $mostra += $aDadosExerc['nQuantFisica']+$aDadosExerc['nValor'];
   }
 }

  if($mostra == 0) {
  	continue;
  }
 //validaNovaPagina(&$pdf, 40);
   if ($controle && $iPrograma != $oEstimativa->iPrograma) {
     $pdf->addpage("L");
    }
	  $pdf->setfont('arial','B', 8);
	  $pdf->cell(18,$alt, "Programa:",0,0,"L");
	  $pdf->cell(10,$alt, str_pad($oEstimativa->iPrograma, 4, '0', STR_PAD_LEFT), 0, 0, "R");
	  $pdf->cell(100,$alt, $oEstimativa->sPrograma, 0, 1, "L");
	  $iPosYDepois = $pdf->GetY();
	  $iPosXIndicador = 162;

	  //Busca as inform��es detalhadas do programa e da a��o
	  $sSqlPrograma  = "select case when o54_tipoprograma = 1 then 'Programas Final�sticos' ";
	  $sSqlPrograma .= "            when o54_tipoprograma = 2 then 'Programas de Apoio as Pol�ticas P�blicas e �reas Especiais' ";
	  $sSqlPrograma .= "       end as tipo, ";
	  $sSqlPrograma .= "       o54_problema as problema,";
	  $sSqlPrograma .= "       o54_finali as finalidade,";
	  $sSqlPrograma .= "       o54_publicoalvo as alvo, ";
	  $sSqlPrograma .= "       o17_dataini as dataini, ";
	  $sSqlPrograma .= "       o17_datafin as datafin, ";
	  $sSqlPrograma .= "       o54_justificativa as justificativa,";
	  $sSqlPrograma .= "       o54_objsetorassociado as associado,";
	  $sSqlPrograma .= "       o54_estrategiaimp as estrategia    ";
	  $sSqlPrograma .= "  from orcprograma ";
	  $sSqlPrograma .= "  left join orcprogramahorizontetemp on  o54_programa = o17_programa ";
	  $sSqlPrograma .= "  and  o17_anousu = ".db_getsession('DB_anousu');
	  $sSqlPrograma .= "where o54_anousu 	 = ".db_getsession('DB_anousu');
	  $sSqlPrograma .= "  and o54_programa = $oEstimativa->iPrograma";
	  //die($sSqlPrograma);
	  $resSqlPrograma	= pg_query($sSqlPrograma);
  	$iLinhaPrograma	= pg_num_rows($resSqlPrograma);

  	if ( $iLinhaPrograma > 0 ){
  		$oDadosPrograma = db_utils::fieldsMemory($resSqlPrograma,0);
  	} else {

  		$oDadosPrograma = new	stdClass();
  		$oDadosPrograma->tipo						= "";
  		$oDadosPrograma->problema				= "";
  		$oDadosPrograma->finalidade			= "";
  		$oDadosPrograma->alvo						= "";
  		$oDadosPrograma->justificativa	= "";
  		$oDadosPrograma->associado			= "";
  		$oDadosPrograma->estrategia			= "";
  		$oDadosPrograma->dataini			  = "";
  		$oDadosPrograma->datafin		  	= "";

  	}
  	/**
  	 * Consultamos os orgaos que estao vinculados a esse programa possui
  	 */
  		$oDaoProgramaOrgao  = db_utils::getDao("orcprogramaorgao");
  		$sWhere             = "o12_orcprograma = {$oEstimativa->iPrograma}";
  		$sWhere            .= "and o12_anousu  = ".db_getsession('DB_anousu');
  		$sSqlOrgaos         = $oDaoProgramaOrgao->sql_query(null,"o40_orgao, o40_descr", "o40_orgao",$sWhere);
  		$rsOrgaos           = $oDaoProgramaOrgao->sql_record($sSqlOrgaos);
			//$pdf->ln();
			$aOrgaos            = db_utils::getColectionByRecord($rsOrgaos);

			/**
       * Consultamos os orgaos que estao vinculados a esse programa possui
       */
      $oDaoProgramaUnidade  = db_utils::getDao("orcprogramaunidade");
      $sWhere               = "o14_orcprograma = {$oEstimativa->iPrograma}";
      $sWhere              .= "and o14_anousu  = ".db_getsession('DB_anousu');
      $sSqlUnidades         = $oDaoProgramaUnidade->sql_query(null,"o41_orgao,o41_unidade, o41_descr",
                                                              "o41_orgao,o41_unidade ",$sWhere);

      $rsUnidades           = $oDaoProgramaUnidade->sql_record($sSqlUnidades);
      //$pdf->ln();

      $aUnidades            = db_utils::getColectionByRecord($rsUnidades);
			$pdf->setfont('arial','B', 8);
  		$pdf->cell(60,$alt, "Tipo de Programa:",0,0,"L");
  		$pdf->setfont('arial','', 8);
	  	$pdf->multicell(210,$alt, $oDadosPrograma->tipo, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Horizontel Temporal:",0,0,"L");
  		$pdf->setfont('arial','', 8);
  		$pdf->cell(20,$alt, "Data In�cio:",0,0,"L");
  		$pdf->cell(20,$alt, db_formatar($oDadosPrograma->dataini,'d'),0,0,"L");
  		$pdf->cell(20,$alt, "Data T�rmino:",0,0,"L");
  		$pdf->cell(20,$alt, db_formatar($oDadosPrograma->datafin,'d'),0,1,"L");
	  	$pdf->setfont('arial','B', 8);
			$pdf->cell(60,$alt, "Problema:",0,0,"L");
  		$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->problema),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->problema, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->problema, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Finalidade:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->finalidade),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->finalidade, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->finalidade, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "P�blico Alvo:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->alvo),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 35));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->alvo, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->alvo, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Justificativa:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->justificativa),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->justificativa, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->justificativa, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Objetivo Setor Associado:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->associado),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->associado, 0,"L");
      }
	  	//$pdf->multicell(210,$alt, $oDadosPrograma->associado, 0,"L");
	  	$pdf->setfont('arial','B', 8);
	  	$pdf->cell(60,$alt, "Estrat�gia de Implementa��o do Programa:",0,0,"L");
	  	$pdf->setfont('arial','', 8);
  		$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosPrograma->estrategia),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
      if($texto != ""){
      	$pdf->addpage("L");
       	$pdf->setfont('arial','', 8);
       	$pdf->cell(60,$alt, "",0,0,"L");
       	$pdf->multicell(210,$alt, $oDadosPrograma->estrategia, 0,"L");
      }

	  	//$pdf->multicell(210,$alt, $oDadosPrograma->estrategia, 0,"L");
  	 $pdf->setfont('arial','B', 8);
     $pdf->cell(60,$alt, "Org�os:",0,1,"L");
     $pdf->setfont('arial','', 8);
     if (count($aOrgaos) > 0) {

       foreach ($aOrgaos as $oOrgao) {

         validaNovaPagina($pdf, 25);
         $pdf->setx(15);
         $pdf->cell(10, $alt,$oOrgao->o40_orgao." - ",0,0,"R");
         $pdf->cell(90, $alt,$oOrgao->o40_descr,0,1,"L");

       }
     }

     $pdf->setfont('arial','B', 8);
     $pdf->cell(60,$alt, "Unidades:",0,1,"L");
     $pdf->setfont('arial','', 8);
     if (count($aUnidades) > 0) {

       foreach ($aUnidades as $oUnidade) {

         validaNovaPagina($pdf, 25);
         $pdf->setx(15);
         $pdf->cell(15, $alt,$oUnidade->o41_orgao.".{$oUnidade->o41_unidade} - ",0,0,"R");
         $pdf->cell(90, $alt,$oUnidade->o41_descr,0,1,"L");

       }
     }

	  $pdf->ln();
	  if ( $oEstimativa->iLinhasAcoes > 0 ) {

	  	foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ){

	  		$sSqlAcao  = "select o55_finali as finalidade,    ";
	  		$sSqlAcao .= "       o55_especproduto as produto, ";
	  		$sSqlAcao .= "       case when o55_tipoacao = 1 then 'Or�ament�ria' ";
	  		$sSqlAcao .= "            when o55_tipoacao = 2 then 'N�o-Or�ament�ria' ";
	  		$sSqlAcao .= "       end as tipo, ";
	  		$sSqlAcao .= "       case when o55_formaimplementacao = 1 then 'Direta' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 2 then 'Descentralizada' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 3 then 'Transfer�ncia Obrigat�ria' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 4 then 'Transfer�ncia Volunt�ria' ";
	  		$sSqlAcao .= "            when o55_formaimplementacao = 5 then 'Transfer�ncia em Linha de Cr�dito'";
	  		$sSqlAcao .= "       end as forma, ";
	  		$sSqlAcao .= "       o55_detalhamentoimp as detalhamento, ";
	  		$sSqlAcao .= "       o55_origemacao as origem, ";
	  		$sSqlAcao .= "       o55_baselegal as base ";
	  		$sSqlAcao .= "  from orcprojativ ";
	  		$sSqlAcao .= " where o55_projativ = {$aDadosAcoes["iAcao"]}";
	  		$sSqlAcao .= "  and  o55_anousu   = ".db_getsession('DB_anousu');
	  		//$sSqlAcao .= "  and  o55_instit   = ".db_getsession('DB_instit');

	  		$resSqlAcao	= pg_query($sSqlAcao);
  	    $iLinhaAcao	= pg_num_rows($resSqlAcao);
  	    $pdf->Ln();
  	    validaNovaPagina($pdf, 35);
  	    $iAltDescr = ($alt);
  	    $pdf->setfont('arial','B', 8);

  	    //verifica a soma se for zero n�o mostra

  	   	$nTotalGeral     = 0;
	  	  foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){
	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	   += $nTotalRecurso;
	  	  }

	  	  if ($nTotalGeral == 0) {
	  	  	continue;
	  	  }


  	    $pdf->cell(18,$alt, "A��o:",0,0,"L");
	  		$pdf->cell(10,$alt, $aDadosAcoes["iAcao"], 0, 0, "R");
	  		$pdf->cell(200,$alt, $aDadosAcoes['sDescricao'], 0, 1, "L");


		  	//$pdf->Cell(25,$alt,"A��o:"	  							,"",0,"L",0);
		  	//$pdf->multicell(200,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),90," ",STR_PAD_RIGHT),"","L",0);

		  	if ( $iLinhaAcao > 0 ){
		  		$oDadosAcao1 = db_utils::fieldsMemory($resSqlAcao,0);
		  	}else{
		  		$oDadosAcao1 = new stdClass();
		  		$oDadosAcao1->finalidade	= "";
		  		$oDadosAcao1->produto 	 	= "";
		  		$oDadosAcao1->tipo				= "";
		  		$oDadosAcao1->forma				= "";
		  		$oDadosAcao1->detalhamento= "";
		  		$oDadosAcao1->origem			= "";
		  		$oDadosAcao1->base				= "";
		  	}
					//$pdf->ln();
					validaNovaPagina($pdf, 25);
					$pdf->setfont('arial','B', 8);
		  		$pdf->cell(60,$alt, "Finalidade:",0,0,"L");
		  		$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->finalidade),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->finalidade, 0,"L");
          }
          validaNovaPagina($pdf, 25);
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->finalidade, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Especifica��o do Produto:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->produto),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->produto, 0,"L");
          }
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->produto, 0,"L");
			  	validaNovaPagina($pdf, 25);
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Tipo de A��o:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->tipo),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->tipo, 0,"L");
          }
          validaNovaPagina($pdf, 25);
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->tipo, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Forma de Implementa��o:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$pdf->multicell(210,$alt, $oDadosAcao1->forma, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	validaNovaPagina($pdf, 25);
			  	$pdf->cell(60,$alt, "Detalhamento da Implementa��o:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
			  	$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->detalhamento),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->detalhamento, 0,"L");
          }
			  	//$pdf->setfont('arial','', 8);
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->detalhamento, 0,"L");
			  	validaNovaPagina($pdf, 25);
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Origem da A��o:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->origem),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->origem, 0,"L");
          }
          validaNovaPagina($pdf, 25);
			  	//$pdf->multicell(210,$alt, $oDadosAcao1->origem, 0,"L");
			  	$pdf->setfont('arial','B', 8);
			  	$pdf->cell(60,$alt, "Base Legal:",0,0,"L");
			  	$pdf->setfont('arial','', 8);
	  			$texto = $pdf->Row_multicell(array('','','',stripslashes($oDadosAcao1->base),'',''),
                                              $alt,false,5,0,true,true,3,($pdf->h - 25));
          if($texto != ""){
          	$pdf->addpage("L");
          	$pdf->setfont('arial','', 8);
          	$pdf->cell(60,$alt, "",0,0,"L");
          	$pdf->multicell(210,$alt, $oDadosAcao1->base, 0,"L");
          }
			  	$pdf->multicell(210,$alt, $oDadosAcao1->base, 0,"L");


	  		//$pdf->Ln();
	  	  validaNovaPagina($pdf, 35);
	  	  $pdf->setfont('arial','B', 8);
	  	  //$pdf->Cell(62,$alt,"A��o"	  							,"TBR",0,"C",1);
	      $pdf->Cell(88,$alt,"Unidade"		 					,"TR" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Classifica��o"					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Produto"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Unidade Medida"  					,"TRL" ,0,"C",1);
	      $pdf->Cell(26,$alt,"Ano"    					,"TRBL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Metas"    					,"TRL" ,0,"C",1);
	      $pdf->Cell(40,$alt,"Valor R$"    					,"TL" ,1,"C",1);
	      $iPosYAntes  = $pdf->GetY();

	      $pdf->setfont('arial','', 8);
	      $iAltDescr = ($alt);

	  	  $pdf->setfont('arial','', 8);
	  	  //$pdf->multicell(62,$iAltDescr,str_pad(substr($aDadosAcoes["iAcao"]."-".$aDadosAcoes['sDescricao'],0,90),90," ",STR_PAD_RIGHT),"TR","L",0);
	  	  $pdf->setfont('arial','', 8);
	  	  $pdf->SetXY(10,$iPosYAntes);
	  	  $pdf->multicell(88,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidade'],0,40),40," ",STR_PAD_RIGHT),"TR","L",0);
	  	  $pdf->SetXY(98,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sTipo'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(124,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sProduto'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	  	  $pdf->SetXY(150,$iPosYAntes);
	  	  $pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidadeMed'],0,40),40," ",STR_PAD_RIGHT),"TRL","L",0);
	   	  $nTotalLivre     = 0;
	  	  $nTotalVinculado = 0;
	  	  $nTotalMetas     = 0;
	  	  $nTotalGeral     = 0;
	  	  $iLinhasExerc	 = 0;
	  	  $pdf->SetY($iPosYAntes);
	      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ){

	  	    $nTotalRecurso    = $aDadosExerc['nValor'];
	   	    $nTotalGeral	 += $nTotalRecurso;
	   	    $nTotalMetas     += $aDadosExerc['nQuantFisica'];
	   	    $tipo = "f";
	        if ($iModelo == 2) {

			 	    if ($iExercicio != $oGet->iAno) {
			 	      $iExercicio = "";
			 	      $tipo = "";
			 	    }
			 	  }
	  	    $pdf->SetX(176);
	  	    $pdf->Cell(26,$alt,$iExercicio  			     			,"BR" ,0,"C",0);
	  	    $pdf->Cell(40,$alt,$aDadosExerc['nQuantFisica']	,"TBR",0,"R",0);

	  	    $pdf->Cell(40,$alt,db_formatar($aDadosExerc['nValor'],$tipo)	,"TBL",1,"R",0);

	  	    $iLinhasExerc++;

	      }
	  	  $pdf->SetY($iPosYAntes);
	  	  for ($iSeq=0; $iSeq < 4; $iSeq++ ) {

	  	    $sBorda = "R";
	  	    if ($iSeq == 3) {
	  	      $sBorda = "RB";
	  	    }
	  	    //$pdf->Cell(62,$alt,"","{$sBorda}" ,0);
	  	    $pdf->Cell(88,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	  	    $pdf->Cell(26,$alt,"",$sBorda ,0);
	        $pdf->Cell(26,$alt,"",$sBorda ,1);
	      }
	  	  $pdf->setfont('arial','B', 8);
	  	  $pdf->Cell(192,$alt,"Total da a��o para os exerc�cios" ,"TBR",0,"R",0);
	  	  $pdf->setfont('arial','' , 8);
	  	  $pdf->Cell(40 ,$alt, $nTotalMetas ,"TBR",0,"R",0);
	  	  $pdf->Cell(40 ,$alt,db_formatar($nTotalGeral,"f")	   ,"TBL",1,"R",0);
	      $pdf->setfont('arial','B', 8);
	      //$pdf->addpage("L");
	      //$pdf->ln();
	  	}
	  }
	  $pdf->ln();
	  /**
	   * Escrevemos o Totalizador do Programa
	   */
	  validaNovaPagina($pdf, 40);
    $pdf->Cell(193,$alt,""          ,"TBR",0,"C",0);
    $pdf->Cell(40 ,$alt,"Ano"       ,"TBR",0,"C",0);
    $pdf->Cell(40 ,$alt,"Valor"       ,"TBR",1,"C",0);

    $iY = $pdf->getY();
    $pdf->Cell(193,$alt*3," TOTAL PROGRAMA","TBR",1,"C",0);
    $pdf->setY($iY);

    $nTotalGeral  = 0;
    $nTotalValor = 0;
    foreach ( $aTotalAcoes[$oEstimativa->iPrograma] as $iExercicio => $aDados ) {

      $nTotalValor     = $aDados;
      $nTotalGeral     += $nTotalValor;

      $pdf->SetX(203);
      $pdf->Cell(40,$alt,$iExercicio                           ,"BR" ,0,"C",0);
      $pdf->Cell(40,$alt,db_formatar($nTotalValor,"f")        ,"TB" ,1,"R",0);

    }
    $pdf->Cell(193,$alt*-1,"Total dos Exerc�cios","TBR",0,"C",0);
    $pdf->Cell(40,$alt,""                               ,"BR" ,0,"C",0);
    $pdf->Cell(40,$alt,db_formatar($nTotalGeral,"f")  ,"TB" ,1,"R",0);
    $iPrograma = $oEstimativa->iPrograma;
    $pdf->ln();
	}

}
/**
 * Totalizador Geral
 */
$pdf->ln();
validaNovaPagina($pdf, 35);
$pdf->Cell(193,$alt,""          ,"TBR",0,"C",0);
$pdf->Cell(40 ,$alt,"Ano"       ,"TBR",0,"C",0);
$pdf->Cell(40 ,$alt,"Valor"       ,"TBR",1,"C",0);

$iY = $pdf->getY();
$pdf->Cell(193,$alt*3," TOTAL GERAL","TBR",1,"C",0);
$pdf->setY($iY);
$nTotalGeral = 0;
foreach ( $aTotalAcoes as $oProjativ) {

  foreach ( $oProjativ as $iExercicio => $aDados ) {

    $nTotalValor     = $aDados;
    $nTotalGeral     += $nTotalValor;
    if (isset($aTotalFinal[$iExercicio])) {
      $aTotalFinal[$iExercicio] += $nTotalValor;
    } else {
      $aTotalFinal[$iExercicio]  = $nTotalValor;
    }
  }
}
foreach ( $aTotalFinal as $iExercicio => $nTotalValor) {

  $pdf->SetX(203);
  $pdf->Cell(40,$alt,$iExercicio                           ,"BR" ,0,"C",0);
  $pdf->Cell(40,$alt,db_formatar($nTotalValor,"f")        ,"TB" ,1,"R",0);

}
$pdf->Cell(193,$alt*-1,"Total dos Exerc�cios","TBR",0,"C",0);
$pdf->Cell(40,$alt,""                               ,"BR" ,0,"C",0);
$pdf->Cell(40,$alt,db_formatar($nTotalGeral,"f")  ,"TB" ,1,"R",0);
$pdf->Output();


function validaNovaPagina($pdf, $iAltura){

 if ($pdf->getY() > $pdf->h - $iAltura){

    $alt = 4;
    $pdf->addpage("L");

  }

}

?>