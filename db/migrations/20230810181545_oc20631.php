<?php

use Phinx\Migration\AbstractMigration;

class Oc20631 extends AbstractMigration
{
    public function up()
    {
        $sql = "update acordoposicaotipo set ac27_descricao='Acr�scimo/Decr�scimo de item(ns) conjugado' where ac27_sequencial=14";
        $this->execute($sql);
    }
}
