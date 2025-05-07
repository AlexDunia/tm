<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class VerifyEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:verify {--fix : Attempt to fix common issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the environment configuration for production readiness';

    /**
     * Critical environment variables that must be set for production
     */
    protected $criticalEnvVars = [
        'APP_KEY', 'APP_URL', 'DB_CONNECTION', 'DB_HOST',
        'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('KakaWorld Environment Verification');
        $this->line('=====================================');

        $issues = [];
        $warnings = [];
        $fixedCount = 0;
        $shouldFix = $this->option('fix');

        // Check environment
        $env = config('app.env');
        $this->line("Environment: <fg=yellow>$env</>");
        if ($env !== 'production') {
            $warnings[] = "APP_ENV is not set to 'production'";
            if ($shouldFix) {
                $this->setEnvValue('APP_ENV', 'production');
                $this->comment("Fixed: Set APP_ENV to 'production'");
                $fixedCount++;
            }
        }

        // Check debug mode
        $debug = config('app.debug');
        $this->line("Debug Mode: " . ($debug ? "<fg=red>Enabled</>" : "<fg=green>Disabled</>"));
        if ($debug) {
            $issues[] = "APP_DEBUG is enabled (should be false in production)";
            if ($shouldFix) {
                $this->setEnvValue('APP_DEBUG', 'false');
                $this->comment("Fixed: Set APP_DEBUG to false");
                $fixedCount++;
            }
        }

        // Check environment variables
        $this->line("\nChecking critical environment variables:");
        foreach ($this->criticalEnvVars as $var) {
            $value = env($var);
            if (empty($value) && $var !== 'APP_KEY') {
                $issues[] = "$var is not set";
            } elseif ($var === 'APP_KEY' && $value === 'base64:') {
                $issues[] = "APP_KEY appears to be invalid";
                if ($shouldFix) {
                    $this->call('key:generate', ['--force' => true]);
                    $this->comment("Fixed: Generated new APP_KEY");
                    $fixedCount++;
                }
            } else {
                // Mask sensitive values
                $displayValue = in_array($var, ['DB_PASSWORD', 'APP_KEY']) ? '******' : $value;
                $this->line("  $var: <fg=green>✓</> ($displayValue)");
            }
        }

        // Check database connection
        $this->line("\nTesting database connection:");
        try {
            DB::connection()->getPdo();
            $this->line("  Database: <fg=green>✓</> (Connected to: " . DB::connection()->getDatabaseName() . ")");

            // Check if migrations have been run
            $this->line("\nChecking database migrations:");
            if (Schema::hasTable('migrations')) {
                $count = DB::table('migrations')->count();
                $this->line("  Migrations: <fg=green>✓</> ($count migrations found)");
            } else {
                $issues[] = "Migration table not found - migrations may not have been run";
                if ($shouldFix) {
                    if ($this->confirm('Run database migrations now?', false)) {
                        $this->call('migrate', ['--force' => true]);
                        $this->comment("Fixed: Ran database migrations");
                        $fixedCount++;
                    }
                }
            }
        } catch (\Exception $e) {
            $issues[] = "Database connection failed: " . $e->getMessage();
        }

        // Check storage directory permissions
        $this->line("\nChecking storage directory permissions:");
        $storagePath = storage_path();
        $this->checkDirectoryPermissions($storagePath, $issues);

        // Check log files and log rotation
        $this->line("\nChecking log files:");
        $logPath = storage_path('logs');
        $logFiles = File::files($logPath);
        $this->line("  Log files: " . count($logFiles) . " files found");

        $largeLogFiles = [];
        foreach ($logFiles as $file) {
            if ($file->getSize() > 50 * 1024 * 1024) { // 50 MB
                $largeLogFiles[] = $file->getFilename() . " (" . round($file->getSize() / 1024 / 1024, 2) . " MB)";
            }
        }

        if (count($largeLogFiles) > 0) {
            $warnings[] = "Large log files found: " . implode(", ", $largeLogFiles);
            if ($shouldFix && $this->confirm('Clear large log files?', false)) {
                foreach ($largeLogFiles as $largeFile) {
                    $filename = explode(" (", $largeFile)[0];
                    File::put(storage_path("logs/$filename"), "Log cleared by env:verify on " . date('Y-m-d H:i:s') . "\n");
                    $this->comment("Fixed: Cleared $filename");
                    $fixedCount++;
                }
            }
        }

        // Check that required PHP extensions are loaded
        $this->line("\nChecking PHP extensions:");
        $requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'tokenizer', 'openssl', 'mbstring', 'xml', 'ctype', 'fileinfo', 'curl'];
        $missingExtensions = [];

        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                $this->line("  $ext: <fg=green>✓</>");
            } else {
                $this->line("  $ext: <fg=red>✗</>");
                $missingExtensions[] = $ext;
            }
        }

        if (count($missingExtensions) > 0) {
            $issues[] = "Missing PHP extensions: " . implode(", ", $missingExtensions);
        }

        // Summarize findings
        $this->line("\nSummary:");
        $this->line("  Critical Issues: " . count($issues));
        $this->line("  Warnings: " . count($warnings));

        if (count($issues) > 0) {
            $this->error("\nCritical issues that need to be fixed:");
            foreach ($issues as $i => $issue) {
                $this->error("  " . ($i + 1) . ". $issue");
            }
        }

        if (count($warnings) > 0) {
            $this->warn("\nWarnings to consider:");
            foreach ($warnings as $i => $warning) {
                $this->warn("  " . ($i + 1) . ". $warning");
            }
        }

        if ($shouldFix) {
            $this->info("\nFixed $fixedCount issues automatically.");
        }

        if (count($issues) === 0 && count($warnings) === 0) {
            $this->info("\nEnvironment verification complete. No issues found!");
            return 0;
        }

        if (count($issues) > 0) {
            return 1;
        }

        return 0;
    }

    /**
     * Check directory permissions
     */
    private function checkDirectoryPermissions($path, &$issues)
    {
        if (!File::isWritable($path)) {
            $issues[] = "Directory not writable: $path";
            $this->line("  $path: <fg=red>✗</> (not writable)");
        } else {
            $this->line("  $path: <fg=green>✓</> (writable)");
        }

        $subdirs = ['logs', 'framework/cache', 'framework/sessions', 'framework/views'];
        foreach ($subdirs as $subdir) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $subdir;
            if (File::exists($fullPath)) {
                if (!File::isWritable($fullPath)) {
                    $issues[] = "Directory not writable: $fullPath";
                    $this->line("  $subdir: <fg=red>✗</> (not writable)");
                } else {
                    $this->line("  $subdir: <fg=green>✓</> (writable)");
                }
            } else {
                $issues[] = "Directory doesn't exist: $fullPath";
                $this->line("  $subdir: <fg=red>✗</> (doesn't exist)");
            }
        }
    }

    /**
     * Update .env file value
     */
    private function setEnvValue($key, $value)
    {
        $envPath = app()->environmentFilePath();

        if (File::exists($envPath)) {
            $content = File::get($envPath);

            // Replace existing value
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                // Add value if it doesn't exist
                $content .= "\n{$key}={$value}\n";
            }

            File::put($envPath, $content);
            return true;
        }

        return false;
    }
}
