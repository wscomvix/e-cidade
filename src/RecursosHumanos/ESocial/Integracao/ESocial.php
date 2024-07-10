<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use \ECidade\V3\Extension\Registry;
use \ECidade\Core\Config;
use DBHttpRequest;
use Exception;

/**
 * Classe respons�vel pelo envio dos dados do eSocial para a API do e-cidade
 */
class ESocial
{
    /**
     * Classe para requisi��o HTTP
     *
     * @var DBHttpRequest
     */
    private $httpRequest;

    /**
     * Configura��o da aplica��o
     *
     * @var Config
     */
    private $config;

    /**
     * Recurso para envio dos dados
     *
     * @var string
     */
    private $recurso;

    /**
     * Dados a ser enviados
     *
     * @var array|\stdClass
     */
    private $dados;

    public function __construct(Config $config, $recurso = NULL)
    {
        $this->config = $config;

        // $this->validaConfiguracao();

        $dadosAPI = $this->config->get('app.api');
        $httpRequest = new DBHttpRequest(Registry::get('app.config'));
        $httpRequest->addOptions(array(
            'baseUrl' => $dadosAPI['esocial']['url'] ,
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            )
        ));
        $this->httpRequest = $httpRequest;

        // $httpRequest->addOptions(array(
        //     'headers' => array(
        //         'X-Access-Token' => $this->login()
        //     )
        // ));

        $this->recurso = $recurso;
    }

    /**
     * Seta os dados a ser enviados
     *
     * @param \stdClass[] $dados
     */
    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Realiza a requisi��o enviando os dados para API
     */
    public function request()
    {
        
        $data = json_encode($this->dados);

        $resultSend = $this->httpRequest->send($this->recurso, 'POST', array(
            'body' => $data
        ));

        $result = json_decode($this->httpRequest->getBody());

        if ($this->httpRequest->getResponseCode() >= 400) {
            throw new Exception($result->message);
        }
        
        return $resultSend;
    }

    /**
     * Retorna o c�digo de resposta HTTP da requisi��o
     *
     * @return integer
     */
    public function getResponseCode()
    {
        return $this->httpRequest->getResponseCode();
    }

    /**
     * Valida se foi configurado o acesso a API.
     * @throws Exception
     * @return void
     */
    private function validaConfiguracao()
    {
        $dadosAPI = $this->config->get('app.api');
        if (empty($dadosAPI['esocial']['url']) ||
            empty($dadosAPI['esocial']['login']) ||
            empty($dadosAPI['esocial']['password'])) {
            throw new Exception("Entre em contato com o administrador do sistema para configurar acesso ao eSocial.");
        }
        return true;
    }

    /**
     * Efetua o login na API do eSocial
     *
     * @return string
     */
    private function login()
    {
        $dadosAPI = $this->config->get('app.api');
        unset($dadosAPI['esocial']['url']);

        $this->httpRequest->send('/auth/login', 'POST', array(
            'body' => \json_encode((object) $dadosAPI['esocial'])
        ));

        $result = json_decode($this->httpRequest->getBody());

        if (!isset($result->access_token)) {
            throw new Exception("Erro ao efetuar login na API.");
        }

        return $result->access_token;
    }

    /**
     * Retorna o numero do protocolo de envio request realizado
     *
     * @return string
     */
    public function getProtocoloEnvioLote()
    {
        return (string) $this->httpRequest->getObjXml()->dadosRecepcaoLote->protocoloEnvio;
    }

    /**
     * Retorna o id do processamento
     *
     * @return string
     */
    public function getIdProcessamento()
    {
        $arrId = current($this->httpRequest->getObjXml()->retornoEventos->evento->attributes());
        return (string) current($arrId);
    }

    /**
     * Retorna o numero do recibo de envio
     *
     * @return string
     */
    public function getNumeroRecibo()
    {
        return $this->httpRequest->getObjXml()->retornoEventos->evento->retornoEvento->eSocial->retornoEvento->recibo->nrRecibo;
    }

    /**
     * Retorna descricao da resposta no status
     *
     * @return string
     */
    public function getDescResposta()
    {
        return (string) $this->httpRequest->getObjXml()->status->descResposta;
    }

    /**
     * Retorna codigo da resposta da conusulta do envio
     *
     * @return string
     */
    public function getCdRespostaProcessamento()
    {
        return (string) $this->httpRequest->getObjXml()->retornoEventos->evento->retornoEvento->eSocial->retornoEvento->processamento->cdResposta;
    }

    /**
     * Retorna descricao da resposta da conusulta do envio
     *
     * @return string
     */
    public function getDescRespostaProcessamento()
    {
        return (string) $this->httpRequest->getObjXml()->retornoEventos->evento->retornoEvento->eSocial->retornoEvento->processamento->ocorrencias->ocorrencia->descricao;
    }

    /**
     * Retorna codigo da resposta da conusulta do envio
     *
     * @return string
     */
    public function getCdRespostaConsulta()
    {
        return (string) $this->httpRequest->getObjXml()->status->cdResposta;
    }

    /**
     * Retorna o objeto xml do evento 5001 retornado pelo envio dos eventos 1200,2299,2399
     *
     * @return object
     */
    public function getObjXmlEvtRetorno()
    {
        $aXmlAttributes = $this->httpRequest->getObjXml()->retornoEventos->evento->tot;
        foreach ($aXmlAttributes as $aXmlEventos) {
            $evento = current(current($aXmlEventos->attributes()));
            if (in_array($evento, array("S5001","S5011"))) {
                return $aXmlEventos->eSocial;
            }
        }
        return null;
    }

}
