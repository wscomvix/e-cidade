begin;
select fc_startsession();
insert into db_itensmenu values (3000247,'Alterar Dota��o RP','Alterar Dota��o RP','',1,1,'Alterar Dota��o RP','t');
insert into db_itensmenu values (3000248,'Inclus�o','Inculir Alterar Dota��o RP','sic1_dotacaorpsicom001.php',1,1,'Incluir Alterar Dota��o RP','t');
insert into db_itensmenu values (3000249,'Altera��o','Alterar Alterar Dota��o RP','sic1_dotacaorpsicom002.php',1,1,'Alterar Alterar Dota��o RP','t');
insert into db_itensmenu values (3000250,'Exclus�o','Excluir Alterar Dota��o RP','sic1_dotacaorpsicom003.php',1,1,'Excluir Alterar Dota��o RP','t');

insert into db_menu values (3962,3000247,35,2000018);
insert into db_menu values (3000247,3000248,1,2000018);
insert into db_menu values (3000247,3000249,2,2000018);
insert into db_menu values (3000247,3000250,3,2000018);
commit;

