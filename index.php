<?php
/*
 * Created on Jun 5, 2012
 *
 * Author: chens
 * File Name: index.php
 *
 */
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	<meta name="GENERATOR" content="PHPEclipse 1.2.0" />
	<title>Guess My Number</title>
	<link rel="stylesheet" type="text/css" href="css/common.css" />
	<style type="text/css">
		form {
			background-color: #FFD699;
}
		.hint, .attempt {
			position: relative;
			top: 10px;
			padding: 20px;
			margin: 10px;
}
		.hint p {
			padding: 0;
			margin: 0;
}
		.attempt {
			color: #006B24;
			background-color: #99D6AD;
}
		.high {
			color: #990000;
			background-color: #FFCCCC;
}
		.low {
			color: #000099;
			background-color: #CCCCFF;
}
	</style>
</head>
<body>

<h1>Guess The Mystery Number</h1>
<p>The computer will pick a number between 1 and 100 randomly, and you have 5 attempts to guess that mystery number.</p>

<?php
	//number of attempts for user to try to guess the mystery number
	$numberOfAttempts = 5;
	//an array of guessing numbers
	//$guessingNumbers = array();
	//
	$currentAttemptNumber = 1;
	//key number for calculating the mystery number
	define("KEY_CONSTANT", "12345");
	
	/**
	 * request for a new random number
	 */
	function getMysteryNumber() {
		return rand(111111, 999999);
	}
	
	
	/**
	 * compares two number and returns a hint to user if the guessing number is too high or too low
	 * @param int_type $userNumber
	 * @param int_type $mysteryNumber
	 * @return string
	 */
	function displayHint($userNumber, $mysteryNumber) {
		global $numberOfAttempts;
		//how many attempt(s) left
		if ( $numberOfAttempts - $_POST["attemptCounter"] != 1) {
			//$attemptLeft = "Your have " . ( $numberOfAttempts - $_POST["attemptCounter"] ) . " more attempts.";
			$attemptLeft = "<div class=\"attempt\"><h4>Attempts Left: ".( $numberOfAttempts - $_POST["attemptCounter"] )."</h4></div>";
		} else {
			$attemptLeft = "<div class=\"attempt\"><h3>This is your last attempt!</h3></div>";
		}
		
		//is the number bigger or smaller or empty
		if ( $userNumber > $mysteryNumber ){
			$hint = $attemptLeft .
					"<div class=\"hint high\">
					<p>Your previous guessing number " . $userNumber . " is higher than the mystery number.</p>
					</div>";
		} elseif($userNumber == NULL) {
			$hint = $attemptLeft . 
					"<div class=\"hint low\">
					<p>You did not enter a guessing number previously.</p>
					</div>";
		} else {
			$hint = $attemptLeft .
					"<div class=\"hint low\">
					<p>Your previous guessing number " . $userNumber . " is lower than the mystery number.</p>
					</div>";
		}
		
		return $hint;
		
	}

	
	if ( isset( $_POST["send"] ) && ( $_POST["myNumber"] == (int)($_POST["mysteryNumber"]/KEY_CONSTANT) ) ) {
		displayYouWin(); //grr...you got it
	} else {
		//
		if ( isset( $_POST["attemptCounter"] ) ) {
			$currentAttemptNumber = $_POST["attemptCounter"] + 1;
		}
		//
		if ( isset( $_POST["attemptCounter"] ) && ( $_POST["attemptCounter"] == $numberOfAttempts ) ) {
			displayYouLoose(); //hahhaha you lost
		} else {
			if ( isset( $_POST["send"] ) && ( $_POST["attemptCounter"] > 0 ) ) {
				$mysteryNumber = $_POST["mysteryNumber"];
			} else {
				$mysteryNumber = getMysteryNumber();
			}
			//
			playGame($mysteryNumber, $currentAttemptNumber, "");		
		}
	}

	function displayGuessingNumbers( $number, $numbersString ) {
		global $guessingNumbers;
		
		$guessingNumbers = explode(",", $numbersString);
		echo "<pre>";
		print_r($guessingNumbers);
		echo "</pre>";
		
		$guessingNumbers[] = $number;
		
		$numbersString = implode( ",", $guessingNumbers );
		
		//playGame( (int)($_POST["mysteryNumber"]/KEY_CONSTANT), $_POST["attemptCounter"], $numbersString );
		
	}
	/**
	 * Letting the user to enter a gussing number
	 * @param integer type $theMysteryNumber to store the mystery number generated randomly
	 * @param integer type $Attempt to store the Attempt counter
	 */
	function playGame( $theMysteryNumber, $theAttemptNumber, $guessingNumbers ) {
		//global $numberOfAttempts, $guessingNumbers;		
?>

<form action="index.php" method="post">
	<div style="width: 30em">
		<input type="hidden" name="mysteryNumber" id="mysteryNumber" value="<?php echo $theMysteryNumber; ?>" />
		<input type="hidden" name="attemptCounter" id="attemptCounter" value="<?php echo $theAttemptNumber; ?>" />
		<input type="hidden" name="guessingNumbers" id="guessingNumbers" value="<?php echo $guessingNumbers; ?>" />
		<label>What is your guess?</label>
		<input type="text" name="myNumber" id="myNumber" value="" />
		<div>
			<input type="submit" name="send" value="Send" />
		</div>
	</div>
</form>
<?php
	
	//display hint
	if ( isset( $_POST["attemptCounter"] ) && $_POST["attemptCounter"] > 0 ){
		echo displayHint( $_POST["myNumber"], (int)($_POST["mysteryNumber"]/KEY_CONSTANT) );
	}
	 
	}
	
	
	function displayYouWin() {
		?>
		<h3>Congratulations, you have guessed the mystery number.</h3>
		<p>The myster number is <?php echo (int)($_POST["mysteryNumber"]/KEY_CONSTANT); ?>.</p>
		<form action="index.php" method="post">
			<div>
				<input type="submit" name="newGame" value="New Game" />
			</div>
		</form>
		<?php
	}

	function displayYouLoose(){
	?>
		<h3>Game Over</h3>
		<p>The myster number is <?php echo (int)($_POST["mysteryNumber"]/KEY_CONSTANT); ?>.</p>
		<form action="index.php" method="post">
			<div>
				<input type="submit" name="newGame" value="New Game" />
			</div>
		</form>
	<?php }?>
</body>
</html>