<?php

namespace App\Services\Patrimonial\Orcamento;

use App\Repositories\Patrimonial\PcOrcamRepository;
use Exception;

class PcOrcamService
{

    /**
     * @var PcOrcamRepository
     */
    private $pcOrcamRepository;

    public function __construct()
    {
        $this->pcOrcamRepository = new PcOrcamRepository();
    }

    /**
     *
     * @param array $orcamento - dados do or�amento
     * @return bool
     */

    public function updateOrcamento($orcamento)
    {

        if($orcamento->pc20_dtate == ""){
            throw new Exception("Usu�rio: Campo Prazo limite para entrega do or�amento n�o Informado.");
        }

        if($orcamento->pc20_hrate == ""){
            throw new Exception("Usu�rio: Campo Hora limite para entrega do or�amento n�o Informado.");
        }

        $orcamento->pc20_prazoentrega = $orcamento->pc20_prazoentrega == "" ? 0 : $orcamento->pc20_prazoentrega;
        $orcamento->pc20_validadeorcamento = $orcamento->pc20_validadeorcamento == "" ? 0 : $orcamento->pc20_validadeorcamento;

        $result = $this->pcOrcamRepository->update($orcamento);
        return $result;

    }

    /**
     *
     * @param int $sequencial - Sequencial do Or�amento/Licita��o/Processo de Compra
     * @param string $origem - Origem do Or�amento
     * @return array
     */
    public function getDadosManutencaoOrcamento($sequencial,$origem)
    {

        $dadosOrcamento = $this->pcOrcamRepository->getDadosManutencaoOrcamento($sequencial,$origem);

        if ($dadosOrcamento[0]->situacao != '0' && $dadosOrcamento[0]->situacao != null) {
            throw new Exception('Carregamento de dados abortado, o or�amento selecionado possui Processo Licitat�rio vinculado que n�o est� com o status Em andamento.');
        }

        if ($dadosOrcamento[0]->precoreferencia != null) {
            throw new Exception('Carregamento de dados abortado, o or�amento selecionado possui Pre�o de Refer�ncia');
        }

        return $dadosOrcamento;
    }
}
