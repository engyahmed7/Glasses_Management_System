<?php
require_once "./db.php";

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

    $resourceId = (isset($urlParts[2]) && is_numeric($urlParts[2])) ? (int) $urlParts[2] : 0;

    global $capsule;

    if ($resourceId == 0) {
        return json_encode(getAllGlasses($capsule));
    } else {
        return json_encode(getSingleGlass($capsule, $resourceId));
    }
}

function addNewGlaases($capsule)
{
    if (empty($_POST)) {
        http_response_code(400);
        return json_encode(["error" => "No data sent"]);
    }

    $data = $_POST;

    $capsule->table("items")->insert($data);

    return json_encode($data);
}


function deleteGlass($capsule)
{
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);

    $resourceId = (isset($urlParts[2]) && is_numeric($urlParts[2])) ? (int) $urlParts[2] : 0;

    if ($resourceId == 0) {
        http_response_code(400);
        return json_encode(["error" => "No resource id provided"]);
    } else {
        $capsule->table("items")->where("id", $resourceId)->delete();
        return json_encode(["message" => "Glass deleted"]);
    }

}

function updateGlass($capsule)
{
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $resourceId = (isset($urlParts[2]) && is_numeric($urlParts[2])) ? (int) $urlParts[2] : 0;

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
