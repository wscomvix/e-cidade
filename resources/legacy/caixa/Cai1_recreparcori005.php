<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_recreparcori_classe.php");
require_once("classes/db_recreparcarretipo_classe.php");
require_once("classes/db_recreparcdest_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrecreparcori       = new cl_recreparcori();
$clrecreparcdest      = new cl_recreparcdest();
$clrecreparcarretipo  = new cl_recreparcarretipo();

$db_opcao = 22;
$db_botao = false;
//Validar por tipo e origem se ja existe
if ( isset($oPost->alterar) ) {

	$lSqlErro = false;

  db_inicio_transacao();
  $db_opcao = 2;

  // faz a valida��o da altera��o
 	$sWhere  = "     k70_recori    = {$oPost->k70_recori}   ";
  $sWhere .= " and k70_vezesfim >= {$oPost->k70_vezesini} ";
  $sWhere .= " and k70_vezesini <= {$oPost->k70_vezesfim} ";
  $sWhere .= " and k70_codigo   != {$oPost->k70_codigo}   ";

 	$clrecreparcori->sql_record($clrecreparcori->sql_query_file(null, "*", null, $sWhere));

  // se passar na valida��o faz a altera��o
  if ( $clrecreparcori->numrows == 0 ) {

  	$lSqlErro = false;

	} else {
		//echo "<br><br><br>Aqui";
		$rsRecReparcArretipo = $clrecreparcarretipo->sql_record($clrecreparcarretipo->sql_query_recreparcori(null,
		                                                                                                     "*",
		                                                                                                     null,
		                                                                                                     $sWhere)
		                                                                                                     );
		$lSqlErro = false;
		if ($clrecreparcarretipo->numrows > 0) {

			for ($iInd = 0 ; $iInd < $clrecreparcarretipo->numrows; $iInd++) {

				$iK72_sequencial = db_utils::fieldsMemory($rsRecReparcArretipo,$iInd)->k72_sequencial;
				if (trim($iK72_sequencial) == "" && $iK72_sequencial == null) {

					$lSqlErro = true;
				}
			}
		}

		//tem que ler os valores atuais

		$sSqlRecParcArretipo = $clrecreparcarretipo->sql_query_recreparcori(null,
		                                                                    " k72_arretipo,k72_codigo ",
		                                                                    null,
		                                                                    "k72_codigo=$oPost->k70_codigo"
		                                                                    );

    $rsSqlRecParcArretipo = $clrecreparcarretipo->sql_record($sSqlRecParcArretipo);
		if ($clrecreparcarretipo->numrows > 0) {

			$k72_arretipo = db_utils::fieldsMemory($rsSqlRecParcArretipo,0)->k72_arretipo;

		  $sWhere .= " and k72_arretipo = $k72_arretipo";
		  $sSqlRecParcArretipo = $clrecreparcarretipo->sql_query_recreparcori(null, "*", null, $sWhere);
		  $rsSqlRecParcArretipo = $clrecreparcarretipo->sql_record($sSqlRecParcArretipo);

		  if ($clrecreparcarretipo->numrows > 0) {

			 $lSqlErro = true;
		  }
		}


		if ($lSqlErro) {
		  $sMsgErro = "J� existe um intervalo n�merico fornecido entre a parcela inicial e a final cadastrada para esta receita !";
	    $db_botao = true;
		}
	}

	if (!$lSqlErro) {

		// altera receita de origem
    $clrecreparcori->k70_recori   = $oPost->k70_recori;
    $clrecreparcori->k70_vezesini = $oPost->k70_vezesini;
    $clrecreparcori->k70_vezesfim = $oPost->k70_vezesfim;
    $clrecreparcori->alterar($oPost->k70_codigo);

    // alterar receita de destino
    $clrecreparcdest->k71_codigo  = $oPost->k70_codigo;
    $clrecreparcdest->k71_recdest = $oPost->k71_recdest;
    $clrecreparcdest->alterar($oPost->k70_codigo);

    // tratamento de erros
    if ( $clrecreparcori->erro_status == "0") {
      $lSqlErro = true;
      $sMsgErro = $clrecreparcori->erro_msg;
    }
    if( $clrecreparcdest->erro_status == "0" ) {
      $lSqlErro = true;
      $sMsgErro = $clrecreparcdest->erro_msg;
    }
	}

  db_fim_transacao($lSqlErro);


} else if( isset($oGet->chavepesquisa) ) {

   $db_opcao = 2;

   $sCampos  = "recreparcori.*, 					 			  ";
   $sCampos .= "recreparcdest.*,					 			  ";
   $sCampos .= "tabrec.k02_descr as k02_descrori, ";
   $sCampos .= "a.k02_descr 		 as k02_descrdest ";

   $result = $clrecreparcori->sql_record($clrecreparcori->sql_query_dadosrec($oGet->chavepesquisa,$sCampos));
   db_fieldsmemory($result,0);
   $db_botao = true;

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<?
  	include("forms/db_frmrecreparcori.php");
	?>
</center>
</body>
</html>
<?
if ( isset($oPost->alterar) ) {

  if ( $lSqlErro ) {
    db_msgbox($sMsgErro);
  } else {
    $clrecreparcori->erro(true,true);
  }
}
if(isset($oGet->chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.tipodebito.disabled = false;

         CurrentWindow.corpo.iframe_tipodebito.location.href='cai1_reparcoritipodebito001.php?k72_codigo=".@$k70_codigo."';

     ";
      if(isset($oGet->liberaaba)){
         echo "  parent.mo_camada('tipodebito');";
      }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
if ( $db_opcao==22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
