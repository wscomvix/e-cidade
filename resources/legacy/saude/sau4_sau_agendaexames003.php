<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");


db_postmemory($HTTP_POST_VARS);

$clcgs_und          = new cl_cgs_und;
$clsau_agendaexames = new cl_sau_agendaexames;


//$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
	$clsau_agendaexames->sql_record($clsau_agendaexames->sql_query("","*",""," s113_i_prestadorhorarios = $s112_i_codigo and 
	                                                                       s113_i_numcgs    = $z01_i_cgsund  and 
	                                                                       s113_d_exame  = '$s113_d_exame' "));
	if( $clsau_agendaexames->numrows != 0 ){
		db_msgbox("Paciente ja inclu�do.");
		unset($incluir);	                                                                 
	}else{
		db_inicio_transacao();
		$clsau_agendaexames->s113_i_prestadorhorarios = $s112_i_codigo;
		$clsau_agendaexames->s113_i_login             = db_getsession("DB_id_usuario");
		$clsau_agendaexames->s113_i_numcgs            = $z01_i_cgsund;
		$clsau_agendaexames->s113_d_agendamento       = date("Y",db_getsession("DB_datausu")).'/'.date("m",db_getsession("DB_datausu")).'/'.date("d",db_getsession("DB_datausu"));
		$clsau_agendaexames->s113_d_exame             = $s113_d_exame;
		$clsau_agendaexames->s113_i_ficha             = $s113_i_ficha;
		$clsau_agendaexames->s113_c_hora              = $s113_c_hora;
		$clsau_agendaexames->s113_i_situacao          = 1;
		$clsau_agendaexames->incluir(null);
		db_fim_transacao();
	}
}else if(isset($excluir)){
	db_inicio_transacao();
	$clsau_agendaexames->excluir($chavepesquisaagenda);
	db_fim_transacao();
}else if(isset($chavepesquisaagenda) && !empty($chavepesquisaagenda)){
	$result = $clsau_agendaexames->sql_record($clsau_agendaexames->sql_query($chavepesquisaagenda));
	db_fieldsmemory($result,0);
}

?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmagendamento003.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form1","z01_i_cgsund",true,1,"z01_i_cgsund",true);
</script>
<?php
if(isset($incluir)||isset($excluir) ){
  if($clsau_agendaexames->erro_status=="0"){
    $clsau_agendaexames->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsau_agendaexames->erro_campo!=""){
      echo "<script> document.form1.".$clsau_agendaexames->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsau_agendaexames->erro_campo.".focus();</script>";
    }
  }else{
    //$clsau_agendaexames->erro(true,false);
    ?><script>
			parent.document.getElementById('framecalendario').contentDocument.location.reload(true);
			parent.document.getElementById('frameagendados').contentDocument.location.reload(true);
          	parent.db_iframe_agendamento.hide();    	
    </script><?
  }
}
?>