<!-- # PHP Restaurant Application

This is a robust restaurant management system built using PHP. The application provides features for user management, menu management, order processing, table reservations, payment integration, inventory management, reporting, and notifications. It is designed to streamline restaurant operations for both staff and customers.

---

## Features

### 1. **User Management**
- **User Roles:** Staff and Customers.
- **Authentication:** User registration, login, and password recovery.
- **Profile Management:** Update personal information, change password, and upload profile pictures.

### 2. **Menu Management (Staff Only)**
- **Categories:** Add, edit, and delete menu categories (e.g., Appetizers, Main Course, Desserts).
- **Menu Items:** Add, edit, and delete menu items with images, prices, descriptions, and availability.
- **Special Offers:** Create and manage discounts with expiry dates.

### 3. **Order Management (Customers Only)**
- **Order Placement:** Customers can place orders online with customization options.
- **Order Tracking:** Real-time order status updates (Pending, Preparing, Ready, Delivered).
- **Order History:** View past orders and reorder functionality.

### 4. **Table Reservation**
- **Reservation Management:** Customers can book tables online by selecting date, time, and number of guests.
- **Reservation Confirmation:** Email/SMS confirmation and options to modify or cancel reservations.

### 5. **Payment Integration**
- **Payment Methods:** Credit/Debit Card and Cash on Delivery.
- **Payment Gateway:** Secure payment processing.
- **Invoice Generation:** Generate and send invoices to customers.

### 6. **Inventory Management (Staff Only)**
- **Stock Tracking:** Monitor inventory levels.
- **Supplier Management:** Add, edit, and delete supplier information.

### 7. **Reporting and Analytics (Staff Only)**
- **Sales Reports:** Generate daily, weekly, and monthly sales reports.

### 8. **Notifications and Alerts**
- **Order Notifications:** Notify staff and customers about order updates.
- **Reservation Alerts:** Notify customers about reservation confirmations.

### 9. **Staff Dashboard**
- **Overview:** Display key metrics like total sales, active orders, and reservations.
- **Manage Users:** Add, edit, and delete users with role assignments.
- **Manage Menu:** Add, edit, and delete menu items and categories.
- **Manage Orders:** View and update order statuses.
- **Manage Reservations:** Confirm, modify, or cancel reservations.

### 10. **Customer Interface**
- **Menu Browsing:** Browse menu by categories and search for specific items.
- **Order Placement:** Add items to cart, customize orders, and place orders.
- **Reservation Booking:** Book tables and manage reservations.
- **Order Tracking:** Track order status in real-time.
- **Feedback and Reviews:** Submit feedback and reviews for orders.

---

## Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/your-username/php-restaurant-app.git
   cd php-restaurant-app -->

Certainly! Below is a structured folder organization for your PHP-based Restaurant Application using Object-Oriented Programming (OOP) principles and Bootstrap for the frontend. Since you've requested not to use routing, we'll focus on organizing files logically and ensuring modularity.

---

### **Folder Structure**

```
restaurant-app/
├── config/
│   ├── db.php                # Database configuration
├── controllers/
│   ├── StaffController.php   # Handles staff-related logic
│   ├── CustomerController.php # Handles customer-related logic
│   ├── OrderController.php    # Handles order-related logic
│   ├── ReservationController.php # Handles reservation-related logic
├── models/
│   ├── User.php              # User model (Staff/Customer)
│   ├── Menu.php              # Menu model
│   ├── Order.php             # Order model
│   ├── Reservation.php       # Reservation model
│   ├── Inventory.php         # Inventory model
├── views/
│   ├── layouts/
│   │   ├── header.php        # Common header
│   │   ├── footer.php        # Common footer
│   ├── staff/
│   │   ├── dashboard.php     # Staff dashboard
│   │   ├── menu-management.php # Manage menu items
│   │   ├── order-management.php # Manage orders
│   │   ├── reservation-management.php # Manage reservations
│   ├── customer/
│   │   ├── menu.php          # Customer menu browsing
│   │   ├── order-placement.php # Place orders
│   │   ├── reservation.php   # Book tables
│   ├── shared/
│   │   ├── login.php         # Login form
│   │   ├── register.php      # Registration form
├── public/
│   ├── css/
│   │   ├── styles.css        # Custom CSS
│   ├── js/
│   │   ├── scripts.js        # Custom JavaScript
│   ├── images/               # Store images
├── index.php                 # Entry point of the application
└── .env                      # Environment variables (optional)
```

---

### **Explanation of Folder Structure**
1. **`config/`**: Contains configuration files, such as database settings.
2. **`controllers/`**: Houses all the controller classes responsible for handling business logic.
3. **`models/`**: Contains all the model classes that interact with the database.
4. **`views/`**: Stores HTML templates for different user interfaces.
5. **`public/`**: Holds static assets like CSS, JavaScript, and images.
6. **`index.php`**: Acts as the entry point for the application.
7. **`.env`**: Optional file for storing environment-specific configurations.

---

### **Example Files**

#### 1. `config/db.php` (Database Configuration)
```php
<?php
class Database {
    private $host = "localhost";
    private $db_name = "restaurant_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }
}
?>
```

---

#### 2. `models/User.php` (User Model)
```php
<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role; // 'staff' or 'customer'

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = "INSERT INTO {$this->table} (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email AND password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
```

---

#### 3. `controllers/StaffController.php` (Staff Controller)
```php
<?php
include_once '../models/User.php';
include_once '../config/db.php';

class StaffController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function manageMenu() {
        // Logic to fetch and display menu items for staff
        include '../views/staff/menu-management.php';
    }

    public function updateMenuItem($itemId, $newPrice) {
        // Logic to update menu item price
        echo "Updated menu item $itemId with new price $newPrice";
    }
}
?>
```

---

#### 4. `views/layouts/header.php` (Bootstrap Header)
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurant App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
```

---

#### 5. `views/customer/menu.php` (Customer Menu Page)
```php
<?php include '../layouts/header.php'; ?>
<div class="container mt-5">
    <h1 class="text-center">Our Menu</h1>
    <div class="row">
        <!-- Example menu item -->
        <div class="col-md-4">
            <div class="card">
                <img src="../public/images/pizza.jpg" class="card-img-top" alt="Pizza">
                <div class="card-body">
                    <h5 class="card-title">Margherita Pizza</h5>
                    <p class="card-text">Delicious pizza with fresh ingredients.</p>
                    <p class="card-text"><strong>Price:</strong> $10</p>
                    <a href="#" class="btn btn-primary">Add to Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>
```

---

#### 6. `index.php` (Entry Point)
```php
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: views/shared/login.php");
    exit;
}

// Load appropriate page based on user role
$userRole = $_SESSION['role'];
if ($userRole === 'staff') {
    include 'views/staff/dashboard.php';
} elseif ($userRole === 'customer') {
    include 'views/customer/menu.php';
}
?>
```

---

### **Key Points**
1. **OOP Approach**: Each component (e.g., User, Menu, Order) is encapsulated in its own class.
2. **Bootstrap Integration**: The `header.php` and `menu.php` files demonstrate how to use Bootstrap for styling.
3. **No Routing**: Instead of using a routing system, pages are included dynamically based on user roles or actions.

This structure ensures modularity, scalability, and maintainability while adhering to OOP principles and leveraging Bootstrap for a responsive UI. Let me know if you need further clarification!
