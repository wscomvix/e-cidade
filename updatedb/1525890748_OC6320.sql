-- Ocorr�ncia 6320
BEGIN;


SELECT fc_startsession();

-- In�cio do script

INSERT INTO db_itensmenu
VALUES (
          (SELECT max(id_item) + 1
           FROM db_itensmenu), 'Excluir autoriza��o de empenho',
                               'Excluir autoriza��o de empenho',
                               'ac04_excluiautorizacao001.php',
                               1,
                               1,
                               'Excluir autoriza��o de empenho',
                               't');


INSERT INTO db_menu
VALUES (8289,
          (SELECT max(id_item)
           FROM db_itensmenu),
          (SELECT max(menusequencia)+1
           FROM db_menu
           WHERE id_item = 8289
             AND modulo = (SELECT id_item
                FROM db_modulos
                WHERE nome_modulo = 'Contratos')), (SELECT id_item
                FROM db_modulos
                WHERE nome_modulo = 'Contratos'));


-- Fim do script

COMMIT;

