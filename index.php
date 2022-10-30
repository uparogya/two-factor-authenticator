<?php
	$code=$email=$userInput=$userInputErr=$otherErr="";
	$emailStatus = false;

	if ($_SERVER['REQUEST_METHOD']=='POST') {
		if (empty($_POST['userInput'])) {
			$userInputErr = "email is blank";
		}else{
			$userInput = trim(stripslashes(htmlspecialchars($_POST['userInput'])));
			if (!filter_var($userInput, FILTER_VALIDATE_EMAIL)) {
				$userInputErr = "incorrect email format";
			}else{
				if ((!preg_match("/@gmail.com/i", strtolower($userInput))) && (!preg_match("/@yahoo.com/i", strtolower($userInput))) && (!preg_match("/@icloud.com/i", strtolower($userInput))) && (!preg_match("/@outlook.com/i", strtolower($userInput))) && (!preg_match("/@hotmail.com/i", strtolower($userInput))) && (!preg_match("/@protonmail.com/i", strtolower($userInput))) && (!preg_match("/@aol.com/i", strtolower($userInput))) && (!preg_match("/@yandex.com/i", strtolower($userInput))) && (!preg_match("/@zohomail.com/i", strtolower($userInput)))) {
					$userInputErr = "invalid doamin";
				}else{
					$email = trim(stripslashes(htmlspecialchars(strtolower($userInput))));
					$emailStatus = true;
				}
			}
		}
	}

	if (isset($_POST['submit']) AND $emailStatus == true) {
		$code = rand(100000, 999999);
		session_start();
		$_SESSION['code'] = $code;
		$_SESSION['email'] = $email;
		//Mail Starts Here
		require_once "Mail.php";
		$from = "";//ENTER YOUR GMAIL ADDRESS HERE
		$to = $email;
		$host = "ssl://smtp.gmail.com";
		$port = "465";
		$username = '';//ENTER YOUR GMAIL ADDRESS HERE
		$password = ''; //ENTER YOUR GOOGLE APP TOKEN HERE
		$subject = "Your Verification Code";
		$body =' 
			<html>
				<head>
					<title> Verification Code </title>
					<style>
						body{
							padding: 0;
							margin: 0;
							background-color: #F8F8FF;
							display: flex;
							align-items: center;
							justify-content: center;
							height: 100vh;
							width: 100vw;
						}
						#content{
							text-align: center;
							display: flex;
							flex-direction: column;
							align-items: center;
							justify-content: space-evenly;
							width: 90vw;
							height 90vh;
							background-color: #eaf3ee;
							padding: 7vh 0;
							border: 2px solid #2E8B57;
							border-radius: 1em;
						}
						p{
							text-align: center;
							width: 45%;
							color: dimgray;
							font-family: Times New Roman;
							font-size: 1.05em;
							letter-spacing: 0.6px;
						}
						h3{
							text-align: center;
							color: #2E8B57;
							font-size: 2em;
							font-weight: lighter;
							font-family: sans-serif;
							font-variant: small-caps;
							letter-spacing: 2px;
						}
					</style>
				</head>
				<body>
					<div id="content">
						<h1>Verification Code</h1>
						<p>Enter the following code to verify your email address.</p>
						<h3>'.$_SESSION['code'].'</h3>
						<p>If you did not request to verify your email, you may disregard this email.</p>
					</div>
				</body>
			</html>
		';
		$headers = array ('MIME-Version' => 1.0,'Content-type' => 'text/html', 'charset' => 'UTF-8','From' => $from, 'To' => $to,'Subject' => $subject);
		$smtp = Mail::factory('smtp',
 			array ('host' => $host,
   				'port' => $port,
   				'auth' => true,
   				'username' => $username,
   				'password' => $password));
		$mail = $smtp->send($to, $headers, $body);
		//Mail Ends Here
		if (PEAR::isError($mail)) {
			$otherErr = "couldn't verify";
		} else {
 			header('location:authentication.php');
 			$_SESSION['verificationStatus'] = true;
		}
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
	<form method="POST" action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])); ?>" >
		<p>Enter your email address below!</p>
		<input type="text" name="userInput" placeholder="someone@example.com" value="<?php echo($userInput); ?>" ><br>
		<input type="submit" name="submit" value="Send Code">
		<p class="error"><?php echo($userInputErr); ?></p>
		<p class="error"><?php echo($otherErr); ?></p>
	</form>
</body>
</html>