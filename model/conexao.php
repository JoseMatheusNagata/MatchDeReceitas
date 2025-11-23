<?php
$localhost = 'localhost';
$user = "root";
$password = "Estacio@123";
$banco = "swipesdereceitas";

global $pdo;

try {
    $pdo = new PDO("mysql:dbname=".$banco.";host=".$localhost.";charset=utf8mb4", $user, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    echo "erro: " .$e->getMessage();
}
?>
?>