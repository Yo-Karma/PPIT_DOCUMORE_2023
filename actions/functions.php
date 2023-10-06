<?php
session_start();
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/settings.php");

// SQL CONNECTION \\
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

function isStaff($username)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE username=?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function isProd()
{
    if (ENVIRONMENT == 'production') {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['add_staff'])) {
    if ($_SESSION['adminperms'] == 1) {
        addStaff();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['add_whitelist'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        addWhitelist();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['deletestaff'])) {
    if ($_SESSION['adminperms'] == 1) {
        deleteStaff();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['deletewhitelist'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        deleteWhitelist();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['update_site'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        updateSite();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['add_document'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        addDocument();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['deletedocument'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        deleteDocument();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['add_header'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        addHeader();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['deleteheader'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        deleteHeader();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['add_section'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        addSection();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['deletesection'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        deleteSection();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['whitelistdoc'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        whitelistDoc();
    } else {
        header('Location: ../index.php');
    }
}

if (isset($_POST['update_socials'])) {
    if ($_SESSION['adminperms'] == 1 || $_SESSION['staffperms'] == 1) {
        updateSocials();
    } else {
        header('Location: ../index.php');
    }
}

function updateSocials()
{
    $social_email = htmlspecialchars($_POST['social_email']);
    $social_twitter = htmlspecialchars($_POST['social_twitter']);
    $social_instagram = htmlspecialchars($_POST['social_instagram']);
    $social_github = htmlspecialchars($_POST['social_github']);

    global $pdo;

    $stmt = $pdo->prepare("DELETE FROM social_media");
    $stmt->execute();

    $stmt = $pdo->prepare("INSERT INTO social_media (email, twitter, instagram, github) VALUES (?, ?, ?, ?)");
    $stmt->execute([$social_email, $social_twitter, $social_instagram, $social_github]);


    header('Location: ../admin/main.php?success');
}

function whitelistDoc()
{
    global $pdo;

    $whitelistdoc = htmlspecialchars($_POST['whitelistdoc']);
    $updatestatus = htmlspecialchars($_POST['updatestatus']);

    // UPDATE DATABASE
    $stmt = $pdo->prepare("UPDATE documents SET whitelist=? WHERE ID=?");
    $stmt->execute([$updatestatus, $whitelistdoc]);
}

function addStaff()
{
    global $pdo;

    $github_username = htmlspecialchars($_POST['github_username']);

    $stmt = $pdo->prepare("INSERT INTO staff (username) VALUES (?)");
    $result = $stmt->execute(array($github_username));


    header('Location: ../admin/permissions.php?success');
}

function deleteStaff()
{
    global $pdo;

    $deletestaff = htmlspecialchars($_POST['deletestaff']);

    // if (!empty($_POST['token']) || hash_equals($_SESSION['token'], $_POST['token'])) {
    // DELETE FROM DATABASE
    $stmt = $pdo->prepare("DELETE FROM staff WHERE ID=? ");
    $stmt->execute([$deletestaff]);
    //}
}

function updateSite()
{
    global $pdo;

    $site_name = htmlspecialchars($_POST['site_name']);
    $accent_color = htmlspecialchars($_POST['accent_color']);
    $description = htmlspecialchars($_POST['description']);

    $stmt = $pdo->prepare("UPDATE settings SET site_name=?, accent_color=?, description=?");
    $stmt->execute([$site_name, $accent_color, $description]);

    header('Location: ../admin/main.php?success');
}

function addDocument()
{
    global $pdo;

    $name = htmlspecialchars($_POST['doc_name']);
    $description = htmlspecialchars($_POST['doc_description']);

    $stmt = $pdo->prepare("INSERT INTO documents (name, description) VALUES (?, ?)");
    $stmt->execute([$name, $description]);


    header('Location: ../admin/documents.php?success');
}

function addWhitelist()
{
    global $pdo;

    $section_doc = htmlspecialchars($_POST['section_doc']);
    $github_username = htmlspecialchars($_POST['github_username']);

    $stmt = $pdo->prepare("INSERT INTO whitelist (username, documentID) VALUES (?, ?)");
    $stmt->execute([$github_username, $section_doc]);


    header('Location: ../admin/whitelist.php?success');
}

function deleteWhitelist()
{
    global $pdo;

    $deletewhitelist = htmlspecialchars($_POST['deletewhitelist']);

    $stmt = $pdo->prepare("DELETE FROM whitelist WHERE ID=? ");
    $stmt->execute([$deletewhitelist]);
}


function deleteDocument()
{
    global $pdo;

    $deletedocument = htmlspecialchars($_POST['deletedocument']);

    // Reason for this is because if you delete a document, you want to delete all the headers and sections
    $stmt = $pdo->prepare("DELETE FROM headers WHERE documentID=?");
    $stmt->execute([$deletedocument]);

    $stmt = $pdo->prepare("DELETE FROM sections WHERE documentID=?");
    $stmt->execute([$deletedocument]);

    $stmt = $pdo->prepare("DELETE FROM documents WHERE ID=?");
    $stmt->execute([$deletedocument]);

    header('Location: ../admin/documents.php?success');
}

function addHeader()
{
    global $pdo;

    $doc = htmlspecialchars($_POST['header_doc']);
    $title = htmlspecialchars($_POST['header_title']);

    $stmt = $pdo->prepare("INSERT INTO headers (documentID, title) VALUES (?, ?)");
    $stmt->execute([$doc, $title]);

    header('Location: ../admin/documents.php?success');
}

function deleteHeader()
{
    global $pdo;

    $deleteheader = htmlspecialchars($_POST['deleteheader']);

    // Reason for this is because if you delete a header, you want to delete all the sections that are under that header
    $stmt = $pdo->prepare("DELETE FROM sections WHERE headerID=?");
    $stmt->execute([$deleteheader]);

    $stmt = $pdo->prepare("DELETE FROM headers WHERE ID=?");
    $stmt->execute([$deleteheader]);

    header('Location: ../admin/documents.php?success');
}

function addSection()
{
    global $pdo;

    $doc = htmlspecialchars($_POST['section_doc']);
    $header = htmlspecialchars($_POST['section_header']);
    $title = htmlspecialchars($_POST['section_title']);
    $text = htmlspecialchars($_POST['section_text']);

    $stmt = $pdo->prepare("INSERT INTO sections (documentID, headerID, title, text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$doc, $header, $title, $text]);


    header('Location: ../admin/documents.php?success');
}

function deleteSection()
{
    global $pdo;

    $deletesection = htmlspecialchars($_POST['deletesection']);

    $stmt = $pdo->prepare("DELETE FROM sections WHERE ID=?");
    $stmt->execute([$deletesection]);

    header('Location: ../admin/documents.php?success');
}
