<?php
$localhost = 'localhost';
$user = "root";
$password = "Estacio@123";
$banco = "swipesdereceitas";

global $pdo;

try {
    $pdo = new PDO("mysql:dbname=".$banco.";host=".$localhost, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "erro: " .$e->getMessage();
}

?>