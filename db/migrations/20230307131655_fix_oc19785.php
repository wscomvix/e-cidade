<?php

use Phinx\Migration\AbstractMigration;

class FixOc19785 extends AbstractMigration
{
    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Relat�rios', help = 'Relat�rios',desctec = 'Relat�rios'
        where descricao = 'Relatrios';";
        $this->execute($sql);
    }
}
