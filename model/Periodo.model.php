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
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 * Class PeriodoRelatorioContabil
 *
 * Respons�vel por controlar os per�odos dispon�veis no sistema
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.1 $
 */
final class Periodo {

  /**
   * C�digo
   * @var integer
   */
  private $iCodigo;

  /**
   * Descri��o
   * @var string
   */
  private $sDescricao;

  private $iDiaInicial;
  private $iDiaFinal;
  private $iMesInicial;
  private $iMesMesFinal;
  private $sSigla;

  /**
   * Carrega as informa��es do objeto
   * @throws BusinessException
   * @param $iCodigo
   */
  public function __construct($iCodigo) {

    $oDaoPeriodo    = new cl_periodo();
    $rsBuscaPeriodo = $oDaoPeriodo->sql_record($oDaoPeriodo->sql_query_file($iCodigo));
    if ($oDaoPeriodo->erro_status == "0") {
      throw new BusinessException("Per�odo [{$iCodigo}] n�o encontrado.");
    }
    $oStdPeriodo        = db_utils::fieldsMemory($rsBuscaPeriodo, 0);
    $this->iCodigo      = $iCodigo;
    $this->sDescricao   = $oStdPeriodo->o114_descricao;
    $this->iDiaInicial  = $oStdPeriodo->o114_diainicial;
    $this->iDiaFinal    = $oStdPeriodo->o114_diafinal;
    $this->iMesInicial  = $oStdPeriodo->o114_mesinicial;
    $this->iMesMesFinal = $oStdPeriodo->o114_mesfinal;
    $this->sSigla       = $oStdPeriodo->o114_sigla;
    unset($oStdPeriodo);
  }

  /**
   * Retorna o c�digo sequencial
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descri��o completa
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @return mixed
   */
  public function getDiaInicial()
  {
    return $this->iDiaInicial;
  }

  /**
   * @param mixed $iDiaInicial
   */
  public function setDiaInicial($iDiaInicial)
  {
    $this->iDiaInicial = $iDiaInicial;
  }

  /**
   * @return mixed
   */
  public function getDiaFinal()
  {
    return $this->iDiaFinal;
  }

  /**
   * @param mixed $iDiaFinal
   */
  public function setDiaFinal($iDiaFinal)
  {
    $this->iDiaFinal = $iDiaFinal;
  }

  /**
   * @return mixed
   */
  public function getMesInicial()
  {
    return $this->iMesInicial;
  }

  /**
   * @param mixed $iMesInicial
   */
  public function setMesInicial($iMesInicial)
  {
    $this->iMesInicial = $iMesInicial;
  }

  /**
   * @return mixed
   */
  public function getMesMesFinal()
  {
    return $this->iMesMesFinal;
  }

  /**
   * @param mixed $iMesMesFinal
   */
  public function setMesMesFinal($iMesMesFinal)
  {
    $this->iMesMesFinal = $iMesMesFinal;
  }

  /**
   * @return mixed
   */
  public function getSigla()
  {
    return $this->sSigla;
  }

  /**
   * @param mixed $sSigla
   */
  public function setSigla($sSigla)
  {
    $this->sSigla = $sSigla;
  }

  /**
   * Busca o per�odo do m�s
   * @param $iMes
   * @return $this
   * @throws BusinessException
   */
  public function getPeriodoByMes($iMes){
    $oDaoPeriodo    = new cl_periodo();
    $rsBuscaPeriodo = $oDaoPeriodo->sql_record($oDaoPeriodo->sql_query_file(null,"*",null,"o114_mesinicial = {$iMes} and o114_mesfinal = {$iMes}"));
    if ($oDaoPeriodo->erro_status == "0") {
      throw new BusinessException("Per�odo n�o encontrado.");
    }

    $oStdPeriodo        = db_utils::fieldsMemory($rsBuscaPeriodo, 0);
    $this->iCodigo      = $oStdPeriodo->o114_sequencial;
    $this->sDescricao   = $oStdPeriodo->o114_descricao;
    $this->iDiaInicial  = $oStdPeriodo->o114_diainicial;
    $this->iDiaFinal    = $oStdPeriodo->o114_diafinal;
    $this->iMesInicial  = $oStdPeriodo->o114_mesinicial;
    $this->iMesMesFinal = $oStdPeriodo->o114_mesfinal;
    $this->sSigla       = $oStdPeriodo->o114_sigla;
    unset($oStdPeriodo);
    return $this;
  }

}