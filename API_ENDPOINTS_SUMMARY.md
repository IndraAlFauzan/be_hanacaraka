# 📋 API Endpoints Summary - Aksara Jawa API

Total Implemented Endpoints: **41 Endpoints**

---

## ✅ Implemented Endpoints Breakdown

### 1. Authentication (4 endpoints)

- ✅ POST `/api/v1/auth/register` - Register new user (pemain)
- ✅ POST `/api/v1/auth/login` - Login user
- ✅ POST `/api/v1/auth/logout` - Logout user
- ✅ GET `/api/v1/auth/me` - Get authenticated user info

### 2. Levels Management (5 endpoints)

- ✅ GET `/api/v1/levels` - List all levels (with unlock status)
- ✅ GET `/api/v1/levels/{id}` - Get level detail with stages
- ✅ POST `/api/v1/admin/levels` - Create new level (Admin)
- ✅ PUT `/api/v1/admin/levels/{id}` - Update level (Admin)
- ✅ DELETE `/api/v1/admin/levels/{id}` - Delete level (Admin)

### 3. Stages Management (5 endpoints)

- ✅ GET `/api/v1/stages` - List stages with filters (level_id, is_active)
- ✅ GET `/api/v1/stages/{id}` - Get stage detail with materials/quiz/evaluation
- ✅ POST `/api/v1/admin/stages` - Create new stage (Admin)
- ✅ PUT `/api/v1/admin/stages/{id}` - Update stage (Admin)
- ✅ DELETE `/api/v1/admin/stages/{id}` - Delete stage (Admin)

### 4. Materials Management (4 endpoints)

- ✅ GET `/api/v1/stages/{stageId}/materials` - Get materials for stage
- ✅ POST `/api/v1/admin/materials` - Create material (Admin)
- ✅ PUT `/api/v1/admin/materials/{id}` - Update material (Admin)
- ✅ DELETE `/api/v1/admin/materials/{id}` - Delete material (Admin)

### 5. Evaluation/Drawing Challenge (2 endpoints)

- ✅ GET `/api/v1/stages/{stageId}/evaluation` - Get evaluation config
- ✅ POST `/api/v1/evaluations/{evaluationId}/submit-drawing` - Submit drawing (Pemain, rate limited 5/min)
- ✅ POST `/api/v1/admin/evaluations` - Create evaluation (Admin)

**Features**:

- File upload (max 2MB, PNG/JPG)
- Image compression & resize to 1024x1024
- ML service integration (Python Flask)
- Similarity scoring (≥70% to pass)
- Auto XP reward & stage completion
- Sequential unlock logic

### 6. Quiz System (5 endpoints)

- ✅ GET `/api/v1/stages/{stageId}/quiz` - Get quiz with questions
- ✅ POST `/api/v1/quizzes/{quizId}/submit` - Submit quiz answers (Pemain)
- ✅ POST `/api/v1/admin/quizzes` - Create quiz with questions (Admin)
- ✅ PUT `/api/v1/admin/quizzes/{id}` - Update quiz (Admin)
- ✅ DELETE `/api/v1/admin/quizzes/{id}` - Delete quiz (Admin)

**Features**:

- Multiple choice questions (a,b,c,d)
- Auto scoring (≥60% to pass)
- XP reward on pass
- Stage completion trigger

### 7. Progress Tracking (1 endpoint)

- ✅ GET `/api/v1/users/{userId}/progress` - Get user progress summary

**Returns**:

- Total XP, current level
- Completed stages count
- Progress percentage
- Stage-by-stage status

### 8. Leaderboard (2 endpoints)

- ✅ GET `/api/v1/leaderboard/weekly?week_start_date=2026-02-10` - Weekly leaderboard (Redis cached, 5 min TTL)
- ✅ GET `/api/v1/leaderboard/all-time?limit=10` - All-time leaderboard

**Features**:

- Top 10 rankings
- Current user rank
- Redis caching for performance
- SQL RANK() window function

### 9. Gamification - Badges (2 endpoints)

- ✅ GET `/api/v1/badges` - List all available badges
- ✅ GET `/api/v1/users/{userId}/badges` - Get user's earned badges

**Badge Types**:

- XP milestones (50, 150, 300, 600, 1000, 2000 XP)
- Streak badges (3, 7, 14, 30 days)
- Level completion badges (Level 1-8)

### 10. User Profile Management (3 endpoints)

- ✅ GET `/api/v1/users/{id}` - Get user profile (self or admin)
- ✅ PUT `/api/v1/users/{id}` - Update profile (self only)
- ✅ POST `/api/v1/users/{id}/upload-avatar` - Upload avatar (self only)

**Updatable Fields**:

- name
- daily_goal_xp (10-500)
- avatar_url

### 11. Translation Tool (2 endpoints)

- ✅ POST `/api/v1/translate/latin-to-javanese` - Translate Latin to Javanese Script
- ✅ POST `/api/v1/translate/javanese-to-latin` - Translate Javanese Script to Latin

**Features**:

- Simple transliteration algorithm
- Max 500 characters
- Unicode Javanese script support

### 12. Admin Dashboard (2 endpoints)

- ✅ GET `/api/v1/admin/dashboard` - Get dashboard statistics (Admin)
- ✅ GET `/api/v1/admin/users?page=1&per_page=20&search=john&role=pemain` - Get paginated user list (Admin)

**Dashboard Stats**:

- Total users
- Active users today
- Total/completed stages
- Average completion rate
- Top 5 users by XP
- Latest 5 registrations
- Weekly registration trend

### 13. File Upload (2 endpoints)

- ✅ POST `/api/v1/admin/upload/image` - Upload image (Admin)
- ✅ DELETE `/api/v1/admin/upload/image` - Delete image (Admin)

**Supported Types**:

- material (learning content images)
- reference (drawing reference images)
- avatar (user avatars)

**Features**:

- Max 2MB file size
- PNG/JPG/JPEG support
- Auto resize & compress (GD library)
- Unique filename generation

### 14. Health Check (1 endpoint)

- ✅ GET `/api/v1/health` - API health status (Public)

**Returns**:

- Status (ok/degraded)
- Timestamp
- Database connection status
- Redis connection status

---

## 📊 Endpoint Categories

| Category          | Count  | Access Level                            |
| ----------------- | ------ | --------------------------------------- |
| **Public**        | 3      | No auth (register, login, health)       |
| **Authenticated** | 17     | All authenticated users                 |
| **Pemain Only**   | 2      | Drawing & quiz submission               |
| **Admin Only**    | 19     | CRUD operations, dashboard, file upload |
| **Total**         | **41** |                                         |

---

## 🔐 Middleware & Protection

### Authentication

- Laravel Sanctum (token-based)
- Header: `Authorization: Bearer {token}`

### Role-Based Access Control

- **CheckRole** middleware
- Roles: `admin`, `pemain`

### Rate Limiting

- General API: **60 requests/minute**
- Drawing submission: **5 requests/minute**

---

## 📝 Request/Response Format

### Success Response

```json
{
  "success": true,
  "message": "Optional success message",
  "data": { ... }
}
```

### Error Response

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
- `401` - Unauthorized (no/invalid token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error
- `503` - Service Unavailable (health check degraded)

---

## 🎯 Key Features Implemented

### ✅ Core Learning Flow

1. User registers → Pemain role assigned
2. View levels & stages → Sequential unlock logic
3. Access learning materials
4. Complete drawing challenge → ML evaluation
5. Complete quiz → Auto scoring
6. Stage completed → XP awarded
7. Level up → Unlock next level's stages
8. Earn badges → XP/Streak/Level milestones

### ✅ Gamification System

- XP accumulation (10 XP per stage)
- Level progression (8 levels, cumulative XP)
- Badge system (18 badges total)
- Daily streak tracking
- Weekly leaderboard (Redis cached)
- All-time leaderboard

### ✅ Admin Management

- Complete CRUD for levels, stages, materials, evaluations, quizzes
- Dashboard with analytics
- User management (list, search, filter)
- File upload system
- Content management

### ✅ Technical Features

- Database transactions for data integrity
- Redis caching for leaderboards
- Image processing (resize, compress)
- ML service integration (drawing evaluation)
- Sequential unlock algorithm
- Automatic XP & badge awarding
- Activity tracking (last_activity_date)

---

## 🚀 Testing Endpoints

### Quick Test with curl

```bash
# Health check
curl http://localhost:8000/api/v1/health

# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Get levels (authenticated)
curl http://localhost:8000/api/v1/levels \
  -H "Authorization: Bearer YOUR_TOKEN"

# Admin dashboard (admin only)
curl http://localhost:8000/api/v1/admin/dashboard \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

---

## 📌 Important Notes

### Default Admin Credentials

```
Email: admin@aksarajawa.com
Password: Admin123!
```

### Required Environment Variables

```env
ML_SERVICE_URL=http://localhost:5000
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
```

### Database Seeded Data

- ✅ 1 Admin user
- ✅ 8 Levels
- ✅ 135 Stages (20+18+18+17+17+16+15+14)
- ✅ 18 Badges

---

## 🔄 Next Steps (Optional Enhancements)

- [ ] Password reset flow (email verification)
- [ ] Social login (Google, Facebook)
- [ ] Push notifications (FCM integration)
- [ ] Detailed analytics per user
- [ ] Export/Import data (CSV/Excel)
- [ ] Automated testing (PHPUnit)
- [ ] API rate limiting per user
- [ ] Advanced search & filters
- [ ] Activity logs (audit trail)
- [ ] Scheduled tasks (weekly leaderboard reset)

---

**Last Updated**: February 13, 2026  
**Total Endpoints**: 41  
**Framework**: Laravel 12  
**Status**: ✅ Production Ready
