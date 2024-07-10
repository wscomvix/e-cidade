<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_assenta_classe.php");
include("classes/db_tipoasse_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$classenta  = new cl_assenta;
$cltipoasse = new cl_tipoasse;

$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){


  /**
   * Verificamos se o assentamento j� n�o esta vinculado com um lote de registros de ponto
   * se estiver, n�o permite a exclus�o.
   */
  $oDaoAssentaLoteRegistroPonto = new cl_assentaloteregistroponto();
  $sSqlAssentaLoteRegistroPonto = $oDaoAssentaLoteRegistroPonto->sql_query_file(null, "rh160_sequencial", null, "rh160_assentamento = {$h16_codigo}");
  $rsAssentaLoteRegistroPonto   = db_query($sSqlAssentaLoteRegistroPonto);
 
  if (pg_num_rows($rsAssentaLoteRegistroPonto) > 0) {
    
    db_msgbox("Assentamento j� possu� evento financeiro, exclus�o n�o permitida.");
    db_redireciona("");
  }

  db_inicio_transacao();
  $db_opcao = 3;

  $oPeriodoAquisitivoAssentamento = PeriodoAquisitivoAssentamento::getPeriodoAquisitivoAssentamento(new Assentamento( $h16_codigo ));

  if ($oPeriodoAquisitivoAssentamento) {
    $oPeriodoAquisitivoAssentamento->excluir();
  }

  /**
   * Tratamento para exclus�o de assentamentos de substitui��o
   */
  $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao();
  $rsAssentamentoSubstituicao   = $oDaoAssentamentoSubstituicao->sql_record($oDaoAssentamentoSubstituicao->sql_query_file($h16_codigo));

  if($rsAssentamentoSubstituicao && $oDaoAssentamentoSubstituicao->numrows > 0){
    $oDaoAssentamentoSubstituicao->excluir($h16_codigo);
  }

  /**
   * Tratamento para exclus�o de assentamentos com atributos dinamicos
   */
  $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
  $oDaoAssentaAttr->excluir(null,null, "h80_assenta = $h16_codigo" );


  /**
   * verificamos se o assentamento tem vinculo com afastamento no pessoal, 
   * e efetuamos a exclusao do mesmo
   */
  $clafastaassenta   = new cl_afastaassenta();
  $sSqlAfastaAssenta = $clafastaassenta->sql_query_file(null, "h81_afasta", null, "h81_assenta = {$h16_codigo}");
  $rsAfastaAssenta   = db_query($sSqlAfastaAssenta);

  if (!$rsAfastaAssenta) {
    throw new DBException($rsAfastaAssenta->erro_msg);
  }

  if (pg_num_rows($rsAfastaAssenta) > 0) {
    
    $clafastaassenta->excluir(null, "h81_assenta = {$h16_codigo}");
    $clafasta = new cl_afasta();
    $clafasta->excluir(db_utils::fieldsMemory($rsAfastaAssenta, 0)->h81_afasta);
  }

  /**
   * Exclui da tabela assenta.
   */
  $classenta->excluir($h16_codigo);

  db_fim_transacao();

  if ($classenta->erro_status != "0") {
    $h12_codigo = $h12_assent = $h16_assent = '';
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $classenta->sql_record($classenta->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmassenta.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($classenta->erro_status=="0"){
    $classenta->erro(true,false);
  }else{
    $classenta->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if(isset($h16_conver) && trim($h16_conver) == "t"){
  db_msgbox("Assentamento gerado a partir de dois assentamentos de meio dia. \\nExclus�o n�o permitida.");
  echo "<script>location.href = 'rec1_assenta003.php'</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>