begin;
select fc_startsession();
update db_syscampo set descricao = 'Sequencial da Licita��o', rotulo  = 'Sequencial da Licita�ao', rotulorel  = 'Sequencial da Licita��o' where nomecam = 'l202_licitacao';
commit;
