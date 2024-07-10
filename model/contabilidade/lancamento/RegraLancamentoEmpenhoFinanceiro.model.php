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

require_once("interfaces/IRegraLancamentoContabil.interface.php");

/**
 * Model que responsavel por localizar as contas credito/debito para que seja executado o
 * lancamento contabil
 * @author     Matheus Felini <matheus.felini@dbseller.com.br>
 * @package    contabilidade
 * @subpackage lancamento
 * @version    $Revision: 1.2 $
 */
class RegraLancamentoEmpenhoFinanceiro implements IRegraLancamentoContabil {

  /**
   * @see IRegraLancamentoContabil::getRegraLancamento()
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oDaoTransacao     = db_utils::getDao('contranslr');
    $sWhere            = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere           .= " and c45_anousu      = ".db_getsession("DB_anousu");
    $sWhere           .= " and c46_seqtranslan = {$iCodigoLancamento}";
    $sSqlTransacao     = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
    $rsTransacao       = $oDaoTransacao->sql_record($sSqlTransacao);
    $iTotalLancamentos = $oDaoTransacao->numrows;

    /**
     * Percorremos as contas cadastradas para descobrir qual a conta credito sera executado o documento
     */
    for ($i = 0; $i < $oDaoTransacao->numrows; $i++) {

      $oDadosTransacao =  db_utils::fieldsMemory($rsTransacao, $i);
      
      if ($oDadosTransacao->c47_ref == 0 || ($oDadosTransacao->c47_ref != 0 && $oDadosTransacao->c47_ref == $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getTipoCompra()) && $oDadosTransacao->c46_ordem == 1) {
      
        return new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
      }
      
      if ($oDadosTransacao->c46_ordem != 1 &&  !empty($oDadosTransacao->c47_seqtranslr)) {
      	return new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
      }
      
    }

    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    return false;
  }

}