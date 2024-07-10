<?php

class cronogramaFinancerio {
  
  /**
   * Codigo da Perspectiva
   *
   * @var integer
   */
  protected $iPerspectiva;

  /**
   * Ano do Cronrograma
   *
   * @var iAno
   */
  protected $iAno;
  
  /**
   * descri��o do cronograma
   *
   * @var string
   */
  protected $sDescricao;
  
  /**
   * instiui��es utilizadas
   *
   * @var array
   */
  protected $aInstituicoes = array();
  
  protected $aReceitas = array();
  /**
   * 
   */
  
  function __construct($iPerspectiva) {
    
    if ($iPerspectiva != "") {
      
      $oDaoCronogramaPerspectiva = db_utils::getDao("cronogramaperspectiva");
      $sWhere                    = "o124_sequencial = {$iPerspectiva} and o123_situacao = 1"; 
      $sSqlDadosCronograma       = $oDaoCronogramaPerspectiva->sql_query_integracao(null,"*", null, $sWhere);
      $rsDadosCronograma         = $oDaoCronogramaPerspectiva->sql_record($sSqlDadosCronograma);
      
      if ($oDaoCronogramaPerspectiva->numrows > 0) {

        $this->iPerspectiva   = $iPerspectiva;     
        $oDadosCronograma = db_utils::fieldsMemory($rsDadosCronograma,0,false,false);
        $this->sDescricao = $oDadosCronograma->o124_descricao; 
        $this->iAno       = $oDadosCronograma->o124_ano;
        $this->setInstituicoes(array(db_getsession("DB_instit")));
         
      }
    }
  }
  
  /**
   * @return array
   */
  public function getInstituicoes() {

    return $this->aInstituicoes;
  }
  
  /**
   * @param array $aInstituicoes
   */
  public function setInstituicoes($aInstituicoes) {

    $this->aInstituicoes = $aInstituicoes;
  }
  
  /**
   * @return iAno
   */
  public function getAno() {

    return $this->iAno;
  }
  
  /**
   * @return integer
   */
  public function getPerspectiva() {

    return $this->iPerspectiva;
  }
  
  /**
   * @return string
   */
  public function getDescricao() {

    return $this->sDescricao;
  }
  
  /**
   * Retornas as receitas cadastradas no ano;
   *
   * @return array 
   */
  protected  function getReceitas() {
    
     /**
      * Buscamos todas as Receitas do ano e das institui��es Marcadas
      */
    $aReceitas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcReceita  = db_utils::getDao("orcreceita");
    $sListaCampos  = "o57_fonte,          ";      
    $sListaCampos .= "o57_descr,          " ;
    $sListaCampos .= "o70_instit,         " ;
    $sListaCampos .= "o70_anousu,         " ;
    $sListaCampos .= "o70_concarpeculiar, " ;
    $sListaCampos .= "o70_codrec" ;
    $sWhere        = "o70_anousu = {$this->getAno()} ";
    $sWhere       .= " and o70_instit in({$sInstituicoes})";
       
    $sSqlReceita  = $oDaoOrcReceita->sql_query(null, null, $sListaCampos, "o57_fonte,o70_concarpeculiar",$sWhere);
    $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
    if ($oDaoOrcReceita->numrows == 0) {
      throw new Exception("N�o Existem Receitas cadastradas para o ano {$this->getAno()}");      
    }
    
    for ($i = 0; $i < $oDaoOrcReceita->numrows; $i++) {
      
      $oReceita                 = db_utils::fieldsMemory($rsReceita, $i);
      $oReceita->iPerspectiva   = $this->getPerspectiva();
      $oReceita->iSequencial    = null;
      
      $sHash                 = $oReceita->o70_codrec.$oReceita->o70_instit.$oReceita->o70_concarpeculiar;
      $aReceitas[$sHash]     = $oReceita; 
      
    }
    return $aReceitas;
  }
  
  public function CalcularBases($iTipo) {

    if ($iTipo == 1) {
      
      require_once("model/cronogramaBaseReceita.model.php");
      $aReceitas = $this->getReceitas();
      $iNumRows  = count($aReceitas);
      $oDaoCronogramaReceita = db_utils::getDao("cronogramaperspectivareceita");
      foreach ($aReceitas as $oReceita) {
        
        /**
         * Verificamos se nao existe a receita na perspectiva
         * Caso exista, apenas   
         */
        $sHash   = $oReceita->o70_codrec.$oReceita->o70_instit.$oReceita->o70_concarpeculiar;
        $sWhere  = "o126_cronogramaperspectiva = {$this->iPerspectiva}";
        $sWhere .= " and o126_codrec = {$oReceita->o70_codrec}";
        $sSqlVerificaReceita = $oDaoCronogramaReceita->sql_query_file(null,"*", null, $sWhere);
        $rsVerificaReceita   = $oDaoCronogramaReceita->sql_record($sSqlVerificaReceita);
        if ($oDaoCronogramaReceita->numrows > 0) {
          $aReceitas[$sHash]->iSequencial = db_utils::fieldsMemory($rsVerificaReceita, 0)->o126_sequencial;
        } else {
          
          $oDaoCronogramaReceita->o126_anousu                = $oReceita->o70_anousu;
          $oDaoCronogramaReceita->o126_codrec                = $oReceita->o70_codrec;
          $oDaoCronogramaReceita->o126_cronogramaperspectiva = $oReceita->iPerspectiva;
          $oDaoCronogramaReceita->incluir(null);
          if ($oDaoCronogramaReceita->erro_status == 0) {
            throw new Exception("N�o foi possivel incluir receita na Perspectiva\n{$oDaoCronogramaReceita->erro_msg}");
          }
          
          $aReceitas[$sHash]->iSequencial = $oDaoCronogramaReceita->o126_sequencial;
          $aReceitas[$sHash]->aBases      = new cronogramaBaseReceita($aReceitas[$sHash]);
          $aReceitas[$sHash]->aBases->calcularBases();
          
        }
      }
    }
  }
  
  public function getBaseReceitas($sStrutural= '', $iRecurso = '') {
    
    
    require_once("model/cronogramaBaseReceita.model.php");
    
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    
    $sWhere  = "o70_anousu = {$this->getAno()} ";
    $sWhere .= " and o70_instit in({$sInstituicoes})";
    $sWhere .= " and o126_cronogramaperspectiva = {$this->getPerspectiva()}";
    if (trim($sStrutural) != "") {
      $sWhere .= " and o57_fonte ilike '{$sStrutural}%'";
    }

    if (!empty($iRecurso)) {
      $sWhere .= " and c61_codigo = {$iRecurso}";
    }
    
    $aReceitas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcReceita  = db_utils::getDao("cronogramaperspectivareceita");
    $sListaCampos  = "o57_fonte,          ";      
    $sListaCampos .= "o57_descr,          " ;
    $sListaCampos .= "o70_instit,         " ;
    $sListaCampos .= "o70_anousu,         " ;
    $sListaCampos .= "o70_concarpeculiar, " ;
    $sListaCampos .= "o126_sequencial, " ;
    $sListaCampos .= "o70_codrec" ;
    $sSqlReceita  = $oDaoOrcReceita->sql_query_receita(null,$sListaCampos, "o57_fonte,o70_concarpeculiar",$sWhere);
    $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
    if ($oDaoOrcReceita->numrows == 0) {
      throw new Exception("N�o Existem Receitas cadastradas para o ano {$this->getAno()}");      
    }
    
    for ($i = 0; $i < $oDaoOrcReceita->numrows; $i++) {
      
      $oReceita                 = db_utils::fieldsMemory($rsReceita, $i,false,false,true);
      $oReceita->iPerspectiva   = $this->getPerspectiva();
      $oReceita->iSequencial    = null;
      
      $aReceitas[$i]     = $oReceita; 
      $aReceitas[$i]->iSequencial   = $oReceita->o126_sequencial;
      $aReceitas[$i]->aBases        = new cronogramaBaseReceita($aReceitas[$i]);
      $aReceitas[$i]->aBases->dados = $aReceitas[$i]->aBases->getDadosBase(); 
      
    }
    return $aReceitas;
  }
}
?>