# Attendance Management System — Database Design

## 1. Database

Database engine:

```text
MySQL
```

Use:

* Foreign keys
* Indexes
* Unique constraints
* Timestamps
* Soft deletes where appropriate

---

# 2. Entity Relationship Overview

```text
users
  |
  | 1:1
  v
employees
  |
  +------ departments
  |
  +------ designations
  |
  +------ attendance
  |
  +------ leaves
  |
  +------ work_schedules
```

---

# 3. users

Purpose:

Stores authentication accounts.

Fields:

```text
id
name
email
password
role
email_verified_at
remember_token
created_at
updated_at
```

Role:

```text
admin
employee
```

Rules:

* Email must be unique.
* Admin does not require employee_id.
* Employee must have a linked employee profile.

---

# 4. employees

Fields:

```text
id
employee_code
user_id
first_name
last_name
email
phone
date_of_birth
gender
address
department_id
designation_id
joining_date
employment_status
profile_photo
basic_salary
work_schedule_id
created_at
updated_at
deleted_at
```

Constraints:

```text
employee_code UNIQUE
user_id UNIQUE
```

Relationships:

```text
Employee belongsTo User
Employee belongsTo Department
Employee belongsTo Designation
Employee belongsTo WorkSchedule
Employee hasMany Attendance
Employee hasMany Leave
```

---

# 5. departments

Fields:

```text
id
name
description
status
created_at
updated_at
```

Relationship:

```text
Department hasMany Employees
```

---

# 6. designations

Fields:

```text
id
name
description
status
created_at
updated_at
```

Relationship:

```text
Designation hasMany Employees
```

---

# 7. work_schedules

Fields:

```text
id
name
start_time
end_time
break_start
break_end
grace_period
status
created_at
updated_at
```

Example:

```text
Office Schedule
09:00 - 17:00
Grace Period: 15 minutes
```

Relationship:

```text
WorkSchedule hasMany Employees
```

---

# 8. attendance

Fields:

```text
id
employee_id
date
check_in
check_out
status
late_minutes
early_leave_minutes
overtime_minutes
working_hours
notes
created_at
updated_at
```

Unique constraint:

```text
employee_id + date
```

Relationship:

```text
Attendance belongsTo Employee
```

---

# 9. attendance status

Allowed values:

```text
present
late
absent
half_day
leave
holiday
```

---

# 10. leave_types

Fields:

```text
id
name
description
default_days
status
created_at
updated_at
```

Examples:

```text
Casual Leave
Sick Leave
Annual Leave
Emergency Leave
```

---

# 11. leaves

Fields:

```text
id
employee_id
leave_type_id
start_date
end_date
total_days
reason
status
admin_note
approved_by
approved_at
created_at
updated_at
```

Status:

```text
pending
approved
rejected
cancelled
```

Relationships:

```text
Leave belongsTo Employee
Leave belongsTo LeaveType
Leave belongsTo User through approved_by
```

---

# 12. settings

Fields:

```text
id
key
value
type
created_at
updated_at
```

Examples:

```text
company_name
company_email
company_phone
company_address
timezone
date_format
office_start_time
office_end_time
grace_period
currency
```

Settings should not be hardcoded into controllers or Blade files.

---

# 13. notifications

Use Laravel's notification structure if database notifications are required.

Recommended fields:

```text
id
type
notifiable_type
notifiable_id
data
read_at
created_at
updated_at
```

---

# 14. Indexes

Recommended indexes:

```text
users.email

employees.employee_code
employees.user_id
employees.department_id
employees.designation_id

attendance.employee_id
attendance.date
attendance.employee_id + date

leaves.employee_id
leaves.leave_type_id
leaves.status
leaves.start_date
leaves.end_date
```

---

# 15. Important Database Rules

## Admin

An admin user can exist without an employee record.

```text
users.role = admin
employees = NULL
```

This is valid.

---

## Employee

An employee user must have an employee record.

```text
users.role = employee
employees.user_id = users.id
```

---

# 16. Data Integrity

Use foreign keys.

Example:

```text
employees.department_id
        ↓
departments.id
```

When deleting related records, choose appropriate behavior.

Avoid accidental cascading deletion of important attendance records.

---

# 17. Migration Order

Recommended migration order:

```text
users
departments
designations
work_schedules
employees
leave_types
attendance
leaves
settings
notifications
```

---

# 18. Seeder Order

Seed:

```text
Admin
Departments
Designations
Work Schedules
Employees
Employee Users
Leave Types
Attendance
Leaves
Settings
```

Default Admin:

```text
Email:
admin@example.com

Password:
password
```

---

# 19. Database Testing

Tests must verify:

* Unique employee code
* Unique email
* Foreign keys
* Admin without employee profile
* Employee with employee profile
* One attendance record per employee per date
* Valid leave date range
* Valid attendance relationship
