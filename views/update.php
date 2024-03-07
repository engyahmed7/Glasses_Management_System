<?php
session_start();
require_once "../db.php";

$Item_id = isset($_GET['itemId']) ? $_GET['itemId'] : '';

if (!empty($Item_id)) {
    $userData = $capsule->table('items')->where('id', $Item_id)->first();
} else {
    header('Location: table_glasses.php');
}

if (!empty($_POST)) {
    $productName = isset($_POST['name']) ? $_POST['name'] : '';
    $productPrice = isset($_POST['price']) ? $_POST['price'] : '';
    $productCategory = isset($_POST['category']) ? $_POST['category'] : '';

    if (!empty($_FILES['img']['name'])) {
        $productImg = $_FILES['img']['name'];
        $target_dir = "../Resources/images/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
    } else {
        $productImg = $userData->Photo;
    }


    $capsule->table('items')->where('id', $Item_id)->update([
        'product_name' => $productName,
        'list_price' => $productPrice,
        'category' => $productCategory,
        'Photo' => $productImg
    ]);

    $_SESSION['alert_message'] = "Your data is updated successfully.";
    header('Location: table_glasses.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <h1 class="display-4 text-center mt-5 mb-5">Update User</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3 container-fluid">

                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= $userData->product_name; ?>"
                    aria-describedby="helpId" />

                <label for=" img" class="form-label mt-2">Product Photo</label>
                <input type="file" class="form-control" name="img" id="img" placeholder=""
                    aria-describedby="fileHelpId" />

                <label for="price" class="form-label mt-2">Product Price</label>
                <input type="text" name="price" id="price" class="form-control" placeholder=""
                    value="<?= $userData->list_price; ?>" aria-describedby="helpId" />

                <label for="category" class="form-label mt-2">Product Category</label>
                <textarea class="form-control" name="category" id="category"
                    rows="3"><?= $userData->category; ?></textarea>


                <button type="submit" class="btn btn-warning mt-3">Update</button>
            </div>
        </form>
    </div>

</body>

</html>