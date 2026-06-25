# EMS - Expense Management System

Laravel 13 based expense management system. Built this as an assignment — covers everything from auth to real-time notifications.

**Repository:** https://github.com/vikas4983/EMS.git

---

## Stack

- Laravel 13
- Livewire (Jetstream)
- Spatie Permission (roles/permissions)
- Laravel Reverb + Echo (websockets)
- MySQL
- Vite

---

## Git Branching

Maintained separate branches throughout development instead of pushing everything to main.

```
main                    → stable, final code
develop                 → active development, all features merged here
dashbord                → dashboard with role-based stats and caching
test-case               → unit & feature tests (approval, creation, notifications, reports)
real-time-notification  → Laravel Reverb + Echo setup, navbar notification dropdown
soft-delete-category    → soft deletes on categories and expenses
```


Workflow followed:
- New feature → branch off `develop`
- Once done → merge back into `develop`
- After full testing → merge `develop` into `main`

---

## How to Run

**1. Clone & install dependencies**

```bash
git clone https://github.com/vikas4983/EMS.git
cd EMS
composer install
npm install
cp .env.example .env
php artisan key:generate
```

**2. Install Jetstream with Livewire**

```bash
composer require laravel/jetstream
php artisan jetstream:install livewire
```

**3. Install Spatie Permission**

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

**4. Install Laravel Reverb (WebSockets)**

```bash
composer require laravel/reverb
php artisan reverb:install
```

**5. Setup .env**

Fill in DB credentials, mail config, and Reverb keys.

```env
DB_DATABASE=ems
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...

REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_APP_SECRET=...
REVERB_HOST=localhost
REVERB_PORT=8080
```

**6. Migrate & seed**

```bash
php artisan migrate
php artisan db:seed
```

Seeders create roles, permissions, default categories, and 3 demo users.

**7. Start everything**

Three terminals needed:

```bash
php artisan serve
php artisan reverb:start
php artisan queue:work
```

```bash
npm run dev
```

---

## Login Credentials (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@admin.com | password |
| Manager | manager@manager.com | password |
| Employee | employee@employee.com | password |

---

## What's Implemented

**Auth & Roles**
Jetstream handles login/logout. Used Spatie for RBAC — three roles: admin, manager, employee. Permission checks are on both the UI side and backend (middleware), so even if someone hits a URL directly they'll get blocked.

**Categories**
Simple CRUD, only admin can create/edit/delete. Soft deletes on categories.

**Expenses**
Employees submit with title, amount, date, category, description, and can upload multiple receipts (image or PDF). Receipts go into `storage` and are accessible only to logged-in users via a protected route.

Employee can edit their expense as long as it's still pending. Once manager acts on it, editing is locked.

**Approval Flow**
Managers see all pending expenses and can approve or reject with a comment. After action, expense is locked for editing and notifications fire.

**Notifications**
Two things happen when manager approves/rejects — an email goes out (via queue) and a real-time notification shows up in the navbar dropdown (via Reverb + Echo). Same for when an employee submits a new expense — manager gets notified.

**Dashboard**
One dashboard page, content changes based on role. Shows total expenses this month, pending count, and top 5 categories. Stats are cached so it doesn't hammer the DB on every load.

**Reports**
Admin can filter expenses (by date range, category, status, employee) and download as PDF or CSV.

**Performance**
Eager loading on all list queries, pagination, cache on dashboard stats, emails in queue.

---

## Running Tests

```bash
# all tests
php artisan test

# expense folder
php artisan test tests/Feature/Expense/
php artisan test tests/Feature/Expense/ExpenseApprovalTest.php
php artisan test tests/Feature/Expense/ExpenseCreationTest.php

# notifications
php artisan test tests/Feature/Notification/NotificationTest.php

# reports
php artisan test tests/Feature/Report/ReportExportTest.php
```

Tests use `RefreshDatabase` so they're isolated. Roles and permissions are created fresh in `setUp()` for each test class.

---

## Notes

## AI Usage

Like many developers, I use AI tools such as ChatGPT, Claude, Codex, and Antigravity as part of my daily development workflow. I mainly use them for research, learning new concepts, debugging, performance optimization, and exploring different implementation approaches.

For this project, AI was mainly helpful in a few areas:

- Understanding the initial setup and configuration of Laravel Reverb and Laravel Echo, as it was my first time working with real-time notifications.
- Reviewing a few PHPUnit test cases and improving repetitive test setup.
- Exploring optimization ideas such as eager loading, caching strategies, and general Laravel best practices.

The application was developed by implementing and adapting these ideas based on the project requirements and my understanding of Laravel.

---

## Future Improvements

Although the project meets the assignment requirements, there are several features that could be added in future versions:

- Dashboard charts and analytics
- Multi-level expense approval workflow
- REST API for mobile or third-party integration
- Improved mobile responsiveness
- Activity and audit logs
- Expense limits and department-wise budgets
- Advanced filtering and reporting
- Scheduled monthly expense summary emails