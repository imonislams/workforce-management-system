# Workforce Management System (WMS)

Production-ready Workforce Management System built with **Laravel 12**, **PHP 8.3**, **Tailwind CSS**, and **Alpine.js**.

---

## Features

- **Dual-Role Authentication**:
  - Administrator login via Email + Password.
  - Employee login via Employee Code + Password.
- **Employee Lifecycle Management**: Full CRUD, unique employee code generation, status management, profile photo uploads, department & designation assignments.
- **Attendance Management**: Single check-in / check-out per date, automatic working hour computation, late minutes, early leave, overtime calculation based on shift work schedules.
- **Leave Management**: Leave type setup, employee leave applications with date range overlap validation, admin approval workflow with notes.
- **Work Schedule Management**: Configurable shift templates (start/end times, break times, grace period).
- **Reports & Analytics**: 8 comprehensive reports (Daily, Monthly, Employee, Department, Late, Absence, Leave, Overtime) with multi-criteria filtering and CSV exports.
- **System Settings**: Database-backed company profile, default working hours, attendance rules, and localization settings.
- **Role-Based Access Control**: Server-side Laravel Policies and Role Middleware.

---

## Installation & Setup

### Prerequisites
- PHP >= 8.2 (PHP 8.3 recommended)
- Composer
- Node.js & npm
- SQLite or MySQL database

### Setup Steps

1. **Clone repository & install dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Migration & Seeding**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Asset Compilation**:
   ```bash
   npm run build
   ```

5. **Run Application**:
   ```bash
   php artisan serve
   ```

---

## Default Credentials

### Administrator
- **Email**: `admin@example.com`
- **Password**: `password`

### Sample Employee
- **Employee Code**: `EMP-0001`
- **Password**: `password`

---

## Running Tests

Execute automated feature and unit test suites:
```bash
php artisan test
```
