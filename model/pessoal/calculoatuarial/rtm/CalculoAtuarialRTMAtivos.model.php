<?php
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

require_once('model/pessoal/calculoatuarial/rtm/CalculoAtuarialRTMBase.model.php');

/**
 * Calculo Atuarial RTM Ativos
 * @author Robson de Jesus <robson.silva@contassconsultoria.com.br>
 */
class CalculoAtuarialRTMAtivos extends CalculoAtuarialRTMBase {

	/**
    * arquivo que sera gerado
    * @var String
    */
	protected $sFile = '/tmp/calc_ativos.txt';

	function __construct()
	{
		
	}

	public function processar($anofolha,$mesfolha,$where) {

	    $sSql = "SELECT DISTINCT rh01_regist ||'# '||
	    servidor.z01_nome ||'# '||
	    servidor.z01_cgccpf ||'# '||
	    coalesce(servidor.z01_pis,' ') ||'# '||
	    CASE
	    WHEN rh01_sexo = 'M' THEN 2
	    ELSE 1
	    END ||'# '||
	    to_char(rh01_nasc, 'DD/MM/YYYY') ||'# '||
	    '1' ||'# '||
	    instituicao.z01_nome ||'# '||
	    instituicao.z01_cgccpf ||'# '||
	    db21_tipopoder ||'# '||
	    '1' ||'# '||
	    CASE
	    WHEN rhfuncao.rh37_funcaogrupo != '2' THEN 4
	    ELSE 2
	    END ||'# '||
	    ' ' ||'# '||
	    CASE
	    WHEN r45_situac = 2 OR r45_situac = 7 THEN '3'
	    WHEN r45_situac = 3 OR r45_situac = 4 OR r45_situac = 9 THEN '13'
	    WHEN r45_situac = 5 THEN '15'
	    WHEN r45_situac = 6 OR r45_situac = 8 THEN '14'
	    ELSE '1'
	    END ||'# '||
	    coalesce(CASE
	    WHEN r45_mesusu = $mesfolha AND r45_situac = 2 OR r45_situac = 7 THEN to_char(r45_dtreto, 'DD/MM/YYYY')
	    ELSE NULL
	    END::varchar, '') 
	    ||'# '||
	    to_char(rh01_admiss, 'DD/MM/YYYY') ||'# '||
	    to_char(rh01_admiss, 'DD/MM/YYYY') ||'# '||
	    rh37_descr ||'# '||
	    to_char(rh01_admiss, 'DD/MM/YYYY') ||'# '||
	    rh37_descr ||'# '||
	    trim(translate(round(COALESCE(sal.salariobase, 0),2)::varchar,'.',','))||'# '||
	    trim(translate(round(COALESCE(sal.total,0),2)::varchar,'.',',')) ||'# '||
	    ' ' 
	    ||'# '||
	    trim(translate(round(COALESCE(sal.vlrcontribuicao,0),2)::varchar,'.',',')) 
	    ||'# '||
	    ' ' ||'# '||
	    ' ' ||'# '||
	    ' ' ||'# '||
	    ' ' ||'# '||
	    ' ' ||'# '||
	    case when rh02_abonopermanencia = 't' then 1 else 2 end ||'# '||
	    ' ' ||'# '||

	    (SELECT count(*)
	    FROM rhdepend
	    WHERE rh31_regist = rh01_regist) ||'# '||

	    CASE
	    WHEN rh01_estciv = 4 THEN 9
	    ELSE rh01_estciv
	    END 
	    ||'# '||

	    COALESCE((SELECT to_char(rh31_dtnasc, 'DD/MM/YYYY')
	    FROM rhdepend
	    WHERE rh31_regist = rh01_regist
	    AND rh31_gparen = 'C' LIMIT 1),'')
	    ||'# '||

	    COALESCE((SELECT CASE
	    WHEN rh31_especi = 'S' THEN 2
	    ELSE 1
	    END AS condicao
	    FROM rhdepend
	    WHERE rh31_regist = rh01_regist
	    AND rh31_gparen = 'C' LIMIT 1)::varchar,'' )
	    ||'# '||

	    COALESCE(
	    (SELECT to_char(rh31_dtnasc, 'DD/MM/YYYY')
	    FROM rhdepend
	    WHERE rh31_regist = rh01_regist
	    ORDER BY rh31_dtnasc DESC
	    LIMIT 1
	    ), '')  
	    ||'# '||

	    COALESCE((SELECT to_char(rh31_dtnasc, 'DD/MM/YYYY')
	    FROM rhdepend
	    WHERE rh31_regist = rh01_regist
	    ORDER BY rh31_dtnasc desc
	    LIMIT 1
	    OFFSET 1),'' )||'# '||

	    COALESCE((SELECT CASE
	    WHEN rh31_especi = 'C'
	    OR rh31_especi = 'S' THEN 2
	    ELSE 1
	    END AS condicao
	    FROM rhdepend
	    WHERE rh31_codigo =
	    (SELECT rh31_codigo
	    FROM rhdepend
	    WHERE rh31_regist = rh01_regist
	    ORDER BY rh31_dtnasc desc
	    LIMIT 1
	    OFFSET 1))::varchar,'') ||'# '||
	    2 ||'# '||
	    '' ||'# '||
	    rh02_anousu ||'# '||
	    rh02_mesusu 

	    as todo
	    FROM rhpessoal
	    INNER JOIN rhpessoalmov ON rh02_regist = rh01_regist
	    AND rh02_anousu = $anofolha
	    AND rh02_mesusu = $mesfolha
	    AND rh01_instit = ".db_getsession('DB_instit')."
	    INNER JOIN rhlota ON r70_codigo = rh02_lota
	    AND r70_instit = rh02_instit
	    INNER JOIN rhfuncao ON rh37_funcao = rh01_funcao
	    AND rh37_instit = rh02_instit
	    INNER JOIN rhregime ON rh30_codreg = rh02_codreg
	    AND rh30_instit = rh02_instit
	    LEFT JOIN
	    (SELECT r14_regist,
	    sum(CASE
	    WHEN r14_rubric = 'R992' THEN r14_valor
	    ELSE 0
	    END) AS salariobase,
	    sum(CASE
	    WHEN r14_rubric = 'R993' THEN r14_valor
	    ELSE 0
	    END) AS vlrcontribuicao,
	    sum(CASE
	    WHEN r14_pd = 1 THEN r14_valor
	    ELSE 0
	    END) AS total
	    FROM gerfsal
	    WHERE r14_anousu = $anofolha
	    AND r14_mesusu = $mesfolha
	    GROUP BY r14_regist) AS sal ON r14_regist = rh01_regist
	    JOIN db_config ON codigo = rh01_instit
	    JOIN cgm instituicao ON db_config.numcgm=instituicao.z01_numcgm
	    JOIN cgm servidor ON servidor.z01_numcgm = rh01_numcgm
	    LEFT JOIN afasta ON r45_regist = rh01_regist
	    AND r45_anousu = $anofolha
	    AND r45_mesusu = $mesfolha
	    WHERE rh30_vinculo = 'A'
	    AND rh30_regime = 1
	    AND NOT EXISTS
	    (SELECT *
	    FROM rhpesrescisao
	    WHERE rh05_seqpes = rhpessoalmov.rh02_seqpes)
	    $where";

	    $rsResult = db_query($sSql);
	    
	    $iNum = pg_numrows($rsResult);

	    $this->abreArquivo();
	    for($iCont = 0;$iCont < pg_numrows($rsResult);$iCont++) {
	    
	    	db_atutermometro($iCont,$iNum,'calculo_folha',1);
	    	$sLine = db_utils::fieldsMemory($rsResult,$iCont)->todo;
	    	if (empty($sLine)) {
	    		continue;
	    	}
	    	$this->adicionaLinha($sLine);
	    }
	    $this->fechaArquivo();
	}
}