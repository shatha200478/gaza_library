<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$books = $conn->query("
    SELECT books.title, books.author, books.reference_code, books.book_image, universities.name AS uni_name
    FROM books
    INNER JOIN universities ON books.university_id = universities.id
    ORDER BY universities.name
");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الكتب - البوابة الموحدة</title>
    
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
            background: 
                linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: var(--text-main);
            min-height: 100vh;
        }

        .header {
            background-color: rgba(255, 255, 255, 0.98);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .header h1 {
            margin: 0;
            color: var(--primary-color);
            font-size: 22px;
            font-weight: 700;
        }

        .back-btn {
            background: var(--primary-color);
            padding: 10px 20px;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: var(--primary-hover);
        }

        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .content-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .section-title {
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

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .book-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border-right: 6px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(30,79,163,0.15);
        }

        .book-cover-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 12px;
            background: #f1f3f5;
        }

        .book-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
        }
.book-info {
            font-size: 14px;
            color: #555;
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .book-info i {
            color: #777;
            width: 18px;
        }

        .uni-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e7f1ff;
            color: var(--primary-color);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>البوابة الموحدة - جميع الكتب المسجلة</h1>
        <a class="back-btn" href="index.php">
            <i class="fa-solid fa-arrow-right"></i> العودة للشاشة الرئيسية
        </a>
    </div>

    <div class="main-container">
        
        <div class="content-card">
            <h2 class="section-title">
                <i class="fa-solid fa-layer-group"></i> دليل الكتب المتاحة في كافة الجامعات
            </h2>

            <div class="books-grid">
                <?php
                if ($books && $books->num_rows > 0) {
                    while ($row = $books->fetch_assoc()) {
                        $image_name = !empty($row['book_image']) ? $row['book_image'] : 'default_book.jpg';
                        echo "
                        <div class='book-box'>
                            <div>
                                <img src='../img/" . htmlspecialchars($image_name) . "' class='book-cover-img' alt='غلاف الكتاب'>
                                <div class='book-title'>" . htmlspecialchars($row['title']) . "</div>
                                <div class='book-info'>
                                    <i class='fa-solid fa-pen-nib'></i>
                                    <span>المؤلف: " . htmlspecialchars($row['author']) . "</span>
                                </div>
                                <div class='book-info'>
                                    <i class='fa-solid fa-barcode'></i>
                                    <span>الرمز المرجعي: " . htmlspecialchars($row['reference_code']) . "</span>
                                </div>
                            </div>
                            <div>
                                <div class='uni-badge'>
                                    <i class='fa-solid fa-university'></i>
                                    <span>تتبع لجامعة: " . htmlspecialchars($row['uni_name']) . "</span>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    echo "
                    <div class='no-books'>
                        <i class='fa-solid fa-face-frown' style='font-size: 40px; margin-bottom: 10px; display:block;'></i>
                        لا توجد كتب مسجلة في النظام حالياً.
                    </div>";
                }
                ?>
            </div>
        </div>

    </div>

</body>
</html>