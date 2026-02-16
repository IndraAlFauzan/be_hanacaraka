# 📚 Hanacaraka API Documentation

**Version:** 2.0  
**Base URL:** `http://localhost:8000/api/v1`  
**Last Updated:** February 2026

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Response Format](#response-format)
4. [Error Handling](#error-handling)
5. [Rate Limiting](#rate-limiting)
6. [🌐 Public Endpoints](#-public-endpoints)
7. [🎮 Player Endpoints](#-player-endpoints)
8. [🔧 Admin Endpoints](#-admin-endpoints)
9. [Data Models](#data-models)
10. [Quick Reference](#quick-reference)

---

## 📖 Overview

Hanacaraka adalah REST API untuk aplikasi pembelajaran aksara Jawa. API ini menyediakan fitur untuk:

- Autentikasi pengguna (login/register)
- Manajemen level dan stage pembelajaran
- Materi pembelajaran aksara Jawa
- Quiz dan evaluasi
- Challenge menggambar aksara
- Tracking progress dan XP
- Leaderboard
- Badge/achievement system
- Transliterasi Latin ↔ Aksara Jawa

### 🎯 Learning Flow (Alur Pembelajaran)

Setiap stage memiliki `evaluation_type` yang menentukan cara menyelesaikan stage:

| evaluation_type | Cara Menyelesaikan      | XP Distribution        |
| --------------- | ----------------------- | ---------------------- |
| `drawing`       | Drawing Challenge saja  | 100% XP dari drawing   |
| `quiz`          | Quiz saja               | 100% XP dari quiz      |
| `both`          | Drawing + Quiz keduanya | 50% drawing + 50% quiz |

```
┌─────────────────────────────────────────────────────────────────────┐
│                    STAGE WORKFLOW BY TYPE                           │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  evaluation_type = "drawing" (Default)                              │
│  ══════════════════════════════════════                             │
│   📖 Material → ✏️ Drawing Challenge → ✅ Stage Complete            │
│                        ↓                                            │
│                   📝 Quiz (Optional, bonus XP only)                 │
│                                                                     │
│  evaluation_type = "quiz"                                           │
│  ════════════════════════                                           │
│   📖 Material → 📝 Quiz → ✅ Stage Complete                         │
│                                                                     │
│  evaluation_type = "both"                                           │
│  ═══════════════════════                                            │
│   📖 Material → ✏️ Drawing (50% XP) → 📝 Quiz (50% XP)              │
│                                    → ✅ Stage Complete              │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🔐 Authentication

API menggunakan **Laravel Sanctum** dengan Bearer Token.

### Request Header

```http
Authorization: Bearer {your_access_token}
Content-Type: application/json
Accept: application/json
```

### Roles

| Role     | Description                       |
| -------- | --------------------------------- |
| `pemain` | Player - pengguna aplikasi mobile |
| `admin`  | Administrator - akses panel admin |

---

## 📤 Response Format

### Success Response

```json
{
    "success": true,
    "message": "Operation successful",
    "data": { ... }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description"
}
```

### Validation Error (422)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

---

## ⚠️ Error Handling

| Code | Description       |
| ---- | ----------------- |
| 200  | OK                |
| 201  | Created           |
| 400  | Bad Request       |
| 401  | Unauthorized      |
| 403  | Forbidden         |
| 404  | Not Found         |
| 422  | Validation Error  |
| 429  | Too Many Requests |
| 500  | Server Error      |

---

## ⏱️ Rate Limiting

| Endpoint           | Limit      |
| ------------------ | ---------- |
| Drawing submission | 10 req/min |
| General API        | 60 req/min |

---

# 🌐 Public Endpoints

Endpoint yang dapat diakses tanpa autentikasi.

---

## Health Check

### GET `/health`

Cek status kesehatan API.

**Response:**

```json
{
    "status": "ok",
    "timestamp": "2026-02-14T10:00:00+07:00",
    "database": "connected",
    "cache": "working"
}
```

---

## Authentication

### POST `/auth/register`

Mendaftarkan user baru.

**Request:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "pemain",
            "total_xp": 0,
            "current_level": 1
        },
        "token": "1|abc123..."
    }
}
```

---

### POST `/auth/login`

Login user.

**Request:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": { ... },
        "token": "2|xyz789..."
    }
}
```

---

# 🎮 Player Endpoints

Endpoint untuk pengguna dengan role `pemain`. Semua endpoint memerlukan autentikasi.

---

## 👤 Profile & Account

### GET `/auth/me`

Get info user yang sedang login.

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "pemain",
        "total_xp": 500,
        "current_level": 3,
        "streak_count": 5,
        "badges_count": 3,
        "completed_stages_count": 10
    }
}
```

---

### POST `/auth/logout`

Logout user.

**Response:**

```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

### GET `/profile`

Get profile detail user.

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "total_xp": 500,
        "current_level": 3,
        "streak_count": 5,
        "daily_goal_xp": 50,
        "avatar_url": "/storage/avatars/1.jpg",
        "badges": [...]
    }
}
```

---

### PUT `/profile`

Update profile user.

**Request:**

```json
{
    "name": "John Updated",
    "daily_goal_xp": 100
}
```

---

### POST `/profile/avatar`

Upload avatar (multipart/form-data).

| Field  | Type | Rules                       |
| ------ | ---- | --------------------------- |
| avatar | file | required, image, max:2048KB |

---

## 📚 Learning Content

### GET `/levels`

List semua level dengan status unlock.

**Query Parameters:**
| Param | Description |
|-------|-------------|
| is_active | Filter by status aktif |

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "level_number": 1,
            "title": "Pengenalan Aksara Jawa",
            "xp_required": 0,
            "is_unlocked": true,
            "total_stages": 20
        },
        {
            "id": 2,
            "level_number": 2,
            "title": "Aksara Vokal",
            "xp_required": 150,
            "is_unlocked": false,
            "total_stages": 18
        }
    ]
}
```

---

### GET `/levels/{id}`

Detail level dengan daftar stages.

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "level_number": 1,
        "title": "Pengenalan Aksara Jawa",
        "description": "Level dasar untuk mengenal huruf-huruf Aksara Jawa",
        "xp_required": 0,
        "stages": [
            {
                "id": 1,
                "stage_number": 1,
                "title": "Aksara Ha",
                "xp_reward": 15,
                "evaluation_type": "quiz"
            }
        ]
    }
}
```

---

### GET `/stages`

List semua stages dengan progress status.

**Query Parameters:**
| Param | Description |
|-------|-------------|
| level_id | Filter by level |
| is_active | Filter by status aktif |

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "level_id": 1,
            "stage_number": 1,
            "title": "Aksara Ha",
            "xp_reward": 15,
            "evaluation_type": "quiz",
            "is_active": true,
            "is_unlocked": true,
            "status": "completed",
            "has_material": true,
            "has_evaluation": true,
            "has_quiz": true
        }
    ]
}
```

**evaluation_type:**
| Value | Description |
|-------|-------------|
| `drawing` | Stage diselesaikan dengan menggambar aksara |
| `quiz` | Stage diselesaikan dengan menjawab quiz |
| `both` | Stage memerlukan keduanya |

**status:**
| Value | Description |
|-------|-------------|
| `locked` | Stage belum terbuka |
| `in_progress` | Sedang dikerjakan |
| `completed` | Sudah selesai |

---

### GET `/stages/{id}`

Detail stage dengan semua konten.

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "level_id": 1,
        "stage_number": 1,
        "title": "Aksara Ha",
        "xp_reward": 15,
        "evaluation_type": "quiz",
        "level": {
            "id": 1,
            "title": "Pengenalan Aksara Jawa"
        },
        "materials": [...],
        "quizzes": [...],
        "evaluations": [...]
    }
}
```

---

### GET `/stages/{stageId}/materials`

Get semua materi untuk stage.

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "stage_id": 1,
            "title": "Mengenal Aksara Ha",
            "content_markdown": "# Aksara Ha\n\n...",
            "image_url": "/storage/aksara/Ha.png",
            "order_index": 1
        }
    ]
}
```

---

### GET `/stages/{stageId}/quiz`

Get quiz untuk stage.

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "stage_id": 1,
        "title": "Quiz Aksara Ha",
        "passing_score": 60,
        "questions": [
            {
                "id": 1,
                "question_text": "Aksara Jawa di bawah ini dibaca apa?\n\nꦲ",
                "option_a": "Ha",
                "option_b": "Na",
                "option_c": "Ca",
                "option_d": "Ra",
                "order_index": 1
            }
        ]
    }
}
```

---

### GET `/stages/{stageId}/evaluation`

Get evaluation (drawing challenge) untuk stage.

**Response:**

```json
{
    "success": true,
    "data": {
        "evaluation": {
            "id": 1,
            "stage_id": 1,
            "character_target": "ꦲ",
            "reference_image_url": "/storage/aksara/Ha.png",
            "min_similarity_score": 70
        },
        "user_attempts": 3,
        "user_best_score": 85.5
    }
}
```

---

## 📝 Submit Answers

### POST `/quizzes/{quizId}/submit`

Submit jawaban quiz.

**Request:**

```json
{
    "answers": [
        { "question_id": 1, "selected_answer": "a" },
        { "question_id": 2, "selected_answer": "b" },
        { "question_id": 3, "selected_answer": "c" }
    ]
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "result_id": 123,
        "score": 80,
        "is_passed": true,
        "correct_answers": 4,
        "total_questions": 5,
        "xp_earned": 15,
        "stage_completed": true
    }
}
```

**Behavior by evaluation_type:**

| evaluation_type | Jika Quiz Lulus                                                |
| --------------- | -------------------------------------------------------------- |
| `drawing`       | Bonus XP saja (50-100% stage XP), `stage_completed: false`     |
| `quiz`          | Full stage XP, `stage_completed: true`                         |
| `both`          | 50% stage XP, `stage_completed: true` jika drawing sudah lulus |

---

### POST `/stages/{stageId}/submit-drawing`

Submit gambar aksara (untuk evaluation_type: drawing/both).

**Content-Type:** `multipart/form-data`  
**Rate Limit:** 10 requests/minute

| Field            | Type   | Rules                         |
| ---------------- | ------ | ----------------------------- |
| drawing_image    | file   | required, image, max:2048KB   |
| similarity_score | number | required, 0-100 (dari TFLite) |

> ⚠️ **Note:** `similarity_score` dihitung oleh TFLite model di mobile app, lalu dikirim ke backend.

**Response (Lulus):**

```json
{
    "success": true,
    "data": {
        "result_id": 456,
        "similarity_score": 85.5,
        "is_passed": true,
        "xp_earned": 15,
        "level_up": false,
        "stage_completed": true,
        "new_badges": [...],
        "next_stage_unlocked": {
            "id": 2,
            "title": "Aksara Na",
            "stage_number": 2
        }
    }
}
```

**Behavior by evaluation_type:**

| evaluation_type | Jika Drawing Lulus                                     |
| --------------- | ------------------------------------------------------ |
| `drawing`       | Full stage XP, `stage_completed: true`                 |
| `quiz`          | Drawing tidak digunakan                                |
| `both`          | 50% stage XP, `stage_completed: false` (quiz required) |

---

## 📊 Progress & Stats

### GET `/progress`

Get progress summary user.

**Response:**

```json
{
    "success": true,
    "data": {
        "user_id": 2,
        "total_xp": 15,
        "current_level": 1,
        "total_completed_stages": 1,
        "total_stages": 135,
        "completion_percentage": 0.7,
        "current_streak": 1,
        "last_activity_date": "2026-02-15T00:00:00.000000Z",
        "stages": [
            {
                "stage_id": 137,
                "stage_title": "Aksara Ha",
                "level_id": 1,
                "status": "completed",
                "completed_at": "2026-02-15T11:59:25.000000Z"
            }
        ]
    }
}
```

---

### GET `/progress/levels`

Get detail progress per level dengan semua stages.

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "level_id": 1,
            "level_number": 1,
            "title": "Pengenalan Aksara Jawa",
            "xp_required": 0,
            "is_unlocked": true,
            "total_stages": 20,
            "completed_stages": 5,
            "completion_percentage": 25.0,
            "stages": [
                {
                    "stage_id": 1,
                    "stage_number": 1,
                    "title": "Aksara Ha",
                    "xp_reward": 15,
                    "evaluation_type": "quiz",
                    "status": "completed",
                    "completed_at": "2026-02-10T10:00:00Z"
                },
                {
                    "stage_id": 2,
                    "stage_number": 2,
                    "title": "Aksara Na",
                    "xp_reward": 15,
                    "evaluation_type": "drawing",
                    "status": "in_progress",
                    "completed_at": null
                }
            ]
        }
    ]
}
```

**status values:**
| Value | Description |
|-------|-------------|
| `locked` | Stage belum terbuka |
| `unlocked` | Stage terbuka, belum dikerjakan |
| `in_progress` | Sedang dikerjakan |
| `completed` | Sudah selesai |

---

### GET `/progress/levels/{levelId}`

Get detail progress untuk level spesifik.

**Response:**

```json
{
    "success": true,
    "data": {
        "level_id": 1,
        "level_number": 1,
        "title": "Pengenalan Aksara Jawa",
        "description": "Level dasar untuk mengenal huruf-huruf Aksara Jawa",
        "xp_required": 0,
        "is_unlocked": true,
        "total_stages": 20,
        "completed_stages": 5,
        "completion_percentage": 25.0,
        "stages": [...]
    }
}
```

---

### GET `/my-badges`

Get badges yang sudah didapat user beserta waktu perolehan.

**Response:**

```json
{
    "success": true,
    "data": {
        "earned_badges": [
            {
                "id": 1,
                "name": "Pemula",
                "description": "Selesaikan level pertama",
                "icon_url": "/images/badges/pemula.png",
                "requirement_type": "level_complete",
                "requirement_value": 1,
                "earned_at": "2026-02-05T10:00:00Z"
            }
        ],
        "total_earned": 3,
        "total_available": 10
    }
}
```

---

### GET `/badges`

Get semua badges yang tersedia (tanpa status earned).

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Pemula",
            "description": "Selesaikan level pertama",
            "icon_url": "/images/badges/pemula.png",
            "requirement_type": "level_complete",
            "requirement_value": 1
        }
    ]
}
```

---

## 🏆 Leaderboard

### GET `/leaderboard/weekly`

Get leaderboard mingguan.

**Query Parameters:**
| Param | Description |
|-------|-------------|
| week_start_date | Tanggal mulai minggu (Y-m-d) |

**Response:**

```json
{
    "success": true,
    "data": {
        "week_start_date": "2026-02-10",
        "top_10": [
            {
                "rank": 1,
                "user_id": 5,
                "name": "Jane Doe",
                "avatar_url": "/storage/avatars/5.jpg",
                "weekly_xp": 250
            }
        ],
        "current_user_rank": {
            "rank": 15,
            "weekly_xp": 100
        }
    }
}
```

---

### GET `/leaderboard/all-time`

Get leaderboard sepanjang masa.

**Query Parameters:**
| Param | Description |
|-------|-------------|
| limit | Jumlah hasil (default: 10) |

---

## 🔤 Translation

### POST `/translate/latin-to-javanese`

Transliterasi teks Latin ke aksara Jawa.

**Request:**

```json
{
    "text": "hanacaraka"
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "input": "hanacaraka",
        "output": "ꦲꦤꦕꦫꦏ"
    }
}
```

---

### POST `/translate/javanese-to-latin`

Transliterasi aksara Jawa ke Latin.

**Request:**

```json
{
    "text": "ꦲꦤꦕꦫꦏ"
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "input": "ꦲꦤꦕꦫꦏ",
        "output": "hanacaraka"
    }
}
```

---

# 🔧 Admin Endpoints

Endpoint untuk pengguna dengan role `admin`. Semua endpoint memerlukan autentikasi dengan role admin.

---

## 📊 Dashboard

### GET `/admin/dashboard`

Get statistik dashboard admin.

**Response:**

```json
{
    "success": true,
    "data": {
        "total_users": 1250,
        "total_pemain": 1240,
        "total_admin": 10,
        "new_users_today": 15,
        "new_users_this_week": 85,
        "total_levels": 8,
        "total_stages": 135,
        "total_materials": 135,
        "total_quizzes": 135,
        "total_evaluations": 135,
        "top_users": [...],
        "weekly_registrations": [...]
    }
}
```

---

## 👥 User Management

### GET `/admin/users`

List semua user dengan pagination.

**Query Parameters:**
| Param | Description |
|-------|-------------|
| role | Filter by role |
| search | Search nama/email |
| per_page | Items per page |

---

### GET `/admin/users/{id}`

Detail user spesifik.

---

### GET `/admin/users/{userId}/progress`

Progress user spesifik.

---

### GET `/admin/users/{userId}/badges`

Badges user spesifik.

---

## 📚 Level Management

### POST `/admin/levels`

Buat level baru.

**Request:**

```json
{
    "level_number": 3,
    "title": "Level Lanjutan",
    "description": "Materi lanjutan",
    "xp_required": 200,
    "is_active": true
}
```

---

### PUT `/admin/levels/{id}`

Update level.

---

### DELETE `/admin/levels/{id}`

Hapus level.

---

## 📖 Stage Management

### POST `/admin/stages`

Buat stage baru.

**Request:**

```json
{
    "level_id": 1,
    "stage_number": 2,
    "title": "Aksara Na",
    "xp_reward": 15,
    "evaluation_type": "quiz",
    "is_active": true
}
```

| Field           | Rules                          |
| --------------- | ------------------------------ |
| level_id        | required, exists:levels        |
| stage_number    | required, integer, min:1       |
| title           | required, string, max:100      |
| xp_reward       | required, integer, min:0       |
| evaluation_type | required, in:drawing,quiz,both |
| is_active       | boolean                        |

---

### PUT `/admin/stages/{id}`

Update stage.

---

### DELETE `/admin/stages/{id}`

Hapus stage.

---

## 📝 Material Management

### POST `/admin/materials`

Buat materi baru (multipart/form-data).

| Field            | Rules                       |
| ---------------- | --------------------------- |
| stage_id         | required, exists:stages     |
| title            | required, max:255           |
| content_markdown | nullable, string            |
| image            | nullable, image, max:2048KB |
| order_index      | integer, min:1              |

---

### PUT `/admin/materials/{id}`

Update materi.

---

### DELETE `/admin/materials/{id}`

Hapus materi.

---

## 📋 Quiz Management

### POST `/admin/quizzes`

Buat quiz dengan questions.

**Request:**

```json
{
    "stage_id": 1,
    "title": "Quiz Aksara Ha",
    "passing_score": 60,
    "questions": [
        {
            "question_text": "Aksara ini dibaca apa?",
            "option_a": "Ha",
            "option_b": "Na",
            "option_c": "Ca",
            "option_d": "Ra",
            "correct_answer": "a",
            "order_index": 1
        }
    ]
}
```

---

### PUT `/admin/quizzes/{id}`

Update quiz.

---

### DELETE `/admin/quizzes/{id}`

Hapus quiz.

---

## ✏️ Evaluation Management

### POST `/admin/evaluations`

Buat evaluation (drawing challenge).

**Request:**

```json
{
    "stage_id": 1,
    "character_target": "ꦲ",
    "reference_image_url": "/storage/aksara/Ha.png",
    "min_similarity_score": 70
}
```

---

## 📁 File Upload

### POST `/admin/upload/image`

Upload file gambar (multipart/form-data).

| Field | Rules                                  |
| ----- | -------------------------------------- |
| image | required, image, max:2048KB            |
| type  | required, in:material,reference,avatar |

**Response:**

```json
{
    "success": true,
    "data": {
        "url": "/storage/materials/uuid.jpg",
        "filename": "uuid.jpg"
    }
}
```

---

### DELETE `/admin/upload/image`

Hapus file gambar.

**Request:**

```json
{
    "url": "/storage/materials/uuid.jpg",
    "type": "material"
}
```

---

# 📊 Data Models

### User

```json
{
    "id": 1,
    "name": "string",
    "email": "string",
    "role": "pemain|admin",
    "total_xp": 0,
    "current_level": 1,
    "streak_count": 0,
    "daily_goal_xp": 50,
    "avatar_url": "string|null"
}
```

### Level

```json
{
    "id": 1,
    "level_number": 1,
    "title": "string",
    "description": "string|null",
    "xp_required": 0,
    "is_active": true
}
```

### Stage

```json
{
    "id": 1,
    "level_id": 1,
    "stage_number": 1,
    "title": "string",
    "xp_reward": 15,
    "evaluation_type": "drawing|quiz|both",
    "is_active": true
}
```

### Material

```json
{
    "id": 1,
    "stage_id": 1,
    "title": "string",
    "content_markdown": "string|null",
    "image_url": "string|null",
    "order_index": 1
}
```

### Quiz

```json
{
    "id": 1,
    "stage_id": 1,
    "title": "string",
    "passing_score": 60
}
```

### QuizQuestion

```json
{
    "id": 1,
    "quiz_id": 1,
    "question_text": "string",
    "option_a": "string",
    "option_b": "string",
    "option_c": "string",
    "option_d": "string",
    "correct_answer": "a|b|c|d",
    "order_index": 1
}
```

### Evaluation

```json
{
    "id": 1,
    "stage_id": 1,
    "character_target": "ꦲ",
    "reference_image_url": "string",
    "min_similarity_score": 70
}
```

### Badge

```json
{
    "id": 1,
    "name": "string",
    "description": "string",
    "icon_url": "string",
    "requirement_type": "xp_milestone|streak|level_complete|custom",
    "requirement_value": 1
}
```

---

# 🔗 Quick Reference

## 🌐 Public (No Auth)

| Method | Endpoint         | Description  |
| ------ | ---------------- | ------------ |
| GET    | `/health`        | Health check |
| POST   | `/auth/register` | Register     |
| POST   | `/auth/login`    | Login        |

## 🎮 Player Endpoints

### Account & Profile

| Method | Endpoint          | Description       |
| ------ | ----------------- | ----------------- |
| GET    | `/auth/me`        | Current user info |
| POST   | `/auth/logout`    | Logout            |
| GET    | `/profile`        | Profile detail    |
| PUT    | `/profile`        | Update profile    |
| POST   | `/profile/avatar` | Upload avatar     |

### Learning Content

| Method | Endpoint                  | Description      |
| ------ | ------------------------- | ---------------- |
| GET    | `/levels`                 | List levels      |
| GET    | `/levels/{id}`            | Level detail     |
| GET    | `/stages`                 | List stages      |
| GET    | `/stages/{id}`            | Stage detail     |
| GET    | `/stages/{id}/materials`  | Stage materials  |
| GET    | `/stages/{id}/quiz`       | Stage quiz       |
| GET    | `/stages/{id}/evaluation` | Stage evaluation |

### Submit & Complete

| Method | Endpoint                      | Description       |
| ------ | ----------------------------- | ----------------- |
| POST   | `/quizzes/{id}/submit`        | Submit quiz       |
| POST   | `/stages/{id}/submit-drawing` | Submit drawing ⭐ |

### Progress & Stats

| Method | Endpoint                | Description               |
| ------ | ----------------------- | ------------------------- |
| GET    | `/progress`             | User progress summary     |
| GET    | `/progress/levels`      | Progress per level detail |
| GET    | `/progress/levels/{id}` | Progress level spesifik   |
| GET    | `/my-badges`            | User badges               |
| GET    | `/badges`               | All badges                |
| GET    | `/leaderboard/weekly`   | Weekly leaderboard        |
| GET    | `/leaderboard/all-time` | All-time leaderboard      |

### Translation

| Method | Endpoint                       | Description  |
| ------ | ------------------------------ | ------------ |
| POST   | `/translate/latin-to-javanese` | Latin → Jawa |
| POST   | `/translate/javanese-to-latin` | Jawa → Latin |

## 🔧 Admin Endpoints

### Dashboard & Users

| Method | Endpoint                     | Description     |
| ------ | ---------------------------- | --------------- |
| GET    | `/admin/dashboard`           | Dashboard stats |
| GET    | `/admin/users`               | List users      |
| GET    | `/admin/users/{id}`          | User detail     |
| GET    | `/admin/users/{id}/progress` | User progress   |
| GET    | `/admin/users/{id}/badges`   | User badges     |

### Content Management

| Method | Endpoint                | Description       |
| ------ | ----------------------- | ----------------- |
| POST   | `/admin/levels`         | Create level      |
| PUT    | `/admin/levels/{id}`    | Update level      |
| DELETE | `/admin/levels/{id}`    | Delete level      |
| POST   | `/admin/stages`         | Create stage      |
| PUT    | `/admin/stages/{id}`    | Update stage      |
| DELETE | `/admin/stages/{id}`    | Delete stage      |
| POST   | `/admin/materials`      | Create material   |
| PUT    | `/admin/materials/{id}` | Update material   |
| DELETE | `/admin/materials/{id}` | Delete material   |
| POST   | `/admin/quizzes`        | Create quiz       |
| PUT    | `/admin/quizzes/{id}`   | Update quiz       |
| DELETE | `/admin/quizzes/{id}`   | Delete quiz       |
| POST   | `/admin/evaluations`    | Create evaluation |

### File Management

| Method | Endpoint              | Description  |
| ------ | --------------------- | ------------ |
| POST   | `/admin/upload/image` | Upload image |
| DELETE | `/admin/upload/image` | Delete image |

---

## 📝 Changelog

### Version 2.0 (February 2026)

- Reorganized documentation by role (Player vs Admin)
- Added `evaluation_type` field for stages (drawing/quiz/both)
- Updated stage completion logic based on evaluation_type
- Added `stage_completed` field in submission responses
- Simplified data models based on actual database schema

---

## 📞 Support

For API support or questions, please contact the development team.
