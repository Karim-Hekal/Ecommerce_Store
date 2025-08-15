<?php
$title = "الملف الشخصي";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// تأكد من تسجيل الدخول
if (!isLoggedIn()) {
    header('Location: /ecommerce-store/login.php');
    exit();
}

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();

// رسائل النجاح/الخطأ
if (isset($_SESSION['success'])) {
    echo '<div class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg mb-6">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg mb-6">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
include __DIR__ . '/includes/header.php';
?>

<!-- خلفية داكنة -->
<section class="py-12 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 min-h-screen">
    <div class="max-w-2xl mx-auto px-4">
        <div class="glass bg-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl p-8">
            <h2
                class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                الملف الشخصي
            </h2>

            <!-- نموذج التحديث -->
            <form method="POST" action="/ecommerce-store/includes/auth.php" enctype="multipart/form-data"
                class="space-y-6">
                <!-- صورة البروفايل -->
                <div class="text-center">
                    <label for="profile_image" class="cursor-pointer">
                        <img src="/ecommerce-store/uploads/profiles/<?= $user['profile_image'] ?? 'default.jpg' ?>"
                            id="preview"
                            class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-blue-500">
                        <span class="text-blue-400 text-sm hover:underline">تغيير الصورة</span>
                    </label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>

                <!-- الاسم -->
                <div>
                    <label class="block text-gray-300 mb-2">الاسم الكامل</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>

                <!-- البريد -->
                <div>
                    <label class="block text-gray-300 mb-2">البريد الإلكتروني</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-gray-400">
                </div>


                <!-- زر الحفظ -->
                <button type="submit" name="update_profile"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition">
                    <i class="fas fa-save mr-2"></i>حفظ التغييرات
                </button>
            </form>

            <!-- تغيير كلمة المرور -->
            <div class="mt-8">
                <h3 class="text-xl font-bold mb-4 text-blue-400">تغيير كلمة المرور</h3>
                <form method="POST" action="/ecommerce-store/includes/auth.php" class="space-y-4">
                    <input type="password" name="current_password" placeholder="كلمة المرور الحالية" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <input type="password" name="new_password" placeholder="كلمة المرور الجديدة" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <input type="password" name="confirm_password" placeholder="تأكيد كلمة المرور" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <button type="submit" name="change_password"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition">
                        تغيير كلمة المرور
                    </button>
                </form>
            </div>


        </div>

    </div>
</section>

<!-- JavaScript معاينة الصورة -->
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>