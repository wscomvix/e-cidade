<?php

require_once("model/licitacao/PortalCompras/Comandos/ValidaAcessoApiInterface.model.php");

class ValidaResultadoVazio implements ValidaAcessoApiInterface
{
     /**
     * Verifica se resultado � vazio
     *
     * @param resource $results
     * @return void
     */
    public function execute($results): void
    {
        if (empty($results)) {
            throw new Exception("Registro n�o encontrado");
        }
    }
}
