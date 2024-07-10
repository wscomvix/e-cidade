/**
 * Arquivo pre up
 */

----------------------------------------------------
---- TIME FOLHA DE PAGAMENTO
----------------------------------------------------
---- Tarefa: 93695
----------------------------------------------------

insert into db_sysarquivo values (3724, 'rubricadescontoconsignado', 'Tabela que grava a ordem de descontos consignados.', 'rh140', '2014-08-05', 'Rubrica Desconto Consignado', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,3724);
insert into db_syscampo values(20691,'rh140_sequencial','int8','Sequencial da tabela rubricadescontoconsignado.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20692,'rh140_rubric','char(4)','Rubrica','', 'Rubrica',4,'f','f','f',3,'text','Rubrica');
insert into db_syscampo values(20693,'rh140_instit','int4','Instituio','0', 'Instituio',10,'f','f','f',1,'text','Instituio');
insert into db_syscampo values(20694,'rh140_ordem','int4','Numerao de ordem de rubrica consignada.','0', 'Ordem',10,'f','f','f',1,'text','Ordem');
insert into db_sysarqcamp values(3724,20691,1,0);
insert into db_sysarqcamp values(3724,20692,2,0);
insert into db_sysarqcamp values(3724,20693,3,0);
insert into db_sysarqcamp values(3724,20694,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3724,20691,1,20691);
insert into db_sysforkey values(3724,20692,1,1177,0);
insert into db_sysforkey values(3724,20693,2,1177,0);
insert into db_syssequencia values(1000384, 'rubricadescontoconsignado_rh140_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000384 where codarq = 3724 and codcam = 20691;
insert into db_sysindices values(4099,'rubricadescontoconsignado_rubric_in',3724,'1');
insert into db_syscadind values(4099,20692,1);

--Altera��o de labels
update db_syscampo set nomecam = 'r11_fer13', conteudo = 'varchar(4)', descricao = 'Rubrica onde � pago o 1/3 de f�rias.', valorinicial = '', rotulo = '1/3 de F�rias', nulo = 't', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = '1/3 de F�rias' where codcam = 3795;
update db_syscampo set nomecam = 'r11_ferabo', conteudo = 'varchar(4)', descricao = 'Rubrica onde � pago o abono de f�rias', valorinicial = '', rotulo = 'Abono de F�rias', nulo = 't', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Abono de F�rias' where codcam = 3799;
update db_syscampo set nomecam = 'r11_fer13a', conteudo = 'varchar(4)', descricao = 'Rubrica onde � pago o 1/3 sobre abono de f�rias', valorinicial = '', rotulo = '1/3 Abono de F�rias', nulo = 't', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = '1/3 Abono de F�rias' where codcam = 3798;
update db_syscampo set nomecam = 'r11_feradi', conteudo = 'varchar(4)', descricao = 'Rubrica onde � pago o adiantamento de f�rias', valorinicial = '', rotulo = 'Adiantamento de F�rias', nulo = 't', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Adiantamento de F�rias' where codcam = 3801;
update db_syscampo set nomecam = 'r11_fadiab', conteudo = 'varchar(4)', descricao = 'Rubrica onde ser� lan�ado o adiantamento sobre abono de f�rias', valorinicial = '', rotulo = 'Adiantamento Abono de F�rias', nulo = 't', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Adiantamento Abono de F�rias' where codcam = 3802;
update db_syscampo set nomecam = 'r11_ferant', conteudo = 'varchar(4)', descricao = 'Rubrica onde � descontado as f�rias pagas no m�s anterior', valorinicial = '', rotulo = 'F�rias M�s Anterior', nulo = 't', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'F�rias M�s Anterior' where codcam = 3796;
update db_syscampo set nomecam = 'r11_desliq', conteudo = 'varchar(20)', descricao = 'C�digos que ser�o calculados baseados no l�quido da folha. Dever� ser informado o percentual no ponto.', valorinicial = '', rotulo = 'C�digos Sobre o L�quido', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'C�digos Sobre o L�quido' where codcam = 4581;
update db_syscampo set nomecam = 'r11_rubpgintegral', conteudo = 'varchar(32)', descricao = 'Paga Valor Integral', valorinicial = '', rotulo = 'Pagamento Valor Integral', nulo = 't', tamanho = 32, maiusculo = 'f', autocompl = 'f', aceitatipo = 3, tipoobj = 'text', rotulorel = 'Pagamento Valor Integral' where codcam = 9437;
update db_syscampo set descricao = 'M�s do Exerc�cio', valorinicial = '0', rotulo = 'M�s do Exerc�cio', nulo = 'f', tamanho = 2, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'M�s do Exerc�cio' where codcam = 3687;
update db_syscampo set descricao = 'C�lculo pela Quantidade (s/n)', rotulo = 'C�lculo pela Quantidade (s/n)', rotulorel = 'Quant' where codcam = 3690;
update db_syscampo set descricao = 'Pesquisa Valores M�s Anterior', rotulo = 'Pesquisa Valores M�s Anterior', rotulorel = 'M�s Ant' where codcam = 3691;
update db_syscampo set descricao = 'C�digo da Institui��o', rotulo = 'C�digo da Institui��o', rotulorel = ' C�digo da Institui��o' where codcam = 7633;
update db_syscampo set descricao = 'Endere�o da Institui��o', rotulo = 'Endere�o da Institui��o', rotulorel = 'Endere�o da Institui��o' where codcam = 451;
update db_syscampo set descricao = 'Munic�pio da Institui��o', rotulo = 'Munic�pio da Institui��o', rotulorel = 'Munic�pio da Institui��o' where codcam = 452;

--Adicionando campo baseconsignada na tabela cfpess
insert into db_syscampo values(20695,'r11_baseconsignada','char(4)','Margem de abatimento consignado.','', 'Base Consignada',4,'t','f','f',3,'text','Base Consignada');
insert into db_sysarqcamp values(536,20695,82,0);
insert into db_sysforkey values(536,3758,1,530,0);
insert into db_sysforkey values(536,3759,2,530,0);
insert into db_sysforkey values(536,20695,3,530,0);
insert into db_sysforkey values(536,9892,4,530,0);

--Alterado label tabela db_config
update db_syscampo set nomecam = 'codigo', conteudo = 'int4', descricao = 'C�digo da Institui��o', valorinicial = '0', rotulo = 'C�digo da Institui��o', nulo = 'f', tamanho = 2, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'C�digo da Institui��o' where codcam = 449;
update db_syscampo set nomecam = 'db21_tipopoder', conteudo = 'int4', descricao = 'Poder', valorinicial = '6', rotulo = 'Tipo de Poder', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Poder' where codcam = 17759;
insert into db_syscampodef values(17759,'4','Minist�rio P�blico');
insert into db_syscampodef values(17759,'5','Tribunal de Contas');
insert into db_syscampodef values(17759,'6','Outros');

----------------------------------------------------
---- Tarefa: 96909 - Folha Complementar
----------------------------------------------------

--Altera��o de labels
update db_syscampo set nomecam = 'rh27_rubric', conteudo = 'char(4)', descricao = 'C�digo da Rubrica', valorinicial = '', rotulo = 'Rubrica', nulo = 'f', tamanho = 4, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Rubrica' where codcam = 7104;

--Tipo Folha
insert into db_sysarquivo values (3728, 'rhtipofolha', 'Tabela de relacionamento com a folha de pagamento.', 'rh142', '2014-08-21', 'Tipo da Folha', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,3728);
insert into db_syscampo values(20715,'rh142_sequencial','int4','Sequencial do tipo de folha.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20716,'rh142_descricao','varchar(100)','Descri��o referente ao tipo da folha.','', 'Descri��o',100,'f','f','f',0,'text','Descri��o');

delete from db_syscampodep where codcam = 20716;
delete from db_syscampodef where codcam = 20716;
insert into db_syscampodef values(20716,'13� Sal�rio','');
insert into db_syscampodef values(20716,'Adiantamento','');
insert into db_syscampodef values(20716,'Complementar','');
insert into db_syscampodef values(20716,'Rescis�o','');
insert into db_syscampodef values(20716,'Sal�rio','');

insert into db_sysarqcamp values(3728,20715,1,0);
insert into db_sysarqcamp values(3728,20716,2,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3728,20715,1,20715);
insert into db_syssequencia values(1000389, 'rhtipofolha_rh142_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000389 where codarq = 3728 and codcam = 20715;

--Folha Pagamento
insert into db_sysarquivo values (3727, 'rhfolhapagamento', 'Tabela respons�vel por armazenar o status e o tipo da folha da compet�ncia atual.', 'rh141', '2014-08-21', 'Folha de Pagamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,3727);
insert into db_syscampo values(20706,'rh141_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20707,'rh141_codigo','int4','C�digo da folha de pagamentos.','0', 'N�mero',10,'f','f','f',1,'text','N�mero');
insert into db_syscampo values(20708,'rh141_anoref','int4','Ano de refer�ncia da folha de pagemento.','0', 'Ano de Refer�ncia',10,'f','f','f',1,'text','Ano de Refer�ncia');
insert into db_syscampo values(20709,'rh141_mesref','int4','M�s de refer�ncia da folha de pagamento.','0', 'M�s de Refer�ncia',10,'f','f','f',1,'text','M�s de Refer�ncia');
insert into db_syscampo values(20710,'rh141_anousu','int4','Ano do pagamento da folha.','0', 'Ano',10,'f','f','f',1,'text','Ano');
insert into db_syscampo values(20711,'rh141_mesusu','int4','M�s do pagamento da folha.','0', 'M�s',10,'f','f','f',1,'text','M�s');
insert into db_syscampo values(20712,'rh141_instit','int4','C�digo da institui��o que foi gerado da folha de pagamento.','0', 'Institui��o',10,'f','f','f',1,'text','Institui��o');
insert into db_syscampo values(20713,'rh141_tipofolha','int4','Tipo da folha de pagamento.','0', 'Tipo da Folha',10,'f','f','f',1,'text','Tipo da Folha');
insert into db_syscampo values(20714,'rh141_status','bool','Status da folha de pagamento. True -> Folha est� aberta. False -> Folha est� fechada.','f', 'Status',1,'f','f','f',5,'text','Status');
insert into db_syscampo values(20717,'rh141_descricao','text','Descri��o da folha de pagamento.','', 'Descri��o',1,'f','f','f',0,'text','Descri��o');

insert into db_sysarqcamp values(3727,20706,1,0);
insert into db_sysarqcamp values(3727,20707,2,0);
insert into db_sysarqcamp values(3727,20708,3,0);
insert into db_sysarqcamp values(3727,20709,4,0);
insert into db_sysarqcamp values(3727,20710,5,0);
insert into db_sysarqcamp values(3727,20711,6,0);
insert into db_sysarqcamp values(3727,20712,7,0);
insert into db_sysarqcamp values(3727,20713,8,0);
insert into db_sysarqcamp values(3727,20714,9,0);
insert into db_sysarqcamp values(3727,20717,10,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3727,20706,1,20706);
insert into db_sysforkey values(3727,20713,1,3728,0);
insert into db_syssequencia values(1000390, 'rhfolhapagamento_rh141_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000390 where codarq = 3727 and codcam = 20706;
insert into db_sysindices values(4104,'rhfolhapagamento_sequencial_anousu_mesusu_instit_in',3727,'0');
insert into db_syscadind values(4104,20706,1);
insert into db_syscadind values(4104,20710,2);
insert into db_syscadind values(4104,20711,3);
insert into db_syscadind values(4104,20712,4);
insert into db_sysindices values(4105,'rhfolhapagamento_sequencial_in',3727,'0');
insert into db_syscadind values(4105,20706,1);

--Historico Calculo
insert into db_sysarquivo values (3729, 'rhhistoricocalculo', 'Tabela armazena o hist�rio do c�lculo da folha de pagamento.', 'rh143', '2014-08-21', 'Hist�rico do C�lculo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,3729);
insert into db_syscampo values(20718,'rh143_sequencial','int4','Sequencial da tabela \"rhhistoricocalculo\".','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20719,'rh143_folhapagamento','int4','Este campo identifica a folha de pagamento do hist�rico do c�lculo.','0', 'Folha de Pagamento',10,'f','f','f',1,'text','Folha de Pagamento');
insert into db_syscampo values(20720,'rh143_rubrica','char(4)','C�digo da rubrica.','', 'Rubrica',4,'f','f','f',3,'text','Rubrica');
insert into db_syscampo values(20721,'rh143_quantidade','float8','Quantidade da rubrica do hist�rico do c�lculo.','0', 'Quantidade',10,'f','f','f',4,'text','Quantidade');
insert into db_syscampo values(20722,'rh143_valor','float8','Valor da rubrica do hist�rico do c�lculo.','0', 'Valor da Rubrica',10,'f','f','f',4,'text','Valor da Rubrica');
insert into db_syscampo values(20730,'rh143_regist',    'int4','Vincula o hist�rico do c�lculo com o Servidor','0', 'Matr�cula do Servidor',10,'f','f','f',1,'text','Matr�cula do Servidor');
insert into db_syscampo values(20735,'rh143_tipoevento','int4','Tipo de Evento gravado no hist�rico',           '', 'Tipo de Evento',       10,'f','f','f',1,'text','Tipo de Evento');

insert into db_sysarqcamp values(3729,20718,1,1000392);
insert into db_sysarqcamp values(3729,20730,2,0);
insert into db_sysarqcamp values(3729,20719,3,0);
insert into db_sysarqcamp values(3729,20720,4,0);
insert into db_sysarqcamp values(3729,20721,5,0);
insert into db_sysarqcamp values(3729,20722,6,0);
insert into db_sysarqcamp values(3729,20735,7,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3729,20718,1,20718);
insert into db_syssequencia values(1000392, 'rhhistoricocalculo_rh143_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000392 where codarq = 3729 and codcam = 20718;
insert into db_sysforkey values(3729,20719,1,3727,0);
insert into db_sysindices values(4107,'rhhistoricocalculo_folhapagamento_in',3729,'0');
insert into db_sysindices values(4109,'rhhistoricocalculo_regist_in',3729,'0');
insert into db_syscadind values(4107,20719,1);
insert into db_syscadind values(4109,20730,1);

--Historico Ponto
insert into db_sysarquivo values (3730, 'rhhistoricoponto', 'Tabela que armazena o hist�rico do ponto da folha de pagamento.', 'rh144', '2014-08-21', 'Hist�rico do Ponto', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,3730);
insert into db_syscampo values(20723,'rh144_sequencial','int4','Sequencial da tabela \"rhhistoricoponto\".','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(20731,'rh144_regist','int4','Faz a liga��o do hist�rico do ponto para um servidor','0', 'Matr�cula do Servidor',10,'f','f','f',1,'text','Matr�cula do Servidor');
insert into db_syscampo values(20724,'rh144_folhapagamento','int4','Este campo identifica a folha de pagamento do hist�rico do ponto.','0', 'Folha de Pagamento',10,'f','f','f',1,'text','Folha de Pagamento');
insert into db_syscampo values(20725,'rh144_rubrica','char(4)','C�digo da rubrica.','', 'Rubrica',4,'f','f','f',3,'text','Rubrica');
insert into db_syscampo values(20726,'rh144_quantidade','float8','Quantidade da rubrica do hist�rico do ponto.','0', 'Quantidade da Rubrica',10,'f','f','f',4,'text','Quantidade da Rubrica');
insert into db_syscampo values(20727,'rh144_valor','float8','Valor da rubrica do hist�rico do ponto.','0', 'Valor da Rubrica',10,'f','f','f',4,'text','Valor da Rubrica');
insert into db_sysarqcamp values(3730,20723,1,1000391);
insert into db_sysarqcamp values(3730,20731,2,0);
insert into db_sysarqcamp values(3730,20724,3,0);
insert into db_sysarqcamp values(3730,20725,4,0);
insert into db_sysarqcamp values(3730,20726,5,0);
insert into db_sysarqcamp values(3730,20727,6,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3730,20723,1,20723);
insert into db_syssequencia values(1000391, 'rhhistoricoponto_rh144_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000391 where codarq = 3730 and codcam = 20723;
insert into db_sysforkey values(3730,20724,1,3727,0);
insert into db_sysindices values(4108,'rhhistoricoponto_folhapagamento_in',3730,'0');
insert into db_sysindices values(4110,'rhhistoricoponto_regist_in',3730,'0');
insert into db_syscadind values(4108,20724,1);
insert into db_syscadind values(4110,20731,1);

/**
 * Cria��o de Menus
 */
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values (9958, 'Manuten��o de Folha Complementar', 'Manuten��o de Folha Complementar','' ,'1' ,'1' ,'Esse menu agrupa os submenus: Abertura; Fechamento; Cancelamento da abertura; Cancelamento do fechamento.' ,'true'),
       (9959, 'Manuten��o da Folha Sal�rio',      'Manuten��o da Folha Sal�rio',     '' ,'1' ,'1' ,'Esse menu agrupa os seguintes itens: Fechamento; Cancelamento do Fechamento.' ,'true'),
       (9960, 'Abertura',                         'Abertura',                        'pes4_aberturacomplementar001.php',         '1' ,'1', 'Abertura da folha complementar.' ,'true'),
       (9961, 'Fechamento',                       'Fechamento',                      'pes4_fechamentocomplementar001.php',       '1' ,'1', 'Fechamento da folha complementar.' ,'true'),
       (9962, 'Cancelar Abertura',                'Cancelar Abertura',               'pes4_cancelaaberturacomplementar001.php',  '1' ,'1', 'Cancelamento de abertura da folha complementar.' ,'true'),
       (9963, 'Cancelar Fechamento',              'Cancelar Fechamento',             'pes4_cancelafechamentocomplementar001.php','1' ,'1', 'Cancelamento do fechamento da folha complementar.' ,'true'),
       (9964, 'Fechamento',                       'Fechamento',                      'pes4_fechamentosalario001.php' ,           '1' ,'1', 'Fechamento da folha sal�rio.' ,'true'),
       (9965, 'Cancelar Fechamento',              'Cancelar Fechamento',             'pes4_cancelafechamentosalario001.php',     '1', '1', 'Cancelamento do fechamento da folha sal�rio.', 'true');
/**
 * Organiza��o dos menus
 */
insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values (1818 ,9958 ,102, 952),
       (1818 ,9959 ,103, 952),
       (9958 ,9960 ,1,   952),
       (9958 ,9961 ,2,   952),
       (9958 ,9962 ,3,   952),
       (9958 ,9963 ,4,   952),
       (9959 ,9964 ,1,   952),
       (9959 ,9965 ,2,   952);

update db_menu set menusequencia = 1  where id_item = 1818 and modulo = 952 and id_item_filho = 5016;
update db_menu set menusequencia = 2  where id_item = 1818 and modulo = 952 and id_item_filho = 9767;
update db_menu set menusequencia = 3  where id_item = 1818 and modulo = 952 and id_item_filho = 5050;
update db_menu set menusequencia = 4  where id_item = 1818 and modulo = 952 and id_item_filho = 5047;
update db_menu set menusequencia = 5  where id_item = 1818 and modulo = 952 and id_item_filho = 5112;
update db_menu set menusequencia = 6  where id_item = 1818 and modulo = 952 and id_item_filho = 5156;
update db_menu set menusequencia = 7  where id_item = 1818 and modulo = 952 and id_item_filho = 4504;
update db_menu set menusequencia = 8  where id_item = 1818 and modulo = 952 and id_item_filho = 9958;
update db_menu set menusequencia = 9  where id_item = 1818 and modulo = 952 and id_item_filho = 9959;
update db_menu set menusequencia = 10 where id_item = 1818 and modulo = 952 and id_item_filho = 5036;
update db_menu set menusequencia = 11 where id_item = 1818 and modulo = 952 and id_item_filho = 5005;
update db_menu set menusequencia = 12 where id_item = 1818 and modulo = 952 and id_item_filho = 4755;
update db_menu set menusequencia = 13 where id_item = 1818 and modulo = 952 and id_item_filho = 5106;
update db_menu set menusequencia = 14 where id_item = 1818 and modulo = 952 and id_item_filho = 5110;
update db_menu set menusequencia = 15 where id_item = 1818 and modulo = 952 and id_item_filho = 8815;
update db_menu set menusequencia = 16 where id_item = 1818 and modulo = 952 and id_item_filho = 5204;
update db_menu set menusequencia = 17 where id_item = 1818 and modulo = 952 and id_item_filho = 5280;
update db_menu set menusequencia = 18 where id_item = 1818 and modulo = 952 and id_item_filho = 5305;
update db_menu set menusequencia = 19 where id_item = 1818 and modulo = 952 and id_item_filho = 5234;
update db_menu set menusequencia = 20 where id_item = 1818 and modulo = 952 and id_item_filho = 5136;
update db_menu set menusequencia = 21 where id_item = 1818 and modulo = 952 and id_item_filho = 3516;
update db_menu set menusequencia = 22 where id_item = 1818 and modulo = 952 and id_item_filho = 331384;
update db_menu set menusequencia = 23 where id_item = 1818 and modulo = 952 and id_item_filho = 782400;
update db_menu set menusequencia = 24 where id_item = 1818 and modulo = 952 and id_item_filho = 5196;
update db_menu set menusequencia = 25 where id_item = 1818 and modulo = 952 and id_item_filho = 7150;
update db_menu set menusequencia = 26 where id_item = 1818 and modulo = 952 and id_item_filho = 7570;
update db_menu set menusequencia = 27 where id_item = 1818 and modulo = 952 and id_item_filho = 7684;
update db_menu set menusequencia = 28 where id_item = 1818 and modulo = 952 and id_item_filho = 8679;
update db_menu set menusequencia = 29 where id_item = 1818 and modulo = 952 and id_item_filho = 8806;
update db_menu set menusequencia = 30 where id_item = 1818 and modulo = 952 and id_item_filho = 8827;
update db_menu set menusequencia = 31 where id_item = 1818 and modulo = 952 and id_item_filho = 9514;
update db_menu set menusequencia = 32 where id_item = 1818 and modulo = 952 and id_item_filho = 9793;

--Valida��o do campo regist alterado.
update db_syscampo set aceitatipo = 1 where codcam = 4325;
update db_syscampo set aceitatipo = 1 where codcam = 7024;

---------------------
-- T96970
---------------------
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20737 ,'r11_abonoprevidencia' ,'varchar(4)' ,'Rubrica para abono de perman�ncia de INSS.' ,'' ,'Rubrica Abono de Perman�ncia' ,4 ,'true' ,'false' ,'false' ,0 ,'text' ,'Rubrica Abono de Perman�ncia' );
delete from db_syscampodef where codcam = 20737;
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 536 ,20737 ,83 ,0 );

----------------------------------------------------
---- Tarefa: Acerto Redmine Issue: 102184
----------------------------------------------------
update db_syscampo set nomecam = 'rh02_seqpes', conteudo = 'int4', descricao = 'Sequ�ncia do cadastro de pessoal.', valorinicial = '0', rotulo = 'Sequ�ncia', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequ�ncia' where codcam = 7021;

----------------------------------------------------
---- Tarefa: Acerto Redmine Issue: 102490
----------------------------------------------------
update db_syscampo set nomecam = 'rh31_irf', conteudo = 'varchar(1)', descricao = 'IRF: 0-N�o Dependente;\n 1-C�njuge;\n 2-Filho(a) at� 21 anos;\n 3- Filho(a) ou enteado(a) at� 24 anos em curso universit�rio ou t�cnico de segundo grau;\n 4-Irm�o(�), neto(a), bisneto(a) at� 21 anos;\n 5- Irm�o(�), neto(a), bisneto(a), com 21 � 24 anos;\n 6-Pais ,av�s, bisav�s;\n 7- Menor pobre at� 21 anos;\n 8-Absolutamente incapaz.', valorinicial = '', rotulo = 'IRF', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'IRF' where codcam = 7155;

----------------------------------------------------
---- TIME FINANCEIRO {
----------------------------------------------------

insert into db_sysarquivo values (3726, 'conlancamdata', 'conlancamdata', 'c03', '2014-08-11', 'conlancamdata', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,3726);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20702 ,'c03_sequencial' ,'int4' ,'C�digo' ,'' ,'C�digo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'C�digo' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20703 ,'c03_codlan' ,'int4' ,'C�digo do Lan�amento' ,'' ,'C�digo do Lan�amento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'C�digo do Lan�amento' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20704 ,'c03_data' ,'date' ,'Data' ,'' ,'Data' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3726 ,20704 ,3 ,0 );
insert into db_syssequencia values(1000386, 'conlancamdata_c03_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_syssequencia set nomesequencia = 'conlancamdata_c03_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1000386;
update db_sysarquivo set nomearq = 'conlancamordem', descricao = 'conlancamordem', sigla = 'c03', dataincl = '2014-08-11', rotulo = 'conlancamordem', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 3726;
insert into db_sysarqarq values(0,3726);
update db_syscampo set codcam = 20702 , nomecam = 'c03_sequencial' , conteudo = 'int4' , descricao = 'C�digo' , rotulo = 'C�digo' , tamanho = 10 , nulo = 'false' , maiusculo = 'false' , autocompl = 'false' , aceitatipo = 1 , tipoobj = 'text' , rotulorel = 'C�digo' where codcam = 20702;
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3726 ,20702 ,1 ,1000386 );
update db_syscampo set codcam = 20703 , nomecam = 'c03_codlan' , conteudo = 'int4' , descricao = 'C�digo do Lan�amento' , rotulo = 'C�digo do Lan�amento' , tamanho = 10 , nulo = 'false' , maiusculo = 'false' , autocompl = 'false' , aceitatipo = 1 , tipoobj = 'text' , rotulorel = 'C�digo do Lan�amento' where codcam = 20703;
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3726 ,20703 ,2 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20705 ,'c03_ordem' ,'int4' ,'Ordem' ,'' ,'Ordem' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ordem' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3726 ,20705 ,3 ,0 );
update db_sysarqcamp set codsequencia = 0 where codsequencia = 1000386;
insert into db_syssequencia values(1000387, 'conlancamordem_c03_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000387 where codarq = 3726 and codcam = 20702;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3726,20703,1,20702);
insert into db_sysforkey values(3726,20703,1,760,0);
insert into db_sysindices values(4103,'conlancamordem_c03_codlan_in',3726,'0');
insert into db_syscadind values(4103,20703,1);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3726,20702,1,20703);

insert into contacorrente (c17_sequencial, c17_contacorrente, c17_descricao) values
  (100, 'CC 100', 'RECEITA OR�AMENT�RIA'),
  (101, 'CC 101', 'DOTA��O'),
  (102, 'CC 102', 'DESPESA OR�AMENT�RIA'),
  (103, 'CC 103', 'FONTE DE RECURSO'),
  (104, 'CC 104', 'CREDOR'),
  (105, 'CC 105', 'MOVIMENTA��O FINANCEIRA'),
  (106, 'CC 106', 'RESTOS A PAGAR'),
  (107, 'CC 107', 'CONTRATOS PASSIVOS'),
  (108, 'CC 108', 'CONTRATOS')
;

insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
  values ( 20732 ,'c19_estrutural' ,'varchar(15)' ,'Estrutural' ,'null' ,'Estrutural' ,15 ,'true' ,'false' ,'false' ,0 ,'text' ,'Estrutural' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3492 ,20732 ,17 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
  values ( 20733 ,'c19_orcdotacao' ,'int4' ,'Orcdota��o' ,'null' ,'Orcdota��o' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Orcdota��o' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3492 ,20733 ,18 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
  values ( 20734 ,'c19_orcdotacaoanousu' ,'int4' ,'Orcdota��o Ano' ,'null' ,'Orcdota��o Ano' ,4 ,'true' ,'false' ,'false' ,1 ,'text' ,'Orcdota��o Ano' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3492 ,20734 ,19 ,0 );

insert into db_sysforkey values(3492,20734,1,758,0);
insert into db_sysforkey values(3492,20733,2,758,0);

insert into db_itensmenu values( 9966, 'TCE/AC', 'TCE/AC', '', '1', '1', 'TCE/AC', '1'	);
insert into db_itensmenu values( 9967, 'Vincula��o do Plano de Contas', 'Vincula��o do Plano de Contas', 'con4_vinculacaotceac001.php?tipo=1', '1', '1', 'Vincula��o do Plano de Contas', '1'	);
insert into db_itensfilho (id_item, codfilho) values(9967,1);
insert into db_menu values(6819,9966,5,209);

insert into db_itensmenu values( 9968, 'Gerar Arquivos', 'Gera��o de arquivos para o TCE do Acre', 'con4_gerararquivostceacre001.php', '1', '1', '', '1'	);
insert into db_itensfilho (id_item, codfilho) values(9968,1);
insert into db_menu values(9966,9968,1,209);
update db_menu set menusequencia = 1 where id_item = 9966 and modulo = 209 and id_item_filho = 9968;

insert into db_itensmenu values( 9969, 'Vincula��o de Recursos', 'Vincula��o de Recursos', 'con4_vinculacaotceac001.php?tipo=2', '1', '1', 'Vincula��o de Recursos', '1'	);
insert into db_itensfilho (id_item, codfilho) values(9969,1);
insert into db_itensmenu values( 9970, 'Vincula��o de Documentos', 'Vincula��o de Documentos', 'con4_vinculacaotceac001.php?tipo=3', '1', '1', 'Vincula��o de Documentos', '1'	);
insert into db_itensfilho (id_item, codfilho) values(9970,1);
insert into db_itensmenu values( 9971, 'Configura��es', 'Configura��es', '', '1', '1', 'Importa��o dos arquivos de configura��o', '1'	);
insert into db_menu values(9966,9971,2,209);
insert into db_menu values(9971,9967,1,209);
insert into db_menu values(9971,9970,2,209);
insert into db_menu values(9971,9969,3,209);


/**
 * Inicio Tabela tipodespacho
 */
insert into db_sysarquivo values (3732, 'tipodespacho', 'tipodespacho', 'p100', '2014-09-15', 'tipodespacho', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,3732);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20746 ,'p100_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );
delete from db_syscampodef where codcam = 20746;
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3732 ,20746 ,1 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20747 ,'p100_descricao' ,'varchar(20)' ,'Descri��o' ,'' ,'Descri��o' ,20 ,'false' ,'true' ,'false' ,0 ,'text' ,'Descri��o' );
delete from db_syscampodef where codcam = 20747;
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3732 ,20747 ,2 ,0 );
insert into db_syssequencia values(1000394, 'tipodespacho_p100_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000394 where codarq = 3732 and codcam = 20746;
delete from db_sysprikey where codarq = 3732;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3732,20746,1,20747);
insert into db_sysindices values(4112,'tipodespacho_sequencial_in',3732,'0');
insert into db_syscadind values(4112,20746,1);

update db_syscampo SET rotulo = 'C�digo do Usu�rio' where codcam = 6450;
update db_syscampo SET rotulo = 'Nome do Usu�rio' where codcam = 570;
update db_syscampo SET rotulo = 'C�digo do Andamento' where codcam = 6447;
update db_syscampo SET rotulo = 'Usu�rio' where codcam = 6868;
update db_syscampo SET rotulo = 'Departamento' where codcam = 6880;



CREATE SEQUENCE tipodespacho_p100_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE tipodespacho(
p100_sequencial  int4 NOT NULL  default nextval('tipodespacho_p100_sequencial_seq'),
p100_descricao   varchar(20) not null,
CONSTRAINT tipodespacho_sequ_pk PRIMARY KEY (p100_sequencial));

CREATE INDEX tipodespacho_sequencial_in ON tipodespacho(p100_sequencial);
insert into tipodespacho values (1, 'Despacho');

/**
 * Fim Tabela tipodespacho
 */
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20751 ,'p78_tipodespacho' ,'int4' ,'Tipo de Despacho' ,'' ,'Tipo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo de Despacho' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1059 ,20751 ,9 ,0 );
insert into db_sysforkey values(1059,20751,1,3732,0);

alter table procandamint add column p78_tipodespacho int default 1;
update procandamint set p78_tipodespacho = 1;

ALTER TABLE procandamint
ADD CONSTRAINT procandamint_tipodespacho_fk FOREIGN KEY (p78_tipodespacho)
REFERENCES tipodespacho;

update db_syscampo set nomecam = 'm40_codigo', conteudo = 'int8', descricao = 'C�digo da Requisi��o', valorinicial = '0', rotulo = 'C�digo da Requisi��o', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'C�digo da Requisi��o' where codcam = 6865;
update db_syscampo set nomecam = 'm42_codigo', conteudo = 'int8', descricao = 'C�digo de Atendimento', valorinicial = '0', rotulo = 'C�digo de Atendimento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'C�digo de Atendimento' where codcam = 6876;
update db_syscampo set nomecam = 'm40_login', conteudo = 'int4', descricao = 'C�digo do usu�rio', valorinicial = '0', rotulo = 'Usu�rio', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Cod. Usu�rio' where codcam = 6868;
update db_syscampo set nomecam = 'id_usuario', conteudo = 'int4', descricao = 'C�digo do Usu�rio', valorinicial = '0', rotulo = 'C�digo do Usu�rio', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Cod. Usu�rio' where codcam = 568;
update db_syscampo set nomecam = 'login', conteudo = 'varchar(20)', descricao = 'Login do Usu�rio', valorinicial = '', rotulo = 'Login do Usu�rio', nulo = 'f', tamanho = 20, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Login do Usu�rio' where codcam = 571;

----------------------------------------------------
---- } FIM TIME FINANCEIRO
----------------------------------------------------


----------------------------------------------------
---- TIME C
----------------------------------------------------
update db_sysarqmod set codmod = 1000004 where codmod = 1008004 and codarq = 2556;

update db_syscampo set nomecam = 's103_c_emitirfaa', conteudo = 'char(1)', descricao = 'Gerar FA Autom�tica', valorinicial = 'N', rotulo = 'Gerar FA Autom�tica', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Gerar FA Autom�tica' where codcam = 14590;
delete from db_syscampodep where codcam = 14590;
delete from db_syscampodef where codcam = 14590;
update db_syscampo set nomecam = 's103_i_todacomp', conteudo = 'int4', descricao = 'Campo para que o usu�rio selecione se deseja apresentar os procedimento com a �ltima compet�ncia ou n�o. 2-N�O(default) 1-SIM', valorinicial = '0', rotulo = 'Apresentar Todas Compet�ncias', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Apresentar Todas Compet�ncias' where codcam = 18471;
delete from db_syscampodep where codcam = 18471;
delete from db_syscampodef where codcam = 18471;

----------------------------------------------------
---- Tarefa: 95483
----------------------------------------------------

insert into db_syscampo values(20729,'z01_codigoibge','varchar(50)','C�digo do municipio segundo o IBGE','', 'C�digo IBGE',50,'true','f','f',1,'text','C�digo IBGE');
insert into db_sysarqcamp values(1010144, 20729, 78, 0);

update db_syscampo set maiusculo = 'f', aceitatipo = 1 where codcam = 1008854;
update db_syscampo set maiusculo = 'f', aceitatipo = 1 where codcam = 1008857;
update db_syscampo set maiusculo = 'f', aceitatipo = 1 where codcam = 1008864;

----------------------------------------------------
---- Tarefa: 95540
----------------------------------------------------
update db_itensmenu set libcliente = false where id_item in (8887, 8888, 8889, 8890, 8891, 8892, 8893, 8894);
update db_syscampo set conteudo = 'float8' where codcam in (18218, 18219, 18220, 18221);


insert into db_listadump
values ( 474, 'grupotaxa', 'delete from grupotaxa;', 'ALTER TABLE grupotaxa DISABLE TRIGGER ALL;', 'ALTER TABLE grupotaxa ENABLE TRIGGER ALL;', true);


ALTER TABLE grupotaxa DISABLE TRIGGER ALL;
DELETE FROM grupotaxa;
insert into grupotaxa values  (1, 1, 'CUSTAS PROCESSUAIS');
insert into grupotaxa values  (2, 1, 'CUSTAS ADICIONAIS');
ALTER TABLE grupotaxa ENABLE TRIGGER ALL;

select setval('grupotaxa_ar37_sequencial_seq', 2);


----------------------------------------------------
---- SAUDE
----------------------------------------------------
---- Tarefa: 95051
----------------------------------------------------
insert into db_syscampo values(20738,'la30_casasdecimaisapresentacao','int4','Casas Decimais para Apresenta��o dos valores','0', 'Casas Decimais para Apresenta��o',10,'t','f','f',1,'text','Casas Decimais para Apresenta��o');
insert into db_sysarqcamp values(2905,20738,8,0);


-------------------------------------------------------------------------------
--                                INTEGRACAO                                 --
-------------------------------------------------------------------------------

insert into db_sysarquivo values (3725, 'db_releasenotes', 'Tabela de verifica��o de leitura do changelog por usu�rios', 'db147', '2014-08-06', 'Changelog', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (7,3725);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20696 ,'db147_sequencial' ,'int4' ,'C�digo do Changelog' ,'' ,'C�digo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'C�digo' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3725 ,20696 ,1 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20697 ,'db147_nomearquivo' ,'varchar(255)' ,'Nome do arquivo' ,'' ,'Nome do arquivo' ,255 ,'false' ,'false' ,'false' ,1 ,'text' ,'Nome do arquivo' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3725 ,20697 ,2 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20698 ,'db147_db_versao' ,'int4' ,'C�digo da Vers�o' ,'' ,'C�digo da Vers�o' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'C�digo da Vers�o' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3725 ,20698 ,3 ,0 );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 20699 ,'db147_id_usuario' ,'int8' ,'C�digo do Usu�rio' ,'' ,'C�digo do Usu�rio' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'C�digo do Usu�rio' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3725 ,20699 ,4 ,0 );
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3725,20696,1,20696);
insert into db_sysforkey values(3725,20699,1,109,0);
insert into db_sysforkey values(3725,20698,1,939,0);
insert into db_sysindices values(4101,'db_releasenotes_id_usuario_in',3725,'0');
insert into db_syscadind values(4101,20699,1);
insert into db_sysindices values(4102,'db_releasenotes_db_versao_in',3725,'0');
insert into db_syscadind values(4102,20698,1);
insert into db_syssequencia values(1000385, 'db_releasenotes_db147_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000385 where codarq = 3725 and codcam = 20696;

