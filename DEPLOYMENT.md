# 🚀 Deployment Guide - Aksara Jawa API

Panduan lengkap untuk deploy REST API Laravel ke production server.

---

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Server Requirements](#server-requirements)
- [Production Deployment](#production-deployment)
- [Database Setup](#database-setup)
- [Redis Configuration](#redis-configuration)
- [Web Server Configuration](#web-server-configuration)
- [SSL Certificate](#ssl-certificate)
- [Environment Variables](#environment-variables)
- [Post-Deployment](#post-deployment)
- [Troubleshooting](#troubleshooting)

---

## ✅ Prerequisites

Sebelum deploy, pastikan:

- [x] Server Linux (Ubuntu 22.04 LTS recommended)
- [x] Domain name sudah pointing ke server IP
- [x] SSH access ke server
- [x] SSL certificate (Let's Encrypt recommended)

---

## 🖥 Server Requirements

### Minimum Specifications

| Component    | Requirement                   |
| ------------ | ----------------------------- |
| OS           | Ubuntu 22.04 LTS / Debian 11+ |
| CPU          | 2 vCPU                        |
| RAM          | 2 GB                          |
| Storage      | 20 GB SSD                     |
| PHP          | 8.2 or higher                 |
| MySQL        | 8.0                           |
| Redis        | 7.x                           |
| Nginx/Apache | Latest stable                 |

### Required PHP Extensions

```bash
php8.2
php8.2-cli
php8.2-fpm
php8.2-mysql
php8.2-redis
php8.2-mbstring
php8.2-xml
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-bcmath
php8.2-intl
```

---

## 🚀 Production Deployment

### 1. Install Server Software

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-redis php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install Redis
sudo apt install redis-server -y
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Setup Application Directory

```bash
# Create directory
sudo mkdir -p /var/www/aksarajawa-api
sudo chown -R $USER:$USER /var/www/aksarajawa-api

# Clone repository (atau upload via FTP)
cd /var/www/aksarajawa-api
# git clone your-repo-url .

# atau upload file manual lalu:
# unzip your-project.zip
# mv be_hanacaraka/* .
```

### 3. Install Dependencies

```bash
cd /var/www/aksarajawa-api
composer install --optimize-autoloader --no-dev
```

### 4. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env
nano .env
```

**Production `.env` settings**:

```env
APP_NAME="Aksara Jawa API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.aksarajawa.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=be_hanacaraka_prod
DB_USERNAME=aksara_user
DB_PASSWORD=strong_random_password_here

CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_password_here
REDIS_PORT=6379

ML_SERVICE_URL=https://ml.aksarajawa.com

SANCTUM_STATEFUL_DOMAINS=app.aksarajawa.com,aksarajawa.com

# AWS S3 (recommended untuk production)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=aksarajawa-uploads

SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

LOG_CHANNEL=daily
LOG_LEVEL=warning
```

### 5. Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/aksarajawa-api

# Set permissions
sudo chmod -R 755 /var/www/aksarajawa-api
sudo chmod -R 775 /var/www/aksarajawa-api/storage
sudo chmod -R 775 /var/www/aksarajawa-api/bootstrap/cache

# Create storage link
php artisan storage:link
```

---

## 🗄 Database Setup

### 1. Create Database & User

```bash
sudo mysql -u root -p
```

```sql
-- Create database
CREATE DATABASE be_hanacaraka_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'aksara_user'@'localhost' IDENTIFIED BY 'strong_random_password_here';

-- Grant privileges
GRANT ALL PRIVILEGES ON be_hanacaraka_prod.* TO 'aksara_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Exit
EXIT;
```

### 2. Run Migrations & Seeders

```bash
cd /var/www/aksarajawa-api

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force
```

### 3. Verify Database

```bash
php artisan tinker

# Test connection
DB::connection()->getPdo();

# Count tables
DB::select('SHOW TABLES');
```

---

## 🔴 Redis Configuration

### 1. Secure Redis

```bash
sudo nano /etc/redis/redis.conf
```

**Update settings**:

```conf
# Bind to localhost only
bind 127.0.0.1 ::1

# Set password
requirepass your_strong_redis_password

# Enable persistence
save 900 1
save 300 10
save 60 10000

# Max memory
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### 2. Restart Redis

```bash
sudo systemctl restart redis-server
sudo systemctl status redis-server
```

### 3. Test Connection

```bash
redis-cli -a your_strong_redis_password

# Test
PING
# Should return: PONG

# Exit
EXIT
```

---

## 🌐 Web Server Configuration

### Nginx Configuration

#### 1. Create Nginx Config

```bash
sudo nano /etc/nginx/sites-available/aksarajawa-api
```

**Paste configuration**:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name api.aksarajawa.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name api.aksarajawa.com;

    root /var/www/aksarajawa-api/public;
    index index.php index.html;

    # SSL Configuration (will be updated by Certbot)
    ssl_certificate /etc/letsencrypt/live/api.aksarajawa.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.aksarajawa.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Logging
    access_log /var/log/nginx/aksarajawa-api-access.log;
    error_log /var/log/nginx/aksarajawa-api-error.log;

    # Upload size limit
    client_max_body_size 2M;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 2. Enable Site

```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/aksarajawa-api /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## 🔒 SSL Certificate

### Using Let's Encrypt (Certbot)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain certificate
sudo certbot --nginx -d api.aksarajawa.com

# Test auto-renewal
sudo certbot renew --dry-run
```

Certificate akan auto-renew setiap 60 hari.

---

## ⚙️ Environment Variables

### Critical Production Settings

```env
# Security
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here

# Database (use strong password)
DB_PASSWORD=random_secure_password_min_16_chars

# Redis (use strong password)
REDIS_PASSWORD=random_secure_redis_password

# Session & Cache
SESSION_DRIVER=redis
CACHE_STORE=redis

# Queue (optional, untuk background jobs)
QUEUE_CONNECTION=redis

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=warning

# Rate Limiting (sudah dikonfigurasi di AppServiceProvider)
# Default: 60/min untuk API, 5/min untuk drawing submission
```

---

## 🔄 Post-Deployment

### 1. Clear & Cache Config

```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Setup Queue Worker (Optional)

Jika menggunakan queues:

```bash
# Install Supervisor
sudo apt install supervisor -y

# Create worker config
sudo nano /etc/supervisor/conf.d/aksarajawa-worker.conf
```

**Paste**:

```ini
[program:aksarajawa-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/aksarajawa-api/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/aksarajawa-api/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Update supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start aksarajawa-worker:*
```

### 3. Setup Cron Jobs

```bash
sudo crontab -e
```

**Add**:

```cron
# Laravel Scheduler
* * * * * cd /var/www/aksarajawa-api && php artisan schedule:run >> /dev/null 2>&1

# Weekly leaderboard reset (every Monday 00:00)
0 0 * * 1 cd /var/www/aksarajawa-api && php artisan leaderboard:reset-weekly >> /dev/null 2>&1
```

### 4. Test API

```bash
# Health check
curl https://api.aksarajawa.com/api/v1/health

# Test authentication
curl -X POST https://api.aksarajawa.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@aksarajawa.com","password":"Admin123!"}'
```

---

## 🐛 Troubleshooting

### Issue 1: 502 Bad Gateway

```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Issue 2: Permission Denied

```bash
# Reset permissions
sudo chown -R www-data:www-data /var/www/aksarajawa-api
sudo chmod -R 755 /var/www/aksarajawa-api
sudo chmod -R 775 /var/www/aksarajawa-api/storage
sudo chmod -R 775 /var/www/aksarajawa-api/bootstrap/cache
```

### Issue 3: Redis Connection Failed

```bash
# Check Redis status
sudo systemctl status redis-server

# Test Redis connection
redis-cli -a your_redis_password ping

# Check Laravel logs
tail -f /var/www/aksarajawa-api/storage/logs/laravel.log
```

### Issue 4: Database Connection Error

```bash
# Test MySQL connection
mysql -u aksara_user -p be_hanacaraka_prod

# Check MySQL status
sudo systemctl status mysql

# Verify credentials in .env
grep DB_ /var/www/aksarajawa-api/.env
```

### Issue 5: CORS Errors

Update Sanctum stateful domains di `.env`:

```env
SANCTUM_STATEFUL_DOMAINS=app.aksarajawa.com,www.aksarajawa.com
```

Then clear config:

```bash
php artisan config:clear
php artisan config:cache
```

---

## 📊 Monitoring

### Check Application Health

```bash
# Check Nginx access log
sudo tail -f /var/log/nginx/aksarajawa-api-access.log

# Check Laravel logs
tail -f /var/www/aksarajawa-api/storage/logs/laravel.log

# Check disk space
df -h

# Check memory usage
free -m

# Check CPU usage
top
```

### Performance Monitoring Tools

- **Laravel Telescope** (development only)
- **New Relic** (recommended for production)
- **Datadog**
- **Sentry** (error tracking)

---

## 🔄 Updates & Maintenance

### Deploying Updates

```bash
cd /var/www/aksarajawa-api

# Pull latest code
# git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear & cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
```

### Database Backup

```bash
# Create backup script
sudo nano /usr/local/bin/backup-aksarajawa-db.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u aksara_user -p'your_password' be_hanacaraka_prod | gzip > $BACKUP_DIR/aksarajawa_$DATE.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "aksarajawa_*.sql.gz" -mtime +7 -delete
```

```bash
# Make executable
sudo chmod +x /usr/local/bin/backup-aksarajawa-db.sh

# Add to cron (daily at 2 AM)
sudo crontab -e
```

```cron
0 2 * * * /usr/local/bin/backup-aksarajawa-db.sh >> /var/log/mysql-backup.log 2>&1
```

---

## 📝 Checklist Deployment

- [ ] Server requirements terpenuhi
- [ ] Domain pointing ke server IP
- [ ] SSL certificate installed
- [ ] Database created & migrated
- [ ] Redis configured & secured
- [ ] Environment variables configured
- [ ] File permissions set correctly
- [ ] Nginx/Apache configured
- [ ] Config cached untuk production
- [ ] Seeder dijalankan (admin user created)
- [ ] API health check passed
- [ ] Authentication tested
- [ ] Backup scheduled
- [ ] Monitoring tools installed
- [ ] Documentation updated

---

## 🎯 Production Best Practices

1. **Never** set `APP_DEBUG=true` di production
2. Gunakan strong passwords (min 16 characters)
3. Enable Redis authentication
4. Gunakan HTTPS (SSL) untuk semua endpoints
5. Setup automated backups (database & files)
6. Monitor logs regularly
7. Keep Laravel & dependencies updated
8. Use CDN untuk static assets (jika ada)
9. Enable OPcache untuk PHP
10. Setup rate limiting per IP (via Nginx)

---

## 📞 Support

Jika ada masalah saat deployment:

1. Check Laravel logs: `/storage/logs/laravel.log`
2. Check Nginx error logs: `/var/log/nginx/error.log`
3. Check PHP-FPM logs: `/var/log/php8.2-fpm.log`
4. Contact development team

---

**Last Updated**: February 13, 2026
