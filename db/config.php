<?php
    $servername = "localhost";
    $username = "nicole-tracy.clottey";
    $password = "nicole123";
    $dbname = "webtech_fall2024_nicole-tracy_clottey";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for errors
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
?>