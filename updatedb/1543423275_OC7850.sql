
-- Ocorr�ncia 78509
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table acordo alter column ac16_numeroacordo type varchar(14);

-- Fim do script

COMMIT;

