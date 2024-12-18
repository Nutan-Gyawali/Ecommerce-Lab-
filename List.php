<?php
$host = 'localhost';
$dbname = 'productmanager';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function ShowProd($conn)
{
    // Prepare a SQL query to join product with category to get category name
    $sql = "SELECT p.id, p.name, c.name AS category_name, p.price, p.description 
            FROM product p
            LEFT JOIN category c ON p.category_id = c.id";

    $result = mysqli_query($conn, $sql);

    // Start creating the toggle button and table container
    echo "<button id='toggleProductsBtn' onclick='toggleProducts()'>Show/Hide Products</button>";
    echo "<div id='productsTableContainer' style='display:none;'>";

    // Check if there are any products
    if (mysqli_num_rows($result) > 0) {
        // Start creating the HTML table
        echo "<table border='1' style='width:100%; border-collapse: collapse; margin-top:10px;'>";
        echo "<thead>";
        echo "<tr style='background-color:#f2f2f2;'>";
        echo "<th>ID</th>";
        echo "<th>Product Name</th>";
        echo "<th>Category</th>";
        echo "<th>Price</th>";
        echo "<th>Description</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Loop through the results and display each product
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
            echo "<td>NRs " . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No products found.</p>";
    }

    // Close the container div
    echo "</div>";

    // Add JavaScript for toggling
    echo "<script>
    function toggleProducts() {
        var tableContainer = document.getElementById('productsTableContainer');
        var toggleBtn = document.getElementById('toggleProductsBtn');
        
        if (tableContainer.style.display === 'none') {
            tableContainer.style.display = 'block';
            toggleBtn.textContent = 'Hide Products';
        } else {
            tableContainer.style.display = 'none';
            toggleBtn.textContent = 'Show Products';
        }
    }
    </script>";
}

// If you want to call the function directly when this file is loaded
ShowProd($conn);
$conn->close();
