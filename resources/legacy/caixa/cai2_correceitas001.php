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

use repositories\caixa\relatorios\ReceitaFormaArrecadacaoRepositoryLegacy;
use repositories\caixa\relatorios\ReceitaOrdemRepositoryLegacy;
use repositories\caixa\relatorios\ReceitaTipoReceitaRepositoryLegacy;
use repositories\caixa\relatorios\ReceitaTipoRepositoryLegacy;

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("classes/db_orctiporec_classe.php");

require_once 'repositories/caixa/relatorios/ReceitaFormaArrecadacaoRepositoryLegacy.php';
require_once 'repositories/caixa/relatorios/ReceitaOrdemRepositoryLegacy.php';
require_once 'repositories/caixa/relatorios/ReceitaTipoReceitaRepositoryLegacy.php';
require_once 'repositories/caixa/relatorios/ReceitaTipoRepositoryLegacy.php';

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');
$clrotulo->label('o08_reduz');

$clorctiporec = new cl_orctiporec;
$clorctiporec->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Verifique !.');
    return false;
  }
  return true;
}


function js_emite(){

	vir = "";
  	cods= "";
  	var_obj = document.getElementById('receita').length;
  	for(y=0;y<var_obj;y++){

		var_if = document.getElementById('receita').options[y].value;
    	cods += vir + var_if;
		vir = ",";

  	}

	vir = "";
  	sReduzidos = "";
  	oReduzidos = document.getElementById('contas').length;

	for(z=0;z<oReduzidos;z++){

      	sReduzido = document.getElementById('contas').options[z].value;
      	sReduzidos += vir + sReduzido;
      	vir = ",";

	}

	vir = "";
  	sCgms = "";
  	oCgms = document.getElementById('cgm').length;

	for(w=0;w<oCgms;w++){

      	sCgm = document.getElementById('cgm').options[w].value;
      	sCgms += vir + sCgm;
      	vir = ",";

	}

  	if (document.form1.o15_codigo.value == 0){
       	recurso = "";
  	} else {
       	recurso = document.form1.o15_codigo.value;
	}

	qry  = "estrut="+document.form1.estrut.value;
	qry += "&sinana="+document.form1.sinana.value;
	qry += "&ordem="+document.form1.ordem.value;
	// qry += "&desdobrar="+document.form1.desdobrar.value;
	qry += "&codrec="+cods;
	qry += "&datai="+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
	qry += "&dataf="+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
	qry += "&tipo="+document.form1.tipo.value;
	qry += "&recurso="+recurso;
	qry += "&conta="+sReduzidos;
	qry += "&contribuinte="+sCgms;
	qry += "&emparlamentar="+document.form1.emparlamentar.value;
	qry += "&regrepasse="+document.form1.regrepasse.value;
	qry += "&formarrecadacao="+document.form1.formarrecadacao.value;
	qry += "&formatoRelatorio="+document.form1.formatoRelatorio.value;

	jan = window.open('cai2_correceitas002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	jan.moveTo(0,0);

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
	#fieldset_contas, #fieldset_cgm {
        width: 400px;
        text-align: center;
	}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<center>
	<div style="margin-top: 25px; width: 650px">
		<form name="form1" method="post" action="" onsubmit="return js_verifica();">
			<fieldset><legend>RECEITAS</legend>
				<table  align="center">
					<tr>
						<td colspan="4" align="center">
							<table>
								<tr>
									<td align="center">
										<? $aux_receita = new cl_arquivo_auxiliar;
										$aux_receita->cabecalho = "<strong>Receitas</strong>";
										$aux_receita->codigo = "k02_codigo";
										$aux_receita->descr  = "k02_drecei";
										$aux_receita->nomeobjeto = 'receita';
										$aux_receita->funcao_js = 'js_mostra';
										$aux_receita->funcao_js_hide = 'js_mostra1';
										$aux_receita->sql_exec  = "";
										$aux_receita->func_arquivo = "func_tabrec_todas.php";
										$aux_receita->nomeiframe = "db_iframe";
										$aux_receita->localjan = "";
										$aux_receita->db_opcao = 2;
										$aux_receita->tipo = 2;
										$aux_receita->top = 0;
										$aux_receita->linhas = 6;
										$aux_receita->tamanho_campo_descricao = 30;
										$aux_receita->nome_botao = 'db_lanca_receita';
										$aux_receita->vwidth = 404;
										$aux_receita->funcao_gera_formulario(); ?>
									</td>
								</tr>
								<tr>
									<td>
										<? $aux_conta = new cl_arquivo_auxiliar;
										$aux_conta->cabecalho = "<strong>Contas</strong>";
										$aux_conta->codigo = "k13_conta";
										$aux_conta->descr  = "k13_descr";
										$aux_conta->nomeobjeto = 'contas';
										$aux_conta->funcao_js = 'js_mostra_contas';
										$aux_conta->funcao_js_hide = 'js_mostra_contas1';
										$aux_conta->sql_exec  = "";
										$aux_conta->func_arquivo = "func_saltes.php";
										$aux_conta->nomeiframe = "db_iframe_saltes";
										$aux_conta->localjan = "";
										$aux_conta->onclick = "";
										$aux_conta->db_opcao = 2;
										$aux_conta->tipo = 2;
										$aux_conta->top = 0;
										$aux_conta->linhas = 5;
										$aux_conta->vwidth = 400;
										$aux_conta->tamanho_campo_descricao = 24;
										$aux_conta->nome_botao = 'db_lanca_conta';
										$aux_conta->funcao_gera_formulario(); ?>
									</td>
								</tr>
								<tr>
									<td>
										<? $aux_cgm = new cl_arquivo_auxiliar;
										$aux_cgm->cabecalho = "<strong>Contribuinte</strong>";
										$aux_cgm->codigo = "z01_numcgm";
										$aux_cgm->descr = "z01_nome";
										$aux_cgm->isfuncnome = true;
										$aux_cgm->nomeobjeto = 'cgm';
										$aux_cgm->funcao_js = 'js_mostraCgm';
										$aux_cgm->funcao_js_hide = 'js_mostraCgm1';
										$aux_cgm->sql_exec = "";
										$aux_cgm->func_arquivo = "func_nome.php";
										$aux_cgm->nomeiframe = "func_nome";
										$aux_cgm->localjan = "";
										$aux_cgm->db_opcao = 2;
										$aux_cgm->tipo = 2;
										$aux_cgm->top = 0;
										$aux_cgm->linhas = 5;
										$aux_cgm->vwidth = 400;
										$aux_cgm->tamanho_campo_descricao = 28;
										$aux_cgm->nome_botao = 'db_lanca_cgm';
										$aux_cgm->funcao_gera_formulario(); ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td align="right" colspan=""><strong>Data Inicial :</strong></td>
						<td>
							<?= db_inputdata('datai','01','01',db_getsession("DB_anousu"),true,'text',4) ?>
						<strong>Data Final :</strong>
						<?
							$datausu = date("Y/m/d",db_getsession("DB_datausu"));
							$dataf_ano = substr($datausu,0,4);
							$dataf_mes = substr($datausu,5,2);
							$dataf_dia = substr($datausu,8,2);
							?>
							<?= db_inputdata('dataf',$dataf_dia,$dataf_mes,$dataf_ano,true,'text',4) ?>
						</td>
					</tr>
					 <tr>
						<td align="right">
							<strong>Estrutural da Receita:</strong>
						</td>
						<td align="left">
							<? db_input('estrut',18,0,true,'text',2,""); ?>
						</td>
					</tr>
					<tr>
						<td align="right">
							<strong>Tipo de Receita:</strong>
						</td>
						<td>
							<select name="tipo" onchange="js_valor();" style="width: 140px;">
								<option value=<?= ReceitaTipoReceitaRepositoryLegacy::TODOS ?>>Todas</option>
								<option value=<?= ReceitaTipoReceitaRepositoryLegacy::ORCAMENTARIA ?>>Or�amentarias</option>
								<option value=<?= ReceitaTipoReceitaRepositoryLegacy::EXTRA ?>>Extra-Or�amentarias</option>
						</td>
					</tr>
                    <!--
					<tr>
						<td align="right">
							<strong>Desdobrar Receita:</strong>
						</td>
						<td>
							<select name="desdobrar" onchange="js_valor();">
								<option value = 'N'>N�o</option>
								<option value = 'S'>Sim</option>
						</td>
					</tr>
					<tr>
                    -->
						<td align="right">
							<strong>Ordem:</strong>
						</td>
						<td>
							<select name="ordem" id="ordem" onchange="js_validarTipo(this)" style="width: 175px;">
								<option value=<?= ReceitaOrdemRepositoryLegacy::CODIGO ?>>C�digo Receita</option>
								<option value=<?= ReceitaOrdemRepositoryLegacy::ESTRUTURAL ?>>Estrutural</option>
								<option value=<?= ReceitaOrdemRepositoryLegacy::ALFABETICA ?>>Alfab�tica Descri��o Receita</option>
								<option value=<?= ReceitaOrdemRepositoryLegacy::REDUZIDO_ORCAMENTO ?>>Reduzido Or�amento</option>
								<option value=<?= ReceitaOrdemRepositoryLegacy::REDUZIDO_CONTA ?>>Reduzido Conta</option>
								<option value=<?= ReceitaOrdemRepositoryLegacy::CONTRIBUINTE ?>>Contribuinte</option>
								<option value=<?= ReceitaOrdemRepositoryLegacy::OPERACAO_CREDITO ?>>Opera��o de Cr�dito</option>
                            </select>
						</td>
					</tr>
					<tr>
						<td align="right">
							<strong>Tipo:</strong>
						</td>
						<td>
							<select name="sinana" id="sinana" style="width: 175px;">
								<option value=<?= ReceitaTipoRepositoryLegacy::RECEITA ?>>Sint�tico/Receita</option>
								<option value=<?= ReceitaTipoRepositoryLegacy::ESTRUTURAL ?>>Sint�tico/Estrutural</option>
								<option value=<?= ReceitaTipoRepositoryLegacy::ANALITICO ?>>Anal�tico</option>
								<option value=<?= ReceitaTipoRepositoryLegacy::CONTA ?>>Sint�tico/Conta</option>
								<option value=<?= ReceitaTipoRepositoryLegacy::DIARIO ?>>Di�rio</option>
								<option value=<?= ReceitaTipoRepositoryLegacy::ANALITICO_RECEITA ?>>Anal�tico/Receita</option>
						</td>
					</tr>
					<tr>
						<td align="right">
							<strong>Formato:</strong>
						</td>
						<td>
							<?
							db_select('formatoRelatorio',array("0" => "PDF", "1" => "CSV"),true,1);
							?>
						</td>
					</tr>
					<tr>
						<td align="right" nowrap>
							<strong>Referente a Emenda Parlamentar:</strong>
						</td>
						<td>
							<select name="emparlamentar" style="width: 175px;">
								<option value = '0'>Todas</option>
								<option value = '1'>1 - Emenda parlamentar individual</option>
								<option value = '2'>2 - Emenda parlamentar de bancada</option>
								<option value = '3'>3 - N�o se aplica</option>
								<option value = '4'>4 - Emenda n�o impositiva</option>
						</td>
					</tr>
					<tr>
						<td align="right" nowrap>
							<strong>Regulariza��o de Repasse:</strong>
						</td>
						<td>
							<select name="regrepasse" style="width: 175px;">
								<option value = '0'>Todas</option>
								<option value = '1'>Sim</option>
								<option value = '2'>N�o</option>
						</td>
					</tr>
					<tr>
						<td align="right" nowrap>
							<strong>Forma de Arrecada��o:</strong> 
						</td>
						<td>
							<select name="formarrecadacao" style="width: 175px;">
								<option value=<?= ReceitaFormaArrecadacaoRepositoryLegacy::TODAS ?>>Todas</option>
								<option value=<?= ReceitaFormaArrecadacaoRepositoryLegacy::ARQUIVO_BANCARIO ?>>Via arquivo banc�rio</option>
								<option value=<?= ReceitaFormaArrecadacaoRepositoryLegacy::EXCETO_ARQUIVO_BANCARIO ?>>Exceto via arquivo banc�rio e reten��es</option>
								<option value=<?= ReceitaFormaArrecadacaoRepositoryLegacy::RETENCAO ?>>Via Reten��es</option>
						</td>
					</tr>
					<tr>
						<td nowrap title="<?=$To15_codigo?>" align="right"><?=$Lo15_codigo?></td>
						<td nowrap>
						<?  $dbwhere     = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
							$res_tiporec = $clorctiporec->sql_record($clorctiporec->sql_query_file(null,"o15_codigo,o15_descr","o15_codigo",$dbwhere));
							db_selectrecord("o15_codigo",$res_tiporec,true,2,"","","","0");
						?>
						</td>
					</tr>
				</table>
			</fieldset>
			<br>
			<input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
		</form>
	</div>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
document.getElementById('formatoRelatorio').style.width='175px';

oDBToogleCredores = new DBToogle('fieldset_contas', false);
oDBToogleCredores = new DBToogle('fieldset_cgm', false);

function js_pesquisatabrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta','db_iframe_conclass','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|0|3','Pesquisa',true,'0');
  }else{
     if(document.form1.c60_codcla.value != ''){
     	js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta','db_iframe_conclass','func_tabrec_todas.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
        document.form1.k02_drecei.value = '';
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave;
  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     db_iframe.hide();
}

function js_validarTipo(select)
{
    if (select.options[select.selectedIndex].value == <?= ReceitaOrdemRepositoryLegacy::CONTRIBUINTE ?>
		|| select.options[select.selectedIndex].value == <?= ReceitaOrdemRepositoryLegacy::OPERACAO_CREDITO ?>) {
        if (document.getElementById('sinana').value == 'S4')
            document.getElementById('sinana').options[3].setAttribute('selected', '');
		
		if (document.getElementById('sinana').value == 'AR'){
            document.getElementById('sinana').options[3].setAttribute('selected', '');
		}

        document.getElementById('sinana').options[4].setAttribute('disabled', '');
		document.getElementById('sinana').options[5].setAttribute('disabled', '');
        return;
    }
    document.getElementById('sinana').options[4].removeAttribute('disabled', '');
	document.getElementById('sinana').options[5].removeAttribute('disabled', '');
	document.getElementById('sinana').options[3].removeAttribute('selected', '');
    return
}
</script>


<?
if(isset($ordem)){
  echo "<script>
                   js_emite();
       </script>";
}
?>
