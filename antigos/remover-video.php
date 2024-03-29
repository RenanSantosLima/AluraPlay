<?php

use Alura\Mvc\Repository\VideoRepository;

    $dbPath = __DIR__ . "/banco.sqlite";
    $pdo = new PDO("sqlite:$dbPath");

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    $repository = new VideoRepository($pdo);
    

    if ($repository->remove($id) === false){
        header("Location: /?sucesso=0");
    } else {
        header("Location: /?sucesso=1");
    }

?>