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

$clrotulo = new rotulocampo;
$clrotulo->label('r02_regime');
$clrotulo->label('r02_descr');
$clrotulo->label('r02_codigo');
$clrotulo->label('r02_hrssem');
$clrotulo->label('r02_hrsmen');
$clrotulo->label('r02_tipo');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "CADASTRO DE PADR�ES";
$head4 = "PER�ODO : ".$mes." / ".$ano;

$sql = "
        select distinct r02_tipo, r02_descr,r02_hrsmen,r02_hrssem,round(r02_valor,2) as r02_valor 
	from padroes 
	where r02_anousu = $ano 
	  and r02_mesusu = $mes
		and r02_instit = ".db_getsession("DB_instit")."
	order by r02_descr
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem C�digos cadastrados no per�odo de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$pre = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(40,$alt,'PADR�O',1,0,"C",1);
      $pdf->cell(20,$alt,'H.MENSAIS',1,0,"R",1);
      $pdf->cell(20,$alt,'H.SEMAN.',1,0,"R",1);
      $pdf->cell(20,$alt,'VALOR',1,0,"C",1);
      $pdf->cell(20,$alt,'TIPO',1,1,"C",1);
//      $total = 0;
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1)
     $pre = 0;
   else
     $pre = 1;
     
   $pdf->setfont('arial','',7);
   $pdf->cell(40,$alt,$r02_descr,0,0,"L",$pre);
   $pdf->cell(20,$alt,$r02_hrssem,0,0,"R",$pre);
   $pdf->cell(20,$alt,$r02_hrsmen,0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($r02_valor,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,$r02_tipo,0,1,"C",$pre);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(110,$alt,'TOTAL DE REGISTROS  : '.$total,"T",0,"C",0);

$pdf->Output();
   
?>