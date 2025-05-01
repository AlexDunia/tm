<?php

try {
    $dsn = 'mysql:host=127.0.0.1;dbname=kakadb';
    $username = 'root';
    $password = 'Oliviaestherdunia1@';

    echo "Attempting MySQL connection...\n";
    $pdo = new PDO($dsn, $username, $password);

    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL successfully!\n";

    // Test query to verify data
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "Number of users in database: $userCount\n";

    $stmt = $pdo->query("SHOW TABLES");
    echo "Tables in the database:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Tables_in_kakadb'] . "\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
