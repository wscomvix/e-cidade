begin;
select fc_startsession();

insert into db_itensmenu values (3000100,'Presta��o de Contas Anual - PCA','Presta��o de Contas Anual - PCA','con4_gerarpca.php',1,1,'','t');

insert into db_menu values (8987,3000100,5,2000018);

commit;
