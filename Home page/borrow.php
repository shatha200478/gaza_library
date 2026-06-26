<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT full_name FROM users WHERE id='$user_id'")->fetch_assoc();

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
$book = null;

if ($book_id > 0) {
    $result = $conn->query("SELECT * FROM books WHERE id = $book_id");
    if ($result && $result->num_rows > 0) {
        $book = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم طلب استعارة</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e4fa3;
            --primary-hover: #153b7a;
            --bg-overlay: rgba(15, 32, 67, 0.65);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-main: #333;
        }

        body {
            font-family: "Tajawal", sans-serif;
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 1000px;
            width: 100%;
            margin: 20px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .book-details-sidebar {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .form-title {
            color: var(--primary-color);
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: "Tajawal", sans-serif;
            font-size: 15px;
        }

        .form-control:disabled {
            background-color: #e9ecef;
            color: #495057;
        }

        .book-img {
            max-width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .book-title-text {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
.submit-btn {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            background: var(--primary-hover);
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="form-title"><i class="fa-solid fa-file-signature"></i> تقديم طلب استعارة</h2>
        <form action="process_borrow.php" method="POST">
            <input type="hidden" name="book_id" value="<?php echo $book ? htmlspecialchars($book['id']) : ''; ?>">
            
            <div class="form-group">
                <label>اسم الطالب:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" disabled>
            </div>

            <div class="form-group">
                <label>تاريخ الاستلام المتوقع:</label>
                <input type="date" name="expected_receive_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>مدة الاستعارة (أيام):</label>
                <select name="borrow_duration" class="form-control" required>
                    <option value="7">7 أيام (أسبوع)</option>
                    <option value="14">14 يوم (أسبوعين)</option>
                </select>
            </div>

            <button type="submit" class="submit-btn"><i class="fa-solid fa-paper-plane"></i> إرسال الطلب للمكتبة</button>
        </form>
        <center><a href="index.php" class="back-link"><i class="fa-solid fa-arrow-right"></i> العودة لقائمة الكتب</a></center>
    </div>

    <div class="book-details-sidebar">
        <?php if ($book): ?>
            <img src="../img/<?php echo htmlspecialchars(!empty($book['book_image']) ? $book['book_image'] : 'default_book.jpg'); ?>" class="book-img" alt="غلاف الكتاب">
            <div class="book-title-text"><?php echo htmlspecialchars($book['title']); ?></div>
            <p style="color: #666; margin: 5px 0;">المؤلف: <?php echo htmlspecialchars($book['author']); ?></p>
            <p style="color: #888; font-size: 13px;">الرمز المرجعي: <?php echo htmlspecialchars($book['reference_code']); ?></p>
        <?php else: ?>
            <i class="fa-solid fa-book-open" style="font-size: 60px; color: #ccc; margin-bottom: 20px;"></i>
            <div class="book-title-text" style="color: #999;">كتاب غير محدد</div>
            <p style="color: #aaa; font-size: 14px;">تأكد من بيانات الكتاب قبل إتمام الطلب</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>