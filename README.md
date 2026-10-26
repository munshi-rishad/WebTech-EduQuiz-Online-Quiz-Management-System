# EduQuiz – Online Quiz Management System

A PHP and MySQL web application for the **Web Technologies (CSC 3215)** course.
Admins, Instructors and Students log in to their own panel: instructors create courses and quizzes,
students enroll and take quizzes, and admins manage users and courses.

## Features

- One login and registration system for every user (Student or Instructor sign-up).
- Remember Me, reset password, change password, edit profile and delete account.
- Live AJAX check for username, email and phone while registering.
- **Student:** browse and enroll in courses, take quizzes, see results and average score.
- **Instructor:** create courses and quizzes, add questions, publish quizzes, see student results with Pass / Fail (pass mark 40%).
- **Admin:** activate / deactivate / delete users, remove courses, see a system report.

## Technologies Used

- PHP 8 and MySQL
- HTML, CSS and JavaScript (AJAX)
- XAMPP (Apache + MySQL)
- MVC (Model–View–Controller) architecture

## Project Structure (MVC)

```
WebTech-EduQuiz-Online-Quiz-Management-System/
├── index.php                 # start page: sends the user to Login or to their dashboard
├── model/                    # MODEL: every database query
│   ├── DatabaseConnection.php   # connection + user/account queries
│   ├── AdminModel.php
│   ├── InstructorModel.php
│   └── StudentModel.php
├── Auth/                     # pages shared by all users
│   ├── View/                 # VIEW: Login, Registration, ForgotPassword, Profile, Password
│   ├── Control/              # CONTROLLER: checks the forms, AJAX checks, logout
│   └── JavaScript/           # form validation + AJAX
├── Admin/                    # Admin panel   (MainPage.php, View/, Control/)
├── Instructor/               # Instructor panel (MainPage.php, View/, Control/)
├── Student/                  # Student panel (MainPage.php, View/, Control/)
├── css/style.css
├── database/eduquiz.sql      # creates the database with demo data
├── report/                   # project report (LaTeX source + PDF)
└── Screenshots/
```

- **Model** – only talks to the database.
- **View** – only shows the page (HTML).
- **Controller** – reads the form, calls the Model and decides what the View shows.

## Requirements

- **XAMPP** (Apache + MySQL + PHP 8) – download from [apachefriends.org](https://www.apachefriends.org/)
- **Git** – download from [git-scm.com](https://git-scm.com/downloads)
- A web browser (Chrome, Edge, Firefox)
- (Optional) **VS Code** to read or edit the code

## Clone the Project

Open a terminal inside the XAMPP `htdocs` folder (usually `C:\xampp\htdocs`) and run:

```bash
git clone https://github.com/munshi-rishad/WebTech-EduQuiz-Online-Quiz-Management-System.git
```

## Setup and Run

1. Install XAMPP, open the **XAMPP Control Panel** and click **Start** for **Apache** and **MySQL**.
2. Clone the project into `C:\xampp\htdocs` (see above).
3. Open **http://localhost/phpmyadmin**, click the **Import** tab, choose `database/eduquiz.sql` and click **Import**.
   This creates the `eduquiz_short` database with demo data.
4. Open the project in the browser:
   **http://localhost/WebTech-EduQuiz-Online-Quiz-Management-System/**
5. Log in with one of the demo accounts below.

> If your MySQL user or password is not the XAMPP default (`root` with no password), change them in `model/DatabaseConnection.php`.

### Demo Accounts

- **Admin:** `admin@eduquiz.local` / `admin123`
- **Instructor:** `instructor@eduquiz.local` / `instructor123`
- **Student:** `student@eduquiz.local` / `student123`

New Student and Instructor accounts can be created from the **Register** page.

## Project Report

The report is written in LaTeX: [report/EduQuiz_Report.tex](report/EduQuiz_Report.tex) · PDF: [report/EduQuiz_Report.pdf](report/EduQuiz_Report.pdf)

## Screenshots

### 1. Login Page

One login page for Admin, Instructor and Student, with Remember Me and Forgot password.

<p align="center">
  <img src="Screenshots/login_page.png" alt="Login Page" width="800">
</p>

### 2. Registration with Live AJAX Check

Create a Student or Instructor account; username, email and phone are checked while typing.

<p align="center">
  <img src="Screenshots/registration_ajax_check.png" alt="Registration with Live AJAX Check" width="800">
</p>

### 3. Student Dashboard

Enrolled courses, quiz attempts and average score.

<p align="center">
  <img src="Screenshots/student_dashboard.png" alt="Student Dashboard" width="800">
</p>

### 4. Browse and Enroll in Courses

Students see every course and enroll with one click.

<p align="center">
  <img src="Screenshots/student_available_courses.png" alt="Browse and Enroll in Courses" width="800">
</p>

### 5. Take a Quiz

Multiple-choice quiz of an enrolled course.

<p align="center">
  <img src="Screenshots/student_take_quiz.png" alt="Take a Quiz" width="800">
</p>

### 6. Quiz Result History

Score, percent and date of every quiz the student took.

<p align="center">
  <img src="Screenshots/student_quiz_result_history.png" alt="Quiz Result History" width="800">
</p>

### 7. Instructor – Manage Quizzes and Questions

Create quizzes, publish them, see the questions of a quiz and add new questions.

<p align="center">
  <img src="Screenshots/instructor_create_quiz_add_questions.png" alt="Instructor – Manage Quizzes and Questions" width="800">
</p>

### 8. Instructor – Student Results

Results of the instructor's quizzes with Pass / Fail (pass mark 40%).

<p align="center">
  <img src="Screenshots/instructor_student_quiz_results.png" alt="Instructor – Student Results" width="800">
</p>

### 9. Admin – User Management

Activate, deactivate or delete user accounts.

<p align="center">
  <img src="Screenshots/admin_user_management.png" alt="Admin – User Management" width="800">
</p>

### 10. Admin – System Report

Totals of students, instructors, courses, quizzes and attempts, plus recent attempts.

<p align="center">
  <img src="Screenshots/admin_system_report.png" alt="Admin – System Report" width="800">
</p>
