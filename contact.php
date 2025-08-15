<?php
$title = "تواصل معنا";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>

<!-- خلفية داكنة -->
<section class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white px-6 py-12">
    <div class="max-w-2xl mx-auto">
        <!-- عنوان داخل إطار زجاجي -->
        <div class="w-fit mx-auto mb-10 backdrop-blur-md bg-white/10 border border-white/20 rounded-xl px-8 py-4">
            <h1 class="text-3xl font-bold">تواصل معنا</h1>
        </div>

        <!-- رسائل النجاح / الخطأ -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- نموذج التواصل -->
        <form method="POST" action="/ecommerce-store/includes/auth.php"
            class="glass bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl p-8 space-y-6">
            <div>
                <label class="block text-gray-300 mb-2">الموضوع</label>
                <input type="text" name="subject" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-300 mb-2">الرسالة</label>
                <textarea name="message" rows="5" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"></textarea>
            </div>
            <button type="submit" name="send_message"
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition">
                <i class="fas fa-paper-plane mr-2"></i>إرسال الرسالة
            </button>
        </form>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>