<?php
session_start();
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/actions/settings.php";
require_once __DIR__ . "/actions/functions.php";

if (!empty($_SESSION['user'])) {
	$user  = $_SESSION['user'];
}

if ($_GET['search']) {
	$search = $_GET['search'];
	$documents = $pdo->query("SELECT * FROM documents WHERE name LIKE '%$search%' OR description LIKE '%$search%'");
} else {
	$documents = $pdo->query("SELECT * FROM documents");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $site_name; ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="favicon.ico">
	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
	<!-- FontAwesome JS-->
	<script defer src="assets/fontawesome/js/all.min.js"></script>
	<!-- CSS -->
	<link id="theme-style" rel="stylesheet" href="assets/css/theme.css">
	<link id="main-style" rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
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
					<?php if ($_SESSION['adminperms'] == 1 or $_SESSION['staffperms'] == 1) { ?>
						<div class="docs-logout-wrapper" style="margin-right: 6em;">
							<a href="admin/main.php" class="btn btn-outline-info">Admin</a>
						</div>
					<?php } ?>
					<div class="docs-logout-wrapper">
						<a href="actions/logout.php" class="btn btn-outline-info">Logout</a>
					</div>
				<?php } ?>
				<div class="docs-top-utilities d-flex justify-content-end align-items-center">
					<?php if (empty($user)) { ?>
						<a href="https://github.com/login/oauth/authorize?client_id=2beffd362ef51a721c94&redirect_uri=https://hamz.dev/documore/actions/register.php" class="btn btn-info d-none d-lg-flex">Login using Github</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</header>


	<div class="page-header theme-bg-dark py-5 text-center position-relative">
		<div class="theme-bg-shapes-right"></div>
		<div class="theme-bg-shapes-left"></div>
		<div class="container">
			<h1 class="page-heading single-col-max mx-auto"><?php echo strtoupper($site_name); ?></h1>
			<div class="page-intro single-col-max mx-auto"><?php echo $description; ?></div>
			<div class="main-search-box pt-3 d-block mx-auto">
				<form class="search-form w-100">
					<input type="text" placeholder="Search the docs..." name="search" class="form-control search-input">
					<button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
				</form>
			</div>
		</div>
	</div>


	<!-- ORIGINAL -->
	<div class="page-content">
		<div class="container">
			<div class="docs-overview py-5">
				<div class="row justify-content-center">

					<?php
					foreach ($documents as $row) {

						$check_id = $row['ID'];
						$whitelist = $row['whitelist'];

						if ($whitelist == "1") {
							$whitelistcheck = $pdo->query("SELECT * FROM whitelist WHERE documentID='$check_id' AND username='$user->login'");
							foreach ($whitelistcheck as $row2) {
								$whitelist_check = $row2['ID'];
							}
						}

						if (!empty($whitelist_check) || $whitelist == "0") {
					?>
							<div class="col-12 col-lg-4 py-3">
								<div class="card shadow-sm">
									<div class="card-body">
										<h5 class="card-title mb-3">
											<span class="theme-icon-holder card-icon-holder me-2">
												<i class="fas fa-map-signs"></i>
											</span>
											<span class="card-title-text"><?php echo $row['name']; ?></span>
										</h5>
										<div class="card-text">
											<?php echo $row['description']; ?>
										</div>
										<a class="card-link-mask" href="document/index.php?ID=<?php echo $row['ID']; ?>"></a>
									</div>
								</div>
							</div>
						<?php
						}
					}

					if (empty($check_id)) {
						?>
						<div class='d-flex justify-content-center align-items-center pt-5'>
							<h3>No doc's found.</h3>
						</div>
					<?php
					}
					?>

				</div>
			</div>

		</div>
	</div>


	<!-- FOOTER -->
	<?php include "includes/footer.inc.php"; ?>

	<!-- JS -->
	<script src="assets/plugins/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/smoothscroll.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/highlight.min.js"></script>
	<script src="assets/js/highlight-custom.js"></script>
	<script src="assets/plugins/simplelightbox/simple-lightbox.min.js"></script>
	<script src="assets/plugins/gumshoe/gumshoe.polyfills.min.js"></script>
	<script src="assets/js/docs.js"></script>
</body>

</html>