<?php
$title = "المنتجات";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';

$product_id = $_GET['id'] ?? null;

// إذا كان هناك id → عرض تفاصيل المنتج
if ($product_id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if (!$product) {
        header('Location: /ecommerce-store/products.php');
        exit();
    }

    // التقييمات
    $reviews = $pdo->prepare("SELECT r.*, u.name, u.profile_image 
                              FROM reviews r 
                              JOIN users u ON r.user_id = u.id 
                              WHERE r.product_id = ? 
                              ORDER BY r.created_at DESC");
    $reviews->execute([$product_id]);
    ?>

    <!-- تفاصيل المنتج -->
    <section class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- الصورة -->
                <div class="w-full lg:aspect-[4/3] bg-gray-800 rounded-xl overflow-hidden">
                    <img src="/ecommerce-store/uploads/products/<?= $product['image'] ?? 'default.jpg' ?>"
                        class="w-full h-full object-contain bg-gray-800 rounded-xl">
                </div>

                <!-- معلومات -->
                <div>
                    <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="text-2xl text-blue-400 mb-4"><?= number_format($product['price']) ?> جنيه</p>
                    <p class="text-gray-300 mb-6 leading-relaxed"><?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>
                    <p class="mb-2"><span class="text-gray-400">المخزون:</span> <span
                            class="text-green-400"><?= $product['stock'] ?></span></p>
                    <button class="mt-4 bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-md">
                        أضف إلى السلة
                    </button>
                </div>
            </div>

            <!-- التقييمات -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">التقييمات</h2>
                <?php if (isLoggedIn()): ?>
                    <form method="POST" action="/ecommerce-store/includes/auth.php" class="mb-8">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?> ">
                        <select name="rating" class="bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                            <option value="5">5 نجوم</option>
                            <option value="4">4 نجوم</option>
                            <option value="3">3 نجوم</option>
                            <option value="2">2 نجمة</option>
                            <option value="1">نجمة واحدة</option>
                        </select>
                        <textarea name="comment" rows="3" placeholder="اكتب تعليقك..."
                            class="w-full mt-3 bg-gray-700 border border-gray-600 rounded p-2 text-white"></textarea>
                        <button type="submit" name="add_review" class="mt-3 bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
                            إرسال
                        </button>
                    </form>
                <?php endif; ?>

                <div class="space-y-4">
                    <?php while ($review = $reviews->fetch()): ?>
                        <div class="bg-gray-800/50 p-4 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="/ecommerce-store/uploads/profiles/<?= $review['profile_image'] ?? 'default.jpg' ?>"
                                        class="w-10 h-10 rounded-full mr-3">
                                    <div>
                                        <p class="font-bold"><?= htmlspecialchars($review['name']) ?></p>
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i
                                                    class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-600' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if (isAdmin()): ?>
                                    <form method="POST" action="/ecommerce-store/includes/auth.php">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" name="delete_review" class="text-red-400 text-sm">
                                            حذف
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p class="mt-2 text-gray-300"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

<?php } else { ?>
    <!-- قائمة المنتجات -->
    <section class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <!-- عنوان داخل إطار زجاجي -->
            <div class="w-fit mx-auto mb-12 backdrop-blur-md bg-white/10 border border-white/20 rounded-xl px-8 py-4">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                    جميع المنتجات
                </h1>
            </div>

            <!-- المنتجات -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php
                $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                while ($product = $stmt->fetch()):
                    ?>
                    <!-- كرت المنتج -->
                    <div
                        class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl overflow-hidden hover:scale-105 transition-transform duration-300">
                        <!-- صورة كاملة بنفس المقاس -->
                        <div class="w-full aspect-[4/3]">
                            <img src="/ecommerce-store/uploads/products/<?= $product['image'] ?? 'default.jpg' ?>"
                                class="w-full h-full object-contain bg-gray-800 rounded-t-xl">
                        </div>

                        <!-- محتوى المنتج -->
                        <div class="p-4 space-y-2">
                            <h3 class="text-white font-bold text-lg truncate" title="<?= htmlspecialchars($product['name']) ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </h3>
                            <p class="text-blue-400 font-bold text-xl">
                                <?= number_format($product['price']) ?> جنيه
                            </p>

                            <!-- زر التفاصيل -->
                            <a href="/ecommerce-store/products.php?id=<?= $product['id'] ?>"
                                class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center py-2 rounded-md transition-colors">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php include __DIR__ . '/includes/footer.php'; ?>