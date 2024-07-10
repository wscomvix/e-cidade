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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfautent_classe.php");
include("classes/db_saltes_classe.php");
include("classes/db_caiparametro_classe.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");
$rotulocampo->label("k29_contassemmovimento");

$k00_dtoper     = date('Y-m-d',db_getsession("DB_datausu"));
$k00_dtoper_dia = date('d',db_getsession("DB_datausu"));
$k00_dtoper_mes = date('m',db_getsession("DB_datausu"));
$k00_dtoper_ano = date('Y',db_getsession("DB_datausu"));
$k29_instit     = db_getsession("DB_instit");

$clcaiparametro  = new cl_caiparametro;
$rsCaiParametro  = $clcaiparametro->sql_record($clcaiparametro->sql_query($k29_instit,"k29_contassemmovimento",null,""));
$iCaiParametro   = @pg_num_rows($rsCaiParametro);

if ( $iCaiParametro > 0 ) {
  db_fieldsmemory($rsCaiParametro,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>


function js_relatorio() {

  var obj                  = document.form1;
  var datai                = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf                = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  var contasnegativas      = obj.contasnegativas.value;
  var imprimeinterferencia = obj.imprime_interferencia.value;
  var agrupar              = obj.agrupar.value;
  var ordemconta           = obj.ordem_conta.value;
  var caixabanco           = obj.caixabanco.value;
  var negrito              = obj.k29_destacarnaoconciliadas.value;
  //var caixa                = obj.k11_id.value;
  //var conta                = obj.k13_conta.value;
  var quebrarpag           = obj.quebrarpag.value;
  var contassemmov         = obj.k29_contassemmovimento.value;
  var agruparfonte         = obj.agrupar_fonte.value;
  var fonte 			   = obj.o15_codigo.value;
  var tipo_conta         = obj.tipo_conta.value;
  var agrupar_tipo_conta 			   = obj.agrupar_tipo_conta.value;

  jan = window.open('cai2_emissbol002.php?contasnegativas='+contasnegativas
                                                           +'&imprime_interferencia='+imprimeinterferencia
                                                           +'&ordem_conta='+ordemconta
                                                           +'&datai='+datai
                                                           +'&dataf='+dataf
                                                           +'&agrupar='+agrupar
                                                           +'&caixabanco='+caixabanco
                                                           +'&negrito='+negrito
                                                           //+'&conta='+conta
                                                           +'&quebrarpag='+quebrarpag
                                                           +'&contassemmov='+contassemmov
                                                           +'&agrupar_fonte='+agruparfonte
                                                           +'&tipo_conta='+tipo_conta
                                                           +'&agrupar_tipo_conta='+agrupar_tipo_conta
														   +'&fonte='+fonte,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_relatorio1(tipo) {

  var tipo         = tipo;
  var obj          = document.form1;
  var datai        = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf        = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  var caixa        = obj.k11_id.value;
  var conta        = obj.k13_conta.value;
  var agrupar      = obj.agrupar.value;
  var quebrarpag   = obj.quebrarpag.value;
  var contassemmov = obj.k29_contassemmovimento.value;
  var caixabanco   = obj.caixabanco.value;

  jan = window.open('cai2_emissbol003.php?datai='+datai
                                                 +'&dataf='+dataf
                                                 +'&caixa='+caixa
                                                 +'&conta='+conta
                                                 +'&agrupar='+agrupar
                                                 +'&quebrarpag='+quebrarpag
                                                 +'&contassemmov='+contassemmov
                                                 +'&caixabanco='+caixabanco
                                                 +'&tiporel='+tipo,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_relatorio2() {
  var obj          = document.form1;
  var datai        = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf        = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  var caixa        = obj.k11_id.value;
  var conta        = obj.k13_conta.value;
  var quebrarpag   = obj.quebrarpag.value;
  var agrupar      = obj.agrupar.value;
  var contassemmov = obj.k29_contassemmovimento.value;
  var caixabanco   = obj.caixabanco.value;

  jan = window.open('cai2_emissbol004.php?datai='+datai
                                                 +'&dataf='+dataf
                                                 +'&caixa='+caixa
                                                 +'&conta='+conta
                                                 +'&agrupar='+agrupar
                                                 +'&quebrarpag='+quebrarpag
                                                 +'&caixabanco='+caixabanco
                                                 +'&contassemmov='+contassemmov,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_bloquearordem(){
    if (document.form1.agrupar.value == 'S') {
        document.form1.ordem_conta.disabled = true;
        // Incluindo condi��o de agrupamento
        if (document.form1.k29_destacarnaoconciliadas.getElementsByTagName("option")[1].selected) {
            // Desmarco o agrupamento
            document.form1.k29_destacarnaoconciliadas.getElementsByTagName("option")[1].selected = false;
            document.form1.k29_destacarnaoconciliadas.getElementsByTagName("option")[0].selected = true;
        }
    } else {
        document.form1.ordem_conta.disabled = false;
    }
}

function js_pesquisa_recurso(mostra){

	if (mostra==true){
	  	js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostrarecurso1|o15_codigo|o15_descr','Pesquisa',true);
	} else {

		if (document.form1.o15_codigo.value != ''){
		  	js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostrarecurso','Pesquisa',false);
	   	} else{
		 	document.form1.o15_descr.value = '';
	   	}
	}

}

function js_mostrarecurso(chave,erro){

	document.form1.o15_descr.value = chave;

	if( erro == true ){

		document.form1.o15_codigo.value = '';
	  	document.form1.o15_codigo.focus();

	}

}

function js_mostrarecurso1(chave1,chave2){

	document.form1.o15_codigo.value = chave1;
	document.form1.o15_descr.value = chave2;
	db_iframe_orctiporec.hide();

}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
    <br><br>

        <form name="form1" method="post" action="">
          <fieldset style="width: 430px;">
           <legend><b>Boletim por Per�odo</b></legend>
          <table border="0" cellspacing="0" cellpadding="0">
	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>
            <tr>
              <td align="right" nowrap><strong>Data inicial:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp;
                <?=db_inputdata("datai",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,'text',2)?>
              </td>
            </tr>


            <tr>
              <td align="right" nowrap><strong>Data final:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp;
                <?=db_inputdata("dataf",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,'text',2)?>
              </td>
            </tr>



<!--            <tr>
              <td height="25" nowrap><strong>Data Final:</strong></td>
              <td height="25" nowrap>&nbsp;&nbsp;
              </td>
            </tr>
            <tr>
              <td align="right" nowrap title="<?=$Tk11_id?>"><?=$Lk11_id?></td>
              <td align="left"  nowrap>&nbsp; &nbsp;
                <?
			//	$clcfautent = new cl_cfautent;
			//	$result = $clcfautent->sql_record($clcfautent->sql_query("","k11_id#k11_local","k11_local"));
			//	db_selectrecord("k11_id",$result,true,2,"","","","0");
				?>
              </td>
            </tr>

            <tr>
              <td align="right" nowrap title="<?=$Tk13_conta?>"><?=$Lk13_conta?></td>
              <td align="left" nowrap>&nbsp; &nbsp;
                <?
				//$clsaltes = new cl_saltes;
				//$result = $clsaltes->sql_record($clsaltes->sql_query("","saltes.k13_conta#k13_descr","k13_descr"));
				//db_selectrecord("k13_conta",$result,true,2,"","","","0");
				?>
              </td>
            </tr>-->
<tr></tr>
		  <tr>
		    <td align="right" nowrap title="<?=@$Tk29_contassemmovimento?>">
		       <strong>Traz Contas sem Movimento:</strong>
		    </td>
		    <td align="left" nowrap>&nbsp; &nbsp;
		      <?
		         $x = array('f'=>'N�o','t'=>'Sim');
		         db_select('k29_contassemmovimento',$x,true,2,"");
		      ?>
		    </td>
		  </tr>


          <tr>
		    <td align="right" nowrap title="<?=@$Tk29_destacarnaoconciliadas?>">
		       <strong>Destacar n�o Conciliadas:</strong>
		    </td>
		    <td align="left" nowrap>&nbsp; &nbsp;
		      <?
		         $x = array('f'=>'N�o','t'=>'Sim');
		         db_select('k29_destacarnaoconciliadas',$x,true,2,"onchange='js_verifica_campos();'");
		      ?>
		    </td>
		  </tr>

     <tr>
        <td align="right"><strong>Contas negativas em cinza:</strong>
	 </td>
        <td>
	  &nbsp; &nbsp;
          <select name="contasnegativas">
            <option value = 'S'>Sim</option>
            <option value = 'N'>N�o</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Agrupar Contas:</strong>
   </td>
        <td>
    &nbsp; &nbsp;
          <select name="agrupar" onchange="js_bloquearordem()" >
            <option value = 'N'>N�o</option>
            <option value = 'S'>Sim</option>
        </td>
      </tr>
     <tr>
        <td align="right"><strong>Imprimir Interfer�ncias:</strong>
	 </td>
        <td>
	  &nbsp; &nbsp;
          <select name="imprime_interferencia">
            <option value = 'N'>N�o</option>
            <option value = 'S'>Sim</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Quebrar p�ginas:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="quebrarpag">
            <option value = 'S'>Sim</option>
            <option value = 'N'>N�o</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>S� Caixa/Banco:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="caixabanco">
            <option value = 'S'>Sim</option>
            <option value = 'N'>N�o</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Ordem:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="ordem_conta">
            <option value = '1'>C�d Bco/Reduz</option>
            <option value = '2'>Nome Bco/Reduz</option>
            <option value = '3'>Estrutural</option>
            <option value = '4'>Descri��o</option>
            <option value = '5'>Reduzido</option>

        </td>
      </tr>
     <tr>
        <td align="right"><strong>Agrupar por Fonte:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="agrupar_fonte" onclick="verconta()">
            <option value = 'N'>N�o</option>
            <option value = 'S'>Sim</option>
          </select>
        </td>
      </tr>
	<tr>
		<td align="right"><?db_ancora("<b>Fonte:</b>","js_pesquisa_recurso(true);",1);?></td>
		<td nowrap>&nbsp; &nbsp;
			<?
				db_input("o15_codigo",6,1,true,"text",4,"onchange='js_pesquisa_recurso(false);'");
				db_input("o15_descr",30,"",true,"text",3);
			?>
		</td>
	</tr>
  <tr>
        <td align="right"><strong>Tipo de Conta:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="tipo_conta">
            <option value = '0' selected>Todas</option>
            <option value = '1'>Conta corrente</option>
            <option value = '2'>Conta poupan�a</option>
            <option value = '3'>Conta aplica��o</option>

        </td>
      </tr>
      <tr>
        <td align="right"><strong>Agrupar por tipo de Conta:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="agrupar_tipo_conta">
            <option value = '1'>Sim</option>
            <option value = '2' selected>N�o</option>
          </td>
      </tr>

	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>

            <tr>
              <td colspan = "2" align="center" >
	          <input name="boletim" type="button" id="boletim" onClick="js_relatorio()" value="Boletim" />
	          <!--<input name="autentica" type="button" id="autentica" onClick="js_relatorio1('c')" value="Autentica��o Completo">
	          <input name="autenticaresum" type="button" id="autenticaresum" onClick="js_relatorio1('r')" value="Autentica��o Resumido">
	          <input name="autent_conta" type="button" id="autent_conta" onClick="js_relatorio2()" value="Autentica��o por Conta">-->
	      </td>
            </tr>
          </table>
          </fieldset>
        </form>

      </center>
	</td>
  </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_verifica_campos() {
    if (document.form1.k29_destacarnaoconciliadas.value == 't') {
        if (document.form1.agrupar.getElementsByTagName("option")[1].selected) {
            // Desmarco o agrupamento
            document.form1.agrupar.getElementsByTagName("option")[1].selected = false;
            document.form1.agrupar.getElementsByTagName("option")[0].selected = true;
        }
    }
}

function verconta(){

  if(document.form1.agrupar_fonte.value == 'S'){
    document.form1.agrupar_tipo_conta.disabled=true
    }  else
    document.form1.agrupar_tipo_conta.disabled=false
}
</script>
