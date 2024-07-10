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
require_once("model/caixa/ExtratoBancarioSicom.model.php");

$head3 = "CONTAS EXTRATO BANC�RIO SICOM";

$extratoBancario = new ExtratoBancarioSicom($_GET['ano'], db_getsession("DB_instit"));

// echo "<pre>";print_r($extratoBancario);exit;

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

foreach($extratoBancario->getContasHabilitadas() as $conta)
{
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
    {
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(0,$alt,"CONTAS PENDENTES DE ENVIO",0,0,"L",0);
        $pdf->ln();
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,$alt,"Reduzido",1,0,"C",1);
        $pdf->cell(15,$alt,"TCE",1,0,"C",1);    
        $pdf->cell(90,$alt,"Reduzidos Agrupados",1,0,"C",1);   
        $pdf->cell(70,$alt,substr("Descricao", 0, 50),1,0,"L",1);   
        $pdf->cell(15,$alt,"Banco",1,0,"C",1);   
        $pdf->cell(15,$alt,"Agencia",1,0,"C",1);   
        $pdf->cell(15,$alt,"Conta",1,0,"C",1);
        $pdf->cell(20,$alt,"Tipo",1,0,"C",1);   
        $pdf->cell(20,$alt,"Limite",1,1,"C",1);   
        $troca = 0;
    }

    if ($conta->situacao == 'pendente') {
        $pdf->setfont('arial','',7);
        $pdf->cell(15,$alt,$conta->reduzido,1,0,"C",0);
        $pdf->cell(15,$alt,$conta->codtce,1,0,"C",0);    
        $pdf->cell(90,$alt,implode(", ", $conta->contas),1,0,"C",0);
        $pdf->cell(70,$alt,substr($conta->descricao, 0, 50),1,0,"L",0);  
        $pdf->cell(15,$alt,$conta->banco,1,0,"C",0);
        $pdf->cell(15,$alt,$conta->agencia. "-" . $conta->digito_agencia,1,0,"C",0);
        $pdf->cell(15,$alt,$conta->conta . "-" . $conta->digito_conta,1,0,"C",0);
        $pdf->cell(20,$alt,$conta->tipo,1,0,"C",0);
        $pdf->cell(20,$alt,$conta->limite,1,1,"C",0);
        $total ++;
    }
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"CONTAS ENVIADAS",0,0,"L",0);
$pdf->ln();

foreach($extratoBancario->getContasHabilitadas() as $conta)
{
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
    {
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,$alt,"Reduzido",1,0,"C",1);
        $pdf->cell(15,$alt,"TCE",1,0,"C",1);    
        $pdf->cell(90,$alt,"Reduzidos Agrupados",1,0,"C",1);   
        $pdf->cell(70,$alt,substr("Descricao", 0, 50),1,0,"L",0);  
        $pdf->cell(15,$alt,"Banco",1,0,"C",1);   
        $pdf->cell(15,$alt,"Agencia",1,0,"C",1);   
        $pdf->cell(15,$alt,"Conta",1,0,"C",1);
        $pdf->cell(20,$alt,"Tipo",1,0,"C",1);   
        $pdf->cell(20,$alt,"Limite",1,1,"C",1);  
        $troca = 0;
    }

    if ($conta->situacao == 'enviado') {
        $pdf->setfont('arial','',7);
        $pdf->cell(15,$alt,$conta->reduzido,1,0,"C",0);
        $pdf->cell(15,$alt,$conta->codtce,1,0,"C",0);    
        $pdf->cell(90,$alt,implode(", ", $conta->contas),1,0,"C",0);
        $pdf->cell(70,$alt,substr($conta->descricao, 0, 50),1,0,"L",0);  
        $pdf->cell(15,$alt,$conta->banco,1,0,"C",0);
        $pdf->cell(15,$alt,$conta->agencia. "-" . $conta->digito_agencia,1,0,"C",0);
        $pdf->cell(15,$alt,$conta->conta . "-" . $conta->digito_conta,1,0,"C",0);
        $pdf->cell(20,$alt,$conta->tipo,1,0,"C",0);
        $pdf->cell(20,$alt,$conta->limite,1,1,"C",0);
        $total ++;
    }
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>