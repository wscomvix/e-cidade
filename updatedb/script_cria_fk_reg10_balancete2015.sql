select fc_startsession();
begin;
alter table balancete112015 add column si178_reg10 int8 not null;
alter table balancete122015 add column si179_reg10 int8 not null;
alter table balancete132015 add column si180_reg10 int8 not null;
alter table balancete142015 add column si181_reg10 int8 not null;
alter table balancete152015 add column si182_reg10 int8 not null;
alter table balancete162015 add column si183_reg10 int8 not null;
alter table balancete172015 add column si184_reg10 int8 not null;
alter table balancete182015 add column si185_reg10 int8 not null;
alter table balancete192015 add column si186_reg10 int8 not null;
alter table balancete202015 add column si187_reg10 int8 not null;
alter table balancete212015 add column si188_reg10 int8 not null;
alter table balancete222015 add column si189_reg10 int8 not null;

alter table balancete112015 add constraint fk_balancete102015_si77_sequencial foreign key (si178_reg10) references balancete102015 (si177_sequencial);
alter table balancete122015 add constraint fk_balancete102015_si77_sequencial foreign key (si179_reg10) references balancete102015 (si177_sequencial);
alter table balancete132015 add constraint fk_balancete102015_si77_sequencial foreign key (si180_reg10) references balancete102015 (si177_sequencial);
alter table balancete142015 add constraint fk_balancete102015_si77_sequencial foreign key (si181_reg10) references balancete102015 (si177_sequencial);
alter table balancete152015 add constraint fk_balancete102015_si77_sequencial foreign key (si182_reg10) references balancete102015 (si177_sequencial);
alter table balancete162015 add constraint fk_balancete102015_si77_sequencial foreign key (si183_reg10) references balancete102015 (si177_sequencial);
alter table balancete172015 add constraint fk_balancete102015_si77_sequencial foreign key (si184_reg10) references balancete102015 (si177_sequencial);
alter table balancete182015 add constraint fk_balancete102015_si77_sequencial foreign key (si185_reg10) references balancete102015 (si177_sequencial);
alter table balancete202015 add constraint fk_balancete102015_si77_sequencial foreign key (si187_reg10) references balancete102015 (si177_sequencial);
alter table balancete212015 add constraint fk_balancete102015_si77_sequencial foreign key (si188_reg10) references balancete102015 (si177_sequencial);
alter table balancete222015 add constraint fk_balancete102015_si77_sequencial foreign key (si189_reg10) references balancete102015 (si177_sequencial);
commit;