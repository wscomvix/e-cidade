begin;
select fc_startsession();

update db_itensmenu set descricao = 'Presta��o de Contas Anual - PCA' where id_item = 3000100;

commit;
