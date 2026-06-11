# Gas Station Debt Management Library

## Overview

**New Mbarara Service Station Internal Audit System** is a comprehensive PHP-based web application designed to manage fuel station operations, transaction tracking, and debt management. This system provides role-based access for both administrative and front-desk staff with centralized tracking and reporting capabilities.

---

## 🎯 Purpose

This library serves as an internal audit and transaction management system for gas service stations, enabling:
- **Secure user authentication** with role-based access control
- **Transaction entry and tracking** for fuel and services
- **Meter reading management** and recording
- **Debt tracking and management** for customers
- **Data validation** and error handling
- **Administrative oversight** and reporting

---

## ✨ Key Features

### 1. **Dual-Role User System**
   - **Admin Users**: Full access to meter readings, reports, and system administration
   - **Front-End Users**: Transaction entry for fuel sales and customer interactions
   - Secure session management with database authentication

### 2. **Transaction Management**
   - Form-based transaction entry system
   - Multiple transaction types support
   - Input validation and error checking
   - Transaction posting and recording

### 3. **Meter Readings**
   - Date-based meter reading recording
   - jQuery UI datepicker integration for easy date selection
   - Admin panel for meter data review and management

### 4. **Security Features**
   - Password hashing using SHA1
   - Session-based authentication
   - Database connection management
   - User input validation and sanitization

### 5. **User Interface**
   - Clean, organized dashboard
   - Responsive menu system
   - Error messaging and user feedback
   - Navigation between admin and front-desk areas

---

## 📁 Directory Structure

```
larybantu.github.io-gasstation/
├── index.php                 # Login page (entry point)
├── frontarea.php            # Front-desk user dashboard
├── meter_readings.php       # Meter reading admin panel
├── logout.php               # User logout handler
├── mainmenu_out.php         # Main navigation menu
│
├── includes/                # Core library files
│   ├── db_connection.php    # Database connection setup
│   ├── session.php          # Session management
│   ├── sessionadmin.php     # Admin session handling
│   ├── functions.php        # Utility functions
│   ├── form_functions.php   # Form validation utilities
│   ├── header.php           # Header template
│   ├── mainmenu.php         # Main menu template
│   └── bodycontent.php      # Body content template
│
├── css/                     # Stylesheets
│   ├── style.css            # Main stylesheet
│   ├── modify.css           # Dashboard stylesheet
│   └── jquery-ui.css        # jQuery UI themes
│
├── js/                      # JavaScript libraries
│   ├── jquery.min.js        # jQuery library
│   └── jquery-ui.min.js     # jQuery UI library
│
├── img/                     # Image assets
│   ├── newfav.ico          # Favicon
│   └── webversion.png      # Logo/header image
│
├── images/                  # Additional images
└── admin/                   # Admin-specific files
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 5.x or higher
- MySQL database
- Web server (Apache, Nginx, etc.)
- jQuery and jQuery UI libraries (included)

### Installation

1. **Clone or download the repository**
   ```bash
   git clone https://github.com/larybantu/larybantu.github.io-gasstation.git
   ```

2. **Database Setup**
   - Create a MySQL database
   - Create required tables:
     - `admin` - Admin user credentials
     - `front_user` - Front-desk user credentials
     - `transactions` - Transaction records
     - `meter_readings` - Meter reading records

3. **Configure Database Connection**
   - Update `includes/db_connection.php` with your database credentials
   - Ensure proper host, username, password, and database name

4. **Set File Permissions**
   - Ensure proper permissions on directories for session storage
   - Configure web server to serve files from root directory

5. **Access the Application**
   - Navigate to `http://localhost/[install-directory]/index.php`
   - Login with admin or front-desk credentials

---

## 📝 Database Schema

### Admin Table
```sql
CREATE TABLE admin (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30) UNIQUE,
  hsd_password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Front User Table
```sql
CREATE TABLE front_user (
  front_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30) UNIQUE,
  hsd_password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🔐 Authentication Flow

1. User submits login form on `index.php`
2. System validates username and password against both `admin` and `front_user` tables
3. Password verified using SHA1 hashing
4. On success: Session created with `user_id` and `username`
5. Admin users → Redirected to `meter_readings.php`
6. Front-desk users → Redirected to `frontarea.php`
7. Logout clears session and returns to login page

---

## 🛠️ Core Functions (includes/)

### `db_connection.php`
- Establishes MySQL database connection
- Handles connection errors

### `session.php` & `sessionadmin.php`
- Session initialization and validation
- User authentication checks
- Redirect to login if session invalid

### `functions.php`
- Utility functions for common operations
- Database query helpers
- Error confirmation functions

### `form_functions.php`
- Form validation functions
- Field length checking
- Required field validation
- Field sanitization

---

## 💡 Usage Examples

### Login Process
```php
// index.php handles form submission
if (isset($_POST['submit'])) {
    // Validate input
    // Check database for user
    // Create session and redirect
}
```

### Transaction Entry
```php
// frontarea.php - Front-desk area
// Users select transaction type from menu
// Form loads for data entry
// Submit post transaction
```

### Meter Reading
```php
// meter_readings.php - Admin panel
// View and record meter readings
// Date selection with jQuery datepicker
// Submit and track readings over time
```

---

## 🔄 Debt Management Features

This system tracks customer debt through:
- **Transaction Records**: All sales and payments recorded in database
- **Balance Tracking**: Customer account balance calculations
- **Reporting**: Generate debt reports for follow-up
- **Status Monitoring**: Track overdue accounts

---

## 🚨 Security Notes

⚠️ **Important**: This application uses deprecated MySQL functions. For production use, migrate to MySQLi or PDO for better security and support.

### Recommendations:
1. Replace `mysql_*` functions with MySQLi or PDO
2. Use parameterized queries to prevent SQL injection
3. Implement password hashing with bcrypt/Argon2 instead of SHA1
4. Add CSRF token protection to forms
5. Implement rate limiting on login attempts
6. Use HTTPS for all connections

---

## 👥 User Roles

| Role | Access | Permissions |
|------|--------|-------------|
| **Admin** | Full System | Meter readings, reports, system settings |
| **Front-Desk** | Limited | Transaction entry, customer interactions |

---

## 📋 Transaction Types Supported

- Fuel Sales
- Service Charges
- Debt Payments
- Account Adjustments
- Refunds

---

## 🐛 Known Issues & Limitations

1. **Deprecated MySQL Functions**: Uses old `mysql_*` functions (PHP 5.5+ removed)
2. **Password Hashing**: SHA1 is not suitable for password storage
3. **Missing Validation**: Some input validation could be enhanced
4. **No CSRF Protection**: Forms lack CSRF token protection
5. **Session Management**: Could use more robust session handling

---

## 🔧 Technology Stack

| Component | Technology |
|-----------|-----------|
| **Backend** | PHP 5.x |
| **Database** | MySQL |
| **Frontend** | HTML, CSS, JavaScript |
| **UI Library** | jQuery, jQuery UI |
| **Authentication** | Session-based |

**Language Composition:**
- PHP: 75.6%
- JavaScript: 22.8%
- Hack: 1.6%

---

## 📄 File Descriptions

| File | Purpose |
|------|---------|
| `index.php` | Login page and authentication |
| `frontarea.php` | Front-desk user dashboard |
| `meter_readings.php` | Admin meter reading panel |
| `logout.php` | Session termination |
| `mainmenu_out.php` | Navigation menu for logged-out users |

---

## 🎓 Author

**Developed by:** Hillary (Systems Developer)
- **Qualifications**: BSc. Computer Science, CCNA
- **Contact**: hbantu@gmail.com, hillary@naxicsolutions.com

---

## 📞 Support & Help

For issues, questions, or assistance:
- Email: hbantu@gmail.com
- Open an issue on GitHub
- Check the system documentation

---

## 📜 License

Copyright reserved. Only for study purposes.

---

## 🚀 Future Enhancements

- [ ] Modernize PHP codebase (MySQLi/PDO)
- [ ] Implement proper password hashing (bcrypt)
- [ ] Add CSRF protection
- [ ] Create REST API for mobile apps
- [ ] Add real-time debt notifications
- [ ] Implement SMS alerts for overdue accounts
- [ ] Add dashboard analytics and charts
- [ ] Create mobile-responsive interface
- [ ] Add export to PDF/Excel reports
- [ ] Implement backup and recovery system

---

## 📦 Repository Information

- **Repository**: [larybantu/larybantu.github.io-gasstation](https://github.com/larybantu/larybantu.github.io-gasstation)
- **Created**: October 9, 2023
- **Language**: PHP (Primary)
- **Status**: Active

---

**Last Updated**: 2026-06-11

