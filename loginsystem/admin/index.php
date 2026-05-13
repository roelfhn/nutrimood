<?php
session_start();
include_once('../includes/config.php');

// Code for login
if (isset($_POST['login'])) {
    $adminusername = $_POST['username'];
    $pass = md5($_POST['password']);
    $ret = mysqli_query($con, "SELECT * FROM admin WHERE username='$adminusername' and password='$pass'");
    $num = mysqli_fetch_array($ret);
    if ($num > 0) {
        $_SESSION['login'] = $_POST['username'];
        $_SESSION['adminid'] = $num['id'];
        echo "<script>window.location.href='dashboard.php'</script>";
        exit();
    } else {
        echo "<script>alert('Invalid username or password');</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin Login</title>
    <link rel="icon" href="../image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet'>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arvo', serif;
            background: linear-gradient(135deg,rgb(189, 206, 56) 0%, #fefefe 100%);
            color: white;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .left-panel {
            width: 50%;
            background-color:linear-gradient(135deg,rgb(45, 139, 59) 0%, #fefefe 100%);;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }

        .left-panel img {
             width: 100%;
            max-width: 200px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .left-panel h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .left-panel p {
            font-size: 1rem;
            color: #1f2449;
        }

        .right-panel {
            width: 50%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            overflow-y: auto;
        }

        .login-container {
            background: white;
            color: #1f2449;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            max-width: 400px;
            width: 100%;
        }

        h2, h3 {
            text-align: center;
            color: #1f2449;
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions a {
            font-size: 0.9rem;
            color: #4caf50;
            text-decoration: none;
        }

        button {
            padding: 10px 20px;
            background-color: rgb(45, 139, 59);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #14182f;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
        }

        .footer-links a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                height: auto;
            }

            .left-panel img {
                max-width: 150px;
            }

            .login-container {
                padding: 20px;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="../image/A.gif" alt="Admin Login" />
            <h2>Welcome back, MOodMaster!</h2>
            <p>Ayo atur rasa dan suasana.</p>
        </div>
        <div class="right-panel">
            <div class="login-container">
                <h2>NutriMood Admin</h2>
                <h3>Login Panel</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input name="username" type="text" placeholder="Enter your username" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="password" type="password" placeholder="Enter your password" required />
                    </div>
                    <div class="actions">
                        <a href="password-recovery.php">Forgot Password?</a>
                        <button name="login" type="submit">Login</button>
                    </div>
                </form>
                <div class="footer-links">
                    <a href="../index.php">← Back to Home Page</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
