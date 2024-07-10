<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/itbi/Itbi.model.php");
$oGet = db_utils::postMemory($_GET);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <form action="" name="form1" method="post">
	      <center>
          <table id="processando" style="visibility:visible" width="100%" border="0" cellspacing="0">
            <tr>
              <td height="45">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="center"><font size="5">Processando Classifica��o Aguarde...
                </font></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
          <table id="processado" style="visibility:hidden" width="100%" border="0" cellspacing="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="center"><font size="5">Processo Conclu�do.</font></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
        </center>
      </form>
	  </td>
  </tr>
</table>
</body>
</html>
<?php

   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

   flush();

   try {


   	db_inicio_transacao();

   	if(!isset($oGet->codret) || $oGet->codret == ""){
   		throw new Exception("C�digo do arquivo ({$oGet->codret}) de Retorno Inv�lido.");
   	}
     $oInstit = new Instituicao(db_getsession('DB_instit'));

     if($oInstit->getCodigoCliente() == Instituicao::COD_CLI_PMPIRAPORA) {
       $sSqlIntegracaoJMS = "UPDATE disbanco
                              SET k00_numpre = debitos_jms.k00_numpre,
                                  k00_numpar = debitos_jms.k00_numpar
                              FROM debitos_jms
                              WHERE disbanco.k00_numpre = debitos_jms.k00_numpre_old
                                  AND disbanco.k00_numpar = debitos_jms.k00_numpar_old
                                  AND disbanco.codret = {$oGet->codret}";
       if (!db_query($sSqlIntegracaoJMS)) {
         throw new Exception(str_replace("\n", "", substr(pg_last_error(), 0, strpos(pg_last_error(), "CONTEXT"))));
       }
       /**
        * Quando s�o importados as guias da JMS, as do ecidade ficam com numpre com tamanho < 6. Para n�o ficar lixo,
        * setamos o classi = true.
        */
       $sSqlIgnoraGuiasEcidade = "UPDATE disbanco
                                  SET classi = true
                                  WHERE char_length(k00_numpre::varchar) < 6
                                  AND disbanco.codret = {$oGet->codret}";
     }

    $config = db_query("select * from db_config where codigo = ".db_getsession("DB_instit"));
    db_fieldsmemory($config,0);

   	$sSql = "select fc_executa_baixa_banco($oGet->codret,'".date("Y-m-d",db_getsession("DB_datausu"))."')";
   	$rsBaixaBanco = db_query($sSql);
   	if (!$rsBaixaBanco) {
   		throw new Exception(str_replace("\n","",substr(pg_last_error(), 0, strpos(pg_last_error(),"CONTEXT"))));
   	}

   	$sRetornoBaixaBanco = db_utils::fieldsMemory($rsBaixaBanco, 0)->fc_executa_baixa_banco;
   	if (substr($sRetornoBaixaBanco,0,1) != '1') {
   	  throw new Exception($sRetornoBaixaBanco);
   	}
    $oItbi = new Itbi();
    $oItbi->processarTransferenciaAutomatica($oGet->codret);
   	$oItbi->processarInsercaoPromitente($oGet->codret);

    db_msgbox($sRetornoBaixaBanco);

   	db_fim_transacao(false);

   } catch (Exception $oErro) {

   	db_fim_transacao(true);
   	$sMsgRetorno  = "Erro durante o processamento da Classifica��o da Baixa de Banco!\\n\\n{$oErro->getMessage()}";
   	db_msgbox($sMsgRetorno);

   }

   db_redireciona("cai4_baixabanco002.php?db_opcao=4&pesquisar=true&k15_codbco={$oGet->k15_codbco}&k15_codage={$oGet->k15_codage}");
?>
