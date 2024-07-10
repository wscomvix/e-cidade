BEGIN;


SELECT fc_startsession();


CREATE TABLE "rubricasesocial" ( "e990_sequencial" varchar NOT NULL,
                                                           "e990_descricao" varchar NOT NULL,
                                                                                    CONSTRAINT rubricasesocial_pk PRIMARY KEY ("e990_sequencial")) WITH ( OIDS=FALSE);


CREATE TABLE "baserubricasesocial" ( "e991_rubricasesocial" varchar NOT NULL,
                                                                                              "e991_rubricas" varchar NOT NULL,
                                                                                                                      "e991_instit" integer NOT NULL) WITH ( OIDS=FALSE);



ALTER TABLE "baserubricasesocial" ADD CONSTRAINT "baserubricasesocial_rubricasesocial_fk0"
FOREIGN KEY ("e991_rubricasesocial") REFERENCES "rubricasesocial"("e990_sequencial");


ALTER TABLE "baserubricasesocial" ADD CONSTRAINT "baserubricasesocial_rubricas_fk1"
FOREIGN KEY ("e991_rubricas",
             "e991_instit") REFERENCES "rhrubricas"("rh27_rubric",
                                                    "rh27_instit");

 -- INSERINDO db_sysarquivo

INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform)
VALUES (
          (SELECT max(codarq)+1
           FROM db_sysarquivo), 'rubricasesocial',
                                'estrutura de armazenamento de r�bricas do e-social',
                                'e990',
                                '2018-02-14',
                                'R�bricas E-Social',
                                0,
                                FALSE,
                                FALSE,
                                FALSE,
                                FALSE);

 -- INSERINDO db_sysarqmod

INSERT INTO db_sysarqmod (codmod, codarq)
VALUES (28,
          (SELECT max(codarq)
           FROM db_sysarquivo));

 -- INSERINDO db_syscampo

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
VALUES (
          (SELECT max(codcam)+1
           FROM db_syscampo), 'e990_sequencial',
                              'int4',
                              'Sequencia rubricas e-social',
                              '0',
                              'Sequencia rubricas e-social',
                              10,
                              FALSE,
                              FALSE,
                              FALSE,
                              1,
                              'text',
                              'Sequencia rubricas e-social');


INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
VALUES (
          (SELECT max(codcam)+1
           FROM db_syscampo), 'e990_descricao',
                              'int4                                    ',
                              'Nome da r�brica',
                              '0',
                              'Nome da r�brica',
                              10,
                              FALSE,
                              FALSE,
                              FALSE,
                              1,
                              'text',
                              'Nome da r�brica');


INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e990_sequencial'), 1,
                                               0);


INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e990_descricao'), 2,
                                              0);


INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform)
VALUES (
          (SELECT max(codarq)+1
           FROM db_sysarquivo), 'baserubricasesocial',
                                'Bases das r�bricas do E-Social',
                                'e991',
                                '2018-02-14',
                                'Bases das r�bricas do E-Social',
                                0,
                                FALSE,
                                FALSE,
                                FALSE,
                                FALSE);

 -- INSERINDO db_sysarqmod

INSERT INTO db_sysarqmod (codmod, codarq)
VALUES (28,
          (SELECT max(codarq)
           FROM db_sysarquivo));

 -- INSERINDO db_syscampo



INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
VALUES (
          (SELECT max(codcam)+1
           FROM db_syscampo), 'e991_rubricasesocial',
                              'int4                                    ',
                              'Sequencial do rubricasesocial',
                              '0',
                              'Sequencial do rubricasesocial',
                              10,
                              FALSE,
                              FALSE,
                              FALSE,
                              1,
                              'text',
                              'Sequencial do rubricasesocial');


INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
VALUES (
          (SELECT max(codcam)+1
           FROM db_syscampo), 'e991_rubricas',
                              'int4                                    ',
                              'Sequencial do rubricas',
                              '0',
                              'Sequencial do rubricas',
                              10,
                              FALSE,
                              FALSE,
                              FALSE,
                              1,
                              'text',
                              'Sequencial do rubricas');


INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
VALUES (
          (SELECT max(codcam)+1
           FROM db_syscampo), 'e991_instit',
                              'text',
                              'Instituicao',
                              '0',
                              'Instituicao',
                              10,
                              FALSE,
                              FALSE,
                              FALSE,
                              1,
                              'text',
                              'Instituicao do rhrubricas');



INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e991_rubricasesocial'), 2,
                                                    0);


INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e991_rubricas'), 3,
                                             0);


INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e991_instit'), 4,
                                           0);

 -- INSERINDO db_sysforkey

INSERT INTO db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e991_rubricasesocial'), 1,
          (SELECT codarq
           FROM db_sysarquivo
           WHERE nomearq = 'rubricasesocial'), 0);


INSERT INTO db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e991_rubricas'), 2,
          (SELECT codarq
           FROM db_sysarquivo
           WHERE nomearq = 'rhrubricas'), 0);


INSERT INTO db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel)
VALUES (
          (SELECT max(codarq)
           FROM db_sysarquivo),
          (SELECT codcam
           FROM db_syscampo
           WHERE nomecam = 'e991_instit'), 3,
          (SELECT codarq
           FROM db_sysarquivo
           WHERE nomearq = 'rhrubricas'), 0);




INSERT INTO db_itensmenu
VALUES (
          (SELECT max(id_item) + 1
           FROM db_itensmenu), 'E-social / Rubricas',
                               'E-social / Rubricas',
                               '',
                               1,
                               1,
                               'E-social / Rubricas',
                               't');


INSERT INTO db_menu
VALUES (4374,
          (SELECT max(id_item)
           FROM db_itensmenu),
          (SELECT (max(menusequencia)+1)
           FROM db_menu
           WHERE id_item = 4374
             AND modulo = 952), 952);




INSERT INTO db_itensmenu
VALUES (
          (SELECT max(id_item) + 1
           FROM db_itensmenu), 'Inclusao',
                               'Inclusao',
                               'pes2_rubricasesocial001.php',
                               1,
                               1,
                               'Inclusao',
                               't');


INSERT INTO db_menu
VALUES (
          (SELECT max(id_item)-1
           FROM db_itensmenu),
          (SELECT max(id_item)
           FROM db_itensmenu),
          (SELECT (max(menusequencia)+1)
           FROM db_menu
           WHERE id_item =
               (SELECT max(id_item)-1
                FROM db_itensmenu)
             AND modulo = 952), 952);




INSERT INTO db_itensmenu
VALUES (
          (SELECT max(id_item) + 1
           FROM db_itensmenu), 'Alteracao',
                               'Alteracao',
                               'pes2_rubricasesocial002.php',
                               1,
                               1,
                               'Alteracao',
                               't');


INSERT INTO db_menu
VALUES (
          (SELECT max(id_item)-2
           FROM db_itensmenu),
          (SELECT max(id_item)
           FROM db_itensmenu),
          (SELECT (max(menusequencia)+1)
           FROM db_menu
           WHERE id_item =
               (SELECT max(id_item)-2
                FROM db_itensmenu)
             AND modulo = 952), 952);




INSERT INTO db_itensmenu
VALUES (
          (SELECT max(id_item) + 1
           FROM db_itensmenu), 'Exclusao',
                               'Exclusao',
                               'pes2_rubricasesocial003.php',
                               1,
                               1,
                               'Exclusao',
                               't');


INSERT INTO db_menu
VALUES (
          (SELECT max(id_item)-3
           FROM db_itensmenu),
          (SELECT max(id_item)
           FROM db_itensmenu),
          (SELECT (max(menusequencia)+1)
           FROM db_menu
           WHERE id_item =
               (SELECT max(id_item)-3
                FROM db_itensmenu)
             AND modulo = 952), 952);

TRUNCATE TABLE rubricasesocial CASCADE;

INSERT INTO rubricasesocial
VALUES('1000',
       'Sal�rio, vencimento, soldo ou subs�dio');


INSERT INTO rubricasesocial
VALUES('1002',
       'Descanso semanal remunerado - DSR');


INSERT INTO rubricasesocial
VALUES('1003',
       'Horas extraordin�rias');


INSERT INTO rubricasesocial
VALUES('1004',
       'Horas extraordin�rias - Indeniza��o de banco de horas');


INSERT INTO rubricasesocial
VALUES('1020',
       'F�rias - gozadas');


INSERT INTO rubricasesocial
VALUES('1023',
       'F�rias - abono pecuni�rio');


INSERT INTO rubricasesocial
VALUES('1024',
       'F�rias - o dobro na vig�ncia do contrato');


INSERT INTO rubricasesocial
VALUES('1040',
       'Licen�a-pr�mio');


INSERT INTO rubricasesocial
VALUES('1041',
       'Licen�a-pr�mio indenizada');


INSERT INTO rubricasesocial
VALUES('1099',
       'Outras verbas salariais');


INSERT INTO rubricasesocial
VALUES('1201',
       'Adicional de fun��o / cargo confian�a');


INSERT INTO rubricasesocial
VALUES('1202',
       'Adicional de insalubridade');


INSERT INTO rubricasesocial
VALUES('1203',
       'Adicional de periculosidade');


INSERT INTO rubricasesocial
VALUES('1204',
       'Adicional de transfer�ncia');


INSERT INTO rubricasesocial
VALUES('1205',
       'Adicional noturno');


INSERT INTO rubricasesocial
VALUES('1206',
       'Adicional por tempo de servi�o(quinquenio, bienio,etc)');


INSERT INTO rubricasesocial
VALUES('1211',
       'assiduidade/produtividade');


INSERT INTO rubricasesocial
VALUES('1212',
       'Gratifica��es ou outras verbas permanente - (integra remunera��o do efetivo)');


INSERT INTO rubricasesocial
VALUES('1213',
       'Gratifica��es ou outras verbas transit�ria - (n�o integra remunera��o)');


INSERT INTO rubricasesocial
VALUES('1215',
       'Adicional de unidoc�ncia');


INSERT INTO rubricasesocial
VALUES('1299',
       'Outros adicionais');


INSERT INTO rubricasesocial
VALUES('1350',
       'Bolsa de estudo - estagi�rio');


INSERT INTO rubricasesocial
VALUES('1407',
       'Aux�lio-educa��o');


INSERT INTO rubricasesocial
VALUES('1409',
       'Sal�rio-fam�lia');


INSERT INTO rubricasesocial
VALUES('1410',
       'Aux�lio - Locais de dif�cil acesso');


INSERT INTO rubricasesocial
VALUES('1602',
       'Ajuda de custo de transfer�ncia');


INSERT INTO rubricasesocial
VALUES('1620',
       'Ressarcimento de despesas pelo uso de ve�culo pr�prio');


INSERT INTO rubricasesocial
VALUES('1621',
       'Ressarcimento de despesas de viagem, exceto despesas com ve�culos');


INSERT INTO rubricasesocial
VALUES('1623',
       'Ressarcimento de provis�o (IRRF)');


INSERT INTO rubricasesocial
VALUES('1629',
       'Ressarcimento de outras despesas');


INSERT INTO rubricasesocial
VALUES('1651',
       'Di�rias de viagem - at� 50% do sal�rio');


INSERT INTO rubricasesocial
VALUES('1652',
       'Di�rias de viagem - acima de 50% do sal�rio');


INSERT INTO rubricasesocial
VALUES('1801',
       'Aux�lio-alimenta��o');


INSERT INTO rubricasesocial
VALUES('1805',
       'Aux�lio-moradia');


INSERT INTO rubricasesocial
VALUES('1810',
       'Aux�lio-transporte');


INSERT INTO rubricasesocial
VALUES('2920',
       'Reembolsos diversos / devolucao de descontos indevidos');


INSERT INTO rubricasesocial
VALUES('2930',
       'Insufici�ncia de saldo');


INSERT INTO rubricasesocial
VALUES('3501',
       'Remunera��o por presta��o de servi�os(sem vinc. trabalhista)');


INSERT INTO rubricasesocial
VALUES('4050',
       'Sal�rio maternidade');


INSERT INTO rubricasesocial
VALUES('4051',
       'Sal�rio maternidade - 13� sal�rio');


INSERT INTO rubricasesocial
VALUES('5001',
       '13� sal�rio');


INSERT INTO rubricasesocial
VALUES('5005',
       '13� sal�rio complementar/ diferen�a');


INSERT INTO rubricasesocial
VALUES('5504',
       '13� sal�rio - Adiantamento');


INSERT INTO rubricasesocial
VALUES('6000',
       'Saldo de sal�rios na rescis�o contratual');


INSERT INTO rubricasesocial
VALUES('6001',
       '13� sal�rio relativo ao aviso-pr�vio indenizado');


INSERT INTO rubricasesocial
VALUES('6002',
       '13� sal�rio proporcional na rescis�o');


INSERT INTO rubricasesocial
VALUES('6003',
       'Indeniza��o compensat�ria do aviso-pr�vio');


INSERT INTO rubricasesocial
VALUES('6004',
       'F�rias - o dobro na rescis�o');


INSERT INTO rubricasesocial
VALUES('6006',
       'F�rias proporcionais');


INSERT INTO rubricasesocial
VALUES('6007',
       'F�rias vencidas na rescis�o');


INSERT INTO rubricasesocial
VALUES('6101',
       'Indeniza��o compensat�ria- multa rescis�ria 20 ou 40% (CF/88) - clt');


INSERT INTO rubricasesocial
VALUES('6102',
       'Indeniza��o do art. 9� lei n� 7.238/84');


INSERT INTO rubricasesocial
VALUES('6104',
       'Indeniza��o do art. 479 da CLT');


INSERT INTO rubricasesocial
VALUES('6106',
       'Multa do art. 477 da CLT');


INSERT INTO rubricasesocial
VALUES('6129',
       'Outras Indeniza��es - n�o prevista no manual e-social');


INSERT INTO rubricasesocial
VALUES('6901',
       'Desconto do aviso-pr�vio (pedido demiss�o e empregado n�o cumpriu aviso-pr�vio)');


INSERT INTO rubricasesocial
VALUES('6904',
       'Multa prevista no art. 480 da CLT');


INSERT INTO rubricasesocial
VALUES('7001',
       'Proventos Aposentados');


INSERT INTO rubricasesocial
VALUES('7002',
       'Proventos - Pens�o por morte Civil');


INSERT INTO rubricasesocial
VALUES('9200',
       'Desconto de Adiantamentos (exceto desc. adiant. 13�)');


INSERT INTO rubricasesocial
VALUES('9201',
       'Contribui��o Previdenci�ria');


INSERT INTO rubricasesocial
VALUES('9203',
       'Imposto de renda retido na fonte');


INSERT INTO rubricasesocial
VALUES('9205',
       'Provis�o de contribui��o previdenci�ria e IRRF');


INSERT INTO rubricasesocial
VALUES('9209',
       'Faltas ou atrasos');


INSERT INTO rubricasesocial
VALUES('9210',
       'DSR s/faltas e atrasos');


INSERT INTO rubricasesocial
VALUES('9213',
       'Pens�o aliment�cia');


INSERT INTO rubricasesocial
VALUES('9214',
       '13� sal�rio - desconto de adiantamento');


INSERT INTO rubricasesocial
VALUES('9216',
       'Desconto de vale-transporte');


INSERT INTO rubricasesocial
VALUES('9217',
       'Contribui��o a Outras Entidades e Fundos');


INSERT INTO rubricasesocial
VALUES('9218',
       'Reten��es judiciais');


INSERT INTO rubricasesocial
VALUES('9219',
       'Desconto de assist�ncia m�dica ou odontol�gica');


INSERT INTO rubricasesocial
VALUES('9221',
       'Desconto de f�rias');


INSERT INTO rubricasesocial
VALUES('9222',
       'Desconto de outros impostos e contribui��es');


INSERT INTO rubricasesocial
VALUES('9223',
       'Previd�ncia complementar - parte do empregado(clt)');


INSERT INTO rubricasesocial
VALUES('9225',
       'Previd�ncia complementar - parte do servidor P�blico');


INSERT INTO rubricasesocial
VALUES('9230',
       'Contribui��o Sindical - Compuls�ria(anual)');


INSERT INTO rubricasesocial
VALUES('9231',
       'Contribui��o Sindical - Associativa(mensal)');


INSERT INTO rubricasesocial
VALUES('9232',
       'Contribui��o Sindical - Assistencial (custeio das atividades assistenciais do sindicato)');


INSERT INTO rubricasesocial
VALUES('9250',
       'Seguro de vida - desconto');


INSERT INTO rubricasesocial
VALUES('9254',
       'Empr�stimos consignados - desconto');


INSERT INTO rubricasesocial
VALUES('9258',
       'Conv�nios - fornecimento de produtos ou servi�os ao empregado, sem pagamento imediato, mas com posterior desconto em folha');


INSERT INTO rubricasesocial
VALUES('9270',
       'Danos e preju�zos causados pelo trabalhador (ex: infra��o de transito)');


INSERT INTO rubricasesocial
VALUES('9290',
       'Desconto de pagamento indevido em meses anteriores');


INSERT INTO rubricasesocial
VALUES('9299',
       'Outros descontos n�o previstos nos itens anteriores');


INSERT INTO rubricasesocial
VALUES('9901',
       'Base de c�lculo da contribui��o previdenci�ria');


INSERT INTO rubricasesocial
VALUES('9902',
       'Total da base de c�lculo do FGTS');


INSERT INTO rubricasesocial
VALUES('9903',
       'Total da base de c�lculo do IRRF');


INSERT INTO rubricasesocial
VALUES('9904',
       'Total da base de c�lculo do FGTS rescis�rio');


INSERT INTO rubricasesocial
VALUES('9908',
       'FGTS - dep�sito');


INSERT INTO rubricasesocial
VALUES('9930',
       'Sal�rio maternidade pago pela Previd�ncia Social');


INSERT INTO rubricasesocial
VALUES('9931',
       '13� sal�rio maternidade pago pela Previd�ncia Social');


INSERT INTO rubricasesocial
VALUES('9932',
       'Aux�lio-doen�a acident�rio');


INSERT INTO rubricasesocial
VALUES('9933',
       'Aux�lio-doen�a');


INSERT INTO rubricasesocial
VALUES('9938',
       'Isen��o IRRF - 65 anos');


INSERT INTO rubricasesocial
VALUES('9939',
       'Outros valores tribut�veis');


INSERT INTO rubricasesocial
VALUES('9989',
       'Outros valores informativos, que n�o sejam vencimentos nem descontos');


COMMIT;

