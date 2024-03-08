<?php

require './vendor/autoload.php';

$urlParts = explode('/', $_SERVER['REQUEST_URI']);

$resource = $urlParts[2];
$resourceId = (isset($urlParts[3]) && is_numeric($urlParts[3])) ? (int) $urlParts[3] : 0;

/**
 * 1- Define METHOD
 * 2- Define RESOURCE
 * 3- Define Resource_ID
 */
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // $data = handleGet($resource, $resourceId);
        break;
    case 'POST':
        echo "Will create";
        break;
    case 'PUT':
        echo "Will update";
        break;
    case 'DELETE':
        echo "Will delete";
        break;

    default:
        echo 'not supported';
        break;
}

$statusCode = is_null($data) ? 404 : 200;
http_response_code($statusCode);
header('Content-Type: application/json');

if (!empty($data)) {
    echo json_encode($data);
}

/**
 * 
 * Get with no user id (user id = 0) => List all users
 * Get with user id => get only single user by id
 * 
 * @param type $resource
 * @param type $resourceId
 * @return type
 */
// function handleGet($resource, $resourceId) {
//     if ($resource == 'users') {
//         if ($resourceId != 0) {
//             return (new \App\Webservice())->getSingleUser($resourceId);
//         }
//         return (new \App\Webservice())->getUsers();
//     }
// }
