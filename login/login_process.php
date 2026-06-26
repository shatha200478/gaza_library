<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // تم التعديل هنا ليقرأ الحقل كـ username متوافقاً مع قاعدة بياناتك
    $username = $conn->real_escape_string(trim($_POST['email'])); 
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // تم التعديل هنا ليصبح البحث بناءً على عمود username
        $result = $conn->query("SELECT * FROM users WHERE username='$username'");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role']; 

                if ($user['role'] == 'admin') {
                    header("Location: ../admin/dashboard.php");
                    exit();
                } else {
                    header("Location: ../Home%20page/borrow_management.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Wrong password.";
            }
        } else {
            $_SESSION['error'] = "Account not found.";
        }
    } else {
        $_SESSION['error'] = "All fields required.";
    }
    
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>