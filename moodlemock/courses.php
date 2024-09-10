<?php 

include ('db.php');
include ('config.php'); 

if (!isLoggedIn()){
    redirect('/.login.php');
}


$courseid = $_get['id'];
$stmt = $conn->prepare("SELECT * FROM course where id = :id"); 
$stmt->bindParam(':id', $course_id); 
$stmt->execute(); 
$course = $stmt ->fetch(PDO::FETCH_ASSOC);

if ($_server['REQUEST_METHOD'] == 'POST'){
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, course_name) VALUES (:user_id, :course_id, :course_name)");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':course_id', $course_id); 
    $stmt->bindParam(':course_name', $course_name); 
    $stmt->execute(); 
    echo "<script>alert('You are now enrolled in the course!');</script>";
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="container">
        <!-- Filter/Search Section -->
        <div class="filter-area">
            <input type="text" id="courseSearch" placeholder="Search for courses...">
            <select id="courseCategory">
                <option value="all">All Categories</option>
                <option value="programming">Programming</option>
                <option value="design">Design</option>
                <option value="data-science">Data Science</option>
            </select>
            <button onclick="filterCourses()">Search</button>
        </div>

        <!-- Courses List -->
        <div class="course-wrapper">
            <!-- Example Course Items -->
            <div class="course-item" data-category="programming">
                <img src="path/to/image1.jpg" alt="Course Image">
                <h3>Introduction to Python</h3>
                <p>Learn the basics of Python programming with this beginner-friendly course.</p>
                <a href="course_details.php?id=1" class="enroll-btn">Enroll Now</a>
            </div>

            <div class="course-item" data-category="design">
                <img src="path/to/image2.jpg" alt="Course Image">
                <h3>Web Design Fundamentals</h3>
                <p>Master the principles of modern web design with HTML and CSS.</p>
                <a href="course_details.php?id=2" class="enroll-btn">Enroll Now</a>
            </div>

            <div class="course-item" data-category="data-science">
                <img src="path/to/image3.jpg" alt="Course Image">
                <h3>Data Analysis with Python</h3>
                <p>Learn how to analyze data and build models with Python.</p>
                <a href="course_details.php?id=3" class="enroll-btn">Enroll Now</a>
            </div>

            <!-- Add more course items as needed -->
        </div>
    </div>

    <script src="/assets/js/courses.js"></script>
</body>
</html>


