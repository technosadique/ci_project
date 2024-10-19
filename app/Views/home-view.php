<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
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
				<form method="get" action="" class="mb-3">
					<div class="input-group col-sm-4">
						<input type="text" name="search" class="form-control" value="<?= esc($search) ?>" placeholder="Search...">
						<div class="input-group-append">
							<button class="btn btn-primary" type="submit">Search</button>
						</div>
					</div>
				</form>

				<table class="table table-bordered table-striped">
				 <tr>
				 <th><a href="?sort=id&order=<?= $order ?>">Id</a></th>
				 <th><a href="?sort=fname&order=<?= $order ?>">Name</a></th>
				 <th><a href="?sort=class&order=<?= $order ?>">Class</a></th>
				 <th><a href="?sort=section&order=<?= $order ?>">Section</a></th>
				 <th>Action</th>
				 
				 </tr>
				 <?php foreach($students as $row){  ?>
				 <tr><td><?php echo $row['id']?></td><td><?php echo $row['fname']?></td><td><?php echo $row['class']?></td><td><?php echo $row['section']?></td><td><a class="badge btn-primary" href="<?php echo BASE_URL?>home/edit/<?php echo $row['id'];?>">Edit</a> <a class="badge btn-danger" href="<?php echo BASE_URL?>home/remove/<?php echo $row['id'];?>" onclick="return confirm('Are you sure, you want to delete this?');">Delete</a></td></tr>
				 
				 <?php } ?>
				 </table>
				 <!-- Bootstrap Pagination -->
				 <?= $pager->links(); ?>
				 <?php /* ?>
				<nav>
					<ul class="pagination">
					
						<li class="page-item"><a class="page-link" href="<?php echo BASE_URL?>listing?page=1">1</a></li>
						<li class="page-item"><a class="page-link" href="<?php echo BASE_URL?>listing?page=2">2</a></li>   
						<li class="page-item"><a class="page-link" href="<?php echo BASE_URL?>listing?page=2">Next</a></li>
					</ul>
				</nav>
				<?php */  ?>
				</div>
				
			</div>
		</div>
	</div>
<?= $this->endSection() ?>