<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kávé Kereső</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
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
              <h4>Kávé Katalógus</h4>
            </a>
          </li>
          <li class="nav-item mt-1">
            <form class="d-flex" role="search" action="coffees.php" method="post">
              <input class="form-control me-2" type="search" placeholder="Keresés..." aria-label="Keresés" />
              <button class="btn btn-outline-success" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </form>
          </li>
          <li class="nav-item ms-1 ms-md-2 mt-1 me-2">
            <a href="search.php"><button class="btn btn-outline-success" type="submit">
                Részletes keresés
              </button></a>
          </li>
        </ul>
        <ul class="navbar-nav navbar-right mb-2">
          <?php if (!$isLoggedIn): ?>
            <li class="nav-item me-2 mt-2 mt-md-0 ms-1 ms-md-0">
              <a href="registration.html"><button class="btn btn-outline-primary">
                  Regisztráció
                </button></a>
            </li>
            <li class="nav-item ms-1 me-2 mt-2 mt-md-0">
              <a href="login.html"><button class="btn btn-outline-primary">
                  Bejelentkezés
                </button></a>
            </li>
          <?php else: ?>
            <li class="nav-item ms-1 me-2 mt-2 mt-md-0">
              <a href="logout.php"><button class="btn btn-outline-primary">
                  Kijelentkezés
                </button></a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-5">
    <div class="card p-4">
      <h1 class="text-center mb-4">Részletes keresés</h1>
      <form id="coffee-search-form">
        <div class="mb-3">
          <label for="manufacturer" class="form-label">Gyártó</label>
          <select id="manufacturer" name="manufacturer" class="form-select">
            <option value="">Válasszon gyártót...</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="region" class="form-label">Régió</label>
          <select id="region" name="region" class="form-select">
            <option value="">Válasszon régiót...</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="roasting" class="form-label">Pörkölés</label>
          <select id="roasting" name="roasting" class="form-select">
            <option value="">Válasszon pörkölést...</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="flavorNotes" class="form-label">Ízjegy</label>
          <select id="flavorNotes" name="flavorNotes" class="form-select">
            <option value="">Válasszon ízjegyet...</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Keresés</button>
      </form>
    </div>

    <div class="container mt-4">
      <div id="results" class="row">
        <!-- Cards for search results will be displayed here -->
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Fetch data for dropdowns from fetch_search.php
      const fetchOptions = async () => {
        try {
          const response = await fetch("fetch_search.php");
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          const data = await response.json();

          populateDropdown("manufacturer", data.manufacturers);
          populateDropdown("region", data.regions);
          populateDropdown("roasting", data.roastings);
          populateDropdown("flavorNotes", data.flavorNotes);
        } catch (error) {
          console.error(
            "There was a problem with the fetch operation:",
            error
          );
        }
      };

      // Populate dropdown menu
      const populateDropdown = (id, options) => {
        const dropdown = document.getElementById(id);
        options.forEach((option) => {
          const opt = document.createElement("option");
          opt.value = option;
          opt.textContent = option;
          dropdown.appendChild(opt);
        });
      };

      // Handle search form submission
      const searchForm = document.getElementById("coffee-search-form");
      searchForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(searchForm);

        try {
          const response = await fetch("coffees.php", {
            method: "POST",
            body: formData,
          });

          if (!response.ok) {
            throw new Error("Search request failed");
          }

          const results = await response.json();
          displayResults(results);
        } catch (error) {
          console.error("There was a problem with the search:", error);
        }
      });

      // Display search results
      const displayResults = (results) => {
        const resultsContainer = document.getElementById("results");
        resultsContainer.innerHTML = "";

        if (results.length === 0) {
          resultsContainer.innerHTML =
            '<p class="text-center text-muted">Nincs ilyen kávé.</p>';
          return;
        }
        console.log(results);

        results.forEach((coffee) => {
          const card = document.createElement("div");
          card.className = "col-md-4";
          card.innerHTML = `
                      <div class="card mb-5 m-1 shadow-sm">
                          <img class="card-img-top" src="
                            https://i.imgur.com/SdgMDOM.jpeg
                          " alt="image of coffee">
                          <div class="card-body">
                              <h5 class="card-title">${coffee.coffeeName}</h5>
                              <p class="card-text">${coffee.manufacturer}</p>
                              <div class="d-flex justify-content-between align-items-center">
                                  <span class="text-muted small">${coffee.region}</span>
                                  <a href="details.php?id=${coffee.id}" class="btn btn-sm btn-primary">Megtekint</a>
                              </div>
                          </div>
                      </div>
                  `;
          resultsContainer.appendChild(card);
        });
      };

      // Call fetchOptions to initialize dropdowns
      fetchOptions();
    });
  </script>
</body>

</html>