select fc_startsession();
BEGIN;
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values (3000270,'Movimenta��o de Ve�culos' ,'Movimenta��o de Ve�culos' ,'vei2_movveiculos001.php' ,'1' ,'1' ,'' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5336 ,3000270 ,19 ,633 );
commit;