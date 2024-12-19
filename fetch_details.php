<?php
header("Content-Type: application/json");

// Database credentials
$servername = "localhost";
$username = "root"; // Change as needed
$password = "admin"; // Change as needed
$dbname = "CofeBeanWebPage"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  http_response_code(500); // Internal server error
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

// Check if the ID parameter exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  http_response_code(400); // Bad request
  echo json_encode(["error" => "Invalid ID"]);
  exit;
}

$id = intval($_GET['id']);

// Fetch the coffee data by ID
$sql = "SELECT id, manufacturer, coffeename, region, roasting, flavornotes, link FROM coffees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo json_encode($result->fetch_assoc());
} else {
  http_response_code(404); // Not found
  echo json_encode(["error" => "Coffee not found"]);
}

$stmt->close();
$conn->close();
?>