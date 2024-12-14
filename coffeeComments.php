<?php
// Kapcsolódás az adatbázishoz
$servername = "localhost"; // Adatbázis szerver
$username = "root";        // Adatbázis felhasználó
$password = "admin";            // Adatbázis jelszó
$dbname = "cofebeanwebpage";   // Adatbázis neve

// Kapcsolódás létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizzük a kapcsolatot
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// A kávé azonosítója, amelyhez a kommenteket kérdezzük le
$coffeeID = $_GET['CoffeID'];  // Kávé azonosító lekérdezése URL paraméterből

// Az SQL lekérdezés előkészítése
$sql = "SELECT c.Opinion, u.username FROM Opinions c
        JOIN Users u ON c.UserID = u.ID
        WHERE c.CoffeID = ?";

// Lekérdezés végrehajtása
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $coffeeID);
$stmt->execute();

// Eredmények lekérdezése
$result = $stmt->get_result();

// Ha találunk kommenteket, kiírjuk őket
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['Opinion']) . "</p>";
    }
} else {
    echo "Nincsenek kommentek ehhez a kávéhoz.";
}

// Kapcsolat bezárása
$stmt->close();
$conn->close();
?>
