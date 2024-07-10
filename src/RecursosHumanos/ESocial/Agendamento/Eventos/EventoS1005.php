<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\EventoBase;

/**
 * Classe respons�vel por montar as informa��es do evento S1005 Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Robson de Jesus
 */
class EventoS1005 extends EventoBase
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

        if (empty($this->dados[0]->dadosEstab->aliqGilrat->procAdmJudRat->tpProc)) {
            unset($this->dados[0]->dadosEstab->aliqGilrat->procAdmJudRat);
        }
        if (empty($this->dados[0]->dadosEstab->aliqGilrat->procAdmJudFap->tpProc)) {
            unset($this->dados[0]->dadosEstab->aliqGilrat->procAdmJudFap);
        }

        if ($this->dados[0]->dadosEstab->infoCaepf->tpCaepf == 0) {
            //unset($this->dados[0]->dadosEstab->infoCaepf->tpCaepf);
            $this->dados[0]->dadosEstab->infoCaepf = null;
        }

        if ($this->dados[0]->dadosEstab->infoObra->indSubstPatrObra == 0) {
            //unset($this->dados[0]->dadosEstab->infoObra->indSubstPatrObra);
            $this->dados[0]->dadosEstab->infoObra = null;
        }

        /*if ($this->dados[0]->dadosEstab->infoTrab->infoApr->contApr == 0) {
            $this->dados[0]->dadosEstab->infoTrab->infoApr->nrProcJud = null;
            unset($this->dados[0]->dadosEstab->infoTrab->infoApr->infoEntEduc);
        }*/
        // var_dump($this->dados[0]->dadosEstab->infoCaepf->tpCaepf);
        // exit;

        $oDadosAPI                          = new \stdClass;
        $oDadosAPI->evtTabEstab             = new \stdClass;
        $oDadosAPI->evtTabEstab->sequencial = 1;
        $oDadosAPI->evtTabEstab->tpInsc     = $this->dados[0]->ideEstab->tpInsc;
        $oDadosAPI->evtTabEstab->nrInsc     = $this->dados[0]->ideEstab->nrInsc;
        $oDadosAPI->evtTabEstab->iniValid   = $this->iniValid;
        if (!empty($this->fimValid)) {
            $oDadosAPI->evtTabEstab->fimvalid = $this->fimValid;
        }
        $oDadosAPI->evtTabEstab->modo       = "INC";
        $oDadosAPI->evtTabEstab->dadosEstab = $this->dados[0]->dadosEstab;
        // var_dump($oDadosAPI);
        // exit;
        return $oDadosAPI;
    }
}
