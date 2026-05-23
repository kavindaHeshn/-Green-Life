<?php
header('Content-Type: application/json');
session_start();

// Database connection configuration
$host = "localhost";
$user = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "GreenLife";

// Create connection
try {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit();
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection error: ' . $e->getMessage()]);
    exit();
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
    exit();
}

// Clear CSRF token after use
unset($_SESSION['csrf_token']);

// Validate and process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $treatment = isset($_POST['treatment']) ? trim($_POST['treatment']) : '';
    $preferred_date = isset($_POST['preferred_date']) ? trim($_POST['preferred_date']) : '';
    $preferred_time = isset($_POST['preferred_time']) ? trim($_POST['preferred_time']) : '';
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

    // Server-side validation
    if (empty($name) || empty($email) || empty($treatment) || empty($preferred_date) || empty($preferred_time)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All required fields (Name, Email, Treatment, Date, Time) are required.']);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }

    // Validate date (must be today or future)
    $today = date('Y-m-d');
    if ($preferred_date < $today) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Preferred date must be today or in the future.']);
        exit();
    }

    // Validate treatment against allowed values
    $allowed_packages = [
        'Panchakarma Therapy', 'Herbal Remedies', 'Yoga & Meditation', 
        'Personalized Diet', 'Counselling'
    ];
    if (!in_array($treatment, $allowed_packages)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid treatment selected.']);
        exit();
    }

    // Sanitize notes to prevent XSS or other issues
    $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

    // Prepare SQL query using prepared statements
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, package_name, preferred_date, preferred_time, notes) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Query preparation failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("ssssss", $name, $email, $treatment, $preferred_date, $preferred_time, $notes);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Your appointment has been successfully booked!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error saving booking: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}

// Close connection
$conn->close();
?>