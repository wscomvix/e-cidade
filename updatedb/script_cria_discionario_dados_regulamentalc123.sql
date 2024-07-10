select fc_startsession();
begin;
INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'regulamentalc123                        ', 'Regulamenta��o LC 123/2006', 'l210 ', '2016-03-21', 'regulamentalc123', 0, false, false, false, false);

INSERT INTO db_sysarqmod (codmod, codarq) VALUES (19, (select max(codarq) from db_sysarquivo));

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_sequencial                         ', 'int8                                    ', 'C�digo sequencial da tabela', '0', 'C�digo Sequencial', 10, false, false, false, 1, 'text', 'C�digo Sequencial');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_regulamentart47                    ', 'int8                                    ', 'Identifica se o munic�pio implementou a regulamenta��o do art. 47 da 123/2006 n', '', 'Possui regulamenta��o do art. 47', 1, false, false, false, 1, 'text', 'Possui regulamenta��o do art. 47');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_nronormareg                        ', 'varchar(6)                              ', 'N�mero da norma que regulamentou o art. 47 da LC 123/2006.', '', 'Numero norma', 6, true, true, false, 0, 'text', 'Numero norma');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_datanormareg                       ', 'date                                    ', 'Data da norma que regulamentou o art. 47 da LC 123/2006.', '', 'Data norma', 10, true, false, false, 1, 'text', 'Data norma');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_datapubnormareg                    ', 'date                                    ', 'Data de publica��o da norma que regulamentou o art. 47 da LC 123/2006.', '', 'Data publica��o norma', 10, true, false, false, 1, 'text', 'Data publica��o norma');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_regexclusiva                       ', 'int4                                    ', 'Identifica se o munic�pio regulamentou procedimentos para participa��o de ME e EPP.', '', 'Regula��o Exclusiva', 1, true, false, false, 1, 'text', 'Regula��o Exclusiva');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_artigoregexclusiva                 ', 'varchar(6)                              ', 'Artigo da regulamenta��o exclusiva.', '', 'Artigo da regulamenta��o exclusiva', 6, true, true, false, 0, 'text', 'Artigo da regulamenta��o exclusiva');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_valorlimiteregexclusiva            ', 'float8                                  ', 'Valor Limite da regulamenta��o exclusiva (LC 123/2006 art. 48, I).n', '', 'Valor Limite da reg exclusiva', 14, false, false, false, 4, 'text', 'Valor Limite da reg exclusiva');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_procsubcontratacao                 ', 'int4                                    ', 'Identifica se o munic�pio estabeleceu procedimentos para a subcontrata��o de ME e EPP.', '', 'Procedimentos Subcontratacao', 1, true, false, false, 1, 'text', 'Procedimentos Subcontratacao');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_artigoprocsubcontratacao           ', 'varchar(6)                              ', 'Artigo dos procedimentos de subcontrata��o (LC 123/2006 art. 48, II).n', '', 'Artigo proc subcontrata��o', 6, true, true, false, 0, 'text', 'Artigo proc subcontrata��o');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_percentualsubcontratacao           ', 'float8                                  ', 'Percentual estabelecido para subcontrata��o (LC 123/2006 art. 48, II). Informar valor com duas casas decimais.n', '', 'Percent Subcontratacao', 6, false, false, false, 4, 'text', 'Percent Subcontratacao');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_criteriosempenhopagamento          ', 'int4                                    ', 'O munic�pio estabeleceu crit�rios para empenho epagamento a Microempresas e Empresas de Pequeno Porte? (LC 123/2006 (art. 48, � 2o). 1 ? Sim; 2 ? N�o.n', '', 'Criterios Empenho Pagamento', 1, true, false, false, 1, 'text', 'Criterios Empenho Pagamento');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_artigoempenhopagamento             ', 'varchar(6)                              ', 'Artigo relativo aos crit�rios para empenho e pagamento (LC 123/2006 art. 48, � 2o).n', '', 'Artigo Empenho Pagamento', 6, true, true, false, 0, 'text', 'Artigo Empenho Pagamento');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_estabeleceuperccontratacao         ', 'int4                                    ', 'O munic�pio estabeleceu reserva de percentual do objetonpara a contrata��o de microempresas e empresas den', '', 'Estabeleceu Percent Contratacao', 1, true, false, false, 1, 'text', 'Estabeleceu Percent Contratacao');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_artigoperccontratacao              ', 'varchar(6)                              ', 'Artigo do percentual contrata��o (LC 123/2006 (art. 48, III).', '', 'Artigo Percent. Contraca��o', 6, true, true, false, 0, 'text', 'Artigo Percent. Contraca��o');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_percentualcontratacao              ', 'float8                                  ', 'Percentual estabelecido para contrata��o (LC 123/2006 art. 48, III). Informar valor com duas casas decimais.n', '', 'Percentual contratado', 6, false, false, false, 4, 'text', 'Percentual contratado');
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'l210_instit                             ', 'int4                                    ', 'Institui��o', '', 'Institui��o', 1, true, false, false, 1, 'text', 'Institui��o');

INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_sequencial'), 1, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_regulamentart47'), 2, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_nronormareg'), 3, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_datanormareg'), 4, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_datapubnormareg'), 5, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_regexclusiva'), 6, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_artigoregexclusiva'), 7, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_valorlimiteregexclusiva'), 8, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_procsubcontratacao'), 9, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_artigoprocsubcontratacao'), 10, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_percentualsubcontratacao'), 11, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_criteriosempenhopagamento'), 12, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_artigoempenhopagamento'), 13, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_estabeleceuperccontratacao'), 14, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_artigoperccontratacao'), 15, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_percentualcontratacao'), 16, 0);
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l210_instit'), 17, 0);

commit;