<?php

interface ValidaAcessoApiInterface
{
    /**
     * Executa pool de valida��es
     *
     * @param resource $results
     * @return void
     */
    public function execute($results): void;
}
