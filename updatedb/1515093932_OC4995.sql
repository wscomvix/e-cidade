
-- Ocorr�ncia 4995
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table pcfornecertifdoc alter column pc75_numdocumento type varchar(40);

-- Fim do script

COMMIT;

