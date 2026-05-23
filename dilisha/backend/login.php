<?php
session_start();
include '../configuration/config.php';

$usernameOrEmail = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($usernameOrEmail) || empty($password)) {
    echo "Please fill in both fields.";
    exit;
}

$sql = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/user.php");
        } elseif ($user['role'] === 'staff') {
            header("Location: ../staff/user.php");
        } else {
            header("Location: ../customerDashboard/customerhome.php");
        }
        exit;
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ User not found.";
}

$stmt->close();
$conn->close();
?>
