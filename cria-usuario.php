<?php 

$pdo = new PDO("mysql:host=localhost;dbname=aluraplay", 'root', '');

$email = $argv[1];
$password = $argv[2];
$hash = password_hash($password, PASSWORD_ARGON2ID);//pode usar o password_default se não quiser definir o hash que usar

$sql = "Insert Into users (email, password) Values (?, ?);";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $email);
$stmt->bindValue(2, $hash);
$stmt->execute();

?>