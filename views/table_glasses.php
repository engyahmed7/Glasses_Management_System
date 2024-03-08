<?php
session_start();
require_once "../db.php";

/* -------------------------------------------------------------------------- */
/*                                 pagination                                 */
/* -------------------------------------------------------------------------- */

$items = $capsule->table("items")->select()->get();

$itemsPerPage = 5;
$totalItems = $items->count();
$totalPages = ceil($totalItems / $itemsPerPage);

$page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
$currentPage = min($page, $totalPages);

$startIndex = ($currentPage - 1) * $itemsPerPage;

$itemsOnPage = $items->slice($startIndex, $itemsPerPage)->values();

$errorMsg = '';
$successMessage = '';
/* -------------------------------------------------------------------------- */
/*                             add / insert products                          */
/* -------------------------------------------------------------------------- */

$productName = isset($_POST['name']) ? $_POST['name'] : '';
$productPrice = isset($_POST['price']) ? $_POST['price'] : '';
$productCategory = isset($_POST['category']) ? $_POST['category'] : '';
$productImage = isset($_POST['img']) ? $_POST['img'] : '';

$flag = false;

if (!empty($_POST)) {
    $originalFilename = $_FILES['img']['name'];
    if (!empty($_FILES['img']['tmp_name'])) {

        if (!empty($productName) && !empty($productPrice) && !empty($productCategory)) {
            try {
                $capsule->table('items')->insert([
                    'product_name' => $productName,
                    'list_price' => $productPrice,
                    'category' => $productCategory,
                    'Photo' => $originalFilename,
                    'date' => date('Y-m-d H:i:s'),
                    'Rating' => "5",
                    'CouNtry' => "USA",
                    'Units_In_Stock' => '10',
                ]);

                $target_dir = "../Resources/images/";
                $target_file = $target_dir . basename($_FILES["img"]["name"]);
                move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
                $successMessage = "Your data is added successfully";
                $flag = true;
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            $errorMsg = "All fields are required.";
        }
    } else {
        $errorMsg = "Please select an image file.";
    }
}

/* -------------------------------------------------------------------------- */
/*                                delete logic                                */
/* -------------------------------------------------------------------------- */

$deletedFlag = false;
$deletedId = isset($_GET['_id']) ? $_GET['_id'] : '';

if (!empty($deletedId)) {
    $deletedItem = $capsule->table('items')->where('id', $deletedId)->first();

    if (!$deletedItem) {
        $errorMsg = "Item not found";
    } else {
        $imageName = $deletedItem->Photo;
        $target_dir = "../Resources/images/";
        $target_file = $target_dir . basename($imageName);

        if (!file_exists($target_file)) {
            $errorMsg = "Image file not found";
        } else {
            unlink($target_file);
        }

        $capsule->table('items')->where('id', $deletedId)->delete();
        $successMessage = "Your data is deleted successfully.";
        $deletedFlag = true;
    }
}


/* -------------------------------------------------------------------------- */
/*                                  search                                    */
/* -------------------------------------------------------------------------- */

$searchResutlt = isset($_GET['search']) ? $_GET['search'] : '';
$searchFlag = false;
if (!empty($searchResutlt)) {
    $resFromSearch = $capsule->table('items')
        ->where('id', $searchResutlt)
        ->orWhere('product_name', 'LIKE', "%$searchResutlt%")
        ->orWhere('list_price', 'LIKE', "%$searchResutlt%")
        ->orWhere('category', 'LIKE', "%$searchResutlt%")
        ->orWhere("CouNtry", "LIKE", "%$searchResutlt%")
        ->get();
    $searchFlag = true;
}

// handle update alert 


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
    <?php
    if (isset($_SESSION['alert_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Done!</strong> ' . $_SESSION['alert_message'] . '
                </div>';

        unset($_SESSION['alert_message']);
    }
    ?>
    <!-- add / delete product alert -->
    <?php if (!empty($_POST['submit']) && $flag || $deletedFlag) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Done!</strong>
            <?php echo $successMessage ?>
        </div>
    <?php } ?>

    <!-- Error alert -->
    <?php if ($errorMsg) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?= $errorMsg ?>
        </div>
    <?php } ?>

    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h1 class="text-center">Glasses Table</h1>
            </div>
            <form method="get" class="d-flex justify-content-center mt-4">
                <input type="text" class="form-control me-2 w-25" name="search" id="search" placeholder="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <div class="table-responsive mt-5">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">View details</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- search res display  -->
                        <?php
                        if ($searchFlag) {
                            if ($resFromSearch->count() > 0) {
                                foreach ($resFromSearch as $item) { ?>
                                    <tr>
                                        <td>
                                            <?= $item->id ?>
                                        </td>
                                        <td>
                                            <?= $item->product_name ?>
                                        </td>
                                        <td>
                                            <a href="details.php?id=<?= $item->id ?>" class="btn btn-success">
                                                View more
                                            </a>
                                            <a href="table_glasses.php?_id=<?= $item->id ?>" class="btn btn-danger">
                                                Delete
                                            </a>
                                            <a href="update.php?itemId=<?= $item->id ?>" class="btn btn-warning">
                                                Update
                                            </a>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>

                                <!-- search not found -->
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    </button>
                                    <strong>Sorry!</strong> No results found.
                                </div>

                                <tr>
                                    <td colspan="3" class="text-center">
                                        No results found
                                    </td>
                                </tr>

                            <?php } ?>
                        <?php } else {
                            /* -------------------------------------------------------------------------- */
                            /*                           // display all results                           */
                            /* -------------------------------------------------------------------------- */
                            foreach ($itemsOnPage as $item) { ?>
                                <tr>
                                    <td>
                                        <?= $item->id ?>
                                    </td>
                                    <td>
                                        <?= $item->product_name ?>
                                    </td>
                                    <td>
                                        <a href="details.php?id=<?= $item->id ?>" class=" btn btn-success">
                                            View more
                                        </a>
                                        <a href="?_id=<?= $item->id ?>" class="btn btn-danger">
                                            Delete
                                        </a>
                                        <a href="update.php?itemId=<?= $item->id ?>" class="btn btn-warning">
                                            Update
                                        </a>

                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>

                </table>
            </div>
            <!-- pagination -->
            <?php if (!$searchFlag) { ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label=" Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label=" Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php } ?>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-primary w-25" data-bs-toggle="modal" data-bs-target="#modalId">
                Add
            </button>
            <!-- Modal -->

            <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">
                                Add Your Data
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3 container-fluid">

                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder=""
                                        aria-describedby="helpId" />

                                    <label for=" img" class="form-label mt-2">Product Photo</label>
                                    <input type="file" class="form-control" name="img" id="img" placeholder=""
                                        aria-describedby="fileHelpId" />

                                    <label for="price" class="form-label mt-2">Product Price</label>
                                    <input type="text" name="price" id="price" class="form-control" placeholder=""
                                        aria-describedby="helpId" />

                                    <label for="category" class="form-label mt-2">Product Category</label>
                                    <textarea class="form-control" name="category" id="category" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" name="submit" value="Add" class="btn btn-primary" />
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
