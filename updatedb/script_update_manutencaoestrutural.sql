BEGIN;
select fc_startsession();
update db_itensmenu set descricao = 'Manuten��o Estrutural PCASP' where id_item = 9994;

COMMIT;