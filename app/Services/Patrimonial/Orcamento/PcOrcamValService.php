<?php

namespace App\Services\Patrimonial\Orcamento;

use App\Repositories\Patrimonial\PcOrcamValRepository;
use Exception;

class PcOrcamValService
{

    /**
     * @var PcOrcamValRepository
     */
    private $pcOrcamValRepository;

    public function __construct()
    {
        $this->pcOrcamValRepository = new PcOrcamValRepository();
    }

    /**
     *
     * @param array $pcOrcamVal - dados do or�amento
     * @return bool
     */
    public function updateOrcamVal($pcOrcamVal)
    {

        if($pcOrcamVal['pc23_quant'] == ""){
            throw new Exception("Usu�rio: Campo Quantidade Or�ada n�o Informado.");
        }

        if($pcOrcamVal['pc23_vlrun'] == ""){
            throw new Exception("Usu�rio: Campo Valor Unit�rio n�o Informado.");
        }    

        if($pcOrcamVal['pc23_valor'] == ""){
            throw new Exception("Usu�rio: Campo Valor n�o Informado.");
        }   
        
        $result = $this->pcOrcamValRepository->update($pcOrcamVal);
        return $result;

    }
}
