begin;
select fc_startsession();
update db_itensmenu set descricao = 'M�dia de Consumo de Materiais',help = 'M�dia de Consumo de Materiais',desctec = 'M�dia de Consumo de Materiais' where  id_item=3000099;
commit;
