<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
$stmt->bind_param("i",$id);
if ($stmt->execute()) {
    header("Location: manage_notices.php?msg=Deleted");
    exit();
} else {
    die("DB error: " . $stmt->error);
}
?>
