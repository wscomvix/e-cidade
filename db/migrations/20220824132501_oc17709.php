<?php

use Phinx\Migration\AbstractMigration;

class Oc17709 extends AbstractMigration
{


    public function up()
    {
        $sql = "update db_itensmenu set descricao = 'Declara��o de Frequ�ncia',
        help = 'Declara��o de Frequ�ncia', desctec = 'Declara��o de Frequ�ncia'
        where descricao = 'Atestado de Frequ�ncia';";
        $this->execute($sql);
    }
}
