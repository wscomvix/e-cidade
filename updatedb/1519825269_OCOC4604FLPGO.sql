 -- Ocorr�ncia OC4604FLPGO
BEGIN;


SELECT fc_startsession();

 -- In�cio do script

ALTER TABLE flpgo112018
ALTER COLUMN si196_nrodocumento
DROP NOT NULL;


ALTER TABLE flpgo112018
ALTER COLUMN si196_nrodocumento
DROP DEFAULT;


ALTER TABLE flpgo112018
ALTER COLUMN si196_codreduzidopessoa
DROP NOT NULL;


ALTER TABLE flpgo112018
ALTER COLUMN si196_codreduzidopessoa
DROP DEFAULT;


ALTER TABLE flpgo112018
ALTER COLUMN si196_mes
DROP NOT NULL;


ALTER TABLE flpgo112018
ALTER COLUMN si196_mes
DROP DEFAULT;


ALTER TABLE flpgo112018
ALTER COLUMN si196_inst
DROP NOT NULL;


ALTER TABLE flpgo112018
ALTER COLUMN si196_inst
DROP DEFAULT;


ALTER TABLE flpgo112018
ALTER COLUMN si196_tiporemuneracao
DROP NOT NULL;


ALTER TABLE flpgo112018
ALTER COLUMN si196_tiporemuneracao
DROP DEFAULT;


ALTER TABLE flpgo112018 ADD COLUMN si196_indtipopagamento VARCHAR(1);


ALTER TABLE flpgo112018 ADD COLUMN si196_codvinculopessoa VARCHAR(15);


ALTER TABLE flpgo112018 ADD COLUMN si196_codrubricaremuneracao VARCHAR(4);


ALTER TABLE flpgo112018 ADD COLUMN si196_desctiporubrica VARCHAR(150);

ALTER TABLE flpgo122018
ALTER COLUMN si197_nrodocumento
DROP NOT NULL;


ALTER TABLE flpgo122018
ALTER COLUMN si197_nrodocumento
DROP DEFAULT;


ALTER TABLE flpgo122018
ALTER COLUMN si197_codreduzidopessoa
DROP NOT NULL;


ALTER TABLE flpgo122018
ALTER COLUMN si197_codreduzidopessoa
DROP DEFAULT;


ALTER TABLE flpgo122018
ALTER COLUMN si197_mes
DROP NOT NULL;


ALTER TABLE flpgo122018
ALTER COLUMN si197_mes
DROP DEFAULT;


ALTER TABLE flpgo122018
ALTER COLUMN si197_inst
DROP NOT NULL;


ALTER TABLE flpgo122018
ALTER COLUMN si197_inst
DROP DEFAULT;


ALTER TABLE flpgo122018
ALTER COLUMN si197_tipodesconto
DROP NOT NULL;


ALTER TABLE flpgo122018
ALTER COLUMN si197_tipodesconto
DROP DEFAULT;


ALTER TABLE flpgo122018 ADD COLUMN si197_indtipopagamento VARCHAR(1);


ALTER TABLE flpgo122018 ADD COLUMN si197_codvinculopessoa VARCHAR(15);


ALTER TABLE flpgo122018 ADD COLUMN si197_codrubricadesconto VARCHAR(4);


ALTER TABLE flpgo122018 ADD COLUMN si197_desctiporubrica VARCHAR(150);

 -- Fim do script
 COMMIT;

