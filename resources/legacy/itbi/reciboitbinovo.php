<?
require("fpdf151/scpdf.php");
require("libs/db_conecta.php");
include("classes/db_itbiavalia_classe.php");
include("classes/db_itbi_classe.php");
include("classes/db_itbimatric_classe.php");
include("classes/db_itbilogin_classe.php");
include("classes/db_itburbano_classe.php");
include("classes/db_itbirural_classe.php");
include("classes/db_itbiruralcaract_classe.php");
include("classes/db_itbinumpre_classe.php");
include("classes/db_itbinome_classe.php");
include("classes/db_itbicgm_classe.php");
include("classes/db_itbiconstr_classe.php");
include("classes/db_numpref_classe.php");
include("classes/db_itbiconstrespecie_classe.php");
include("classes/db_itbiconstrtipo_classe.php");
include("classes/db_parreciboitbi_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
$clitbiavalia = new cl_itbiavalia;
$clitbi = new cl_itbi;
$clitbirural = new cl_itbirural;
$clitbiruralcaract = new cl_itbiruralcaract;
$clitbimatric = new cl_itbimatric;
$clitbilogin = new cl_itbilogin;
$clitburbano = new cl_itburbano;
$clitbinumpre = new cl_itbinumpre;
$clitbinome = new cl_itbinome;
$clitbicgm = new cl_itbicgm;
$clitbiconstr = new cl_itbiconstr;
$clitbiconstrespecie = new cl_itbiconstrespecie;
$clitbiconstrtipo = new cl_itbiconstrtipo;
$clnumpref = new cl_numpref;
$clparreciboitbi = new cl_parreciboitbi;

$result = $clitbiavalia->sql_record($clitbiavalia->sql_query($itbi)); 
db_fieldsmemory($result,0);
$result = $clitbi->sql_record($clitbi->sql_query($itbi)); 
db_fieldsmemory($result,0);
$areaterreno = $it01_areaterreno;
$areatran = $it01_areaedificada;
$result = $clitburbano->sql_record($clitburbano->sql_query($itbi));
if($clitburbano->numrows > 0){
  db_fieldsmemory($result,0);
  $tipo = "urbano";
}
$result = $clitbirural->sql_record($clitbirural->sql_query($itbi)); 
if($clitbirural->numrows > 0){
  db_fieldsmemory($result,0);
  $tipo = "rural";
}
$result = $clitbimatric->sql_record($clitbimatric->sql_query($itbi)); 
db_fieldsmemory($result,0);
$proprietarios = "";
$result = $clitbinome->sql_record($clitbinome->sql_query($itbi,""," it03_nome as nomecomp,it03_cpfcnpj as cgccpfcomprador,it03_endereco as enderecocomprador, it03_numero as numerocomprador, it03_compl as complcomprador, it03_munic as municipiocomprador,it03_uf as ufcomprador,it03_cep as cepcomprador,it03_bairro as bairrocomprador","it03_seq desc")); 
if($clitbinome->numrows  > 0){
  $traco = '';
  $proprietarios .= "\n".'ADQUIRENTES : ';
  $num = pg_numrows($result);
  for ($p = 0;$p < $num;$p++){
    db_fieldsmemory($result,$p);
    $proprietarios .= $traco.trim($nomecomp);
    $traco = ' - ';
  }
}
$result = $clitbicgm->sql_record($clitbicgm->sql_query($itbi,""," z01_nome as nomecomp,z01_cgccpf as cgccpfcomprador,z01_ender as enderecocomprador, z01_numero as numerocomprador, z01_compl as complcomprador, z01_munic as municipiocomprador,z01_uf as ufcomprador,z01_cep as cepcomprador,z01_bairro as bairrocomprador","it02_numcgm desc")); 
if($clitbicgm->numrows  > 0){
  $traco = '';
  if($proprietarios == ""){
    $proprietarios = "\n".'ADQUIRENTES : ';
  }else{
    $proprietarios .= " - ";
  }
  $num = pg_numrows($result);
  for ($p = 0;$p < $num;$p++){
    db_fieldsmemory($result,$p);
    $proprietarios .= $traco.trim($nomecomp);
    $traco = ' - ';
  }
}
if($proprietarios == ""){
  echo "<script>alert('� Guia de ITBI deve ter no m�nimo um Adquirente!')</script>";
  echo "<script>window.close()</script>";
  exit;
}
$resultcons = $clitbiconstr->sql_record($clitbiconstr->sql_query("","*",""," it08_guia = $itbi")); 
if($clitbiconstr->numrows  > 0){
  $num = pg_numrows($resultcons);
  $areatotal = 0;
  $areatrans = 0;
  for ($p = 0;$p < $num;$p++){
    db_fieldsmemory($resultcons,$p);
    $areatrans += $it08_areatrans;
    $areatotal += $it08_area;
  }
}
$numpre = $clnumpref->sql_numpre();
$resnumpre = $clitbinumpre->sql_record($clitbinumpre->sql_query($itbi));
if($clitbinumpre->numrows > 0){
  $clitbinumpre->it15_guia = $itbi;
  $clitbinumpre->it15_numpre = $numpre;
  $clitbinumpre->alterar($itbi,$numpre);
}else{
  $clitbinumpre->it15_guia = $itbi;
  $clitbinumpre->it15_numpre = $numpre;
  $clitbinumpre->incluir($itbi,$numpre);
}
$result1 = pg_exec($conn,"select proprietario.*
                       from proprietario
    				   where j01_matric = ".$it06_matric." limit 1 ");
db_fieldsmemory($result1,0);
$result = $clparreciboitbi->sql_record($clparreciboitbi->sql_query_file()); 
if($clparreciboitbi->numrows > 0){
  db_fieldsmemory($result,0);
}else{
  echo "<script>alert('Par�metros do recibo n�o configurados\\ncontate suporte!')</script>";
  echo "<script>window.close()</script>";
  exit;
}
pg_exec("begin");
$numpre = $clitbinumpre->it15_numpre;
$sql = "insert into recibo values($it17_numcgm,
				 '".date("Y-m-d",db_getsession("DB_datausu"))."',
				  $it17_codigo,
				 707,
				 $it14_valorpaga,
				 '$it14_dtvenc', 
				 $numpre,
				 1,
				 1,
				 0,
				 29,
				 0,
				 0)";
$result = pg_exec($sql);
pg_exec("commit");
$datavencimento = $it14_dtvenc;
$valorpagamento = $it14_valorpaga;
$vlrbar = db_formatar(str_replace('.','',str_pad(number_format($valorpagamento,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
$config = pg_exec("select * from db_config where codigo = ".db_getsession("DB_instit"));
db_fieldsmemory($config,0);
$numpre = db_numpre_sp($numpre,1); 

if ($formvencfebraban == 1) {
  $db_dtvenc = str_replace("-","",$datavencimento);
  $vencbar = $db_dtvenc . '000000';
} elseif ($formvencfebraban == 2) {
  $db_dtvenc = str_replace("-","",$datavencimento);
  $db_dtvenc = substr($db_dtvenc,6,2) . substr($db_dtvenc,4,2) . substr($db_dtvenc,2,2);
  $vencbar = $db_dtvenc . '00000000';
}

$inibar="8" . $segmento . "6";
$resultcod = pg_exec("select fc_febraban('$inibar'||'$vlrbar'||'".$numbanco."'||'".$vencbar."'||'$numpre')");
$fc_febraban = pg_result($resultcod,0,0);

  if ($fc_febraban == "") {
    db_msgbox("Erro ao gerar codigo de barras (3)!");
    exit;
  }

    			
$codigo_barras   = substr($fc_febraban,0,strpos($fc_febraban,','));
$linha_digitavel = substr($fc_febraban,strpos($fc_febraban,',')+1);
$matriz= explode('\.',$j40_refant);
$pdf = new scpdf();
$pdf->Open();
$pdf->settopmargin(5);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(235);
$altura = 3.5;
for ( $i=1;$i < 3;$i++){
$pdf->SetFillColor(235);
   $y = $pdf->gety() - 2;
   $pdf->Image('../dbportal2/imagens/files/'.$logo,10,$y,14);
   $pdf->SetFont('Arial','B',10);
   $pdf->setx(30);
   $pdf->Cell(100,3,$nomeinst,0,1,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Imposto Sobre Transmiss�o de Bens Im�veis (ITBI)',0,0,"L",0);
   $pdf->SetFont('Arial','B',12);
   $pdf->cell(100,3,'Vencimento : '.db_formatar($datavencimento,'d'),0,1,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Guia de Recolhimento N'.chr(176).' SMF/'.db_formatar($itbi,'s','0',5).'/'.db_getsession("DB_anousu"),0,0,"L",0);
   $pdf->cell(100,3,'C�digo de Arrecada��o : '.$numpre,0,1,"L",0);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Tipo de Transmiss�o : '.$it04_descr,0,1,"L",0);
   $pdf->Ln(7);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'',1,0,"C",1);
   $pdf->cell(80,$altura,'Identifica��o do Transmitente',1,0,"C",1);
   $pdf->cell(80,$altura,'Identifica��o do Adquirente',1,1,"C",1);
   $pdf->cell(20,$altura,'Nome : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_nome,1,0,"L",0);
   $pdf->cell(80,$altura,(@$it03_nome == ""?@$nomecomp:@$it03_nome),1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'CNPJ/CPF:',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_cgccpf,1,0,"L",0);
   $pdf->cell(80,$altura,$cgccpfcomprador,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'Endere�o : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_ender.' - '.$z01_bairro ,1,0,"L",0);
   $pdf->cell(80,$altura,$enderecocomprador.','.$numerocomprador.' / '.$complcomprador ,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'Munic�pio : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_munic.'('.$z01_uf.') - CEP: '.$z01_cep ,1,0,"L",0);
   $pdf->cell(80,$altura,$municipiocomprador.'('.$ufcomprador.') - CEP: '.$cepcomprador . ' - BAIRRO: '.$bairrocomprador ,1,1,"L",0);
   $pdf->Ln(2);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(88,$altura,'Dados do Im�vel',1,0,"C",1);
   $pdf->cell(2,$altura,'',0,0,"C",0);
   $pdf->cell(90,$altura,'Dados da Constru��o(��es)',1,1,"C",1);
   $pdf->SetFont('Arial','',8);
   $y = $pdf->gety();

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(35,$altura,'Matr�cula da Prefeitura: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(13,$altura,$it06_matric,1,0,"L",0);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(30,$altura,'N�mero do im�vel: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(10,$altura,$j39_numero,1,1,"L",0);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(15,$altura,'Setor : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(14,$altura,$j34_setor,1,0,"L",0);
   $pdf->SetFont('Arial','B',8);

   $pdf->cell(15,$altura,'Quadra : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(14,$altura,$j34_quadra,1,0,"L",0);
   $pdf->SetFont('Arial','B',8);

   $pdf->cell(15,$altura,'Lote: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(15,$altura,$matriz[3],1,1,"L",0);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Logradouro: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(66,$altura,$j14_tipo . " " . $j14_nome,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   if(isset($tipo) && $tipo == "urbano"){
     $pdf->cell(22,$altura,'Situa��o: ',1,0,"L",1);
     $pdf->SetFont('Arial','',8);
     $pdf->cell(66,$altura,@$it07_descr,1,1,"L",0);
     $pdf->SetFont('Arial','B',8);
     $pdf->cell(22,$altura,'Frente: ',1,0,"L",1);
     $pdf->cell(21,$altura,db_formatar($it05_frente,'f') . 'm',1,0,"R",0);
     $pdf->cell(22,$altura,'Fundos : ',1,0,"L",1);
     $pdf->cell(23,$altura,db_formatar($it05_fundos,'f').'m',1,1,"R",0);
     $pdf->cell(22,$altura,'Lado Esquerdo: ',1,0,"L",1);
     $pdf->cell(21,$altura,db_formatar($it05_esquerdo,'f').'m',1,0,"R",0);
     $pdf->cell(22,$altura,'Lado Direito: ',1,0,"L",1);
     $pdf->cell(23,$altura,db_formatar($it05_direito,'f').'m',1,1,"R",0);
   }else{
     $pdf->SetFont('Arial','B',8);
     $pdf->cell(22,$altura,'Frente: ',1,0,"L",1);
     $pdf->cell(21,$altura,db_formatar($it18_frente,'f') . 'm',1,0,"R",0);
     $pdf->cell(22,$altura,'Fundos : ',1,0,"L",1);
     $pdf->cell(23,$altura,db_formatar($it18_fundos,'f').'m',1,1,"R",0);
     $pdf->cell(22,$altura,'Profundidade: ',1,0,"L",1);
     $pdf->cell(66,$altura,db_formatar($it18_prof,'f').'m',1,0,"R",0);
    // $pdf->cell(22,$altura,'',1,0,"L",1);
    // $pdf->cell(23,$altura,"",1,1,"R",0);
   }
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'',0,1,"L",0);
   $pdf->cell(22,$altura,'',1,0,"L",1);
   $pdf->cell(33,$altura,'REAL',1,0,"C",1);
   $pdf->cell(33,$altura,'TRANSMITIDA',1,1,"C",1);
   $pdf->SetFont('Arial','B',7);
   $pdf->cell(22,$altura,'Terreno',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(33,$altura,db_formatar($areaterreno+0,'f',' ',' ',' ',5).'m2',1,0,"R",0);
   $pdf->cell(33,$altura,db_formatar($areatran,'f',' ',' ',' ',5).'m2',1,1,"R",0);
   $pdf->SetFont('Arial','B',7);
   $pdf->cell(22,$altura,'Constru��o(��es)',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(33,$altura,(isset($areatotal)?db_formatar($areatotal,'f',' ',' ',' ',5):"").'m2',1,0,"R",0);
   $pdf->cell(33,$altura,(isset($areatrans)?db_formatar($areatrans,'f',' ',' ',' ',5):"").'m2',1,1,"R",0);
   if($tipo == "rural"){
     $pdf->SetFont('Arial','B',6);
     $result = $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query($itbi,"","*","j31_codigo"));
     if($clitbiruralcaract->numrows > 0){
       $pula = 0;
       for($ru=0;$ru<$clitbiruralcaract->numrows;$ru++){
 	 db_fieldsmemory($result,$ru);
	 if($ru == 2 || $ru == 5)
	   $pula = 1;
	 else
	   $pula = 0;
         $pdf->SetFont('Arial','B',6);
         $pdf->cell(23,$altura,$j31_descr,1,0,"L",1);
         $pdf->SetFont('Arial','',6);
         $pdf->cell(6.33,$altura,$it19_valor."%",1,$pula,"R",0);
       }
       $pdf->SetFont('Arial','B',7);
       $pdf->cell(23,$altura,'',1,0,"L",1);
       $pdf->SetFont('Arial','',7);
       $pdf->cell(6.33,$altura,'',1,$pula,"R",0);
     }
   }
   $pdf->SetXY(100,$y);

   $pdf->SetFont('Arial','B',7);
   $pdf->cell(20,$altura,'Descri��o',1,0,"C",1);
   $pdf->cell(18,$altura,'Tipo',1,0,"C",1);
   $pdf->cell(22,$altura,'�rea m2',1,0,"C",1);
   $pdf->cell(22,$altura,'�rea trans m2',1,0,"C",1);
   $pdf->cell(8,$altura,'Ano',1,1,"C",1);
   $pdf->SetFont('Arial','',7);

   $y = $pdf->gety();
   for ($ii = 1;$ii <= 10 ; $ii++){
       $pdf->setx(100);
       $pdf->cell(20,$altura,'',1,0,"C");
       $pdf->cell(18,$altura,'',1,0,"C");
       $pdf->cell(22,$altura,'',1,0,"C");
       $pdf->cell(22,$altura,'',1,0,"C");
       $pdf->cell(8,$altura,'',1,1,"C");
   }
   $yy = $pdf->gety();
   $pdf->SetXY(100,$y);
   if(@pg_numrows($resultcons) > 0){
     for ($n = 0;$n < pg_numrows($resultcons) ; $n++){
	 db_fieldsmemory($resultcons,$n);
	 $resultt = $clitbiconstrespecie->sql_record($clitbiconstrespecie->sql_query($it08_codigo)); 
	 db_fieldsmemory($resultt,0);
	 $it09_codigo = $j31_descr;
	 $resultt = $clitbiconstrtipo->sql_record($clitbiconstrtipo->sql_query($it08_codigo)); 
	 db_fieldsmemory($resultt,0);
	 $it10_codigo = $j31_descr;
	 $pdf->setx(100);
	 $pdf->cell(20,$altura,$it09_codigo,0,0,"L",0);
	 $pdf->cell(18,$altura,$it10_codigo,0,0,"L",0);
	 $pdf->cell(22,$altura,db_formatar($it08_area,'f',' ',' ',' ',5),0,0,"R",0);
	 $pdf->cell(22,$altura,db_formatar($it08_areatrans,'f',' ',' ',' ',5),0,0,"R",0);
	 $pdf->cell(8,$altura,$it08_ano,0,1,"C",0);
	 if($n == 9)
	   break;
     }
   }
   $pdf->sety($yy+2);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(180,$altura,'Observa��es',1,1,"L",1);
   $pdf->SetFont('Arial','',8);
   $y = $pdf->gety();
   $pdf->cell(180,$altura,'',"TLR",1,"L",0);
   $pdf->cell(180,$altura,'',"LBR",1,"l",0);
   $pdf->cell(180,$altura,'',"LBR",1,"l",0);
   $pdf->cell(180,$altura,'',"LBR",1,"l",0);
   $pdf->cell(180,$altura,'',"BLR",1,"l",0);
   $yy = $pdf->gety();
   $pdf->sety($y);   
   $pdf->multicell(180,$altura,$it01_obs,1,"L",0);
   $pdf->multicell(180,$altura,$proprietarios,1,"L",0);
   $pdf->sety($yy);   
   $pdf->cell(50,$altura,'Valor Terreno : '.db_formatar($it14_valoravalter,'f'),1,0,"L",0);
   $pdf->cell(65,$altura,'Valor Constru��o(��es) : '.db_formatar($it14_valoravalconstr,'f'),1,0,"L",0);
   $pdf->cell(65,$altura,'Valor Avalia��o : '.db_formatar($it14_valoraval,'f'),1,1,"L",0);
   $pdf->cell(50,$altura,'Valor Informado : '.db_formatar($it14_valoravalconstr + $it14_valoravalter,'f'),1,0,"L",0);
   $pdf->cell(35,$altura,'Al�quota : '.db_formatar($it14_aliquota,'f').'%',1,0,"L",0);
   $pdf->cell(35,$altura,'Desconto : '.db_formatar($it14_desc,'f').'%',1,0,"L",0);
   $pdf->SetFont('Arial','B',9);
   $pdf->cell(60,$altura,'Valor a Pagar : R$ '.db_formatar(($it14_valorpaga + $tx_banc),'f'),1,1,"L",0);
   $pdf->setfont('Arial','B',11); 
   $pdf->ln(3);
//   $pdf->multicell(180,4,$munic.', '.date('d').' de '.db_mes(date('m')).' de '.date('Y').'.',0,"R",0);
   $pdf->multicell(180,4,$munic.', '.substr($it01_data,8,2).' de '.db_mes(substr($it01_data,5,2)).' de '.substr($it01_data,0,4).'.',0,"R",0);
   $pdf->Ln(4);
   $pdf->setfont('Arial','',11); 
   $pos = $pdf->gety();
   $pdf->setfillcolor(0,0,0); 
   $pdf->text(14,$pos,$linha_digitavel);
   $pdf->int25(10,$pos+1,$codigo_barras,15,0.341);
   $pdf->ln(30);
}   	
$pdf->Output()
?>
