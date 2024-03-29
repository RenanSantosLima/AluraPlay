<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class Error404Controller implements RequestHandlerInterface {

        public function handle(ServerRequestInterface $request): ResponseInterface {
            //http_response_code(404)
            return new Response(404);
        }
    }

?>