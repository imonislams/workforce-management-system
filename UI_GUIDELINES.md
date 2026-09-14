# Attendance Management System — UI Guidelines

## 1. Design Goal

Create a modern, clean, professional attendance management dashboard.

The UI should feel like a real SaaS/business application.

Avoid:

* Excessive colors
* Unnecessary animations
* Crowded layouts
* Huge cards
* Inconsistent spacing
* Inconsistent buttons
* Conflicting role labels

---

# 2. Layout

Desktop:

```text
┌─────────────────────────────────────────────┐
│ Sidebar │ Topbar                            │
│         ├───────────────────────────────────┤
│         │                                   │
│         │ Main Content                      │
│         │                                   │
│         │                                   │
└─────────────────────────────────────────────┘
```

Mobile:

```text
┌──────────────────────────────┐
│ Topbar + Menu                │
├──────────────────────────────┤
│                              │
│ Main Content                 │
│                              │
└──────────────────────────────┘
```

---

# 3. Sidebar

## Administrator

```text
Dashboard

Employees
Departments
Designations

Attendance
  Today's Attendance
  Attendance Records
  Reports

Leave Management
  Leave Applications
  Leave Types

Work Schedules

Users

Reports

Settings
```

## Employee

```text
Dashboard

My Attendance
Attendance History

My Leave
Apply Leave
Leave History

My Profile
```

---

# 4. Sidebar User Section

Administrator:

```text
John Admin
Administrator
```

Employee:

```text
Rahim Ahmed
Employee
EMP-0001
```

Never show:

```text
Admin Employee Administrator Employee
```

Role information must be rendered based on the authenticated user's actual role.

---

# 5. Topbar

Topbar contains:

* Page title
* Search if required
* Notification icon
* User dropdown
* Logout

Role badge:

Administrator:

```text
Admin
```

Employee:

```text
Employee
```

Only one role badge should be displayed.

---

# 6. Dashboard Cards

Admin dashboard cards:

```text
Total Employees
Present Today
Absent Today
Late Today
On Leave
Departments
```

Cards should contain:

* Label
* Number
* Small supporting information
* Appropriate icon

Avoid excessive visual decoration.

---

# 7. Employee Dashboard

Employee dashboard should prioritize today's attendance.

Example:

```text
Good Morning, Rahim

Today's Attendance

Check In
09:05 AM

Check Out
Not checked yet

Working Hours
04:35

Status
Present
```

Main action:

```text
CHECK IN
```

After check-in:

```text
CHECK OUT
```

After checkout:

```text
Attendance Completed
```

---

# 8. Tables

Tables must support:

* Search
* Filtering
* Pagination
* Sorting where useful
* Responsive layout

Employee table:

```text
Employee ID
Name
Department
Designation
Joining Date
Status
Actions
```

Attendance table:

```text
Date
Employee
Check In
Check Out
Working Hours
Status
```

Leave table:

```text
Employee
Leave Type
Start Date
End Date
Days
Status
Actions
```

---

# 9. Status Badges

Use consistent status badges.

Examples:

```text
Present
Late
Absent
Leave
Pending
Approved
Rejected
Active
Inactive
```

Each status must have consistent visual treatment throughout the application.

---

# 10. Forms

Forms should:

* Use clear labels
* Show required fields
* Show validation errors
* Preserve entered values after validation errors
* Group related fields

Employee form:

```text
Personal Information

First Name
Last Name
Email
Phone
Date of Birth
Gender
Address

Employment Information

Employee Code
Department
Designation
Joining Date
Work Schedule
Employment Status

Account Information

Password
Confirm Password
```

---

# 11. Buttons

Primary actions:

```text
Create
Save
Update
Check In
Check Out
Approve
```

Secondary actions:

```text
Cancel
Back
Reset
```

Danger actions:

```text
Delete
Reject
Deactivate
```

Use confirmation dialogs for destructive actions.

---

# 12. Notifications

Use toast/alert notifications for:

* Created successfully
* Updated successfully
* Deleted successfully
* Login successful
* Attendance checked in
* Attendance checked out
* Leave submitted
* Leave approved
* Leave rejected

Messages should be short and clear.

Example:

```text
Employee created successfully.
```

---

# 13. Empty States

Do not show blank tables.

Example:

```text
No attendance records found.

Try changing your filters or date range.
```

---

# 14. Loading States

Buttons should provide feedback during requests.

Example:

```text
Saving...
```

instead of allowing duplicate submissions.

---

# 15. Confirmation Dialogs

Before deleting:

```text
Are you sure?

This action cannot be undone.

Cancel
Delete
```

Before rejecting leave:

```text
Reject Leave Application?

Reason
[................]

Cancel
Reject
```

---

# 16. Responsive Design

Must work correctly on:

* Desktop
* Laptop
* Tablet
* Mobile

Test widths:

```text
320px
375px
768px
1024px
1280px
1440px+
```

Tables should not break the page on mobile.

Use horizontal scrolling or responsive table layouts where appropriate.

---

# 17. Dashboard Charts

Admin dashboard may include:

### Monthly Attendance

Show:

```text
Present
Absent
Late
Leave
```

### Department Distribution

Show employee distribution by department.

Charts should remain readable on mobile.

---

# 18. Profile UI

Employee profile:

```text
Profile Photo

Name
Employee Code
Email
Phone

Department
Designation
Joining Date
Employment Status
```

Do not allow employees to edit restricted fields.

---

# 19. Settings UI

Use tabs or cards:

```text
General
Attendance
Localization
System
```

General:

```text
Company Name
Email
Phone
Address
Logo
```

Attendance:

```text
Office Start Time
Office End Time
Grace Period
Weekend
```

Localization:

```text
Timezone
Date Format
Currency
```

---

# 20. Accessibility

Use:

* Proper labels
* Keyboard-friendly controls
* Good contrast
* Focus states
* Semantic HTML
* Accessible buttons

Do not use color as the only way to communicate status.

---

# 21. Component Reusability

Create reusable Blade components for:

```text
Button
Card
Modal
Alert
Badge
Table
Input
Select
Pagination
Dropdown
```

Avoid duplicating markup unnecessarily.

---

# 22. UI Consistency Rules

Every page should follow:

```text
Same sidebar
Same topbar
Same page header
Same button styles
Same table style
Same form style
Same spacing
Same status badges
```

---

# 23. Final UI Quality Checklist

Before completing the project verify:

[ ] Sidebar works
[ ] Mobile menu works
[ ] Topbar works
[ ] Admin role displays correctly
[ ] Employee role displays correctly
[ ] No conflicting badges
[ ] Dashboard cards align
[ ] Tables responsive
[ ] Forms responsive
[ ] Buttons consistent
[ ] Modals work
[ ] Toast messages work
[ ] Validation messages work
[ ] Empty states work
[ ] Loading states work
[ ] Charts responsive
[ ] Mobile layout works
[ ] No horizontal page overflow
[ ] No broken icons
[ ] No console errors
