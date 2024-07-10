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
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd04_i_unidade");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
$db_opcao = 1;
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
  <td align="center" valign="top" bgcolor="#CCCCCC">
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
      <td nowrap title="<?=@$Tsd04_i_medico?>">
       <?
       db_ancora(@$Lsd04_i_medico,"js_pesquisasd04_i_medico(true);",$db_opcao);
       ?>
      </td>
      <td>
       <?
       db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd04_i_medico(false);'")
       ?>
       <?
       db_input('z01_nome',80,$Iz01_nome,true,'text',3,'')
       ?>
     </td>
    </tr>
   </table>
   <table border="1" cellpadding="0" cellspacing="0" width="90%">
     <?
      $result = $clunidades->sql_record($clunidades->sql_query());
      if($clunidades->numrows > 0){
     ?>
       <tr>
        <td bgcolor="#D0D0D0" width="30"><input type="button" value="M" name="marca" title="Marcar/Desmarcar" onclick="marcar(<?=$clunidades->numrows?>, this)"></td>
        <td colspan="5"><b>Selecione as Unidades</b></td>
       </tr>
      <?$bg = "#E8E8E8";
        echo "<tr bgcolor='#b0b0b0'>";
        for($u=0; $u< $clunidades->numrows; $u++){
         db_fieldsmemory($result,$u);
         echo "<td align='center' width='30'><input type='checkbox' value='$sd02_i_codigo' name='unidade'></td><td align='center' width='50'>".$sd02_i_codigo."</td><td width='400'>".$descrdepto."</td>";
          @$coluna = $coluna + 1;
          if ($coluna>1)
            {
             echo "<tr>";
             echo "<tr bgcolor='$bg'>";
             if($bg == "#E8E8E8"){
              $bg = "#B0B0B0";
             }else{
              $bg = "#E8E8E8";
             }
             $coluna = 0;
            }
        }
        }else{
         echo "<tr><td class='texto'>Unidades n�o cadastradas</td></tr>";
        }
      ?>
     <tr>
       <td colspan='6' align='center' >
         <input name='start' type='button' value='Gerar' onclick="valida(<?=$clunidades->numrows?>,this)">
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
function marcar(tudo,documento)
 {
  for(i=0;i<tudo;i++)
   {
    if(documento.value=="D")
     {
      document.form1.unidade[i].checked=false;
     }
    if(documento.value=="M")
     {
      document.form1.unidade[i].checked=true;
     }
   }
  if(document.form1.marca.value == "D")
   {
    document.form1.marca.value="M";
   }
  else
   {
    document.form1.marca.value="D";
   }
 }

function valida(tudo,documento){
   obj = document.form1;
   count = 0;
   query='';
   if((obj.data1_dia.value !='') && (obj.data1_mes.value !='') && (obj.data1_ano.value !='') && (obj.data1_dia.value !='') && (obj.data2_mes.value !='') && (obj.data2_ano.value !='')){
     query +="data1="+obj.data1_ano.value+"X"+obj.data1_mes.value+"X"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"X"+obj.data2_mes.value+"X"+obj.data2_dia.value;
     count +=1;
   }
    if(count<1){
      alert("Preencha o Per�do Corretamente!");
    }else{
         query +="&medico="+obj.sd04_i_medico.value;
         query +="&unidades=";
         sep = "";
         for(i=0;i<tudo;i++)
         {
          if(obj.unidade[i].checked == true)
           {
            query += sep+obj.unidade[i].value;
            sep = "X";
            count += 1;
           }
         }
         if(count<2){
           alert("Preencha os Campos Corretamente!");
         }else{
           jan = window.open('sau2_atendimentos002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
           jan.moveTo(0,0);
         }
    }
}

function js_pesquisasd04_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_medico.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd04_i_medico.focus();
    document.form1.sd04_i_medico.value = '';
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd04_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}
</script>