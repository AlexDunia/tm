<?php

/**
 * PostgreSQL to MySQL Data Migration Script
 *
 * This script should be run from the command line using:
 * php database/pg-to-mysql-migration.php
 *
 * Prerequisites:
 * 1. Both PostgreSQL and MySQL connections should be configured
 * 2. MySQL database schema should already be migrated with empty tables
 * 3. PostgreSQL database should have the data to be migrated
 */

// Load Laravel environment
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Configure connections
$pgConnection = [
    'driver' => 'pgsql',
    'host' => env('PG_HOST', '127.0.0.1'),
    'port' => env('PG_PORT', '5432'),
    'database' => env('PG_DATABASE', 'forge'),
    'username' => env('PG_USERNAME', 'forge'),
    'password' => env('PG_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => 'prefer',
];

// MySQL connection uses the default configuration from the .env file

// Create a temporary PostgreSQL connection
config(['database.connections.pg_source' => $pgConnection]);

echo "Starting PostgreSQL to MySQL migration...\n";

try {
    // Get list of tables to migrate (excluding migrations table and other system tables)
    $tables = \Illuminate\Support\Facades\DB::connection('pg_source')
        ->select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'");

    foreach ($tables as $table) {
        $tableName = $table->table_name;

        // Skip migration tables
        if ($tableName == 'migrations' || $tableName == 'failed_jobs' || $tableName == 'password_resets' || $tableName == 'password_reset_tokens') {
            echo "Skipping system table: $tableName\n";
            continue;
        }

        echo "Migrating table: $tableName\n";

        // Get records from PostgreSQL
        $records = \Illuminate\Support\Facades\DB::connection('pg_source')->table($tableName)->get();
        echo "  - Found " . count($records) . " records\n";

        // Clear destination table first to avoid conflicts
        \Illuminate\Support\Facades\DB::table($tableName)->truncate();

        // Special handling for tables with JSON data
        if ($tableName == 'newtransactions') {
            foreach ($records as $record) {
                // Convert record to array
                $data = get_object_vars($record);

                // Handle ticket_ids special case (if exists)
                if (isset($data['ticket_ids'])) {
                    // Ensure it's valid JSON before encoding to string
                    if (is_string($data['ticket_ids'])) {
                        $json = json_decode($data['ticket_ids'], true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $data['ticket_ids'] = json_encode($json);
                        } else {
                            // If not valid JSON, store as empty array
                            $data['ticket_ids'] = json_encode([]);
                        }
                    } else {
                        // Convert non-string to JSON
                        $data['ticket_ids'] = json_encode($data['ticket_ids']);
                    }
                }

                // Insert into MySQL
                \Illuminate\Support\Facades\DB::table($tableName)->insert($data);
            }
        } else {
            // For all other tables, batch insert
            $chunks = array_chunk($records->toArray(), 100);
            foreach ($chunks as $chunk) {
                $dataSet = [];
                foreach ($chunk as $record) {
                    $dataSet[] = get_object_vars($record);
                }
                \Illuminate\Support\Facades\DB::table($tableName)->insert($dataSet);
            }
        }

        echo "  - Completed migrating table: $tableName\n";
    }

    echo "Migration completed successfully!\n";

} catch (\Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
} finally {
    // Remove temporary connection
    config(['database.connections.pg_source' => null]);
}
