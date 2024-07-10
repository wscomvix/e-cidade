<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19677 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        -- INCLUI O TIPO DE DOCUMENTO 1045 - CABE�ALHO DETALHADO ALVAR� MODELO 99

        INSERT INTO configuracoes.db_tipodoc (db08_codigo, db08_descr) VALUES(1045, 'CABE�ALHO DETALHADO ALVAR�');               
                
        COMMIT;        

SQL;
        $this->execute($sql);
    }

}
