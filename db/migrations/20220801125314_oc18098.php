<?php

use Phinx\Migration\AbstractMigration;

class Oc18098 extends AbstractMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

         
        ALTER TABLE pagordem ADD COLUMN e50_cattrabalhador int4;
                    ALTER TABLE pagordem ADD COLUMN e50_empresadesconto int4;
                    ALTER TABLE pagordem ADD COLUMN e50_contribuicaoprev char;
                    ALTER TABLE pagordem ADD COLUMN e50_valorremuneracao float8;
                    ALTER TABLE pagordem ADD COLUMN e50_valordesconto float8;
                    ALTER TABLE pagordem ADD COLUMN  e50_datacompetencia date;

        Create table IF NOT EXISTS contabilidade.categoriatrabalhador(
                    ct01_codcategoria int8 not null,
                    ct01_descricaocategoria varchar(500) not null
                );
       
        INSERT INTO CATEGORIATRABALHADOR (CT01_CODCATEGORIA,CT01_DESCRICAOCATEGORIA) VALUES
        ('101','EMPREGADO - GERAL  INCLUSIVE O EMPREGADO P�BLICO DA ADMINISTRA��O DIRETA OU INDIRETA CONTRATADO PELA CLT'),
        ('102','EMPREGADO - TRABALHADOR RURAL POR PEQUENO PRAZO DA LEI 11.718/2008'),
        ('103','EMPREGADO - APRENDIZ'),
        ('104','EMPREGADO - DOM�STICO'),
        ('105','EMPREGADO - CONTRATO A TERMO FIRMADO NOS TERMOS DA LEI 9.601/1998'),
        ('106','TRABALHADOR TEMPOR�RIO - CONTRATO NOS TERMOS DA LEI 6.019/1974'),
        ('107','EMPREGADO - CONTRATO DE TRABALHO VERDE E AMARELO - SEM ACORDO PARA ANTECIPA��O MENSAL DA MULTA RESCIS�RIA DO FGTS'),
        ('108','EMPREGADO - CONTRATO DE TRABALHO VERDE E AMARELO - COM ACORDO PARA ANTECIPA��O MENSAL DA MULTA RESCIS�RIA DO FGTS'),
        ('111','EMPREGADO - CONTRATO DE TRABALHO INTERMITENTE'),
        ('201','TRABALHADOR AVULSO PORTU�RIO'),
        ('202','TRABALHADOR AVULSO N�O PORTU�RIO'),
        ('301','SERVIDOR P�BLICO TITULAR DE CARGO EFETIVO  MAGISTRADO  MINISTRO DE TRIBUNAL DE CONTAS  CONSELHEIRO DE TRIBUNAL DE CONTAS E MEMBRO DO MINIST�RIO P�BLICO'),
        ('302','SERVIDOR P�BLICO OCUPANTE DE CARGO EXCLUSIVO EM COMISS�O'),
        ('303','EXERCENTE DE MANDATO ELETIVO'),
        ('304','SERVIDOR P�BLICO EXERCENTE DE MANDATO ELETIVO  INCLUSIVE COM EXERC�CIO DE CARGO EM COMISS�O'),
        ('305','SERVIDOR P�BLICO INDICADO PARA CONSELHO OU �RG�O DELIBERATIVO  NA CONDI��O DE REPRESENTANTE DO GOVERNO  �RG�O OU ENTIDADE DA ADMINISTRA��O P�BLICA'),
        ('306','SERVIDOR P�BLICO CONTRATADO POR TEMPO DETERMINADO  SUJEITO A REGIME ADMINISTRATIVO ESPECIAL DEFINIDO EM LEI PR�PRIA'),
        ('307','MILITAR'),
        ('308','CONSCRITO'),
        ('309','AGENTE P�BLICO - OUTROS'),
        ('310','SERVIDOR P�BLICO EVENTUAL'),
        ('311','MINISTROS  JU�ZES  PROCURADORES  PROMOTORES OU OFICIAIS DE JUSTI�A � DISPOSI��O DA JUSTI�A ELEITORAL'),
        ('312','AUXILIAR LOCAL'),
        ('313','SERVIDOR P�BLICO EXERCENTE DE ATIVIDADE DE INSTRUTORIA  CAPACITA��O  TREINAMENTO  CURSO OU CONCURSO  OU CONVOCADO PARA PARECERES T�CNICOS OU DEPOIMENTOS'),
        ('401','DIRIGENTE SINDICAL - INFORMA��O PRESTADA PELO SINDICATO'),
        ('410','TRABALHADOR CEDIDO/EXERC�CIO EM OUTRO �RG�O/JUIZ AUXILIAR - INFORMA��O PRESTADA PELO CESSION�RIO/DESTINO'),
        ('501','DIRIGENTE SINDICAL - SEGURADO ESPECIAL'),
        ('701','CONTRIBUINTE INDIVIDUAL - AUT�NOMO EM GERAL  EXCETO SE ENQUADRADO EM UMA DAS DEMAIS CATEGORIAS DE CONTRIBUINTE INDIVIDUAL'),
        ('711','CONTRIBUINTE INDIVIDUAL - TRANSPORTADOR AUT�NOMO DE PASSAGEIROS'),
        ('712','CONTRIBUINTE INDIVIDUAL - TRANSPORTADOR AUT�NOMO DE CARGA'),
        ('721','CONTRIBUINTE INDIVIDUAL - DIRETOR N�O EMPREGADO  COM FGTS'),
        ('722','CONTRIBUINTE INDIVIDUAL - DIRETOR N�O EMPREGADO  SEM FGTS'),
        ('723','CONTRIBUINTE INDIVIDUAL - EMPRES�RIO  S�CIO E MEMBRO DE CONSELHO DE ADMINISTRA��O OU FISCAL'),
        ('731','CONTRIBUINTE INDIVIDUAL - COOPERADO QUE PRESTA SERVI�OS POR INTERM�DIO DE COOPERATIVA DE TRABALHO'),
        ('734','CONTRIBUINTE INDIVIDUAL - TRANSPORTADOR COOPERADO QUE PRESTA SERVI�OS POR INTERM�DIO DE COOPERATIVA DE TRABALHO'),
        ('738','CONTRIBUINTE INDIVIDUAL - COOPERADO FILIADO A COOPERATIVA DE PRODU��O'),
        ('741','CONTRIBUINTE INDIVIDUAL - MICROEMPREENDEDOR INDIVIDUAL'),
        ('751','CONTRIBUINTE INDIVIDUAL - MAGISTRADO CLASSISTA TEMPOR�RIO DA JUSTI�A DO TRABALHO OU DA JUSTI�A ELEITORAL QUE SEJA APOSENTADO DE QUALQUER REGIME PREVIDENCI�RIO'),
        ('761','CONTRIBUINTE INDIVIDUAL - ASSOCIADO ELEITO PARA DIRE��O DE COOPERATIVA  ASSOCIA��O OU ENTIDADE DE CLASSE DE QUALQUER NATUREZA OU FINALIDADE  BEM COMO O S�NDICO OU ADMINISTRADOR ELEITO PARA EXERCER ATIVIDADE DE DIRE��O CONDOMINIAL  DESDE QUE RECEBAM REMUNERA��O'),
        ('771','CONTRIBUINTE INDIVIDUAL - MEMBRO DE CONSELHO TUTELAR  NOS TERMOS DA LEI 8.069/1990'),
        ('781','MINISTRO DE CONFISS�O RELIGIOSA OU MEMBRO DE VIDA CONSAGRADA  DE CONGREGA��O OU DE ORDEM RELIGIOSA'),
        ('901','ESTAGI�RIO'),
        ('902','M�DICO RESIDENTE OU RESIDENTE EM �REA PROFISSIONAL DE SA�DE'),
        ('903','BOLSISTA'),
        ('904','PARTICIPANTE DE CURSO DE FORMA��O  COMO ETAPA DE CONCURSO P�BLICO  SEM V�NCULO DE EMPREGO/ESTATUT�RIO'),
        ('906','BENEFICI�RIO DO PROGRAMA NACIONAL DE PRESTA��O DE SERVI�O CIVIL VOLUNT�RIO');


        COMMIT;

SQL;
        $this->execute($sql);
    } 
}