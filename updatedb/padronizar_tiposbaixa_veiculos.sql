begin;
delete from veiccadtipobaixa;
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (1,'Alienacao');
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (2,'Obsolescencia (bens inserviveis)');
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (3,'Sinistro');
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (4,'Doacao');
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (5,'Cessao');
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (6,'Transferencia');
insert into veiccadtipobaixa (ve12_sequencial,ve12_descr) values (99,'Outros');
commit;