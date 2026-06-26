<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = intval($_SESSION['user_id']);

if (empty($action) || $id <= 0) {
    header("Location: borrow_management.php");
    exit();
}

switch ($action) {
    case 'renew':
        $stmt = $conn->prepare("UPDATE borrows SET return_expected_date = DATE_ADD(return_expected_date, INTERVAL 7 DAY) WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('تم تمديد فترة الاستعارة لمدة أسبوع إضافي بنجاح'); window.location.href='borrow_management.php';</script>";
        } else {
            echo "خطأ في التجديد: " . $conn->error;
        }
        break;
        
    default:
        header("Location: borrow_management.php");
        exit();
}
?>