<?php
require_once "../db.php";

$product_id = $_GET['id'];

$items = $capsule->table("items")->select()->where("id", $product_id)->get();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glasses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>

<body>

  <div class="container mt-5">
    <div class="card col-7 offset-2 ">
      <?php foreach ($items as $item) { ?>
        <div class="card-header text-center mb-5">
          <div class="display-5">
            <?= $item->product_name ?>
          </div>
        </div>
        <img src="../Resources/images/<?= $item->Photo ?>" class="pt-2 card-img-top img-fluid w-50 d-block m-auto"
          alt="...">
        <div class="card-body">
          <h5 class="card-subtitle mb-2 text-muted text-center">
            <?= $item->category ?>
          </h5>

          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>Price:</strong>
              <?= $item->list_price ?>
            </li>
            <li class="list-group-item">
              <strong>Code:</strong>
              <?= $item->PRODUCT_code ?>
            </li>
            <li class="list-group-item">
              <strong>Units in Stock:</strong>
              <?= $item->Units_In_Stock ?>
            </li>
            <li class="list-group-item">
              <strong>Rating:</strong>
              <?= $item->Rating ?>
            </li>
          </ul>
          <br />
          <p class="card-text text-center text-muted"><small class="text-body-secondary">
              <?= $item->date ?>
            </small>
          </p>
        </div>

      <?php } ?>
    </div>
  </div>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>