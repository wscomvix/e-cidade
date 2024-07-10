insert into db_versao (db30_codver, db30_codversao, db30_codrelease, db30_data, db30_obs)  values (350, 3, 34, '2015-01-08', 'Tarefas: 97239, 97273, 97334, 97337, 97349, 97350, 97352, 97353, 97354, 97356, 97358, 97359, 97360, 97361, 97362, 97363, 97366, 97368, 97369, 97370, 97371, 97374, 97375, 97376, 97378, 97379, 97383, 97386, 97387, 97388, 97389, 97390, 97391, 97392, 97393, 97394, 97400, 97401, 97404, 97405, 97406, 97415, 97416, 97418, 97419, 97421, 97422, 97423, 97424, 97425, 97427, 97428, 97429, 97430, 97433, 97434, 97435, 97436, 97437, 97438, 97439, 97440, 97441, 97442, 97443, 97445, 97446, 97447, 97448, 97449, 97450, 97452, 97454, 97455, 97457, 97458, 97459, 97460, 97461, 97462, 97464');DROP function if EXISTS "db_fxxx" (integer,integer,integer,integer);
CREATE OR REPLACE FUNCTION "db_fxxx" (integer,integer,integer,integer) 
RETURNS varchar(250) 
AS 
$$

DECLARE

    REGISTRO   ALIAS FOR $1;
    ANO        ALIAS FOR $2;
    MES        ALIAS FOR $3;
    INSTIT     ALIAS FOR $4;
    
    HRSSEM            FLOAT4  := 0;
    F001	            FLOAT4  := 0;
    F002	            FLOAT4  := 0;
    F003	            DATE;
    F004	            INTEGER := 0;
    F005	            INTEGER := 0;
    F006	            INTEGER := 0;
    F006_CLT          INTEGER := 0;
    F008	            FLOAT4  := 0;
    F009	            INTEGER := 0;
    F007	            FLOAT8  := 0;
    F010	            FLOAT8  := 0;
    F011	            FLOAT4  := 0;
    F012	            INTEGER := 0;
    F013	            INTEGER := 0;
    F014	            INTEGER := 0;
    F015	            FLOAT4  := 0;
    F022	            INTEGER := 0;
    F024	            FLOAT4  := 0;
    F025	            INTEGER := 0;
    F026	            VARCHAR(25) := ' ';
    F030	            FLOAT8  := 0;
    D913	            FLOAT8  := 0;
    D908	            FLOAT8  := 0;
    VALOR_PADRAO      FLOAT8  := 0;
    VALOR_PADRAO_PREV FLOAT8  := 0;
    valor_progress    FLOAT8  := 0;
    DATA_BASE         DATE;
    DATA_PROGR        DATE;
    DATA_TRIEN        DATE;
    DATA_ATUAL        DATE;
    DIVERSOMINIMO     varchar(4) := '    ';
    DIVERSOMINIMOPREV varchar(4) := '    ';
    PADRAO            varchar(25);
    VALMIN            FLOAT8 := 0;
    ANOS              INTEGER := 0;
    PERC_PROG         FLOAT4  := 0;
    VALOR_PROG        FLOAT8  := 0;
    VALOR_PROG_PREV   FLOAT8  := 0;
    MAX_TRIENIO       INTEGER := 0;
    PRIMEIRO_DOANO    DATE;
    
    R_PES        RECORD;
    R_PAD        RECORD;
    R_PAD_PREV   RECORD;
    R_PROGR      RECORD;
    R_CFPESS     RECORD;
    R_DEPEND     RECORD;
    R_FERIAS     RECORD;

    VALORMIN     FLOAT8 := 0;
    VALORMINPREV FLOAT8 := 0;

BEGIN

FOR R_PES IN SELECT rh01_regist     as r01_regist,
                    rh30_regime     as r01_regime,
                    rh03_padrao     as r01_padrao,
                    rh03_padraoprev as r01_padraoprev,
                    rh02_hrssem     as r01_hrssem,
                    rh02_salari     as r01_salari,
                    rh30_vinculo    as r01_tpvinc,
                    rh01_admiss     as r01_admiss,
                    rh01_progres    as r01_anter,
                    case when rh01_progres is not null 
                         then 'S' 
                         else 'N' 
                    end as r01_progr,
                    rh01_trienio as r01_trien,
                    rh01_nasc    as r01_nasc,
                    rh02_tbprev  as r01_tbprev
    FROM RHPESSOAL
         INNER JOIN CGM             ON RH01_NUMCGM    = Z01_NUMCGM
         INNER JOIN RHPESSOALMOV    ON RH02_ANOUSU    = ANO
                                   AND RH02_MESUSU    = MES
                                   AND RH02_REGIST    = REGISTRO
                                   AND RH02_INSTIT    = INSTIT
         LEFT JOIN  RHPESRESCISAO   ON RH05_SEQPES    = RH02_SEQPES
         LEFT JOIN  RHPESPADRAO     ON RH02_SEQPES    = RH03_SEQPES
         INNER JOIN RHREGIME        ON RH30_CODREG    = RH02_CODREG
                                   AND RH30_INSTIT    = RH02_INSTIT
         WHERE RH01_REGIST = REGISTRO 
               LOOP
END LOOP;

FOR R_CFPESS IN SELECT * 
                FROM CFPESS 
                WHERE R11_ANOUSU = ANO
                  AND R11_MESUSU = MES
                  AND R11_INSTIT = INSTIT
		ORDER BY R11_ANOUSU DESC ,
		         R11_MESUSU DESC 
	        LIMIT 1 LOOP
END LOOP;

FOR R_FERIAS IN SELECT * 
                FROM CADFERIA 
                WHERE R30_ANOUSU = ANO
   	          AND R30_MESUSU = MES
      	          AND R30_REGIST = REGISTRO 
		ORDER BY R30_PERAI DESC   
	        LIMIT 1 LOOP
END LOOP;

--raise notice 'regime: %', R_Pes.R01_REGIME;
--raise notice 'padrao: %', R_Pes.R01_padrao;

FOR R_PAD IN
   SELECT * 
   FROM PADROES
   WHERE R02_ANOUSU = ANO
     AND R02_MESUSU = MES
     AND R02_INSTIT = INSTIT
     AND R02_REGIME = R_PES.R01_REGIME
     AND R02_CODIGO = R_PES.R01_PADRAO LOOP
END LOOP;

-- Padr�o de previd�ncia
FOR R_PAD_PREV IN
   SELECT *
   FROM PADROES
   WHERE R02_ANOUSU = ANO
     AND R02_MESUSU = MES
     AND R02_INSTIT = INSTIT
     AND R02_REGIME = R_PES.R01_REGIME
     AND R02_CODIGO = R_PES.R01_PADRAOPREV LOOP
END LOOP;

DATA_ATUAL = ano||'-'||mes||'-'||ndias(ano,mes);

-- verifica horas semanais

IF R_PES.R01_HRSSEM <> 0 OR R_PES.R01_HRSSEM <> NULL THEN
   F002 = R_PES.R01_HRSSEM;
ELSE
   IF R_PAD.R02_HRSSEM <> NULL THEN
      F002 = R_PAD.R02_HRSSEM;
   END IF;

END IF;

PADRAO = substr(R_PAD.R02_DESCR,1,25);

-- verifica horas mensais

IF R_PES.R01_HRSSEM <> NULL OR R_PES.R01_HRSSEM > 0 THEN
   F008 = R_PES.R01_HRSSEM * 5;
ELSE
   F008 = F002 * 5;
END IF;

-- verifica salario sem progressao

IF R_PES.R01_SALARI <> NULL OR R_PES.R01_SALARI > 0 THEN
   F007 = R_PES.R01_SALARI;
   F010 = R_PES.R01_SALARI;
ELSE
--raise notice 'padrao : %', R_PES.R01_PADRAO;
   IF R_PES.R01_PADRAO <> '' AND R_PES.R01_PADRAO IS NOT NULL THEN
      IF R_PAD.R02_TIPO = 'H' THEN 
         VALOR_PADRAO = ROUND(R_PAD.R02_VALOR*F008,2);
      ELSE
         VALOR_PADRAO = R_PAD.R02_VALOR;
      END IF;
      IF R_PES.R01_HRSSEM > 0 AND R_PAD.R02_HRSSEM > 0 THEN
         F007 = ((VALOR_PADRAO/R_PAD.R02_HRSSEM)*R_PES.R01_HRSSEM);
         F010 = ((VALOR_PADRAO/R_PAD.R02_HRSSEM)*R_PES.R01_HRSSEM);
      ELSE
         F007 = VALOR_PADRAO;
         F010 = VALOR_PADRAO;
      END IF;
      DIVERSOMINIMO = R_PAD.R02_MINIMO;
   ELSE
      F007 = 0;
      F010 = 0;
   END IF;
END IF;

-- Padr�o de previd�ncia
IF R_PES.R01_PADRAOPREV <> '' AND R_PES.R01_PADRAOPREV IS NOT NULL THEN
  IF R_PAD_PREV.R02_TIPO = 'H' THEN
     VALOR_PADRAO_PREV = ROUND(R_PAD_PREV.R02_VALOR*F008,2);
  ELSE
     VALOR_PADRAO_PREV = R_PAD_PREV.R02_VALOR;
  END IF;
  IF R_PES.R01_HRSSEM > 0 AND R_PAD_PREV.R02_HRSSEM > 0 THEN
     F030 = ((VALOR_PADRAO_PREV/R_PAD_PREV.R02_HRSSEM)*R_PES.R01_HRSSEM);
  ELSE
     F030 = VALOR_PADRAO_PREV;
  END IF;
  DIVERSOMINIMOPREV = R_PAD_PREV.R02_MINIMO;
ELSE
  F030 = 0;
END IF;

--raise notice 'f007: %', f007;
--raise notice 'f010: %', f010;

-- data base

IF R_PES.R01_TPVINC = 'A' THEN
   DATA_BASE = R_CFPESS.R11_DATAF;
ELSE
   DATA_BASE = R_PES.R01_ADMISS;
END IF;

-- data de progressao

IF R_PES.R01_ANTER IS NULL THEN
   DATA_PROGR = R_PES.R01_ADMISS;
ELSE
   DATA_PROGR = R_PES.R01_ANTER;
END IF;

-- data para trienio

IF R_PES.R01_TRIEN IS NULL THEN
   DATA_TRIEN = R_PES.R01_ADMISS;
ELSE
   DATA_TRIEN = R_PES.R01_TRIEN;
END IF;

ANOS = fc_idade(DATA_PROGR,DATA_BASE);

-- ANOS PARA AVAN�O
F012 = fc_idade(DATA_PROGR,DATA_BASE);

-- QUANTIDADE DE TRIENIOS 
F013 = round(fc_idade(DATA_TRIEN, DATA_BASE)/3) ;

-- MESES DE PROGRESSAO

F024 = FC_CONTA_MESES( DATA_PROGR, DATA_BASE );

F024 = F024 ;

IF R_PES.R01_PROGR = 'S'  AND ( R_PES.R01_SALARI = 0 OR R_PES.R01_SALARI IS NULL ) THEN

  FOR R_PROGR IN SELECT * 
                 FROM PROGRESS 
                 WHERE R24_ANOUSU = ANO
                   AND R24_MESUSU = MES
                   AND R24_INSTIT = INSTIT
                   AND R24_PADRAO = R_PES.R01_PADRAO
                   AND R24_REGIME = R_PES.R01_REGIME
		   AND R24_MESES <= F024
	         ORDER BY R24_MESES
                 LOOP
       F014 = F014+1;
       F015 = R_PROGR.R24_PERC;

       IF R_PES.R01_HRSSEM > 0 AND R_PAD.R02_HRSSEM > 0 THEN
          VALOR_PROG = ((R_PROGR.R24_VALOR/R_PAD.R02_HRSSEM)*R_PES.R01_HRSSEM);
       ELSE
          VALOR_PROG = R_PROGR.R24_VALOR;
       END IF;

       -- Padr�o de previd�ncia
       IF R_PES.R01_HRSSEM > 0 AND R_PAD_PREV.R02_HRSSEM > 0 THEN
          VALOR_PROG_PREV = ((R_PROGR.R24_VALOR/R_PAD_PREV.R02_HRSSEM)*R_PES.R01_HRSSEM);
       ELSE
          VALOR_PROG_PREV = R_PROGR.R24_VALOR;
       END IF;

       PERC_PROG  = R_PROGR.R24_PERC;
       PADRAO = substr(R_PROGR.R24_DESCR,1,25);
   END LOOP;
   IF VALOR_PROG > 0 THEN 
      F010 = VALOR_PROG;
      -- * caso padrao por hora e avaliacao chamada pela consulta de funcionario
      IF R_PAD.R02_TIPO = 'H' THEN
         F010 = F010 * F008;
      END IF;
--   ELSE
--      F010 += round(F007*round(PERC_PROG::FLOAT/100,2),2);
   END IF;

   -- Padr�o de previd�ncia
   IF VALOR_PROG_PREV > 0 THEN
      F030 = VALOR_PROG;
      IF R_PAD_PREV.R02_TIPO = 'H' THEN
         F030 = F030 * F008;
      END IF;
   END IF;

   IF DIVERSOMINIMO <> '    ' THEN
      SELECT R07_VALOR 
      INTO VALORMIN
      FROM PESDIVER 
      WHERE R07_ANOUSU = ANO
        AND R07_MESUSU = MES
        AND R07_INSTIT = INSTIT
      	AND R07_CODIGO = DIVERSOMINIMO;
      IF F010 < VALORMIN THEN
         F010 = VALORMIN;
      END IF;
   END IF;

   -- Padr�o de previd�ncia
   IF DIVERSOMINIMOPREV <> '    ' THEN
      SELECT R07_VALOR
      INTO VALORMINPREV
      FROM PESDIVER
      WHERE R07_ANOUSU = ANO
        AND R07_MESUSU = MES
        AND R07_INSTIT = INSTIT
      	AND R07_CODIGO = DIVERSOMINIMOPREV;
      IF F030 < VALORMINPREV THEN
         F030 = VALORMINPREV;
      END IF;
   END IF;
END IF;

-- calcula salario hora

-- raise notice 'f007 : %', f007;

IF (R_PES.R01_HRSSEM = 0 OR R_PES.R01_HRSSEM IS NULL) then
--   OR (R_PAD.R02_HRSSEM = 0 OR R_PAD.R02_HRSSEM IS NULL) THEN
   IF F002 > 0 THEN
      F001 = F007/(F002 * 5);
      F011 = F010/(F002 * 5);
   ELSE
      F001 = 0;
      F011 = 0;
   END IF;
END IF;

--raise notice 'regist: %', R_pes.r01_regist;
-- DATA DE ADMISSAO

F003 = R_PES.R01_ADMISS;

SELECT R07_VALOR 
FROM PESDIVER
INTO MAX_TRIENIO
WHERE R07_ANOUSU = ANO
  AND R07_MESUSU = MES
  AND R07_INSTIT = INSTIT
  AND R07_CODIGO = 'D909';

--raise notice 'max trienio : %', max_trienio;
--raise notice 'F013        : %', F013;
IF F013 > MAX_TRIENIO AND MAX_TRIENIO > 0 THEN
   F013 = MAX_TRIENIO;
END IF;

-- QUANTIDADE DE QUINQUENIOS
F022 = (DATA_BASE - DATA_PROGR) / 1825;

--raise notice 'DATA        : %', DATA_ATUAL;
-- IDADE DO FUNCIONARIO
F004 = (DATA_ATUAL - R_PES.R01_NASC) / 365;

-- DEPENDENTES PARA IRF
F005 = 0;

-- DEPENDENTES PARA SAL. FAM
F006 = 0;

FOR R_DEPEND IN SELECT * 
               FROM RHDEPEND 
               WHERE RH31_REGIST = R_PES.R01_REGIST
               LOOP
	       
    SELECT R07_VALOR 
      FROM PESDIVER
      INTO D908
     WHERE R07_ANOUSU = ANO
       AND R07_MESUSU = MES
       AND R07_INSTIT = INSTIT
       AND R07_CODIGO = 'D908';
      
   SELECT R07_VALOR
     FROM PESDIVER
     INTO D913
    WHERE R07_ANOUSU = ANO
      AND R07_MESUSU = MES
      AND R07_INSTIT = INSTIT
      AND R07_CODIGO = 'D913';
      
   IF R_DEPEND.RH31_DEPEND = 'C' THEN
      IF ( R_PES.R01_REGIME = 1 OR R_PES.R01_REGIME = 3 ) AND R_PES.R01_TBPREV = R_CFPESS.R11_TBPREV THEN
         IF ((DATA_ATUAL - R_DEPEND.RH31_DTNASC)/365) < D913 THEN
               F006 = F006 + 1;
         END IF;
         IF ((DATA_ATUAL - R_DEPEND.RH31_DTNASC)/365) < D908 THEN
               F006_CLT = F006_CLT;
         END IF;

      ELSE 
         IF R_PES.R01_REGIME = 1 OR R_PES.R01_REGIME = 3 THEN 
            IF ((DATA_ATUAL - R_DEPEND.RH31_DTNASC)/365) < D913 THEN
                 F006 = F006 + 1;
            END IF;            
         ELSE
            IF ((DATA_ATUAL - R_DEPEND.RH31_DTNASC)/365) < D908 THEN
               F006 = F006 + 1;
            END IF;   
	 END IF;
      END IF;
   ELSE 
      IF R_DEPEND.RH31_DEPEND = 'S' THEN
         F006 = F006 + 1;
         F006_CLT = F006_CLT + 1;
      END IF;
   END IF;
   IF R_DEPEND.RH31_IRF != '0' THEN
      IF R_DEPEND.RH31_IRF IN ('1', '4', '5', '6', '7') THEN
         F005 = F005 + 1;
      ELSE 
         IF R_DEPEND.RH31_IRF IN ('2', '3') THEN
            IF ((DATA_ATUAL - R_DEPEND.RH31_DTNASC)/365) <= 21 THEN
               F005 = F005 + 1;
            END IF;
         END IF;
      END IF;
   END IF;
END LOOP;
 
IF (R_PES.R01_TBPREV = 0 OR R_PES.R01_TBPREV IS NULL) AND (R_PES.R01_REGIME = 2 OR R_PES.R01_REGIME = 3) THEN
   F006 = 0;
   F006_CLT = 0;
END IF;

PRIMEIRO_DOANO = ANO||'-'||01||'-'||01;

-- Mese para 13 salario
IF F003 < PRIMEIRO_DOANO THEN
   F009 = 12;
ELSE
   IF EXTRACT( DAY FROM F003) > 15 THEN
      F009 = (13 - EXTRACT( MONTH FROM F003)) -1;
   ELSE
      F009 = (13 - EXTRACT( MONTH FROM F003));
   END IF;
END IF;

-- dias do mes
--raise notice 'ano        : %', ano;
--raise notice 'mes        : %', mes;
--raise notice 'dias        : %', ndias(ano,mes);

select ndias(ano,mes) into F025;

-- F026 = PADRAO;

if padrao is not null then
  F026 = PADRAO;
end if;

-- F001 - 001,11 
-- F002 - 012,11 
-- F003 - 023,11 
-- F004 - 034,11 
-- F005 - 045,11 
-- F006 - 056,11 
-- F006 - 067,11 
-- F007 - 078,11 
-- F008 - 089,11 
-- F009 - 100,11
-- F010 - 111,11
-- F011 - 122,11
-- F012 - 133,11
-- F013 - 144,11
-- F014 - 155,11
-- F015 - 166,11
-- F022	- 177,11
-- F024 - 188,11
-- F025 - 199,11
-- F030 - 111,11
-- F026 -
RETURN TO_CHAR(F001,'9999999.99')||
       TO_CHAR(F002,'9999999.99')||
       ' '||F003||
       TO_CHAR(F004,'9999999.99')||
       TO_CHAR(F005,'9999999.99')||
       TO_CHAR(F006,'9999999.99')||
       TO_CHAR(F006_CLT,'9999999.99')||
       TO_CHAR(F007,'9999999.99')||
       TO_CHAR(F008,'9999999.99')||
       TO_CHAR(F009,'9999999.99')||
       TO_CHAR(F010,'9999999.99')||
       TO_CHAR(F011,'9999999.99')||
       TO_CHAR(F012,'9999999.99')||
       TO_CHAR(F013,'9999999.99')||
       TO_CHAR(F014,'9999999.99')||
       TO_CHAR(F015,'9999999.99')||
       TO_CHAR(F022,'9999999.99')||
       TO_CHAR(F024,'9999999.99')||
       TO_CHAR(F025,'9999999.99')||
       TO_CHAR(F030,'9999999.99')||
       ' '||F026;

END;

$$

LANGUAGE plpgsql;create or replace function fc_autodeinfracao( integer ) returns varchar(200) as
$$
declare

	iCodigoAutoInfracao	alias for $1;

	iDiasVencimento         integer;
	iInscricao		          integer;
	iMatricula		          integer;
	fValorInflator	        float8;
	fValorDebito            float8  default 0;
  fValorTotalDebito       float8  default 0;
  fValorLevantamento      float8  default 0;
  fValorTotalLevantamento float8  default 0;
  lConsultaLevantamento   boolean default false;
	iNumCgm		              integer;
	dDataVencimento	        date;
	iTipoDebito			        integer;
	iHistorico		          integer;
  iParFiscalReceita       integer;
  iParFiscalReceitaExp    integer;
	iNumpre		              integer;
	iReceita		            integer;
	dDataHoje			          date;
	iAnoSessao	            integer;
	iArrecant 		          integer;
	iArrecad	  	          integer;

  iInstitSessao           integer;

  r_fiscaltipo            record;
  r_autonumpre            record;
  r_autolevanta           record;

  lRaise                  boolean default false;
  lAbatimento             boolean default false;

begin

  /**
   * Busca Dados Sess�o
   */
  iInstitSessao := cast(fc_getsession('DB_instit') as integer);
  iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
  lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

  if lRaise is true then

    if trim(fc_getsession('db_debug')) <> '' then
      perform fc_debug('Iniciando Calculo do Auto de Infracao... Auto: ' || iCodigoAutoInfracao, lRaise, false, false);
    else
      perform fc_debug('Iniciando Calculo do Auto de Infracao... Auto: ' || iCodigoAutoInfracao, lRaise, true, false);
    end if;
  end if;

  if iInstitSessao is null then
    raise exception 'Variavel de sessao [DB_instit] nao encontrada';
  end if;

  select CURRENT_date	into dDataHoje;

  /**
   * Busca o valor do inflator configurando para o ano
   * Caso nao encontre, deve retornar o erro e n�o mais
   * utilizar 1 como default
   */
  perform fc_debug('Busca inflator na cissqn');

  select distinct i02_valor
         into fValorInflator
    from cissqn
         inner join infla on q04_inflat = i02_codigo
   where cissqn.q04_anousu       = iAnoSessao
     and date_part('y',I02_DATA) = iAnoSessao;

  if fValorInflator is null then
    return 'Valor do inflator n�o configurado corretamente. Verifique a configura��o de C�lculo do ISSQN.';
  end if;

  perform fc_debug('Inflator utilizado fValorInflator: ' || fValorInflator);

  /**
   * Verifica se existe Pagamento Parcial para o d�bito informado
   */
  perform fc_debug('Verifica se existe Pagamento Parcial para o Auto');
  for r_autonumpre in select distinct y17_numpre
                        from autonumpre
                       where y17_codauto = iCodigoAutoInfracao
  loop

  select fc_verifica_abatimento( 1, r_autonumpre.y17_numpre )::boolean into lAbatimento;

  if lAbatimento then

    perform fc_debug('Encontrado Pagamento Parcial. Numpre: ' || r_autonumpre.y17_numpre );
	  return 'Opera��o Cancelada! D�bito possui pagamento parcial. Numpre: ' || r_autonumpre.y17_numpre;
	end if;

  end loop;

  /**
   * Verifica se existe data de vencimento no auto
   */
  perform fc_debug('Verifica se auto possui data de vencimento informada');
  select y50_dtvenc
         into dDataVencimento
    from auto
   where y50_codauto = iCodigoAutoInfracao;

  if dDataVencimento is null then

    perform fc_debug('Data de vencimento do auto n�o informada.');
    return 'Auto sem vencimento.';
  end if;

  /**
   * Verifica se existe tipo de debito e historico configurado
   */
  perform fc_debug('Verifica se existe tipo de debito e historico configurado na parfiscal');

  select y32_tipo, y32_hist, y32_receit, y32_receitexp
         into iTipoDebito, iHistorico, iParFiscalReceita, iParFiscalReceitaExp
    from parfiscal
   where y32_instit = iInstitSessao;

  if iTipoDebito is null then

    perform fc_debug('Parametros da parfiscal nao configurados.');
    return 'Parametros de Tipo de D�bito e Hist�rico n�o configurados. Verifique os par�metros do Fiscal.';
  end if;

 /**
  * Verifica se existe procedencias vinculadas
  */
  perform fc_debug('Verifica se existe pelo menos uma procedencia vinculada');
  perform 1
     from autotipo
    where y59_codauto = iCodigoAutoInfracao;

  if not found then

    perform fc_debug('Nao encontrou procedencia vinculada ao auto');
    return 'O Auto de infra��o n�o possui proced�ncia vinculada.';
  end if;

  /**
   * Verifica receitas vinculadas
   * Deve haver receitas vinculadas a procedencia, caso nao exista, deve "vincular"
   * a que tiver na aba receita (autorec)
   */
  perform fc_debug('Verifica se existem receitas vinculadas');

  perform *
     from autotipo
          inner join fiscalproc    on y59_codtipo = y29_codtipo
          left  join fiscalprocrec on y45_codtipo = y29_codtipo
          left  join autorec       on y59_codauto = y57_codauto
    where y59_codauto = iCodigoAutoInfracao
      and y45_receit is null
      and y57_receit is null;

  if found then

    perform fc_debug('Nao encontrada receitas vinculadas ao auto');
    return 'O Auto de infra��o possui proced�ncia(s) sem receita(s) vinculada(s).';
  end if;

  /**
   * Verifica se existe pelo menos uma procedencia de percentual,
   * DEVE haver levantamento cadastrado
   */
  perform fc_debug('Verifica se existe pelo menos uma procedencia de percentual, DEVE haver levantamento cadastrado...');

  perform 1
     from autotipo
    where y59_codauto = iCodigoAutoInfracao
      and exists ( select 1
                     from fiscalproc
                          inner join fiscalprocrec on y45_codtipo = y29_codtipo
                                                  and y45_percentual is true
                    where y59_codtipo = y29_codtipo );
  if found then

    perform fc_debug('Encontrou pelo menos uma procedencia de percentual');
    perform fc_debug('Verifica se existe levantamento');

    /**
     * Setamos a variavel para buscar os levantamentos para calculo
     */
    lConsultaLevantamento = true;

    perform 1
       from autolevanta
            inner join levanta  on y60_codlev = y117_levanta
            inner join levvalor on y60_codlev = y63_codlev
      where y117_auto = iCodigoAutoInfracao;

    if not found then

      perform fc_debug('Nao encontrou levantamentos');
      return 'O Auto de infra��o possui proced�ncia de percentual vinculada, por�m, n�o foi encontrado levantamento ou o mesmo n�o possui valores informados.';
    end if;

  end if;

  perform fc_debug('Fim das verificacoes de parametros, receitas e procedencias. Prosseguindo com o calculo');
  /**
   * Fim das verifica��es de parametros, receitas e procedencias
   */

  /**
   * Busca vinculos do auto ( CGM, Inscri��o, Matr�cula )
   */
  perform fc_debug('');
  perform fc_debug('CHAMANDO fc_autodeinfracao_getVinculos...');

  select iAutoNumCgm, iAutoMatricula, iAutoInscricao
    into iNumCgm, iMatricula, iInscricao
    from fc_autodeinfracao_getVinculos( iCodigoAutoInfracao );

  perform fc_debug('FIM fc_autodeinfracao_getVinculos...');
  perform fc_debug('RESPOSTA fc_autodeinfracao_getVinculos -> iNumCgm: ' || coalesce(iNumCgm,0) || ' iMatricula: ' || coalesce(iMatricula,0) || ' iInscricao: ' || coalesce(iInscricao,0) );
  perform fc_debug('');

  /**
   * Verifica se ja existe numpre vinculado ao auto
   */
  perform fc_debug('Verifica se numpre ja existe numpre vinculado ao auto');

  select y17_numpre
    into iNumpre
    from autonumpre
   where y17_codauto = iCodigoAutoInfracao;

  if iNumpre = 0 OR iNumpre is null then

    perform fc_debug('Numpre nulo ou Numpre 0 (zero)');

    select nextval('numpref_k03_numpre_seq') into iNumpre;
    insert into autonumpre (y17_numpre, y17_codauto) values (iNumpre, iCodigoAutoInfracao);

    if iInscricao is not null then

      insert into arreinscr  VALUES (iNumpre, iInscricao,100);
      perform fc_debug('Inserindo numpre na arreinscr -> Numpre: ' || iNumpre ||' Inscricao: ' || iInscricao);
    end if;

  else

    perform fc_debug('Numpre ja existe na autonumpre');

    /**
     * Verifica se numpre j� esta pago
     */
    select k00_numpre
           into iArrecant
      from arrecant
     where k00_numpre = iNumpre;

    if iArrecant is not null then

      perform fc_debug('Numpre esta na arrecant - PAGO -> Numpre: ' || iNumpre);
      return  'Auto j� pago.';
    end if;

    /**
     * Verifica se numpre j� esta na arrecad
     */
    select k00_numpre
           into iArrecad
      from arrecad
     where k00_numpre = iNumpre;

    if iArrecad is not null then

      perform fc_debug('Numpre esta na arrecad - ABERTO -> Numpre: ' || iNumpre);
      perform fc_debug('Deletando da arrecad...');
      delete from arrecad where k00_numpre = iNumpre;
    end if;

  end if;

  perform fc_debug('Fim das verificacoes de numpre do auto');

  perform fc_debug('Prosseguindo com o Numpre: ' || iNumpre);

  perform fc_debug('');
  perform fc_debug('Iniciando geracao do debito');

  /**
   * Busca levantamentos para calculo com percentual
   */
  if lConsultaLevantamento is true then

    for r_autolevanta in
        select y117_levanta
          from autolevanta
         where y117_auto = iCodigoAutoInfracao
    loop

      /**
       * Busca valor de total corrigido do levantamento
       */
      select fc_autodeinfracao_getValorLevantamentoCorrigido( r_autolevanta.y117_levanta,
                                                               iParFiscalReceita,
                                                               iParFiscalReceitaExp ) into fValorLevantamento;

      fValorTotalLevantamento = fValorTotalLevantamento + fValorLevantamento;
    end loop;

    perform fc_debug('Valor Total do Levantamento: ' || fValorTotalLevantamento);
    perform fc_debug('');
  end if;

  /**
   * Aplicando regras por procedencia
   */
  for r_fiscaltipo in
      select distinct y29_codtipo,
                      y29_descr,
                      y45_receit,
                      y59_valor,
                      y45_valor,
                      y45_vlrfixo,
                      coalesce( y45_percentual, false) as y45_percentual,
                      y57_receit
        from autotipo
             inner join fiscalproc    on y59_codtipo     = y29_codtipo
             left  join fiscalprocrec on y45_codtipo     = y29_codtipo
             left  join autorec       on y59_codauto     = y57_codauto
             left  join autotipobaixa on y86_codautotipo = y59_codigo
    	 where y59_codauto = iCodigoAutoInfracao
         and y86_codbaixaproc is null
	loop

    fValorDebito = 0;

    /**
     * Quando nao encontra receita na procendencia (fiscalprocred)
     * deve pegar receita vinculada ao auto (autorec)
     */
    iReceita  = r_fiscaltipo.y45_receit;
    if r_fiscaltipo.y45_receit is null then

      perform('Receita nao encontrada na procedencia, alterando receita da procedencia pela receita do auto -> Receita: ' || r_fiscaltipo.y57_receit );
      iReceita = r_fiscaltipo.y57_receit;
    end if;

    /**
     * Verifica se � procedencia de Valor
     */
    if r_fiscaltipo.y45_percentual is false then

      perform fc_debug('Procedencia de valor -> CodTipo: ' || r_fiscaltipo.y29_codtipo);

      /**
       * Valor variavel ou nao esta definido valor na fiscalprocrec
       */
      if r_fiscaltipo.y45_vlrfixo is false or r_fiscaltipo.y45_valor is null then

    		fValorDebito 	= r_fiscaltipo.y59_valor;
        perform fc_debug('Formula utilizada: '||r_fiscaltipo.y59_valor);
      else

        fValorDebito  = (r_fiscaltipo.y45_valor * fValorInflator);
        perform fc_debug('Formula utilizada: ('||r_fiscaltipo.y45_valor||' * '||fValorInflator||') = '||fValorDebito);
      end if;

      /**
       * Gera arrecad e (arrematric ou arreinscr)
       */
      perform fc_autodeinfracao_geraDebito( iNumCgm,  dDataHoje, iReceita, iHistorico, fValorDebito,
                                            dDataVencimento, iNumpre,   iTipoDebito,   iMatricula,
                                            iInscricao );

    else

      perform fc_debug('Procedencia de percentual -> CodTipo: ' || r_fiscaltipo.y29_codtipo);

      /**
       * Procedencia de percentual
       */
      if r_fiscaltipo.y45_vlrfixo is false or r_fiscaltipo.y45_valor is null then

        fValorDebito  = ( r_fiscaltipo.y59_valor / 100 ) * fValorTotalLevantamento;
        perform fc_debug('Formula utilizada: ( '||r_fiscaltipo.y59_valor||' / 100 ) * '||fValorTotalLevantamento||' = '||fValorDebito);
      else

        fValorDebito  = ( r_fiscaltipo.y45_valor / 100 ) * fValorTotalLevantamento;
        perform fc_debug('Formula utilizada: ( '||r_fiscaltipo.y45_valor||' / 100 ) * '||fValorTotalLevantamento||' = '||fValorDebito);
      end if;

      /**
       * Gera arrecad e (arrematric ou arreinscr)
       */
      perform fc_autodeinfracao_geraDebito( iNumCgm,  dDataHoje, iReceita, iHistorico, fValorDebito,
                                            dDataVencimento, iNumpre,   iTipoDebito,   iMatricula,
                                            iInscricao );
    end if;

    fValorTotalDebito = fValorTotalDebito + fValorDebito;

  end loop;

  perform fc_debug('Total do Auto: ' || fValorTotalDebito);

  if lRaise is true then
    perform fc_debug('Fim do Calculo do Auto de Infracao.',lRaise,false,true);
  end if;

  return  ' C�lculo do Auto de Infra��o Executado. Numpre: ' || iNumpre;

END;
$$ language 'plpgsql';create or replace function fc_baixabanco( cod_ret integer, datausu date) returns varchar as
$$
declare

  retorno                   boolean default false;

  r_codret                  record;
  r_idret                   record;
  r_divold                  record;
  r_receitas                record;
  r_idunica                 record;
  q_disrec                  record;
  r_testa                   record;

  x_totreg                  float8;
  valortotal                float8;
  valorjuros                float8;
  valormulta                float8;
  fracao                    float8;
  nVlrRec                   float8;
  nVlrTfr                   float8;
  nVlrRecm                  float8;
  nVlrRecj                  float8;

  _testeidret               integer;
  vcodcla                   integer;
  gravaidret                integer;
  v_nextidret               integer;
  conta                     integer;

  v_contador                integer;
  v_somador                 numeric(15,2) default 0;
  v_valor                   numeric(15,2) default 0;

  v_valor_sem_round         float8;
  v_diferenca_round         float8;

  dDataCalculoRecibo        date;
  dDataReciboUnica          date;

  v_contagem                integer;
  primeirarec               integer default 0;
  primeirarecj              integer default 0;
  primeirarecm              integer default 0;
  primeiranumpre            integer;
  primeiranumpar            integer;
  iMaiorReceitaDisrec       integer;
  iMaiorIdretDisrec         integer;
  nBloqueado                integer;

  valorlanc                 float8;
  valorlancj                float8;
  valorlancm                float8;

  oidrec                    int8;

  autentsn                  boolean;

  valorrecibo               float8;
  v_totalrecibo             float8;

  v_total1                  float8 default 0;
  v_total2                  float8 default 0;

  v_totvlrpagooriginal      float8;

  v_estaemrecibopaga        boolean;
  v_estaemrecibo            boolean;
  v_estaemarrecadnormal     boolean;
  v_estaemarrecadunica      boolean;
  lVerificaReceita          boolean;
  lClassi                   boolean;
  lVariavel                 boolean;
  lReciboPossuiPgtoParcial  boolean default false;

  nSimDivold                integer;
  nNaoDivold                integer;
  iQtdeParcelasAberto       integer;
  iQtdeParcelasRecibo       integer;
  iQtdeParcelasPago         integer;

  nValorSimDivold           numeric(15,2) default 0;
  nValorNaoDivold           numeric(15,2) default 0;
  nValorTotDivold           numeric(15,2) default 0;

  nValorPagoDivold          numeric(15,2) default 0;
  nTotValorPagoDivold       numeric(15,2) default 0;

  nTotalRecibo              numeric(15,2) default 0;
  nTotalNovosRecibos        numeric(15,2) default 0;

  nTotalDisbancoOriginal    numeric(15,2) default 0;
  nTotalDisbancoDepois      numeric(15,2) default 0;


  iNumnovDivold             integer;
  rContador                 record;
  iIdret                    integer;
  iCodcli                   integer;
  v_diferenca               float8 default 0;

  cCliente                  varchar(100);
  iIdRetProcessar           integer;

  rValoresInconsistentes    record;

  lRaise                    boolean default false;

  iInstitSessao             integer;

  rReciboPaga               record;


  -- Abatimentos
  lAtivaPgtoParcial         boolean default false;
  lInsereJurMulCorr         boolean default true;

  iAnoSessao                integer;
  iAbatimento               integer;
  iAbatimentoArreckey       integer;
  iArreckey                 integer;
  iArrecadCompos            integer;
  iNumpreIssVar             integer;
  iNumpreRecibo             integer;
  iNumpreReciboAvulso       integer;
  iTipoDebitoPgtoParcial    integer;
  iTipoAbatimento           integer;
  iTipoReciboAvulso         integer;
  iReceitaCredito           integer;
  iRows                     integer;
  iSeqIdRet                 integer;
  iNumpreAnterior           integer default 0;

  nVlrCalculado             numeric(15,2) default 0;
  nDescontoUnica            numeric(15,2) default 0;
  nVlrPgto                  numeric(15,2) default 0;
  nVlrJuros                 numeric(15,2) default 0;
  nVlrMulta                 numeric(15,2) default 0;
  nVlrCorrecao              numeric(15,2) default 0;
  nVlrHistCompos            numeric(15,2) default 0;
  nVlrJurosCompos           numeric(15,2) default 0;
  nVlrMultaCompos           numeric(15,2) default 0;
  nVlrCorreCompos           numeric(15,2) default 0;
  nVlrPgtoParcela           numeric(15,2) default 0;
  nVlrDiferencaPgto         numeric(15,2) default 0;
  nVlrTotalRecibopaga       numeric(15,2) default 0;
  nVlrTotalHistorico        numeric(15,2) default 0;
  nVlrTotalJuroMultaCorr    numeric(15,2) default 0;
  nVlrReceita               numeric(15,2) default 0;
  nVlrAbatido               numeric(15,2) default 0;
  nVlrDiferencaDisrec       numeric(15,2) default 0;
  nVlrInformado             numeric(15,2) default 0;
  nVlrTotalInformado        numeric(15,2) default 0;

  nVlrToleranciaPgtoParcial numeric(15,2) default 0;
  nVlrToleranciaCredito     numeric(15,2) default 0;

  nPercPgto                 numeric;
  nPercReceita              numeric;
  nPercDesconto             numeric;

  sSql                      text;

  rValidaArrebanco          record;
  rRecordDisbanco           record;
  rRecordBanco              record;
  rRecord                   record;
  rRecibo                   record;
  rAcertoDiferenca          record;
  rteste record;

  sDebug                    text;
  /**
   * variavel de controle do numpre , se tiver ativado o pgto parcial, e essa variavel for dif. de 0
   * os numpres a partir dele serão tratados como pgto parcial, abaixo, sem pgto parcial
   */
  iNumprePagamentoParcial   integer default 0;

begin

  -- Busca Dados Sess�o
  iInstitSessao := cast(fc_getsession('DB_instit') as integer);
  iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
  lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

  if lRaise is true then
    if trim(fc_getsession('db_debug')) <> '' then
      perform fc_debug('  <BaixaBanco>  - INICIANDO PROCESSAMENTO... ',lRaise,false,false);
    else
      perform fc_debug('  <BaixaBanco>  - INICIANDO PROCESSAMENTO... ',lRaise,true,false);
    end if;
  end if;



  /**
   * Verifica se esta configurado Pagamento Parcial
   * Buscamos o valor base setado na numpref campo k03_numprepgtoparcial
   * Consulta o tipo de debito configurado para Recibo Avulso
   * Consulta o parametro de tolerancia para pagamento parcial
   *
   */
  select k03_pgtoparcial,
         k03_numprepgtoparcial,
         k03_reciboprot,
         coalesce(numpref.k03_toleranciapgtoparc,0)::numeric(15, 2),
         coalesce(numpref.k03_toleranciacredito,0)::numeric(15, 2)
    into lAtivaPgtoParcial,
         iNumprePagamentoParcial,
         iTipoReciboAvulso,
         nVlrToleranciaPgtoParcial,
         nVlrToleranciaCredito
    from numpref
   where numpref.k03_anousu = iAnoSessao
     and numpref.k03_instit = iInstitSessao;

   if lRaise is true then
     perform fc_debug('  <BaixaBanco>  - PARAMETROS DO NUMPREF '                                  ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - lAtivaPgtoParcial:  '||lAtivaPgtoParcial                 ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - iNumprePagamentoParcial:  '||iNumprePagamentoParcial     ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - iTipoReciboAvulso:  '||iTipoReciboAvulso                 ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - nVlrToleranciaPgtoParcial:  '||nVlrToleranciaPgtoParcial ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - nVlrToleranciaCredito:  '||nVlrToleranciaCredito         ,lRaise,false,false);
   end if;

   if iTipoReciboAvulso is null then
     return '2 - Operacao Cancelada. Tipo de Debito nao configurado para Recibo Avulso. ';
   end if;

    select k00_conta,
           autent,
           count(*)
      into conta,
           autentsn,
           vcodcla
      from disbanco
           inner join disarq on disarq.codret = disbanco.codret
     where disbanco.codret = cod_ret
       and disbanco.classi is false
       and disbanco.instit = iInstitSessao
  group by disarq.k00_conta,
           disarq.autent;

  if vcodcla is null or vcodcla = 0 then
    return '3 - ARQUIVO DE RETORNO DO BANCO JA CLASSIFICADO';
  end if;
  if conta is null or conta = 0 then
    return '4 - SEM CONTA CADASTRADA PARA ARRECADACAO. OPERACAO CANCELADA.';
  end if;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  - autentsn:  '||autentsn,lRaise,false,false);
  end if;

  select upper(munic)
  into cCliente
  from db_config
  where codigo = iInstitSessao;

  if autentsn is false then

    select nextval('discla_codcla_seq')
      into vcodcla;

    insert into discla (
      codcla,
      codret,
      dtcla,
      instit
    ) values (
      vcodcla,
      cod_ret,
      datausu,
      iInstitSessao
    );

   /**
    * Insere dados da baixa de Banco nesta tabela pois na pl que a chama o arquivo e divido em mais de uma classificacao
    */
   if lRaise is true then
     perform fc_debug('  <BaixaBanco> - 276 - '||cod_ret||','||vcodcla,lRaise,false,false);
   end if;

   insert into   tmp_classificaoesexecutadas("codigo_retorno", "codigo_classificacao")
          values (cod_ret, vcodcla);

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - vcodcla: '||vcodcla,lRaise,false,false);
    end if;

  else
    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - nao '||autentsn,lRaise,false,false);
    end if;
  end if;

/**
 * Aqui inicia pre-processamento do Pagamento Parcial
 */
  if lAtivaPgtoParcial is true then

    if lRaise then
      perform fc_debug('  <PgtoParcial>  - Parametro pagamento parcial ativado !',lRaise,false,false);
    end if;

    /*******************************************************************************************************************
     *  VERIFICA RECIBO AVULSO
     ******************************************************************************************************************/
    -- Caso exista algum recibo avulso que jah esteja pago, o sistema gera um novo recibo avulso
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 1 - VERIFICA RECIBO AVULSO',lRaise,false,false);
    end if;

    for rRecordDisbanco in

      select disbanco.*
        from disbanco
             inner join recibo   on recibo.k00_numpre   = disbanco.k00_numpre
             inner join arrepaga on arrepaga.k00_numpre = disbanco.k00_numpre
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and case when iNumprePagamentoParcial = 0
                  then true
                  else disbanco.k00_numpre > iNumprePagamentoParcial
              end
         and disbanco.instit = iInstitSessao

    loop

      select nextval('numpref_k03_numpre_seq')
        into iNumpreRecibo;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - lan�a recibo avulto j� pago ',lRaise,false,false);
      end if;

      insert into recibo ( k00_numcgm,
                 k00_dtoper,
               k00_receit,
               k00_hist,
               k00_valor,
               k00_dtvenc,
               k00_numpre,
               k00_numpar,
               k00_numtot,
               k00_numdig,
               k00_tipo,
               k00_tipojm,
               k00_codsubrec,
               k00_numnov
                         ) select k00_numcgm,
                                k00_dtoper,
                                k00_receit,
                              k00_hist,
                              k00_valor,
                              k00_dtvenc,
                              iNumpreRecibo,
                              k00_numpar,
                              k00_numtot,
                              k00_numdig,
                              k00_tipo,
                              k00_tipojm,
                              k00_codsubrec,
                              k00_numnov
                             from recibo
                            where recibo.k00_numpre = rRecordDisbanco.k00_numpre;


      insert into reciborecurso ( k00_sequen,
                    k00_numpre,
                    k00_recurso
                                ) select nextval('reciborecurso_k00_sequen_seq'),
                               iNumpreRecibo,
                               k00_recurso
                                    from reciborecurso
                                   where reciborecurso.k00_numpre = rRecordDisbanco.k00_numpre;


      insert into arrehist ( k00_numpre,
                             k00_numpar,
                             k00_hist,
                             k00_dtoper,
                             k00_hora,
                             k00_id_usuario,
                             k00_histtxt,
                             k00_limithist,
                             k00_idhist
                           ) values (
                             iNumpreRecibo,
                             1,
                             502,
                             datausu,
                             '00:00',
                             1,
                             'Recibo avulso referente a baixa do recibo avulso ja pago - Numpre : '||rRecordDisbanco.k00_numpre,
                             null,
                             nextval('arrehist_k00_idhist_seq')
                          );

      insert into arreproc ( k80_numpre,
                             k80_codproc )  select iNumpreRecibo,
                                                   arreproc.k80_codproc
                                              from arreproc
                                             where arreproc.k80_numpre = rRecordDisbanco.k00_numpre;

      insert into arrenumcgm ( k00_numpre,
                               k00_numcgm ) select iNumpreRecibo,
                                                   arrenumcgm.k00_numcgm
                                              from arrenumcgm
                                             where arrenumcgm.k00_numpre = rRecordDisbanco.k00_numpre;

      insert into arrematric ( k00_numpre,
                               k00_matric ) select iNumpreRecibo,
                                                   arrematric.k00_matric
                                              from arrematric
                                             where arrematric.k00_numpre = rRecordDisbanco.k00_numpre;

      insert into arreinscr ( k00_numpre,
                              k00_inscr )   select iNumpreRecibo,
                                                   arreinscr.k00_inscr
                                              from arreinscr
                                             where arreinscr.k00_numpre = rRecordDisbanco.k00_numpre;

      if lRaise then
        perform fc_debug('  <PgtoParcial>  - 1 - Alterando numpre disbanco ! novo numpre : '||iNumpreRecibo,lRaise,false,false);
      end if;

      update disbanco
         set k00_numpre = iNumpreRecibo
       where idret      = rRecordDisbanco.idret;

    end loop; --Fim do loop de valida��o da regra 1 para recibo avulso


    /*********************************************************************************
     *  GERA RECIBO PARA CARNE
     ********************************************************************************/
    -- Verifica se o pagamento eh referente a um Carne
    -- Caso seja entao eh gerado um recibopaga para os debitos
    -- do arrecad e acertado o numpre na tabela disbanco
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 2 - GERA RECIBO PARA CARNE!',lRaise,false,false);
    end if;

    for rRecordDisbanco in select disbanco.idret,
                                  disbanco.dtpago,
                                  disbanco.k00_numpre,
                                  disbanco.k00_numpar,
                                  ( select k00_dtvenc
                                      from (select k00_dtvenc
                                              from arrecad
                                             where arrecad.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arrecad.k00_numpar = disbanco.k00_numpar
                                                  end
                                           union
                                            select k00_dtvenc
                                              from arrecant
                                             where arrecant.k00_numpre = disbanco.k00_numpre
                                               and case
                                                     when disbanco.k00_numpar = 0 then true
                                                     else arrecant.k00_numpar = disbanco.k00_numpar
                                                   end
                                           union
                                            select k00_dtvenc
                                              from arreold
                                             where arreold.k00_numpre = disbanco.k00_numpre
                                               and case
                                                     when disbanco.k00_numpar = 0 then true
                                                     else arreold.k00_numpar = disbanco.k00_numpar
                                                   end
                                            ) as x limit 1
                                  ) as data_vencimento_debito
                            from disbanco
                            where disbanco.codret = cod_ret
                              and disbanco.classi is false
                              and disbanco.instit = iInstitSessao
                              and case when iNumprePagamentoParcial = 0
                                       then true
                                       else disbanco.k00_numpre > iNumprePagamentoParcial
                                   end
                              and exists ( select 1
                                             from arrecad
                                            where arrecad.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arrecad.k00_numpar  = disbanco.k00_numpar
                                                  end
                                            union all
                                           select 1
                                             from arrecant
                                            where arrecant.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arrecant.k00_numpar = disbanco.k00_numpar
                                                  end
                                           union all
                                           select 1
                                             from arreold
                                            where arreold.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arreold.k00_numpar = disbanco.k00_numpar
                                                  end
                                            limit 1 )
                              and not exists ( select 1
                                                 from issvar
                                                where issvar.q05_numpre = disbanco.k00_numpre
                                                  and issvar.q05_numpar = disbanco.k00_numpar
                                                limit 1 )
                              and not exists ( select 1
                                                 from tmpnaoprocessar
                                                where tmpnaoprocessar.idret = disbanco.idret )
                         order by disbanco.idret

    loop

      select nextval('numpref_k03_numpre_seq')
        into iNumpreRecibo;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Processando geracao de recibo para - Numpre: '||rRecordDisbanco.k00_numpre||'  Numpar: '||rRecordDisbanco.k00_numpar,lRaise,false,false);
      end if;

      select distinct
             arrecad.k00_tipo
        into rRecord
        from arrecad
       where arrecad.k00_numpre = rRecordDisbanco.k00_numpre
         and case
               when rRecordDisbanco.k00_numpar = 0
                 then true
               else arrecad.k00_numpar = rRecordDisbanco.k00_numpar
             end
       limit 1;

      if found then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Encontrou no arrecad - Gerando Recibo para o debito - Numpre: '||rRecordDisbanco.k00_numpre||'  Numpar: '||rRecordDisbanco.k00_numpar,lRaise,false,false);
        end if;

        select k00_codbco,
               k00_codage,
               fc_numbco(k00_codbco,k00_codage) as fc_numbco
          into rRecordBanco
          from arretipo
         where k00_tipo = rRecord.k00_tipo;

        insert into db_reciboweb ( k99_numpre,
                                   k99_numpar,
                                   k99_numpre_n,
                                   k99_codbco,
                                   k99_codage,
                                   k99_numbco,
                                   k99_desconto,
                                   k99_tipo,
                                   k99_origem
                                 ) values (
                                   rRecordDisbanco.k00_numpre,
                                   rRecordDisbanco.k00_numpar,
                                   iNumpreRecibo,
                                   coalesce(rRecordBanco.k00_codbco,0),
                                   coalesce(rRecordBanco.k00_codage,'0'),
                                   rRecordBanco.fc_numbco,
                                   0,
                                   2,
                                   1 );

        dDataCalculoRecibo := rRecordDisbanco.data_vencimento_debito;

        select ru.k00_dtvenc
          into dDataReciboUnica
          from recibounica ru
         where ru.k00_numpre = rRecordDisbanco.k00_numpre
           and rRecordDisbanco.k00_numpar = 0
           and ru.k00_dtvenc >= rRecordDisbanco.dtpago
         order by k00_dtvenc
         limit 1;

        if found then
          dDataCalculoRecibo := dDataReciboUnica;
        end if;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - ');
          perform fc_debug('  <PgtoParcial>  ---------------- Validando datas de vencimento ----------------');
          perform fc_debug('  <PgtoParcial>  - Op��es:');
          perform fc_debug('  <PgtoParcial>  - 1 - Pr�ximo dia util do vencimento do arrecad : ' || fc_proximo_dia_util(dDataCalculoRecibo));
          perform fc_debug('  <PgtoParcial>  - 2 - Data do Pagamento Banc�rio                : ' || rRecordDisbanco.dtpago);
          perform fc_debug('  <PgtoParcial>  ---------------------------------------------------------------');
          perform fc_debug('  <PgtoParcial>  - ');
          perform fc_debug('  <PgtoParcial>  - Op��o Default : "1" ');
        end if;

        if rRecordDisbanco.dtpago > fc_proximo_dia_util(dDataCalculoRecibo)  then -- Paguei Depois do Vencimento

          dDataCalculoRecibo := rRecordDisbanco.dtpago;

          if lRaise is true then
            perform fc_debug('  <PgtoParcial>  - Alterando para Op��o de Vencimento "2" ');
          end if;
        end if;

        if lRaise is true then

          perform fc_debug('  <PgtoParcial>');
          perform fc_debug('  <PgtoParcial>  - Rodando FC_RECIBO'    );
          perform fc_debug('  <PgtoParcial>  --- iNumpreRecibo      : ' || iNumpreRecibo      );
          perform fc_debug('  <PgtoParcial>  --- dDataCalculoRecibo : ' || dDataCalculoRecibo );
          perform fc_debug('  <PgtoParcial>  --- iAnoSessao         : ' || iAnoSessao         );
          perform fc_debug('  <PgtoParcial>');
        end if;

        select * from fc_recibo(iNumpreRecibo,dDataCalculoRecibo,dDataCalculoRecibo,iAnoSessao)
          into rRecibo;

        if rRecibo.rlerro is true then
          return '5 - '||rRecibo.rvmensagem||' Erro ao processar idret '||rRecordDisbanco.idret||'.';
        end if;

      else

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Nao encontrou no arrecad - Gerando Recibo para o debito - Numpre: '||rRecordDisbanco.k00_numpre||'  Numpar: '||rRecordDisbanco.k00_numpar,lRaise,false,false);
        end if;

        select distinct
               arrecant.k00_tipo
          into rRecord
          from arrecant
         where arrecant.k00_numpre = rRecordDisbanco.k00_numpre
         union
        select distinct
               arreold.k00_tipo
          from arreold
         where arreold.k00_numpre = rRecordDisbanco.k00_numpre
         limit 1;

        select k00_codbco,
               k00_codage,
               fc_numbco(k00_codbco,k00_codage) as fc_numbco
          into rRecordBanco
          from arretipo
         where k00_tipo = rRecord.k00_tipo;

        insert into db_reciboweb ( k99_numpre,
                                   k99_numpar,
                                   k99_numpre_n,
                                   k99_codbco,
                                   k99_codage,
                                   k99_numbco,
                                   k99_desconto,
                                   k99_tipo,
                                   k99_origem
                                 ) values (
                                   rRecordDisbanco.k00_numpre,
                                   rRecordDisbanco.k00_numpar,
                                   iNumpreRecibo,
                                   coalesce(rRecordBanco.k00_codbco,0),
                                   coalesce(rRecordBanco.k00_codage,'0'),
                                   rRecordBanco.fc_numbco,
                                   0,
                                   2,
                                   1 );

        if lRaise is true then
          perform fc_debug('<PgtoParcial>  - Lan�ou recibo caso sej� carne ',lRaise,false,false);
        end if;

        insert into recibopaga ( k00_numcgm,
         k00_dtoper,
         k00_receit,
         k00_hist,
         k00_valor,
         k00_dtvenc,
         k00_numpre,
         k00_numpar,
         k00_numtot,
         k00_numdig,
         k00_conta,
         k00_dtpaga,
         k00_numnov )
        select k00_numcgm,
               k00_dtoper,
               k00_receit,
               k00_hist,
               k00_valor,
               k00_dtvenc,
               k00_numpre,
               k00_numpar,
               k00_numtot,
               k00_numdig,
               0,
               datausu,
               iNumpreRecibo
          from arrecant
         where arrecant.k00_numpre = rRecordDisbanco.k00_numpre
           and case
                 when rRecordDisbanco.k00_numpar = 0 then true
                   else rRecordDisbanco.k00_numpar = arrecant.k00_numpar
               end
         union
        select k00_numcgm,
               k00_dtoper,
               k00_receit,
               k00_hist,
               k00_valor,
               k00_dtvenc,
               k00_numpre,
               k00_numpar,
               k00_numtot,
               k00_numdig,
               0,
               datausu,
               iNumpreRecibo
          from arreold
         where arreold.k00_numpre = rRecordDisbanco.k00_numpre
           and case
                 when rRecordDisbanco.k00_numpar = 0 then true
                   else rRecordDisbanco.k00_numpar = arreold.k00_numpar
               end ;

      end if;

      if rRecordDisbanco.k00_numpar = 0 then
        insert into tmplista_unica values (rRecordDisbanco.idret);
      end if;

      -- Acerta o conteudo da disbanco, alterando o numpre do carne pelo da recibopaga
      if lRaise then
        perform fc_debug('  <PgtoParcial>  - Acertando numpre do recibo gerado para o carne (arreold ou arrecant) numnov : '||iNumpreRecibo,lRaise,false,false);
      end if;

      if lRaise then
        perform fc_debug('  <PgtoParcial>  - 2 - Alterando numpre disbanco ! novo numpre : '||iNumpreRecibo,lRaise,false,false);
      end if;

      update disbanco
         set k00_numpre = iNumpreRecibo,
             k00_numpar = 0
       where idret = rRecordDisbanco.idret;

    end loop;

    if lRaise then
      perform fc_debug('  <PgtoParcial>  - Final processamento para geracao recibo para carne, '||clock_timestamp(),lRaise,false,false);
    end if;

   /**
     * Inserimos na tmpnaoprocessar os numpres dos idrets onde nao
     * sao encontrada as origens excluindo juro, multa e desconto
     */
    for r_idret in select idret, k00_numpre
                     from disbanco
                    where disbanco.codret = cod_ret
    loop

    perform 1
       from recibopaga
      where recibopaga.k00_numnov = r_idret.k00_numpre
        and recibopaga.k00_hist  not in ( 918, 400, 401 )
        and not
            exists ( select 1
                       from arrecad
                      where arrecad.k00_numpre = recibopaga.k00_numpre
                        and arrecad.k00_numpar = recibopaga.k00_numpar
                        and arrecad.k00_receit = recibopaga.k00_receit
                      union all
                     select 1
                       from arrecant
                      where arrecant.k00_numpre = recibopaga.k00_numpre
                        and arrecant.k00_numpar = recibopaga.k00_numpar
                        and arrecant.k00_receit = recibopaga.k00_receit
                      union all
                     select 1
                       from arreold
                      where arreold.k00_numpre = recibopaga.k00_numpre
                        and arreold.k00_numpar = recibopaga.k00_numpar
                        and arreold.k00_receit = recibopaga.k00_receit
                      union all
                     select 1
                       from arreprescr
                      where arreprescr.k30_numpre = recibopaga.k00_numpre
                        and arreprescr.k30_numpar = recibopaga.k00_numpar
                        and arreprescr.k30_receit = recibopaga.k00_receit
                      limit 1 );

      if found then

        if lRaise then
          perform fc_debug(' <baixabanco> N�O ENCONTRADA ORIGENS, INSERINDO NA tmpnaoprocessar '||r_idret.idret,lRaise,false,false);
        end if;

        insert into tmpnaoprocessar values (r_idret.idret);
      end if;

    end loop;

    /*******************************************************************************************************************
     *  N�O PROCESSA PAGAMENTOS DUPLICADOS EM RECIBOS DIFERENTES
     ******************************************************************************************************************/
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 4 - NAO PROCESSA PAGAMENTOS DUPLICADOS EM RECIBOS DIFERENTES!',lRaise,false,false);
    end if;
    for r_idret in

        select x.k00_numpre,
               x.k00_numpar,
               count(x.idret) as ocorrencias
          from ( select distinct
                        recibopaga.k00_numpre,
                        recibopaga.k00_numpar,
                        disbanco.idret
                   from disbanco
                        inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                  where disbanco.codret = cod_ret
                    and disbanco.classi is false
                    and case when iNumprePagamentoParcial = 0
                             then true
                             else disbanco.k00_numpre > iNumprePagamentoParcial
                         end

                    and disbanco.instit = iInstitSessao ) as x
                left  join numprebloqpag  on numprebloqpag.ar22_numpre = x.k00_numpre
                                         and numprebloqpag.ar22_numpar = x.k00_numpar
         where numprebloqpag.ar22_numpre is null
             and not exists ( select 1
                                from tmpnaoprocessar
                               where tmpnaoprocessar.idret = x.idret )
         group by x.k00_numpre,
                  x.k00_numpar
           having count(x.idret) > 1

    loop

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - ######## 1111 incluido no naoprocesar',lRaise,false,false);
      end if;

      for iRows in 1..( r_idret.ocorrencias - 1 ) loop

          if lRaise then
            perform fc_debug('  <PgtoParcial>  - Inserindo em nao processar - Pagamento duplicado em recibos diferentes',lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - ########  incluido no naprocesar',lRaise,false,false);
          end if;

          -- @todo - verificar esta logica, a principio parece estar inserindo aqui o mesmo recibo
          -- em arquivos (codret) diferentes

          insert into tmpnaoprocessar select coalesce(max(disbanco.idret),0)
                                     from disbanco
                                    where disbanco.codret = cod_ret
                                      and case when iNumprePagamentoParcial = 0
                                               then true
                                               else disbanco.k00_numpre > iNumprePagamentoParcial
                                           end
                                      and disbanco.classi is false
                                      and disbanco.instit = iInstitSessao
                                      and disbanco.k00_numpre in ( select recibopaga.k00_numnov
                                                                     from recibopaga
                                                                    where recibopaga.k00_numpre = r_idret.k00_numpre
                                                                      and recibopaga.k00_numpar = r_idret.k00_numpar )
                                      and not exists ( select 1
                                                         from tmpnaoprocessar
                                                        where tmpnaoprocessar.idret = disbanco.idret );

      end loop;

    end loop;


    /*********************************************************************************************************************
     *  EFETUA AJUSTE NOS RECIBOS QUE TENHAM ALGUMA PARCELA DE SUA ORIGEM, PAGA/CANCELADA/IMPORTADA PARA DIVIDA/PARCELADA
     *********************************************************************************************************************/
    --
    -- Processa somente os recibos que tenham todos debitos em aberto ou todos pagos
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 5 - EFETUA AJUSTE NOS RECIBOS QUE TENHAM ALGUMA PARCELA DE SUA ORIGEM',lRaise,false,false);
      perform fc_debug('  <PgtoParcial>           PAGA/CANCELADA/IMPORTADA PARA DIVIDA/PARCELADA!',lRaise,false,false);
    end if;

    for r_idret in
        select disbanco.idret,
               disbanco.k00_numpre as numpre,
               r.k00_numpre,
               r.k00_numpar,
               (select count(*)
                  from (select distinct
                               recibopaga.k00_numpre,
                               recibopaga.k00_numpar
                          from recibopaga
                               inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                                 and arrecad.k00_numpar = recibopaga.k00_numpar
                         where recibopaga.k00_numnov = disbanco.k00_numpre ) as x
               ) as qtd_aberto,
               (select count(*)
                  from (select distinct
                               k00_numpre,
                               k00_numpar
                          from recibopaga
                          where recibopaga.k00_numnov = disbanco.k00_numpre ) as x
               ) as qtd_recibo,
               exists ( select 1
                          from arrecad a
                         where a.k00_numpre = r.k00_numpre
                           and a.k00_numpar = r.k00_numpar ) as arrecad,
               exists ( select 1
                          from arrecant a
                         where a.k00_numpre = r.k00_numpre
                           and a.k00_numpar = r.k00_numpar ) as arrecant,
               exists ( select 1
                          from arreold a
                         where a.k00_numpre = r.k00_numpre
                           and a.k00_numpar = r.k00_numpar ) as arreold
          from disbanco
               inner join recibopaga r   on r.k00_numnov              = disbanco.k00_numpre
               left  join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                        and numprebloqpag.ar22_numpar = disbanco.k00_numpar
         where disbanco.codret = cod_ret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and numprebloqpag.ar22_numpre is null
           and case when iNumprePagamentoParcial = 0
                    then true
                    else disbanco.k00_numpre > iNumprePagamentoParcial
                end
           and not exists ( select 1
                              from tmpnaoprocessar
                             where tmpnaoprocessar.idret = disbanco.idret )
           order by disbanco.codret,
                  disbanco.idret,
                  disbanco.k00_numpre,
                  r.k00_numpre,
                  r.k00_numpar
    loop

      if lRaise is true then
        perform fc_debug('<PgtoParcial> Processando idret '||r_idret.idret||' Numpre: '||r_idret.numpre||'...',lRaise,false,false);
      end if;

      -- @todo - verificar esta logica com muita calma, acredito nao ser aqui o melhor lugar...
      if ( r_idret.qtd_aberto = r_idret.qtd_recibo ) or r_idret.qtd_aberto = 0 then
        if lRaise is true then
          perform fc_debug('<PgtoParcial> Continuando 1 ( qtd_aberto = qtd_recibo OU qtd_aberto = 0 )...',lRaise,false,false);
        end if;
        continue;
      end if;

      if r_idret.arrecad then
        perform 1 from arrecad where k00_numpre = r_idret.k00_numpre and k00_tipo = 3;
        if found then
          if lRaise is true then
        perform fc_debug('<PgtoParcial> Continuando 2 ( nao encontrou numpre na arrecad )...',lRaise,false,false);
      end if;
          continue;
        end if;
      elsif r_idret.arrecant then
        perform 1 from arrecant where k00_numpre = r_idret.k00_numpre and k00_tipo = 3;
        if found then
          if lRaise is true then
        perform fc_debug('<PgtoParcial> Continuando 3 ( nao encontrou numpre na arrecant )...',lRaise,false,false);
      end if;
          continue;
        end if;
      elsif r_idret.arreold then
        perform 1 from arreold where k00_numpre = r_idret.k00_numpre and k00_tipo = 3;
        if found then
          if lRaise is true then
             perform fc_debug('<PgtoParcial> Continuando 4 ( nao encontrou numpre na arreold )...',lRaise,false,false);
          end if;
          continue;
        end if;
      end if;

      --
      -- Se nao encontrar o numpre e numpar em nenhuma das tabelas : arrecad,arrecant,arreold
      --   insere em tmpnaoprocessar para nao processar do loop principal do processamento
      --
      if r_idret.arrecad is false and r_idret.arrecant is false and r_idret.arreold is false then
        perform 1 from tmpnaoprocessar where idret = r_idret.idret;
        if not found then

          if lRaise is true then
             perform fc_debug('<PgtoParcial> Inserindo idret '||r_idret.idret||' em tmpnaoprocessar...',lRaise,false,false);
          end if;
          insert into tmpnaoprocessar values (r_idret.idret);
        end if;
      elsif r_idret.arrecad is false then
        --
        --  Caso nao encontrar no arrecad deleta o numpre e numpar
        --    da recibopaga para ajustar o recibo, pressupondo que tenha sido pago ou cancelado
        --    uma parcela do recibo. Este ajuste no recibo é necessario para que o sistema encontre
        --    a diferenca entre o valor pago e o valor do recibo, gerando assim um credito com o valor
        --    da diferenca
        --

        if lRaise then
          perform fc_debug('  <PgtoParcial>  - Quantidade em aberto : '||r_idret.qtd_aberto||' Quantidade no recibo : '||r_idret.qtd_recibo                             ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Deletando da recibopaga -- numnov : '||r_idret.numpre||' numpre : '||r_idret.k00_numpre||' numpar : '||r_idret.k00_numpar,lRaise,false,false);
        end if;

        --
        -- Verificamos se o numnov que esta prestes a ser deletado poussui vinculo com alguma partilha
        -- Caso encontrado vinculo, o recibo nao e exclui�do e sera retornado erro no processamento
        --
        perform v77_processoforopartilha
           from processoforopartilhacusta
          where v77_numnov in (select k00_numnov
                                from recibopaga
                               where k00_numnov = r_idret.numpre
                                 and k00_numpre = r_idret.k00_numpre
                                 and k00_numpar = r_idret.k00_numpar);
        if found then
          raise exception   'Erro ao realizar exclusao de recibo da CGF (recibopaga) Numnov: % Numpre: % Numpar: % possuem vinculo com geracao de partilha de custas para processo do foro', r_idret.numpre, r_idret.k00_numpre, r_idret.k00_numpar;
        end if;

        delete from recibopaga
         where k00_numnov = r_idret.numpre
           and k00_numpre = r_idret.k00_numpre
           and k00_numpar = r_idret.k00_numpar;

      end if;

    end loop;

    /*******************************************************************************************************************
     *  GERA RECIBO PARA ISSQN VARIAVEL
     ******************************************************************************************************************/
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 6 - GERA RECIBO PARA ISSQN VARIAVEL',lRaise,false,false);
    end if;
    -- Verifica se existe algum  referente a ISSQN Variavel que ja esteja quitado e o valor seja 0 (zero)
    -- Nesse caso sera gerado ARRECAD / ISSVAR / RECIBO para o  encontrado e acertado o numpre na tabela disbanco

    --
    -- Alterado o sql para buscar dados da disbanco de issqn vari�vel que est�o na recibopaga, jah realizava antes da alteracao,
    -- e buscar dados da disbanco de issqn variavel que nao tiveram seu pagamento por recibo, l�gica nova.
    --
    for rRecordDisbanco in select distinct
                                  disbanco.*,
                                  issvar_carne.q05_numpre as issvar_carne_numpre,
                                  issvar_carne.q05_numpar as issvar_carne_numpar
                             from disbanco
                                  left join recibopaga                        on recibopaga.k00_numnov            = disbanco.k00_numpre
                                  left join arrecant                          on arrecant.k00_numpre              = recibopaga.k00_numpre
                                                                             and arrecant.k00_numpar              = recibopaga.k00_numpar
                                                                             and arrecant.k00_receit              = recibopaga.k00_receit
                                  left join issvar as issvar_recibo           on issvar_recibo.q05_numpre         = arrecant.k00_numpre
                                                                             and issvar_recibo.q05_numpar         = arrecant.k00_numpar
                                  left join issvar as issvar_carne            on issvar_carne.q05_numpre          = disbanco.k00_numpre
                                                                             and issvar_carne.q05_numpar          = disbanco.k00_numpar
                                  left join arrecant as arrecant_issvar_carne on arrecant_issvar_carne.k00_numpre = disbanco.k00_numpre
                                                                             and arrecant_issvar_carne.k00_numpar = disbanco.k00_numpar
                            where disbanco.classi is false
                              and disbanco.codret = cod_ret
                              and disbanco.instit = iInstitSessao
                              and ( issvar_recibo.q05_numpre is not null or ( issvar_carne.q05_numpre is not null and arrecant_issvar_carne.k00_numpre is not null) )
                              and case when iNumprePagamentoParcial = 0
                                       then true
                                       else disbanco.k00_numpre > iNumprePagamentoParcial
                                   end
                              and not exists ( select 1
                                                 from tmpnaoprocessar
                                                where tmpnaoprocessar.idret = disbanco.idret )
                         order by disbanco.idret
    loop

      if lRaise is true then
          perform fc_debug('  <PgtoParcial> ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> PROCESSANDO IDRET '||rRecordDisbanco.idret||'...',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>                                                 ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Gerando recibos                                 ',lRaise,false,false);
      end if;

      --
      -- Alterado o sql para atender aos casos em que foi pago um issqn variavel por carn� ao inv�s de recibo
      --
      select distinct
             case
               when recibopaga.k00_numnov is not null and round(sum(recibopaga.k00_valor),2) > 0.00 then
                 round(sum(recibopaga.k00_valor),2)
               else
                 vlrpago
             end
        into nVlrTotalRecibopaga
        from disbanco
             left join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
       where disbanco.idret  = rRecordDisbanco.idret
         and disbanco.instit = iInstitSessao
       group by recibopaga.k00_numnov, disbanco.vlrpago ;

      if lRaise is true then

        perform fc_debug('  <PgtoParcial> Numpre Disbanco .........: '||rRecordDisbanco.k00_numpre                                                            ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial> Numpre IssVar ...........: '||rRecordDisbanco.issvar_carne_numpre||' Parcela: '||rRecordDisbanco.issvar_carne_numpar,lRaise,false,false);
        perform fc_debug('  <PgtoParcial> Valor Pago na Disbanco (recibopaga) ..: '||nVlrTotalRecibopaga                                                                   ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial> ',lRaise,false,false);
      end if;

      for rRecord in select distinct tipo,
                                        k00_numpre,
                                        k00_numpar,
                                        case
                                          when k00_valor = 0 then rRecordDisbanco.vlrpago
                                          else k00_valor
                                        end as k00_valor
                       from ( select distinct
                                     1 as tipo,
                                     recibopaga.k00_numpre,
                                     recibopaga.k00_numpar,
                                     round(sum(recibopaga.k00_valor),2) as k00_valor
                                from recibopaga
                                     inner join arrecant  c on c.k00_numpre = recibopaga.k00_numpre
                                                          and c.k00_numpar  = recibopaga.k00_numpar
                               where recibopaga.k00_numnov = rRecordDisbanco.k00_numpre
                               group by recibopaga.k00_numpre,
                                        recibopaga.k00_numpar
                               union
                              select 2 as tipo,
                                     rRecordDisbanco.issvar_carne_numpre as k00_numpre,
                                     rRecordDisbanco.issvar_carne_numpar as k00_numpar,
                                     rRecordDisbanco.vlrpago             as k00_valor
                               where rRecordDisbanco.issvar_carne_numpre is not null
                             ) as dados
                      order by k00_numpre, k00_numpar

      loop

        if lRaise is true then

          perform fc_debug('  <PgtoParcial> '                                                                                                          ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Calculando valor informado...'                                                                             ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor pago na Disbanco ...:'||rRecordDisbanco.vlrpago                                                      ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor do debito ..........:'||rRecord.k00_valor                                                            ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor do debito encontrado na tabela '||(case when rRecord.tipo = 1 then 'Recibopaga' else 'Disbanco' end ),lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor pago na disbanco ...:'||nVlrTotalRecibopaga                                                          ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Calculo ..................: ( Valor pago na Disbanco * ((( Valor do debito * 100 ) / Valor pago na disbanco ) / 100 ))',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor Informado ..........: ( '||coalesce(rRecordDisbanco.vlrpago,0)||' * ((( '||coalesce(rRecord.k00_valor,0)||' * 100 ) / '||coalesce(nVlrTotalRecibopaga,0)||' ) / 100 )) = '||( coalesce(rRecordDisbanco.vlrpago,0) * ((( coalesce(rRecord.k00_valor,0) * 100 ) / coalesce(nVlrTotalRecibopaga,0) ) / 100 )) ,lRaise,false,false);
        end if;

        nVlrInformado := ( rRecordDisbanco.vlrpago * ((( rRecord.k00_valor * 100 ) / nVlrTotalRecibopaga ) / 100 ));

        --if rRecord.k00_numpre != iNumpreAnterior then

          -- Gera Numpre do ISSQN Variavel
          select nextval('numpref_k03_numpre_seq')
            into iNumpreIssVar;

          -- Gera Numpre do Recibo
          select nextval('numpref_k03_numpre_seq')
            into iNumpreRecibo;

          iNumpreAnterior    := rRecord.k00_numpre;
          nVlrTotalInformado := 0;

          insert into arreinscr select distinct
                                       iNumpreIssVar,
                                       arreinscr.k00_inscr,
                                       arreinscr.k00_perc
                                  from arreinscr
                                 where arreinscr.k00_numpre = rRecord.k00_numpre;
        --end if;

        --
        -- Apenas excluimos o recibo quando o pagamento for por recibo (tipo = 1)
        --
        if rRecord.tipo = 1 then

          delete
            from recibopaga
           where k00_numnov = rRecordDisbanco.k00_numpre
             and k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar;
        end if;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial> Incluindo registros do Numpre '||rRecord.k00_numpre||' Parcela '||rRecord.k00_numpar||' na tabela arrecad como iss complementar com o novo numpre '||iNumpreIssVar,lRaise,false,false);
        end if;

        /*
         * Alterada a l�gica para inclus�o no arrecad.
         *
         * Ao inv�s de utilizar a data de opera��o e vencimento original do d�bito, esta sendo utilizada a data de processamento da baixa de banco
         * Isto devido a gera��o de corre��o, juro e multa indevidos para o d�bito pois esses valores ja est�o embutidos no valor total pago na disbanco.
         *
         */
        insert into arrecad ( k00_numpre,
                              k00_numpar,
                              k00_numcgm,
                              k00_dtoper,
                              k00_receit,
                              k00_hist,
                              k00_valor,
                              k00_dtvenc,
                              k00_numtot,
                              k00_numdig,
                              k00_tipo,
                              k00_tipojm
                            ) select iNumpreIssVar,
                                 arrecant.k00_numpar,
                                 arrecant.k00_numcgm,
                                 datausu,
                                 arrecant.k00_receit,
                                 arrecant.k00_hist,
                                 ( case
                                     when rRecord.tipo = 1
                                       then 0
                                     else rRecordDisbanco.vlrpago
                                   end ),
                                 datausu,
                                 1,
                                 arrecant.k00_numdig,
                                 arrecant.k00_tipo,
                                 arrecant.k00_tipojm
                                from arrecant
                               where arrecant.k00_numpre = rRecord.k00_numpre
                                 and arrecant.k00_numpar = rRecord.k00_numpar;

        insert into issvar ( q05_codigo,
                             q05_numpre,
                             q05_numpar,
                             q05_valor,
                             q05_ano,
                             q05_mes,
                             q05_histor,
                             q05_aliq,
                             q05_bruto,
                             q05_vlrinf
                           ) select nextval('issvar_q05_codigo_seq'),
                                iNumpreIssVar,
                                issvar.q05_numpar,
                                issvar.q05_valor,
                                issvar.q05_ano,
                                issvar.q05_mes,
                                'ISSQN Complementar gerado automaticamente atraves da baixa de banco devido a quitacao ',
                                issvar.q05_aliq,
                                issvar.q05_bruto,
                                nVlrInformado
                              from issvar
                             where q05_numpre = rRecord.k00_numpre
                               and q05_numpar = rRecord.k00_numpar;


        select k00_codbco,
               k00_codage,
               fc_numbco(k00_codbco,k00_codage) as fc_numbco
          into rRecordBanco
          from arretipo
         where k00_tipo = ( select k00_tipo
                              from arrecant
                             where arrecant.k00_numpre = rRecord.k00_numpre
                               and arrecant.k00_numpar = rRecord.k00_numpar
                             limit 1 );


        insert into db_reciboweb ( k99_numpre,
                                   k99_numpar,
                                   k99_numpre_n,
                                   k99_codbco,
                                   k99_codage,
                                   k99_numbco,
                                   k99_desconto,
                                   k99_tipo,
                                   k99_origem
                                 ) values (
                                   iNumpreIssVar,
                                   rRecord.k00_numpar,
                                   iNumpreRecibo,
                                   coalesce(rRecordBanco.k00_codbco,0),
                                   coalesce(rRecordBanco.k00_codage,'0'),
                                   rRecordBanco.fc_numbco,
                                   0,
                                   2,
                                   1
                                 );

         if lRaise is true then
           perform fc_debug('  <PgtoParcial>  - xxx - valor informado : '||nVlrInformado||' total : '||nVlrTotalInformado,lRaise,false,false);
         end if;

         nVlrTotalInformado := ( nVlrTotalInformado + nVlrInformado );

      end loop;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - 1 - valor antes disbanco : '||nVlrTotalInformado,lRaise,false,false);
      end if;

      if rRecordDisbanco.vlrpago != round(nVlrTotalInformado,2) then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Valor Pago na Disbanco diferente do Valor Total Informado... ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Valor Pago na Disbanco ....: '||rRecordDisbanco.vlrpago,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Valor Total Informado......: '||round(nVlrTotalInformado,2),lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Alterando o valor informado da issvar ajustando com a diferenca encontrada ('||(rRecordDisbanco.vlrpago - round(nVlrTotalInformado,2))||')',lRaise,false,false);
        end if;

        update issvar
           set q05_vlrinf = q05_vlrinf + (rRecordDisbanco.vlrpago - round(nVlrTotalInformado,2))
         where q05_codigo = ( select max(q05_codigo)
                                from issvar
                               where q05_numpre = iNumpreIssVar );
      end if;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - 2 - valor antes disbanco : '||nVlrTotalInformado,lRaise,false,false);
      end if;

      -- Gera Recibopaga
      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Gerando ReciboPaga',lRaise,false,false);
      end if;

      select * from fc_recibo(iNumpreRecibo,rRecordDisbanco.dtpago,rRecordDisbanco.dtpago,iAnoSessao)
        into rRecibo;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Fim do Processamento da ReciboPaga',lRaise,false,false);
      end if;

      if rRecibo.rlerro is true then
        return ' 6 - '||rRecibo.rvmensagem;
      end if;

      -- Acerta o conteudo da disbanco, alterando o numpre do ISSQN quitado pelo da recibopaga
      if lRaise then
        perform fc_debug('  <PgtoParcial>  - 3 - Alterando numpre disbanco ! novo numpre : '||iNumpreRecibo,lRaise,false,false);
      end if;

      update disbanco
         set vlrpago = round((vlrpago - nVlrTotalInformado),2),
             vlrtot  = round((vlrtot  - nVlrTotalInformado),2)
       where idret   = rRecordDisbanco.idret;

       /**
        * Comentando update da tmpantesprocessar pois gerava inconsistencia quando o debito
        * foi pago em duplicidade
        *
       update tmpantesprocessar
         set vlrpago = round((vlrpago - nVlrTotalInformado),2)
       where idret   = rRecordDisbanco.idret;*/

       perform * from recibopaga
         where k00_numnov = rRecordDisbanco.k00_numpre;

       if not found then

         update disbanco
            set k00_numpre = iNumpreRecibo,
                k00_numpar = 0,
                vlrpago    = round(nVlrTotalInformado,2),
                vlrtot     = round(nVlrTotalInformado,2)
          where idret      = rRecordDisbanco.idret;

          /*update tmpantesprocessar
             set vlrpago    = round(nVlrTotalInformado,2)
           where idret      = rRecordDisbanco.idret;*/

       else

         iSeqIdRet := nextval('disbanco_idret_seq');

         if lRaise is true then
           perform fc_debug('  <PgtoParcial>  - idret update : '||rRecordDisbanco.idret||' novo idret : '||iSeqIdRet||' valor antes disbanco : '||nVlrTotalInformado,lRaise,false,false);
         end if;

          insert into disbanco ( k00_numbco,
                                k15_codbco,
                                k15_codage,
                                codret,
                                dtarq,
                                dtpago,
                                vlrpago,
                                vlrjuros,
                                vlrmulta,
                                vlracres,
                                vlrdesco,
                                vlrtot,
                                cedente,
                                vlrcalc,
                                idret,
                                classi,
                                k00_numpre,
                                k00_numpar,
                                convenio,
                                instit )
                        select k00_numbco,
                               k15_codbco,
                               k15_codage,
                               codret,
                               dtarq,
                               dtpago,
                               round(nVlrTotalInformado,2),
                               0,
                               0,
                               0,
                               0,
                               round(nVlrTotalInformado,2),
                               cedente,
                               round(nVlrTotalInformado,2),
                               iSeqIdRet,
                               classi,
                               iNumpreRecibo,
                               0,
                               convenio,
                              instit
                         from disbanco
                        where disbanco.idret = rRecordDisbanco.idret;
           end if;

         if lRaise is true then
           perform fc_debug('  <PgtoParcial>  ',lRaise,false,false);
           perform fc_debug('  <PgtoParcial>  FIM DO PROCESSAMENTO DO IDRET '||rRecordDisbanco.idret,lRaise,false,false);
           perform fc_debug('  <PgtoParcial>  ',lRaise,false,false);
         end if;

    end loop;

    /*******************************************************************************************************************
     *  GERA ABATIMENTOS
     ******************************************************************************************************************/
    --
    -- Verifica se existe abatimentos sendo eles ( PAGAMENTO PARCIAL, CREDITO E DESCONTO )
    --

    if lRaise is true then
      perform fc_debug('  <PgtoParcial> Regra 7 - GERA ABATIMENTO ', lRaise,false,false);
    end if;

    for r_idret in

        select distinct
               disbanco.k00_numpre as numpre,
               disbanco.k00_numpar as numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               disbanco.dtpago,
               round(sum(recibopaga.k00_valor),2) as k00_valor,
               recibopaga.k00_dtpaga,
               disbanco.instit
          from disbanco
               inner join recibopaga     on disbanco.k00_numpre       = recibopaga.k00_numnov
               left  join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                        and numprebloqpag.ar22_numpar = disbanco.k00_numpar
         where disbanco.codret = cod_ret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and numprebloqpag.ar22_numpre is null
           and case when iNumprePagamentoParcial = 0
                    then true
                    else disbanco.k00_numpre > iNumprePagamentoParcial
                end
           and not exists ( select 1
                              from tmpnaoprocessar
                             where tmpnaoprocessar.idret = disbanco.idret )
           and exists ( select 1
                          from arrecad
                         where arrecad.k00_numpre = recibopaga.k00_numpre
                           and arrecad.k00_numpar = recibopaga.k00_numpar
                         union all
                        select 1
                          from arrecant
                         where arrecant.k00_numpre = recibopaga.k00_numpre
                           and arrecant.k00_numpar = recibopaga.k00_numpar
                         union all
                        select 1
                          from arreold
                         where arreold.k00_numpre = recibopaga.k00_numpre
                           and arreold.k00_numpar = recibopaga.k00_numpar
                         union all
                        select 1
                          from arreprescr
                         where arreprescr.k30_numpre = recibopaga.k00_numpre
                           and arreprescr.k30_numpar = recibopaga.k00_numpar
                          limit 1 )
      group by disbanco.k00_numpre,
               disbanco.k00_numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               disbanco.dtpago,
               disbanco.instit,
               recibopaga.k00_dtpaga
      order by disbanco.idret

    loop

      if lRaise is true then

        perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'=')                                  ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - IDRET : '||r_idret.idret                             ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'=')                                  ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '                                                    ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Numpre RECIBOPAGA : '||r_idret.numpre                ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Valor Pago        : '||r_idret.vlrpago::numeric(15,2),lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '                                                    ,lRaise,false,false);

      end if;

      --
      -- se o recibo estiver valido buscamos o valor calculado do recibo
      --
      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Data recibopaga : '||r_idret.k00_dtpaga||' data pago banco : '||r_idret.dtpago,lRaise,false,false);
      end if;

      --
      -- Verificamos se o recibo que esta sendo pago tem algum pagamento parcial
      --   caso tenha pgto parcial recalcula a origem do debito
      --
      perform *
         from recibopaga r
              inner join arreckey           k    on k.k00_numpre       = r.k00_numpre
                                                and k.k00_numpar       = r.k00_numpar
                                                and k.k00_receit       = r.k00_receit
              inner join abatimentoarreckey ak   on ak.k128_arreckey   = k.k00_sequencial
              inner join abatimentodisbanco ab   on ab.k132_abatimento = ak.k128_abatimento
        where k00_numnov    = r_idret.numpre;

      if found then
        lReciboPossuiPgtoParcial := true;
      else

        lReciboPossuiPgtoParcial := false;
        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  ------------------------------------------'            ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Nao Encontrou Pagamento Parcial Anterior'            ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Numpre: '||r_idret.numpre||', IDRet: '||r_idret.idret,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  ------------------------------------------'            ,lRaise,false,false);
        end if;
      end if;

      /**
       * Validamos se o recibo foi gerado por regra, pois caso tenha
       * sido n�o deve recalcular a origem do d�bito
       * --Se for diferente de 0 n�o pode recalcular
       **/
      if lReciboPossuiPgtoParcial is true then

        perform *
         from recibopaga r
              inner join arreckey           k    on k.k00_numpre       = r.k00_numpre
                                                 and k.k00_numpar       = r.k00_numpar
                                                 and k.k00_receit       = r.k00_receit
              inner join abatimentoarreckey ak   on ak.k128_arreckey   = k.k00_sequencial
              inner join abatimentodisbanco ab   on ab.k132_abatimento = ak.k128_abatimento
              inner join db_reciboweb       dw   on r.k00_numnov       = dw.k99_numpre_n
        where k00_numnov   = r_idret.numpre
          and k99_desconto <> 0;

        if found then
          lReciboPossuiPgtoParcial := false;
        end if;

      end if;

      if lRaise then
        perform fc_debug('  <PgtoParcial>  - numpre : '||r_idret.numpre||' data para pagamento : '||fc_proximo_dia_util(r_idret.k00_dtpaga)||' data que foi pago : '||r_idret.dtpago||' encontrou outro abatimento : '||lReciboPossuiPgtoParcial,lRaise,false,false);
      end if;

      if fc_proximo_dia_util(r_idret.k00_dtpaga) >= r_idret.dtpago and lReciboPossuiPgtoParcial is false then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Calculado 1 ',lRaise,false,false);
        end if;

        select round(sum(k00_valor),2) as valor_total_recibo
          into nVlrCalculado
          from recibopaga
               inner join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov
         where recibopaga.k00_numnov = r_idret.numpre
           and disbanco.idret        = r_idret.idret
           and exists ( select 1
                          from arrecad
                         where arrecad.k00_numpre = recibopaga.k00_numpre
                           and arrecad.k00_numpar = recibopaga.k00_numpar
                         limit 1 );

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Valor calculado para recibo pago dentro do vencimento (recibopaga) : '||nVlrCalculado,lRaise,false,false);
        end if;

      else

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Calculado 2 ',lRaise,false,false);
        end if;

        select coalesce(round(sum(utotal),2),0)::numeric(15,2)
          into nVlrCalculado
          from ( select ( substr(fc_calcula,15,13)::float8 +
                          substr(fc_calcula,28,13)::float8 +
                          substr(fc_calcula,41,13)::float8 -
                          substr(fc_calcula,54,13)::float8 ) as utotal
                   from ( select fc_calcula( x.k00_numpre,
                                             x.k00_numpar,
                                             0,
                                             x.dtpago,
                                             x.dtpago,
                                             extract(year from x.dtpago)::integer)
                                        from ( select distinct
                                                      recibopaga.k00_numpre,
                                                      recibopaga.k00_numpar,
                                                      dtpago
                                                 from recibopaga
                                                      inner join disbanco    on disbanco.k00_numpre     = recibopaga.k00_numnov
                                                      inner join arrecad     on arrecad.k00_numpre      = recibopaga.k00_numpre
                                                                            and arrecad.k00_numpar      = recibopaga.k00_numpar
                                                where recibopaga.k00_numnov = r_idret.numpre
                                                  and disbanco.idret        = r_idret.idret ) as x
                        ) as y
                ) as z;

      end if;

      if nVlrCalculado is null then
        nVlrCalculado := 0;
      end if;

      perform 1
         from recibopaga
              inner join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov
              inner join arrecad  on arrecad.k00_numpre  = recibopaga.k00_numpre
                                 and arrecad.k00_numpar  = recibopaga.k00_numpar
              inner join issvar   on issvar.q05_numpre   = recibopaga.k00_numpre
                                 and issvar.q05_numpar   = recibopaga.k00_numpar
        where recibopaga.k00_numnov = r_idret.numpre
          and arrecad.k00_valor     = 0;

      if found then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - **** ISSQN Variavel **** ',lRaise,false,false);
        end if;

        nVlrCalculado := r_idret.vlrpago;

      end if;


      if nVlrCalculado < 0 then
        return '7 - Debito com valor negativo - Numpre : '||r_idret.numpre;
      end if;


      nVlrPgto          := ( r_idret.vlrpago )::numeric(15,2);
      nVlrDiferencaPgto := ( nVlrCalculado - nVlrPgto )::numeric(15,2);

      if lRaise is true then

        perform fc_debug('  <PgtoParcial>  - Calculado ................: '||nVlrCalculado            ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Diferenca ................: '||nVlrDiferencaPgto        ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Tolerancia Pgto Parcial ..: '||nVlrToleranciaPgtoParcial,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Tolerancia Credito .......: '||nVlrToleranciaCredito    ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '                                                       ,lRaise,false,false);

      end if;

      -- Caso o Pagamento Parcial esteja ativado entao a verificado se o valor pago e igual ao total do
      -- e caso nao seja, tambem e verificado se a diferenca do pagamento e menor que a tolenrancia para pagamento
      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - nVlrDiferencaPgto: '||nVlrDiferencaPgto||', nVlrDiferencaPgto: '||nVlrDiferencaPgto||',  nVlrToleranciaPgtoParcial: '||nVlrToleranciaPgtoParcial,lRaise,false,false);
      end if;

      if nVlrDiferencaPgto > 0 and nVlrDiferencaPgto > nVlrToleranciaPgtoParcial then

        -- Percentual pago do debito
        nPercPgto          := (( nVlrPgto * 100 ) / nVlrCalculado )::numeric;

        -- Insere Abatimento
        select nextval('abatimento_k125_sequencial_seq')
          into iAbatimento;

        if lRaise is true then

          perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-'),lRaise,false,false);
          perform fc_debug('  PAGAMENTO PARCIAL : '||iAbatimento,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-'),lRaise,false,false);

        end if;

        insert into abatimento ( k125_sequencial,
                                 k125_tipoabatimento,
                                 k125_datalanc,
                                 k125_hora,
                                 k125_usuario,
                                 k125_instit,
                                 k125_valor,
                                 k125_perc
                               ) values (
                                 iAbatimento,
                                 1,
                                 datausu,
                                 to_char(current_timestamp,'HH24:MI'),
                                 cast(fc_getsession('DB_id_usuario') as integer),
                                 iInstitSessao,
                                 nVlrPgto,
                                 nPercPgto
                               );

        insert into abatimentodisbanco ( k132_sequencial,
                     k132_abatimento,
                     k132_idret
                     ) values (
                      nextval('abatimentodisbanco_k132_sequencial_seq'),
                      iAbatimento,
                      r_idret.idret
                    );


        -- Gera um Recibo Avulso
        select nextval('numpref_k03_numpre_seq')
          into iNumpreReciboAvulso;

        insert into abatimentorecibo ( k127_sequencial,
                                       k127_abatimento,
                                       k127_numprerecibo,
                                       k127_numpreoriginal
                                     ) values (
                                       nextval('abatimentorecibo_k127_sequencial_seq'),
                                       iAbatimento,
                                       iNumpreReciboAvulso,
                                       coalesce( (select k00_numpre
                                                    from tmpdisbanco_inicio_original
                                                   where idret = r_idret.idret), iNumpreReciboAvulso)
                                     );


        -- Geracao de Recibo Avulso por Receita e Pagamento;

        select distinct round(sum(recibopaga.k00_valor),2)
          into nVlrTotalRecibopaga
          from disbanco
               inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
         where disbanco.idret  = r_idret.idret
           and disbanco.instit = iInstitSessao;


        for rRecord in select distinct
                              recibopaga.k00_numcgm     as k00_numcgm,
                              recibopaga.k00_receit     as k00_receit,
                              round(sum(recibopaga.k00_valor),2) as k00_valor
                         from disbanco
                              inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                        where disbanco.idret  = r_idret.idret
                          and disbanco.instit = iInstitSessao
                     group by recibopaga.k00_receit,
                              recibopaga.k00_numcgm
        loop

          select k00_tipo
            into iTipoDebitoPgtoParcial
            from ( select ( select arrecad.k00_tipo
                              from arrecad
                             where arrecad.k00_numpre = recibopaga.k00_numpre
                               and arrecad.k00_numpar = recibopaga.k00_numpar

                             union

                            select arrecant.k00_tipo
                              from arrecant
                             where arrecant.k00_numpre = recibopaga.k00_numpre
                               and arrecant.k00_numpar = recibopaga.k00_numpar
                                limit 1
                          ) as k00_tipo
                     from disbanco
                          inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                    where disbanco.idret  = r_idret.idret
                      and disbanco.instit = iInstitSessao
                 ) as x;


          nPercReceita := ( (rRecord.k00_valor * 100) / nVlrTotalRecibopaga )::numeric(20,10);
          nVlrReceita  := trunc(( nVlrPgto * ( nPercReceita / 100 ))::numeric(15,2),2);

          if lRaise is true then
            perform fc_debug('  <PgtoParcial>  - <PgtoParcial> - Gerando recibo por receita e pagamento ',lRaise,false,false);
          end if;

          insert into recibo ( k00_numcgm,
                               k00_dtoper,
                               k00_receit,
                               k00_hist,
                               k00_valor,
                               k00_dtvenc,
                               k00_numpre,
                               k00_numpar,
                               k00_numtot,
                               k00_numdig,
                               k00_tipo,
                               k00_tipojm,
                               k00_codsubrec,
                               k00_numnov
                             ) values (
                               rRecord.k00_numcgm,
                               datausu,
                               rRecord.k00_receit,
                               504,
                               nVlrReceita,
                               datausu,
                               iNumpreReciboAvulso,
                               1,
                               1,
                               0,
                               iTipoDebitoPgtoParcial,
                               0,
                               0,
                               0
                             );


          insert into arrehist ( k00_numpre,
                                 k00_numpar,
                                 k00_hist,
                                 k00_dtoper,
                                 k00_hora,
                                 k00_id_usuario,
                                 k00_histtxt,
                                 k00_limithist,
                                 k00_idhist
                               ) values (
                                 iNumpreReciboAvulso,
                                 1,
                                 504,
                                 datausu,
                                 '00:00',
                                 1,
                                 'Recibo avulso referente pagamento parcial do recibo da CGF - numnov: ' || r_idret.numpre || ' idret: ' || r_idret.idret,
                                 null,
                                 nextval('arrehist_k00_idhist_seq')
                               );

          perform *
             from arrenumcgm
            where k00_numpre = iNumpreReciboAvulso
              and k00_numcgm = rRecord.k00_numcgm;

          if not found then

            insert into arrenumcgm ( k00_numcgm, k00_numpre ) values ( rRecord.k00_numcgm, iNumpreReciboAvulso );

          end if;
        end loop;


        -- Acerta as origens do Recibo Avulso de acordo os Numpres da recibopaga informado

        select array_to_string(array_accum(iNumpreReciboAvulso || '_' || arrematric.k00_matric || '_' || arrematric.k00_perc), ',')
          into sSql
          from recibopaga
               inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
         where recibopaga.k00_numnov = r_idret.numpre;

        insert into arrematric select distinct
                                      iNumpreReciboAvulso,
                                      arrematric.k00_matric,
                                      -- colocado 100 % fixo porque o numpre do recibo avulso gerado se trata de pagamento parcial
                                      -- e nao vai ter divisao de percentual entre mais de um numpre da mesma matricula
                                      100 as k00_perc
                                 from recibopaga
                                      inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
                                where recibopaga.k00_numnov = r_idret.numpre;


        insert into arreinscr  select distinct
                                      iNumpreReciboAvulso,
                                      arreinscr.k00_inscr,
                                      -- colocado 100 % fixo porque o numpre do recibo avulso gerado se trata de pagamento parcial
                                      -- e nao vai ter divisao de percentual entre mais de um numpre da mesma inscricao
                                      100 as k00_perc
                                 from recibopaga
                                      inner join arreinscr on arreinscr.k00_numpre = recibopaga.k00_numpre
                                where recibopaga.k00_numnov = r_idret.numpre;



        -- Percorre todos os debitos a serem abatidos

        for rRecord in select distinct
                              arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              arrecad.k00_hist,
                              arrecad.k00_receit,
                              arrecad.k00_tipo
                         from recibopaga
                              inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                                and arrecad.k00_numpar = recibopaga.k00_numpar
                                                and arrecad.k00_receit = recibopaga.k00_receit
                        where recibopaga.k00_numnov = r_idret.numpre
                     order by arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              arrecad.k00_receit
        loop

          select arreckey.k00_sequencial,
                 arrecadcompos.k00_sequencial
            into iArreckey,
                 iArrecadCompos
            from arreckey
                 left join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = rRecord.k00_hist;

          --
          -- Alteracao realizada conforme solicitacao da tarefa 75450 solicitada pela Catia Renata
          --   quanto tiver um recibo com desconto manual e for realizado um pagamento parcial o sistema
          --   utiliza como valor calculado o valor liquido (valor com o desconto manual 918)
          --   e deixa o desconto perdido no arrecad, abatimentoarreckey, arreckey sendo que o mesmo ja foi utilizado
          --   para resolver, deletamos o registro de historico 918 do arrecad.
          --

          delete
            from arrecad
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = 918;

          delete
            from abatimentoarreckey
           using arreckey
           where k00_sequencial = k128_arreckey
             and k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = 918;

          delete
            from arreckey
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = 918;

          if iArreckey is null then

            select nextval('arreckey_k00_sequencial_seq')
              into iArreckey;

            insert into arreckey ( k00_sequencial,
                                   k00_numpre,
                                   k00_numpar,
                                   k00_receit,
                                   k00_hist,
                                   k00_tipo
                                 ) values (
                                   iArreckey,
                                   rRecord.k00_numpre,
                                   rRecord.k00_numpar,
                                   rRecord.k00_receit,
                                   rRecord.k00_hist,
                                   rRecord.k00_tipo
                                 );

          end if;

          -- Insere ligacao do abatimento com o debito

          select nextval('abatimentoarreckey_k128_sequencial_seq')
            into iAbatimentoArreckey;

          insert into abatimentoarreckey ( k128_sequencial,
                                           k128_arreckey,
                                           k128_abatimento,
                                           k128_valorabatido,
                                           k128_correcao,
                                           k128_juros,
                                           k128_multa
                                         ) values (
                                           iAbatimentoArreckey,
                                           iArreckey,
                                           iAbatimento,
                                           0,
                                           0,
                                           0,
                                           0
                                         );

          if iArrecadCompos is not null then

            insert into abatimentoarreckeyarrecadcompos ( k129_sequencial,
                                                          k129_abatimentoarreckey,
                                                          k129_arrecadcompos,
                                                          k129_vlrhist,
                                                          k129_correcao,
                                                          k129_juros,
                                                          k129_multa
                                                        ) values (
                                                          nextval('abatimentoarreckeyarrecadcompos_k129_sequencial_seq'),
                                                          iAbatimentoArreckey,
                                                          iArrecadCompos,
                                                          0,
                                                          0,
                                                          0,
                                                          0
                                                        );
          end if;

        end loop;


        -- Consulta valor total histoico do debito

        select round(sum(x.k00_valor),2) as k00_valor
          into nVlrTotalHistorico
          from ( select distinct arrecad.*
                   from recibopaga
                      inner join arrecad  on arrecad.k00_numpre = recibopaga.k00_numpre
                                       and arrecad.k00_numpar = recibopaga.k00_numpar
                                       and arrecad.k00_receit = recibopaga.k00_receit
                where recibopaga.k00_numnov = r_idret.numpre ) as x;


        if lRaise is true then

          perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Total Historico   : '||nVlrTotalHistorico,lRaise,false,false);

        end if;


        -- Valor a ser abatido do arrecad e igual ao percentual do pagamento sobre o total historico
        nVlrAbatido := trunc(( nVlrTotalHistorico * ( nPercPgto / 100 ))::numeric(15,2),2);


        nVlrTotalJuroMultaCorr := nVlrPgto - nVlrAbatido;


        -- Dilui o valor abatido do arrecad ate zerar o nVlrAbatido encontrado
        while round(nVlrAbatido,2) > 0 loop

          nPercPgto := (( nVlrAbatido * 100 ) / nVlrTotalHistorico )::numeric;

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'.')              ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Valor Abatido   : '||nVlrAbatido ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Perc  Pagamento : '||nPercPgto   ,lRaise,false,false);

          end if;

          for rRecord in select *,
                                case
                                  when k00_hist = 918 then 0
                                  else ( substr(fc_calcula,15,13)::float8 - substr(fc_calcula, 2,13)::float8 )::float8
                                end as vlrcorrecao,
                                case when k00_hist = 918 then 0::float8 else substr(fc_calcula,28,13)::float8 end as vlrjuros,
                                case when k00_hist = 918 then 0::float8 else substr(fc_calcula,41,13)::float8 end as vlrmulta
                           from ( select abatimentoarreckey.k128_sequencial,
                                         abatimentoarreckeyarrecadcompos.k129_sequencial,
                                         arrecad.*,
                                         arrecadcompos.*,
                                         fc_calcula( arrecad.k00_numpre,
                                                     arrecad.k00_numpar,
                                                     arrecad.k00_receit,
                                                     r_idret.dtpago,
                                                     r_idret.dtpago,
                                                     extract( year from r_idret.dtpago )::integer )
                                    from abatimentoarreckey
                                         inner join arreckey      on arreckey.k00_sequencial    = abatimentoarreckey.k128_arreckey
                                         left  join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
                                         left  join abatimentoarreckeyarrecadcompos on k129_abatimentoarreckey = abatimentoarreckey.k128_sequencial
                                         inner join arrecad       on arrecad.k00_numpre         = arreckey.k00_numpre
                                                                 and arrecad.k00_numpar         = arreckey.k00_numpar
                                                                 and arrecad.k00_receit         = arreckey.k00_receit
                                                                 and arrecad.k00_hist           = arreckey.k00_hist
                                   where abatimentoarreckey.k128_abatimento = iAbatimento
                                order by arrecad.k00_numpre asc,
                                         arrecad.k00_numpar asc,
                                         arrecad.k00_valor  desc
                                ) as x


          loop

            -- Caso tenha sido zerado a variavel nVlrAbatido entao sai do loop

            if nVlrAbatido <= 0 then

              exit;

            end if;

            nVlrPgtoParcela := trunc((rRecord.k00_valor * ( nPercPgto / 100 ))::numeric(20,10),2);

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - Valor Pagamento da Parcela: '||nVlrPgtoParcela,lRaise,false,false);
              perform fc_debug('  <PgtoParcial>  - lInsereJurMulCorr: '||lInsereJurMulCorr,lRaise,false,false);
            end if;

            if lInsereJurMulCorr then

              nVlrJuros         := trunc((rRecord.vlrjuros     * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrMulta         := trunc((rRecord.vlrmulta     * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrCorrecao      := trunc((rRecord.vlrcorrecao  * ( nPercPgto / 100 ))::numeric(20,10),2);

              nVlrHistCompos    := trunc((rRecord.k00_vlrhist  * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrJurosCompos   := trunc((rRecord.k00_juros    * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrMultaCompos   := trunc((rRecord.k00_multa    * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrCorreCompos   := trunc((rRecord.k00_correcao * ( nPercPgto / 100 ))::numeric(20,10),2);

              if lRaise is true then
                perform fc_debug('  <PgtoParcial>  - nPercPgto:          : '||nPercPgto           ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.vlrjuros    : '||rRecord.vlrjuros    ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.vlrmulta    : '||rRecord.vlrmulta    ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.vlrcorrecao : '||rRecord.vlrcorrecao ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>'                                                ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_vlrhist : '||rRecord.k00_vlrhist ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_juros   : '||rRecord.k00_juros   ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_multa   : '||rRecord.k00_multa   ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_correcao: '||rRecord.k00_correcao,lRaise,false,false);

                perform fc_debug('  <PgtoParcial>  -'                                             ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  -'                                             ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrJuros      : '||nVlrJuros                ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrMulta      : '||nVlrMulta                ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrCorrecao   : '||nVlrCorrecao             ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - '                                            ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrHistCompos : '||nVlrHistCompos           ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrJurosCompos: '||nVlrJurosCompos          ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrMultaCompos: '||nVlrMultaCompos          ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrCorreCompos: '||nVlrCorreCompos          ,lRaise,false,false);
              end if;

            else

              nVlrJuros         := 0;
              nVlrMulta         := 0;
              nVlrCorrecao      := 0;

              nVlrHistCompos    := 0;
              nVlrJurosCompos   := 0;
              nVlrMultaCompos   := 0;
              nVlrCorreCompos   := 0;

            end if;
            if lRaise is true then

              perform fc_debug('  <PgtoParcial>  -    Numpre: '||lpad(rRecord.k00_numpre,10,'0')||' Numpar: '||lpad(rRecord.k00_numpar, 3,'0')||' Receita: '||rRecord.k00_receit||' Valor Parcela: '||rRecord.k00_valor::numeric(15,2)||' Corr: '||nVlrCorrecao::numeric(15,2)||' Juros: '||nVlrJuros::numeric(15,2)||' Multa: '||nVlrMulta::numeric(15,2)||' Valor Pago: '||nVlrPgtoParcela::numeric(15,2)||' Resto: '||nVlrAbatido::numeric(15,2),lRaise,false,false);

            end if;

            -- Nao deixa retornar o valor zerado

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - nVlrPgtoParcela: '||nVlrPgtoParcela||' rRecord.k00_hist: '||rRecord.k00_hist,lRaise,false,false);
            end if;

            if round(nVlrPgtoParcela,2) <= 0 and rRecord.k00_hist != 918 then

              if lRaise is true then

                perform fc_debug('  <PgtoParcial>  -    * Valor Parcela Menor que 0,01 - Corrige para 0,01 ',lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);

              end if;

              nVlrPgtoParcela := 0.01;

            end if;


            update abatimentoarreckey
               set k128_valorabatido = ( k128_valorabatido + nVlrPgtoParcela )::numeric(15,2),
                   k128_correcao     = ( k128_correcao     + nVlrCorrecao    )::numeric(15,2),
                   k128_juros        = ( k128_juros        + nVlrJuros       )::numeric(15,2),
                   k128_multa        = ( k128_multa        + nVlrMulta       )::numeric(15,2)
             where k128_sequencial   = rRecord.k128_sequencial;


            if rRecord.k129_sequencial is not null then

              update abatimentoarreckeyarrecadcompos
                 set k129_vlrhist      = ( k129_vlrhist  + nVlrHistCompos  )::numeric(15,2),
                     k129_correcao     = ( k129_correcao + nVlrCorreCompos )::numeric(15,2),
                     k129_juros        = ( k129_juros    + nVlrJurosCompos )::numeric(15,2),
                     k129_multa        = ( k129_multa    + nVlrMultaCompos )::numeric(15,2)
               where k129_sequencial   = rRecord.k129_sequencial;

            end if;


            nVlrAbatido := trunc(( nVlrAbatido - nVlrPgtoParcela )::numeric(20,10),2)::numeric(15,2);

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - nVlrAbatido: '||nVlrAbatido,lRaise,false,false);
            end if;

          end loop;

          if lRaise is true then
            perform fc_debug('  <PgtoParcial>  - lInsereJurMulCorr = False',lRaise,false,false);
          end if;

          lInsereJurMulCorr := false;

        end loop;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - iAbatimento: '||iAbatimento,lRaise,false,false);
        end if;

        select round(sum(abatimentoarreckey.k128_correcao) +
                     sum(abatimentoarreckey.k128_juros)    +
                     sum(abatimentoarreckey.k128_multa),2) as totaljuromultacorr
          into rRecord
          from abatimentoarreckey
         where abatimentoarreckey.k128_abatimento = iAbatimento;


        if lRaise is true then

          perform fc_debug('  <PgtoParcial>  - '                                                          ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Total - Juros/ Multa / Corr : '||rRecord.totaljuromultacorr,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Total - Geral               : '||nVlrTotalJuroMultaCorr    ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - '                                                          ,lRaise,false,false);

        end if;


        if rRecord.totaljuromultacorr <> round(nVlrTotalJuroMultaCorr,2) then

          update abatimentoarreckey
             set k128_correcao = ( k128_correcao + ( nVlrTotalJuroMultaCorr - round(rRecord.totaljuromultacorr,2) ))::numeric(15,2)
           where k128_sequencial in ( select max(k128_sequencial)
                                         from abatimentoarreckey
                                        where k128_abatimento = iAbatimento );
        end if;

        for rRecord in select distinct
                              arrecad.*,
                              abatimentoarreckey.k128_valorabatido,
                              arrecadcompos.k00_sequencial                              as arrecadcompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_vlrhist ,0) as histcompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_correcao,0) as correcompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_juros   ,0) as juroscompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_multa   ,0) as multacompos
                         from abatimentoarreckey
                              inner join arreckey                        on arreckey.k00_sequencial    = abatimentoarreckey.k128_arreckey
                              inner join arrecad                         on arrecad.k00_numpre         = arreckey.k00_numpre
                                                                        and arrecad.k00_numpar         = arreckey.k00_numpar
                                                                        and arrecad.k00_receit         = arreckey.k00_receit
                                                                        and arrecad.k00_hist           = arreckey.k00_hist
                              left  join arrecadcompos                   on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
                              left  join abatimentoarreckeyarrecadcompos on abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey = abatimentoarreckey.k128_sequencial
                        where abatimentoarreckey.k128_abatimento = iAbatimento
                     order by arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              arrecad.k00_receit

        loop


          -- Caso o valor abata todo valor devido entao e quitado a tabela

          if round((rRecord.k00_valor - rRecord.k128_valorabatido),2) = 0 then

            insert into arrecantpgtoparcial ( k00_numpre,
                                              k00_numpar,
                                              k00_numcgm,
                                              k00_dtoper,
                                              k00_receit,
                                              k00_hist,
                                              k00_valor,
                                              k00_dtvenc,
                                              k00_numtot,
                                              k00_numdig,
                                              k00_tipo,
                                              k00_tipojm,
                                              k00_abatimento
                                            ) values (
                                              rRecord.k00_numpre,
                                              rRecord.k00_numpar,
                                              rRecord.k00_numcgm,
                                              rRecord.k00_dtoper,
                                              rRecord.k00_receit,
                                              rRecord.k00_hist,
                                              rRecord.k00_valor,
                                              rRecord.k00_dtvenc,
                                              rRecord.k00_numtot,
                                              rRecord.k00_numdig,
                                              rRecord.k00_tipo,
                                              rRecord.k00_tipojm,
                                              iAbatimento
                                            );
            delete
              from arrecad
             where k00_numpre = rRecord.k00_numpre
               and k00_numpar = rRecord.k00_numpar
               and k00_receit = rRecord.k00_receit
               and k00_hist   = rRecord.k00_hist;

          else

            update arrecad
             set k00_valor  = ( k00_valor - rRecord.k128_valorabatido )
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = rRecord.k00_hist;

          end if;


          if rRecord.arrecadcompos is not null then

            update arrecadcompos
               set k00_vlrhist    = ( k00_vlrhist  - rRecord.histcompos  ),
                   k00_correcao   = ( k00_correcao - rRecord.correcompos ),
                   k00_juros      = ( k00_juros    - rRecord.juroscompos ),
                   k00_multa      = ( k00_multa    - rRecord.multacompos )
             where k00_sequencial = rRecord.arrecadcompos;

          end if;

        end loop;

        -- Acerta NUMPRE da disbanco
        if lRaise then
          perform fc_debug('  <PgtoParcial>  - 4 - Alterando numpre disbanco ! novo numpre : '||iNumpreReciboAvulso,lRaise,false,false);
        end if;

        update disbanco
           set k00_numpre = iNumpreReciboAvulso,
               k00_numpar = 0
         where idret      = r_idret.idret;


      --
      -- FIM PGTO PARCIAL
      --
      -- INICIO CREDITO/DESCONTO
      -- validacao da tolerancia do credito
      -- se o valor da diferenca for menor que 0 (significa que � um credito)
      -- e se o valor absoluto da diferenca for maior que o valor da tolerancia para credito sera gerado o credito
      --
      --
      elsif nVlrDiferencaPgto != 0 and ( nVlrDiferencaPgto > 0 or ( nVlrDiferencaPgto < 0 and abs(nVlrDiferencaPgto) > nVlrToleranciaCredito) ) then


        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - nVlrDiferencaPgto: '||nVlrDiferencaPgto||' - nVlrToleranciaCredito: '||nVlrToleranciaCredito, lRaise, false, false);
        end if;

        select nextval('abatimento_k125_sequencial_seq')
          into iAbatimento;


        if nVlrDiferencaPgto > 0 then

          iTipoAbatimento   = 2;

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - DESCONTO : '||iAbatimento,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);

          end if;

        else

          iTipoAbatimento   = 3;
          nVlrDiferencaPgto := ( nVlrDiferencaPgto * -1 );

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - CREDITO : '||iAbatimento ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);

          end if;

        end if;


        nPercPgto := (( nVlrDiferencaPgto * 100 ) / r_idret.k00_valor )::numeric;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Lancando Abatimento. nPercPgto: '||nPercPgto,lRaise,false,false);
        end if;

        insert into abatimento ( k125_sequencial,
                                 k125_tipoabatimento,
                                 k125_datalanc,
                                 k125_hora,
                                 k125_usuario,
                                 k125_instit,
                                 k125_valor,
                                 k125_perc,
                                 k125_valordisponivel
                               ) values (
                                 iAbatimento,
                                 iTipoAbatimento,
                                 datausu,
                                 to_char(current_timestamp,'HH24:MI'),
                                 cast(fc_getsession('DB_id_usuario') as integer),
                                 iInstitSessao,
                                 nVlrDiferencaPgto,
                                 nPercPgto,
                                 nVlrDiferencaPgto
                               );

        insert into abatimentodisbanco ( k132_sequencial,
                                         k132_abatimento,
                                         k132_idret
                                       ) values (
                                         nextval('abatimentodisbanco_k132_sequencial_seq'),
                                         iAbatimento,
                                         r_idret.idret
                                       );
        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - TipoAbatimento: '||iTipoAbatimento,lRaise,false,false);
        end if;

          if iTipoAbatimento = 3 then


          -- Gera um Recibo Avulso

          select nextval('numpref_k03_numpre_seq')
            into iNumpreReciboAvulso;

          if lRaise is true then
            perform fc_debug('  <PgtoParcial> -  ## Gerando recibo avulso. NumpreReciboAvulso: '||iNumpreReciboAvulso,lRaise,false,false);
          end if;

          insert into abatimentorecibo ( k127_sequencial,
                                         k127_abatimento,
                                         k127_numprerecibo,
                                         k127_numpreoriginal
                                       ) values (
                                         nextval('abatimentorecibo_k127_sequencial_seq'),
                                         iAbatimento,
                                         iNumpreReciboAvulso,
                                         coalesce( (select k00_numpre
                                                      from tmpdisbanco_inicio_original
                                                     where idret = r_idret.idret ), iNumpreReciboAvulso)
                                       );

          for rRecord in select k00_numcgm,
                                k00_tipo,
                                round(sum(k00_valor),2) as k00_valor
                           from ( select recibopaga.k00_numcgm,
                                         ( select arrecad.k00_tipo
                                             from arrecad
                                            where arrecad.k00_numpre = recibopaga.k00_numpre
                                              and arrecad.k00_numpar = recibopaga.k00_numpar
                                            union all
                                           select arrecant.k00_tipo
                                             from arrecant
                                            where arrecant.k00_numpre = recibopaga.k00_numpre
                                              and arrecant.k00_numpar = recibopaga.k00_numpar
                                            union all
                                           select arreold.k00_tipo
                                             from arreold
                                            where arreold.k00_numpre = recibopaga.k00_numpre
                                              and arreold.k00_numpar = recibopaga.k00_numpar
                                    union all
                                 select 1
                                   from arreprescr
                                  where arreprescr.k30_numpre = recibopaga.k00_numpre
                                    and arreprescr.k30_numpar = recibopaga.k00_numpar
                                         limit 1 ) as k00_tipo,
                                         recibopaga.k00_valor
                                    from disbanco
                                         inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                                   where disbanco.idret  = r_idret.idret
                                     and disbanco.instit = iInstitSessao
                                ) as x
                       group by k00_numcgm,
                                k00_tipo
           loop

             nVlrReceita := ( rRecord.k00_valor * ( nPercPgto / 100 ) )::numeric(15,2);

             select k00_receitacredito
               into iReceitaCredito
               from arretipo
              where k00_tipo = rRecord.k00_tipo;

              if lRaise is true then
                perform fc_debug('  <PgtoParcial>  - iReceitaCredito: '||iReceitaCredito,lRaise,false,false);
              end if;

             if iReceitaCredito is null then
               return '8 - Receita de Credito nao configurado para o tipo : '||rRecord.k00_tipo;
             end if;

             if lRaise is true then
               perform fc_debug('  <PgtoParcial>  - ## lancando o recibo ref ao credito. ReceitaCredito: '||rRecord.k00_tipo||' ValorReceita: '||nVlrReceita,lRaise,false,false);
             end if;

             insert into recibo ( k00_numcgm,
                                  k00_dtoper,
                                  k00_receit,
                                  k00_hist,
                                  k00_valor,
                                  k00_dtvenc,
                                  k00_numpre,
                                  k00_numpar,
                                  k00_numtot,
                                  k00_numdig,
                                  k00_tipo,
                                  k00_tipojm,
                                  k00_codsubrec,
                                  k00_numnov
                                ) values (
                                  rRecord.k00_numcgm,
                                  datausu,
                                  iReceitaCredito,
                                  505,
                                  nVlrReceita,
                                  datausu,
                                  iNumpreReciboAvulso,
                                  1,
                                  1,
                                  0,
                                  iTipoReciboAvulso,
                                  0,
                                  0,
                                  0
                                );

             insert into arrehist ( k00_numpre,
                                    k00_numpar,
                                    k00_hist,
                                    k00_dtoper,
                                    k00_hora,
                                    k00_id_usuario,
                                    k00_histtxt,
                                    k00_limithist,
                                    k00_idhist
                                  ) values (
                                    iNumpreReciboAvulso,
                                    1,
                                    505,
                                    datausu,
                                    '00:00',
                                    1,
                                    'Recibo avulso referente ao credito do recibo da CGF - numnov: ' || r_idret.numpre || 'idret: ' || r_idret.idret,
                                    null,
                                    nextval('arrehist_k00_idhist_seq')
                                  );

             perform *
                from arrenumcgm
               where k00_numpre = iNumpreReciboAvulso
                 and k00_numcgm = rRecord.k00_numcgm;

             if not found then
               perform fc_debug('  <PgtoParcial>  - inserindo registro do recibo na arrenumcgm',lRaise,false,false);
               insert into arrenumcgm ( k00_numcgm, k00_numpre ) values ( rRecord.k00_numcgm, iNumpreReciboAvulso );

             end if;

           end loop;

           if lRaise is true then
             perform fc_debug('  <PgtoParcial>  - Inserindo na Arrematric [3]:'||iNumpreReciboAvulso,lRaise,false,false);
           end if;

           select array_to_string(array_accum(distinct iNumpreReciboAvulso || '-' || arrematric.k00_matric || '-' || arrematric.k00_perc),' , ')
             into sDebug
             from recibopaga
                  inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
            where recibopaga.k00_numnov = r_idret.numpre;

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - '||sDebug,lRaise,false,false);
            end if;

           insert into arrematric select distinct
                                         iNumpreReciboAvulso,
                                         arrematric.k00_matric,
                                         arrematric.k00_perc
                                    from recibopaga
                                         inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
                                   where recibopaga.k00_numnov = r_idret.numpre;

           insert into arreinscr  select distinct
                                         iNumpreReciboAvulso,
                                         arreinscr.k00_inscr,
                                         arreinscr.k00_perc
                                    from recibopaga
                                         inner join arreinscr on arreinscr.k00_numpre = recibopaga.k00_numpre
                                   where recibopaga.k00_numnov = r_idret.numpre;

          if nVlrCalculado = 0 then

            if lRaise then
              perform fc_debug('  <PgtoParcial>  - 5 - Alterando numpre disbanco ! novo numpre : '||iNumpreReciboAvulso,lRaise,false,false);
            end if;

            update disbanco
               set k00_numpre = iNumpreReciboAvulso,
                   k00_numpar = 0
             where idret      = r_idret.idret;

          else

            if lRaise is true or true then
              perform fc_debug('  <PgtoParcial>  - Insere Disbanco',lRaise,false,false);
            end if;

            select nextval('disbanco_idret_seq')
              into iSeqIdRet;

            insert into disbanco (k00_numbco,
                                  k15_codbco,
                                  k15_codage,
                                  codret,
                                  dtarq,
                                  dtpago,
                                  vlrpago,
                                  vlrjuros,
                                  vlrmulta,
                                  vlracres,
                                  vlrdesco,
                                  vlrtot,
                                  cedente,
                                  vlrcalc,
                                  idret,
                                  classi,
                                  k00_numpre,
                                  k00_numpar,
                                  convenio,
                                  instit )
                           select k00_numbco,
                                  k15_codbco,
                                  k15_codage,
                                  codret,
                                  dtarq,
                                  dtpago,
                                  round(nVlrDiferencaPgto,2),
                                  0,
                                  0,
                                  0,
                                  0,
                                  round(nVlrDiferencaPgto,2),
                                  cedente,
                                  round(vlrcalc,2),
                                  iSeqIdRet,
                                  classi,
                                  iNumpreReciboAvulso,
                                  0,
                                  convenio,
                                 instit
                            from disbanco
                           where disbanco.idret = r_idret.idret;


            insert into tmpantesprocessar ( idret,
                                         vlrpago,
                                         v01_seq
                                       ) values (
                                         iSeqIdRet,
                                         nVlrDiferencaPgto,
                                         ( select nextval('w_divold_seq') )
                                       );

            update disbanco
               set vlrpago  = round(( vlrpago - nVlrDiferencaPgto ),2),
                   vlrtot   = round(( vlrtot  - nVlrDiferencaPgto ),2)
             where idret    = r_idret.idret;

            update tmpantesprocessar
               set vlrpago = round( vlrpago - nVlrDiferencaPgto,2 )
             where idret   = r_idret.idret;

          end if;

        end if;

        while nVlrDiferencaPgto > 0 loop

          nPercDesconto := (( nVlrDiferencaPgto * 100 ) / r_idret.k00_valor )::numeric;

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'.')               ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Percentual : '||nPercDesconto     ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Diferenca  : '||nVlrDiferencaPgto ,lRaise,false,false);

          end if;

          perform 1
             from recibopaga
            where recibopaga.k00_numnov = r_idret.numpre
              and recibopaga.k00_hist  != 918
              and exists ( select 1
                             from arrecad
                            where arrecad.k00_numpre = recibopaga.k00_numpre
                              and arrecad.k00_numpar = recibopaga.k00_numpar
                              and arrecad.k00_receit = recibopaga.k00_receit
                            union all
                           select 1
                             from arrecant
                            where arrecant.k00_numpre = recibopaga.k00_numpre
                              and arrecant.k00_numpar = recibopaga.k00_numpar
                              and arrecant.k00_receit = recibopaga.k00_receit
                            union all
                           select 1
                             from arreold
                            where arreold.k00_numpre = recibopaga.k00_numpre
                              and arreold.k00_numpar = recibopaga.k00_numpar
                              and arreold.k00_receit = recibopaga.k00_receit
                            union all
                           select 1
                             from arreprescr
                            where arreprescr.k30_numpre = recibopaga.k00_numpre
                              and arreprescr.k30_numpar = recibopaga.k00_numpar
                              and arreprescr.k30_receit = recibopaga.k00_receit
                            limit 1 );

          if not found then
            return '9 - Recibo '||r_idret.numpre||' inconsistente. IDRET : '||r_idret.idret;
          end if;

          for rRecord in select distinct
                                recibopaga.k00_numpre,
                                recibopaga.k00_numpar,
                                recibopaga.k00_receit,
                                recibopaga.k00_hist,
                                recibopaga.k00_numcgm,
                                recibopaga.k00_numtot,
                                recibopaga.k00_numdig,
                                ( select arrecad.k00_tipo
                                    from arrecad
                                   where arrecad.k00_numpre  = recibopaga.k00_numpre
                                     and arrecad.k00_numpar  = recibopaga.k00_numpar
                                   union all
                                  select arrecant.k00_tipo
                                    from arrecant
                                   where arrecant.k00_numpre = recibopaga.k00_numpre
                                     and arrecant.k00_numpar = recibopaga.k00_numpar
                                   union all
                                  select arreold.k00_tipo
                                    from arreold
                                   where arreold.k00_numpre = recibopaga.k00_numpre
                                     and arreold.k00_numpar = recibopaga.k00_numpar
                                   union all
                                  select 1
                                    from arreprescr
                                   where arreprescr.k30_numpre = recibopaga.k00_numpre
                                     and arreprescr.k30_numpar = recibopaga.k00_numpar
                                   limit 1 ) as k00_tipo,
                                round(sum(recibopaga.k00_valor),2) as k00_valor
                           from recibopaga
                          where recibopaga.k00_numnov = r_idret.numpre
                            and recibopaga.k00_hist  != 918
                            and exists ( select 1
                                           from arrecad
                                          where arrecad.k00_numpre = recibopaga.k00_numpre
                                            and arrecad.k00_numpar = recibopaga.k00_numpar
                                            and arrecad.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arrecant
                                          where arrecant.k00_numpre = recibopaga.k00_numpre
                                            and arrecant.k00_numpar = recibopaga.k00_numpar
                                            and arrecant.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arreold
                                          where arreold.k00_numpre = recibopaga.k00_numpre
                                            and arreold.k00_numpar = recibopaga.k00_numpar
                                            and arreold.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arreprescr
                                          where arreprescr.k30_numpre = recibopaga.k00_numpre
                                            and arreprescr.k30_numpar = recibopaga.k00_numpar
                                            and arreprescr.k30_receit = recibopaga.k00_receit
                                          limit 1 )
                       group by recibopaga.k00_numpre,
                                recibopaga.k00_numpar,
                                recibopaga.k00_receit,
                                recibopaga.k00_hist,
                                recibopaga.k00_numcgm,
                                recibopaga.k00_numtot,
                                recibopaga.k00_numdig
          loop

            if nVlrDiferencaPgto <= 0 then

              if lRaise is true then

                perform fc_debug('  <PgtoParcial>  - '     ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - SAIDA',lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - '     ,lRaise,false,false);

              end if;

              exit;

            end if;

            nVlrPgtoParcela := trunc((rRecord.k00_valor * ( nPercDesconto / 100 ))::numeric,2);


            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  -   Numpre: '||lpad(rRecord.k00_numpre,10,'0')||' Numpar: '||lpad(rRecord.k00_numpar, 3,'0')||' Receita: '||lpad(rRecord.k00_receit,10,'0')||' Valor Parcela: '||rRecord.k00_valor::numeric(15,2)||' Valor Pago: '||nVlrPgtoParcela::numeric(15,2)||' Resto: '||nVlrDiferencaPgto::numeric(15,2),lRaise,false,false);
            end if;


            if nVlrPgtoParcela <= 0 then

              if lRaise is true then
                perform fc_debug('  <PgtoParcial>  -   * Valor Parcela Menor que 0,01 - Corrige para 0,01 ',lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);
              end if;

              nVlrPgtoParcela := 0.01;

            end if;


            select k00_sequencial
              into iArreckey
              from arrecadacao.arreckey
             where k00_numpre = rRecord.k00_numpre
               and k00_numpar = rRecord.k00_numpar
               and k00_receit = rRecord.k00_receit
               and k00_hist   = rRecord.k00_hist;


            if not found then

              select nextval('arreckey_k00_sequencial_seq')
                into iArreckey;

              insert into arreckey ( k00_sequencial,
                                     k00_numpre,
                                     k00_numpar,
                                     k00_receit,
                                     k00_hist,
                                     k00_tipo
                                   ) values (
                                     iArreckey,
                                     rRecord.k00_numpre,
                                     rRecord.k00_numpar,
                                     rRecord.k00_receit,
                                     rRecord.k00_hist,
                                     rRecord.k00_tipo
                                   );
            end if;


            select k128_sequencial
              into iAbatimentoArreckey
              from abatimentoarreckey
                   inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey
             where abatimentoarreckey.k128_abatimento = iAbatimento
               and arreckey.k00_numpre = rRecord.k00_numpre
               and arreckey.k00_numpar = rRecord.k00_numpar
               and arreckey.k00_receit = rRecord.k00_receit
               and arreckey.k00_hist   = rRecord.k00_hist;

            if found then

              update abatimentoarreckey
                 set k128_valorabatido = ( k128_valorabatido + nVlrPgtoParcela )::numeric(15,2)
               where k128_sequencial   = iAbatimentoArreckey;

            else

              -- Insere ligacao do abatimento com o

              insert into abatimentoarreckey ( k128_sequencial,
                                               k128_arreckey,
                                               k128_abatimento,
                                               k128_valorabatido,
                                             k128_correcao,
                                             k128_juros,
                                             k128_multa
                                             ) values (
                                               nextval('abatimentoarreckey_k128_sequencial_seq'),
                                               iArreckey,
                                               iAbatimento,
                                               nVlrPgtoParcela,
                                               0,
                                               0,
                                               0
                                             );
            end if;

            nVlrDiferencaPgto := round(nVlrDiferencaPgto - nVlrPgtoParcela,2);

          end loop;

        end loop;

      end if; -- fim credito/desconto

    end loop;

    if lRaise is true then
      perform fc_debug('  <PgtoParcial>  -  FIM ABATIMENTO ',lRaise,false,false);
    end if;

  end if;

  /**
   * Fim do Pagamento Parcial
   */

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - processando numpres duplos com pagamento em cota unica e parcelado no mesmo arquivo...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.instit,
             disbanco.k00_numpre,
             disbanco.k00_numpar,
             coalesce((select count(*)
                         from recibopaga
                        where recibopaga.k00_numnov = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as quant_recibopaga,
             coalesce((select count(*)
                         from arrecad
                        where arrecad.k00_numpre = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as quant_arrecad_unica,
             coalesce((select max(k00_numtot)
                         from arrecad
                        where arrecad.k00_numpre = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as arrecad_unica_numtot,
             coalesce((select count(distinct k00_numpar)
                         from arrecad
                        where arrecad.k00_numpre = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as arrecad_unica_quant_numpar,
             coalesce((select max(d2.idret)
                         from disbanco d2
                        where d2.k00_numpre = disbanco.k00_numpre
                          and d2.codret = disbanco.codret
                          and d2.idret <> disbanco.idret
                          and classi is false),0) as idret_mesmo_numpre
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
    order by idret
  loop

    -- idret_mesmo_numpre
    -- busca se tem algum numpre duplo no mesmo arquivo (significa que o contribuinte pagou no mesmo dia e banco e consequentemente no mesmo arquivo
    -- o numpre numpre 2 ou mais vezes

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - numpre: '||r_codret.k00_numpre||' - parcela: '||r_codret.k00_numpar||' - quant_recibopaga: '||r_codret.quant_recibopaga||' - quant_arrecad_unica: '||r_codret.quant_arrecad_unica||' - arrecad_unica_numtot: '||r_codret.arrecad_unica_numtot||' - arrecad_unica_quant_numpar: '||r_codret.arrecad_unica_quant_numpar,lRaise,false,false);
    end if;

    -- alteracao 1
    -- o sistema tem que descobrir nos casos de pagamento da unica e parcelado, qual o idret na unica de maior percentual (pois pode ter pago 2 unicas)
    -- e nao inserir na tabela "tmpnaoprocessar" o idret desse registro

    if r_codret.k00_numpar = 0 and r_codret.quant_arrecad_unica > 0 then

      if r_codret.arrecad_unica_quant_numpar <> r_codret.arrecad_unica_numtot then
        -- se for unica e a quantidade de parcelas em aberto for diferente da quantidade total de parcelas, significa que o contribuinte pagou como unica
        -- mas ja tem parcelas em aberto, e dessa forma o sistema nao vai processar esse registro para alguem verificar o que realmente vai ser feito,
        -- pois o contribuinte pagou o valor da unica mas nao tem mais todas as parcelas que formaram a unica em aberto

        if cCliente != 'ALEGRETE' then
          insert into tmpnaoprocessar values (r_codret.idret);

          if lRaise is true then
           perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (1): '||r_codret.idret,lRaise,false,false);
          end if;
        end if;

      else

        for r_testa in
          select idret,
                 k00_numpre,
                 k00_numpar
            from disbanco
           where disbanco.k00_numpre =  r_codret.k00_numpre
             and disbanco.codret     =  r_codret.codret
             and disbanco.idret      <> r_codret.idret
             and classi              is false
        loop

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - idret: '||r_testa.idret||' - numpar: '||r_testa.k00_numpar,lRaise,false,false);
          end if;

          -- busca a parcela unica de menor valor (maior percentual de desconto) paga por esse numpre
          select idret
          into iIdRetProcessar
          from disbanco
          where disbanco.k00_numpre =  r_codret.k00_numpre
                and disbanco.k00_numpar = 0
                and disbanco.codret     =  r_codret.codret
                and classi is false
          order by vlrpago limit 1;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - idret: '||r_testa.idret||' - iIdRetProcessar: '||iIdRetProcessar,lRaise,false,false);
          end if;

          -- senao for o registro da unica de maior percentual nao processa
          if iIdRetProcessar != r_testa.idret then

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (2): '||r_testa.idret,lRaise,false,false);
            end if;

            insert into tmpnaoprocessar values (r_testa.idret);

          else

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - NAO inserindo em tmpnaoprocessar (2): '||r_testa.idret,lRaise,false,false);
            end if;

          end if;

      end loop;

        select count(distinct disbanco2.idret)
          into v_contador
          from disbanco
               inner join recibopaga          on disbanco.k00_numpre  =  recibopaga.k00_numpre
                                             and disbanco.k00_numpar  =  0
               inner join disbanco disbanco2  on disbanco2.k00_numpre =  recibopaga.k00_numnov
                                             and disbanco2.k00_numpar =  0
                                             and disbanco2.codret     =  cod_ret
                                             and disbanco2.classi     is false
                                             and disbanco2.instit     =  iInstitSessao
                                             and disbanco2.idret      <> r_codret.idret
         where disbanco.codret = cod_ret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and disbanco.idret  = r_codret.idret;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - v_contador: '||v_contador,lRaise,false,false);
        end if;

        if v_contador = 1 then
          select distinct
                 disbanco2.idret
            into iIdret
            from disbanco
                 inner join recibopaga         on disbanco.k00_numpre  = recibopaga.k00_numpre
                                              and disbanco.k00_numpar  = 0
                 inner join disbanco disbanco2 on disbanco2.k00_numpre = recibopaga.k00_numnov
                                              and disbanco2.k00_numpar = 0
                                              and disbanco2.codret     = cod_ret
                                              and disbanco2.classi     is false
                                              and disbanco2.instit     = iInstitSessao
                                              and disbanco2.idret      <> r_codret.idret
           where disbanco.codret = cod_ret
             and disbanco.classi is false
             and disbanco.instit = iInstitSessao
             and disbanco.idret  = r_codret.idret;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - inserindo em nao processar (3) - idret1: '||iIdret||' - idret2: '||r_codret.idret,lRaise,false,false);
          end if;

  --        insert into tmpnaoprocessar values (r_codret.idret);
          insert into tmpnaoprocessar values (iIdret);

        elsif v_contador >= 1 then
          return '21 - IDRET ' || r_codret.idret || ' COM MAIS DE UM PAGAMENTO NO MESMO ARQUIVO. CONTATE SUPORTE PARA VERIFICA��ES!';
        end if;

      end if;

    end if;



    -- Validamos o numpre para ver se n�o está duplicado em algum lugar
    -- arrecad(k00_numpre) = recibopaga(k00_numnov)
    -- arrecad(k00_numpre) = recibo(k00_numnov)
    -- caso esteja n�o processa o numpre caindo em inconsistencia
    if exists ( select 1 from arrecad where arrecad.k00_numpre   = r_codret.k00_numpre limit 1)
          and ( exists ( select 1 from recibopaga where recibopaga.k00_numnov = r_codret.k00_numpre limit 1) or
                exists ( select 1 from recibo     where recibo.k00_numnov     = r_codret.k00_numpre limit 1) ) then
       if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (5): '||r_codret.idret,lRaise,false,false);
       end if;
       insert into tmpnaoprocessar values (r_codret.idret);
    end if;

    -- Validacao numpre na ISSVAR com numpar = 0 na DISBANCO para nao processar
    -- porem se o numpre estiver na db_reciboweb (k99_numpre_n) e na issvar (q05_numpre)
    -- significa que esse debito eh oriundo de uma integracao externa. Ex: Gissonline
    if r_codret.k00_numpar = 0
      and exists (select 1 from issvar where q05_numpre = r_codret.k00_numpre)
      and not exists (select 1 from db_reciboweb where k99_numpre_n = r_codret.k00_numpre) then
      if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (6): '||r_codret.idret,lRaise,false,false);
      end if;
      insert into tmpnaoprocessar values (r_codret.idret);
    end if;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    end if;

  end loop;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - inicio separando recibopaga...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  -- acertando recibos (recibopaga) com registros que foram importados divida e outros que nao foram importados, e estava gerando erro, entao a logica abaixo
  -- separa em dois recibos novos os casos
  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.instit,
             disbanco.k00_numpre,
             disbanco.k00_numpar,
             disbanco.vlrpago::numeric(15,2),
             (select round(sum(k00_valor),2)
                from recibopaga
               where k00_numnov = disbanco.k00_numpre) as recibopaga_sum_valor
       from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and k00_numpar = 0
         and exists (select 1 from recibopaga inner join divold on k00_numpre = k10_numpre and k00_numpar = k10_numpar where k00_numnov = disbanco.k00_numpre)
         and (select count(*) from recibopaga where k00_numnov = disbanco.k00_numpre) > 0
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by idret
  loop

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - vlrpago: '||r_codret.vlrpago||' - numpre: '||r_codret.k00_numpre||' - numpar: '||r_codret.k00_numpar,lRaise,false,false);
      end if;

      nSimDivold := 0;
      nNaoDivold := 0;

      nValorSimDivold := 0;
      nValorNaoDivold := 0;

      nTotValorPagoDivold := 0;

      nTotalRecibo       := 0;
      nTotalNovosRecibos := 0;

      perform * from (
      select  recibopaga.k00_numpre as recibopaga_numpre,
              recibopaga.k00_numpar as recibopaga_numpar,
              recibopaga.k00_receit as recibopaga_receit,
              recibopaga.k00_numnov,
              coalesce( (select count(*)
                           from divold
                                inner join divida  on divold.k10_coddiv  = divida.v01_coddiv
                                inner join arrecad on arrecad.k00_numpre = divida.v01_numpre
                                                  and arrecad.k00_numpar = divida.v01_numpar
                                                  and arrecad.k00_valor  > 0
                          where divold.k10_numpre = recibopaga.k00_numpre
                            and divold.k10_numpar = recibopaga.k00_numpar
                        ), 0 ) as divold,
              round(sum(k00_valor),2) as k00_valor
         from disbanco
              inner join recibopaga on disbanco.k00_numpre = recibopaga.k00_numnov
                                   and disbanco.k00_numpar = 0
        where disbanco.idret = r_codret.idret
        group by recibopaga.k00_numpre,
                 recibopaga.k00_numpar,
                 recibopaga.k00_receit,
                 recibopaga.k00_numnov,
                 divold
      ) as x where k00_valor < 0;

      if found then
        insert into tmpnaoprocessar values (r_codret.idret);
        perform fc_debug('  <BaixaBanco>  - idret '||r_codret.idret || ' - insert tmpnaoprocessar',lRaise,false,false);
      else

        for r_testa in
        select  recibopaga.k00_numpre as recibopaga_numpre,
                recibopaga.k00_numpar as recibopaga_numpar,
                recibopaga.k00_receit as recibopaga_receit,
                recibopaga.k00_numnov,
                coalesce( (select count(*)
                             from divold
                                  inner join divida  on divold.k10_coddiv = divida.v01_coddiv
                                  inner join arrecad on arrecad.k00_numpre = divida.v01_numpre
                                                   and arrecad.k00_numpar = divida.v01_numpar
               and arrecad.k00_valor > 0
                           where divold.k10_numpre = recibopaga.k00_numpre
                             and divold.k10_numpar = recibopaga.k00_numpar
--                           and divold.k10_receita = recibopaga.k00_receit
                          ),0) as divold,
                round(sum(k00_valor),2) as k00_valor
           from disbanco
                inner join recibopaga on disbanco.k00_numpre = recibopaga.k00_numnov
                                     and disbanco.k00_numpar = 0
          where disbanco.idret = r_codret.idret
          group by recibopaga.k00_numpre,
                   recibopaga.k00_numpar,
                   recibopaga.k00_receit,
                   recibopaga.k00_numnov,
                   divold
        loop

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - verificando recibopaga - numpre: '||r_testa.recibopaga_numpre||' - numpar: '||r_testa.recibopaga_numpar||' - divold: '||r_testa.divold||' - k00_valor: '||r_testa.k00_valor,lRaise,false,false);
          end if;

          if r_testa.divold > 0 then
            nSimDivold := nSimDivold + 1;
            nValorSimDivold := nValorSimDivold + r_testa.k00_valor;
          else
            nNaoDivold := nNaoDivold + 1;
            nValorNaoDivold := nValorNaoDivold + r_testa.k00_valor;
          end if;
          insert into tmpacerta_recibopaga_unif values (r_testa.recibopaga_numpre, r_testa.recibopaga_numpar, r_testa.recibopaga_receit, r_testa.k00_numnov, case when r_testa.divold > 0 then 1 else 2 end);

        end loop;

      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nSimDivold: '||nSimDivold||' - nNaoDivold: '||nNaoDivold||' - idret: '||r_codret.idret,lRaise,false,false);
      end if;

      if nSimDivold > 0 and nNaoDivold > 0 then

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - vai ser dividido...',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - nSimDivold: '||nSimDivold||' - nNaoDivold: '||nNaoDivold||' - idret: '||r_codret.idret,lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        end if;

        nValorTotDivold := nValorSimDivold + nValorNaoDivold;

        for rContador in select 1 as tipo union select 2 as tipo
          loop

          select nextval('numpref_k03_numpre_seq') into iNumnovDivold;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - inserindo em recibopaga - numnov: '||iNumnovDivold,lRaise,false,false);
            perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
          end if;

          insert into recibopaga
          (
          k00_numcgm,
          k00_dtoper,
          k00_receit,
          k00_hist,
          k00_valor,
          k00_dtvenc,
          k00_numpre,
          k00_numpar,
          k00_numtot,
          k00_numdig,
          k00_conta,
          k00_dtpaga,
          k00_numnov
          )
          select
          k00_numcgm,
          k00_dtoper,
          k00_receit,
          k00_hist,
          k00_valor,
          k00_dtvenc,
          k00_numpre,
          k00_numpar,
          k00_numtot,
          k00_numdig,
          k00_conta,
          k00_dtpaga,
          iNumnovDivold
          from recibopaga
          inner join tmpacerta_recibopaga_unif on
          recibopaga.k00_numpre = tmpacerta_recibopaga_unif.numpre and
          recibopaga.k00_numpar = tmpacerta_recibopaga_unif.numpar and
          recibopaga.k00_receit = tmpacerta_recibopaga_unif.receit and
          recibopaga.k00_numnov = tmpacerta_recibopaga_unif.numpreoriginal
          where tmpacerta_recibopaga_unif.tipo = rContador.tipo;

          insert into db_reciboweb
          (
          k99_numpre,
          k99_numpar,
          k99_numpre_n,
          k99_codbco,
          k99_codage,
          k99_numbco,
          k99_desconto,
          k99_tipo,
          k99_origem
          )
          select
          distinct
          k99_numpre,
          k99_numpar,
          iNumnovDivold,
          k99_codbco,
          k99_codage,
          k99_numbco,
          k99_desconto,
          k99_tipo,
          k99_origem
          from db_reciboweb
          inner join tmpacerta_recibopaga_unif on
          k99_numpre = tmpacerta_recibopaga_unif.numpre and
          k99_numpar = tmpacerta_recibopaga_unif.numpar and
          k99_numpre_n = tmpacerta_recibopaga_unif.numpreoriginal
          where tmpacerta_recibopaga_unif.tipo = rContador.tipo;

          insert into arrehist
          (
          k00_numpre,
          k00_numpar,
          k00_hist,
          k00_dtoper,
          k00_hora,
          k00_id_usuario,
          k00_histtxt,
          k00_limithist,
          k00_idhist
          )
          values
          (
          iNumnovDivold,
          0,
          930,
          current_date,
          to_char(now(), 'HH24:MI'),
          1,
          'criado automaticamente pela divisao automatica dos recibos durante a consistencia da baixa de banco - numpre original: ' || r_testa.k00_numnov,
          null,
          nextval('arrehist_k00_idhist_seq'));

          select nextval('disbanco_idret_seq') into v_nextidret;

          nValorPagoDivold := case when rContador.tipo = 1 then nValorSimDivold else nValorNaoDivold end / nValorTotDivold * r_codret.vlrpago;
          nTotValorPagoDivold := nTotValorPagoDivold + nValorPagoDivold;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - tipo: '||rContador.tipo||' - nValorSimDivold: '||nValorSimDivold||' - nValorNaoDivold: '||nValorNaoDivold||' - nValorTotDivold: '||nValorTotDivold||' - vlrpago: '||r_codret.vlrpago||' - nTotValorPagoDivold: '||nTotValorPagoDivold,lRaise,false,false);
          end if;

          if rContador.tipo = 2 then
            if nTotValorPagoDivold <> r_codret.vlrpago then
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - acertando nValorPagoDivold',lRaise,false,false);
              end if;
              nValorPagoDivold := r_codret.vlrpago - nTotValorPagoDivold;
            end if;
          end if;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - inserindo disbanco - idret: '||v_nextidret||' - vlrpago: '||nValorPagoDivold||' - numnov: '||iNumnovDivold||' - novo idret: '||v_nextidret,lRaise,false,false);
          end if;


          insert into disbanco (k00_numbco,
                                k15_codbco,
                                k15_codage,
                                codret,
                                dtarq,
                                dtpago,
                                vlrpago,
                                vlrjuros,
                                vlrmulta,
                                vlracres,
                                vlrdesco,
                                vlrtot,
                                cedente,
                                vlrcalc,
                                idret,
                                classi,
                                k00_numpre,
                                k00_numpar,
                                convenio,
                                instit )
                         select k00_numbco,
                                k15_codbco,
                                k15_codage,
                                codret,
                                dtarq,
                                dtpago,
                                round(nValorPagoDivold,2),
                                0,
                                0,
                                0,
                                0,
                                round(nValorPagoDivold,2),
                                cedente,
                                round(vlrcalc,2),
                                v_nextidret,
                                false,
                                iNumnovDivold,
                                0,
                                convenio,
                                instit
                           from disbanco
                          where idret = r_codret.idret;

          insert into tmpantesprocessar (idret, vlrpago, v01_seq) values (v_nextidret, nValorPagoDivold, (select nextval('w_divold_seq')) );

          select round(sum(k00_valor),2)
            into nTotalRecibo
            from recibopaga where k00_numnov = iNumnovDivold;

          nTotalNovosRecibos := nTotalNovosRecibos + nTotalRecibo;

        end loop;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - nTotalNovosRecibos: '||nTotalNovosRecibos||' - recibopaga_k00_valor: '||r_codret.recibopaga_sum_valor,lRaise,false,false);
          if round(nTotalNovosRecibos,2) <> round(r_codret.recibopaga_sum_valor,2) then
            return '22 - INCONSISTENCIA AO GERAR NOVOS RECIBOS NA DIVISAO. IDRET: ' || r_codret.idret || ' - NUMPRE RECIBO ORIGINAL: ' || r_codret.k00_numpre;
          end if;
        end if;

        /*delete
          from disbancotxtreg
          where disbancotxtreg.k35_idret = r_codret.idret;*/
        update disbancotxtreg
           set k35_idret = v_nextidret
         where k35_idret = r_codret.idret;

        delete
          from issarqsimplesregdisbanco
         where q44_disbanco = r_codret.idret;

        delete
          from disbanco
         where disbanco.idret = r_codret.idret;

--        return 'parou';
      else

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - NAO vai ser dividido...',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - nSimDivold: '||nSimDivold||' - nNaoDivold: '||nNaoDivold||' - idret: '||r_codret.idret,lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        end if;

      end if;

      delete from tmpacerta_recibopaga_unif;

  end loop;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - fim separando recibopaga...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  select round(sum(vlrpago),2)
    into nTotalDisbancoOriginal
    from tmpdisbanco_inicio_original;

  select round(sum(vlrpago),2)
    into nTotalDisbancoDepois
    from disbanco
   where disbanco.codret = cod_ret
     and disbanco.classi is false
     and disbanco.instit = iInstitSessao;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - nTotalDisbancoOriginal: '||nTotalDisbancoOriginal||' - nTotalDisbancoDepois: '||nTotalDisbancoDepois,lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - inicio verificando se foi importado para divida',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  -- verifica se foi importado para divida, porem somente nos casos de pagamento por carne, ou seja, registros que estejam no arrecad pelo numpre e parcela
  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.instit
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
       /*
         and case when iNumprePagamentoParcial = 0
                  then true
                  else disbanco.k00_numpre > iNumprePagamentoParcial
              end
        */
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by idret
  loop

    -- inicio numpre/numpar (carne)
    for r_idret in
      select distinct
             1 as tipo,
             disbanco.dtarq,
             disbanco.dtpago,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join divold   on divold.k10_numpre = disbanco.k00_numpre
                                and divold.k10_numpar = disbanco.k00_numpar
             inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                and divida.v01_instit = iInstitSessao
             inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                and arrecad.k00_numpar = divida.v01_numpar
        and arrecad.k00_valor > 0
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.k00_numpar > 0
      union
      select distinct
             2 as tipo,
             disbanco.dtarq,
             disbanco.dtpago,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
       from disbanco
             inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre
             inner join divold       on divold.k10_numpre = db_reciboweb.k99_numpre
                                    and divold.k10_numpar = db_reciboweb.k99_numpar
             inner join divida       on divida.v01_coddiv = divold.k10_coddiv
                                    and divida.v01_instit = iInstitSessao
             inner join arrecad      on arrecad.k00_numpre = divida.v01_numpre
                                    and arrecad.k00_numpar = divida.v01_numpar
            and arrecad.k00_valor > 0
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.k00_numpar = 0
       union
      select distinct
             3 as tipo,
             disbanco.dtarq,
             disbanco.dtpago,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join divold   on divold.k10_numpre = disbanco.k00_numpre and disbanco.k00_numpar = 0
             inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                and divida.v01_instit = iInstitSessao
             inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                and arrecad.k00_numpar = divida.v01_numpar
                                and arrecad.k00_valor > 0
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.k00_numpar = 0

    loop

      --
      -- Verificamos se o idret ja nao teve um abatimento lancado.
      --   Quando temos um recibo que teve uma de suas origens(numpre, numpar) importadas para divida / parcelada
      --   antes do processamento do pagamento a baixa os retira do recibopaga para gerar uma diferenca e processa
      --   o pagamento parcial / credito normalmente
      -- Por isso no caso de existir regitros na abatimentodisbanco passamos para a proxima volta do for
      --
      perform *
         from abatimentodisbanco
        where k132_idret = r_codret.idret;

      if found then
        continue;
      end if;


      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        perform fc_debug('  <BaixaBanco>  - divold idret: '||R_IDRET.idret||' - tipo: '||R_IDRET.tipo||' - vlrpago: '||R_IDRET.vlrpago,lRaise,false,false);
      end if;

      v_total1 := 0;
      v_total2 := 0;
      v_diferenca_round := 0;

      -- Verificar com Evandro porque nao Colocar o SUM direto
      for r_divold in

        select distinct
               1 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor
          from disbanco
               inner join divold   on divold.k10_numpre = disbanco.k00_numpre
                                  and divold.k10_numpar = disbanco.k00_numpar
               inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                  and divida.v01_instit = iInstitSessao
               inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                  and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret and r_idret.tipo = 1
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and disbanco.k00_numpar > 0
        union
        select distinct
               2 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor
          from disbanco
               inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre and disbanco.k00_numpar = 0
               inner join divold       on divold.k10_numpre = db_reciboweb.k99_numpre
                                      and divold.k10_numpar = db_reciboweb.k99_numpar
               inner join divida       on divida.v01_coddiv = divold.k10_coddiv
                                      and divida.v01_instit = iInstitSessao
               inner join arrecad      on arrecad.k00_numpre = divida.v01_numpre
                                      and arrecad.k00_numpar = divida.v01_numpar
              and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret and r_idret.tipo = 2
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
         union
        select distinct
               3 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor
          from disbanco
               inner join divold   on divold.k10_numpre = disbanco.k00_numpre and disbanco.k00_numpar = 0
               inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                  and divida.v01_instit = iInstitSessao
               inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                  and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret and r_idret.tipo = 3
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and disbanco.k00_numpar = 0

      loop

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - somando v_total1 - v01_coddiv: '||r_divold.v01_coddiv||' - valor: '||r_divold.v01_valor,lRaise,false,false);
        end if;

        v_total1 := v_total1 + r_divold.v01_valor;

      end loop;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - v_total1: '||v_total1,lRaise,false,false);
      end if;

--      select setval('w_divold_seq',1);

      for r_divold in
        select * from
        (
        select distinct
               1 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor,
               nextval('w_divold_seq') as v01_seq
          from disbanco
               inner join divold   on divold.k10_numpre = disbanco.k00_numpre
                                  and divold.k10_numpar = disbanco.k00_numpar
               inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                  and divida.v01_instit = iInstitSessao
               inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                  and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and r_idret.tipo = 1
           and disbanco.k00_numpar > 0
         union
         select distinct
               2 as tipo,
               v01_coddiv,
               v01_numpre,
               v01_numpar,
               v01_valor,
               nextval('w_divold_seq') as v01_seq
          from (
                 select distinct
                       v01_coddiv,
                       divida.v01_numpre,
                       divida.v01_numpar,
                       divida.v01_valor
                  from disbanco
                       inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre and disbanco.k00_numpar = 0
                       inner join divold   on divold.k10_numpre = db_reciboweb.k99_numpre
                                          and divold.k10_numpar = db_reciboweb.k99_numpar
                       inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                          and divida.v01_instit = iInstitSessao
                       inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                          and arrecad.k00_numpar = divida.v01_numpar
            and arrecad.k00_valor > 0
                 where disbanco.idret  = r_codret.idret
                   and disbanco.classi is false
                   and disbanco.instit = iInstitSessao
                   and r_idret.tipo = 2
              ) as x
        union
         select distinct
               3 as tipo,
               v01_coddiv,
               v01_numpre,
               v01_numpar,
               v01_valor,
               nextval('w_divold_seq') as v01_seq
          from (
                select distinct
                       v01_coddiv,
                       divida.v01_numpre,
                       divida.v01_numpar,
                       divida.v01_valor
                from disbanco
                     inner join divold   on divold.k10_numpre = disbanco.k00_numpre and disbanco.k00_numpar = 0
                     inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                        and divida.v01_instit = iInstitSessao
                     inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                        and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
               where disbanco.idret  = r_codret.idret
                 and disbanco.classi is false
                 and disbanco.instit = iInstitSessao
                 and r_idret.tipo = 3
                 and disbanco.k00_numpar = 0
              ) as x
           ) as x
           order by v01_seq

      loop

        select nextval('disbanco_idret_seq')
          into v_nextidret;

        v_valor           := round(round(r_divold.v01_valor, 2) / v_total1 * round(r_idret.vlrpago, 2), 2);
        v_valor_sem_round := round(r_divold.v01_valor, 2) / v_total1 * round(r_idret.vlrpago, 2);

        v_diferenca_round := v_diferenca_round + (v_valor - v_valor_sem_round);

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo disbanco - processando idret: '||r_codret.idret||' - v01_coddiv: '||r_divold.v01_coddiv||' - valor: '||v_valor,lRaise,false,false);
        end if;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - v_valor: '||v_valor||' - v_valor_sem_round: '||v_valor_sem_round||' - v_diferenca_round: '||v_diferenca_round||' - seq: '||r_divold.v01_seq||' - tipo: '||r_divold.tipo,lRaise,false,false);
        end if;

        insert into disbanco (
          k00_numbco,
          k15_codbco,
          k15_codage,
          codret,
          dtarq,
          dtpago,
          vlrpago,
          vlrjuros,
          vlrmulta,
          vlracres,
          vlrdesco,
          vlrtot,
          cedente,
          vlrcalc,
          idret,
          classi,
          k00_numpre,
          k00_numpar,
          convenio,
          instit
        ) values (
          r_idret.k00_numbco,
          r_idret.k15_codbco,
          r_idret.k15_codage,
          cod_ret,
          r_idret.dtarq,
          r_idret.dtpago,
          v_valor,
          0,
          0,
          0,
          0,
          v_valor,
          '',
          0,
          v_nextidret,
          false,
          r_divold.v01_numpre,
          r_divold.v01_numpar,
          '',
          r_idret.instit
        );

        insert into tmpantesprocessar (idret, vlrpago, v01_seq) values (v_nextidret, v_valor, r_divold.v01_seq);

        v_total2 := v_total2 + v_valor;

      end loop;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - v_total2 antes da diferenca do round: '||v_total2,lRaise,false,false);
      end if;

      if v_diferenca_round <> 0 then
        update tmpantesprocessar set vlrpago = round(vlrpago - v_diferenca_round,2) where v01_seq = (select max(v01_seq) from tmpantesprocessar);
        update disbanco          set vlrpago = round(vlrpago - v_diferenca_round,2) where idret   = (select idret from tmpantesprocessar where v01_seq = (select max(v01_seq) from tmpantesprocessar));
        v_total2 := v_total2 - v_diferenca_round;
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - v_total2 depois da diferenca do round: '||v_total2,lRaise,false,false);
        end if;

      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - v_total2: '||v_total2||' - vlrpago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      if round(v_total2, 2) <> round(r_idret.vlrpago, 2) then
        return '23 - IDRET ' || r_codret.idret || ' INCONSISTENTE AO VINCULAR A DIVIDA ATIVA! CONTATE SUPORTE - VALOR SOMADO: ' || v_total2 || ' - VALOR PAGO: ' || r_idret.vlrpago || '!';
      end if;

      /*delete
        from disbancotxtreg
       where exists(select idret
                      from disbanco
                     where idret  = k35_idret
                       and codret = cod_ret); */
      update disbancotxtreg
         set k35_idret = v_nextidret
       where k35_idret = r_codret.idret;

      --
      -- Deletando da issarqsimplesregdisbanco pois pode o debito
      -- do simples ter sido importado para divida
      --
      delete
        from issarqsimplesregdisbanco
       where q44_disbanco = r_codret.idret;

      delete
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi = false
         and disbanco.idret  = r_codret.idret;

      delete
        from tmpantesprocessar
       where idret = r_codret.idret;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - DELETANDO DISBANCO E ANTESPROCESSAR...',lRaise,false,false);
      end if;

    end loop;
    -- fim numpre/numpar (carne)

  end loop;


  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - fim verificando se foi importado para divida',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - inicio PROCESSANDO REGISTROS...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;


  --------
  -------- PROCESSANDO REGISTROS
  --------

  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.k00_numpre,
             disbanco.k00_numpar,
             disbanco.vlrpago
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
      /*
         and case when iNumprePagamentoParcial = 0
                  then true
                  else disbanco.k00_numpre > iNumprePagamentoParcial
              end
       */
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by disbanco.idret
  loop
    gravaidret := 0;

    -- pelo NUMPRE
    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - iniciando registro disbanco - idret '||r_codret.idret,lRaise,false,false);
    end if;

    -- T24879: Atualizar valor pago de recibo da emissao geral do ISS
    -- Verifica se é recibo da emissao geral do issqn e na recibopaga esta com valor zerado
    -- caso positivo iremos atualizar o valor da recibopaga com o vlrpago da disbanco
    -- e gerar um arrehist para o caso

      select k00_numpre,
             k00_numpar,
             k00_receit,
             k00_hist,
             round(sum(k00_valor),2) as k00_valor
        into rReciboPaga
        from db_reciboweb
             inner join recibopaga  on k00_numnov = k99_numpre_n
       where k99_numpre_n = r_codret.k00_numpre
         and k99_tipo     = 6 -- Emissao Geral de ISSQN
    group by k00_numpre,
             k00_numpar,
             k00_receit,
             k00_hist
      having cast(round(sum(k00_valor),2) as numeric) = cast(0.00 as numeric);

    if found then
      update recibopaga
         set k00_valor  = r_codret.vlrpago
       where k00_numnov = r_codret.k00_numpre
         and k00_numpre = rReciboPaga.k00_numpre
         and k00_numpar = rReciboPaga.k00_numpar
         and k00_receit = rReciboPaga.k00_receit
         and k00_hist   = rReciboPaga.k00_hist;

      -- T24879: gerar arrehist para essa alteracao
      insert
        into arrehist(k00_idhist, k00_numpre, k00_numpar, k00_hist, k00_dtoper, k00_hora, k00_id_usuario, k00_histtxt, k00_limithist)
      values (nextval('arrehist_k00_idhist_seq'),
              rReciboPaga.k00_numpre,
              rReciboPaga.k00_numpar,
              rReciboPaga.k00_hist,
              cast(fc_getsession('DB_datausu') as date),
              to_char(current_timestamp, 'HH24:MI'),
              cast(fc_getsession('DB_id_usuario') as integer),
              'ALTERADO PELO ARQUIVO BANCARIO CODRET='||cast(r_codret.codret as text)||' IDRET='||cast(r_codret.idret as text),
              null);

    end if;

    v_estaemrecibopaga    := false;
    v_estaemrecibo        := false;
    v_estaemarrecadnormal := false;
    v_estaemarrecadunica  := false;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - verificando recibopaga...',lRaise,false,false);
      -- TESTE 1 - RECIBOPAGA
      -- alteracao 2 - sistema deve testar como ja faz na autentica se todos os registros da recibopaga estao na arrecad, e senao tem que dar inconsistencia
    end if;

    for r_idret in

    /**
     * @todo verificar numprebloqpag / alterar disbanco por recibopaga
     */
        select disbanco.k00_numpre as numpre,
               disbanco.k00_numpar as numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               round(sum(recibopaga.k00_valor),2) as k00_valor,
               disbanco.instit
          from disbanco
               inner join recibopaga     on disbanco.k00_numpre       = recibopaga.k00_numnov
               left  join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                        and numprebloqpag.ar22_numpar = disbanco.k00_numpar
         where disbanco.idret  = r_codret.idret
           and disbanco.classi is false
       /*
           and case when iNumprePagamentoParcial = 0
                    then true
                    else disbanco.k00_numpre > iNumprePagamentoParcial
                end
        */
           and disbanco.instit = iInstitSessao
           and recibopaga.k00_conta = 0
           and numprebloqpag.ar22_numpre is null
      group by disbanco.k00_numpre,
               disbanco.k00_numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               disbanco.instit
    loop

      v_estaemrecibopaga := true;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - recibopaga - numpre '||r_idret.numpre||' numpar '||r_idret.numpar,lRaise,false,false);
      end if;

      -- Verifica se algum numpre do recibo nao esta no arrecad
      -- caso nao esteja passa para o proximo e deixa inconsistente
      select coalesce(count(*),0)
        into iQtdeParcelasAberto
        from  (select distinct
                      arrecad.k00_numpre,
                      arrecad.k00_numpar
                 from recibopaga
                      inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                        and arrecad.k00_numpar = recibopaga.k00_numpar
                where k00_numnov = r_codret.k00_numpre ) as x;

      select coalesce(count(*),0)
        into iQtdeParcelasRecibo
        from (select distinct
                     recibopaga.k00_numpre,
                     recibopaga.k00_numpar
                from recibopaga
               where k00_numnov = r_codret.k00_numpre ) as x;

      if iQtdeParcelasAberto <> iQtdeParcelasRecibo then
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -   nao encontrou arrecad... gravaidret: '||gravaidret,lRaise,false,false);
        end if;
        continue;
      else
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -   encontrou em arrecad... gravaidret: '||gravaidret,lRaise,false,false);
        end if;
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - entrou no update vlrcalc (1)...',lRaise,false,false);
      end if;

      -- Acerta vlrcalc
      update disbanco
         set vlrcalc = round((select (substr(fc_calcula,15,13)::float8+
                                substr(fc_calcula,28,13)::float8+
                                substr(fc_calcula,41,13)::float8-
                                substr(fc_calcula,54,13)::float8) as utotal
                          from (select fc_calcula(k00_numpre,k00_numpar,0,dtpago,dtpago,extract(year from dtpago)::integer)
                                  from disbanco
                                 where idret = r_codret.idret
                                   and codret = r_codret.codret
                                   and instit = iInstitSessao
                          ) as x
                       ),2)
       where idret  = r_codret.idret
         and codret = r_codret.codret
         and instit = r_idret.instit;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - saiu no update vlrcalc (1)...',lRaise,false,false);
      end if;

      gravaidret := r_codret.idret;
      retorno    := true;

      -- INSERE NO ARREPAGA OS PAGAMENTOS
      insert into arrepaga ( k00_numcgm,
                             k00_dtoper,
                             k00_receit,
                             k00_hist,
                             k00_valor,
                             k00_dtvenc,
                             k00_numpre,
                             k00_numpar,
                             k00_numtot,
                             k00_numdig,
                             k00_conta,
                             k00_dtpaga
                           ) select k00_numcgm,
                                    datausu,
                                k00_receit,
                                k00_hist,
                                round(sum(k00_valor),2) as k00_valor,
                                datausu,
                                k00_numpre,
                                k00_numpar,
                                k00_numtot,
                                k00_numdig,
                                conta,
                                datausu
                               from ( select k00_numcgm,
                                       k00_receit,
                                       case
                                         when exists ( select 1
                                                         from tmplista_unica
                                                        where idret = r_idret.idret ) then 990
                                         else k00_hist
                                       end as k00_hist,
                                       round((k00_valor / r_idret.k00_valor) * r_idret.vlrpago, 2) as k00_valor,
                                       k00_numpre,
                                       k00_numpar,
                                       k00_numtot,
                                       k00_numdig
                                  from recibopaga
                                 where k00_numnov = r_idret.numpre
                                    ) as x
                           group by k00_numcgm,
                          k00_receit,
                          k00_hist,
                          k00_numpre,
                          k00_numpar,
                          k00_numtot,
                          k00_numdig
                           order by k00_numpre,
                                    k00_numpar,
                                    k00_receit;



-- ALTERA SITUACAO DO ARREPAGA
      update recibopaga
         set k00_conta = conta,
             k00_dtpaga = datausu
       where k00_numnov = r_idret.numpre;

      v_contador := 0;
      v_somador  := 0;
      v_contagem := 0;

      for q_disrec in
          select k00_numpre,
                 k00_numpar,
                 k00_receit,
                 sum(round((k00_valor / r_idret.k00_valor) * r_idret.vlrpago, 2))
            from recibopaga
           where k00_numnov = r_idret.numpre
        group by k00_numpre,
                 k00_numpar,
                 k00_receit
          having sum(round(k00_valor,2)) <> 0.00::float8
      loop
        v_contagem := v_contagem + 1;
      end loop;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - v_contagem: '||v_contagem,lRaise,false,false);
      end if;

      for q_disrec in
          select k00_numpre,
                 k00_numpar,
                 k00_receit,
                 sum( round((k00_valor / r_idret.k00_valor) * r_idret.vlrpago, 2) )::numeric(15,2)
            from recibopaga
           where k00_numnov = r_idret.numpre
        group by k00_numpre,
                 k00_numpar,
                 k00_receit
          having sum(round(k00_valor,2)) <> 0.00::float8
      loop

        v_contador := v_contador + 1;
-- INSERE NO ARRECANT
        insert into arrecant  (
          k00_numpre,
          k00_numpar,
          k00_numcgm,
          k00_dtoper,
          k00_receit,
          k00_hist  ,
          k00_valor ,
          k00_dtvenc,
          k00_numtot,
          k00_numdig,
          k00_tipo  ,
          k00_tipojm
        ) select arrecad.k00_numpre,
                 arrecad.k00_numpar,
                 arrecad.k00_numcgm,
                 arrecad.k00_dtoper,
                 arrecad.k00_receit,
                 arrecad.k00_hist  ,
                 arrecad.k00_valor ,
                 arrecad.k00_dtvenc,
                 arrecad.k00_numtot,
                 arrecad.k00_numdig,
                 arrecad.k00_tipo  ,
                 arrecad.k00_tipojm
            from arrecad
                 inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre
                                       and arreinstit.k00_instit = iInstitSessao
           where arrecad.k00_numpre = q_disrec.k00_numpre
             and arrecad.k00_numpar = q_disrec.k00_numpar;
-- DELETE DO ARRECAD
        delete
          from arrecad
         using arreinstit
         where arreinstit.k00_numpre = arrecad.k00_numpre
           and arreinstit.k00_instit = iInstitSessao
           and arrecad.k00_numpre = q_disrec.k00_numpre
           and arrecad.k00_numpar = q_disrec.k00_numpar;

       -- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
        select arreidret.k00_numpre
          into _testeidret
          from arreidret
         where arreidret.k00_numpre = q_disrec.k00_numpre
           and arreidret.k00_numpar = q_disrec.k00_numpar
           and arreidret.idret      = r_idret.idret
           and arreidret.k00_instit = iInstitSessao;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo arreidret - numpre: '||q_disrec.k00_numpre||' - numpar: '||q_disrec.k00_numpar||' - idret: '||r_idret.idret,lRaise,false,false);
        end if;

        if _testeidret is null then
          insert into arreidret (
            k00_numpre,
            k00_numpar,
            idret,
            k00_instit
          ) values (
            q_disrec.k00_numpre,
            q_disrec.k00_numpar,
            r_idret.idret,
            r_idret.instit
          );
        end if;

        if q_disrec.sum != 0 then
          if autentsn is false then
-- GRAVA DISREC DAS RECEITAS PARA A CLASSIFICACAO
            v_somador := v_somador + q_disrec.sum;

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - inserindo disrec - receita: '||q_disrec.k00_receit||' - valor: '||q_disrec.sum||' - contador: '||v_contador||' - somador: '||v_somador||' - contagem: '||v_contagem,lRaise,false,false);
            end if;

            v_valor := q_disrec.sum;

            if v_contador = v_contagem then
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - vlrpago: '||r_idret.vlrpago||' - v_somador: '||v_somador,lRaise,false,false);
              end if;
              v_valor := v_valor + round(r_idret.vlrpago - v_somador,2);
            end if;

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - into disrec 1',lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - Verifica Receita',lRaise,false,false);
            end if;


            lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - Retorno verifica Receita: '||lVerificaReceita,lRaise,false,false);
            end if;

            if lVerificaReceita is false then
              return '24 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (1).';
            end if;

            perform * from disrec where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;

            if not found then

              v_valor := round(v_valor,2);

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -    inserindo disrec 1 - valor: '||v_valor||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;

              if v_valor > 0 then

                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - Inserindo na DISREC. valor: '||v_valor,lRaise,false,false);
                end if;

                insert into disrec (
                  codcla,
                  k00_receit,
                  vlrrec,
                  idret,
                  instit
                ) values (
                  vcodcla,
                  q_disrec.k00_receit,
                  v_valor,
                  r_idret.idret,
                  r_idret.instit
                );
              end if;

            else

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -    update disrec 1 - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;

              update disrec set vlrrec = vlrrec + round(v_valor,2)
              where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
            end if;

          end if;
        end if;

      end loop;

    end loop;

    if v_estaemrecibopaga is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em recibopaga...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em recibopaga...',lRaise,false,false);
      end if;
    end if;

-- arquivo recibo

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - verificando recibo...',lRaise,false,false);
      -- TESTE 2 -RECIBO AVULSO
    end if;

    for r_idret in
      select distinct
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join recibo       on disbanco.k00_numpre       = recibo.k00_numpre
             left join numprebloqpag on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                    and numprebloqpag.ar22_numpar = disbanco.k00_numpar
       where disbanco.idret  = r_codret.idret
         and disbanco.classi = false
         and disbanco.instit = iInstitSessao
         and numprebloqpag.ar22_sequencial is null
    loop

      v_estaemrecibo := true;

-- Verifica se algum numpre do recibo já esta pago
-- caso positivo passa para o proximo e deixa inconsistente
      perform recibo.k00_numpre
         from recibo
              inner join arrepaga  on arrepaga.k00_numpre = recibo.k00_numpre
                                  and arrepaga.k00_numpar = recibo.k00_numpar
        where recibo.k00_numpre = r_idret.numpre;

      if found then
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - recibo ja esta pago... gravaidret: '||gravaidret,lRaise,false,false);
        end if;
        continue;
      end if;

      -- Acerta vlrcalc
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - entrou no update vlrcalc (1)...',lRaise,false,false);
      end if;

      -- Acerta vlrcalc
      update disbanco
         set vlrcalc = round((select (substr(fc_calcula,15,13)::float8+
                                substr(fc_calcula,28,13)::float8+
                                substr(fc_calcula,41,13)::float8-
                                substr(fc_calcula,54,13)::float8) as utotal
                          from (select fc_calcula(k00_numpre,k00_numpar,0,dtpago,dtpago,extract(year from dtpago)::integer)
                                  from disbanco
                                 where idret = r_codret.idret
                                   and codret = r_codret.codret
                                   and instit = iInstitSessao
                          ) as x
                       ),2)
       where idret  = r_codret.idret
         and codret = r_codret.codret
         and instit = r_idret.instit;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - saiu no update vlrcalc (1)...',lRaise,false,false);
      end if;


      gravaidret := r_codret.idret;
      retorno    := true;

      -- INSERE NO ARREPAGA OS PAGAMENTOS
      select round(sum(k00_valor),2)
        into valorrecibo
        from recibo
       where k00_numpre = r_idret.numpre;

-- comentado por evandro em 24/05/2007, pois teste abaixo da regra de 3 utiliza essa variavel e tem que ser o valor do ecibo para funcionar
--        VALORRECIBO = R_IDRET.VLRPAGO;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - xxx - numpre: '||r_idret.numpre||' - valorrecibo: '||valorrecibo||' - vlrpago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      if valorrecibo = 0 then
        valorrecibo := r_idret.vlrpago;
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - recibo... vlrpago: '||r_idret.vlrpago||' - valor recibo: '||valorrecibo,lRaise,false,false);
      end if;

      insert into arrepaga (
        k00_numcgm,
        k00_dtoper,
        k00_receit,
        k00_hist,
        k00_valor,
        k00_dtvenc,
        k00_numpre,
        k00_numpar,
        k00_numtot,
        k00_numdig,
        k00_conta,
        k00_dtpaga
      ) select recibo.k00_numcgm,
               datausu,
               recibo.k00_receit,
               recibo.k00_hist,
               round((recibo.k00_valor / valorrecibo) * r_idret.vlrpago, 2),
               datausu,
               recibo.k00_numpre,
               recibo.k00_numpar,
               recibo.k00_numtot,
               recibo.k00_numdig,
               conta,
               datausu
          from recibo
               inner join arreinstit  on arreinstit.k00_numpre = recibo.k00_numpre
                                     and arreinstit.k00_instit = iInstitSessao
         where recibo.k00_numpre = r_idret.numpre;

      -- Verifica se o Total Pago é diferente do que foi Classificado (inserido na Arrepaga)
      v_diferenca := round(r_idret.vlrpago - (select round(sum(k00_valor),2) from arrepaga where k00_numpre = r_idret.numpre), 2);
      if v_diferenca > 0 then
        -- Altera maior receita com a diferenca encontrada
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - recibo com diferenca de '||v_diferenca||' no classificado do idret '||r_idret.idret||' (numpre '||r_idret.numpre||' numpar '||r_idret.numpar||')',lRaise,false,false);
        end if;

        update arrepaga
           set k00_valor = k00_valor + v_diferenca
         where k00_numpre = r_idret.numpre
           and k00_receit = (select max(k00_receit) from arrepaga where k00_numpre = r_idret.numpre);
      end if;
      v_diferenca := 0; -- Seta valor anterior para garantir

-- ALTERA SITUACAO DO ARREPAGA

      for q_disrec in

          select arrepaga.k00_numpre,
                 arrepaga.k00_numpar,
                 arrepaga.k00_receit,
                 sum(round(arrepaga.k00_valor, 2))
            from arrepaga
                 inner join disbanco on disbanco.k00_numpre = arrepaga.k00_numpre
           where arrepaga.k00_numpre = r_idret.numpre
             and disbanco.idret      = r_codret.idret
             and disbanco.instit     = iInstitSessao
        group by arrepaga.k00_numpre,
                 arrepaga.k00_numpar,
                 arrepaga.k00_receit
      loop
-- INSERE NO ARRECANT
-- DELETE DO ARRECAD
-- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
        select arreidret.k00_numpre
          into _testeidret
          from arreidret
         where arreidret.k00_numpre = q_disrec.k00_numpre
           and arreidret.k00_numpar = q_disrec.k00_numpar
           and arreidret.idret      = r_idret.idret
           and arreidret.k00_instit = iInstitSessao;

        if _testeidret is null then
          insert into arreidret (
            k00_numpre,
            k00_numpar,
            idret,
            k00_instit
          ) values (
            q_disrec.k00_numpre,
            q_disrec.k00_numpar,
            r_idret.idret,
            r_idret.instit
          );
        end if;

        if q_disrec.sum != 0 then
          if autentsn is false then
-- GRAVA DISREC DAS RECEITAS PARA A CLASSIFICACAO
            lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);
            if lVerificaReceita is false then
              return '25 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (2).';
            end if;

            perform *
               from disrec
              where disrec.codcla     = vcodcla
                and disrec.k00_receit = q_disrec.k00_receit
                and disrec.idret      = r_idret.idret
                and disrec.instit     = r_idret.instit;
            if not found then
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - into disrec 2 - valor: '||q_disrec.sum||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;


              if round(q_disrec.sum,2) > 0 then

                insert into disrec (
                  codcla,
                  k00_receit,
                  vlrrec,
                  idret,
                  instit
                ) values (
                  vcodcla,
                  q_disrec.k00_receit,
                  round(q_disrec.sum,2),
                  r_idret.idret,
                  r_idret.instit
                );

             end if;

            else
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -    update disrec 2 - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;
              update disrec set vlrrec = vlrrec + round(v_valor,2)
              where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
            end if;
            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - into disrec 3',lRaise,false,false);
            end if;
          end if;
        end if;

      end loop;

    end loop;

    if v_estaemrecibo is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em recibo...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em recibo...',lRaise,false,false);
      end if;
    end if;

    ----
    ---- PROCURANDO ARRECAD
    ----

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - verificando arrecad...',lRaise,false,false);
      -- TESTE 3 - ARRECAD
    end if;

    for r_idret in
      select distinct
             1 as tipo,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join arrecad      on arrecad.k00_numpre = disbanco.k00_numpre
                                    and arrecad.k00_numpar = disbanco.k00_numpar
             inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre
                                    and arreinstit.k00_instit = iInstitSessao
             left join arrepaga      on arrepaga.k00_numpre = arrecad.k00_numpre
                                    and arrepaga.k00_numpar = arrecad.k00_numpar
                                    and arrepaga.k00_receit = arrecad.k00_receit
             left join numprebloqpag on numprebloqpag.ar22_numpre = arrecad.k00_numpre
                                    and numprebloqpag.ar22_numpar = arrecad.k00_numpar
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and arrepaga.k00_numpre is null
         and numprebloqpag.ar22_sequencial is null
      union
      select distinct
             2 as tipo,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join arrecad      on arrecad.k00_numpre = disbanco.k00_numpre
                                    and disbanco.k00_numpar = 0
             inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre
                                    and arreinstit.k00_instit = iInstitSessao
             left join arrepaga      on arrepaga.k00_numpre = arrecad.k00_numpre
                                    and arrepaga.k00_numpar = arrecad.k00_numpar
                                    and arrepaga.k00_receit = arrecad.k00_receit
             left join numprebloqpag on numprebloqpag.ar22_numpre = arrecad.k00_numpre
                                    and numprebloqpag.ar22_numpar = arrecad.k00_numpar
       where disbanco.idret = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and arrepaga.k00_numpre is null
         and numprebloqpag.ar22_sequencial is null
    loop

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - ###### - tipo: '||r_idret.tipo,lRaise,false,false);
      end if;

      retorno := true;

      if r_idret.numpar = 0 then
        v_estaemarrecadunica  := true;
      else
        v_estaemarrecadnormal := true;
      end if;
-- INSERE NO DISBANCO O VALOR CORRETO DO PAGAMENTO

--if R_IDRET.numpre = 11479037 and R_IDRET.numpar = 2 THEN
-- lRaise = true;
--ELSE
--  lRaise = false;
--END IF;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - codret : '||r_codret.codret||'-idret : '||r_codret.idret,lRaise,false,false);
      end if;

      --select sum(arrecad.k00_valor)
      --  into x_totreg
      --  from arrecad
      --       inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre
      --                             and arreinstit.k00_instit = iInstitSessao
      --       inner join disbanco    on disbanco.k00_numpre = arrecad.k00_numpre
      --                             and disbanco.k00_numpar = arrecad.k00_numpar
      --                             and disbanco.instit = iInstitSessao
      -- where arrecad.k00_numpre  = r_idret.numpre
      --   and arrecad.k00_numpar  = r_idret.numpar;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - arrecad - numpre: '||r_idret.numpre||' - numpar: '||r_idret.numpar||' - tot: '||x_totreg||' - pago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      --if ( ( x_totreg = 0 or x_totreg is null ) and r_idret.numpar != 0 ) then
      --  update disbanco
      --     set vlrcalc = vlrtot
      --   where idret  = r_codret.idret
      --     and codret = r_codret.codret
      --     and instit = r_idret.instit;
      --else

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - entrou no update vlrcalc...',lRaise,false,false);
        end if;

        -- Acerta vlrcalc
        update disbanco
           set vlrcalc = round((select (substr(fc_calcula,15,13)::float8+
                                  substr(fc_calcula,28,13)::float8+
                                  substr(fc_calcula,41,13)::float8-
                                  substr(fc_calcula,54,13)::float8) as utotal
                            from (select fc_calcula(k00_numpre,k00_numpar,0,dtpago,dtpago,extract(year from dtpago)::integer)
                                    from disbanco
                                   where idret = r_codret.idret
                                     and codret = r_codret.codret
                                     and instit = iInstitSessao
                            ) as x
                         ),2)
         where idret  = r_codret.idret
           and codret = r_codret.codret
           and instit = r_idret.instit;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - saiu no update vlrcalc...',lRaise,false,false);
        end if;

      --end if;

      if not r_idret.numpre is null then

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - aaaaaaaaaaaaaaaaaaaaaaaa',lRaise,false,false);
        end if;

        if r_idret.numpar != 0 then

          -- TESTE 3.1 - ARRECAD COM PARCELA PREENCHIDA

          valortotal := r_idret.vlrpago+r_idret.vlracres-r_idret.vlrdesco;
          valorjuros := r_idret.vlrjuros;
          valormulta := r_idret.vlrmulta;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - valortotal: '||valortotal,lRaise,false,false);
          end if;

          select round(sum(arrecad.k00_valor),2) as k00_vlrtot
            into nVlrTfr
            from arrecad
                 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
           where arrecad.k00_numpre    = r_idret.numpre
             and arrecad.k00_numpar    = r_idret.numpar
             and arreinstit.k00_instit = r_idret.instit;

          primeirarec := 0;
          valorlanc   := 0;
          valorlancj  := 0;
          valorlancm  := 0;
          for r_receitas in
              select k00_numcgm,
                     k00_numtot,
                     k00_numdig,
                     k00_receit,
                     round(sum(k00_valor),2)::float8 as k00_valor,
                     k02_recjur,
                     k02_recmul
                from arrecad
                     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                     inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit
                     inner join tabrecjm   on tabrec.k02_codjm      = tabrecjm.k02_codjm
               where arrecad.k00_numpre    = r_idret.numpre
                 and arrecad.k00_numpar    = r_idret.numpar
                 and arreinstit.k00_instit = r_idret.instit
            group by k00_numcgm,
                     k00_numtot,
                     k00_numdig,
                     k00_receit,
                     k02_recjur,
                     k02_recmul
          loop

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - inicio do for...',lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
            end if;

            if r_receitas.k00_valor = 0 then
              fracao := 1::float8;
            else
              fracao := round((r_receitas.k00_valor*100)::float8/nVlrTfr,8)::float8/100::float8;
            end if;

            nVlrRec := to_char(round(valortotal * fracao,2),'9999999999999.99')::float8;

            -- juros
            nVlrRecj := to_char(round(valorjuros * fracao,2),'9999999999999.99')::float8;

            -- multa
            nVlrRecm := to_char(round(valormulta * fracao,2),'9999999999999.99')::float8;

            if lRaise then
              perform fc_debug('  <BaixaBanco>  - JUROS : '||nVlrRecj||' RECEITA : '||r_receitas.k02_recjur,lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - MULTA : '||nVlrRecm||' RECEITA : '||r_receitas.k02_recmul,lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - VALOR : '||nVlrRec ||' RECEITA : '||r_receitas.k00_receit,lRaise,false,false);
            end if;

            if r_receitas.k02_recjur = r_receitas.k02_recmul then
              nVlrRecj := nVlrRecj + nVlrRecm;
              nVlrRecm := 0;
            end if;

            if r_receitas.k02_recjur is null then
              nVlrRec  := nVlrRecm + nVlrRecj;
              nVlrRecj := 0;
              nVlrRecm := 0;
            end if;

            gravaidret := r_codret.idret;

            --
            -- Inserindo o valor da receita
            --
            if nVlrRec != 0 then
              if primeirarec = 0 then
                primeirarec := r_receitas.k00_receit;
              end if;
              valorlanc := round(valorlanc + nVlrRec,2)::float8;
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - valorlanc: '||valorlanc,lRaise,false,false);
              end if;

              insert into arrepaga  (
                k00_numcgm,
                k00_dtoper,
                k00_receit,
                k00_hist  ,
                k00_valor ,
                k00_dtvenc,
                k00_numpre,
                k00_numpar,
                k00_numtot,
                k00_numdig,
                k00_conta ,
                k00_dtpaga
              ) values (
                r_receitas.k00_numcgm,
                datausu,
                r_receitas.k00_receit  ,
                991,
                nVlrRec,
                datausu ,
                r_idret.numpre,
                r_idret.numpar ,
                r_receitas.k00_numtot ,
                r_receitas.k00_numdig ,
                conta,
                datausu
              );
            end if;

            --
            -- Inserindo o valor do juros
            --
            if round(nVlrRecj,2)::float8 != 0 then
              if primeirarecj = 0 then
                primeirarecj := r_receitas.k02_recjur;
              end if;
              valorlancj := round(valorlancj + nVlrRecj,2)::float8;

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - Valor do juros '||nVlrRecj,lRaise,false,false);
                perform fc_debug('  <BaixaBanco>  - valorlancj: '||valorlancj,lRaise,false,false);
              end if;

              insert into arrepaga (
                k00_numcgm,
                k00_dtoper,
                k00_receit,
                k00_hist  ,
                k00_valor ,
                k00_dtvenc,
                k00_numpre,
                k00_numpar,
                k00_numtot,
                k00_numdig,
                k00_conta ,
                k00_dtpaga
              ) values (
                r_receitas.k00_numcgm,
                datausu,
                r_receitas.k02_recjur ,
                991,
                round(nVlrRecj,2)::float8,
                datausu,
                r_idret.numpre,
                r_idret.numpar ,
                r_receitas.k00_numtot ,
                r_receitas.k00_numdig  ,
                conta,
                datausu
              );
            end if;

            --
            -- Inserindo o valor da multa
            --
            if round(nVlrRecm,2)::float8 != 0 then

              if lRaise then
                perform fc_debug('  <BaixaBanco>  - Valor da multa : '||round(nVlrRecm,2),lRaise,false,false);
              end if;

              if primeirarecm = 0 then
                primeirarecm := r_receitas.k02_recmul;
              end if;
              valorlancm := round(valorlancm + nVlrRecm,2)::float8;

              insert into arrepaga (
                k00_numcgm,
                k00_dtoper,
                k00_receit,
                k00_hist  ,
                k00_valor ,
                k00_dtvenc,
                k00_numpre,
                k00_numpar,
                k00_numtot,
                k00_numdig,
                k00_conta ,
                k00_dtpaga
              ) values (
                r_receitas.k00_numcgm,
                datausu,
                r_receitas.k02_recmul ,
                991 ,
                round(nVlrRecm,2)::float8,
                datausu  ,
                r_idret.numpre,
                r_idret.numpar ,
                r_receitas.k00_numtot ,
                r_receitas.k00_numdig  ,
                conta,
                datausu
              );
            else
              if lRaise then
                perform fc_debug('  <BaixaBanco>  - nao processou multa - valor da multa : '||round(nVlrRecm,2),lRaise,false,false);
              end if;
            end if;

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - final do for...',lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
            end if;

          end loop;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
            perform fc_debug('  <BaixaBanco>  - fora do for...',lRaise,false,false);
            perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
          end if;

          valorlanc := round(valortotal - (valorlanc),2)::float8;

          if valorlanc != 0 then
            select oid
              into oidrec
              from arrepaga
             where k00_numpre = r_idret.numpre
               and k00_numpar = r_idret.numpar
               and k00_receit = primeirarec;

            update arrepaga
               set k00_valor = round(k00_valor + valorlanc,2)::float8
             where oid = oidrec ;
          end if;

          valorlancj := round(valorjuros - (valorlancj),2)::float8;
          if valorlancj != 0 then

            if lRaise then
              perform fc_debug('  <BaixaBanco>  - Somando juros na receira principal : '||valorlancj,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = r_idret.numpre
               and k00_numpar = r_idret.numpar
               and k00_receit = primeirarecj;

            -- comentei para teste

            update arrepaga
               set k00_valor = round(k00_valor + valorlancj,2)::float8
             where oid = oidrec;

          end if;

          valorlancm := round(valormulta - (valorlancm),2)::float8;
          if valorlancm != 0 then
            select oid
              into oidrec
              from arrepaga
             where k00_numpre = r_idret.numpre
               and k00_numpar = r_idret.numpar
               and k00_receit = primeirarecm;

            update arrepaga
               set k00_valor = round(k00_valor + valorlancm,2)::float8
             where oid = oidrec;

          end if;

          for q_disrec in
              select k00_receit,
                     round(sum(k00_valor),2) as sum
                from arrepaga
               where k00_numpre = r_idret.numpre
                 and k00_numpar = r_idret.numpar
                 and k00_dtoper = datausu
            group by k00_receit
          loop
            if q_disrec.sum != 0 then
              if autentsn is false then

                lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);
                if lVerificaReceita is false then
                  return '26 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (3).';
                end if;

                perform *
                   from disrec
                  where disrec.codcla = vcodcla
                    and disrec.k00_receit = q_disrec.k00_receit
                    and disrec.idret      = r_idret.idret
                    and disrec.instit     = r_idret.instit;
                if not found then
                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  - into disrec 4 - valor: '||q_disrec.sum||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;

                  if round(q_disrec.sum,2) > 0 then

                    insert into disrec (
                      codcla,
                      k00_receit,
                      vlrrec,
                      idret,
                      instit
                    ) values (
                      vcodcla,
                      q_disrec.k00_receit,
                      round(q_disrec.sum,2),
                      r_idret.idret,
                      r_idret.instit
                    );

                  end if;

                else

                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  -    update disrec 4 - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;

                  update disrec
                     set vlrrec = vlrrec + round(v_valor,2)
                   where disrec.codcla     = vcodcla
                     and disrec.k00_receit = q_disrec.k00_receit
                     and disrec.idret      = r_idret.idret
                     and disrec.instit     = r_idret.instit;

                end if;
                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - into disrec 5',lRaise,false,false);
                end if;
              end if;
            end if;
          end loop;

          insert into arrecant (
            k00_numcgm,
            k00_dtoper,
            k00_receit,
            k00_hist  ,
            k00_valor ,
            k00_dtvenc,
            k00_numpre,
            k00_numpar,
            k00_numtot,
            k00_numdig,
            k00_tipo  ,
            k00_tipojm
          ) select arrecad.k00_numcgm,
                   arrecad.k00_dtoper,
                   arrecad.k00_receit,
                   arrecad.k00_hist  ,
                   arrecad.k00_valor ,
                   arrecad.k00_dtvenc,
                   arrecad.k00_numpre,
                   arrecad.k00_numpar,
                   arrecad.k00_numtot,
                   arrecad.k00_numdig,
                   arrecad.k00_tipo  ,
                   arrecad.k00_tipojm
              from arrecad
                   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
             where arrecad.k00_numpre = r_idret.numpre
               and arrecad.k00_numpar = r_idret.numpar
               and arreinstit.k00_instit = r_idret.instit;

          delete
            from arrecad
           using arreinstit
           where arrecad.k00_numpre    = arreinstit.k00_numpre
             and arrecad.k00_numpre    = r_idret.numpre
             and arrecad.k00_numpar    = r_idret.numpar
             and arreinstit.k00_instit = r_idret.instit;

-- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
          select arreidret.k00_numpre
            into _testeidret
            from arreidret
           where arreidret.k00_numpre = r_idret.numpre
             and arreidret.k00_numpar = r_idret.numpar
             and arreidret.idret      = r_idret.idret
             and arreidret.k00_instit = r_idret.instit;

          if _testeidret is null then
            insert into arreidret (
              k00_numpre,
              k00_numpar,
              idret,
              k00_instit
            ) values (
              r_idret.numpre,
              r_idret.numpar,
              r_idret.idret,
              r_idret.instit
            );
          end if;

        else
          -- PARCELA UNICA
          -- TESTE 3.2 - ARRECAD COM PARCELA UNICA

          valortotal := r_idret.vlrpago+r_idret.vlracres-r_idret.vlrdesco;
          valorjuros := r_idret.vlrjuros;
          valormulta := r_idret.vlrmulta;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  -  unica - vlrtot '||valortotal||' - numpre: '||r_idret.numpre,lRaise,false,false);
          end if;


          select round(sum(arrecad.k00_valor),2) as k00_vlrtot
            into nVlrTfr
            from arrecad
                 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
           where arrecad.k00_numpre    = r_idret.numpre
             and arreinstit.k00_instit = r_idret.instit;

          primeirarec := 0;
          valorlanc   := 0;
          valorlancj  := 0;
          valorlancm  := 0;

          for r_idunica in
            select distinct
                   arrecad.k00_numpre as numpre,
                   arrecad.k00_numpar as numpar
              from arrecad
                   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
             where arrecad.k00_numpre    = r_idret.numpre
               and arreinstit.k00_instit = r_idret.instit
          loop

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - dentro do for do arrecad - parcela: '||r_idunica.numpar,lRaise,false,false);
            end if;

            for r_receitas in
                select k00_numcgm,
                       k00_numtot,
                       k00_numdig,
                       k00_receit,
                       k00_tipo,
                       round(sum(k00_valor),2)::float8 as k00_valor,
                       k02_recjur,
                       k02_recmul
                  from arrecad
                       inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                       inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit
                       inner join tabrecjm   on tabrec.k02_codjm      = tabrecjm.k02_codjm
                 where arrecad.k00_numpre    = r_idunica.numpre
                   and arrecad.k00_numpar    = r_idunica.numpar
                   and arreinstit.k00_instit = r_idret.instit
              group by k00_numcgm,
                       k00_numtot,
                       k00_numdig,
                       k00_receit,
                       k00_tipo,
                       k02_recjur,
                       k02_recmul
            loop

              --
              -- Modificação realizada devido ao erro gerado na tarefa 32607
              -- Motivo do erro:
              -- Foi pego o valor de 72.83 para um numpre de ISSQN Var, quando o arquivo do banco retornou, o  estava com valor zero no arrecad
              -- O que ocasionava erro nas linhas abaixo pois a variavel nVlrTfr que e resultado do somatorio do valor do  na tabela arrecad e
              -- utilizado para a divisão do valor da receita abaixo, estava igual a zero.
              --
              if r_receitas.k00_tipo = 3 and nVlrTfr = 0 then
                fracao := 100;
              else
                fracao := round((r_receitas.k00_valor*100)::float8/nVlrTfr,8)::float8/100::float8;
              end if;
              --
              -- fim da modificacao
              --


              nVlrRec := round(to_char(round(valortotal * fracao,2),'9999999999999.99')::float8,2)::float8;

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -  rec '||r_receitas.k00_receit||' nVlrRec '||nVlrRec,lRaise,false,false);
              end if;
-- juros
              nVlrRecj := round(to_char(round(valorjuros * fracao,2),'9999999999999.99')::float8,2)::float8;

-- multa
              nVlrRecm := round(to_char(round(valormulta * fracao,2),'9999999999999.99')::float8,2)::float8;

              if r_receitas.k02_recjur = r_receitas.k02_recmul then
                nVlrRecj := nVlrRecj + nVlrRecm;
                nVlrRecm := 0;
              end if;

              if r_receitas.k02_recjur is null then
                nVlrRec  := nVlrRecm + nVlrRecj;
                nVlrRecj := 0;
                nVlrRecm := 0;
              end if;

              gravaidret := r_codret.idret;

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - nVlrRec: '||nVlrRec,lRaise,false,false);
              end if;

              if nVlrRec != 0 then
                if primeirarec = 0 then
                  primeirarec := r_receitas.k00_receit;
                end if;
                primeiranumpre := r_idunica.numpre;
                primeiranumpar := r_idunica.numpar;
                valorlanc      := round(valorlanc + nVlrRec,2)::float8;

                insert into arrepaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist,
                  k00_valor,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta,
                  k00_dtpaga
                ) values (
                  r_receitas.k00_numcgm,
                  datausu,
                  r_receitas.k00_receit  ,
                  990 ,
                  round(nVlrRec,2)::float8,
                  datausu  ,
                  r_idunica.numpre,
                  r_idunica.numpar ,
                  r_receitas.k00_numtot,
                  r_receitas.k00_numdig  ,
                  conta,
                  datausu
                );
              end if;

              if round(nVlrRecj,2)::float8 != 0 then
                if primeirarecj = 0 then
                  primeirarecj := r_receitas.k02_recjur;
                end if;
                primeiranumpre := r_idunica.numpre;
                primeiranumpar := r_idunica.numpar;
                valorlancj     := round(valorlancj + nVlrRecj,2)::float8;
                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - juros '||nVlrRecj,lRaise,false,false);
                end if;

                insert into arrepaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist  ,
                  k00_valor ,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta ,
                  k00_dtpaga
                ) values (
                  r_receitas.k00_numcgm,
                  datausu,
                  r_receitas.k02_recjur ,
                  990,
                  round(nVlrRecj,2)::float8,
                  datausu,
                  r_idunica.numpre,
                  r_idunica.numpar ,
                  r_receitas.k00_numtot ,
                  r_receitas.k00_numdig  ,
                  conta,
                  datausu
                );
              end if;

              if round(nVlrRecm,2)::float8 != 0 then
                if primeirarecm = 0 then
                  primeirarecm := r_receitas.k02_recmul;
                end if;
                primeiranumpre := r_idunica.numpre;
                primeiranumpar := r_idunica.numpar;
                valorlancm     := round(valorlancm + nVlrRecm,2)::float8;

                insert into arrepaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist  ,
                  k00_valor ,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta ,
                  k00_dtpaga
                ) values (
                  r_receitas.k00_numcgm,
                  datausu,
                  r_receitas.k02_recmul ,
                  990 ,
                  round(nVlrRecm,2)::float8,
                  datausu ,
                  r_idunica.numpre,
                  r_idunica.numpar ,
                  r_receitas.k00_numtot ,
                  r_receitas.k00_numdig  ,
                  conta,
                  datausu
                );
              end if;

            end loop;

            insert into arrecant (
              k00_numcgm,
              k00_dtoper,
              k00_receit,
              k00_hist  ,
              k00_valor ,
              k00_dtvenc,
              k00_numpre,
              k00_numpar,
              k00_numtot,
              k00_numdig,
              k00_tipo  ,
              k00_tipojm
            ) select arrecad.k00_numcgm,
                     arrecad.k00_dtoper,
                     arrecad.k00_receit,
                     arrecad.k00_hist  ,
                     arrecad.k00_valor ,
                     arrecad.k00_dtvenc,
                     arrecad.k00_numpre,
                     arrecad.k00_numpar,
                     arrecad.k00_numtot,
                     arrecad.k00_numdig,
                     arrecad.k00_tipo  ,
                     arrecad.k00_tipojm
                from arrecad
                     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
               where arrecad.k00_numpre    = r_idunica.numpre
                 and arrecad.k00_numpar    = r_idunica.numpar
                 and arreinstit.k00_instit = r_idret.instit;

            delete
              from arrecad
             using arreinstit
             where arrecad.k00_numpre    = arreinstit.k00_numpre
               and arrecad.k00_numpre    = r_idunica.numpre
               and arrecad.k00_numpar    = r_idunica.numpar
               and arreinstit.k00_instit = r_idret.instit;
-- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
            select arreidret.k00_numpre
              into _testeidret
              from arreidret
             where arreidret.k00_numpre = r_idunica.numpre
               and arreidret.k00_numpar = r_idunica.numpar
               and arreidret.idret      = r_idret.idret
               and arreidret.k00_instit = r_idret.instit;

            if _testeidret is null then
              insert into arreidret (
                k00_numpre,
                k00_numpar,
                idret,
                k00_instit
              ) values (
                r_idunica.numpre,
                r_idunica.numpar,
                r_idret.idret,
                r_idret.instit
              );
            end if;

          end loop;

          valorlanc  := round(valortotal - (valorlanc),2)::float8;
          valorlancj := round(valorjuros - (valorlancj),2)::float8;
          valorlancm := round(valormulta - (valorlancm),2)::float8;

          IF VALORLANC != 0  THEN

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  -  acerta 1 -- '||valorlanc,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = primeiranumpre
               and k00_numpar = primeiranumpar
               and k00_receit = primeirarec;

            update arrepaga
               set k00_valor = round(k00_valor + valorlanc,2)::float8
             where oid = oidrec ;
          end if;

          if valorlancj != 0 then

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  -  acerta 2 -- '||valorlancj,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = primeiranumpre
               and k00_numpar = primeiranumpar
               and k00_receit = primeirarecj;

            update arrepaga
               set k00_valor = round(k00_valor + valorlancj,2)::float8
             where oid = oidrec;

          end if;

          if valorlancm != 0 then

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  -  acerta 3  -- '||valorlancm,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = primeiranumpre
               and k00_numpar = primeiranumpar
               and k00_receit = primeirarecm;

            update arrepaga
               set k00_valor = round(k00_valor + valorlancm,2)::float8
             where oid = oidrec;

          end if;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - antes for disrec - datausu: '||datausu,lRaise,false,false);
          end if;

          for q_disrec in
              select k00_receit,
                     round(sum(k00_valor),2) as sum
                from arrepaga
               where k00_numpre = r_idret.numpre
--                and k00_numpar = r_idret.numpar
                 and k00_dtoper = datausu
            group by k00_receit
          loop
            if q_disrec.sum != 0 then
              if autentsn is false then
                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - into disrec 6',lRaise,false,false);
                end if;

                lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);
                if lVerificaReceita is false then
                  return '27 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (4).';
                end if;

                perform * from disrec where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
                if not found then
                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  -    inserindo disrec 6 - valor: '||q_disrec.sum||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;

                  if round(q_disrec.sum,2) > 0 then

                    insert into disrec (
                      codcla,
                      k00_receit,
                      vlrrec,
                      idret,
                      instit
                    ) values (
                      vcodcla,
                      q_disrec.k00_receit,
                      round(q_disrec.sum,2),
                      r_idret.idret,
                      r_idret.instit
                    );

                  end if;

                else
                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  -    update disrec 6 - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;
                  update disrec set vlrrec = vlrrec + round(v_valor,2)
                  where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
                end if;
              end if;
            end if;
            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - durante for disrec',lRaise,false,false);
            end if;
          end loop;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - depois for disrec',lRaise,false,false);
          end if;

        end if;

      end if;

    end loop;

    if v_estaemarrecadnormal is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em arrecad normal...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em arrecad normal...',lRaise,false,false);
      end if;
    end if;

    if v_estaemarrecadunica is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em arrecad unica...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em arrecad unica...',lRaise,false,false);
      end if;
    end if;

-- pelo numpre do arrecad
    if gravaidret != 0 then
      if autentsn is false then
        insert into disclaret (
          codcla,
          codret
        ) values (
          vcodcla,
          r_codret.idret
        );
      end if;

      select ar22_sequencial
        into nBloqueado
        from numprebloqpag
             inner join disbanco on disbanco.k00_numpre = numprebloqpag.ar22_numpre
                                and disbanco.k00_numpar = numprebloqpag.ar22_numpar
        where disbanco.idret = r_codret.idret;

      if nBloqueado is not null and nBloqueado > 0 then
        lClassi = false;
      else
        lClassi = true;
      end if;

      /* Comentado pois essa validacao deve ser realizada no inicio do processamento
         e adicionado registro na tabela temporaria "tmpnaoprocessar"
      perform *
         from issvar
              inner join disbanco on disbanco.k00_numpre = issvar.q05_numpre
        where disbanco.idret = r_codret.idret
          and disbanco.k00_numpar = 0 ;
      if found then
        lClassi = false;
      else
        lClassi = true;
      end if;*/

      if lRaise is true then
        if lClassi is true then
          perform fc_debug('  <BaixaBanco>  -  3 - Debito nao Bloqueado ',lRaise,false,false);
        else
          perform fc_debug('  <BaixaBanco>  -  4 - Debito Bloqueado '||r_codret.idret,lRaise,false,false);
        end if;
      end if;

      update disbanco
         set classi = lClassi
       where idret = r_codret.idret;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - classi is false',lRaise,false,false);
      end if;
    end if;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  - finalizando registro disbanco - idret '||R_CODRET.IDRET,lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    end if;

  end loop;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - fim PROCESSANDO REGISTROS...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  select sum(round(tmpantesprocessar.vlrpago,2))
    into v_total1
    from tmpantesprocessar
         inner join disbanco on tmpantesprocessar.idret = disbanco.idret
   where disbanco.classi is true;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  - ===============',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - VCODCLA: '||VCODCLA,lRaise,false,false);
  end if;

  if autentsn is false then

    select sum(round(disrec.vlrrec,2))
      into v_total2
      from disrec
     where disrec.codcla = VCODCLA;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  |1| v_total1 (soma disbanco.vlrpago): '||v_total1||' - v_total2 (soma disrec.vlrrec): '||v_total2,lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
    end if;

    perform distinct
            disbanco.idret,
            disrec.idret
       from tmpantesprocessar
            inner join disbanco  on disbanco.idret = tmpantesprocessar.idret
                                and disbanco.classi is true
            left  join disrec    on disrec.idret = disbanco.idret
      where disrec.idret is null;

    if found and autentsn is false then
      return '28 - REGISTROS CLASSIFICADOS SEM DISREC';
    end if;

    v_diferenca = ( v_total1 - v_total2 );

    if cast(round(v_diferenca,2) as numeric) <> cast(round(0,2) as numeric) then

      if lRaise is true then
        perform fc_debug('============================',lRaise,false,false);
        perform fc_debug('<BaixaBanco> - Executar Acerto',lRaise,false,false);
        perform fc_debug('<BaixaBanco> - CodRet: '||cod_ret,lRaise,false,false);
        perform fc_debug('============================',lRaise,false,false);
      end if;

      for rAcertoDiferenca in  select idret,
                                      vlrpago as valor_disbanco,
                                      ( select sum(vlrrec)
                                          from disrec
                                         where disrec.idret = disbanco.idret) as valor_disrec
                                 from disbanco
                                where codret = cod_ret
                                  and cast(round(vlrpago,2) as numeric) <> cast(round((select sum(vlrrec)
                                                                                        from disrec
                                                                                       where disrec.idret = disbanco.idret),2) as numeric)
      loop

        nVlrDiferencaDisrec := ( rAcertoDiferenca.valor_disbanco - rAcertoDiferenca.valor_disrec );

        if lRaise is true then
          perform fc_debug('  <BaixaBanco> - Acerto de diferenca disrec | idret : '||rAcertoDiferenca.idret,lRaise,false,false);
          perform fc_debug('  <BaixaBanco> - valor disbanco : '||rAcertoDiferenca.valor_disbanco           ,lRaise,false,false);
          perform fc_debug('  <BaixaBanco> - valor disrec : '||rAcertoDiferenca.valor_disrec               ,lRaise,false,false);
        end if;

        update disrec
           set vlrrec = ( vlrrec + nVlrDiferencaDisrec )
         where idret  = rAcertoDiferenca.idret
           and codcla = VCODCLA
           and k00_receit = (select k00_receit
                               from disrec
                              where idret = rAcertoDiferenca.idret
                              order by vlrrec
                               desc limit 1);

      end loop;

      select sum(round(disrec.vlrrec,2))
        into v_total2
        from disrec
       where disrec.codcla /* = vcodcla;*/
       in (select codigo_classificacao
               from tmp_classificaoesexecutadas);

       perform fc_debug('  <BaixaBanco> - v_total2 ( soma disrec ) : ' ||v_total2, lRaise, false, false);
    end if;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  |2| v_total1 (soma disbanco.vlrpago): '||v_total1||' - v_total2 (soma disrec.vlrrec): '||v_total2,lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
    end if;

    if v_total1 <> v_total2 then

      return '29 - INCONSISTENCIA NOS VALORES PROCESSADOS DIFERENCA TOTAL DE '||(v_total1-v_total2);

    end if;

  end if;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  - FIM DO PROCESSAMENTO... ',lRaise,false,true);
  end if;

  if retorno = false then
    return '30 - NAO EXISTEM DEBITOS PENDENTES PARA ESTE ARQUIVO';
  else
    return '1 - PROCESSO CONCLUIDO COM SUCESSO ';
  end if;

end;

$$ language 'plpgsql';drop function fc_calculoiptu_araruama_2011(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);
create or replace function fc_calculoiptu_araruama_2011(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula       alias   for $1;
   iAnousu          alias   for $2;
   lGerafinanc      alias   for $3;
   lAtualizaParcela alias   for $4;
   lNovonumpre      alias   for $5;
   lCalculogeral    alias   for $6;
   lDemonstrativo   alias   for $7;
   iParcelaini      alias   for $8;
   iParcelafim      alias   for $9;

   iIdbql           integer default 0;
   iNumcgm          integer default 0;
   iCodcli          integer default 0;
   iCodisen         integer default 0;
   iTipois          integer default 0;
   iParcelas        integer default 0;
   iNumconstr       integer default 0;
   iCodErro         integer default 0;
   iCaracterLote    integer default 0;

   dDatabaixa       date;

   nValorUFISA      numeric default 0;
   nValorMinimo     numeric default 0;
   nAreal           numeric default 0;
   nAreac           numeric default 0;
   nTotarea         numeric default 0;
   nFracao          numeric default 0;
   nFracaolote      numeric default 0;
   nAliquota        numeric default 0;
   nIsenaliq        numeric default 0;
   nArealo          numeric default 0;
   nVvc             numeric(15,2) default 0;
   nVvt             numeric(15,2) default 0;
   nVv              numeric(15,2) default 0;
   nViptu           numeric(15,2) default 0;

   tRetorno         text default '';
   tDemo            text default '';

   bFinanceiro      boolean;
   bDadosIptu       boolean;
   lErro            boolean;
   lIsentaxas       boolean;
   lTempagamento    boolean;
   lEmpagamento     boolean;
   bTaxasCalculadas boolean;
   lRaise           boolean default false; -- true para habilitar raise na funcao principal

   rCfiptu          record;

begin
 /**
  *  Retorna o valor do inflator UFISA
  */

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);
  lRaise    := true;

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || lTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || lEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || lIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);

  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  perform fc_putsession('anousu_calculo', iAnousu::varchar);

  select fc_get_valor_ufisa()
    into nValorUFISA;

  perform fc_debug('VALOR DO INFLATOR(UFISA) : '|| nValorUFISA, lRaise);

  /* GUARDA OS PARAMETROS DO CALCULO */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  update tmpdadosiptu set matric = iMatricula;

  /* CALCULA VALOR DO TERRENO */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_araruama_2011  IDBQL : '||iIdbql||' -- FRACAO DO LOTE '||nFracaolote||' -- DEMO '||tRetorno||' -- ERRO '||lErro||' ', lRaise);

  select rnvvt,
         rnarea,
         rtdemo,
         rtmsgerro,
         rberro
    into nVvt,
         nAreac,
         tDemo,
         tRetorno,
         lErro
    from fc_iptu_calculavvt_araruama_2011(iIdbql, iAnousu, nFracaolote, lDemonstrativo, lRaise);

  perform fc_debug('RETORNO fc_iptu_calculavvt_araruama_2011 -->>> VVT : '||nVvt||' -- AREA CONTRUIDA '||nAreac||' --  RETORNO '||tRetorno||' -- ERRO '||lErro,lRaise);

  /* CALCULA VALOR DA CONSTRUCAO */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_araruama_2011  MATRICULA '||iMatricula||' -- ANOUSU '||iAnousu||' -- DEMO '||lDemonstrativo||' -- ERRO '||lRaise, lRaise);

  select rnvvc,
         rntotarea,
         rinumconstr,
         rtdemo,
         rtmsgerro,
         rberro
    into nVvc,
         nTotarea,
         iNumconstr,
         tDemo,
         tRetorno,
         lErro
    from fc_iptu_calculavvc_araruama_2011(iMatricula,iAnousu,lDemonstrativo,lRaise);

  perform fc_debug('RETORNO fc_iptu_calculavvc_araruama_2011 -->>> VVC : '||nVvc||' -- AREA TOTAL : '||nTotarea||' --  NUMERO DE CONTRUCOES : '||iNumconstr||' -- RETORNO : '||tRetorno||' -- ERRO : '||lErro, lRaise);

  if nVvc is null or nVvc = 0 and iNumconstr <> 0 then
     select fc_iptu_geterro(22, '') into tRetorno;
     return tRetorno;
  end if;
  /**
   *  Calcula valor venal do imovel
   */
  nVv    := nVvc + nVvt;

  /* BUSCA A ALIQUOTA  */
  -- so executar se nao for isento
  if iNumconstr is not null and iNumconstr > 0 then
      select fc_iptu_getaliquota_araruama_2011(iMatricula, iAnousu, iIdbql, iNumcgm, nVv, true,  lRaise) into nAliquota;
  else
      select fc_iptu_getaliquota_araruama_2011(iMatricula, iAnousu, iIdbql, iNumcgm, nVv, false, lRaise) into nAliquota;
  end if;

  if not found or nAliquota = 0 then
     select fc_iptu_geterro(13, '') into tRetorno;
     return tRetorno;
  end if;
-- return nAliquota::text;
/*--------- CALCULA O VALOR DO IPTU  ----------- */

  nViptu := nVv * ( nAliquota / 100 );

 -- seleciona a caracteristica do lote do Grupo 2

 select j35_caract
   into iCaracterLote
   from iptubase
        inner join carlote   on  j35_idbql   =  j01_idbql
        inner join caracter  on  j31_codigo  =  j35_caract
  where j01_matric = iMatricula
    and j31_grupo = 2;

  /**
   * Caso a caracteristica do lote do grupo 4 for 30115
   * o Valor do iptu vai ser 75% do valor do inflator
   * E seta a aliquota = 75%
   *
   * Em outras caracteristicas o valor minimo n�o pode ser menor que o valor
   * do inflator. Por causa disso é estipulado um valor minimo.
   */
  if iCaracterLote = 30115 then
    update tmpdadosiptu set aliq = nValorUFISA;
--    nViptu := nValorUFISA * 0.75;
    nViptu := nValorUFISA;
  else

    if nViptu < nValorUFISA then
      nViptu := nValorUFISA;
    end if;
  end if;


/*---------------------------------------------------------*/
  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;
  if not found or iParcelas = 0 then
     select fc_iptu_geterro(14, '') into tRetorno;
     return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas;

/* CALCULA AS TAXAS */
  select db21_codcli
    into iCodcli
    from db_config
   where prefeitura is true;

  perform fc_debug('PARAMETROS fc_iptu_calculataxas  ANOUSU '||iAnousu||' -- CODCLI '||iCodcli, lRaise);

  select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lRaise)
    into bTaxasCalculadas;

  perform fc_debug('RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - '||bTaxasCalculadas, lRaise);

/* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lRaise )
    into tDemo;

/* GERA FINANCEIRO */
  if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo

    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lRaise)
      into bDadosIptu;

      if lGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lRaise)
          into bFinanceiro;
      end if;

  else
     return tDemo;
  end if;

  if lDemonstrativo is false then
     update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$  language 'plpgsql';create or replace function fc_iptu_taxacoletalixo_araruama_2011(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;

    nValTaxa       numeric(15,2) default 0;
    iGrupo         integer       default 0;
    iMatricula     integer       default 0;
    sSqlBase       text          default '';
    iValorUfisa    numeric       default 0;
    rSqlBase       record;
    sSetor         varchar(4);
    iAreaPrograma  integer       default 0;
    nFator         numeric       default 0.00;
    sSqlTmp        text          default '';
begin
  select fc_get_valor_ufisa() into iValorUfisa;
  /**
   * Valida qual setor / area programa
   */
  select substr(trim(j34_setor),1,2)::integer, j34_setor
    into iAreaPrograma,sSetor
    from lote
   inner join tmpdadostaxa on lote.j34_idbql = tmpdadostaxa.idbql;

  /**
   * Pega dados da matricula da tabela tempor�ria
   */
    select matric
      into iMatricula
      from tmpdadostaxa ;

    if not found then
      perform fc_debug('Nenhuma matricula selecionada',lRaise);
      return false;
    else

      sSqlBase:= '
                     select j31_grupo,
                            j35_caract,
                            j01_matric
                       from iptubase
                            inner join carlote  on j35_idbql  = j01_idbql
                            inner join caracter on j31_codigo = j35_caract
                      where j01_matric = '||iMatricula||'
                        and (j31_grupo = 1 or
                             (    j31_grupo  = 2
                              and j35_caract = 30115)
                            );';
      /**
       * Percorre daddos do sql
       */
      for rSqlBase in execute sSqlBase loop

        if rSqlBase.j31_grupo = 1 then

          if rSqlBase.j35_caract = 1 then

            if iAreaPrograma in (1,2,3,4,6,7,8,9,10) then
              nFator := nFator + 0.30;
            elsif (iAreaPrograma = 5)                then
              nFator := nFator + 0.50;
            elsif (iAreaPrograma in (11,12))         then
              nFator := nFator + 0.20;
            end if;
          elsif  rSqlBase.j35_caract = 2 then

            if iAreaPrograma in (1,2,3,4,6,7,8,9,10) then
              nFator := nFator + 0.70;
            elsif   iAreaPrograma = 5                then
              nFator := nFator + 1.00;
            elsif iAreaPrograma in (11,12)           then
              nFator := nFator + 0.30;
            end if;
          elsif  rSqlBase.j35_caract = 3 then

            if iAreaPrograma in (1,2,3,4,6,7,8,9,10) then
              nFator := nFator + 0.30;
            elsif iAreaPrograma = 5                  then
              nFator := nFator + 0.50;
            elsif iAreaPrograma in (11,12)           then
              nFator := nFator + 0.20;
            end if;
          elsif  rSqlBase.j35_caract = 4 then

            if iAreaPrograma in (1,2,3,4,6,7,8,9,10) then
              nFator := nFator + 0.70;
            elsif iAreaPrograma = 5                  then
              nFator := nFator + 1.00;
            elsif iAreaPrograma in (11,12)           then
              nFator := nFator + 0.30;
            end if;
          end if;
        elsif rSqlBase.j31_grupo = 2 then
          nFator := 0.5855;
        end if;
      end loop;

      nValTaxa := iValorUfisa * nFator;
      perform fc_debug('Valor taxa: '||nValTaxa||'  - Fator :'||nFator, lRaise);

      insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

      if iPercIsen > 0 then
         nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
      end if;
      sSqlTmp := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';

      perform fc_debug('Inserindo em tabela temporaria '||sSqlTmp, lRaise);

      execute sSqlTmp;

    end if;
     return true;

end;
$$  language 'plpgsql';drop function fc_iptu_taxaexoficio_araruama_2011(integer,numeric,integer,numeric,numeric,boolean);
create or replace function fc_iptu_taxaexoficio_araruama_2011(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare
  iReceita       alias for $1;
  iAliquota      alias for $2;
  iHistCalc      alias for $3;
  iPercIsen      alias for $4;
  nValpar        alias for $5;
  lRaise         alias for $6;

  nValTaxa                  numeric(15,2) default 0;
  nValorBase     numeric(15,2) default 0;
  iNparc         integer       default 0;
  iMatricula     integer       default 0;
  tSql           text          default '';
  rSql           record;
  iGrupo         integer       default 0;
  iCaract        integer       default 0;
  nValorTaxa     float         default 0;
  nFatorUfisa    float         default 0;
  nFator         float         default 0;
  iAnoCalculo    integer;

begin

	select anousu
	  into iAnoCalculo
	  from tmpdadostaxa;

	
  select fc_get_valor_ufisa(iAnoCalculo)
    into nFatorUfisa;
  
  select matric
    into iMatricula
    from tmpdadostaxa;

  for rSql in

      select j31_grupo,
             j48_caract,
             j48_matric,
             j39_area,
             j71_valor
        from iptuconstr 
             inner join carconstr on j48_matric = j39_matric 
                                 and j48_idcons = j39_idcons 
             inner join caracter  on j48_caract = j31_codigo  
             left  join carvalor  on j71_caract = j31_codigo
                                 and j71_anousu = iAnoCalculo 

       where j39_matric = iMatricula
  loop

    iGrupo  := rSql.j31_grupo;
    iCaract := rSql.j48_caract;

    if iGrupo = 4 then 

      if iCaract in (14,30) then
        nValTaxa := nValTaxa + (rSql.j39_area * rSql.j71_valor);
      end if;
    end if;
  end loop;



  nValTaxa := nValTaxa * 0.01;

  if(nValTaxa <= 0 or nValTaxa is null) then
    return false;
  end if;

  insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValTaxa);

  perform fc_debug('insert into tmptaxapercisen values ('||iReceita||', '||iPercIsen||', 0, '||nValTaxa||')', lRaise);

  if iPercIsen > 0 then
    nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
  end if;

  insert into tmprecval values (iReceita, nValTaxa, iHistCalc, true);


  perform fc_debug('insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true);', lRaise);

  return true;

end;
$$  language 'plpgsql';create or replace function fc_iptu_taxamanutredeeletric_araruama_2011(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;

    nValTaxa       numeric(15,2) default 0;
    nValorBase     numeric(15,2) default 0;
    bPredial       boolean       default false;
    iNparc         integer       default 0;
    tSql           text          default '';
    iGrupo         integer       default 0;
    iCaracterisca  integer       default 0;
    nUfisa         numeric(15,2) default 0;
begin

  perform fc_debug(' CALCULANDO TAXA DE MANUTEN��O ELETRICA ... ', lRaise);
  perform fc_debug(' receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistCalc||' raise - '||lRaise||' PercIsen - '||iPercIsen, lRaise);

  perform fc_debug('SELECIONA A CARACTERISTICA E GRUPO ...', lRaise);

	select j31_grupo,
	       j35_caract
	  into iGrupo,
	       iCaracterisca
	  from tmpdadostaxa
	       inner join carlote  on j35_idbql  = idbql
	       inner join caracter on j31_codigo = j35_caract
	 where j31_grupo = 1;

	if not found then

	  return false;
	end if;

  perform fc_debug('SELECIONA VALOR DA UFISA ...', lRaise);

  select *
    into nUfisa
    from fc_get_valor_ufisa();


  if iGrupo = 1 then

    if iCaracterisca in (1,3) then
      nValTaxa := nUfisa * 0.16;
    end if;
  end if;

  if nValTaxa <> 0 then

    insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

    if iPercIsen > 0 then
	    nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
	  end if;
	 /**
	  * Insere na tabela tempor�ria
	  */
    insert into tmprecval values (iReceita,nValTaxa,iHistCalc,true);

	  if lRaise then
	    perform fc_debug('SQL : '||tSql, lRaise);
	  end if;
	end if;
  return true;
end;
$$  language 'plpgsql';-- create type tp_iptu_calculavvc as (rnVvc       numeric(15,2),
--                                    rnTotarea   numeric,
--                                    riNumconstr integer,
--                                    rtDemo      text,
--                                    rtMsgerro   text,
--                                    rbErro      boolean,
--                                    riCodErro   integer,
--                                    rtErro      text
--                                   );
create or replace function fc_iptu_calculavvc_araruama_2011(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as
$$
declare

    iMatricula     alias for $1;
    iAnousu        alias for $2;
    bMostrademo    alias for $3;
    lRaise         alias for $4;

    nAreaconstr    numeric default 0;
    nValorConstr   numeric default 0;
    nValor         numeric default 0;
    nVlrM2         numeric default 0;
    iNumerocontr   integer default 0;
    iAuxMatric     integer default 0;

    tSql           text    default '';
    bAtualiza      boolean default true;
    rConstr        record;

    rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE;

begin
-- SEPARAR PARA TRATAR MELHOR OS ERROS, TIRAR O INNER COM CARVALOR PQ PODE HAVER CONSTRUCAO SEM VALOR LANCADO
    perform fc_debug('INICIANDO CALCULO VVC ...');

    rtp_iptu_calculavvc.rnVvc       := 0;
    rtp_iptu_calculavvc.rnTotarea   := 0;
    rtp_iptu_calculavvc.riNumconstr := 0;
    rtp_iptu_calculavvc.rtDemo      := '';
    rtp_iptu_calculavvc.rtMsgerro   := 'Retorno ok' ;
    rtp_iptu_calculavvc.rbErro      := 'f';
    rtp_iptu_calculavvc.riCodErro   := 0;
    rtp_iptu_calculavvc.rtErro      := '';

    tSql := ' select distinct
                     j11_matric,
                     j11_idcons,
                     j11_vlrcons,
                     j39_idcons,
                     j39_area::numeric
                from iptucalcpadraoconstr
                     inner join iptucalcpadrao  on iptucalcpadrao.j10_sequencial = iptucalcpadraoconstr.j11_iptucalcpadrao
                                               and iptucalcpadrao.j10_anousu     = '|| iAnousu ||'
                     inner join iptuconstr      on iptuconstr.j39_matric         = iptucalcpadraoconstr.j11_matric
                                               and iptuconstr.j39_idcons         = iptucalcpadraoconstr.j11_idcons
               where iptuconstr.j39_dtdemo  is null
                 and iptucalcpadraoconstr.j11_matric = '||iMatricula;

    perform fc_debug(tSql, lRaise);
    for rConstr in execute tSql loop

         nValorConstr := rConstr.j11_vlrcons::numeric;
         nValor       := nValor + nValorConstr;
         iNumerocontr := iNumerocontr + 1;
         nAreaconstr  := nAreaconstr + rConstr.j39_area;

         if nValorConstr = 0 or rConstr.j39_area = 0 then
           nVlrM2       := 0;
         else
           nVlrM2       := nValorConstr / rConstr.j39_area;
         end if;

         insert into tmpiptucale (anousu , matric    , idcons            , areaed          , vm2   , pontos,valor)
                          values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, nVlrM2, 0     ,nValorConstr);

         if bAtualiza then
            update tmpdadosiptu set predial = true;
            bAtualiza = false;
         end if;
    end loop;

    rtp_iptu_calculavvc.rnVvc       := nValor::numeric;
    rtp_iptu_calculavvc.rnTotarea   := nAreaconstr::numeric;
    rtp_iptu_calculavvc.riNumconstr := iNumerocontr;
    rtp_iptu_calculavvc.rtDemo      := '';
    rtp_iptu_calculavvc.rbErro      := 'f';
    update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc;
    return rtp_iptu_calculavvc;

end;
$$  language 'plpgsql';--drop function if exists fc_iptu_calculavvt_araruama_2011(integer,numeric,boolean,boolean);
--drop function if exists fc_iptu_calculavvt_araruama_2011(integer,integer,numeric,boolean,boolean);

--drop type if exists tp_iptu_calculavvt;

-- create type tp_iptu_calculavvt as (rnVvt     numeric(15,2),
--                                    rnAreaTotalC    numeric,
--                                    rnArea    numeric,
--                                    rnTestada   numeric,
--                                    rtDemo    text,
--                                    rtMsgerro text,
--                                    rbErro    boolean,
--                                    riCoderro integer,
--                                    rtErro    text);

create or replace function fc_iptu_calculavvt_araruama_2011(integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$
declare

    iIdbql        alias   for   $1;
    iAnousu       alias   for   $2;
    nFracao       alias   for   $3;
    bMostrademo   alias   for   $4;
    lRaise        alias   for   $5;

    rnAreaTotLote numeric;
    rnArealote    numeric;
    nValor        numeric;
    iMatricula    numeric;
    rtp_iptu_calculavvt tp_iptu_calculavvt%ROWTYPE;

begin

    rtp_iptu_calculavvt.rnVvt        := 0;
    rtp_iptu_calculavvt.rnAreaTotalC := 0;
    rtp_iptu_calculavvt.rnArea       := 0;
    rtp_iptu_calculavvt.rnTestada    := 0;
    rtp_iptu_calculavvt.rtDemo       := '';
    rtp_iptu_calculavvt.rtMsgerro    := '';
    rtp_iptu_calculavvt.rbErro       := 'f';
    rtp_iptu_calculavvt.riCoderro    := 0;
    rtp_iptu_calculavvt.rtErro       := '';

    perform fc_debug('INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...');
   /**
    * Pega matricula da tabela temporaria
    */
    select matric
      into iMatricula
      from tmpdadosiptu;

    select j34_area,
           j10_vlrter
      into rnArealote,
           nValor
      from iptubase
           inner join iptucalcpadrao on j01_matric = j10_matric
                                    and j10_anousu = iAnousu
           inner join lote           on j34_idbql  = j01_idbql
     where j01_matric = iMatricula;

    if not found then

      rtp_iptu_calculavvt.rnVvt     := 0;
      rtp_iptu_calculavvt.rnArea    := 0;
      rtp_iptu_calculavvt.rtDemo    := '';
      rtp_iptu_calculavvt.rtMsgerro := ' Valor m2 do terreno nao encontrado';
      rtp_iptu_calculavvt.rbErro    := 't';
      return rtp_iptu_calculavvt;
    end if;

    rnAreaTotLote                 := rnArealote * (nFracao / 100);
    rtp_iptu_calculavvt.rnArea    := rnAreaTotLote;
    rtp_iptu_calculavvt.rnVvt     := nValor;
    rtp_iptu_calculavvt.rtDemo    := '';
    rtp_iptu_calculavvt.rtMsgerro := '';
    rtp_iptu_calculavvt.rbErro    := 'f';

    update tmpdadosiptu set vvt = nValor, vm2t=(rnArealote/nValor), areat=rnAreaTotLote;

    return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';/**
 * @version $Revision: 1.7 $
 */
 drop function if exists fc_get_valor_ufisa(integer);
 drop function if exists fc_get_valor_ufisa();
 drop function if exists fc_iptu_getaliquota_araruama_2011(integer,integer,integer,numeric,boolean,boolean);
 drop function if exists fc_iptu_getaliquota_araruama_2011(integer,integer,integer,integer,numeric,boolean,boolean);

 /**
  * Fun��o que pega os dados da ufisa
  */
create or replace function fc_get_valor_ufisa(integer) returns numeric as
$$
  declare

    iAnoBase alias for $1;

    nValorUfisa numeric default 1;

  begin

    select j18_vlrref
      into nValorUfisa
      from cfiptu
     where j18_anousu = iAnoBase;

    if not found then
      perform fc_debug('Valor da UFISA n�o encontrado para a data selecionada: '||iAnoBase, true);
      return coalesce(nValorUfisa, 0);
    end if;
    return nValorUfisa;
  end

$$  language 'plpgsql';
/**
 * Wrapper da fun��o fc_get_valor_ufisa
 */
create or replace function fc_get_valor_ufisa() returns numeric as
$$
  declare
    nValor  numeric default 0;
  begin
    select fc_get_valor_ufisa( extract (year from fc_getsession('DB_datausu')::date)::integer ) into nValor;
    return nValor;
  end

$$  language 'plpgsql';

/**
 * C�lculo da aliquiota
 */
create or replace function fc_iptu_getaliquota_araruama_2011(integer,integer,integer,integer,numeric,boolean,boolean) returns numeric as
$$
declare

  iMatricula      alias for $1;
  iAnoUsu         alias for $2;
  iIdbql          alias for $3;
  iNumcgm         alias for $4;
  nVlrVenalImovel alias for $5;
  bPredial        alias for $6;
  lRaise          alias for $7;

  nVlrAliquota     numeric default 1;
  nVlrBaseAliquota numeric default 0;
  nVlrImovel       numeric default 0;
  nVlrUFISA        numeric default 0;
  rnAliq           numeric default 0;
  iSetor           integer default 0;
  iCaract          integer default 0;
  iImoTerritoriais integer default 0;
  iNumcalculos     integer default 0;
  rBase            record;
  sSqlBase  text   default '';

begin
  /* EXECUTAR SOMENTE SE NAO TIVER ISENCAO */
  perform fc_debug('DEFININDO QUAL ALIQUOTA APLICAR ...', lRaise);
  perform fc_debug('IPTU : '||case when bPredial is true then 'PREDIAL' else 'TERRITORIAL' end, lRaise);

 /* Criterios para escolha da aliquota */


  select fc_get_valor_ufisa(iAnoUsu)
    into nVlrUFISA;
  /**
   * Valor base para c�lculo das aliquotas
   */

  if   nVlrVenalImovel = 0 or nVlrUFISA = 0 then
    nVlrBaseAliquota := 0;
  else
    nVlrBaseAliquota := nVlrVenalImovel::numeric / nVlrUFISA::numeric;
  end if;

   sSqlBase := '
                   select j31_grupo,
                          j35_caract,
                          j01_matric
                     from iptubase
                          inner join carlote  on j35_idbql  = j01_idbql
                          inner join caracter on j31_codigo = j35_caract
                    where j01_matric = '||iMatricula||' and j31_grupo in (2,4);';

    for rBase in execute sSqlBase loop

      perform fc_debug('
      +------------------------------------------------+
      |         Dados Para Calculo da Aliquota         |
      +------------------------------------------------+
      |  Matricula      : '||rBase.j01_matric||'       |
      |  Idbql          : '||iIdbql||'                 |
      |  Vvi            : '||nVlrVenalImovel||'        |
      |  Grupo          : '||rBase.j31_grupo||'        |
      |  Caracteristica : '||rBase.j35_caract||'       |
      +------------------------------------------------+
        SQL executado : '||sSqlBase, lRaise);

      /**
       * Verifica grupo do imovel
       */

      if rBase.j31_grupo  = 2 then
        /**
         * Verifica caracteristica
         */
        if rBase.j35_caract    = 7 then

          if     nVlrBaseAliquota <= 26                             then nVlrAliquota := 2;
          elsif (nVlrBaseAliquota > 26 and nVlrBaseAliquota <= 79)  then nVlrAliquota := 2.50;
          elsif (nVlrBaseAliquota > 79 and nVlrBaseAliquota <= 263) then nVlrAliquota := 3;
          elsif  nVlrBaseAliquota > 263                             then nVlrAliquota := 3.50;
          end if;

        elsif rBase.j35_caract = 6 then

          nVlrAliquota := 1.50;

        elsif rBase.j35_caract = 5 then

          if nVlrBaseAliquota <= 79                                    then nVlrAliquota := 0.60;
            elsif (nVlrBaseAliquota > 79  and nVlrBaseAliquota <= 158) then nVlrAliquota := 0.70;
            elsif (nVlrBaseAliquota > 158 and nVlrBaseAliquota <= 658) then nVlrAliquota := 0.75;
            elsif nVlrBaseAliquota > 658                               then nVlrAliquota := 0.80;
          end if;

        elsif rBase.j35_caract = 8 then
          nVlrAliquota := 0.10;
        elsif rBase.j35_caract = 30115 then
          nVlrAliquota := 0.25;
        else
          perform fc_debug('Regra de c�lculo inconsistente. Grupo: '||rBase.j31_grupo||', Caracteristica: '||rBase.j35_caract, lRaise);
        end if;

      elsif rBase.j31_grupo = 4 then

        if (rBase.j35_caract = 14 or rBase.j35_caract = 27) then
          nVlrAliquota := 1;
        end if;
      end if;

      perform fc_debug('Grupo: '||rBase.j31_grupo||', Caracteristica: '||rBase.j35_caract, lRaise);
      perform fc_debug('Aliquota setada '||nVlrAliquota, lRaise);
      perform fc_debug('Base Aliquota '||nVlrBaseAliquota, lRaise);


    end loop;


  perform fc_debug('aliquota final :'||nVlrAliquota, lRaise);
  execute 'update tmpdadosiptu set aliq = '||nVlrAliquota;
  return nVlrAliquota;
end;
$$  language 'plpgsql';create or replace function fc_calculoiptu_arr_2015(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula 	  	    alias   for $1;
   iAnousu              alias   for $2;
   lGerafinanceiro      alias   for $3;
   lAtualizaParcela     alias   for $4;
   lNovonumpre          alias   for $5;
   lCalculogeral        alias   for $6;
   lDemonstrativo       alias   for $7;
   iParcelaini          alias   for $8;
   iParcelafim          alias   for $9;

   iIdbql               integer default 0;
   iNumcgm              integer default 0;
   iCodcli              integer default 0;
   iCodisen             integer default 0;
   iTipois              integer default 0;
   iParcelas            integer default 0;
   iNumconstr           integer default 0;
	 iZona				        integer default 0;

   dDatabaixa           date;

   nAreal               numeric default 0;
   nAreac               numeric default 0;
   nTotarea             numeric default 0;
   nFracao              numeric default 0;
   nFracaolote          numeric default 0;
   nAliquota            numeric default 0;
   nIsenaliq            numeric default 0;
   nArealo              numeric default 0;
   nVvc                 numeric(15,2) default 0;
   nVvt                 numeric(15,2) default 0;
   nVv                  numeric(15,2) default 0;
   nViptu               numeric(15,2) default 0;
   nValorMaxAnoAnterior numeric(15,2) default 0;

   tRetorno             text default '';
   tDemo                text default '';

   bFinanceiro          boolean;
   bDadosIptu           boolean;
   lErro                boolean;
	 iCodErro					    integer;
	 tErro						    text;
   bIsentaxas           boolean;
   bTempagamento        boolean;
   bEmpagamento         boolean;
   bTaxasCalculadas     boolean;
   lRaise               boolean default false;

   rCfiptu              record;

begin

  lRaise    := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, bTempagamento,
         bEmpagamento, iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || bTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || bEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || bIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);

  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  /**
   * Guarda os parametros do calculo
   */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /**
   * Calcula valor do terreno
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_arr_2015 IDBQL: '||iIdbql||' - FRACAO DO LOTE: '||nFracaolote||' DEMO: '||tRetorno||'- ERRO: '||lErro, lRaise);

  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvt_arr_2015( iAnousu ,iIdbql, nFracaolote, rCfiptu.j18_vlrref::numeric, lDemonstrativo, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvt_arr_2015 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

	if lErro is true then

    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
	end if;

  /**
   * Verifica Zona Especifica para atribuir isencao
   */
	select j34_zona
	  into iZona
	  from lote
   where j34_idbql = iIdbql;

	if iZona = 24 then

		nIsenaliq  := 100;
		bIsentaxas := true;
		iTipois    := 1;
    update tmpdadosiptu set tipoisen = iTipois;
    perform fc_debug(' Verifica Zona Especifica para atribuir isencao -> CODISEN: '||iCodisen||' - TIPOISEN : '||iTipois||' - ALIQ INSEN : '||nIsenaliq||' - INSENTAXAS: '||bIsentaxas||' - AREALO: ' || nArealo, lRaise);
  end if;

  /**
   * Calcula valor da construcao
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_arr_2015 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu||' - DEMO: '||lDemonstrativo, lRaise);

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvc_arr_2015( iMatricula, iAnousu, rCfiptu.j18_vlrref::numeric, lDemonstrativo, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvc_arr_2015 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRU��ES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  if nVvc is null or nVvc = 0 and iNumconstr <> 0 then

    select fc_iptu_geterro(22, '') into tRetorno;
    return tRetorno;
  end if;

  /**
   * Busca a aliquota
   */
  if iNumconstr is not null and iNumconstr > 0 then
    select fc_iptu_getaliquota_arr_2015('true'::boolean, nTotarea::numeric, lRaise) into nAliquota;
  else
    select fc_iptu_getaliquota_arr_2015('false'::boolean, nTotarea::numeric, lRaise) into nAliquota;
  end if;

  if nAliquota = 0 then

    select fc_iptu_geterro(13, '') into tRetorno;
    return tRetorno;
  end if;

  /**
   * Calcula o Valor Venal
   */
  perform fc_debug('' || lpad('',60,'-'), lRaise);

  nVv    := nVvc + nVvt;
  perform fc_debug(' CALCULO DO VALOR VENAL: Vvc= '||nVvc||' nVvt= '||nVvt||' VALOR VENAL= '||nVv, lRaise);

  nViptu := nVv * (nAliquota / 100);
  perform fc_debug(' CALCULO DO VALOR DO IPTU: Vvi= '||nViptu||' Aliquota= '||nAliquota/100, lRaise);

  /**
   * Valor maximo do imposto do iptu calculado no exercicio anterior
   * nao deve ter aumento superior a 50%
   * Utilizamos j21_codhis = 1 pois o hist�rico do IPTU � sempre 1
   */
  select ( j21_valor * 1.5 )
    into nValorMaxAnoAnterior
    from iptucalv
   where j21_matric = iMatricula
     and j21_anousu = (iAnousu-1)
     and j21_codhis = 1;

  if found and nViptu > nValorMaxAnoAnterior then

    perform fc_debug(' VALOR DO IPTU ULTRAPASSOU O TETO MAXIMO (AUMENTO DE 50% COMPARANDO COM EXERCICIO ANTERIOR) VALOR CALCULADO '||nViptu||' TETO MAXIMO= '||nValorMaxAnoAnterior, lRaise);
    nViptu := nValorMaxAnoAnterior;
  end if;

  perform fc_debug('' || lpad('',60,'-'), lRaise);
  /*-------------------------------------------*/

  perform fc_debug('nViptu : ' || nViptu, lRaise);

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim;

  if not found or iParcelas = 0 then

    select fc_iptu_geterro(14, '') into tRetorno;
    return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu
     set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa
     set anousu  = iAnousu,
         matric  = iMatricula,
         idbql   = iIdbql,
         valiptu = nViptu,
         valref  = rCfiptu.j18_vlrref,
         vvt     = nVvt,
         nparc   = iParcelas;

  perform fc_debug('PARAMETROS fc_iptu_calculataxas ANOUSU: '||iAnousu||' - CODCLI: '||iCodcli, lRaise);

  /**
   * Calcula as taxas
   */
  select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, lRaise)
    into bTaxasCalculadas;

  perform fc_debug('RETORNO fc_iptu_calculataxas -> TAXASCALCULADAS: ' || bTaxasCalculadas, lRaise);

  /**
   * Monta o demonstrativo
   */
  select fc_iptu_demonstrativo(iMatricula, iAnousu, iIdbql, lRaise)
    into tDemo;

  /**
   * Gera financeiro
   *  -> Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
   */
  if lDemonstrativo is false then

    select fc_iptu_geradadosiptu(iMatricula, iIdbql, iAnousu, nIsenaliq, lDemonstrativo, lRaise)
      into bDadosIptu;

      if lGerafinanceiro then

        select fc_iptu_gerafinanceiro( iMatricula, iAnousu, iParcelaini, iParcelafim, lCalculogeral, bTempagamento, lNovonumpre, lDemonstrativo, lRaise )
          into bFinanceiro;
      end if;
  else
     return tDemo;
  end if;

  if lDemonstrativo is false then

     update iptucalc
        set j23_manual = tDemo
      where j23_matric = iMatricula
        and j23_anousu = iAnousu;
  end if;

  perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise, false, true);

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$ language 'plpgsql';create or replace function fc_iptu_taxalimpeza_arr_2015(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;

    nValorTaxa      numeric(15,2) default 0;
    nValorBase      numeric(15,2) default 0;
		nAreaEdificada  numeric(15,2) default 0;
		nPercentual	    numeric(15,2) default 0;
    iIdbql          integer       default 0;
    iNparc          integer       default 0;
    lPredial        boolean       default false;
    tSql            text          default '';

begin

    /**
     * So calcula se for predial
     */
    perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO...', lRaise);
    perform fc_debug(' <iptu_taxalimpeza_arr> receita - ' ||iReceita||' aliq - '||iAliquota||' historico - ' || iHistCalc, lRaise);

    select coalesce( sum(areaed) ,0)
      into nAreaEdificada
      from ( select areaed, coalesce( ( select carconstr.j48_caract
                                          from carconstr
                                               inner join caracter on carconstr.j48_caract = caracter.j31_codigo
                                         where carconstr.j48_matric = tmpiptucale.matric
                                           and carconstr.j48_idcons = tmpiptucale.idcons
                                           and caracter.j31_grupo   = 68
                                      ), 0 ) as j48_caract
              from tmpiptucale
           ) as x
    where j48_caract not in (738);

    select predial
      into lPredial
      from tmpdadosiptu;

    if lPredial then

      select idbql,nparc
        into iIdbql,iNparc
        from tmpdadostaxa;

      if not found then
        return false;
      end if;

      if nValpar <> 0 then
        nValorBase := nValpar;
      end if;

		  nPercentual := 0;

      perform fc_debug(' <iptu_taxalimpeza_arr> nAreaEdificada: ' || nAreaEdificada, lRaise);

      if nAreaEdificada = 0 then
				nPercentual := 0;
      elsif nAreaEdificada between 1 and 200 then
				nPercentual := 90;
      elsif nAreaEdificada between 200.01 and 300 then
				nPercentual := 150;
      else
				nPercentual := 200;
			end if;

      if nPercentual > 0 then

        nValorTaxa := (nValorBase * nPercentual) / 100 ;

        perform fc_debug(' <iptu_taxalimpeza_arr> Limpeza: ' || nValorTaxa, lRaise);
        perform fc_debug(' <iptu_taxalimpeza_arr> Inserindo tmptaxapercisen  - iReceita '||coalesce(iReceita,0)||' iPercIsen - '||coalesce(nPercentual,0)||' nValorTaxa - ' || coalesce(nValorTaxa,0), lRaise);

        insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValorTaxa);

        if iPercIsen > 0 then
          nValorTaxa := nValorTaxa * (100 - iPercIsen) / 100;
        end if;

        tSql := 'insert into tmprecval values ('||iReceita||','||nValorTaxa||','||iHistCalc||',true)';

        execute tSql;

      end if;

    end if;

  return true;

end;
$$ language 'plpgsql';create or replace function fc_iptu_calculavvc_arr_2015( iMatricula      integer,
                                                        iAnousu         integer,
                                                        nVlrref         numeric,
                                                        bMostrademo     boolean,
                                                        bRaise          boolean,

                                                        OUT rnVvc       numeric(15,2),
                                                        OUT rnTotarea   numeric,
                                                        OUT riNumconstr integer,
                                                        OUT rtDemo      text,
                                                        OUT rtMsgerro   text,
                                                        OUT rbErro      boolean,
                                                        OUT riCodErro   integer,
                                                        OUT rtErro      text
                                                      ) returns record as
$$
declare

    iMatricula     alias for $1;
    iAnousu        alias for $2;
	nVlrref 	   alias for $3;
    lRaise         alias for $5;

	nValorVenalTotal	 numeric(15,2) default 0;
	iNumeroedificacoes   integer default 0;
    nVm2c    			 numeric(15,2) default 0;
    nValorVenal     	 numeric;
    nFatorEstConservacao numeric;
	lEdificacao			 boolean;
    nAreaconstr			 numeric(15,2) default 0;
	lMatriculaPredial	 boolean;

    tSqlConstr           text    default '';
	tSqlCar		         text    default '';
    lAtualiza            boolean default true;
    rConstr              record;
    rCar			     record;

begin

    perform fc_debug('', lRaise);
    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('* INICIANDO CALCULO DO VALOR VENAL DA CONSTRUCAO', lRaise);

    rnVvc       := 0;
    rnTotarea   := 0;
    riNumconstr := 0;
    rtDemo      := '';
    rtMsgerro   := 'Retorno ok' ;
    rbErro      := 'f';
    riCodErro   := 0;
    rtErro      := '';

    tSqlConstr :=               ' select * ';
    tSqlConstr := tSqlConstr || '  from iptuconstr';
	tSqlConstr := tSqlConstr || ' where j39_matric = ' || iMatricula;
    tSqlConstr := tSqlConstr || '   and j39_dtdemo is null';

    perform fc_debug('Select buscando as contrucoes : ' || tSqlConstr, lRaise);

    for rConstr in execute tSqlConstr loop

     lEdificacao := true;

     nVm2c       := coalesce(fc_iptu_get_valor_medio_carconstr_arr_2015( iMatricula, rConstr.j39_idcons, iAnousu, lRaise), 0);
     perform fc_debug('MATRICULA : ' || iMatricula || ' IDCONSTR: ' || rConstr.j39_idcons ||' ANO: '|| iAnousu || 'VALOR: ' || nVm2c, lRaise);

     if nVm2c = 0 then

      rbErro    := true;
      riCodErro := 107;
      rtErro    := '';
      return;
     end if;

     nFatorEstConservacao := fc_iptu_getfatorcarconstr( iMatricula, rConstr.j39_idcons, 27, iAnousu );

     perform fc_debug('  nFatorEstConservacao : ' || nFatorEstConservacao, lRaise);
     if nFatorEstConservacao = 0 then

      rbErro    := true;
      riCodErro := 102;
      rtErro    := '27 - ESTADO DE CONSERVA��O';
      return;
     end if;

     perform fc_debug(' VVC usando formula: ( rConstr.j39_area * nVm2c * nFatorEstConservacao )', lRaise);
     perform fc_debug('  -> Valores: ( '||rConstr.j39_area||' * '||nVm2c||' * '||nFatorEstConservacao||' )', lRaise);

     nValorVenal        := ( rConstr.j39_area * nVm2c * nFatorEstConservacao );
	 nValorVenalTotal   := nValorVenalTotal + nValorVenal;
     perform fc_debug('Valor total venal: '||coalesce(nValorVenalTotal,0),lRaise);

     nAreaconstr        := nAreaconstr + rConstr.j39_area;
     perform fc_debug('Area Construida: ' || coalesce(nAreaconstr,0),lRaise);
	 iNumeroedificacoes := iNumeroedificacoes + 1;

	 insert into tmpiptucale (anousu, matric,idcons,areaed,vm2,pontos,valor,edificacao)
 					  values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, nVm2c, 0, nValorVenal, lEdificacao);
	 if lAtualiza then

	   update tmpdadosiptu set predial = true;
	   lAtualiza = false;
	 end if;

    end loop;

    perform matric
   	   from tmpiptucale
	  where edificacao is true;

	if found then
	  lMatriculaPredial = true;
	else
	  lMatriculaPredial = false;
	end if;

	if lMatriculaPredial is true then

	  rnVvc       := nValorVenalTotal;
	  rnTotarea   := nAreaconstr;
	  riNumconstr := iNumeroedificacoes;
	  rtDemo      := '';
	  rbErro      := 'f';

	  update tmpdadosiptu set vvc = rnVvc;
	else

	  delete from tmpiptucale;
	  update tmpdadosiptu set predial = false;
	end if;

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('', lRaise);

  return;

end;
$$  language 'plpgsql';create or replace function fc_iptu_calculavvt_arr_2015(integer,integer,numeric,numeric,boolean,boolean,
                                                       OUT rnVvt        numeric(15,2),
                                                       OUT rnAreaTotalC numeric,
                                                       OUT rnArea       numeric,
                                                       OUT rnTestada    numeric,
                                                       OUT rtDemo       text,
                                                       OUT rtMsgerro    text,
                                                       OUT rbErro       boolean,
                                                       OUT riCoderro    integer,
                                                       OUT rtErro       text ) returns record as
$$
declare

    iAnousu      alias for $1;
    iIdbql       alias for $2;
    nFracao      alias for $3;
    nVlrref      alias for $4;
    lRaise       alias for $6;

    rnArealote                    numeric;
    rnAreaCorrigida               numeric;
    rnVm2terreno                  numeric;
    nFatorSituacao                numeric;
    nFatorPedologia               numeric;
    nFatorNivel                   numeric;
    nFatorGleba                   numeric;
    nFatorDepreciacaoProfundidade numeric;

begin

    rnVvt        := 0;
    rnAreaTotalC := 0;
    rnArea       := 0;
    rnTestada    := 0;
    rtDemo       := '';
    rtMsgerro    := '';
    rbErro       := 'f';
    riCoderro    := 0;
    rtErro       := '';

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('* INICIANDO CALCULO DO VALOR VENAL TERRITORIAL',lRaise);

    select case when j34_areal  = 0 then j34_area   else j34_areal  end
      into rnArealote
      from lote
           inner join testpri on j49_idbql  = j34_idbql
           inner join testada on j36_idbql  = j49_idbql
                             and j36_face   = j49_face
                             and j36_codigo = j49_codigo
           inner join face    on j37_face   = j49_face
    where j34_idbql = iIdbql;

    if rnArealote is null then

      rbErro    := 't';
      riCodErro := 6;
      rtErro    := '';
      return;
    end if;

    perform fc_debug('AREA REAL DO LOTE: ' || rnArealote,lRaise);

    select coalesce( max(j81_valorterreno), 0 )
      into rnVm2terreno
      from face
           inner join facevalor fv on fv.j81_face = face.j37_face
           inner join testada   tp on tp.j36_face = face.j37_face
     where j81_anousu    = iAnousu
       and tp.j36_idbql  = iIdbql;

    if not found or rnVm2terreno = 0 then

      rbErro    := true;
      riCodErro := 25;
      rtErro    := '';
      return;
    end if;

    perform fc_debug('nVlrM2Terreno = ' || rnVm2terreno, lRaise);

    rnAreaCorrigida := ( rnArealote * ( nFracao / 100 ) );
    rnArea          := rnAreaCorrigida;

    nFatorSituacao                := ( select fc_iptu_getfatorcarlote( 5, iIdbql, iAnousu) );
    nFatorGleba                   := ( select fc_iptu_get_fator_gleba_arr_2015( iIdbql ) );
    nFatorDepreciacaoProfundidade := ( select fc_iptu_get_fator_profundidade_arr_2015( iIdbql ) );

    perform fc_debug('FATOR SITUACAO = ' || nFatorSituacao || ' IDBQL = ' ||iIdbql|| ' ANO : ' ||iAnousu , lRaise);
    if nFatorSituacao = 0 then

      rbErro    := true;
      riCodErro := 101;
      rtErro    := '5 - POSI��O';
      return;
    end if;

    /**
     * Terrenos de esquina
     */
    perform *
       from carlote
      where j35_idbql = iIdbql
        and j35_caract in (13, 14, 15, 16);

    if found or nFatorGleba <> 1 then
      nFatorDepreciacaoProfundidade := 1;
    end if;

    -- VVT := FIT x Vm2t x S x G x DP (se G=0)
    rnVvt := ( rnAreaCorrigida *
               rnVm2terreno    *
               nFatorSituacao  *
               nFatorGleba     *
               nFatorDepreciacaoProfundidade );

    perform fc_debug('Calculando VVT utilizando formula: VVT := FIT x Vm2t x S x G x DP (se G=0)',           lRaise);
    perform fc_debug(' -> Valores: VVT := '||rnAreaCorrigida||' x '||rnVm2terreno||' x '||nFatorSituacao||' x '||nFatorGleba||' x ' || nFatorDepreciacaoProfundidade, lRaise);
    perform fc_debug('AREA CORRIG BRUTA (RAIZ QUADRADA DA PROFUNDIDADE): ' || rnAreaCorrigida,               lRaise);
    perform fc_debug('VALOR METRO QUADRADO DO TERRENO:                   ' || rnVm2terreno,                  lRaise);
    perform fc_debug('FATOR SITUACAO:                                    ' || nFatorSituacao,                lRaise);
    perform fc_debug('FATOR GLEBA:                                       ' || nFatorGleba,                   lRaise);
    perform fc_debug('FATOR DEPRECIACAO PROFUNDIDADE:                    ' || nFatorDepreciacaoProfundidade, lRaise);
    perform fc_debug('VALOR VENAL TERRENO:                               ' || rnVvt,                         lRaise);

    update tmpdadosiptu set vvt = rnVvt, vm2t=rnVm2terreno, areat=rnAreaCorrigida;

    perform fc_debug('' || lpad('',60,'-'), lRaise);

    return;

end;
$$  language 'plpgsql';create or replace function fc_iptu_getaliquota_arr_2015(boolean,numeric,boolean) returns numeric as
$$
declare

  lPredial        alias for $1;
  nAreaConstruida alias for $2;
  lRaise          alias for $3;

  nAliquota       numeric default 0;

begin

  perform fc_debug( 'DEFININDO ALIQUOTA A APLICAR' , lRaise);
  perform fc_debug( 'IPTU: ' || case when lPredial is true then 'PREDIAL' else 'TERRITORIAL' end, lRaise);
  perform fc_debug( 'AreaConstruida: ' || nAreaConstruida, lRaise);

  /**
   * Terrenos com edificacao - predial     - a aliquota de 0,60%
   * Terrenos baldios        - territorial - a aliquota de 1,50%
   */
  if lPredial is true then
    nAliquota = 0.60;
  end if;

  if lPredial is false or nAreaConstruida <= 15 then
    nAliquota = 1.5;
  end if;

  perform fc_debug('Aliquota Final: ' || nAliquota, lRaise);

  execute 'update tmpdadosiptu set aliq = ' || coalesce( nAliquota, 0 );

  return nAliquota;

end;
$$ language 'plpgsql';create or replace function fc_iptu_get_fator_profundidade_arr_2015(integer) returns numeric as
$$

  select case
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 0     and 10.00 then 0.70
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 10.01 and 12.50 then 0.80
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 12.51 and 15.00 then 0.85
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 15.01 and 16.00 then 0.90
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 16.01 and 18.00 then 0.95
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 18.01 and 40.00 then 1.00
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 40.01 and 45.00 then 0.95
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 45.01 and 50.00 then 0.90
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 50.01 and 55.00 then 0.85
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 55.01 and 60.00 then 0.80
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 60.01 and 65.00 then 0.78
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 65.01 and 70.00 then 0.75
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 70.01 and 75.00 then 0.73
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 75.01 and 80.00 then 0.70
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) between 80.01 and 99.99 then 0.60
           when round( j34_area / (case when j36_testle = 0 then j36_testad else j36_testle end), 2 ) >= 100.00 then 0.50
         end as fator_profundidade
    from lote
         inner join testpri tp on tp.j49_idbql = lote.j34_idbql
         inner join testada t  on t.j36_idbql  = tp.j49_idbql
                              and t.j36_face   = tp.j49_face

   where j34_idbql = $1;

$$
language 'sql';create or replace function fc_iptu_get_fator_gleba_arr_2015(integer) returns numeric as
$$

  select case
           when j34_area < 10000.00                          then 1
           when j34_area > 10000.00 and j34_area <= 15000.00 then 0.40
           when j34_area > 15000.00                          then 0.20
           else 1
         end as fator_gleba
    from lote
   where j34_idbql = $1;

$$
language 'sql';create or replace function fc_iptu_get_valor_medio_carconstr_arr_2015(integer, integer, integer, boolean) returns float as
$$
declare

  iMatricula       alias for $1;
  iIdContrucao     alias for $2;
  iAnousu          alias for $3;
  lRaise           alias for $4;

  fValorMedio      float8 default 0;

begin

  lRaise  := (case when fc_getsession('DB_debugon') is null then false else true end);

  select coalesce(sum(j119_valor),0)
    into fValorMedio
    from carcaractervalor
   where j119_anousu = $3
     and j119_caracteristica1 in ( select j48_caract
                                     from carconstr
                                          inner join caracter on j31_codigo = j48_caract
                                    where j48_matric = $1
                                      and j48_idcons = $2
                                      and j31_grupo  = 68 )
     and j119_caracteristica2 in ( select j48_caract
                                     from carconstr
                                          inner join caracter on j31_codigo = j48_caract
                                    where j48_matric = $1
                                      and j48_idcons = $2
                                      and j31_grupo  = 69 );

  perform fc_debug(' <iptu_get_valor_medio_carconstr> Buscando valor medio utilizando parametros:'      , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> iMatricula      : ' || iMatricula                 , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> iIdContrucao    : ' || iIdContrucao               , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> iAnousu         : ' || iAnousu                    , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> Valor Retornado : ' || coalesce( fValorMedio, 0 ) , lRaise);
  perform fc_debug('', lRaise);

  return fValorMedio;

end;
$$  language 'plpgsql';create or replace function fc_iptu_taxalimpeza_osorio(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita                 alias for $1;
    iAliquota                alias for $2;
    iHistoricoCalculoIsencao alias for $3;
    nPercIsen                alias for $4;
    lRaise                   alias for $6;

    nValorTaxa      numeric(15,2) default 0;
    nValorDesconto  numeric(15,2) default 0;
    nInflatorURM    numeric(15,4) default 0;
    nAreaEdificada  numeric(15,2) default 0;
    iIdbql          integer       default 0;
    iNparc          integer       default 0;
    tSql            text          default '';
    iURM            integer       default 0;

  begin

    /**
     * So calcula se for predial
     */
    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO...', lRaise);
    perform fc_debug(' <iptu_taxalimpeza_osorio> receita - ' ||iReceita||' aliq - '||iAliquota||' historico - ' || iHistoricoCalculoIsencao, lRaise);

    select coalesce( sum(areaed) ,0)
      into nAreaEdificada
      from ( select areaed, coalesce( ( select carconstr.j48_caract
                                          from carconstr
                                               inner join caracter on carconstr.j48_caract = caracter.j31_codigo
                                         where carconstr.j48_matric = tmpiptucale.matric
                                           and carconstr.j48_idcons = tmpiptucale.idcons
                                      ), 0 ) as j48_caract
              from tmpiptucale
           ) as x;

    select idbql, nparc
      into iIdbql, iNparc
      from tmpdadostaxa;

    perform fc_debug(' <iptu_taxalimpeza_osorio> iNparc: ' || iNparc, lRaise);
    perform fc_debug(' <iptu_taxalimpeza_osorio> iIdbql: ' || iIdbql, lRaise);

    if not found then
      return false;
    end if;

    perform fc_debug(' <iptu_taxalimpeza_osorio> nAreaEdificada: ' || nAreaEdificada, lRaise);

    if nAreaEdificada = 0 then
      nPercIsen := 100;
    end if;

    case
      when nAreaEdificada between 1   and 50   then
        iURM := 15;
      when nAreaEdificada between 51  and 100  then
        iURM := 30;
      when nAreaEdificada between 101 and 200  then
        iURM := 60;
      when nAreaEdificada between 201 and 300  then
        iURM := 90;
      when nAreaEdificada between 301 and 1000 then
        iURM := 120;
      when nAreaEdificada > 1000 then
        iURM := 300;
      else
        iURM := 0;
    end case;

    select i02_valor::numeric
      into nInflatorURM
      from infla
     where i02_codigo = 'URM'
       and extract ( year from i02_data) = (select anousu
                                              from tmpiptucale
                                             limit 1)
     limit 1;

    perform fc_debug(' <iptu_taxalimpeza_osorio> Inflator encontrado: ' || coalesce(nInflatorURM, 0), lRaise);

    if nInflatorURM is null or nInflatorURM = 0 then
      return false;
    end if;
    perform fc_debug(' <iptu_taxalimpeza_osorio> Percentual de Isencao: ' || nPercIsen, lRaise);

    nValorTaxa := nInflatorURM * iURM;

    perform fc_debug(' <iptu_taxalimpeza_osorio> URM: ' || iURM || ' INFLATOR: ' || nInflatorURM, lRaise);
    perform fc_debug(' <iptu_taxalimpeza_osorio> Limpeza: ' || nValorTaxa, lRaise);
    perform fc_debug(' <iptu_taxalimpeza_osorio> Inserindo tmptaxapercisen  - iReceita '||coalesce(iReceita,0)||' nPercIsen - '||coalesce(nPercIsen,0)||' nValorTaxa - ' || coalesce(nValorTaxa,0), lRaise);

    insert into tmptaxapercisen values (iReceita, nPercIsen, 0, nValorTaxa);

    if nPercIsen > 0 then

      nValorDesconto := nValorTaxa * ( nPercIsen / 100 );
      nValorTaxa     := nValorTaxa * ( 100 - nPercIsen ) / 100;
    end if;

    perform fc_debug(' <iptu_taxalimpeza_osorio> LIMPEZA COM ISEN��O: ' || coalesce(nValorTaxa,0) || ' DESCONTO: '|| nValorDesconto, lRaise);

    tSql := 'insert into tmprecval values ('||iReceita||','||nValorTaxa||','||iHistoricoCalculoIsencao||',true)';

    execute tSql;

    return true;

  end;
$$ language 'plpgsql';drop function if exists fc_calculoiptu_riopardo_2015(integer, integer, boolean, boolean, boolean, boolean, boolean, integer, integer);
create or replace function fc_calculoiptu_riopardo_2015(integer, integer, boolean, boolean, boolean, boolean, boolean, integer, integer) returns varchar(100) as
$$
declare

   iMatricula 	  	    alias   for $1;
   iAnousu    	  	    alias   for $2;
   lGerafinanceiro      alias   for $3; -- Gera Financeiro
   lAtualizaParcela     alias   for $4; -- Atualizar Parcelas
   lNovonumpre          alias   for $5; -- Gera Novo Numpre
   lCalculogeral        alias   for $6; -- Calculo Geral
   lMostraDemonstrativo alias   for $7; -- Demonstrativo
   iParcelaini     	    alias   for $8;
   iParcelafim     	    alias   for $9;

   iIdbql              integer default 0;
   iNumcgm             integer default 0;
   iCodcli             integer default 0;
   iCodisen            integer default 0;
   iTipois             integer default 0;
   iParcelas           integer default 0;
   iNumconstr          integer default 0;
   iCodErro            integer default 0;

   dDatabaixa          date;

   nAreal              numeric default 0;
   nAreac              numeric default 0;
   nTotarea            numeric default 0;
   nFracao             numeric default 0;
   nFracaolote         numeric default 0;
   nAliquota           numeric default 0;
   nIsenaliq           numeric default 0;
   nArealote           numeric default 0;
   nVvc                numeric(15,2) default 0;
   nVvt                numeric(15,2) default 0;
   nVv                 numeric(15,2) default 0;
   nViptu              numeric(15,2) default 0;
   nPercentualAliquota numeric(15,2) default 0;

   tRetorno            text default '';
   tDemo               text default '';

   lFinanceiro         boolean;
   lDadosIptu          boolean;
   lErro               boolean;
   lIsentaxas          boolean;
   lTempagamento       boolean;
   lEmpagamento        boolean;
   lTaxasCalculadas    boolean;

   lRaise              boolean default false;
   lSubRaise           boolean default false;

   rCfiptu             record;
   dadosConstrucoes    record;

begin

  lRaise    := ( fc_getsession('DB_debugon') = 'false' );
  lSubRaise := ( fc_getsession('DB_debugon') = 'false' );

  perform fc_debug('INICIANDO CALCULO', lRaise, true, false);

  /**
   * Cria tabela temporaria com constru��es COM caracteristica de concluida
   */
  begin
    create temporary table w_iptuconstr as
    select *
      from ( select *, (select j31_codigo
                          from carconstr
                               inner join caracter on j31_codigo = j48_caract
                         where j48_matric = j39_matric
                           and j48_idcons = j39_idcons
                           and j31_grupo  = 58) as situacao
              from cadastro.iptuconstr
             where j39_matric = iMatricula
               and j39_dtdemo is null) as x
    where x.situacao not in (192);

    exception
         when duplicate_table then
         delete from w_iptuconstr;
         insert into w_iptuconstr
          select * from (
          select *,
                  (select j31_codigo from carconstr inner join caracter on j31_codigo = j48_caract where j48_matric = j39_matric and j48_idcons = j39_idcons and j31_grupo = 58) as situacao
          from cadastro.iptuconstr
          where j39_matric = iMatricula and j39_dtdemo is null) as x
          where x.situacao not in (192);

  end;

  /**
   * Cria tabela temporaria com constru��es SEM caracteristica de concluida
   */
  begin

    create temporary table w_iptuconstr_original as
    select *
      from ( select *, (select j31_codigo
                          from carconstr
                               inner join caracter on j31_codigo = j48_caract
                         where j48_matric = j39_matric
                           and j48_idcons = j39_idcons
                           and j31_grupo = 58) as situacao
              from cadastro.iptuconstr
             where j39_matric = iMatricula
               and j39_dtdemo is null) as x;

    exception
         when duplicate_table then
         delete from w_iptuconstr_original;
         insert into w_iptuconstr_original
          select * from (
          select *,
                  (select j31_codigo from carconstr inner join caracter on j31_codigo = j48_caract where j48_matric = j39_matric and j48_idcons = j39_idcons and j31_grupo = 58) as situacao
          from cadastro.iptuconstr
          where j39_matric = iMatricula and j39_dtdemo is null) as x;

  end;

  update cadastro.iptuconstr
     set j39_dtdemo = '1900-01-01'
    from w_iptuconstr
   where w_iptuconstr.j39_matric = iptuconstr.j39_matric
     and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

  /**
   * Verifica se os parametros passados estao corretos
   */
  select riidbql, rnareal, rnfracao, rinumcgm, rdbaixa, rbErro, rtretorno
    into iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, lErro, tRetorno
    from fc_iptu_verificaparametros(iMatricula,iAnousu,iParcelaini,iParcelafim);

  perform fc_debug('<parametros> IDBQL: '  || iIdbql,   lRaise);
  perform fc_debug('<parametros> AREAL: '  || nAreal,   lRaise);
  perform fc_debug('<parametros> FRACAO: ' || nFracao,  lRaise);
  perform fc_debug('<parametros> CGM: '    || iNumcgm,  lRaise);

  /**
   * Verifica se o calculo pode ser realizado
   */
  select rbErro, riCodErro
    into lErro, iCodErro
    from fc_iptu_verificacalculo(iMatricula,iAnousu,iParcelaini,iParcelafim);

  if lErro is true and lMostraDemonstrativo is false then

    select fc_iptu_geterro(27,'') into tRetorno;

    update cadastro.iptuconstr
       set j39_dtdemo = null
      from w_iptuconstr
     where w_iptuconstr.j39_matric = iptuconstr.j39_matric
       and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;
    return tRetorno;
  end if;

  /**
   * Verifica se matricula esta baixada
   */
  if dDataBaixa is not null and to_char(dDataBaixa,'Y')::integer <= iAnousu then

    /**
     * Exclui calculo e retorna msg de matricula baixada
     */
    perform fc_iptu_excluicalculo( iMatricula, iAnousu );

    select fc_iptu_geterro(2,'') into tRetorno;

     update cadastro.iptuconstr
       set j39_dtdemo = null
      from w_iptuconstr
     where w_iptuconstr.j39_matric = iptuconstr.j39_matric
       and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

     return tRetorno;
  end if;

  /**
   * Cria as tabelas temporarias
   */
  select fc_iptu_criatemptable( lSubRaise ) into lErro;

  /**
   * Guarda os parametros do calculo
   */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /**
   * Fraciona lote
   */
  perform fc_debug('PARAMETROS IPTU_FRACIONALOTE FRACAO DO LOTE: Matricula: ' || iMatricula || ' - Anousu: '|| iAnousu, lRaise);

  select rnfracao, rtdemo, rtmsgerro, rbErro
    into nFracaolote, tDemo, tRetorno, lErro
    from fc_iptu_fracionalote(iMatricula, iAnousu, lMostraDemonstrativo, lSubRaise);
    update tmpdadosiptu set fracao = nFracaolote;

  perform fc_debug('RETORNO fc_iptu_fracionalote -> Fra��o do Lote: '||nFracaolote||' - DEMONS: '||tDemo, lRaise);

  /**
   * Verifica pagamentos
   */
  perform fc_debug('', lRaise);
  perform fc_debug('PARAMETROS fc_iptu_verificapag -> Matricula: '||iMatricula||' - Anousu: '||iAnousu||' - Desmonstrativo: '||lMostraDemonstrativo, lRaise);

  select rbTempagamento, rbEmpagamento, rtmsgretorno, rbErro
    into lTempagamento, lEmpagamento, tRetorno, lErro
    from fc_iptu_verificapag(iMatricula, iAnousu, lCalculogeral, lAtualizaparcela, false, lMostraDemonstrativo, lSubRaise);

  perform fc_debug('RETORNO fc_iptu_verificapag -> TEMPAGAMENTO: '||lTempagamento||' - EMPAGAMENTO: '||lEmpagamento||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug(' ', lRaise);

  /**
   * Verifica isencoes
   */
  perform fc_debug('PARAMETROS fc_iptu_verificaisencoes -> Matricula: '||iMatricula||' - Anousu: '||iAnousu||' - Desmonstrativo: '||lMostraDemonstrativo, lRaise);

  select ricodisen, ritipois, rnisenaliq, rbIsentaxas, rnArealo
    into iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealote
    from fc_iptu_verificaisencoes(iMatricula,iAnousu,lMostraDemonstrativo,lSubRaise);

  if iTipois is not null then
    update tmpdadosiptu set tipoisen = iTipois;
  end if;

  perform fc_debug('RETORNO fc_iptu_verificaisencoes -> CODISEN:    '||iCodisen||' '
                                                       'TIPOISEN:   '||iTipois||' '
                                                       'ALIQ INSEN: '||nIsenaliq||' '
                                                       'INSENTAXAS: '||lIsentaxas||' '
                                                       'AREALO:     '||nArealote, lRaise);
  perform fc_debug(' ', lRaise);

  /**
   * Calcula valor do terreno
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_riopardo_2015 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu||' - FRACAO DO LOTE: '||nFracaolote, lRaise);

  select rnvvt, rnarea, rtdemo, rtmsgerro, riCodErro, rbErro
    into nVvt, nAreac, tDemo, tRetorno, iCodErro, lErro
    from fc_iptu_calculavvt_riopardo_2015(iMatricula, iIdbql, iAnousu, nFracaolote, nAreal, lMostraDemonstrativo, lSubRaise);

  perform fc_debug('RETORNO fc_iptu_calculavvt_riopardo_2015 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if iCodErro > 0 then

     select fc_iptu_geterro( iCodErro, tRetorno ) into tRetorno;

     update cadastro.iptuconstr
        set j39_dtdemo = null
       from w_iptuconstr
      where w_iptuconstr.j39_matric = iptuconstr.j39_matric
        and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

     return tRetorno;
  end if;

  alter table tmpiptucale add column caracteristica integer, add column aliquota numeric;
  perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTUCALE ALTERADA', lRaise);

  /**
   * Calcula valor da construcao
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_riopardo_2015 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu||' - DEMO: '||lMostraDemonstrativo, lRaise);

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, riCodErro, rbErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, iCodErro, lErro
    from fc_iptu_calculavvc_riopardo_2015( iMatricula, iAnousu, lMostraDemonstrativo, lSubRaise);

  perform fc_debug('RETORNO fc_iptu_calculavvc_riopardo_2015 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONTRU��ES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if iCodErro > 0 then

     select fc_iptu_geterro( iCodErro, tRetorno ) into tRetorno;

     update cadastro.iptuconstr
        set j39_dtdemo = null
       from w_iptuconstr
      where w_iptuconstr.j39_matric = iptuconstr.j39_matric
        and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

     return tRetorno;
  end if;

  /**
   * Busca a aliquota
   */
  if iNumconstr is not null and iNumconstr > 0 then
    select fc_iptu_getaliquota_riopardo_2015(iMatricula, iIdbql, iNumcgm, true, iAnousu, lSubRaise) into nAliquota;
  else
    select fc_iptu_getaliquota_riopardo_2015(iMatricula, iIdbql, iNumcgm, false, iAnousu, lSubRaise) into nAliquota;
  end if;

  if not found or nAliquota = 0 then

     select fc_iptu_geterro(13, '') into tRetorno;

     update cadastro.iptuconstr
        set j39_dtdemo = null
       from w_iptuconstr
      where w_iptuconstr.j39_matric = iptuconstr.j39_matric
        and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

     return tRetorno;
  end if;

  /**
   * Buscar dados de cada constru��o separadamente, para multiplicar pela sua respectiva aliquota
   */

  /**
   * Calcula o Valor Venal
   */
  perform fc_debug('' || lpad('',60,'-'), lRaise);
  nVv    := nVvc + nVvt;

  perform fc_debug(' CALCULO DO VALOR VENAL: Vvc= '||nVvc||' nVvt= '||nVvt||' VALOR VENAL= '||nVv, lRaise);

  /**
   * Realizamos um for para que seja aplicada a aliquota de cada constru�ao, proporcinal ao Vvc
   */
  for dadosConstrucoes in select * from tmpiptucale where matric = iMatricula loop

    nPercentualAliquota := (dadosConstrucoes.valor * 100) / nVvc;
    nViptu              := nViptu + (nVv * nPercentualAliquota/100) * dadosConstrucoes.aliquota/100;
    perform fc_debug(' Constru�ao = '||dadosConstrucoes.idcons||' Vvi= '||nViptu||' Aliquota= '||dadosConstrucoes.aliquota, lRaise);

  end loop;

  /**
   * Se o nViptu for 0, significa que o IPTU eh territorial, entao aplicamos a aliquota quem vem do getaliquota
   */
  if nViptu = 0 then
    nViptu := nVv * (nAliquota / 100);
  end if;

  perform fc_debug(' CALCULO DO VALOR DO IPTU: Vvi= '||nViptu||' Aliquota= '||nAliquota/100, lRaise);

  perform fc_debug('' || lpad('',60,'-'), lRaise);
  /*-------------------------------------------*/

  perform fc_debug('nViptu : '||nViptu, lRaise);

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim;

  if not found or iParcelas = 0 then

     select fc_iptu_geterro(14, '') into tRetorno;

     update cadastro.iptuconstr
        set j39_dtdemo = null
       from w_iptuconstr
      where w_iptuconstr.j39_matric = iptuconstr.j39_matric
        and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

     return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;

  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas;

  /**
   * Calcula as taxas
   */
  select db21_codcli
    into iCodcli
    from db_config
    where prefeitura is true;

  perform fc_debug('PARAMETROS fc_iptu_calculataxas ANOUSU: '||iAnousu||' - CODCLI: '||iCodcli, lRaise);

  select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, lSubRaise)
    into lTaxasCalculadas;
  perform fc_debug('RETORNO fc_iptu_calculataxas -> TAXASCALCULADAS: '||lTaxasCalculadas, lRaise);

  /**
   * Monta o demonstrativo
   */
  select fc_iptu_demonstrativo(iMatricula, iAnousu, iIdbql, lSubRaise )
    into tDemo;

  /**
   * Gera financeiro
   */
  if lMostraDemonstrativo is false then

    select fc_iptu_geradadosiptu(iMatricula, iIdbql, iAnousu, nIsenaliq, lMostraDemonstrativo, lSubRaise)
      into lDadosIptu;

      if lGerafinanceiro then

        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lMostraDemonstrativo,lSubRaise)
          into lFinanceiro;
      end if;
  else

    update cadastro.iptuconstr
      set j39_dtdemo = null
     from w_iptuconstr
    where w_iptuconstr.j39_matric = iptuconstr.j39_matric
      and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

    return tDemo;
  end if;

  if lMostraDemonstrativo is false then
     update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu;
  end if;

  perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise,false,true);

  select fc_iptu_geterro(1, '') into tRetorno;

  update cadastro.iptuconstr
       set j39_dtdemo = null
      from w_iptuconstr
     where w_iptuconstr.j39_matric = iptuconstr.j39_matric
       and w_iptuconstr.j39_idcons = iptuconstr.j39_idcons;

  return tRetorno;

end;
$$  language 'plpgsql';drop function if exists fc_iptu_taxacoletalixo_riopardo_2015(integer,numeric,integer,numeric,numeric,boolean);
create or replace function fc_iptu_taxacoletalixo_riopardo_2015(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;

    iIdbql         integer default 0;
    iAnousu        integer default 0;
    nAreaTotConstr numeric default 0;
    iMatricula     integer;

    bPredial       boolean default false;
    tSql           text    default '';

    nLixo          numeric(15,2) default 0;

begin

    perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO ...',lRaise,false,false);
    perform fc_debug(' <iptu_taxacoletalixo_riopardo> receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistCalc,lRaise,false,false);

    select predial
      into bPredial
      from tmpdadosiptu;

    select anousu,  idbql,  matric
      into iAnousu, iIdbql, iMatricula
      from tmpdadostaxa;

    if not found then
      return false;
    end if;

    perform(' <iptu_taxacoletalixo_riopardo> bPredial: ' || bPredial, lRaise);

    if bPredial is true then

       select coalesce(sum(j39_area), 0)
         into nAreaTotConstr
         from w_iptuconstr_original
        where j39_matric = iMatricula
          and j39_dtdemo is null
     group by j39_matric;

     perform(' <iptu_taxacoletalixo_riopardo> nAreaTotConstr: ' || nAreaTotConstr, lRaise);

     select 0.15::numeric(15, 2) * ( select i02_valor
                                       from infla
                                            inner join cfiptu on i02_codigo = j18_infla
                                      where extract (year from i02_data) = j18_anousu
                                   order by i02_data desc limit 1)::numeric(15,2) * nAreaTotConstr  into nLixo;

     perform(' <iptu_taxacoletalixo_riopardo> Valor Lixo: ' || nLixo, lRaise);

    end if;

    insert into tmptaxapercisen values (iReceita,iPercIsen,0,nLixo);

    if iPercIsen > 0 then
       nLixo := nLixo * (100 - iPercIsen) / 100;
    end if;

    perform fc_debug(' <iptu_taxacoletalixo_riopardo> iReceita: ' || iReceita || ' nLixo: ' || nLixo, lRaise);
    perform fc_debug(' <iptu_taxacoletalixo_riopardo> iHistCalc: ' || iHistCalc, lRaise);
    perform fc_debug(' <iptu_taxacoletalixo_riopardo> Inserindo tmptaxapercisen  - iReceita '||coalesce(iReceita,0)||' nLixo - '||coalesce(nLixo,0)||' iHistCalc - ' || coalesce(iHistCalc,0), lRaise);


    tSql := 'insert into tmprecval values ('||iReceita||','||nLixo||','||iHistCalc||',true)';
    execute tSql;

    return true;

end;
$$ language 'plpgsql';create or replace function fc_iptu_calculavvc_riopardo_2015(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as
$$
declare

    iMatricula                alias   for $1;
    iAnousu                   alias   for $2;
    lRaise                    alias   for $4;

    iNumerocontr              integer default 0;

    iZona                     integer;
    nTotAreaEdificada         numeric(15,2) default 0;
    nVlrVenalPredial          numeric(15,2) default 0;
    nVlrVenalPredialTot       numeric(15,2) default 0;

    tSql                      text    default '';
    lAtualiza                 boolean default true;

    nCorrecaoEstrutura        numeric(15,2);
    nCorrecaoEstado           numeric(15,2);
    nCorrecaoPadrao           numeric(15,2);
    nVm2                      numeric(15,2);
    nAliquota                 numeric(15,2);

    iPontuacao                integer;
    iSomatorioPontos          integer;
    iSomatorioLimite          integer;
    iCarSomatorioPontos       integer;
    iCarCategoria             integer;
    iCarSituacao              integer;
    iCarVM2                   integer;
    iCategoria                integer;
    iCaracteristicaDestinacao integer;

    r_Constr                  record;
    r_idConstr                record;
    r_TestaPontuacao          record;

    rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE;

begin

    perform fc_debug('', lRaise);
    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('* INICIANDO CALCULO DO VALOR VENAL DA CONSTRUCAO', lRaise);

    rtp_iptu_calculavvc.rnVvc       := 0;
    rtp_iptu_calculavvc.rnTotarea   := 0;
    rtp_iptu_calculavvc.riNumconstr := 0;
    rtp_iptu_calculavvc.rtDemo      := '';
    rtp_iptu_calculavvc.rtMsgerro   := 'Retorno ok' ;
    rtp_iptu_calculavvc.rbErro      := 'f';
    rtp_iptu_calculavvc.riCodErro   := 0;
    rtp_iptu_calculavvc.rtErro      := '';

    tSql := ' select distinct on (iptuconstr.j39_matric, j39_idcons)
                     iptuconstr.j39_matric,
                     j39_idcons,
                     j39_ano,
                     j39_area::numeric
                from iptuconstr
           where iptuconstr.j39_dtdemo is null
             and iptuconstr.j39_matric = '||iMatricula;

    perform fc_debug('Select buscando as contrucoes : '||tSql,lRaise,false,false);

    select j34_zona
      into izona
      from iptubase
           inner join lote on j01_idbql = j34_idbql
     where j01_matric = iMatricula;

    for r_Constr in execute tSql loop

     /**
      * Busca Caracteristica de destina��o e aliquota a ser aplicada
      */
      select j48_caract, j73_aliq
        into iCaracteristicaDestinacao, nAliquota
        from carconstr
             inner join caracter on j48_caract = j31_codigo
             inner join caraliq  on j48_caract = j73_caract
                                and j73_anousu = iAnousu
       where j48_matric = r_Constr.j39_matric
         and j48_idcons = r_Constr.j39_idcons
         and j31_grupo  = 61;

        if iCaracteristicaDestinacao is null then

          rtp_iptu_calculavvc.rnVvc       := 0;
          rtp_iptu_calculavvc.rnTotarea   := 0;
          rtp_iptu_calculavvc.riNumconstr := 0;
          rtp_iptu_calculavvc.rtMsgerro   := '61';
          rtp_iptu_calculavvc.riCodErro   := 102;
          rtp_iptu_calculavvc.rbErro      := 't';
          return rtp_iptu_calculavvc;
        end if;

        if nAliquota is null then

          rtp_iptu_calculavvc.rnVvc       := 0;
          rtp_iptu_calculavvc.rnTotarea   := 0;
          rtp_iptu_calculavvc.riNumconstr := 0;
          rtp_iptu_calculavvc.rtMsgerro   := '';
          rtp_iptu_calculavvc.riCodErro   := 13;
          rtp_iptu_calculavvc.rbErro      := 't';
          return rtp_iptu_calculavvc;
        end if;

       perform fc_debug(' <VVC> iCaracteristicaDestinacao: '||iCaracteristicaDestinacao||' nAliquota: ' || nAliquota, lRaise);

      select j48_caract
        into iCarSituacao
        from carconstr
             inner join caracter on j48_caract = j31_codigo
       where j48_matric = r_Constr.j39_matric
         and j48_idcons = r_Constr.j39_idcons
         and j31_grupo  = 58;

      if iCarSituacao is null then

        rtp_iptu_calculavvc.rnVvc       := 0;
        rtp_iptu_calculavvc.rnTotarea   := 0;
        rtp_iptu_calculavvc.riNumconstr := 1;
        rtp_iptu_calculavvc.rtDemo      := 'Configure a caracter�stica do grupo 58 para que o sistema possa descobrir a situa��o da edifica��o!';
        rtp_iptu_calculavvc.riCodErro   := 24;
        rtp_iptu_calculavvc.rbErro      := 'f';
        return rtp_iptu_calculavvc;
      end if;

      /**
       * Caracteristica de concluida - 192
       */
      if iCarSituacao in (192) then

        /**
         * Correcao estrutura
         */
        select j74_fator
          into nCorrecaoEstrutura
          from carconstr
               inner join caracter on j31_codigo = j48_caract
                                  and j31_grupo  = 27
               inner join carfator on j74_caract = j48_caract
                                  and j74_anousu = iAnousu
        where j48_matric = r_Constr.j39_matric
          and j48_idcons = r_Constr.j39_idcons;

        if nCorrecaoEstrutura is null then
          nCorrecaoEstrutura := 1;
        end if;

        /**
         * Correcao estado
         */
        select j74_fator
          into nCorrecaoEstado
          from carconstr
               inner join caracter on j31_codigo = j48_caract
                                  and j31_grupo  = 70
               inner join carfator on j74_caract = j48_caract
                                  and j74_anousu = iAnousu
        where j48_matric = r_Constr.j39_matric
          and j48_idcons = r_Constr.j39_idcons;

        if nCorrecaoEstado is null then
          nCorrecaoEstado := 1;
        end if;

        /**
         * Correcao padrao
         */
        select j74_fator
          into nCorrecaoPadrao
          from carconstr
               inner join caracter on j31_codigo = j48_caract
                                  and j31_grupo  = 71
               inner join carfator on j74_caract = j48_caract
                                  and j74_anousu = iAnousu
        where j48_matric = r_Constr.j39_matric
          and j48_idcons = r_Constr.j39_idcons;

        if nCorrecaoPadrao is null then
          nCorrecaoPadrao := 1;
        end if;

        for r_TestaPontuacao in select 70       as grupo
                                union select 71 as grupo
                                union select 73 as grupo
                                union select 74 as grupo
                                union select 75 as grupo
                                union select 76 loop
          perform *
             from carconstr
                  inner join caracter on j48_caract = j31_codigo
            where j48_matric = r_Constr.j39_matric
              and j48_idcons = r_Constr.j39_idcons
              and j31_grupo  = r_TestaPontuacao.grupo;

          if not found then

            rtp_iptu_calculavvc.rnVvc       := 0;
            rtp_iptu_calculavvc.rnTotarea   := 0;
            rtp_iptu_calculavvc.riNumconstr := 1;
            rtp_iptu_calculavvc.rtDemo      := 'Configure a caracter�stica do grupo ' || r_TestaPontuacao.grupo || ' para que o sistema possa calcular a pontua��o corretamente!';
            rtp_iptu_calculavvc.riCodErro   := 24;
            rtp_iptu_calculavvc.rbErro      := 'f';
            return rtp_iptu_calculavvc;
          end if;

        end loop;

        /**
         * Pontuacao
         */
        iPontuacao := 0;

        for r_idConstr in

          select j48_caract,j31_pontos, j31_grupo, j31_descr, j32_descr
            from carconstr
                 inner join caracter on j48_caract = j31_codigo
                 inner join cargrup  on j31_grupo = j32_grupo
           where j48_matric = r_Constr.j39_matric
             and j48_idcons = r_Constr.j39_idcons
             and j31_grupo in (70,71,73,74,75,76) loop

          perform fc_debug(rpad(upper(r_idconstr.j32_descr),30,'_') || '\t' || rpad(r_idconstr.j48_caract,3) || ' ' || rpad(rtrim(r_idconstr.j31_descr),25,'_') || '\t' || lpad(r_idconstr.j31_pontos,3),lRaise,false,false);

          iPontuacao := iPontuacao + r_idConstr.j31_pontos;

        end loop;

        select j48_caract
          into iCarSomatorioPontos
          from carconstr
               inner join caracter on j48_caract = j31_codigo
         where j48_matric = r_Constr.j39_matric
           and j48_idcons = r_Constr.j39_idcons
           and j31_grupo  = 57;

        select sum(j71_valor)
          from ( select ( select sum(j71_valor)
                            from carvalor
                           where j71_anousu = iAnousu
                             and j71_caract = iCarSomatorioPontos
                             and j71_ini    = j48_caract ) as j71_valor
                   from carconstr
                        inner join caracter on j48_caract = j31_codigo
                        inner join cargrup  on j31_grupo = j32_grupo
                  where j48_matric = r_Constr.j39_matric
                    and j48_idcons = r_Constr.j39_idcons
                    and j31_grupo in (72, 73, 74, 75, 76)
               ) as x
          into iSomatorioPontos;

        if iSomatorioPontos is null then

          rtp_iptu_calculavvc.rnVvc       := 0;
          rtp_iptu_calculavvc.rnTotarea   := 0;
          rtp_iptu_calculavvc.riNumconstr := 1;
          rtp_iptu_calculavvc.rtDemo      := 'Somat�rio de pontos n�o executado. Verifique tabela!';
          rtp_iptu_calculavvc.riCodErro   := 23;
          rtp_iptu_calculavvc.rbErro      := 'f';
          return rtp_iptu_calculavvc;
        end if;

        if iCarSomatorioPontos in (171,181,179,178,172,170,257,175,176,182,184) then

          if iSomatorioPontos > 100 then
            iSomatorioPontos := 100;
          end if;
          iSomatorioLimite := 100;
        elsif iCarSomatorioPontos in (180,174) then

          if iSomatorioPontos > 80 then
            iSomatorioPontos := 80;
          end if;
          iSomatorioLimite := 80;
        elsif iCarSomatorioPontos in (183) then

          if iSomatorioPontos > 30 then
            iSomatorioPontos := 30;
          end if;
          iSomatorioLimite := 30;
        end if;

        select j48_caract
          into iCarCategoria
          from carconstr
               inner join caracter on j48_caract = j31_codigo
         where j48_matric = r_Constr.j39_matric
           and j48_idcons = r_Constr.j39_idcons
           and j31_grupo  = 79;

        if iCarCategoria is null then

          rtp_iptu_calculavvc.rnVvc       := 0;
          rtp_iptu_calculavvc.rnTotarea   := 0;
          rtp_iptu_calculavvc.riNumconstr := 1;
          rtp_iptu_calculavvc.rtDemo      := 'Configure a caracter�stica do grupo 79 para que o sistema possa descobrir a categoria da edifica��o!';
          rtp_iptu_calculavvc.riCodErro   := 24;
          rtp_iptu_calculavvc.rbErro      := 'f';
          return rtp_iptu_calculavvc;
        end if;

        /**
         * 401 - Alvenaria
         */
        if iCarCategoria = 401 then

          if iPontuacao <= 9 then

            iCategoria := 3;
            iCarVM2    := 361;
          elsif iPontuacao <= 12 then

            iCategoria := 2;
            iCarVM2    := 360;
          elsif iPontuacao <= 15 then

            iCategoria := 1;
            iCarVM2    := 362;
          else

            iCategoria := 0;
            iCarVM2    := 363;
          end if;

        /**
         * 402 - Metallica/concreto
         */
        elsif iCarCategoria = 402 then

          if iPontuacao <= 9 then

            iCategoria := 3;
            iCarVM2    := 383;
          elsif iPontuacao <= 12 then

            iCategoria := 2;
            iCarVM2    := 380;
          elsif iPontuacao <= 15 then

            iCategoria := 1;
            iCarVM2    := 381;
          else

            iCategoria := 0;
            iCarVM2    := 382;
          end if;

        /**
         * 403 - Fibra
         */
        elsif iCarCategoria = 403 then

          if iPontuacao <= 9 then

            iCategoria := 2;
            iCarVM2    := 400;
          else

            iCategoria := 1;
            iCarVM2    := 399;
          end if;

        /**
         * 404 - Mista
         */
        elsif iCarCategoria = 404 then

          if iPontuacao <= 7 then

            iCategoria := 3;
            iCarVM2    := 393;
          elsif iPontuacao <= 9then

            iCategoria := 2;
            iCarVM2    := 390;
          elsif iPontuacao <= 11 then

            iCategoria := 1;
            iCarVM2    := 391;
          else

            iCategoria := 0;
            iCarVM2    := 392;
          end if;

        /**
         * 405 - Madeira
         */
        elsif iCarCategoria = 405 then

          if iPontuacao <= 6 then

            iCategoria := 3;
            iCarVM2    := 371;
          elsif iPontuacao <= 8 then

            iCategoria := 2;
            iCarVM2    := 370;
          elsif iPontuacao <= 10 then

            iCategoria := 1;
            iCarVM2    := 372;
          else

            iCategoria := 0;
            iCarVM2    := 373;
          end if;

        end if;

        select j74_fator
          into nVm2
          from carfator
         where j74_anousu = iAnousu
           and j74_caract = iCarVM2;

        if nVm2 is null or nVm2 = 0 then

          rtp_iptu_calculavvc.rnVvc       := 0;
          rtp_iptu_calculavvc.rnTotarea   := 0;
          rtp_iptu_calculavvc.riNumconstr := 1;
          rtp_iptu_calculavvc.rtDemo      := 'Valor do m2 da construcao zerado';
          rtp_iptu_calculavvc.riCodErro   := 10;
          rtp_iptu_calculavvc.rbErro      := 'f';
          return rtp_iptu_calculavvc;
        end if;

        perform fc_debug(' <VVC> idcons '||r_Constr.j39_idcons||' / nVm2 '||nVm2, lRaise);

        nVm2 := nVm2 * nCorrecaoEstrutura * nCorrecaoEstado * nCorrecaoPadrao;

        perform fc_debug(' <VVC> Formula para Vm2', lRaise);
        perform fc_debug(' <VVC> nVm2 * nCorrecaoEstrutura * nCorrecaoEstado * nCorrecaoPadrao', lRaise);
        perform fc_debug(' <VVC> nVm2 = '||nVm2||' * '||nCorrecaoEstrutura||' * '||nCorrecaoEstado||' * '||nCorrecaoPadrao, lRaise);

        nVlrVenalPredial = nVm2 * r_constr.j39_area * (iSomatorioPontos::numeric(15,2) / iSomatorioLimite::numeric(15,2));

        perform fc_debug(' <VVC> Formula para Valor Venal da Constru�ao', lRaise);
        perform fc_debug(' <VVC> nVlrVenalPredial = nVm2 * area * (iSomatorioPontos / iSomatorioLimite)', lRaise);
        perform fc_debug(' <VVC> nVlrVenalPredial = '||nVm2||' * '||r_constr.j39_area||' * ('||iSomatorioPontos::numeric(15,2)||' / '||iSomatorioLimite::numeric(15,2)||')', lRaise);

        nTotAreaEdificada := nTotAreaEdificada + r_constr.j39_area;
        iNumerocontr       = iNumerocontr + 1;

        nVlrVenalPredialTot = nVlrVenalPredialTot + nVlrVenalPredial;

        insert into tmpiptucale (anousu, matric,idcons, areaed, vm2, pontos, valor, caracteristica, aliquota)
                         values (iAnousu,iMatricula, r_Constr.j39_idcons, r_Constr.j39_area, nVm2, iSomatorioPontos, nVlrVenalPredial, iCaracteristicaDestinacao, nAliquota);

        if lAtualiza then
           update tmpdadosiptu set predial = true;
           lAtualiza = false;
        end if;

      end if;

    end loop;

    perform fc_debug(' <VVC> Valor total venal predial : '||coalesce(nVlrVenalPredialTot,0),lRaise,false,false);

    rtp_iptu_calculavvc.rnVvc       := nVlrVenalPredialTot;
    rtp_iptu_calculavvc.rnTotarea   := nTotAreaEdificada;
    rtp_iptu_calculavvc.riNumconstr := iNumerocontr;
    rtp_iptu_calculavvc.rtDemo      := '';
    rtp_iptu_calculavvc.riCodErro   := 0;
    rtp_iptu_calculavvc.rbErro      := 'f';

    update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc;
    return rtp_iptu_calculavvc;

end;
$$  language 'plpgsql';create or replace function fc_iptu_calculavvt_riopardo_2015(integer,integer,integer,numeric,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$
declare

    iMatricula           alias for $1;
    iIdbql               alias for $2;
    iAnousu              alias for $3;
    nFracao              alias for $4;
    nAreaLote            alias for $5;
    lMostrademonstrativo alias for $6;
    lRaise               alias for $7;

    iZona                integer;
    iFace                integer;
    iCarPavimentacao     integer;
    iCarMuro             integer;
    iCarPasseio          integer;
    iCarLimpo            integer;
    iCarCalcada          integer;

    iMultiplicadorTrib   numeric(15,2);

    nAreaConstruida      numeric(15,2);
    nVlrM2Terreno        numeric(15,2);
    nAreaLoteIsento      numeric(15,2);
    nAreaReallote        numeric(15,2);
    nTestada             numeric(15,2);
    nProfundMedia        numeric(15,2);
    nMajoracao           numeric(15,2) default 0;

    nAreaTributavel      numeric(15,2);

    nAreaTotLote         numeric(15,2);
    nVlrVenalTerreno     numeric(15,2);

    nCorrecaoSituacao    numeric(15,2);
    nCorrecaoTopografia  numeric(15,2);
    nCorrecaoPedologia   numeric(15,2);

    tRetorno             text default '';

    rtp_iptu_calculavvt  tp_iptu_calculavvt%ROWTYPE;

begin

    rtp_iptu_calculavvt.rnVvt        := 0;
    rtp_iptu_calculavvt.rnAreaTotalC := 0;
    rtp_iptu_calculavvt.rnArea       := 0;
    rtp_iptu_calculavvt.rnTestada    := 0;
    rtp_iptu_calculavvt.rtDemo       := '';
    rtp_iptu_calculavvt.rtMsgerro    := '';
    rtp_iptu_calculavvt.rbErro       := 'f';
    rtp_iptu_calculavvt.riCoderro    := 0;
    rtp_iptu_calculavvt.rtErro       := '';

    perform fc_debug('' || lpad('',60,'-'), lRaise, false, false);
    perform fc_debug(' <VVT> INICIANDO CALCULO DO VALOR VENAL TERRITORIAL',lRaise);

    select j34_zona
      into iZona
      from lote where j34_idbql = iIdbql;

    select j51_valorm2t
      into nVlrM2Terreno
      from zonasvalor
     where j51_anousu = iAnousu
       and j51_zona   = iZona;

    perform fc_debug(' <VVT> nVlrM2Terreno: ' || nVlrM2Terreno, lRaise);

    select rnarealo
      into nAreaLoteIsento
      from fc_iptu_verificaisencoes(iMatricula, iAnousu, lMostrademonstrativo, lRaise);

    if nAreaLoteIsento > 0 then

      perform fc_debug(' <VVT> AREA REAL DO LOTE: ' || nAreaLote, lRaise);
      nAreaRealLote = nAreaLote - nAreaLoteIsento;
      if nAreaRealLote < 0 then

        rtp_iptu_calculavvt.rnVvt     := 0;
        rtp_iptu_calculavvt.rnArea    := 0;
        rtp_iptu_calculavvt.rtDemo    := '';
        rtp_iptu_calculavvt.rtMsgerro := 'Area real do lote n�o pode ser menor que 0 (zero)';
        rtp_iptu_calculavvt.riCodErro := 17;
        rtp_iptu_calculavvt.rbErro    := 't';
        return rtp_iptu_calculavvt;
      end if;

      perform fc_debug(' <VVT> AREA ISENTA DO LOTE: ' || nAreaLoteIsento,lRaise);
    else

      nAreaRealLote = nAreaLote;
    end if;

    select j49_face, case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
      into iFace, nTestada
      from testpri
           inner join face    on j49_face  = j37_face
           inner join testada on j49_face  = j36_face
                             and j49_idbql = j36_idbql
     where j49_idbql = iIdbql;

    if iFace is null then

      rtp_iptu_calculavvt.rnVvt     := 0;
      rtp_iptu_calculavvt.rnArea    := 0;
      rtp_iptu_calculavvt.rtDemo    := '';
      rtp_iptu_calculavvt.rtMsgerro := 'testada principal do lote n�o cadastrada';
      rtp_iptu_calculavvt.riCodErro := 6;
      rtp_iptu_calculavvt.rbErro    := 't';
      return rtp_iptu_calculavvt;
    end if;

    nProfundMedia := round(nAreaRealLote / nTestada,0);

    if nAreaRealLote <= 360 or nProfundMedia <= 30 then

      perform fc_debug(' <VVT> Utilizando area real do lote (nAreaTributavel): ' || nAreaRealLote, lRaise);

      nAreaTributavel := nAreaRealLote;
    else

      perform fc_debug(' <VVT> nAreaRealLote: ' || nAreaRealLote, lRaise);

      if    nAreaRealLote between 360.01 and 600  then
        iMultiplicadorTrib := 0.50;
      elsif nAreaRealLote between 600.01 and 1000 then
        iMultiplicadorTrib := 0.40;
      elsif nAreaRealLote between 1000.01 and 3000 then
        iMultiplicadorTrib := 0.30;
      elsif nAreaRealLote between 3000.01 and 10000 then
        iMultiplicadorTrib := 0.20;
      elsif nAreaRealLote between 10000.01 and 50000 then
        iMultiplicadorTrib := 0.10;
      elsif nAreaRealLote between 50000.01 and 100000 then
        iMultiplicadorTrib := 0.20;
      elsif nAreaRealLote between 100000.01 and 200000 then
        iMultiplicadorTrib := 0.30;
      elsif nAreaRealLote > 200000 then
        iMultiplicadorTrib := 0.40;
      end if;

      perform fc_debug(' <VVT> iMultiplicadorTrib: ' || iMultiplicadorTrib, lRaise);

      perform fc_debug(' <VVT> Calculando nAreaTributavel: nTestada * 30 + (nAreaRealLote - nTestada * 30) * iMultiplicadorTrib ', lRaise);
      perform fc_debug(' <VVT> Valores -> '||coalesce(nTestada,0)||' * 30 + ('||coalesce(nAreaRealLote,0)||' - '||coalesce(nTestada,0)||' * 30) * '||coalesce(iMultiplicadorTrib,0), lRaise);

      nAreaTributavel := nTestada * 30 + (nAreaRealLote - nTestada * 30) * iMultiplicadorTrib;

    end if;

    /**
     * Correcao Situacao
     */
    select j74_fator
      into nCorrecaoSituacao
      from carlote
           inner join caracter on j31_codigo = j35_caract
                              and j31_grupo = 33
           inner join carfator on j74_caract = j35_caract
                              and j74_anousu = iAnousu
    where j35_idbql = iIdbql;

    perform fc_debug(' <VVT> nCorrecaoSituacao: ' || coalesce(nCorrecaoSituacao, 0), lRaise);

    if nCorrecaoSituacao is null then
      nCorrecaoSituacao := 1;
    end if;

    perform fc_debug(' <VVT> Calculo Correcao nVlrM2Terreno: nVlrM2Terreno * nCorrecaoSituacao', lRaise);
    perform fc_debug(' <VVT> Valores -> ' || coalesce(nVlrM2Terreno,0) || ' - nCorrecaoSituacao: ' || coalesce(nCorrecaoSituacao,0), lRaise);

    nVlrM2Terreno := nVlrM2Terreno * nCorrecaoSituacao;

    /**
     * Correcao Topografia
     */
    select j74_fator
      into nCorrecaoTopografia
      from carlote
           inner join caracter on j31_codigo = j35_caract
                              and j31_grupo  = 32
           inner join carfator on j74_caract = j35_caract
                              and j74_anousu = iAnousu
    where j35_idbql = iIdbql;

    perform fc_debug(' <VVT> nCorrecaoTopografia: ' || coalesce(nCorrecaoTopografia, 0), lRaise);

    if nCorrecaoTopografia is null then
      nCorrecaoTopografia := 1;
    end if;

    perform fc_debug(' <VVT> Calculo Correcao nVlrM2Terreno: nVlrM2Terreno * nCorrecaoTopografia', lRaise);
    perform fc_debug(' <VVT> Valores -> ' || coalesce(nVlrM2Terreno,0) || ' - nCorrecaoTopografia: ' || coalesce(nCorrecaoTopografia,0), lRaise);

    nVlrM2Terreno := nVlrM2Terreno * nCorrecaoTopografia;

    /**
     * Correcao Pedologia
     */
    select j74_fator
      into nCorrecaoPedologia
      from carlote
           inner join caracter on j31_codigo = j35_caract
                              and j31_grupo = 34
           inner join carfator on j74_caract = j35_caract
                              and j74_anousu = iAnousu
    where j35_idbql = iIdbql;

    perform fc_debug(' <VVT> nCorrecaoPedologia: ' || coalesce(nCorrecaoTopografia, 0), lRaise);

    if nCorrecaoPedologia is null then
      nCorrecaoPedologia := 1;
    end if;

    perform fc_debug(' <VVT> Calculo Correcao Pedologia nVlrM2Terreno: nVlrM2Terreno * nCorrecaoPedologia', lRaise);
    perform fc_debug(' <VVT> Valores -> ' || coalesce(nVlrM2Terreno,0) || ' - nCorrecaoPedologia: ' || coalesce(nCorrecaoPedologia,0), lRaise);

    nVlrM2Terreno := nVlrM2Terreno * nCorrecaoPedologia;

    perform fc_debug('Valor do M2 do Terreno ao final das correcoes: ' || coalesce(nVlrM2Terreno, 0), lRaise);

    perform fc_debug(' ', lRaise);
    perform fc_debug(' <VVT> Valor de correc�es aplicadas: ', lRaise);
    perform fc_debug(' <VVT> nCorrecaoSituacao: '   || nCorrecaoSituacao,   lRaise);
    perform fc_debug(' <VVT> nCorrecaoTopografia: ' || nCorrecaoTopografia, lRaise);
    perform fc_debug(' <VVT> nCorrecaoPedologia: '  || nCorrecaoPedologia,  lRaise);

    /**
     * Verifica se h� caracteristica de pavimentacao lancada
     */
    select j35_caract
      into iCarPavimentacao
      from carlote
           inner join caracter on j31_codigo = j35_caract
                              and j31_grupo  = 47
    where j35_idbql = iIdbql;

    if iCarPavimentacao is null then

      rtp_iptu_calculavvt.rnVvt     := 0;
      rtp_iptu_calculavvt.rnArea    := 0;
      rtp_iptu_calculavvt.rtDemo    := '';
      rtp_iptu_calculavvt.rtMsgerro := 'sem caracter�stica lancada no grupo 47 (pavimenta��o)';
      rtp_iptu_calculavvt.riCodErro := 24;
      rtp_iptu_calculavvt.rbErro    := 't';
      return rtp_iptu_calculavvt;
    end if;

    /**
     * Retorna area construida da matricula
     */
    select fc_iptu_getareaconstrmat(iMatricula) into nAreaConstruida;

    if nAreaConstruida is null then
      nAreaConstruida = 0;
    end if;

    perform fc_debug(' <VVT> nAreaConstruida: '  || nAreaConstruida,  lRaise);

    /**
     * Verifica Majoracao Territorial
     */
    if iCarPavimentacao in (114, 115, 117, 118) and nAreaConstruida = 0 then

      perform fc_debug(' <VVT> Territorial',  lRaise);

      select j35_caract
        into iCarMuro
        from carlote
             inner join caracter on j31_codigo = j35_caract
                                and j31_grupo  = 14
      where j35_idbql = iIdbql;

      /**
       * SEM muro
       */
      if iCarMuro = 44 then

        nMajoracao := 30;
        perform fc_debug(' <VVT> Majoracao 30% - sem muro',  lRaise);
      end if;

      select j35_caract
        into iCarPasseio
        from carlote
             inner join caracter on j31_codigo = j35_caract
                                and j31_grupo  = 16
      where j35_idbql = iIdbql;

      /**
       * SEM passeio
       */
      if iCarPasseio = 36 then

        nMajoracao := nMajoracao + 30;
        perform fc_debug(' <VVT> Majoracao 30% - sem passeio',  lRaise);
      end if;

    end if;

    /**
     * Verifica Majoracao predial
     */
    if iCarPavimentacao in (114, 115, 117, 118) and nAreaConstruida > 0 then

      perform fc_debug(' <VVT> Predial',  lRaise);

      select j35_caract
        into iCarPasseio
        from carlote
             inner join caracter on j31_codigo = j35_caract
                                and j31_grupo  = 16
      where j35_idbql = iIdbql;

      /**
       * SEM passeio
       */
      if iCarPasseio = 36 then

        nMajoracao := 50;
        perform fc_debug(' <VVT> Majoracao 50% - sem passeio', lRaise);
      end if;

    end if;

    /**
     * Aplica Majoracao
     */
    perform fc_debug(' <VVT> nMajoracao:' || coalesce(nMajoracao, 0), lRaise);

    if nMajoracao > 0 then

      perform fc_debug(' <VVT> Majoracao <> de 0 entao aplica no valor do M2 do terreno', lRaise);
      perform fc_debug(' <VVT> Formular -> nVlrM2Terreno + round( ( nVlrM2Terreno * nMajoracao / 100), 2)', lRaise);
      perform fc_debug(' <VVT> Valores  -> '||coalesce(nVlrM2Terreno,0)||' + round( ( '||coalesce(nVlrM2Terreno,0)||' * '||coalesce(nMajoracao,0)||' / 100), 2)', lRaise);
      nVlrM2Terreno := nVlrM2Terreno + round( ( nVlrM2Terreno * nMajoracao / 100), 2);
    end if;

    nVlrVenalTerreno := nVlrM2Terreno * nAreaTributavel * (nFracao / 100);

    perform fc_debug(' <VVT> AREA DO LOTE UTILIZADA PARA CALCULO: '   || nAreaRealLote,lRaise);
    perform fc_debug(' <VVT> TESTADA TRIBUTADA: '                     || nTestada || ' METROS',lRaise);
    perform fc_debug(' <VVT> PROFUNDIDADE (AREA DO LOTE / TESTADA): ' || nProfundMedia,lRaise);
    perform fc_debug(' <VVT> VALOR VENAL TERRENO: '                   || nVlrVenalTerreno || ' - FRACAO: ' || nFracao,lRaise);
    perform fc_debug(' <VVT> Valor m2 do terreno encontrado : '       ||coalesce(nVlrM2Terreno,0),lRaise);

    if not found or nVlrM2Terreno = 0 then

      rtp_iptu_calculavvt.rnVvt     := 0;
      rtp_iptu_calculavvt.rnArea    := 0;
      rtp_iptu_calculavvt.rtDemo    := '';
      rtp_iptu_calculavvt.rtMsgerro := 'Valor do m2 do terreno n�o configurado';
      rtp_iptu_calculavvt.riCodErro := 7;
      rtp_iptu_calculavvt.rbErro    := 't';
      return rtp_iptu_calculavvt;
    end if;

    nAreaTotLote := ( nAreaTributavel * (nFracao / 100) );
    perform fc_debug(' <VVT> nAreaTotLote: ' || coalesce(nAreaTotLote, 0), lRaise);

    rtp_iptu_calculavvt.rnVvt     := nVlrVenalTerreno;
    rtp_iptu_calculavvt.rnArea    := nAreaTotLote;
    rtp_iptu_calculavvt.rtDemo    := '';
    rtp_iptu_calculavvt.rtMsgerro := '';
    rtp_iptu_calculavvt.riCodErro := 0;
    rtp_iptu_calculavvt.rbErro    := 'f';

    update tmpdadosiptu
       set vvt   = rtp_iptu_calculavvt.rnVvt,
           vm2t  = nVlrM2Terreno,
           areat = nAreaTotLote;

    return rtp_iptu_calculavvt;

end;
$$ language 'plpgsql';create or replace function fc_iptu_getaliquota_riopardo_2015(integer,integer,integer,boolean,integer,boolean) returns numeric as
$$
declare

    iMatricula         alias for $1;
    iIdbql             alias for $2;
    lPredial           alias for $4;
    iAnousu            alias for $5;
    lRaise             alias for $6;

    nAliquota          numeric default 0;

begin

  perform fc_debug( '< GET ALIQUOTA >DEFININDO ALIQUOTA A APLICAR' , lRaise);
  perform fc_debug( '< GET ALIQUOTA >IPTU: ' || case when lPredial is true then 'PREDIAL' else 'TERRITORIAL' end, lRaise);

  if lPredial is true then

    select j73_aliq
      into nAliquota
      from carconstr
           inner join caraliq    on j73_anousu = iAnousu
                                and j73_caract = j48_caract
           left  join iptuconstr on j48_idcons = j39_idcons
                                and j48_matric = j39_matric
      where j48_matric = iMatricula
        and j39_dtdemo is null
        and j39_idprinc is true
   order by j73_aliq
    limit 1;

  else
    /*Aliquota padrao para IPTU territorial*/
    nAliquota := 3;
  end if;

  perform fc_debug('< GET ALIQUOTA >Aliquota Final :' || nAliquota, lRaise );
  execute 'update tmpdadosiptu set aliq = ' || nAliquota;
  return nAliquota;

end;
$$ language 'plpgsql';/**
 * Fun��o para buscar endere�o de matricula conforme regra configurada
 *
 * @param iMatricula        integer  Matricula
 * @param iTratamento       integer  Regra de retorno do endere�o da matricula
 *
 * @return text             varchar  String com o endere�o da matricula conforme regra
 */
create or replace function fc_iptuender(integer, integer) returns varchar(200) as
$$
declare

   iMatric        alias for $1;
   iTratamento    alias for $2;

   lRaise         boolean default false;
   rtp_IptuEnder  tp_iptuender%ROWTYPE;
   iNumCgm        integer default 0;
   iRegra         integer default 2;   --regra de entraga
   sOrdemExec     varchar default null;
   iAnoUsu        integer default null;
   iInstit        integer default null;
   aOrdem         text[];
   iTotPos        integer;
   iInd           integer;
   aFuncoes       text[][2];
   rRetornofc     record;
   sSql           varchar;
   sEndereco      varchar;
   funcao         varchar;

begin

   iAnoUsu := cast(fc_getsession('DB_anousu') as integer);
   iInstit := cast(fc_getsession('DB_instit') as integer);
   lRaise  := (case when fc_getsession('DB_debugon') is null then false else true end);

   perform fc_debug('In�cio da consulta - matricula: '||iMatric,lRaise,true,false);

   /**
    * Buscamos a regra escolhida pelo usuario
    */
   if iTratamento is null or iTratamento not between 1 and 7 then

     select j18_ordendent
       into iRegra
       from cfiptu
      where j18_anousu = iAnoUsu;
   else

    perform fc_debug('Regra de Tratamento informada: ' || coalesce(iTratamento, 0), lRaise);
    iRegra := iTratamento;
   end if;

   if iRegra is null then
     iRegra := 2;
   end if;

   perform fc_debug('Regra utilizada: '||iRegra,lRaise);

   /**
    * Tipos de endereco:
    *
    * 1 - Endereco Imobiliario;
    * 2 - Zona de Entrega
    * 3 - endereco da construcao (predial)
    * 4 - Endereco do terreno - baldio'
    * 5 - Endereco do CGM,
    * 6 - Endereco de Entrega
    */
   sordemexec := case
                 when iRegra = 1 then '1,2,6,5,3'
                 when iRegra = 2 then '1,2,6,3,5'
                 when iRegra = 3 then '1,2,6,3'
                 when iRegra = 4 then '1,2,5,3'
                 when iRegra = 5 then '6,3,5'
                 when iRegra = 6 then '6,5,3'
                 when iRegra = 7 then '4,3'
               end;

   -- Array com o codigo das funcoes:
   aFuncoes := array[['1','fc_iptuenderimob','0'],
                     ['2','fc_iptuenderzonaentrega','0'],
                     ['3','fc_iptuenderpredial','0'],
                     ['4','fc_iptuenderterreno','1'],
                     ['5','fc_iptuendercgm','0'],
                     ['6','fc_iptuenderentrega','0']];

   aOrdem  := string_to_array(sOrdemExec,',');
   iTotPos := array_upper(aOrdem,1)::integer;

   for iInd in 1..iTotPos loop

    perform fc_debug('Funcao Executada: '||aFuncoes[aOrdem[iInd]::integer][2],lRaise,false,false);

    execute  'select  * from '||aFuncoes[aOrdem[iInd]::integer][2]||'('||iMatric||')' into rRetornofc;

    if found then

        -- fazemos algumas validacoes no endereco.
        -- e obrigatorio ter: endereco e numero, ou caixa postal.
        if length(trim(rRetornofc.rsEndereco)) >= 1
           and ((trim(rRetornofc.rsNumero) <> '0' and rRetornofc.rsNumero is not null) or rRetornofc.rsEndereco ~ '[0-9]+')
           and (rRetornofc.rsNumero is not null or rRetornofc.rsEndereco ~ '[0-9]+')
           or (length(trim(rRetornofc.riCxPost::text)) > 0 and rRetornofc.riCxPost > 0 )
           or ( length(trim(rRetornofc.rsEndereco)) >= 1 and aFuncoes[aOrdem[iInd]::integer][3] = '1') then

           sEndereco :=              rpad(trim(coalesce(rRetornofc.rsEndereco     ,'')),40) || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsNumero       ,'')),10) ||'#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsCompl        ,'')),20) || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsBairro       ,'')),40) || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsMunic        ,'')),40) || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsUf           ,'')),2)  || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsCep          ,'')),8)  || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.riCxPost::text ,'0')),20) || '#';
           sEndereco := sEndereco || rpad(trim(coalesce(rRetornofc.rsDestinatario ,'')),40);

           if (length(sEndereco) > 0) then

              if lRaise is true then
                raise notice '%', fc_debug('Funcao com endereco correto:'||aFuncoes[aOrdem[iInd]::integer][2],lRaise,false,true);
              end if;

             return sEndereco;
           end if;

        end if;

      end if;

   end loop;

   if lRaise is true then
    raise notice '%', fc_debug(' Funcao com endereco correto:'||aFuncoes[aOrdem[iInd]::integer],lRaise,false,true);
   end if;

return rpad(' ',200);
end;
$$
language 'plpgsql';

/**
 * Wrapper da iptuender para utilizar apenas a matricula
 *
 * @param iMatricula        integer  Matricula
 *
 * @return text             varchar  String com o endere�o da matricula conforme regra
 */
create or replace function fc_iptuender(integer) returns varchar(200) as
$$
declare

   iMatricula      alias for $1;

begin

  return fc_iptuender( iMatricula, null );

end;
$$ language 'plpgsql';insert into db_versaoant (db31_codver,db31_data) values (350, current_date);
select setval ('db_versaousu_db32_codusu_seq',(select max (db32_codusu) from db_versaousu));
select setval ('db_versaousutarefa_db28_sequencial_seq',(select max (db28_sequencial) from db_versaousutarefa));
select setval ('db_versaocpd_db33_codcpd_seq',(select max (db33_codcpd) from db_versaocpd));
select setval ('db_versaocpdarq_db34_codarq_seq',(select max (db34_codarq) from db_versaocpdarq));create table bkp_db_permissao_20150108_092835 as select * from db_permissao;
create temp table w_perm_filhos as 
select distinct 
       i.id_item        as filho, 
       p.id_usuario     as id_usuario, 
       p.permissaoativa as permissaoativa, 
       p.anousu         as anousu, 
       p.id_instit      as id_instit, 
       m.modulo         as id_modulo  
  from db_itensmenu i  
       inner join db_menu      m  on m.id_item_filho = i.id_item 
       inner join db_permissao p  on p.id_item       = m.id_item_filho 
                                 and p.id_modulo     = m.modulo 
 where coalesce(i.libcliente, false) is true;

create index w_perm_filhos_in on w_perm_filhos(filho);

create temp table w_semperm_pai as 
select distinct m.id_item       as pai, m.id_item_filho as filho 
  from db_itensmenu i 
       inner join db_menu            m  on m.id_item   = i.id_item 
       left  outer join db_permissao p  on p.id_item   = m.id_item 
                                       and p.id_modulo = m.modulo 
 where p.id_item is null 
   and coalesce(i.libcliente, false) is true;
create index w_semperm_pai_in on w_semperm_pai(filho);
insert into db_permissao (id_usuario,id_item,permissaoativa,anousu,id_instit,id_modulo) 
select distinct wf.id_usuario, wp.pai, wf.permissaoativa, wf.anousu, wf.id_instit, wf.id_modulo 
  from w_semperm_pai wp 
       inner join w_perm_filhos wf on wf.filho = wp.filho 
       where not exists (select 1 from db_permissao p 
                    where p.id_usuario = wf.id_usuario 
                      and p.id_item    = wp.pai 
                      and p.anousu     = wf.anousu 
                      and p.id_instit  = wf.id_instit 
                      and p.id_modulo  = wf.id_modulo); 
delete from db_permissao
 where not exists (select a.id_item 
                     from db_menu a 
                    where a.modulo = db_permissao.id_modulo 
                      and (a.id_item       = db_permissao.id_item or 
                           a.id_item_filho = db_permissao.id_item) );
delete from db_itensfilho    
 where not exists (select 1 from db_arquivos where db_arquivos.codfilho = db_itensfilho.codfilho);

CREATE FUNCTION acerta_permissao_hierarquia() RETURNS varchar AS $$ 

 declare  

   i integer default 1; 

   BEGIN 

  while i < 5 loop   

    insert into db_permissao select distinct 
                                 db_permissao.id_usuario, 
                                 db_menu.id_item, 
                                 db_permissao.permissaoativa, 
                                 db_permissao.anousu, 
                                 db_permissao.id_instit, 
                                 db_permissao.id_modulo 
                            from db_permissao 
                                 inner join db_menu on db_menu.id_item_filho = db_permissao.id_item 
                                                   and db_menu.modulo        = db_permissao.id_modulo 
                           where not exists ( select 1 
                                                from db_permissao as p 
                                               where p.id_item    = db_menu.id_item 
                                                 and p.id_usuario = db_permissao.id_usuario 
                                                 and p.anousu     = db_permissao.anousu 
                                                 and p.id_instit  = db_permissao.id_instit 
                                                 and p.id_modulo  = db_permissao.id_modulo );

  i := i+1; 

 end loop;

return 'Processo concluido com sucesso!';
END; 
$$ LANGUAGE 'plpgsql' ;

select acerta_permissao_hierarquia();
drop function acerta_permissao_hierarquia();create or replace function fc_executa_ddl(text) returns boolean as $$ 
  declare  
    sDDL     alias for $1;
    lRetorno boolean default true;
  begin   
    begin 
      EXECUTE sDDL;
    exception 
      when others then 
        raise info 'Error Code: % - %', SQLSTATE, SQLERRM;
        lRetorno := false;
    end;  
    return lRetorno;
  end; 
  $$ language plpgsql ;

  select fc_executa_ddl('ALTER TABLE '||quote_ident(table_schema)||'.'||quote_ident(table_name)||' ENABLE TRIGGER ALL;') 
  from information_schema.tables 
   where table_schema not in ('pg_catalog', 'pg_toast', 'information_schema')
     and table_schema !~ '^pg_temp'
     and table_type = 'BASE TABLE'
   order by table_schema, table_name;

                                                                                                       
SELECT CASE WHEN EXISTS (SELECT 1 FROM pg_authid WHERE rolname = 'dbseller')                           
  THEN fc_grant('dbseller', 'select', '%', '%') ELSE -1 END;                                           
SELECT CASE WHEN EXISTS (SELECT 1 FROM pg_authid WHERE rolname = 'plugin')                             
  THEN fc_grant('plugin', 'select', '%', '%') ELSE -1 END;                                             
SELECT fc_executa_ddl('GRANT CREATE ON TABLESPACE '||spcname||' TO dbseller;')                         
  FROM pg_tablespace                                                                                   
 WHERE spcname !~ '^pg_' AND EXISTS (SELECT 1 FROM pg_authid WHERE rolname = 'dbseller');              
                                                                                                       
  delete from db_versaoant where not exists (select 1 from db_versao where db30_codver = db31_codver); 
  delete from db_versaousu where not exists (select 1 from db_versao where db30_codver = db32_codver); 
  delete from db_versaocpd where not exists (select 1 from db_versao where db30_codver = db33_codver); 
                                                                                                       
/*select fc_schemas_dbportal();*/
