<?php
namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Utils\Controller\Controller;

use App\CSRF\ArrayCSRF;

/**
 * Controller de tarefas
 */
final class ControllerTask extends Controller
{
    /**
     * Cadastro de novo usuário
     *
     * @param Request $request
     * @param Response $response
     * @param Array $args
     * @return void
     */
    public function new(Request $request, Response $response, Array $args)
    {
        //Dados da sessão
        $sessao = $this->twigArgs->retArgs()['sessao'];

        
        $task = $request->getParam('new_task');
    }
}