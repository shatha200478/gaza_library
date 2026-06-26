<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$book_query = $conn->query("SELECT * FROM books WHERE id='$book_id'");
if (!$book_query || $book_query->num_rows == 0) {
    echo "الكتاب غير موجود.";
    exit();
}
$book = $book_query->fetch_assoc();

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT full_name, university_id FROM users WHERE id='$user_id'")->fetch_assoc();

$uni_id = $user['university_id'];
$img_path = "../img/";
$logo_src = $img_path . "library.jpg"; 
switch ($uni_id) {
    case 1: $logo_src = $img_path . "aqsa-logo.jpg"; break;
    case 2: $logo_src = $img_path . "azhar-logo.jpg"; break;
    case 3: $logo_src = $img_path . "iug-logo.jpg"; break;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الكتاب</title>
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
        .header-title-area { display: flex; align-items: center; gap: 20px; }
        .main-logo-group img { height: 50px; width: auto; }
        .header h1 { font-size: 22px; color: var(--primary-color); margin: 0; }
        
        .container {
            max-width: 800px; margin: 50px auto; padding: 0 20px;
        }
        .details-card {
            background: rgba(255, 255, 255, 0.98); border-radius: 12px;
            padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .book-icon {
            font-size: 70px; color: var(--primary-color); margin-bottom: 20px;
        }
        .book-title { font-size: 26px; font-weight: 700; margin-bottom: 10px; }
        .book-meta { font-size: 16px; color: #666; margin-bottom: 30px; }
        
        .btn-confirm {
            display: inline-block; background: #2e7d32; color: white;
            padding: 12px 35px; border-radius: 6px; font-size: 16px;
            font-weight: bold; text-decoration: none; transition: 0.2s;
            border: none; cursor: pointer;
        }
        .btn-confirm:hover { background: #1b5e20; }
        .btn-back {
            display: inline-block; background: #fff; color: #555;
            padding: 12px 35px; border-radius: 6px; font-size: 16px;
            text-decoration: none; border: 1px solid #ccc; margin-right: 10px;
        }
        .btn-back:hover { background: #f5f5f5; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title-area">
            <div class="main-logo-group"><img src="<?php echo $logo_src; ?>" alt="Logo"></div>
            <h1>البوابة الموحدة لاستعارة الكتب الجامعية - غزة</h1>
        </div>
        <div style="font-size: 14px; color:#555;">مرحبًا: <strong><?php echo htmlspecialchars($user['full_name']); ?></strong></div>
    </div>
<div class="container">
        <div class="details-card">
            <div class="book-icon"><i class="fa-solid fa-book-open"></i></div>
            <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
            <div class="book-meta">الكاتب: <?php echo htmlspecialchars($book['author'] ?? 'غير محدد'); ?> | الجامعة المالكة للكتاب: <?php echo htmlspecialchars($book['university'] ?? 'المكتبة المركزية'); ?></div>
            
            <p style="line-height: 1.6; color: #555; margin-bottom: 40px;">
                عند الضغط على تأكيد الاستعارة, سيتم حجز نسخة من هذا الكتاب لك فوراً. يرجى التوجه لمقر المكتبة المذكورة لاستلام الكتاب خلال مدة أقصاها 48 ساعة.
            </p>

            <form action="process_borrow.php" method="POST" style="display: inline-block;">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <button type="submit" class="btn-confirm">تأكيد طلب الاستعارة</button>
            </form>
            <a href="books.php" class="btn-back">إلغاء والعودة</a>
        </div>
    </div>

</body>
</html>