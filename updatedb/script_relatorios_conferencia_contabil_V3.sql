select fc_startsession();
begin;
insert into orcparamrel( o42_codparrel ,o42_orcparamrelgrupo ,o42_descrrel ,o42_notapadrao ) values (4000003,4 ,'ANEXO IV - GASTO COM PESSOAL' ,'ANEXO IV - GASTO COM PESSOAL' );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,17 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,18 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,19 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,20 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,21 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,22 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,23 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,24 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,25 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,26 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,27 ,4000003 );
insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel ) values ( nextval('orcparamrelperiodos_o113_sequencial_seq')+4000000 ,28 ,4000003 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 4000003 ,1 ,'RECEITA CORRENTE DO MUNIC�PIO' ,0 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'RECEITA CORRENTE DO MUNIC�PIO' ,'t' ,'f' ,1,1 ,'' ,'f' ,0 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 4000003 ,2 ,'SENTEN�AS JUDICIAIS ANTERIORES' ,1 ,0 ,1 ,'f' ,'f' ,'f' ,'f' ,'f' ,'Senten�as Judiciais Anteriores' ,'t' ,'f' ,2 ,1 ,'Senten�as Judiciais Anteriores' ,'f' ,0 );

insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Relat�rio di�rio das Despesas','Relat�rio di�rio das Despesas','emp2_empenhospagos001.php',1,1,'Relat�rio di�rio das Despesas','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Transfer�ncias Banc�rias','Transfer�ncias Banc�rias','con2_transfbanc001.php',1,1,'Transfer�ncias Banc�rias','t');
insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'DEMONST D�VIDAS FUNDADAS','DEMONST D�VIDAS FUNDADAS','con2_ddc001.php',1,1,'DEMONST D�VIDAS FUNDADAS','t');
insert into db_menu values (4000292,(select max(id_item)-2 from db_itensmenu),12,209);
insert into db_menu values (4000292,(select max(id_item)-1 from db_itensmenu),12,209);
insert into db_menu values (4000292,(select max(id_item) from db_itensmenu),12,209);

commit;