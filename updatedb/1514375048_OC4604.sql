
-- Ocorr�ncia 4604
BEGIN;
SELECT fc_startsession();

-- In�cio do script

ALTER TABLE lqd102017 DROP COLUMN si118_dtsentenca;
ALTER TABLE alq102017 DROP COLUMN si121_dtsentenca;

-- Fim do script

COMMIT;

