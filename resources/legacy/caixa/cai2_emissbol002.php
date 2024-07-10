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

include ("fpdf151/pdf.php");
require ("libs/db_utils.php");
include ("fpdf151/assinatura.php");
include ("classes/db_orcfontesdes_classe.php");
include ("classes/db_orcfontes_classe.php");
// include ("classes/db_caiparametro_classe.php");

include ("libs/db_liborcamento.php");

$clorcfontesdes = new cl_orcfontesdes;
$clorcfontes = new cl_orcfontes;
$classinatura = new cl_assinatura;
// $clcaiparametro = new cl_caiparametro;

db_postmemory($HTTP_SERVER_VARS);


// lista parametros do modulo contabil
// $res = $clcaiparametro->sql_record($clcaiparametro->sql_query_file(db_getsession("DB_instit")));
// if ($clcaiparametro->numrows >0){
//    db_fieldsmemory($res,0);
// }


$dataatual = date("Y-m-d",db_getsession("DB_datausu"));

 if (isset($k29_boletimzerado) && $k29_boletimzerado=='f'){
  // permite exibi��o de boletim zerado !
  $sql = " select k12_data
           from corrente
           where corrente.k12_instit = ".db_getsession("DB_instit")." and
                 corrente.k12_data  = '".$datai."' limit 1";
  $res = db_query($sql);
  if(pg_numrows($res)==0){
    db_redireciona("db_erros.php?fechar=true&db_erro=N�o existem la�amentos nesta data (".db_formatar($datai,'d').")");
    exit;
  }
 }


/**
- O que ocorre quando aparece receita no bloco de receita e n�o aparece
somando acima na conta banc�ria correspondente


*/



$seleciona_conta = '';
$descr_conta = 'TODAS AS CONTAS';
$ano = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");
if ($conta != 0) {
	$seleciona_conta = ' and corrente.k12_conta = '.$conta;
	$sql = 'select * from saltes ';
	$sql .= ' inner join conplanoexe on c62_reduz = k13_reduz and c62_anousu = '.db_getsession('DB_anousu');
	$sql .= ' inner join conplanoreduz on c62_reduz = c61_reduz and c61_anousu=c62_anousu  and c61_instit = '.db_getsession('DB_instit');
	$sql .= ' where k13_conta = '.$conta;
  $sql .= "   and (k13_limite is null or k13_limite >= '$dataatual') ";
	//  echo $sql;
	$result = db_query($sql);
	db_fieldsmemory($result, 0);
	$descr_conta = "CONTA : ".$conta.' - '.$k13_descr;
	$xconta = $conta;
} else {
	$xconta = 'k13_conta';
}

$selecao = 'TODOS OS CAIXAS';
$seleciona = '';
if ($caixa != 0) {
	$seleciona = ' and corrente.k12_id = '.$caixa;
	$sql = "select * from cfautent where k11_id = $caixa and k11_instit = ".db_getsession("DB_instit");
	$result = db_query($sql);
	//   db_criatabela($result);exit;
	db_fieldsmemory($result, 0);
	$selecao = "CAIXA : ".$caixa.' - '.$k11_local;
	$ip = "'".$k11_ipterm."'";
} else {
	$ip = 'null';
}
/*

if($datai == $dataf){
  $sql = "select k12_data
          from boletim";

  $head1 = "BOLETIM DA TESOURARIA";
  $head3 = "BOLETIM N�MERO: ".@$numbol;
  $head5 = "DATA : ".@$datai;

}else{
  */
////  RECEITAS
$sql = " select c60_codsis, k12_conta, k12_receit, tabrec.k02_tipo, tabrec.k02_drecei,
                round(cornump.k12_valor,2) as k12_valor
         from corrente
              inner join cornump on corrente.k12_id = cornump.k12_id
				                        and corrente.k12_data = cornump.k12_data
                                and corrente.k12_autent = cornump.k12_autent
              inner join tabrec  on k12_receit = k02_codigo
              inner join conplanoexe on k12_conta = c62_reduz
	                                  and c62_anousu = ".db_getsession('DB_anousu')."
      	      inner join conplanoreduz on c62_reduz = c61_reduz and c61_anousu = c62_anousu
	            inner join conplano      on c60_codcon = c61_codcon and c60_anousu = c61_anousu
         where corrente.k12_instit = ".db_getsession("DB_instit")." and
	             corrente.k12_data  between '".$datai."' and '".$dataf."' $seleciona_conta $seleciona
         order by k02_tipo";
$resultorcamentaria = db_query($sql);

/*
  Inclu�do as receitas extras de slips
*/
$sql_rec_ext = "

                SELECT corrente.k12_id,
                corrente.k12_autent,
                corrente.k12_data,
                corrente.k12_valor,
                CASE
                    WHEN (h.c60_codsis = 6 AND f.c60_codsis = 6) THEN 'tran'
                    WHEN (h.c60_codsis = 5 AND f.c60_codsis = 6) THEN 'tran'
                    WHEN (h.c60_codsis = 6 AND f.c60_codsis = 5) THEN 'tran'
                    WHEN (h.c60_codsis = 5 AND f.c60_codsis = 5) THEN 'tran'
                    ELSE 'desp'
                END AS tipo,
                b.k12_conta AS debito,
                f.c60_descr AS descr_debito,
                f.c60_codsis AS sis_debito,
                corrente.k12_conta AS credito,
                h.c60_descr AS descr_credito,
                h.c60_codsis AS sis_credito
                FROM corrente
                INNER JOIN corlanc b ON corrente.k12_id = b.k12_id AND corrente.k12_autent = b.k12_autent AND corrente.k12_data = b.k12_data
                INNER JOIN conplanoreduz t2 ON b.k12_conta = t2.c61_reduz AND t2.c61_anousu = ".db_getsession('DB_anousu')." AND t2.c61_instit = ".db_getsession("DB_instit")."
                INNER JOIN conplano f ON t2.c61_codcon = f.c60_codcon AND t2.c61_anousu = f.c60_anousu
                INNER JOIN conplanoreduz t3 ON corrente.k12_conta = t3.c61_reduz AND t3.c61_anousu = ".db_getsession('DB_anousu')." AND t3.c61_instit = ".db_getsession("DB_instit")."
                INNER JOIN conplano h ON t3.c61_codcon = h.c60_codcon AND t3.c61_anousu = h.c60_anousu
                INNER JOIN sliptipooperacaovinculo ON b.k12_codigo = k153_slip AND k153_slipoperacaotipo NOT IN (1, 2, 5, 6, 9, 10, 13, 14)
                LEFT JOIN saltes c ON c.k13_conta = corrente.k12_conta
                LEFT JOIN saltes d ON d.k13_conta = b.k12_conta
                WHERE corrente.k12_instit = ".db_getsession("DB_instit")."
                AND corrente.k12_data BETWEEN '".$datai."' and '".$dataf."' $seleciona_conta $seleciona


               
               ";

$resultextraorcamentaria = db_query($sql_rec_ext);

// db_criatabela($resultextraorcamentaria);exit;

//// DESPESA EXTRA-ORCAMENTARIA E MOVIMENTA��ES BANCARIAS

$sWherePCASP = '';
if (USE_PCASP) {
  $sWherePCASP = " left join sliptipooperacaovinculo on b.k12_codigo = k153_slip
                                                    and k153_slipoperacaotipo in (1,2,5,6,9,10,13,14)";
}

$sql = "
SELECT corrente.k12_id,
corrente.k12_autent,
corrente.k12_data,
corrente.k12_valor,
CASE
    WHEN (h.c60_codsis = 6 AND f.c60_codsis = 6) THEN 'tran'
    WHEN (h.c60_codsis = 5 AND f.c60_codsis = 6) THEN 'tran'
    WHEN (h.c60_codsis = 6 AND f.c60_codsis = 5) THEN 'tran'
    WHEN (h.c60_codsis = 5 AND f.c60_codsis = 5) THEN 'tran'
    ELSE 'desp'
END AS tipo,
b.k12_conta AS debito,
f.c60_descr AS descr_debito,
f.c60_codsis AS sis_debito,
corrente.k12_conta AS credito,
h.c60_descr AS descr_credito,
h.c60_codsis AS sis_credito
FROM corrente
INNER JOIN corlanc b ON corrente.k12_id = b.k12_id AND corrente.k12_autent = b.k12_autent AND corrente.k12_data = b.k12_data
INNER JOIN conplanoreduz t2 ON b.k12_conta = t2.c61_reduz AND t2.c61_anousu = ".db_getsession('DB_anousu')." AND t2.c61_instit = ".db_getsession("DB_instit")."
INNER JOIN conplano f ON t2.c61_codcon = f.c60_codcon AND t2.c61_anousu = f.c60_anousu
INNER JOIN conplanoreduz t3 ON corrente.k12_conta = t3.c61_reduz AND t3.c61_anousu = ".db_getsession('DB_anousu')." AND t3.c61_instit = ".db_getsession("DB_instit")."
INNER JOIN conplano h ON t3.c61_codcon = h.c60_codcon AND t3.c61_anousu = h.c60_anousu
$sWherePCASP
LEFT JOIN saltes c ON c.k13_conta = corrente.k12_conta
LEFT JOIN saltes d ON d.k13_conta = b.k12_conta
WHERE corrente.k12_instit = ".db_getsession("DB_instit")."
AND corrente.k12_data BETWEEN '".$datai."' and '".$dataf."' $seleciona_conta $seleciona

";
if ($agrupar == 'S') {

      $sql .= " and (select c63_banco::integer||lpad(c63_agencia,4,0)||c63_conta||c63_dvconta||c63_dvagencia as hashconta
                 from conplanoconta
                 inner join conplanoreduz on c61_anousu = c63_anousu
                 and c61_codcon=c63_codcon
                 where c61_reduz=corrente.k12_conta
                     and c61_anousu=".db_getsession('DB_anousu').") !=
                (select c63_banco::integer||lpad(c63_agencia,4,0)||c63_conta||c63_dvconta||c63_dvagencia as hashconta
                 from conplanoconta
                 inner join conplanoreduz on c61_anousu = c63_anousu
                 and c61_codcon=c63_codcon
                 where c61_reduz=b.k12_conta
                     and c61_anousu=".db_getsession('DB_anousu').") ";
}
      $sqlxx .= " )
	as x) as xx) as xxx
          inner join conplanoexe   e on entrou = e.c62_reduz
                              and e.c62_anousu = ".db_getsession('DB_anousu')."
          inner join conplanoreduz i on e.c62_reduz  = i.c61_reduz and
	                                e.c62_anousu = i.c61_anousu
          inner join conplano      f on i.c61_codcon = f.c60_codcon and
                                        i.c61_anousu = f.c60_anousu and
                                        i.c61_instit = ".db_getsession("DB_instit")."
          inner join conplanoexe   g on saiu = g.c62_reduz and
                                g.c62_anousu = ".db_getsession('DB_anousu')."
          inner join conplanoreduz j on g.c62_reduz  = j.c61_reduz  and
                                        g.c62_anousu = j.c61_anousu
          inner join conplano      h on j.c61_codcon = h.c60_codcon and
                                        j.c61_anousu = h.c60_anousu
                                        and j.c61_instit = ".db_getsession("DB_instit")."
    ";

$resultdespesaextra = db_query($sql);
//echo ' Despesa Extra-Or�amentaria ';
// db_criatabela($resultdespesaextra);exit;
if ($ordem_conta == 1) {
  $ordem_conta = " c63_banco, k13_reduz ";
}elseif ($ordem_conta == 2) {
  $ordem_conta = " k13_descr, k13_reduz";
}elseif ($ordem_conta == 3) {
  $ordem_conta = " c60_estrut ";
}elseif ($ordem_conta == 5) {
  $ordem_conta = " k13_reduz ";
} else {
  $ordem_conta = " c60_descr ";
}

if($agrupar=='S'){
  $ordem_conta = "c63_banco,lpad(c63_agencia,4,0),c63_dvagencia,c63_conta,c63_dvconta,c63_tipoconta";
}



$sWhere = "";
if (isset($fonte) && $fonte != '') {  
  $sWhere .= db_getsession('DB_anousu') > 2022 ? " and o15_codigo = '{$fonte}' " : " and o15_codtri = '{$fonte}' ";  
  $descr_conta = "CONTAS DA FONTE {$fonte}";
}

if ($agrupar_fonte == 'S') {
  if($tipo_conta == '0')
        $sWhere .= " and c63_tipoconta > '{$tipo_conta}' ";
  else
        $sWhere .= " and c63_tipoconta = '{$tipo_conta}' ";
  $ordem_conta = "c61_codigo,".$ordem_conta;
}

if ($agrupar_fonte == 'N') {
    if ($agrupar_tipo_conta == 2) {
      if($tipo_conta != '0')
        $sWhere .= " and c63_tipoconta = '{$tipo_conta}' ";
    }else
      $ordem_conta =" c63_tipoconta ";
}


/// CONTAS MOVIMENTO
$sql="select   o15_codtri,c61_codigo,
               o15_descr,
               k13_reduz,
               k13_descr,
               c60_estrut,
               c60_codsis,
         c63_banco,c63_agencia,c63_conta,c63_dvconta,c63_dvagencia,c63_tipoconta,
         hashconta,
	       substr(fc_saltessaldo,2,13)::float8 as anterior,
	       substr(fc_saltessaldo,15,13)::float8 as debitado ,
	       substr(fc_saltessaldo,28,13)::float8 as creditado,
	       substr(fc_saltessaldo,41,13)::float8 as atual
	from (
 	      select o15_codtri,c61_codigo,
               o15_descr,
               k13_reduz,
 	             k13_descr,
	             c60_estrut,
		     c60_codsis,
         c63_banco,c63_agencia,c63_conta,c63_dvconta,c63_dvagencia,c63_tipoconta,
         c63_banco::integer||lpad(c63_agencia,4,0)||c63_conta||c63_dvconta||c63_dvagencia as hashconta, ";

         if ($agrupar == 'S') {
          $sql .= " fc_saltessaldo(k13_reduz,'".$datai."','".$dataf."',$ip,".db_getsession("DB_instit").") ";
        } else {
          $sql .= " fc_saltessaldo(k13_reduz,'".$datai."','".$dataf."',$ip,".db_getsession("DB_instit").") ";
        }
$sql .=	" from  saltes
	             inner join conplanoexe   on k13_reduz = c62_reduz
		                             and c62_anousu = ".db_getsession('DB_anousu')."
		     inner join conplanoreduz on c62_reduz  = c61_reduz and
		                                 c61_anousu = c62_anousu and
		                                 c61_instit = ".db_getsession("DB_instit")."
	             inner join conplano      on c60_codcon = c61_codcon and c60_anousu=c61_anousu
               inner join orctiporec ON o15_codigo = c61_codigo
	             left  join conplanoconta on c60_codcon = c63_codcon and c63_anousu=c60_anousu
  where (k13_limite is null or k13_limite >= '$dataatual' ) $sWhere
 	order by ".$ordem_conta."

          ) as x
	      ";


$resultcontasmovimento = db_query($sql);

if($agrupar_tipo_conta == '1'){
  $atualc = 0;$atualp=0;$atuala  = 0;
  $anteriorc = 0; $anteriorp = 0;$anteriora = 0;
  $debitadoc = 0;$debitadop = 0;$debitadoa = 0;
  $creditadoc = 0;$creditadop = 0;$creditadoa = 0;
$conttipo = array();
for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
	db_fieldsmemory($resultcontasmovimento, $i);
  if($c63_tipoconta == '1'){
      $atualc += $atual;
      $debitadoc += $debitado;
      $creditadoc += $creditado;
      $anteriorc += $anterior;
      $conttipo[0] += 1;}
  if($c63_tipoconta == '2'){
      $atualp += $atual;
      $debitadop += $debitado;
      $creditadop += $creditado;
      $anteriorp += $anterior;
      $conttipo[1] += 1;  }
  if($c63_tipoconta == '3'){
      $atuala += $atual;
      $debitadoa += $debitado;
      $creditadoa += $creditado;
      $anteriora += $anterior;
      $conttipo[2] += 1; }
}

}

//db_criatabela($resultcontasmovimento);echo pg_last_error();exit;

/*
echo ' Contas Movimento  '."<br><br>";
echo ' caixa anterior   '.$caixa_saldo_anterior."<br>";
echo ' caixa debitado   '.$caixa_debitado."<br>";
echo ' caixa creditado  '.$caixa_creditado."<br>";
echo ' caixa atual      '.$caixa_saldo_atual."<br>";
echo ' bancos anterior  '.$bancos_saldo_anterior."<br>";
echo ' bancos debitado  '.$bancos_debitado."<br>";
echo ' bancos creditado '.$bancos_creditado."<br>";
echo ' bancos atual     '.$bancos_saldo_atual."<br>";
*/

///DESPESAS ORCAMENTARIAS

$sql = "
         select c60_codsis,
	                corrente.k12_conta,
	                c60_descr,
		            case when corrente.k12_estorn = 'f' then sum(round(k12_valor,2)) else 0 end as valor,
		            case when corrente.k12_estorn = 't' then sum(round(k12_valor,2)) else 0 end as estorno
         from corrente
		              inner join coremp b       on corrente.k12_autent = b.k12_autent
		                                       and corrente.k12_id = b.k12_id
				 	                           and corrente.k12_data = b.k12_data
			          left join corlanc c       on c.k12_autent = b.k12_autent
			                               and c.k12_id = b.k12_id
					                       and c.k12_data = b.k12_data
		              inner join empempenho e   on e60_numemp = b.k12_empen
			          inner join conplanoreduz on c61_reduz = corrente.k12_conta and c61_anousu= e.e60_anousu
			          inner join conplano on c61_codcon = c60_codcon and c60_anousu=c61_anousu
         where corrente.k12_instit = ".db_getsession("DB_instit")." and
	            c.k12_codigo is null and
	            e.e60_anousu = ".db_getsession("DB_anousu")." and
	            corrente.k12_data between '".$datai."' and '".$dataf."' $seleciona_conta $seleciona
	     group by c60_codsis,corrente.k12_conta,c60_descr,k12_estorn
	     order by c60_codsis,corrente.k12_conta
       ";

//echo $sql;
$resultdespesaorca = db_query($sql);
//db_criatabela($resultorcamentaria);

$head2 = "BOLETIM DE CAIXA E DE BANCOS";
//}
$head4 = $selecao;
$head6 = $descr_conta;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

//RECEITAS RECEBIDAS NO CAIXA
$numlin = pg_numrows($resultorcamentaria);
$cai_rec_orc = 0;
$cai_rec_ext = 0;
for ($i = 0; $i < $numlin; $i ++) {
	db_fieldsmemory($resultorcamentaria, $i);
	//if ($c60_codsis == 5) {
		if ($k02_tipo == 'O') {
			$cai_rec_orc += $k12_valor;
		}
		elseif ($k02_tipo == 'E') {
			$cai_rec_ext += $k12_valor;
		}
	//}
}

//RECEITAS EXTRAS RECEBIDAS NO CAIXA
$numlin = pg_numrows($resultextraorcamentaria);
for ($x = 0; $x < $numlin; $x ++) {
  db_fieldsmemory($resultextraorcamentaria, $x);
	//if ($sis_debito == 5) {
     $cai_rec_ext += $k12_valor;
	//}
}
//DESPESAS OR�AMENTARIAS PAGAS NO CAIXA
//db_criatabela($resultdespesaorca);
$numlin = pg_numrows($resultdespesaorca);
$cai_desp_orca = 0;
$cai_ret_bco_orca = 0;
$cai_dep_bco_orca = 0;
for ($i = 0; $i < $numlin; $i ++) {
	db_fieldsmemory($resultdespesaorca, $i);
	//if ($c60_codsis == 5) {
		$cai_desp_orca += $valor + $estorno;
	//}
}

//DESPESAS EXTRA-OR�AMENTARIAS PAGAS NO CAIXA
$numlin = pg_numrows($resultdespesaextra);
$cai_desp_ext = 0;
$cai_ret_bco = 0;
$cai_dep_bco = 0;
for ($i = 0; $i < $numlin; $i ++) {
	db_fieldsmemory($resultdespesaextra, $i);
	//if ($sis_credito == 5 || $sis_debito == 5) {
		if ($tipo == 'desp') {
			$cai_desp_ext += $k12_valor;
		} else {
			//if ($sis_debito == 5) {
				$cai_ret_bco += $k12_valor;
			//}
			//if ($sis_credito == 5 || $sis_debito != 5) {
				$cai_dep_bco += $k12_valor;
			//}
		}
	//}
}

$cai_tot_entradas = $cai_rec_orc + $cai_rec_ext + $cai_ret_bco;
$cai_tot_saidas = $cai_desp_ext + $cai_dep_bco + $cai_desp_orca + $cai_dep_bco_orca;
//echo $cai_desp_ext;
/// SALDOS DO CAIXA
$caixa_saldo_anterior = 0;
$caixa_debitado = 0;
$caixa_creditado = 0;
$caixa_saldo_atual = 0;
$aAgrupaFonteCaixa  = array();
$aAgrupaFonteBancos = array();
for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
	db_fieldsmemory($resultcontasmovimento, $i);
	//if ($c60_codsis == 5) {
		$caixa_saldo_anterior += $anterior;
    $caixa_debitado += $debitado;
		$caixa_creditado += $creditado;
		$caixa_saldo_atual += $atual;
	//}
    if ($agrupar_fonte == 'S') {
      if ($c60_codsis == 5) {
        $nomeArray = "aAgrupaFonteCaixa";
      } else if ($c60_codsis == 6) {
        $nomeArray = "aAgrupaFonteBancos";
      }
      if (!isset(${$nomeArray}[$o15_codtri])) {
        $oFonte = new stdClass();
        $oFonte->o15_codigo = $c61_codigo;
        $oFonte->o15_descr = $o15_descr;
        $oFonte->anterior = 0;
        $oFonte->debitado = 0;
        $oFonte->creditado = 0;
        $oFonte->atual = 0;
        ${$nomeArray}[$o15_codtri] = $oFonte;
      }
      ${$nomeArray}[$o15_codtri]->anterior += $anterior;
      ${$nomeArray}[$o15_codtri]->debitado += $debitado;
      ${$nomeArray}[$o15_codtri]->creditado += $creditado;
      ${$nomeArray}[$o15_codtri]->atual += $atual;

      if (db_getsession("DB_anousu") > 2022) {
        ${$nomeArray}[$c61_codigo]->anterior += $anterior;
        ${$nomeArray}[$c61_codigo]->debitado += $debitado;
        ${$nomeArray}[$c61_codigo]->creditado += $creditado;
        ${$nomeArray}[$c61_codigo]->atual += $atual;
      }
    }
}

$saldo_seguinte = $cai_tot_entradas + $caixa_saldo_anterior - $cai_tot_saidas;
$alt = 5;
$pdf->SetFont('Arial', 'B', 12);
//$pdf->SetTextColor(0,100,255);
$pdf->Setfillcolor(235);
$pdf->Cell(190, 5, "BOLETIM DE CAIXA E DE BANCOS DE ".db_formatar($datai, 'd')." AT� ".db_formatar($dataf,'d'), 0, 1, "C", 0);
$pdf->SetFont('Arial', 'B', 10);
// $pdf->ln(6);
// $pdf->Cell(192, 6, "MOVIMENTA��ES DOS CAIXA/BANCOS", 1, 1, "L", 0);
// $pdf->SetFont('Arial', 'B', 8);
// $pdf->cell(48, $alt, 'ENTRADAS (D�BITO)', 1, 0, 'C', 0);
// $pdf->cell(48, $alt, 'VALORES', 1, 0, 'C', 0);
// $pdf->cell(48, $alt, 'SA�DAS (CR�DITO)', 1, 0, 'C', 0);
// $pdf->cell(48, $alt, 'VALORES', 1, 1, 'C', 0);
// $pdf->SetTextColor(0);
// $pdf->cell(48, $alt, 'Receitas Or�ament�rias', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_rec_orc, 'f'), 1, 0, 'R', 0);
// $pdf->cell(48, $alt, 'Despesas Or�ament�rias', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_desp_orca, 'f'), 1, 1, 'R', 0);
// $pdf->cell(48, $alt, 'Receitas Extra-Or�ament�rias', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_rec_ext, 'f'), 1, 0, 'R', 0);
// $pdf->cell(48, $alt, 'Despesas Extra-Or�ament�rias', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_desp_ext, 'f'), 1, 1, 'R', 0);
// $pdf->cell(48, $alt, 'Retiradas de Bancos', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_ret_bco, 'f'), 1, 0, 'R', 0);
// $pdf->cell(48, $alt, 'Dep�sitos Banc�rios', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_dep_bco, 'f'), 1, 1, 'R', 0);
// //$pdf->SetTextColor(0,100,255);
// $pdf->cell(48, $alt, 'TOTAL', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_tot_entradas, 'f'), 1, 0, 'R', 0);
// $pdf->cell(48, $alt, 'TOTAL', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_tot_saidas, 'f'), 1, 1, 'R', 0);
// $pdf->SetTextColor(0, 0, 0);
// $pdf->cell(48, $alt, 'Saldo Anterior', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($caixa_saldo_anterior, 'f'), 1, 0, 'R', 0);
// $pdf->cell(48, $alt, 'Saldo do dia', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($saldo_seguinte, 'f'), 1, 1, 'R', 0);
// //$pdf->SetTextColor(0,100,255);
// $pdf->cell(48, $alt, 'SOMA', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($caixa_saldo_anterior + $cai_tot_entradas, 'f'), 1, 0, 'R', 0);
// $pdf->cell(48, $alt, 'SOMA', 1, 0, 'L', 0);
// $pdf->cell(48, $alt, db_formatar($cai_tot_saidas + $saldo_seguinte, 'f'), 1, 1, 'R', 0);

$pdf->ln(6);

$cai_tot_entradas = $cai_rec_orc + $cai_rec_ext + $cai_ret_bco;
$cai_tot_saidas = $cai_desp_ext + $cai_dep_bco + $cai_desp_orca + $cai_dep_bco_orca;
//echo $cai_desp_ext;
/// SALDOS DO CAIXA
/*$caixa_saldo_anterior = 0;
$caixa_debitado = 0;
$caixa_creditado = 0;
$caixa_saldo_atual = 0;
for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
	db_fieldsmemory($resultcontasmovimento, $i);
	//if ($c60_codsis == 5) {
		$caixa_saldo_anterior += $anterior;
		$caixa_debitado += $debitado;
		$caixa_creditado += $creditado;
		$caixa_saldo_atual += $atual;
	//}
}*/
$saldo_seguinte = $cai_tot_entradas + $caixa_saldo_anterior - $cai_tot_saidas;
$alt = 5;
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(192, 6, "MOVIMENTA��ES DOS CAIXAS/BANCOS", 1, 1, "L", 0);
$pdf->SetFont('Arial', 'B', 7);
$pdf->cell(96, $alt, 'CAIXA', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'SALDO', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'DEP�SITOS', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'RETIRADAS', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'SALDO', "LRT", 1, 'C', 0);
$pdf->cell(96, $alt, '', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, 'ANTERIOR', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, '', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, '', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, 'ATUAL', "LRB", 1, 'C', 0);
$pdf->SetTextColor(0);
$saldoc_anterior = 0;
$saldoc_debitado = 0;
$saldoc_creditado = 0;
$saldoc_atual = 0;
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 8);
$sVerificaFonte = "";

for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
	db_fieldsmemory($resultcontasmovimento, $i);
	if ($pdf->gety() > ($pdf->h - 30)) {
		$pdf->addpage();
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(192, 6, "MOVIMENTA��ES DOS CAIXAS/BANCOS", 1, 1, "L", 0);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->cell(96, $alt, 'CAIXA', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'SALDO', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'DEP�SITOS', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'RETIRADAS', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'SALDO', "LRT", 1, 'C', 0);
		$pdf->cell(96, $alt, '', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, 'ANTERIOR', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, '', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, '', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, 'ATUAL', "LRB", 1, 'C', 0);
		$pdf->SetFont('Arial', '', 6);
	}
	if ($c60_codsis == 5) {
    if ($agrupar_fonte == "S" && $sVerificaFonte != $o15_descr) {
      if ($sVerificaFonte != "") {
        $pdf->cell(192, $alt, "", 1, 1, 1, 0);
      }
      // $pdf->cell(96, $alt, 'teste', "LRT", 0, 'C', 0);
      $pdf->SetFont('Arial', 'B', 7);
      $pdf->cell(96, $alt, $c61_codigo.' - '.substr($o15_descr,0,57), "LTB", 0, 'L', 0);
      $pdf->cell(24, $alt, db_formatar($aAgrupaFonteCaixa[$o15_codtri]->anterior, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($aAgrupaFonteCaixa[$o15_codtri]->debitado, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($aAgrupaFonteCaixa[$o15_codtri]->creditado, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($aAgrupaFonteCaixa[$o15_codtri]->atual, 'f'), 1, 1, 'R', 0);
      $pdf->SetFont('Arial', '', 6);
      $sVerificaFonte = $o15_descr;
    }
		$pdf->cell(80, $alt, $k13_reduz.' - '.$k13_descr, "LTB", 0, 'L', 0);
		$pdf->cell(16, $alt, $c63_conta, "RTB", 0, 'L', 0);
		$pdf->cell(24, $alt, db_formatar($anterior, 'f'), 1, 0, 'R', 0);
		$pdf->cell(24, $alt, db_formatar($debitado, 'f'), 1, 0, 'R', 0);
		$pdf->cell(24, $alt, db_formatar($creditado, 'f'), 1, 0, 'R', 0);
		$pdf->cell(24, $alt, db_formatar($atual, 'f'), 1, 1, 'R', 0);
		$saldoc_anterior += $anterior;
		$saldoc_debitado += $debitado;
		$saldoc_creditado += $creditado;
		$saldoc_atual += $atual;
	}

}
$pdf->SetFont('Arial', 'B', 8);
//$pdf->SetTextColor(0,100,255);
$pdf->cell(96, $alt, 'TOTAL', 1, 0, 'L', 0);
$pdf->cell(24, $alt, db_formatar($saldoc_anterior, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldoc_debitado, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldoc_creditado, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldoc_atual, 'f'), 1, 1, 'R', 0);

/// SALDOS DE INTERFERENCIA
if ($imprime_interferencia == 'S') {
	$inter_saldo_anterior = 0;
	$inter_debitado = 0;
	$inter_creditado = 0;
	$inter_saldo_atual = 0;
	for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
		db_fieldsmemory($resultcontasmovimento, $i);
		if ($c60_codsis == 8) {
			$inter_saldo_anterior += $anterior;
			$inter_debitado += $debitado;
			$inter_creditado += $creditado;
			$inter_saldo_atual += $atual;
		}
	}
	$saldo_seguinte = $cai_tot_entradas + $caixa_saldo_anterior - $cai_tot_saidas;
	$alt = 5;
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->cell(96, $alt, 'INTERFER�NCIA', "LRT", 0, 'C', 0);
	$pdf->cell(24, $alt, 'SALDO', "LRT", 0, 'C', 0);
	$pdf->cell(24, $alt, 'DEP�SITOS', "LRT", 0, 'C', 0);
	$pdf->cell(24, $alt, 'RETIRADAS', "LRT", 0, 'C', 0);
	$pdf->cell(24, $alt, 'SALDO', "LRT", 1, 'C', 0);
	$pdf->cell(96, $alt, '', "LRB", 0, 'C', 0);
	$pdf->cell(24, $alt, 'ANTERIOR', "LRB", 0, 'C', 0);
	$pdf->cell(24, $alt, '', "LRB", 0, 'C', 0);
	$pdf->cell(24, $alt, '', "LRB", 0, 'C', 0);
	$pdf->cell(24, $alt, 'ATUAL', "LRB", 1, 'C', 0);
	$pdf->SetTextColor(0);
	$saldoi_anterior = 0;
	$saldoi_debitado = 0;
	$saldoi_creditado = 0;
	$saldoi_atual = 0;
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial', '', 8);
	for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
		db_fieldsmemory($resultcontasmovimento, $i);
		if ($contassemmov == "f" and $debitado == 0 and $creditado == 0 and $anterior==0 and $atual==0) {
			continue;
		}
		if ($pdf->gety() > ($pdf->h - 30)) {
			$pdf->addpage();
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->cell(96, $alt, 'INTERFER�NCIA', "LRT", 0, 'C', 0);
			$pdf->cell(24, $alt, 'SALDO', "LRT", 0, 'C', 0);
			$pdf->cell(24, $alt, 'DEP�SITOS', "LRT", 0, 'C', 0);
			$pdf->cell(24, $alt, 'RETIRADAS', "LRT", 0, 'C', 0);
			$pdf->cell(24, $alt, 'SALDO', "LRT", 1, 'C', 0);
			$pdf->cell(96, $alt, '', "LRB", 0, 'C', 0);
			$pdf->cell(24, $alt, 'ANTERIOR', "LRB", 0, 'C', 0);
			$pdf->cell(24, $alt, ' ', "LRB", 0, 'C', 0);
			$pdf->cell(24, $alt, ' ', "LRB", 0, 'C', 0);
			$pdf->cell(24, $alt, 'ATUAL', "LRB", 1, 'C', 0);
			$pdf->SetFont('Arial', '', 6);
		}
		if ($c60_codsis == 8) {
			$pdf->cell(80, $alt, $k13_reduz.' - '.$k13_descr, "LTB", 0, 'L', 0);
			$pdf->cell(16, $alt, $c63_conta, "RTB", 0, 'L', 0);
			$pdf->cell(24, $alt, db_formatar($anterior, 'f'), 1, 0, 'R', 0);
			$pdf->cell(24, $alt, db_formatar($debitado, 'f'), 1, 0, 'R', 0);
			$pdf->cell(24, $alt, db_formatar($creditado, 'f'), 1, 0, 'R', 0);
			$pdf->cell(24, $alt, db_formatar($atual, 'f'), 1, 1, 'R', 0);
			$saldoi_anterior += $anterior;
			$saldoi_debitado += $debitado;
			$saldoi_creditado += $creditado;
			$saldoi_atual += $atual;
		}
	}
	$pdf->SetFont('Arial', 'B', 8);
	//$pdf->SetTextColor(0,100,255);
	$pdf->cell(96, $alt, 'TOTAL', 1, 0, 'L', 0);
	$pdf->cell(24, $alt, db_formatar($saldoi_anterior, 'f'), 1, 0, 'R', 0);
	$pdf->cell(24, $alt, db_formatar($saldoi_debitado, 'f'), 1, 0, 'R', 0);
	$pdf->cell(24, $alt, db_formatar($saldoi_creditado, 'f'), 1, 0, 'R', 0);
	$pdf->cell(24, $alt, db_formatar($saldoi_atual, 'f'), 1, 1, 'R', 0);
}

// lista contas de banco
//$pdf->SetTextColor(0,100,255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->cell(96, $alt, 'BANCOS', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'SALDO', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'DEP�SITOS', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'RETIRADAS', "LRT", 0, 'C', 0);
$pdf->cell(24, $alt, 'SALDO', "LRT", 1, 'C', 0);
$pdf->cell(96, $alt, '', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, 'ANTERIOR', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, ' ', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, ' ', "LRB", 0, 'C', 0);
$pdf->cell(24, $alt, 'ATUAL', "LRB", 1, 'C', 0);
$pdf->SetTextColor(0);
$saldo_anterior = 0;
$saldo_debitado = 0;
$saldo_creditado = 0;
$saldo_atual = 0;
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 8);
if($agrupar_tipo_conta == '2'&& $tipo_conta !=0){
  if($c63_tipoconta == '1'){
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->cell(192, 6, " CONTAS CORRENTES ",1, 1, "L", 0);
  }
  if($c63_tipoconta == '2'){
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->cell(192, 6, " CONTAS POUPAN�A ",1, 1, "L", 0);
    }
  if($c63_tipoconta == '3'){
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->cell(192, 6, " CONTAS APLICA��ES ",1, 1, "L", 0);

    }

}

$contasNegrito = array();
$sql = "SELECT k171_conta FROM conciliacaobancaria WHERE k171_datafechamento >= '{$dataf}' ";
$query = pg_query($sql);
while ($data = pg_fetch_object($query)) {
    $contasNegrito[$data->k171_conta] = $data->k171_conta;
}

//db_criatabela($resultcontasmovimento);exit;
$aContasMovs = array();

for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i ++) {
  db_fieldsmemory($resultcontasmovimento, $i);

  if ($c60_codsis == 6) {
    if($agrupar=='S'){
      $sHash = $hashconta;
    }else{
      $sHash = $k13_reduz;
    }

    if( !isset($aContasMovs[$sHash]) ){
      $oConta = new stdClass();
      if($agrupar=='S'){
        $oConta->c63_conta = "-";
        $oConta->descr = $k13_descr;
      }else{
        // Condicionar o negrito
        if ($negrito == 't') {
            if (array_key_exists($k13_reduz, $contasNegrito))
                $oConta->negrito = '';
            else
                $oConta->negrito = 'B';
            } else {
                $oConta->negrito = '';
        }
        $oConta->descr = $k13_reduz.' - '.$k13_descr;
        $oConta->c63_conta = "N� Cta: ".$c63_conta.'-'.$c63_dvconta;
      }
      $oConta->o15_codtri  = $o15_codtri;
      $oConta->o15_descr   = $o15_descr;
      $oConta->c61_codigo  = $c61_codigo;
      $oConta->anterior    = $anterior;
      $oConta->debitado    = $debitado;
      $oConta->creditado   = $creditado;
      $oConta->atual       = $atual;
      $oConta->codsis      = $c60_codsis;
      $aContasMovs[$sHash] = $oConta;
    }else{
      $aContasMovs[$sHash]->anterior  += $anterior;
      $aContasMovs[$sHash]->debitado  += $debitado;
      $aContasMovs[$sHash]->creditado += $creditado;
      $aContasMovs[$sHash]->atual     += $atual;
    }
  }
}

$sVerificaFonte = "";
$itipo = 0;
foreach ($aContasMovs as $oConta) {
  $itipo++;
  if($agrupar_tipo_conta == '1' and $agrupar_fonte == 'N'){
    if($itipo == 1 && (count($conttipo[0])>0)){
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->cell(96, $alt, " I. CONTAS CORRENTES ", "LTB", 0, 'L', 0);
      $pdf->cell(24, $alt, db_formatar($anteriorc, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($debitadoc, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($creditadoc, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($atualc, 'f'), 1, 1, 'R', 0);
    }
    if($itipo == ($conttipo[0]+1) && (count($conttipo[1])>0)){
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->cell(96, $alt, " II. CONTAS POUPAN�A ", "LTB", 0, 'L', 0);
      $pdf->cell(24, $alt, db_formatar($anteriorp, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($debitadop, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($creditadop, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($atualp, 'f'), 1, 1, 'R', 0);
    }
    if($itipo == ($conttipo[0]+$conttipo[1])+1 && (count($conttipo[2])>0)){
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->cell(96, $alt, " III. CONTAS APLICA��ES ", "LTB", 0, 'L', 0);
      $pdf->cell(24, $alt, db_formatar($anteriora, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($debitadoa, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($creditadoa, 'f'), 1, 0, 'R', 0);
      $pdf->cell(24, $alt, db_formatar($atuala, 'f'), 1, 1, 'R', 0);

    }
  }

	if ($contassemmov == "f" and $oConta->debitado == 0 and $oConta->creditado == 0 and $oConta->anterior==0 and $oConta->atual ==0) {
    continue;
  }
	if ($pdf->gety() > ($pdf->h - 30)) {
		$pdf->addpage();
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(192, 6, "MOVIMENTA��ES DOS CAIXAS/BANCOS", 1, 1, "L", 0);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->cell(96, $alt, 'BANCOS', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'SALDO', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'DEP�SITOS', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'RETIRADAS', "LRT", 0, 'C', 0);
		$pdf->cell(24, $alt, 'SALDO', "LRT", 1, 'C', 0);
		$pdf->cell(96, $alt, '', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, 'ANTERIOR', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, ' ', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, ' ', "LRB", 0, 'C', 0);
		$pdf->cell(24, $alt, 'ATUAL', "LRB", 1, 'C', 0);

		$pdf->SetFont('Arial', '', 8);

	}


  $pre = 0;
  if($negrito == 't' && $oConta->negrito == "B"){
    $pre = 1;
    $pdf->Setfillcolor(220);
  }

	if ($oConta->codsis == 6) {
    if ($agrupar_fonte == "S" && $sVerificaFonte != $oConta->o15_descr) {
      if ($sVerificaFonte != "") {
        $pdf->cell(192, $alt, 0, 1, 1, 1, 0);
      }
      $pdf->SetFont('Arial', 'B', 7);
      if (db_getsession("DB_anousu") > 2022) {
        $pdf->cell(96, $alt, $oConta->c61_codigo .' - '.substr($oConta->o15_descr,0,52), "LTB", 0, 'L', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->c61_codigo]->anterior, 'f'), 1, 0, 'R', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->c61_codigo]->debitado, 'f'), 1, 0, 'R', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->c61_codigo]->creditado, 'f'), 1, 0, 'R', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->c61_codigo]->atual, 'f'), 1, 1, 'R', 0);
      }else{
        $pdf->cell(96, $alt, $oConta->o15_codtri .' - '.substr($oConta->o15_descr,0,52), "LTB", 0, 'L', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->o15_codtri]->anterior, 'f'), 1, 0, 'R', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->o15_codtri]->debitado, 'f'), 1, 0, 'R', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->o15_codtri]->creditado, 'f'), 1, 0, 'R', 0);
        $pdf->cell(24, $alt, db_formatar($aAgrupaFonteBancos[$oConta->o15_codtri]->atual, 'f'), 1, 1, 'R', 0);        
      }
      $sVerificaFonte = $oConta->o15_descr;
    }
		$pdf->SetFont('Arial', $oConta->negrito, 6);
		$pdf->cell(78, $alt, $oConta->descr, "LTB", 0, 'L', $pre);

		$pdf->SetFont('Arial', $oConta->negrito, 6);
		$pdf->cell(18, $alt, substr($oConta->c63_conta,0,16), "RTB", 0, 'L', $pre);

		$pdf->cell(24, $alt, db_formatar($oConta->anterior, 'f'), 1, 0, 'R', $pre);
		$pdf->cell(24, $alt, db_formatar($oConta->debitado, 'f'), 1, 0, 'R', $pre);
		$pdf->cell(24, $alt, db_formatar($oConta->creditado, 'f'), 1, 0, 'R', $pre);
		$pdf->cell(24, $alt, db_formatar($oConta->atual, 'f'), 1, 1, 'R', $pre);
		$saldo_anterior += $oConta->anterior;
		$saldo_debitado += $oConta->debitado;
		$saldo_creditado += $oConta->creditado;
		$saldo_atual += $oConta->atual;
	}


}

$pdf->SetFont('Arial', 'B', 8);
//$pdf->SetTextColor(0,100,255);
$pdf->cell(96, $alt, 'TOTAL', 1, 0, 'L', 0);
$pdf->cell(24, $alt, db_formatar($saldo_anterior, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldo_debitado, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldo_creditado, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldo_atual, 'f'), 1, 1, 'R', 0);
$pdf->cell(96, $alt, 'TOTAL CAIXA/BANCOS', 1, 0, 'L', 0);
$pdf->cell(24, $alt, db_formatar($saldo_anterior + $saldoc_anterior, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldo_debitado + $saldoc_debitado, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldo_creditado + $saldoc_creditado, 'f'), 1, 0, 'R', 0);
$pdf->cell(24, $alt, db_formatar($saldo_atual + $saldoc_atual, 'f'), 1, 1, 'R', 0);

$sql =
/*
"
select debito,descr_debito,sum(caixa) as caixa,sum(banco) as banco
from
(select distinct
	debito,
        descr_debito,
        case when sis_credito = 5 then k12_valor else 0 end as caixa,
        case when sis_credito = 6 then k12_valor else 0 end as banco
from
	(select k12_id,
       		k12_autent,
       		k12_data,
       		k12_valor,
       		tipo,
       		entrou as debito,
       		f.c60_descr as descr_debito,
       		f.c60_codsis as sis_debito,
       		saiu as credito,
       		h.c60_descr as descr_credito,
       		h.c60_codsis as sis_credito
	from 	(select k12_id,
             		k12_autent,
	     		k12_data,
	     		k12_valor,
	     		tipo,
	     		corlanc as entrou,
	     		corrente as saiu from 	(select *, case when coalesce(corl_saltes,0) = 0
	                                           	      then 'desp'
						              else 'tran'
						                end as tipo
				    		from 	(select corrente.k12_id,
				                 	     corrente.k12_autent,
						 	     corrente.k12_data,
						 	     corrente.k12_valor,
						 	     corrente.k12_conta as corrente,
						 	     c.k13_conta as corr_saltes,
						 	     b.k12_conta as corlanc,
							     d.k13_conta as corl_saltes
				          		from corrente
					       			inner join corlanc b on corrente.k12_id = b.k12_id
					                		            and corrente.k12_autent=b.k12_autent
								   		    and corrente.k12_data = b.k12_data
					       			left join saltes c   on c.k13_conta = corrente.k12_conta
					       			left join saltes d   on d.k13_conta = b.k12_conta
					  		where corrente.k12_data = '$datai' $seleciona_conta $seleciona )
				          		as x)
	                            		as xx)
      		as xxx
      		inner join conplanoexe   e on entrou = e.c62_reduz
		inner join conplanoreduz i on e.c62_reduz  = i.c61_reduz
      		inner join conplano      f on i.c61_codcon = f.c60_codcon
      		inner join conplanoexe   g on saiu = g.c62_reduz
		inner join conplanoreduz j on g.c62_reduz = j.c61_reduz
      		inner join conplano      h on j.c61_codcon = h.c60_codcon)
	as xxxx
) as xxxxx
group by debito,descr_debito
*/
"
select k12_data,
       case when k12_estorn =  'f' then valor else 0 end as valor,
       case when k12_estorn <> 'f' then valor else 0 end as estorno,
       corrente,
       descr_conta,
       corr_saltes,
       corlanc,
       case when (sisdebito = 6 and siscredito = 6) then 'tran'
            when (sisdebito = 6 and siscredito = 5) then 'tran'
            when (sisdebito = 5 and siscredito = 6) then 'tran'
            when (sisdebito = 5 and siscredito = 5) then 'tran'
       else 'desp' end as  tipo,
       descr_receita,
       corl_saltes
from (
select corrente.k12_id,
       corrente.k12_data,
       sum(round(corrente.k12_valor,2)) as valor,
       corrente.k12_conta as corrente,
       p2.c60_descr as descr_conta,
       coalesce(c.k13_conta,0) as corr_saltes,
       b.k12_conta as corlanc,
       p1.c60_descr as descr_receita,
       p1.c60_codsis as sisdebito,
       p2.c60_codsis as siscredito,
       coalesce(d.k13_conta,0) as corl_saltes,
       k12_estorn
from corrente
     inner join corlanc b on corrente.k12_id = b.k12_id
                          and corrente.k12_autent=b.k12_autent
                          and corrente.k12_data = b.k12_data
     $sWherePCASP
     left join saltes c   on c.k13_conta = corrente.k12_conta
     left join saltes d   on d.k13_conta = b.k12_conta
     inner join conplanoreduz r1 on b.k12_conta = r1.c61_reduz and r1.c61_anousu=".db_getsession("DB_anousu")."
     inner join conplano      p1 on r1.c61_codcon = p1.c60_codcon and r1.c61_anousu=p1.c60_anousu
     inner join conplanoreduz r2 on corrente.k12_conta = r2.c61_reduz and r2.c61_anousu=".db_getsession("DB_anousu")."
     inner join conplano      p2 on r2.c61_codcon = p2.c60_codcon and r2.c61_anousu=p2.c60_anousu
where corrente.k12_instit = ".db_getsession("DB_instit")." and
      corrente.k12_data = '$datai' $seleciona_conta $seleciona
group by corrente.k12_id,
       corrente.k12_data,
       corrente.k12_conta,
       p2.c60_descr,
       c.k13_conta,
       b.k12_conta,
       p1.c60_descr,
       p1.c60_codsis,
       p2.c60_codsis,
       d.k13_conta,
       k12_estorn
order by corrente.k12_conta,
         p2.c60_descr,
	 b.k12_conta,
	 p1.c60_descr
) as x
    ";
//echo $sql;exit;
$resultdespesaextra = db_query($sql);
//db_criatabela($resultdespesaextra);
if($caixabanco=='N'){
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(192, 6, "", 0, 1, "L", 0);
  $pdf->Cell(192, 6, "TRANSFER�NCIAS BANC�RIAS", 1, 1, "L", 0);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->cell(112, $alt, 'DESCRI��O', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'VALOR', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'ESTORNO', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'BANCO', 1, 1, 'C', 0);
  $pdf->SetTextColor(0);
  $saldo_anterior = 0;
  $saldo_debitado = 0;
  $saldo_creditado = 0;
  $saldo_atual = 0;
  $pdf->SetFont('Arial', '', 6);

  // TRANSFERENCIAS

  $numlin = pg_numrows($resultdespesaextra);
  $total_valor = 0;
  $total_estorno = 0;
  $total_banco = 0;
  $quebra = 0;

  for ($i = 0; $i < $numlin; $i ++) {
  	db_fieldsmemory($resultdespesaextra, $i);
      if ($tipo == "desp") {

        continue;
      }
  	$sql = "select k13_conta
  	           from saltes
  	             inner join conplanoexe on c62_reduz = k13_reduz and
  		                              c62_anousu = ".db_getsession('DB_anousu')."
  	             inner join conplanoreduz on c61_reduz = c62_reduz and
  		                                c61_anousu = c62_anousu and
  		                                c61_instit = ".db_getsession('DB_instit')."
  	           where k13_conta = $corr_saltes
  	 	   union all
  		   select k13_conta
  		   from saltes
  	             inner join conplanoexe on c62_reduz = k13_reduz and
  		                              c62_anousu = ".db_getsession('DB_anousu')."
  	             inner join conplanoreduz on c62_reduz = c61_reduz and
  	                                        c61_anousu = c62_anousu and
  		                                c61_instit = ".db_getsession('DB_instit')."
  		   where k13_conta = $corl_saltes
           and (k13_limite is null or k13_limite >= '$dataatual' )";
    $result = db_query($sql);

  	if ($result == false || pg_numrows($result) != 2)
  		continue;

  	if ($quebra != $corrente) {
  		$pdf->SetFont('Arial', 'B', 8);
  		$pdf->cell(10, $alt, $corrente, "LTB", 0, "R", 0);
  		$pdf->cell(102, $alt, '- '.$descr_conta, "RTB", 0, "L", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 1, "R", 0);
  		$quebra = $corrente;
  		$pdf->SetFont('Arial', '', 6);
  	}
  	$pdf->cell(10, $alt, $corlanc, "LTB", 0, "R", 0);
  	$pdf->cell(102, $alt, '- '.$descr_receita, "RTB", 0, "L", 0);
  	$pdf->cell(20, $alt, db_formatar($valor, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($estorno, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($valor + $estorno, 'f'), 1, 0, "R", 0);
  	$total_banco += $valor + $estorno;
  	if ($i +1 == $numlin) {
  	        // ultima linha
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	}
  	elseif ($quebra != pg_result($resultdespesaextra, $i +1, "corrente")) {

  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	} else {

  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  	}
  	$total_valor += $valor;
  	$total_estorno += $estorno;
  }

  $pdf->SetFont('Arial', 'B', 6);
  //$pdf->SetTextColor(0,100,255);
  $pdf->cell(112, $alt, 'TOTAL', 1, 0, 'L', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 1, 'R', 0);
  $pdf->SetTextColor(0);

  //RECEITAS OR�AMENT�RIAS
  $pdf->SetTextColor(0);
  if ($quebrarpag == 'N') {
  	$pdf->ln();
  } else {
  	$pdf->addpage();
  }
  //$pdf->SetTextColor(0,100,255);
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(192, 6, "RECEITAS OR�AMENT�RIAS", 1, 1, "L", 0);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->cell(112, $alt, 'DESCRI��O', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'VALOR', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'ESTORNO', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL CONTA', 1, 1, 'C', 0);
  $pdf->SetTextColor(0);
  $saldo_anterior = 0;
  $saldo_debitado = 0;
  $saldo_creditado = 0;
  $saldo_atual = 0;
  $pdf->SetTextColor(0);
  $pdf->SetFont('Arial', 'b', 6);

  /*
    Inclu�do as receitas extras de slips
  */
  $sql_rec_ext = "
                  select
                         k12_conta,
                         k12_dconta,
                         sum(case when k12_estorn = 'f' then round(valor,2) else 0 end) as valor,
                         sum(case when k12_estorn = 't' then round(valor,2) else 0 end) as estorno,
                         ctarec,
                         descr_ctarec
                  from (
                        select
                               entrou as debito,
                               max(f.c60_descr) as descr_debito,
                               saiu as credito,
                               max(h.c60_descr) as descr_credito,
                               sum(round(valor,2)) as valor,
                               case when k12_estorn = 'f' then entrou else saiu end as k12_conta,
                               case when k12_estorn = 'f' then max(f.c60_descr) else max(h.c60_descr) end as k12_dconta,
                               case when k12_estorn = 'f' then entrou else saiu end as ctarec,
                               case when k12_estorn = 'f' then max(h.c60_descr) else max(f.c60_descr) end as descr_ctarec,
                               k12_estorn
                        from
                             (select
                                     k12_id,
                                     k12_autent,
                                     k12_data,
                                     k12_valor as valor,
                                     corlanc as entrou,
                                     corrente as saiu,
                                     k12_estorn
                              from
                                   (select corrente.k12_id,
                                           corrente.k12_autent,
                                           corrente.k12_data,
                                           corrente.k12_valor,
                                           corrente.k12_conta as corrente,
                                           coalesce(c.k13_conta,0) as corr_saltes,
                                           b.k12_conta as corlanc,
                                           coalesce(d.k13_conta,0) as corl_saltes,
                                           corrente.k12_estorn
                                    from corrente
                                         inner join corlanc b on corrente.k12_id = b.k12_id
                                                             and corrente.k12_autent=b.k12_autent
                                                             and corrente.k12_data = b.k12_data
                                         inner join sliptipooperacaovinculo on b.k12_codigo = k153_slip
                                                                           and k153_slipoperacaotipo not in (1,2,5,6,9,10,13,14)
                                         left join saltes c   on c.k13_conta = corrente.k12_conta
                                         left join saltes d   on d.k13_conta = b.k12_conta
                             	      where corrente.k12_instit = ".db_getsession("DB_instit")." and
                                    corrente.k12_data = '$datai' $seleciona_conta $seleciona
                                   ) as xx
                             ) as xxx
                                  inner join conplanoexe   e on entrou = e.c62_reduz
                                                      and e.c62_anousu = ".db_getsession('DB_anousu')."
                                  inner join conplanoreduz i on e.c62_reduz  = i.c61_reduz and
                        	                                e.c62_anousu = i.c61_anousu
                                  inner join conplano      f on i.c61_codcon = f.c60_codcon and
                                                                i.c61_anousu = f.c60_anousu and
                                                                i.c61_instit = ".db_getsession("DB_instit")."
                                  inner join conplanoexe   g on saiu = g.c62_reduz and
                                                        g.c62_anousu = ".db_getsession('DB_anousu')."
                                  inner join conplanoreduz j on g.c62_reduz  = j.c61_reduz  and
                                                                g.c62_anousu = j.c61_anousu
                                  inner join conplano      h on j.c61_codcon = h.c60_codcon and
                                                                j.c61_anousu = h.c60_anousu
                                                                and j.c61_instit = ".db_getsession("DB_instit")."
                        group by entrou, saiu, k12_estorn
                       ) as x
                  group by k12_conta, k12_dconta, ctarec, descr_ctarec
                 ";

  $resultextraorcamentaria = db_query($sql_rec_ext);

  $sql = "

  select k12_conta,
         c60_descr,
         k12_receit,
         k02_tipo,
         k02_drecei,
         sum(case when k12_estorn = 'f' then round(valor,2) else 0 end) as valor,
         sum(case when k12_estorn = 't' then round(valor,2) else 0 end) as estorno
  from
  (select c60_codsis,
         k12_estorn,
         k12_conta,
         c60_descr,
         k12_receit,
         tabrec.k02_tipo,
         tabrec.k02_drecei,
         sum(round(cornump.k12_valor,2)) as valor
  from corrente
                inner join cornump  on corrente.k12_id      = cornump.k12_id
  				 and corrente.k12_data   = cornump.k12_data
  				 and corrente.k12_autent = cornump.k12_autent
                inner join tabrec  on k12_receit = k02_codigo
  	      inner join conplanoexe on k12_conta = c62_reduz
  	                            and c62_anousu = ".db_getsession('DB_anousu')."
  	      inner join conplanoreduz on c62_reduz = c61_reduz and c61_anousu=c62_anousu
  	      inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu
           where corrente.k12_instit = ".db_getsession('DB_instit')." and
  	       corrente.k12_data  = '".$datai."' $seleciona_conta $seleciona
  group by c60_codsis,
           k12_estorn,
           k12_conta,
        	 c60_descr,
           k12_receit,
           tabrec.k02_tipo,
           tabrec.k02_drecei) as x
  group by k12_conta,
           c60_descr,
           k12_receit,
           k02_tipo,
           k02_drecei
  order by k02_tipo,k12_conta,c60_descr,k12_receit;

  ";

  $resultreceitas = db_query($sql);

  $numlin = pg_numrows($resultreceitas);
  $total_valor = 0;
  $total_estorno = 0;
  $quebra = 0;
  $total_banco = 0;
  $total_geral = 0;
  for ($i = 0; $i < $numlin; $i ++) {
  	db_fieldsmemory($resultreceitas, $i);
  	if ($k02_tipo == 'O') {
  		if ($quebra != $k12_conta) {
  			$pdf->SetFont('Arial', 'B', 8);
  			$pdf->cell(10, $alt, $k12_conta, "LTB", 0, "R", 0);
  			$pdf->cell(102, $alt, '- '.$c60_descr, "RTB", 0, "L", 0);
  			$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  			$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  			$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  			$pdf->cell(20, $alt, '', 1, 1, "R", 0);
  			$quebra = $k12_conta;
  			$pdf->SetFont('Arial', '', 6);
  		}

  		$pdf->cell(10, $alt, $k12_receit, "LTB", 0, "R", 0);
  		$pdf->cell(102, $alt, '- '.$k02_drecei, "RTB", 0, "L", 0);
  		$pdf->cell(20, $alt, db_formatar($valor, 'f'), 1, 0, "R", 0);
  		$pdf->cell(20, $alt, db_formatar($estorno, 'f'), 1, 0, "R", 0);
  		$pdf->cell(20, $alt, db_formatar($valor + $estorno, 'f'), 1, 0, "R", 0);
  		$total_valor += $valor;
  		$total_estorno += $estorno;
  		$total_banco += $valor + $estorno;
  		$total_geral += $valor + $estorno;
  		if ($i +1 == $numlin) {
  			$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  			$total_banco = 0;
  		}
  		elseif ($quebra != pg_result($resultreceitas, $i +1, "k12_conta")) {
  			$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  			$total_banco = 0;
  		} else {
  			$pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 1, "R", 0);
  		}
  	}
  }

  $pdf->SetFont('Arial', 'B', 6);
  //$pdf->SetTextColor(0,100,255);
  $pdf->cell(112, $alt, 'TOTAL', 1, 0, 'L', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 1, 'R', 0);

  //RECEITAS EXTRA-OR�AMENT�RIAS

  $pdf->SetTextColor(0);

  if ($quebrarpag == 'N') {
  	$pdf->ln();
  } else {
  	$pdf->addpage();
  }

  //$pdf->SetTextColor(0,100,255);
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(192, 6, "RECEITAS EXTRA-OR�AMENT�RIAS", 1, 1, "L", 0);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->cell(112, $alt, 'DESCRI��O', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'VALOR', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'ESTORNO', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL CONTA', 1, 1, 'C', 0);
  $pdf->SetTextColor(0);
  $saldo_anterior = 0;
  $saldo_debitado = 0;
  $saldo_creditado = 0;
  $saldo_atual = 0;
  $pdf->SetTextColor(0);
  $pdf->SetFont('Arial', 'b', 6);

  $numlin = pg_numrows($resultreceitas);
  $total_valor = 0;
  $total_estorno = 0;
  $quebra = 0;
  $total_banco = 0;
  $total_geral = 0;
  for ($i = 0; $i < $numlin; $i ++) {
  	db_fieldsmemory($resultreceitas, $i);

  	if ($k02_tipo == 'E') {
  		if ($quebra != $k12_conta) {
  			$pdf->SetFont('Arial', 'B', 8);
  			$pdf->cell(10, $alt, $k12_conta, "LTB", 0, "R", 0);
  			$pdf->cell(102, $alt, '- '.$c60_descr, "RTB", 0, "L", 0);
  			$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  			$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  			$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  			$pdf->cell(20, $alt, '', 1, 1, "R", 0);
  			$quebra = $k12_conta;
  			$pdf->SetFont('Arial', '', 6);
  		}

  		$pdf->cell(10, $alt, $k12_receit, "LTB", 0, "R", 0);
  		$pdf->cell(102, $alt, '- '.$k02_drecei, "RTB", 0, "L", 0);
  		$pdf->cell(20, $alt, db_formatar($valor, 'f'), 1, 0, "R", 0);
  		$pdf->cell(20, $alt, db_formatar($estorno, 'f'), 1, 0, "R", 0);
  		$pdf->cell(20, $alt, db_formatar($valor + $estorno, 'f'), 1, 0, "R", 0);
  		$total_valor += $valor;
  		$total_estorno += $estorno;
  		$total_banco += $valor + $estorno;
  		$total_geral += $valor + $estorno;
  		if ($i +1 == $numlin) {
  			$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  			$total_banco = 0;
  		}
  		elseif ($quebra != pg_result($resultreceitas, $i +1, "k12_conta")) {
  			$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  			$total_banco = 0;
  		} else {
  			$pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 1, "R", 0);
  		}
  	}
  }


  $numlin = pg_numrows($resultextraorcamentaria);
  $quebra = 0;
  for ($z = 0; $z < $numlin; $z ++) {
  	db_fieldsmemory($resultextraorcamentaria, $z);

  	if ($quebra != $k12_conta) {
  		$pdf->SetFont('Arial', 'B', 8);
  		$pdf->cell(10, $alt, $k12_conta, "LTB", 0, "R", 0);
  		$pdf->cell(102, $alt, '- '.$k12_dconta, "RTB", 0, "L", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 1, "R", 0);
  		$quebra = $k12_conta;
  		$pdf->SetFont('Arial', '', 6);
  	}

  	$pdf->cell(10, $alt, $ctarec, "LTB", 0, "R", 0);
  	$pdf->cell(102, $alt, '- '.$descr_ctarec, "RTB", 0, "L", 0);
  	$pdf->cell(20, $alt, db_formatar($valor, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($estorno, 'f'), 1, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($valor + $estorno, 'f'), 1, 0, "R", 0);

  	$total_valor   += $valor;
  	$total_estorno += $estorno;
  	$total_banco   += $valor + $estorno;
    $total_geral   += $valor + $estorno;

  	if ($z +1 == $numlin) {
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	} elseif ($quebra != pg_result($resultextraorcamentaria, $z +1, "k12_conta")) {
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	} else {
  		$pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 1, "R", 0);
  	}
  }


  $pdf->SetFont('Arial', 'B', 6);
  $pdf->cell(112, $alt, 'TOTAL', 1, 0, 'L', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 1, 'R', 0);



  //DESPESAS OR�AMENT�RIAS

  $pdf->SetTextColor(0);

  if ($quebrarpag == 'N') {
  	$pdf->ln();
  } else {
  	$pdf->addpage();
  }

  //$pdf->SetTextColor(0,100,255);
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(192, 6, "DESPESAS OR�AMENT�RIAS", 1, 1, "L", 0);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->cell(112, $alt, 'DESCRI��O', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'VALOR', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'ESTORNO', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL CONTA', 1, 1, 'C', 0);
  $pdf->SetTextColor(0);
  $pdf->SetFont('Arial', '', 6);
  $numlin = pg_numrows($resultdespesaorca);
  $total_valor = 0;
  $total_estorno = 0;
  $total_banco = 0;
  $quebra = 0;

  for ($i = 0; $i < $numlin; $i ++) {
  	db_fieldsmemory($resultdespesaorca, $i);
  	/*   if($quebra != $k12_conta){
  	      $pdf->SetFont('Arial','B',8);
  	      $pdf->cell(10,$alt,$k12_conta,"LTB",0,"R",0);
  	      $pdf->cell(102,$alt,'- '.$c60_descr,"RTB",0,"L",0);
  	      $pdf->cell(20,$alt,'',1,0,"R",0);
  	      $pdf->cell(20,$alt,'',1,0,"R",0);
  	      $pdf->cell(20,$alt,'',1,0,"R",0);
  	      $pdf->cell(20,$alt,'',1,1,"R",0);
  	      $quebra = $k12_conta;
  	      $pdf->SetFont('Arial','',6);
  	   }*/
  	$pdf->cell(10, $alt, $k12_conta, "LTB", 0, "R", 0);
  	$pdf->cell(102, $alt, '- '.$c60_descr, "TB", 0, "L", 0);
  	$pdf->cell(20, $alt, db_formatar($valor, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($estorno, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($valor + $estorno, 'f'), 1, 0, "R", 0);
  	$total_valor += $valor;
  	$total_estorno += $estorno;

  	$total_banco += $valor + $estorno;
  	if ($i +1 == $numlin) {
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	}
  	elseif ($quebra != pg_result($resultdespesaorca, $i +1, "k12_conta")) {
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	} else {
  		$pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 1, "R", 0);
  	}
  }

  $pdf->SetFont('Arial', 'B', 6);
  //$pdf->SetTextColor(0,100,255);
  $pdf->cell(112, $alt, 'TOTAL', 1, 0, 'L', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 1, 'R', 0);
  $pdf->SetTextColor(0);

  //DESPESAS EXTRA-OR�AMENT�RIAS
  if ($quebrarpag == 'N') {
  	$pdf->ln();
  } else {
  	$pdf->addpage();
  }
  //$pdf->SetTextColor(0,100,255);
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(192, 6, "DESPESAS EXTRA-OR�AMENT�RIAS", 1, 1, "L", 0);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->cell(112, $alt, 'DESCRI��O', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'VALOR', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'ESTORNO', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'TOTAL', 1, 0, 'C', 0);
  $pdf->cell(20, $alt, 'BANCO', 1, 1, 'C', 0);
  $pdf->SetTextColor(0);
  $saldo_anterior = 0;
  $saldo_debitado = 0;
  $saldo_creditado = 0;
  $saldo_atual = 0;
  $pdf->SetFont('Arial', '', 6);

  //echo 'Despesa Extra-Or�amentaria';
  //db_criatabela($resultdespesaextra);

  $numlin = pg_numrows($resultdespesaextra);
  $total_valor = 0;
  $total_estorno = 0;
  $total_banco = 0;
  $quebra = 0;
  $tipo = null;
  for ($i = 0; $i < $numlin; $i ++) {
  	db_fieldsmemory($resultdespesaextra, $i);
  	$sql = "select k13_conta
  	           from saltes
  	             inner join conplanoexe on c62_reduz = k13_reduz and
  		                              c62_anousu = ".db_getsession('DB_anousu')."
  	             inner join conplanoreduz on c62_reduz = c61_reduz and
  		                                c61_anousu = c62_anousu and
  		                                c61_instit = ".db_getsession('DB_instit')."
  		   where k13_conta = $corr_saltes
  		   union all
  		   select k13_conta
  		   from saltes
  	             inner join conplanoexe on c62_reduz = k13_reduz and
  		                              c62_anousu = ".db_getsession('DB_anousu')."
  	             inner join conplanoreduz on c62_reduz = c61_reduz and
  		                                c61_anousu = c62_anousu and
  		                                c61_instit = ".db_getsession('DB_instit')."
  		   where k13_conta = $corl_saltes";
  	$result = db_query($sql);
  	//echo "$sql<br>";exit;
  	//if ($result == false || pg_numrows($result) == 2)
  	if ($result == false || $tipo != 'desp') {
  		continue;
  	}

  	if ($quebra != $corrente) {
  		$pdf->SetFont('Arial', 'B', 8);
  		$pdf->cell(10, $alt, $corrente, "LTB", 0, "R", 0);
  		$pdf->cell(102, $alt, '- '.$descr_conta, "RTB", 0, "L", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 0, "R", 0);
  		$pdf->cell(20, $alt, '', 1, 1, "R", 0);
  		$quebra = $corrente;
  		$pdf->SetFont('Arial', '', 6);
  	}
  	$pdf->cell(10, $alt, $corlanc, "LTB", 0, "R", 0);
  	$pdf->cell(102, $alt, '- '.$descr_receita, "RTB", 0, "L", 0);
  	$pdf->cell(20, $alt, db_formatar($valor, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($estorno, 'f'), 1, 0, "R", 0);
  	$pdf->cell(20, $alt, db_formatar($valor + $estorno, 'f'), 1, 0, "R", 0);
  	$total_banco += $valor + $estorno;
  	if ($i +1 == $numlin) {
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	}
  	elseif ($quebra != pg_result($resultdespesaextra, $i +1, "corrente")) {
  		$pdf->cell(20, $alt, db_formatar($total_banco, 'f'), 1, 1, "R", 0);
  		$total_banco = 0;
  	} else {
  		$pdf->cell(20, $alt, db_formatar(0, 'f'), 1, 1, "R", 0);
  	}
  	$total_valor += $valor;
  	$total_estorno += $estorno;
  }

  $pdf->SetFont('Arial', 'B', 6);
  //$pdf->SetTextColor(0,100,255);
  $pdf->cell(112, $alt, 'TOTAL', 1, 0, 'L', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 0, 'R', 0);
  $pdf->cell(20, $alt, db_formatar($total_valor + $total_estorno, 'f'), 1, 1, 'R', 0);

  $pdf->cell(192, 5, '', 1, 1, 'R', 0);

}
/**
 *
 */
if( $pdf->gety() > ( $pdf->h - 50 ) ){
	$pdf->addpage();
}

$pdf->SetTextColor(0);

$ass_sec_original = $classinatura->assinatura(1002, "");

$tes = "______________________________"."\n\n"."Tesoureiro";
$sec = "______________________________"."\n\n"."Secretaria da Fazenda";
$cont = "______________________________"."\n\n"."Contador";
$pref = "______________________________"."\n\n"."Prefeito";
$ass_pref = $classinatura->assinatura(1000, $pref);
//$ass_pref = $classinatura->assinatura_usuario();
$ass_sec = $classinatura->assinatura(1002, $sec);
$ass_tes = $classinatura->assinatura(1004, $tes);
$ass_cont = $classinatura->assinatura(1005, $cont);

//echo $ass_pref;
$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref, 0);
if( $pdf->gety() > ( $pdf->h - 30 ) ){
	$pdf->addpage();
}

$largura = ($pdf->w) / 3;
$pdf->ln(10);

$sqlparagpadrao  = " select db61_texto ";
$sqlparagpadrao .= " from db_documentopadrao ";
$sqlparagpadrao .= "     inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
$sqlparagpadrao .= "     inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
$sqlparagpadrao .= "     inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
$sqlparagpadrao .= " where db60_tipodoc = 1508 and db60_instit = " . db_getsession("DB_instit") . " order by db62_ordem";

$pdf->h -= 50;

$resparagpadrao = @db_query($sqlparagpadrao);
if (@pg_numrows($resparagpadrao) > 0) {
    db_fieldsmemory($resparagpadrao, 0);
    // echo $db61_texto;
    if ($pdf->gety() > ($pdf->h - 70)) {
        $pdf->addpage();
    }
    @eval($db61_texto);
}

$pdf->Output();
?>