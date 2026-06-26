<?php
session_start();
include '../db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $university = mysqli_real_escape_string($conn, $_POST['university']);

    $user_check = $conn->query("SELECT * FROM users WHERE username='$username'");
    
    if ($user_check->num_rows == 0) {
        $message = "خطأ: اسم المستخدم أو الرقم الجامعي غير مسجل في النظام.";
    } else {
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' AND university_id='$university'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
} else {
    header("Location: ../Home page/index.php");
    exit();
}
        } else {
            $user_data = $user_check->fetch_assoc();
            $db_password = $user_data['password'];
            $db_uni = $user_data['university_id'];
            
            $message = "الاسم موجود، ولكن كلمة المرور في الداتا بيز هي ($db_password) ورقم الجامعة هو ($db_uni). بينما أنت أدخلت جامعة رقم ($university).";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - البوابة الموحدة</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1e4fa3;
            --secondary-color: #1e4fa3;
            --bg-overlay: rgba(15, 32, 67, 0.35);
        }

        body {
            font-family: "Tajawal", sans-serif;
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                        url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header-logos img {
            height: 50px;
            width: auto;
            margin-left: 10px;
        }

        .header h1 {
            font-size: 22px;
            color: var(--primary-color);
            margin: 0;
            font-weight: 700;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.98);
            width: 100%;
            max-width: 430px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            box-sizing: border-box;
        }

        .login-container h2 {
            color: #111111;
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: right;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #333;
        }

        .input-wrapper {
            position: relative;
        }
.input-wrapper i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 40px 12px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-family: "Tajawal";
            font-size: 14px;
            box-sizing: border-box;
            background-color: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 79, 163, 0.15);
            outline: none;
        }

        .flex-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            color: #555;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-family: "Tajawal";
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #888;
            font-size: 12px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .register-box {
            text-align: center;
            font-size: 14px;
        }

        .register-box a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 700;
        }

        .error-msg {
            color: #c62828;
            font-size: 13px;
            margin-top: 15px;
            text-align: center;
            font-weight: 500;
        }

        .footer {
            background-color: #ffffff;
            text-align: center;
            padding: 15px 20px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title-group">
            <h1>البوابة الموحدة لاستعارة الكتب الجامعية - غزة</h1>
        </div>
        <div class="header-logos">
            <img src="../img/aqsa-logo.jpg" alt="جامعة الأقصى">
            <img src="../img/azhar-logo.jpg" alt="جامعة الأزهر">
            <img src="../img/iug-logo.jpg" alt="الجامعة الإسلامية">
        </div>
    </div>

    <div class="main-content">
        <div class="login-container">
            <h2>تسجيل الدخول للنظام الموحد</h2>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>اسم المستخدم / الرقم الجامعي:</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="username" class="form-control" placeholder="اسم المستخدم أو الرقم الجامعي" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>كلمة المرور:</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
                    </div>
                </div>
<div class="form-group">
                    <label>اختر الجامعة:</label>
                    <select name="university" class="form-control" required>
                        <option value="1">جامعة الأقصى</option>
                        <option value="2">جامعة الأزهر - غزة</option>
                        <option value="3">الجامعة الإسلامية بغزة</option>
                    </select>
                </div>

                <div class="flex-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>تذكرني</span>
                    </label>
                    <a href="forgot_password.php" style="color: var(--primary-color); text-decoration: none;">نسيت كلمة المرور؟</a>
                </div>

                <button type="submit" class="btn-login">تسجيل الدخول</button>
            </form>

            <?php if (!empty($message)): ?>
                <div class="error-msg"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="divider">OR</div>

            <div class="register-box">
                <span>ليس لديك حساب؟</span>
                <a href="register.php">إنشاء حساب جديد</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>© 2026 البوابة الموحدة للمكتبات الجامعية - غزة. جميع الحقوق محفوظة.</p>
    </div>

</body>
</html>
