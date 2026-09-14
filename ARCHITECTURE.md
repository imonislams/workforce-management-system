# Workforce Management System — Architecture Documentation

## 1. Overview
The **Workforce Management System (WMS)** is a production-ready, modular enterprise application built on Laravel 12 with PHP 8.3, MySQL / SQLite, Blade templates, Tailwind CSS, and Alpine.js.

The system adopts a layered architecture separating HTTP handling, business logic, data persistence, and UI rendering:

```
[ HTTP Requests / Browser ]
         │
         ▼
[ Routes & Middleware ] (auth, role:admin, role:employee)
         │
         ▼
[ Controllers ] (Form Requests validation & HTTP responses)
         │
         ▼
[ Services ] (AttendanceService, LeaveService, EmployeeService, etc.)
         │
         ▼
[ Eloquent Models & Policies ] (Domain Rules & Database Abstraction)
         │
         ▼
[ Database ] (MySQL / SQLite with FKs & Indexes)
```

---

## 2. Core Modules
1. **Authentication & Identity**: Dual-login mechanism (Admin via Email + Password; Employee via Employee Code + Password), Session management, Password Reset.
2. **User & Role Management**: RBAC with two roles: `administrator` and `employee`.
3. **Department & Designation Management**: Structural organization master data.
4. **Work Schedule Management**: Shift templates (start/end time, break times, grace period, status).
5. **Employee Lifecycle Management**: Employee profile, unique employee code generator/validator, work schedule linking, status control (active/inactive/terminated).
6. **Attendance & Time Tracking**: Employee Check-in / Check-out, automatic computation of late minutes, early leave, overtime, working hours based on assigned work schedules. Database-level single-record enforcement per date.
7. **Leave Management**: Leave type configuration, employee leave applications with overlap detection, admin approval workflow (approve/reject with notes).
8. **Reports & Data Export**: 8 core reports (Daily, Monthly, Employee, Department, Late, Absence, Leave, Overtime) with multi-criteria filtering and CSV export.
9. **Dashboard & Analytics**: Role-specific dashboards with visual KPI cards, daily attendance summary charts, and actionable task lists.
10. **System Settings**: Database-backed dynamic config for company profile, default office hours, attendance grace rules, and localization.

---

## 3. Authentication & Authorization Flow

### Authentication Flow
- **Administrator Login**: Form submits `email` and `password`. Validated against `users` table where `role = 'admin'`. Redirects to `/admin/dashboard`.
- **Employee Login**: Form submits `employee_code` and `password`. Resolves `employee` by `employee_code`, links to `users` account, validates credentials. Redirects to `/employee/dashboard`.
- **Password Protection**: Passwords hashed using Bcrypt (rounds = 12).
- **Remember Me**: standard persistent token session guard.

### Authorization Flow
- **Middleware Layer**:
  - `auth`: Guarantees authenticated session.
  - `role:admin`: Restricts routes to users with `role === 'admin'`.
  - `role:employee`: Restricts routes to users with `role === 'employee'`.
- **Policy Layer**:
  - `EmployeePolicy`, `AttendancePolicy`, `LeavePolicy`, `DepartmentPolicy`, `DesignationPolicy`, `WorkSchedulePolicy`, `UserPolicy`, `SettingPolicy`.
  - Prevents non-admin access to core settings or other employees' confidential records.

---

## 4. Controller Structure
Controllers maintain single responsibility, handling input validation (via Form Requests) and returning Blade responses or CSV file downloads.

- `Auth\LoginController`: Handles login display, admin/employee login authentication, and logout.
- `Auth\ForgotPasswordController` & `Auth\ResetPasswordController`: Password recovery logic.
- `Admin\DashboardController`: Admin analytical metrics & charts.
- `Admin\EmployeeController`: Employee CRUD, search, filter, photo upload.
- `Admin\DepartmentController`: Department CRUD and status toggle.
- `Admin\DesignationController`: Designation CRUD and status toggle.
- `Admin\WorkScheduleController`: Schedule management & employee assignment.
- `Admin\AttendanceController`: Admin attendance override, view, and logs.
- `Admin\LeaveController`: Admin leave approval/rejection.
- `Admin\LeaveTypeController`: Leave type CRUD.
- `Admin\UserController`: System user management & password resets.
- `Admin\ReportController`: Multi-format report builder & CSV exporter.
- `Admin\SettingController`: System configuration store.
- `Employee\DashboardController`: Employee self-service dashboard (Check-in/out status, summary).
- `Employee\AttendanceController`: Employee check-in, check-out, and attendance history.
- `Employee\LeaveController`: Employee leave application submission, cancellation, history.
- `Employee\ProfileController`: Profile view and password change.

---

## 5. Service Layer Structure
Business logic resides in dedicated Service classes inside `app/Services`:

- **`AttendanceService`**:
  - `checkIn(Employee $employee, array $data)`
  - `checkOut(Employee $employee, array $data)`
  - `calculateMetrics(Attendance $attendance, WorkSchedule $schedule)`
  - `markAbsentEmployees(Carbon $date)`
- **`LeaveService`**:
  - `applyLeave(Employee $employee, array $data)`
  - `approveLeave(Leave $leave, User $admin, ?string $note)`
  - `rejectLeave(Leave $leave, User $admin, ?string $note)`
  - `cancelLeave(Leave $leave, Employee $employee)`
  - `hasOverlappingLeave(int $employeeId, string $startDate, string $endDate)`
- **`EmployeeService`**:
  - `createEmployee(array $data, ?UploadedFile $photo)`
  - `updateEmployee(Employee $employee, array $data, ?UploadedFile $photo)`
  - `generateEmployeeCode()`
- **`ReportService`**:
  - `generateDailyReport(array $filters)`
  - `generateMonthlyReport(array $filters)`
  - `generateEmployeeReport(array $filters)`
  - `generateDepartmentReport(array $filters)`
  - `generateLateReport(array $filters)`
  - `generateAbsenceReport(array $filters)`
  - `generateLeaveReport(array $filters)`
  - `generateOvertimeReport(array $filters)`
  - `exportToCsv(string $filename, array $headers, array $data)`
- **`DashboardService`**:
  - `getAdminDashboardMetrics()`
  - `getEmployeeDashboardMetrics(Employee $employee)`
- **`SettingsService`**:
  - `get(string $key, $default = null)`
  - `set(string $key, $value)`
  - `getAllSettings()`

---

## 6. Route & View Organization

### Routes (`routes/web.php`)
- `Guest Routes`: `/login`, `/forgot-password`, `/reset-password`
- `Admin Routes` (`/admin/*`, protected by `auth` & `role:admin`):
  - `/admin/dashboard`
  - `/admin/employees`
  - `/admin/departments`
  - `/admin/designations`
  - `/admin/work-schedules`
  - `/admin/attendance`
  - `/admin/leave-applications`
  - `/admin/leave-types`
  - `/admin/users`
  - `/admin/reports`
  - `/admin/settings`
- `Employee Routes` (`/employee/*`, protected by `auth` & `role:employee`):
  - `/employee/dashboard`
  - `/employee/check-in`
  - `/employee/check-out`
  - `/employee/attendance-history`
  - `/employee/apply-leave`
  - `/employee/leave-history`
  - `/employee/profile`

### Views (`resources/views/`)
- `layouts/`: `app.blade.php`, `guest.blade.php`, `navigation.blade.php`, `topbar.blade.php`
- `components/`: Blade UI components (`card`, `table`, `button`, `badge`, `modal`, `alert`, `form-group`)
- `admin/`: Modular Blade views per feature
- `employee/`: Self-service views
- `reports/`: Printable and filterable views
- `errors/`: Custom error layouts (`403`, `404`, `419`, `422`, `500`)

---

## 7. Security Approach
- **CSRF Protection**: All POST/PUT/DELETE forms include `@csrf`.
- **Server-Side Authorization**: Policies enforced in Controllers via `$this->authorize()` or `Gate::authorize()`.
- **Mass Assignment Protection**: `$fillable` arrays explicitly declared on all Eloquent models.
- **SQL Injection Prevention**: Eloquent parameter binding used exclusively.
- **XSS Prevention**: Blade `{{ $variable }}` escaping used for content output.
- **FileUpload Security**: Photo uploads restricted to images (`jpeg,png,jpg,webp`), max size 2MB, stored securely in public storage disk with hashed filenames.
- **Unique Attendance Constraint**: Database unique index `(employee_id, date)` guarantees integrity at DB level.

---

## 8. Testing Approach
Comprehensive automated tests in `tests/`:
- **Feature Tests**:
  - `AuthTest`: Login flows for Admin and Employee, password reset, logout.
  - `AuthorizationTest`: Policy checks and route access protection.
  - `DepartmentDesignationTest`: CRUD operations.
  - `WorkScheduleTest`: Shift configuration and assignment.
  - `EmployeeTest`: Employee creation, code auto-generation, update, deactivation.
  - `AttendanceTest`: Check-in, check-out, metrics calculation, duplicate check-in prevention.
  - `LeaveTest`: Application submission, overlap validation, approval/rejection logic.
  - `SettingsTest`: System configuration updates.
  - `DashboardTest`: Metric accuracy and dashboard rendering.
  - `ReportTest`: Report output and CSV exporter.
