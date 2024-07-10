<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13181 extends PostgresMigration
{

    public function up()
    {
        $table = $this->table('relempenhospatronais', array('schema' => 'pessoal', 'id' => false, 'primary_key' => array('rh170_sequencial')));
        $table->addColumn('rh170_sequencial', 'integer', array('identity' => true))
              ->addColumn('rh170_tipo', 'string', array('limit' => 100))
              ->addColumn('rh170_rubric', 'string', array('limit' => 4))
              ->addColumn('rh170_instit', 'integer')
              ->addColumn('rh170_usuario', 'integer')
              ->addForeignKey(array('rh170_rubric', 'rh170_instit'), 'pessoal.rhrubricas', array('rh27_rubric', 'rh27_instit'),array('constraint' => 'relempenhospatronais_rubric_instit_fk'))
              ->addForeignKey('rh170_usuario', 'db_usuarios', 'id_usuario', array('constraint' => 'relempenhospatronais_usuario_fk'))
              ->save();
        $this->createSequence();
        if ($this->checkDicionarioDados()) {
            $this->insertDicionarioDados();
        }

    }

    public function down() 
    {
        $this->table('relempenhospatronais', array('schema' => 'pessoal'))->drop();
        $this->dropSequence();
    }

    private function createSequence() 
    {
        $this->execute("CREATE SEQUENCE relempenhospatronais_rh170_sequencial_seq");
    }

    private function dropSequence() {
        $this->execute("DROP SEQUENCE relempenhospatronais_rh170_sequencial_seq");
    }

    private function checkDicionarioDados()
    {
        $result = $this->fetchRow("SELECT * FROM db_sysarquivo WHERE nomearq = 'relempenhospatronais'");
        if (empty($result)) {
            return true;
        }
        return false;
    }

    private function insertDicionarioDados() 
    {
        $sql = <<<SQL
        -- INSERINDO db_sysarquivo
        INSERT INTO configuracoes.db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'relempenhospatronais', 'Cadastro Tipo/R�bricas para o relat�rio de Obriga��es Patronais', 'rh170', '2020-09-04', 'Cadastro Tipo/R�bricas Obriga��es Patronais', 0, false, false, false, false);
         
        -- INSERINDO db_sysarqmod
        INSERT INTO configuracoes.db_sysarqmod (codmod, codarq) VALUES (28, (select max(codarq) from db_sysarquivo));
         
        -- INSERINDO db_syscampo
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'rh170_sequencial', 'int8', 'C�digo Sequencial', '0', 'C�digo Sequencial', 11, false, false, false, 1, 'text', 'C�digo Sequencial');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'rh170_tipo', 'varchar(100)', 'Tipo de r�brica', '', 'Tipo de r�brica', 100, false, true, false, 0, 'text', 'Tipo de r�brica');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'rh170_rubric', 'varchar(4)', 'R�brica', '', 'R�brica', 4, false, true, false, 0, 'text', 'R�brica');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'rh170_instit', 'int8', 'Institui��o', '0', 'Institui��o', 11, false, false, false, 1, 'text', 'Institui��o');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'rh170_usuario', 'int8', 'Usu�rio', '0', 'Usu�rio', 11, false, false, false, 1, 'text', 'Usu�rio');
         
        -- INSERINDO db_syssequencia
        INSERT INTO configuracoes.db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES ((select max(codsequencia)+1 from db_syssequencia), 'relempenhospatronais_rh170_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
         
        -- INSERINDO db_sysarqcamp
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_sequencial'), 1, (select codsequencia from db_syssequencia where nomesequencia = 'relempenhospatronais_rh170_sequencial_seq'));
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_tipo'), 2, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_rubric'), 3, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_instit'), 4, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_usuario'), 5, 0);
         
        -- INSERINDO db_sysforkey
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_rubric'), 1, 1177, 0);
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_instit'), 2, 1177, 0);
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_usuario'), 1, 109, 0);
         
        -- INSERINDO db_sysprikey
        INSERT INTO configuracoes.db_sysprikey (codarq, codcam, sequen, referen, camiden) VALUES ((select codarq from db_sysarquivo where nomearq = 'relempenhospatronais'), (select codcam from db_syscampo where nomecam = 'rh170_sequencial'), 1, (select codcam from db_syscampo where nomecam = 'rh170_sequencial'), 0);
SQL;
        $this->execute($sql);
    }
}
