<?
$campos = "obr02_sequencial,obr01_numeroobra,l20_edital,l03_descr, l20_numero,l20_objeto,CASE obr02_situacao
    WHEN 1 THEN 'N�o Iniciada'
    WHEN 2 THEN 'Iniciada'
    WHEN 3 THEN 'Paralizada por rescis�o contratual'
    WHEN 4 THEN 'Paralizada'
    WHEN 5 THEN 'Concluida e n�o recebida'
    WHEN 6 THEN 'Conclu�da e recebida provisoriamente'
    WHEN 7 THEN 'Conclu�da e recebida definitivamente'
    WHEN 8 THEN 'Reiniciada'
END AS obr02_situacao,obr02_dtsituacao";
?>
