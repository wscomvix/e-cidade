<?
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_liborcamento.php");
include("fpdf151/assinatura.php");
include("classes/db_orcparamrel_classe.php");
include("libs/db_libcontabilidade.php");
include("libs/db_libtxt.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcppa_classe.php");


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$clorcppa = new cl_orcppa;

/*
 * ----------------------------------------------------------
 *  selecionar todos os fontes do orcpparec que estejam no complano
 *  mas que n�o tenham liga��o com o conplanoreduz 
 */
//echo $sql  = $clorcppa->sql_query_exporta($exercicio, null, " orcppa.*, orcppaval.* ", null, "");
$sql = "select orcppa.*, orcppaval.*
			from orcppa
                inner join orcppaval on o24_codppa = o23_codppa
            where orcppaval.o24_exercicio = $exercicio          
            EXCEPT
            select *
            from (
               ".$sql  = $clorcppa->sql_query_exporta($exercicio, null, " orcppa.*, orcppaval.* ", null, "")."               
             ) as x
            ";            
$res = pg_exec($sql);
//db_criatabela($res);exit;
if (pg_numrows($res) == 0 ){      
    db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado ! ');   
}
/*
 * 
 *   -------------------------------------------------------------------------------------------------
 */
$head2 = "RELATORIO DE EXPORTA��O";
$head4 = "RELA��O DOS ELEMENTOS DE DESPESA QUE NAO SERAO EXPORTADOS";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 4;

$pdf->addpage();

  $pdf->cell(15,$alt,"ORGAO",  1,0,"C",0);
   $pdf->cell(15,$alt,"UNIDADE", 1,0,"C",0);
   $pdf->cell(15,$alt,'FUNCAO', 1,0,"C",0);
//   $pdf->cell(30,$alt,'ELEMENTO', 1,0,"L",0);
   $pdf->cell(90,$alt,'INSTITUICAO', 1,0,"L",0);
   $pdf->cell(20,$alt,'VALOR', 1,0,"L",0);
   $pdf->ln();

$quantidade = 0;
$valor_total =0;

for($x=0;$x <pg_numrows($res);$x++){
   db_fieldsmemory($res,$x);
	
   $pdf->cell(15,$alt,$o23_orgao,  1,0,"C",0);
   $pdf->cell(15,$alt,$o23_unidade, 1,0,"C",0);
   $pdf->cell(15,$alt,$o23_funcao, 1,0,"C",0);
//   $pdf->cell(30,$alt,$o25_codele, 1,0,"L",0);
   @$pdf->cell(90,$alt,$o55_instit, 1,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($o24_valor,'f'), 1,0,"L",0);
   $pdf->ln();

  $quantidade++;
  $valor_total =  $valor_total + $o24_valor; 

}

$pdf->ln();

$pdf->cell(40,$alt,'TOTAL de REGISTROS',1,0,"L",0);
$pdf->cell(20,$alt,$quantidade, 1,0,"R",0);
$pdf->ln();
$pdf->cell(40,$alt,'SOMATORIO',1,0,"L",0);
$pdf->cell(20,$alt,db_formatar($valor_total,'f'),1,0,"R",0);
$pdf->ln();


$pdf->Output();


?>