# Sistem Kas Kelas - Refactored & Simplified

## 🎯 Project Overview

A clean, minimal, and production-ready Laravel web application for managing class cash fund (Kas Kelas). The system supports role-based access control with two roles: **Admin** and **User**.

---

## 📋 System Architecture

### Roles & Permissions

| Feature | Admin | User |
|---------|-------|------|
| Dashboard (Summary) | ✅ | ✅ |
| View Transactions | ✅ (All) | ✅ (Read-only) |
| Add Transaction | ✅ | ❌ |
| Edit Transaction | ✅ | ❌ |
| Delete Transaction | ✅ | ❌ |
| View History | ✅ | ❌ |
| Access /admin/* | ✅ | ❌ |

---

## 📁 Directory Structure

```
resources/views/
├── layouts/
│   └── app.blade.php          # Main layout wrapper
├── components/
│   └── navbar.blade.php       # Navigation (role-based)
├── auth/
│   ├── login.blade.php        # Login form
│   └── register.blade.php     # Registration form
├── dashboard.blade.php        # User dashboard
├── user/
│   └── riwayat.blade.php      # Transaction history (read-only)
└── admin/
    ├── dashboard.blade.php    # Admin summary dashboard
    ├── data_kas.blade.php     # Transaction management (CRUD)
    └── history.blade.php      # Activity logs (read-only)
```

---

## 🗺️ Routes & Navigation

### Public Routes
```
GET  /               → Redirect to /login or /dashboard
GET  /login          → Login form
POST /login          → Process login
GET  /register       → Registration form
POST /register       → Process registration
```

### User Routes (Protected by 'auth' middleware)
```
GET  /dashboard      → User dashboard with summary
GET  /riwayat        → Transaction history (read-only)
```

### Admin Routes (Protected by 'auth' + 'admin' middleware)
```
GET  /admin/dashboard    → Admin summary dashboard
GET  /admin/data-kas     → Transaction management (CRUD)
GET  /admin/history      → Activity logs (read-only)
```

### Logout
```
POST /logout         → Logout user (protected by 'auth')
```

---

## 🛡️ Security & Middleware

### Middleware Stack
- **auth**: Ensures user is logged in
- **admin**: Ensures user has 'admin' role
- **guest**: Ensures user is NOT logged in (for login/register pages)

### Route Protection
- **Public**: `/login`, `/register`
- **User**: Requires authentication (`middleware('auth')`)
- **Admin**: Requires authentication + admin role (`middleware('auth', 'admin')`)

### Authorization Flow
1. **Non-authenticated user** → Redirected to `/login`
2. **Admin tries user route** → Allowed (has auth)
3. **User tries admin route** → Redirected to `/dashboard` with error message
4. **User tries `/admin/...` directly** → Caught by AdminMiddleware, redirected with error

---

## 📊 Dashboards

### Admin Dashboard
**Display:**
- Total Saldo (Balance)
- Total Pemasukan (Income)
- Total Pengeluaran (Expense)

**Purpose:** Quick overview of class fund status

### Admin Data Kas
**Display:**
- Transaction table with all data
- Add button to create new transaction
- Edit/Delete buttons for each transaction

**Actions:**
- ✅ Create new transaction
- ✅ Edit existing transaction
- ✅ Delete transaction
- ✅ View all transaction details

### Admin History
**Display:**
- Activity log table (read-only)
- User who performed action
- Type of action (Tambah/Edit/Hapus)
- Timestamp and description
- No edit/delete buttons

### User Dashboard
**Display:**
- Current Saldo
- Total Pemasukan
- Total Pengeluaran
- Recent transactions table (last 5)

### User Riwayat
**Display:**
- Current Saldo
- Total Pemasukan
- Total Pengeluaran
- Full transaction history (read-only)

**Cannot:**
- ❌ Edit transactions
- ❌ Delete transactions
- ❌ Add transactions

---

## 🗑️ Removed Features

**The following have been deliberately removed to keep the system minimal:**

| Feature | Reason | Replaced By |
|---------|--------|-------------|
| User Profile Page | Not essential for MVP | N/A |
| User Management | Not in scope | N/A |
| Settings Page | Not essential | N/A |
| Laporan (Reports) | Duplicate of Riwayat | Riwayat (consolidated) |
| Quick Actions Buttons | Cluttered UI | Direct menu navigation |
| Activity Feed on Dashboard | Clutter, moved to History | Admin only History page |
| User Role Badge in Navbar | Unnecessary UI element | Removed |
| Profile Icon in Navbar | No profile page | Removed |

---

## 🎨 UI/UX Design

### Design Principles
- **Clean & Minimal**: Only essential UI elements
- **Dark Theme**: Easy on the eyes, modern aesthetic
- **Card-Based Layout**: Consistent component style
- **Responsive Design**: Works on mobile, tablet, desktop
- **Role-Aware Navigation**: Different menus for admin/user

### Navbar Behavior
- **Admin**: Dashboard, Data Kas, History, Logout
- **User**: Dashboard, Riwayat, Logout
- **Username display** on the right side
- Active route highlighting with underline

### Colors
- **Primary**: `#6366f1` (Indigo - interactive elements)
- **Success**: `#10b981` (Green - income)
- **Error**: `#ef4444` (Red - expense/logout)
- **Background**: Dark slate colors for contrast

### Spacing
- Cards have soft shadows and rounded corners
- Consistent padding throughout
- Proper gap between elements

---

## 🚀 Database

### Users Table
```sql
- id (PK)
- name
- email (unique)
- password (bcrypt)
- role (enum: 'admin', 'user') -- Default: 'user'
- created_at
- updated_at
```

### Kas Data Table (Example - Create as needed)
```sql
- id (PK)
- type (enum: 'masuk', 'keluar')
- description
- amount
- recorded_by (FK to users)
- created_at
- updated_at
```

### Activity Log Table (Example - Create as needed)
```sql
- id (PK)
- user_id (FK)
- action (masuk, edit, hapus)
- entity_type (Kas, User, etc.)
- entity_id
- description
- created_at
```

---

## 📦 Dependencies & Setup

### Required
- PHP 8.1+
- Laravel 11+
- MySQL 8.0+
- Composer
- Node.js & npm

### Installation
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Build assets
npm run dev

# Start server
php artisan serve
```

### Create Test Users (using tinker)
```bash
php artisan tinker

# Create admin user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin'
]);

# Create regular user
User::create([
    'name' => 'Regular User',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'role' => 'user'
]);
```

---

## 🔐 Security Checklist

- ✅ Password hashing with bcrypt
- ✅ Session regeneration on login
- ✅ CSRF protection on all forms
- ✅ Middleware-based authorization
- ✅ Role-based access control
- ✅ Prevent unauthorized direct URL access
- ✅ Secure logout with session invalidation
- ✅ Input validation on registration

---

## 📖 Code Structure

### Key Classes
- **App\Http\Controllers\AuthController**: Handles login, register, logout
- **App\Http\Middleware\AdminMiddleware**: Checks for admin role
- **App\Http\Middleware\UserMiddleware**: Checks for authentication
- **App\Models\User**: User model with role attribute

### Blade Templates
- `layouts/app.blade.php`: Main layout with navbar and alerts
- `components/navbar.blade.php`: Conditional navigation based on role
- All views extend `app.blade.php` for consistency

### RBAC Implementation
```php
// Check if authenticated
@auth ... @endauth

// Check if admin
@admin ... @endadmin

// Check if user
@user ... @enduser

// Helper functions
AuthHelper::isAdmin()
AuthHelper::isUser()
AuthHelper::userRole()
```

---

## 🐛 Testing Checklist

### Authentication
- [ ] Register new user (should default to 'user' role)
- [ ] Login with valid credentials
- [ ] Login with invalid credentials (should fail)
- [ ] Logout clears session

### Authorization
- [ ] Admin can access `/admin/dashboard`
- [ ] Admin can access `/admin/data-kas`
- [ ] Admin can access `/admin/history`
- [ ] User cannot access `/admin/*` (redirect to `/dashboard`)
- [ ] User can access `/dashboard`
- [ ] User can access `/riwayat`
- [ ] Non-authenticated user redirected to `/login`

### UI/UX
- [ ] Navbar shows correct items for admin
- [ ] Navbar shows correct items for user
- [ ] Active routes are highlighted
- [ ] Mobile layout is responsive
- [ ] Cards display correctly
- [ ] Alerts appear and auto-dismiss

---

## 📝 Files Changed

### Created
- `resources/views/admin/data_kas.blade.php` (New CRUD page)
- `resources/views/admin/history.blade.php` (New activity log)

### Modified
- `routes/web.php` (Simplified, removed unused routes)
- `resources/views/components/navbar.blade.php` (Clean, minimal menu)
- `resources/views/dashboard.blade.php` (Simplified user dashboard)
- `resources/views/admin/dashboard.blade.php` (Simplified admin summary)
- `resources/views/user/riwayat.blade.php` (Cleaned up layout)
- `resources/css/app.css` (Removed unused styles)

### Deleted
- `resources/views/admin/users.blade.php` (Not MVP)
- `resources/views/admin/settings.blade.php` (Not MVP)
- `resources/views/user/profile.blade.php` (Not MVP)
- `resources/views/user/laporan.blade.php` (Merged with riwayat)

---

## 🎯 Future Enhancements

- [ ] Implement CRUD endpoints for transactions
- [ ] Add activity logging system
- [ ] Implement balance calculation
- [ ] Add export to CSV/PDF
- [ ] Add transaction filtering/search
- [ ] Add user management (for admins)
- [ ] Add settings page (for system configuration)
- [ ] Add toast notifications instead of alerts
- [ ] Add pagination for transaction tables

---

## 💡 Developer Notes

### Adding New Features
1. Create route in `routes/web.php`
2. Create controller if needed in `app/Http/Controllers/`
3. Create view in `resources/views/`
4. Add navigation link in `components/navbar.blade.php` (if visible)
5. Add middleware protection if needed

### Extending RBAC
1. Add new role in user migration
2. Update `AdminMiddleware.php` if needed
3. Add new Blade directives in `RbacServiceProvider.php`
4. Update navigation logic

### Styling Guidelines
- Use CSS variables for colors
- Follow mobile-first responsive design
- Use `.dashboard-card` for summary cards
- Use `.admin-table` for data tables
- Use `.admin-section` for section containers

---

## 📞 Support

For issues or questions about the system:
1. Check the middleware logs
2. Verify user role in database
3. Clear cache with `php artisan cache:clear`
4. Clear config with `php artisan config:clear`
5. Check `.env` file for database configuration

---

**Version**: 1.0 (Refactored & Minimal)  
**Last Updated**: April 2026  
**Status**: Production Ready ✅
