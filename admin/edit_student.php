<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

$id = $_GET['id'];
$query = "SELECT * FROM students WHERE id='$id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $class = $_POST['class'];
    $parent_email = $_POST['parent_email'];
    $contact = $_POST['contact'];

    $update = "UPDATE students 
               SET first_name='$fname', last_name='$lname', class='$class', parent_email='$parent_email', contact='$contact' 
               WHERE id='$id'";
    if (mysqli_query($conn, $update)) {
        header("Location: manage_students.php?msg=Student Updated Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="black-fill">
    <div class="form-container">
        <h2>Edit Student</h2>
        <form method="POST">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required>

            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required>

            <label>Class</label>
            <input type="text" name="class" value="<?php echo $student['class']; ?>" required>

            <label>Parent Email</label>
            <input type="email" name="parent_email" value="<?php echo $student['parent_email']; ?>" required>

            <label>Contact</label>
            <input type="text" name="contact" value="<?php echo $student['contact']; ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
        <a class="login-link" href="manage_students.php">⬅ Back</a>
    </div>
</div>
</body>
</html>
