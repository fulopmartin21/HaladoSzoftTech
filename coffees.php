<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "cofebeanwebpage";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$manufacturer = $conn->real_escape_string($_POST['manufacturer'] ?? '');
$region = $conn->real_escape_string($_POST['region'] ?? '');
$roasting = $conn->real_escape_string($_POST['roasting'] ?? '');
$flavorNotes = $conn->real_escape_string($_POST['flavorNotes'] ?? '');

$conditions = [];
if ($manufacturer)
    $conditions[] = "Manufacturer = '$manufacturer'";
if ($region)
    $conditions[] = "Region = '$region'";
if ($roasting)
    $conditions[] = "Roasting = '$roasting'";
if ($flavorNotes)
    $conditions[] = "FlavorNotes LIKE '%$flavorNotes%'";

$whereClause = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';
$sql = "SELECT * FROM `coffees` $whereClause";

$result = $conn->query($sql);

if ($result) {
    $response = [];
    while ($row = $result->fetch_assoc()) {
        $response[] = [
            "id" => $row['ID'],
            "coffeeName" => $row['CoffeeName'],
            "manufacturer" => $row['Manufacturer'],
            "region" => $row['Region'],
            "roasting" => $row['Roasting'],
            "flavorNotes" => $row['FlavorNotes']
        ];
    }
    echo json_encode($response);
    exit;
}