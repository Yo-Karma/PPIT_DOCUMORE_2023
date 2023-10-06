<?php
session_start();
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../actions/settings.php";
require_once __DIR__ . "/../actions/functions.php";

if (!empty($_SESSION['user'])) {
	$user  = $_SESSION['user'];
}

if (empty($_GET['ID'])) {
	header("Location: ../index.php");
}

$documentID = $_GET['ID'];

$documents = $pdo->query("SELECT * FROM documents WHERE ID='$documentID'");

foreach ($documents as $row) {
	$name = $row['name'];
	$description = $row['description'];
	$whitelist = $row['whitelist'];
	$whitelist_check = null;
	if ($whitelist == "1")
	{
		$whitelistcheck = $pdo->query("SELECT * FROM whitelist WHERE documentID='$documentID' AND username='$user->login'");
		foreach($whitelistcheck as $row2)
		{
			$whitelist_check = $row2['ID'];
		}

		if ($whitelist_check == null) {
			header("Location: ../index.php");
		}
	}
	
}

$headers = $pdo->query("SELECT * FROM headers WHERE documentID='$documentID' ORDER BY ID ASC");
$headers2 = $pdo->query("SELECT * FROM headers WHERE documentID='$documentID' ORDER BY ID ASC");

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $site_name; ?> | <?php echo $name; ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="favicon.ico">
	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
	<!-- FontAwesome JS-->
	<script defer src="../assets/fontawesome/js/all.min.js"></script>
	<!-- CSS -->
	<link id="theme-style" rel="stylesheet" href="../assets/css/theme.css">
	<link id="main-style" rel="stylesheet" href="../assets/css/styles.css">
</head>

<body class="docs-page">
	<header class="header fixed-top">
		<div class="branding docs-branding">
			<div class="container-fluid position-relative py-2">
				<div class="docs-logo-wrapper">
					<div class="site-logo"><a class="navbar-brand" href="index.php"><!-- <img class="logo-icon me-2"
								src="assets/images/logo.png" alt="logo">--><span class="logo-text"><?php echo $site_name; ?></span></a></div>
				</div>
				<?php if (!empty($user)) { ?>
					<div class="docs-top-utilities d-flex justify-content-center align-items-center">
						<img class="rounded-circle" style="width: 50px; margin-right: 10px;" src="<?php echo $user->avatar_url; ?>">
						<p style="margin-top: 1rem;"><?php echo $user->login; ?></p>
					</div>
					<div class="docs-logout-wrapper" style="margin-right: 6em;">
						<a href="../index.php" class="btn btn-outline-info">Back</a>
					</div>
					<div class="docs-logout-wrapper">
						<a href="../actions/logout.php" class="btn btn-outline-info">Logout</a>
					</div>
				<?php } else { ?>
					<div class="docs-logout-wrapper" style="margin-right: 6em;">
						<a href="../index.php" class="btn btn-outline-info">Back</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</header>


	<div class="docs-wrapper">
		<!-- SIDE BAR -->
		<div id="docs-sidebar" class="docs-sidebar">
			<div class="top-search-box d-lg-none p-3">
				<form class="search-form">
					<input type="text" placeholder="Search the docs..." name="search" class="form-control search-input">
					<button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
				</form>
			</div>
			<nav id="docs-nav" class="docs-nav navbar">
				<ul class="section-items list-unstyled nav flex-column pb-3">
					<?php
					foreach ($headers as $row) {
						$headerID = $row['ID'];

						$headerhref = preg_replace('/\s+/', '', $row['title']);

						echo '<li class="nav-item section-title"><a class="nav-link scrollto" href="#' . $headerhref . '"><span
						class="theme-icon-holder me-2"><i class="fas fa-map-signs"></i></span>' . $row['title'] . '</a></li>';

						$sections = $pdo->query("SELECT * FROM sections WHERE documentID='$documentID' AND headerID='$headerID' ORDER BY ID ASC");

						foreach ($sections as $row2) {
							$sectionhref = preg_replace('/\s+/', '', $row2['title']);
							echo '<li class="nav-item"><a class="nav-link scrollto" href="#' . $sectionhref . '">' . $row2['title'] . '</a></li>';
						}
					}
					?>
				</ul>

			</nav>
		</div>

		<div class="docs-content">
			<div class="container">
				<article class="docs-article">
					<header class="docs-header">
						<h1 class="docs-heading"><?php echo $name; ?></h1>
						<section class="docs-intro">
							<p><?php echo $description; ?></p>
						</section>
					</header>
				</article>

				<?php
				foreach ($headers2 as $row) {
					$headerID2 = $row['ID'];

					$headerhref = preg_replace('/\s+/', '', $row['title']);

					echo '<article class="docs-article" id="' . $headerhref . '">
					<header class="docs-header">
						<h1 class="docs-heading">' . $row['title'] . '</h1>
					</header>';

					$sections2 = $pdo->query("SELECT * FROM sections WHERE documentID='$documentID' AND headerID='$headerID2' ORDER BY ID ASC");

					foreach ($sections2 as $row2) {
						$sectionhref = preg_replace('/\s+/', '', $row2['title']);
						echo '<section class="docs-section" id="' . $sectionhref . '">
							<h2 class="section-heading">' . $row2['title'] . '</h2>
							<p>' . htmlspecialchars_decode($row2['text']) . '</p>
						</section>';
					}
					echo '</article>';
				}
				?>

				<!-- FOOTER -->
				<?php include "../includes/footer.inc.php"; ?>
			</div>
		</div>
	</div>
	<!-- JS -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/plugins/popper.min.js"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="../assets/plugins/smoothscroll.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/highlight.min.js"></script>
	<script src="../assets/js/highlight-custom.js"></script>
	<script src="../assets/plugins/simplelightbox/simple-lightbox.min.js"></script>
	<script src="../assets/plugins/gumshoe/gumshoe.polyfills.min.js"></script>
	<script src="../assets/js/docs.js"></script>

</body>

</html>