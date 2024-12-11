<?php
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

// Fetch data from the coffees table
$sql = "SELECT id, manufacturer, coffeename, region FROM coffees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $coffees = [];
  while ($row = $result->fetch_assoc()) {
    $coffees[] = $row;
  }
  echo json_encode($coffees);
} else {
  echo json_encode([]);
}

$conn->close();
?>