begin;
select fc_startsession();
insert into db_itensmenu values (3000095,'Saldo da Licita��o','Saldo da Licita��o','lic2_saldolicitacao001.php',1,1,'Saldo da Licita��o','t');
insert into db_menu values (1797,3000095,100,381);

insert into db_itensmenu values (3000096,'Vencedores da Licita��o','Vencedores da Licita��o','lic2_venclicitacao001.php',1,1,'Vencedores da Licita��o','t');
insert into db_menu values (1797,3000096,101,381);

insert into db_itensmenu values (3000097,'Empenhos por Licita��o','Empenhos por Licita��o','lic2_emplicitacao001.php',1,1,'Empenhos por Licita��o','t');
insert into db_menu values (1797,3000097,102,381);
commit;
