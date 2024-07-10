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
 * Tipo de movimentacao do estque
 *
 * @package estoque
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class TipoMovimentacaoEstoque {

  const ENTRADA          = 1;
  const SAIDA            = 2;
  const EM_TRANSFERENCIA = 4;

  /**
   * C�digo do tipo de movimenta��o
   *
   * @var mixed
   * @access private
   */
  private $iCodigo;

  /**
   * Descricao do tipo de movimentacao
   *
   * @var mixed
   * @access private
   */
  private $sDescricao;

  /**
   * Classificao do tipo
   * entrada, saida ou em transferencia
   *
   * @var integer
   * @access private
   */
  private $iClassificacao;

  /**
   * Constr�i o objeto com os dados
   * @param integer $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    /**
     * C�digo do tipo de movimenta��o n�o informado
     */
    if (empty($iCodigo)) {
      return false;
    }

    $oDaoMatestoquetipo   = db_utils::getDao('matestoquetipo');
    $sSqlTipoMovimentacao = $oDaoMatestoquetipo->sql_query_file($iCodigo);
    $rsTipoMovimentacao   = $oDaoMatestoquetipo->sql_record($sSqlTipoMovimentacao);

    if ($oDaoMatestoquetipo->erro_status == '0') {
      throw new Exception($oDaoMatestoquetipo->erro_msg);
    }

    $oDadosTipoMovimentacao = db_utils::fieldsMemory($rsTipoMovimentacao, 0);

    $this->iCodigo        = (int) $iCodigo;
    $this->sDescricao     = $oDadosTipoMovimentacao->m81_descr;
    $this->iClassificacao = (int) $oDadosTipoMovimentacao->m81_tipo;
  }

  /**
   * Retorna o codigo do tipo de movimenta��o *
   * @access public
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descricao do tipo de movimenta��o
   *
   * @access public
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna a classifica��o do tipo de movimenta��o
   * entrada, saida ou em tranferencia
   *
   * @access public
   * @return integer
   */
  public function getClassificacao() {
    return $this->iClassificacao;
  }

}