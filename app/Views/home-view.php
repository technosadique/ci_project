
<!DOCTYPE html>
<html>
<head>
    <title>Listing</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
<?php include "header.php"; ?>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<div class="card-header">
			<?php if (session()->getFlashdata('message') !== NULL) : ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?php echo session()->getFlashdata('message'); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			<?php endif; ?>
                        <h4>
                            Student Listing
                            <a href="<?php echo BASE_URL?>add_student" class="btn btn-primary float-right">
                                Add
                            </a>
							
							 <a href="<?php echo BASE_URL?>generate_pdf" target="_blank" class="btn btn-success float-right" style="margin:0px 5px 0 0;">
                                Generate PDF
                            </a>
							
							<a href="<?php echo BASE_URL?>generate_csv" class="btn btn-secondary float-right" style="margin:0px 5px 0 0;">
                                CSV
                            </a>
                        </h4>
                    </div>
				<div class="card-body">
				<table class="table table-bordered table-striped">
				 <tr>
				 <th><a href="?sort=id&order=<?= $order ?>">Id</a></th>
				 <th><a href="?sort=fname&order=<?= $order ?>">Name</a></th>
				 <th><a href="?sort=class&order=<?= $order ?>">Class</a></th>
				 <th><a href="?sort=section&order=<?= $order ?>">Section</a></th>
				 <th>Action</th>
				 
				 </tr>
				 <?php foreach($students as $row){  ?>
				 <tr><td><?php echo $row['id']?></td><td><?php echo $row['fname']?></td><td><?php echo $row['class']?></td><td><?php echo $row['section']?></td><td><a href="<?php echo BASE_URL?>home/edit/<?php echo $row['id'];?>">Edit</a> | <a href="<?php echo BASE_URL?>home/remove/<?php echo $row['id'];?>">Delete</a></td></tr>
				 
				 <?php } ?>
				 </table>
				 <?= $pager->links() ?>
				</div>
				
			</div>
		</div>
	</div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
