<?
include ("fpdf151/pdf.php");
include ("libs/db_liborcamento.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_orctiporec_classe.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orcorgao_classe.php");
include ("classes/db_empemphist_classe.php");
include ("classes/db_emphist_classe.php");
include ("classes/db_orcelemento_classe.php");
include ("classes/db_conlancamemp_classe.php");
include ("classes/db_conlancamdoc_classe.php");
include ("classes/db_empempitem_classe.php");
include ("classes/db_empresto_classe.php");
include ("classes/db_empelemento_classe.php");

//db_postmemory($HTTP_POST_VARS,2);exit;
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clselorcdotacao = new cl_selorcdotacao();
$clorcelemento = new cl_orcelemento;
$clemphist = new cl_emphist;
$clconlancamemp = new cl_conlancamemp;
$clconlancamdoc = new cl_conlancamdoc;
$clempempenho = new cl_empempenho;
$clcgm = new cl_cgm;
$clorctiporec = new cl_orctiporec;
$clorcdotacao = new cl_orcdotacao;
$clorcorgao = new cl_orcorgao;
$clempemphist = new cl_empemphist;
$clempempitem = new cl_empempitem;
$clempresto = new cl_empresto;
$clempelemento = new cl_empelemento;

$clorcelemento->rotulo->label();
$clemphist->rotulo->label();
$clempemphist->rotulo->label();
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();
$clempelemento->rotulo->label();
$clrotulo = new rotulocampo;

$tipo = "a"; // sempre analitico

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php

$instits = $clselorcdotacao->getInstit();

$sele_work = $clselorcdotacao->getDados(false);

$sele_desdobramentos="";
$desdobramentos = $clselorcdotacao->getDesdobramento(); // coloca os codele dos desdobramntos no formato (x,y,z)
if ($desdobramentos != "") {
  $sele_desdobramentos = " and empelemento.e64_codele in ".$desdobramentos; // adiciona desdobramentos
}

// echo $sele_work;exit;

$clrotulo->label("pc50_descr");
///////////////////////////////////////////////////////////////////////
$campos = "distinct e60_resumo, e60_numemp,e60_codemp,e60_emiss,e60_numcgm,z01_nome,z01_cgccpf,z01_munic,e60_vlremp,e60_vlranu,e60_vlrliq,e63_codhist,e40_descr,";
$campos = $campos."e60_vlrpag,e60_anousu,e60_coddot,o58_coddot,o58_orgao,o40_orgao,o40_descr,o58_unidade,o41_descr,o15_codigo,o15_descr";
$campos = $campos.",fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,e60_codcom,pc50_descr";

//---------
// monta sql
$txt_where = "e60_instit in $instits";
if ($listaitem != "") {
	if (isset ($veritem) and $veritem == "com") {
		$txt_where = $txt_where." and e62_item in  ($listaitem)";
	} else {
		$txt_where = $txt_where." and e62_item not in  ($listaitem)";
	}
}
if ($listacredor != "") {
	if (isset ($ver) and $ver == "com") {
		$txt_where = $txt_where." and e60_numcgm in  ($listacredor)";
	} else {
		$txt_where = $txt_where." and e60_numcgm not in  ($listacredor)";
	}
}
if ($listahist != "") {
	if (isset ($verhist) and $verhist == "com") {
		$txt_where = $txt_where." and e63_codhist in  ($listahist)";
	} else {
		$txt_where = $txt_where." and e63_codhist not in  ($listahist)";
	}
}
if ($listacom != "" and 1 == 2) {
	if (isset ($vercom) and $vercom == "com") {
		$txt_where = $txt_where." and e60_codcom in  ($listacom)";
	} else {
		$txt_where = $txt_where." and e60_codcom not in  ($listacom)";
	}
}
if (($datacredor != "--") && ($datacredor1 != "--")) {
	$txt_where = $txt_where." and e60_emiss  between '$datacredor' and '$datacredor1'  ";
	//        $datacredor=db_formatar($datacredor,"d");
	//        $datacredor1=db_formatar($datacredor1,"d");
	$info = "De ".db_formatar($datacredor, "d")." at� ".db_formatar($datacredor1, "d").".";
} else
	if ($datacredor != "--") {
		$txt_where = $txt_where." and e60_emiss >= '$datacredor'  ";
		//          $datacredor=db_formatar($datacredor,"d");
		$info = "Apartir de ".db_formatar($datacredor, "d").".";
	} else
		if ($datacredor1 != "--") {
			$txt_where = $txt_where."    e60_emiss <= '$datacredor1'   ";
			//         $datacredor1=db_formatar($datacredor1,"d");
			$info = "At� ".db_formatar($datacredor1, "d").".";
		}

if ($tipoemp == "todos") {
	$txt_where = $txt_where." ";
}
elseif ($tipoemp == "somemp") {
	$txt_where = $txt_where." and (round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2) > 0) and round(yyy.e60_vlrliq,2) = 0 ";
}
elseif ($tipoemp == "saldo") {
	$txt_where = $txt_where." and round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2) - round(yyy.e60_vlrpag,2) > 0 ";
}
elseif ($tipoemp == "saldoliq") {
	$txt_where = $txt_where." and (round(yyy.e60_vlrliq,2) - round(yyy.e60_vlrpag,2) > 0) and round(yyy.e60_vlrliq,2) > 0";
}
elseif ($tipoemp == "saldonaoliq") {
	$txt_where = $txt_where." and (round(yyy.e60_vlrliq,2) - round(yyy.e60_vlrpag,2) > 0) and round(yyy.e60_vlrliq,2) = 0";
}
elseif ($tipoemp == "anul") {
	$txt_where = $txt_where." and round(yyy.e60_vlranu,2) > 0 ";
}
elseif ($tipoemp == "anultot") {
	$txt_where = $txt_where." and round(yyy.e60_vlremp,2) = round(yyy.e60_vlranu,2)";
}
elseif ($tipoemp == "anulparc") {
	$txt_where = $txt_where." and round(yyy.e60_vlranu,2) > 0 and round(yyy.e60_vlremp,2) <> round(yyy.e60_vlranu,2)";
}
elseif ($tipoemp == "anulsem") {
	$txt_where = $txt_where." and round(yyy.e60_vlranu,2) = 0";
}
elseif ($tipoemp == "liq") {
	$txt_where = $txt_where." and round(yyy.e60_vlrliq,2) > 0";
}
elseif ($tipoemp == "liqtot") {
	$txt_where = $txt_where." and ((round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2)) = round(yyy.e60_vlrliq,2))";
}
elseif ($tipoemp == "liqparc") {
	$txt_where = $txt_where." and round(yyy.e60_vlrliq,2) > 0 and ((round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2)) <> round(yyy.e60_vlrliq,2))";
}
elseif ($tipoemp == "liqsem") {
	$txt_where = $txt_where." and round(yyy.e60_vlrliq,2) = 0";
}
elseif ($tipoemp == "pag") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) > 0 ";
}
elseif ($tipoemp == "pagtot") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) > 0 and (round(yyy.e60_vlrliq,2) = round(yyy.e60_vlrpag,2))";
}
elseif ($tipoemp == "pagparc") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) > 0 and (round(yyy.e60_vlrliq,2) <> round(yyy.e60_vlrpag,2))";
}
elseif ($tipoemp == "pagsem") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) = 0";
}
elseif ($tipoemp == "pagsemcomliq") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) = 0 and round(yyy.e60_vlrliq,2) > 0";
}

$txt_where .= " and $sele_work";

/////////////////////////////////////////////  
$ordem = "z01_nome, e60_emiss";
if ($agrupar == "a") {
	$ordem = "z01_nome, e60_emiss";
}
if ($agrupar == "r") {
	$ordem = "o15_codigo, e60_emiss";
}

//if ($tipo=="a"){
if (1 == 1) {
	if ($agrupar == "a") { // fornecedor
		$ordem = "z01_nome, e60_emiss";
	} elseif ($agrupar == "orgao") { // orgao
		$ordem = "o58_orgao, e60_emiss";
	} elseif ($agrupar == "r") { // recurso
		$ordem = "o15_codigo, e60_emiss";
	} elseif ($agrupar == "d" and 1==2) { // desdobramento
		$ordem = "e64_codele, e60_emiss";
	} else {
	  $ordem = "";
	}
//	die($clempempenho->sql_query(null,$campos,$ordem,$txt_where));

	if ($processar == "a") {

		$txt_where = str_replace("yyy.", "", $txt_where);

		// die($clempempenho->sql_query_hist(null,$campos,$ordem,$txt_where));
		$sqlrelemp = $clempempenho->sql_query_relatorio(null, $campos, $ordem, $txt_where);

		if ($agrupar == "d" or 1==1) {
		  $sqlrelemp = "select 	  x.e60_resumo, 
					  x.e60_numemp,
					  x.e60_codemp,
					  x.e60_emiss,
					  x.e60_numcgm,
					  x.z01_nome,
					  x.z01_cgccpf,
					  x.z01_munic,
 					  x.e63_codhist,
					  x.e40_descr,
 				  	  x.e60_anousu,
					  x.e60_coddot,
					  x.o58_coddot,
					  x.o58_orgao,
					  x.o40_orgao,
					  x.o40_descr,
					  x.o58_unidade,
					  x.o41_descr,
					  x.o15_codigo,
					  x.o15_descr,
					  x.dl_estrutural,
					  x.e60_codcom,
					  x.pc50_descr,
					  empelemento.e64_codele,
					  orcelemento.o56_descr,
                                          x.e60_vlremp,
					  x.e60_vlranu, 
					  x.e60_vlrliq,
                                          x.e60_vlrpag,
					  empelemento.e64_vlremp,
					  empelemento.e64_vlrliq,
					  empelemento.e64_vlranu,
					  empelemento.e64_vlrpag
				  from ($sqlrelemp) as x
				  inner join empelemento on x.e60_numemp = e64_numemp  ".$sele_desdobramentos." 
				  inner join orcelemento on o56_codele = e64_codele and o56_anousu = x.e60_anousu
				  group by
                                          x.e60_resumo, 
					  x.e60_numemp,
					  x.e60_codemp,
					  x.e60_emiss,
					  x.e60_numcgm,
					  x.z01_nome,
					  x.z01_cgccpf,
					  x.z01_munic,
 					  x.e63_codhist,
					  x.e40_descr,
 				  	  x.e60_anousu,
					  x.e60_coddot,
					  x.o58_coddot,
					  x.o58_orgao,
					  x.o40_orgao,
					  x.o40_descr,
					  x.o58_unidade,
					  x.o41_descr,
					  x.o15_codigo,
					  x.o15_descr,
					  x.dl_estrutural,
					  x.e60_codcom,
					  x.pc50_descr,
					  empelemento.e64_codele,
					  orcelemento.o56_descr,
                                          x.e60_vlremp,
					  x.e60_vlranu, 
					  x.e60_vlrliq,
                                          x.e60_vlrpag,
					  empelemento.e64_vlremp,
					  empelemento.e64_vlrliq,
					  empelemento.e64_vlranu,
					  empelemento.e64_vlrpag
					  ";
		}
		$sqlrelemp = "select * from ($sqlrelemp) as x order by " . ($agrupar == "d"?"e64_codele, e60_emiss":$ordem);
		$res = $clempempenho->sql_record($sqlrelemp);
                // db_criatabela($res);exit;
		//echo($clempempenho->sql_query_hist(null,$campos,$ordem,$txt_where));exit;
		/*$sql = "
		 select e60_resumo,
		              e60_numemp," .
		              "e60_codemp," .
		              "e60_emiss," .
		              "e60_numcgm," .
		              "z01_nome,z01_cgccpf," .
		              "z01_munic,e60_vlremp," .
		              "e60_vlranu,e60_vlrliq," .
		              "e63_codhist,e40_descr," .
		              "e60_vlrpag,e60_anousu," .
		              "e60_coddot,o58_coddot," .
		              "o58_orgao," .
		              "o40_orgao," .
		              "o40_descr," .
		              "o58_unidade," .
		              "o41_descr," .
		              "o15_codigo," .
		              "o15_descr,fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural," .
		              "e60_codcom,pc50_descr " .
		   "from empempenho " .
		   "inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm " .
		   "inner join db_config on db_config.codigo = empempenho.e60_instit " .
		   "inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot " .
		   "inner join emptipo on emptipo.e41_codtipo = empempenho.e60_codtipo " .
		   "inner join db_config as a on a.codigo = orcdotacao.o58_instit " .
		   "inner join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo " .
		   "inner join orcfuncao on orcfuncao.o52_funcao = orcdotacao.o58_funcao " .
		   "inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao " .
		   "inner join orcprograma on orcprograma.o54_anousu = orcdotacao.o58_anousu and orcprograma.o54_programa = orcdotacao.o58_programa " .
		   "inner join orcelemento on  " .$sele_work_elemento.
		   " inner join orcprojativ on orcprojativ.o55_anousu = orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ " .
		   "inner join orcorgao on orcorgao.o40_anousu = orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao " .
		   "inner join orcunidade on orcunidade.o41_anousu = orcdotacao.o58_anousu and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade " .
		   "left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp " .
		   "left join emphist on emphist.e40_codhist = empemphist.e63_codhist " .
		   "inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom " .
		   "where ".$txt_where."
		    ";
		 //$res=pg_exec($sql);
		 //db_criatabela($res);exit;
		 */
		if ($clempempenho->numrows > 0) {
			$rows = $clempempenho->numrows;
		} else {
			db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem dados para gerar a consulta!');
		}

	} else {

		$sqlperiodo = "
			      select 	empempenho.e60_numemp, 
					e60_resumo,
					e60_codemp,
					e60_emiss,
					e60_numcgm,
					z01_nome,
					z01_cgccpf,
					z01_munic,
					yyy.e60_vlremp,
					yyy.e60_vlranu,
					yyy.e60_vlrliq,
					e63_codhist,
					e40_descr,
					yyy.e60_vlrpag,
					e60_anousu,
					e60_coddot,
					o58_coddot,
					o58_orgao,
					o40_orgao,
					o40_descr,
					o58_unidade,
					o41_descr,
					o15_codigo,
					o15_descr,
					fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,
					e60_codcom,
					pc50_descr 
			   from (
			  select e60_numemp, 
					sum(case when c53_tipo = 10 then c70_valor else 0 end) as e60_vlremp,
					sum(case when c53_tipo = 11 then c70_valor else 0 end) as e60_vlranu,
					sum(case when c53_tipo = 20 then c70_valor else 0 end) - sum(case when c53_tipo = 21 then c70_valor else 0 end) as e60_vlrliq,
					sum(case when c53_tipo = 30 then c70_valor else 0 end) - sum(case when c53_tipo = 31 then c70_valor else 0 end) as e60_vlrpag
				from (
		
				select  e60_numemp,
						c53_tipo,
						sum(c70_valor) as c70_valor
				from (
				      select e60_numemp,
						    e60_anousu,
						    e60_coddot
				      from empempenho		 
				      where 	e60_instit in $instits and 
						e60_emiss between '$datacredor' and '$datacredor1' 
				      ) as xxx			         
					  inner join orcdotacao 		on orcdotacao.o58_anousu 	= xxx.e60_anousu and orcdotacao.o58_coddot = xxx.e60_coddot
					  inner join orcelemento 		on  orcelemento.o56_codele = orcdotacao.o58_codele 
									       and  orcelemento.o56_anousu = orcdotacao.o58_anousu
					      inner join conlancamemp 	on c75_numemp = xxx.e60_numemp
					      inner join conlancam	on c70_codlan = c75_codlan and c70_data <= '$dataesp2'
					      inner join conlancamdoc 	on c71_codlan = c70_codlan
					      inner join conhistdoc 	on c53_coddoc = c71_coddoc and c53_tipo in (10,11,20,21,30,31)
					      inner join conlancamdot   on c73_codlan = c75_codlan            
					 group by e60_numemp, c53_tipo
				) as xxx
			group by e60_numemp) as yyy
					inner join empempenho		on empempenho.e60_numemp	= yyy.e60_numemp
					inner join cgm 			on cgm.z01_numcgm 		= empempenho.e60_numcgm 
					inner join db_config 		on db_config.codigo 		= empempenho.e60_instit 
					inner join orcdotacao 		on orcdotacao.o58_anousu 	= empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot 
					inner join emptipo 		on emptipo.e41_codtipo 		= empempenho.e60_codtipo 
					inner join db_config as a 	on a.codigo 			= orcdotacao.o58_instit 
					inner join orctiporec 		on orctiporec.o15_codigo 	= orcdotacao.o58_codigo 
					inner join orcfuncao 		on orcfuncao.o52_funcao 	= orcdotacao.o58_funcao 
					inner join orcsubfuncao 	on orcsubfuncao.o53_subfuncao 	= orcdotacao.o58_subfuncao 
					inner join orcprograma 		on orcprograma.o54_anousu 	= orcdotacao.o58_anousu 
													   and orcprograma.o54_programa = orcdotacao.o58_programa 
					inner join orcelemento 		on orcelemento.o56_codele = orcdotacao.o58_codele
								       and orcelemento.o56_anousu = orcdotacao.o58_anousu
					inner join orcprojativ 		on orcprojativ.o55_anousu 	= orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ 
					inner join orcorgao 		on orcorgao.o40_anousu 		= orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao 
					inner join orcunidade 		on orcunidade.o41_anousu 	= orcdotacao.o58_anousu 
								 and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade 
					left join  empemphist 		on empemphist.e63_numemp = empempenho.e60_numemp 
					left join  emphist 		on emphist.e40_codhist = empemphist.e63_codhist 
					inner join pctipocompra 	on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
		if ($listaitem != "") {
		  $sqlperiodo .="  inner join empempitem on e62_numemp=empempenho.e60_numemp and e62_item in ($listaitem ) ";
		}

		$sqlperiodo .=" where $txt_where
				order by $ordem";
				
		if ($agrupar == "d") {
		  $sqlperiodo =  "
			      select 	e60_numemp, 
					e60_resumo,
					e60_codemp,
					e60_emiss,
					e60_numcgm,
					z01_nome,
					z01_cgccpf,
					z01_munic,
					e60_vlremp,
					e60_vlranu,
					e60_vlrliq,
					e63_codhist,
					e40_descr,
					e60_vlrpag,
					e60_anousu,
					e60_coddot,
					o58_coddot,
					o58_orgao,
					o40_orgao,
					o40_descr,
					o58_unidade,
					o41_descr,
					o15_codigo,
					o15_descr,
					dl_estrutural,
					e60_codcom,
					pc50_descr,
					empelemento.e64_codele,
					orcelemento.o56_descr
		      from ($sqlperiodo) as x
			    inner join empelemento on x.e60_numemp = e64_numemp
			    inner join orcelemento on o56_codele = e64_codele and o56_anousu = x.e60_anousu
			      group by  e60_numemp, 
					e60_resumo,
					e60_codemp,
					e60_emiss,
					e60_numcgm,
					z01_nome,
					z01_cgccpf,
					z01_munic,
					e60_vlremp,
					e60_vlranu,
					e60_vlrliq,
					e63_codhist,
					e40_descr,
					e60_vlrpag,
					e60_anousu,
					e60_coddot,
					o58_coddot,
					o58_orgao,
					o40_orgao,
					o40_descr,
					o58_unidade,
					o41_descr,
					o15_codigo,
					o15_descr,
					dl_estrutural,
					e60_codcom,
					pc50_descr,
					empelemento.e64_codele,
					orcelemento.o56_descr
			    ";
		}
		//echo $sqlperiodo;
		//exit;
		$res = $clempempenho->sql_record($sqlperiodo);
		// db_criatabela($res);exit;
		$rows = $clempempenho->numrows;
	}

} else {

	/* 
	db_msgbox("Relat�rio sint�tico n�o dispon�vel!!");
	db_redireciona('db_erros.php?fechar=true&db_erro=Relat�rio sint�tico n�o dispon�vel!!');  
	*/

	$sql = "select  e60_numcgm,
									  z01_nome,
									  sum(e60_vlremp) as e60_vlremp,
									  sum(e60_vlranu) as e60_vlranu,
									  sum(e60_vlrliq) as e60_vlrliq,
									  sum(e60_vlrpag) as e60_vlrpag
						 from empempenho 
									 inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm
									 inner join db_config  on  db_config.codigo = empempenho.e60_instit
									 inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot  = empempenho.e60_coddot
									 inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo
									 inner join db_config  as a on   a.codigo = orcdotacao.o58_instit
									 inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo
									 inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao
									 inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
									 inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa
									 inner join orcelemento  on  ".$sele_work_elemento."
									 inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ
									 inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao
									 inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade
									 left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp 
									 left join emphist on emphist.e40_codhist = empemphist.e63_codhist
								  where     
									$txt_where ";
	$sql = $sql." group by e60_numcgm,z01_nome order by $ordem";
	//	   echo $sql;
	$res = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$rows = $clempempenho->numrows;
	} else {

		db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem dados para gerar a consulta ! ');
	}
}

//////////////////////////////////////////////////////////////////////

$head3 = "Relat�rio de Empenhos";

if (isset ($tipoemp) && $tipoemp != "") {
	if ($tipoemp == "todos") {
		$head4 = "Todos os empenhos";
	}
	elseif ($tipoemp == "saldo") {
		$head4 = "Com saldo a pagar geral";
	}
	elseif ($tipoemp == "saldoliq") {
		$head4 = "Com saldo a pagar liquidados";
	}
	elseif ($tipoemp == "saldonaoliq") {
		$head4 = "Com saldo a pagar nao liquidados";
	}
	elseif ($tipoemp == "anul") {
		$head4 = "Com anula��o";
	}
	elseif ($tipoemp == "anultot") {
		$head4 = "Apenas os totalmente anulados";
	}
	elseif ($tipoemp == "anulparc") {
		$head4 = "Apenas os anulados parcialmente";
	}
	elseif ($tipoemp == "anulsem") {
		$head4 = "Apenas os sem anula��o";
	}
	elseif ($tipoemp == "liq") {
		$head4 = "Com liquida��o";
	}
	elseif ($tipoemp == "liqtot") {
		$head4 = "Apenas os liquidados totalmente";
	}
	elseif ($tipoemp == "liqparc") {
		$head4 = "Apenas os liquidados parcialmente";
	}
	elseif ($tipoemp == "liqsem") {
		$head4 = "Apenas os sem liquida��o";
	}
	elseif ($tipoemp == "pag") {
		$head4 = "Com pagamentos";
	}
	elseif ($tipoemp == "pagtot") {
		$head4 = "Apenas os pagos totalmente";
	}
	elseif ($tipoemp == "pagparc") {
		$head4 = "Apenas os pagos parcialmente";
	}
	elseif ($tipoemp == "pagsem") {
		$head4 = "Apenas os sem pagamento";
	}
	elseif ($tipoemp == "pagsemsemliq") {
		$head4 = "Apenas os sem pagamento e sem liquida��o";
	}
	elseif ($tipoemp == "pagsemcomliq") {
		$head4 = "Apenas os sem pagamento e com liquida��o";
	}
}

$head5 = "$info";

if ($processar == "a") {
	$head6 = "Posi��o atual";
} else {
	$head6 = "Periodo especificado: ".db_formatar($dataesp1, "d")." a ".db_formatar($dataesp2, "d");
}

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);


if ($agrupar != "d") {
  $e64_codele = "99999";
}

$tam = '04';
$imprime_header = true;
$contador = 0;
$repete_r = "";
$repete_d = "";
$repete = "";
$t_emp1 = 0;
$t_liq1 = 0;
$t_anu1 = 0;
$t_pag1 = 0;
$t_total1 = 0;
$t_emp2 = 0;
$t_liq2 = 0;
$t_anu2 = 0;
$t_pag2 = 0;
$t_total2 = 0;
$t_emp3 = 0;
$t_liq3 = 0;
$t_anu3 = 0;
$t_pag3 = 0;
$t_total3 = 0;
$t_emp = 0;
$t_liq = 0;
$t_anu = 0;
$t_pag = 0;
$t_total = 0;
$g_emp = 0;
$g_liq = 0;
$g_anu = 0;
$g_pag = 0;
$g_total = 0;
$tg_emp = 0;
$tg_liq = 0;
$tg_anu = 0;
$tg_pag = 0;
$tg_total = 0;
$p = 0;
$t_emp5 = 0;
$t_liq5 = 0;
$t_anu5 = 0;
$t_pag5 = 0;
$t_total5 = 0;
$t_emp6 = 0;
$t_liq6 = 0;
$t_anu6 = 0;
$t_pag6 = 0;
$t_total6 = 0;
$quantimp = 0;

$lanctotemp = 0;
$lanctotanuemp = 0;
$lanctotliq = 0;
$lanctotanuliq = 0;
$lanctotpag = 0;
$lanctotanupag = 0;

/*  geral analitico */
if ($tipo == "a" or 1 == 1) {
	$pdf->SetFont('Arial', '', 7);
	$totalforne = 0;
	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x, true);
		// testa novapagina 
		if ($pdf->gety() > $pdf->h - 30) {
			$pdf->addpage("L");
			$imprime_header = true;
		}

		if ($imprime_header == true) {
			$pdf->Ln();

			$pdf->SetFont('Arial', 'B', 7);

			if ($agrupar == "a") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(105, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}

			if ($agrupar == "d") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo56_codele), 1, 0, "C", 1);
					$pdf->Cell(105, $tam, strtoupper($RLo56_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo56_codele), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo56_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}

			if ($agrupar == "r") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(105, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}
			
			if ($agrupar == "orgao") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo58_codigo), 1, 0, "C", 1);
					$pdf->Cell(105, $tam, strtoupper($RLo40_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo58_codigo), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo40_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}
			
			//*/
			if ($tipo == "a" and $sememp == "n") {
				if ($agrupar == "oo") {
					$pdf->Cell(150, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTA��O", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				}
				$pdf->Cell(15, $tam, "N�", 1, 0, "C", 1);
				$pdf->Cell(15, $tam, "EMP.", 1, 0, "C", 1);
				$pdf->Cell(15, $tam, "EMISS�O", 1, 0, "C", 1);

				if ($agrupar == "a") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1); // recurso
					} else
						if ($mostrar == "t") {
							$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
						}
				}
				
				if ($agrupar == "d") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1); // recurso
					} else
						if ($mostrar == "t") {
							$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
						}
				}
				
				if ($agrupar == "r") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1); // recurso
					} else
						if ($mostrar == "t") {
							$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
						}
				}

				if ($agrupar == "orgao") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLo40_descr), 1, 0, "C", 1); // recurso
					} elseif ($mostrar == "t") {
						$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
					}
				}

				if ($agrupar == "oo") {
					$pdf->Cell(40, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1);
				}

				$pdf->Cell(65, $tam, strtoupper($RLe60_coddot), 1, 0, "L", 1); // cod+estrut dotatao // quebra linha
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha

				if ($mostralan == "m") {
					$pdf->Cell(40, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, "DATA", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "LAN�AMENTO", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "DOCUMENTO", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "VALOR", 1, 1, "C", 1); // quebra linha1
				}
				if ($mostraritem == "m") {
					$pdf->Cell(40, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, "ITEM", 1, 0, "C", 1);
					$pdf->Cell(75, $tam, "DESCRI��O DO ITEM", 1, 0, "C", 1);
					$pdf->Cell(20, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(20, $tam, "VALOR TOTAL", 1, 0, "C", 1);
					$pdf->Cell(102, $tam, "COMPLEMENTO", 1, 1, "C", 1); // quebra linha1
				}
			}

			$pdf->SetFont('Arial', '', 7);
			$imprime_header = false;

		}
		/* ----------- */
		if ($repete != $e60_numcgm and $agrupar == "a") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
					} else {
						$base = "";
						$preenche = 0;
					}
					$pdf->Cell(120, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell(30, $tam, ($sememp == "n" ? "TOTAL DE " : "               ").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_r = $o15_codigo;
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "a") {
				$pdf->Cell(45, $tam, "$e60_numcgm", 0, 0, "C", 0);
				$pdf->Cell(80, $tam, "$z01_nome", 0, 1, "L", 0);
				if ($sememp == "n") {
					$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "d") {
				$pdf->Cell(45, $tam, "$o56_codele", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "r") {
				$pdf->Cell(45, $tam, "$o15_codigo", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o15_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}

			$pdf->SetFont('Arial', '', 7);
		}
		/* ----------- */
		if ($repete_d != $e64_codele and $agrupar == "d") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
					} else {
						$base = "";
						$preenche = 0;
					}
					$pdf->Cell(120, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell(30, $tam, ($sememp == "n" ? "TOTAL DE " : "               ").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_d = $e64_codele;
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "d") {
			  $pdf->Cell(45, $tam, "$e64_codele", 0, 0, "C", 0);
			  $pdf->Cell(105, $tam, "$o56_descr", 0, 1, "L", 0);
			}
			$pdf->SetFont('Arial', '', 7);
		}
		if ($repete_r != $o15_codigo and $agrupar == "r") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
					} else {
						$base = "";
						$preenche = 0;
					}
					$pdf->Cell(120, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell(30, $tam, ($sememp == "n" ? "TOTAL DE " : "               ").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_r = $o15_codigo;
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "a") {
				$pdf->Cell(45, $tam, "$e60_numcgm", 0, 0, "C", 0);
				$pdf->Cell(80, $tam, "$z01_nome", 0, 0, "L", 0);
				if ($sememp == "n") {
					$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "d") {
				$pdf->Cell(45, $tam, "$e64_codele", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "r") {
				$pdf->Cell(45, $tam, "$o15_codigo", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o15_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			$pdf->SetFont('Arial', '', 7);
		}
		/* ----------- */
		if ($repete_r != $o58_orgao and $agrupar == "orgao") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
					} else {
						$base = "";
						$preenche = 0;
					}
					$pdf->Cell(120, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell(30, $tam, ($sememp == "n" ? "TOTAL DE " : "               ").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_r = $o58_orgao; // trocado
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "a") {
				$pdf->Cell(45, $tam, "$e60_numcgm", 0, 0, "C", 0);
				$pdf->Cell(80, $tam, "$z01_nome", 0, 0, "L", 0);
				if ($sememp == "n") {
					$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "d") {
				$pdf->Cell(45, $tam, "$e64_codele", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "r") {
				$pdf->Cell(45, $tam, "$o15_codigo", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o15_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "orgao") {
				$pdf->Cell(45, $tam, "$o58_orgao", 0, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o40_descr", 0, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			$pdf->SetFont('Arial', '', 7);
		}



       /* --------  */

		if ($agrupar == "a") {
			$preenche = 1;
		}
		if ($agrupar == "d") {
			if ($mostralan == 'm') {
				$preenche = 1;
			} else {
				$preenche = 0;
			}
		} else {
			$preenche = 0;
		}
		if ($agrupar == "r") {
			if ($mostralan == 'm') {
				$preenche = 1;
			} else {
				$preenche = 0;
			}
		} else {
			$preenche = 0;
		}
		if ($agrupar == "orgao") {
			if ($mostralan == 'm') {
				$preenche = 1;
			} else {
				$preenche = 0;
			}
		} else {
			$preenche = 0;
		}

		$quantimp ++;
		// caso o exercicio do empenho for maior que o do exercicio do resto nao gerar
		
        if(substr($dataesp2,0,4)<db_getsession("DB_anousu")){
        		
		  $resresto = $clempresto->sql_record($clempresto->sql_query(db_getsession("DB_anousu"), $e60_numemp, "*", "", ""));
		  if ($clempresto->numrows > 0) {
			db_fieldsmemory($resresto, 0, true);
			if ($processar != "a") {
				$e60_vlremp += $e91_vlremp;
				$e60_vlranu += $e91_vlranu;
				$e60_vlrliq += $e91_vlrliq;
				$e60_vlrpag += $e91_vlrpag;
			}
		  }
	    
	    }
		
		$total = $e60_vlrliq - $e60_vlrpag;
		
		// o tipo sempre � == "A"
		if ($tipo == "a" and $sememp == "n") {
			$pdf->Cell(15, $tam, "$e60_numemp", 0, 0, "R", $preenche);
			$pdf->Cell(15, $tam, "$e60_codemp", 0, 0, "R", $preenche);
			$pdf->Cell(15, $tam, $e60_emiss, 0, 0, "C", $preenche);

			if ($agrupar == "a") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, db_formatar($o15_codigo, 'recurso')." - ".substr($o15_descr, 0, 20), 0, 0, "L", $preenche); // recurso
				} else
					if ($mostrar == "t") {
						$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", 0, 0, "L", $preenche); // tipo de compra
					}
			}
			if ($agrupar == "d") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, substr($z01_nome, 0, 25), 0, 0, "L", $preenche); // recurso
				} elseif ($mostrar == "t") {
					$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", 0, 0, "L", $preenche); // tipo de compra
				}
			}
			if ($agrupar == "r") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, substr($z01_nome, 0, 25), 0, 0, "L", $preenche); // recurso
				} else
					if ($mostrar == "t") {
						$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", 0, 0, "L", $preenche); // tipo de compra
					}
			}
		    if ($agrupar == "orgao") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, substr($z01_nome, 0, 25), 0, 0, "L", $preenche); // recurso
				} else
					if ($mostrar == "t") {
						$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", 0, 0, "L", $preenche); // tipo de compra
					}
			}		
			if ($agrupar == "oo") {
			  $pdf->Cell(40, $tam, substr($z01_nome,0,20), 0, 0, "L", 0);
			}

			$pdf->Cell(65, $tam, "$e60_coddot -  $dl_estrutural", 0, 0, "L", $preenche); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($e60_vlremp, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlranu, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlrliq, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlrpag, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), 'B', 0, "R", $preenche); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), 'B', 1, "R", $preenche);
			if ($mostrarobs == "m") {
				$pdf->multicell(270, 4, $e60_resumo);
			}
			
			
			

			if ($processar != "a" or 1 == 1) {

				$reslancam = $clconlancamemp->sql_record($clconlancamemp->sql_query("", "*", "", " c75_numemp = $e60_numemp ". ($processar == "a" ? "" : " and c75_data between '$dataesp1' and '$dataesp2'")));
				$rows_lancamemp = $clconlancamemp->numrows;
				for ($lancemp = 0; $lancemp < $rows_lancamemp; $lancemp ++) {
					db_fieldsmemory($reslancam, $lancemp, true);
					$reslancamdoc = $clconlancamdoc->sql_record($clconlancamdoc->sql_query($c70_codlan, "*"));
					db_fieldsmemory($reslancamdoc, 0, true);
					if ($mostralan == "m") {
						$preenche = ($lancemp % 2 == 0 ? 0 : 1);
						$pdf->Cell(40, $tam, "", 0, 0, "R", $preenche);
						$pdf->Cell(20, $tam, $c70_data, 0, 0, "C", $preenche);
						$pdf->Cell(25, $tam, $c70_codlan, 0, 0, "R", $preenche);
						$pdf->Cell(25, $tam, $c53_descr, 0, 0, "L", $preenche);
						$pdf->Cell(25, $tam, db_formatar($c70_valor, 'f'), 0, 1, "R", $preenche);
					}

					if ($c53_tipo == 10) {
						$lanctotemp += $c70_valor;
					}
					elseif ($c53_tipo == 11) {
						$lanctotanuemp += $c70_valor;
					}
					elseif ($c53_tipo == 20) {
						$lanctotliq += $c70_valor;
					}
					elseif ($c53_tipo == 21) {
						$lanctotanuliq += $c70_valor;
					}
					elseif ($c53_tipo == 30) {
						$lanctotpag += $c70_valor;
					}
					elseif ($c53_tipo == 31) {
						$lanctotanupag += $c70_valor;
					}

				}
			}

			if ($mostraritem == "m") {
				$resitem = $clempempitem->sql_record($clempempitem->sql_query($e60_numemp, null, "*"));
				$rows_item = $clempempitem->numrows;
				for ($item = 0; $item < $rows_item; $item ++) {
					db_fieldsmemory($resitem, $item, true);
					$preenche = ($item % 2 == 0 ? 0 : 1);
					$pdf->Cell(40, $tam, "", 0, 0, "R", $preenche);
					$pdf->Cell(20, $tam, "$e62_item", 0, 0, "R", $preenche);
					$pdf->Cell(75, $tam, "$pc01_descrmater", 0, 0, "L", $preenche);
					$pdf->Cell(20, $tam, db_formatar($e62_quant, 'f'), 0, 0, "R", $preenche);
					$pdf->Cell(20, $tam, db_formatar($e62_vltot, 'f'), 0, 0, "R", $preenche);
					$pdf->Cell(80, $tam, substr($e62_descr, 0, 70), 0, 1, "L", $preenche);
					$pdf->Cell(20, $tam, "", 0, 1, "R", $preenche);
				}
			}

		}






		if ($sememp == "n") {
			$pdf->Ln(1);
		}
		/* somatorio  */
		$t_emp += $e60_vlremp;
		$t_liq += $e60_vlrliq;
		$t_anu += $e60_vlranu;
		$t_pag += $e60_vlrpag;
		$t_total += $total;
		$g_emp += $e60_vlremp;
		$g_liq += $e60_vlrliq;
		$g_anu += $e60_vlranu;
		$g_pag += $e60_vlrpag;
		$g_total += $total;
		/*  */
		if ($x == ($rows -1)) {
			//$pdf->setX(125);
			/* imprime totais -*/
			$pdf->SetFont('Arial', 'B', 7);
			if ($sememp == "n") {
				$base = "B";
				$preenche = 1;
			} else {
				$base = "";
				$preenche = 0;
			}
			$pdf->Cell(120, $tam, '', $base, 0, "R", $preenche);
			$pdf->Cell(30, $tam, ($sememp == "n" ? "TOTAL DE " : "               ").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(65, $tam, "TOTAL DE EMPENHOS: ".db_formatar($rows, "s"), "T", 0, "L", 1);
			if ($totalforne > 0) {
			  $pdf->Cell(60, $tam, "TOTAL DE FORNECEDORES: ".db_formatar($totalforne, "s"), "T", 0, "L", 1);
			} else {
			  $pdf->Cell(60, $tam, "", "T", 0, "L", 1);
			}
			$pdf->Cell(25, $tam, "TOTAL GERAL", "T", 0, "L", 1);
			$pdf->Cell(18, $tam, db_formatar($g_emp, 'f'), "T", 0, "R", 1); //totais globais
			$pdf->Cell(18, $tam, db_formatar($g_anu, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_liq, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_pag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_liq - $g_pag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_emp - $g_anu - $g_liq, 'f'), "T", 0, "R", 1); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($g_emp - $g_anu - $g_pag, 'f'), "T", 1, "R", 1); //quebra linha

			$pdf->Ln();
			$pdf->Cell(150, $tam, "MOVIMENTA��O CONTABIL NO PERIODO", "T", 0, "L", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotemp, 'f'), "T", 0, "R", 1); //totais globais
			$pdf->Cell(18, $tam, db_formatar($lanctotanuemp, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotliq - $lanctotanuliq, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotpag - $lanctotanupag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar(($lanctotliq - $lanctotanuliq) - ($lanctotpag - $lanctotanupag), 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar(($lanctotemp - ($lanctotanuemp + ($lanctotpag - $lanctotanupag))) - (($lanctotliq - $lanctotanuliq) - ($lanctotpag - $lanctotanupag)), 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotemp - ($lanctotanuemp + ($lanctotpag - $lanctotanupag)), 'f'), "T", 1, "R", 1);
			$pdf->SetFont('Arial', '', 7);
		}
	}
}

/* geral sintetico */
if ($tipo == "s") {

	$pdf->SetFont('Arial', '', 7);
	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x, true);
		// testa nova pagina
		if ($pdf->gety() > $pdf->h - 30) {
			$pdf->addpage("L");
			$imprime_header = true;
		}

		if ($imprime_header == true) {
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(20, $tam, strtoupper($RLe60_numcgm), 1, 0, "C", 1);
			$pdf->Cell(100, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
			$pdf->Cell(30, $tam, "TOTAL A PAGAR", 1, 1, "C", 1); //quebra linha
			$pdf->Ln();
			$pdf->SetFont('Arial', '', 7);
			$imprime_header = false;
		}
		/* ----------- */
		$pdf->Ln(1);
		$pdf->Cell(20, $tam, $e60_numcgm, 0, 0, "R", $p);
		$pdf->Cell(100, $tam, $z01_nome, 0, 0, "L", $p);
		$pdf->Cell(20, $tam, $e60_vlremp, 0, 0, "R", $p);
		$pdf->Cell(20, $tam, $e60_vlranu, 0, 0, "R", $p);
		$pdf->Cell(20, $tam, $e60_vlrliq, 0, 0, "R", $p);
		$pdf->Cell(20, $tam, $e60_vlrpag, 0, 0, "R", $p);
		$total = $e60_vlremp - $e60_vlranu - $e60_vlrpag;
		$pdf->Cell(30, $tam, db_formatar($total, 'f'), 0, 1, "R", $p); //quebra linha
		$t_emp += $e60_vlremp;
		$t_liq += $e60_vlrliq;
		$t_anu += $e60_vlranu;
		$t_pag += $e60_vlrpag;
		$t_total += $total;
		if ($p == 0) {
			$p = 1;
		} else
			$p = 0;
		if ($x == ($rows -1)) {
			$pdf->Ln();
			$pdf->setX(110);
			/* imprime totais -*/
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(25, $tam, "TOTAL ", "T", 0, "L", 1);
			$pdf->Cell(20, $tam, db_formatar($t_emp, 'f'), "T", 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($t_anu, 'f'), "T", 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($t_liq, 'f'), "T", 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($t_pag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(22, $tam, db_formatar($t_total, 'f'), "T", 1, "R", 1); //quebra linha
			$pdf->SetFont('Arial', '', 7);

		}
		/* */

	}
} /* fim geral sintetico */

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select case when e63_codhist is null then 0               else e63_codhist end as e63_codhist,
														       case when e40_descr   is null then 'SEM HISTORICO' else e40_descr   end as e40_descr,
														       e60_vlremp, e60_vlranu, e60_vlrliq, e60_vlrpag from (
														select e63_codhist,e40_descr,
												                       sum(e60_vlremp) as e60_vlremp,
												                       sum(e60_vlranu) as e60_vlranu,
												          	       sum(e60_vlrliq) as e60_vlrliq,
												       	               sum(e60_vlrpag) as e60_vlrpag
												   	        from empempenho 
															inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot  = empempenho.e60_coddot
															inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
															                       and  orcelemento.o56_anousu = orcdotacao.o58_anousu
												                       	left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp 
												                       	left join emphist on emphist.e40_codhist = empemphist.e63_codhist
														where 	$txt_where
												                      ";
		$sql = $sql." group by e63_codhist,e40_descr order by e63_codhist) as x";
	} else {
		$sql = "select case when x.e63_codhist is null then 0               else x.e63_codhist end as e63_codhist,
														       case when x.e40_descr   is null then 'SEM HISTORICO' else x.e40_descr   end as e40_descr,
														       sum(e60_vlremp) as e60_vlremp, sum(e60_vlranu) as e60_vlranu, sum(e60_vlrliq) as e60_vlrliq, sum(e60_vlrpag) as e60_vlrpag from 
														       ($sqlperiodo) as x
												                       left join empemphist on empemphist.e63_numemp = x.e60_numemp 
												                       left join emphist on emphist.e40_codhist = empemphist.e63_codhist
														       group by x.e63_codhist,x.e40_descr order by x.e63_codhist";
	}
	$result = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZA��O DOS HIST�RICOS", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, strtoupper($RLe63_codhist), 1, 0, "C", 1);
				$pdf->Cell(100, $tam, strtoupper($RLe40_descr), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "NAO LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $e63_codhist, 0, 0, "R", $p);
			$pdf->Cell(100, $tam, $e40_descr, 0, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, 0, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), 0, 1, "R", $p); //quebra linha
			$t_emp1 += $e60_vlremp;
			$t_liq1 += $e60_vlrliq;
			$t_anu1 += $e60_vlranu;
			$t_pag1 += $e60_vlrpag;
			$t_total1 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); //quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
			/* */

		}

	} else {

	}

}

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select case when o58_codigo is null then 0               else o58_codigo end as o58_codigo,
														       case when o15_descr  is null then 'SEM RECURSO'  else o15_descr   end as o15_descr,
														       e60_vlremp, e60_vlranu, e60_vlrliq, e60_vlrpag from (
														select o58_codigo,o15_descr,
												                       sum(e60_vlremp) as e60_vlremp,
												                       sum(e60_vlranu) as e60_vlranu,
												          	       sum(e60_vlrliq) as e60_vlrliq,
												       	               sum(e60_vlrpag) as e60_vlrpag
												   	        from empempenho 
															inner join orcdotacao   on orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot  = empempenho.e60_coddot
															inner join orcelemento  on orcelemento.o56_codele = orcdotacao.o58_codele
															                       and orcelemento.o56_anousu = orcdotacao.o58_anousu
												            left join orctiporec    on orctiporec.o58_codigo = orcdotacao.o58_codigo 
														where 	$txt_where
												                      ";
		$sql = $sql." group by o58_codigo,o15_descr order by o58_codigo) as x";
	} else {
		$sql = "select case when x.o58_codigo is null then 0               else x.o58_codigo end as o58_codigo,
														       case when x.o15_descr  is null then 'SEM RECURSO'   else x.o15_descr   end as o15_descr,
														       sum(e60_vlremp) as e60_vlremp, sum(e60_vlranu) as e60_vlranu, sum(e60_vlrliq) as e60_vlrliq, sum(e60_vlrpag) as e60_vlrpag from 
														       ($sqlperiodo) as x
												                       left join orctiporec    on orctiporec.o58_codigo = orcdotacao.o58_codigo 
														       group by x.o58_codigo,x.o15_descr order by x.o58_codigo";
	}
	//     die($sqlperiodo)
	$result = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZA��O DOS RECURSOS", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, strtoupper($RLo58_codigo), 1, 0, "C", 1);
				$pdf->Cell(100, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "NAO LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $o58_codigo, 0, 0, "R", $p);
			$pdf->Cell(100, $tam, $o15_descr, 0, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, 0, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), 0, 1, "R", $p); //quebra linha
			$t_emp1 += $e60_vlremp;
			$t_liq1 += $e60_vlrliq;
			$t_anu1 += $e60_vlranu;
			$t_pag1 += $e60_vlrpag;
			$t_total1 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); //quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
			/* */

		}

	} else {

	}

}

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select e60_codcom, pc50_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from empempenho
														    inner join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom
														    inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu
																	 and orcdotacao.o58_coddot = empempenho.e60_coddot
														    inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
														                           and  orcelemento.o56_anousu = orcdotacao.o58_anousu
														    where $txt_where
															  ";
		$sql = $sql."group by e60_codcom, pc50_descr order by e60_codcom";
	} else {
		$sql = "select 	x.e60_codcom, x.pc50_descr,
   sum(x.e60_vlremp) as e60_vlremp,
   sum(x.e60_vlranu) as e60_vlranu,
   sum(x.e60_vlrliq) as e60_vlrliq,
   sum(x.e60_vlrpag) as e60_vlrpag
   from
  ($sqlperiodo) as x
 	inner join pctipocompra on x.e60_codcom = pctipocompra.pc50_codcom
 	inner join orcdotacao on orcdotacao.o58_anousu = x.e60_anousu
   		   and orcdotacao.o58_coddot = x.e60_coddot
 	inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
                               and  orcelemento.o56_anousu = orcdotacao.o58_anousu
  	group by x.e60_codcom, x.pc50_descr order by x.e60_codcom";
	}
	$result1 = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result1, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZA��O POR TIPO DE COMPRA", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, 'Codigo', 1, 0, "C", 1);
				$pdf->Cell(100, $tam, strtoupper($RLpc50_descr), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "N�O LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $e60_codcom, 0, 0, "R", $p);
			$pdf->Cell(100, $tam, $pc50_descr, 0, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, 0, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), 0, 1, "R", $p); //quebra linha
			$t_emp5 += $e60_vlremp;
			$t_liq5 += $e60_vlrliq;
			$t_anu5 += $e60_vlranu;
			$t_pag5 += $e60_vlrpag;
			$t_total5 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); // quebra linha
				$pdf->SetFont('Arial', '', 7);

			}
			/* */

		}

	}

}

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select o58_orgao,
														    o40_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from empempenho
														    inner join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom
														    inner join orcdotacao 	on orcdotacao.o58_anousu = empempenho.e60_anousu and
																	       orcdotacao.o58_coddot = empempenho.e60_coddot
														    inner join orcorgao 	on o40_orgao = o58_orgao and o40_anousu = o58_anousu
														    inner join orcunidade 	on o41_orgao = o40_orgao and o41_unidade = o58_unidade and o41_anousu = o40_anousu
														    where $txt_where
															  ";
		$sql = $sql."group by o58_orgao, o40_descr";
	} else {
		$sql = "select x.o58_orgao,
														    x.o40_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
														    from
														    ($sqlperiodo) as x
														    inner join orcdotacao 	on 	orcdotacao.o58_anousu = x.e60_anousu and
																	       		orcdotacao.o58_coddot = x.e60_coddot
														    inner join orcorgao 	on 	orcorgao.o40_orgao = orcdotacao.o58_orgao and orcorgao.o40_anousu = orcdotacao.o58_anousu
														    inner join orcunidade 	on 	o41_orgao = orcorgao.o40_orgao and o41_unidade = orcdotacao.o58_unidade and o41_anousu = orcorgao.o40_anousu
														    group by x.o58_orgao, x.o40_descr
														    ";
	}
	//     echo $sql;exit;
	$result1 = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result1, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZA��O POR ORGAO", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, 'ORGAO', 1, 0, "C", 1);
				$pdf->Cell(100, $tam, "DESCRICAO", 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "N�O LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $o58_orgao, 0, 0, "R", $p);
			$pdf->Cell(100, $tam, $o40_descr, 0, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, 0, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), 0, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), 0, 1, "R", $p); // quebra linha
			$t_emp6 += $e60_vlremp;
			$t_liq6 += $e60_vlrliq;
			$t_anu6 += $e60_vlranu;
			$t_pag6 += $e60_vlrpag;
			$t_total6 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); // quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
		}

	}

	$t_emp6 = 0;
	$t_liq6 = 0;
	$t_anu6 = 0;
	$t_pag6 = 0;
	$t_total6 = 0;

	if ($processar == "a") {
		$sql = "select o58_orgao,
														    o58_unidade,
														    o40_descr,
														    o41_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from empempenho
														    inner join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom
														    inner join orcdotacao 	on orcdotacao.o58_anousu = empempenho.e60_anousu
																	 and orcdotacao.o58_coddot = empempenho.e60_coddot
														    inner join orcorgao 	on o40_orgao = o58_orgao and o40_anousu = o58_anousu
														    inner join orcunidade 	on o41_orgao = o40_orgao and o41_unidade = o58_unidade and o41_anousu = o40_anousu
														    inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
														                           and  orcelemento.o56_anousu = orcdotacao.o58_anousu
														    where $txt_where
															  ";
		$sql = $sql."group by o58_orgao, o58_unidade, o40_descr, o41_descr";
	} else {
		$sql = "select x.o58_orgao,
														    x.o58_unidade,
														    x.o40_descr,
														    x.o41_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from ($sqlperiodo) as x
														    inner join orcdotacao 	on orcdotacao.o58_anousu = x.e60_anousu
																	 	and orcdotacao.o58_coddot = x.e60_coddot
														    inner join orcorgao 	on orcorgao.o40_orgao = orcdotacao.o58_orgao and o40_anousu = orcdotacao.o58_anousu
														    inner join orcunidade 	on o41_orgao = orcorgao.o40_orgao and o41_unidade = orcdotacao.o58_unidade and o41_anousu = orcorgao.o40_anousu
														    group by x.o58_orgao, x.o58_unidade, x.o40_descr, x.o41_descr";
	}
	//     echo $sql;exit;
	$result1 = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0 and 1 == 2) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result1, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(275, $tam, "TOTALIZA��O POR ORGAO/UNIDADE", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(10, $tam, 'ORGAO', 1, 0, "C", 1);
				$pdf->Cell(60, $tam, "DESCRICAO", 1, 0, "C", 1);
				$pdf->Cell(15, $tam, "UNIDADE", 1, 0, "C", 1);
				$pdf->Cell(80, $tam, "DESCRICAO", 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(30, $tam, "TOTAL A PAGAR", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(10, $tam, $o58_orgao, 0, 0, "R", $p);
			$pdf->Cell(60, $tam, substr($o40_descr, 0, 50), 0, 0, "L", $p);
			$pdf->Cell(15, $tam, $o58_unidade, 0, 0, "L", $p);
			$pdf->Cell(80, $tam, $o41_descr, 0, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, 0, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, 0, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(30, $tam, db_formatar($total, 'f'), 0, 1, "R", $p); //quebra linha
			$t_emp6 += $e60_vlremp;
			$t_liq6 += $e60_vlrliq;
			$t_anu6 += $e60_vlranu;
			$t_pag6 += $e60_vlrpag;
			$t_total6 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				//  $pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(140, $tam, "", "T", 0, "L", 0);
				$pdf->Cell(25, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(30, $tam, db_formatar($t_total6, 'f'), "T", 1, "R", 1); //quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
		}

	}

}
$pdf->output();
?>
