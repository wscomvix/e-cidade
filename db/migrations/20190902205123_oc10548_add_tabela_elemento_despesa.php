<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc10548AddTabelaElementoDespesa extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
        
        BEGIN;
        SELECT fc_startsession();
        
        -- CRIA TABELA ELEMENTO DESPESA

        -- INSERE db_sysarquivo
        INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'eledessiope', 'Elemento Despesa Siope', 'c223 ', '2019-09-02', 'Elemento Despesa Siope', 0, false, false, false, false);
         
        -- INSERE db_sysarqmod
        INSERT INTO db_sysarqmod (codmod, codarq) VALUES (32, (select max(codarq) from db_sysarquivo));
         
        -- INSERE db_syscampo
        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'c223_eledespecidade', 	'varchar(11) ', 'Siope', 		'', 'Siope', 	 11, false, true, false, 0, 'text', 'Siope');
        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'c223_descricao', 		'varchar(100)', 'Descri��o', 	'', 'Descri��o', 100, false, true, false, 0, 'text', 'Descri��o');
        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'c223_anousu', 		    'int4',         'Ano', 	        '', 'Ano',       4, false, true, false, 0, 'text', 'Ano');
         
        -- INSERE db_sysarqcamp
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'c223_eledespecidade'), 1, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'c223_descricao'), 2, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'c223_anousu'), 3, 0);
         
        --DROP TABLE:
        DROP TABLE IF EXISTS eledessiope CASCADE;

        -- CRIA TABELA
        CREATE TABLE eledessiope(c223_eledespecidade varchar(11) NOT NULL , c223_descricao varchar(100) NOT NULL, c223_anousu integer NOT NULL DEFAULT 0 );
        
        -- INSERE ELEMENTO DESPESA
        INSERT INTO eledessiope VALUES
        ('33000000000', 'DESPESAS CORRENTES', '2019'),
        ('33100000000', 'PESSOAL E ENCARGOS SOCIAIS', '2019'),
        ('33190000000', 'APLICA��ES DIRETAS', '2019'),
        ('33190010000', 'Aposentadorias', '2019'),
        ('33190030000', 'Pens�es', '2019'),
        ('33190040000', 'Contrata��o por Tempo Determinado', '2019'),
        ('33190040100', 'Sal�rio Contrato Tempor�rio', '2019'),
        ('33190040200', 'Sal�rio Fam�lia', '2019'),
        ('33190040300', 'Adicional Noturno de Contrato Tempor�rio', '2019'),
        ('33190040500', 'Adicional de Periculosidade de Contrato Tempor�rio', '2019'),
        ('33190040600', 'Adicional de Insalubridade Tempor�rio', '2019'),
        ('33190040700', 'Adicional de Ativ. Penosas - Contrato Tempor�rio', '2019'),
        ('33190041000', 'Serv. Extraordin�rios - Contrato Tempor�rio', '2019'),
        ('33190041100', 'Adicional noturno', '2019'),
        ('33190041200', 'F�rias Vencidas/Proporcionais - Contrato Tempor�rio', '2019'),
        ('33190041300', '13� Sal�rio - Contrato Tempor�rio', '2019'),
        ('33190041400', 'F�rias - Abono Constituicional - Contrato Tempor�rio', '2019'),
        ('33190041500', 'Obriga��es Patronais - Contrato por Tempo Determinado', '2019'),
        ('33190041600', 'F�rias Pagamento Antecipado - Contrato Tempor�rio', '2019'),
        ('33190041700', 'Indeniza��o', '2019'),
        ('33190049900', 'Outras Vantagens - Contratos Tempor�rios', '2019'),
        ('33190050000', 'Outros Benef�cios Previdenci�rios', '2019'),
        ('33190070000', 'Contribui��o a Entidades Fechadas de Previd�ncia', '2019'),
        ('33190080000', 'Outros Benef�cios Assistenciais', '2019'),
        ('33190090000', 'Sal�rio Fam�lia', '2019'),
        ('33190100000', 'Outros Benef�cios de Natureza Social', '2019'),
        ('33190110000', 'Vencimentos e Vantagens Fixas - Pessoal Civil', '2019'),
        ('33190110100', 'Vencimentos e Sal�rios', '2019'),
        ('33190110400', 'Adicional Noturno', '2019'),
        ('33190110500', 'Incorpora��es', '2019'),
        ('33190110600', 'Vantagens Perm. Sent. Jud. Trans. Julgado - Civil', '2019'),
        ('33190110700', 'Abono de Perman�ncia', '2019'),
        ('33190110900', 'Adicional de Periculosidade', '2019'),
        ('33190111000', 'Adicional de Insalubridade', '2019'),
        ('33190111100', 'Adicional de Atividades Penosas', '2019'),
        ('33190111300', 'Incentivo a Qualifica��o', '2019'),
        ('33190111400', 'Adicional de Transfer�ncia', '2019'),
        ('33190112200', 'Pr�-Labore', '2019'),
        ('33190112800', 'Vantagem Pecuni�ria Individual', '2019'),
        ('33190113000', 'Abono Provis�rio - Pessoal Civil', '2019'),
        ('33190113100', 'Gratifica��o por Exerc�cio de Cargo Efetivo', '2019'),
        ('33190113300', 'Gratifica��o por Exerc�cio de Fun��es Comissionadas', '2019'),
        ('33190113500', 'Indeniza��o de Localiza��o', '2019'),
        ('33190113600', 'Gratifica��o por Exerc�cio de Cargo em Comiss�o', '2019'),
        ('33190113700', 'Gratifica��o de Tempo de Servi�o', '2019'),
        ('33190114000', 'Gratifica��es Especiais', '2019'),
        ('33190114100', 'Gratifica��o por Atividades Expostas', '2019'),
        ('33190114200', 'F�rias Vencidas e Proporcionais', '2019'),
        ('33190114300', '13� Sal�rio', '2019'),
        ('33190114400', 'F�rias - Abono Pecuni�rio', '2019'),
        ('33190114500', 'F�rias - Abono Constitucional', '2019'),
        ('33190114600', 'F�rias - Pagamento Antecipado', '2019'),
        ('33190114700', 'Licen�a-Pr�mio', '2019'),
        ('33190114900', 'Licen�a Capacita��o', '2019'),
        ('33190115000', 'Vencim. e Sal. - Pror. Sal�rio Maternidade', '2019'),
        ('33190116000', 'Adicional - Teto Parlamentar', '2019'),
        ('33190117100', 'Remunera��o de Diretores', '2019'),
        ('33190117300', 'Remun. Particip. �rg�os Delibera��o Coletiva', '2019'),
        ('33190117400', 'Subs�dios', '2019'),
        ('33190117500', 'Representa��o Mensal', '2019'),
        ('33190117700', 'Remunera��o de Pessoal em Disponibilidade', '2019'),
        ('33190118700', 'Complementa��o Salarial - Pessoal Civil', '2019'),
        ('33190119900', 'Outras Despesas Fixas - Pessoal Civil', '2019'),
        ('33190120000', 'Vencimentos e Vantagens Fixas - Pessoal Militar', '2019'),
        ('33190130000', 'Obriga��es Patronais', '2019'),
        ('33190130100', 'FGTS', '2019'),
        ('33190130200', 'Contribui��es Previdenci�rias - INSS', '2019'),
        ('33190130300', 'Contribui��es Previdenci�rias - No Exterior', '2019'),
        ('33190130400', 'Contribui��o de Sal�rio-Educa��o', '2019'),
        ('33190130800', 'Plano de Seg. Soc. do Servidor - Pes. Ativo', '2019'),
        ('33190130900', 'Seguros de Acidentes do Trabalho', '2019'),
        ('33190131100', 'FGTS - PDV', '2019'),
        ('33190131400', 'Multas Indedut�veis', '2019'),
        ('33190131500', 'Multas Dedut�veis', '2019'),
        ('33190131700', 'Juros', '2019'),
        ('33190131800', 'Contribui��o para o PIS/PASEP s/ folha de pagamento', '2019'),
        ('33190134000', 'Encargos de pessoal requisitado de outros entes', '2019'),
        ('33190139900', 'Outras obriga��es patronais', '2019'),
        ('33190140000', 'Di�rias - Civil', '2019'),
        ('33190150000', 'Di�rias - Militar', '2019'),
        ('33190160000', 'Outras Despesas Vari�veis - Pessoal Civil', '2019'),
        ('33190170000', 'Outras Despesas Vari�veis - Pessoal Militar', '2019'),
        ('33190340000', 'Outras Desp. de Pessoal - Contr. de Terceiriza��o', '2019'),
        ('33190670000', 'Dep�sitos Compuls�rios', '2019'),
        ('33190910000', 'Senten�as Judiciais', '2019'),
        ('33190920000', 'Despesas de Exerc�cios Anteriores', '2019'),
        ('33190940000', 'Indeniza��es e Restitui��es', '2019'),
        ('33190960000', 'Ressarcimento de Despesas de Pessoal Requisitado', '2019'),
        ('33190990000', 'Outras Desp. com Pessoal e Encargos Sociais', '2019'),
        ('33191000000', 'Aplica��es Diretas - Oper. Intra-Or�ament�rias', '2019'),
        ('33191040000', 'Contrata��o por Tempo Determinado', '2019'),
        ('33191130000', 'Obriga��es Patronais', '2019'),
        ('33191910000', 'Senten�as Judiciais', '2019'),
        ('33191920000', 'Despesas de Exerc�cios Anteriores - Op. Intra-Or�ament�rias', '2019'),
        ('33191960000', 'Ressarcimento de Despesas de Pessoal Requisitado - Opera��es Intra-Or�ament�rias', '2019'),
        ('33200000000', 'JUROS E ENCARGOS DA D�VIDA', '2019'),
        ('33300000000', 'OUTRAS DESPESAS CORRENTES', '2019'),
        ('33320000000', 'Transfer�ncias � Uni�o', '2019'),
        ('33330000000', 'Transfer�ncias a Estados e ao Distrito Federal', '2019'),
        ('33340000000', 'Transfer�ncias a Munic�pios', '2019'),
        ('33350000000', 'Transfer�ncias a Inst. Privadas s/ Fins Lucrativos', '2019'),
        ('33350390000', 'Outros Servi�os de Terceiros - Pessoa Jur�dica', '2019'),
        ('33350410000', 'Contribui��es', '2019'),
        ('33350430000', 'Subven��es Sociais', '2019'),
        ('33350430100', 'Institui��es de Carater de Assist�ncia Social, Cultural e Educacional', '2019'),
        ('33350430101', 'Institui��o de Carater de Assist�ncia Social', '2019'),
        ('33350430102', 'Institui��o de Carater Cultural', '2019'),
        ('33350430103', 'Institui��o de Carater Educacional', '2019'),
        ('33350430500', 'Institui��o de Carater Assistencial em Sa�de', '2019'),
        ('33350920000', 'Despesas de Exerc�cios Anteriores', '2019'),
        ('33350990000', 'Outras Transfer�ncias a Inst. Privadas s/ Fins Lucrativos', '2019'),
        ('33370000000', 'Transf. a Institui��es Multigovernamentais Nacionais', '2019'),
        ('33371000000', 'Transfer�ncias a Cons�rcios P�blicos', '2019'),
        ('33380000000', 'Transfer�ncias ao Exterior', '2019'),
        ('33390000000', 'APLICA��ES DIRETAS', '2019'),
        ('33390040000', 'Contrata��o por Tempo Determinado', '2019'),
        ('33390080000', 'Outros Benef�cios Assistenciais do servidor e do militar', '2019'),
        ('33390140000', 'Di�rias - Civil', '2019'),
        ('33390150000', 'Di�rias - Militar', '2019'),
        ('33390180000', 'Aux�lio Financeiro a Estudantes', '2019'),
        ('33390200000', 'Auxilio Financeiro a Pesquisadores', '2019'),
        ('33390300000', 'MATERIAL DE CONSUMO', '2019'),
        ('33390300100', 'Combust�veis e lubrificantes automotivos', '2019'),
        ('33390300400', 'G�s e outros materiais engarrafados', '2019'),
        ('33390300700', 'G�neros de Alimenta��o', '2019'),
        ('33390300900', 'Material Farmal�gico', '2019'),
        ('33390301000', 'Material Odontologico', '2019'),
        ('33390301100', 'Material Qu�mico', '2019'),
        ('33390301400', 'Material educativo e esportivo', '2019'),
        ('33390301500', 'Material para festividades e homenagens', '2019'),
        ('33390301600', 'Material de expediente', '2019'),
        ('33390301700', 'Material de processamento de dados', '2019'),
        ('33390301900', 'Material de Acondicionamento e Embalagem', '2019'),
        ('33390302000', 'Material de cama, mesa e banho', '2019'),
        ('33390302100', 'Material de copa e cozinha', '2019'),
        ('33390302200', 'Material de limpeza e produtos de higieniza��o', '2019'),
        ('33390302300', 'Uniformes, Tecidos e Aviamentos.', '2019'),
        ('33390302400', 'Material para manuten��o de bens im�veis/instala��es', '2019'),
        ('33390302500', 'Material p/ Manuten��o de Bens e M�veis', '2019'),
        ('33390302600', 'Material el�trico e eletr�nico', '2019'),
        ('33390302800', 'Material de prote��o e seguran�a', '2019'),
        ('33390302900', 'Material p/ �udio, V�deo e Foto', '2019'),
        ('33390303000', 'Material para Comunica��es', '2019'),
        ('33390303100', 'Sementes, Mudas de Plantas e Insumos', '2019'),
        ('33390303900', 'Material para manuten��o de ve�culos', '2019'),
        ('33390304200', 'Ferramentas', '2019'),
        ('33390304400', 'Material de sinaliza��o visual e outros', '2019'),
        ('33390304500', 'Material t�cnico para sele��o e treinamento', '2019'),
        ('33390304600', 'Material bibliogr�fico', '2019'),
        ('33390304700', 'Aquisi��o de Software - Produto', '2019'),
        ('33390304800', 'Bens m�veis n�o ativ�veis', '2019'),
        ('33390306000', 'Material Did�tico', '2019'),
        ('33390309600', 'Material de Consumo - Pagamento Antecipado', '2019'),
        ('33390309900', 'Outros Materiais de Consumo', '2019'),
        ('33390310000', 'Premia��es Culturais, Art�sticas, Cinent�ficas, Desportivas e Outras', '2019'),
        ('33390320000', 'Material de Distribui��o Gratuita', '2019'),
        ('33390330000', 'Passagens e Despesas com Locomo��o', '2019'),
        ('33390340000', 'Outras Despesas de Pessoal decorrentes de Contratos de Terceiriza��o', '2019'),
        ('33390350000', 'Servi�o de Consultoria', '2019'),
        ('33390360000', 'Outros Servi�os de Terceiros - Pessoa F�sica', '2019'),
        ('33390360100', 'Condom�nios', '2019'),
        ('33390360200', 'Di�rias a Colaboradores Eventuais no Pa�s', '2019'),
        ('33390360600', 'Servi�os T�cnicos Profissionais', '2019'),
        ('33390360700', 'Estagi�rios', '2019'),
        ('33390361300', 'Confer�ncias, exposi��es e espet�culos', '2019'),
        ('33390361500', 'Loca��o de im�veis', '2019'),
        ('33390361800', 'Manuten��o e Conserv. de Equipamentos', '2019'),
        ('33390362000', 'Manuten��o e Conserv. de Ve�culos', '2019'),
        ('33390362200', 'Manuten��o e Conserv. de Bens e Im�veis', '2019'),
        ('33390362300', 'Fornecimento de Alimenta��o', '2019'),
        ('33390362500', 'Servi�o de Limpeza e Conserva��o', '2019'),
        ('33390362800', 'Servi�o de sele��o e treinamento', '2019'),
        ('33390363500', 'Servi�os de apoio administrativo, t�cnico e operacional', '2019'),
        ('33390364500', 'Jetons e Gratifica��es a Conselheiros', '2019'),
        ('33390364600', 'Di�rias a Conselheiros', '2019'),
        ('33390365500', 'Servi�os t�cnicos profissionais de TI', '2019'),
        ('33390369900', 'Outros servi�os', '2019'),
        ('33390369901', 'Outros Servi�os Pessoa F�sica - Transporte Escolar', '2019'),
        ('33390369999', 'Outros servi�os', '2019'),
        ('33390370000', 'Loca��o de M�o-de-Obra', '2019'),
        ('33390380000', 'Arrendamento Mercantil', '2019'),
        ('33390390000', 'SERVI�OS DE TERCEIROS - PESSOA JUR�DICA', '2019'),
        ('33390390100', 'Assinaturas de Peri�dicos e Anuidades', '2019'),
        ('33390390200', 'Condom�nios', '2019'),
        ('33390390400', 'Direitos Autorais', '2019'),
        ('33390390500', 'Servi�os T�cnicos Profissionais', '2019'),
        ('33390390800', 'Manuten��o de Software', '2019'),
        ('33390391000', 'Loca��o de Im�veis', '2019'),
        ('33390391100', 'Loca��o de Softwares', '2019'),
        ('33390391200', 'Loca��o de M�quinas e Equipamentos', '2019'),
        ('33390391400', 'Loca��o Bens Mov. Out. Natureza e Intang�veis', '2019'),
        ('33390391500', 'Tributos � conta do locat�rio', '2019'),
        ('33390391600', 'Manuten��o e Conserva��o de Bens Im�veis', '2019'),
        ('33390391700', 'Manuten��o de M�quinas e Equipamentos', '2019'),
        ('33390391900', 'Manuten��o e Conserv. de Ve�culos', '2019'),
        ('33390392000', 'Manut. e Cons. de B. M�veis de outras Naturezas', '2019'),
        ('33390392200', 'Exposi��es, Congressos e Conferencias', '2019'),
        ('33390392300', 'Festividades e Homenagens', '2019'),
        ('33390393600', 'Multas indedutiveis', '2019'),
        ('33390393700', 'Juros', '2019'),
        ('33390394000', 'Programa de Alimenta��o do Trabalhador', '2019'),
        ('33390394100', 'Fornecimento de Alimenta��o', '2019'),
        ('33390394300', 'Despesas com energia el�trica', '2019'),
        ('33390394400', 'Despesas com �gua e esgoto', '2019'),
        ('33390394500', 'Servi�os de G�s', '2019'),
        ('33390394700', 'Servi�os de comunica��o em geral', '2019'),
        ('33390394800', 'Servi�o de Sele��o e Treinamento', '2019'),
        ('33390395000', 'Serv. M�dico-Hospital., Odontol. e Laboratoriais', '2019'),
        ('33390395200', 'Servi�os de reabilita��o profissional', '2019'),
        ('33390395400', 'Servi�os de Creches e Assist. Pr�-Escolar', '2019'),
        ('33390395700', 'Servi�os T�cnicos Profissionais de T.I', '2019'),
        ('33390395800', 'Servi�os de Telecomunica��es', '2019'),
        ('33390395900', 'Servi�os de �udio, V�deo e Foto', '2019'),
        ('33390396300', 'Servi�os Gr�ficos e Editoriais', '2019'),
        ('33390396500', 'Servi�os de Apoio ao Ensino', '2019'),
        ('33390396600', 'Servi�os Judici�rios', '2019'),
        ('33390396900', 'Seguros em Geral', '2019'),
        ('33390397000', 'Confec��o de Uniformes, Bandeiras e Flamulas', '2019'),
        ('33390397200', 'Vale-Transporte', '2019'),
        ('33390397300', 'Transporte de Servidores', '2019'),
        ('33390397400', 'Fretes transportes e encomendas', '2019'),
        ('33390397700', 'Vigil�ncia Ostensiva/Monitorada', '2019'),
        ('33390397800', 'Limpeza e Conserva��o', '2019'),
        ('33390397900', 'Servi�o de Apoio Administrativo, T�cnico e Operacional', '2019'),
        ('33390398000', 'Hospedagens', '2019'),
        ('33390398100', 'Servi�os Banc�rios', '2019'),
        ('33390398300', 'Servi�os de C�pias e Reprodu��o de Documentos', '2019'),
        ('33390399000', 'Servi�os de Publicidade Legal', '2019'),
        ('33390399200', 'Servi�os de publicidade institucional', '2019'),
        ('33390399400', 'Aquisi��o de Softwares', '2019'),
        ('33390399500', 'Manut. Cons. Equip. de Processamento de Dados', '2019'),
        ('33390399600', 'Outros Servi�os de Terceiros PJ - Pagto Antecipado', '2019'),
        ('33390399700', 'Comunica��o de Dados', '2019'),
        ('33390399900', 'Outros Servi�os de Terceiros - Pessoa Jur�dica', '2019'),
        ('33390399903', 'Outros Servi�os de Terceiros PJ - Transporte Escolar', '2019'),
        ('33390399999', 'Outros Servi�os de Terceiros - Pessoa Jur�dica', '2019'),
        ('33390460000', 'Aux�lio alimenta��o', '2019'),
        ('33390470000', 'Obriga��es Tribut�rias e Contributivas', '2019'),
        ('33390471000', 'Taxas', '2019'),
        ('33390471200', 'Contribui��o para o PIS/PASEP', '2019'),
        ('33390471800', 'Contrib.Previdenci�rias - Servi�os de Terceiros', '2019'),
        ('33390472000', 'Obriga��es Patronais S/ Serv. Pessoa Jur�dica', '2019'),
        ('33390479900', 'Outras obriga��es tribut�rias e contributivas', '2019'),
        ('33390480000', 'Outros Aux�lios Financeiros a Pessoas F�sicas', '2019'),
        ('33390490000', 'Auxilio - Transporte', '2019'),
        ('33390670000', 'Dep�sitos Compuls�rios', '2019'),
        ('33390910000', 'Senten�as Judiciais', '2019'),
        ('33390920000', 'Despesas de Exerc�cios Anteriores', '2019'),
        ('33390930000', 'Indeniza��es e Restitui��es', '2019'),
        ('33390990000', 'Outras Aplica��es Diretas', '2019'),
        ('33391000000', 'Aplica��es Diretas - Oper. Intra-Or�ament�rias', '2019'),
        ('33391040000', 'Contrata��o por Tempo Determinado', '2019'),
        ('33391300000', 'Material de Consumo', '2019'),
        ('33391350000', 'Servi�os de Consultoria', '2019'),
        ('33391390000', 'Outros Serv. Terceiros', '2019'),
        ('33391470000', 'Obrig. Tribut. e Contrib.', '2019'),
        ('33391620000', 'Aquisi��o Bens P/ Revenda', '2019'),
        ('33391910000', 'Senten�as Judiciais', '2019'),
        ('33391920000', 'Despesas de Exerc�cios Anteriores', '2019'),
        ('33391930000', 'Indeniza��es e Restitui��es', '2019'),
        ('33391990000', 'Outras Aplica��es Diretas - Oper. Intra-Or�ament�rias', '2019'),
        ('34000000000', 'DESPESAS DE CAPITAL', '2019'),
        ('34400000000', 'INVESTIMENTOS', '2019'),
        ('34420000000', 'Transfer�ncias � Uni�o', '2019'),
        ('34430000000', 'Transfer�ncias a Estados e ao DF', '2019'),
        ('34440000000', 'Transfer�ncias a Munic�pios', '2019'),
        ('34450000000', 'Transfer�ncias a Institui��es Privadas s/ Fins Lucrativos', '2019'),
        ('34480000000', 'Transfer�ncias ao Exterior', '2019'),
        ('34490000000', 'APLICA��ES DIRETAS', '2019'),
        ('34490300000', 'Material de Consumo', '2019'),
        ('34490510000', 'Obras e Instala��es', '2019'),
        ('34490519100', 'Obras em Andamento', '2019'),
        ('34490519200', 'Instala��es', '2019'),
        ('34490519300', 'Benfeitorias em im�veis', '2019'),
        ('34490520000', 'Equipamentos e Material Permanente', '2019'),
        ('34490520400', 'Aparelhos de Medi��o e Orienta��o', '2019'),
        ('34490520600', 'Aparelho e equipamentos de comunica��o', '2019'),
        ('34490521000', 'Aparelhos e Equipamentos para Esportes e Divers�es', '2019'),
        ('34490521200', 'Aparelhos e utens�lios dom�sticos', '2019'),
        ('34490521800', 'Cole��es e Materiais Bibliogr�ficos', '2019'),
        ('34490522400', 'Equipamento de Prote��o, Seguran�a e Socorro', '2019'),
        ('34490522600', 'Instrumentos musicais e art�sticos', '2019'),
        ('34490522800', 'M�quinas e Equipamentos de Natureza Industrial', '2019'),
        ('34490523000', 'M�quinas e Equipamentos Energeticos', '2019'),
        ('34490523200', 'M�quinas e Equipamentos Gr�ficos', '2019'),
        ('34490523300', 'Equipamentos para �udio, V�deo e Foto', '2019'),
        ('34490523400', 'M�quinas, Utens�lios e Equipamentos Diversos', '2019'),
        ('34490523500', 'Equipamentos de Processamento de Dados', '2019'),
        ('34490523600', 'M�quinas, Instala��es e Utens. de Escrit�rio', '2019'),
        ('34490523800', 'Maq. ferramentas e utensilios de oficina', '2019'),
        ('34490524200', 'Mobili�rio em geral', '2019'),
        ('34490524800', 'Ve�culos Diversos', '2019'),
        ('34490525100', 'Pe�as n�o Incorpor�veis a Im�veis', '2019'),
        ('34490525200', 'Ve�culos de tra��o mec�nica', '2019'),
        ('34490529900', 'Outros Materiais Permanentes', '2019'),
        ('34490610000', 'Aquisi��o de Im�veis', '2019'),
        ('34490610300', 'Terrenos', '2019'),
        ('34490910000', 'Senten�as Judiciais', '2019'),
        ('34490920000', 'Despesas de Exerc�cios Anteriores', '2019'),
        ('34490930000', 'Indeniza��es e Restitui��es', '2019'),
        ('34490990000', 'Outras Aplica��es Diretas', '2019'),
        ('34500000000', 'INVERS�ES FINANCEIRAS', '2019'),
        ('34520000000', 'Transfer�ncias � Uni�o', '2019'),
        ('34530000000', 'Transfer�ncias a Estados e ao Distrito Federal', '2019'),
        ('34540000000', 'Transfer�ncias a Munic�pios', '2019'),
        ('34550000000', 'Transfer�ncias a Institui��es Privadas s/ Fins Lucrativos', '2019'),
        ('34570000000', 'Transfer�ncias a Institui��es Multigovernamentais Nacionais', '2019'),
        ('34580000000', 'Transfer�ncias ao Exterior', '2019'),
        ('34590000000', 'APLICA��ES DIRETAS', '2019'),
        ('34590610000', 'Aquisi��o de Im�veis', '2019'),
        ('34590620000', 'Aquisi��o de Produtos para Revenda', '2019'),
        ('34590630000', 'Aquisi��o de T�tulos de Cr�dito', '2019'),
        ('34590640000', 'Aquisi��o de T�tulos Representativos de Capital j� Integralizado', '2019'),
        ('34590650000', 'Constitui��o ou Aumento de Capital de Empresas', '2019'),
        ('34590660000', 'Concess�o de Empr�stimos e Financiamentos', '2019'),
        ('34590990000', 'Outras Aplica��es Diretas', '2019'),
        ('34591000000', 'Aplica��es Diretas - Opera��es Intra-Or�ament�rias', '2019'),
        ('34591470000', 'Obriga��es Tribut�rias e Contributivas', '2019'),
        ('34591620000', 'Aquisi��o de Bens para Revenda', '2019'),
        ('34591910000', 'Senten�as Judiciais', '2019'),
        ('34591920000', 'Despesas de Exerc�cios Anteriores', '2019'),
        ('34600000000', 'AMORTIZA��O DA D�VIDA', '2019');
        
        COMMIT;        
SQL;
        $this->execute($sql);
    }
}