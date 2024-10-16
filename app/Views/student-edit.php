
<!DOCTYPE html>
<html>
<head>
    <title>Edit</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<style>
	.help-block{color:red;}
	</style>
</head>
<body>
<div class="container mt-5">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<div class="card-header">
                        <h2>
                            Student Edit                            
                        </h2>
                    </div>
				<div class="card-body">
				
				<form method="post" action="<?php echo BASE_URL?>update_data">
				<div class="form-group">
					<label for="name">First Name</label>
					<input type="text" class="form-control" id="fname" name="fname" value="<?php echo $students['fname']; ?>" placeholder="Enter your first name" >
					<?php if(isset($validation) && $validation->hasError('fname')){  ?>
					<span><?php echo $validation->showError('fname')  ?></span>
					<?php } ?>
				</div>
				<div class="form-group">
					<label for="name">Last Name</label>
					<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $students['lname']; ?>" placeholder="Enter your last name" >
					<?php if(isset($validation) && $validation->hasError('lname')){  ?>
					<span><?php echo $validation->showError('lname')  ?></span>
					<?php } ?>
				</div>
				
				<div class="form-group">
					<label for="class">Class</label>
					<input type="text" class="form-control" id="class" name="class" value="<?php echo $students['class']; ?>" placeholder="Enter your class" >
					<?php if(isset($validation) && $validation->hasError('class')){  ?>
					<span><?php echo $validation->showError('class')  ?></span>
					<?php } ?>
				
				</div>
				<div class="form-group">
					<label for="section">Section</label>
					<input type="text" class="form-control" id="section" name="section" value="<?php echo $students['section']; ?>"  placeholder="Enter your section" >
					<?php if(isset($validation) && $validation->hasError('section')){  ?>
					<span><?php echo $validation->showError('section')  ?></span>
					<?php } ?>
					<input type="hidden" class="form-control" id="student_id" name="student_id" value="<?php echo $students['id']; ?>">
				</div>       
				<button type="submit" class="btn btn-primary">Submit</button>
				<a href="<?php echo BASE_URL?>" class="btn btn-secondary">Cancel</a> 
			    </form>
				
				
				</div>
				
			</div>
		</div>
	</div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js">
</html>
