select fc_startsession();
begin;
select setval('orcparamrel_o42_codparrel_seq',4000001);

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Relat�rios de Acompanhamento','Relat�rios de Acompanhamento','',1,1,'Relat�rios de Acompanhamento','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'ANEXO IV - Gastos com Pessoal','ANEXO IV - Gastos com Pessoal','con2_anexoIVgastopessoal001.php',1,1,'ANEXO IV - Gastos com Pessoal','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'ANEXO III (FUNDEB) ','ANEXO III (FUNDEB)','con2_anexoIIIfundeb001.php',1,1,'ANEXO III (FUNDEB)','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'ANEXO I Educa��o','ANEXO I Educa��o','con2_anexoIeduc001.php',1,1,'ANEXO I Educa��o','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'ANEXO II Educa��o','ANEXO II Educa��o','con2_anexoIIeduc001.php',1,1,'ANEXO II Educa��o','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'ANEXO I SA�DE','ANEXO I SA�DE','con2_anexoIsaude001.php',1,1,'ANEXO I SA�DE','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'ANEXO II SA�DE','ANEXO II SA�DE','con2_anexoIIsaude001.php',1,1,'ANEXO II SA�DE','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'DEPOSITOS DECENDIAIS EDUCA��O & SA�DE','DEPOSITOS DECENDIAIS EDUCA��O & SA�DE','con2_depositosdecendiais001.php',1,1,'DEPOSITOS DECENDIAIS EDUCA��O & SA�DE','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'CALCULO PASEP','CALCULO PASEP','con2_calculopasep001.php',1,1,'CALCULO PASEP','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'REPASSE CAMARA','REPASSE CAMARA','con2_repassecamara001.php',1,1,'REPASSE CAMARA','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'DEMO DE MOV BANC�RIO','DEMO DE MOV BANC�RIO','con2_dmn001.php',1,1,'DEMO DE MOV BANC�RIO','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Relat�rio di�rio das Receitas','Relat�rio di�rio das Receitas','cai2_correceitas001.php',1,1,'Relat�rio di�rio das Receitas','t');

insert into db_menu values (3331,(select max(id_item)-11 from db_itensmenu),51,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-10 from db_itensmenu),1,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-9 from db_itensmenu),2,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-8 from db_itensmenu),3,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-7 from db_itensmenu),4,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-6 from db_itensmenu),5,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-5 from db_itensmenu),6,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-4 from db_itensmenu),7,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-3 from db_itensmenu),8,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-2 from db_itensmenu),9,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item)-1 from db_itensmenu),10,209);
insert into db_menu values ((select max(id_item)-11 from db_itensmenu),(select max(id_item) from db_itensmenu),11,209);

alter table db_config add column db21_habitantes int default 0;
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'db21_habitantes', 'int8', 'Habitantes', '0', 'Habitantes', 8, false, false, false, 1, 'text', 'Habitantes');
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (83, (select codcam from db_syscampo where nomecam = 'db21_habitantes'), (select max(seqarq)+1 from db_sysarqcamp where codarq = 83), 0);
commit;