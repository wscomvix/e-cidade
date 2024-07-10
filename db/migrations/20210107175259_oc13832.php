<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13832 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
  
        BEGIN;
        SELECT fc_startsession();

        INSERT INTO orcsuplemtipo
            SELECT 1020 AS o48_tiposup,
                    'ATO ADMINISTRATIVO DE ALTERACAO DO ELEMENTO DE DESPESA' AS o48_descr,
                    7 AS o48_coddocsup,
                    51 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    7 AS o48_suplcreditoespecial,
                    51 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1021 AS o48_tiposup,
                    'ATO ADMINISTRATIVO DE ALTERACAO DE MODALIDADE DE APLICACAO' AS o48_descr,
                    7 AS o48_coddocsup,
                    51 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    7 AS o48_suplcreditoespecial,
                    51 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1022 AS o48_tiposup,
                    'ATO ADMINISTRATIVO DE ALTERACAO DE FUN��O E SUBFUNCAO' AS o48_descr,
                    7 AS o48_coddocsup,
                    51 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    7 AS o48_suplcreditoespecial,
                    51 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1023 AS o48_tiposup,
                    'SUPLEMENTACAO DE CREDITOS ESPECIAIS POR REDUCAO' AS o48_descr,
                    56 AS o48_coddocsup,
                    57 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    56 AS o48_suplcreditoespecial,
                    57 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1024 AS o48_tiposup,
                    'SUPLEMENTACAO DE CREDITOS ESPECIAIS POR SUPERAVIT FINANCEIRO' AS o48_descr,
                    60 AS o48_coddocsup,
                    0 AS o48_coddocred,
                    0 AS o48_arrecadmaior,
                    't' AS o48_superavit,
                    60 AS o48_suplcreditoespecial,
                    0 AS o48_redcreditoespecial;

        INSERT INTO orcsuplemtipo
            SELECT 1025 AS o48_tiposup,
                    'SUPLEMENTACAO DE CREDITO ESPECIAL ARRECADACAO A MAIOR' AS o48_descr,
                    61 AS o48_coddocsup,
                    0 AS o48_coddocred,
                    58 AS o48_arrecadmaior,
                    'f' AS o48_superavit,
                    61 AS o48_suplcreditoespecial,
                    0 AS o48_redcreditoespecial;                    

        UPDATE db_itensmenu
        SET descricao = 'Lei de Altera��o Or�ament�ria',
            help = 'Lei de Altera��o Or�ament�ria',
            desctec = 'Lei de Altera��o Or�ament�ria'
        WHERE descricao = 'Projeto de Lei';

        UPDATE db_syscampo 
        SET descricao = 'Data de San��o da Lei',
            rotulo = 'Data de San��o da Lei',
            rotulorel = 'Data de San��o da Lei'
        WHERE nomecam = 'o138_data';

        UPDATE db_syscampo 
        SET descricao = 'Texto da Lei',
            rotulo = 'Texto da Lei',
            rotulorel = 'Texto da Lei'
        WHERE nomecam = 'o138_textolei';

        UPDATE db_syscampo SET nulo = 'f' WHERE nomecam = 'o39_numero';

        UPDATE db_syscampo SET nulo = 'f' WHERE nomecam = 'o39_data';

        UPDATE db_syscampo SET rotulo = 'Descri��o' WHERE nomecam = 'o39_descr';

        UPDATE db_syscampo SET rotulo = 'C�digo' WHERE nomecam = 'o39_codproj';

        DELETE FROM orcsuplemtipo WHERE o48_tiposup = 1005;

        UPDATE orcsuplemtipo SET o48_descr = 'CREDITO SUPLEMENTAR - ANULA��O DE DOTA��ES' WHERE o48_tiposup = 1001;
        
        UPDATE orcsuplemtipo SET o48_descr = 'CREDITO SUPLEMENTAR - OPERACAO DE CREDITO' WHERE o48_tiposup = 1002;
        
        UPDATE orcsuplemtipo SET o48_descr = 'CREDITO SUPLEMENTAR - SUPERAVIT FINANCEIRO' WHERE o48_tiposup = 1003;
        
        UPDATE orcsuplemtipo SET o48_descr = 'CREDITO SUPLEMENTAR - EXCESSO DE ARRECADA��O' WHERE o48_tiposup = 1004;

        UPDATE orcsuplemtipo SET o48_coddocred = 0, o48_arrecadmaior = 58 WHERE o48_tiposup = 1019;                     

        COMMIT;

SQL;
    $this->execute($sql);
 	}

}