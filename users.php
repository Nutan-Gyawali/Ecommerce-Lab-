<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript function to validate the form
        function validateForm() {
            var username = document.forms["userForm"]["name"].value;
            var password = document.forms["userForm"]["password"].value;
            var email = document.forms["userForm"]["email"].value;
            var fname = document.forms["userForm"]["fname"].value;
            var lname = document.forms["userForm"]["lname"].value;
            var phone = document.forms["userForm"]["pnumber"].value;

            // Check if any field is empty
            if (username == "" || password == "" || email == "" || fname == "" || lname == "" || phone == "") {
                alert("All fields must be filled out!");
                return false;
            }

            // Validate email format
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!email.match(emailPattern)) {
                alert("Please enter a valid email address!");
                return false;
            }

            // Validate password length
            if (password.length < 6) {
                alert("Password must be at least 6 characters long!");
                return false;
            }

            // Validate phone number format (only numbers)
            var phonePattern = /^[0-9]{10}$/;
            if (!phone.match(phonePattern)) {
                alert("Please enter a valid 10-digit phone number!");
                return false;
            }

            return true; // Form is valid, proceed with submission
        }

        // Function to toggle visibility of the user table
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
    </script>
</head>

<body>

    <!-- User Registration Form -->
    <div class="form-container">
        <h2>Register User</h2>
        <form name="userForm" method="POST" onsubmit="return validateForm()">
            USERNAME:
            <input type="text" name="name" placeholder="Enter your username">
            PASSWORD:
            <input type="password" name="password" placeholder="Create a password">
            EMAIL:
            <input type="email" name="email" placeholder="Enter your email">
            FIRST NAME:
            <input type="text" name="fname" placeholder="Firstname">
            LAST NAME:
            <input type="text" name="lname" placeholder="Lastname">
            PHONE NUMBER:
            <input type="number" name="pnumber" placeholder="Enter your phone number">
            <input type="submit" name="submit" value="Register">
        </form>
    </div>

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

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $username = $_POST['name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['pnumber'];

        // Validate that fields are not empty
        if (empty($username) || empty($password) || empty($email) || empty($fname) || empty($lname) || empty($phone)) {
            echo "All fields are required!";
        } else {
            // Validate email format (Server-side)
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format!";
            } else {
                // Validate password length
                if (strlen($password) < 6) {
                    echo "Password must be at least 6 characters long!";
                } else {
                    // Check if the username already exists in the database
                    $checkQuery = "SELECT * FROM users WHERE username = ?";
                    $stmt = $conn->prepare($checkQuery);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        echo "Username already taken!";
                    } else {
                        // Hash password for security
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Insert user into the database
                        $stmt = $conn->prepare("INSERT INTO users (username, password, email, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssss", $username, $hashed_password, $email, $fname, $lname, $phone);

                        if ($stmt->execute()) {
                            echo "User registered successfully!";
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                    }
                    $stmt->close();
                }
            }
        }
    }

    // Function to display users
    function showUsers($conn)
    {
        $sql = "SELECT username, first_name, last_name, email, phone FROM users";
        $result = mysqli_query($conn, $sql);

        // Button to toggle user table visibility
        echo "<button id='toggleUsersBtn' onclick='toggleUsers()'>Show/Hide Users</button>";

        // Container for the user table (initially hidden)
        echo "<div id='userTableContainer' style='display:none;'>";

        // Check if there are any users in the database
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<thead><tr>";
            echo "<th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th>";
            echo "</tr></thead><tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No users found.</p>";
        }

        // Close the container div
        echo "</div>";
    }

    // Call function to show users after registration
    showUsers($conn);

    // Close the database connection
    $conn->close();
    ?>

</body>

</html>