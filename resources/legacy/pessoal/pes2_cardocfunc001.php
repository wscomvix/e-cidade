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
include("dbforms/db_classesgenericas.php");
$gform    = new cl_formulario_rel_pes;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  let qry = '';
  qry += "&tipo="             + document.form1.tipo.value;
  qry += "&quebrar="          + document.form1.quebrar.value;
  if(document.form1.selcargo){
    if(document.form1.selcargo.length > 0){
      faixacargo = js_campo_recebe_valores();
      qry+= "&fca="+faixacargo;
    }
  }else if(document.form1.cargoi){
    carini = document.form1.cargoi.value;
    carfim = document.form1.cargof.value;
    qry+= "&cai="+carini;
    qry+= "&caf="+carfim;
  }

  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      qry+= "&flt="+faixalot;
    }
  }else if(document.form1.lotaci){
    lotini = document.form1.lotaci.value;
    lotfim = document.form1.lotacf.value;
    qry+= "&lti="+lotini;
    qry+= "&ltf="+lotfim;
  }
  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      faixaorg = js_campo_recebe_valores();
      qry+= "&for="+faixaorg;
    }
  }else if(document.form1.orgaoi){
    orgini = document.form1.orgaoi.value;
    orgfim = document.form1.orgaof.value;
    qry+= "&ori="+orgini;
    qry+= "&orf="+orgfim;
  }
  jan = window.open('pes2_cardocfunc002.php?regime='+document.form1.reg.value+
					      '&ordem='+document.form1.ordem.value+
					      '&cargo='+document.form1.cargo.value+
					      '&demit='+document.form1.demit.value+
					      '&ano='+document.form1.DBtxt23.value+
					      '&mes='+document.form1.DBtxt25.value+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
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

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return ;">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de compet�ncia" >
        <strong>Ano / M�s :</strong>
        </td>
        <td>&nbsp;&nbsp;&nbsp;
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Ordem para a emiss�o do relat�rio" ><strong>Ordem : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
            $xx = array("n"=>"Num�rica","a"=>"Alfab�tica");
            db_select('ordem',$xx,true,4,"");
	  ?>
	</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Regime do funcion�rio" ><strong>Regime : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
	    $sql = "select rh30_codreg,rh30_descr from rhregime where rh30_instit = ".db_getsession('DB_instit')." order by rh30_codreg";
	    $res = pg_query($sql);
            db_selectrecord('reg', $res, true, 4,'','','','0');
	  ?>
	</td>
      </tr>
      <tr >
        <td align="right" nowrap  ><strong>Listar Demitidos : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
            $xy = array("n"=>"Nao","s"=>"Sim");
            db_select('demit',$xy,true,4,"");
	  ?>
	</td>
      </tr>
      <tr >
        <td align="right" nowrap  ><strong>Listar Cargo : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
            $xyy = array("n"=>"Nao","s"=>"Sim");
            db_select('cargo',$xyy,true,4,"");
	  ?>
	</td>
      </tr>
      <?
        if(!isset($tipo)){
          $tipo = "l";
        }
        if(!isset($filtro)){
          $filtro = "i";
        }
        
        $gform->tipores = true;
        $gform->usalota = true;               // PERMITIR SELE��O DE LOTA��ES
        $gform->usaLotaFieldsetClass = true;  // PERMITIR SELE��O DE LOTA��ES
        $gform->usaorga = true;               // PERMITIR SELE��O DE �RG�O
        $gform->usacarg = true;               // PERMITIR SELE��O DE Cargo
        //$gform->mostaln = true;             // Removido campo tipo de ordem e carregado manualmente 
                                              
        $gform->masnome = "ordem";            
                                              
        $gform->ca1nome = "cargoi";           // NOME DO CAMPO DO CARGO INICIAL
        $gform->ca2nome = "cargof";           // NOME DO CAMPO DO CARGO FINAL
        $gform->ca3nome = "selcargo";         
        $gform->ca4nome = "Cargo";            
                                              
        $gform->lo1nome = "lotaci";           // NOME DO CAMPO DA LOTA��O INICIAL
        $gform->lo2nome = "lotacf";           // NOME DO CAMPO DA LOTA��O FINAL
        $gform->lo3nome = "sellot";           
                                              
        $gform->or1nome = "orgaoi";           // NOME DO CAMPO DO �RG�O INICIAL
        $gform->or2nome = "orgaof";           // NOME DO CAMPO DO �RG�O FINAL
        $gform->or3nome = "selorg";           // NOME DO CAMPO DE SELE��O DE �RG�OS
        $gform->or4nome = "Secretaria";       // NOME DO CAMPO DE SELE��O DE �RG�OS
                                              
        $gform->trenome = "tipo";             // NOME DO CAMPO TIPO DE RESUMO
        $gform->tfinome = "filtro";           // NOME DO CAMPO TIPO DE FILTRO
                                              
        $gform->resumopadrao = "l";           // TIPO DE RESUMO PADR�O
        $gform->filtropadrao = "i";           
        $gform->strngtipores = "loc";         // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                              
        $gform->selecao = false;               
        $gform->onchpad = true;               // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
    
        $gform->manomes = false;
        $gform->gera_form( db_anofolha(), db_mesfolha() );
      ?>
      <tr >
        <td nowrap><strong>Quebrar :</strong>&nbsp;
        </td>
        <td>
          <?
            $xxy = array(
                      "n"=>"N�O", 
                      "s"=>"SIM"
                        );
            db_select('quebrar',$xxy,true,4,"");
          ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>