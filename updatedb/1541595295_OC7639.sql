
-- Ocorr�ncia 7639
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

ALTER TABLE conplanoconplanoorcamento
ALTER COLUMN c72_conplano
DROP NOT NULL;


ALTER TABLE conplanoconplanoorcamento
ALTER COLUMN c72_conplano
DROP DEFAULT;

-- Fim do script

COMMIT;

