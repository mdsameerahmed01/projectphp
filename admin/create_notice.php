<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);
    $status = isset($_POST['status']) && $_POST['status'] == '1' ? 1 : 0;
    $created_by = $_SESSION['email'];

    if ($title === '' || $message === '') {
        $error = "Title and message are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO notices (title, message, created_by, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $message, $created_by, $status);
        if ($stmt->execute()) {
            header("Location: admin_home.php?msg=Notice created");
            exit();
        } else {
            $error = "DB Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Notice</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="body-home">
    <div class="black-fill">
        <div class="form-container">
            <h2>Create Notice</h2>
            <?php if (!empty($error)): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <form method="POST">
                <label>Title</label>
                <input type="text" name="title" required>

                <label>Message</label>
                <textarea name="message" rows="6" required></textarea>

                <label>Publish</label>
                <select name="status">
                    <option value="1">Published</option>
                    <option value="0">Draft / Hidden</option>
                </select>

                <button type="submit">Create</button>
            </form>
            <a class="login-link" href="admin_home.php">⬅ Back to Notices</a>
        </div>
    </div>
</body>

</html>