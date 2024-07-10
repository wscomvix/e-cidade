
-- Ocorr�ncia 7425
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

ALTER TABLE empautoriza ALTER COLUMN e54_numerl TYPE varchar(12);
ALTER TABLE empautoriza ADD COLUMN e54_nummodalidade int4;
ALTER TABLE empautoriza ADD COLUMN e54_licoutrosorgaos int4;
ALTER TABLE empautoriza ADD COLUMN e54_adesaoregpreco int4;
ALTER TABLE empautoriza ADD COLUMN e54_tipoorigem int4;
ALTER TABLE empautoriza ADD COLUMN e54_tipoautorizacao int4;

-- inserindo novos tipos de compra tribunal

INSERT INTO pctipocompratribunal
VALUES(106,
       6,
       'Dispensa ou Inexigibilidade realizada por outro �rg�o',
       'MG');

INSERT INTO pctipocompratribunal
VALUES(107,
       6,
       'Licita��o - Regime Diferenciado de Contrata��es P�blicas - RDC',
       'MG');

INSERT INTO pctipocompratribunal
VALUES(108,
       6,
       'Licita��o realizada por consorcio p�blico',
       'MG');

INSERT INTO pctipocompratribunal
VALUES(109,
       6,
       'Licita��o realizada por outro ente da federa��o',
       'MG');

UPDATE db_syscampo
SET rotulo = 'Numero da Modalidade',
    rotulorel='Numero da Modalidade'
WHERE codcam IN
        (SELECT codcam
         FROM db_syscampo
         WHERE nomecam LIKE '%lic211_numero%');

-- Fim do script

COMMIT;

