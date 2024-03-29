<?php 

$dbPath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$dbPath");
$pdo->exec("Create Table users (id INTEGER PRIMARY KEY, email TEXT, password TEXT);");

?>