<!DOCTYPE html PUBLIC>
<html>
<head>
<title>Random Number</title>
<link type="text/css" rel="stylesheet" href="css/common.css" />
<style type="text/css">
h1, h2, h3, h4 {
	margin: 0;
	padding: 0;
}
#page-wrap {
	margin: 50px auto;
	width: 600px;
	padding: 10px;
	border: 2px solid #666;
	height: 400px;
	background-color: #F0F0F0;
}
.high {
	color: #990000;
	background-color: #EBCCCC;
}
.low {
	color: #000099;
	background-color: #E6E6FF;
}
.hint {
	display: block;
	clear: both;
	text-align: center;
}
.hint p {
	line-height: 5em;
	display: block;
	paddng: 10px auto;
}
.attempt-counter {
	text-align: right;
	padding-right: 50px;
	color: #009900;
	background-color: #CCEBCC;
}
form {
}
a, a:link {
	text-decoration: none;
}
a#start-game {
	display: block;
	width: 200px;
	height: 2em;
	line-height: 2em;
	text-align: center;
	border: 1px solid #666;
	position: relative;
	margin: 0 auto;
	background-color: #CCC;
	color: white;
	font-weight: strong;
	font-size: 2em;
}
section#top p{
	padding-left: 50px;
}
section#top, section#bottom {
	padding: 5px;
	height: 80px;
	position: relative;
}
section#mid {
	padding: 3%;
	height: 50%;
}
section#mid p {
	line-height: 2em;
	
}
label, input {
	margin: 15px 0;
	line-height: 2em;
	height: 2em;
}


</style>
</head>
<body>
<div id="page-wrap">
<section id="top">
<h1>Mystery Number</h1>
<p>Guess the mystery number.</p><hr />
</section>
<?php 

	$randomNum = rand(1, 100);
	$attemptLeft = 5;
	
	function playGame($chances) { 
?>
		
		<section id="mid">
<?php 
		if ( $chances > 1 ) {
			echo "<p class=\"attempt-counter\">You have $chances chances</p>";
		} else {
			echo "<p class=\"attempt-counter\">This is your last chance!</p>";
		}
?>	

			<form action="index.php" method="post">
				<label for="guessingnumber">What number will it be? (1-100)</label>
				<input name="guessingnumber" type="text" value="" />
				<div id="submitBtn">
					<input type="submit" name="submit" value="Send" />
				</div>
			</form>
		</section>
<?php 
		if ( isset( $_POST["guessingnumber"] ) ) {
			if ( $_POST["guessingnumber"] > $_COOKIE["randomNum"] ) {
				echo "<section id=\"bottom\"><p class=\"hint high\">You guessed too high.</p></div>";
			} else {
				echo "<section id=\"bottom\"><p class=\"hint low\">You guessed too low.</p></div>";
			}
		}
		
	} //<<-- end of playGame function --
	
	if( isset($_GET["action"]) and $_GET["action"] == "start" ) {
		
		playGame($attemptLeft);
	
	} elseif ( isset($_POST["submit"] ) && isset($_POST["guessingnumber"] ) ) {
		
		if ( ( $_POST["guessingnumber"] != $_COOKIE["randomNum"] ) ) {
			
			if ( $_COOKIE["chances"] > 1 ) {	
				$attemptLeft = $_COOKIE["chances"] - 1;
				setcookie("chances", $attemptLeft, 0, "/", "", false, true);
				playGame($attemptLeft);
			} else {
				echo "<section id=\"mid\"><p>Game Over!</p><p>The random number is " . $_COOKIE["randomNum"] . "</p></section>";
				echo "<section id=\"bottom\"><a href=\"index.php\" id=\"start-game\">NEW GAME</a></section>";
			}
		
		} else {
			echo "<section id=\"mid\"><p>You got it!</p><p>The random number is " . $_COOKIE["randomNum"] . "</p></section>";
			echo "<section id=\"bottom\"><a href=\"index.php\" id=\"start-game\">NEW GAME</a></section>";
		}

	} else {
		echo "<section id=\"mid\"><p>A random mystery number will be picked. You have 5 chances to guess that number.</p></section>";
		setcookie("randomNum", $randomNum, 0, "/", "", false, true);
		setcookie("chances", $attemptLeft, 0, "/", "", false, true);
?>
<section id="bottom"><a href="index.php?action=start" id="start-game">START</a></section>
<?php } ?>
</div>
</body>
</html>