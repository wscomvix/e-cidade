BEGIN;
SELECT fc_startsession();
UPDATE db_itensmenu  SET libcliente = 'f' WHERE descricao = 'Autentica��o de Recebimentos';
COMMIT;