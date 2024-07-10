<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class EsocialAcertoMenus extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

        UPDATE db_itensmenu SET descricao = 'Tabela de Rubricas - S1010' WHERE descricao = 'Tabela de Rubricas';

        UPDATE db_itensmenu SET descricao = 'Tabela de Lota��o Tribut�ria - S1020' WHERE descricao = 'Lota��o Tribut�ria';

        UPDATE db_menu SET menusequencia = 1 WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao = 'Informa��es do Empregador');
        UPDATE db_itensmenu SET descricao = 'Informa��es do Empregador - S1000 e S1005' WHERE descricao = 'Informa��es do Empregador';

        INSERT INTO db_itensmenu
        VALUES ((SELECT MAX(id_item)+1 FROM db_itensmenu),
            'Tabelas',
            'Tabelas',
            '',
            1,
            1,
            'Tabelas',
            't');

        INSERT INTO db_menu 
        VALUES (10466,
            (SELECT MAX(id_item) FROM db_itensmenu),
            1,
            10216);

        UPDATE db_menu SET id_item = (SELECT MAX(id_item) FROM db_itensmenu) WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao = 'Preenchimento') AND id_item_filho != (SELECT id_item FROM db_itensmenu WHERE descricao = 'Confer�ncia de Dados') AND id_item_filho != (SELECT MAX(id_item) FROM db_itensmenu);

        INSERT INTO db_itensmenu
        VALUES ((SELECT MAX(id_item)+1 FROM db_itensmenu),
            'Peri�dicos',
            'Peri�dicos',
            '',
            1,
            1,
            'Peri�dicos',
            't');

        INSERT INTO db_menu 
        VALUES (10466,
            (SELECT MAX(id_item) FROM db_itensmenu),
            2,
            10216);

        INSERT INTO db_itensmenu
        VALUES ((SELECT MAX(id_item)+1 FROM db_itensmenu),
            'N�o Peri�dicos',
            'N�o Peri�dicos',
            '',
            1,
            1,
            'N�o Peri�dicos',
            't');

        INSERT INTO db_menu 
        VALUES (10466,
            (SELECT MAX(id_item) FROM db_itensmenu),
            3,
            10216);

        UPDATE db_menu SET id_item = (SELECT MAX(id_item) FROM db_itensmenu) WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao = 'Confer�ncia de Dados');

        UPDATE db_itensmenu SET descricao = 'Confer�ncia de Dados - S2200' WHERE descricao = 'Confer�ncia de Dados';

SQL;
        $this->execute($sql);
    }

    public function down() {

    }
 }
