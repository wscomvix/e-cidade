begin;
SELECT fc_startsession();
update db_itensmenu set libcliente = 'f' where id_item=8569;
update db_itensmenu set libcliente = 'f' where id_item=8573;
update db_itensmenu set libcliente = 'f' where id_item=8588;
update db_itensmenu set libcliente = 'f' where id_item=8589;
update db_itensmenu set descricao = 'Inclus�o', help = 'Inclus�o' where id_item = 4000307;

update acordoposicaotipo set ac27_descricao = 'Reajuste' where ac27_sequencial=5;
update acordoposicaotipo set ac27_descricao = 'Altera��o de Prazo de Vig�ncia' where ac27_sequencial=6;
commit;
