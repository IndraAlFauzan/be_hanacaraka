# 📚 Hanacaraka API Documentation

**Version:** 1.0  
**Base URL:** `http://localhost:8000/api/v1`  
**Last Updated:** February 2026

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Response Format](#response-format)
4. [Error Handling](#error-handling)
5. [Rate Limiting](#rate-limiting)
6. [Endpoints](#endpoints)
    - [Health Check](#health-check)
    - [Authentication](#authentication-endpoints)
    - [Users](#users)
    - [Levels](#levels)
    - [Stages](#stages)
    - [Materials](#materials)
    - [Quizzes](#quizzes)
    - [Evaluations](#evaluations)
    - [Challenges](#challenges)
    - [Progress](#progress)
    - [Leaderboard](#leaderboard)
    - [Badges](#badges)
    - [Translation](#translation)
    - [Admin Dashboard](#admin-dashboard)
    - [File Upload](#file-upload)

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

---

## 🔐 Authentication

API ini menggunakan **Laravel Sanctum** untuk autentikasi. Token dikirimkan melalui header `Authorization` dengan format `Bearer {token}`.

### Request Header

```http
Authorization: Bearer {your_access_token}
Content-Type: application/json
Accept: application/json
```

### Roles

| Role     | Description                      |
| -------- | -------------------------------- |
| `pemain` | User biasa (player)              |
| `admin`  | Administrator dengan akses penuh |

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

### Pagination Response

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [ ... ],
        "per_page": 20,
        "total": 100,
        "last_page": 5
    }
}
```

---

## ⚠️ Error Handling

### HTTP Status Codes

| Code | Description                              |
| ---- | ---------------------------------------- |
| 200  | OK - Request berhasil                    |
| 201  | Created - Resource berhasil dibuat       |
| 400  | Bad Request - Request tidak valid        |
| 401  | Unauthorized - Token tidak valid/expired |
| 403  | Forbidden - Tidak memiliki akses         |
| 404  | Not Found - Resource tidak ditemukan     |
| 422  | Unprocessable Entity - Validasi gagal    |
| 429  | Too Many Requests - Rate limit exceeded  |
| 500  | Internal Server Error                    |
| 503  | Service Unavailable                      |

### Validation Error Response

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

---

## ⏱️ Rate Limiting

| Endpoint           | Limit              |
| ------------------ | ------------------ |
| Drawing submission | 10 requests/minute |
| General API        | 60 requests/minute |

---

## 🔌 Endpoints

---

### Health Check

#### GET `/health`

Cek status kesehatan API.

**Auth Required:** ❌ No

**Response:**

```json
{
    "status": "ok",
    "timestamp": "2026-02-14T10:00:00+07:00",
    "database": "connected",
    "cache": "working",
    "cache_driver": "database"
}
```

**Status Values:**

- `ok` - Semua service berjalan normal
- `degraded` - Beberapa service tidak tersedia

---

### Authentication Endpoints

#### POST `/auth/register`

Mendaftarkan user baru sebagai pemain.

**Auth Required:** ❌ No

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| name | required, string, max:255 |
| email | required, email, unique:users |
| password | required, min:6, confirmed |

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
            "current_level": 1,
            "streak_count": 0,
            "avatar_url": null
        },
        "token": "1|abc123..."
    }
}
```

---

#### POST `/auth/login`

Login user.

**Auth Required:** ❌ No

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| email | required, email |
| password | required, string |

**Response:**

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "pemain",
            "total_xp": 500,
            "current_level": 3
        },
        "token": "2|xyz789..."
    }
}
```

**Error Response (401):**

```json
{
    "success": false,
    "message": "Email atau password salah"
}
```

---

#### POST `/auth/logout`

Logout user.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

#### GET `/auth/me`

Get authenticated user info.

**Auth Required:** ✅ Yes

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
        "last_activity_date": "2026-02-14",
        "daily_goal_xp": 50,
        "avatar_url": "/storage/avatars/avatar_1.jpg",
        "badges": [...],
        "badges_count": 3,
        "completed_stages_count": 10
    }
}
```

---

### Users / Profile

Endpoint user sekarang menggunakan token untuk identifikasi, tidak perlu ID.

#### GET `/profile`

Get current authenticated user profile.

**Auth Required:** ✅ Yes

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
        "last_activity_date": "2026-02-14",
        "daily_goal_xp": 50,
        "avatar_url": "/storage/avatars/avatar_1.jpg",
        "badges": [
            {
                "id": 1,
                "name": "Pemula",
                "description": "Selesaikan level pertama",
                "icon_url": "/images/badges/pemula.png"
            }
        ]
    }
}
```

---

#### PUT `/profile`

Update current authenticated user profile.

**Auth Required:** ✅ Yes

**Request Body:**

```json
{
    "name": "John Updated",
    "daily_goal_xp": 100
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| name | sometimes, string, max:255 |
| daily_goal_xp | sometimes, integer, min:10, max:500 |

**Response:**

```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Updated",
        "daily_goal_xp": 100
    }
}
```

---

#### POST `/profile/avatar`

Upload avatar for current authenticated user.

**Auth Required:** ✅ Yes  
**Content-Type:** `multipart/form-data`

**Request Body:**
| Field | Type | Rules |
|-------|------|-------|
| avatar | file | required, image, mimes:jpeg,jpg,png, max:2048 |

**Response:**

```json
{
    "success": true,
    "message": "Avatar uploaded successfully",
    "data": {
        "avatar_url": "/storage/avatars/avatar_1_uuid.jpg"
    }
}
```

---

### Levels

#### GET `/levels`

List all levels with unlock status.

**Auth Required:** ✅ Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| is_active | boolean | Filter by active status |

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "level_number": 1,
            "title": "Aksara Dasar",
            "description": "Belajar aksara Jawa dasar",
            "xp_required": 0,
            "is_active": true,
            "is_unlocked": true,
            "total_stages": 5
        },
        {
            "id": 2,
            "level_number": 2,
            "title": "Sandhangan",
            "description": "Belajar sandhangan aksara Jawa",
            "xp_required": 100,
            "is_active": true,
            "is_unlocked": false,
            "total_stages": 4
        }
    ]
}
```

---

#### GET `/levels/{id}`

Get level detail with stages.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "level_number": 1,
        "title": "Aksara Dasar",
        "description": "Belajar aksara Jawa dasar",
        "xp_required": 0,
        "stages": [
            {
                "id": 1,
                "title": "Ha Na Ca Ra Ka",
                "stage_number": 1
            }
        ]
    }
}
```

---

#### POST `/admin/levels` (Admin)

Create new level.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

**Request Body:**

```json
{
    "level_number": 3,
    "title": "Level Lanjutan",
    "description": "Materi lanjutan",
    "xp_required": 200,
    "is_active": true
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| level_number | required, integer, unique |
| title | required, string, max:100 |
| description | nullable, string |
| xp_required | required, integer, min:0 |
| is_active | boolean |

**Response (201):**

```json
{
    "success": true,
    "message": "Level created successfully",
    "data": { ... }
}
```

---

#### PUT `/admin/levels/{id}` (Admin)

Update level.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

---

#### DELETE `/admin/levels/{id}` (Admin)

Delete level.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

**Response:**

```json
{
    "success": true,
    "message": "Level deleted successfully"
}
```

---

### Stages

#### GET `/stages`

List all stages with progress status.

**Auth Required:** ✅ Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| level_id | integer | Filter by level |
| is_active | boolean | Filter by active status |

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "level_id": 1,
            "stage_number": 1,
            "title": "Ha Na Ca Ra Ka",
            "xp_reward": 20,
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

**Status Values:**

- `locked` - Stage belum terbuka
- `in_progress` - Sedang dikerjakan
- `completed` - Sudah selesai

---

#### GET `/stages/{id}`

Get stage detail with all content.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "level_id": 1,
        "title": "Ha Na Ca Ra Ka",
        "description": "Belajar 5 aksara pertama",
        "stage_number": 1,
        "level": {
            "id": 1,
            "title": "Aksara Dasar"
        },
        "materials": [...],
        "quizzes": [...],
        "evaluations": [...]
    }
}
```

---

#### POST `/admin/stages` (Admin)

Create new stage.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

**Request Body:**

```json
{
    "level_id": 1,
    "stage_number": 2,
    "title": "Da Ta Sa Wa La",
    "description": "5 aksara berikutnya",
    "xp_reward": 25,
    "is_active": true
}
```

---

#### PUT `/admin/stages/{id}` (Admin)

Update stage.

---

#### DELETE `/admin/stages/{id}` (Admin)

Delete stage.

---

### Materials

#### GET `/stages/{stageId}/materials`

Get all materials for a stage.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "stage_id": 1,
            "title": "Pengenalan Aksara Ha",
            "content_text": "Aksara Ha adalah...",
            "content_markdown": "# Aksara Ha\n\nAksara Ha adalah...",
            "image_url": "/storage/materials/ha.png",
            "order_index": 1
        }
    ]
}
```

---

#### POST `/admin/materials` (Admin)

Create new material.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`  
**Content-Type:** `multipart/form-data`

**Request Body:**
| Field | Type | Rules |
|-------|------|-------|
| stage_id | integer | required, exists:stages |
| title | string | required, max:255 |
| content_text | string | nullable |
| content_markdown | string | nullable |
| image | file | nullable, image, mimes:jpeg,jpg,png, max:2048 |
| order_index | integer | min:1 |

---

#### PUT `/admin/materials/{id}` (Admin)

Update material.

---

#### DELETE `/admin/materials/{id}` (Admin)

Delete material.

---

### Quizzes

#### GET `/stages/{stageId}/quiz`

Get quiz for a stage.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "stage_id": 1,
        "title": "Quiz Aksara Ha Na Ca Ra Ka",
        "passing_score": 70,
        "questions": [
            {
                "id": 1,
                "question_text": "Aksara berikut adalah?",
                "question_type": "multiple_choice",
                "image_url": "/storage/quiz/q1.png",
                "choices": [
                    { "id": "a", "text": "Ha" },
                    { "id": "b", "text": "Na" },
                    { "id": "c", "text": "Ca" },
                    { "id": "d", "text": "Ra" }
                ],
                "order_index": 1
            }
        ]
    }
}
```

**Question Types:**

- `multiple_choice` - Pilihan ganda
- `true_false` - Benar/Salah
- `fill_blank` - Isian singkat
- `matching` - Mencocokkan

---

#### POST `/quizzes/{quizId}/submit`

Submit quiz answers.

**Auth Required:** ✅ Yes  
**Role Required:** `pemain`

**Request Body:**

```json
{
    "answers": [
        { "question_id": 1, "answer": "a" },
        { "question_id": 2, "answer": "true" },
        { "question_id": 3, "answer": "ka" }
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
        "xp_earned": 20
    }
}
```

---

#### POST `/admin/quizzes` (Admin)

Create quiz with questions.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`  
**Content-Type:** `multipart/form-data`

**Request Body:**

```json
{
    "stage_id": 1,
    "title": "Quiz Level 1",
    "passing_score": 70,
    "questions": [
        {
            "question_text": "Pilih aksara Ha",
            "question_type": "multiple_choice",
            "choices": ["Ha", "Na", "Ca", "Ra"],
            "correct_answer": "Ha",
            "order_index": 1
        }
    ]
}
```

---

#### PUT `/admin/quizzes/{id}` (Admin)

Update quiz.

---

#### DELETE `/admin/quizzes/{id}` (Admin)

Delete quiz.

---

### Evaluations

#### GET `/stages/{stageId}/evaluation`

Get evaluation for a stage (drawing challenge).

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": {
        "evaluation": {
            "id": 1,
            "stage_id": 1,
            "title": "Gambar Aksara Ha",
            "description": "Gambar aksara Ha menggunakan jari",
            "reference_image_url": "/storage/references/ha.png",
            "min_similarity_score": 70
        },
        "user_attempts": 3,
        "user_best_score": 85.5
    }
}
```

---

#### POST `/admin/evaluations` (Admin)

Create evaluation.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

**Request Body:**

```json
{
    "stage_id": 1,
    "title": "Gambar Aksara Ha",
    "description": "Gambar aksara Ha dengan benar",
    "reference_image_url": "/storage/references/ha.png",
    "min_similarity_score": 70
}
```

---

### Challenges

#### POST `/evaluations/{evaluationId}/submit-drawing`

Submit drawing for evaluation.

**Auth Required:** ✅ Yes  
**Role Required:** `pemain`  
**Content-Type:** `multipart/form-data`  
**Rate Limit:** 10 requests/minute

**Request Body:**
| Field | Type | Rules |
|-------|------|-------|
| drawing_image | file | required, image, mimes:jpeg,jpg,png, max:2048 |

**Response:**

```json
{
    "success": true,
    "data": {
        "result_id": 456,
        "similarity_score": 85.5,
        "is_passed": true,
        "xp_earned": 25,
        "level_up": false,
        "new_badges": [
            {
                "id": 5,
                "name": "Seniman Aksara",
                "description": "Dapatkan skor 85+ dalam challenge"
            }
        ],
        "next_stage_unlocked": 2
    }
}
```

---

### Progress

#### GET `/progress`

Get current authenticated user's progress summary.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": {
        "total_completed_stages": 10,
        "total_stages": 25,
        "completion_percentage": 40,
        "total_xp": 500,
        "current_streak": 5,
        "stages": [
            {
                "stage_id": 1,
                "stage_title": "Ha Na Ca Ra Ka",
                "level_id": 1,
                "status": "completed",
                "completed_at": "2026-02-10T10:00:00Z"
            }
        ]
    }
}
```

---

### Leaderboard

#### GET `/leaderboard/weekly`

Get weekly leaderboard.

**Auth Required:** ✅ Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| week_start_date | date (Y-m-d) | Start date of week (default: current week) |

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

#### GET `/leaderboard/all-time`

Get all-time leaderboard.

**Auth Required:** ✅ Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| limit | integer | Number of results (default: 10) |

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "rank": 1,
            "user_id": 3,
            "name": "Top Player",
            "avatar_url": "/storage/avatars/3.jpg",
            "total_xp": 5000,
            "current_level": 10
        }
    ]
}
```

---

### Badges

#### GET `/badges`

Get all available badges.

**Auth Required:** ✅ Yes

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
            "requirement_value": 1,
            "xp_bonus": 50,
            "is_active": true
        }
    ]
}
```

---

#### GET `/my-badges`

Get current authenticated user's earned badges.

**Auth Required:** ✅ Yes

**Response:**

```json
{
    "success": true,
    "data": {
        "earned_badges": [
            {
                "id": 1,
                "name": "Pemula",
                "earned_at": "2026-02-05T10:00:00Z"
            }
        ],
        "total_earned": 3,
        "total_available": 10
    }
}
```

---

### Translation

#### POST `/translate/latin-to-javanese`

Translate Latin text to Javanese script.

**Auth Required:** ✅ Yes

**Request Body:**

```json
{
    "text": "hanacaraka"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| text | required, string, max:1000 |

**Response:**

```json
{
    "success": true,
    "data": {
        "input": "hanacaraka",
        "output": "ꦲꦤꦕꦫꦏ",
        "output_format": "javanese_script"
    }
}
```

---

#### POST `/translate/javanese-to-latin`

Translate Javanese script to Latin text.

**Auth Required:** ✅ Yes

**Request Body:**

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
        "output": "hanacaraka",
        "output_format": "latin"
    }
}
```

---

### Admin Dashboard

#### GET `/admin/dashboard`

Get admin dashboard statistics.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

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
        "total_levels": 5,
        "total_stages": 25,
        "total_materials": 100,
        "total_quizzes": 25,
        "total_evaluations": 25,
        "top_users": [...],
        "weekly_registrations": [
            {"date": "2026-02-08", "count": 12},
            {"date": "2026-02-09", "count": 18}
        ]
    }
}
```

---

#### GET `/admin/users`

Get paginated user list (admin).

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| role | string | Filter by role (pemain/admin) |
| search | string | Search by name/email |
| per_page | integer | Items per page (default: 20) |

**Response:**

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "role": "pemain",
                "total_xp": 500,
                "created_at": "2026-01-15T10:00:00Z"
            }
        ],
        "per_page": 20,
        "total": 1250
    }
}
```

---

#### GET `/admin/users/{id}`

Get specific user detail by ID.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

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
        "badges": [...]
    }
}
```

---

#### GET `/admin/users/{userId}/progress`

Get specific user's progress by ID.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

---

#### GET `/admin/users/{userId}/badges`

Get specific user's earned badges by ID.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

---

### File Upload

#### POST `/admin/upload/image`

Upload image file.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`  
**Content-Type:** `multipart/form-data`

**Request Body:**
| Field | Type | Rules |
|-------|------|-------|
| image | file | required, image, mimes:jpeg,jpg,png, max:2048 |
| type | string | required, in:material,reference,avatar |

**Response:**

```json
{
    "success": true,
    "message": "Image uploaded successfully",
    "data": {
        "url": "/storage/materials/material_uuid.jpg",
        "filename": "material_uuid.jpg",
        "type": "material"
    }
}
```

---

#### DELETE `/admin/upload/image`

Delete uploaded image.

**Auth Required:** ✅ Yes  
**Role Required:** `admin`

**Request Body:**

```json
{
    "url": "/storage/materials/material_uuid.jpg",
    "type": "material"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Image deleted successfully"
}
```

---

## 📊 Data Models

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
    "last_activity_date": "date",
    "daily_goal_xp": 50,
    "avatar_url": "string|null",
    "created_at": "datetime",
    "updated_at": "datetime"
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
    "description": "string|null",
    "xp_reward": 20,
    "is_active": true
}
```

### Material

```json
{
    "id": 1,
    "stage_id": 1,
    "title": "string",
    "content_text": "string|null",
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
    "passing_score": 70
}
```

### QuizQuestion

```json
{
    "id": 1,
    "quiz_id": 1,
    "question_text": "string",
    "question_type": "multiple_choice|true_false|fill_blank|matching",
    "choices": ["array"],
    "correct_answer": "string",
    "image_url": "string|null",
    "order_index": 1
}
```

### Evaluation

```json
{
    "id": 1,
    "stage_id": 1,
    "title": "string",
    "description": "string|null",
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
    "requirement_type": "string",
    "requirement_value": 1,
    "xp_bonus": 0,
    "is_active": true
}
```

---

## 🔗 Quick Reference

### Public Endpoints (No Auth)

| Method | Endpoint         | Description  |
| ------ | ---------------- | ------------ |
| GET    | `/health`        | Health check |
| POST   | `/auth/register` | Register     |
| POST   | `/auth/login`    | Login        |

### Authenticated Endpoints (Player)

| Method | Endpoint                           | Description           |
| ------ | ---------------------------------- | --------------------- |
| POST   | `/auth/logout`                     | Logout                |
| GET    | `/auth/me`                         | Current user info     |
| GET    | `/profile`                         | User profile detail   |
| PUT    | `/profile`                         | Update profile        |
| POST   | `/profile/avatar`                  | Upload avatar         |
| GET    | `/progress`                        | User progress         |
| GET    | `/my-badges`                       | User earned badges    |
| GET    | `/levels`                          | List levels           |
| GET    | `/levels/{id}`                     | Level detail          |
| GET    | `/stages`                          | List stages           |
| GET    | `/stages/{id}`                     | Stage detail          |
| GET    | `/stages/{id}/materials`           | Stage materials       |
| GET    | `/stages/{id}/quiz`                | Stage quiz            |
| GET    | `/stages/{id}/evaluation`          | Stage evaluation      |
| POST   | `/quizzes/{id}/submit`             | Submit quiz           |
| POST   | `/evaluations/{id}/submit-drawing` | Submit drawing        |
| GET    | `/badges`                          | All badges            |
| GET    | `/leaderboard/weekly`              | Weekly leaderboard    |
| GET    | `/leaderboard/all-time`            | All-time leaderboard  |
| POST   | `/translate/latin-to-javanese`     | Translate to Javanese |
| POST   | `/translate/javanese-to-latin`     | Translate to Latin    |

### Admin Endpoints

| Method | Endpoint                     | Description       |
| ------ | ---------------------------- | ----------------- |
| GET    | `/admin/dashboard`           | Dashboard stats   |
| GET    | `/admin/users`               | User list         |
| GET    | `/admin/users/{id}`          | User detail       |
| GET    | `/admin/users/{id}/progress` | User progress     |
| GET    | `/admin/users/{id}/badges`   | User badges       |
| POST   | `/admin/levels`              | Create level      |
| PUT    | `/admin/levels/{id}`         | Update level      |
| DELETE | `/admin/levels/{id}`         | Delete level      |
| POST   | `/admin/stages`              | Create stage      |
| PUT    | `/admin/stages/{id}`         | Update stage      |
| DELETE | `/admin/stages/{id}`         | Delete stage      |
| POST   | `/admin/materials`           | Create material   |
| PUT    | `/admin/materials/{id}`      | Update material   |
| DELETE | `/admin/materials/{id}`      | Delete material   |
| POST   | `/admin/quizzes`             | Create quiz       |
| PUT    | `/admin/quizzes/{id}`        | Update quiz       |
| DELETE | `/admin/quizzes/{id}`        | Delete quiz       |
| POST   | `/admin/evaluations`         | Create evaluation |
| POST   | `/admin/upload/image`        | Upload image      |
| DELETE | `/admin/upload/image`        | Delete image      |

---

## 📝 Changelog

### Version 1.0 (February 2026)

- Initial API release
- 44 endpoints total
- Authentication with Laravel Sanctum
- User endpoints now use token instead of ID parameter
- Full CRUD for levels, stages, materials, quizzes, evaluations
- Drawing challenge with ML evaluation
- Gamification (XP, badges, leaderboard)
- Latin ↔ Javanese translation

---

## 📞 Contact

For API support or questions, please contact the development team.
