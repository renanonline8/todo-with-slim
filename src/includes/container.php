<?php
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../../templates');

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};

$container['ini'] = function($c) {
    $ini = new \Utils\LeitorINI\LeitorINI(__DIR__ . "/../../app.ini");
    return $ini;
};

require __DIR__ . '/activerecord.php';

$container['debugLog'] = function($c) use ($container) {
    $varsINI = $container->ini->retornaVariaveis();
    $caminho = __DIR__ . $varsINI['debug_log']['caminho'];
    $log = new Monolog\Logger('app');
    $log->pushHandler(
        new Monolog\Handler\StreamHandler($caminho, Monolog\Logger::DEBUG)
    );
    return $log;
};

$container['twigArgs'] = function($c) use ($container) {
    $twigArgs = new \Utils\TwigUtils\TwigArgs();
    return $twigArgs;
};

$container['upload'] = function($c) use ($container) {
    $ini = $container->ini->retornaVariaveis();
    $upload = new \Utils\Upload\Upload(__DIR__ . $ini['upload']['path']);
    return $upload;
};

$container['swiftTransport'] = function($c) use ($container) {
    $ini = $container->ini->retornaVariaveis();
    $transport = (
            new \Swift_SmtpTransport(
                $ini['swift_mailer']['smtp_url'], 
                $ini['swift_mailer']['smtp_port'], 
                $ini['swift_mailer']['security']
            )
        )
        ->setUsername($ini['swift_mailer']['username'])
        ->setPassword($ini['swift_mailer']['password'])
    ;
    return $transport;
};

$container['forgotPassJwt'] = function ($c) use ($container) {
    $ini = $container->ini->retornaVariaveis();
    return new \Utils\ForgotPass\ForgotPassJwt($ini['forgot_pass_jwt']['secret_key']);
};

$container['csrf'] = function ($c) use ($container) {
    return new \Slim\Csrf\Guard();
};

//Controllers
require_once __DIR__ . '/controllers.php';