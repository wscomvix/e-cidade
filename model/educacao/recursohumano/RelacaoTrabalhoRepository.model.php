<?php

/**
 * Repositoy para as Rela��es de Trabalho
 * @package   Educacao
 * @author    Andr� Mello - andre.mello@dbseller.com.br
 */
class RelacaoTrabalhoRepository {

  const MENSAGEM_RELACAOTRABALHOREPOSITORY = "educacao.escola.RelacaoTrabalhoRepository.";

  private $aRelacoesTrabalho = array();

  private static $oInstance;

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna a inst�ncia do Repositorio
   * @return RelacaoTrabalhoRepository
   */
  protected function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new RelacaoTrabalhoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Retorna inst�ncia de Rela��o de Trabalho pelo c�digo informado
   * @param  integer $iRelacaoTrabalho C�digo da Rela��o de Trabalho
   * @return RelacaoTrabalho
   */
  public static function getRelacaoTrabalhoByCodigo( $iRelacaoTrabalho ) {

    if (!array_key_exists($iRelacaoTrabalho, RelacaoTrabalhoRepository::getInstance()->aRelacoesTrabalho)) {
      RelacaoTrabalhoRepository::getInstance()->aRelacoesTrabalho[$iRelacaoTrabalho] = new RelacaoTrabalho($iRelacaoTrabalho);
    }

    return RelacaoTrabalhoRepository::getInstance()->aRelacoesTrabalho[$iRelacaoTrabalho];
  }

  /**
   * Retorna as Rela��es de Trabalho de um Profissional da Escola
   * @param  ProfissionalEscola $oProfissionalEscola
   * @return RelacaoTrabalho[]
   */
  public static function getRelacaoTrabalhoByProfissionalEscola( ProfissionalEscola $oProfissionalEscola ) {

    $oDaoRelacaoTrabalho   = new cl_relacaotrabalho();
    $sWhereRelacaoTrabalho = " ed23_i_rechumanoescola = {$oProfissionalEscola->getCodigo()}";
    $sSqlRelacaoTrabalho   = $oDaoRelacaoTrabalho->sql_query_file( null, "ed23_i_codigo", null, $sWhereRelacaoTrabalho );
    $rsRelacaoTrabalho     = db_query( $sSqlRelacaoTrabalho );

    $oErro = new stdClass();
    if ( !$rsRelacaoTrabalho ) {

      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGEM_RELACAOTRABALHOREPOSITORY . "erro_buscar_relacao_trabalho", $oErro ) );
    }

    $iLinhas           = pg_num_rows($rsRelacaoTrabalho);
    $aRelacoesTrabalho = array();

    if ( $iLinhas > 0 ) {

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

        $iRelacaoTrabalho    = db_utils::fieldsMemory($rsRelacaoTrabalho, $iContador)->ed23_i_codigo;
        $aRelacoesTrabalho[] = RelacaoTrabalhoRepository::getRelacaoTrabalhoByCodigo( $iRelacaoTrabalho );
      }
    }

    return $aRelacoesTrabalho;
  }
}