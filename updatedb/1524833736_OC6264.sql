
-- Ocorr�ncia 6264
BEGIN;                   
SELECT fc_startsession();

-- In�cio do script

alter table public.licpregao add column l45_instit integer;

-- Fim do script

COMMIT;

