
-- Ocorr�ncia 3770
BEGIN;
SELECT fc_startsession();

-- In�cio do script

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Tabela','Tabela','',1,1,'Tabela','t');
insert into db_menu values (29,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 29),28);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inculir Tabela','com1_pctabela001.php',1,1,'Incluir Tabela','t');
insert into db_menu values ((select max(id_item)-1 from db_itensmenu),(select max(id_item) from db_itensmenu),1,28);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Alterar Tabela','com1_pctabela002.php',1,1,'Alterar Tabela','t');
insert into db_menu values ((select max(id_item)-2 from db_itensmenu),(select max(id_item) from db_itensmenu),2,28);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Excluir Tabela','com1_pctabela003.php',1,1,'Excluir Tabela','t');
insert into db_menu values ((select max(id_item)-3 from db_itensmenu),(select max(id_item) from db_itensmenu),3,28);


insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Tabela/Taxa','Processo Crit�rio de Adjudica��o','',1,1,'Processo Crit�rio de Adjudica��o','t');
insert into db_menu values (2567,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 2567),28);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Incluir','Processo Crit�rio de Adjudica��o','emp1_empautorizataxatabela001.php',1,1,'Incluir Autoriza��o','t');
insert into db_menu values ((select max(id_item)-1 from db_itensmenu),(select max(id_item) from db_itensmenu),1,28);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Alterar','Processo Crit�rio de Adjudica��o','emp1_empautorizataxatabela002.php',1,1,'Alterar Autoriza��o','t');
insert into db_menu values ((select max(id_item)-2 from db_itensmenu),(select max(id_item) from db_itensmenu),2,28);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Excluir','Processo Crit�rio de Adjudica��o','emp1_empautorizataxatabela003.php',1,1,'Excluir Autoriza��o','t');
insert into db_menu values ((select max(id_item)-3 from db_itensmenu),(select max(id_item) from db_itensmenu),3,28);
-- Fim do script

COMMIT;



