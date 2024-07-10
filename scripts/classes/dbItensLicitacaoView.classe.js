function dbViewItensLicitacao(sNomeInstance, oNode, destinacaoExclusiva) {


    var me = this, aItensPosicao = new Array();

    this.aPeriodoItensNovos = new Array()
    this.sInstance = sNomeInstance;
    this.sUrlRpc = 'lic4_licitacao.RPC.php';

    oNode.style.display = 'none';

    sContent = " <table>";
    sContent += "   <tr> ";
    sContent += "     <td> ";
    sContent += "       <fieldset> ";
    sContent += "         <legend>Itens</legend> ";
    sContent += "         <div id='ctnGridItens' style=\"width: 1000px\"></div> ";
    sContent += "       </fieldset> ";
    sContent += "     </td> ";
    sContent += "   </tr> ";
    sContent += " </table> ";
    
    oNode.innerHTML = sContent;
    oNode.style.display = '';

    this.pesquisaItensLicitacao = (params) => {
        let oParam = {
            exec: 'getItensLicitacao',
            iProcCompra: params.iProcCompra,
            iLicitacao: params.iLicitacao
        }

        me.oGridItens.clearAll(true);

        new AjaxRequest(me.sUrlRpc, oParam, (oRetorno, erro) => {

            if(oRetorno.itens){
                me.preencheItens(oRetorno.itens);
            }

        }).setMessage("Aguarde, pesquisando itens.")
            .execute();
    }

    /**
     * Monta a tela principal com os itens do processo de compra
     */
    this.main = () => {

        /**
         * Itens
         */
        me.oGridItens = new DBGrid('oGridItens');
        me.oGridItens.nameInstance = me.sInstance + '.oGridItens';
        me.oGridItens.setCheckbox(0);
        me.oGridItens.setCellAlign(['center', 'center', 'center', 'center','center', 'center', "center", 'center', 'center']);
        me.oGridItens.setCellWidth(["7%", '8%', "30%", "17%", "10%", "10%", "8%", "10%", "10%", ""]);
        me.oGridItens.setHeader(["C�digo", "N� Item", "Descri��o", "Complemento do Item", "Qtde", "Unidade", "ME/EPP", "Qtde Exclusiva", "Sigiloso", ""]);
        me.oGridItens.setHeight(200);
        me.oGridItens.show($('ctnGridItens'));
        
    }

    me.show = (oParams) => {
        me.main();
        me.pesquisaItensLicitacao(oParams);
    }

    me.hide = () => {
        oNode.style.display = 'none';
    }

    this.preencheItens = (aItens) => {

        me.oGridItens.clearAll(true);

        var aEventsIn  = ["onmouseover"];
        var aEventsOut = ["onmouseout"];
        aDadosHintGrid = new Array();
        let aSelecionados = [];

        aItens.each( (oItem, iSeq) => {

            let aLinha = new Array();
            aLinha[0] = oItem.codigoitem ? oItem.codigoitem : ' - ';
            aLinha[1] = oItem.seqitem;
            aLinha[2] = oItem.descritem ? oItem.descritem.urlDecode() : ' - ';
            aLinha[3] = oItem.complitem ? oItem.complitem.urlDecode() : ' - ';
            aLinha[4] = oItem.qtditem;
            aLinha[5] = oItem.unidade;
            
            oInputQuantidade = new DBTextField('quantidade' + iSeq, 'quantidade' + iSeq, '');
            oInputQuantidade.addStyle("width", "100%");
            oInputQuantidade.setClassName("text-right");
            oInputQuantidade.addEvent("onInput", "this.value = this.value.replace(/[^0-9\.]/g, '')");
            oInputQuantidade.addEvent("onChange", ";" + me.sInstance + ".js_checaQtdeExclusiva("+iSeq+", this.value);");
            oInputQuantidade.setReadOnly(true);
            
            aLinha[6] = new DBComboBox('meEpp' + iSeq, 'meEpp' + iSeq,null,'100%');
            aLinha[6].addItem('0', 'N�o');
            aLinha[6].addItem('1', 'Sim');
            aLinha[6].addEvent("onChange", ";" + me.sInstance + ".js_liberaQtdeExclusiva("+iSeq+", this.value);");
            
            aLinha[6].lDisabled = oItem.marcado;
            
            if(destinacaoExclusiva == 1){
                aLinha[6].lDisabled = true;
            }
            
            
            aLinha[7] = oInputQuantidade;
            aLinha[7].lDisabled = oItem.marcado;

            aLinha[8] = new DBComboBox('Sigilo' + iSeq, 'Sigilo' + iSeq,null,'100%');
            aLinha[8].addItem('f', 'N�o');
            aLinha[8].addItem('t', 'Sim');
            
            aLinha[9] = oItem.procitem;
            
            if(oItem.marcado){
                aSelecionados.push(iSeq);
            }

            me.oGridItens.addRow(aLinha, false, oItem.marcado, oItem.marcado);
            
            let sTextEvent  = " ";
            
            if (aLinha[1] !== '') {
                sTextEvent += "<b>Item: </b>"+aLinha[1];
            } else {
                sTextEvent += "<b>Nenhum dado � mostrar</b>";
            }

            let oDadosHint       = new Object();
            oDadosHint.idLinha   = `oGridItensrowoGridItens${iSeq}`;
            oDadosHint.sText     = sTextEvent;
            aDadosHintGrid.push(oDadosHint);

        });

        for(let count=0; count < aSelecionados.length; count++){
            for(let i=0; i < 10; i++){
                me.oGridItens.aRows[aSelecionados[count]].aCells[i].addClassName('linha__marcada');
            }
        }

        me.oGridItens.renderRows();

        aDadosHintGrid.each( (oHint, id) => {
            var oDBHint    = eval("oDBHint_"+id+" = new DBHint('oDBHint_"+id+"')");
            oDBHint.setText(oHint.sText);
            oDBHint.setShowEvents(aEventsIn);
            oDBHint.setHideEvents(aEventsOut);
            oDBHint.setPosition('B', 'L');
            oDBHint.setUseMouse(true);
            oDBHint.make($(oHint.idLinha), 2);
        });

        this.js_liberaQtdeExclusiva = (sequencial, valor) => {

            if(parseInt(valor)){
                document.getElementById(`quantidade${sequencial}`).classList.remove('readonly');
                document.getElementById(`quantidade${sequencial}`).removeAttribute('readonly');
            }else{
                document.getElementById(`quantidade${sequencial}`).value = '';
                document.getElementById(`quantidade${sequencial}`).classList.add('readonly');
                document.getElementById(`quantidade${sequencial}`).setAttribute('readonly', false);
            }
            
        }

        this.js_checaQtdeExclusiva = (sequencial, valor) => {
            
            valor = parseFloat(valor);
            let valorCelula = parseFloat(me.oGridItens.aRows[sequencial].aCells[5].getContent());
            
            if(!valor || valor < 0){
                alert('Quantidade Exclusiva informada inv�lida. Verifique!');
                document.getElementById(`quantidade${sequencial}`).value = '';
                document.getElementById(`quantidade${sequencial}`).focus();
                return;
            }

            if(valor > valorCelula){
                alert('Qtde Exclusiva informada � maior que a quantidade do item.');
                document.getElementById(`quantidade${sequencial}`).value = '';
                document.getElementById(`quantidade${sequencial}`).focus();
                return;
            }

        }
    }

}
