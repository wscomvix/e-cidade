<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21396 extends PostgresMigration
{
    public function up()
    {
        //Cria a tabela issconfiguracaogruposervicousuario, para inclus�o dos cgm para permiss�o de altera��o da descri��o da atividade
        $this->execute("CREATE TABLE IF NOT EXISTS issqn.issconfiguracaogruposervicousuario (id_usuario int8 NOT NULL,
                        CONSTRAINT issconfiguracaogruposervicousuario_id_usuario_pk PRIMARY KEY (id_usuario));");
    }
}
