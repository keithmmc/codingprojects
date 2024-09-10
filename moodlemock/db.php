<?php 

$host = 'localhost';
$db = 'moodle_php';
$user = 'root'; // change if using a different username
$pass = '';     // change if you have a MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
