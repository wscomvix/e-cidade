begin;
select fc_startsession();
insert into db_itensmenu values (3000114,'Movimenta��o de Notas','Movimenta��o de Notas','emp2_movimentacaonotas001.php',1,1,'Movimenta��o de Notas','t');
insert into db_menu values (30,3000114,443,398);
commit;
