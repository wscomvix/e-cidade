BEGIN;
INSERT INTO db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) values ((select max(id_item)+1 from db_itensmenu),'SIGAF','SIGAF','mat4_gerarsigaf.php',1,1,'SIGAF','t');
INSERT INTO db_menu (id_item,id_item_filho,menusequencia,modulo) values (32,(select id_item from db_itensmenu where descricao = 'SIGAF'),453,480);

INSERT INTO db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) values ((select max(id_item)+1 from db_itensmenu),'Question�rio de Triagem','Question�rio de Triagem','',1,1,'Question�rio de Triagem','t');
INSERT INTO db_menu (id_item,id_item_filho,menusequencia,modulo) values (3444,(select id_item from db_itensmenu where descricao = 'Question�rio de Triagem'),400,6877);

INSERT INTO db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','far1_qtriagem001.php',1,1,'Inclus�o','t');
INSERT INTO db_menu (id_item,id_item_filho,menusequencia,modulo) values ((select id_item from db_itensmenu where descricao = 'Question�rio de Triagem'),(select max(id_item) from db_itensmenu),1,6877);
INSERT INTO db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','far1_qtriagem002.php',1,1,'Altera��o','t');
INSERT INTO db_menu (id_item,id_item_filho,menusequencia,modulo) values ((select id_item from db_itensmenu where descricao = 'Question�rio de Triagem'),(select max(id_item) from db_itensmenu),2,6877);
INSERT INTO db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','far1_qtriagem003.php',1,1,'Exclus�o','t');
INSERT INTO db_menu (id_item,id_item_filho,menusequencia,modulo) values ((select id_item from db_itensmenu where descricao = 'Question�rio de Triagem'),(select max(id_item) from db_itensmenu),3,6877);


COMMIT;