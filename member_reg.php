<?php
//database configuraion
$servername = "localhost";
$username = "root";
$password = "";
$database = "library_test";

// Creating the connection
$conn = new mysqli($servername, $username, $password, $database);

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//displaying members
$result = $conn->query("SELECT * FROM member");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["member_id"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["birthday"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>";
        echo "<button class='edit' onclick='editMember(\"$row[member_id]\", \"$row[first_name]\", \"$row[last_name]\", \"$row[birthday]\", \"$row[email]\")'>Edit</button>&nbsp&nbsp";
        echo "<button class='delete' onclick='deleteMember(\"" . $row['member_id'] . "\")'>Delete</button>";
        echo "</td>";
        echo "</tr>";
    }
}

$conn->close();
?>