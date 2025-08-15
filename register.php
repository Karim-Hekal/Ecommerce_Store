<?php
$title = "إنشاء حساب";
include 'includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900"></div>

    <div class="relative z-10 w-full max-w-md">
        <div class="glass bg-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50 p-8">
            <div class="text-center mb-8">
                <h2
                    class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                    إنشاء حساب جديد
                </h2>
                <p class="text-gray-400 mt-2">انضم إلينا اليوم</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg mb-6">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="includes/auth.php" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-user mr-2"></i>الاسم الكامل
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-2"></i>البريد الإلكتروني
                    </label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-lock mr-2"></i>كلمة المرور
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>

                <button type="submit" name="register"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>إنشاء الحساب
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400">
                    لديك حساب بالفعل؟
                    <a href="/ecommerce-store/login.php" class="text-blue-400 hover:text-blue-300 transition">
                        تسجيل الدخول
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>