<?php

/**
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


class TipoSlip
{
    private $iTipoSlip;
    private $sDescricao;

    public function setTipoSlip($codSlip)
    {
        $this->iTipoSlip = $codSlip;
    }

    public function getDescricaoTipoSlip()
    {
        $this->setDescricaoTipoSlip();
        return $this->sDescricao[$this->iTipoSlip];
    }

    public function getDescricaoTipoSlipArray()
    {
        $this->setDescricaoTipoSlip();
        return $this->sDescricao;
    }

    public function setDescricaoTipoSlip()
    {
        $this->sDescricao = array(
                '0' => "Todos",
                '01' => "Aplica��o Financeira",
                '02' => "Resgate de Aplica��o Financeira",
                '03' => "Transfer�ncia entre contas banc�rias",
                '04' => "Transfer�ncias de Valores Retidos",
                '05' => "Dep�sito decendial educa��o",
                '06' => "Dep�sito decendial sa�de",
                '07' => "Transfer�ncia da Contrapartida do Conv�nio",
                '08' => "Transfer�ncia entre contas de fontes diferentes",
                '09' => "Transfer�ncia da conta caixa para esta conta",
                '10' => "Saques"
        );
    }

}
