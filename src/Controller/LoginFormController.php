<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\HtmlRendererTrait;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class LoginFormController implements RequestHandlerInterface {

        

        private Engine $templates;

        public function __construct(Engine $templates)
        {
            $this->templates = $templates;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            if (array_key_exists('logado', $_SESSION) && $_SESSION['logado'] === true) {
                return new Response(302, [
                    'Location' => '/'
                ]);
            }
            //return new Response(200, [], $this->renderTemplete("login-form"));
            return new Response(200, [], $this->templates->render("login-form"));
        }

    }

?>