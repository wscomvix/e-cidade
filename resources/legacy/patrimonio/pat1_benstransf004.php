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
include("classes/db_benstransf_classe.php");
include("classes/db_benstransfdes_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_benstransfcodigo_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clbenstransfcodigo = new cl_benstransfcodigo;
$clbenstransf = new cl_benstransf;
$clbenstransfdes = new cl_benstransfdes;
$cldb_usuarios = new cl_db_usuarios;
$cldb_depart = new cl_db_depart;
$cldb_depusu = new cl_db_depusu;

$db_opcao = 1;
$db_botao = true;

if(isset($transfdireta)){

    if($transfdireta == "true"){
        $t93_tipo = 2;
    }else{
        $t93_tipo = 1;
    }
}

if(isset($incluir)){
    $sqlerro=false;

    if ($db_param=="int"){
        $sql_depto_dest = $cldb_depart->sql_query_div(null,"descrdepto as depto_destino","","  db_depart.instit = ".db_getsession("DB_instit"));
    } else {
        $sql_depto_dest = $cldb_depart->sql_query_file(null,"descrdepto as depto_destino",""," and coddepto<>$t93_depart");
    }

    $dep_destino = $cldb_depart->sql_record($sql_depto_dest);
    if($cldb_depart->numrows>0){
        db_fieldsmemory($dep_destino,0);
    }else{
        $sqlerro=true;
        $erro_msg = _M("patrimonial.patrimonio.db_frmbenstransf.inclusao_nao_efetuada");
    }

    db_inicio_transacao();
    if($t93_data_dia!="" && $t93_data_mes!="" && $t93_data_ano!=""){
        $data = $t93_data_ano."-".$t93_data_mes."-".$t93_data_dia;
//    db_msgbox($data.'-------------'.date("Y-m-d"));
        if($data<date("Y-m-d")){
            $sqlerro = true;
            $erro_msg = _M("patrimonial.patrimonio.db_frmbenstransf.data_invalida");
            $clapolice->erro_campo = "t93_data_dia";
        }
    }
    if($sqlerro==false){
        $clbenstransf->incluir(null);
        $t93_codtran = $clbenstransf->t93_codtran;
        $t93_depart  = $clbenstransf->t93_depart;
        $t93_tipo    = $clbenstransf->t93_tipo;

        if($clbenstransf->erro_status==0){
            $sqlerro=true;
        }
        $erro_msg = $clbenstransf->erro_msg;
    }
    if($sqlerro==false){
        $clbenstransfdes->incluir($t93_codtran,$t94_depart);
        $erro_msg = $clbenstransfdes->erro_msg;
        if($clbenstransfdes->erro_status==0){
            $sqlerro=true;
        }
    }
    db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC>

<?
include("forms/db_frmbenstransf.php");
?>

</body>
</html>
<?
if(isset($incluir)){
    db_msgbox($erro_msg);
    if($sqlerro==true){
        if($clbenstransf->erro_campo!=""){
            echo "<script> document.form1.".$clbenstransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clbenstransf->erro_campo.".focus();</script>";
        };
    }else{
        //  db_msgbox($db_param);
        db_redireciona("pat1_benstransf005.php?liberaaba=true&chavepesquisa=$t93_codtran&t93_depart=$t93_depart&db_param=$db_param&transfdireta=$transfdireta");
    }
}
?>
