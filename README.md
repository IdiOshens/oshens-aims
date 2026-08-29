# OSHENS AIMS - Academic Information Management System

A comprehensive Academic and Attendance Information Management System designed for university operations.

## 🎯 Features
- **Role-Based Access**: Admin, Lecturer, and Student dashboards
- **Attendance Tracking**: Virtual and physical attendance management
- **Course Management**: Course scheduling, enrollment, and tracking
- **User Management**: Complete CRUD operations for all user types
- **Virtual Classroom**: Integration with meeting links for online lectures

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+
- MySQL/MariaDB
- XAMPP or similar local server environment

### Installation
1. Clone the repository:
 + "" + "" + "" + "bash" + @"
git clone https://github.com/yourusername/oshens-aims.git
 + "" + "" + "" + @"

2. Import the database schema:
 + "" + "" + "" + "bash" + @"
mysql -u root -p < database_schema.sql
 + "" + "" + "" + @"

3. Configure database connection in  + "includes/config.php" + @"

4. Run the application:
 + "" + "" + "" + "bash" + @"
php -S localhost:8000
 + "" + "" + "" + @"

## 🔧 Technology Stack
- **Backend**: PHP 7/8+ (Procedural with MySQLi)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **UI Framework**: Soft UI Dashboard
- **Charts**: Chart.js

## 📁 Project Structure
 + "" + "" + "" + @"
oshens AIMS/
├── includes/          # Core PHP includes
├── assets/           # CSS, JS, fonts, images
├── dashboard/        # Role-specific dashboards
├── modules/          # Core functionality modules
└── ...
 + "" + "" + "" + @"

## ⚠️ Security Status
Current security vulnerabilities are being addressed:
- [ ] Authentication guard implementation
- [ ] CSRF protection
- [ ] SQL injection prevention
- [ ] Environment variable configuration

## 📝 License
MIT License - See LICENSE file for details

## 🤝 Contributing
Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.
