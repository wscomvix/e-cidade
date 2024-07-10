<?php

namespace ECidade\Configuracao\Formulario\Model;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Factory para Model de Formulario especifico do eSocial
 *
 * @package  ECidade\Configuracao\Formulario\Model
 * @author   Robson de Jesus
 */
class FormularioModelFactory
{

    public static function get($tipo)
    {
        $formularioModel = null;
        switch ($tipo) {
            case Tipo::S1010:
                $formularioModel = new \ECidade\Configuracao\Formulario\Model\FormularioS1010;
                break;
            case Tipo::S2200:
                $formularioModel = new \ECidade\Configuracao\Formulario\Model\FormularioS2200;
                break;
            default:
                throw new \Exception('Model fomul�rio n�o encontrado.');
        }

        return $formularioModel;
    }

    public static function getStatic($tipo)
    {
        switch ($tipo) {
            case Tipo::RUBRICA:
                return '\ECidade\Configuracao\Formulario\Model\FormularioS1010';
                break;
            case Tipo::CADASTRAMENTO_INICIAL:
                return '\ECidade\Configuracao\Formulario\Model\FormularioS2200';
                break;
            default:
                return '\ECidade\Configuracao\Formulario\Model\FormularioEspecificoBase';
        }

        return null;
    }
}
