<?php

class DeParaRecurso
{
    private $dePara;
    private $deParaAnterior;
    private $ano;

    public function __construct()
    {
        $this->setAno();
        $this->setDePara();
        $this->setDeParaAnterior();
    }

    public function getDePara($de)
    {
        $de = $de == 1 ? 100 : $de;
        $iPrimeiroDigito = strlen(ltrim($de,0)) == 3 ? substr(ltrim($de,0),0,1) : substr($de, 0, 1);
        $de = strlen(ltrim($de,0)) == 3 ? substr(ltrim($de,0),1,2) : substr($de, 1, 7);
        return (array_key_exists($de, $this->dePara)) ? $iPrimeiroDigito . $this->dePara[$de] : $iPrimeiroDigito . $de;
    }

    public function getDeParaAnterior($de)
    {
        $iPrimeiroDigito = strlen(ltrim($de,0)) == 3 ? substr(ltrim($de,0),0,1) : substr($de, 0, 1);
        $de = strlen(ltrim($de,0)) == 3 ? substr(ltrim($de,0),1,2) : substr($de, 1, 7);
        return (array_key_exists($de, $this->deParaAnterior)) ? $iPrimeiroDigito . $this->deParaAnterior[$de] : $iPrimeiroDigito . $de;
    }
    

    public function setDePara()
    {
        $this->dePara = array(
            "00" => "5000000",
            "01" => "5000001",
            "02" => "5000002",
            "03" => "8000000",
            "04" => "8010000",
            "05" => "8020000",
            "06" => "5760010",
            "07" => "5440000",
            "08" => "7080000",
            "12" => "6590020",
            "13" => "5990030",
            "16" => "7500000",
            "17" => "7510000",
            "18" => "5400007",
            "19" => "5400000",
            "20" => "5760000",
            "21" => "6220000",
            "22" => "5700000",
            "23" => "6310000",
            "24" => "7000000",
            "29" => "6600000",
            "30" => "8990040",
            "31" => "7590050",
            "32" => "6040000",
            "33" => "7150000",
            "34" => "7160000",
            "35" => "7170000",
            "36" => "7180000",
            "42" => "6650000",
            "43" => "5510000",
            "44" => "5520000",
            "45" => "5530000",
            "46" => "5690000",
            "47" => "5500000",
            "48" => "6000000",
            "49" => "6000000",
            "50" => "6000000",
            "51" => "6000000",
            "52" => "6000000",
            "53" => "6010000",
            "54" => "6590000",
            "55" => "6210000",
            "56" => "6610000",
            "57" => "7520000",
            "58" => "8990060",
            "59" => "6000000",
            "60" => ($this->ano > 2023) ? "7210000" : "7040000",
            "61" => "7070000",
            "62" => "7490120",
            "63" => "7130070",
            "64" => "7060000",
            "65" => "7490000",
            "66" => "5420007",
            "67" => "5420000",
            "68" => "7100100",
            "69" => "7100000",
            "70" => "5010000",
            "71" => "5710000",
            "72" => "5720000",
            "73" => "5750000",
            "74" => "5740000",
            "75" => "5730000",
            "76" => "6320000",
            "77" => "6330000",
            "78" => "6360000",
            "79" => "6340000",
            "80" => "6350000",
            "81" => "7010000",
            "82" => "7020000",
            "83" => "7030000",
            "84" => "7090000",
            "85" => "7530000",
            "86" => ($this->ano > 2023) ? "7200000" :"7040000",
            "87" => "7050000",
            "88" => "5000000",
            "89" => "5000000",
            "90" => "7540000",
            "91" => "7540000",
            "92" => "7550000",
            "93" => "8990000",
            "7040000" => ($this->ano > 2023) ? "7200000" : "7040000"
        );
    }

    public function setDeParaAnterior()
    {
        $this->deParaAnterior = array(
             "5000000" => "00",
             "5000001" => "01",
             "5000002" => "02",
             "8000000" => "03",
             "8010000" => "04",
             "8020000" => "05",
             "5760010" => "06",
             "5440000" => "07",
             "7080000" => "08",
             "6590020" => "12",
             "5990030" => "13",
             "7500000" => "16",
             "7510000" => "17",
             "5400007" => "18",
             "5400000" => "19",
             "5760000" => "20",
             "6220000" => "21",
             "5700000" => "22",
             "6310000" => "23",
             "7000000" => "24",
             "6600000" => "29",
             "8990040" => "30",
             "7590050" => "31",
             "6040000" => "32",
             "7150000" => "33",
             "7160000" => "34",
             "7170000" => "35",
             "7180000" => "36",
             "6650000" => "42",
             "5510000" => "43",
             "5520000" => "44",
             "5530000" => "45",
             "5690000" => "46",
             "5500000" => "47",
             "6000000" => "48",
             "6000000" => "49",
             "6000000" => "50",
             "6000000" => "51",
             "6000000" => "52",
             "6010000" => "53",
             "6590000" => "54",
             "6210000" => "55",
             "6610000" => "56",
             "7520000" => "57",
             "8990060" => "58",
             "6000000" => "59",
             "7040000" => "60",
             "7070000" => "61",
             "7490120" => "62",
             "7130070" => "63",
             "7060000" => "64",
             "7490000" => "65",
             "5420007" => "66",
             "5420000" => "67",
             "7100100" => "68",
             "7100000" => "69",
             "5010000" => "70",
             "5710000" => "71",
             "5720000" => "72",
             "5750000" => "73",
             "5740000" => "74",
             "5730000" => "75",
             "6320000" => "76",
             "6330000" => "77",
             "6360000" => "78",
             "6340000" => "79",
             "6350000" => "80",
             "7010000" => "81",
             "7020000" => "82",
             "7030000" => "83",
             "7090000" => "84",
             "7530000" => "85",
             "7040000" => "86",
             "7050000" => "87",
             "7540000" => "90",
             "7540000" => "91",
             "7550000" => "92",
             "8990000" => "93",
        );
    }
    public function setAno()
    {
        $this->ano = db_getsession('DB_anousu');
    }
}
