# Attendance Management System — Architecture

## 1. Project Overview

The Attendance Management System is a Laravel-based web application designed to manage employees, attendance, leave, departments, designations, work schedules, reports, users, and system settings.

The system has two primary roles:

* Administrator
* Employee

The architecture must remain modular, maintainable, secure, and easy to extend.

---

# 2. Technology Stack

* Laravel
* PHP 8.2+
* MySQL
* Blade
* Tailwind CSS
* Alpine.js
* Laravel Eloquent ORM
* Laravel Authentication
* Laravel Policies
* Laravel Middleware
* Laravel Form Requests
* PHPUnit/Pest

---

# 3. High-Level Architecture

The application follows a layered Laravel architecture:

```text
Browser
   |
   v
Routes
   |
   v
Middleware
   |
   v
Controllers
   |
   v
Form Requests / Validation
   |
   v
Services
   |
   v
Models / Eloquent
   |
   v
MySQL Database
```

For UI:

```text
Blade Layout
    |
    +-- Sidebar
    +-- Topbar
    +-- Components
    +-- Pages
    +-- Forms
    +-- Tables
    +-- Cards
```

---

# 4. User Roles

## Administrator

The Administrator is responsible for managing the entire system.

Permissions:

* Dashboard
* Employees
* Departments
* Designations
* Attendance
* Leave
* Work schedules
* Reports
* Users
* Settings

An Administrator does NOT require an employee profile.

---

## Employee

Employees can only access their own information.

Permissions:

* Employee dashboard
* Own profile
* Own attendance
* Check-in
* Check-out
* Attendance history
* Leave application
* Leave history

Employees cannot access administrative modules.

---

# 5. Authentication Architecture

Authentication must be handled using Laravel's authentication system.

Administrator login:

```text
Email + Password
```

Employee login:

```text
Employee Code + Password
```

Authentication flow:

```text
Login Request
     |
     v
Validate Credentials
     |
     v
Find User
     |
     v
Check Role
     |
     +---- Admin ------> Admin Dashboard
     |
     +---- Employee ---> Employee Dashboard
```

Never determine authorization only from frontend UI.

Authorization must always be checked server-side.

---

# 6. Authorization

Use middleware and policies.

Suggested middleware:

```text
auth
admin
employee
```

Example:

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```

Employee routes:

```php
Route::middleware(['auth', 'employee'])->group(function () {
    // Employee routes
});
```

---

# 7. Important Authorization Rule

Administrators must be able to access the dashboard without an employee record.

Correct:

```text
User role = admin
        |
        v
Admin Dashboard
```

Incorrect:

```text
User role = admin
        |
        v
Check employee profile
        |
        v
Account Setup Pending
```

Employee profile checks must only apply to employee users.

---

# 8. Main Modules

## Authentication

* Login
* Logout
* Forgot password
* Reset password

## Dashboard

* Admin dashboard
* Employee dashboard

## Employee Management

* Employee list
* Create employee
* Edit employee
* View employee
* Delete employee
* Activate/deactivate

## Department Management

* Create
* Edit
* Delete
* Status

## Designation Management

* Create
* Edit
* Delete
* Status

## Attendance

* Check-in
* Check-out
* Attendance records
* Attendance status
* Working hours
* Late calculation
* Overtime calculation

## Leave

* Leave types
* Leave applications
* Approval
* Rejection
* Leave history

## Work Schedule

* Create schedules
* Edit schedules
* Assign schedules
* Grace period

## Reports

* Daily
* Monthly
* Employee
* Department
* Late
* Absence
* Leave
* Overtime

## Users

* Admin users
* Employee users
* Account status

## Settings

* General
* Attendance
* Localization
* System

---

# 9. Suggested Folder Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── DashboardController.php
│   │   ├── EmployeeController.php
│   │   ├── DepartmentController.php
│   │   ├── DesignationController.php
│   │   ├── AttendanceController.php
│   │   ├── LeaveController.php
│   │   ├── LeaveTypeController.php
│   │   ├── WorkScheduleController.php
│   │   ├── ReportController.php
│   │   ├── UserController.php
│   │   ├── ProfileController.php
│   │   └── SettingController.php
│   │
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── EmployeeMiddleware.php
│   │
│   └── Requests/
│       ├── Employee/
│       ├── Attendance/
│       ├── Leave/
│       ├── Department/
│       └── Designation/
│
├── Models/
│   ├── User.php
│   ├── Employee.php
│   ├── Department.php
│   ├── Designation.php
│   ├── Attendance.php
│   ├── Leave.php
│   ├── LeaveType.php
│   ├── WorkSchedule.php
│   └── Setting.php
│
├── Policies/
│
└── Services/
    ├── AttendanceService.php
    ├── LeaveService.php
    ├── EmployeeService.php
    ├── ReportService.php
    └── DashboardService.php
```

---

# 10. View Structure

```text
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── guest.blade.php
│   └── navigation.blade.php
│
├── components/
│   ├── alert.blade.php
│   ├── button.blade.php
│   ├── card.blade.php
│   ├── modal.blade.php
│   ├── table.blade.php
│   └── badge.blade.php
│
├── admin/
│   ├── dashboard.blade.php
│   ├── employees/
│   ├── departments/
│   ├── designations/
│   ├── attendance/
│   ├── leaves/
│   ├── reports/
│   ├── users/
│   └── settings/
│
├── employee/
│   ├── dashboard.blade.php
│   ├── attendance/
│   ├── leaves/
│   └── profile/
│
└── auth/
    ├── login.blade.php
    ├── forgot-password.blade.php
    └── reset-password.blade.php
```

---

# 11. Service Layer

Business logic should not become unnecessarily large inside controllers.

Example:

```text
AttendanceController
        |
        v
AttendanceService
        |
        +-- Check In
        +-- Check Out
        +-- Calculate Hours
        +-- Calculate Late
        +-- Calculate Overtime
```

This keeps controllers clean.

---

# 12. Attendance Calculation

Attendance service should calculate:

```text
Working Hours
Late Minutes
Early Leave
Overtime
```

Example:

```text
Schedule:
09:00 AM - 05:00 PM

Grace Period:
15 minutes

Check-in:
09:08 AM

Result:
Present
Late = 0
```

Check-in:

```text
09:25 AM

Result:
Late
Late Minutes = 10
```

The exact calculation should be implemented in a reusable service.

---

# 13. Leave Workflow

```text
Employee
   |
   v
Apply Leave
   |
   v
Pending
   |
   +------> Admin Approves
   |              |
   |              v
   |           Approved
   |
   +------> Admin Rejects
                  |
                  v
               Rejected
```

---

# 14. Reporting Architecture

Reports should use dedicated queries/services.

Avoid loading unnecessary records.

Use:

* Query scopes
* Eager loading
* Pagination
* Date filtering
* Database indexes

---

# 15. Error Handling

Use Laravel's standard exception handling.

Required pages:

```text
403
404
419
422
500
```

Do not expose sensitive debugging information in production.

---

# 16. Security

The application must implement:

* CSRF protection
* Authentication
* Authorization
* Policies
* Middleware
* Password hashing
* Form validation
* Mass assignment protection
* Secure file uploads
* Database constraints

Never trust frontend authorization.

---

# 17. Performance

Avoid:

* N+1 queries
* Unnecessary database queries
* Large unpaginated tables

Use:

```php
with()
paginate()
select()
where()
scope()
```

where appropriate.

---

# 18. Development Workflow

Implementation order:

```text
Architecture
    ↓
Database
    ↓
Authentication
    ↓
Authorization
    ↓
Employees
    ↓
Attendance
    ↓
Leave
    ↓
Reports
    ↓
Dashboard
    ↓
Settings
    ↓
UI Refinement
    ↓
Testing
    ↓
QA
```

Each phase must be tested before moving to the next phase.

---

# 19. Definition of Done

The project is complete only when:

* Application runs successfully
* Database migrates successfully
* Seeders work
* Admin login works
* Employee login works
* Admin dashboard works
* Employee dashboard works
* Attendance works
* Leave works
* Reports work
* Authorization works
* Responsive UI works
* Automated tests pass
* Documentation is complete
