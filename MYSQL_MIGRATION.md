# PostgreSQL to MySQL Migration Guide

This document provides instructions for migrating the Laravel application from PostgreSQL to MySQL.

## Prerequisites

1. MySQL server installed and running
2. PostgreSQL server with your existing data
3. Laravel application up to date

## Migration Options

You can choose from one of these migration methods:

### Option 1: One-step Command (Recommended)

We've created a custom Artisan command that handles the entire migration process:

```bash
php artisan db:migrate-to-mysql --pg-database=your_pg_database --pg-username=your_pg_username --pg-password=your_pg_password
```

This command will:
- Verify both database connections
- Run fresh migrations on MySQL
- Copy all data from PostgreSQL to MySQL
- Handle JSON data conversion and other data type adjustments

### Option 2: Manual Migration

If you prefer a step-by-step approach:

1. Update your `.env` file with MySQL connection details:
   ```bash
   php database/update-env-for-mysql.php
   ```

2. Run migrations on the MySQL database:
   ```bash
   php artisan migrate:fresh
   ```

3. Run the data migration script:
   ```bash
   php database/pg-to-mysql-migration.php
   ```

## Changes Made for MySQL Compatibility

We've updated the following components to ensure compatibility with MySQL:

1. Database queries:
   - Changed `CAST(cquantity AS INTEGER)` to `CAST(cquantity AS SIGNED)`
   - Updated JSON data handling

2. Migration files:
   - Added explicit JSON handling for the `newtransactions` table

3. Models:
   - Added proper type casting in models
   - Updated JSON attribute handling

4. Database configuration:
   - Updated `AppServiceProvider` with MySQL-specific configurations

## Testing After Migration

After migration, test the following functionality:

1. User authentication (login, registration)
2. Cart functionality
3. Checkout process
4. Transaction history
5. Admin features

## Troubleshooting

If you encounter issues after migration:

1. **Database Connection Issues**: Verify your MySQL connection details in `.env` file
2. **Missing Data**: Use the `db:migrate-to-mysql` command with debugging enabled
3. **JSON Data Problems**: Check the `newtransactions` table's `ticket_ids` column format

## Reverting to PostgreSQL

To revert to PostgreSQL if needed:

1. Restore your original `.env` file
2. Run `php artisan migrate:fresh` to recreate the schema in PostgreSQL
3. Restore your PostgreSQL data from backup 
