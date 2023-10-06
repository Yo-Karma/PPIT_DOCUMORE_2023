<?php
session_start();
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../actions/settings.php";
require_once __DIR__ . "/../actions/functions.php";

if (!empty($_SESSION['user'])) {
	$user  = $_SESSION['user'];
}

// ACTION NOTIFICATIONS
if (isset($_GET['success'])) {
	$actionMessage = '<div class="alert alert-success alert-dismissible fade show" style="text-align: center; font-size: 18px;" role="alert">Added Successfully!</div>';
}

$staff = $pdo->query("SELECT * FROM staff");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $site_name; ?> | Permissions</title>

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
						<a href="../index.php" class="btn btn-outline-primary">Back</a>
					</div>
					<div class="docs-logout-wrapper">
						<a href="../actions/logout.php" class="btn btn-outline-primary">Logout</a>
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

			<div class="page-intro single-col-max mx-auto">Permissions</div>
		</div>
	</div>

	<div class="page-content">
		<div class="container">
			<!-- Menu -->
			<?php include "menu.inc.php"; ?>

			<div class="row">
				<?php if ($actionMessage) {
					echo $actionMessage;
				} ?>
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Add Staff</h5>
							<p class="card-text">Insert github username for those who will have access to admin section!</p>
							<form action="../actions/functions.php" method="post">
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<label for="github_username">Username</label>
										<input type="text" name="github_username" class="form-control" placeholder="Eg. HamzDevelopment" required>
									</div>
								</div>
								<br>
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<button class="btn btn-info" type="submit" name="add_staff">Add</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Current Staff</h5>
							<div class="table-responsive">
								<table class="table text-center">
									<tbody>
										<tr>
											<td>Username</td>
											<td></td>
										</tr>
										<?php
										foreach ($staff as $row) {
											echo '<tr>
                                                <td class="table-text">' . $row['username'] . '</td>
                                                <td><a class="btn btn-danger" onclick="deleteStaff(`' . $row['ID'] . '`)">Delete</a></td>
                                                </tr>';
										}
										?>
									</tbody>
								</table>
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
	<script>
		function deleteStaff(id) {
			$.ajax({
				type: "POST",
				url: "../actions/functions.php",
				data: {
					deletestaff: id
				},
				success: function(res) {
					// success
					location.reload();
				}
			});

		}
	</script>
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