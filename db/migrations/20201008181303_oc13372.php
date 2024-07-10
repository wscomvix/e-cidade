<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13372 extends PostgresMigration
{

    public function up()
    {

        $table = $this->table('rhestagiocurricular', array('schema' => 'recursoshumanos', 'id' => false, 'primary_key' => array('h83_sequencial')));
        $table->addColumn('h83_sequencial', 'integer')
              ->addColumn('h83_regist', 'integer')
              ->addColumn('h83_instensino', 'string', array('limit' => 200))
              ->addColumn('h83_cnpjinstensino', 'string', array('limit' => 14))
              ->addColumn('h83_curso', 'string', array('limit' => 200))
              ->addColumn('h83_matricula', 'integer')
              ->addColumn('h83_dtinicio', 'date')
              ->addColumn('h83_dtfim', 'date')
              ->addColumn('h83_cargahorariatotal', 'integer')
              ->addColumn('h83_supervisor', 'integer')
              ->addColumn('h83_instit', 'integer')
              ->addForeignKey('h83_regist', 'pessoal.rhpessoal', 'rh01_regist', array('constraint' => 'rhestagiocurricular_regist_rhpessoal_fk'))
              ->addForeignKey('h83_supervisor', 'pessoal.rhpessoal', 'rh01_regist', array('constraint' => 'rhestagiocurricular_supervisor_rhpessoal_fk'))
              ->save();
        $this->createSequence();
        $this->insertMenu();
        if ($this->checkDicionarioDados()) {
            $this->insertDicionarioDados();
        }
    }

    public function down() 
    {
        $this->table('rhestagiocurricular', array('schema' => 'recursoshumanos'))->drop();
        $this->dropSequence();
        $this->dropMenu();
    }

    public function createSequence() {
        $this->execute("CREATE SEQUENCE rhestagiocurricular_h83_sequencial_seq");
    }

    private function dropSequence() 
    {
        $this->execute("DROP SEQUENCE rhestagiocurricular_h83_sequencial_seq");
    }

    private function checkDicionarioDados()
    {
        $result = $this->fetchRow("SELECT * FROM db_sysarquivo WHERE nomearq = 'rhestagiocurricular'");
        if (empty($result)) {
            return true;
        }
        return false;
    }

    private function insertMenu()
    {
        $sql = "
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Est�gio', 'Est�gio','',1,1,'','t');

        INSERT INTO db_menu VALUES (29,(SELECT max(id_item) FROM db_itensmenu),(SELECT max(menusequencia)+1 FROM db_menu WHERE id_item = 29 AND modulo = 2323),2323);

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Inclus�o', 'Inclus�o','rec1_rhestagiocurricular001.php',1,1,'','t');
        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Est�gio'),(SELECT max(id_item) FROM db_itensmenu),1,2323);

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Altera��o', 'Altera��o','rec1_rhestagiocurricular002.php',1,1,'','t');
        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Est�gio'),(SELECT max(id_item) FROM db_itensmenu),2,2323);

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Exclus�o', 'Exclus�o','rec1_rhestagiocurricular003.php',1,1,'','t');
        INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Est�gio'),(SELECT max(id_item) FROM db_itensmenu),3,2323);

        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Termo de Realiza��o de Est�gio', 'Termo de Realiza��o de Est�gio','rec2_termoestagio001.php',1,1,'','t');
        INSERT INTO db_menu VALUES (30,(SELECT max(id_item) FROM db_itensmenu),(SELECT max(menusequencia)+1 FROM db_menu WHERE id_item = 30 AND modulo = 2323),2323);
        ";
        $this->execute($sql);
    }

    private function dropMenu()
    {
        $sql = "
        DELETE FROM db_menu WHERE id_item = (SELECT id_item FROM db_itensmenu WHERE descricao = 'Est�gio');
        DELETE FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao = 'Est�gio');
        DELETE FROM db_itensmenu WHERE funcao = 'rec1_rhestagiocurricular001.php';
        DELETE FROM db_itensmenu WHERE funcao = 'rec1_rhestagiocurricular002.php';
        DELETE FROM db_itensmenu WHERE funcao = 'rec1_rhestagiocurricular003.php';
        DELETE FROM db_itensmenu WHERE descricao = 'Est�gio';

        DELETE FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao = 'Termo de Realiza��o de Est�gio');
        DELETE FROM db_itensmenu WHERE descricao = 'Termo de Realiza��o de Est�gio';
        ";
        $this->execute($sql);
    }

    private function insertDicionarioDados() 
    {
        $sql = <<<SQL
        -- INSERINDO db_sysarquivo
        INSERT INTO configuracoes.db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'rhestagiocurricular', 'Estagios Curriculares', 'h83', '2020-10-08', 'Estagios Curriculares', 0, false, false, false, false);
         
        -- INSERINDO db_sysarqmod
        INSERT INTO configuracoes.db_sysarqmod (codmod, codarq) VALUES (29, (select max(codarq) from db_sysarquivo));
         
        -- INSERINDO db_syscampo
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_sequencial', 'int8', 'C�digo Sequencial', '0', 'C�digo Sequencial', 11, false, false, false, 1, 'text', 'C�digo Sequencial');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_regist', 'int8', 'Matr�cula do Estagi�rio', '0', 'Matr�cula do Estagi�rio', 11, false, false, false, 1, 'text', 'Matr�cula do Estagi�rio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_cnpjinstensino', 'varchar(14)', 'CNPJ Institui��o de Ensino', '', 'CNPJ Institui��o de Ensino', 14, false, true, false, 0, 'text', 'CNPJ Institui��o de Ensino');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_instensino', 'varchar(200)', 'Institui��o de Ensino', '', 'Institui��o de Ensino', 200, false, true, false, 0, 'text', 'Institui��o de Ensino');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_curso', 'varchar(200)', 'Curso', '', 'Curso', 200, false, true, false, 0, 'text', 'Curso');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_matricula', 'int8', 'Matr�cula na Institui��o de Ensino', '0', 'Matr�cula na Institui��o de Ensino', 11, false, false, false, 1, 'text', 'Matr�cula na Institui��o de Ensino');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_dtinicio', 'date', 'Data de In�cio do Est�gio', 'null', 'Data de In�cio do Est�gio', 10, false, false, false, 1, 'text', 'Data de In�cio do Est�gio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_dtfim', 'date', 'Data Final do Est�gio', 'null', 'Data Final do Est�gio', 10, false, false, false, 1, 'text', 'Data Final do Est�gio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_cargahorariatotal', 'int8', 'Carga Hor�ria Total do Estagio', '0', 'Carga Hor�ria Total do Estagio', 11, false, false, false, 1, 'text', 'Carga Hor�ria Total do Estagio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_supervisor', 'int8', 'Supervisor de Est�gio', '0', 'Supervisor de Est�gio', 11, false, false, false, 1, 'text', 'Supervisor de Est�gio');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'h83_instit', 'int8', 'Institui��o', '0', 'Institui��o', 11, false, false, false, 1, 'text', 'Institui��o');
         
        -- INSERINDO db_syssequencia
        INSERT INTO configuracoes.db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES ((select max(codsequencia)+1 from db_syssequencia), 'rhestagiocurricular_h83_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
         
        -- INSERINDO db_sysarqcamp
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_sequencial'), 1, (select codsequencia from db_syssequencia where nomesequencia = 'rhestagiocurricular_h83_sequencial_seq'));
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_regist'), 2, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_cnpjinstensino'), 3, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_instensino'), 4, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_curso'), 5, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_matricula'), 6, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_dtinicio'), 7, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_dtfim'), 8, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_cargahorariatotal'), 9, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_supervisor'), 10, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_instit'), 11, 0);
         
        -- INSERINDO db_sysforkey
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_regist'), 1, 1153, 0);
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_supervisor'), 1, 1153, 0);
         
        -- INSERINDO db_sysprikey
        INSERT INTO configuracoes.db_sysprikey (codarq, codcam, sequen, referen, camiden) VALUES ((select codarq from db_sysarquivo where nomearq = 'rhestagiocurricular'), (select codcam from db_syscampo where nomecam = 'h83_sequencial'), 1, (select codcam from db_syscampo where nomecam = 'h83_sequencial'), 0);

SQL;
        $this->execute($sql);
    }
}
