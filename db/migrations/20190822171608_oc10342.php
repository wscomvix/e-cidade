<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc10342 extends PostgresMigration
{

    public function up()
    {
        $this->insertCampoCodFundoTceMg();
    }

    public function insertCampoCodFundoTceMg()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        --Inser��o dos campo no dicion�rio
        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
        VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'si09_codfundotcemg', 'int8', 'C�digo do Fundo TCE/MG', '0', 'C�digo do Fundo TCE/MG', 8, FALSE, FALSE, FALSE, 0, 'int', 'C�digo do Fundo TCE/MG');
        
        -- V�nculo tabelas com campo
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
        VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq='infocomplementaresinstit' LIMIT 1), (SELECT codcam FROM db_syscampo WHERE nomecam = 'si09_codfundotcemg'), 12, 0);

        -- Alter table
        ALTER TABLE infocomplementaresinstit ADD COLUMN si09_codfundotcemg varchar(8);

        COMMIT;

SQL;
        $this->execute($sql);

    }
}