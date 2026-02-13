# REST API Laravel 12 - Aplikasi Gamifikasi Belajar Aksara Jawa

## 📋 Daftar Isi

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Database Schema](#database-schema)
- [Installation](#installation)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Environment Variables](#environment-variables)
- [Testing](#testing)

---

## 🎯 Overview

REST API backend untuk aplikasi mobile Flutter yang mengajarkan Aksara Jawa melalui gamifikasi. API ini mendukung:

- **2 Role**: Admin (1 user) dan Pemain (unlimited)
- **8 Level** dengan total **135 Stage** pembelajaran
- **Sequential Unlock**: Stage/Level dibuka setelah menyelesaikan yang sebelumnya
- **Drawing Evaluation**: Menggunakan TensorFlow Lite + Python Flask untuk scoring similarity ≥70%
- **Gamifikasi**: XP, Level Up, Badges, Streak, Leaderboard (weekly)
- **Quiz System**: Pilihan ganda per stage
- **File Storage**: Support local storage dan AWS S3
- **Caching**: Redis untuk leaderboard (TTL 5 menit)

---

## 🛠 Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL 8.0
- **Cache**: Redis
- **Authentication**: Laravel Sanctum (Token-based)
- **File Storage**: Local Storage / AWS S3
- **ML Service**: Python Flask (TensorFlow Lite) - deployed separately

---

## 🗄 Database Schema

### Tabel Utama (15 tables)

1. **users** - User data dengan role (admin/pemain), XP, level, streak
2. **levels** - 8 level pembelajaran dengan XP requirement
3. **stages** - 135 stage dengan reward XP
4. **materials** - Konten pembelajaran per stage (Markdown)
5. **evaluations** - Drawing challenge configuration
6. **challenge_results** - Hasil drawing submission user
7. **quizzes** - Quiz configuration per stage
8. **quiz_questions** - Soal multiple choice
9. **quiz_results** - Hasil quiz submission
10. **badges** - Badge definitions (XP, streak, level)
11. **user_badges** - Badge yang sudah diraih user
12. **leaderboard_weekly** - Leaderboard mingguan
13. **user_progress** - Progress tracking per stage
14. **password_reset_tokens** - Password reset
15. **personal_access_tokens** - Sanctum tokens

---

## 📦 Installation

### 1. Clone & Install Dependencies

```bash
cd /path/to/be_hanacaraka
composer install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env`:

```env
APP_NAME="Aksara Jawa API"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=be_hanacaraka
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

ML_SERVICE_URL=http://localhost:5000

SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

### 3. Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

**Default Admin Account**:

- Email: `admin@aksarajawa.com`
- Password: `Admin123!`

### 4. Generate Storage Link (jika menggunakan local storage)

```bash
php artisan storage:link
```

### 5. Start Development Server

```bash
php artisan serve
```

API akan berjalan di: `http://localhost:8000`

---

## 🔐 Authentication

API menggunakan **Laravel Sanctum** dengan token-based authentication.

### Register

```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response**:

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 2,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "pemain",
            "total_xp": 0,
            "current_level": 1,
            "streak_count": 0,
            "daily_goal_xp": 50
        },
        "token": "1|abc123xyz..."
    }
}
```

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

### Authenticated Requests

Semua endpoint yang memerlukan autentikasi harus menyertakan header:

```http
Authorization: Bearer {token}
```

---

## 🚀 API Endpoints

### Base URL

```
http://localhost:8000/api/v1
```

### Authentication Endpoints

| Method | Endpoint         | Description                 | Auth |
| ------ | ---------------- | --------------------------- | ---- |
| POST   | `/auth/register` | Register new user (pemain)  | ❌   |
| POST   | `/auth/login`    | Login user                  | ❌   |
| POST   | `/auth/logout`   | Logout user                 | ✅   |
| GET    | `/auth/me`       | Get authenticated user info | ✅   |

### Level Endpoints

| Method | Endpoint             | Description      | Auth | Role  |
| ------ | -------------------- | ---------------- | ---- | ----- |
| GET    | `/levels`            | Get all levels   | ✅   | All   |
| GET    | `/levels/{id}`       | Get level detail | ✅   | All   |
| POST   | `/admin/levels`      | Create new level | ✅   | Admin |
| PUT    | `/admin/levels/{id}` | Update level     | ✅   | Admin |
| DELETE | `/admin/levels/{id}` | Delete level     | ✅   | Admin |

### Stage Endpoints

| Method | Endpoint                | Description         | Auth | Role  |
| ------ | ----------------------- | ------------------- | ---- | ----- |
| GET    | `/stages?level_id={id}` | Get stages by level | ✅   | All   |
| GET    | `/stages/{id}`          | Get stage detail    | ✅   | All   |
| POST   | `/admin/stages`         | Create new stage    | ✅   | Admin |
| PUT    | `/admin/stages/{id}`    | Update stage        | ✅   | Admin |
| DELETE | `/admin/stages/{id}`    | Delete stage        | ✅   | Admin |

### Material Endpoints

| Method | Endpoint                      | Description             | Auth | Role  |
| ------ | ----------------------------- | ----------------------- | ---- | ----- |
| GET    | `/stages/{stageId}/materials` | Get materials for stage | ✅   | All   |
| POST   | `/admin/materials`            | Create new material     | ✅   | Admin |
| PUT    | `/admin/materials/{id}`       | Update material         | ✅   | Admin |
| DELETE | `/admin/materials/{id}`       | Delete material         | ✅   | Admin |

### Evaluation (Drawing Challenge) Endpoints

| Method | Endpoint                                     | Description           | Auth | Role   |
| ------ | -------------------------------------------- | --------------------- | ---- | ------ |
| GET    | `/stages/{stageId}/evaluation`               | Get evaluation config | ✅   | All    |
| POST   | `/evaluations/{evaluationId}/submit-drawing` | Submit drawing        | ✅   | Pemain |
| POST   | `/admin/evaluations`                         | Create evaluation     | ✅   | Admin  |

**Submit Drawing Example**:

```http
POST /api/v1/evaluations/1/submit-drawing
Authorization: Bearer {token}
Content-Type: multipart/form-data

drawing_image: [FILE] (PNG/JPG, max 2MB)
```

**Response**:

```json
{
    "success": true,
    "data": {
        "result_id": 123,
        "similarity_score": 82.5,
        "is_passed": true,
        "xp_earned": 10,
        "level_up": false,
        "new_badges": [],
        "next_stage_unlocked": {
            "id": 2,
            "title": "Stage 2: Huruf B"
        }
    }
}
```

### Quiz Endpoints

| Method | Endpoint                   | Description         | Auth | Role   |
| ------ | -------------------------- | ------------------- | ---- | ------ |
| GET    | `/stages/{stageId}/quiz`   | Get quiz for stage  | ✅   | All    |
| POST   | `/quizzes/{quizId}/submit` | Submit quiz answers | ✅   | Pemain |

**Submit Quiz Example**:

```http
POST /api/v1/quizzes/1/submit
Authorization: Bearer {token}
Content-Type: application/json

{
  "answers": [
    {
      "question_id": 1,
      "selected_answer": "a"
    },
    {
      "question_id": 2,
      "selected_answer": "c"
    }
  ]
}
```

**Response**:

```json
{
    "success": true,
    "data": {
        "result_id": 456,
        "score": 80,
        "is_passed": true,
        "correct_answers": 4,
        "total_questions": 5,
        "xp_earned": 10
    }
}
```

### Progress Endpoints

| Method | Endpoint                   | Description       | Auth | Role       |
| ------ | -------------------------- | ----------------- | ---- | ---------- |
| GET    | `/users/{userId}/progress` | Get user progress | ✅   | Self/Admin |

**Response**:

```json
{
  "success": true,
  "data": {
    "user_id": 2,
    "total_xp": 250,
    "current_level": 2,
    "completed_stages": 18,
    "total_stages": 135,
    "progress_percentage": 13.33,
    "streak_count": 5,
    "stages": [...]
  }
}
```

### Leaderboard Endpoints

| Method | Endpoint                                         | Description              | Auth |
| ------ | ------------------------------------------------ | ------------------------ | ---- |
| GET    | `/leaderboard/weekly?week_start_date=2026-02-10` | Get weekly leaderboard   | ✅   |
| GET    | `/leaderboard/all-time?limit=10`                 | Get all-time leaderboard | ✅   |

**Weekly Leaderboard Response**:

```json
{
    "success": true,
    "data": {
        "week_start_date": "2026-02-10",
        "top_10": [
            {
                "rank": 1,
                "user_id": 5,
                "name": "Alice",
                "avatar_url": null,
                "total_xp": 850
            }
        ],
        "current_user_rank": {
            "rank": 25,
            "total_xp": 300
        }
    }
}
```

### Health Check

| Method | Endpoint  | Description       | Auth |
| ------ | --------- | ----------------- | ---- |
| GET    | `/health` | API health status | ❌   |

---

## ⚙️ Environment Variables

### Required Variables

```env
# App
APP_NAME="Aksara Jawa API"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=be_hanacaraka
DB_USERNAME=root
DB_PASSWORD=

# Redis (for caching)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# ML Service (Python Flask)
ML_SERVICE_URL=http://localhost:5000

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

### Optional (AWS S3 untuk production)

```env
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

## 🧪 Testing

### Manual Testing dengan curl

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Get Levels
curl -X GET http://localhost:8000/api/v1/levels \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Testing dengan Postman

1. Import collection dari dokumentasi ini
2. Set environment variable `base_url` = `http://localhost:8000/api/v1`
3. Set `token` setelah login
4. Test semua endpoints

---

## 📊 Data Seeding

Database sudah di-seed dengan:

- **1 Admin user**: `admin@aksarajawa.com` / `Admin123!`
- **8 Levels**: Level 1-8 dengan XP requirements
- **135 Stages**: Distributed across all levels
- **24 Badges**: XP milestones, streak, dan level completion

---

## 🔒 Security

- ✅ Laravel Sanctum token authentication
- ✅ Role-based access control (Admin/Pemain)
- ✅ Rate limiting (60 req/min general, 5 req/min drawing)
- ✅ Input validation on all endpoints
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Laravel default)
- ✅ CSRF protection for stateful requests

---

## 📈 Performance Optimization

- ✅ Redis caching untuk leaderboard (TTL 5 menit)
- ✅ Database indexing pada kolom yang sering di-query
- ✅ Eager loading untuk menghindari N+1 queries
- ✅ Image compression untuk drawing submissions

---

## 🐛 Error Handling

All endpoints return consistent error format:

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created
- `401` - Unauthorized (invalid/missing token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

---

## 📝 Notes

### ML Service Integration

API ini membutuhkan Python Flask service untuk evaluasi drawing. Service tersebut harus:

1. Running di port 5000 (atau sesuai `ML_SERVICE_URL`)
2. Accept POST request ke `/evaluate` dengan body:
    ```json
    {
        "reference_image_url": "https://...",
        "user_drawing_url": "https://..."
    }
    ```
3. Return response:
    ```json
    {
        "similarity_score": 82.5
    }
    ```

### Sequential Unlock Logic

- Stage pertama di level pertama selalu unlocked
- Stage berikutnya unlock setelah stage sebelumnya completed
- Level unlock berdasarkan user XP ≥ level.xp_required
- Progress di-track di tabel `user_progress`

---

## 🎨 Frontend Integration

API ini dirancang untuk Flutter mobile app dengan:

- Token-based authentication (simpan di secure storage)
- Multipart form upload untuk drawing images
- JSON response untuk semua endpoints
- Consistent error handling

---

## 👥 Contributors

- **Developer**: AI Assistant
- **Project**: Aplikasi Gamifikasi Belajar Aksara Jawa

---

## 📄 License

This project is proprietary and confidential.

---

## 🔗 Related Services

- **ML Service**: Python Flask with TensorFlow Lite (separate repository)
- **Mobile App**: Flutter (separate repository)

---

## 📞 Support

For issues and questions, contact the development team.

---

**Last Updated**: February 13, 2026
