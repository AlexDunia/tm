<?php
// Simple script to test Vite asset loading

function checkFile($path) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
    $exists = file_exists($fullPath);
    $readable = is_readable($fullPath);
    $size = $exists ? filesize($fullPath) : 0;

    return [
        'path' => $path,
        'exists' => $exists,
        'readable' => $readable,
        'size' => $size,
        'status' => $exists && $readable ? 'OK' : 'ISSUE'
    ];
}

// Check build directory
$buildDir = 'build';
$buildDirExists = is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $buildDir);

// Check manifest
$manifestPath = 'build/manifest.json';
$manifestExists = file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $manifestPath);
$manifest = $manifestExists ? json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $manifestPath), true) : null;

// CSS and JS files from manifest
$cssFile = $manifest && isset($manifest['resources/sass/app.scss']) ? $manifest['resources/sass/app.scss']['file'] : null;
$jsFile = $manifest && isset($manifest['resources/js/app.js']) ? $manifest['resources/js/app.js']['file'] : null;

// Results
$results = [
    'build_directory' => [
        'path' => $buildDir,
        'exists' => $buildDirExists,
        'status' => $buildDirExists ? 'OK' : 'MISSING'
    ],
    'manifest' => [
        'path' => $manifestPath,
        'exists' => $manifestExists,
        'status' => $manifestExists ? 'OK' : 'MISSING',
        'content' => $manifest
    ],
    'css_file' => $cssFile ? checkFile($cssFile) : ['status' => 'NOT_FOUND', 'path' => null],
    'js_file' => $jsFile ? checkFile($jsFile) : ['status' => 'NOT_FOUND', 'path' => null]
];

// Headers
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vite Asset Test</title>
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
        .status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: bold;
        }
        .status-ok {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-issue {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-missing {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-not-found {
            background-color: #fff3cd;
            color: #664d03;
        }
        pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            overflow: auto;
        }
        .actions {
            margin-top: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            margin-right: 0.5rem;
        }
        .btn:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <h1>Vite Asset Test</h1>

    <div class="card">
        <h2>Build Directory</h2>
        <p>
            Status:
            <span class="status status-<?= strtolower($results['build_directory']['status']) ?>">
                <?= $results['build_directory']['status'] ?>
            </span>
        </p>
        <p>Path: <?= $results['build_directory']['path'] ?></p>
        <p>Exists: <?= $results['build_directory']['exists'] ? 'Yes' : 'No' ?></p>
    </div>

    <div class="card">
        <h2>Manifest File</h2>
        <p>
            Status:
            <span class="status status-<?= strtolower($results['manifest']['status']) ?>">
                <?= $results['manifest']['status'] ?>
            </span>
        </p>
        <p>Path: <?= $results['manifest']['path'] ?></p>
        <p>Exists: <?= $results['manifest']['exists'] ? 'Yes' : 'No' ?></p>
        <?php if ($results['manifest']['content']): ?>
        <h3>Content:</h3>
        <pre><?= json_encode($results['manifest']['content'], JSON_PRETTY_PRINT) ?></pre>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>CSS Asset</h2>
        <p>
            Status:
            <span class="status status-<?= strtolower($results['css_file']['status']) ?>">
                <?= $results['css_file']['status'] ?>
            </span>
        </p>
        <?php if ($results['css_file']['path']): ?>
        <p>Path: <?= $results['css_file']['path'] ?></p>
        <p>Exists: <?= $results['css_file']['exists'] ? 'Yes' : 'No' ?></p>
        <p>Readable: <?= $results['css_file']['readable'] ? 'Yes' : 'No' ?></p>
        <p>Size: <?= $results['css_file']['size'] ?> bytes</p>
        <?php else: ?>
        <p>CSS file not found in manifest</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>JavaScript Asset</h2>
        <p>
            Status:
            <span class="status status-<?= strtolower($results['js_file']['status']) ?>">
                <?= $results['js_file']['status'] ?>
            </span>
        </p>
        <?php if ($results['js_file']['path']): ?>
        <p>Path: <?= $results['js_file']['path'] ?></p>
        <p>Exists: <?= $results['js_file']['exists'] ? 'Yes' : 'No' ?></p>
        <p>Readable: <?= $results['js_file']['readable'] ? 'Yes' : 'No' ?></p>
        <p>Size: <?= $results['js_file']['size'] ?> bytes</p>
        <?php else: ?>
        <p>JavaScript file not found in manifest</p>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="db-test.php" class="btn">Run Database Test</a>
        <a href="/" class="btn">Go to Homepage</a>
    </div>
</body>
</html>
