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
	$actionMessage = '<div class="alert alert-success alert-dismissible fade show" style="text-align: center; font-size: 18px;" role="alert">Action Successful!</div>';
}

$documents = $pdo->query("SELECT * FROM documents");
$documentsselect = $pdo->query("SELECT * FROM documents");
$documentsselect2 = $pdo->query("SELECT * FROM documents");
$headers = $pdo->query("SELECT * FROM headers ORDER BY documentID ASC");
$headersselect = $pdo->query("SELECT * FROM headers ORDER BY documentID ASC");
$sections = $pdo->query("SELECT * FROM sections ORDER BY documentID ASC");

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $site_name; ?> | Documents</title>

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

			<div class="page-intro single-col-max mx-auto">Document Creation</div>
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
							<h5 class="card-title text-center py-2">Add Document</h5>
							<p class="card-text text-center">This will create the base document page.</p>
							<form action="../actions/functions.php" method="post">
								<div class="row justify-content-center">
									<div class="form-group col-md-12 mb-3">
										<label for="doc_name">Doc. Name</label>
										<input type="text" name="doc_name" class="form-control" placeholder="Eg. Documore" required>
									</div>
									<div class="form-group col-md-12">
										<label for="doc_description">Doc. Description</label>
										<input type="text" name="doc_description" class="form-control" placeholder="Eg. The following document contains..." required>
									</div>
								</div>
								<br>
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<button class="btn btn-info" type="submit" name="add_document">Add</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Current Documents</h5>
							<div class="table-responsive">
								<table class="table text-center">
									<tbody>
										<tr>
											<td>Name</td>
											<td>Description</td>
											<td>Whitelist</td>
											<td></td>
										</tr>
										<?php
										foreach ($documents as $row) {
											echo '<tr>
                                                <td class="table-text">' . $row['name'] . '</td>
                                                <td class="table-text">' . $row['description'] . '</td>';
											if ($row['whitelist'] == "1") {
												echo '<td><a class="btn btn-warning" onclick="whitelistDoc(`' . $row['ID'] . '`, `0`)">Turn Off</a></td>';
											} else {
												echo '<td><a class="btn btn-success" onclick="whitelistDoc(`' . $row['ID'] . '`, `1`)">Turn On</a></td>';
											}
											echo '<td><a class="btn btn-danger" onclick="deleteDocument(`' . $row['ID'] . '`)">Delete</a></td>
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

			<div class="row mt-4">
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Add Header</h5>
							<p class="card-text text-center">This will create a header for your document.</p>
							<form action="../actions/functions.php" method="post">
								<div class="row justify-content-center">
									<div class="form-group col-md-12 mb-3">
										<label for="header_doc">For Document</label>
										<select class="form-control" name="header_doc">
											<?php
											foreach ($documentsselect as $row) {
												echo '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
											}
											?>
										</select>
									</div>
									<div class="form-group col-md-12">
										<label for="header_title">Header Title</label>
										<input type="text" name="header_title" class="form-control" placeholder="Eg. Setup" required>
									</div>
								</div>
								<br>
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<button class="btn btn-info" type="submit" name="add_header">Add</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Current Headers</h5>
							<div class="table-responsive">
								<table class="table text-center">
									<tbody>
										<tr>
											<td>For Document</td>
											<td>Title</td>
											<td></td>
										</tr>
										<?php
										foreach ($headers as $row) {
											$documentID = $row['documentID'];

											$getdocuments = $pdo->query("SELECT * FROM documents WHERE ID='$documentID'");
											foreach ($getdocuments as $row2) {
												$docName = $row2['name'];
											}
											echo '<tr>
                                                <td class="table-text">' . $docName . '</td>
                                                <td class="table-text">' . $row['title'] . '</td>
                                                <td><a class="btn btn-danger" onclick="deleteHeader(`' . $row['ID'] . '`)">Delete</a></td>
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
			<div class="row mt-4">
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Add Section</h5>
							<p class="card-text text-center">This will create a section for your header.</p>
							<form action="../actions/functions.php" method="post">
								<div class="row justify-content-center">
									<div class="form-group col-md-12 mb-3">
										<label for="section_doc">For Document</label>
										<select class="form-control" name="section_doc">
											<?php
											foreach ($documentsselect2 as $row) {
												echo '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
											}
											?>
										</select>
									</div>
									<div class="form-group col-md-12 mb-3">
										<label for="section_header">For Header</label>
										<select class="form-control" name="section_header">
											<?php
											foreach ($headersselect as $row) {
												echo '<option value="' . $row['ID'] . '">' . $row['title'] . '</option>';
											}
											?>
										</select>
									</div>
									<div class="form-group col-md-12">
										<label for="section_title">Title</label>
										<input type="text" name="section_title" class="form-control" placeholder="Eg. Database" required>
									</div>
									<div class="form-group col-md-12">
										<label for="section_text">Text</label>
										<textarea type="text" name="section_text" class="form-control" placeholder="Eg. <b>Test</b>" required></textarea>
									</div>
								</div>
								<br>
								<div class="row justify-content-center">
									<div class="form-group col-md-6 text-center">
										<button class="btn btn-info" type="submit" name="add_section">Add</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title text-center py-2">Current Sections</h5>
							<div class="table-responsive">
								<table class="table text-center">
									<tbody>
										<tr>
											<td>For Document</td>
											<td>For Header</td>
											<td>Title</td>
											<td></td>
										</tr>
										<?php
										foreach ($sections as $row) {
											$documentID2 = $row['documentID'];
											$headerID = $row['headerID'];

											$getdocuments = $pdo->query("SELECT * FROM documents WHERE ID='$documentID'");
											foreach ($getdocuments as $row2) {
												$docName = $row2['name'];
											}
											$getheaders = $pdo->query("SELECT * FROM headers WHERE ID='$headerID'");
											foreach ($getheaders as $row2) {
												$headerTitle = $row2['title'];
											}
											echo '<tr>
                                                <td class="table-text">' . $docName . '</td>
                                                <td class="table-text">' . $headerTitle . '</td>
                                                <td class="table-text">' . $row['title'] . '</td>
                                                <td><a class="btn btn-danger" onclick="deleteSection(`' . $row['ID'] . '`)">Delete</a></td>
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

		function deleteDocument(id) {
			$.ajax({
				type: "POST",
				url: "../actions/functions.php",
				data: {
					deletedocument: id
				},
				success: function(res) {
					// success
					location.reload();
				}
			});
		}

		function deleteHeader(id) {
			$.ajax({
				type: "POST",
				url: "../actions/functions.php",
				data: {
					deleteheader: id
				},
				success: function(res) {
					// success
					location.reload();
				}
			});
		}

		function deleteSection(id) {
			$.ajax({
				type: "POST",
				url: "../actions/functions.php",
				data: {
					deletesection: id
				},
				success: function(res) {
					// success
					location.reload();
				}
			});
		}

		function whitelistDoc(id, status) {
			$.ajax({
				type: "POST",
				url: "../actions/functions.php",
				data: {
					whitelistdoc: id,
					updatestatus: status
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