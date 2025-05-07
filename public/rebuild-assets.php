<?php
// Security check - this should only be accessible with a special token
$token = $_GET['token'] ?? '';
if ($token !== 'temporary_debug_access') {
    die('Access denied');
}

// Project root
$projectRoot = dirname(__DIR__);
$buildCommand = 'cd ' . escapeshellarg($projectRoot) . ' && npm run build';

echo "<h1>Asset Rebuild Utility</h1>";
echo "<p>This tool will attempt to rebuild your frontend assets using 'npm run build'.</p>";

if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // Execute the build command
    echo "<h2>Executing Build Command</h2>";
    echo "<pre>$ $buildCommand</pre>";
    echo "<div style='background: #f5f5f5; padding: 15px; border: 1px solid #ddd; margin: 15px 0; max-height: 400px; overflow: auto;'>";

    // Capture the command output
    $output = [];
    $return_var = 0;

    // Execute the command and capture output
    if (function_exists('exec')) {
        exec($buildCommand . " 2>&1", $output, $return_var);
        foreach ($output as $line) {
            echo htmlspecialchars($line) . "<br>";
        }

        if ($return_var === 0) {
            echo "</div><p style='color: green; font-weight: bold;'>Build completed successfully!</p>";
            echo "<p>The following actions were performed:</p>";
            echo "<ul>";
            echo "<li>Compiled CSS and JavaScript</li>";
            echo "<li>Created Vite manifest file</li>";
            echo "<li>Generated hashed asset files in the public/build directory</li>";
            echo "</ul>";
        } else {
            echo "</div><p style='color: red; font-weight: bold;'>Build failed with error code: $return_var</p>";
            echo "<p>Possible issues:</p>";
            echo "<ul>";
            echo "<li>Node.js/npm not installed or not in PATH</li>";
            echo "<li>Missing dependencies (try running 'npm install' first)</li>";
            echo "<li>Build script errors in your frontend code</li>";
            echo "</ul>";
        }
    } else {
        echo "Unable to execute commands with the exec() function. It may be disabled by your server configuration.</div>";
    }

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='vite-test.php' style='display: inline-block; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;'>Test Vite Assets</a>";
    echo "<a href='/' style='display: inline-block; padding: 10px 15px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;'>Go to Homepage</a>";
    echo "</div>";
} else {
    // Show confirmation form
    echo <<<HTML
    <div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
        <strong>Warning:</strong> This will run npm build commands on your server. Make sure:
        <ul>
            <li>Node.js is installed and available</li>
            <li>Your server has sufficient permissions</li>
            <li>This won't interfere with any ongoing processes</li>
        </ul>
    </div>

    <form method="post" style="margin: 20px 0;">
        <input type="hidden" name="confirm" value="yes">
        <button type="submit" style="padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Rebuild Assets
        </button>
        <a href="/" style="display: inline-block; padding: 10px 15px; background: #f44336; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px;">
            Cancel
        </a>
    </form>

    <div style="margin-top: 30px;">
        <h3>Alternative manual steps:</h3>
        <ol>
            <li>SSH into your server</li>
            <li>Navigate to the project root: <code>cd {$projectRoot}</code></li>
            <li>Run: <code>npm install</code> (if you haven't already)</li>
            <li>Run: <code>npm run build</code></li>
            <li>Check that files were created in <code>public/build/</code></li>
        </ol>
    </div>
HTML;
}
?>
