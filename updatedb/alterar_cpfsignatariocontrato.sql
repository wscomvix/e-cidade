begin;
select fc_startsession();
update db_syscampo set rotulo='CPF Resp do Contrato no Org�o' ,descricao='CPF Resp do Contrato no Org�o' ,rotulorel='CPF Resp do Contrato no Org�o' where nomecam = 'si172_cpfsignatariocontratante';
commit;
