<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}

include("../db_connect.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_home.php?msg=Invalid notice ID");
    exit();
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM notices WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$notice = $res->fetch_assoc();

if (!$notice) {
    header("Location: admin_home.php?msg=Notice not found");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $status = (isset($_POST['status']) && $_POST['status'] === '1') ? 1 : 0;

    if ($title === '' || $message === '') {
        $error = "Title and message are required.";
    } elseif (mb_strlen($title) > 255) {
        $error = "Title must be 255 characters or less.";
    } else {
        $u = $conn->prepare("UPDATE notices SET title = ?, message = ?, status = ? WHERE id = ?");
        $u->bind_param("ssii", $title, $message, $status, $id);
        if ($u->execute()) {
            header("Location: admin_home.php?msg=Notice updated successfully");
            exit();
        } else {
            $error = "Database error: " . htmlspecialchars($u->error);
        }
    }

    $notice['title'] = $title;
    $notice['message'] = $message;
    $notice['status'] = $status;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Notice</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="body-home">
    <div class="black-fill">
        <div class="form-container">
            <h2>Edit Notice</h2>

            <?php if (!empty($error)): ?>
                <p class="error" style="text-align:center;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($notice['title'] ?? '') ?>" required>

                <label>Message</label>
                <textarea name="message" rows="6" required><?= htmlspecialchars($notice['message'] ?? '') ?></textarea>

                <label>Publish</label>
                <select name="status">
                    <option value="1" <?= (!empty($notice['status']) && $notice['status'] == 1) ? 'selected' : '' ?>>Published</option>
                    <option value="0" <?= (isset($notice['status']) && $notice['status'] == 0) ? 'selected' : '' ?>>Draft</option>
                </select>

                <div style="margin-top:12px;">
                    <button type="submit">Update</button>
                    <a class="login-link" href="admin_home.php" style="display:inline-block; margin-left:10px;">⬅ Back</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>