# RBAC Quick Reference - Cheat Sheet

## 🚀 Quick Start

### Use in Blade (UI)
```blade
@admin
  <!-- Only admins see this -->
@endadmin

@user
  <!-- Only users see this -->
@enduser

@can('manage-users')
  <!-- If user can manage users -->
@endcan
```

### Use in Controllers (Logic)
```php
if (isAdmin()) {
  // Admin logic
}

if (userCan('manage-users')) {
  // Do action
}
```

### Use in Routes (Protection)
```php
Route::middleware(['auth', 'admin'])->group(function () {
  Route::get('/admin/dashboard', ...);
});
```

---

## 📋 Helper Functions

| Function | Purpose | Example |
|----------|---------|---------|
| `isAdmin()` | Is user admin? | `@if(isAdmin())` |
| `isUser()` | Is user regular? | `@if(isUser())` |
| `hasRole($r)` | Has role? | `hasRole('admin')` |
| `hasAnyRole($a)` | Has any role? | `hasAnyRole(['admin', 'mod'])` |
| `userRole()` | Get role | `{{ userRole() }}` |
| `userCan($a)` | Can perform? | `userCan('manage-users')` |

---

## 🎯 Blade Directives

| Directive | Usage |
|-----------|-------|
| `@admin` | Show to admins only |
| `@notAdmin` | Show to non-admins |
| `@user` | Show to users only |
| `@notUser` | Show to non-users |
| `@role('admin')` | Show to specific role |
| `@anyRole(['a','b'])` | Show if any role |
| `@can('action')` | Show if can do action |

---

## 🛡️ Middleware

| Middleware | Purpose |
|------------|---------|
| `auth` | User is logged in |
| `admin` | User is admin |
| `user` | Alias for auth (any role) |

### Apply in Routes
```php
// Admin only
Route::middleware(['auth', 'admin'])->group(function () {
  Route::get('/admin/dashboard', ...);
});

// Any authenticated user
Route::middleware('auth')->group(function () {
  Route::get('/dashboard', ...);
});
```

---

## 📱 Routes

| Route | Role | Purpose |
|-------|------|---------|
| `/dashboard` | Any | User home |
| `/riwayat` | User | Transaction history |
| `/laporan` | User | Reports |
| `/profile` | Any | User profile |
| `/admin/dashboard` | Admin | Admin home |
| `/admin/users` | Admin | User management |
| `/admin/kas-data` | Admin | Kas management |
| `/admin/settings` | Admin | System settings |

---

## 🔓 Sessions

### For Current User
```php
auth()->user()           // Get user object
auth()->user()->name     // User name
auth()->user()->role     // User role
isAdmin()                // Is admin?
userRole()               // Get role
```

### Check in View
```blade
{{ auth()->user()->name }}
{{ auth()->user()->role }}
@if(auth()->check())
  User is logged in
@endif
```

---

## 🎨 Common Patterns

### Admin Menu (Navbar)
```blade
@admin
  <a href="/admin/dashboard">Dashboard</a>
  <a href="/admin/users">Users</a>
@endadmin
```

### User Menu (Navbar)
```blade
@user
  <a href="/dashboard">Dashboard</a>
  <a href="/riwayat">History</a>
@enduser
```

### Protected Action Button
```blade
@can('manage-users')
  <button>Manage Users</button>
@endcan
```

### Show User Role
```blade
Your role: {{ userRole() }}
```

---

## 🏗️ Add New Role

### 1. Update DB
```php
// migration
$table->enum('role', ['admin', 'user', 'moderator'])->default('user');
```

### 2. Create Middleware
```php
// app/Http/Middleware/ModeratorMiddleware.php
if (auth()->user()->role !== 'moderator') {
  return redirect()->route('dashboard');
}
```

### 3. Register
```php
// config/app.php
// app/Http/Kernel.php
'moderator' => ModeratorMiddleware::class,
```

### 4. Use
```php
Route::middleware(['auth', 'moderator'])->group(...);
```

---

## 🔧 Add New Permission

### In AuthHelper.php
```php
public static function can(string $action): bool
{
    $permissions = [
        'admin' => ['manage-users', 'new-action'],
        'user' => ['view-dashboard'],
    ];
    
    return in_array($action, $permissions[userRole()] ?? []);
}
```

### Use
```blade
@can('new-action')
  Content
@endcan
```

```php
if (userCan('new-action')) {
  // Do something
}
```

---

## ❌ Common Mistakes

| ❌ Wrong | ✅ Right |
|---------|----------|
| Hardcoded role checks | Use `isAdmin()` helper |
| Frontend-only security | Add middleware to routes |
| No error messages | Redirect with message |
| Repeating role checks | Create helper function |
| String role values | Use enum in DB |

---

## 🧪 Testing

### Create Test Admin
```bash
php artisan tinker
```
```php
User::create(['name'=>'Admin', 'email'=>'admin@t.com', 'password'=>bcrypt('pass'), 'role'=>'admin']);
```

### Create Test User
```php
User::create(['name'=>'User', 'email'=>'user@t.com', 'password'=>bcrypt('pass'), 'role'=>'user']);
```

### Exit Tinker
```php
exit
```

---

## 🐛 Troubleshooting

| Issue | Fix |
|-------|-----|
| Directives not work | Clear cache: `php artisan cache:clear` |
| Functions undefined | Clear config: `php artisan config:clear` |
| Middleware not working | Check route middleware applied |
| User can access admin | Check DB role value and middleware |

---

## 📊 Permission Matrix

```
         Admin   User
Dashboard  ✓      ✓
Riwayat    ✗      ✓
Laporan    ✓      ✓
Profile    ✓      ✓
Admin Menu ✓      ✗
Settings   ✓      ✗
```

---

## 🎯 Files to Know

| File | Purpose |
|------|---------|
| `app/Helpers/AuthHelper.php` | Helper functions |
| `app/Providers/RbacServiceProvider.php` | Directives & registration |
| `app/Http/Middleware/AdminMiddleware.php` | Admin check |
| `app/Http/Middleware/UserMiddleware.php` | Auth check |
| `config/app.php` | Service provider registration |
| `app/Http/Kernel.php` | Middleware aliases |

---

## 📖 Full Docs

See: `RBAC_IMPLEMENTATION.md`

---

**Quick Reference Version**: 1.0  
**Last Updated**: April 7, 2026
