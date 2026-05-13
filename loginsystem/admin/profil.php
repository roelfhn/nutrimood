<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once './includes/config.php'; // Pastikan file config.php berisi koneksi database

$userId = $_SESSION['id'];

// Function to get user profile data
function getUserProfile($con, $userId) {
    // Modified query to match the signup structure
    $stmt = $con->prepare("SELECT up.*, u.fname, u.lname, u.email as user_email, u.contactno 
                           FROM user_profiles up 
                           RIGHT JOIN users u ON up.user_id = u.id 
                           WHERE u.id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // Combine fname and lname if name is not set in profile
        if (empty($data['name']) && !empty($data['fname'])) {
            $data['name'] = trim($data['fname'] . ' ' . $data['lname']);
        }
        // Use contactno if phone is not set
        if (empty($data['phone']) && !empty($data['contactno'])) {
            $data['phone'] = $data['contactno'];
        }
        return $data;
    } else {
        // If no profile exists, return basic user data
        $stmt = $con->prepare("SELECT id, fname, lname, email, contactno FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        if ($userData) {
            $userData['name'] = trim($userData['fname'] . ' ' . $userData['lname']);
            $userData['phone'] = $userData['contactno'];
            $userData['user_email'] = $userData['email'];
        }
        return $userData;
    }
}

// Get user profile data
$profileData = getUserProfile($con, $userId);

// Handle AJAX requests for profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'update_profile') {
        try {
            // Check if profile exists
            $checkStmt = $con->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
            $checkStmt->bind_param("i", $userId);
            $checkStmt->execute();
            $exists = $checkStmt->get_result()->num_rows > 0;
            
            if ($exists) {
                // Update existing profile
                $stmt = $con->prepare("UPDATE user_profiles SET 
                    name = ?, phone = ?, birth_date = ?, gender = ?, 
                    height = ?, weight = ?, blood_type = ?, allergies = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = ?");
                $stmt->bind_param("ssssddssi", 
                    $_POST['name'], $_POST['phone'], $_POST['birth_date'], 
                    $_POST['gender'], $_POST['height'], $_POST['weight'], 
                    $_POST['blood_type'], $_POST['allergies'], $userId);
            } else {
                // Insert new profile - get user email from users table
                $emailStmt = $con->prepare("SELECT email FROM users WHERE id = ?");
                $emailStmt->bind_param("i", $userId);
                $emailStmt->execute();
                $emailResult = $emailStmt->get_result();
                $userEmail = $emailResult->fetch_assoc()['email'];
                
                $stmt = $con->prepare("INSERT INTO user_profiles 
                    (user_id, name, email, phone, birth_date, gender, height, weight, blood_type, allergies) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssssddss", 
                    $userId, $_POST['name'], $userEmail, $_POST['phone'], 
                    $_POST['birth_date'], $_POST['gender'], $_POST['height'], 
                    $_POST['weight'], $_POST['blood_type'], $_POST['allergies']);
            }
            
            if ($stmt->execute()) {
                // Parse name and update users table with fname and lname
                $fullName = trim($_POST['name']);
                $nameParts = explode(' ', $fullName, 2);
                $fname = $nameParts[0];
                $lname = isset($nameParts[1]) ? $nameParts[1] : '';
                
                $updateUserStmt = $con->prepare("UPDATE users SET fname = ?, lname = ?, contactno = ? WHERE id = ?");
                $updateUserStmt->bind_param("sssi", $fname, $lname, $_POST['phone'], $userId);
                $updateUserStmt->execute();
                
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit();
    }
    
    if ($_POST['action'] === 'upload_image') {
        // Handle profile picture upload
        if (isset($_FILES['profile_image'])) {
            $uploadDir = 'uploads/profiles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = time() . '_' . $_FILES['profile_image']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                // Check if profile exists, if not create one
                $checkStmt = $con->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
                $checkStmt->bind_param("i", $userId);
                $checkStmt->execute();
                $exists = $checkStmt->get_result()->num_rows > 0;
                
                if ($exists) {
                    $stmt = $con->prepare("UPDATE user_profiles SET profile_picture = ? WHERE user_id = ?");
                    $stmt->bind_param("si", $uploadPath, $userId);
                } else {
                    // Create basic profile entry for image
                    $userStmt = $con->prepare("SELECT fname, lname, email, contactno FROM users WHERE id = ?");
                    $userStmt->bind_param("i", $userId);
                    $userStmt->execute();
                    $userData = $userStmt->get_result()->fetch_assoc();
                    
                    $fullName = trim($userData['fname'] . ' ' . $userData['lname']);
                    $stmt = $con->prepare("INSERT INTO user_profiles (user_id, name, email, phone, profile_picture) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("issss", $userId, $fullName, $userData['email'], $userData['contactno'], $uploadPath);
                }
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'image_path' => $uploadPath]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update image path']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            }
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NutriMOod-Profile</title>
    <link rel="icon" href="./image/logo.png" type="image/png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Arvo"
    />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
      * {
        box-sizing: border-box;
      }
      body {
        font-family: "Arvo", serif;
        margin: 0;
        background-color: linear-gradient(135deg, #b5e7bc 0%, #fefefe 100%);
        background: linear-gradient(135deg, #a8e0af 0%, #fefefe 100%);
        position: relative;
      }
      /* Tombol home kecil di pojok kanan atas */
      .home-button {
        position: fixed;
        top: 10px;
        right: 10px;
        background-color: #4caf50;
        color: white;
        border: none;
        padding: 6px 10px;
        font-size: 0.85rem;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
      }
      .home-button:hover {
        background-color: #388e3c;
      }

      /* Decorative bubbles */
    .bubble {
      position: absolute;
      border-radius: 50%;
      opacity: 0.15;
      z-index: 0;
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

      main {
        padding: 2rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
      }
      .card {
        background: linear-gradient(135deg, #57c766 0%, #ffdfdf 100%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        margin-bottom: 2rem;
      }
      .profile-top {
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
      }
      .profile-pic {
        position: relative;
      }
      .profile-pic img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
      }
      .edit-pic-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: #28a745;
        color: white;
        padding: 4px 6px;
        border-radius: 50%;
        font-size: 12px;
        cursor: pointer;
      }
      .edit-button {
        margin-left: auto;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
      }
      .edit-button:hover {
        background-color: #1e7e34;
      }
      .profile-section {
        margin-top: 1rem;
      }
      .profile-section h3 {
        color: #28a745;
        margin-bottom: 0.5rem;
      }
      .section-title {
        font-weight: bold;
        color: #28a745;
        margin-top: 1em;
      }
      .gender-options {
        display: flex;
        gap: 20px;
        margin-bottom: 10px;
      }
      .form-check-inline {
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .badge-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 0.5em;
      }
      .badge {
        background-color: #ffe6e6;
        color: #d10000;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
      }
      input::placeholder {
        color: #aaa;
        font-style: italic;
      }

      .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
      }
      .modal {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
      }
      .modal-close {
        position: absolute;
        top: 8px;
        right: 12px;
        font-size: 1.2rem;
        background: none;
        border: none;
        cursor: pointer;
        color: #666;
      }
      .modal-close:hover {
        color: #000;
      }

      .save-btn {
        background-color: #4caf50;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 1rem;
        width: 100%;
      }
      .save-btn:hover {
        background-color: #0056b3;
      }

      form label {
        display: block;
        margin: 10px 0 4px;
        font-size: 0.9rem;
      }
      form input,
      form select {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
      }

      .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
      }

      .form-check-input {
        width: 16px;
        height: 16px;
      }

      .hidden {
        display: none;
      }

      .loading {
        opacity: 0.6;
        pointer-events: none;
      }

      /* Custom SweetAlert2 styles */
      .my-actions {
        margin: 2em 2em 0;
      }
      .order-1 {
        order: 1;
      }
      .order-2 {
        order: 2;
      }
      .order-3 {
        order: 3;
      }
      .right-gap {
        margin-right: auto;
      }

      @media (max-width: 480px) {
        .edit-button {
          margin: 10px 0 0 auto;
        }
      }
    </style>
  </head>
  <body>
    <!-- Decorative bubbles -->
  <div class="bubble bubble1"></div>
  <div class="bubble bubble2"></div>
  <div class="bubble bubble3"></div>

    <!-- Tombol home kecil -->
    <a href="dashboard.php" class="home-button" title="Home">Home</a>

    <main>
      <div class="card" id="profileCard">
        <div class="profile-top">
          <div class="profile-pic">
            <img
              src="<?php echo !empty($profileData['profile_picture']) ? htmlspecialchars($profileData['profile_picture']) : 'https://cdn-icons-png.flaticon.com/512/706/706830.png'; ?>"
              alt="Profile"
              id="profileImage"
            />
            <label for="profilePicInput" class="edit-pic-btn">📷</label>
            <input type="file" id="profilePicInput" accept="image/*" hidden />
          </div>
          <div class="profile-info">
            <h3 id="displayName"><?php echo htmlspecialchars($profileData['name'] ?? 'NutriMood User'); ?></h3>
            <p id="displayEmail"><?php echo htmlspecialchars($profileData['email'] ?? $profileData['user_email'] ?? 'user@nutrimood.com'); ?></p>
          </div>
          <button class="edit-button" onclick="toggleForm()">
            Edit Profile
          </button>
        </div>

        <hr />

        <div class="profile-section">
          <h3>Personal Info</h3>
          <p>
            <strong>Phone:</strong>
            <span id="displayPhone"><?php echo htmlspecialchars($profileData['phone'] ?? 'Not set'); ?></span>
          </p>
          <p>
            <strong>Birth Date:</strong> 
            <span id="displayDob"><?php echo htmlspecialchars($profileData['birth_date'] ?? 'Not set'); ?></span>
          </p>
          <p><strong>Gender:</strong> <span id="displayGender"><?php echo htmlspecialchars($profileData['gender'] ?? 'Not set'); ?></span></p>
        </div>

        <div class="profile-section">
          <h3>Health Info</h3>
          <p><strong>Height:</strong> <span id="displayHeight"><?php echo $profileData['height'] ? htmlspecialchars($profileData['height']) . ' cm' : 'Not set'; ?></span></p>
          <p><strong>Weight:</strong> <span id="displayWeight"><?php echo $profileData['weight'] ? htmlspecialchars($profileData['weight']) . ' kg' : 'Not set'; ?></span></p>
          <p><strong>Blood Type:</strong> <span id="displayBlood"><?php echo htmlspecialchars($profileData['blood_type'] ?? 'Not set'); ?></span></p>
          <p class="section-title">Allergies</p>
          <div id="displayAllergy" class="badge-container">
            <?php 
            if (!empty($profileData['allergies'])) {
                $allergies = explode(',', $profileData['allergies']);
                foreach ($allergies as $allergy) {
                    echo '<span class="badge">' . htmlspecialchars(trim($allergy)) . '</span>';
                }
            } else {
                echo '<span class="badge">No allergies recorded</span>';
            }
            ?>
          </div>
        </div>
      </div>

      <div class="modal-overlay hidden" id="editModal">
        <div class="modal">
          <button class="modal-close" onclick="closeModal()">✖</button>
          <h3>Edit Profile</h3>
          <form id="profileForm" onsubmit="event.preventDefault(); showSaveConfirmation();">
            <label>Name: <input type="text" id="nameInput" required /></label>
            <label>Email: <input type="email" id="emailInput" required readonly /></label>
            <label>Phone: <input type="text" id="phoneInput" /></label>
            <label>Birth Date: <input type="date" id="dobInput" /></label>
            <label>Gender:</label>
            <div class="gender-options">
              <label class="form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  name="gender"
                  id="genderMale"
                  value="Male"
                />
                Male
              </label>
              <label class="form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  name="gender"
                  id="genderFemale"
                  value="Female"
                />
                Female
              </label>
            </div>

            <label>Height (cm): <input type="number" id="heightInput" step="0.01" /></label>
            <label>Weight (kg): <input type="number" id="weightInput" step="0.01" /></label>
            <label>Blood Type:
              <select id="bloodInput">
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </label>
            <label>Allergies:
              <input
                type="text"
                id="allergyInput"
                placeholder="e.g., Peanuts, Shellfish"
              />
              <small>Use commas to separate multiple allergies</small>
            </label>
            <button type="submit" class="save-btn">Save Changes</button>
          </form>
        </div>
      </div>
    </main>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
      // Load profile data into form
      function toggleForm() {
        const modal = document.getElementById("editModal");
        modal.classList.remove("hidden");

        // Fill form with current data
        document.getElementById("nameInput").value = document.getElementById("displayName").innerText;
        document.getElementById("emailInput").value = document.getElementById("displayEmail").innerText;
        
        const phoneText = document.getElementById("displayPhone").innerText;
        document.getElementById("phoneInput").value = phoneText !== 'Not set' ? phoneText : '';
        
        const dobText = document.getElementById("displayDob").innerText;
        document.getElementById("dobInput").value = dobText !== 'Not set' ? dobText : '';

        const gender = document.getElementById("displayGender").innerText;
        if (gender === "Male") {
          document.getElementById("genderMale").checked = true;
        } else if (gender === "Female") {
          document.getElementById("genderFemale").checked = true;
        }

        const heightText = document.getElementById("displayHeight").innerText;
        if (heightText !== 'Not set') {
          document.getElementById("heightInput").value = parseFloat(heightText);
        }
        
        const weightText = document.getElementById("displayWeight").innerText;
        if (weightText !== 'Not set') {
          document.getElementById("weightInput").value = parseFloat(weightText);
        }
        
        const bloodText = document.getElementById("displayBlood").innerText;
        document.getElementById("bloodInput").value = bloodText !== 'Not set' ? bloodText : '';

        // Get allergies
        const allergyBadges = document.querySelectorAll("#displayAllergy .badge");
        const allergies = Array.from(allergyBadges)
          .map(badge => badge.innerText)
          .filter(text => text !== 'No allergies recorded')
          .join(", ");
        document.getElementById("allergyInput").value = allergies;
      }

      function closeModal() {
        document.getElementById("editModal").classList.add("hidden");
      }

      // Show SweetAlert2 confirmation before saving
      function showSaveConfirmation() {
        Swal.fire({
          title: 'Do you want to save the changes?',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Yes',
          denyButtonText: 'No',
          customClass: {
            actions: 'my-actions',
            cancelButton: 'order-1 right-gap',
            confirmButton: 'order-2',
            denyButton: 'order-3',
          },
        }).then((result) => {
          if (result.isConfirmed) {
            saveProfile();
          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info');
          }
        });
      }

      // Save profile with AJAX
      function saveProfile() {
        const formData = new FormData();
        formData.append('action', 'update_profile');
        formData.append('name', document.getElementById("nameInput").value);
        formData.append('email', document.getElementById("emailInput").value);
        formData.append('phone', document.getElementById("phoneInput").value);
        formData.append('birth_date', document.getElementById("dobInput").value);
        
        const genderElement = document.querySelector('input[name="gender"]:checked');
        formData.append('gender', genderElement ? genderElement.value : '');
        
        formData.append('height', document.getElementById("heightInput").value);
        formData.append('weight', document.getElementById("weightInput").value);
        formData.append('blood_type', document.getElementById("bloodInput").value);
        formData.append('allergies', document.getElementById("allergyInput").value);

        // Show loading state with SweetAlert2
        Swal.fire({
          title: 'Saving...',
          text: 'Please wait while we update your profile',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        fetch('', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update display with new values
            updateDisplayValues();
            closeModal();
            Swal.fire('Saved!', 'Your profile has been updated successfully', 'success');
          } else {
            Swal.fire('Error!', data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('Error!', 'An error occurred while saving your profile', 'error');
        });
      }

      // Update display values after save
      function updateDisplayValues() {
        document.getElementById("displayName").innerText = document.getElementById("nameInput").value;
        document.getElementById("displayEmail").innerText = document.getElementById("emailInput").value;
        document.getElementById("displayPhone").innerText = document.getElementById("phoneInput").value || 'Not set';
        document.getElementById("displayDob").innerText = document.getElementById("dobInput").value || 'Not set';

        const genderElement = document.querySelector('input[name="gender"]:checked');
        document.getElementById("displayGender").innerText = genderElement ? genderElement.value : 'Not set';

        const height = document.getElementById("heightInput").value;
        document.getElementById("displayHeight").innerText = height ? height + " cm" : 'Not set';
        
        const weight = document.getElementById("weightInput").value;
        document.getElementById("displayWeight").innerText = weight ? weight + " kg" : 'Not set';
        
        document.getElementById("displayBlood").innerText = document.getElementById("bloodInput").value || 'Not set';

        // Update allergies
        const allergyText = document.getElementById("allergyInput").value;
        const allergyList = allergyText
          .split(",")
          .map((item) => item.trim())
          .filter((item) => item !== "");

        const allergyContainer = document.getElementById("displayAllergy");
        allergyContainer.innerHTML = "";
        
        if (allergyList.length > 0) {
          allergyList.forEach((item) => {
            const badge = document.createElement("span");
            badge.className = "badge";
            badge.innerText = item;
            allergyContainer.appendChild(badge);
          });
        } else {
          const badge = document.createElement("span");
          badge.className = "badge";
          badge.innerText = "No allergies recorded";
          allergyContainer.appendChild(badge);
        }
      }

      // Handle profile picture upload with SweetAlert2
      const profilePicInput = document.getElementById("profilePicInput");
      const profileImage = document.getElementById("profileImage");

      profilePicInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
          // Show loading state
          Swal.fire({
            title: 'Uploading...',
            text: 'Please wait while we upload your profile picture',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          const formData = new FormData();
          formData.append('action', 'upload_image');
          formData.append('profile_image', file);

          fetch('', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              profileImage.src = data.image_path + '?t=' + new Date().getTime();
              Swal.fire('Success!', 'Profile picture updated successfully', 'success');
            } else {
              Swal.fire('Error!', 'Error uploading image: ' + data.message, 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'An error occurred while uploading the image', 'error');
          });
        }
      });
    </script>
  </body>
</html>