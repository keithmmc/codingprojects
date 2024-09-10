<?php 

include 'db.php'; 
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_post['register'])){
    $username = trim($_POST['username']); 
    $email = trim($_POST['email']); 
    $password = trim($_POST['password']);

    if(empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username or email already exists!');</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
        }
     
    }
}