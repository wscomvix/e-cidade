<?php
require_once 'model/relatorios/Relatorio.php';
include("classes/db_db_docparag_classe.php");
include("classes/homologacaoadjudica_classe.php");
include("classes/db_liclicita_classe.php");
include("libs/db_libdocumento.php");
include("classes/db_liccomissaocgm_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_db_documento_classe.php");
include("lic1_relatorio_helper.php");

$clhomologacaoadjudica = new cl_homologacaoadjudica();
$clliclicita =  new cl_liclicita();
$clliccomissaocgm     = new cl_liccomissaocgm();
$cldb_config          = new cl_db_config();
$cldb_documento          = new cl_db_documento();

// include("fpdf151/pdf.php");
require("libs/db_utils.php");
$oGet = db_utils::postMemory($_GET);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$result = $cldb_documento->sql_record($cldb_documento->sql_query("","*","","db03_descr like 'HOMOLOGACAO RELATORIO'"));
$result1 = db_utils::fieldsMemory($result, 0);

$oLibDocumento = new libdocumento($result1->db03_tipodoc,null);

switch ($oGet->tipoprecoreferencia) {
    case '2':
        $tipoReferencia = " MAX(pc23_vlrun) ";
        break;

    case '3':
        $tipoReferencia = " MIN(pc23_vlrun) ";
        break;

    default:
        $tipoReferencia = " (sum(pc23_vlrun)/count(pc23_orcamforne)) ";
        break;
}

$rsResultado = db_query("select pc80_criterioadjudicacao from pcproc where pc80_codproc = {$codigo_preco}");
$criterio    = db_utils::fieldsMemory($rsResultado, 0)->pc80_criterioadjudicacao;
$sCondCrit   = ($criterio == 3 || empty($criterio)) ? " AND pc23_valor <> 0 " : "";


    $oLinha = null;

    $sWhere  = " db02_descr like 'ASS. RESP. DEC. DE RECURSOS FINANCEIROS' ";
    //$sWhere .= " AND db03_descr like 'ASSINATURA DO RESPONS�VEL PELA DECLARA��O DE RECURSOS FINANCEIROS' ";
    $sWhere .= " AND db03_instit = db02_instit ";
    $sWhere .= " AND db02_instit = " . db_getsession('DB_instit');

    $cl_docparag = new cl_db_docparag;

    $sAssinatura = $cl_docparag->sql_query_doc('', '', 'db02_texto', '', $sWhere);
    $rs = $cl_docparag->sql_record($sAssinatura);
    $oLinha = db_utils::fieldsMemory($rs, 0)->db02_texto;


    $sWhere  = " db02_descr like 'RESPONS�VEL PELA COTA��O' ";
    //$sWhere .= " AND db03_descr like 'ASSINATURA DO RESPONS�VEL PELA DECLARA��O DE RECURSOS FINANCEIROS' ";
    $sWhere .= " AND db03_instit = db02_instit ";
    $sWhere .= " AND db02_instit = " . db_getsession('DB_instit');

    $sSqlCotacao = $cl_docparag->sql_query_doc('', '', 'db02_texto', '', $sWhere);
    $rsCotacao = $cl_docparag->sql_record($sSqlCotacao);
    $sAssinaturaCotacao = db_utils::fieldsMemory($rsCotacao, 0)->db02_texto;

    if($nome==""){
        $comissao = $clliccomissaocgm->sql_record($clliccomissaocgm->sql_query_file(null,'l31_codigo,l31_liccomissao,l31_numcgm, (select cgm.z01_nome from cgm where z01_numcgm = l31_numcgm) as z01_nome, l31_tipo',null,"l31_licitacao=$codigo_preco"));
        for($i=0;$i<$clliccomissaocgm->numrows;$i++){
            $comisaoRes = db_utils::fieldsMemory($comissao, $i);
           if($comisaoRes->l31_tipo==6){
                $nome = $comisaoRes->z01_nome;
            }
        }
    }

    $nTotalItens = 0;

    if($valor==""){

        $nTotalItens = 0;
        $campos = "DISTINCT pc01_codmater,pc01_descrmater,cgmforncedor.z01_nome,cgmforncedor.z01_cgccpf,m61_descr,pc11_quant,pc23_valor,pcorcamval.pc23_vlrun,l203_homologaadjudicacao,pc81_codprocitem,l04_descricao,pc11_seq";
        $sWhere = " liclicitem.l21_codliclicita = {$codigo_preco} and pc24_pontuacao = 1 AND itenshomologacao.l203_homologaadjudicacao = {$sequencial}";
        $result = $clhomologacaoadjudica->sql_record($clhomologacaoadjudica->sql_query_itens_comhomologacao(null,$campos,"pc11_seq,z01_nome",$sWhere));

        for ($iCont = 0; $iCont < pg_num_rows($result); $iCont++) {
            $oResult = db_utils::fieldsMemory($result, $iCont);

            $nTotalItens += $oResult->pc23_valor;
        }
    }

    $resultLici = $clliclicita->sql_record($clliclicita->sql_query(null,"*","","l20_codigo = $codigo_preco"));
    $resultLici = db_utils::fieldsMemory($resultLici, 0);
    $tipojulgamento = $resultLici->l20_tipojulg;
    $oLibDocumento->l20_edital = $resultLici->l20_edital;
    $oLibDocumento->l20_anousu = $resultLici->l20_anousu;
    $oLibDocumento->l20_objeto = $resultLici->l20_objeto;
    $oLibDocumento->l44_descricao = strtoupper($resultLici->l44_descricao);
    $oLibDocumento->l03_descr = $resultLici->l03_descr;
    $oLibDocumento->l20_numero = $resultLici->l20_numero;
    $oLibDocumento->z01_nome = $nome;
    $sql1 = "select * from cgm where z01_nome like '$nome'";
    $rsResul12 = db_query($sql1);
    $resultcpf = db_utils::fieldsMemory($rsResul12, 0);
    $oLibDocumento->z01_cpf = $resultcpf->z01_cgccpf;
    $oLibDocumento->valor_total = db_formatar($nTotalItens,"f");

    $aParagrafos = $oLibDocumento->getDocParagrafos();


    $data = "Data: ";

    $mPDF = new Relatorio('', 'A4-L', 8, "arial", 7, 7, 50);

    $mPDF
        ->addInfo($head3, 2)
        ->addInfo($head5, 4)
        ->addInfo($head8, 7);

    ob_start();

?>

    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/html">

    <head>
        <title>Relat�rio</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--        <link rel="stylesheet" type="text/css" href="estilos/relatorios/padrao.style.css">-->
        <style type="text/css">
            .content {
                width: 1070px;
            }

            .table {
                font-size: 10px;
                /*background: url("imagens/px_preto.jpg") repeat center;*/
                background-repeat: repeat-y;
                background-position: 0 50px;
                height: 30px;
                border: 0px solid black !important;
            }
            .cabecalho1{
                margin-left: 35%;
            }

            .col-item {
                width: 45px;
            }

            .col-descricao_item {
                width: 550px;
            }

            .col-valor_un {
                width: 80px;
                padding-right: 5px;
            }

            .col-quant {
                width: 60px;
            }

            .col-un {
                width: 45px;
            }

            .col-total {
                width: 90px;
                padding-left: 5px;
            }

            .col-valor_total-text {
                width: 925px;
                padding-left: 5px;
            }

            .col-valor_total-valor {
                width: 120px;
                padding-right: 5px;
            }

            .row .col-un,
            .row .col-total,
            .row .col-quant,
            .row .col-valor_un,
            .row .col-valor_un {}

            .linha-vertical {
                border-top: 2px solid;
                text-align: center;
                margin-top: 80px;
                margin-left: 19%;
                width: 50%;
                line-height: 1.3em;
            }

            .col-descricao-item-layout-antigo {
                width: 245px;
            }


            .item-menu {
                border: 1px solid #000000;
                text-align: center;
                font-weight: bold;
            }

            .item-text-descricao {
                border: 1px solid #000000;
                text-align: justify;
            }

            .item-text {
                border: 1px solid #000000;
                text-align: center;
            }

            .item-text-total {
                font-weight: bold;
            }

            .item-menu-color {
                background: #f5f5f0;
                font-weight: bold;
            }

            .item-total-color {
                background: #f5f5f0;
                font-weight: bold;
                width: 935px;
            }

            td
        </style>
    </head>

    <body>
    <h2 style="text-align: center;">HOMOLOGA��O DE PROCESSO</h2>
            <?php
            foreach ($aParagrafos as $oParag) {
                $texto = $oParag->oParag->db02_texto;
            }

            $textoP = explode("\n",$texto);
            for($i=0;$i<count($textoP);$i++){
                echo "<strong>$textoP[$i]</strong>";
                echo"<br>";
            }
            echo"<br>";
            ?>
            <table border="1">
            <?php
            $nTotalItens = 0;
            $lote = FALSE;
            $sem_percentual = FALSE;
            $com_percentual = FALSE;
            $mostra_homologado_geral = FALSE;
            $somaTotalHomologados = 0;

            $campos = "DISTINCT pc01_codmater,pc01_descrmater,pc01_complmater,pc01_tabela,pc01_taxa,cgmforncedor.z01_nome,cgmforncedor.z01_cgccpf,m61_descr,m61_abrev,pc11_quant,pc23_valor,pc23_obs,pcorcamval.pc23_vlrun,pcorcamval.pc23_percentualdesconto,pcorcamval.pc23_perctaxadesctabela,l203_homologaadjudicacao,pc81_codprocitem,l04_descricao,pc11_seq,l20_criterioadjudicacao,liclicitem.l21_ordem AS ordem";
            $sWhere = " liclicitem.l21_codliclicita = {$codigo_preco} and pc24_pontuacao = 1 AND itenshomologacao.l203_homologaadjudicacao = {$sequencial}";
            $result = $clhomologacaoadjudica->sql_record($clhomologacaoadjudica->sql_query_itens_comhomologacao(null,$campos,"pc11_seq,z01_nome",$sWhere));
            $array1 = array();
            $op = 0;

            for ($iCont = 0; $iCont < pg_num_rows($result); $iCont++) {
                $oResult = db_utils::fieldsMemory($result, $iCont);
                $verifica = 0;
                if($array1==""){
                    $array1[$op][1] = $oResult->z01_cgccpf;
                    $array1[$op][2] = $oResult->z01_nome;
                    $array1[$op][3] = $oResult->l04_descricao;
                    $op++;
                }else{
                    for($j=0;$j<$op;$j++){
                        if($array1[$j][1]==$oResult->z01_cgccpf){
                            $verifica = 1;
                        }
                    }
                    if($verifica==0){
                        $array1[$op][1] = $oResult->z01_cgccpf;
                        $array1[$op][2] = $oResult->z01_nome;
                        $array1[$op][3] = $oResult->l04_descricao;
                        $op++;
                    }
                }

            }

        for($j=0;$j<$op;$j++){

            if(strlen($array1[$j][1])==14){
                $bloco_1 = substr($array1[$j][1],0,2);
                $bloco_2 = substr($array1[$j][1],2,3);
                $bloco_3 = substr($array1[$j][1],5,3);
                $bloco_4 = substr($array1[$j][1],8,4);
                $digito_verificador = substr($array1[$j][1],-2);
                $cpf_cnpj_formatado = "CNPJ ".$bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;

            }else if(strlen($array1[$j][1])==11){
                $bloco_1 = substr($array1[$j][1],0,3);
                $bloco_2 = substr($array1[$j][1],3,3);
                $bloco_3 = substr($array1[$j][1],6,3);
                $dig_verificador = substr($array1[$j][1],-2);
                $cpf_cnpj_formatado = "CPF ".$bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;

            }

            ?>
        <br>
            <?php
            $colspan = getColspan($tipojulgamento, $oResult->l20_criterioadjudicacao);
            ?>

            <tr >
                <th colspan="<?php echo $colspan; ?>">
                    <?php echo  $array1[$j][2]." - ".$cpf_cnpj_formatado ?>
                </th>
            </tr>

            <?php
                if (isCriterioAdjucacaoOutrosTipoJulgamentoLote($tipojulgamento, $oResult->l20_criterioadjudicacao) ||
                    isCriterioAdjucacaoDescontoMenorTaxaPercTipoJulgamentoLote($tipojulgamento, $oResult->l20_criterioadjudicacao)
                ) {
                    $lote = TRUE;
                    getCabecalhoTabelaLinhaLote($array1[$j][3], $colspan, 'word');
                }

                if (isCriterioAdjucacaoTipoJulgamentoCabecalhoSemPercentual($tipojulgamento, $oResult->l20_criterioadjudicacao)) {
                    $outros = TRUE;
                    $mostra_homologado_geral = TRUE;
                    getCabecalhoTabelaLayout('word');
                } elseif (isCriterioAdjucacaoTipoJulgamentoCabecalhoComPercentual($tipojulgamento, $oResult->l20_criterioadjudicacao)) {
                    $outros = TRUE;
                    $mostra_homologado_geral = TRUE;
                    getCabecalhoTabelaLayoutComPercentual('word');
                } else {
                    getCabecalhoTabelaLayoutAntigo('word');
                }
            ?>

            <?php
            $nTotalItens = 0;
            $valor = 0;
            $controle = 0;
            for ($iCont = 0; $iCont < pg_num_rows($result); $iCont++) {
                $oDadosDaLinha = new stdClass();
                $oResult = db_utils::fieldsMemory($result, $iCont);

                if ($array1[$j][1]==$oResult->z01_cgccpf) {
                    $nTotalItens += $oResult->pc23_valor;
                    $valor += $oResult->pc23_valor;
                    $oDadosDaLinha = new stdClass();
                    $oDadosDaLinha->seq = $iCont + 1;
                    $oDadosDaLinha->item = $oResult->pc01_codmater;
                    $oDadosDaLinha->descricao = strtoupper($oResult->pc01_descrmater) . " " . strtoupper($oResult->pc01_complmater);
                    $oDadosDaLinha->unidadeDeMedida = strtoupper($oResult->m61_abrev);
                    $oDadosDaLinha->marca = $oResult->pc23_obs == "" ? "-" : strtoupper($oResult->pc23_obs);
                    $oDadosDaLinha->quantidade = $oResult->pc11_quant;
                    $oDadosDaLinha->valorUnitario = "R$" . number_format($oResult->pc23_vlrun, $oGet->quant_casas, ",", ".");
                    $oDadosDaLinha->total = number_format($oResult->pc23_valor, 2, ",", ".");

                    if ($oResult->pc01_tabela == "t" || $oResult->pc01_taxa == "t") {
                        if ($oResult->pc01_tabela == "t") {
                            $oDadosDaLinha->percentual = $oResult->pc23_perctaxadesctabela . "%";
                        } else {
                            $oDadosDaLinha->percentual =  $oResult->pc23_percentualdesconto . "%";
                        }

                        $oDadosDaLinha->valorUnitario = '-';
                    } else {
                        $oDadosDaLinha->percentual = "-";
                    }

                    if (isCriterioAdjucacaoTipoJulgamentoCabecalhoSemPercentual($tipojulgamento, $oResult->l20_criterioadjudicacao)) {
                        getDadosTabelaLayout($oResult, $oDadosDaLinha, 'word');
                    } elseif (isCriterioAdjucacaoTipoJulgamentoCabecalhoComPercentual($tipojulgamento, $oResult->l20_criterioadjudicacao)) {
                        getDadosTabelaLayoutComPercentual($oResult, $oDadosDaLinha, 'word');
                    } else {
                        getDadosTabelaLayoutAntigo($oDadosDaLinha, 'word');
                    }
                }
            }

            if ($mostra_homologado_geral) {
                $somaTotalHomologados += $nTotalItens;
                getTabelaLinhaTotalHomologado($nTotalItens, $colspan, 'word');
            } else {
                getTabelaLinhaSemTotalHomologado($valor, $nTotalItens, $colspan, 'word');
            }
        }
    ?>
            <?php
                if ($mostra_homologado_geral) {
                    getTabelaLinhaTotalGeral($somaTotalHomologados, $colspan, 'word');
                }
            ?>
</table>
<?php
$data = date('d/m/Y');
        $data = explode("/",$data);
        $anousu = date("Y",db_getsession("DB_datausu"));
	    $mesusu = date("m",db_getsession("DB_datausu"));
	    $diausu = date("d",db_getsession("DB_datausu"));

        switch ($mesusu) {
            case 1:
                $mes = "Janeiro";
                break;

            case 2:
                $mes = "Fevereiro";
                break;

            case 3:
                $mes = "Mar�o";
                break;

            case 4:
                $mes = "Abril";
                break;

            case 5:
                $mes = "Maio";
                break;

            case 6:
                $mes = "Junho";
                break;

            case 7:
                $mes = "Julho";
                break;

            case 8:
                $mes = "Agosto";
                break;

            case 9:
                $mes = "Setembro";
                break;

            case 10:
                $mes = "Outubro";
                break;

            case 11:
                $mes = "Novembro";
                break;

            case 12:
                $mes = "Dezembro";
                break;

        }

        $resultado = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit')));
        $resultado = db_utils::fieldsMemory($resultado, 0);

        ?>
        <br>
        <br>
        <div style="text-align: right;">
        <? echo $resultado->munic;?>, <?echo $diausu?> de <?echo $mes;?> de <?echo $anousu;?>
        </div>

        <?php

$chars = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
$byChars = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');

$dadosAssinatura = explode('\n', $sAssinaturaCotacao);
$sCotacao = '';

//if (count($dadosAssinatura) > 1) {
    $sCotacao = '<div class="linha-vertical">';
    //for ($count = 0; $count < count($dadosAssinatura); $count++) {
        $sCotacao .= "<strong>" . strtoupper($nome) . "</strong>";
        //$sCotacao .= $count ? '' : "<br/>";
    //}
    $sCotacao .= "</div>";
    echo <<<HTML
    $sCotacao
HTML;


?>


</body>

</html>
<?php

header("Content-type: application/vnd.ms-word; charset=UTF-8");
header("Content-Disposition: attachment; Filename=HOMOLOGACAO_LICITACAO_" . $codigo_preco . ".doc");
?>
