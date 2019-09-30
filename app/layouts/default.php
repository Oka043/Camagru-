<?php 
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Cimpatible" content="ie=edge">
	<!-- <link rel="stylesheet" type="text/css" href="/public/css/resets.css"> -->
	<link rel="stylesheet" type="text/css" href="/public/css/style.css">
	<link rel="stylesheet" type="text/css" href="/public/css/style_1024.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	
	<title><?=$title ?></title>
</head>
<body>
	<?php 
		require_once ROOT_PATH."/app/views/additional/header.php";
		echo $content;
		require_once ROOT_PATH."/app/views/additional/footer.php";
	?>
</body>
</html>