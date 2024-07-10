<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use stdClass;

class RedesimApiResponse
{
    /**
     * Identificador �nico
     * @var int
     */
    public int $id;

    /**
     * Munic�pio
     * @var string
     */
    public string $cliente;

    /**
     * @var stdClass
     */
    public stdClass $dados;

}
