# PostgreSQL to MySQL Migration Plan

## Overview
This document outlines the steps required to migrate the Laravel application from PostgreSQL to MySQL.

## 1. Data Type Compatibility Changes

### Numeric Types
- PostgreSQL `decimal` -> MySQL `decimal` (same parameters)
- PostgreSQL integer CAST operations need to be updated for MySQL syntax

### JSON/JSONB Handling
- JSON column usage needs to be adjusted (ticket_ids in newtransactions table)
- MySQL JSON functions are different from PostgreSQL's jsonb functions

### Text Types
- No major changes needed as both databases support varchar/text

## 2. Migration Files to Update

The following migration files need modification to ensure MySQL compatibility:
- `2014_10_12_000000_create_users_table.php`
- `2023_06_18_065838_create_mctlists_table.php`
- `2023_08_30_173421_create_newtransactions_table.php`
- All other migration files to be reviewed for PostgreSQL-specific syntax

## 3. Query Modifications

### Raw Queries
Update the following raw queries:
- `CAST(cquantity AS INTEGER)` -> `CAST(cquantity AS SIGNED)`
- `CAST(ctotalprice AS DECIMAL(10,2))` -> `CAST(ctotalprice AS DECIMAL(10,2))`

### Affected Files:
- `app/Http/Controllers/CartController.php`
- `temp_header_start.txt`

## 4. Index Optimization
- Review and update indexes for MySQL optimization
- MySQL has different indexing strategies than PostgreSQL

## 5. Transaction Handling
- Update transaction isolation levels if needed
- Review all `DB::beginTransaction()` usage

## 6. Database Configuration
- `.env` file has already been updated for MySQL connection
- Confirm proper configuration in `config/database.php`

## 7. Execution Plan

### Pre-Migration Tasks
1. Create a full backup of the PostgreSQL database
2. Set up a MySQL database with the same credentials as in the updated .env file

### Migration Process
1. Update migration files for MySQL compatibility
2. Update models with MySQL-specific casts if needed
3. Update queries and raw SQL statements
4. Run fresh migrations on MySQL database
5. Import data from PostgreSQL to MySQL

### Post-Migration Tasks
1. Test all application functionality
2. Verify relationships and data integrity
3. Monitor application performance

## 8. Testing Procedure
1. Test all CRUD operations
2. Test authentication
3. Test cart functionality
4. Test checkout and payment processing
5. Test admin features 
