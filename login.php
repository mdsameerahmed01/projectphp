<?php
session_start();
include("db_connect.php"); // <-- create this file with your DB connection details

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in Admin table
    $adminQuery = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $adminResult = mysqli_query($conn, $adminQuery);
    if (mysqli_num_rows($adminResult) > 0) {
        $_SESSION['role'] = "admin";
        $_SESSION['email'] = $email;
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check in Teacher table
    $teacherQuery = "SELECT * FROM teachers WHERE email='$email' AND password='$password'";
    $teacherResult = mysqli_query($conn, $teacherQuery);
    if (mysqli_num_rows($teacherResult) > 0) {
        $_SESSION['role'] = "teacher";
        $_SESSION['email'] = $email;
        header("Location: teacher_dashboard.php");
        exit();
    }

    // Check in Student table
    $studentQuery = "SELECT * FROM students WHERE email='$email' AND password='$password'";
    $studentResult = mysqli_query($conn, $studentQuery);
    if (mysqli_num_rows($studentResult) > 0) {
        $_SESSION['role'] = "student";
        $_SESSION['email'] = $email;
        header("Location: student_dashboard.php");
        exit();
    }

    $error = "Invalid login credentials!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p style="text-align:center; margin-top:10px;">
            Not registered? 
            <a href="student_register.php">Student</a> | 
            <a href="teacher_register.php">Teacher</a> | 
            <a href="admin_register.php">Admin</a>
        </p>
    </div>
</body>
</html>
