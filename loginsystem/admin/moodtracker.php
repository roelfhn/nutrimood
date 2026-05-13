<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
  // If not logged in, redirect to login page
  header("Location: login.php");
  exit();
}

// Get user info from session
$userId = $_SESSION['id'];
$userName = $_SESSION['name'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MoodTracker</title>
  <link rel="icon" href="./image/logo.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg,rgb(126, 202, 137) 0%, #fefefe 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    .container {
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0px 8px 30px rgba(34, 197, 94, 0.2);
      padding: 40px 30px;
      text-align: center;
      width: 100%;
      max-width: 1000px;
      position: relative;
      z-index: 1;
      box-sizing: border-box;
    }

    h1 {
      color:rgb(8, 145, 58);
      font-size: clamp(2rem, 5vw, 3rem);
      margin-bottom: 30px;
      font-weight: bold;
      position: relative;
    }

    h1::after {
      content: "";
      display: block;
      width: 60px;
      height: 3px;
      background-color: #22c55e;
      margin: 10px auto 0;
      border-radius: 5px;
    }

    /* Month & year selectors */
    .selectors {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    select {
      padding: 10px 15px;
      border-radius: 6px;
      border: 1.5px solid #ccc;
      font-size: 16px;
      min-width: 130px;
      transition: border-color 0.3s ease;
    }
    select:focus {
      border-color: #22c55e;
      outline: none;
    }

    /* Calendar */
    .calendar {
      width: 100%;
      max-width: 900px;
      margin: 0 auto 35px;
    }

    /* Calendar day cells formatting */
    .fc-day, 
    .fc-day-top, 
    .fc-day-number {
      padding: 0 !important;
      height: 75px !important;
      vertical-align: top !important;
      position: relative;
      font-size: 14px;
      box-sizing: border-box;
    }

    /* Day number */
    .fc-day-number {
      position: absolute;
      top: 4px;
      right: 6px;
      font-weight: 600;
      color: #666;
      z-index: 3;
      font-size: 13px;
      user-select: none;
    }

    /* Mood circle with emoji */
    .fc-day .day-circle {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin: 20px auto 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      position: relative;
      z-index: 2;
      background-color: transparent;
      transition: background-color 0.3s ease;
      user-select: none;
      box-shadow: 0 0 6px rgba(0,0,0,0.05);
    }

    .fc-event { display: none !important; }

    /* Mood options */
    .mood-options {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .mood-option {
      width: 80px;
      height: 80px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 40px;
      text-align: center;
      border-radius: 50%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      user-select: none;
    }

    .mood-option:hover {
      transform: scale(1.15);
    }

   #selectedMood button {
      margin-top: 10px;
      padding: 10px 10px;
      background-color: #39ac31;
      border: none;
      border-radius: 6px;
      color: white;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease, transform 0.2s ease;
      user-select: none;
      display: inline-block;
      width: auto;
      max-width: 200px;
      white-space: nowrap;
    }

    #selectedMood button:hover {
      background-color: #2e8b2e;
      transform: scale(1.05);
    }

    /* Mood history */
    #moodHistory {
      margin-top: 20px;
      text-align: left;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      color: #333;
      font-size: 16px;
      user-select: none;
    }

    /* Decorative bubbles */
    .bubble {
      position: absolute;
      border-radius: 50%;
      opacity: 0.15;
      z-index: 0;
      pointer-events: none;
    }

    .bubble1 {
      width: 120px;
      height: 120px;
      background: #22c55e;
      top: 20%;
      left: -60px;
    }

    .bubble2 {
      width: 200px;
      height: 200px;
      background: #a3e635;
      bottom: 10%;
      right: -100px;
    }

    .bubble3 {
      width: 80px;
      height: 80px;
      background: #bef264;
      top: 60%;
      left: 20%;
    }

    /* User info bar */
    .user-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding: 10px 15px;
      background-color: #f9f9f9;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .logout-btn {
      padding: 8px 12px;
      background-color: #f44336;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #d32f2f;
    }

    /* Responsive tweaks */
    @media (max-width: 640px) {
      .fc-day, .fc-day-number {
        height: 60px !important;
      }
      .fc-day .day-circle {
        width: 40px;
        height: 40px;
        font-size: 22px;
        margin-top: 15px;
      }
      .mood-option {
        width: 60px;
        height: 60px;
        font-size: 30px;
      }
      .selectors {
        gap: 15px;
      }
      select {
        min-width: 100px;
        font-size: 14px;
        padding: 8px 10px;
      }
    }
  </style>
</head>
<body>
  <div class="bubble bubble1"></div>
  <div class="bubble bubble2"></div>
  <div class="bubble bubble3"></div>

  <div class="container">
    <h1>MoodTracker</h1>

    <div class="user-info">
      <span id="welcomeMessage">Welcome <?php echo htmlspecialchars($userName); ?>!</span>
      <a href="dashboard.php" class="logout-btn">Go Back</a>
    </div>

    <div class="selectors">
      <select id="monthSelector" onchange="changeMonthYear()"></select>
      <select id="yearSelector" onchange="changeMonthYear()"></select>
    </div>

    <div id="calendar" class="calendar"></div>

    <div>
      <h2>How are you feeling today?</h2>
      <div class="mood-options">
        <div class="mood-option" style="background-color: #FFEB3B;" onclick="selectMood('😊 Happy')">😊</div>
        <div class="mood-option" style="background-color: #2196F3;" onclick="selectMood('😢 Sad')">😢</div>
        <div class="mood-option" style="background-color: #F44336;" onclick="selectMood('😰 Stressed')">😰</div>
        <div class="mood-option" style="background-color: #8BC34A;" onclick="selectMood('😴 Tired')">😴</div>
        <div class="mood-option" style="background-color: #FFC107;" onclick="selectMood('⚡ Energetic')">⚡</div>
      </div>
      <div id="selectedMood" class="mood-selected"></div>
      <div id="moodHistory" style="display:none;"></div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>
  <script>
    // Track selected date and mood data
    let selectedDate = null;
    let moodData = {};

    // Constants for mood colors
    const colorMap = {
      "😊 Happy": "#FFEB3B",
      "😢 Sad": "#2196F3",
      "😰 Stressed": "#F44336",
      "😴 Tired": "#8BC34A",
      "⚡ Energetic": "#FFC107"
    };

    // Initialize everything when document is ready
    $(document).ready(function() {
      populateYearSelector();
      populateMonthSelector();
      initializeCalendar();
      loadUserMoodData();
    });

    // Initialize FullCalendar
    function initializeCalendar() {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month'
        },
        selectable: true,
        dayRender: function(date, cell) {
          const formattedDate = date.format('YYYY-MM-DD');
          cell.html(`
            <div class="day-circle" data-date="${formattedDate}"></div>
            <div class="fc-day-number">${date.date()}</div>
          `);
          if (moodData[formattedDate]) {
            renderMoodCircle(formattedDate, moodData[formattedDate]);
          }
        },
        dayClick: function(date) {
          selectedDate = date.format('YYYY-MM-DD');
          showSelectedMood('');
        },
        viewRender: function() {
          renderAllMoods();
          updateSelectors();
        }
      });
    }

    // Load user mood data using AJAX
    function loadUserMoodData() {
      $.ajax({
        url: 'get-moods.php', // You'll need to create this PHP file
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          if (data.success) {
            moodData = data.moods;
            renderAllMoods();
          } else {
            console.error('Failed to load mood data:', data.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading mood data:', error);
        }
      });
    }

    // Save mood to database with AJAX
    function selectMood(mood) {
      if (!selectedDate) {
        alert('Please select a date first.');
        return;
      }
      
      $.ajax({
        url: 'save-mood.php', // You'll need to create this PHP file
        type: 'POST',
        data: {
          date: selectedDate,
          mood: mood
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            moodData[selectedDate] = mood;
            renderMoodCircle(selectedDate, mood);
            showSelectedMood(mood);
          } else {
            alert('Failed to save your mood: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Error saving mood:', error);
          alert('Failed to save your mood. Please try again.');
        }
      });
    }

    // Display mood in calendar
    function renderMoodCircle(date, mood) {
      const cell = $(`.fc-day[data-date="${date}"]`);
      const emoji = mood.split(' ')[0];
      const circle = cell.find('.day-circle');
      const color = colorMap[mood];
      if (circle.length) {
        circle.text(emoji);
        circle.css('background-color', color);
      }
    }

    // Display all moods on calendar
    function renderAllMoods() {
      for (const date in moodData) {
        if (moodData.hasOwnProperty(date)) {
          renderMoodCircle(date, moodData[date]);
        }
      }
    }

    // Show selected mood status
    function showSelectedMood(mood) {
      const display = selectedDate ? `Mood for ${selectedDate}: ${mood}` : '';
      const button = `<button onclick="toggleMoodHistory()">SHOW MOOD HISTORY</button>`;
      $('#selectedMood').html(display + '<br>' + button).fadeIn(300);
    }

    // Toggle mood history display
    function toggleMoodHistory() {
      const historyDiv = document.getElementById('moodHistory');
      if (historyDiv.style.display === "none" || historyDiv.style.display === "") {
        let historyHtml = '<strong>Mood History:</strong><ul>';
        const sortedDates = Object.keys(moodData).sort((a, b) => new Date(b) - new Date(a));
        sortedDates.forEach(date => {
          historyHtml += `<li>${date}: ${moodData[date]}</li>`;
        });
        historyHtml += '</ul>';
        historyDiv.innerHTML = historyHtml;
        historyDiv.style.display = "block";
      } else {
        historyDiv.style.display = "none";
      }
    }

    // Helper function to populate year selector
    function populateYearSelector() {
      const yearSelector = document.getElementById('yearSelector');
      const currentYear = new Date().getFullYear();
      for (let year = currentYear - 10; year <= currentYear + 10; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.text = year;
        if (year === currentYear) option.selected = true;
        yearSelector.appendChild(option);
      }
    }

    // Helper function to populate month selector
    function populateMonthSelector() {
      const monthSelector = document.getElementById('monthSelector');
      const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
      const now = new Date();
      months.forEach((month, i) => {
        const option = document.createElement('option');
        option.value = i;
        option.text = month;
        if (i === now.getMonth()) option.selected = true;
        monthSelector.appendChild(option);
      });
    }

    // Change calendar view to selected month/year
    function changeMonthYear() {
      const month = document.getElementById('monthSelector').value;
      const year = document.getElementById('yearSelector').value;
      const date = moment(`${year}-${parseInt(month)+1}-01`, 'YYYY-MM-DD');
      $('#calendar').fullCalendar('gotoDate', date);
    }

    // Update selectors to match calendar view
    function updateSelectors() {
      const currentDate = $('#calendar').fullCalendar('getDate');
      document.getElementById('monthSelector').value = currentDate.month();
      document.getElementById('yearSelector').value = currentDate.year();
    }
  </script>
</body>
</html>