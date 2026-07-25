# Registration Control System - Setup Guide

## Overview
This document explains how to set up the registration control system that allows admins to open/close registration while keeping login available.

## Files Created/Modified

### 1. Database Migration
**File**: `database/migrations/2026_02_19_000001_create_settings_table.php`
- Creates `settings` table to store configuration
- Initializes default settings for registration control
- **Action Required**: Run migration once database is connected

### 2. Settings Model
**File**: `app/Models/Setting.php`
- Provides helper methods to get/set settings
- Automatic type casting (boolean, integer, array, string)
- Usage: `Setting::getSetting('registration_open', true)`

### 3. Admin Controller
**File**: `app/Http/Controllers/Admin/RegistrationSettingsController.php`
- Manages registration settings
- Provides toggle and update functionality
- Accessible only to authenticated users (recommend adding admin check)

### 4. Registration Form Check
**File**: `app/Http/Controllers/Auth/RegisteredUserController.php`
- Modified `create()` method to check if registration is open
- Modified `store()` method to prevent registration when closed
- Shows custom closed message to users

### 5. Registration Closed View
**File**: `resources/views/auth/registration-closed.blade.php`
- Professional closed portal message
- Links to login and home page
- Customizable message from admin panel

### 6. Admin Settings View
**File**: `resources/views/admin/registration-settings.blade.php`
- User-friendly admin panel
- Quick toggle button
- Custom message editor
- Status indicator

### 7. Routes
**File**: `routes/web.php`
- Added admin routes (prefix: `/admin`)
- Routes:
  - `GET /admin/registration-settings` - View settings
  - `POST /admin/registration-settings/toggle` - Quick toggle
  - `POST /admin/registration-settings/update` - Update settings

### 8. Admin Menu Item
**File**: `resources/views/partials/dashboard/vertical-nav.blade.php`
- Added "Registration Control" menu item
- Visible only to admin users
- Orange accent color for visibility

## How to Implement

### Step 1: Ensure Database Connection
Make sure your `.env` file has correct database credentials:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sobas
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### Step 2: Run Migration
```bash
cd d:\sobas
php artisan migrate
```

### Step 3: Access Admin Panel
1. Log in with an admin account
2. In the sidebar, you'll see "Registration Control" menu item (orange color)
3. Click to open the registration settings page

### Step 4: Control Registration

#### Quick Toggle
- Click "Open Registration" or "Close Registration" button to toggle instantly

#### Detailed Settings
1. Toggle the "Allow new user registration" checkbox
2. Customize the closed portal message
3. Click "Save Settings"

## Features

### When Registration is OPEN
- Users can access registration page
- Full registration flow is available
- Login is available

### When Registration is CLOSED
- Users see custom closure message
- Cannot submit registration form
- Login page remains accessible
- Message explains status and provides links

## Database Structure

### Settings Table
```
id              - Primary key
key             - Setting name (unique)
value           - Setting value
type            - Data type (string, boolean, integer, array)
description     - Admin description
created_at      - Creation timestamp
updated_at      - Update timestamp
```

## Default Settings

| Key | Value | Type |
|-----|-------|------|
| registration_open | 1 | boolean |
| registration_closed_message | "Registration portal is currently closed..." | string |

## Security Notes

- Add additional permission/role checks to controller if needed
- Currently accessible to all authenticated users - consider restricting to admin only
- Messages are sanitized before display

## Customization

### Change Admin Access Level
In `RegistrationSettingsController`, add middleware:
```php
public function __construct()
{
    $this->middleware('admin'); // Add custom middleware
}
```

### Change Menu Visibility
In `vertical-nav.blade.php`, modify condition:
```blade
@if(auth()->check() && auth()->user()->user_type == 'admin')
    <!-- Registration Control menu item -->
@endif
```

### Modify Closed Message
Edit default message in migration or database directly.

## Testing

1. **Test Registration OPEN**
   - Admin opens registration
   - User can access `/register`
   - Form submits successfully

2. **Test Registration CLOSED**
   - Admin closes registration
   - User redirected to closed page
   - Custom message displays
   - Login link works
   - Cannot access form

3. **Test Login**
   - Login always works regardless of registration status
   - Both open and closed states

## Troubleshooting

### Settings table not found
- Run migration: `php artisan migrate`

### Admin menu not showing
- Verify user has `user_type == 'admin'`
- Check sidebar cache: `php artisan view:clear`

### Settings not saving
- Check database connection
- Verify proper POST method is used
- Check form CSRF token

## Future Enhancements

1. Add registration deadline datetime
2. Add email notifications when registration opens/closes
3. Add registration statistics
4. Add role-based access control
5. Add activity logging
6. Add multiple registration periods

---

**Created**: February 19, 2026
**Status**: Ready for Implementation
