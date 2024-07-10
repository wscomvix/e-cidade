<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13541 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
        
        BEGIN;
        SELECT fc_startsession();

        INSERT INTO db_syscampo VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'o142_orcmodalidadeaplic','bool','Or�amento por Modalidade de Aplica��o','','Or�amento por Modalidade de Aplica��o',1,false,false,false,5,'text','Or�amento por Modalidade de Aplica��o');

        INSERT INTO db_sysarqcamp VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'ppaleidadocomplementar'), (SELECT codcam FROM db_syscampo WHERE nomecam = 'o142_orcmodalidadeaplic'), 7, 0);

        ALTER TABLE ppaleidadocomplementar ADD COLUMN o142_orcmodalidadeaplic boolean NOT NULL DEFAULT FALSE;

        COMMIT;        
SQL;
        $this->execute($sql);
    }
}