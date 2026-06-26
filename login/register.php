<?php<?php
@session_start();
include '../db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $university = $_POST['university'] ?? '';

    if (!empty($fullname) && !empty($username) && !empty($password)) {
        $sql = "INSERT INTO users (full_name, username, password, role, university_id) VALUES ('$fullname', '$username', '$password', 'student', '$university')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.'); window.location.href='login.php';</script>";
            exit();
        } else {
            $message = "حدث خطأ أثناء التسجيل: " . $conn->error;
        }
    } else {
        $message = "يرجى ملء جميع الحقول المطلوبة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب جديد - البوابة الموحدة</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Tajawal", sans-serif;
            background:
                linear-gradient(rgba(30,79,163,0.45), rgba(30,79,163,0.45)),
                url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            direction: rtl;
            overflow: hidden;
        }

        .header {
            flex-shrink: 0;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 10px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .header img {
            height: 45px;
            margin: 0 8px;
            vertical-align: middle;
        }

        .header h1 {
            color: #1e4fa3;
            font-size: 19px;
            font-weight: bold;
            margin: 6px 0 0 0;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.96);
            width: 100%;
            max-width: 350px; 
            padding: 25px 25px; 
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            text-align: center;
            position: relative;
        }

        h2 {
            color: #1e4fa3;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
            border-bottom: 2px solid #1e4fa3;
            padding-bottom: 8px;
        }

        label {
            display: block;
            text-align: right;
            margin-bottom: 6px;
            color: #333;
            font-weight: bold;
            font-size: 13px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #1e4fa3;
            border-radius: 8px;
            margin-bottom: 14px; 
            outline: none;
            font-size: 14px;
            background-color: #fff;
        }

        input:focus, select:focus {
            border-color: #003d99;
            box-shadow: 0 0 6px rgba(30,79,163,0.3);
        }

        button {
            width: 100%;
            padding: 11px;
            background: #1e4fa3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 5px;
            transition: background 0.2s;
        }

        button:hover {
            background: #003d99;
        }

        .links {
            margin-top: 15px;
            font-size: 13px;
        }
.links a {
            color: #1e4fa3;
            text-decoration: none;
            font-weight: bold;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error-msg {
            color: red;
            margin-top: 10px;
            font-weight: bold;
            font-size: 13px;
        }

        footer {
            flex-shrink: 0;
            text-align: center;
            color: #333;
            font-size: 13px;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.95);
            border-top: 1px solid #ccc;
            padding: 10px 0;
            z-index: 1000;
        }

        footer p {
            margin-bottom: 4px;
        }

        footer a {
            color: #1e4fa3;
            margin: 0 6px;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="../img/iug-logo.jpg" alt="الجامعة الإسلامية">
        <img src="../img/aqsa-logo.jpg" alt="جامعة الأقصى">
        <img src="../img/azhar-logo.jpg" alt="جامعة الأزهر">
        <h1>البوابة الموحدة لاستعارة الكتب الجامعية - غزة</h1>
    </div>

    <div class="main-content">
        <div class="login-container">
            <h2>إنشاء حساب جديد</h2>

            <form method="POST">
                <label>الاسم الكامل:</label>
                <input type="text" name="fullname" required>

                <label>اسم المستخدم / الرقم الجامعي:</label>
                <input type="text" name="username" required>

                <label>كلمة المرور:</label>
                <input type="password" name="password" required>

                <label>اختر الجامعة:</label>
                <select name="university" required>
                    <option value="1">الجامعة الإسلامية بغزة</option>
                    <option value="2">جامعة الأقصى</option>
                    <option value="3">جامعة الأزهر - غزة</option>
                </select>

                <button type="submit">إنشاء الحساب</button>
            </form>

            <?php if (!empty($message)): ?>
                <p class="error-msg"><?php echo $message; ?></p>
            <?php endif; ?>

            <div class="links">
                <a href="login.php">العودة لتسجيل الدخول</a>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2026 جميع الحقوق محفوظة - البوابة الجامعية الموحدة</p>
        <a href="#">عن النظام</a> |
        <a href="#">اتصل بنا</a> |
        <a href="#">سياسة الخصوصية</a>
    </footer>

</body>
</html>
