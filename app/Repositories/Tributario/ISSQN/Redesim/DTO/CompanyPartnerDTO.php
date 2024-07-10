<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use DateTime;

class CompanyPartnerDTO extends BaseDTO
{
    public string $cpf;

    public DateTime $inicio;

    public ?DateTime $fim = null;

    public bool $representanteLegal;

    public string $nome;

    public DateTime $inclusao;

    public ?float $participacao = null;

    public ?CompanyPartnerAdressDTO $endereco = null;

    public string $ddd = '';

    public string $telefone = '';

    public string $email = '';

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }
        foreach ($data as $attribute => $value) {
            if (in_array($attribute, ['inclusao', 'inicio', 'fim'])) {
                $value = $this->formatDateBr($value);
            }

            if ($attribute === 'participacao') {
                $value = (float) $value;
            }
            $this->$attribute = $value;
        }
    }
}
