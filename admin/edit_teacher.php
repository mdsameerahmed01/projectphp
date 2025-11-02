<?php 
require_once __DIR__ . '/../session_cookie/session_init.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php?msg=Access Denied");
    exit();
}
include("../db_connect.php");

$id = $_GET['id'];
$query = "SELECT * FROM teachers WHERE id='$id'";
$result = mysqli_query($conn, $query);
$teacher = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $subjects = $_POST['subjects'];
    $qualification = $_POST['qualification'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    $update = "UPDATE teachers 
               SET first_name='$fname', last_name='$lname', subjects='$subjects', qualification='$qualification', email='$email', contact='$contact' 
               WHERE id='$id'";
    if (mysqli_query($conn, $update)) {
        header("Location: manage_teachers.php?msg=Teacher Updated Successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="black-fill">
    <div class="form-container">
        <h2>Edit Teacher</h2>
        <form method="POST">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $teacher['first_name']; ?>" required>

            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $teacher['last_name']; ?>" required>

            <label>Subjects</label>
            <input type="text" name="subjects" value="<?php echo $teacher['subjects']; ?>" required>

            <label>Qualification</label>
            <input type="text" name="qualification" value="<?php echo $teacher['qualification']; ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo $teacher['email']; ?>" required>

            <label>Contact</label>
            <input type="text" name="contact" value="<?php echo $teacher['contact']; ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
        <a class="login-link" href="manage_teachers.php">⬅ Back</a>
    </div>
</div>
</body>
</html>
