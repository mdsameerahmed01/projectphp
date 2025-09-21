<?php
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == "admin") {
        $query = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['role'] = "admin";
            $_SESSION['email'] = $email;

            mysqli_query($conn, "INSERT INTO login_logs (email, role) VALUES ('$email', 'admin')");

            header("Location: admin/admin_home.php");
            exit();
        }
    } elseif ($role == "teacher") {
        $query = "SELECT * FROM teachers WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['role'] = "teacher";
            $_SESSION['email'] = $email;

            mysqli_query($conn, "INSERT INTO login_logs (email, role) VALUES ('$email', 'teacher')");

            header("Location: teacher/teacher_home.php");
            exit();
        }
    } elseif ($role == "student") {
        $query = "SELECT * FROM students WHERE parent_email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['role'] = "student";
            $_SESSION['email'] = $email;

            mysqli_query($conn, "INSERT INTO login_logs (email, role) VALUES ('$email', 'student')");

            header("Location: student/student_home.php");
            exit();
        }
    } elseif ($role == "other") {
        $_SESSION['role'] = "other";
        $_SESSION['email'] = $email;

        mysqli_query($conn, "INSERT INTO login_logs (email, role) VALUES ('$email', 'other')");

        header("Location: index.php?msg=Welcome Guest!");
        exit();
    }

    $error = "Invalid email or password for $role!";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="icon" href="img/logo.png">
</head>

<body style="background-image: url('img/s2.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="black-fill"><br>
        <div class="form-container">
            <h2>Login</h2>
            <?php if (isset($error)) {
                echo "<p style='color:red; text-align:center;'>$error</p>";
            } ?>
            <form method="POST" action="">
                <label>Role</label>
                <select name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                    <option value="other">Other</option>
                </select>

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
    </div>
</body>
</html>
