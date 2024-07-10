<?php

namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

/**
 * Tipos de Formul�rios do eSocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Model\Formulario
 * @author   Andrio Costa - andrio.costa@dbseller.com.br
 */
class Tipo
{
    /**
     * Constantes referente aos formul�rios existentes no e-cidade
     */
    const EMPREGADOR = 1;
    const RUBRICA = 2;
    const SERVIDOR = 3;
    const LOTACAO_TRIBUTARIA = 4;
    const CARGO = 5;
    const CARREIRA = 6;
    const FUNCAO = 7;
    const HORARIO = 8;
    const AMBIENTE = 9;
    const PROCESSOSAJ = 10;
    const PORTUARIO = 11;
    const REMUNERACAO_TRABALHADOR = 12;
    const REMUNERACAO_SERVIDOR = 13;
    const BENEFICIOS_ENTESPUBLICOS = 14;
    const PAGAMENTOS_RENDIMENTOS = 15;
    const REABERTURA_EVENTOS = 21;
    const FECHAMENTO_EVENTOS = 22;
    const REGISTRO_PRELIMINAR = 24;
    const CADASTRAMENTO_INICIAL = 37;
    const ESTABELECIMENTOS = 26;
    const ALTERACAODEDADOS = 38;
    const ALTERACAO_CONTRATO = 39;
    const TSV_INICIO = 44;
    const TSV_ALT_CONTR = 45;
    const TSV_TERMINO = 46;
    const CD_BENEF_IN = 47;
    const AFASTAMENTO_TEMPORARIO = 40;
    const CADASTRO_BENEFICIO = 49;
    const DESLIGAMENTO = 25;
    const EXCLUSAO_EVENTOS = 53;


    /**
     * Constante referente aos tipos de formata��o aplicada em cima dos dados do formul�rio
     */
    const S1000 = 'S1000';
    const S1005 = 'S1005';
    const S1010 = 'S1010';
    const S1020 = 'S1020';
    const S1030 = 'S1030';
    const S1035 = 'S1035';
    const S1040 = 'S1040';
    const S1050 = 'S1050';
    const S1060 = 'S1060';
    const S1070 = 'S1070';
    const S1080 = 'S1080';
    const S1200 = 'S1200';
    const S1202 = 'S1202';
    const S1207 = 'S1207';
    const S1210 = 'S1210';
    const S1250 = 'S1250';
    const S1260 = 'S1260';
    const S1270 = 'S1270';
    const S1280 = 'S1280';
    const S1295 = 'S1295';
    const S1298 = 'S1298';
    const S1299 = 'S1299';
    const S1300 = 'S1300';
    const S2190 = 'S2190';
    const S2200 = 'S2200';
    const S2205 = 'S2205';
    const S2206 = 'S2206';
    const S2210 = 'S2210';
    const S2220 = 'S2220';
    const S2221 = 'S2221';
    const S2230 = 'S2230';
    const S2231 = 'S2231';
    const S2240 = 'S2240';
    const S2245 = 'S2245';
    const S2250 = 'S2250';
    const S2260 = 'S2260';
    const S2298 = 'S2298';
    const S2299 = 'S2299';
    const S2300 = 'S2300';
    const S2306 = 'S2306';
    const S2399 = 'S2399';
    const S2400 = 'S2400';
    const S2410 = 'S2410';
    const S3000 = 'S3000';
    const S5001 = 'S5001';
    const S5002 = 'S5002';
    const S5003 = 'S5003';
    const S5011 = 'S5011';
    const S5012 = 'S5012';
    const S5013 = 'S5013';

    public static function getArrVinculacaoTipo()
    {
        return array(
            1 => 'S1000',
            2 => 'S1010',
            4 => 'S1020',
            5 => 'S1030',
            6 => 'S1035',
            7 => 'S1040',
            8 => 'S1050',
            9 => 'S1060',
            10 => 'S1070',
            11 => 'S1080',
            12 => 'S1200',
            13 => 'S1202',
            14 => 'S1207',
            15 => 'S1210',
            16 => 'S1250',
            17 => 'S1260',
            18 => 'S1270',
            19 => 'S1280',
            20 => 'S1295',
            21 => 'S1298',
            22 => 'S1299',
            23 => 'S1300',
            24 => 'S2190',
            25 => 'S2299',
            26 => 'S1005',
            28 => 'S2210',
            29 => 'S2220',
            30 => 'S2221',
            32 => 'S2240',
            33 => 'S2245',
            34 => 'S2250',
            35 => 'S2260',
            36 => 'S2298',
            37 => 'S2200',
            38 => 'S2205',
            39 => 'S2206',
            40 => 'S2230',
            41 => 'S2231',
            42 => 'S3000',
            43 => 'S5001',
            44 => 'S2300',
            45 => 'S2306',
            46 => 'S2399',
            47 => 'S2400',
            48 => 'S2405',
            49 => 'S2410',
            50 => 'S2416',
            51 => 'S2418',
            52 => 'S2420',
            53 => 'S3000',
            54 => 'S5001',
            55 => 'S5002',
            56 => 'S5003',
            57 => 'S5011',
            58 => 'S5013'
        );
    }

    /**
     * retorna Array para vincular tipo formulario a tipo de formatacao
     */
    public static function getVinculacaoTipo($iTipoFormulario)
    {
        $aVinculacaoTipo = self::getArrVinculacaoTipo();
        return $aVinculacaoTipo[$iTipoFormulario];
    }

    public static function getTipoFormulario($sEvento)
    {
        return array_search($sEvento, self::getArrVinculacaoTipo());
    }

    public static function getConst($const)
    {
        return constant('self::' . $const);
    }

    public static function getTipo($iTipoFormulario)
    {
        return self::getConst(self::getVinculacaoTipo($iTipoFormulario));
    }
}
