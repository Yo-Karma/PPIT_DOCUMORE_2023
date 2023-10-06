<?php

require_once(__DIR__ . "/../config.php");

$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

// https://stackoverflow.com/questions/40582161/how-to-properly-use-bearer-tokens
function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

// https://stackoverflow.com/questions/40582161/how-to-properly-use-bearer-tokens
function getBearerToken()
{
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function isTokenValid($token)
{
    if (empty($token)) {
        return false;
    } else {
        return $token == BEARER_TOKEN;
    }
}

function validateGetRequest($specialRequestKey, $callback)
{
    if (isset($_GET[$specialRequestKey])) {
        if (isTokenValid(getBearerToken())) {
            call_user_func($callback);
        } else {
            echo json_encode(array("message" => "Unauthorized"));
            exit();
        }
    } else {
        echo json_encode(array("message" => "Invalid request"));
        exit();
    }
}

validateGetRequest("whitelist", function () {
    global $pdo;

    $get_username = $_GET["username"];
    $get_documentId = $_GET["documentId"];


    $stmt = $pdo->prepare("INSERT INTO whitelist (username, documentID) VALUES (:username, :documentID)");
    $stmt->bindParam(":username", $get_username);
    $stmt->bindParam(":documentID", $get_documentId);

    $stmt->execute();

    http_response_code(200);
    echo json_encode(array("message" => "Whitelisted"));
    exit();
});


validateGetRequest("unwhitelist", function () {
    global $pdo;

    $get_username = $_GET["username"];
    $get_documentId = $_GET["documentId"];


    $stmt = $pdo->prepare("DELETE FROM whitelist WHERE username = :username AND documentID = :documentID");
    $stmt->bindParam(":username", $get_username);
    $stmt->bindParam(":documentID", $get_documentId);

    $stmt->execute();

    http_response_code(200);
    echo json_encode(array("message" => "Unwhitelisted"));
    exit();
});
