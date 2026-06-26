<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id='$delete_id' AND role != 'admin'");
    header("Location: admin_users.php");
    exit();
}

$users_result = $conn->query("SELECT users.*, 
    CASE 
        WHEN university_id = 1 THEN 'جامعة الأقصى'
        WHEN university_id = 2 THEN 'جامعة الأزهر'
        WHEN university_id = 3 THEN 'الجامعة الإسلامية'
        ELSE 'غير محدد'
    END as university_name 
    FROM users WHERE role='user'");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - لوحة الأدمن</title>
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
        .custom-table { width: 100%; border-collapse: collapse; font-size: 14px; text-align: center; }
        .custom-table th, .custom-table td { padding: 12px; border: 1px solid #dee2e6; }
        .custom-table th { background-color: #f1f3f5; font-weight: bold; }
        
        .btn-action {
            display: inline-block; padding: 5px 12px; border-radius: 4px;
            text-decoration: none; font-size: 13px; font-family: "Tajawal";
        }
        .btn-delete { border: 1px solid #c62828; color: #c62828; }
        .btn-delete:hover { background: #ffebee; }
        .btn-nav { background: var(--primary-color); color: white; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>لوحة تحكم الإدارة - إدارة الطلاب المسجلين</h1>
        <div><a href="dashboard.php" class="btn-action btn-nav" style="margin:0;">العودة للرئيسية</a></div>
    </div>

    <div class="container">
        <div class="content-card">
            <h2 style="margin-top:0;">قائمة الطلاب المسجلين في البوابة الموحدة</h2>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>رقم المستخدم</th>
                        <th>الاسم الكامل</th>
                        <th>البريد الإلكتروني</th>
                        <th>الجامعة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($users_result && $users_result->num_rows > 0): ?>
                        <?php while($row = $users_result->fetch_assoc()): ?>
                        <tr>
<td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['university_name']); ?></td>
                            <td>
                                <a href="admin_users.php?delete_id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('هل أنت متأكد من حذف هذا الطالب نهائياً؟');" 
                                   class="btn-action btn-delete">حذف الحساب</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">لا يوجد طلاب مسجلين حالياً.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>