<?php

/**
 * Script to update .env file with MySQL connection details
 *
 * This script backs up the current .env file and creates a new one with MySQL settings
 * Usage: php database/update-env-for-mysql.php
 */

// Config
$host = '127.0.0.1';
$port = '3306';
$database = 'tm_mysql';  // Update with your MySQL database name
$username = 'root';      // Update with your MySQL username
$password = '';          // Update with your MySQL password

// Paths
$envPath = __DIR__ . '/../.env';
$envBackupPath = __DIR__ . '/../.env.backup-' . date('Y-m-d-His');

// Check if .env exists
if (!file_exists($envPath)) {
    echo "Error: .env file not found at $envPath\n";
    exit(1);
}

// Create backup
if (!copy($envPath, $envBackupPath)) {
    echo "Error: Failed to create backup of .env file at $envBackupPath\n";
    exit(1);
}

echo "Created backup of .env file at $envBackupPath\n";

// Read current .env file
$content = file_get_contents($envPath);

// Update MySQL connection details
$content = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $content);
$content = preg_replace('/DB_HOST=.*/', "DB_HOST=$host", $content);
$content = preg_replace('/DB_PORT=.*/', "DB_PORT=$port", $content);
$content = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE=$database", $content);
$content = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME=$username", $content);
$content = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=$password", $content);

// Add configuration for PostgreSQL connection (for data migration)
if (!strpos($content, 'PG_HOST=')) {
    $content .= "\n# PostgreSQL connection for data migration\n";
    $content .= "PG_HOST=127.0.0.1\n";
    $content .= "PG_PORT=5432\n";
    $content .= "PG_DATABASE=tm\n"; // Update with your PostgreSQL database name
    $content .= "PG_USERNAME=postgres\n"; // Update with your PostgreSQL username
    $content .= "PG_PASSWORD=\n"; // Update with your PostgreSQL password
}

// Save updated .env file
if (file_put_contents($envPath, $content) === false) {
    echo "Error: Failed to write to .env file\n";
    exit(1);
}

echo "Successfully updated .env file with MySQL connection details\n";
echo "Please update the PostgreSQL settings in the .env file for data migration\n";
echo "Next steps:\n";
echo "1. Run MySQL migrations: php artisan migrate:fresh\n";
echo "2. Run data migration script: php database/pg-to-mysql-migration.php\n";
echo "3. Test the application with MySQL\n";
