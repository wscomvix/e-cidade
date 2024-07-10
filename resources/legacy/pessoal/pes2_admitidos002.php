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
include("classes/db_rhpessoal_classe.php");
include("classes/db_pontofx_classe.php");
include("classes/db_rhcadregime_classe.php");
$clrhpessoal = new cl_rhpessoal;
$clpontofx = new cl_pontofx;
$clrhcadregime = new cl_rhcadregime;
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("rh01_admiss");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_ident");
$clrotulo->label("rh01_nasc");
$clrotulo->label("rh37_rubric");
$clrotulo->label("rh37_descr");
$clrotulo->label("r70_estrut");
$clrotulo->label("r70_descr");
$clrotulo->label("r70_codigo");
$clrotulo->label("o40_orgao");
$clrotulo->label("o40_descr");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$documento = $_GET['documentos'];

$head5 = "PER�ODO: ".db_formatar($datai,"d")." at� ".db_formatar($dataf,"d");

$ano = db_anofolha();
$mes = db_mesfolha();

$camposQuebra = ", '' as quebrar";
$labelFuncaoLotacao = $RLrh37_descr;
$valorImprime = "rh37_descr";
if($tiporesumo == "l"){
  $labelFuncaoLotacao = $RLr70_descr;
  $valorImprime = "r70_descr";
  $camposQuebra = ", r70_estrut as quebrar, r70_descr as descricao";
  $camposQuebra = ", r70_estrut as quebrar, r70_descr as descricao";
  if($ordem == "a"){
    $orderby = " r70_estrut,z01_nome ";
    $head6  = "ORDEM: ALFAB�TICA";
  }else if($ordem == "n"){
    $orderby = " r70_estrut,rh01_regist ";
    $head6  = "ORDEM: MATR�CULA";
  }else{
    $orderby = " r70_estrut,rh01_admiss ";
    $head6  = "ORDEM: ADMISS�O";
  }
}else if($tiporesumo == "c"){
  $labelFuncaoLotacao = $RLrh37_descr;
  $valorImprime = "rh37_descr";
  $camposQuebra = ", rh37_funcao as quebrar, rh37_descr as descricao";
  if($ordem == "a"){
    $orderby = " rh37_descr,z01_nome ";
    $head6  = "ORDEM: ALFAB�TICA";
  }else if($ordem == "n"){
    $orderby = " rh37_descr,rh01_regist ";
    $head6  = "ORDEM: MATR�CULA";
  }else{
    $orderby = " rh37_descr,rh01_admiss ";
    $head6  = "ORDEM: ADMISS�O";
  }
}else if($tiporesumo == "o"){
  $labelFuncaoLotacao = $RLo40_descr;
  $valorImprime = "o40_descr";
  $camposQuebra = ", o40_orgao as quebrar, o40_descr as descricao";
  if($ordem == "a"){
    $orderby = " o40_descr,z01_nome ";
    $head6  = "ORDEM: ALFAB�TICA";
  }else if($ordem == "n"){
    $orderby = " o40_descr,rh01_regist ";
    $head6  = "ORDEM: MATR�CULA";
  }else{
    $orderby = " o40_descr,rh01_admiss ";
    $head6  = "ORDEM: ADMISS�O";
  }
}else{
  if($ordem == "a"){
    $orderby = " z01_nome ";
    $head6  = "ORDEM: ALFAB�TICA";
  }else if($ordem == "n"){
    $orderby = " rh01_regist ";
    $head6  = "ORDEM: MATR�CULA";
  }else{
    $orderby = " rh01_admiss ";
    $head6  = "ORDEM: ADMISS�O";
  }
}

$sWhere = "";
if($tiporesumo == 'c'){
 if(isset($cai) && trim($cai) != "" && isset($caf) && trim($caf) != ""){
    // Se for por intervalos e vier lota��o inicial e final
    $sWhere     .= " and rh37_funcao between '".$cai."' and '".$caf."' ";
  }else if(isset($cai) && trim($cai) != ""){
    // Se for por intervalos e vier somente lota��o inicial
    $sWhere    .= " and rh37_funcao >= '".$cai."' ";
  }else if(isset($caf) && trim($caf) != ""){
    // Se for por intervalos e vier somente lota��o final
    $sWhere    .= " and rh37_funcao <= '".$caf."' ";
  }else if(isset($fca) && trim($fca) != ""){
    // Se for por selecionados
    $sWhere  .= " and rh37_funcao in ('".str_replace(",","','",$fca)."') ";
  }
}elseif($tiporesumo == 'l'){
  if(isset($lti) && trim($lti) != "" && isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier local inicial e final
    $sWhere    .= " and r70_estrut between '".$lti."' and '".$ltf."' ";
  }else if(isset($lti) && trim($lti) != ""){
    // Se for por intervalos e vier somente local inicial
    $sWhere    .= " and r70_estrut >= '".$lti."' ";
  }else if(isset($ltf) && trim($ltf) != ""){
    // Se for por intervalos e vier somente local final
    $sWhere    .= " and r70_estrut <= '".$ltf."' ";
  }else if(isset($flt) && trim($flt) != ""){
    // Se for por selecionados
    $sWhere  .= " and r70_estrut in ('".str_replace(",","','",$flt)."') ";
  }

}else{
  if(isset($ori) && trim($ori) != "" && isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier �rg�o inicial e final
    $sWhere    .= " and o40_orgao between ".$ori." and ".$orf;
    $sIntervalo.= " DE ".$ori." A ".$orf;
  }else if(isset($ori) && trim($ori) != ""){
    // Se for por intervalos e vier somente �rg�o inicial
    $sWhere .= " and o40_orgao >= ".$ori;
  }else if(isset($orf) && trim($orf) != ""){
    // Se for por intervalos e vier somente �rg�o final
    $sWhere    .= " and o40_orgao <= ".$orf;
  }else if(isset($for) && trim($for) != ""){
    // Se for por selecionados
    $sWhere  .= " and o40_orgao in (".$for.") ";
  }
}

$dbwhere = " rh02_anousu = ".$ano." and rh02_mesusu = ".$mes." and rh02_instit = ".db_getsession("DB_instit")." and ";

if($regime != ""){
  if($tipo=="r"){
     $dbwhere .= " rh30_regime in (".$regime.") and ";
  }else{
     $dbwhere .= " rh30_codreg in (".$regime.") and ";
  }
}

if($adm_dem == 'a'){
  $head3 = "FUNCION�RIOS ADMITIDOS";
   
  if(trim($datai) != "" && trim($dataf) != ""){
    $dbwhere.= " rh01_admiss between '".$datai."' and '".$dataf."' ";
  }else if(trim($datai) != ""){
    $dbwhere.= " rh01_admiss >= '".$datai."' ";
  }else if(trim($dataf) != ""){
    $dbwhere.= " rh01_admiss <= '".$dataf."' ";
  }
}else{
  $head3 = "FUNCION�RIOS DEMITIDOS";
   
  if(trim($datai) != "" && trim($dataf) != ""){
    $dbwhere.= " rh05_recis between '".$datai."' and '".$dataf."' ";
  }else if(trim($datai) != ""){
    $dbwhere.= " rh05_recis >= '".$datai."' ";
  }else if(trim($dataf) != ""){
    $dbwhere.= " rh05_recis <= '".$dataf."' ";
  }
}
$dbwhereinativo = " and (rh30_vinculo = 'A' ";
if(isset($listainativo)){
  $dbwhereinativo .= " or rh30_vinculo = 'I' ";
}
if(isset($listapens)){
  $dbwhereinativo .= " or rh30_vinculo = 'P' ";
}
$dbwhereinativo.= ") ";

$dbwhererescis = " and rh05_recis is null ";
if(isset($listarescis) || $adm_dem == 'd' ) {
  $dbwhererescis = "";
}


$dbwhere .= $dbwhereinativo.$dbwhererescis.$sWhere;

$sql_dados = $clrhpessoal->sql_query_lotafuncres(null,"rh01_regist, z01_nome, z01_ident, z01_cgccpf, rh01_nasc, rh01_admiss, r70_estrut, r70_descr, rh37_funcao, rh37_descr,o40_orgao,o40_descr,rh03_padrao,rh05_recis".$camposQuebra,$orderby,$dbwhere);
$result_dados = $clrhpessoal->sql_record($sql_dados);
$numrows_dados = $clrhpessoal->numrows;
if($numrows_dados == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=N�o existem funcion�rios admitidos no per�odo de ".db_formatar($datai,"d")." e ".db_formatar($dataf,"d").".");
}

if($regime != 0){
   if($tipo=="r"){
      $head7 = "REGIMES : (".$regime.")";
   }else{
      $head7 = "VINCULOS : (".$regime.")";
   }
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont("arial","b",8);
$troca = 1;
$alt = 4;
$pre = 0;
$lotacao_antes = 0;

$imprime_dados_quebra = true;

for($x=0; $x<$numrows_dados; $x++){
  db_fieldsmemory($result_dados,$x);

  if($quebrapagina == "s" && $lotacao_antes != $quebrar){
    $lotacao_antes = $quebrar;
    $troca = 1;
    $imprime_dados_quebra = true;
  }

  if($pdf->gety() > $pdf->h - 30 || $troca != 0){
    if($documentos)
      $pdf->addpage('L');
    else
      $pdf->addpage();  

    $pdf->setfont("arial","b",8);
    $pdf->cell(20,$alt,$RLrh01_regist,1,0,"C",1);
    $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(70,$alt,$labelFuncaoLotacao,1,0,"C",1);

    if($documentos){
      $pdf->cell(20,$alt,$RLrh01_admiss,1,0,"C",1);
      $pdf->cell(20,$alt,'Rescis�o',1,0,"C",1);
      $pdf->cell(20,$alt,'CPF',1,0,"C",1);
      $pdf->cell(20,$alt,'RG',1,0,"C",1);
      $pdf->cell(44,$alt,'Data de Nascimento',1,1,"C",1);
    }else{
      $pdf->cell(15,$alt,$RLrh01_admiss,1,0,"C",1);
      $pdf->cell(15,$alt,'Rescis�o',1,0,"C",1);
      $pdf->cell(13,$alt,'PADRAO',1,1,"C",1);
    }

    if($fixo == "s"){
      $pdf->setfont("arial","b",6);
      $pdf->cell(15,$alt,"RUBRICA",0,0,"C",0);
      $pdf->cell(110,$alt,"DESCRI��O",0,0,"C",0);
      $pdf->cell(30,$alt,"QUANT",0,0,"C",0);
      $pdf->cell(30,$alt,"VALOR",0,1,"C",0);
    }

    $imprime_dados_quebra = true;
    $troca = 0;
    $pre = 1;
  }

  if($quebrapagina == "s" && $imprime_dados_quebra == true){
    $pdf->setfont("arial","b",8);
    $pdf->ln(2);
    $pdf->cell(0,$alt,$quebrar." - ".$descricao,0,1,"L",0);
    $imprime_dados_quebra = false;
  }

  if($pre == 1){
    $pre = 0;
  }else{
    $pre = 1;
  }

  $put_b = "";
  $put_t = "0";
  if($fixo == "s"){
    $put_b = "b";
    $put_t = "T";
    $pre = 1;
    $pdf->ln(1);
  }

  $pdf->setfont("arial",$put_b,7);
  $pdf->cell(20,$alt,$rh01_regist,$put_t,0,"C",$pre);
  $pdf->cell(65,$alt,$z01_nome,$put_t,0,"L",$pre);
  $pdf->cell(70,$alt,substr($$valorImprime,0,40),$put_t,0,"L",$pre);
  if($documentos){
    $pdf->cell(20,$alt,db_formatar($rh01_admiss,"d"),$put_t,0,"C",$pre);
    $pdf->cell(20,$alt,db_formatar($rh05_recis,"d"),$put_t,0,"C",$pre);
    $pdf->cell(20,$alt,$z01_cgccpf,$put_t,0,"C",$pre);
    $pdf->cell(20,$alt,$z01_ident,$put_t,0,"C",$pre);
    $pdf->cell(44,$alt,db_formatar($rh01_nasc,"d"), $put_t,1,"C",$pre);
  }else{
    $pdf->cell(15,$alt,db_formatar($rh01_admiss,"d"),$put_t,0,"C",$pre);
    $pdf->cell(15,$alt,db_formatar($rh05_recis,"d"),$put_t,0,"C",$pre);
    $pdf->cell(13,$alt,$rh03_padrao,$put_t,1,"L",$pre);
  }
  

  if($fixo == "s"){
    $sql_fixo = $clpontofx->sql_query_rubrica($ano, $mes, $rh01_regist, null, " * ");
    $result_fixo = $clpontofx->sql_record($sql_fixo);
    $numrows_fixo = $clpontofx->numrows;
    for($xx=0; $xx<$numrows_fixo; $xx++){
      db_fieldsmemory($result_fixo,$xx);
      $pdf->setfont("arial","",6);
      $pdf->cell(15,$alt,$rh27_rubric,0,0,"C",0);
      $pdf->cell(110,$alt,$rh27_descr,0,0,"L",0);
      $pdf->cell(30,$alt,db_formatar($r90_quant,"f"),0,0,"R",0);
      $pdf->cell(30,$alt,db_formatar($r90_valor,"f"),0,1,"R",0);
    }
  }
  $total += 1;
}

$pdf->setfont("arial","b",8);
$pdf->cell(190,$alt,"TOTAL :  ".$total."  FUNCION�RIOS","T",0,"R",0);



$pdf->Output();
