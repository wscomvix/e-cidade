<?php

use Phinx\Migration\AbstractMigration;

class Oc12100 extends AbstractMigration
{
    public function up()
    {
		$sql = "
			update db_syscampo set rotulo = 'C�d. Acordo', rotulorel = 'C�d. Acordo' where nomecam = 'ac16_sequencial';
		";
		$this->execute($sql);
    }

    public function down(){
    	$sql = "
    		update db_syscampo set rotulo = 'C�digo Acordo', rotulorel = 'C�digo Acordo' where nomecam = 'ac16_sequencial';
    	";
    	$this->execute($sql);
	}
}
