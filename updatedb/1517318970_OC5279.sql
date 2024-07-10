
-- Ocorr�ncia 5279
BEGIN;                   
SELECT fc_startsession();

--Inser��o dos campo no dicion�rio
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 've05_instit', 'int', 'Institui��o', 'null', 'Institui��o', 20, FALSE, FALSE, FALSE, 0, 'int', 'Institui��o');

-- V�nculo tabelas com campo
INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq='veicmotoristas' LIMIT 1),
(SELECT codcam FROM db_syscampo WHERE nomecam = 've05_instit'), 8, 0);

-- Alter table
ALTER TABLE veicmotoristas ADD COLUMN ve05_instit int8;


COMMIT;

