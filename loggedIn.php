<?php
	session_start();
	if (!isset($_SESSION['verificationStatus'])) {
		header('location:index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Email Verification</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<p> Welcome <br><?php echo($_SESSION['email']); ?> </p>
	<p> Your Email is Verified! </p>
</body>
</html>