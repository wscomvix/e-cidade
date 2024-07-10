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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");

require_once("classes/db_placaixarec_classe.php");
require_once("classes/db_placaixa_classe.php");
require_once("classes/db_tabplan_classe.php");
require_once("classes/db_taborc_classe.php");

$oGet = db_utils::postMemory($_GET);

$head3 = "PLANILHA DE RECEITAS";

$clPlaCaixa    = new cl_placaixa;
$clPlaCaixaRec = new cl_placaixarec;
$clTabPlan     = new cl_tabplan;
$clTabOrc      = new cl_taborc;

$clPlaCaixaRec->rotulo->label();


$sWherePlaCaixa  = "     k81_seqpla is not null ";
$sWherePlaCaixa .= " and k80_instit = ".db_getsession("DB_instit");

if (isset($oGet->codpla) && trim($oGet->codpla) != '' ) {
	$sWherePlaCaixa .= " and k80_codpla = {$oGet->codpla} ";
} else if ( isset($oGet->sListaPla) && trim($oGet->sListaPla) != ''  ) {  
  $sWherePlaCaixa .= " and k80_codpla in ({$oGet->sListaPla}) ";
} else if ( isset($oGet->iPlaIni) || isset($oGet->iPlaFin) ) {
	
	if ( isset($oGet->iPlaIni) && trim($oGet->iPlaIni) != '' ) {
		$sWherePlaCaixa .= " and k80_codpla >= {$oGet->iPlaIni} ";
	}
	
  if ( isset($oGet->iPlaFin) && trim($oGet->iPlaFin) != '' ) {
    $sWherePlaCaixa .= " and k80_codpla <= {$oGet->iPlaFin} ";
  }	
	
}
if ( isset($oGet->k144_numeroprocesso) && !empty($oGet->k144_numeroprocesso)) {
  
  $sWherePlaCaixa .= " and k144_numeroprocesso = '{$oGet->k144_numeroprocesso}' ";
}
 
$sSqlPlanilha    = $clPlaCaixa->sql_query_rec(null,"k80_codpla, k144_numeroprocesso",null,$sWherePlaCaixa);

$rsPlaCaixa      = $clPlaCaixa->sql_record($sSqlPlanilha);
$iLinhasPlaCaixa = $clPlaCaixa->numrows;

if ( $iLinhasPlaCaixa == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Planilha de caixa n�o cadastrada com este c�digo ou de outra institui��o.');
  exit;
}

if ( $iLinhasPlaCaixa >= 1 &&  isset($oGet->k144_numeroprocesso) && !empty($oGet->k144_numeroprocesso)) {
  
  $sProcesso = db_utils::fieldsMemory($rsPlaCaixa, 0)->k144_numeroprocesso;
  $head4 = "PROCESSO ADMINISTRATIVO: {$sProcesso}";
}

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$iAlt  = 4;
$oPdf->DefOrientation = "L";

for ( $iInd=0; $iInd < $iLinhasPlaCaixa; $iInd++ ) {
	
	$oPlaCaixa = db_utils::fieldsMemory($rsPlaCaixa,$iInd);
	
	$total = 0;

	$sWherePlaCaixaRec = "k81_codpla = {$oPlaCaixa->k80_codpla}";
	$rsRec      = $clPlaCaixaRec->sql_record($clPlaCaixaRec->sql_query(null,"*","op01_dataassinaturacop,k81_seqpla",$sWherePlaCaixaRec));
	$iLinhasRec = $clPlaCaixaRec->numrows;

	for($iIndRec = 0; $iIndRec < $iLinhasRec;$iIndRec++){
	   
	  $oRec = db_utils::fieldsMemory($rsRec,$iIndRec);
	   
	  if ($oPdf->gety() > $oPdf->h - 30 || $iIndRec==0 ){
	      $oPdf->AddPage();
	      $oPdf->setfont('arial','b',14);
	      $oPdf->cell(30,$iAlt,"Planilha :"         ,0,0,"L",0);
	      $oPdf->cell(10,$iAlt,$oRec->k81_codpla               ,0,1,"R",0);
	      $oPdf->ln();
	      $oPdf->cell(20,$iAlt,"Data:"                   ,0,0,"L",0);
	      $oPdf->cell(20,$iAlt,db_formatar($oRec->k80_data,'d'),0,1,"R",0);
	      $oPdf->ln();
	      $oPdf->setfont('arial','b',8);
	      $oPdf->cell(10,$iAlt,"Seq"       ,"B",0,"R",0); 
	      $oPdf->cell(15,$iAlt,"Codigo"    ,"B",0,"R",0); 
	      $oPdf->cell(15,$iAlt,"Conta"     ,"B",0,"R",0); 
	      $oPdf->cell(60,$iAlt,"Descri��o" ,"B",0,"L",0); 
	      $oPdf->cell(10,$iAlt,"Receita"    ,"B",0,"R",0); 
		  $oPdf->cell(80,$iAlt,"Descri��o" ,"B",0,"L",0); 
	      $oPdf->cell(13,$iAlt,"Reduz"     ,"B",0,"L",0);      
		  $oPdf->cell(15,$iAlt,"Fonte"     ,"B",0,"L",0); 
		  $oPdf->cell(40,$iAlt,"Emenda Parlamentar","B",0,"L",0); 	
		  $oPdf->cell(20,$iAlt,"Valor"     ,"B",1,"R",0);	       
	      $oPdf->setfont('arial','',6);
	   }

	   $oPdf->cell(10,$iAlt,$iIndRec+1  ,0,0,"R",0); 
	   $oPdf->cell(15,$iAlt,$oRec->k81_seqpla ,0,0,"R",0); 
	   $oPdf->cell(15,$iAlt,$oRec->k81_conta  ,0,0,"R",0); 
	   $oPdf->cell(50,$iAlt, substr($oRec->k13_descr,0,45));
	   $oPdf->cell(20,$iAlt,$oRec->k81_receita,0,0,"R",0); 

	   // procuramos o reduzido para receitas-extra
	   $reduz="";
	
	   if ($oRec->k02_tipo=='O'){
	     $rr= $clTabOrc->sql_record($clTabOrc->sql_query(db_getsession("DB_anousu"),$oRec->k81_receita));
	     if ($clTabOrc->numrows > 0 ){
	         db_fieldsmemory($rr,0);
	         // aqui tenho o70_codrec
	         $reduz = $k02_codrec;
	     } else {
	         $reduz = 0;
	     }  
	   } else {
	     $rr= $clTabPlan->sql_record($clTabPlan->sql_query($oRec->k81_receita,db_getsession("DB_anousu")));
	     if ($clTabPlan->numrows > 0 ){
	         db_fieldsmemory($rr,0);
	         // aqui tenho o reduzido
	         $reduz = $k02_reduz;
	     } else {
	         $reduz = 0;
	     }  
	   }  
	   if ($reduz==0){
		 $oPdf->cell($atc,$iAlt,"* REVISAR CADASTRO ",0,0,"L",0); 
	   } else {
			if (substr($oRec->k02_estorc,0,1) == 4) {
				$estutural = substr($oRec->k02_estorc,1);
			}	
			$oPdf->cell(75,$iAlt, $estutural." - ".$oRec->k02_drecei,0,"J");
			// $oPdf->SetXY(195 * $iIndRec,$iAlt + 51);
	   }
	   $oPdf->cell(15,$iAlt,"(".$reduz.")",0,0,"R",0); 
	   $oPdf->cell(17,$iAlt,$oRec->k81_codigo,0,0,"C",0); 
	   $k81_emparlamentar = '';
	   switch ($oRec->k81_emparlamentar) {
			case '1':
				$k81_emparlamentar = '1 - Emenda parlamentar individual';
			break;
			case '2':
				$k81_emparlamentar = '2 - Emenda parlamentar de bancada ou de bloco';
			break;
			case '3':
				$k81_emparlamentar = '3 - N�o se aplica';
			break;
			case '4':
				$k81_emparlamentar = '4 - Emenda n�o impositiva (Emendas de ';
			break;
	   }
	   
		$oPdf->cell(35,$iAlt,$k81_emparlamentar,0,0,"J",0); 	
		$oPdf->cell(25,$iAlt,db_formatar($oRec->k81_valor,'f'),0,1,"R",0);
	   /**
	    * Imprime o nome / razao social
	    */
	   $oPdf->setfont('arial','B',6);
	   $oPdf->cell(25, $iAlt,"Nome / Raz�o Social: ",0,0,"L",0);
	   $oPdf->setfont('arial','',6);
	   $oPdf->cell(100,$iAlt,$oRec->z01_nome,0,1,"L",0);
	   if ($oRec->db83_codigoopcredito) {
		 $oPdf->setfont('arial','B',6);
		 $oPdf->cell(15,$iAlt,"Opera��o de Cr�dito: ",0,0,"L",0); 
		 $oPdf->setfont('arial','',6);
		 $oPdf->cell(25,$iAlt,$oRec->db83_codigoopcredito,0,0,"C",0); 
	   }
	   if ($oRec->op01_dataassinaturacop) {
		 $oPdf->setfont('arial','B',6);
		 $oPdf->cell(10,$iAlt,"Data Opera��o de Cr�dito: ",0,0,"L",0); 
		 $oPdf->setfont('arial','',6);
		 $data = explode("-", $oRec->op01_dataassinaturacop);
		 $oPdf->cell(47,$iAlt,$data[2]."/".$data[1]."/".$data[0],0,1,"C",0);
	   }
	   if ($oRec->c206_nroconvenio) {
		 $oPdf->setfont('arial','B',6);
		 $oPdf->cell(17,$iAlt,"Conv�nio",0,0,"L",0); 
		 $oPdf->setfont('arial','',6);
		 $dataconvenio = '';
		 if ($oRec->c206_dataassinatura) {
			$dataconvenio = explode("-", $oRec->c206_dataassinatura);
			$dataconvenio = " - ".$dataconvenio[2]."/".$dataconvenio[1]."/".$dataconvenio[0];
		 }
	   	 $oPdf->cell(17,$iAlt,$oRec->c206_nroconvenio.$dataconvenio,0,1,"C",0); 
	   }
	   	   
	   if($oRec->k81_obs!="") {
	     
	     $oPdf->cell(25,$iAlt,"",0,0,"L",0);
	     $oPdf->multicell(165,$iAlt,$oRec->k81_obs,0,1,"L",0);
	   }
	   $total += $oRec->k81_valor;
	}
	$oPdf->setfont('arial','b',8);
	$oPdf->ln();
	$oPdf->cell(160 - $atc,$iAlt,'TOTAL DE REGISTROS  :  '.$iLinhasRec,1,0,"L",0);
	$oPdf->cell(120,$iAlt,'Valor Total :  '.db_formatar($total,'f'),1,0,"R",0);
}

$oPdf->Output();
   
?>