
-- Ocorr�ncia 4604
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

ALTER TABLE infocomplementaresinstit ADD COLUMN si09_assessoriacontabil bigint;
ALTER TABLE infocomplementaresinstit ADD COLUMN si09_cgmassessoriacontabil bigint;

-- Fim do script

COMMIT;

