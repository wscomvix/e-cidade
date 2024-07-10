create or replace function fc_dotacaosaldo(integer,integer,integer,date,date)
returns varchar
as '
DECLARE

  ANOUSU   	ALIAS FOR $1;
  CODDOT  	ALIAS FOR $2;
  TIPO     	ALIAS FOR $3;
    -- 1 SALDO INICIAL DA DOTACAO - ORCAMENTO
    -- 2 SALDO DA DOTACAO  com reserva POR EMP, LIQ, PAG ... POR MES
    -- 3 SALDO DA DOTACAO  PELA CONTABILIDADE ...
    -- 4 SALDO ACUMULADO POR MES
  DATAUSU	ALIAS FOR $4;
  DATAFIM	ALIAS FOR $5;

  VALORINI  	FLOAT8 DEFAULT 0;
  VALOR  	FLOAT8 DEFAULT 0;

  -- VARIAVEL PARA VALOR DA DOTACAO
  VALORRES	                    FLOAT8 DEFAULT 0;

  -- VARIAVEL PARA VALORES RESERVADOS ATE A DATA INFORMADA (FINAL)
  RESERVADO_MANUAL_ATE_DATA	    FLOAT8 DEFAULT 0;
  RESERVADO_AUTOMATICO_ATE_DATA	FLOAT8 DEFAULT 0;
  RESERVADO_ATE_DATA            FLOAT8 DEFAULT 0;

  -- VARIAVEL PARA VALOR DAS RESERVAS DE SALDO
  DATAINI 	DATE;

  SALDO_ANTERIOR	   FLOAT8 DEFAULT 0;
  VALOR_EMPENHADO	   FLOAT8 DEFAULT 0;
  VALOR_ANULADO		   FLOAT8 DEFAULT 0;
  VALOR_LIQUIDADO	   FLOAT8 DEFAULT 0;
  VALOR_PAGO		     FLOAT8 DEFAULT 0;
  VALOR_SUPLEMENTADO FLOAT8 DEFAULT 0;
  VALOR_ESPECIAL     FLOAT8 DEFAULT 0;
  VALOR_REDUZIDO	   FLOAT8 DEFAULT 0;

  VALOR_TRANSFSUP FLOAT8 DEFAULT 0;
  VALOR_TRANSFRED FLOAT8 DEFAULT 0;

  VALOR_EMPENHADOT	  FLOAT8 DEFAULT 0;
  VALOR_ANULADOT	    FLOAT8 DEFAULT 0;
  VALOR_LIQUIDADOT	  FLOAT8 DEFAULT 0;
  VALOR_PAGOT		      FLOAT8 DEFAULT 0;
  VALOR_SUPLEMENTADOT	FLOAT8 DEFAULT 0;
  VALOR_ESPECIALT     FLOAT8 DEFAULT 0;
  VALOR_REDUZIDOT	    FLOAT8 DEFAULT 0;
	VALOR_TRANSFSUPT    FLOAT8 DEFAULT 0;
	VALOR_TRANSFREDT    FLOAT8 DEFAULT 0;

  VALOR_EMPENHADOA	  FLOAT8 DEFAULT 0;
  VALOR_ANULADOA	    FLOAT8 DEFAULT 0;
  VALOR_LIQUIDADOA	  FLOAT8 DEFAULT 0;
  VALOR_PAGOA		      FLOAT8 DEFAULT 0;
  VALOR_SUPLEMENTADOA	FLOAT8 DEFAULT 0;
  VALOR_ESPECIALA	    FLOAT8 DEFAULT 0;
  VALOR_REDUZIDOA	    FLOAT8 DEFAULT 0;
  VALOR_TRANSFSUPA	  FLOAT8 DEFAULT 0;
  VALOR_TRANSFREDA	  FLOAT8 DEFAULT 0;

  SALDO_A_PAGAR 	        FLOAT8 DEFAULT 0;
  SALDO_A_PAGAR_LIQUIDADO	FLOAT8 DEFAULT 0;

  VALOR_ATUAL_DOT             FLOAT8;
  VALOR_ATUAL_MENOS_RESERVADO FLOAT8;


  BEGIN

    SELECT O58_VALOR
    INTO VALORINI
    FROM ORCDOTACAO
    WHERE O58_ANOUSU = ANOUSU
      AND O58_CODDOT = CODDOT;

    IF VALORINI IS NULL THEN
      RETURN 0;
    END IF;

    IF TIPO = 1 THEN

      RETURN ''1'' || TO_CHAR(VALORINI,''999999999.99'');
      -- RETORNO O VALOR DA DOTACAO COM OU SEM RESERVA

    END IF;

    IF TIPO != 5 THEN

      SELECT SUM(O80_VALOR)::float8
      INTO VALORRES
      FROM ORCRESERVA
      WHERE O80_ANOUSU = ANOUSU
            AND O80_CODDOT = CODDOT
            AND DATAUSU <= O80_DTFIM;

      IF VALORRES IS NULL THEN
        VALORRES := 0;
      END IF;

    ELSE

      SELECT SUM(O80_VALOR)::float8
      INTO VALORRES
      FROM ORCRESERVA
        LEFT JOIN  orcreservager ON o80_codres = o84_codres
      WHERE O80_ANOUSU = ANOUSU
            AND O80_CODDOT = CODDOT
            AND DATAUSU <= O80_DTFIM
            AND o84_codres IS NULL;

      IF VALORRES IS NULL THEN
        VALORRES := 0;
      END IF;

    END IF;

    -- BUSCA VALORES RESERVADOS ATE A DATA INFORMADA (FINAL)
    SELECT
      COALESCE(SUM(CASE WHEN orcreservager.o84_codres IS NULL THEN o80_valor ELSE 0 END),0) as manual,
      COALESCE(SUM(CASE WHEN orcreservager.o84_codres IS NOT NULL THEN o80_valor ELSE 0 END),0) as automatico,
      COALESCE(SUM(o80_valor),0) as total
    INTO RESERVADO_MANUAL_ATE_DATA, RESERVADO_AUTOMATICO_ATE_DATA, RESERVADO_ATE_DATA
    FROM orcreserva
      LEFT JOIN orcreservager ON o84_codres = o80_codres
    WHERE o80_anousu = ANOUSU
      AND o80_coddot = CODDOT
      AND (o80_dtini <= DATAFIM
      AND  o80_dtfim >= DATAFIM);


    -- VALOR EMPENHADO
    DATAINI := ANOUSU||''-01-01'' ;

    SELECT
      SUM(CASE WHEN  C53_TIPO = 10 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) AS EMPENHADO,
      SUM(CASE WHEN  C53_TIPO = 11 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) AS ANULADO,
      SUM(CASE WHEN  C53_TIPO = 20 THEN ROUND(c70_VALOR,2) ELSE (CASE WHEN C53_TIPO = 21 THEN ROUND(c70_VALOR*-(1::FLOAT8),2) ELSE 0::FLOAT8 END) END ) AS LIQUIDADO,
      SUM(CASE WHEN  C53_TIPO = 30 THEN ROUND(c70_VALOR,2) ELSE (CASE WHEN C53_TIPO = 31 THEN ROUND(c70_VALOR*-(1::FLOAT8),2) ELSE 0::FLOAT8 END) END ) AS PAGO     ,
      SUM((CASE WHEN (C53_TIPO > 39 AND C53_TIPO < 41) THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END) - ( CASE WHEN C53_TIPO = 41 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) ) AS SUPLEMENTADO,
      SUM((CASE WHEN (C53_TIPO > 49 AND C53_TIPO < 51) THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) - (CASE WHEN C53_TIPO = 51 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END)) AS ESPECIAL,
      SUM((CASE WHEN (C53_TIPO > 59 AND C53_TIPO < 61) THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) - (CASE WHEN C53_TIPO = 61 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END) ) AS REDUZIDO,
      SUM(0) AS TRANSFRECSUP,
      SUM(0) AS TRANSFRECRED
    INTO
      VALOR_EMPENHADOA,
      VALOR_ANULADOA,
      VALOR_LIQUIDADOA,
      VALOR_PAGOA,
      VALOR_SUPLEMENTADOA,
      VALOR_ESPECIALA,
      VALOR_REDUZIDOA,
			VALOR_TRANSFSUPA,
			VALOR_TRANSFREDA
    FROM CONLANCAMDOT
      INNER JOIN CONLANCAM ON C70_CODLAN = C73_CODLAN
      INNER JOIN CONLANCAMDOC ON C71_CODLAN = C70_CODLAN
      INNER JOIN CONHISTDOC ON C53_CODDOC = C71_CODDOC
    WHERE C73_ANOUSU = ANOUSU
          AND C73_CODDOT = CODDOT
          AND C73_DATA BETWEEN DATAINI::date AND DATAUSU-1;

    IF VALOR_EMPENHADOA IS NULL THEN
      VALOR_EMPENHADOA := 0;
    END IF;

    IF VALOR_ANULADOA IS NULL THEN
      VALOR_ANULADOA := 0;
    END IF;

    IF VALOR_LIQUIDADOA IS NULL THEN
      VALOR_LIQUIDADOA := 0;
    END IF;

    IF VALOR_PAGOA IS NULL THEN
      VALOR_PAGOA := 0;
    END IF;

    IF VALOR_SUPLEMENTADOA IS NULL THEN
      VALOR_SUPLEMENTADOA := 0;
    END IF;

    IF VALOR_ESPECIALA IS NULL THEN
      VALOR_ESPECIALA := 0;
    END IF;

    IF VALOR_REDUZIDOA IS NULL THEN
      VALOR_REDUZIDOA  := 0;
    END IF;

    IF VALOR_TRANSFSUPA IS NULL THEN
      VALOR_TRANSFSUPA := 0;
    END IF;

    IF VALOR_TRANSFREDA IS NULL THEN
      VALOR_TRANSFREDA := 0;
    END IF;

    VALOR := VALOR + VALOR_SUPLEMENTADOA + VALOR_ESPECIALA - VALOR_REDUZIDOA - VALOR_EMPENHADOA + VALOR_ANULADOA;

    SELECT
      SUM(CASE WHEN  C53_TIPO = 10 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) AS EMPENHADO,
      SUM(CASE WHEN  C53_TIPO = 11 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) AS ANULADO,
      SUM(CASE WHEN  C53_TIPO = 20 THEN ROUND(c70_VALOR,2) ELSE (CASE WHEN C53_TIPO = 21 THEN ROUND(c70_VALOR*-(1::FLOAT8),2) ELSE 0::FLOAT8 END) END ) AS LIQUIDADO,
      SUM(CASE WHEN  C53_TIPO = 30 THEN ROUND(c70_VALOR,2) ELSE (CASE WHEN C53_TIPO = 31 THEN ROUND(c70_VALOR*-(1::FLOAT8),2) ELSE 0::FLOAT8 END) END ) AS PAGO     ,
      SUM((CASE WHEN (C53_TIPO > 39 AND C53_TIPO < 41) THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END) - ( CASE WHEN C53_TIPO = 41 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) ) AS SUPLEMENTADO,
      SUM((CASE WHEN (C53_TIPO > 49 AND C53_TIPO < 51) THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) - (CASE WHEN C53_TIPO = 51 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END)) AS ESPECIAL,
      SUM((CASE WHEN (C53_TIPO > 59 AND C53_TIPO < 61) THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END ) - (CASE WHEN C53_TIPO = 61 THEN ROUND(c70_VALOR,2) ELSE 0::FLOAT8 END) ) AS REDUZIDO,
      SUM(0) AS TRANSFRECSUP,
      SUM(0) AS TRANSFRECRED
    INTO
      VALOR_EMPENHADO,
      VALOR_ANULADO,
      VALOR_LIQUIDADO,
      VALOR_PAGO,
      VALOR_SUPLEMENTADO,
      VALOR_ESPECIAL,
      VALOR_REDUZIDO,
      VALOR_TRANSFSUP,
      VALOR_TRANSFRED
    FROM CONLANCAMDOT
      INNER JOIN CONLANCAM ON C70_CODLAN = C73_CODLAN
      INNER JOIN CONLANCAMDOC ON C71_CODLAN = C70_CODLAN
      INNER JOIN CONHISTDOC ON C53_CODDOC = C71_CODDOC
    WHERE C73_ANOUSU = ANOUSU
          AND C73_CODDOT = CODDOT
          AND C73_DATA BETWEEN DATAUSU AND DATAFIM;

    VALOR := VALOR + VALORINI;

    IF VALOR_EMPENHADO IS NULL THEN
      VALOR_EMPENHADO := 0;
    END IF;

    IF VALOR_ANULADO IS NULL THEN
      VALOR_ANULADO   := 0;
    END IF;

    IF VALOR_LIQUIDADO IS NULL THEN
      VALOR_LIQUIDADO := 0;
    END IF;

    IF VALOR_PAGO IS NULL THEN
      VALOR_PAGO := 0;
    END IF;

    IF VALOR_SUPLEMENTADO IS NULL THEN
      VALOR_SUPLEMENTADO := 0;
    END IF;

    IF VALOR_ESPECIAL IS NULL THEN
      VALOR_ESPECIAL := 0;
    END IF;

    IF VALOR_REDUZIDO IS NULL THEN
      VALOR_REDUZIDO := 0;
    END IF;

    IF VALOR_TRANSFSUP IS NULL THEN
      VALOR_TRANSFSUP := 0;
    END IF;

    IF VALOR_TRANSFRED IS NULL THEN
      VALOR_TRANSFRED := 0;
    END IF;

    VALOR_EMPENHADOT    := ROUND(VALOR_EMPENHADO + VALOR_EMPENHADOA,2);
    VALOR_ANULADOT      := ROUND(VALOR_ANULADO  + VALOR_ANULADOA,2);
    VALOR_LIQUIDADOT    := ROUND(VALOR_LIQUIDADO+ VALOR_LIQUIDADOA,2);
    VALOR_PAGOT         := ROUND(VALOR_PAGO     + VALOR_PAGOA ,2) ;
    VALOR_SUPLEMENTADOT := ROUND(VALOR_SUPLEMENTADO + VALOR_SUPLEMENTADOA,2);
    VALOR_ESPECIALT     := ROUND(VALOR_ESPECIAL + VALOR_ESPECIALA,2);
    VALOR_REDUZIDOT     := ROUND(VALOR_REDUZIDO + VALOR_REDUZIDOA,2);
    VALOR_TRANSFSUPT    := ROUND(VALOR_TRANSFSUP + VALOR_TRANSFSUPA,2);
    VALOR_TRANSFREDT    := ROUND(VALOR_TRANSFRED + VALOR_TRANSFREDA,2);

    -- SALDO INICIAL
    -- SALDO ANTERIOR
    -- SALDO EMPENHADO
    -- SALDO ANULADO
    -- SALDO LIQUIDADO
    -- SALDO PAGO
    -- SALDO SUPLEMENTADO
    -- SALDO REDUZIDO
    -- SALDO ATUAL
    -- VALOR RESERVADO
    -- SALDO ATUAL MENOS O RESERVADO
    -- SALDO ATUAL A PAGAR
    -- SALDO ATUAL A PAGAR LIQUIDADO
    -- SALDO EMPENHADO ACUMULADO
    -- SALDO ANULADO   ACUMULADO
    -- SALDO LIQUIDADO ACUMULADO
    -- SALDO PAGO ACUMULADO
    -- SALDO SUPLEMENTADO ACUMULADO
    -- SALDO REDUZIDO ACUMULADO

    SALDO_A_PAGAR := VALOR_EMPENHADO - VALOR_ANULADO - VALOR_LIQUIDADO;

    IF SALDO_A_PAGAR < 0 THEN
      SALDO_A_PAGAR := 0;
    END IF;

    SALDO_A_PAGAR_LIQUIDADO := VALOR_LIQUIDADO-VALOR_PAGO;
    IF SALDO_A_PAGAR_LIQUIDADO < 0 THEN
      SALDO_A_PAGAR_LIQUIDADO := 0;
    END IF;

    VALOR_ATUAL_DOT := VALOR + (VALOR_SUPLEMENTADO + VALOR_ESPECIAL) - VALOR_REDUZIDO - VALOR_EMPENHADO + VALOR_ANULADO;
    VALOR_ATUAL_MENOS_RESERVADO := (VALOR) + (VALOR_SUPLEMENTADO + VALOR_ESPECIAL) - VALOR_REDUZIDO - VALOR_EMPENHADO + VALOR_ANULADO - (VALORRES);

    RETURN ''2''
      || TO_CHAR(VALORINI,''999999999.99'')
      || TO_CHAR(VALOR,''999999999.99'')
      || TO_CHAR(VALOR_EMPENHADO,''999999999.99'')
      || TO_CHAR(VALOR_ANULADO,''999999999.99'')
      || TO_CHAR(VALOR_LIQUIDADO,''999999999.99'')
      || TO_CHAR(VALOR_PAGO,''999999999.99'')
      || TO_CHAR((VALOR_SUPLEMENTADO+VALOR_ESPECIAL),''999999999.99'')
      || TO_CHAR(VALOR_REDUZIDO,''999999999.99'')
      || TO_CHAR(VALOR_ATUAL_DOT,''999999999.99'')
      || TO_CHAR(VALORRES,''999999999.99'')
      || TO_CHAR(VALOR_ATUAL_MENOS_RESERVADO,''999999999.99'')
      || TO_CHAR(SALDO_A_PAGAR,''999999999.99'')
      || TO_CHAR(SALDO_A_PAGAR_LIQUIDADO,''999999999.99'')
      || TO_CHAR(VALOR_EMPENHADOT,''999999999.99'')
      || TO_CHAR(VALOR_ANULADOT,''999999999.99'')
      || TO_CHAR(VALOR_LIQUIDADOT,''999999999.99'')
      || TO_CHAR(VALOR_PAGOT,''999999999.99'')
      || TO_CHAR((VALOR_SUPLEMENTADOT+VALOR_ESPECIALT),''999999999.99'')
      || TO_CHAR(VALOR_REDUZIDOT,''999999999.99'')
      || TO_CHAR(VALOR_SUPLEMENTADO,''999999999.99'')
      || TO_CHAR(VALOR_SUPLEMENTADOT,''999999999.99'')
      || TO_CHAR(VALOR_ESPECIAL,''999999999.99'')
      || TO_CHAR(VALOR_ESPECIALT,''999999999.99'')
      || TO_CHAR(VALOR_TRANSFSUP,''999999999.99'')
      || TO_CHAR(VALOR_TRANSFSUPT,''999999999.99'')
      || TO_CHAR(VALOR_TRANSFRED,''999999999.99'')
      || TO_CHAR(VALOR_TRANSFREDT,''999999999.99'')
      || TO_CHAR(RESERVADO_MANUAL_ATE_DATA,''999999999.99'')
      || TO_CHAR(RESERVADO_AUTOMATICO_ATE_DATA,''999999999.99'')
      || TO_CHAR(RESERVADO_ATE_DATA,''999999999.99'');
  END;
' language 'plpgsql';