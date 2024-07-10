<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("classes/db_afasta_classe.php");
include("classes/db_codmovsefip_classe.php");
include("classes/db_movcasadassefip_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_pontofx_classe.php");
include("classes/db_pontofs_classe.php");
include("classes/db_rhrubricas_classe.php");
include("classes/db_inssirf_classe.php");
require_once("classes/db_assenta_classe.php");
require_once("classes/db_afastaassenta_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clafasta = new cl_afasta;
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clcodmovsefip = new cl_codmovsefip;
$clmovcasadassefip = new cl_movcasadassefip;
$clpontofx = new cl_pontofx;
$clpontofs = new cl_pontofs;
$clrhrubricas = new cl_rhrubricas;
$clinssirf = new cl_inssirf;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $db_botao = true;
  $sqlerro = false;
  $clafasta->r45_codigo = $r45_codigo;
  if(trim($r45_dtreto_dia) != "" && trim($r45_dtreto_mes) != "" && trim($r45_dtreto_ano) != ""){
    $clafasta->r45_dtreto = $r45_dtreto_ano."-".$r45_dtreto_mes."-".$r45_dtreto_dia;
  }
  $r45_dtafas = $r45_dtafas_ano."-".$r45_dtafas_mes."-".$r45_dtafas_dia;
  $r45_dtreto = $r45_dtreto_ano."-".$r45_dtreto_mes."-".$r45_dtreto_dia;
  $sWhereVerificaAfastamento = "r45_anousu = {$r45_anousu} and r45_mesusu = {$r45_mesusu} and r45_regist = {$r45_regist} ";
  $sWhereVerificaAfastamento .= " and r45_codigo != '{$r45_codigo}' ";
  $sWhereVerificaAfastamento .= " and (";
  $sWhereVerificaAfastamento .= " ( r45_dtafas between '{$r45_dtafas}'::date and '{$r45_dtreto}'::date or r45_dtreto between '{$r45_dtafas}'::date and '{$r45_dtreto}'::date )";
  $sWhereVerificaAfastamento .= " or ( '{$r45_dtafas}'::date between r45_dtafas and r45_dtreto or '{$r45_dtreto}'::date between r45_dtafas and r45_dtreto )";
  $sWhereVerificaAfastamento .= " )";
  
  $sSqlVerificaAfastamento   = $clafasta->sql_query_file(null, "r45_codigo", null, $sWhereVerificaAfastamento);
  $rsVerificaAfastamento     = $clafasta->sql_record($sSqlVerificaAfastamento);
  $aVerificaAfastamento      = db_utils::getCollectionByRecord($rsVerificaAfastamento);
  if(count($aVerificaAfastamento) > 0){
    
    $sqlerro  = true;
    $erro_msg = "O Servidor j� possui afastamento para o per�odo selecionado \\n Verifique afastamentos Anteriores";
  }
  if ($sqlerro == false) {
    $clafasta->alterar($r45_codigo);
    $erro_msg = $clafasta->erro_msg;
    if($clafasta->erro_status == "0"){
      $sqlerro = true;
    }
  }
  if ($sqlerro == false) {

    $clafastaassenta   = new cl_afastaassenta;
    $classenta         = new cl_assenta;
    $sSqlAfastaAssenta = $clafastaassenta->sql_query_file(null, "h81_assenta", null, " h81_afasta = {$r45_codigo}");
    $rsAfastaAssenta   = db_query($sSqlAfastaAssenta);
    if(pg_num_rows($rsAfastaAssenta) > 0){

      $oAfastaAssenta = db_utils::fieldsmemory($rsAfastaAssenta, 0);
      $classenta->h16_dtterm = $clafasta->r45_dtreto;
      $classenta->alterar($oAfastaAssenta->h81_assenta);
      if($classenta->erro_status == "0") {
        $sqlerro  = true;
        $erro_msg = $classenta->erro_msg;
      }
    }
  }
  if($sqlerro == false){
    $arr_possiveis = Array(2,3,4,5,6,7,8,10,12);
    if(in_array($r45_situac,$arr_possiveis)){

      $result_pontofx = $clpontofx->sql_record($clpontofx->sql_query_file(db_anofolha(),db_mesfolha(),$r45_regist));
      $numrows_pontofx = $clpontofx->numrows;

      if($numrows_pontofx > 0){
        $subpes = db_anofolha();
        $subpes.= db_mesfolha();

      //  db_verifica_dias_trabalhados($r45_regist,db_anofolha(),db_mesfolha());
        $result_dias_trab = db_query("select fc_dias_trabalhados(".$r45_regist.",".db_anofolha().",".db_mesfolha().",false,".db_getsession("DB_instit").") as dias_pagamento");
        if(pg_numrows($result_dias_trab) > 0){
          db_fieldsmemory($result_dias_trab, 0);
        }

        
        $result_tbprev = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query_file(null,null,"(rh02_tbprev + 2) as rh02_tbpev","","rh02_anousu = ".db_anofolha()." and rh02_mesusu = ".db_mesfolha()." and rh02_regist = ".$r45_regist." and rh02_instit = ".db_getsession("DB_instit")));
        if($clrhpessoalmov->numrows > 0){
          db_fieldsmemory($result_tbprev, 0);
        }
	
        for($i=0; $i<$numrows_pontofx; $i++){
          db_fieldsmemory($result_pontofx, $i);

          $result_inssirfsau = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession("DB_instit"),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".trim($rh02_tbpev)."' and (trim(r33_rubsau) <> '' and r33_rubsau is not null) "));
	      $numrows_sau = $clinssirf->numrows;

	      $result_inssirfmat = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession("DB_instit"),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".trim($rh02_tbpev)."' and (trim(r33_rubmat) <> '' and r33_rubmat is not null) "));
          $numrows_mat = $clinssirf->numrows;
	        
          $result_inssirfaci = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession("DB_instit"),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '".trim($rh02_tbpev)."' and (trim(r33_rubaci) <> '' and r33_rubaci is not null) "));
	      $numrows_aci = $clinssirf->numrows;
//	  echo "<br>  r45_situac --> $r45_situac dias_pagamento  $dias_pagamento rubsau $numrows_sau  rubaci $numrows_aci";
          if(($dias_pagamento > 0 && 
	      (
	        ($r45_situac == 6 && $numrows_sau == 0) || 
          ($r45_situac == 10 && $numrows_sau == 0) || 
          ($r45_situac == 12 && $numrows_sau == 0) || 
	        ($r45_situac == 5 && $numrows_mat == 0) ||
	        ($r45_situac == 8 && $numrows_sau == 0) ||
            ($r45_situac == 3 && $numrows_aci == 0)
	      ))
	      || ($dias_pagamento > 0 && $r45_situac != 6 && $r45_situac != 10 && $r45_situac != 12 && $r45_situac != 5 && $r45_situac != 3 && $r45_situac != 8)
	    ){
//	  echo "<br><br>".'dias pagamento1  '.$dias_pagamento.' rubsau '.$numrows_sau.'  rubaci '.$numrows_aci;
            $result_procp = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,db_getsession('DB_instit'),"rh27_propq","","rh27_rubric = '".$r90_rubric."' and rh27_calcp = 't'"));

            $valor_ponto = $r90_valor;
            $quant_ponto = $r90_quant;
            if($clrhrubricas->numrows > 0){
              db_fieldsmemory($result_procp, 0);
              if($r90_valor > 0){
                $valor_ponto = ($r90_valor / 30) * $dias_pagamento;
              }
              if($r90_quant > 0 && $rh27_propq == "t"){
                $quant_ponto = ($r90_quant / 30) * $dias_pagamento;
              }
            }

            $altinclui = true;
            $result_pontofs = $clpontofs->sql_record($clpontofs->sql_query_file(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric));
            if($clpontofs->numrows == 0){
	      $altinclui = false;
            }
//echo "<BR> r45_regist --> $r45_regist r90_rubric --> $r90_rubric valor_ponto --> $valor_ponto quant_ponto --> $quant_ponto";
            $clpontofs->r10_anousu = db_anofolha();
            $clpontofs->r10_mesusu = db_mesfolha();
            $clpontofs->r10_regist = $r45_regist;
            $clpontofs->r10_rubric = $r90_rubric;
            $clpontofs->r10_valor  = "round($valor_ponto,2)";
            $clpontofs->r10_quant  = "round($quant_ponto,2)";
            $clpontofs->r10_lotac  = $r90_lotac;
            $clpontofs->r10_datlim = $r90_datlim;
            $clpontofs->r10_instit = db_getsession("DB_instit");
            $result_pontofs = $clpontofs->sql_record($clpontofs->sql_query_file(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric));
            if($altinclui == true){
              $clpontofs->alterar(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric);
            }else{
              $clpontofs->incluir(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric);
            }
            if($clpontofs->erro_status=="0"){
              $sqlerro = true;
              $erro_msg = $clpontofs->erro_msg;
              break;
            }
          }else if( $r45_situac == 2 || $r45_situac == 7    || 
                   ($r45_situac == 6  && $numrows_sau == 0) ||
                   ($r45_situac == 10  && $numrows_sau == 0) ||
                   ($r45_situac == 12  && $numrows_sau == 0) ||
                   ($r45_situac == 5  && $numrows_mat == 0) ||  
                   ($r45_situac == 8  && $numrows_sau == 0) ||
                   ($r45_situac == 3  && $numrows_aci == 0)   ){
            $clpontofs->excluir(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric);
            if($clpontofs->erro_status=="0"){
              $sqlerro = true;
              $erro_msg = $clpontofs->erro_msg;
              break;
            }
          }
        }
      }
    }
  }
//exit;
  db_fim_transacao();
}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $result = $clafasta->sql_record($clafasta->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $sWhereVerificaAfastamento = "r45_anousu = {$r45_anousu} and r45_mesusu = {$r45_mesusu} and r45_regist = {$r45_regist} ";
  $sWhereVerificaAfastamento .= " and r45_dtafas >= '{$r45_dtreto}' ";
  $sWhereVerificaAfastamento .= " and r45_codigo != '{$r45_codigo}' ";

  $sSqlVerificaAfastamento   = $clafasta->sql_query_file(null, "r45_codigo", null, $sWhereVerificaAfastamento);
  $rsVerificaAfastamento     = $clafasta->sql_record($sSqlVerificaAfastamento);
  if(pg_num_rows($rsVerificaAfastamento) > 0){
    
    db_msgbox("Este afastamento n�o pode ser alterado \\n Existem afastamentos posteriores  no m�s para este servidor");
    $db_opcao = 22;
  } else {
    $db_botao = true;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmafasta.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{
    $clafasta->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","r45_dtreto_dia",true,1,"r45_dtreto_dia",true);
</script>