<?php
$title = "الصفحة الرئيسية";
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>


<section class="relative py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1
            class="text-6xl leading-[2] font-bold mb-6 bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
            مرحباً في عالم التسوق
        </h1>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            اكتشف أحدث المنتجات بأفضل الأسعار مع تجربة تسوق فريدة
        </p>
        <a href="/ecommerce-store/products.php"
            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
            تصفح المنتجات <i class="fas fa-arrow-left mr-2"></i>
        </a>
    </div>
</section>


<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-blue-400">خدماتنا المميزة</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass p-8 rounded-2xl text-center hover:bg-blue-900/20 transition">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-blue-400">توصيل سريع</h3>
                <p class="text-gray-300">توصيل خلال 24-48 ساعة إلى باب منزلك</p>
            </div>

            <div class="glass p-8 rounded-2xl text-center hover:bg-blue-900/20 transition">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-purple-400">منتجات أصلية</h3>
                <p class="text-gray-300">ضمان الجودة والأصالة على جميع منتجاتنا</p>
            </div>

            <div class="glass p-8 rounded-2xl text-center hover:bg-blue-900/20 transition">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-pink-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3 text-pink-400">دعم 24/7</h3>
                <p class="text-gray-300">فريق متخصص جاهز لمساعدتك في أي وقت</p>
            </div>
        </div>
    </div>
</section>


<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12 text-blue-400">منتجات مميزة</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php
            $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
            while ($product = $stmt->fetch()):
                ?>
                <div class="glass rounded-xl overflow-hidden hover:transform hover:scale-105 transition">
                    <img src="/ecommerce-store/uploads/products/<?= $product['image'] ?>" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-2 text-white"><?= $product['name'] ?></h3>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                            <?= number_format($product['price']) ?> جنيه
                        </p>
                        <a href="/ecommerce-store/products.php?id=<?= $product['id'] ?>"
                            class="block w-full mt-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-2 rounded-lg text-center hover:from-blue-700 hover:to-purple-700">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>