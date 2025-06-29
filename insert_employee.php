<?php
// insert_employee.php

// Enable error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$dbname = 'company_db';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are allowed');
    }
    
    // Validate and sanitize input data
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $salary = $_POST['salary'] ?? null;
    $joinDate = $_POST['joinDate'] ?? '';
    $address = trim($_POST['address'] ?? '');
    
    // Validation
    if (empty($firstName)) {
        throw new Exception('First name is required');
    }
    
    if (empty($lastName)) {
        throw new Exception('Last name is required');
    }
    
    if (empty($email)) {
        throw new Exception('Email is required');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    if (empty($phone)) {
        throw new Exception('Phone number is required');
    }
    
    if (empty($department)) {
        throw new Exception('Department is required');
    }
    
    if (empty($position)) {
        throw new Exception('Position is required');
    }
    
    if (empty($joinDate)) {
        throw new Exception('Join date is required');
    }
    
    // Validate date format
    $dateTime = DateTime::createFromFormat('Y-m-d', $joinDate);
    if (!$dateTime || $dateTime->format('Y-m-d') !== $joinDate) {
        throw new Exception('Invalid date format');
    }
    
    // Check if email already exists
    $checkEmailStmt = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE email = ?");
    $checkEmailStmt->execute([$email]);
    
    if ($checkEmailStmt->fetchColumn() > 0) {
        throw new Exception('Email already exists in the system');
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO employees (first_name, last_name, email, phone, department, position, salary, join_date, address, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute the statement
    $result = $stmt->execute([
        $firstName,
        $lastName,
        $email,
        $phone,
        $department,
        $position,
        $salary ? (float)$salary : null,
        $joinDate,
        $address
    ]);
    
    if ($result) {
        $employeeId = $pdo->lastInsertId();
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Employee added successfully!',
            'employee_id' => $employeeId
        ]);
    } else {
        throw new Exception('Failed to insert employee data');
    }
    
} catch (PDOException $e) {
    // Database error
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // General error
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close connection
$pdo = null;
?>