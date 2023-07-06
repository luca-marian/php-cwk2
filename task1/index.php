<!DOCTYPE html>
<html lang="en">
<head>
    <title>Web Programming using PHP - Coursework 2 - Task 1</title>
</head>
<body>
	<header>   
        <h1>Web Programming using PHP - Coursework 2 - Task 1 Dynamic Menu building</h1>
	</header>
	<main>
		<?php
		#Your PHP solution code should go here...
		$navLists = ['main'=>['home'=>'Home Page','study'=>'Study','res'=>'Research','sem'=>'Seminars','contact'=>'Contact'],
					 'study'=>['ug'=>'Undergraduate', 'pg'=>'Post Graduate', 'res'=>'Research Degrees'],
					 'res'=>['rStaff'=>'Staff','rProj'=>'Research Projects','rStu'=>'Research Students'],
					 'sem'=>['current'=>'Current Year','prev'=>'Previous Years']];
		?>
    </main> 
</body>
</html>