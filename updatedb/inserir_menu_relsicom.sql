begin;
select fc_startsession();
insert into db_itensmenu values (3000259,'Relat�rios Gerais','Relat�rios Gerais','con4_relatoriossicom.php',1,1,'Relat�rios Gerais','t');
insert into db_menu values (30,3000259,1,2000018);
commit;
