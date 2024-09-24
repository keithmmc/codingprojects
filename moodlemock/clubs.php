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
    echo "you are already a member of this club!!";
} else {
    $check_club = $conn->prepare("SELECT * FROM clubs WHERE id = :club_id");
    $check_club->bindParam(':club_id', $club_id);
    $check_club->execute();
    $club = $check_club->fetch(PDO::FETCH_ASSOC);
    if ($club['current_members'] >= $club['max_members']){
        echo "The club is full. you cannot join";

    }else{
        $stmt = $conn->prepare("INSERT INTO club_memberships (user_id, club_id) VALUES (:user_id, :club_id)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':club_id', $club_id);
            $stmt->execute();
            $update_club = $conn->prepare("UPDATE clubs SET current_members = current_members + 1 WHERE id = :club_id");
            $update_club->bindParam(':club_id', $club_id);
            $update_club->execute();

            echo "You have successfully joined the club!";
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Clubs</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Clubs</h2>
    <div class="club-list">
        <?php foreach ($clubs as $club): ?>
            <div class="club-item">
                <h3><?= htmlspecialchars($club['name']) ?></h3>
                <p><?= htmlspecialchars($club['description']) ?></p>
                <p>Members: <?= htmlspecialchars($club['current_members']) ?> / <?= htmlspecialchars($club['max_members']) ?></p>
                <form method="POST">
                    <input type="hidden" name="club_id" value="<?= htmlspecialchars($club['id']) ?>">
                    <button type="submit">Join Club</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="../user/dashboard.php">Back to Dashboard</a>
</body>
</html>
    


    

