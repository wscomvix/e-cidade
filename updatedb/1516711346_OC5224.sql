
-- Ocorr�ncia 5224
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

ALTER TABLE metareal102017 ADD COLUMN si171_mes bigint;
ALTER TABLE metareal102017 ALTER COLUMN si171_mes SET NOT NULL;
ALTER TABLE metareal102017 ALTER COLUMN si171_mes SET DEFAULT 0;

-- Fim do script

COMMIT;

