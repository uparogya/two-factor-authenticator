<?php
	session_start();
	if (!isset($_SESSION['verificationStatus'])) {
		header('location:index.php');
	}

	$userInput = $userInputErr = "";
	$codeStatus = $wrongCode = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
		if (empty($_POST['userCode'])) {
			$userInputErr = "please enter the code";
		}else{
			$userInput = trim(stripslashes(htmlspecialchars($_POST['userCode'])));
			if (!preg_match("/^[0-9]*$/", $userInput)) {
				$userInputErr = "code is a 6 digit number";
			}else{
				$enteredCode = $userInput;
				$codeStatus = true;
			}
		}
	}

	if (isset($_POST['submit']) AND $codeStatus == true) {
		if ($_SESSION['code'] != $enteredCode) {
			$userInputErr = "invalid code";
			$wrongCode = true;
		}else{
			header('location:loggedIn.php');
		}
	}

	if (isset($_POST['resend'])) {
		session_unset();
		session_destroy();
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
	<h1>Email Verification</h1>
	<form method="POST" action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])); ?>">
		<p>A code has been sent to <br> <?php echo($_SESSION['email']); ?></p>
		<input type="text" name="userCode" placeholder="6 Digit Verification Code" value="<?php echo($userInput); ?>" maxlength="6"><br>
		<input type="submit" name="submit" value="Verify">
		<input type="submit" name="resend" value="Resend Code" style="display: <?php if ($wrongCode == true) {echo"inline";}else{echo"none";} ?>;">
		<p class="error"><?php echo($userInputErr); ?></p>
	</form>
</body>
</html>