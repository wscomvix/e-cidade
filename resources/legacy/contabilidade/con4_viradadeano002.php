<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_virada_classe.php");
require_once("classes/db_db_viradaitem_classe.php");
require_once("classes/db_db_viradaitemlog_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldb_virada        = new cl_db_virada();
$cldb_viradaitem    = new cl_db_viradaitem();
$cldb_viradaitemlog = new cl_db_viradaitemlog();

$sqlerro = false;

$aListaItens = explode("_",$lista);
array_shift($aListaItens);
$sListaItens = implode(",",$aListaItens);

$iAnoOrigem  = db_getsession('DB_anousu');
$iAnoDestino = ($iAnoOrigem + 1);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script>
function js_imprime(virada){

  jan = window.open('con4_viradadeano003.php?&virada='+virada,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  //js_OpenJanelaIframe('','db_iframe_relatorio','con4_viradadeano003.php?&virada='+virada,'Pesquisa',true);
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0">
<table width="750" border="1" align="center" cellspacing="0"
	bgcolor="#CCCCCC">
	<tr>
		<td><br>
    <?php
    db_criatermometro('termometro','Concluido...','blue',1);
    echo "<br><br>";
    db_criatermometro('termometroitem','Concluido...','blue',1);

    db_inicio_transacao();

    // Consulta todas institui��es

    $sSqlConsultaInstit = "SELECT codigo, nomeinst, db21_tipoinstit FROM db_config WHERE db21_tipoinstit IN (1,2,5) ORDER BY 1";
    $rsConsultaInstit   = db_query($sSqlConsultaInstit);
    $iLinhasInstit      = pg_num_rows($rsConsultaInstit);

    $aInstit = array();
    for ( $iInd=0; $iInd < $iLinhasInstit; $iInd++ ) {
    	$oInstit = db_utils::fieldsMemory($rsConsultaInstit,$iInd);
    	$aInstit[$oInstit->codigo] = $oInstit->nomeinst;
    }
    $sListaInstit = implode(",",$aInstit);

    // Verifica apartir dos itens informados se deve ser feito a valida��o do or�amento

    $lValida = false;
    foreach ( $aListaItens as $iInd => $iItem ) {
      if ( in_array($iItem,array(1,2,3,4,8,11,12,15))) {
        $lValida = true;
      }
    }

    if ( $lValida ) {

	    if ( !$sqlerro ) {

		    // Verifica se existe uma vers�o do PPA homologada
		    // $sSqlPPAVersao    = " SELECT * FROM ppaversao        ";
		    // $sSqlPPAVersao   .= " WHERE o119_versaofinal IS TRUE ";
		    // $rsPPAVersao      = db_query($sSqlPPAVersao);
		    // $iLinhasPPAVersao = pg_num_rows($rsPPAVersao);

		  	// if ( $iLinhasPPAVersao > 0 ) {

		  	// 	$aListaInstitErro = array();

			  //   foreach ( $aInstit as $iInstit => $sDescrInstit ) {

			  //   	// Verifica se existe vincula��o do PPA com or�amento
					// $sSqlPPAIntegracao  = " select ppaintegracao.*                	 ";
					// $sSqlPPAIntegracao .= "   from ppaintegracao                  	 ";
					// $sSqlPPAIntegracao .= "	  join db_config on o123_instit = codigo ";
					// $sSqlPPAIntegracao .= "  where o123_ano      = {$iAnoDestino} 	 ";
					// $sSqlPPAIntegracao .= "    and prefeitura = 't'     		     ";
					// $sSqlPPAIntegracao .= "    and o123_situacao = 1              	 ";
					// $sSqlPPAIntegracao .= "    and o123_tipointegracao = 1        	 ";

			  //     $rsPPAIntegracao    = db_query($sSqlPPAIntegracao);

		   //      if ( pg_num_rows($rsPPAIntegracao) == 0 ) {
		   //      	$aListaInstitErro[] = $sDescrInstit;
		   //        $sqlerro  = true;
		   //      }
		   //    }

		   //    if ( $sqlerro ) {
		   //    	if ( count($aListaInstitErro) > 1 ) {
		   //        $erro_msg  = "Exporta��o do PPA para Or�amento n�o encontrado nas institui��es:\\n";
		   //        $erro_msg .= implode("\\n",$aListaInstitErro);
		   //     	} else {
			  //       $erro_msg  = "Exporta��o do PPA para Or�amento n�o encontrado na institui�ao {$aListaInstitErro[0]}!";
		   //    	}
		   //    }

		   //  } else {

		    	$sErroMsg = '';

		    	foreach ( $aInstit as $iInstit => $sDescrInstit ) {

		      	// Verifica se existe cadastro de contas para o exerc�cio de destino
                  $sSqlConPlano  = " SELECT * FROM conplanoreduz                                            ";
                  $sSqlConPlano .= " JOIN conplanoexe ON (c62_anousu, c62_reduz) = (c61_anousu, c61_reduz)  ";
                  $sSqlConPlano .= " WHERE c61_instit = {$iInstit}                                          ";
                  $sSqlConPlano .= "   AND c61_anousu = {$iAnoDestino} LIMIT 1                              ";

                  $rsConPlano    = db_query($sSqlConPlano);

                  if ( pg_num_rows($rsConPlano) == 0 ) {
                    $sErroMsg = "Cadastro de contas para o exerc�cio {$iAnoDestino} n�o encontrado na institui��o {$sDescrInstit}!\\n";
                    $sqlerro  = true;
                }

                if ($oInstit->db21_tipoinstit == 1){

              // Verifica se existe alguma receita configurada para o exerc�cio de destino
                  $sSqlOrcReceita  = "SELECT * FROM orcreceita                 ";
                  $sSqlOrcReceita .= "WHERE o70_anousu = {$iAnoDestino} LIMIT 1";

                  $rsOrcReceita    = db_query($sSqlOrcReceita);

                  if ( pg_num_rows($rsOrcReceita) == 0 ) {
                    $sErroMsg = "Nenhuma receita encontrada para o or�amento de {$iAnoDestino} na institui��o {$sDescrInstit}\\n";
                    $sqlerro  = true;
                }

              // Verifica se existe alguma conta de despesa cadastrada para o exerc�cio de destino
                $sSqlOrcDotacao  = " SELECT * FROM orcdotacao                  ";
                $sSqlOrcDotacao .= " WHERE o58_instit = {$iInstit}             ";
                $sSqlOrcDotacao .= "   AND o58_anousu = {$iAnoDestino} limit 1 ";

                $rsOrcDotacao    = db_query($sSqlOrcDotacao);

                if ( pg_num_rows($rsOrcDotacao) == 0 ) {
                    $sErroMsg = "Nenhuma conta de despesa cadastrada para o exerc�cio {$iAnoDestino} na institui��o {$sDescrInstit}\\n";
                    $sqlerro  = true;
                }
            }
        }

	   if ( $sqlerro ) {
	     $erro_msg  = "Processamento Interrompido!\\n";
	     $erro_msg .= $sErroMsg;
	   }
	 // }
	  }
    }

    if ( !$sqlerro ) {

	    // inclui na db_virada
	    $cldb_virada->c30_anoorigem  = $anoorigem;
	    $cldb_virada->c30_anodestino = $anodestino;
	    $cldb_virada->c30_usuario    = db_getsession("DB_id_usuario");
	    $cldb_virada->c30_data       = date("Y-m-d");
	    $cldb_virada->c30_hora       = date("H:i");
	    $cldb_virada->c30_situacao   = 1;
	    $cldb_virada->incluir(null);
	    if ($cldb_virada->erro_status==0) {
	      $sqlerro = true;
	      $erro_msg = $cldb_virada->erro_msg;
	    }

	    //echo "<pre>".print_r($lista)."</pre>";

	    if($sqlerro == false) {
	      $aItem  = split("_",$lista);
	      $iCountItem = sizeof($aItem);
	      for ($iItem=0; $iItem<$iCountItem; $iItem++) {
	        if (($aItem[$iItem] != "") && ($sqlerro == false)) {

	          $sqlcaditem = "SELECT * FROM db_viradacaditem WHERE c33_sequencial=".$aItem[$iItem];
	          $resultcaditem = db_query($sqlcaditem);
	          db_fieldsmemory($resultcaditem, 0);

	          // inclui na db_viradaitem
	          $cldb_viradaitem->c31_db_virada        = $cldb_virada->c30_sequencial;
	          $cldb_viradaitem->c31_db_viradacaditem = $aItem[$iItem];
	          $cldb_viradaitem->c31_situacao         = 1;
	          $cldb_viradaitem->incluir(null);
	          if ($cldb_viradaitem->erro_status==0) {
	            $sqlerro = true;
	            $erro_msg = $cldb_viradaitem->erro_msg;
	          }

	          if($sqlerro == false) {
                  $sArquivoItemVirada = "con4_viradadeano002_item".str_pad("{$aItem[$iItem]}", 3, "0", STR_PAD_LEFT).".php";
                  if(file_exists(dirname(__FILE__)."/".$sArquivoItemVirada)) {
                      // Seta Variavel Para Erro, caso houver
                      $erro_msg = "Erro ao processar item {$c33_sequencial}-{$c33_descricao}!\\n";

                      $sMensagemTermometroItem = "Processando Item {$c33_sequencial}-{$c33_descricao}...";

                      // Inclui programa adequado para ser processado
                      require_once($sArquivoItemVirada);

                      if($sqlerro==true) {
                          break;
                      }
                  } else {
                      $sqlerro  = true;
                      $erro_msg = "Item de Virada {$aItem[$iItem]} n�o dispon�vel! Processamento Interrompido!";
                      break;
                  }
	          }
	        }
	        db_atutermometro($iItem, $iCountItem, 'termometro', 1, "Processando Virada de Ano do Exercicio {$anoorigem} para {$anodestino}");
	      }
	    }
    }

    db_fim_transacao($sqlerro);

    $sSqlInstit = "select cgc from db_config where codigo = " . db_getsession("DB_instit");
    $rsResultCnpj = db_query($sSqlInstit);
    $sCnpj = db_utils::fieldsMemory($rsResultCnpj, 0)->cgc;

    /**
     * Copia arquivos XML de dados dos elementos da despesa e da receita para o ano destino da virada
     * ap�s processamento de qualquer item.
     */
    $sArquivoDesp = "legacy_config/sicom/" . db_getsession("DB_anousu") . "/{$sCnpj}_sicomelementodespesa.xml";
    $sArquivoRec = "legacy_config/sicom/" . db_getsession("DB_anousu") . "/{$sCnpj}_sicomelementodespesa.xml";

    if (file_exists($sArquivoDesp) || file_exists($sArquivoRec)){

      passthru("mkdir legacy_config/sicom/{$iAnoDestino}");
      passthru("cp legacy_config/sicom/{$iAnoOrigem}/*.xml legacy_config/sicom/{$iAnoDestino}/");
    }


    ?>
  </td>
	</tr>
	<tr>
	  <td align="center">
    <?php

    if($sqlerro == false){
      $virada = $cldb_virada->c30_sequencial;
      echo "
            <script>
              var confirmar = confirm('O procedimento de virada do(s) item(s) foi realizado com sucesso.\\nVisualizar o relat�rio de logs das inconsist�ncias desta opera��o?');
              if(confirmar==true){
                js_imprime($virada);
              }else{
                alert('Procedimento de virada concluido.');
              }
              parent.document.form1.submit();

            </script>
          ";
    } else {
      db_msgbox($erro_msg);
      echo "<script>parent.document.form1.submit();</script>";
    }
    ?>
	  </td>
	</tr>
</table>
</body>
</html>
