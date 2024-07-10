<?php
require_once ('ArquivoSiprevBase.model.php');
require_once ('ArquivoSiprevOrgao.model.php');

class ArquivoSiprevCarreira extends  ArquivoSiprevBase {
  
  protected $sNomeArquivo = "carreiras";
  //protected $sCnpj        = "00711026041";
  
  /*
   * Essa classe n�o possui um metodo getDados Proprio,
   * para tanto, percorremos o retorno do metodo getDados da classe ArquivoSiprevOrgao
   */
  public function getDados() {
  	
  	$oDadosOrgao                   = new ArquivoSiprevOrgao();
  	$aDadosOrgao                   = $oDadosOrgao->getDados(); 
  	$aDadosCarreira                = array();
  	foreach ($aDadosOrgao as $oIndiceDados => $oValorDados) {
  		
  		$oLinha                      = new stdClass();
      $oLinha->dadosCarreira       = new stdClass();
      $oLinha->orgao               = new stdClass();
      // Dados Carreira
      $oLinha->dadosCarreira->nome = "Servidor P�blico";
      // Orgao Vinculo
      $oLinha->dadosCarreira->orgao->nome  = $oValorDados->dadosOrgao->nome;
      $oLinha->dadosCarreira->orgao->poder = $oValorDados->dadosOrgao->poder;
       
      $aDadosCarreira[]            = $oLinha;
  	}
  	return $aDadosCarreira;
  	
  }
  
  /*
   * Esse m�todo � respons�vel por definir quais os elementos e suas propriedades que ser�o 
   * repassadas para o arquivo que ser� gerado
   */
  public  function getElementos(){

  	$aDados         = array();
    $aDadosCarreira = array("nome"         => "dadosCarreira",             
                            "propriedades" => Array( "nome",
                                                     Array( "nome"         => "orgao",
                                                            "propriedades" => Array("nome",
                                                                                    "poder"
                                                                                    ) 
                                                           ) 
                                                    )                          
                            );
    $aDados[]       = $aDadosCarreira;    
    return $aDados;    
    	
  }
  
  
  
}  
?>
