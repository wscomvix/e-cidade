<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11211 extends PostgresMigration
{
    public function up()
    {
        $sql= "
            ALTER TABLE liclicita ADD COLUMN l20_nroedital bigint;
            ALTER TABLE liclicita ADD COLUMN l20_cadinicial integer;
            ALTER TABLE liclicita ADD COLUMN l20_exercicioedital integer;
            
            UPDATE db_itensmenu SET descricao = 'Configura��o de Numera��o', help = 'Configura��o de Numera��o' WHERE id_item = 4689;

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l20_nroedital', 'bigint', 'Edital', '0', 'Edital', 10, false, false, false, 0, 'text', 'Edital');

            CREATE TABLE pccfeditalnum(
                l47_numero bigint DEFAULT 0,
                l47_anousu integer DEFAULT 0,
                l47_instit integer NOT NULL DEFAULT 0
            );

            ALTER TABLE pccfeditalnum ADD CONSTRAINT pccfeditalnum_instit_fk
            FOREIGN KEY(l47_instit) REFERENCES db_config(codigo);

            INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'pccfeditalnum', 'pccfeditalnum', 'l47', '2019-12-06', 'pccfeditalnum', 1, false, false, false, false);

            INSERT INTO db_sysarqmod (codmod, codarq) VALUES (19, (select max(codarq) from db_sysarquivo));
             
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l47_instit', 'int4', 'C�digo da instituicao', '0', 'Institui��o', 2, false, false, false, 0, 'text', 'C�digo da institui��o');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l47_anousu', 'int4', 'Ano', '0', 'Ano', 6, false, false, false, 1, 'text', 'Ano');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l47_numero', 'int8', 'Numera��o', '0', 'Numera��o', 8, false, false, false, 1, 'text', 'Numera��o');

            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l47_instit'), 1, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l47_anousu'), 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l47_numero'), 3, 0);
        
        ";
        $this->execute($sql);
    }

    public function down(){
        $sql= "
            ALTER TABLE liclicita DROP COLUMN l20_nroedital;
            ALTER TABLE liclicita DROP COLUMN l20_cadinicial;
            ALTER TABLE liclicita DROP COLUMN l20_exercicioedital;

            UPDATE db_itensmenu
            SET descricao = 'Configura��o de Processo Licitat�rio', help = 'Configura��o de Processo Licitat�rio'
            WHERE id_item = 4689;
            
            ALTER TABLE liclicita DROP COLUMN l20_numedital;
            
            DROP TABLE pccfeditalnum;
            
            DELETE FROM db_sysarqcamp where codcam in ((select codcam from db_syscampo where nomecam = 'l47_instit'),
            (select codcam from db_syscampo where nomecam = 'l47_anousu'),
            (select codcam from db_syscampo where nomecam = 'l47_numero'));
            
            DELETE FROM db_sysarqmod where codarq = (select codarq from db_sysarquivo where nomearq = 'pccfeditalnum');
            
            DELETE from db_sysarquivo where nomearq = 'pccfeditalnum';
            
            DELETE FROM db_syscampo where nomecam in ('l47_instit', 'l47_anousu', 'l47_numero');
        ";
        $this->execute($sql);
    }
}
