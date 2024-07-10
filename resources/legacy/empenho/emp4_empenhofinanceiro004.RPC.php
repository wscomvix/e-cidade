<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");


$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$iInstituicaoSessao = db_getsession('DB_instit');

switch ($oParam->exec) {

  case "validarRecursoDotacaoPorAutorizacao":

    $oAutorizacaoEmpenho       = new AutorizacaoEmpenho($oParam->iCodigoAutorizacaoEmpenho);
    $iCodigoRecursoAutorizacao = $oAutorizacaoEmpenho->getDotacao()->getRecurso();
    $iCodigoFundebParametro    = ParametroCaixa::getCodigoRecursoFUNDEB($iInstituicaoSessao);

    $lRecursoFundeb = false;
    if ($iCodigoFundebParametro == $iCodigoRecursoAutorizacao) {
      $lRecursoFundeb = true;
    }

    $aFontesEmenda = array(
      551000, 552000, 553000, 569000, 570000, 571000, 576000, 576001, 600000, 601000, 602000, 603000, 621000, 631000, 632000, 660000, 661000, 665000, 700000, 700007, 701000, 710000, 706000 
    );

    $oRetorno->lEmendaParlamentar = in_array(substr($iCodigoRecursoAutorizacao, 1, 6), $aFontesEmenda);
    $oRetorno->lEmendaIndividual = in_array(substr($iCodigoRecursoAutorizacao, 1, 6), array('706000'));
    $oRetorno->lEmendaIndividualEBancada = in_array(substr($iCodigoRecursoAutorizacao, 1, 6), array('710000'));
    $oRetorno->lEsferaEmendaParlamentar = substr($iCodigoRecursoAutorizacao, 1, 6) == 665000 ? 't': 'f';

    $oRetorno->lFundeb = $lRecursoFundeb;
    break;

  case "getFinalidadePagamentoFundebEmpenho":

    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oParam->iSequencialEmpenho);
    $iRecursoDotacao    = $oEmpenhoFinanceiro->getDotacao()->getRecurso();
    $oFinalidade        = $oEmpenhoFinanceiro->getFinalidadePagamentoFundeb();
    $oAutorizacaoEmpenho       = new AutorizacaoEmpenho($oParam->iCodigoAutorizacaoEmpenho);
    $iCodigoRecursoAutorizacao = $oAutorizacaoEmpenho->getDotacao()->getRecurso();
    $iCodigoFundebParametro    = ParametroCaixa::getCodigoRecursoFUNDEB($iInstituicaoSessao);

    $lRecursoFundeb = false;
    if ($iCodigoFundebParametro == $iCodigoRecursoAutorizacao) {
      $lRecursoFundeb = true;
    }

    $aFontesEmenda = array(
        551000, 552000, 553000, 569000, 570000, 571000, 576000, 576001, 600000, 601000, 602000, 603000, 621000, 631000, 632000, 660000, 661000, 665000, 700000, 700007, 701000, 706000, 710000
    );

    $oRetorno->lFonte = substr($iCodigoRecursoAutorizacao, 1, 6);
    $oRetorno->lEmendaParlamentar = in_array(substr($iCodigoRecursoAutorizacao, 1, 6), $aFontesEmenda);
    $oRetorno->lEmendaIndividual = in_array(substr($iCodigoRecursoAutorizacao, 1, 6), array('706000'));
    $oRetorno->lEmendaIndividualEBancada = in_array(substr($iCodigoRecursoAutorizacao, 1, 6), array('710000'));
    $oRetorno->lEsferaEmendaParlamentar = substr($iCodigoRecursoAutorizacao, 1, 6) == 665000 ? 't': 'f';
    $oRetorno->lPossuiFinalidadePagamentoFundeb = false;
    if (!empty($oFinalidade)) {

      $oRetorno->lPossuiFinalidadePagamentoFundeb            = true;
      $oRetorno->oFinalidadePagamentoFundeb                  = new stdClass();
      $oRetorno->oFinalidadePagamentoFundeb->e151_sequencial = $oFinalidade->getCodigoSequencial();
      $oRetorno->oFinalidadePagamentoFundeb->e151_codigo     = $oFinalidade->getCodigo();
      $oRetorno->oFinalidadePagamentoFundeb->e151_descricao  = urlencode($oFinalidade->getDescricao());

    } else if ($iRecursoDotacao === ParametroCaixa::getCodigoRecursoFUNDEB($iInstituicaoSessao))  {

      $oRetorno->lPossuiFinalidadePagamentoFundeb = true;

    }

    break;

}
echo $oJson->encode($oRetorno);
?>