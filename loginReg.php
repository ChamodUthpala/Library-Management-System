<?php
// this is db_config
$servername = "localhost";
$username = "root";
$password = "";
$database = "library_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

include 'db_config.php';

$result = $conn->query("SELECT * FROM user");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["password"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
         
        echo "<td>";
        echo "<button class='edit' onclick='editUser(\"$row[user_id]\", \"$row[first_name]\", \"$row[last_name]\", \"$row[username]\", \"$row[password]\",\"$row[email]\")'>Edit</button>&nbsp";
        echo "<button class='delete' onclick='deleteUserr(\"" . $row['user_id'] . "\")'>Delete</button>";
        echo "</td>";
        echo "</tr>";
    }
}

 
?>

 

