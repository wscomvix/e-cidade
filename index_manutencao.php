<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<?
require("libs/db_conn.php");
if (isset($serv)&&$serv!=""){	  
   	$servidor = $DB_CONEXAO[$serv]["SERVIDOR"];
   	$user = $DB_CONEXAO[$serv]["USUARIO"];
   	$port = $DB_CONEXAO[$serv]["PORTA"];
	  $senh = $DB_CONEXAO[$serv]["SENHA"];
		$base_pesq = $DB_CONEXAO[$serv]["BASE"];
	  if(!($conn1 = pg_connect("host=$servidor dbname=template1 user=$user port=$port password=$senh "))) {
    //  echo "erro ao conectar...\n";

		  echo "<script>location.href='index.php';</script>";
      exit;
    }
		$sql_bases = "select * from pg_database where datname ilike '$base_pesq%' order by datname;";
		$result_bases = pg_query($sql_bases);    
		$numrows_bases = pg_numrows($result_bases);
		pg_close($conn1);
}else{
$servidor = "";
$user = "";
$port = "";
$senh = ""	;
}
?>
<html>
<head>
<title>Tela de acesso para DBPortal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="Javascript">
<!--
alert("Prezados usuarios o DBPortal estara indisponivel hoje (03/03/2009) a partir das 18h30min. Objetivo: atualizacao de versao.")
//-->
</script>
<script>
function js_alapucha(evt) {
  evt = (evt) ? evt : (window.event) ? window.event : "";
  if(evt.keyCode == 13)
    js_submeter();
}
function js_submeter() {
  document.getElementById('testaLogin').innerHTML = '';
  document.form1.DB_senha.value = calcMD5(document.form1.senha.value);
  document.form1.DB_login.value = document.form1.login.value;
  document.form1.senha.value = "";
  document.form1.login.value = "";
  wname = 'wname' + Math.floor(Math.random() * 10000);
	qry = "";
	if (document.form1.serv){
		qry += "&servidor="+document.form1.servidor.value;
		qry += "&base="+document.form1.base.value;
		qry += "&user="+document.form1.user.value;
		qry += "&port="+document.form1.port.value;
		qry += "&senha="+document.form1.senh.value;
	}
  jan1 = window.open('abrir.php?estenaoserveparanada=1&DB_login=' + document.form1.DB_login.value + '&DB_senha=' + document.form1.DB_senha.value+qry,wname,'width=1,height=1');
//  jan1.blur();  
 // document.form1.login.focus();
// window.close();
}

function js_mostrarelatorio(){
  var alvo = "fpdf151/mostrarelatorio.php?arquivo="+document.form1.arquivo.value;
  jan1 = window.open(alvo,'','location=0');
  document.form1.arquivo.select();
  document.form1.arquivo.focus();
}

// testa se p�gina aceita cookies

function testa_cookie1(){
  var resposta;
  // Esta funcao testa se os cookies sao aceitos
  // Tenta escrever um cookie.
  document.cookie = 'aceita_cookie=sim;path=/;';
  // Checa se conseguiu
  if(document.cookie == '') {
    document.write ('<a href="http://www.dbseller.com.br"><p><font face="Arial" size="4" color="#000080">DBSeller Inform�tica Ltda</font></p></a>');
    document.write ('<TABLE cellSpacing=2 cellPadding=0 width=590 border=0>');
    document.write ('<TBODY>');
    document.write ('<TR>');
    document.write ('<TD style="PADDING-RIGHT: 3px; PADDING-LEFT: 3px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px" bgColor=#93bee2>');
//    document.write ('<FONT face="verdana"><B>Erro: Navegador n�o suporta Cookie</B></br>Esta vers�o do sistema trabalha com cookies, aguarde novas vers�es.</FONT></TD>');
    document.write ('<TD vAlign=top></TD>');
    document.write ('</TR>');
    document.write ('<TR><TD height=6></TD></TR>');
    document.write ('<TR vAlign=top><TD>');
    document.write ('<TABLE borderColor=#000080 cellSpacing=0 cellPadding=3 border=1>');
    document.write ('<TBODY>');
    document.write ('<TR><TD><FONT face="verdana" size=2 color=#000080><B>O navegador que voc� est� usando n�o d� suporte a Cookie ou talvez voc� o tenha desativado.</B></FONT></TD></TR>');
    document.write ('</TBODY>');
    document.write ('</TABLE>');
    document.write ('</TD>');
    document.write ('</TR>');
    document.write ('</TBODY>');
    document.write ('</TABLE>');
    document.write ('<P>');
    document.write ('<TABLE width=590>');
    document.write ('<TBODY>');
    document.write ('<TR><TD>');
    document.write ('<FONT face="verdana" size=2>');
    document.write ('<B>Voc� est� usando um navegador que n�o d� suporte a Cookie?</B>');
    document.write ('<UL>Se o seu navegador n�o der suporte a Cookie, voc� poder� atualizar para um navegador mais recente.</UL>');
    document.write ('<B>O Cookie est� desativado?</B>');
    document.write ('<DL><DD>Se o Cookie estiver desativado, voc� dever� ativ�-lo para entrar na rede. As instru��es est�o a seguir.');
    document.write ('<P><B>Como ativar o Cookie</B></P>');
    document.write ('<P>Internet Explorer 5 ou superior</P>');
    document.write ('<OL>');
    document.write ('<LI>Clique em <B>Ferramentas</B> e em <B>Op��es da Internet</B>.</LI>');
    document.write ('<LI>Clique na guia <B>Seguran�a</B>.</LI>');
    document.write ('<LI>Clique no bot�o <B>N�vel personalizado</B>.</LI>');
    document.write ('<LI>Role para a se��o <B>Cookie</B>. Sob <B>Permitir cookies por sess�o(n�o armazenados)</B> e <B>Permitir cookies que est�o armazenados no computador</B>, selecione <B>Ativar</B>.</LI>');
    document.write ('<LI>Clique no bot�o <B>OK</B>. </LI>');
    document.write ('</OL>');
    document.write ('<P>Internet Explorer 4.x</P>');
    document.write ('<OL>');
    document.write ('<LI>Clique em <B>Exibir</B> e em <B>Op��es da Internet</B>.</LI>');
    document.write ('<LI>Clique na guia <B>Seguran�a</B>.</LI>');
    document.write ('<LI>Clique no bot�o <B>Configura��es</B>.</LI>');
    document.write ('<LI>Role para a se��o <B>Cookies</B>.</LI>');
    document.write ('<LI>Selecione <B>Permitir cookies por sess�o</B> e <B>Permitir cookies que est�o armazenados no computador</B>.</LI>');
    document.write ('<LI>Clique no bot�o <B>OK</B>.</LI>');
    document.write ('</OL>');
    document.write ('<P>Netscape 6</P>');
    document.write ('<OL>');
    document.write ('<LI>Clique em <B>Editar</B> e em <B>Prefer�ncias</B>.</LI>');
    document.write ('<LI>Clique em <B>Avan�ado</B>.</LI>');
    document.write ('<LI>Clique em <B>Cookies</B>.</LI>');
    document.write ('<LI>Habilite a op��o <B>Permitir todos os cookies</B>.</LI>');
    document.write ('<LI>Clique no bot�o <B>OK</B>. </LI></OL>');
    document.write ('<p>Outros navegadores</p>');
    document.write ('<UL>Para saber se o seu navegador d� suporte a Cookie e obter instru��es detalhadas sobre como ativar este recurso, consulte a Ajuda on-line para seu navegador.</UL>');
    document.write ('</DD></DL>');
    document.write ('<P></FONT>&nbsp;</P>');
    document.write ('</TD>');
    document.write ('</TR>');
    document.write ('</TBODY>');
    document.write ('</TABLE>');
    return (false);
  } else {
    // Apaga o cookie.
    document.cookie = 'aceita_cookie=sim; expires=Fri, 13-Apr-1970 00:00:00 GMT';
    return (true);
  }
}
if(testa_cookie1()){
  document.write('<form name="form1">');
  document.write(' <table width="790" height="430" border="0" cellpadding="0" cellspacing="0">');
  document.write('   <tr>');
  document.write('     <td width="203" height="437" valign="top" bgcolor="#7F7F7F"><img src="imagens/imagem3d_o.jpg" width="155" height="434"></td>');
  document.write('     <td width="279" valign="middle" bgcolor="#7F7F7F"><table width="100%" height="257" border="0" cellpadding="5" cellspacing="0">');
  document.write('         <tr> ');
  document.write('           <td height="140" align="center"><img src="imagens/consultor_o.gif" width="199" height="104"></td>');
  document.write('         </tr>');
	<?
	if (isset($DB_CONEXAO)){
  ?>
 //document.write('            <input name="DB_CONEXAO" type="hidden" value="<?=$DB_CONEXAO?>" size="40"> ');
  document.write('            <input name="user" type="hidden" value="<?=@$user?>" size="40"> ');
  document.write('            <input name="senh" type="hidden" value="<?=@$senh?>" size="40"> ');
  document.write('            <input name="port" type="hidden" value="<?=@$port?>"  size="40"> ');
  document.write('            <input name="servidor" type="hidden" value="<?=@$servidor?>" size="40"> ');
  document.write('         <tr> ');
  document.write("           <td>Servidor:<br><select   name='serv'  onchange='document.form1.submit();' ><option name='condicao2' value=''  >Selecione um servidor</option>");
																 <?
																 for($w=0;$w<count($DB_CONEXAO);$w++){
																 ?>
  document.write("         <option name='condicao2' value='<?=@$w?>'><?=$DB_CONEXAO[$w]["SERVIDOR"].":".$DB_CONEXAO[$w]["PORTA"] ?> </option>");
	                               <?
																 }
																 ?>
  document.write('          </select></td>');
  document.write('         </tr>');
  document.write('         <tr> ');
  document.write("          <td>Bases:<br> <select   name='base'><option name='condicao3' value=''>Selecione uma base</option>");
	<?
if (isset($servidor)&&$servidor!=""){
	?>
     	  document.form1.serv.value = '<?=@$serv?>';
				<?
		for ($w=0;$w<$numrows_bases;$w++){
			$datname = pg_result($result_bases,$w,"datname");
			?>
      document.write("         <option name='condicao3' value='<?=@$datname?>'><?=@$datname?> </option>");
			<?
		}		
}
	?>
  document.write('        </select></td> </tr>');
	<?
	}
	?>
  document.write('         <tr> ');
  document.write('           <td>Login:<br> <input name="login" type="text" size="40"> </td>');
  document.write('         </tr>');
  document.write('         <tr> ');
  document.write('           <td>Senha:<br> <input name="senha" onKeyUp="js_alapucha(event)" type="password" size="40"> </td>');
  document.write('         </tr>');
  document.write('         <tr> ');
  document.write('           <td height="33" align="right"><input name="Submit" type="button" class="botao" onClick="js_submeter()" value="Acessar"></td>');
  document.write('         </tr>');
  document.write('	  <tr> ');
  document.write('           <td height="33" align="center" valign="middle" style="font-family: Arial, Helvetica, sans-serif;font-size: 15px;font-weight: bold;;color:red" id="testaLogin">&nbsp;');
  document.write('		');
  document.write('		</td>');
  document.write('         </tr>');
  document.write('       </table></td>');
  document.write('     <td width="271" valign="top"><table width="100%" height="371" border="0" cellpadding="0" cellspacing="0">');
  document.write('         <tr>');
  document.write('           <td height="240" align="center" bgcolor="#0C2E60"><font size="4" color="white">');
  document.write('               Indique o nome do relat�rio a ser impresso:<br><br></font>');
  document.write('               <input name="arquivo" value="" type="text" size="40"><br><br><br>');
  document.write('               <input name="gera" value="Gera Relat�rio" type="button" onclick="js_mostrarelatorio()" >');
  document.write('           </td>');
  document.write('         </tr>');
  document.write('         <tr>');
  document.write('           <td height="200" align="center" ><a href="http://www.dbseller.com.br"><img border="none" src="imagens/logo_dbseller_o.gif" width="181" height="62"></a></td>');
  document.write('         </tr>');
  document.write('       </table></td>');
  document.write('   </tr>');
  document.write(' </table>');
  document.write(' <input type="hidden" name="DB_senha">');
  document.write('<input type="hidden" name="DB_login">');
  document.write('</form>');
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.botao {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #000000;
	background-color: #FF0000;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="(document.form1?document.form1.login.focus():'')">
</body>
</html>
