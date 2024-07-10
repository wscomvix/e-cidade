<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);
require_once("interfaces/IRegraLancamentoContabil.interface.php");
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Classe responsavel por criar a regra de lancamento de inscricao dos Restos a pagar
 * @author Iuri Guntchnigg
 * @package Contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.2 $
 * Class RegraLancamentoEncerramentoRP
 */
class RegraLancamentoEncerramentoVariacoesPatrimoniais implements IRegraLancamentoContabil {

  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil           = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $oLancamentoEventoContabil = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);
    $iContaEvento = '0';
    if (!$oLancamentoEventoContabil || count($oLancamentoEventoContabil->getRegrasLancamento()) == 0) {
      return false;
    }

    $aRegrasDoLancamento = $oLancamentoEventoContabil->getRegrasLancamento();
    if (count($aRegrasDoLancamento) == 0) {
      return false;
    }

    $oRegraLancamento = $aRegrasDoLancamento[0];
    $oMovimentoConta  = $oLancamentoAuxiliar->getMovimentacaoContabil();
    
    /*  A conta refer�ncia n�o dever� mais vir da transa��o.
        Ser� de acordo com o PRIMEIRO e QUINTO n�vel da conta do movimento, obedecendo as regras do TCE-MG.

    $iContaEvento     = $oRegraLancamento->getContaDebito();
    if ($oLancamentoAuxiliar->getContaReferencia() != "") {
      $iContaEvento = $oLancamentoAuxiliar->getContaReferencia();
    }*/

    $sSql  = "SELECT si09_tipoinstit FROM db_config
              JOIN infocomplementaresinstit ON si09_instit = codigo
              WHERE codigo = ".db_getsession("DB_instit");

    $rsInst = db_query($sSql);      
    $oInst  = db_utils::fieldsMemory($rsInst, 0)->si09_tipoinstit;

    $sContaSuperDefitConsolidadacao     = '237110101';
    $sContaSuperDefitIntraOFSS          = '237120101';
    $sContaSuperDefitInterOFSSUniao     = '237130101';
    $sContaSuperDefitInterOFSSEstado    = '237140101';
    $sContaSuperDefitInterOFSSMunicipio = '237150101';
    $sComplemnto = '0101';

    if($oInst == '1'){ // C�maras
      $sComplemnto = '02'.$sComplemnto;
    }else if($oInst == '2' ){ // Prefeituras
      $sComplemnto = '01'.$sComplemnto;
    }else if($oInst == '5'){ // Previd�ncias
      $sComplemnto = '03'.$sComplemnto;
    }else if($oInst == '51'){ // Cons�rcios
      $sComplemnto = '50'.$sComplemnto;
    }else { // Outros
      $sComplemnto = '04'.$sComplemnto;
    }    
   // echo $oInst." conta ".$sContaSuperDefitInterOFSSMunicipio.$sComplemnto;
    $oDaoConPlano  = db_utils::getDao("conplano");
    $sWhere        = " c61_reduz = {$oMovimentoConta->getConta()} ";
    $sSqlConplano  = $oDaoConPlano->sql_query_reduz(null, " substr(c60_estrut,5,1) as subtitulo ", null, $sWhere);
    $rsConplano    = $oDaoConPlano->sql_record($sSqlConplano);

    $sConPlanoSubTitulo  = db_utils::fieldsMemory($rsConplano)->subtitulo;

    $oDaoConPlanoRef  = db_utils::getDao("conplano");
    
    if($sConPlanoSubTitulo == '5'){// InterOFSSMunicipio 
        $sWhere        = " c60_estrut = '{$sContaSuperDefitInterOFSSMunicipio}{$sComplemnto}' ";
        $sSqlConplanoRef  = $oDaoConPlanoRef->sql_query_reduz(null, " c61_reduz ", null, $sWhere);
        $rsConplanoRef    = $oDaoConPlanoRef->sql_record($sSqlConplanoRef);
        $iContaEvento  = db_utils::fieldsMemory($rsConplanoRef)->c61_reduz;
    }else if($sConPlanoSubTitulo == '2'){// IntraOFSS
      $sWhere        = " c60_estrut = '{$sContaSuperDefitIntraOFSS}{$sComplemnto}' ";
        $sSqlConplanoRef  = $oDaoConPlanoRef->sql_query_reduz(null, " c61_reduz ", null, $sWhere);
        $rsConplanoRef    = $oDaoConPlanoRef->sql_record($sSqlConplanoRef);
        $iContaEvento  = db_utils::fieldsMemory($rsConplanoRef)->c61_reduz;
    }else if($sConPlanoSubTitulo == '3'){// InterOFSSUniao
      $sWhere        = " c60_estrut = '{$sContaSuperDefitInterOFSSUniao}{$sComplemnto}' ";
        $sSqlConplanoRef  = $oDaoConPlanoRef->sql_query_reduz(null, " c61_reduz ", null, $sWhere);
        $rsConplanoRef    = $oDaoConPlanoRef->sql_record($sSqlConplanoRef);
        $iContaEvento  = db_utils::fieldsMemory($rsConplanoRef)->c61_reduz;      
    }else if($sConPlanoSubTitulo == '4'){// InterOFSSEstado
      $sWhere        = " c60_estrut = '{$sContaSuperDefitInterOFSSEstado}{$sComplemnto}' ";
        $sSqlConplanoRef  = $oDaoConPlanoRef->sql_query_reduz(null, " c61_reduz ", null, $sWhere);
        $rsConplanoRef    = $oDaoConPlanoRef->sql_record($sSqlConplanoRef);
        $iContaEvento  = db_utils::fieldsMemory($rsConplanoRef)->c61_reduz;      
    }else {// 1 - Consolidadacao
        $sWhere        = " c60_estrut = '{$sContaSuperDefitConsolidadacao}{$sComplemnto}' ";
        $sSqlConplanoRef  = $oDaoConPlanoRef->sql_query_reduz(null, " c61_reduz ", null, $sWhere);
        $rsConplanoRef    = $oDaoConPlanoRef->sql_record($sSqlConplanoRef);
        $iContaEvento  = db_utils::fieldsMemory($rsConplanoRef)->c61_reduz;      
    }

    switch ($oMovimentoConta->getTipoSaldo()) {

      case 'D':

        $oRegraLancamento->setContaCredito($oMovimentoConta->getConta());
        $oRegraLancamento->setContaDebito($iContaEvento);
        break;
      case 'C':

        $oRegraLancamento->setContaCredito($iContaEvento);
        $oRegraLancamento->setContaDebito($oMovimentoConta->getConta());
        break;
    }

    /**
     * A conta sempre � modificada, nao podemos manter os dados no repositorio
     */
    EventoContabilRepository::removerEventoContabil($oEventoContabil);
    EventoContabilLancamentoRepository::removerEventoContabilLancamento($oLancamentoEventoContabil);
    return $oRegraLancamento;
  }

}