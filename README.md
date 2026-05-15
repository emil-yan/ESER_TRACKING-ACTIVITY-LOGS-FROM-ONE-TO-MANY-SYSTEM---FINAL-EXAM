# 🐾 Pet Clinic Management System (Staff Portal)

A secure, responsive web application demonstrating a **One-to-Many (1:N)** database relationship with full **Activity Tracking**, built using **PHP (PDO)** and **MySQL**. 

This project was developed as a Final Exam requirement to illustrate an internal business tool. It features a secure administrative dashboard where authenticated staff members can manage clinic data, while the system quietly records their actions for accountability.

---

## ✨ System Features
* **Staff Authentication:** Secure registration and login system for clinic staff/admins using PHP sessions and password hashing.
* **Parent Entity Management (Owners):** Full CRUD operations to register, update, delete, and search for pet owners.
* **Child Entity Management (Pets):** Full CRUD operations to register, update, delete, and search for pets. Every pet is strictly linked to a registered owner via a dropdown selection.
* **Activity Tracking Logs:** A read-only, chronological dashboard tracking every `CREATE`, `UPDATE`, and `DELETE` action, specifically identifying which staff member performed it.
* **Modern UI:** Clean, responsive design built rapidly using Tailwind CSS via CDN.
* **Secure Database Operations:** Utilizes PHP Data Objects (PDO) with prepared statements to prevent SQL injection.

---

## 🛠️ Technology Stack
* **Frontend:** HTML5, Tailwind CSS
* **Backend:** PHP 8.x
* **Database:** MySQL
* **Environment:** XAMPP (Localhost)

---

## 🗂️ File Structure
```text
pet-clinic/
├── includes/
│   ├── db_connect.php       # PDO connection string
│   └── logger.php           # Helper function to insert activity logs
├── index.php                # Secure Admin Dashboard hub
├── register.php             # Staff account creation
├── login.php                # Staff authentication
├── logout.php               # Destroys active session
├── owners.php               # Parent Entity: View & Search table
├── add_owner.php            # Parent Entity: Create logic
├── edit_owner.php           # Parent Entity: Update logic
├── delete_owner.php         # Parent Entity: Delete logic
├── pets.php                 # Child Entity: View & Search table
├── add_pet.php              # Child Entity: Create logic
├── edit_pet.php             # Child Entity: Update logic
├── delete_pet.php           # Child Entity: Delete logic
├── logs.php                 # Read-only activity tracking dashboard
├── sql_code.md              # Read-only SQL code of the database used
├── pet_clinic_db.sql        # Direct export of the database used
└── README.md                # Project documentation
