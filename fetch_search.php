<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "cofebeanwebpage"; // Adjust to your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Fetch unique dropdown options
  $dropdowns = ["manufacturers" => "Manufacturer", "regions" => "Region", "roastings" => "Roasting", "flavorNotes" => "FlavorNotes"];
  $response = [];

  foreach ($dropdowns as $key => $column) {
    $sql = "SELECT DISTINCT `$column` FROM `coffees` ORDER BY `$column` ASC";
    $result = $conn->query($sql);

    if ($result) {
      $response[$key] = [];
      while ($row = $result->fetch_assoc()) {
        $response[$key][] = $row[$column];
      }
    } else {
      http_response_code(500);
      echo json_encode(["error" => "Failed to fetch $column options."]);
      exit;
    }
  }

  echo json_encode($response);
  exit;

} else {
  http_response_code(405);
  echo json_encode(["error" => "Method not allowed"]);
}

$conn->close();
exit;
?>