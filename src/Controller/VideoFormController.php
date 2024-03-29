<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\HtmlRendererTrait;
use Alura\Mvc\Repository\VideoRepository;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class VideoFormController implements RequestHandlerInterface {

        

        private VideoRepository $repository;
        private Engine $templates;

        public function __construct(VideoRepository $repository, Engine $templates)
        {
            $this->repository = $repository;
            $this->templates = $templates;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface {
            $queryParams = $request->getQueryParams();
            $id = filter_var($queryParams['id'] ?? '', FILTER_VALIDATE_INT);
            //$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            /** @var ?Video $video */
            $video = null;
            if ($id !== false && $id !== null) {
                $video = $this->repository->find($id);
            }

            return new Response(200, [], $this->templates->render("video-form", [
                'id' => $id,
                'video' => $video
            ]));
            /*echo $this->renderTemplete("video-form", [
                'video' => $video
            ]);*/
            //require_once __DIR__ . "/../../views/video-form.php"; 
        }

    }

?>