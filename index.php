<!DOCTYPE html>
<html lang="en">
<?php
require_once 'List.php';  // Make sure the path is correct
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script>
        function Showprod() {
            // Use AJAX to load products dynamically
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'list.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('productsContainer').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function Showcategory() {
            // Implement category listing similar to Showprod if needed
            alert('Category listing not implemented');
        }
    </script>
</head>

<body>
    <form method="POST" action="categorydb.php">
        <div class="AddCategory">
            <label>Add Category </label>
            <input type="text" id="Add-Category" name="Add-Category">
            <input type="submit" value="Add Category">
        </div>
    </form>

    <form method="POST" action="addProduct.php">
        <div class="AddProduct">
            <label>Add Product </label>
            <input type="text" name="Product-Name" placeholder="New Product" required>

            <label> Price </label>
            <input type="number" name="Product-Price" placeholder="Price in NRs" required>

            <label>Description </label>
            <input type="text" name="Product-Description" placeholder="Product Description" required>

            <label>Choose Category</label>
            <select name="Category-Name" required>
                <option value="" disabled selected>Select a category</option>
                <?php
                // PHP code to fetch categories from the database
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

                // Query to get all categories
                $sql = "SELECT name FROM category";
                $result = mysqli_query($conn, $sql);

                // Populate dropdown with categories
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No categories available</option>';
                }

                $conn->close();
                ?>
            </select>

            <input type="submit" value="Add Product">
        </div>
    </form>

    <!-- <button onclick="Showprod()">Show Product</button> -->
    <button onclick="Showcategory()">List all Categories</button>

    <!-- Container to dynamically load products -->
    <div id="productsContainer"></div>
</body>

</html>