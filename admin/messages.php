<?php
$title = "الرسائل";
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';


if (!isAdmin()) {
    header('Location: /ecommerce-store/index.php');
    exit();
}

// حذف الكل
if (isset($_POST['delete_all_messages'])) {
    if (!isAdmin()) {
        $_SESSION['error'] = "ليس لديك صلاحية";
        header('Location: /ecommerce-store/index.php');
        exit();
    }

    $pdo->query("DELETE FROM messages");
    $_SESSION['success'] = "تم حذف كل الرسائل بنجاح";
    header('Location: /ecommerce-store/admin/messages.php');
    exit();
}

// حذف رسالة
if (isset($_POST['delete_message'])) {
    $id = (int) $_POST['message_id'];
    $pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([$id]);
    $_SESSION['success'] = "تم حذف الرسالة بنجاح";
    header('Location: /ecommerce-store/admin/messages.php');
    exit();
}

$messages = $pdo->query("SELECT m.*, u.name, u.email 
                         FROM messages m 
                         JOIN users u ON m.user_id = u.id 
                         ORDER BY m.created_at DESC")->fetchAll();
include __DIR__ . '/../includes/header.php';
?>

<section class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 text-white px-6 py-12">
    <div class="max-w-5xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">جميع الرسائل</h1>
            <a href="/ecommerce-store/admin/dashboard.php" class="text-blue-400 hover:underline">← لوحة التحكم</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <!-- زر حذف الكل -->
        <form method="POST" class="mb-6" onsubmit="return confirm('هل أنت متأكد من حذف جميع الرسائل؟')">
            <button type="submit" name="delete_all_messages"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-trash-alt mr-2"></i>حذف الكل
            </button>
        </form>
        <div class="glass bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-white/10">
                    <tr>
                        <th class="px-4 py-3 text-right">المرسل</th>
                        <th class="px-4 py-3 text-right">الموضوع</th>
                        <th class="px-4 py-3 text-right">الرسالة</th>
                        <th class="px-4 py-3 text-right">التاريخ</th>
                        <th class="px-4 py-3 text-right">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr class="border-t border-white/10">
                            <td class="px-4 py-3">
                                <p class="font-semibold"><?= htmlspecialchars($msg['name']) ?></p>
                                <p class="text-sm text-gray-400"><?= htmlspecialchars($msg['email']) ?></p>
                            </td>
                            <td class="px-4 py-3"><?= htmlspecialchars($msg['subject']) ?></td>
                            <td class="px-4 py-3 max-w-xs truncate"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                            <td class="px-4 py-3 text-sm"><?= $msg['created_at'] ?></td>
                            <td class="px-4 py-3">
                                <form method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                    <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                                    <button type="submit" name="delete_message" class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>