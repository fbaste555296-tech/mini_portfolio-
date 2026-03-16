<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Get statistics
$projects_count = $conn->query("SELECT COUNT(*) as count FROM projects")->fetch_assoc()['count'];
$skills_count = $conn->query("SELECT COUNT(*) as count FROM skills_experiences")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portfolio Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .navbar {
            background-color: #0066cc;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }

        .navbar-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .logout-btn {
            background-color: #d32f2f !important;
        }

        .logout-btn:hover {
            background-color: #b71c1c !important;
        }

        .dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .welcome-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .welcome-section h2 {
            margin-top: 0;
            color: #333;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            color: #0066cc;
            margin-top: 0;
            font-size: 20px;
        }

        .card .count {
            font-size: 36px;
            color: #0066cc;
            font-weight: bold;
            margin: 15px 0;
        }

        .card p {
            color: #666;
            margin-bottom: 15px;
        }

        .card a {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 5px;
        }

        .card a:hover {
            background-color: #0052a3;
        }

        .public-view-link {
            background-color: #4caf50 !important;
        }

        .public-view-link:hover {
            background-color: #45a049 !important;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Portfolio Manager</h1>
        <div class="navbar-right">
            <span>Welcome!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h2>Dashboard</h2>
            <p>Manage your portfolio, projects, and professional experience.</p>
        </div>

        <div class="cards-container">
            <!-- Projects Card -->
            <div class="card">
                <h3>📁 Projects</h3>
                <div class="count"><?php echo $projects_count; ?></div>
                <p>Manage your portfolio projects</p>
                <a href="manage portfolio project.php">Manage Projects</a>
                <a href="public view.php">View Public</a>
            </div>

            <!-- Skills & Experiences Card -->
            <div class="card">
                <h3>🎯 Skills & Experiences</h3>
                <div class="count"><?php echo $skills_count; ?></div>
                <p>Manage your skills and professional experiences</p>
                <a href="manage skills n experiences.php">Manage Skills</a>
                <a href="public view.php">View Public</a>
            </div>

            <!-- Public Profile Card -->
            <div class="card">
                <h3>👁️ Public Profile</h3>
                <p>View your public portfolio page</p>
                <a href="public view.php" class="public-view-link">View Profile</a>
            </div>
        </div>
    </div>
</body>
</html>
