<?php
/**
 * Classe respons�vel pela figura de Partilha.
 * @author vinicius.silva@dbseller.com.br
 */

/**
 * Carregamos as classes necess�rias para o funcionamento da classe.
 */
db_app::import('Taxa');
db_app::import('recibo');

class Partilha {

	/**
	 * C�digo da partilha.
	 * @var integer
	 */
	protected $iCodigoPartilha;

	/**
	 * C�digo do processo do foro.
	 * @var integer
	 */
	protected $iCodigoProcessoForo;

	/**
	 * Tipo do lan�amento.
	 * @var integer
	 */
	protected $iTipoLancamento;

	/**
	 * Data do pagamento.
	 * @var date
	 */
	protected $dDataPagamento;

	/**
	 * Observa��o da partilha.
	 * @var string
	 */
	protected $sObservacao;

	/**
	 * Recibo da partilha.
	 * @var Recibo
	 */
	protected $oRecibo;
	
	/**
	 * Collection de taxas
	 * @var array
	 */
	protected $aTaxas = array();

	/**
	 * M�todo construtor da classe.
	 */
	public function __construct($iCodigoPartilha) {

		if (empty($iCodigoProcessoForo)) {

			$oDaoProcessoForoPartilha      = db_utils::getDao("processoforopartilha");
			$oDaoProcessoForoPartilhaCusta = db_utils::getDao("processoforopartilhacusta");
			
			$sSqlProcessoForoPartilha      = $oDaoProcessoForoPartilha->sql_query_file($iCodigoPartilha);
			$rsProcessoForoPartilha        = $oDaoProcessoForoPartilha->sql_record($sSqlProcessoForoPartilha);

			if ( $oDaoProcessoForoPartilha->erro_status == "0" ) {
				
				$sErro = "Erro ao Buscar dados da Partilha: {$iCodigoPartilha}: \n".
				         $oDaoProcessoForoPartilha->erro_msg;
        throw new DBException($sErro);
			}

			/**
			 * Carregamos o resultado da busca da partilha e setamos as propriedades da classe com o mesmo.
			 */
			$oPartilha = db_utils::fieldsMemory($rsProcessoForoPartilha, 0);
			$this->setCodigoPartilha($oPartilha->v76_sequencial);
			$this->setCodigoProcessoForo($oPartilha->v76_processoforo);
			$this->setTipoLancamento($oPartilha->v76_tipolancamento);
			$this->setDataPagamento($oPartilha->v76_dtpagamento);
			$this->setObservacao($oPartilha->v76_obs);
			
			
			/**
			 * Buscamos as taxas/custas da partilha.
			 */
			$sSqlProcessoForoPartilhaCusta = $oDaoProcessoForoPartilhaCusta->sql_query_file(null, "*", null, 
			                                                                                " v77_processoforopartilha = 
			                                                                                {$this->getCodigoPartilha()} ");
      $rsProcessoForoPartilhaCusta   = $oDaoProcessoForoPartilhaCusta->sql_record($sSqlProcessoForoPartilhaCusta);
      
      if ($oDaoProcessoForoPartilhaCusta->erro_status == "0") {
        throw new DBException("Erro ao Buscar dados das taxas da Partilha");
      }
    	
      $aTaxas        = db_utils::getCollectionByRecord($rsProcessoForoPartilhaCusta);
    	$iNumpreRecibo = null;

    	foreach ($aTaxas as $oTaxa) {
    		
    		$this->adicionarTaxa(new Taxa($oTaxa->v77_taxa));
    		$iNumpreRecibo = $oTaxa->v77_numnov;
    	}
    	$this->setRecibo( new recibo(null, null, null, $iNumpreRecibo) );
		}
	}
	
	/**
	 * Setter do C�digo da Partilha.
	 * @param integer $iCodigoPartilha
	 */
	public function setCodigoPartilha($iCodigoPartilha) {
		$this->iCodigoPartilha = $iCodigoPartilha;
	}
	
	/**
	 * Getter do C�digo da Partilha.
	 * @return integer
	 */
	public function getCodigoPartilha() {
		return $this->iCodigoPartilha;
	}
	
	/**
	 * Setter do C�digo do Processo do Foro.
	 * @param integer $iCodigoProcessoForo
	 */
	public function setCodigoProcessoForo($iCodigoProcessoForo) {
		$this->iCodigoProcessoForo = $iCodigoProcessoForo;
	}
	
	/**
	 * Getter do C�digo do Processo do Foro.
	 * @return integer
	 */
	public function getCodigoProcessoForo() {
		return $this->iCodigoProcessoForo;
	}

	/**
	 * Setter do Tipo do Lan�amento.
	 * @param integer $iTipoLancamento
	 */
	public function setTipoLancamento($iTipoLancamento) {
		$this->iTipoLancamento = $iTipoLancamento;
	}
	
	/**
	 * Getter do Tipo do Lancamento.
	 * @return integer
	 */
	public function getTipoLancamento() {
		return $this->iTipoLancamento;
	}
	
	/**
	 * Setter da Data de Pagamento.
	 * @param date $dDataPagamento
	 */
	public function setDataPagamento($dDataPagamento) {
		$this->dDataPagamento = $dDataPagamento;
	}
	
	/**
	 * Getter da Data de Pagamento.
	 * @return date
	 */
	public function getDataPagamento() {
		return $this->dDataPagamento;
	}

	/**
	 * Setter da Observa��o da Partilha.
	 * @param string $sObservacao
	 */
	public function setObservacao($sObservacao) {
		$this->sObservacao = $sObservacao;
	}
	
	/**
	 * Getter da Observa��o da Partilha.
	 * @return string
	 */
	public function getObservacao() {
		return $this->sObservacao;
	}

	/**
	 * Setter do Recibo
	 * @param Recibo $oRecibo
	 */
	public function setRecibo($oRecibo) {
		$this->oRecibo = $oRecibo;
	}

	/**
	 * Getter do Recibo.
	 * @return Recibo
	 */
	public function getRecibo() {
		return $this->oRecibo;
	}
	
	/**
	 * M�todo que adiciona um �ndice � collection de taxas.
	 * @param Taxa $oTaxa
	 */
	public function adicionarTaxa(Taxa $oTaxa) {
		$this->aTaxas[$oTaxa->getCodigoTaxa()] = $oTaxa;
	}
	
	/**
	 * M�todo que remove um �ndice � collection de taxas.
	 */
	public function removerTaxa($iCodigoTaxa) {
		
	}
	
	/**
	 * Getter da collection de taxas.
	 * @return array
	 */
	public function getTaxas() {
		return $this->aTaxas;
	}
}