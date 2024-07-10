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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");

$oJson  = new services_json();
$oParametro = $oJson->decode(str_replace("\\", "", $_POST['json']));

$oRetorno            = new stdClass();
$oRetorno->lErro     = false;
$oRetorno->sMensagem = "";

try {

    switch ($oParametro->exec) {

        /**
         * Salva os dados da regra em um documento
         */
        case "getDadosProcessoProtocolo":

            $iAnoSessao     = db_getsession("DB_anousu");
            $aDadosProcesso = explode("/", $oParametro->sNumeroProcesso);

            if (count($aDadosProcesso) == 2) {
                $iAnoSessao = $aDadosProcesso[1];
            }
            $oInstituicao       = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
            $oProcessoProtocolo = processoProtocolo::getInstanciaPorNumeroEAno($aDadosProcesso[0], $iAnoSessao, $oInstituicao);

            if ( !$oProcessoProtocolo ) {
                throw new Exception("Processo de protocolo ({$aDadosProcesso[0]}/{$iAnoSessao}) n�o encontrado.");
            }

            $oRetorno->iSequencialProcesso = $oProcessoProtocolo->getCodProcesso();
            $oRetorno->iNumeroProcesso     = $aDadosProcesso[0];
            $oRetorno->iAnoProcesso        = $iAnoSessao;
            $oRetorno->sRequerenteProcesso = urlencode($oProcessoProtocolo->getRequerente());

            break;

        case 'getMovimentacoesProcesso' :

            require_once 'model/protocolo/RefactorConsultaProcessoProtocolo.model.php';
            require_once 'classes/db_protprocesso_classe.php';

            $oRefactorProcessoProtocolo = new RefactorConsultaProcessoProtocolo($oParametro->iCodigoProcesso);
            $aMovimentacoes = $oRefactorProcessoProtocolo->getMovimentacoes();
            $oRetorno->aMovimentacoes = array();

            $oProcessoProtocolo = new cl_protprocesso();
            $oProcessoPrincipal = $oProcessoProtocolo->getPrincipal($oParametro->iCodigoProcesso);

            /**
             * Passa urlEncode() em todas as propriedades dos movimentos
             */
            if (count($aMovimentacoes) > 0) {

                foreach ($aMovimentacoes as $oDadosMovimentacao) {

                    foreach ( $oDadosMovimentacao as $sPropridade => $sValor ) {
                        $oDadosMovimentacao->$sPropridade = urlEncode($sValor);
                    }

                    $oRetorno->aMovimentacoes[] = $oDadosMovimentacao;
                }
            }

            if ($oProcessoPrincipal) {

              $oRetorno->lTemProcessoPrincipal = true;

              $oRefactorProcessoPrincipal = new RefactorConsultaProcessoProtocolo($oProcessoPrincipal->p30_procprincipal);
              $aMovimentacoesPrincipal    = $oRefactorProcessoPrincipal->getMovimentacoes();

              if (!empty($aMovimentacoesPrincipal)) {

                $oUltimaMovimentacao = end($aMovimentacoesPrincipal);

                foreach ($oUltimaMovimentacao as $sPropridade => $sValor) {
                  $oUltimaMovimentacao->$sPropridade = urlEncode($sValor);
                }

                $oRetorno->aMovimentacoes[] = $oUltimaMovimentacao;

              }

            }

            break;

        default :
            throw new Exception("Par�metro inv�lido");
            break;

    }

} catch (Exception $oErro) {

    db_fim_transacao(true);
    $oRetorno->lErro     = true;
    $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);
