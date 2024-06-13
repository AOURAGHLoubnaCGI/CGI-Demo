<?php
// Set the content type to application/json
header('Content-Type: application/json');

$servername = "localhost";
$username = "id22281554_loubna";
$password = "4K4|e@Q9tQ3y";
$dbname = "id22281554_loubna";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);
    
    $projects = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }
    
    $conn->close();
    
    echo json_encode($projects);
}
else if ($method == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate the input data
    if (!isset($input['projects_status_id'], $input['projects_types_id'], $input['created_by'], $input['name'], $input['created_at'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        exit;
    }

    $projects_status_id = $input['projects_status_id'];
    $projects_types_id = $input['projects_types_id'];
    $created_by = $input['created_by'];
    $name = $input['name'];
    $description = isset($input['description']) ? $input['description'] : null;
    $team = isset($input['team']) ? $input['team'] : null;
    $created_at = $input['created_at'];
    $order_tasks_by = isset($input['order_tasks_by']) ? $input['order_tasks_by'] : null;

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO projects (projects_status_id, projects_types_id, created_by, name, description, team, created_at, order_tasks_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to prepare statement', 'error' => $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("iiisssss", $projects_status_id, $projects_types_id, $created_by, $name, $description, $team, $created_at, $order_tasks_by);

    // Execute statement
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Project added successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to add project', 'error' => $stmt->error]);
    }

    // Close statement
    $stmt->close();
}

?>
