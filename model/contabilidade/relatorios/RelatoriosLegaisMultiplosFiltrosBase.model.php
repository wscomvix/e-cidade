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

/**
 * classe para controle dos valores dos anexos legais da RGF/LRF
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 *
 */
require_once ("model/relatorioContabilprevexecreceitasorc.model.php");

class RelatoriosLegaisMultiplosFiltrosBase {


  /**
   * Linhas que ser�o processadas os balancetes de receita
   * @var array
   */
  protected $aLinhasProcessarReceita = array();

  /**
   * Linhas que ser�o processadas os balancetes de despesa
   * @var array
   */
  protected $aLinhasProcessarDespesa = array();

  /**
   * Linhas que ser�o processadas os balancetes de verifica��o
   * @var array
   */
  protected $aLinhasProcessarVerificacao = array();

  /**
   * Linhas que ser�o processadas com as movimenta��es dos restos a pagar
   * @var array
   */
  protected $aLinhasProcessarRestosPagar = array();

  /**
   * Linhas para processar na consist�ncia
   * @var array
   */
  protected $aLinhasConsistencia = array();

  /**
   * instacia da classe RelatorioContabil
   *
   * @var relatorioContabil
   */
  protected $oRelatorioLegal;

  /**
   * Exericio do relatorio
   *
   * @var integer
   */
  protected $iAnoUsu;

  /**
   * Codigo do relatorio
   *
   * @var integer
   */
  protected $iCodigoRelatorio;

  /**
   * Linhas do Relat�rio
   *
   * @var integer
   *
   */
  protected $aDados = array();

  /**
   * lista de Institui��es
   *
   * @var string
   */
  protected $sListaInstit;
  /**
   * Codigo do periodo de emissao
   *
   * @var integer
   *
   */
  protected $iCodigoPeriodo;

  /**
   * Data inicial do per�odo selecionado
   * Pega o primeiro dia com base no per�odo selecionado
   *
   * @var DBDate
   */
  protected $oDataInicialPeriodo;

  /**
   * Data inicial do relat�rio
   * Pega por padr�o o primeiro dia do ano do per�odo do relat�rio
   *
   * @var DBDate
   */
  protected $oDataInicial;

  /**
   * Data final do relat�rio
   * Pega o ultimo dia com base no per�odo selecionado do relat�rio
   *
   * @var DBDate
   */
  protected $oDataFinal;

  /**
   * Campos para c�lculo do Balancete de Receita
   * @var array
   */
  static $aCamposReceita = array('saldo_inicial', 'saldo_prevadic_acum', 'saldo_inicial_prevadic', 'saldo_anterior',
    'saldo_arrecadado', 'saldo_a_arrecadar', 'saldo_arrecadado_acumulado',
    'saldo_prev_anterior');

  /**
   * Campos para c�lculo do Balancete de Despesa
   * @var array
   */
  static $aCamposDespesa = array('dot_ini', 'saldo_anterior', 'empenhado', 'anulado', 'liquidado', 'pago',
    'suplementado', 'reduzido', 'atual', 'reservado', 'atual_menos_reservado',
    'atual_a_pagar_liquidado', 'empenhado_acumulado', 'anulado_acumulado',
    'atual_a_pagar','liquidado_acumulado', 'pago_acumulado', 'suplementado_acumulado',
    'reduzido_acumulado', 'proj', 'ativ', 'oper', 'ordinario', 'vinculado', 'suplemen',
    'suplemen_acumulado', 'especial', 'especial_acumulado');

  static $aCamposRestoPagar = array('e91_vlremp',
    'e91_vlranu',
    'e91_vlrliq',
    'e91_vlrpag',
    'vlranu',
    'vlrliq',
    'vlrpag',
    'vlrpagnproc',
    'vlranuliq',
    'vlranuliqnaoproc');
  /**
   * Campos para consulta do Balancete de Verifica��o
   * @var array
   */
  static $aCamposVerificacao = array(
    'sinal_anterior',
    'saldo_anterior',
    'saldo_anterior_debito',
    'saldo_anterior_credito',
    'saldo_final',
    'sinal_final'
  );

  /**
   * Marcadores que ser�o substituidos nas linhas do relat�rio
   * @var array
   */
  protected $aMarcadoresLinhasRelatorio = array(
    '#exercicio_anterior' => '',
    '#exercicio' => ''
  );

  /**
   * Tipo de c�lculo do Balancete de Receita
   */
  const TIPO_CALCULO_RECEITA = 1;

  /**
   * Tipo de c�lculo do Balancete de Despesa
   */
  const TIPO_CALCULO_DESPESA = 2;

  /**
   * Tipo de c�lculo do Balancete de Verifica��o
   */
  const TIPO_CALCULO_VERIFICACAO = 3;

  /**
   * Calculos de Restos a pagar
   */
  const TIPO_CALCULO_RESTO = 4;

  protected $oPeriodo;

  /**
   *
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    $this->iCodigoRelatorio = $iCodigoRelatorio;
    $this->iAnoUsu          = $iAnoUsu;
    $this->iCodigoPeriodo   = $iCodigoPeriodo;
    $this->oRelatorioLegal  = new relatorioContabilPrevExecReceitasOrc($iCodigoRelatorio, false);

    $oDaoPeriodo      = db_utils::getDao("periodo");
    $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
    $rsPeriodo        = db_query($sSqlDadosPeriodo);
    $oDadosPeriodo    = db_utils::fieldsMemory($rsPeriodo, 0);
    $this->oPeriodo   = $oDadosPeriodo;
    $iMesFinal = (int)$this->oPeriodo->o114_mesfinal;
    $sDiaFinal = $this->oPeriodo->o114_diafinal;

    $sDataInicial = "{$this->iAnoUsu}-1-1";
    $sDataExercicio = "{$this->iAnoUsu}-{$iMesFinal}-{$sDiaFinal}";

    if((int)$iMesFinal === 2){

    $aPeriodo       = data_periodo($this->iAnoUsu, $oDadosPeriodo->o114_sigla);
    $sDataExercicio = $aPeriodo[1];

    }


    $this->setDataInicialPeriodo(new DBDate($sDataInicial));

    $this->aMarcadoresLinhasRelatorio['#exercicio']          = $this->iAnoUsu;
    $this->aMarcadoresLinhasRelatorio['#exercicio_anterior'] = $this->iAnoUsu-1;

    $this->setDataInicial(new DBDate($sDataInicial));
    $this->setDataFinal(new DBDate($sDataExercicio));
  }

  /**
   * retorna os dados do relatorio.
   *
   */
  public function getDados() {

    $this->aLinhasConsistencia = $this->getLinhasRelatorio();
    $this->executarBalancetesNecessarios();
    $this->processarValoresManuais();
    $this->processaTotalizadores($this->aLinhasConsistencia);
    return $this->aLinhasConsistencia;
  }

  /**
   * retorna os dados necess�rios para o relatorio simplidicado
   *
   */
  public function getDadosSimplificado() {

  }

  /**
   * define as instituicoes que serao usadas no relatorio
   *
   * @param string $sInstituicoes lista das instituicoes, seperadas por virgula
   */
  public function setInstituicoes($sInstituicoes) {
    $this->sListaInstit = $sInstituicoes;
  }

  /**
   * Retorna as institui��es setadas para o relat�rio. Quando o par�metro $lObjeto for true, retorna uma cole��o
   * de institu��es
   * @param bool $lObjeto
   * @return Instituicao[]|string
   */
  public function getInstituicoes($lObjeto = false) {

    if ($lObjeto) {

      $aInstituicoes = explode(',', str_replace("-",",", $this->sListaInstit));
      $aInstituicoesRetorno = array();
      foreach ($aInstituicoes as $iCodigoInstituicao) {
        $aInstituicoesRetorno[$iCodigoInstituicao] = InstituicaoRepository::getInstituicaoByCodigo($iCodigoInstituicao);
      }
      return $aInstituicoesRetorno;
    }
    return $this->sListaInstit;
  }


  /**
   * Processa as formulas do relat�rio
   * @param $aLinhas
   * @throws \Exception
   */
  public function processaTotalizadores ($aLinhas)  {

    foreach ($aLinhas as $iLinha => $oLinha) {

      if(!$oLinha->totalizar){
        continue;
      }

      foreach ($oLinha->colunas as $iColuna => $oColuna) {

        if(trim(trim($oColuna->o116_formula) == "")){
          continue;
        }
        $this->parseFormula($aLinhas, $iLinha, $iColuna);

      }
    }
  }

  /**
   * Reprocessa as formulas da linha passada
   * @param  array $aLinhas
   * @param  integer $iLinha
   * @throws \Exception
   */
  public function processaFormulasLinha($aLinhas, $iLinha) {

    foreach($aLinhas[$iLinha]->colunas as $iColuna => $oColuna) {

      if (!empty($oColuna->o116_formula)) {
        $this->parseFormula($aLinhas, $iLinha, $iColuna);
      }
    }
  }

  /**
   * Faz o parse da formula da linha e coluna passados
   * @param  array &$aLinhas - Array das linhas do relat�rio
   * @param  integer $iLinha - Linha
   * @param  integer $iColuna - Coluna
   * @throws \Exception
   */
  private function parseFormula(&$aLinhas, $iLinha, $iColuna) {

    try {
      $sFormula = $this->oRelatorioLegal->parseFormula('aLinhas', $aLinhas[$iLinha]->colunas[$iColuna]->o116_formula, $iColuna, $aLinhas);
    } catch (Exception $e) {
      echo 'Exce��o capturada: ',  $e->getMessage(), "\n";
    }

    $evaluate = "\$aLinhas[{$iLinha}]->{$aLinhas[$iLinha]->colunas[$iColuna]->o115_nomecoluna} = {$sFormula};";

    ob_start();
    eval($evaluate);
    $sRetorno = ob_get_contents();
    ob_clean();

    if (strpos(strtolower($sRetorno), "parse error") !== false) {
      $sMsg =  "Linha {$iLinha}, Coluna {$aLinhas[$iLinha]->colunas[$iColuna]->o115_nomecoluna} com erro no cadastro da formula<br>{$aLinhas[$iLinha]->colunas[$iColuna]->o116_formula} <Br>{$sRetorno}";
      throw new Exception($sMsg);
    }
  }

  /**
   * Retorna os periodos cadastras para o relatorio
   *
   * @return array();
   */
  public  function getPeriodos() {

    return $this->oRelatorioLegal->getPeriodos();
  }

  /**
   * Verifica se h� espa�o na p�gina e escreve a nota explicativa.
   *
   * Exemplo:
   * $this->notaExplicativa($oPdf, array($this, 'adicionarPagina'), 20);
   *
   * @param  PDFDocument $oPdf     Inst�ncia da PDFDocument
   * @param  array       $callback Callback utilizado para quebrar a p�gina (se necess�rio)
   * @param  integer     $iMargem  Margem a ser considerada no c�lculo
   * @throws ParameterException
   */
  public function notaExplicativa(PDFDocument $oPdf, array $callback, $iMargem = 0) {

    /**
     * Verifica se o �ndice zero � objeto
     */
    if (!is_object($callback[0])) {
      throw new ParameterException('N�o foi informada uma inst�ncia de objeto v�lida.');
    }

    /**
     * Verifica se o m�todo existe no objeto informado
     */
    if (!method_exists($callback[0], $callback[1])) {
      throw new ParameterException('O m�todo n�o existe no objeto informado.');
    }

    /**
     * Verifica a visiblidade do m�todo
     */
    $oReflection = new ReflectionMethod($callback[0], $callback[1]);
    if (!$oReflection->isPublic()) {
      throw new ParameterException('O m�todo informado deve ser p�blico.');
    }

    /**
     * Calcula o tamanho da nota explicativa e caso n�o haja espa�o
     * suficiente para escrever chama o m�todo passado por par�metro
     */
    $iAltura = $this->oRelatorioLegal->notaExplicativa($oPdf, $this->iCodigoPeriodo, $oPdf->getAvailWidth(), false);
    if ($oPdf->getAvailHeight() < ($iAltura + $iMargem)) {
      call_user_func($callback);
    }

    /**
     * Escreve a nota explicativa
     */
    $this->oRelatorioLegal->notaExplicativa($oPdf, $this->iCodigoPeriodo, $oPdf->getAvailWidth());
  }

  /**
   * Monta a nota explicativa
   *
   * @deprecated Utilize o m�todo notaExplicativa
   * @param FPDF $oPdf instancia do PDf
   * @param integer $iPeriodo Codigo do periodo
   * @param integer $iTam Tamanho da celula
   * @return void
   */
  public function getNotaExplicativa($oPdf, $iPeriodo,$iTam = 190) {
    $this->oRelatorioLegal->getNotaExplicativa($oPdf, $iPeriodo,$iTam);
  }

  /**
   * Seta a data inicial do relat�rio
   *
   * @param DBDate $oDataInicial inst�ncia da data inicial do relat�rio
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * Seta a data final do relat�rio
   *
   * @param DBDate $oDataFinal inst�ncia da data final do relat�rio
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * Data inicial de emiss�o do relat�rio
   * @return DBDate Data inicial da emiss�o do relat�rio
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   * Data final de emiss�o do relat�rio
   * @return DBDate Data final da emiss�o do relat�rio
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   * Retorna as linhas configuradas para o relat�rio
   */
  public function getLinhasRelatorio() {

    $aLinhasRetorno   = array();
    $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
    foreach ($aLinhasRelatorio as $oLinha) {

      $oLinha->setPeriodo($this->iCodigoPeriodo);

      $oParametros                    = $oLinha->getParametros($this->iAnoUsu, $this->getInstituicoes()) ;
      $oColunas                       = $oLinha->getCols($this->iCodigoPeriodo);
      $oLinhaRetorno                  = new stdClass();
      $oLinhaRetorno->ordem           = $oLinha->getOrdem();
      $oLinhaRetorno->totalizar       = $oLinha->isTotalizador();
      $oLinhaRetorno->descricao       = $oLinha->getDescricaoLinha();
      $oLinhaRetorno->colunas         = $oColunas;
      $oLinhaRetorno->contas          = array();
      $oLinhaRetorno->desdobrar       = false;
      $oLinhaRetorno->nivel           = $oLinha->getNivel();
      $oLinhaRetorno->parametros      = $oParametros;
      $oLinhaRetorno->oLinhaRelatorio = $oLinha;
      $oLinhaRetorno->origem          = $oLinha->getOrigemDados();

      foreach ($this->aMarcadoresLinhasRelatorio as $sMarcador => $sValor) {
        $oLinhaRetorno->descricao = str_replace($sMarcador, $sValor, $oLinhaRetorno->descricao);
      }

      if ($oParametros->desdobrarlinha && $oLinha->desdobraLinha()) {
        $oLinhaRetorno->desdobrar = true;
      }

      /**
       * Criamos as colunas
       */
      foreach ($oLinhaRetorno->colunas as $oColuna) {

        $oLinhaRetorno->{$oColuna->o115_nomecoluna} = 0;
      }
      $aLinhasRetorno[$oLinha->getOrdem()] = $oLinhaRetorno;
    }
    return $aLinhasRetorno;
  }

  protected static function formataConta(array $aPrefixos, $oConta){

    $aContas = array();
    foreach ($aPrefixos as $sPrefixo){

      $oNovaConta = new stdClass();
      $oNovaConta->estrutural = $sPrefixo.substr($oConta->estrutural,1,12);
      $oNovaConta->nivel = '';
      $oNovaConta->exclusao = FALSE;
      $oNovaConta->indicador = '';
      $aContas[] = $oNovaConta;

    }
    return $aContas;

  }

  /**
   * Realiza o Calculo do valor para a linha informada
   *
   * @param resource $Recordset    resource com os dados do balancete do tipo informado
   * @param resource $RecordsetPosterior    resource com os dados do balancete do tipo informado no ano posterior
   * @param stdClass $oLinha       stdClass com os dados a ser Analisado
   * @param integer  $iTipoCalculo tipo do calculo que deve ser realizado
   * @return float
   */
  protected static function calcularValorDaLinhaComValoresDerivados($Recordset, $RecordsetPosterior, stdClass $oLinha, $iTipoCalculo) {

    $iTotalLinhasCorrente = pg_num_rows($Recordset);
    $iTotalLinhasPosterior = pg_num_rows($RecordsetPosterior);
    $iTotalLinhas = $iTotalLinhasCorrente > $iTotalLinhasPosterior ? $iTotalLinhasCorrente : $iTotalLinhasPosterior;

    $aContass = $oLinha->parametros->contas;

    // Percorre as linhas do balancete da receita
    for ($iLinha = 0; $iLinha < $iTotalLinhas; $iLinha++) {

      $oDados         = new stdClass();
      $oDadosResource = db_utils::fieldsMemory($Recordset, $iLinha);
      $oDadosResourcePosterior = db_utils::fieldsMemory($RecordsetPosterior, $iLinha);

      // Percorre as contas de uma linha - 1� n�vel
      foreach ($aContass as $oConta) {

        $oVerificacaoAnoAtual = $oLinha->oLinhaRelatorio->match($oConta,
          $oLinha->parametros->orcamento,
          $oDadosResource,
          $iTipoCalculo
        );

        $oVerificacaoAnoPosterior = $oLinha->oLinhaRelatorio->match($oConta,
          $oLinha->parametros->orcamento,
          $oDadosResourcePosterior,
          $iTipoCalculo
        );

        if (!$oVerificacaoAnoAtual->match and !$oVerificacaoAnoPosterior->match) {
          continue;
        }

        $aContas = array();
        $aContas[] = $oConta;
        $aContasCols1e2 = null;
        $aContasCol4 = null;
        $aContasCol6 = null;
        $aContasCol8 = null;

        $aColunas1e2 = null;
        $aColuna4 = null;
        $aColuna6 = null;


        if ($oVerificacaoAnoAtual->match){

          // Organiza as colunas que ser�o usadas para o c�lculo
          $aColuna3 = RelatoriosLegaisMultiplosFiltrosBase::processarColunasDaLinha($oLinha,array(3));
          RelatoriosLegaisMultiplosFiltrosBase::calcularValorDaLinha($aContas, $oLinha, $oDadosResource, $iTipoCalculo, $oDados, $aColuna3);

          // DERIVA��O - IN�CIO

          // COLUNAS 1 E 2
          // Cria as contas derivadas da conta original - Coluna 1 e 2
          $aPrefixos = array('491','492','493','495','496','498','499');
          $aContasCols1e2 = array($oConta);

          foreach (RelatoriosLegaisMultiplosFiltrosBase::formataConta($aPrefixos, $oConta) as $oContaa){
            $aContasCols1e2[] = $oContaa;
          }

          // Organiza as colunas 1 e 2 para serem utilizadas no c�lculo
          $aColunas1e2 = RelatoriosLegaisMultiplosFiltrosBase::processarColunasDaLinha($oLinha,array(1,2));

          // COLUNA 4
          // Cria as contas derivadas da conta original - Coluna 4
          $aPrefixos = array('491','492','493','496','498','499');
          $aContasCol4 = array();
          foreach (RelatoriosLegaisMultiplosFiltrosBase::formataConta($aPrefixos, $oConta) as $oContaa){
            $aContasCol4[] = $oContaa;
          }

          // Organiza a coluna 4 que ser� usada para o c�lculo
          $aColuna4 = RelatoriosLegaisMultiplosFiltrosBase::processarColunasDaLinha($oLinha,array(4));

          // COLUNA 6
          // Cria as contas derivadas da conta original - Coluna 6
          $aPrefixos = array('495');
          $aContasCol6 = array();
          foreach (RelatoriosLegaisMultiplosFiltrosBase::formataConta($aPrefixos, $oConta) as $oContaa){
            $aContasCol6[] = $oContaa;
          }

          // Organiza a coluna 6 que ser� usada para o c�lculo
          $aColuna6 = RelatoriosLegaisMultiplosFiltrosBase::processarColunasDaLinha($oLinha,array(6));

          // Percorre as linhas do balancete da receita - 2� n�vel
          for ($iLinha2 = 0; $iLinha2 < $iTotalLinhas; $iLinha2++) {

            $oDadosResource2 = db_utils::fieldsMemory($Recordset, $iLinha2);

            if($oVerificacaoAnoAtual->match){

              RelatoriosLegaisMultiplosFiltrosBase::calcularValorDaLinha($aContasCols1e2, $oLinha, $oDadosResource2, $iTipoCalculo, $oDados, $aColunas1e2);
              RelatoriosLegaisMultiplosFiltrosBase::calcularValorDaLinha($aContasCol4, $oLinha, $oDadosResource2, $iTipoCalculo, $oDados, $aColuna4);
              RelatoriosLegaisMultiplosFiltrosBase::calcularValorDaLinha($aContasCol6, $oLinha, $oDadosResource2, $iTipoCalculo, $oDados, $aColuna6);
            }
          }
        }

        if($oVerificacaoAnoPosterior->match && $RecordsetPosterior){

          $aPrefixos = array('491','492','493','495','496','498','499');
          $aContasCol8 = array($oConta);

          foreach (RelatoriosLegaisMultiplosFiltrosBase::formataConta($aPrefixos, $oConta) as $oContaa){
            $aContasCol8[] = $oContaa;
          }

          $aColuna8 = null;

          // Organiza a coluna 8 para ser utilizada no c�lculo
          $aColuna8 = RelatoriosLegaisMultiplosFiltrosBase::processarColunasDaLinha($oLinha,array(8));

          // Percorre as linhas do balancete da receita - 2� n�vel
          for ($iLinha2 = 0; $iLinha2 < $iTotalLinhas; $iLinha2++) {
            $oDadosResource3 = db_utils::fieldsMemory($RecordsetPosterior, $iLinha2);
            RelatoriosLegaisMultiplosFiltrosBase::calcularValorDaLinha($aContasCol8, $oLinha, $oDadosResource3, $iTipoCalculo, $oDados, $aColuna8);
          }

        }
      }
    }

    if((float)$oLinha->deducrec < 0){
      $oLinha->deducrec = $oLinha->deducrec * -1;
    }
    if((float)$oLinha->deducformacfundeb < 0){
      $oLinha->deducformacfundeb = $oLinha->deducformacfundeb * -1;
    }

    if(in_array((int)$oLinha->ordem,
      array(5,6,8,9,10,13,14,15,89,92,117,123,124,125,188,191,192,193,194,201,202,203,204,205,221,222,223,224,225,323,324,326))){
      $oLinha->recrealizadabasecalcpercaplicasps = $oLinha->recrealizadabruta - $oLinha->deducrec;
    }
    $oLinha->totgeralrecliqrealiza = $oLinha->recrealizadabruta - $oLinha->deducrec - $oLinha->deducformacfundeb;

    return $oLinha;

  }

  protected static function calcularValorDaLinha($aContas, $oLinha, $oDadosResource, $iTipoCalculo, $oDados, $aColunasCalcular){

    $aListaColunas        = RelatoriosLegaisMultiplosFiltrosBase::$aCamposReceita;

    foreach ($aContas as $iK => $oContaN2) {

      $oVerificacao = $oLinha->oLinhaRelatorio->match($oContaN2,
        $oLinha->parametros->orcamento,
        $oDadosResource,
        $iTipoCalculo
      );

      if (!$oVerificacao->match) {
        continue;
      }

      $oValoresParaCalculo = clone $oDadosResource;

      if ($oContaN2->exclusao) {

        foreach ($aListaColunas as $sColuna) {
          $oValoresParaCalculo->{$sColuna} *= -1;
        }
      }

      if ($oLinha->desdobrar) {

        if (!isset($oLinha->contas[$oContaN2->estrutural])) {

          $oContaDesdobrada                    = new stdClass();
          $oContaDesdobrada->descricao         = $oValoresParaCalculo->{$sNomeColunaDescricao};
          $oLinha->contas[$oContaN2->estrutural] = $oContaDesdobrada;
        }
      }
      $oDados->resource = $oValoresParaCalculo;

      foreach ($aColunasCalcular as $oColuna) {

        $oDados->coluna     = $oColuna;
        $nValorConta        = RelatoriosLegaisMultiplosFiltrosBase::resolverFormula($oColuna->formula, $oDados);

        if ($oLinha->desdobrar) {

          $oContaDesdobrada                    = $oLinha->contas[$oContaN2->estrutural];
          if (!isset($oContaDesdobrada->{$oColuna->nome})) {
            $oContaDesdobrada->{$oColuna->nome} = 0;
          }
          $oContaDesdobrada->{$oColuna->nome} += $nValorConta;
        }

        if (isset($oColuna->agrupar)) {
          RelatoriosLegaisMultiplosFiltrosBase::agrupar($oLinha, $oColuna, $oValoresParaCalculo, $nValorConta);
        }
        $oLinha->{$oColuna->nome} += $nValorConta;

      }
    }
    return $oLinha;

  }


  /**
   * Realiza do agrupamentop dos valores atravez de um tipo
   * @param $oLinha
   * @param $oColuna
   * @param $oResource
   * @param $nValor
   */
  protected static function agrupar($oLinha, $oColuna, $oResource, $nValor) {

    if (!isset($oLinha->{$oColuna->agrupar->nome})) {
      $oLinha->{$oColuna->agrupar->nome} = array();
    }

    if (!isset($oLinha->{$oColuna->agrupar->nome}[$oResource->{$oColuna->agrupar->campo}])) {

      $oAgrupar                   = new stdClass();
      $oAgrupar->nome             = $oResource->{$oColuna->agrupar->descricao};
      $oAgrupar->{$oColuna->nome} = 0;

      $oLinha->{$oColuna->agrupar->nome}[$oResource->{$oColuna->agrupar->campo}] = $oAgrupar;
    }

    $oAgrupar = $oLinha->{$oColuna->agrupar->nome}[$oResource->{$oColuna->agrupar->campo}];

    $oAgrupar->{$oColuna->nome} += $nValor;
  }

  /**
   * REalizar o parse da formula
   * @param string $sFormula Formula matematica
   * @param stdClass $oDados objeto com os valores
   * @param $oLinha
   * @param $oColuna
   * @return int
   */
  protected static function resolverFormula($sFormula, $oDados) {

    $nValor = 0;
    if (trim($sFormula) != '' ) {

      $sFormula = str_replace('#', '$oDados->resource->', $sFormula);
      eval("\$nValor = {$sFormula};");
    }

    return $nValor;
  }

  /**
   * Realiza o processamento das linhas com valores Digitados Manuais
   */
  protected function processarValoresManuais() {

    foreach ($this->aLinhasConsistencia as $oLinha) {

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null,
        $this->getInstituicoes(),
        $this->iAnoUsu
      );
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }
    }
  }

  /**
   * Retorna a instancia do relatorioContabil
   * @return relatorioContabil
   */
  public function getRelatorioContabil() {
    return $this->oRelatorioLegal;
  }

  /**
   * Verifica quais os tipos de calculos devem ser executados para a consist�ncia
   */
  protected function processarTiposDeCalculo() {

    foreach($this->aLinhasConsistencia as $iLinhas => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      switch ($oLinha->origem) {

        case linhaRelatorioContabil::ORIGEM_RECEITA:

          $this->aLinhasProcessarReceita[] = $iLinhas;
          break;

        case linhaRelatorioContabil::ORIGEM_DESPESA:

          $this->aLinhasProcessarDespesa[] = $iLinhas;
          break;

        case linhaRelatorioContabil::ORIGEM_RESTOS_PAGAR:
          $this->aLinhasProcessarRestosPagar[] = $iLinhas;
          break;

        case linhaRelatorioContabil::ORIGEM_VERIFICACAO:

          $this->aLinhasProcessarVerificacao[] = $iLinhas;
          break;

      }
    }

  }


  /**
   * Executa oo calculo dos balancetes necessarios
   */
  protected function executarBalancetesNecessarios() {

    $this->processarTiposDeCalculo();

    if (count($this->aLinhasProcessarReceita) > 0) {
      $this->executarBalanceteDaReceita();
    }

    if (count($this->aLinhasProcessarDespesa) > 0) {
      $this->executarBalanceteDespesa();
    }

    if (count($this->aLinhasProcessarVerificacao) > 0) {
      $this->executarBalanceteVerificacao();
    }

    if (count($this->aLinhasProcessarRestosPagar) > 0 ) {
      $this->executarRestosPagar();
    }
  }


  /**
   * Executa o Balancete da Receita - Fonte de dados para este relat�rio
   */
  protected function executarBalanceteDaReceita() {

    $sWhereReceita      = "o70_instit in ({$this->getInstituicoes()})";
    db_inicio_transacao();
    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
      $sWhereReceita, $this->iAnoUsu,
      "$this->iAnoUsu-01-01",
      $this->getDataFinal()->getDate(),
    false," * ",false
    );
    db_fim_transacao(true);
    $rsBalanceteReceitaPosterior = null;

    if((int)$this->iCodigoPeriodo === 130) {

      db_inicio_transacao();
      $iAnoPosterior = $this->iAnoUsu + 1;
      $rsBalanceteReceitaPosterior = db_receitasaldo(11, 1, 3, true,
        $sWhereReceita, $iAnoPosterior,
        "$iAnoPosterior-01-01",
        "$iAnoPosterior-01-31",
        false," * ",false
      );
      db_fim_transacao(true);
    }

    foreach ($this->aLinhasProcessarReceita as $iLinha ) {

      $oLinha = $this->aLinhasConsistencia[$iLinha];

      RelatoriosLegaisMultiplosFiltrosBase::calcularValorDaLinhaComValoresDerivados($rsBalanceteReceita,
        $rsBalanceteReceitaPosterior,
        $oLinha,
        RelatoriosLegaisMultiplosFiltrosBase::TIPO_CALCULO_RECEITA
      );

    }
    $this->limparEstruturaBalanceteReceita();

  }

  /**
   * Processa as colunas que ser�o usadas para o calculo
   *
   * @param stdClass $oLinha Instancia da linha
   * @param null     $aColunas
   * @return array retorna um array com as linhas
   */
  protected static function processarColunasDaLinha(stdClass $oLinha, $aColunas) {

    $aColunasRetorno = array();
    foreach($aColunas as $iCol){
      $oColunaRelatorio          = $oLinha->colunas[$iCol-1];

      $oColuna             = new stdClass();
      $oColuna->nome       = $oColunaRelatorio->o115_nomecoluna;
      $oColuna->formula    = $oColunaRelatorio->o116_formula;
      $oColuna->analisada  = false;

      if (property_exists($oColuna, 'agrupar')) {
        $oColuna->agrupar = $oColunaRelatorio->agrupar;
      }
      $aColunasRetorno[] = $oColuna;
    }

    return $aColunasRetorno;
  }

  /**
   * remove as tabelas utilizadas para processamento do balancete de verificacao
   */
  protected function limparEstruturaBalanceteVerificacao() {

    db_query("drop table if exists work_pl");
    db_query("drop table if exists work_pl_estrut");
    db_query("drop table if exists work_pl_estrut");
    db_query("drop table if exists work_pl_estrutmae");
  }


  /**
   * Remove as tabelas utilizadas para processamento do balancete de despesa
   */
  protected  function limparEstruturaBalanceteDespesa() {
    db_query("drop table if exists work_dotacao");
  }

  /**
   * Remove as tabelas utilizadas para processamento do balancete de receita
   */
  protected function limparEstruturaBalanceteReceita() {
    db_query("drop table if exists work_receita");
  }

  /**
   * @return DBDate
   */
  public function getDataInicialPeriodo() {
    return $this->oDataInicialPeriodo;
  }

  /**
   * @param DBDate $oDataInicialPeriodo
   */
  public function setDataInicialPeriodo(DBDate $oDataInicialPeriodo) {
    $this->oDataInicialPeriodo = $oDataInicialPeriodo;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAnoUsu;
  }


  /**
   * @param RelatoriosLegaisMultiplosFiltrosBase $oRelatorio
   * @param array                $aLinhasValidar
   * @param bool                 $lValidarExercicioAnterior
   *
   * @return Recurso[]
   */
  public static function getRecursosPendentesConfiguracao(RelatoriosLegaisMultiplosFiltrosBase $oRelatorio, array $aLinhasValidar, $lValidarExercicioAnterior = false) {

    $aRecursosNaoConfiguradosRetorno = array();
    $iAnoAnterior         = ($oRelatorio->getDataInicial()->getAno() - 1);
    $oDataInicialAnterior = new DBDate("01/01/{$iAnoAnterior}");
    $oDataFinalAnterior   = new DBDate("{$oRelatorio->getDataFinal()->getDia()}/{$oRelatorio->getDataFinal()->getMes()}/{$iAnoAnterior}");

    $sWhereReceita = "o70_instit in ({$oRelatorio->getInstituicoes()})";
    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
      $sWhereReceita, $oRelatorio->getAno(),
      $oRelatorio->getDataInicial()->getDate(),
      $oRelatorio->getDataFinal()->getDate());

    $sWhereDespesa      = " o58_instit in({$oRelatorio->getInstituicoes()})";
    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
      $oRelatorio->getAno(),
      $oRelatorio->getDataInicial()->getDate(),
      $oRelatorio->getDataFinal()->getDate());

    $rsBalanceteReceitaAnoAnterior = null;
    $rsBalanceteDespesaAnoAnterior = null;
    if ($lValidarExercicioAnterior) {

      db_query("drop table if exists work_dotacao");
      db_query("drop table if exists work_receita");

      $rsBalanceteReceitaAnoAnterior = db_receitasaldo(11, 1, 3, true,
        $sWhereReceita, $oRelatorio->getAno(),
        $oDataInicialAnterior->getDate(),
        $oDataFinalAnterior->getDate());

      $rsBalanceteDespesaAnoAnterior = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
        $oRelatorio->getAno(),
        $oDataInicialAnterior->getDate(),
        $oDataFinalAnterior->getDate());
    }

    $aRecursos = array();

    /**
     * Pega os recursos das movimenta��es do exerc�cio atual
     */
    for ($iRowCalculo = 0; $iRowCalculo < pg_num_rows($rsBalanceteReceita); $iRowCalculo++)  {
      $aRecursos[] = pg_fetch_result($rsBalanceteReceita, $iRowCalculo, 'o70_codigo');
    }

    for ($iRowCalculo = 0; $iRowCalculo < pg_num_rows($rsBalanceteDespesa); $iRowCalculo++) {
      $aRecursos[] = pg_fetch_result($rsBalanceteDespesa, $iRowCalculo, 'o58_codigo');
    }

    /**
     * Pega os recursos das movimenta��es do exerc�cio anterior
     */
    if ($lValidarExercicioAnterior) {

      for ($iRowCalculo = 0; $iRowCalculo < pg_num_rows($rsBalanceteReceitaAnoAnterior); $iRowCalculo++)  {
        $aRecursos[] = pg_fetch_result($rsBalanceteReceitaAnoAnterior, $iRowCalculo, 'o70_codigo');
      }

      for ($iRowCalculo = 0; $iRowCalculo < pg_num_rows($rsBalanceteDespesaAnoAnterior); $iRowCalculo++) {
        $aRecursos[] = pg_fetch_result($rsBalanceteDespesaAnoAnterior, $iRowCalculo, 'o58_codigo');
      }
    }

    $aRecursos = array_unique($aRecursos);
    sort($aRecursos);

    /**
     * Pega os recursos da configura��o
     */
    $aRecursosConfiguradosIn    = array();
    $aRecursosConfiguradosNotIn = array();

    $aLinhasRelatorio = $oRelatorio->getLinhasRelatorio();

    foreach ($aLinhasValidar as $iLinhaRelatorio) {

      $pArrayToMerge =& $aRecursosConfiguradosIn;
      if (strtolower(trim($aLinhasRelatorio[$iLinhaRelatorio]->parametros->orcamento->recurso->operador)) != 'in') {
        $pArrayToMerge =& $aRecursosConfiguradosNotIn;
      }
      $pArrayToMerge = array_merge($pArrayToMerge, $aLinhasRelatorio[$iLinhaRelatorio]->parametros->orcamento->recurso->valor);
    }

    $oDaoTipoRec = new cl_orctiporec();

    /**
     * Pega os recursos do tipo vinculado quando for "n�o contendo" na configura��o
     */
    if (!empty($aRecursosConfiguradosNotIn)) {

      $sSqlTiporec = $oDaoTipoRec->sql_query_file( null, "o15_codigo", null, "o15_codigo not in (" . implode(', ', $aRecursosConfiguradosNotIn) . ") and o15_tipo = 2");
      $rsTiporec   = $oDaoTipoRec->sql_record($sSqlTiporec);

      if ($rsTiporec && pg_num_rows($rsTiporec) > 0) {

        for ($iRowTiporec = 0; $iRowTiporec < pg_num_rows($rsTiporec); $iRowTiporec++) {
          $aRecursosConfiguradosIn[] = db_utils::fieldsMemory($rsTiporec, $iRowTiporec)->o15_codigo;
        }
      }
    }

    $aRecursosNaoConfigurados = array_diff($aRecursos, array_unique($aRecursosConfiguradosIn));
    if (!empty($aRecursosNaoConfigurados)) {

      $sSqlTiporec = $oDaoTipoRec->sql_query_file( null, "o15_codigo", null, "o15_codigo in (" . implode(', ', $aRecursosNaoConfigurados) . ") and o15_tipo = 2");
      $rsTiporec   = $oDaoTipoRec->sql_record($sSqlTiporec);

      if ($rsTiporec && pg_num_rows($rsTiporec) > 0) {

        for ($iRowTiporec = 0; $iRowTiporec < pg_num_rows($rsTiporec); $iRowTiporec++) {
          $oDadosTiporec = db_utils::fieldsMemory($rsTiporec, $iRowTiporec);

          $aRecursosNaoConfiguradosRetorno[] = new Recurso($oDadosTiporec->o15_codigo);
        }
      }
    }

    db_query("drop table if exists work_dotacao");
    db_query("drop table if exists work_receita");
    return $aRecursosNaoConfiguradosRetorno;
  }

  /**
   * @return Periodo
   */
  public function getPeriodo() {
    return new Periodo($this->iCodigoPeriodo);
  }
}
