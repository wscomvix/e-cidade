
-- Ocorr�ncia 9233
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script
UPDATE db_syscampo SET rotulo = 'N� Processo',rotulorel='N� Processo' WHERE codcam = (select codcam from db_syscampo where nomecam like '%e54_numerl%');


-- Fim do script

COMMIT;

