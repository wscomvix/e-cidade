<?php

class MSCEncerramento {

    //@var integer
    public $iConta;
    //@var integer
    public $iIC1;
    //@var string
    public $sTipoIC1;
    //@var integer
    public $iIC2;
    //@var string
    public $sTipoIC2;
    //@var integer
    public $iIC3;
    //@var string
    public $sTipoIC3;
    //@var integer
    public $iIC4;
    //@var string
    public $sTipoIC4;
    //@var integer
    public $iIC5;
    //@var string
    public $sTipoIC5;
    //@var integer
    public $iIC6;
    //@var string
    public $sTipoIC6;
    //@var integer
    public $iValor;
    //@var string
    public $sTipoValor;
    //@var string
    public $sNaturezaValor;
    //@var string
    public $sIdentifier;
    //@var string
    public $sInstant;
    //@var string
    public $sEntriesType;
    //@var string
    public $sPeriodIdentifier;
    //@var string
    public $sPeriodDescription;
    //@var string
    public $sPeriodStart;
    //@var string
    public $sPeriodEnd;
    //@var string
    public $aRegistros = array();
    //@var string
    public $sNomeArq;
    //@var string
    public $sCaminhoArq;
    //@var integer
    public $iErroSQL;
    //@var string
    public $sTipoMatriz;

    //Conta
    public function setConta($iConta) {
        $this->iConta = $iConta;
    }
    public function getConta() {
        return $this->iConta;
    }
    //IC1
    public function setIC1($iIC1) {
        $this->iIC1 = $iIC1;
    }
    public function getIC1() {
        return $this->iIC1;
    }
    //Tipo1
    public function setTipoIC1($sTipoIC1) {
        $this->sTipoIC1 = $sTipoIC1;
    }
    public function getTipoIC1() {
        return $this->sTipoIC1;
    }
    //IC2
    public function setIC2($iIC2) {
        $this->iIC2 = $iIC2;
    }
    public function getIC2() {
        return $this->iIC2;
    }
    //Tipo2
    public function setTipoIC2($sTipoIC2) {
        $this->sTipoIC2 = $sTipoIC2;
    }
    public function getTipoIC2() {
        return $this->sTipoIC2;
    }
    //IC3
    public function setIC3($iIC3) {
        $this->iIC3 = $iIC3;
    }
    public function getIC3() {
        return $this->iIC3;
    }
    //Tipo3
    public function setTipoIC3($sTipoIC3) {
        $this->sTipoIC3 = $sTipoIC3;
    }
    public function getTipoIC3() {
        return $this->sTipoIC3;
    }
    //IC4
    public function setIC4($iIC4) {
        $this->iIC4 = $iIC4;
    }
    public function getIC4() {
        return $this->iIC4;
    }
    //Tipo4
    public function setTipoIC4($sTipoIC4) {
        $this->sTipoIC4 = $sTipoIC4;
    }
    public function getTipoIC4() {
        return $this->sTipoIC4;
    }
    //IC5
    public function setIC5($iIC5) {
        $this->iIC5 = $iIC5;
    }
    public function getIC5() {
        return $this->iIC5;
    }
    //Tipo5
    public function setTipoIC5($sTipoIC5) {
        $this->sTipoIC5 = $sTipoIC5;
    }
    public function getTipoIC5() {
        return $this->sTipoIC5;
    }
    //IC6
    public function setIC6($iIC6) {
        $this->iIC6 = $iIC6;
    }
    public function getIC6() {
        return $this->iIC6;
    }
    //Tipo6
    public function setTipoIC6($sTipoIC6) {
        $this->sTipoIC6 = $sTipoIC6;
    }
    public function getTipoIC6() {
        return $this->sTipoIC6;
    }
    //Valor
    public function setValor($iValor) {
        $this->iValor = $iValor;
    }
    public function getValor() {
        return $this->iValor;
    }
    //TipoValor
    public function setTipoValor($sTipoValor) {
        $this->sTipoValor = $sTipoValor;
    }
    public function getTipoValor() {
        return $this->sTipoValor;
    }
    //NaturezaValor
    public function setNaturezaValor($sNaturezaValor) {
        $this->sNaturezaValor = $sNaturezaValor;
    }
    public function getNaturezaValor() {
        return $this->sNaturezaValor;
    }
    //Identifier
    public function setIdentifier($sIdentifier) {
        $this->sIdentifier = $sIdentifier;
    }
    public function getIdentifier() {
        return $this->sIdentifier;
    }
    //Instant
    public function setInstant($sInstant) {
        $this->sInstant = $sInstant;
    }
    public function getInstant() {
        return $this->sInstant;
    }
    //EntriesType
    public function setEntriesType($sEntriesType) {
        $this->sEntriesType = $sEntriesType;
    }
    public function getEntriesType() {
        return $this->sEntriesType;
    }
    //PeriodIdentifier
    public function setPeriodIdentifier($sPeriodIdentifier) {
        $this->sPeriodIdentifier = $sPeriodIdentifier;
    }
    public function getPeriodIdentifier() {
        return $this->sPeriodIdentifier;
    }
    //PeriodDescription
    public function setPeriodDescription($sPeriodDescription) {
        $this->sPeriodDescription = $sPeriodDescription;
    }
    public function getPeriodDescription() {
        return $this->sPeriodDescription;
    }
    //PeriodStart;
    public function setPeriodStart($sPeriodStart) {
        $this->sPeriodStart = $sPeriodStart;
    }
    public function getPeriodStart() {
        return $this->sPeriodStart;
    }
    //PeriodEnd;
    public function setPeriodEnd($sPeriodEnd) {
        $this->sPeriodEnd = $sPeriodEnd;
    }
    public function getPeriodEnd() {
        return $this->sPeriodEnd;
    }
    //Nome do arquivo;
    public function setNomeArq($sNomeArq) {
        $this->sNomeArq = $sNomeArq;
    }
    public function getNomeArq() {
        return $this->sNomeArq;
    }
    //Caminho do arquivo;
    public function setCaminhoArq($sCaminhoArq) {
        $this->sCaminhoArq = $sCaminhoArq;
    }
    public function getCaminhoArq() {
        return $this->sCaminhoArq;
    }
    public function getErroSQL() {
        return $this->iErroSQL;
    }
    public function setErroSQL($iErroSQL) {
        $this->iErroSQL = $iErroSQL;
    }
    //TipoMatriz
    public function setTipoMatriz($sTipoMatriz) {
        $this->sTipoMatriz = $sTipoMatriz;
    }
    public function getTipoMatriz() {
        return $this->sTipoMatriz;
    }

    public function gerarMSC($ano, $mes, $formato) {

        $aRegis = $this->getConsulta($ano, $mes);
        ksort($aRegis);
        foreach ($aRegis as $key => $value){
            $this->aRegistros += $this->gerarLinhas($value);
        }

        switch ($formato) {

            case 'xbrl' :

                if (file_exists("model/contabilidade/arquivos/msc/" . $ano . "/MSCXbrl.model.php")) {

                    require_once("model/contabilidade/arquivos/msc/" . $ano . "/MSCXbrl.model.php");

                    $xbrl = new MSCXbrl;
                    $xbrl->setIdentifier($this->getIdentifier());
                    $xbrl->setEntriesType($this->getEntriesType());
                    $xbrl->setPeriodIdentifier($this->getPeriodIdentifier());
                    $xbrl->setPeriodDescription($this->getPeriodDescription());
                    $xbrl->setPeriodStart($this->getPeriodStart());
                    $xbrl->setPeriodEnd($this->getPeriodEnd());
                    $xbrl->setInstant($this->getInstant());
                    $xbrl->setNomeArq($this->getNomeArq());
                    $xbrl->gerarArquivoXBRL($this->aRegistros);

                } else {
                    throw new Exception ("Arquivo MSCXbrl para o ano {$ano} n�o existe. ");
                }

                break;

            case 'csv' :

                if (file_exists("model/contabilidade/arquivos/msc/" . $ano . "/MSCCsv.model.php")) {

                    require_once("model/contabilidade/arquivos/msc/" . $ano . "/MSCCsv.model.php");

                    $csv = new MSCCsv;
                    $csv->setNomeArq($this->getNomeArq());
                    $csv->setIdentifier($this->getIdentifier());
                    $csv->setPeriodIdentifier($this->getPeriodIdentifier());
                    $csv->gerarArquivoCSV($this->aRegistros);

                } else {
                    throw new Exception ("Arquivo MSCCsv para o ano {$ano} n�o existe. ");
                }

                break;

            default:

        }

    }

    public function gerarLinhas($oRegistro) {

        $aLinhas = array('beginning_balance', 'period_change_deb', 'period_change_cred', 'ending_balance');
        $aRegistros = array();
        $indice = "";

        for ($ind = 0; $ind <= 6; $ind++) {
            $indice .= ($oRegistro[$ind] != "null") ? $oRegistro[$ind] : '';
        }

        $oContas = new stdClass;
        $oContas->registros = array();

        for ($i=0; $i < 4; $i++) {

            if (in_array($aLinhas[$i], $oRegistro, true)) {

                $key = array_search($aLinhas[$i], $oRegistro, true);
                $oNovoRegistro = new stdClass;
                $oNovoRegistro->conta = $oRegistro[0];

                // so vai ter um registro no arquivo se o valor for diferente de ZERO
                if (number_format($oRegistro[$key-1], 2, '.', '') > 0) {
                    if (($aLinhas[$i] == 'beginning_balance' || $aLinhas[$i] == 'ending_balance')) {
                        $oNovoRegistro->nat_vlr   = $oRegistro[$key+1];
                        $oNovoRegistro->tipoValor = $aLinhas[$i];
                        $oNovoRegistro->valor     = number_format($oRegistro[$key-1], 2, '.', '');
                    }
                    else if ($aLinhas[$i] == 'period_change_deb' || $aLinhas[$i] == 'period_change_cred') {
                        $nat_valor = explode("_", $aLinhas[$i]);
                        $oNovoRegistro->nat_vlr   = $nat_valor[2] == 'deb' ? 'D' : 'C';
                        $oNovoRegistro->tipoValor = 'period_change';
                        $oNovoRegistro->valor     = number_format($oRegistro[$key-1], 2, '.', '');
                    }

                    $aTipoIC = array("po", "fp", "fr", "nr", "nd", "fs", "ai", "dc", "es");

                    for ($ii = 1; $ii <= 6; $ii++) {
                        $IC = "IC".$ii;
                        $TipoIC = "TipoIC".$ii;

                        if ($oRegistro[$ii] != "null") {
                            $cIC = explode("_", $oRegistro[$ii]);
                            if (in_array($cIC[1], $aTipoIC, true)) {
                                $oNovoRegistro->{$IC}     = $cIC[0];
                                $oNovoRegistro->{$TipoIC} = strtoupper($cIC[1]);
                            }
                        }
                    }
                    $aRegistros[$indice] = $oContas;
                    $aRegistros[$indice]->registros[$i] = $oNovoRegistro;
                }
            }
        }

        return $aRegistros;
    }

    public function getConsulta($ano, $mes) {
        /*
          * Definindo o periodo em que serao selecionado os dados
          */
        $this->setErroSQL(0);
        $data_inicio = db_getsession("DB_anousu")."-12-01";
        $data_fim   = db_getsession("DB_anousu")."-12-31";
        $aDadosAgrupados = array();

        $aDadosAgrupados = array_merge(
            (array)$this->getDadosIC01($ano, $data_inicio, $data_fim),
            (array)$this->getDadosIC02($ano, $data_inicio, $data_fim),
            (array)$this->getDadosIC03($ano, $data_inicio),
            (array)$this->getDadosIC04($ano, $data_inicio),
            (array)$this->getDadosIC05($ano, $data_inicio),
            (array)$this->getDadosIC06($ano, $data_inicio),
            (array)$this->getDadosIC07EMP($ano, $data_inicio),
            (array)$this->getDadosIC07RSP($ano, $data_inicio),
            (array)$this->getDadosIC08($ano, $data_inicio, $data_fim),
            (array)$this->getDadosIC09EMP($ano, $data_inicio),
            (array)$this->getDadosIC09RSP($ano, $data_inicio)
        );
        return $aDadosAgrupados;
    }

    public function getDadosIC($IC, $aCampos, $rsResult) {
        $aDadosIC  = "aDadosIC{$IC}";
        $$aDadosIC = array();
        $aIC       = "aIC{$IC}";

        for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {
            $oReg = db_utils::fieldsMemory($rsResult, $iCont);
            $sHash = "";
            for ($ind = 0; $ind <= 6; $ind++) {
                $sHash .= (isset($oReg->{$aCampos[$ind]}) && !empty($oReg->{$aCampos[$ind]})) ? $oReg->{$aCampos[$ind]} : '';
            }
            if (!isset(${$aDadosIC}[$sHash])) {
                $$aIC = array();
                for ($i = 0; $i < 17; $i++) {

                    if ($i > 0 && $i <= 6) {
                        ${$aIC}[$i] = isset($oReg->{$aCampos[$i]}) ? "{$oReg->{$aCampos[$i]}}_{$aCampos[$i]}" : "_{$aCampos[$i]}";
                    }
                    else if ($i == 7) {
                        ${$aIC}[$i] = ($oReg->nat_vlr_si == 'C') ? $oReg->saldoinicial * -1 : $oReg->saldoinicial;
                    }
                    else if ($i == 10) {
                        ${$aIC}[$i] += $oReg->debito;
                    }
                    else if ($i == 11) {
                        ${$aIC}[$i] = $oReg->tipovalordeb;
                    }
                    else if ($i == 12) {
                        ${$aIC}[$i] += $oReg->credito;
                    }
                    else if ($i == 13) {
                        ${$aIC}[$i] = $oReg->tipovalorcred;
                    }
                    else if ($i == 14) {
                        ${$aIC}[$i] += ($oReg->nat_vlr_sf == 'C' ? $oReg->saldofinal * -1 : $oReg->saldofinal);
                    }
                    else {
                        ${$aIC}[$i] = $oReg->{$aCampos[$i]};
                    }
                }
                ${$aDadosIC}[$sHash] = $$aIC;

            } else {
                ${$aDadosIC}[$sHash][7]  += ($oReg->nat_vlr_si == 'C') ? $oReg->saldoinicial * -1 : $oReg->saldoinicial;
                ${$aDadosIC}[$sHash][10] += $oReg->debito;

                if (!empty($oReg->tipovalordeb)) {
                    ${$aDadosIC}[$sHash][11] = $oReg->tipovalordeb;
                }

                ${$aDadosIC}[$sHash][12] += $oReg->credito;

                if (!empty($oReg->tipovalorcred)) {
                    ${$aDadosIC}[$sHash][13] = $oReg->tipovalorcred;
                }

                ${$aDadosIC}[$sHash][14] += ($oReg->nat_vlr_sf == 'C' ? $oReg->saldofinal * -1 : $oReg->saldofinal);
            }

        }

        $aDadosICFinal  = "aDadosIC{$IC}Final";
        $$aDadosICFinal = array();

        foreach (${$aDadosIC} as $obj) {
            $sHash = "";
            for ($ind = 0; $ind <= 6; $ind++) {
                $sHash .= ($obj[$ind] != "_null") ? $obj[$ind] : '';
            }
            $oIC = $obj;
            $oIC[9]  = $obj[7] > 0 ? 'D' : 'C';
            $oIC[7]  = abs($obj[7]);
            $oIC[16] = $oIC[14] > 0 ? 'D' : 'C';
            $oIC[14] = abs($oIC[14]);

            ${$aDadosICFinal}[$sHash] = $oIC;

        }
        return $$aDadosICFinal;
    }

    public function getDadosIC01($iAno, $dataInicio, $dataFim) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
      select estrut as conta,
              CASE
              WHEN db21_tipoinstit IN (6) THEN 10132
              WHEN db21_tipoinstit IN (2) THEN 20231
              ELSE 10131
          END AS po,
        abs(round(substr(fc_planosaldonovo,45,14)::float8,2)::float8) AS saldoinicial,
        'beginning_balance' AS tipovalor_si,
        substr(fc_planosaldonovo,60,1)::varchar(1) AS nat_vlr_si,
        round(substr(fc_movencerramentomsc,1,15)::float8,2)::float8 AS debito,
        CASE
            WHEN round(substr(fc_movencerramentomsc,1,15)::float8,2)::float8 = 0 THEN NULL
            ELSE 'period_change_deb'
        END AS tipovalordeb,
        round(substr(fc_movencerramentomsc,17,54)::float8,2)::float8 AS credito,
        CASE
            WHEN round(substr(fc_movencerramentomsc,17,54)::float8,2)::float8 = 0 THEN NULL
            ELSE 'period_change_cred'
        END AS tipovalorcred,
        abs(round((case when substr(fc_planosaldonovo,60,1)::varchar(1) = 'C' then substr(fc_planosaldonovo,45,14)::float8 * -1 else substr(fc_planosaldonovo,45,14)::float8 end  )  + substr(fc_movencerramentomsc,1,15)::float8 - substr(fc_movencerramentomsc,17,54)::float8,2)::float8) AS saldofinal,
        'ending_balance' AS tipovalor_sf,
         case when round((case when substr(fc_planosaldonovo,60,1)::varchar(1) = 'C' then substr(fc_planosaldonovo,45,14)::float8 * -1 else substr(fc_planosaldonovo,45,14)::float8 end  )  + substr(fc_movencerramentomsc,1,15)::float8 - substr(fc_movencerramentomsc,17,54)::float8,2)::float8 > 0 then 'D' else 'C' end AS nat_vlr_sf
       from
      (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
              db21_tipoinstit,
        c61_reduz,
        c61_codcon,
        c61_codigo,
        r.c61_instit,
        fc_planosaldonovo(".$iAno.", c61_reduz, '".$dataInicio."', '".$dataFim."', false),
        fc_movencerramentomsc('".$dataInicio."', '".$dataFim."', r.c61_reduz)
           from conplanoexe e
       inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
       inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
       inner join db_config ON codigo = r.c61_instit
         left outer join consistema on c60_codsis = c52_codsis
         left join vinculopcaspmsc on (substr(p.c60_estrut,1,9), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
         where {$this->getTipoMatriz()} (c60_infcompmsc is null or c60_infcompmsc = 0 or c60_infcompmsc = 1) and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
       ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0) ";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "null", "null", "null", "null", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(1, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(1);
        }

    }

    public function getDadosIC02($iAno, $dataInicio, $dataFim) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
    select estrut as conta,
        CASE
        WHEN db21_tipoinstit IN (6) THEN 10132
        WHEN db21_tipoinstit IN (2) THEN 20231
        ELSE 10131
        END AS po,
        case when c60_identificadorfinanceiro = 'F' then 1 else 2 end as fp,
      abs(round(substr(fc_planosaldonovo,45,14)::float8,2)::float8) AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_planosaldonovo,60,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_movencerramentomsc,1,15)::float8,2)::float8 AS debito,  
      CASE
          WHEN round(substr(fc_movencerramentomsc,1,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_movencerramentomsc,17,15)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_movencerramentomsc,17,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      abs(round((case when substr(fc_planosaldonovo,60,1)::varchar(1) = 'C' then substr(fc_planosaldonovo,45,14)::float8 * -1 else substr(fc_planosaldonovo,45,14)::float8 end  )  + substr(fc_movencerramentomsc,1,15)::float8 - substr(fc_movencerramentomsc,17,15)::float8,2)::float8) AS saldofinal,
      'ending_balance' AS tipovalor_sf,
       case when round((case when substr(fc_planosaldonovo,60,1)::varchar(1) = 'C' then substr(fc_planosaldonovo,45,14)::float8 * -1 else substr(fc_planosaldonovo,45,14)::float8 end  )  + substr(fc_movencerramentomsc,1,15)::float8 - substr(fc_movencerramentomsc,17,15)::float8,2)::float8 > 0 then 'D' else 'C' end AS nat_vlr_sf,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c61_instit
     from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
            db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit, c60_identificadorfinanceiro,
      fc_planosaldonovo(".$iAno.", c61_reduz, '".$dataInicio."', '".$dataFim."', false),
      fc_movencerramentomsc('".$dataInicio."', '".$dataFim."', r.c61_reduz)
         from conplanoexe e
     inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
     inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
         inner join db_config ON codigo = r.c61_instit
       left outer join consistema on c60_codsis = c52_codsis
       left join vinculopcaspmsc on (substr(p.c60_estrut,1,9), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
       where {$this->getTipoMatriz()} c60_infcompmsc = 2 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
     ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0) ";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fp", "fr", "null", "null", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(2, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(2);
        }

    }

    public function getDadosIC03($iAno, $dataInicio) {

    }

    public function getDadosIC04($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
    select estrut as conta,
        CASE
        WHEN db21_tipoinstit IN (6) THEN 10132
        WHEN db21_tipoinstit IN (2) THEN 20231
        ELSE 10131
        END AS po,
        case when c60_identificadorfinanceiro = 'F' then 1 else 2 end as fp,
        o15_codstn as fr,
      round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
      'ending_balance' AS tipovalor_sf,
        substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c61_instit
      from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
            db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit,
            c60_identificadorfinanceiro,o15_codstn,
            fc_saldocontacorrente($iAno,c19_sequencial,103,$iMes,codigo)
          from conplanoexe e
      inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
      inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
          inner join db_config ON codigo = r.c61_instit
          inner join contacorrentedetalhe on c19_conplanoreduzanousu = c61_anousu and c19_reduz = c61_reduz
        left outer join consistema on c60_codsis = c52_codsis
        left join vinculopcaspmsc on (substr(p.c60_estrut,1,9), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
        left join orctiporec on c19_orctiporec = o15_codigo
        where {$this->getTipoMatriz()} c60_infcompmsc = 4 or c60_infcompmsc is null and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
      ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0) ";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fp", "fr", "null", "null", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(4, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(4);
        }
    }

    public function getDadosIC05($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
    select estrut as conta,
        CASE
        WHEN db21_tipoinstit IN (6) THEN 10132
        WHEN db21_tipoinstit IN (2) THEN 20231
        ELSE 10131
        END AS po,
        o15_codstn as fr,
      round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
      'ending_balance' AS tipovalor_sf,
        substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c61_instit
      from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
            db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit,
            c60_identificadorfinanceiro,o15_codstn,
            fc_saldocontacorrente($iAno,c19_sequencial,103,$iMes,codigo)
          from conplanoexe e
      inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
      inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
          inner join db_config ON codigo = r.c61_instit
          inner join contacorrentedetalhe on c19_conplanoreduzanousu = c61_anousu and c19_reduz = c61_reduz
        left outer join consistema on c60_codsis = c52_codsis
        left join vinculopcaspmsc on (substr(p.c60_estrut,1,9), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
        left join orctiporec on c19_orctiporec = o15_codigo
        where {$this->getTipoMatriz()} c60_infcompmsc = 5 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
      ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fp", "fr", "null", "null", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(5, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(5);
        }
    }

    public function getDadosIC06($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
      select estrut as conta,
          CASE
          WHEN db21_tipoinstit IN (6) THEN 10132
          WHEN db21_tipoinstit IN (2) THEN 20231
          ELSE 10131
          END AS po,
          CASE
            WHEN o15_codtri = '124'
              THEN
                CASE
                  WHEN substr(natreceita,1,6) = '172810' OR substr(natreceita,1,6) = '242810' THEN 15200000
                  ELSE 15100000 END
            ELSE o15_codstn
          END AS fr,
          natreceita AS nr,
        round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
        'beginning_balance' AS tipovalor_si,
        substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
        round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
        CASE
            WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
            ELSE 'period_change_deb'
        END AS tipovalordeb,
        round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
        CASE
            WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
            ELSE 'period_change_cred'
        END AS tipovalorcred,
        round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
        'ending_balance' AS tipovalor_sf,
         substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
        c61_reduz,
        c61_codcon,
        c61_codigo,
        c61_instit
       from
      (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
              db21_tipoinstit,
              o15_codtri,
        c61_reduz,
        c61_codcon,
        c61_codigo,
        r.c61_instit,
              c60_identificadorfinanceiro,o15_codstn,
              CASE
                WHEN substr(c19_estrutural,2,1) = '9' THEN substr(c19_estrutural,4,8)
                ELSE substr(c19_estrutural,2,8)
              END AS natreceita,
              fc_saldocontacorrente($iAno,c19_sequencial,100,$iMes,codigo)
           from conplanoexe e
       inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
       inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
           inner join db_config ON codigo = r.c61_instit
         inner join contacorrentedetalhe on c19_conplanoreduzanousu = c61_anousu and c19_reduz = c61_reduz
         left outer join consistema on c60_codsis = c52_codsis
         left join vinculopcaspmsc on (substr(p.c60_estrut,2,8), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
         left join orctiporec on c19_orctiporec = o15_codigo
         where {$this->getTipoMatriz()} c60_infcompmsc = 6 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
       ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fr", "nr", "null", "null", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(6, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(6);
        }
    }

    public function getDadosIC07EMP($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
    select estrut as conta,
        CASE
        WHEN db21_tipoinstit IN (6) THEN 10132
        WHEN db21_tipoinstit IN (2) THEN 20231
        ELSE 10131
        END AS po,
        funsub AS fs,
        CASE
          WHEN o15_codtri = '103' AND o58_funcao = '04' THEN 14300000
          ELSE o15_codstn
        END AS fr,
        CASE 
          WHEN substr(c60_estrut, 1, 3) = '522' AND natdespesa != '99999999' THEN rpad(substr(natdespesa, 1, 6), 8, '0')
          WHEN substr(c60_estrut, 1, 3) = '622' THEN natdespesa
          ELSE natdespesa
        END AS nd,
        null AS es,
      round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
      'ending_balance' AS tipovalor_sf,
       substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c61_instit
     from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
    case when c211_mscestrut is null then substr(o56_elemento,2,8) else c211_mscestrut end as natdespesa,
      c60_estrut,
      db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit,
      o15_codtri,
      lpad(o58_funcao,2,0) AS o58_funcao,
      c60_identificadorfinanceiro,o15_codstn,lpad(o58_funcao,2,0)||lpad(o58_subfuncao,3,0) as funsub,
      fc_saldocontacorrente($iAno,c19_sequencial,102,$iMes,codigo)
         from conplanoexe e
          inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
          inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
          inner join db_config ON codigo = r.c61_instit
          inner join contacorrentedetalhe on c19_conplanoreduzanousu = c61_anousu and c19_reduz = c61_reduz
          inner join orcdotacao on c19_orcdotacao= o58_coddot and o58_anousu=c19_orcdotacaoanousu
          left join empempenho on e60_numemp=c19_numemp
          left join empelemento on e64_numemp = e60_numemp
          LEFT JOIN orcelemento ON o56_codele=e64_codele AND o56_anousu=e60_anousu
          left join elemdespmsc on (substr(o56_elemento,2,8), o56_anousu) = (c211_elemdespestrut, c211_anousu)
          left outer join consistema on c60_codsis = c52_codsis
          left join vinculopcaspmsc on (substr(p.c60_estrut,2,8), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
          left join orctiporec on c19_orctiporec = o15_codigo
       where {$this->getTipoMatriz()} c19_contacorrente=102 and c60_infcompmsc = 7 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
     ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fs", "fr", "nd", "es", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(7, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(7);
        }
    }

    public function getDadosIC07RSP($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
    select estrut as conta,
        CASE
        WHEN db21_tipoinstit IN (6) THEN 10132
        WHEN db21_tipoinstit IN (2) THEN 20231
        ELSE 10131
        END AS po,
        funsub AS fs,
        CASE
          WHEN o15_codtri = '103' AND o58_funcao = '04' THEN 14300000
          ELSE o15_codstn
        END AS fr,
        CASE 
          WHEN substr(c60_estrut, 1, 3) = '522' AND natdespesa != '99999999' THEN rpad(substr(natdespesa, 1, 6), 8, '0')
          WHEN substr(c60_estrut, 1, 3) = '622' THEN natdespesa
          ELSE natdespesa
        END AS nd,
        null AS es,
      round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
      'ending_balance' AS tipovalor_sf,
       substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c61_instit
     from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
    case when c211_mscestrut is null then substr(c19_estrutural,2,8) else c211_mscestrut end as natdespesa,
      c60_estrut,
      db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit,
      o15_codtri,
      lpad(o58_funcao,2,0) AS o58_funcao,
            c60_identificadorfinanceiro,o15_codstn,lpad(o58_funcao,2,0)||lpad(o58_subfuncao,3,0) as funsub,
            fc_saldocontacorrente($iAno,c19_sequencial,101,$iMes,codigo)
         from conplanoexe e
     inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
     inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
         inner join db_config ON codigo = r.c61_instit
       inner join contacorrentedetalhe on c19_conplanoreduzanousu = c61_anousu and c19_reduz = c61_reduz
       inner join orcdotacao on c19_orcdotacao= o58_coddot and o58_anousu=c19_orcdotacaoanousu
       left join elemdespmsc on  (substr(c19_estrutural,2,8), $iAno) = (c211_elemdespestrut, c211_anousu)
       left outer join consistema on c60_codsis = c52_codsis
       left join vinculopcaspmsc on (substr(p.c60_estrut,2,8), $iAno) = (c210_pcaspestrut, c210_anousu)
       left join orctiporec on c19_orctiporec = o15_codigo
       where {$this->getTipoMatriz()} c19_contacorrente=101 and c60_infcompmsc = 7 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
     ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fs", "fr", "nd", "es", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(7, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(7);
        }
    }

    public function getDadosIC08($iAno, $dataInicio, $dataFim) {

        $iMes = date('m',strtotime($dataInicio));
        $sSQL = "select * from (
    select estrut as conta,
            CASE
            WHEN db21_tipoinstit IN (6) THEN 10132
            WHEN db21_tipoinstit IN (2) THEN 20231
            ELSE 10131
        END AS po,
        case when c60_identificadorfinanceiro = 'F' then 1 else 2 end as fp,
        null as dc,
        o15_codstn AS fr,
      abs(round(substr(fc_planosaldonovo,45,14)::float8,2)::float8) AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_planosaldonovo,60,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_movencerramentomsc,1,15)::float8,2)::float8 AS debito,
      CASE
          WHEN round(substr(fc_movencerramentomsc,1,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_movencerramentomsc,17,54)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_movencerramentomsc,17,54)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      abs(round( (case when substr(fc_planosaldonovo,60,1)::varchar(1) = 'C' then substr(fc_planosaldonovo,45,14)::float8 * -1 else substr(fc_planosaldonovo,45,14)::float8 end  ) + substr(fc_movencerramentomsc,1,15)::float8 - substr(fc_movencerramentomsc,17,54)::float8,2)::float8) AS saldofinal,
      'ending_balance' AS tipovalor_sf,
      case when round((case when substr(fc_planosaldonovo,60,1)::varchar(1) = 'C' then substr(fc_planosaldonovo,45,14)::float8 * -1 else substr(fc_planosaldonovo,45,14)::float8 end  ) + substr(fc_movencerramentomsc,1,15)::float8 - substr(fc_movencerramentomsc,17,54)::float8,2)::float8 > 0 then 'D' else 'C' end AS nat_vlr_sf
      from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,p.c60_identificadorfinanceiro as c60_identificadorfinanceiro,
            db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit,o15_codstn,
      fc_planosaldonovo(".$iAno.", c61_reduz, '".$dataInicio."', '".$dataFim."', false),
      fc_movencerramentomsc('".$dataInicio."', '".$dataFim."', r.c61_reduz)
          from conplanoexe e
      inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
      inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
      inner join orctiporec on o15_codigo = c61_codigo
          inner join db_config ON codigo = r.c61_instit
        left outer join consistema on c60_codsis = c52_codsis
        left join vinculopcaspmsc on (substr(p.c60_estrut,1,9), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
        where {$this->getTipoMatriz()} c60_infcompmsc = 8 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
      ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fp", "dc", "fr", "null", "null", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(8, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(8);
        }
    }
    /**
     * A IC09 atinge tanto empenhos como restos a pagar mas na hora de pegar as informa��es complementares e necess�rio pegar separadamente
     * por isso temos duas fun��es para IC09
     *
     */
    public function getDadosIC09EMP($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = "select * from (
    select estrut as conta,
        CASE
        WHEN db21_tipoinstit IN (6) THEN 10132
        WHEN db21_tipoinstit IN (2) THEN 20231
        ELSE 10131
        END AS po,
        funsub AS fs,
        CASE
          WHEN o15_codtri = '103' AND o58_funcao = '04' THEN 14300000
          ELSE o15_codstn
        END AS fr,
        natdespesa AS nd,
        null AS es,
        e60_anousu as ai,
      round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
      'beginning_balance' AS tipovalor_si,
      substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
      round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_deb'
      END AS tipovalordeb,
      round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
      CASE
          WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
          ELSE 'period_change_cred'
      END AS tipovalorcred,
      round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
      'ending_balance' AS tipovalor_sf,
       substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c61_instit
     from
    (select case when c210_mscestrut is null then substr(p.c60_estrut,1,9) else c210_mscestrut end as estrut,
    case when c211_mscestrut is null then substr(c19_estrutural,2,8) else c211_mscestrut end as natdespesa,
            db21_tipoinstit,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      r.c61_instit, e60_anousu,
      o15_codtri,
      lpad(o58_funcao,2,0) AS o58_funcao,
            p.c60_identificadorfinanceiro,o15_codstn,lpad(o58_funcao,2,0)||lpad(o58_subfuncao,3,0) as funsub,
            fc_saldocontacorrente($iAno,c19_sequencial,102,$iMes,codigo)
         from conplanoexe e
     inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
     inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
         inner join db_config ON codigo = r.c61_instit
       inner join contacorrentedetalhe on c19_conplanoreduzanousu = c61_anousu and c19_reduz = c61_reduz
       inner join orcdotacao on c19_orcdotacao= o58_coddot and o58_anousu=c19_orcdotacaoanousu
       inner join empempenho on c19_numemp=e60_numemp
       left join elemdespmsc on (substr(c19_estrutural,2,8), c60_anousu) = (c211_elemdespestrut, c211_anousu)
       left outer join consistema on c60_codsis = c52_codsis
       left join vinculopcaspmsc on (substr(p.c60_estrut,2,8), p.c60_anousu) = (c210_pcaspestrut, c210_anousu)
       left join orctiporec on o58_codigo = o15_codigo
       where {$this->getTipoMatriz()} c19_contacorrente=102 and c60_infcompmsc = 9 and c62_anousu = ".$iAno." and r.c61_reduz is not null order by p.c60_estrut
     ) as movgeral) as movfinal where (saldoinicial <> 0 or debito <> 0 or credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fs", "fr", "nd", "es", "ai", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(9, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(9);
        }
    }

    /**
     * A IC09 atinge tanto empenhos como restos a pagar mas na hora de pegar as informa��es complementares e necess�rio pegar separadamente
     * por isso temos duas fun��es para IC09
     *
     */
    public function getDadosIC09RSP($iAno, $dataInicio) {

        $iMes = date('m',strtotime($dataInicio));

        $sSQL = " SELECT * FROM
                  ( SELECT estrut AS conta,
                           CASE
                               WHEN db21_tipoinstit IN (6) THEN 10132
                               WHEN db21_tipoinstit IN (2) THEN 20231
                               ELSE 10131
                           END AS po,
                           funsub AS fs,
                           CASE
                               WHEN o15_codtri = '103'
                                    AND o58_funcao = '04' THEN 14300000
                               ELSE o15_codstn
                           END AS fr,
                           natdespesa AS nd,
                           NULL AS es,
                           e60_anousu AS ai,
                           round(substr(fc_saldocontacorrente,113,15)::float8,2)::float8 AS saldoinicial,
                           'beginning_balance' AS tipovalor_si,
                           substr(fc_saldocontacorrente,177,1)::varchar(1) AS nat_vlr_si,
                           round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 AS debito,
                           CASE
                               WHEN round(substr(fc_saldocontacorrente,129,15)::float8,2)::float8 = 0 THEN NULL
                               ELSE 'period_change_deb'
                           END AS tipovalordeb,
                           round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 AS credito,
                           CASE
                               WHEN round(substr(fc_saldocontacorrente,145,15)::float8,2)::float8 = 0 THEN NULL
                               ELSE 'period_change_cred'
                           END AS tipovalorcred,
                           round(substr(fc_saldocontacorrente,161,15)::float8,2)::float8 AS saldofinal,
                           'ending_balance' AS tipovalor_sf,
                           substr(fc_saldocontacorrente,181,1)::varchar(1) AS nat_vlr_sf,
                           c61_reduz,
                           c61_codcon,
                           c61_codigo,
                           c61_instit
                   FROM
                       (SELECT CASE
                                   WHEN c210_mscestrut IS NULL THEN substr(p.c60_estrut,1,9)
                                   ELSE c210_mscestrut
                               END AS estrut,
                               CASE
                                   WHEN si177_naturezadespesa||lpad(si177_subelemento::varchar,2,0) = tb.c211_elemdespestrut THEN tb.c211_mscestrut
                                   WHEN substr(contacorrentedetalhe.c19_estrutural,2,8) = a1.c211_elemdespestrut THEN a1.c211_mscestrut
                                   WHEN elemdespmsc.c211_mscestrut IS NULL AND si177_naturezadespesa IS NOT NULL THEN si177_naturezadespesa||lpad(si177_subelemento::varchar,2,0)
                                   WHEN elemdespmsc.c211_mscestrut IS NULL AND conplanoorcamento.c60_estrut IS NULL THEN substr(c19_estrutural, 2, 8)
                                   WHEN elemdespmsc.c211_mscestrut IS NULL AND c19_estrutural IS NULL THEN substr(conplanoorcamento.c60_estrut, 2, 8)
                                   WHEN elemdespmsc.c211_mscestrut IS NULL AND c19_estrutural IS NOT NULL THEN substr(conplanoorcamento.c60_estrut, 2, 8)
                                   WHEN elemdespmsc.c211_mscestrut IS NULL AND conplanoorcamento.c60_estrut IS NOT NULL THEN substr(c19_estrutural, 2, 8)
                                   ELSE elemdespmsc.c211_mscestrut
                               END AS natdespesa,
                               db21_tipoinstit,
                               c61_reduz,
                               c61_codcon,
                               c61_codigo,
                               o15_codtri,
                               lpad(o58_funcao,2,0) AS o58_funcao,
                               r.c61_instit,
                               e60_anousu,
                               p.c60_identificadorfinanceiro,
                               o15_codstn,
                               lpad(o58_funcao,2,0)||lpad(o58_subfuncao,3,0) AS funsub,
                               fc_saldocontacorrente($iAno,c19_sequencial,106,$iMes,codigo)
                        FROM conplanoexe e
                        INNER JOIN conplanoreduz r ON r.c61_anousu = c62_anousu AND r.c61_reduz = c62_reduz
                        INNER JOIN conplano p ON r.c61_codcon = c60_codcon AND r.c61_anousu = c60_anousu
                        INNER JOIN db_config ON codigo = r.c61_instit
                        INNER JOIN contacorrentedetalhe ON c19_conplanoreduzanousu = c61_anousu AND c19_reduz = c61_reduz
                        INNER JOIN empempenho ON c19_numemp=e60_numemp
                        INNER JOIN orcdotacao ON e60_coddot= o58_coddot AND o58_anousu=e60_anousu
                        INNER JOIN empelemento ON e64_numemp=e60_numemp
                        LEFT JOIN dotacaorpsicom ON e60_numemp = si177_numemp
                        LEFT JOIN conplanoorcamento ON conplanoorcamento.c60_codcon=e64_codele AND conplanoorcamento.c60_anousu=e60_anousu
                        LEFT JOIN elemdespmsc ON (substr(conplanoorcamento.c60_estrut,2,8), $iAno) = (elemdespmsc.c211_elemdespestrut, elemdespmsc.c211_anousu)
                        LEFT JOIN elemdespmsc tb ON (si177_naturezadespesa||lpad(si177_subelemento::varchar,2,0), $iAno) = (tb.c211_elemdespestrut, tb.c211_anousu) 
                        LEFT JOIN elemdespmsc a1 ON (substr(contacorrentedetalhe.c19_estrutural,2,8), $iAno) = (a1.c211_elemdespestrut, a1.c211_anousu)
                        LEFT OUTER JOIN consistema ON p.c60_codsis = c52_codsis
                        LEFT JOIN vinculopcaspmsc ON (substr(c19_estrutural,2,8), $iAno) = (c210_pcaspestrut, c210_anousu)
                        LEFT JOIN orctiporec ON o58_codigo = o15_codigo
                        WHERE {$this->getTipoMatriz()} c19_contacorrente=106
                            AND p.c60_infcompmsc = 9
                            AND c62_anousu = ".$iAno."
                            AND r.c61_reduz IS NOT NULL
                        ORDER BY p.c60_estrut ) AS movgeral) AS movfinal
              WHERE (saldoinicial <> 0 OR debito <> 0 OR credito <> 0)";

        $rsResult = db_query($sSQL);

        $aCampos  = array("conta", "po", "fs", "fr", "nd", "es", "ai", "saldoinicial", "tipovalor_si", "nat_vlr_si", "debito", "tipovalordeb", "credito", "tipovalorcred", "saldofinal", "tipovalor_sf", "nat_vlr_sf");

        if ($rsResult) {
            return $this->getDadosIC(9, $aCampos, $rsResult);
        } else {
            $this->setErroSQL(9);
        }
    }

    public function getRegistrosRelatorio($aRegis) {

        ksort($aRegis);

        $keys = array_keys($aRegis);
        $last_key = array_pop($keys);

        foreach ($aRegis as $key => $value) {

            if ($iConta != $value[0]) {

                if (!empty($iConta)) {
                    $aRegistros[$iConta] = $oNovoResgistro;
                }
                $oNovoResgistro                     = new stdClass;
                $oNovoResgistro->conta = $iConta    = $value[0];
                $oNovoResgistro->beginning_balance  = 0;
                $oNovoResgistro->period_change_deb  = 0;
                $oNovoResgistro->period_change_cred = 0;
                $oNovoResgistro->ending_balance     = 0;

            }

            if (!empty($value[7])) {
                $oNovoResgistro->beginning_balance  += $value[9] == 'D' ? $value[7] : $value[7] * -1;
            }
            if (!empty($value[10])) {
                $oNovoResgistro->period_change_deb  += $value[10];
            }
            if (!empty($value[12])) {
                $oNovoResgistro->period_change_cred += $value[12] * -1;
            }
            if (!empty($value[14])) {
                $oNovoResgistro->ending_balance     += $value[16] == 'D' ? $value[14] : $value[14] * -1;
            }

            if ($key == $last_key) {
                $aRegistros[$iConta] = $oNovoResgistro;
            }

        }

        return $aRegistros;

    }
}
