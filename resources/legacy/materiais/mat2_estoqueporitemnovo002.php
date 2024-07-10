<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once ("fpdf151/pdf.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/db_stdClass.php");
require_once ("classes/materialestoque.model.php");

$oGet = db_utils::postMemory($_GET);



$aListaWhere         = array();
$aListaWhereSaidas   = array();
$sInformacaoMaterial = "";
$oTotalEstoqueAnalitico = new stdClass();
$oTotalEstoqueSintetico = new stdClass();
/**
 * Verificamos os fitros por departamento
 * V�riaveis do Get s�o strings por isso comparamos o false como string
 */
if (isset($oGet->almoxarifados) && !empty($oGet->almoxarifados)) {

	$sDepartamento = " m70_coddepto in  ($oGet->almoxarifados) ";
	if (isset($oGet->veralmoxarifados) && $oGet->veralmoxarifados == "false") {
		$sDepartamento = " m70_coddepto not in  ($oGet->almoxarifados) ";
	}
	$aListaWhere[] = $sDepartamento ;
}

/**
 * Verificamos os filtros de material
 * V�riaveis do Get s�o strings por isso comparamos o false como string
 */
if (isset($oGet->listamaterial) && !empty($oGet->listamaterial)) {

	$sTipoFiltro = " in ";
	if (isset($oGet->vermaterial) && $oGet->vermaterial == "false") {
		$sTipoFiltro = " not in ";
	}
	$sMaterial = " m70_codmatmater {$sTipoFiltro} ({$oGet->listamaterial}) ";
	$aListaWhere[] = $sMaterial;
}

if (isset($oGet->data_inicial) && !empty($oGet->data_inicial)) {

	$sDataInicial         = implode("-", array_reverse(explode("/", $oGet->data_inicial)));
	$sDatas               = " m71_data >= '{$sDataInicial}' ";
	$aListaWhere[]        = $sDatas;
	$aListaWhereSaidas[]  = $sDatas;
}

if (isset($oGet->data_final) && !empty($oGet->data_final)) {

	$sDataFinal          = implode("-", array_reverse(explode("/", $oGet->data_final)));
	$sDatas              = " m71_data <= '{$sDataFinal}' ";
	$aListaWhere[]       = $sDatas;
	$aListaWhereSaidas[] = $sDatas;
}

/**
 * Verificamos filtro para institui��o
 */
if (isset($oGet->instituicoes) && !empty($oGet->instituicoes)) {
	$aListaWhere[] = " dpartini.instit in ($oGet->instituicoes) ";
}

/**
 * Verificamos se devemos filtar por materiais:
 * Ativos / Inativos
 */
if ($oGet->opcao_material == "A") {

	$aListaWhere[]         = " m60_ativo = 't' ";
	$sInformacaoMaterial  .= "Materiais Ativos";

} else if ($oGet->opcao_material== "I") {

	$aListaWhere[]         = " m60_ativo = 'f' ";
	$sInformacaoMaterial  .= "Materiais Inativos";
}

$aListaWhere[]   = "m71_servico is false";
/**
 * Verificamos a ordem de busca dos filtros
 */
$sOrdem = '';
switch ($oGet->ordem) {

	case 'a':

		$sOrdem = 'm60_descr';
		break;
	case 'c':

		$sOrdem = 'm60_codmater';
		break;
	case 'd':

		$sOrdem = 'm70_coddepto, m60_descr';
		break;
}

if (isset($oGet->quebrapordepartamento) && $oGet->quebrapordepartamento == "S") {
	$sOrdem = "dpartestoque.coddepto, m60_descr";
}

$sWhere       = implode(" and ", $aListaWhere);
$sWhereSaidas = implode(" and ", $aListaWhereSaidas);

if (!empty($sWhereSaidas)) {

	$sWhereSaidas = "and {$sWhereSaidas}";
}

if (isset($oGet->verestoquezerado) && $oGet->verestoquezerado == 'S' ) {

	$sWhere.= " and m71_quant >= '0' ";
}
$oDaoMatEstoqueIniMei = db_utils::getDao("matestoqueinimei");

/**
 * criamos uma lista de itens por estoque. conforme filtros acima.
 */
$sCampos          = " distinct dpartestoque.coddepto as codigo_departamento, ";
$sCampos         .= " dpartestoque.descrdepto as descricao_departamento, m71_codmatestoque, m71_codlanc, m71_quant,  ";
$sCampos         .= " m60_codmater, m60_descr, dpartini.coddepto as codigo_almoxarifado,  m77_lote, ";
$sCampos         .= " dpartini.descrdepto as descrisao_almoxarifado, ";
$sCampos         .= " m71_valor, m77_dtvalidade, m76_nome as fabricante, m71_data as data_movimeto";
$sSqlDadosEstoque = $oDaoMatEstoqueIniMei->sql_query_precomedio(null, $sCampos, $sOrdem, $sWhere);

// die($sSqlDadosEstoque);

$aTiposMovimentoEntrada = array(1, 3, 12);
$rsDadosEstoque         = $oDaoMatEstoqueIniMei->sql_record($sSqlDadosEstoque);
$aItensEstoque          = array();
$iTotalItensEstoque     = $oDaoMatEstoqueIniMei->numrows;

if ($iTotalItensEstoque > 25000) {

	$sMsgErro  = "N�o foi poss�vel gerar o relat�rio. Muitos registros foram encontrados. <br>";
	$sMsgErro .= "Por favor, refine sua busca. ";
	db_redireciona("db_erros.php?fechar=true&db_erro=$sMsgErro");
}

$oTotalEstoqueAnalitico->iNumeroLancamentos = $iTotalItensEstoque;
$oTotalEstoqueAnalitico->iTotalEntrada      = 0;
$oTotalEstoqueAnalitico->iTotalAtendido     = 0;
$oTotalEstoqueAnalitico->iTotal             = 0;

$oTotalEstoqueSintetico->iNumeroLancamentos = $iTotalItensEstoque;
$oTotalEstoqueSintetico->iTotalEstoque      = 0;
$oTotalEstoqueSintetico->iTotal             = 0;

if ($iTotalItensEstoque > 0) {

	for ($iEstoque = 0; $iEstoque < $iTotalItensEstoque; $iEstoque++) {

		/**
		 * Buscamos o valor da entrada, pre�o m�dio do material
		 */
		$oDadosItemEstoque                    = db_utils::fieldsMemory($rsDadosEstoque, $iEstoque);

		if ($oDadosItemEstoque->m71_valor == 0 || $oDadosItemEstoque->m71_quant == 0) {
			continue;
		}

		$oItemEstoque                         = new stdClass();
		$oItemEstoque->iCodigoDepartamento    = $oDadosItemEstoque->codigo_almoxarifado;
		$oItemEstoque->sDescricaoDepartamento = $oDadosItemEstoque->descrisao_almoxarifado;
		$oItemEstoque->iCodigoItem            = $oDadosItemEstoque->m60_codmater;
		$oItemEstoque->sDescricaoItem         = $oDadosItemEstoque->m60_descr;
		$oItemEstoque->iQuantidadeEstoque     = 0;
		$oItemEstoque->iQuantidadeAtendida    = 0;
		$oItemEstoque->nValorEstoque          = 0;
		$oItemEstoque->nPrecoMedio            = $oDadosItemEstoque->m71_valor / $oDadosItemEstoque->m71_quant;
		$oItemEstoque->nValorUnitario         = $oDadosItemEstoque->m71_valor / $oDadosItemEstoque->m71_quant;
		$oItemEstoque->iCodigoAlmoxarifado    = $oDadosItemEstoque->codigo_departamento;
		$oItemEstoque->sDescricaoAlmoxarifado = $oDadosItemEstoque->descricao_departamento;
		$oItemEstoque->iCodigoEstoque         = $oDadosItemEstoque->m71_codmatestoque;
		$oItemEstoque->sLote                  = $oDadosItemEstoque->m77_lote;
		$oItemEstoque->dtValidade             = $oDadosItemEstoque->m77_dtvalidade;
		$oItemEstoque->sFabricante            = $oDadosItemEstoque->fabricante;
		$oItemEstoque->dtMovimento            = $oDadosItemEstoque->data_movimeto;
		$oItemEstoque->iCodigoMovimento       = 0;
		$oItemEstoque->aMovimentacoes         = array();

		/**
		 * Buscamos os Movimentos do material
		 */
		$oDaoMatEstoqueIni  = db_utils::getDao('matestoqueini');
		$sCamposSaidaItem   = " coalesce(round(m82_quant, 2),0) as quantidade,   ";
		$sCamposSaidaItem  .= " round(m89_precomedio, 4)        as preco_medio,  ";
		$sCamposSaidaItem  .= " round(m89_valorunitario, 4)     as valorunitario, ";
		$sCamposSaidaItem  .= " m81_tipo, ";
		$sCamposSaidaItem  .= " m80_codigo, ";
		$sCamposSaidaItem  .= " m80_codtipo ";
		$sWhereSaidasItens  = " m82_matestoqueitem   = {$oDadosItemEstoque->m71_codlanc} ";
		$sWhereSaidasItens .= $sWhereSaidas;
		$sWhereSaidasItens .= " and m81_tipo in (1, 2) ";
		$sOrdemSaidasItens  = " m80_data,m80_hora, m80_codigo ";

		$sSqlSaidasItens    = $oDaoMatEstoqueIni->sql_query_movimentacoes(null,
		$sCamposSaidaItem,
		$sOrdemSaidasItens,
		$sWhereSaidasItens);
		$rsSaidasItens     = db_query($sSqlSaidasItens) ;
		$iTotalSaidasItens = pg_num_rows($rsSaidasItens);

		/**
		 * Busca o saldo anterio do item, se informado um per�odo inicial
		 */
		if (isset($oGet->data_inicial) && !empty($oGet->data_inicial)) {

			$sDataInicial          = implode("-", array_reverse(explode("/", $oGet->data_inicial)));
			$sCamposSaldoAnterior  = " sum(coalesce(case when m81_tipo = 1 then round(m82_quant,2) 														  ";
			$sCamposSaldoAnterior .= "                   when m81_tipo = 2 then round(m82_quant,2) *-1 end, 0)) as saldoinicial ";
			$sWhereSaldoAnterior   = " m82_matestoqueitem   = {$oDadosItemEstoque->m71_codlanc} ";
			$sWhereSaldoAnterior  .= " and m80_data < '{$sDataInicial}'";
			$sSqlSaldoAnterior     = $oDaoMatEstoqueIni->sql_query_movimentacoes(null,
			$sCamposSaldoAnterior,
			null,
			$sWhereSaldoAnterior
			);

			$rsSaldoAnterior                   = $oDaoMatEstoqueIni->sql_record($sSqlSaldoAnterior);
			$nSaldoAnterior                    = db_utils::fieldsMemory($rsSaldoAnterior, 0)->saldoinicial;
			$oItemEstoque->iQuantidadeEstoque  = $nSaldoAnterior;
			unset($rsSaldoAnterior);
			unset($nSaldoAnterior);
			unset($oDaoMatEstoqueIni);
		}

		if ($rsSaidasItens && $iTotalSaidasItens > 0) {

			for ($iSaida = 0; $iSaida < $iTotalSaidasItens; $iSaida++) {

				$oDadosSaidaItem                    = db_utils::fieldsMemory($rsSaidasItens, $iSaida);
				$oItemEstoque->nPrecoMedio          = $oDadosSaidaItem->preco_medio;

				if ($oDadosSaidaItem->m81_tipo == 2) {
					$oItemEstoque->iQuantidadeAtendida += $oDadosSaidaItem->quantidade;
				} else {

					if (in_array($oDadosSaidaItem->m80_codtipo, $aTiposMovimentoEntrada)) {
						$oItemEstoque->iCodigoMovimento = $oDadosSaidaItem->m80_codigo;
					}
					$oItemEstoque->iQuantidadeEstoque  += $oDadosSaidaItem->quantidade;
				}

				unset($oDadosSaidaItem);
			}
		}

		$iItensEstoque               = ($oItemEstoque->iQuantidadeEstoque * $oItemEstoque->nPrecoMedio);
		$oItemEstoque->nValorEstoque = round($iItensEstoque, 2);

		$aItensEstoque[]             = $oItemEstoque;
		unset($oDadosItemEstoque);
		unset($rsSaidasItens);
	}

	/**
	 * Agrupamos os Itens Sinteticamente
	 */
	$aDepartamentos = array();
	$aEstoques      = array();
	foreach ($aItensEstoque as $oItemEstoque) {

		if (!isset($aEstoques[$oItemEstoque->iCodigoEstoque])) {

			$aMovimetacoesEstoque                     = array();
			$oEstoque                                 = new stdClass();
			$oEstoque->iCodigoItem                    = $oItemEstoque->iCodigoItem;
			$oEstoque->sDescricaoItem                 = $oItemEstoque->sDescricaoItem;
			$oEstoque->iCodigoAlmoxarifado            = $oItemEstoque->iCodigoAlmoxarifado;
			$oEstoque->sDescricaoAlmoxarifado         = $oItemEstoque->sDescricaoAlmoxarifado;
			$oEstoque->nQuantidadeEstoque             = 0;
			$oEstoque->nPrecoMedio                    = 0;
			$oEstoque->aMovimentacoesEstoque          = array();
			$aEstoques[$oItemEstoque->iCodigoEstoque] = $oEstoque;
		}
		$nQuatidadeEstoque = $oItemEstoque->iQuantidadeEstoque - $oItemEstoque->iQuantidadeAtendida;

		$aEstoques[$oItemEstoque->iCodigoEstoque]->nQuantidadeEstoque     += $nQuatidadeEstoque;
		$aEstoques[$oItemEstoque->iCodigoEstoque]->nPrecoMedio             = $oItemEstoque->nPrecoMedio;
		$aEstoques[$oItemEstoque->iCodigoEstoque]->aMovimentacoesEstoque[] = $oItemEstoque;

		/**
		 * Array dos Departamentos
		 */
		if (!isset($aDepartamentos[$oItemEstoque->iCodigoDepartamento][$oItemEstoque->iCodigoItem])) {

			$aMovimetacoesDepartamento             = array();
			$oItem                                 = new stdClass();
			$oItem->iCodigoItem                    = $oItemEstoque->iCodigoItem;
			$oItem->sDescricaoItem                 = $oItemEstoque->sDescricaoItem;
			$oItem->iCodigoAlmoxarifado            = $oItemEstoque->iCodigoAlmoxarifado;
			$oItem->sDescricaoAlmoxarifado         = $oItemEstoque->sDescricaoAlmoxarifado;
			$oItem->nQuantidadeEstoque             = 0;
			$oItem->nPrecoMedio                    = 0;
			$aDepartamentos[$oItemEstoque->iCodigoDepartamento][$oItemEstoque->iCodigoItem] = $oItem;
		}
		$nQuatidadeEstoque = $oItemEstoque->iQuantidadeEstoque - $oItemEstoque->iQuantidadeAtendida;

		$aDepartamentos[$oItemEstoque->iCodigoDepartamento][$oItemEstoque->iCodigoItem]->nQuantidadeEstoque     += $nQuatidadeEstoque;
		$aDepartamentos[$oItemEstoque->iCodigoDepartamento][$oItemEstoque->iCodigoItem]->nPrecoMedio             = $oItemEstoque->nPrecoMedio;
		$aDepartamentos[$oItemEstoque->iCodigoDepartamento][$oItemEstoque->iCodigoItem]->aMovimentacoesDepartamento[] = $oItemEstoque;
	}
} else {
	db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem registros cadastrados.');
}

/**
 * Cabe�alho do Relat�rio
 */
$sInformacaoData = "";
if (!empty($oGet->data_inicial) && !empty($oGet->data_final)) {
	$sInformacaoData = "De {$oGet->data_inicial} at� {$oGet->data_final}";
} else if (!empty($oGet->data_inicial) && empty($oGet->data_final)) {
	$sInformacaoData = "Apartir de {$oGet->data_inicial}";
} else if (empty($oGet->data_inicial) && !empty($oGet->data_final)) {
	$sInformacaoData = "At� de {$oGet->data_final}";
}

switch ($oGet->tipoimpressao) {

	case "S": // Sint�tico
		$sTipoImpressa = "Sint�tica";
		break;
	case "A": // Anal�tica
		$sTipoImpressa = "Anal�tica";
		break;
	case "C": // Confer�ncia
		$sTipoImpressa = "Confer�ncia";
		break;
}

$head3 = "Relat�rio de Estoque";
$head4 = $sTipoImpressa;
$head5 = $sInformacaoMaterial;
$head6 = $sInformacaoData;
$head7 = "Somente Materiais";

if ($oGet->totalizador == 'sim') {
  $head8 = "Com totalizador";
}

/**
 * Vari�vel de Configura��o do Relat�rio
 */

$lPrimeiraCelula = true;
$iLinhaAltura    = 10;
if ($oGet->tipoimpressao != "C") {
	$iLinhaAltura    = 4;
}


$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);

/**
 * Verifica o tipo a ser impresso do relat�rio recebido pelo par�metro: $oGet->quebrapordepartamento
 */
if (isset($oGet->quebrapordepartamento) && $oGet->quebrapordepartamento == "S") {

	switch ($oGet->tipoimpressao) {

		 case "S": // Sint�tico

      foreach ($aDepartamentos as $aDepartamento) {

        $oTotalEstoqueSintetico->iTotalIteracao = 0;
        $oTotalEstoqueSintetico->valorTotal = 0;
        $oTotalEstoqueSintetico->quant             = 0;
        //echo count($aDepartamento);exit;

        foreach ($aDepartamento as $oMovimentos) {

          if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiraCelula ) {
              $oPdf->addPage('L');
              setHeaderSintetico($oPdf);
          }

          getDadosSinteticoQuebra($oPdf, $oMovimentos, $oTotalEstoqueSintetico, $oGet, true);

          $lPrimeiraCelula = false;

        }
        $oTotalEstoqueSintetico->iNumero +=  $oTotalEstoqueSintetico->iTotalIteracao;
        if($oTotalEstoqueSintetico->iTotalIteracao != 0){
          getTotalizadorSinteticoPorDepart($oPdf, $oTotalEstoqueSintetico->iTotalIteracao, $oTotalEstoqueSintetico->quant, $oTotalEstoqueSintetico->valorTotal , true);
          $lPrimeiraCelula = false;
        }
      }
      getTotalizadorSintetico($oPdf, $oTotalEstoqueSintetico, false);
      break;
    case "A": // Anal�tica
      foreach ($aDepartamentos as $aDepartamento) {

        foreach ($aDepartamento as $oMovimentos) {

          if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiraCelula) {

            $oPdf->addPage('L');
            setHeaderAnalitico($oPdf);
          }

          $oPdf->setfont('arial', 'b', 7);
          $lImprime = getDadosSintetico($oPdf, $oMovimentos, $oTotalEstoqueSintetico, $oGet, true, 0, true,0,0);

          if ($lImprime) {
            foreach ($oMovimentos->aMovimentacoesDepartamento as $oItens) {

							getDadoAnalitico($oPdf, $oItens);
						}
					}
					$lPrimeiraCelula = false;
				}
				$lPrimeiraCelula = true;
			}$iContador = 0;
			break;
		case "C": // Confer�ncia

			foreach ($aDepartamentos as $aDepartamento) {

        $iContador = 0;
        foreach ($aDepartamento as $oMovimentos) {

          if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiraCelula) {

            $oPdf->addPage('L');
            setHeaderSintetico($oPdf, false);
          }
          $iPreenche = 0;

          if ($iContador % 2 != 0){
            $iPreenche = 1;
          }

					$lImprime = getDadosSintetico($oPdf, $oMovimentos, $oGet, false, $iPreenche,false,0,0);
					if ($iContador) {
						$iContador++;
					}
					$lPrimeiraCelula = false;
				}
				$lPrimeiraCelula = true;
			}
			break;
	}

} else {

	switch ($oGet->tipoimpressao) {

		case "S": // Sint�tico

			$iContador = 0;
      $aItens = array();

      foreach ($aEstoques as $oMovimentos) {

        $aItens[] = $oMovimentos;

        if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiraCelula) {

          $oPdf->addPage('L');
          setHeaderSintetico($oPdf);
        }
        $iPreenche = 0;

        if ($iContador % 2 != 0){
          $iPreenche = 1;
        }

        $lImprime = getDadosSintetico($oPdf, $oMovimentos, $oGet, true,$iPreenche,false,55,35);

        if ($lImprime) {
          $iContador++;
        }
        $lPrimeiraCelula = false;

      }

      if ($oGet->totalizador == 'sim') {

        getTotalGeralSintetico($oPdf, $aItens);

      }

			break;
		case "A": // Anal�tica

			foreach ($aEstoques as $oMovimentos) {

				if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiraCelula) {

					$oPdf->addPage('L');
					setHeaderAnalitico($oPdf);
				}

				$oPdf->setfont('arial', 'b', 7);

				$lImprime = getDadosSintetico($oPdf, $oMovimentos, $oGet, true, 0, true,20,0);

				if ($lImprime) {
					foreach ($oMovimentos->aMovimentacoesEstoque as $oItens) {

						getDadoAnalitico($oPdf, $oItens);
					}
					$oPdf->cell(280, 1,"","B", 1, "L", $iPreenche);
				}
				//$oPdf->cell(280, 1,"","B", 1, "L", $iPreenche);
				$lPrimeiraCelula = false;

			}
			break;
		case "C": // Confer�ncia
			//echo "Confer�ncia";exit;

			$iContador = 0;
			foreach ($aEstoques as $oMovimentos) {

				if ($oPdf->gety() > $oPdf->h - 30 || $lPrimeiraCelula) {

					$oPdf->addPage('L');
					setHeaderSintetico($oPdf, false);
				}
				$iPreenche = 0;
				$espessura=$oPdf->LineWidth;

				if ($iContador % 2 != 0) {
					$iPreenche = 1;//echo "passou";exit;
					//$oPdf->Line(230,65,260,65);
				  $oPdf->SetLineWidth(0.3);
				}else{
				$oPdf->SetLineWidth(0.4);
				}
				$oPdf->LineWidth=$espessura;
				//$oPdf->Line(230,75,260,75);

				$lImprime = getDadosSintetico($oPdf, $oMovimentos, $oGet, false, $iPreenche,$lAnalitico,30,30);

				if ($lImprime) {
					$iContador++;
				}
				$lPrimeiraCelula = false;

			}

			break;
	}

}



/**
 * Insere cabe�alho para relat�rios do tipo:
 * Sint�tico / Confer�ncia
 * @param object $oPdf
 * @param boolean $lSintetico
 */
function setHeaderSintetico($oPdf, $lSintetico = true) {

	$iAlturaLinha  = 4;
	$iLarguraLinha = 0;
	$iBorda        = 0;
	if (!$lSintetico) {

		$iAlturaLinha  = 10;
		$iLarguraLinha = 10;
		$iBorda        = 1;
	}

	$oPdf->setfont('arial', 'b', 7);

	$oPdf->cell(30,                   $iAlturaLinha, "Cod. Material",      1, 0, "C", 1);
	$oPdf->cell(100,                  $iAlturaLinha, "Descri��o Material", 1, 0, "C", 1);
	$oPdf->cell(70 - $iLarguraLinha,  $iAlturaLinha, "Almoxarifado",       1, 0, "C", 1);
	$oPdf->cell(40 - $iLarguraLinha,  $iAlturaLinha, "Quant. Estoque",     1, 0, "C", 1);
	if (!$lSintetico) {
		$oPdf->cell(40 - $iLarguraLinha,  $iAlturaLinha, "Contagem",         1, 0, "C", 1);
	}
	$oPdf->cell(40 - $iLarguraLinha,  $iAlturaLinha, "Valor em Estoque",   1, 1, "C", 1);
}

/**
 * Insere cabe�alho para relat�rios do tipo Anal�tico
 * @param object $oPdf
 */
function setHeaderAnalitico($oPdf) {

	setHeaderSintetico($oPdf);
	$oPdf->cell(25,  4, 'Cod. Lan�amento',  1, 0, "C", 1);
	$oPdf->cell(20,  4, "Data",             1, 0, "C", 1);
	$oPdf->cell(25,  4, "Lote",             1, 0, "C", 1);
	$oPdf->cell(30,  4, "Validade",         1, 0, "C", 1);
	$oPdf->cell(60,  4, "Fabricante",       1, 0, "C", 1);
	$oPdf->cell(30,  4, "Vlr Unit�rio",     1, 0, "C", 1);
	$oPdf->cell(30,  4, "Qtd Entrada",      1, 0, "C", 1);
	$oPdf->cell(30,  4, "Quant. Atendida",  1, 0, "C", 1);
	$oPdf->cell(30,  4, "Valor",            1, 1, "C", 1);

}


/**
 * Insere os dados para relat�rios do tipo:
 * Sint�tico / Confer�ncia
 * @param object $oPdf
 * @param object $oMovimentos
 * @param boolean $lSintetico
 * @param integer $iPreenche pinta a linha
 */

function getDadosSintetico($oPdf, $oMovimentos, $oGet, $lSintetico = true, $iPreenche = 0, $lAnalitico = false,$posicao,$sinte){

	if ($oGet->verestoquezerado == "N" && $oMovimentos->nQuantidadeEstoque == 0) {
		return false;
	}
	$altura=80;

	$iAlturaLinha  = 4;
	$iLarguraLinha = 0;
	$iBorda        = "T";

	if (!$lSintetico) {

		$iAlturaLinha  = 12;
		$iLarguraLinha = 12;
		$iBorda        = 1;

	}
	if ($lAnalitico) {
		$iBorda = "B";
		$altura=40;
	}

	$iCodigoAlmoxarifado = str_pad($oMovimentos->iCodigoAlmoxarifado, 3, " ", STR_PAD_RIGHT);
	$sAlmoxarifado       = "{$iCodigoAlmoxarifado} - $oMovimentos->sDescricaoAlmoxarifado";
	//   $nValorEstoque       = $oMovimentos->nQuantidadeEstoque * $oMovimentos->nPrecoMedio;
	//   $nValorEstoque       = number_format($nValorEstoque, 2);
	//$sDescricaoItem      = substr($oMovimentos->sDescricaoItem, 0, 65);

	$sDescricaoItem=$oMovimentos->sDescricaoItem;
	$sDescricaoItem= ltrim($sDescricaoItem);

	if (strlen($sDescricaoItem) > 60 || strlen($sAlmoxarifado) > 40){
		if(strlen($sDescricaoItem) > 60){
			$descrItem = quebrar_texto($sDescricaoItem,60);
			$alt_novo = count($descrItem);
		}else{
			$sAlmox = quebrar_texto($sAlmoxarifado,40);
			$alt_novo = count($sAlmox);
		}
	} else {
		$alt_novo = 1;
	}

	$oMaterial     = new MaterialEstoque($oMovimentos->iCodigoItem);
	$nValorEstoque = number_format($oMaterial->getPrecoMedio() * $oMovimentos->nQuantidadeEstoque, 2);


	$oPdf->cell(30,10, $oMovimentos->iCodigoItem,       "", 0, "C", $iPreenche);

	//	$oPdf->cell(280, 10,"","B", 0, "L", $iPreenche);


	if (strlen($sDescricaoItem) > 60){

		$pos_x = $oPdf->x;
		$pos_y =$oPdf->y;
		foreach ($descrItem as $descr_nova){
			$descr_nova=ltrim($descr_nova);
			$oPdf->cell(100,5,substr($descr_nova,0,60),   "", 1, "L", $iPreenche);
			$oPdf->x=$pos_x;
		}
		$oPdf->x = $pos_x+100;
		$oPdf->y = $pos_y;

	}else{
		$oPdf->cell(100,10,substr($sDescricaoItem,0,60),"", 0, "L", $iPreenche);// verificar
		//		$oPdf->cell(100,10,substr($sDescricaoItem,0,60),"", 0, "L", $iPreenche);// verificar
	}

	/*almoxarifado */

	if (strlen($sAlmoxarifado) > 60){

		$pos_x = $oPdf->x;
		$pos_y =$oPdf->y;
		foreach ($sAlmoxarifado as $sAlmox) {

			$oPdf->cell(70,$iAlturaLinha,substr($sAlmox,0,60),     "", 1, "L", $iPreenche);
			$oPdf->x=$pos_x;
		}
		$oPdf->x = $pos_x+100;
		$oPdf->y = $pos_y;

	} else{
		$oPdf->cell(60, 10,substr($sAlmoxarifado,0,40),"", 0, "L", $iPreenche);
	}

	//$oPdf->SetLineWidth(0.3);

	//$oPdf->Line(230,($altura*$alt_novo)+5,260,($altura*$alt_novo)+5);

	$oPdf->cell($posicao,10, $oMovimentos->nQuantidadeEstoque, "", 0, "C", $iPreenche);
	//$oPdf->cell(30,10, $oMovimentos->nQuantidadeEstoque, "", 0, "C", $iPreenche);
	if (!$lSintetico) {
		$altura=30;

		$oPdf->cell(30,10, "",                           "B", 0, "R", $iPreenche);
		/*$oPdf->SetLineWidth(0.3);
		 $oPdf->Line(230,55,260,55);*/
	}//-20
	//	$oPdf->cell($sinte,10, $nValorEstoque,                   "", 1, "C", $iPreenche);//20 para sintetico
	$oPdf->cell($sinte,10, $nValorEstoque,                   "", 1, "C", $iPreenche);//20 para sintetico
	//$oPdf->cell($altura,10, $nValorEstoque,                   "", 1, "C", $iPreenche);

	return true;

}

/**
 * Insere cabe�alho para relat�rios do tipo:
 * Anal�tico
 * @param object $oPdf
 * @param object $oItens Lan�amentos
 */
function getDadoAnalitico($oPdf, $oItens) {

	$nValorUnitario = number_format($oItens->nValorUnitario, 2);
	$nValorEstoque  = number_format($oItens->nValorEstoque, 2);
	$dtMovimento    = formataData($oItens->dtMovimento);
	$dtValidade     = formataData($oItens->dtValidade);

	$oPdf->setfont('arial', '', 7);

	$oPdf->cell(25,  4, $oItens->iCodigoMovimento,     0, 0, "R", 0);
	$oPdf->cell(20,  4, $dtMovimento,                  0, 0, "C", 0);
	$oPdf->cell(25,  4, $oItens->sLote,                0, 0, "L", 0);
	$oPdf->cell(30,  4, $dtValidade,                   0, 0, "C", 0);
	$oPdf->cell(60,  4, $oItens->sFabricante,          0, 0, "L", 0);
	$oPdf->cell(30,  4, $nValorUnitario,               0, 0, "R", 0);
	$oPdf->cell(30,  4, $oItens->iQuantidadeEstoque,   0, 0, "R", 0);
	$oPdf->cell(30,  4, $oItens->iQuantidadeAtendida,  0, 0, "R", 0);
	$oPdf->cell(30,  4, $nValorEstoque,                0, 1, "R", 0);
}

function formataData($dtData, $sSearch = "-", $sReplace = "/") {

	return implode("/", array_reverse(explode("-", $dtData)));
}

$oPdf->Output();

function quebrar_texto($texto,$tamanho){

	$aTexto = explode(" ", $texto);
	$string_atual = "";
	foreach ($aTexto as $word) {
		$string_ant = $string_atual;
		$string_atual .= " ".$word;
		if (strlen($string_atual) > $tamanho) {
			$aTextoNovo[] = $string_ant;
			$string_ant   = "";
			$string_atual = $word;
		}
	}
	$aTextoNovo[] = $string_atual;
	return $aTextoNovo;

}

function getDadosSinteticoQuebra($oPdf, $oMovimentos, $oTotalEstoqueSintetico, $oGet, $lSintetico = true, $iPreenche = 0, $lAnalitico = false) {

  if ($oGet->verestoquezerado == "N" && $oMovimentos->nQuantidadeEstoque == 0) {
    return false;
  }

  $iAlturaLinha  = 4;
  $iLarguraLinha = 0;
  $iBorda        = "0";
  if (!$lSintetico) {

    $iAlturaLinha  = 10;
    $iLarguraLinha = 10;
    $iBorda        = 1;
  }
  if ($lAnalitico) {
    $iBorda = "T";
  }

  $iCodigoAlmoxarifado = str_pad($oMovimentos->iCodigoAlmoxarifado, 3, " ", STR_PAD_RIGHT);
  $sAlmoxarifado       = "{$iCodigoAlmoxarifado} - $oMovimentos->sDescricaoAlmoxarifado";
  $nValorEstoque       = $oMovimentos->nQuantidadeEstoque * $oMovimentos->nPrecoMedio;

  $oTotalEstoqueSintetico->iTotalEstoque += $oMovimentos->nQuantidadeEstoque;
  $oTotalEstoqueSintetico->iTotal        += $nValorEstoque;

  //ADD dia 06-05-2015
  $oTotalEstoqueSintetico->iTotalIteracao++;
  $oTotalEstoqueSintetico->quant += $oMovimentos->nQuantidadeEstoque;
  $oTotalEstoqueSintetico->valorTotal += $nValorEstoque;
  //******************************************************************

  $nValorEstoque       = number_format($nValorEstoque, 2);
  $sDescricaoItem      = substr($oMovimentos->sDescricaoItem, 0, 65);

  $oMaterial     = new MaterialEstoque($oMovimentos->iCodigoItem);

 // $nValorEstoque = number_format($oMaterial->getPrecoMedio() * $oMovimentos->nQuantidadeEstoque, 2);

  $oPdf->cell(30,                   $iAlturaLinha, $oMovimentos->iCodigoItem,        $iBorda, 0, "R", $iPreenche);
  $oPdf->cell(100,                  $iAlturaLinha, $sDescricaoItem,                  $iBorda, 0, "L", $iPreenche);
  $oPdf->cell(70 - $iLarguraLinha,  $iAlturaLinha, $sAlmoxarifado,                   $iBorda, 0, "L", $iPreenche);
  $oPdf->cell(40 - $iLarguraLinha,  $iAlturaLinha, $oMovimentos->nQuantidadeEstoque, $iBorda, 0, "R", $iPreenche);
  if (!$lSintetico) {
    $oPdf->cell(40 - $iLarguraLinha,  $iAlturaLinha, "",                             $iBorda, 0, "R", $iPreenche);
  }
  $oPdf->cell(40 - $iLarguraLinha,  $iAlturaLinha, $nValorEstoque,                   $iBorda, 1, "R", $iPreenche);

  return true;
}

/*
* Fun��o Contass Contabilidade
*/
function getTotalizadorSinteticoPorDepart($oPdf, $oTotalRegistros,$nEstoque, $valorTotal, $lSintetico = true) {

  $oPdf->ln(2);
  $oPdf->setfont('arial', 'B', 7);

  $iLarguraLinha = 0;
  if (!$lSintetico) {
    $iLarguraLinha = 10;
  }

  $oPdf->cell(200 - $iLarguraLinha, 4, 'Total do departamento: ' . $oTotalRegistros, 'TB', 0, "L", 0);
  $oPdf->cell(40 - $iLarguraLinha,  4, $nEstoque, 1, 0, "R", 0);
  if (!$lSintetico) {
    $oPdf->cell(40 - $iLarguraLinha, 4, "", 'TB', 0, "R", 0);
  }
  $oPdf->cell(40 - $iLarguraLinha,  4, number_format($valorTotal,2), 1, 1, "R", 0);
}

/**
 * Insere totalizados para relat�rio do tipo "Sintetico" quando agrupado por departamentos
 * @param  Object  $oPdf
 * @param  Object  $oTotalEstoqueSintetico
 * @param  boolean $lSintetico
 */
function getTotalizadorSintetico($oPdf, $oTotalEstoqueSintetico, $lSintetico = true) {

  $oPdf->ln(2);
  $oPdf->setfont('arial', 'B', 7);

  $iLarguraLinha = 0;
  if (!$lSintetico) {
    $iLarguraLinha = 10;
  }

  $oPdf->cell(200 - $iLarguraLinha, 4, 'Total de Registros: ' . $oTotalEstoqueSintetico->iNumero, 'TB', 0, "L", 0);
  $oPdf->cell(40 - $iLarguraLinha,  4, $oTotalEstoqueSintetico->iTotalEstoque, 1, 0, "R", 0);
  if (!$lSintetico) {
    $oPdf->cell(40 - $iLarguraLinha, 4, "", 'TB', 0, "R", 0);
  }
  $oPdf->cell(40 - $iLarguraLinha,  4, number_format($oTotalEstoqueSintetico->iTotal,2), 1, 1, "R", 0);

}


/**
 * Insere totalizados para relat�rio do tipo:
 * Sintetico
 * @param  Object   $oPdf
 * @param  Array    $aItens
 */
function getTotalGeralSintetico($oPdf, $aItens) {

  $oTotal = new stdClass();
  $oTotal->qtdEstoque = 0;
  $oTotal->vlrEstoque = 0;

  foreach ($aItens as $oMovimentos) {

    $oMaterial     = new MaterialEstoque($oMovimentos->iCodigoItem);
    $nValorEstoque = $oMaterial->getPrecoMedio() * $oMovimentos->nQuantidadeEstoque;

    $oTotal->qtdEstoque += $oMovimentos->nQuantidadeEstoque;
    $oTotal->vlrEstoque += $nValorEstoque;

  }

  $oPdf->ln(2);
  $oPdf->setfont('arial', 'B', 7);

  $oPdf->cell(200 - $iLarguraLinha, 4, 'Total:', 'LTB', 0, "L", 0);
  $oPdf->cell(40 - $iLarguraLinha,  4, $oTotal->qtdEstoque, 1, 0, "C", 0);
  $oPdf->cell(40 - $iLarguraLinha,  4, number_format($oTotal->vlrEstoque, 2), 1, 1, "C", 0);

}
