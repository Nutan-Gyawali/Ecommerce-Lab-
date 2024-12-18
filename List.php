<?php
$host = 'localhost';
$dbname = 'productmanager';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'products') {
    ShowProd($conn);
} elseif ($action === 'categories') {
    ShowCategory($conn);
}

function ShowProd($conn)
{
    $sql = "SELECT p.id, p.name, c.name AS category_name, p.price, p.description 
            FROM product p
            LEFT JOIN category c ON p.category_id = c.id";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Product List</h3>";
        echo "<table border='1' style='width:100%; border-collapse: collapse; margin-top:10px;'>";
        echo "<thead><tr>";
        echo "<th>ID</th><th>Product Name</th><th>Category</th><th>Price</th><th>Description</th>";
        echo "</tr></thead>";
        echo "<tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
            echo "<td>NRs " . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No products found.</p>";
    }
}

function ShowCategory($conn)
{
    $sql = "SELECT id, name FROM category";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Category List</h3>";
        echo "<table border='1' style='width:100%; border-collapse: collapse; margin-top:10px;'>";
        echo "<thead><tr><th>ID</th><th>Category Name</th></tr></thead>";
        echo "<tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No categories found.</p>";
    }
}

$conn->close();
