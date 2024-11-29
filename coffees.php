<?php
// Kapcsolódás az adatbázishoz
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "cofebeanwebpage";

// Csatlakozás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizzük, hogy sikerült-e a kapcsolat
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Paraméterek ellenőrzése (GET kérésből), ha nem létezik, akkor null-ra állítjuk
$manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : null;
$coffeename = isset($_GET['coffeename']) ? $_GET['coffeename'] : null;
$region = isset($_GET['region']) ? $_GET['region'] : null;
$flavors = isset($_GET['flavors']) ? $_GET['flavors'] : null;
$roasting = isset($_GET['roasting']) ? $_GET['roasting'] : null;

// Az alap SQL lekérdezés
$sql = "SELECT * FROM coffees WHERE 1"; // '1' mindig igaz, így könnyen hozzáadhatunk további feltételeket
$values = []; // Paraméterek tárolására
$type_string = ''; // Típusok tárolására

// Paraméterek hozzáadása a lekérdezéshez, ha nem null
if ($manufacturer !== null) {
    $sql .= " AND manufacturer = ?";
    $values[] = $manufacturer;
    $type_string .= 's'; // string típus
}
if ($coffeename !== null) {
    $sql .= " AND coffeename = ?";
    $values[] = $coffeename;
    $type_string .= 's'; // string típus
}
if ($region !== null) {
    $sql .= " AND region = ?";
    $values[] = $region;
    $type_string .= 's'; // string típus
}
if ($flavors !== null) {
    $sql .= " AND flavornotes = ?";
    $values[] = $flavors;
    $type_string .= 's'; // string típus
}
if ($roasting !== null) {
    $sql .= " AND roasting = ?";
    $values[] = $roasting;
    $type_string .= 's'; // string típus
}

// Ha nincsenek paraméterek, akkor minden rekordot visszaadunk
if (empty($values)) {
    // Ha nincsenek szűrők, akkor az alap lekérdezés fut le
    $sql = "SELECT * FROM coffees";
}

// Ha vannak paraméterek, akkor készítjük elő a lekérdezést
$stmt = $conn->prepare($sql);

// Ha vannak paraméterek, akkor bind_param használata
if (!empty($values)) {
    // A bind_param() második paramétere a típusokat tartalmazó string
    $stmt->bind_param($type_string, ...$values);
}

// Lekérdezés futtatása
$stmt->execute();

// Az eredmény lekérése
$result = $stmt->get_result();

// Kiíratjuk az eredményeket
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ellenőrizzük, hogy az oszlopok léteznek-e, mielőtt kiírjuk
        echo "Manufacturer: " . (isset($row['Manufacturer']) ? htmlspecialchars($row['Manufacturer']) : 'N/A') . "<br>";
        echo "Coffee Name: " . (isset($row['CoffeeName']) ? htmlspecialchars($row['CoffeeName']) : 'N/A') . "<br>";
        echo "Region: " . (isset($row['Region']) ? htmlspecialchars($row['Region']) : 'N/A') . "<br>";
        echo "Flavors: " . (isset($row['FlavorNotes']) ? htmlspecialchars($row['FlavorNotes']) : 'N/A') . "<br>";
        echo "Roasting: " . (isset($row['Roasting']) ? htmlspecialchars($row['Roasting']) : 'N/A') . "<br><br>";
    }
} else {
    echo "Nincs találat.";
}

// Kapcsolat lezárása
$stmt->close();
$conn->close();
?>
