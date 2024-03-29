<?php

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class NovoVideoController implements RequestHandlerInterface {
        
        use FlashMessageTrait;
        private VideoRepository $repository;

        public function __construct(VideoRepository $repository)
        {
            $this->repository = $repository;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface {
            $resquestBody = $request->getParsedBody();
            $url = filter_var($resquestBody['url'], FILTER_VALIDATE_URL);
            //$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
            if ($url === false) {
                $this->addErrorMessage('URL inválida');
                //header("Location: /novo-video");
                return new Response(302, [
                    'Location' => '/novo-video'
                ]);
                //exit();
            }

            $titulo = filter_var($resquestBody['titulo']);
            //$titulo = filter_input(INPUT_POST, 'titulo');
            if ($titulo === false) {
                $this->addErrorMessage('Titulo não informado');
                //header("Location: /novo-video");
                return new Response(302, [
                    'Location' => '/novo-video'
                ]);
                //exit();
            }

            

            $video = new Video($url, $titulo);

            //if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $files = $request->getUploadedFiles();
            /** @var UploadedFileInterface $uploadedImage */
            $uploadedImage = $files['image'];
            if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                //$mimeType = $finfo->file($_FILES['image']['tmp_name']);
                $tmpFile = $uploadedImage->getStream()->getMetadata('uri');
                $mimeType = $finfo->file($tmpFile);

                if (str_starts_with($mimeType, 'image/')) {
                    /*$safeFileName = uniqid('upload_') . '_' . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME);
                    move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        __DIR__ . '/../../public/img/uploads/' . $safeFileName
                    );*/
                    $safeFileName = uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME);
                    $uploadedImage->moveTo(__DIR__ . '/../../public/img/uploads/' . $safeFileName);
                    $video->setFilePath($safeFileName);
                }
            }

            $success = $this->repository->add($video);

            if ($success === false){
                $this->addErrorMessage('Erro ao cadastrar video');
                //header("Location: /novo-video");
                return new Response(302, [
                    'Location' => '/novo-video'
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