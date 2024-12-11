<?php
session_start();

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

// A komment adatainak lekérése (pl. POST metódussal)
$coffeeID = $_POST['coffeeID'];  // A kávé azonosítója, amelyhez a komment tartozik
$userID = $_SESSION['user_id'];      // A felhasználó azonosítója
$comment = $_POST['comment'];    // A komment szövege

// Ellenőrizzük, hogy létezik-e a coffeeID a Comments táblában
$sql_check_coffee = "SELECT 1 FROM coffees WHERE ID = ?";
$stmt_check_coffee = $conn->prepare($sql_check_coffee);
$stmt_check_coffee->bind_param("i", $coffeeID);
$stmt_check_coffee->execute();
$result_check_coffee = $stmt_check_coffee->get_result();

// Ha nincs ilyen kávé ID, akkor hibát dobunk
if ($result_check_coffee->num_rows === 0) {
    die("A megadott kávé nem létezik.");
}

// Ellenőrizzük, hogy létezik-e a userID a Users táblában
$sql_check_user = "SELECT 1 FROM users WHERE ID = ?";
$stmt_check_user = $conn->prepare($sql_check_user);
$stmt_check_user->bind_param("i", $userID);
$stmt_check_user->execute();
$result_check_user = $stmt_check_user->get_result();

// Ha nincs ilyen felhasználó ID, akkor hibát dobunk
if ($result_check_user->num_rows === 0) {
    die("A megadott felhasználó nem létezik.");
}

// Az SQL lekérdezés előkészítése
$stmt = $conn->prepare("INSERT INTO opinions (Opinion, UserID, CoffeID) VALUES (?, ?, ?)");
$stmt->bind_param("sii", $comment, $userID, $coffeeID);

// Lekérdezés végrehajtása
if ($stmt->execute()) {
    // echo "Komment sikeresen hozzáadva!";
    echo json_encode(["success" => true]);
} else {
    //echo "Hiba történt a komment hozzáadásakor: " . $stmt->error;
    echo json_encode(["success" => false]);
}

// Kapcsolat bezárása
$stmt->close();
$stmt_check_coffee->close();
$stmt_check_user->close();
$conn->close();
?>
