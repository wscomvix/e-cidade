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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("model/caixa/slip/TipoSlip.model.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_GET_VARS,2);exit;

//$clslip = new cl_slip;

$clrotulo = new rotulocampo;
$clrotulo->label('k17_codigo');
$clrotulo->label('k17_data');
$clrotulo->label('k17_debito');
$clrotulo->label('k17_credito');
$clrotulo->label('k17_valor');
$clrotulo->label('k17_hist');
$clrotulo->label('k17_texto');
$clrotulo->label('k17_dtaut');
$clrotulo->label('k17_autent');
$clrotulo->label('c60_descr');
$clrotulo->label('z01_nome');

$sDbInstit = str_replace('-',', ',$db_selinstit);
$iAnoUsu   = db_getsession("DB_anousu");

$sWhere = "";
$where1 = "";
$sAnd   = "";
$info   = "";

if (isset($db_selinstit) && $db_selinstit != "") {
  $sWhere .= "{$sAnd} k17_instit in ({$sDbInstit}) ";
  $sAnd   = " and ";
}

if (($data != "--") && ($data1 != "--")) {
  $sWhere .= "{$sAnd} k17_data  between '$data' and '$data1'  ";
  $sAnd   = " and ";
} else if ($data != "--"){
	$sWhere .= "{$sAnd} k17_data >= '$data'  ";
	$sAnd   = " and ";
} else if ($data1!="--"){
   $sWhere .= "{$sAnd} k17_data <= '$data1'   ";
   $sAnd   = " and ";
}

if (($data_autenticacao != "--") && ($data_autenticacao1 != "--")) {
    $sWhere .= "{$sAnd} k17_dtaut  between '$data_autenticacao' and '$data_autenticacao1'  ";
    $sAnd   = " and ";
} else if ($data_autenticacao != "--"){
    $sWhere .= "{$sAnd} k17_dtaut >= '$data_autenticacao'  ";
    $sAnd   = " and ";
} else if ($data_autenticacao1!="--"){
    $sWhere .= "{$sAnd} k17_dtaut <= '$data_autenticacao1'   ";
    $sAnd   = " and ";
}

// Filtro em conta espe
$codconta = isset($codconta) ? intval($codconta) : '';
$codcontacred = isset($codcontacred) ? intval($codcontacred) : '';

if (!empty($codconta) || !empty($codcontacred)) {
  if($codconta > 0 and $codcontacred == 0){//somente conta a debito
    $sWhere .= " AND (k17_debito = {$codconta}) ";
  }else if ($codconta == 0 and $codcontacred > 0){//somente conta a credito
    $sWhere .= " AND (k17_credito = {$codcontacred}) ";
  }else{
    $sWhere .= " AND ( (k17_debito = {$codconta}) AND (k17_credito = {$codcontacred}) ) ";
  }

}

if ($situac=="A"){
   $where1 = "";
	 $info   = "SITUA��O: Todas ";
}else {
   $where1 = "{$sAnd} k17_situacao = $situac ";
	 switch ($situac){
      case 1:
			   $tipo = " N�o Autenticadas";
			break;
      case 2:
			   $tipo = " Autenticadas";
			break;
      case 3:
			   $tipo = " Revogadas";
			break;
      case 4:
			   $tipo = " Canceladas";
			break;
	 }
	 $info = "SITUA��O: $tipo";
}
if($tiposlip != 0){
  $sWhere3 .= "{$sAnd} k153_slipoperacaotipo = '$tiposlip'   ";
   $sAnd   = " and ";
}
if(trim($codigos) != ""){
  $sWhere .= "{$sAnd} k17_numcgm ";
  if($parametro=="N"){
    $sWhere .= " not ";
  }
  $sWhere .= " in ($codigos) ";
  $sAnd   = " and ";
}
$whereslip = "";

if(isset($slip1) && trim($slip1)!=""){
  $whereslip = " slip.k17_codigo >= $slip1 ";
}

if(isset($slip2) && trim($slip2) != ""){
  if(trim($whereslip) != ""){
    $whereslip = " slip.k17_codigo between $slip1 and $slip2 ";
  }else{
    $whereslip = " slip.k17_codigo <= $slip2 ";
  }
}

if(isset($recurso) && $recurso!='0'){
    $whereslip .= " ( r1.c61_codigo =$recurso  or r2.c61_codigo =$recurso  ) ";
    $head4 = "RECURSO : $recurso";

}
if(isset($hist) && $hist != ''){
    $whereslip .= " slip.k17_hist = {$hist}";
}

if($tipo != 0){
  $sWhere3 .= "{$sAnd} k17_tiposelect = '$tipo'   ";
   $sAnd   = " and ";
}

if (!empty($whereslip)) {
  $sWhere .= $sAnd.$whereslip;
}

$sDescrInst = '';
$sVirg      = '';
$bFlagAbrev = false;
$sSqlInstit = "select codigo,nomeinst,nomeinstabrev from db_config where codigo in ({$sDbInstit}) ";
$resInst    = db_query($sSqlInstit);
$iInstit    = pg_num_rows($resInst);

if ($iInstit > 0) {
	for($i = 0; $i < $iInstit; $i++){
	  $oDescrInst = db_utils::fieldsMemory($resInst,$i);
	  if (strlen(trim($oDescrInst->nomeinstabrev)) > 0) {
	       $sDescrInst .= $sVirg."($oDescrInst->codigo)".$oDescrInst->nomeinstabrev;
	       $bFlagAbrev  = true;
	  } else {
	       $sDescrInst .= $sVirg."($oDescrInst->codigo)".$oDescrInst->nomeinst;
	  }

	  $sVirg = ', ';
	}
}

if ($bFlagAbrev == false){
  if (strlen($sDescrInst) > 42){
    $sDescrInst = substr($sDescrInst,0,100);
  }
}

$sCampoProcesso = '';
if  ( isset($k145_numeroprocesso) && !empty($k145_numeroprocesso)) {

  $sCampoProcesso = ' , k145_numeroprocesso as sprocesso ';
  $sWhere .= " and k145_numeroprocesso = '{$k145_numeroprocesso}'";
}

if ($agrupar == 1) {
    $sAgrupar = "cgm.z01_numcgm";
} elseif ($agrupar == 2) {
    $sAgrupar = "slip.k17_debito";
} elseif ($agrupar == 3) {
    $sAgrupar = "slip.k17_credito";
} elseif ($agrupar == 4) {
    $sAgrupar = "k29_recurso";
} elseif ($agrupar == 5) {
  $sAgrupar = "k17_tiposelect";
} else {
    $sAgrupar = "";
}

$head3 = "CADASTRO DE SLIP ";
$head4 = $info;
$head5 = "ORDEM: Num�rica";
$head6 = "INSTITUI��O: ".$sDescrInst;

$sql = "         select slip.k17_codigo {$sCampoProcesso},
                             k17_data,
                             k17_debito,
	                         c1.c60_descr as debito_descr,
		                     k17_credito,
						     c2.c60_descr as credito_descr,
						     k17_valor,
								 (case when k17_situacao = 1 then 'N�o Autenticado'
								       when k17_situacao = 2 then 'Autenticado'
                       when k17_situacao = 3 then 'Estornado'
											 when k17_situacao = 4 then 'Cancelado'
									end
									) as k17_situacao,
						     k17_hist,
						     k17_texto,
						     k17_dtaut,
						     k17_autent,
						     z01_numcgm,
						     z01_nome,
                 case
                  when k29_recurso = 1 then 15000000
                  else k29_recurso
                 end as k29_recurso,
                 o15_descr,
                 k17_tiposelect
                      from slip
	                     inner join conplanoreduz r1 on r1.c61_reduz = k17_debito and r1.c61_anousu=".$iAnoUsu."
	                     inner join conplano c1 on c1.c60_codcon = r1.c61_codcon and c1.c60_anousu = r1.c61_anousu

	                     inner join conplanoreduz r2 on r2.c61_reduz = k17_credito and r2.c61_anousu=".$iAnoUsu."
		             inner join conplano c2 on c2.c60_codcon = r2.c61_codcon and c2.c60_anousu = r2.c61_anousu

		             left  join slipnum on slipnum.k17_codigo = slip.k17_codigo
		             left  join cgm on cgm.z01_numcgm = slipnum.k17_numcgm
		             left join slipprocesso on slip.k17_codigo = k145_slip
                 left join sliptipooperacaovinculo on slip.k17_codigo = sliptipooperacaovinculo.k153_slip
                 left join sliprecurso on slip.k17_codigo = k29_slip
                 left join orctiporec on k29_recurso = o15_codigo
                 where {$sWhere} {$where1} {$sWhere3}";

if ($sAgrupar != "" || $ordenar != '0') {
  $sql .= "order by {$sAgrupar}";
  $sql .= $sAgrupar != "" && $ordenar != '0' ? "," : "";
  $sql .= ($ordenar == '1' ? " slip.k17_codigo" : ($ordenar == '2' ? " slip.k17_data" : ($ordenar == '3' ? " slip.k17_dtaut" : "")));
}          

$result = db_query($sql);


if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem registros cadastrados.');

}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$prenc = 0;
$alt   = 4;
$total = 0;
$total_grupo          = 0;
$total_valor          = 0;
$total_valor_grupo    = 0;
$total_credito        = 0;
$total_credito_grupo  = 0;
$total_debito         = 0;
$total_debito_grupo   = 0;
$num_rows             = pg_numrows($result);
$clDeParaRecurso = new DeParaRecurso;
$clTipoSlip = new TipoSlip;
for($x = 0; $x < pg_numrows($result);$x++){
    
    db_fieldsmemory($result,$x);

    $k29_recurso = $clDeParaRecurso->getDePara($k29_recurso);

    $clTipoSlip->setTipoSlip($k17_tiposelect);
    $k17_tiposelectdescr = $clTipoSlip->getDescricaoTipoSlip();

    if ( isset($sprocesso) && !empty($sprocesso)) {
        $head7 = "PROCESSO ADMINISTRATIVO: {$sprocesso}";
    }

    if ( $agrupar != 0 && $repete == "" ) {

        if ($agrupar == 1) {
            $nome_variavel = "z01_numcgm";
        } elseif ($agrupar == 2) {
            $nome_variavel = "k17_debito";
        } elseif ($agrupar == 3) {
            $nome_variavel = "k17_credito";
        } elseif ($agrupar == 4) {
          $nome_variavel = "k29_recurso";
        } elseif ($agrupar == 5) {
          $nome_variavel = "k17_tiposelect";
        }

    }

    if ( $agrupar != 0 && $repete != "" && $repete != $$nome_variavel ) {
      
        $pdf->setfont('arial','b',8);
        $pdf->cell(50, $alt, "TOTAL DE ".db_formatar($total_grupo, "s")." SLIP". ($total_grupo == 1 ? "" : "S"),  "B", 0, "C", 1);
        $pdf->cell(80, $alt, "TOTAL DEBITO: ".db_formatar($total_debito_grupo,'f'), "B", 0, "C", 1);
        $pdf->cell(80, $alt, "TOTAL CREDITO: ".db_formatar($total_credito_grupo,'f'),  "B", 0, "C", 1);
        $pdf->cell(70, $alt, "TOTAL: ".db_formatar($total_valor_grupo,'f'),  "B", 0, "R", 1);
        $pdf->Ln();

        $total_grupo = 0;
        $total_debito_grupo = 0;
        $total_credito_grupo = 0;
        $total_valor_grupo = 0;
    
    }

    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      
        $pdf->addpage("L");
        $pdf->setfont('arial','b',8);
        $pdf->cell(20,$alt+4,$RLk17_codigo,1,0,"C",1);
        $y  = $pdf->getY();
        $pdf->cell(30,$alt,"Datas",1,1,"C",1);
        $pdf->setx(30);
        $pdf->cell(15,$alt,'Emiss�o',1,0,"C",1);
        $pdf->cell(15,$alt,'Autentic',1,0,"R",1);
        $pdf->setxy(60,$y);
        $pdf->cell(15,$alt+4,"C. D�bito",1,0,"C",1);
        $pdf->cell(65,$alt+4,$RLc60_descr,1,0,"C",1);
        $pdf->cell(15,$alt+4,"C. Cr�dito",1,0,"C",1);
        $pdf->cell(65,$alt+4,$RLc60_descr,1,0,"C",1);
        $pdf->cell(40,$alt+4,"Situa��o",1,0,"C",1);
        $pdf->cell(30,$alt+4,$RLk17_valor,1,1,"C",1);
        $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(215,$alt,$RLk17_texto,1,1,"C",1);

        $troca = 0;
        $prenc = 1;
    
    }

    if ($prenc == 0){
        $prenc = 1;
    } else $prenc = 0;

    if ($agrupar != 0 && $total_grupo == 0) {

        $pdf->setfont('arial','b',8);

        if ($agrupar == 1) {            
            $pdf->cell(280, $alt, "CREDOR: ".$z01_nome, 0, 0, "L", 0);
            $pdf->Ln();
        } elseif ($agrupar == 2) {
            $pdf->cell(280, $alt, "CONTA DEBITO: ".$k17_debito.' - '.$debito_descr, 0, 0, "L", 0);
            $pdf->Ln();
        } elseif ($agrupar == 3) {
            $pdf->cell(280, $alt, "CONTA CREDITO: ".$k17_credito.' - '.$credito_descr, 0, 0, "L", 0);
            $pdf->Ln();
        } elseif ($agrupar == 4) {
            $pdf->cell(280, $alt, "FONTE DE RECURSO: ".$k29_recurso.' - '.$o15_descr, 0, 0, "L", 0);
            $pdf->Ln();
        } elseif ($agrupar == 5) {
            if ($k17_tiposelect) {
              $pdf->cell(280, $alt, "TIPO: ".$k17_tiposelect.' - '.$k17_tiposelectdescr, 0, 0, "L", 0);
              $pdf->Ln();
            } else {
              $pdf->cell(280, $alt, "Sem Tipo Informado", 0, 0, "L", 0);
              $pdf->Ln();
            }
        }
        

    }

   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$k17_codigo,0,0,"C",$prenc);
   $pdf->cell(15,$alt,db_formatar($k17_data,'d'),0,0,"C",$prenc);
   $pdf->cell(15,$alt,db_formatar($k17_dtaut,'d'),0,0,"C",$prenc);
   $pdf->cell(15,$alt,$k17_debito,0,0,"C",$prenc);
   $pdf->cell(65,$alt,substr($debito_descr,0,44),0,0,"L",$prenc);
   $pdf->cell(15,$alt,$k17_credito,0,0,"C",$prenc);
   $pdf->cell(65,$alt,substr($credito_descr,0,44),0,0,"L",$prenc);
   $pdf->cell(40,$alt,$k17_situacao,0,0,"C",$prenc);
   $pdf->cell(30,$alt,db_formatar($k17_valor,'f'),0,1,"R",$prenc);
   $pdf->cell(65,$alt,substr($z01_nome,0,35),0,0,"L",$prenc);
   $pdf->multicell(215,$alt,$k17_texto,0,"L",$prenc);
   $total++;
   $total_valor += $k17_valor;
   
   $total_grupo++;
   $total_valor_grupo += $k17_valor;
   
   if($k17_credito==$codconta) {
      
      $total_credito += $k17_valor;
      $total_credito_grupo += $k17_valor;
   
   } else if($k17_debito==$codconta) {
        
      $total_debito += $k17_valor;
      $total_debito_grupo += $k17_valor;
   
   }

   if ( $agrupar != 0 && $num_rows == $total ) {
      $pdf->setfont('arial','b',8);
      $pdf->cell(50, $alt, "TOTAL DE ".db_formatar($total_grupo, "s")." SLIP". ($total_grupo == 1 ? "" : "S"),  "B", 0, "C", 1);
      $pdf->cell(80, $alt, "TOTAL DEBITO: ".db_formatar($total_debito_grupo,'f'), "B", 0, "C", 1);
      $pdf->cell(80, $alt, "TOTAL CREDITO: ".db_formatar($total_credito_grupo,'f'),  "B", 0, "C", 1);
      $pdf->cell(70, $alt, "TOTAL: ".db_formatar($total_valor_grupo,'f'),  "B", 0, "R", 1);
      $pdf->Ln();
      $pdf->Ln();
  }

   if ($agrupar != 0) {
      
      if ($agrupar == 1) {
          $repete = $z01_numcgm;
      } elseif ($agrupar == 2) {
          $repete = $k17_debito;
      } elseif ($agrupar == 3) {
          $repete = $k17_credito;
      } elseif ($agrupar == 4) {
        $repete = $k29_recurso;
      } elseif ($agrupar == 5) {
        $repete = $k17_tiposelect;
    }

  }

}

$pdf->setfont('arial','b',8);
$pdf->cell(117,$alt,'TOTAL DE REGISTROS:  '.$total,"T",0,"L",0);
$pdf->cell(60,$alt,'TOTAL DEBITO:  '.db_formatar($total_debito,'f'),"T",0,"L",0);
$pdf->cell(60,$alt,'TOTAL CREDITO:  '.db_formatar($total_credito,'f'),"T",0,"L",0);
$pdf->cell(41,$alt,'VALOR TOTAL:  '.db_formatar($total_valor,'f'),"T",0,"L",0);

$pdf->Output();
?>
