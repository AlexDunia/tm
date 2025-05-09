<?php
/**
 * Script to switch back to production environment
 *
 * This script:
 * 1. Finds the most recent .env.production-backup file
 * 2. Restores it as the active .env file
 * 3. Rebuilds production caches
 */

// Find the most recent production backup
$backupFiles = glob(__DIR__ . '/.env.production-backup-*');

if (empty($backupFiles)) {
    echo "Error: No production backup files found.\n";
    echo "You need to have run switch-to-testing.php first to create a backup.\n";
    exit(1);
}

// Sort files by modification time (newest first)
usort($backupFiles, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$latestBackup = $backupFiles[0];
$envPath = __DIR__ . '/.env';
$testingBackupPath = __DIR__ . '/.env.testing-backup-' . date('Y-m-d-His');

// Backup current testing environment
if (file_exists($envPath)) {
    copy($envPath, $testingBackupPath);
    echo "Created backup of testing .env file at $testingBackupPath\n";
}

// Restore production environment
if (copy($latestBackup, $envPath)) {
    echo "Successfully restored production .env from $latestBackup\n";
} else {
    echo "Error: Failed to restore production .env file.\n";
    exit(1);
}

// Rebuild caches for production
echo "Rebuilding production caches...\n";
$commands = [
    'php artisan cache:clear',
    'php artisan config:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    system($command, $result);
    if ($result !== 0) {
        echo "Warning: Command may have failed: $command\n";
    }
}

echo "\n========================================\n";
echo "ENVIRONMENT SWITCHED BACK TO PRODUCTION MODE\n";
echo "========================================\n\n";
echo "Your testing settings have been backed up to: $testingBackupPath\n";
echo "To switch back to testing mode, run: php switch-to-testing.php\n";
