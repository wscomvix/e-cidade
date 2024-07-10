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

$campos = "distinct liclicita.l20_codigo,
					liclicita.l20_edital,
					l20_anousu,
					pctipocompra.pc50_descr,
					liclicita.l20_numero,
					(CASE WHEN l20_nroedital IS NULL THEN '-'
						ELSE l20_nroedital::varchar
					END) as l20_nroedital,
					liclicita.l20_datacria as dl_Data_Abertura_Proc_Adm,
					liclicita.l20_dataaber as dl_Data_Emis_Alt_Edital_Convite,
					liclicita.l20_dtpublic as dl_Data_Publica��o_DO,
					liclicita.l20_objeto,
					liclicita.l20_tipojulg";
?>