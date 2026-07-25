# Registration Control System - Architecture & Flow

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    REGISTRATION CONTROL SYSTEM              │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │           ADMIN DASHBOARD                            │  │
│  │  ┌────────────────────────────────────────────────┐  │  │
│  │  │ Sidebar: "Registration Control" (Orange)       │  │  │
│  │  │  → Link to: /admin/registration-settings       │  │  │
│  │  └────────────────────────────────────────────────┘  │  │
│  └──────────────────────────────────────────────────────┘  │
│                           ↓                                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │    REGISTRATION SETTINGS PAGE                        │  │
│  │  ┌────────────────────────────────────────────────┐  │  │
│  │  │ Current Status: [OPEN] 🟢 or [CLOSED] 🔴     │  │  │
│  │  ├────────────────────────────────────────────────┤  │  │
│  │  │ Quick Toggle:                                  │  │  │
│  │  │  [Close Registration] or [Open Registration]   │  │  │
│  │  ├────────────────────────────────────────────────┤  │  │
│  │  │ Detailed Settings:                             │  │  │
│  │  │  ☐ Allow new user registration (checkbox)     │  │  │
│  │  │  ┌──────────────────────────────────────────┐ │  │  │
│  │  │  │ Custom Message Text Area                 │ │  │  │
│  │  │  │ (Message shown when registration closed) │ │  │  │
│  │  │  └──────────────────────────────────────────┘ │  │  │
│  │  │  [Save Settings] [Cancel]                      │  │  │
│  │  └────────────────────────────────────────────────┘  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │           DATABASE: SETTINGS TABLE                   │  │
│  │  ┌────────────────────────────────────────────────┐  │  │
│  │  │ id | key | value | type | description | ...   │  │  │
│  │  │ 1  | registration_open | 1 | boolean | ...   │  │  │
│  │  │ 2  | registration_closed_message | ... | ... │  │  │
│  │  └────────────────────────────────────────────────┘  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔄 Registration Process Flow

### When Registration is OPEN ✅

```
User                Browser              Server              Database
 │                   │                     │                  │
 ├─── Visit /register ──→                  │                  │
 │                   │                     │                  │
 │                   ├─────────────────→ RegisteredUserController
 │                   │      create()        │                  │
 │                   │                     ├─ Check status ───→
 │                   │                     │ (registration_open │
 │                   │                     │  = true)          │
 │                   │                     ← TRUE returned ────┤
 │                   │                     │                  │
 │                   ← Show Register Form ─┤                  │
 │                   │                     │                  │
 │ Fill form & Submit                      │                  │
 ├─── POST /register ──→                   │                  │
 │                   │                     │                  │
 │                   ├─────────────────→ RegisteredUserController
 │                   │       store()        │                  │
 │                   │                     ├─ Check status ───→
 │                   │                     │ (registration_open │
 │                   │                     │  = true)          │
 │                   │                     ← TRUE returned ────┤
 │                   │                     │                  │
 │                   │                    [Validate Data]      │
 │                   │                    [Process Payment]    │
 │                   │                    [Create User] ──→ SAVED
 │                   │                     │                  │
 │                   ← Success Page ──────┤                  │
 │                   │                     │                  │
```

### When Registration is CLOSED ❌

```
User                Browser              Server              Database
 │                   │                     │                  │
 ├─── Visit /register ──→                  │                  │
 │                   │                     │                  │
 │                   ├─────────────────→ RegisteredUserController
 │                   │      create()        │                  │
 │                   │                     ├─ Check status ───→
 │                   │                     │ (registration_open │
 │                   │                     │  = false)         │
 │                   │                     ← FALSE returned ───┤
 │                   │                     │                  │
 │                   │                    [Get custom message]│
 │                   │                     │                  │
 │                   ← Closed Page ───────┤                  │
 │ (Shows message,   │ [Lock Icon]         │                  │
 │  Login Link,      │ [Custom Message]    │                  │
 │  Home Link)       │ [Login Button]      │                  │
 │                   │                     │                  │
 │                   │                     │                  │
 │ Tries POST /register (if direct)        │                  │
 ├─── POST /register ──→                   │                  │
 │                   │                     │                  │
 │                   ├─────────────────→ RegisteredUserController
 │                   │       store()        │                  │
 │                   │                     ├─ Check status ───→
 │                   │                     │ (registration_open │
 │                   │                     │  = false)         │
 │                   │                     ← FALSE returned ───┤
 │                   │                     │                  │
 │                   │                    [Abort - return error]
 │                   │                     │                  │
 │                   ← Error Message ─────┤                  │
 │                   │ "Registration Closed"                  │
 │                   │                     │                  │
```

### Login (Always Works) ✅

```
User                Browser              Server              Database
 │                   │                     │                  │
 ├─── Visit /login ──→                     │                  │
 │                   │                     │                  │
 │                   ├──────────────────→ AuthenticatedSessionController
 │                   │    create()         │                  │
 │                   │ [NO REGISTRATION CHECK!]               │
 │                   │                     │                  │
 │                   ← Show Login Form ───┤                  │
 │                   │                     │                  │
 │ Enter credentials &  Submit             │                  │
 ├─── POST /login ──→                      │                  │
 │                   │                     │                  │
 │                   ├──────────────────→ AuthenticatedSessionController
 │                   │     store()         │                  │
 │                   │ [NORMAL AUTH]      ├─ Verify User ───→
 │                   │                     │                  │
 │                   │                    [Authenticate] ← OK
 │                   │                     │                  │
 │                   ← Dashboard ────────┤                  │
 │ (Success!)        │ (Session created)   │                  │
 │                   │                     │                  │
```

---

## 📊 State Diagram

```
                    ┌─────────────────┐
                    │                 │
                    │    START        │
                    │                 │
                    └────────┬────────┘
                             │
                             ▼
                   ┌─────────────────┐
                   │                 │
                   │ Check DB Setting│
                   │ registration_   │
                   │ open = ?        │
                   │                 │
                   └────────┬────────┘
                      │     │
            ┌─────────┘     └──────────┐
            │                          │
            ▼ TRUE                     ▼ FALSE
    ┌──────────────────┐      ┌──────────────────┐
    │                  │      │                  │
    │ REGISTRATION     │      │ REGISTRATION     │
    │ OPEN STATE ✅     │      │ CLOSED STATE ❌   │
    │                  │      │                  │
    │ Display Form     │      │ Show Closed      │
    │ Allow Submission │      │ Page             │
    │ Process Payment  │      │ Show Message     │
    │ Create User      │      │ Block Form       │
    │                  │      │                  │
    └────────┬─────────┘      └──────────────────┘
             │
             │ Admin Toggle
             │
             ▼
    ┌─────────────────┐
    │                 │
    │ UPDATE DB       │
    │ registration_   │
    │ open = !old     │
    │                 │
    └────────┬────────┘
             │
             ▼
    ┌─────────────────┐
    │                 │
    │ NEXT USER       │
    │ REQUEST SEES    │
    │ NEW STATE       │
    │                 │
    └─────────────────┘
```

---

## 🗂️ File Relationships

```
┌─────────────────────────────────────────────────────┐
│              ROUTES (web.php)                       │
│  POST /admin/registration-settings/toggle           │
│  POST /admin/registration-settings/update           │
│  GET  /admin/registration-settings                  │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────┐
│  RegistrationSettingsController (Admin)             │
│  • index() → registration-settings.blade.php        │
│  • toggle() → Update DB, Redirect                   │
│  • update() → Save detailed settings                │
└──────────────┬───────────────────────┬──────────────┘
               │                       │
        ┌──────▼────────┐      ┌────────▼──────┐
        │                │      │                │
        ▼                │      ▼                │
    ┌──────────────┐     │  ┌──────────────┐    │
    │ Setting      │     │  │ Setting      │    │
    │ Model        │◄────┘  │ Model        │◄───┘
    │ get/setSetting   │     │ get/setSetting   │
    └────────┬─────┘     │  └─────────────────┘
             │                │
             ▼                ▼
        ┌──────────────────────────┐
        │   Database: Settings     │
        │   Table                  │
        └──────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  ROUTES (auth.php)                                  │
│  GET  / (register form) ← RegisteredUserController  │
│  POST / (register submit)← RegisteredUserController │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────┐
│  RegisteredUserController                           │
│  • create() → Check registration_open               │
│    ├─ If TRUE → register.blade.php                  │
│    └─ If FALSE → registration-closed.blade.php      │
│                                                     │
│  • store() → Check registration_open                │
│    ├─ If TRUE → Process registration                │
│    └─ If FALSE → Redirect with error                │
└──────────────┬───────────────────────┬──────────────┘
               │                       │
        ┌──────▼────────┐      ┌────────▼──────┐
        │                │      │                │
        ▼                ▼      ▼                ▼
    register.blade   registration-closed   Setting
      .php           .blade.php             Model
```

---

## 🔌 Integration Points

```
Admin User
    │
    ├─→ Dashboard
    │   └─→ Sidebar
    │       └─→ "Registration Control" Link
    │           └─→ /admin/registration-settings
    │               └─→ RegistrationSettingsController@index
    │                   └─→ VIEW: registration-settings.blade.php
    │
    └─→ [Click Toggle/Save]
        └─→ RegistrationSettingsController@toggle/update
            └─→ Setting::setSetting()
                └─→ Database Update
                    └─→ Immediate Effect


Regular User
    │
    ├─→ Visit /register
    │   └─→ RegisteredUserController@create
    │       ├─→ Setting::getSetting('registration_open')
    │       ├─ If TRUE → VIEW: register.blade.php
    │       └─ If FALSE → VIEW: registration-closed.blade.php
    │
    └─→ [Try to submit if CLOSED]
        └─→ RegisteredUserController@store
            ├─→ Setting::getSetting('registration_open')
            ├─ If TRUE → Process registration
            └─ If FALSE → Redirect with error message


Login (Always Available)
    │
    └─→ Visit /login
        └─→ AuthenticatedSessionController@create
            └─→ VIEW: login.blade.php
                [NO registration_open check]
                [User can login normally]
```

---

## 📈 State Change Timeline

```
Timeline: Registration Status Changes

Admin Opens Dashboard
    │
    ├─ Sidebar visible
    ├─ "Registration Control" visible (Admin only)
    │
    ▼ CLICK
    
Admin goes to Settings Page
    │
    ├─ Current status: CLOSED 🔴
    ├─ Message displayed
    │
    ▼ CLICK "Open Registration"
    
Toggle Action
    │
    ├─ INSTANT DB UPDATE
    │  registration_open: 0 → 1
    │
    ├─ Success Message shown
    │
    ▼ NEXT USER REQUEST

User tries /register
    │
    ├─ Check DB: registration_open = 1 ✅
    ├─ Show registration form
    ├─ Can submit and complete registration
    │
    ▼ (DAYS LATER)
    
Admin needs to close registration
    │
    ├─ CLICK "Close Registration"
    │
    ▼ INSTANT DB UPDATE
    
New status in DB
    │
    ├─ registration_open: 1 → 0
    │
    ▼ NEXT USER REQUEST

User tries /register
    │
    ├─ Check DB: registration_open = 0 ❌
    ├─ Show closed page
    ├─ Cannot complete registration
    ├─ But can still login
    │
    └─ [END]
```

---

## 🎯 Control Flow Summary

```
┌──────────────┐
│ ADMIN PANEL  │
└──────┬───────┘
       │ Toggle/Update
       ▼
┌──────────────────────┐
│ SETTING in DATABASE  │
│ registration_open    │
└──────┬───────────────┘
       │ Check on each request
       ├─────────────────────────────┬────────────────────┐
       │                             │                    │
       ▼ TRUE                        ▼ FALSE              ▼
   ┌────────────┐            ┌──────────────┐      ┌──────────┐
   │ FORM SHOWN │            │ CLOSED PAGE  │      │ LOGIN OK │
   │ REGISTER   │            │ SHOWN        │      │ (no check)
   │ WORKS      │            │ REGISTER     │      │          │
   │ PAYMENT OK │            │ BLOCKED      │      │ ALWAYS   │
   └────────────┘            └──────────────┘      │ WORKS    │
                                                   └──────────┘
```

---

**Diagram Version**: 1.0
**Last Updated**: February 19, 2026
