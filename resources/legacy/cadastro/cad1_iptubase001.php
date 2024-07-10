<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *  junto com este programa; se n�o, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$verilote          = false;
$verimatricula     = false;
$j18_utidadosdiver = false;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
//  db_postmemory($HTTP_SERVER_VARS,2);

$rsparametro = db_query("select * from cfiptu where j18_anousu = ".db_getsession('DB_anousu'));
$numrows     = pg_numrows($rsparametro);
if($numrows > 0){
  db_fieldsmemory($rsparametro,0);
}else{
  db_msgbox("Configure os parametros de calculo do iptu no modulo cadastro !!");
}

?>
<head>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/JavaScript">
    function js_novolote2(idmatricul,setor,quadra,bairro,loteam,zona,caract,setor1,bairro1,loteam1){
     document.formaba.matricula.disabled = true;
     iframe_lote.location.href="cad1_lotealt.php?idmatricu="+idmatricul+"&j34_setor="+setor+"&j34_quadra="+quadra+"&j34_bairro="+bairro+"&j34_loteam="+loteam+"&j34_zona="+zona+"&caracteristica="+caract+"&j30_descr="+setor1+"&j13_descr="+bairro1+"&j34_descr="+loteam1;
   }
   function js_novamatric(){
    idlote = document.form1.idlote.value;
    location.href="cad1_iptubase001.php?idlote="+idlote+"&liberamatri=true";
  }
  function js_parentiframe(iframe,confere){

    if(iframe=="lote" && confere==true){
     document.formaba.lote.style.color = "#666666";
     document.formaba.lote.style.fontWeight = "normal";
     document.formaba.matricula.disabled = false;
     mo_camada('matricula',true,'Iframe2');

     iframe_iptubase.document.form1.j01_idbql.value=document.form1.idlote.value;

     var ve_constr=document.formaba.ve_constr.value;


     if(ve_constr==false){
       for(var j=0;j<10000; j++){
        ve_constr=document.formaba.ve_constr.value;
        if(ve_constr==true){
          break;
        }
      }
    }
    iframe_iptuconstr.document.form1.id_setor2.value=document.form1.idsetor.value;
    iframe_iptuconstr.document.form1.id_quadra2.value=document.form1.idquadra.value;

    var ve_constrescr=document.formaba.ve_constrescr.value;
    if(ve_constrescr==false){
     for(var j=0;j<10000; j++){
      ve_constrescr=document.formaba.ve_constrescr.value;
      if(ve_constrescr==true){
        break;
      }
    }
  }
  iframe_constrescr.document.form1.id_setor.value=document.form1.idsetor.value;
  iframe_constrescr.document.form1.id_quadra.value=document.form1.idquadra.value;

}else if(iframe=="matricula" && confere==true){
  document.formaba.constr.disabled = false;
  document.formaba.escrit.disabled = false;
  document.formaba.imobiliaria.disabled = false;
  document.formaba.promitente.disabled = false;
  document.formaba.outros.disabled = false;
  document.formaba.ender.disabled = false;
  document.formaba.novamatric.disabled = false;
  document.formaba.dadosdiver.disabled = false;
  document.formaba.isencao.disabled    = false;


  iframe_lote.document.form1.idmatricu.value=document.form1.idmatricula.value;

  iframe_iptuconstr.document.form1.j39_matric.value=document.form1.idmatricula.value;
  iframe_iptuconstr.document.form1.z01_nome.value=document.form1.nomematricula.value;
  iframe_iptuconstr.document.form1.id_setor2.value=document.form1.idsetor.value;
  iframe_iptuconstr.document.form1.id_quadra2.value=document.form1.idquadra.value;

  iframe_constrescr.document.form1.j52_matric.value=document.form1.idmatricula.value;
  iframe_constrescr.document.form1.z01_nome.value=document.form1.nomematricula.value;
  iframe_constrescr.document.form1.id_setor.value=document.form1.idsetor.value;
  iframe_constrescr.document.form1.id_quadra.value=document.form1.idquadra.value;

  iframe_imobil.document.form1.j44_matric.value=document.form1.idmatricula.value;
  iframe_imobil.document.form1.z01_nomematri.value=document.form1.nomematricula.value;

  iframe_promitente.document.form1.j41_matric.value=document.form1.idmatricula.value;
  iframe_promitente.document.form1.z01_nomematri.value=document.form1.nomematricula.value;

  iframe_propri.document.form1.j42_matric.value=document.form1.idmatricula.value;
  iframe_propri.document.form1.z01_nomematri.value=document.form1.nomematricula.value;


  iframe_iptuender.document.form1.j43_matric.value = document.form1.idmatricula.value;
  iframe_iptuender.document.form1.z01_nome.value   = document.form1.nomematricula.value;
  iframe_dadosdiver.document.form1.j80_matric.value = document.form1.idmatricula.value;

  iframe_isencao.location.href = "cad4_iptuisen002.php?alterando=true&j46_matric="+document.form1.idmatricula.value;

  mo_camada('constr',true,'Iframe3');
}
}
function mo_camada(idtabela,mostra,camada){
 var tabela = document.getElementById(idtabela);
 var divs = document.getElementsByTagName("DIV");
 var tab  = document.getElementsByTagName("TABLE");
 var aba = eval('document.formaba.'+idtabela+'.name');
 var input = eval('document.formaba.'+idtabela);
 var alvo = document.getElementById(camada);
 for (var j = 0; j < divs.length; j++){
  if(mostra){
    if(alvo.id == divs[j].id){
      divs[j].style.visibility = "visible" ;
      divs[j].style.zIndex = 99;
      divs[j].style.width  = screen.availWidth;
      divs[j].style.height  = screen.availHeight;

    }else{
      if(divs[j].className == 'tabela'){
        divs[j].style.visibility = "hidden";
        divs[j].style.zIndex = 98;
        divs[j].style.width  = screen.availWidth;
        divs[j].style.height  = screen.availHeight;
      }
    }
  }else{
    if(alvo.id == divs[j].id){
     divs[j].stlert(dadosveri[1]);
     divs[j].style.width  = screen.availWidth;
     divs[j].style.height  = screen.availHeight;

   }
 }
}
for(var x = 0; x < tab.length; x++){
  if(tab[x].className == 'bordas'){
    for(y=0; y < document.forms['formaba'].length; y++){
      tab[x].style.border = "1px outset #cccccc";
      tab[x].style.borderBottomColor = "#000000";

      document.formaba.lote.style.color = "#666666";
      document.formaba.lote.style.fontWeight = "normal";

      document.formaba.matricula.style.color = "#666666";
      document.formaba.matricula.style.fontWeight = "normal";

      document.formaba.constr.style.color = "#666666";
      document.formaba.constr.style.fontWeight = "normal";

      document.formaba.escrit.style.color = "#666666";
      document.formaba.escrit.style.fontWeight = "normal";

      document.formaba.imobiliaria.style.color = "#666666";
      document.formaba.imobiliaria.style.fontWeight = "normal";

      document.formaba.promitente.style.color = "#666666";
      document.formaba.promitente.style.fontWeight = "normal";

      document.formaba.outros.style.color = "#666666";
      document.formaba.outros.style.fontWeight = "normal";

      document.formaba.ender.style.color = "#666666";
      document.formaba.ender.style.fontWeight = "normal";

      document.formaba.isencao.style.color = "#666666";
      document.formaba.isencao.style.fontWeight = "normal";
    }
    if(aba == tab[x].id){
      tab[x].style.border = "3px outset #999999";
      tab[x].style.borderBottomWidth = "0px";
      tab[x].style.borderRightWidth = "1px";
      tab[x].style.borderLeftColor =  "#000000";
      tab[x].style.borderTopColor =  "#3c3c3c";
      tab[x].style.borderRightColor =  "#000000";
      tab[x].style.borderRightStyle =  "inset";
    }
    input.style.color = "black";
    input.style.fontWeight = "bold";
  }
}

}
function js_veripros(nome){
  cgm_iptubase = iframe_iptubase.document.form1.j01_numcgm.value;
  cgm_propri = iframe_propri.document.form1.j42_numcgm.value;
  cgm_promitente = iframe_promitente.document.form1.j41_numcgm.value;

  cgm_selpropri = iframe_propri.document.form1.cgmpropri.value;
  cgm_selpromitente = iframe_promitente.document.form1.cgmpromi.value;

  proprimatriz="";
  promimatriz="";
  proprimatriz=cgm_selpropri.split("#");
  promimatriz=cgm_selpromitente.split("#");
  if(nome=="iptubase"){
    for(var i=0; i<proprimatriz.length;i++){
      if(cgm_iptubase==proprimatriz[i]){
       alert("Nome cadastrado como Outros Propriet?rios! Verifique!");
       return false;
       break;
     }
   }
   for(var i=0; i<promimatriz.length;i++){
    if(cgm_iptubase==promimatriz[i]){
     alert("Nome cadastrado como Promitente ou Possuidor! Verifique!");
     return false;
     break;
   }
 }
}else if(nome=="propri"){
  for(var x=0; x<promimatriz.length;x++){
    if(promimatriz[x]==cgm_propri){
     alert("Nome cadastrado como Promitente ou Possuidor! Verifique!");
     return false;
     break;
   }

 }
 if(cgm_iptubase==cgm_propri){
   alert("Nome j? cadastrado como propriet?rio principal! Verifique!");
   return false;
 }
}else if(nome="promitente"){
  for(var x=0; x<proprimatriz.length;x++){
    if(proprimatriz[x]==cgm_promitente){
     alert("Nome cadastrado como Propriet?rio Secunnd?rio! Verifique!");
     return false;
     break;
   }

 }
 if(cgm_iptubase==cgm_promitente){
   alert("Nome cadastrado como propriet?rio principal! Verifique!");
   return false;
 }



}

return true;

}
</script>
<style>
  a {text-decoration:none;
  }
  a:hover {text-decoration:none;
   color: #666666;
 }
 a:visited {text-decoration:none;
   color: #999999;
 }
 a:active {
  color: black;
  font-weight: bold;
}
.nomes {background-color: transparent;
  border:none;
  text-align: center;
  font-size: 11px;
  color: #666666;
  font-weight:normal;
  cursor: hand;
}
.nova {background-color: transparent;
 border:none;
 text-align: center;
 font-size: 11px;
 color: darkblue;
 font-weight:bold;
 cursor: hand;
 height:14px;
}
.bordas{border: 1px outset #cccccc;
  border-bottom-color: #000000;
}
.bordasi{border: 0px outset #cccccc;
}
.novamat{border: 2px outset #cccccc;
 border-right-color: darkblue;
 border-bottom-color: darkblue;
 background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad=" js_trocacordeselect();">

  <table width="100%" border="1" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table valign="top" marginwidth="0" height=100% width="100%" border="3" cellspacing="0" cellpadding="0" >
    <tr>
      <form name="formaba" method="post" id="formaba" >
        <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
         <table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
           <tr>
             <td>
              <table class="bordas" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " id="lote"  cellpadding="3" cellspacing="0" width="12%">
                <tr>
                  <td nowrap>
                    <input readonly name="lote" class="nomes" style="font-weight:bold; color:black" type="text" value="Lote" title="Cadastro de Lote" size="4" maxlength="7" onClick="mo_camada('lote',true,'Iframe1');">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="matricula" cellpadding="3" cellspacing="0" width="12%">
                <tr>
                  <td  id="link_matric" nowrap>
                    <input <?=(isset($liberamatri)?"":"disabled")?> readonly name="matricula" type="text" value="Matricula" size="10" maxlength="10"  class="nomes"  title="Matricula do Proprietario"  onClick="mo_camada('matricula',true,'Iframe2');">
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table border="0" class="bordas" id="constr" cellpadding="3" cellspacing="0" width="12%">
               <tr>
                <td nowrap id="link_constr">
                  <input disabled  readonly type="text" value="Constru��es" size="12" maxlength="12"  class="nomes"  name="constr" title="Constru��es" onClick="mo_camada('constr',true,'Iframe3');">
                  <input type="hidden" name="ve_constr" value="false">
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table border="0" class="bordas" id="escrit" cellpadding="3" cellspacing="0" width="12%">
              <tr>
                <td id="link_constrescr" nowrap>
                  <input disabled readonly type="text" value="Escrituradas" size="12" maxlength="12"  class="nomes"  name="escrit" title="Constru��es Escrituradas" onClick="mo_camada('escrit',true,'Iframe4');">
                  <input type="hidden" name="ve_constrescr" value="false">
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table border="0" class="bordas" id="imobiliaria" cellpadding="3" cellspacing="0" width="12%">
              <tr>
                <td id="link_imobil" nowrap>
                  <input disabled readonly type="text" value="Imobili�ria" size="11" maxlength="11"  class="nomes"  name="imobiliaria" title="Manuten��o de Imobili�ria"  onClick="mo_camada('imobiliaria',true,'Iframe5');">
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table border="0" class="bordas" id="promitente" cellpadding="3" cellspacing="0" width="12%">
              <tr>
                <td id="link_promit" nowrap>
                  <input disabled readonly type="text" value="Promitente" size="10" maxlength="10"  class="nomes"  name="promitente" title="Manuten��o de Promitente Comprador" onClick="mo_camada('promitente',true,'Iframe6');">
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table border="0" class="bordas" id="outros" cellpadding="3" cellspacing="0" width="12%">
              <tr>
                <td id="link_propri" nowrap >
                  <input disabled readonly type="text" value="Outros Propr" size="12" maxlength="12"  class="nomes"  name="outros"  title="Outros Propriet�rios"  onClick="mo_camada('outros',true,'Iframe7');">
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table border="0" class="bordas" id="ender" cellpadding="3" cellspacing="0" width="12%">
              <tr>
                <td id="link_entreg" nowrap>
                  <input disabled readonly type="text" value="End.Entrega" size="11" maxlength="11"  class="nomes"  name="ender" title="Manuten��o de Endere�os de Entrega" onClick="mo_camada('ender',true,'Iframe8');">
                </td>
              </tr>
            </table>
          </td>

          <td>
            <table border="0" class="bordas" id="isencao" cellpadding="3" cellspacing="0" width="12%">
              <tr>
                <td id="link_isencao" nowrap>
                  <input disabled readonly type="text" value="Isen��o" size="11" maxlength="11"  class="nomes"  name="isencao" title="Isen��o" onClick="mo_camada('isencao',true,'Iframe10');">
                </td>
              </tr>
            </table>
          </td>

          <!-- criado por robson -->

          <td>
            <table border="0" class="bordas" id="dadosdiver" cellpadding="<?=($j18_utidadosdiver=='t'?"3":"0")?>" cellspacing="0" width="12%">
              <tr>
                <td id="link_dadosdiver" nowrap>
                  <input disabled readonly type="<?=($j18_utidadosdiver=='t'?"text":"hidden")?>" value="Dados diversos" size="11" maxlength="11"  class="nomes"  name="dadosdiver" title="Manuten��o de dados diversos" onClick="mo_camada('dadosdiver',true,'Iframe9');">
                </td>
              </tr>
            </table>
          </td>

          <td>
            <table valign="top" border="0" class="novamat" id="novamatric" cellpadding="3" cellspacing="0" width="12%">
              <tr valign="top">
                <td id="link_novamatric" nowrap>
                  <input disabled readonly type="text" value="Nova Mat" size="8" maxlength="8"  class="nova"  name="novamatric" title="Nova Matricula" onClick="js_novamatric();">
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </form>
</tr>
<tr>
  <form name="form1" method="post" id="form1" >
    <td nowrap>
      <input name="idlote" type="hidden" value="<?=@$idlote?>" >
      <input name="idmatricula" type="hidden" value="" >
      <input name="nomematricula" type="hidden" value="" >
      <input name="idsetor" type="hidden" value="" >
      <input name="idquadra" type="hidden" value="" >
      <input name="pripromitente" type="hidden" value="false" >
    </td>
    <td height="100%" width=100%>    <br><br>

      <div  id="Iframe1" style="position:absolute;left:0px; top:47px; z-index:99; visibility: visible;">
       <?
       $query_xx = "";
       if(isset($idlote)){
         $query_xx.="?j34_idbql=$idlote&novolote=true";
       }else if(isset($nov)){
         $query_xx = "?".$HTTP_SERVER_VARS['QUERY_STRING'];
       }
       ?>
       <iframe name="iframe_lote" frameborder="0" marginwidth="0" leftmargin="0" topmargin="0" src="cad1_lotealt.php<?=$query_xx?>"  height="100%" scrolling="no"  width="100%">
       </iframe>
     </div>

     <div class="tabela" id="Iframe2" style="position:absolute; left:0px; top:47px; z-index:99; visibility: <?=(isset($liberamatri)?"visible":"hidden")?>;">
       <iframe id="iptubase"  class="bordasi"  frameborder="0" name="iframe_iptubase"   leftmargin="0" topmargin="0" src="cad1_iptubasealt.php<?=(isset($idlote)?"?j01_idbql=$idlote":"")?>" scrolling="no"  height="100%" width="100%"></iframe>
     </div>

     <div class="tabela" id="Iframe3" style="position:absolute; left:0px; top:47px; z-index:99; visibility: hidden;">
      <iframe name="iframe_iptuconstr" class="bordasi" frameborder="0" leftmargin="0" topmargin="0" src="cad1_iptuconstralt.php" scrolling="no" height=100% width=100%></iframe>
    </div>

    <div class="tabela" id="Iframe4" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: hidden;">
      <iframe name="iframe_constrescr" class="bordasi" frameborder="0" leftmargin="0" topmargin="0" src="cad1_constrescralt.php" scrolling="no" height="100%" width=100%></iframe>
    </div>

    <div class="tabela" id="Iframe5" style="position:absolute; left:0px; top:47px; z-index:99; visibility: hidden;">
      <iframe name="iframe_imobil" frameborder="0" class="bordasi" leftmargin="0" topmargin="0" src="cad1_imobilalt.php" scrolling="no" height="100%" width="100%"></iframe>
    </div>

    <div class="tabela" id="Iframe6" style="position:absolute; left:0px; top:47px; z-index:99; visibility: hidden;">
      <iframe name="iframe_promitente" frameborder="0" class="bordasi" leftmargin="0" topmargin="0" src="cad1_promitentealt.php" scrolling="no" height="100%" width="100%"></iframe>
    </div>

    <div class="tabela" id="Iframe7" style="position:absolute; left:0px; top:47px; z-index:99; visibility: hidden;">
      <iframe name="iframe_propri" frameborder="0" class="bordasi" leftmargin="0" topmargin="0" src="cad1_proprialt.php" scrolling="no" height="100%" width="100%"></iframe>
    </div>
    <div class="tabela" id="Iframe8" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: hidden;">
      <iframe name="iframe_iptuender" frameborder="0" class="bordasi" leftmargin="0" topmargin="0" src="cad1_iptuenderalt.php" scrolling="no" height="100%" width="100%"></iframe>
    </div>
    <!-- criado por robson -->
    <div class="tabela" id="Iframe9" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: hidden;">
      <iframe name="iframe_dadosdiver" frameborder="0" class="bordasi" leftmargin="0" topmargin="0" src="cad1_iptudiversosalt001.php" scrolling="no" height="100%" width="100%"></iframe>
    </div>

    <div class="tabela" id="Iframe10" style="position:absolute; left:0px; top:47px;  z-index:99; visibility: hidden;">
     <iframe name="iframe_isencao"  frameborder="0"  class="bordasi"  leftmargin="0" topmargin="0"  src=""  scrolling="no" height="100%" width="100%"></iframe>
    </div>

    <div id="load"  style="position:absolute; left:300px; top:167px; z-index:99;visibility: visible;">

     Processando.&nbsp;Aguarde...
   </div>

 </td>
</form>
</tr>
<tr>
</tr>
</table>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  <?
  if(isset($liberamatri)){
    echo "
    function js_novamatriaba(){
      mo_camada('matricula',true,'Iframe2');
    }
    js_novamatriaba();
    ";

  }

  ?>

  mo_camada('lote',true,'Iframe1');


</script>
