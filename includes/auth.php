<?php
// تصحيح المسار إلى config/database.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isset($_POST['login'])) {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_image'] = $user['profile_image'] ?? 'default.jpg';
        redirect($user['role'] === 'admin' ? '/ecommerce-store/admin/dashboard.php' : '/ecommerce-store/index.php');
    } else {
        $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة";
        redirect('/ecommerce-store/login.php');
    }
}

if (isset($_POST['register'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        $_SESSION['success'] = "تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول";
        redirect('/ecommerce-store/login.php');
    } catch (PDOException $e) {
        $_SESSION['error'] = "البريد الإلكتروني مستخدم بالفعل";
        redirect('/ecommerce-store/register.php');
    }
}

if (isset($_POST['update_profile'])) {
    if (!isLoggedIn())
        redirect('/ecommerce-store/login.php');

    $name = sanitize($_POST['name']);
    $user_id = $_SESSION['user_id'];
    $oldImage = $_SESSION['user_image'] ?? 'default.jpg';

    $newImage = $oldImage;
    if (!empty($_FILES['profile_image']['name'])) {
        $upload = uploadImage($_FILES['profile_image'], 'uploads/profiles');
        if ($upload) {
            if ($oldImage !== 'default.jpg') {
                $oldPath = __DIR__ . '/../uploads/profiles/' . $oldImage;
                if (file_exists($oldPath))
                    unlink($oldPath);
            }
            $newImage = $upload;
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$name, $newImage, $user_id]);

    $_SESSION['user_name'] = $name;
    $_SESSION['user_image'] = $newImage;
    $_SESSION['success'] = "تم تحديث الملف الشخصي";
    redirect('/ecommerce-store/profile.php');
}

if (isset($_POST['add_review'])) {
    if (!isLoggedIn())
        redirect('login.php');

    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = sanitize($_POST['comment']);

    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_id, $_SESSION['user_id'], $rating, $comment]);

    $_SESSION['success'] = "تم إضافة تقييمك بنجاح";
    redirect("/ecommerce-store/products.php?id=$product_id");
}

if (isset($_POST['send_message'])) {
    if (!isLoggedIn())
        redirect('/ecommerce-store/login.php');

    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);

    $stmt = $pdo->prepare("INSERT INTO messages (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $subject, $message]);

    $_SESSION['success'] = "تم إرسال رسالتك بنجاح";
    redirect('/ecommerce-store/contact.php');
}

if (isset($_POST['delete_review'])) {
    if (!isAdmin()) {
        $_SESSION['error'] = "ليس لديك صلاحية";
        redirect('index.php');
    }

    $review_id = $_POST['review_id'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$review_id]);

    $_SESSION['success'] = "تم حذف التعليق بنجاح";
    $product_id = $_POST['product_id'] ?? $_SERVER['HTTP_REFERER'];
    redirect("/ecommerce-store/products.php?id=" . $product_id);
}
// معالج رسالة تواصل معنا
if (isset($_POST['send_message'])) {
    if (!isLoggedIn()) {
        $_SESSION['error'] = "يجب تسجيل الدخول أولاً";
        header('Location: /ecommerce-store/login.php');
        exit();
    }

    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $stmt = $pdo->prepare("INSERT INTO messages (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $subject, $message]);

    $_SESSION['success'] = "تم إرسال الرسالة بنجاح";
    header('Location: /ecommerce-store/contact.php');
    exit();
}

// تغيير كلمة المرور
if (isset($_POST['change_password'])) {
    if (!isLoggedIn())
        redirect('/ecommerce-store/login.php');

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if ($new !== $confirm) {
        $_SESSION['error'] = "كلمتا المرور الجديدتان غير متطابقتين";
        redirect('/ecommerce-store/profile.php');
    }

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!password_verify($current, $user['password'])) {
        $_SESSION['error'] = "كلمة المرور الحالية غير صحيحة";
        redirect('/ecommerce-store/profile.php');
    }

    $hash = password_hash($new, PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hash, $user_id]);

    $_SESSION['success'] = "تم تغيير كلمة المرور بنجاح";
    redirect('/ecommerce-store/profile.php');
}


// إضافة منتج جديد
if (isset($_POST['form_type']) && $_POST['form_type'] === 'add_product') {
    if (!isAdmin())
        redirect('/ecommerce-store/index.php');

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
    redirect('/ecommerce-store/admin/products.php');
    exit();
}

// تعديل منتج
if (isset($_POST['form_type']) && $_POST['form_type'] === 'update_product') {
    if (!isAdmin())
        redirect('/ecommerce-store/index.php');

    $id = (int) $_POST['product_id'];
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $old_image = $_POST['old_image'] ?? 'default.jpg';
    $image_name = $old_image;

    // رفع صورة جديدة إن وجدت
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
    redirect('/ecommerce-store/admin/products.php');
    exit();
}
?>