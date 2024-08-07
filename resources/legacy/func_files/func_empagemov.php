<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_empagemov_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempagemov = new cl_empagemov;
$clempagemov->rotulo->label("e81_codmov");
$clempagemov->rotulo->label("e81_numemp");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te81_codmov?>">
              <?=$Le81_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e81_codmov",6,$Ie81_codmov,true,"text",4,"","chave_e81_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te81_numemp?>">
              <?=$Le81_numemp?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e81_numemp",10,$Ie81_numemp,true,"text",4,"","chave_e81_numemp");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empagemov.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
        if (!isset($pesquisa_chave)) {

          if (isset($campos)==false) {
             if (file_exists("funcoes/db_func_empagemov.php") == true ) {
               include("funcoes/db_func_empagemov.php");
             } else {
               $campos = "empagemov.*";
             }
          }

          if (isset($chave_e81_codmov) && (trim($chave_e81_codmov)!="") ) {
  	         $sql = $clempagemov->sql_query( null,
                                             $campos,
                                             "e81_codmov", 
                                             " e80_instit = " . db_getsession("DB_instit") . " and e81_codmov = $chave_e81_codmov" );
          } else if (isset($chave_e81_numemp) && (trim($chave_e81_numemp)!="") ) {
  	         $sql = $clempagemov->sql_query( null,
                                             $campos,
                                             "e81_numemp",
                                             " e80_instit = " . db_getsession("DB_instit") . " and e81_numemp like '$chave_e81_numemp%' " );
          } else if (isset($chave_empenho_conferido)) {
            $sql = $clempagemov->sql_query_empenho_conferido( null, 
                                                              $campos, 
                                                              "e81_codmov",
                                                              " e45_conferido is not null and e80_instit = " . db_getsession("DB_instit")
                                                              . " and not exists(select e170_sequencial from empprestarecibo where e170_emppresta = e45_sequencial)"
                                                              . " and e81_cancelado is null"
                                                              . " AND (
                                                                exists 
                                                                    (SELECT 1 FROM conlancamemp 
                                                                        INNER JOIN conlancamdoc ON c71_codlan = c75_codlan
                                                                        INNER JOIN conlancam ON c70_codlan = c75_codlan
                                                                        INNER JOIN empelemento ON e64_numemp = c75_numemp
                                                                        WHERE c71_coddoc = 414
                                                                          AND e64_numemp = e81_numemp
                                                                       GROUP BY empelemento.e64_vlrpag
                                                                       HAVING sum(c70_valor) < sum(e64_vlrpag))
                                                                  OR
                                                                      EXISTS (SELECT 1 FROM empprestaitem
                                                                        WHERE e46_numemp = e45_numemp AND e46_valor = 0)
                                                                  )" 
                                                              );
          } else {
             $sql = $clempagemov->sql_query(null, $campos, "e81_codmov"," e80_instit = " . db_getsession("DB_instit"));
          }

          db_lovrot($sql, 15, "()", "", $funcao_js);
        } else {
          if ($pesquisa_chave!=null && $pesquisa_chave!="") {
            $result = $clempagemov->sql_record($clempagemov->sql_query($pesquisa_chave));

            if ($clempagemov->numrows != 0) {
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$e81_numemp',false);</script>";
            } else {
  	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
            }
          } else {
  	        echo "<script>".$funcao_js."('',false);</script>";
          }
        }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
