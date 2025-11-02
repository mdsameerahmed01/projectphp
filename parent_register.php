<?php

require_once __DIR__ . '/session_cookie/session_init.php';
include("db_connect.php");

$errors = [];
$success_message = "";
$email = $fname = $lname = $role = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $fname    = trim($_POST['fname'] ?? '');
    $lname    = trim($_POST['lname'] ?? '');

    if (empty($email) || empty($password) || empty($fname) || empty($lname)) {
        $errors[] = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($password) < 8) { 
        $errors[] = "Password must be at least 8 characters long.";
    }
    
    if (empty($errors)) {
        $check_sql = "SELECT email FROM parents WHERE email = ? LIMIT 1";
        
        if ($stmt = mysqli_prepare($conn, $check_sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "This email is already registered. Please use another one.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database check error: " . mysqli_error($conn);
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_sql = "INSERT INTO parents (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $insert_sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $fname, $lname, $email, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Parent Registered Successfully! You can now log in.";
                $email = $fname = $lname; 
            } else {
                $errors[] = "Registration Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database preparation error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Parent Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <div class="black-fill"><br>
        <div class="form-container">
            <h2>Parent Registration</h2>

            <?php if (!empty($errors)): ?>
                <div style="color:red; text-align:center; padding:10px; border:1px solid red; margin-bottom:10px;">
                    <?php foreach ($errors as $err) {
                        echo "<p>" . htmlspecialchars($err) . "</p>";
                    } ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div style="color:green; text-align:center; padding:10px; border:1px solid green; margin-bottom:10px;">
                    <p><?= htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <label>Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($email); ?>">

                <label>Password</label>
                <input type="password" name="password" required>

                <label>First Name</label>
                <input type="text" name="fname" required value="<?= htmlspecialchars($fname); ?>">

                <label>Last Name</label>
                <input type="text" name="lname" required value="<?= htmlspecialchars($lname); ?>">

                <button type="submit" name="register">Register</button>
            </form>
            <a class="login-link" href="login.php">Already have an account? Login</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>