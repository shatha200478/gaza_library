<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT full_name, university_id FROM users WHERE id='$user_id'")->fetch_assoc();

$uni_id = $user['university_id'];
$uni = $conn->query("SELECT name FROM universities WHERE id='$uni_id'")->fetch_assoc();

$logo_src = "../img/default-logo.jpg";

switch ($uni_id) {
    case 1:
        $logo_src = "../img/aqsa-logo.jpg";
        break;
    case 2:
        $logo_src = "../img/azhar-logo.jpg";
        break;
    case 3:
        $logo_src = "../img/iug-logo.jpg";
        break;
}

$books = $conn->query("SELECT * FROM books WHERE university_id = '$uni_id'");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية - البوابة الموحدة</title>
    
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
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .header-title-box {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .uni-logo {
            width: auto;
            height: 55px;
            object-fit: contain;
        }

        .header h1 {
            margin: 0;
            color: var(--primary-color);
            font-size: 22px;
            font-weight: 700;
        }

        .logout-btn {
            background: #dc3545;
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

        .logout-btn:hover {
            background: #bd2130;
        }

        .main-wrapper {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 30px;
        }

        @media (max-width: 900px) {
            .main-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .sidebar-card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            text-align: center;
            height: fit-content;
            border-top: 6px solid var(--primary-color);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: #e7f1ff;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 32px;
        }
.sidebar-card h2 {
            font-size: 20px;
            color: var(--primary-color);
            margin: 10px 0;
        }

        .uni-badge {
            background: #e7f1ff;
            color: var(--primary-color);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
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
            position: relative;
            overflow: hidden;
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

        .borrow-btn {
            display: block;
            text-align: center;
            background: var(--primary-color);
            color: white;
            padding: 8px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .borrow-btn:hover {
            background: var(--primary-hover);
        }

        .no-books {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            color: #777;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-title-box">
            <img src="<?php echo $logo_src; ?>" alt="شعار الجامعة" class="uni-logo">
            <h1>البوابة الموحدة لاستعارة الكتب الجامعية - غزة</h1>
        </div>
        <a class="logout-btn" href="../login/logout.php">
            <i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج
        </a>
    </div>

    <div class="main-wrapper">
        
        <div class="sidebar-card">
            <div class="profile-avatar">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <h2>مرحبًا بك، <?php echo htmlspecialchars($user['full_name']); ?> 👋</h2>
            <div class="uni-badge">
                <i class="fa-solid fa-university"></i>
                <span><?php echo htmlspecialchars($uni['name']); ?></span>
            </div>
        </div>
<div class="content-card">
            <h2 class="section-title">
                <i class="fa-solid fa-book-bookmark"></i> الكتب المتاحة في جامعتك
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
                            <a href='borrow.php?book_id=" . htmlspecialchars($row['id']) . "' class='borrow-btn'><i class='fa-solid fa-hand-holding'></i> طلب استعارة</a>
                        </div>
                        ";
                    }
                } else {
                    echo "
                    <div class='no-books'>
                        <i class='fa-solid fa-face-frown' style='font-size: 40px; margin-bottom: 10px; display:block;'></i>
                        لا توجد كتب متاحة حالياً في جامعتك.
                    </div>";
                }
                ?>
            </div>
        </div>

    </div>

</body>
</html>