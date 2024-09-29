<?php
	if (isset($_SESSION['mensagem'])):
?>

<div class="alert alert-warning alert-dismissible fade show" role="alert">
	<?= $_SESSION['mensagem']; ?>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?php
	unset($_SESSION['mensagem']);
	endif;

	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
