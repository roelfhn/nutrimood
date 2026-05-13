<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Choose your move!</title>
  <link rel="icon" href="./image/logo.png" type="image/png">

  <!-- ICONSCOUT Icons -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Arvo'>
  
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arvo', serif;
      background: linear-gradient(135deg,rgb(146, 216, 156) 0%, #fefefe 100%);
      color: #333;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header a {
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .header img {
      width: 50px;
      height: 50px;
      object-fit: contain;
    }

    .header .brand-name {
      font-size: 28px;
      font-weight: bold;
      color: #22c55e;
      margin: 0;
    }

    .header a:hover .brand-name {
      color: #16a34a;
    }

    .content {
      text-align: center;
      padding: 50px 20px;
    }

    .content h1 {
      font-size: 40px;
      margin-bottom: 40px;
      color: #1a1b47;
    }

    .menu-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      justify-content: center;
      max-width: 1200px;
      margin: 0 auto;
      padding-top: 20px;
    }

    .menu-item {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s, box-shadow 0.3s;
      overflow: hidden;
      cursor: pointer;
      text-align: center;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 500px;
    }

    .menu-item:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    }

    .menu-item .icon {
      width: 100%;
      height: 250px;
      background-color: #d4fcd9;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .menu-item .icon img {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
    }

    .menu-item h3 {
      font-size: 24px;
      color: #22c55e;
      margin: 20px 0 10px;
    }

    .menu-item p {
      font-size: 16px;
      color: #555;
      margin: 0 20px 20px;
    }

    .menu-item .play-button {
      padding: 10px 20px;
      background-color: #22c55e;
      color: white;
      text-decoration: none;
      font-size: 16px;
      border-radius: 10px;
      display: inline-block;
      transition: background-color 0.3s;
      margin-top: auto;
    }

    .menu-item .play-button:hover {
      background-color: #16a34a;
    }

    .gif-image {
      width: 250px;
      height: auto;
      margin: 5px auto;
      display: block;
    }
  </style>
</head>
<body>

  <!-- Background Music -->
  <audio autoplay loop>
    <source src="./music/doodle.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
  </audio>

  <!-- Header -->
  <div class="header">
    <a href="../index.html">
      <img src="./image/logo.png" alt="NutriMOod Logo">
      <h1 class="brand-name">NutriMOod</h1>
    </a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="menu-container">

      <!-- Registrasi Section -->
      <div class="menu-item">
        <div class="icon">
          <img src="image/B.gif" alt="Signup GIF" class="gif-image">
        </div>
        <h3>Register</h3>
        <p>Yuk gabung dan mulai hidup sehat bersama NutriMood! Daftarkan dirimu untuk petualangan seru dan bergizi! 💚</p>
        <a href="signup.php" class="play-button">Register Now</a>
      </div>

      <!-- Login Section -->
      <div class="menu-item">
        <div class="icon">
          <img src="image/D.gif" alt="Login GIF" class="gif-image">
        </div>
        <h3> Login</h3>
        <p>Sudah punya akun? Masuk dan lanjutkan perjalanan bahagia & seimbangmu hari ini! ✨</p>
        <a href="login.php" class="play-button">Login Here</a>
      </div>

      <!-- Admin Section -->
      <div class="menu-item">
        <div class="icon">
          <img src="image/A.gif" alt="Admin GIF" class="gif-image">
        </div>
        <h3> Admin</h3>
        <p>Kelola pengguna, data, dan semangat positif komunitas kita dengan cerdas dan bijak! 🌱</p>
        <a href="admin/index.php" class="play-button">Admin Only!</a>
      </div>

    </div>
  </div>

</body>
</html>
