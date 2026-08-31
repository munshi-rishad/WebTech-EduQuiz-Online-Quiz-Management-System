<div align="center">

# 🎓 EduQuiz

### Online Quiz & Course Management System

A PHP and MySQL based web application for managing courses, quizzes, students, instructors and quiz results.

<br>

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-AJAX-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

</div>

---

## 📌 About EduQuiz

**EduQuiz** is an online quiz and course management system developed using PHP, MySQL, JavaScript, AJAX, HTML5 and CSS3.

The system provides separate panels for:

- 👨‍🎓 Students
- 👨‍🏫 Instructors
- 🛡️ Administrators

Students can enroll in courses, take published quizzes and view their results.

Instructors can create courses, create quizzes, add questions and view student results.

Administrators can manage users, control courses and monitor the system.

---

# ✨ Features

## 👨‍🎓 Student Panel

- 📊 Student Dashboard
- 📚 Browse Available Courses
- 📝 View Published Quizzes
- ✍️ Take Quizzes
- 🎯 Automatic Score Calculation
- 📈 View Quiz Result History
- 👤 Manage Profile
- 🔐 Change Password
- 📖 Enroll in Courses

---

## 👨‍🏫 Instructor Panel

- 📊 Instructor Dashboard
- 📚 Create Courses
- 🗂️ Manage Courses
- 📝 Create Quizzes
- ❓ Add Multiple Choice Questions
- 📢 Publish / Unpublish Quizzes
- 📊 View Student Quiz Results
- 🗑️ Delete Courses

---

## 🛡️ Admin Panel

- 📊 Admin Dashboard
- 👥 User Management
- ✅ Activate / Deactivate Users
- 🗑️ Delete Users
- 📚 Course Control
- 📈 System Reports
- 👤 Profile Management
- 🔐 Password Management

---

# 🖥️ Project Screenshots

## 🔐 Authentication

<table>
<tr>
<td align="center">
<b>Login Page</b><br><br>
<img src="Screenshots/login_page.png" width="100%">
</td>

<td align="center">
<b>Registration Page</b><br><br>
<img src="Screenshots/registration_page.png" width="100%">
</td>
</tr>
</table>

---

# 👨‍🎓 Student Panel

<table>
<tr>
<td align="center">
<b>Student Dashboard</b><br><br>
<img src="Screenshots/student_dashboard.png" width="100%">
</td>

<td align="center">
<b>Available Courses</b><br><br>
<img src="Screenshots/student_available_courses.png" width="100%">
</td>
</tr>

<tr>
<td align="center">
<b>Published Quizzes</b><br><br>
<img src="Screenshots/student_published_quizzes.png" width="100%">
</td>

<td align="center">
<b>Take Quiz</b><br><br>
<img src="Screenshots/student_take_quiz.png" width="100%">
</td>
</tr>

<tr>
<td align="center">
<b>Quiz Result History</b><br><br>
<img src="Screenshots/student_quiz_result_history.png" width="100%">
</td>

<td align="center">
<b>Student Profile</b><br><br>
<img src="Screenshots/student_profile.png" width="100%">
</td>
</tr>

<tr>
<td align="center">
<b>Change Password</b><br><br>
<img src="Screenshots/student_change_password.png" width="100%">
</td>

<td></td>
</tr>
</table>

---

# 👨‍🏫 Instructor Panel

<table>
<tr>
<td align="center">
<b>Instructor Dashboard</b><br><br>
<img src="Screenshots/instructor_dashboard.png" width="100%">
</td>

<td align="center">
<b>Manage Courses</b><br><br>
<img src="Screenshots/instructor_manage_courses.png" width="100%">
</td>
</tr>

<tr>
<td align="center">
<b>Manage Quizzes</b><br><br>
<img src="Screenshots/instructor_manage_quizzes.png" width="100%">
</td>

<td align="center">
<b>Create Quiz & Add Questions</b><br><br>
<img src="Screenshots/instructor_create_quiz_add_questions.png" width="100%">
</td>
</tr>

<tr>
<td align="center">
<b>Student Quiz Results</b><br><br>
<img src="Screenshots/instructor_student_quiz_results.png" width="100%">
</td>

<td></td>
</tr>
</table>

---

# 🛡️ Admin Panel

<table>
<tr>
<td align="center">
<b>Admin Dashboard</b><br><br>
<img src="Screenshots/admin_dashboard.png" width="100%">
</td>

<td align="center">
<b>User Management</b><br><br>
<img src="Screenshots/admin_user_management.png" width="100%">
</td>
</tr>

<tr>
<td align="center">
<b>Course Control</b><br><br>
<img src="Screenshots/admin_course_control.png" width="100%">
</td>

<td align="center">
<b>System Report</b><br><br>
<img src="Screenshots/admin_system_report.png" width="100%">
</td>
</tr>
</table>

---

# 🧰 Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Server-side application logic |
| MySQL | Database management |
| HTML5 | Web page structure |
| CSS3 | User interface design |
| JavaScript | Client-side validation |
| AJAX | Asynchronous availability checking |
| PHP Sessions | Authentication and role management |
| XAMPP | Local development environment |

---

# ⚡ AJAX & Validation

EduQuiz demonstrates AJAX-based validation using JavaScript `XMLHttpRequest`.

### AJAX Features

- ✅ Username availability checking
- ✅ Email availability checking
- ✅ Phone availability checking
- ✅ No page reload required

### Main AJAX Files

```text
Admin/
├── Control/
│   ├── CheckUser.php
│   ├── CheckEmail.php
│   └── CheckPhone.php
│
└── JavaScript/
    └── CheckUser.js
