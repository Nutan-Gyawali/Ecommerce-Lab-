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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $Pname = trim(htmlspecialchars($_POST['Product-Name'] ?? ''));
    $Price = trim(htmlspecialchars($_POST['Product-Price'] ?? ''));
    $Description = trim(htmlspecialchars($_POST['Product-Description'] ?? ''));
    $category = trim(htmlspecialchars($_POST['Category-Name'] ?? ''));

    // Comprehensive input validation
    $errors = [];

    if (empty($Pname)) {
        $errors[] = "Product name is required.";
    }

    if (empty($Price) || !is_numeric($Price) || $Price <= 0) {
        $errors[] = "Invalid price. Must be a positive number.";
    }

    if (empty($Description)) {
        $errors[] = "Product description is required.";
    }

    if (empty($category)) {
        $errors[] = "Category selection is required.";
    }

    // If there are validation errors, stop and display them
    if (!empty($errors)) {
        echo "Errors found:\n";
        foreach ($errors as $error) {
            echo "- " . $error . "\n";
        }
        exit;
    }

    // Prepared statement to find category ID
    $stmt = $conn->prepare("SELECT id FROM category WHERE name = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Selected category does not exist.";
        exit;
    }

    $categoryRow = $result->fetch_assoc();
    $categoryid = $categoryRow['id'];

    // Prepare insert statement for product
    $insert_stmt = $conn->prepare("INSERT INTO product(name, category_id, price, description) VALUES ( ?, ?, ?, ?)");

    // 'ids' means: integer, decimal, string
    $insert_stmt->bind_param("siss", $Pname, $categoryid, $Price, $Description);

    // Execute and handle the result
    if ($insert_stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error adding product: " . $insert_stmt->error;
    }

    // Close statements
    $stmt->close();
    $insert_stmt->close();
}

// Close connection
$conn->close();
