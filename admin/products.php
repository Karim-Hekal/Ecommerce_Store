<?php
$title = "إدارة المنتجات";
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    header('Location: /ecommerce-store/index.php');
    exit();
}


// معالج POST داخلي
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. إضافة منتج
    if (isset($_POST['add_product'])) {
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = (float) $_POST['price'];
        $stock = (int) $_POST['stock'];
        $image_name = 'default.jpg';

        if (!empty($_FILES['image']['name'])) {
            $image_name = uploadImage($_FILES['image'], 'uploads/products') ?: 'default.jpg';
        }

        $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)")
            ->execute([$name, $description, $price, $stock, $image_name]);
        $_SESSION['success'] = "تم إضافة المنتج بنجاح";
    }

    // 2. تحديث منتج
    if (isset($_POST['update_product'])) {
        $id = (int) $_POST['product_id'];
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = (float) $_POST['price'];
        $stock = (int) $_POST['stock'];
        $old_image = $_POST['old_image'] ?? 'default.jpg';
        $image_name = $old_image;

        if (!empty($_FILES['image']['name'])) {
            $new_image = uploadImage($_FILES['image'], 'uploads/products');
            if ($new_image) {
                if ($old_image !== 'default.jpg') {
                    unlink(__DIR__ . '/../uploads/products/' . $old_image);
                }
                $image_name = $new_image;
            }
        }

        $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?")
            ->execute([$name, $description, $price, $stock, $image_name, $id]);
        $_SESSION['success'] = "تم تحديث المنتج بنجاح";
    }

    // 3. حذف منتج
    if (isset($_POST['delete_product'])) {
        $id = (int) $_POST['product_id'];
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product['image'] !== 'default.jpg') {
            unlink(__DIR__ . '/../uploads/products/' . $product['image']);
        }

        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        $_SESSION['success'] = "تم حذف المنتج بنجاح";
    }

    // 4. حذف كل المنتجات
    if (isset($_POST['delete_all_products'])) {
        $images = $pdo->query("SELECT image FROM products WHERE image != 'default.jpg'")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($images as $img) {
            $path = __DIR__ . '/../uploads/products/' . $img;
            if (file_exists($path))
                unlink($path);
        }
        $pdo->query("DELETE FROM products");
        $_SESSION['success'] = "تم حذف كل المنتجات بنجاح";
    }

    header('Location: /ecommerce-store/admin/products.php');
    exit();
}

// استعلام المنتجات
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<!-- خلفية داكنة -->
<section class="py-12 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <!-- زر عائم للعودة إلى Dashboard -->
            <div class="flex items-center justify-between mb-8">
                <a href="/ecommerce-store/admin/dashboard.php"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i> لوحة التحكم
                </a>
            </div>
            <h1
                class="text-4xl leading-[2] font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                إدارة المنتجات
            </h1>
            <button onclick="toggleAddForm()"
                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>إضافة منتج
            </button>
        </div>

        <!-- رسائل النجاح -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- نموذج الإضافة -->
        <div id="addForm" class="hidden glass bg-gray-800/50 p-6 rounded-xl mb-6">
            <form method="POST" action="/ecommerce-store/includes/auth.php" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="add_product">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="اسم المنتج" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400">
                    <input type="number" name="price" placeholder="السعر" step="0.01" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400">
                </div>
                <textarea name="description" placeholder="وصف المنتج" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400"></textarea>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="number" name="stock" placeholder="المخزون" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400">
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                </div>
                <button type="submit" name="add_product"
                    class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-2 rounded-lg hover:from-green-700 hover:to-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>إضافة
                </button>
            </form>
        </div>

        <!-- زر حذف الكل – يظهر فقط إذا وجدت منتجات -->
        <?php if ($products && count($products) > 0): ?>
            <form method="POST" class="mb-6" onsubmit="return confirm('هل أنت متأكد من حذف جميع المنتجات؟')">
                <button type="submit" name="delete_all_products"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-trash-alt mr-2"></i>حذف الكل
                </button>
            </form>
        <?php endif; ?>

        <!-- جدول المنتجات -->
        <div class="glass bg-gray-800/50 backdrop-blur-xl rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-800/80">
                    <tr>
                        <th class="px-6 py-4 text-right text-gray-300">الصورة</th>
                        <th class="px-6 py-4 text-right text-gray-300">الاسم</th>
                        <th class="px-6 py-4 text-right text-gray-300">السعر</th>
                        <th class="px-6 py-4 text-right text-gray-300">المخزون</th>
                        <th class="px-6 py-4 text-right text-gray-300">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <img src="/ecommerce-store/uploads/products/<?= $product['image'] ?? 'default.jpg' ?>"
                                    class="w-16 h-16 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($product['name']) ?></td>
                            <td class="px-6 py-4 text-blue-400 font-bold"><?= number_format($product['price']) ?></td>
                            <td class="px-6 py-4 text-green-400"><?= $product['stock'] ?></td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2 space-x-reverse">
                                    <button onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)"
                                        class="text-blue-400 hover:text-blue-300">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" name="delete_product" class="text-red-400 hover:text-red-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- نموذج التعديل -->
        <div id="editModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="glass bg-gray-800/90 backdrop-blur-xl rounded-xl p-8 w-full max-w-md">
                <h3 class="text-2xl font-bold text-white mb-6">تعديل المنتج</h3>
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="product_id" id="edit_id">
                    <input type="hidden" name="old_image" id="edit_old_image">
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <textarea name="description" id="edit_description" rows="3"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white"></textarea>
                    <input type="number" name="price" id="edit_price" step="0.01" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <input type="number" name="stock" id="edit_stock" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white">
                    <div class="flex justify-end space-x-3 space-x-reverse">
                        <button type="button" onclick="closeEditModal()"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-500">إلغاء</button>
                        <button type="submit" name="update_product"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700">
                            تحديث
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<script>
    function toggleAddForm() {
        document.getElementById('addForm').classList.toggle('hidden');
    }

    function editProduct(product) {
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_description').value = product.description;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock').value = product.stock;
        document.getElementById('edit_old_image').value = product.image;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>