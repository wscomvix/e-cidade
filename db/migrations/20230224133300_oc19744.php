<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19744 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        -- INCLUI O TIPO DE DOCUMENTO 1040 - CABE�ALHO MODELO CARN� 30

        INSERT INTO configuracoes.db_tipodoc (db08_codigo, db08_descr) VALUES(1040, 'CABE�ALHO CARN� MODELO 30');               
                
        COMMIT;        

SQL;
        $this->execute($sql);
    }

}
