<?php

require_once __DIR__ . '/session_cookie/session_init.php';
include("db_connect.php");

$errors = [];
$success_message = "";
$fname = $lname = $dob = $gender = $address = $contact = $class = $father_name = $mother_name = $parent_email = $parent_contact = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $password = $_POST['password'] ?? '';
    $dob = trim($_POST['dob'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $mother_name = trim($_POST['mother_name'] ?? '');
    $parent_email = trim($_POST['parent_email'] ?? '');
    $parent_contact = trim($_POST['parent_contact'] ?? '');
    
    if (empty($fname) || empty($lname) || empty($parent_email) || empty($password)) {
        $errors[] = "Please fill in all required fields.";
    } elseif (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Parent Email format.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        $check_sql = "SELECT parent_email FROM students WHERE parent_email = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $check_sql)) {
            mysqli_stmt_bind_param($stmt, "s", $parent_email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "This Parent Email is already registered.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_sql = "INSERT INTO students 
            (first_name, last_name, password_hash, dob, gender, address, contact, class, father_name, mother_name, parent_email, parent_contact)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $insert_sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssssssss",
                $fname, $lname, $hashed_password, $dob, $gender, $address, $contact, $class, $father_name, $mother_name, $parent_email, $parent_contact);

            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Student Registered Successfully!";
                $fname = $lname = $dob = $gender = $address = $contact = $class = $father_name = $mother_name = $parent_email = $parent_contact = "";
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
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body>
<div class="black-fill"><br>
    <div class="form-container">
        <h2>Student Registration</h2>

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

            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($dob); ?>">

            <label>Gender</label>
            <select name="gender">
                <option value="">Select</option>
                <option <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                <option <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                <option <?= $gender === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>

            <label>Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($address); ?>">

            <label>Contact Number</label>
            <input type="text" name="contact" value="<?= htmlspecialchars($contact); ?>">

            <label>Class/Grade</label>
            <select name="class">
                <option value="">Select</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $class == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>

            <hr>
            <h3>Parent/Guardian Information</h3>

            <label>Father's Name</label>
            <input type="text" name="father_name" value="<?= htmlspecialchars($father_name); ?>">

            <label>Mother's Name</label>
            <input type="text" name="mother_name" value="<?= htmlspecialchars($mother_name); ?>">

            <label>Parent Email</label>
            <input type="email" name="parent_email" required value="<?= htmlspecialchars($parent_email); ?>">

            <label>Parent Contact Number</label>
            <input type="text" name="parent_contact" value="<?= htmlspecialchars($parent_contact); ?>">

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Register</button>
        </form>

        <a class="login-link" href="login.php">Already have an account? Login</a>
    </div>
</div>
</body>

</html>
