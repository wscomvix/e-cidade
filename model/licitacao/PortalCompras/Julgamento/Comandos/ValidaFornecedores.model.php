<?php

require_once("classes/db_liclicitaimportarjulgamento_classe.php");
require_once("model/licitacao/PortalCompras/Julgamento/Proposta.model.php");
require_once("model/licitacao/PortalCompras/Julgamento/Ranking.model.php");

class ValidaFornecedores
{
    /**
     * Verifica se existe h� fornecedores n�o cadastrados
     *
     * @param array $ranking
     * @return void
     * @throws Exception
     */
    public function execute(Julgamento $julgamento): void
    {
        $fornecedores = [];
        $mensagem = "Fornecedores n�o localizados: ";

        $participantes = $julgamento->getParticipantes();


        $lotes = $julgamento->getLotes();
        $itens = array_map(fn(Lote $lote) => $lote->getItems(), $lotes);
        $rankingItem = array_map(fn(array $item) => $item[0]->getRanking(), $itens);

        $cl_liclicitaimportarjulgamento =  new cl_liclicitaimportarjulgamento();


        foreach ($rankingItem as $rankings) {
            /** @var Ranking[] $rankings */
            foreach ($rankings as $ranking) {
                $idFornecedor = $ranking->getIdFornecedor();
                $resultado = $cl_liclicitaimportarjulgamento->buscaFornecedor($idFornecedor);

                if (empty($resultado) && !in_array($idFornecedor, $fornecedores)) {
                    $fornecedores[] = $idFornecedor;
                    $mensagem .= " \n - ". $idFornecedor;
                    $mensagem .= " - ". $this->filtraParticipantes($participantes, $idFornecedor);
                }
            }
        }

        if (!empty($fornecedores)) {
            throw new Exception(utf8_encode($mensagem));
        }
    }

    /**
     * Retorna a raz�o social
     *
     * @param array $participantes
     * @param string $idFornecedor
     * @return string
     */
    private function filtraParticipantes(array $participantes, string $idFornecedor): string
    {
        $participante = array_filter($participantes, function(Participante $participante) use($idFornecedor) {
            return $participante->getCnpj() == $idFornecedor;
        });

        if (empty($participante)) {
            return "Participante n�o encontrado";
        }

        $chave = array_keys($participante);

        return $participante[$chave[0]]->getRazaoSocial();
    }
}
