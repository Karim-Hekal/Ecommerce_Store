<?php
$rootPath = realpath(__DIR__ . '/..');
require_once $rootPath . '/config/database.php';
require_once $rootPath . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['success'])) {
    echo '<div class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg mb-4">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'متجرنا الإلكتروني' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #111827, #1e1b4b);
            color: white;
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="font-sans">
    <!-- Navbar -->
    <nav class="bg-black/80 backdrop-blur-lg border-b border-blue-900/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/ecommerce-store/index.php"
                    class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                    متجرنا
                </a>

                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="/ecommerce-store/index.php"
                        class="text-gray-300 hover:text-blue-400 transition">الرئيسية</a>
                    <a href="/ecommerce-store/products.php"
                        class="text-gray-300 hover:text-blue-400 transition">المنتجات</a>
                    <a href="/ecommerce-store/contact.php" class="text-gray-300 hover:text-blue-400 transition">تواصل
                        معنا</a>
                </div>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 space-x-reverse">
                                <img src="/ecommerce-store/uploads/profiles/<?= htmlspecialchars($_SESSION['user_image'] ?? 'default.jpg') ?>"
                                    class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
                                <span
                                    class="text-gray-300"><?= htmlspecialchars($_SESSION['user_name'] ?? 'مستخدم') ?></span>
                            </button>

                            <!-- خلي القائمة تغطي المساحة بين الزر والقائمة -->
                            <div
                                class="absolute left-0 mt-2 w-56 glass rounded-lg shadow opacity-0 group-hover:opacity-100 group-hover:visible invisible transition-all duration-200">
                                <a href="/ecommerce-store/profile.php"
                                    class="block px-4 py-3 text-gray-200 hover:bg-blue-900/50 rounded-t-lg">الملف الشخصي</a>
                                <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                                    <a href="/ecommerce-store/admin/dashboard.php"
                                        class="block px-4 py-3 text-gray-200 hover:bg-blue-900/50">لوحة التحكم</a>
                                <?php endif; ?>
                                <a href="/ecommerce-store/logout.php"
                                    class="block px-4 py-3 text-red-400 hover:bg-red-900/50 rounded-b-lg">تسجيل الخروج</a>
                            </div>
                        </div>

                    </div>
                <?php else: ?>
                    <a href="/ecommerce-store/login.php"
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105">
                        تسجيل الدخول
                    </a>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </nav>