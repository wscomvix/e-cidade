
-- Ocorr�ncia OC8680
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

--crita tabelas
CREATE TABLE metasadotadas(
  c224_sequencial int8 PRIMARY KEY,
  c224_medida int4 NOT NULL,
  c224_descricao text NOT NULL
  );

CREATE SEQUENCE metasadotadas_c224_sequencial_seq;

CREATE TABLE medidasadotadaslrf(
c225_sequencial             int8 NOT NULL,
c225_metasadotadas          int4,
c225_anousu                 int4 NOT NULL,
c225_mesusu                 int4 NOT NULL,
c225_orgao                  int4 NOT NULL,
c225_dadoscomplementareslrf int8 NOT NULL,
PRIMARY KEY (c225_sequencial,c225_metasadotadas),
FOREIGN KEY (c225_metasadotadas) REFERENCES metasadotadas (c224_sequencial),
FOREIGN KEY (c225_dadoscomplementareslrf) references dadoscomplementareslrf (c218_sequencial),
CONSTRAINT medidasadotadaslrf_referencia UNIQUE (c225_metasadotadas, c225_orgao, c225_anousu, c225_mesusu)
);

CREATE SEQUENCE medidasadotadaslrf_c225_sequencial_seq;

--inseriondo dados padroes para tabela
INSERT INTO metasadotadas
VALUES (
            (SELECT nextval('metasadotadas_c224_sequencial_seq')), 1,
                                                                   'Acompanhamento sistem�tico da inadimpl�ncia dos tributos com a cobran�a da d�vida ativa, apurando-se os valores inscritos para cobran�a administrativa e ou judicial.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 2,
                                                                  'Campanhas de conscientiza��o e incentivo da popula��o quanto � quita��o de tributos e d�vida ativa.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 3,
                                                                  'Recadastramento mobili�rio e imobili�rio.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 4,
                                                                  'Acompanhamento e expans�o da base de arrecada��o do ISS - identifica��o de novas situa��es de sujei��o');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 5,
                                                                  'Informatiza��o e aprimoramento do setor de tributa��o para melhor controle de suas receitas pr�prias.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 6,
                                                                  'Implanta��o da nota fiscal eletr�nica.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 7,
                                                                  'Atualiza��o da legisla��o tribut�ria.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 8,
                                                                  'Estudos e an�lises para fomentar a fiscaliza��o tribut�ria dos maiores contribuintes do munic�pio.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 9,
                                                                  'Manuten��o da Junta de Julgamento e de Recursos Fiscais.');
INSERT INTO metasadotadas
VALUES(
           (SELECT nextval('metasadotadas_c224_sequencial_seq')), 99,
                                                                  'Outras medidas de combate a sonega��o e evas�o de receitas.');

-- Fim do script

COMMIT;

