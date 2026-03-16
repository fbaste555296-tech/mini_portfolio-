<?php
session_start();
require 'config.php';

// Simple login - just check password
$password = '1234'; // Set your password here

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $entered_password = $_POST['password'];
    
    if ($entered_password === $password) {
        $_SESSION['logged_in'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid password!";
    }
}

// Redirect to index if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}

$error_message = $error_message ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-box h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .login-box form {
            margin: 0;
            padding: 0;
        }
        
        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .login-box input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 5px rgba(0, 102, 204, 0.3);
        }
        
        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-box button:hover {
            background-color: #0052a3;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }

        .info-message {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #bee5eb;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Portfolio Manager</h1>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="info-message">
                💡 Default password: <strong>1234</strong>
            </div>

            <form method="POST" action="">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Enter password" 
                    required
                    autofocus
                >
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_registration ? 'Register' : 'Login'; ?> - Portfolio Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }
        
        .login-box h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .login-box form {
            margin: 0;
            padding: 0;
        }
        
        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .login-box input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 5px rgba(0, 102, 204, 0.3);
        }
        
        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-box button:hover {
            background-color: #0052a3;
        }
        
        .login-box .signup-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        
        .login-box .signup-link a {
            color: #0066cc;
            text-decoration: none;
        }
        
        .login-box .signup-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Portfolio Manager</h1>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="info-message">
                💡 Default password: <strong>1234</strong>
            </div>

            <form method="POST" action="">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Enter password" 
                    required
                    autofocus
                >
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
