
-- Ocorr�ncia 5649
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table tpcontra add column h13_dscapo varchar(3);

-- Fim do script

COMMIT;

