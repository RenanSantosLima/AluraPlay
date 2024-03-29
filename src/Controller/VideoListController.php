<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\HtmlRendererTrait;
use Alura\Mvc\Repository\VideoRepository;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class VideoListController implements RequestHandlerInterface {

        

        private VideoRepository $videoRepository;
        private Engine $templates;

        public function __construct(VideoRepository $videoRepository, Engine $templates)
        {
            
            $this->videoRepository = $videoRepository;
            $this->templates = $templates;

        }

        public function handle(ServerRequestInterface $request): ResponseInterface {
            /*session_start();
            if (!array_key_exists('logado',$_SESSION)) {
                header("Location: /login");
                return;
            }*/
            $videoList = $this->videoRepository->all();
            //echo $this->renderTemplete("video-list", ['videoList' => $videoList]);
            return new Response(200, [], $this->templates->render("video-list", ['videoList' => $videoList]));
        }
    }

?>