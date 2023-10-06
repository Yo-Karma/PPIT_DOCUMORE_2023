<?php
session_start();
require_once __DIR__ . '/../actions/functions.php';
require_once __DIR__ . '/../config.php';

// SQL CONNECTION \\
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

if (isset($_GET['code'])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://github.com/login/oauth/access_token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => GITHUB_CLIENT_ID,
        'client_secret' => GITHUB_CLIENT_SECRET,
        'code' => $_GET['code'],
        'scope' => 'user:email'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $pairs = explode("&", $response);
    $data = array();

    foreach ($pairs as $pair) {
        list($key, $value) = explode("=", $pair);
        $data[$key] = $value;
    }

    $access_token = $data['access_token'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.github.com/user");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: token $access_token",
        "User-Agent: documore"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    $user = json_decode($response);

    $_SESSION['user'] = $user;

    $username = $user->login;
    $avatar = $user->avatar_url;

    // Check if user exists
    $stmt = $pdo->prepare("SELECT name FROM users WHERE name=?");
    $stmt->execute([$username]);
    $result = $stmt->fetchAll();
    $exists = false;
    foreach ($result as $row) {
        $exists = true;
    }

    if ($exists == false) {
        $stmt = $pdo->prepare("INSERT INTO users (name, avatar) VALUES (?, ?)");
        $result = $stmt->execute(array($username, $avatar));
        if (!$result) {
            echo "\nPDO::errorInfo():\n";
            print_r($stmt->errorInfo());
        }
    }

    // Set Admin Perms if MASTER_USER_NAME or if in staff table.
    if (MASTER_USER_NAME == $user->login) {
        $_SESSION['adminperms'] = 1;
    }

    if (isStaff($username)) {
        $_SESSION['staffperms'] = 1;
    }


    header('Location: ../index.php?success=login');
}
