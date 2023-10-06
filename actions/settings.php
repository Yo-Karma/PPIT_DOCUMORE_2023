<?php
require_once(__DIR__ . "/../config.php");

// SQL CONNECTION \\
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

// SETTINGS \\
$settings = $pdo->query("SELECT * FROM settings");

foreach ($settings as $row) {
    $site_name = $row['site_name'];
    $accent_color = $row['accent_color'];
    $description = $row['description'];
}

list($r, $g, $b) = sscanf($accent_color, "#%02x%02x%02x");
?>

<style>
    .theme-bg-dark {
        background-color: <?php echo $accent_color; ?> !important;
    }

    :root {
        --bs-link-color: <?php echo $accent_color; ?> !important;
        --bs-link-hover-color: <?php echo $accent_color; ?> !important;
    }

    .social-list li a {
        color: <?php echo $accent_color; ?> !important;
    }

    .btn-light {
        background: rgba(<?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, .1) !important;
        color: <?php echo $accent_color; ?> !important;
    }

    .docs-overview .card:hover {
        background: rgba(<?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, .1) !important;
    }

    .theme-icon-holder {
        color: <?php echo $accent_color; ?> !important;
        background: rgba(<?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, .1) !important;
    }

    .btn-outline-info {
        color: <?php echo $accent_color; ?> !important;
        border-color: <?php echo $accent_color; ?> !important;
    }

    .btn-outline-info:hover {
        background: <?php echo $accent_color; ?> !important;
        color: #fff !important;
    }

    .btn-info {
        background: <?php echo $accent_color; ?> !important;
        border-color: <?php echo $accent_color; ?> !important;
    }

    .btn-info:hover {
        background: <?php echo $accent_color; ?> !important;
        color: #fff !important;
    }

    .form-control {
        line-height: 1 !important;
    }

    .docs-nav .nav-item {
        margin-bottom: 1rem !important;
    }
</style>