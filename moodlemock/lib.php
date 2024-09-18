<?php 
include 'db.php';  // Database connection
include 'config.php';  // Configuration settings
session_start();

// Ensure the user is logged in
if (!isLoggedIn()){
    redirect('login.php');
}

// Check if we are viewing a specific book
$bookid = isset($_GET['id']) ? $_GET['id'] : null;

if ($bookid) {
    // Fetch the book details if a book ID is present
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->bindParam(':id', $bookid);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the book is already reserved
    $reservedStmt = $conn->prepare("SELECT * FROM reservations WHERE book_id = :book_id AND status = 'reserved'");
    $reservedStmt->bindParam(':book_id', $bookid);
    $reservedStmt->execute();
    $reservation = $reservedStmt->fetch(PDO::FETCH_ASSOC);

    // Handle book reservation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
        $userid = $_SESSION['user_id']; // Assuming the user is logged in and session contains user ID

        // If the book is already reserved, show an error message
        if ($reservation) {
            echo "<script>alert('This book is already reserved!');</script>";
        } else {
            // Reserve the book
            $reserveStmt = $conn->prepare("INSERT INTO reservations (user_id, book_id, status, reserved_at) VALUES (:user_id, :book_id, 'reserved', NOW())");
            $reserveStmt->bindParam(':user_id', $userid);
            $reserveStmt->bindParam(':book_id', $bookid);
            $reserveStmt->execute();

            // Show success message
            echo "<script>alert('You have successfully reserved the book!'); window.location.href = 'dashboard.php';</script>";
        }
    }
}

// Handle adding a new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $bookName = $_POST['book_name'];
    $author = $_POST['author'];
    $description = $_POST['description'];

    // Prepare the SQL statement to insert the new book
    $stmt = $conn->prepare("INSERT INTO books (title, author, description) VALUES (:book_name, :author, :description)");
    
    // Bind the parameters to the SQL query
    $stmt->bindParam(':book_name', $bookName);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':description', $description);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Book added successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding the book. Please try again.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($book['title']) ? htmlspecialchars($book['title']) : 'Library'; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS here -->
</head>
<body>

<?php if (isset($book)): ?>
    <!-- Book details and reservation section -->
    <div class="book-details">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($book['description']); ?></p>

        <?php if ($reservation): ?>
            <p style="color: red;">This book is currently reserved by another user.</p>
        <?php else: ?>
            <form method="POST">
                <button type="submit" name="reserve" class="reserve-btn">Reserve Book</button>
            </form>
        <?php endif; ?>
    </div>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

<?php else: ?>
    <!-- Add book form -->
    <div class="add-book-form">
        <h2>Add a New Book</h2>
        <form method="POST">
            <div>
                <label for="book_name">Book Name:</label>
                <input type="text" id="book_name" name="book_name" required>
            </div>
            <div>
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <button type="submit" name="add_book" class="submit-btn">Add Book</button>
        </form>
    </div>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
<?php endif; ?>



</body>
</html>
