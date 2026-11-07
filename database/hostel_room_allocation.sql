-- Hostel Room Allocation System
-- XAMPP / MariaDB / MySQL compatible. All sample records are fictional.
CREATE DATABASE IF NOT EXISTS hostel_room_allocation CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE hostel_room_allocation;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS allocations;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS hostels;
DROP TABLE IF EXISTS students;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE students(
 student_id INT AUTO_INCREMENT PRIMARY KEY,
 student_name VARCHAR(100) NOT NULL,
 email VARCHAR(120) NOT NULL UNIQUE,
 phone VARCHAR(20),
 gender ENUM('Female','Male','Other') NOT NULL,
 course VARCHAR(100) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE hostels(
 hostel_id INT AUTO_INCREMENT PRIMARY KEY,
 hostel_name VARCHAR(100) NOT NULL UNIQUE,
 location VARCHAR(150) NOT NULL,
 gender_type ENUM('Female','Male','Any') NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE rooms(
 room_id INT AUTO_INCREMENT PRIMARY KEY,
 hostel_id INT NOT NULL,
 room_number VARCHAR(20) NOT NULL,
 room_type ENUM('Single','Double','Triple','Dormitory') NOT NULL,
 capacity INT NOT NULL,
 monthly_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_rooms_hostel FOREIGN KEY(hostel_id) REFERENCES hostels(hostel_id)
   ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT uq_hostel_room UNIQUE(hostel_id,room_number),
 CONSTRAINT chk_room_capacity CHECK(capacity>0),
 CONSTRAINT chk_monthly_fee CHECK(monthly_fee>=0)
) ENGINE=InnoDB;

CREATE TABLE allocations(
 allocation_id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 room_id INT NOT NULL,
 allocation_date DATE NOT NULL,
 vacating_date DATE NULL,
 status ENUM('Active','Completed','Cancelled') NOT NULL DEFAULT 'Active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_alloc_student FOREIGN KEY(student_id) REFERENCES students(student_id)
   ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT fk_alloc_room FOREIGN KEY(room_id) REFERENCES rooms(room_id)
   ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT chk_allocation_dates CHECK(vacating_date IS NULL OR vacating_date>=allocation_date),
 INDEX idx_alloc_room_status(room_id,status),
 INDEX idx_alloc_student_status(student_id,status)
) ENGINE=InnoDB;

INSERT INTO students(student_name,email,phone,gender,course) VALUES
('Aarav Mehta','aarav.mehta@example.test','9000000101','Male','BSc Computer Science'),
('Mira Shah','mira.shah@example.test','9000000102','Female','BSc Data Science'),
('Zoya Khan','zoya.khan@example.test','9000000103','Female','BSc Information Technology'),
('Kabir Rao','kabir.rao@example.test','9000000104','Male','BCom'),
('Anaya Iyer','anaya.iyer@example.test','9000000105','Female','BSc Artificial Intelligence');

INSERT INTO hostels(hostel_name,location,gender_type) VALUES
('Maple Residence','North Campus','Female'),
('Cedar House','East Campus','Male'),
('Oak Commons','Central Campus','Any');

INSERT INTO rooms(hostel_id,room_number,room_type,capacity,monthly_fee) VALUES
(1,'F-101','Double',2,4500),(1,'F-102','Triple',3,3800),(1,'F-103','Single',1,6000),
(2,'M-201','Double',2,4500),(2,'M-202','Triple',3,3800),(3,'C-301','Dormitory',4,3000);

INSERT INTO allocations(student_id,room_id,allocation_date,status) VALUES
(2,1,'2026-08-01','Active'),(3,1,'2026-08-02','Active'),
(1,4,'2026-08-03','Active'),(4,5,'2026-08-04','Active'),(5,2,'2026-08-05','Active');

-- At least five meaningful DQL queries
-- Q1: Student directory
SELECT student_id,student_name,course,email FROM students ORDER BY student_name;
-- Q2: Vacant rooms
SELECT r.room_id,h.hostel_name,r.room_number,r.capacity,COUNT(a.allocation_id) occupied_beds,
       r.capacity-COUNT(a.allocation_id) vacant_beds
FROM rooms r JOIN hostels h ON h.hostel_id=r.hostel_id
LEFT JOIN allocations a ON a.room_id=r.room_id AND a.status='Active'
GROUP BY r.room_id,h.hostel_name,r.room_number,r.capacity
HAVING vacant_beds>0 ORDER BY h.hostel_name,r.room_number;
-- Q3: Room count by hostel
SELECT h.hostel_name,COUNT(r.room_id) total_rooms FROM hostels h
LEFT JOIN rooms r ON r.hostel_id=h.hostel_id GROUP BY h.hostel_id,h.hostel_name;
-- Q4: Active allocations
SELECT * FROM allocations WHERE status='Active' ORDER BY allocation_date DESC;
-- Q5: Average monthly fee by room type
SELECT room_type,ROUND(AVG(monthly_fee),2) average_monthly_fee
FROM rooms GROUP BY room_type ORDER BY average_monthly_fee DESC;

-- Three JOIN queries
-- JOIN 1: Students with their room and hostel
SELECT s.student_name,s.course,h.hostel_name,r.room_number,a.allocation_date
FROM students s JOIN allocations a ON a.student_id=s.student_id
JOIN rooms r ON r.room_id=a.room_id JOIN hostels h ON h.hostel_id=r.hostel_id
WHERE a.status='Active';
-- JOIN 2: Hostels with rooms, including empty hostels
SELECT h.hostel_name,h.location,r.room_number,r.room_type,r.capacity
FROM hostels h LEFT JOIN rooms r ON r.hostel_id=h.hostel_id;
-- JOIN 3: Room occupancy and vacant beds
SELECT h.hostel_name,r.room_number,r.capacity,COUNT(a.allocation_id) occupied_beds,
       r.capacity-COUNT(a.allocation_id) vacant_beds
FROM hostels h JOIN rooms r ON r.hostel_id=h.hostel_id
LEFT JOIN allocations a ON a.room_id=r.room_id AND a.status='Active'
GROUP BY h.hostel_name,r.room_id,r.room_number,r.capacity;

-- Demonstration DML examples (run individually when needed):
-- UPDATE students SET phone='9000000199' WHERE student_id=1;
-- UPDATE allocations SET status='Completed',vacating_date='2026-10-31' WHERE allocation_id=1;
-- DELETE FROM allocations WHERE allocation_id=999;
