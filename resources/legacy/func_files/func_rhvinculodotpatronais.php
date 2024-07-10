<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhvinculodotpatronais_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhvinculodotpatronais = new cl_rhvinculodotpatronais;
$clrhvinculodotpatronais->rotulo->label();
?>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
    <center>
    <form name="form2" method="post" action="" class="container">
        <fieldset>
            <legend>Dados para Pesquisa</legend>
            <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
                <tr>
                    <td>�rg�o Dota��o Original:</td>
                    <td><? db_input('rh171_orgaoorig', 10, $Irh171_orgaoorig, true, "text", 4, "", "chave_rh171_orgaoorig"); ?></td>
                </tr>
                <tr>
                    <td>Unidade Dota��o Original:</td>
                    <td><? db_input('rh171_unidadeorig', 10, $Irh171_unidadeorig, true, "text", 4, "", "chave_rh171_unidadeorig"); ?></td>
                </tr>
                <tr>
                    <td>Projeto/Atividade Dota��o Original:</td>
                    <td><? db_input('rh171_projativorig', 10, $Irh171_projativorig, true, "text", 4, "", "chave_rh171_projativorig"); ?></td>
                </tr>
                <tr>
                    <td>Recurso Dota��o Original:</td>
                    <td><? db_input('rh171_recursoorig', 10, $Irh171_recursoorig, true, "text", 4, "", "chave_rh171_recursoorig"); ?></td>
                </tr>
                <tr>
                    <td>Programa Dota��o Original:</td>
                    <td><? db_input('rh171_programaorig', 10, $Irh171_programaorig, true, "text", 4, "", "chave_rh171_programaorig"); ?></td>
                </tr>
                <tr>
                    <td>Fun��o Dota��o Original:</td>
                    <td><? db_input('rh171_funcaoorig', 10, $Irh171_funcaoorig, true, "text", 4, "", "chave_rh171_funcaoorig"); ?></td>
                </tr>
                <tr>
                    <td>Subfuncao Dota��o Original:</td>
                    <td><? db_input('rh171_subfuncaoorig', 10, $Irh171_subfuncaoorig, true, "text", 4, "", "chave_rh171_subfuncaoorig"); ?></td>
                </tr>
            </table>
        </fieldset>
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhvinculodotpatronais.hide();">
    </form>
    <?
    if(!isset($pesquisa_chave)){
        $campos = " rh171_sequencial,
                    rh171_orgaoorig as dl_Orgao_Original,
                    rh171_orgaonov as dl_Orgao_Novo,
                    rh171_unidadeorig as dl_Unidade_Original,
                    rh171_unidadenov as dl_Unidade_Novo,
                    rh171_projativorig as dl_Proj_Ativ_Original,
                    rh171_projativnov as dl_Proj_Ativ_Novo,
                    rh171_recursoorig as dl_Recurso_Original,
                    rh171_recursonov as dl_Recurso_Novo,
                    rh171_programaorig as dl_Programa_Original,
                    rh171_programanov as dl_Programa_Novo,
                    rh171_funcaoorig as dl_Funcao_Original,
                    rh171_funcaonov as dl_Funcao_Novo,
                    rh171_subfuncaoorig as dl_Subfuncao_Original,
                    rh171_subfuncaonov as dl_Subfuncao_Novo,
                    rh171_mes,
                    CASE WHEN rh171_codtab = 1 THEN 'INSS' ELSE 'Previd�ncia' END as dl_Tabela";
        
        $sWhere = null;
	    $aWhere = array();

        if (isset($chave_rh171_orgaoorig) && !empty($chave_rh171_orgaoorig)) {
            $aWhere[] = " rh171_orgaoorig = {$chave_rh171_orgaoorig} ";
        }

        if (isset($chave_rh171_unidadeorig) && !empty($chave_rh171_unidadeorig)) {
            $aWhere[] = " rh171_unidadeorig = {$chave_rh171_unidadeorig} ";
        }

        if (isset($chave_rh171_projativorig) && !empty($chave_rh171_projativorig)) {
            $aWhere[] = " rh171_projativorig = {$chave_rh171_projativorig} ";
        }

        if (isset($chave_rh171_recursoorig) && !empty($chave_rh171_recursoorig)) {
            $aWhere[] = " rh171_recursoorig = {$chave_rh171_recursoorig} ";
        }

        if (isset($chave_rh171_programaorig) && !empty($chave_rh171_programaorig)) {
            $aWhere[] = " rh171_programaorig = {$chave_rh171_programaorig} ";
        }

        if (isset($chave_rh171_funcaoorig) && !empty($chave_rh171_funcaoorig)) {
            $aWhere[] = " rh171_funcaoorig = {$chave_rh171_funcaoorig} ";
        }

        if (isset($chave_rh171_subfuncaoorig) && !empty($chave_rh171_subfuncaoorig)) {
            $aWhere[] = " rh171_subfuncaoorig = {$chave_rh171_subfuncaoorig} ";
        }

        $aWhere[] = " rh171_anousu = ".db_getsession("DB_anousu");
        $aWhere[] = " rh171_instit = ".db_getsession("DB_instit");

        $sWhere = implode(' and ', $aWhere);

        $sql = $clrhvinculodotpatronais->sql_query(null, $campos, "rh171_sequencial, rh171_mes", $sWhere);
        $repassa = array();
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

    } else {
        
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
            $result = $clrhvinculodotpatronais->sql_record($clrhvinculodotpatronais->sql_query($pesquisa_chave));
            if($clrhvinculodotpatronais->numrows!=0){
                db_fieldsmemory($result,0);
                echo "<script>".$funcao_js."('$rh171_sequencial',false);</script>";
            } else {
	            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
            }
        } else {
	        echo "<script>".$funcao_js."('',false);</script>";
        }
    }
      ?>
</center>
</body>
</html>