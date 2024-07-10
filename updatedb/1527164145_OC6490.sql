
-- Ocorr�ncia 6490
BEGIN;
SELECT fc_startsession();

-- In�cio do script

-- CAMPO PARA IDENTIFICAR A FINALIZA��O DO ITEM EM MATREQUI
ALTER TABLE matrequi ADD COLUMN m40_finalizado BOOLEAN DEFAULT FALSE;

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
  VALUES ((select max(codcam)+1 from db_syscampo), 'm40_finalizado', 'boolean', 'Status da Requisi��o', '', 'Status da Requisi��o', 1, false, true, false, 0, 'text', 'Status da Requisi��o');

INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
  VALUES (
    (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('m40_codigo')))
    , (select codcam from db_syscampo where nomecam = 'm40_finalizado')
    , (select max(seqarq)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('m40_codigo'))))
    , (select max(codsequencia)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('m40_codigo')))));

-- Fim do script

COMMIT;

BEGIN;
SELECT fc_startsession();

-- In�cio do script

-- CAMPO PARA IDENTIFICAR A DATA DA FINALIZA��O DO ITEM EM MATREQUI
ALTER TABLE matrequi ADD COLUMN m40_dtfinalizado DATE;

INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
  VALUES ((select max(codcam)+1 from db_syscampo), 'm40_dtfinalizado', 'date', 'Dt. Finaliza��o', '', 'Dt. Finaliza��o', 1, false, true, false, 0, 'text', 'Dt. Finaliza��o');

INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
  VALUES (
    (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('m40_codigo')))
    , (select codcam from db_syscampo where nomecam = 'm40_dtfinalizado')
    , (select max(seqarq)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('m40_codigo'))))
    , (select max(codsequencia)+1 from db_sysarqcamp where codarq = (select codarq from db_sysarqcamp where codcam = (select codcam from db_syscampo where nomecam in ('m40_codigo')))));

-- Fim do script

COMMIT;
