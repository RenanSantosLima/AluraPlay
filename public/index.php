<?php

use Alura\Mvc\Controller\Controller;
use Alura\Mvc\Controller\DeleteVideoController;
use Alura\Mvc\Controller\EditVideoController;
use Alura\Mvc\Controller\Error404Controller;
use Alura\Mvc\Controller\NovoVideoController;
use Alura\Mvc\Controller\VideoFormController;
use Alura\Mvc\Controller\VideoListController;
use Alura\Mvc\Repository\VideoRepository;

    require_once __DIR__ . "/../vendor/autoload.php";

    /*$dbPath = __DIR__ . "/../banco.sqlite";
    $pdo = new PDO("sqlite:$dbPath");*/
    //$pdo = new PDO("mysql:host=localhost;dbname=aluraplay", 'root', '');
    //$videoRepository = new VideoRepository($pdo);

    $routes = require_once __DIR__ . "/../config/routes.php";

    /** ConteinerInterface $diConteiner */
    $diConteiner = require_once __DIR__ . "/../config/dependencies.php";

    $pathInfo = $_SERVER['PATH_INFO'] ?? '/';
    $httpMethod = $_SERVER['REQUEST_METHOD'];

    //por padrão se coloca no começo do arquivo antes de tudo. Porém se tiver tudo certo pode ser você quiser
    session_start();
    
    if (isset($_SESSION['logado'])) {
        $originalInfo = $_SESSION['logado'];
        unset($_SESSION['logado']);
        session_regenerate_id();
        $_SESSION['logado'] = $originalInfo;
    }

    $isLoginRoute = $pathInfo === '/login';
    if (!array_key_exists('logado',$_SESSION) && !$isLoginRoute) {
        header("Location: /login");
        return;
    }


    $key = "$httpMethod|$pathInfo";
    if (array_key_exists($key, $routes)) {
        $controllerClass = $routes["$httpMethod|$pathInfo"];

        $controller = $diConteiner->get($controllerClass);

        
        //antigo------------------------------------------
        //$controller = new $controllerClass($videoRepository);
    }else {
        $controller = new Error404Controller();
    }

    $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

    $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
        $psr17Factory, // ServerRequestFactory
        $psr17Factory, // UriFactory
        $psr17Factory, // UploadedFileFactory
        $psr17Factory  // StreamFactory
    );

    $request = $creator->fromGlobals();
    
    /** @var RequestHandlerInterface $controller */
    $response = $controller->handle($request);

    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }




    echo $response->getBody();




    /* uso um pouco mais de linhas rotas--------------------------------------------------------------- 
    if (!array_key_exists('PATH_INFO', $_SERVER) || $_SERVER['PATH_INFO'] === "/") {
        //$controller = new VideoListController($videoRepository);
        //require_once __DIR__ . "/../listagem-videos.php";
    } elseif ($_SERVER['PATH_INFO'] === "/novo-video") {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //$controller = new VideoFormController($videoRepository);
            //require_once __DIR__ .  '/../formulario.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$controller = new NovoVideoController($videoRepository);
            //require_once __DIR__ .  '/../novo-video.php';
        }

    } else if ($_SERVER['PATH_INFO'] === "/editar-video") {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //$controller = new VideoFormController($videoRepository);
            //require_once __DIR__ .  '/../formulario.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$controller = new EditVideoController($videoRepository);
            //require_once __DIR__ .  '/../editar-video.php';
        }
    } else if ($_SERVER['PATH_INFO'] === "/remover-video") {
        $controller = new DeleteVideoController($videoRepository);
        //require_once __DIR__ .  "/../remover-video.php";
    } else {
        $controller = new Error404VideoController($videoRepository);
        //http_response_code(404);
    }*/

    /** @var Controller $controller */
    //$controller->processaRequisicao();
?>