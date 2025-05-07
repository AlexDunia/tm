<?php
// This script temporarily enables debugging

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

// Check if APP_DEBUG is already true
if (strpos($envContent, 'APP_DEBUG=true') !== false) {
    echo "Debug mode is already enabled";
} else {
    // Replace APP_DEBUG=false with APP_DEBUG=true
    $envContent = preg_replace('/APP_DEBUG=false/', 'APP_DEBUG=true', $envContent);

    // If the replacement wasn't found, add it
    if (strpos($envContent, 'APP_DEBUG=true') === false) {
        $envContent .= "\nAPP_DEBUG=true\n";
    }

    // Write back to .env
    if (file_put_contents($envPath, $envContent)) {
        echo "Debug mode has been enabled successfully";

        // Create a scheduled task to disable debug mode after 1 hour
        // This is a safety measure to prevent leaving debug mode on permanently
        if (function_exists('exec')) {
            // Schedule disabling debug mode after 1 hour using at command
            @exec('echo "php ' . __DIR__ . '/disable-debug.php" | at now + 1 hour 2>&1', $output, $returnVar);
        }
    } else {
        echo "Failed to write to .env file. Check permissions.";
    }
}

// Output a link to disable debug
echo "<br><br><a href='disable-debug.php?token=temporary_debug_access'>Disable Debug Mode</a>";
echo "<br><br><a href='db-test.php'>Run Database Test</a>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Mode Enabled</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
            color: #333;
            background-color: #f8f9fa;
        }
        h1 {
            margin-bottom: 1.5rem;
            color: #0d6efd;
        }
        .card {
            background-color: white;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .tools {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .tool-card {
            background-color: white;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.25rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .tool-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .tool-link {
            display: block;
            padding: 0.75rem 1.25rem;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            margin-top: 1rem;
        }
        .tool-link:hover {
            background-color: #0b5ed7;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <h1>Debug Mode Enabled</h1>

    <div class="warning">
        <strong>Important:</strong> Debug mode has been enabled for troubleshooting. This will expose detailed error messages that can be a security risk if left enabled. Debug mode will automatically disable after 1 hour.
    </div>

    <div class="card">
        <h2>Diagnostic Tools</h2>
        <p>Use these tools to help diagnose the 500 error on your production site:</p>

        <div class="tools">
            <div class="tool-card">
                <div class="tool-icon">🔍</div>
                <h3>Database Test</h3>
                <p>Check database connection and query performance</p>
                <a href="db-test.php" class="tool-link">Run Test</a>
            </div>

            <div class="tool-card">
                <div class="tool-icon">📦</div>
                <h3>Vite Assets Test</h3>
                <p>Verify that Vite assets are properly built and accessible</p>
                <a href="vite-test.php" class="tool-link">Test Assets</a>
            </div>

            <div class="tool-card">
                <div class="tool-icon">🔄</div>
                <h3>Rebuild Assets</h3>
                <p>Rebuild your Vite assets from scratch</p>
                <a href="rebuild-assets.php?token=temporary_debug_access" class="tool-link">Rebuild</a>
            </div>

            <div class="tool-card">
                <div class="tool-icon">🛑</div>
                <h3>Disable Debug</h3>
                <p>Turn off debug mode after troubleshooting</p>
                <a href="disable-debug.php?token=temporary_debug_access" class="tool-link">Disable</a>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Common 500 Error Causes</h2>
        <ul>
            <li><strong>Database Issues:</strong> Connection problems, missing tables, or slow queries</li>
            <li><strong>Missing Vite Assets:</strong> Frontend assets not properly built for production</li>
            <li><strong>Permission Problems:</strong> Storage or cache directories not writable</li>
            <li><strong>Memory Limitations:</strong> PHP memory_limit too low for your application</li>
            <li><strong>Bad Configuration:</strong> .env settings not properly set for production</li>
        </ul>
    </div>
</body>
</html>
