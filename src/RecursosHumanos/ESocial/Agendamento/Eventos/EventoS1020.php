<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\EventoBase;

/**
 * Classe respons�vel por montar as informa��es do evento S1020 Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Robson de Jesus
 */
class EventoS1020 extends EventoBase
{

    /**
     *
     * @param \stdClass $dados
     */
    function __construct($dados)
    {
        parent::__construct($dados);
    }

    /**
     * Retorna dados no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    public function montarDados()
    {
        foreach ($this->dados as $oDado) {

            $oDadosAPI                            = new \stdClass;
            $oDadosAPI->evtTabLotacao             = new \stdClass;
            $oDadosAPI->evtTabLotacao->sequencial = 1;
            $oDadosAPI->evtTabLotacao->codLotacao = $oDado->ideLotacao->codLotacao;
            $oDadosAPI->evtTabLotacao->iniValid   = $this->iniValid;
            if (!empty($this->fimValid)) {
                $oDadosAPI->evtTabLotacao->fimvalid = $this->fimValid;
            }

            $oDadosAPI->evtTabLotacao->modo               = "INC";
            $oDadosAPI->evtTabLotacao->dadosLotacao       = $oDado->dadosLotacao;
            $oDadosAPI->evtTabLotacao->dadosLotacao->fpas = $oDado->fpasLotacao->fpas;
            $oDadosAPI->evtTabLotacao->dadosLotacao->tpLotacao = str_pad($oDado->dadosLotacao->tpLotacao, 2, "0", STR_PAD_LEFT);
            $oDadosAPI->evtTabLotacao->dadosLotacao->tpInsc = empty($oDado->dadosLotacao->tpInsc) ? null : $oDado->dadosLotacao->tpInsc;
            $oDadosAPI->evtTabLotacao->dadosLotacao->nrInsc = empty($oDado->dadosLotacao->nrInsc) ? null : $oDado->dadosLotacao->nrInsc;

            if (in_array($oDado->dadosLotacao->tpLotacao, array('01', '10', '21', '24', '90', '91'))) {
                $oDadosAPI->evtTabLotacao->dadosLotacao->nrInsc = null;
                $oDadosAPI->evtTabLotacao->dadosLotacao->tpInsc = null;
            }

            $oDadosAPI->evtTabLotacao->dadosLotacao->codTercs = str_pad($oDado->fpasLotacao->codTercs, 4, "0", STR_PAD_LEFT);
            $oDadosAPI->evtTabLotacao->dadosLotacao->codTercsSusp = empty($oDado->fpasLotacao->codTercsSusp) ? null : $oDado->fpasLotacao->codTercsSusp;

            if (!empty($oDado->dadosLotacao->procJudTerceiro)) {
                $oDadosAPI->evtTabLotacao->dadosLotacao->procJudTerceiro = $oDado->dadosLotacao->procJudTerceiro;
            }

            if (!empty($oDado->dadosLotacao->infoEmprParcial)) {
                $oDadosAPI->evtTabLotacao->dadosLotacao->infoemprparcial = $oDado->dadosLotacao->infoEmprParcial;
            }

            if (!empty($oDado->dadosLotacao->dadosOpPort)) {
                $oDadosAPI->evtTabLotacao->dadosLotacao->dadosopport = $oDado->dadosLotacao->dadosOpPort;
            }

            $aDadosAPI[] = $oDadosAPI;
        }

        return $aDadosAPI;
    }
}
