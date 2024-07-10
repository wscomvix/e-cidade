<?
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
require_once("model/licitacao/LicitacaoModalidade.model.php");

/**
 * Class licitacao
 */
class licitacao
{

    /*
     * Sequencial da tabela
     */
    private $iCodLicitacao   = null;
    private $aItensLicitacao = array();
    private $oDados          = null;
    private $oDaoLicita      = null;
    private $oDaoParametros  = null;
    protected $aFornecedores = array();

    protected $iCodigoSituacao;
    protected $iNumeroEdital;
    protected $iNumeroLicitacal;
    protected $iAno;
    protected $sObjeto;

    /**
     * @return mixed
     */
    public function getObjeto()
    {
        return $this->sObjeto;
    }

    /**
     * @param mixed $sObjeto
     * @return licitacao
     */
    public function setObjeto($sObjeto)
    {
        $this->sObjeto = $sObjeto;
        return $this;
    }
    /**
     * objeto processoProtocolo
     * @var object
     */
    private $oProcessoProtocolo;

    /**
     * Situa��o da Licita��o
     * @var SituacaoLicitacao
     */
    private $oSituacaoLicitacao;

    /**
     * @var integer
     */
    private $iCodigoModalidade;


    /**
     * registro de preco da licitacao
     *
     * @var compilacaoRegistroPreco
     */
    private $oRegistroPreco  = null;

    function __construct($iCodLicitacao = null)
    {

        if (!empty($iCodLicitacao)) {

            $this->iCodLicitacao = $iCodLicitacao;

            $oDaoLicitacao = db_utils::getDao("liclicita");
            $sSqlBuscaLicitacao = $oDaoLicitacao->sql_query_old($iCodLicitacao);
            $rsBuscaLicitacao   = $oDaoLicitacao->sql_record($sSqlBuscaLicitacao);

            $oDadoLicitacao = db_utils::fieldsMemory($rsBuscaLicitacao, 0);
            $this->iNumeroEdital      = $oDadoLicitacao->l20_edital;
            $this->iCodigoSituacao    = $oDadoLicitacao->l20_licsituacao;
            $this->iNumeroLicitacal   = $oDadoLicitacao->l20_numero;
            $this->iAno               = $oDadoLicitacao->l20_anousu;
            $this->iCodigoModalidade  = $oDadoLicitacao->l20_codtipocom;
            $this->sObjeto            = $oDadoLicitacao->l20_objeto;
            $this->iTipoCompraTribunal = $oDadoLicitacao->l44_sequencial;
            unset($oDadoLicitacao);
        }
        $this->oDaoLicita  = db_utils::getDao("liclicita");
    }

    public function getNumeroLicitacao()
    {
        return $this->iNumeroLicitacal;
    }

    /**
     * retorna os dados do processo do protocolo vinculado a Licita��o
     * @return processoProtocolo object
     */
    public function getProcessoProtocolo()
    {

        $oDaoLicLicitaProc = db_utils::getDao("liclicitaproc");
        $sSqlProcesso      = $oDaoLicLicitaProc->sql_query(null, "l34_protprocesso", null, "l34_liclicita = {$this->iCodLicitacao} ");
        $rsProcesso        = $oDaoLicLicitaProc->sql_record($sSqlProcesso);
        if ($oDaoLicLicitaProc->numrows > 0) {

            $iProcessoProtocolo = db_utils::fieldsMemory($rsProcesso, 0)->l34_protprocesso;
            $oProcessoProtocolo = new processoProtocolo($iProcessoProtocolo);
            return $oProcessoProtocolo;
        }
    }

    /**
     * setamos o processo do protocolo
     * @param processoProtocolo $oProcessoProtocolo
     */
    public function setProcessoProtocolo(processoProtocolo $oProcessoProtocolo)
    {

        $this->oProcessoProtocolo = $oProcessoProtocolo;
    }

    /**
     * Seta o C�digo da Licita��o
     * @param $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iCodLicitacao = $iCodigo;
    }

    /**
     * Retorna o C�digo sequencial da Licita��o
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodLicitacao;
    }

    /**
     * Retorna o ano da Licita��o
     * @return integer
     */
    public function getAno()
    {
        return $this->iAno;
    }

    /**
     * traz os Processos de compra Vinculadas a licitacao.
     * @return array
     */

    public function getProcessoCompras()
    {

        $aSolicitacoes = array();
        if ($this->iCodLicitacao == null) {

            throw new exception("C�digo da licitacao nulo");
            return false;
        }
        $oDaoLicitem  = db_utils::getDao("liclicitem");
        $sCampos      = "distinct pc80_codproc,coddepto, descrdepto,login,pc80_data,pc80_resumo";
        $rsProcessos  = $oDaoLicitem->sql_record(
            $oDaoLicitem->sql_query_inf(
                null,
                $sCampos,
                "pc80_codproc",
                "l21_codliclicita = {$this->iCodLicitacao}"
            )
        );
        if ($oDaoLicitem->numrows > 0) {

            for ($iInd = 0; $iInd < $oDaoLicitem->numrows; $iInd++) {

                $aSolicitacoes[] = db_utils::fieldsMemory($rsProcessos, $iInd);
            }
        }
        return $aSolicitacoes;
    }
    /**
     * retorna os Dados da Licitacao
     * @return object
     */
    public function getDados()
    {

        $rsLicita     = $this->oDaoLicita->sql_record($this->oDaoLicita->sql_queryContratos($this->iCodLicitacao));
        $this->oDados = db_utils::fieldsMemory($rsLicita, 0);
        return $this->oDados;
    }

    /**
     * Retorna os itens da solicitacao num xml
     * @return XML
     */
    public function itensToXml()
    {

        $oDaoLicitaItens     = db_utils::getDao("liclicitem");
        $oDaoLicitaItensLote = db_utils::getDao("liclicitemlote");
        $sSqlItens           = $oDaoLicitaItens->sql_query_inf(
            null,
            "distinct
      liclicitem.*,
      pcprocitem.*,
      solicita.*,
      pcmater.*,
      matunid.*,
      solicitem.*",
            "l21_codigo",
            "l21_codliclicita={$this->iCodLicitacao}"
        );
        $rsItens         = $oDaoLicitaItens->sql_record($sSqlItens);
        $aItensLicitacao = db_utils::getCollectionByRecord($rsItens);

        if (count($aItensLicitacao) == 0) {
            throw new BusinessException('Licita��o sem itens vinculados.\nProcedimento abortado.');
        }

        $sStringXml  = "<?xml version='1.0'  standalone='yes'?>";
        $sStringXml .= "<licitacao>";
        $sStringXml .= "</licitacao>";
        $oXml = new SimpleXMLElement($sStringXml);
        foreach ($aItensLicitacao as $oItemLicitacao) {

            $oItem = $oXml->addChild("item");
            for ($i = 0; $i < pg_num_fields($rsItens); $i++) {

                $sCampo      = pg_field_name($rsItens, $i);
                $sValorCampo = $oItemLicitacao->$sCampo;
                $oItem->addChild("$sCampo", utf8_encode($sValorCampo));
            }
            /*
             * Verificamos se o item possui lote
             */
            $sSqlLote  = $oDaoLicitaItensLote->sql_query_file(
                null,
                "*",
                null,
                "l04_liclicitem={$oItemLicitacao->l21_codigo}"
            );
            $rsLote    = $oDaoLicitaItensLote->sql_record($sSqlLote);
            $oItemLote = $oItem->addChild("lote");
            if ($oDaoLicitaItensLote->numrows > 0) {

                $aItensLote = db_utils::getCollectionByRecord($rsLote);

                foreach ($aItensLote as $oLote) {


                    $oItemLote->addAttribute("l04_codigo", utf8_encode($oLote->l04_codigo));
                    $oItemLote->addAttribute("l04_liclicitem", utf8_encode($oLote->l04_liclicitem));
                    $oItemLote->addAttribute("l04_descricao", utf8_encode($oLote->l04_descricao));
                }
            }
        }
        /*
         * adicionamos as secretarias no xml
         */
        $sSqlSecretarias = $oDaoLicitaItens->sql_query_orc(
            null,
            "distinct o40_descr",
            null,
            "l21_codliclicita = {$this->iCodLicitacao}"
        );
        $rsSecretarias   = $oDaoLicitaItens->sql_record($sSqlSecretarias);

        $oXmlSecretarias = $oXml->addChild("secretarias");
        $aSecretarias    = db_utils::getCollectionByRecord($rsSecretarias);
        foreach ($aSecretarias as $oSecretaria) {
            $oXmlSecretarias->addChild("secretaria", utf8_encode($oSecretaria->o40_descr));
        }

        /*
         * adicionamos os elementos
         */
        $sSqlElemento = $oDaoLicitaItens->sql_query_inf(
            null,
            "distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural ",
            null,
            "l21_codliclicita={$this->iCodLicitacao}"
        );
        $rsElementos   = $oDaoLicitaItens->sql_record($sSqlElemento);
        $oXmlElementos = $oXml->addChild("elementos");
        $aElementos    = db_utils::getCollectionByRecord($rsElementos);
        foreach ($aElementos as $oElemento) {
            $oXmlElementos->addChild("elemento", utf8_encode($oElemento->estrutural));
        }

        return $oXml->asXML();
    }

    /**
     * Altera Situa��o de uma Licita��o
     *
     * @param integer  $iCodigoSituacao //C�digo da situa��o
     * @param string   $sObservacao     //observa��o do procedimento
     * @return void
     */

    public function alterarSituacao($iCodigoSituacao, $sObservacao = "")
    {

        //caso n�o exista transa��o ativa no BD
        if (!db_utils::inTransaction()) {
            throw new Exception('Sem transa��o Ativa', 'Lic-0');
        }

        $bPossuiJulgamento  = $this->hasJulgamento();
        $bPoissuiFornecedor = $this->hasFornecedor();

        switch ($iCodigoSituacao) {

            case 3:

                $bPossuiJulgamento  = $this->hasJulgamento();

                if ($bPossuiJulgamento) {
                    throw new Exception('A soLicita��o j� possui Julgamento, impossível alterar situa��o');
                }
                break;

            case 4:

                $bPoissuiFornecedor = $this->hasFornecedor();

                if ($bPoissuiFornecedor) {
                    throw new Exception('A soLicita��o j� possui Fornecedor, impossível alterar situa��o');
                }
                break;

            case 0:

                $this->retornaAndamento($sObservacao);
                return true;
                break;

            case 5:

                if (in_array($this->iCodigoSituacao, array(2, 3, 4))) {
                    throw new Exception("Esta Licita��o n�o encontra-se nas situa�ões Em andamento ou Julgada. Procedimento abortado.");
                }

                break;
        }

        /**
         * Incluimos a situacao  para licitacao
         */
        $oDaoLiclicita                  = db_utils::getDao("liclicita");
        $oDaoLiclicita->l20_codigo      = $this->iCodLicitacao;
        $oDaoLiclicita->l20_licsituacao = $iCodigoSituacao;
        $oDaoLiclicita->alterar_situacao($this->iCodLicitacao);

        if ($oDaoLiclicita->erro_status == 0) {

            $sErro = "Erro ao alterar status da Licita��o:\n\n Erro técnico: erro na altera��o de status /{$oDaoLiclicita->erro_msg}";
            throw new Exception($sErro, 4);
        }

        /**
         * Incluimos a nova situa��o
         */
        $oDaolicSituacao = db_utils::getDao("liclicitasituacao");
        $oDaolicSituacao->l11_data        = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaolicSituacao->l11_hora        = db_hora();
        $oDaolicSituacao->l11_licsituacao = $iCodigoSituacao;
        $oDaolicSituacao->l11_obs         = $sObservacao;
        $oDaolicSituacao->l11_id_usuario  = db_getsession("DB_id_usuario");
        $oDaolicSituacao->l11_liclicita   = $this->iCodLicitacao;
        $oDaolicSituacao->incluir(null);

        if ($oDaolicSituacao->erro_status == 0) {

            $sErro = "Erro ao alterar status da Licita��o:\n\n Erro técnico: erro ao incluir nova situa��o /{$oDaolicSituacao->erro_msg}";
            throw new Exception($sErro, 5);
        }
        return true;
    }

    /*
     * Cancela uma licitacao deserta
     */
    public function retornaAndamento($sObservacao = "")
    {

        $oDaoLiclicita                  = db_utils::getDao("liclicita");
        $oDaoLiclicita->l20_codigo      = $this->iCodLicitacao;
        $oDaoLiclicita->l20_licsituacao = "0";
        $oDaoLiclicita->alterar_situacao($this->iCodLicitacao);

        if ($oDaoLiclicita->erro_status == 0) {

            $sErro = "erro ao incluir licita��o deserta:\n{$oDaoLiclicita->erro_msg}";
            throw new Exception($sErro, 4);
        }

        /*
        * incluimos a situa��o
        */
        $oDaolicSituacao = db_utils::getDao("liclicitasituacao");
        $oDaolicSituacao->l11_data        = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaolicSituacao->l11_hora        = db_hora();
        $oDaolicSituacao->l11_licsituacao = "0";
        $oDaolicSituacao->l11_obs         = $sObservacao;
        $oDaolicSituacao->l11_id_usuario  = db_getsession("DB_id_usuario");
        $oDaolicSituacao->l11_liclicita   = $this->iCodLicitacao;
        $oDaolicSituacao->incluir(null);

        if ($oDaolicSituacao->erro_status == 0) {

            $sErro = "erro ao incluir licita��o deserta:\n{$oDaolicSituacao->erro_msg}";
            throw new Exception($sErro, 5);
        }
    }

    public function getInfoLog()
    {

        $oDaoitensLog = db_utils::getDao("liclicitaitemlog");
        $sSqlLog      = $oDaoitensLog->sql_query_file($this->iCodLicitacao);
        $rsLog        = $oDaoitensLog->sql_record($sSqlLog);
        if ($oDaoitensLog->numrows == 1) {

            $oLog = db_utils::fieldsMemory($rsLog, 0);
            $oXML = new SimpleXMLElement($oLog->l14_xml);
        }
        return $oXML;
    }

    /**
     * Retorna o registro de pre�o da solicitacao
     * @return compilacaoRegistroPreco
     */
    public function getCompilacaoRegistroPreco()
    {

        if ($this->oRegistroPreco == null) {

            $sSqlRegistroPreco  = "select distinct pc10_numero                                                                ";
            $sSqlRegistroPreco .= "  from liclicitem                                                                          ";
            $sSqlRegistroPreco .= "       inner join pcprocitem on liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem ";
            $sSqlRegistroPreco .= "       inner join solicitem  on pcprocitem.pc81_solicitem    = solicitem.pc11_codigo       ";
            $sSqlRegistroPreco .= "       inner join solicita   on solicitem.pc11_numero        = solicita.pc10_numero        ";
            $sSqlRegistroPreco .= " where l21_codliclicita = {$this->iCodLicitacao}                                           ";
            $sSqlRegistroPreco .= "   and pc10_solicitacaotipo = 6                                                            ";

            $rsRegistroPreco   = db_query($sSqlRegistroPreco);
            if (pg_num_rows($rsRegistroPreco) == 1) {
                $this->oRegistroPreco = new compilacaoRegistroPreco(db_utils::fieldsMemory($rsRegistroPreco, 0)->pc10_numero);
            } else {
                throw new Exception('Licitacao n�o possui registros de pre�os.');
            }
        }
        return $this->oRegistroPreco;
    }


    static function getItensPorFornecedor($aLicitacoes, $iFornecedor, $lTipo)
    {

        $oDaoLicilicitem  = db_utils::getDao("liclicitem");

        $sLista = $aLicitacoes;
        $sCampos          = "l21_codigo as codigo, pc01_codmater as codigomaterial,";
        $sCampos         .= "pc01_descrmater as material, pc23_vlrun as valorunitario,";
        $sCampos         .= "pc01_servico as servico, 1 as origem, (case
		when pc18_codele is null 
		then (select pc07_codele from pcmaterele where pc07_codmater = pc01_codmater limit 1) else pc18_codele
	end) as elemento,";
        $sCampos         .= "pc23_quant as quantidade, pc23_valor as valortotal,l20_numero as numero";
        $sSqlLicitacoes   = $oDaoLicilicitem->sql_query_soljulg(
            null,
            $sCampos,
            "l21_codigo, l21_ordem",
            "pc21_numcgm= {$iFornecedor}
      and ac24_sequencial is null
      and l21_codliclicita in({$sLista})"
        );
        //echo $sSqlLicitacoes; die();

        $rsLicitacoes    = $oDaoLicilicitem->sql_record($sSqlLicitacoes);
        return db_utils::getCollectionByRecord($rsLicitacoes, false, false, true);
    }

    /**
     * retorna todas as licita�ões que possuem um item ganho pelo credor.
     *
     * @param integer $iFornecedor codigo do fornecedor
     * @param boolean $lValidaAutorizadas valida autorizadas
     * @param boolean $lValidaHomologacao valida homologadas
     * @return array
     */
    static function getLicitacoesByFornecedor($iFornecedor, $lValidaAutorizadas = false, $lValidaHomologacao = false, $filtro = '')
    {

        $oDaoLicilicitem = db_utils::getDao("liclicitem");
        $sWhere          = '';
        if ($lValidaAutorizadas) {

            $sWhere .= " and not exists (";
            $sWhere .= "                 select 1 ";
            $sWhere .= "                   from empautoriza  ";
            $sWhere .= "                        inner join empautitem           on e55_autori                      = e54_autori";
            $sWhere .= "                        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
            $sWhere .= "                                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori";
            $sWhere .= "                        inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
            $sWhere .= "                        inner join liclicitem           on pc81_codprocitem                = l21_codpcprocitem";
            $sWhere .= "                  where l21_codliclicita = l20_codigo";
            $sWhere .= "                    and e54_anulad is null";
            $sWhere .= " )";
        }

        if ($lValidaHomologacao) {
            $sWhere .= "and exists( select 1 from homologacaoadjudica where l20_codigo = l202_licitacao and l202_datahomologacao is not null)";
        }

        $sCampos         = "distinct l20_codigo as licitacao, l20_objeto as objeto, l20_numero as numero";
        $sCampos        .= ", pc21_numcgm as cgm, l20_numero as numero_exercicio, l20_datacria as data";
        $sCampos        .= ", l20_edital as edital"; // campo adicionado para atender novas demandas do sicom 2014
        $sWhere = "pc21_numcgm = {$iFornecedor} and ac24_sequencial is null {$sWhere}";
        if ($filtro) {
            $sWhere .= " AND l03_pctipocompratribunal {$filtro}";
        }

        $sSqlLicitacoes  = $oDaoLicilicitem->sql_query_soljulg(
            null,
            $sCampos,
            "l20_codigo",
            $sWhere,
            true
        );


        $rsLicitacoes    = $oDaoLicilicitem->sql_record($sSqlLicitacoes);
        return db_utils::getCollectionByRecord($rsLicitacoes, false, false, true);
    }

    /**
     * @param $iFornecedor
     * @param bool $lValidaAutorizadas
     * @param bool $lValidaHomologacao
     * @return stdClass[]
     * OC8339
     */
    public function getLicByFornecedorCredenciamento($iFornecedor, $lValidaAutorizadas = false, $lValidaHomologacao = false)
    {

        $oDaoLicilicitem = db_utils::getDao("liclicitem");
        $sWhere          = '';
        if ($lValidaAutorizadas) {

            $sWhere .= " and not exists (";
            $sWhere .= "                 select 1 ";
            $sWhere .= "                   from empautoriza  ";
            $sWhere .= "                        inner join empautitem           on e55_autori                      = e54_autori";
            $sWhere .= "                        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
            $sWhere .= "                                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori";
            $sWhere .= "                        inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
            $sWhere .= "                        inner join liclicitem           on pc81_codprocitem                = l21_codpcprocitem";
            $sWhere .= "                  where l21_codliclicita = l20_codigo";
            $sWhere .= "                    and e54_anulad is null";
            $sWhere .= " )";
        }

        if ($lValidaHomologacao) {
            $sWhere .= "and exists( select 1 from homologacaoadjudica where l20_codigo = l202_licitacao)";
        }

        $sCampos         = "distinct l20_codigo as licitacao, l20_objeto as objeto, l20_numero as numero";
        $sCampos        .= ", l20_numero as numero_exercicio, l20_datacria as data";
        $sCampos        .= ", l20_edital as edital"; // campo adicionado para atender novas demandas do sicom 2014

        $sSqlLicitacoes  = $oDaoLicilicitem->sql_query_soljulgCredenciamento(
            null,
            $sCampos,
            "l20_codigo",
            "l205_fornecedor = {$iFornecedor} AND cflicita.l03_pctipocompratribunal IN (102,103)
            {$sWhere}"
        );

        $rsLicitacoes    = $oDaoLicilicitem->sql_record($sSqlLicitacoes);
        return db_utils::getCollectionByRecord($rsLicitacoes, false, false, true);
    }
    //FIM OC8339

    static function getItensPorFornecedorCredenciamento($iFornecedor, $iLicitacao, $anousu = null)
    {

        $oDaoLiclicitem  = db_utils::getDao("liclicitem");

        $sCampos = "l21_codigo AS codigo,
        pc01_codmater AS codigomaterial,
        pc01_descrmater AS material,
        (SELECT si02_vlprecoreferencia
        FROM itemprecoreferencia
        WHERE si02_itemproccompra = pcorcamitemproc.pc31_orcamitem) AS valorunitario,
        pc01_servico AS servico,
        pc11_servicoquantidade,
        1 AS origem,
        pc18_codele AS elemento,
        pc23_quant AS quantidade,
        (SELECT si02_qtditem * si02_vlprecoreferencia
        FROM itemprecoreferencia
        WHERE si02_itemproccompra = pcorcamitemproc.pc31_orcamitem) AS valortotal,
        l20_numero AS numero,
        sum(l213_qtdcontratada) AS l213_qtdcontratada,
        sum(l213_valorcontratado) AS l213_valorcontratado,
        l21_ordem AS sequencia";
        $sWhere  = "l20_codigo = {$iLicitacao} AND l205_fornecedor = {$iFornecedor} and pc24_pontuacao = 1";
        $sWhere .= "GROUP BY l21_codigo,
        pc01_codmater,
        solicitem.pc11_servicoquantidade,
        pc23_vlrun,
        pc18_codele,
        pc23_quant,
        pc23_valor,
        l20_numero,
        pcorcamitemproc.pc31_orcamitem";
        $sSqlItensCred = $oDaoLiclicitem->sql_query_soljulgCredenciamento(null, $sCampos, null, $sWhere);
        $rsItenscred = db_query($sSqlItensCred);
        return db_utils::getCollectionByRecord($rsItenscred, false, false, true);
    }

    /**
     * Retorna o valor total parcial da licitacao
     *
     * @param integer_type $iCodigoItemProcesso
     * @param integer_type $iCodigoDotacao
     * @param integer_type $iOrcTipoRec
     * @return $oDadoValorParcial
     */
    public function getValoresParciais($iCodigoItemProcesso, $iCodigoDotacao, $iOrcTipoRec = null)
    {

        if (empty($iCodigoItemProcesso)) {
            throw new Exception("C�digo do item do processo n�o informado!");
        }

        if (empty($iCodigoDotacao)) {
            throw new Exception("C�digo da dota��o n�o informado!");
        }

        /**
         * Retorna somentes as autorizacoes das contrapartidas
         */
        $sWhereContrapartida = " and e56_orctiporec is null";
        if (!empty($iOrcTipoRec)) {
            $sWhereContrapartida = " and e56_orctiporec = {$iOrcTipoRec}";
        }

        $oDaoEmpAutItem    = db_utils::getDao("empautitem");
        $oDaoPcOrcam       = db_utils::getDao("pcorcam");

        $oDadoValorParcial = new stdClass();
        $oDadoValorParcial->nValorAutorizacao      = 0;
        $oDadoValorParcial->iQuantidadeAutorizacao = 0;
        $oDadoValorParcial->nValorItemJulgado      = 0;
        $oDadoValorParcial->iQuantidadeItemJulgado = 0;

        /**
         * Retorna o valor total da autorizacao de empenho da licitacao
         */
        $sCampos           = "sum(e55_vltot) as valorautorizacao,               ";
        $sCampos          .= "sum(e55_quant) as quantidadeautorizacao           ";
        $sWhere            = "          e73_pcprocitem = {$iCodigoItemProcesso} ";
        $sWhere           .= "      and e56_coddot     = {$iCodigoDotacao}      ";
        $sWhere           .= "      and e54_anulad is null                      ";
        $sWhere           .= "      {$sWhereContrapartida}                      ";
        $sWhere           .= " group by e55_vltot,                              ";
        $sWhere           .= "          e55_quant                               ";
        $sSqlAutorizacao   = $oDaoEmpAutItem->sql_query_itemdot(null, null, $sCampos, null, $sWhere);
        $rsSqlAutorizacao  = $oDaoEmpAutItem->sql_record($sSqlAutorizacao);
        if ($oDaoEmpAutItem->numrows > 0) {

            for ($iIndEmpAutItem = 0; $iIndEmpAutItem < $oDaoEmpAutItem->numrows; $iIndEmpAutItem++) {

                $oAutorizacao                               = db_utils::fieldsMemory($rsSqlAutorizacao, $iIndEmpAutItem);
                $oDadoValorParcial->nValorAutorizacao      += $oAutorizacao->valorautorizacao;
                $oDadoValorParcial->iQuantidadeAutorizacao += $oAutorizacao->quantidadeautorizacao;
            }
        }

        /**
         * Retorna o valor do item julgado na licitacao
         */
        $sCampos              = "pc23_quant, pc23_valor, pc13_valor, pc13_quant, pc11_vlrun, pc11_quant";
        $sWhere               = "l21_codpcprocitem = {$iCodigoItemProcesso} and pc24_pontuacao = 1";
        $sWhere              .= " and pc13_coddot  = {$iCodigoDotacao} ";
        $sWhereContrapartida  = " and pc19_orctiporec is null ";
        if ($iOrcTipoRec > 0) {
            $sWhereContrapartida = "  and pc19_orctiporec = {$iOrcTipoRec} ";
        }
        $sWhere .= $sWhereContrapartida;
        $sSqlPcOrcam       = $oDaoPcOrcam->sql_query_valitemjulglic(null, $sCampos, null, $sWhere);
        $rsSqlPcOrcam      = $oDaoPcOrcam->sql_record($sSqlPcOrcam);
        if ($oDaoPcOrcam->numrows > 0) {

            for ($iIndPcOrcam = 0; $iIndPcOrcam < $oDaoPcOrcam->numrows; $iIndPcOrcam++) {

                $oItemJulgado                               = db_utils::fieldsMemory($rsSqlPcOrcam, $iIndPcOrcam);
                $nPercentualDotacao = 0;

                if ($oItemJulgado->pc13_valor > 0 && $oItemJulgado->pc11_vlrun > 0) {
                    $nPercentualDotacao = ($oItemJulgado->pc13_valor * 100) / ($oItemJulgado->pc11_quant * $oItemJulgado->pc11_vlrun);
                }

                /**
                 * retorna o valor novo da dotacao; (pode ter um aumento/diminui��o do valor)
                 */
                $nValorDotacao          = round(($oItemJulgado->pc23_valor * $nPercentualDotacao) / 100, 2);
                $oDados->valordiferenca = $nValorDotacao;
                $oDadoValorParcial->nValorItemJulgado      += $nValorDotacao;
                $oDadoValorParcial->iQuantidadeItemJulgado += $oItemJulgado->pc23_quant;
            }
        }
        $oDadoValorParcial->nValorSaldoTotal = ($oDadoValorParcial->nValorItemJulgado - $oDadoValorParcial->nValorAutorizacao);
        return $oDadoValorParcial;
    }


    /**
     * retorna os itens que podem ser gerados autorizacao de empenho
     *
     */
    public function getItensParaAutorizacao()
    {

        $oDaoPcOrcamJulg      = db_utils::getDao("pcorcamjulg");
        $oDaoOrcReservaSol    = db_utils::getDao("orcreservasol");
        $this->oDaoParametros = db_utils::getDao("empparametro");

        $sCampos  = "l21_ordem,";
        $sCampos .= "pc01_codmater as codigomaterial,";
        $sCampos .= "pc01_descrmater as descricaomaterial,";
        $sCampos .= "pc01_servico as servico,";
        $sCampos .= "pc11_quant as quanttotalitem,";
        $sCampos .= "pc11_vlrun as valorunitario,";
        $sCampos .= "pc11_numero,";
        $sCampos .= "pc11_codigo as codigoitemsolicitacao,";
        $sCampos .= "pc11_servicoquantidade as servicoquantidade,";
        $sCampos .= "pc13_coddot as codigodotacao,";
        $sCampos .= "pc13_sequencial as codigodotacaoitem,";
        $sCampos .= "pc13_quant as quanttotaldotacao,";
        $sCampos .= "pc13_anousu as anodotacao,";
        $sCampos .= "pc13_valor as valordotacao,";
        $sCampos .= "pc17_unid,";
        $sCampos .= "pc17_quant,";
        $sCampos .= "pc23_orcamforne,";
        $sCampos .= "pc23_valor as valorfornecedor,";

        /**
         *  Alterado conforme solicitacao do henrique,
         *    antes o sistema buscava o obs do item a ser gravado na empautitem da pcorcamval
         *    troquei para buscar resumo do item da solicitacao, caso nao encontre no item
         *    busca resumo da solicitacao. T57360
         */

        $sCampos .= "case when trim(pc23_obs) <> '' then pc23_obs";
        $sCampos .= "     else pc10_resumo ";
        $sCampos .= " end as observacao,";
        $sCampos .= "pc10_resumo as observacao_solicita,";
        $sCampos .= "pc23_vlrun as valorunitariofornecedor,";
        $sCampos .= "pc23_quant as quantfornecedor,";
        $sCampos .= "z01_numcgm as codigofornecedor,";
        $sCampos .= "z01_nome as fornecedor,";
        $sCampos .= "m61_descr,";
        $sCampos .= "m61_usaquant,";
        $sCampos .= "pc10_numero as codigosolicitacao,";
        $sCampos .= "pc19_orctiporec as contrapartida,";
        $sCampos .= "pc81_codprocitem as codigoitemprocesso,";
        $sCampos .= "pc22_orcamitem,";
        $sCampos .= "pc18_codele as codigoelemento,";
        $sCampos .= "o56_descr as descricaoelemento,";
        $sCampos .= "o56_elemento as elemento,";
        $sCampos .= "pc28_solicitem as itemanulado";
        $sOrder = "z01_numcgm,pc13_coddot,pc18_codele, pc19_sequencial,l21_ordem, pc19_orctiporec,pc13_sequencial";
        $sWhere = "l20_codigo = {$this->iCodLicitacao} and pc24_pontuacao = 1 and pc10_instit = " . db_getsession("DB_instit");

        $sSqlPcOrcamJulg = $oDaoPcOrcamJulg->sql_query_gerautlic(null, null, $sCampos, $sOrder, $sWhere);
        $rsPcOrcamJulg   = $oDaoPcOrcamJulg->sql_record($sSqlPcOrcamJulg);
        $iRowPcOrcamJulg = $oDaoPcOrcamJulg->numrows;
        $aItens          = array();
        if ($iRowPcOrcamJulg > 0) {

            for ($i = 0; $i < $iRowPcOrcamJulg; $i++) {

                $oDados = db_utils::fieldsMemory($rsPcOrcamJulg, $i, false, false, true);

                /*
                 * calcula o percentual da dota��o em relacao ao valor total
                 */
                $nPercentualDotacao = 100;
                if ($oDados->valorunitario > 0 && $oDados->valordotacao > 0) {
                    $nPercentualDotacao = ($oDados->valordotacao * 100) / ($oDados->quanttotalitem * $oDados->valorunitario);
                }
                $oDados->percentual = $nPercentualDotacao;

                /**
                 * retorna o valor novo da dotacao; (pode ter um aumento/diminui��o do valor)
                 */
                $nValorDotacao          = round(($oDados->valorfornecedor * $nPercentualDotacao) / 100, 2);
                $oDados->valordiferenca = $nValorDotacao;

                /**
                 * Verificamos o valor reservado para o item
                 */
                $sSqlReservaDotacao    = $oDaoOrcReservaSol->sql_query_orcreserva(
                    null,
                    null,
                    "o80_codres,o80_valor",
                    "",
                    "o82_pcdotac = {$oDados->codigodotacaoitem}"
                );
                $rsReservaDotacao          = $oDaoOrcReservaSol->sql_record($sSqlReservaDotacao);
                $oDados->valorreserva      = 0;
                $oDados->dotacaocomsaldo   = true;
                $oDados->saldofinaldotacao = 0;

                if ($oDaoOrcReservaSol->numrows == 1) {
                    $oDados->valorreserva = db_utils::fieldsMemory($rsReservaDotacao, 0)->o80_valor;
                }

                $oValoresAutorizados          = $this->getValoresParciais(
                    $oDados->codigoitemprocesso,
                    $oDados->codigodotacao,
                    $oDados->contrapartida
                );
                $oDados->quantidadeautorizada = $oValoresAutorizados->iQuantidadeAutorizacao;
                $oDados->valorautorizado      = $oValoresAutorizados->nValorAutorizacao;
                $oDados->saldoautorizar       = $oValoresAutorizados->nValorSaldoTotal;
                $oDotacao = new Dotacao($oDados->codigodotacao, $oDados->anodotacao);
                $oDados->saldofinaldotacao    = $oDotacao->getSaldoAtualMenosReservado();
                $oDados->servico              = $oDados->servico == 't' ? true : false;

                /**
                 * Verificamos as quantidades executadas do item
                 */
                $oDados->saldoquantidade      = $oDados->quanttotaldotacao - $oDados->quantidadeautorizada;
                $oDados->saldovalor           = $oDados->valordiferenca    - $oDados->valorautorizado;

                /**
                 * Verifica se a dota��o tem saldo para poder autorizar o item
                 */
                if ($oDotacao->getSaldoAtualMenosReservado() <= 0 && $oDados->valorreserva == 0) {
                    $oDados->dotacaocomsaldo = false;
                }

                if ($oDotacao->getSaldoAtualMenosReservado() < ($oDados->quanttotalitem * $oDados->valorunitario) && $oDados->servico == false) {
                    $oDados->dotacaocomsaldo = false;

                    /**
                     * Verifica se o valor da reserva � suficiente para autorizar o item parcialmente
                     */
                    if ($oDados->valorreserva >= ($oDados->valorunitario * $oDados->saldoquantidade)) {
                        $oDados->dotacaocomsaldo = true;
                    }
                }
                /**
                 * Verificamos as quantidades executadas do item
                 */
                $oDados->saldoquantidade      = $oDados->quanttotaldotacao - $oDados->quantidadeautorizada;
                $oDados->saldovalor           = $oDados->valordiferenca    - $oDados->valorautorizado;
                /**
                 * Caso for servi�o e ele n�o for controlado por quantidade setamos o saldo de quantidade para 1
                 */
                if ($oDados->servico && $oDados->servicoquantidade == "f") {
                    $oDados->saldoquantidade = 1;
                }
                $oDados->autorizacaogeradas = $this->getAutorizacoes($oDados->codigoitemprocesso, $oDados->codigodotacao);

                /**
                 * busca o parametro de casas decimais para formatar o valor jogado na grid
                 */
                $iAnoSessao             = db_getsession("DB_anousu");
                $sWherePeriodoParametro = " e39_anousu = {$iAnoSessao} ";
                $sSqlPeriodoParametro   = $this->oDaoParametros->sql_query_file(null, "e30_numdec", null, $sWherePeriodoParametro);
                $rsPeriodoParametro     = $this->oDaoParametros->sql_record($sSqlPeriodoParametro);


                $oDados->valorunitariofornecedor = number_format(
                    $oDados->valorunitariofornecedor,
                    db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec,
                    '.',
                    ''
                );
                $aItens[] = $oDados;
            }

            return $aItens;
        }
    }

    /**
     * Retorna as autoriza��es geradas para o item.
     *
     * @param integer $iCodigoItemProcesso
     * @param integer $iCodigoDotacao
     * @param integer $iOrcTipoRec
     * TODO retornar objeto da autoriza��o
     */
    public function getAutorizacoes($iCodigoItemProcesso, $iCodigoDotacao, $iOrcTipoRec = null)
    {

        if (empty($iCodigoItemProcesso)) {
            throw new Exception("C�digo do item do processo n�o informado!");
        }

        if (empty($iCodigoDotacao)) {
            throw new Exception("C�digo da dota��o n�o informado!");
        }

        /**
         * Retorna somentes as autorizacoes das contrapartidas
         */
        $sWhereContrapartida = " and e56_orctiporec is null";
        if (!empty($iOrcTipoRec)) {
            $sWhereContrapartida = " and e56_orctiporec = {$iOrcTipoRec}";
        }

        $sCampos  = "distinct e55_autori as autorizacao                 ";
        $sWhere   = "          e73_pcprocitem = {$iCodigoItemProcesso} ";
        $sWhere  .= "      and e56_coddot     = {$iCodigoDotacao}      ";
        $sWhere  .= "      and e54_anulad is null                      ";
        $sWhere  .= "      {$sWhereContrapartida}                      ";
        $oDaoEmpAutItem  = db_utils::getDao("empautitem");
        $sSqlAutorizacao = $oDaoEmpAutItem->sql_query_itemdot(null, null, $sCampos, null, $sWhere);
        $rsAutorizacao   = $oDaoEmpAutItem->sql_record($sSqlAutorizacao);
        $aAutorizacoes   = array();

        for ($iRow = 0; $iRow < $oDaoEmpAutItem->numrows; $iRow++) {

            $oDadosAutorizacao = db_utils::fieldsMemory($rsAutorizacao, $iRow);
            $aAutorizacoes[]   = $oDadosAutorizacao->autorizacao;
        }

        return $aAutorizacoes;
    }

    /**
     * gera os dados para a autorizacao;
     *
     * @param array $aDados
     */
    public function gerarAutorizacoes($aDadosAutorizacao)
    {

        $aAutorizacoes  = array();

        /**
         * calcular reservas para a Solicitacao (quando parcial)
         * calcular orcreserva
         */
        $oDadosLicitacao   = $this->getDados();
        $oDaoOrcReservaSol = db_utils::getDao("orcreservasol");
        $oDaoOrcReserva    = db_utils::getDao("orcreserva");
        $oDaoPcdotac       = db_utils::getDao("pcdotac");
        $oDaoOrcReservaAut = db_utils::getDao("orcreservaaut");

        /**
         * Percorrendo as autoriza��es á gerar
         */
        foreach ($aDadosAutorizacao as $oDados) {

            $nValorTotal = 0;

            /**
             * Percorrendo os itens de cada autoriza��o � gerar
             */
            foreach ($oDados->itens as $oItem) {

                /**
                 * Para cada item temos uma reserva
                 */
                $nValorTotal    += (float) str_replace(',', '.', $oItem->valortotal);
                /**
                 * verificamos se exite reserva de saldo para a solicitacao;
                 * caso exista, devemos calcular a diferen�a entre o que deve ser gerado para a autoriza��o e a solicita��o
                 */
                $aReservas         = itemSolicitacao::getReservasSaldoDotacao($oItem->pcdotac);
                $nNovoValorReserva = (float) str_replace(',', '.', $oItem->valortotal);
                if (!empty($aReservas)) {

                    $nNovoValorReserva   = $aReservas[0]->valor - $oItem->valortotal;
                    if ($nNovoValorReserva < 0) {
                        $nNovoValorReserva = 0;
                    }

                    /**
                     * Exclu�mos a reserva da solicita��o e incluimos uma nova
                     */
                    $oDaoOrcReservaSol->excluir(null, "o82_codres = {$aReservas[0]->codigoreserva}");
                    if ($oDaoOrcReservaSol->erro_status == 0) {
                        throw new Exception($oDaoOrcReservaSol->erro_msg);
                    }

                    /**
                     * Excluir OrcReserva
                     */
                    $oDaoOrcReserva->excluir($aReservas[0]->codigoreserva);
                    if ($oDaoOrcReserva->erro_status == 0) {
                        throw new Exception($oDaoOrcReserva->erro_msg);
                    }
                }
                /**
                 * Inclu�mos os dados na OrcReserva e orcreservasol, caso o item ainda tenha valor dispon�vel
                 */
                $oSaldo = $this->getValoresParciais($oItem->codigoprocesso, $oDados->dotacao, $oDados->contrapartida);
            }
            /**
             * Salvamos a Autorizacao;
             */

            /**
             * Resumo da autoriza��o
             * Conforme Solicitado pela ocorr�ncia 1892, o resumo deve ser a informa��o preenchida na primeira tela do menu Mod. Licita��o >> Procedimentos >> Gera autoriza��o
             * @see: Ocorr�ncia 1892
             */
            $sResumo = $oDados->resumo;
            $oAutorizacao = new AutorizacaoEmpenho();
            /**
             * n�o pode-se setar o codigo da reserva da soLicita��o na Autorizacao.
             * A autorizacao gera um codigo de reserva quando inclusa
             */
            $oAutorizacao->setDesdobramento($oDados->elemento);
            $oAutorizacao->setDotacao($oDados->dotacao);
            $oAutorizacao->setContraPartida($oDados->contrapartida);
            $oFornecedor  = CgmFactory::getInstance('', $oDados->cgm);
            $oAutorizacao->setFornecedor($oFornecedor);
            $oAutorizacao->setValor($nValorTotal);
            $oAutorizacao->setTipoEmpenho($oDados->tipoempenho);
            $oAutorizacao->setCaracteristicaPeculiar($oDados->concarpeculiar);

            $aItemSolcitem = array();
            foreach ($oDados->itens as $oItem) {

                $oAutorizacao->addItem($oItem);
                $aItemSolcitem[] = $oItem->solicitem;
            }

            $oAutorizacao->setDestino($oDados->destino);
            $oAutorizacao->setContato($oDados->sContato);
            $oAutorizacao->setResumo(addslashes($sResumo));
            $oAutorizacao->setTelefone($oDados->sTelefone);
            $oAutorizacao->setTipoCompra($oDados->tipocompra);
            $oAutorizacao->setPrazoEntrega($oDados->prazoentrega);
            $oAutorizacao->setTipoLicitacao($oDados->sTipoLicitacao);
            $oAutorizacao->setTipoLicitacao($oDadosLicitacao->l03_tipo);
            $oAutorizacao->setNumeroLicitacao($oDados->iNumeroLicitacao);
            $oAutorizacao->setOutrasCondicoes($oDados->sOutrasCondicoes);
            $oAutorizacao->setCondicaoPagamento($oDados->condicaopagamento);
            $oAutorizacao->setNumeroLicitacao("{$oDadosLicitacao->l20_edital}/{$oDadosLicitacao->l20_anousu}");
            $oAutorizacao->setModalidade($oDadosLicitacao->l20_numero);
            $oAutorizacao->setDataAutorizacao(date("Y-m-d", db_getsession('DB_datausu')));
            /**
             * Verifico o tipo de origem dalic4_editaldocumentos compra pelo codigo do tribunal
             *  @OC7425
             *  1 - n�o ou dispensa por valor (art. 24, I e II da Lei 8.666/93);
             *  2 - Licita��o;
             *  3 - Dispensa ou Inexigibilidade;
             *  4 - Ades�o � ata de registro de pre�os;
             *  5 - Licita��o realizada por outro �rg�o ou entidade;
             *  6 - Dispensa ou Inexigibilidade realizada por outro �rg�o ou entidade;
             *  7 - Licita��o - Regime Diferenciado de Contrata��es P�blicas - RDC, conforme Lei no 12.462/2011
             *  8 - Licita��o realizada por consorcio p�blico
             *  9 - Licita��o realizada por outro ente da federa��o
             */
            $tipoLicitacao = array(52, 48, 49, 50, 51, 53, 54);
            $tipoDispensaInex = array(100, 101, 102);
            if (in_array($oDadosLicitacao->l44_sequencial, $tipoLicitacao)) {
                $oAutorizacao->setSTipoorigem(2);
            } elseif (in_array($oDadosLicitacao->l44_sequencial, $tipoDispensaInex)) {
                $oAutorizacao->setSTipoorigem(3);
            }
            $oAutorizacao->setSTipoautorizacao(2);
            $oAutorizacao->salvar();

            $sProcessoAdministrativo = null;

            if (isset($oDados->e150_numeroprocesso) && !empty($oDados->e150_numeroprocesso)) {
                $sProcessoAdministrativo = db_stdClass::normalizeStringJsonEscapeString($oDados->e150_numeroprocesso);
            }

            /**
             * Buscar o C�digo do processo da tabela solicitaprotprocesso e incluir na empautorizaprotprocesso caso tenha
             */
            $oDaoSolicitem             = db_utils::getDao("solicitem");
            $sCodigosItens             = implode(",", $aItemSolcitem);
            $sSqlBuscaSolicitem        = $oDaoSolicitem->sql_query_solicitaprotprocesso(null, "solicitaprotprocesso.*", null, "pc11_codigo in ({$sCodigosItens})");
            $rsBuscaSolicitem          = $oDaoSolicitem->sql_record($sSqlBuscaSolicitem);
            $oDadoSolicitaProtProcesso = db_utils::fieldsMemory($rsBuscaSolicitem, 0);

            if (empty($sProcessoAdministrativo) &&  !empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso)) {
                $sProcessoAdministrativo = $oDadoSolicitaProtProcesso->pc90_numeroprocesso;
            }

            if (!empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso)) {

                $oDaoEmpAutorizaProcesso                      = db_utils::getDao("empautorizaprocesso");
                $oDaoEmpAutorizaProcesso->e150_sequencial     = null;
                $oDaoEmpAutorizaProcesso->e150_empautoriza    = $oAutorizacao->getAutorizacao();
                $oDaoEmpAutorizaProcesso->e150_numeroprocesso = $sProcessoAdministrativo;
                $oDaoEmpAutorizaProcesso->incluir(null);
                if ($oDaoEmpAutorizaProcesso->erro_status == 0) {

                    $sMensagemProcessoAdministrativo  = "Ocorreu um erro para incluir o n�mero do processo administrativo ";
                    $sMensagemProcessoAdministrativo .= "na autoriza��o de empenho.\n\n{$oDaoEmpAutorizaProcesso->erro_msg}";
                    throw new Exception($sMensagemProcessoAdministrativo);
                }
            }
            $aAutorizacoes[] = $oAutorizacao->getAutorizacao();
        }
        return $aAutorizacoes;
    }

    /**
     * Pega os itens de uma Licita��o
     * @return array
     */
    public function getItens()
    {

        if (count($this->aItensLicitacao) == 0) {

            $oDaoLicLicitem     = db_utils::getDao("liclicitem");
            $sSqlLicLicitem     = $oDaoLicLicitem->sql_query(
                null,
                "l21_codigo",
                "l21_ordem",
                "l21_codliclicita = {$this->iCodLicitacao}"
            );

            $rsLicLicitem       = $oDaoLicLicitem->sql_record($sSqlLicLicitem);
            $iNumRowsLiclicitem = $oDaoLicLicitem->numrows;


            for ($iRow = 0; $iRow < $iNumRowsLiclicitem; $iRow++) {

                $oDadoLicLicitem = db_utils::fieldsMemory($rsLicLicitem, $iRow);
                $oItemLicitacao  = new ItemLicitacao($oDadoLicLicitem->l21_codigo);
                $this->aItensLicitacao[] = $oItemLicitacao;
            }
        }
        return $this->aItensLicitacao;
    }

    /**
     * Pega os itens de acordo com o processo de compras
     * @param integer $iProcesso
     */
    public function getItensPorProcessoDeCompras($iProcesso)
    {

        $aItens        = $this->getItens();
        $aItensRetorno = array();
        foreach ($aItens as $oItem) {

            if ($oItem->getProcessoCompra() == $iProcesso) {
                $aItensRetorno[] = $oItem;
            }
        }
        return $aItensRetorno;
    }

    /**
     * Desvincula o processo de compras de uma licitacao
     * @param  integer $iProcesso
     * @return boolean
     */
    public function desvinculaProcessoDeCompras($iProcesso)
    {

        $aItensProcesso        = $this->getItensPorProcessoDeCompras($iProcesso);
        $aItensProcessoCompras = array();
        $aCodLicLicitem        = array();

        //echo ("<pre>".print_r($aItensProcesso, 1)."</pre>");exit;
        foreach ($aItensProcesso as $oItemLicitacao) {

            $iCodItemProcCompra = $oItemLicitacao->getItemProcessoCompras();
            /**
             * Verifica se existem fornecedores vinculados ao processo de compras
             */
            $sSqlBuscaItem  = " select distinct z01_numcgm, z01_nome";
            $sSqlBuscaItem .= "   from pcorcamitemlic";
            $sSqlBuscaItem .= "        inner join pcorcamitem  on pc22_orcamitem = pc26_orcamitem   ";
            $sSqlBuscaItem .= "        inner join pcorcamforne on    pc21_codorc = pc22_codorc      ";
            $sSqlBuscaItem .= "        inner join cgm          on     z01_numcgm = pc21_numcgm      ";
            $sSqlBuscaItem .= "        inner join liclicitem   on     l21_codigo = pc26_liclicitem  ";
            $sSqlBuscaItem .= "  where l21_codpcprocitem  = {$iCodItemProcCompra} ";
            $rsBuscaItem    = db_query($sSqlBuscaItem);
            $iTotalLinhas   = pg_num_rows($rsBuscaItem);

            /**
             * Caso exista fornecedores cadastrados o processo é abortado, do contrário é excluido os registros em
             * liclicitemlote e liclicitem
             */
            if ($iTotalLinhas > 0) {


                $sItensMovimento = "";
                for ($iRow = 0; $iRow < $iTotalLinhas; $iRow++) {

                    $oDadosItem       = db_utils::fieldsMemory($rsBuscaItem, $iRow);
                    $sItensMovimento .= $oDadosItem->z01_numcgm . " - " . $oDadosItem->z01_nome . "\n";
                }
                $sMsgException = "Desvincule os fornecedores abaixo dos processos de compras selecionados.\n\n{$sItensMovimento}";
                throw new Exception($sMsgException);
            } else {
                $oItemLicitacao->remover($oItemLicitacao->getCodigo());
            }
        }
    }

    /**
     * Busca as solicita��es que tem dota��o do ano anterior.
     * @return mixed
     */
    public function getSolicitacoesDotacaoAnoAnterior()
    {

        $oDaoLicLicitem   = db_utils::getDao("liclicitem");
        $sWhereDotacao    = "l21_codliclicita = {$this->getCodigo()} and pc13_anousu < " . db_getsession("DB_anousu");
        $sCamposDotacao   = "distinct pc11_numero as solicita";
        $sSqlBuscaDotacao = $oDaoLicLicitem->sql_query_orc(null, $sCamposDotacao, null, $sWhereDotacao);
        $rsBuscaDotacao   = $oDaoLicLicitem->sql_record($sSqlBuscaDotacao);
        $iRowDotacao      = $oDaoLicLicitem->numrows;
        $aSolicitacao     = array();

        if ($iRowDotacao > 0) {

            for ($iRow = 0; $iRow < $iRowDotacao; $iRow++) {

                $iSolicita      = db_utils::fieldsMemory($rsBuscaDotacao, $iRow)->solicita;
                $aSolicitacao[] = $iSolicita;
            }
        }
        return $aSolicitacao;
    }

    public function getEdital()
    {
        return $this->iNumeroEdital;
    }

    public function alterarObservacaoSituacao($iSequencialLicitacao, $sObservacao)
    {

        $oDaoLicLicitaSituacao = db_utils::getDao('liclicitasituacao');
        //busco a ultima situacao deserta
        $sSqlSituacao = $oDaoLicLicitaSituacao->sql_query(null, "l11_sequencial", null, "l11_liclicita = {$iSequencialLicitacao} and l11_licsituacao = 3 ORDER BY l11_sequencial desc limit 1");
        $rsSequencialLiclicitasituacao = $oDaoLicLicitaSituacao->sql_record($sSqlSituacao);
        $iSequencialLicitacaoSituacao = db_utils::fieldsMemory($rsSequencialLiclicitasituacao, 0);
        $oDaoLicLicitaSituacao->l11_obs        = "{$sObservacao}";
        $oDaoLicLicitaSituacao->l11_sequencial = $iSequencialLicitacaoSituacao->l11_sequencial;
        $oDaoLicLicitaSituacao->alterar($iSequencialLicitacaoSituacao->l11_sequencial);

        if ($oDaoLicLicitaSituacao->erro_status == 0) {

            $sMsgErro  = "n�o foi possivel alterar a observa��o\n";
            $sMsgErro .= $oDaoLicLicitaSituacao->erro_msg;
            throw new Exception($sMsgErro);
        }
    }

    public function hasJulgamento()
    {

        $oDaoLicLicita  = db_utils::getDao('liclicita');
        $sSQL           = $oDaoLicLicita->sql_query_julgamento_licitacao($this->iCodLicitacao);
        $rsLiLicita     = $oDaoLicLicita->sql_record($sSQL);
        $iRowJulgamento = $oDaoLicLicita->numrows;

        $lRetorno       = false;
        if ($iRowJulgamento > 0) {
            $lRetorno = true;
        }
        return $lRetorno;
    }


    public function hasFornecedor()
    {

        if (count($this->getFornecedor()) > 0) {

            return true;
        }
        return false;
    }

    /**
     * Retorna um array de fornecedores.
     */
    public function getFornecedor()
    {

        if (count($this->aFornecedores) == 0) {

            $sWhereFornecedor           = "l20_codigo = {$this->getCodigo()}";
            $oDaoOrcamentoItemLicitacao = db_utils::getDao('pcorcamitemlic');
            $sSqlBuscaFornecedor        = $oDaoOrcamentoItemLicitacao->sql_query_fornecedores(null, "distinct z01_numcgm, z01_nome", null, $sWhereFornecedor);
            $rsBuscaFornecedor          = $oDaoOrcamentoItemLicitacao->sql_record($sSqlBuscaFornecedor);

            if ($oDaoOrcamentoItemLicitacao->numrows > 0) {
                $this->aFornecedores = db_utils::getCollectionByRecord($rsBuscaFornecedor);
            }
        }
        return $this->aFornecedores;
    }


    /**
     * funcao para verifica saldo disponivel num determinada modalidade de Licita��o
     *
     * @param $iModadalidade  sequencial da modalidade
     * @param $iItem          codigo do item a ser comprado
     * @param $dtJulgamento   data do julgamento
     * @return boolean
     *
     */
    public static function verificaSaldoModalidade($iModalidade, $iItem, $dtJulgamento)
    {

        $oRetornVerificacao = new stdClass();
        $oDaoPcOrcamItem    = db_utils::getDao("pcorcamitem");
        $oDaoModalidade     = db_utils::getDao("cflicitavalores");

        /*
         * montamos objeto com dados da modalidade, para verifica��o posterior.
         */
        $sWhere          = "     l40_codfclicita = {$iModalidade}";
        $sWhere         .= " and l40_datainicial <= '{$dtJulgamento}'";
        $sWhere         .= " and l40_datafinal   >= '{$dtJulgamento}'";
        $sSqlModalidade  = $oDaoModalidade->sql_query(null, "cflicitavalores.*,cflicita.* ", null, $sWhere);

        $rsModalidade    = $oDaoModalidade->sql_record($sSqlModalidade);
        if ($oDaoModalidade->numrows <= 0) {
            /*
             * se caso nao encontre faixa de valor pra modalidade, significa
             * que ela n�o sera controlada por valores.
             */
            $oRetornVerificacao->lPossuiSaldo = true;
            $oRetornVerificacao->sMensagem    = '';
            return $oRetornVerificacao;
        }
        $oDadosModalidade = db_utils::fieldsMemory($rsModalidade, 0);

        /*
         * buscamos dados do material em questão com base no item dentro do orcamento pc22_orcamitem
         */
        $sCampos     = "pc01_codmater, sum(pc11_quant * pc11_vlrun) as total ";
        $sWhereItem  = "pc22_orcamitem = {$iItem} group by pc01_codmater";

        $sSqlMaterial = $oDaoPcOrcamItem->sql_query_saldoModalidade(null, $sCampos, null, $sWhereItem);
        $rsMaterial   = $oDaoPcOrcamItem->sql_record($sSqlMaterial);

        if ($oDaoPcOrcamItem->numrows <= 0) {
            throw new Exception("ERRO [ 1 ] - Verificando saldo da Modalidade - Item do Processo de Compra n�o Encontrado.");
        }

        /*
         * montamos objeto com dados domaterial a ser analisado
         */
        $oDadosMaterial         = db_utils::fieldsMemory($rsMaterial, 0);
        $oRetornVerificacao->lPossuiSaldo = true;
        $oRetornVerificacao->sMensagem    = '';
        $oMaterial              = new MaterialCompras($oDadosMaterial->pc01_codmater);
        $iCodigoMaterialPcMater = $oMaterial->getMaterial();

        /*
         * com o codigo do material da pcmater, verificamos
         * todas compras que ja foram feitas para esse material
         * nessa modalidade nesse periodo de julgamento;
         */

        $sWhereMateriaisComprados  = "     pc16_codmater   = {$iCodigoMaterialPcMater} ";
        $sWhereMateriaisComprados .= " and l20_codtipocom  = {$iModalidade}            ";
        $sWhereMateriaisComprados .= " and pc26_orcamitem != {$iItem}                  "; // e o item que esta sendo julgado n�o venha junto com os ja julgados

        $sCamposMateriaisComprados = "sum(pc11_quant * pc11_vlrun) as total ";
        $sSqlMateriaisComprados    = $oDaoPcOrcamItem->sql_query_saldoModalidade(null, $sCamposMateriaisComprados, null, $sWhereMateriaisComprados);
        $rsMateriaisComprados      = $oDaoPcOrcamItem->sql_record($sSqlMateriaisComprados);


        $aDadosMateriaisComprados = db_utils::getCollectionByRecord($rsMateriaisComprados);

        /*
         * com os dados dos materias ja adquiridos para a modalidade
         * verificamos os valores totais dos itens na adquiridos e o saldo restante da modalidade
         * dentro do preiodo do julgamento
         */
        $oMateriaisComprados = db_utils::fieldsMemory($rsMateriaisComprados, 0);

        /*
         * verificamos se a data do julgamento esta entre o periodo da modalidade
         * para verificarmos valores
         */
        if (($dtJulgamento >= $oDadosModalidade->l40_datainicial) &&  ($dtJulgamento <=  $oDadosModalidade->l40_datafinal)) {

            /*
             * verificamos a soma do total do material julgado, com as somas totais dos
             * materias iguais ja comprados para a modalidade e comparamos se excede o total da modalidade
             */
            $nValorTotalMaterial = $oDadosMaterial->total;
            $nTotalGasto         = $oMateriaisComprados->total;
            $nTotalModalidade    = $oDadosModalidade->l40_valormaximo;
            $nTotalDesejado      = ($nValorTotalMaterial + $nTotalGasto);

            if ($nTotalDesejado > $nTotalModalidade) {

                $sErroMsg  = "ERRO [ 2 ] - Modalidade {$iModalidade} - {$oDadosModalidade->l03_descr} ";
                $sErroMsg .= "Sem Saldo para o Item {$iCodigoMaterialPcMater} - {$oMaterial->getDescricao()}";

                $oRetornVerificacao->lPossuiSaldo = false;
                $oRetornVerificacao->sMensagem    = $sErroMsg;
            }
        }
        return $oRetornVerificacao;
    }


    public static function getDescricaoModalidade($iModalidade)
    {

        $oDaoModalidade = db_utils::getDao("cflicita");
        $sSqlModalidade = $oDaoModalidade->sql_query_file($iModalidade);
        $rsModalidade   = $oDaoModalidade->sql_record($sSqlModalidade);
        $oModalidade    = db_utils::fieldsMemory($rsModalidade, 0);
        return $oModalidade; //l03_descr;
    }

    /**
     * @return LicitacaoModalidade
     */
    public function getModalidade()
    {
        return new LicitacaoModalidade($this->iCodigoModalidade);
    }

    /**
     * Retorna a situa��o da Licita��o
     * @return SituacaoLicitacao
     */
    public function getSituacao()
    {

        if ($this->iCodigoSituacao != "") {
            $this->oSituacaoLicitacao = new SituacaoLicitacao($this->iCodigoSituacao);
        }
        return $this->oSituacaoLicitacao;
    }

    public function getComissao()
    {

        $aComissao = array();
        if ($this->iCodLicitacao == null) {

            throw new exception("C�digo da licitacao nulo");
            return false;
        }
        $oDaoLiclicita  = db_utils::getDao("liclicita");
        $sCampos = " l20_instit||'-'||nomeinst as instit,
      ender,
       l20_edital||'/'||l20_anousu AS processo,
       l03_descr||' Nº:'||l20_numero as modalidade,
       l20_objeto,
       z01_nome,
       case l46_tipo
       when 1 then 'Leiloeiro'
       when 2 then 'Membro/Equipe de Apoio'
       when 3 then 'Presidente'
       when 4 then 'Secretario'
       when 5 then 'Servidor Designado'
       when 6 then 'Pregoeiro'
       end as l46_tipo";

        $rsMembros  = $oDaoLiclicita->sql_record($oDaoLiclicita->sql_query_comissao_pregao($this->iCodLicitacao, $sCampos));
        if ($oDaoLiclicita->numrows > 0) {

            for ($iInd = 0; $iInd < $oDaoLiclicita->numrows; $iInd++) {

                $aComissao[] = db_utils::fieldsMemory($rsMembros, $iInd);
            }
        }
        return $aComissao;
    }
}
