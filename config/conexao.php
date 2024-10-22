<?php

try {
    $conn = new PDO("mysql:host=".DB_HOST.";port=3306;dbname=".DB_BASE.";charset=utf8", DB_NAME, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    exit;
}