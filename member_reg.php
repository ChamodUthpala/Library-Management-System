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



$update = false;
$member_id = 0;
$first_name = "";
$last_name = "";
$birthday = "";
$email = "";

if (isset($_POST['submit'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $birthday = $_POST["birthday"];
    $email = $_POST["email"];

    $member_id = $_POST["member_id"];

    // Check if the member ID already exists
    $checkQuery = "SELECT * FROM member WHERE member_id = '$member_id'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Member ID already exists, update the existing record
        $updateQuery = "UPDATE member SET first_name='$first_name', last_name='$last_name', birthday='$birthday', email='$email' WHERE member_id = '$member_id'";
        $conn->query($updateQuery) or die($conn->error);
        $_SESSION['message'] = "Record has been updated!";
    } else {
        // Member ID doesn't exist, insert a new record
        $insertQuery = "INSERT INTO member (member_id, first_name, last_name, birthday, email) VALUES ('$member_id', '$first_name', '$last_name', '$birthday', '$email')";
        $conn->query($insertQuery) or die($conn->error);
        $_SESSION['message'] = "Record has been saved!";
    }

    $_SESSION['msg_type'] = "warning";
    header("Location: member_reg.html");
    exit();
}

if (isset($_GET['delete_member_id'])) {
    $member_id = $_GET['delete_member_id'];
    
    // To avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM member WHERE member_id = ?");
    $stmt->bind_param("s", $member_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['msg_type'] = "danger";
    $stmt->close();
    header("Location: member_reg.html");
}



if (isset($_GET['edit_member_id'])) {
    $u_id = $_GET['edit_member_id'];
    $update = true;

    // Retrieve other details
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];
    $birthday = $_GET['birthday'];
    $email = $_GET['email'];
}




if (isset($_POST['update'])) {
    $member_id = $_POST['member_id'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthday = $_POST['birthday'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("UPDATE member SET first_name=?, last_name=?, birthday=?, email=? WHERE member_id = ?");
    $stmt->bind_param("sssss", $first_name, $last_name, $birthday, $email, $member_id);
    $stmt->execute();

    $_SESSION['message'] = "Record has been updated!";
    $_SESSION['msg_type'] = "warning";

    $stmt->close();
    header("Location: member_reg.html");
    exit();
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