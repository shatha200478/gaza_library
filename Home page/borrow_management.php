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

$user_id = intval($_SESSION['user_id']);

$user_query = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $user_query->fetch_assoc();

$borrows_result = $conn->query("SELECT borrows.*, books.title as book_title, books.author as book_author 
    FROM borrows 
    JOIN books ON borrows.book_id = books.id 
    WHERE borrows.user_id = '$user_id' 
    ORDER BY borrows.id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة استعارات الطالب</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e4fa3;
            --bg-overlay: rgba(15, 32, 67, 0.4);
            --card-bg: #ffffff;
            --text-color: #333;
        }
        body {
            font-family: "Tajawal", sans-serif;
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                        url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0; padding: 0; color: var(--text-color);
        }
        .header {
            background-color: #ffffff; padding: 15px 50px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 22px; color: var(--primary-color); margin: 0; }
        
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .content-card {
            background: rgba(255, 255, 255, 0.98); border-radius: 12px;
            padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .custom-table { width: 100%; border-collapse: collapse; font-size: 14px; text-align: center; margin-top: 15px; }
        .custom-table th, .custom-table td { padding: 12px; border: 1px solid #dee2e6; }
        .custom-table th { background-color: #f1f3f5; font-weight: bold; }
        
        .btn-action {
            display: inline-block; padding: 6px 14px; border-radius: 4px;
            text-decoration: none; font-size: 13px; font-family: "Tajawal"; font-weight: bold;
        }
        .btn-renew { background-color: #2e7d32; color: white; border: none; }
        .btn-renew:hover { background-color: #1b5e20; }
        .btn-nav { background: var(--primary-color); color: white; margin-left: 10px; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-size: 14px; }
        
        .status-badge { font-weight: bold; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-active { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <div class="header">
        <h1>مرحباً بك، <?php echo htmlspecialchars($user['full_name']); ?></h1>
        <div>
            <a href="books.php" class="btn-nav">استعراض الكتب المتاحة</a>
            <a href="../logout.php" class="btn-nav" style="background:#c62828;">تسجيل الخروج</a>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <h2 style="margin-top:0;">سجل طلبات الاستعارة الخاصة بك</h2>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>عنوان الكتاب</th>
<th>المؤلف</th>
                        <th>تاريخ الطلب</th>
                        <th>تاريخ الإرجاع المتوقع</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($borrows_result && $borrows_result->num_rows > 0): ?>
                        <?php while($row = $borrows_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['book_title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['book_author'] ?: 'غير محدد'); ?></td>
                            <td><?php echo $row['borrow_date']; ?></td>
                            <td><?php echo $row['return_expected_date'] ?: 'لم يحدد بعد'; ?></td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <span class="status-badge status-pending">قيد الانتظار</span>
                                <?php else: ?>
                                    <span class="status-badge status-active">نشطة / مستلمة</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'active'): ?>
                                    <a href="Action_borrow.php?action=renew&id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('هل تريد تمديد فترة استعارة هذا الكتاب لمدة أسبوع إضافي؟');" 
                                       class="btn-action btn-renew">تجديد الاستعارة</a>
                                <?php else: ?>
                                    <span style="color:#777;">في انتظار الموافقة</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7">لم تقم بطلب استعارة أي كتب حتى الآن.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>