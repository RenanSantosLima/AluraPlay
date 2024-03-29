<?php 

    $dbPath = __DIR__ . "/banco.sqlite";
    $pdo = new PDO("sqlite:$dbPath");

    $pdo->exec("Alter Table videos ADD COLUMN image_path TEXT");

?>