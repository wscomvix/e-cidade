<?php

require_once("classes/db_acordoposicao_classe.php");

class ValidaAlteracaoApostilamentoCommand
{
    public function execute($iAcordo)
    {
        $cl_acordoposicao = new cl_acordoposicao;
        $sql = $cl_acordoposicao->sqlAPosicaoApostilamentoEmpenho($iAcordo);

        $cl_acordoposicao->sql_record($sql);

        if ($cl_acordoposicao->numrows != 0) {
            throw new Exception("A altera��o n�o poder� ser efetuada, o apostilamento possui autoriza��o(�es) e/ou empenho(s) vinculado(s).");
        }
    }
}
