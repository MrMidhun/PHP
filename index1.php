<?php

$dataFile = 'users.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $submissionDate = date('Y-m-d'); // Add timestamp for user submissions

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
    } else {
        // Check if the data file exists, if not, initialize an empty array
        if (file_exists($dataFile)) {
            $jsonData = file_get_contents($dataFile);
            $users = json_decode($jsonData, true);
        } else {
            $users = [];
        }

        // Check for duplicate emails
        $emailExists = false;
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            echo "<script>alert('Email already exists! Please use a different one.');</script>";
        } else {
            // Add new user with a submission date
            $users[] = ['name' => $name, 'email' => $email, 'date' => $submissionDate];

            // Write to the JSON file with pretty print for better readability
            file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT));

            echo "<script>alert('User data submitted successfully!');</script>";
        }
    }
}

// Display users data if file exists
$usersData = '';
if (file_exists($dataFile)) {
    $jsonData = file_get_contents($dataFile);
    $users = json_decode($jsonData, true);

    if (!empty($users)) {
        $usersData .= "<h3>Users List:</h3><table border='1' cellspacing='0' cellpadding='10'>";
        $usersData .= "<tr><th>Name</th><th>Email</th><th>Date Submitted</th></tr>";
        foreach ($users as $user) {
            $submissionDate = isset($user['date']) ? $user['date'] : 'N/A'; // Check if 'date' exists
            $usersData .= "<tr>";
            $usersData .= "<td>" . $user['name'] . "</td>";
            $usersData .= "<td>" . $user['email'] . "</td>";
            $usersData .= "<td>" . $submissionDate . "</td>";
            $usersData .= "</tr>";
        }
        $usersData .= "</table>";
    } else {
        $usersData .= "<p>No users available.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced PHP Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        #userData {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        #toggleButton {
            margin-top: 10px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #toggleButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Register User</h2>
    
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <input type="submit" value="Submit">
    </form>
    
    <button id="toggleButton">Display Data</button>

    <div id="userData" style="display:none;">
        <?php echo $usersData; ?>
    </div>
</div>

<script>
    document.getElementById("toggleButton").addEventListener("click", function() {
        var userDataDiv = document.getElementById("userData");
        if (userDataDiv.style.display === "none" || userDataDiv.style.display === "") {
            userDataDiv.style.display = "block";  
            this.textContent = "Hide Data";  
        } else {
            userDataDiv.style.display = "none";  
            this.textContent = "Display Data";  
        }
    });
</script>

</body>
</html>
