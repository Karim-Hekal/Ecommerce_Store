<?php
$title = "إدارة المستخدمين";
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin())
    redirect('../index.php');

// إضافة/تعديل/حذف المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // إضافة مستخدم
    if (isset($_POST['add_user'])) {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        $_SESSION['success'] = "تم إضافة المستخدم بنجاح";
    }

    // تعديل مستخدم
    if (isset($_POST['edit_user'])) {
        $id = $_POST['user_id'];
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $role = $_POST['role'];

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, password=?, role=? WHERE id=?");
            $stmt->execute([$name, $email, $password, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
            $stmt->execute([$name, $email, $role, $id]);
        }

        $_SESSION['success'] = "تم تعديل بيانات المستخدم";
    }


    // حذف مستخدم
    if (isset($_POST['delete_user'])) {
        $id = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "تم حذف المستخدم بنجاح";
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8 text-black">
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
            إدارة المستخدمين</h1>
        <button onclick="toggleAddForm()"
            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">

            <i class="fas fa-plus mr-2"></i> إضافة مستخدم
        </button>
    </div>

    <!-- نموذج الإضافة -->
    <div id="addForm" class="glass bg-gray-800/50 backdrop-blur-xl rounded-xl overflow-hidden p-7">
        <form method="POST" class="flex flex-wrap gap-3 items-center text-gray-300">
            <input type="text" name="name" placeholder="الاسم" required class="p-2 border rounded text-black w-64">
            <input type="email" name="email" placeholder="البريد الإلكتروني" required
                class="p-2 border rounded text-black w-64">
            <input type="password" name="password" placeholder="كلمة المرور" required
                class="p-2 border rounded text-blackر w-64">

            <select name="role" class="p-2 border rounded text-black w-40">
                <option value="user">مستخدم</option>
                <option value="admin">مدير</option>
            </select>

            <button type="submit" name="add_user" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-md text-white">
                إضافة
            </button>
        </form>
    </div>


    <!-- جدول المستخدمين -->
    <div class="glass bg-gray-800/50 backdrop-blur-xl rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-800/80">
                <tr>
                    <th class="px-6 py-4 text-right text-gray-300">الاسم</th>
                    <th class="px-6 py-4 text-right text-gray-300">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-gray-300">الدور</th>
                    <th class="px-6 py-4 text-right text-gray-300">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="border-t border-gray-700 hover:bg-gray-700/50 transition">
                        <!-- الاسم -->
                        <td class="px-6 py-4 text-white font-semibold"><?= $user['name'] ?></td>

                        <!-- البريد الإلكتروني -->
                        <td class="px-6 py-4 text-blue-400 font-bold"><?= $user['email'] ?></td>

                        <!-- الدور -->
                        <td class="px-6 py-4">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-medium shadow-sm 
                            <?= $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                <?= $user['role'] === 'admin' ? ' مــديـر ' : 'مستخدم' ?>
                            </span>
                        </td>

                        <!-- الإجراءات -->
                        <td class="px-6 py-4 flex gap-6">
                            <!-- زر تعديل -->
                            <button type="button"
                                onclick="openEditModal(<?= $user['id'] ?>, '<?= $user['name'] ?>', '<?= $user['email'] ?>', '<?= $user['role'] ?>')"
                                class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- زر الحذف -->
                            <form method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="delete_user" class="text-red-600 hover:text-red-800"
                                    title="حذف المستخدم">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- نافذة التعديل -->
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div
            class="bg-gray-800/40 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl w-full max-w-lg p-6 text-gray-200">
            <h2 class="text-2xl font-bold mb-4 text-white">تعديل المستخدم</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="user_id" id="edit_user_id">

                <input type="text" name="name" id="edit_name" placeholder="الاسم" required
                    class="w-full p-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:border-blue-500">

                <input type="email" name="email" id="edit_email" placeholder="البريد الإلكتروني" required
                    class="w-full p-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:border-blue-500">

                <input type="password" name="password" placeholder="كلمة المرور (اتركها فارغة إذا لا تريد التغيير)"
                    class="w-full p-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:border-blue-500">

                <select name="role" id="edit_role"
                    class="w-full p-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:border-blue-500">
                    <option value="user">مستخدم</option>
                    <option value="admin">مدير</option>
                </select>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg border border-white/20 text-white">
                        إلغاء
                    </button>
                    <button type="submit" name="edit_user"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white shadow-lg shadow-blue-500/30">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function openEditModal(id, name, email, role) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }
    </script>

    <script>
        function toggleAddForm() {
            document.getElementById('addForm').classList.toggle('hidden');
        }
    </script>

    <?php include '../includes/footer.php'; ?>