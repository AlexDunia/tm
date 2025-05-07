# Production Deployment Guide for KakaWorld

This document provides steps to ensure your application is properly configured for production deployment, with special focus on error handling and security.

## 1. Environment Configuration

Create or update your `.env` file with these optimized production settings:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kakaworld.co

LOG_CHANNEL=stack
LOG_LEVEL=error

# If using file sessions, ensure proper storage
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Use HTTPS in production
SANCTUM_STATEFUL_DOMAINS=kakaworld.co
SESSION_SECURE_COOKIE=true

# Optimize cache for production
CACHE_DRIVER=file

# Database settings - keep credentials secure
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kakadb
DB_USERNAME=kakauser
DB_PASSWORD=strong_password_here
```

## 2. PHP Settings

Copy these optimized settings to your production `php.ini` or use the provided `php-production.ini`:

```ini
; Error handling - log errors but don't display them
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php/error.log
error_reporting = E_ALL

; Security settings
expose_php = Off
allow_url_fopen = Off
allow_url_include = Off
session.use_strict_mode = 1
session.use_only_cookies = 1
session.cookie_httponly = 1
session.cookie_secure = 1
session.cookie_samesite = "Lax"

; Performance optimization
max_execution_time = 120
max_input_time = 60
memory_limit = 256M
post_max_size = 20M
upload_max_filesize = 20M
max_input_vars = 3000

; OPcache settings for performance
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.validate_timestamps = 0
opcache.save_comments = 1
opcache.fast_shutdown = 0
```

## 3. Web Server Configuration

### Apache Configuration

Your `.htaccess` file should include these optimized settings:

```apache
# Performance Optimization
<IfModule mod_php.c>
    php_value max_execution_time 120
    php_value memory_limit 256M
    php_value upload_max_filesize 20M
    php_value post_max_size 20M
    php_flag opcache.enable On

    # Disable PHP error display in production
    php_flag display_errors Off
    php_flag display_startup_errors Off
php_value error_reporting E_ALL
    php_flag log_errors On

    # Hide PHP version information
    php_flag expose_php Off
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

# Remove PHP signature
Header unset X-Powered-By
ServerSignature Off
</IfModule>

# Disable directory browsing
Options -Indexes

# Prevent access to sensitive files
<FilesMatch "^\.env|composer\.json|composer\.lock|package\.json|package-lock\.json|phpunit\.xml|README\.md">
    Order allow,deny
    Deny from all
</FilesMatch>

# Custom error pages
ErrorDocument 400 /errors/400.html
ErrorDocument 401 /errors/401.html
ErrorDocument 403 /errors/403.html
ErrorDocument 404 /errors/404.html
ErrorDocument 500 /errors/500.html
```

### Nginx Configuration

For Nginx servers, ensure your configuration includes:

```nginx
# Security headers
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

# Hide server version
server_tokens off;

# PHP settings
fastcgi_param PHP_VALUE "
  display_errors=Off
  display_startup_errors=Off
  error_reporting=E_ALL
  log_errors=On
  memory_limit=256M
  max_execution_time=120
  upload_max_filesize=20M
  post_max_size=20M
  opcache.enable=1
";

# Prevent access to sensitive files
location ~ /\.env {
    deny all;
}
location ~ /composer\.(json|lock) {
    deny all;
}
location ~ /(package|phpunit)\..*$ {
    deny all;
}
location ~ /README\.md {
    deny all;
}
```

## 4. Frontend Asset Optimization

Ensure your Vite assets are properly built for production:

```bash
# Install dependencies
npm ci

# Build for production
npm run build
```

Your production deployment should always use pre-built assets - never run dev mode.

## 5. Database Settings

- Create a dedicated database user with limited permissions:
  ```sql
  CREATE USER 'kakauser'@'localhost' IDENTIFIED BY 'strong_password_here';
  GRANT SELECT, INSERT, UPDATE, DELETE ON kakadb.* TO 'kakauser'@'localhost';
  FLUSH PRIVILEGES;
  ```

- Run database migrations and seed production data:
  ```bash
  php artisan migrate --force
  php artisan db:seed --class=ProductionDataSeeder
  ```

- Configure your database server for performance:
  ```ini
  innodb_buffer_pool_size = 128M
  query_cache_size = 32M
  max_connections = 150
  ```

## 6. File Permissions

Set proper ownership and permissions:

```bash
# Set ownership
chown -R www-data:www-data /path/to/your/laravel/app

# Set permissions
find /path/to/your/laravel/app -type f -exec chmod 644 {} \;
find /path/to/your/laravel/app -type d -exec chmod 755 {} \;

# Make storage and bootstrap/cache writable
chmod -R 775 /path/to/your/laravel/app/storage
chmod -R 775 /path/to/your/laravel/app/bootstrap/cache
```

## 7. Caching for Performance

Enable Laravel's built-in caching system:

```bash
# Clear all caches first
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

## 8. Verify Error Handling

Test that errors are properly handled:

1. Intentionally cause different types of errors (database, routing, etc.)
2. Verify no sensitive information is displayed
3. Check that generic error pages are shown
4. Confirm errors are logged properly to the error log
5. Test both web and API endpoints

## 9. Monitoring and Logging

- Set up log rotation:
  ```
  /path/to/your/laravel/app/storage/logs/*.log {
      daily
      missingok
      rotate 14
      compress
      delaycompress
      notifempty
      create 0640 www-data www-data
  }
  ```

- Consider implementing monitoring services (New Relic, DataDog, etc.)
- Set up automated alerts for critical errors

## Production Deployment Checklist

- [ ] APP_DEBUG set to false in .env
- [ ] Frontend assets built with `npm run build`
- [ ] Database migrations applied
- [ ] All caches optimized with artisan commands
- [ ] PHP error display disabled
- [ ] Custom error pages configured and tested
- [ ] Database credentials secured with limited-access user
- [ ] File permissions set correctly
- [ ] HTTPS properly configured
- [ ] Security headers enabled
- [ ] Cache and log directories writable
- [ ] Log rotation configured
- [ ] Server signatures/headers removed

## Rollback Plan

In case of deployment issues, have these ready:

1. Previous version of your application code
2. Database backup from before deployment
3. List of all configuration changes made
4. Contact information for your server team

By following these steps, your KakaWorld application will handle errors gracefully in production, maintain optimal performance, and keep your data secure. 
