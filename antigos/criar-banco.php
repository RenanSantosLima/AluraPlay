<?php 

    $dbPath = __DIR__ . "/banco.sqlite";
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->exec('Create Table videos(id INTEGER PRIMARY KEY, url TEXT, title TEXT);');
?>