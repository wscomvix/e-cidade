<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


include("fpdf151/pdf.php");
include ("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//echo($HTTP_SERVER_VARS["QUERY_STRING"]); exit;

if($imprime_analitico=="a"){
	$head1 = "EXTRATO BANC�RIO ANAL�TICO";
}else{
	$head1 = "EXTRATO BANC�RIO SINT�TICO";
}

$head3 = "PER�ODO : ".db_formatar(@$datai,"d")." A ".db_formatar(@$dataf,"d");

if ($somente_contas_bancarias == "s") {
	$head4 = "SOMENTE CONTAS BANC�RIAS";
}

if($agrupapor == 2){
	$head5 = "AGRUPAMENTO: PELA CONTA DE RECEITA";
}
if($agrupapor == 3){
	$head5 = "AGRUPAMENTO: PELOS C�DIGOS DE EMPENHO E RECEITA";
}
if($receitaspor == 1){
	$head6 = "BAIXA BANC�RIA: N�O AGRUPADO PELA CLASSIFICA��O";
}
if($receitaspor == 2){
	$head6 = "BAIXA BANC�RIA: AGRUPADO PELA CLASSIFICA��O";
}

if ($exibir_retencoes == 'n') {
    $head7 = "N�O EXIBIR RETEN��ES";
}


/// CONTAS MOVIMENTO
$sql ="	    select   k13_reduz,
                     k13_descr,
		     k13_dtimplantacao,
                     c60_estrut,
                     c60_codsis,
	                   c63_conta,
                       c63_dvconta,
                       c63_agencia,
                       c63_dvagencia,
	                   substr(fc_saltessaldo,2,13)::float8 as anterior,
	                   substr(fc_saltessaldo,15,13)::float8 as debitado ,
	                   substr(fc_saltessaldo,28,13)::float8 as creditado,
	                   substr(fc_saltessaldo,41,13)::float8 as atual
            	from (
 	                  select k13_reduz,
 	                         k13_descr,
				 k13_dtimplantacao,
	                         c60_estrut,
		                       c60_codsis,
		                       c63_conta,
                               c63_dvconta,
                               c63_agencia,
                               c63_dvagencia,
	                         fc_saltessaldo(k13_reduz,'".$datai."','".$dataf."',null," . db_getsession("DB_instit") . ")
	                  from   saltes
	                         inner join conplanoexe   on k13_reduz = c62_reduz
		                                              and c62_anousu = ".db_getsession('DB_anousu')."
		                     inner join conplanoreduz on c61_anousu=c62_anousu and c61_reduz = c62_reduz and c61_instit = " . db_getsession("DB_instit") . "
	                         inner join conplano      on c60_codcon = c61_codcon and c60_anousu=c61_anousu
	                         left  join conplanoconta on c60_codcon = c63_codcon and c63_anousu=c60_anousu ";
if($conta != "") {
	$sql .= "where c61_reduz in $conta ";
}

if ($conta != "" && $somente_contas_bancarias == "s"){
	$sql .= " and c60_codsis = 6 ";
} else if ($somente_contas_bancarias == "s"){
	$sql .= "where c60_codsis = 6 ";
}
$sql .= "  ) as x ";
// verifica se � pra selecionar somente as contas com movimeto
if ($somente_contas_com_movimento=='s'){
	//$sql.=" where (debitado > 0 or creditado > 0)  ";
}
$sql .= " order by substr(k13_descr,1,3),k13_reduz ";
// die($sql);
//echo "2 ".$sql; exit;
$resultcontasmovimento = db_query($sql);

if(pg_numrows($resultcontasmovimento) == 0){
	db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem dados neste periodo.');
}

$saldo_dia_credito = 0;
$saldo_dia_debito = 0;


$aContas = array();

$numrows = pg_numrows($resultcontasmovimento);
for($linha=0;$linha<$numrows;$linha++){

	db_fieldsmemory($resultcontasmovimento,$linha);
	if (($somente_contas_com_movimento=='s') && $debitado == 0 && $creditado == 0) {
		continue;
	}

	// escreve a conta e a descri��o + saldo inicial
	$aContas[$k13_reduz]->k13_reduz = $k13_reduz;
	$aContas[$k13_reduz]->k13_descr = $k13_descr;
    $aContas[$k13_reduz]->c63_conta = $c63_conta.'-'.$c63_dvconta;
    $aContas[$k13_reduz]->c63_agencia = $c63_agencia.'-'.$c63_dvagencia;
	$aContas[$k13_reduz]->k13_dtimplantacao = $k13_dtimplantacao;

	// para contas banc�rias, saldo positivo = debito, negativos indica debito
	if ($anterior > 0 ){
		$aContas[$k13_reduz]->debito 	= $anterior;
		$aContas[$k13_reduz]->credito = 0;
	} else {
		$aContas[$k13_reduz]->credito = $anterior;
		$aContas[$k13_reduz]->debito	= 0;
	}
	// ****************** ANALITICO e sintetico****************


	// *********************  EMPENHO ***************************
	$sqlempenho = "
  /* empenhos- despesa or�amentaria */
  /*   EMPENHO */

  select distinct
        corrente.k12_id as caixa,
        corrente.k12_data as data,
		0 as valor_debito,
		corrente.k12_valor as valor_credito,
	    'Pgto. Emp. '||e60_codemp||'/'||e60_anousu::text||' OP: '||coremp.k12_codord::text as tipo_movimentacao,
        e60_codemp||'/'||e60_anousu::text as codigo,
        'empenho'::text as tipo,
        0 as receita,
		null::text as receita_descr,
		corhist.k12_histcor::text as historico,
		case
            when e86_cheque is not null and e86_cheque <> '0' then 'CHE '||e86_cheque::text
            when coremp.k12_cheque = 0 then e81_numdoc::text
            else 'CHE '||coremp.k12_cheque::text
            end as numdoc,
		    null::text as contrapartida,
		    coremp.k12_codord as ordem,
		    z01_nome::text as credor,
		    z01_numcgm::text as numcgm,
		    k12_codautent,
		    k105_corgrupotipo,
		    '' as codret,
			  '' as dtretorno,
			  '' as arqret,
				'' as dtarquivo,
			 0 as k153_slipoperacaotipo
 from corrente
      inner join coremp on coremp.k12_id = corrente.k12_id and coremp.k12_data   = corrente.k12_data
                                                           and coremp.k12_autent = corrente.k12_autent
      inner join empempenho on e60_numemp = coremp.k12_empen
      inner join cgm on z01_numcgm = e60_numcgm
	  left join empord on e82_codord = coremp.k12_codord
	  left join empageconfche on e91_codcheque = e82_codmov
	    left join corhist on  corhist.k12_id     = corrente.k12_id    and corhist.k12_data   = corrente.k12_data  and
					                                                              corhist.k12_autent = corrente.k12_autent
      left join corautent	on corautent.k12_id     = corrente.k12_id   and corautent.k12_data   = corrente.k12_data
							                                                        and corautent.k12_autent = corrente.k12_autent
      left join corgrupocorrente on corrente.k12_data = k105_data and corrente.k12_id = k105_id and corrente.k12_autent = k105_autent
	  left join corempagemov 	on corempagemov.k12_data = coremp.k12_data and corempagemov.k12_id = coremp.k12_id and corempagemov.k12_autent = coremp.k12_autent
	  left join empagemov       on k12_codmov  = e81_codmov
      left join empageconf ON empageconf.e86_codmov = empagemov.e81_codmov
 where corrente.k12_conta = $k13_reduz  and corrente.k12_data between '".$datai."'
                                        and '".$dataf."'
                                        and corrente.k12_instit = ".db_getsession("DB_instit")."


";
    if ($exibir_retencoes == 'n') {
        $sqlempenho .= condicao_retencao('empenho');
    }

	$sqlanalitico = "
  /* RECIBO */

 select
       caixa,
		   data,
		   valor_debito,
		   valor_credito,
		   tipo_movimentacao,
		   codigo,
		   tipo,
		   receita,
       receita_descr,
		   historico,
		   numdoc,
		   contrapartida,
       ordem,
		   credor,
		   ''::text as numcgm,
		   k12_codautent,
		   0 as k105_corgrupotipo,
		   '' as codret,
			 '' as dtretorno,
			 '' as arqret,
			 '' as dtarquivo,
			 0 as k153_slipoperacaotipo
	     from (
      	     select
	                 caixa,
		               data,
		               sum(valor_debito) as valor_debito,
		               valor_credito,
		               tipo_movimentacao::text,
		               codigo::text,
		               tipo::text,
		               receita,
                   receita_descr::text,
		               historico::text,
		               numdoc::text,
		               null::text as contrapartida,
		               ordem,
		               credor::text,
		               k12_codautent
	          from (
                  select
	                      corrente.k12_id as caixa,
                        corrente.k12_data as data,
		                    cornump.k12_valor as valor_debito,
		                    0 as valor_credito,
	                      ('Recibo '||k12_numpre||'-'||k12_numpar)::text
	                       as tipo_movimentacao,
			                  k12_numpre||'-'||k12_numpar::text as codigo,
                        'recibo'::text as tipo,
		                    cornump.k12_receit as receita,
			                  tabrec.k02_drecei::text as receita_descr,
			                  (coalesce(corhist.k12_histcor,'.'))::text as historico,
			                  null::text as numdoc,
			                  null::text as contrapartida,
			                  e20_pagordem as ordem,
		                   (select z01_nome::text from arrepaga inner join cgm on z01_numcgm = k00_numcgm where k00_numpre=cornump.k12_numpre limit 1 ) as credor,			  k12_codautent
                   from corrente
                       inner join cornump on cornump.k12_id = corrente.k12_id and cornump.k12_data = corrente.k12_data
                                                                                    and cornump.k12_autent = corrente.k12_autent
                       left join corgrupocorrente on corrente.k12_id    = k105_id
                                         and corrente.k12_autent = k105_autent and corrente.k12_data = k105_data
                       left join retencaocorgrupocorrente     on e47_corgrupocorrente  = k105_sequencial
                       left join retencaoreceitas             on e47_retencaoreceita   = e23_sequencial
                       left join retencaopagordem             on e23_retencaopagordem  = e20_sequencial
                       inner join tabrec on tabrec.k02_codigo   = cornump.k12_receit
		                   left join corhist on  corhist.k12_id     = corrente.k12_id and corhist.k12_data     = corrente.k12_data
                                                                                  and corhist.k12_autent   = corrente.k12_autent
				               left join corautent	on corautent.k12_id = corrente.k12_id and corautent.k12_data   = corrente.k12_data
							                                                                    and corautent.k12_autent = corrente.k12_autent
                       left  join corcla on corcla.k12_id = corrente.k12_id and corcla.k12_data   = corrente.k12_data
                                                                            and corcla.k12_autent = corrente.k12_autent
                       left join corplacaixa on corrente.k12_id  = k82_id and corrente.k12_data  = k82_data
                                                                          and corrente.k12_autent= k82_autent
	                 where corrente.k12_conta = $k13_reduz
                     and (corrente.k12_data between '".$datai."'  and '".$dataf."')
		                 and corrente.k12_instit = ".db_getsession("DB_instit")."
                     and k12_codcla is null
                     and k82_seqpla is null ";

    if ($exibir_retencoes == 'n') {
        $sqlanalitico .= condicao_retencao('recibo');
    }

    $sqlanalitico .= " ) as x
		group by
		       caixa,
			   data,
			   valor_credito,
			   tipo_movimentacao,
			   codigo,
			   tipo,
			   receita,
               receita_descr,
			   historico,
			   numdoc,
			   contrapartida,
			   ordem,
			   credor,
               k12_codautent
             ) as xx


/* PLANILHA */
union all

	     select
             caixa,
             data,
      		   valor_debito,
		         valor_credito,
		         tipo_movimentacao,
		         codigo,
		         tipo,
		         receita,
		         receita_descr,
		         historico,
		         numdoc,
             contrapartida,
		         ordem,
		         credor,
		         ''::text as numcgm,
		         k12_codautent,
			 0 as k105_corgrupotipo,
		         '' as codret,
						 '' as dtretorno,
						 '' as arqret,
						 '' as dtarquivo,
			0 as k153_slipoperacaotipo
	     from (
	           select
	                 caixa,
		               data,
		               sum(valor_debito) as valor_debito,
		               valor_credito,
		               tipo_movimentacao::text,
		               codigo::text,
		               tipo::text,
		               receita,
		               receita_descr::text,
		               historico::text,
		               numdoc::text,
		               null::text as contrapartida,
		               ordem,
		               credor::text	,
                    ". (($imprime_analitico=="a")?"k12_codautent":"null::text as k12_codautent") . "

	           from (
                  select
	                       corrente.k12_id as caixa,
                         corrente.k12_data as data,
                         case when k12_valor > 0 then k12_valor else 0 end as valor_debito,
                         case when k12_valor < 0 then k12_valor else 0 end as valor_credito,
	                       ('planilha :'||k81_codpla)::text as tipo_movimentacao,
			                   k81_codpla::text as codigo,
           	             'planilha'::text as tipo,
		   	                 k81_receita as receita,
                         tabrec.k02_drecei as receita_descr,
		                     (coalesce(placaixarec.k81_obs,'.'))::text as historico,
		                     null::text as numdoc,
			                   null::text as contrapartida,
		                     0 as ordem,
			                   null::text as credor ,
	                       k12_codautent
                  from corrente
			                 	inner join corplacaixa on k12_id = k82_id  and k12_data   = k82_data
                                                                   and k12_autent = k82_autent
                				inner join placaixarec on k81_seqpla = k82_seqpla
			                  inner join tabrec on tabrec.k02_codigo = k81_receita
		                     /*
		                      left  join arrenumcgm on k00_numpre = cornump.k12_numpre
                          left join cgm on k00_numcgm = z01_numcgm
                        */
	                     left join corhist on corhist.k12_id = corrente.k12_id     and corhist.k12_data  = corrente.k12_data
                                                                                 and  corhist.k12_autent = corrente.k12_autent
			                 inner join corautent on corautent.k12_id = corrente.k12_id and corautent.k12_data   = corrente.k12_data
                                                                                 and corautent.k12_autent = corrente.k12_autent
           			where corrente.k12_conta = $k13_reduz  and (corrente.k12_data between '".$datai."'  and '".$dataf."')
		                                                   and corrente.k12_instit = ".db_getsession("DB_instit")."

              ) as x
		group by
		       caixa,
			     data,
			     valor_credito,
			     tipo_movimentacao,
			     codigo,
			     tipo,
			     receita,
			     receita_descr,
			     historico,
           numdoc,
	         contrapartida,
			     ordem,
			     credor,
			     k12_codautent
             ) as xx

/*  BAIXA DE BANCO */

union all

      select
             caixa,
		         data,
		         valor_debito,
		         valor_credito,
		         tipo_movimentacao,
		         codigo,
		         tipo,
		         receita,
             receita_descr,
		         historico,
		         numdoc,
		         contrapartida,
             ordem,
		         credor,
		         ''::text as numcgm,
		         k12_codautent,
			 0 as k105_corgrupotipo,
		         codret::text,
			       dtretorno::text,
			       arqret::text,
			     dtarquivo::text,
			 0 as k153_slipoperacaotipo
     from (
	         select
	                caixa,
      		        data,
		              sum(valor_debito) as valor_debito,
		              valor_credito,
		              tipo_movimentacao::text,
		              codigo::text,
		              tipo::text,
		              receita,
                  receita_descr::text,
		              historico::text,
		              numdoc::text,
		              null::text as contrapartida,
		              ordem,
		              credor::text,
		              k12_codautent,
		              codret,
			            dtretorno,
			            arqret,
			            dtarquivo
	          from (
                  select
	                      corrente.k12_id as caixa,
                        corrente.k12_data as data,
		                    (cornump.k12_valor::float - round(coalesce((select sum(vlrrec) from disrec_desconto_integral  a
								inner join discla d on d.codcla  =  a.codcla  
								where dtaute = corrente.k12_data and a.k00_receit = cornump.k12_receit 
								and a.codcla = discla.codcla 
								),0),2)::float) as  valor_debito,
		                    0 as valor_credito,
	                      ('Baixa da banco ')::text as tipo_movimentacao,
		                     discla.codret as codigo,
                        'baixa'::text as tipo,
		                    cornump.k12_receit as receita,
		                    tabrec.k02_drecei::text as receita_descr,
		                    (coalesce(corhist.k12_histcor,'.'))::text as historico,
		                    null::text as numdoc,
		                    null::text as contrapartida,
			                  0 as ordem,
			                  disarq.codret as codret,
			                  disarq.dtretorno as dtretorno,
			                  disarq.arqret as arqret,
			                  disarq.dtarquivo as dtarquivo,
		                    (select z01_nome::text from recibopaga inner join cgm on z01_numcgm = k00_numcgm where k00_numpre=cornump.k12_numpre limit 1 ) as credor,k12_codautent
                 from corrente
                      inner join cornump on cornump.k12_id = corrente.k12_id and cornump.k12_data   = corrente.k12_data
                                                                             and cornump.k12_autent = corrente.k12_autent
                      inner join tabrec on tabrec.k02_codigo = cornump.k12_receit

	                 	   /*
                         left  join arrenumcgm on k00_numpre = cornump.k12_numpre
                         left join cgm on k00_numcgm = z01_numcgm
                      */

	                   left join corhist   on corhist.k12_id   = corrente.k12_id  and	corhist.k12_data     = corrente.k12_data
                                                                                and corhist.k12_autent   = corrente.k12_autent
                  	 left join corautent on corautent.k12_id = corrente.k12_id
                                        and corautent.k12_data   = corrente.k12_data
                                        and corautent.k12_autent = corrente.k12_autent

		                 inner join corcla    on corcla.k12_id    = corrente.k12_id  and corcla.k12_data      = corrente.k12_data
                                                                                and corcla.k12_autent    = corrente.k12_autent
                     inner join discla on discla.codcla = corcla.k12_codcla and discla.instit = ".db_getsession("DB_instit")."
           					 inner join disarq on disarq.codret = discla.codret and disarq.instit = discla.instit
                     left join corplacaixa on corplacaixa.k82_id     = corrente.k12_id
                                          and corplacaixa.k82_data   = corrente.k12_data
                                          and corplacaixa.k82_autent = corrente.k12_autent
			          where corrente.k12_conta = $k13_reduz
                  and (corrente.k12_data between '".$datai."'  and '".$dataf."')
                  and corrente.k12_instit = ".db_getsession("DB_instit")."

                  and corplacaixa.k82_id is null
                  and corplacaixa.k82_data is null
                  and corplacaixa.k82_autent is null

              ) as x where valor_debito <> 0
		group by
		           caixa,
		      	   data,
			         valor_credito,
			         tipo_movimentacao,
			         codigo,
			         tipo,
			         receita,
               receita_descr,
			         historico,
			         numdoc,
			         contrapartida,
			         ordem,
			         credor,
               k12_codautent,
               codret,
			         dtretorno,
			         arqret,
			         dtarquivo
             ) as xx

";
	//  SINTETICO
	$sqlsintetico = "
   union all
  select caixa,
       data,
       valor_debito,
       valor_credito,
       null::text as tipo_movimentacao,
       codigo,
       tipo,
       0 as receita,
       null::text as receita_descr,
       historico,
       numdoc,
       contrapartida,
       ordem,
       credor,
       ''::text as numcgm,
       k12_codautent,
       0 as k105_corgrupotipo,
       '' as codret,
			 '' as dtretorno,
			 '' as arqret,
			 '' as dtarquivo,
	    0 as k153_slipoperacaotipo
from (
select caixa,
       data,
       sum(valor_debito) as valor_debito,
       sum(valor_credito) as valor_credito,
       codigo,
       tipo,
       historico,
       numdoc,
       contrapartida,
       ordem,
       credor,
       k12_codautent
  from ($sqlanalitico) as agrupado
	group by
		caixa,
		data,
	    codigo,
		tipo,
		historico,
		numdoc,
		contrapartida,
		ordem,
		credor,
		k12_codautent
	) as autent_recibo
";
	/* SLIP DEBITO */
	$sqlslip="

 	     union all
	     /* transferencias a debito - entradas*/
	     select
	           corrente.k12_id as caixa,
	           corlanc.k12_data as data,
		   corrente.k12_valor as valor_debito,
		   0 as valor_credito,
		   'Slip '||k12_codigo::text as tipo_movimentacao,
		   k12_codigo::text as codigo,
		   'slip'::text as tipo,
		   0 as receita,
           null::text as receita_descr,
		   slip.k17_texto::text as historico,
		   case when e91_cheque is null then e81_numdoc::text else 'CHE '||e91_cheque::text end as numdoc,
           k17_debito||' - '||c60_descr as contrapartida,
		   0 as ordem,
		   z01_nome::text as credor,
		   z01_numcgm::text as numcgm,
       k12_codautent,
       0 as k105_corgrupotipo,
       '' as codret,
			 '' as dtretorno,
			 '' as arqret,
			 '' as dtarquivo,
			 sliptipooperacaovinculo.k153_slipoperacaotipo
	     from corlanc
	           inner join corrente on corrente.k12_id  = corlanc.k12_id    and
		                          corrente.k12_data  = corlanc.k12_data  and
					 									 corrente.k12_autent = corlanc.k12_autent

           inner join slip on slip.k17_codigo = corlanc.k12_codigo
		   inner join conplanoreduz on c61_reduz  = slip.k17_credito
                                       and c61_anousu = ".db_getsession('DB_anousu')."
               inner join conplano      on c60_codcon = c61_codcon
                                       and c60_anousu = c61_anousu

		   left join slipnum on slipnum.k17_codigo = slip.k17_codigo
		   left join cgm on slipnum.k17_numcgm = z01_numcgm
           left join sliptipooperacaovinculo on sliptipooperacaovinculo.k153_slip=slip.k17_codigo

		   left join corconf on corconf.k12_id = corlanc.k12_id 				and
		                        corconf.k12_data = corlanc.k12_data 		and
														corconf.k12_autent = corlanc.k12_autent and
														corconf.k12_ativo is true
                   left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov and
                   													  corconf.k12_ativo is true
                   													  and empageconfche.e91_ativo is true
                   left join corhist on   corhist.k12_id     = corrente.k12_id    and
		                          corhist.k12_data   = corrente.k12_data  and
					  corhist.k12_autent = corrente.k12_autent
					left join corautent	on corautent.k12_id     = corrente.k12_id
									and corautent.k12_data   = corrente.k12_data
									and corautent.k12_autent = corrente.k12_autent
					left join corempagemov on corempagemov.k12_data = corautent.k12_data
										   and corempagemov.k12_id = corautent.k12_id
										   and corempagemov.k12_autent = corautent.k12_autent
					left join empagemov on corempagemov.k12_codmov = e81_codmov
			     where corlanc.k12_conta = $k13_reduz  and
	           corlanc.k12_data between '".$datai."'  and '".$dataf."'

	     union all
/* SLIP CREDITO */

	     select distinct on (data, caixa, k12_codautent, codigo)
	           corrente.k12_id as caixa,
	           corlanc.k12_data as data,
		   0                  as valor_debito,
		   corrente.k12_valor as valor_credito,
		   'Slip '||k12_codigo::text as tipo_movimentacao,
		   k12_codigo::text as codigo,
		   'slip'::text as tipo,
		   0 as receita,
		   null::text as receita_descr,
		   slip.k17_texto::text as historico,
		   case when e91_cheque is null then e81_numdoc::text else 'CHE '||e91_cheque::text end as numdoc,
		   k17_debito||' - '||c60_descr as contrapartida,
		   0 as ordem,
		   z01_nome::text as credor,
		   z01_numcgm::text as numcgm,
       k12_codautent,
       0 as k105_corgrupotipo,
       '' as codret,
			 '' as dtretorno,
			 '' as arqret,
			 '' as dtarquivo,
			 sliptipooperacaovinculo.k153_slipoperacaotipo
	     from corrente
	           inner join corlanc on corrente.k12_id     = corlanc.k12_id
                                 and corrente.k12_data   = corlanc.k12_data
                                 and corrente.k12_autent = corlanc.k12_autent
		       inner join slip on        slip.k17_codigo = corlanc.k12_codigo
               inner join conplanoreduz    on c61_reduz  = slip.k17_debito
                                          and c61_anousu = ".db_getsession('DB_anousu')."
               inner join conplano         on c60_codcon = c61_codcon
                                          and c60_anousu = c61_anousu
		       left join slipnum on slipnum.k17_codigo = slip.k17_codigo
		       left join cgm on slipnum.k17_numcgm = z01_numcgm
		       left join corconf on corconf.k12_id = corlanc.k12_id
                                and corconf.k12_data = corlanc.k12_data
                                and	corconf.k12_autent = corlanc.k12_autent
                                and corconf.k12_ativo is true
               left join sliptipooperacaovinculo on sliptipooperacaovinculo.k153_slip=slip.k17_codigo
               left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
               												and	corconf.k12_ativo is true
               												and empageconfche.e91_ativo is true
	           left join corhist on corhist.k12_id     = corrente.k12_id
                                and corhist.k12_data   = corrente.k12_data
                                and corhist.k12_autent = corrente.k12_autent
              left join corautent	on corautent.k12_id     = corrente.k12_id
									and corautent.k12_data   = corrente.k12_data
									and corautent.k12_autent = corrente.k12_autent
			  left join empageslip  on empageslip.e89_codigo = slip.k17_codigo
    		  left join empagemov   on e89_codmov=e81_codmov
	     where corrente.k12_conta = $k13_reduz  and
	           corrente.k12_data between '".$datai."'  and '".$dataf."'

	     order by data, caixa, k12_codautent, codigo
";

	//$imprime_analitico = 'a';

	if($imprime_analitico == "a"){
		$sqltotal = $sqlempenho." union all ".$sqlanalitico.$sqlslip;
	}else{
		$sqltotal = $sqlempenho.$sqlsintetico.$sqlslip;
	}
	$sqltotal = $sqlempenho." union all ".$sqlanalitico.$sqlslip;
    // die($sqltotal);
	$resmovimentacao = db_query($sqltotal);
	// echo $sqltotal;
	// db_criatabela($resmovimentacao);exit;
	$quebra_data = '';
	$saldo_dia_final   = $anterior;

	$aContas[$k13_reduz]->data = array();
	$iInd = -1;
	$saldo_dia_debito = 0;
	$saldo_dia_credito = 0;
	//$lPrimeiroDaConta = true;
	if (pg_numrows($resmovimentacao)>0){
		for  ($i=0;$i < pg_numrows($resmovimentacao);$i++){

			db_fieldsmemory($resmovimentacao,$i);

			//quando agrupar os pagamentos o sistema vai retirar as reten��es do relatorio.
            if($pagempenhos==3){
				if (  $ordem > 0 and ($k105_corgrupotipo == 5 or $k105_corgrupotipo == 0 or $k105_corgrupotipo == 2)  ) {
					continue;
				}
			}
			if (isset($considerar_retencoes) && $considerar_retencoes == "n") {
				if ( $ordem > 0 and ( $k105_corgrupotipo == 0 or $k105_corgrupotipo == 2 ) ) {
					continue;
				}
			}

			// controla quebra de saldo por dia
			if ($quebra_data != $data && $quebra_data != '' && $totalizador_diario=='s'){
				$lPrimeiroDaConta = false;
				$aContas[$k13_reduz]->data[$iInd]->saldo_dia_debito 	= $saldo_dia_debito;
				$aContas[$k13_reduz]->data[$iInd]->saldo_dia_credito 	= $saldo_dia_credito;
				// calcula saldo a debito ou credito
				if ($saldo_dia_debito < 0){
					$saldo_dia_final -= abs($saldo_dia_debito);
					$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
				} else {
					$saldo_dia_final += $saldo_dia_debito;
					$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
				}

				if ($saldo_dia_credito < 0){
					$saldo_dia_final += abs($saldo_dia_credito);
					$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
				} else {
					$saldo_dia_final -= $saldo_dia_credito;
					$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
				}
				$saldo_dia_debito  = 0;
				$saldo_dia_credito = 0;

			}

			if($quebra_data != $data){
				$aContas[$k13_reduz]->data[++$iInd]->data = $data;
				$aContas[$k13_reduz]->data[$iInd]->movimentacoes = array();
			}

			$oMovimentacao = new stdClass();
			$oMovimentacao->caixa 				= $caixa;
			$oMovimentacao->valor_debito 	= $valor_debito;
			$oMovimentacao->valor_credito	= $valor_credito;
			$oMovimentacao->receita				= $receita;
			$oMovimentacao->k12_codautent = $k12_codautent;
			$oMovimentacao->codigo 				= $codigo;
			$oMovimentacao->credor 				= $credor;
			$oMovimentacao->codigocredor  = $numcgm;
			$oMovimentacao->codret  			= $codret;
			$oMovimentacao->dtretorno  		= $dtretorno != "" ? db_formatar($dtretorno,'d') : "";
			$oMovimentacao->arqret  			= $arqret;
			$oMovimentacao->dtarquivo  		= $dtarquivo != "" ? db_formatar($dtarquivo,'d') : "";//$dtarquivo;

			$oMovimentacao->tipo	 			= $tipo;
			if($tipo=='planilha'){
				$oMovimentacao->planilha	= $codigo;
			}else{
				$oMovimentacao->planilha	= "";
			}
			if($tipo=='baixa'){
				$oMovimentacao->k12_codcla	= $codigo;
			}else{
				$oMovimentacao->k12_codcla	= "";
			}
			//empenho
			if($tipo=='empenho'){
				$oMovimentacao->empenho		= $codigo;
				$oMovimentacao->ordem 		= $ordem;
			}else{
				$oMovimentacao->empenho		= "";
				$oMovimentacao->ordem 		= $ordem;
			}
			if ($codigo){

				$sql = "select k17_numdocumento from slip
				where k17_codigo = $codigo";
				$numdocumento = db_query($sql);
				if (pg_numrows($numdocumento)>0){
					db_fieldsmemory($numdocumento,0);
				}
				
			}

			$oMovimentacao->numdoc 			= $k17_numdocumento ? $k17_numdocumento:$numdoc;

			if($tipo=='slip'){
				//$pdf->Cell(15,$alt,$codigo,0,0,"C",0);
				$oMovimentacao->slip 		= $codigo;
			}else{
				//$pdf->Cell(15,$alt,"",0,0,"C",0);
				$oMovimentacao->slip 		= "";
			}

			// DEBITO E CREDITO

			if ($valor_debito ==0 &&  $valor_credito != 0  ){
				//$pdf->Cell(20,$alt,'','L',0,"R",0);
				$oMovimentacao->valor_debito = "";
				//Modifica��o feita para acertar a forma quando � mostrada os valores relativos as planilha de dedu��o
				if ($tipo == "planilha") {
					$valor_credito = $valor_credito*-1;
					$oMovimentacao->valor_credito = $valor_credito;
				} else {
					$valor_credito = $valor_credito;
					$oMovimentacao->valor_credito = $valor_credito;
				}

			} elseif ($valor_credito== 0 && $valor_debito != 0 ){
				$oMovimentacao->valor_debito = $valor_debito;
				$oMovimentacao->valor_credito = $valor_credito;
			}
			else {
				$oMovimentacao->valor_debito = $valor_debito;
				$oMovimentacao->valor_credito = $valor_credito;
			}

			if ($receita > 0){
				// selecina reduzido da receita no plano de contas

				$sql = "select c61_reduz
		    						from taborc
		      					inner join orcreceita on o70_codrec=taborc.k02_codrec and o70_anousu=k02_anousu and o70_instit=".db_getsession("DB_instit")."
		      					inner join conplanoreduz on c61_codcon=o70_codfon and c61_instit=o70_instit and c61_anousu=o70_anousu
		    					where  k02_codigo = $receita
		       					 and k02_anousu = ".db_getsession("DB_anousu")."
		    		union
	            	select c61_reduz
		    						from tabplan
		        				inner join conplanoreduz on c61_reduz=k02_reduz and c61_instit=".db_getsession("DB_instit")." and c61_anousu=k02_anousu
		    					where k02_codigo = $receita
		      					and k02_anousu = ".db_getsession("DB_anousu")."
	               	";
				//die ($sql);
				$res_rec = db_query($sql);
				$c61_reduz ="";
				if (pg_numrows($res_rec)>0){
					db_fieldsmemory($res_rec,0);
				}

			}
			//$x1= $pdf->GetX ();

			$oMovimentacao->contrapartida = "";

			if($tipo == 'recibo'  || $tipo == 'planilha' || $tipo == 'baixa'){

				//if($imprime_analitico=="a"){
				if($receita > 0){
					$oMovimentacao->contrapartida = $receita." ";
					if($c61_reduz != ""){
						$oMovimentacao->contrapartida .= "(".$c61_reduz.") - ";
					}
					$oMovimentacao->contrapartida .= $receita_descr;
				}
				//}

			}
			if($tipo == 'slip'){
				$oMovimentacao->contrapartida = $contrapartida;
			}
			$oMovimentacao->credor = $credor;

			$oMovimentacao->historico = $historico;

			if($valor_debito != 0 || $valor_credito != 0){
			  // soma acumuladores diarios
			  $saldo_dia_debito  += $valor_debito;
			  $saldo_dia_credito += $valor_credito;

			  $quebra_data = $data;

			  $aContas[$k13_reduz]->data[$iInd]->movimentacoes[] = $oMovimentacao;
			}


		}
	}


	if ($totalizador_diario=='s'){

		// calcula saldo a debito ou credito
		$aContas[$k13_reduz]->data[$iInd]->saldo_dia_debito 	= $saldo_dia_debito;
		$aContas[$k13_reduz]->data[$iInd]->saldo_dia_credito 	= $saldo_dia_credito;
		// calcula saldo a debito ou credito
		if ($saldo_dia_debito < 0){
			$saldo_dia_final -= abs($saldo_dia_debito);
			$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
		} else {
			$saldo_dia_final += $saldo_dia_debito;
			$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
		}
		if ($saldo_dia_credito < 0){
			$saldo_dia_final += abs($saldo_dia_credito);
			$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
		} else {
			$saldo_dia_final -= $saldo_dia_credito;
			$aContas[$k13_reduz]->data[$iInd]->saldo_dia_final = $saldo_dia_final;
		}

	}
	$aContas[$k13_reduz]->debitado  = $debitado;
	$aContas[$k13_reduz]->creditado = $creditado;
	$aContas[$k13_reduz]->atual 		= $atual;
}

//echo "<pre>";
//print_r($aContas);
//echo "<pre>";
//exit();

if($agrupapor != 1 || $receitaspor == 2 ){
	$aMovimentacao = array();
	$aContasNovas	 = array();
	foreach ($aContas as $key2=>$oConta){
		$aContasNovas[$key2] = $oConta;
		foreach ($oConta->data as $key1=>$oData){
			//$aContasNovas[$oConta->k13_reduz]->data[$key1] = $oData;
			foreach ($oData->movimentacoes as $oMovimento){

				//se por baixa banc�ria
				if($receitaspor== 2 && $oMovimento->tipo == "Baixa"){
					$controle = false;
					foreach ($aMovimentacao as $key=>$oValor) {
						//echo "<br>$oValor->receita -- $oMovimento->receita";
						if($oValor->tipo == $oMovimento->tipo && $oValor->codigo == $oMovimento->codigo && $controle == false){
							$controle = true;
							$chave = $key;
						}
					}
					if($controle){
						//	echo "<br>aqui1";
						//soma senao inseri no array
						$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
						$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
						$aMovimentacao[$chave]->caixa						= "";
						//$aMovimentacao[$chave]->k12_codautent		= "";
						//$aMovimentacao[$chave]->tipo						= "";
						$aMovimentacao[$chave]->planilha				= "";
						$aMovimentacao[$chave]->empenho					= "";
						$aMovimentacao[$chave]->ordem						= "";
						$aMovimentacao[$chave]->numdoc					= "";
						$aMovimentacao[$chave]->slip						= "";
						$aMovimentacao[$chave]->contrapartida		= "Baixa Banc�ria ref Arquivo ";
						$aMovimentacao[$chave]->contrapartida  .= $oMovimento->arqret.", do dia ";
						$aMovimentacao[$chave]->contrapartida  .= $oMovimento->dtarquivo.", retorno ";
						$aMovimentacao[$chave]->contrapartida  .= $oMovimento->codret." de ";
						$aMovimentacao[$chave]->contrapartida  .= $oMovimento->dtretorno;

						$aMovimentacao[$chave]->credor					= "";
						$aMovimentacao[$chave]->historico				= "";
						$aMovimentacao[$chave]->agrupado				= 'Baixa';

					}else{

						$aMovimentacao[] = $oMovimento;
					}

				}else if($agrupapor == 2 && $oMovimento->receita != "0" && $oMovimento->tipo != "Baixa"){

					// agrupa por receita
					$controle = false;
					//$chave = $oMovimento->codigo;
					if($oMovimento->tipo == "slip"){
						$aMovimentacao[] = $oMovimento;
					}else{
						foreach ($aMovimentacao as $key=>$oValor) {
							//echo "<br>$oValor->receita -- $oMovimento->receita";
							if($oValor->receita == $oMovimento->receita && $controle == false){
								$controle = true;
								$chave = $key;
							}
						}
						if($controle){
							//echo "<br>aqui1";
							//soma senao inseri no array
							$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
							$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
							$aMovimentacao[$chave]->caixa						= "";
							$aMovimentacao[$chave]->k123_codautent	= "";
							$aMovimentacao[$chave]->tipo						= "";
							$aMovimentacao[$chave]->planilha				= "";
							$aMovimentacao[$chave]->empenho					= "";
							$aMovimentacao[$chave]->ordem						= "";
							$aMovimentacao[$chave]->numdoc					= "";
							$aMovimentacao[$chave]->slip						= "";
							$aMovimentacao[$chave]->contrapartida		= $oMovimento->contrapartida;
							$aMovimentacao[$chave]->credor					= "";
							$aMovimentacao[$chave]->historico				= "";
							$aMovimentacao[$chave]->agrupado				= 'receita';

						}else{
							$aMovimentacao[] = $oMovimento;
						}
					}
				}else if($agrupapor == 3 && $oMovimento->tipo == "empenho"){

					$controle = false;
					foreach ($aMovimentacao as $key=>$oValor) {
						//echo "<br>$oValor->receita -- $oMovimento->receita";
						if($oValor->receita == $oMovimento->receita && $oValor->codigo == $oMovimento->codigo &&
							 $oValor->tipo == $oMovimento->tipo &&	$controle == false)
						{
							$controle = true;
							$chave = $key;
						}
					}
					if($controle){
						//echo "<br>aqui1";
						//soma senao inseri no array
						$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
						$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
						$aMovimentacao[$chave]->caixa						= "";
						$aMovimentacao[$chave]->k123_codautent	= "";
						//$aMovimentacao[$chave]->tipo						= "";
						$aMovimentacao[$chave]->planilha				= "";
						//$aMovimentacao[$chave]->empenho					= "";
						$aMovimentacao[$chave]->ordem						= "";
						$aMovimentacao[$chave]->numdoc					= "";
						$aMovimentacao[$chave]->slip						= "";
						$aMovimentacao[$chave]->contrapartida		= $oMovimento->credor;
						$aMovimentacao[$chave]->credor					= "";
						$aMovimentacao[$chave]->historico				= "";
						$aMovimentacao[$chave]->agrupado				= 'empenho';

					}else{
						$oMovimento->contrapartida = $oMovimento->credor;

						$aMovimentacao[] = $oMovimento;
					}
				}else if($agrupapor == 2 && $pagempenhos==2){

					$controle = false;
					if($oMovimento->tipo != "empenho"){
						$aMovimentacao[] = $oMovimento;
					}else{
						foreach ($aMovimentacao as $key=>$oValor) {

							if($oValor->ordem == $oMovimento->ordem && $controle == false && $oValor->tipo == "empenho")
							{
								$controle = true;
								$chave = $key;
							}
						}
						if($controle){

							$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
							$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;

							if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
								$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
								$aMovimentacao[$chave]->contrapartida = $oMovimento->contrapartida;
							}
						}else{
							if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
								$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
							}
							if($oMovimento->tipo == "empenho" || $oMovimento->tipo == "slip"){
								$oMovimento->codigo = "";
							}
							$aMovimentacao[] = $oMovimento;
						}
					}
				}else{

					if($pagempenhos == 2 && $imprime_analitico == "s"){
						//								echo "<pre>";
						//								echo var_dump($oMovimento);
						//								echo "<pre>";
						if($oMovimento->tipo !="empenho"){
							$aMovimentacao[] = $oMovimento;
						}else {
							$controle = false;
							foreach ($aMovimentacao as $key=>$oValor) {
								//echo "<br>$oValor->receita -- $oMovimento->receita";
								if($oValor->ordem == $oMovimento->ordem &&
									 $oValor->tipo == $oMovimento->tipo &&	$controle == false && $oValor->tipo == "empenho")
								{
									$controle = true;
									$chave = $key;
								}
							}
							if($controle){
								//echo "<br>aqui1";
								//soma senao inseri no array
								$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
								$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
								$aMovimentacao[$chave]->caixa						= "";
								$aMovimentacao[$chave]->k123_codautent	= "";
								//$aMovimentacao[$chave]->tipo						= "";
								$aMovimentacao[$chave]->planilha				= "";
								//$aMovimentacao[$chave]->empenho					= "";
								//$aMovimentacao[$chave]->ordem						= "";
								$aMovimentacao[$chave]->numdoc					= "";
								$aMovimentacao[$chave]->slip						= "";
								$aMovimentacao[$chave]->contrapartida		= $oMovimento->credor;
								$aMovimentacao[$chave]->credor					= "";
								$aMovimentacao[$chave]->historico				= "";
								$aMovimentacao[$chave]->agrupado				= 'empenho';

							}else{
								//$oMovimento->contrapartida = $oMovimento->credor;
								if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
									$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
								}
								if($oMovimento->tipo == "empenho" || $oMovimento->tipo == "slip"){
									$oMovimento->codigo = "";

									$aMovimentacao[] = $oMovimento;
								}

							}
						}
					}else {

						if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
							$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
						}
						if($oMovimento->tipo == "empenho" || $oMovimento->tipo == "slip"){
							$oMovimento->codigo = "";
						}
						$aMovimentacao[] = $oMovimento;

					}
				}
			}
			$aContasNovas[$oConta->k13_reduz]->data[$key1]->movimentacoes = $aMovimentacao;
			$aMovimentacao = array();
		}
	}
	$aContas = $aContasNovas;
}
else if ($agrupapor == 1 && $pagempenhos == 2 ){
	$aMovimentacao = array();
	$aContasNovas	 = array();

	foreach ($aContas as $key2=>$oConta){
		$aContasNovas[$key2] = $oConta;
		foreach ($oConta->data as $key1=>$oData){

			foreach ($oData->movimentacoes as $oMovimento){
				$controle = false;

				if($oMovimento->tipo != "empenho"){

					$aMovimentacao[] = $oMovimento;
				}else{

					foreach ($aMovimentacao as $key=>$oValor) {
						if($oValor->ordem == $oMovimento->ordem && $controle == false
							&& $oMovimento->tipo == "empenho" && $oValor->tipo == "empenho")
						{

							$controle = true;
							$chave = $key;
						}
					}
					if($controle){
						$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
						$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
						if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
							$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
							$aMovimentacao[$chave]->contrapartida = $oMovimento->contrapartida;
						}
					}else{
						if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
							$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
						}
						$aMovimentacao[] = $oMovimento;
					}

				}

			}
			$aContasNovas[$oConta->k13_reduz]->data[$key1]->movimentacoes = $aMovimentacao;
			$aMovimentacao = array();
		}

	}
	$aContas = $aContasNovas;
}else if ($agrupapor == 1 && $pagempenhos == 3 ){
	$aMovimentacao = array();
	$aContasNovas	 = array();

	foreach ($aContas as $key2=>$oConta){
		$aContasNovas[$key2] = $oConta;
		foreach ($oConta->data as $key1=>$oData){

			foreach ($oData->movimentacoes as $oMovimento){
				$controle = false;

				if($oMovimento->tipo != "empenho" && $oMovimento->tipo != "planilha"
					&& ($oMovimento->tipo != "slip" && !in_array($oMovimento->slipoperacaotipo, array(5,6) ) ) ) {

					$aMovimentacao[] = $oMovimento;

				}else if($oMovimento->tipo == "planilha"){

					foreach ($aMovimentacao as $key=>$oValor) {
						if($oValor->receita == $oMovimento->receita && $controle == false
							&& $oMovimento->tipo == "planilha" && $oValor->tipo == "planilha")
						{
							$controle = true;
							$chave = $key;
						}
					}
					if($controle){
						$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
						$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
					}else{
						$aMovimentacao[] = $oMovimento;
					}

				}else if($oMovimento->tipo == "slip" && !in_array($oMovimento->slipoperacaotipo, array(5,6) ) ){

					foreach ($aMovimentacao as $key=>$oValor) {
						if($oValor->codigocredor == $oMovimento->codigocredor && $controle == false
							&& $oMovimento->tipo == "slip" && $oValor->tipo == "slip")
						{
							$controle = true;
							$chave = $key;
						}
					}
					if($controle){
						$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
						$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
					}else{
						$aMovimentacao[] = $oMovimento;
					}

				}else{

					foreach ($aMovimentacao as $key=>$oValor) {
						if($oValor->codigocredor == $oMovimento->codigocredor && $controle == false
							&& $oMovimento->tipo == "empenho" && $oValor->tipo == "empenho")
						{

							$controle = true;
							$chave = $key;
						}
					}
					if($controle){
						$aMovimentacao[$chave]->valor_debito 		+= $oMovimento->valor_debito;
						$aMovimentacao[$chave]->valor_credito 	+= $oMovimento->valor_credito;
						if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
							$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
							$aMovimentacao[$chave]->contrapartida = $oMovimento->contrapartida;
						}
					}else{
						if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
							$oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
						}
						$aMovimentacao[] = $oMovimento;
					}

				}

			}
			$aContasNovas[$oConta->k13_reduz]->data[$key1]->movimentacoes = $aMovimentacao;
			$aMovimentacao = array();
		}

	}
	$aContas = $aContasNovas;
} else if ($agrupapor == 1 && $pagempenhos == 1 ){

    foreach ($aContas as $key2=>$oConta){
		$aContasNovas[$key2] = $oConta;
		foreach ($oConta->data as $key1=>$oData){

			foreach ($oData->movimentacoes as $oMovimento){

                if($oMovimento->tipo == "empenho" && $oMovimento->empenho != ""){
                    $oMovimento->contrapartida = $oMovimento->codigocredor." - ".$oMovimento->credor;
                }

            }

        }

    }
}


/*echo "<pre>";
print_r($aContas);
echo "</pre>";
exit();*/


if($imprime_pdf == 'p'){
	$pdf = new PDF();
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->SetTextColor(0,0,0);
	$pdf->setfillcolor(235);
	$pdf->AutoPageBreak = false;
	$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");

	$quebra_data = "";
	$contaquebra = 0;
	$lQuebra_Historico = false;
	foreach ($aContas as $oConta) {
		$lImprimeSaldo = true;
		if ($quebrar_contas == 'n' && $pdf->GetY() > $pdf->h - 25){

			$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");

		}
		if ($quebrar_contas == 's' && $oConta->k13_reduz != $contaquebra && $contaquebra != 0){
			AddWithoutPageNumber($pdf,"L");	
		}
		$contaquebra = $oConta->k13_reduz;

		imprimeConta($pdf,$oConta,$lImprimeSaldo);
		$lImprimeSaldo = false;
		imprimeCabecalho($pdf);

		foreach ($oConta->data as $oData){

			if (property_exists($oData, 'movimentacoes') && !empty($oData->movimentacoes)) {

				foreach ($oData->movimentacoes as $oMovimento) {

					if($totalizador_diario == 's' && $quebra_data != "" && $quebra_data != $oData->data){

						imprimeTotalMovDia($pdf,$saldo_dia_debito,$saldo_dia_credito,$saldo_dia_final);
						$saldo_dia_debito = 0;
						$saldo_dia_credito = 0;
						$saldo_dia_final = 0;

					}


					if ($pdf->GetY() > $pdf->h - 25){

						$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");

						imprimeConta($pdf,$oConta,$lImprimeSaldo);
						imprimeCabecalho($pdf);

					}



					if($lQuebra_Historico){
						imprimeConta($pdf,$oConta,$lImprimeSaldo);
						imprimeCabecalho($pdf);
						$lQuebra_Historico = false;
					}

					$pdf->Cell(20,5,db_formatar($oData->data,'d')	,"T",0,"C",0);
					$pdf->Cell(85,5,substr($oMovimento->contrapartida, 0, 58),"T",0,"L",0);
					$pdf->Cell(25,5,$oMovimento->planilha			,"T",0,"C",0);
					$pdf->Cell(25,5,$oMovimento->empenho			,"T",0,"C",0);
					$pdf->Cell(25,5,$oMovimento->ordem 	== "0" ? "" : $oMovimento->ordem,"T",0,"C",0);
					$pdf->Cell(25,5,$oMovimento->numdoc == "0" ? ""	: $oMovimento->numdoc,"T",0,"C",0);
					$pdf->Cell(25,5,$oMovimento->slip					,"T",0,"C",0);
					$pdf->Cell(25,5,$oMovimento->valor_debito == 0	? "" : db_formatar($oMovimento->valor_debito,"f")	,"T",0,"R",0);
					$pdf->Cell(25,5,$oMovimento->valor_credito == 0	? "" : db_formatar($oMovimento->valor_credito,"f")	,"T",0,"R",0);
					$pdf->ln();

					//Demais detalhes quando analitica
					if($imprime_analitico == 'a'){
						if(!isset($oMovimento->agrupado)){
							if ($pdf->GetY() > $pdf->h - 25){

								$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");

								imprimeConta($pdf,$oConta,$lImprimeSaldo);
								imprimeCabecalho($pdf);

							}
							$pdf->Cell(20,5,"",0,0,"C",0);
							$pdf->Cell(30,5,"Autentica��o mec�nica:","",0,"L",0);
							$pdf->Cell(150,5,trim($oMovimento->k12_codautent)		,"",0,"L",0);
							$pdf->ln();
							if ($pdf->GetY() > $pdf->h - 25){

								$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");

								imprimeConta($pdf,$oConta,$lImprimeSaldo);
								imprimeCabecalho($pdf);

							}
							$pdf->Cell(20,5,"",0,0,"C",0);
							$pdf->Cell(65,5,"Classifica��o de baixa banc�ria:","",0,"L",0);
							//					$pdf->Cell(65,5,"Baixa Banc�ria ref Arquivo:","",0,"L",0);
							$pdf->Cell(150,5,$oMovimento->codigo		,"",0,"L",0);
							$pdf->ln();
							if ($pdf->GetY() > $pdf->h - 25){

								$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");

								imprimeConta($pdf,$oConta,$lImprimeSaldo);
								imprimeCabecalho($pdf);

							}
							$pdf->Cell(20,5,"",0,0,"C",0);
							$pdf->Cell(25,5,"Nome/Raz�o Social:","",0,"L",0);
							$pdf->Cell(150,5,$oMovimento->credor		,"",0,"L",0);
							$pdf->ln();

							$lQuebra_Historico = false;
							if ($oMovimento->historico!=""  && $imprime_historico=='s' ){
								$lh = 0;
								while ($oMovimento->historico!=""){
									$lh = $lh + 1 ;
									if ($pdf->gety() > $pdf->h - 25){
										$quebrar_contas == 'n'? $pdf->AddPage("L"):AddWithoutPageNumber($pdf,"L");
										$lQuebra_Historico = true;
									}
									$pdf->Cell(20,5,"",0,0,"C",0);
									if($lh==1){
										$pdf->Cell(25,5,"Hist�rico:","",0,"L",0);
									}else{
										$pdf->Cell(25,5,"","",0,"L",0);
									}
									$oMovimento->historico =  $pdf->Row_multicell(array('','','',$oMovimento->historico,'',''),5,false,5,0,true,true,3,($pdf->h - 25),180);
								}
							}
						}else if(isset($oMovimento->agrupado) && $oMovimento->agrupado == 'Baixa' && false){
							$pdf->Cell(20,5,"",0,0,"C",0);
							$pdf->Cell(65,5,"Classifica��o de baixa banc�ria:","",0,"L",0);
							//					$pdf->Cell(65,5,"Baixa Banc�ria ref Arquivo:","",0,"L",0);
							$pdf->Cell(150,5,$oMovimento->codigo		,"",0,"L",0);
							$pdf->ln();
						}
					}
					$quebra_data = $oData->data;
				}
			}

			if($totalizador_diario == 's'){
				$saldo_dia_credito = $oData->saldo_dia_credito;
				$saldo_dia_debito  = $oData->saldo_dia_debito;
				$saldo_dia_final   = $oData->saldo_dia_final;
			}
		}
		$quebra_data = "";
		imprimeTotalMovDia($pdf,$saldo_dia_debito,$saldo_dia_credito,$saldo_dia_final);
		$saldo_dia_credito = 0;
		$saldo_dia_debito = 0;
		$saldo_dia_final = 0;
		imprimeTotalMovConta($pdf,$oConta->debitado,$oConta->creditado,$oConta->atual);
		$pdf->Ln(5);
	
	}

	if ($pdf->GetY() > $pdf->h - 25){

		$quebrar_contas == 'n'? $pdf->AddPage("L"): AddWithoutPageNumber($pdf,"L");

	}


    // die;
	$quebrar_contas == 'n'? $pdf->Output(): OutputWithoutPageNumber($pdf);
	exit();
}else{
	//aqui vai gerar o txt
	//Aqui ponteiro para o arquivo
	$fp = fopen('tmp/ExtratoBancario.csv', 'w');
	//	echo  "<html>
	//	     <head>
	//	       <script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/scripts.js\"></script>
	//	     </head>
	//	     <body bgcolor='#cccccc'><center>
	//	     <br><br>
	//	     <form name='form1' id='form1'>
	//	     <div id=\"pagina\">
	//	     <b>Aguarde gerando arquivo ...</b>
	//	     </div>
	//	     </form>
	//	     <br><br>
	//	     </body></html>";
	//	flush();
	//
	//Inicio do processametno do conteudo do txt

	$quebra_data = "";
	$lQuebra_Historico = false;
	foreach ($aContas as $oConta) {
		$lImprimeSaldo = true;

		imprimeContaTxt($fp,$oConta->k13_reduz,$oConta->k13_descr,$oConta->debito,$oConta->credito,$lImprimeSaldo);
		$lImprimeSaldo = false;
		imprimeCabecalhoTxt($fp);

		foreach ($oConta->data as $oData){

			foreach ($oData->movimentacoes as $oMovimento) {

				if($totalizador_diario == 's' && $quebra_data != "" && $quebra_data != $oData->data){

					imprimeTotalMovDiaTxt($fp,$saldo_dia_debito,$saldo_dia_credito,$saldo_dia_final);
					$saldo_dia_debito = 0;
					$saldo_dia_credito = 0;
					$saldo_dia_final = 0;

				}

				if($lQuebra_Historico){
					imprimeContaTxt($fp,$oConta->k13_reduz,$oConta->k13_descr,$oConta->debito,$oConta->credito,$lImprimeSaldo);
					imprimeCabecalhoTxt($fp);
					$lQuebra_Historico = false;
				}
				$aLinhaDados = array();
				$aLinhaDados[0] = db_formatar($oData->data,'d');
				$aLinhaDados[1] = $oMovimento->contrapartida;
				$aLinhaDados[2] = $oMovimento->planilha     ;
				$aLinhaDados[3] = $oMovimento->empenho      ;
				$aLinhaDados[4] = $oMovimento->ordem  == "0" ? "" : $oMovimento->ordem;
				$aLinhaDados[5] = $oMovimento->numdoc == "0" ? "" : $oMovimento->numdoc;
				$aLinhaDados[6] = $oMovimento->slip         ;
				$aLinhaDados[7] = $oMovimento->valor_debito == 0  ? "" : db_formatar($oMovimento->valor_debito,"f");
				$aLinhaDados[8] = $oMovimento->valor_credito == 0 ? "" : db_formatar($oMovimento->valor_credito,"f");

				fputcsv($fp,$aLinhaDados,',','"');

				//Demais detalhes quando analitica
				if($imprime_analitico == 'a'){
					if(!isset($oMovimento->agrupado)){
						$aLinhaDados = array();
						$aLinhaDados[0] ='';
						$aLinhaDados[1] = "Autentica��o mec�nica:";
						$aLinhaDados[2] = '';
						$aLinhaDados[3] = '';
						$aLinhaDados[4] = trim($oMovimento->k12_codautent);
						$aLinhaDados[5] = '';
						$aLinhaDados[6] = '';
						$aLinhaDados[7] = '';
						$aLinhaDados[8] = '';
						fputcsv($fp,$aLinhaDados,',','"');

						$aLinhaDados = array();
						$aLinhaDados[0] ='';
						//$pdf->Cell(20,5,"",0,0,"C",0);
						$aLinhaDados[1] = "Classifica��o de baixa banc�ria:";
						$aLinhaDados[2] = '';
						$aLinhaDados[3] = '';
						$aLinhaDados[4] = $oMovimento->codigo;
						$aLinhaDados[5] = '';
						$aLinhaDados[6] = '';
						$aLinhaDados[7] = '';
						$aLinhaDados[8] = '';
						fputcsv($fp,$aLinhaDados,',','"');

						$aLinhaDados = array();
						$aLinhaDados[0] ='';
						$aLinhaDados[1] = "Nome/Raz�o Social:";
						$aLinhaDados[2] = '';
						$aLinhaDados[3] = '';
						$aLinhaDados[4] = $oMovimento->credor;
						$aLinhaDados[5] = '';
						$aLinhaDados[6] = '';
						$aLinhaDados[7] = '';
						$aLinhaDados[8] = '';
						fputcsv($fp,$aLinhaDados,',','"');

						$lQuebra_Historico = false;

						if ($oMovimento->historico!=""  && $imprime_historico=='s' ){
							$aLinhaDados = array();
							$aLinhaDados[0] ='';
							$aLinhaDados[1] = "Hist�rico:";
							$aLinhaDados[2] = '';
							$aLinhaDados[3] = $oMovimento->historico;
							$aLinhaDados[4] = '';
							$aLinhaDados[5] = '';
							$aLinhaDados[6] = '';
							$aLinhaDados[7] = '';
							$aLinhaDados[8] = '';
							fputcsv($fp,$aLinhaDados,',','"');

						}

					}else if(isset($oMovimento->agrupado) && $oMovimento->agrupado == 'Baixa' && false){

						$aLinhaDados = array();
						$aLinhaDados[0] ='';
						$aLinhaDados[1] = "Classifica��o de baixa banc�ria:";
						$aLinhaDados[2] = '';
						$aLinhaDados[3] = '';
						$aLinhaDados[4] = $oMovimento->codigo ;
						$aLinhaDados[5] = '';
						$aLinhaDados[6] = '';
						$aLinhaDados[7] = '';
						$aLinhaDados[8] = '';
						fputcsv($fp,$aLinhaDados,',','"');

					}
				}

				$quebra_data = $oData->data;
			}

			if($totalizador_diario == 's'){
				$saldo_dia_credito = $oData->saldo_dia_credito;
				$saldo_dia_debito  = $oData->saldo_dia_debito;
				$saldo_dia_final   = $oData->saldo_dia_final;
			}
		}
		$quebra_data = "";
		imprimeTotalMovDiaTxt($fp,$saldo_dia_debito,$saldo_dia_credito,$saldo_dia_final);
		$saldo_dia_credito = 0;
		$saldo_dia_debito = 0;
		$saldo_dia_final = 0;
		imprimeTotalMovContaTxt($fp,$oConta->debitado,$oConta->creditado,$oConta->atual);
		//$pdf->Ln(5);
	}

	//Aqui encerra ponteiro para o arquivo
	fclose($fp);

	//	echo "<script>
	//	       document.getElementById('pagina').innerHTML = '<b>Arquivo gerado !</b><br><br>';
	//
	//        </script>";

	//	echo "<script>";
	//
	//  echo "  listagem = 'tmp/ExtratoBancario.csv #Dowload do Arquivo ExtratoBancario.csv';";
	//  echo "  js_montarlista(listagem,'form1');";
	//  echo "</script>";
	echo "<script language='javascript' type='text/javascript'>
          document.location.href = 'tmp/ExtratoBancario.csv';
        </script>";
	exit();
}

// imprimeConta($pdf,$oConta->k13_reduz,$oConta->k13_descr,$oConta->k13_dtimplantacao,$oConta->debito,$oConta->credito,$lImprimeSaldo);
// function imprimeConta($pdf,$codigo,$descricao,$dtimplantacao,$debito,$credito,$lImprimeSaldo){
function imprimeConta($pdf,$oConta,$lImprimeSaldo){
	$pdf->SetFont('Arial','b',8);
	$pdf->Cell(12,5,"CONTA:"								,0,0,"L",0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(95,5,$oConta->k13_reduz." - ".$oConta->k13_descr,0,0,"L",0);
    $pdf->SetFont('Arial','b',8);
    $pdf->Cell(10,5,"N�:"								,0,0,"L",0);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,5,$oConta->c63_conta,0,0,"L",0);
    $pdf->SetFont('Arial','b',8);
    $pdf->Cell(10,5,"AG:"								,0,0,"L",0);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(15,5,$oConta->c63_agencia,0,0,"L",0);
	$pdf->SetFont('Arial','b',8);

	if($lImprimeSaldo){
		$pdf->Cell(73,5,"SALDO ANTERIOR:"				,0,0,"R",0);
		$pdf->Cell(25,5,$oConta->debito  == 0 ? "" : db_formatar($oConta->debito,'f')	,0,0,"R",0);
		$pdf->Cell(25,5,$oConta->credito == 0 ? "" : db_formatar($oConta->credito,'f'),0,0,"R",0);
	}
	$pdf->ln();
	$pdf->SetFont('Arial','',7);
}

function imprimeCabecalho($pdf){
	$pdf->SetFont('Arial','b',8);
	$pdf->Cell(20,5,"DATA"					,"T",0,"C",1);
	$pdf->Cell(85,5,"CONTRAPARTIDA"	,"TL",0,"C",1);
	$pdf->Cell(25,5,"PLANILHA"			,"TL",0,"C",1);
	$pdf->Cell(25,5,"EMPENHO"			,"TL",0,"C",1);
	$pdf->Cell(25,5,"ORDEM"					,"TL",0,"C",1);
	$pdf->Cell(25,5,"N� DOCUMENTO"				,"TL",0,"C",1);
	$pdf->Cell(25,5,"SLIP"					,"TL",0,"C",1);
	$pdf->Cell(25,5,"D�BITO"				,"TL",0,"C",1);
	$pdf->Cell(25,5,"CR�DITO"				,"TL",0,"C",1);
	$pdf->ln();
	$pdf->Cell(20,5,""														,"TB",0,"R",1);
	$pdf->Cell(210,5,"INFORMA��ES COMPLEMENTARES"	,"TLB",0,"C",1);
	$pdf->Cell(25,5,""														,"TLB",0,"R",1);
	$pdf->Cell(25,5,""														,"TB",0,"R",1);
	$pdf->ln();
	$pdf->SetFont('Arial','',7);
}

function imprimeTotalMovDia($pdf,$saldo_dia_debito,$saldo_dia_credito,$saldo_dia_final){
	$pdf->SetFont('Arial','b',8);
	$pdf->Cell(20,5,""																	,"TB",0,"R",1);
	$pdf->Cell(210,5,"TOTAIS DA MOVIMENTA��O NO DIA:"		,"TB",0,"R",1);
	$pdf->Cell(25,5,$saldo_dia_debito 	== 0 ? "" : db_formatar($saldo_dia_debito,'f')	,"TLB",0,"R",1);
	$pdf->Cell(25,5,$saldo_dia_credito	== 0 ? "" : db_formatar($saldo_dia_credito,'f')	,"TLB",0,"R",1);
	$pdf->ln();
	$pdf->Cell(20,5,""																	,"TB",0,"R",1);
	$pdf->Cell(210,5,"SALDO NO DIA:"										,"TB",0,"R",1);
	$pdf->Cell(50,5,$saldo_dia_final 	  == 0 ? "" : db_formatar($saldo_dia_final,'f') ,"TLB",0,"R",1);
	$pdf->ln();

	$pdf->SetFont('Arial','',7);
}
function imprimeTotalMovConta($pdf,$saldo_debitado,$saldo_creditado,$saldo_atual){
	$pdf->SetFont('Arial','b',8);
	$pdf->Cell(20,5,""																	,"TB",0,"R",1);
	$pdf->Cell(210,5,"TOTAIS DA MOVIMENTA��O 1:"					,"TB",0,"R",1);
	$pdf->Cell(25,5,$saldo_debitado 	== 0 ? "" : db_formatar($saldo_debitado,'f')	,"TLB",0,"R",1);
	$pdf->Cell(25,5,$saldo_creditado	== 0 ? "" : db_formatar($saldo_creditado,'f')	,"TB",0,"R",1);
	$pdf->ln();
	$pdf->Cell(20,5,""																	,"TB",0,"R",1);
	$pdf->Cell(210,5,"SALDO FINAL:"											,"TB",0,"R",1);
	$pdf->Cell(50,5,$saldo_atual 	  == 0 ? "" : db_formatar($saldo_atual,'f') ,"TLB",0,"R",1);
	$pdf->ln();
	$pdf->SetFont('Arial','',7);
}

function imprimeContaTxt($fp,$codigo,$descricao,$debito,$credito,$lImprimeSaldo){
	$aLinha = array();
	$aLinha[0]  = "";
	$aLinha[1]  = "";
	$aLinha[2]  = "";
	$aLinha[3]  = "";
	$aLinha[4]  = "";
	$aLinha[5]  = "";
	$aLinha[6]  = "";
	$aLinha[7]  = "";
	$aLinha[8]  = "";

	fputcsv($fp,$aLinha,',','"');

	$aLinhaConta = array();
	$aLinhaConta[0] = 'CONTA';
	$aLinhaConta[1] =  $codigo." - ".$descricao;
	$aLinhaConta[2] = '';
	$aLinhaConta[3] = '';
	$aLinhaConta[4] = '';
	$aLinhaConta[5] = '';
	$aLinhaConta[6] = '';
	$aLinhaConta[7] = '';
	$aLinhaConta[8] = '';
	if($lImprimeSaldo){
		//$pdf->Cell(160,5,"SALDO ANTERIOR:"        ,0,0,"R",0);
		$aLinhaConta[6] = 'SALDO ANTERIOR:';
		//$pdf->Cell(25,5,$debito  == 0 ? "" : db_formatar($debito,'f') ,0,0,"R",0);
		$aLinhaConta[7] = $debito  == 0 ? "" : db_formatar($debito,'f');
		//$pdf->Cell(25,5,$credito == 0 ? "" : db_formatar($credito,'f'),0,0,"R",0);
		$aLinhaConta[8] = $credito == 0 ? "" : db_formatar($credito,'f');
	}
	fputcsv($fp,$aLinhaConta,',','"');
}

function imprimeCabecalhoTxt($fp){

	$aLinhaCabecalho = array();
	$aLinhaCabecalho[0]  = "DATA";
	$aLinhaCabecalho[1]  = "CONTRAPARTIDA";
	$aLinhaCabecalho[2]  = "PLANILHA";
	$aLinhaCabecalho[3]  = "EMPENHO";
	$aLinhaCabecalho[4]  = "ORDEM";
	$aLinhaCabecalho[5]  = "N� DOCUMENTO";
	$aLinhaCabecalho[6]  = "SLIP";
	$aLinhaCabecalho[7]  = "D�BITO";
	$aLinhaCabecalho[8]  = "CR�DITO";

	fputcsv($fp,$aLinhaCabecalho,',','"');

	$aLinhaCabecalho1 = array();
	$aLinhaCabecalho1[0] = '';
	$aLinhaCabecalho1[1] = "INFORMA��ES COMPLEMENTARES";
	$aLinhaCabecalho1[2] = "";
	$aLinhaCabecalho1[3] = "";
	$aLinhaCabecalho1[4] = "";
	$aLinhaCabecalho1[5] = "";
	$aLinhaCabecalho1[6] = "";
	$aLinhaCabecalho1[7] = "";
	$aLinhaCabecalho1[8] = "";
	fputcsv($fp,$aLinhaCabecalho1,',','"');
}

function imprimeTotalMovDiaTxt($fp,$saldo_dia_debito,$saldo_dia_credito,$saldo_dia_final){
	$aLinhaTotalMovDia = array();

	$aLinhaTotalMovDia[0] = '';
	$aLinhaTotalMovDia[1] = '';
	$aLinhaTotalMovDia[2] = '';
	$aLinhaTotalMovDia[3] = '';
	$aLinhaTotalMovDia[4] = '';
	$aLinhaTotalMovDia[5] = '';
	$aLinhaTotalMovDia[6] = "TOTAIS DA MOVIMENTA��O NO DIA:";
	$aLinhaTotalMovDia[7] = $saldo_dia_debito   == 0 ? "" : db_formatar($saldo_dia_debito,'f') ;
	$aLinhaTotalMovDia[8] = $saldo_dia_credito  == 0 ? "" : db_formatar($saldo_dia_credito,'f');

	fputcsv($fp,$aLinhaTotalMovDia,',','"');
	//$pdf->ln();
	$aLinhaTotalMovDia = array();

	$aLinhaTotalMovDia[0] = '';
	$aLinhaTotalMovDia[1] = '';
	$aLinhaTotalMovDia[2] = '';
	$aLinhaTotalMovDia[3] = '';
	$aLinhaTotalMovDia[4] = '';
	$aLinhaTotalMovDia[5] = '';

	$aLinhaTotalMovDia[6] = "SALDO NO DIA:";
	$aLinhaTotalMovDia[7] = '';
	$aLinhaTotalMovDia[8] = $saldo_dia_final    == 0 ? "" : db_formatar($saldo_dia_final,'f');
	//$pdf->ln();
	fputcsv($fp,$aLinhaTotalMovDia,',','"');
	//$pdf->SetFont('Arial','',7);
}
function imprimeTotalMovContaTxt($fp,$saldo_debitado,$saldo_creditado,$saldo_atual){
	$aLinhaTotalMovConta = array();

	$aLinhaTotalMovConta[0] = '';
	$aLinhaTotalMovConta[1] = '';
	$aLinhaTotalMovConta[2] = '';
	$aLinhaTotalMovConta[3] = '';
	$aLinhaTotalMovConta[4] = '';
	$aLinhaTotalMovConta[5] = '';
	$aLinhaTotalMovConta[6] = "TOTAIS DA MOVIMENTA��O 2:";
	$aLinhaTotalMovConta[7] = $saldo_debitado   == 0 ? "" : db_formatar($saldo_debitado,'f');
	$aLinhaTotalMovConta[8] = $saldo_creditado  == 0 ? "" : db_formatar($saldo_creditado,'f');

	fputcsv($fp,$aLinhaTotalMovConta,',','"');
	//$pdf->ln();
	$aLinhaTotalMovConta = array();

	$aLinhaTotalMovConta[0] = '';
	$aLinhaTotalMovConta[1] = '';
	$aLinhaTotalMovConta[2] = '';
	$aLinhaTotalMovConta[3] = '';
	$aLinhaTotalMovConta[4] = '';
	$aLinhaTotalMovConta[5] = '';
	$aLinhaTotalMovConta[6] = "SALDO FINAL:";
	$aLinhaTotalMovConta[7] = '';
	$aLinhaTotalMovConta[8] =$saldo_atual    == 0 ? "" : db_formatar($saldo_atual,'f');
	fputcsv($fp,$aLinhaTotalMovConta,',','"');

}


function condicao_retencao($bloco_query) {
    $sql  = " AND ( ";
    $sql .= "     SELECT * FROM ( ";

    if ($bloco_query == 'empenho')
        $sql .= "     SELECT SUM(e23_valorretencao) retencao ";
    if ($bloco_query == 'recibo')
        $sql .= "     SELECT e23_valorretencao retencao ";

    $sql .= "         FROM retencaoreceitas ";
    $sql .= "         INNER JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec ";
    $sql .= "         INNER JOIN retencaopagordem retencao ON retencao.e20_sequencial = retencaoreceitas.e23_retencaopagordem ";
    $sql .= "         INNER JOIN tabrec ON tabrec.k02_codigo = retencaotiporec.e21_receita ";
    $sql .= "         INNER JOIN retencaotipocalc ON retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc ";
    $sql .= "         INNER JOIN pagordem ON pagordem.e50_codord = retencao.e20_pagordem ";
    $sql .= "         INNER JOIN pagordemnota ON pagordem.e50_codord = pagordemnota.e71_codord ";
    $sql .= "         INNER JOIN empnota ON pagordemnota.e71_codnota = empnota.e69_codnota ";
    $sql .= "         INNER JOIN retencaoempagemov ON e23_sequencial = e27_retencaoreceitas ";
    $sql .= "         LEFT JOIN empagemovslips ON e27_empagemov = k107_empagemov ";
    $sql .= "         LEFT JOIN slipempagemovslips ON k107_sequencial = k108_empagemovslips ";
    $sql .= "         LEFT JOIN retencaocorgrupocorrente ON e23_sequencial = e47_retencaoreceita ";
    $sql .= "         LEFT JOIN corgrupocorrente ON e47_corgrupocorrente = k105_sequencial ";
    $sql .= "         LEFT JOIN cornump as numpre ON numpre.k12_data = k105_data ";
    $sql .= "            AND numpre.k12_autent = k105_autent ";
    $sql .= "            AND numpre.k12_id = k105_id ";
    $sql .= "         LEFT JOIN issplannumpre ON numpre.k12_numpre = q32_numpre ";

    if ($bloco_query == 'empenho')
        $sql .= "     WHERE retencao.e20_pagordem = coremp.k12_codord ";
    if ($bloco_query == 'recibo') {
        $sql .= "     WHERE retencao.e20_pagordem = retencaopagordem.e20_pagordem ";
        $sql .= "        AND numpre.k12_numpre = cornump.k12_numpre ";
        $sql .= "        AND numpre.k12_numpar = cornump.k12_numpar ";
    }

    $sql .= "            AND e27_principal IS TRUE ";
    $sql .= "            AND e23_ativo IS TRUE ";
    $sql .= "    ) as w ";
    $sql .= "    WHERE round(retencao, 2) = round(corrente.k12_valor, 2) ";
    $sql .= " ) IS NULL ";

    return $sql;
}

function AddWithoutPageNumber($pdf,$orientation = '', $size = '')
{
	// Start a new page
	if ($pdf->state == 0)
		$pdf->Open();

	$family = $pdf->FontFamily;
	$style = $pdf->FontStyle . ($pdf->underline ? 'U' : '');
	$fontsize = $pdf->FontSizePt;
	$lw = $pdf->LineWidth;
	$dc = $pdf->DrawColor;
	$fc = $pdf->FillColor;
	$tc = $pdf->TextColor;
	$cf = $pdf->ColorFlag;

	if ($pdf->page > 0) {
		// Page footer
		$pdf->InFooter = true;
		Footer($pdf);
		$pdf->InFooter = false;
		// Close page
		$pdf->_endpage();
	}
	// Start new page
	$pdf->_beginpage($orientation, $size);
	// Set line cap style to square
	$pdf->_out('2 J');
	// Set line width
	$pdf->LineWidth = $lw;
	$pdf->_out(sprintf('%.2F w', $lw * $pdf->k));
	// Set font
	if ($family)
		$pdf->SetFont($family, $style, $fontsize);
	// Set colors
	$pdf->DrawColor = $dc;
	if ($dc != '0 G')
		$pdf->_out($dc);
	$pdf->FillColor = $fc;
	if ($fc != '0 g')
		$pdf->_out($fc);
	$pdf->TextColor = $tc;
	$pdf->ColorFlag = $cf;
	// Page header
	$pdf->InHeader = true;
	$pdf->Header();
	$pdf->InHeader = false;

	// Restore line width
	if ($pdf->LineWidth != $lw) {
		$pdf->LineWidth = $lw;
		$pdf->_out(sprintf('%.2F w', $lw * $pdf->k));
	}
	// Restore font
	if ($family)
		$pdf->SetFont($family, $style, $fontsize);
	// Restore colors
	if ($pdf->DrawColor != $dc) {
		$pdf->DrawColor = $dc;
		$pdf->_out($dc);
	}
	if ($pdf->FillColor != $fc) {
		$pdf->FillColor = $fc;
		$pdf->_out($fc);
	}
	$pdf->TextColor = $tc;
	$pdf->ColorFlag = $cf;
}

//Page footer
function Footer($pdf)
{
  //#00#//footer
  //#10#//Este m�todo � usado para criar o rodap� da p�gina. Ele � automaticamente chamado por |addPage|
  //#10#//e |close| e n�o deve ser chamado diretamente pela aplica��o. A  implementa��o  em  FPDF  est�
  //#10#//vazia, ent�o voc�  deve  criar  uma  subclasse  e  sobrepor  o  m�todo  se  voc�  quiser   um
  //#10#//processamento espec�fico.
  //#15#//footer()
  //#99#//Exemplo:
  //#99#//class PDF extends FPDF
  //#99#//{
  //#99#//  function Footer()
  //#99#//  {
  //#99#//    Vai para 1.5 cm da borda inferior
  //#99#//      $pdf->SetY(-15);
  //#99#//    Seleciona Arial it�lico 8
  //#99#//      $pdf->SetFont('Arial','I',8);
  //#99#//    Imprime o n�mero da p�gina centralizado
  //#99#//      $pdf->Cell(0,10,'Page '.$pdf->PageNo(),0,0,'C');
  //#99#//  }
  //#99#//}
  global $conn;
  global $result;
  global $url;
  

	/*
	   * Modifica��o para exibir o caminho do menu
	   * na base do relat�rio
	   */
	//$sSqlMenuAcess = "SELECT fc_montamenu(funcao) as menu from db_itensmenu where id_item =".db_getsession("DB_itemmenu_acessado");
	$sSqlMenuAcess = " select trim(modulo.descricao)||'>'||trim(menu.descricao)||'>'||trim(item.descricao) as menu
						   from db_menu
						  inner join db_itensmenu as modulo on modulo.id_item = db_menu.modulo
						  inner join db_itensmenu as menu on menu.id_item = db_menu.id_item
						  inner join db_itensmenu as item on item.id_item = db_menu.id_item_filho
						  where id_item_filho = " . db_getsession("DB_itemmenu_acessado") . "
							and modulo = " . db_getsession("DB_modulo");

	$rsMenuAcess   = db_query($conn, $sSqlMenuAcess);
	$sMenuAcess    = substr(pg_result($rsMenuAcess, 0, "menu"), 0, 50);

	//Position at 1.5 cm from bottom
	$pdf->SetFont('Arial', '', 5);
	$pdf->text(10, $pdf->h - 8, 'Base: ' . @$GLOBALS["DB_NBASE"]);
	$pdf->SetFont('Arial', 'I', 6);
	$pdf->SetY(-10);
	$nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
	$nome = substr($nome, strrpos($nome, "/") + 1);
	$result_nomeusu = db_query($conn, "select nome as nomeusu from db_usuarios where id_usuario =" . db_getsession("DB_id_usuario"));
	if (pg_numrows($result_nomeusu) > 0) {
	  $nomeusu = pg_result($result_nomeusu, 0, 0);
	}
	if (isset($nomeusu) && $nomeusu != "") {
	  $emissor = $nomeusu;
	} else {
	  $emissor = @$GLOBALS["DB_login"];
	}
	$sDataSistema = '';
	$lDateFooter = true;
	if ($lDateFooter) {
	  $sDataSistema = '  Exerc: ' . db_getsession("DB_anousu") . '   Data: ' . date("d-m-Y", db_getsession("DB_datausu")) . " - " . date("H:i:s");
	}
	$pdf->Cell(0, 10, $sMenuAcess . "  " . $nome . '   Emissor: ' . substr(ucwords(strtolower($emissor)), 0, 30) . $sDataSistema, "T", 0, 'L'); 
}

function OutputWithoutPageNumber($pdf,$file='',$download=false,$mostrar=false)
//#00#//output
//#10#//Salva um documento PDF em um arquivo local ou envia-o para o browser. Neste �ltimo caso, o  plug-in  ser�  usado
//#10#//(se instalado) ou um download (caixa de di�logo "Salvar como") ser� apresentada.
//#10#//O m�todo primeiro chama |close|, se necess�rio para terminar o documento.
//#15#//output($file='',$download=false)
//#20#//file         : O nome do arquivo. Se vazio ou n�o informado, o documento ser� enviado ao browser para que ele  o
//#20#//               use com o plug-in (se instalado).
//#20#//download     : Se file for informado, indica que ele deve ser salvo localmente  (false) ou  mostrar a  caixa  de
//#20#//               di�logo "Salvar como" no browser. Valor padr�o: false.

{
	if($file=='')
	  $file = $pdf->GeraArquivoTemp();

  //Output PDF to file or browser
  global $HTTP_ENV_VARS;

  if($pdf->state<3)
  	CloseWithoutPageNumber($pdf);
  if($file=='')
  {
	//Send to browser
	Header('Content-Type: application/pdf');
				header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              // Date in the past
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
				header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");                                    // HTTP/1.0
				header("Cache-control: private");


	if(headers_sent())
		$pdf->Error('Some data has already been output to browser, can\'t send PDF file');
	Header('Content-Length: '.strlen($pdf->buffer));
	echo $pdf->buffer;

  }
  else
  {
		  if($download)
	{

		   if(isset($HTTP_ENV_VARS['HTTP_USER_AGENT']) and strpos($HTTP_ENV_VARS['HTTP_USER_AGENT'],'MSIE 5.5'))
			 Header('Content-Type: application/dummy');
		   else
			 Header('Content-Type: application/octet-stream');
		   if(headers_sent())
		   	 $pdf->Error('Some data has already been output to browser, can\'t send PDF file');
			 Header('Content-Length: '.strlen($pdf->buffer));
			 Header('Content-disposition: attachment; filename='.$file);
	   echo $pdf->buffer;
	}
	else
	{

		  ////////// N�O RETIRAR ESSE IF SEM FALAR COM MARLON
		  ////////// NECESS�RIO PARA PROGRAMA DO M�DULO PESSOAL
		  ////////// gera��o de arquivos BB
		  if($mostrar == false){
		header('Content-Type: application/pdf');
		header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              // Date in the past
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
		header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");                                    // HTTP/1.0
		header("Cache-control: private");
		echo $pdf->buffer;
		  }


	  //Save file locally
	  $f=fopen($file,'wb');
	  if(!$f)
	  	$pdf->Error('Unable to create output file: '.$file);
	  fwrite($f,$pdf->buffer,strlen($pdf->buffer));
	  fclose($f);

	  	$pdf->arquivo_retorno  = $file;

//echo "<script>location.href='tmp/".$file."'</script>";


	}
  }
}

function CloseWithoutPageNumber($pdf)
{
	// Terminate document
	if ($pdf->state == 3)
		return;
	if ($pdf->page == 0)
		AddWithoutPageNumber($pdf);
	// Page footer
	$pdf->InFooter = true;
	Footer($pdf);
	$pdf->InFooter = false;
	// Close page
	$pdf->_endpage();
	// Close document
	$pdf->_enddoc();
}


?>
