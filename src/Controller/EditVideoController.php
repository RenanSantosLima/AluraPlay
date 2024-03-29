<?php 

    namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

    class EditVideoController implements RequestHandlerInterface {

        use FlashMessageTrait;

        private VideoRepository $videoRepository;

        public function __construct(VideoRepository $videoRepository)
        {
            $this->videoRepository = $videoRepository;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface {

            $queryParams= $request->getQueryParams();
            $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
            //$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id === false || $id === null) {
                return new Response(302, [
                    'Location' => '/'
                ]);
                //exit();
            }

            //$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
            $requestBody = $request->getParsedBody();
            $url = filter_var($requestBody['url'], FILTER_VALIDATE_URL);
            if ($url === false) {
                return new Response(302, [
                    'Location' => '/'
                ]);
                //exit();
            }
            
            //$titulo = filter_input(INPUT_POST, 'titulo');
            $titulo = filter_var($requestBody['titulo']);
            if ($titulo === false) {
                return new Response(302, [
                    'Location' => '/'
                ]);
                //exit();
            }

            $video = new Video($url, $titulo);
            $video->setId($id);

            
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

            $success = $this->videoRepository->update($video);

            if ($success === false){
                $this->addErrorMessage('Erro ao editar video');
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