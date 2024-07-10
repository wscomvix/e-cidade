----------------------------
--- Time C - INICIO
----------------------------

-- 82709

insert into db_sysarquivo values (3760, 'regracalculo', 'Define qual � a regra para c�lculo do resultado do aluno.', 'ed126', '2014-12-02', 'Regra C�lculo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1008004,3760);
insert into db_syscampo values(20890,'ed126_codigo','int4','C�digo sequ�ncial','0', 'C�digo',10,'f','f','f',1,'text','C�digo');
insert into db_syscampo values(20891,'ed126_descricao','varchar(100)','Descri��o da regra','', 'Descri��o',100,'f','t','f',0,'text','Descri��o');
insert into db_sysarqcamp values(3760,20890,1,0);
insert into db_sysarqcamp values(3760,20891,2,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3760,20890,1,20891);
insert into db_syssequencia values(1000423, 'regracalculo_ed126_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000423 where codarq = 3760 and codcam = 20890;

insert into db_sysarquivo values (3759, 'diarioregracalculo', 'Define qual a regra de c�lculo que deve ser aplicado a determinado per�odo do Di�rio.', 'ed125', '2014-12-02', 'Diario Regra C�lculo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1008004,3759);
insert into db_syscampo values(20892,'ed125_codigo','int4','C�digo sequ�ncial','0', 'C�digo',10,'f','f','f',1,'text','C�digo');
insert into db_syscampo values(20893,'ed125_ordemperiodo','int4','Ordem do Per�odo que ser� efetuada a regra de c�lculo','0', 'Ordem do Per�odo',10,'f','f','f',1,'text','Ordem do Per�odo');
insert into db_syscampo values(20894,'ed125_diario','int4','C�digo do Di�rio','0', 'C�digo do Di�rio',10,'f','f','f',1,'text','C�digo do Di�rio');
insert into db_syscampo values(20895,'ed125_regracalculo','int4','Regra de c�lculo','0', 'Regra de C�lculo',10,'f','f','f',1,'text','Regra de C�lculo');
insert into db_sysarqcamp values(3759,20892,1,0);
insert into db_sysarqcamp values(3759,20893,2,0);
insert into db_sysarqcamp values(3759,20894,3,0);
insert into db_sysarqcamp values(3759,20895,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3759,20892,1,20892);
insert into db_sysforkey values(3759,20895,1,3760,0);
insert into db_sysforkey values(3759,20894,1,1010118,0);
insert into db_sysindices values(4141,'diarioregracalculo_diario_in',3759,'0');
insert into db_syscadind values(4141,20894,1);
insert into db_sysindices values(4142,'diarioregracalculo_regracalculo_in',3759,'0');
insert into db_syscadind values(4142,20895,1);
insert into db_syssequencia values(1000422, 'diarioregracalculo_ed125_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000422 where codarq = 3759 and codcam = 20892;

insert into db_tipodoc values (5017, 'PROPORCIONALIDADE ENSINO FUNDAMENTAL');
insert into db_tipodoc values (5018, 'PROPORCIONALIDADE ENSINO EJA');
insert into db_documentopadrao( db60_coddoc ,db60_descr ,db60_tipodoc ,db60_instit ) values ( 229 ,'ENSINO FUNDAMENTAL' ,5017 ,1 );
insert into db_paragrafopadrao( db61_codparag ,db61_descr ,db61_texto ,db61_alinha ,db61_inicia ,db61_espaco ,db61_alinhamento ,db61_altura ,db61_largura ,db61_tipo )
values ( 524 ,'PAR�GRAFO' ,'Nota obtida com proporcionalidade.' ,0 ,0 ,1 ,'J' ,0 ,0 ,1 );
insert into db_docparagpadrao( db62_coddoc ,db62_codparag ,db62_ordem ) values ( 229 ,524 ,1 );
insert into db_documentopadrao( db60_coddoc ,db60_descr ,db60_tipodoc ,db60_instit ) values ( 230 ,'ENSINO EJA' ,5018 ,1 );
insert into db_paragrafopadrao( db61_codparag ,db61_descr ,db61_texto ,db61_alinha ,db61_inicia ,db61_espaco ,db61_alinhamento ,db61_altura ,db61_largura ,db61_tipo )
values ( 525 ,'PAR�GRAFO' ,'Nota obtida com proporcionalidade.' ,0 ,0 ,1 ,'J' ,0 ,0 ,1 );
insert into db_docparagpadrao( db62_coddoc ,db62_codparag ,db62_ordem ) values ( 230 ,525 , 1);


insert into db_syscampo values(20898,'ed41_julgamenoravaliacao','bool','Controla se a avalia��o informada, substituir� a menor nota do resultado o qual o procedimento est� vinculado.','false', 'Julgar Menor Avalia��o',1,'f','f','f',5,'text','Julgar Menor Avalia��o');
delete from db_sysarqcamp where codarq = 1010078;
insert into db_sysarqcamp values(1010078,1008450,1,1000140);
insert into db_sysarqcamp values(1010078,1008451,2,0);
insert into db_sysarqcamp values(1010078,1008452,3,0);
insert into db_sysarqcamp values(1010078,1008453,4,0);
insert into db_sysarqcamp values(1010078,1008454,5,0);
insert into db_sysarqcamp values(1010078,1008455,6,0);
insert into db_sysarqcamp values(1010078,1008456,7,0);
insert into db_sysarqcamp values(1010078,1008457,8,0);
insert into db_sysarqcamp values(1010078,20418,9,0);
insert into db_sysarqcamp values(1010078,20898,10,0);

