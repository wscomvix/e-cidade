
-- Ocorr�ncia 6425
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script
ALTER TABLE orgao102018 ALTER COLUMN si14_nrodocumentoassessoria TYPE VARCHAR(14);


-- Fim do script

COMMIT;

