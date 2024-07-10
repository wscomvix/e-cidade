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
 * var oAjaxRequest = new AjaxRequest('con1_departamentos.RPC.php', {exec: 'listarDepartamentos', codusuario : 1}, callBackRetorno);
 * oAjaxRequest.setMessage('Buscando departamentos...');
 * oAjaxRequest.execute();
 *
 * @param {string} sPathFile
 * @param {object} oParameters
 * @param {function} fnCallback
 * @constructor
 */
AjaxRequest = function(sPathFile, oParameters, fnCallback) {

    /**
     * C�digo da requisi��o
     * @type {number}
     */
    this.id = 0;

    /**
     * Caminho do arquivo que receber� a requisi��o
     * @type {string}
     */
    this.sPathFile = sPathFile;

    /**
     * Fun��o de callback que ser� executada ap�s a conclus�o da requisi��o
     * - Ela ser� devolvida com dois par�metros fnCallback(oObject, lErro)
     * @type {Function}
     */
    this.fnCallback = fnCallback;

    /**
     * Par�metros da requisi��o
     * @type {object}
     */
    this.oParameters = oParameters;

    /**
     * Asynchronous
     * @type {boolean}
     */
    this.lAsynchronous = true;

    /**
     * Mensagem padr�o
     * @type {string}
     */
    this.sMessage = "Aguarde...";

    /**
     * Arquivos
     * @type {Array}
     */
    this.aFiles = [];

    /**
     * @param {boolean} lAsynchronous
     */
    this.asynchronous = function (lAsynchronous) {
        this.lAsynchronous = lAsynchronous;
        return this;
    };

    /**
     * @param sMessage
     */
    this.setMessage = function(sMessage) {

        this.sMessage = sMessage;
        return this;
    };


    this.setParameters = function(oParameters) {
        this.oParameters = oParameters;
    };

    this.setCallBack   = function(fnCallBack) {
        this.fnCallback = fnCallBack;
    }

    /**
     * Adiciona um arquivo a ser carregado
     * @param {object} oInput
     */
    this.addFileInput = function(oInput) {

        this.aFiles.push(oInput);
        return this;
    }
};

/**
 * ID unico da requisicao
 * @type {number}
 */
AjaxRequest._uid = 0;

/**
 * Construtor est�titco
 */
AjaxRequest.create = function(sPathFile, oParameters, fnCallback) {
    return new AjaxRequest(sPathFile, oParameters, fnCallback);
};

/**
 * Executa uma requisi��o ajax
 */
AjaxRequest.prototype.execute = function() {

    js_divCarregando(this.sMessage, 'msgBox' + (++this.id));

    var oRequest = {
        method : 'post',
        asynchronous : this.lAsynchronous,
        onComplete : function(oAjax) {

            js_removeObj('msgBox' + this.id);

            var oReturn = JSON.parse(oAjax.responseText);

            if (oReturn.erro == undefined) {
                oReturn.erro = true;

            }

            this.fnCallback(oReturn, oReturn.erro);
        }.bind(this)
    };

    /**
     * Verifica se deve fazer upload de algum arquivo e muda os cabe�alhos da requisi��o
     */
    if (this.aFiles.length > 0) {

        /**
         * Cria o formulario de upload com os campos de arquivos
         */
        var oForm = new FormData();

        oForm.append("json", Object.toJSON(this.oParameters));

        this.aFiles.each(function(oFile) {
            oForm.append(oFile.name, oFile.files.item(0));
        });

        oRequest.contentType = '';
        oRequest.encoding    = false;
        oRequest.postBody    = oForm;

        /**
         * Altera a fun��o setRequestHeader do XMLHttpRequest quando for utilizar o FormData
         *
         * No Chrome esta setando a propriedade Content-Type vazio devido a uma limita��o
         * do Prototype.js, o que acaba ocasionando um erro na requisi��o
         */
        if (window.XMLHttpRequest) {

            var bkpSetRequestHeader = XMLHttpRequest.prototype.setRequestHeader;

            XMLHttpRequest.prototype.setRequestHeader = function(header, value) {

                if (value != '') {
                    bkpSetRequestHeader.call(this, header, value);
                }
            }
        }

    } else {
        oRequest.parameters = 'json=' + Object.toJSON(this.oParameters).replace('%','');
    }

    new Ajax.Request(this.sPathFile, oRequest);

    /**
     * Restaura a fun��o setRequestHeader do XMLHttpRequest
     */
    if (this.aFiles.length > 0 && window.XMLHttpRequest) {
        XMLHttpRequest.prototype.setRequestHeader = bkpSetRequestHeader;
    }
};
