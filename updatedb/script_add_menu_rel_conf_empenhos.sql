begin;
select fc_startsession();
insert into db_itensmenu values (3000263,'Confer�ncia de Empenhos','Confer�ncia de Empenhos','con4_conferenciaempenhos001.php',1,1,'Confer�ncia de Empenhos','t');
insert into db_menu values (30,3000263,3,2000018);
commit;