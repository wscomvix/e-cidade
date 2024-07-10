insert into db_versao (db30_codver, db30_codversao, db30_codrelease, db30_data, db30_obs)  values (355, 3, 39, '2015-07-06', 'Tarefas: 98129, 98138, 98141, 98143, 98144, 98145, 98146, 98148, 98149, 98152, 98153, 98155, 98156, 98157, 98159, 98160, 98161, 98162, 98163, 98164, 98166, 98167, 98168, 98171, 98172, 98173, 98174, 98175, 98176, 98177, 98178, 98179, 98182, 98188, 98189, 98190, 98191, 98192, 98193, 98195, 98196, 98197, 98198, 98201, 98204, 98207, 98208, 98209, 98211, 98213, 98214, 98215, 98216, 98217, 98220, 98221, 98222, 98225, 98226, 98227, 98229, 98230, 98231, 98233, 98240, 98241, 98244, 98245, 98246, 98247, 98248, 98249, 98250, 98251, 98253, 98255, 98256, 98257, 98259, 98261, 98263, 98264, 98265, 98266, 98267, 98268, 98269, 98270, 98273, 98274, 98275, 98276, 98278, 98280, 98281, 98288, 98289, 98290, 98291, 98292, 98294, 98296, 98300, 98301, 98302, 98303, 98304, 98305, 98311, 98312, 98313, 98314, 98359, 98364, 98365, 98366, 98367, 98368');--
-- Funcao que gera registros no Financeiro de acordo com calculo
--
-- Parametros: 1 - Ano de Referencia
--             2 - Mes de Referencia
--             3 - Matricula
--             4 - Numpre
--             5 - NumCgm
--             6 - Receita
--             7 - Historico
--             8 - Valor


--drop function fc_agua_calculogerafinanceiro(integer, integer, integer, integer, integer, integer, integer, float8);
create or replace function fc_agua_calculogerafinanceiro(integer, integer, integer, integer, integer, integer, integer, integer, float8) returns bool as 
$$
declare
  -- Parametros
  iAno     alias for $1;
  iMes     alias for $2;
  iMatric   alias for $3;
  iNumpreOld alias for $4;
  iNumpre   alias for $5;
  iNumCgm   alias for $6;
  iReceit   alias for $7;
  iCodHist   alias for $8;
  nValor     alias for $9;
  -- Variaveis
  rArrecad          record;
  rArrecant         record;
  rArreMatric       record;
  rArreNumCgm       record;
  rsResulDescArreca record;
  rAguaDescHist     record;
  dDataVenc         date;
  dDataOper         date;
  iNumTot            integer := 12; -- Total de Parcelas
  iNumDig           integer := 0;
  iTipo             integer := 0;
  iTipojm           integer := 0;
  iIdOcor           integer := 0;
  iIdOcorMatric     integer := 0;
  fTotalDescDebito  float;
  lRaise            boolean default false;
begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );   

  -- Busca Data de Vencimento
  dDataVenc := fc_agua_datavencimento(iAno, iMes, iMatric);

  if dDataVenc is null then
    return false;
  end if;

  --dDataOper := to_date(''01-''||to_char(iMes,''00'')||''-''||iAno, ''DD-MM-YYYY'');
  if fc_getsession('DB_datausu') = '' or fc_getsession('DB_datausu') is null then
    raise exception 'Variavel de sessao[DB_datausu] nao encontrada';
  end if;
  
  dDataOper := cast(fc_getsession('DB_datausu') as date);
  
  iTipo := fc_agua_confarretipo(iAno);
  
  -- Verifica se NumpreOld nao foi Pago/Cancelado/Importado pra Divida
  -- Verifica tambem se numpre n�o possui pagamento parcial
  perform * from ( 
    select k00_numpre
      from arrecant
     where k00_numpre = iNumpreOld
       and k00_numpar = iMes
       and k00_tipo   = iTipo
       and k00_receit = iReceit
    union all
    select k10_numpre
      from divold
     where k10_numpre  = iNumpreOld
       and k10_numpar  = iMes
       and k10_receita = iReceit
  ) as x;
  
  if not found and nValor > 0 then
    
    --deleta da arreold os registros antigos
    delete from arreold 
     where k00_numpre = iNumpreOld
       and k00_numpar = iMes
       and k00_tipo   = iTipo
       and k00_receit = iReceit;
  
    -- Insere Registro no Arrecad
    insert into arrecad(
      k00_numpre , k00_numpar ,
      k00_numcgm , k00_dtoper ,
      k00_receit , k00_hist   ,
      k00_valor  , k00_dtvenc ,
      k00_numtot , k00_numdig ,
      k00_tipo   , k00_tipojm 
    ) values (
      iNumpre , 
      iMes ,
      iNumCgm , 
      dDataOper ,
      iReceit , 
      3999 + iMes ,
      nValor  , 
      dDataVenc ,
      iNumTot , 
      iNumDig ,
      iTipo , 
      iTipojm
    );
    
    /**
     * retorna para arrecad e arrehist os descontos lan�ados 
    */
    fTotalDescDebito := 0;
    
    for rsResulDescArreca IN ( SELECT *
                                 FROM aguadescarrecad
                                WHERE x35_numpre = iNumpre
                                  AND x35_numpar = iMes
                                  AND x35_numcgm = iNumCgm
                                  AND x35_receit = iReceit)
    loop
                                  
      /**
       * cancela desconto caso valor do desconto sej� maior do que o d�bito
       */
    
      fTotalDescDebito := fTotalDescDebito + (rsResulDescArreca.x35_valor * -1);
  
      IF (fTotalDescDebito < nValor) THEN
  
        INSERT INTO arrecad(k00_numpre, k00_numpar, k00_numcgm,
                            k00_dtoper, k00_receit, k00_hist  ,
                            k00_valor , k00_dtvenc, k00_numtot,
                            k00_numdig, k00_tipo  , k00_tipojm)
                    VALUES (rsResulDescArreca.x35_numpre, rsResulDescArreca.x35_numpar, rsResulDescArreca.x35_numcgm,
                            rsResulDescArreca.x35_dtoper, rsResulDescArreca.x35_receit, rsResulDescArreca.x35_hist  ,
                            rsResulDescArreca.x35_valor , rsResulDescArreca.x35_dtvenc, rsResulDescArreca.x35_numtot,
                            rsResulDescArreca.x35_numdig, rsResulDescArreca.x35_tipo  , rsResulDescArreca.x35_tipojm);
        
        SELECT INTO rAguaDescHist *
          FROM aguadescarrehist
               INNER JOIN arrehist ON arrehist.k00_numpre = aguadescarrehist.x36_numpre
                                  AND arrehist.k00_numpar = aguadescarrehist.x36_numpar
                                  AND arrehist.k00_hist   = aguadescarrehist.x36_hist
                                  AND arrehist.k00_dtoper = aguadescarrehist.x36_dtoper
                                  AND arrehist.k00_hora   = aguadescarrehist.x36_hora
         WHERE aguadescarrehist.x36_numpre = iNumpre
           AND aguadescarrehist.x36_numpar = iMes;      
           
        IF NOT found THEN
  
          INSERT INTO arrehist (k00_numpre , k00_numpar   , k00_hist      ,
                                k00_dtoper , k00_hora     , k00_id_usuario,
                                k00_histtxt, k00_limithist, k00_idhist)
          SELECT x36_numpre , x36_numpar   , x36_hist      ,
                 x36_dtoper , x36_hora     , x36_id_usuario,
                 x36_histtxt, x36_limithist, nextval('arrehist_k00_idhist_seq')
            FROM aguadescarrehist
           WHERE aguadescarrehist.x36_numpre = iNumpre
             AND aguadescarrehist.x36_numpar = iMes;
        
        END IF;
        
      ELSE 
      
        iIdOcor       := nextval('histocorrencia_ar23_sequencial_seq');
        iIdOcorMatric := nextval('histocorrenciamatric_ar25_sequencial_seq');
        
        INSERT INTO histocorrencia  
          (ar23_sequencial  , ar23_id_usuario, ar23_instit, ar23_modulo,
           ar23_id_itensmenu, ar23_data      , ar23_hora  , ar23_tipo  ,
           ar23_descricao   , ar23_ocorrencia)
         VALUES
          (iIdOcor, cast(fc_getsession('DB_id_usuario') as integer),
           cast(fc_getsession('DB_instit') as integer), cast(fc_getsession('DB_modulo') as integer),
           cast(fc_getsession('DB_itemmenu_acessado') as integer), TO_DATE(fc_getsession('DB_datausu'), 'YYYY-MM-DD'),
           TO_CHAR(CURRENT_TIMESTAMP, 'HH24:MI')                 , 2,           
           'CANCELAMENTO DE DESCONTO', 'Desconto lan�ado anteriormente sobre o Numpre '||iNumpre||
           ' � maior que o valor da Parcela Total: R$ '||nValor);
        
        INSERT INTO histocorrenciamatric (ar25_sequencial, ar25_matric, ar25_histocorrencia)
                                  VALUES (iIdOcorMatric, iMatric, iIdOcor);
      
      END IF;
    
    END loop;
    
    -- Gera ArreMatric
    select  into rArreMatric
        *
    from   arrematric
    where  k00_numpre = iNumpre;
  
    if not found then
      insert into arrematric (
        k00_numpre,
        k00_matric
      ) values (
        iNumpre,
        iMatric
      );
    end if;
  end if;
 
  return true;
end;
$$ language 'plpgsql';--
-- Funcao que efetua calculo/re-calculo de uma matricula especifica
--
-- Parametros: 1 - Ano de Referencia
--             2 - Mes de Referencia
--             3 - Matricula
--             4 - Tipo de movimentacao aguacalc(1 - Parcial, 2 - Geral, 3 Coletor)
--             5 - Troca Numpre? (Qdo houver recalculo)
--             6 - Gera Financeiro? (arrecad, arrematric, arrenumcgm, arreold)
--
SET check_function_bodies TO off; 

drop function if exists fc_agua_calculoparcial(integer, integer, integer, integer, bool, bool);

create or replace function fc_agua_calculoparcial(integer, integer, integer, integer, bool, bool) returns varchar as 
$$
declare
  ---------------------- Parametros
  iAno             alias for $1;
  iMes             alias for $2;
  iMatric          alias for $3;
  iTipo            alias for $4;
  lTrocaNumpre     alias for $5;
  lGeraFinanceiro  alias for $6;
  ----------------------- Variaveis
  sMemoria         text   := ''; -- Campo que contera a memoria de Calculo da Matricula
  sHora            text   := '';
  dData            date   := null;
  nConsumo         float8 := 0;
  nExcesso         float8 := 0;
  nSaldoComp       float8 := 0;
  lTaxaBasica      bool   := false;
  rCalculo         record;  
  rAguaBase        record;
  rCondominio      record;
  iUsuario         integer;
  iInstit          integer;
  iEconomias       integer;
  iConsumo         integer;
  iNumpre          integer;
  iNumpreOld       integer;
  iCodCalc         integer;
  iCondominio      integer;
  iApto            integer;
  iTipoImovel      integer;
  nValorTemp       float8 := 0;
  nPercentualIsencao   float4 := 0;
  lAguaLigada      bool := false;
  lSemAgua         bool := false;
  lEsgotoLigado    bool := false;
  lSemEsgoto       bool := false;
  lRecalculo       bool := false;
  lGeraArrecad     bool := true;
  lRaise           bool := true;
  nAreaConstr      float4 := 0;
  lGeraDesconto    bool := false;
  dDataVenc         date;
  lAtivaPgtoParcial boolean default false;
  lPossuiPagamentoParcialDebito boolean default false;
  
  rsAreaAlterada record;
  
begin
  
  dDataVenc := fc_agua_datavencimento(iAno, iMes, iMatric);
  -- 1) Verifica se Existe Hidrometro Instalado
  if fc_agua_hidrometroinstalado(iMatric) = false then
    lTaxaBasica := true;
  else
    -- 1.1) Verifica Consumo e Excesso
    nConsumo   := fc_agua_consumo(iAno, iMes, iMatric);
    nExcesso   := fc_agua_excesso(iAno, iMes, iMatric);
    nSaldoComp := fc_agua_saldocompensado(iAno, iMes, iMatric);
    
    if (nConsumo + nExcesso) = 0 then 
      -- E R R O !!!!
      lTaxaBasica := true;
    end if;
    
  end if;
  
  -- Busca dados da Matricula
  select * 
    into rAguaBase
    from aguabase
   where x01_matric = iMatric;

  -- 2) Verifica dados para Calculo
  lGeraArrecad  := lGeraFinanceiro;
  iEconomias    := rAguaBase.x01_qtdeconomia;
  iConsumo      := fc_agua_consumocodigo(iAno, iMatric, rAguaBase.x01_zona);
  lAguaLigada   := fc_agua_agualigada(iAno, iMatric);
  lEsgotoLigado := fc_agua_esgotoligado(iAno, iMatric);
  lSemAgua      := fc_agua_semagua(iAno, iMatric);
  lSemEsgoto    := fc_agua_semesgoto(iAno, iMatric);
  nAreaConstr   := fc_agua_areaconstr(iMatric);  
  --iNumpre       := fc_agua_numpre();
  iCodCalc      := fc_agua_calculocodigo(iAno, iMes, iMatric);

  if iConsumo is null then
    return '2 - N�O FOI POSS�VEL ENCONTRAR CONFIGURA��O DE CONSUMO PARA MATR�CULA '||iMatric||' EXERC�CIO '||iAno||' ZONA '||rAguaBase.x01_zona;
  end if;
  
  
  if iCodCalc is not null then
    lRecalculo := true;
  end if;
  
  -- 3) Verifica se eh matricula de condominio e apto
  iCondominio := fc_agua_condominiocodigo(iMatric);
  iApto       := fc_agua_condominioapto(iMatric);
  
    raise notice 'lGeraArrecad (%) lAguaLigada (%) iApto (%) nConsumo (%) nExcesso (%)', lGeraArrecad, lAguaLigada, iApto, nConsumo, nExcesso;

  -- 4) Gera AguaCalc
  --    . Verifica se eh um Novo Calculo 
  dData      := TO_DATE(fc_getsession('DB_datausu'), 'YYYY-MM-DD');
  sHora      := TO_CHAR(CURRENT_TIMESTAMP, 'HH24:MI');
  iUsuario   := CAST(fc_getsession('DB_id_usuario') as integer);
  iInstit    := CAST(fc_getsession('DB_instit') as integer);

  if lRecalculo = false then
    iNumpre    := fc_agua_numpre();
    iNumpreOld := iNumpre;
    iCodCalc   := nextval('aguacalc_x22_codcalc_seq');
  
    insert into aguacalc(
      x22_codcalc,
      x22_codconsumo,
      x22_exerc,
      x22_mes,
      x22_matric,
      x22_area,
      x22_numpre,
      x22_tipo,
      x22_data,
      x22_hora,
      x22_usuario
    ) values (
      iCodCalc,
      iConsumo,
      iAno,
      iMes,
      iMatric,
      nAreaConstr,
      iNumpre,
      iTipo,
      dData,
      sHora,
      iUsuario
    );
  else
    --if lTrocaNumpre = false then
      iNumpreOld := fc_agua_calculonumpre(iAno, iMes, iMatric);
      iNumpre    := iNumpreOld;
    --end if;
    
    update aguacalc 
       set x22_codconsumo = iConsumo,
           x22_area       = nAreaConstr,
           x22_numpre     = iNumpre,
           x22_tipo       = iTipo,
           x22_data       = dData,
           x22_hora       = sHora,
           x22_usuario    = iUsuario
     where x22_codcalc    = iCodCalc;

    delete 
      from aguacalcval 
     where x23_codcalc = iCodCalc;

  end if;
  
  select k03_pgtoparcial
    into lAtivaPgtoParcial
    from numpref
   where k03_instit = iInstit
     and k03_anousu = iAno;
  
  if lAtivaPgtoParcial is true then      
          
    select true
      into lPossuiPagamentoParcialDebito
      from arreckey
           inner join abatimentoarreckey on k128_arreckey   = k00_sequencial
           inner join abatimento         on k125_sequencial = k128_abatimento 
     where k00_numpre = iNumpre
       and k00_numpar = iMes;
       
    if lPossuiPagamentoParcialDebito is true then
        return '1 - Calculo n�o efetuado, matricula possui d�bito com pagamento parcial.';  
    end if;
       
      
  end if;
  
  -- Se for um Apto de um Condominio e tiver agua desligada,
  -- for�a nao gerar financeiro
  if (lAguaLigada = false) and (iApto is not null) then
    lGeraArrecad := false;

    perform fc_agua_calculogeraarreold(
      iAno,
      iMes,
      iNumpreOld);
  end if;

  if lGeraArrecad = true then
  
    raise notice 'Ano(%), Mes(%), Numpre(%)', iAno, iMes, iNumpreOld;
      
    perform fc_agua_calculogeraarreold(
      iAno,
      iMes,
      iNumpreOld);
  end if;

  -- Busca Registros para C�lculo
  for rCalculo in 
    select aguaconsumo.*,
           aguaconsumorec.*,
           aguaconsumotipo.*,
           0::float8 as x99_valor,
           0::float8 as x99_valor_desconto
      from aguaconsumo
           inner join aguaconsumorec  on x19_codconsumo = x20_codconsumo
           inner join aguaconsumotipo on x20_codconsumotipo = x25_codconsumotipo
     where x19_codconsumo = iConsumo
  loop
    --
    -- Verifica se Esta Calculando Consumo de Agua
    --
    if  (rCalculo.x25_codconsumotipo = fc_agua_confconsumoagua()) and
      (lSemAgua = false) then
      --
      -- ATENCAO!! Multiplica pelo retorno da funcao que verifica
      --           a qtd de economias levando em conta o multiplicador
      --
      if (iCondominio is not null) then
      
        nValorTemp := fc_agua_calculatxapto(iAno, iCondominio, rCalculo.x25_codconsumotipo);
      
      else 
      
        nValorTemp := rCalculo.x20_valor * fc_agua_qtdeconomias(iMatric);
        
      end if;
    --
    -- .. ou Esgoto
    --
    elsif (rCalculo.x25_codconsumotipo = fc_agua_confconsumoesgoto()) and
          (lSemEsgoto = false) then
          
      if (iCondominio is not null) then
      
        nValorTemp := fc_agua_calculatxapto(iAno, iCondominio, rCalculo.x25_codconsumotipo);
      
      else
      
        nValorTemp := rCalculo.x20_valor * fc_agua_qtdeconomias(iMatric);
        
      end if;
      
    --
    -- .. ou Excesso
    --
    elsif (rCalculo.x25_codconsumotipo = fc_agua_confconsumoexcesso()) then
      if   ((iCondominio is not null) and (lAguaLigada = false)) or (lSemAgua = false) then
        if nExcesso > 0 then
          lGeraDesconto = true;
          nValorTemp   := rCalculo.x20_valor * nExcesso;
        else
          nValorTemp := 0;
        end if;
      else
        nValorTemp := 0;
      end if;
    else
      nValorTemp := 0;
    end if;
    
    -- Verifica Isencao
    nPercentualIsencao := fc_agua_percentualisencao(iAno, iMes, iMatric, rCalculo.x25_codconsumotipo);
    
    if nPercentualIsencao = 0 then
      rCalculo.x99_valor := nValorTemp;
    else
      rCalculo.x99_valor := round(nValorTemp - (nValorTemp * (nPercentualIsencao / 100)), 2);
    end if;

      raise notice '2 - Matricula=(%) Isencao=(%) Padrao=(%) Taxa=(%) Valor Calculado=(%) Tipo Consumo=(%)', 
        iMatric,
        nPercentualIsencao,
        rCalculo.x20_valor,
        rCalculo.x25_descr,
        rCalculo.x99_valor,
        rCalculo.x25_codconsumotipo;

    if rCalculo.x99_valor > 0 then
      insert into aguacalcval (
        x23_codcalc,
        x23_codconsumotipo,
        x23_valor
      ) values (
        iCodCalc,
        rCalculo.x25_codconsumotipo,
        rCalculo.x99_valor
      );
    end if;

    if lGeraArrecad = true then
      perform fc_agua_calculogerafinanceiro(
        iAno,
        iMes,
        iMatric,
        iNumpreOld,
        iNumpre,
        coalesce(rAguaBase.x01_numcgm, 0),
        rCalculo.x25_receit,
        rCalculo.x25_codhist,
        rCalculo.x99_valor);
    end if;
    
    if lGeraDesconto = true and nSaldoComp > 0 then
      --caso haja saldos compensados repete opera��o para gerar o desconto
      lGeraDesconto := false;
      
      nValorTemp := -(rCalculo.x20_valor * nSaldoComp);
      
      if nPercentualIsencao = 0 then
        rCalculo.x99_valor_desconto := nValorTemp;
      else
        rCalculo.x99_valor_desconto := round(nValorTemp - (nValorTemp * (nPercentualIsencao / 100)), 2);
      end if;
  
        raise notice '1 - Matricula=(%) Isencao=(%) Padrao=(%) Taxa=(%) Valor Calculado=(%) Tipo Consumo=(%)', 
          iMatric,
          nPercentualIsencao,
          rCalculo.x20_valor,
          rCalculo.x25_descr,
          rCalculo.x99_valor_desconto,
          rCalculo.x25_codconsumotipo;
  
      if lGeraArrecad = true then
      
        perform * from (
                        select k00_numpre
                          from arrecant
                         where k00_numpre = iNumpre
                           and k00_numpar = iMes
                           and k00_tipo   = 137
                           and k00_receit = rCalculo.x25_receit
                        union all
                        select k10_numpre
                          from divold
                         where k10_numpre  = iNumpre
                           and k10_numpar  = iMes
                           and k10_receita = rCalculo.x25_receit
                      ) as x;
                  
        if not found then

          delete from arrehist where k00_numpre = iNumpre and k00_numpar = iMes and k00_hist in (970, 918) and k00_histtxt LIKE 'VOLUME DE %GUA COMPENSADO%';

          insert into arrehist
          ( k00_numpre, k00_numpar, k00_hist, k00_dtoper, k00_hora, k00_id_usuario, k00_histtxt, k00_limithist, k00_idhist )
          values 
          ( iNumpre, iMes, 970, dData, sHora, iUsuario, 'VOLUME DE �GUA COMPENSADO - VALOR DESCONTO = '||abs(rCalculo.x99_valor_desconto), null, nextval('arrehist_k00_idhist_seq') );

          insert into arrecad
          ( k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm )
          values
          ( iNumpre, iMes, coalesce(rAguaBase.x01_numcgm, 0), dData, rCalculo.x25_receit, 970, rCalculo.x99_valor_desconto,dDataVenc, 12, 0, 137, 0 );
          
          if rCalculo.x99_valor - abs(rCalculo.x99_valor_desconto) = 0 then
          
            insert into cancdebitos ( k20_codigo, k20_cancdebitostipo, k20_instit, k20_descr, k20_hora, k20_data, k20_usuario )
            values ( ( select nextval('cancdebitos_k20_codigo_seq') ), 2, iInstit, 'CANCELAMENTO POR COMPENSA��O DE CR�DITO', sHora, dData, iUsuario );
            
            insert into cancdebitosconcarpeculiar ( k72_sequencial, k72_cancdebitos, k72_concarpeculiar ) 
            values ( ( select nextval('cancdebitosconcarpeculiar_k72_sequencial_seq') ), ( select last_value from cancdebitos_k20_codigo_seq ),'000' );
            
            insert into cancdebitosreg ( k21_sequencia, k21_codigo, k21_numpre, k21_numpar, k21_receit, k21_data, k21_hora, k21_obs ) 
            values ( ( select nextval('cancdebitosreg_k21_sequencia_seq') ), 
                     ( select last_value from cancdebitos_k20_codigo_seq ), 
                     iNumpre,
                     iMes,
                     rCalculo.x25_receit,
                     dData, 
                     sHora,
                     'TAXA DE EXCESSO INTEGRALMENTE COMPENSADA, SALDO COMPENSADO: '||nSaldoComp||'m�, VALOR DESCONTO R$'||abs(rCalculo.x99_valor_desconto) );
                     
            insert into cancdebitosproc ( k23_codigo, k23_data, k23_hora, k23_usuario, k23_obs, k23_cancdebitostipo ) 
            values ( ( select nextval('cancdebitosproc_k23_codigo_seq') ), 
                     dData, 
                     sHora, 
                     iUsuario, 
                     'TAXA DE EXCESSO INTEGRALMENTE COMPENSADA, SALDO COMPENSADO: '||nSaldoComp||'m�, VALOR DESCONTO R$'||abs(rCalculo.x99_valor_desconto), 
                     2 );
                     
            insert into cancdebitosprocconcarpeculiar ( k74_sequencial, k74_cancdebitosproc, k74_concarpeculiar ) 
            values ( ( select nextval('cancdebitosprocconcarpeculiar_k74_sequencial_seq') ), ( select last_value from cancdebitosproc_k23_codigo_seq ), '000' );
            
            insert into arrecant ( k00_numpre, k00_numpar, k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numtot, k00_numdig, k00_tipo, k00_tipojm )
            select k00_numpre,
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
              from arrecad
             where k00_numpre = iNumpre
               and k00_numpar = iMes
               and k00_receit = rCalculo.x25_receit;
               
            delete
              from arrecad
             where k00_numpre = iNumpre
               and k00_numpar = iMes
               and k00_receit = rCalculo.x25_receit;
               
            insert into cancdebitosprocreg ( k24_sequencia, k24_codigo, k24_cancdebitosreg, k24_vlrhis, k24_vlrcor, k24_juros, k24_multa, k24_desconto ) 
            values ( ( select nextval('cancdebitosprocreg_k24_sequencia_seq') ), 
                     ( select last_value from cancdebitosproc_k23_codigo_seq ), 
                     ( select last_value from cancdebitosreg_k21_sequencia_seq ), 
                     0, 0, 0, 0, 0 );

          end if;
          
        end if;
        
      end if;
      
    end if;
    
  end loop;

  -- Verifica se matricula possui area alterada

    raise info '==========: INICIO AREA ALTERADA MATRICULA %, ANO %, MES % :==========', iMatric, iAno, iMes;

  
  select * 
    into rsAreaAlterada
    from w_matric_altera
         inner join iptubase on j01_matric = matricula
   where matricula = iMatric
     and not (area_anterior = 0 and area_atualizada > 60);
  
  if (rsAreaAlterada.matricula is not null) then
       
      raise info 'AREA ALTERADA: Inicio Tratamento: Area Antiga (%), Area Atualizada (%)',
      rsAreaAlterada.area_anterior,
      rsAreaAlterada.area_atualizada;
    
    iTipoImovel := fc_agua_tipoimovel(iMatric);

    if iTipoImovel = 1 and nAreaConstr = 0 then
      select into nAreaConstr max(x19_areafim) from aguaconsumo ;
    end if;
    
      raise info 'AREA ALTERADA: CODIGO CONSUMO ANTIGO -> %',iConsumo;
    
    if iTipoImovel = 1 then
    
      select x19_codconsumo
          into iConsumo
          from aguaconsumo
         where x19_exerc = iAno
           and rsAreaAlterada.area_anterior between x19_areaini and x19_areafim
           and fc_agua_existecaract(iMatric, x19_caract) is not null
           and x19_ativo is true
         limit 1;    
    end if;
      
      raise info 'AREA ALTERADA: CODIGO CONSUMO NOVO -> %',iConsumo;
    
    delete from w_matric_altera_debito where matricula = iMatric and mes = iMes;
    
    for rCalculo in 
      select aguaconsumo.*,
             aguaconsumorec.*,
             aguaconsumotipo.*,
             0::float8 as x99_valor,
             0::float8 as x99_valor_desconto
        from aguaconsumo
             inner join aguaconsumorec  on x19_codconsumo = x20_codconsumo
             inner join aguaconsumotipo on x20_codconsumotipo = x25_codconsumotipo
       where x19_codconsumo = iConsumo
    loop
      
      if  (rCalculo.x25_codconsumotipo = fc_agua_confconsumoagua()) and
        (lSemAgua = false) then

        if (iCondominio is not null) then
          nValorTemp := fc_agua_calculatxapto(iAno, iCondominio, rCalculo.x25_codconsumotipo);
        else 
          nValorTemp := rCalculo.x20_valor * fc_agua_qtdeconomias(iMatric);
        end if;
        
      elsif (rCalculo.x25_codconsumotipo = fc_agua_confconsumoesgoto()) and
            (lSemEsgoto = false) then
            
        if (iCondominio is not null) then
          nValorTemp := fc_agua_calculatxapto(iAno, iCondominio, rCalculo.x25_codconsumotipo);
        else
          nValorTemp := rCalculo.x20_valor * fc_agua_qtdeconomias(iMatric);
        end if;
        
      elsif (rCalculo.x25_codconsumotipo = fc_agua_confconsumoexcesso()) then
        
        if ((iCondominio is not null) and (lAguaLigada = false)) or (lSemAgua = false) then
          if nExcesso > 0 then
            lGeraDesconto = true;
            nValorTemp   := rCalculo.x20_valor * nExcesso;
          else
            nValorTemp := 0;
          end if;
        else
          nValorTemp := 0;
        end if;
      else
        nValorTemp := 0;
      end if;
      
      nPercentualIsencao := fc_agua_percentualisencao(iAno, iMes, iMatric, rCalculo.x25_codconsumotipo);
      
      if nPercentualIsencao = 0 then
        rCalculo.x99_valor := nValorTemp;
      else
        rCalculo.x99_valor := round(nValorTemp - (nValorTemp * (nPercentualIsencao / 100)), 2);
      end if;
     
        raise notice 'AREA ALTERADA - Matricula=(%) Isencao=(%) Padrao=(%) Taxa=(%) Valor Calculado=(%) Tipo Consumo=(%)', 
          iMatric,
          nPercentualIsencao,
          rCalculo.x20_valor,
          rCalculo.x25_descr,
          rCalculo.x99_valor,
          rCalculo.x25_codconsumotipo;
      
      if (rCalculo.x99_valor <> 0 and rsAreaAlterada.area_anterior <> 0) then 
      
        insert into w_matric_altera_debito (matricula, mes, codconsumotipo, codconsumo, valor, area_calculada) 
             values (iMatric, iMes, rCalculo.x25_codconsumotipo, iConsumo, rCalculo.x20_valor, rsAreaAlterada.area_anterior);
      end if;
      
      if lGeraDesconto = true and nSaldoComp > 0 then
        
        lGeraDesconto := false;
        nValorTemp    := -(rCalculo.x20_valor * nSaldoComp);
        
        if nPercentualIsencao = 0 then
          rCalculo.x99_valor_desconto := nValorTemp;
        else
          rCalculo.x99_valor_desconto := round(nValorTemp - (nValorTemp * (nPercentualIsencao / 100)), 2);
        end if;
    
        
          raise notice 'AREA ALTERADA: Matricula=(%) Isencao=(%) Padrao=(%) Taxa=(%) Valor Calculado=(%) Tipo Consumo=(%)', 
            iMatric,
            nPercentualIsencao,
            rCalculo.x20_valor,
            rCalculo.x25_descr,
            rCalculo.x99_valor_desconto,
            rCalculo.x25_codconsumotipo;
        
        if lGeraArrecad = true then
          
            raise info 'AREA ALTERADA (desconto): iAno (%), iMes (%), iNumpre (%), Receita (%), CodHist (%), Valor (%)',
              iAno,
              iMes,
              iNumpre,
              rCalculo.x25_receit,
              rCalculo.x25_codhist,
              rCalculo.x99_valor;
          
          perform * from (
                          select k00_numpre
                            from arrecant
                           where k00_numpre = iNumpre
                             and k00_numpar = iMes
                             and k00_tipo   = 137
                             and k00_receit = rCalculo.x25_receit
                          union all
                          select k10_numpre
                            from divold
                           where k10_numpre  = iNumpre
                             and k10_numpar  = iMes
                             and k10_receita = rCalculo.x25_receit
                        ) as x;
                    
          if not found then
    
            raise info 'AREA ALTERADA (compensacao): Valor Desconto = %', abs(rCalculo.x99_valor_desconto);  
          
            if rCalculo.x99_valor - abs(rCalculo.x99_valor_desconto) = 0 then
              raise info 'AREA ALTERADA (compensacao): Valor D�bito = Valor Desconto - CANCELADO O D�BITO';  
            end if;
          end if;
        end if;
      end if;
    end loop;
  end if;
  
  raise notice 'Economias=(%) Agua Ligada=(%) Esgoto Ligado (%) Recalculo=(%) CodCalc=(%) Numpre=(%)',
      iEconomias,
      lAguaLigada,
      lEsgotoLigado,
      lRecalculo,
      iCodCalc,
      iNumpre;
  
  perform fc_tmp_agua_prorroga_just_venc(iMatric);

  return '1 - CALCULO CONCLUIDO COM SUCESSO ';
end;
$$ language 'plpgsql';drop function if exists fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer);
drop function if exists fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer,integer);

drop type     if exists tp_autenticacao_arrecadacao;

create type tp_autenticacao_arrecadacao as (id           integer,
                                            data         date,
                                            codautent    integer,
                                            autenticacao text,
                                            erro         boolean,
                                            mensagem     text);

create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer) returns tp_autenticacao_arrecadacao as
$$
declare
 rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

begin

 rtp_autenticacao := fc_autentica($1, $2, $3, $4, $5, $6, $7, $8, 0);
 return rtp_autenticacao;

end;
$$ language 'plpgsql';

create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer, integer) returns tp_autenticacao_arrecadacao as
$$
DECLARE
  NUMPRE       ALIAS FOR $1;
  NUMPAR       ALIAS FOR $2;
  DTEMITE      ALIAS FOR $3;
  DTVENC       ALIAS FOR $4;
  SUBDIR       ALIAS FOR $5;
  CONTA        ALIAS FOR $6;
  IPTERM       ALIAS FOR $7;
  INSTIT       ALIAS FOR $8;
  iCodigoGrupo alias for $9;

  rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

  iFormaCorrecao integer default 2;
  ANOUSU         integer;

  CODAUT  INTEGER ;
  IDTERM  INTEGER ;
  HORA    CHAR(5) ;
  IDENT1  CHAR(1);
  IDENT2  CHAR(1);
  IDENT3  CHAR(1);

  UNICA         BOOLEAN := FALSE;
  NUMERO_ERRO   char(200);
  NUMTOT        INTEGER;
  NUMDIG        INTEGER;
  NUMCGM        INTEGER;
  RECORD_NUMPRE  RECORD;
  RECORD_ALIAS   RECORD;
  RECORD_GRAVA   RECORD;
  RECORD_NUMPREF RECORD;
  GRAVA_CORNUMP  RECORD;
  VALOR_RECEITA FLOAT8;
  CORRECAO      FLOAT8;
  JURO          FLOAT8;
  MULTA         FLOAT8;
  DESCONTO      FLOAT8 := 0;
  TOTALZAO      FLOAT8;
  TOTALCORRENTE FLOAT8;
  TOTALCORNUMP  FLOAT8;
  RECEITA       INTEGER;
  K03_RECMUL    INTEGER;
  K03_RECJUR    INTEGER;
  DTOPER        DATE;
  DATAVENC      DATE;
  QUAL_OPER     INTEGER;
  SQLRECIBO     VARCHAR(400);
  VLRCORRECAO   FLOAT8 := 0;
  VLRJUROS      FLOAT8 := 0;
  VLRMULTA      FLOAT8 := 0;
  VLRDESCONTO   FLOAT8 := 0;
  HIST          INTEGER;
  PROCESSA      BOOLEAN;
  NUM_PAR       INTEGER;
  DB_ARQUIVO    VARCHAR(10);
  GRAVAR_RECUNI BOOLEAN DEFAULT FALSE;
  VLRINF    FLOAT8;
  AUTENTICACAO  TEXT;
  totperc   float8;
  TIPOAUTENT  INTEGER;
  iParcelasPagasCanceladas integer;
  iNumpreRecibopaga        integer;
  iNumpreDigitado          integer;
  TEMHISTORICO  TEXT := null;

  v_composicao record;

  nComposCorrecao   numeric(15,2) default 0;
  nComposJuros      numeric(15,2) default 0;
  nComposMulta      numeric(15,2) default 0;

  nCorreComposJuros numeric(15,2) default 0;
  nCorreComposMulta numeric(15,2) default 0;

  lRaise  boolean default false;


  VTIPO   VARCHAR(1);
  VINSTIT INTEGER;

  --
  -- MODIFICACAO REALIZADA PARA UTILIZACAO DAS TAXAS DE EXPEDIENTE JUNTO AO CAIXA
  --
  --
  -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
  --

  GRAVA_RECEITA_RECIBO boolean default false;

  VTAXAACRESCIMO numeric(15,2) default 0;
  RECEITATAXAACRESCIMO INTEGER;

  --
  -- Fim modificacao DB_utiliza_taxa_expediente_autenticacao
  --

  BEGIN

    lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
    if lRaise is true then
      perform fc_debug('<autentica>  Inicio processamento autentica��o...',lRaise, true, false);
    end if;

    select cast( fc_getsession('DB_anousu') as integer )
      into ANOUSU;


    rtp_autenticacao.id           := 0;
    rtp_autenticacao.data         := null;
    rtp_autenticacao.codautent    := 0;
    rtp_autenticacao.autenticacao := '';
    rtp_autenticacao.erro         := true;
    rtp_autenticacao.mensagem     := '';


    select k03_separajurmulparc
      into iFormaCorrecao
      from numpref
     where k03_instit = INSTIT
       and k03_anousu = ANOUSU;

    select k00_instit
      into VINSTIT
      from arreinstit
     where k00_numpre = NUMPRE;
    if VINSTIT is null then

      if lRaise is true then
        perform fc_debug('<autentica>  1 - Fim do processamento da autentica��o...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '9 NUMPRE '|| NUMPRE ||' SEM INSTITUICAO DEFINIDA';
      return rtp_autenticacao;

    end if;

    if VINSTIT <> INSTIT then

      if lRaise is true then
        perform fc_debug('<autentica>  2 - Fim do processamento da autentica��o...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '7 INSTITUICAO INVALIDA PARA AUTENTICACAO DESTE NUMPRE';
      return rtp_autenticacao;

    end if;

    --
    -- VERIFICAMOS SE ESTA SETADA A VARIAVEL DB_utiliza_taxa_expediente_autenticacao NA SESSAO
    -- SE ESTIVER SETADA UTILIZAMOS A LOGICA DE UTILIZAR A TAXA DE EXPEDIENTE NAS AUTENTICACOES
    --
    -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
    --
    if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
      select k07_valorf ,
             k07_codigo
        into VTAXAACRESCIMO,
             RECEITATAXAACRESCIMO
        from TABDESC
             inner join tabdesccadban on k114_tabdesc = codsubrec
             inner join cadban        on k114_codban = k15_codigo
       where k15_conta = CONTA;

       if not found then
          VTAXAACRESCIMO := 0;
       end if;
    end if;
    --
    -- Fim modificacao DB_utiliza_taxa_expediente_autenticacao
    --

    perform ar22_sequencial
       from numprebloqpag
      where ar22_numpre = NUMPRE
        and ar22_numpar = NUMPAR;

    if found then

      if lRaise is true then
        perform fc_debug('<autentica>  3 - Fim do processamento da autentica��o...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '9 D�BITO N�O PODE SER PAGO, FAVOR VERIFICAR COM CHEFE DO SETOR DE ARRECADA��O';
      return rtp_autenticacao;

    end if;

    IF NUMPAR = '000' then

      -- testar se esse numpre est� na recibopaga
      perform k00_numpre
      from recibopaga
      where k00_numnov = NUMPRE;
      if found then

        perform recibopaga.k00_numpre
        from recibopaga
        left join arrecad on recibopaga.k00_numpre = arrecad.k00_numpre
                         and recibopaga.k00_numpar = arrecad.k00_numpar
--                         and recibopaga.k00_receit = arrecad.k00_receit
        where k00_numnov = NUMPRE
          and arrecad.k00_numpre is null;

        if found is true then

          if lRaise is true then
            perform fc_debug('<autentica>  4 - Fim do processamento da autentica��o...',lRaise, false, true);
          end if;

          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '9 RECIBO INCONSISTENTE. EXISTEM PARCELA(S) PAGA(S)/CANCELADA(S). EMITA OUTRO RECIBO.';
          return rtp_autenticacao;


        end if;

      else

        select abs(count(distinct k00_numpar)-k00_numtot)
        into iParcelasPagasCanceladas
        from arrecad
        where k00_numpre = NUMPRE
        group by k00_numtot
        having count(distinct k00_numpar)<>k00_numtot;

        if found is true and iParcelasPagasCanceladas > 0 then

          if lRaise is true then
            perform fc_debug('<autentica>  5 - Fim do processamento da autentica��o...',lRaise, false, true);
          end if;

          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '9 RECIBO INCONSISTENTE. EXISTEM PARCELA(S) PAGA(S)/CANCELADA(S). EMITA OUTRO RECIBO.';
          return rtp_autenticacao;

        end if;

      end if;

    end if;


    FOR RECORD_NUMPREF IN SELECT * FROM NUMPREF WHERE K03_ANOUSU = SUBDIR LOOP
      K03_RECJUR := RECORD_NUMPREF.K03_RECJUR;
      K03_RECMUL := RECORD_NUMPREF.K03_RECMUL;
    END LOOP;

--raise notice '%-%',numpre,numpar;
    VALOR_RECEITA := 0;

    FOR RECORD_NUMPRE IN   SELECT * FROM ( SELECT DISTINCT
                                                  K00_NUMPRE,
                                                  K00_NUMPAR
                                             FROM ARRECAD
                                            WHERE K00_NUMPRE = NUMPRE
                                          UNION ALL
                                           SELECT DISTINCT
                                                  RECIBO.K00_NUMPRE,
                                                  RECIBO.K00_NUMPAR
                                             FROM RECIBO
                                                  LEFT OUTER JOIN ARREPAGA ON RECIBO.K00_NUMPRE = ARREPAGA.K00_NUMPRE
                                             WHERE RECIBO.K00_NUMPRE = NUMPRE
                                               AND ARREPAGA.K00_NUMPRE IS NULL
                                          UNION ALL
                                             SELECT DISTINCT
                                                    K00_NUMPRE,
                                                    K00_NUMPAR
                                               FROM RECIBOPAGA
                                              WHERE K00_NUMNOV = NUMPRE
                                             ) AS X
                                             ORDER BY K00_NUMPRE,K00_NUMPAR
    LOOP

      IF NUMPAR != 0 THEN
        IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
          NUM_PAR := 0;
        ELSE
          NUM_PAR := NUMPAR;
        END IF;
      ELSE
        NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
        perform k00_numpre from arrecad where k00_numpre = numpre;
        if found then
          unica = true;
        else
          unica := exists( select *
                           from db_reciboweb
                          where k99_numpre_n = numpre
                            and k99_numpar = 0 );
        end if;
      END IF;

      IF NUM_PAR != 0 or unica is true THEN
        PROCESSA := FALSE;

        if lRaise is true then
          perform fc_debug('<autentica>  ok '||numpre||' np '||numpar||' npa-'||RECORD_NUMPRE.K00_NUMPRE||'-'||RECORD_NUMPRE.K00_NUMPAR,lRaise,false,false);
        end if;

        FOR RECORD_ALIAS IN  SELECT * FROM (
          SELECT k00_numpar,K00_RECEIT,max(k00_hist) as k00_hist,K00_DTOPER,K00_NUMCGM,fc_calculavenci(K00_NUMPRE,K00_NUMPAR,K00_DTVENC,DTEMITE) AS K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM ARRECAD
           WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR
           GROUP BY k00_numpar,K00_RECEIT,K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,K00_NUMPAR
         UNION ALL
          SELECT k00_numpar, K00_RECEIT, max(k00_hist) as k00_hist, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM RECIBO
           WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR
           GROUP BY k00_numpar,K00_RECEIT, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE
        UNION ALL
          SELECT k00_numpar, K00_RECEIT, max(k00_hist) as k00_hist, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM RECIBOPAGA
           WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
             AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
             AND K00_NUMNOV = NUMPRE
             AND K00_CONTA = 0
           GROUP BY k00_numpar,K00_RECEIT,K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE ) AS X
           ORDER BY K00_NUMPRE,K00_RECEIT
      LOOP

          VALOR_RECEITA := RECORD_ALIAS.K00_VALOR;

          if lRaise then
            perform fc_debug('<autentica>  -- Dentro do for principal --',lRaise,false,false);
            perform fc_debug('<autentica>   Numpar recibopaga : '||RECORD_ALIAS.k00_numpar||' Numpar select principal(db_reciboweb): '||RECORD_NUMPRE.K00_NUMPAR,lRaise,false,false);
          end if;

          IF RECORD_ALIAS.K00_VALOR = 0 THEN
            SELECT Q05_VLRINF
            INTO VLRINF
            FROM ISSVAR
            WHERE Q05_NUMPRE = RECORD_NUMPRE.K00_NUMPRE AND Q05_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;
            IF VLRINF = 0 THEN
              IF UNICA = TRUE THEN

                if lRaise is true then
                  perform fc_debug('<autentica>  6 - Fim do processamento da autentica��o...',lRaise, false, true);
                end if;
                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '5 VALOR UNICA ZERADO';
                return rtp_autenticacao;

              ELSE

                if lRaise is true then
                   perform fc_debug('<autentica>  7 - Fim do processamento da autentica��o...',lRaise, false, true);
                end if;

                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '9 VALOR DA PARCELA ZERADA';
                return rtp_autenticacao;

              END IF;
            END IF;
            VALOR_RECEITA := VLRINF;
          END IF;

          RECEITA  := RECORD_ALIAS.K00_RECEIT;
          DTOPER   := RECORD_ALIAS.K00_DTOPER;
          NUMCGM   := RECORD_ALIAS.K00_NUMCGM;
          DATAVENC := RECORD_ALIAS.K00_DTVENC;
          QUAL_OPER := 0;


          if lRaise is true then
            perform fc_debug('<autentica> Processando inclusao dos dados no arrepaga...',lRaise, false, false);
            perform fc_debug('<autentica> Buscando dados do Numpre: '||NUMPRE||' Parcela: '||NUM_PAR||' Receita: '||RECEITA||' nas tabelas arrecad, recibo e recibopaga ',lRaise, false, false);
          end if;

          FOR RECORD_GRAVA IN SELECT * FROM (
            SELECT arrecad.K00_NUMCGM,
            arrecad.K00_DTOPER,
            arrecad.K00_RECEIT,
            arrecad.K00_HIST,
            arrecad.K00_VALOR,
            arrecad.K00_DTVENC,
            arrecad.K00_NUMPRE,
            arrecad.K00_NUMPAR,
            arrecad.K00_NUMTOT,
            arrecad.K00_NUMDIG,
            arrecad.K00_TIPO ,'ARRECAD' AS DB_ALIAS ,
            (select k00_histtxt
               from arrehist
              where arrehist.k00_numpre = arrecad.k00_numpre
                and arrehist.k00_numpar = arrecad.k00_numpar
                limit 1 ) as k00_histtxt
            FROM ARRECAD
            WHERE arrecad.K00_NUMPRE = NUMPRE AND arrecad.K00_NUMPAR = NUM_PAR AND arrecad.K00_RECEIT = RECEITA

            UNION ALL
            SELECT RECIBO.K00_NUMCGM,
            RECIBO.K00_DTOPER,
            RECIBO.K00_RECEIT,
            RECIBO.K00_HIST,
            RECIBO.K00_VALOR,
            RECIBO.K00_DTVENC,
            RECIBO.K00_NUMPRE,
            RECIBO.K00_NUMPAR,
            RECIBO.K00_NUMTOT,
            RECIBO.K00_NUMDIG,
            RECIBO.K00_TIPO,'RECIBO'  AS DB_ALIAS ,
            (select k00_histtxt
               from arrehist
              where arrehist.k00_numpre = recibo.k00_numpre
                limit 1 ) as k00_histtxt
            FROM RECIBO
            WHERE RECIBO.K00_NUMPRE = NUMPRE
            AND RECIBO.K00_NUMPAR = NUM_PAR
            AND RECIBO.K00_RECEIT = RECEITA

            UNION ALL

            SELECT recibopaga.K00_NUMCGM,
                   recibopaga.K00_DTOPER,
                   recibopaga.K00_RECEIT,
                   recibopaga.K00_HIST,
                   recibopaga.K00_VALOR,
                   recibopaga.K00_DTVENC,
                   recibopaga.K00_NUMPRE,
                   recibopaga.K00_NUMPAR,
                   recibopaga.K00_NUMTOT,
                   recibopaga.K00_NUMDIG,
                   20 AS K00_TIPO,
                   'RECUNI'  AS DB_ALIAS ,
                   (select k00_histtxt
                      from arrehist
                     where arrehist.k00_numpre = recibopaga.k00_numnov
                       limit 1 ) as k00_histtxt
              FROM RECIBOPAGA
             WHERE K00_NUMNOV = NUMPRE
               AND recibopaga.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
               AND recibopaga.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
               AND recibopaga.K00_RECEIT = RECEITA ) AS X
         LOOP

            IF (length(RECORD_GRAVA.K00_HISTTXT)>0 )  THEN
              TEMHISTORICO = RECORD_GRAVA.K00_HISTTXT;
            END IF;

            IF QUAL_OPER = 0 THEN
              HIST      := RECORD_GRAVA.K00_HIST;
              NUMTOT    := RECORD_GRAVA.K00_NUMTOT;
              NUMDIG    := RECORD_GRAVA.K00_NUMDIG;
              QUAL_OPER := 1;
              DB_ARQUIVO = RECORD_GRAVA.DB_ALIAS;
            END IF;

            if lRaise is true then
              perform fc_debug('<autentica>  db_arquivo:'||db_arquivo,lRaise,false,false);
            end if;

            PROCESSA := TRUE;
            IF DB_ARQUIVO = 'ARRECAD' THEN
              INSERT INTO ARRECANT  (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_TIPO,
              K00_TIPOJM
              )
              VALUES (
              RECORD_GRAVA.K00_NUMCGM,
              RECORD_GRAVA.K00_DTOPER,
              RECORD_GRAVA.K00_RECEIT,
              RECORD_GRAVA.K00_HIST,
              RECORD_GRAVA.K00_VALOR,
              RECORD_GRAVA.K00_DTVENC,
              RECORD_GRAVA.K00_NUMPRE,
              RECORD_GRAVA.K00_NUMPAR,
              RECORD_GRAVA.K00_NUMTOT,
              RECORD_GRAVA.K00_NUMDIG,
              RECORD_GRAVA.K00_TIPO,
              0);
            ELSE

              if lRaise is true then
                perform fc_debug('<autentica> 1 - Inserindo dados na arrepaga... Numpre: '||RECORD_GRAVA.K00_NUMPRE||' Numpar:'||RECORD_GRAVA.K00_NUMPAR||' Receita:'||RECORD_GRAVA.K00_RECEIT,lRaise,false,false);
              end if;
              INSERT INTO ARREPAGA
              (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_CONTA,
              K00_DTPAGA)
              VALUES (
              RECORD_GRAVA.K00_NUMCGM,
              RECORD_GRAVA.K00_DTOPER,
              RECORD_GRAVA.K00_RECEIT,
              RECORD_GRAVA.K00_HIST,
              RECORD_GRAVA.K00_VALOR,
              DATAVENC,
              RECORD_GRAVA.K00_NUMPRE,
              RECORD_GRAVA.K00_NUMPAR,
              RECORD_GRAVA.K00_NUMTOT,
              RECORD_GRAVA.K00_NUMDIG,
              CONTA,
              DTEMITE);
            END IF;
          END LOOP;

          IF DB_ARQUIVO = 'ARRECAD' THEN
            DELETE FROM ARRECAD WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR AND K00_RECEIT = RECEITA;
-- CALCULA CORRECAO
            IF VALOR_RECEITA <> 0 THEN
--       raise notice '%-%-%-%-%-%',RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DTVENC;

              if iFormaCorrecao = 1 then

                --if  <> 918 then
                  select rnCorreComposJuros, rnCorreComposMulta, rnComposCorrecao, rnComposJuros, rnComposMulta
                    into nCorreComposJuros, nCorreComposMulta, nComposCorrecao, nComposJuros, nComposMulta
                    from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, record_alias.k00_hist, dtoper, dtvenc, subdir, datavenc);

                  if lRaise is true then
                     perform fc_debug('<autentica>  1=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise,false,false);
                  end if;

                  valor_receita = valor_receita + nComposCorrecao;

                  if lRaise is true then
                     perform fc_debug('<autentica>  2=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise,false,false);
                  end if;

                --else

                  --valor_receita = valor_receita - RECORD_GRAVA.K00_VALOR;

                --end if;

              end if;

              CORRECAO := ROUND( FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DATAVENC) , 2 );

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 1: '||CORRECAO,lRaise,false,false);
              end if;

              valor_receita = valor_receita - nComposCorrecao;

              CORRECAO := ROUND( CORRECAO - VALOR_RECEITA , 2 );

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 2: '||CORRECAO||' - nCorreComposJuros: '||nCorreComposJuros||' - nCorreComposMulta: '||nCorreComposMulta,lRaise,false,false);
                perform fc_debug('<autentica>  codrec -- '||RECEITA||' correcao -- '||CORRECAO||' receita -- '||VALOR_RECEITA,lRaise,false,false);
              end if;

              correcao := correcao + nCorreComposJuros + nCorreComposMulta;

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 3: '||CORRECAO,lRaise,false,false);
                perform fc_debug('<autentica>  codrec -- '||RECEITA||' correcao -- '||CORRECAO||' receita -- '||VALOR_RECEITA,lRaise,false,false);
              end if;

            ELSE
              CORRECAO := 0;
            END IF;


            VLRCORRECAO := VLRCORRECAO + CORRECAO + VALOR_RECEITA;

            if lRaise is true then
              perform fc_debug('<autentica>  VLRCORRECAO (2): '||VLRCORRECAO,lRaise,false,false);
            end if;

--          IF (VALOR_RECEITA + CORRECAO) <> 0 AND UNICA = FALSE THEN
            IF (VALOR_RECEITA + CORRECAO) <> 0 THEN

              if lRaise is true then
                 perform fc_debug('<autentica>  FC_JUROS('||RECEITA||','||DATAVENC||','||DTEMITE||','||DTOPER||',false,'||SUBDIR||')',lRaise,false,false);
                 perform fc_debug('<autentica>  FC_MULTA('||RECEITA||','||DATAVENC||','||DTEMITE||','||DTOPER||','||SUBDIR||')',lRaise,false,false);
              end if;
-- CALCULA JUROS
              JURO  := ROUND(( CORRECAO+VALOR_RECEITA) * FC_JUROS(RECEITA,DATAVENC,DTEMITE,DTOPER,FALSE,SUBDIR),2 );
              JURO = JURO + nComposJuros;
-- CALCULA MULTA
              MULTA := ROUND(( CORRECAO+VALOR_RECEITA) * FC_MULTA(RECEITA,DATAVENC,DTEMITE,DTOPER,SUBDIR),2 );
              MULTA = MULTA + nComposMulta;

              SELECT K02_RECMUL, K02_RECJUR
              INTO K03_RECMUL,K03_RECJUR
              FROM TABREC
              WHERE K02_CODIGO = RECEITA;
              IF K03_RECMUL IS NULL THEN
                K03_RECMUL = RECEITA_MUL;
              END IF;
              IF K03_RECJUR IS NULL THEN
                K03_RECJUR = RECEITA_JUR;
              END IF;

              IF K03_RECJUR = 0 OR K03_RECMUL = 0 OR K03_RECJUR = K03_RECMUL THEN
                IF JURO+MULTA <> 0 THEN
                  VLRJUROS := VLRJUROS + JURO;
                  VLRMULTA := VLRMULTA + MULTA;

                  if lRaise is true then
                    perform fc_debug('<autentica> 2 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECJUR,
                  400,
                  (JURO+MULTA),
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );
                END IF;
              ELSE
                IF JURO <> 0 THEN
                  VLRJUROS := VLRJUROS + JURO;

                  if lRaise is true then
                    perform fc_debug('<autentica> 3 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECJUR,
                  400,
                  JURO,
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );

                END IF;
                IF MULTA <> 0 THEN
                  VLRMULTA := VLRMULTA + MULTA;

                  if lRaise is true then
                    perform fc_debug('<autentica> 4 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECMUL,
                  401,
                  MULTA,
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );
                END IF;
              END IF;
            END IF;

            if lRaise is true then
              perform fc_debug('<autentica>  valor_receita + correcao = '||(VALOR_RECEITA + CORRECAO),lRaise,false,false);
            end if;

            IF (VALOR_RECEITA + CORRECAO) <> 0 THEN
--              raise notice 'fc_desconto(%,%,%,%,%,%,%,%)',RECEITA,DTEMITE,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR,NUMPRE;
              DESCONTO := COALESCE(FC_DESCONTO(RECEITA,DTEMITE,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR,NUMPRE), 0);
              if unica then
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                                    if lRaise is true then
                                      perform fc_debug('<autentica> 5 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 990,(VALOR_RECEITA+CORRECAO)-DESCONTO, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
--                  raise notice 'inserindo unica | valor -- % desconto -- %',(VALOR_RECEITA+CORRECAO),DESCONTO
                else
                                    if lRaise is true then
                                      perform fc_debug('<autentica> 6 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 990,(VALOR_RECEITA+CORRECAO), DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                END IF;

              if lRaise is true then
                perform fc_debug('<autentica>   (receita + correcao) = '||(VALOR_RECEITA+CORRECAO)||' desconto = '||(DESCONTO*-1),lRaise,false,false);
              end if;

              else

                                if lRaise is true then
                                  perform fc_debug('<autentica> 7 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                end if;
                INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                              VALUES (NUMCGM, DTEMITE, RECEITA, HIST + 100, VALOR_RECEITA+CORRECAO, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                --CALCULAR DESCONTO
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                                    if lRaise is true then
                                      perform fc_debug('<autentica> 8 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 918, DESCONTO*-1, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                END IF;
              end if;

            END IF;


          ELSE

            if lRaise then
              perform fc_debug('<autentica>  else db_arquivo != arrecad',lRaise,false,false);
            end if;

            IF RECORD_GRAVA.K00_TIPO = 400 THEN
              VLRJUROS := VLRJUROS + RECORD_ALIAS.K00_VALOR;
            ELSE
              IF RECORD_GRAVA.K00_TIPO = 401 THEN
                VLRMULTA := VLRMULTA + RECORD_ALIAS.K00_VALOR;
              ELSE
                VLRCORRECAO := VLRCORRECAO + RECORD_ALIAS.K00_VALOR ;
              END IF;
            END IF ;

            IF DB_ARQUIVO = 'RECUNI' THEN
              if lRaise then
                perform fc_debug('<autentica>  db_arquivo = recuni',lRaise,false,false);
              end if;

              INSERT INTO ARRECANT
              (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_TIPO,
              K00_TIPOJM
              )
              SELECT K00_NUMCGM,
                     K00_DTOPER,
                     K00_RECEIT,
                     K00_HIST,
                     K00_VALOR,
                     K00_DTVENC,
                     K00_NUMPRE,
                     K00_NUMPAR,
                     K00_NUMTOT,
                     K00_NUMDIG,
                     K00_TIPO,
                     K00_TIPOJM
                FROM ARRECAD
               WHERE ARRECAD.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and ARRECAD.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 AND ARRECAD.K00_RECEIT = RECEITA;

              DELETE FROM ARRECAD
               WHERE ARRECAD.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and ARRECAD.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 AND ARRECAD.K00_RECEIT = RECEITA;

              UPDATE RECIBOPAGA
                 SET K00_CONTA = CONTA
               WHERE RECIBOPAGA.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and RECIBOPAGA.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 and RECIBOPAGA.K00_RECEIT = RECEITA
                 and RECIBOPAGA.K00_NUMNOV = NUMPRE;

            END IF;
          END IF;
        END LOOP;
      END IF;
    END LOOP;

    --
    -- ADICIONADA A SOMA DA VARIAVEL VTAXAACRESCIMO A VARIAVEL TOTALZAO
    -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
    -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
    --
    if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
      TOTALZAO = VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO;
    else
      TOTALZAO = VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO;
    end if;
    if lRaise is true then
       perform fc_debug('<autentica>  VLRCORRECAO = '||VLRCORRECAO||' VLRJUROS = '||VLRJUROS||' VLRMULTA = '||VLRMULTA||' VLRDESCONTO = '||VLRDESCONTO,lRaise,false,false);
       perform fc_debug('<autentica>  k12_valor: '||TOTALZAO,lRaise,false,false);
    end if;

    IF PROCESSA = TRUE THEN

--RAISE NOTICE '%X%X%X%', VLRCORRECAO,VLRJUROS,VLRMULTA,VLRDESCONTO;

      SELECT K11_ID,K11_IDENT1,K11_IDENT2,K11_IDENT3,K11_TIPAUTENT
        INTO IDTERM,IDENT1,IDENT2,IDENT3,TIPOAUTENT
        FROM CFAUTENT
       WHERE K11_IPTERM = IPTERM
         AND K11_INSTIT = INSTIT;

--raise notice '-%-',idterm;
      IF NOT IDTERM IS NULL AND TIPOAUTENT != 3 THEN
        SELECT MAX(K12_AUTENT)
        INTO CODAUT
        FROM CORRENTE WHERE K12_ID = IDTERM AND K12_DATA = DTEMITE;
        IF CODAUT IS NULL THEN
          CODAUT := 1;
        ELSE
          CODAUT := CODAUT + 1;
        END IF;

-- GRAVA AUTENTICACAO
        hora := to_char(now(), 'HH24:MI');

         --
         -- ADICIONADA A SOMA DA VARIAVEL VTAXAACRESCIMO AOS VALORES QUE SERAO GRAVADOS NO CAMPO K12_VALOR
         -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
         -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
         --
         if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then

           BEGIN
              INSERT INTO CORRENTE ( k12_id, k12_data, k12_autent, k12_hora, k12_conta, k12_valor, k12_estorn, k12_instit )
                            values ( IDTERM, DTEMITE, CODAUT, hora, conta, ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO), false, INSTIT );
           EXCEPTION WHEN OTHERS THEN
             rtp_autenticacao.erro     := true;
             rtp_autenticacao.mensagem := SQLERRM;
             return rtp_autenticacao;
           END;

         else

           BEGIN
              INSERT INTO CORRENTE ( k12_id, k12_data, k12_autent, k12_hora, k12_conta, k12_valor, k12_estorn, k12_instit )
                            values ( IDTERM, DTEMITE, CODAUT, hora, conta, ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO), false, INSTIT );
           EXCEPTION WHEN OTHERS THEN
             rtp_autenticacao.erro     := true;
             rtp_autenticacao.mensagem := SQLERRM;
             return rtp_autenticacao;
           END;

         end if;



-- GRAVA GRUPO DA AUTENTICA��O
   if iCodigoGrupo <> 0 then
     insert into
       corgrupocorrente (
          k105_sequencial,
          k105_corgrupo,
          k105_data,
          k105_autent,
          k105_id,
            k105_corgrupotipo
        ) values (
          nextval('corgrupocorrente_k105_sequencial_seq'),
          iCodigoGrupo,
          DTEMITE,
          CODAUT,
          IDTERM,
            3
      );
   end if;

-- GRAVA HISTORICO
        IF TEMHISTORICO IS NOT NULL THEN
          INSERT INTO CORHIST ( K12_ID, K12_DATA, K12_AUTENT, K12_HISTCOR)
                       values ( IDTERM, DTEMITE, CODAUT, TEMHISTORICO );
        END IF;

        --
        -- Setamos a variavel GRAVA_RECEITA_RECIBO como false para que seja gravada a taxa de expediente
        -- Esta modificacao so eh utlizada quando a variavel DB_utiliza_taxa_expediente_autenticacao esta setada na sessao
        --
        -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
        --

        if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
           GRAVA_RECEITA_RECIBO := FALSE;
        end if;

        --
        -- FIM MODIFICACAO DB_utiliza_taxa_expediente_autenticacao
        --

        FOR RECORD_NUMPRE IN SELECT DISTINCT K00_NUMPRE,K00_NUMPAR
                               FROM ARREPAGA
                              WHERE K00_NUMPRE = NUMPRE
                            UNION ALL
                             SELECT DISTINCT
                                    recibopaga.K00_NUMPRE,
                                    recibopaga.K00_NUMPAR
                               FROM RECIBOPAGA
                               WHERE K00_NUMNOV = NUMPRE
                               ORDER BY K00_NUMPRE,K00_NUMPAR
        LOOP
/*
        perform k12_numpre, k12_numpar
           from cornump
                inner join corrente on corrente.k12_id     = cornump.k12_id
                                   and corrente.k12_data   = cornump.k12_data
                                   and corrente.k12_autent = cornump.k12_autent
          where cornump.k12_numpre = record_numpre.k00_numpre
            and cornump.k12_numpar = record_numpre.k00_numpar
          group by k12_numpre, k12_numpar
         having cast(round(sum(cornump.k12_valor),2) as numeric) > cast(round(0,2) as numeric);

         if found then
           raise notice 'encontrou parcela paga passando para o proximo';
           continue;
         end if;
*/
          -- FABRIZIO
          IF NUMPAR != 0 THEN
            IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
              NUM_PAR := 0;
            ELSE
              NUM_PAR := NUMPAR;
            END IF;
          ELSE
            NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
            UNICA = TRUE;
            perform k00_numpre from arrecad where k00_numpre = numpre;
            if found then
              unica = true;
            else
              unica := exists( select *
                                 from db_reciboweb
                                where k99_numpre_n = numpre
                                  and k99_numpar = 0 );
            end if;

          END IF;


          IF NUM_PAR != 0 or unica is true THEN

          --
          -- ADICIONADA INCLUSAO DA TAXA DE EXPEDIENTE NA ARREPAGA
          -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
          -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
          --
          -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
          --
          if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then

             IF GRAVA_RECEITA_RECIBO = FALSE AND VTAXAACRESCIMO > 0 THEN

                INSERT INTO ARREPAGA ( K00_NUMCGM,
                                       K00_DTOPER,
                                       K00_RECEIT,
                                       K00_HIST,
                                       K00_VALOR,
                                       K00_DTVENC,
                                       K00_NUMPRE,
                                       K00_NUMPAR,
                                       K00_NUMTOT,
                                       K00_NUMDIG,
                                       K00_CONTA,
                                       K00_DTPAGA )
                              VALUES ( NUMCGM,
                                       DTEMITE,
                                       RECEITATAXAACRESCIMO,
                                       200,
                                       VTAXAACRESCIMO,
                                       DATAVENC,
                                       RECORD_NUMPRE.K00_NUMPRE,
                                       NUM_PAR,
                                       NUMTOT,
                                       NUMDIG,
                                       CONTA,
                                       DTEMITE );

                GRAVA_RECEITA_RECIBO := TRUE;

             END IF;

          end if;
          --
          -- FIM MODIFICACAO DB_utiliza_taxa_expediente_autenticacao
          --

            FOR GRAVA_CORNUMP IN SELECT K00_RECEIT,SUM(K00_VALOR) AS VALOR
                                   FROM ARREPAGA
                                  WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                                AND K00_NUMPAR = NUM_PAR
                                  GROUP BY K00_RECEIT
      LOOP

-- quando grava no corrente já grava a instituição passada para esta função
-- quando for gravar aqui no corrente, verifica a instituição da receita
-- se ela confere com a instituição informada
              SELECT K02_TIPO
                INTO VTIPO
                FROM TABREC
               WHERE K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT;
              if lRaise then
               perform fc_debug('<autentica>  Tipo de receita: '||VTIPO,lRaise,false,false);
              end if;

              IF VTIPO = 'O' THEN
                SELECT O70_INSTIT
                  INTO VINSTIT
                  FROM TABORC
                       inner join orcreceita on o70_codrec = k02_codrec
                                            and o70_anousu = k02_anousu
                 WHERE TABORC.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                   AND TABORC.K02_ANOUSU = extract( year from DTEMITE)::integer;

                if VINSTIT is null then
                  rtp_autenticacao.erro     := true;
                  rtp_autenticacao.mensagem := '8 NAO ENCONTRADO REGISTRO DA RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' NA ORCRECEITA PARA O ANO '||extract( year from DTEMITE)::integer;
                  return rtp_autenticacao;

                end if;

              ELSE

                SELECT C61_INSTIT
                  INTO VINSTIT
                  FROM TABPLAN
                       INNER JOIN CONPLANOREDUZ ON C61_REDUZ  = k02_REDUZ
                                               AND C61_ANOUSU = K02_ANOUSU
                                               AND C61_INSTIT = INSTIT
                 WHERE TABPLAN.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                   AND K02_ANOUSU = extract( year from DTEMITE)::integer;

                if VINSTIT is null then
                  rtp_autenticacao.erro     := true;
                  rtp_autenticacao.mensagem := '7 NAO ENCONTRADO REGISTRO DA RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' NA TABPLAN PARA O ANO '||extract( year from DTEMITE)::integer;
                  return rtp_autenticacao;
                end if;

                if lRaise is true then
                  perform fc_debug('<autentica>  GRAVA_CORNUMP.K00_RECEIT: '||GRAVA_CORNUMP.K00_RECEIT||', INSTIT: '||INSTIT||', DTEMITE: '||DTEMITE||', VINSTIT:'||VINSTIT,lRaise,false,false);
                end if;

              END IF ;

              IF VINSTIT IS NULL  or VINSTIT != INSTIT THEN

                if lRaise is true then
                  perform fc_debug('<autentica>  8 - Fim do processamento da autentica��o... ',lRaise, false, true);
                end if;
                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '6 RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' DE INSTITUICAO DIFERENTE';
                return rtp_autenticacao;

              END IF;

              if lRaise is true then
                perform fc_debug('<autentica>  numpre: '||RECORD_NUMPRE.K00_NUMPRE||' - numpar: '||NUM_PAR||' - receita: '||GRAVA_CORNUMP.K00_RECEIT,lRaise,false,false);
              end if;
              INSERT INTO CORNUMP (k12_id,k12_data,k12_autent,k12_numpre,k12_numpar,k12_numtot,k12_numdig,k12_receit,k12_valor,k12_numnov)
                           values ( IDTERM, DTEMITE, CODAUT, RECORD_NUMPRE.K00_NUMPRE,NUM_PAR, NUMTOT,NUMDIG,GRAVA_CORNUMP.K00_RECEIT,GRAVA_CORNUMP.VALOR, NUMPRE );

            END LOOP;
          END IF;
        END LOOP;
      ELSE
        IF TIPOAUTENT = 3 THEN
-- ERRO QUANDO O TERMINAL NAO ESTA CADASTRADO

          if lRaise is true then
            perform fc_debug('<autentica>  9 - Fim do processamento da autentica��o...',lRaise, false, true);
          end if;
          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '1 BAIXADO SEM AUTENTICACAO';
          return rtp_autenticacao;

        ELSE

          if lRaise is true then
            perform fc_debug('<autentica>  10 - Fim do processamento da autentica��o...',lRaise, false, true);
          end if;
          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '2 TERMINAL NAO CADASTRADO';
          return rtp_autenticacao;

        END IF;
      END IF;
-- AUTENTICACAO CORRETA
perform fc_debug('<autentica>  data -- '||IDTERM||' idterm -- '||DTEMITE||' autent -- '||CODAUT,lRaise,false,false);

      select VAL_CORRENTE - VAL_CORNUMP, VAL_CORRENTE, VAL_CORNUMP
        into TOTALZAO, TOTALCORRENTE, TOTALCORNUMP
        from (SELECT (SELECT ROUND(SUM(K12_VALOR),2)
                FROM CORRENTE
                       WHERE K12_DATA   = DTEMITE
                         AND K12_ID     = IDTERM
                         AND K12_AUTENT = CODAUT ) AS VAL_CORRENTE,
                     (SELECT ROUND(SUM(K12_VALOR),2)
              FROM CORNUMP
             WHERE K12_DATA   = DTEMITE
                         AND K12_ID     = IDTERM
                         AND K12_AUTENT = CODAUT) AS VAL_CORNUMP
             ) AS X;

      if lRaise is true then
        perform fc_debug('<autentica>  TOTALZAO: '||TOTALZAO||' - VAL_CORRENTE: '||TOTALCORRENTE||' - VAL_CORNUMP: '||TOTALCORNUMP,lRaise,false,false);
      end if;

      IF TOTALZAO <> 0 THEN

        if lRaise is true then
          perform fc_debug('<autentica>  11 - Fim do processamento da autentica��o...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '5 - INCONSISTENCIA NA AUTENTICACAO! CONTATE SUPORTE!';
        return rtp_autenticacao;

      END IF;

      if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
         AUTENTICACAO:= TO_CHAR(CODAUT,'999999') ||
                        DTEMITE ||
                        IDENT1 ||
                        IDENT2 ||
                        IDENT3 ||
                        TO_CHAR(NUMPRE,'99999999') ||
                        TO_CHAR(NUM_PAR,'999') ||
                        TO_CHAR(ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO),'999999999.99')||'+';
      else
         AUTENTICACAO:= TO_CHAR(CODAUT,'999999') ||
                        DTEMITE ||
                        IDENT1 ||
                        IDENT2 ||
                        IDENT3 ||
                        TO_CHAR(NUMPRE,'99999999') ||
                        TO_CHAR(NUM_PAR,'999') ||
                        TO_CHAR(ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO),'999999999.99')||'+';
      end if;

      INSERT INTO CORAUTENT (K12_ID,
                             K12_DATA,
                             K12_AUTENT,
                             K12_CODAUTENT)
                     VALUES (IDTERM,
                             DTEMITE,
                             CODAUT,
                             AUTENTICACAO);

      if lRaise is true then
        perform fc_debug('<autentica>  12 - Fim do processamento da autentica��o...',lRaise, false, true);
      end if;

      rtp_autenticacao.id           := IDTERM;
      rtp_autenticacao.data         := DTEMITE;
      rtp_autenticacao.codautent    := CODAUT;
      rtp_autenticacao.autenticacao := '1' || AUTENTICACAO;
      rtp_autenticacao.erro         := false;
      rtp_autenticacao.mensagem     := '1' || AUTENTICACAO;
      return rtp_autenticacao;

    ELSE
-- NUMPRE NAO PROCESSADO
--raise notice 'numpar: % - NUMPRE % ',NUMPAR,NUMPRE;
      IF NUMPAR = 0 THEN
        SELECT SUM(K00_VALOR)
          INTO VLRCORRECAO
          FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE ;
      ELSE
        SELECT SUM(K00_VALOR)
          INTO VLRCORRECAO
          FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE
           AND K00_NUMPAR = NUMPAR;
      END IF;
      IF VLRCORRECAO IS NULL THEN

        if lRaise is true then
          perform fc_debug('<autentica>  13 - Fim do processamento da autentica��o...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '4 VALOR ZERADO';
        return rtp_autenticacao;

      ELSE

        if lRaise is true then
          perform fc_debug('<autentica>  14 - Fim do processamento da autentica��o...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '3 VALOR PAGO';
        return rtp_autenticacao;

      END IF;
    END IF;

  END;
$$ language 'plpgsql';create or replace function fc_baixabanco( cod_ret integer, datausu date) returns varchar as
$$
declare

  retorno                          boolean default false;

  r_codret                         record;
  r_idret                          record;
  r_divold                         record;
  r_receitas                       record;
  r_idunica                        record;
  q_disrec                         record;
  r_testa                          record;

  x_totreg                         float8;
  valortotal                       float8;
  valorjuros                       float8;
  valormulta                       float8;
  fracao                           float8;
  nVlrRec                          float8;
  nVlrTfr                          float8;
  nVlrRecm                         float8;
  nVlrRecj                         float8;

  _testeidret                      integer;
  vcodcla                          integer;
  gravaidret                       integer;
  v_nextidret                      integer;
  conta                            integer;

  v_contador                       integer;
  v_somador                        numeric(15,2) default 0;
  v_valor                          numeric(15,2) default 0;

  v_valor_sem_round                float8;
  v_diferenca_round                float8;

  dDataCalculoRecibo               date;
  dDataReciboUnica                 date;

  v_contagem                       integer;
  primeirarec                      integer default 0;
  primeirarecj                     integer default 0;
  primeirarecm                     integer default 0;
  primeiranumpre                   integer;
  primeiranumpar                   integer;

  nBloqueado                       integer;

  valorlanc                        float8;
  valorlancj                       float8;
  valorlancm                       float8;

  oidrec                           int8;

  autentsn                         boolean;

  valorrecibo                      float8;

  v_total1                         float8 default 0;
  v_total2                         float8 default 0;

  v_estaemrecibopaga               boolean;
  v_estaemrecibo                   boolean;
  v_estaemarrecadnormal            boolean;
  v_estaemarrecadunica             boolean;
  lVerificaReceita                 boolean;
  lClassi                          boolean;
  lReciboInvalidoPorTrocaDeReceita boolean default false;
  lReciboPossuiPgtoParcial         boolean default false;

  nSimDivold                       integer;
  nNaoDivold                       integer;
  iQtdeParcelasAberto              integer;
  iQtdeParcelasRecibo              integer;

  nValorSimDivold                  numeric(15,2) default 0;
  nValorNaoDivold                  numeric(15,2) default 0;
  nValorTotDivold                  numeric(15,2) default 0;

  nValorPagoDivold                 numeric(15,2) default 0;
  nTotValorPagoDivold              numeric(15,2) default 0;

  nTotalRecibo                     numeric(15,2) default 0;
  nTotalNovosRecibos               numeric(15,2) default 0;

  nTotalDisbancoOriginal           numeric(15,2) default 0;
  nTotalDisbancoDepois             numeric(15,2) default 0;

  iNumnovDivold                    integer;
  iIdret                           integer;
  v_diferenca                      float8 default 0;

  cCliente                         varchar(100);
  iIdRetProcessar                  integer;

  -- Abatimentos
  lAtivaPgtoParcial                boolean default false;
  lInsereJurMulCorr                boolean default true;

  iAbatimento                      integer;
  iAbatimentoArreckey              integer;
  iArreckey                        integer;
  iArrecadCompos                   integer;
  iNumpreIssVar                    integer;
  iNumpreRecibo                    integer;
  iNumpreReciboAvulso              integer;
  iTipoDebitoPgtoParcial           integer;
  iTipoAbatimento                  integer;
  iTipoReciboAvulso                integer;
  iReceitaCredito                  integer;
  iRows                            integer;
  iSeqIdRet                        integer;
  iNumpreAnterior                  integer default 0;

  nVlrCalculado                    numeric(15,2) default 0;
  nVlrPgto                         numeric(15,2) default 0;
  nVlrJuros                        numeric(15,2) default 0;
  nVlrMulta                        numeric(15,2) default 0;
  nVlrCorrecao                     numeric(15,2) default 0;
  nVlrHistCompos                   numeric(15,2) default 0;
  nVlrJurosCompos                  numeric(15,2) default 0;
  nVlrMultaCompos                  numeric(15,2) default 0;
  nVlrCorreCompos                  numeric(15,2) default 0;
  nVlrPgtoParcela                  numeric(15,2) default 0;
  nVlrDiferencaPgto                numeric(15,2) default 0;
  nVlrTotalRecibopaga              numeric(15,2) default 0;
  nVlrTotalHistorico               numeric(15,2) default 0;
  nVlrTotalJuroMultaCorr           numeric(15,2) default 0;
  nVlrReceita                      numeric(15,2) default 0;
  nVlrAbatido                      numeric(15,2) default 0;
  nVlrDiferencaDisrec              numeric(15,2) default 0;
  nVlrInformado                    numeric(15,2) default 0;
  nVlrTotalInformado               numeric(15,2) default 0;

  nVlrToleranciaPgtoParcial        numeric(15,2) default 0;
  nVlrToleranciaCredito            numeric(15,2) default 0;

  nPercPgto                        numeric;
  nPercReceita                     numeric;
  nPercDesconto                    numeric;

  iCountDebitoOrigem               integer default 0;
  iCountRecibopaga                 integer default 0;

  iAnoSessao                       integer;
  iInstitSessao                    integer;

  rReciboPaga                      record;
  rContador                        record;
  rRecordDisbanco                  record;
  rRecordBanco                     record;
  rRecord                          record;
  rRecibo                          record;
  rAcertoDiferenca                 record;

  /**
   * variavel de controle do numpre , se tiver ativado o pgto parcial, e essa variavel for dif. de 0
   * os numpres a partir dele ser�o tratados como pgto parcial, abaixo, sem pgto parcial
   */
  iNumprePagamentoParcial          integer default 0;

  lRaise                           boolean default false;
  sDebug                           text;

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

    insert into discla ( codcla,  codret,  dtcla,   instit )
                values ( vcodcla, cod_ret, datausu, iInstitSessao );

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
        perform fc_debug('  <PgtoParcial>  - lan�a recibo avulso j� pago ',lRaise,false,false);
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

      insert into reciborecurso ( k00_sequen, k00_numpre, k00_recurso )
           select nextval('reciborecurso_k00_sequen_seq'),
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

        select k00_codbco, k00_codage, fc_numbco(k00_codbco,k00_codage) as fc_numbco
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
                                 end;

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
        perform fc_debug('  <PgtoParcial>  - ######## Incluido na tmpnaoprocessar',lRaise,false,false);
      end if;

      for iRows in 1..( r_idret.ocorrencias - 1 ) loop

          perform fc_debug('  <PgtoParcial>  - Inserindo na tmpnaoprocessar - Pagamento duplicado em recibos diferentes', lRaise);
          perform fc_debug('  <PgtoParcial>  - ######## Incluido na tmpnaoprocessar numpre: ' || r_idret.k00_numpre,      lRaise);
          perform fc_debug('  <PgtoParcial>                                         numpar: ' || r_idret.k00_numpar,      lRaise);

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

      lReciboInvalidoPorTrocaDeReceita = false;

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

       perform 1
          from recibopaga
         where recibopaga.k00_numnov = r_idret.numpre
           and recibopaga.k00_hist   not in (918, 970, 400, 401)
           and not exists (select 1
                             from arrecad
                            where arrecad.k00_numpre = recibopaga.k00_numpre
                              and arrecad.k00_numpar = recibopaga.k00_numpar
                              and arrecad.k00_receit = recibopaga.k00_receit );
      if found then
        lReciboInvalidoPorTrocaDeReceita := true;
      end if;

      perform fc_debug('  <PgtoParcial>  - Numpre : '||r_idret.numpre, lRaise);
      perform fc_debug('  <PgtoParcial>  - Data para pagamento : '||fc_proximo_dia_util(r_idret.k00_dtpaga), lRaise);
      perform fc_debug('  <PgtoParcial>  - Encontrou outro abatimento : '||lReciboPossuiPgtoParcial, lRaise);
      perform fc_debug('  <PgtoParcial>  - Trocou de receita : '||lReciboInvalidoPorTrocaDeReceita, lRaise);

      if     fc_proximo_dia_util(r_idret.k00_dtpaga) >= r_idret.dtpago
         and lReciboPossuiPgtoParcial is false
         and lReciboInvalidoPorTrocaDeReceita is false
      then

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
             and k00_hist   in (918, 970);

          delete
            from abatimentoarreckey
           using arreckey
           where k00_sequencial = k128_arreckey
             and k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   in (918, 970);

          delete
            from arreckey
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   in (918, 970);

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

        -- Consulta valor total historico do debito
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
                                  when k00_hist in (918, 970) then 0
                                  else ( substr(fc_calcula,15,13)::float8 - substr(fc_calcula, 2,13)::float8 )::float8
                                end as vlrcorrecao,
                                case when k00_hist in (918, 970) then 0::float8 else substr(fc_calcula,28,13)::float8 end as vlrjuros,
                                case when k00_hist in (918, 970) then 0::float8 else substr(fc_calcula,41,13)::float8 end as vlrmulta
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

            if round(nVlrPgtoParcela,2) <= 0 and rRecord.k00_hist not in (918, 970) then

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

           if lRaise is true then
             perform fc_debug('  <PgtoParcial>  - '||sDebug,lRaise,false,false);
           end if;

           insert into arrematric select distinct
                                         iNumpreReciboAvulso,
                                         arrematric.k00_matric,
                                         -- colocado 100 % fixo porque o numpre do recibo avulso gerado se trata de credito
                                         -- e nao vai ter divisao de percentual entre mais de um numpre da mesma matricula
                                         100 as k00_perc
                                    from recibopaga
                                         inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
                                   where recibopaga.k00_numnov = r_idret.numpre;

           insert into arreinscr  select distinct
                                         iNumpreReciboAvulso,
                                         arreinscr.k00_inscr,
                                         -- colocado 100 % fixo porque o numpre do recibo avulso gerado se trata de credito
                                         -- e nao vai ter divisao de percentual entre mais de um numpre da mesma matricula
                                         100 as k00_perc
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
              and recibopaga.k00_hist   not in (918, 970)
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
                            and recibopaga.k00_hist   not in (918, 970)
                            and exists ( select 1
                                           from arrecad
                                          where arrecad.k00_numpre = recibopaga.k00_numpre
                                            and arrecad.k00_numpar = recibopaga.k00_numpar
                                         --   and arrecad.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arrecant
                                          where arrecant.k00_numpre = recibopaga.k00_numpre
                                            and arrecant.k00_numpar = recibopaga.k00_numpar
                                         --   and arrecant.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arreold
                                          where arreold.k00_numpre = recibopaga.k00_numpre
                                            and arreold.k00_numpar = recibopaga.k00_numpar
                                         --   and arreold.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arreprescr
                                          where arreprescr.k30_numpre = recibopaga.k00_numpre
                                            and arreprescr.k30_numpar = recibopaga.k00_numpar
                                         --   and arreprescr.k30_receit = recibopaga.k00_receit
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

          insert into tmpnaoprocessar values (iIdret);

        elsif v_contador >= 1 then
          return '21 - IDRET ' || r_codret.idret || ' COM MAIS DE UM PAGAMENTO NO MESMO ARQUIVO. CONTATE SUPORTE PARA VERIFICA��ES!';
        end if;

      end if;

    end if;

    -- Validamos o numpre para ver se nao esta duplicado em algum lugar
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

  /**
   * Adicionada valida��o para separar recibos e carnes que possuiam suas origens importadas para divida
   * apenas quando pagamento parcial estiver DESATIVADO
   */
  if lAtivaPgtoParcial = false then

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

        perform *
           from abatimentodisbanco
          where k132_idret = r_codret.idret;

        if found then
          continue;
        end if;

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
                             --and divold.k10_receita = recibopaga.k00_receit
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

          update disbancotxtreg
             set k35_idret = v_nextidret
           where k35_idret = r_codret.idret;

          delete
            from issarqsimplesregdisbanco
           where q44_disbanco = r_codret.idret;

          delete
            from disbancoprocesso
           where k142_idret = r_codret.idret;

          delete
            from disbanco
           where disbanco.idret = r_codret.idret;

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

  end if;
  /**
   * Fim da separa��o de recibos e carnes que possuiam suas origens importadas para divida
   */

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
        from disbancoprocesso
       where k142_idret = r_codret.idret;

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
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by disbanco.idret
  loop
    gravaidret := 0;

    -- pelo NUMPRE
    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - iniciando registro disbanco - idret '||r_codret.idret,lRaise,false,false);
    end if;

    -- Verifica se eh recibo da emissao geral do issqn e na recibopaga esta com valor zerado
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

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - xxx - numpre: '||r_idret.numpre||' - valorrecibo: '||valorrecibo||' - vlrpago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      if valorrecibo = 0 then
        valorrecibo := r_idret.vlrpago;
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - recibo... vlrpago: '||r_idret.vlrpago||' - valor recibo: '||valorrecibo,lRaise,false,false);
      end if;

     /**
      * Alterado para agrupar por receita quando for recibo avulso para n�o gerar registros duplicados
      * na arrepaga (k00_numpre k00_numpar k00_receit k00_hist)
      */
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
               min(datausu),
                recibo.k00_receit,
                recibo.k00_hist,
               sum(round((recibo.k00_valor / valorrecibo) * r_idret.vlrpago, 2)),
               min(datausu),
                recibo.k00_numpre,
                recibo.k00_numpar,
               min(recibo.k00_numtot),
               min(recibo.k00_numdig),
               min(conta),
               min(datausu)
           from recibo
                inner join arreinstit on arreinstit.k00_numpre = recibo.k00_numpre
                                     and arreinstit.k00_instit = iInstitSessao
         where recibo.k00_numpre = r_idret.numpre
      group by recibo.k00_numcgm,
               recibo.k00_numpre,
               recibo.k00_numpar,
               recibo.k00_receit,
               recibo.k00_hist;

      -- Verifica se o Total Pago � diferente do que foi Classificado (inserido na Arrepaga)
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
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - codret : '||r_codret.codret||'-idret : '||r_codret.idret,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - arrecad - numpre: '||r_idret.numpre||' - numpar: '||r_idret.numpar||' - tot: '||x_totreg||' - pago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

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

      if not r_idret.numpre is null then

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

$$ language 'plpgsql';--p--drop function fc_issqn(integer,date,integer,date,boolean,boolean);
--drop function fc_issqn(integer,date,integer,date,boolean,boolean,integer);

drop function if exists fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar);
drop function if exists fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer);
drop function if exists fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer,integer);

set check_function_bodies to on;

create or replace function fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar)
 returns varchar(200)
as $$
  begin
    return fc_issqn($1, $2, $3, $4, $5, $6, $7, $8, 0);
  end;
$$ language 'plpgsql';


create or replace function fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer)
 returns varchar(200)
as $$
  begin
    return fc_issqn($1, $2, $3, $4, $5, $6, $7, $8, $9, 1);
  end;
$$ language 'plpgsql';

create or replace function fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer,integer)
returns varchar(200)
as $$
declare

  iInscr                    alias   for $1; -- inscricao que esta sendo calculada
  dDatahj                   alias   for $2; -- data do sistema
  iAnousu                   alias   for $3; -- ano do sistema
  dDtbaixa                  alias   for $4; -- data da baixa se estiver calculando baixa
  bRecalc                   alias   for $5; -- se e um recalculo
  bGeral                    alias   for $6; -- se e um calculo geral
  iInstit                   alias   for $7; -- instituicao
  sAtivs                    alias   for $8; -- relacao das atividades separadas por virgula. Exemplo: 1,2,3,4,5
  iTipoCalculo              alias   for $9; -- Tipo de calculo: 0 - Todos,
                                            --                  1 - ISSQN,
                                            --                  2 - Alvara
  iNumeroParcelasAlvara     alias   for $10;-- Quantidade de Parcelas em que o Alvare deve ser dividido

  iCalcfixvar               integer default 0;
  iDiasvctoCissqn           integer default 0;
  iConsiderarMesInicio      integer default 0;
  iMesFinal                 integer default 0;
  iDiaInicio                integer default 0;

  v_vencproc                integer default 0;
  v_quantexcluido           integer default 0;
  v_diasjasomados           integer default 0;
  v_anoatualservidor        integer;
  v_tipo_quant              integer;
  iTotalVencimentosCad      integer;
  v_anoiniciocalc           integer;
  v_mesinicio               integer;
  v_numprejapago            integer;
  v_uqtab                   integer;
  v_uqcad                   float8;
  v_ativprinc               integer;
  v_codvencadcalc           integer;
  v_codven                  integer;
  v_sequencia               integer;
  v_tabativ                 integer;
  v_ativtipo                integer;
  v_tipcalc                 integer;
  iInscrexiste              integer;
  v_quantativ               integer;
  v_numpre                  integer;
  v_numpar                  integer;
  v_numtot                  integer;
  v_numdig                  integer;
  v_numcgm                  integer;
  v_receita                 integer;
  v_codbco                  integer;
  iAux                      integer;
  v_diasdesdeinicio         integer;
  v_diasdestevcto           integer;
  iTotalDiasAno             integer;
  v_q81_rec                 integer;
  iQtdeVencProcessar        integer;
  v_totvenc                 integer default 0;
  iQtdParcelas              integer;
  iQtdParcelasPagas         integer;
  v_forcal                  integer;
  iDiasParaVencimento       integer default 0;
  iAnoCadastroEmpresa       integer default 0;

  v_qprovisorio             float8 default 1;
  nValorParcela             float8;
  v_valor                   float8;
  v_valorgrav               float8 default 0;
  v_quant                   float8;
  v_base                    float8;
  v_valinflator             float8;
  v_q81_qini                float8;
  v_q81_qfim                float8;
  v_q81_val                 float8;
  nPercentualParcela        float8;

  nValorTotalPago           float8 default 0; -- total pago usado no caso de um recalculo
  nDescontoPagamentoParcela float8 default 0; -- desconto por parcela, e o valor a ser descontado por parcela no caso de um recalculo com pagamentos
  nPercPagoNovo             float8 default 0; -- percentual pago referente ao total do calculo, usado para calcular o desconto por parcela no caso de um recalculo com pagamento

  dDataCadastro             date;
  v_venc                    date;
  v_vencvar                 date;
  v_venccalc                date;
  iPrimeiroDiaAno           date;
  iUltimoDiaAno             date;
  v_dtvencano               date;
  dMaiorVencimentoCadvenc   date;
  dInicioAtividade          date;
  dInicioAtividadecalc      date;
  v_dtbase                  date;
  v_databaixa               date;
  dDtProporcionalidade      date;
  dVencimentoAtual          date;

  v_cep                     char(8);
  v_cepinstit               char(8);
  v_integr                  char(1);
  v_var                     char(1);
  v_codage                  char(5);
  v_numbco                  char(15);

  v_descrvariavel           varchar(50);
  v_descrtipcalc            varchar(40);
  v_descrcadcalc            varchar(40);
  sDescrProporcionalidade   varchar;

  v_text                    text;
  v_textexclui              text;
  v_textexclui2             text;
  v_manual                  text default '\n';
  tManual                   text;
  sSqlInsert                text;
  sManualCorrecao           text;
  sManualText               text;

  v_provisorio              boolean default false;
  v_cadcalc_var             boolean default false;
  v_cadcalc_fix             boolean default false;
  v_comcalculo              boolean default false;
  v_continuar               boolean default true;
  v_prim                    boolean;
  v_jagravou                boolean;
  lJaPassouUltVenc          boolean;
  bTemFixado                boolean default false; -- se tem valor fixado(varfixval)
  lProcessaParcVencidas     boolean default false;
  lProcessaParcela          boolean;
  lTabelasCriadas           boolean;
  lInscricaoMei             boolean default false;

  v_record_ativ             record;
  v_record_tipcalc          record;
  v_record_cadvenc          record;
  v_record_cadcalc          record;
  v_record_ativprinc        record;
  v_record_maiorvalor       record;
  v_record_somatodos        record;
  v_record_variavel         record;
  v_numprejacalculado       record;
  v_record_excluir          record;
  rDebitos                  record;
  rParcelasAlvara           record;

  dtOperacao                varchar;

  lRaise                    boolean default false; -- variavel para debug
  lAbatimento               boolean default false;

  begin

    select to_char(fc_getsession('DB_datausu')::date, 'DD/MM/YYYY') into dtOperacao;

      if  dtOperacao is null then
         RAISE EXCEPTION 'Erro: variavel de sessao DB_datausu nao declarado!';
      end if;


    if     iTipoCalculo = 1 then
       v_manual :=  '  ======================= Calculo de ISSQN          | Data de Calculo: ' || dtOperacao ||'  =========== \n';
    elsif  iTipoCalculo = 2 then
       v_manual :=  '  ======================= Calculo de Alvara         | Data de Calculo: ' || dtOperacao ||'  =========== \n';
    else
       v_manual :=  '  ======================= Calculo de ISSQN e Alvara | Data de Calculo: ' || dtOperacao ||'  =========== \n';
    end if;

    lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

    perform fc_debug('INICIANDO CALCULO PARA INSCRICAO : '||iInscr||' EXERCICIO : '||iAnousu,lRaise,true,false);

    perform fc_debug('DATA DE BAIXA - '||dDtbaixa,lRaise,false,false);

    perform * from ativtipo
      where q80_ativ in ( select distinct q07_ativ from tabativ where q07_inscr = iInscr );
    if not found then
      return '24-Empresa sem tipo de calculo configurado!';
    end if;

    --
    -- Realizamos a confer�ncia dos dados para sabermos se a inscri��o � optante pelo SIMPLES
    --
    -- Caso seja optante n�o ser� calculado a taxa de alvar� da empresa.
    --
    -- Deve possui cadastro na tabela meicgm...
    -- OU
    -- A empresa deve ser optante com:
    --  - A categoria 3 - MEI;
    --  - Data de in�cio do cadastro no simples menor ou igual a data de calculo;
    --  - N�o pode estar com o cadastro do simples baixado (isscadsimplesbaixa)
    --

    perform *
       from meicgm
            inner join issbase on issbase.q02_numcgm = meicgm.q115_numcgm
      where issbase.q02_inscr = iInscr;

    if found then
      lInscricaoMei = true;
    end if;

    perform *
       from isscadsimples
      where isscadsimples.q38_inscr     = iInscr
        and isscadsimples.q38_categoria = 3
        and isscadsimples.q38_dtinicial <= dDatahj
        and not exists ( select 1
                           from isscadsimplesbaixa
                          where isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial
                            and q39_dtbaixa <= dDatahj );
    if found then
      lInscricaoMei = true;
    end if;

    --
    -- FIM DA CONFER�NCIA DE CALCULO DO MEI
    --

    select extract(year from q02_dtinic)
      into iAnoCadastroEmpresa
      from issbase
     where q02_inscr = iInscr;

    if iAnousu < iAnoCadastroEmpresa then
      return '24-N�o pode ser feito calculo para exercicio menor que o ano de cadastramento da empresa.';
    end if;

    select fc_issqn_criatemptable(lRaise)
      into lTabelasCriadas;
    if lTabelasCriadas is false then
      return '24-Problema ao criar as tabelas temporarias. ';
    end if;


    for rDebitos in
      select q01_numpre
        from isscalc
       where q01_inscr  = iInscr
         and q01_anousu = iAnousu
    loop

      -- Verifica se existe Pagamento Parcial para o d�bito informado

      select fc_verifica_abatimento(1,rDebitos.q01_numpre)::boolean into lAbatimento;

      if lAbatimento then
        return '24-Opera��o Cancelada, D�bito com Pagamento Parcial!';
      end if;

    end loop;


    sSqlInsert := '
    insert into ativs (q07_inscr,q07_perman,q07_seq,q07_calcula,q07_ativ,q03_descr,q07_datain,q07_datafi,q07_databx,q07_quant,q11_tipcalc)
           select distinct
                  q07_inscr,
                  q07_perman,
                  q07_seq, \'*\'::char(1) as q07_calcula,
                  q07_ativ,
                  q03_descr,
                  q07_datain,
                  q07_datafi,
                  q07_databx,
                  q07_quant,
                  q11_tipcalc
             from tabativ
                  left outer join tabativtipcalc on q11_inscr   = q07_inscr
                                                and q11_seq     = q07_seq
                  inner join ativtipo            on q07_ativ    = q80_ativ
                  inner join tipcalc             on q80_tipcal  = q81_codigo
                  inner join ativid              on q07_ativ    = q03_ativ
                  inner join cadcalc             on q81_cadcalc = q85_codigo
            where q07_inscr ='||  iInscr ||'
              and q07_seq in ('||sAtivs||')
     union
           select distinct
                  q07_inscr,
                  q07_perman,
                  q07_seq, \'*\'::char(1) as q07_calcula,
                  q07_ativ,
                  q03_descr,
                  q07_datain,
                  q07_datafi,
                  q07_databx,
                  q07_quant,
                  q11_tipcalc
             from tabativ
                  left outer join tabativtipcalc on q11_inscr     = q07_inscr
                                                and q11_seq       = q07_seq
                  inner join clasativ            on q82_ativ      = q07_ativ
                  inner join issbaseporte        on q45_inscr     = q07_inscr
                  inner join issportetipo        on q41_codclasse = q82_classe
                                                and q41_codporte  = q45_codporte
                  inner join tipcalc             on q81_codigo    = q41_codtipcalc
                  inner join ativid              on q07_ativ      = q03_ativ
                  inner join cadcalc             on q81_cadcalc   = q85_codigo
            where q07_inscr =  '|| iInscr ||'
              and q07_seq in ('||sAtivs||')' ;

    execute sSqlInsert;

    select count(*) from ativs into v_sequencia;

    --raise notice 'quant: % - inscr: % - sAtivs: %', v_sequencia, iInscr, sAtivs;

    v_sequencia = 1;

    --
    -- Primeiro dia do ano para calculo
    --
    iPrimeiroDiaAno = to_char(iAnousu, '9999') || '-01-01';

    --
    -- Ultimo dia do ano para calculo
    --
    iUltimoDiaAno = to_char(iAnousu, '9999') || '-12-31';

    --
    -- Total de dias do ano
    --
    iTotalDiasAno  = iUltimoDiaAno::date - iPrimeiroDiaAno::date + 1;

    select q02_inscr
      from issbase
      into iInscrexiste
     where q02_inscr = iInscr;
    if iInscrexiste is null then
      return '11-INSCRICAO NAO CADASTRADA ';
    end if;

    select q02_dtinic,
           q02_dtinic,
           extract (month from q02_dtinic)
      from issbase
      into dInicioAtividade,
           dDataCadastro,
           v_mesinicio
     where q02_inscr = iInscr;

    if dInicioAtividade is null then

      select q02_dtinic,
             extract (month from q02_dtinic)
        from issbase
        into dInicioAtividade,
             v_mesinicio
       where q02_inscr = iInscr;

      if dInicioAtividade is null then
        select min(q07_datain),
               extract (month from min(q07_datain))
          into dInicioAtividade,
               v_mesinicio
          from tabativ
         where (q07_datafi is null or q07_datafi >= dDatahj)
           and (q07_databx is null or q07_databx >= dDatahj);

        if dInicioAtividade is null then
          return '12-INSCRICAO SEM DATA DE INICIO CONFIGURADA! ';
        else
          v_manual = v_manual || 'data de inicio da inscricao (baseada na menor data de inicio das atividades): ' || dInicioAtividade || '\n';
        end if;
      else

        v_manual = v_manual || 'data de inicio da inscricao (baseada na data de cadastramento): ' || dInicioAtividade || '\n';

      end if;

    else
      v_manual = v_manual || 'data de inicio da inscricao: ' || dInicioAtividade || '\n';
    end if;

    select extract (year from dDatahj) into v_anoatualservidor;

    perform fc_debug('inscr: '||iInscr||' - inicio: '||dInicioAtividade,lRaise,false,false);

--*********************************** se calcula por area ou por quantidade de funcionarios ********************************************************
--
-- grande bloco que verifica a variavel v_quant, que ser� utilizada para encontrar posteriormente o tipcalc a ser utilizado no calculo
--
    select q60_campoutilcalc
      into v_tipo_quant
      from parissqn;

--  se o campo q60_campoutilcalc for igual a 1 calcula pelo campo q30_area

    if v_tipo_quant is null then
      return '13-PARAMETRO DE QUANTIDADE PARA O CALCULO NAO CONFIGURADO NA TABELA PARISSQN';
    end if;

--**************************************************************************************************************************************************

    select q04_vbase,
           q04_calfixvar,
           q04_diasvcto
      from cissqn
      into v_base,
           iCalcfixvar,
           iDiasvctoCissqn
     where cissqn.q04_anousu = iAnousu;

    if v_base = 0 or v_base is null then
      return '14-SEM VALOR BASE CADASTRADO NOS PARAMETROS ';
    end if;

    v_manual = v_manual || 'valor base configurado nos parametros: ' || v_base || '\n';

    select q04_dtbase
      from cissqn
      into v_dtbase
    where cissqn.q04_anousu = iAnousu;
    if v_dtbase is null then
      return '15-SEM DATA BASE CADASTRADA NOS PARAMETROS ';
    end if;
    v_manual = v_manual || 'data base configurada nos parametros: ' || v_dtbase || '\n';

    select distinct i02_valor
      from cissqn
      into v_valinflator
           inner join infla on q04_inflat = i02_codigo
     where cissqn.q04_anousu = iAnousu
       and date_part('y',i02_data) = iAnousu;
    if v_valinflator is null then
      v_valinflator = 1;
--      return 'valor do inflator nao configurado corretamente';
    end if;
    v_manual = v_manual || 'valor do inflator configurada nos parametros: ' || v_valinflator || '\n';

    perform fc_debug('valor do inflator : '||v_valinflator,lRaise,false,false);

    select q02_dtbaix
      from issbase
      into v_databaixa
     where q02_inscr = iInscr;
    if v_databaixa is not null then
      return '16-INSCRICAO JA BAIXADA ';
    end if;

    -- Q81_TIPO:
    -- 1 issqn
    -- 4 alvara
    -- 5 taxa de expediente
    select count(*) into v_quantativ from (
    select distinct q07_seq
      from tabativ
           inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
           inner join tipcalc on q81_codigo = q80_tipcal
     where q81_tipo in (1,4,5)
       and q07_inscr = iInscr
       and (q07_datafi is null or q07_datafi >= dDatahj)
       and (q07_databx is null or q07_databx >= dDatahj)) as x;

    perform fc_debug('Total de atividades : '||v_quantativ,lRaise,false,false);

    select q07_inscr
      from tabativ
      into v_tabativ
           inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
     where q07_inscr = iInscr
       and q07_datain <= dDatahj;

    select q07_inscr
      from tabativ
      into v_ativtipo
           inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
     where q07_inscr = iInscr
       and (q07_datafi is null or q07_datafi >= dDatahj)
       and (q07_databx is null or q07_databx >= dDatahj);

    select q07_inscr
      from tabativ
      into v_tipcalc
           inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
           inner join tipcalc  on q81_codigo = q80_tipcal
     where q81_tipo in (1,4,5)
       and q07_inscr = iInscr
       and q07_datain <= dDatahj;

    perform fc_debug('Data da baixa : '||dDtbaixa,lRaise,false,false);

-- verifica quais os tipos de calculo para as atividades cadastradas para a inscricao

    select cep
      from db_config
      into v_cepinstit
     where codigo = iInstit;

    if v_cepinstit is null then
      return '17-PROBLEMAS COM A TABELA DB_CONFIG';
    end if;
    v_manual = v_manual || 'cep da instituicao: ' || v_cepinstit || '\n';

    select q02_cep
     from issbase
     into v_cep
    where q02_inscr = iInscr;

    v_manual = v_manual || 'cep da inscricao: ' || v_cep || '\n';

    v_manual = v_manual || '\n--- p r i m e i r a   e t a p a  -----\n';

    select distinct
           q07_inscr
      into iAux
      from ativs;

    perform fc_debug('Inscricao tabela temporaria (ativs) : '||iAux,lRaise,false,false);

    -- ativs � uma tabela tempor�ria criada antes de chamar a funcao

    for v_record_ativ in execute 'select * from ativs where q07_inscr = ' || iInscr loop


      v_manual = v_manual || '\nprocessando atividade: ' || v_record_ativ.q07_ativ || ' - ' || v_record_ativ.q03_descr || ' - sequencia: ' || v_record_ativ.q07_seq || ' - inicio: ' || v_record_ativ.q07_datain || '\n';
      --
      -- sempre entra aqui porque ninguem utiliza o esquema da tabela
      -- tabativtipcalc (tipo de calculo especifico para aquela atividade daquela inscricao)
      --
      if v_record_ativ.q11_tipcalc is null then
        v_text = 'select distinct
                         tipcalc.*,
                         cadcalc.q85_outromun,
                         cadcalc.q85_var,
                         case
                           when q81_tipo = 4 then
                             ( select q83_codven
                                 from ativtipo ativtipo2
                                      inner join tipcalc    on ativtipo2.q80_tipcal = q81_codigo
                                      left  join tipcalcexe on q83_tipcalc = q81_codigo
                                                           and q83_anousu  = (select extract(year from q02_dtinic)
                                                                                from issbase
                                                                               where q02_inscr = '||iInscr||')
                                where ativtipo2.q80_ativ = ativtipo.q80_ativ and ativtipo2.q80_tipcal = ativtipo.q80_tipcal )
                           else
                             q83_codven
                         end as q83_codven
                    from ativtipo
                         inner join tipcalc    on q80_tipcal = q81_codigo
                         left join tipcalcexe  on q83_tipcalc = q81_codigo
                                              and q83_anousu = ' || iAnousu || '
                         inner join cadcalc    on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                   where q81_tipo in (1,4,5)
                     and q80_ativ = ' || v_record_ativ.q07_ativ || '
                 union
                  select tipcalc.*,
                         cadcalc.q85_outromun,
                         cadcalc.q85_var,
                         case
                           when q81_tipo = 4 then
                             ( select q83_codven
                                 from tipcalc tipcalc2
                                      left  join tipcalcexe tipcalcexe2   on tipcalcexe2.q83_tipcalc = tipcalc2.q81_codigo
                                                             and tipcalcexe2.q83_anousu  = (select extract(year from issbase2.q02_dtinic)
                                                                                  from issbase issbase2
                                                                                 where issbase2.q02_inscr = '||iInscr||')
                                      inner join cadcalc cadcalc2  on cadcalc2.q85_codigo = tipcalc2.q81_cadcalc
                                      inner join clasativ clasativ2  on clasativ2.q82_classe = issportetipo.q41_codclasse and clasativ2.q82_ativ = ' || v_record_ativ.q07_ativ || '
                                where tipcalc2.q81_codigo = issportetipo.q41_codtipcalc )
                           else
                             q83_codven
                         end as q83_codven
                    from issportetipo
                         inner join issbaseporte on q45_inscr = ' || iInscr || '
                         inner join tipcalc      on q41_codtipcalc = q81_codigo
                         left  join tipcalcexe   on q83_tipcalc = q81_codigo
                                                and q83_anousu = ' || iAnousu || '
                         inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                         inner join clasativ     on q82_classe = q41_codclasse
                   where q45_codporte = q41_codporte
                     and q81_tipo in (1,4,5)
                     and q82_ativ = ' || v_record_ativ.q07_ativ;

        v_textexclui = 'select distinct
                               tipcalc.q81_cadcalc
                          from ativtipo
                               inner join tipcalc on q80_tipcal = q81_codigo
                               inner join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                         where q81_tipo in (1,4,5)
                           and q80_ativ = ' || v_record_ativ.q07_ativ || '
                       union
                        select tipcalc.q81_cadcalc
                          from issportetipo
                               inner join issbaseporte on q45_inscr = ' || iInscr || '
                               inner join tipcalc      on q41_codtipcalc = q81_codigo
                               inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                               inner join clasativ     on q82_classe = q41_codclasse
                         where q45_codporte = q41_codporte
                           and q81_tipo in (1,4,5)
                           and q82_ativ = ' || v_record_ativ.q07_ativ;
      else
        v_text = 'select tipcalc.*,
                         q85_outromun,
                         cadcalc.q85_var
                    from tipcalc
                         left outer join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                   where q81_tipo in (1,4,5)
                     and q81_codigo = ' || v_record_ativ.q11_tipcalc;
      end if;

-- deletando calculo atual da inscricao do ano
      v_manual = v_manual || 'deletando os calculos antigos da inscricao no ano - apenas os que nao serao recalculados neste calculo\n';

      v_textexclui2 = 'select q01_numpre,
                              q01_cadcal
                         from isscalc
                        where q01_cadcal not in ( ' || v_textexclui || ')
                          and q01_inscr  = ' || iInscr || '
                          and q01_anousu = ' || iAnousu;

      -- limpa o financeiro do que foi selecionado pelo usuario antes de calcular

      for v_record_excluir in execute v_textexclui2 loop

        perform fc_debug('Numpre : '||v_record_excluir.q01_numpre,lRaise,false,false);
        -- verifica se n�o esta no arrecant
        select k00_numpre
          from arrecant
          into v_numprejapago
         where k00_numpre = v_record_excluir.q01_numpre;
        if v_numprejapago is null then

          v_manual = v_manual || '     deletando numpre ' || v_record_excluir.q01_numpre || ' - calculo: ' || v_record_excluir.q01_cadcal || '\n';
          delete from arrecad where k00_numpre = v_record_excluir.q01_numpre;
          delete from isscalc where q01_numpre = v_record_excluir.q01_numpre;
          v_quantexcluido = v_quantexcluido + 1;

        else
          perform fc_debug('Numpre ja pago : '||v_record_excluir.q01_numpre,lRaise,false,false);
        end if;

      end loop;

      v_manual = v_manual || 'quantidade de calculos excluidos: ' || v_quantexcluido || ' \n';

      perform fc_debug('Atividade : '||v_record_ativ.q07_ativ,lRaise,false,false);

      perform fc_debug('',lRaise,false,false);
      perform fc_debug('Sql buscando os tipos de calculo : '||v_text,lRaise,false,false);
      perform fc_debug('',lRaise,false,false);

      -- para cada atividade retornada com seu tipo de calculo...


      for v_record_tipcalc in execute v_text loop


         select *
           into v_quant, tManual
           from fc_buscaquantidadeempresa( cast(iInscr                 as integer),
                                           cast(iAnousu                as integer),
                                           cast(v_tipo_quant           as integer),
                                           cast(v_record_ativ.q07_ativ as integer)
                                         );
         perform fc_debug('========== Excluindo registros da porcalculo e "do": '||tManual,lRaise,false,false);


         perform fc_debug('========== QUANTIDADES : '||tManual,lRaise,false,false);

         v_manual = v_manual||tManual;

         if v_quant = 0 and v_tipo_quant = 3 then
              return '30 - Erro buscando as quantidades para o calculo por pontua��o. Verifique o cadastro de pontua��o das Classes, Areas, Zonas e Empregados';
         end if;


        perform fc_debug('========== Tipo de calculo : '||v_record_tipcalc.q81_codigo||'-'||v_record_tipcalc.q81_descr||' - Quantidade : '||v_quant,lRaise,false,false);

        v_continuar = true;

        perform fc_debug('p r o c e s s a n d o  tipo de calculo: ' || v_record_tipcalc.q81_codigo || ' - ' || v_record_tipcalc.q81_descr || ' - gera: ' || v_record_tipcalc.q81_gera,lRaise,false,false);

        if  extract(year from dInicioAtividade )::integer = iAnousu then
          v_q81_rec  = v_record_tipcalc.q81_recexe;
          v_q81_qini = v_record_tipcalc.q81_qiexe;
          v_q81_qfim = v_record_tipcalc.q81_qfexe;
          v_q81_val  = v_record_tipcalc.q81_valexe;
        else
          v_q81_rec  = v_record_tipcalc.q81_recpro;
          v_q81_qini = v_record_tipcalc.q81_qipro;
          v_q81_qfim = v_record_tipcalc.q81_qfpro;
          v_q81_val  = v_record_tipcalc.q81_valpro;
        end if;

        perform fc_debug('Quantidade : '||coalesce(v_quant,0)||' Entre : '||coalesce(v_q81_qini,0)||' e '||coalesce(v_q81_qfim,0),lRaise,false,false);

        if coalesce(v_quant,0) >= coalesce(v_q81_qini,0) and coalesce(v_quant,0) <= coalesce(v_q81_qfim,999999) then

          perform fc_debug('Dentro do if da quantidade gera : '||v_record_tipcalc.q81_gera,lRaise,false,false);
          perform fc_debug('Ano de inicio de atividades : '||to_number(substr(dInicioAtividade,1,4),'99999'),lRaise,false,false);

          if v_record_tipcalc.q81_gera = 1 and to_number(substr(dInicioAtividade,1,4),'99999') < iAnousu then
            perform fc_debug('nao vai processar...',lRaise,false,false);
            v_manual = v_manual || '\n';
          else

            perform fc_debug('entrou no gera 2 do if - verificando pagamentos ',lRaise,false,false);

            v_manual = v_manual || 'verificando pagamentos\n';

            for v_numprejacalculado in
              select q01_numpre
                from isscalc
               where q01_anousu = iAnousu
                 and q01_inscr  = iInscr
                 and q01_cadcal = v_record_tipcalc.q81_cadcalc
                 and q01_recei  = v_q81_rec
            loop

              perform fc_debug('procurando numpre : '||v_numprejacalculado.q01_numpre,lRaise,false,false);
              v_manual = v_manual || 'processando numpre: ' || v_numprejacalculado.q01_numpre || '\n';

              -- trocado ordem de verifica��o de arrecad, arrecant para arrecant, arrecad
              select k00_numpre
                from arrecant
                into v_numprejapago
               where k00_numpre = v_numprejacalculado.q01_numpre;

              perform fc_debug('q01_numpre: '|| v_numprejacalculado.q01_numpre,lRaise,false,false);

              if v_numprejacalculado.q01_numpre is not null then

                perform fc_debug('v_numprejacalculado.q01_numpre is not null - v_numprejapago: '||v_numprejapago,lRaise,false,false);

                if v_numprejapago is null then

                  v_manual = v_manual || 'numpre em aberto\n';

                  if dDtbaixa is not null and v_record_tipcalc.q85_var is true then
                    v_manual = v_manual || 'se data da baixa preenchido e variavel, deleta do arrecad e issvar\n';

                    perform fc_debug('Deletando do arrecad e issvar',lRaise,false,false);

                    delete from arrecad where k00_numpre = v_numprejacalculado.q01_numpre and k00_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                    perform *
                       from issvarlev
                            inner join issvar on issvar.q05_codigo = issvarlev.q18_codigo
                      where q05_numpre = v_numprejacalculado.q01_numpre
                        and q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;
                    if found then
                      return '28 - EMPRESA JA POSSUI LEVANTAMENTO FISCAL PARA COMPETENCIA : '||(to_number(substr(dDtbaixa,6,2),'999') + 1)||'/'||iAnousu ;
                    end if;

                    delete from issvarnotas
                     using issvar
                     where issvar.q05_codigo  = issvarnotas.q06_codigo
                       and issvar.q05_numpre  = v_numprejacalculado.q01_numpre
                       and issvar.q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                    delete from issvar
                     where q05_numpre = v_numprejacalculado.q01_numpre
                       and q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                  elsif dDtbaixa is not null then

                    v_manual = v_manual || 'se data da baixa preenchido e nao variavel, insere no isscalcant e deleta do isscalc e arrecad\n';

                    perform fc_debug('Deletando do arrecad e isscalv',lRaise,false,false);

                    insert into isscalcant select * from isscalc where q01_numpre = v_numprejacalculado.q01_numpre;
                    delete from isscalc where q01_numpre = v_numprejacalculado.q01_numpre;
                    delete from arrecad where k00_numpre = v_numprejacalculado.q01_numpre;
                  end if;

                else

                  perform fc_debug('Numpre pago.',lRaise,false,false);

                  v_manual = v_manual || 'numpre pago\n';
                  v_comcalculo = true;

                end if;

                if v_numprejapago is null then

                  select k00_numpre
                  from arrecad
                  into v_numprejapago
                  where k00_numpre = v_numprejacalculado.q01_numpre;

                  if v_numprejapago is not null then

                    if bRecalc is false then
                      return '18-INSCRICAO JA CALCULADA E RECALCULO NAO PASSADO COMO PARAMETRO';
                    end if;

                    perform fc_debug('recalculo',lRaise,false,false);

                    v_manual = v_manual || 'recalculo\n';
                    v_comcalculo = true;
                  end if;

                end if;

              end if;

            end loop;

            perform fc_debug('saiu do procura pagamentos - continuar : '||( case when v_continuar is true then 'true' else 'false' end ),lRaise,false,false);

            if v_continuar then

                -- se true, busca quantidade do tabativ, senao default 1
              if v_record_tipcalc.q81_uqtab is false then

                perform fc_debug('uqtab false',lRaise,false,false);

                v_uqtab = 1;
                v_manual = v_manual || 'quantidade utilizada da tabela para calculo (utilizada default do sistema):' || v_uqtab || '\n';
              else

                perform fc_debug('uqtab true',lRaise,false,false);

                if v_record_ativ.q07_quant = 0 then
                  v_uqtab = 1;
                else
                  v_uqtab = v_record_ativ.q07_quant;
                end if;
                v_manual = v_manual || 'quantidade utilizada para calculo (baseada na quantidade da atividade lancada): ' || v_uqtab || '\n';
              end if;

              -- se true, busca quantidade do issquant, senao default 1
              if v_record_tipcalc.q81_uqcad is false then
                v_uqcad = 1;
              else
                select q30_mult
                  from issquant
                  into v_uqcad
                 where issquant.q30_inscr = iInscr
                   and issquant.q30_anousu = iAnousu;
                if v_uqcad is null then
                  return '19-MULTIPLICADOR NAO LANCADO PARA ESTA INSCRICAO';
                end if;

              end if;

              if v_record_tipcalc.q81_integr is true then
                v_integr = '1';
              else
                v_integr = '0';
              end if;
              v_manual = v_manual || 'integral: (1 = sim - 0 = nao): ' || v_integr || '\n';

              select case when q85_var is true then '1' else '0' end as q85_var
              from cadcalc
              into v_var
              where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;
              if v_var is null then
                return '20-NAO DEFINIDO NO CADASTRO DE CALCULO SE VARIAVEL OU NAO';
              end if;

              select q85_forcal
              from cadcalc
              into v_forcal
              where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;
              if v_forcal is null then
                return '21-nAO DEFINIDO NO CADASTRO DE CALCULO A FORMA DE CALCULO';
              end if;
              v_manual = v_manual || 'forma de calculo (1 = atividade principal - 2 = atividade com maior valor - 3 = soma do valor das atividades):' || v_forcal || '\n';

              select q85_perman
                from cadcalc
                into v_provisorio
               where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;

              if v_provisorio is true and v_record_ativ.q07_perman is false then

                v_qprovisorio = v_record_tipcalc.q81_percprovis;
                v_manual = v_manual || 'provisorio: vai acrescer ' || v_qprovisorio || ' por centro no valor calculado\n';

                perform fc_debug('Provisorio -- '||v_qprovisorio,lRaise,false,false);

              else

                v_qprovisorio = 1;

                perform fc_debug('Nao provisorio',lRaise,false,false);

              end if;

              perform fc_debug('inserindo na tabela tudo... sequencia:'||v_sequencia,lRaise,false,false);
              perform fc_debug('q81_val: '||v_q81_val||' - v_base: '|| v_base||' - uqtab: '||v_uqtab||' - uqcad: '||v_uqcad||' - qprovisorio: '||v_qprovisorio||' - valinflator: '||v_valinflator,lRaise,false,false);

              if v_record_tipcalc.q83_codven is null then
                return '25-VENCIMENTO NAO ENCONTRADO NO CADASTRO DE TIPO DE CALCULO';
              else
                v_codven = v_record_tipcalc.q83_codven;
              end if;
              perform fc_debug('q81_codigo: '||v_record_tipcalc.q81_codigo||' - q81_cadcalc: '||v_record_tipcalc.q81_cadcalc||' - vencimento: '||v_codven,lRaise,false,false);

              --
              -- Se empresa for optante pelo simples nao deve ser efetuado
              -- calculo de alvara
              --
              if lInscricaoMei and v_record_tipcalc.q81_cadcalc = 1 then
                perform fc_debug('Inscricao optante pelo MEI, nao calculando alvara.',lRaise,false,false);
                continue;
              end if;


              insert into
                tudo values (iAnousu,
                             iInscr,
                             v_record_ativ.q07_ativ,
                             v_record_tipcalc.q81_codigo,
                             v_record_tipcalc.q81_cadcalc,
                             v_base,
                             v_record_tipcalc.q81_recexe,
                             v_record_tipcalc.q81_recpro,
                             coalesce(v_quant,0),
                             v_record_tipcalc.q81_qiexe,
                             v_record_tipcalc.q81_qfexe,
                             v_uqtab,
                             v_uqcad,
                             v_forcal,
                             v_codven,
                             v_integr,
                             v_record_tipcalc.q81_tippro,
                             v_q81_val * v_base * v_uqtab * v_uqcad * v_qprovisorio * v_valinflator,
                             v_q81_val * v_qprovisorio * v_valinflator,
                             v_record_ativ.q07_datain,
                             coalesce( v_record_ativ.q07_datafi,(iAnousu||'-12-31')::date ),
                             v_var,
                             v_record_tipcalc.q81_gera,
                             v_sequencia);

                  v_sequencia = v_sequencia + 1;

            end if;

          end if;

        end if;

      end loop;
      perform fc_debug('Terminou atividade',lRaise,false,false);

    end loop;

    --
    -- SEGUNDA FASE DO CALCULO
    -- na fase anterior a rotina insere registros na tabela tudo e nela � que se baseia daqui para frente para saber o que calcular
    --

    perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('SEGUNDA FASE DO CALCULO',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('Na fase anterior a rotina insere registros na tabela tudo e nela � que se baseia daqui para frente para saber o que calcular',lRaise,false,false);


    if    iTipoCalculo = 1  then
      perform fc_debug('Excluindo registros de Alvar�', lRaise, false, false);
      DELETE FROM tudo where cadcalc not in (2,3);
    elsif iTipoCalculo = 2  then

      perform fc_debug('Excluindo registros de ISSQN', lRaise, false, false);
      DELETE FROM tudo where cadcalc not in (1,4,9);
    end if;


    select count(*) into iAux from tudo;

    v_manual = v_manual || '\nregistros processados na etapa de tipos de calculo: ' || iAux || '\n';
    v_manual = v_manual || '\n---- s e g u n d a  e t a p a  -----\n';
    v_manual = v_manual || 'agrupando por cadastro de calculo' || '\n';

    perform fc_debug('Quantidade de registros tabela tudo : '||iAux,lRaise,false,false);


    --
    -- for na tabela tudo com os tipos de calculo a utilizar
    -- veja o detalhe do group by, que faz com que apenas um cadcalc (ALVARA/ISSQN FIXO/ISSQN VARIAVEL) seja utilizado por calculo
    -- nessa fase o sistema cria registros na tabela porcalculo que ser� utilizada nessa fase
    --



    for v_record_cadcalc in select cadcalc, forcal, var from tudo group by cadcalc, forcal, var loop

      select q85_descr
        from cadcalc
      into v_descrcadcalc
      where q85_codigo = v_record_cadcalc.cadcalc;

      v_manual = v_manual || '   processando calculo ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc || '\n';

      perform fc_debug('Var : '||v_record_cadcalc.var||' cadcalc : '||v_record_cadcalc.cadcalc,lRaise,false,false);

      -- se for variavel
      if v_record_cadcalc.var = '1' then

        v_manual = v_manual || '      variavel\n';

        -- pode ser fixado por inscricao
        for v_record_variavel in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc limit 1 loop

          perform fc_debug('Inserindo na tabela tudo fixado por inscricao',lRaise,false,false);


          execute 'insert into porcalculo values ('
          || iAnousu || ','
          || iInscr  || ','
          || v_base || ','
          || v_record_variavel.tipcalc || ','
          || v_record_variavel.cadcalc || ','
          || v_record_variavel.forcal  || ','
          || v_record_variavel.codven  || ','
          || v_record_variavel.integr  || ','
          || '''' || v_record_variavel.tipopro || '''' || ','
          || '''' || v_record_variavel.inicio  || '''' || ','
          || '''' || coalesce( v_record_variavel.final,(iAnousu||'-12-31')::date )   || '''' || ','
          || 0 || ','
          || v_record_variavel.valori || ','
          || 0 || ','
          || '''' || v_record_variavel.var     || '''' || ','
          || v_record_variavel.gera || ','
          || v_record_variavel.seq
          || ');';

        end loop;

      -- se NAO for variavel
      else

        perform fc_debug('Inserindo na tabela tudo pela atividade principal',lRaise,false,false);

        v_manual = v_manual || '      nao variavel\n';

        -- se for pela atividade principal
        if v_record_cadcalc.forcal = 1 then
          v_manual = v_manual || '         calculando pela atividade principal\n';

          select q07_ativ
            from ativprinc
            into v_ativprinc
                 inner join tabativ on q07_inscr = q88_inscr and q07_seq = q88_seq
           where q88_inscr = iInscr;
          if v_ativprinc is null then
            return '22-SEM ATIVIDADE PRINCIPAL CADASTRADA PARA ESTA INSCRICAO ';
          end if;

--        for v_record_ativprinc in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc and tudo.ativ = v_ativprinc loop
--        DESCOBRIR QUEM E PORQUE COMENTARAM A LINHA ACIMA
--        PORQUE PELA LOGICA DEVERIA UTILIZAR A LINHA COMENTADA

          for v_record_ativprinc in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc limit 1 loop

            v_manual = v_manual || '            inserindo na tabela de tipos de calculo a processar - tipcalc: ' || v_record_ativprinc.tipcalc || ' - cadcalc: ' || v_record_ativprinc.cadcalc || '\n';
            execute 'insert into porcalculo values ('
            || iAnousu || ','
            || iInscr  || ','
            || v_base || ','
            || v_record_ativprinc.tipcalc || ','
            || v_record_ativprinc.cadcalc || ','
            || v_record_ativprinc.forcal  || ','
            || v_record_ativprinc.codven  || ','
            || v_record_ativprinc.integr  || ','
            || '''' || v_record_ativprinc.tipopro || '''' || ','
            || '''' || v_record_ativprinc.inicio  || '''' || ','
            || '''' || coalesce( v_record_ativprinc.final, (iAnousu||'-12-31')::date )   || '''' || ','
            || v_record_ativprinc.valor   || ','
            || v_record_ativprinc.valori  || ','
            || 0 || ','
            || '''' || v_record_ativprinc.var     || '''' || ','
            || v_record_ativprinc.gera    || ','
            || v_record_ativprinc.seq
            || ');';

          end loop;

        end if;

        -- pela atividade que gerou o maior valor
        if v_record_cadcalc.forcal = 2 then
          v_manual = v_manual || 'calculando pela atividade que gerou o maior valor\n';

          for v_record_maiorvalor in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc order by valor desc limit 1 loop

            perform fc_debug('Inserindo na tabela porcalculo pela atividade de maior valor valor : '||v_record_maiorvalor.valor||' - tipo de calculo: '||v_record_maiorvalor.tipcalc||' - vencimento: '||v_record_maiorvalor.codven,lRaise,false,false);

            execute 'insert into porcalculo values ('
            || iAnousu || ','
            || iInscr  || ','
            || v_base || ','
            || v_record_maiorvalor.tipcalc || ','
            || v_record_maiorvalor.cadcalc || ','
            || v_record_maiorvalor.forcal  || ','
            || v_record_maiorvalor.codven  || ','
            || v_record_maiorvalor.integr  || ','
            || '''' || v_record_maiorvalor.tipopro || '''' || ','
            || '''' || v_record_maiorvalor.inicio  || '''' || ','
            || '''' || coalesce( v_record_maiorvalor.final, (iAnousu||'-12-31')::date )   || '''' || ','
            || v_record_maiorvalor.valor   || ','
            || v_record_maiorvalor.valori  || ','
            || 0 || ','
            || '''' || v_record_maiorvalor.var     || '''' || ','
            || v_record_maiorvalor.gera    || ','
            || v_record_maiorvalor.seq
            || ');';

          end loop;

        end if;

        -- pela soma de todos os valores calculados
        -- ainda nao implementado totalmente
        -- ou seja, nao funciona ainda...
        -- TESTADORES DEVEM UTILIZAR ESSA FORMULA DE CALCULO NOS TESTES
        if v_record_cadcalc.forcal = 3 then
          v_manual = v_manual || 'calculando pela soma de todos os valores\n';

          for v_record_somatodos in select * ,
                                           (select sum(valor) as somatotal
                                              from tudo
                                             where tudo.cadcalc = v_record_cadcalc.cadcalc) as somatotal
                                      from tudo
                                     where tudo.cadcalc = v_record_cadcalc.cadcalc loop

            select q85_codven
            from cadcalc
            into v_codvencadcalc
            where q85_codigo = v_record_cadcalc.cadcalc;
            if v_codvencadcalc is null then
              return '23-SEM VENCIMENTO PADRAO NO CADASTRO DE VENCIMENTOS ';
            end if;

            execute 'insert into porcalculo values ('
            || iAnousu || ','
            || iInscr  || ','
            || v_base || ','
            || v_record_somatodos.tipcalc || ','
            || v_record_cadcalc.cadcalc || ','
            || v_record_cadcalc.forcal  || ','
            || v_codvencadcalc            || ','
            || v_record_somatodos.integr  || ','
            || '''' || v_record_somatodos.tipopro || '''' || ','
            || '''' || v_record_somatodos.inicio  || '''' || ','
            || '''' || coalesce( v_record_somatodos.final, (iAnousu||'-12-31')::date)   || '''' || ','
            || v_record_somatodos.somatotal || ','
            || v_record_somatodos.valori  || ','
            || 0 || ','
            || '''' || v_record_somatodos.var     || '''' || ','
            || v_record_somatodos.gera    || ','
            || v_record_somatodos.seq
            || ');';

          end loop;

        end if;

      end if;

    end loop;
    -- fim do for do select na tabela tudo, que gera os registros na porcalculo

    v_manual = v_manual || '\n---- t e r c e i r a  e t a p a  -----\n';
    v_manual = v_manual || 'agrupando por tipo de vencimento e preparando para calcular\n';

    perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TERCEIRA ETAPA DO CALCULO ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('agrupando por tipo de vencimento e preparando para calcular',lRaise,false,false);


-- carazinho usa 30 porcento para cada atividade excedente...
    for v_record_cadcalc in select porcalculo.*,
                                   tipcalc.q81_excedenteativ
                              from porcalculo
                                   inner join tipcalc on q81_codigo = porcalculo.tipcalc
    loop

      if v_record_cadcalc.q81_excedenteativ > 0 then
        v_valor = v_record_cadcalc.valor;

        perform fc_debug('Seq : '||v_record_cadcalc.seq||' Valor : '||v_valor,lRaise,false,false);

        v_valor = v_valor + (v_valor * v_record_cadcalc.q81_excedenteativ * (v_quantativ - 1));
        update porcalculo set valor = v_valor where seq = v_record_cadcalc.seq ;

        perform fc_debug('Apos calcular 30% por atividade excedente - Seq : '||v_record_cadcalc.seq||' Valor : '||v_valor,lRaise,false,false);

      end if;
    end loop;

    perform fc_debug('Descobrindo de calcula fixo ou variavel ',lRaise,false,false);

    --
    -- descobrir se calcula fixo ou variavel
    --
    for v_record_cadvenc in select distinct cadcalc from porcalculo
    loop

      if v_record_cadvenc.cadcalc = 2 then

        v_cadcalc_fix = true;

      elsif v_record_cadvenc.cadcalc = 3 then

        v_cadcalc_var = true;

      end if;

    end loop;

    if v_cadcalc_fix = true and v_cadcalc_var = true and iCalcfixvar = 1 then

      perform fc_debug('Inscricao com dois (2) calculos fixo/var, excluido calculo fixo',lRaise,false,false);


      delete from porcalculo where cadcalc = 2;
      v_manual = v_manual || '\n inscricao com dois (2) calculos fixo/variavel, calculado somente variavel \n';

    end if;

    perform fc_debug('select trazendo os vencimentos para gerar as proporcionalidades',lRaise,false,false);

    for v_record_cadvenc in select codven from porcalculo group by codven
    loop

      v_manual = v_manual || 'processando vencimento ' || v_record_cadvenc.codven || '\n';

      perform fc_debug('Codigo do cadastro de vencimentos : '||v_record_cadvenc.codven,lRaise,false,false);

      for v_record_cadcalc in select distinct tipcalc, cadcalc, valor, integr, inicio, final, tipopro, seq from porcalculo where codven = v_record_cadvenc.codven loop

        perform fc_debug('Tipo de calulo : '||v_record_cadcalc.tipcalc||' Cadcalc : '||v_record_cadcalc.cadcalc||'seq :'||v_record_cadcalc.seq,lRaise,false,false);

        select q81_abrev
        from tipcalc
        into v_descrtipcalc
        where q81_codigo = v_record_cadcalc.tipcalc;

        select q85_descr
        from cadcalc
        into v_descrcadcalc
        where q85_codigo = v_record_cadcalc.cadcalc;

        v_manual = v_manual || 'processando tipcalc: ' || v_record_cadcalc.tipcalc || ' - ' || v_descrtipcalc || ' - cadcalc: ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc || '\n';

        v_valor = v_record_cadcalc.valor;
        v_manual = v_manual || 'valor: ' || v_valor || '\n';

        -- se � para calcular com proporcionalidade
        if v_record_cadcalc.integr = '0' then

          perform fc_debug('Valor sem proporcionalidade : '||v_valor,lRaise,false,false);

          v_manual = v_manual || '   nao integral = proporcional ' || ' inicio: ' || v_record_cadcalc.inicio || ' - ano atual ' || iAnousu || '\n';

          -- soh calcula integralidade se ano do inicio da atividade for igual ao atual ou for calculo de baixa
          if extract(year from v_record_cadcalc.inicio)::integer = iAnousu or dDtbaixa is not null then
            --
            -- Calculo da proporcionalidade
            --
            if dDtbaixa is null then
              dDtProporcionalidade := v_record_cadcalc.final;
            else
              dDtProporcionalidade := dDtbaixa;
            end if;

            perform fc_debug('Tipo   - '||v_record_cadcalc.tipopro,lRaise,false,false);
            perform fc_debug('Inicio - '||v_record_cadcalc.inicio,lRaise,false,false);
            perform fc_debug('Final  - '||dDtProporcionalidade,lRaise,false,false);

            --
            -- Funcao fc_issqn_proporcionalidade
            --   retorna o valor proporcional ao periodo de atividade do exercio e a descricao do tipo de proporcionalidade
            --


            select rnValorProporcional,rsTipoProporcionalidade
              into v_valor,sDescrProporcionalidade
              from fc_issqn_proporcionalidade(v_record_cadcalc.valor::numeric,v_record_cadcalc.tipopro::varchar,v_record_cadcalc.inicio::date,dDtProporcionalidade::date,iAnousu::integer,dDtbaixa::date);

            v_manual = v_manual ||sDescrProporcionalidade||' \n';

          end if;

          perform fc_debug('Valor com a proporcionalide : '||v_valor,lRaise,false,false);

          v_manual = v_manual || 'valor ja calculado a proporcionalidade: ' || v_valor || '\n';

        else

          v_manual = v_manual || 'integral\n';

        end if;

        update porcalculo set valorintegr = v_valor  where seq = v_record_cadcalc.seq ;

      end loop;

    end loop;

    select count(*)
      into iAux
      from porcalculo;

    v_manual = v_manual || '\n---- q u a r t a  e t a p a  -----\n';
    v_manual = v_manual || 'total de calculos que o sistema vai processar: ' || iAux || '\n';

    if iAux = 0 then
      return '24-NENHUM CALCULO EFETUADO!';
    end if;

    --
    -- QUARTA FASE - GERANDO FINANCEIRO
    --
    perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('QUARTA FASE DO CALCULO (GERANDO FINANCEIRO) ',lRaise,false,false);
    perform fc_debug('Quantidade de registros tabela porcalculo : '||iAux,lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);

    /* for nos calculo da inscricao que esta sendo calculada */

    perform fc_debug('Percorrendo os calculo da inscricao que esta sendo calculada',lRaise,false,false);

    dInicioAtividade := dDataCadastro;

    for v_record_cadcalc in select * from porcalculo loop

      select q81_abrev
        from tipcalc
        into v_descrtipcalc
      where q81_codigo = v_record_cadcalc.tipcalc;

      select q85_descr
      from cadcalc
      into v_descrcadcalc
      where q85_codigo = v_record_cadcalc.cadcalc;

      v_manual = v_manual || '\nprocessando calculo ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc ||  ' - tipo de calculo: ' || v_record_cadcalc.tipcalc || ' - ' || v_descrtipcalc || '\n';

      -- data de baixa preenchida e � variavel
      -- resumindo, � para nao fazer nada se for vari�vel e calculo para baixa

      if dDtbaixa is not null and v_record_cadcalc.var = '1' then

        perform fc_debug('Com data de baixa passada como parametro: ' || dDtbaixa || ' e variavel: nao calcula',lRaise,false,false);
        v_manual = v_manual || 'com data de baixa passada como parametro: ' || dDtbaixa || ' e variavel: nao calcula\n';
      -- senao
      else

        select q01_numpre
          from isscalc
          into v_numpre
         where q01_anousu = iAnousu
           and q01_inscr = iInscr
           and q01_cadcal = v_record_cadcalc.cadcalc;

        if v_numpre is null then

          perform fc_debug('Sem calculo para o exercicio ',lRaise,false,false);
          v_comcalculo = false;

        else

          perform fc_debug('Com calculo para o exercicio numpre : '||v_numpre,lRaise,false,false);
          v_comcalculo = true;

        end if;

        if v_comcalculo is false then
          v_manual = v_manual || 'calculo novo\n';

          select nextval('numpref_k03_numpre_seq')
            into v_numpre;

          perform fc_debug('Calculo Novo ',lRaise,false,false);
          perform fc_debug('Novo numpre : '||v_numpre,lRaise,false,false);

          if v_numpre is null then
            return '25-ERRO DO PROCESSAR SEQUENCIA DO NUMPRE';
          end if;

        else

          v_manual = v_manual || 'calculo ja existe... utilizar o mesmo numpre\n';
          select q01_numpre
            from isscalc
            into v_numpre
            where q01_anousu = iAnousu
              and q01_inscr = iInscr
              and q01_cadcal = v_record_cadcalc.cadcalc;

        end if;

        v_numpar = 1;

        select max(q82_parc)
          into v_numtot
          from cadvenc
               inner join cadvencdesc on q82_codigo = q92_codigo
         where cadvenc.q82_codigo = v_record_cadcalc.codven;

        if v_record_cadcalc.var = '1' then
          v_numtot = 12;
        else

          if v_numtot is null then
            v_numtot = 0;
          end if;

          if bGeral is false then
            v_numtot = 1;
          end if;
        end if;

        v_manual = v_manual || 'total de parcelas baseado no vencimento: ' || v_numtot || '\n';

        perform fc_debug('Codigo do Vencimento encontrado : '||v_record_cadcalc.codven,lRaise,false,false);
        perform fc_debug('Numpre : '||v_numpre||' Parcela : '||v_numpar||' Numtot : '||v_numtot||' Cadcalc : '||v_record_cadcalc.cadcalc,lRaise,false,false);

        select fc_digito(v_numpre,v_numpar,v_numtot)
          into v_numdig;

        if v_numdig is null then
          return '26-ERRO DO PROCESSAR FUNCAO DE CALCULO DO DIGITO VERIFICADOR';
        end if;

        select k15_codbco,
               k15_codage
          into v_codbco,
               v_codage
          from cadvencdescban
               inner join cadban on cadvencdescban.q93_cadban = cadban.k15_codigo
        where q93_codigo = v_record_cadcalc.codven;

        if v_codbco is null then
          v_codbco = 0;
        end if;

        if v_codage is null then
          v_codage = 0;
        end if;

        select q02_numcgm
          into v_numcgm
          from issbase
         where q02_inscr = iInscr;
        if v_numcgm is null then
          return '27-CGM DA INSCRICAO : '||iInscr||' NAO ENCONTRADO DO CADASTRO DO CGM';
        end if;

        perform fc_debug('Inicio : '||v_record_cadcalc.inicio||' Anousu : '||iAnousu,lRaise,false,false);

        if iAnousu = to_number(substr(v_record_cadcalc.inicio,1,4),'99999') then

          v_manual = v_manual || 'Ano atual igual ao ano de inicio da atividade: ' || iAnousu || '\n';

          perform fc_debug('Ano atual igual ao ano de inicio',lRaise,false,false);

          select recexe
            into v_receita
            from tudo
           where tudo.seq = v_record_cadcalc.seq;
        else

          v_manual = v_manual||'Ano atual diferente do ano de inicio da atividade: ' || iAnousu || '\n';
          perform fc_debug('Ano atual diferente ao ano de inicio',lRaise,false,false);
          select recpro
            into v_receita
            from tudo
           where tudo.seq = v_record_cadcalc.seq;

        end if;

        v_prim = false;

        /**
         * Se for variavel
         */
        if v_record_cadcalc.var = '1' then
          v_manual = v_manual || 'variavel\n';

          perform fc_debug('Calculando variavel -- vencimento : '||v_record_cadcalc.codven,lRaise,false,false);

         -- se for baixa
          perform fc_debug('',lRaise,false,false);
          perform fc_debug('COMECANDO A PROCESSAR OS VENCIMENTOS (PELO CADASTRO DE VENCIMENTOS CADVENC) ',lRaise,false,false);
          perform fc_debug('',lRaise,false,false);

          /* este for calcula pelo cadvenc todas as parcelas pelo select do porcalculo(todos os calculos da inscricao) */
          for v_record_cadvenc in select * from cadvenc
                                           inner join cadvencdesc on q82_codigo = q92_codigo
                                     where cadvenc.q82_codigo = v_record_cadcalc.codven
                                     order by q82_parc
          loop

            perform fc_debug('Processando vencimentos ',lRaise,false,false);
            perform fc_debug('PARCELA : '||v_record_cadvenc.q82_parc||' VENCIMENTO : '||v_record_cadvenc.q82_venc,lRaise,false,false);
            perform fc_debug('1 -- Deletando do arrecad numpre : '||v_numpre||' numpar : '||v_record_cadvenc.q82_parc,lRaise,false,false);

            delete from arrecad
             where k00_numpre = v_numpre
               and k00_numpar = v_record_cadvenc.q82_parc;

            if to_number(substr(v_record_cadvenc.q82_venc,1,4)||substr(v_record_cadvenc.q82_venc,6,2),'999999') >
               to_number(substr(v_record_cadcalc.inicio,1,4)||substr(v_record_cadcalc.inicio,6,2),'999999') then

              v_manual = v_manual || 'vencimento do cadastro de vencimentos: ' || v_record_cadvenc.q82_venc || ' maior ou igual a data de inicio da atividade mais 1 mes\n';

              select count(*)
                into iAux
                from arreinscr
               where k00_numpre = v_numpre
                 and k00_inscr = iInscr;

              if iAux = 0 then

                v_manual = v_manual || 'inserindo numpre no arreinscr: ' || v_numpre || '\n';
                insert into arreinscr values (v_numpre,iInscr);
              end if;

              /* se nao for primeiro calculo do exercicio */
              if v_prim is false then

                if v_comcalculo is true then

                  perform fc_debug('Deletando do isscalc numpre : '||v_numpre,lRaise,false,false);
                  v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || v_numpre || '\n';
                  delete from isscalc where q01_numpre = v_numpre;

                end if;

                insert into isscalc values (iAnousu,iInscr,v_record_cadcalc.cadcalc,v_receita,v_numpre,v_record_cadcalc.valori / v_valinflator);
                insert into numpres values (v_numpre);
                v_prim = true;

              end if;

              v_valor         := v_record_cadcalc.valori / v_valinflator;
              v_valorgrav     := 0;
              v_descrvariavel := 'arrecadacao de issqn variavel nao fixado';
              v_manual        := v_manual || 'valor: ' || v_valor || '\n';

              /* verifica se tem valor fixado lancado */
              select q34_valor
                into v_valorgrav
                from varfix
                     inner join varfixval on varfix.q33_codigo = varfixval.q34_codigo
               where varfix.q33_inscr    = iInscr
                 and varfixval.q34_numpar = v_record_cadvenc.q82_parc;

              if v_valorgrav is not null then

                v_descrvariavel = 'arrecadacao de issqn variavel fixado';
                bTemFixado = true;
              else

                v_valorgrav     = 0;
                v_descrvariavel = 'arrecadacao de issqn variavel nao fixado';
              end if;

              perform fc_debug('Tipo de valor : '||v_descrvariavel ,lRaise,false,false);

              v_numpar = v_record_cadvenc.q82_parc;
              v_manual = v_manual || 'parcela: ' || v_numpar || '\n';

              select q05_codigo
                into iAux
                from issvar
                     inner join arreinscr on q05_numpre = arreinscr.k00_numpre
               where q05_ano   = iAnousu
                 and q05_mes   = v_numpar
                 and k00_inscr = iInscr
                 and q05_valor > 0;

              v_vencvar = v_record_cadvenc.q82_venc;

              perform fc_debug('Vencimento variavel : '||v_vencvar,lRaise,false,false);

              select q05_numpre
                from issvar
                into v_numprejapago
                     inner join arrecant on q05_numpre = arrecant.k00_numpre and q05_numpar = arrecant.k00_numpar
                     inner join arreinscr on arrecant.k00_numpre = arreinscr.k00_numpre
              where q05_ano   = iAnousu
                and q05_mes   = v_numpar
                and k00_inscr = iInscr;

              perform fc_debug('Numpre ja pago '||v_numprejapago||' Numpar : '||v_numpar||' Anousu : '||iAnousu,lRaise,false,false);

              if v_numprejapago is null then

                perform fc_debug('2 -- deletando do arrecad numpre : '||v_numpre||' numpar : '||v_numpar,lRaise,false,false);

                delete from arrecad where k00_numpre = v_numpre and k00_numpar = v_numpar ;

                perform * from fc_statusdebitos(v_numpre,v_numpar) where rtstatus = 'PAGO' or rtstatus = 'CANCELADO'  limit 1;
                if found then
                  continue;
                end if;

                perform * from issvardiv
                          inner join issvar    on q05_codigo = q19_issvar
                          inner join arreinscr on k00_numpre = q05_numpre
                    where issvar.q05_ano       = iAnousu
                      and issvar.q05_mes       = v_numpar
                      and arreinscr.k00_inscr  = iInscr ;

                if found then
                  return '28 - INSCRICAO COM CALCULO IMPORTADO PARA DIVIDA';
                end if;

                perform fc_debug('deletando do issvar Ano : '||iAnousu||' Mes : '||v_numpar||' Inscricao : '||iInscr,lRaise,false,false);
                perform *
                   from arreinscr
                        inner join issvar    on issvar.q05_numpre    = arreinscr.k00_numpre
                        inner join issvarlev on issvarlev.q18_codigo = issvar.q05_codigo
                  where issvar.q05_ano       = iAnousu
                    and issvar.q05_mes       = v_numpar
                    and arreinscr.k00_inscr  = iInscr ;
                if found then
                  return '28 - EMPRESA JA POSSUI LEVANTAMENTO FISCAL PARA COMPETENCIA : '||v_numpar||'/'||iAnousu ;
                end if;

               delete from issvarnotas
                using issvar
                where issvar.q05_codigo = issvarnotas.q06_codigo
                  and issvar.q05_ano    = iAnousu
                  and issvar.q05_mes    = v_numpar ;

                delete from issvar
                 using arreinscr
                 where issvar.q05_ano       = iAnousu
                   and issvar.q05_mes       = v_numpar
                   and arreinscr.k00_inscr  = iInscr
                   and arreinscr.k00_numpre = issvar.q05_numpre ;

                delete from informacaodebito
                 where informacaodebito.k163_numpre = v_numpre
                   and informacaodebito.k163_numpar = v_numpar;

                insert into issvar (q05_codigo, q05_numpre, q05_numpar, q05_valor, q05_ano, q05_mes, q05_histor, q05_aliq, q05_bruto)
                            values (nextval('issvar_q05_codigo_seq'), v_numpre, v_numpar, v_valorgrav, iAnousu, v_numpar, v_descrvariavel, v_valor, 0);

                perform fc_debug('INSERT NUMERO 1 NO ARRECAD (issvar)',lRaise,false,false);
                perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(v_valorgrav,2)||' Vencimento : '||v_vencvar||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                     values (v_numcgm,v_vencvar,v_receita,v_record_cadvenc.q82_hist,round(v_valorgrav,2),v_vencvar,v_numpre,v_numpar,v_numtot,v_numdig,v_record_cadvenc.q92_tipo);

                if v_codbco != 0 and v_comcalculo is false then

                  select fc_numbco(v_codbco,v_codage) into v_numbco;
                  insert into arrebanco values (v_numpre,v_numpar,v_codbco,v_codage,v_numbco,'');

                end if;

              else
                perform fc_debug('Ja pago',lRaise,false,false);
              end if;

              v_numpar = v_numpar + 1;

            end if;

          end loop;

          perform fc_debug('FIM DO PROCESSAMENTO DO VARIAVEL',lRaise,false,false);

        else  -- SE NAO FOR VARIAVEL

          perform fc_debug('',lRaise,false,false);
          perform fc_debug('--------------------- P R O C E S S A N D O   (  N A O  )  V A R I A V E L  ---------------------',lRaise,false,false);
          perform fc_debug('vencimento: '||v_record_cadcalc.codven,lRaise,false,false);
          perform fc_debug('',lRaise,false,false);

          v_manual = v_manual || 'processando com base no cadastro de vencimentos\n';

          lJaPassouUltVenc   = false;
          v_jagravou         = false;
          iQtdeVencProcessar = 0;

          -- busca quantas parcelas vai calcular e registrar no arrecad (variavel iQtdeVencProcessar)
          select count(*)
            into v_totvenc
            from cadvencdesc
                 inner join cadvenc on q82_codigo = q92_codigo
           where cadvencdesc.q92_codigo = v_record_cadcalc.codven ;

          select count(*)
            into iQtdeVencProcessar
            from cadvencdesc
                 inner join cadvenc on q82_codigo = q92_codigo
           where cadvencdesc.q92_codigo = v_record_cadcalc.codven
             and ( case
                     when cadvencdesc.q92_formacalcparcvenc = 1
                       then case when q82_venc >= dInicioAtividade then true else false end
                     when cadvencdesc.q92_formacalcparcvenc = 3
                       then q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                     else
                       case
                         when cadvenc.q82_calculaparcvenc is true
                           then true
                         else
                           q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                       end
                   end );
             -- esse case tem a finalidade de processar ou nao parcelas
             -- vencidas de acordo com os parametros do cadastro de
             -- vencimentos(cadvencdescm,cadvenc)
          perform fc_debug('Pesquisando quantidade de vencimentos no periodo de atividade',lRaise,false,false);
          perform fc_debug('Data inicial : '||coalesce(dInicioAtividade,'2999-01-01'::date)    ,lRaise,false,false);
          perform fc_debug('Data final   : '||iUltimoDiaAno ,lRaise,false,false);
          perform fc_debug('Quantidade de vencimentos encontrada : '||iQtdeVencProcessar||''    ,lRaise,false,false);

          -- utiliza a variavel iTotalVencimentosCad, que e o total de vencimentos para mais abaixo saber em que parcela jogar os centavos de diferenca de arredondamento
          -- utiliza a variavel dMaiorVencimentoCadvenc para no caso da empresa iniciar depois do ultimo vencimento, calcular tudo em uma parcela e jogar no vencimento 31-12
          select max(cadvenc.q82_venc), coalesce(max(cadvencdesc.q92_diasvcto),0), count(*)
            into dMaiorVencimentoCadvenc, iDiasParaVencimento, iTotalVencimentosCad
            from cadvencdesc
                 inner join cadvenc on q82_codigo = q92_codigo
           where cadvencdesc.q92_codigo = v_record_cadcalc.codven;

          -- passando conteudo da variavel do cissqn para cadvencdesc
          iDiasvctoCissqn := iDiasParaVencimento;

          perform fc_debug('Quantidade de vencimentos a processar : '||iQtdeVencProcessar,  lRaise,false,false);
          perform fc_debug('Dias para o vencimento(q92_diasvcto)  : '||iDiasParaVencimento, lRaise,false,false);
          perform fc_debug('Maior vencimento do cadastro          : '||dMaiorVencimentoCadvenc||' Data atual : '||dDatahj,lRaise,false,false);

          select count(*), sum(k00_valor)
            into iQtdParcelasPagas, nValorTotalPago
            from ( select k00_numpre, k00_numpar, sum(k00_valor) as k00_valor
                     from arrecant
                    where k00_numpre = v_numpre
                 group by k00_numpre, k00_numpar ) as x;

          perform fc_debug('Quantidade de parcelas pagas : '||coalesce(iQtdParcelasPagas,0)||' Valor total pago : '||coalesce(nValorTotalPago,0),lRaise,false,false);

          begin

            select case when count(*) = 1 then
                     1
                   else
                     ( count(*) - iQtdParcelasPagas )
                   end as qtdparcelas,

                   case when count(*) = 1 then
                     100
                   else
                     ( 100 / ( count(*) - iQtdParcelasPagas ) )
                   end as percpago

              into iQtdParcelas, nPercPagoNovo
              from cadvencdesc
                   left outer join cadvenc on q82_codigo = q92_codigo
             where cadvencdesc.q92_codigo = v_record_cadcalc.codven
               and ( case
                     when cadvencdesc.q92_formacalcparcvenc = 1
                       then case when q82_venc >= dInicioAtividade then true else false end
                     when cadvencdesc.q92_formacalcparcvenc = 3

                       then q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                     else
                       case
                         when cadvenc.q82_calculaparcvenc is true
                           then true
                         else
                           q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                       end
                   end ) ;

           exception

             when division_by_zero then
               nPercPagoNovo := 0;

           end;

          if iQtdParcelasPagas = 0 then
            nPercPagoNovo := 0;
          end if;

          /*
           * Quando a quantidade total de parcelas for negativo, significa que  o calculo n�o possui mais
           * parcelas em aberto, todas est�o abertas. logo, o total de parcelas devera ser 1
           */
          if iQtdParcelas < 0 then
            iQtdParcelas = 1;
          end if;

          if nPercPagoNovo < 0 then
            nPercPagoNovo = 100;
          end if;

          nDescontoPagamentoParcela := coalesce( ( nValorTotalPago / iQtdParcelas ) ,0);

          perform fc_debug(' PROCURANDO PAGAMENTOS : ',lRaise,false,false);
          perform fc_debug('--------------------------------------------------------------------------------------------',lRaise,false,false);
          perform fc_debug(' TOTAL PAGO           : '||coalesce( nValorTotalPago, 0)           ,lRaise,false,false);
          perform fc_debug(' PARCELAS PAGAS       : '||coalesce( iQtdParcelasPagas, 0)         ,lRaise,false,false);
          perform fc_debug(' PARCELAS A CALCULAR  : '||coalesce( iQtdParcelas, 0)              ,lRaise,false,false);
          perform fc_debug(' DESCONTO POR PARCELA : '||coalesce( nDescontoPagamentoParcela, 0) ,lRaise,false,false);
          perform fc_debug(' PERCENTUAL NOVO      : '||coalesce( nPercPagoNovo, 0)             ,lRaise,false,false);
          perform fc_debug('--------------------------------------------------------------------------------------------',lRaise,false,false);

          -- sempre deve deletar o calculo anterior no caso de encontrar um
          perform fc_debug('DELETANDO DO ARRECAD NUMPRE : '||v_numpre,lRaise,false,false);
          delete from arrecad where k00_numpre = v_numpre;

          -- Loop que gera/processa financeiro
          for v_record_cadvenc in select *
                                    from cadvencdesc
                                         left join cadvenc on q82_codigo = q92_codigo
                                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                                order by q82_parc
          loop

            perform fc_debug('------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('Processando Vencimento : '||v_record_cadvenc.q82_venc||' Parcela : '||v_record_cadvenc.q82_parc||' Inicio : '||v_record_cadcalc.inicio,lRaise,false,false);
            perform fc_debug('------------------------------------------------------------------------------------------',lRaise,false,false);

            /**
             * Guardar o vencimento atual do cadvenc
             */
            dVencimentoAtual := v_record_cadvenc.q82_venc;

            if dVencimentoAtual is null then
              dVencimentoAtual := dDatahj;
            end if;

            /**
             * Se a quantidade de vencimentos a processar for igual a zero
             */
            if iQtdeVencProcessar = 0 then

              -- soma dias para o vencimento na data do ultimo vencimento
              if iDiasvctoCissqn > 0 then

                dVencimentoAtual := ( dDatahj + iDiasvctoCissqn )::date;
                perform fc_debug('Trocou o vencimento para : '||dVencimentoAtual,lRaise,false,false);
              else

                -- se mesmo somando os dias para vencimento o debito continuar vencido joga para 31/12
                dVencimentoAtual := cast(to_char(iAnousu,'9999')||'-12-31' as date);
                perform fc_debug('Trocou o vencimento para ultimo dia do ano : '||dVencimentoAtual,lRaise,false,false);
              end if;

            end if;

            perform fc_debug('Vencimento apos processamento das regras : '||dVencimentoAtual,lRaise,false,false);

            /**
             * Variavel para controlar a forma para calculo de parcelas vencidas
             */
            lProcessaParcVencidas := ( case
                                         when v_record_cadvenc.q92_formacalcparcvenc = 1
                                           then true
                                         when v_record_cadvenc.q92_formacalcparcvenc = 3
                                           then v_record_cadvenc.q82_venc between dDatahj and iUltimoDiaAno
                                         else
                                           case
                                             when v_record_cadvenc.q82_calculaparcvenc is true
                                               then true
                                             else
                                               v_record_cadvenc.q82_venc between dDatahj and iUltimoDiaAno
                                           end
                                       end );

            perform fc_debug('Processando parcelas vencidas(lProcessaParcVencidas) ? '||(case when lProcessaParcVencidas is true then 'SIM' else 'NAO' end),lRaise,false,false);

            v_vencproc       = v_vencproc + 1;
            lProcessaParcela = false;

            perform fc_debug('Vencimento do cadvenc : '||dVencimentoAtual||' Maior Vencimento : '||dMaiorVencimentoCadvenc,lRaise,false,false);

            -- se vencimento do cadvenc do registro atual do for maior que o maximo vencimento do cadvenc
            if v_record_cadvenc.q82_venc > dMaiorVencimentoCadvenc then

              perform fc_debug('',lRaise,false,false);
              perform fc_debug('Vencimento do cadastro de vencimentos maior que o maximo vencimento, pasando lJaPassouUltVenc para true',lRaise,false,false);
              perform fc_debug('',lRaise,false,false);
              lJaPassouUltVenc = true;
            end if;

            perform fc_debug('Proximo vencimento : '||v_vencproc||'  Total de vencimentos : '||iTotalVencimentosCad||' Passa: '||( case when lProcessaParcela is true then 'true' else 'false' end ),lRaise,false,false);

            perform fc_debug('Data da baixa em branco',lRaise,false,false);
            lProcessaParcela = true;

            if extract( year from v_record_cadcalc.inicio) <> iAnousu then

              perform fc_debug('Ano de inicio diferente do atual ',lRaise,false,false);
              lProcessaParcela = true;
              v_numtot         = v_totvenc;

            else

              perform fc_debug('Ano de inicio igual do atual ',lRaise,false,false);
              if v_record_cadvenc.q82_venc >= dInicioAtividade or dVencimentoAtual is null or iQtdeVencProcessar = 0 or lProcessaParcVencidas is true then

                perform fc_debug('Vencimento: '||dVencimentoAtual||' maior ou igual a inicio: '||dInicioAtividade||' ou vencimento is null ',lRaise,false,false);
                lProcessaParcela = true;

              else

                perform fc_debug('Passando lProcessaParcela para FALSE -- Vencimento: '||dVencimentoAtual||' menor que inicio: '||dInicioAtividade,lRaise,false,false);
                lProcessaParcela = false;

              end if;
              v_numtot = iQtdeVencProcessar;

            end if;

            if dDtbaixa is not null and dVencimentoAtual > dDtbaixa then

              perform fc_debug('Data de baixa : '||dDtbaixa,lRaise,false,false);
              perform fc_debug('1 - Passando lProcessaParcela para false',lRaise,false,false);
            end if;

            if dDatahj > dVencimentoAtual and lProcessaParcVencidas is false then

              perform fc_debug('Inicio maior que data de vencimento e processar parcelas vencidas NAO',lRaise,false,false);
              lProcessaParcela = false;
            end if;

            perform fc_debug('----------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('Total de vencimentos     : '||iTotalVencimentosCad ,lRaise,false,false);
            perform fc_debug('Vecimentos do cadastro   : '||v_vencproc,lRaise,false,false);
            perform fc_debug('Ja passou ult vencimento : '||(case when lJaPassouUltVenc is true then 'SIM' else 'NAO' end),lRaise,false,false);
            perform fc_debug('Inicio                   : '||v_record_cadcalc.inicio,lRaise,false,false);
            perform fc_debug('Vencimento do Cadastro   : '||dVencimentoAtual,lRaise,false,false);
            perform fc_debug('----------------------------------------------------------------------------------------------',lRaise,false,false);

            if v_record_cadcalc.inicio > dVencimentoAtual and lJaPassouUltVenc is false and iTotalVencimentosCad <> v_vencproc then

              perform fc_debug('Passando lProcessaParcela para false',lRaise,false,false);
              lProcessaParcela = false;
            end if;

            perform fc_debug('Passando para o proximo vencimento(lProcessaParcela) ? '||(case when lProcessaParcela is true then 'True' else 'False' end),lRaise,false,false);

            if lProcessaParcela is true then

              -- DESCOBRIR EM QUE CASO E UTILIZADO
              --
              -- Desabilitado o if abaixo pois � justamente ele que impede o calculo para anos posteriores,
              v_venc = dVencimentoAtual;

              perform fc_debug('Quantidade de vencimentos(iQtdeVencProcessar) a processar : '||iQtdeVencProcessar,lRaise,false,false);
              if iQtdeVencProcessar = 0 then
                nPercentualParcela = 100;
              else

                -- Verificacao do valor total proporcional
                if v_record_cadcalc.tipopro = 'D' then

                  v_venc     = v_record_cadvenc.q82_venc;
                  v_venccalc = v_venc;
                  if lJaPassouUltVenc is true or v_record_cadvenc.q82_venc = dMaiorVencimentoCadvenc then

                    v_venccalc = to_char(iAnousu,'9999') || '-12-31';
                    dMaiorVencimentoCadvenc  = v_venccalc;

                  end if;
                  v_anoiniciocalc := extract(year from v_record_cadcalc.inicio);
                  if v_anoiniciocalc < iAnousu then
                    dInicioAtividadecalc = to_char(iAnousu,'9999') || '-01-01';
                  else
                    dInicioAtividadecalc = v_record_cadcalc.inicio;
                  end if;

                  perform fc_debug('Total de vencimentos : '||dMaiorVencimentoCadvenc||' Inicio: '||dInicioAtividadecalc,lRaise,false,false);
                  v_diasdesdeinicio = (iUltimoDiaAno - dInicioAtividadecalc)::integer + 1;
                  perform fc_debug('Vencimento calculo : '||v_venccalc||' Inicio: '||dInicioAtividadecalc,lRaise,false,false);
                  v_diasdestevcto = ((v_venccalc - dInicioAtividadecalc)::integer + 1) - v_diasjasomados;

                  if bGeral is true or dDtbaixa is not null then
                    nPercentualParcela = 100::float8 / iQtdeVencProcessar::float8;
                  else
                    nPercentualParcela = (100::float8 / v_diasdesdeinicio::float8)::float8 * v_diasdestevcto::float8;
                    v_diasjasomados = v_diasjasomados + v_diasdestevcto;
                  end if;

                else

                  nPercentualParcela = 100::float8 / iQtdParcelas::float8;

                end if;
              end if;

              perform fc_debug('Vencimento : '||v_venc||' Dias de vencimento : '||iDiasvctoCissqn,lRaise,false,false);

              if v_venc is null and iAnousu < v_anoatualservidor then

                v_venc = to_char(iAnousu,'9999')||'-12-31';

                --- verificado o vcto do alvara quando calculado, data calc. + 30 dias
                if iDiasvctoCissqn > 0 and iAnousu = v_anoatualservidor then

                  select dDatahj + iDiasvctoCissqn
                    into v_venc;

                  v_manual = v_manual || 'alterado vencimento para: '||v_venc||'\n';

                end if;
              end if;

              perform fc_debug('Percentual : '||nPercentualParcela,lRaise,false,false);
              perform fc_debug('Vencimento : '||v_venc,lRaise,false,false);

              if v_prim is false then
                if v_comcalculo is false then
                  insert into arreinscr values (v_numpre,iInscr);
                else
                  perform fc_debug('deletando do isscalc o numpre : '||v_numpre,lRaise,false,false);
                  v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || v_numpre || '\n';
                  delete from isscalc where q01_numpre = v_numpre;
                end if;

                insert into isscalc values (iAnousu, iInscr, v_record_cadcalc.cadcalc, v_receita, v_numpre, v_record_cadcalc.valor);
                insert into numpres values ( v_numpre );
                v_prim = true;

              end if;

              /**
               * Valida quando executa calculo geral
               */
              if bGeral is true then

                /**
                 * Subtraido valor que ja foi pago, caso contrario subtraira 0
                 */
                nValorParcela = round( ( (v_record_cadcalc.valorintegr) * nPercentualParcela / 100) - coalesce(nDescontoPagamentoParcela, 0), 2);

                perform fc_debug('(1) Valor Parcela : '||nValorParcela,lRaise,false,false);
                perform fc_debug('Valor Total       : '||v_record_cadcalc.valorintegr||' Valor Parcial(parcela) : '||nValorParcela,lRaise,false,false);

                /**
                 * Ultima Parcela
                 */
                if v_numpar = v_numtot then

                  /**
                   * Arredonda os centavos e joga na ultima
                   */
                  perform fc_debug('',lRaise,false,false);
                  perform fc_debug('(4) Valor Parcela : '||nValorParcela,lRaise,false,false);
                  nValorParcela = nValorParcela + ((v_record_cadcalc.valorintegr) - (nValorParcela * v_numtot));
                  perform fc_debug('(5) Valor Parcela : '||nValorParcela,lRaise,false,false);
                end if;

                perform fc_debug('100 -- Delete from arrecad numpre : '||v_numpre||' numpar : '||v_record_cadvenc.q82_parc,lRaise,false,false);

                -- se a parcela esta paga ou cancelada passa para a proxima
                perform * from fc_statusdebitos(v_numpre, v_record_cadvenc.q82_parc)
                  where rtstatus = 'PAGO'
                     or rtstatus = 'CANCELADO' limit 1;
                if found then

                  perform fc_debug('1 -- PARCELA '||v_record_cadvenc.q82_parc||' ESTA PAGA OU CANCELADA ',lRaise,false,false);
                  continue;
                end if;

                nValorParcela := round(nValorParcela,2);
                perform fc_debug('INSERT NUMERO 2 NO ARRECAD',lRaise,false,false);
                perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(nValorParcela,2)||' Vencimento : '||v_venc||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                if nValorParcela > 0 then

                  /**
                   * Alterado para inserir o q82_parc ao inves do numpar pois a pl nao levava em consideracao parcelas
                   * pagas e ou canceladas e assim gerando inconsistencia com numpar pago/cancelado e em aberto ao mesmo tempo
                   */
                  insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                       values (v_numcgm, v_dtbase, v_receita, v_record_cadvenc.q92_hist, round(nValorParcela,2),
                               v_venc, v_numpre, v_record_cadvenc.q82_parc, v_numtot, v_numdig, v_record_cadvenc.q92_tipo);

                end if;

              else

                -- se a parcela esta paga ou cancelada passa para a proxima
                perform * from fc_statusdebitos(v_numpre, v_record_cadvenc.q82_parc)
                  where rtstatus = 'PAGO'
                     or rtstatus = 'CANCELADO' limit 1;

                if found then

                  perform fc_debug('2 -- PARCELA '||v_record_cadvenc.q82_parc||' ESTA PAGA OU CANCELADA ',lRaise,false,false);
                  v_numpar = v_numpar + 1;
                  continue;

                  /**
                   * Replicada mesma logica descrita acima quando o calculo eh geral
                   */
                else
                  v_numpar = v_record_cadvenc.q82_parc;
                end if;

                if v_numpar is null then
                  v_numpar := 1;
                end if;

                v_dtvencano   = v_venc;
                nValorParcela = round( ( ( v_record_cadcalc.valorintegr * nPercentualParcela ) / 100 ) - nDescontoPagamentoParcela,2 );

                v_manual = v_manual || 'valor da parcela: ' || nValorParcela || '\n';

                perform fc_debug('Valor integral        : '||v_record_cadcalc.valorintegr||' Percentual : '||nPercentualParcela,lRaise,false,false);
                perform fc_debug('Valor da parcela      : '||nValorParcela,lRaise,false,false);
                perform fc_debug('Percentual da parcela : '||nPercentualParcela,lRaise,false,false);
                perform fc_debug('Desconto da parcela   : '||nDescontoPagamentoParcela,lRaise,false,false);

                perform fc_debug('300 -- Delete from arrecad numpre : '||v_numpre||' numpar : '||v_numpar,lRaise,false,false);

                if nValorParcela > 0 then

                  perform fc_debug('INSERT NUMERO 3 NO ARRECAD',lRaise,false,false);
                  perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(nValorParcela,2)||' - Vencimento : '||v_dtvencano||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                  /**
                   * Caso Seja Calculo de Alvar�, valida quantaas parcelas foram informadas para o c�lculo de Alvara
                   *
                   * Seguindo pelo principio:
                   * -- Dividir o Valor Pela Quantidade
                   * -- Criar parcelas do alvara sempre com base de vencimento
                   * -- Gravar nova estrutura de d�bitos no arrecad
                   **/
                   if v_record_cadcalc.cadcalc = 1  and iNumeroParcelasAlvara > 1 then

                    perform fc_debug('+----------------------------------------------',lRaise,false,false);
                    perform fc_debug('| Divisao da Parcela do Alvara em - '|| iNumeroParcelasAlvara ||' - partes ',lRaise,false,false);
                    perform fc_debug('+--------------------------------------------',lRaise,false,false);

                     for rParcelasAlvara
                      in select numero_parcela,
                                valor_parcela,
                                data_vencimento
                           from fc_issqn_divide_valores(round(nValorParcela,2), iNumeroParcelasAlvara,v_dtvencano )
                     loop
                       -- ---------------------------- --
                       --  Inserindo Dados no Arrecad  --
                       -- ---------------------------- --
                       perform fc_debug('| Parcela           : '|| rParcelasAlvara.numero_parcela ,lRaise,false,false);
                       perform fc_debug('| ValorParcela      : '|| rParcelasAlvara.valor_parcela  ,lRaise,false,false);
                       perform fc_debug('| VencimentoParcela : '|| rParcelasAlvara.data_vencimento,lRaise,false,false);


                        insert
                          into
                       arrecad (k00_numcgm,
                                k00_dtoper,
                                k00_receit,
                                k00_hist,
                                k00_valor,
                                k00_dtvenc,
                                k00_numpre,
                                k00_numpar,
                                k00_numtot,
                                k00_numdig,
                                k00_tipo)
                        values (v_numcgm,
                                v_dtbase,
                                v_receita,
                                v_record_cadvenc.
                                q92_hist,
                                rParcelasAlvara.valor_parcela,   -- VALOR ANTERIOR -> round(nValorParcela,2)
                                rParcelasAlvara.data_vencimento, -- VALOR ANTERIOR -> v_dtvencano,
                                v_numpre,
                                rParcelasAlvara.numero_parcela,  -- VALOR ANTERIOR -> v_numpar
                                iNumeroParcelasAlvara,           -- VALOR ANTERIOR -> v_numtot
                                v_numdig,
                                v_record_cadvenc.q92_tipo);

                     perform fc_debug('+--------------------------------------------',lRaise,false,false);
                     end loop;

                   else -- Fim da Validacao de parcelas e Tipo de Calculo, seguindo como era antes


                    insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                         values (v_numcgm, v_dtbase, v_receita, v_record_cadvenc.q92_hist, round(nValorParcela,2), v_dtvencano, v_numpre, v_numpar, v_numtot, v_numdig, v_record_cadvenc.q92_tipo);
                  end if;
                end if;

              end if; -- final do bloco para bgeral is false
              perform fc_debug('Valor parcela : '||nValorParcela,lRaise,false,false);

              if v_comcalculo is false then

                select fc_numbco(v_codbco,v_codage) into v_numbco;
                insert into arrebanco values (v_numpre,v_numpar,v_codbco,v_codage,v_numbco);
              end if;

              v_numpar = v_numpar + 1;
              v_jagravou = true;

              if nPercentualParcela = 100 then
                perform fc_debug('Saindo do for percentual = 100',lRaise,false,false);
                exit;
              end if;

            -- se lProcessaParcela is false
            else

              v_manual = v_manual || 'nao utilizando vencimento: ' || v_record_cadvenc.q82_venc || '\n';
              perform fc_debug('Nao processando financeiro lProcessaParcela is false',lRaise,false,false);

            end if;

          end loop;

        end if;

      end if;

    end loop;


    for v_record_cadcalc in select * from numpres loop

      select q01_manual
        into sManualText
        from isscalc
       where q01_inscr  = iInscr
         and q01_anousu = iAnousu
         and q01_manual is not null
       limit 1;

      v_manual := v_manual || 'atualizando log do calculo do numpre: ' || v_record_cadcalc.numpre || '\n';
      update isscalc
         set q01_manual = coalesce(sManualText,' ') || v_manual
       where q01_inscr = iInscr and q01_anousu = iAnousu;

    end loop;

    return '01-OK';

  end;

$$ language 'plpgsql';create or replace function fc_sanitario(integer,date,integer,date,boolean,boolean,integer)
returns varchar(200)
as $$
declare
  
	iInscr   					      alias for $1;
  dtDatahj  				 	    alias for $2;
  iAnoUsu						 	    alias for $3;
  dtBaixa						 	    alias for $4;
  lRecalc   				 	    alias for $5;
  lGeral						 	    alias for $6;
  iInstit						 	    alias for $7;
                         
  lProvisorio				 	    boolean default false;
  lComCalculo				 	    boolean default false;
	lRaise						 	    boolean default false;
                          
	dtInicio				        date;
  dtBase							    date;
  dDtProporcionalidade    date;
	                        
  sIntegr	  			        char(1);
  sVar  					        char(1);
  sCodAge				          char(5);
                          
	iForcal							    int2;
                          
	sText								    text;
  v_manual 	              text default '\n';
                          
  nValor					        float8;
  nQuant					        float8;
  nBase								    float8;
  nValInflator		        float8;
  nQProvisorio		        float8 default 1;
  
  sDescrTipCalc						varchar(40);
  sDescrCadCalc						varchar(40);
  sDescrProporcionalidade varchar(40);

  iSequencia	 		        integer;
  iInscrExiste 	          integer;
  iUqtab					        integer;
  iUqcad					        integer;
  iAtivPrinc	 		        integer;
  iCodVencCadCalc         integer;
  iNumpre							    integer;
  iNumpar 				        integer;
  iNumtot 				        integer;
  iNumdig 				        integer;
  iNumcgm 				        integer;
  iReceita				        integer;
  iCodBco							    integer;
	iInscrSani					    integer;
	iNumpreInscr				    integer;
  iSeila					        integer;
                          
  rRecAtiv						    record;
  rRecTipCalc					    record;
	rFinanceiro							record;
  rRecCadVenc 	      		record;
  rRecCadCalc 	      		record;
  rRecAtivPrinc 	    		record;
  rRecMaiorValor     			record;
  rRecSomaTodos 	    		record;
  
  BEGIN
    
  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
   
  perform fc_debug('INICIANDO C�LCULO DE SANIT�RIO',lRaise,true,false);

    execute 'create temporary table numpres ( "numpre" 	integer );';
    
    execute 'create temporary table tudo ( "anousu" 	integer,
																					 "inscr"		integer,
																					 "ativ"			integer,
																					 "tipcalc" 	integer,
																					 "cadcalc" 	integer,
																					 "vbase"		float8,
																					 "recexe" 	integer,
																					 "recpro" 	integer,
																					 "quant"		float8,
																					 "qiexe"		float8,
																					 "qfexe"		float8,
																					 "uqtab"		float8,
																					 "uqcad" 	  float8,
																					 "forcal" 	integer,
																					 "codven" 	integer,
																					 "integr" 	char(1),
																					 "tipopro" 	char(1),
																					 "valor" 	  float8,
																					 "valori"   float8,
																					 "inicio" 	date,
																					 "final"    date,
																					 "var"      char(1),
																				 	 "gera"     int2,
																					 "seq"      integer );';
    
    
		execute 'create temporary table porcalculo ( "anousu"			 integer,
																								 "inscr"			 integer,
																								 "vbase"			 float8,
																								 "tipcalc"  	 integer,
																								 "cadcalc"   	 integer,
																								 "forcal"  		 integer,
																								 "codven"  		 integer,
																								 "integr"  		 char(1),
																								 "tipopro"   	 char(1),
																								 "inicio"  		 date,
																								 "final"       date,
																								 "valor"	 		 float8,
																								 "valori"	 		 float8,
																								 "valorintegr" float8,
																								 "var"				 char(1),
																								 "gera"				 int2,
																								 "seq"         integer );';
    
    
		iSequencia = 1;
  
	  
		-- Verifica se h� registros da inscri��o na Sanit�rio
    
		select y80_codsani,y80_area
			from sanitario
			into iInscrExiste,nQuant
     where y80_codsani = iInscr;
    
		if iInscrExiste is null then
      return ' Inscricao n�o Cadastrada ';
    end if;


    -- Verifica se est� cadastrado data de in�cio da Atividade
		
		select min(y83_dtini) as y83_dtini
			from saniatividade
			into dtInicio
		 where y83_codsani = iInscr 
			 and y83_dtfim is null;
    
    if dtInicio is null then
      return ' Inscri��o sem data de inicio configurada! ';
    else
      v_manual = v_manual || 'Data de inicio da inscri��o: ' || dtInicio || '\n';
    end if;
    
		
		perform fc_debug('C�digo Sanit�rio  : '||iInscr,lRaise,false,false);
		perform fc_debug('In�cio            : '||dtInicio,lRaise,false,false);
		
		
		-- Verifica se h� base e data cadastrado na tabela de parametros (cissqn)
		
		select q04_vbase,
					 q04_dtbase
			from cissqn
			into nBase,
					 dtBase
		 where cissqn.q04_anousu = iAnoUsu;
		
		if nBase = 0 or nBase is null then
      return 'Sem valor base cadastrado nos parametros ';
    end if;
    
		if dtBase is null then
      return 'Sem data base cadastrada nos parametros ';
    end if;
    
    
    v_manual = v_manual || 'Valor base configurado nos parametros: ' || nBase || '\n';
		v_manual = v_manual || 'Data base configurada nos parametros:  ' || dtBase || '\n';
		
    
		-- Verifica valor do inflator
		
		select distinct i02_valor
			from cissqn
			into nValInflator
					 inner join infla on q04_inflat = i02_codigo
		 where cissqn.q04_anousu			 = iAnoUsu
			 and date_part('y',i02_data) = iAnoUsu;
    
		if nValInflator is null then
       nValInflator = 1;
    end if;
    
		v_manual = v_manual || 'Valor do inflator configurada nos parametros: ' || nValInflator || '\n';
    
		perform fc_debug('Valor do inflator : '||nValInflator,lRaise,false,false);

		if dtBaixa is null then
			perform fc_debug('Data da Baixa     : (Nulo)',lRaise,false,false);
    else
			perform fc_debug('Data da Baixa     :'||dtBaixa,lRaise,false,false);
		end if;

		-- Verifica quais os tipos de calculo para as atividades cadastradas para a inscricao
    
    
		perform fc_debug('',lRaise,false,false);
		perform fc_debug('            P R I M E I R A   E T A P A           ',lRaise,false,false);
		perform fc_debug('',lRaise,false,false);
		v_manual = v_manual || '\n--- P R I M E I R A   E T A P A  -----\n';
		
		
		for rRecAtiv in execute 'select * from ativs where y83_codsani = ' || iInscr loop
      
			v_manual = v_manual || '\n Processando Atividade: ' || rRecAtiv.y83_ativ || ' - ' || rRecAtiv.q03_descr || ' - In�cio: ' || rRecAtiv.y83_dtini || '\n';
      
			sText = 'select distinct tipcalc.*, 
															 cadcalc.q85_outromun, 
															 cadcalc.q85_var,
                               tipcalcexe.q83_codven
													from ativtipo 
															 inner join tipcalc    on q80_tipcal				  = q81_codigo 
                               inner join tipcalcexe on tipcalcexe.q83_anousu = ' || iAnoUsu || ' and tipcalcexe.q83_tipcalc = tipcalc.q81_codigo
															 inner join cadcalc    on cadcalc.q85_codigo = tipcalc.q81_cadcalc 
												 where q81_tipo in (2) 
													 and q80_ativ = ' || rRecAtiv.y83_ativ;
				
				
				-- Para cada atividade retornada com seu tipo de calculo...
				
				for rRecTipCalc in execute sText loop
        
					
					perform fc_debug('C�digo Tipcalc (q81_codigo) : '||rRecTipCalc.q81_codigo,lRaise,false,false);
					
					v_manual	 = v_manual || 'Processando tipo de c�lculo: ' || rRecTipCalc.q81_codigo || ' - ' || rRecTipCalc.q81_descr || '\n';
					
					
					
					if nQuant >= rRecTipCalc.q81_qiexe and nQuant <= rRecTipCalc.q81_qfexe then
						
						perform fc_debug('...entrou no if da quantidade',lRaise,false,false);
						
						---------------------------------------------------------
						-- Tabela tipcalc :
						-- q81_gera = 1  Gera somente no ano inicial da inscri��o
						-- q81_gera = 2  Gera todos os anos
						---------------------------------------------------------

						if rRecTipCalc.q81_gera = 1 and to_number(substr(dtInicio,1,4),'99999') < iAnoUsu then
							
							perform fc_debug('Exerc�cio da Atividade menor que o exerc�cio do c�lculo',lRaise,false,false);
							perform fc_debug('Exerc�cio da Atividade :'||to_number(substr(dtInicio,1,4),'99999'),lRaise,false,false);
							perform fc_debug('Exerc�cio do C�lculo   :'||iAnoUsu,lRaise,false,false);
							
							v_manual = v_manual || '\n';
							
						else
							
							perform fc_debug('...entrou no gera 2 do if',lRaise,false,false);
							v_manual = v_manual || ' Verificando Pagamentos\n';
								
							perform fc_debug('...Cep 2',lRaise,false,false);
								
								
							-- Se true, busca quantidade do tabativ, senao default 1
								
							if rRecTipCalc.q81_uqtab is false then
								iUqtab   = 1;
								v_manual = v_manual || 'Quantidade utilizada da tabela para calculo ( utilizada default do sistema):' || iUqtab || '\n';
								perform fc_debug('...quantidade utilizada da tabela para calculo ( utilizada default do sistema):' || iUqtab,lRaise,false,false);
							else
								iUqtab   = rRecAtiv.q07_quant;
								v_manual = v_manual || 'Quantidade utilizada para calculo ( baseada na quantidade da atividade lancada): ' || iUqtab || '\n';
								perform fc_debug('...quantidade utilizada para calculo ( baseada na quantidade da atividade lancada): ' || iUqtab,lRaise,false,false);
							end if;
								
							iUqcad = 1;
								
							if rRecTipCalc.q81_integr is true then
								sIntegr = '1';
							else
								sIntegr = '0';
							end if;
									
							v_manual = v_manual || 'integral: (1 = sim - 0 = nao): ' || sIntegr || '\n';
							perform fc_debug('Integral : (1 = Sim , 0 = N�o): ' || sIntegr,lRaise,false,false);
							

							select case 
											 when q85_var is true then '1' else '0' 
										 end as q85_var
								from cadcalc
								into sVar
							 where cadcalc.q85_codigo = rRecTipCalc.q81_cadcalc;
								
							if sVar is null then
								return 'nao definido no cadastro de calculo se variavel ou nao';
							end if;
								
							select q85_forcal
								from cadcalc
								into iForcal
							 where cadcalc.q85_codigo = rRecTipCalc.q81_cadcalc;
								
							if iForcal is null then
								return 'nao definido no cadastro de calculo a forma de calculo';
							end if;
								
							v_manual = v_manual || 'forma de calculo ( 1 = atividade principal - 2 = atividade com maior valor - 3 = soma do valor das atividades ):' || iForcal || '\n';
								
							select q85_perman
								from cadcalc
								into lProvisorio
							 where cadcalc.q85_codigo = rRecTipCalc.q81_cadcalc;
								
							if lProvisorio is true and rRecAtiv.q07_perman is false then
								nQProvisorio = 1.5;
								v_manual     = v_manual || 'provisorio: vai acrescer 50 por centro no valor calculado\n';
							else
								nQProvisorio = 1;
							end if;
								
							execute 'insert into tudo values ('	|| iAnoUsu || ',' 
																									|| iInscr  || ',' 
																									|| rRecAtiv.y83_ativ || ','
																									|| rRecTipCalc.q81_codigo || ','
																									|| rRecTipCalc.q81_cadcalc || ','
																									|| nBase || ','
																									|| rRecTipCalc.q81_recexe || ','
																									|| rRecTipCalc.q81_recpro || ','
																									|| nQuant || ','
																									|| rRecTipCalc.q81_qiexe || ','
																									|| rRecTipCalc.q81_qfexe || ','
																									|| iUqtab || ','
																									|| iUqcad || ','
																									|| iForcal || ','
																									|| rRecTipCalc.q83_codven || ','
																									|| sIntegr || ','
																									|| '''' ||rRecTipCalc.q81_tippro || '''' || ','
																									|| rRecTipCalc.q81_valexe * nBase * iUqtab * iUqcad * nQProvisorio * nValInflator || ','
																									|| rRecTipCalc.q81_valexe * nQProvisorio * nValInflator || ','
																									|| '''' || rRecAtiv.y83_dtini || '''' || ','
																									|| '''' || coalesce( rRecAtiv.y83_dtfim,(iAnoUsu||'-12-31')::date ) || '''' || ','
																									|| '''' || sVar                    || '''' || ','
																									|| rRecTipCalc.q81_gera || ','
																									|| iSequencia || ');';
								
							iSequencia = iSequencia + 1;
								
						end if;
						
					end if;
					
				end loop;
      
	    perform fc_debug('Terminou Atividade!',lRaise,false,false);
      
    end loop;
    
    
		select count(*) into iSeila from tudo;
    
    
    v_manual = v_manual || '\n Registros processados na etapa de tipos de calculo: ' || iSeila || '\n';
    v_manual = v_manual || '\n ---- S E G U N D A  E T A P A  ----- \n';
    v_manual = v_manual || '	Agrupando por cadastro de calculo' || '\n';
   
		perform fc_debug('',lRaise,false,false);
    perform fc_debug('            S E G U N D A   E T A P A            ',lRaise,false,false);
		perform fc_debug('',lRaise,false,false);

    
		for rRecCadCalc in select cadcalc, forcal, var from tudo group by cadcalc, forcal, var loop
      
      
			select q85_descr
				from cadcalc
			  into sDescrCadCalc
			 where q85_codigo = rRecCadCalc.cadcalc;
      
      
			v_manual = v_manual || 'processando calculo ' || rRecCadCalc.cadcalc || ' - ' || sDescrCadCalc || '\n';
      
			perform fc_debug('Var: '||rRecCadCalc.var||' - CadCalc: '||rRecCadCalc.cadcalc,lRaise,false,false);
      
			
			-- se for pela atividade principal
			
			v_manual = v_manual || ' nao variavel\n';
      
      if rRecCadCalc.forcal = 1 then
        
        v_manual = v_manual || ' calculando pela atividade principal\n';
        
        select y83_ativ 
					from saniatividade
				  into iAtivPrinc
				 where y83_codsani = iInscr 
					 and y83_ativprinc is true;
        
				if iAtivPrinc is null then
          return 'Sem atividade principal cadastrada para esta inscri��o ';
        end if;
        
        for rRecAtivPrinc in select * from tudo where tudo.cadcalc = rRecCadCalc.cadcalc loop
          
          v_manual = v_manual || ' inserindo na tabela de tipos de calculo a processar - tipcalc: ' || rRecAtivPrinc.tipcalc || ' - cadcalc: ' || rRecAtivPrinc.cadcalc || '\n';
          
					execute 'insert into porcalculo values ('|| iAnoUsu || ',' 
																									 || iInscr  || ',' 
																									 || nBase || ','
																									 || rRecAtivPrinc.tipcalc || ','
																									 || rRecAtivPrinc.cadcalc || ','
																									 || rRecAtivPrinc.forcal  || ','
																									 || rRecAtivPrinc.codven  || ','
																									 || rRecAtivPrinc.integr  || ','
																									 || '''' || rRecAtivPrinc.tipopro || '''' || ','
																									 || '''' || rRecAtivPrinc.inicio  || '''' || ','
																									 || '''' || coalesce( rRecAtivPrinc.final,(iAnoUsu||'-12-31')::date ) || '''' || ','
																									 || rRecAtivPrinc.valor   || ','
																									 || rRecAtivPrinc.valori  || ','
																									 || 0 || ','
																									 || '''' || rRecAtivPrinc.var     || '''' || ','
																									 || rRecAtivPrinc.gera    || ','
																									 || rRecAtivPrinc.seq
																									 || ');';
        end loop;
        
      end if;
      
			
			-- pela atividade que gerou o maior valor
      
			
			if rRecCadCalc.forcal = 2 then
        v_manual = v_manual || 'calculando pela atividade que gerou o maior valor\n';
        
        for rRecMaiorValor in select * from tudo where tudo.cadcalc = rRecCadCalc.cadcalc order by valor desc limit 1 loop
          
          execute 'insert into porcalculo values (' || iAnoUsu || ',' 
																										|| iInscr  || ',' 
																										|| nBase || ','
																										|| rRecMaiorValor.tipcalc || ','
																										|| rRecMaiorValor.cadcalc || ','
																										|| rRecMaiorValor.forcal  || ','
																										|| rRecMaiorValor.codven  || ','
																										|| rRecMaiorValor.integr  || ','
																										|| '''' || rRecMaiorValor.tipopro || '''' || ','
																										|| '''' || rRecMaiorValor.inicio  || '''' || ','
																										|| '''' || coalesce( rRecMaiorValor.final,(iAnoUsu||'-12-31')::date ) || '''' || ','
																										|| rRecMaiorValor.valor   || ','
																										|| rRecMaiorValor.valori  || ','
																										|| 0 || ','
																										|| '''' || rRecMaiorValor.var     || '''' || ','
																										|| rRecMaiorValor.gera    || ','
																										|| rRecMaiorValor.seq 
																										|| ');';
          
        end loop;
        
      end if;
      
      
      
			if rRecCadCalc.forcal = 3 then
        
				v_manual = v_manual || 'calculando pela soma de todos os valores\n';
        
        for rRecSomaTodos in select sum(valor) as somatotal from tudo where tudo.cadcalc = rRecCadCalc.cadcalc loop
          
          select q85_codven 
          	from cadcalc
          	into iCodVencCadCalc
           where q85_codigo = rRecCadCalc.cadcalc;
					
					if iCodVencCadCalc is null then
            return 'sem vencimento padrao no cadastro de vencimentos ';
          end if;
          
          execute 'insert into porcalculo values ('|| iAnoUsu || ',' 
																									 || iInscr  || ',' 
																									 || nBase   || ','
																									 || rRecCadCalc.tipcalc || ','
																									 || rRecCadCalc.cadcalc || ','
																									 || rRecCadCalc.forcal  || ','
																									 || iCodVencCadCalc          || ','
																									 || rRecCadCalc.integr  || ','
																									 || '''' || rRecCadCalc.tipopro || '''' || ','
																									 || '''' || rRecCadCalc.inicio  || '''' || ','
	 																						     || '''' || coalesce( rRecCadCalc.final, (iAnoUsu||'-12-31')::date)   || '''' || ','
																									 || rRecSomaTodos.somatotal
																									 || ');';
          
        end loop;
        
      end if;
      
    end loop;
    



    v_manual = v_manual || '\n---- T E R C E I R A  E T A P A  -----\n';
    v_manual = v_manual || 'Agrupando por tipo de vencimento e preparando para calcular\n';


		perform fc_debug('',lRaise,false,false);
    perform fc_debug('            T E R C E I R A   E T A P A          ',lRaise,false,false);
		perform fc_debug('',lRaise,false,false);



		for rRecCadVenc in select codven from porcalculo group by codven loop
      
      v_manual = v_manual || 'processando vencimento ' || rRecCadVenc.codven || '\n';
			
			perform fc_debug('processando vencimento ' || rRecCadVenc.codven,lRaise,false,false);
      
			
			for rRecCadCalc in select * from porcalculo where codven = rRecCadVenc.codven loop
        
        select q81_abrev 
					from tipcalc 
					into sDescrTipCalc
				 where q81_codigo = rRecCadCalc.tipcalc;
        
        select q85_descr
          from cadcalc
          into sDescrCadCalc
         where q85_codigo = rRecCadCalc.cadcalc;
        
        
        nValor	 = rRecCadCalc.valor;
				
				v_manual = v_manual || 'processando tipcalc: ' || rRecCadCalc.tipcalc || ' - ' || sDescrTipCalc || ' - cadcalc: ' || rRecCadCalc.cadcalc || ' - ' || sDescrCadCalc || '\n';
				v_manual = v_manual || 'valor: ' || nValor || '\n';
        
        
				if rRecCadCalc.integr = '0' then
          
          v_manual = v_manual || '   nao integral = proporcional ' || ' inicio: ' || rRecCadCalc.inicio || ' - ano atual ' || iAnoUsu || '\n';
					
					---- Fun��o Proporcionalidade ----
				
					if dtBaixa is null then
						dDtProporcionalidade := rRecCadCalc.final;
					else
						dDtProporcionalidade := dtBaixa;
					end if;
					
				  perform fc_debug('--------------------------------------------------------',lRaise,false,false);
					perform fc_debug('Valor                   : '||nValor,lRaise,false,false);
					perform fc_debug('Tipo Proporcionalidade  : '||rRecCadCalc.tipopro,lRaise,false,false);
					perform fc_debug('Inicio Atividade        : '||rRecCadCalc.inicio,lRaise,false,false);
					perform fc_debug('Final Aividade          : '||dDtProporcionalidade,lRaise,false,false);
					
					
					select rnValorProporcional,rsTipoProporcionalidade
            into nValor,sDescrProporcionalidade
            from fc_issqn_proporcionalidade(rRecCadCalc.valor::numeric,rRecCadCalc.tipopro::varchar,rRecCadCalc.inicio::date,dDtProporcionalidade::date,iAnoUsu::integer,dtBaixa::date);
						


					perform fc_debug('Valor Proporcional      : '||nValor,lRaise,false,false);
					perform fc_debug('--------------------------------------------------------',lRaise,false,false);
        
				else
          
					v_manual = v_manual || '   integral\n';
					perform fc_debug('Integral',lRaise,false,false);
        
				end if;
        
        execute 'update porcalculo set valorintegr = ' || nValor || ' where seq = ' || rRecCadCalc.seq || ';';
        
      end loop; 
      
    end loop;

		
		
		select count(*) into iSeila from porcalculo;
    
		perform fc_debug('',lRaise,false,false);
    perform fc_debug('            Q U A R T A  E T A P A            ',lRaise,false,false);
		perform fc_debug('',lRaise,false,false);
		
		perform fc_debug('Iniciando o c�lculo ...',lRaise,false,false);
		perform fc_debug('Total porcalculo final : '||iSeila,lRaise,false,false);

		v_manual = v_manual || '\n---- Q U A R T A  E T A P A  -----\n';
    v_manual = v_manual || 'Total de calculos que o sistema vai processar: ' || iSeila || '\n';
    
    
		if iSeila = 0 then
			return '2' || ' nenhum calculo efetuado!';
    end if;
    
    
		for rRecCadCalc in select * from porcalculo loop
      
      select q81_abrev 
				from tipcalc 
				into sDescrTipCalc
       where q81_codigo = rRecCadCalc.tipcalc;
      
      select q85_descr
        from cadcalc
				into sDescrCadCalc
       where q85_codigo = rRecCadCalc.cadcalc;
      
      
			perform fc_debug('Tipcalc : '||rRecCadCalc.tipcalc,lRaise,false,false);
			perform fc_debug('Calculo : '||rRecCadCalc.tipcalc,lRaise,false,false);
      v_manual = v_manual || '\nprocessando calculo ' || rRecCadCalc.cadcalc || ' - ' || sDescrCadCalc ||  ' - tipo de calculo: ' || rRecCadCalc.tipcalc || ' - ' || sDescrTipCalc || '\n';
      
      
      if dtBaixa is not null and rRecCadCalc.var = '1' then

				perform fc_debug(' Atividade com Baixa : ',dtBaixa,lRaise,false,false);
        v_manual = v_manual || 'com data de baixa passada como parametro: ' || dtBaixa || ' e variavel: nao calcula\n';

      else
        
				perform fc_debug('Atividade sem Baixa',lRaise,false,false);
        
        select y84_numpre
					from sanicalc
					into iNumpre
				 where y84_anousu  = iAnoUsu
					 and y84_codsani = iInscr;
        
        if iNumpre is null then
          lComCalculo = false;
					perform fc_debug('lComCalculo is false',lRaise,false,false);
        else
          lComCalculo = true;
					perform fc_debug('lComCalculo is true',lRaise,false,false);
        end if;
        
        
        if lComCalculo is false then
          
					v_manual = v_manual || 'calculo novo\n';
          
					select nextval('numpref_k03_numpre_seq') into iNumpre;
					
					if iNumpre is null then
            return 'erro do processar sequencia do numpre';
          end if;
        
				else
          
					v_manual = v_manual || 'calculo ja existe... utilizar o mesmo numpre\n';
					perform fc_debug('C�lculo j� existe, utilizar o mesmo Numpre',lRaise,false,false);
          
					select y84_numpre
						from sanicalc
						into iNumpre
					 where y84_anousu  = iAnoUsu
						 and y84_codsani = iInscr;
        
				end if;
        
        iNumpar = 1;
        
        select max(q82_parc) 
					from cadvenc 
							 inner join cadvencdesc on q82_codigo = q92_codigo 
				 where cadvenc.q82_codigo = rRecCadCalc.codven
					into iNumtot;
        
				if iNumtot is null then
          iNumtot = 0;
				end if;
        
        if lGeral is false then
          iNumtot = 1;
        end if;
        
        
				v_manual = v_manual || 'total de parcelas baseado no vencimento: ' || iNumtot || '\n';
        
        
				select fc_digito(iNumpre,iNumpar,iNumtot) into iNumdig;
        
				if iNumdig is null then
          return 'erro do processar funcao de calculo do digito verificador';
        end if;
        
        select k15_codbco 
          from cadvencdescban
							 inner join cadban on cadvencdescban.q93_cadban = cadban.k15_codigo
          into iCodBco
         where q93_codigo = rRecCadCalc.codven;
        
				if iCodBco is null then
           iCodBco = 0;
        end if;
        
        select k15_codage 
					from cadvencdescban
							 inner join cadban on cadvencdescban.q93_cadban = cadban.k15_codigo
					into sCodAge
				 where q93_codigo = rRecCadCalc.codven;
				
				if sCodAge is null then
          sCodAge = 0;
        end if;
        
        select y80_numcgm
					from sanitario
					into iNumcgm
				 where y80_codsani = iInscr;
        
				if iNumcgm is null then
          return 'problema no select q02_numcgm where q02_inscr = inscricao';
        end if;
        
        
				perform fc_debug('In�cio : '||rRecCadCalc.inicio||' AnoUsu:'||iAnoUsu,lRaise,false,false);
        
        
        if iAnoUsu = to_number(substr(rRecCadCalc.inicio,1,4),'99999') then
          
					v_manual = v_manual || '   ano atual igual ao ano de inicio da atividade: ' || iAnoUsu || '\n';
					perform fc_debug('Ano atual igual ao ano de inicio...',lRaise,false,false);
          
					select recexe 
						from tudo 
						into iReceita
           where tudo.seq = rRecCadCalc.seq;
        
				else
          
					v_manual = v_manual || '   ano atual diferente do ano de inicio da atividade: ' || iAnoUsu || '\n';
					perform fc_debug('Ano atual diferente ao ano de inicio...',lRaise,false,false);
          
					select recpro 
						from tudo 
						into iReceita
					 where tudo.seq = rRecCadCalc.seq;
        
				end if;
        
				
				v_manual = v_manual || 'processando com base no cadastro de vencimentos\n';
        
        
				-- Deleta registros da sanicalc caso exista
				
				if lComCalculo is true then
          
					perform fc_debug('Deletando da sanicalc e arrecad - Numpre : '||iNumpre,lRaise,false,false);
					v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || iNumpre || '\n';
          
					delete from sanicalc where y84_numpre = iNumpre;
          delete from arrecad  where k00_numpre = iNumpre;
        
				end if;
        
				perform fc_debug('--------------------------------------------------------',lRaise,false,false);
				perform fc_debug('                    Inserindo Arrecad ',lRaise,false,false);
				perform fc_debug('--------------------------------------------------------',lRaise,false,false);
				perform fc_debug('Numpre     : '||iNumpre,lRaise,false,false);
				perform fc_debug('Numcgm     : '||iNumcgm,lRaise,false,false);
				perform fc_debug('Codven     : '||rRecCadCalc.codven,lRaise,false,false);
				perform fc_debug('Data Oper. : '||dtBase,lRaise,false,false);
				perform fc_debug('Receita    : '||iReceita,lRaise,false,false);
				perform fc_debug('--------------------------------------------------------',lraise,false,false);

				-- Fun��o que Gera Financeiro ( Insere no Arrecad )
        
				select *
          into rFinanceiro
          from fc_gerafinanceiro(iNumpre::integer,nValor::float8,rRecCadCalc.codven::integer,iNumcgm::integer,dtBase::date,iReceita::integer,dtDatahj::date);

				--	Insere Registro na Sanicalc
				
				perform fc_debug('--------------------------------------------------------',lRaise,false,false);
				perform fc_debug('                    Inserindo Sanicalc ',lRaise,false,false);
				perform fc_debug('--------------------------------------------------------',lRaise,false,false);
				perform fc_debug('C�d. Sanit�rio : '||iInscr,lRaise,false,false);
				perform fc_debug('Anousu         : '||iAnoUsu,lRaise,false,false);
				perform fc_debug('Numpre         : '||iNumpre,lRaise,false,false);
				perform fc_debug('Valor          : '||round(nValor,2),lRaise,false,false);
				perform fc_debug('--------------------------------------------------------',lRaise,false,false);
				
				insert into sanicalc values ( iInscr, iAnoUsu, iNumpre,round(nValor,2));
				insert into numpres  values ( iNumpre );


				--	Consulta Inscri��o do sanit�rio e insere na tabela arreinscr
				
				select y18_inscr 
				  into iInscrSani 
				  from sanitarioinscr 
				 where y18_codsani = iInscr;
        
				if iInscrSani is not null then
          
          select k00_numpre 
					  from arreinscr 
						into iNumpreInscr 
					 where k00_numpre = iNumpre;
          
					if iNumpreInscr != 0 or iNumpreInscr is not null then
						delete from arreinscr where k00_numpre = iNumpreInscr;
					end if;
          
				  perform fc_debug('--------------------------------------------------------',lraise,false,false);
				  perform fc_debug('                    Inserindo Arreinscr ',lRaise,false,false);
				  perform fc_debug('--------------------------------------------------------',lraise,false,false);
					perform fc_debug('Inscri��o : '||iInscrSani,lRaise,false,false);
					perform fc_debug('Numpre    : '||iNumpre,lRaise,false,false);
					perform fc_debug('--------------------------------------------------------',lraise,false,false);
          
					insert into arreinscr ( k00_numpre,k00_inscr ) values ( iNumpre,iInscrSani );
          
        end if;

      end if;
      
    end loop;

    
    for rRecCadCalc in select * from numpres loop
      
      v_manual = v_manual || 'atualizando log do calculo do numpre: ' || rRecCadCalc.numpre || '\n';
      --update isscalc set q01_manual = v_manual where q01_numpre = rRecCadCalc.numpre; 
      
    end loop;
    
    execute 'drop table tudo;';
    execute 'drop table porcalculo;';
		
	  raise notice '%',fc_debug('Conclu�do Calculo para o sanit�rio : '||iInscr,lRaise,false,true);		
    
    return 'ok';
    
  end;
  
$$ language 'plpgsql';insert into db_versaoant (db31_codver,db31_data) values (355, current_date);
select setval ('db_versaousu_db32_codusu_seq',(select max (db32_codusu) from db_versaousu));
select setval ('db_versaousutarefa_db28_sequencial_seq',(select max (db28_sequencial) from db_versaousutarefa));
select setval ('db_versaocpd_db33_codcpd_seq',(select max (db33_codcpd) from db_versaocpd));
select setval ('db_versaocpdarq_db34_codarq_seq',(select max (db34_codarq) from db_versaocpdarq));create table bkp_db_permissao_20150706_173813 as select * from db_permissao;
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
