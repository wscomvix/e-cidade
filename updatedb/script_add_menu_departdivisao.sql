BEGIN;
SELECT fc_startsession();
INSERT INTO db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) values (3000183,'Departamento/Divis�o','Departamento/Divis�o','pat2_gerardepartdiv.php',1,1,'Departamento/Divis�o','t');
INSERT INTO db_menu (id_item,id_item_filho,menusequencia,modulo) values (30,3000183,600,439);
COMMIT;
