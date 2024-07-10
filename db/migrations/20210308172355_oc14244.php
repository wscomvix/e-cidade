<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14244 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
  
        BEGIN;
        SELECT fc_startsession();

        UPDATE orctiporec SET o15_descr = 'TRANSF. FUNDEB APLIC. MAGIST�RIO 70' WHERE o15_codigo = '118';

        UPDATE db_estruturavalor SET db121_descricao = 'TRANSF. FUNDEB APLIC. MAGIST�RIO 70' WHERE db121_estrutural = '118';

        UPDATE orctiporec SET o15_descr = 'TRANSF. FUNDEB APLIC. OUTRAS DESP. 30' WHERE o15_codigo = '119';

        UPDATE db_estruturavalor SET db121_descricao = 'TRANSF. FUNDEB APLIC. OUTRAS DESP. 30' WHERE db121_estrutural = '119';

        INSERT INTO db_estruturavalor
            VALUES (NEXTVAL('db_estruturavalor_db121_sequencial_seq'),
                5,
                '166',
                'TRANSF. DO FUNDEB COMP. DA UNIAO ? VAAT 70',
                0,
                1,
                1),
            (NEXTVAL('db_estruturavalor_db121_sequencial_seq'),
                5,
                '167',
                'TRANSF. DO FUNDEB COMP. DA UNIAO ? VAAT OUT. DESP 30',
                0,
                1,
                1);      

        INSERT INTO orctiporec
            VALUES (166,
                'TRANSF. DO FUNDEB COMP. DA UNIAO ? VAAT 70',
                '167',
                'Recursos de Complementa��o da Uni�o ao FUNDEB ? VAAT, com base no art. 212-A, inciso V, b, da Constitui��o Federal, destinados ao pagamento da remunera��o dos profissionais da educa��o b�sica em efetivo exerc�cio.',
                2,
                NULL,
                (SELECT max(db121_sequencial) FROM db_estruturavalor WHERE db121_estrutural = '166'),
                11180000),
                (167,
                'TRANSF. DO FUNDEB COMP. DA UNIAO ? VAAT OUT. DESP 30',
                '167',
                'Recursos de Complementa��o da Uni�o ao FUNDEB ? VAAT, com base no art. 212-A, inciso V, b, da Constitui��o Federal, destinados ao pagamento de outras despesas da educa��o b�sica.',
                2,
                NULL,
                (SELECT max(db121_sequencial) FROM db_estruturavalor WHERE db121_estrutural = '167'),
                11190000);     

        COMMIT;

SQL;
    $this->execute($sql);
  }

}