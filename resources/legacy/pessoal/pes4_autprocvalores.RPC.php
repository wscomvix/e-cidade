<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_rhautonomolanc_classe.php");
$clRHAutonomoLanc = new cl_rhautonomolanc(); 

require_once("classes/db_rhsefiprhautonomolanc_classe.php");
$clRHSefipRHAutonomoLanc = new cl_rhsefiprhautonomolanc(); 

require_once("classes/db_rhsefip_classe.php");
$clRHSefip = new cl_rhsefip();

require_once("classes/db_rhsefipcancela_classe.php");
$clRHSefipCancela = new cl_rhsefipcancela();


$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = "";

try {
	
 	if ( $oParam->sMethod == "consultaRegistros" ) {

 		
 		if ( db_permissaomenu(db_getsession('DB_anousu'),4,1306) || 
 		     db_permissaomenu(db_getsession('DB_anousu'),4,8453) ) {

 		   $lAlteraCgm = true;  	
 		 } else {
       $lAlteraCgm = false;
 		 }
 		
 		$iAnoUsu = $oParam->iAnoUsu;
 		$iMesUsu = str_pad($oParam->iMesUsu,2,'0',STR_PAD_LEFT); 

    // Verifica se j� existe sefip gerado para a compet�ncia informada
    $sWhereVerificaSefip  = "        rh90_ativa is true       ";
    $sWhereVerificaSefip .= "    and rh90_anousu = {$iAnoUsu} "; 
    $sWhereVerificaSefip .= "    and rh90_mesusu = {$iMesUsu} ";
    $sWhereVerificaSefip .= "    and rh90_instit = ".db_getsession("DB_instit");
    
    $rsVerificaSefip      = $clRHSefip->sql_record($clRHSefip->sql_query_file(null,"*",null,$sWhereVerificaSefip)); 		
 		
 		if ( $clRHSefip->numrows > 0 ) {
 			
 			$sMsg  = "Sefip j� gerado para a compet�ncia {$iMesUsu}/{$iAnoUsu}.\n";
 			$sMsg .= "Caso queira processar novamente os registros da compet�ncia informada\n";
 			$sMsg .= "dever� ser cancelado a gera��o atual.";
 			throw new Exception($sMsg);
 		}
 		
 		$sDataCompIni = "{$iAnoUsu}-{$iMesUsu}-01";
 		
 	  $lBisexto = verifica_bissexto($sDataCompIni);

	  if ($lBisexto) {
	   $iFev = 29;
	  } else {
	   $iFev = 28;
	  }
	
	  $aUltimoDia = array("01"=>"31",
	                      "02"=>$iFev,
	                      "03"=>"31",
	                      "04"=>"30",
	                      "05"=>"31",
	                      "06"=>"30",
	                      "07"=>"31",
	                      "08"=>"31",
	                      "09"=>"30",
	                      "10"=>"31",
	                      "11"=>"30",
	                      "12"=>"31");
 		
 		$sDataCompFim = "{$iAnoUsu}-{$iMesUsu}-".$aUltimoDia[$iMesUsu];

    $aElementosFiltro = array('33903604','33903606','33903610','33903611','33903612','33903613','33903616','33903617','33903618','33903619','33903620','33903621','33903622','33903623','33903624','33903625','33903626','33903627','33903628','33903629','33903630','33903631','33903632','33903633','33903634','33903642','33903643','33903644','33903645','33903699','33903501','33903502','33903503','33903504','33903505','33903506','33903599');
 		
 		
    $sSqlConsultaAutonomos  = "select codord,                                                                                                            ";
    $sSqlConsultaAutonomos .= "       z01_nome as nome,                                                                                                  ";
    $sSqlConsultaAutonomos .= "       z01_numcgm as numcgm,                                                                                              ";
    $sSqlConsultaAutonomos .= "       pis,                                                                                                               ";
    $sSqlConsultaAutonomos .= "       cbo,                                                                                                               ";
    $sSqlConsultaAutonomos .= "       configurado,                                                                                                       ";
    $sSqlConsultaAutonomos .= "       c70_data AS data_liquidacao,                                                                                       ";
    $sSqlConsultaAutonomos .= "       sum(coalesce(valor_inss,0)) AS valor_inss,                                                                         ";
    $sSqlConsultaAutonomos .= "       sum(coalesce(valor_irrf,0)) as valor_irrf,                                                                                     ";
    $sSqlConsultaAutonomos .= "       coalesce(c70_valor,0) as valor_servico                                                                                    ";
    $sSqlConsultaAutonomos .= " from (select z01_nome,                                                                                                   ";
    $sSqlConsultaAutonomos .= "              z01_numcgm, e50_codord as codord,                                                                         ";
    $sSqlConsultaAutonomos .= "              e50_data as data_calculo,                                                          ";
    $sSqlConsultaAutonomos .= "              case when retencaotiporec.e21_retencaotipocalc IN (3,7) then coalesce(e23_valorretencao, 0) end as valor_inss,";
    $sSqlConsultaAutonomos .= "              sum(case                                                                                                    ";
    $sSqlConsultaAutonomos .= "                      when retencaotiporec.e21_retencaotipocalc in (1,2)                                                  ";
    $sSqlConsultaAutonomos .= "                      then (select sum(rr.e23_valorretencao)                                                              ";
    $sSqlConsultaAutonomos .= "                              from retencaoreceitas rr                                                                    ";
    $sSqlConsultaAutonomos .= "                             where rr.e23_retencaopagordem = retencaopagordem.e20_sequencial)                             ";
    $sSqlConsultaAutonomos .= "                  else 0                                                                                                  ";                    
    $sSqlConsultaAutonomos .= "                  end)   as valor_irrf,                                                                                   ";
    $sSqlConsultaAutonomos .= "              trim(cgm.z01_pis)         as pis,                                                                           ";
    $sSqlConsultaAutonomos .= "              trim(rhcbo.rh70_estrutural) as cbo,                                                                           ";
    $sSqlConsultaAutonomos .= "              case                                                                                                        ";
    $sSqlConsultaAutonomos .= "                   when rhautonomolanc.rh89_sequencial is not null                                                        ";
    $sSqlConsultaAutonomos .= "                     then 't'                                                                                             ";
    $sSqlConsultaAutonomos .= "                   else 'f'                                                                                               ";
    $sSqlConsultaAutonomos .= "              end               as configurado                                                                            ";
    $sSqlConsultaAutonomos .= "         from  pagordem                                                                                                   ";
    $sSqlConsultaAutonomos .= "              left join retencaopagordem         on pagordem.e50_codord = retencaopagordem.e20_pagordem                   ";
    $sSqlConsultaAutonomos .= "              inner join empempenho       on pagordem.e50_numemp                   = e60_numemp                           ";
    $sSqlConsultaAutonomos .= "              inner join empelemento      on empempenho.e60_numemp                   = empelemento.e64_numemp             ";
    $sSqlConsultaAutonomos .= "              inner join orcelemento      on empelemento.e64_codele = orcelemento.o56_codele and empempenho.e60_anousu = orcelemento.o56_anousu ";
    $sSqlConsultaAutonomos .= "              inner join cgm              on e60_numcgm                            = z01_numcgm                           ";
    $sSqlConsultaAutonomos .= "              left  join cgmfisico        on z04_numcgm                            = z01_numcgm                           ";
    $sSqlConsultaAutonomos .= "              left  join rhcbo            on rh70_sequencial                       = z04_rhcbo                            ";
    $sSqlConsultaAutonomos .= "              left join retencaoreceitas on retencaoreceitas.e23_retencaopagordem = retencaopagordem.e20_sequencial      ";
    $sSqlConsultaAutonomos .= "              left join retencaotiporec  on retencaotiporec.e21_sequencial        = retencaoreceitas.e23_retencaotiporec ";
    $sSqlConsultaAutonomos .= "              left  join rhautonomolanc   on rhautonomolanc.rh89_codord            = e20_pagordem                         ";
    $sSqlConsultaAutonomos .= "        where ( (retencaotiporec.e21_retencaotiporecgrupo = 1                                                             ";
    $sSqlConsultaAutonomos .= "          and retencaoreceitas.e23_ativo is true                                                                          ";
    $sSqlConsultaAutonomos .= "          and retencaotiporec.e21_retencaotipocalc in (1, 2, 3, 7) )                                                      ";
    $sSqlConsultaAutonomos .= "          OR (substr(orcelemento.o56_elemento,2,8) IN ('".implode("','",$aElementosFiltro)."') ) )                        ";
    $sSqlConsultaAutonomos .= "          and pagordem.e50_data >= '{$sDataCompIni}'::date ";
    $sSqlConsultaAutonomos .= "          and length(trim(cgm.z01_cgccpf)) <= 11 																																				 ";
    $sSqlConsultaAutonomos .= "          AND e60_instit = ".db_getsession("DB_instit");
    $sSqlConsultaAutonomos .= "        group by e50_codord,                                                                                            ";
    $sSqlConsultaAutonomos .= "              e50_data,                                                                                              ";
    $sSqlConsultaAutonomos .= "              z01_nome,                                                                                                   ";
    $sSqlConsultaAutonomos .= "              z01_numcgm,                                                                                                 ";
    $sSqlConsultaAutonomos .= "              pis,                                                                                                        ";
    $sSqlConsultaAutonomos .= "              cbo,                                                                                                        ";
    $sSqlConsultaAutonomos .= "              configurado, e23_valorretencao, retencaotiporec.e21_retencaotipocalc                                        ";
    $sSqlConsultaAutonomos .= "        order by e50_codord) as                                                                                         ";
    $sSqlConsultaAutonomos .= "    retencoes                                                                                                             ";
    $sSqlConsultaAutonomos .= "       inner join conlancamord on retencoes.codord = c80_codord                                                           ";
    $sSqlConsultaAutonomos .= "       inner join conlancam    on c70_codlan      = c80_codlan                                                            ";
    $sSqlConsultaAutonomos .= "       inner join conlancamdoc on c70_codlan       = c71_codlan                                                           ";
    $sSqlConsultaAutonomos .= "       inner join conhistdoc   on c71_coddoc       = c53_coddoc                                                           ";
    $sSqlConsultaAutonomos .= " where c53_tipo = 20                                                                                                      ";
    $sSqlConsultaAutonomos .= " AND c70_data BETWEEN '{$sDataCompIni}'::date and '{$sDataCompFim}'::date ";
    $sSqlConsultaAutonomos .= " AND not exists (SELECT c53_tipo FROM conlancamord JOIN conlancamdoc ON c80_codlan = c71_codlan JOIN conhistdoc ON c71_coddoc = c53_coddoc WHERE c80_codord = codord AND c53_tipo = 21) ";
    $sSqlConsultaAutonomos .= " group by codord,                                                                                                         ";
    $sSqlConsultaAutonomos .= "          z01_nome,                                                                                                       ";
    $sSqlConsultaAutonomos .= "          z01_numcgm,                                                                                                     ";
    $sSqlConsultaAutonomos .= "          pis,                                                                                                            ";
    $sSqlConsultaAutonomos .= "          cbo,                                                                                                            ";
    $sSqlConsultaAutonomos .= "          data_liquidacao,                                                                                                ";
    $sSqlConsultaAutonomos .= "          configurado, c70_valor                                                     									 ";
 		
    $rsListaAutonomos       = db_query($sSqlConsultaAutonomos);
    $aListaAutonomos        = db_utils::getColectionByRecord($rsListaAutonomos,false,false,true);

	  $oRetorno->aListaAutonomos = $aListaAutonomos;
	  $oRetorno->lAlteraCgm      = $lAlteraCgm;  		
  	
 	} else if ( $oParam->sMethod == "insereRegistros" ) {

 		db_inicio_transacao();
 		
 		$sWhereExcluir  = "     rh89_anousu = {$oParam->iAnoUsu}";
 		$sWhereExcluir .= " and rh89_mesusu = {$oParam->iMesUsu}";
 		
 	  $clRHAutonomoLanc->excluir(null,$sWhereExcluir);
      
    if ( $clRHAutonomoLanc->erro_status == 0 ) {
      throw new Exception($clRHAutonomoLanc->erro_msg);   
    }
    
 		foreach ( $oParam->aListaAutonomos as $oAutonomo ) {
 			
      $clRHAutonomoLanc->rh89_numcgm       = $oAutonomo->numcgm;
      $clRHAutonomoLanc->rh89_anousu       = $oParam->iAnoUsu;
      $clRHAutonomoLanc->rh89_mesusu       = $oParam->iMesUsu;
      $clRHAutonomoLanc->rh89_codord       = $oAutonomo->codord;
      $clRHAutonomoLanc->rh89_dataliq      = $oAutonomo->data_liquidacao;
      $clRHAutonomoLanc->rh89_valorretinss = $oAutonomo->valor_inss;
      $clRHAutonomoLanc->rh89_valorretirrf = $oAutonomo->valor_irrf;
      $clRHAutonomoLanc->rh89_valorserv    = $oAutonomo->valor_servico;
      $clRHAutonomoLanc->rh89_instit       = db_getsession('DB_instit');
      $clRHAutonomoLanc->rh89_processado   = 'false';
       			
      $clRHAutonomoLanc->incluir(null);
      
      if ( $clRHAutonomoLanc->erro_status == 0 ) {
        throw new Exception($clRHAutonomoLanc->erro_msg);  	
      }
 		}
 		
 		db_fim_transacao();

 		
 		
  } else if ( $oParam->sMethod == "verificaGeracaoSefip" ) { 		
 		
    // Verifica se j� existe sefip gerado para a compet�ncia informada
    $sWhereVerificaSefip  = "        rh90_ativa is true               ";
    $sWhereVerificaSefip .= "    and rh90_anousu = {$oParam->iAnoUsu} "; 
    $sWhereVerificaSefip .= "    and rh90_mesusu = {$oParam->iMesUsu} ";
    $sWhereVerificaSefip .= "    and rh90_instit = ".db_getsession("DB_instit");
    
    $rsVerificaSefip      = $clRHSefip->sql_record($clRHSefip->sql_query_file(null,"*",null,$sWhereVerificaSefip));
    
    if ( $clRHSefip->numrows > 0 ) {
      $lGerado = true;            
	    $oRetorno->iCodSefip = db_utils::fieldsMemory($rsVerificaSefip,0)->rh90_sequencial;
    } else {
    	$lGerado = false;
    }

    $oRetorno->lGerado   = $lGerado;
    
  } else if ( $oParam->sMethod == "cancelaGeracaoSefip" ) {     

    $iAnoUsu = $oParam->iAnoUsu;
    $iMesUsu = $oParam->iMesUsu;  	 
  	
    db_inicio_transacao();
    
    $sWhereSefip  = "     rh90_ativa is true       ";
    $sWhereSefip .= " and rh90_anousu = {$iAnoUsu} ";
    $sWhereSefip .= " and rh90_mesusu = {$iMesUsu} ";
    $sWhereSefip .= " and rh90_instit = ".db_getsession("DB_instit");
    
    $rsDadosSefip = $clRHSefip->sql_record($clRHSefip->sql_query_file(null,"rh90_sequencial",null,$sWhereSefip)); 
    
    if ( $clRHSefip->numrows > 0 ) {

    	$oSefip = db_utils::fieldsMemory($rsDadosSefip,0);
    	
    	// Exclui tabela de liga��o dos autonomos com a Sefip
    	
    	$clRHSefipRHAutonomoLanc->excluir(null," rh92_rhsefip = {$oSefip->rh90_sequencial} ");
          	
	    if ( $clRHSefipRHAutonomoLanc->erro_status == 0 ) {
	      throw new Exception($clRHSefipRHAutonomoLanc->erro_msg);   
	    }        	

	    // Altera a Sefip para ativa = true
	    
	    $clRHSefip->rh90_sequencial = $oSefip->rh90_sequencial;
	    $clRHSefip->rh90_ativa      = 'false';
	    $clRHSefip->alterar($oSefip->rh90_sequencial);

	    if ( $clRHSefip->erro_status == 0 ) {
	      throw new Exception($clRHSefip->erro_msg);   
	    }    

	    // Gera tabela de cancelamento da Sefip  
	    
	    $clRHSefipCancela->rh91_rhsefip    = $oSefip->rh90_sequencial;
	    $clRHSefipCancela->rh91_data       = date('Y-m-d',db_getsession('DB_datausu'));
	    $clRHSefipCancela->rh91_hora       = db_hora();
	    $clRHSefipCancela->rh91_id_usuario = db_getsession('DB_id_usuario');
	    $clRHSefipCancela->rh91_obs        = '';
	    
	    $clRHSefipCancela->incluir(null);
	    
      if ( $clRHSefipCancela->erro_status == 0 ) {
        throw new Exception($clRHSefipCancela->erro_msg);   
      }    	    
	    
    } else {
    	throw new Exception("Gera��o da Sefip para a compet�ncia {$iMesUsu} / {$iAnoUsu} n�o encontrada!");
    }
    
    db_fim_transacao();

  } else if ( $oParam->sMethod == "downloadAquivo" ) {    
  
	  db_inicio_transacao();
	  
	  $rsOidArquivo    = $clRHSefip->sql_record($clRHSefip->sql_query_file($oParam->iCodSefip,"rh90_arquivo"));
	  $iOidArquivo     = db_utils::fieldsMemory($rsOidArquivo,0)->rh90_arquivo;
	  
    $sCaminhoArquivo = "tmp/SEFIP.RE";
    $lGeraArquivo    = pg_lo_export($iOidArquivo,$sCaminhoArquivo,$conn);
             
    if (!$lGeraArquivo) {
    	throw new Exception("Erro ao gerar arquivo SEFIP");       
    }
    
    db_fim_transacao();

    $oRetorno->sCaminhoArquivo = $sCaminhoArquivo;
    
	}  
  
} catch ( Exception $eException ) {

	$oRetorno->iStatus = 2;
	$oRetorno->sMsg    = urlencode(str_replace("\\n","\n",$eException->getMessage()));
	
	if ( db_utils::inTransaction() ) {
		db_fim_transacao(true);
	}
	
}

echo $oJson->encode($oRetorno);

?>