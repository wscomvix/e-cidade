
-- Ocorr�ncia acertactb
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

CREATE TABLE IF NOT EXISTS acertactb (
  si95_reduz int8 NOT NULL default 0,
  si95_codtceant int8 NOT NULL default 0,
  CONSTRAINT acertactb_sequ_pk PRIMARY KEY (si95_reduz) );

-- Fim do script

COMMIT;

