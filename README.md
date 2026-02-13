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

## 📖 Tentang Proyek

Aplikasi mobile Flutter untuk belajar Aksara Jawa melalui sistem gamifikasi yang menyenangkan. Backend REST API ini menyediakan:

- **8 Level Pembelajaran** dengan total **135 Stage**
- **Drawing Challenge** dengan AI Scoring (TensorFlow Lite)
- **Quiz System** dengan multiple choice
- **Gamifikasi**: XP, Badges, Streak, Leaderboard
- **Sequential Unlock**: Stage/Level unlock berdasarkan progress

---

## 🎮 Features

### ✅ Authentication & Authorization

- [x] Laravel Sanctum token-based authentication
- [x] 2 Role: Admin (1 user) & Pemain (unlimited)
- [x] Rate limiting: 60 req/min (general), 5 req/min (drawing)

### ✅ Learning System

- [x] 8 Levels dengan progressive XP requirements
- [x] 135 Stages terdistribusi across all levels
- [x] Sequential unlock mechanism
- [x] Learning materials dengan Markdown support

### ✅ Drawing Challenge

- [x] Upload drawing (PNG/JPG, max 2MB)
- [x] AI-powered similarity scoring (≥70% to pass)
- [x] Integration dengan Python Flask ML service
- [x] Automatic XP & stage completion

### ✅ Quiz System

- [x] Multiple choice questions per stage
- [x] Automatic scoring (≥60% to pass)
- [x] XP reward untuk passing quiz
- [x] Quiz result tracking

### ✅ Gamification

- [x] XP system dengan level progression
- [x] 18 Badges (XP milestones, streaks, level completion)
- [x] Daily streak tracking
- [x] Weekly leaderboard dengan Redis caching
- [x] All-time leaderboard

### ✅ Progress Tracking

- [x] Per-stage progress monitoring
- [x] Completion percentage calculation
- [x] Stage unlock logic
- [x] User progress dashboard

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

- PHP >= 8.2
- Composer
- MySQL 8.0
- Redis Server
- XAMPP / Laravel Herd / Laravel Valet

### Installation

1. **Clone repository**

    ```bash
    cd /Applications/XAMPP/xamppfiles/htdocs/be_hanacaraka
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

3. **Setup environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure database** di `.env`

    ```env
    DB_DATABASE=be_hanacaraka
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Run migrations & seeders**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Create storage symlink**

    ```bash
    php artisan storage:link
    ```

7. **Start development server**
    ```bash
    php artisan serve
    ```

API berjalan di: **http://localhost:8000**

### Default Admin Account

```
Email: admin@aksarajawa.com
Password: Admin123!
```

---

## 📚 Documentation

Dokumentasi lengkap API tersedia di: **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)**

### Quick Links

- [Authentication Endpoints](./API_DOCUMENTATION.md#authentication-endpoints)
- [Level & Stage Management](./API_DOCUMENTATION.md#level-endpoints)
- [Drawing Challenge](./API_DOCUMENTATION.md#evaluation-drawing-challenge-endpoints)
- [Quiz System](./API_DOCUMENTATION.md#quiz-endpoints)
- [Leaderboard](./API_DOCUMENTATION.md#leaderboard-endpoints)
- [Environment Variables](./API_DOCUMENTATION.md#environment-variables)

---

## 📂 Project Structure

```
be_hanacaraka/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── LevelController.php
│   │   │   ├── StageController.php
│   │   │   ├── MaterialController.php
│   │   │   ├── EvaluationController.php
│   │   │   ├── QuizController.php
│   │   │   ├── ChallengeController.php
│   │   │   ├── ProgressController.php
│   │   │   └── LeaderboardController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Level.php
│   │   ├── Stage.php
│   │   ├── Material.php
│   │   ├── Evaluation.php
│   │   ├── ChallengeResult.php
│   │   ├── Quiz.php
│   │   ├── QuizQuestion.php
│   │   ├── QuizResult.php
│   │   ├── Badge.php
│   │   ├── UserProgress.php
│   │   └── LeaderboardWeekly.php
│   └── Services/
│       ├── ProgressService.php
│       ├── GamificationService.php
│       ├── LeaderboardService.php
│       └── DrawingEvaluationService.php
├── database/
│   ├── migrations/ (15 migration files)
│   └── seeders/
│       ├── AdminSeeder.php
│       ├── LevelsSeeder.php
│       ├── StagesSeeder.php
│       └── BadgesSeeder.php
├── routes/
│   └── api.php
└── config/
    ├── auth.php
    ├── sanctum.php
    └── database.php
```

---

## 🗄 Database Schema

### 15 Tables

| Table                    | Description                         |
| ------------------------ | ----------------------------------- |
| `users`                  | User data (role, XP, level, streak) |
| `levels`                 | 8 learning levels                   |
| `stages`                 | 135 learning stages                 |
| `materials`              | Learning content (Markdown)         |
| `evaluations`            | Drawing challenge config            |
| `challenge_results`      | Drawing submission results          |
| `quizzes`                | Quiz configuration                  |
| `quiz_questions`         | Multiple choice questions           |
| `quiz_results`           | Quiz submission results             |
| `badges`                 | Badge definitions                   |
| `user_badges`            | Earned badges                       |
| `leaderboard_weekly`     | Weekly rankings                     |
| `user_progress`          | Stage progress tracking             |
| `password_reset_tokens`  | Password resets                     |
| `personal_access_tokens` | Sanctum tokens                      |

**Full ERD & relationships**: See [API_DOCUMENTATION.md](./API_DOCUMENTATION.md#database-schema)

---

## 🔐 Security Features

- ✅ Token-based authentication (Laravel Sanctum)
- ✅ Role-based access control (Admin/Pemain)
- ✅ Rate limiting per endpoint
- ✅ Input validation on all requests
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection
- ✅ CSRF protection for stateful requests

---

## 📈 Performance Optimization

- ✅ Redis caching untuk leaderboard (TTL 5 menit)
- ✅ Database indexing pada frequent queries
- ✅ Eager loading untuk N+1 query prevention
- ✅ Efficient SQL queries dengan window functions

---

## 🧪 Testing

### Manual Testing

```bash
# Test authentication
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Test endpoints with token
curl -X GET http://localhost:8000/api/v1/levels \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Postman Collection

Import dokumentasi API ke Postman untuk testing lengkap semua endpoints.

---

## 🔧 Configuration

### Environment Variables

Key environment variables yang perlu dikonfigurasi:

```env
# App
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

# Database
DB_DATABASE=be_hanacaraka

# Redis (required untuk leaderboard)
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# ML Service (Python Flask)
ML_SERVICE_URL=http://localhost:5000
ML_SERVICE_TIMEOUT=30

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

**Full list**: [API_DOCUMENTATION.md#environment-variables](./API_DOCUMENTATION.md#environment-variables)

---

## 🤖 ML Service Integration

API ini terintegrasi dengan Python Flask service untuk drawing evaluation:

**Expected ML Service Endpoint**:

```
POST http://localhost:5000/evaluate
Content-Type: application/json

{
  "reference_image_url": "https://...",
  "user_drawing_url": "https://..."
}

Response:
{
  "similarity_score": 82.5
}
```

ML service menggunakan **TensorFlow Lite** untuk scoring similarity drawing user dengan reference image.

---

## 📊 Seeded Data

Setelah `php artisan db:seed`:

- ✅ 1 Admin user
- ✅ 8 Levels (dengan XP requirements)
- ✅ 135 Stages (distributed: 20+18+18+17+17+16+15+14)
- ✅ 18 Badges (6 XP + 4 Streak + 8 Level)

---

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

**Total**: 60+ endpoints

Full endpoint list: [API_DOCUMENTATION.md#api-endpoints](./API_DOCUMENTATION.md#api-endpoints)

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

---

## 🤝 Contributing

Project ini merupakan proprietary software. Untuk kontribusi, hubungi development team.

---

## 📄 License

Proprietary and Confidential

---

## 📞 Support

Untuk pertanyaan dan dukungan, hubungi:

- **Email**: support@aksarajawa.com
- **Developer**: GitHub Copilot

---

## 🎉 Acknowledgments

- Laravel Framework Team
- TensorFlow Team
- Redis Contributors

---

<div align="center">

**Built with ❤️ using Laravel 12**

[⬆ Back to Top](#-rest-api-laravel-12---aplikasi-gamifikasi-belajar-aksara-jawa)

</div>
