<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greenlife";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('<div class="error">Connection failed: ' . $conn->connect_error . '</div>');
}
$conn->set_charset("utf8mb4");

// Handle form submission for editing feedback
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_feedback'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];

    $sql = "UPDATE feedback SET name = ?, email = ?, feedback = ?, rating = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $feedback, $rating, $id);

    if ($stmt->execute()) {
        echo '<div class="success">Feedback updated successfully</div>';
    } else {
        echo '<div class="error">Error updating feedback: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_feedback'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM feedback WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<div class="success">Feedback deleted successfully</div>';
    } else {
        echo '<div class="error">Error deleting feedback: ' . $conn->error . '</div>';
    }
    $stmt->close();
}

// Fetch all feedback entries
$sql = "SELECT * FROM feedback ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GreenLife Admin Dashboard - Manage Feedback</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=block" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @font-face {
      font-family: 'Algerian';
      src: url('https://fonts.cdnfonts.com/css/algerian') format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    /* Base styles */
    body {
      background-color: #e8f5e9;
      color: #333;
      font-family: 'Poppins', Arial, sans-serif;
      font-weight: 600;
      margin: 0;
      scroll-behavior: smooth;
    }

    /* Sidebar */
    .sidebar {
      width: 16rem;
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
      color: #ffca28;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 100vh;
    }

    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
    }
    .logo img {
      width: 100%;
      max-width: 8rem;
      height: auto;
      border-radius: 0.5rem;
      border: 2px solid #ffca28;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      animation: fadeIn 0.8s ease forwards;
      opacity: 0;
    }

    .nav-link {
      display: block;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      transition: background-color 0.3s ease, transform 0.3s ease;
      transform: translateX(-20px);
      opacity: 0;
      animation: slideIn 0.5s ease forwards;
      animation-delay: calc(0.1s * var(--index));
      color: #ffca28;
      position: relative;
      text-decoration: none;
      font-weight: 700;
    }
    .nav-link:hover {
      background-color: #b74213;
      transform: scale(1.03);
    }
    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 1rem;
      background-color: #4caf50;
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: calc(100% - 2rem);
    }
    .nav-link.active {
      background-color: #4caf50;
      font-weight: 800;
    }

    .btn-logout, .btn-language {
      width: 100%;
      background-color: #4caf50;
      color: #ffffff;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      font-weight: 700;
      transition: background-color 0.3s ease, transform 0.2s ease;
      border: none;
      cursor: pointer;
      margin-bottom: 1rem;
      text-align: center;
    }
    .btn-language {
      background-color: #ffca28;
      color: #333;
    }
    .btn-logout:hover, .btn-language:hover {
      animation: rotate 0.7s ease-in-out both;
    }
    .btn-logout:hover {
      background-color: #2e7d32;
    }
    .btn-language:hover {
      background-color: #b74213;
      color: #ffffff;
    }
    .btn-logout:hover span, .btn-language:hover span {
      animation: storm 0.7s ease-in-out both;
      animation-delay: 0.06s;
    }

    .main-content {
      flex: 1;
      padding: 2rem;
      animation: fadeIn 0.8s ease forwards;
      opacity: 0;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    .header h2 {
      font-family: 'Algerian', serif;
      font-size: 1.875rem;
      font-weight: 800;
      color: #b74213;
    }
    .header p {
      color: #333;
      font-weight: 600;
    }

    .flex-container {
      display: flex;
      min-height: 100vh;
    }

    /* Table styling */
    .feedback-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 0.5rem;
      border: 2px solid #ffca28;
      overflow: hidden;
    }
    .feedback-table th, .feedback-table td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #ccc;
      font-weight: 600;
    }
    .feedback-table th {
      background-color: #4caf50;
      color: #ffffff;
      font-weight: 700;
    }
    .feedback-table tr:hover {
      background-color: #e8f4e8;
    }

    .btn-edit, .btn-delete {
      background-color: #ffca28;
      color: #333;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-right: 0.5rem;
    }
    .btn-edit:hover, .btn-delete:hover {
      background-color: #b74213;
      color: #ffffff;
      animation: rotate 0.7s ease-in-out both;
    }
    .btn-delete {
      background-color: #dc3545;
      color: #ffffff;
    }
    .btn-delete:hover {
      background-color: #b91c1c;
    }

    /* Form styling */
    .edit-form {
      background-color: #ffffff;
      padding: 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border: 2px solid #ffca28;
      margin-top: 2rem;
      display: none;
    }
    .edit-form h3 {
      font-family: 'Algerian', serif;
      font-size: 2rem;
      color: #b74213;
      margin-bottom: 1rem;
    }
    .edit-form label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #b74213;
    }
    .edit-form input, .edit-form textarea, .edit-form select {
      width: 100%;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #f9fafb;
      color: #333;
      font-weight: 600;
    }
    .edit-form input:focus, .edit-form textarea:focus, .edit-form select:focus {
      border-color: #ffca28;
      outline: none;
    }
    .edit-form textarea {
      resize: vertical;
      min-height: 100px;
    }
    .edit-form button {
      background-color: #4caf50;
      color: #ffffff;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .edit-form button:hover {
      background-color: #2e7d32;
      animation: rotate 0.7s ease-in-out both;
    }
    .edit-form button:hover span {
      animation: storm 0.7s ease-in-out both;
      animation-delay: 0.06s;
    }

    /* Success/Error Messages */
    .success, .error {
      text-align: center;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-weight: 600;
    }
    .success {
      color: #4caf50;
      background-color: #e8f4e8;
    }
    .error {
      color: #dc3545;
      background-color: #f8d7da;
    }

    /* Animations */
    @keyframes slideIn {
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
      }
    }

    @keyframes rotate {
      0% { transform: rotate(0deg) translate3d(0, 0, 0); }
      25% { transform: rotate(3deg) translate3d(0, 0, 0); }
      50% { transform: rotate(-3deg) translate3d(0, 0, 0); }
      75% { transform: rotate(1deg) translate3d(0, 0, 0); }
      100% { transform: rotate(0deg) translate3d(0, 0, 0); }
    }

    @keyframes storm {
      0% { transform: translate3d(0, 0, 0) translateZ(0); }
      25% { transform: translate3d(4px, 0, 0) translateZ(0); }
      50% { transform: translate3d(-3px, 0, 0) translateZ(0); }
      75% { transform: translate3d(2px, 0, 0) translateZ(0); }
      100% { transform: translate3d(0, 0, 0) translateZ(0); }
    }

    /* Accessibility */
    a:focus, button:focus, input:focus, select:focus, textarea:focus {
      outline: 2px solid #ffca28;
      outline-offset: 2px;
    }

    /* Responsive design */
    @media screen and (max-width: 768px) {
      .flex-container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        min-height: auto;
      }
      .feedback-table th, .feedback-table td {
        padding: 8px;
        font-size: 14px;
      }
      .edit-form input, .edit-form textarea, .edit-form select {
        width: 100%;
      }
    }

    @media screen and (max-width: 480px) {
      .header h2 {
        font-size: 1.8rem;
      }
      .edit-form h3 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="flex-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div>
        <div class="logo">
          <img src="../photos/logo.png" alt="GreenLife Wellness Center Logo" aria-label="GreenLife Wellness Center Logo">
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center" style="font-family: 'Algerian', serif;">GreenLife</h1>
        <button id="languageToggle" class="btn-language" aria-label="Switch language"><span>Switch to Sinhala</span></button>
        <nav role="navigation" aria-label="Admin navigation">
          <ul>
            <li><a href="contactus.php" class="nav-link" data-key="inquiries" style="--index: 1;" aria-label="Manage inquiries">Manage Inquiries</a></li>
            <li><a href="bookings.php" class="nav-link" data-key="bookings" style="--index: 2;" aria-label="Manage bookings">Manage Bookings</a></li>
            <li><a href="user.php" class="nav-link" data-key="users" style="--index: 3;" aria-label="Manage users">Manage Users</a></li>
            <li><a href="feedback.php" class="nav-link active" data-key="feedback" style="--index: 4;" aria-label="Manage feedback">Manage Feedback</a></li>
          </ul>
        </nav>
      </div>
      <div class="mt-6">
        <button id="logoutBtn" class="btn-logout" aria-label="Logout"><span>Logout</span></button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="header">
        <div>
          <h2>Manage Feedback</h2>
          <p>View, edit, and delete feedback submitted by users.</p>
        </div>
      </div>

      <!-- Feedback Table -->
      <table class="feedback-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Feedback</th>
            <th>Rating</th>
            <th>Submitted At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['feedback']); ?></td>
              <td><?php echo htmlspecialchars($row['rating']); ?></td>
              <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
              <td>
                <button class="btn-edit" onclick="showEditForm(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(str_replace("'", "\'", $row['name'])); ?>', '<?php echo htmlspecialchars(str_replace("'", "\'", $row['email'])); ?>', '<?php echo htmlspecialchars(str_replace("'", "\'", $row['feedback'])); ?>', <?php echo $row['rating']; ?>)" aria-label="Edit feedback from <?php echo htmlspecialchars($row['name']); ?>"><span>Edit</span></button>
                <form method="POST" action="feedback.php" style="display:inline;">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <button type="submit" name="delete_feedback" class="btn-delete" onclick="return confirm('Are you sure you want to delete this feedback?')" aria-label="Delete feedback from <?php echo htmlspecialchars($row['name']); ?>"><span>Delete</span></button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Edit Feedback Form -->
      <div class="edit-form" id="editForm">
        <h3>Edit Feedback</h3>
        <form method="POST" action="feedback.php">
          <input type="hidden" name="id" id="editId">
          <label for="editName"><i class="fas fa-user"></i> Name:</label>
          <input type="text" name="name" id="editName" required aria-label="Name">
          <label for="editEmail"><i class="fas fa-envelope"></i> Email:</label>
          <input type="email" name="email" id="editEmail" required aria-label="Email">
          <label for="editFeedback"><i class="fas fa-comment-dots"></i> Feedback:</label>
          <textarea name="feedback" id="editFeedback" required aria-label="Feedback"></textarea>
          <label for="editRating"><i class="fas fa-star"></i> Rating (1-5):</label>
          <select name="rating" id="editRating" required aria-label="Rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
          <button type="submit" name="edit_feedback" aria-label="Update feedback"><span>Update Feedback</span></button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Translation data
    const translations = {
      en: {
        inquiries: 'Manage Inquiries',
        users: 'Manage Users',
        feedback: 'Manage Feedback',
        bookings: 'Manage Bookings',
        languageToggle: 'Switch to Sinhala'
      },
      si: {
        inquiries: 'පරීක්ෂණ කළමනාකරණය',
        users: 'පරිශීලකයින් කළමනාකරණය',
        feedback: 'ප්‍රතිපෝෂණ කළමනාකරණය',
        bookings: 'වෙන්කිරීම් කළමනාකරණය',
        languageToggle: 'Switch to English'
      }
    };

    // Initialize language
    let currentLanguage = localStorage.getItem('language') || 'en';
    updateLanguage(currentLanguage);

    // Language toggle
    document.getElementById('languageToggle').addEventListener('click', () => {
      currentLanguage = currentLanguage === 'en' ? 'si' : 'en';
      localStorage.setItem('language', currentLanguage);
      updateLanguage(currentLanguage);
    });

    // Update navigation links
    function updateLanguage(lang) {
      document.querySelectorAll('.nav-link').forEach(link => {
        const key = link.getAttribute('data-key');
        link.textContent = translations[lang][key];
      });
      document.getElementById('languageToggle').querySelector('span').textContent = translations[lang].languageToggle;
    }

    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', () => {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'login.html';
      }
    });

    // Active link highlighting
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
      });
    });

    // Show edit form with pre-filled data
    function showEditForm(id, name, email, feedback, rating) {
      document.getElementById('editId').value = id;
      document.getElementById('editName').value = name;
      document.getElementById('editEmail').value = email;
      document.getElementById('editFeedback').value = feedback;
      document.getElementById('editRating').value = rating;
      document.getElementById('editForm').style.display = 'block';
    }
  </script>
</body>
</html>

<?php
$conn->close();
?>