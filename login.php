<?php
$title = "تسجيل الدخول";
include 'includes/header.php';

if (!empty($_SESSION['user-name'])) {
    redirect('index.php');
}
?>

<!-- خلفية كاملة مع تدرج -->
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- خلفية متحركة -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900"></div>

    <!-- دوائر ديكورية -->
    <div
        class="absolute top-0 -left-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
    </div>
    <div
        class="absolute top-0 -right-4 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
    </div>
    <div
        class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
    </div>

    <!-- نموذج تسجيل الدخول -->
    <div class="relative z-10 w-full max-w-md">
        <div class="glass bg-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50 p-8">
            <div class="text-center mb-8">
                <h2
                    class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                    مرحباً مجدداً
                </h2>
                <p class="text-gray-400 mt-2">سجّل دخولك للمتابعة</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg mb-6">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/ecommerce-store/includes/auth.php" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-2"></i>البريد الإلكتروني
                    </label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-lock mr-2"></i>كلمة المرور
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50">
                </div>

                <button type="submit" name="login"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>تسجيل الدخول
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400">
                    ليس لديك حساب؟
                    <a href="/ecommerce-store/register.php" class="text-blue-400 hover:text-blue-300 transition">
                        إنشاء حساب جديد
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- أنماط إضافية -->
<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }

    .animate-blob {
        animation: blob 7s infinite;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }

    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

<?php include 'includes/footer.php'; ?>