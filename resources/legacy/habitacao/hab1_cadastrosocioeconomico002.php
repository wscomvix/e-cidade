<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbViewAvaliacoes.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
               datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
unset($_SESSION["oCandidatoHabitacao"]);
?></head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
<?
      $clcriaabas  = new cl_criaabas;
      $clcriaabas->identifica  = array("grupoprograma"      => "Grupo/Programa",
                                       "composicaofamiliar" => "Composi��o Familiar",
                                       "cadsocioeconomico"  => "Cadastro S�cio-Econ�mico");
       
      $clcriaabas->title       = array("grupoprograma"      => "Grupo/Programa",
                                       "composicaofamiliar" => "Composi��o Familiar",
                                       "cadsocioeconomico"  => "Cadastro S�cio-Econ�mico");
        
       $clcriaabas->src        = array("grupoprograma"      => "hab1_cadastrosocioeconomico004.php?alteracao=1",
                                       "composicaofamiliar" => "hab1_composicaofamiliar001.php",
                                       "cadsocioeconomico"  => "hab1_cadastroavaliacao001.php"
                                      );
       
       $clcriaabas->sizecampo  = array("grupoprograma"      => "30",
                                       "composicaofamiliar" => "30",
                                       "cadsocioeconomico"  => "30");
       
       $clcriaabas->disabled   = array(
                                       "composicaofamiliar" => "true",
                                       "cadsocioeconomico"  => "true");
        
       $clcriaabas->cria_abas(); 
 ?>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>