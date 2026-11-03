<?php
$host = 'localhost';
$db   = 'tenesiconstruct_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tengeneza Database kama haikuwepo
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `$db`;");

    // Soma na utekeleze muundo kutoka database.sql
    $sql = file_get_contents('database.sql');
    $pdo->exec($sql);

    echo "Muunganisho na Database umefanikiwa na meza zote zimetengenezwa!";
} catch (\PDOException $e) {
    echo "Imeshindikana kuunganisha: " . $e->getMessage();
}
?>
