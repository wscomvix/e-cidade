<?php

use Phinx\Migration\AbstractMigration;

class Oc10279 extends AbstractMigration
{
    public function up()
    {
      $this->execute("UPDATE db_syscampo SET descricao = 'Combust�vel', rotulo = 'Combust�vel', rotulorel = 'Combust�vel' WHERE codcam = 10982");
    }

    public function down(){

    }
}
