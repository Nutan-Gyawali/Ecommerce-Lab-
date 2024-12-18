<?php
// Database connection
$host = 'localhost';
$dbname = 'productmanager';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Call the function to display users
showUsers($conn);

// Close the database connection
$conn->close();


// Function to show all users from the database
function showUsers($conn)
{
    // SQL query to fetch users from the database
    $sql = "SELECT username, first_name, last_name, email, phone FROM users";
    $result = mysqli_query($conn, $sql);

    // Button to toggle user table visibility
    echo "<button id='toggleUsersBtn' onclick='toggleUsers()'>Show/Hide Users</button>";

    // Container for the user table (initially hidden)
    echo "<div id='userTableContainer' style='display:none;'>";

    // Check if there are any users in the database
    if (mysqli_num_rows($result) > 0) {
        // Start creating the HTML table
        echo "<table border='1' style='width:100%; border-collapse: collapse; margin-top:10px;'>";
        echo "<thead>";
        echo "<tr style='background-color:#f2f2f2;'>";
        echo "<th>Username</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Email</th>";
        echo "<th>Phone</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Loop through the results and display each user
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }

    // Close the container div
    echo "</div>";

    // JavaScript to toggle the table visibility
    echo "<script>
    function toggleUsers() {
        var tableContainer = document.getElementById('userTableContainer');
        var toggleBtn = document.getElementById('toggleUsersBtn');
        
        if (tableContainer.style.display === 'none') {
            tableContainer.style.display = 'block';
            toggleBtn.textContent = 'Hide Users';
        } else {
            tableContainer.style.display = 'none';
            toggleBtn.textContent = 'Show Users';
        }
    }
    </script>";
}
