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

/**
 * Model para retornar configura��es do m�dulo CAIXA
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package caixa
 * @version $Revision: 1.4 $
 */
final class ParametroCaixa {

  const TIPO_TRANSMISSAO_CNAB240 = 1;
  const TIPO_TRANSMISSAO_OBN     = 2;

  const CAMINHO_ARQUIVO = 'legacy_config/financeiro/agenda_pagamento.ini';

  /**
   * Retorna o c�digo do recurso configurado para FUNDEB configurado para a institui��o informada na sess�o. Caso a
   * institui��o n�o possua recurso configurado, ir� retornar 0 (zero)
   * @return integer
   */
  public static function getCodigoRecursoFUNDEB($iInstituicao) {

    $oDaoCaiParametro   = db_utils::getDao('caiparametro');
    $sSqlBuscaParametro = $oDaoCaiParametro->sql_query_file($iInstituicao, "k29_orctiporecfundeb");
    $rsBuscaParametro   = $oDaoCaiParametro->sql_record($sSqlBuscaParametro);

    $iCodigoRecurso = 0;
    if ($oDaoCaiParametro->numrows == 1) {

      $iCodigoRecurso = db_utils::fieldsMemory($rsBuscaParametro, 0)->k29_orctiporecfundeb;
      if (empty($iCodigoRecurso)) {
        $iCodigoRecurso = 0;
      }
    }
    return $iCodigoRecurso;
  }

  /**
   * @param $iTipoTransmissao
   * @return bool
   */
  public static function setTipoTramissaoPadrao($iTipoTransmissao = self::TIPO_TRANSMISSAO_CNAB240) {

    $sConteudoArquivo = "tipo_transmissao={$iTipoTransmissao}";
    $lAlterouArquivo  = file_put_contents(self::CAMINHO_ARQUIVO, $sConteudoArquivo);
    if (!$lAlterouArquivo) {
      return false;
    }
    return true;
  }

  /**
   * @return int
   */
  public static function getTipoTransmissaoPadrao() {

    if (file_exists(self::CAMINHO_ARQUIVO)) {

      $aIniFile = parse_ini_file(self::CAMINHO_ARQUIVO);
      return $aIniFile['tipo_transmissao'];
    }
    return self::TIPO_TRANSMISSAO_CNAB240;
  }
}
