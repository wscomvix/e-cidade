BEGIN;
SELECT fc_startsession();
-- Ocorr�ncia

-- Alter table
ALTER TABLE empempenho ADD COLUMN e60_tipodespesa int8;

COMMIT;
