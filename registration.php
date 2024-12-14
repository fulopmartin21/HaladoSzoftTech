<?php
// Adatbázis kapcsolódás
$servername = "localhost";
$username = "root";  // alapértelmezett MySQL felhasználónév
$password = "admin";      // alapértelmezett MySQL jelszó (üres)
$dbname = "CofeBeanWebPage";  // Az adatbázis neve

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizzük, hogy sikerült-e a kapcsolat
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ha az űrlap elküldésre került
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Az űrlapról érkező adatokat tisztítjuk
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $birth_place = mysqli_real_escape_string($conn, $_POST['birth_place']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = mysqli_real_escape_string($conn, $_POST['password2']);

    // Az adatbevitel alapvető ellenőrzése (üres mezők)
    if (empty($name) || empty($birth_place) || empty($birth_date) || empty($username) || empty($password)) {
        echo "Minden mezőt ki kell tölteni!";
    } else {
        // Ellenőrizzük, hogy a felhasználónév már létezik-e
        $sql_check = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql_check);

        if ($result->num_rows > 0) {
            echo "A felhasználónév már foglalt!";
        } else {
            // Jelszó titkosítása
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Adatok mentése az adatbázisba
            $sql = "INSERT INTO users (Name, BirthPlace, DateOfBirth, Username, Password) 
                    VALUES ('$name', '$birth_place', '$birth_date', '$username', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                header("Location: index.php");
                exit;
            } else {
                echo "Hiba: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>