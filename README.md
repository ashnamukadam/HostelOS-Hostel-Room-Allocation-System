# HostelOS – Hostel Room Allocation System

A web-based Hostel Room Allocation System developed using **PHP, MySQL, HTML, CSS and JavaScript**. The application helps manage hostel records, student information, room inventory and room allocations through a centralized dashboard.

## Project Overview

HostelOS is designed to simplify hostel administration by organizing student details, hostel information, room availability and allocation records in a structured database.

The system provides CRUD (Create, Read, Update and Delete) operations and reporting features to make hostel room management easier and more efficient.

## Features

- **Dashboard:** Overview of students, hostels, rooms and allocation statistics.
- **Student Management:** Add, view, update and delete student records.
- **Hostel Management:** Manage hostel details and hostel information.
- **Room Management:** Maintain room numbers, room types, capacities and monthly fees.
- **Room Allocation:** Allocate rooms to students and manage allocation records.
- **Checkout Management:** Complete student allocations when they vacate rooms.
- **Reports:** View vacant beds, room occupancy and student-hostel allocation details.
- **Database Relationships:** Uses primary keys, foreign keys and relational database constraints.

## Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Backend development |
| MySQL | Database management |
| PDO | Database connectivity |
| HTML5 | Page structure |
| CSS3 | User interface and styling |
| JavaScript | Client-side functionality |
| XAMPP | Local development environment |

## Database Design

The project uses a MySQL database named `hostel_room_allocation`, consisting of four main tables:

1. **students** – Stores student information.
2. **hostels** – Stores hostel details.
3. **rooms** – Stores room information and capacity.
4. **allocations** – Stores student room allocation records.

The database uses primary keys and foreign key relationships to maintain data integrity.

## Project Structure

```text
hostel_room_allocation/
│
├── assets/
│   └── style.css
│
├── database/
│   └── hostel_room_allocation.sql
│
├── allocations.php
├── config.php
├── hostels.php
├── includes.php
├── index.php
├── reports.php
├── rooms.php
└── students.php
```

## Installation and Setup

Follow these steps to run the project locally.

### 1. Install XAMPP

Download and install [XAMPP](https://www.apachefriends.org/).

Start the following services:
- Apache
- MySQL

### 2. Clone the Repository

Open your terminal and run:

```bash
git clone https://github.com/ashnamukadam/HostelOS-Hostel-Room-Allocation-System.git
```

### 3. Move the Project

Copy the `hostel_room_allocation` folder into the XAMPP `htdocs` directory.

Example:

```text
C:\xampp\htdocs\hostel_room_allocation
```

### 4. Import the Database

1. Open `http://localhost/phpmyadmin/`.
2. Select the **Import** tab.
3. Choose `database/hostel_room_allocation.sql`.
4. Click **Import**.

**Important:** The SQL script recreates the project tables and inserts sample records. Re-importing it will drop and recreate those tables, replacing their existing data.

### 5. Configure Database Connection

Open `config.php` and verify the database settings:

```php
$host = '127.0.0.1';
$db   = 'hostel_room_allocation';
$user = 'root';
$pass = '';
```

