<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Admin Registration</h2>
    <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>First Name</label>
        <input type="text" name="fname" required>

        <label>Last Name</label>
        <input type="text" name="lname" required>

        <button type="submit" name="register">Register</button>
    </form>
</div>

<?php
if (isset($_POST['register'])) {
    include("db_connect.php");

    $email    = $_POST['email'];
    $password = $_POST['password']; // later we will encrypt
    $fname    = $_POST['fname'];
    $lname    = $_POST['lname'];

    $sql = "INSERT INTO admins (email, password, first_name, last_name) 
            VALUES ('$email', '$password', '$fname', '$lname')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='text-align:center;color:green;'>Admin Registered Successfully!</p>";
    } else {
        echo "<p style='text-align:center;color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
</body>
</html>
