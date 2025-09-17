<!DOCTYPE html>
<html>
<head>
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Teacher Registration</h2>
    <form method="POST" action="">
        <label>First Name</label>
        <input type="text" name="fname" required>

        <label>Last Name</label>
        <input type="text" name="lname" required>

        <label>Gender</label>
        <select name="gender">
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
        </select>

        <label>Date of Birth</label>
        <input type="date" name="dob">

        <label>Address</label>
        <input type="text" name="address">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Contact Number</label>
        <input type="text" name="contact">

        <label>Subject(s) of Expertise</label>
        <input type="text" name="subjects">

        <label>Educational Qualifications</label>
        <input type="text" name="qualification">

        <label>Teacher ID</label>
        <input type="text" name="teacher_id" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="register">Register</button>
    </form>
</div>

<?php
if (isset($_POST['register'])) {
    include("db_connect.php");

    $fname        = $_POST['fname'];
    $lname        = $_POST['lname'];
    $gender       = $_POST['gender'];
    $dob          = $_POST['dob'];
    $address      = $_POST['address'];
    $email        = $_POST['email'];
    $contact      = $_POST['contact'];
    $subjects     = $_POST['subjects'];
    $qualification= $_POST['qualification'];
    $teacher_id   = $_POST['teacher_id'];
    $password     = $_POST['password'];

    $sql = "INSERT INTO teachers 
            (first_name, last_name, gender, dob, address, email, contact, subjects, qualification, teacher_id, password)
            VALUES 
            ('$fname', '$lname', '$gender', '$dob', '$address', '$email', '$contact', '$subjects', '$qualification', '$teacher_id', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='text-align:center;color:green;'>Teacher Registered Successfully!</p>";
    } else {
        echo "<p style='text-align:center;color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
</body>
</html>
