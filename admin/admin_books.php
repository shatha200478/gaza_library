<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {

    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $author = $conn->real_escape_string($_POST['author'] ?? '');

    if (!empty($title)) {

        if (isset($_POST['book_id']) && $_POST['book_id'] !== '') {

            $id = intval($_POST['book_id']);

            $conn->query("
                UPDATE books 
                SET title='$title', author='$author' 
                WHERE id=$id
            ");

        } else {

            $conn->query("
                INSERT INTO books (title, author) 
                VALUES ('$title', '$author')
            ");
        }

        header("Location: admin_books.php");
        exit();
    }
}

if (isset($_GET['delete_book_id'])) {
    $delete_id = intval($_GET['delete_book_id']);
    $conn->query("DELETE FROM books WHERE id='$delete_id'");
    header("Location: admin_books.php");
    exit();
}

$edit_book = null;

if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = $conn->query("SELECT * FROM books WHERE id=$edit_id");

    if ($res && $res->num_rows > 0) {
        $edit_book = $res->fetch_assoc();
    }
}

$books_result = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الكتب - لوحة الأدمن</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap">

    <style>
        :root { --primary-color: #1e4fa3; --bg-overlay: rgba(15, 32, 67, 0.4); }

        body {
            font-family: "Tajawal", sans-serif;
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), url('../img/library.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #ffffff;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 22px;
            color: var(--primary-color);
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 25px;
        }

        @media(max-width: 900px) {
            .container { grid-template-columns: 1fr; }
        }

        .content-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 14px;
        }

        .custom-table th, .custom-table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .custom-table th { background-color: #f1f3f5; }

        .form-group {
            margin-bottom: 15px;
            text-align: right;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Tajawal";
        }

        .btn {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .btn-add {
            background: #1e4fa3;
            color: white;
        }
.btn-add:hover {
            background: #163a7a;
        }

        .btn-delete {
            border: 1px solid #c62828;
            color: #c62828;
            background: none;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-delete:hover {
            background: #ffebee;
        }

        .btn-edit {
            border: 1px solid #1e4fa3;
            color: #1e4fa3;
            background: none;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            margin-left: 5px;
        }

        .btn-edit:hover {
            background: #e8f0ff;
        }

        .btn-nav {
            background: var(--primary-color);
            color: white;
            width: auto;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>إدارة الكتب الجامعية</h1>
    <a href="dashboard.php" class="btn-nav">العودة للرئيسية</a>
</div>

<div class="container">

    <!-- جدول الكتب -->
    <div class="content-card">
        <h2>الكتب المتوفرة</h2>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>العنوان</th>
                    <th>المؤلف</th>
                    <th>إجراءات</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($books_result && $books_result->num_rows > 0): ?>
                    <?php while ($row = $books_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['author'] ?: '-') ?></td>
                            <td>
                                <a href="admin_books.php?edit_id=<?= $row['id'] ?>" class="btn-edit">تعديل</a>

                                <a href="admin_books.php?delete_book_id=<?= $row['id'] ?>"
                                   onclick="return confirm('هل أنت متأكد؟');"
                                   class="btn-delete">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">لا توجد كتب</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

   
    <div class="content-card">
        <h3>
            <?= $edit_book ? "تعديل كتاب" : "إضافة كتاب" ?>
        </h3>

        <form method="POST">

            <input type="hidden" name="book_id" value="<?= $edit_book['id'] ?? '' ?>">

            <div class="form-group">
                <label>العنوان</label>
                <input type="text" name="title"
                       value="<?= $edit_book['title'] ?? '' ?>"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label>المؤلف</label>
                <input type="text" name="author"
                       value="<?= $edit_book['author'] ?? '' ?>"
                       class="form-control">
            </div>

            <button type="submit" name="add_book" class="btn btn-add">
                <?= $edit_book ? "تعديل" : "إضافة" ?>
            </button>

        </form>
    </div>

</div>

</body>
</html>