# Web Engineering Lab Projects

This repository contains two academic web application development projects:

---

## 1. Responsive Student Registration Web Page
A fully responsive, accessible student registration web application built with vanilla web standards.

### Technologies:
- **HTML5:** Semantic elements (`<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`), accessible forms with explicit label associations, and `<fieldset>` with `<legend>` for radio buttons.
- **CSS3:** Responsive layout using CSS Grid and Flexbox, custom variables (`:root`), $48\text{px}$ touch targets, and media queries for mobile, tablet, and desktop viewports. Both external stylesheet (`styles.css`) and internal (`<style>`) implementations are supported.
- **JavaScript:** Client-side validation for all fields (RFC 5322 email regex, minimum 8-character password, required fields, date constraints), non-alert inline error messaging, real-time error clearance on input, accessible password reveal toggle, and dynamic DOM manipulation displaying submitted records in a live table with individual row deletion.

### Files:
- `index.html` — Main document with semantic landmarks and embedded internal stylesheet.
- `styles.css` — External responsive stylesheet.
- `script.js` — Client-side validation logic and DOM manipulation.

---

## 2. Student Result Portal (Laravel)
A web application built with the Laravel PHP framework located in the [`student portal`](./student%20portal) directory.

### Key Features:
- **Database Migrations:**
  - `students` table: `name`, `roll` (unique), `department`, `email` (unique).
  - `results` table: `student_id` (foreign key with cascade on delete), `course_code`, `marks`, `grade`.
- **Eloquent Relationships:**
  - `Student` model has a One-to-Many relationship with `Result` (`$student->results()`).
  - `Result` model belongs to `Student` (`$result->student()`).
- **Automated Grade Calculation:**
  - Converts numerical marks (0–100) automatically into letter grades ($A+, A, A-, B+, B, B-, C+, C, D, F$) and GPA ($4.00$ scale) both on the server (via model hooks & controller) and client-side via live input preview.
- **Resource Controller & Routing:**
  - `StudentController` provides complete CRUD operations (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) with search filters and pagination.
  - Common Blade layout (`resources/views/layouts/app.blade.php`) with flash messaging and error blocks.
- **Form Validation:**
  - Form Requests (`StoreStudentRequest`, `UpdateStudentRequest`, `StoreResultRequest`) enforce required fields, unique constraints, and numeric ranges.
- **Authentication Middleware Protection:**
  - Protected routes (`create`, `store`, `edit`, `update`, `destroy`, and adding/deleting course results) are guarded behind Laravel's `auth` middleware.
  - Public routes (`index`, `show`) allow guests to search and view student transcripts.

### Running the Laravel Application:
```bash
cd "student portal"
php artisan serve
```
Open `http://127.0.0.1:8000` in your web browser.

**Default Administrator Account:**
- Email: `admin@portal.com`
- Password: `password`
