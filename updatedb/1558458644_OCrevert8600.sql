
-- Ocorr�ncia revert8600
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table cgm drop column z01_ibge;

-- Fim do script

COMMIT;

