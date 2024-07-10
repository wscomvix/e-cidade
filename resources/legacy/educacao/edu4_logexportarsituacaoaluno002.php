<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("fpdf151/pdf.php");

$oGet = db_utils::postMemory( $_GET );

/**
 * Cria a inst�ncia de Escola para preenchimento do cabe�alho
 */
$oEscola = EscolaRepository::getEscolaByCodigo( db_getsession( "DB_coddepto" ) );
$oJson   = new Services_JSON();

/**
 * L� o cont�udo do arquivo de log gerado
 */
$sArquivoLog  = file_get_contents( "{$oGet->sCaminhoArquivo}" );
$oJsonArquivo = $oJson->decode( $sArquivoLog );

/**
 * Define Largura e Altura padr�es para a linha do arquivo PDF
 */
$iLargura = 192;
$iAltura  = 4;
 
/**
 * Caso o atributo aLogs n�o tenha sido setado ou n�o existam logs gerados, apresenta a mensagem e redireciona para 
 * o formul�rio de importa��o
 */
if ( !isset( $oJsonArquivo->aLogs ) || count( $oJsonArquivo->aLogs ) == 0 ) {
  
  $sMensagem = "N�o foram encontrados dados com os filtros informados para gera��o do arquivo de log.";
  db_redireciona( "db_erros.php?fechar=true&db_erro={$sMensagem}" );
}

/**
 * Dados do cabe�alho
 */
$head1 = "EXPORTA��O SITUA��O DO ALUNO DO CENSO";
$head3 = "ESCOLA: {$oEscola->getCodigo()} - {$oEscola->getNome()}";
$head4 = "ANO: {$oGet->iAno}";
$head6 = "Registros com erros";

/**
 * Cria a inst�ncia de PDF e inicializa os m�todos padr�es
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 20);

$oPdf->addPage();
$oPdf->SetFont( "arial", "", 7 );
$oPdf->SetFillColor( 225, 225, 225 ); 

$iContador       = 0;
$iTotalRegistros = count($oJsonArquivo->aLogs);
foreach($oJsonArquivo->aLogs as $oErro) {

  $iPreenchimento = 0;
    
  if ( $iContador % 2 != 0 ) {
    $iPreenchimento = 1;
  }
  
  $oPdf->MultiCell($iLargura, $iAltura, utf8_decode($oErro->sErro), 0, 'L', $iPreenchimento);
  $iContador++;
}

$oPdf->Output();