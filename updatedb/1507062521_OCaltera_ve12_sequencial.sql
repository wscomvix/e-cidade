begin;

select fc_startsession();

update veiccadtipobaixa set ve12_sequencial = 7 where ve12_sequencial = 6;

commit;

-- Ocorr�ncia altera_ve12_sequencial


