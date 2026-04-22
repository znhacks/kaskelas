# 🚀 Sistem Kas Kelas - Quick Start Guide

## ⚡ Installation (5 minutes)

```bash
# 1. Navigate to project
cd c:\Jordi\Kas

# 2. Install dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Setup database in .env
# DB_DATABASE=kas_kelas
# DB_USERNAME=root
# DB_PASSWORD=(your password)

# 5. Run migrations
php artisan migrate

# 6. Build assets
npm run dev

# 7. Start server
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 Test Credentials

### Create Admin User (via Tinker):
```bash
php artisan tinker

# In tinker, run:
$user = User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);

# Regular user for testing:
$user = User::create([
    'name' => 'Regular User',
    'email' => 'user@example.com',
    'password' => bcrypt('password123'),
    'role' => 'user'
]);

exit
```

---

## 📂 Folder Structure

```
app/Http/
  ├── Controllers/AuthController.php
  └── Middleware/CheckAdmin.php

resources/
  ├── css/app.css (ALL styling)
  └── views/
      ├── layouts/app.blade.php
      ├── auth/
      │   ├── login.blade.php
      │   └── register.blade.php
      ├── components/navbar.blade.php
      ├── dashboard.blade.php
      └── admin/
          ├── dashboard.blade.php
          ├── users.blade.php
          └── kas_data.blade.php

routes/web.php (All routes)
```

---

## 🎯 Available Routes

| Route | Method | Auth | Role | Purpose |
|-------|--------|------|------|---------|
| / | GET | - | - | Redirect to login or dashboard |
| /register | GET | - | - | Show registration form |
| /register | POST | - | - | Handle registration |
| /login | GET | - | - | Show login form |
| /login | POST | - | - | Handle login |
| /logout | POST | ✓ | - | Handle logout |
| /dashboard | GET | ✓ | user | User dashboard |
| /admin/dashboard | GET | ✓ | admin | Admin dashboard |
| /admin/users | GET | ✓ | admin | User management |
| /admin/kas-data | GET | ✓ | admin | Kas data/transactions |

---

## 🎨 UI Features

✓ Modern dark theme  
✓ Gradient backgrounds  
✓ Card-based layouts  
✓ Smooth animations  
✓ Responsive design  
✓ Form validation  
✓ Loading states  
✓ Auto-dismiss alerts  
✓ Focus glow effects  
✓ Hover animations  

---

## 🔐 Authentication Flow

```
Unauth User
    ↓
Login/Register
    ↓
Auth Check
    ↓
Admin? → Yes → /admin/dashboard
    ↓ No
→ /dashboard (user)

Try admin route as user → Redirect to /dashboard + error
```

---

## 💾 Database Setup

### Users Table (after migration):
- id
- name
- email
- password (hashed)
- **role** (enum: 'user' or 'admin' - NEW)
- email_verified_at
- remember_token
- created_at
- updated_at

---

## 🛠️ What's Ready to Build

1. **Transactions Management**
   - Create Transaction model & migration
   - Add TransactionController
   - Add routes for CRUD operations

2. **Reports**
   - Extend admin/kas_data.blade.php
   - Add calculation logic
   - Create report generation

3. **User Classes**
   - Link users to classes
   - Add class management
   - Filter transactions by class

4. **Dashboard Stats**
   - Calculate total kas
   - Total pemasukan/pengeluaran
   - Display in cards

---

## 🐛 Common Issues

| Issue | Solution |
|-------|----------|
| CSS not loading | Run `npm run dev` |
| Login fails | Check `.env` database config |
| Migrations error | Run `php artisan migrate:fresh` |
| Role check not working | Verify middleware in Kernel.php |
| 404 on routes | Clear route cache: `php artisan route:clear` |

---

## 📝 Current Status: ✅ COMPLETE

- ✅ Auth system (login/register/logout)
- ✅ Role-based access control
- ✅ Modern responsive UI
- ✅ All views created
- ✅ CSS styling complete
- ✅ Routes configured
- ✅ Middleware setup

**Ready for:** Database features, business logic, and extensions

---

## 🚀 Next Commands

After running migrations:

```bash
# Start watching for changes
npm run dev

# In another terminal:
php artisan serve

# Access application:
# http://localhost:8000
```

---

**Happy Coding! 🎉**
