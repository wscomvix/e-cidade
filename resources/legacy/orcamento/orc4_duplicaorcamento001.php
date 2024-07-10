<?
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



require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_orcorgao_classe.php"));
require_once(modification("classes/db_orcunidade_classe.php"));
require_once(modification("classes/db_orcprograma_classe.php"));
require_once(modification("classes/db_orcprojativ_classe.php"));
require_once(modification("classes/db_orcelemento_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));

$cliframe_seleciona = new cl_iframe_seleciona;

$clorcorgao    = new cl_orcorgao;
$clorcunidade  = new cl_orcunidade;
$clorcprograma = new cl_orcprograma;
$clorcprojativ = new cl_orcprojativ;
$clorcelemento = new cl_orcelemento;
$clorcdotacao  = new cl_orcdotacao;


db_postmemory($HTTP_POST_VARS);
$debug = false;

$anousu = db_getsession("DB_anousu");
$aFontesNovas = array('100' => 15000000,'101' => 15000001,'102' => 15000002,'103' => 18000000,'104' => 18010000,'105' => 18020000,
                        '106' => 15760010,'107' => 15440000,'108' => 17080000,'112' => 16590020,'113' => 15990030,'116' => 17500000,
                        '117' => 17510000,'118' => 15400007,'119' => 15400000,'120' => 15760000,'121' => 16220000,'122' => 15700000,
                        '123' => 16310000,'124' => 17000000,'129' => 16600000,'130' => 18990040,'131' => 17590050,'132' => 16040000,
                        '142' => 16650000,'143' => 15510000,'144' => 15520000,'145' => 15530000,'146' => 15690000,'147' => 15500000,
                        '153' => 16010000,'154' => 16590000,'155' => 16210000,'156' => 16610000,'157' => 17520000,'158' => 18990060,
                        '159' => 16000000,'160' => 17040000,'161' => 17070000,'162' => 17490120,'163' => 17130070,'164' => 17060000,
                        '165' => 18990000,'166' => 15420007,'167' => 15420000,'168' => 17100100,'169' => 17100000,'170' => 15010000,
                        '171' => 15710000,'172' => 15720000,'173' => 15750000,'174' => 15740000,'175' => 15730000,'176' => 16320000,
                        '177' => 16330000,'178' => 16360000,'179' => 16340000,'180' => 16350000,'181' => 17010000,'182' => 17020000,
                        '183' => 17030000,'184' => 17090000,'185' => 17530000,'186' => 17040000,'187' => 17050000,'188' => 15000080,
                        '189' => 15000090,'190' => 17540000,'191' => 17540000,'192' => 17550000,'193' => 18990130, '132' => 16040000,
                        '133' => 17150000, '134' => 17160000, '135' => 17170000, '136' => 17180000);


$dt_ini = $anousu.'-01-01';
$dt_fin = $anousu.'-12-31';

$anousu_ant = (db_getsession("DB_anousu")-1);
$dt_ini_ant = $anousu_ant.'-01-01';
$dt_fin_ant = $anousu_ant.'-12-31';

$sqlerro = false;
$doc = "";
$db_opcao = 22;
$db_botao = false;
$erro = false;

// orgaos
if (isset ($processa_orgao) && ($processa_orgao == 'Processar')) {
	// obtem uma matriz de chaves
	$chaves = explode('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
			if ($chaves[$i] == "")
				continue;
			// seleciona orgaos e insere no exercicio alvo
			$res = $clorcorgao->sql_record($clorcorgao->sql_query_file($anousu_ant,$chaves[$i]));
			if  (($clorcorgao->numrows) > 0) {
				db_fieldsmemory($res, 0);
				$clorcorgao->o40_anousu = $anousu;
				$clorcorgao->o40_orgao  = $o40_orgao;
                        	$clorcorgao->o40_codtri = $o40_codtri;
                        	$clorcorgao->o40_descr  = "$o40_descr";
                        	$clorcorgao->o40_instit = $o40_instit;
                        	$clorcorgao->o40_finali = $o40_finali;
				$clorcorgao->incluir($anousu,$o40_orgao);
				if ($clorcorgao->erro_status == '0') {
					db_msgbox($clorcorgao->erro_msg);
					$erro = true;
					break;
				}
			}
		} // END FOR
		db_fim_transacao($erro);
	} // END IF
}
// unidades
if (isset ($processa_unidade) && ($processa_unidade == 'Processar')) {
	$chaves = explode('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		$ct = count($chaves);
		for ($i = 0; $i < $ct; $i ++) {
		   if ($chaves[$i] == "") continue;
                   $pesquisa = explode('-',$chaves[$i]);
		   $res = $clorcunidade->sql_record($clorcunidade->sql_query_file($anousu_ant,$pesquisa[0],$pesquisa[1]));
		   if  (($clorcunidade->numrows) > 0) {
		  	db_fieldsmemory($res, 0);
			$clorcunidade->o41_anousu = $anousu;
			$clorcunidade->o41_orgao  = $o41_orgao;
                       	$clorcunidade->o41_unidade= $o41_unidade;
     	                $clorcunidade->o41_codtri = $o41_codtri;
                       	$clorcunidade->o41_descr  = "$o41_descr";
                       	$clorcunidade->o41_ident  = $o41_ident;
                       	$clorcunidade->o41_cnpj   = $o41_cnpj;
			$clorcunidade->incluir($anousu,$o41_orgao,$o41_unidade);
			if ($clorcunidade->erro_status == '0') {
				db_msgbox($clorcunidade->erro_msg);
				$erro = true;
				break;
			}
		   }
		} //
		db_fim_transacao($erro);
	} //
}//
// programa
if (isset ($processa_programa) && ($processa_programa == 'Processar')) {
	$chaves = explode('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
		   if ($chaves[$i] == "") continue;
		   $res = $clorcprograma->sql_record($clorcprograma->sql_query_file($anousu_ant,$chaves[$i]));
		   if  (($clorcprograma->numrows) > 0) {
		  	db_fieldsmemory($res, 0);
			$clorcprograma->o54_anousu   = $anousu;
			$clorcprograma->o54_programa = $o54_programa;
                       	$clorcprograma->o54_descr    = $o54_descr;
     	                $clorcprograma->o54_codtri   = $o54_codtri;
                       	$clorcprograma->o54_finali   = "$o54_finali";
                       	$clorcprograma->o54_instit   = $o54_instit;
			$clorcprograma->incluir($anousu,$o54_programa);
			if ($clorcprograma->erro_status == '0') {
				db_msgbox($clorcprograma->erro_msg);
				$erro = true;
				break;
			}
		   }
		} //
		db_fim_transacao($erro);
	} //
}//
// projativ
if (isset ($processa_projativ) && ($processa_projativ == 'Processar')) {
	$chaves = explode('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
		   if ($chaves[$i] == "") continue;
		   $res = $clorcprojativ->sql_record($clorcprojativ->sql_query_file($anousu_ant,$chaves[$i]));
		   if  (($clorcprojativ->numrows) > 0) {
		  	db_fieldsmemory($res, 0);
			$clorcprojativ->o55_anousu   = $anousu;
			$clorcprojativ->o55_tipo     = $o55_tipo;
                       	$clorcprojativ->o55_projativ = $o55_projativ;
     	                $clorcprojativ->o55_descr    = $o55_descr;
                       	$clorcprojativ->o55_finali   = "$o55_finali";
                       	$clorcprojativ->o55_instit   = $o55_instit;
			$clorcprojativ->incluir($anousu,$o55_projativ);
			if ($clorcprojativ->erro_status == '0') {
				db_msgbox($clorcprojativ->erro_msg);
				$erro = true;
				break;
			}
		   }
		} //
		db_fim_transacao($erro);
	} //
}//
// orcelemento
if (isset ($processa_elemento) && ($processa_elemento == 'Processar')) {
	$chaves = explode('#', $chaves);
	if (count($chaves) > 0) {
		db_inicio_transacao();
		for ($i = 0; $i < count($chaves); $i ++) {
		   if ($chaves[$i] == "") continue;
		   $res = $clorcelemento->sql_record($clorcelemento->sql_query_file($chaves[$i],$anousu_ant));
		   if  (($clorcelemento->numrows) > 0) {
		  	db_fieldsmemory($res, 0);
			$clorcelemento->o56_anousu   = $anousu;
			$clorcelemento->o56_codele   = $o56_codele;
                       	$clorcelemento->o56_elemento = $o56_elemento;
     	                $clorcelemento->o56_descr    = $o56_descr;
                       	$clorcelemento->o56_finali   = $o56_finali;
                       	$clorcelemento->o56_orcado   = $o56_orcado;
			$clorcelemento->incluir($o56_codele,$anousu);
			if ($clorcelemento->erro_status == '0') {
				db_msgbox($clorcelemento->erro_msg);
				$erro = true;
				break;
			}
		   }
		} //
		db_fim_transacao($erro);
	} //
}//
// dota��es, migra dota��es
if (isset ($processa_dotacao) && ($processa_dotacao == 'Processar')) {

    $instit = db_getsession("DB_instit");
    // echo "aqui";
    // vars
    // $dotacao='inicial'
    // $dataf_dia = '31';
    // $dataf_mes = '12';
    // $dataf_ano = '2006';
    // $percent = 'on';
    // $percentual = '3';
    $datafinal = $dataf_ano.'-'.$dataf_mes.'-'.$dataf_dia;
    // echo $datafinal;
    // seleciona todas as dota��es do exercicio atual caso exista
    $resAtual= $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"*",null," orcdotacao.o58_anousu = $anousu and o58_instit =  $instit "));
    $rowsAtual = $clorcdotacao->numrows;
    // seleciona todas as dota��es do exercicio anterior
    $res= $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"*",null," substr(o58_codigo::varchar,1,1) != '2' and orcdotacao.o58_anousu = $anousu_ant and o58_instit =  $instit "));

    $rows = $clorcdotacao->numrows;

    db_inicio_transacao();

    if($rowsAtual > 0 ){
        db_msgbox("Importa��o j� realizada para a instiui��o!");
        $erro = true;
    }else {
        for ($x = 0; $x < $rows; $x++) {
            db_fieldsmemory($res, $x);
            $dot = db_dotacaosaldo(8, 2, 3, true, "o58_instit = " . $instit . " and o58_coddot =$o58_coddot", $anousu_ant, $anousu_ant . '-01-01', $datafinal);

            if (pg_numrows($dot) > 0) {
                db_fieldsmemory($dot, 0);

                $valor = '0.00';
                if ($dotacao == 'inicial') {
                    $valor = $dot_ini;
                }
                if ($dotacao == 'atualizada') {
                    $valor = $dot_ini + $suplementado - $reduzido;
                }
                if (isset($percent) && $percent == 'on' && ($percentual > 0) && $dotacao != 'zerada') {
                    // quando n�o for dota��o zerada, e tiver percentual
                    if ($valor > 0) {
                        $adicional = round(($valor * $percentual) / 100, 0);
                        $valor = $valor + $adicional;
                    }
                }
                $valor = round($valor, 0);
                if($valor <= 1000){
                    $valor = '1000.00';
                }
                $whereExits  = " o58_anousu =".$anousu;
                $whereExits .= " and o58_orgao =".$o58_orgao;
                $whereExits .= " and o58_unidade =".$o58_unidade;
                $whereExits .= " and o58_funcao =".$o58_funcao;
                $whereExits .= " and o58_subfuncao =".$o58_subfuncao;
                $whereExits .= " and o58_programa =".$o58_programa;
                $whereExits .= " and o58_projativ =".$o58_projativ;
                $whereExits .= " and o58_codele =".$o58_codele;

                if($anousu == 2023){
                    $whereExits .= " and o58_codigo = ".$aFontesNovas[$o58_codigo];
                }

                if($anousu != 2023){
                    $whereExits .= " and o58_codigo = ".$o58_codigo;
                }

                $resDotacaoExits = $clorcdotacao->sql_record($clorcdotacao->sql_query(null, null, "*", null, $whereExits));
                $o58_coddotExits = db_utils::fieldsMemory($resDotacaoExits, 0)->o58_coddot;
                $o58_valorExits  = db_utils::fieldsMemory($resDotacaoExits, 0)->o58_valor;

                if($o58_coddotExits){
                    $clorcdotacao->o58_valor = $valor + $o58_valorExits;
                    $clorcdotacao->alterar($anousu, $o58_coddotExits);
                    if ($clorcdotacao->erro_status == '0') {
                        db_msgbox($clorcdotacao->erro_msg);
                        $erro = true;
                        break;
                    }
                    continue;
                }

                // insere nas tabelas
                $clorcdotacao->o58_anousu = $anousu;
                $clorcdotacao->o58_coddot = $o58_coddot;
                $clorcdotacao->o58_orgao = $o58_orgao;
                $clorcdotacao->o58_unidade = $o58_unidade;
                $clorcdotacao->o58_funcao = $o58_funcao;
                $clorcdotacao->o58_subfuncao = $o58_subfuncao;
                $clorcdotacao->o58_programa = $o58_programa;
                $clorcdotacao->o58_projativ = $o58_projativ;
                $clorcdotacao->o58_codele = $o58_codele;
                $clorcdotacao->o58_codigo = $o58_codigo;
                if($anousu == 2023){
                    $clorcdotacao->o58_codigo = $aFontesNovas[$o58_codigo];
                }
                $clorcdotacao->o58_valor = $valor;
                $clorcdotacao->o58_instit = $o58_instit;
                $clorcdotacao->o58_localizadorgastos = $o58_localizadorgastos;
                $clorcdotacao->o58_datacriacao = $o58_datacriacao;
                $clorcdotacao->o58_concarpeculiar = $o58_concarpeculiar;

                $clorcdotacao->incluir($anousu, $o58_coddot);
                if ($clorcdotacao->erro_status == '0') {
                    db_msgbox($clorcdotacao->erro_msg);
                    $erro = true;
                    break;
                }
            }  //

        }  // endfor

    }
    db_fim_transacao($erro);
    if ($clorcdotacao->erro_status != '0' && $erro != true) {
        db_msgbox("Importa��o conclu�da com sucesso!");
        $erro = false;
    }

}

if (isset ($processa_dotacao) && ($processa_dotacao == 'Excluir')) {
    $instit = db_getsession("DB_instit");
    $datafinal = $dataf_ano.'-'.$dataf_mes.'-'.$dataf_dia;
    // echo $datafinal;
    // seleciona todas as dota��es do exercicio atual caso exista
    $resAtual= $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"*",null," orcdotacao.o58_anousu = $anousu and o58_instit =  $instit "));
    $rowsAtual = $clorcdotacao->numrows;
    $erro = false;

    db_inicio_transacao();
    if($rowsAtual == 0 ){
        db_msgbox("Sem or�amento para excluir da instiui��o!");
        $erro = true;
    }else {

        $res= $clorcdotacao->excluir(null,null," o58_anousu = $anousu and o58_instit =  $instit ");
        if ($clorcdotacao->erro_status == '0') {
            db_msgbox($clorcdotacao->erro_msg);
            $erro = true;
        }
        if($res){
            db_msgbox("Exclus�o Realizada com Sucesso!!");
        }
    }
    db_fim_transacao($erro);

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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<form name="form1" action="" method="POST">

<table border=1 width=100% height=80% cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" valign=top>
<tr>
  <td width=50% valign=top>
  <table width=100% border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr>
   <td>
     <table border=0 align=center>
     <tr>
       <td colspan=3><h3>Duplica��o de Or�amento ( do exer�cio <?=(db_getsession("DB_anousu")-1)?> para <?=db_getsession("DB_anousu")?> ) </h3></td>
         <td colspan=3> </h3></td>
     </tr>
         <tr>

             <td colspan=3><h4>1 - Importa��o s� poder� ser realizada uma vez por institui��o. </h4></td>
         </tr>
         <!--     <tr>-->
<!--       <td colspan=3><b>Cadastros </b> </td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Org�o </td>-->
<!--       <td><input type=submit name='processa_orgao' value=Selecionar   ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Unidade </td>-->
<!--       <td><input type=submit name='processa_unidade' value=Selecionar   ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Fun��o (independe de exerc�cio) </td>-->
<!--       <td><input type=submit name='processa_funcao' value=Selecionar disabled  ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>SubFun��o (independe de exerc�cio) </td>-->
<!--       <td><input type=submit name='processa_subfuncao' value=Selecionar disabled  ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Programa </td>-->
<!--       <td><input type=submit name='processa_programa' value=Selecionar   ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Proj/Atividades </td>-->
<!--       <td><input type=submit name='processa_projativ' value=Selecionar   ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Elementos de despesa </td>-->
<!--       <td><input type=submit name='processa_elemento' value=Selecionar  ></td>-->
<!--     </tr>-->
<!--     <tr>-->
<!--       <td width=40px> &nbsp; </td>-->
<!--       <td>Recursos (independe de exerc�cio) </td>-->
<!--       <td><input type=submit name='processa_recurso' value=Selecionar  disabled ></td>-->
<!--     </tr>-->
<!---->
     <tr>
       <td colspan=3><b>Dota��es</b> </td>
     </tr>
     <tr>
       <td width=40px> &nbsp; </td>
       <td>Duplica��o de dota��es  </td>
       <td><input type=submit name=processa_dotacao value=Selecionar ></td>
     </tr>
    </table>
   </td>
  </tr>
  </table>
</td>
<td height=100% width=50% valign=top align=center >
<?
  $size_iframe=400;
  if (isset ($processa_orgao) && $processa_orgao == "Selecionar") {
	   $sql = "select o40_orgao,o40_descr
        	   from orcorgao
	           where o40_anousu=".(db_getsession("DB_anousu")-1)."
	           EXCEPT
	           select o40_orgao,o40_descr
	           from orcorgao
	           where o40_anousu=".db_getsession("DB_anousu")."
	           order by o40_orgao
          ";
	  $sql_marca = "";
	  $cliframe_seleciona->campos = "o40_orgao,o40_descr";
	  $cliframe_seleciona->legenda = "Org�os";
	  $cliframe_seleciona->sql = $sql;
	  $cliframe_seleciona->sql_marca = $sql_marca;
	  $cliframe_seleciona->iframe_height = $size_iframe;
	  $cliframe_seleciona->iframe_width = "100%";
	  $cliframe_seleciona->iframe_nome = "cta_orgao";
	  $cliframe_seleciona->chaves = "o40_orgao";
	  $cliframe_seleciona->iframe_seleciona(1);

    ?><table border=0 width=100%>
      <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('orgao');"></td></tr>
      </table>
    <?
  }
  if (isset ($processa_unidade) && $processa_unidade == "Selecionar") {
       	   $sql = "select o41_orgao,o41_unidade,o41_descr
                   from orcunidade
	           where o41_anousu=".(db_getsession("DB_anousu")-1)."
	           EXCEPT
	           select o41_orgao,o41_unidade,o41_descr
	           from orcunidade
	           where o41_anousu=".db_getsession("DB_anousu")."
	           order by o41_unidade
          ";
	   $sql_marca = "";
	   $cliframe_seleciona->campos = "o41_orgao,o41_unidade,o41_descr";
	   $cliframe_seleciona->legenda = "Unidades";
	   $cliframe_seleciona->sql = $sql;
	   $cliframe_seleciona->sql_marca = $sql_marca;
	   $cliframe_seleciona->iframe_height = $size_iframe;
	   $cliframe_seleciona->iframe_width = "100%";
	   $cliframe_seleciona->iframe_nome = "cta_unidade";
	   $cliframe_seleciona->chaves = "o41_orgao,o41_unidade";
	   $cliframe_seleciona->iframe_seleciona(1);

       ?><table border=0 width=100%>
          <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('unidade');"></td></tr>
          </table>
       <?
  }
  if (isset ($processa_programa) && $processa_programa == "Selecionar") {
       	   $sql = "select o54_programa,o54_descr
                   from orcprograma
	           where o54_anousu=".(db_getsession("DB_anousu")-1)."
	           EXCEPT
	           select o54_programa,o54_descr
	           from orcprograma
	           where o54_anousu=".db_getsession("DB_anousu")."
	           order by o54_programa
          ";
	   $sql_marca = "";
	   $cliframe_seleciona->campos = "o54_programa,o54_descr";
	   $cliframe_seleciona->legenda = "Programas";
	   $cliframe_seleciona->sql = $sql;
	   $cliframe_seleciona->sql_marca = $sql_marca;
	   $cliframe_seleciona->iframe_height = $size_iframe;
	   $cliframe_seleciona->iframe_width = "100%";
	   $cliframe_seleciona->iframe_nome = "cta_programa";
	   $cliframe_seleciona->chaves = "o54_programa";
	   $cliframe_seleciona->iframe_seleciona(1);

       ?><table border=0 width=100%>
          <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('programa');"></td></tr>
          </table>
       <?
  }
  if (isset ($processa_projativ) && $processa_projativ == "Selecionar") {
       	   $sql = "select o55_projativ,o55_descr
                   from orcprojativ
	           where o55_anousu=".(db_getsession("DB_anousu")-1)."
	           EXCEPT
	           select o55_projativ,o55_descr
	           from orcprojativ
	           where o55_anousu=".db_getsession("DB_anousu")."
	           order by o55_projativ
          ";
	   $sql_marca = "";
	   $cliframe_seleciona->campos = "o55_projativ,o55_descr";
	   $cliframe_seleciona->legenda = "Proj/Atividades";
	   $cliframe_seleciona->sql = $sql;
	   $cliframe_seleciona->sql_marca = $sql_marca;
	   $cliframe_seleciona->iframe_height = $size_iframe;
	   $cliframe_seleciona->iframe_width = "100%";
	   $cliframe_seleciona->iframe_nome = "cta_projativ";
	   $cliframe_seleciona->chaves = "o55_projativ";
	   $cliframe_seleciona->iframe_seleciona(1);

       ?><table border=0 width=100%>
          <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('projativ');"></td></tr>
          </table>
       <?
  }
  if (isset ($processa_elemento) && $processa_elemento == "Selecionar") {
       	   $sql = "select o56_codele,o56_elemento,o56_descr
                   from orcelemento
	           where o56_anousu=".(db_getsession("DB_anousu")-1)."
	           EXCEPT
	           select o56_codele,o56_elemento,o56_descr
	           from orcelemento
	           where o56_anousu=".db_getsession("DB_anousu")."
	           order by o56_elemento
          ";
	   $sql_marca = "";
	   $cliframe_seleciona->campos = "o56_codele,o56_elemento,o56_descr";
	   $cliframe_seleciona->legenda = "Elemento";
	   $cliframe_seleciona->sql = $sql;
	   $cliframe_seleciona->sql_marca = $sql_marca;
	   $cliframe_seleciona->iframe_height = $size_iframe;
	   $cliframe_seleciona->iframe_width = "100%";
	   $cliframe_seleciona->iframe_nome = "cta_elemento";
	   $cliframe_seleciona->chaves = "o56_codele";
	   $cliframe_seleciona->iframe_seleciona(1);

       ?><table border=0 width=100%>
          <tr><td width=100% align=center><input type=button value=Processar onClick="js_processa('elemento');"></td></tr>
          </table>
       <?
  }
  if (isset ($processa_dotacao) && $processa_dotacao == "Selecionar") {
       ?><table border=0 width=100%>
         <tr><td height=50px> &nbsp; </td></tr>

           <tr>
           <td><h3>Previs�o inicial das dota��es para <?=$anousu ?></h3></td>
	   </tr>
	   <tr><td><input type=radio name=dotacao value=zerada  ><b>Zerado </b>   </td></tr>
	   <tr><td><input type=radio name=dotacao value=inicial ><b>Dota��o inicial   </td></tr>
           <tr><td><input type=radio name=dotacao value=atualizada><b>Dota��o Atualizada ate  <? $dataf_dia = '31';$dataf_mes = '12';	$dataf_ano = $anousu_ant; db_inputdata("dataf",@$dataf_dia,@$dataf_mes,@$dataf_ano,true,'text',1) ?> </td></tr>
           <tr><td><input type=checkbox name=percent><b> Percentual adicional de <input type=text name=percentual value='0' size=4 maxlength=4>% </b></td></tr>
	   <tr><td height=50px> &nbsp; </td></tr>
	 </table>
       <?
       ?><table border=0 width=100%>
          <tr><td width=50% align=right><input type=submit name=processa_dotacao value=Processar ></td>
              <td width=50% align=left><input type=button value=Excluir onClick="js_processaExcluir('Excluir');"></td>
          </tr>
         </table>
       <?
  }



?>
</td>
</tr>
</table>


</form>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>

function js_processa(tipo){
  js_gera_chaves();
  // cria um objeto que indica o tipo de processamento
  obj=document.createElement('input');
  obj.setAttribute('name','processa_'+tipo);
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','Processar');
  document.form1.appendChild(obj);
  document.form1.submit();
}


function js_processaExcluir(tipo){
    if (confirm("Tem certeza que deseja excluir toda meta da despesa?") ) {
        // cria um objeto que indica o tipo de processamento
        obj = document.createElement('input');
        obj.setAttribute('name', 'processa_dotacao');
        obj.setAttribute('type', 'hidden');
        obj.setAttribute('value', tipo);
        document.form1.appendChild(obj);
        document.form1.submit();
    }
}
</script>

</body>
</html>
