<?php

use Phinx\Migration\AbstractMigration;

class Oc20291v3 extends AbstractMigration
{
    public function up()
    {

    $sql = <<<SQL

    BEGIN;

    SELECT fc_startsession();

        UPDATE configuracoes.db_syscampo
        SET descricao='Data inicio participa��o',rotulo='Data inicio participa��o', rotulorel='Data inicio participa��o'
        WHERE nomecam = 'c215_datainicioparticipacao';

        UPDATE configuracoes.db_syscampo
        SET descricao='Data fim participa��o', rotulo='Data fim participa��o', rotulorel='Data fim participa��o'
        WHERE nomecam = 'c215_datafimparticipacao';

    COMMIT;

SQL;
        $this->execute($sql);
    } 
}