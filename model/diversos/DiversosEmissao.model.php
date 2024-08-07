<?php
/**
 * Class responsavel pela emiss�o do relat�rio de importa��es para diversos
 *
 * @package  Diversos
 * @author    everton          <everton.heckler@dbseller.com.br>
 *
 * @revision  $Author  : $
 * @version  $Revision: 1.1 $
 */
class DiversosEmissao {

	/**
	 * Cgm utilizado na pesquisa
	 * @var integer
	 */
	protected $iCgm;

	/**
	 * Matricula utilizada na pesquisa
	 * @var integer
	 */
	protected $iMatricula;

	/**
	 * Numpre utilizada na pesquisa
	 * @var integer
	 */
	protected $iNumpre;

	/**
	 * Data Inicial utilizada na pesquisa
	 * @var date
	 */
	protected $dDataInicial;

	/**
	 * Data Final utilizada na pesquisa
	 * @var date
	 */
	protected $dDataFinal;

	/**
	 * Tipo utilizada na pesquisa
	 * Analitico/Sintetico
	 * @var integer
	 */
	protected $sTipo;

	/**
	 * Origem utilizada na pesquisa
	 * IPTU/Agua
	 * @var string
	 */
	protected $sOrigem;

	/**
	 * formato utilizado para gera�ao do relat�rio
	 * PDF/CSV
	 * @var string
	 */
	protected $sFormato;

	/**
	 * objeto contendo os dados retornados do sql
	 */
	protected $oDadosRetorno;

	/**
	 * Codigo do Diverso Importado
	 * @var integer
	 */
	protected $iCodDiverso;

	/**
	 * Define o CGM
	 * @param integer $iCgm
	 */
	public function setCgm($iCgm) {

		$this->iCgm = $iCgm;
	}

	/**
	 * Define a Matricula
	 * @param integer $iMatricula
	 */
	public function setMatricula($iMatricula) {

		$this->iMatricula = $iMatricula;
	}

	/**
	 * Define o Numpre
	 * @param integer $iNumpre
	 */
	public function setNumpre($iNumpre) {

		$this->iNumpre = $iNumpre;
	}

	/**
	 * Define a Data Inicial
	 * @param date $dDataInicial
	 */
	public function setDataInicial($dDataInicial) {

		$this->dDataInicial = $dDataInicial;
	}

	/**
	 * Define a Data Final
	 * @param date $dDataFinal
	 */
	public function setDataFinal($dDataFinal) {

		$this->dDataFinal = $dDataFinal;
	}

	/**
	 * Define Tipo de Relat�rio
	 * Analitico/Sint�tico
	 * @param string $sTipo
	 */
	public function setTipo($sTipo) {

		if (empty($sTipo)) {
			throw new BusinessException('N�o definido o tipo de Relat�rio.');
		}

		$this->sTipo = $sTipo;
	}

	/**
	 * Define a Origem
	 * IPTU/AGUA
	 * @param string $sOrigem
	 */
	public function setOrigem($sOrigem) {

		if (empty($sOrigem)) {
			throw new BusinessException('N�o definido a origem do Relat�rio.');
		}

		$this->sOrigem = $sOrigem;
	}

	/**
	 * Define o Formato para gera��o
	 * PDF/CSV
	 * @param string $sFormato
	 */
	public function setFormato($sFormato) {

		if (empty($sFormato)) {
			throw new BusinessException('N�o definido o formato do Relat�rio.');
		}

		$this->sFormato = $sFormato;
	}

	/**
	 * Define o codigo do diverso importado
	 * @param integer $iCodDiverso
	 */
	public function setCodDiverso($iCodDiverso) {

		if (empty($iCodDiverso)) {
			throw new BusinessException('C�digo Diverso n�o informado.');
		}

		$this->iCodDiverso = $iCodDiverso;
	}

	/**
	 * Define os dados do retorno do sql
	 * @param array $aDadosRetorno
	 */
	public function setDadosRetorno($oDadosRetorno) {

		if (empty($oDadosRetorno)) {
			throw new BusinessException('Nenhum Registro Encontrado.');
		}

		$this->oDadosRetorno = $oDadosRetorno;
	}

	/**
	 *  Construtor da classe
	 *
	 * @param integer DiversosEmissao
	 */
	function __construct($iCodDiverso) {

		if ( !empty($iCodDiverso) ) {
			$this->setCodDiverso($iCodDiverso);
		}
	}

	/**
	 * monta objeto com o retorno do sql
	 */
	protected function objetoRetorno() {
		 
		$oWherePesquisa = new stdClass();

		$oWherePesquisa->iCgm         = $this->iCgm;
		$oWherePesquisa->iMatricula   = $this->iMatricula;
		$oWherePesquisa->iNumpre      = $this->iNumpre;
		$oWherePesquisa->dDataInicial = $this->dDataInicial;
		$oWherePesquisa->dDataFinal   = $this->dDataFinal;
		$oWherePesquisa->sTipo        = $this->sTipo;
		$oWherePesquisa->sOrigem      = $this->sOrigem;
		$oWherePesquisa->iCodDiverso  = $this->iCodDiverso;

    
		$oDaoDiverImporta = db_utils::getDao('diverimporta');
		$sSqlRelatorio    = $oDaoDiverImporta->sql_query_relatorio_importacao($oWherePesquisa);
		
		/*$rsDadosRelatorio = $oDaoDiverImporta->sql_record($sSqlRelatorio);
 
		if ($oDaoDiverImporta->numrows > 0) {

			$aDadosRelatorio = db_utils::getCollectionByRecord($rsDadosRelatorio, true);
*/
			$aDadosRetorno = Array();

			//foreach($aDadosRelatorio as $oDadosRelatorio) {

				$oDadosRetorno = new stdClass();
				$oDadosRetorno->codimportacao   = "1234";
				$oDadosRetorno->dv11_data       = '2012-11-01';
				$oDadosRetorno->dv11_hora       = '11:00';
				$oDadosRetorno->login           = 'login';
				$oDadosRetorno->dv05_numcgm     = '1234';
				$oDadosRetorno->z01_nome        = "nome"   ;
				$oDadosRetorno->matricula       = "43121"  ;
				$oDadosRetorno->observacao      = "observacao" ;
				$oDadosRetorno->aRegistros      = array();
				$aDadosRetorno['1234'] = $oDadosRetorno;

			//}
			//foreach($aDadosRelatorio as $oDadosRelatorio) {
				$oRegistros    = new stdClass();

				$oRegistros->tipoprocedencia    = "tipopro";
				$oRegistros->numpreantigo       = "5555555";
				$oRegistros->numparantigo       = "99";
				$oRegistros->receitaantigo      = "123";
				$oRegistros->procedencia        = "procedencia";
				$oRegistros->numprenovo         = "666666";
				$oRegistros->numparnovo         = "88";
				$oRegistros->dv05_vlrhis        = "100.00";
				$oRegistros->dv05_valor         = "90.00";
				$oRegistros->juros              = "";
				$oRegistros->multa              = "";
				$oRegistros->total              = "";
				$oRegistros->descrreceitaantiga = 'receitaantiga';

				$aDadosRetorno['1234']->aRegistros[] = $oRegistros;

			//}
		//}
		return $aDadosRetorno;
	}

	/**
	 * Gera��o do CSV
	 */
	protected function gerarCSV(){

		$aLinhas = array();
		$oCabecalho = new stdClass();
		$sArquivo   = '/tmp/relatorio_faixa_salarial_'. date('Y-m-d_H:i') . '_' .'1';
		//$sArquivo   = 'tmp/relatorio_faixa_salarial_'. date('Y-m-d_H:i') . '_' . db_getsession('DB_login');
		$fArquivo = fopen($sArquivo, "w");
		
		
		$oCabecalho->sDatahora   = "Data-Hora";
		$oCabecalho->sLogin      = "Login";
		$oCabecalho->CGM         = "CGM";
		$oCabecalho->nome        = "Nome";
		$oCabecalho->Matr�cula   = "Matricula";
		$oCabecalho->Observa��es = "Observa��es";
		$oCabecalho->Tipo        = "Tipo";
		$oCabecalho->Numpre      = "Numpre de origem";
		$oCabecalho->Parcela     = "Parcela de origem";
		$oCabecalho->Receita     = "Receita de origem";
		$oCabecalho->Procedencia = "Procedencia";
		$oCabecalho->Numpre      = "Numpre destino";
		$oCabecalho->Parcela     = "Parcela destino";
		$oCabecalho->Vlrhist     = "Valor Hist";
		$oCabecalho->Vlrcorr     = "Valor Corre";
		$oCabecalho->Juros       = "Juros";
		$oCabecalho->Multa       = "Multa";
		$oCabecalho->Total       = "Total";
		
		$aLinhas[] = $oCabecalho;
		
		foreach ($this->objetoRetorno() as $oDadosRelatorio) {
			
			foreach ($oDadosRelatorio->aRegistros as $oRegistro) {
				
				$oConteudo->sDatahora   = $oDadosRelatorio->dv11_data ."-". $oDadosRelatorio->dv11_hora;
				$oConteudo->sLogin      = $oDadosRelatorio->login;
				$oConteudo->CGM         = $oDadosRelatorio->dv05_numcgm;
				$oConteudo->nome        = $oDadosRelatorio->z01_nome;
				$oConteudo->Matr�cula   = $oDadosRelatorio->matricula;
				$oConteudo->Observa��es = $oDadosRelatorio->observacao;
				$oConteudo->Tipo        = "";
				$oConteudo->Numpre      = $oRegistro->numpreantigo;
				$oConteudo->Parcela     = $oRegistro->numparantigo;
				$oConteudo->Receita     = $oRegistro->receitaantigo;
				$oConteudo->Procedencia = $oRegistro->tipoprocedencia;
				$oConteudo->Numpre      = $oRegistro->numprenovo;
				$oConteudo->Parcela     = $oRegistro->numparnovo;
				$oConteudo->Vlrhist     = $oRegistro->dv05_vlrhis;
				$oConteudo->Vlrcorr     = $oRegistro->dv05_valor;
				$oConteudo->Juros       = $oRegistro->juros;
				$oConteudo->Multa       = $oRegistro->multa;
				$oConteudo->Total       = $oRegistro->total;
				
				$aLinhas[] = $oConteudo;
			}
		}
		foreach ($aLinhas as $oLinha) {
			
			fputcsv($fArquivo, (array)$oLinha, ";");
		}
		
		fclose($fArquivo);

	}

	/**
	 * Gera��o do PDF
	 */
	protected function gerarPDF($aTipoRel) {
		 
		global $head1, $head2, $head3, $head4, $head5, $head6;
		
		$head1 = "Relat�rio de Importa��o para Diversos";
		$head2 = 'Filtros Utilizados:';
		
		if (!empty($this->iCgm)) {
			$head3 = 'CGM: '.$this->iCgm;
		}
		
		if (!empty($this->iMatricula)) {
			$head4 = 'Matricula: '.$this->iMatricula;
		}
		
		if (!empty($this->iNumpre)) {
			$head5 = 'Numpre: '.$this->iNumpre;
		}
		
		if (!empty($this->dDataInicial) and !empty($this->dDataFinal)) {
			$head6 = 'Periodo de: '.$this->dDataInicial . ' at� ' . $this->dDataFinal;
		}
		
		if (!empty($this->dDataInicial) and empty($this->dDataFinal)) {
			$head6 = 'Data Inicial: '.$this->dDataInicial;
		}
		
		if (empty($this->dDataInicial) and !empty($this->dDataFinal)) {
			$head6 = 'Data Final: '.$this->dDataFinal;
		}
		
		
		$oPdf = new PDF();

		$oPdf->Open();
		$oPdf->AliasNbPages();
		$oPdf->setfillcolor(235);
		
		$troca  = 1;
		$alt    = 4;
		$p      = 0;

		$iCodImpotacao = "";
		$total         = 0;
		$totalog       = 0;

		$fTotalValorHist       = 0;
		$fTotalValor           = 0;
		$fTotalValorJuro       = 0;
		$fTotalValorMulta      = 0;
		$fTotalValorTotal      = 0;
		$fGeralTotalValorHist  = 0;
		$fGeralTotalValor      = 0;
		$fGeralTotalValorJuro  = 0;
		$fGeralTotalValorMulta = 0;
		$fGeralTotalValorTotal = 0;
    
		foreach($this->oDadosRetorno as $oDadosRelatorio ) {
				
			if ($aTipoRel == "Analitico") {
				
				if ($iCodImpotacao != $oDadosRelatorio ->codimportacao) {

					if ($iCodImpotacao != "") {
						 
						$oPdf->setfont('arial', 'b', 8);
						$oPdf->cell(125, $alt, 'TOTAL: '         , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, $fTotalValorHist  , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, $fTotalValor      , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, $fTotalValorJuro  , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, $fTotalValorMulta , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, $fTotalValorTotal , "T", 1, "R", 1);

						$oPdf->ln();
						$total = 0;

						$fTotalValorHist  = 0;
						$fTotalValor      = 0;
						$fTotalValorJuro  = 0;
						$fTotalValorMulta = 0;
						$fTotalValorTotal = 0;
					}

					if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ) {

						$oPdf->addpage("L");
						$oPdf->setrightmargin(0.5);
						$troca = 0;
					}

					$oPdf->setfont('arial', 'b', 8);
					$oPdf->cell(35,  $alt, 'Data - Hora', 1, 0, "C", 1);
					$oPdf->cell(30,  $alt, 'Login'      , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'CGM'        , 1, 0, "C", 1);
					$oPdf->cell(60,  $alt, 'Nome'       , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'Matricula'  , 1, 0, "C", 1);
					$oPdf->cell(110, $alt, 'Observa��o' , 1, 1, "C", 1);

					$oPdf->setfont('arial', '', 8);
					$oPdf->cell(35,  $alt, "{$oDadosRelatorio ->dv11_data} - {$oDadosRelatorio ->dv11_hora}"   , 0, 0, "C", 0);
					$oPdf->cell(30,  $alt, $oDadosRelatorio ->login       , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->dv05_numcgm , 0, 0, "C", 0);
					$oPdf->cell(60,  $alt, $oDadosRelatorio ->z01_nome    , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->matricula   , 0, 0, "C", 0);
					$oPdf->cell(110, $alt, $oDadosRelatorio ->observacao  , 0, 1, "L", 0);

					$p             = 0;
					$iCodImpotacao = $oDadosRelatorio ->codimportacao;
					$totalog++;
				}

				if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){

					$oPdf->addpage("L");
					$oPdf->setrightmargin(0.5);
					 
					$oPdf->setfont('arial', 'b', 8);
					$oPdf->cell(35,  $alt, 'Data - Hora', 1, 0, "C", 1);
					$oPdf->cell(30,  $alt, 'Login'      , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'CGM'        , 1, 0, "C", 1);
					$oPdf->cell(60,  $alt, 'Nome'       , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'Matricula'  , 1, 0, "C", 1);
					$oPdf->cell(110, $alt, 'Observa��o' , 1, 1, "C", 1);
					 
					$oPdf->setfont('arial', '', 8);
					$oPdf->cell(35,  $alt, "{$oDadosRelatorio ->dv11_data} - {$oDadosRelatorio ->dv11_hora}"   , 0, 0, "C", 0);
					$oPdf->cell(30,  $alt, $oDadosRelatorio ->login       , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->dv05_numcgm , 0, 0, "C", 0);
					$oPdf->cell(60,  $alt, $oDadosRelatorio ->z01_nome    , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->matricula   , 0, 0, "C", 0);
					$oPdf->cell(110, $alt, $oDadosRelatorio ->observacao  , 0, 1, "L", 0);

					$p     = 0;
					$troca = 0;
				}

				$oPdf->setfont('arial', 'b', 7);
					
				$oPdf->cell(84, $alt, 'Dados de Origem'  , 1, 0, "C", 1);
				$oPdf->cell(191, $alt, 'Dados de Destino' , 1, 1, "C", 1);
				
				$oPdf->cell(30, $alt, 'Tipo'       , 1, 0, "C", 1);
				$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
				$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Receita'    , 1, 0, "C", 1);
				$oPdf->cell(17, $alt, 'Proced�ncia', 1, 0, "C", 1);
				$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
				$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Vlr hist'   , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Vlr corr(*)', 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Juros(*)'   , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Multa(*)'   , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Total(*)'   , 1, 1, "C", 1);
					
			} else {
				
				if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){

					$oPdf->addpage("L");
					$oPdf->setrightmargin(0.5);
					$troca = 0;
					
					$oPdf->cell(84, $alt, 'Dados de Origem'   , 1, 0, "C", 1);
					$oPdf->cell(191, $alt, 'Dados de Destino' , 1, 1, "C", 1);
					
					$oPdf->setfont('arial', 'b', 7);
					$oPdf->cell(30, $alt, 'Tipo'       , 1, 0, "C", 1);
					$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
					$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Receita'    , 1, 0, "C", 1);
					$oPdf->cell(17, $alt, 'Proced�ncia', 1, 0, "C", 1);
					$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
					$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Vlr hist'   , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Vlr corr(*)', 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Juros(*)'   , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Multa(*)'   , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Total(*)'   , 1, 1, "C", 1);
						
						
				}
			}

			foreach($oDadosRelatorio->aRegistros as $oRegistros ) {
				
				$oPdf->setfont('arial', '', 8);
				$oPdf->cell(30, $alt, $oRegistros ->tipoprocedencia    , 0, 0, "C", 0);
				$oPdf->cell(14, $alt, $oRegistros ->numpreantigo       , 0, 0, "C", 0);
				$oPdf->cell(10, $alt, $oRegistros ->numparantigo       , 0, 0, "C", 0);
				$oPdf->cell(30, $alt, $oRegistros ->descrreceitaantiga , 0, 0, "C", 0);
				$oPdf->cell(17, $alt, $oRegistros ->procedencia        , 0, 0, "C", 0);
				$oPdf->cell(14, $alt, $oRegistros ->numprenovo         , 0, 0, "C", 0);
				$oPdf->cell(10, $alt, $oRegistros ->numparnovo         , 0, 0, "C", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->dv05_vlrhis, 'f') , 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->dv05_valor, 'f'), 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->juros, 'f'), 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->multa, 'f'), 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->total, 'f'), 0, 1, "R", 0);
					
					
				$fTotalValorHist   = $fTotalValorHist  + $oRegistros ->dv05_vlrhis;
				$fTotalValor       = $fTotalValor      + $oRegistros ->dv05_valor;
				$fTotalValorJuro   = $fTotalValorJuro  + $oRegistros ->juros;
				$fTotalValorMulta  = $fTotalValorMulta + $oRegistros ->multa;
				$fTotalValorTotal  = $fTotalValorHist + $fTotalValor + $fTotalValorJuro + $fTotalValorMulta;
					
				$fGeralTotalValorHist  = $fGeralTotalValorHist  + $fTotalValorHist;
				$fGeralTotalValor      = $fGeralTotalValor      + $fTotalValor;
				$fGeralTotalValorJuro  = $fGeralTotalValorJuro  + $fTotalValorJuro;
				$fGeralTotalValorMulta = $fGeralTotalValorMulta + $fTotalValorMulta;
				$fGeralTotalValorTotal = $fGeralTotalValorTotal + $fTotalValorTotal;
			}
			$total++;

		}
		if ($aTipoRel == "Analitico") {
				
			$oPdf->setfont('arial', 'b', 8);
			$oPdf->cell(125, $alt, 'TOTAL: '         , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, $fTotalValorHist  , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, $fTotalValor      , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, $fTotalValorJuro  , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, $fTotalValorMulta , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, $fTotalValorTotal , "T", 1, "R", 1);
			$oPdf->ln();

		}

		$oPdf->cell(125, $alt, 'TOTAL GERAL: '        , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, $fGeralTotalValorHist  , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, $fGeralTotalValor      , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, $fGeralTotalValorJuro  , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, $fGeralTotalValorMulta , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, $fGeralTotalValorTotal , "T", 1, "R", 1);
		$oPdf->ln();

		$oPdf->Output();

		return true;

	}

	/**
	 * fun��o responsavel pela gera��o do relat�rio
	 * @throws BusinessException
	 */
	public function gerarRelatorio() {

		if (empty($this->sTipo)) {
			throw new BusinessException('[1] - Tipo para emiss�o n�o informado.');
		}

		if (empty($this->sOrigem)) {
			throw new BusinessException('[2] - origem n�o informada emiss�o.');
		}

		if (empty($this->sFormato)) {
			throw new BusinessException('[3] - Formato para emiss�o n�o informado.');
		}

		$this->setDadosRetorno($this->objetoRetorno());
		
		if ($this->sFormato == "PDF") {
			 
			$this->gerarPDF($this->sTipo);
				
		} else if ($this->sFormato == "CSV") {
				
			$this->gerarCSV();
				
		} else {
			throw new BusinessException('[3] - Erro na defini��o de um  formato para o relat�rio.');
		}
	}


}