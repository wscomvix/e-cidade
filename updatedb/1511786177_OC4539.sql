
-- Ocorr�ncia 4539
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

ALTER TABLE ordembancariapagamento ADD COLUMN k00_ordemauxiliar bigint;

-- Fim do script

COMMIT;

