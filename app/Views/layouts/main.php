<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title><?= $title ?? 'Student Management' ?></title>	
</head>
<style>
	.help-block{color:red;}
</style>
<body>
    <div class="container mt-5">	
        <?= $this->renderSection('content') ?>
		<footer class="mt-2"><!-- Copyright -->
		  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
			© <?php echo date('Y') ?> Copyright   
		  </div>
  <!-- Copyright -->
		</footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	
</body>
</html>
