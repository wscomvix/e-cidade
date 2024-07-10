<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include ("libs/db_liborcamento.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_pctipocompra_classe.php");


$cliframe_seleciona = new cl_iframe_seleciona;
$aux = new cl_arquivo_auxiliar;
$clpctipocompra = new cl_pctipocompra;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
 

if (!isset($desdobramento)){
    $desdobramento = "true";
 }

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
//habilitar e desabilitar campo mostrar lan�amento
function disable_select(){
  document.getElementById("mostrarlancamentos").disabled=true
}
function enable_select(){
  document.getElementById("mostrarlancamentos").disabled=false
}
function js_imprime(){
 vir="";
 listacredor="";
 for(x=0;x<document.form1.credor.length;x++){
  listacredor+=vir+document.form1.credor.options[x].value;
  vir=",";
 }

// document.form1.listacredor.value = listacredor;
  sel_instit  = document.form1.db_selinstit.value;    
  if(sel_instit == 0){
    alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
    return false;
  } 
  var obj=document.form1;
  var query='1=1';
  var data1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
  var data2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }
  // pega dados da func_selorcdotacao_aba.php
 //document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
 
 //  query = parent.iframe_filtro.js_pesquisa();
 filtra_despesa =  parent.iframe_filtro.js_atualiza_variavel_retorno();
 imprime_filtro = 'nao';
 if (obj.imprime_filtro.checked == true ){
   imprime_filtro = 'sim';
 }

 var sPeriodoInicial = document.form1.data1.value.split("/");
 var sPeriodoFinal   = document.form1.data2.value.split("/");

 var sURL  = 'emp2_emp_liq_pag002.php?db_selinstit='+sel_instit;
     sURL += '&pag='+document.form1.pag.checked;
     sURL += '&estpag='+document.form1.estpag.checked;
     sURL += '&liq='+document.form1.liq.checked;
     sURL += '&estliq='+document.form1.estliq.checked;
	   sURL += '&emp='+document.form1.emp.checked;
	   sURL += '&estemp='+document.form1.estemp.checked;
	   sURL += '&ordem='+document.form1.ordem.value;
	   sURL += '&com_mov='+document.form1.com_mov.value;
	   sURL += '&rp='+document.form1.rp.value;
     sURL += '&tipopessoa='+document.form1.tipopessoa.value;
     sURL += '&agrupar='+document.form1.agrupar.value;
     sURL += '&autonomofiltro='+document.form1.autonomofiltro.value;
     sURL += '&mostrarlancamentos='+document.form1.mostrarlancamentos.value;
	   sURL += '&tipocompra='+document.form1.tipocompra.value;							   
	   sURL += '&mostraritem='+document.form1.mostraritem.value;
	   sURL += '&com_ou_sem='+document.form1.com_ou_sem.value;
	   sURL += '&listacredor='+listacredor;
	   sURL += '&dataini='+sPeriodoInicial[2]+'-'+sPeriodoInicial[1]+'-'+sPeriodoInicial[0];
  	 sURL += '&datafin='+sPeriodoFinal[2]+'-'+sPeriodoFinal[1]+'-'+sPeriodoFinal[0];
  	 sURL += '&filtra_despesa='+filtra_despesa;
  	 sURL += '&imprime_filtro='+imprime_filtro;
  	 sURL += '&sDadosFornecedor='+$F('dados_fornecedor');
 
 jan = window.open(sURL,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post">
<div style="width: 500px">
<fieldset>
<table border=0>
  <tr>
    <td>
    	<?
			db_selinstit('parent.js_limpa', 300, 100);
	    ?>     
   </td>
 </tr>
  <tr>
       <table border="0" >
       <tr> 
           <td align="center">
                <strong>Op��es:</strong>
                <select name="com_ou_sem">
                    <option name="condicao1" value="com">Com os credores selecionados</option>
                    <option name="condicao1" value="sem">Sem os credores selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Credores</strong>";
                 $aux->codigo = "e60_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'credor';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_cgm_empenho.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_cgm";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 1;
                 $aux->linhas = 4;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
     </table>
     </tr>
     <table border=0> 
      <tr>
          <td nowrap align="left" colspan=3>
               <b> Per�odo </b>
               <? 
	          $dia="01";
		  $mes="01";
		  $ano= db_getsession("DB_anousu");
		  $dia2="31";
		  $mes2="12";
		  $ano2= db_getsession("DB_anousu");
	          db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
                  echo " a ";
                  db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
               ?>
          </td>
       </tr>
       <tr>
         <td colspan=3>
             <b> Tipo de Compra: </b>
	       <?  
	          $res = $clpctipocompra->sql_record(
		      $clpctipocompra->sql_query_file(null,"pc50_codcom,pc50_descr","pc50_descr"));
	          db_selectrecord("tipocompra",$res,true,1,'','','','0');   ?>
         </td>
      </tr>
       <tr>
           <td nowrap align="left">
                    <b> Ordem:</b>
                    <select name="ordem">
                      <option name="ordem" value="e">Empenho </option> 
                      <option name="ordem" value="d">Data Empenho</option>
                      <option name="ordem" value="l">Data Lan�amento</option>
                      <option name="ordem" value="t">Tipo</option>
                      <option name="ordem" value="v">Valor</option>
                      <option name="ordem" value="c">Credor</option>
                    </select>
	       <br>
               <b> Mostrar Historicos: </b>
               <select name="com_mov">
                    <option name="com_mov" value="s">Sim</option>
                    <option name="com_mov" value="n">N�o</option>
               </select><br>
               <b> Mostrar Itens: </b>
               <select name="mostraritem">
                    <option name="mostraritem" value="m">Sim</option>
                    <option name="mostraritem" value="n">N�o</option>
               </select><br>
               <b>  Lista RP: </b>
               <select name="rp">
                    <option name="rp" value="s">Sim</option>
                    <option name="rp" value="n">N�o</option>
                    <option name="rp" value="somente">Somente RP</option>
               </select><br>
               <b>  Tipo Documento: </b>
               <select name="tipopessoa">
                    <option name="tipopessoa" value="todos">Todos</option>
                    <option name="tipopessoa" value="cpf">CPF</option>
                    <option name="tipopessoa" value="cnpj">CNPJ</option>
               </select><br>
               <b>  Agrupar Por: </b>
               <select name="agrupar">
                    <option name="agrupar" value="n" onclick="disable_select()">N�o Agrupar</option>
                    <option name="agrupar" value="c" onclick="enable_select()">Credor</option>
                   
               </select><br>
               <b>  Mostrar lan�amentos: </b>
               <select name="mostrarlancamentos" id="mostrarlancamentos" disabled>
                    <option name="mostrarlancamentos" value="s">Sim</option>
                    <option name="mostrarlancamentos" value="n">N�o</option>    
               </select><br>
               
               </select><br>
               <b>  Filtro: </b>
               <select name="autonomofiltro">
                    <option name="autonomofiltro" value="n">Nenhum</option>
                    <option name="autonomofiltro" value="a">Aut�nomos</option>
                   
               </select><br>

           </td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <td> 
<!--             <b>
	      Totaliza&ccedil;&atilde;o por:</b><br>-->
	           <input type="checkbox" name="emp" checked >Empenho<br>
	           <input type="checkbox" name="estemp" checked >Estorno de Empenho<br>
	           <input type="checkbox" name="liq" checked >Liquida��o<br>
	           <input type="checkbox" name="estliq" checked >Estorno de Liquida��o<br>
             <input type="checkbox" name="pag" checked >Pagamento<br>	
             <input type="checkbox" name="estpag" checked >Estorno de Pagamento<br>
             <br>
             <input type="checkbox" name="imprime_filtro" >Imprime Filtro <br>
	       </td>
       </tr>
	     <tr> 
         <td align="left">
           <b>  Imprimir Dados do Fornecedor: </b>
         </td>
         <td>
           <select name="dados_fornecedor" id='dados_fornecedor'>
                <option value="s">Sim</option>
                <option value="n">N�o</option>
           </select>
         </td>
       </tr> 
    </table>
  </table>
  </fieldset>
  </div>
  <input name="Imprimir" type="button" onclick='js_imprime();'  value="Imprimir">
  </form>
</center>
</body>
</html>