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

//processform.php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

$update = false;
$user_id = 0;
$first_name = "";
$last_name = "";
$username = "";
$password = "";
$email = "";

if (isset($_POST['submit'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]); // Add this line
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    $user_id = $_POST["user_id"];

    // Check if the user ID already exists
    $checkQuery = "SELECT * FROM user WHERE user_id = '$user_id'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // user ID already exists, update the existing record
        $updateQuery = "UPDATE user SET first_name='$first_name', last_name='$last_name', username='$username', password='$password', email='$email' WHERE user_id = '$user_id'";

        $conn->query($updateQuery) or die($conn->error);
        $_SESSION['message'] = "Record has been updated!";
    } else {
        // user ID doesn't exist, insert a new record
        $insertQuery = "INSERT INTO user (user_id, first_name, last_name, username, password, email) VALUES ('$user_id', '$first_name', '$last_name', '$username', '$password', '$email')";
        $conn->query($insertQuery) or die($conn->error);
        $_SESSION['message'] = "Record has been saved!";
    }

    $_SESSION['msg_type'] = "warning";
    header("Location: loginReg.php");
    exit();
}

if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    
    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    
    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['msg_type'] = "danger";
    $stmt->close();
    header("Location: index.php");
    exit();
}

if (isset($_GET['edit_user_id'])) {
    $user_id = $_GET['edit_user_id'];
    $update = true;

    // Retrieve other details
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];
    $username = $_GET['username'];
    $password = $_GET['password'];
    $email = $_GET['email'];

    // Rest of your code for editing...
}
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE user SET first_name='$first_name', last_name='$last_name', password='$password', email='$email' WHERE user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Record has been updated!";
        $_SESSION['msg_type'] = "warning";
        echo "success";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}



// Add this condition to handle the update
if (isset($_POST['submit']) && isset($_GET['edit_user_id'])) {
    $user_id = $_GET['edit_user_id'];
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // Perform the update in the database
    $updateQuery = "UPDATE user SET first_name='$first_name', last_name='$last_name', username='$username', password='$password', email='$email' WHERE user_id = '$user_id'";
    $conn->query($updateQuery) or die($conn->error);

    $_SESSION['message'] = "Record has been updated!";
    $_SESSION['msg_type'] = "warning";
    header("Location: loginReg.php");
    exit();
}


$conn->close();
?>


<?php
//display_users.php
         
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
