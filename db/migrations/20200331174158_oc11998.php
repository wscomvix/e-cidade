<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11998 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        INSERT INTO eledessiope VALUES 
        ('33390085300', 'Aux�lio Reclus�o', '2020'),
        ('33390085600', 'Sal�rio Fam�lia', '2020'),
        ('33390089900', 'Outros Benef�cios Assistenciais do servidor e do militar', '2020');

        UPDATE naturdessiope SET c222_natdespsiope = '33390089900' WHERE c222_natdespecidade = '33390080000' AND c222_anousu = '2020';        	

        COMMIT;

SQL;
        $this->execute($sql);
    }

}