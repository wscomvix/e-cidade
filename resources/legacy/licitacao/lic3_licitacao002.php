<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *  junto com este programa; se n�o, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("dbforms/verticalTab.widget.php");

$oDaoLicitacao = new cl_liclicita;
$oDaoRotulo    = new rotulocampo;
$clliclicitaedital = new cl_liclicitaedital;
$clliclicitaata = new cl_liclicitaata;
$clliclicitaminuta = new cl_liclicitaminuta;
$oDaoLicitacao->rotulo->label();
$oDaoRotulo->label("nome");
$oDaoRotulo->label("l03_descr");

$oGet = db_utils::postMemory($_GET);

$sSqlBuscaLicitacao = $oDaoLicitacao->sql_query_file($l20_codigo);
$rsLicitacao        = $oDaoLicitacao->sql_record($sSqlBuscaLicitacao);

$sSqlTribunal = $oDaoLicitacao->getTipocomTribunal($l20_codigo);
$rsTribunal = db_query($sSqlTribunal);
$tipoTribunal = db_utils::fieldsMemory($rsTribunal, 0)->l03_pctipocompratribunal;


if ($oDaoLicitacao->numrows == 0) {

    db_redireciona('db_erros.php?fechar=true&db_erro=Este registro n�o possui licita��o.');
    exit;
} else {
    $oLicitatacao = db_utils::fieldsMemory($rsLicitacao, 0);
}


$dtSituacao            = "";
$oDaoLicitacaoSituacao = db_utils::getDao("liclicitasituacao");
$sWhere                = " l11_liclicita       = " . $oLicitatacao->l20_codigo;
$sWhere               .= " and l11_licsituacao = " . $oLicitatacao->l20_licsituacao;
$sOrder                = " l11_sequencial desc limit 1 ";
$sSqlDataSituacao      = $oDaoLicitacaoSituacao->sql_query(null, "l11_data,l08_descr", $sOrder, $sWhere);
$rsDataSituacao        = $oDaoLicitacaoSituacao->sql_record($sSqlDataSituacao);
if ($oDaoLicitacaoSituacao->numrows > 0) {
    $dtSituacao = db_formatar(db_utils::fieldsMemory($rsDataSituacao, 0)->l11_data, 'd');
}

$oDaoCfLicita = db_utils::getDao("cflicita");
$sWhere                = " l03_codigo       = " . $oLicitatacao->l20_codtipocom;
$sSqlCfLicita      = $oDaoCfLicita->sql_query(null, "l03_descr", null, $sWhere);
$rsCfLicita        = $oDaoCfLicita->sql_record($sSqlCfLicita);
if ($oDaoCfLicita->numrows > 0) {
    $l03_descr = db_utils::fieldsMemory($rsCfLicita, 0)->l03_descr;
}

$oDaoDbUsuarios = db_utils::getDao("db_usuarios");
$sWhere                = " db_usuarios.id_usuario       = " . $oLicitatacao->l20_id_usucria;
$sSqlDbUsuarios      = $oDaoDbUsuarios->sql_query(null, "db_usuarios.nome", null, $sWhere);

$rsDbUsuarios        = $oDaoDbUsuarios->sql_record($sSqlDbUsuarios);
if ($oDaoDbUsuarios->numrows > 0) {
    $nome_usuario = db_utils::fieldsMemory($rsDbUsuarios, 0)->nome;
}

//$sWhere                = " db_usuarios.id_usuario = liclicita.l20_id_usucria";

//cflicita          on cflicita.l03_codigo = liclicita.l20_codtipocom";

$oDadosLicitatacao  = new licitacao($l20_codigo);
$oProcessoProtocolo = $oDadosLicitatacao->getProcessoProtocolo();
$sProcessoProtocolo = '';

if (!empty($oProcessoProtocolo)) {

    //pro3_consultaprocesso002.php?codproc=42096/2013&numero=42096/2013
    $sProcessoProtocolo = $oProcessoProtocolo->getNumeroProcesso() . "/" . $oProcessoProtocolo->getAnoProcesso();
}

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .valor {
            background-color: #FFF;
        }
    </style>
</head>

<body>
    <fieldset>
        <legend style="font-weight: bolder;"> Dados da Licita��o</legend>

        <table style="" border='0'>

            <tr>
                <td title="<?= $Tl20_codigo ?>">
                    <?php echo $Ll20_codigo; ?>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo $oLicitatacao->l20_codigo; ?>
                </td>
                <td nowrap="nowrap" title="Numero do Processo">
                    <?php
                    if ($sProcessoProtocolo != '') {

                        db_ancora("N�mero do Processo:", "consultaProcesso('{$sProcessoProtocolo}')", 1);
                    } else {
                        echo "<b> N�mero do Processo: </b>";
                    }
                    ?>
                </td>
                <td align='left' class="valor" colspan="3">
                    <?php echo $sProcessoProtocolo ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= $Tl20_edital ?>" style="width: 100px;">
                    <?= $Ll20_edital ?>
                </td>
                <td nowrap="nowrap" class="valor" style="width: 500px; text-align: left; ">
                    <?php echo $oLicitatacao->l20_edital; ?>
                </td>
                <td nowrap="nowrap" style=" width: 100px;">
                    <?= $Ll20_anousu ?>
                </td>
                <td nowrap="nowrap" class="valor" style="width:500px; text-align: left; " colspan="3">
                    <?php echo $oLicitatacao->l20_anousu; ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_codtipocom ?>">
                    <b>Modalidade:</b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo $oLicitatacao->l20_codtipocom . " - " . $l03_descr; ?>
                </td>
                <td nowrap="nowrap" title="<?= $Tl20_numero ?>" style="width:13%;">
                    <?= $Ll20_numero ?>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left; width:12%;">
                    <?php echo $oLicitatacao->l20_numero; ?>
                </td>
                <?php if (!in_array($tipoTribunal, array('100', '101', '102', '103')) && $oLicitatacao->l20_exercicioedital >= 2020) : ?>
                    <td nowrap="nowrap" title="<?= $Tl20_nroedital ?>" style="width: 7%;text-align:center;padding-right: 3px;">
                        <b>Edital:</b>
                    </td>
                    <td nowrap="nowrap" class="valor" style="text-align: left;">
                        <?php echo $oLicitatacao->l20_nroedital; ?>
                    </td>
                <?php endif; ?>
            </tr>
            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_datacria ?>">
                    <b>Data de Abertura do Processo licitat�rio:</b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_datacria))); ?>
                </td>
                <td nowrap="nowrap" title="<?= @$Tl20_horacria ?>">
                    <b><?= @$Ll20_horacria ?></b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;" colspan="3">
                    <?php echo $oLicitatacao->l20_horacria; ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_dataaber ?>">
                    <b>Data Emissao Edital/Convite:</b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_dataaber))); ?>
                </td>
                <td nowrap="nowrap" title="<?= @$Tl20_horaaber ?>">
                    <b><?= @$Ll20_horaaber ?></b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;" colspan="3">
                    <?php echo $oLicitatacao->l20_horaaber; ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap">
                    <b>Abertura das Propostas:</b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_recdocumentacao))); ?>
                </td>
                <td nowrap="nowrap" style="text-align: left;" title="<?= @$Tl20_dtpublic ?>">
                    <b><?= @$Ll20_dtpublic ?></b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;" colspan="3">
                    <?php echo implode("/", array_reverse(explode("-", $oLicitatacao->l20_dtpublic))); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_id_usucria ?>">
                    <?= @$Ll20_id_usucria ?>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo $oLicitatacao->l20_id_usucria . " - " . $nome_usuario; ?>
                </td>

                <td nowrap="nowrap">
                    <b>Situa��o:</b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;" colspan="3">
                    <?php echo db_utils::fieldsMemory($rsDataSituacao, 0)->l08_descr; ?>
                </td>
            </tr>

            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_criterioadjudicacao ?>">
                    <?= @$Ll20_criterioadjudicacao ?>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php
                    //OC7708
                    switch ($oLicitatacao->l20_criterioadjudicacao) {
                        case 1:
                            echo "Tabela";
                            break;
                        case 2:
                            echo "Taxa";
                            break;
                        case 3:
                            echo "Outros";
                            break;
                        default:
                            echo "Outros";
                    }
                    ?>
                </td>

                <td nowrap="nowrap" title="<?= @$Tl20_usaregistropreco ?>">
                    <?= @$Ll20_usaregistropreco ?>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;" colspan="3">
                    <?php echo $oLicitatacao->l20_usaregistropreco == 't' ? 'Sim' : 'N�o'; ?>
                </td>
            </tr>

            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_local ?>">
                    <b><?= @$Ll20_local ?></b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;">
                    <?php echo $oLicitatacao->l20_local ?>
                </td>

                <td nowrap="nowrap" title="<?= @$Tl20_tipojulg ?>">
                    <b><?= @$Ll20_tipojulg ?></b>
                </td>
                <td nowrap="nowrap" class="valor" style="text-align: left;" colspan="3">
                    <?php switch ($oLicitatacao->l20_tipojulg) {
                        case 1:
                            echo "Por item";
                            break;
                        case 2:
                            echo "Global";
                            break;
                        case 3:
                            echo "Por lote";
                            break;
                        default:
                            echo "0";
                    } ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" title="<?= @$Tl20_objeto ?>">
                    <b><?= @$Ll20_objeto ?></b>
                </td>
                <td colspan="5" align='left' class="valor">
                    <?php echo $oLicitatacao->l20_objeto ?>
                </td>
            </tr>
        </table>
    </fieldset>

    <?php
    /**
     * Configuramos e exibimos as "abas verticais" (componente verticalTab)
     */
    $oVerticalTab = new verticalTab('detalhesLicitacao', 350);
    $sGetUrl      = "l20_codigo={$oGet->l20_codigo}";

    //  $oVerticalTab->add('dadosItensLicitacoes', 'Itens/Licitac�es',
    //                     "forms/db_frminfolic.php?{$sGetUrl}");
    $oVerticalTab->add(
        'dadosItensLicitacoes',
        'Itens/Licitac�es',
        "db_consultaitenslicitacao001.php?{$sGetUrl}"
    );

    $oVerticalTab->add(
        'dadosProcessosCompras',
        'Processos de Compras',
        "lic3_infolic003.php?tipo=p&{$sGetUrl}"
    );

    $oVerticalTab->add(
        'dadosSolicitacoesCompras',
        'Solicita��es de Compras',
        "lic3_infolic003.php?tipo=s&{$sGetUrl}"
    );

    $oVerticalTab->add(
        'dadosSituacoesLicitacao',
        'Situa��es da Licita��o',
        "lic3_infolic003.php?tipo=m&{$sGetUrl}"
    );
    $sWhereAnexos = "l27_liclicita = {$oGet->l20_codigo}";
    $sSqlAnexos   = $clliclicitaedital->sql_query_file(null,"*",null,$sWhereAnexos);
    $rsAnexos     = $clliclicitaedital->sql_record($sSqlAnexos);
    $iLinhasEdital = $clliclicitaedital->numrows;
    if($iLinhasEdital>0){
        $oVerticalTab->add(
            'dadosEditais',
            'Editais',
            "lic3_infolicanexo002.php?{$sGetUrl}"
        );
    }
    $sWhereAnexos = "l39_liclicita = {$oGet->l20_codigo}";
    $sSqlAnexos   = $clliclicitaata->sql_query_file(null,"*",null,$sWhereAnexos);
    $rsAnexos     = $clliclicitaata->sql_record($sSqlAnexos);
    $iLinhasAta = $clliclicitaata->numrows;
    if ( $iLinhasAta > 0 ){
        $oVerticalTab->add(
            'dadosAtas',
            'Atas',
            "lic3_infolicata002.php?{$sGetUrl}"
        );
    }
    $sWhereAnexos = "l43_liclicita = {$oGet->l20_codigo}";
    $sSqlAnexos   = $clliclicitaminuta->sql_query_file(null,"*",null,$sWhereAnexos);
    $rsAnexos     = $clliclicitaminuta->sql_record($sSqlAnexos);
    $iLinhasAnexo = $clliclicitaminuta->numrows;
    if ( $iLinhasAnexo > 0 ){
        $oVerticalTab->add(
            'dadosMinutas',
            'Minutas',
            "lic3_infolicminuta002.php?{$sGetUrl}"
        );
    }
    $oVerticalTab->add(
        'dadosAcordo',
        'Acordos',
        "com3_pesquisalicitacaocontrato.php?{$sGetUrl}"
    );

    $oVerticalTab->add(
        'dadosHomologacao',
        'Homologa��o',
        "com3_pesquisahomologacao.php?{$sGetUrl}"
    );

    $oVerticalTab->add(
        'dadosResponsaveis',
        'Respons�veis pela Licita��o',
        "com3_pesquisaresponsaveis.php?{$sGetUrl}"
    );

    if (!in_array($tipoTribunal, array('100', '101', '102', '103'))){
        $oVerticalTab->add(
            'dadosComissao',
            'Comiss�o de Licita��o',
            "com3_pesquisacomissao.php?{$sGetUrl}"
        );
    }

    $oVerticalTab->add(
        'dadosParecer',
        'Parecer',
        "com3_pesquisaparecer.php?{$sGetUrl}"
    );

    $oVerticalTab->add(
        'dadosComissao',
        'Fornecedores Habilitados',
        "lic3_fornhabilitados.php?{$sGetUrl}"
    );
    if($tipoTribunal==100 || $tipoTribunal==101 || $tipoTribunal==102 || $tipoTribunal==103){
        $oVerticalTab->add(
            'dadosDispensaInexigibilidade',
            'Dispensa/Inexigibilidade',
            "lic3_infolic004.php?{$sGetUrl}"
        );
    }
    if($oLicitatacao->l20_naturezaobjeto==1 || $oLicitatacao->l20_naturezaobjeto==7){
        $oVerticalTab->add(
            'dadosEdital',
            'Edital',
            "lic3_infolicedital002.php?{$sGetUrl}"
        );
    }
    $oVerticalTab->add(
        'dadosPncp',
        'Dados PNCP',
        "lic3_dadospcnp.php?{$sGetUrl}"
    );
    $oVerticalTab->add(
        'AnexosPncp',
        'Anexos PNCP',
        "lic3_anexospncp.php?{$sGetUrl}"
    );
    $oVerticalTab->show();
    ?>

</body>

<script>
    function consultaProcesso(sProtProcesso) {

        var sUrlProcesso = "pro3_consultaprocesso002.php?numero=" + sProtProcesso; //42096/2013" ;// "lic3_licitacao002.php?l20_codigo="+iCodigoLicitacao
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_processo', sUrlProcesso, 'Consulta de Processo', true);
    }
</script>
