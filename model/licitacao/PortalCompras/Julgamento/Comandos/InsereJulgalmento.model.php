<?php

require_once("model/licitacao/PortalCompras/Julgamento/Julgamento.model.php");
require_once("classes/db_liclicitaimportarjulgamento_classe.php");
require_once("model/licitacao/PortalCompras/Julgamento/Proposta.model.php");
require_once("model/licitacao/PortalCompras/Julgamento/Lance.model.php");
require_once("model/licitacao/PortalCompras/Julgamento/Ranking.model.php");
require_once("classes/db_pcorcam_classe.php");
require_once("classes/db_pcorcamitem_classe.php");
require_once("classes/db_pcorcamitemproc_classe.php");
require_once("classes/db_pcorcamforne_classe.php");
require_once("classes/db_pcorcamval_classe.php");
require_once("classes/db_pcorcamjulg_classe.php");
require_once("classes/db_habilitacaoforn_classe.php");
require_once("classes/db_liclicita_classe.php");

class InsereJulgamento
{
    private $climportjulgamento;

    /**
     * Metodo construtor
     */
    public function __construct()
    {
        $this->climportjulgamento = new cl_liclicitaimportarjulgamento;
    }

    /**
     * Inser Julgamento
     *
     * @param Julgamento $julgamento
     * @return array
     */
    public function execute(Julgamento $julgamento): array
    {
        $clpcorcam = new cl_pcorcam;

        $idJulgamento = $julgamento->getId();
        $numero = $julgamento->getNumero();
        $clpcorcam->pc20_obs = "ORCAMENTO IMPORTADO -"
            . $idJulgamento
            ." - ". $numero;
        $clpcorcam->pc20_dtate = $julgamento->getDataProposta();
        $clpcorcam->pc20_hrate = $julgamento->getHoraProposta();
        $clpcorcam->pc20_importado = 't';
        $lotes = $julgamento->getLotes();

        $participantes = $julgamento->getParticipantes();
        $dataAbertura = $julgamento->getDataAberturaProposta();

        try{
            db_inicio_transacao();

            $clpcorcam->incluir(null);

            $idPcorcam = (int)$clpcorcam->pc20_codorc;

            foreach($lotes as $lote) {
                $itens = $lote->getItems();

                /** @var Item[] $itens */
                foreach($itens as $item) {
                    $propostas = $item->getPropostas();
                    $tipoJulgamento = $item->getTipoJulgamento();
                    $ranking = $item->getRanking();
                    $lances = $item->getLances();

                    $idPcorcamitem    = $this->lidaPcorcamitem($idPcorcam);
                    $this->lidaPcorcamitemlic($item->getId(), $idPcorcamitem, $idJulgamento);

                    /** @var Proposta[] $propostas */
                    foreach($propostas as $proposta) {
                        $idFornecedor = $proposta->getIdFornecedor();
                        $numcgm = $this->buscaNumcgm($idFornecedor);

                        $idPcorcamforne   = $this->lidaPcorcamforne($numcgm, $idPcorcam);

                        $this->lidaPcorcamval(
                            $proposta,
                            $lances[$idFornecedor],
                            $idPcorcamforne,
                            $idPcorcamitem,
                            $tipoJulgamento
                        );
                        $this->lidaPcorcamjulg(
                            $proposta,
                            $ranking,
                            $idPcorcamitem,
                            $idPcorcamforne
                        );
                    }
                }
            }

            /** @var Participantes[] $participantes */
            foreach($participantes as $participante) {

                if($participante->getCnpj() == ""){
                    throw new Exception("Participante sem documento cadastrado ".$participante->getRazaoSocial());
                }
                $numcgm = $this->buscaNumcgm($participante->getCnpj());
                $representanteLegal = $participante->getRepresentanteLegal();
                $this->lidaHabilitacaoforn(
                    $numcgm,
                    $idJulgamento,
                    $representanteLegal,
                    $dataAbertura
                );
            }

            $this->lidaLiclicitasituacao($idJulgamento);
            $this->lidaLiclicita($idJulgamento);

            db_fim_transacao(false);
            return ['success' => true];
        } catch(Exception $e) {
            db_fim_transacao(true);
            return [
                'success' => false,
                 'message' =>utf8_encode($e->getMessage())
            ];
        }
    }

    /**
     * Busca Numcgm
     *
     * @param string $cnpj
     * @return string
     */
    private function buscaNumcgm(string $cnpj): string
    {
        $numcgmResource = $this->climportjulgamento->buscaNumCgm($cnpj);

        if ((int)$this->climportjulgamento->erro_status === 0) {
            throw new Exception('Documento nao encontrato no ecidade : '.$cnpj);
        }

        $numcgm = (db_utils::fieldsMemory($numcgmResource, 0))->numcgm;
        return $numcgm;
    }

    /**
     * Cuida de inserir na Pcorcamforne
     *
     * @param string $numcgm
     * @param integer $idPcorcam
     * @return integer
     * @throws Exception
     */
    private function lidaPcorcamforne(string $numcgm, int $idPcorcam): int
    {
        $clpcorcamforne                  = new cl_pcorcamforne;

        $result = $clpcorcamforne->sql_record(
            $clpcorcamforne->sql_query_file(
            null,
            "pc21_orcamforne",
            null,
            " pc21_numcgm = '$numcgm' and pc21_codorc = '$idPcorcam'"
             )
        );

        if (!empty($result)) {
            return (int)(db_utils::fieldsMemory($result, 0))->pc21_orcamforne;
        }

        $clpcorcamforne->pc21_codorc     = $idPcorcam;
        $clpcorcamforne->pc21_numcgm     = $numcgm;
        $clpcorcamforne->pc21_importado  = 't';
        $clpcorcamforne->pc21_prazoent   = null;
        $clpcorcamforne->pc21_validadorc = null;
        $clpcorcamforne->incluir(null);

        if ((int)$clpcorcamforne->erro_status  !== 1) {
            throw new Exception("Pcorcamforne ".$clpcorcamforne->erro_msg. $clpcorcamforne->erro_campo);
        }

        return (int)$clpcorcamforne->pc21_orcamforne;
    }

    /**
     * Cuida de inserir na Pcorcamitem
     *
     * @param integer $idPcorcam
     * @return integer
     */
    private function lidaPcorcamitem(int $idPcorcam): int
    {
        $clpcorcamitem              = new cl_pcorcamitem;
        $clpcorcamitem->pc22_codorc = $idPcorcam;
		$clpcorcamitem->incluir(null);

        if ( (int)$clpcorcamitem->erro_status  !== 1 ) {
            throw new Exception("Pcorcamitem ".$clpcorcamitem->erro_msg);
        }

        return $clpcorcamitem->pc22_orcamitem;
    }

    /**
     * Cuida de inserir na Pcorcamitemlic
     *
     * @param integer $id
     * @param integer $idPcorcamitem
     * @return integer
     */
    private function lidaPcorcamitemlic(int $id, int $idPcorcamitem, int $idJulgamento  ): int
    {
        $clpcorcamitemlic                  = new cl_pcorcamitemlic;
        $result = $this->climportjulgamento->buscaL21codigo(
            $id,
            $idJulgamento
        );

        if (empty($result)) {
            throw new Exception("Pcorcamitemlic - erro ao buscar pc26_liclicitem");
        }

        $clpcorcamitemlic->pc26_liclicitem = (int)(db_utils::fieldsMemory($result, 0))->idliclicitem;

        $clpcorcamitemlic->pc26_orcamitem  = $idPcorcamitem;
        $clpcorcamitemlic->incluir(null);

        if ((int)$clpcorcamitemlic->erro_status  !== 1) {
            throw new Exception("Pcorcamitemlic ".$clpcorcamitemlic->erro_msg);
        }

        return (int)$clpcorcamitemlic->pc26_liclicitem;
    }

    /**
     * Cuida de inserir na Pcorcamval
     *
     * @param Proposta $proposta
     * @param integer $idOrcamforne
     * @param integer $idOrcamitem
     * @param integer $tipoJulgamento
     * @return void
     */
    private function lidaPcorcamval(Proposta $proposta, Lance $lance, int $idOrcamforne, int $idOrcamitem, int $tipoJulgamento )
    {
        $clpcorcamval                          = new cl_pcorcamval;
        $clpcorcamval->pc23_valor              = $lance->getValorTotal();
        $clpcorcamval->pc23_quant              = $proposta->getQuantidade();
        $clpcorcamval->pc23_obs                = $proposta->getMarca();
        $clpcorcamval->pc23_vlrun              = $lance->getValorUnitario();
        $clpcorcamval->pc23_validmin           = null;
        $clpcorcamval->pc23_perctaxadesctabela = null;
        $clpcorcamval->pc23_percentualdesconto = $tipoJulgamento == 1 ? $proposta->getValorDesconto()
            : null;

        $clpcorcamval->incluir($idOrcamforne, $idOrcamitem);

        if ((int)$clpcorcamval->erro_status  !== 1) {
            throw new Exception("Pcorcamval ".$clpcorcamval->erro_msg);
        }
    }

    /**
     * Cuida de inserir na Pcorcamjulg
     *
     * @param Proposta $proposta
     * @param Array $ranking
     * @param integer $idPcorcamitem
     * @param integer $idPcorcamforne
     * @return void
     * @throws Exception
     */
    private function lidaPcorcamjulg(Proposta $proposta, Array $ranking, int $idPcorcamitem, int $idPcorcamforne): void
    {
        $cnpj            = $proposta->getIdFornecedor();
        $rankingFiltrado = array_filter($ranking, function(Ranking $posicao) use($cnpj) {
            return $posicao->getIdFornecedor() == $cnpj;
        });
        $posicaoFiltrada = (reset($rankingFiltrado))->getPosicao();

        $clpcorcamjulg                  = new cl_pcorcamjulg;
        $clpcorcamjulg->pc24_pontuacao  = $posicaoFiltrada;

        $clpcorcamjulg->incluir($idPcorcamitem, $idPcorcamforne);

        if ((int)$clpcorcamjulg->erro_status  !== 1) {
            throw new Exception("Pcorcamjulg ".$clpcorcamjulg->erro_msg);
        }
    }

    /**
     * Cuida de inserir na Habilitacaoforn
     *
     * @param string $numcgm
     * @param integer $idJulgamento
     * @param string $representanteLegal
     * @param string $dataHab
     * @return void
     * @throws Exception
     */
    private function lidaHabilitacaoforn(
        string $numcgm,
        int $idJulgamento,
        string $representanteLegal,
        string $dataHabilitacao
    ): void {
        $clhabilitacaoforn = new cl_habilitacaoforn;
        $query = $clhabilitacaoforn->sql_query( null, "l206_sequencial", null, $dbwhere=" l206_fornecedor = $numcgm and l206_licitacao = $idJulgamento");
        $result = $clhabilitacaoforn->sql_record($query);

        if(!empty($result)) {
            return;
        }

        $clhabilitacaoforn->l206_fornecedor        = $numcgm;
        $clhabilitacaoforn->l206_licitacao        = $idJulgamento;
        $clhabilitacaoforn->l206_representante    = $representanteLegal;
        $clhabilitacaoforn->l206_datahab          = $dataHabilitacao;
        $clhabilitacaoforn->l206_numcertidaoinss  = null;
        $clhabilitacaoforn->l206_dataemissaoinss  = null;
        $clhabilitacaoforn->l206_datavalidadeinss = null;
        $clhabilitacaoforn->l206_numcertidaofgts  = null;
        $clhabilitacaoforn->l206_dataemissaofgts  = null;
        $clhabilitacaoforn->l206_datavalidadefgts = null;
        $clhabilitacaoforn->l206_numcertidaocndt  = null;
        $clhabilitacaoforn->l206_dataemissaocndt  = null;
        $clhabilitacaoforn->l206_datavalidadecndt = null;

        $clhabilitacaoforn->incluir(null);

        if ((int)$clhabilitacaoforn->erro_status  !== 1) {
            throw new Exception("Habilitacaoforn ".$clhabilitacaoforn->erro_msg." Cgm:".$numcgm);
        }

    }

    /**
     * Cuida de inserir na Liclicitasituacao
     *
     * @param integer $idJulgamento
     * @return void
     * @throws Exception
     */
    private function lidaLiclicitasituacao(int $idJulgamento): void
    {
        $clliclicitasituacao   = new cl_liclicitasituacao;
        $clliclicitasituacao->l11_data        = date("Y-m-d", db_getsession("DB_datausu"));
        $clliclicitasituacao->l11_hora        = db_hora();
        $clliclicitasituacao->l11_obs         = "Julgamento importado plataforma eletr�nica";
        $clliclicitasituacao->l11_licsituacao = 1;
        $clliclicitasituacao->l11_id_usuario  = db_getsession("DB_id_usuario");
        $clliclicitasituacao->l11_liclicita   = $idJulgamento;
        $clliclicitasituacao->incluir(null);

        if ((int)$clliclicitasituacao->erro_status  !== 1) {
            throw new Exception("Liclicitasituacao". $clliclicitasituacao->erro_msg);
        }
    }

    /**
     * Cuida de inserir na Liclicit
     *
     * @param integer $l20_codigo
     * @return void
     * @throws Exception
     */
    private function lidaLiclicita(int $l20_codigo)
    {
        $clliclicita          = new cl_liclicita;

        $clliclicita->l20_codigo  = $l20_codigo;
        $clliclicita->l20_licsituacao = '1';
        $clliclicita->alterar_liclicitajulgamento($l20_codigo);

        if ((int)$clliclicita->erro_status  !== 1) {
            throw new Exception("Liclicita ". $clliclicita->erro_msg);
        }
    }
}
