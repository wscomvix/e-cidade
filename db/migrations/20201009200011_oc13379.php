<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13379 extends PostgresMigration
{

    public function up()
    {
        $table = $this->table('cadferiaspremio', array('schema' => 'pessoal', 'id' => false, 'primary_key' => array('r95_sequencial')));
        $table->addColumn('r95_sequencial', 'integer')
              ->addColumn('r95_anousu', 'integer')
              ->addColumn('r95_mesusu', 'integer')
              ->addColumn('r95_regist', 'integer')
              ->addColumn('r95_perai', 'date')
              ->addColumn('r95_peraf', 'date')
              ->addColumn('r95_ndias', 'integer')
              ->addColumn('r95_per1i', 'date')
              ->addColumn('r95_per1f', 'date')
              ->addForeignkey('r95_regist', 'pessoal.rhpessoal', 'rh01_regist', array('constraint' => 'cadferiaspremio_rhpessoal_fk'))
              ->save();
        $this->table('cfpess', array('schema' => 'pessoal'))
             ->addColumn('r11_feriaspremio', 'string', array('limit' => 4, 'null' => true))
             ->save();
        if ($this->checkDicionarioDados()) {
            $this->insertDicionarioDados();
        }
        $this->createSequence();
        $this->insertMenu();
    }

    public function down()
    {
        $this->table('cadferiaspremio', array('schema' => 'pessoal'))->drop();
        $this->table('cfpess', array('schema' => 'pessoal'))->removeColumn('r11_feriaspremio');
        $this->dropSequence();
        $this->dropMenu();
    }

    public function createSequence() {
        $this->execute("CREATE SEQUENCE cadferiaspremio_r95_sequencial_seq");
    }

    private function dropSequence() 
    {
        $this->execute("DROP SEQUENCE cadferiaspremio_r95_sequencial_seq");
    }

    private function insertMenu()
    {
        $sql = "
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'F�rias Normais', 'F�rias Normais','',1,1,'','t');
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'F�rias Pr�mio', 'F�rias Pr�mio','',1,1,'','t');
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Inclus�o', 'Inclus�o','pes4_cadferiaspremio001.php',1,1,'','t');
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Consulta', 'Consulta','pes4_cadferiaspremio002.php',1,1,'','t');
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Exclus�o', 'Exclus�o','pes4_cadferiaspremio003.php',1,1,'','t');

        UPDATE db_menu SET id_item = (SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Normais') WHERE id_item = 5047 AND modulo = 952;

        INSERT INTO db_menu VALUES (5047,(SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Normais'),1,952);
        INSERT INTO db_menu VALUES (5047,(SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Pr�mio'),2,952);
        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Pr�mio'),(SELECT id_item FROM db_itensmenu WHERE funcao = 'pes4_cadferiaspremio001.php'),1,952);
        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Pr�mio'),(SELECT id_item FROM db_itensmenu WHERE funcao = 'pes4_cadferiaspremio002.php'),2,952);
        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Pr�mio'),(SELECT id_item FROM db_itensmenu WHERE funcao = 'pes4_cadferiaspremio003.php'),3,952);

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Confer�ncia de F�rias Pr�mio', 'Confer�ncia de F�rias Pr�mio','pes2_confferiaspremio001.php',1,1,'','t');
        INSERT INTO db_menu VALUES (5703,(SELECT id_item FROM db_itensmenu WHERE funcao = 'pes2_confferiaspremio001.php'),5,952);
        ";
        $this->execute($sql);
    }

    private function dropMenu()
    {
        $sql = "
        DELETE FROM db_menu WHERE id_item = 5047 AND modulo = 952;
        UPDATE db_menu SET id_item = 5047 WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Normais') AND modulo = 952;
        DELETE FROM db_menu WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao = 'F�rias Pr�mio') AND modulo = 952;
        
        DELETE FROM db_itensmenu WHERE descricao = 'F�rias Normais';
        DELETE FROM db_itensmenu WHERE descricao = 'F�rias Pr�mio';
        DELETE FROM db_itensmenu WHERE funcao = 'pes4_cadferiaspremio001.php';
        DELETE FROM db_itensmenu WHERE funcao = 'pes4_cadferiaspremio002.php';
        DELETE FROM db_itensmenu WHERE funcao = 'pes4_cadferiaspremio003.php';

        DELETE FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE funcao = 'pes2_confferiaspremio001.php') AND modulo = 952;        
        DELETE FROM db_itensmenu WHERE funcao = 'pes2_confferiaspremio001.php';
        ";
        $this->execute($sql);
    }

    private function checkDicionarioDados()
    {
        $result = $this->fetchRow("SELECT * FROM db_sysarquivo WHERE nomearq = 'cadferiaspremio'");
        if (empty($result)) {
            return true;
        }
        return false;
    }

    private function insertDicionarioDados()
    {
        $sql = <<<SQL
        -- INSERINDO db_sysarquivo
        INSERT INTO configuracoes.db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'cadferiaspremio', 'Cadastro de F�rias Pr�mio', 'r95  ', '2020-10-10', 'Cadastro de F�rias Pr�mio', 0, false, false, false, false);
         
        -- INSERINDO db_sysarqmod
        INSERT INTO configuracoes.db_sysarqmod (codmod, codarq) VALUES (28, (select max(codarq) from db_sysarquivo));
         
        -- INSERINDO db_syscampo
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_sequencial', 'int8', 'C�digo Sequencial', '0', 'C�digo Sequencial', 8, false, false, false, 1, 'text', 'C�digo Sequencial');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_anousu', 'int8', 'Ano do Ex�rcicio', '0', 'Ano do Ex�rcicio', 8, false, false, false, 1, 'text', 'Ano do Ex�rcicio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_mesusu', 'int8', 'M�s do Exerc�cio', '0', 'M�s do Exerc�cio', 8, false, false, false, 1, 'text', 'M�s do Exerc�cio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_regist', 'int8', 'Matr�cula do Funcion�rio', '0', 'Matr�cula do Funcion�rio', 8, false, false, false, 1, 'text', 'Matr�cula do Funcion�rio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_perai', 'date', 'Data de In�cio do per�odo aquisitivo', 'null', 'Per�odo Aquisitivo', 10, false, false, false, 1, 'text', 'Per�odo Aquisitivo');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_peraf', 'date', 'Data Final do per�odo aquisitivo', 'null', 'Final Per�odo', 10, false, false, false, 1, 'text', 'Final Per�odo');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_ndias', 'int8', 'Total de Dias a Gozar', '0', 'Total de Dias a Gozar', 8, false, false, false, 1, 'text', 'Total de Dias a Gozar');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_per1i', 'date', 'Data de In�cio do per�odo de gozo', 'null', 'In�cio Gozo', 10, false, false, false, 1, 'text', 'In�cio Gozo');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'r95_per1f', 'date', 'Data final do per�odo de gozo', 'null', 'Final Gozo', 10, false, false, false, 1, 'text', 'Final Gozo');
         
        -- INSERINDO db_syssequencia
        INSERT INTO configuracoes.db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES ((select max(codsequencia)+1 from db_syssequencia), 'cadferiaspremio_r95_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
         
        -- INSERINDO db_sysarqcamp
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_sequencial'), 1, (select codsequencia from db_syssequencia where nomesequencia = 'cadferiaspremio_r95_sequencial_seq'));
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_anousu'), 2, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_mesusu'), 3, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_regist'), 4, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_perai'), 5, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_peraf'), 6, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_ndias'), 7, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_per1i'), 8, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_per1f'), 9, 0);
         
        -- INSERINDO db_sysforkey
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_regist'), 1, 1153, 0);
         
        -- INSERINDO db_sysprikey
        INSERT INTO configuracoes.db_sysprikey (codarq, codcam, sequen, referen, camiden) VALUES ((select codarq from db_sysarquivo where nomearq = 'cadferiaspremio'), (select codcam from db_syscampo where nomecam = 'r95_sequencial'), 1, (select codcam from db_syscampo where nomecam = 'r95_sequencial'), 0);

        -- INSERINDO DICIONARIO DE DADOS r11_feriaspremio
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from configuracoes.db_syscampo), 'r11_feriaspremio', 'varchar(4)', 'F�rias Pr�mio', '', 'F�rias Pr�mio', 4, true, true, false, 0, 'text', 'F�rias Pr�mio');

        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from configuracoes.db_sysarquivo where nomearq = 'cfpess'), (select codcam from configuracoes.db_syscampo where nomecam = 'r11_feriaspremio'), 93, 0);
SQL;
        $this->execute($sql);
    }
}
