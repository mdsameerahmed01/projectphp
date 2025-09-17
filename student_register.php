<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Student Registration</h2>
    <form method="POST" action="">
        <label>First Name</label>
        <input type="text" name="fname" required>

        <label>Last Name</label>
        <input type="text" name="lname" required>

        <label>Date of Birth</label>
        <input type="date" name="dob">

        <label>Gender</label>
        <select name="gender">
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
        </select>

        <label>Address</label>
        <input type="text" name="address">

        <label>Contact Number</label>
        <input type="text" name="contact">

        <label>Class/Grade</label>
        <select name="class">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
        </select>

        <hr>
        <h3>Parent/Guardian Information</h3>

        <label>Father's Name</label>
        <input type="text" name="father_name">

        <label>Mother's Name</label>
        <input type="text" name="mother_name">

        <label>Parent Email</label>
        <input type="email" name="parent_email">

        <label>Parent Contact Number</label>
        <input type="text" name="parent_contact">

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
    $dob          = $_POST['dob'];
    $gender       = $_POST['gender'];
    $address      = $_POST['address'];
    $contact      = $_POST['contact'];
    $class        = $_POST['class'];
    $father_name  = $_POST['father_name'];
    $mother_name  = $_POST['mother_name'];
    $parent_email = $_POST['parent_email'];
    $parent_contact = $_POST['parent_contact'];
    $password     = $_POST['password'];

    // Insert query
    $sql = "INSERT INTO students 
        (first_name, last_name, dob, gender, address, contact, class, father_name, mother_name, parent_email, parent_contact, password)
        VALUES 
        ('$fname', '$lname', '$dob', '$gender', '$address', '$contact', '$class', '$father_name', '$mother_name', '$parent_email', '$parent_contact', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='text-align:center;color:green;'>Student Registered Successfully!</p>";
    } else {
        echo "<p style='text-align:center;color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
</body>
</html>
