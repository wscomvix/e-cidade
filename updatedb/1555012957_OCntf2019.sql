
-- Ocorr�ncia correçao ntf2019
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script
ALTER TABLE ntf102019 ALTER COLUMN si143_chaveacesso TYPE varchar(44);
ALTER TABLE ntf202019 ALTER COLUMN si145_chaveacesso TYPE varchar(44);
-- Fim do script

COMMIT;

