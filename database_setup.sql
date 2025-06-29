-- Create database
CREATE DATABASE IF NOT EXISTS company_db;
USE company_db;

-- Create employees table
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    department VARCHAR(50) NOT NULL,
    position VARCHAR(100) NOT NULL,
    salary DECIMAL(10,2) NULL,
    join_date DATE NOT NULL,
    address TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create index on email for faster lookups
CREATE INDEX idx_email ON employees(email);

-- Create index on department for filtering
CREATE INDEX idx_department ON employees(department);

-- Insert sample data (optional)
INSERT INTO employees (first_name, last_name, email, phone, department, position, salary, join_date, address) VALUES
('John', 'Doe', 'john.doe@company.com', '+1234567890', 'IT', 'Software Developer', 75000.00, '2024-01-15', '123 Main St, City, State'),
('Jane', 'Smith', 'jane.smith@company.com', '+1234567891', 'HR', 'HR Manager', 65000.00, '2024-02-01', '456 Oak Ave, City, State'),
('Mike', 'Johnson', 'mike.johnson@company.com', '+1234567892', 'Finance', 'Financial Analyst', 60000.00, '2024-03-10', '789 Pine Rd, City, State');