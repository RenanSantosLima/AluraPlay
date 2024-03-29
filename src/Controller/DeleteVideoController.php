<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class DeleteVideoController implements RequestHandlerInterface {

        use FlashMessageTrait;

        private VideoRepository $videoRepository;

        public function __construct(VideoRepository $videoRepository)
        {
            $this->videoRepository = $videoRepository;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface {
            $queryParams = $request->getQueryParams();
            $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
            // usado quando o metodo não recebe parametros
            //$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null) {
                //header("Location: /?sucesso=0");
                $this->addErrorMessage('Id inválido');
                return new Response(302, [
                    'Location' => '/'
                ]);
            }
            
            $success = $this->videoRepository->remove($id);

            if ($success === false){
                $this->addErrorMessage('Erro ao remover video');
                return new Response(302, [
                    'Location' => '/'
                ]);
            } else {
                return new Response(302, [
                    'Location' => '/'
                ]);
            }
        }
    }

?>