BEGIN;
SELECT fc_startsession();

-- inser��o de campo
ALTER TABLE dividaconsolidada
  ADD COLUMN si167_subtipo VARCHAR(1) DEFAULT NULL;



COMMIT;
