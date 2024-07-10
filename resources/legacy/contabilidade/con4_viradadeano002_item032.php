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

require_once("classes/db_avaliacaoestruturanota_classe.php");
require_once("classes/db_avaliacaoestruturaregra_classe.php");

if ($sqlerro == false) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);
  
  /**
   * Buscamos os dados da tabela avaliacaoestruturanota a serem importados
   */
  $oDaoAvaliacaoEstruturaNota   = db_utils::getDao("avaliacaoestruturanota");
  $sWhereAvaliacaoEstruturaNota = "ed315_ano = {$iAnoOrigem}";
  $sSqlAvaliacaoEstruturaNota   = $oDaoAvaliacaoEstruturaNota->sql_query_file(
                                                                              null,
                                                                              "*",
                                                                              null,
                                                                              $sWhereAvaliacaoEstruturaNota
                                                                             );
  $rsAvaliacaoEstruturaNota      = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);
  $iLinhasAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNota->numrows;
  
  if ($iLinhasAvaliacaoEstruturaNota > 0) {
    
    for ($iContadorNota = 0; $iContadorNota < $iLinhasAvaliacaoEstruturaNota; $iContadorNota++) {
      
      $oDadosEstruturaNota                = db_utils::fieldsMemory($rsAvaliacaoEstruturaNota, $iContadorNota);
      $oDaoAvaliacaoEstruturaNotaMigracao = db_utils::getDao("avaliacaoestruturanota");
      
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_escola         = $oDadosEstruturaNota->ed315_escola;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_db_estrutura   = $oDadosEstruturaNota->ed315_db_estrutura;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ativo          = $oDadosEstruturaNota->ed315_ativo ? 'true':'false';
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_arredondamedia = $oDadosEstruturaNota->ed315_arredondamedia ? 'true':'false';
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_observacao     = $oDadosEstruturaNota->ed315_observacao;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ano            = $iAnoDestino;
      $oDaoAvaliacaoEstruturaNotaMigracao->incluir(null);
      $iCodigoAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNotaMigracao->ed315_sequencial;
      
      if ($oDaoAvaliacaoEstruturaNotaMigracao->erro_status == "0") {
        
        $sqlerro   = true;
        $erro_msg .= $oDaoAvaliacaoEstruturaNotaMigracao->erro_msg;
        
      }
      
      /**
       * Buscamos os dados da tabela avaliacaoestruturaregra, que tenham alguma configuracao de nota migrada, vinculada
       */
      $oDaoAvaliacaoEstruturaRegra   = db_utils::getDao("avaliacaoestruturaregra");
      $sWhereAvaliacaoEstruturaRegra = "ed318_avaliacaoestruturanota = {$oDadosEstruturaNota->ed315_sequencial}";
      $sSqlAvaliacaoEstruturaRegra   = $oDaoAvaliacaoEstruturaRegra->sql_query_file(
                                                                                    null,
                                                                                    "*",
                                                                                    null,
                                                                                    $sWhereAvaliacaoEstruturaRegra
                                                                                   );
      $rsAvaliacaoEstruturaRegra      = $oDaoAvaliacaoEstruturaRegra->sql_record($sSqlAvaliacaoEstruturaRegra);
      $iLinhasAvaliacaoEstruturaRegra = $oDaoAvaliacaoEstruturaRegra->numrows;
      
      if ($iLinhasAvaliacaoEstruturaRegra > 0) {
        
        for ($iContadorRegra = 0; $iContadorRegra < $iLinhasAvaliacaoEstruturaRegra; $iContadorRegra++) {
          
          $oDadosEstruturaRegra                = db_utils::fieldsMemory($rsAvaliacaoEstruturaRegra, $iContadorRegra);
          $oDaoAvaliacaoEstruturaRegraMigracao = db_utils::getDao("avaliacaoestruturaregra");
          
          $oDaoAvaliacaoEstruturaRegraMigracao->ed318_avaliacaoestruturanota = $iCodigoAvaliacaoEstruturaNota;
          $oDaoAvaliacaoEstruturaRegraMigracao->ed318_regraarredondamento    = $oDadosEstruturaRegra->ed318_regraarredondamento;
          $oDaoAvaliacaoEstruturaRegraMigracao->incluir(null);
          
          if ($oDaoAvaliacaoEstruturaRegraMigracao->erro_status == "0") {
            
            $sqlerro   = true;
            $erro_msg .= $oDaoAvaliacaoEstruturaRegraMigracao->erro_msg;
          }
          
          unset($oDadosEstruturaRegra);
          unset($oDaoAvaliacaoEstruturaRegraMigracao);
        }
      } else {
    
        if ($iLinhasAvaliacaoEstruturaRegra == 0) {
          
          $cldb_viradaitemlog->c35_log  = "N�o existe regra de arredondamento vinculada a configura��o da nota para";
          $cldb_viradaitemlog->c35_log .= " migra��o para o ano de destino $iAnoDestino";
        }
        
        $cldb_viradaitemlog->c35_codarq        = 893;
        $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
        $cldb_viradaitemlog->c35_data          = date("Y-m-d");
        $cldb_viradaitemlog->c35_hora          = date("H:i");
        $cldb_viradaitemlog->incluir(null);
        
        if ($cldb_viradaitemlog->erro_status == 0) {
          
          $sqlerro   = true;
          $erro_msg .= $cldb_viradaitemlog->erro_msg;
        }
      }
      
      unset($oDadosEstruturaNota);
      unset($oDaoAvaliacaoEstruturaNota);
    }
  } else {
    
    if ($iLinhasAvaliacaoEstruturaRegra == 0) {
      $cldb_viradaitemlog->c35_log = "N�o existem configura��es de nota para migra��o para o ano de destino $iAnoDestino";
    }
    
    $cldb_viradaitemlog->c35_codarq        = 893;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    
    if ($cldb_viradaitemlog->erro_status == 0) {
      
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
  
  db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
}
?>