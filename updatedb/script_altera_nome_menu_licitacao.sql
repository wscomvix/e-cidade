select fc_startsession();
begin;
update db_itensmenu set descricao = 'Configura��o de Processo Licitat�rio', help = 'Configura��o de Processo Licitat�rio' where id_item = 4689;
update db_syscampo set rotulo = 'Processo Licitat�rio', rotulorel = 'Processo Licitat�rio' where nomecam = 'l20_edital';
commit;