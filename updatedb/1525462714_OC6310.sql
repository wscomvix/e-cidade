
-- Ocorr�ncia 6310
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

INSERT INTO configuracoes.db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) VALUES
((select max(id_item)+1 as id_item from db_itensmenu), 'Exclus�o', 'Exclus�o de Veicabast', 'vei1_veicabast003.php', 1, '1', 'Altera��o de Veicabast', true);
INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5430 ,(select max(id_item) as id_item from db_itensmenu) ,4 ,633 );

-- Fim do script

COMMIT;

