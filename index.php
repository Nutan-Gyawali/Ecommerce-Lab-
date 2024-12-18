<!DOCTYPE html>
<html lang="en">
<?php
require_once 'List.php';
?>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        /* Form container styling */
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Form labels */
        form label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        /* Input fields */
        form input[type="text"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        form input[type="text"]:focus,
        form input[type="number"]:focus,
        form select:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Submit button styling */
        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Buttons for listing products and categories */
        button {
            margin: 10px;
            padding: 10px 15px;
            font-size: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Data container styling */
        #dataContainer {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <form method="POST" action="categorydb.php">
        <div class="AddCategory">
            <label>Add Category</label>
            <input type="text" id="Add-Category" name="Add-Category" required>
            <input type="submit" value="Add Category">
        </div>
    </form>

    <form method="POST" action="addProduct.php">
        <div class="AddProduct">
            <label>Add Product</label>
            <input type="text" name="Product-Name" placeholder="New Product" required>

            <label>Price</label>
            <input type="number" name="Product-Price" placeholder="Price in NRs" required>

            <label>Description</label>
            <input type="text" name="Product-Description" placeholder="Product Description" required>

            <label>Choose Category</label>
            <select name="Category-Name" required>
                <option value="" disabled selected>Select a category</option>
                <?php
                $conn = new mysqli($host, $user, $password, $dbname);
                $sql = "SELECT name FROM category";
                $result = mysqli_query($conn, $sql);
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

    <button onclick="ShowProd()">List Products</button>
    <button onclick="ShowCategory()">List Categories</button>

    <div id="dataContainer" class="data-container"></div>

</body>

<script>
    function ShowProd() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'List.php?action=products', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('dataContainer').innerHTML = xhr.responseText;
                document.getElementById('dataContainer').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        };
        xhr.send();
    }

    function ShowCategory() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'List.php?action=categories', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('dataContainer').innerHTML = xhr.responseText;
                document.getElementById('dataContainer').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        };
        xhr.send();
    }
</script>

</html>