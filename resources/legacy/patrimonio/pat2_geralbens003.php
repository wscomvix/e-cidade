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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_bensbaix_classe.php");
require_once("classes/db_cfpatriplaca_classe.php");
require_once("classes/db_benscadcedente_classe.php");
$clbenscadcedente = new cl_benscadcedente();
$clbens = new cl_bens;
$clbensbaix = new cl_bensbaix;
$clcfpatriplaca = new cl_cfpatriplaca;

$clbens->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('descrdepto');
$clrotulo->label('t64_descr');
$clrotulo->label('t64_class');
$clrotulo->label('t53_ntfisc');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sSqlPatriPlaca = $clcfpatriplaca->sql_query_file(db_getsession("DB_instit"));
$res_cfpatriplaca = $clcfpatriplaca->sql_record($sSqlPatriPlaca);
if ($clcfpatriplaca->numrows > 0) {
    db_fieldsmemory($res_cfpatriplaca, 0);
} else {

    $sMsg = _M('patrimonial.patrimonio.pat2_geralbens002.nao_existem_placas_para_instituicao');
    db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
    exit;
}

$where_instit = " 1=1 ";
$ordem = "";
$info .= "Relat�rio Geral de Bens;";
$info .= "Ordenado por ";
$quebra = "";
if ($ordenar == "depart") {
    $ordem = "t52_depart";
    $info .= "Departamento;";
} else if ($ordenar == "placa") {
    if ($t07_confplaca == 1 or $t07_confplaca == 4) {
        $ordem = "cast( regexp_replace( coalesce(nullif(trim(t52_ident),''), '0') , '[^0-9.,-]' , '', 'g') as numeric)";
    } else {
        $ordem = "t52_ident";
    }
    $info .= "Placa;";
} else if ($ordenar == "bem") {
    $ordem = "t52_bem";
    $info .= "Bem;";
} else if ($ordenar == "classi") {
    $ordem = "t64_class";
    $info .= "Classifica��o;";
} else if ($ordenar == "data") {
    $ordem = "t52_dtaqu";
    $info .= "Data de Aquisi��o;";

} else if ($ordenar == "orgao") {
    $ordem = "db01_orgao,db01_unidade,t52_depart, t52_ident";
    $info .= "�rg�o;";
} else if ($ordenar == "unidade") {
    $ordem = "db01_orgao,db01_unidade,t52_depart, t52_ident";
    $info .= "Unidade;";
} else if ($ordenar == "descricao") {
    $ordem = "t52_descr";
    $info .= "Descri��o do Bem;";
}

if ($bens_convenio == "T") {
    $head7 = "Bens: Todos";
} else if ($bens_convenio == "N") {
    $head7 = "Bens: Nenhum Conv�nio";
} else if ($bens_convenio == "S") {
    $head7 = "Bens: Com Conv�nio";
}

if($cgmFornecedor){
  if(!$head5){
    $head6 = 'CGM: '.utf8_decode($nomeFornecedor);
  }else $head5 = 'CGM: '.utf8_decode($nomeFornecedor);
}

if (isset($quebra_por) && $quebra_por != "" && $imp_classi == "S") {

    if ($quebra_por == 2) {
        if ($ordenar == "depart") {
            $ordem .= ",t33_divisao";
        } else {
            $ordem .= "t52_depart,t33_divisao," . $ordem;
        }

    } else if ($quebra_por == 3) {
        $ordem = $ordem == "" ? "t64_class" : "t64_class," . $ordem;
    }

}

if (isset($coddepart) and $coddepart != 0 && $coddepart != "") {
    $where_instit .= " and t52_depart=$coddepart ";
}

if (isset($divisao) && trim($divisao) != "" && $divisao != 0) {
    if ($where_instit != "") {
        $where_instit .= " and t33_divisao = $divisao ";
    } else {
        $where_instit .= " t33_divisao = $divisao ";
    }

}
if(isset($descr)){
    $where_instit .= " and t52_descr ilike '$descr' ";
}

$flag_datas = 0;
if (isset($data_inicial) && trim(@$data_inicial) != "" && isset($data_final) && trim(@$data_final) != "") {
    $flag_datas = 1;
} else if (isset($data_inicial) && trim(@$data_inicial) != "") {
    $flag_datas = 2;
} else if (isset($data_final) && trim(@$data_final) != "") {
    $flag_datas = 3;
}

if (($flag_datas == 1 || $flag_datas == 2 || $flag_datas == 3) && $where_instit != "") {
    $where_instit .= " and ";
}

if ($flag_datas == 1) {
    $where_instit .= "t52_dtaqu between '$data_inicial' and '$data_final'";
    $info .= "De " . db_formatar($data_inicial, "d") . " a " . db_formatar($data_final, "d").";";
}

if ($flag_datas == 2) {
    $where_instit .= "t52_dtaqu >= '$data_inicial'";
    $info .= "Aquisi��o a partir de " . db_formatar($data_inicial, "d").";";
}

if ($flag_datas == 3) {
    $where_instit .= "t52_dtaqu <= '$data_final'";
    $info .= "Aquisi��o at� " . db_formatar($data_final, "d").";";
}


/** nota fiscal */
if (isset($Enumero) && !empty($Enumero)) {
    // filtrar pela nota
    $where_instit .= " AND (t53_ntfisc = '{$Enumero}') ";
    $info .= "Nota Fiscal: {$Enumero};";
}


$flag_forn = false;
$flag_classi = false;

if ($imp_forn == "S") {
    $flag_forn = true;
}

if ($imp_classi == "S") {
    $flag_classi = true;
}

if ($where_instit != "") {
    $where_instit .= " and ";
}

$where_instit .= "t52_instit = " . db_getsession("DB_instit");


if ($t07_confplaca == 1 or $t07_confplaca == 4) {
    $campos = "t53_ntfisc, t52_bem, t52_descr, round(t52_valaqu,2) as t52_valaqu, t52_dtaqu, cast( regexp_replace( coalesce(nullif(trim(t52_ident),''), '0') , '[^0-9.,-]' , '', 'g') as numeric) as t52_ident,
  					 t52_depart, descrdepto, t52_numcgm, z01_nome, t52_obs, t64_class, t64_descr, t33_divisao, departdiv.t30_descr,
  					 (select count(*)
                     from bensplaca
                          inner join bensplacaimpressa on bensplacaimpressa.t73_bensplaca = bensplaca.t41_codigo
                    where t41_bem = t52_bem) as totaletiquetas";
} else {
    $campos = "t53_ntfisc, t52_bem, t52_descr, round(t52_valaqu,2) as t52_valaqu, t52_dtaqu, t52_ident, t52_depart, descrdepto, t52_numcgm, z01_nome, t52_obs,
  					 t64_class, t64_descr, t33_divisao, departdiv.t30_descr,
  					 (select count(*)
                     from bensplaca
                          inner join bensplacaimpressa on bensplacaimpressa.t73_bensplaca = bensplaca.t41_codigo
                    where t41_bem = t52_bem) as totaletiquetas";
}

if (isset($orgaos) && isset($unidades) && isset($departamentos)) {

    $campos .= ",o40_descr,o40_orgao,o41_unidade,o41_descr,db01_orgao,db01_unidade";
    $campos = "distinct " . $campos;
    if ($orgaos != "") {
        $where_instit .= " and db01_orgao in $orgaos ";
    }
    if ($unidades != "") {
        $where_instit .= " and db01_unidade in $unidades ";
    }
    if ($departamentos != "") {
        $where_instit .= " and db01_coddepto in $departamentos ";
    }
    $where_instit .= " and db01_anousu =" . db_getsession('DB_anousu');

    if (isset($conv) && trim($conv) != "" && isset($bens_convenio) && trim($bens_convenio) == "S") {
        $where_instit .= " and t09_benscadcedente = $conv";
        $sqlrelatorio = $clbens->sql_query_orgao_convenio(null, "$campos", $ordem, "$where_instit");
        $rsConvenio = $clbenscadcedente->sql_record($clbenscadcedente->sql_query($conv, "z01_nome as convenio"));
        if ($clbenscadcedente->numrows > 0) {
            db_fieldsmemory($rsConvenio, 0);
        }
        $head6 = "Conv�nio : $convenio";
    } else if (isset($bens_convenio) && trim($bens_convenio) == "S") {
        $where_instit .= " and benscedente.t09_bem is not null ";
        $sqlrelatorio = $clbens->sql_query_left_convenio(null, "$campos", $ordem, "$where_instit");
    } else if (isset($bens_convenio) && trim($bens_convenio) == "N") {
        $where_instit .= " and benscedente.t09_bem is null ";
        $sqlrelatorio = $clbens->sql_query_left_convenio(null, "$campos", $ordem, "$where_instit");
    } else {
        $sqlrelatorio = $clbens->sql_query_orgao(null, "$campos", $ordem, "$where_instit");
    }

} else {
    $campos = "distinct " . $campos;

    if (isset($conv) && trim($conv) != "" && isset($bens_convenio) && trim($bens_convenio) == "S") {
        $where_instit .= " and t09_benscadcedente = $conv";
        $sqlrelatorio = $clbens->sql_query_convenio(null, "$campos", $ordem, "$where_instit", "and not exists (select 1 from bensbaix where t55_codbem=t52_bem)");
        $rsConvenio = $clbenscadcedente->sql_record($clbenscadcedente->sql_query($conv, "z01_nome as convenio"));
        if ($clbenscadcedente->numrows > 0) {
            db_fieldsmemory($rsConvenio, 0);
        }
        $head6 = "Conv�nio : $convenio";
    } else if (isset($bens_convenio) && trim($bens_convenio) == "N") {
        //Op��o nehum conv�nio
        $where_instit .= " and benscedente.t09_bem is null ";
        $sqlrelatorio = $clbens->sql_query_left_convenio(null, "$campos", $ordem, "$where_instit");
    } else if (isset($bens_convenio) && trim($bens_convenio) == "S") {
        //Op��o nehum conv�nio
        $where_instit .= " and benscedente.t09_bem is not null ";
        $sqlrelatorio = $clbens->sql_query_left_convenio(null, "$campos", $ordem, "$where_instit");
    } else {
        //Op��o todos
        $sqlrelatorio = $clbens->sql_query(null, "$campos", $ordem, "$where_instit");
    }
}

if($cgmFornecedor){
  if($where_instit){{
    $where_instit .= ' and ';
    $where_instit .=  ' t52_numcgm = '.$cgmFornecedor.' ';
  }}
}

$sqlrelatorio = $clbens->sql_query(null,"$campos",$ordem,"$where_instit");
$result = $clbens->sql_record($sqlrelatorio);

if ($clbens->numrows == 0) {

    $sMsg = _M('patrimonial.patrimonio.pat2_geralbens003.nao_existem_registros');
    db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
    exit;
}

header("Content-type: text/plain");
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=geral_bens.csv");
header("Pragma: no-cache");
readfile( 'geral_bens.csv' );

echo $info.($head6 ? $head6.";" : '' ).($head7 ? $head7.";" : '');

echo "\nC�digo;";
echo "Descri��o do Bem;";
echo "Vlr Aquisi��o;";
echo "Dt. Aquisi��o;";
echo "Empenho;";
echo "Placa;";
echo "Departamento;";
echo "Divis�o;";
echo "Nota F.;";

if($flag_forn){
    echo "Fornecedor;";
    echo "Nome;";
    echo "Observa��es;";
}

if($flag_classi){
    echo "Classifica��o;";
    echo "Descri��o da Classifica��o;";
}

for ($count=0;$count<pg_numrows($result);$count++){
    $oBem = db_utils::fieldsMemory($result, $count);
    $sSql = "select (e60_codemp || '/' || e60_anousu) as e60_codemp from bensmater join empempenho on e60_numemp=t53_empen where t53_codbem = $oBem->t52_bem ";

    if ($t53_empen != "") {
        $sSql .= " and e60_numemp = '" . $t53_empen . "'";
    }
    $rsNumemp = db_query($sSql);

    if (pg_num_rows($rsNumemp) == 0 && $t53_empen != "") {
        continue;
    }
    $iNumemp = db_utils::fieldsMemory($rsNumemp, 0)->e60_codemp;

    $sWhereBensBaixados = " t55_codbem = {$oBem->t52_bem}  ";
    if (!empty($data_final)) {
        $sWhereBensBaixados .= " and t55_baixa <= '{$data_final}' ";
    }
    $sSqlBuscaBensBaixados = $clbensbaix->sql_query_file(null, "*", null, $sWhereBensBaixados);
    $result_bensbaix = $clbensbaix->sql_record($sSqlBuscaBensBaixados);
    if ($clbensbaix->numrows > 0) {
        continue;
    }

    echo "\n".$oBem->t52_bem.";";
    echo str_replace(';',',', $oBem->t52_descr).";";
    echo $oBem->t52_valaqu.";";
    echo join('/', array_reverse(explode('-', $oBem->t52_dtaqu))).";";
    echo $iNumemp.";";
    echo $oBem->t52_ident.";";
    echo $oBem->t52_depart."-".$oBem->descrdepto.";";
    echo $oBem->t33_divisao ? ($oBem->t33_divisao. "-" . substr($oBem->t30_descr, 0, 27)).';' : ' '.";";
    echo $oBem->t53_ntfisc.";";

    if($flag_forn){
        echo $oBem->t52_numcgm.";";
        echo $oBem->z01_nome.";";
        $observacoes = str_replace(',,', ',', preg_replace('/[\r\n;]/', ',', $oBem->t52_obs));
        $observacoes = trim($observacoes) == '.' ? '' : $observacoes;
        echo "$observacoes;";
    }

    if($flag_classi){
        echo $oBem->t64_class.";";
        echo str_replace(';', ',', $oBem->t64_descr).";";
    }
}

?>
