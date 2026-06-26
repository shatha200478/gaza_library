<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    $expected_receive_date = mysqli_real_escape_string($conn, $_POST['expected_receive_date']);
    $borrow_duration = isset($_POST['borrow_duration']) ? intval($_POST['borrow_duration']) : 7;

    if ($book_id > 0 && !empty($expected_receive_date)) {
        $return_date = date('Y-m-d', strtotime($expected_receive_date . " + $borrow_duration days"));

        $query = "INSERT INTO borrows (user_id, book_id, borrow_date, return_date, status) 
                  VALUES ('$user_id', '$book_id', '$expected_receive_date', '$return_date', 'pending')";

        if ($conn->query($query)) {
            echo "<script>
                    alert('تم إرسال طلب الاستعارة بنجاح بانتظار موافقة المكتبة!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "خطأ في إرسال الطلب: " . $conn->error;
        }
    } else {
        echo "<script>
                alert('الرجاء التأكد من اختيار الكتاب وتعبئة البيانات المطلوبة.');
                window.location.href = 'index.php';
              </script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>