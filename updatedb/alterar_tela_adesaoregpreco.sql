begin;
select fc_startsession();
update db_syscampo set descricao = 'N�mero do Processo de Ades�o',rotulo = 'N�mero do Processo de Ades�o',rotulorel = 'N�mero do Processo de Ades�o'
where nomecam = 'si06_numeroadm';
update db_syscampo set descricao = 'N�mero Processo Licitat�rio',rotulo = 'N�mero Processo Licitat�rio',rotulorel = 'N�mero Processo Licitat�rio'
where nomecam = 'si06_numeroprc';
update db_syscampo set descricao = 'Fornecedor Ganhador',rotulo = 'Fornecedor Ganhador',rotulorel = 'Fornecedor Ganhador'
where nomecam = 'si06_fornecedor';
update db_syscampo set descricao = 'N�mero Modalidade',rotulo = 'N�mero Modalidade',rotulorel = 'N�mero Modalidade'
where nomecam = 'si06_numlicitacao';
update db_syscampo set descricao = 'Pre�o/Desc. Unit',rotulo = 'Pre�o/Desc. Unit',rotulorel = 'Pre�o/Desc. Unit'
where nomecam = 'si07_precounitario';


alter table adesaoregprecos add column si06_processoporlote int8;
alter table adesaoregprecos add column si06_instit int8;
alter table adesaoregprecos alter column si06_tipodocumento drop not null;
alter table adesaoregprecos alter column si06_tipodocumento drop default;
alter table adesaoregprecos alter column si06_numerodocumento drop default;
alter table adesaoregprecos alter column si06_fornecedor drop not null;
alter table adesaoregprecos alter column si06_fornecedor drop default;

alter table itensregpreco add column si07_descricaolote varchar(250);
alter table itensregpreco add column si07_fornecedor int8;

alter table itensregpreco add constraint itensregpreco_fornecedor_fk FOREIGN KEY (si07_fornecedor) REFERENCES cgm(z01_numcgm);
delete from itensregpreco where si07_sequencialadesao not in (select si06_sequencial from adesaoregprecos);
alter table itensregpreco add constraint itensregpreco_sequencialadesao_fk FOREIGN KEY (si07_sequencialadesao) REFERENCES adesaoregprecos(si06_sequencial);

alter table itensregpreco add column si07_codunidade int8;
alter table itensregpreco add constraint itensregpreco_codunidade_fk FOREIGN KEY (si07_codunidade) REFERENCES matunid(m61_codmatunid);
alter table itensregpreco alter column si07_descricaoitem drop not null;
alter table itensregpreco alter column si07_numeroitem drop not null;
alter table itensregpreco alter column si07_unidade drop not null;

/*ALTERAR INSTITUICOES NULAS*/
update adesaoregprecos set si06_instit = 1 where si06_instit is null;

/*ALTERAR LOTES NULOS*/
update adesaoregprecos set si06_processoporlote = 1 where si06_sequencial in (select si07_sequencialadesao from itensregpreco where si07_numerolote is not null and si07_numerolote != 0);
update adesaoregprecos set si06_processoporlote = 2 where si06_sequencial in (select si07_sequencialadesao from itensregpreco where si07_numerolote is null or si07_numerolote = 0);
commit;
