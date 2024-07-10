begin;
select fc_startsession();
/*Alterar label campo Acordo*/
update db_syscampo set rotulo = rotulorel where nomecam='ac16_sequencial';

/*Alterar label campo Contratado*/
update db_syscampo set rotulo = 'CGM Contratado' where nomecam='ac16_contratado';
update db_syscampo set descricao = 'CGM Contratado' where nomecam='ac16_contratado';
update db_syscampo set rotulorel = 'CGM Contratado' where nomecam='ac16_contratado';

/*Alterar label campo Resumo Objeto*/
update db_syscampo set rotulo = 'Objeto' where nomecam='ac16_resumoobjeto';
update db_syscampo set descricao = 'Objeto' where nomecam='ac16_resumoobjeto';
update db_syscampo set rotulorel = 'Objeto' where nomecam='ac16_resumoobjeto';

/*Alterar nome menu Assinatura do Contrato*/
update db_itensmenu set descricao = 'Assinatura do Acordo' where descricao = 'Assinatura do Contrato';
update db_itensmenu set help = 'Assinatura do Acordo' where help = 'Assinatura do Contrato';
update db_itensmenu set desctec = 'Assinatura do Acordo' where desctec = 'Assinatura do Contrato';

/*Alterar nome menu Rescis�o do Contrato*/
update db_itensmenu set descricao = 'Rescis�o do Acordo' where descricao like 'Rescis% do Contrato';
update db_itensmenu set help = 'Rescis�o do Acordo' where help like 'Rescis% do Contrato';
update db_itensmenu set desctec = 'Rescis�o do Acordo' where desctec like 'Rescis% do Contrato';

/*Alterar nome menu Rescis�o do Contrato*/
update db_itensmenu set descricao = 'Anula��o do Acordo' where descricao like 'Anula% do Contrato';
update db_itensmenu set help = 'Anula��o do Acordo' where help like 'Anula% do Contrato';
update db_itensmenu set desctec = 'Anula��o do Acordo' where desctec like 'Anula% do Contrato';

commit;