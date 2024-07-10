<?php

use Phinx\Migration\AbstractMigration;

class Oc16862 extends AbstractMigration
{
    public function up()
    {
        $sql = "
                ALTER TABLE contratos102022 RENAME COLUMN si83_unidadedemedidaprazoexex TO si83_unidadedemedidaprazoexec;
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
                ALTER TABLE contratos102022 RENAME COLUMN si83_unidadedemedidaprazoexec TO si83_unidadedemedidaprazoexex;
        ";
        $this->execute($sql);
    }
}
