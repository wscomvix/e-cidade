
-- Ocorr�ncia ide_sicomfolha
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table ide2018 alter column si11_codorgao type varchar(3);

-- Fim do script

COMMIT;