<?php
session_start();  // Indítjuk a session-t, hogy tároljuk a bejelentkezett felhasználó adatait

// Adatbázis kapcsolódás
$servername = "localhost";
$username = "root";  // Alapértelmezett MySQL felhasználónév
$password = "admin";      // Alapértelmezett MySQL jelszó (üres)
$dbname = "CofeBeanWebPage";  // Az adatbázis neve

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizzük, hogy sikerült-e a kapcsolat
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ha a formot elküldték
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input = json_decode(file_get_contents("php://input"), true);

    // A felhasználónevet és jelszót kinyerjük a POST-ból
    $username = mysqli_real_escape_string($conn, $input['username']);
    $password = mysqli_real_escape_string($conn, $input['password']);

    // Az adatbázisból kinyerjük a felhasználó adatait
    $sql = "SELECT id, username, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    // Ha a felhasználó létezik az adatbázisban
    if ($result->num_rows > 0) {
        // Az adatokat beolvassuk
        $row = $result->fetch_assoc();

        // Ellenőrizzük, hogy a megadott jelszó helyes-e (password_verify() használatával)
        if (password_verify($password, $row['password'])) {
            // Ha a jelszó helyes, akkor bejelentkezünk
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            echo json_encode(["success" => true]);
            exit();
        } else {
            // Ha a jelszó nem helyes
            echo json_encode(["success" => false, "error" => "Hibás jelszó!"]);
            exit();
        }
    } else {
        // Ha a felhasználónév nem létezik
        echo json_encode(["success" => false, "error" => "Hibás felhasználónév!"]);
        exit();
    }
}

$conn->close();
?>