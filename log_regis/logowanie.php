<?php
	session_start();
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
		header('Location:../glowna/glowna.php');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Logowanie</title>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<link rel="Stylesheet" href="../style.css">

</head>
<body style="	background-image: url('../img/tlo1.jpg'); background-size:cover; background-repeat:no-repeat; overflow:hidden;">
	<h1>Logowanie</h1>
	<div id="login">
			<div class="logo">
			MY FARM
			</div>
			<form action="zaloguj.php" method="post">

			Login: <br /> <input type="text" name="login" class="form" required/> <br />
			Hasło: <br /> <input type="password" name="haslo" class="form"required /> <br />
			<center>
				<div class="g-recaptcha" data-sitekey="6Led5L0UAAAAAEKJVzYux240RnmVZ2BSNiIDyrIq" style="margin-left:auto; margin-right:auto;"></div>
			</center>
			<br/>
			<?php
			if (isset($_SESSION['e_bot']))
			{
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);
			}
			?>

			<input type="submit" value="Zaloguj się" class="form" /><br /><br />


		</form>
		<?php
			if(isset($_SESSION['blad']))
			{
				echo $_SESSION['blad']."<br />";
			}


			if (isset($_SESSION['udanarejestracja']))
			{
				echo"Udana rejestracja! Zapraszamy do pierwszego logowania"	;
				unset($_SESSION['udanarejestracja']);
			}
			else
			{
				print'<a href="rejestracja.php">Zarejestruj się<br /> <input type="button" value="Zarejestruj się" class="form" /> </a><br />';
				print'<a href="wyslij_e.php">Przypomnij hasło<br /> <input type="button" value="Zmień hasło" class="form" /> </a>';
			}







		?>
	</div>





</body>
</html>
