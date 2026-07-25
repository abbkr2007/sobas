# Quick Reference - Registration Control System

## 🚀 Quick Start

### For Admin
1. Log in → Dashboard
2. Sidebar → **Registration Control** (orange button)
3. Click **"Close Registration"** or **"Open Registration"**
4. Done! Effect is immediate

### For Users
- **If OPEN**: Can register normally
- **If CLOSED**: See closure message, can still login

---

## 📱 Admin Panel Location
```
URL: /admin/registration-settings
Sidebar: "Registration Control" (orange icon, admin only)
```

---

## 🔄 Two Ways to Control Registration

### Quick Toggle
- **Button**: "Close Registration" / "Open Registration"
- **Effect**: Instant
- **Best for**: Quick on/off changes

### Detailed Settings
- **Toggle Switch**: Enable/disable registration
- **Text Area**: Customize closure message
- **Button**: "Save Settings"
- **Best for**: Message customization

---

## 💾 Database Info

### Auto-Created on Migration
```
Table: settings
Default Records:
- registration_open (true)
- registration_closed_message (default text)
```

---

## 📋 What Happens

### Registration OPEN ✅
```
→ /register page accessible
→ Form works normally
→ Payment processing works
→ Users can complete registration
```

### Registration CLOSED ❌
```
→ /register shows closure page
→ Cannot submit registration form
→ Custom message displayed
→ Login still works ✅
→ Users can access dashboard
```

---

## 🔧 Implementation Tasks

- [ ] Run: `php artisan migrate`
- [ ] Test: Visit `/admin/registration-settings`
- [ ] Test: Toggle registration on/off
- [ ] Test: Try registering when closed
- [ ] Test: Verify login works when closed

---

## 📂 Key Files

| File | Purpose |
|------|---------|
| `database/migrations/2026_02_19_000001_create_settings_table.php` | Create settings table |
| `app/Models/Setting.php` | Settings helper |
| `app/Http/Controllers/Admin/RegistrationSettingsController.php` | Admin logic |
| `resources/views/admin/registration-settings.blade.php` | Admin panel UI |
| `resources/views/auth/registration-closed.blade.php` | Closed message UI |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Registration check |

---

## ⚡ Quick Code Usage

### In Your Code
```php
// Check if registration is open
$isOpen = Setting::getSetting('registration_open', true);

// Get custom message
$message = Setting::getSetting('registration_closed_message');

// Update setting
Setting::setSetting('registration_open', false, 'boolean');
```

---

## 🎨 UI Elements

### Admin Panel
- Status badge (Green/Red)
- Current status display
- Quick toggle button
- Settings form
- Information boxes
- Success/Error messages

### Closed Page
- Lock icon
- "Registration Closed" heading
- Custom message
- Alert box
- Login button
- Home button

---

## 🔐 Access Control

✅ **Visible to**: Authenticated admin users only
❌ **NOT visible to**: Regular users, guests
✅ **Menu shows in**: Sidebar (orange color)

---

## 📞 Support

See full documentation in:
- `REGISTRATION_CONTROL_SETUP.md` - Detailed guide
- `REGISTRATION_CONTROL_README.md` - Complete overview

---

## 📊 Status Indicators

| Icon/Badge | Meaning |
|------------|---------|
| 🟢 GREEN Badge | Registration is OPEN |
| 🔴 RED Badge | Registration is CLOSED |
| 🔒 Lock Icon | Portal is locked/closed |
| ✅ Check | Registration works |

---

**Last Updated**: February 19, 2026
**System**: Registration Control v1.0
**Status**: Ready to Deploy
