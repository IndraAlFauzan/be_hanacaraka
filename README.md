# 🎯 REST API Laravel 12 - Aplikasi Gamifikasi Belajar Aksara Jawa

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-7.x-DC382D?style=for-the-badge&logo=redis&logoColor=white)

Backend REST API untuk aplikasi mobile Flutter pembelajaran Aksara Jawa dengan sistem gamifikasi lengkap.

[📚 API Documentation](./API_DOCUMENTATION.md) • [🚀 Quick Start](#-quick-start) • [🎮 Features](#-features)

</div>

---

## 🛠 Tech Stack

| Category           | Technology                     |
| ------------------ | ------------------------------ |
| **Framework**      | Laravel 12                     |
| **Language**       | PHP 8.2+                       |
| **Database**       | MySQL 8.0                      |
| **Cache**          | Redis 7.x                      |
| **Authentication** | Laravel Sanctum                |
| **Storage**        | Local / AWS S3                 |
| **ML Service**     | Python Flask + TensorFlow Lite |

---

## 🚀 Quick Start

### Prerequisites

**Opsi 1: Docker (Recommended)**

- Docker Desktop
- Docker Compose

**Opsi 2: Manual**

- PHP >= 8.2
- Composer
- MySQL 8.0
- Redis Server
- XAMPP / Laravel Herd / Laravel Valet

---

## 📦 Installation

### Opsi 1: Docker (Recommended)

```bash
# 1. Clone repository
git clone <repository-url>
cd be_hanacaraka

# 2. Setup lengkap (otomatis: build, up, install, migrate)
make setup
```

**API berjalan di:** `http://localhost:8000`

**Services:**

- API: `http://localhost:8000`
- phpMyAdmin: `http://localhost:8080`
- Redis Commander: `http://localhost:8081`

**Common Commands:**

```bash
# View all available commands
make help

# Development
make up           # Start containers
make down         # Stop containers
make restart      # Restart containers
make logs         # View logs
make shell        # Access container shell

# Laravel
make migrate      # Run migrations
make seed         # Run seeders
make fresh        # Fresh migrate with seed
make tinker       # Laravel Tinker
make cache        # Clear all caches

# Database
make mysql        # Access MySQL CLI
make redis        # Access Redis CLI

# Testing
make test         # Run PHPUnit tests
make lint         # Run PHP CS Fixer
```

📘 **Panduan lengkap Docker**: [DOCKER_GUIDE.md](./docs/DOCKER_GUIDE.md)

---

### Opsi 2: Manual Installation

```bash
# 1. Clone repository
git clone <repository-url>
cd be_hanacaraka

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Configure .env
# Set DB_HOST=127.0.0.1
# Set REDIS_HOST=127.0.0.1
# Configure DB credentials

# 5. Generate application key
php artisan key:generate

# 6. Run migrations & seeders
php artisan migrate --seed

# 7. Create storage link
php artisan storage:link

# 8. Start development server
php artisan serve
```

**API berjalan di:** `http://localhost:8000`

## 🎯 API Endpoints Overview

| Category        | Endpoints | Description                 |
| --------------- | --------- | --------------------------- |
| **Auth**        | 4         | Register, login, logout, me |
| **Levels**      | 5         | CRUD operations             |
| **Stages**      | 5         | CRUD operations             |
| **Materials**   | 4         | CRUD operations             |
| **Evaluations** | 2         | Get config, submit drawing  |
| **Quizzes**     | 2         | Get quiz, submit answers    |
| **Progress**    | 1         | Get user progress           |
| **Leaderboard** | 2         | Weekly, all-time            |

Full endpoint list: [API Documentation](docs/API_DOCUMENTATION.md)

---

## 🚦 Status Codes

| Code | Description      |
| ---- | ---------------- |
| 200  | Success          |
| 201  | Created          |
| 401  | Unauthorized     |
| 403  | Forbidden        |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

---

## 📱 Frontend Integration

API ini dirancang untuk Flutter mobile app dengan:

- Token-based auth (simpan di secure storage)
- Multipart form upload untuk images
- Consistent JSON responses
- Comprehensive error messages
