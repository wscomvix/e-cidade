
-- Ocorr�ncia 5462
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table rhfuncao add column rh37_atividadedocargo varchar(150);

-- Fim do script

COMMIT;

