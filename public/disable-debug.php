<?php
// This script disables debugging mode

// Verify security token to prevent unauthorized access
$token = $_GET['token'] ?? '';
if ($token !== 'temporary_debug_access') {
    die('Access denied');
}

// Path to .env file
$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    die('.env file not found');
}

// Read current .env content
$envContent = file_get_contents($envPath);

// Replace APP_DEBUG=true with APP_DEBUG=false
$envContent = preg_replace('/APP_DEBUG=true/', 'APP_DEBUG=false', $envContent);

// Write back to .env
if (file_put_contents($envPath, $envContent)) {
    echo "Debug mode has been disabled successfully";
} else {
    echo "Failed to write to .env file. Check permissions.";
}

// Output a link to enable debug mode again if needed
echo "<br><br><a href='enable-debug.php?token=temporary_debug_access'>Enable Debug Mode</a>";
?>
