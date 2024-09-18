<?php 

include 'db.php';
include 'config.php'; 

if (!isLoggedIn()) {
    redirect('../user/login.php');
} 

$stmt = $conn->prepare("SELECT * FROM clubs");
$stmt->execute();
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $club_id = $_POST['club_id'];
    $user_id = $_SESSION['user_id'];
}

$check_memebership = $conn->prepare("SELECT * FROM club_memberships WHERE user_id = :user_id AND club_id = :club_id");
$check_membership->bindParam(':user_id', $user_id);
$check_membership->bindParam(':club_id', $club_id);
$check_membership->execute(); 
if($check_membership->rowCount() > 0) {
    echo "you are already a member of this club!!"
} else {
    $check_club = $conn->prepare("SELECT * FROM clubs WHERE id = :club_id");
    $check_club->bindParam(':club_id', $club_id);
    $check_club->execute();
    $club = $check_club->fetch(PDO::FETCH_ASSOC);

    
} 
