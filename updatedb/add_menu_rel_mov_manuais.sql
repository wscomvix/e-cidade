begin;
select fc_startsession();
insert into db_itensmenu values (nextval('db_itensmenu_id_item_seq'),'Relat�rio de Movimenta��es Manuais','Relat�rio de Movimenta��es Manuais','mat2_relatoriomovimentacaomanual001.php',1,1,'','t');
insert into db_menu values (8787,(select max(id_item) as id_item from db_itensmenu),102,480);
commit;

