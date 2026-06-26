-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 22 يونيو 2026 الساعة 05:32
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gaza_library_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `university_id` int(11) DEFAULT NULL,
  `reference_code` varchar(50) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `book_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category_id`, `university_id`, `reference_code`, `available`, `description`, `book_image`) VALUES
(1, 'فن اللامبالاة', 'مارك مانسون', 1, 3, 'REF-001', 1, 'كتاب يساعد على فهم الحياة بواقعية والتعامل مع التحديات.', 'indifference.jpg'),
(2, 'العادات السبع للناس الأكثر فعالية', 'ستيفن كوفي', 1, 3, 'REF-002', 1, 'كتاب تطوير ذات شهير يشرح سبع عادات للنجاح.', 'habits.jpg'),
(3, 'التفكير السريع والبطيء', 'دانيال كانيمان', 2, 2, 'REF-003', 1, 'كتاب يشرح نظامي التفكير في العقل البشري.', 'thinking.jpg'),
(4, 'إدارة المشاريع الاحترافية PMP', 'ريتا مولكاهي', 3, 1, 'REF-004', 1, 'مرجع شامل للتحضير لشهادة PMP.', 'pmp.jpg'),
(5, 'مبادئ الإدارة', 'روبينز وكولتر', 3, 2, 'REF-005', 1, 'كتاب أكاديمي يشرح أساسيات الإدارة الحديثة.', 'management.jpg'),
(6, 'هندسة البرمجيات', 'إيان سومرفيل', 4, 1, 'REF-006', 1, 'مرجع عالمي في هندسة البرمجيات.', 'software.jpg'),
(7, 'شبكات الحاسوب', 'كورو وروس', 4, 3, 'REF-007', 1, 'كتاب مشهور في مجال شبكات الحاسوب.', 'networks.jpg'),
(8, 'مقدمة في البرمجة بلغة بايثون', 'جون زيل', 4, 3, 'REF-008', 1, 'كتاب تعليمي لتعلم أساسيات لغة بايثون.', 'python.jpg'),
(9, 'الأمير', 'نيكولو مكيافيلي', 5, 1, 'REF-009', 1, 'كتاب كلاسيكي يناقش مبادئ الحكم والسياسة.', 'prince.jpg'),
(10, 'الأب الغني والأب الفقير', 'روبرت كيوساكي', 6, 3, 'REF-010', 1, 'كتاب يشرح التفكير المالي للأغنياء والفقراء.', 'dad.jpg'),
(11, 'مقدمة في تاريخ فلسطين الحديث', 'د. سلمان أبو ستة', 2, 2, 'REF-011', 1, 'كتاب يستعرض تاريخ فلسطين الحديث والمعاصر والتحولات السياسية.', 'palestine.jpg'),
(12, 'الفقه المقارن', 'د. وهبة الزحيلي', 3, 2, 'REF-012', 1, 'دراسة شاملة للأحكام الفقهية والمسائل الخلافية بين المذاهب الإسلامية.', 'fiqh.jpg'),
(13, 'أساسيات البرمجة', 'د. أحمد طه', 1, 1, 'REF-013', 1, 'دليل شامل للمبتدئين لتعلم المفاهيم الأساسية للبرمجة والتفكير المنطقي.', 'programming.jpg');

-- --------------------------------------------------------

--
-- بنية الجدول `borrows`
--

CREATE TABLE `borrows` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `return_expected_date` date DEFAULT NULL,
  `status` enum('pending','active') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `borrow_requests`
--

CREATE TABLE `borrow_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `expected_return` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'تطوير الذات'),
(2, 'علم النفس'),
(3, 'إدارة'),
(4, 'تقنية'),
(5, 'فلسفة'),
(6, 'اقتصاد');

-- --------------------------------------------------------

--
-- بنية الجدول `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `expected_return` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `universities`
--

CREATE TABLE `universities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `universities`
--

INSERT INTO `universities` (`id`, `name`) VALUES
(1, 'جامعة الأقصى'),
(2, 'جامعة الأزهر'),
(3, 'الجامعة الإسلامية');

-- --------------------------------------------------------

--
-- بنية الجدول `university_books`
--

CREATE TABLE `university_books` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `university_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `university_books`
--

INSERT INTO `university_books` (`id`, `book_id`, `university_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','student') DEFAULT NULL,
  `university_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `university_id`) VALUES
(1, 'Admin', 'admin', '123', 'admin', 1),
(2, 'بوابة الازهر الرقمية', 'azhar_student', 'azhar1234', 'student', 2),
(3, 'بوابة الأقصى الرقمية', 'aqsa_student', 'aqsa1234', 'student', 1),
(4, 'بوابة الإسلامية الرقمية', 'iug_student', 'iug1234', 'student', 3),
(7, 'أدمن جامعة الأزهر', 'admin_azhar', '123', 'admin', 2),
(8, 'أدمن الجامعة الإسلامية', 'admin_iug', '123', 'admin', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_books_category` (`category_id`),
  ADD KEY `fk_books_university` (`university_id`);

--
-- Indexes for table `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_loans_user` (`user_id`),
  ADD KEY `fk_loans_book` (`book_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `university_books`
--
ALTER TABLE `university_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_university` (`university_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `university_books`
--
ALTER TABLE `university_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`),
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_books_university` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);

--
-- قيود الجداول `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `borrows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD CONSTRAINT `borrow_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrow_requests_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- قيود الجداول `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `fk_loans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- قيود الجداول `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_university` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
