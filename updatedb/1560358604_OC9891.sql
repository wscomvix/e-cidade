-- Ocorr�ncia 9891
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

UPDATE db_syscampo SET tamanho = 200 WHERE nomecam='e990_descricao';

-- Fim do script

COMMIT;

