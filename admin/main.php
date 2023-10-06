<?php
session_start();
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../actions/settings.php";
require_once __DIR__ . "/../actions/functions.php";

if (!empty($_SESSION['user'])) {
	$user  = $_SESSION['user'];
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
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $site_name; ?> | Admin</title>

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

<body>
	<header class="header fixed-top">
		<div class="branding docs-branding">
			<div class="container-fluid position-relative py-2">
				<div class="docs-logo-wrapper">
					<div class="site-logo"><a class="navbar-brand" href="../index.php"><!-- <img class="logo-icon me-2"
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
				<?php } ?>
			</div>
		</div>
	</header>


	<div class="page-header theme-bg-dark py-5 text-center position-relative">
		<div class="theme-bg-shapes-right"></div>
		<div class="theme-bg-shapes-left"></div>
		<div class="container">
			<h1 class="page-heading single-col-max mx-auto">Admin</h1>
			<div class="page-intro single-col-max mx-auto">General Settings</div>
		</div>
	</div>

	<div class="page-content">
		<div class="container">
			<!-- Menu -->
			<?php include "menu.inc.php"; ?>

			<div class="row">

				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Current Site Details</h5>
							<!-- Display the current site name and accent colour -->

							<div class="justify-content-center text-align-left pt-3">
								<p class="text-center"><b>Site Name:</b> <?php echo $site_name; ?></p>
								<p class="text-center"><b>Accent Colour:</b> <span style="color: <?php echo $accent_color; ?>;"><?php echo $accent_color; ?></span></p>
								<p class="text-center"><b>Description:</b> <?php echo $description; ?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Change Site Details</h5>
							<p class="card-text text-center">Make your site your own by changing these details.</p>
							<form action="../actions/functions.php" method="post">
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<label for="accent_color">Accent Colour</label>
										<input type="text" name="accent_color" class="form-control" placeholder="Eg. #000FF" value="<?php echo $accent_color; ?>" required>
									</div>

									<div class="form-group col-md-6 text-center">
										<label for="site_name">Site Name</label>
										<input type="text" name="site_name" class="form-control" placeholder="Eg. Documore" value="<?php echo $site_name; ?>" required>
									</div>

									<div class="form-group col-md-12 text-center pt-2">
										<label for="description">Description</label>
										<input type="text" name="description" class="form-control" placeholder="" value="<?php echo $description; ?>" required>
									</div>
								</div>

								<br>

								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<button type="submit" name="update_site" class="btn btn-info btn-block">Update</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-md-12">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Update Social Media Links</h5>
							<p class="card-text text-center">This will create a header for your document.</p>
							<form action="../actions/functions.php" method="post">
								<div class="row">
									<div class="form-group col-md-3 text-center">
										<label for="social_email">Email</label>
										<input type="text" name="social_email" class="form-control" placeholder="None" value="<?php echo $email_link; ?>">
									</div>
									<div class="form-group col-md-3 text-center">
										<label for="social_twitter">Twitter</label>
										<input type="text" name="social_twitter" class="form-control" placeholder="None" value="<?php echo $twitter_link; ?>">
									</div>
									<div class="form-group col-md-3 text-center">
										<label for="social_instagram">Instagram</label>
										<input type="text" name="social_instagram" class="form-control" placeholder="None" value="<?php echo $instagram_link; ?>">
									</div>
									<div class="form-group col-md-3 text-center">
										<label for="social_github">Github</label>
										<input type="text" name="social_github" class="form-control" placeholder="None" value="<?php echo $github_link; ?>">
									</div>
								</div>
								<br>
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<button class="btn btn-info" type="submit" name="update_socials">Update</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>


	</div>
	</div>

	<!-- FOOTER -->
	<?php include "../includes/footer.inc.php"; ?>

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