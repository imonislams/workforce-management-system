# Workforce Management System — UI/UX Guidelines

## 1. Design System & Theme
- **Color Palette**:
  - Primary / Brand: Indigo / Slate (`bg-indigo-600`, `text-indigo-600`, `hover:bg-indigo-700`)
  - Neutral Background: Gray 50/100 (`bg-gray-50`, `bg-gray-100`)
  - Surface Cards: Pure White (`bg-white` with `shadow-sm rounded-xl border border-gray-200/80`)
  - Status Colors:
    - Success / Active / Present: Emerald (`bg-emerald-50 text-emerald-700 border-emerald-200`)
    - Warning / Late / Pending: Amber (`bg-amber-50 text-amber-700 border-amber-200`)
    - Danger / Absent / Rejected: Rose / Red (`bg-rose-50 text-rose-700 border-rose-200`)
    - Info / Neutral / Inactive: Slate (`bg-slate-50 text-slate-700 border-slate-200`)

---

## 2. Layout Architecture
- **Sidebar Navigation**:
  - Left-hand vertical bar (`w-64`), collapsible on mobile using Alpine.js (`x-data="{ sidebarOpen: false }"`).
  - Admin view includes links for Dashboard, Employees, Departments, Designations, Attendance, Leave Management, Work Schedules, Users, Reports, and Settings.
  - Employee view includes links for Dashboard, My Attendance, Attendance History, My Leave, Apply Leave, Leave History, and My Profile.
- **Topbar**:
  - Contains toggle button for mobile navigation, page header title, current user avatar/name badge, and a user dropdown menu (Profile link, Change Password, Logout).
- **Content Area**:
  - Max-width responsive container (`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8`).

---

## 3. UI Components Standards
- **Cards & Widgets**:
  - KPI Stat Cards display metric title, value, icon, and contextual indicator.
- **Data Tables**:
  - Styled with clean padding (`px-6 py-4`), hover states (`hover:bg-gray-50/50`), column sorting headers, status badges, and pagination footer.
- **Forms & Input Fields**:
  - Standardized form controls (`border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm`).
  - Validation errors highlighted in red (`text-red-600 text-sm mt-1`).
- **Modals & Dialogs**:
  - Backdrop overlays (`fixed inset-0 bg-gray-900/50 backdrop-blur-sm`).
  - Animated Alpine.js show/hide transitions.
- **Notifications & Alerts**:
  - Toast notifications triggered on flash session events (`success`, `error`, `warning`).

---

## 4. Responsive & Mobile Behavior
- Fully responsive across desktop (>= 1024px), tablet (768px - 1023px), and mobile (< 768px).
- Mobile view features drawer sidebar navigation and stacked form fields.
