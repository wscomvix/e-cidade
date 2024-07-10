<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpessoalmov->rotulo->label();
$clrhtipoapos->rotulo->label();
$clrhpesrescisao->rotulo->label();
$clrhpesbanco->rotulo->label();
$clrhpespadrao->rotulo->label();
$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("r70_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("rh30_descr");
$clrotulo->label("rh30_regime");
$clrotulo->label("rh30_vinculo");
$clrotulo->label("h13_descr");
$clrotulo->label("h13_tpcont");
$clrotulo->label("db90_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("r59_descr1");
$clrotulo->label("r02_descr");
$clrotulo->label("rh20_cargo");
$clrotulo->label("rh04_descr");
$clrotulo->label("rh21_regpri");
$clrotulo->label("rh19_propi");
$clrotulo->label("rh05_empenhado");
$clrotulo->label("db83_sequencial");
$clrotulo->label("rh01_reajusteparidade");
$clrotulo->label("rh03_padraoprev");
$clrotulo->label("r02_descrprev");
$clrotulo->label("jt_nome");
$clrotulo->label("rh173_codigo");
$clrotulo->label("rh173_descricao");
$clrotulo->label("rh05_motivo");

const REGIME_CLT = 2;

if (empty($GLOBALS['rh01_tipadm'])) {
    $GLOBALS['rh01_tipadm'] = $tipadm;
}

if (isset($db_opcaoal)) {
    $db_opcao = 33;
    $db_botao = false;
} elseif (isset($opcao) && $opcao == "alterar") {
    $db_botao = true;
    $db_opcao = 2;
} elseif (isset($opcao) && $opcao == "excluir") {
    $db_opcao = 3;
    $db_botao = true;
} else {
    $db_opcao = 1;
    $db_botao = true;
    if (isset($novo) || (isset($alterar) && $sqlerro == false) ||   isset($excluir) || (isset($incluir) && $sqlerro == false)) {
        $rh02_anousu   = "";
        $rh02_mesusu   = "";
        $rh02_regist   = "";
        $rh02_codreg   = "";
        $rh02_tipsal   = "";
        $rh02_folha    = "";
        $rh02_fpagto   = "";
        $rh02_banco    = "";
        $rh02_agenc    = "";
        $rh02_agenc_d  = "";
        $rh02_contac   = "";
        $rh02_contac_d = "";
        $rh02_tbprev   = "";
        $rh02_hrsmen   = "";
        $rh02_hrssem   = "";
        $rh02_ocorre   = "";
        $rh02_ponto    = "";
        $rh02_progr    = "";
        $rh02_salari   = "";
    }
}

?>
<style>
    /* Ajusta o tamanho dos campos do fieldset da aba de movimenta��es */
    #rh02_tipcatprof,
    #rh02_salari,
    #rh02_fpagto {
        width: 200px;
    }

    #rh02_anousu,
    #rh02_regist,
    #rh02_lota,
    #rh02_codreg,
    #rh02_tpcont,
    #rh02_funcao,
    #rh20_cargo,
    #rh03_padrao,
    #rh03_padraoprev,
    #rh02_folha,
    #rh19_propi,
    #rh02_hrssem,
    #rh02_hrsmen {
        width: 65px;
    }

    #rh02_vincrais,
    #rh02_ocorre {
        width: 460px;
    }

    #rh01_reajusteparidade,
    #rh02_tipsal,
    #rh02_folha,
    #rh02_deficientefisico,
    #rh02_portadormolestia,
    #rh02_abonopermanencia {
        width: 115px;
    }

    #nomecgminstituidor {
        width: 260px;
    }

    #rh02_tipobeneficio {
        width: 417px;
    }

    #rh02_tipoparentescoinst {
        width: 329px;
    }
</style>
<form name="form1" method="post" action="" enctype="multipart/form-data">
    <center>
        <table border="0">
            <tr>
                <td align="center">
                    <fieldset>
                        <?php
                        db_input('rh02_seqpes', 6, $Irh02_seqpes, true, 'hidden', 3, "");
                        ?>
                        <table width="100%" border="0">
                            <tr>
                                <td nowrap title="<?= @$Trh02_regist ?>">
                                    <?php
                                    db_ancora(@$Lrh02_regist, "js_pesquisarh02_regist(true);", 3);
                                    ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    db_input('rh02_regist', 6, $Irh02_regist, true, 'text', 3, "");
                                    $result_nome = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh02_regist, "z01_nome"));
                                    if ($clrhpessoal->numrows > 0) {
                                        db_fieldsmemory($result_nome, 0);
                                    }
                                    ?>
                                    <?php
                                    db_input('z01_nome', 34, $Iz01_nome, true, 'text', 3, '');
                                    ?>

                                    <b>Exerc�cio:</b>
                                    <?php
                                    db_input('rh02_anousu', 4, $Irh02_anousu, true, 'text', 3, "")
                                    ?>
                                    &nbsp;<b>/</b>&nbsp;
                                    <?php
                                    db_input('rh02_mesusu', 2, $Irh02_mesusu, true, 'text', 3, "")
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend>Informa��es Contratuais</legend>
                        <?php
                        db_input('rh02_seqpes', 6, $Irh02_seqpes, true, 'hidden', 3, "");
                        ?>
                        <table width="100%" border="0">

                            <tr>
                                <td nowrap title="<?= @$Trh02_lota ?>">
                                    <?php
                                    db_ancora(@$Lrh02_lota, "js_pesquisarh02_lota(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    db_input('rh02_lota', 6, $Irh02_lota, true, 'text', $db_opcao, "onchange='js_pesquisarh02_lota(false);'");
                                    ?>
                                    <?php
                                    db_input('r70_descr', 34, $Ir70_descr, true, 'text', 3, '', '', '', "width: 205px");
                                    ?>
                                    <?
                                    db_input('recurso', 8, '', true,'text', 3, '', '', '');
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Trh02_funcao ?>" align="right">
                                    <?php
                                    db_ancora(@$Lrh02_funcao, "js_pesquisarh02_funcao(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    db_input('rh02_funcao', 6, $Irh02_funcao, true, 'text', $db_opcao, "onchange='js_pesquisarh02_funcao(false);'")
                                    ?>
                                    <?php
                                    db_input('rh37_descr', 33, $Irh37_descr, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>
                            <tr id="vinculoorigem" style="display: none">
                                <td></td>
                                <td></td>
                                <td nowrap align="right" id="torigem">
                                    <?php
                                    $opcaoorigem = 3;
                                    if ($db_opcao == 1 || $db_opcao == 2) {
                                        $opcaoorigem = $db_opcao;
                                    }
                                    db_ancora(@$Lrh21_regpri, "js_pesquisarh21_regpri(true);", $opcaoorigem);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    db_input('rh21_regpri', 6, $Irh21_regpri, true, 'hidden', $opcaoorigem, "onchange='js_pesquisarh21_regpri(false);'")
                                    ?>
                                    <?php
                                    db_input('z01_nome', 33, $Iz01_nome, true, 'hidden', 3, '', 'z01_nomeorigem')
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Trh02_codreg ?>">
                                    <?php
                                    db_ancora(@$Lrh02_codreg, "js_pesquisarh02_codreg(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    db_input('rh02_codreg', 6, $Irh02_codreg, true, 'text', $db_opcao, "onchange='js_pesquisarh02_codreg(false);'")
                                    ?>
                                    <?php
                                    db_input('rh30_regime', 2, $Irh30_regime, true, 'text', 3, '');
                                    ?>
                                    <?php
                                    db_input('rh30_descr', 28, $Irh30_descr, true, 'text', 3, '');
                                    ?>
                                    <?php
                                    db_input('rh30_vinculo', 2, $Irh30_vinculo, true, 'hidden', 3, '');
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Trh20_cargo ?>" align="right">
                                    <?php
                                    db_ancora(@$Lrh20_cargo, "js_pesquisarh20_cargo(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    db_input('rh20_cargo', 6, $Irh20_cargo, true, 'text', $db_opcao, "onchange='js_pesquisarh20_cargo(false);'");
                                    ?>
                                    <?php
                                    db_input('rh04_descr', 33, $Irh04_descr, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Trh02_tpcont ?>">
                                    <?php
                                    db_ancora(@$Lrh02_tpcont, "js_pesquisarh02_tpcont(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap id="tipoContrato">
                                    <?php
                                    db_input('rh02_tpcont', 6, $Irh02_tpcont, true, 'text', $db_opcao, "onchange='js_pesquisarh02_tpcont(false);'")
                                    ?>
                                    <?php
                                    db_input('h13_tpcont', 2, $Ih13_tpcont, true, 'text', 3, '');
                                    ?>
                                    <?php
                                    db_input('h13_descr', 28, $Ih13_descr, true, 'text', 3, '');
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Trh03_padrao ?>" align="right" id="Labelpadrao">
                                    <?php
                                    db_ancora(@$Lrh03_padrao, "js_pesquisarh03_padrao(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap id="padrao">
                                    <?php
                                    db_input('rh03_padrao', 6, $Irh03_padrao, true, 'text', $db_opcao, "onchange='js_pesquisarh03_padrao(false);'")
                                    ?>
                                    <?php
                                    db_input('r02_descr', 33, $Ir02_descr, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>
                            <tr id="trservidorinstituidor" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                                <td>
                                    <?php
                                    db_ancora("Servidor Instituidor:", "js_pesquisarh02_cgminstituidor(true);", $db_opcao);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    db_input("rh02_cgminstituidor", 6, '', true, 'text', "", "onchange='js_pesquisarh02_cgminstituidor(false);'");
                                    db_input("nomecgminstituidor", 33, true, 'text', 3);
                                    ?>
                                </td>
                            </tr>
                            <tr id="trdtfalecimento" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                                <td>
                                    <strong>Data Falecimento</strong>
                                </td>
                                <td>
                                    <?php
                                    db_inputdata('rh02_dtobitoinstituidor', @$rh02_dtobitoinstituidor_dia, @$rh02_dtobitoinstituidor_mes, @$rh02_dtobitoinstituidor_ano, true, 'text', $db_opcao, "")
                                    ?>
                                </td>
                            </tr>
                            <tr id="tipoparentesco" style="display:<?= $db_opcao == 2 ? 'table-row' : 'none' ?>;">
                                <td>
                                    <strong>Tipo do Parentesco</strong>
                                </td>
                                <td>
                                    <?php
                                    $iparentesco = array(
                                        0 => "Selecione",
                                        1 => "Filho(a)",
                                        2 => "Neto(a)",
                                        3 => "C�njuge",
                                        4 => "Filho(a)inv�lido",
                                        5 => "M�e",
                                        6 => "Pai",
                                        7 => "Vi�vo(a)",
                                        8 => "Companheiro(a)",
                                        9 => "Enteado(a)",
                                        10 => "Tutelado/curatelado(a)",
                                        11 => "Outras Situa��es"
                                    );
                                    db_select("rh02_tipoparentescoinst", $iparentesco, true, $db_opcao, "onchange='js_desctipoparentescoinst()'");
                                    ?>
                                </td>
                            </tr>
                            <tr id="desctipoparentescoinst" style="display: none">
                                <td nowrap title="<?= @$Trh02_desctipoparentescoinst ?>">
                                    <?= @$Lrh02_desctipoparentescoinst ?>
                                </td>
                                <td>
                                    <?php
                                    db_textarea("rh02_desctipoparentescoinst", 3, 43, 0, true, 'text', $db_opcao);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Trh02_tbprev ?>" id="LabelTabelaPrev">
                                    <?= @$Lrh02_tbprev ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    $result_tbprev = $clinssirf->sql_record($clinssirf->sql_query_file(null, null, " distinct cast(r33_codtab as integer)-2 as r33_codtab,r33_nome", "r33_codtab", "r33_instit = " . db_getsession("DB_instit") . " and r33_codtab between 3 and 6 and r33_mesusu=$rh02_mesusu and r33_anousu=$rh02_anousu "));
                                    db_selectrecord("rh02_tbprev", $result_tbprev, true, $db_opcao, "", "", "", "0-Nenhum...");
                                    ?>
                                </td>
                                <td align="right" nowrap id="LabelPadraoPrev">
                                    <?php
                                    db_ancora(@$Lrh03_padraoprev, "js_pesquisarh03_padraoprev(true);", $db_opcao);
                                    ?>
                                </td>
                                <td nowrap id="padrao_prev">
                                    <?php
                                    $Trh03_padraoprev = ''; // Remove title.
                                    db_input('rh03_padraoprev', 6, $Irh03_padraoprev, true, 'text', $db_opcao, "oninput='js_pesquisarh03_padraoprev(false);'", "", "#E6E4F1")
                                    ?>
                                    <?php
                                    $Tr02_descrprev = ''; // Remove title.
                                    db_input('r02_descrprev', 33, @$Ir02_descrprev, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Trh02_tipsal ?>">
                                    <?= @$Lrh02_tipsal ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    $arr_tipsal = array('M' => 'Mensal', 'Q' => 'Quinzenal', 'D' => 'Di�rio', 'H' => 'Hora');
                                    db_select("rh02_tipsal", $arr_tipsal, true, $db_opcao);
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Trh02_salari ?>" align="right">
                                    <?= @$Lrh02_salari ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    db_input('rh02_salari', 15, $Irh02_salari, true, 'text', $db_opcao, "onchange='js_validaPadraoPrevidencia(false);'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Trh02_folha ?>">
                                    <?= @$Lrh02_folha ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    $arr_folha = array('M' => 'Mensal', 'S' => 'Semanal', 'Q' => 'Quinzenal');
                                    db_select("rh02_folha", $arr_folha, true, $db_opcao);
                                    ?>
                                </td>
                                <td nowrap title="<?= @$Trh02_fpagto ?>" align="right">
                                    <?= @$Lrh02_fpagto ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    $arr_fpagto = array(
                                        '3' => 'Cr�dito em conta',
                                        '1' => 'Dinheiro',
                                        '2' => 'Cheque',
                                        '4' => 'Cheque/Pagamento Administrativo'
                                    );
                                    db_select("rh02_fpagto", $arr_fpagto, true, $db_opcao);
                                    ?>
                                </td>
                            </tr>
                            <tr id="disabpropri">
                                <td nowrap title="<?= $Trh19_propi ?>">
                                    <?php db_ancora(@$Lrh19_propi, "", 3); ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    if (isset($rh30_vinculo) && $rh30_vinculo == "A") {
                                        db_input('rh19_propi', 6, $Irh19_propi, true, 'text', 3, "");
                                    } else {
                                        db_input('rh19_propi', 6, $Irh19_propi, true, 'text', 2, "");
                                    }
                                    ?>
                                    <b>%</b>
                                </td>
                            </tr>
                            <tr id="tipoapos">
                                <td nowrap title="<?= $Trh02_rhtipoapos ?>">
                                    <?= @$Lrh02_rhtipoapos ?>
                                </td>
                                <td nowrap>
                                    <?php
                                    $sSqlRhTipoApos  = $clrhtipoapos->sql_query(null, "*", "rh88_sequencial", "");
                                    $rsSqlRhTipoApos = $clrhtipoapos->sql_record($sSqlRhTipoApos);
                                    db_selectrecord('rh02_rhtipoapos', $rsSqlRhTipoApos, true, $db_opcao, "", "", "", "", "js_verificatipoapos(this.value)");
                                    ?>
                                </td>
                                <td id="labelvalidadepensao">
                                    <?= @$Lrh02_validadepensao ?>
                                </td>
                                <td id="validadepensao">
                                    <?php
                                    db_inputdata('rh02_validadepensao', @$rh02_validadepensao_dia, @$rh02_validadepensao_mes, @$rh02_validadepensao_ano, true, 'text', $db_opcao, "")
                                    ?>
                                </td>
                            </tr>
                            <tr id="tipobeneficio" style="display: none;">
                                <td nowrap align="left">
                                    <strong>Tipo de Benef�cio:</strong>
                                </td>
                                <td>
                                    <?php
                                    $array = array('0' => 'Selecione');
                                    db_select('rh02_tipobeneficio', $array, true, $db_opcao);
                                    ?>
                                </td>
                            </tr>

                            <tr id="tipo7" style="display: none;">
                                <td>
                                    <strong>Descri��o do ato que originou o Benef�cio:</strong>
                                </td>
                                <td>
                                    <?php
                                    db_textarea('rh02_descratobeneficio', 0, 75, $Ipc01_descrmater, true, 'text', $db_opcao, "", '', '', '255');
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <fieldset id="ctnHoras">
                        <legend align="left"><b>Hor�rio de Trabalho</b></legend>
                        <center>
                            <table width="84%" align="left">
                                <tr>
                                    <td nowrap title="<?= @$Trh02_hrsmen ?>">
                                        <?= @$Lrh02_hrsmen ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh02_hrsmen', 4, $Irh02_hrsmen, true, 'text', $db_opcao, "")
                                        ?>
                                    </td>
                                    <td nowrap title="<?= @$Trh02_hrssem ?>" align="right">
                                        <?= @$Lrh02_hrssem ?>
                                    </td>
                                    <td nowrap align="left">
                                        <?php
                                        db_input('rh02_hrssem', 4, $Irh02_hrssem, true, 'text', $db_opcao, "")
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="Jornada de trabalho">
                                        <?php
                                        db_ancora('Jornada de Trabalho:', "js_pesquisarh02_jornadadetrabalho(true);", $db_opcao);
                                        ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh02_jornadadetrabalho', 8, 1, true, 'text', $db_opcao, "onchange='js_pesquisarh02_jornadadetrabalho(false);'");
                                        ?>
                                        <?php
                                        db_input('jt_nome', 34, 1, true, 'text', 3, '');
                                        ?>
                                    </td>
                                    <td align="right">
                                        <strong>Possui Hor�rio Noturno:</strong>
                                    </td>
                                    <td>
                                        <?php
                                        $aHorarioNoturno = array('f' => 'N�o', 't' => 'Sim');
                                        db_select("rh02_horarionoturno", $aHorarioNoturno, true, $db_opcao, "style='width:65';", "", "", "");
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Tipo de Jornada:</strong>
                                    </td>
                                    <td>
                                        <?php
                                        $aTipoJornada = array(
                                            '0' => 'Selecione', '2' => 'Jornada 12 x 36 (12 horas de trabalho seguidas de 36 horas ininterruptas de descanso', '3' => 'Jornada com hor�rio di�rio fixo e folga vari�vel', '4' => 'Jornada com hor�rio di�rio fixo e folga fixa (no domingo)', '5' => 'Jornada com hor�rio di�rio fixo e folga fixa (exceto no domingo)', '6' => 'Jornada com hor�rio di�rio fixo e folga fixa (em outro dia da semana), com folga adicional ', '7' => 'Turno ininterrupto de revezamento', '9' => 'Demais tipos de jornada'
                                        );
                                        db_select("rh02_tipojornada", $aTipoJornada, true, $db_opcao, "style='width:313;'", "", "", "");
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </fieldset>
                    <fieldset>
                        <legend>Informa��es Complementares</legend>
                        <center>
                            <table width="74%" align="left">
                                <tr>
                                    <td nowrap title="<?= @$Trh02_tipsal ?>">
                                        <?= @$Lrh01_reajusteparidade ?>
                                    </td>
                                    <td>
                                        <?php
                                        $oDaoReajusteParidade = db_utils::getDao('rhreajusteparidade');
                                        $sSql                 = $oDaoReajusteParidade->sql_query_file(null, '*', 'rh148_sequencial');
                                        $rsReajusteParidade   = db_query($sSql);

                                        if (!$rsReajusteParidade) {
                                            throw new DBException('Erro ao buscar os dados da tabela rhreajusteparidade.');
                                        }

                                        $aTipoReajuste     = array("0" => '');
                                        $aReajusteParidade = db_utils::getCollectionByRecord($rsReajusteParidade, false, false, true);

                                        foreach ($aReajusteParidade as $oReajusteParidade) {
                                            $aTipoReajuste["{$oReajusteParidade->rh148_sequencial}"] = $oReajusteParidade->rh148_descricao;
                                        }
                                        $aTipoReajuste["0"] = "Nenhum";

                                        db_select('rh01_reajusteparidade', $aTipoReajuste, true, $db_opcao);
                                        ?>
                                    </td>
                                    <td nowrap align="right" title="<?php echo $Trh02_diasgozoferias; ?>">
                                        <?php echo @$Lrh02_diasgozoferias; ?>
                                    </td>
                                    <td>
                                        <?php

                                        if (!isset($rh02_diasgozoferias) && $db_opcao == 1) {
                                            $rh02_diasgozoferias = 30;
                                        }

                                        db_input('rh02_diasgozoferias', 10, 1, true, 'text', $db_opcao);

                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td nowrap title="<?= @$Trh02_deficientefisico ?>" align="left">
                                        <?= @$Lrh02_deficientefisico ?>
                                    </td>
                                    <td nowrap>
                                        <?php $clrotulo->label("rh02_deficientefisico");
                                        $aDeficiente = array('f' => 'N�o', 't' => 'Sim');
                                        db_select("rh02_deficientefisico", $aDeficiente, true, $db_opcao, "onchange='js_informarTipoDeficiencia()'");
                                        ?>
                                    </td>
                                    <td nowrap title="Plano de Segrega��o da Massa" align="right">
                                        <strong>Plano de Segrega��o da Massa:</strong>
                                    </td>
                                    <td colspan="1" nowrap>
                                        <?php
                                        $aPlanSegreg = array('0' => 'Selecione', '1' => 'Fundo em capitaliza��o', '2' => 'Fundo em reparti��o', '3' => 'Mantido pelo Tesouro');
                                        db_select("rh02_plansegreg", $aPlanSegreg, true, $db_opcao, "", "", "", "");
                                        ?>
                                    </td>
                                </tr>
                                <tr id="row_rh02_tipodeficiencia" <?php echo ($GLOBALS['rh02_deficientefisico'] == 't') ? '' : 'style="display: none;"' ?>>
                                    <td nowrap title="<?= @$Trh02_tipodeficiencia ?>" align="left">
                                        <?= @$Lrh02_tipodeficiencia ?>
                                    </td>
                                    <td colspan="2" nowrap>
                                        <?php
                                        $result_tipodeficiencia = $cltipodeficiencia->sql_record($cltipodeficiencia->sql_query_file(null, "rh150_sequencial,rh150_descricao", 'rh150_sequencial asc', null));
                                        db_selectrecord("rh02_tipodeficiencia", $result_tipodeficiencia, true, $db_opcao, "style='width:115;'", "", "", "", "js_informarReab()", 1);
                                        ?>

                                        <?= @$Lrh02_laudodeficiencia ?>
                                        <?php
                                        db_input('rh02_laudodeficiencia_file', 10, 0, true, 'file', $db_opcao, "", "", "", "height: 29px;");
                                        if (!empty($GLOBALS['rh02_laudodeficiencia'])) {
                                            db_input('rh02_laudodeficiencia', 10, 0, true, 'hidden', $db_opcao, "", "", "", ""); ?> <input type="button" name="imprimir_laudodeficiencia" value="Imprimir" onclick="js_imprimir_laudo('rh02_laudodeficiencia');"> <?php
                                        }
                                                                                                                                                                            ?>
                                    </td>
                                </tr>
                                <tr id="row_rh02_cotadeficiencia" <?php echo ($GLOBALS['rh02_deficientefisico'] == 't') ? '' : 'style="display: none;"' ?>>
                                    <td nowrap title="Cota de Pessoas com Defici�ncia" align="left">
                                        <strong>Cota de Pessoas com Defici�ncia:</strong>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        $aCotaDef = array('f' => 'N�o', 't' => 'Sim');
                                        db_select("rh02_cotadeficiencia", $aCotaDef, true, $db_opcao, "style='width:115;'", "", "", "");
                                        ?>
                                    </td>

                                    <td colspan="2" nowrap title="Trabalhador Reabilitado/Readaptado" align="left">
                                        <span id="row_rh02_reabreadap" <?php echo ($GLOBALS['rh02_deficientefisico'] == 't') ? '' : 'style="display: none;"' ?>>
                                            <strong>Trabalhador Reabilitado/Readaptado:</strong>

                                            <?php
                                            $aReab = array('f' => 'N�o', 't' => 'Sim');
                                            db_select("rh02_reabreadap", $aReab, true, $db_opcao, "style='width:115;'", "", "", "");
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Trh02_portadormolestia ?>" align="left">
                                        <?= @$Lrh02_portadormolestia ?>
                                    </td>
                                    <td colspan="3" nowrap>
                                        <?php $clrotulo->label("rh02_portadormolestia ");
                                        $aMolestia = array('f' => 'N�o', 't' => 'Sim');
                                        db_select("rh02_portadormolestia", $aMolestia, true, $db_opcao, "onchange='js_informarLaudoPortadorMolestia()'");
                                        ?>

                                        <span id="laudoportadormolestia" <?php echo ($GLOBALS['rh02_portadormolestia'] == 't') ? '' : 'style="display: none;"' ?>>
                                            <?= @$Lrh02_laudoportadormolestia ?>
                                            <?php
                                            db_input('rh02_laudoportadormolestia_file', 10, 0, true, 'file', $db_opcao, "", "", "", "height: 29px;");
                                            if (!empty($GLOBALS['rh02_laudoportadormolestia'])) {
                                                db_input('rh02_laudoportadormolestia', 10, 0, true, 'hidden', $db_opcao, "", "", "", ""); ?> <input type="button" name="imprimir_laudoportadormolestia" value="Imprimir" onclick="js_imprimir_laudo('rh02_laudoportadormolestia');"> <?php
                                            } ?>

                                            <?= @$Lrh02_datalaudomolestia ?>
                                            <?php
                                            db_inputdata('rh02_datalaudomolestia', @$rh02_datalaudomolestia_dia, @$rh02_datalaudomolestia_mes, @$rh02_datalaudomolestia_ano, true, 'text', $db_opcao, "")
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $Lrh02_abonopermanencia ?>
                                    </td>
                                    <td colspan="3">
                                        <?php
                                        $aAbonoPermanencia = array('f' => 'N�o', 't' => 'Sim');
                                        db_select('rh02_abonopermanencia', $aAbonoPermanencia, true, $db_opcao, "onchange='js_abonopermanencia()'");
                                        ?>
                                        <span id="datainicio" <?php echo ($GLOBALS['rh02_abonopermanencia'] == 't') ? '' : 'style="display: none;"' ?>>

                                            <strong>Data In�cio:</strong>
                                            <?php
                                            db_inputdata('rh02_datainicio', @$rh02_datainicio_dia, @$rh02_datainicio_mes, @$rh02_datainicio_ano, true, 'text', $db_opcao, "")
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Trh02_vincrais ?>">
                                        <?= @$Lrh02_vincrais ?>
                                    </td>
                                    <td nowrap colspan="3">
                                        <?php
                                        $arr_vincrais = array(
                                            '00' => '   - Nenhum',
                                            '10' => '10 - Trab urbano vinc a empr pessoa juridica - CLT p/tempo indeterminado',
                                            '15' => '15 - Trab urbano vinc a empr pessoa fisica  - CLT p/tempo indeterminado',
                                            '20' => '20 - Trab rural vinc a empr pessoa juridica - CLT p/tempo indeterminado',
                                            '25' => '25 - Trab rural vinc a empr pessoa fisica  - CLT p/tempo indeterminado',
                                            '30' => '30 - Serv regido pelo regime juridico unico (Fed,est,munic) e militar',
                                            '31' => '31 - Serv regido pelo Regime Jur�dico �nico (fed,est,munic) e militar,vinc a RGPS',
                                            '35' => '35 - Serv publico nao-efetivo',
                                            '40' => '40 - Trabalhador avulso',
                                            '50' => '50 - Trab temporario, regido pela Lei n. 6.019 de 03.01.74',
                                            '55' => '55 - Aprendiz contratado na termos do art. 428 da CLT.',
                                            '60' => '60 - Trab urbano vinc a empr pessoa juridica - CLT p/tempo determinado',
                                            '65' => '65 - Trab urbano vinc a empr pessoa fisica - CLT p/tempo determinado',
                                            '70' => '70 - Trab rural vinc a empr pessoa juridica - CLT p/tempo determinado',
                                            '75' => '75 - Trab rural vinc a empr pessoa fisica - CLT p/tempo determinado',
                                            '80' => '80 - Diretor sem vinc empregaticio c/ recolhimento do FGTS',
                                            '90' => '90 - Contrato de trabalho p/prazo determinado Lei 9.601 CLT',
                                            '90' => '90 - Contrato de Trabalho por Tempo Determinado, reg pela Lei no. 8.745',
                                            '95' => '95 - Contrato de Trabalho por Tempo Determinado, reg pela Lei no. 8.745 e 9.849',
                                            '96' => '96 - Contrato de Trabalho por Prazo Determinado, regido por Lei Estadual',
                                            '97' => '97 - Contrato de Trabalho por Prazo Determinado, regido por Lei Municipal'
                                        );
                                        db_select("rh02_vincrais", $arr_vincrais, true, $db_opcao, "style='width:313;'");
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Trh02_ocorre ?>">
                                        <?= @$Lrh02_ocorre ?>
                                    </td>
                                    <td nowrap colspan="3">
                                        <?php
                                        $arr_ocorre = array(
                                            '' => 'Nunca esteve exposta',
                                            '01' => '01 - N�o exposto no momento, mas j� esteve',
                                            '02' => '02 - Exposta (aposentadoria esp. 15 anos)',
                                            '03' => '03 - Exposta (aposentadoria esp. 20 anos)',
                                            '04' => '04 - Exposta (aposentadoria esp. 25 anos)',
                                            '05' => '05 - Mais de um v�nculo (ou fonte pagadora) - N�o exposi��o a agente nocivo',
                                            '06' => '06 - Mais de um v�nculo (ou fonte pagadora) - Exposta (aposentadoria esp. 15 anos)',
                                            '07' => '07 - Mais de um v�nculo (ou fonte pagadora) - Exposta (aposentadoria esp. 20 anos)',
                                            '08' => '08 - Mais de um v�nculo (ou fonte pagadora) - Exposta (aposentadoria esp. 25 anos)'
                                        );
                                        db_select("rh02_ocorre", $arr_ocorre, true, $db_opcao, "style='width:313;'");
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td align="center" width="100%">
                    <div id="ctnContaBancariaServidor"></div>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <fieldset id="ctnRescisao">
                        <legend align="left"><b>Rescis�o</b></legend>
                        <center>
                            <table width="100%">
                                <tr>
                                    <td nowrap title="<?= @$Trh05_recis ?>">
                                        <?= @$Lrh05_recis ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_inputdata('rh05_recis', @$rh05_recis_dia, @$rh05_recis_mes, @$rh05_recis_ano, true, 'text', $db_opcao, "")
                                        ?>
                                    </td>
                                    <td nowrap title="<?= @$Trh05_causa ?>">
                                        <?php
                                        db_ancora(@$Lrh05_causa, "js_pesquisarh05_causa(true);", $db_opcao);
                                        ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh05_causa', 6, $Irh05_causa, true, 'text', 3, "")
                                        ?>
                                        <?php
                                        db_input('r59_descr', 40, $Ir59_descr, true, 'text', 3, "")
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td nowrap title="<?= @$Trh05_caub ?>">
                                        <?php
                                        db_ancora(@$Lrh05_caub, "", 3);
                                        ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh05_caub', 6, $Irh05_caub, true, 'text', 3, "")
                                        ?>
                                        <?php
                                        db_input('r59_descr1', 40, $Ir59_descr1, true, 'text', 3, "")
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td nowrap title="<?= @$Trh173_codigo ?>">
                                        <?php
                                        db_ancora(@$Lrh173_codigo, "js_pesquisarh173_codigo(true);", $db_opcao);
                                        ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh05_motivo', 6, $Irh05_motivo, true, 'text', 3, "")
                                        ?>
                                        <?php
                                        db_input('rh173_descricao', 40, $Irh173_descricao, true, 'text', 3, "")
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Trh05_taviso ?>">
                                        <?php
                                        db_ancora(@$Lrh05_taviso, "", 3);
                                        ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        if (!isset($rh05_taviso)) {
                                            $rh05_taviso = 3;
                                        }
                                        $x = array("1" => "Trabalhado", "2" => "Aviso indenizado", "3" => "Sem aviso");
                                        db_select('rh05_taviso', $x, true, $db_opcao, "onchange='js_disabdata(this.value);'");
                                        ?>
                                        <?php
                                        $rh05_mremun = 0;
                                        db_input('rh05_mremun', 10, $Irh05_mremun, true, 'hidden', 3, "")
                                        ?>
                                    </td>
                                    <td nowrap title="<?= @$Trh05_aviso ?>">
                                        <?= @$Lrh05_aviso ?>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_inputdata('rh05_aviso', @$rh05_aviso_dia, @$rh05_aviso_mes, @$rh05_aviso_ano, true, 'text', $db_opcao, "")
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><B>Empenhos Gerados: </B></td>
                                    <td colspan="3">
                                        <?php
                                        db_select('rh05_empenhado', array("f" => "N�o", "t" => "Sim",), 1, 1);
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td title="<?php echo $Trh05_codigoseguranca; ?>">
                                        <?php echo $Lrh05_codigoseguranca; ?>
                                    </td>
                                    <td>
                                        <?php db_input('rh05_codigoseguranca', 10, $Irh05_codigoseguranca, true, 'text', $db_opcao); ?>
                                    </td>

                                    <?php if ($rh30_regime == REGIME_CLT) { ?>
                                        <td title="<?php echo $Trh05_saldofgts; ?>" align="right">
                                            <?php echo $Lrh05_saldofgts; ?>
                                        </td>
                                        <td>
                                            <?php db_input('rh05_saldofgts', 10, $Irh05_saldofgts, true, 'text', $db_opcao); ?>
                                        </td>
                                    <?php } ?>
                                </tr>

                                <tr>
                                    <td title="<?php echo $Trh05_trct; ?>">
                                        <?php echo $Lrh05_trct; ?>
                                    </td>
                                    <td colspan="3">
                                        <?php db_input('rh05_trct', 10, $Irh05_trct, true, 'text', $db_opcao); ?>
                                        <?php db_input('db83_sequencial', 10, $Idb83_sequencial, true, 'hidden', $db_opcao, ""); ?>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <fieldset id="ctnDadosCedencia">
                        <legend align="left"><b>Dados Ced�ncia</b></legend>
                        <center>
                            <table width="100%">
                                <tr id="tipadm" <?php echo (in_array($GLOBALS['rh01_tipadm'], array('3', '4'))) ? '' : 'style="display: none;"' ?>>
                                    <td nowrap title="CNPJ Cedente">
                                        <strong>CNPJ Cedente:</strong>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh02_cnpjcedente', 14, 1, true, 'text', $db_opcao, "  onBlur='js_verificaCNPJ(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)'", '', '', '', '14');
                                        ?>
                                        <script type="text/javascript">
                                            function js_limpa(obj) {
                                                x = obj.value;
                                                y = x.replace('.', '');
                                                y = y.replace('/', '');
                                                y = y.replace('-', '');
                                                document.form1.rh02_cnpjcedente.value = y;
                                            }
                                        </script>
                                    </td>
                                    <td nowrap title="Matricula do Trabalhador no �rg�o Cedente">
                                        <strong>Matricula do Trabalhador no �rg�o Cedente:</strong>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_input('rh02_mattraborgcedente', 10, 1, true, 'text', $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                                <tr id="tipadm" <?php echo (in_array($GLOBALS['rh01_tipadm'], array('3', '4'))) ? '' : 'style="display: none;"' ?>>
                                    <td nowrap title="Data de Admiss�o no �rg�o Cedente">
                                        <strong>Data de Admiss�o no �rg�o Cedente:</strong>
                                    </td>
                                    <td nowrap>
                                        <?php
                                        db_inputdata('rh02_dataadmisorgcedente', @$rh02_dataadmisorgcedente_dia, @$rh02_dataadmisorgcedente_mes, @$rh02_dataadmisorgcedente_ano, true, 'text', $db_opcao, "")
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <fieldset id="ctnSiope">
                        <legend align="left"><b>Siope</b></legend>
                        <center>
                            <table width="100%">
                                <tr>
                                    <td nowrap title="Categoria Profissional SIOPE" align="left" colspan="2">
                                        <strong><?= @$Lrh02_tipcatprof ?> </strong>
                                        <?php
                                        $tipcatprof = array(
                                            0 => 'Nenhum',
                                            1 => 'Docente habilitado em curso de n�vel m�dio',
                                            2 => 'Docente habilitado em curso de pedagogia',
                                            3 => 'Docente habilitado em curso de licenciatura plena',
                                            4 => 'Docente habilitado em programa especial de forma��o pedag�gica de docentes',
                                            5 => 'Docente p�s-graduado em cursos de especializa��o para forma��o de docentes para educa��o profissional t�cnica de n�vel m�dio',
                                            6 => 'Docente graduado bacharel e tecn�logo com diploma de mestrado ou doutorado na �rea do componente curricular da educa��o profissional t�cnica de n�vel m�dio',
                                            7 => 'Docente professor ind�gena sem pr�via forma��o pedag�gica',
                                            8 => 'Docente instrutor, tradutor e int�rprete de libras.',
                                            9 => 'Docente professor de comunidade quilombola',
                                            10 => 'Profissionais n�o habilitados, por�m autorizados a exercer a doc�ncia em car�ter prec�rio e provis�rio na educa��o infantil e nos anos iniciais do ensino fundamental.',
                                            11 => 'Profissionais graduados, bachar�is e tecn�logos autorizados a atuar como docentes, em car�ter prec�rio e provis�rio, nos anos finais do ensino fundamental e no ensino m�dio e m�dio integrado � educa��o.',
                                            12 => 'Profissionais experientes, n�o graduados, autorizados a atuar como docentes, em car�ter prec�rio e provis�rio, no ensino m�dio e m�dio integrado � educa��o profissional t�cnica de n�vel m�dio.',
                                            13 => 'Profissionais em efetivo exerc�cio no �mbito da educa��o infantil e ensino fundamental.',
                                            14 => 'Auxiliar/Assistente Educacional',
                                            15 => 'Profissionais que exercem fun��es de secretaria escolar, alimenta��o escolar (merendeiras), multimeios did�ticos e infraestrutura.',
                                            16 => 'Profissionais que atuam na realiza��o das atividades requeridos nos ambientes de secretaria, de manuten��o em geral.',
                                        );
                                        db_select("rh02_tipcatprof", $tipcatprof, true, $db_opcao, "");
                                        ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <strong><?= @$Lrh02_segatuacao ?> </strong>
                                        <?php
                                        $segatuacao = array(
                                            0 => 'Selecione',
                                            1 => 'Creche',
                                            2 => 'Pr�-escola',
                                            3 => 'Fundamental 1',
                                            4 => 'Fundamental 2',
                                            5 => 'M�dio',
                                            6 => 'Profissional',
                                            7 => 'Administrativo',
                                            8 => 'EJA',
                                            9 => 'Especial',
                                        );
                                        db_select("rh02_segatuacao", $segatuacao, true, $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <fieldset>
                            <legend align="left"><b>Art. 61 da LDB</b></legend>
                            <table width="100%">
                                <?php
                                $aArtSiopeOpcao = array(
                                    'f' => 'N�o',
                                    't' => 'Sim',
                                );
                                $cldbsyscampo = db_utils::getDao('db_syscampo');
                                $rsArtSiopeCampos = $cldbsyscampo->sql_record($cldbsyscampo->sql_query_file(null, "descricao", "codcam", "nomecam in ('rh02_art61ldb1','rh02_art61ldb2','rh02_art61ldb3','rh02_art61ldb4','rh02_art61ldb5','rh02_art1leiprestpsiccologia','rh02_art1leiprestservsocial','rh02_art61ldboutros','rh02_art1leioutros')"));
                                $aArtSiopeCampos = db_utils::getColectionByRecord($rsArtSiopeCampos);
                                ?>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[0]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art61ldb1", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[0]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[1]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art61ldb2", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[1]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[2]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art61ldb3", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[2]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[3]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art61ldb4", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[3]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[4]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art61ldb5", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[4]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[7]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art61ldboutros", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[7]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                            </table>
                            </fieldset>

                            <fieldset>
                                <legend align="left"><b>Art. 1 da Lei n� 13.935/2019</b></legend>
                            <table width="100%">
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $Trh02_art1leiprestpsiccologia; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art1leiprestpsiccologia", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[5]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $Trh02_art1leiprestservsocial; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art1leiprestservsocial", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[6]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap" title="<?php echo $aArtSiopeCampos[8]->descricao; ?>" align="left" colspan="2">
                                        <?php
                                        db_select("rh02_art1leioutros", $aArtSiopeOpcao, true, $db_opcao);
                                        ?>
                                        <strong><?= @substr($aArtSiopeCampos[8]->descricao, 0, 140) ?> </strong>
                                    </td>
                                </tr>
                            </table>
                            </fieldset>
                            <center>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <input type="hidden" name="tipadm" value="<?= $GLOBALS['rh01_tipadm'] ?>">
                    <input type="hidden" name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" />
                    <input type="button" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> <?php if ($db_opcao != 3) {
                                            echo "onclick='js_validaDados();'";
                                        } ?>>
                </td>
            </tr>
        </table>

        <?php
        db_app::load("DBHint.widget.js");

        $lExcluir = 'false';

        if ($db_opcao == 3 || $db_opcao == 33) {
            $lExcluir = 'true';
        }
        ?>

        <script>
            var sMensagem = 'recursoshumanos.pessoal.db_frmrhpessoalmov.';

            /**
             * Instancia componente de dados da conta banc�ria
             */
            var oContaBancariaServidor = new DBViewContaBancariaServidor($F('db83_sequencial'), 'oContaBancariaServidor', <?= $lExcluir ?>);
            oContaBancariaServidor.show('ctnContaBancariaServidor');
            oContaBancariaServidor.getDados($F('db83_sequencial'));

            /**
             * valida antes de colar no campo valor
             */

            $('inputCodigoBanco').onpaste = function(event) {
                return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
            }

            $('inputDvConta').onpaste = function(event) {
                return /^[0-9|.xX]+$/.test(event.clipboardData.getData('text/plain'));
            }

            $('inputDvAgencia').onpaste = function(event) {
                return /^[0-9|.xX]+$/.test(event.clipboardData.getData('text/plain'));
            }
            $('inputNumeroAgencia').onpaste = function(event) {
                return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
            }

            $('inputOperacao').onpaste = function(event) {
                return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
            }

            $('inputNumeroConta').onpaste = function(event) {
                return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
            }

            $('inputOperacao').onkeyup = function(event) {
                return js_ValidaCampos(this, 1, 'C�digo da Opera��o', false, false, event);
            }

            function js_disabledtipoapos(vinculo) {

                var sVinculo = vinculo;

                if (sVinculo != "") {
                    if (sVinculo != "A") {

                        document.getElementById("tipoapos").style.display = "";
                        document.getElementById("rh02_rhtipoapos").disabled = false;
                        document.getElementById("rh02_rhtipoaposdescr").disabled = false;

                        if (sVinculo == 'P') {

                            document.getElementById("labelvalidadepensao").style.display = "";
                            document.getElementById("validadepensao").style.display = "";
                            document.getElementById("rh02_validadepensao").disabled = false;
                            document.form1.dtjs_rh02_validadepensao.disabled = false;
                        } else {

                            document.getElementById("labelvalidadepensao").style.display = "none";
                            document.getElementById("validadepensao").style.display = "none";
                            document.getElementById("rh02_validadepensao").disabled = true;
                            document.form1.dtjs_rh02_validadepensao.disabled = true;
                        }
                    } else {

                        document.getElementById("tipoapos").style.display = "none";
                        document.getElementById("tipobeneficio").style.display = "none";
                        document.getElementById("tipo7").style.display = "none";
                        document.getElementById("rh02_tipobeneficio").value = "0";
                        document.getElementById("rh02_descratobeneficio").value = '';
                        document.getElementById("labelvalidadepensao").style.display = "none";
                        document.getElementById("validadepensao").style.display = "none";
                        document.getElementById("rh02_rhtipoapos").disabled = true;
                        document.getElementById("rh02_rhtipoaposdescr").disabled = true;
                        document.getElementById("rh02_validadepensao").disabled = true;
                        document.form1.dtjs_rh02_validadepensao.disabled = true;
                    }
                } else {

                    document.getElementById("tipoapos").style.display = "none";
                    document.getElementById("labelvalidadepensao").style.display = "none";
                    document.getElementById("validadepensao").style.display = "none";
                    document.getElementById("rh02_rhtipoapos").disabled = true;
                    document.getElementById("rh02_rhtipoaposdescr").disabled = true;
                    document.getElementById("rh02_validadepensao").disabled = true;
                    document.form1.dtjs_rh02_validadepensao.disabled = true;
                }
            }

            function js_disabdata(valor) {
                if (valor == 1) {
                    document.form1.dtjs_rh05_aviso.disabled = false;
                    document.form1.rh05_aviso_dia.readOnly = false;
                    document.form1.rh05_aviso_mes.readOnly = false;
                    document.form1.rh05_aviso_ano.readOnly = false;

                    document.form1.rh05_aviso_dia.style.backgroundColor = '';
                    document.form1.rh05_aviso_mes.style.backgroundColor = '';
                    document.form1.rh05_aviso_ano.style.backgroundColor = '';
                } else {
                    document.form1.dtjs_rh05_aviso.disabled = true;
                    document.form1.rh05_aviso_dia.readOnly = true;
                    document.form1.rh05_aviso_mes.readOnly = true;
                    document.form1.rh05_aviso_ano.readOnly = true;

                    document.form1.rh05_aviso_dia.style.backgroundColor = '#DEB887';
                    document.form1.rh05_aviso_mes.style.backgroundColor = '#DEB887';
                    document.form1.rh05_aviso_ano.style.backgroundColor = '#DEB887';

                    document.form1.rh05_aviso_dia.value = "";
                    document.form1.rh05_aviso_mes.value = "";
                    document.form1.rh05_aviso_ano.value = "";
                }
            }

            function js_pesquisarh02_funcao(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhfuncao', 'func_rhfuncao.php?funcao_js=parent.js_mostrarhfuncao1|rh37_funcao|rh37_descr&instit=<?= db_getsession("DB_instit") ?>', 'Pesquisa', true, '0');
                } else {
                    if (document.form1.rh02_funcao.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhfuncao', 'func_rhfuncao.php?pesquisa_chave=' + document.form1.rh02_funcao.value + '&funcao_js=parent.js_mostrarhfuncao&instit=<?= db_getsession("DB_instit") ?>', 'Pesquisa', false, '0');
                    } else {
                        document.form1.rh37_descr.value = '';
                    }
                }
            }

            function js_mostrarhfuncao(chave, erro) {
                document.form1.rh37_descr.value = chave;
                if (erro == true) {
                    document.form1.rh02_funcao.focus();
                    document.form1.rh02_funcao.value = '';
                }
            }

            function js_mostrarhfuncao1(chave1, chave2) {
                document.form1.rh02_funcao.value = chave1;
                document.form1.rh37_descr.value = chave2;
                db_iframe_rhfuncao.hide();
            }

            function js_pesquisarh02_jornadadetrabalho(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframejornadadetrabalho', 'func_jornadadetrabalho.php?funcao_js=parent.js_mostrajornadadetrabalho1|jt_sequencial|jt_nome&instit=<?= (db_getsession("DB_instit")) ?>', 'Pesquisa', true, '0');
                } else {
                    if (document.form1.rh02_jornadadetrabalho.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframejornadadetrabalho', 'func_jornadadetrabalho.php?pesquisa_chave=' + document.form1.rh02_jornadadetrabalho.value + '&funcao_js=parent.js_mostrajornadadetrabalho&instit=<?= (db_getsession("DB_instit")) ?>', 'Pesquisa', false, '0');
                    } else {
                        document.form1.jt_nome.value = '';
                    }
                }
            }

            function js_mostrajornadadetrabalho(chave1, chave2, erro) {
                document.form1.rh02_jornadadetrabalho.value = chave1;
                document.form1.jt_nome.value = chave2;
                if (erro == true) {
                    document.form1.rh02_jornadadetrabalho.focus();
                    document.form1.rh02_jornadadetrabalho.value = '';
                    document.form1.jt_nome.value = '';
                }
            }

            function js_mostrajornadadetrabalho1(chave1, chave2) {
                document.form1.rh02_jornadadetrabalho.value = chave1;
                document.form1.jt_nome.value = chave2;
                db_iframejornadadetrabalho.hide();
            }

            function js_pesquisarh02_lota(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframerhlota', 'func_rhlota.php?funcao_js=parent.js_mostrarhlota1|r70_codigo|r70_descr|recurso&instit=<?= (db_getsession("DB_instit")) ?>', 'Pesquisa', true, '0');
                } else {
                    if (document.form1.rh02_lota.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframerhlota', 'func_rhlota.php?pesquisa_chave=' + document.form1.rh02_lota.value + '&funcao_js=parent.js_mostrarhlota&instit=<?= (db_getsession("DB_instit")) ?>', 'Pesquisa', false, '0');
                    } else {
                        document.form1.r70_descr.value = '';
                    }
                }
            }

            function js_mostrarhlota(chave, erro) {
                document.form1.r70_descr.value = chave;
                if (erro == true) {
                    document.form1.rh02_lota.focus();
                    document.form1.rh02_lota.value = '';
                } else {
                    js_verifica_lotacao();
                }
            }

            function js_mostrarhlota1(chave1, chave2, chave3) {
                document.form1.rh02_lota.value = chave1;
                document.form1.r70_descr.value = chave2;
                document.form1.recurso.value = chave3;
                db_iframerhlota.hide();
                js_verifica_lotacao();
            }

            function js_pesquisarh21_regpri(mostra) {

                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhpessoal', 'func_rhpessoal.php?lTodos=1&funcao_js=parent.js_mostraorigem1|rh01_regist|z01_nome', 'Pesquisa', true, 0);
                } else {

                    if (document.form1.rh21_regpri.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhpessoal', 'func_rhpessoal.php?lTodos=1&pesquisa_chave=' + document.form1.rh21_regpri.value + '&funcao_js=parent.js_mostraorigem', 'Pesquisa', false, 0);
                    } else {
                        document.form1.z01_nomeorigem.value = '';
                    }
                }
            }

            function js_mostraorigem(chave, erro) {
                document.form1.z01_nomeorigem.value = chave;
                if (erro == true) {
                    document.form1.rh21_regpri.focus();
                    document.form1.rh21_regpri.value = '';
                }
            }

            function js_mostraorigem1(chave1, chave2) {
                document.form1.rh21_regpri.value = chave1;
                document.form1.z01_nomeorigem.value = chave2;
                db_iframe_rhpessoal.hide();
            }

            function js_pesquisarh03_padrao(mostra) {
                if (document.form1.rh30_regime.value != "") {
                    if (js_validaPadraoPrevidencia(false)) {
                        if (mostra == true) {
                            js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_padroes', 'func_padroes.php?funcao_js=parent.js_mostrapadrao1|r02_codigo|r02_descr&regime=' + document.form1.rh30_regime.value + '&chave_r02_anousu=' + document.form1.rh02_anousu.value + '&chave_r02_mesusu=' + document.form1.rh02_mesusu.value, 'Pesquisa', true, '0');
                        } else {
                            if (document.form1.rh03_padrao.value != '') {
                                js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_padroes', 'func_padroes.php?pesquisa_chave=' + document.form1.rh03_padrao.value + '&funcao_js=parent.js_mostrapadrao&regime=' + document.form1.rh30_regime.value + '&chave_r02_anousu=' + document.form1.rh02_anousu.value + '&chave_r02_mesusu=' + document.form1.rh02_mesusu.value, 'Pesquisa', false, '0');
                            } else {
                                document.form1.rh03_padrao.value = '';
                                document.form1.r02_descr.value = '';
                            }
                        }
                    }
                } else {
                    alert(_M(sMensagem + 'erro_regime'));
                    document.form1.rh03_padrao.value = '';
                    document.form1.r02_descr.value = '';
                }
            }

            function js_mostrapadrao(chave, erro) {
                document.form1.r02_descr.value = chave;
                if (erro == true) {
                    document.form1.rh03_padrao.focus();
                    document.form1.rh03_padrao.value = '';
                }
            }

            function js_mostrapadrao1(chave1, chave2) {
                document.form1.rh03_padrao.value = chave1;
                document.form1.r02_descr.value = chave2;
                db_iframe_padroes.hide();
            }

            function js_pesquisarh03_padraoprev(mostra) {
                if (document.form1.rh30_regime.value != "") {
                    if (js_validaPadraoPrevidencia(true)) {
                        if (mostra == true) {
                            js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_padroes', 'func_padroes.php?funcao_js=parent.js_mostrapadrao_prev1|r02_codigo|r02_descr&regime=' + document.form1.rh30_regime.value + '&chave_r02_anousu=' + document.form1.rh02_anousu.value + '&chave_r02_mesusu=' + document.form1.rh02_mesusu.value, 'Pesquisa', true, '0');
                        } else {
                            if (document.form1.rh03_padraoprev.value != '') {
                                js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_padroes', 'func_padroes.php?pesquisa_chave=' + document.form1.rh03_padraoprev.value + '&funcao_js=parent.js_mostrapadrao_prev&regime=' + document.form1.rh30_regime.value + '&chave_r02_anousu=' + document.form1.rh02_anousu.value + '&chave_r02_mesusu=' + document.form1.rh02_mesusu.value, 'Pesquisa', false, '0');
                            } else {
                                document.form1.rh03_padraoprev.value = '';
                                document.form1.r02_descrprev.value = '';
                            }
                        }
                    }
                } else {
                    alert(_M(sMensagem + 'erro_regime'));
                    document.form1.rh03_padraoprev.value = '';
                    document.form1.r02_descrprev.value = '';
                }
            }

            function js_mostrapadrao_prev(chave, erro) {
                document.form1.r02_descrprev.value = chave;
                if (erro == true) {
                    document.form1.rh03_padraoprev.focus();
                    document.form1.rh03_padraoprev.value = '';
                }
            }

            function js_mostrapadrao_prev1(chave1, chave2) {
                document.form1.rh03_padraoprev.value = chave1;
                document.form1.r02_descrprev.value = chave2;
                db_iframe_padroes.hide();
            }

            function js_pesquisarh02_tpcont(mostra) {
                if (document.form1.rh30_regime.value != "") {
                    if (mostra == true) {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_tpcontra', 'func_tpcontra.php?funcao_js=parent.js_mostratpcontra1|h13_codigo|h13_descr|h13_tpcont&regime=' + document.form1.rh30_regime.value, 'Pesquisa', true, '0');
                    } else {
                        if (document.form1.rh02_tpcont.value != '') {
                            js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_tpcontra', 'func_tpcontra.php?pesquisa_chave=' + document.form1.rh02_tpcont.value + '&funcao_js=parent.js_mostratpcontra&regime=' + document.form1.rh30_regime.value, 'Pesquisa', false, '0');
                        } else {
                            document.form1.rh02_tpcont.value = '';
                            document.form1.h13_descr.value = '';
                            document.form1.h13_tpcont.value = '';
                        }
                    }
                } else {
                    alert(_M(sMensagem + 'erro_regime'));
                    document.form1.rh02_tpcont.value = '';
                    document.form1.h13_descr.value = '';
                    document.form1.h13_tpcont.value = '';
                }
            }

            function js_mostratpcontra(chave, chave2, erro) {
                document.form1.h13_descr.value = chave;
                if (erro == true) {
                    document.form1.rh05_causa.focus();
                    document.form1.rh02_tpcont.value = '';
                    document.form1.h13_tpcont.value = '';
                } else {
                    document.form1.h13_tpcont.value = chave2;
                }
            }

            function js_mostratpcontra1(chave1, chave2, chave3) {
                document.form1.rh02_tpcont.value = chave1;
                document.form1.h13_descr.value = chave2;
                document.form1.h13_tpcont.value = chave3;
                db_iframe_tpcontra.hide();
            }

            function js_pesquisarh05_causa(mostra) {
                if (document.form1.rh02_codreg.value != "") {
                    if (mostra == true) {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rescisao', 'func_rescisaoalt.php?funcao_js=parent.js_mostrarescisao1|r59_causa|r59_descr|r59_caub|r59_descr1&chave_r59_anousu=<?= $rh02_anousu ?>&chave_r59_mesusu=<?= $rh02_mesusu ?>&regime=' + document.form1.rh02_codreg.value, 'Pesquisa', true, '0');
                    } else {
                        if (document.form1.rh05_causa.value != '') {
                            js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rescisao', 'func_rescisaoalt.php?pesquisa_chave=' + document.form1.rh05_causa.value + '&funcao_js=parent.js_mostrarescisao&ano=<?= $rh02_anousu ?>&mes=<?= $rh02_mesusu ?>&regime=' + document.form1.rh02_codreg.value, 'Pesquisa', false, '0');
                        } else {
                            document.form1.rh05_caub.value = '';
                            document.form1.r59_descr.value = '';
                            document.form1.r59_descr1.value = '';
                        }
                    }
                } else {
                    alert(_M(sMensagem + 'erro_regime'));
                    document.form1.rh05_causa.value = '';
                    document.form1.rh05_caub.value = '';
                    document.form1.r59_descr.value = '';
                    document.form1.r59_descr1.value = '';
                    document.form1.rh05_motivo.value = '';
                    document.form1.rh173_descricao.value = '';
                }
            }

            function js_mostrarescisao(chave, chave2, chave3, erro) {
                document.form1.r59_descr.value = chave;
                if (erro == true) {
                    document.form1.rh05_causa.focus();
                    document.form1.rh05_causa.value = '';
                    document.form1.rh05_caub.value = '';
                    document.form1.r59_descr1.value = '';

                } else {
                    document.form1.rh05_caub.value = chave2;
                    document.form1.r59_descr1.value = chave3;
                }
            }

            function js_mostrarescisao1(chave1, chave2, chave3, chave4) {

                document.form1.rh05_causa.value = chave1;
                document.form1.r59_descr.value = chave2;
                document.form1.rh05_caub.value = chave3;
                document.form1.r59_descr1.value = chave4;
                db_iframe_rescisao.hide();
            }

            function js_pesquisarh173_codigo(mostra) {
                if (document.form1.rh02_codreg.value != "") {
                    if (mostra == true) {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_motivo', 'func_rhmotivorescisao.php?funcao_js=parent.js_mostramotivo1|rh173_codigo|rh173_descricao&chave_r59_anousu=<?= $rh02_anousu ?>&chave_r59_mesusu=<?= $rh02_mesusu ?>&regime=' + document.form1.rh02_codreg.value, 'Pesquisa', true, '0');
                    } else {
                        if (document.form1.rh173_codigo.value != '') {
                            js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_motivo', 'func_rhmotivorescisao.php?pesquisa_chave=' + document.form1.rh173_codigo.value + '&funcao_js=parent.js_mostrarescisao&ano=<?= $rh02_anousu ?>&mes=<?= $rh02_mesusu ?>&regime=' + document.form1.rh02_codreg.value, 'Pesquisa', false, '0');
                        } else {
                            document.form1.rh173_codigo.value = '';
                            document.form1.rh173_descricao.value = '';
                        }
                    }
                } else {
                    alert(_M(sMensagem + 'erro_regime'));
                    document.form1.rh173_codigo.value = '';
                    document.form1.rh173_descricao.value = '';
                }
            }

            function js_mostramotivo(chave, chave2, erro) {
                if (erro == true) {
                    document.form1.rh173_codigo.focus();
                    document.form1.rh05_motivo.value = '';
                    document.form1.rh173_descricao.value = '';
                } else {
                    document.form1.rh05_motivo.value = chave;
                    document.form1.rh173_descricao.value = chave2;
                }
            }

            function js_mostramotivo1(chave1, chave2) {
                document.form1.rh05_motivo.value = chave1;
                document.form1.rh173_descricao.value = chave2;
                db_iframe_motivo.hide();
            }

            function js_validaDados() {

                if (!js_validaTipoDeficiencia()) {
                    return false;
                }

                if (oContaBancariaServidor.lStatus || $F('inputCodigoBanco') == '') {
                    js_verificaconta();
                }

            }

            function js_verificaconta() {

                /**
                 * V�lida se o campo sal�rio � negativo
                 */
                var salario = parseFloat(document.form1.rh02_salari.value.trim());
                if (salario < 0) {

                    alert(_M(sMensagem + 'salario_negativo'));
                    return false;
                }


                if (document.form1.rh30_vinculo.value == 'I' || document.form1.rh30_vinculo.value == 'P') {

                    if (document.form1.rh02_rhtipoapos.value == 0) {

                        alert(_M(sMensagem + 'erro_tipo_pensao'));
                        return false;
                    }
                }

                if (document.form1.rh30_regime.value == 2 
                    && document.form1.rh02_jornadadetrabalho.value == '') {
                    alert('O campo Jornada de Trabalho � obrigat�rio.');
                    return false;
                }
                if (document.form1.rh30_regime.value == 2 
                    && document.form1.rh02_tipojornada.value == 0) {
                    alert('O campo Tipo de Jornada � obrigat�rio.');
                    return false;
                }


                if (document.form1.rh02_fpagto.value > 1 || document.form1.inputCodigoBanco.value != "") {

                    if (document.form1.inputCodigoBanco.value == "") {

                        alert(_M(sMensagem + 'erro_codigo_banco'));
                        document.form1.inputCodigoBanco.focus();
                        return false;
                    } else if (document.form1.inputNumeroAgencia.value == "") {

                        alert(_M(sMensagem + 'erro_numero_agencia'));
                        document.form1.inputNumeroAgencia.focus();
                        return false;
                    } else if (document.form1.inputDvAgencia.value == "") {

                        alert(_M(sMensagem + 'erro_dv_agencia'));
                        document.form1.inputDvAgencia.focus();
                        return false;
                    } else if (document.form1.inputNumeroConta.value == "") {

                        alert(_M(sMensagem + 'erro_numero_conta_corrente'));
                        document.form1.inputNumeroConta.focus();
                        return false;
                    } else if (document.form1.inputDvConta.value == "") {

                        alert(_M(sMensagem + 'erro_dv_conta_corrente'));
                        document.form1.inputDvConta.focus();
                        return false;
                    } else if (document.form1.cboTipoConta.value == 0) {

                        alert(_M(sMensagem + 'erro_tipo_conta'));
                        return false;
                    }
                }

                if (document.form1.rh02_lota.value == "") {

                    alert(_M(sMensagem + 'erro_lotacao'));
                    document.form1.rh02_lota.focus();
                    return false;
                } else if (document.form1.rh02_codreg.value == "") {

                    alert(_M(sMensagem + 'erro_regime'));
                    document.form1.rh02_codreg.focus();
                    return false;
                } else if (document.form1.rh02_tpcont.value == "") {

                    alert(_M(sMensagem + 'erro_tipo_contato'));
                    document.form1.rh02_tpcont.focus();
                    return false;
                } else if (document.form1.rh02_hrsmen.value == "") {

                    alert(_M(sMensagem + 'erro_qnd_horas_mensais'));
                    document.form1.rh02_hrsmen.focus();
                    return false;
                } else if (document.form1.rh02_hrssem.value == "") {

                    alert(_M(sMensagem + 'erro_qnd_horas_semanais'));
                    document.form1.rh02_hrssem.focus();
                    return false;
                } else if (document.form1.rh02_tbprev.value == "0") {
                    if (!confirm(_M(sMensagem + 'confirm_calculo_previdencia'))) {
                        return false;
                    }
                } else if (document.form1.rh02_funcao.value == "") {
                    alert(_M(sMensagem + 'erro_cargo'));
                    document.form1.rh02_funcao.focus();
                    return false;
                } else if (document.form1.rh02_diasgozoferias.value == "") {
                    alert(_M(sMensagem + 'erro_diasgozoferias'));
                    document.form1.rh02_diasgozoferias.focus();
                    return false;
                } else if (document.form1.rh02_diasgozoferias.value < 30) {
                    alert(_M(sMensagem + 'erro_diasgozoferias_minimo'));
                    document.form1.rh02_diasgozoferias.focus();
                    return false;
                }

                if (document.form1.tipadm.value == 3 || document.form1.tipadm.value == 4) {
                    if (document.form1.rh02_cnpjcedente.value != "") {
                        if (js_verificaCNPJ(document.form1.rh02_cnpjcedente) == false) {
                            alert("Usu�rio: \n\nInforme o CNPJ Cedente.\n\nAdministrador:");
                            return false;
                        }
                    }
                }

                document.form1.submit();
            }

            function js_cancelar() {

                var opcao = document.createElement("input");
                opcao.setAttribute("type", "hidden");
                opcao.setAttribute("name", "novo");
                opcao.setAttribute("value", "true");
                document.form1.appendChild(opcao);
                document.form1.submit();
            }

            function js_pesquisarh44_codban(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_db_bancos', 'func_db_bancos.php?funcao_js=parent.js_mostrabancos1|db90_codban|db90_descr', 'Pesquisa', true, 0);
                } else {
                    if (document.form1.rh44_codban.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_db_bancos', 'func_db_bancos.php?pesquisa_chave=' + document.form1.rh44_codban.value + '&funcao_js=parent.js_mostrabancos', 'Pesquisa', false, 0);
                    } else {
                        document.form1.db90_descr.value = '';
                    }
                }
            }

            function js_mostrabancos(chave, erro) {
                document.form1.db90_descr.value = chave;
                if (erro == true) {
                    document.form1.rh44_codban.focus();
                    document.form1.rh44_codban.value = '';
                }
            }

            function js_mostrabancos1(chave1, chave2) {
                document.form1.rh44_codban.value = chave1;
                document.form1.db90_descr.value = chave2;
                db_iframe_db_bancos.hide();
            }

            function js_pesquisarh20_cargo(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhcargo', 'func_rhcargo.php?funcao_js=parent.js_mostrarhcargo1|rh04_codigo|rh04_descr', 'Pesquisa', true, '0');
                } else {
                    if (document.form1.rh20_cargo.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhcargo', 'func_rhcargo.php?pesquisa_chave=' + document.form1.rh20_cargo.value + '&funcao_js=parent.js_mostrarhcargo', 'Pesquisa', false, 0);
                    } else {
                        document.form1.rh04_descr.value = '';
                    }
                }
            }

            function js_mostrarhcargo(chave, erro) {
                document.form1.rh04_descr.value = chave;
                if (erro == true) {
                    document.form1.rh20_cargo.focus();
                    document.form1.rh20_cargo.value = '';
                }
            }

            function js_mostrarhcargo1(chave1, chave2) {
                document.form1.rh20_cargo.value = chave1;
                document.form1.rh04_descr.value = chave2;
                db_iframe_rhcargo.hide();
            }

            function js_pesquisarh02_regist(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhpessoal', 'func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|rh01_numcgm', 'Pesquisa', true, '0');
                } else {
                    if (document.form1.rh02_regist.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhpessoal', 'func_rhpessoal.php?pesquisa_chave=' + document.form1.rh02_regist.value + '&funcao_js=parent.js_mostrarhpessoal', 'Pesquisa', false, 0);
                    } else {
                        document.form1.rh01_numcgm.value = '';
                    }
                }
            }

            function js_mostrarhpessoal(chave, erro) {
                document.form1.rh01_numcgm.value = chave;
                if (erro == true) {
                    document.form1.rh02_regist.focus();
                    document.form1.rh02_regist.value = '';
                }
            }

            function js_mostrarhpessoal1(chave1, chave2) {
                document.form1.rh02_regist.value = chave1;
                document.form1.rh01_numcgm.value = chave2;
                db_iframe_rhpessoal.hide();
            }

            function js_camposorigem(opcao) {
                if (opcao == false) {
                    document.form1.rh21_regpri.type = "hidden";
                    document.form1.z01_nomeorigem.type = "hidden";
                    document.form1.rh21_regpri.value = "";
                    document.form1.z01_nomeorigem.value = "";
                    $("vinculoorigem").hide();
                    document.getElementById("torigem").title = "";
                } else {
                    document.form1.rh21_regpri.type = "text";
                    document.form1.z01_nomeorigem.type = "text";
                    document.form1.rh21_regpri.readOnly = false;
                    document.form1.rh21_regpri.style.backgroundColor = "";
                    $("vinculoorigem").show();
                    document.getElementById("torigem").title = "<?= str_replace("\r", "\\r", str_replace("\n", "\\n", AddSlashes($Trh21_regpri))) ?>";
                }
            }

            function js_pesquisarh02_codreg(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhregime', 'func_rhregimereg.php?funcao_js=parent.js_mostrarhregime1|rh30_codreg|rh30_descr|rh30_regime|rh30_vinculo', 'Pesquisa', true, 0);
                } else {
                    if (document.form1.rh02_codreg.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_rhregime', 'func_rhregimereg.php?pesquisa_chave=' + document.form1.rh02_codreg.value + '&funcao_js=parent.js_mostrarhregime', 'Pesquisa', false, 0);
                    } else {
                        document.form1.rh30_regime.value = '';
                        document.form1.rh30_descr.value = '';
                        document.form1.rh30_vinculo.value = '';
                        document.form1.rh05_causa.value = '';
                        document.form1.rh05_caub.value = '';
                        document.form1.r59_descr.value = '';
                        document.form1.r59_descr1.value = '';
                        document.form1.rh02_tpcont.value = '';
                        document.form1.h13_descr.value = '';
                        document.form1.h13_tpcont.value = '';
                        js_camposorigem(false);
                        js_disabpropri("");
                        js_disabledtipoapos("");
                    }
                }
            }

            function js_mostrarhregime(chave, chave2, chave3, erro) {
                document.form1.rh30_descr.value = chave;
                if (erro == true) {
                    document.form1.rh02_codreg.focus();
                    document.form1.rh02_codreg.value = '';
                    document.form1.rh30_regime.value = '';
                    document.form1.rh30_vinculo.value = '';
                    js_camposorigem(false);
                } else {
                    document.form1.rh30_regime.value = chave2;
                    document.form1.rh30_vinculo.value = chave3;
                    document.form1.rh02_tpcont.value = '';
                    document.form1.h13_descr.value = '';
                    document.form1.h13_tpcont.value = '';
                    js_pesquisarh02_tpcont(true);
                    if (chave3 == "P") {
                        document.getElementById('trservidorinstituidor').style.display = '';
                        document.getElementById('trdtfalecimento').style.display = '';
                        document.getElementById('tipoparentesco').style.display = '';
                        js_camposorigem(true);
                    } else {
                        document.getElementById('trservidorinstituidor').style.display = 'none';
                        document.getElementById('trdtfalecimento').style.display = 'none';
                        document.getElementById('tipoparentesco').style.display = 'none';
                        js_camposorigem(false);
                    }
                }
                js_disabpropri(chave3);
                js_disabledtipoapos(chave3);
            }

            function js_mostrarhregime1(chave1, chave2, chave3, chave4) {
                document.form1.rh02_codreg.value = chave1;
                document.form1.rh30_descr.value = chave2;
                document.form1.rh30_regime.value = chave3;
                document.form1.rh30_vinculo.value = chave4;
                db_iframe_rhregime.hide();
                document.form1.rh02_tpcont.value = '';
                document.form1.h13_descr.value = '';
                document.form1.h13_tpcont.value = '';
                js_pesquisarh02_tpcont(true);
                if (chave4 == "P") {
                    document.getElementById('trservidorinstituidor').style.display = '';
                    document.getElementById('trdtfalecimento').style.display = '';
                    document.getElementById('tipoparentesco').style.display = '';
                    document.getElementById('rh02_cgminstituidor').value = '';
                    document.getElementById('rh02_dtobitoinstituidor').value = '';
                    document.getElementById('rh02_tipoparentescoinst').value = 0;
                    js_camposorigem(true);
                } else {
                    document.getElementById('trservidorinstituidor').style.display = 'none';
                    document.getElementById('trdtfalecimento').style.display = 'none';
                    document.getElementById('tipoparentesco').style.display = 'none';
                    document.getElementById('rh02_cgminstituidor').value = '';
                    document.getElementById('rh02_dtobitoinstituidor').value = '';
                    document.getElementById('rh02_tipoparentescoinst').value = 0;
                    js_camposorigem(false);
                }
                js_disabpropri(chave4);
                js_disabledtipoapos(chave4);
                js_desctipoparentescoinst()
            }

            function js_disabpropri(opcao) {
                if (opcao == "A") {
                    // document.form1.rh19_propi.style.backgroundColor = '#DEB887';
                    // document.form1.rh19_propi.readOnly = true;
                    // document.form1.rh19_propi.value = "";
                    $('rh19_propi').value = "";
                    $('disabpropri').hide();
                } else {
                    // document.form1.rh19_propi.style.backgroundColor = '';
                    // document.form1.rh19_propi.readOnly = false;
                    $('disabpropri').show();
                }
            }
            /**
             * Verifica se foi selecionado algum valor de sal�rio ou uma fun��o padr�o para o calculo
             */
            function js_validaPadraoPrevidencia(bAvisoPrevidencia) {

                var salario = parseFloat(document.form1.rh02_salari.value.trim());
                var valorPadrao = document.form1.rh03_padrao.value;

                if (valorPadrao.trim() == "" && (isNaN(salario) || salario.valueOf() == 0)) {

                    document.form1.rh03_padraoprev.value = '';
                    document.form1.r02_descrprev.value = '';

                    if (bAvisoPrevidencia) {

                        document.form1.rh03_padraoprev.focus();

                        alert(_M(sMensagem + 'erro_selecionar_padraoprev'));

                        return false;
                    }
                }

                return true;
            }

            /**
             * Habilita select para informar tipo de defici�ncia
             * no caso de informar sim como deficiente f�sico
             */
            function js_informarTipoDeficiencia() {

                var nodeDeficiente = $("rh02_deficientefisico");
                var nodeTipoDeficiencia = $("row_rh02_tipodeficiencia");
                var nodeCotaDeficiencia = $("row_rh02_cotadeficiencia");

                if (nodeDeficiente.value == 't' || nodeDeficiente.value.toLowerCase().indexOf('sim') > -1) {
                    nodeTipoDeficiencia.show();
                    if ($("rh30_regime").value == "2") {
                        nodeCotaDeficiencia.show();
                    } else {
                        nodeCotaDeficiencia.hide();
                    }
                } else if (nodeDeficiente.value == 'f' || nodeDeficiente.value.toLowerCase().indexOf('n�o') > -1) {
                    nodeTipoDeficiencia.hide();
                    nodeCotaDeficiencia.hide();
                    $("rh02_tipodeficiencia").value = 0;
                    js_informarReab();
                }
            }

            function js_informarReab() {
                var nodeTipodeficiente = $("rh02_tipodeficiencia");
                var nodeReab = $("row_rh02_reabreadap");

                if (nodeTipodeficiente.value != 0) {
                    nodeReab.show();
                } else if (nodeTipodeficiente.value == 0) {
                    nodeReab.hide();
                    $("rh02_reabreadap").value = "f";
                }
            }

            /**
             * Habilita campo para informar o laudo de portador de molestia
             */
            function js_informarLaudoPortadorMolestia() {
                var nodePortadorMolestia = $("rh02_portadormolestia");
                var nodeLaudoMolestia = $("laudoportadormolestia");

                if (nodePortadorMolestia.value == 't' || nodePortadorMolestia.value.toLowerCase().indexOf('sim') > -1) {
                    nodeLaudoMolestia.show();
                } else if (nodePortadorMolestia.value == 'f' || nodePortadorMolestia.value.toLowerCase().indexOf('n�o') > -1) {
                    nodeLaudoMolestia.hide();
                    $('rh02_datalaudomolestia').value = "";
                    $('rh02_laudoportadormolestia_file').value = "";
                }
            }

            function js_abonopermanencia() {
                var nodeAbonoPermanencia = $("rh02_abonopermanencia");
                var nodeDataInicio = $("datainicio");

                if (nodeAbonoPermanencia.value == 't' || nodeAbonoPermanencia.value.toLowerCase().indexOf('sim') > -1) {
                    nodeDataInicio.show();
                } else if (nodeAbonoPermanencia.value == 'f' || nodeAbonoPermanencia.value.toLowerCase().indexOf('n�o') > -1) {
                    nodeDataInicio.hide();
                }
            }

            /**
             * Valida tipo de defici�ncia
             */
            function js_validaTipoDeficiencia() {

                var nodeDeficiente = $("rh02_deficientefisico");
                var nodeTipoDeficiencia = $("rh02_tipodeficiencia");

                if (nodeDeficiente.value == 't' || nodeDeficiente.value.toLowerCase().indexOf('sim') > -1) {

                    if (nodeTipoDeficiencia.value == 0) {
                        alert(_M(sMensagem + 'tipo_deficiencia'));
                        return false;
                    }

                } else if (nodeDeficiente.value == 'f' || nodeDeficiente.value.toLowerCase().indexOf('n�o') > -1) {
                    nodeTipoDeficiencia.value = 0;
                    js_ProcCod_rh02_tipodeficiencia('rh02_tipodeficiencia', 'rh02_tipodeficiencia');
                }

                return true;
            }

            function js_pesquisarh02_cgminstituidor(mostra) {
                if (mostra == true) {
                    js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_cgm', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true, '0', '1');
                } else {
                    if (document.form1.rh02_cgminstituidor.value != '') {
                        js_OpenJanelaIframe('top.corpo.iframe_rhpessoalmov', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + document.form1.rh02_cgminstituidor.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false);
                    } else {
                        document.form1.nomecgminstituidor.value = '';
                    }
                }
            }

            function js_mostracgm(erro, chave) {
                document.form1.nomecgminstituidor.value = chave;
                if (erro == true) {
                    document.form1.rh02_cgminstituidor.focus();
                    document.form1.rh02_cgminstituidor.value = '';
                }
            }

            function js_mostracgm1(chave1, chave2) {
                document.form1.rh02_cgminstituidor.value = chave1;
                document.form1.nomecgminstituidor.value = chave2;
                db_iframe_cgm.hide();
            }

            function verificapensao() {
                var value = document.form1.rh30_vinculo.value;
                if (value == "P") {
                    document.getElementById('trservidorinstituidor').style.display = '';
                    document.getElementById('trdtfalecimento').style.display = '';
                    document.getElementById('tipoparentesco').style.display = '';
                } else {
                    document.getElementById('trservidorinstituidor').style.display = 'none';
                    document.getElementById('trdtfalecimento').style.display = 'none';
                    document.getElementById('tipoparentesco').style.display = 'none';
                }
            }
            verificapensao();


            js_disabdata("<?= ($rh05_taviso) ?>");

            // Informa��es das mensagens de sugest�o para o padr�o de previd�ncia
            var aEventoShow = new Array('onMouseover', 'onFocus');
            var aEventoHide = new Array('onMouseout', 'onBlur');
            var oDbHintPadraoPrev = new DBHint('oDbHintPadraoPrev');
            oDbHintPadraoPrev.setText(_M(sMensagem + 'suggest_padrao_prev'));
            oDbHintPadraoPrev.setShowEvents(aEventoShow);
            oDbHintPadraoPrev.setHideEvents(aEventoHide);
            oDbHintPadraoPrev.make($('LabelPadraoPrev'));
            oDbHintPadraoPrev.make($('rh03_padraoprev'));
            oDbHintPadraoPrev.make($('r02_descrprev'));

            // Hint de aviso para altera��es na tabela de previd�ncia
            var oDbHintTabelaPrev = new DBHint('oDbHintTabelaPrev');
            oDbHintTabelaPrev.setText(_M(sMensagem + 'suggest_tabela_prev'));
            oDbHintTabelaPrev.setShowEvents(aEventoShow);
            oDbHintTabelaPrev.setHideEvents(aEventoHide);
            oDbHintTabelaPrev.make($('LabelTabelaPrev'));
            oDbHintTabelaPrev.make($('rh02_tbprev'));
            oDbHintTabelaPrev.make($('rh02_tbprevdescr'));

            <?php
            if (isset($rh30_vinculo) && $rh30_vinculo == "P") {
                echo "js_camposorigem(true);";
            } else {
                echo "js_camposorigem(false);";
                echo "js_disabpropri('" . @$rh30_vinculo . "');";
            }
            ?>

            function js_desctipoparentescoinst() {
                if (document.querySelector('#rh02_tipoparentescoinst').value == 11) {
                    document.querySelector("#desctipoparentescoinst").style.display = "";
                } else {
                    document.querySelector("#desctipoparentescoinst").style.display = "none";
                    document.querySelector("#rh02_desctipoparentescoinst").value = '';
                }
            }
            js_desctipoparentescoinst();

            function js_imprimir_laudo(laudo) {
                let iOid = document.getElementById(laudo).value;
                jan = window.open('pes2_imprimirlaudo002.php?oid=' + iOid, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
                jan.moveTo(0, 0);
            }

            document.getElementById("ctnContaBancariaServidor").firstElementChild.setAttribute("id", "ctnContaBancaria");
            var oToogleContaBancaria = new DBToogle('ctnContaBancaria', true);
            var oToogleRescisao = new DBToogle('ctnRescisao', ($('rh05_recis').value != '' ? true : false));
            var oToogleSiope = new DBToogle('ctnSiope', false);
            var oToogleDadosCedencia = new DBToogle('ctnDadosCedencia', false);

            function js_verifica_lotacao() {
                let oParam = new Object();
                oParam.sMethod = 'consultaLotacaoRecurso';
                oParam.iLotacao = $('rh02_lota').value;
                var oAjax = new Ajax.Request(
                    'pes1_rhpessoalmov.RPC.php', {
                        method: 'post',
                        parameters: 'json=' + Object.toJSON(oParam),
                        onComplete: js_retornoVerificaLotacao
                    }
                );
            }

            function js_retornoVerificaLotacao(oAjax) {
                var oRetorno = eval("(" + oAjax.responseText + ")");
                oToogleSiope.show(oRetorno.lShow);
            }
            js_verifica_lotacao();

            function js_verificaCNPJ(cnpj) {
                if (cnpj.value.length == 14) {
                    return js_TestaNI(cnpj, 1);
                }
                if (cnpj.value != "") {
                    cnpj.select();
                    cnpj.focus();
                }
                return false;
            }

            function js_verificatipoapos(value) {
                let rh01_admiss = '<?= $rh01_admiss ?>';
                let opcoesbeneficio = document.getElementById('rh02_tipobeneficio').options;
                document.getElementById('tipobeneficio').style.display = '';

                if (value == 2 || value == 3 || value == 5 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade e tempo de contribui��o - Proventos com integralidade, revis�o pela paridade', '0101'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade e tempo de contribui��o - Proventos pela m�dia, reajuste manter valor real', '0102'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade - Proventos proporcionais calculado sobre integralidade, revis�o pela paridade', '0103'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade - Proventos proporcionais calculado sobre a m�dia, reajuste manter valor real', '0104'));
                    opcoesbeneficio.add(new Option('Aposentadoria compuls�ria - Proventos proporcionais calculado sobre integralidade, revis�o pela paridade', '0105'));
                    opcoesbeneficio.add(new Option('Aposentadoria compuls�ria - Proventos proporcionais calculado sobre a m�dia, reajuste manter valor real', '0106'));
                    opcoesbeneficio.add(new Option('Aposentadoria de professor - Proventos com integralidade, revis�o pela paridade', '0107'));
                    opcoesbeneficio.add(new Option('Aposentadoria de professor - Proventos pela m�dia, reajuste manter valor real', '0108'));
                }

                if (value == 6 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria especial - Risco', '0201'));
                    opcoesbeneficio.add(new Option('Aposentadoria especial - Exposi��o a agentes nocivos', '0202'));
                    opcoesbeneficio.add(new Option('Aposentadoria da pessoa com defici�ncia', '0203'));
                }

                if (value == 4 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos com integralidade, revis�o pela paridade', '0301'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos pela m�dia, reajuste manter valor real', '0302'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos proporcionais calculado sobre integralidade, revis�o pela paridade', '0303'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos proporcionais calculado sobre a m�dia, reajuste manter valor real', '0304'));
                }

                if (value == 1 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Pens�o por morte (art. 40, � 7�, da CF/1988)', '0601'));
                    opcoesbeneficio.add(new Option('Pens�o por morte com paridade, decorrente do art. 6�-A da EC 41/2003', '0602'));
                    opcoesbeneficio.add(new Option('Pens�o por morte com paridade, decorrente do art. 3� da EC 47/2005', '0603'));

                }
                console.log(value);
                if (value && rh01_admiss < '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria sem paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0801'));
                    opcoesbeneficio.add(new Option('Aposentadoria com paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0802'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez com paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0803'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez sem paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0804'));
                    opcoesbeneficio.add(new Option('Transfer�ncia para reserva concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0805'));
                    opcoesbeneficio.add(new Option('Reforma concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0806'));
                    opcoesbeneficio.add(new Option('Pens�o por morte com paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0807'));
                    opcoesbeneficio.add(new Option('Pens�o por morte sem paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0808'));
                    opcoesbeneficio.add(new Option('Outros benef�cios previdenci�rios concedidos antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0809'));
                    opcoesbeneficio.add(new Option('Aposentadoria de parlamentar - Plano pr�prio', '0810'));
                    opcoesbeneficio.add(new Option('Aposentadoria de servidor vinculado ao Poder Legislativo - Plano pr�prio', '0811'));
                    opcoesbeneficio.add(new Option('Pens�o por morte - Plano pr�prio', '0812'));
                    if (value == 7) {
                        document.getElementById('tipo7').style.display = '';
                    } else {
                        document.getElementById('tipo7').style.display = 'none';
                    }
                } else if (value == 7) {
                    document.getElementById('tipo7').style.display = '';
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Pens�o especial sem v�nculo previdenci�rio', '1001'));
                    opcoesbeneficio.add(new Option('Outros benef�cios sem v�nculo previdenci�rio', '1009'));
                } else {
                    document.getElementById('tipo7').style.display = 'none';
                }
            }

            function js_CarregaTipoBeneficio() {
                var oParam = new Object();
                oParam.sMethod = 'getTipoBeneficio';
                oParam.rh02_regist = <?= $rh02_regist ?>;
                oParam.rh02_anousu = <?= $rh02_anousu ?>;
                oParam.rh02_mesusu = <?= $rh02_mesusu ?>;
                var oAjax = new Ajax.Request(
                    'pes1_rhpessoalmov.RPC.php', {
                        parameters: 'json=' + Object.toJSON(oParam),
                        asynchronous: false,
                        method: 'post',
                        onComplete: js_oBeneficio
                    });
            }

            function js_oBeneficio(oAjax) {
                var oRetorno = eval('(' + oAjax.responseText + ")");
                let value = document.getElementById('rh02_rhtipoapos').value;
                let rh01_admiss = '<?= $rh01_admiss ?>';
                let opcoesbeneficio = document.getElementById('rh02_tipobeneficio').options;
                console.log(oRetorno.rh02_tipobeneficio);
                if (oRetorno.rh02_tipobeneficio != undefined) {
                    document.getElementById('tipobeneficio').style.display = '';
                }
                if (value == 2 || value == 3 || value == 5 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade e tempo de contribui��o - Proventos com integralidade, revis�o pela paridade', '0101'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade e tempo de contribui��o - Proventos pela m�dia, reajuste manter valor real', '0102'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade - Proventos proporcionais calculado sobre integralidade, revis�o pela paridade', '0103'));
                    opcoesbeneficio.add(new Option('Aposentadoria por idade - Proventos proporcionais calculado sobre a m�dia, reajuste manter valor real', '0104'));
                    opcoesbeneficio.add(new Option('Aposentadoria compuls�ria - Proventos proporcionais calculado sobre integralidade, revis�o pela paridade', '0105'));
                    opcoesbeneficio.add(new Option('Aposentadoria compuls�ria - Proventos proporcionais calculado sobre a m�dia, reajuste manter valor real', '0106'));
                    opcoesbeneficio.add(new Option('Aposentadoria de professor - Proventos com integralidade, revis�o pela paridade', '0107'));
                    opcoesbeneficio.add(new Option('Aposentadoria de professor - Proventos pela m�dia, reajuste manter valor real', '0108'));
                }

                if (value == 6 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria especial - Risco', '0201'));
                    opcoesbeneficio.add(new Option('Aposentadoria especial - Exposi��o a agentes nocivos', '0202'));
                    opcoesbeneficio.add(new Option('Aposentadoria da pessoa com defici�ncia', '0203'));
                }

                if (value == 4 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos com integralidade, revis�o pela paridade', '0301'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos pela m�dia, reajuste manter valor real', '0302'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos proporcionais calculado sobre integralidade, revis�o pela paridade', '0303'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez - Proventos proporcionais calculado sobre a m�dia, reajuste manter valor real', '0304'));
                }

                if (value == 1 && rh01_admiss >= '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Pens�o por morte (art. 40, � 7�, da CF/1988)', '0601'));
                    opcoesbeneficio.add(new Option('Pens�o por morte com paridade, decorrente do art. 6�-A da EC 41/2003', '0602'));
                    opcoesbeneficio.add(new Option('Pens�o por morte com paridade, decorrente do art. 3� da EC 47/2005', '0603'));

                }

                if (value && rh01_admiss < '2021-11-21') {
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Aposentadoria sem paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0801'));
                    opcoesbeneficio.add(new Option('Aposentadoria com paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0802'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez com paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0803'));
                    opcoesbeneficio.add(new Option('Aposentadoria por invalidez sem paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0804'));
                    opcoesbeneficio.add(new Option('Transfer�ncia para reserva concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0805'));
                    opcoesbeneficio.add(new Option('Reforma concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0806'));
                    opcoesbeneficio.add(new Option('Pens�o por morte com paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0807'));
                    opcoesbeneficio.add(new Option('Pens�o por morte sem paridade concedida antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0808'));
                    opcoesbeneficio.add(new Option('Outros benef�cios previdenci�rios concedidos antes da obrigatoriedade de envio dos eventos n�o peri�dicos para entes p�blicos no eSocial', '0809'));
                    opcoesbeneficio.add(new Option('Aposentadoria de parlamentar - Plano pr�prio', '0810'));
                    opcoesbeneficio.add(new Option('Aposentadoria de servidor vinculado ao Poder Legislativo - Plano pr�prio', '0811'));
                    opcoesbeneficio.add(new Option('Pens�o por morte - Plano pr�prio', '0812'));
                    if (value == 7) {
                        document.getElementById('tipo7').style.display = '';
                    }
                } else if (value == 7) {
                    document.getElementById('tipo7').style.display = '';
                    opcoesbeneficio.length = 0;
                    opcoesbeneficio.add(new Option('Selecione', '0'));
                    opcoesbeneficio.add(new Option('Pens�o especial sem v�nculo previdenci�rio', '1001'));
                    opcoesbeneficio.add(new Option('Outros benef�cios sem v�nculo previdenci�rio', '1009'));
                } else {
                    document.getElementById('tipo7').style.display = 'none';
                }
                document.getElementById('rh02_tipobeneficio').value = oRetorno.rh02_tipobeneficio;
                if (oRetorno.rh02_descratobeneficio != undefined) {
                    document.getElementById('rh02_descratobeneficio').value = oRetorno.rh02_descratobeneficio;
                } else {
                    document.getElementById('rh02_descratobeneficio').value = '';
                }
            }
            js_CarregaTipoBeneficio();
        </script>
        <?php

        if (isset($rh21_regpri)) {
            echo "<script>";
            echo "js_pesquisarh21_regpri(false);";
            echo "</script>";
        }
        ?>
