<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('j34_setor');
$clrotulo->label('j34_quadra');
$clrotulo->label('j34_lote');
$clrotulo->label('j39_numero');	

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$where = "";
$and = "";

$setores = @$_SESSION["setor"];
$quadras = @$_SESSION["quadra"];
$ruas    = @$_SESSION["rua"];

if (!empty($setores) ) {
 if (is_array($setores)) {
   $setores = implode(",",$setores);
 }
 $where .= $and."j34_setor in ($setores)";
 $and = " and ";
} 
  
if (!empty($quadras) ) {
 if (is_array($quadras)) {
   $quadras = implode(",",$quadras);
 }
 
 $where .= $and."j34_quadra in ($quadras)";
 $and = " and "; 
}

if (!empty($ruas)) {
 if(is_array($ruas)){
   $ruas = implode(",",$ruas);
 }
  $where .= " $and proprietario.codpri in ($ruas)";
  $and = " and ";
}

unset($_SESSION["setor"]);
unset($_SESSION["quadra"]);
unset($_SESSION["rua"]);


if (isset($filtro)&&$filtro=="1"){
  $campo = " z01_numcgm "; 
}else if (isset($filtro)&&$filtro=="2"){
  $campo = " z01_cgmpri ";
}

if ($lista != "") {
	if (isset ($ver) and $ver == "com") {
		$where .= $and."  $campo in  ($lista)";		
	} else {
		$where.= $and."  $campo  ($lista)";		
	}
	$and = " and ";
}

$info1 = "";
if (isset($tipo1)&&$tipo1=="b"){
  $where .= $and." j01_baixa is not null ";
  $and = " and ";  
  $info1 = "Listar: Matriculas Baixadas ";
}else if (isset($tipo1)&&$tipo1=="n"){
  $where .= $and." j01_baixa is null ";
  $and = " and ";  
  $info1 = "Listar: Matriculas Nao Baixadas";
}

$info2 = "";
if (isset($tipo2)&&$tipo2=="pr"){
  $where .= $and."j01_tipoimp = 'Predial'  ";
  $and = " and "; 
  $info2 = "Tipo: Predial"; 
}else if (isset($tipo2)&&$tipo2=="tr"){
  $where .= $and."j01_tipoimp = 'Territorial'  ";
  $and = " and ";
  $info2 = "Tipo: Territorial";  
}

$info4 = "";
if (isset($bairro)&&$bairro > 0){
  $where .= $and."j13_codi = ".$bairro;
  $and = " and "; 
  $info4 = "Bairro:".$descrbairro; 
}

$order = "";
$info3 = "";
if (isset($ordem)&&$ordem=="pr"){
  $order = "order by proprietario";   
  $info3 = "Ordenado por: Propriet�rio"; 
}else if (isset($ordem)&&$ordem=="ps"){
  $order = "order by possuidor";
  $info3 = "Ordenado por: Possuidor";
}else if (isset($ordem)&&$ordem=="m"){
  $order = "order by j01_matric";
  $info3 = "Ordenado por: Matr�cula";
}else if (isset($ordem)&&$ordem=="s"){
  $order = "order by j34_setor,j34_quadra,j34_lote";
  $info3 = "Ordenado por: Setor/Quadra/Lote";
}

if ($where!=""){
	$where = "where ".$where; 
}

$head2 = "Lotes por Setor/Quadra/Proprietario";
$head3 = @$info3;
$head4 = @$info1;
$head5 = @$info2;
$head6 = @$info4;

$sql = "select * from (
      select proprietario.j01_matric,
             j34_setor,
             j34_quadra,
             j34_lote,
             j34_area as area_lote,
             sum(j39_area) as area_const,
             nomepri as testada_principal,
             proprietario.
             j39_numero,
	           array_to_string( array_accum( distinct iptuconstr.j39_compl), '. ') as j39_compl,
             proprietario,
	     z01_municpri,
             z01_nome as possuidor,
             j01_tipoimp as tipo,
             j01_baixa,
             z01_numcgm,
             j04_quadraregimo,
             j04_loteregimo
     from proprietario      
     left join iptuconstr on j39_matric = proprietario.j01_matric
     left join iptubaseregimovel on j04_matric = proprietario.j01_matric
     $where  
     group by
             proprietario.j01_matric,
             j34_setor,
             j34_quadra,
             j34_lote,
             j34_area,
             nomepri,
             proprietario.
             j39_numero,
             proprietario,
	     z01_municpri,
             proprietario.z01_nome,
             proprietario.j01_tipoimp,
			 j01_baixa,
             z01_numcgm,
             j04_quadraregimo,
             j04_loteregimo ) as x $order";

$result = db_query($sql);
$numrows = pg_numrows($result);
if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem registros cadastrados.');
}
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$vvt = 0;
for($x = 0; $x < $numrows;$x++){
   db_fieldsmemory($result,$x);
    $valorVenalTerreno = getValorVenalTerreno($j01_matric);
    $valorVenalConstrucao = getValorVenalConstrucao($j01_matric);
    $vvt += $valorVenalTerreno+$valorVenalConstrucao;
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(13,$alt,"Matricula",1,0,"C",1);
      $pdf->cell(8,$alt,$RLj34_setor,1,0,"C",1);
      $pdf->cell(8,$alt,substr($RLj34_quadra,0,4),1,0,"C",1);
      $pdf->cell(8,$alt,$RLj34_lote,1,0,"C",1);
      $pdf->cell(16,$alt,"Area Lote",1,0,"C",1);
      $pdf->cell(16,$alt,"Area Constr",1,0,"C",1);
      $pdf->cell(44,$alt,"Testada Principal",1,0,"C",1);
      $pdf->cell(12,$alt,"N�",1,0,"C",1);
      $pdf->cell(15,$alt,"Compl",1,0,"C",1);
      $pdf->cell(45,$alt,"Proprietario",1,0,"C",1);
      $pdf->cell(28,$alt,"Cidade",1,0,"C",1);
       $pdf->cell(8,$alt,"Q.R.I",1,0,"C",1);
       $pdf->cell(8,$alt,"L.R.I",1,0,"C",1);
       $pdf->cell(5,$alt,"Tp",1,0,"C",1);
       $pdf->cell(15,$alt,"V.V.T",1,0,"C",1);
       $pdf->cell(15,$alt,"V.V.C",1,0,"C",1);
       $pdf->cell(15,$alt,"Total",1,1,"C",1);
       $troca = 0;
      $p=0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(13,$alt,$j01_matric,0,0,"C",$p);
   $pdf->cell(8,$alt,$j34_setor,0,0,"C",$p);
   $pdf->cell(8,$alt,$j34_quadra,0,0,"C",$p);
   $pdf->cell(8,$alt,$j34_lote,0,0,"C",$p);
   $pdf->cell(16,$alt,db_formatar($area_lote+0,'p'),0,0,"C",$p);
   $pdf->cell(16,$alt,db_formatar($area_const+0,'p'),0,0,"C",$p);
   $pdf->cell(44,$alt,substr($testada_principal,0,30),0,0,"L",$p);
   $pdf->cell(12,$alt,$j39_numero,0,0,"C",$p);
   $pdf->cell(15,$alt,substr($j39_compl,0,9),0,0,"L",$p);
   $pdf->cell(45,$alt,substr($proprietario,0,30),0,0,"L",$p);
   $pdf->cell(28,$alt,substr($z01_municpri,0,29),0,0,"L",$p);
   $pdf->cell(8,$alt,$j04_quadraregimo,0,0,"L",$p);
   $pdf->cell(8,$alt,$j04_loteregimo,0,0,"L",$p);
   $pdf->cell(5,$alt,substr($tipo,0,1),0,0,"L",$p);
   $pdf->cell(15,$alt,db_formatar($valorVenalTerreno,'f'),0,0,"R",$p);
   $pdf->cell(15,$alt,db_formatar($valorVenalConstrucao,'f'),0,0,"R",$p);
   $pdf->cell(15,$alt,db_formatar($valorVenalTerreno+$valorVenalConstrucao,'f'),0,1,"R",$p);

   if ($p==1)$p=0;
   else $p=1;
   
   $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(224,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->cell(54,$alt,"VALOR VENAL TOTAL  : ".db_formatar($vvt,'f'),"T",1,"L",0);
$pdf->ln(2);
$pdf->setfont('arial',null,8);
$pdf->cell(0,$alt,"* Valores Venais calculados de acordo com o �ltimo ano de c�lculo de cada im�vel. ","",1,"L",0);
$pdf->cell(0,$alt,"* Valores Venais Territoriais zerados significa que n�o houve c�lculo de IPTU para o im�vel. ","",1,"L",0);
$pdf->cell(0,$alt,"* Valores Venais de Constru��o zerados significa que n�o houve c�lculo de IPTU para aquela constru��o. ","",1,"L",0);
$pdf->Output();

function getValorVenalTerreno($matric){
    $cl_iptucalc = new cl_iptucalc();
    $sSqlMaxAnousu = $cl_iptucalc->sql_query_file(null, $matric,"j23_anousu"," j23_anousu DESC limit 1");
    $resMaxAnoUsu = $cl_iptucalc->sql_record($sSqlMaxAnousu);
    if(!$resMaxAnoUsu){
        return 0;
    }

    $iAnousu = db_utils::fieldsMemory($resMaxAnoUsu,0)->j23_anousu;
    $sSqlValorVenalTerreno = $cl_iptucalc->sql_query_file($iAnousu, $matric,"j23_vlrter");
    $resValorVenalTerreno  = $cl_iptucalc->sql_record($sSqlValorVenalTerreno);

    return db_utils::fieldsMemory($resValorVenalTerreno,0)->j23_vlrter;
}

function getValorVenalConstrucao($matric){
    $cl_iptucale = new cl_iptucale();
    $cl_iptucalc = new cl_iptucalc();
    $sSqlMaxAnousu = $cl_iptucalc->sql_query_file(null, $matric,"j23_anousu"," j23_anousu DESC limit 1");
    $resMaxAnoUsu = $cl_iptucalc->sql_record($sSqlMaxAnousu);
    if(!$resMaxAnoUsu){
        return 0;
    }

    $iAnousu = db_utils::fieldsMemory($resMaxAnoUsu,0)->j23_anousu;
    $sSqlValorVenalConstrucao = $cl_iptucale->sql_query_file($iAnousu, $matric,null,"j22_valor");
    $resValorVenalConstrucao = $cl_iptucale->sql_record($sSqlValorVenalConstrucao);

    if(!$resValorVenalConstrucao){
        return 0;
    }

    return db_utils::fieldsMemory($resValorVenalConstrucao,0)->j22_valor;
}

?>