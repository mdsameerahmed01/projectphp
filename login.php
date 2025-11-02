<?php

ob_start();
require_once __DIR__ . '/session_cookie/session_init.php';
include("db_connect.php");

$error = ""; 
$email = ""; 
$role = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = trim($_POST['email'] ?? '');
    $password_input = $_POST['password'] ?? ''; 
    $role = trim($_POST['role'] ?? '');
    
    if (empty($email) || empty($role) || $password_input === '') {
        $error = "Please fill all required fields.";
    } else {

        $table = "";
        $email_col = "";
        $redirect = "";
        $hash_col = "password_hash"; 

        switch ($role) {
            case "admin":
                $table = "admins";
                $email_col = "email";
                $redirect = "admin/admin_home.php";
                break;
            case "teacher":
                $table = "teachers";
                $email_col = "email";
                $redirect = "teacher/teacher_home.php";
                break;
            case "student":
                $table = "students";
                $email_col = "parent_email"; 
                $redirect = "student/student_home.php";
                break;
            case "parent":
                $table = "parents";
                $email_col = "email";
                $redirect = "index.php"; 
                break;
            default:
                $error = "Invalid role selected.";
                break;
        }


        if (!$error && $table !== "") {
            
            $sql = "SELECT $hash_col FROM $table WHERE $email_col = ? LIMIT 1";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) === 1) {
                    mysqli_stmt_bind_result($stmt, $password_db_hash);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    if (password_verify($password_input, $password_db_hash)) {
                        
                        session_regenerate_id(true); 
                        $_SESSION['role'] = $role;
                        $_SESSION['email'] = $email;

                        // Secure Logging
                        $log_sql = "INSERT INTO login_logs (email, role) VALUES (?, ?)";
                        if ($log_stmt = mysqli_prepare($conn, $log_sql)) {
                            mysqli_stmt_bind_param($log_stmt, "ss", $email, $role);
                            mysqli_stmt_execute($log_stmt);
                            mysqli_stmt_close($log_stmt);
                        }

                        header("Location: $redirect");
                        exit();
                    } else {
                        $error = "Invalid email or password for $role!";
                    }

                } else {
                    $error = "Invalid email or password for $role!";
                }
            } else {
                $error = "Database preparation error. Please try again.";
            }
        }
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <div class="black-fill"><br>
        <div class="form-container">
            <h2>Login</h2>
            <?php if (isset($error)) {
                echo "<p style='color:red; text-align:center;'>" . htmlspecialchars($error) . "</p>";
            } ?>
            <form method="POST" action="">
                <label>Role</label>
                <select name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="teacher" <?= $role === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                    <option value="student" <?= $role === 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="parent" <?= $role === 'parent' ? 'selected' : ''; ?>>Parent</option>
                </select>

                <label>Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($email); ?>">

                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" required class="form-control">
                    <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>

                <button type="submit">Login</button>
            </form>

            <p style="text-align:center; margin-top:10px;">
                Not registered?
                <a href="student_register.php">Student</a> |
                <a href="parent_register.php">Praent</a> |
                <a href="teacher_register.php">Teacher</a> |
                <a href="admin_register.php">Admin</a>
            </p>
        </div>
    </div>
    <script>
    function togglePassword() {
        const pass = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");
        
        if (pass.type === "password") {
        
            pass.type = "text";
        
            eyeIcon.classList.remove("bi-eye"); 
            eyeIcon.classList.add("bi-eye-slash");
        } else {  
            pass.type = "password";
            
            eyeIcon.classList.remove("bi-eye-slash"); 
            eyeIcon.classList.add("bi-eye");
        }
    }
</script>
</body>

</html>
