<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class MigrateToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-to-mysql
                            {--pg-host=127.0.0.1 : PostgreSQL host}
                            {--pg-port=5432 : PostgreSQL port}
                            {--pg-database= : PostgreSQL database name}
                            {--pg-username= : PostgreSQL username}
                            {--pg-password= : PostgreSQL password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate database from PostgreSQL to MySQL including all data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting PostgreSQL to MySQL migration...');

        // Check if MySQL connection is configured
        try {
            DB::connection()->getPdo();
            $this->info('MySQL connection successful: ' . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error('MySQL connection failed: ' . $e->getMessage());
            return 1;
        }

        // Configure PostgreSQL connection
        $pgHost = $this->option('pg-host');
        $pgPort = $this->option('pg-port');
        $pgDatabase = $this->option('pg-database');
        $pgUsername = $this->option('pg-username');
        $pgPassword = $this->option('pg-password');

        // If any PostgreSQL parameter is empty, prompt for it
        if (empty($pgDatabase)) {
            $pgDatabase = $this->ask('Enter PostgreSQL database name');
        }
        if (empty($pgUsername)) {
            $pgUsername = $this->ask('Enter PostgreSQL username');
        }
        if (empty($pgPassword)) {
            $pgPassword = $this->secret('Enter PostgreSQL password');
        }

        // Configure temporary PostgreSQL connection
        $pgConnection = [
            'driver' => 'pgsql',
            'host' => $pgHost,
            'port' => $pgPort,
            'database' => $pgDatabase,
            'username' => $pgUsername,
            'password' => $pgPassword,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ];

        config(['database.connections.pg_source' => $pgConnection]);

        // Test PostgreSQL connection
        try {
            DB::connection('pg_source')->getPdo();
            $this->info('PostgreSQL connection successful: ' . DB::connection('pg_source')->getDatabaseName());
        } catch (\Exception $e) {
            $this->error('PostgreSQL connection failed: ' . $e->getMessage());
            return 1;
        }

        // Confirm migration
        if (!$this->confirm('This will run fresh migrations on your MySQL database and import all data from PostgreSQL. Continue?', true)) {
            $this->info('Migration cancelled');
            return 0;
        }

        // Run fresh migrations on MySQL
        $this->info('Running fresh migrations on MySQL...');
        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->info(Artisan::output());

        // Get tables to migrate
        try {
            $tables = DB::connection('pg_source')
                ->select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'");

            foreach ($tables as $table) {
                $tableName = $table->table_name;

                // Skip migration tables
                if ($tableName == 'migrations' || $tableName == 'failed_jobs' || $tableName == 'password_resets' || $tableName == 'password_reset_tokens') {
                    $this->info("Skipping system table: $tableName");
                    continue;
                }

                // Check if table exists in MySQL
                if (!Schema::hasTable($tableName)) {
                    $this->warn("Table $tableName does not exist in MySQL, skipping");
                    continue;
                }

                $this->info("Migrating table: $tableName");

                // Get records from PostgreSQL
                $records = DB::connection('pg_source')->table($tableName)->get();
                $this->info("  - Found " . count($records) . " records");

                // Skip if empty
                if (count($records) == 0) {
                    $this->info("  - No records to migrate");
                    continue;
                }

                // Special handling for tables with JSON data
                if ($tableName == 'newtransactions') {
                    $this->handleNewtransactionsTable($records);
                } else {
                    $this->handleStandardTable($tableName, $records);
                }

                $this->info("  - Completed migrating table: $tableName");
            }

            $this->info('Migration completed successfully!');

        } catch (\Exception $e) {
            $this->error("Error during migration: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        } finally {
            // Remove temporary connection
            config(['database.connections.pg_source' => null]);
        }

        return 0;
    }

    /**
     * Handle migration of the newtransactions table with JSON data
     */
    private function handleNewtransactionsTable($records)
    {
        $this->info("  - Processing newtransactions with JSON handling");

        foreach ($records as $record) {
            // Convert record to array
            $data = get_object_vars($record);

            // Handle ticket_ids special case
            if (isset($data['ticket_ids'])) {
                // Ensure it's valid JSON before encoding
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

            try {
                // Insert into MySQL
                DB::table('newtransactions')->insert($data);
            } catch (\Exception $e) {
                $this->warn("  - Error inserting record: " . $e->getMessage());
                // Continue with next record
            }
        }
    }

    /**
     * Handle migration of standard tables
     */
    private function handleStandardTable($tableName, $records)
    {
        $this->info("  - Processing standard table");

        // Batch insert in chunks to avoid memory issues
        $chunks = array_chunk($records->toArray(), 100);
        $counter = 0;

        foreach ($chunks as $chunk) {
            $dataSet = [];
            foreach ($chunk as $record) {
                $dataSet[] = get_object_vars($record);
            }

            try {
                DB::table($tableName)->insert($dataSet);
                $counter += count($dataSet);
                $this->info("  - Inserted $counter records");
            } catch (\Exception $e) {
                $this->warn("  - Error inserting chunk: " . $e->getMessage());

                // Try inserting records one by one
                $this->info("  - Trying individual inserts");
                foreach ($dataSet as $data) {
                    try {
                        DB::table($tableName)->insert($data);
                        $counter++;
                    } catch (\Exception $innerEx) {
                        $this->warn("  - Skipped record due to error: " . $innerEx->getMessage());
                    }
                }
            }
        }
    }
}
