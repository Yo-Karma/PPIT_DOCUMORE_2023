<input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
<div class="text-center pt-4">
    <a class="btn btn-light m-2" href="main.php">General Settings</a>

    <?php if (isset($_SESSION['adminperms']) && $_SESSION['adminperms'] == 1) { ?>
        <a class="btn btn-light m-2" href="permissions.php">Permissions</a>
    <?php } ?>

    <a class="btn btn-light m-2" href="documents.php">Document Creation</a>

    <a class="btn btn-light m-2" href="whitelist.php">Whitelisting</a>
</div>
<hr>