<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

if (isset($_GET['approve_id'])) {
    $approve_id = intval($_GET['approve_id']);
    $expected_date = date('Y-m-d', strtotime('+14 days'));
    $conn->query("UPDATE borrows SET status='active', return_expected_date='$expected_date' WHERE id='$approve_id'");
    header("Location: admin_borrows.php");
    exit();
}

$borrows_result = $conn->query("SELECT borrows.*, users.full_name as student_name, books.title as book_title 
    FROM borrows 
    JOIN users ON borrows.user_id = users.id 
    JOIN books ON borrows.book_id = books.id
    ORDER BY borrows.id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة طلبات الاستعارة</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap">
    <style>
        :root { --primary-color: #1e4fa3; --bg-overlay: rgba(15, 32, 67, 0.4); }
        body {
            font-family: "Tajawal", sans-serif;
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover; margin: 0; padding: 0;
        }
        .header { background-color: #ffffff; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header h1 { font-size: 22px; color: var(--primary-color); margin: 0; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .content-card { background: rgba(255, 255, 255, 0.98); border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .custom-table { width: 100%; border-collapse: collapse; text-align: center; font-size: 14px; }
        .custom-table th, .custom-table td { padding: 12px; border: 1px solid #dee2e6; }
        .custom-table th { background-color: #f1f3f5; }
        .btn-action { display: inline-block; padding: 5px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; color: white; background: #2e7d32; }
        .btn-action:hover { background: #1b5e20; }
        .btn-nav { background: var(--primary-color); }
        .status-badge { font-weight: bold; padding: 4px 8px; border-radius: 4px; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-active { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <div class="header">
        <h1>لوحة الإدارة - طلبات الاستعارة والمعالجة</h1>
        <div><a href="dashboard.php" class="btn-action btn-nav">العودة للرئيسية</a></div>
    </div>

    <div class="container">
        <div class="content-card">
            <h2 style="margin-top: 0; margin-bottom: 20px;">الطلبات الواردة من الطلاب</h2>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم الطالب</th>
                        <th>عنوان الكتاب</th>
                        <th>تاريخ تقديم الطلب</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($borrows_result && $borrows_result->num_rows > 0): ?>
                        <?php while($row = $borrows_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
<td><?php echo $row['borrow_date']; ?></td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <span class="status-badge status-pending">قيد الانتظار</span>
                                <?php else: ?>
                                    <span class="status-badge status-active">نشطة / مستلمة</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <a href="admin_borrows.php?approve_id=<?php echo $row['id']; ?>" class="btn-action">تسليم وموافقة</a>
                                <?php else: ?>
                                    ---
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">لا توجد طلبات استعارة مسجلة في النظام حتى الآن.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>