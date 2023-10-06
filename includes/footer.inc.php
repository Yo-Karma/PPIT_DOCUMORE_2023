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

$sql = "SELECT * FROM social_media";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$social_media = $stmt->fetchAll();

$github_link = $social_media[0]['github'];
$twitter_link = $social_media[0]['twitter'];
$email_link = $social_media[0]['email'];
$instagram_link = $social_media[0]['instagram'];

?>


<footer class="footer">
    <div class="footer-bottom text-center py-5">
        <ul class="social-list list-unstyled pb-4 mb-0">
            <?php
            if ($github_link != "") {
                echo '<li class="list-inline-item"><a target="_blank"  href="' . $github_link . '"><i class="fab fa-github fa-fw"></i></a></li>';
            }
            if ($twitter_link != "") {
                echo '<li class="list-inline-item"><a target="_blank"  href="' . $twitter_link . '"><i class="fab fa-twitter fa-fw"></i></a></li>';
            }
            if ($email_link != "") {
                echo '<li class="list-inline-item"><a target="_blank"  href="mailto:' . $email_link . '"><i class="fas fa-envelope fa-fw"></i></a></li>';
            }
            if ($instagram_link != "") {
                echo '<li class="list-inline-item"><a target="_blank"  href="' . $instagram_link . '"><i class="fab fa-instagram fa-fw"></i></a></li>';
            }
            ?>
        </ul>
        <small class="copyright">Documore | Made with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by Hamiz & Daniel</small>
    </div>
</footer>
<!-- Xiaoying Riley -->