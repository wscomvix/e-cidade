<?php
require_once("model/issqn/AlvaraMovimentacao.model.php");

/**
 * Enter description here...
 *
 */
class AlvaraCancelamento extends AlvaraMovimentacao {

	/**
	 * M�todo construtor. Seta qual o alvar� ser� alterado
	 * @param integer $iCodigoAlvara c�digo do alavar� que ser� cancelado
	 */
	function __construct($iCodigoAlvara) {
		parent::__construct($iCodigoAlvara);
	}
	
	function getUltimaMovimentacao() {
		
		try {
			
			$aMovimentacoes       = parent::getUltimaMovimentacao();
			$aGuardaMovimentacoes = array("0");
			foreach ($aMovimentacoes as $oMovimentacao) {
			  $aGuardaMovimentacoes [$oMovimentacao->q120_sequencial] = array(
			                                                              "q121_descr"           =>$oMovimentacao->q121_descr, 
			                                                              "q120_dtmov"           =>$oMovimentacao->q120_dtmov,
			                                                              "q120_sequencial"      =>$oMovimentacao->q120_sequencial,
			                                                              "q120_isstipomovalvara"=>$oMovimentacao->q120_isstipomovalvara
			                                                            );   
			}
			
			
			if (count($aGuardaMovimentacoes) == 0) {
				throw new Exception('Alvar� sem movimenta��es.');
			} else {
				return max($aGuardaMovimentacoes);
			}
		} catch (ErrorException $eErro){
	    throw new ErrorException($eErro->getMessage());
		}
	}
		
	/**
	 * Efetua o cancelamento gerando um novo registro identico ao movimento
	 * anterior ao que est� sendo cancelado
	 */
	function cancelaUltimaMovimentacao() {
		parent::salvar();
	}
	
}
?>