<?php

require_once __DIR__ . '/session_cookie/session_init.php';
include("db_connect.php");

$errors = [];
$success_message = "";
$fname = $lname = $gender = $dob = $address = $email = $contact = $subjects = $qualification = $teacher_id = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {

    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $subjects = trim($_POST['subjects'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $teacher_id = trim($_POST['teacher_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($fname) || empty($lname) || empty($email) || empty($teacher_id) || empty($password)) {
        $errors[] = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        $check_sql = "SELECT email FROM teachers WHERE email = ? OR teacher_id = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $check_sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $teacher_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "This Email or Teacher ID is already registered.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_sql = "INSERT INTO teachers 
            (first_name, last_name, gender, dob, address, email, contact, subjects, qualification, teacher_id, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $insert_sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssssss", 
                $fname, $lname, $gender, $dob, $address, $email, $contact, $subjects, $qualification, $teacher_id, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Teacher Registered Successfully!";
                $fname = $lname = $gender = $dob = $address = $email = $contact = $subjects = $qualification = $teacher_id = "";
            } else {
                $errors[] = "Error saving record: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body>
<div class="black-fill"><br>
    <div class="form-container">
        <h2>Teacher Registration</h2>

        <?php if (!empty($errors)): ?>
            <div style="color:red;text-align:center;padding:10px;border:1px solid red;margin-bottom:10px;">
                <?php foreach ($errors as $err) echo "<p>" . htmlspecialchars($err) . "</p>"; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div style="color:green;text-align:center;padding:10px;border:1px solid green;margin-bottom:10px;">
                <p><?= htmlspecialchars($success_message); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>First Name</label>
            <input type="text" name="fname" required value="<?= htmlspecialchars($fname); ?>">

            <label>Last Name</label>
            <input type="text" name="lname" required value="<?= htmlspecialchars($lname); ?>">

            <label>Gender</label>
            <select name="gender">
                <option value="">Select</option>
                <option <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                <option <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                <option <?= $gender === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>

            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($dob); ?>">

            <label>Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($address); ?>">

            <label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($email); ?>">

            <label>Contact Number</label>
            <input type="text" name="contact" value="<?= htmlspecialchars($contact); ?>">

            <label>Subject(s) of Expertise</label>
            <input type="text" name="subjects" value="<?= htmlspecialchars($subjects); ?>">

            <label>Educational Qualifications</label>
            <input type="text" name="qualification" value="<?= htmlspecialchars($qualification); ?>">

            <label>Teacher ID</label>
            <input type="text" name="teacher_id" required value="<?= htmlspecialchars($teacher_id); ?>">

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Register</button>
        </form>

        <a class="login-link" href="login.php">Already have an account? Login</a>
    </div>
</div>
</body>
</html>
