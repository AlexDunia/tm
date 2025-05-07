<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL older than 5.7.7
        Schema::defaultStringLength(191);

        // Enable query logging in development environment
        if ($this->app->environment('local')) {
            DB::enableQueryLog();

            // Log slow queries (over 1 second)
            DB::listen(function($query) {
                if ($query->time > 1000) {
                    Log::channel('daily')->warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }

        // Set reasonable default select limits to prevent large result sets
        Builder::macro('smartPaginate', function ($perPage = 15) {
            return $this->paginate($perPage);
        });

        // Set default query timeout to prevent long-running queries
        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_TIMEOUT, 30);

        // Add select timeout to MySQL connections
        $dbConnection = config('database.default');
        if ($dbConnection === 'mysql') {
            // MySQL specific configuration
            // DB::statement('SET SESSION MAX_EXECUTION_TIME=30000'); // 30 seconds in milliseconds

            // Set MySQL strict mode
            DB::statement("SET SESSION sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        }

        // Add global scope to all queries to ensure they're properly indexed
        // Builder::macro('withOptimizedIndexes', function () {
        //     return $this->whereRaw('1=1');
        // });
    }
}
