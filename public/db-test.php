<?php
// Simple script to test database connection
try {
    // Load environment variables from Laravel .env
    $dotenv = file_exists(__DIR__.'/../.env') ? file_get_contents(__DIR__.'/../.env') : '';
    $env = [];

    // Parse .env file
    foreach (explode("\n", $dotenv) as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2) + [1 => ''];
        $env[trim($key)] = trim($value, '"\'');
    }

    // Get database connection details
    $host = $env['DB_HOST'] ?? '127.0.0.1';
    $database = $env['DB_DATABASE'] ?? 'kakadb';
    $username = $env['DB_USERNAME'] ?? 'root';
    $password = $env['DB_PASSWORD'] ?? '';
    $port = $env['DB_PORT'] ?? '3306';

    echo "Attempting connection to MySQL: $host:$port/$database<br>";

    // Connect to database
    $dsn = "mysql:host=$host;port=$port;dbname=$database";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    // Test a query
    echo "Connection successful! Testing query...<br>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "Number of users: $count<br>";

    // Test the slow query from the logs
    echo "Testing query on mctlists table...<br>";
    $start = microtime(true);
    $stmt = $pdo->query("EXPLAIN SELECT COUNT(*) FROM mctlists");
    $queryPlan = $stmt->fetchAll();
    $time = microtime(true) - $start;

    echo "Query execution time: " . round($time * 1000, 2) . "ms<br>";
    echo "Query execution plan:<br><pre>";
    print_r($queryPlan);
    echo "</pre>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
