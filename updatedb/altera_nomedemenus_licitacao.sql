BEGIN;
SELECT fc_startsession();
UPDATE db_itensmenu set descricao = 'Respons�veis pela licita��o' where id_item = 4796;
UPDATE db_itensmenu set descricao = 'Comiss�o de licita��o' where id_item = 3000045;

UPDATE db_syscampo set descricao = 'Comiss�o de licita��o', rotulo = 'Comiss�o de licita��o', rotulorel = 'Comiss�o de licita��o'  where codcam = 2009525;
UPDATE db_syscampo set rotulo = 'Respons�veis pela licita��o', rotulorel = 'Respons�veis pela licita��o'  where codcam = 7909;
COMMIT;