<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("classes/db_rhestagiocurricular_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_GET_VARS);
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$clrhestagiocurricular = new cl_rhestagiocurricular;
$campos = "rhestagiocurricular.*,h83_matricula,cgm.z01_nome,cgm.z01_cgccpf,cgmsupervisor.z01_nome as supervisor, cgmsupervisor.z01_cgccpf as cpfsupervisor";
$result = $clrhestagiocurricular->sql_record($clrhestagiocurricular->sql_query(null, $campos, null, "h83_regist = {$regist} and h83_instit = ".db_getsession("DB_instit")));
$oDados = db_utils::fieldsMemory($result);
if($clrhestagiocurricular->numrows == 0 || !$result) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Estagi�rio n�o encontrado!');
    exit; 
}

$cldbconfig = new cl_db_config;
$campos = "nomeinst,cgc,upper(munic) as munic,uf";
$rsInstit = db_query($cldbconfig->sql_query_file(db_getsession("DB_instit"), $campos));
$oInstit = db_utils::fieldsMemory($rsInstit);

$head2 = "TERMO DE REALIZA��O DE EST�GIO";
$head5 = "Data: ".db_formatar($dataemissao, 'd');

$alt   = 6;
$fonte = 'arial';

$pdf = new PDF();
$pdf->showDateFooter(false);
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();

$pdf->setfont($fonte,'b',12);
$pdf->ln(5);
$pdf->cell(189,6,"TERMO DE REALIZA��O DE EST�GIO",0,1,"C",0);
$pdf->ln(10);
$texto = "Declaramos, para os devidos fins, que o(a) estudante {$oDados->z01_nome}, inscrito(a) no CPF ".db_formatar($oDados->z01_cgccpf, 'cpf').", matriculado(a) na institui��o de ensino {$oDados->h83_instensino} inscrita no CNPJ ".db_formatar($oDados->h83_cnpjinstensino, 'cnpj').", sob o n� de matr�cula {$oDados->h83_matricula}, realizou est�gio na empresa: {$oInstit->nomeinst}, no per�odo de ".db_formatar($oDados->h83_dtinicio, 'd')." � ".db_formatar($oDados->h83_dtfim, 'd').", totalizando a carga hor�ria de {$oDados->h83_cargahorariatotal} horas, sob a supervis�o de {$oDados->supervisor}, inscrito(a) no CPF ".db_formatar($oDados->cpfsupervisor, 'cpf').".";
$pdf->setfont($fonte,'',12);
$pdf->MultiCell(189, $alt, $texto, 0, "J", 0, 0);
$pdf->ln(4);
$mes = strftime('%B', strtotime($dataemissao));
$data = strftime('%d de '.ucfirst($mes).' de %Y', strtotime($dataemissao));
$pdf->MultiCell(189, $alt, "{$oInstit->munic} ({$oInstit->uf}), {$data}.", 0, "J", 0, 0);
$pdf->ln(8);
$pdf->cell(50,6,"",0,0,"C",0);
$pdf->cell(90,6,"","B",1,"C",0);
$pdf->MultiCell(189, $alt, "Supervisor de Est�gio", 0, "C", 0, 0);

$pdf->output();