<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
?>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Főoldal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-body-tertiary">
    <div class="container-fluid ms-4 me-4 mt-1">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse m-1" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mt-1 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active me-3" aria-current="page" href="index.php">
              <h4><i class="fa-solid fa-mug-saucer"></i> Kávé Katalógus</h4>
            </a>
          </li>
          <li class="nav-item ms-1 ms-md-2 mt-1 me-2">
            <a href="search.php"><button class="btn btn-outline-success" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i> Részletes keresés
              </button></a>
          </li>
        </ul>
        <ul class="navbar-nav navbar-right mb-2">
          <?php if (!$isLoggedIn): ?>
            <li class="nav-item me-2 mt-2 mt-md-0 ms-1 ms-md-0">
              <a href="registration.html"><button class="btn btn-outline-primary">
                  <i class="fa-regular fa-address-card"></i>
                  Regisztráció
                </button></a>
            </li>
            <li class="nav-item ms-1 me-2 mt-2 mt-md-0">
              <a href="login.html"><button class="btn btn-outline-primary">
                  <i class="fa-solid fa-arrow-right-to-bracket"></i>
                  Bejelentkezés
                </button></a>
            </li>
          <?php else: ?>
            <li class="nav-item ms-1 me-2 mt-2 mt-md-0">
              <a href="logout.php"><button class="btn btn-outline-primary">
                  Kijelentkezés
                  <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button></a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <?php if (!empty($_SESSION['message'])) {
      echo '<p class="message p-2"> ' . $_SESSION['message'] . '</p>';
      unset($_SESSION['message']);
    } ?>
  </div>

  <div class="container mt-4">
    <div class="row" id="coffee-list">
      <!-- Coffee cards are dynamically inserted here -->
    </div>
  </div>

  <script>
    // Fetch data from PHP and render it dynamically
    fetch("fetch_coffees.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            "Network response was not ok " + response.statusText
          );
        }
        return response.json();
      })
      .then((data) => {
        const coffeeList = document.getElementById("coffee-list");
        data.forEach((coffee) => {
          // Create card for each coffee
          const card = document.createElement("div");
          card.className = "col-md-4";
          card.innerHTML = `
                      <div class="card mb-5 m-1 shadow-sm">
                          <img class="card-img-top" src="
                            https://i.imgur.com/SdgMDOM.jpeg
                          " alt="image of coffee">
                          <div class="card-body">
                              <h5 class="card-title">${coffee.coffeename}</h5>
                              <p class="card-text">${coffee.manufacturer}</p>
                              <div class="d-flex justify-content-between align-items-center">
                                  <span class="text-muted small">${coffee.region}</span>
                                  <a href="details.php?id=${coffee.id}" class="btn btn-sm btn-primary">Megtekint</a>
                              </div>
                          </div>
                      </div>
                  `;
          coffeeList.appendChild(card);
        });
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  </script>
</body>

</html>