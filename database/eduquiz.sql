DROP DATABASE IF EXISTS eduquiz_short;
CREATE DATABASE eduquiz_short CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eduquiz_short;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 email VARCHAR(120) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 phone VARCHAR(30) DEFAULT '',
 department VARCHAR(100) DEFAULT '',
 role ENUM('student','instructor','admin') NOT NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE courses (
 id INT AUTO_INCREMENT PRIMARY KEY,
 instructor_id INT NOT NULL,
 code VARCHAR(30) NOT NULL,
 title VARCHAR(120) NOT NULL,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE enrollments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 course_id INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_enroll (student_id,course_id),
 FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE quizzes (
 id INT AUTO_INCREMENT PRIMARY KEY,
 course_id INT NOT NULL,
 title VARCHAR(120) NOT NULL,
 is_published TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE questions (
 id INT AUTO_INCREMENT PRIMARY KEY,
 quiz_id INT NOT NULL,
 question_text TEXT NOT NULL,
 option_a VARCHAR(255) NOT NULL,
 option_b VARCHAR(255) NOT NULL,
 option_c VARCHAR(255) NOT NULL,
 option_d VARCHAR(255) NOT NULL,
 correct_option ENUM('A','B','C','D') NOT NULL,
 mark DECIMAL(6,2) NOT NULL DEFAULT 1,
 FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE attempts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 quiz_id INT NOT NULL,
 student_id INT NOT NULL,
 score DECIMAL(6,2) NOT NULL,
 total DECIMAL(6,2) NOT NULL,
 attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
 FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Plain-text passwords only because this is a local academic demo project.
INSERT INTO users(name,username,email,password,phone,department,role) VALUES
('System Admin','admin','admin@eduquiz.local','admin123','','Administration','admin'),
('Nadia Instructor','nadia','instructor@eduquiz.local','instructor123','','CSE','instructor'),
('Ayesha Student','ayesha','student@eduquiz.local','student123','','CSE','student');

INSERT INTO courses(instructor_id,code,title,description) VALUES
(2,'CSE101','Introduction to Computing','A small demo course for the short project.');
INSERT INTO enrollments(student_id,course_id) VALUES(3,1);
INSERT INTO quizzes(course_id,title,is_published) VALUES(1,'Quiz 1',1);
INSERT INTO questions(quiz_id,question_text,option_a,option_b,option_c,option_d,correct_option,mark) VALUES
(1,'Which language runs on the server in this project?','HTML','PHP','CSS','SQL','B',1),
(1,'Which database is used?','MySQL','MongoDB','Oracle','SQLite','A',1);
