<?php

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsaservico_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsanumpre_classe.php");
include("dbforms/db_funcoes.php");

function db_calculaLinhasTexto22($texto){

   $linha = 1;
   $caracter = 0;
   for ($i = 0;$i < strlen($texto); $i++){
       if ($caracter == 59 or substr($texto,$i,1) == "\n"){
         $linha++;
         $caracter = 0;
       }
       $caracter++;
   }
   return $linha;
}

$clissnotaavulsaservico = new cl_issnotaavulsaservico;
$clissnotaavulsa        = new cl_issnotaavulsa;
$clissnotaavulsatomador = new cl_issnotaavulsatomador;
$clissnotaavulsanumpre = new cl_issnotaavulsanumpre();
$clparissqn             = new cl_parissqn;
$get                    = db_utils::postmemory($_GET);
$post                   = db_utils::postmemory($_POST);
$db_opcao               = 22;
$db_botao               = false;
$q62_notaavulsa         = isset($post->q62_issnotaavulsa)?$post->q62_issnotaavulsa:$get->q51_sequencial;
$lGeraNota              = false;
$emitenota              = true;
$rsPar                  = $clparissqn->sql_record($clparissqn->sql_query(null,"*"));
$oPar                   = db_utils::fieldsMemory($rsPar,0);

if(isset($post->alterar) || isset($post->excluir) || isset($post->incluir)){
  $sqlerro = false;
}

if(isset($post->incluir)){

  if($sqlerro==false){

    db_inicio_transacao();
    $clissnotaavulsaservico->incluir(null);
    $erro_msg = $clissnotaavulsaservico->erro_msg;
    if($clissnotaavulsaservico->erro_status==0){
      $sqlerro=true;
    }else{

      $post->totlinhas += db_calculaLinhasTexto22($post->q62_discriminacao);
    }
    db_fim_transacao($sqlerro);
  }

}else if(isset($post->alterar)){
  if($sqlerro==false){

    db_inicio_transacao();
    $clissnotaavulsaservico->alterar($post->q62_sequencial);
    $post->totlinhas += db_calculaLinhasTexto22($post->q62_discriminacao);
    $erro_msg = $clissnotaavulsaservico->erro_msg;
    if($clissnotaavulsaservico->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($post->excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clissnotaavulsaservico->excluir($post->q62_sequencial);
    $erro_msg = $clissnotaavulsaservico->erro_msg;
    if($clissnotaavulsaservico->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clissnotaavulsaservico->sql_record($clissnotaavulsaservico->sql_query($post->q62_sequencial));
   if($result!=false && $clissnotaavulsaservico->numrows>0){
     db_fieldsmemory($result,0);
   }
}
if (isset($post->recibo)) {

    $lSqlErro = false;
    $rsNumpre = $clissnotaavulsanumpre->sql_record($clissnotaavulsanumpre->sql_query(null, "*",null," q52_issnotaavulsa = {$post->q62_issnotaavulsa} "));
    $oNumpre = db_utils::fieldsMemory($rsNumpre, 0);

    if (!empty($oNumpre->q52_sequencial)) {
        db_msgbox('J� existe uma guia gerada para essa nota.');
    } else {

        $lSqlErro = false;
        $rsNot = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query($post->q62_issnotaavulsa, "*"));
        $oNot = db_utils::fieldsMemory($rsNot, 0);
        if ($post->totlinhas > 40) {

            db_msgbox('Total das linhas da descri��o da nota maior que o permitido (40 linha)');
            $emitenota = true;
            $lGeraNota = true;
            $db_botao = true;
        } else if (str_replace(",", ".", $post->vlrrectotal) >= $oPar->q60_notaavulsavlrmin) {

            db_inicio_transacao();
            $clarrecad = new cl_arrecad();
            $clarrehist = new cl_arrehist();
            $rsNum = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
            $oNum = db_utils::fieldsMemory($rsNum, 0);
            //Codigo numpre do Recibo
            $rsNumnov = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numnov");
            $rsTom = $clissnotaavulsatomador->sql_record($clissnotaavulsatomador->sql_query_tomador($post->q62_issnotaavulsa));
            $oTom = db_utils::fieldsMemory($rsTom, 0);
            /**
             * A data do vencimento do ISSQN � sempre no m�s subsequente ao do fato gerador
             * $oPar->q60_notaavulsadiasprazo -> dia do vencimento
             */
            $oDataPgto = new DateTime(date($oTom->q53_dtservico));
            $oDataPgto->modify('+ 1 month');
            $oNumnov = db_utils::fieldsMemory($rsNumnov, 0);
            $aDataPgto = explode("-", $oDataPgto->format('Y-m-d'));
            $dataPagto = date("Y-m-d", mktime(0, 0, 0, $aDataPgto[1], $oPar->q60_notaavulsadiasprazo, $aDataPgto[0]));

            $clarrecad->k00_numpre = $oNum->k03_numpre;
            $clarrecad->k00_numpar = 1;
            $clarrecad->k00_numcgm = $oNot->q02_numcgm;
            $clarrecad->k00_valor = str_replace(",", ".", str_replace(".", "", $post->vlrrectotal));
            $clarrecad->k00_receit = $oPar->q60_receit;
            $clarrecad->k00_tipo = $oPar->q60_tipo_notaavulsa;
            $clarrecad->k00_dtoper = $oNot->q51_dtemiss;
            $clarrecad->k00_dtvenc = $dataPagto;
            $clarrecad->k00_numtot = 1;
            $clarrecad->k00_numdig = 1;
            $clarrecad->k00_tipojm = 1;
            $clarrecad->k00_hist = $oPar->q60_histsemmov;
            $clarrecad->incluir();
            if ($clarrecad->erro_status == 0) {

                $lSqlErro = true;
                $erro_msg = $clarrecad->erro_msg;
            }
            if (!$lSqlErro) {

                $clarrehist->k00_numpre = $oNum->k03_numpre;
                $clarrehist->k00_numpar = 1;
                $clarrehist->k00_hist = $oPar->q60_histsemmov;
                $clarrehist->k00_dtoper = $oNot->q51_dtemiss;
                $clarrehist->k00_id_usuario = db_getsession("DB_id_usuario");
                $clarrehist->k00_hora = date("h:i");
                $clarrehist->k00_histtxt = "Valor referente a nota fiscal avulsa n� " . $oNot->q51_numnota . " de (" . db_formatar($oNot->q51_dtemiss, "d") . ")";
                $clarrehist->k00_limithist = null;
                $clarrehist->incluir(null);
                if ($clarrehist->erro_status == 0) {

                    $lSqlErro = true;
                    $erro_msg = $clarrehist->erro_msg;

                }

            }
            if (!$lSqlErro) {

                $clissnotaavulsanumpre = new cl_issnotaavulsanumpre();
                $clissnotaavulsanumpre->q52_issnotaavulsa = $q62_issnotaavulsa;
                $clissnotaavulsanumpre->q52_numpre = $oNum->k03_numpre;
                $clissnotaavulsanumpre->q52_numnov = $oNumnov->k03_numnov;
                $clissnotaavulsanumpre->incluir(null);
                if ($clissnotaavulsanumpre->erro_status == 0) {

                    $lSqlErro = true;
                    $erro_msg = $clissnotaavulsanumpre->erro_msg;

                }
                if (!$lSqlErro) {

                    $clarreinscr = new cl_arreinscr();
                    $clarreinscr->k00_perc = 100;
                    $clarreinscr->k00_inscr = $oNot->q02_inscr;
                    $clarreinscr->k00_numpre = $oNum->k03_numpre;
                    $clarreinscr->incluir($oNum->k03_numpre, $oNot->q02_inscr);
                    if ($clarreinscr->erro_status == 0) {

                        $lSqlErro = true;
                        $erro_msg = $clarreinscr->erro_msg;
                    }
                }

            }

            db_fim_transacao($lSqlErro);
            if ($lSqlErro) {

                db_msgbox($erro_msg);
            } else {

                $db_botao = false;
                $rsObs = $clissnotaavulsaservico->sql_record(
                    $clissnotaavulsaservico->sql_query(null, "sum(q62_vlrissqn) as tvlrissqn, sum(q62_vlrdeducao) as tvlrdeducoes, sum(q62_vlrtotal) as tvlrtotal",
                        null, "q62_issnotaavulsa=" . $post->q62_issnotaavulsa));
                $oObs = db_utils::fieldsmemory($rsObs, 0);
                $obs = "Referente a nota fiscal avulsa n� " . $oNot->q51_numnota . "\n";
                $obs .= "Tomador : " . $oTom->z01_cgccpf . " - " . $oTom->z01_nome . "\n";
                $obs .= "Imposto : R$ " . trim(db_formatar($oObs->tvlrissqn, "f")) . "\n";
                $obs .= "Dedu��es: R$ " . trim(db_formatar($oObs->tvlrdeducoes, "f")) . "\n";
                $obs .= "Valor servi�o: R$ " . trim(db_formatar($oObs->tvlrtotal, "f")) . "\n";
                session_register("DB_obsrecibo", $obs);
                db_putsession("DB_obsrecibo", $obs);
                $url = "iss1_issnotaavulsarecibo.php?numpre=" . $oNum->k03_numpre . "&tipo=" . $oPar->q60_tipo_notaavulsa . "&ver_inscr=" . $oNot->q02_inscr;
                $url .= "&numcgm=" . $oNot->q02_numcgm . "&emrec=t&CHECK10=" . $oNum->k03_numpre . "P1&tipo_debito=" . $oPar->q60_tipo_notaavulsa;
                $url .= "&k03_tipo=" . $oPar->q60_tipo_notaavulsa . "&k03_parcelamento=f&k03_perparc=f&ver_numcgm=" . $oNot->q02_numcgm;
                $url .= "&totregistros=1&k03_numnov=" . $oNumnov->k03_numnov . "&loteador=";
                echo "<script>\n";

                echo " window.open('$url','','location=0');\n";
                echo "</script>\n";

            }

        }
        $lGeraNota = true;
    }
}
if (isset($post->notaavulsa)){

   if ($post->totlinhas > 40 ){

    db_msgbox('Total das linhs da descri��o da nota maior que o permitido (40 linha)');
    $emitenota = true;
    $lGeraNota = true;
    $db_botao  = true;

   }else{
      if ($clissnotaavulsa->emiteNotaAvulsa($post->q62_issnotaavulsa)){

        $emitenota = false;
        $lGeraNota = false;
        $db_botao  = false;
      }

   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
	<?php
	include("forms/db_frmissnotaavulsaservico.php");
	?>
    </center>
  </body>
</html>
<?php
if(isset($alterar) || isset($excluir) || isset($incluir)){
    if ($erro_msg != ''){
       db_msgbox($erro_msg);
    }
    if($clissnotaavulsaservico->erro_campo!=""){
        echo "<script> document.form1.".$clissnotaavulsaservico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clissnotaavulsaservico->erro_campo.".focus();</script>";
    }
}
?>
<script>
function js_emiteNota(num){

   url = "iss2_issnotaavulsanotafiscal002.php?q51_sequencial="+num;
   window.open(url,'','location=0');

}
<?php

if (isset($post->recibo)){

    echo "document.getElementById('db_opcao').disabled=true;\n";
    echo "document.getElementById('recibo').disabled=true;\n";

}
if (isset($post->notaavulsa)){

    echo "document.getElementById('db_opcao').disabled=true;\n";
    echo "document.getElementById('recibo').disabled=true;\n";
    echo "document.getElementById('nota').disabled=true;\n";

}
?>
</script>
