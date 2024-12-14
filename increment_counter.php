<?php
header("Content-Type: application/json"); // JSON response
header("Cache-Control: no-cache, no-store, must-revalidate"); // Prevent caching
header("Pragma: no-cache");
header("Expires: 0");

// Database configuration
$host = "localhost";
$username = "root";
$password = "admin";
$dbname = "cofebeanwebpage";

try {
  // Establish a database connection
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Process POST request
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (!isset($_POST['coffee_id']) || !is_numeric($_POST['coffee_id'])) {
      throw new Exception('Invalid coffee ID.');
    }

    $coffeeId = (int) $_POST['coffee_id'];

    // Increment the counter for the coffee ID
    $stmt = $pdo->prepare("UPDATE coffees SET counter = counter + 1 WHERE id = :id");
    $stmt->execute([':id' => $coffeeId]);

    if ($stmt->rowCount() > 0) {
      // Redirect to the desired URL after increment
      header("Location: https://casinomocca.com/");
      exit;
    } else {
      throw new Exception('Coffee ID not found.');
    }
  } else {
    throw new Exception('Invalid request method.');
  }
} catch (Exception $e) {
  // Handle errors gracefully
  error_log($e->getMessage());
  echo "An error occurred: " . htmlspecialchars($e->getMessage());
}