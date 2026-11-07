<?php
$host = "mariadb";          // sem Docker (XAMPP/WAMP), troque por "localhost"
$banco = "RChecklist";
$usuario = "root";
$senha = "mypass";          // no XAMPP a senha costuma ser vazia: ""

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
