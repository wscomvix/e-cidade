<?php
/*
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


require_once('model/configuracao/DBEstruturaValor.model.php');

/**
 * Model para controle do grupo do material do estoque
 * Class MaterialGrupo
 */
class MaterialGrupo extends DBEstruturaValor
{

  /**
   * C�digo sequencial do grupo do material
   * @var integer
   */
  protected $iCodigoGrupo;

  /**
   * C�digo sequencial da conta cont�bil. Normalmente uma conta de ATIVO na contabilidade
   * @var integer
   */
  protected $iConta;

  /**
   * Ano da conta cont�bil / VPD
   * @var integer
   */
  protected $iAnoUsu;

  /**
   * Vari�vel de controle para sabermos se o grupo est� ativo
   * @var boolean
   */
  protected $lAtivo;

  /**
   * Descri��o do Tipo de Estrutural
   * @var string
   */
  protected $sTipoEstrutural = 'Grupo/Subgrupo';

  /**
   * Descri��o da conta cont�bil
   * @var string
   */
  protected $sDescricaoConta;

  /**
   * C�digo da conta referente a Varia��o Patrimonial Diminutiva
   * @var integer
   */
  protected $iCodigoContaVPD;

  /**
   * Conta do Plano de Contas referente ao VPD
   * @var ContaPlanoPCASP
   */
  protected $oPlanoContaVPD;

  /**
   * Conta do plano de contas referente � conta do grupo ATIVO
   * @var ContaPlanoPCASP
   */
  protected $oPlanoContaAtivo;

  /**
   * C�digo da conta referente a Transfer�ncia
   * @var integer
   */
  protected $iCodigoContaTransf;

  /**
   * C�digo da conta referente a Transfer�ncia VPD
   * @var integer
   */
  protected $iCodigoContaTransfVPD;

  /**
   * C�digo da conta referente a Doa��o
   * @var integer
   */
  protected $iCodigoContaDoacao;

  /**
   * C�digo da conta referente a Doa��o VPD
   * @var integer
   */
  protected $iCodigoContaDoacaoVPD;

  /**
   * C�digo da conta referente a Perda de Ativo
   * @var integer
   */
  protected $iCodigoContaPerdaAtivo;

  /**
   * C�digo da conta referente a Perda de Ativo VPD
   * @var integer
   */
  protected $iCodigoContaPerdaAtivoVPD;

  /**
   * C�digo cont�bil Cr�dito
   * @var integer
   */
  protected $iCodigoContaCredito;

  /**
   * C�digo da cont�bil D�bito
   * @var integer
   */
  protected $iCodigoContaDebito;


  const SAIDA_INVENTARIO    = 1;
  const SAIDA_TRANSFERENCIA = 2;
  const SAIDA_DOACAO        = 3;
  const SAIDA_PERDA_ATIVO   = 4;
  const SAIDA_OBRAS_ANDAMENTO   = 5;




  /**
   * C�digo sequencial do grupo do material
   * @param $iCodigoGrupo
   */
  public function __construct($iCodigoGrupo = null)
  {

    if (!empty($iCodigoGrupo)) {

      $oDaoGrupoMaterial = db_utils::getDao("materialestoquegrupo");

      $iAnoUsu        = db_getsession('DB_anousu');
      $sSqlDadosGrupo = $oDaoGrupoMaterial->sql_query_conta_ano($iCodigoGrupo, $iAnoUsu, "*");

      $rsDadosGrupo = $oDaoGrupoMaterial->sql_record($sSqlDadosGrupo);
      if ($oDaoGrupoMaterial->numrows > 0) {

        $oDadosGrupo = db_utils::fieldsMemory($rsDadosGrupo, 0);
        $this->iCodigoGrupo    = $iCodigoGrupo;
        $this->lAtivo          = $oDadosGrupo->m65_ativo == 't' ? true : false;
        $this->iConta          = $oDadosGrupo->m66_codcon;
        $this->sDescricaoConta = $oDadosGrupo->c60_descr;
        $this->iCodigoContaVPD = $oDadosGrupo->m66_codconvpd;
        $this->iCodigoContaTransf         = $oDadosGrupo->m66_codcontransf;
        $this->iCodigoContaTransfVPD      = $oDadosGrupo->m66_codcontransfvpd;
        $this->iCodigoContaDoacao         = $oDadosGrupo->m66_codcondoacao;
        $this->iCodigoContaDoacaoVPD      = $oDadosGrupo->m66_codcondoacaovpd;
        $this->iCodigoContaPerdaAtivo     = $oDadosGrupo->m66_codconperdaativo;
        $this->iCodigoContaCredito  = $oDadosGrupo->m66_codconcredito;
        $this->iCodigoContaDebito  = $oDadosGrupo->m66_codcondebito;
        $this->iAnoUsu         = $oDadosGrupo->m66_anousu;
        parent::__construct($oDadosGrupo->m65_db_estruturavalor);
        unset($oDadosGrupo);
      }
    }
    $this->tipo = __CLASS__;
  }

  /**
   * persiste os dados do grupo
   *
   * @return MaterialGrupo
   */
  public function salvar()
  {

    parent::salvar();
    $oDaoGrupoMaterial                        = db_utils::getDao("materialestoquegrupo");
    $oDaoGrupoMaterial->m65_ativo             = $this->lAtivo ? "true" : "false";
    $oDaoGrupoMaterial->m65_db_estruturavalor = $this->iCodigo;
    if (empty($this->iCodigoGrupo)) {

      $oDaoGrupoMaterial->incluir(null);
      $this->iCodigoGrupo = $oDaoGrupoMaterial->m65_sequencial;
    } else {

      $oDaoGrupoMaterial->m65_sequencial = $this->getCodigo();
      $oDaoGrupoMaterial->alterar($this->getCodigo());
    }
    if ($oDaoGrupoMaterial->erro_status == 0) {
      throw new Exception($oDaoGrupoMaterial->erro_msg);
    }

    /**
     * realiza o controle da conta.
     */
    $sWhere                 = "m66_materialestoquegrupo  = {$this->getCodigo()}";
    $oDaoGrupoMaterialConta = db_utils::getDao("materialestoquegrupoconta");
    $oDaoGrupoMaterialConta->excluir(null, $sWhere);
    if (!empty($this->iConta)) {

      $sSqlDadosConta = $oDaoGrupoMaterialConta->sql_query_file(null, "*", null, $sWhere);
      $rsDadosConta   = $oDaoGrupoMaterialConta->sql_record($sSqlDadosConta);
      $oDaoGrupoMaterialConta->m66_anousu               = db_getsession("DB_anousu");
      $oDaoGrupoMaterialConta->m66_codcon               = $this->getConta();
      $oDaoGrupoMaterialConta->m66_materialestoquegrupo = $this->getCodigo();
      $oDaoGrupoMaterialConta->m66_codconvpd            = $this->iCodigoContaVPD;
      $oDaoGrupoMaterialConta->m66_codcontransf         = $this->iCodigoContaTransf;
      $oDaoGrupoMaterialConta->m66_codcontransfvpd      = $this->iCodigoContaTransfVPD;
      $oDaoGrupoMaterialConta->m66_codcondoacao         = $this->iCodigoContaDoacao;
      $oDaoGrupoMaterialConta->m66_codcondoacaovpd      = $this->iCodigoContaDoacaoVPD;
      $oDaoGrupoMaterialConta->m66_codconperdaativo     = $this->iCodigoContaPerdaAtivo;
      $oDaoGrupoMaterialConta->m66_codconperdaativovpd  = $this->iCodigoContaPerdaAtivoVPD;
      $oDaoGrupoMaterialConta->m66_codconcredito     = $this->iCodigoContaCredito;
      $oDaoGrupoMaterialConta->m66_codcondebito  = $this->iCodigoContaDebito;

      if ($oDaoGrupoMaterialConta->numrows == 0) {
        $oDaoGrupoMaterialConta->incluir(null);
      } else {

        $iCodigoConta = db_utils::fieldsMemory($rsDadosConta, 0)->m66_sequencial;
        $oDaoGrupoMaterialConta->m66_sequencial = $iCodigoConta;
        $oDaoGrupoMaterialConta->alterar($iCodigoConta);
      }
    }

    if ($oDaoGrupoMaterialConta->erro_status == 0) {
      throw new Exception($oDaoGrupoMaterialConta->erro_msg);
    }
    return $this;
  }

  /**
   * define se o grupo est� ativo
   *
   * @param  boolean $lAtivo ativo/inativo
   * @return MaterialGrupo
   */
  public function setAtivo($lAtivo)
  {

    $this->lAtivo = $lAtivo;
    return $this;
  }

  /**
   * verifica se ogrupo est� ativo
   *
   * @return boolean
   */
  public function isAtivo()
  {
    return $this->lAtivo;
  }

  /**
   * Retorna o c�digo do grupo de acordo com o c�digo informado via par�metro
   * @param integer $iCodigoEstrutura
   * @return integer
   */
  static public function getCodigoByEstrutura($iCodigoEstrutura)
  {

    $iCodigoGrupo       = null;
    $oDaoGrupoMaterial  = db_utils::getDao("materialestoquegrupo");
    $sSqlCodigo         = $oDaoGrupoMaterial->sql_query_file(
      null,
      'm65_sequencial',
      null,
      "m65_db_estruturavalor={$iCodigoEstrutura}"
    );

    $rsCodigo  = $oDaoGrupoMaterial->sql_record($sSqlCodigo);
    if ($oDaoGrupoMaterial->numrows > 0) {
      $iCodigoGrupo = db_utils::fieldsMemory($rsCodigo, 0)->m65_sequencial;
    }
    return $iCodigoGrupo;
  }

  /**
   * Define a conta contabil
   * @param $iConta
   * @return MaterialGrupo
   */
  public function setConta($iConta)
  {

    $this->iConta = $iConta;
    return $this;
  }

  /**
   * retorna a conta cont�bil do grupo
   *@return integer
   */
  public function getConta()
  {
    return $this->iConta;
  }

  /**
   * Retorna o codigo do Grupo
   *
   * @return integer
   */
  public function getCodigo()
  {
    return $this->iCodigoGrupo;
  }
  /**
   * Retorna o codigo do Grupo
   *
   * @return integer
   */
  public function getCodigoEstrutural()
  {
    return $this->iCodigo;
  }

  /**
   * Retorna a Descri��o da Conta Cont�bil
   * @return string
   */
  public function getDescricaoConta()
  {
    return $this->sDescricaoConta;
  }

  /**
   * Retorna o c�digo da conta VPD (Varia��o Patrimonial Diminutiva)
   * @return integer
   */
  public function getCodigoContaVPD()
  {
    return $this->iCodigoContaVPD;
  }

  /**
   * Retorna o c�digo da conta VPD (Varia��o Patrimonial Diminutiva)
   * @return integer
   */
  public function setCodigoContaVPD($iCodigoContaVPD)
  {
    $this->iCodigoContaVPD = $iCodigoContaVPD;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Transfer�ncia
   * @return integer
   */
  public function getCodigoContaTransf()
  {
    return $this->iCodigoContaTransf;
  }

  /**
   * Retorna o c�digo da conta Transfer�ncia
   * @return object
   */
  public function setCodigoContaTransf($iCodigoConta)
  {
    $this->iCodigoContaTransf = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Transfer�ncia VPD
   * @return integer
   */
  public function getCodigoContaTransfVPD()
  {
    return $this->iCodigoContaTransfVPD;
  }

  /**
   * Retorna o c�digo da conta Transfer�ncia VPD
   * @return object
   */
  public function setCodigoContaTransfVPD($iCodigoConta)
  {
    $this->iCodigoContaTransfVPD = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Doa��o
   * @return integer
   */
  public function getCodigoContaDoacao()
  {
    return $this->iCodigoContaDoacao;
  }

  /**
   * Retorna o c�digo da conta Doa��o
   * @return object
   */
  public function setCodigoContaDoacao($iCodigoConta)
  {
    $this->iCodigoContaDoacao = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Doa��o VPD
   * @return integer
   */
  public function getCodigoContaDoacaoVPD()
  {
    return $this->iCodigoContaDoacaoVPD;
  }

  /**
   * Retorna o c�digo da conta Doa��o VPD
   * @return object
   */
  public function setCodigoContaDoacaoVPD($iCodigoConta)
  {
    $this->iCodigoContaDoacaoVPD = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Perda Ativo
   * @return integer
   */
  public function getCodigoContaPerdaAtivo()
  {
    return $this->iCodigoContaPerdaAtivo;
  }

  /**
   * Retorna o c�digo da conta Perda Ativo
   * @return object
   */
  public function setCodigoContaPerdaAtivo($iCodigoConta)
  {
    $this->iCodigoContaPerdaAtivo = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Perda Ativo VPD
   * @return integer
   */
  public function getCodigoContaPerdaAtivoVPD()
  {
    return $this->iCodigoContaPerdaAtivoVPD;
  }

  /**
   * Retorna o c�digo da conta Perda Ativo VPD
   * @return object
   */
  public function setCodigoContaPerdaAtivoVPD($iCodigoConta)
  {
    $this->iCodigoContaPerdaAtivoVPD = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta Cr�dito
   * @return integer
   */
  public function getCodigoContaCredito()
  {
    return $this->iCodigoContaCredito;
  }

  /**
   * Seta o c�digo da conta Cr�dito
   * @return object
   */
  public function setCodigoContaCredito($iCodigoConta)
  {
    $this->iCodigoContaCredito = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da conta D�bito
   * @return integer
   */
  public function getCodigoContaDebito()
  {
    return $this->iCodigoContaDebito;
  }

  /**
   * Seta o c�digo da conta D�bito
   * @return object
   */
  public function setCodigoContaDebito($iCodigoConta)
  {
    $this->iCodigoContaDebito = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna um objeto do tipo ContaPlanoPCASP
   * @return ContaPlanoPCASP
   */
  public function getContaVPD($iTipoSaida = 1)
  {

    $oContas = $this->contasParaSaida($iTipoSaida);

    if (!empty($oContas->codigoVPD)) {
      $this->oPlanoContaVPD = new ContaPlanoPCASP($oContas->codigoVPD, $this->iAnoUsu);
    }
    return $this->oPlanoContaVPD;
  }

  /**
   * Retorna um objeto do tipo ContaPlanoPCASP para a Conta Cont�bil
   * @return ContaPlanoPCASP
   */
  public function getContaAtivo($iTipoSaida = 1)
  {

    $oContas = $this->contasParaSaida($iTipoSaida);

    if (!empty($oContas->codigo)) {
      $this->oPlanoContaAtivo = new ContaPlanoPCASP($oContas->codigo, $this->iAnoUsu);
    }
    return $this->oPlanoContaAtivo;
  }



  public function contasParaSaida($iTipoSaida = 1)
  {

    $oContas = new stdClass();
    $oContas->codigo = 0;
    $oContas->codigoVPD = 0;

    if ($iTipoSaida === self::SAIDA_INVENTARIO) {

      $oContas->codigo = $this->getConta();
      $oContas->codigoVPD = $this->getCodigoContaVPD();
    } else if ($iTipoSaida === self::SAIDA_TRANSFERENCIA) {

      $oContas->codigo = $this->getCodigoContaTransf();
      $oContas->codigoVPD = $this->getCodigoContaTransfVPD();
    } else if ($iTipoSaida === self::SAIDA_DOACAO) {

      $oContas->codigo = $this->getCodigoContaDoacao();
      $oContas->codigoVPD = $this->getCodigoContaDoacaoVPD();
    } else if ($iTipoSaida === self::SAIDA_PERDA_ATIVO) {

      $oContas->codigo = $this->getCodigoContaPerdaAtivo();
      $oContas->codigoVPD = $this->getCodigoContaPerdaAtivoVPD();
    } else if ($iTipoSaida === self::SAIDA_OBRAS_ANDAMENTO) {

      $oContas->codigo = $this->getCodigoContaCredito();
      $oContas->codigoVPD = $this->getCodigoContaDebito();
    }

    return $oContas;
  }
}
