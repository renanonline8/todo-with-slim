<?php
namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Utils\Controller\Controller;

use App\CSRF\ArrayCSRF;
use App\Models\Usuario;
use App\Models\Task;

final class ControllerDashboard extends Controller
{
    public function dash(Request $request, Response $response, Array $args)
    {
        //CSRF
        $csrf = new ArrayCSRF(
            $this->csrf->getTokenNameKey(),
            $this->csrf->getTokenValueKey(),
            $request
        );
        $this->twigArgs->adcDados('csrf', $csrf->getCSRF());

        //Dados da sessão
        $sessao = $this->twigArgs->retArgs()['sessao'];

        //Usuario ID
        $id = $sessao['id'];

        //Localizar id do usuário
        $user = Usuario::find_by_id_externo($id);
        
        //Obter tarefas do usuário
        $tasks = Task::find_all_by_id_user_and_checked($user->id, false);

        //Converter para array as tarefas
        $listTasks = [];
        foreach ($tasks as $key => $value) {
            array_push($listTasks, $value->to_array(
                array(
                    'only' => array('id_ext', 'task')
                )
            ));
        }

        //Adicionar ao twig
        $this->twigArgs->adcDados('tasksList', $listTasks);

        //Retornar view
        return $this->view->render($response, 'dashboard.twig', $this->twigArgs->retArgs());
    }
}