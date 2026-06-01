-- Student Course Enrollment System
-- Run this file once to set up the database

-- Create and select the database
CREATE DATABASE IF NOT EXISTS enrollment_db;
USE enrollment_db;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    student_id   INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    email        VARCHAR(100)
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    course_id    INT AUTO_INCREMENT PRIMARY KEY,
    course_name  VARCHAR(100) NOT NULL,
    credits      INT NOT NULL DEFAULT 3
);

-- Enrollments table (bridge between students and courses)
CREATE TABLE IF NOT EXISTS enrollments (
    enrollment_id  INT AUTO_INCREMENT PRIMARY KEY,
    student_id     INT NOT NULL,
    course_id      INT NOT NULL,
    enrolled_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id)  REFERENCES courses(course_id)   ON DELETE CASCADE,
    UNIQUE KEY no_duplicate (student_id, course_id)
);

-- Seed some sample students
INSERT INTO students (name, email) VALUES
    ('Alice Johnson', 'alice@example.com'),
    ('Bob Smith',     'bob@example.com'),
    ('Carol White',   'carol@example.com');

-- Seed some sample courses
INSERT INTO courses (course_name, credits) VALUES
    ('Introduction to Programming', 3),
    ('Database Systems',            3),
    ('Web Development',             3),
    ('Computer Networks',           3);

CREATE TABLE IF NOT EXISTS members (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    student_id VARCHAR(20)  NOT NULL,
    email      VARCHAR(100) NOT NULL,
    role       VARCHAR(100) NOT NULL,
    bio        TEXT
);

INSERT INTO members (name, student_id, email, role, bio) VALUES
    ('Jun',           '413856039', 'chinjunsi90@gmail.com',    'Project Lead',                        'Hello, I am Jun and I love math.'),
    ('Jordan',        '413856070', 'xxjordanxx88@gmail.com',   'Project Initiator & Backend Developer','Hello I am Jordan! I love to study!'),
    ('Terence Tseng', '413856013', 'terencet.301@gmail.com',   'Frontend Developer',                  'Hi I am Terence studying at TKU');