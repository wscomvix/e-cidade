
-- Ocorr�ncia 7971
BEGIN;
SELECT fc_startsession();

-- In�cio do script

INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Desconto Externo de Previd�ncia','Desconto Externo de Previd�ncia','pes2_rhinssoutros001.php',1,1,'Desconto Externo de Previd�ncia','t');
INSERT INTO db_menu values (1797,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 1797 and modulo = 952),952);

-- Fim do script

COMMIT;
