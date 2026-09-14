# Workforce Management System — Database Schema Specification

## 1. Entity Relationship Overview

The database uses InnoDB engine (MySQL) or SQLite with foreign key constraints enabled.

```
       +--------------------+
       |       users        |
       +--------------------+
                 | 1:1
                 v
       +--------------------+         +--------------------+
       |     employees      | --------> |    departments     |
       +--------------------+ 1:N     +--------------------+
         |   |            | 1:N
         |   |            +---------> +--------------------+
         |   |                        |    designations    |
         |   v 1:N                    +--------------------+
         | +--------------------+
         | |   work_schedules   |
         | +--------------------+
         |
         +----------------------------------+
         | 1:N                              | 1:N
         v                                  v
+--------------------+            +--------------------+
|     attendance     |            |       leaves       |
+--------------------+            +--------------------+
                                            | N:1
                                            v
                                  +--------------------+
                                  |    leave_types     |
                                  +--------------------+
```

---

## 2. Table Specifications

### 2.1 `users`
Stores user credentials for both Administrators and Employees.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(255), Not Null)
- `email` (VARCHAR(255), Unique, Not Null)
- `email_verified_at` (TIMESTAMP, Nullable)
- `password` (VARCHAR(255), Not Null)
- `role` (ENUM('admin', 'employee'), Default 'employee', Not Null, Index)
- `status` (ENUM('active', 'inactive'), Default 'active', Not Null, Index)
- `remember_token` (VARCHAR(100), Nullable)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

### 2.2 `departments`
Master record for organizational departments.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(255), Unique, Not Null)
- `description` (TEXT, Nullable)
- `status` (ENUM('active', 'inactive'), Default 'active', Not Null, Index)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

### 2.3 `designations`
Master record for job titles and designations.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(255), Unique, Not Null)
- `description` (TEXT, Nullable)
- `status` (ENUM('active', 'inactive'), Default 'active', Not Null, Index)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

### 2.4 `work_schedules`
Shift schedules detailing work hours, break times, and grace periods.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(255), Not Null)
- `start_time` (TIME, Not Null) -- e.g. 09:00:00
- `end_time` (TIME, Not Null) -- e.g. 17:00:00
- `break_start` (TIME, Nullable) -- e.g. 13:00:00
- `break_end` (TIME, Nullable) -- e.g. 14:00:00
- `grace_period` (INT UNSIGNED, Default 15, Not Null) -- Minutes late allowed before marked Late
- `status` (ENUM('active', 'inactive'), Default 'active', Not Null, Index)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

### 2.5 `employees`
Core employee profile information.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `user_id` (BIGINT UNSIGNED, Unique, Nullable, Foreign Key -> `users.id` ON DELETE SET NULL)
- `employee_code` (VARCHAR(50), Unique, Not Null, Index) -- e.g. EMP-0001
- `first_name` (VARCHAR(255), Not Null)
- `last_name` (VARCHAR(255), Not Null)
- `email` (VARCHAR(255), Unique, Not Null)
- `phone` (VARCHAR(50), Nullable)
- `date_of_birth` (DATE, Nullable)
- `gender` (ENUM('male', 'female', 'other'), Nullable)
- `address` (TEXT, Nullable)
- `department_id` (BIGINT UNSIGNED, Nullable, Foreign Key -> `departments.id` ON DELETE SET NULL, Index)
- `designation_id` (BIGINT UNSIGNED, Nullable, Foreign Key -> `designations.id` ON DELETE SET NULL, Index)
- `work_schedule_id` (BIGINT UNSIGNED, Nullable, Foreign Key -> `work_schedules.id` ON DELETE SET NULL, Index)
- `joining_date` (DATE, Not Null)
- `employment_status` (ENUM('active', 'inactive', 'terminated'), Default 'active', Not Null, Index)
- `photo` (VARCHAR(255), Nullable)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)
- `deleted_at` (TIMESTAMP, Nullable) -- Soft delete support

### 2.6 `attendance`
Daily attendance records for employees. Strictly one record per employee per date.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `employee_id` (BIGINT UNSIGNED, Not Null, Foreign Key -> `employees.id` ON DELETE CASCADE, Index)
- `date` (DATE, Not Null, Index)
- `check_in` (DATETIME, Nullable)
- `check_out` (DATETIME, Nullable)
- `status` (ENUM('present', 'late', 'absent', 'half_day', 'leave', 'holiday'), Not Null, Index)
- `late_minutes` (INT UNSIGNED, Default 0, Not Null)
- `early_leave_minutes` (INT UNSIGNED, Default 0, Not Null)
- `overtime_minutes` (INT UNSIGNED, Default 0, Not Null)
- `working_hours` (DECIMAL(5, 2), Default 0.00, Not Null)
- `notes` (TEXT, Nullable)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)
- **Unique Constraint**: `UNIQUE (employee_id, date)`

### 2.7 `leave_types`
Configuration of available leave types.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(255), Unique, Not Null) -- e.g. Sick Leave, Annual Leave
- `code` (VARCHAR(50), Unique, Not Null) -- e.g. SL, AL
- `max_days` (INT UNSIGNED, Default 14, Not Null)
- `description` (TEXT, Nullable)
- `status` (ENUM('active', 'inactive'), Default 'active', Not Null, Index)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

### 2.8 `leaves`
Leave application records and approval history.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `employee_id` (BIGINT UNSIGNED, Not Null, Foreign Key -> `employees.id` ON DELETE CASCADE, Index)
- `leave_type_id` (BIGINT UNSIGNED, Not Null, Foreign Key -> `leave_types.id` ON DELETE CASCADE, Index)
- `start_date` (DATE, Not Null, Index)
- `end_date` (DATE, Not Null, Index)
- `total_days` (INT UNSIGNED, Not Null)
- `reason` (TEXT, Not Null)
- `status` (ENUM('pending', 'approved', 'rejected', 'cancelled'), Default 'pending', Not Null, Index)
- `admin_note` (TEXT, Nullable)
- `approved_by` (BIGINT UNSIGNED, Nullable, Foreign Key -> `users.id` ON DELETE SET NULL)
- `approved_at` (DATETIME, Nullable)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

### 2.9 `settings`
System settings stored as key-value pairs.
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `key` (VARCHAR(255), Unique, Not Null, Index)
- `value` (TEXT, Nullable)
- `created_at` (TIMESTAMP, Nullable)
- `updated_at` (TIMESTAMP, Nullable)

---

## 3. Key Indexes & Rules
1. **Unique Constraint**: `attendance (employee_id, date)` strictly prevents duplicate attendance entries for an employee on the same date.
2. **Unique Constraints**:
   - `users.email`
   - `employees.employee_code`
   - `employees.email`
   - `departments.name`
   - `designations.name`
   - `leave_types.name`, `leave_types.code`
   - `settings.key`
3. **Foreign Key Integrity**:
   - Deleting an employee cascades to delete their `attendance` and `leaves` records.
   - Deleting department, designation, or schedule sets corresponding employee fields to `NULL`.
