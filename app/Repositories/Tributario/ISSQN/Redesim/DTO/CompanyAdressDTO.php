<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

class CompanyAdressDTO
{
    /**
     * U para Urbano, R para Rural, S para Sem regulariza��o
     * @var string
     */
    public string $tipoCod = '';

    public string $inscricaoImobiliaria = '';

    public string $incra;

    public string $cidade;

    public string $tipoLogradouroCod;

    public string $numero;

    public string $complemento = '';

    public string $bairro;

    public string $cep;

    public string $uf;

    public string $pontoReferencia = '';

    public string $latitude = '';

    public string $longitude;

    public string $logradouro;

    public bool $exclusivamenteResidencial = false;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

}
