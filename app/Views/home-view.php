
<!DOCTYPE html>
<html>
<head>
    <title>Listing</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
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
				 <tr><th>Id</th><th>Name</th><th>Class</th><th>Section</th><th>Action</th></tr>
				 <?php foreach($students as $row){  ?>
				 <tr><td><?php echo $row['id']?></td><td><?php echo $row['fname']?></td><td><?php echo $row['class']?></td><td><?php echo $row['section']?></td><td><a href="<?php echo BASE_URL?>home/edit/<?php echo $row['id'];?>">Edit</a> | <a href="<?php echo BASE_URL?>home/remove/<?php echo $row['id'];?>">Delete</a></td></tr>
				 
				 <?php } ?>
				 </table>
				</div>
				
			</div>
		</div>
	</div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js">
</html>
