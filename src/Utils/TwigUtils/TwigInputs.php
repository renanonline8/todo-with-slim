<?php
namespace Utils\TwigUtils;

/**
 * Armazena inputs de formulário para uso futuro
 */
class TwigInputs
{
    /**
     * Registra os inputs
     * 
     * @param $inputs Array com inputs
     */
    public function registra(Array $inputs)
    {
        $_SESSION['input'] = $inputs;
    }

    /**
     * Retorna os inputs
     * 
     * @return array Array com inputs
     */
    public function retorna()
    {
        return !empty($_SESSION['input']) ? $_SESSION['input'] : null ;
    }

    /**
     * Limpa os inputs
     */
    public function limpa()
    {
        $_SESSION['input'] = array();
    }
}