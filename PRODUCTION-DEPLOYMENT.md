# Production Deployment Guide

## Error Handling Configuration

This document provides steps to ensure errors are handled safely in production without exposing sensitive information.

### 1. Environment Configuration

Create or update your `.env` file with these production settings:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-production-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 2. PHP Settings

Copy the provided `php-production.ini` to your production server and ensure it's loaded:

- For Apache: Update the `php.ini` path or use `php_admin_value` directives in your virtual host configuration.
- For Nginx: Update the `fastcgi_param` PHP_VALUE directive with the settings.

### 3. Web Server Configuration

#### Apache Configuration

Add this to your `.htaccess` file or virtual host configuration:

```apache
# Disable PHP error display
php_flag display_errors off
php_flag display_startup_errors off
php_value error_reporting E_ALL

# Remove PHP signature
Header unset X-Powered-By
ServerSignature Off
```

#### Nginx Configuration

Ensure our `nginx-secure-headers.conf` is included in your server block:

```nginx
include /path/to/nginx-secure-headers.conf;
```

### 4. Database Settings

- Ensure database credentials are properly secured and not exposed in error messages
- Set up a database user with restricted permissions for the application
- Configure your database server to not expose version information

### 5. Verify Error Handling

Test that errors are properly handled:

1. Intentionally cause a database connection error
2. Verify no sensitive information is displayed
3. Check that generic error pages are shown
4. Confirm errors are logged properly to the error log
5. Test both web interface and API endpoints

### 6. Monitoring and Logging

- Set up a log monitoring system to alert on critical errors
- Regularly review logs for security issues
- Consider implementing a third-party error tracking service

### Security Checklist

- [ ] APP_DEBUG set to false in .env
- [ ] Custom error pages configured and tested
- [ ] PHP error display disabled
- [ ] Database credentials secured
- [ ] Server signatures/headers removed
- [ ] HTTPS configured properly
- [ ] File permissions set correctly
- [ ] Log rotation configured
- [ ] Error monitoring system in place

By following these steps, your Laravel application will handle errors gracefully in production without exposing sensitive code or configuration details to users. 
