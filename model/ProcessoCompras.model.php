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


class ProcessoCompras
{

    const ARQUIVO_MENSAGEM = 'patrimonial.compras.ProcessoCompras.';

    /**
     * Tipos de processo de compra
     */
    const TIPO_ITEM        = 1;
    const TIPO_LOTE        = 2;


    /**
     * Tipos de Criterios de Adjudicacao
     */
    const CRITERIO_DESC_TABELA = 1;
    const CRITERIO_MENOR_TAXA  = 2;
    const CRITERIO_OUTROS      = 3;

    /**
     * Codigo de um processo de compras
     * @var integer
     */
    protected $iCodigo;


    /**
     * Data de emiss�o do processo de compras
     */
    protected $dtDataEmissao;

    /**
     * Resumo do Processo de Compras
     */
    protected $sResumo;


    /**
     * Departamento que incluiu o departamento
     */
    protected $iCodigoDepartamento;


    /**
     * Descricao do departamento
     */
    protected $sDescricaoDepartamento;

    /**
     * Situaao do processo de compras
     */
    protected $iSituacao = 2;

    /**
     * C�digo do usu�rio que incluiu o processo de compras
     */
    protected $iUsuario;

    /**
     * NOme do usuario que emitiu o processo de compras
     * @var string
     */
    protected $sNomeUsuario;
    /**
     * Array de itens de um processo de compras
     * @var array
     */
    protected $aItens = array();

    /**
     * Lotes Pertencentes ao processo de Compras
     * @var LoteProcessoCompra[]
     */
    protected $aLotes = array();

    /**
     * Tipo de processo de compra
     * @var integer
     */
    protected $iTipoProcesso = 1;

    /** OC3770
     * Criterio de Adjudicacao
     * @var integer
     */
    protected $iCriterioAdjudicacao = 3;
    /*FIM - OC3770*/

    /**
     * Tipo de Numero da Dispensa
     * @var integer
     */
    protected $iNumeroDispensa = 0;

    /**
     * Dispensa por valor
     * @var string
     */
    protected $sDispensaPorValor;

    /**
     * Orcamento Sigiloso
     * @var string
     */
    protected $sOrcamentoSigiloso;

    /**
     * Orcamento SubContratacao
     * @var string
     */
    protected $sSubContratacao;

    /**
     * Orcamento DadosComplementares
     * @var string
     */
    protected $sDadosComplementares;

    /**
     * Amparo legal
     * @var string
     */
    protected $iAmparolegal;

    protected  $iModalidadeContratacao;

    /**
     * Amparo legal
     * @var integer
     */
    protected $iCategoriaprocesso;

    protected $iCriteriojulgamento;

    /**
     *
     */
    function __construct($iCodigo = null)
    {

        if (!empty($iCodigo)) {

            $oDaoPcProc = db_utils::getDao("pcproc");
            $sSqlPcProc = $oDaoPcProc->sql_query($iCodigo);
            $rsPcProc   = $oDaoPcProc->sql_record($sSqlPcProc);
            if ($oDaoPcProc->numrows > 0) {

                $oDadosProcesso = db_utils::fieldsMemory($rsPcProc, 0);
                $this->setCodigo($iCodigo);
                $this->setDataEmissao(db_formatar($oDadosProcesso->pc80_data, 'd'));
                $this->setCodigoDepartamento($oDadosProcesso->pc80_depto);
                $this->setDescricaoDepartamento($oDadosProcesso->descrdepto);
                $this->setResumo($oDadosProcesso->pc80_resumo);
                $this->setSituacao($oDadosProcesso->pc80_situacao);
                $this->setUsuario($oDadosProcesso->pc80_usuario);
                $this->setNomeUsuario($oDadosProcesso->nome);
                $this->setTipoProcesso($oDadosProcesso->pc80_tipoprocesso);
                /*OC3770*/
                $this->setCriterioAdjudicacao($oDadosProcesso->pc80_criterioadjudicacao);
                $this->setNumerodispensa($oDadosProcesso->pc80_numdispensa);
                $this->setDispensaPorValor($oDadosProcesso->pc80_dispvalor);
                $this->setOrcSigiloso($oDadosProcesso->pc80_orcsigiloso);
                $this->setSubContratacao($oDadosProcesso->pc80_subcontratacao);
                $this->setDadosComplementares($oDadosProcesso->pc80_dadoscomplementares);
                $this->setAmparoLegal($oDadosProcesso->pc80_amparolegal);
                $this->setCategoriaProcesso($oDadosProcesso->pc80_categoriaprocesso);
                $this->setModalidadeContratacao($oDadosProcesso->pc80_modalidadecontratacao);
                $this->setCriterioJulgamento($oDadosProcesso->pc80_criteriojulgamento);
                /*FIM - OC3770*/
                unset($oDadosProcesso);
            }
        }
    }

    public function getNumerodispensa()
    {
        return $this->iNumeroDispensa;
    }
    /**
     * Retorna o valor da propriedade iNumeroDispensa
     * @return integer
     */
    public function setNumerodispensa($iNumeroDispensa)
    {
        $this->iNumeroDispensa = $iNumeroDispensa;
    }

    public function getModalidadeContratacao()
    {
        return $this->iModalidadeContratacao;
    }

    /**
     * Retorna o valor da propriedade iModalidadeContratacao
     * @return integer
     */
    public function setModalidadeContratacao($iModalidadeContratacao)
    {
        $this->iModalidadeContratacao = $iModalidadeContratacao;
    }

    public function getAmparoLegal()
    {
        return $this->iAmparolegal;
    }

    /**
     * Retorna o valor da propriedade iAmparolegal
     * @return integer
     */
    public function setAmparoLegal($iAmparolegal)
    {
        $this->iAmparolegal = $iAmparolegal;
    }

    public function getCriterioJulgamento()
    {
        return $this->iCriteriojulgamento;
    }

    /**
     * Retorna o valor da propriedade iCriteriojulgamento
     * @return integer
     */
    public function setCriterioJulgamento($iCriteriojulgamento)
    {
        $this->iCriteriojulgamento = $iCriteriojulgamento;
    }

    public function getCategoriaProcesso()
    {
        return $this->iCategoriaprocesso;
    }

    /**
     * Retorna o valor da propriedade iCategoriaprocesso
     * @return integer
     */
    public function setCategoriaProcesso($iCategoriaprocesso)
    {
        $this->iCategoriaprocesso = $iCategoriaprocesso;
    }

    public function setDispensaPorValor($sDispensaPorValor)
    {
        $this->sDispensaPorValor = $sDispensaPorValor;
    }
    /**
     * Retorna o valor da propriedade sDispensaPorValor
     * @return integer
     */
    public function getDispensaPorValor()
    {
        return $this->sDispensaPorValor;
    }

    public function setOrcSigiloso($sOrcamentoSigiloso)
    {
        $this->sOrcamentoSigiloso = $sOrcamentoSigiloso;
    }
    /**
     * Retorna o valor da propriedade sOrcamentoSigiloso
     * @return integer
     */
    public function getOrcSigiloso()
    {
        return $this->sOrcamentoSigiloso;
    }

    public function setSubContratacao($sSubContratacao)
    {
        $this->sSubContratacao = $sSubContratacao;
    }
    /**
     * Retorna o valor da propriedade sSubContratacao
     * @return integer
     */
    public function getSubContratacao()
    {
        return $this->sSubContratacao;
    }

    public function setDadosComplementares($sDadosComplementares)
    {
        $this->sDadosComplementares = $sDadosComplementares;
    }
    /**
     * Retorna o valor da propriedade sDadosComplementares
     * @return integer
     */
    public function getDadosComplementares()
    {
        return $this->sDadosComplementares;
    }

    /**
     * Seta valor na propriedade iCodigo
     * @param integer $iCodigo
     */
    public function setCodigo($iCodigo)
    {
        $this->iCodigo = $iCodigo;
    }
    /**
     * Retorna o valor da propriedade iCodigo
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * Retorna a data da emissao do Processo de Compras
     * Retorno data no formato DD/MM/YYYY
     * @return string
     */
    public function getDataEmissao()
    {
        return $this->dtDataEmissao;
    }

    /**
     * Define a data da emissao do processo de compras
     * @param string $dtDataEmissao data no formado dd/mm/YYYY
     */
    public function setDataEmissao($dtDataEmissao)
    {
        $this->dtDataEmissao = $dtDataEmissao;
    }

    /**
     * Retorna o codigo do departamento que pertence o process de compras
     * @return integer
     */
    public function getCodigoDepartamento()
    {
        return $this->iCodigoDepartamento;
    }

    /**
     * Define o departamento que pertence o processo de compras
     * @param integer $iCodigoDepartamento c�digfo do departamento (db_Depart.coddeto)
     */
    public function setCodigoDepartamento($iCodigoDepartamento)
    {
        $this->iCodigoDepartamento = $iCodigoDepartamento;
    }

    /**
     * Retorna a Situa��o do processo de compras.
     * Os valores que o m�todo retorna s�o 1 - Em Analise 2 - Autorizado 3 - N�o Autorizado
     * @return integer
     */
    public function getSituacao()
    {
        return $this->iSituacao;
    }

    /**
     * Define a situa��o do Processo de compras
     * Os valores validos para $iSituacao s�o:
     * 1 - Em Analise 2 - Autorizado 3 - N�o Autorizado
     * Aonde: 1 - O processo de compras nao pode ser utilizado em Licita��es, ou orcamentos
     *        2 - O processo estp� liberado para gerar licita��o e gerar Or�amentos.
     *        3 - O processo n�o foi autorizado, e n�o poder� ser mais utilizado.
     * @param integer $iSituacao
     */
    public function setSituacao($iSituacao)
    {
        $this->iSituacao = $iSituacao;
    }

    /**
     * Retorna o usu�rio respons�vel pelo processo de compras
     * @return integer
     */
    public function getUsuario()
    {
        return $this->iUsuario;
    }

    /**
     * Define o usu�rio respons�vel pelo processo de compras
     * @param integer $iUsuario C�digo do usu�rio db_usuarios.id_usuario
     */
    public function setUsuario($iUsuario)
    {
        $this->iUsuario = $iUsuario;
    }

    /**
     * Retorna o nome do usuario respons�vel pelo processo de compras
     * @return string
     */
    public function getNomeUsuario()
    {
        return $this->sNomeUsuario;
    }

    /**
     * Define o nome do usuario
     * @param string $sNomeUsuario nome do usuario
     */
    protected function setNomeUsuario($sNomeUsuario)
    {
        $this->sNomeUsuario = $sNomeUsuario;
    }
    /**
     * Retorna a descri��o do departamento do processo de compras
     * @return string
     */
    public function getDescricaoDepartamento()
    {
        return $this->sDescricaoDepartamento;
    }

    /**
     * define a descri��o do departamento do processo de compras
     * @param unknown_type $sDescricaoDepartamento
     */
    protected function setDescricaoDepartamento($sDescricaoDepartamento)
    {
        $this->sDescricaoDepartamento = $sDescricaoDepartamento;
    }

    /**
     * Retorna o resumo do processo de compras
     * @return string
     */
    public function getResumo()
    {
        return $this->sResumo;
    }

    /**
     * define o resumo do processo de comprsa
     * @param string $sResumo
     */
    public function setResumo($sResumo)
    {
        $this->sResumo = $sResumo;
    }

    /**
     * Adiciona um item no array que armazena
     * @param stdClass $oItem
     */
    public function adicionarItem($oItem)
    {
        $this->aItens[] = $oItem;
    }

    /**
     * Retorna os itens do processo de compras
     * @return ItemProcessoCompra[]
     * @acess public
     */
    public function getItens()
    {

        if (count($this->aItens) == 0 && !empty($this->iCodigo)) {

            $oDaoProcessoCompraItem      = new cl_pcprocitem();
            $sWhere                      = "pc81_codproc = {$this->getCodigo()}";
            $sSqlDadosProcessoCompraItem = $oDaoProcessoCompraItem->sql_query_file(null, "*", "pc81_codprocitem", $sWhere);
            $rsDadosProcessoCompraItem   = $oDaoProcessoCompraItem->sql_record($sSqlDadosProcessoCompraItem);
            if ($oDaoProcessoCompraItem->numrows > 0) {

                for ($iProcessoCompraItem = 0; $iProcessoCompraItem < $oDaoProcessoCompraItem->numrows; $iProcessoCompraItem++) {

                    $iCodigoItem          = db_utils::fieldsMemory($rsDadosProcessoCompraItem, $iProcessoCompraItem)->pc81_codprocitem;
                    $oItemProcessoCompras = ItemProcessoCompraRepository::getItemByCodigo($iCodigoItem);
                    $this->aItens[]       = $oItemProcessoCompras;
                }
            }
        }

        return $this->aItens;
    }

    static function getItensPorFornecedor($aProcessos, $iFornecedor, $lTipo)
    {

        $oDaoPCprocItem  = db_utils::getDao("pcprocitem");

        $sVirgula   = "";
        $sProcessos = "";

        foreach ($aProcessos as $oProcesso) {

            if ($oProcesso != null || $oProcesso != '') {

                $sProcessos .= $sVirgula . $oProcesso;
                $sVirgula = ', ';
            }
        }

        $sCampos          = "pc81_codprocitem as codigo, pc01_codmater as codigomaterial,";
        $sCampos         .= "pc01_descrmater as material, pc23_vlrun as valorunitario,";
        $sCampos         .= "pc01_servico as servico, 1 as origem, pc18_codele as elemento,";
        $sCampos         .= "pc23_quant as quantidade, pc23_valor as valortotal,pc81_codproc as numero";
        $sSqlProcessos   = $oDaoPCprocItem->sql_query_soljulg(
            null,
            $sCampos,
            "pc81_codproc, pc11_seq",
            "pc21_numcgm= {$iFornecedor}
                                                    and ac23_sequencial is null
                                                   and pc81_codproc in({$sProcessos})"
        );

        $rsProcessos    = $oDaoPCprocItem->sql_record($sSqlProcessos);
        return db_utils::getCollectionByRecord($rsProcessos, false, false, true);
    }

    /**
     * retorna todas os processos de compras que possuem um item ganho pelo credor.
     *
     * @param integer $iFornecedor codigo do fornecedor
     * @return array
     */
    static function getProcessosByFornecedor($iFornecedor, $lValidaAutorizadas = false)
    {

        $oDaoPCprocItem = db_utils::getDao("pcprocitem");
        $sWhere = '';
        $datausu = date("Y-m-d", db_getsession('DB_datausu'));
        if ($lValidaAutorizadas) {

            $sWhere .= " and not exists (";
            $sWhere .= "                 select 1 ";
            $sWhere .= "                   from empautoriza  ";
            $sWhere .= "                        inner join empautitem           on e55_autori                      = e54_autori";
            $sWhere .= "                        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
            $sWhere .= "                                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori";
            $sWhere .= "                        inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
            $sWhere .= "                  where pc81_codproc = pc80_codproc";
            $sWhere .= "                    and e54_anulad is null";
            $sWhere .= " )";
            $sWhere .= "and pc80_data <= '$datausu'";
        }
        $sCampos        = "distinct pc81_codproc as licitacao, pc10_resumo as objeto, '' as numero, pc21_numcgm as cgm";
        $sCampos       .= ", pc11_numero as numero_exercicio, pc80_data as data";
        $sSqlProcessos  = $oDaoPCprocItem->sql_query_soljulg(
            null,
            $sCampos,
            "1",
            "pc21_numcgm= {$iFornecedor} and ac23_sequencial is null  {$sWhere}"
        );

        $rsProcessos    = $oDaoPCprocItem->sql_record($sSqlProcessos);
        return db_utils::getCollectionByRecord($rsProcessos, false, false, true);
    }

    /**
     * Salva um processo de compras para uma solicita��o
     * @throws Exception
     */
    public function salvar()
    {

        $oDaoPcProc                    = new cl_pcproc();
        $oDaoPcProc->pc80_codproc      = null;
        $oDaoPcProc->pc80_data         = implode("-", array_reverse(explode("/", $this->getDataEmissao())));
        $oDaoPcProc->pc80_depto        = $this->getCodigoDepartamento();
        $oDaoPcProc->pc80_usuario      = $this->getUsuario();
        $oDaoPcProc->pc80_resumo       = $this->sResumo;
        $oDaoPcProc->pc80_situacao     = $this->getSituacao();
        $oDaoPcProc->pc80_tipoprocesso = $this->getTipoProcesso();
        $oDaoPcProc->pc80_criterioadjudicacao = $this->getCriterioAdjudicacao();
        $oDaoPcProc->pc80_numdispensa = $this->getNumerodispensa();
        $oDaoPcProc->pc80_dispvalor = $this->getDispensaPorValor();
        $oDaoPcProc->pc80_orcsigiloso = $this->getOrcSigiloso();
        $oDaoPcProc->pc80_subcontratacao = $this->getSubContratacao();
        $oDaoPcProc->pc80_dadoscomplementares = $this->getDadosComplementares();
        $oDaoPcProc->pc80_amparolegal = $this->getAmparoLegal();
        $oDaoPcProc->pc80_categoriaprocesso = $this->getCategoriaProcesso();
        $oDaoPcProc->pc80_modalidadecontratacao = $this->getModalidadeContratacao();
        $oDaoPcProc->pc80_criteriojulgamento = $this->getCriterioJulgamento();

        if (empty($this->iCodigo)) {

            $oDaoPcProc->incluir(null);
            $this->iCodigo = $oDaoPcProc->pc80_codproc;
        } else {
            $oDaoPcProc->pc80_codproc = $this->getCodigo();
            $oDaoPcProc->alterar($oDaoPcProc->pc80_codproc);
        }

        if ($oDaoPcProc->erro_status == 0) {

            $sMsgErro  = "N�o foi poss�vel salvar o processo de compras.\n\n";
            $sMsgErro .= str_replace("\n", "\\n", $oDaoPcProc->erro_msg);
            throw new Exception($sMsgErro);
        }

        $aItemProcessoCompra = $this->getItens();
        foreach ($aItemProcessoCompra as $oItemProcessoCompra) {

            $oDaoPcProcItem                   = new cl_pcprocitem();
            $oDaoPcProcItem->pc81_codproc     = $this->getCodigo();

            if ($oItemProcessoCompra instanceof ItemProcessoCompra) {

                $oDaoPcProcItem->pc81_solicitem   = $oItemProcessoCompra->getItemSolicitacao()->getCodigoItemSolicitacao();
                $oDaoPcProcItem->pc81_codprocitem = $oItemProcessoCompra->getCodigo();
            } else {
                $oDaoPcProcItem->pc81_codprocitem = '';
                $oDaoPcProcItem->pc81_solicitem = $oItemProcessoCompra->pc81_solicitem;
            }

            if (empty($oDaoPcProcItem->pc81_codprocitem)) {

                $oDaoPcProcItem->incluir(null);
                if ($oItemProcessoCompra instanceof ItemProcessoCompra) {
                    $oItemProcessoCompra->setCodigo($oDaoPcProcItem->pc81_codprocitem);
                } else {
                    $oItemProcessoCompra->pc81_codprocitem = $oDaoPcProcItem->pc81_codprocitem;
                }
            } else {
                $oDaoPcProcItem->alterar($oItemProcessoCompra->getCodigo());
            }

            if ($oDaoPcProcItem->erro_status == 0) {
                throw new Exception($oDaoPcProcItem->erro_msg);
            }
        }

        /**
         * Salva os lotes no processo compra informado
         */
        if ($this->getTipoProcesso() == self::TIPO_LOTE) {

            foreach ($this->getLotes() as $oLote) {
                $oLote->salvar();
            }
        }

        return true;
    }

    public function getItensParaAutorizacao()
    {

        $oDaoPcOrcamJulg      = db_utils::getDao("pcorcamjulg");
        $oDaoOrcReservaSol    = db_utils::getDao("orcreservasol");
        $this->oDaoParametros = db_utils::getDao("empparametro");
        $oDaoPcProc           = db_utils::getDao("pcproc");

        $sCampos  = "pc11_seq,";
        $sCampos .= "pc01_codmater as codigomaterial,";
        $sCampos .= "pc01_descrmater as descricaomaterial,";
        $sCampos .= "pc01_servico as servico,";
        $sCampos .= "pc11_quant as quanttotalitem,";
        $sCampos .= "pc11_vlrun as valorunitario,";
        $sCampos .= "pc11_numero,";
        $sCampos .= "pc11_codigo as codigoitemsolicitacao,";
        $sCampos .= "pc13_coddot as codigodotacao,";
        $sCampos .= "pc13_sequencial as codigodotacaoitem,";
        $sCampos .= "pc13_quant as quanttotaldotacao,";
        $sCampos .= "pc13_anousu as anodotacao,";
        $sCampos .= "pc13_valor as valordotacao,";
        $sCampos .= "pc17_unid,";
        $sCampos .= "pc17_quant,";
        $sCampos .= "pc23_orcamforne,";
        $sCampos .= "pc23_valor as valorfornecedor,";
        $sCampos .= "case when trim(pc11_resum) <> '' then pc11_resum";
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
        $sCampos .= "pc11_servicoquantidade as servicoquantidade,";
        $sCampos .= "pc28_solicitem itemanulado";

        $sOrder = "z01_numcgm,pc13_coddot,pc18_codele, pc19_sequencial, pc19_orctiporec,pc13_sequencial";
        $sWhere = "pc80_codproc = {$this->getCodigo()} and pcorcamjulg.pc24_pontuacao = 1 and pc10_instit = " . db_getsession("DB_instit");

        $sSqlProcCompras    = $oDaoPcProc->sql_query_gerautproc(null, $sCampos, $sOrder, $sWhere);
        $rsProcessoCompra   = $oDaoPcProc->sql_record($sSqlProcCompras);
        $iRowProcessoCompra = $oDaoPcProc->numrows;
        $aItens          = array();
        if ($iRowProcessoCompra > 0) {

            for ($i = 0; $i < $iRowProcessoCompra; $i++) {

                $oDados = db_utils::fieldsMemory($rsProcessoCompra, $i, false, false, true);

                if ($oDados->codigofornecedor == "") {
                    throw new Exception("N�o existe or�amento julgado para este processo de compras.");
                }

                /*
                 * calcula o percentual da dota��o em relacao ao valor total
                 */

                $nPercentualDotacao = 100;
                if ($oDados->valorunitario > 0) {
                    $nPercentualDotacao = ($oDados->valordotacao * 100) / ($oDados->quanttotalitem * $oDados->valorunitario);
                    $oDados->percentual = $nPercentualDotacao;
                }
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

                $oDados->quantidadeautorizada = 0;
                $oDados->valorautorizado      = 0;
                $oDados->saldoautorizar       = $oDados->valordotacao;
                if (!empty($oDados->codigoitemprocesso)) {

                    $oValoresAutorizados          = $this->getValoresParciais(
                        $oDados->codigoitemprocesso,
                        $oDados->codigodotacao,
                        $oDados->contrapartida
                    );

                    $oDados->quantidadeautorizada = $oValoresAutorizados->iQuantidadeAutorizacao;
                    $oDados->valorautorizado      = $oValoresAutorizados->nValorAutorizacao;
                    $oDados->saldoautorizar       = $oValoresAutorizados->nValorSaldoTotal;
                }
                $oDotacao                     = new Dotacao($oDados->codigodotacao, $oDados->anodotacao);
                $oDados->saldofinaldotacao    = $oDotacao->getSaldoAtualMenosReservado();
                $oDados->servico              = $oDados->servico == 't' ? true : false;

                /**
                 * Verifica se a dota��o tem saldo para poder autorizar o item
                 */
                $nSaldoAtualReserva = $oDotacao->getSaldoAtualMenosReservado() + $oDados->valorreserva;
                if ($nSaldoAtualReserva <= 0 && $oDados->valorreserva == 0) {
                    $oDados->dotacaocomsaldo = false;
                }

                if (($nSaldoAtualReserva) < ($oDados->quanttotaldotacao * $oDados->valorunitario) && $oDados->servico == false) {
                    $oDados->dotacaocomsaldo = false;
                    if ($oDados->valorreserva > $oDados->valorunitario) {
                        $oDados->dotacaocomsaldo = true;
                    }
                }

                /**
                 * Verificamos as quantidades executadas do item
                 */
                $oDados->saldoquantidade      = $oDados->quanttotaldotacao - $oDados->quantidadeautorizada;
                $oDados->saldovalor           = $oDados->valordiferenca    - $oDados->valorautorizado;
                if ($oDados->servico && $oDados->servicoquantidade != "t") {
                    $oDados->saldoquantidade = 1;
                }
                $oDados->autorizacaogeradas = array();
                if (!empty($oDados->codigoitemprocesso)) {
                    $oDados->autorizacaogeradas  = licitacao::getAutorizacoes($oDados->codigoitemprocesso, $oDados->codigodotacao);
                }
                /**
                 * busca o parametro de casas decimais para formatar o valor jogado na grid
                 */
                $iAnoSessao             = db_getsession("DB_anousu");
                $sWherePeriodoParametro = " e39_anousu = {$iAnoSessao} ";
                $sSqlPeriodoParametro   = $this->oDaoParametros->sql_query_file(null, "e30_numdec", null, $sWherePeriodoParametro);
                $rsPeriodoParametro     = $this->oDaoParametros->sql_record($sSqlPeriodoParametro);

                $iNumDec = 2;
                if ($this->oDaoParametros->numrows > 0) {

                    $iNumDec =  (int)db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec;
                }
                $oDados->valorunitariofornecedor = number_format(
                    (float)$oDados->valorunitariofornecedor,
                    $iNumDec,
                    '.',
                    ''
                );
                /**
                 * buscamos o saldo da dota��o do item na data que est� sendo feita a atoriza��o
                 */
                $dataini = "2016-" . date("m", db_getsession("DB_datausu")) . "-01";
                $datafim = date("Y-m-d", db_getsession("DB_datausu"));
                $result = db_dotacaosaldo(8, 2, 2, true, ' o58_coddot = ' . $oDados->codigodotacao . ' and o58_anousu = ' . db_getsession("DB_anousu"), db_getsession("DB_anousu"), $dataini, $datafim);
                if (pg_numrows($result) > 0) {
                    $oSaldoDotacao = db_utils::fieldsMemory($result, 0);
                    $oDados->saldodotacao = ($oSaldoDotacao->dot_ini + $oSaldoDotacao->suplementado_acumulado - $oSaldoDotacao->reduzido_acumulado) - $oSaldoDotacao->empenhado_acumulado + $oSaldoDotacao->anulado_acumulado - $oSaldoDotacao->reservado;
                }

                $aItens[] = $oDados;
            }
        }

        return $aItens;
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
        $sWhere               = "pc81_codprocitem = {$iCodigoItemProcesso} and pc24_pontuacao = 1";
        $sWhere              .= " and pc13_coddot  = {$iCodigoDotacao} ";
        $sWhereContrapartida  = " and pc19_orctiporec is null ";
        if ($iOrcTipoRec > 0) {
            $sWhereContrapartida = "  and pc19_orctiporec = {$iOrcTipoRec} ";
        }
        $sWhere .= $sWhereContrapartida;
        $sSqlPcOrcam       = $oDaoPcOrcam->sql_query_valor_item_julgado_processocompra(null, $sCampos, null, $sWhere);
        $rsSqlPcOrcam      = $oDaoPcOrcam->sql_record($sSqlPcOrcam);
        if ($oDaoPcOrcam->numrows > 0) {

            for ($iIndPcOrcam = 0; $iIndPcOrcam < $oDaoPcOrcam->numrows; $iIndPcOrcam++) {

                $oItemJulgado                               = db_utils::fieldsMemory($rsSqlPcOrcam, $iIndPcOrcam);
                $nPercentualDotacao = 100;
                if ($oItemJulgado->pc11_vlrun > 0) {
                    $nPercentualDotacao = ($oItemJulgado->pc13_valor * 100) /
                        ($oItemJulgado->pc11_quant * $oItemJulgado->pc11_vlrun);
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
        $oDadoValorParcial->nValorSaldoTotal = ($oDadoValorParcial->nValorItemJulgado
            - $oDadoValorParcial->nValorAutorizacao);
        return $oDadoValorParcial;
    }



    /**
     * Gera a autoriza��o de empenho para uma solicita��o de compras
     * @param array $aDadosAutorizacao
     */
    public function gerarAutorizacoes($aDadosAutorizacao)
    {

        $aAutorizacoes     = array();
        $oDaoOrcReservaSol = db_utils::getDao("orcreservasol");
        $oDaoOrcReserva    = db_utils::getDao("orcreserva");
        $oDaoPcdotac       = db_utils::getDao("pcdotac");
        $oDaoSolicitem     = db_utils::getDao("solicitem");

        /**
         * Criamos um orcamento para os itens que nao possuem orcamento lan�ado
         *
         */

        foreach ($aDadosAutorizacao as $oDados) {

            $nValorTotal = 0;
            foreach ($oDados->itens as $oItem) {

                $nValorTotal += $oItem->valortotal;
                /**
                 * verificamos se exite reserva de saldo para a solicitacao;
                 * caso exista, devemos calcular a diferen�a entre o que deve ser gerado para a autorizacao e a solictacao
                 */

                $aReservas = itemSolicitacao::getReservasSaldoDotacao($oItem->pcdotac);
                if (count($aReservas)  > 0) {

                    $nNovoValorReserva   = $aReservas[0]->valor - $oItem->valortotal;
                    if ($nNovoValorReserva < 0) {
                        $nNovoValorReserva = 0;
                    }

                    /**
                     * excluirmos a reserva e incluimos uma nova
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

                    /**
                     * Inclu�mos os dados na OrcReserva, caso o item ainda tenha valor dispo
                     */
                    $oSaldo = $this->getValoresParciais(
                        $oItem->codigoprocesso,
                        $oDados->dotacao,
                        $oDados->contrapartida
                    );

                    // print_r($oSaldo);
                    if ($nNovoValorReserva > 0 && ($oSaldo->nValorAutorizacao + $oItem->valortotal < $oSaldo->nValorItemJulgado)) {

                        $oDaoOrcReserva->o80_anousu = db_getsession("DB_anousu");
                        $oDaoOrcReserva->o80_coddot = $oDados->dotacao;
                        $oDaoOrcReserva->o80_dtfim  = db_getsession("DB_anousu") . "-12-31";
                        $oDaoOrcReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
                        $oDaoOrcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
                        $oDaoOrcReserva->o80_valor  = $nNovoValorReserva;
                        $oDaoOrcReserva->o80_descr  = "Reserva item Solicitacao";
                        $oDaoOrcReserva->incluir(null);

                        if ($oDaoOrcReserva->erro_status == 0) {

                            $sMsgErro  = "N�o foi possivel gerar reserva para a dota��o: {$oDados->dotacao}.\n";
                            $sMsgErro .= $oDaoOrcReserva->erro_msg;
                            throw new Exception($sMsgErro);
                        }

                        $oDaoOrcReservaSol->o82_codres    = $oDaoOrcReserva->o80_codres;
                        $oDaoOrcReservaSol->o82_pcdotac   = $oDados->pcdotac;
                        $oDaoOrcReservaSol->o82_solicitem = $oItem->solicitem;
                        $oDaoOrcReservaSol->incluir(null);
                        if ($oDaoOrcReservaSol->erro_status == 0) {

                            $sMsgErro  = "N�o foi possivel gerar reserva para a dota��o: {$oDados->dotacao}.\n";
                            $sMsgErro .= $oDaoOrcReservaSol->erro_msg;
                            throw new Exception($sMsgErro);
                        }
                    }
                }
            }
            /**
             * verificamos se a dota��o tem saldo na data que est� sendo feita a atoriza��o
             */
            $dataini = "2016-" . date("m", db_getsession("DB_datausu")) . "-01";
            $datafim = date("Y-m-d", db_getsession("DB_datausu"));
            $result = db_dotacaosaldo(8, 2, 2, true, ' o58_coddot = ' . $oDados->dotacao . ' and o58_anousu = ' . db_getsession("DB_anousu"), db_getsession("DB_anousu"), $dataini, $datafim);
            if (pg_numrows($result) > 0) {
                $oSaldoDotacao = db_utils::fieldsMemory($result, 0);
                $nSaldoAcumuladoDisponivel = ($oSaldoDotacao->dot_ini + $oSaldoDotacao->suplementado_acumulado - $oSaldoDotacao->reduzido_acumulado) - $oSaldoDotacao->empenhado_acumulado + $oSaldoDotacao->anulado_acumulado - $oSaldoDotacao->reservado;
                if (db_formatar($nValorTotal, 'f') > db_formatar($nSaldoAcumuladoDisponivel, 'f')) {
                    $nSaldoAcumuladoDisponivel = trim(db_formatar($nSaldoAcumuladoDisponivel, 'f'));
                    $nValorTotal = trim(db_formatar($nValorTotal, 'f'));
                    $sMsgErro = "Valor a autorizar($nValorTotal) maior que saldo($nSaldoAcumuladoDisponivel) da Dota��o $oDados->dotacao na data atual(" . date("d/m/Y", db_getsession("DB_datausu")) . ")";
                    throw new Exception($sMsgErro);
                }
            }

            /**
             * Salvamos a Autorizacao;
             * Resumo da autoriza��o
             */
            $rsPcdotac = $oDaoPcdotac->sql_record($oDaoPcdotac->sql_query_solicita(
                null,
                null,
                null,
                "pc10_resumo",
                null,
                "pc13_sequencial = {$oItem->pcdotac}"
            ));

            $sResumo   = $oDaoPcdotac->numrows > 0 ? db_utils::fieldsMemory($rsPcdotac, 0)->pc10_resumo : $oDados->resumo;

            $oAutorizacao = new AutorizacaoEmpenho();
            $oFornecedor  = CgmFactory::getInstanceByCgm($oDados->cgm);

            $oAutorizacao->setValor($nValorTotal);
            $oAutorizacao->setFornecedor($oFornecedor);
            $oAutorizacao->setDotacao($oDados->dotacao);
            $oAutorizacao->setDesdobramento($oDados->elemento);
            $oAutorizacao->setTipoEmpenho($oDados->tipoempenho);
            $oAutorizacao->setContraPartida($oDados->contrapartida);
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
            $oAutorizacao->setDataAutorizacao(date('Y-m-d',db_getsession('DB_datausu')));
            $oAutorizacao->setNumeroLicitacao($oDados->iNumeroLicitacao);
            $oAutorizacao->setModalidade($oDados->iModalidade);
            $oAutorizacao->setOutrasCondicoes($oDados->sOutrasCondicoes);
            $oAutorizacao->setCondicaoPagamento($oDados->condicaopagamento);
            $oAutorizacao->setSTipoautorizacao($this->getTipoAutorizacao($oDados->tipocompra));
            $oAutorizacao->setSTipoorigem($this->getTipoOrigemAutorizacao($oDados->tipocompra));
            $oAutorizacao->setSAdesaoregpreco($oDados->oAdesaoRegPrecoCod);
            $oAutorizacao->setCodigoLicitacao($oDados->oLicitacaoCodigo);
            $oAutorizacao->setSLicoutrosorgaos($oDados->oLicoutrosorgaosCod);

            $oAutorizacao->salvar();

            $sProcessoAdministrativo = null;

            if (isset($oDados->e150_numeroprocesso) && !empty($oDados->e150_numeroprocesso)) {
                $sProcessoAdministrativo = db_stdClass::normalizeStringJsonEscapeString($oDados->e150_numeroprocesso);
            }

            /**
             * Buscar o c�digo do processo da tabela solicitaprotprocesso e incluir na empautorizaprotprocesso caso tenha
             */
            $oDaoSolicitem             = db_utils::getDao("solicitem");
            $sCodigosItens             = implode(",", $aItemSolcitem);
            $sSqlBuscaSolicitem        = $oDaoSolicitem->sql_query_solicitaprotprocesso(
                null,
                "solicitaprotprocesso.*",
                null,
                "pc11_codigo in ({$sCodigosItens})"
            );
            $rsBuscaSolicitem          = $oDaoSolicitem->sql_record($sSqlBuscaSolicitem);
            $oDadoSolicitaProtProcesso = db_utils::fieldsMemory($rsBuscaSolicitem, 0);

            if (empty($sProcessoAdministrativo) &&  !empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso)) {
                $sProcessoAdministrativo = $oDadoSolicitaProtProcesso->pc90_numeroprocesso;
            }

            if (!empty($sProcessoAdministrativo)) {

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
     * Busca as solicita��es que tem dota��o do ano anterior.
     * @return mixed
     */
    public function getSolicitacoesDotacaoAnoAnterior()
    {

        $oDaoPcProcItem   = db_utils::getDao("pcprocitem");
        $sWhereDotacao    = "pc81_codproc = {$this->getCodigo()} and pc13_anousu < " . db_getsession("DB_anousu");
        $sCamposDotacao   = "distinct pc11_numero as solicita";
        $sSqlBuscaDotacao = $oDaoPcProcItem->sql_query_dotac(null, $sCamposDotacao, null, $sWhereDotacao);
        $rsBuscaDotacao   = $oDaoPcProcItem->sql_record($sSqlBuscaDotacao);
        $iRowDotacao      = $oDaoPcProcItem->numrows;
        $aSolicitacao     = array();

        if ($iRowDotacao > 0) {

            for ($iRow = 0; $iRow < $iRowDotacao; $iRow++) {

                $iSolicita      = db_utils::fieldsMemory($rsBuscaDotacao, $iRow)->solicita;
                $aSolicitacao[] = $iSolicita;
            }
        }
        return $aSolicitacao;
    }

    /**
     * Adiciona lote no processo de compras
     *
     * @param String $sNomeLote
     * @return LoteProcessoCompra
     * @throws BusinessException
     * @access public
     */
    public function adicionarLote($sNomeLote)
    {

        if (empty($sNomeLote)) {
            throw new BusinessException(_M(ProcessoCompras::ARQUIVO_MENSAGEM . "descricao_nao_informado"));
        }

        foreach ($this->getLotes() as $oLotes) {

            if ($oLotes->getNome() == $sNomeLote) {

                $oVariaveis            = new stdClass();
                $oVariaveis->nome_lote = $sNomeLote;
                throw new BusinessException(_M(ProcessoCompras::ARQUIVO_MENSAGEM . "lote_cadastrado", $oVariaveis));
            }
        }

        $oLoteProcessoCompra = new LoteProcessoCompra();
        $oLoteProcessoCompra->setNome($sNomeLote);
        $oLoteProcessoCompra->setProcessoCompra($this);
        $this->aLotes[] = $oLoteProcessoCompra;
        return $oLoteProcessoCompra;
    }

    /**
     * Retorna os lotes do processo de compras
     *
     * @return LoteProcessoCompra[]
     * @acess public
     */
    public function getLotes()
    {

        if (count($this->aLotes) == 0) {

            $oDaoProcessCompraLote = new cl_processocompralote();
            $sWhere                = "pc68_pcproc = {$this->getCodigo()}";
            $sSqlDadosLotes        = $oDaoProcessCompraLote->sql_query_file(null, "*", "pc68_nome", $sWhere);
            $rsDadosLote           = $oDaoProcessCompraLote->sql_record($sSqlDadosLotes);
            if ($oDaoProcessCompraLote->numrows > 0) {

                for ($iLote = 0; $iLote < $oDaoProcessCompraLote->numrows; $iLote++) {
                    $this->aLotes[] = new LoteProcessoCompra(db_utils::fieldsMemory($rsDadosLote, $iLote)->pc68_sequencial);
                }
            }
        }

        return $this->aLotes;
    }

    /**
     * Retorna um lote atrav�s do c�digo
     *
     * @access public
     * @param Integer $iCodigoLote
     * @return LoteProcessaCompra|boolean
     */
    public function getLotePorCodigo($iCodigoLote)
    {

        foreach ($this->getLotes() as $oLote) {

            if ($oLote->getCodigo() == $iCodigoLote) {
                return $oLote;
            }
        }

        return false;
    }

    /**
     * Retorna um item atrav�s do c�digo
     *
     * @access public
     * @param Integer $iCodigoItem
     * @return boolean | ItemProcessoCompra
     */
    public function getItemPorCodigo($iCodigoItem)
    {

        foreach ($this->getItens() as $oItemProcessoCompra) {

            if ($oItemProcessoCompra->getCodigo() == $iCodigoItem) {
                return $oItemProcessoCompra;
            }
        }

        return false;
    }

    /**
     * Retorna o tipo de processo de compra
     * @return integer
     */
    public function getTipoProcesso()
    {
        return $this->iTipoProcesso;
    }

    /**
     * Retorna o Criterio de Adjudicacao
     * @return integer
     */
    public function getCriterioAdjudicacao()
    {
        return $this->iCriterioAdjudicacao;
    }

    /**
     * Retorna todos os orcamentos do processo de compras
     * @return OrcamentoCompra[]
     */
    public function getOrcamentos()
    {

        $aOrcamentos     = array();
        $oDaoPcOrcamItem = new cl_pcorcamitemproc();
        $sWhere          = "pc81_codproc = {$this->getCodigo()}";
        $sSqlOrcamento   = $oDaoPcOrcamItem->sql_query(null, null, "distinct pc20_codorc", null, $sWhere);
        $rsOrcamentos    = $oDaoPcOrcamItem->sql_record($sSqlOrcamento);
        if (!$rsOrcamentos) {
            return $aOrcamentos;
        }
        for ($iOrcamento = 0; $iOrcamento < $oDaoPcOrcamItem->numrows; $iOrcamento++) {

            $oOrcamento = new OrcamentoCompra(db_utils::fieldsMemory($rsOrcamentos, $iOrcamento)->pc20_codorc);
            $aOrcamentos[] = $oOrcamento;
        }
        return $aOrcamentos;
    }

    /**
     * Seta o tipo de processo de compra
     * @param integer $iTipoProcesso
     */
    public function setTipoProcesso($iTipoProcesso)
    {
        $this->iTipoProcesso = $iTipoProcesso;
    }

    /** OC3770
     * Seta o Criterio de Adjudicacao
     * @param integer $iTipoProcesso
     */
    public function setCriterioAdjudicacao($iCriterioAdjudicacao)
    {
        $this->iCriterioAdjudicacao = $iCriterioAdjudicacao;
    }
    /*FIM - OC3770*/

    public function remover()
    {

        if (!db_utils::inTransaction()) {
            throw new DBException(_M(self::ARQUIVO_MENSAGEM . "sem_transacao_ativa"));
        }


        foreach ($this->getLotes() as $oLote) {
            $oLote->remover();
        }

        foreach ($this->getOrcamentos() as $oOrcamento) {
            $oOrcamento->remover();
        }

        $oDaoAdesao       = db_utils::getDao("adesaoregprecos");
        $sSqlAdesao       = $oDaoAdesao->sql_query_file('', '*', '', "si06_processocompra = {$this->getCodigo()}");
        $oDaoAdesao->sql_record($sSqlAdesao);

        if ($oDaoAdesao->numrows > 0) {
            throw new DBException("Processo de compras em Ades�o de registro de pre�o");
        }

        /**
         * Deletamos o todos os vinculos do Processo de compras com as autorizacoes Geradas
         */
        $oDaoEmpautitemPcProc = new cl_empautitempcprocitem();
        $sWhereEmpautitem  = "e73_pcprocitem in (";
        $sWhereEmpautitem .= "                   select distinct pc81_codprocitem ";
        $sWhereEmpautitem .= "                     from pcprocitem ";
        $sWhereEmpautitem .= "                    where pc81_codproc={$this->getCodigo()}";
        $sWhereEmpautitem .= "                   )";
        $oDaoEmpautitemPcProc->excluir(null, $sWhereEmpautitem);

        $oDaoPcProcItem    = new cl_pcprocitem();
        $oDaoPcProcItem->excluir(null, "pc81_codproc = {$this->getCodigo()}");

        if ($oDaoPcProcItem->erro_status == "0") {
            throw new DBException(_M(self::ARQUIVO_MENSAGEM . "erro_excluir_item"));
        }

        $oDaoPcProc = new cl_pcproc();
        $oDaoPcProc->excluir($this->getCodigo());

        if ($oDaoPcProcItem->erro_status == "0") {
            throw new DBException(_M(self::ARQUIVO_MENSAGEM . "erro_excluir_processo_compra"));
        }

    }

    public function getDadosAutorizacao()
    {

        $oDaoPcProc = new cl_pcproc();
        $sSql = $oDaoPcProc->queryDadosAutorizacao($this->getCodigo());
        $rsDados         = $oDaoPcProc->sql_record($sSql);
        $oDados = db_utils::fieldsMemory($rsDados, 0);
        $aDados[] = $oDados;

        return $aDados;

    }

    public function getDadosAdesaoRegpreco($sequencial)
    {

        $oDadosAdesao = new cl_adesaoregprecos();

        $sql = "select si06_numlicitacao,si06_modalidade,si06_numeroprc,si06_anoproc from adesaoregprecos where si06_sequencial = {$sequencial}";

        $rsDados = $oDadosAdesao->sql_record($sql);
        $oDados = db_utils::fieldsMemory($rsDados, 0);
        $aDados[] = $oDados;

        return $aDados;
    }

    public function getLicoutrosOrgaos($sequencial)
    {

        $oLicOutrosOrgaos       = db_utils::getDao("liclicitaoutrosorgaos");

        $rsDadoslic = $oLicOutrosOrgaos->sql_record($oLicOutrosOrgaos->sql_query_file($sequencial, "*", null, null));
        $oDadoslic = db_utils::fieldsMemory($rsDadoslic, 0);
        $oDadoslicarray[] = $oDadoslic;

        return $oDadoslicarray;
    }

    /**
    * @return int retorna o tipo da autoriza��o
    */
    public function getTipoAutorizacao($tipoCompra){

        $rsTipoCompraTribunal = db_query("select pc50_pctipocompratribunal from pctipocompra where pc50_codcom = " . $tipoCompra);
        $tipoCompraTribunal = db_utils::fieldsMemory($rsTipoCompraTribunal, 0)->pc50_pctipocompratribunal;
        
        $aCodTribunalLicOutrosOrgaos = ["105","106","107","108","109"];
        $aCodTribunalLicitacao = ["51","48","50","101","54","102","103","49","53","52","110","100"];
        $codTribunalAdesao = "104";
        $codTribunalDireta = "13";
                     
        if ($tipoCompraTribunal == $codTribunalDireta){
            return 1;
        } 
                
        if (in_array($tipoCompraTribunal, $aCodTribunalLicitacao)){
            return 2;
        } 
               
        if (in_array($tipoCompraTribunal, $aCodTribunalLicOutrosOrgaos)){
            return 3;
        } 
                
        if ($tipoCompraTribunal == $codTribunalAdesao){
            return 4;
        } 
        
    }

    /**
    * @return int retorna o tipo de origem
    */
    public function getTipoOrigemAutorizacao($tipoCompra){

        $rsTipoCompraTribunal = db_query("select pc50_pctipocompratribunal from pctipocompra where pc50_codcom = " . $tipoCompra);
        $tipoCompraTribunal = db_utils::fieldsMemory($rsTipoCompraTribunal, 0)->pc50_pctipocompratribunal;
        
        $aCodTribunalLicOutrosOrgaos = ["105","106","107","108","109"];
        $aCodTribunalLicitacao = ["51","48","50","54","49","53","52","110"];
        $aCodTribunalDispensaInexibilidade = ["103","102","101","100"];
        $codTribunalAdesao = "104";
        $codTribunalDireta = "13";
                     
        if ($tipoCompraTribunal == $codTribunalDireta){
            return 1;
        } 
                
        if (in_array($tipoCompraTribunal, $aCodTribunalLicitacao)){
            return 2;
        } 
               
        if (in_array($tipoCompraTribunal, $aCodTribunalDispensaInexibilidade)){
            return 3;
        } 
                
        if ($tipoCompraTribunal == $codTribunalAdesao){
            return 4;
        } 

        if (in_array($tipoCompraTribunal, $aCodTribunalLicOutrosOrgaos)){
            if($tipoCompraTribunal == "105") return 5;
            if($tipoCompraTribunal == "106") return 6;
            if($tipoCompraTribunal == "107") return 7;
            if($tipoCompraTribunal == "108") return 8;
            if($tipoCompraTribunal == "109") return 9;
        } 
        
    }
}
