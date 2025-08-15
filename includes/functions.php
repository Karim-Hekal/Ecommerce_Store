<?php
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function uploadImage($file, $folder)
{
    if (!$file || $file['error'] !== UPLOAD_ERR_OK)
        return false;

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed))
        return false;

    $newName = uniqid() . '.' . $ext;
    $path = __DIR__ . '/../' . $folder . '/' . $newName;

    if (!is_dir(dirname($path)))
        mkdir(dirname($path), 0755, true);

    move_uploaded_file($file['tmp_name'], $path);

    return $newName;
}

function getUserById($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
?>