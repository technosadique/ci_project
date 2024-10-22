<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
		<?php include "header.php"; ?>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<div class="card-header">
                        <h2>
                            Student Add                            
                        </h2>
                    </div>
				<div class="card-body">
				
				<form method="post" action="<?php echo BASE_URL?>save_data" enctype="multipart/form-data">
				<div class="form-group">
					<label for="name">First Name</label>
					<input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your first name">
					<?php if(isset($validation) && $validation->hasError('fname')){  ?>
					<span><?php echo $validation->showError('fname')  ?></span>
					<?php } ?>
				</div>
				<div class="form-group">
					<label for="name">Last Name</label>
					<input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your last name">
					<?php if(isset($validation) && $validation->hasError('lname')){  ?>
					<span><?php echo $validation->showError('lname')  ?></span>
					<?php } ?>
				</div>
				
				<div class="form-group">
					<label for="class">Class</label>
					<input type="text" class="form-control" id="class" name="class" placeholder="Enter your class">
					<?php if(isset($validation) && $validation->hasError('class')){  ?>
					<span><?php echo $validation->showError('class')  ?></span>
					<?php } ?>
				</div>
				<div class="form-group">
					<label for="section">Section</label>
					<input type="text" class="form-control" id="section" name="section"  placeholder="Enter your section">
					<?php if(isset($validation) && $validation->hasError('section')){  ?>
					<span><?php echo $validation->showError('section')  ?></span>
					<?php } ?>
				</div> 

				<div class="form-group">
					<label for="section">Upload Image</label>
					<input type="file" class="form-control" id="image" name="image">
					
				</div>
				
				<button type="submit" class="btn btn-primary">Submit</button> 
				<a href="<?php echo BASE_URL?>listing" class="btn btn-secondary">Cancel</a> 
			    </form>
				
				
				</div>
				
			</div>
		</div>
	</div>

<?= $this->endSection() ?>