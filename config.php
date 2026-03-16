<?php
// Database configuration
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "portfolio_db";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create projects table
$conn->query("CREATE TABLE IF NOT EXISTS projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    project_title VARCHAR(100) NOT NULL,
    project_description TEXT NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    project_url VARCHAR(255),
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create skills_experiences table
$conn->query("CREATE TABLE IF NOT EXISTS skills_experiences (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) NOT NULL,
    experience_level VARCHAR(50) NOT NULL,
    years_of_experience INT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Portfolio Information (single user)
define('PORTFOLIO_NAME', 'John Kelvin Navarro');
define('PORTFOLIO_USERNAME', 'gord_user');
define('PORTFOLIO_EMAIL', 'johnkelvinnavarro79@gmail.com');

?>
