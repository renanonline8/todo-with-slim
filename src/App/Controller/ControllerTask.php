<?php
namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

use Utils\Controller\Controller;

use App\CSRF\ArrayCSRF;
use App\Validacao\ValidacaoRedireciona;
use App\Models\Task;
use App\Models\Usuario;

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

        //Usuario ID
        $id = $sessao['id'];

        //Tarefa
        $taskName = $request->getParam('new_task');

        //Validar
        $validacao = new ValidacaoRedireciona(
            $this->router->pathFor('dashboard')
        );
        $validacao->adicionaRegra(
            v::stringType()
            ->notEmpty()
            ->validate($id),
            15
        );
        $validacao->adicionaRegra(
            v::stringType()
            ->notEmpty()
            ->validate($taskName),
            16
        );
        $validacao->adicionaRegra(
            v::stringType()
            ->length(null, 255)
            ->validate($taskName),
            17
        );
        if (!$validacao->valida()) {
            return $response->withRedirect(
                $validacao->retornaURLErros()
            );
        }

        //Localizar usuario
        $user = Usuario::find_by_id_externo($id);

        //Adicionar tarefa no banco
        $task = new Task();
        $task->id_ext = uniqid();
        $task->task = $taskName;
        $task->id_user = $user->id;
        $task->save();

        return $response->withRedirect(
            $this->router->pathFor('dashboard') .
            '?mensagens=18'
        );
    }

    /**
     * Responsável por marcar tarefa como concluída
     *
     * @param Request $request
     * @param Response $response
     * @param Array $args
     * @return void
     */
    public function checked(Request $request, Response $response, Array $args) {
        //Localizar a tarefa...
        $task = Task::find_by_id_ext($args['id']);
        $valid = new ValidacaoRedireciona(
            $this->router->pathFor('dashboard')
        );
        $valid->adicionaRegra(v::notEmpty()->validate($task), 19);
        if(!$valid->valida()) {
            return $response->withRedirect($valid->retornaURLErros());
        }

        //Verificar se tarefa pertence ao usuário desta tarefa...
        $sessao = $this->twigArgs->retArgs()['sessao'];
        $id = $sessao['id'];
        $usuario = Usuario::find_by_id_externo($id);
        $valid->adicionaRegra(
            v::equals($task->id_user)->validate($usuario->id),
            20
        );
        if(!$valid->valida()) {
            return $response->withRedirect($valid->retornaURLErros());
        }

        //Marcar como concluída...
        $task->checked = true;
        $task->save();

        //Retornar para dashboard...
        return $response->withRedirect(
            $this->router->pathFor('dashboard') . 
            '?mensagens=21'
        );
    }

    public function delete(Request $request, Response $response, Array $args) {
        //Localizar a tarefa
        $task = Task::find_by_id_ext($args['id']);
        $valid = new ValidacaoRedireciona(
            $this->router->pathFor('dashboard')
        );
        $valid->adicionaRegra(v::notEmpty()->validate($task), 19);
        if(!$valid->valida()) {
            return $response->withRedirect($valid->retornaURLErros());
        }

        //Verificar se a tarefa pertence ao usuário desta tarefa
        $sessao = $this->twigArgs->retArgs()['sessao'];
        $id = $sessao['id'];
        $usuario = Usuario::find_by_id_externo($id);
        $valid->adicionaRegra(
            v::equals($task->id_user)->validate($usuario->id),
            20
        );
        if(!$valid->valida()) {
            return $response->withRedirect($valid->retornaURLErros());
        }

        //Apagar tarefa
        $task->delete();

        //Retornar para dashboard
        return $response->withRedirect(
            $this->router->pathFor('dashboard') . 
            '?mensagens=22'
        );
    }
}