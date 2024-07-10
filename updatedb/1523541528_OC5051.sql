
-- Ocorr�ncia 5051
BEGIN;
SELECT fc_startsession();

-- In�cio do script

UPDATE acordoposicaotipo
SET ac27_descricao = 'Inclus�o'
WHERE ac27_sequencial = 1;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Reequilibrio'
WHERE ac27_sequencial = 2;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Realinhamento'
WHERE ac27_sequencial = 3;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Aditamento'
WHERE ac27_sequencial = 4;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Outros'
WHERE ac27_sequencial = 7;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Vig�ncia/Execu��o'
WHERE ac27_sequencial = 13;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Altera��o de Prazo de Execu��o'
WHERE ac27_sequencial = 8;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Acr�scimo de Item(ns)'
WHERE ac27_sequencial = 9;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Decr�scimo de Item(ns)'
WHERE ac27_sequencial = 10;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Altera��o de Projeto/Especifica��o'
WHERE ac27_sequencial = 12;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Acr�scimo/Decr�scimo de item(ns) conjugado'
WHERE ac27_sequencial = 14;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Reajuste'
WHERE ac27_sequencial = 5;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Altera��o de Prazo de Vig�ncia'
WHERE ac27_sequencial = 6;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Acr�scimo de Valor (Apostilamento)'
WHERE ac27_sequencial = 15;


UPDATE acordoposicaotipo
SET ac27_descricao = 'Decr�scimo de Valor (Apostilamento)'
WHERE ac27_sequencial = 16;


UPDATE acordoposicaotipo
SET ac27_descricao = 'N�o houve altera��o de Valor (Apostilamento)'
WHERE ac27_sequencial = 17;

-- Fim do script

COMMIT;

