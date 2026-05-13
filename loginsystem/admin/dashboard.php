<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NutriMood - Dashboard</title>
    <link rel="icon" href="./image/logo.png" type="image/png">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Arvo'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arvo', serif;
            background: linear-gradient(135deg, rgb(129, 189, 138) 0%, #fefefe 100%);
            overflow-x: hidden;
            color: #333;
        }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-image {
            width: 70px;
            border-radius: 10px;
        }

        .app-title {
            font-size: 2rem;
            font-weight: bold;
            color: #22c55e;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .nav-button {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .logout-button {
            background-color: #ff4c4c;
            color: #fff;
        }

        .logout-button:hover {
            background-color: #d43c3c;
            transform: scale(1.05);
        }

        .profile-button {
            background-color: #22c55e;
            color: #fff;
        }

        .profile-button:hover {
            background-color: #16a34a;
            transform: scale(1.05);
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .dashboard-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: bold;
            color: rgb(22, 150, 52);
            margin-bottom: 10px;
        }

        .dashboard-description {
            font-size: 1.2rem;
            color: #555;
            max-width: 600px;
            margin-bottom: 30px;
        }

        .features-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
            max-width: 1200px;
        }

        .feature-card {
            background-color: rgb(254, 254, 254);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #22c55e;
        }

        .feature-description {
            font-size: 1rem;
            color: #666;
            margin: 10px 0 20px;
        }

        .feature-button {
            background-color: #22c55e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease, transform 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-button:hover {
            background-color: #16a34a;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .app-header {
                flex-direction: column;
                text-align: center;
            }

            .nav-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .features-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="app-header">
        <div class="logo-container">
            <img src="./image/logo.jpg" alt="NutriMood Logo" class="logo-image" />
            <h1 class="app-title">NutriMood</h1>
        </div>
        <div class="nav-buttons">
            <a href="index.php" class="nav-button logout-button">Logout</a>
            <a href="profil.php" class="nav-button profile-button">Profile</a>
        </div>
    </header>

    <main class="dashboard-container">
        <h3 class="dashboard-title">Dashboard</h3>
        <p class="dashboard-description">
            Your personal companion for nutrition and wellness. Choose from our
            features below to get started.
        </p>

        <section class="features-container">
            <article class="feature-card">
                <div class="feature-icon">🤖</div>
                <h2 class="feature-title">MOodBot</h2>
                <p class="feature-description">
                    Get personalized nutrition advice and wellness tips.
                </p>
                <a href="chatbot.html" class="feature-button">Launch ChatBot</a>
            </article>

            <article class="feature-card">
                <div class="feature-icon">😊</div>
                <h2 class="feature-title">MoodTracker</h2>
                <p class="feature-description">
                    Track and analyze your daily mood patterns.
                </p>
                <a href="moodtracker.php" class="feature-button">Launch MoodTracker</a>
            </article>

            <article class="feature-card">
                <div class="feature-icon">🎮</div>
                <h2 class="feature-title">NutriCatch</h2>
                <p class="feature-description">
                    Learn about nutrition through fun mini-games.
                </p>
                <a href="nutricatch.html" class="feature-button">Launch NutriCatch</a>
            </article>
        </section>
    </main>
</body>
</html>
<?php } ?>
