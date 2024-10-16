<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
<h3>Student Listing</h3>
<table class="styled-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Section</th>
            <th>Class</th>
        </tr>
    </thead>
    <tbody>
	<?php  foreach($students as $row){?>
        <tr>
            <td><?php echo $row['fname'];?></td>
            <td><?php echo $row['section'];?></td>
            <td><?php echo $row['class'];?></td>
        </tr>
	<?php }  ?>        
    </tbody>
</table>
</body>
</html>