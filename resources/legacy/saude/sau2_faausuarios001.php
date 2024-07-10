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
include("classes/db_unidades_classe.php");
$clunidades = new cl_unidades;
$clrotulo = new rotulocampo;
$clrotulo->label("sd02_i_codigo");
$clrotulo->label("sd04_i_unidade");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
     <td align="right" >
       <b> Per�odo:</b>
     </td>
     <td>
       <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
        A
       <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
     </td>
     </tr>
     <tr>
      <td><?db_ancora(@$Lsd04_i_unidade,"js_pesquisasd04_i_unidade(true);",1);?></td>
      <td>
       <?db_input('sd04_i_unidade',10,@$Isd04_i_unidade,true,'text',1," onchange='js_pesquisasd04_i_unidade(false);'")?>
       <?db_input('descrdepto',80,@$Idescrdepto,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
       <td colspan='6' align='center' >
         <input name='start' type='button' value='Gerar' onclick="valida()">
       </td>
     </tr>
    </table>
    </form>
  </td>
 </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_pesquisasd04_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_unidade.value != ''){
        js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd04_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd04_i_unidade.focus();
    document.form1.sd04_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd04_i_unidade.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}
function valida(){
 obj = document.form1;
 query='';
 if((obj.data1_dia.value !='') && (obj.data1_mes.value !='') && (obj.data1_ano.value !='') && (obj.data1_dia.value !='') && (obj.data2_mes.value !='') && (obj.data2_ano.value !='')){
  query +="data1="+obj.data1_ano.value+"X"+obj.data1_mes.value+"X"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"X"+obj.data2_mes.value+"X"+obj.data2_dia.value;
 }else{
  alert("Preencha o per�odo!");
  return false;
 }
 //if(obj.sd04_i_unidade.value != ""){
  query +="&unidade="+obj.sd04_i_unidade.value;
 //}else{
 // alert("Informe a unidade!");
 // return false;
 //}
 jan = window.open('sau2_faausuarios002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>