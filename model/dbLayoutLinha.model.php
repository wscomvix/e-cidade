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
 * Model utilizado para indentifica��o de um campo dentro de uma linha
 * @package configuracao
 * @author Felipe Nunes Ribeiro 
 * @revision $Author: dbandre.mello $
 * @version $Revision: 1.12 $    
 */
class DBLayoutLinha {
  
  private $sLinha;
  
  private $aPropriedadesCampos;
 	/**
   * Identifica se usa ou n�o o separador para quebrar a linha e determinar o valor do campo solicitado.
   * Se estiver setado para true e o separador for vazio, pega o valor pelas posi��o de in�cio do campo.
   * @var boolean
   */
  private $lUsaSeparador;
  
  private $lUsaChr;
  
  private $sNomeCampo = ''; 
  /**
   * @param string $sLinha              // String da linha
   * @param array  $aPropriedadesCampos // Array com as propriedades do campo 
   *                                       Ex:  aPropriedadesCampos[nome_campo_layout][posicao_inicial_linha]
   *                                            aPropriedadesCampos[nome_campo_layout][posicao_final_linha]
   *                                            aPropriedadesCampos[nome_campo_layout][separador_campos]
   *                                            aPropriedadesCampos[nome_campo_layout][indice_campo] 
   *                                            (indice_campo somente para quando se utilizar o separador)
   * @param boolean $lUsaSeparador Identifica se usa ou n�o o separador para quebrar a linha e determinar o valor 
   * do campo solicitado. Se estiver setado para true e o separador for vazio, pega o valor pelas posi��o de 
   * in�cio do campo.
   */
  function __construct($sLinha,$aPropriedadesCampos, $lUsaSeparador = false, $lUsaChr = false) {
    
    $this->sLinha              = $sLinha;
    $this->aPropriedadesCampos = $aPropriedadesCampos;
    $this->lUsaSeparador       = $lUsaSeparador;
    $this->lUsaChr             = $lUsaChr;
    $this->sNomeCampo          =  key($aPropriedadesCampos);
    
  }

  /**
   * M�todo m�gico utilizado para retornar o valor do campo dentro da linha
   *
   * @param  string $sName // Nome do Campo
   * @return string        // Conte�do do campo dentro da linha    
   */
  public function __get($sName){

    /**
    * Se estiver setado para usar o separador e o separador n�o for vazio.
    */  	
    if ($this->lUsaSeparador 
        && isset($this->aPropriedadesCampos[$sName][2]) 
        && !empty($this->aPropriedadesCampos[$sName][2])) {
      
      if ($this->lUsaChr) {
        eval('$s = '.$this->aPropriedadesCampos[$sName][2].';');
      }else{
        $s = $this->aPropriedadesCampos[$sName][2];
      }
      
      $aTmp = explode($s , $this->sLinha);
      $sValorRetorno = '';
      if (isset($aTmp[$this->aPropriedadesCampos[$sName][3]])) {
        $sValorRetorno = $aTmp[$this->aPropriedadesCampos[$sName][3]];
      }
      return $sValorRetorno;
           
    }
    
    $this->sNameTemp = $sName;
    $iPosIni = $this->aPropriedadesCampos[$sName][0];
    $iPosFim = $this->aPropriedadesCampos[$sName][1];
    
    return trim(substr($this->sLinha, ($iPosIni-1), $iPosFim));
    
  }

  /**
   * M�todo m�gico utizado para determinar se existe ou n�o um campo do layout
   *
   * @param  string $sName // Nome do Campo
   * @return boolean       // Existe ou n�o o campo no layout
   */
  public function __isset($sName){

    return isset($this->aPropriedadesCampos[$sName]);

  }

  /**
   * Retorna o nome do campo 
   *
   * @return unknown
   */
  function getNomeLinha() {
  	return $this->sNomeCampo;
  }
  
  public function getProperties() {
    return $this->aPropriedadesCampos;
  }
  
  public function getLinha() {
    return $this->sLinha;
  }
}

?>