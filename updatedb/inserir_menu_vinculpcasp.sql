begin;
select fc_startsession();
insert into db_itensmenu values (3000184,'V�nculo Pcasp TCE','V�nculo Pcasp TCE','',1,1,'V�nculo Pcasp TCE      ','t');
insert into db_itensmenu values (3000185,'Inclus�o','Inculir V�nculo Pcasp TCE','con1_vinculopcasptce001.php',1,1,'Incluir V�nculo Pcasp TCE','t');
insert into db_itensmenu values (3000186,'Altera��o','Alterar V�nculo Pcasp TCE','con1_vinculopcasptce002.php',1,1,'Alterar V�nculo Pcasp TCE','t');
insert into db_itensmenu values (3000187,'Exclus�o','Excluir V�nculo Pcasp TCE','con1_vinculopcasptce003.php',1,1,'Excluir V�nculo Pcasp TCE','t');

insert into db_menu values (3962,3000184,34,2000018);
insert into db_menu values (3000184,3000185,1,2000018);
insert into db_menu values (3000184,3000186,2,2000018);
insert into db_menu values (3000184,3000187,3,2000018);
commit;