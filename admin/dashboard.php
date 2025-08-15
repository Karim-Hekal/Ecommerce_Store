<?php
$title = "لوحة التحكم";
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('/ecommerce-store/index.php');
}

// استعلامات الإحصائيات
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$total_reviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();

include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-blue-400">لوحة التحكم</h1>

    <!-- بطاقات الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-users text-3xl text-blue-400 mr-4"></i>
                <div>
                    <p class="text-gray-300">المستخدمين</p>
                    <p class="text-3xl font-bold text-white"><?= $total_users ?></p>
                </div>
            </div>
        </div>

        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-box text-3xl text-purple-400 mr-4"></i>
                <div>
                    <p class="text-gray-300">المنتجات</p>
                    <p class="text-3xl font-bold text-white"><?= $total_products ?></p>
                </div>
            </div>
        </div>

        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-envelope text-3xl text-green-400 mr-4"></i>
                <div>
                    <p class="text-gray-300">الرسائل</p>
                    <p class="text-3xl font-bold text-white"><?= $total_messages ?></p>
                </div>
            </div>
        </div>

        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <div class="flex items-center">
                <i class="fas fa-star text-3xl text-yellow-400 mr-4"></i>
                <div>
                    <p class="text-gray-300">التقييمات</p>
                    <p class="text-3xl font-bold text-white"><?= $total_reviews ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- عرض سريع للمحتوى -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- المستخدمون -->
        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <h3 class="text-xl font-bold text-blue-400 mb-4">آخر المستخدمين</h3>
            <?php
            $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
            foreach ($users as $user): ?>
                <div class="flex items-center space-x-3 space-x-reverse mb-3">
                    <img src="/ecommerce-store/uploads/profiles/<?= $user['profile_image'] ?? 'default.jpg' ?>"
                        class="w-8 h-8 rounded-full object-cover">
                    <div>
                        <p class="text-white font-semibold"><?= htmlspecialchars($user['name']) ?></p>
                        <p class="text-gray-400 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="/ecommerce-store/admin/users.php" class="text-blue-400 text-sm hover:underline">عرض الكل →</a>
        </div>

        <!-- المنتجات -->
        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <h3 class="text-xl font-bold text-purple-400 mb-4">آخر المنتجات</h3>
            <?php
            $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5")->fetchAll();
            foreach ($products as $product): ?>
                <div class="flex items-center space-x-3 space-x-reverse mb-3">
                    <img src="/ecommerce-store/uploads/products/<?= $product['image'] ?? 'default.jpg' ?>"
                        class="w-12 h-12 rounded object-cover">
                    <div>
                        <p class="text-white font-semibold"><?= htmlspecialchars($product['name']) ?></p>
                        <p class="text-purple-400 text-sm"><?= number_format($product['price']) ?> جنيه</p>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="/ecommerce-store/admin/products.php" class="text-purple-400 text-sm hover:underline">عرض الكل →</a>
        </div>

        <!-- الرسائل -->
        <div class="glass bg-gray-800/50 p-6 rounded-xl">
            <h3 class="text-xl font-bold text-green-400 mb-4">آخر الرسائل</h3>
            <?php
            $messages = $pdo->query("SELECT m.*, u.name FROM messages m JOIN users u ON m.user_id = u.id ORDER BY m.created_at DESC LIMIT 5")->fetchAll();
            foreach ($messages as $msg): ?>
                <div class="mb-3">
                    <p class="text-white font-semibold"><?= htmlspecialchars($msg['subject']) ?></p>
                    <p class="text-gray-400 text-sm">من: <?= htmlspecialchars($msg['name']) ?></p>
                </div>
            <?php endforeach; ?>
            <a href="/ecommerce-store/admin/messages.php" class="text-green-400 text-sm hover:underline">عرض الكل →</a>
        </div>

    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>