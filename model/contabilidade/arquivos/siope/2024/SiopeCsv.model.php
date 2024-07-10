<?php

class SiopeCsv extends Siope {

    //@var String
    protected $sArquivo;

    //@var String
    protected $sDelim = ";";

    //@var String
    protected $_arquivo;

    //@var String
    protected $sLinha;

    public function gerarArquivoCSV(array $aDados, $tipo = null) {
        
        //Despesa
        if ($tipo == 1) {

            $this->sArquivo = $this->getNomeArquivo();
            $this->abreArquivo();

            foreach ($aDados as $oDespesa) {

                $aSiope[0] = 'V';
                $aSiope[1] = '1';
                $aSiope[2] = $oDespesa->cod_planilha;
                $aSiope[3] = $this->getElementoDespesaFormat($oDespesa->elemento_siope);
                $aSiope[4] = $oDespesa->descricao_siope;                
                $aSiope[5] = number_format($oDespesa->dot_atualizada, 2, ',', '');
                $aSiope[6] = number_format($oDespesa->empenhado, 2, ',', '');
                $aSiope[7] = number_format($oDespesa->liquidado, 2, ',', '');
                $aSiope[8] = number_format($oDespesa->pagamento, 2, ',', '');
                $aSiope[9] = number_format($oDespesa->rp_processado, 2, ',', '');
                $aSiope[10] = number_format($oDespesa->rp_nprocessado, 2, ',', '');

                $this->sLinha = $aSiope;
                $this->adicionaLinha();

            }

            $this->fechaArquivo();

        } elseif ($tipo == 2) {
            //Receita

            $this->sArquivo = $this->getNomeArquivo();
            $this->abreArquivo();
           
            foreach ($aDados as $oReceita) {
              
                if (!($oReceita['prev_atualizada'] == 0 && $oReceita['rec_realizada'] == 0  && $oReceita['ded_fundeb'] == 0
                        && $oReceita['outras_ded'] == 0 && $oReceita['intra'] == 0)) {
         
                    $aSiope[0] = 'V';
                    $aSiope[1] = '1';
                    $aSiope[2] = '1';
                    $aSiope[3] = $this->getElementoReceitaFormat($oReceita['natureza']);
                    $aSiope[4] = $oReceita['descricao'];
                    $aSiope[5] = number_format($oReceita['prev_atualizada'], 2, ',', '');
                    $aSiope[6] = number_format($oReceita['rec_realizada'], 2, ',', '');
                    $aSiope[7] = number_format($oReceita['ded_fundeb'], 2, ',', '');
                    $aSiope[8] = number_format($oReceita['outras_ded'], 2, ',', '');
                    $aSiope[9] = number_format($oReceita['intra'], 2, ',', '');

                    $this->sLinha = $aSiope;
                    $this->adicionaLinha();

                }

            }

            $this->fechaArquivo();

        } elseif ($tipo == 3) {
            //Linhas despesa fundeb
            
            $this->sArquivo = $this->getNomeArquivo();
            $this->reAbreArquivo();

            foreach ($aDados as $iCodLinha => $value) {

                $aSiope[0] = 'V';
                $aSiope[1] = '1';
                $aSiope[2] = '1705';
                $aSiope[3] = $iCodLinha;
                $aSiope[4] = $value->descricao;
                $aSiope[5] =  number_format($value->empenhado, 2, ',', '');
                $aSiope[6] =  number_format($value->liquidado, 2, ',', '');
                $aSiope[7] =  number_format($value->pagamento, 2, ',', '');
                $aSiope[8] =  number_format($value->rp_nprocessado, 2, ',', '');
                $aSiope[9] =  number_format($value->rp_nprocscx, 2, ',', ''); 

                $this->sLinha = $aSiope;
                $this->adicionaLinha();

            }

            $this->fechaArquivo();

        }

    }

    function abreArquivo() {
        $this->_arquivo = fopen($this->sArquivo . '.csv', "w");
    }

    function reAbreArquivo() {
        $this->_arquivo = fopen($this->sArquivo . '.csv', "a");
    }

    function fechaArquivo() {
        fclose($this->_arquivo);
    }

    function adicionaLinha() {
        $aLinha = array();

        foreach ($this->sLinha as $sLinha) {
            $aLinha[] = $sLinha;
        }

        $sLinha = implode(";", $aLinha);
        fputs($this->_arquivo, $sLinha);
        fputs($this->_arquivo, "\r\n");

    }

}
