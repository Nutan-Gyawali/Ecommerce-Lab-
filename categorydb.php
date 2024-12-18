<?php
$host = 'localhost';
$dbname = 'productmanager';
$user = 'root';
$password = '';

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission if a new category is being added
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Add-Category'])) {
    $newcategory = trim(htmlspecialchars($_POST['Add-Category']));

    // Check if category is empty
    if (empty($newcategory)) {
        echo "Category name cannot be empty.";
        exit;
    }

    // Prepare a statement to prevent SQL injection and check for existing category
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM category WHERE name = ?");
    $stmt->bind_param("s", $newcategory);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "Category already exists";
    } else {
        // Prepare insert statement
        $insert_stmt = $conn->prepare("INSERT INTO category (name) VALUES (?)");
        $insert_stmt->bind_param("s", $newcategory);

        // Execute the insert
        if ($insert_stmt->execute()) {
            echo "New category added successfully";
        } else {
            echo "Error: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    }

    $stmt->close();
}

$conn->close();
