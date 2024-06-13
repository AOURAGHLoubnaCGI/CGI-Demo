<?php
// Set the content type to application/json
header('Content-Type: application/json');

$servername = "localhost";
$username = "id22281554_loubna";
$password = "4K4|e@Q9tQ3y";
$dbname = "id22281554_loubna";

$conn = new mysqli($servername, $username, $password, $dbname);

$method = $_SERVER['REQUEST_METHOD'];

// Get user ID from the query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid user ID']);
    exit;
}

// Prepare SQL statement for deletion
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to prepare statement', 'error' => $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param("i", $id);

// Execute statement
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(['message' => 'User deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'User not found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to delete user', 'error' => $stmt->error]);
}

// Close statement
$stmt->close();

?>
