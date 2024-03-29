<?php 

    namespace Alura\Mvc\Controller;

    abstract class ControllerWithHtml implements Controller {

        private const TEMPLATE_PATH = __DIR__ . "/../../views/";
        
        protected function renderTemplete (string $templeteName, array $context = []): string {
            extract($context);
            //Inicializa um buffer de saida
            ob_start();
            //$templatePath = __DIR__ . "/../../views/";
            require_once self::TEMPLATE_PATH . $templeteName . ".php";
            // me da o conteudo da saida
            $html = ob_get_contents();
            //limpa buffer
            ob_clean();

            return $html;
        }
    }

?>