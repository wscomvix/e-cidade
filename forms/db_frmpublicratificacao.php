<?
//MODULO: licitacao
$clliclicita->rotulo->label();

$sSqlTipo = 'SELECT l03_pctipocompratribunal,
                    l20_tipojulg
                        FROM liclicita
                        JOIN cflicita ON l20_codtipocom = l03_codigo WHERE l20_codigo = ' . $l20_codigo;

$rsTipo = db_query($sSqlTipo);
$l20_tipojulg = db_utils::fieldsMemory($rsTipo, 0)->l20_tipojulg;

if (isset($l20_codigo)) {

    if ($l20_codigo != "") {
        $comissao = $clliccomissaocgm->sql_record($clliccomissaocgm->sql_query_file(null, 'l31_codigo,l31_liccomissao,l31_numcgm, (select cgm.z01_nome from cgm where z01_numcgm = l31_numcgm) as z01_nome, l31_tipo', null, "l31_licitacao=$l20_codigo"));
        for ($i = 0; $i < $clliccomissaocgm->numrows; $i++) {
            $comisaoRes = db_utils::fieldsMemory($comissao, $i);
            if ($comisaoRes->l31_tipo == 2) {
                $respRaticodigo = $comisaoRes->l31_numcgm;
                $respRatinome = $comisaoRes->z01_nome;
            } else if ($comisaoRes->l31_tipo == 8) {
                $respPubliccodigo = $comisaoRes->l31_numcgm;
                $respPublicnome = $comisaoRes->z01_nome;
            }
        }
    }
}

?>
<form name="form1" method="post" action="" style="margin-left: 20%;margin-top: 2%;" onsubmit="return js_IHomologacao(this);">
    <fieldset style="width: 62.5%">
        <legend>
            <b>Dispensa/Inexigibilidade</b>
        </legend>

        <table>
            <tr>
                <td>
                    <?
                    db_ancora("Licita��o:", "js_pesquisaLicitacao(true);", $db_opcao);
                    ?>
                </td>
                <td>
                    <?
                    db_input('l20_codigo', 10, $Il20_codigo, true, 'text', $db_opcao, " onchange='js_pesquisaLicitacao(false);'")
                    ?>
                    <?
                    db_input('l20_objeto', 40, $Il20_objeto, true, 'text', 3, '')
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Tipo de Processo:</strong>
                </td>
                <td>
                    <?
                    $al20_tipoprocesso = array("0" => "Selecione", "1" => "1-Dispensa", "2" => "2-Inexigibilidade", "3" => "3-Inexigibilidade por credenciamento/chamada p�blica", "4" => "4-Dispensa por chamada publica","5" => "5-Dispensa para Registro de Pre�os","6" => "6-Inexigibilidade para Registro de Pre�os");
                    db_select("l20_tipoprocesso", $al20_tipoprocesso, true, 3, "", "", "");
                    db_input('l03_pctipocompratribunal', 45, $l03_pctipocompratribunal, true, 'text', 3, 'style="display: none;"');
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Tl20_codepartamento ?>">
                    <?
                    db_ancora("Resp. Ratifica��o:", "js_pesquisal31_numcgm(true,'respRaticodigo','respRatinome');", $db_opcao)

                    ?>
                </td>
                <td>
                    <?
                    db_input('respRaticodigo', 10, $respRaticodigo, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respRaticodigo','respRatinome');");
                    db_input('respRatinome', 45, $respRaticodigo, true, 'text', 3, "");
                    ?>
                </td>
            </tr>

            <tr id="trdtlimitecredenciamento" <?php echo (in_array($l03_pctipocompratribunal, array(102, 103))) ? '' : 'style="display: none"'; ?>>
                <td>
                    <strong>Data Final do Credenciamento:</strong>
                </td>
                <td>
                    <?
                    db_inputdata("l20_dtlimitecredenciamento", $l20_dtlimitecredenciamento, true, $db_opcao, "style=width: 100%;", "", "");
                    ?>
                </td>
            </tr>

            <tr>
                <td nowrap title="<?= @$Tl20_dtpubratificacao ?>">
                    <strong>Data Publica��o Termo Ratifica��o:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('l20_dtpubratificacao', $l20_dtpubratificacao, true, $db_opcao, "", "", "");
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Tl20_codepartamento ?>">
                    <?
                    db_ancora("Resp. pela Publica��o:", "js_pesquisal31_numcgm(true,'respPubliccodigo','respPublicnome');", $db_opcao)

                    ?>
                </td>
                <td>
                    <?
                    db_input('respPubliccodigo', 10, $Il20_codepartamento, true, 'text', $db_opcao, "onchange=js_pesquisal31_numcgm(false,'respPubliccodigo','respPublicnome');");
                    db_input('respPublicnome', 45, $Il20_descricaodep, true, 'text', 3, "");
                    ?>
                </td>
            </tr>

            <tr>
                <td nowrap title="<?= @$Tl20_veicdivulgacao ?>">
                    <strong>Veiculo de Divulga��o:</strong>
                </td>
                <td>
                    <?
                    db_textarea('l20_veicdivulgacao', 0, 53, $Il20_veicdivulgacao, true, 'text', $db_opcao, "onkeyup='limitaTextarea(this);'", "", "#ffffff", 50);
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <?php

    if (!empty($l20_codigo)) {

        $sCampos  = "DISTINCT pc81_codprocitem,pc23_vlrun, pc11_seq, pc11_codigo, pc11_quant, pc11_vlrun, m61_descr, pc01_codmater, pc01_descrmater, pc11_resum";

        $joinPrecoReferencia = false;
        if (in_array($l03_pctipocompratribunal, array(102, 103))) {
            $sCampos .= ", si02_vlprecoreferencia";
            $valorUnitario = 'si02_vlprecoreferencia';
            $joinPrecoReferencia = true;
        } elseif (in_array($l03_pctipocompratribunal, array(100, 101))) {
            $sCampos .= ",'-' as pc23_vlrun";
            $valorUnitario = 'pc23_vlrun';
        } else {
            $valorUnitario = 'pc11_vlrun';
        }
        $sOrdem   = "pc11_seq";
        $sWhere   = "liclicitem.l21_codliclicita = {$l20_codigo} ";

        if ($l03_pctipocompratribunal != "103" && $l03_pctipocompratribunal != "102") {
            $sWhere  .= "and pc24_pontuacao = 1";
        }

        $sSqlItemLicitacao = $clhomologacaoadjudica->sql_query_itens(null, $sCampos, $sOrdem, $sWhere, $joinPrecoReferencia);

        $sResultitens = $clhomologacaoadjudica->sql_record($sSqlItemLicitacao);
        $aItensLicitacao = db_utils::getCollectionByRecord($sResultitens);
        $numrows = $clhomologacaoadjudica->numrows;
    }

    if ($numrows > 0) {
        ?>
            <table style="width: 65%;">
                <tr class="DBgrid">
                    <td class="table_header" style="width: 33px; height:30px;" onclick="marcarTodos();">M</td>
                    <td class="table_header" style="width: 40px">Ordem</td>
                    <td class="table_header" style="width: 50px">Item</td>
                    <td class="table_header" style="width: 235px">Descri��o Item</td>
                    <td class="table_header" style="width: 50px">Unidade</td>
                    <?php if ($valorUnitario != '') : ?>
                        <td class="table_header" style="width: 72px">Valor Unit�rio</td>
                    <?php endif; ?>
                    <td class="table_header" style="width: 125px">Quantidade Licitada</td>
                </tr>
                <?php foreach ($aItensLicitacao as $key => $aItem) :
                    $iItem = $aItem->pc81_codprocitem;
                ?>
                    <tr class="DBgrid">
                        <td class="table_header" style="width: <?= !$valorUnitario ? '58px' : '32px' ?>">
                            <input type="checkbox" class="marca_itens[<?= $iItem ?>]" name="aItonsMarcados" value="<?= $iItem ?>" id="<?= $iItem ?>">
                        </td>
        
                        <td class="linhagrid" style="width: <?= !$valorUnitario ? '74px' : '44px' ?>">
                            <?= $aItem->pc11_seq ?>
                            <input type="hidden" name="" value="<?= $aItem->pc11_seq ?>" id="<?= $iItem ?>">
                        </td>
        
                        <td class="linhagrid" style="width: <?= !$valorUnitario ? '92px' : '52px' ?>">
                            <?= $aItem->pc81_codprocitem ?>
                            <input type="hidden" name="" value="<?= $aItem->pc81_codprocitem ?>" id="<?= $iItem ?>">
                        </td>
        
                        <td class="linhagrid" style="width: <?= !$valorUnitario ? '421px' : '260px' ?>">
                            <?= $aItem->pc01_descrmater ?>
                            <input type="hidden" name="" value="<?= $aItem->pc01_descrmater ?>" id="<?= $iItem ?>">
                        </td>
        
                        <td class="linhagrid" style="width: <?= !$valorUnitario ? '90px' : '55px' ?>">
                            <?= $aItem->m61_descr ?>
                            <input type="hidden" name="" value="<?= $aItem->m61_descr ?>" id="<?= $iItem ?>">
                        </td>
        
                        <?php if ($valorUnitario != '') : ?>
                            <td class="linhagrid" style="width: 80px">
                                <?= $aItem->$valorUnitario ?>
                                <input type="hidden" name="" value="<?= $aItem->$valorUnitario ?>" id="<?= $iItem ?>">
                            </td>
                        <?php endif; ?>
        
                        <td class="linhagrid" style="width: <?= !$valorUnitario ? '204px' : '120px' ?>">
                            <?= $aItem->pc11_quant ?>
                            <input type="hidden" name="" value="<?= $aItem->pc11_quant ?>" id="<?= $iItem ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php } ?>
    
        </div>
        <div style="margin-left: 25%;">
            <?php if ($db_opcao == 11 || $db_opcao == 1) : ?>
                <input name="Incluir" type="submit" id="incluir" value="Incluir">
            <?php elseif ($db_opcao == 22 || $db_opcao == 2) : ?>
                <input name="Alterar" type="button" id="excluir" value="Alterar" onclick="js_AHomologacao()">
            <?php else : ?>
                <input name="Excluir" type="button" id="excluir" value="Excluir" onclick="js_EHomologacao()">
            <?php endif; ?>
            <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa(<?= $db_opcao == 1 ? false : true ?>);">
        </div>
</form>

<script>
    db_opcao = <?= $db_opcao ?>;

    js_verificatipoproc();

    function js_pesquisa(ratificacao = false) {
        if (ratificacao) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_publicratificacao',
                'func_liclicita.php?credenciamento=true&situacao=10&ratificacao=true&dispensas=true&ocultacampos=true&funcao_js=parent.js_preenchepesquisa|l20_codigo|l20_objeto|tipocomtribunal', 'Pesquisa', true);
        } else {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_publicratificacao',
                'func_liclicita.php?credenciamento=true&situacao=1&ratificacao=false&dispensas=true&ocultacampos=true&funcao_js=parent.js_preenchepesquisa|l20_codigo', 'Pesquisa', true);
        }
    }

    function js_preenchepesquisa(chave) {
        js_findTipos(chave);
    }

    function js_retornoConsulta(chave, licitacao) {
        db_iframe_publicratificacao.hide();

        if (db_opcao === 33 || db_opcao === 3) {
            window.location.href = "lic1_publicratificacao003.php?chavepesquisa=" + chave + "&l03_pctipocompratribunal=" +
                licitacao.l03_pctipocompratribunal + "&l20_tipoprocesso=" + licitacao.l20_tipoprocesso;
        } else if (db_opcao === 22 || db_opcao === 2) {
            window.location.href = "lic1_publicratificacao002.php?chavepesquisa=" + chave + "&l03_pctipocompratribunal=" +
                licitacao.l03_pctipocompratribunal + "&l20_objeto=" + licitacao.l20_objeto;
        } else if (db_opcao == 1) {
            window.location.href = "lic1_publicratificacao001.php?l20_codigo=" + chave + "&l03_pctipocompratribunal=" +
                licitacao.l03_pctipocompratribunal + "&l20_objeto=" + licitacao.l20_objeto;
        }
    }

    /**
     * Funcao para mostrar campo data final quando for chamada publica e credenciamento
     *
     */

    function js_verificatipoproc() {
        const tipocompratribunal = document.getElementById("l03_pctipocompratribunal");

        if (tipocompratribunal === 102 || tipocompratribunal === 103) {
            document.getElementById('trdtlimitecredenciamento').style.display = "";
        }
    }

    /**
     * Fun��o para limitar texaarea*
     *
     */
    function limitaTextarea(valor) {
        var qnt = valor.value;
        quantidade = 80;
        total = qnt.length;

        if (total <= quantidade) {
            resto = quantidade - total;
            document.getElementById('contador').innerHTML = resto;
        } else {
            document.getElementById(valor.name).value = qnt.substr(0, quantidade);
            alert("Ol�. Para atender  as normas do TCE MG / SICOM, este campo �  limitado. * LIMITE ALCAN�ADO * !");
        }
    }

    /***
     * Fun��o para Busca licita��es e Carregar itens
     *
     */
    function js_pesquisaLicitacao(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_liclicita', 'func_liclicita.php?credenciamento=true' + (db_opcao == 1 ? '&situacao=1&ratificacao=false' : '&situacao=10&ratificacao=true') +
                '+&dispensas=true&ocultacampos=true&funcao_js=parent.js_mostraliclicita1|l20_codigo|l20_objeto|tipocomtribunal|l20_tipoprocesso', 'Pesquisa', true);
        } else {
            if (document.form1.l20_codigo.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_liclicita', 'func_liclicita.php?credenciamento=true' +
                    (db_opcao == 1 ? '&situacao=1' : '&situacao=10') +
                    '&enviada=true&pesquisa_chave=' + document.form1.l20_codigo.value + '&tipoproc=true&dispensas=true&ocultacampos=true&funcao_js=parent.js_mostraliclicita', 'Pesquisa', false);
            } else {
                document.form1.l20_codigo.value = '';
                js_limpaCampos();
            }
        }
    }

    function js_mostraliclicita(chave, erro, chave2, tipoProcesso) {
        document.form1.l20_objeto.value = chave;
        if (erro == true) {
            document.form1.l20_codigo.focus();
            document.form1.l20_codigo.value = '';
        } else {
            if (db_opcao == 1) {
                window.location.href = "lic1_publicratificacao001.php?l20_codigo=" +
                    document.form1.l20_codigo.value + "&l20_objeto=" + chave + "&l20_tipoprocesso=" + tipoProcesso;
            } else {
                window.location.href = "lic1_publicratificacao002.php?chavepesquisa=" +
                    document.form1.l20_codigo.value + "&l20_tipoprocesso=" + tipoProcesso;
            }
        }

    }

    function js_mostraliclicita1(chave1, chave2, chave3, tipoProcesso) {
        if (db_opcao == 1) {
            let caminho = "lic1_publicratificacao001.php?";
            window.location.href = caminho + "l20_codigo=" + chave1
                + "&l20_objeto=" + chave2 + "&l20_tipoprocesso=" + tipoProcesso +  "&l03_pctipocompratribunal=" + chave3;
        } else {
            window.location.href = "lic1_publicratificacao002.php?chavepesquisa=" + chave1 +  "&l20_tipoprocesso=" + tipoProcesso + "&l03_pctipocompratribunal=" + chave3;
        }
        db_iframe_liclicita.hide();
    }

    /**
     * Retorna todos os itens
     */

    function aItens() {
        var itensNum = document.getElementsByName("aItonsMarcados");

        return Array.prototype.map.call(itensNum, function(item) {
            return item;
        });
    }

    function FormataStringData(data) {
        //js_FormatarStringData
        //Funcao para retornar data no formato dd/mm/yyyy
        //para deve ser do tipo yyyy-mm-dd

        var ano = data.split("-")[0];
        var mes = data.split("-")[1];
        var dia = data.split("-")[2];

        return ("0" + dia).slice(-2) + '/' + ("0" + mes).slice(-2) + '/' + ano;
        // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
    }
    /**
     * Marca todos os itens
     *
     */
    function marcarTodos() {

        aItens().forEach(function(item) {

            var check = item.classList.contains('marcado');

            if (check) {
                item.classList.remove('marcado');
            } else {
                item.classList.add('marcado');
            }
            item.checked = !check;

        });
    }

    /**
     * Retorna itens marcados
     */

    function getItensMarcados() {
        return aItens().filter(function(item) {
            return item.checked;
        });
    }

    /**
     *Salva os itens do homologados
     *
     */

    function js_IHomologacao() {
        let itens = getItensMarcados();

        if (document.getElementById('l20_dtpubratificacao').value == '') {
            alert('Campo Data Publica��o Termo de Ratifica��o n�o informado');
            return false;
        }

        if (document.getElementById('respRaticodigo').value == '') {
            alert('Campo Respons�vel pela Ratifica��o n�o informado.');
            return false;
        }

        if (document.getElementById('respPubliccodigo').value == '') {
            alert('Campo Respons�vel pela Publica��o n�o informado.');
            return false;
        }

        if (itens.length < 1) {
            alert('Selecione pelo menos um item da lista.');
            return false;
        }

        var itensEnviar = [];

        try {
            itens.forEach(function(item) {
                let coditem = item.id;
                var novoItem = {
                    l205_item: coditem,
                };
                itensEnviar.push(novoItem);
            });
            salvarCredAjax({
                exec: 'salvarHomo',
                licitacao: document.getElementById('l20_codigo').value,
                l20_dtpubratificacao: document.getElementById('l20_dtpubratificacao').value,
                l20_dtlimitecredenciamento: document.getElementById('l20_dtlimitecredenciamento').value,
                l20_veicdivulgacao: document.getElementById('l20_veicdivulgacao').value,
                respRaticodigo: document.getElementById('respRaticodigo').value,
                respPubliccodigo: document.getElementById('respPubliccodigo').value,
                // l20_justificativa          : document.getElementById('l20_justificativa').value,
                // l20_razao                  : document.getElementById('l20_razao').value,
                itens: itensEnviar,
            }, retornoAjax);
        } catch (e) {
            alert(e.toString());
        }
        return false;
    }

    function salvarCredAjax(params, onComplete) {
        js_divCarregando('Aguarde salvando', 'div_aguarde');
        var request = new Ajax.Request('lic1_credenciamento.RPC.php', {
            method: 'post',
            parameters: 'json=' + JSON.stringify(params),
            onComplete: function(res) {
                js_removeObj('div_aguarde');
                onComplete(res);
            }
        });
    }

    function retornoAjax(res) {
        var response = JSON.parse(res.responseText);
        if (response.status != 1) {
            alert(response.message.urlDecode());
        } else if (response.erro == false) {
            alert('Salvo com sucesso!');
            window.location.href = "lic1_publicratificacao001.php";
        }
    }

    /**
     * Alterar Homologa��o
     *
     */
    function js_AHomologacao() {
        let itens = getItensMarcados();

        if (itens.length < 1) {
            alert('Selecione pelo menos um item da lista.');
            return false;
        }

        if (document.getElementById('l20_dtpubratificacao').value == '') {
            alert('Campo Data Publica��o Termo de Ratifica��o n�o informado.');
            return false;
        }

        if (document.getElementById('respRaticodigo').value == '') {
            alert('Campo Respons�vel pela Ratifica��o n�o informado.');
            return false;
        }

        if (document.getElementById('respPubliccodigo').value == '') {
            alert('Campo Respons�vel pela Publica��o n�o informado.');
            return false;
        }

        var itensEnviar = [];

        try {
            itens.forEach(function(item) {
                let coditem = item.id;
                var novoItem = {
                    l205_item: coditem,
                };
                itensEnviar.push(novoItem);
            });
            aHomoAjax({
                exec: 'alterarHomo',
                licitacao: document.getElementById('l20_codigo').value,
                l20_dtpubratificacao: document.getElementById('l20_dtpubratificacao').value,
                l20_dtlimitecredenciamento: document.getElementById('l20_dtlimitecredenciamento').value,
                l20_veicdivulgacao: document.getElementById('l20_veicdivulgacao').value,
                respRaticodigo: document.getElementById('respRaticodigo').value,
                respPubliccodigo: document.getElementById('respPubliccodigo').value,
                // l20_justificativa          : document.getElementById('l20_justificativa').value,
                // l20_razao                  : document.getElementById('l20_razao').value,
                itens: itensEnviar,
            }, oRetornoAjax);
        } catch (e) {
            alert(e.toString());
        }
        return false;
    }

    function aHomoAjax(params, onComplete) {
        js_divCarregando('Aguarde salvando', 'div_aguarde');
        var request = new Ajax.Request('lic1_credenciamento.RPC.php', {
            method: 'post',
            parameters: 'json=' + JSON.stringify(params),
            onComplete: function(res) {
                js_removeObj('div_aguarde');
                onComplete(res);
            }
        });
    }

    function oRetornoAjax(res) {
        var response = JSON.parse(res.responseText);

        if (response.status == 2) {
            alert(response.message.urlDecode());
        } else if (response.erro == false) {
            alert('Salvo com sucesso!');
            window.location.href = "lic1_publicratificacao001.php";
        }
    }

    /**
     * Fun��o para buscar itens para homologa��o
     */
    function BuscarItens() {

        try {
            carregarItens({
                exec: 'getItensHomo',
                licitacao: document.getElementById('l20_codigo').value,
            }, oretornoitens);
        } catch (e) {
            alert(e.toString());
        }
        return false;
    }

    function carregarItens(params, onComplete) {
        js_divCarregando('Aguarde salvando', 'div_aguarde');
        var request = new Ajax.Request('lic1_credenciamento.RPC.php', {
            method: 'post',
            parameters: 'json=' + JSON.stringify(params),
            onComplete: function(res) {
                js_removeObj('div_aguarde');
                onComplete(res);
            }
        });
    }

    function oretornoitens(res) {
        var oRetornoitens = JSON.parse(res.responseText);
        oRetornoitens.itens.forEach(function(item, x) {
            document.getElementById(item.l203_item).checked = true;
        });

        if (oRetornoitens.dtpublicacao === "") {
            document.getElementById('l20_dtpubratificacao').value = "";
        } else {
            document.getElementById('l20_dtpubratificacao').value = FormataStringData(oRetornoitens.dtpublicacao);
        }

        if (oRetornoitens.dtlimitecredenciamento === "") {
            document.getElementById('l20_dtlimitecredenciamento').value = "";
        } else {
            document.getElementById('l20_dtlimitecredenciamento').value = FormataStringData(oRetornoitens.dtlimitecredenciamento);
        }
    }

    /**
     * Excluir Homologa��o
     *
     */
    function js_EHomologacao() {

        if (document.getElementById('l20_dtpubratificacao').value == '') {
            alert('Campo Data Publica��o Termo de Ratifica��o n�o informado.');
            return false;
        }

        try {
            excluirhomologacao({
                exec: 'excluirHomo',
                licitacao: document.getElementById('l20_codigo').value,
                ratificacao: document.form1.l20_dtpubratificacao.value
            }, oretornoexclusao);
        } catch (e) {
            alert(e.toString());
        }
        return false;
    }

    function excluirhomologacao(params, onComplete) {
        js_divCarregando('Aguarde salvando', 'div_aguarde');
        var request = new Ajax.Request('lic1_credenciamento.RPC.php', {
            method: 'post',
            parameters: 'json=' + JSON.stringify(params),
            onComplete: function(res) {
                js_removeObj('div_aguarde');
                onComplete(res);
            }
        });
    }

    function oretornoexclusao(res) {
        var oRetornoitens = JSON.parse(res.responseText);

        if (oRetornoitens.status == 2) {
            alert(oRetornoitens.message.urlDecode());
        } else if (oRetornoitens.erro == false) {
            alert('Publica��o de Termo de Ratifica��o exclu�da com sucesso!');
            window.location.href = "lic1_publicratificacao003.php";
        }
    }

    function js_findTipos(licitacao) {

        let request = new Ajax.Request('lic4_licitacao.RPC.php', {
            method: 'post',
            exec: 'findTipos',
            asynchronous: false,
            parameters: 'json=' + JSON.stringify({
                exec: 'findTipos',
                iLicitacao: licitacao
            }),
            onComplete: (res) => {
                js_onCompleteTipo(licitacao, res);
            }
        });
    }

    function js_onCompleteTipo(licitacao, response) {
        let oTipos = JSON.parse(response.responseText);
        js_retornoConsulta(licitacao, oTipos.dadosLicitacao);
    }

    function js_limpaCampos() {
        document.getElementById('l20_tipoprocesso_select_descr').value = '';
        document.getElementById('l20_objeto').value = '';
        document.getElementsByName("aItonsMarcados").value = '';
    }

    var varNumCampo;
    var varNomeCampo;

    function js_pesquisal31_numcgm(mostra, numCampo, nomeCampo) {
        varNumCampo = numCampo;
        varNomeCampo = nomeCampo;

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&filtro=1', 'Pesquisa', true, '0', '1');
        } else {
            numcgm = document.getElementById(numCampo).value;
            if (numcgm != '') {
                js_OpenJanelaIframe('', 'db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + numcgm + '&funcao_js=parent.js_mostracgm&filtro=1', 'Pesquisa', false);
            } else {
                document.getElementById(numCampo).value = "";
            }
        }
    }

    function js_mostracgm(erro, chave) {
        document.getElementById(varNomeCampo).value = chave;
        if (erro == true) {
            //  document.form1.l31_numcgm.focus();
            document.getElementById(varNumCampo).value = "";
            alert("Respons�vel n�o encontrado!");
        }
    }

    function js_mostracgm1(chave1, chave2) {

        document.getElementById(varNumCampo).value = chave1;
        document.getElementById(varNomeCampo).value = chave2;
        db_iframe_cgm.hide();
    }
</script>
