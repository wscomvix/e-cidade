BEGIN;

SELECT fc_startsession();

INSERT INTO db_itensmenu VALUES (4001100, 'Transfer�ncia', 'Transfer�ncia de ve�culos', 'vei1_baixatransferencia001.php', 1, 1, 'Transfer�ncia de ve�culos', 't');
INSERT INTO db_menu VALUES (5395, 4001100, 200, 633);

INSERT INTO db_itensmenu VALUES (4001101, 'Documentos', 'Documentos de ve�culos', '', 1, 1, 'Documentos de ve�culos', 't');
INSERT INTO db_itensmenu VALUES (4001102, 'Transfer�ncia', 'Relat�rio de transfer�ncia de ve�culos', '', 1, 1, 'Relat�rio de transfer�ncia de ve�culos', 't');
INSERT INTO db_menu VALUES (5336, 4001101, 201, 633);
INSERT INTO db_menu VALUES (4001101, 4001102, 202, 633);



COMMIT;



