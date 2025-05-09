<?php
/**
 * Script to switch from production to testing environment
 *
 * This script:
 * 1. Updates .env file to testing settings
 * 2. Clears Laravel caches
 * 3. Sets appropriate permissions
 */

// Path to .env file
$envPath = __DIR__ . '/.env';
$envBackupPath = __DIR__ . '/.env.production-backup-' . date('Y-m-d-His');

// Check if .env exists
if (!file_exists($envPath)) {
    echo "Error: .env file not found at $envPath\n";
    echo "Creating a new .env file for testing...\n";

    // Create a basic testing .env file
    $testingEnv = "APP_ENV=testing\n";
    $testingEnv .= "APP_DEBUG=true\n";
    $testingEnv .= "APP_URL=http://localhost\n\n";
    $testingEnv .= "LOG_CHANNEL=stack\n";
    $testingEnv .= "LOG_LEVEL=debug\n\n";
    $testingEnv .= "SESSION_DRIVER=file\n";
    $testingEnv .= "SESSION_LIFETIME=120\n";
    $testingEnv .= "SESSION_SECURE_COOKIE=false\n\n";
    $testingEnv .= "CACHE_DRIVER=file\n\n";
    $testingEnv .= "DB_CONNECTION=mysql\n";
    $testingEnv .= "DB_HOST=127.0.0.1\n";
    $testingEnv .= "DB_PORT=3306\n";
    $testingEnv .= "DB_DATABASE=kakadb\n";
    $testingEnv .= "DB_USERNAME=root\n";
    $testingEnv .= "DB_PASSWORD=\n\n";
    $testingEnv .= "SANCTUM_STATEFUL_DOMAINS=localhost\n";

    file_put_contents($envPath, $testingEnv);
    echo "Created new .env file with testing settings.\n";
} else {
    // Backup existing .env file
    if (copy($envPath, $envBackupPath)) {
        echo "Created backup of .env file at $envBackupPath\n";

        // Read current .env file
        $content = file_get_contents($envPath);

        // Replace production settings with testing settings
        $content = preg_replace('/APP_ENV=production/', 'APP_ENV=testing', $content);
        $content = preg_replace('/APP_DEBUG=false/', 'APP_DEBUG=true', $content);
        $content = preg_replace('/APP_URL=https:\/\/.*/', 'APP_URL=http://localhost', $content);
        $content = preg_replace('/LOG_LEVEL=error/', 'LOG_LEVEL=debug', $content);
        $content = preg_replace('/SESSION_SECURE_COOKIE=true/', 'SESSION_SECURE_COOKIE=false', $content);
        $content = preg_replace('/SANCTUM_STATEFUL_DOMAINS=.*/', 'SANCTUM_STATEFUL_DOMAINS=localhost', $content);

        // Save updated .env file
        if (file_put_contents($envPath, $content) !== false) {
            echo "Successfully updated .env file with testing settings\n";
        } else {
            echo "Error: Failed to write to .env file\n";
            exit(1);
        }
    } else {
        echo "Error: Failed to create backup of .env file at $envBackupPath\n";
        exit(1);
    }
}

// Clear Laravel caches
echo "Clearing Laravel caches...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear',
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    system($command, $result);
    if ($result !== 0) {
        echo "Warning: Command may have failed: $command\n";
    }
}

echo "\n===================================\n";
echo "ENVIRONMENT SWITCHED TO TESTING MODE\n";
echo "===================================\n\n";
echo "Your production settings have been backed up to: $envBackupPath\n";
echo "To restore production settings, run: php switch-to-production.php\n";
