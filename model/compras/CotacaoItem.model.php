<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe VO representando um item na cota��o
 * Class CotacaoItem
 * @author $Author: dbiuri $
 * @version $Revision: 1.2 $
 */

class CotacaoItem {

  /**
   * Item Cota��o
   *
   * @access private
   * @var ItemOrcamento
   */
  private $oItem;

  /**
   * Fornecedor que cotou o item
   *
   * @access private
   * @var CgmFisico|CgmJuridico
   */
  private $oFornecedor;

  /**
   * Valor unit�rio do item
   *
   * @access private
   * @var float
   */
  private $nValorUnitario = 0;

  /**
   * Quantidade Cotada
   *
   * @var float
   */
  private $nQuantidade = 0;

  /**
   * valor do desconto
   * @var float
   */
  private $nValorDesconto = 0;

  /**
   * Construtor da classe
   */
  function __construct() {

  }

  /**
   * Retorna o valor unit�rio da cota��o do item
   *
   * @access public
   * @return float
   */
  public function getValorUnitario() {
    return $this->nValorUnitario;
  }

  /**
   * Seta o valor unit�rio da cota��o do item
   *
   * @access public
   * @param float $nValorUnitario
   */
  public function setValorUnitario($nValorUnitario) {
    $this->nValorUnitario = $nValorUnitario;
  }

  /**
   * Retorna o fornecedor
   *
   * @access public
   * @return CgmFisico|CgmJuridico
   */
  public function getFornecedor() {
    return $this->oFornecedor;
  }

  /**
   * Seta o fornecedor
   *
   * @access public
   * @param CgmBase $oFornecedor
   */
  public function setFornecedor(CgmBase $oFornecedor) {
    $this->oFornecedor = $oFornecedor;
  }

  /**
   * Retorna o item
   *
   * @access public
   * @return ItemOrcamento
   */
  public function getItem() {
    return $this->oItem;
  }

  /**
   * Seta o item para ser cotado
   *
   * @access public
   * @param ItemOrcamento $oItem
   */
  public function setItem(ItemOrcamento $oItem) {
    $this->oItem = $oItem;
  }

  /**
   * Retorna a quantidade cotada
   *
   * @return float
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * Seta a quantidade cotada
   *
   * @param float $nQuantidade
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * Retorna a valor total cotada do item
   *
   * @return float
   */
  public function getValorTotal() {
    return round($this->getValorUnitario() * $this->getQuantidade(), 2);
  }

  /**
   * Retorna o valor do desconto concedido
   * @return float
   */
  public function getValorDesconto() {
    return $this->nValorDesconto;
  }

  /**
   * Define o valor do desconto concedido
   * @param float $nValorDesconto
   */
  public function setValorDesconto($nValorDesconto) {
    $this->nValorDesconto = $nValorDesconto;
  }



}