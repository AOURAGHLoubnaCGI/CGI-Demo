<?php
// Set the content type to application/json
header('Content-Type: application/json');

$servername = "localhost";
$username = "id22281554_loubna";
$password = "4K4|e@Q9tQ3y";
$dbname = "id22281554_loubna";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$conn->close();

echo json_encode($tasks);
?>
