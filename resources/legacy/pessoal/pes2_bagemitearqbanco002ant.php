<?
include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcaixa_ze.php");
include("libs/db_libgertxtfolha.php");
include("classes/db_folha_classe.php");
include("classes/db_pensao_classe.php");
include("classes/db_rharqbanco_classe.php");
include("classes/db_orctiporec_classe.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$cllayouts_bb  = new cl_layouts_bb;
$cllayout_BBBS = new cl_layout_BBBS;
$clfolha       = new cl_folha;
$clpensao      = new cl_pensao;
$clrharqbanco  = new cl_rharqbanco;
$clorctiporec  = new cl_orctiporec;
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r38_liq");
$clrotulo->label("r38_banco");
$clrotulo->label("r38_agenc");
$clrotulo->label("r38_conta");
$clrotulo->label("r70_descr");

$sqlerro = false;

$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultinst = pg_query($sqlinst);
db_fieldsmemory($resultinst,0);

//die($clrharqbanco->sql_query($rh34_codarq));    
$result_arqbanco=$clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));    
if($clrharqbanco->numrows>0){

  db_fieldsmemory($result_arqbanco,0);

  $acodigodobanco = $rh34_codban;
  $atipoinscricao = "2";
  $inscricaoprefa = $cgc;
  $aconveniobanco = $rh34_convenio;

  $dvagenciabanco = "0";
  $dvcontadobanco = "0";
  $dvcontaagencia = "0";

  $agenciadobanco = $rh34_agencia;
  $dacontadobanco = $rh34_conta;

  $dvdacontabanco = "0";
  if(trim($rh34_dvconta) != ""){
    $digitos = strlen($rh34_dvconta);
    $dvdacontabanco = $rh34_dvconta[0];
  }
  $dacontadobanco .= $dvdacontabanco;

  if(trim($rh34_dvagencia)!=""){
    $dvagenciabanco = $rh34_dvagencia[0];
  }

  if(trim($rh34_dvconta)!=""){
    $dvcontadobanco = $rh34_dvconta[0];
    $digitos        = strlen($rh34_dvconta);
    if($digitos>1){
      $dvcontaagencia = $rh34_dvconta[1];
    }
  }

  $nomeprefeitura = $nomeinst;
  $descricaobanco = $db90_descr;

  if(isset($datagera) && $datagera!=""){
    $datag = explode('-',$datagera);
    $datag_dia=$datag[2];
    $datag_mes=$datag[1];
    $datag_ano=$datag[0];
  }

  if(isset($datadeposit) && $datadeposit!=""){
    $datad = explode('-',$datadeposit);
    $datad_dia = $datad[2];
    $datad_mes = $datad[1];
    $datad_ano = $datad[0];
  }

  $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
  $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;
  $paramnome = $datag_mes.$datag_ano."_".date("H").date("i");

  $ahoradegeracao = date("H").date("i").date("s");
  $sequenciaarqui = $rh34_sequencial;
  // db_msgbox($sequenciaarqui);
  $versaodoarquiv = "030";

}else{
  $sqlerro = true;
  $erro_msg = "Arquivo n�o encontrado";
}

if(!isset($rh34_where) || (isset($rh34_where) && trim($rh34_where) == "")){
  $rh34_wherefolha = "";
  $rh34_wherepensa = ""; 
}else{
  $rh34_wherefolha = $rh34_where." and ";
  $rh34_wherepensa = $rh34_where." and "; 
}

$rh34_wherefolha.= " r38_banco = '$rh34_codban' ";
$rh34_wherepensa.= " r52_codbco = '$rh34_codban' and r52_anousu = ".db_anofolha()." and r52_mesusu = ".db_mesfolha();
if($tiparq == 0){
  $titrelatorio = "Todos os funcion�rios";
  $titarquivo   = "pagtofuncionarios";
}else if($tiparq == 1){
  $rh34_wherefolha.= "
                 and substr(r70_estrut,2,2) = '08' 
                 and r38_regime = 1 
                 and r38_vincul = 'A'
                ";
  $titrelatorio = "SMED";
  $titarquivo   = "blvsmed";
}else if($tiparq == 2){
  $rh34_wherefolha.= "
                 and substr(r70_estrut,2,2) <> '22' 
                 and r38_vincul <> 'A'
                "; 
  $titrelatorio = "Inativos e Pensionistas - Bage";
  $titarquivo   = "blvinat";
}else if($tiparq == 3){
  $rh34_wherefolha.= "
                 and substr(r70_estrut,2,2) <> '22' 
                 and r38_vincul = 'A' 
                 and (
                      (
                       substr(r70_estrut,2,2) = '08' and r38_regime <> 1
                      ) 
                      or 
                      (
                       substr(r70_estrut,2,2) in ('01','02','03','04','05','06','07','09','10','11','12','13','14','15','16','17','18','19','20','21','99')
                      )
                     )";
  $titrelatorio = "Demais Funcionarios da Prefeitura - Bage";
  $titarquivo   = "blvfuncio";
}else if($tiparq == 4){
  $rh34_wherefolha.= "
                 and substr(r70_estrut,2,2) = '22'
                "; 
  $titrelatorio = "Funpas - Bage";
  $titarquivo   = "blvfunpas";
  $inscricaoprefa = "04025494000110";
  $cgc = "04025494000110";
  $nomeprefeitura = "FUNPAS";

}

if($sqlerro == false){

  if($tiparq < 5){
    $sql = $clfolha->sql_query_gerarqbag(null,"folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia,
                                               r70_descr,
                                               length(trim(z01_cgccpf)) as tam,
                                               r38_liq as valorori",
                                              "r38_banco,r38_nome",
                                              "$rh34_wherefolha");
    $result  = $clfolha->sql_record($sql);
    $numrows = $clfolha->numrows;
  }else{
    if($tiparq == 5){
      $rh34_wherepensa .= " and substr(r70_estrut,2,2) = '22' ";
      $titarquivo = "pensaojudicialfunpas";
      $titrelatorio = "PENS�O JUDICIAL FUNPAS";
      $inscricaoprefa = "04025494000110";
      $cgc = "04025494000110";
      $nomeprefeitura = "FUNPAS";
    }else{
      $rh34_wherepensa .= " and substr(r70_estrut,2,2) <> '22' ";
      $titarquivo = "pensaojudicialprefeitura";
      $titrelatorio = "PENS�O JUDICIAL PREFEITURA";
    }
    if($qfolha == 1){
      $campovalor = " r52_valor ";
      $rh34_wherepensa .= " and r52_valor > 0 ";
    }else if($qfolha == 2){
      $campovalor = " r52_valcom ";
      $rh34_wherepensa .= " and r52_valcom > 0 ";
    }else if($qfolha == 3){
      $campovalor = " r52_val13 ";
      $rh34_wherepensa .= " and r52_val13 > 0 ";
    }
    $sql = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as r38_liq, length(trim(r52_codage)) as qtddigitosagencia,
                                               r52_numcgm as r38_regist,
                                               r52_codbco as r38_banco,
 					                                     r52_conta as r38_conta,
					                                     r52_codage as r38_agenc,
					                                     cgm.*,
                                               r70_descr,
                                               length(trim(z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco,z01_nome",
                                              "$rh34_wherepensa and $campovalor > 0");
		//die($sql);
    $result  = $clpensao->sql_record($sql);
    $numrows = $clpensao->numrows;
  }
  if($numrows > 0){
    $nomearquivo_impressao = "/tmp/".$titarquivo.".pdf";
    $nomearquivo = $titarquivo.".txt";
    $cllayouts_bb->nomearq  = "tmp/$nomearquivo";
    $cllayout_BBBS->nomearq = "tmp/$nomearquivo";
    $cllayout_BBBS->BSheaderA_001_003 = $acodigodobanco;
    $cllayout_BBBS->BSheaderA_019_032 = $inscricaoprefa;
    $cllayout_BBBS->BSheaderA_033_037 = $aconveniobanco;
    $cllayout_BBBS->BSheaderA_053_057 = $agenciadobanco;
    $cllayout_BBBS->BSheaderA_062_071 = $dacontadobanco;
    $cllayout_BBBS->BSheaderA_073_102 = $nomeprefeitura;
    $cllayout_BBBS->BSheaderA_103_132 = $descricaobanco;
    $cllayout_BBBS->BSheaderA_144_151 = $adatadegeracao;
    $cllayout_BBBS->BSheaderA_152_157 = $ahoradegeracao;
    $cllayout_BBBS->BSheaderA_158_163 = $sequenciaarqui;
    $cllayout_BBBS->BSheaderA_192_211 = $sequenciaarqui;
    $cllayout_BBBS->geraHEADERArqBS();

    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt = 4;

    $head3 = $titrelatorio;
    $head5 = "SEQUENCIAL DO ARQUIVO  :  ".$sequenciaarqui;
    $head6 = "GERA��O  :  ".db_formatar($datagera,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';
    $head7 = "PAGAMENTO:  ".db_formatar($datadeposit,"d");
    $head8 = 'BANCO : '.$rh34_codban.' - '.$db90_descr;

    $xvalor    = 0;
    $xvaltotal = 0;
    $xbanco    = "";
    $ant_codgera = "";
    $total_geral = 0;

    $xtotal_func = 0;
    $xtotal = 0;
    $total_func = 0;

    $soma_dep = 0;
    $soma_doc = 0;
    $soma_ted = 0;
    $tota_dep = 0;
    $tota_doc = 0;
    $tota_ted = 0;

    $seq_header   = 0;
    $registro     = 1;
    $valor_header = 0;

    $bancoanterior= "";

    $entrar = true;
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);

      if($entrar == true || $pdf->gety() > $pdf->h - 30){
        $pdf->addpage("L");
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt,$RLrh01_regist,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
        $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
        $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
        $pdf->cell(20,$alt,$RLr38_liq,1,0,"C",1);
        $pdf->cell(15,$alt,"Cod.Pgto.",1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_banco,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(25,$alt,$RLr38_conta,1,1,"C",1);
        $entrar = false;
      }

      if($rh34_codban==$r38_banco){
        $codpgto   = "DEP";
        $soma_dep += $r38_liq;
        $tota_dep += $r38_liq;
      }else{
        if($r38_liq<5000){
          $codpgto   = "DOC";
          $soma_doc += $r38_liq;
          $tota_doc += $r38_liq;
        }else{
          $codpgto   = "TED";
          $soma_ted += $r38_liq;
          $tota_ted += $r38_liq;
        }
      }

      if($bancoanterior != $r38_banco){

        $bancoanterior = $r38_banco;

	if($acodigodobanco == '041'){
	  $tiposerv = "30";
	  $tipopaga = "01";
	}else{
	  $tiposerv = "12";
	  $tipopaga = "03";
	}

        if($seq_header != 0){
          $cllayout_BBBS->BBBStraillerL_001_003 = $acodigodobanco; 
          $cllayout_BBBS->BBBStraillerL_004_007 = $seq_header;
          $cllayout_BBBS->BBBStraillerL_018_023 = $seq_detalhe; 
          $cllayout_BBBS->BBBStraillerL_024_041 = $valor_header;
          $cllayout_BBBS->geraTRAILLERLote();
	  $valor_header = 0;
	  $registro ++;
	}

        $seq_header ++;
	$seq_detalhe = 0;
	$registro ++;

	$cllayout_BBBS->BSheaderL_001_003 = $acodigodobanco;
	$cllayout_BBBS->BSheaderL_004_007 = $seq_header;
	$cllayout_BBBS->BSheaderL_010_011 = $tiposerv;
	$cllayout_BBBS->BSheaderL_012_013 = $tipopaga;
	$cllayout_BBBS->BSheaderL_019_032 = $inscricaoprefa;
	$cllayout_BBBS->BSheaderL_033_037 = $aconveniobanco;
	$cllayout_BBBS->BSheaderL_053_057 = $agenciadobanco;
	$cllayout_BBBS->BSheaderL_062_071 = $dacontadobanco;
	$cllayout_BBBS->BSheaderL_073_102 = $nomeprefeitura;
	$cllayout_BBBS->BSheaderL_143_172 = $ender;
	$cllayout_BBBS->BSheaderL_193_212 = $munic;
	$cllayout_BBBS->BSheaderL_213_220 = $cep;
	$cllayout_BBBS->BSheaderL_221_222 = $uf;
	$cllayout_BBBS->geraHEADERLoteBS();
      }

      $compensacao = "   ";
      if($acodigodobanco == $r38_banco || $r38_liq<5000){
        $compensacao = "010";
      }else{
	if($r38_liq>=5000){
	  $compensacao = "018";
	}
      }


      $agenciapagarT = str_replace('.','',str_replace('-','',$r38_agenc));
      $contasapagarT = str_replace('.','',str_replace('-','',$r38_conta));

      $agenciapagarT+= 0;

      if($qtddigitosagencia == 5){
        $agenciapagarT = substr($agenciapagarT, 0, 4);
      }

      $agenciapagarT = db_formatar($agenciapagarT,'s','0', 5,'e',0);
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $contasapagarT+= 0;
      if($contasapagarT == 0){
	continue;
      }
      $contasapagarT = db_formatar($contasapagarT,'s','0',10,'e',0);

      $agenciapagar = substr($agenciapagarT,0,4);
      $digitoagenci = substr($agenciapagarT,4,1);

      $contasapagar = substr($contasapagarT,0,10);

      $conf = 1;
      if($tam == 14){
        $conf = 2;
      }

      $valor_header += $r38_liq;
      $registro ++;
      $seq_detalhe ++;
      $xtotal_func ++;
      $xvaltotal += $r38_liq;


      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,$r38_regist,1,0,"C",0);
      $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
      $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
      $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
      $pdf->cell(65,$alt,$r70_descr,1,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(15,$alt,$codpgto,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
      $pdf->cell(25,$alt,$r38_conta,1,1,"R",0);


      $cllayout_BBBS->BSregist_001_003 = $acodigodobanco;
      $cllayout_BBBS->BSregist_004_007 = $seq_header;
      $cllayout_BBBS->BSregist_009_013 = $seq_detalhe;
      $cllayout_BBBS->BSregist_018_020 = $compensacao;
      $cllayout_BBBS->BSregist_021_023 = $r38_banco;
      $cllayout_BBBS->BSregist_024_028 = $agenciapagar;
      $cllayout_BBBS->BSregist_030_042 = $contasapagar;
      $cllayout_BBBS->BSregist_044_073 = $z01_nome; 
      $cllayout_BBBS->BSregist_074_088 = $r38_regist;
      $cllayout_BBBS->BSregist_094_101 = $datadedeposito; 
      $cllayout_BBBS->BSregist_120_134 = $r38_liq;
      $cllayout_BBBS->BSregist_203_203 = $conf;
      $cllayout_BBBS->BSregist_204_217 = $z01_cgccpf;
      $cllayout_BBBS->geraREGISTROSBS();

    }

    $registro ++;
    $cllayout_BBBS->BBBStraillerL_001_003 = $acodigodobanco; 
    $cllayout_BBBS->BBBStraillerL_004_007 = $seq_header;
    $cllayout_BBBS->BBBStraillerL_018_023 = $seq_detalhe; 
    $cllayout_BBBS->BBBStraillerL_024_041 = $valor_header;
    $cllayout_BBBS->geraTRAILLERLote();

    $registro ++;
    $cllayout_BBBS->BBBStraillerA_001_003 = $acodigodobanco;
    $cllayout_BBBS->BBBStraillerA_018_023 = $seq_header;
    $cllayout_BBBS->BBBStraillerA_024_029 = $registro;
    $cllayout_BBBS->geraTRAILLERArquivo();

    $cllayout_BBBS->gera();

    $pdf->setfont('arial','b',8);

    $pdf->cell(190,$alt,'Total de funcion�rios',1,0,"C",1);
    $pdf->cell(20,$alt,$xtotal_func,1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->cell(190,$alt,'Total Geral',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
    $pdf->cell(70,$alt,'',1,1,"C",1);

    $pdf->Output($nomearquivo_impressao,false,true);
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}

/*
if($sqlerro == false){


    for($i=0;$i<$numrows;$i++){


          ///// TRAILLER DO LOTE
	      if($seq_header != 0){
            // DEMAIS POSI��ES EST�O FIXAS NA CLASSE
		    $valor_header = 0;
		    $registro += 1;
	      }
          ///// FINAL DO TRAILLER DO LOTE        

	      $seq_header  += 1;
	      $seq_detalhe  = 0;
	      $registro    += 1;

		  $tamanho = strlen($cep);
		  if($tamanho>5){
		    $com = db_formatar(substr($cep,5,$tamanho),'s',' ',3,'d',0);
	  	    $cep = substr($cep,0,5);
  		  }

          ///// HEADER DO LOTE
          // DEMAIS POSI��ES EST�O FIXAS NA CLASSE
		  $cllayout_BBBS->BBheaderL_001_003 = $acodigodobanco;
		  $cllayout_BBBS->BBheaderL_004_007 = $seq_header;
		  $cllayout_BBBS->BBheaderL_010_011 = $tiposerv;
		  $cllayout_BBBS->BBheaderL_012_013 = $tipopaga;
		  $cllayout_BBBS->BBheaderL_019_032 = $inscricaoprefa;
		  $cllayout_BBBS->BBheaderL_033_052 = $aconveniobanco;
		  $cllayout_BBBS->BBheaderL_053_057 = $agenc;
		  $cllayout_BBBS->BBheaderL_058_058 = $digia[0];
		  $cllayout_BBBS->BBheaderL_059_070 = $conta;
		  $cllayout_BBBS->BBheaderL_071_071 = $digic[0];
		  $cllayout_BBBS->BBheaderL_073_102 = $nomeprefeitura;
		  $cllayout_BBBS->BBheaderL_143_172 = $ender;
		  $cllayout_BBBS->BBheaderL_173_177 = $numero;
		  $cllayout_BBBS->BBheaderL_193_212 = $munic;	      
		  $cllayout_BBBS->BBheaderL_213_217 = $cep;
		  $cllayout_BBBS->BBheaderL_218_220 = $com;
		  $cllayout_BBBS->BBheaderL_221_222 = $uf;
		  $cllayout_BBBS->geraHEADERLoteBB();
          ///// FINAL DO HEADER DO LOTE


	    $seq_detalhe += 1;
	    $tot_valor    = 0;
	    $numero_lote  = 1;
	    $registro += 1;

	    if($tam == 14){
	      $conf   = "2";
	      $cgccpf = $z01_cgccpf;
	    }elseif($tam == 11){
	      $conf   = "1";
	      $cgccpf = db_formatar($z01_cgccpf,'s','0',14,'e',0);
	    }else{
	      $conf   = "3";
	      $cgccpf = "";
	    }

	    $compensacao     = "   ";

	    if($r38_banco == $acodigodobanco || $valorori<5000){
	      if($acodigodobanco=="001"){
            $compensacao = "700";
	      }else{
		    $compensacao = "010";
	      }
	    }else{
	      if($valorori>=5000){
		    $compensacao = "018";
	      }
	    }

        $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_agenc)),'s','0', 5,'e',0);
        $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$r38_conta)),'s','0',13,'e',0);

        $agenciapagar = substr($agenciapagarT,0,4);
        $digitoagenci = substr($agenciapagarT,4,1);

        $contasapagar = substr($contasapagarT,0,12);
        $digitocontas = substr($contasapagarT,12,1);

        $digitoconage = "0";

	    $tamanhoconta =  strlen($contasapagar);
	    if($tamanhoconta>11){
	      $contasapagar = substr($contasapagar,($tamanhoconta-11));
	    }

        if($r38_banco == $acodigodobanco){
          $compensacao = str_repeat('0',3);
        }

        // REGISTROS SEGMENTO A
        $cllayout_BBBS->BBregistA_001_003 = $acodigodobanco;
        $cllayout_BBBS->BBregistA_004_007 = $seq_header;
        $cllayout_BBBS->BBregistA_009_013 = $seq_detalhe;
        $cllayout_BBBS->BBregistA_018_020 = $compensacao;
        $cllayout_BBBS->BBregistA_021_023 = $r38_banco;
        $cllayout_BBBS->BBregistA_024_028 = $agenciapagar;
        $cllayout_BBBS->BBregistA_029_029 = $digitoagenci; 
	    $cllayout_BBBS->BBregistA_030_041 = $contasapagar;
	    $cllayout_BBBS->BBregistA_042_042 = $digitocontas; 
	    $cllayout_BBBS->BBregistA_043_043 = $digitoconage;
	    $cllayout_BBBS->BBregistA_044_073 = $z01_nome;
	    $cllayout_BBBS->BBregistA_074_093 = $r38_regist;
	    $cllayout_BBBS->BBregistA_094_101 = $datadedeposito;
	    $cllayout_BBBS->BBregistA_120_134 = $r38_liq;

        $seq_detalhe += 1;

        $z01_cep = str_replace(".","",$z01_cep);
        $z01_cep = str_replace("-","",$z01_cep);
        // REGISTROS SEGMENTO B
	    $cllayout_BBBS->BBregistB_001_003 = $acodigodobanco;
	    $cllayout_BBBS->BBregistB_004_007 = $seq_header;
	    $cllayout_BBBS->BBregistB_009_013 = $seq_detalhe;
	    $cllayout_BBBS->BBregistB_018_018 = $conf;
	    $cllayout_BBBS->BBregistB_019_032 = $cgccpf;
        $cllayout_BBBS->BBregistB_033_062 = $z01_ender;
        $cllayout_BBBS->BBregistB_063_067 = $z01_numero;
        $cllayout_BBBS->BBregistB_068_082 = $z01_compl;
        $cllayout_BBBS->BBregistB_083_097 = $z01_bairro;
        $cllayout_BBBS->BBregistB_098_117 = $z01_munic;
        $cllayout_BBBS->BBregistB_118_122 = $z01_cep;
        $cllayout_BBBS->BBregistB_123_125 = $z01_cep;
        $cllayout_BBBS->BBregistB_126_127 = $z01_uf;
	    $cllayout_BBBS->BBregistB_128_135 = $datadedeposito;
	    $cllayout_BBBS->BBregistB_136_150 = $r38_liq;
        $cllayout_BBBS->geraREGISTROSBB();
	    // FINAL REGISTROS

        $registro += 1;
        $valor_header += $r38_liq;

        ///// INICIA IMPRESS�O DO CORPO DO RELAT�RIO
        $pdf->setfont('arial','b',8);
        $entrar = false;

        if($ant_codgera!=$rh25_recurso || $entrar == true || $pdf->gety() > $pdf->h - 30){
          if($i !=0 && $ant_codgera!=$rh25_recurso){
            $pdf->cell(140,$alt,'Total de funcion�rios deste recurso',1,0,"C",1);
            $pdf->cell(20,$alt,$xtotal_func,1,0,"R",1);
            $pdf->cell(65,$alt,'',1,1,"C",1);

            $pdf->cell(140,$alt,'DEP',1,0,"C",1);
            $pdf->cell(20,$alt,db_formatar($soma_dep,'f'),1,0,"R",1);
            $pdf->cell(65,$alt,'',1,1,"C",1);

            $pdf->cell(140,$alt,'DOC',1,0,"C",1);
            $pdf->cell(20,$alt,db_formatar($soma_doc,'f'),1,0,"R",1);
            $pdf->cell(65,$alt,'',1,1,"C",1);

            $pdf->cell(140,$alt,'TED',1,0,"C",1);
            $pdf->cell(20,$alt,db_formatar($soma_ted,'f'),1,0,"R",1);
            $pdf->cell(65,$alt,'',1,1,"C",1);

            $pdf->cell(140,$alt,'Total Banco',1,0,"C",1);
            $pdf->cell(20,$alt,db_formatar($xtotal,'f'),1,0,"R",1);
            $pdf->cell(65,$alt,'',1,1,"C",1);
            $soma_dep = 0;
            $soma_doc = 0;
            $soma_ted = 0;
            $xtotal_func = 0;
          	$xtotal = 0;
            $pdf->ln(3);

          }

          // $pdf->cell(225,0.1,"","T",1,"L",0);
   
          $pdf->cell(20,$alt,"RECURSO",1,0,"C",1);
          $pdf->cell(205,$alt,"DESCRI��O",1,1,"C",1);

          $pdf->cell(20,$alt,$RLrh01_regist,1,0,"C",0);
          $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",0);
          $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",0);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",0);
          $pdf->cell(20,$alt,$RLr38_liq,1,0,"C",0);
          $pdf->cell(15,$alt,"Cod.Pgto.",1,0,"C",0);
          $pdf->cell(15,$alt,$RLr38_banco,1,0,"C",0);
          $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",0);
          $pdf->cell(20,$alt,$RLr38_conta,1,1,"C",0);
          $entrar = true;
          $pdf->ln(3);

          $result_descricao_recurso = $clorctiporec->sql_record($clorctiporec->sql_query_file($rh25_recurso,"o15_descr as descricao_recurso"));
          if($clorctiporec->numrows > 0){
          	db_fieldsmemory($result_descricao_recurso,0);
          }

          $pdf->cell(20,$alt,$rh25_recurso,1,0,"C",1);
          $pdf->cell(205,$alt,$descricao_recurso." - ".$descricao." (".$codconta.") ",1,1,"L",1);
          $ant_codgera=$rh25_recurso;
        }

        if($banco==$r38_banco){
          $codpgto   = "DEP";
          $soma_dep += $r38_liq;
          $tota_dep += $r38_liq;
        }else{
          if($r38_liq<5000){
            $codpgto   = "DOC";
            $soma_doc += $r38_liq;
            $tota_doc += $r38_liq;
          }else{
            $codpgto   = "TED";
            $soma_ted += $r38_liq;
            $tota_ted += $r38_liq;
          }
        }

        $pdf->setfont('arial','',7);
        $pdf->cell(20,$alt,$r38_regist,1,0,"C",0);
        $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
        $pdf->cell(80,$alt,$z01_nome,1,0,"L",0);
        $pdf->cell(20,$alt,$z01_cgccpf,1,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
        $pdf->cell(15,$alt,$codpgto,1,0,"C",0);
        $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
        $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
        $pdf->cell(20,$alt,$r38_conta,1,1,"R",0);
        $total++;
        $xtotal    += $r38_liq;
        $xvaltotal += $r38_liq;

        $xtotal_func++;
        $total_func++;

      }

      $pdf->setfont('arial','b',8);

      $pdf->cell(140,$alt,'Total de funcion�rios deste recurso',1,0,"C",1);
      $pdf->cell(20,$alt,$xtotal_func,1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);

      $pdf->cell(140,$alt,'DEP',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($soma_dep,'f'),1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);

      $pdf->cell(140,$alt,'DOC',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($soma_doc,'f'),1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);

      $pdf->cell(140,$alt,'TED',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($soma_ted,'f'),1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);

      $pdf->cell(140,$alt,'Total Banco',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($xtotal,'f'),1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);

      $pdf->ln(2);

      $pdf->cell(140,$alt,'Total de funcion�rios',1,0,"C",1);
      $pdf->cell(20,$alt,$total_func,1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);

      $pdf->cell(140,$alt,'Total Geral',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
      $pdf->cell(65,$alt,'',1,1,"C",1);


      ///// TRAILLER DO LOTE
      // DEMAIS POSI��ES EST�O FIXAS NA CLASSE
      $cllayout_BBBS->BBBStraillerL_001_003 = $acodigodobanco; 
	  $cllayout_BBBS->BBBStraillerL_004_007 = $seq_header;
      $cllayout_BBBS->BBBStraillerL_018_023 = $seq_detalhe; 
	  $cllayout_BBBS->BBBStraillerL_024_041 = $valor_header;
	  $cllayout_BBBS->geraTRAILLERLote();
     ///// FINAL DO TRAILLER DO LOTE

	  $valor_header = 0;
	  $registro += 1;

	  ////  TRAILLER DO ARQUIVO
	  $registro += 1;
	  $cllayout_BBBS->BBBStraillerA_001_003 = $acodigodobanco;
	  $cllayout_BBBS->BBBStraillerA_018_023 = $seq_header;
	  $cllayout_BBBS->BBBStraillerA_024_029 = $registro;
	  $cllayout_BBBS->geraTRAILLERArquivo();
      ///// FINAL DO TRAILLER DO ARQUIVO

      $cllayout_BBBS->gera();

  if($sqlerro == false){   
          //db_msgbox("//// LAYOUT BANCO DO BRASIL");
          //----------------------------------------------------------------------------------
	      ///// COME�O DO HEADER 
	      ///// rharqbanco
	      $cllayouts_bb->cabec01 = '0' ; 					// fixo
	      $cllayouts_bb->cabec02 = '1' ; 					// fixo
	      $cllayouts_bb->cabec03 = '       ' ; 				// branco
	      $cllayouts_bb->cabec04 = '03' ; 					// fixo
	      $cllayouts_bb->cabec05 = ' ' ; 					// branco
	      $cllayouts_bb->cabec06 = '00000' ;  				// fixo
	      $cllayouts_bb->cabec07 = '         ';   				// brancos
	      $cllayouts_bb->cabec08 = db_formatar(@$rh34_agencia,'s','0',4,'e',0); // numero da agencia
	      $cllayouts_bb->cabec09 = $rh34_dvagencia;				// digito da agencia
	      $cllayouts_bb->cabec10 = db_formatar(@$rh34_conta,'s','0',9,'e',0); // conta
	      $cllayouts_bb->cabec11 = $rh34_dvconta;			  	// digito da conta
	      $cllayouts_bb->cabec12 = '     ' ; 				// brancos
	      $cllayouts_bb->cabec13 = substr(strtoupper($nomeinst),0,30) ; 	// nome da empresa
	      $cllayouts_bb->cabec14 = '001'; 					// codigo do banco
	      $cllayouts_bb->cabec15 = $rh34_convenio ; 				// numero do converio
	      $cllayouts_bb->cabec16 = '   ' ; 					// brancos
	      $cllayouts_bb->cabec17 = db_formatar(@$e90_codgera,'s',' ',10,'e',0);	// campo livre
	      $cllayouts_bb->cabec18 = '00' ;					// tipo de retorno magnetico
	      $cllayouts_bb->cabec19 = '000' ;					// para uso do banco
	      $cllayouts_bb->cabec20 = str_repeat(' ',46) ;			// brancos
	      $cllayouts_bb->cabec21 = str_repeat(' ',17) ;			// exclusivo do sistema
	      $cllayouts_bb->cabec22 = 'NOVO';					// "novo" para possibilitar o arquivo de retorno
	      $cllayouts_bb->cabec23 = str_repeat(' ',15) ;			// brancos
	      $cllayouts_bb->cabec24 = str_repeat(' ',9) ;			// brancoi
	      $cllayouts_bb->cabec25 = '000001' ;				// sequencial - 000001
	      $cllayouts_bb->gera_cabecalho();
	      //// FIM DO HEADER
          //----------------------------------------------------------------------------------

	      for($i=0; $i<$numrows; $i++ ){
		    db_fieldsmemory($result,$i); 
	        $tam = strlen($z01_cgccpf);
	        ////GERA DETALHE
            if($tam == 14){
		      $conf = 4;
		      $cgccpf = substr($z01_cgccpf,0,12);
		      $dig_cgccpf = substr($z01_cgccpf,12,2);
		    }elseif($tam == 11){
		      if($z01_cgccpf == '00000000000'){
                $conf = 1;
		        $cgccpf = '000000000000';
		        $dig_cgccpf = '00';
		      }else{
		        $conf = 2;
                $cgccpf = str_pad(substr($z01_cgccpf,0,9),12,"0",STR_PAD_LEFT);
		        $dig_cgccpf = substr($z01_cgccpf,9,2);
		      }
            }else{
              $conf = 1;
		      $cgccpf = '000000000000';
		      $dig_cgccpf = '00';
            }

		    $agencia_fav = db_formatar( str_replace('.','',str_replace('-','',$r38_agenc)),'s','0',4,'e',0);
		    $conta_fav   = db_formatar( str_replace('.','',str_replace('-','',$r38_conta)),'s','0',13,'e',0);

            $cllayouts_bb->corp01 = '1' ;						// fixo 
  	        $cllayouts_bb->corp02 = ' ' ;						// brancos 
	 	    $cllayouts_bb->corp03 = 0 ;					// indicador de conferencia
		    $cllayouts_bb->corp04 = '          ';					// cgc, cnpf ou zeros
		    $cllayouts_bb->corp05 = '  ' ;					// digitos do cpf ou cnpf
		    $cllayouts_bb->corp06 = '0000' ;					// sequencial 
		    $cllayouts_bb->corp07 = '0' ;						// sequencial 
		    $cllayouts_bb->corp08 = '000000000' ;					// sequencial 
		    $cllayouts_bb->corp09 =  db_formatar($r38_regist,'s','0',8,'e',0) ;						// sequencial 
		    $cllayouts_bb->corp10 = str_repeat(' ',5) ;	// livre  // n�o precisa
		    $cllayouts_bb->corp11 = str_repeat(' ',5) ;				// sequencial 
		    $cllayouts_bb->corp12 = str_repeat(' ',9) ;				// campo livre
		    $cllayouts_bb->corp13 = '000' ;					// camara de compensa��o
		    $cllayouts_bb->corp14 = ($r38_banco = 001?'   ':$r38_banco) ;	// codigo do banco r38_banco
		    $cllayouts_bb->corp15 = substr($agencia_fav,0,4) ;			// agencia do favorecido r38_agenc
		    $cllayouts_bb->corp16 = ($r38_banco = 001?substr($agencia_fav,4,1):db_CalculaDV(substr($agencia_fav,0,4))) ;  // digito da agencia 
		    $cllayouts_bb->corp17 = substr($conta_fav,0,12) ;	// conta do favorecido 
		    $cllayouts_bb->corp18 = ($r38_banco = 001?substr($conta_fav,12,1):db_CalculaDV(substr($conta_fav,0,4))) ;   // digito da conta
		    $cllayouts_bb->corp19 = '  ' ;					// brancos 
		    $cllayouts_bb->corp20 = db_formatar($z01_nome,'s',' ',40,'d',0);	// nome do favorecido 
		    $cllayouts_bb->corp21 = $deposito_dia.$deposito_mes.substr($deposito_ano,2,2);	 				// data do dep�sito(DDMMAA) 
		    $cllayouts_bb->corp22 = db_formatar(str_replace(',','',str_replace('.','',$r38_liq)),'s','0',13,'e',0);	// valor r38_liq
		    $cllayouts_bb->corp23 = '001' ;		// codigo do servi�o 
		    $cllayouts_bb->corp24 = str_repeat(' ',40) ;				// livre 
		    $cllayouts_bb->corp25 = str_repeat(' ',10) ;				// livre 
		    $cllayouts_bb->corp26 = str_pad($registro,6,"0",STR_PAD_LEFT);	// sequencial
		    $cllayouts_bb->gera_corpo();
		    $registro += 1;
            ////FINAL DETALHE
      }	
      //trailler.;...  RODAP�
      $cllayouts_bb->rodap01 = '9';  			// fixo
      $cllayouts_bb->rodap02 = str_repeat(' ',193) ;	// brancos
      $cllayouts_bb->rodap03 = str_pad($registro,6,"0",STR_PAD_LEFT);        // numero de registros
      $cllayouts_bb->gera_trailer();
      echo $cllayouts_bb->texto;
      $cllayouts_bb->gera();
    }else if($sqlerro==false){
     $sqlerro  = true;
	  $erro_msg = "O tipo selecionado, n�o possui layout cadastrado.";
    }
//////////////////////////////////
    }else{
      $sqlerro  = true;
      $erro_msg = "Sem dados para gerar arquivo";
    }
  }

*/

  if($sqlerro == false){
    echo "
    <script>
      parent.js_detectaarquivo('tmp/$nomearquivo','$nomearquivo_impressao');
    </script>
    ";
  }else{
    echo "
    <script>
      parent.js_erro('$erro_msg');
    </script>
    ";
  }

  db_fim_transacao($sql);

?>
