<?php
namespace App\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Utils\Controller\Controller;

final class ControllerHome extends Controller
{
    public function index(Request $request, Response $response, Array $args)
    {
        return $this->view->render($response, 'index.twig');
    }
}