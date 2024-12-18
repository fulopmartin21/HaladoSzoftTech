<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
?>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kávé Részletező</title>
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

  <div class="container mt-4" id="coffee-details">
    <!-- Coffee details will be dynamically inserted here -->
  </div>

  <!-- Comments -->
  <div class="container p-3 mt-4" style="display: flex; justify-content: center">
    <div style="
          padding: 10px;
          border-radius: 25px;
          box-shadow: 0px 0px 15px 1px rgba(0, 0, 0, 0.25);
          width: fit-content;
          display: flex;
          flex-direction: column;
          height: fit-content;
          align-items: center;
          justify-content: center;
        ">
      <div id="comments-container" class="container" style="
            border: solid;
            border-color: grey;
            background-color: #f4f5f6;
            border-width: 1px;
            margin: 10px;
          "></div>
      <form id="commentForm" style="width: 100%">
        <input type="hidden" name="coffeeID" />
        <label for="commentAreaId"></label>
        <textarea id="commentAreaId" style="width: 100%" name="comment" cols="40" rows="3"
          placeholder="Írjon véleményt..." required></textarea>
        <div style="width: 100%; display: flex; justify-content: right">
          <input type="submit" value="Küldés" class="btn btn-primary" style="margin: 5px 0 5px 5px" />
        </div>
      </form>
    </div>
  </div>

  <script>
    // Get the coffee ID from the URL
    const params = new URLSearchParams(window.location.search);
    const coffeeId = params.get("id");

    if (!coffeeId) {
      document.getElementById("coffee-details").innerHTML =
        "<p>Invalid coffee ID.</p>";
    } else {
      // Fetch coffee details
      fetch(`fetch_details.php?id=${coffeeId}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error(
              "Network response was not ok " + response.statusText
            );
          }
          return response.json();
        })
        .then((data) => {
          if (data.error) {
            document.getElementById(
              "coffee-details"
            ).innerHTML = `<p>${data.error}</p>`;
          } else {
            const container = document.getElementById("coffee-details");
            container.innerHTML = `
                          <table class="table table-bordered">
                              <tr>
                                  <td rowspan="6" style="width: 50%;"> 
                                      <img class="img-fluid" src="https://i.imgur.com/SdgMDOM.jpeg" alt="Coffee picture">
                                  </td>
                                  <td><strong>Kávé neve:</strong> ${data.coffeename}</td>
                              </tr>
                              <tr>
                                  <td><strong>Gyártó:</strong> ${data.manufacturer}</td>
                              </tr>
                              <tr>
                                  <td><strong>Régió:</strong> ${data.region}</td>
                              </tr>
                              <tr>
                                  <td><strong>Pörkölés:</strong> ${data.roasting}</td>
                              </tr>
                              <tr>
                                  <td><strong>Ízjegyek:</strong> ${data.flavornotes}</td>
                              </tr>
                              <tr> 
                                <td><strong>Megvásárolható itt:</strong> 
                                  <form action="increment_counter.php" method="POST" style="display: inline;">
                                      <input type="hidden" name="coffee_id" value="${data.id}" />
                                      <button type="submit" class="btn btn-link text-decoration-none" style="padding: 0; padding-bottom: 5px;">Casino Mocca</button>
                                  </form>
                                </td>
                              </tr>
                          </table>
                      `;
          }
        })
        .catch((error) => {
          console.error(
            "There was a problem fetching coffee details:",
            error
          );
          document.getElementById("coffee-details").innerHTML =
            "<p>Error loading coffee details.</p>";
        });

      const coffeeIdInput = document.querySelector('input[name="coffeeID"]');
      coffeeIdInput.value = coffeeId;

      document
        .getElementById("commentForm")
        .addEventListener("submit", function (event) {
          event.preventDefault();

          const formData = new FormData(this);
          fetch("comment.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((result) => {
              if (result.success) {
                alert("Vélemény hozzáadva!");
                fetchComments();
                this.reset();
              } else {
                alert(
                  "Nem sikerült elmenteni az üzenetet: " + result.message
                );
              }
            })
            .catch((error) => {
              console.error("Hiba a komment mentésekor:", error);
            });
        });

      const fetchComments = () => {
        fetch(`coffeeComments.php?CoffeID=${coffeeId}`)
          .then((response) => response.text())
          .then((data) => {
            const commentsContainer =
              document.getElementById("comments-container");

            commentsContainer.innerHTML = data;
          })
          .catch((error) => {
            console.error("Error fetching comments:", error);
          });
      };

      fetchComments();

    }
  </script>
</body>

</html>