# Role-Based Access Control (RBAC) - Complete Guide

## 📋 Overview

This document provides comprehensive information about the Role-Based Access Control (RBAC) system implemented in the Sistem Kas Kelas application.

---

## 🔐 User Roles

### Admin Role
- **Access Level**: Full system access
- **Use Case**: System administrators, managers
- **Permissions**:
  - Manage all users
  - Manage kas data and transactions
  - View reports for all classes
  - Access system settings
  - Change system configurations
- **Routes**:
  - `/admin/dashboard` - Admin dashboard
  - `/admin/users` - User management
  - `/admin/kas-data` - Kas data management
  - `/admin/settings` - System settings

### User Role (Default)
- **Access Level**: Limited to personal dashboard
- **Use Case**: Students, class members
- **Permissions**:
  - View personal dashboard
  - View transaction history (Riwayat)
  - View personal reports (Laporan)
  - View and edit personal profile
- **Routes**:
  - `/dashboard` - User dashboard
  - `/riwayat` - Transaction history
  - `/laporan` - Reports
  - `/profile` - User profile

---

## 🛡️ Middleware

### AdminMiddleware
**File**: `app/Http/Middleware/AdminMiddleware.php`

Ensures only admin users can access protected routes.

```php
// Checks:
// 1. User is authenticated
// 2. User role is 'admin'
// Redirects to dashboard with error if not admin
```

**Usage in routes**:
```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```

### UserMiddleware
**File**: `app/Http/Middleware/UserMiddleware.php`

Basic authentication check for user routes.

```php
// Checks:
// 1. User is authenticated (any role allowed)
```

**Usage in routes**:
```php
Route::middleware(['auth'])->group(function () {
    // User routes
});
```

---

## 🔗 Helper Functions

### Available Functions

#### 1. `isAdmin()`
Check if current user is admin.

```blade
@if(isAdmin())
    <!-- Show admin content -->
@endif
```

#### 2. `isUser()`
Check if current user is regular user.

```blade
@if(isUser())
    <!-- Show user content -->
@endif
```

#### 3. `hasRole($role)`
Check if user has specific role.

```blade
@if(hasRole('admin'))
    <!-- Show admin content -->
@endif
```

#### 4. `hasAnyRole($roles)`
Check if user has any of the given roles.

```blade
@if(hasAnyRole(['admin', 'moderator']))
    <!-- Show content for admin or moderator -->
@endif
```

#### 5. `userRole()`
Get current user's role.

```blade
<p>Your role: {{ userRole() }}</p>
```

#### 6. `userCan($action)`
Check if user can perform an action.

```blade
@if(userCan('manage-users'))
    <!-- Show user management -->
@endif
```

---

## 📨 Blade Directives

### @admin ... @endadmin
Show content only to admin users.

```blade
@admin
    <a href="/admin/dashboard">Admin Dashboard</a>
    <a href="/admin/users">Manage Users</a>
@endadmin
```

### @notAdmin ... @endnotAdmin
Show content to non-admin users.

```blade
@notAdmin
    <a href="/dashboard">Dashboard</a>
@endnotAdmin
```

### @user ... @enduser
Show content only to user role.

```blade
@user
    <a href="/dashboard">Dashboard</a>
    <a href="/riwayat">History</a>
@enduser
```

### @notUser ... @endnotUser
Show content to non-user roles.

```blade
@notUser
    <a href="/admin/dashboard">Admin Panel</a>
@endnotUser
```

### @role('admin') ... @endrole
Show content for specific role.

```blade
@role('admin')
    <!-- Admin content -->
@endrole
```

### @anyRole(['admin', 'user']) ... @endanyRole
Show content if user has any of the roles.

```blade
@anyRole(['admin', 'manager'])
    <a href="/reports">View Reports</a>
@endanyRole
```

### @can('action') ... @endcan
Show content if user can perform action.

```blade
@can('manage-users')
    <a href="/users/manage">Manage Users</a>
@endcan
```

---

## 📂 File Structure

```
app/
├── Helpers/
│   └── AuthHelper.php ................ RBAC Helper functions
├── Http/
│   ├── Middleware/
│   │   ├── AdminMiddleware.php ........ Admin access check
│   │   └── UserMiddleware.php ........ User authentication
│   └── Controllers/
│       └── AuthController.php ........ Login/Register logic
├── Providers/
│   └── RbacServiceProvider.php ........ Registers helpers & directives

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php ............. Main layout
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── components/
│   │   └── navbar.blade.php .......... Role-based navbar
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── users.blade.php
│   │   ├── kas_data.blade.php
│   │   └── settings.blade.php ........ Admin settings
│   ├── user/
│   │   ├── profile.blade.php
│   │   ├── riwayat.blade.php
│   │   └── laporan.blade.php
│   └── dashboard.blade.php

config/
└── app.php ........................... Registered RbacServiceProvider

routes/
└── web.php ........................... Protected routes with middleware
```

---

## 🔒 Route Structure

### Public Routes
```php
// No authentication required
GET  /register
POST /register
GET  /login
POST /login
```

### User Routes (authenticated, any role)
```php
// All authenticated users can access
GET /dashboard
GET /riwayat
GET /laporan
GET /profile
```

### Admin Routes (authenticated + admin role)
```php
// Only admin users can access
GET /admin/dashboard
GET /admin/users
GET /admin/kas-data
GET /admin/settings
```

---

## 🔄 Login Flow

```
User Input Credentials
        ↓
AuthController::login() validates
        ↓
DB check for user & password
        ↓
Credentials match?
        ├─ NO → Redirect to login with error
        │
        └─ YES → Check role
             ├─ role = 'admin' → Redirect to /admin/dashboard
             └─ role = 'user'  → Redirect to /dashboard
```

---

## 🚫 Security Features

### 1. Authentication Check
- All protected routes require `auth` middleware
- Users redirected to login if not authenticated

### 2. Role Verification
- Admin routes use `admin` middleware
- Checks user role in database
- Non-admin users redirected with error message

### 3. Session Management
- Sessions regenerated on login (prevents fixation)
- Sessions invalidated on logout
- CSRF tokens verified on all POST requests

### 4. Password Security
- Passwords hashed using bcrypt
- Hash updated on registration
- Cannot access raw password values

### 5. Access Control
```php
// Admin middleware prevents:
- Non-admin users accessing /admin/* routes
- Anonymous users accessing any protected routes
- Session hijacking through token regeneration
```

---

## 🧪 Testing Access Control

### Test Script (Optional)
```php
// Via Tinker
php artisan tinker

// Create test admin user
$admin = User::create([
    'name' => 'Admin Test',
    'email' => 'admin@test.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);

// Create test regular user
$user = User::create([
    'name' => 'User Test',
    'email' => 'user@test.com',
    'password' => bcrypt('password123'),
    'role' => 'user'
]);

exit
```

### Manual Testing
1. **Test Admin Access**:
   - Login as admin user
   - Should see admin menu in navbar
   - Should access `/admin/dashboard`
   - Should access `/admin/users`
   - Should access `/admin/kas-data`

2. **Test User Access**:
   - Login as regular user
   - Should see user menu in navbar
   - Should access `/dashboard`
   - Should access `/riwayat`
   - Should access `/laporan`
   - Should NOT access `/admin/dashboard` (redirected)

3. **Test Unauthorized Access**:
   - Try accessing `/admin/users` as regular user
   - Should be redirected to `/dashboard` with error

---

## 📊 Current Permissions Matrix

| Action | Admin | User |
|--------|-------|------|
| View Dashboard | ✓ | ✓ |
| View Admin Dashboard | ✓ | ✗ |
| Manage Users | ✓ | ✗ |
| Manage Kas Data | ✓ | ✗ |
| View Reports | ✓ | ✓ |
| Change Settings | ✓ | ✗ |
| View Profile | ✓ | ✓ |
| View History | ✗ | ✓ |

---

## 🔧 Extending RBAC

### Add New Role

1. **Update Database**:
```php
// In migration
$table->enum('role', ['admin', 'user', 'moderator'])->default('user');
```

2. **Update AuthHelper.php**:
```php
public static function getRoles(): array
{
    return ['admin', 'user', 'moderator'];
}
```

3. **Create Middleware**:
```php
// app/Http/Middleware/ModeratorMiddleware.php
if (auth()->user()->role !== 'moderator') {
    return redirect()->route('dashboard');
}
```

4. **Register in Kernel.php**:
```php
'moderator' => \App\Http\Middleware\ModeratorMiddleware::class,
```

5. **Use in Routes**:
```php
Route::middleware(['auth', 'moderator'])->group(function () {
    // Moderator routes
});
```

### Add New Permission

1. **Update RbacServiceProvider.php**:
```php
// In can() method of AuthHelper
$permissions = [
    'admin' => ['manage-users', 'new-permission'],
    'user' => ['view-dashboard'],
];
```

2. **Use in Controller/Blade**:
```blade
@can('new-permission')
    <!-- Show feature -->
@endcan
```

---

## 🚀 Best Practices

### Do's ✓
- ✓ Always use middleware for route protection
- ✓ Use Blade directives in views for UI visibility
- ✓ Verify role on both backend and frontend
- ✓ Use helper functions for cleaner code
- ✓ Log unauthorized access attempts

### Don'ts ✗
- ✗ Don't rely only on frontend checks
- ✗ Don't expose admin UI to regular users
- ✗ Don't create duplicate role checks
- ✗ Don't hardcode role names (use constants)
- ✗ Don't trust user input for role verification

---

## 📝 Summary

The RBAC system provides:

1. **Multiple layers of security** - Frontend visibility + Backend enforcement
2. **Clean code** - Helper functions and Blade directives
3. **Flexible permissions** - Easy to extend and modify
4. **User experience** - Role-based navigation and redirects
5. **Maintainability** - Centralized role management

All components are in place and connected. The system is ready for production use!

---

## 🆘 Troubleshooting

| Issue | Cause | Solution |
|-------|-------|----------|
| User can access admin routes | Middleware not applied | Check route middleware |
| Blade directives not working | Service provider not registered | Add to config/app.php |
| Helper functions undefined | Autoload not configured | Clear cache and composer dump |
| Redirects not working | AuthController logic issue | Check role in database |

---

**Last Updated**: April 7, 2026  
**Version**: 1.0 - Complete RBAC Implementation
