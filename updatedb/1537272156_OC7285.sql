
-- Ocorr�ncia 7285
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

UPDATE db_itensmenu SET descricao='Liberar Empenhos para OC e Liquida��o', help = 'Liberar Empenhos para OC e Liquida��o' WHERE id_item = 8004;

-- Fim do script

COMMIT;

