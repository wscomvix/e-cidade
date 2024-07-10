
-- Ocorr�ncia 6782
BEGIN;
SELECT fc_startsession();

-- In�cio do script

-- ADICIONAR R�TULO ENCERRAMENTO DO PER�ODO CONT�BIL
-- #################################################
INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
  VALUES ((select max(codcam)+1 from db_syscampo), 'c99_encpercont', 'varchar(120)', 'Encerramento do Per�odo Cont�bil', '', 'Encerramento do Per�odo Cont�bil', 120, false, false, false, 0, 'text', 'Encerramento do Per�odo Cont�bil');

  INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
  VALUES (
    (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu')))
    , (select codcam from db_syscampo where nomecam = 'c99_encpercont')
    , (select max(seqarq)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu'))))
    , (select max(codsequencia)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu')))));


-- ADICIONAR R�TULO ENCERRAMENTO DO PER�ODO PATRIMONIAL
-- #################################################

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
  VALUES ((select max(codcam)+1 from db_syscampo), 'c99_encperpat', 'varchar(120)', 'Encerramento do Per�odo Patrimonial', '', 'Encerramento do Per�odo Patrimonial', 120, false, false, false, 0, 'text', 'Encerramento do Per�odo Patrimonial');

INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
  VALUES (
    (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu')))
    , (select codcam from db_syscampo where nomecam = 'c99_encperpat')
    , (select max(seqarq)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu'))))
    , (select max(codsequencia)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu')))));


-- ADICIONAR INPUT ENCERRAMENTO DO PER�ODO PATRIMONIAL
-- #################################################
ALTER TABLE condataconf ADD COLUMN c99_datapat DATE;

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
  VALUES ((select max(codcam)+1 from db_syscampo), 'c99_datapat', 'date', 'Data de Encerramento do Per�odo Patrim.', '', 'Data de Encerramento do Per�odo Patrim.', 1, false, false, false, 0, 'text', 'Data de Encerramento do Per�odo Patrim.');

INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
  VALUES (
    (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu')))
    , (select codcam from db_syscampo where nomecam = 'c99_datapat')
    , (select max(seqarq)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu'))))
    , (select max(codsequencia)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('c99_anousu')))));


-- CORRE��O DE R�TULO E DESCRI��O
-- #################################################
UPDATE db_syscampo
SET rotulo='Institui��o', descricao='Institui��o', rotulorel='Institui��o'
WHERE codcam=8010;

-- Fim do script

-- Fim do script

COMMIT;
