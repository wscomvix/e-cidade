<?php

use App\Models\ConfiguracaoPixBancoDoBrasil;
use App\Models\InstituicaoFinanceiraApiPix;
use App\Models\ISSQN\RedesimSettings;
use App\Services\Tributario\Arrecadacao\SavePixApiSettingsService;
use ECidade\V3\Datasource\Database;
use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request;

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_libdicionario.php");
require_once ("classes/db_numpref_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_libdicionario.php");

/**
 * @var Request $request
 */
$request = Registry::get('app.request');
db_postmemory($request->post()->all());
$clnumpref = new cl_numpref();
$db_opcao = 22;
$db_botao = false;
$instit = db_getsession("DB_instit");
$databaseInstance = Database::getInstance();
const DBSELLER_ID = '1';

function isDBSellerUser(): bool
{
    return db_getsession("DB_id_usuario") === DBSELLER_ID;
}

function savePixSettings(Request $request): void
{
    $apiConfiguration = null;
    $k03_instituicao_financeira = $request->post()->get('k03_instituicao_financeira');
    $instituicaoFinanceira = InstituicaoFinanceiraApiPix::find($k03_instituicao_financeira);

    if (empty($instituicaoFinanceira)) {
        throw new BusinessException('Instituição financeira não encontrada');
    }

    if ((int)$k03_instituicao_financeira === InstituicaoFinanceiraApiPix::BANCO_DO_BRASIL) {
        $apiConfiguration = new ConfiguracaoPixBancoDoBrasil();
    }

    if (empty($apiConfiguration)) {
        throw new BusinessException('Instituição financeira não habilitada para integração via PIX.');
    }

    $savePixApiSettingsService = new SavePixApiSettingsService($apiConfiguration);

    $savePixApiSettingsService->execute($request->post()->all());
}

if (isset($alterar)) {
    try {
        $databaseInstance->begin();
        $db_opcao = 2;
        $clnumpref->k03_instit = $instit;
        $clnumpref->alterar($k03_anousu, $instit);
        if(isDBSellerUser() && isset($k03_ativo_integracao_pix)) {
            savePixSettings($request);
        }
        $databaseInstance->commit();
    } catch (BusinessException $ex) {
        $databaseInstance->rollback();
        db_msgbox('Ocorreu um erro ao alterar os parâmetros do módulo Arrecadaçao: ' . $ex->getMessage());
    }
} elseif (isset($chavepesquisa)) {

    $db_opcao = 2;
    $result = $clnumpref->sql_record($clnumpref->sql_query($chavepesquisa, $instit));
    db_fieldsmemory($result, 0);
    $db_botao = true;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
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
    <table width="800" border="0" align="center" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" align="top" bgcolor="#CCCCCC">
          <center>
              <?php include("forms/db_frmnumpref.php"); ?>
          </center>
    	  </td>
      </tr>
    </table>
    <?php
  db_menu(
      db_getsession("DB_id_usuario"),
      db_getsession("DB_modulo"),
      db_getsession("DB_anousu"),
      db_getsession("DB_instit")
  );
  ?>
  </body>
</html>
<?php
if (isset($alterar) ) {

  if ($clnumpref->erro_status=="0") {

    $clnumpref->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clnumpref->erro_campo!="") {

      echo "<script> document.form1.".$clnumpref->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clnumpref->erro_campo.".focus();</script>";
    }

  }else{
    $clnumpref->erro(true,true);
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
