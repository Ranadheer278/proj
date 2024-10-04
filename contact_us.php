<?php
// Database connection details
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_name = "tour";

// Create connection
$conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form inputs
$name = htmlspecialchars($_POST['name']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars($_POST['phone']);
$subject = htmlspecialchars($_POST['subject']);
$message = htmlspecialchars($_POST['message']);

// Check for valid email
if (!$email) {
    die("Invalid email address");
}

// Prepare an SQL statement
$stmt = $conn->prepare("INSERT INTO contact (Name, Email, Phone, Subject, Message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

// Execute the statement
if ($stmt->execute()) {
    echo "We will contact you soon<br><br>";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement
$stmt->close();

// Display data from the 'contact' table
$sql = "SELECT * FROM contact";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Submitted Contacts:</h2>";
    echo "<table><tr><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["Name"]. "</td><td>" . $row["Email"]. "</td><td>" . $row["Phone"]. "</td><td>" . $row["Subject"]. "</td><td>" . $row["Message"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No records found";
}

// Close the connection
$conn->close();
?>

