BEGIN;
select fc_startsession();
update db_itensmenu set descricao = 'Manuten��o Estrutural PCASP', help = 'Manuten��o Estrutural PCASP', desctec = 'Manuten��o Estrutural PCASP'  where id_item = 9994;
COMMIT;