<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

$id = $_GET['id'];
$delete = "DELETE FROM students WHERE id='$id'";
if (mysqli_query($conn, $delete)) {
    header("Location: manage_students.php?msg=Student Deleted Successfully");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
