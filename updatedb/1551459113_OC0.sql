
-- Ocorr�ncia 0
BEGIN;
SELECT fc_startsession();

-- In�cio do script

INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Manuten��o Saldo Conta Corrente','Manuten��o Saldo Conta Corrente','con4_manutsaldocontacorrente001.php',1,1,'Manuten��o Saldo Conta Corrente','t');
INSERT INTO db_menu values (9680,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 9680 and modulo = 209),209);


-- Fim do script

COMMIT;

