<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

$total_books = $conn->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='user'")->fetch_assoc()['count'];
$total_borrows = $conn->query("SELECT COUNT(*) as count FROM borrows WHERE status='active'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الأدمن</title>
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
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95); padding: 20px; border-radius: 10px;
            text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .stat-card i { font-size: 40px; color: var(--primary-color); margin-bottom: 10px; }
        .stat-card h3 { margin: 5px 0; font-size: 16px; color: #666; }
        .stat-card p { margin: 0; font-size: 28px; font-weight: bold; color: #111; }

        .menu-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;
        }
        .menu-btn {
            background: var(--card-bg); padding: 25px; border-radius: 12px;
            text-align: center; text-decoration: none; color: var(--text-color);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: 0.3s;
            display: flex; flex-direction: column; align-items: center; gap: 10px;
        }
        .menu-btn:hover { transform: translateY(-5px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .menu-btn i { font-size: 32px; color: #2e7d32; }
        .menu-btn span { font-size: 18px; font-weight: 700; }
        
        .btn-logout { background: #c62828; color: white; padding: 8px 18px; border-radius: 4px; text-decoration: none; font-size: 14px; }
        .btn-logout:hover { background: #b71c1c; }
    </style>
</head>
<body>

    <div class="header">
        <h1>لوحة تحكم الإدارة العليا (Admin)</h1>
        <div><a href="../login/logout.php" class="btn-logout"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a></div>
    </div>

    <div class="container">
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa-solid fa-book"></i>
                <h3>إجمالي الكتب بالنظام</h3>
                <p><?php echo $total_books; ?></p>
            </div>
<div class="stat-card">
                <i class="fa-solid fa-users"></i>
                <h3>الطلاب المسجلين</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-handshake"></i>
                <h3>الاستعارات النشطة حالياً</h3>
                <p><?php echo $total_borrows; ?></p>
            </div>
        </div>

        <div class="menu-grid">
            <a href="admin_books.php" class="menu-btn">
                <i class="fa-solid fa-book-medical" style="color: #1e4fa3;"></i>
                <span>إدارة كتب المكتبة الجامعية</span>
            </a>
            <a href="admin_borrows.php" class="menu-btn">
                <i class="fa-solid fa-file-invoice" style="color: #2e7d32;"></i>
                <span>إدارة طلبات الاستعارة المعلقة</span>
            </a>
            <a href="admin_users.php" class="menu-btn">
                <i class="fa-solid fa-user-gear" style="color: #e65100;"></i>
                <span>إدارة الطلاب الحسابات</span>
            </a>
        </div>

    </div>

</body>
</html>