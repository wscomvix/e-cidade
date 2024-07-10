<?
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_parecerlicitacao_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_liclicitasituacao_classe.php");
include("classes/db_liclicita_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clparecerlicitacao = new cl_parecerlicitacao;
$pctipocompra = new cl_pctipocompra; 

$db_opcao = 22;
$db_botao = false;
$recebeAlteracao = true;

if($_POST['json']){
    $oLicitacao = str_replace('\\','',$_POST);
    $oLicitacao = json_decode($oLicitacao['json']);
	$valores = array();
    if(!$oLicitacao->inicio){
		$sqlDescricao = $pctipocompra->sql_buscaDescricao(""," DISTINCT pc50_descr, l20_codigo, l20_anousu", " l20_anousu DESC ",
			" l20_edital = $oLicitacao->edital",'1');
	}
    else{
		$sqlDescricao = $clparecerlicitacao->sql_query('',"pc50_descr, l200_licitacao",'',' l200_sequencial = '
			.$oLicitacao->sequencial.' and l200_licitacao = '.$oLicitacao->licitacao);
	}
    $sqlNumber = db_query($sqlDescricao);
    $descricao = db_utils::fieldsMemory($sqlNumber, 0);
    if($descricao->pc50_descr != ''){
		$valores[] = utf8_encode($descricao->pc50_descr);
    }

    if($descricao->l20_codigo != ''){
		$valores[] = $descricao->l20_codigo;
    }else $valores[] = $descricao->l200_licitacao;

    echo json_encode($valores);
    die();
}

if (isset($alterar)) {

  /**
   * Verifica data de julgamento e homologa��o da licita��o do tipo julgamento
   */
    $dtDataParecer = date('Y-m-d', strtotime(str_replace('/', '-', $l200_data)));

    $clliclicita        = new cl_liclicita;
    $sSql               = $clliclicita->sql_query_file('', 'l20_dataaber, l20_licsituacao','','l20_codigo = '.$l200_licitacao);
    $oLicitacao         = db_utils::fieldsMemory(db_query($sSql), 0);

    //Jur�dico Julgamento
    if($l200_tipoparecer == "3") {

        $clliclicitasituacao    = new cl_liclicitasituacao;
        $sOrder                 = 'l11_sequencial desc';
        $sWhereJulg             = 'l11_liclicita = '.$l200_licitacao.'and l11_licsituacao = 1';
        $sSqlJulg               = $clliclicitasituacao->sql_query(null, 'l11_data', $sOrder, $sWhereJulg);
        $dtDataJulg             = db_utils::fieldsMemory(db_query($sSqlJulg), 0)->l11_data;
        $dtDataJulgShow         = str_replace('-', '/', date('d-m-Y', strtotime($dtDataJulg)));

        //Licita��o n�o julgada
        if($oLicitacao->l20_licsituacao == 0){

            $clliclicitasituacao->erro_msg = 'Usu�rio, � necess�rio efetuar o julgamento da licita��o.';
            echo "<script>alert('{$clliclicitasituacao->erro_msg}');</script>";
            db_redireciona('lic1_parecerlicitacao001.php');

        }

        //licitacao julgada
        if($oLicitacao->l20_licsituacao == 1){

            if ($dtDataParecer < $dtDataJulg) {

                $clliclicitasituacao->erro_msg = 'Licita��o julgada em '.$dtDataJulgShow.'. A data do parecer dever� ser igual ou superior a data de julgamento.';
                echo "<script>alert('{$clliclicitasituacao->erro_msg}');</script>";
                db_redireciona('lic1_parecerlicitacao001.php');

            }
        }

        //licita��o homologada
        if($oLicitacao->l20_licsituacao == 10){

            $clliclicitasituacao->erro_msg = 'Licita��o j� homologada.';
            echo "<script>alert('{$clliclicitasituacao->erro_msg}');</script>";
            db_redireciona('lic1_parecerlicitacao001.php');

        }
    }

    /**
     * Verifica data de publica��o do edital da licita��o
     */
    if($l200_tipoparecer == "2") {

        $dtDataEmissao      = $oLicitacao->l20_dataaber;
        $dtDataEmissaoShow  = str_replace('-', '/', date('d-m-Y', strtotime($dtDataEmissao)));

        if($dtDataParecer < $dtDataEmissao){

            $clliclicitasituacao->erro_msg = 'Edital emitido em '.$dtDataEmissaoShow.'. A data do parecer do tipo (2- Jurdico-Edital) n�o pode ser anterior a data de emiss�o do edital.';
            echo "<script>alert('{$clliclicitasituacao->erro_msg}');</script>";
            db_redireciona('lic1_parecerlicitacao001.php');

        }

    }

    $result_dtcadcgm = db_query("select z09_datacadastro from historicocgm where z09_numcgm = {$l200_numcgm} and z09_tipo = 1");
    db_fieldsmemory($result_dtcadcgm, 0)->z09_datacadastro;
    $dtsession   = date("Y-m-d",db_getsession("DB_datausu"));

    if($dtsession < $z09_datacadastro){
        $msg_erro = "Usu�rio: A data de cadastro do CGM informado � superior a data do procedimento que est� sendo realizado. Corrija a data de cadastro do CGM e tente novamente!";
        echo "<script>alert('{$msg_erro}');</script>";
        db_redireciona('lic1_parecerlicitacao001.php');
    }

    $rsResult = db_query("select l20_codtipocom from liclicita where l20_codigo = $l200_licitacao");
  $codtipocom = db_utils::fieldsMemory($rsResult, 0)->l20_codtipocom;
  $count = 1;
  if ($codtipocom == 4 || $codtipocom == 6) { 
    $sSQL = "SELECT l206_fornecedor
              FROM habilitacaoforn
                INNER JOIN pcforne ON pcforne.pc60_numcgm = habilitacaoforn.l206_fornecedor
                INNER JOIN liclicita ON liclicita.l20_codigo = habilitacaoforn.l206_licitacao
                INNER JOIN cgm ON cgm.z01_numcgm = pcforne.pc60_numcgm
                INNER JOIN db_usuarios ON db_usuarios.id_usuario = pcforne.pc60_usuario
                INNER JOIN db_config ON db_config.codigo = liclicita.l20_instit
                INNER JOIN db_usuarios AS a ON a.id_usuario = liclicita.l20_id_usucria
                INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                INNER JOIN liclocal ON liclocal.l26_codigo = liclicita.l20_liclocal
                INNER JOIN liccomissao ON liccomissao.l30_codigo = liclicita.l20_liccomissao
                INNER JOIN licsituacao ON licsituacao.l08_sequencial = liclicita.l20_licsituacao
                  WHERE l206_licitacao = $l200_licitacao";
    
    $rsResult = db_query($sSQL);
    $count = pg_num_rows($rsResult);
  }
  
  $sql = db_query("select z01_cgccpf as cpf from cgm where z01_numcgm = $l200_numcgm");
  $cgm = db_utils::fieldsMemory($sql, 0)->cpf;
  
  $db_opcao = 2;

  if (strlen($cgm) > 11) {
    $recebeAlteracao = false;
    echo "<script>alert('O CGM selecionado dever� ser de Pessoa F�sica.');</script>";
    $db_botao = true;
  }
  else if (empty($count) && $l200_tipoparecer != "2") {
    $recebeAlteracao = false;
    echo "<script>alert('Inclus�o abortada. Verifique os fornecedores habilitados!');</script>";
    $db_botao = true;
  } else {
    
      db_inicio_transacao();
      $clparecerlicitacao->alterar($l200_sequencial); 
      db_fim_transacao();
  }

  /*if(strlen($cgm) <= 11){
	  db_inicio_transacao();
	  $clparecerlicitacao->alterar($l200_sequencial);
	  db_fim_transacao();
  }else {
      $recebeAlteracao = false;
      echo "<script>alert('O CGM selecionado dever� ser de Pessoa F�sica.');</script>";
      $db_botao = true;

  }*/

} else if(isset($chavepesquisa)) {
   $db_opcao = 2;
   $result = $clparecerlicitacao->sql_record($clparecerlicitacao->sql_query($chavepesquisa)); 
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
  <fieldset style=" margin-top: 30px; width: 500px; height: 182px;">
  <legend>Parecer da Licita&ccedil;&atilde;o</legend>
	<?
	include("forms/db_frmparecerlicitacao.php");
	?>
   </fieldset>
  </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
    if($recebeAlteracao){
		if($clparecerlicitacao->erro_status=="0"){
			$clparecerlicitacao->erro(true,false);
			$db_botao=true;
			echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
			if($clparecerlicitacao->erro_campo!=""){
				echo "<script> document.form1.".$clparecerlicitacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clparecerlicitacao->erro_campo.".focus();</script>";
			}
		}
        else{
            $clparecerlicitacao->erro(true,true);
		}
    }
}
if($db_opcao==22 && $recebeAlteracao){
  echo "<script>document.form1.pesquisar.click();</script>";
  echo "<script>document.form1.db_opcao.enabled=true;</script>";
}
?>
<script>
js_tabulacaoforms("form1","l200_licitacao",true,1,"l200_licitacao",true);
</script>
