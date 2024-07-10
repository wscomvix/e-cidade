begin;
select fc_startsession();

INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'dotacaorpsicom', 'Alterar Dota��o RP Sicom', 'si177', '2016-01-11', 'Alterar Dota��o RP Sicom', 0, false, false, false, false);

INSERT INTO db_sysarqmod (codmod, codarq) VALUES (2008005, (select max(codarq) from db_sysarquivo));

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codsubfuncao', 'int8              ', 'C�digo da Subfun��o', '0', 'C�digo da Subfun��o', 3, false, false, false, 1, 'text', 'C�digo da Subfun��o');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codunidadesub', 'int8              ', 'C�digo Unidade/Subunidade', '0', 'C�digo Unidade/Subunidade', 8, false, false, false, 1, 'text', 'C�digo Unidade/Subunidade');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_numemp      ', 'int8              ', 'Sequencial do Empenho', '0', 'Sequencial do Empenho', 11, false, false, false, 1, 'text', 'Sequencial do Empenho');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codorgaotce ', 'int8              ', 'C�digo Org�o TCE', '0', 'C�digo Org�o TCE', 2, false, false, false, 1, 'text', 'C�digo Org�o TCE');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codprograma ', 'int8              ', 'C�digo do Programa', '0', 'C�digo do Programa', 4, false, false, false, 1, 'text', 'C�digo do Programa');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_subelemento ', 'int8              ', 'Subelemento da Despesa', '0', 'Subelemento da Despesa', 2, false, false, false, 1, 'text', 'Subelemento da Despesa');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codfuncao   ', 'int8              ', 'C�digo da Fun��o', '0', 'C�digo da Fun��o', 2, false, false, false, 1, 'text', 'C�digo da Fun��o');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codfontrecursos                   ', 'int8              ', 'Fonte de Recursos', '0', 'Fonte de Recursos', 3, false, false, false, 1, 'text', 'Fonte de Recursos');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_idacao      ', 'int8              ', 'C�digo da A��o', '0', 'C�digo da A��o', 4, false, false, false, 1, 'text', 'C�digo da A��o');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_codunidadesuborig                 ', 'int8              ', 'C�digo Unidade/Subunidade Original', '0', 'C�digo Unidade/Subunidade Original', 8, false, false, false, 1, 'text', 'C�digo Unidade/Subunidade Original');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_naturezadespesa                   ', 'int8              ', 'Natureza da Despesa', '0', 'Natureza da Despesa', 6, false, false, false, 1, 'text', 'Natureza da Despesa');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_idsubacao   ', 'int8              ', 'C�digo da suba��o', '0', 'C�digo da suba��o', 4, false, false, false, 1, 'text', 'C�digo da suba��o');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'si177_sequencial  ', 'int8              ', 'Campo Sequencial', '0', 'Campo Sequencial', 11, false, false, false, 1, 'text', 'Campo Sequencial');

INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_sequencial'), 1, (select max(codsequencia)+1 from db_syssequencia));
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_numemp'), 2, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codorgaotce'), 3, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codunidadesub'), 4, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codunidadesuborig'), 5, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codfuncao'), 6, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codsubfuncao'), 7, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codprograma'), 8, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_idacao'), 9, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_idsubacao'), 10, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_naturezadespesa'), 11, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_subelemento'), 12, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_codfontrecursos'), 13, 0);

INSERT INTO db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES ((select max(codsequencia)+1 from db_syssequencia), 'dotacaorpsicom_si177_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

INSERT INTO db_sysindices (codind, nomeind, codarq, campounico) VALUES ((select max(codind)+1 from db_sysindices), 'dotacaorpsicom_si177_numemp_index', 1010192, '0');

INSERT INTO db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'si177_numemp'), 1, 889, 0);

commit;
