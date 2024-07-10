<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_orcreserva_classe.php");
require_once("classes/db_orcreservasup_classe.php");
require_once("classes/db_orcsuplem_classe.php");
require_once("classes/db_orcsuplemval_classe.php");
require_once("classes/db_orcdotacao_classe.php");   // instancia da classe dota��o
require_once("classes/db_orcreceita_classe.php"); // receita
require_once("classes/db_orcorgao_classe.php"); // receita
require_once("classes/db_orcparametro_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcsuplemval           = new cl_orcsuplemval;
$clorcdotacao             = new cl_orcdotacao;  // instancia da classe dota��o
$clorcsuplem              = new cl_orcsuplem;
$clorcorgao               = new cl_orcorgao;
$clorcreserva             = new cl_orcreserva;
$clorcreservasup          = new cl_orcreservasup;
$clrotulo                 = new rotulocampo;
$clorcparametro           = new cl_orcparametro();

$clrotulo->label("o58_concarpeculiar");
$clrotulo->label("c58_descr");

$clorcsuplem->rotulo->label();
$clorcsuplemval->rotulo->label();
$clorcorgao->rotulo->label();
$clorcdotacao->rotulo->label();

$op =1 ;
$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu");

/*OC10197*/
$result=$clorcparametro->sql_record($clorcparametro->sql_query_file($anousu,"o50_controlafote1017,o50_controlafote10011006"));
if ($clorcparametro->numrows > 0 ){
    db_fieldsmemory($result,0);
}
$limpa_dados = false;
//------------------------------------------
if (isset($pesquisa_dot) && $o47_coddot!=""){
    // foi clicado no bot�o "pesquisa" da tela
    $res = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"),$o47_coddot));
    if ($clorcdotacao->numrows > 0 ){
        db_fieldsmemory($res,0); // deve existir 1 registro
        $resdot= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$anousu.'-01-01',$anousu.'-12-31');
        db_fieldsmemory($resdot,0);
    }
}
//------------------------------------------

if(isset($incluir)){
    // pressionado botao incluir na tela
    $limpa_dados = true;
    $sqlerro = false;

    db_inicio_transacao();

    // verifica se tem saldo para reservar  - saldo atual_menos_reservado = saldo disponivel liquido
    $dtini  = $anousu.'-01-01'; // pega o saldo desdo inicio do ano, pra n�o permitir voltar a data e reduzir
    $dtfin  = $anousu.'-12-31';
    $result= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$dtini,$dtfin) ;
    db_fieldsmemory($result,0);
    if ($atual_menos_reservado < (abs($o47_valor))){
        $sqlerro =true;
        db_msgbox("Dota��o $o47_coddot sem saldo ! (Saldo $atual_menos_reservado) ");
    }

    // lan�a reserva
    $clorcreserva->o80_anousu = $anousu;
    $clorcreserva->o80_coddot = $o47_coddot;
    $clorcreserva->o80_dtlanc = date("Y-m-d", db_getsession('DB_datausu'));
    $clorcreserva->o80_dtini  = date("Y-m-d", db_getsession('DB_datausu'));
    $clorcreserva->o80_dtfim  = db_getsession('DB_anousu')."-12-31";
    $clorcreserva->o80_valor  = $o47_valor;
    $clorcreserva->o80_descr  = "suplementacao ";
    $clorcreserva->o80_justificativa  = "suplementacao ";
    if ($sqlerro==false){
        $clorcreserva->incluir("");
        if ($clorcreserva->erro_status == 0 ){
            $sqlerro = true;
            db_msgbox($clorcreserva->erro_msg);
        }
    }
    //
    $clorcreservasup->o81_codres = $clorcreserva->o80_codres;
    $clorcreservasup->o81_codsup = $o46_codsup;
    if ($sqlerro == false){
        $clorcreservasup->incluir($clorcreservasup->o81_codres);
        if ($clorcreservasup->erro_status == 0 ){
            $sqlerro = true;
            db_msgbox($clorcreservasup->erro_msg);
        }
    }
    $clorcsuplemval->o47_valor          = (abs($o47_valor))*-1;
    $clorcsuplemval->o47_anousu         = db_getsession("DB_anousu");
    $clorcsuplemval->o47_concarpeculiar = "{$o58_concarpeculiar}";
    /*OC5813*/
    //valida se a dota��o j� foi usada numa operacao contr�ria

    $sSuplementacao = $clorcsuplemval->sql_record("select * from orcsuplemval join orcsuplem on o47_codsup=o46_codsup where o46_codlei = {$o39_codproj} and o47_coddot = {$o47_coddot} ");
    if(pg_num_rows($sSuplementacao)>0){
        $aSuple = db_utils::getCollectionByRecord($sSuplementacao);
        foreach($aSuple as $oSupl){
            //existe uma suplementacao que agora est� sendo reduzida
            if($oSupl->o47_valor > 0){
                $sqlerro = true;
                db_msgbox('Usu�rio, inclus�o abortada. Esta dota��o j� foi inserida em outra suplementa��o neste mesmo projeto!');
                $limpa_dados = false;
                break;
            }else{
                $sqlerro = false;
            }
        }
    }else{
        $sqlerro = false;
    }
    /*FIM OC5813*/
    /*OC5815*/
    /*todo estrutural anterior � fonte de recurso deve ser id�ntico para a dota��o que suplementa e para a dota��o que reduz*/

    if($tiposup=='1017'){
        $sSqlEstruturalDotacaoEnviada = "SELECT fc_estruturaldotacao(".db_getsession('DB_anousu').",o58_coddot) AS dl_estrutural
            FROM orcdotacao d
            INNER JOIN orcprojativ p ON p.o55_anousu = ".db_getsession('DB_anousu')."
            AND p.o55_projativ = d.o58_projativ
            INNER JOIN orcelemento e ON e.o56_codele = d.o58_codele
            AND o56_anousu = o58_anousu
            WHERE o58_anousu=".db_getsession('DB_anousu')."
              AND o58_coddot = {$o47_coddot}";

        $oEstruturalDotacaoEnviada = db_utils::fieldsMemory(db_query($sSqlEstruturalDotacaoEnviada));

        $sSqlestrututural = "SELECT fc_estruturaldotacao(".db_getsession('DB_anousu').",o58_coddot) AS dl_estrutural,
              o56_elemento,
              o55_descr::text,
              o56_descr,
              o58_coddot,
              o58_instit,
              o46_codlei,
              o46_codsup,
              o46_tiposup
              FROM orcsuplemval
              JOIN orcdotacao ON o47_anousu=o58_anousu
              AND o47_coddot=o58_coddot
              JOIN orcsuplem ON o47_codsup=o46_codsup
              INNER JOIN orcprojativ ON o55_anousu = ".db_getsession('DB_anousu')."
              AND o55_projativ = o58_projativ
              INNER JOIN orcelemento e ON o56_codele = o58_codele
              AND o56_anousu = o58_anousu
              WHERE o46_codlei = {$o39_codproj}
              AND o46_codsup = {$o46_codsup}
              AND o46_tiposup = {$tiposup}
              AND o58_anousu = ".db_getsession('DB_anousu')."
              ";

        if(pg_num_rows(db_query($sSqlestrututural))>0){
            $oEstruturalSupl = db_query($sSqlestrututural);
            $oEstruturalSupl = db_utils::fieldsMemory($oEstruturalSupl);
            if(!(substr($oEstruturalDotacaoEnviada->dl_estrutural, 0,36) ==  substr($oEstruturalSupl->dl_estrutural, 0,36) && substr($oEstruturalDotacaoEnviada->dl_estrutural, 37,4) !=  substr($oEstruturalSupl->dl_estrutural, 37,4)) && db_getsession('DB_anousu') < 2023){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;

            }

            if(!(substr($oEstruturalDotacaoEnviada->dl_estrutural, 0,36) ==  substr($oEstruturalSupl->dl_estrutural, 0,36) && substr($oEstruturalDotacaoEnviada->dl_estrutural, 37,8) !=  substr($oEstruturalSupl->dl_estrutural, 37,8)) &&  db_getsession('DB_anousu') > 2022){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;

            }

            if($o50_controlafote1017 == 't' && db_getsession('DB_anousu') < 2023) {
                /**
                 * OC 9112 - Inicio
                 * OC 10930 - Modifica valida��o para verificar apenas 2 �ltimos d�gitos das fontes
                 */

                /*valida fonte 101 e 102*/
                $validacao = array(01, 02);
                if (substr($oEstruturalSupl->dl_estrutural, 39, 2) == 00 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 39, 2), $validacao)) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 101*/
                if (substr($oEstruturalSupl->dl_estrutural, 39, 2) == 01 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 39, 2) != 00) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 102*/
                if (substr($oEstruturalSupl->dl_estrutural, 39, 2) == 02 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 39, 2) != 00) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 118*/
                if (substr($oEstruturalSupl->dl_estrutural, 39, 2) == 18 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 39, 2) != 19) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 119*/
                if (substr($oEstruturalSupl->dl_estrutural, 39, 2) == 19 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 39, 2) != 18) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /**
                 * OC 9112 - Fim
                 */
            }

            if($o50_controlafote1017 == 't' && db_getsession('DB_anousu') > 2022) {
                /**
                 * OC 9112 - Inicio
                 * OC 10930 - Modifica valida��o para verificar apenas 2 �ltimos d�gitos das fontes
                 */

                /*valida fonte 15000001 e 15000002*/
                $validacao = array(5000001, 5000002);
                if (substr($oEstruturalSupl->dl_estrutural, 38, 7) == 5000000 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 7), $validacao)) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 15000001*/
                if (substr($oEstruturalSupl->dl_estrutural, 38, 7) == 5000001 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 7) != 5000000) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 15000002*/
                if (substr($oEstruturalSupl->dl_estrutural, 38, 7) == 5000002 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 7) != 5000000) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 15400007*/
                
                if (substr($oEstruturalSupl->dl_estrutural, 38, 7) == 5400007 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 7) != 5400000) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /*valida fonte 15400000*/
                if (substr($oEstruturalSupl->dl_estrutural, 38, 7) == 5400000 && substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 7) != 5400007) {
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }

                /**
                 * OC 9112 - Fim
                 */
            }
        }
    }

    if($tiposup == '1001' || $tiposup == '1006'){
        
        $sSqlEstruturalDotacaoEnviada = "SELECT fc_estruturaldotacao(".db_getsession('DB_anousu').",o58_coddot) AS dl_estrutural
            FROM orcdotacao d
            INNER JOIN orcprojativ p ON p.o55_anousu = ".db_getsession('DB_anousu')."
            AND p.o55_projativ = d.o58_projativ
            INNER JOIN orcelemento e ON e.o56_codele = d.o58_codele
            AND o56_anousu = o58_anousu
            WHERE o58_anousu=".db_getsession('DB_anousu')."
              AND o58_coddot = {$o47_coddot}";

        $oEstruturalDotacaoEnviada = db_utils::fieldsMemory(db_query($sSqlEstruturalDotacaoEnviada));

        $sSqlestrututural = "SELECT fc_estruturaldotacao(".db_getsession('DB_anousu').",o58_coddot) AS dl_estrutural,
              o56_elemento,
              o55_descr::text,
              o56_descr,
              o58_coddot,
              o58_instit,
              o46_codlei,
              o46_codsup,
              o46_tiposup
              FROM orcsuplemval
              JOIN orcdotacao ON o47_anousu=o58_anousu
              AND o47_coddot=o58_coddot
              JOIN orcsuplem ON o47_codsup=o46_codsup
              INNER JOIN orcprojativ ON o55_anousu = ".db_getsession('DB_anousu')."
              AND o55_projativ = o58_projativ
              INNER JOIN orcelemento e ON o56_codele = o58_codele
              AND o56_anousu = o58_anousu
              WHERE o46_codlei = {$o39_codproj}
              AND o46_codsup = {$o46_codsup}
              AND o46_tiposup = {$tiposup}
              AND o58_anousu = ".db_getsession('DB_anousu')."
              ";

        if(pg_num_rows(db_query($sSqlestrututural))>0) {
            $oEstruturalSupl = db_query($sSqlestrututural);

            $oEstruturalSupl = db_utils::fieldsMemory($oEstruturalSupl);
//            if (!(substr($oEstruturalDotacaoEnviada->dl_estrutural, 0, 36) == substr($oEstruturalSupl->dl_estrutural, 0, 36) && substr($oEstruturalDotacaoEnviada->dl_estrutural, 37, 4) != substr($oEstruturalSupl->dl_estrutural, 37, 4))) {
//                $sqlerro = true;
//                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
//                $limpa_dados = false;
//
//            }
        }

        if($o50_controlafote10011006 == 't' && db_getsession('DB_anousu') < 2023){
            /*valida fonte 100*/
            $validacao = array(100, 101, 102);

            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 100 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /*valida fonte 101*/
            $validacao = array(100, 101);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 101 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /*valida fonte 102*/
            $validacao = array(100,102);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 102 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /*valida fonte 118*/
            $validacao = array(118, 119);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 118 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /*valida fonte 119*/
            $validacao = array(118, 119);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 119 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            /* Adicionando a valida��o da fonte 166 - OC17881 */
            $validacao = array(166, 167);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 166 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            /* Adicionando a valida��o da fonte 167 - OC17881 */
            $validacao = array(166, 167);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 167 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /*valida diferente*/
            /* Adicionando a valida��o das fontes com primeiro digito separado - OC17881 */
            $validacao = array(100, 101, 102, 118, 119, 166, 167, 200, 201, 202, 218, 219, 266, 267);
            if (!in_array(substr($oEstruturalSupl->dl_estrutural, 38, 3), $validacao)) {
                if (substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3) != substr($oEstruturalSupl->dl_estrutural, 38, 3)){
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
                }
            }

            /* Adicionando a valida��o da fonte 200 - OC17881 */
            $validacao = array(200, 201, 202);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 200 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /* Adicionando a valida��o da fonte 201 - OC17881 */
            $validacao = array(200, 201);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 201 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /* Adicionando a valida��o da fonte 202 - OC17881 */
            $validacao = array(200, 202);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 202 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /* Adicionando a valida��o da fonte 218 - OC17881 */
            $validacao = array(218, 219);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 218 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        
            /* Adicionando a valida��o da fonte 219 - OC17881 */
            $validacao = array(218, 219);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 219 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            /* Adicionando a valida��o da fonte 266 - OC17881 */
            $validacao = array(266, 267);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 266 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            /* Adicionando a valida��o da fonte 267 - OC17881 */
            $validacao = array(266, 267);
            if (substr($oEstruturalSupl->dl_estrutural, 38, 3) == 267 && !in_array(substr($oEstruturalDotacaoEnviada->dl_estrutural, 38, 3), $validacao)) {
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        }

        if($o50_controlafote10011006 == 't' && db_getsession('DB_anousu') > 2022){
                   
            /*valida diferente*/
            /* Adicionando a valida��o das fontes com primeiro digito separado - OC17881 */
            // $validacao = array(100, 101, 102, 118, 119, 166, 167, 200, 201, 202, 218, 219, 266, 267);
            // echo substr($oEstruturalDotacaoEnviada->dl_estrutural, 37, 7)." <br>".substr($oEstruturalSupl->dl_estrutural, 37, 7)." <br>";
            // echo substr($oEstruturalDotacaoEnviada->dl_estrutural, 1, 43)." <br>".substr($oEstruturalSupl->dl_estrutural, 1, 43);exit;
            if (substr($oEstruturalDotacaoEnviada->dl_estrutural, 37, 7) != substr($oEstruturalSupl->dl_estrutural, 37, 7) || (substr($oEstruturalDotacaoEnviada->dl_estrutural, 0, 43) == substr($oEstruturalSupl->dl_estrutural, 0, 43))){
                    $sqlerro = true;
                    db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                    $limpa_dados = false;
            }
        }
    }

    if($tiposup == 1020 || $tiposup == 1021 || $tiposup == 1022){
        $sSqlEstruturalDotacaoEnviada = "SELECT fc_estruturaldotacao(".db_getsession('DB_anousu').",o58_coddot) AS dl_estrutural
            FROM orcdotacao d
            INNER JOIN orcprojativ p ON p.o55_anousu = ".db_getsession('DB_anousu')."
            AND p.o55_projativ = d.o58_projativ
            INNER JOIN orcelemento e ON e.o56_codele = d.o58_codele
            AND o56_anousu = o58_anousu
            WHERE o58_anousu=".db_getsession('DB_anousu')."
              AND o58_coddot = {$o47_coddot}";

        $oEstruturalDotacaoEnviada = db_utils::fieldsMemory(db_query($sSqlEstruturalDotacaoEnviada));

        $sSqlestrututural = "SELECT fc_estruturaldotacao(".db_getsession('DB_anousu').",o58_coddot) AS dl_estrutural,
              o56_elemento,
              o55_descr::text,
              o56_descr,
              o58_coddot,
              o58_instit,
              o46_codlei,
              o46_codsup,
              o46_tiposup,
	          o47_valor
              FROM orcsuplemval
              JOIN orcdotacao ON o47_anousu=o58_anousu
              AND o47_coddot=o58_coddot
              JOIN orcsuplem ON o47_codsup=o46_codsup
              INNER JOIN orcprojativ ON o55_anousu = ".db_getsession('DB_anousu')."
              AND o55_projativ = o58_projativ
              INNER JOIN orcelemento e ON o56_codele = o58_codele
              AND o56_anousu = o58_anousu
              WHERE o46_codlei = {$o39_codproj}
              AND o46_codsup = {$o46_codsup}
              AND o46_tiposup = {$tiposup}
              AND o58_anousu = ".db_getsession('DB_anousu')."
              order by o47_valor desc
              ";

        if(pg_num_rows(db_query($sSqlestrututural))>0 && db_getsession('DB_anousu') < 2023){
            $oEstruturalSupl = db_query($sSqlestrututural);
            $oEstruturalSupl = db_utils::fieldsMemory($oEstruturalSupl);

            if( $tiposup == 1020 && (substr($oEstruturalDotacaoEnviada->dl_estrutural,0,28).substr($oEstruturalDotacaoEnviada->dl_estrutural,30,11) != (substr($oEstruturalSupl->dl_estrutural,0,28).substr($oEstruturalSupl->dl_estrutural,30,11)))){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            if( $tiposup == 1021 && (substr($oEstruturalDotacaoEnviada->dl_estrutural,0,26).substr($oEstruturalDotacaoEnviada->dl_estrutural,28,13) != (substr($oEstruturalSupl->dl_estrutural,0,26).substr($oEstruturalSupl->dl_estrutural,28,13)))){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            if( $tiposup == 1022 && (substr($oEstruturalDotacaoEnviada->dl_estrutural,0,5).substr($oEstruturalDotacaoEnviada->dl_estrutural,13,29) != (substr($oEstruturalSupl->dl_estrutural,0,5).substr($oEstruturalSupl->dl_estrutural,13,29)))){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        }

        if(pg_num_rows(db_query($sSqlestrututural))>0 && db_getsession('DB_anousu') > 2022){         
            $oEstruturalSupl = db_query($sSqlestrututural);
            $oEstruturalSupl = db_utils::fieldsMemory($oEstruturalSupl);
            
            if( $tiposup == 1020){
                if((substr($oEstruturalDotacaoEnviada->dl_estrutural,0,44) == (substr($oEstruturalSupl->dl_estrutural,0,44))) ||
                  (substr($oEstruturalDotacaoEnviada->dl_estrutural,0,28).substr($oEstruturalDotacaoEnviada->dl_estrutural,30,14) != (substr($oEstruturalSupl->dl_estrutural,0,28).substr($oEstruturalSupl->dl_estrutural,30,14)))){
                   $sql = "select * from orcsuplemval where o47_codsup = {$o46_codsup}";
                   if (pg_num_rows(db_query($sql)) >= 1) {
                        db_msgbox("Usu�rio: A dota��o reduzida � incompat�vel com a dota��o suplementada.");
                        $sqlerro = true;
                        $limpa_dados = false;
                        echo "<script>
                                parent.mo_camada('reducao');
                              </script>";
                              
                    }
                }
            }
            
            if( $tiposup == 1021 && (substr($oEstruturalDotacaoEnviada->dl_estrutural,0,26).substr($oEstruturalDotacaoEnviada->dl_estrutural,28,16) != (substr($oEstruturalSupl->dl_estrutural,0,26).substr($oEstruturalSupl->dl_estrutural,28,16)))){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }

            if( $tiposup == 1022 && (substr($oEstruturalDotacaoEnviada->dl_estrutural,0,5).substr($oEstruturalDotacaoEnviada->dl_estrutural,13,31) != (substr($oEstruturalSupl->dl_estrutural,0,5).substr($oEstruturalSupl->dl_estrutural,13,31)))){
                $sqlerro = true;
                db_msgbox("Usu�rio, inclus�o abortada. Dota��o incompat�vel com o tipo de suplementa��o utilizada");
                $limpa_dados = false;
            }
        }
    }
    if ($sqlerro == false ) {
        $clorcsuplemval->incluir($o46_codsup,db_getsession("DB_anousu"),$o47_coddot);
        if ($clorcsuplemval->erro_status == 0){
            $sqlerro = true;
            db_msgbox($clorcsuplemval->erro_msg);
            $limpa_dados = false;
        }
    }
    db_fim_transacao($sqlerro);

}elseif(isset($opcao) && $opcao=="excluir" ){
    $limpa_dados = true;
    // clicou no exlcuir, j� exlcui direto, nem confirma nada
    db_inicio_transacao();
    $sqlerro     = false;
    // procura reserva
    $res = $clorcreservasup->sql_record($clorcreservasup->sql_query(null,"o81_codres",null,"o81_codsup = $o46_codsup and o80_coddot=$o47_coddot "));
    if ($clorcreservasup->numrows > 0){
        db_fieldsmemory($res,0);
    }
    $clorcreservasup->excluir($o81_codres);
    if ($clorcreservasup->erro_status == 0){
        $sqlerro = true;
        db_msgbox($clorcreservasup->erro_msg);
    }
    $clorcreserva->excluir($o81_codres);
    if ($clorcreserva->erro_status == 0){
        $sqlerro = true;
        db_msgbox($clorcreserva->erro_msg);
    }
    $clorcsuplemval->excluir($o46_codsup,$anousu,$o47_coddot);
    if ($clorcsuplemval->erro_status == 0){
        $sqlerro = true;
        $limpa_dados = false;
        db_msgbox($clorcsuplemval->erro_msg);
    }
    // $sqlerro = true;
    db_msgbox($clorcsuplemval->erro_msg);
    db_fim_transacao($sqlerro);

}
if ($limpa_dados ==true){
    $o47_coddot = "";
    $o58_orgao  = "";
    $o40_descr  = "";
    $o56_elemento ="";
    $o56_descr    ="";
    $o58_codigo   ="";
    $o15_descr    ="";
    $o47_valor    ="";
    $atual_menos_reservado = "";
}
// --------------------------------------
// calcula total das redu��es
$res = $clorcsuplemval->sql_record("select sum(o47_valor) as soma_reduz
                                    from orcsuplemval where o47_codsup=$o46_codsup and o47_valor < 0");
if ($clorcsuplemval->numrows > 0 ){
    db_fieldsmemory($res,0,true);
}

// --------------------------------------

// --------------------------------------
// calcula total das redu��es
$oSuplementacao = new Suplementacao($o46_codsup);
$soma_suplem    = $oSuplementacao->getvalorSuplementacao();
// 

$totalSuplemRec = db_formatar($soma_suplem - abs($soma_reduz),"f");
$soma_reduz = db_formatar($soma_reduz,"f");
$soma_suplem = db_formatar($soma_suplem,"f");

?>
    <html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="480" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
                <center>
                    <?
                    include("forms/db_frmorcsuplemval_reducao.php");
                    ?>
                </center>
            </td>
        </tr>
    </table>
    </body>
    </html>
<?

if(isset($incluir) || isset($alterar) || isset($excluir)){
    
    if($clorcsuplemval->erro_status=="0"){
        $clorcsuplemval->erro(true,false);
        $db_botao=true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if($clorcsuplemval->erro_campo!=""){
            echo "<script> document.form1.".$clorcsuplemval->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clorcsuplemval->erro_campo.".focus();</script>";
        };
    }else{
        $clorcsuplemval->erro(true,false);
    };
};

?>
