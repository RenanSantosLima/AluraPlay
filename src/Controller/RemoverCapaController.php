<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class RemoverCapaController implements RequestHandlerInterface {

        use FlashMessageTrait;

        private VideoRepository $videoRepository;

        public function __construct(VideoRepository $videoRepository)
        {
            $this->videoRepository = $videoRepository;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            $queryParams = $request->getQueryParams();
            $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
            //$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null) {
                //header("Location: /?sucesso=0");
                $this->addErrorMessage('Id inválido');
                return new Response(302, [
                    'Location' => '/'
                ]);
            }
            
            $success = $this->videoRepository->updateCapa($id);

            if ($success === false){
                //header("Location: /?sucesso=0");
                $this->addErrorMessage('Erro ao remover a capa');
                return new Response(302, [
                    'Location' => '/'
                ]);
            } else {
                //header("Location: /?sucesso=1");
                return new Response(302, [
                    'Location' => '/'
                ]);
            }
        }

    }

?>