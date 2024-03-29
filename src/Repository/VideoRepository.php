<?php 

    namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\Video;
use PDO;

    class VideoRepository {

        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function add(Video $video): bool {
            $sql = "Insert Into videos (url, title, image_path) Values (:url, :title, :image_path);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":url", $video->url);
            $stmt->bindValue(":title", $video->title);
            $stmt->bindValue(":image_path", $video->getFilePath());
            $result = $stmt->execute();

            $id = $this->pdo->lastInsertId();
            $video->setId(intval($id));
            return $result;
        }

        public function remove(int $id): bool {
            $sql = "Delete from videos Where id = :id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);

            return $stmt->execute();
        }

        public function update(Video $video): bool {
            $updateImageSql = '';
            if ($video->getFilePath() !== null) {
                $updateImageSql = ", image_path = :image_path";
            }
            $sql = "Update videos Set url = :url, title = :title $updateImageSql Where id = :id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":url", $video->url);
            $stmt->bindValue(":title", $video->title);
            $stmt->bindValue(":id", $video->id, PDO::PARAM_INT);
            if ($video->getFilePath() !== null) {
                $stmt->bindValue(":image_path", $video->getFilePath());
            }

            return $stmt->execute();
        }

        /**
         * return Video[]
         */
        public function all(): array {
            $videoList = $this->pdo->query("Select * from videos;")->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function (array $videoData) {
                $video = new Video($videoData['url'],$videoData['title']);
                $video->setId($videoData['id']);

                if ($videoData['image_path'] !== null) {
                    $video->setFilePath($videoData['image_path']);
                }
                
                return $video;
            },$videoList);
        }

        public function find(int $id) {
            $stmt = $this->pdo->prepare("Select * from videos Where id = :id;");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return $this->hydrateVideo($stmt->fetch(PDO::FETCH_ASSOC));
        }

        private function hydrateVideo(array $videoData): Video {
            $video = new Video($videoData['url'], $videoData['title']);
            $video->setId($videoData['id']);

            if ($videoData['image_path'] !== null) {
                $video->setFilePath($videoData['image_path']);
            }

            return $video;
        }

        public function updateCapa(int $id): bool {
            $sql = "Update videos Set image_path = :image_path Where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":image_path", null);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        }
    }

?>