<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .success {
            color: green;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
        }
        .error {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 4px;
        }
        pre {
            background-color: #f1f1f1;
            padding: 10px;
            overflow: auto;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Connection Test</h1>

        <?php
        try {
            // Test database connection
            $connection = DB::connection();
            echo '<div class="success">Database connection successful using driver: ' . DB::connection()->getDriverName() . '</div>';

            // Get database name
            $dbName = DB::connection()->getDatabaseName();
            echo '<p>Connected to database: <strong>' . $dbName . '</strong></p>';

            // Check if users table exists
            $tables = DB::select("SHOW TABLES");
            $userTableExists = false;

            echo '<h2>Tables in Database:</h2>';
            echo '<ul>';
            foreach($tables as $table) {
                $tableName = reset($table);
                echo '<li>' . $tableName . '</li>';
                if ($tableName == 'users') {
                    $userTableExists = true;
                }
            }
            echo '</ul>';

            // If users table exists, show its structure
            if ($userTableExists) {
                echo '<h2>Users Table Structure</h2>';
                $columns = DB::select("SHOW COLUMNS FROM users");

                echo '<table>';
                echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
                foreach($columns as $column) {
                    echo '<tr>';
                    echo '<td>' . $column->Field . '</td>';
                    echo '<td>' . $column->Type . '</td>';
                    echo '<td>' . $column->Null . '</td>';
                    echo '<td>' . $column->Key . '</td>';
                    echo '<td>' . ($column->Default !== null ? $column->Default : 'NULL') . '</td>';
                    echo '<td>' . $column->Extra . '</td>';
                    echo '</tr>';
                }
                echo '</table>';

                // Check if any users exist
                $userCount = DB::table('users')->count();
                echo '<p>Total users in the database: <strong>' . $userCount . '</strong></p>';
            } else {
                echo '<div class="error">Users table does not exist!</div>';
            }

        } catch (Exception $e) {
            echo '<div class="error">Database error: ' . $e->getMessage() . '</div>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
        ?>

        <h2>PHP Information</h2>
        <p>PHP Version: <?php echo phpversion(); ?></p>
        <p>Extensions loaded:</p>
        <ul>
            <?php
            $extensions = get_loaded_extensions();
            sort($extensions);
            foreach($extensions as $ext) {
                if (in_array($ext, ['pdo', 'pdo_mysql', 'mysqli'])) {
                    echo '<li><strong>' . $ext . '</strong></li>';
                }
            }
            ?>
        </ul>
    </div>
</body>
</html>
