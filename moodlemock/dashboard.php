<?php 
require '../db.php';
require '../config.php';

if (!isLoggedIn()) {
    redirect('../user/login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch clubs the user has joined
$stmt = $conn->prepare("
    SELECT c.* 
    FROM club_memberships cm
    JOIN clubs c ON cm.club_id = c.id
    WHERE cm.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$joined_clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch available clubs
$stmt = $conn->prepare("
    SELECT * 
    FROM clubs
    WHERE current_members < max_members 
    AND id NOT IN (
        SELECT club_id 
        FROM club_memberships 
        WHERE user_id = :user_id
    )
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$available_clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch books the user has reserved
$stmt = $conn->prepare("
    SELECT b.*, br.reservation_date 
    FROM book_reservations br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$reserved_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all books available for reservation
$stmt = $conn->prepare("SELECT * FROM books");
$stmt->execute();
$available_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<h2>Welcome to Your Dashboard</h2>

<h3>Clubs You've Joined</h3>
<div class="club-list">
    <?php if (count($joined_clubs) > 0): ?>
        <?php foreach ($joined_clubs as $club): ?>
            <div class="club-item">
                <h4><?= htmlspecialchars($club['name']) ?></h4>
                <p><?= htmlspecialchars($club['description']) ?></p>
                <p>Members: <?= htmlspecialchars($club['current_members']) ?> / <?= htmlspecialchars($club['max_members']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't joined any clubs yet.</p>
    <?php endif; ?>
</div>

<h3>Available Clubs to Join</h3>
<div class="club-list">
    <?php foreach ($available_clubs as $club): ?>
        <div class="club-item">
            <h4><?= htmlspecialchars($club['name']) ?></h4>
            <p><?= htmlspecialchars($club['description']) ?></p>
            <p>Members: <?= htmlspecialchars($club['current_members']) ?> / <?= htmlspecialchars($club['max_members']) ?></p>
            <form method="POST" action="../clubs.php">
                <input type="hidden" name="club_id" value="<?= htmlspecialchars($club['id']) ?>">
                <button type="submit">Join Club</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<h3>Your Reserved Books</h3>
<div class="book-list">
    <?php if (count($reserved_books) > 0): ?>
        <?php foreach ($reserved_books as $book): ?>
            <div class="book-item">
                <h4><?= htmlspecialchars($book['title']) ?></h4>
                <p>Reserved on: <?= htmlspecialchars($book['reservation_date']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You haven't reserved any books yet.</p>
    <?php endif; ?>
</div>

<h3>Available Books for Reservation</h3>
<div class="book-list">
    <?php foreach ($available_books as $book): ?>
        <div class="book-item">
            <h4><?= htmlspecialchars($book['title']) ?></h4>
            <form method="POST" action="../lib.php">
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
                <button type="submit">Reserve Book</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<a href="../user/logout.php">Sign Out</a>

</body>
</html>