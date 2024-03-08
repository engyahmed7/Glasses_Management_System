<?php
require_once "./db.php";

header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo handleGetRequest();
        break;
    case 'POST':
        echo addNewGlaases($capsule);
        break;
    case 'PUT':
        echo updateGlass($capsule);
        break;
    case 'DELETE':
        echo deleteGlass($capsule);
        break;
    default:
        http_response_code(405);
        echo "Method not supported";
        break;
}

function getAllGlasses($capsule)
{
    return $capsule->table("items")->select()->get();
}

function getSingleGlass($capsule, $id)
{
    $item = $capsule->table("items")->select()->where("id", $id)->first();

    if (empty($item)) {
        return ["error" => "No item found"];
    }

    return $item;
}

function handleGetRequest()
{
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);

    $resource = $urlParts[2];

    $resourceId = (isset($urlParts[3]) && is_numeric($urlParts[3])) ? (int) $urlParts[3] : 0;

    global $capsule;

    if ($resource === 'glasses') {
        if ($resourceId == 0) {
            return json_encode(getAllGlasses($capsule));
        } else {
            return json_encode(["message" => "Invalid API"]);
        }
    } elseif ($resource === 'glass') {
        if ($resourceId != 0) {
            return json_encode(getSingleGlass($capsule, $resourceId));
        } else {
            http_response_code(400);
            return json_encode(["error" => "No resource ID provided"]);
        }
    } else {
        http_response_code(404);
        return json_encode(["error" => "Resource not found"]);
    }
}


function addNewGlaases($capsule)
{
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);

    if (isset($urlParts[2])) {
        $resource = $urlParts[2];


        if ($resource === 'glasses') {
            if (empty($_POST)) {
                http_response_code(400);
                return json_encode(["error" => "No data sent"]);
            }

            $file = $_FILES['Photo'];
            $uploadDirectory = "./Resources/images/";
            $targetFile = $uploadDirectory . basename($file['name']);

            $data = $_POST;
            $data['Photo'] = $targetFile;

            $capsule->table("items")->insert($data);

            return json_encode($data);
        }
    }
    http_response_code(404);
    return json_encode(["error" => "Resource not found"]);
}


function deleteGlass($capsule)
{
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);

    $resource = $urlParts[2];

    $resourceId = (isset($urlParts[3]) && is_numeric($urlParts[3])) ? (int) $urlParts[3] : 0;

    if($resource === 'glasses'){

    if ($resourceId == 0) {
        http_response_code(400);
        return json_encode(["error" => "No resource id provided"]);
    }
    $existingItem = $capsule->table("items")->find($resourceId);
    if (!$existingItem) {
        http_response_code(404);
        return json_encode(["error" => "Item not found with ID: $resourceId"]);
    }

    $deleted = $capsule->table("items")->where("id", $resourceId)->delete();
    if ($deleted) {
        return json_encode(["message" => "Item deleted successfully"]);
    }
}
http_response_code(404);
return json_encode(["error" => "Resource not found"]);
}

function updateGlass($capsule)
{
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);

    $resource = $urlParts[2];

    $resourceId = (isset($urlParts[3]) && is_numeric($urlParts[3])) ? (int) $urlParts[3] : 0;

    if($resource === 'glasses'){

    if ($resourceId == 0) {
        http_response_code(400);
        return json_encode(["error" => "No resource ID provided"]);
    }

    $rawData = file_get_contents("php://input");

    if (empty($rawData)) {
        http_response_code(400);
        return json_encode(["error" => "No data sent"]);
    }

    $data = json_decode($rawData, true);

    if (empty($data)) {
        http_response_code(400);
        return json_encode(["error" => "Invalid data sent"]);
    }

    $existingItem = $capsule->table("items")->find($resourceId);
    if (!$existingItem) {
        http_response_code(404);
        return json_encode(["error" => "Item not found with ID: $resourceId"]);
    }

    $capsule->table("items")->where("id", $resourceId)->update($data);

    return json_encode($data);
}
http_response_code(404);
return json_encode(["error" => "Resource not found"]);
}
