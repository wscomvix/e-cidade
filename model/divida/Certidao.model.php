<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
 *  Model para controle das CDA's
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package divida
 */
class Certidao {

  /**
   * Sequencial da Certidao
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Date de Emiss�o
   * @var date
   */
  private $dDataEmissao = null;

  /**
   * Oid do par�grafo da Certid�o
   * @var oid
   */
  private $iOid = null;

  /**
   * Usu�rio que emitiu a Certid�o
   * @var UsuarioSistema
   */
  private $oUsuario = null;

  /**
   * Institui��o da Certid�o
   * @var Instituicao
   */
  private $oInstituicao = null;

  /**
   * Array de Inconsistencias
   * @var array
   */
  private $aInconsistencias = array();

  /**
   * Construtor da classe
   *
   * @param integer $iSequencial C�digo da Certid�o
   */
  public function __construct( $iSequencial = null ) {

    $oDaoCertidao = new cl_certid;
    $rsCertidao   = null;

    if ( !is_null($iSequencial) ) {

      $sSqlCertidao = $oDaoCertidao->sql_query_file($iSequencial);
      $rsCertidao   = $oDaoCertidao->sql_record($sSqlCertidao);
    }

    if ( !empty($rsCertidao) ) {

      $oCertidao = db_utils::fieldsMemory($rsCertidao, 0);

      $this->iSequencial  = $oCertidao->v13_certid;
      $this->dDataEmissao = $oCertidao->v13_dtemis;
      $this->iOid         = $oCertidao->v13_memo;
      $this->oUsuario     = new UsuarioSistema( $oCertidao->v13_login );
      $this->oInstituicao = new Instituicao( $oCertidao->v13_instit  );
    }
  }

  /**
   * Validamos se a Certid�o est� em cobran�a extrajudicial
   * @return boolean
   */
  public function isCobrancaExtrajudicial() {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );

    if( $rsCertidao ){

      $oCertidao    = db_utils::fieldsMemory( $rsCertidao, 0 );
      if ( isset($oCertidao->v32_tipo ) ) {

        if ( $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO ||
             $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_PROTESTADO ) {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Validamos se em algum momento a Certid�o possuiu cobran�a extrajudicial
   * @return boolean
   */
  public function hasCobrancaExtrajudicial() {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );

    if( $rsCertidao ){

      if($oDaoCertidao->numrows > 0){
        return true;
      }
    }

    return false;
  }

  /**
   * Buscamos os dados do d�bito de divida
   * @return array
   */
  public function getNumpreNumpar() {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_queryCertidao($this->iSequencial, "certidao", "numpre, numpar, numcgm");
    $rsCertidao   = $oDaoCertidao->sql_record($sSqlCertidao);

    return db_utils::getCollectionByRecord($rsCertidao);
  }

  /**
   * Comparamos a data da movimenta��o atual com a anterior
   *
   * @param  DBDate $oDataMovimentacao
   * @return boolean
   */
  public function validaDataMovimentacao( DBDate $oDataMovimentacao ) {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSqlCertidao );
    $oCertidao    = db_utils::fieldsMemory( $rsCertidao, 0 );

    if ( !empty($oCertidao) ) {

      $oDataMovimentacaoAnterior = new DBDate( db_formatar($oCertidao->v32_datamovimentacao, 'd') );
      $iResultado  = DBDate::calculaIntervaloEntreDatas( $oDataMovimentacao, $oDataMovimentacaoAnterior, 'd' );

      if ( $iResultado < 0 ) {
        return false;
      }
    }

    return true;
  }

  /**
   * Retorna ultima data de movimenta��o da certidao
   *
   * @todo mover para movimentacao
   * @return string data de movimenta��o
   */
  public function getDataUltimaMovimentacao(){

    $oDaoCertidao = new cl_certid;
    $sSqlDataUltimaMovimentacao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $rsDataUltimaMovimentacao   = $oDaoCertidao->sql_record( $sSqlDataUltimaMovimentacao );
    if( $rsDataUltimaMovimentacao ){

      $sDataUltimaMovimentacao  = db_utils::fieldsMemory( $rsDataUltimaMovimentacao, 0 )->v32_datamovimentacao;
    }

    return db_formatar($sDataUltimaMovimentacao, 'd');
  }

  /**
   * Validamos o tipo de movimenta��o
   *
   * @param  integer $iTipo tipo de movimenta��o
   * @return boolean
   */
  public function validaTipoMovimentacao( $iTipo ) {

    $oDaoCertidao = new cl_certid;
    $sSqlCertidao = $oDaoCertidao->sql_query_movimentacao( $this->iSequencial );
    $oCertidao    = db_utils::fieldsMemory( $oDaoCertidao->sql_record($sSqlCertidao), 0 );

    if ( !empty($oCertidao) ) {

      if ( $iTipo               == CertidMovimentacao::TIPO_MOVIMENTACAO_PROTESTADO &&
           $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO) {
        return true;
      }

      if ( $iTipo               == CertidMovimentacao::TIPO_MOVIMENTACAO_RESGATADO &&
           ( $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_ENVIADO ||
             $oCertidao->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_PROTESTADO ) ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Buscamos os dados da certid�o no arrecad
   *
   * @param  string $sCampos
   * @return array/boolean
   */
  public function getArrecad($sCampos) {

    $oDaoCertidao = new cl_certid;
    $sSql         = $oDaoCertidao->sql_query_arrecad($sCampos, $this->iSequencial);
    $rsCertidao   = $oDaoCertidao->sql_record($sSql);

    return db_utils::getCollectionByRecord($rsCertidao);
  }

  /**
   * Buscamos o c�digo da inicial da certid�o, caso exista
   * @return integer/boolean
   */
  public function getInicial() {

    $oDaoCertidao = new cl_certid;
    $sSql         = $oDaoCertidao->sql_query_inicial_cda( $this->iSequencial );
    $rsCertidao   = $oDaoCertidao->sql_record( $sSql );
    $oCertidao    = db_utils::fieldsMemory($rsCertidao, 0);

    if ( empty($oCertidao->v51_inicial) ) {
      return false;
    }

    return $oCertidao->v51_inicial;
  }

  /**
   * Buscamos os abatimentos vinculados aos d�bitos da CDA
   *
   * @param  string  $sCampos
   * @param  integer $iTipoAbatimento
   *
   * @return array/boolean
   */
  public function getAbatimento( $sCampos = "*", $iTipoAbatimento = 1 ) {

    $oDaoCertidao = new cl_certid;
    $sSql         = $oDaoCertidao->sql_query_abatimento( $this->iSequencial, $sCampos, $iTipoAbatimento );
    $rsCertidao   = $oDaoCertidao->sql_record($sSql);

    if ( empty($rsCertidao) ) {
      return false;
    }

    return db_utils::getCollectionByRecord($rsCertidao);
  }

  /**
   * Busca o c�digo senquencial da certid�o
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca a data de emiss�o
   * @return date
   */
  public function getDataEmissao() {
    return $this->dDataEmissao;
  }

  /**
   * Altera a date de emiss�o
   * @param date
   */
  public function setDateEmissao( $dDataEmissao ) {
    $this->dDataEmissao = $dDataEmissao;
  }

  /**
   * Busca o oid do par�grafo da certid�o
   * @return integer
   */
  public function getOid() {
    return $this->iOid;
  }

  /**
   * Altera o oid da certid�o
   * @param integer
   */
  public function setOid( $iOid) {
    $this->iOid = $iOid;
  }

  /**
   * Busca o Usu�rio da Certid�o
   * @return UsuarioSistema
   */
  public function getUsuario() {
    return $this->oUsuario;
  }

  /**
   * Altera o Usu�rio da certid�o
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario( UsuarioSistema $oUsuario ) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Busca a Institui��o
   * @return Institui��o
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Altera a Institui��o da certid�o
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao( Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Busca as inconsistencias do recibo
   * @return array
   */
  public function getInconsistencias() {
    return $this->aInconsistencias;
  }
}