<?php
session_start(); 
include_once('includes/config.php');

// Code for login 
$loginError = false; // Inisialisasi variabel untuk kesalahan login
if (isset($_POST['login'])) {
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $useremail = mysqli_real_escape_string($con, $_POST['uemail']);
    
    // Get user data including both names for profile integration
    $ret = mysqli_query($con, "SELECT id, fname, lname, email, password FROM users WHERE email='$useremail'");
    $num = mysqli_fetch_array($ret);
    
    // Since password is stored as plain text, compare directly
    if ($num && $password === $num['password']) {
        // Set session variables for profile integration
        $_SESSION['id'] = $num['id'];
        $_SESSION['name'] = $num['fname'] . ' ' . $num['lname']; // Full name for display
        $_SESSION['fname'] = $num['fname'];
        $_SESSION['lname'] = $num['lname'];
        $_SESSION['email'] = $num['email'];
        
        header("location:dashboard.php");
        exit();
    } else {
        $loginError = true; // Set flag for login error
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>NutriMOod - Login</title>
    <link rel="icon" href="./image/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arvo', serif;
            background: linear-gradient(135deg,rgb(224, 19, 19) 0%,rgb(110, 227, 145) 100%);
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
            background-color:linear-gradient(135deg,rgb(210, 29, 29) 0%, #fefefe 100%);
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
            color: #ddd;
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

        .login-box {
            background-color: white;
            color: #1f2449;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #1f2449;
        }

        .login-box p {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color:rgb(45, 139, 59) ;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color:rgb(35, 120, 48) ;
        }

        .forgot {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .forgot a {
            font-size: 0.9rem;
            text-decoration: none;
            color: #888;
        }

        .forgot a:hover {
            color: #1f2449;
        }

        .footer-links {
            text-align: center;
            margin-top: 15px;
        }

        .footer-links a {
            color: #1f2449;
            text-decoration: none;
            display: block;
            margin-top: 5px;
            font-size: 0.9rem;
        }

        .alert {
            display: <?php echo $loginError ? 'block' : 'none'; ?>; /* Tampilkan alert jika ada kesalahan login */
            color: red;
            margin-bottom: 15px;
            text-align: center;
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

            .login-box {
                padding: 20px;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="image/D.gif" alt="Login Animation" />
            <h2>Mood kamu nunggu direkomendasiin lagi nih.</h2>
            <p>Masuk Sekarang!</p>
        </div>
        <div class="right-panel">
            <div class="login-box">
                <h2>Welcome Back</h2>
                <p>Login to continue</p>

                <!-- Alert untuk kesalahan login -->
                <div class="alert alert-danger">
                    <strong>Nutrizen!</strong> Invalid username or password. Please try again.
                </div>

                <form method="post">
                    <label for="uemail">Email Address</label>
                    <input type="email" name="uemail" id="uemail" placeholder="Enter your email" required />

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required />

                    <div class="forgot">
                        <a href="password-recovery.php">Forgot Password?</a>
                    </div>

                    <button class="login-btn" name="login" type="submit">Login</button>
                </form>

                <div class="footer-links">
                    <a href="signup.php">Need an account? Sign up!</a>
                    <a href="index.php">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

<?php include('includes/footer.php'); ?>
</body>
</html>
