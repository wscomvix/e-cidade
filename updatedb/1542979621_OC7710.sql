
-- Ocorr�ncia 7710

--SCRIPT 01
BEGIN;
SELECT fc_startsession();

-- Inserir item de menu Relat�rios SIOPS
INSERT INTO db_itensmenu VALUES (
  (select max(id_item)+1 from db_itensmenu),
  'Relat�rios SIOPS', 'Relat�rios SIOPS',
  '', 1, 1, 'Relat�rios SIOPS', 't');

-- Insere item no menu Relat�rios
INSERT INTO db_menu VALUES (
  3331,
  (select max(id_item) from db_itensmenu),
  (select max(menusequencia)+1 from db_menu where modulo = 209 and id_item = 3331),
  209);

-- Inserir item de menu Prev. e Exec. das Receitas Or�ament�rias
INSERT INTO db_itensmenu VALUES (
  (select max(id_item)+1 from db_itensmenu),
  'Prev. e Exec. das Receitas Or�ament�rias',
  'Prev. e Exec. das Receitas Or�ament�rias',
  'con2_prevexecreceitasorcamentarias001.php', 1, 1,
  'Prev. e Exec. das Receitas Or�ament�rias', 't');

INSERT INTO db_menu VALUES (
  (select max(id_item)-1 from db_itensmenu),
  (select max(id_item) from db_itensmenu),
  1,
  209);

-- REGISTRO DO RELAT�RIO
INSERT
INTO orcparamrel
VALUES(
--   (SELECT MAX(o42_codparrel)+1 FROM orcparamrel WHERE o42_codparrel < 9999),
  173,
  'Prev. e Exec. das Receitas Or�ament�rias',
  1
);

--------------------------------------------------------
--            FUN��O DE INCLUS�O DE LINHA             --
--------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_incluirlinha7710(descricao VARCHAR(250), totalizador BOOLEAN) RETURNS VOID AS
$$
BEGIN

  INSERT
  INTO  orcparamseq
  VALUES (
--     (SELECT  MAX(o42_codparrel) FROM orcparamrel WHERE o42_codparrel < 9999), --o69_codparamrel
    173, --o69_codparamrel
    (SELECT  COALESCE((SELECT  MAX(o69_codseq)+1
                       FROM  orcparamseq
--                        WHERE  o69_codparamrel = (SELECT MAX(o42_codparrel) FROM orcparamrel WHERE o42_codparrel < 9999)),
                       WHERE  o69_codparamrel = 173),
                       1)), --o69_codseq
    descricao, --o69_descr
    1, --o69_grupo
    0, --o69_grupoexclusao
    0, --o69_nivel
    false, --o69_libnivel
    false, --o69_librec
    false, --o69_libsubfunc
    false, --o69_libfunc
    false, --o69_verificaano
    descricao, --o69_labelrel
    false, --o69_manual
    totalizador, --o69_totalizador
    (SELECT  COALESCE((SELECT  MAX(o69_codseq)+1
                       FROM  orcparamseq
--                        WHERE  o69_codparamrel = (SELECT MAX(o42_codparrel) FROM orcparamrel WHERE o42_codparrel < 9999)),
                       WHERE  o69_codparamrel = 173),

                      1)), --o69_ordem
    1, --o69_nivellinha
    '', --o69_observacao
    false, --o69_desdobrarlinha
    1 --o69_origem
  );

END
$$
language plpgsql;

-----------------------------------------------
--            INCLUS�O DE LINHAS             --
-----------------------------------------------
SELECT fc_incluirlinha7710('Receitas Correntes', TRUE);
SELECT fc_incluirlinha7710('Receita Tribut�ria', TRUE);
SELECT fc_incluirlinha7710('Impostos', TRUE);
SELECT fc_incluirlinha7710('Impostos sobre o Patrim�nio e a Renda', TRUE);
SELECT fc_incluirlinha7710('Imposto sobre a Propriedade Territorial Rural - ITR',FALSE);
SELECT fc_incluirlinha7710('Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU',FALSE);
SELECT fc_incluirlinha7710('Imposto de Renda Retido e Proventos de Qualquer Natureza',TRUE);
SELECT fc_incluirlinha7710('Imposto de Renda Retido nas Fontes sobre os Rendimentos do Trabalho - IRRF',FALSE);
SELECT fc_incluirlinha7710('Imposto de Renda Retido nas Fontes sobre Outros Rendimentos',FALSE);
SELECT fc_incluirlinha7710('Imposto sobre a Transmiss�o "Inter Vivos" de Bens Im�veis e de Direitos Reais sobre Im�veis - ITBI',FALSE);
SELECT fc_incluirlinha7710('Imposto sobre a Produ��o e a Circula��o', TRUE);
SELECT fc_incluirlinha7710('Imposto sobre Servi�os de Qualquer Natureza - ISS',TRUE);
SELECT fc_incluirlinha7710('Imposto sobre Servi�os de Qualquer Natureza',FALSE);
SELECT fc_incluirlinha7710('Adicional ISS - Fundo Municipal de Combate � Pobreza',FALSE);
SELECT fc_incluirlinha7710('ISS / ICMS / SIMPLES - Lei Federal 9.317 / 96 - Imposto sobre Servi�os',FALSE);
SELECT fc_incluirlinha7710('Taxas', TRUE);
SELECT fc_incluirlinha7710('Taxas pelo Exerc�cio do Poder de Pol�cia', TRUE);
SELECT fc_incluirlinha7710('Taxa de Fiscaliza��o de Vigil�ncia Sanit�ria',FALSE);
SELECT fc_incluirlinha7710('Taxa de Sa�de Suplementar',FALSE);
SELECT fc_incluirlinha7710('Taxa pela Utiliza��o de Selos de Controle e de Contadores de Produ��o',FALSE);
SELECT fc_incluirlinha7710('Outras Taxas pelo Exerc�cio do Poder de Pol�cia',FALSE);
SELECT fc_incluirlinha7710('Taxas pela Presta��o de Servi�os',FALSE);
SELECT fc_incluirlinha7710('Contribui��o de Melhoria',FALSE);
SELECT fc_incluirlinha7710('Receitas de Contribui��es', TRUE);
SELECT fc_incluirlinha7710('Contribui��es Sociais', TRUE);
SELECT fc_incluirlinha7710('Contribui��o para o Fundo de Sa�de das For�as Armadas',FALSE);
SELECT fc_incluirlinha7710('Contribui��es para o Regime Pr�prio de Previd�ncia do Servidor P�blico', TRUE);
SELECT fc_incluirlinha7710('Contribui��o Patronal de Servidor Ativo Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal de Servidor Ativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Inativo Civil',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Inativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Pensionista Civil',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Pensionista Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o do Servidor Ativo Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��o de Servidor Ativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��es do Servidor Inativo Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��es de Servidor Inativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��es de Pensionista Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��es de Pensionista Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Previdenci�ria para Amortiza��o do D�ficit Atuarial',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Previdenci�ria em Regime de Parcelamento de D�bitos - RPPS',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento da Contribui��o Patronal, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento da Contribui��o do Servidor Ativo Civil, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento da Contribui��o do Servidor Inativo Civil, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento de Pensionista Civil, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Outras Contribui��es Previdenci�rias',FALSE);
SELECT fc_incluirlinha7710('Outras Contribui��es',FALSE);
SELECT fc_incluirlinha7710('Receita Patrimonial', TRUE);
SELECT fc_incluirlinha7710('Receitas Imobili�rias',FALSE);
SELECT fc_incluirlinha7710('Receitas de Valores Mobili�rios', TRUE);
SELECT fc_incluirlinha7710('Juros de T�tulos de Renda',FALSE);
SELECT fc_incluirlinha7710('Remunera��o de Dep�sitos Banc�rios', TRUE);
SELECT fc_incluirlinha7710('Remunera��o de Dep�sitos de Recursos Vinculados', TRUE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Royalties', TRUE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Royalties da Educa��o',FALSE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Royalties da Sa�de',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Royalties',FALSE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - FUNDEB',FALSE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Fundo de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - A��es e Servi�os P�blicos de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Conv�nios com a �rea de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Receita de Remunera��o de Dep�sitos Banc�rios de Recursos Vinculados - Conv�nios com a �rea da Sa�de',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas de Remunera��o de Outros Dep�sitos Banc�rios de Recursos Vinculados',FALSE);
SELECT fc_incluirlinha7710('Remunera��o de Dep�sitos de Recursos n�o Vinculados',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas de Valores Mobili�rios',FALSE);
SELECT fc_incluirlinha7710('Compensa��es Financeiras',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas Patrimoniais',FALSE);
SELECT fc_incluirlinha7710('Receita Agropecu�ria',FALSE);
SELECT fc_incluirlinha7710('Receita Industrial',FALSE);
SELECT fc_incluirlinha7710('Receita de Servi�os',TRUE);
SELECT fc_incluirlinha7710('Servi�os de Sa�de',TRUE);
SELECT fc_incluirlinha7710('Servi�os Hospitalares',FALSE);
SELECT fc_incluirlinha7710('Servi�os de Registro de An�lise e de Controle de Produtos Sujeitos a Normas de Vigil�ncia Sanit�ria',FALSE);
SELECT fc_incluirlinha7710('Servi�os Radiol�gicos e Laboratoriais',FALSE);
SELECT fc_incluirlinha7710('Servi�os de Assist�ncia � Sa�de Suplementar do Servidor Civil',FALSE);
SELECT fc_incluirlinha7710('Servi�os de Sa�de a Terceiros',TRUE);
SELECT fc_incluirlinha7710('Servi�os de Sa�de ao Estado',FALSE);
SELECT fc_incluirlinha7710('Servi�os de Sa�de a Munic�pios',FALSE);
SELECT fc_incluirlinha7710('Servi�os de Cons�rcios de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Servi�os de Sa�de a Institui��es Privadas - Sa�de Suplementar (TUNEP)',FALSE);
SELECT fc_incluirlinha7710('Outros Servi�os de Sa�de a Terceiros',FALSE);
SELECT fc_incluirlinha7710('Servi�os Ambulatoriais',FALSE);
SELECT fc_incluirlinha7710('Outros Servi�os de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Outros Servi�os',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Correntes',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias Intergovernamentais',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias da Uni�o',TRUE);
SELECT fc_incluirlinha7710('Participa��o na Receita da Uni�o',TRUE);
SELECT fc_incluirlinha7710('Cota Parte do Fundo de Participa��o dos Munic�pios - FPM - Parcela referente � CF, art. 159, I, al�nea b (Cota Mensal)',FALSE);
SELECT fc_incluirlinha7710('Cota Parte do Fundo de Participa��o dos Munic�pios - (1% Cota entregue no m�s de dezembro)',FALSE);
SELECT fc_incluirlinha7710('Cota Parte do Fundo de Participa��o dos Munic�pios - (1% Cota entregue no m�s de julho)',FALSE);
SELECT fc_incluirlinha7710('Cota Parte do Imposto sobre a Propriedade Territorial Rural - ITR',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte da Contribui��o de Interven��o no Dom�nio Econ�mico - CIDE',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte do Imposto Sobre Opera��es de Cr�dito, C�mbio e Seguro,ou Relativas a T�tulos ou Valores Mobili�rios-IOF OURO',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncia da Compensa��o Financeira pela Explora��o de Recursos Naturais',TRUE);
SELECT fc_incluirlinha7710('Cota-parte da Compensa��o Financeira de Recursos H�dricos',FALSE);
SELECT fc_incluirlinha7710('Cota-parte da Compensa��o Financeira de Recursos Minerais - CFEM',FALSE);
SELECT fc_incluirlinha7710('Cota-parte Royalties - Compensa��o Financeira pela Produ��o de Petr�leo - Lei n� 7.990/89',FALSE);
SELECT fc_incluirlinha7710('Cota-parte Royalties pelo Excedente da Produ��o do Petr�leo - Lei n� 9.478/97, artigo 49, I e II',FALSE);
SELECT fc_incluirlinha7710('Cota-parte Royalties pela Participa��o Especial - Lei n� 9.478/97, artigo 50',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte do Fundo Especial do Petr�leo - FEP',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias Decorrentes de Compensa��o Financeira pela Explora��o de Recursos Naturais',FALSE);
SELECT fc_incluirlinha7710('Custeio das A��es e Servi�os P�blicos de Sa�de',TRUE);
SELECT fc_incluirlinha7710('Aten��o B�sica',FALSE);
SELECT fc_incluirlinha7710('Aten��o de M�dia e Alta Complexidade Ambulatorial e Hospitalar',FALSE);
SELECT fc_incluirlinha7710('Vigil�ncia em Sa�de',FALSE);
SELECT fc_incluirlinha7710('Assist�ncia Farmac�utica',FALSE);
SELECT fc_incluirlinha7710('Gest�o do SUS',FALSE);
SELECT fc_incluirlinha7710('Outros Programas Financiados por Transfer�ncias Fundo a Fundo',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do Fundo Nacional de Assist�ncia Social - FNAS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do Fundo Nacional do Desenvolvimento da Educa��o - FNDE',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias do Sal�rio-Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Diretas do FNDE Referentes ao Programa Dinheiro Direto na Escola - PDDE',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Diretas do FNDE Referentes ao Programa Nacional de Alimenta��o Escolar - PNAE',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Diretas do FNDE Referentes ao Programa Nacional de Apoio ao Transporte do Escolar - PNATE',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias Diretas do Fundo Nacional do Desenvolvimento da Educa��o - FNDE',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncia Financeira do ICMS - Desonera��o - L.C. N� 87/96 - LEI KANDIR',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias a Cons�rcios P�blicos',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Advindas de Emendas Parlamentares Individuais',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias da Uni�o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias dos Estados',TRUE);
SELECT fc_incluirlinha7710('Participa��o na Receita dos Estados',TRUE);
SELECT fc_incluirlinha7710('Cota-Parte do ICMS',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte do IPVA',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte do IPI sobre Exporta��o',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte da Contribui��o de Interven��o no Dom�nio Econ�mico - CIDE',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do SUS - Estado',FALSE);
SELECT fc_incluirlinha7710('Outras Participa��es na Receita dos Estados',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncia da Cota-Parte da Compensa��o Financeira (25%)',TRUE);
SELECT fc_incluirlinha7710('Cota-Parte da Compensa��o Financeira de Recursos H�dricos',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte da Compensa��o Financeira de Recursos Minerais - CFEM',FALSE);
SELECT fc_incluirlinha7710('Cota-Parte Royalties - Compensa��o Financeira pela Produ��o do Petr�leo - Lei n� 7.990/89, artigo 9�',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias Decorrentes de Compensa��es Financeiras',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncia de Recursos do Estado para Programas de Sa�de - Repasse Fundo a Fundo',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do Estado para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Estado para a �rea de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias a Cons�rcios P�blicos',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias dos Estados',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias dos Munic�pios',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Recebimento pela Presta��o de Servi�os de Sa�de a Munic�pios',FALSE);
SELECT fc_incluirlinha7710('Recebimento pela Presta��o de Servi�os a Cons�rcios de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Recursos Provenientes do Fundo Municipal de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias dos Munic�pios para Aquisi��o de Medicamentos',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncia dos Munic�pios para a �rea de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Munic�pios para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias a Cons�rcios P�blicos',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias dos Munic�pios',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Multigovernamentais',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do FUNDEB',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos da Complementa��o da Uni�o ao FUNDEB',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias Multigovernamentais',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Institui��es Privadas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias do Exterior',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Pessoas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o e de Suas Entidades',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o para o Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o Destinadas a Programas de Saneamento B�sico',TRUE);
SELECT fc_incluirlinha7710('Conv�nios com o Minist�rio da Sa�de para Saneamento B�sico',FALSE);
SELECT fc_incluirlinha7710('Outros Conv�nios da Uni�o para Saneamento B�sico',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Conv�nios da Uni�o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncia de Conv�nios dos Estados e do Distrito Federal e de Suas Entidades',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nio dos Estados para o Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nio dos Estados Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Conv�nio dos Estados',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Munic�pios e de Suas Entidades',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nio dos Munic�pios para o Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nio dos Munic�pios Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Conv�nios dos Munic�pios',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios de Institui��es Privadas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios do Exterior',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias Correntes',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas Correntes',TRUE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora',TRUE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora dos Tributos',TRUE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora do Imposto sobre a Propriedade Territorial Rural - ITR',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da Taxa de Fiscaliza��o e Vigil�ncia Sanit�ria',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da Taxa de Sa�de Suplementar',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora do Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora do Imposto sobre a Transmiss�o Inter Vivos de Bens Im�veis - ITBI',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora do Imposto sobre Servi�os de Qualquer Natureza - ISS',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora sobre o ISS / ICMS / SIMPLES',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora de Outros Tributos',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora das Contribui��es',TRUE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora das Contribui��es Para o Regime Pr�prio de Previd�ncia do Servidor',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora das Contribui��es Previdenci�rias Para o Regime Geral de Previd�ncia',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora de Outras Contribui��es',FALSE);
SELECT fc_incluirlinha7710('Multa e Juros de Mora da D�vida Ativa dos Tributos',TRUE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa do Imposto sobre a Propriedade Territorial Rural - ITR',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa do Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa do Imposto sobre a Transmiss�o Inter Vivos de Bens Im�veis - ITBI',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa do Imposto sobre Servi�os de Qualquer Natureza - ISS',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa sobre o ISS / ICMS / SIMPLES',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa da Taxa de Fiscaliza��o e Vigil�ncia Sanit�ria',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora da D�vida Ativa de Outros Tributos',FALSE);
SELECT fc_incluirlinha7710('Multa e Juros de Mora da D�vida Ativa das Contribui��es',TRUE);
SELECT fc_incluirlinha7710('Multa e Juros de Mora da D�vida Ativa das Contribui��es Previdenci�rias Para o Regime Geral de Previd�ncia Social',FALSE);
SELECT fc_incluirlinha7710('Multa e Juros de Mora da D�vida Ativa de Outras Contribui��es',FALSE);
SELECT fc_incluirlinha7710('Multa e Juros de Mora da D�vida Ativa de Outras Receitas',FALSE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora de Outras Receitas',FALSE);
SELECT fc_incluirlinha7710('Multas de Outras Origens',FALSE);
SELECT fc_incluirlinha7710('Indeniza��es e Restitui��es',TRUE);
SELECT fc_incluirlinha7710('Indeniza��es',FALSE);
SELECT fc_incluirlinha7710('Restitui��es',TRUE);
SELECT fc_incluirlinha7710('Restitui��es do SUS',FALSE);
SELECT fc_incluirlinha7710('Outras Restitui��es',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa',TRUE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa Tribut�ria',TRUE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa do Imposto sobre a Propriedade Territorial Rural - ITR',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa do Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa do Imposto sobre a Transmiss�o Inter-Vivos de Bens Im�veis - ITBI',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa do Imposto sobre Servi�os de Qualquer Natureza - ISS',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa do ISS / ICMS / SIMPLES',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa da Taxa de Fiscaliza��o e Vigil�ncia Sanit�ria',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa da Taxa de Sa�de Suplementar',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa de Outros Tributos',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa n�o tribut�ria',TRUE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa das Contribui��es Previdenci�rias Para o Regime Geral da Previd�ncia Social',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa de Ressarcimento ao Regime �nico de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas da D�vida Ativa n�o Tribut�ria',FALSE);
SELECT fc_incluirlinha7710('Receitas Decorrentes de Aportes Peri�dicos para Amortiza��o de D�ficit Atuarial do RPPS',FALSE);
SELECT fc_incluirlinha7710('Receitas Decorrentes de Compensa��es ao RGPS',FALSE);
SELECT fc_incluirlinha7710('Receitas Diversas',FALSE);
SELECT fc_incluirlinha7710('Receitas de Capital',TRUE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito',TRUE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Internas',TRUE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Internas - Contratuais',TRUE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Internas para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Internas para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Internas para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Opera��es de Cr�dito Internas - Contratuais',FALSE);
SELECT fc_incluirlinha7710('Outras Opera��es de Cr�dito Internas',FALSE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Externas',TRUE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Externas - Contratuais',TRUE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Externas para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Externas para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Opera��es de Cr�dito Externas para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Opera��es de Cr�dito Externas - Contratuais',FALSE);
SELECT fc_incluirlinha7710('Outras Opera��es de Cr�dito Externas',FALSE);
SELECT fc_incluirlinha7710('Aliena��o de Bens M�veis e Im�veis',FALSE);
SELECT fc_incluirlinha7710('Amortiza��o de Empr�stimos',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Capital',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias Intergovernamentais',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias da Uni�o',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do Sistema �nico de Sa�de - SUS',TRUE);
SELECT fc_incluirlinha7710('Bloco Investimentos na Rede de Servi�os P�blicos de Sa�de',TRUE);
SELECT fc_incluirlinha7710('Aten��o b�sica',FALSE);
SELECT fc_incluirlinha7710('Aten��o especializada',FALSE);
SELECT fc_incluirlinha7710('Vigil�ncia em sa�de',FALSE);
SELECT fc_incluirlinha7710('Gest�o e desenvolvimento de tecnologias em sa�de no SUS',FALSE);
SELECT fc_incluirlinha7710('Gest�o do SUS',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Recursos do SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias da Uni�o para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias a Cons�rcios P�blicos',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias Advindas de Emendas Parlamentares Individuais',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias da Uni�o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias dos Estados',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos do Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias dos Estados para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias a Cons�rcios P�blicos',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias dos Estados',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias dos Munic�pios',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos Destinadas a Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Recursos Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Munic�pios para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias a Cons�rcios P�blicos',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias dos Munic�pios',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Institui��es Privadas para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Institui��es Privadas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias do Exterior para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias do Exterior',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas para Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas para Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Pessoas para Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Pessoas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Outras Institui��es P�blicas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o e de suas Entidades',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o para o Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios da Uni�o Destinadas a Programas de Saneamento B�sico',TRUE);
SELECT fc_incluirlinha7710('Conv�nios com o Minist�rio da Sa�de para a �rea de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outros Conv�nios e Transfer�ncias da Uni�o para Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Conv�nios da Uni�o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Estados e do Distrito Federal e de suas Entidades',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Estados para o Sistema �nico de Sa�de - SUS',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Estados Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Estados Destinadas a Programas de Saneamento B�sico',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Conv�nios dos Estados',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Munic�pios e de suas Entidades',TRUE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Munic�pios Destinados a Programas de Sa�de',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Munic�pios Destinadas a Programas de Educa��o',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios dos Munic�pios Destinadas a Programas de Saneamento',FALSE);
SELECT fc_incluirlinha7710('Outras Transfer�ncias de Conv�nios dos Munic�pios',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios de Institui��es Privadas',FALSE);
SELECT fc_incluirlinha7710('Transfer�ncias de Conv�nios do Exterior',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas de Capital',FALSE);
SELECT fc_incluirlinha7710('Receitas Correntes Intra-Or�ament�rias',TRUE);
SELECT fc_incluirlinha7710('Receitas Tribut�rias',TRUE);
SELECT fc_incluirlinha7710('Impostos',TRUE);
SELECT fc_incluirlinha7710('Impostos sobre o Patrim�nio e a Renda',TRUE);
SELECT fc_incluirlinha7710('Imposto sobre a Propriedade Territorial Rural - ITR',FALSE);
SELECT fc_incluirlinha7710('Imposto sobre a Renda e Proventos de Qualquer Natureza - IRRF',FALSE);
SELECT fc_incluirlinha7710('Impostos sobre a Produ��o e a Circula��o de Mercadorias',TRUE);
SELECT fc_incluirlinha7710('Imposto sobre Servi�os de Qualquer Natureza - ISS',FALSE);
SELECT fc_incluirlinha7710('Taxas',TRUE);
SELECT fc_incluirlinha7710('Taxas pelo Exerc�cio do Poder de Pol�cia',TRUE);
SELECT fc_incluirlinha7710('Taxa de Fiscaliza��o de Vigil�ncia Sanit�ria',FALSE);
SELECT fc_incluirlinha7710('Taxa de Sa�de Suplementar',FALSE);
SELECT fc_incluirlinha7710('Taxas pela Presta��o de Servi�os',FALSE);
SELECT fc_incluirlinha7710('Outras Taxas',FALSE);
SELECT fc_incluirlinha7710('Receita de Contribui��o',TRUE);
SELECT fc_incluirlinha7710('Contribui��es para o Regime Pr�prio de Previd�ncia do Servidor P�blico',TRUE);
SELECT fc_incluirlinha7710('Contribui��o Patronal de Servidor Ativo Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal de Servidor Ativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Inativo Civil',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Inativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Pensionista Civil',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Patronal - Pensionista Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o do Servidor Ativo Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��o de Servidor Ativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��es do Servidor Inativo Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��es de Servidor Inativo Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��es de Pensionista Civil para o Regime Pr�prio',FALSE);
SELECT fc_incluirlinha7710('Contribui��es de Pensionista Militar',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Previdenci�ria para Amortiza��o do D�ficit Atuarial',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Previdenci�ria em Regime de Parcelamento de D�bitos - RPPS',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento da Contribui��o Patronal, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento da Contribui��o do Servidor Ativo Civil, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento da Contribui��o do Servidor Inativo Civil, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Receita de Recolhimento de Pensionista Civil, Oriunda do Pagamento de Senten�as Judiciais',FALSE);
SELECT fc_incluirlinha7710('Outras Contribui��es Previdenci�rias',FALSE);
SELECT fc_incluirlinha7710('Contribui��o Previdenci�ria Para o Regime Geral de Previd�ncia Social',FALSE);
SELECT fc_incluirlinha7710('Outras Contribui��es Sociais',FALSE);
SELECT fc_incluirlinha7710('Receita Patrimonial',FALSE);
SELECT fc_incluirlinha7710('Receita Industrial',FALSE);
SELECT fc_incluirlinha7710('Receita de Servi�os',TRUE);
SELECT fc_incluirlinha7710('Servi�os de Sa�de',TRUE);
SELECT fc_incluirlinha7710('Servi�os Hospitalares',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas de Servi�os',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas Correntes',TRUE);
SELECT fc_incluirlinha7710('Multas e Juros de Mora',FALSE);
SELECT fc_incluirlinha7710('Indeniza��es e Restitui��es',FALSE);
SELECT fc_incluirlinha7710('Receita da D�vida Ativa',TRUE);
SELECT fc_incluirlinha7710('Receita da d�vida Ativa dos Tributos',FALSE);
SELECT fc_incluirlinha7710('Outras Receitas da D�vida Ativa',FALSE);
SELECT fc_incluirlinha7710('Receitas Correntes Diversas',FALSE);
SELECT fc_incluirlinha7710('Receitas de Capital Intra - Or�ament�rias',FALSE);
SELECT fc_incluirlinha7710('TOTAL GERAL DAS RECEITAS',TRUE);


----------------------------------------------------------
--            FUN��O DE INCLUS�O DE COLUNAS             --
----------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_registrarcoluna7710(descricao VARCHAR(120),nome VARCHAR(50)) RETURNS VOID AS
$$
BEGIN

  INSERT
  INTO orcparamseqcoluna
  VALUES (
    (SELECT MAX(o115_sequencial)+1 FROM orcparamseqcoluna),
    2018,
    descricao,
    1,
    '',
    nome
  );

END
$$
language plpgsql;


------------------------------------------------------------
--            FUN��O PARA INCLUS�O DE COLUNAS             --
------------------------------------------------------------
-- SELECT fc_registrarcoluna7710('Previs�o das Receitas','prevrec');
-- SELECT fc_registrarcoluna7710('Execu��o das Receitas Or�ament�rias','execrecorc');

SELECT fc_registrarcoluna7710('Previs�o Inicial das Receitas Brutas (a)','previnirecbruta');
SELECT fc_registrarcoluna7710('Previs�o Atualizada das Receitas Brutas (b)', 'prevatualrecbruta');
SELECT fc_registrarcoluna7710('Receitas Realizadas Brutas (c)','recrealizadabruta');
SELECT fc_registrarcoluna7710('Dedu��es das Receitas (d)', 'deducrec');
SELECT fc_registrarcoluna7710('Receitas Realizadas da base para c�lculo do percentual de aplica��o em ASPS (e) = (c-d)','recrealizadabasecalcpercaplicasps');
SELECT fc_registrarcoluna7710('Dedu��o Para Forma��o do FUNDEB (f)','deducformacfundeb');
SELECT fc_registrarcoluna7710('Total Geral das Receitas L�quidas Realizadas (g) = (c- d-f)','totgeralrecliqrealiza');
SELECT fc_registrarcoluna7710('Receitas Orcadas','receitasorcadas');

-------------------------------------------------
--            CRIA��O DOS PER�ODOS             --
-------------------------------------------------
INSERT INTO periodo
VALUES(125, '1� BIMESTRE', 6, 1, 1, 29, 2, '1B', 1);

INSERT INTO periodo
VALUES(126, '2� BIMESTRE', 6, 1, 1, 30, 4, '2B', 2);

INSERT INTO periodo
VALUES(127, '3� BIMESTRE', 6, 1, 1, 30, 6, '3B', 3);

INSERT INTO periodo
VALUES(128, '4� BIMESTRE', 6, 1, 1, 31, 8, '4B', 4);

INSERT INTO periodo
VALUES(129, '5� BIMESTRE', 6, 1, 1, 31, 10, '5B', 5);

INSERT INTO periodo
VALUES(130, '6� BIMESTRE', 6, 1, 1, 31, 12, '6B', 6);


-------------------------------------------------------------
--            FUN��O PARA INCLUS�O DE PER�ODOS             --
-------------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_incluirperiodos7710(periodo INT) RETURNS VOID AS
$$
BEGIN

  INSERT
  INTO orcparamrelperiodos(o113_sequencial, o113_periodo, o113_orcparamrel)
  VALUES ((SELECT MAX(o113_sequencial)+1
             FROM orcparamrelperiodos
            WHERE o113_sequencial
           NOT IN (4000803,4000802,4000801,4000800,4000799,4000798,4000797,4000796,
                   4000795,4000794,4000793,4000792,4000790,4000789,4000788,4000787,
                   4000786,4000785,4000784,4000783,4000782,4000781,4000780,4000779,
                   4000778,4000777,4000776,4000775,4000774,4000773,4000772,4000771,
                   4000770,4000769,4000768,4000767,4000766,4000765,4000764,4000763,
                   4000762,4000761,4000760,4000759,4000758,4000757,4000756,4000755)
              AND o113_sequencial < 1000000),

          periodo,
          173
  );

END
$$
language plpgsql;

----------------------------------
--    INSER��O DE PER�ODOS      --
----------------------------------
SELECT fc_incluirperiodos7710(125);
SELECT fc_incluirperiodos7710(126);
SELECT fc_incluirperiodos7710(127);
SELECT fc_incluirperiodos7710(128);
SELECT fc_incluirperiodos7710(129);
SELECT fc_incluirperiodos7710(130);


----------------------------------------------------------
--            FUN��O DE INCLUS�O DE FORMULA             --
----------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_incluircoluna7710(codseq INT, coluna VARCHAR(50), formula VARCHAR(250)) RETURNS VOID AS
$$

DECLARE
  reg record;
  ordem INT;

BEGIN
  IF coluna='previnirecbruta'
  THEN ordem := 1;
  END IF;
  IF coluna='prevatualrecbruta'
  THEN ordem := 2;
  END IF;
  IF coluna='recrealizadabruta'
  THEN ordem := 3;
  END IF;
  IF coluna='deducrec'
  THEN ordem := 4;
  END IF;
  IF coluna='recrealizadabasecalcpercaplicasps'
  THEN ordem := 5;
  END IF;
  IF coluna='deducformacfundeb'
  THEN ordem := 6;
  END IF;
  IF coluna='totgeralrecliqrealiza'
  THEN ordem := 7;
  END IF;
  IF coluna='receitasorcadas'
  THEN ordem := 8;
  END IF;

  for reg in (
      SELECT o113_periodo
        FROM orcparamrelperiodos
--        WHERE o113_orcparamrel = (SELECT  MAX(o42_codparrel) FROM orcparamrel WHERE o42_codparrel < 500)
       WHERE o113_orcparamrel = 173
    ORDER BY o113_periodo
  )loop

    INSERT
    INTO  orcparamseqorcparamseqcoluna
    VALUES  (
      (SELECT MAX(o116_sequencial)+1 from orcparamseqorcparamseqcoluna),              --o116_sequencial
      codseq,                                                                         --o116_codseq
--       (SELECT  MAX(o42_codparrel) FROM orcparamrel WHERE o42_codparrel < 500),        --o116_codparamrel
      173,        --o116_codparamrel
      (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = coluna), --o116_orcparamseqcoluna
      ordem,                                                                          --o116_ordem
      reg.o113_periodo,                                                                    --o116_periodo
      formula                                                                         --o116_formula
    );

  END loop;
END
$$
language plpgsql;


-----------------------------------------------------------------------------------------
--            INCLUS�O DA F�RMULA PADR�O EM TODAS AS LINHAS DE UMA COLUNA              --
-----------------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_incluirformulapadrao7710(coluna VARCHAR(50)) RETURNS VOID AS
$$

DECLARE
  reg record;
  formula VARCHAR(50);

BEGIN
  IF coluna='previnirecbruta'
  THEN formula := '';
  END IF;
  IF coluna='prevatualrecbruta'
  THEN formula := '';
  END IF;
  IF coluna='recrealizadabruta'
  THEN formula := '';
  END IF;
  IF coluna='deducrec'
  THEN formula := '';
  END IF;
  IF coluna='recrealizadabasecalcpercaplicasps'
  THEN formula := '';
  END IF;
  IF coluna='deducformacfundeb'
  THEN formula := '';
  END IF;
  IF coluna='totgeralrecliqrealiza'
  THEN formula := '';
  END IF;
  IF coluna='receitasorcadas'
  THEN formula := '';
  END IF;

  for reg in (
    SELECT o69_codseq
    FROM orcparamseq
--     WHERE o69_codparamrel = (SELECT  MAX(o42_codparrel) FROM orcparamrel WHERE o42_codparrel < 500)
    WHERE o69_codparamrel = 173
    ORDER BY o69_codseq
  )loop

    PERFORM fc_incluircoluna7710(reg.o69_codseq,coluna, formula);

  END loop;
END
$$
language plpgsql;


----------------------------------------------------------------------------------------------
--            INCLUS�O DA F�RMULA PADR�O EM TODAS AS LINHAS DA PRIMEIRA COLUNA              --
----------------------------------------------------------------------------------------------
SELECT fc_incluirformulapadrao7710('previnirecbruta'); --1
SELECT fc_incluirformulapadrao7710('prevatualrecbruta'); --2
SELECT fc_incluirformulapadrao7710('recrealizadabruta'); --3
SELECT fc_incluirformulapadrao7710('deducrec'); --4
SELECT fc_incluirformulapadrao7710('recrealizadabasecalcpercaplicasps'); --5
SELECT fc_incluirformulapadrao7710('deducformacfundeb'); --6
SELECT fc_incluirformulapadrao7710('totgeralrecliqrealiza'); --7
SELECT fc_incluirformulapadrao7710('receitasorcadas'); --8

---------------------------------------------------------------------------
--            LIMPEZA DE TODAS AS F�RMULAS DO RELAT�RIO 173              --
---------------------------------------------------------------------------
UPDATE orcparamseqorcparamseqcoluna
   SET o116_formula = ''
 WHERE o116_codparamrel = 173;

-- Adiciona a f�rmula #saldo_inicial a todas as linhas da coluna previnirecbruta que possuem estrutural definido
UPDATE orcparamseqorcparamseqcoluna
SET o116_formula = '#saldo_inicial'
WHERE o116_codparamrel = 173
AND o116_codseq IN (5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
                          64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
                          112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
                          148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
                          192,193,195,197,199,201,202,203,204,206,207,210,211,212,213,215,218,221,222,223,224,226,228,232,
                          233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,259,260,261,262,263,264,265,266,267,268,269,271,272,
                          274,275,281,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
                          331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369)
      AND o116_orcparamseqcoluna IN (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna in ('previnirecbruta','receitasorcadas'));

-- Adiciona a f�rmula #saldo_inicial+#saldo_prevadic_acum a todas as linhas da coluna prevatualrecbruta que possuem estrutural definido
UPDATE orcparamseqorcparamseqcoluna
SET o116_formula = '#saldo_inicial+#saldo_prevadic_acum'
WHERE o116_codparamrel = 173
AND o116_codseq IN (5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
                          64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
                          112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
                          148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
                          192,193,195,197,199,201,202,203,204,206,207,210,211,212,213,215,218,221,222,223,224,226,228,232,
                          233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,259,260,261,262,263,264,265,266,267,268,269,271,272,
                          274,275,281,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
                          331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369)
      AND o116_orcparamseqcoluna = (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = 'prevatualrecbruta');

-- Adiciona a f�rmula #saldo_arrecadado a todas as linhas da coluna recrealizadabruta que possuem estrutural definido
UPDATE orcparamseqorcparamseqcoluna
SET o116_formula = '#saldo_arrecadado'
WHERE o116_codparamrel = 173
AND o116_codseq IN (5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
                          64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
                          112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
                          148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
                          192,193,195,197,199,201,202,203,204,206,207,210,211,212,213,215,218,221,222,223,224,226,228,232,
                          233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,259,260,261,262,263,264,265,266,267,268,269,271,272,
                          274,275,281,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
                          331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369)
      AND o116_orcparamseqcoluna = (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = 'recrealizadabruta');

-- Adiciona a f�rmula #saldo_arrecadado a todas as linhas da coluna deducrec que possuem estrutural definido
UPDATE orcparamseqorcparamseqcoluna
SET o116_formula = '#saldo_arrecadado'
WHERE o116_codparamrel = 173
AND o116_codseq IN (5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
                          64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
                          112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
                          148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
                          192,193,195,197,199,201,202,203,204,206,207,210,211,212,213,215,218,221,222,223,224,226,228,232,
                          233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,259,260,261,262,263,264,265,266,267,268,269,271,272,
                          274,275,281,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
                          331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369)
      AND o116_orcparamseqcoluna = (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = 'deducrec');

-- Adiciona a f�rmula #saldo_arrecadado a todas as linhas da coluna deducformacfundeb que possuem estrutural definido
UPDATE orcparamseqorcparamseqcoluna
SET o116_formula = '#saldo_arrecadado'
WHERE o116_codparamrel = 173
AND o116_codseq IN (5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
                          64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
                          112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
                          148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
                          192,193,195,197,199,201,202,203,204,206,207,210,211,212,213,215,218,221,222,223,224,226,228,232,
                          233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,259,260,261,262,263,264,265,266,267,268,269,271,272,
                          274,275,281,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
                          331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369)
      AND o116_orcparamseqcoluna = (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = 'deducformacfundeb');

-- Adiciona a f�rmula #saldo_arrecadado a todas as linhas da coluna deducformacfundeb que possuem estrutural definido
UPDATE orcparamseqorcparamseqcoluna
SET o116_formula = '#saldo_arrecadado'
WHERE o116_codparamrel = 173
      AND o116_codseq IN (5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
                          64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
                          112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
                          148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
                          192,193,195,197,199,201,202,203,204,206,207,210,211,212,213,215,218,221,222,223,224,226,228,232,
                          233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,259,260,261,262,263,264,265,266,267,268,269,271,272,
                          274,275,281,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
                          331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369)
      AND o116_orcparamseqcoluna = (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = 'totgeralrecliqrealiza');


------------------------------------------------------------
--            FUN��O DE ALTERA��O DE F�RMULAS             --
------------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_formula7710(linha INT, coluna VARCHAR(250), formula VARCHAR(250)) RETURNS VOID AS
$$

DECLARE
  a integer[] := (SELECT string_to_array(
      (SELECT replace(
          (SELECT regexp_replace(formula,'A|B|C|D|E|F|G|H|I|\\[|\\]','','g')),
          '+',',')),
      ','));
  i integer;
  formula1 varchar;
  formula2 varchar := '';

BEGIN
  -- realiza um loop e busca todos os registros
  FOREACH i IN ARRAY a
  LOOP
    RAISE NOTICE '%', i;
    formula1 := (SELECT o116_formula FROM orcparamseqorcparamseqcoluna
    WHERE o116_codparamrel = 173
          AND o116_codseq = i
          AND o116_orcparamseqcoluna = (SELECT o115_sequencial
                                        FROM orcparamseqcoluna
                                        WHERE o115_nomecoluna = coluna)
                 LIMIT 1);

    IF formula1 NOT IN ('#saldo_inicial','#saldo_inicial+#saldo_prevadic_acum','#saldo_arrecadado') THEN
      formula2 := CONCAT(formula2,'(F[',i,'])+');
    ELSE formula2 := CONCAT(formula2,'(L[',i,']->',coluna,')+');
    END IF;
  END LOOP;

  UPDATE orcparamseqorcparamseqcoluna
  SET o116_formula = (SELECT RTRIM(formula2,'+'))
  WHERE o116_codparamrel = 173
        AND o116_codseq = linha
        AND o116_orcparamseqcoluna = (SELECT o115_sequencial
                                      FROM orcparamseqcoluna
                                      WHERE o115_nomecoluna = coluna);
END
$$
language plpgsql;



----------------------------------------------------------
--            FUN��O DE CHAMADA DE F�RMULAS             --
----------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_chamaformula7710(linha INT, c1 VARCHAR(250), c2 VARCHAR(250), c3 VARCHAR(250), c4 VARCHAR(250), c5 VARCHAR(250), c6 VARCHAR(250), c7 VARCHAR(250), c8 VARCHAR(250)) RETURNS VOID AS
$$

BEGIN
  PERFORM fc_formula7710(linha,'previnirecbruta',c1);
  PERFORM fc_formula7710(linha,'prevatualrecbruta',c2);
  PERFORM fc_formula7710(linha,'recrealizadabruta',c3);
  PERFORM fc_formula7710(linha,'deducrec',c4);
  PERFORM fc_formula7710(linha,'recrealizadabasecalcpercaplicasps',c5);
  PERFORM fc_formula7710(linha,'deducformacfundeb',c6);
  PERFORM fc_formula7710(linha,'totgeralrecliqrealiza',c7);
  PERFORM fc_formula7710(linha,'receitasorcadas',c8);
END
$$
language plpgsql;


-----------------------------------------------------------
--            FUN��O DE ALTERA��O DE F�RMULA             --
-----------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_alterarformula7710(linha INT, col INT, formula VARCHAR(120)) RETURNS VOID AS
$$

DECLARE
  coluna VARCHAR(50);

BEGIN
  IF col=1
  THEN coluna='previnirecbruta';
  END IF;
  IF col=2
  THEN coluna='prevatualrecbruta';
  END IF;
  IF col=3
  THEN coluna='recrealizadabruta';
  END IF;
  IF col=4
  THEN coluna='deducrec';
  END IF;
  IF col=5
  THEN coluna='recrealizadabasecalcpercaplicasps';
  END IF;
  IF col=6
  THEN coluna='deducformacfundeb';
  END IF;
  IF col=7
  THEN coluna='totgeralrecliqrealiza';
  END IF;
  IF col=8
  THEN coluna='receitasorcadas';
  END IF;

  UPDATE orcparamseqorcparamseqcoluna
  SET o116_formula = formula
  WHERE o116_codparamrel = 173
        AND o116_orcparamseqcoluna = (SELECT o115_sequencial FROM orcparamseqcoluna WHERE o115_nomecoluna = coluna)
        AND o116_codseq = linha;

END
$$
language plpgsql;


--------------------------------------------------------------------
--            FUN��O DE ALTERA��O DE F�RMULAS EM LOTE             --
--------------------------------------------------------------------
CREATE OR REPLACE FUNCTION fc_formulalote7710() RETURNS VOID AS
$$

DECLARE
  a integer[] := array[5,6,8,9,10,13,14,18,21,22,23,28,30,32,34,36,38,40,42,43,44,45,46,47,49,51,58,59,60,61,62,63,
  64,65,66,67,68,69,75,83,84,89,90,91,92,94,96,97,98,99,100,101,102,104,105,106,107,108,109,110,
  112,113,114,115,116,117,118,119,120,123,124,125,126,128,130,131,132,133,134,137,138,140,147,
  148,150,151,152,156,161,165,168,169,172,173,175,176,177,179,180,181,182,183,184,188,189,191,
  192,193,195,197,199,201,202,203,204,206,207,210,211,212,215,218,221,222,223,224,226,228,232,
  233,234,235,240,241,242,243,244,247,248,249,250,251,252,253,263,264,265,267,268,269,271,272,
  274,275,286,291,296,297,300,301,304,305,307,308,309,310,312,313,315,316,318,323,324,326,329,
  331,332,335,337,339,341,343,345,347,349,350,351,352,353,355,356,357,361,363,364,366,368,369];
  i integer;

BEGIN
  -- realiza um loop e busca todos os registros
  FOREACH i IN ARRAY a
  LOOP
    PERFORM fc_alterarformula7710(i,5,CONCAT('(C[',i,',3])-(C[',i,',4])'));
    PERFORM fc_alterarformula7710(i,7,CONCAT('(C[',i,',3])-(C[',i,',4])-(C[',i,',6])'));
  END LOOP;

END
$$
language plpgsql;


SELECT fc_formulalote7710();

-- Fim do script

COMMIT;