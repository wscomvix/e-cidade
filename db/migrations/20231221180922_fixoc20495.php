<?php

use Phinx\Migration\AbstractMigration;

class Fixoc20495 extends AbstractMigration
{

    public function up()
    {
        $sql = "INSERT INTO solicitacaotipo VALUES (8,'Solicita��o Unificada');";
        $this->execute($sql);
    }
}
