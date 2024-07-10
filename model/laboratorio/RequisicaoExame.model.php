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

define("MENSAGEM_REQUISICAO_EXAME", "saude.laboratorio.RequisicaoExame.");
/**
 * Exame de uma Requisicao
 * Class RequisicaoExame
 */
class RequisicaoExame {

  private $iCodigo = null;

  /**
   * Paciente da Solicitacao;
   * @var Cgs
   */
  private $oSolicitante = null;

  /**
   * Cpdigo do Exame que dever� ser executado
   * @var integer
   */
  protected $iCodigoExame;

  /**
   * Exame que dever� ser realizado
   * @var Exame
   */
  protected $oExame;

  /**
   * Situacao do Exame
   * @var string
   */
  protected $sSituacao = '';

  /**
   * Exame nao digitado
   * @var string
   */
  const NAO_DIGITADO = '1 - Nao Digitado';

  /**
   * Valores do exame foi lan�ando
   * @var string
   */
  const LANCADO = '2 - Lancado';

  /**
   * Exame FOi entrege ao solicitante
   * @var string
   */
  const ENTREGUE = '3 - Entregue';

  /**
   * Amostrados do exame foram coletadas
   */
  const COLETADO = '6 - Coletado';

  /**
   * Exame foi conferido e est� pronto para a entrega
   */
  const CONFERIDO = '7 - Conferido';

  /**
   * Exame autorizado
   */
  const AUTORIZADO = '8 - Autorizado';

  /**
   * Observa��o lan�ada para o ex�me
   * @var string
   */
  private $sObservacao;

  /**
   * Instancia o Exame
   * @param $iCodigo
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoRequisicao      = new cl_lab_requiitem();
      $sSqlExameRequisicao = $oDaoRequisicao->sql_query($iCodigo);
      $rsExameRequisicao   = $oDaoRequisicao->sql_record($sSqlExameRequisicao);
      if ($rsExameRequisicao && $oDaoRequisicao->numrows > 0) {
        
        $oDadosRequisicao = db_utils::fieldsMemory($rsExameRequisicao, 0);

        $this->oSolicitante = new Cgs($oDadosRequisicao->la22_i_cgs);
        $this->iCodigo      = $oDadosRequisicao->la21_i_codigo;
        $this->iCodigoExame = $oDadosRequisicao->la08_i_codigo;
        $this->sSituacao    = trim($oDadosRequisicao->la21_c_situacao);
        $this->sObservacao  = trim($oDadosRequisicao->la21_observacao);
      }
    }
  }

  /**
   * Retorna o codigo da requisicao
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o solicitante da requisicao
   * @param Cgs $oSolicitante Solictante da requisicao
   */
  public function setSolicitante(Cgs $oSolicitante) {
    $this->oSolicitante = $oSolicitante;
  }

  /**
   * Retorna o solicitante da Requisicao
   * @return Cgs
   */
  public function getSolicitante() {
    return $this->oSolicitante;
  }

  /**
   * Exame que dever� ser Realizado
   * @return Exame
   */
  public function getExame() {

    if (empty($this->oExame) && !empty($this->iCodigoExame)) {
      $this->oExame = new Exame($this->iCodigoExame);
    }
    return $this->oExame;
  }

  /**
   * Retorna o Resultado do exame
   * @return ResultadoExame
   */
  public function getResultado() {
    return new ResultadoExame($this);
  }

  /**
   * Define a situacao do exame
   *
   * @param string $sSituacao Sitaucao do Exame
   */
  public function setSituacao($sSituacao) {
    $this->sSituacao = $sSituacao;
  }

  /**
   * Retorna a situacao do exame
   * @return string
   */
  public function getSituacao() {
    return $this->sSituacao;
  }

  /**
   * Persiste os dados do exame
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoItemExame = new cl_lab_requiitem();
    if (!empty($this->iCodigo)) {

      $oDaoItemExame->la21_i_codigo   = $this->iCodigo;
      $oDaoItemExame->la21_c_situacao = $this->getSituacao();
      $oDaoItemExame->la21_observacao = $this->sObservacao;
      $oDaoItemExame->alterar($this->iCodigo);
      if ($oDaoItemExame->erro_status == 0) {
        throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_salvar" ) );
      }
    }
  }

  /**
   * Retorna o setor no qual o exame est� vinculado
   * @return Setor
   */
  public function getLaboratorioSetor() {

    $oDaoRequiItem = new cl_lab_requiitem();
    $sWhere        = "la21_i_codigo = {$this->iCodigo}";
    $sSqlRequiItem = $oDaoRequiItem->sql_query('', 'la24_i_setor', '', $sWhere);
    $rsRequiItem   = db_query( $sSqlRequiItem );

    if ( !$rsRequiItem ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsRequiItem );
      throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_buscar_setor", $oMensagem ) );
    }

    if ( pg_num_rows( $rsRequiItem ) == 0 ) {
      throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "setor_nao_encontrado") );
    }

    $iCodigoExame = db_utils::fieldsMemory( $rsRequiItem, 0 )->la24_i_setor;

    return new Setor( $iCodigoExame );
  }

  /**
   * Retorna a observa��o lan�ada para o ex�me
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observa��o ao exame
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
}