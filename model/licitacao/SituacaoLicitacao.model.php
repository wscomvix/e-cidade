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

/**
 * Class SituacaoLicitacao
 * Controla as situa��es da licita��o
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package licitacao
 * @version $Revision: 1.1 $
 */
class SituacaoLicitacao {

  /**
   * C�digo Sequencial da Situa��o
   * @var integer
   */
  private $iCodigo;

  /**
   * Descri��o
   * @var string
   */
  private $sDescricao;

  /**
   * Define se a situa��o permite alterar os dados da licita��o
   * @var bool
   */
  private $lPermiteAlterar;

  /**
   * Define o caminho das mensagens utilizadas pelo objeto
   * @const string
   */
  const URL_MENSAGEM = "patrimonial.licitacao.SituacaoLicitacao.";

  /**
   * Constr�i o objeto
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo) {

    $oDaoLicSituacao   = new cl_licsituacao();
    $sSqlBuscaSituacao = $oDaoLicSituacao->sql_query_file($iCodigo);
    $rsBuscaSituacao   = $oDaoLicSituacao->sql_record($sSqlBuscaSituacao);
    if ($oDaoLicSituacao->erro_status == "0") {
      throw new BusinessException(_M(self::URL_MENSAGEM."licitacao_nao_encontrada"));
    }

    $oStdSituacao          = db_utils::fieldsMemory($rsBuscaSituacao, 0);
    $this->iCodigo         = $iCodigo;
    $this->sDescricao      = $oStdSituacao->l08_descr;
    $this->lPermiteAlterar = $oStdSituacao->l08_altera == "t" ? true : false;
    unset($oStdSituacao);
  }

  /**
   * Retorna o c�digo sequencial
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna se � permitido alterar
   * @return boolean
   */
  public function permiteAlterar() {
    return $this->lPermiteAlterar;
  }

  /**
   * Descri��o
   * @return string
   */
  public function getSDescricao() {
    return $this->sDescricao;
  }
}