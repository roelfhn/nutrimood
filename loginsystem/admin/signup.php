<?php
session_start();
require_once('includes/config.php');

if (isset($_POST['submit'])) {
    $fname    = mysqli_real_escape_string($con, $_POST['fname']);
    $lname    = mysqli_real_escape_string($con, $_POST['lname']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']); // Plain text - TIDAK AMAN!
    $contact  = mysqli_real_escape_string($con, $_POST['contact']);
    
    // PERINGATAN: Menyimpan password plain text sangat tidak aman!
    // Untuk keamanan yang baik, gunakan: $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if email already exists
    $sql = mysqli_query($con, "SELECT id FROM users WHERE email='$email'");
    $row = mysqli_num_rows($sql);

    if ($row > 0) {
        echo "<script>alert('Email id already exist with another account. Please try with other email id');</script>";
    } else {
        // Insert user data
        $msg = mysqli_query($con, "INSERT INTO users(fname, lname, email, password, contactno) VALUES('$fname', '$lname', '$email', '$password', '$contact')");

        if ($msg) {
            // Get the newly created user ID
            $user_id = mysqli_insert_id($con);
            $full_name = $fname . ' ' . $lname;
            
            // Create initial profile entry
            $profile_sql = "INSERT INTO user_profiles (user_id, name, email, phone) VALUES ('$user_id', '$full_name', '$email', '$contact')";
            $profile_result = mysqli_query($con, $profile_sql);
            
            if ($profile_result) {
                echo "<script>alert('Registered successfully! Your profile has been created.');</script>";
            } else {
                echo "<script>alert('Registered successfully! However, there was an issue creating your profile.');</script>";
            }
            
            echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>NutriMOod-Register</title>
    <link rel="icon" href="./image/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arvo', serif;
            background: linear-gradient(135deg,rgb(45, 139, 59) 0%, #fefefe 100%);
            height: 100vh;
            overflow: hidden;
        }

        .wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .left-panel {
            width: 50%;
            background-color: linear-gradient(135deg,rgb(46, 142, 61) 0%, #fefefe 100%);
            color: white;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-panel img {
            width: 100%;
            max-width: 200px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .left-panel h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .left-panel p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .right-panel {
            width: 50%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
        }

        .register-box {
            width: 100%;
            max-width: 450px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            max-height: 90vh;
            overflow-y: auto;
        }

        .register-box h2 {
            text-align: center;
            font-size: 1.6rem;
            margin-bottom: 15px;
            color: #1f2449;
        }

        label {
            display: block;
            margin: 6px 0 4px;
            font-weight: bold;
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .submit-btn {
            background-color:rgb(46, 142, 61);
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color:rgb(46, 142, 61);
        }

        .footer-links {
            text-align: center;
            margin-top: 12px;
        }

        .footer-links a {
            color: #1f2449;
            text-decoration: none;
            display: block;
            margin-top: 4px;
            font-size: 0.9rem;
        }

        .alert {
            display: none; /* Sembunyikan alert secara default */
            color: red;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
                overflow-y: auto;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                height: auto;
            }

            .left-panel img {
                max-width: 150px;
            }

            .register-box {
                padding: 15px;
                max-height: none;
            }
        }
    </style>
    <script>
        function checkpass() {
            var password = document.signup.password.value;
            var confirmPassword = document.signup.confirmpassword.value;

            // Cek apakah password dan konfirmasi password sama
            if (password !== confirmPassword) {
                alert('Password and Confirm Password field does not match');
                document.signup.confirmpassword.focus();
                return false;
            }

            // Cek apakah password memenuhi syarat
            var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
            if (!passwordPattern.test(password)) {
                // Menampilkan alert jika password tidak memenuhi syarat
                document.getElementById('passwordAlert').style.display = 'block';
                return false;
            } else {
                document.getElementById('passwordAlert').style.display = 'none';
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="left-panel">
            <img src="image/B.gif" alt="Signup Animation" />
            <h2>Register Yap!</h2>
            <p>
                Satu akun, sejuta rasa.<br />
                Daftar rasakan pengalaman NutriMOod.
            </p>
        </div>
        <div class="right-panel">
            <div class="register-box">
                
                <h2>Create Account</h2>
                <form method="post" name="signup" onsubmit="return checkpass();">
                    <label for="fname">First Name</label>
                    <input id="fname" name="fname" type="text" required placeholder="Enter your first name" />

                    <label for="lname">Last Name</label>
                    <input id="lname" name="lname" type="text" required placeholder="Enter your last name" />

                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required placeholder="Enter your email" />

                    <label for="contact">Contact Number</label>
                    <input
                        id="contact"
                        name="contact"
                        type="text"
                        required
                        pattern="[0-9]{10}"
                        title="12 numeric characters only"
                        maxlength="12"
                        placeholder="+62"
                    />

                    <label for="password">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                        title="At least one number and one uppercase and lowercase letter, and at least 6 or more characters"
                        placeholder="Create a password"
                    />

                    <label for="confirmpassword">Confirm Password</label>
                    <input
                        id="confirmpassword"
                        name="confirmpassword"
                        type="password"
                        required
                        placeholder="Confirm password"
                    />

                    <div id="passwordAlert" class="alert alert-danger" role="alert">
                        Password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 6 characters long.
                    </div>

                    <button type="submit" name="submit" class="submit-btn">Create Account</button>
                </form>

                <div class="footer-links">
                    <a href="login.php">Have an account? Go to login</a>
                    <a href="index.php">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
