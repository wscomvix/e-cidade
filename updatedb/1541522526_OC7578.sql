
-- Ocorr�ncia 7578
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script
INSERT INTO db_itensmenu VALUES((select max(id_item)+1 FROM db_itensmenu), 'Solicita��o de Compras','Solicita��o de Compras','com2_emitesolicitacao001.php',1,1,'Solicita��o de Compras','t');
INSERT INTO db_menu VALUES(9153,(SELECT max(id_item) FROM db_itensmenu),10,28);

-- Fim do script

COMMIT;

