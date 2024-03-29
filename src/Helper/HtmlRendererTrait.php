<?php 

    namespace Alura\Mvc\Helper;

    trait HtmlRendererTrait {
        
        private function renderTemplete (string $templeteName, array $context = []): string {
            $templatePath = __DIR__ . "/../../views/";
            extract($context);
            //Inicializa um buffer de saida
            ob_start();
            require_once $templatePath . $templeteName . ".php";
            // me da o conteudo da saida
            $html = ob_get_contents();
            //limpa buffer
            ob_clean();

            return $html;
        }
    }

?>