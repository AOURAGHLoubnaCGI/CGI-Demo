<?php
// Set the content type to application/json
header('Content-Type: application/json');

$servername = "localhost";
$username = "id22281554_loubna";
$password = "4K4|e@Q9tQ3y";
$dbname = "id22281554_loubna";

$conn = new mysqli($servername, $username, $password, $dbname);

$method = $_SERVER['REQUEST_METHOD'];

// GET
if ($method == 'GET') {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    $conn->close();

    echo json_encode($users);
}
// POST
else if ($method == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate the input data
    if (!isset($input['users_group_id'], $input['name'], $input['email'], $input['password'], $input['active'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        exit;
    }

    $users_group_id = $input['users_group_id'];
    $name = $input['name'];
    $photo = isset($input['photo']) ? $input['photo'] : null;
    $email = $input['email'];
    $culture = isset($input['culture']) ? $input['culture'] : null;
    $password = $input['password'];
    $active = $input['active'];
    $skin = isset($input['skin']) ? $input['skin'] : null;

    // Check connection
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['message' => 'Database connection failed', 'error' => $conn->connect_error]);
        exit;
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (users_group_id, name, photo, email, culture, password, active, skin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to prepare statement', 'error' => $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("isssssib", $users_group_id, $name, $photo, $email, $culture, $password, $active, $skin);

    // Execute statement
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;

        $new_user = [
            'id' => $last_id,
            'name' => $name,
            'email' => $email,
        ];
    
        http_response_code(201);
        echo json_encode(['message' => 'User added successfully', 'user' => $new_user]);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to add user', 'error' => $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
else {
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
}

?>
