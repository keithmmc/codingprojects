<?php 
include 'db.php';
include 'config.php'; 

if(!isLoggedIn())

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>moodle</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<div class="header-class">
    <nav>
        <ul>
            <a href="signin.php"><</a>
            <a href="courses.php"></a>
            <a href="dashboard.php"></a>
            <a href="profile.php"></a>
            <a href="signout.php"></a>
        </ul>
    </nav>
</div>
</body>
</html>


