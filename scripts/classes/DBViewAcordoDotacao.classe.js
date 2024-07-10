/**
 * View para altera��o das dota�oes do Acordo.
 * Permite a altera��o de dota��es de anos anteriores para dota��es
 * do ano atual.
 @author Victor Felipe
 */

DBViewAcordoDotacao = function(iCodigoAcordo, sNameInstance) {

    var me = this;
    this.iCodigoAcordo = iCodigoAcordo;
    this.sNameInstance = sNameInstance;
    this.sUrlRPC = 'com4_alteradotacaoacordo.RPC.php';
    this.aDotacoes = new Array();
    this.iAnoSessao = '';
    this.oWindow = new windowAux('wndAlteracaoSolicitacoes',
        "Altera��o das Dota��es do Acordo " + me.iCodigoAcordo,
        800,
        450
    );

    this.oGridItens = new DBGrid('gridItensSolicitacao');
    this.oGridItens.sNameInstance = sNameInstance + 'oGridItens';
    oWindowDotacaoItem = new windowAux('wndItemDotacao', "Lista de Dota��es do Acordo " + me.iCodigoAcordo, 800, 450);
    oWindowDotacaoItem.setShutDownFunction(function () {
        oWindowDotacaoItem.destroy();
    });

    var sNomeFuncaoAlterarDotacoes = me.sNameInstance + ".alterarDotacoes()";
    var sContent = "<div id='ctnDotacao'>";
    sContent += "  <fieldset>";
    sContent += "    <legend><b>Itens da Dota��o</b></legend>";
    sContent += "    <div id='ctnGridDotacoesItens'></div>";
    sContent += "  </fieldset>";
    sContent += "  <div id='ctnBtnAlterar' style='text-align:center'>";
    sContent += "    <input type='button' id='btnAlterarDotacao' value='Alterar Dota��es'";
    sContent += "           onclick='" + sNomeFuncaoAlterarDotacoes + "'> ";
    sContent += "  </div>";
    sContent += "</div>";

    oWindowDotacaoItem.setContent(sContent);

    var sMsgHelp = "Para alterar a dota��o de todos itens, clique em \"M\" para marcar todos itens de uma dota��o.";
    sMsgHelp += "Clique no botao \'Alterar\" da Dota��o e selecione a nova dota��o. ";
    sMsgHelp += "Para alterar a dota��o de um item, clique no Bot�o \"Alterar\". Para confirmar as altera��es, clique";
    sMsgHelp += " em <b>\"Alterar\"</b>.";
    oMessageBoard = new DBMessageBoard('msgBoardDotacao',
        'Dota��es Retornadas',
        sMsgHelp,
        oWindowDotacaoItem.getContentContainer()
    );
    oWindowDotacaoItem.show();
    oGridDotacoes = new DBGrid('Dotacoes');
    oGridDotacoes.nameInstance = 'oGridDotacoes';
    oGridDotacoes.setHeight(250);
    oGridDotacoes.setCellAlign(new Array("left",
        "right",
        "right",
        "center",
        "center"
    ));

    oGridDotacoes.setCellWidth(new Array('400px',
        ' 70px',
        ' 70px',
        ' 70px',
        ' 45px'
    ));

    oGridDotacoes.setHeader(new Array('Itens',
        'Quantidade',
        'Valor',
        'Dota��o',
        'A��o'
        )
    );
    oGridDotacoes.show($('ctnGridDotacoesItens'));

    /**
     * Retorna as dota��es da solicita��o, bem como seus itens , agrupados por dotacao
     */
    this.getDotacoes = () => {

        let msgDiv = "Carregando Lista de Itens \n Aguarde ...";
        js_divCarregando(msgDiv, 'msgBox');

        let oParam = new Object();
        oParam.exec = 'getAcordoDotacoes';
        oParam.iCodigoAcordo = me.iCodigoAcordo;
        let oAjax = new Ajax.Request(me.sUrlRPC,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: me.retornoGetDotacoes
            }
        );
    }

    /**
     * preenche os dados da Grid das dota��es
     */
    this.retornoGetDotacoes = (oAjax) => {

        js_removeObj('msgBox');
        let oRetorno = eval("(" + oAjax.responseText + ")");

        if(oRetorno.status == 2){
            alert(oRetorno.message.urlDecode());
        }else{
            me.iAnoSessao = oRetorno.iAnoSessao;
            me.aDotacoes = oRetorno.aDotacoes;
            me.renderizaLinhasGrid();
            me.setPropertyImg();
        }
    }

    /* Fun��o utilizada para manusear a imagem da seta */
    this.controlArrow = (iDotacao = null, showAll = false) => {

        if(iDotacao){
            let image = document.getElementsByClassName(`img__dot${iDotacao}`);

            if(image[0].src.includes('seta.gif')){
                image[0].src = 'imagens/setabaixo.gif';
            }else{
                image[0].src = 'imagens/seta.gif';
            }

            me.showItensLine(iDotacao, showAll);

        }else{
            me.showItensLine();
        }
    }

    /* Define o padding na imagem da seta que fica ao lado da palavra 'Dota��o' */
    this.setPropertyImg = () => {
        let imgsArrows = document.querySelectorAll('span > img');

        imgsArrows.forEach(img => {
            img.style.padding = '0px 10px';
            img.style.cursor = 'pointer';
        })

    }

    /**
     * Renderiza os dados da grid
     */
    this.renderizaLinhasGrid = (iCodDot = false) => {

        oGridDotacoes.clearAll(true);

        let iLinha = 0;
        let iTotalDotacoes = 0;

        for (let iCodigoDotacao in me.aDotacoes) {
            if (typeof (me.aDotacoes[iCodigoDotacao]) == 'function') {
                continue;
            }

            me.aDotacoes[iCodigoDotacao].lMarcadoTodos = false;
            let oDotacao = me.aDotacoes[iCodigoDotacao];

            /**
             * Nome da fun��o que ira mostrar o saldo da dota��o
             */
            let sNomeFuncaoDotacao = me.sNameInstance + `.mostrarDadosDotacao(${oDotacao.iDotacao},${oDotacao.iAnoDotacao})`;
            let sNomeFuncaoAlteraDotacao = me.sNameInstance + `.pesquisaDotacaoGrupo('${iCodigoDotacao}', '${oDotacao.sElemento}', '${oDotacao.iAnoDotacao}')`;
            let sNomeFuncaoMarcarTodos = me.sNameInstance + `.marcaTodosItens('${iCodigoDotacao}')`;
            let sNomeFuncaoHide = me.sNameInstance + `.controlArrow('${iCodigoDotacao}')`;

            var aRowDotacao = new Array();
            aRowDotacao[0]  = `<span style='padding:5px;' onclick=${sNomeFuncaoMarcarTodos}><b>M</b></span>&nbsp;`;
            aRowDotacao[0] += `Dota��o <span onclick=${sNomeFuncaoHide}> <img class='img__dot${iCodigoDotacao}' src='imagens/seta.gif'>  </span>`;
            aRowDotacao[0] += `<a onclick='${sNomeFuncaoDotacao}';return false;' href='#'><b>`;
            aRowDotacao[0] += `${oDotacao.iDotacao}</b></a> do Ano <b>${oDotacao.iAnoDotacao}</b>`;
            aRowDotacao[1] = "";
            aRowDotacao[2] = "";
            aRowDotacao[3] = "";

            if (oDotacao.lAutorizado == 'false') {
                aRowDotacao[4] = `<input id='btnAlteraDotacao${iCodigoDotacao}' type='button' value='Alterar'`;
                aRowDotacao[4] += "       onclick=\""+sNomeFuncaoAlteraDotacao+"\" />";
            } else {
                aRowDotacao[4] = `<input id='btnAlteraDotacao${iCodigoDotacao}' onclick='alert(\"Essa Dota��o Possui Itens J� Autorizados.\")'`;
                aRowDotacao[4] += " type='button' value='Alterar' />";
            }

            oGridDotacoes.addRow(aRowDotacao);
            oGridDotacoes.aRows[iLinha].sStyle = 'background-color:#eeeee2;';
            oGridDotacoes.aRows[iLinha].aCells.each((oCell, iCell) => {
                oCell.sStyle += ';border-right: 1px solid #eeeee2;';
            });
            iLinha++;

            oDotacao.aItens.each( (oItem, iIndice) => {

                let sElementoItem = oDotacao.sElemento;

                let sNomeFuncaoDotacaoItem = me.sNameInstance + ".mostrarDadosDotacao(" + oItem.iDotacao + "," + oDotacao.iAnoDotacao + ")";

                if (sElementoItem == 'false' || sElementoItem == false) {
                    sElementoItem = oItem.sElemento;
                }

                let sNomeFuncaoAlteraDotacaoItem = me.sNameInstance + ".pesquisaDotacaoItem('";
                sNomeFuncaoAlteraDotacaoItem += oItem.iDotacao + "'," + iIndice + ", '" + sElementoItem + "',"+oDotacao.iAnoDotacao+")";
                let sFunctionToogleLinha = me.sNameInstance + ".toogleLinhaItem('" + iCodigoDotacao + "'," + iIndice + ")";
                aRowItem = new Array();
                let sChecked = '';
                if (oItem.lAlterado) {
                    sChecked = ' checked="checked"';
                }
                aRowItem[0] = "<span ><input class='chk" + iCodigoDotacao + "' type='checkbox' " + sChecked + " onclick=\"" + sFunctionToogleLinha + "\"";
                aRowItem[0] += "id='chk" + iLinha + "' value='" + oItem.iItem + "'></span> " + oItem.iOrdem + " - " + oItem.sNomeItem.urlDecode();
                aRowItem[1] = oItem.nQuantidade;
                aRowItem[2] = js_formatar(oItem.nValor, "f");

                let sDotacao = "<a onclick='" + sNomeFuncaoDotacaoItem + ";return false;' href='#'>";
                sDotacao += oItem.iDotacao + "<a>";

                if (oItem.iDotacao == null || oItem.iDotacao == '') {

                    sDotacao = "";
                    sDotacao += "Selecionar<a>";
                }

                aRowItem[3]  = "<a onclick='"+sNomeFuncaoDotacaoItem+";return false;' href='#'>";
                aRowItem[3] += oItem.iDotacao+"<a>";

                aRowItem[3] = sDotacao;

                aRowItem[4] = "<input id='btnAlteraDotacaoItem" + iIndice + "' type='button' value='Alterar'";
                aRowItem[4] += "       onclick=\"" + sNomeFuncaoAlteraDotacaoItem + "\" />";
                oGridDotacoes.addRow(aRowItem);
                oGridDotacoes.aRows[iLinha].isSelected = oItem.lAlterado;

                me.setClassLine(iLinha, iCodigoDotacao);

                if (oItem.lAlterado) {
                    oGridDotacoes.aRows[iLinha].setClassName('marcado');
                }

                oItem.iLinhaNaGrid = iLinha;
                iLinha++;

            });
            iTotalDotacoes++;
        }

        oGridDotacoes.renderRows();
        oGridDotacoes.setNumRows(iTotalDotacoes);

        if(iCodDot){
            /* Trecho utilizado somente quando a fun��o alteraDotacaoGrupo � chamada */
            me.controlArrow(iCodDot, true);
        }else{
            me.controlArrow();
        }
    }

    /**
     * Mostra a tela de saldo da Dota��o
     */
    this.mostrarDadosDotacao = (iDotacao, iAno) => {

        js_OpenJanelaIframe('',
            'db_iframe_dotacao',
            'func_saldoorcdotacao.php?coddot=' + iDotacao + '&anousu=' + iAno,
            'Saldo Dota��o',
            true);
        $('Jandb_iframe_dotacao').style.zIndex = '10000';
    }

    /**
     * Abre janela para alterar a Dota��o do grupo de itens
     */
    this.pesquisaDotacaoGrupo = (sDotacao, sElemento, iAnoDot) => {

        sDotacaoAtual = sDotacao;
        iAnoDotGrupo = iAnoDot;

        let sFuncaoRetorno = 'funcao_js=parent.' + me.sNameInstance + '.alteraDotacaoGrupo|o58_coddot';

        js_OpenJanelaIframe('',
            'db_iframe_alterarDotacao',
            'func_permorcdotacao.php?obriga_depto=nao&elemento=' + sElemento + '&' + sFuncaoRetorno,
            'Escolha uma Dota��o',
            true);
        $('Jandb_iframe_alterarDotacao').style.zIndex = '10000';
    }

    /**
     * Altera as dota��es de todos os itens que possuem uma mesma Dota��o.
     * @param {integer} C�digo da Nova Dota��o
     */
    this.alteraDotacaoGrupo = (iCodigoDotacao) => {
        let keyDotAnterior = sDotacaoAtual;

        if (me.aDotacoes[sDotacaoAtual]) {

            me.aDotacoes[sDotacaoAtual].iDotacao = iCodigoDotacao;
            me.aDotacoes[sDotacaoAtual].iAnoDotacao = me.iAnoSessao;

            me.aDotacoes[sDotacaoAtual].aItens.each( (oItem, iInd) => {

                oItem.iDotacao = iCodigoDotacao;
                oItem.iAnoDotacao = me.iAnoSessao;
                oItem.lAlterado = true;
                oItem.iAnoDotAnterior = iAnoDotGrupo;
                me.marcaLinhaItem(oItem.iLinhaNaGrid);

            });
        }
        delete sDotacaoAtual;
        delete iAnoDotGrupo;
        db_iframe_alterarDotacao.hide();
        me.renderizaLinhasGrid(keyDotAnterior);
    }

    /* Seta as classes nas TRs para manipula��o da exibi��o/oculta��o das linhas */
    this.setClassLine = (iLine, iDotacao) => {

        oGridDotacoes.aRows[iLine].addClassName('tr__'+iDotacao);
        oGridDotacoes.aRows[iLine].addClassName('tr__conteudo');

    }

    /* Exibe a tr referente a dota��o selecionada */
    this.showItensLine = (iDotacao = null, showAll = false) => {
        if(!iDotacao){
            let aLinhas = document.getElementsByClassName('tr__conteudo');

            for(let count = 0; count < aLinhas.length; count++){
                aLinhas[count].style.display = 'none';
            }
        }else{
            let aLinhas = document.getElementsByClassName('tr__'+iDotacao);

            if(showAll){
                aLinhas = document.getElementsByClassName('tr__conteudo');

                for(let count = 0; count < aLinhas.length; count++){
                    aLinhas[count].style.display = aLinhas[count].classList.contains('tr__'+iDotacao) ? '' : 'none';
                }
            }else{
                for(let count = 0; count < aLinhas.length; count++){
                    aLinhas[count].style.display = aLinhas[count].style.display == 'none' ? '' : 'none';
                }
            }

        }
    }

    /**
     * Abre janela para alterar a Dota��o de um item especifico;
     */
    this.pesquisaDotacaoItem = (sDotacao, iIndiceItem, sElemento, iAnoDot) => {
        sDotacaoAtual = sDotacao;
        iAnoDotAtual = iAnoDot;
        iIndiceItemAtual = iIndiceItem;
        let sFuncaoRetorno = 'funcao_js=parent.' + me.sNameInstance + '.alteraDotacaoItem|o58_coddot';

        js_OpenJanelaIframe('',
            'db_iframe_alterarDotacao',
            'func_permorcdotacao.php?obriga_depto=sim&elemento=' + sElemento + '&' + sFuncaoRetorno,
            'Escolha uma Dota��o',
            true);
        $('Jandb_iframe_alterarDotacao').style.zIndex = '10000';
    }

    /**
     * Realiza a altera��o da Dota��o no item
     * @param {integer} iCodigoDotacao C�digo da dota��o
     */
    this.alteraDotacaoItem = (iCodigoDotacao) => {
        let keyDotAnterior = sDotacaoAtual+iAnoDotAtual;

        if (me.aDotacoes[keyDotAnterior]) {

            if (me.aDotacoes[keyDotAnterior].aItens[iIndiceItemAtual]) {

                me.aDotacoes[keyDotAnterior].aItens[iIndiceItemAtual].iDotacao = iCodigoDotacao;
                me.aDotacoes[keyDotAnterior].aItens[iIndiceItemAtual].iAnoDotacao = me.iAnoSessao;
                me.aDotacoes[keyDotAnterior].aItens[iIndiceItemAtual].lAlterado = true;
                me.aDotacoes[keyDotAnterior].aItens[iIndiceItemAtual].iAnoDotAnterior = iAnoDotAtual;
            }
        }

        delete sDotacaoAtual;
        delete iIndiceItemAtual;
        delete iAnoDotAtual;
        db_iframe_alterarDotacao.hide();
        me.renderizaLinhasGrid(keyDotAnterior);
    }

    /**
     *Realiza as altera��es das dota��es dos itens selecionados.
     */
    this.alterarDotacoes = () => {

        let oRowsSelecionadas = oGridDotacoes.getSelection();

        let oParam = new Object();
        oParam.exec = 'alteraDotacoesAcordo';
        oParam.iCodigoAcordo = me.iCodigoAcordo;
        oParam.aItens = new Array();

        for (let iCodigoDotacao in me.aDotacoes) {

            if (typeof (me.aDotacoes[iCodigoDotacao]) == 'function') {
                continue;
            }
            let oDotacao = me.aDotacoes[iCodigoDotacao];

            oDotacao.aItens.each( (oItem, iIndice) => {

                let oItemAlterar = new Object();

                if (oItem.lAlterado) {

                    oItemAlterar.iCodigoDotacaoItem = oItem.iDotacaoSequencial;
                    oItemAlterar.iCodigoItem = oItem.iCodigoItem;
                    oItemAlterar.iCodigoDotacao = oItem.iDotacao;
                    oItemAlterar.iAnoDotacao = oItem.iAnoDotacao;
                    oItemAlterar.iAnoDotAnterior = oItem.iAnoDotAnterior;
                    oParam.aItens.push(oItemAlterar);
                }
            });
        }
        if (oParam.aItens.length == 0) {

            alert('Nenhuma dota��o foi modificada!\nProcessamento cancelado.');
            return false;
        }
        let iNumeroItens = new String(oParam.aItens.length);
        let sMensagemConfirmacao = 'Confirma a altera��o da dota��o dos ';
        sMensagemConfirmacao += iNumeroItens + "(" + iNumeroItens.extenso() + ") itens selecionados?";
        if (!confirm(sMensagemConfirmacao)) {
            return false;
        }

        let msgDiv = "Alterando dota��es modificadas. \n Aguarde ...";
        js_divCarregando(msgDiv, 'msgBox');

        let oAjax = new Ajax.Request(me.sUrlRPC,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: me.retornoAlteracaoDotacoes
            }
        );

    }

    /**
     *fun��o de retorno a�s a execu��o da altera��o dos dados da Dota��o
     */
    this.retornoAlteracaoDotacoes = (oAjax) => {

        js_removeObj('msgBox');

        let oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 2) {
            alert(oRetorno.message.urlDecode());
        } else {

            alert('Dota��es dos itens selecionados, modificados com sucesso.');
            me.getDotacoes();
            me.beforeSave();
        }
    }

    /**
     *Marca uma linha da grid
     */
    this.marcaLinhaItem = (iLinha) => {

        oGridDotacoes.aRows[iLinha].select(true);
        $(oGridDotacoes.aRows[iLinha].sId).style.color = 'green';
        oGridDotacoes.aRows[iLinha].setClassName('marcado');
    }

    /**
     * Controla a marca��o dos checboxes dos itens
     */
    this.toogleLinhaItem = (sDotacao, iIndiceItem) => {

        if (me.aDotacoes[sDotacao].aItens[iIndiceItem]) {
            with (me.aDotacoes[sDotacao].aItens[iIndiceItem]) {

                if (lAlterado) {

                    oGridDotacoes.aRows[iLinhaNaGrid].select(false);
                    lAlterado = false;
                } else {

                    lAlterado = true;
                    oGridDotacoes.aRows[iLinhaNaGrid].select(true);
                }

                me.setClassLine(iLinhaNaGrid, sDotacao);
            }
        }
    }

    this.beforeSave = () => {
        return true;
    }

    this.onBeforeSave = (sFunction) => {
        me.beforeSave = sFunction;
    }

    /**
     *Marca todos os itens que possuiem o mesmo hash de dotacao
     */
    this.marcaTodosItens = (sDotacao) => {

        if (me.aDotacoes[sDotacao]) {

            let lMarcar = true;
            if (me.aDotacoes[sDotacao].lMarcadoTodos) {

                lMarcar = false;
                me.aDotacoes[sDotacao].lMarcadoTodos = false;
            } else {
                me.aDotacoes[sDotacao].lMarcadoTodos = true;
            }

            me.aDotacoes[sDotacao].aItens.each((oItem, iIndice) => {

                with (oItem) {

                    $('chk' + iLinhaNaGrid).checked = lMarcar;
                    oGridDotacoes.aRows[iLinhaNaGrid].select(lMarcar);
                    lAlterado = lMarcar;
                    me.setClassLine(iLinhaNaGrid, sDotacao);
                }
            });
        }
    }

}