-- Ocorr�ncia 5057
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script
INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Manuten��o por Periodo','Manuten��o por Periodo','vei2_veicmanut001.php','1','1','Manuten��o por Periodo');

INSERT INTO db_menu(id_item,id_item_filho,menusequencia,modulo) values (5336,(select max(id_item) from db_itensmenu),23,633);

-- Fim do script

COMMIT;

