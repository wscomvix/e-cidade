begin;
insert into db_itensmenu(id_item,descricao,help,funcao,itemativo,manutencao,desctec) value (4000323,'Manuten��o de ve�culos','Manuten��o de ve�culos','vei2_manutveic001.php','1','1','Manuten��o de ve�culos');
insert into db_menu(id_item,id_item_filho,menusequencia,modulo) value (5336,4000323,22,633);
commit;