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
include("classes/db_pcorcam_classe.php");
include("classes/db_pcproc_classe.php");
$clpcorcam = new cl_pcorcam;
$clpcproc = new cl_pcproc;
$clpcorcam->rotulo->label();
$clpcproc->rotulo->label();
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(){
  if(document.form1.pc20_codorc.value=="" && document.form1.pc80_codproc.value!=""){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?numero='+document.form1.pc80_codproc.value+'&sol=false&rel=true&funcao_js=parent.js_mostrapcorcamsol|pc20_codorc','Pesquisa',true);
  }else if(document.form1.pc20_codorc.value == ""){
    document.form1.pc20_codorc.focus();
    alert("Informe o c�digo do or�amento!");
  }else{
    vir = "";
    forne = "";
    x = iframe_fornec.document.form1;
    for(i=0;i<x.length;i++){
      if(x.elements[i].type == "checkbox"){
				if(x.elements[i].checked==true){
	  			forne += vir+x.elements[i].name;
        	if(x.elements[i].name == "branco"){
          	forne = "branco";
          	break;
        	}
				}
      }
    }
    if(forne==""){
      alert("Informe no m�nimo um fornecedor.");
    }else{
      jan = window.open('com2_procorc002.php?pc20_codorc='+document.form1.pc20_codorc.value+'&forne='+forne,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      /*
      arr_forne = forne.split("forn_");
      for(i=1;i<arr_forne.length;i++){
	alert(arr_forne[i]);
      }
      */
    }
  }
}
function js_mostrapcorcamsol(chave){
  document.form1.pc20_codorc.value = chave;
  document.form1.pc80_codproc.value = "";
  db_iframe_pcorcam.hide();
  document.location.href = "com2_procorc001.php?pc20_codorc="+chave;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.pc20_codorc.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post">
<table border='0'>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td  align="right" nowrap title="<?=$Tpc20_codorc?>"> <? db_ancora(@$Lpc20_codorc,"js_pesquisa_pcorcam(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("pc20_codorc",8,$Ipc20_codorc,true,"text",3,"onchange='js_pesquisa_pcorcam(false);'");
      ?>
    </td>
  </tr>
  <tr>
    <td  align="right" nowrap title="<?=$Tpc80_codproc?>"> <? db_ancora(@$Lpc80_codproc,"js_pesquisapc80_codproc(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("pc80_codproc",8,$Ipc80_codproc,true,"text",4,"onchange='js_pesquisapc80_codproc(false);'");
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="relatorio" type="button" onclick='js_abre();'  value="Gerar relat�rio">
      <input name="limpar" type="button" onclick='js_limparcampos();'  value="Limpar campos">
    </td>
  </tr>
  <?if(isset($pc20_codorc)){?>
  <tr>
    <td colspan=2>
    <iframe name="iframe_fornec" id="fornecedores" marginwidth="0" marginheight="0" frameborder="0" src="com1_selfornecedor001.php?pc21_codorc=<?=$pc20_codorc?>" width="400" height="250"></iframe>
    </td>
  </tr>
  <?}?>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
</table>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_limparcampos(){
  document.form1.pc20_codorc.value="";
  document.form1.pc80_codproc.value="";
  document.location.href = "com2_procorc001.php";
}
function js_pesquisa_pcorcam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?sol=false&funcao_js=parent.js_mostrapcorcam1|pc20_codorc','Pesquisa',true);
  }else{
     if(document.form1.pc20_codorc.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcorcam','func_pcorcamlancval.php?pesquisa_chave='+document.form1.pc20_codorc.value+'&sol=false&funcao_js=parent.js_mostrapcorcam','Pesquisa',true);
     }
  }
}
function js_mostrapcorcam(chave,erro){
  if(erro==true){
    document.form1.pc20_codorc.focus();
    document.form1.pc20_codorc.value = '';
    document.location.href = "com2_procorc001.php";
  }else{
    document.location.href = "com2_procorc001.php?pc20_codorc="+chave;
  }
}
function js_mostrapcorcam1(chave1,chave2){
  document.form1.pc20_codorc.value = chave1;
  db_iframe_pcorcam.hide();
  document.location.href = "com2_procorc001.php?pc20_codorc="+chave1;
}
function js_pesquisapc80_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc','Pesquisa',true);
  }else{
     if(document.form1.pc80_codproc.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcproc','func_pcproc.php?pesquisa_chave='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_mostrapcproc','Pesquisa',false);
     }else{
       document.form1.pc80_codproc.value = '';
     }
  }
}
function js_mostrapcproc(chave,erro){
  if(erro==true){
    document.form1.pc80_codproc.focus();
    document.form1.pc80_codproc.value = '';
  }
}
function js_mostrapcproc1(chave1,x){
  document.form1.pc80_codproc.value = chave1;
  db_iframe_pcproc.hide();
}
//--------------------------------
</script>
</body>
</html>
