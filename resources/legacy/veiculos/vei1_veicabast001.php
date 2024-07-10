<?
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
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veicabast_classe.php");
include("classes/db_veicabastposto_classe.php");
include("classes/db_veicabastpostoempnota_classe.php");
include("classes/db_veicabastretirada_classe.php");
include("classes/db_veicparam_classe.php");
include("classes/db_veicretirada_classe.php");
include("classes/db_empveiculos_classe.php");
include("classes/db_empempenho_classe.php");
require_once("classes/db_condataconf_classe.php");
require_once("libs/db_app.utils.php");
db_app::import("veiculos.*");
db_postmemory($HTTP_POST_VARS);

$clveiculos              = new cl_veiculos;
$clveicabast             = new cl_veicabast;
$clveicabastposto        = new cl_veicabastposto;
$clveicabastpostoempnota = new cl_veicabastpostoempnota;
$clveicabastretirada     = new cl_veicabastretirada;
$clveicparam             = new cl_veicparam;
$clveicretirada          = new cl_veicretirada;
$clempveiculos           = new cl_empveiculos;
$clempempenho           = new cl_empempenho;

$clempveiculos->rotulo->label();

$db_opcao = 1;
$db_botao = true;

$sqlerro = false;

if (isset($ve60_datasaida) && $ve60_datasaida != "") {

    $aData = explode("/", $ve60_datasaida);

    $ve60_datasaida_dia = $aData[0];
    $ve60_datasaida_mes = $aData[1];
    $ve60_datasaida_ano = $aData[2];
}
/**
 * Valida��o solicitadas pela OC 4577
 * @autor Mario Junior
 *
 */
if (isset($incluir)) {
    db_inicio_transacao();
    $medida     = $ve70_medida;
    $oDataAbast = new DBDate($ve70_dtabast);
    $passa = false;
    $horaabast = $ve70_hora;
    $retirada = $ve73_veicretirada;
    $datahoraAbastecimento = strtotime($oDataAbast->getDate() . " " . $ve70_hora);
    $dataabast = $oDataAbast->getDate();

    $resultParam = $clveicparam->sql_record($clveicparam->sql_query_file(null, "*", null, "ve50_instit = " . db_getsession("DB_instit")));
    $resultParamres = db_utils::fieldsMemory($resultParam, 0);

    if ($resultParamres->ve50_abastempenho == 1) {

        $resultadoEmp = $clempempenho->sql_record($clempempenho->sql_query_file($si05_numemp, "*", null, ""));
        $resultadoEmp = db_utils::fieldsMemory($resultadoEmp, 0);



        if (strtotime($resultadoEmp->e60_emiss) >= strtotime($resultParamres->ve50_datacorte)) {
            if ($resultadoEmp->e60_vlrutilizado == "") {
                $sql = "select si05_codabast from empveiculos where si05_numemp = $si05_numemp";

                $result = db_query($sql);
                $soma = 0;
                for ($i = 0; $i < pg_num_rows($result); $i++) {
                    $rsAbastecimento = db_utils::fieldsMemory($result, $i);
                    $sql = "select ve70_valor from veicabast where ve70_codigo = $rsAbastecimento->si05_codabast";
                    $result1 = db_query($sql);
                    $rsAbastecimento1 = db_utils::fieldsMemory($result1, 0);

                    $valorutilizado += $rsAbastecimento1->ve70_valor;
                }
                $clempempenho->e60_vlrutilizado =  $valorutilizado;
                $clempempenho->sql_query_valorutilizado($si05_numemp);
                $valorautilizar = $resultadoEmp->e60_vlremp - $valorutilizado;
            } else {
                $valorautilizar = $resultadoEmp->e60_vlremp - $resultadoEmp->e60_vlrutilizado;
            }

            if ($ve70_valor > $valorautilizar) {
                $sqlerro = true;
                $erro_msg = "Usu�rio: Abastecimento n�o inclu�do, valor total do abastecimento ultrapassou o valor dispon�vel no empenho. Saldo dispon�vel R$ $valorautilizar";
            }
            if ($sqlerro == false) {
                if ($resultadoEmp->e60_vlrutilizado == "") {
                    $valor = $valorutilizado + $ve70_valor;
                } else {
                    $valor = $resultadoEmp->e60_vlrutilizado + $ve70_valor;
                }

                $clempempenho->e60_vlrutilizado =  $valor;
                $clempempenho->sql_query_valorutilizado($si05_numemp);
            }
        }
    }

    $rsDataEmpenho = $clempempenho->sql_record($clempempenho->sql_query_file($si05_numemp,"e60_emiss",null,""));
    $dataEmpenho = db_utils::fieldsMemory($rsDataEmpenho, 0)->e60_emiss;
    if(strtotime($dataEmpenho) > strtotime($dataabast)){
        $sqlerro = true;
        $erro_msg = "Usu�rio: a data do empenho n�o pode ser maior que a data do abastecimento";
    }

    if (isset($ve70_medida) and $ve70_medida == 0) {
        $medidazero = true;
    } else {
        $medidazero = false;
    }

    if ($retirada == "" || $retirada == null) {
        $sqlerro = true;
        $erro_msg = "Campo: Retirada n�o informado";
    }

    /*
       * verifica retirada e devolu��o vinculados ao abastecimento
       */
    $result_retirada = $clveicabast->sql_record($clveicabast->sql_query_retirada(null, "ve60_codigo,ve60_medidasaida,ve61_medidadevol,to_timestamp(ve60_datasaida||' '||ve60_horasaida::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS datahrretirada,to_timestamp(ve61_datadevol||' '||ve61_horadevol::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS datahrdevolucao", null, "ve60_codigo=$retirada"));
    if (pg_num_rows($result_retirada) > 0) {
        $oRetirada = db_utils::fieldsMemory($result_retirada, 0);
        $ve60_medidasaida = $oRetirada->ve60_medidasaida;
        $datahoraRetirada = $oRetirada->datahrretirada;
        $ve61_medidadevol = $oRetirada->ve61_medidadevol;
        $datahoraDevolucao = $oRetirada->datahrdevolucao;
    }

    /*
         * verifica abastecimentos anterior ao que o usuario deseja incluir
         */
    $result_abast1 = $clveicabast->sql_record($clveicabast->sql_query_file_anula(null, "ve70_medida,ve74_codigo,ve70_codigo,ve60_medidasaida,ve60_datasaida,ve61_medidadevol,ve61_datadevol,ve61_horadevol,ve60_horasaida,to_timestamp(ve70_dtabast||' '||ve70_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve70_dtabast", null, "ve60_codigo = $retirada) as x WHERE ve70_dtabast < to_timestamp('{$oDataAbast->getDate()}'||' '||'$horaabast'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP LIMIT 1;"));
    if (pg_num_rows($result_abast1) > 0) {
        $oAbast1       = db_utils::fieldsMemory($result_abast1, 0);
        $ve70_medida1  = $oAbast1->ve70_medida;
        $ve70_datahora1 = $oAbast1->ve70_dtabast;
    }

    /*
       * verifica abastecimentos posterior ao que o usuario deseja incluir
       */
    $result_abast3 = $clveicabast->sql_record($clveicabast->sql_query_file_anula(null, "ve70_medida,ve74_codigo,ve70_codigo,ve60_medidasaida,ve60_datasaida,ve61_medidadevol,ve61_datadevol,ve61_horadevol,ve60_horasaida,to_timestamp(ve70_dtabast||' '||ve70_hora::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP AS ve70_dtabast", "", "ve70_veiculos=$ve70_veiculos and ve60_codigo = $retirada) as x WHERE ve70_dtabast > to_timestamp('{$oDataAbast->getDate()}'||' '||'$horaabast'::varchar,'YYYY-MM-DD hh24:mi')::TIMESTAMP ORDER BY ve70_medida asc LIMIT 1;"));
    if (pg_num_rows($result_abast3) > 0) {
        $oAbast3       = db_utils::fieldsMemory($result_abast3, 0);
        $ve70_medida3  = $oAbast3->ve70_medida;
        $ve70_datahora3 = $oAbast3->ve70_dtabast;
        $ve70_codigo =  $oAbast3->ve70_codigo;
    }

    if (!empty($ve70_datahora1) && $datahoraAbastecimento < strtotime($ve70_datahora1)) {
        db_msgbox("Data ou Hora menor que abastecimento anterior.");
        $sqlerro = true;
    } else if (!empty($ve70_datahora3) && $datahoraAbastecimento > strtotime($ve70_datahora3)) {
        db_msgbox("Data ou Hora maior que abastecimento posterior.");
        $sqlerro = true;
    } else if (!empty($ve70_medida1) && $medida < $ve70_medida1) {
        db_msgbox("Medida de Abastecimento menor que Medida de abastecimento anterior");
        $sqlerro = true;
    } else if (!empty($ve70_medida3) && $medida > $ve70_medida3) {
        db_msgbox("Medida de Abastecimento maior que Medida de abastecimento posterior. Abastecimento: " . $ve70_codigo . " Medida: " . $ve70_medida3 . "km");
        $sqlerro = true;
    } else if (!empty($datahoraRetirada) && $datahoraAbastecimento < strtotime($datahoraRetirada)) {
        db_msgbox("Data ou Hora do Abastecimento menor que da Retirada");
        $sqlerro = true;
    } else if (!empty($datahoraDevolucao) && $datahoraAbastecimento > strtotime($datahoraDevolucao)) {
        db_msgbox("Data ou Hora do Abastecimento maior que da Devolucao");
        $sqlerro = true;
    } else if (!empty($ve60_medidasaida) && $medida < $ve60_medidasaida) {
        db_msgbox("Medida de Abastecimento menor que Medida de Retirada");
        $sqlerro = true;
    } else if (!empty($ve61_medidadevol) && $medida > $ve61_medidadevol) {
        db_msgbox("Medida de Abastecimento maior que Medida de Devolucao");
        $sqlerro = true;
    }
}

if (isset($sel_proprio) && $sel_proprio == 2) {
    if ($ve70_valor == "") {
        db_msgbox("Informar o valor abastecido.");
        $sqlerro = true;
    }
    if ($ve70_vlrun == '') {
        db_msgbox("Informar o valor do litro.");
        $sqlerro = true;
    }
    if ($ve71_nota == "" && $empnota == "" && $e69_codnota == "") {
        db_msgbox("Informar a nota.");
        $sqlerro = true;
    }
}

/**
 * Verificar Encerramento Periodo Patrimonial
 */

if (!empty($ve70_dtabast)) {
    $clcondataconf = new cl_condataconf;
    if (!$clcondataconf->verificaPeriodoPatrimonial($ve70_dtabast)) {
        db_msgbox($clcondataconf->erro_msg);
        $sqlerro  = true;
    }
}

if (isset($incluir) && $self != "") {
    if ($sqlerro == false) {

        if (isset($sel_proprio) && trim($sel_proprio) != "") {
            if ($sel_proprio == 0 && (isset($ve71_veiccadposto) &&
                    (trim($ve71_veiccadposto) == "" || trim($ve71_veiccadposto) != ""))) {
                $erro_msg = "Deve-se optar por escolher um Tipo de Posto";
                $clveicabast->erro_campo = "ve71_veiccadposto";
                $sqlerro = true;
            }
        }

        if ($sqlerro == false) {
            $clveicabast->ve70_usuario = db_getsession("DB_id_usuario");
            $clveicabast->ve70_data    = date("Y-m-d", db_getsession("DB_datausu"));
            $clveicabast->ve70_hora    = $ve70_hora; //db_hora();
            $clveicabast->ve70_ativo = "1";
            $clveicabast->incluir();
            $erro_msg = $clveicabast->erro_msg;
            if ($clveicabast->erro_status == "0") {
                $sqlerro = true;
            }
        }

        if ($sqlerro == false) {
            if (isset($posto_proprio) && trim($posto_proprio) != "") {
                if ($posto_proprio == 2) {
                    if (isset($empnota) && $empnota != null) {
                        $ve71nota = "";
                    } else {
                        $ve71nota = $e69_codnota;
                    }
                    $clveicabastposto->ve71_veicabast = $clveicabast->ve70_codigo;
                    $clveicabastposto->ve71_nota     = $ve71nota;
                    $clveicabastposto->incluir(null);
                    if ($clveicabastposto->erro_status == "0") {
                        $sqlerro = true;
                        $erro_msg = $clveicabastposto->erro_msg;
                    }
                } else {
                    $clveicabastposto->ve71_veicabast = $clveicabast->ve70_codigo;
                    $clveicabastposto->incluir(null);
                    if ($clveicabastposto->erro_status == "0") {
                        $sqlerro = true;
                        $erro_msg = $clveicabastposto->erro_msg;
                    }
                }
            } else {
                $clveicabastposto->ve71_veicabast = $clveicabast->ve70_codigo;
                $clveicabastposto->incluir(null);
                if ($clveicabastposto->erro_status == "0") {
                    $sqlerro = true;
                    $erro_msg = $clveicabastposto->erro_msg;
                }
            }
        }

        if ($sqlerro == false) {
            if (isset($empnota) && $empnota != "") {
                $clveicabastpostoempnota->ve72_veicabastposto = $clveicabastposto->ve71_codigo;
                $clveicabastpostoempnota->ve72_empnota = $e69_codnota;
                $clveicabastpostoempnota->incluir(null);
                if ($clveicabastpostoempnota->erro_status == "0") {
                    $sqlerro = true;
                    $erro_msg = $clveicabastpostoempnota->erro_msg;
                }
            }
        }


        if ($sqlerro == false) {
            if (isset($ve73_veicretirada) && $ve73_veicretirada != "") {
                $clveicabastretirada->ve73_veicabast = $clveicabast->ve70_codigo;
                $clveicabastretirada->incluir(null);
                if ($clveicabastretirada->erro_status == "0") {
                    $sqlerro = true;
                    $erro_msg = $clveicabastretirada->erro_msg;
                }
            }
        }
        if (isset($posto_proprio)) {
            $sel_proprio = $posto_proprio;
        }

        /*
         * Adicionando empabaste
         */
        if ($sqlerro == false) {
            $clempveiculos->si05_codabast = $clveicabast->ve70_codigo;
            $clempveiculos->si05_item_empenho = $si05_item_empenho;
            $clempveiculos->incluir(null);
            if ($clempveiculos->erro_status == "0") {
                $sqlerro = true;
                $erro_msg = $clempveiculos->erro_msg;
            }
        }

        db_fim_transacao($sqlerro);
    }
}
?>
    <html>

    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>

    <body bgcolor=#CCCCCC style='margin-top: 25px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <?
    include("forms/db_frmveicabast.php");
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
    </body>

    </html>
    <script>
        js_tabulacaoforms("form1", "ve70_veiculos", true, 1, "ve70_veiculos", true);
    </script>
<?
if (isset($incluir) && $self != "") {
    if ($clveicabast->erro_status == "0" || $sqlerro == true) {
        //$clveicabast->erro(true,false);
        if ($sqlerro == true) {
            db_msgbox($erro_msg);
        }
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clveicabast->erro_campo != "") {
            echo "<script> document.form1." . $clveicabast->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clveicabast->erro_campo . ".focus();</script>";
        }
    } else {
        $clveicabast->erro(true, true);
    }
}
?>
